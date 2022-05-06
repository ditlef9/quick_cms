<?php
/**
*
* File: _admin/_inc/rebus/_liquibase/rebus/games_sessions.php
* Version 1.0.0
* Date 07:23 01.07.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_rebus_games_sessions_index") or die(mysqli_error($link)); 

echo"
<!-- games_sessions_index -->
";

$query = "SELECT * FROM $t_rebus_games_sessions_index LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_rebus_games_sessions_index: $row_cnt</p>
	";
}
else{
	mysqli_query($link, "CREATE TABLE $t_rebus_games_sessions_index(
	  session_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(session_id), 
	   session_game_id INT, 
	   session_play_as_user_group_team VARCHAR(20),
	   session_user_id INT, 
	   session_group_id INT, 
	   session_team_id INT, 
	   session_start_datetime DATETIME,
	   session_start_year INT,
	   session_start_month INT,
	   session_start_day INT,
	   session_start_hour INT,
	   session_start_minute INT,
	   session_start_time VARCHAR(200),
	   session_is_on_assignment_number INT,
	   session_points DOUBLE,
	   session_ended_game INT,
	   session_is_finished INT,
	   session_finished_datetime DATETIME,
	   session_finished_time VARCHAR(200),
	   session_seconds_used VARCHAR(50),
	   session_time_used_saying VARCHAR(200)
	   )")
	   or die(mysqli_error());


}
echo"
<!-- //games_sessions_index -->

";
?>