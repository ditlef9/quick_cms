<?php
/**
*
* File: _admin/_inc/backup/_tables_and_dirs_to_backup/rebus.php
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
$t_rebus_liquidbase	= $mysqlPrefixSav . "rebus_liquidbase";

$t_rebus_games_index				= $mysqlPrefixSav . "rebus_games_index";
$t_rebus_games_index_geo_distance_measurements	= $mysqlPrefixSav . "rebus_games_index_geo_distance_measurements";
$t_rebus_games_owners			= $mysqlPrefixSav . "rebus_games_owners";
$t_rebus_games_assignments		= $mysqlPrefixSav . "rebus_games_assignments";
$t_rebus_games_assignments_images	= $mysqlPrefixSav . "rebus_games_assignments_images";
$t_rebus_games_comments			= $mysqlPrefixSav . "rebus_games_comments";
$t_rebus_games_high_scores 		= $mysqlPrefixSav . "rebus_games_high_scores";
$t_rebus_games_invited_players 		= $mysqlPrefixSav . "rebus_games_invited_players";

$t_rebus_games_geo_countries		= $mysqlPrefixSav . "rebus_games_geo_countries";
$t_rebus_games_geo_counties		= $mysqlPrefixSav . "rebus_games_geo_counties";
$t_rebus_games_geo_municipalities	= $mysqlPrefixSav . "rebus_games_geo_municipalities";
$t_rebus_games_geo_cities		= $mysqlPrefixSav . "rebus_games_geo_cities";
$t_rebus_games_geo_places		= $mysqlPrefixSav . "rebus_games_geo_places";

$t_rebus_games_sessions_index		= $mysqlPrefixSav . "rebus_games_sessions_index";
$t_rebus_games_sessions_answers		= $mysqlPrefixSav . "rebus_games_sessions_answers";

$t_rebus_groups_index	= $mysqlPrefixSav . "rebus_groups_index";
$t_rebus_groups_members	= $mysqlPrefixSav . "rebus_groups_members";

$t_rebus_teams_index	= $mysqlPrefixSav . "rebus_teams_index";
$t_rebus_teams_members	= $mysqlPrefixSav . "rebus_teams_members";



$tables_truncate_array = array(
			"$t_rebus_games_high_scores", 
			"$t_rebus_games_invited_players", 
			"$t_rebus_games_sessions_index", 
			"$t_rebus_games_sessions_answers");

$tables_backup_array = array(
			"$t_rebus_liquidbase", 

			"$t_rebus_games_index", 
			"$t_rebus_games_index_geo_distance_measurements", 
			"$t_rebus_games_owners", 
			"$t_rebus_games_assignments", 
			"$t_rebus_games_assignments_images", 
			"$t_rebus_games_comments", 
			"$t_rebus_games_high_scores", 
			"$t_rebus_games_invited_players", 

			"$t_rebus_games_geo_countries", 
			"$t_rebus_games_geo_counties", 
			"$t_rebus_games_geo_municipalities", 
			"$t_rebus_games_geo_cities", 
			"$t_rebus_games_geo_places", 

			"$t_rebus_games_sessions_index", 
			"$t_rebus_games_sessions_answers", 

			"$t_rebus_groups_index", 
			"$t_rebus_groups_members", 

			"$t_rebus_teams_index", 
			"$t_rebus_teams_members"
		);

/*- Directories ---------------------------------------------------------------------------- */

$directories_array = array("_uploads/rebus");

?>