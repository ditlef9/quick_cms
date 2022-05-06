<?php
/**
*
* File: _admin/_inc/backup/_tables_and_dirs_to_backup/meal_plans.php
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
$t_meal_plans_liquidbase 	= $mysqlPrefixSav . "meal_plans_liquidbase";
$t_meal_plans 			= $mysqlPrefixSav . "meal_plans";
$t_meal_plans_days		= $mysqlPrefixSav . "meal_plans_days";
$t_meal_plans_meals		= $mysqlPrefixSav . "meal_plans_meals";
$t_meal_plans_entries		= $mysqlPrefixSav . "meal_plans_entries";
$t_meal_plans_user_adapted_view	= $mysqlPrefixSav . "meal_plans_user_adapted_view";



$tables_truncate_array = array("$t_meal_plans_user_adapted_view");

$tables_backup_array = array(
			"$t_meal_plans_liquidbase", 
			"$t_meal_plans", 
			"$t_meal_plans_days", 
			"$t_meal_plans_meals", 
			"$t_meal_plans_entries", 
			"$t_meal_plans_user_adapted_view"
);

/*- Directories ---------------------------------------------------------------------------- */
$directories_array = array("_uploads/meal_plans");

?>