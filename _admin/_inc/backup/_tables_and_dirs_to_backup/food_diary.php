<?php
/**
*
* File: _admin/_inc/backup/_tables_and_dirs_to_backup/food_diary.php
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
$t_food_diary_liquidbase 			= $mysqlPrefixSav . "food_diary_liquidbase";
$t_food_diary_goals 	  			= $mysqlPrefixSav . "food_diary_goals";
$t_food_diary_entires	  			= $mysqlPrefixSav . "food_diary_entires";
$t_food_diary_consumed_days  			= $mysqlPrefixSav . "food_diary_consumed_days";
$t_food_diary_consumed_hours  			= $mysqlPrefixSav . "food_diary_consumed_hours";
$t_food_diary_last_used 			= $mysqlPrefixSav . "food_diary_last_used";
$t_food_diary_user_adapted_view 		= $mysqlPrefixSav . "food_diary_user_adapted_view";

$t_food_diary_meals_index			= $mysqlPrefixSav . "food_diary_meals_index";
$t_food_diary_meals_items			= $mysqlPrefixSav . "food_diary_meals_items";
$t_food_diary_lifestyle_selected_per_day 	= $mysqlPrefixSav . "food_diary_lifestyle_selected_per_day";



$tables_truncate_array = array("$t_food_diary_user_adapted_view");

$tables_backup_array = array(
			"$t_food_diary_liquidbase", 
			"$t_food_diary_goals", 
			"$t_food_diary_entires", 
			"$t_food_diary_consumed_days", 
			"$t_food_diary_consumed_hours", 
			"$t_food_diary_last_used", 
			"$t_food_diary_user_adapted_view", 

			"$t_food_diary_meals_index", 
			"$t_food_diary_meals_items", 
			"$t_food_diary_lifestyle_selected_per_day");

/*- Directories ---------------------------------------------------------------------------- */
$directories_array = array();

?>