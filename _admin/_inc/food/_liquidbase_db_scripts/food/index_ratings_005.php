<?php
/**
*
* File: _admin/_inc/food/_liquidbase_db_scripts/food/index_ratings_002.php
* Version 1.0.0
* Date 21:47 31.10.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_food_index_ratings");

echo"
<!-- ratings -->
	";

	
	$query = "SELECT * FROM $t_food_index_ratings";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_food_index_ratings: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_food_index_ratings(
	  	 rating_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(rating_id), 
	  	   rating_food_id INT,
	  	   rating_language VARCHAR(20),
	  	   rating_title VARCHAR(200),
	  	   rating_text TEXT,
	  	   rating_by_user_id INT,
	  	   rating_by_user_name VARCHAR(50),
	  	   rating_by_user_image_path VARCHAR(250),
	  	   rating_by_user_image_file VARCHAR(50),
	  	   rating_by_user_image_thumb_60 VARCHAR(50),
	  	   rating_by_user_ip VARCHAR(200),
	  	   rating_stars INT,
	  	   rating_created DATETIME,
	  	   rating_created_saying VARCHAR(50),
	  	   rating_created_timestamp VARCHAR(50),
	  	   rating_updated DATETIME,
	  	   rating_updated_saying VARCHAR(50),
	  	   rating_likes INT,
	  	   rating_dislikes INT,
	  	   rating_number_of_replies INT,
	  	   rating_read_blog_owner INT,
	  	   rating_reported INT,
	  	   rating_reported_by_user_id INT,
	  	   rating_reported_reason TEXT,
	  	   rating_reported_checked INT)")
		   or die(mysqli_error());

	}
	echo"
<!-- //ratings -->

";
?>