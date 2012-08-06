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
 * Add to palette
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{rscMemberSubmissionPostProcessor_legend},rscMemberSubmissionPostProcessorRelevantGroup, rscMemberSubmissionPostProcessorActions, rscMemberSubmissionPostProcessorResultlistGeneratorUserlistFilePath, rscMemberSubmissionPostProcessorWebmailAddressbookOwner, rscMemberSubmissionPostProcessorWebmailAddressbookGroupActive, rscMemberSubmissionPostProcessorWebmailAddressbookGroupInactive, rscMemberSubmissionPostProcessorWelcomeForm, rscMemberSubmissionPostProcessorEmailReceiver, rscMemberSubmissionPostProcessorEmailSubject, rscMemberSubmissionPostProcessorEmailContent;';

/**
 * Add fields
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['rscMemberSubmissionPostProcessorRelevantGroup'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorRelevantGroup'],
	'inputType'               => 'select',
	'foreignKey'              => 'tl_member_group.name',
	'filter'                  => true,
	'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50', 'includeBlankOption'=>true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['rscMemberSubmissionPostProcessorActions'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorActions'],
	'inputType'               => 'checkbox',
	'options'                 => array('create_resultlist_generator_userlist' => &$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorActions']['create_resultlist_generator_userlist'],
																		 'synchronize_webmail_addressbook' => &$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorActions']['synchronize_webmail_addressbook'],
																		 'create_welcome_document' => &$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorActions']['create_welcome_document'],
																		 'send_email' => &$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorActions']['send_email']
																		),	
	'eval'                    => array('multiple'=>true, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['rscMemberSubmissionPostProcessorResultlistGeneratorUserlistFilePath'] = array
(
	'label'               => &$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorResultlistGeneratorUserlistFilePath'],
	'inputType'           => 'fileTree',
	'eval'                => array('mandatory'=>true, 'tl_class'=>'clr', 'files'=>false, 'path'=>'tl_files', 'trailingSlash'=>true, 'fieldType'=>'radio', 'extensions'=>'')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['rscMemberSubmissionPostProcessorWebmailAddressbookOwner'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorWebmailAddressbookOwner'],
	'inputType'               => 'select',
	'foreignKey'              => 'rcb2cto_users.name',
	'filter'                  => true,
	'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50 clr', 'includeBlankOption'=>true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['rscMemberSubmissionPostProcessorWebmailAddressbookGroupActive'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorWebmailAddressbookGroupActive'],
	'inputType'               => 'select',
	'foreignKey'              => 'rcb2cto_contactgroups.name',
	'filter'                  => true,
	'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50 clr', 'includeBlankOption'=>true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['rscMemberSubmissionPostProcessorWebmailAddressbookGroupInactive'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorWebmailAddressbookGroupInactive'],
	'inputType'               => 'select',
	'foreignKey'              => 'rcb2cto_contactgroups.name',
	'filter'                  => true,
	'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50', 'includeBlankOption'=>true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['rscMemberSubmissionPostProcessorWelcomeForm'] = array
(
	'label'               => &$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorWelcomeForm'],
	'inputType'           => 'select',
	'foreignKey'          => 'tl_form.title',
	'eval'                => array('mandatory'=>true, 'tl_class'=>'w50 clr', 'includeBlankOption'=>true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['rscMemberSubmissionPostProcessorEmailReceiver'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorEmailReceiver'],
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'rgxp'=>'emailList', 'tl_class'=>'w50 clr')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['rscMemberSubmissionPostProcessorEmailSubject'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorEmailSubject'],
	'inputType'               => 'text',
	'reference'               => &$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessor']['help']['inserttags'],
	'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50', 'allowHtml'=>true, 'helpwizard'=>true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['rscMemberSubmissionPostProcessorEmailContent'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorEmailContent'],
	'inputType'               => 'textarea',
	'reference'               => &$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessor']['help']['inserttags'],
	'eval'                    => array('mandatory'=>true, 'rte'=>'tinyMCE', 'allowHtml'=>true, 'tl_class'=>'clr', 'helpwizard'=>true)
);
	
?>