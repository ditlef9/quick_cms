<?php
/**
*
* File: _admin/_liquibase/db_scripts/users/groups_index.php
* Version 1.0.0
* Date 12:37 12.09.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(isset($_SESSION['admin_user_id'])){
	/*- Tables ---------------------------------------------------------------------------- */
	$t_users_groups_index	= $mysqlPrefixSav . "users_groups_index";

	$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_users_groups_index") or die(mysqli_error($link)); 

	echo"
	<!-- groups_index -->
	";

	$query = "SELECT * FROM $t_users_groups_index LIMIT 1";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_users_groups_index: $row_cnt</p>
		";
	}
	else{


	mysqli_query($link, "CREATE TABLE $t_users_groups_index(
	  group_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(group_id), 
	   group_name VARCHAR(250), 
	   group_language VARCHAR(2), 
	   group_description TEXT, 
	   group_privacy VARCHAR(20),
	   group_key VARCHAR(20),
	   group_logo_path VARCHAR(200),
	   group_logo_file VARCHAR(200),
	   group_created_by_user_id INT,
	   group_created_by_user_name VARCHAR(200),
	   group_created_by_user_email VARCHAR(200), 
	   group_created_by_ip VARCHAR(200), 
	   group_created_by_hostname VARCHAR(200), 
	   group_created_by_user_agent VARCHAR(200), 
	   group_created_datetime DATETIME, 
	   group_created_date_saying VARCHAR(50),
	   group_updated_by_user_id INT,
	   group_updated_by_user_name VARCHAR(200),
	   group_updated_by_user_email VARCHAR(200), 
	   group_updated_by_ip VARCHAR(200), 
	   group_updated_by_hostname VARCHAR(200), 
	   group_updated_by_user_agent VARCHAR(200), 
	   group_updated_datetime DATETIME, 
	   group_updated_date_saying VARCHAR(50)
	   )")
	   or die(mysqli_error());


	}
	echo"
	<!-- //groups_index -->

	";
} // access
?>