<?php
/**
*
* File: _admin/_inc/downloads/_liquibase/downloads_main_categories.php
* Version 1.0.0
* Date 12:57 24.03.2021
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_downloads_main_categories_translations") or die(mysqli_error($link)); 


echo"
	<!-- downloads_main_categories_translations -->
	";
	$query = "SELECT * FROM $t_downloads_main_categories_translations";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_downloads_main_categories_translations: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_downloads_main_categories_translations(
	  	 main_category_translation_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(main_category_translation_id), 
	  	   main_category_id INT,
	  	   main_category_translation_language VARCHAR(20),
	  	   main_category_translation_value VARCHAR(200))");
	}
	echo"
	<!-- //downloads_main_categories_translations -->

";
?>