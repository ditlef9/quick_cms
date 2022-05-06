<?php
if(isset($_SESSION['admin_user_id'])){

	// Table
	$t_users_profile_headlines			= $mysqlPrefixSav . "users_profile_headlines";

	// Drop if exists
	mysqli_query($link,"DROP TABLE IF EXISTS $t_users_profile_headlines") or die(mysqli_error());

	// Create table
	mysqli_query($link, "CREATE TABLE $t_users_profile_headlines(
			   headline_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(headline_id), 
			   headline_title VARCHAR(200),
			   headline_title_clean VARCHAR(200),
			   headline_icon_path_18x18 VARCHAR(200),
			   headline_icon_file_18x18 VARCHAR(200),
			   headline_weight INT,
			   headline_user_can_view_headline INT,
			   headline_show_on_profile INT)")
			   or die(mysqli_error($link));

	// Create defaults
	mysqli_query($link, "INSERT INTO $t_users_profile_headlines
	(headline_id, headline_title, headline_title_clean, headline_icon_path_18x18, headline_icon_file_18x18, headline_user_can_view_headline, headline_show_on_profile) 
	VALUES 
	(NULL, 'About me', 'about_me', '_admin/_design/gfx/icons/18x18', 'account_circle_outline_black_18x18.png', 1, 1)
	")
	or die(mysqli_error($link));


	// Create data table
	$t_users_profile_data_about_me = $mysqlPrefixSav . "users_profile_data_about_me";
	mysqli_query($link,"DROP TABLE IF EXISTS $t_users_profile_data_about_me") or die(mysqli_error());
	mysqli_query($link, "CREATE TABLE $t_users_profile_data_about_me(
			   data_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(data_id), 
			   data_user_id INT,
			   about_me TEXT,
			   website VARCHAR(200))")
			   or die(mysqli_error($link));
}
?>