<?php
if(isset($_SESSION['admin_user_id'])){

	// Table
	$t_users_profile_fields_translations			= $mysqlPrefixSav . "users_profile_fields_translations";

	// Drop if exists
	mysqli_query($link,"DROP TABLE IF EXISTS $t_users_profile_fields_translations") or die(mysqli_error());

	// Create table
	mysqli_query($link, "CREATE TABLE $t_users_profile_fields_translations(
			   translation_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(translation_id), 
			   translation_field_id INT,
			   translation_headline_id INT,
			   translation_language VARCHAR(2),
			   translation_value VARCHAR(200))")
			   or die(mysqli_error($link));

	// Create defaults
	mysqli_query($link, "INSERT INTO $t_users_profile_fields_translations
	(`translation_id`, `translation_field_id`, `translation_headline_id`, `translation_language`, `translation_value`) 
	VALUES 
	(NULL, 1, 1, 'en', 'About me'),
	(NULL, 1, 1, 'no', 'Om meg'),
	(NULL, 2, 1, 'en', 'Website'),
	(NULL, 2, 1, 'no', 'Website')
	")
	or die(mysqli_error($link));
}
?>