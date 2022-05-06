<?php
/**
*
* File: _admin/_inc/throw_the_dice/_liquibase/throw_the_dice/game_index.php
* Version 1.0.0
* Date 21:19 28.08.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}
mysqli_query($link, "DROP TABLE IF EXISTS $t_dice_game_index")  or die(mysqli_error($link));

echo"
<!-- dice_game_index -->
";

$query = "SELECT * FROM $t_dice_game_index LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_dice_game_index: $row_cnt</p>
	";
}
else{
	mysqli_query($link, "CREATE TABLE $t_dice_game_index(
	  game_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(game_id), 
	   game_title VARCHAR(200), 
	   game_owner_name VARCHAR(200), 
	   game_owner_ip VARCHAR(200), 
	   game_password VARCHAR(200), 
	   game_security_code VARCHAR(200), 
	   game_join_pin INT,
	   game_number_of_dices INT,
	   game_throw_log TEXT,
	   game_created_datetime DATETIME, 
	   game_created_saying VARCHAR(200), 
	   game_notes VARCHAR(200))")
	   or die(mysqli_error());
}
echo"
<!-- //dice_game_index -->



";
?>