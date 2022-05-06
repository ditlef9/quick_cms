<?php
/**
*
* File: _admin/_inc/recipes/_liquidbase_db_scripts/recipes_age_restrictions.php
* Version 1.0.0
* Date 17:21 31.12.2020
* Copyright (c) 2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

echo"

	<!-- recipes_age_restrictions -->
	";
	$query = "SELECT * FROM $t_recipes_age_restrictions";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_recipes_age_restrictions: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_recipes_age_restrictions (
	  	 restriction_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(restriction_id), 
	  	 restriction_country_iso VARCHAR(2),
	  	 restriction_country_name VARCHAR(250),
	  	 restriction_country_flag VARCHAR(250),
	  	 restriction_language VARCHAR(250),
	  	 restriction_age_limit INT,
	  	 restriction_title VARCHAR(250),
	  	 restriction_text VARCHAR(250),
	  	 restriction_can_view_recipe INT,
	  	 restriction_can_view_image INT)")
		   or die(mysqli_error());

		$query = "SELECT language_iso_two FROM $t_languages";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_iso_two) = $row;

			
			$inp_country_iso = "$get_language_iso_two";
			$inp_country_iso_mysql = quote_smart($link, $inp_country_iso);

			$inp_country_name = "$get_language_iso_two";
			$inp_country_name = str_replace("_", " ", $inp_country_name);
			$inp_country_name = ucwords($inp_country_name);
			$inp_country_name_mysql = quote_smart($link, $inp_country_name);

			$inp_country_flag = "$get_language_iso_two";
			$inp_country_flag_mysql = quote_smart($link, $inp_country_flag);

			$inp_language = "$get_language_iso_two";
			$inp_language_mysql = quote_smart($link, $inp_language);

			$inp_title = "Age restriction";
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_text = "This page can only be viewed by adults.";
			$inp_text_mysql = quote_smart($link, $inp_text);

			$inp_can_view_image = "1";
			$inp_can_view_image_mysql = quote_smart($link, $inp_can_view_image);
			

			// Check if country exists
			$query_search = "SELECT restriction_id FROM $t_recipes_age_restrictions WHERE restriction_country_name=$inp_country_name_mysql";
			$result_search = mysqli_query($link, $query_search);
			$row_search = mysqli_fetch_row($result_search);
			list($get_restriction_id) = $row_search;

			if($get_restriction_id == ""){
				mysqli_query($link, "INSERT INTO $t_recipes_age_restrictions 
				(restriction_id, restriction_country_iso, restriction_country_name, restriction_country_flag, restriction_language, restriction_age_limit, restriction_title, restriction_text,  restriction_can_view_recipe, restriction_can_view_image) 
				VALUES 
				(NULL, $inp_country_iso_mysql, $inp_country_name_mysql, $inp_country_flag_mysql, $inp_language_mysql, '21', $inp_title_mysql, $inp_text_mysql, '1', $inp_can_view_image_mysql)")
				or die(mysqli_error($link));
			}
		}

	}
	echo"
	<!-- //recipes_age_restrictions  -->
";
?>