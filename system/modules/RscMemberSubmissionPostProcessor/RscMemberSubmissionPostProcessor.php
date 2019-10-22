<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Cliff Parnitzky 2012
 * @author     Cliff Parnitzky
 * @package    RscMemberSubmissionPostProcessor
 * @license    LGPL
 * @filesource
 */


/**
 * Class UserMemberBridgeSyncronizer
 *
 * Provide methods to syncronize data between member and user.
 * @copyright  Cliff Parnitzky 2012
 * @author     Cliff Parnitzky
 * @package    RscMemberSubmissionPostProcessor
 */
class RscMemberSubmissionPostProcessor extends Backend
{
	public function __construct()
	{
		parent::__construct();
		$this->loadLanguageFile("tl_settings");
		$this->import('BackendUser', 'User');
		$this->import('Environment');
	} 

	/**
	 * Executes actions after a submission of a member.
	 * @param DataContainer
	 */
	public function memberSubmission(DataContainer $dc)
	{
		if ($this->isMemberInRelevantGroup($dc->activeRecord) && $dc->activeRecord->disable != 1)
		{
			$this->createResultListGeneratorMemberList();
			$this->synchronizeWebmailAddressbook($dc->activeRecord);
			$this->createWelcomeDocument($dc->activeRecord);
			$this->sendInformationMail($dc->activeRecord);
		}
		else if ($dc->activeRecord->disable == 1)
		{
			$this->synchronizeWebmailAddressbook($dc->activeRecord);
		}
	}
	
	/**
	 * Sends an information mail about the submission of the new member.
	 */
	private function sendInformationMail ($member)
	{
		if ($this->isActionAllowed('send_email') && $this->isMemberNew($member))
		{
			$objEmail = new Email();

			$objEmail->logFile = 'RscMemberSubmissionPostProcessorEmail.log';
			
			$objEmail->from = $GLOBALS['TL_CONFIG']['adminEmail'];
			$objEmail->fromName = $GLOBALS['TL_CONFIG']['websiteTitle'];
			$objEmail->subject = $this->replaceEmailInsertTags($GLOBALS['TL_CONFIG']['rscMemberSubmissionPostProcessorEmailSubject'], $member);
			$objEmail->html = $this->replaceEmailInsertTags($GLOBALS['TL_CONFIG']['rscMemberSubmissionPostProcessorEmailContent'], $member);
			$objEmail->text = $this->transformEmailHtmlToText($objEmail->html);
			
			try
			{
				$objEmail->sendTo(explode(',', $GLOBALS['TL_CONFIG']['rscMemberSubmissionPostProcessorEmailReceiver']));
				return true;
			}
			catch (Swift_RfcComplianceException $e)
			{
				$this->log("Mail could not be send: " . $e->getMessage(), "RscMemberSubmissionPostProcessor sendInformationMail()", TL_ERROR);
				return false;
			}
		}
	}
	
	/**
	 * Replaces all insert tags for the email text.
	 */
	private function replaceEmailInsertTags ($text, $member)
	{
		$textArray = preg_split('/\{\{([^\}]+)\}\}/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
		
		for ($count = 0; $count < count($textArray); $count++)
		{
			$parts = explode("::", $textArray[$count]);
			if ($parts[0] == "member")
			{
				if ($parts[1] == "password")
				{
					$textArray[$count] = '';
				}
				else if ($parts[1] == "dateOfBirth") {
					$textArray[$count] = date($GLOBALS['TL_CONFIG']['dateFormat'], $member->{$parts[1]});
				}
				else if ($parts[1] == "gender") {
					$textArray[$count] = $GLOBALS['TL_LANG']['MSC'][$member->{$parts[1]}];
				}
				else if ($parts[1] == "xt_club_swimflat") {
					$textArray[$count] = strlen($member->{$parts[1]}) ? $GLOBALS['TL_LANG']['MSC']['yes'] : $GLOBALS['TL_LANG']['MSC']['no'];
				}
				else
				{
					$value = $member->{$parts[1]};
					if (is_array($value)) {
						if ($parts[1] == "groups") {
							$value = $this->getArrayValueAsList("tl_member_group", "name", $value);
						}
					}
					$textArray[$count] = $value;
				}
			}
			else if ($parts[0] == "actions")
			{
				$allowedActions = deserialize($GLOBALS['TL_CONFIG']['rscMemberSubmissionPostProcessorActions'], true);
				if (is_array($allowedActions))
				{
					$actionsList = "<ul> ";
					foreach ($allowedActions as $allowedAction)
					{
						$actionsList .= "<li>" . $GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorActions'][$allowedAction] . "</li> ";
					}
					$actionsList .= "</ul>";
					$textArray[$count] = $actionsList;
				}
			}
		}
		
		return implode('', $textArray);
	}
	
	/**
	 * get all values of the given array
	 */
	private function getArrayValueAsList($table, $fieldname, $array)
	{
		if (strlen($table) > 0 && is_array($array) && count($array) > 0)
		{
			$ids = implode(", ", deserialize($array, true));
			$values = $this->Database->prepare("SELECT " . $fieldname . " FROM " . $table . " WHERE id IN (" . $ids . ") ORDER BY name ASC")
								->execute();
			$list = array();
			while ($values->next())
			{
				$list[] = $values->$fieldname;
			}
			return implode(", ", $list);
		}
		return "";
	}
	
	/**
	 * Creates the text from the html for the email.
	 */
	private function transformEmailHtmlToText ($emailHtml)
	{
		$emailText = $emailHtml;
		$emailText = str_replace("</p> ", "\n\n", $emailText);
		$emailText = str_replace("</ul> ", "\n", $emailText);
		$emailText = str_replace(" <li>", " - ", $emailText);
		$emailText = str_replace("</li>", "\n", $emailText);
		$emailText = strip_tags($emailText);
		return $emailText;
	}
	
	/**
	 * Create a new member list for result list generator.
	 */
	private function createResultListGeneratorMemberList ()
	{
		if ($this->isActionAllowed('create_resultlist_generator_userlist'))
		{
			$filePath = \FilesModel::findByUuid($GLOBALS['TL_CONFIG']['rscMemberSubmissionPostProcessorResultlistGeneratorUserlistFilePath'])->path . '/user.js';

			
			// first check if required extension 'associategroups' is installed
			if (!in_array('associategroups', $this->Config->getActiveModules()))
			{
				$this->log('The extension "associategroups" is required for exporting the user list!', 'RscMemberSubmissionPostProcessor createResultListGeneratorMemberList()', TL_ERROR);
				return false;
			}
			
			// Check whether the target file is writeable
			if (file_exists($filePath) && !$this->Files->is_writeable($filePath))
			{
				$this->log('Export of user list failed because the export file is not writable.', 'RscMemberSubmissionPostProcessor createResultListGeneratorMemberList()', TL_ERROR);
				return;
			}
			
			$objFile = new File($filePath);
			$objFile->write("/* Auto exportet userlist auf 'RSC Lüneburg e.V.' members */\n\n");
			
			// export of women
			$objFile->append("// select box for women");
			$objFile->append("var women = new Array(\"\",");
			$this->readUserAndAddToFile($objFile, 'female');
			$objFile->append(");\n");
			
			// export of men
			$objFile->append("// select box for men");
			$objFile->append("var men = new Array(\"\",");
			$this->readUserAndAddToFile($objFile, 'male');
			$objFile->append(");");

			$objFile->close();
		}
	}
	
	/**
	 * Reads the members from db, groupe by gender and writes the lines to the file.
	 * @param file The file to write to.
	 * @param gender The gender of the members.
	 */
	private function readUserAndAddToFile ($file, $gender)
	{
		$user = $this->Database->prepare("SELECT m.firstname, m.lastname "
										. "FROM tl_member m "
										. "LEFT JOIN tl_member_to_group m2g ON m2g.member_id = m.id "
										. "WHERE m.gender = ? AND m.disable = ? AND m2g.group_id = ? "
										. "ORDER BY m.firstname, m.lastname")
										->execute($gender, '', $GLOBALS['TL_CONFIG']['rscMemberSubmissionPostProcessorRelevantGroup'])->fetchAllAssoc();
										
		for ($i = 0; $i < sizeof($user); $i++)
		{
			$line = "\"" . $user[$i]['firstname'] . " " . $user[$i]['lastname'] . "\"";
			if ($i < (sizeof($user) - 1))
			{
				$line .= ",";
			}
			$file->append($line);
		}
	}

	/**
	 * Synchronizes the data of the member with the webmail addressbook.
	 */
	private function synchronizeWebmailAddressbook ($member)
	{
		if ($this->isActionAllowed('synchronize_webmail_addressbook'))
		{
			$memberName = $member->firstname . " " . $member->lastname;
			if ($this->isMemberNew($member))
			{
				$this->Database->prepare("INSERT INTO rcb_contacts (name, firstname, surname, email, rsc_member_number, user_id, changed) "
										. "VALUES (? , ?, ?, ?, ?, ?, now())")
										->execute(array($memberName, $member->firstname, $member->lastname, $member->email, $member->xt_club_membernumber, $GLOBALS['TL_CONFIG']['rscMemberSubmissionPostProcessorWebmailAddressbookOwner']));
				$this->Database->prepare("INSERT INTO rcb_contactgroupmembers (contactgroup_id, contact_id, created) "
										. "SELECT ?, contact_id, now() FROM rcb_contacts WHERE rsc_member_number = ?")
										->execute(array($GLOBALS['TL_CONFIG']['rscMemberSubmissionPostProcessorWebmailAddressbookGroupActive'], $member->xt_club_membernumber));
			}
			else
			{
				if ($member->disable == 1)
				{
					$this->Database->prepare("DELETE FROM rcb_contactgroupmembers WHERE contact_id IN (SELECT contact_id FROM rcb_contacts WHERE rsc_member_number = ?)")
											->execute(array($member->xt_club_membernumber));
					$this->Database->prepare("INSERT INTO rcb_contactgroupmembers (contactgroup_id, contact_id, created) "
											. "SELECT ?, contact_id, now() FROM rcb_contacts WHERE rsc_member_number = ?")
											->execute(array($GLOBALS['TL_CONFIG']['rscMemberSubmissionPostProcessorWebmailAddressbookGroupInactive'], $member->xt_club_membernumber));
				}
				$this->Database->prepare("UPDATE rcb_contacts "
										. "SET name = ?, firstname = ?, surname = ?, email = ?, changed = now() "
										. "WHERE rsc_member_number = ?")
										->execute(array($memberName, $member->firstname, $member->lastname, $member->email, $member->xt_club_membernumber));
			}
		}
	}

	/**
	 * Create the welcome document for the new member.
	 */
	private function createWelcomeDocument ($member)
	{
		if ($this->isActionAllowed('create_welcome_document') && $this->isMemberNew($member))
		{
			$timeNow = time();
		
			$arrSet = array
			(
				'sorting'         => 0,
				'tstamp'          => $timeNow,
				'fd_member'       => $this->User->assignedMember,
				'fd_user'         => 0,
				'fd_member_group' => 0,
				'fd_user_group'   => 0,
				'form'            => '',
				'ip'              => $this->Environment->ip,
				'date'            => $timeNow,
				'published'       => ($GLOBALS['TL_DCA']['tl_formdata']['fields']['published']['default'] == '1' ? '1' : '' ),
				'be_notes'        => 'Automatisch erstellter Datensatz (RscMemberSubmissionPostProcessor->createWelcomeDocument()'
			);
		
			$objNewFormdata = $this->Database->prepare("INSERT INTO tl_formdata %s")->set($arrSet)->execute();
			$intNewId = $objNewFormdata->insertId;
			
			// set form name and alias
			$this->Database->prepare("UPDATE tl_formdata SET form = (SELECT title FROM tl_form WHERE id = ?), alias = ? WHERE id = ?")
						   ->execute(array(intval($GLOBALS['TL_CONFIG']['rscMemberSubmissionPostProcessorWelcomeForm']), strval($intNewId), $intNewId));
			
			// now add the form data details
			$arrFormFields = $this->Database->prepare("SELECT * FROM tl_form_field WHERE pid = ? AND invisible = ''")
											->execute(intval($GLOBALS['TL_CONFIG']['rscMemberSubmissionPostProcessorWelcomeForm']))
											->fetchAllAssoc();
			
			foreach ($arrFormFields as $formField)
			{
				$value = '';
				if ($formField['name'] == 'member')
				{
					$value = $member->id;
				}
				else if ($formField['name'] == 'send')
				{
					$value = 'Nein';
				}
					
				$this->addWelcomeDocumentFormDataDetails($intNewId, $timeNow, $formField, strval($value));
			}
		}
	}
	
	/**
	 * Adds the form data details for a welcome document.
	 * 
	 * @param unknown_type $formDataId The id of the form data record.
	 * @param unknown_type $tstamp The actual time stamp.
	 * @param unknown_type $formField The form field to add the data for.
	 * @param unknown_type $strValue The value to set.
	 */
	private function addWelcomeDocumentFormDataDetails ($formDataId, $tstamp, $formField, $strValue)
	{
		$arrFieldSet = array
		(
				'pid'      => $formDataId,
				'sorting'  => $formField['sorting'],
				'tstamp'   => $tstamp,
				'ff_id'    => $formField['id'],
				'ff_type'  => $formField['type'],
				'ff_name'  => $formField['name'],
				'ff_label' => $formField['label'],
				'value'    => $strValue
		);
			
		$objNewFormdataDetails = $this->Database->prepare("INSERT INTO tl_formdata_details %s")->set($arrFieldSet)->execute();
	}
	
	/**
	 * Returns true, if the given member is new.
	 */
	private function isMemberNew ($member)
	{
		return ($member && $member->tstamp == 0);
	}
	
	/**
	 * Checks if the member is in the relvant group.
	 */
	private function isMemberInRelevantGroup ($member)
	{
		$memberGroups = deserialize($member->groups, true);
		if (is_array($memberGroups))
		{
			foreach ($memberGroups as $group)
			{
				if ($group == $GLOBALS['TL_CONFIG']['rscMemberSubmissionPostProcessorRelevantGroup'])
				{
					return true;
				}
			}
		}
		
		return false;
	}
	
	/**
	 * Returns if the given action is allowed.
	 */
	private function isActionAllowed ($action)
	{
		$allowedActions = deserialize($GLOBALS['TL_CONFIG']['rscMemberSubmissionPostProcessorActions'], true);
		if (is_array($allowedActions))
		{
			foreach ($allowedActions as $allowedAction)
			{
				if ($allowedAction == $action)
				{
					return true;
				}
			}
		}
		
		return false;
	}
}

?>