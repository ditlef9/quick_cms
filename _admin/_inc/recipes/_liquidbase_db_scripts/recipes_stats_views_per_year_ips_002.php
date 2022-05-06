<?php
/**
*
* File: _admin/_inc/recipes/_liquidbase_db_scripts/recipes_stats_views_per_year_ips.php
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
mysqli_query($link,"DROP TABLE IF EXISTS $t_recipes_stats_views_per_year_ips") or die(mysqli_error());


// Stats :: Dayli
$query = "SELECT * FROM $t_recipes_stats_views_per_year_ips LIMIT 1";
$result = mysqli_query($link, $query);

if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_recipes_stats_views_per_year_ips(
				stats_visit_per_year_ip_id INT NOT NULL AUTO_INCREMENT,
				PRIMARY KEY(stats_visit_per_year_ip_id), 
				stats_visit_per_year_year YEAR,
				stats_visit_per_year_recipe_id INT,
				stats_visit_per_year_ip VARCHAR(500))")
				or die(mysqli_error($link));
}


?>