<?php
/**
*
* File: _admin\_inc\blog\_liquidbase_db_scripts/posts.php
* Version 2.0.0
* Date 12:13 12.07.2020
* Copyright (c) 2019-2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */


mysqli_query($link, "DROP TABLE IF EXISTS $t_blog_posts") or die(mysqli_error($link));

echo"

	<!-- blog_posts -->
	";
	$query = "SELECT * FROM $t_blog_posts";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_blog_posts: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_blog_posts(
	  	 blog_post_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(blog_post_id), 
	  	   blog_post_user_id INT,
		   blog_post_title_pre VARCHAR(250), 
	  	   blog_post_title VARCHAR(250), 
	  	   blog_post_language VARCHAR(50),
	  	   blog_post_status VARCHAR(50),
	  	   blog_post_category_id INT,
	  	   blog_post_category_title VARCHAR(250),
	  	   blog_post_introduction TEXT, 
	  	   blog_post_privacy_level VARCHAR(25),
	  	   blog_post_text TEXT,  
	  	   blog_post_image_path VARCHAR(250),
	  	   blog_post_image_thumb_small VARCHAR(250),
	  	   blog_post_image_thumb_medium VARCHAR(250),
	  	   blog_post_image_thumb_large VARCHAR(250),
	  	   blog_post_image_file VARCHAR(250),
	  	   blog_post_image_ext VARCHAR(10),
	  	   blog_post_image_text TEXT,
	  	   blog_post_ad INT,
	  	   blog_post_created DATETIME,
	  	   blog_post_created_rss VARCHAR(200),
	  	   blog_post_updated DATETIME,
	  	   blog_post_updated_rss VARCHAR(200),
	  	   blog_post_allow_comments INT,
	  	   blog_post_comments INT, 
	  	   blog_post_views INT, 
	  	   blog_post_views_ipblock VARCHAR(250),
	  	   blog_post_user_ip VARCHAR(250))")
		   or die(mysqli_error());

	}
	echo"
	<!-- //blog_posts -->

";
?>