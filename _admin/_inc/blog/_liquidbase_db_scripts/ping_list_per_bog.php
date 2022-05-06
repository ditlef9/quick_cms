<?php
/**
*
* File: _admin/_inc/blog/_liquibase/info.php
* Version 1.0.0
* Date 21:19 28.08.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
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



	<!-- blog_ping_list_per_bog -->
	";
	$query = "SELECT * FROM $t_blog_ping_list_per_blog";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_blog_ping_list_per_blog: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_blog_ping_list_per_blog(
	  	 ping_list_per_blog_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(ping_list_per_blog_id), 
	  	   ping_list_per_blog_blog_info_id INT,
	  	   ping_list_per_blog_user_id INT,
	  	   ping_list_per_blog_title VARCHAR(200),
	  	   ping_list_per_blog_url VARCHAR(200),
	  	   ping_list_per_blog_last_pinged_year INT,
	  	   ping_list_per_blog_last_pinged_month INT,
	  	   ping_list_per_blog_last_pinged_day INT,
	  	   ping_list_per_blog_last_pinged_datetime_print VARCHAR(200),
	  	   ping_list_per_blog_added DATETIME,
	  	   ping_list_per_blog_edited DATETIME)")
		   or die(mysqli_error());
	}
	echo"
	<!-- //blog_ping_list_per_bog -->


";
?>