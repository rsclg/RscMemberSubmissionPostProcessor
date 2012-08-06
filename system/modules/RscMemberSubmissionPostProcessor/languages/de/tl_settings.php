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
 */

/**
 * fields
 */
$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessor_legend']                             = "RSC Mitgliedererfassung Folgeprozess";
$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorRelevantGroup']                       = array('Relevante Mitgliedergruppe ', 'Wählen Sie die relevante Mitgliedergruppe, für welche der Folgeprozess ausgeführt werden soll.');
$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorActions']                             = array('Durchzuführende Aktionen', 'Wählen Sie welche Aktionen durchgeführt werden sollen.');
$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorResultlistGeneratorUserlistFilePath'] = array('Pfad der Mitgliederliste für den Ergebnislisten Generator', 'Wählen Sie den Pfad in dem die Mitgliederliste für den Ergebnislisten Generator erstellt werden soll.');
$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorWebmailAddressbookOwner']             = array('Webmail Adressbuch Besitzer', 'Wählen Sie den Besitzer des Webmail Adressbuchs.');
$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorWebmailAddressbookGroupActive']       = array('Webmail Adressbuch Kontakgruppe "Aktive"', 'Wählen Sie die Kontakgruppe, in der die neuen Mitglieder im Webmail Adressbuch eingetragen werden.');
$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorWebmailAddressbookGroupInactive']     = array('Webmail Adressbuch Kontakgruppe "Inaktive"', 'Wählen Sie die Kontakgruppe, in der die deaktivierte Mitglieder im Webmail Adressbuch eingetragen werden.');
$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorWelcomeForm']                         = array('Formular für das Begrüßungsschreiben', 'Wählen Sie das Formular, welches zur Erstellung des Begrüßungsschreiben verwendet wird.');
$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorEmailReceiver']                       = array('E-Mail Empfänger', 'Komma separierte Liste der Empfänger der E-Mail.');
$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorEmailSubject']                        = array('E-Mail Betreff', 'Geben Sie den Betreff für die E-Mail ein. Die Verwendung von Inserttags ist möglich.');
$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorEmailContent']                        = array('E-Mail Inhalt', 'Geben Sie den HTML Inhalt für die E-Mail ein. Dieser wird automatisch als Text umgewandelt. So sind HTML und Text Emails gewährleistet. Die Verwendung von Inserttags ist möglich.');

/**
 * actions
 */
$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorActions']['create_resultlist_generator_userlist'] = 'Mitgliederliste für den Ergebnislisten Generator erzeugen';
$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorActions']['synchronize_webmail_addressbook']      = 'Webmail Adressbuch synchronisieren';
$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorActions']['create_welcome_document']              = 'Begrüßungsschreiben erzeugen';
$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessorActions']['send_email']                           = 'E-Mail versenden';

/**
 * help messages
 */
$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessor']['help']['inserttags']['headline'] = array('<u>Inserttags</u>', 'Folgende Inserttags können verwendet werden:');
$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessor']['help']['inserttags']['member']   = array('<i>{{member::*}}</i>', 'Dieses Tag liefert alle Werte des aktuellen Mitglieds (ersetzen Sie * mit einem beliebigen Attribut des Mitglieds, z.B. <i>firstname</i> oder <i>company</i>, das Attribut <i>password</i> ist nicht erlaubt).');
$GLOBALS['TL_LANG']['tl_settings']['rscMemberSubmissionPostProcessor']['help']['inserttags']['actions']  = array('<i>{{actions}}</i>', 'Dieses Tag liefert alle durchgeführten Aktionen als nicht nummerierte HTML Liste.'); 
 
?>