<?php
/**
*
* File: _admin/_inc/recipes/_liquidbase_db_scripts/recipes_categories.php
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

mysqli_query($link, "DROP TABLE IF EXISTS $t_recipes_categories");

echo"



	<!-- $t_recipes_categories -->
	";
	$query = "SELECT * FROM $t_recipes_categories";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_recipes_categories: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_recipes_categories(
	  	category_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(category_id), 
	  	   category_name VARCHAR(250), 
	  	   category_age_restriction INT, 
	  	   category_image_path VARCHAR(250), 
	  	   category_image_file VARCHAR(250), 
	  	   category_image_updated_month INT,
	  	   category_icon_file VARCHAR(250), 
	  	   category_updated DATETIME)")
		   or die(mysqli_error());

		mysqli_query($link, "INSERT INTO $t_recipes_categories
		(category_id, category_name, category_age_restriction, category_image_path, category_image_file, category_icon_file, category_updated)
		VALUES 
		(NULL, 'Breakfast', 0, '_uploads/recipes/categories', '1_image.png', '1_icon.png', '2018-11-03'),
		(NULL, 'Dinner', 0, '_uploads/recipes/categories', '2_image.png', '2_icon.png', '2018-11-03'),
		(NULL, 'Snacks', 0, '_uploads/recipes/categories', '4_image.png', '4_icon.png', '2018-11-03'),
		(NULL, 'Dessert', 0, '_uploads/recipes/categories', '5_image.png', '5_icon.png', '2018-11-03'),
		(NULL, 'Sides', 0, '_uploads/recipes/categories', '6_image.png', '6_icon.png', '2018-11-03'),
		(NULL, 'Beverages', 0, '_uploads/recipes/categories', '7_image.png', '7_icon.png', '2018-11-03')")
		or die(mysqli_error($link));
	}
	echo"
	<!-- //$t_recipes_categories -->

";
?>