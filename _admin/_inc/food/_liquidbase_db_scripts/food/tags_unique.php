<?php
/**
*
* File: _admin/_inc/food/_liquidbase_db_scripts/tags_unique.php
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

	<!-- food_tags_unique -->
	";
	$query = "SELECT * FROM $t_food_tags_unique";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_food_tags_unique: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_food_tags_unique(
	  	 tag_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(tag_id), 
		`tag_language` varchar(20) DEFAULT NULL,
		`tag_title` varchar(250) DEFAULT NULL,
		`tag_title_clean` varchar(250) DEFAULT NULL,
		`tag_number_of_food` int(11) DEFAULT NULL,
		`tag_last_clicked_year` int(11) DEFAULT NULL,
		`tag_last_clicked_month` int(11) DEFAULT NULL,
		`tag_last_clicked_week` int(11) DEFAULT NULL,
		`tag_unique_views_counter` int(11) DEFAULT NULL,
		`tag_unique_views_ip_block` text
	  	   )")
		   or die(mysqli_error());
	}
	echo"
	<!-- //food_tags_unique -->
	
";
?>