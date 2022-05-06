<?php
/**
*
* File: _admin/_inc/food/_liquibase/food/age_restrictions.php
* Version 1.0.0
* Date 15:43 18.10.2020
* Copyright (c) 2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_food_age_restrictions") or die(mysqli_error($link)); 


echo"


	<!-- food_age_restrictions -->
	";
	$query = "SELECT * FROM $t_food_age_restrictions";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_food_age_restrictions: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_food_age_restrictions (
	  	 restriction_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(restriction_id), 
	  	 restriction_country_name VARCHAR(250),
	  	 restriction_country_iso_two VARCHAR(2),
	  	 restriction_country_flag_path_16x16 VARCHAR(250),
	  	 restriction_country_flag_16x16 VARCHAR(250),
	  	 restriction_language VARCHAR(250),
	  	 restriction_age_limit INT,
	  	 restriction_title VARCHAR(250),
	  	 restriction_text VARCHAR(250),
	  	 restriction_show_food INT,
	  	 restriction_show_image_a INT,
	  	 restriction_show_image_b INT,
	  	 restriction_show_image_c INT,
	  	 restriction_show_image_d INT,
	  	 restriction_show_image_e INT,
	  	 restriction_show_smileys INT)")
		   or die(mysqli_error());

		$query = "SELECT country_id, country_name, country_name_clean, country_native_name, country_iso_two, country_iso_three, country_language_alt_a, country_language_alt_b, country_flag_path_16x16, country_flag_16x16, country_flag_path_32x32, country_flag_32x32 FROM $t_languages_countries ORDER BY country_name ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_country_id, $get_country_name, $get_country_name_clean, $get_country_native_name, $get_country_iso_two, $get_country_iso_three, $get_country_language_alt_a, $get_country_language_alt_b, $get_country_flag_path_16x16, $get_country_flag_16x16, $get_country_flag_path_32x32, $get_country_flag_32x32) = $row;

			$inp_country_iso_two_mysql = quote_smart($link, $get_country_iso_two);

			$inp_country_name = "$get_country_name";
			$inp_country_name_mysql = quote_smart($link, $inp_country_name);
			
			// Flag
			$inp_country_flag_path_16x16_mysql = quote_smart($link, "_admin/_design/gfx/flags/16x16");
			$inp_country_flag_16x16_mysql = quote_smart($link, "unknown.png");
			if(file_exists("../$get_country_flag_path_16x16/$get_country_flag_16x16") && $get_country_flag_16x16 != ""){
				$inp_country_flag_path_16x16_mysql = quote_smart($link, $get_country_flag_path_16x16);
				$inp_country_flag_16x16_mysql = quote_smart($link, $get_country_flag_16x16);
			}


			$inp_language_mysql = quote_smart($link, $get_country_language_alt_a);

			$inp_title = "Age restriction";
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_text = "This page can only be viewed by adults.";
			$inp_text_mysql = quote_smart($link, $inp_text);

			mysqli_query($link, "INSERT INTO $t_food_age_restrictions 
			(restriction_id, restriction_country_name, restriction_country_iso_two, restriction_country_flag_path_16x16, restriction_country_flag_16x16, 
			restriction_language, restriction_age_limit, restriction_title, restriction_text,  restriction_show_food, 
			restriction_show_image_a, restriction_show_image_b, restriction_show_image_c, restriction_show_image_d, restriction_show_image_e, 
			restriction_show_smileys) 
			VALUES 
			(NULL, $inp_country_name_mysql, $inp_country_iso_two_mysql, $inp_country_flag_path_16x16_mysql, $inp_country_flag_16x16_mysql, 
			$inp_language_mysql, '21', $inp_title_mysql, $inp_text_mysql, '1', 
			0, 0, 0, 0, 0, 
			0)")
			or die(mysqli_error($link));
			
		} // while countries

	}
	echo"
	<!-- //food_age_restrictions  -->

";
?>