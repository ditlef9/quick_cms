<?php
/**
*
* File: _admin/_inc/recipes/_liquidbase_db_scripts/recipes_stats_chef_of_the_month.php
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
mysqli_query($link,"DROP TABLE IF EXISTS $t_recipes_stats_chef_of_the_month") or die(mysqli_error());


// Stats :: Dayli
$query = "SELECT * FROM $t_recipes_stats_chef_of_the_month LIMIT 1";
$result = mysqli_query($link, $query);

if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_recipes_stats_chef_of_the_month(
				stats_chef_of_the_month_id INT NOT NULL AUTO_INCREMENT,
				PRIMARY KEY(stats_chef_of_the_month_id), 
				stats_chef_of_the_month_month INT,
				stats_chef_of_the_month_month_full VARCHAR(50),
				stats_chef_of_the_month_month_short VARCHAR(50),
				stats_chef_of_the_month_year YEAR,
				stats_chef_of_the_month_user_id INT,
				stats_chef_of_the_month_user_name VARCHAR(200),
				stats_chef_of_the_month_user_photo_path VARCHAR(200),
				stats_chef_of_the_month_user_photo_thumb VARCHAR(200),
				stats_chef_of_the_month_recipes_posted_count INT,
				stats_chef_of_the_month_recipes_posted_points INT,
				stats_chef_of_the_month_got_visits_count INT,
				stats_chef_of_the_month_got_visits_points double,
				stats_chef_of_the_month_got_favorites_count INT,
				stats_chef_of_the_month_got_favorites_points INT,
				stats_chef_of_the_month_got_comments_count INT,
				stats_chef_of_the_month_got_comments_points INT,
				stats_chef_of_the_month_total_points INT)")
				or die(mysqli_error($link));
}


?>