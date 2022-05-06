<?php
/**
*
* File: _admin/_inc/recipes/_liquidbase_db_scripts/recipes_categories_translations.php
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

mysqli_query($link, "DROP TABLE IF EXISTS $t_recipes_categories_translations");


echo"

	<!-- $t_recipes_categories_translations -->
	";
	$query = "SELECT * FROM $t_recipes_categories_translations";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_recipes_categories_translations: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_recipes_categories_translations(
	  	category_translation_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(category_translation_id), 
	  	   category_id INT, 
	  	   category_translation_language VARCHAR(250), 
	  	   category_translation_title VARCHAR(250), 
	  	   category_translation_text TEXT, 
	  	   category_translation_no_recipes INT, 
	  	   category_translation_image_path VARCHAR(250), 
	  	   category_translation_image VARCHAR(250), 
	  	   category_translation_image_updated_week INT, 
	  	   category_translation_last_updated DATETIME)")
		   or die(mysqli_error());



		mysqli_query($link, "INSERT INTO $t_recipes_categories_translations
		(category_translation_id, category_id, category_translation_language, category_translation_title)
		VALUES 
		(NULL, 1, 'en', 'Breakfast'),
		(NULL, 2, 'en', 'Dinner'),
		(NULL, 3, 'en', 'Sides'),
		(NULL, 4, 'en', 'Snacks'),
		(NULL, 5, 'en', 'Dessert'),
		(NULL, 6, 'en', 'Drinks'),
		(NULL, 1, 'no', 'Frokost'),
		(NULL, 2, 'no', 'Middag'),
		(NULL, 3, 'no', 'Sideretter'),
		(NULL, 4, 'no', 'Snacks'),
		(NULL, 5, 'no', 'Dessert'),
		(NULL, 6, 'no', 'Drikke')")
		or die(mysqli_error($link));

	}
	echo"
	<!-- //$t_recipes_categories_translations -->



";
?>