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

	<!-- blog_links_index -->
	";
	$query = "SELECT * FROM $t_blog_links_index";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_blog_links_index: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_blog_links_index(
	  	 link_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(link_id), 
	  	   link_blog_info_id INT,
	  	   link_user_id INT,
	  	   link_category_id INT,
	  	   link_title VARCHAR(200),
	  	   link_url_real VARCHAR(200),
	  	   link_url_display VARCHAR(200),
	  	   link_description VARCHAR(200),
	  	   link_is_ad INT,
	  	   link_img_path VARCHAR(200),
	  	   link_img_file VARCHAR(200),
	  	   link_clicks_unique INT,
	  	   link_clicks_unique_ipblock TEXT,
	  	   link_added DATETIME,
	  	   link_edited DATETIME)")
		   or die(mysqli_error());
	}
	echo"
	<!-- //blog_links_index -->

";
?>