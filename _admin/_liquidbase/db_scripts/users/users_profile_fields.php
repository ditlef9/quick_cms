<?php
if(isset($_SESSION['admin_user_id'])){

	// Table
	$t_users_profile_fields			= $mysqlPrefixSav . "users_profile_fields";

	// Drop if exists
	mysqli_query($link,"DROP TABLE IF EXISTS $t_users_profile_fields") or die(mysqli_error());

	// Create table
	mysqli_query($link, "CREATE TABLE $t_users_profile_fields(
			   field_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(field_id), 
			   field_headline_id INT,
			   field_title VARCHAR(200),
			   field_title_clean VARCHAR(200),
			   field_weight INT,
			   field_height INT,
			   field_type VARCHAR(20),
			   field_size INT,
			   field_width VARCHAR(4),
			   field_cols INT,
			   field_rows INT,
			   field_user_can_view_field INT,
			   field_show_on_profile INT)")
			   or die(mysqli_error($link));

	// Create defaults
	mysqli_query($link, "INSERT INTO $t_users_profile_fields
	(`field_id`, `field_headline_id`, `field_title`, `field_title_clean`, `field_weight`, `field_type`, `field_size`, `field_width`, `field_cols`, `field_rows`, field_user_can_view_field, `field_show_on_profile`) 
	VALUES 
	(NULL, 1, 'About me', 'about_me', 1, 'textarea', 25, '99%', 45, 5, 1, 1),
	(NULL, 1, 'Website', 'website', 1, 'url', 25, '99%', 45, 5, 1, 1)
	")
	or die(mysqli_error($link));
}
?>