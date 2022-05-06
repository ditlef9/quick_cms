<?php
/**
*
* File: _admin/_inc/rebus/_liquibase/rebus/games_invited_players.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_rebus_games_invited_players") or die(mysqli_error($link)); 

echo"
<!-- games_invited_players -->
";

$query = "SELECT * FROM $t_rebus_games_invited_players LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_rebus_games_invited_players: $row_cnt</p>
	";
}
else{


	mysqli_query($link, "CREATE TABLE $t_rebus_games_invited_players(
	  invited_player_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(invited_player_id), 
	   invited_player_game_id INT,
	   invited_player_game_title VARCHAR(200),
	   invited_player_user_id INT,
	   invited_player_user_name VARCHAR(200),
	   invited_player_user_email VARCHAR(200), 
	   invited_player_user_photo_destination VARCHAR(200), 
	   invited_player_user_photo_thumb_50 VARCHAR(200), 
	   invited_player_invited INT, 
	   invited_player_user_accepted_invitation INT, 
	   invited_player_accepted_by_moderator INT, 
	   invited_player_added_datetime DATETIME, 
	   invited_player_added_date_saying VARCHAR(50), 
	   invited_player_last_played DATETIME
	   )")
	   or die(mysqli_error());



}
echo"
<!-- //games_invited_players -->

";
?>