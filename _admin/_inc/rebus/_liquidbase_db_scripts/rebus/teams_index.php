<?php
/**
*
* File: _admin/_inc/rebus/_liquibase/rebus/teams_index.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_rebus_teams_index") or die(mysqli_error($link)); 

echo"
<!-- teams_index -->
";

$query = "SELECT * FROM $t_rebus_teams_index LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_rebus_teams_index: $row_cnt</p>
	";
}
else{


	mysqli_query($link, "CREATE TABLE $t_rebus_teams_index(
	  team_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(team_id), 
	   team_name VARCHAR(250), 
	   team_language VARCHAR(2), 
	   team_description TEXT, 
	   team_privacy VARCHAR(20),
	   team_key VARCHAR(20),
	   team_group_id INT,
	   team_group_name VARCHAR(200),
	   team_logo_path VARCHAR(200),
	   team_logo_file VARCHAR(200),
	   team_color VARCHAR(20),
	   team_created_by_user_id INT,
	   team_created_by_user_name VARCHAR(200),
	   team_created_by_user_email VARCHAR(200), 
	   team_created_by_ip VARCHAR(200), 
	   team_created_by_hostname VARCHAR(200), 
	   team_created_by_user_agent VARCHAR(200), 
	   team_created_datetime DATETIME, 
	   team_created_date_saying VARCHAR(50),
	   team_updated_by_user_id INT,
	   team_updated_by_user_name VARCHAR(200),
	   team_updated_by_user_email VARCHAR(200), 
	   team_updated_by_ip VARCHAR(200), 
	   team_updated_by_hostname VARCHAR(200), 
	   team_updated_by_user_agent VARCHAR(200), 
	   team_updated_datetime DATETIME, 
	   team_updated_date_saying VARCHAR(50), 
	   team_last_played DATETIME 
	   )")
	   or die(mysqli_error());


}
echo"
<!-- //teams_index -->

";
?>