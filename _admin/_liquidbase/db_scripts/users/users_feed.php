<?php
if(isset($_SESSION['admin_user_id'])){
	$t_users_feeds_index	= $mysqlPrefixSav . "users_feeds_index";
	

	mysqli_query($link,"DROP TABLE IF EXISTS $t_users_feeds_index") or die(mysqli_error());


	mysqli_query($link, "CREATE TABLE $t_users_feeds_index(
			   feed_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(feed_id), 
			   feed_title VARCHAR(200),
			   feed_text TEXT,
			   feed_image_path VARCHAR(200),
			   feed_image_file VARCHAR(200),
			   feed_image_thumb_300x169 VARCHAR(200),
			   feed_image_thumb_540x304 VARCHAR(200),
			   feed_link_url VARCHAR(200),
			   feed_link_name VARCHAR(200),
			   feed_module_name VARCHAR(200),
			   feed_module_part_name VARCHAR(200),
			   feed_module_part_id INT,
			   feed_main_category_id INT,
			   feed_main_category_name VARCHAR(200),
			   feed_sub_category_id INT,
			   feed_sub_category_name VARCHAR(200),
			   feed_user_id INT,
			   feed_user_email VARCHAR(200),
			   feed_user_name VARCHAR(200),
			   feed_user_alias VARCHAR(200),
			   feed_user_photo_file VARCHAR(200),
			   feed_user_photo_thumb_40 VARCHAR(200),
			   feed_user_photo_thumb_50 VARCHAR(200),
			   feed_user_photo_thumb_60 VARCHAR(200),
			   feed_user_photo_thumb_200 VARCHAR(200),
			   feed_user_subscribe INT,
			   feed_user_ip VARCHAR(200),
			   feed_user_hostname VARCHAR(200),
			   feed_language VARCHAR(200),
			   feed_created_datetime DATETIME,
			   feed_created_date_saying VARCHAR(70),
			   feed_created_year INT,
			   feed_created_time VARCHAR(70),
			   feed_modified_datetime DATETIME,
			   feed_likes INT,
			   feed_dislikes INT,
			   feed_comments INT,
			   feed_reported INT,
			   feed_reported_checked INT,
			   feed_reported_reason TEXT)")
			   or die(mysqli_error($link));



}
?>