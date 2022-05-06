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


	mysqli_query($link, "DROP TABLE IF EXISTS $t_blog_images") or die(mysqli_error());
echo"

	<!-- blog_images -->
	";
	$query = "SELECT * FROM $t_blog_images";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_blog_images: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_blog_images(
	  	 image_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(image_id), 
	  	   image_user_id INT,
	  	   image_blog_post_id INT,
	  	   image_title VARCHAR(200),
	  	   image_text VARCHAR(200),
	  	   image_path VARCHAR(200),
	  	   image_thumb_a VARCHAR(200),
	  	   image_thumb_b VARCHAR(200),
	  	   image_thumb_c VARCHAR(200),
	  	   image_file VARCHAR(200),
	  	   image_photo_by_name VARCHAR(200),
	  	   image_photo_by_website VARCHAR(200),
	  	   image_uploaded_datetime DATETIME,
	  	   image_uploaded_ip VARCHAR(200),
	  	   image_unique_views INT,
	  	   image_ip_block TEXT,
	  	   image_reported INT,
	  	   image_reported_checked VARCHAR(200),
	  	   image_likes INT,
	  	   image_dislikes INT,
	  	   image_likes_dislikes_ipblock TEXT,
	  	   image_comments INT)")
		   or die(mysqli_error());

	}
	echo"
	<!-- //blog_images -->


";
?>