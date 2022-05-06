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
$t_workout_plans_liquidbase		= $mysqlPrefixSav . "workout_plans_liquidbase";
$t_workout_plans_yearly  		= $mysqlPrefixSav . "workout_plans_yearly";
$t_workout_plans_period  		= $mysqlPrefixSav . "workout_plans_period";
$t_workout_plans_weekly  		= $mysqlPrefixSav . "workout_plans_weekly";
$t_workout_plans_weekly_tags  		= $mysqlPrefixSav . "workout_plans_weekly_tags";
$t_workout_plans_weekly_tags_unique  	= $mysqlPrefixSav . "workout_plans_weekly_tags_unique";
$t_workout_plans_weekly_comments	= $mysqlPrefixSav . "workout_plans_weekly_comments";
$t_workout_plans_sessions 		= $mysqlPrefixSav . "workout_plans_sessions";
$t_workout_plans_sessions_main 		= $mysqlPrefixSav . "workout_plans_sessions_main";
$t_workout_plans_favorites 		= $mysqlPrefixSav . "workout_plans_favorites";



$tables_truncate_array = array();

$tables_backup_array = array("$t_workout_plans_liquidbase",
"$t_workout_plans_yearly",
"$t_workout_plans_period",
"$t_workout_plans_weekly",
"$t_workout_plans_weekly_tags",
"$t_workout_plans_weekly_tags_unique",
"$t_workout_plans_weekly_comments",
"$t_workout_plans_sessions",
"$t_workout_plans_sessions_main",
"$t_workout_plans_favorites");

/*- Directories ---------------------------------------------------------------------------- */

$directories_array = array("_uploads/workout_plans");

?>