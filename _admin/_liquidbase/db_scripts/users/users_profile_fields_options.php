<?php
if(isset($_SESSION['admin_user_id'])){

	// Table
	$t_users_profile_fields_options	= $mysqlPrefixSav . "users_profile_fields_options";

	// Drop if exists
	mysqli_query($link,"DROP TABLE IF EXISTS $t_users_profile_fields_options") or die(mysqli_error());

	// Create table
	mysqli_query($link, "CREATE TABLE $t_users_profile_fields_options(
			   option_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(option_id), 
			   option_field_id INT,
			   option_headline_id INT,
			   option_title VARCHAR(200),
			   option_title_clean VARCHAR(200),
			   option_weight INT)")
			   or die(mysqli_error($link));


}
?>