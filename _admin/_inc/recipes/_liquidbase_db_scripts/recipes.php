<?php
/**
*
* File: _admin/_inc/recipes/_liquidbase_db_scripts/recipes.php
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

mysqli_query($link, "DROP TABLE IF EXISTS $t_recipes");


echo"


	<!-- $t_recipes -->
	";
	
	$query = "SELECT * FROM $t_recipes";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_recipes: $row_cnt</p>
		";
	}
	else{

		// Image A = 1920x1080 (Website etc)
		// Image B = 1080x1920 (Instagram etc)

		mysqli_query($link, "CREATE TABLE $t_recipes(
	  	 recipe_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(recipe_id), 
	  	   recipe_user_id INT,
	  	   recipe_title VARCHAR(250),
	  	   recipe_category_id INT,
	  	   recipe_language VARCHAR(50),
	  	   recipe_country VARCHAR(200),
	  	   recipe_introduction TEXT,
	  	   recipe_directions TEXT,
	  	   recipe_image_path VARCHAR(250),
	  	   recipe_image_h_a VARCHAR(250),
	  	   recipe_image_h_b VARCHAR(250),
	  	   recipe_image_v_a VARCHAR(250),
	  	   recipe_thumb_h_a_278x156 VARCHAR(250),
	  	   recipe_thumb_h_b_278x156 VARCHAR(250),
	  	   recipe_video_h VARCHAR(250),
	  	   recipe_video_v VARCHAR(250),
	  	   recipe_date DATE,
	  	   recipe_date_saying VARCHAR(250),
	  	   recipe_time TIME,
	  	   recipe_cusine_id INT,
	  	   recipe_season_id INT,
	  	   recipe_occasion_id INT,
	  	   recipe_ingredient_id INT,
	  	   recipe_ingredient_title VARCHAR(200),
	  	   recipe_marked_as_spam VARCHAR(200),
	  	   recipe_unique_hits INT,
	  	   recipe_unique_hits_ip_block TEXT,
	  	   recipe_comments INT,
	  	   recipe_times_favorited INT,
	  	   recipe_user_ip VARCHAR(250),
	  	   recipe_notes VARCHAR(50),
	  	   recipe_password VARCHAR(120),
	  	   recipe_last_viewed DATETIME,
		   recipe_age_restriction INT,
		   recipe_published INT)")
		   or die(mysqli_error());

		
	}


	echo"
	<!-- //$t_recipes -->


";
?>