<?php
/**
*
* File: _admin/_liquidbase/db_scripts/webdesign/webdesign_share_buttons.php
* Version 1.0.0
* Date 21:19 28.08.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// Access check
if(isset($_SESSION['admin_user_id'])){


	$t_languages		= $mysqlPrefixSav . "languages";
	$t_languages_active	= $mysqlPrefixSav . "languages_active";


	$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_languages_active") or die(mysqli_error($link)); 

	mysqli_query($link, "CREATE TABLE $t_languages_active(
	   language_active_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(language_active_id), 
	   language_active_name VARCHAR(250),
	   language_active_slug VARCHAR(250),
	   language_active_native_name VARCHAR(250),
	   language_active_iso_two VARCHAR(250),
	   language_active_iso_three VARCHAR(250),
	   language_active_iso_four VARCHAR(250),
	   language_active_iso_two_alt_a VARCHAR(20),
	   language_active_iso_two_alt_b VARCHAR(20),

	   language_active_flag_emoji_code VARCHAR(250),
	   language_active_flag_emoji_char VARCHAR(250),
	   language_active_flag_emoji_char_output_html VARCHAR(250),
	   language_active_flag_emoji_char_string_value VARCHAR(250),

	   language_active_flag_path_16x16 VARCHAR(250),
	   language_active_flag_active_16x16 VARCHAR(250),
	   language_active_flag_inactive_16x16 VARCHAR(250),

	   language_active_flag_path_18x18 VARCHAR(250),
	   language_active_flag_active_18x18 VARCHAR(250),
	   language_active_flag_inactive_18x18 VARCHAR(250),

	   language_active_flag_path_24x24 VARCHAR(250),
	   language_active_flag_active_24x24 VARCHAR(250),
	   language_active_flag_inactive_24x24 VARCHAR(250),

	   language_active_flag_path_32x32 VARCHAR(250),
	   language_active_flag_active_32x32 VARCHAR(250),
	   language_active_flag_inactive_32x32 VARCHAR(250),

	   language_active_charset VARCHAR(250),
	   language_active_default INT)")
	   or die(mysqli_error($link));


	// Insert active language
	$language_mysql = quote_smart($link, $l);
	$query = "SELECT language_id, language_name, language_slug, language_native_name, language_iso_two, language_iso_three, language_iso_four, language_iso_two_alt_a, language_iso_two_alt_b, language_flag_path_16x16, language_flag_active_16x16, language_flag_inactive_16x16, language_flag_path_18x18, language_flag_active_18x18, language_flag_inactive_18x18, language_flag_path_24x24, language_flag_active_24x24, language_flag_inactive_24x24, language_flag_path_32x32, language_flag_active_32x32, language_flag_inactive_32x32, language_charset FROM $t_languages WHERE language_iso_two=$language_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_language_id, $get_language_name, $get_language_slug, $get_language_native_name, $get_language_iso_two, $get_language_iso_three, $get_language_iso_four, $get_language_iso_two_alt_a, $get_language_iso_two_alt_b, $get_language_flag_path_16x16, $get_language_flag_active_16x16, $get_language_flag_inactive_16x16, $get_language_flag_path_18x18, $get_language_flag_active_18x18, $get_language_flag_inactive_18x18, $get_language_flag_path_24x24, $get_language_flag_active_24x24, $get_language_flag_inactive_24x24, $get_language_flag_path_32x32, $get_language_flag_active_32x32, $get_language_flag_inactive_32x32, $get_language_charset) = $row;

	mysqli_query($link, "INSERT INTO $t_languages_active
	(language_active_id, language_active_name, language_active_slug, language_active_native_name, language_active_iso_two, 
	language_active_iso_three, language_active_iso_four, language_active_iso_two_alt_a, language_active_iso_two_alt_b, language_active_flag_path_16x16, 
	language_active_flag_active_16x16, language_active_flag_inactive_16x16, language_active_flag_path_18x18, language_active_flag_active_18x18, language_active_flag_inactive_18x18, 
	language_active_flag_path_24x24, language_active_flag_active_24x24, language_active_flag_inactive_24x24, language_active_flag_path_32x32, language_active_flag_active_32x32, 
	language_active_flag_inactive_32x32, language_active_charset, language_active_default) 
	VALUES
	(NULL, '$get_language_name', '$get_language_slug', '$get_language_native_name', '$get_language_iso_two', 
	'$get_language_iso_three', '$get_language_iso_four', '$get_language_iso_two_alt_a', '$get_language_iso_two_alt_b', '$get_language_flag_path_16x16', 
	'$get_language_flag_active_16x16', '$get_language_flag_inactive_16x16', '$get_language_flag_path_18x18', '$get_language_flag_active_18x18', '$get_language_flag_inactive_18x18', 
	'$get_language_flag_path_24x24', '$get_language_flag_active_24x24', '$get_language_flag_inactive_24x24', '$get_language_flag_path_32x32', '$get_language_flag_active_32x32', 
	'$get_language_flag_inactive_32x32', '$get_language_charset', '1')
	") or die(mysqli_error($link));



} // access
?>