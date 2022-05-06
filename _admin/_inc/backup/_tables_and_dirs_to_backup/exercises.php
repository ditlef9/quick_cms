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
$t_exercise_liquidbase				= $mysqlPrefixSav . "exercise_liquidbase";
$t_exercise_index 				= $mysqlPrefixSav . "exercise_index";
$t_exercise_index_translated			= $mysqlPrefixSav . "exercise_index_translated";
$t_exercise_index_images			= $mysqlPrefixSav . "exercise_index_images";
$t_exercise_index_videos			= $mysqlPrefixSav . "exercise_index_videos";
$t_exercise_index_muscles			= $mysqlPrefixSav . "exercise_index_muscles";
$t_exercise_index_muscles_images		= $mysqlPrefixSav . "exercise_index_muscles_images";
$t_exercise_index_tags				= $mysqlPrefixSav . "exercise_index_tags";
$t_exercise_tags_cloud				= $mysqlPrefixSav . "exercise_tags_cloud";
$t_exercise_index_comments			= $mysqlPrefixSav . "exercise_index_comments";
$t_exercise_index_translations_relations	= $mysqlPrefixSav . "exercise_index_translations_relations";
$t_exercise_equipments 				= $mysqlPrefixSav . "exercise_equipments";
$t_exercise_types				= $mysqlPrefixSav . "exercise_types";
$t_exercise_types_translations 			= $mysqlPrefixSav . "exercise_types_translations";
$t_exercise_levels				= $mysqlPrefixSav . "exercise_levels";
$t_exercise_levels_translations 		= $mysqlPrefixSav . "exercise_levels_translations";

$t_exercise_muscles_images			= $mysqlPrefixSav . "exercise_muscles_images";

$t_exercise_search_queries 			= $mysqlPrefixSav . "exercise_search_queries";



$tables_truncate_array = array();

$tables_backup_array = array("$t_exercise_liquidbase", 
				"$t_exercise_index", 
				"$t_exercise_index_translated", 
				"$t_exercise_index_images", 
				"$t_exercise_index_videos", 
				"$t_exercise_index_muscles", 
				"$t_exercise_index_muscles_images", 
				"$t_exercise_index_tags", 
				"$t_exercise_tags_cloud", 
				"$t_exercise_index_comments", 
				"$t_exercise_index_translations_relations", 
				"$t_exercise_equipments", 
				"$t_exercise_types", 
				"$t_exercise_types_translations", 
				"$t_exercise_levels", 
				"$t_exercise_levels_translations", 

				"$t_exercise_muscles_images", 

				"$t_exercise_search_queries");

/*- Directories ---------------------------------------------------------------------------- */

$directories_array = array("_uploads/exercises");

?>