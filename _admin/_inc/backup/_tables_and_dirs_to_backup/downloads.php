<?php
/**
*
* File: _admin/_inc/backup/_tables_and_dirs_to_backup/downloads.php
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
$t_downloads_liquidbase				= $mysqlPrefixSav . "downloads_liquidbase";
$t_downloads_index 				= $mysqlPrefixSav . "downloads_index";
$t_downloads_comments				= $mysqlPrefixSav . "downloads_comments";

$t_downloads_main_categories 			= $mysqlPrefixSav . "downloads_main_categories";
$t_downloads_main_categories_translations 	= $mysqlPrefixSav . "downloads_main_categories_translations";

$t_downloads_sub_categories 			= $mysqlPrefixSav . "downloads_sub_categories";
$t_downloads_sub_categories_translations 	= $mysqlPrefixSav . "downloads_sub_categories_translations";




$tables_truncate_array = array();

$tables_backup_array = array(
			"$t_downloads_liquidbase", 
			"$t_downloads_index", 
			"$t_downloads_comments", 

			"$t_downloads_main_categories", 
			"$t_downloads_main_categories_translations", 

			"$t_downloads_sub_categories", 
			"$t_downloads_sub_categories_translations"



);

/*- Directories ---------------------------------------------------------------------------- */

$directories_array = array();

?>