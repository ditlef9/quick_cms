<?php
/**
*
* File: _admin/_inc/rebus/_liquibase/rebus/teams_members.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_rebus_teams_members") or die(mysqli_error($link)); 


echo"
<!-- teams_members -->
";

$query = "SELECT * FROM $t_rebus_teams_members LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_rebus_teams_members: $row_cnt</p>
	";
}
else{


	mysqli_query($link, "CREATE TABLE $t_rebus_teams_members(
	  member_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(member_id), 
	   member_team_id INT,
	   member_user_id INT,
	   member_user_name VARCHAR(200),
	   member_user_email VARCHAR(200), 
	   member_user_photo_destination VARCHAR(200), 
	   member_user_photo_thumb_50 VARCHAR(200), 
	   member_status VARCHAR(200),  
	   member_invited INT, 
	   member_user_accepted_invitation INT, 
	   member_accepted_by_moderator INT, 
	   member_joined_datetime DATETIME, 
	   member_joined_date_saying VARCHAR(50), 
	   member_last_played DATETIME
	   )")
	   or die(mysqli_error());


}
echo"
<!-- //teams_members -->

";
?>