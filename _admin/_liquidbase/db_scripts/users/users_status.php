<?php
if(isset($_SESSION['admin_user_id'])){
	$t_users_status 		= $mysqlPrefixSav . "users_status";
	$t_users_status_subscriptions	= $mysqlPrefixSav . "users_status_subscriptions";
	$t_users_status_likes 		= $mysqlPrefixSav . "users_status_likes";
	$t_users_status_replies 	= $mysqlPrefixSav . "users_status_replies";
	$t_users_status_replies_likes 	= $mysqlPrefixSav . "users_status_replies_likes";


	mysqli_query($link,"DROP TABLE IF EXISTS $t_users_status") or die(mysqli_error());
	mysqli_query($link,"DROP TABLE IF EXISTS $t_users_status_subscriptions") or die(mysqli_error());
	mysqli_query($link,"DROP TABLE IF EXISTS $t_users_status_likes") or die(mysqli_error());
	mysqli_query($link,"DROP TABLE IF EXISTS $t_users_status_replies") or die(mysqli_error());
	mysqli_query($link,"DROP TABLE IF EXISTS $t_users_status_replies_likes") or die(mysqli_error());


	mysqli_query($link, "CREATE TABLE $t_users_status(
			   status_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(status_id), 
			   status_user_id INT,
			   status_created_by_user_id INT,
			   status_created_by_user_alias VARCHAR(200),
			   status_created_by_user_image VARCHAR(200),
			   status_created_by_ip VARCHAR(200),
			   status_text TEXT,
			   status_photo VARCHAR(70),
			   status_datetime DATETIME,
			   status_datetime_print VARCHAR(200),
			   status_time VARCHAR(200),
			   status_language VARCHAR(6),
			   status_likes INT,
			   status_comments INT,
			   status_reported INT,
			   status_reported_checked INT,
			   status_reported_reason TEXT,
			   status_seen INT)")
			   or die(mysqli_error($link));


	mysqli_query($link, "CREATE TABLE $t_users_status_subscriptions(
	 subscription_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(subscription_id), 
	 subscription_status_id INT,
	 subscription_user_id INT,
	 subscription_user_email VARCHAR(200),
	 subscription_user_alias VARCHAR(70),
	 subscription_user_email_sent DATETIME,
	 subscription_user_email_sent_time VARCHAR(70),
	 subscription_user_email_seen VARCHAR(70))")
	 or die(mysqli_error($link));

	mysqli_query($link, "CREATE TABLE $t_users_status_likes(
			   like_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(like_id), 
			   like_status_id INT,
			   like_user_id INT,
			   like_user_alias VARCHAR(70))")
			   or die(mysqli_error($link));

			mysqli_query($link, "CREATE TABLE $t_users_status_replies(
			   reply_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(reply_id), 
			   reply_status_id INT,
			   reply_user_id INT,
		 	   reply_parent_id INT,
			   reply_created_by_user_id INT,
			   reply_created_by_user_alias VARCHAR(200),
			   reply_created_by_user_image VARCHAR(200),
			   reply_created_by_ip VARCHAR(200),
		 	   reply_text TEXT,
		 	   reply_likes INT,
		 	   reply_datetime DATETIME,
			   reply_datetime_print VARCHAR(200),
			   reply_time VARCHAR(200),
		 	   reply_reported INT,
			   reply_reported_checked INT,
			   reply_reported_reason TEXT,
			   reply_seen INT)")
			   or die(mysqli_error($link));

			mysqli_query($link, "CREATE TABLE $t_users_status_replies_likes(
			   like_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(like_id), 
			   like_reply_id INT,
			   like_user_id INT,
			   like_user_alias VARCHAR(70))")
			   or die(mysqli_error($link));


}
?>