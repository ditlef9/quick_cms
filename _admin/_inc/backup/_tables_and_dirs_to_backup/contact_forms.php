<?php
/**
*
* File: _admin/_inc/backup/_tables_and_dirs_to_backup/workout_plans.php
* Version 18:17 13.01.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ------------------------------------------------------------------------ */
$t_contact_forms_liquidbase		= $mysqlPrefixSav . "contact_forms_liquidbase";
$t_contact_forms_index			= $mysqlPrefixSav . "contact_forms_index";
$t_contact_forms_images			= $mysqlPrefixSav . "contact_forms_images";
$t_contact_forms_questions		= $mysqlPrefixSav . "contact_forms_questions";
$t_contact_forms_questions_alternatives	= $mysqlPrefixSav . "contact_forms_questions_alternatives";
$t_contact_forms_auto_replies		= $mysqlPrefixSav . "contact_forms_auto_replies";
$t_contact_forms_messages_index		= $mysqlPrefixSav . "contact_forms_messages_index";
$t_contact_forms_messages_answers	= $mysqlPrefixSav . "contact_forms_messages_answers";


$tables_truncate_array = array();

$tables_backup_array = array(
			"$t_contact_forms_liquidbase",
			"$t_contact_forms_index",
			"$t_contact_forms_images",
			"$t_contact_forms_questions",
			"$t_contact_forms_questions_alternatives",
			"$t_contact_forms_auto_replies",
			"$t_contact_forms_messages_index",
			"$t_contact_forms_messages_answers");

/*- Directories ---------------------------------------------------------------------------- */

$directories_array = array("_uploads/contact_forms_images");

?>