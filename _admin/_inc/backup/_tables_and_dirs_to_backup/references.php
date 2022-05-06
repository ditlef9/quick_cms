<?php
/**
*
* File: _admin/_inc/backup/_tables_and_dirs_to_backup/references.php
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
$t_references_liquidbase 	 = $mysqlPrefixSav . "references_liquidbase";


$t_references_title_translations 	= $mysqlPrefixSav . "references_title_translations";
$t_references_categories_main	 	= $mysqlPrefixSav . "references_categories_main";
$t_references_categories_sub 	 	= $mysqlPrefixSav . "references_categories_sub";

$t_references_index		 	= $mysqlPrefixSav . "references_index";
$t_references_index_groups	 	= $mysqlPrefixSav . "references_index_groups";
$t_references_index_groups_images 	= $mysqlPrefixSav . "references_index_groups_images";
$t_references_index_guides	 	= $mysqlPrefixSav . "references_index_guides";
$t_references_index_guides_comments	= $mysqlPrefixSav . "references_index_guides_comments";
$t_references_index_guides_images	= $mysqlPrefixSav . "references_index_guides_images";




$tables_truncate_array = array();

$tables_backup_array = array(
			"$t_references_liquidbase", 


			"$t_references_title_translations", 
			"$t_references_categories_main", 
			"$t_references_categories_sub", 

			"$t_references_index", 
			"$t_references_index_groups", 
			"$t_references_index_groups_images", 
			"$t_references_index_guides", 
			"$t_references_index_guides_comments", 
			"$t_references_index_guides_images",

);

/*- Directories ---------------------------------------------------------------------------- */

$directories_array = array();

?>