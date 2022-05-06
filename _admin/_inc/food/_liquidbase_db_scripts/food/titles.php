<?php
/**
*
* File: _admin/_inc/food/_liquidbase_db_scripts/titles.php
* Version 1.0.0
* Date 12:38 06.05.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

echo"

	<!-- food_titles -->
	";
	$query = "SELECT * FROM $t_food_titles";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_food_titles: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_food_titles(
	  	 title_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(title_id), 
		`title_language` varchar(20) DEFAULT NULL,
		`title_value` varchar(250) DEFAULT NULL
	  	   )")
		   or die(mysqli_error());

		// Insert default titles
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two) = $row;
			
			// Translation
			include("_translations/site/$get_language_active_iso_two/food/ts_index.php");

			$inp_language_mysql = quote_smart($link, $get_language_active_iso_two);
			$inp_title_mysql = quote_smart($link, $l_food);
			
			mysqli_query($link, "INSERT INTO $t_food_titles (title_id, title_language, title_value) VALUES
					(NULL, $inp_language_mysql, $inp_title_mysql)") or die(mysqli_error());
		}
	}
	echo"
	<!-- //food_titles -->
	
";
?>