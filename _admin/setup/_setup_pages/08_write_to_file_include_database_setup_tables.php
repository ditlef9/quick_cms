<?php

/*- Check if setup is run ------------------------------------------------------------ */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);
$setup_finished_file = "setup_finished_" . $server_name . ".php";
if(file_exists("../_data/$setup_finished_file")){
	echo"Setup is finished.";
	die;
}

// Mysql Setup
$mysql_config_file = "../_data/mysql_" . $server_name . ".php";
if(!(file_exists("$mysql_config_file"))){
	echo"Missing MySQL info.";
	die;
}

/*- MySQL Tables -------------------------------------------------- */
$t_admin_liquidbase = $mysqlPrefixSav . "admin_liquidbase";
	$t_users 	 		= $mysqlPrefixSav . "users";
	$t_users_friends 		= $mysqlPrefixSav . "users_friends";
	$t_users_friends_requests 	= $mysqlPrefixSav . "users_friends_requests";
	$t_users_profile_photo 		= $mysqlPrefixSav . "users_profile_photo";
	$t_users_status 		= $mysqlPrefixSav . "users_status";
	$t_users_status_subscriptions	= $mysqlPrefixSav . "users_status_subscriptions";
	$t_users_status_replies 	= $mysqlPrefixSav . "users_status_replies";
	$t_users_status_replies_likes 	= $mysqlPrefixSav . "users_status_replies_likes";
	$t_users_status_likes 		= $mysqlPrefixSav . "users_status_likes";
	
	$t_users_cover_photos 		= $mysqlPrefixSav . "users_cover_photos";
	$t_users_email_subscriptions 	= $mysqlPrefixSav . "users_email_subscriptions";
	$t_users_notifications 		= $mysqlPrefixSav . "users_notifications";
	$t_users_moderator_of_the_week	= $mysqlPrefixSav . "users_moderator_of_the_week";
	$t_users_api_sessions 		= $mysqlPrefixSav . "users_api_sessions";

	$t_users_antispam_questions	= $mysqlPrefixSav . "users_antispam_questions";
	$t_users_antispam_answers	= $mysqlPrefixSav . "users_antispam_answers";
	
	$t_pages 			= $mysqlPrefixSav . "pages";
	$t_pages_comments		= $mysqlPrefixSav . "pages_comments";
	$t_pages_navigation 		= $mysqlPrefixSav . "pages_navigation";
	$t_images 			= $mysqlPrefixSav . "images";
	$t_images_paths 		= $mysqlPrefixSav . "images_paths";

	$t_languages 			= $mysqlPrefixSav . "languages";
	$t_languages_active 		= $mysqlPrefixSav . "languages_active";
	
	$t_site_translations_directories = $mysqlPrefixSav . "site_translations_directories";
	$t_site_translations_files       = $mysqlPrefixSav . "site_translations_files";
	$t_site_translations_strings	 = $mysqlPrefixSav . "site_translations_strings";

	$t_admin_translations_directories = $mysqlPrefixSav . "admin_translations_directories";
	$t_admin_translations_files       = $mysqlPrefixSav . "admin_translations_files";
	$t_admin_translations_strings     = $mysqlPrefixSav . "admin_translations_strings";

	$t_admin_navigation		= $mysqlPrefixSav . "admin_navigation";
	$t_admin_messages_inbox     = $mysqlPrefixSav . "admin_messages_inbox";

	$t_social_media 	= $mysqlPrefixSav . "social_media";

	$t_analytics 		= $mysqlPrefixSav . "analytics";

	$t_banned_hostnames  = $mysqlPrefixSav . "banned_hostnames";
	$t_banned_ips  = $mysqlPrefixSav . "banned_ips";
	$t_banned_user_agents  = $mysqlPrefixSav . "banned_user_agents";

// Liquidbase
$query = "SELECT * FROM $t_admin_liquidbase LIMIT 1";
$result = mysqli_query($link, $query);

if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_admin_liquidbase(
	   liquidbase_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(liquidbase_id), 
	   liquidbase_module VARCHAR(200),
	   liquidbase_name VARCHAR(200),
	   liquidbase_run_datetime DATETIME,
	   liquidbase_run_saying VARCHAR(200))")
	  or die(mysqli_error($link));
}



$query = "SELECT * FROM $t_users LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){		
}
else{
	mysqli_query($link, "CREATE TABLE $t_users(
	   user_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(user_id), 
	   user_email VARCHAR(70),
	   user_name VARCHAR(70),
	   user_alias VARCHAR(70),
	   user_password VARCHAR(70),
	   user_password_replacement VARCHAR(70),
	   user_password_date DATE,
	   user_salt VARCHAR(70),
	   user_security INT,
	   user_rank VARCHAR(70),
	   user_verified_by_moderator INT,
	   user_first_name VARCHAR(70),
           user_middle_name VARCHAR(70),
	   user_last_name VARCHAR(70),
	   user_language VARCHAR(6),
	   user_country_id INT,
	   user_country_name VARCHAR(20),
	   user_city_name VARCHAR(40),
	   user_timezone_utc_diff INT,
	   user_timezone_value VARCHAR(60),
	   user_measurement VARCHAR(8),
	   user_date_format VARCHAR(20),
	   user_gender VARCHAR(6),
	   user_height INT,
	   user_dob DATE,
	   user_registered DATETIME,
	   user_registered_time VARCHAR(70),
	   user_registered_date_saying VARCHAR(70),
	   user_newsletter INT,
	   user_privacy VARCHAR(70),
	   user_views INT,
	   user_views_ipblock TEXT,
	   user_points INT,
	   user_points_rank VARCHAR(70),
	   user_likes INT,
	   user_dislikes INT,
	   user_status VARCHAR(70),
	   user_login_tries VARCHAR(70),
	   user_last_online DATETIME,
	   user_last_online_time VARCHAR(70),
	   user_last_ip VARCHAR(70),
	   user_synchronized VARCHAR(70),
	   user_notes VARCHAR(70),
	   user_marked_as_spammer INT)")
	   or die(mysqli_error($link));
}




$query = "SELECT * FROM $t_users_profile_photo LIMIT 1";
$result = mysqli_query($link, $query);

if($result !== FALSE){
		}
		else{
			mysqli_query($link, "CREATE TABLE $t_users_profile_photo(
			   photo_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(photo_id), 
			   photo_user_id INT,
			   photo_profile_image INT,
			   photo_title VARCHAR(250),
			   photo_destination VARCHAR(250),
			   photo_thumb_40 VARCHAR(250),
			   photo_thumb_50 VARCHAR(250),
			   photo_thumb_60 VARCHAR(250),
			   photo_thumb_200 VARCHAR(250),
			   photo_uploaded DATETIME,
		 	   photo_uploaded_ip VARCHAR(250),
		 	   photo_views INT,
			   photo_views_ip_block TEXT,
			   photo_likes INT,
			   photo_comments INT,
			   photo_x_offset INT,
			   photo_y_offset INT,
			   photo_text TEXT)")
			   or die(mysqli_error($link));

		}






$query = "SELECT * FROM $t_users_status LIMIT 1";
		$result = mysqli_query($link, $query);

		if($result !== FALSE){
		}
		else{
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


		}

		$t_users_status_likes = $mysqlPrefixSav . "users_status_likes";
		$query = "SELECT * FROM $t_users_status_likes LIMIT 1";
		$result = mysqli_query($link, $query);

		if($result !== FALSE){
		}
		else{
			mysqli_query($link, "CREATE TABLE $t_users_status_likes(
			   like_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(like_id), 
			   like_status_id INT,
			   like_user_id INT,
			   like_user_alias VARCHAR(70))")
			   or die(mysqli_error($link));
		}







$query = "SELECT * FROM $t_users_friends LIMIT 1";
$result = mysqli_query($link, $query);

		if($result !== FALSE){
		}
		else{
			mysqli_query($link, "CREATE TABLE $t_users_friends(
			   friend_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(friend_id), 
			   friend_user_id_a INT,
			   friend_user_id_b INT,
			   friend_user_alias_a VARCHAR(70),
			   friend_user_alias_b VARCHAR(70),
			   friend_user_image_a VARCHAR(70),
			   friend_user_image_b VARCHAR(70),
			   friend_text_a TEXT,
			   friend_text_b TEXT,
			   friend_datetime DATETIME)")
			   or die(mysqli_error($link));

		}


$query = "SELECT * FROM $t_users_friends_requests LIMIT 1";
$result = mysqli_query($link, $query);

		if($result !== FALSE){
		}
		else{
			mysqli_query($link, "CREATE TABLE $t_users_friends_requests(
			   fr_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(fr_id), 
			   fr_from_user_id INT,
			   fr_to_user_id INT,
			   fr_text TEXT,
			   fr_datetime DATETIME,
			   fr_seen INT,
			   fr_reported INT,
		 	  fr_reported_checked INT,
		 	  fr_reported_text TEXT)")
		 	  or die(mysqli_error($link));

		}

$query = "SELECT * FROM $t_users_cover_photos LIMIT 1";
$result = mysqli_query($link, $query);
		if($result !== FALSE){
		}
		else{
			mysqli_query($link, "CREATE TABLE $t_users_cover_photos(
			   cover_photo_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(cover_photo_id), 
			   cover_photo_user_id INT,
			   cover_photo_is_current INT,
			   cover_photo_destination VARCHAR(90),
			   cover_photo_text VARCHAR(120),
			   cover_photo_datetime DATETIME,
			   cover_photo_ip VARCHAR(90),
			   cover_photo_views INT,
			   cover_photo_views_ip_block VARCHAR(90),
			   cover_photo_likes INT,
			   cover_photo_comments INT,
			   cover_photo_reported INT,
			   cover_photo_reported_checked INT,
			   cover_photo_x_offset INT,
			   cover_photo_y_offset INT)")
			   or die(mysqli_error($link));
		}

		


$query = "SELECT * FROM $t_users_email_subscriptions LIMIT 1";
$result = mysqli_query($link, $query);

		if($result !== FALSE){
		}
		else{
			mysqli_query($link, "CREATE TABLE $t_users_email_subscriptions(
			   es_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(es_id), 
		 	  es_user_id INT,
		 	  es_type VARCHAR(90),
			   es_on_off INT)")
			   or die(mysqli_error($link));
		}





// Users :: Antispam questions
$query = "SELECT * FROM $t_users_antispam_questions LIMIT 1";
$result = mysqli_query($link, $query);

if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_users_antispam_questions(
	   antispam_question_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(antispam_question_id), 
	   antispam_question_language VARCHAR(20),
	   antispam_question VARCHAR(200))")
           or die(mysqli_error($link));
	
	mysqli_query($link, "INSERT INTO $t_users_antispam_questions
	(antispam_question_id, antispam_question_language, antispam_question) 
	VALUES 
	(NULL, 'en', 'What is the capital of USA*?'),
	(NULL, 'no', 'Hva er hovedstaden i Norge*?')")
	or die(mysqli_error($link));
}

// Users :: Antispam answers
$query = "SELECT * FROM $t_users_antispam_answers LIMIT 1";
$result = mysqli_query($link, $query);

if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_users_antispam_answers(
	   antispam_answer_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(antispam_answer_id), 
	   antispam_answer_question_id INT,
	   antispam_answer_language VARCHAR(20),
	   antispam_answer VARCHAR(200))")
           or die(mysqli_error($link));
	
	mysqli_query($link, "INSERT INTO $t_users_antispam_answers
	(antispam_answer_id, antispam_answer_question_id, antispam_answer_language, antispam_answer) 
	VALUES 
	(NULL, '1', 'en', 'washington'),
	(NULL, '1', 'en', 'washington dc'),
	(NULL, '1', 'en', 'washington, d.c.'),
	(NULL, '1', 'en', 'washington. d.c.'),
	(NULL, '2', 'no', 'oslo')")
	or die(mysqli_error($link));
}




// Users :: 
$query = "SELECT * FROM $t_users_api_sessions LIMIT 1";
$result = mysqli_query($link, $query);

if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_users_api_sessions(
	   session_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(session_id), 
	   session_user_id VARCHAR(250),
	   session_device_id VARCHAR(250),
	   session_device_name VARCHAR(250),
	   session_device_source VARCHAR(250),
	   session_user_agent VARCHAR(250),
	   session_ip VARCHAR(250),
	   session_hostname VARCHAR(250),
	   session_start_datetime DATETIME,
	   session_valid_to_datetime DATETIME,
	   session_last_used_datetime DATETIME)")
	   or die(mysqli_error($link));
}



// Pages
$query = "SELECT * FROM $t_pages LIMIT 1";
$result = mysqli_query($link, $query);

if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_pages(
	   page_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(page_id), 
	   page_title VARCHAR(120),
	   page_language VARCHAR(120),
	   page_path VARCHAR(250),
	   page_file_name VARCHAR(250),
	   page_slug VARCHAR(120),
	   page_parent_id INT,
	   page_content TEXT,
	   page_no_of_children INT,
	   page_child_level INT,
	   page_no_of_columns INT,
	   page_created DATETIME,
	   page_created_by_user_id INT,
	   page_updated DATETIME,
	   page_updated_by_user_id INT,
 	   page_allow_comments INT,
	   page_no_of_comments INT,
	   page_uniqe_hits INT,
	   page_uniqe_hits_ip_block  VARCHAR(250),
	   page_show_on_control_panel INT)")
	   or die(mysqli_error($link));

}




// Images 
$query = "SELECT * FROM $t_images LIMIT 1";
$result = mysqli_query($link, $query);

if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_images(
	   image_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(image_id), 
	   image_title VARCHAR(120),
	   image_language VARCHAR(120),
	   image_path VARCHAR(250),
	   image_file_name VARCHAR(250),
	   image_slug VARCHAR(120),
	   image_created DATETIME,
	   image_created_by_user_id INT,
	   image_updated DATETIME,
	   image_updated_by_user_id INT,
 	   image_uniqe_hits INT,
	   image_uniqe_hits_ip_block  VARCHAR(250))")
	   or die(mysqli_error($link));
}

// Images :: Paths
$query = "SELECT * FROM $t_images_paths LIMIT 1";
$result = mysqli_query($link, $query);

if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_images_paths(
	   image_path_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(image_path_id), 
	   image_path_title VARCHAR(250),
	   image_path_parent_id INT,
	   image_path_path VARCHAR(250),
	   image_path_no_of_images INT)")
	   or die(mysqli_error($link));
}







// Site translations :: Directories
$query = "SELECT * FROM $t_site_translations_directories LIMIT 1";
$result = mysqli_query($link, $query);

if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_site_translations_directories(
	   site_translation_directory_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(site_translation_directory_id), 
	   site_translation_directory_name VARCHAR(250),
	   site_translation_directory_level VARCHAR(250))")
	   or die(mysqli_error($link));
}

// Site translations :: Files
$query = "SELECT * FROM $t_site_translations_files LIMIT 1";
$result = mysqli_query($link, $query);

if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_site_translations_files(
	   site_translation_file_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(site_translation_file_id), 
	   site_translation_file_name VARCHAR(250),
	   site_translation_dir_id INT)")
	   or die(mysqli_error($link));
}


// Site translations :: Strings
$query = "SELECT * FROM $t_site_translations_strings LIMIT 1";
$result = mysqli_query($link, $query);

if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_site_translations_strings(
	   site_translation_string_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(site_translation_string_id), 
	   site_translation_string_dir_id INT,
	   site_translation_string_file_id INT,
	   site_translation_string_language VARCHAR(250),
	   site_translation_string_variable VARCHAR(250),
	   site_translation_string_value VARCHAR(250))")
	   or die(mysqli_error($link));
}



// Admin translations :: Directories
$query = "SELECT * FROM $t_admin_translations_directories LIMIT 1";
$result = mysqli_query($link, $query);

if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_admin_translations_directories(
	   admin_translation_directory_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(admin_translation_directory_id), 
	   admin_translation_directory_name VARCHAR(250),
	   admin_translation_directory_level VARCHAR(250))")
	   or die(mysqli_error($link));
}

// Admin translations :: Files
$query = "SELECT * FROM $t_admin_translations_files LIMIT 1";
$result = mysqli_query($link, $query);

if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_admin_translations_files(
	   admin_translation_file_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(admin_translation_file_id), 
	   admin_translation_file_name VARCHAR(250),
	   admin_translation_dir_id INT)")
	   or die(mysqli_error($link));
}


// Admin translations :: Strings
$query = "SELECT * FROM $t_admin_translations_strings LIMIT 1";
$result = mysqli_query($link, $query);

if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_admin_translations_strings(
	   admin_translation_string_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(admin_translation_string_id), 
	   admin_translation_string_dir_id INT,
	   admin_translation_string_file_id INT,
	   admin_translation_string_language VARCHAR(250),
	   admin_translation_string_variable VARCHAR(250),
	   admin_translation_string_value VARCHAR(250))")
	   or die(mysqli_error($link));
}


// Banned: Hostnames	
$query = "SELECT * FROM $t_banned_hostnames";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_banned_hostnames(
  	 banned_hostname_id INT NOT NULL AUTO_INCREMENT,
 	  PRIMARY KEY(banned_hostname_id), 
  	   banned_hostname VARCHAR(256),
  	   banned_hostname_datetime DATETIME,
  	   banned_hostname_reason TEXT)")
	   or print(mysqli_error());

	$fh = fopen("../_inc/dashboard/_banned/banned_hostnames.txt", "r");
	$data = fread($fh, filesize("../_inc/dashboard/_banned/banned_hostnames.txt"));
	fclose($fh);

	$array = explode("\n", $data);

	$datetime = date("Y-m-d H:i:s");
	for($x=0;$x<sizeof($array);$x++){
		$line = explode("|", $array[$x]);
		
		$content = output_html($line[0]);
		$content_mysql = quote_smart($link, $content);

		$reason = output_html($line[1]);
		$reason_mysql = quote_smart($link, $reason);

		$query = "SELECT banned_hostname_id FROM $t_banned_hostnames WHERE banned_hostname=$content_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_banned_hostname_id) = $row;

		if($get_banned_hostname_id == ""){
			// Insert
				mysqli_query($link, "INSERT INTO $t_banned_hostnames
				(banned_hostname_id, banned_hostname, banned_hostname_datetime, banned_hostname_reason) 
				VALUES 
				(NULL, $content_mysql, '$datetime', $reason_mysql)")
				or die(mysqli_error($link));
		}
	}
}

	
// Banned: IPS
$query = "SELECT * FROM $t_banned_ips";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_banned_ips(
  	 banned_ip_id INT NOT NULL AUTO_INCREMENT,
 	  PRIMARY KEY(banned_ip_id), 
  	   banned_ip VARCHAR(256),
  	   banned_ip_datetime DATETIME,
  	   banned_ip_reason TEXT)")
	   or print(mysqli_error());

	$fh = fopen("../_inc/dashboard/_banned/banned_ips.txt", "r");
	$data = fread($fh, filesize("../_inc/dashboard/_banned/banned_ips.txt"));
	fclose($fh);

	$array = explode("\n", $data);
		
	$datetime = date("Y-m-d H:i:s");
	for($x=0;$x<sizeof($array);$x++){
		$line = explode("|", $array[$x]);
		
		$content = output_html($line[0]);
		$content_mysql = quote_smart($link, $content);
		$reason = output_html($line[1]);
		$reason_mysql = quote_smart($link, $reason);

		$query = "SELECT banned_ip_id FROM $t_banned_ips WHERE banned_ip=$content_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_banned_ip_id) = $row;

		if($get_banned_ip_id == ""){
			// Insert
			mysqli_query($link, "INSERT INTO $t_banned_ips
			(banned_ip_id, banned_ip, banned_ip_datetime, banned_ip_reason) 
			VALUES 
			(NULL, $content_mysql, '$datetime', $reason_mysql)")
			or die(mysqli_error($link));
		}
	}
}

// Banned: User Agents
$query = "SELECT * FROM $t_banned_user_agents";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_banned_user_agents(
  	 banned_user_agent_id INT NOT NULL AUTO_INCREMENT,
 	  PRIMARY KEY(banned_user_agent_id), 
  	   banned_user_agent VARCHAR(256),
  	   banned_user_agent_datetime DATETIME,
  	   banned_user_agent_reason TEXT)")
	   or print(mysqli_error());

	$fh = fopen("../_inc/dashboard/_banned/banned_user_agents.txt", "r");
	$data = fread($fh, filesize("../_inc/dashboard/_banned/banned_user_agents.txt"));
	fclose($fh);

	$array = explode("\n", $data);
				
	$datetime = date("Y-m-d H:i:s");
	for($x=0;$x<sizeof($array);$x++){
		$line = explode("|", $array[$x]);
			
		$content = output_html($line[0]);
		$content_mysql = quote_smart($link, $content);

		$reason = output_html($line[1]);
		$reason_mysql = quote_smart($link, $reason);

		$query = "SELECT banned_user_agent_id FROM $t_banned_user_agents WHERE banned_user_agent=$content_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_banned_user_agent_id) = $row;

		if($get_banned_user_agent_id == ""){
			// Insert
			mysqli_query($link, "INSERT INTO $t_banned_user_agents
			(banned_user_agent_id, banned_user_agent, banned_user_agent_datetime, banned_user_agent_reason) 
			VALUES 
			(NULL, $content_mysql, '$datetime', $reason_mysql)")
			or die(mysqli_error($link));
		}
	}
}


// Analytics
$query = "SELECT * FROM $t_analytics";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_analytics(
	 analytics_id INT NOT NULL AUTO_INCREMENT,
	 PRIMARY KEY(analytics_id), 
	 analytics_language VARCHAR(256),
	 analytics_title VARCHAR(256),
	 analytics_notes TEXT,
	 analytics_code VARCHAR(256),
	 analytics_created DATETIME,
	 analytics_created_by VARCHAR(256),
	 analytics_updated DATETIME,
	 analytics_updated_by VARCHAR(256),
	 analytics_active INT)")
	 or print(mysqli_error());
}




?>
