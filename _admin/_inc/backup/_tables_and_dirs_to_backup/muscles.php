<?php
/**
*
* File: _admin/_inc/backup/_tables_and_dirs_to_backup/muscles.php
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
$t_muscles_liquidbase			= $mysqlPrefixSav . "muscles_liquidbase";
$t_muscles				= $mysqlPrefixSav . "muscles";
$t_muscles_translations 		= $mysqlPrefixSav . "muscles_translations";
$t_muscle_groups 			= $mysqlPrefixSav . "muscle_groups";
$t_muscle_groups_translations	 	= $mysqlPrefixSav . "muscle_groups_translations";
$t_muscle_part_of 			= $mysqlPrefixSav . "muscle_part_of";
$t_muscle_part_of_translations	 	= $mysqlPrefixSav . "muscle_part_of_translations";



$tables_truncate_array = array();

$tables_backup_array = array(
			"$t_muscles_liquidbase", 
			"$t_muscles", 
			"$t_muscles_translations", 
			"$t_muscle_groups", 
			"$t_muscle_groups_translations", 
			"$t_muscle_part_of", 
			"$t_muscle_part_of_translations"
			);

/*- Directories ---------------------------------------------------------------------------- */
$directories_array = array("_uploads/muscles");

?>