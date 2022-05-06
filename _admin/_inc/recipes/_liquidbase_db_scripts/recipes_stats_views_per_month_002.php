<?php
/**
*
* File: _admin/_inc/recipes/_liquidbase_db_scripts/recipes_stats_views_per_month.php
* Version 1.0.0
* Date 22:29 10.01.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}




// Drop table
mysqli_query($link,"DROP TABLE IF EXISTS $t_recipes_stats_views_per_month") or die(mysqli_error());


// Stats :: Dayli
$query = "SELECT * FROM $t_recipes_stats_views_per_month LIMIT 1";
$result = mysqli_query($link, $query);

if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_recipes_stats_views_per_month(
				stats_visit_per_month_id INT NOT NULL AUTO_INCREMENT,
				PRIMARY KEY(stats_visit_per_month_id), 
				stats_visit_per_month_month INT,
				stats_visit_per_month_month_full VARCHAR(50),
				stats_visit_per_month_month_short VARCHAR(50),
				stats_visit_per_month_year YEAR,
				stats_visit_per_month_recipe_id INT,
				stats_visit_per_month_recipe_title VARCHAR(200),
				stats_visit_per_month_recipe_image_path VARCHAR(200),
				stats_visit_per_month_recipe_thumb_278x156 VARCHAR(200),
				stats_visit_per_month_recipe_language VARCHAR(20),
				stats_visit_per_month_recipe_category_id INT,
				stats_visit_per_month_recipe_category_translated VARCHAR(200),
				stats_visit_per_month_count INT)")
				or die(mysqli_error($link));
}


?>