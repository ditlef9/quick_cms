<?php
/**
*
* File: _admin/_inc/knowledge/_liquibase/knowledge/001_knowledge.php
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



$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_knowledge_spaces_index") or die(mysqli_error($link)); 
$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_knowledge_spaces_categories") or die(mysqli_error($link)); 
$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_knowledge_spaces_members") or die(mysqli_error($link)); 
$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_knowledge_spaces_requested_memberships") or die(mysqli_error($link)); 
$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_knowledge_spaces_favorites") or die(mysqli_error($link)); 

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_knowledge_pages_index") or die(mysqli_error($link)); 
$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_knowledge_pages_edit_history") or die(mysqli_error($link)); 
$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_knowledge_pages_tags") or die(mysqli_error($link)); 
$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_knowledge_pages_comments") or die(mysqli_error($link)); 
$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_knowledge_pages_favorites") or die(mysqli_error($link)); 
$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_knowledge_pages_view_history") or die(mysqli_error($link)); 
$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_knowledge_pages_media") or die(mysqli_error($link)); 
$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_knowledge_pages_diagrams") or die(mysqli_error($link)); 
$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_knowledge_preselected_subscribe") or die(mysqli_error($link)); 
$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_knowledge_home_page_user_remember") or die(mysqli_error($link)); 

echo"

<!-- spaces_spaces_index -->
";

$query = "SELECT * FROM $t_knowledge_spaces_index LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_knowledge_spaces_index: $row_cnt</p>
	";
}
else{

	mysqli_query($link, "CREATE TABLE $t_knowledge_spaces_index(
	  space_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(space_id), 
	   space_title VARCHAR(200), 
	   space_title_clean VARCHAR(200), 
	   space_category_id INT, 
	   space_description TEXT, 
	   space_text TEXT, 
	   space_image VARCHAR(200), 
	   space_thumb_32 VARCHAR(200), 
	   space_thumb_16 VARCHAR(200), 
	   space_language VARCHAR(200), 
	   space_is_archived INT, 
	   space_unique_hits INT, 
	   space_unique_hits_ip_block TEXT, 
	   space_unique_hits_user_id_block TEXT, 
	   space_created_datetime DATETIME, 
	   space_created_date_saying VARCHAR(200), 
	   space_created_user_id INT, 
	   space_created_user_alias VARCHAR(200), 
	   space_created_user_image VARCHAR(200), 
	   space_updated_datetime DATETIME, 
	   space_updated_date_saying VARCHAR(200), 
	   space_updated_user_id INT, 
	   space_updated_user_alias VARCHAR(200), 
	   space_updated_user_image VARCHAR(200))")
	   or die(mysqli_error());	

	// Dates
	$datetime = date("Y-m-d H:i:s");
	$date_saying = date("j M Y");


	// Me
	$query = "SELECT user_id, user_email, user_name, user_alias, user_language, user_last_online, user_rank, user_login_tries FROM $t_users WHERE user_id=$my_user_id_mysql AND user_security=$my_security_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_language, $get_my_user_last_online, $get_my_user_rank, $get_my_user_login_tries) = $row;
	
	// Get my photo
	$query = "SELECT photo_id, photo_destination, photo_thumb_40 FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_40) = $row;


	$inp_my_user_alias_mysql = quote_smart($link, $get_my_user_alias);
	$inp_my_user_email_mysql = quote_smart($link, $get_my_user_email);
	$inp_my_user_image_mysql = quote_smart($link, $get_my_photo_destination);
	
	mysqli_query($link, "INSERT INTO $t_knowledge_spaces_index
	(space_id, space_title, space_title_clean, space_category_id, space_description, space_image, space_is_archived, space_unique_hits, space_unique_hits_ip_block, space_created_datetime, space_created_date_saying, space_created_user_id, space_created_user_alias, space_created_user_image, space_updated_datetime, space_updated_date_saying, space_updated_user_id, space_updated_user_alias, space_updated_user_image) 
	VALUES 
	(NULL, 'Knowledge base', 'knowledge_base', '1', 'How we do things at our company', 'spaces.png', '0', '0', '', '$datetime', '$date_saying', $get_my_user_id, $inp_my_user_alias_mysql, $inp_my_user_image_mysql, '$datetime', '$date_saying', $get_my_user_id, $inp_my_user_alias_mysql, $inp_my_user_image_mysql),
	(NULL, 'Test space', 'test_space', '1', 'Here you can test the spaces. You can edit, delelete pages as you wish.', 'spaces.png', '0', '0', '', '$datetime', '$date_saying', $get_my_user_id, $inp_my_user_alias_mysql, $inp_my_user_image_mysql, '$datetime', '$date_saying', $get_my_user_id, $inp_my_user_alias_mysql, $inp_my_user_image_mysql)")
	or die(mysqli_error($link));

}
echo"
<!-- //knowledge_spaces_index -->

<!-- spaces_categories -->
";

$query = "SELECT * FROM $t_knowledge_spaces_categories LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_knowledge_spaces_categories: $row_cnt</p>
	";
}
else{

	mysqli_query($link, "CREATE TABLE $t_knowledge_spaces_categories(
	  category_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(category_id), 
	   category_title VARCHAR(200), 
	   category_title_clean VARCHAR(200), 
	   category_created_datetime VARCHAR(200))")
	   or die(mysqli_error());	

	$datetime = date("Y-m-d H:i:s");

	mysqli_query($link, "INSERT INTO $t_knowledge_spaces_categories
	(category_id, category_title, category_title_clean, category_created_datetime) 
	VALUES 
	(NULL, 'Knowledge bases', 'knowledge_bases', '$datetime')")
	or die(mysqli_error($link));

}
echo"
<!-- //spaces_categories -->


<!-- knowledge_spaces_members -->
";

$query = "SELECT * FROM $t_knowledge_spaces_members LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_knowledge_spaces_members: $row_cnt</p>
	";
}
else{

	mysqli_query($link, "CREATE TABLE $t_knowledge_spaces_members(
	  member_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(member_id), 
	   member_space_id INT, 
	   member_rank VARCHAR(200),
	   member_user_id INT, 
	   member_user_name VARCHAR(200), 
	   member_user_alias VARCHAR(200), 
	   member_user_email VARCHAR(200), 
	   member_user_first_name VARCHAR(200), 
	   member_user_middle_name VARCHAR(200), 
	   member_user_last_name VARCHAR(200), 
	   member_user_image VARCHAR(200),
	   member_user_position VARCHAR(200),
	   member_user_department VARCHAR(200),
	   member_user_location VARCHAR(200),
	   member_user_about TEXT,
	   member_added_datetime DATETIME, 
	   member_added_date_saying VARCHAR(200), 
	   member_added_by_user_id INT, 
	   member_added_by_user_alias VARCHAR(200), 
	   member_added_by_user_image VARCHAR(200))")
	   or die(mysqli_error());	

	// Dates
	$datetime = date("Y-m-d H:i:s");
	$date_saying = date("j M Y");


	// Add all to this spaces
	$query = "SELECT user_id, user_email, user_name, user_alias  FROM $t_users WHERE user_rank='admin'";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_user_id, $get_user_email, $get_user_name, $get_user_alias) = $row;

	
		// Get photo
		$query_p = "SELECT photo_id, photo_destination, photo_thumb_40 FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
		$result_p = mysqli_query($link, $query_p);
		$row_p = mysqli_fetch_row($result_p);
		list($get_photo_id, $get_photo_destination, $get_photo_thumb_40) = $row_p;


		$inp_user_name_mysql = quote_smart($link, $get_user_name);
		$inp_user_alias_mysql = quote_smart($link, $get_user_alias);
		$inp_user_email_mysql = quote_smart($link, $get_user_email);
		$inp_user_image_mysql = quote_smart($link, $get_photo_destination);
	
		mysqli_query($link, "INSERT INTO $t_knowledge_spaces_members
		(member_id, member_space_id, member_rank, member_user_id, member_user_name, member_user_alias, member_user_email, member_user_image, member_user_about, member_added_datetime, member_added_date_saying, member_added_by_user_id, member_added_by_user_alias, member_added_by_user_image) 
		VALUES 
		(NULL, 1, 'admin', $get_user_id, $inp_user_name_mysql, $inp_user_alias_mysql, $inp_user_email_mysql, $inp_user_image_mysql, '', '$datetime', '$date_saying', 1, 'Administrator', '')")
		or die(mysqli_error($link));
	}
}
echo"
<!-- //knowledge_spaces_members -->

<!-- knowledge_spaces_requested_memberships -->
";

$query = "SELECT * FROM $t_knowledge_spaces_requested_memberships LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_knowledge_spaces_requested_memberships: $row_cnt</p>
	";
}
else{

	mysqli_query($link, "CREATE TABLE $t_knowledge_spaces_requested_memberships(
	  requested_membership_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(requested_membership_id), 
	   requested_membership_space_id INT, 
	   requested_membership_user_id INT, 
	   requested_membership_user_name VARCHAR(200), 
	   requested_membership_user_alias VARCHAR(200), 
	   requested_membership_user_email VARCHAR(200), 
	   requested_membership_user_first_name VARCHAR(200), 
	   requested_membership_user_middle_name VARCHAR(200), 
	   requested_membership_user_last_name VARCHAR(200), 
	   requested_membership_user_image VARCHAR(200),
	   requested_membership_user_position VARCHAR(200),
	   requested_membership_user_department VARCHAR(200),
	   requested_membership_user_location VARCHAR(200),
	   requested_membership_user_about TEXT,
	   requested_membership_datetime DATETIME, 
	   requested_membership_date_saying VARCHAR(200))")
	   or die(mysqli_error());	

}
echo"
<!-- //knowledge_spaces_requested_memberships -->





<!-- knowledge_spaces_favorites -->
";

$query = "SELECT * FROM $t_knowledge_spaces_favorites LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_knowledge_spaces_favorites: $row_cnt</p>
	";
}
else{

	mysqli_query($link, "CREATE TABLE $t_knowledge_spaces_favorites(
	 favorite_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(favorite_id), 
	   favorite_space_id INT,
	   favorite_user_id INT,
	   favorite_category_id INT,
	   favorite_space_title VARCHAR(200),
	   favorite_space_description VARCHAR(200))")
	   or die(mysqli_error());

}
echo"
<!-- //knowledge_spaces_favorites -->

<!-- knowledge_pages_index -->
";

$query = "SELECT * FROM $t_knowledge_pages_index LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_knowledge_pages_index: $row_cnt</p>
	";
}
else{

	mysqli_query($link, "CREATE TABLE $t_knowledge_pages_index(
	  page_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(page_id), 
	   page_space_id INT, 
	   page_title VARCHAR(200), 
	   page_title_clean VARCHAR(200), 
	   page_description TEXT, 
	   page_text TEXT,
	   page_language VARCHAR(200), 
	   page_parent_id INT, 
	   page_no_of_children INT, 
	   page_weight INT, 
	   page_allow_comments INT, 
	   page_no_of_comments INT, 
	   page_unique_hits INT, 
	   page_unique_hits_ip_block TEXT, 
	   page_unique_hits_user_id_block TEXT, 
	   page_created_datetime DATETIME, 
	   page_created_date_saying VARCHAR(200), 
	   page_created_user_id INT, 
	   page_created_user_name VARCHAR(200), 
	   page_created_user_alias VARCHAR(200), 
	   page_created_user_email VARCHAR(200), 
	   page_created_user_image VARCHAR(200), 
	   page_created_subscribe_to_comments INT, 
	   page_updated_datetime DATETIME, 
	   page_updated_date_saying VARCHAR(200), 
	   page_updated_user_id INT, 
	   page_updated_user_name VARCHAR(200), 
	   page_updated_user_alias VARCHAR(200), 
	   page_updated_user_email VARCHAR(200), 
	   page_updated_user_image VARCHAR(200), 
	   page_updated_subscribe_to_comments INT, 
	   page_updated_info TEXT,
	   page_version INT)")
	   or die(mysqli_error());	

	// Dates
	$datetime = date("Y-m-d H:i:s");
	$date_saying = date("j M Y");


	// Me
	$query = "SELECT user_id, user_email, user_name, user_alias, user_language, user_last_online, user_rank, user_login_tries FROM $t_users WHERE user_id=$my_user_id_mysql AND user_security=$my_security_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_language, $get_my_user_last_online, $get_my_user_rank, $get_my_user_login_tries) = $row;
	
	// Get my photo
	$query = "SELECT photo_id, photo_destination, photo_thumb_40 FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_40) = $row;


	$inp_my_user_alias_mysql = quote_smart($link, $get_my_user_alias);
	$inp_my_user_image_mysql = quote_smart($link, $get_my_photo_destination);
	
	mysqli_query($link, "INSERT INTO $t_knowledge_pages_index
	(page_id, page_title, page_title_clean, page_description, page_text, page_parent_id, page_weight, page_allow_comments, page_no_of_comments, page_unique_hits, page_created_datetime, page_created_date_saying, page_created_user_id, page_created_user_alias, page_created_user_image, page_updated_datetime, page_updated_date_saying, page_updated_user_id, page_updated_user_alias, page_updated_user_image) 
	VALUES 
	(1, 'Home page', 'homepage', 'Welcome to our team page', 'This is the start page', '0', '0', '1', '0', '0', '$datetime', '$date_saying', $get_my_user_id, $inp_my_user_alias_mysql, $inp_my_user_image_mysql, '$datetime', '$date_saying', $get_my_user_id, $inp_my_user_alias_mysql, $inp_my_user_image_mysql)")
	or die(mysqli_error($link));

}
echo"
<!-- //knowledge_pages_index -->



<!-- knowledge_pages_edit_history -->
";

$query = "SELECT * FROM $t_knowledge_pages_edit_history	LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_knowledge_pages_edit_history: $row_cnt</p>
	";
}
else{

	mysqli_query($link, "CREATE TABLE $t_knowledge_pages_edit_history(
	  history_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(history_id), 
	   history_page_id INT, 
	   history_page_version INT, 
	   history_page_is_deleted INT,
	   history_page_title VARCHAR(200), 
	   history_page_title_clean VARCHAR(200), 
	   history_page_description TEXT, 
	   history_page_text TEXT,
	   history_page_parent_id INT, 
	   history_weight INT,
 	   history_allow_comments INT, 
	   history_page_no_of_comments INT, 
	   history_page_updated_datetime DATETIME, 
	   history_page_updated_date_saying VARCHAR(200), 
	   history_page_updated_user_id INT, 
	   history_page_updated_user_name VARCHAR(200), 
	   history_page_updated_user_alias VARCHAR(200), 
	   history_page_updated_user_image VARCHAR(200), 
	   history_page_ip VARCHAR(200), 
	   history_page_hostname VARCHAR(200), 
	   history_page_user_agent VARCHAR(200), 
	   history_can_be_deleted_year INT)")
	   or die(mysqli_error());	


}
echo"
<!-- //knowledge_pages_edit_history-->


<!-- knowledge_pages_tags -->
";

$query = "SELECT * FROM $t_knowledge_pages_tags LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_knowledge_pages_tags: $row_cnt</p>
	";
}
else{

	mysqli_query($link, "CREATE TABLE $t_knowledge_pages_tags(
	  tag_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(tag_id), 
	   tag_page_id INT, 
	   tag_title VARCHAR(200), 
	   tag_title_clean VARCHAR(200))")
	   or die(mysqli_error());	

	mysqli_query($link, "INSERT INTO $t_knowledge_pages_tags
	(tag_id, tag_page_id, tag_title, tag_title_clean) 
	VALUES 
	(1, '1', 'home', 'home')")
	or die(mysqli_error($link));

}
echo"
<!-- //knowledge_pages_tags -->



<!-- knowledge_pages_comments -->
";

$query = "SELECT * FROM $t_knowledge_pages_comments LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_knowledge_pages_comments: $row_cnt</p>
	";
}
else{

	mysqli_query($link, "CREATE TABLE $t_knowledge_pages_comments(
	 comment_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(comment_id), 
	   comment_page_id INT,
	   comment_parent_comment_id INT,
	   comment_title VARCHAR(250),
	   comment_text TEXT, 
	   comment_approved INT,
	   comment_datetime DATETIME,
	   comment_time VARCHAR(200),
	   comment_date_print VARCHAR(200),
	   comment_user_id INT,
	   comment_user_name VARCHAR(250),
	   comment_user_alias VARCHAR(250),
	   comment_user_email VARCHAR(250),
	   comment_user_image_file VARCHAR(250),
	   comment_user_ip VARCHAR(250),
	   comment_user_hostname VARCHAR(250),
	   comment_user_agent VARCHAR(250),
	   comment_subscribe_to_new_comments INT, 
	   comment_rating INT, 
	   comment_helpful_clicks INT,
	   comment_useless_clicks INT,
 	   comment_marked_as_spam INT,
	   comment_spam_checked INT,
	   comment_spam_checked_comment TEXT,
	   comment_read_by_page_author INT)")
	   or die(mysqli_error());

}
echo"
<!-- //knowledge_pages_comments -->




<!-- knowledge_pages_favorites -->
";

$query = "SELECT * FROM $t_knowledge_pages_favorites LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_knowledge_pages_favorites: $row_cnt</p>
	";
}
else{

	mysqli_query($link, "CREATE TABLE $t_knowledge_pages_favorites(
	 favorite_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(favorite_id), 
	   favorite_page_id INT,
	   favorite_space_id INT,
	   favorite_user_id INT,
	   favorite_category_id INT,
	   favorite_page_title VARCHAR(200),
	   favorite_page_description VARCHAR(200))")
	   or die(mysqli_error());

}
echo"
<!-- //knowledge_pages_favorites -->


<!-- knowledge_pages_view_history -->
";

$query = "SELECT * FROM $t_knowledge_pages_view_history LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_knowledge_pages_view_history: $row_cnt</p>
	";
}
else{

	mysqli_query($link, "CREATE TABLE $t_knowledge_pages_view_history(
	 history_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(history_id), 
	   history_page_id INT,
	   history_space_id INT,
	   history_user_id INT,
	   history_page_title VARCHAR(200),
	   history_page_description VARCHAR(200),
	   history_page_viewed_datetime DATETIME,
	   history_page_viewed_year INT)")
	   or die(mysqli_error());

}
echo"
<!-- //knowledge_pages_view_history -->



<!-- knowledge_preselected_subscribe -->
";

$query = "SELECT * FROM $t_knowledge_preselected_subscribe LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_knowledge_preselected_subscribe: $row_cnt</p>
	";
}
else{

	mysqli_query($link, "CREATE TABLE $t_knowledge_preselected_subscribe(
	 preselected_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(preselected_id), 
	   preselected_user_id INT,
	   preselected_subscribe INT)")
	   or die(mysqli_error());

}
echo"
<!-- //knowledge_preselected_subscribe -->


<!-- knowledge_pages_media -->
";

$query = "SELECT * FROM $t_knowledge_pages_media LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_knowledge_pages_media: $row_cnt</p>
	";
}
else{

	mysqli_query($link, "CREATE TABLE $t_knowledge_pages_media(
	 media_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(media_id), 
	   media_space_id INT,
	   media_page_id INT,
	   media_type VARCHAR(250),
	   media_ext VARCHAR(250),
	   media_version VARCHAR(250),
	   media_title VARCHAR(250),
	   media_file_path VARCHAR(250),
	   media_file_name VARCHAR(250),
	   media_file_thumb_800 VARCHAR(250),
	   media_file_thumb_100 VARCHAR(250),
	   media_unique_hits INT,
	   media_unique_hits_ip_block TEXT,
	   media_unique_hits_user_id_block TEXT,
	   media_created_datetime DATETIME,
	   media_created_date_saying VARCHAR(250),
	   media_created_by_user_id VARCHAR(250),
	   media_created_by_user_name VARCHAR(250),
	   media_created_by_user_alias VARCHAR(250),
	   media_created_by_user_email VARCHAR(250),
	   media_created_by_user_image_file VARCHAR(250),
	   media_created_by_user_ip VARCHAR(250),
	   media_created_by_user_hostname VARCHAR(250),
	   media_created_by_user_agent VARCHAR(250),
	   media_updated_datetime DATETIME,
	   media_updated_date_saying VARCHAR(250),
	   media_updated_by_user_id VARCHAR(250),
	   media_updated_by_user_name VARCHAR(250),
	   media_updated_by_user_alias VARCHAR(250),
	   media_updated_by_user_email VARCHAR(250),
	   media_updated_by_user_image_file VARCHAR(250),
	   media_updated_by_user_ip VARCHAR(250),
	   media_updated_by_user_hostname VARCHAR(250),
	   media_updated_by_user_agent VARCHAR(250))")
	   or die(mysqli_error());
}
echo"
<!-- //knowledge_pages_images -->


<!-- knowledge_pages_diagrams -->
";

$query = "SELECT * FROM $t_knowledge_pages_diagrams LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_knowledge_pages_diagrams: $row_cnt</p>
	";
}
else{

	mysqli_query($link, "CREATE TABLE $t_knowledge_pages_diagrams(
	 diagram_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(diagram_id), 
	   diagram_space_id INT,
	   diagram_page_id INT,
	   diagram_page_title VARCHAR(250),
	   diagram_type VARCHAR(250),
	   diagram_version VARCHAR(250),
	   diagram_title VARCHAR(250),
	   diagram_file_path VARCHAR(250),
	   diagram_file_xml_name VARCHAR(250),
	   diagram_file_image_name VARCHAR(250),
	   diagram_file_image_thumb_100 VARCHAR(250),
	   diagram_unique_hits INT,
	   diagram_unique_hits_ip_block TEXT,
	   diagram_unique_hits_user_id_block TEXT,
	   diagram_created_datetime DATETIME,
	   diagram_created_date_saying VARCHAR(250),
	   diagram_created_by_user_id VARCHAR(250),
	   diagram_created_by_user_name VARCHAR(250),
	   diagram_created_by_user_alias VARCHAR(250),
	   diagram_created_by_user_email VARCHAR(250),
	   diagram_created_by_user_image_file VARCHAR(250),
	   diagram_created_by_user_ip VARCHAR(250),
	   diagram_created_by_user_hostname VARCHAR(250),
	   diagram_created_by_user_agent VARCHAR(250),
	   diagram_updated_datetime DATETIME,
	   diagram_updated_date_saying VARCHAR(250),
	   diagram_updated_by_user_id VARCHAR(250),
	   diagram_updated_by_user_name VARCHAR(250),
	   diagram_updated_by_user_alias VARCHAR(250),
	   diagram_updated_by_user_email VARCHAR(250),
	   diagram_updated_by_user_image_file VARCHAR(250),
	   diagram_updated_by_user_ip VARCHAR(250),
	   diagram_updated_by_user_hostname VARCHAR(250),
	   diagram_updated_by_user_agent VARCHAR(250))")
	   or die(mysqli_error());
}
echo"
<!-- //knowledge_pages_diagrams -->





<!-- home_page_user_remember -->
";

$query = "SELECT * FROM $t_knowledge_home_page_user_remember LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_knowledge_home_page_user_remember: $row_cnt</p>
	";
}
else{


	mysqli_query($link, "CREATE TABLE $t_knowledge_home_page_user_remember(
	  user_remember_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(user_remember_id), 
	   user_remember_user_id INT,
	   user_remember_space_id INT,
	   user_remember_space_title VARCHAR(200)
	   )")
	   or die(mysqli_error());

}
echo"
<!-- //home_page_user_remember -->

";
?>