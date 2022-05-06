<?php
/**
*
* File: _admin/_inc/blog/_liquibase/blog_stats_most_used_categories.php
* Version 1.0.0
* Date 16:13 12.07.2020
* Copyright (c) 2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */



echo"
	<!-- blog_stats_most_used_categories -->
	";

	
	$query = "SELECT * FROM $t_blog_stats_most_used_categories";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_blog_stats_most_used_categories: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_blog_stats_most_used_categories(
	  	 stats_cat_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(stats_cat_id), 
	  	   stats_cat_language VARCHAR(50),
	  	   stats_cat_title VARCHAR(250),
	  	   stats_cat_title_clean VARCHAR(250),
	  	   stats_cat_posts INT, 
	  	   stats_cat_last_used_datetime DATETIME, 
	  	   stats_cat_last_used_year INT, 
	  	   stats_cat_last_used_user_id INT, 
	  	   stats_cat_last_used_user_ip VARCHAR(250),
	  	   stats_cat_last_used_user_host VARCHAR(250),
	  	   stats_cat_last_used_user_user_agent VARCHAR(250))")
		   or die(mysqli_error());

	}
	echo"
	<!-- //blog_stats_most_used_categories -->

";
?>