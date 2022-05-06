<?php
/**
*
* File: _admin/_inc/rebus/_liquibase/rebus/games_high_scores.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_rebus_games_high_scores") or die(mysqli_error($link)); 


echo"
<!-- games_high_scores -->
";

$query = "SELECT * FROM $t_rebus_games_high_scores LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_rebus_games_high_scores: $row_cnt</p>
	";
}
else{
	mysqli_query($link, "CREATE TABLE $t_rebus_games_high_scores(
	  high_score_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(high_score_id), 
	   high_score_game_id INT,
	   high_score_session_id INT,
	   high_score_play_as_user_group_team VARCHAR(20),
	   high_score_user_id INT,
	   high_score_team_id INT,
	   high_score_group_id INT,
	   high_score_name VARCHAR(200),
	   high_score_image_path VARCHAR(200),
	   high_score_image_file VARCHAR(200),
	   high_score_image_thumb_50x50 VARCHAR(200),
	   high_score_ip VARCHAR(200),
	   high_score_created DATETIME,
	   high_score_created_saying VARCHAR(50),
	   high_score_points DOUBLE,
	   high_score_seconds_used VARCHAR(50),
	   high_score_time_used_saying VARCHAR(200),
	   high_score_sum VARCHAR(50),
	   high_score_sum_saying VARCHAR(50),
	   high_score_place INT
	   )")
	   or die(mysqli_error());

}
echo"
<!-- //games_high_scores -->

";
?>