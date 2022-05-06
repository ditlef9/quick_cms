<?php
/**
*
* File: _admin/_inc/throw_the_dice/_liquibase/throw_the_dice/game_members.php
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
mysqli_query($link, "DROP TABLE IF EXISTS $t_dice_game_members")  or die(mysqli_error($link));

echo"


<!-- dice_game_members -->
";

$query = "SELECT * FROM $t_dice_game_members LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_dice_game_members: $row_cnt</p>
	";
}
else{
	mysqli_query($link, "CREATE TABLE $t_dice_game_members(
	  member_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(member_id), 
	   member_game_id INT, 
	   member_name VARCHAR(200), 
	   member_ip VARCHAR(200),
	   member_security_code VARCHAR(200),
	   member_number_of_throws INT, 
	   member_last_throw_result VARCHAR(200),
	   member_last_seen_time VARCHAR(200),
	   member_joined_datetime DATETIME,
	   member_joined_saying VARCHAR(200))")
	   or die(mysqli_error());
}
echo"
<!-- //dice_game_members -->





";
?>