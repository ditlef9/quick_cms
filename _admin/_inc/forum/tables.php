<?php
/**
*
* File: _admin/_inc/discuss/tables.php
* Version 11:55 30.12.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Functions ------------------------------------------------------------------------ */
function fix_utf($value){
	$value = str_replace("ÃƒÂ¸", "ø", $value);
	$value = str_replace("ÃƒÂ¥", "å", $value);

        return $value;
}
function fix_local($value){
	$value = htmlentities($value);

        return $value;
}
/*- Tables ---------------------------------------------------------------------------- */

$t_forum_titles		= $mysqlPrefixSav . "forum_titles";
$t_forum_subscriptions 	= $mysqlPrefixSav . "forum_subscriptions";

$t_forum_topics 		= $mysqlPrefixSav . "forum_topics";
$t_forum_topics_subscribers 	= $mysqlPrefixSav . "forum_topics_subscribers";
$t_forum_topics_read_by_user	= $mysqlPrefixSav . "forum_topics_read_by_user";
$t_forum_topics_read_by_ip	= $mysqlPrefixSav . "forum_topics_read_by_ip";
$t_forum_topics_tags 		= $mysqlPrefixSav . "forum_topics_tags";
$t_forum_replies		= $mysqlPrefixSav . "forum_replies";
$t_forum_replies_comments	= $mysqlPrefixSav . "forum_replies_comments";

$t_forum_forms		= $mysqlPrefixSav . "forum_forms";
$t_forum_forms_questions	= $mysqlPrefixSav . "forum_forms_questions";

$t_forum_top_users_yearly	= $mysqlPrefixSav . "forum_top_users_yearly";
$t_forum_top_users_monthly	= $mysqlPrefixSav . "forum_top_users_monthly";
$t_forum_top_users_all_time	= $mysqlPrefixSav . "forum_top_users_all_time";

$t_forum_tags_index			= $mysqlPrefixSav . "forum_tags_index";
$t_forum_tags_index_translation	= $mysqlPrefixSav . "forum_tags_index_translation";
$t_forum_tags_watch			= $mysqlPrefixSav . "forum_tags_watch";
$t_forum_tags_ignore			= $mysqlPrefixSav . "forum_tags_ignore";

echo"
<h1>Tables</h1>


	<!-- forum_topics -->
	";
	$query = "SELECT * FROM $t_forum_topics";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_forum_topics: $row_cnt</p>
		";
	}
	else{
		echo"
		<table>
		 <tr> 
		  <td style=\"padding-right: 6px;\">
			<p>
			<img src=\"_design/gfx/loading_22.gif\" alt=\"Loading\" />
			</p>
		  </td>
		  <td>
			<h1>Loading...</h1>
		  </td>
		 </tr>
		</table>
		<meta http-equiv=\"refresh\" content=\"2;url=index.php?open=$open&amp;page=default\">
		";
		mysqli_query($link, "CREATE TABLE $t_forum_topics(
	  	 topic_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(topic_id), 
	  	   topic_user_id INT,
	  	   topic_user_alias VARCHAR(50),
	  	   topic_user_image VARCHAR(50),
	  	   topic_language VARCHAR(50),
	  	   topic_title VARCHAR(250),
	  	   topic_title_short VARCHAR(250),
	  	   topic_title_length INT,
	  	   topic_text TEXT,
	  	   topic_created DATETIME,
	  	   topic_created_time VARCHAR(250),
	  	   topic_updated DATETIME,
	  	   topic_updated_time VARCHAR(250),
	  	   topic_updated_translated VARCHAR(50),
	  	   topic_last_replied DATETIME,
	  	   topic_last_replied_time VARCHAR(200),
	  	   topic_replies INT,
	  	   topic_views INT,
	  	   topic_views_ip_block TEXT,
	  	   topic_likes INT,
	  	   topic_dislikes INT,
	  	   topic_rating INT,
	  	   topic_likes_ip_block TEXT,
	  	   topic_user_ip VARCHAR(250),
	  	   topic_reported INT,
	  	   topic_reported_by_user_id INT,
	  	   topic_reported_reason VARCHAR(250),
	  	   topic_reported_checked VARCHAR(250),
	  	   topic_solved INT)")
		   or die(mysqli_error());

	}
	echo"
	<!-- //forum_topics -->

	<!-- forum_topics_subscribers -->
	";
	$query = "SELECT * FROM $t_forum_topics_subscribers";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_forum_topics_subscribers: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_forum_topics_subscribers(
	  	 topic_subscriber_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(topic_subscriber_id), 
	  	   topic_id INT,
	  	   topic_subscriber_user_id VARCHAR(250),
	  	   topic_subscriber_user_email VARCHAR(250),
	  	   topic_subscriber_last_sendt_email DATETIME)")
		   or die(mysqli_error());

	}
	echo"
	<!-- //forum_topics_subscribers -->

	<!-- forum_topics_read -->
	";
	$query = "SELECT * FROM $t_forum_topics_read_by_user";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_forum_topics_read_by_user: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_forum_topics_read_by_user(
	  	 topic_read_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(topic_read_id), 
	  	   topic_read_topic_id INT,
	  	   topic_read_user_id INT)")
		   or die(mysqli_error());
	}
	echo"
	<!-- //forum_topics_read -->

	<!-- forum_topics_read -->
	";
	$query = "SELECT * FROM $t_forum_topics_read_by_ip";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_forum_topics_read_by_ip: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_forum_topics_read_by_ip(
	  	 topic_read_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(topic_read_id), 
	  	   topic_read_topic_id INT,
	  	   topic_read_ip VARCHAR(250),
	  	   topic_read_year INT)")
		   or die(mysqli_error());
	}
	echo"
	<!-- //forum_topics_read -->



	<!-- forum_topics_tags -->
	";
	$query = "SELECT * FROM $t_forum_topics_tags";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_forum_topics_tags: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_forum_topics_tags(
	  	 topic_tag_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(topic_tag_id), 
	  	   topic_id INT,
	  	   topic_tag_title VARCHAR(250),
	  	   topic_tag_clean VARCHAR(250))")
		   or die(mysqli_error());

	}
	echo"
	<!-- //forum_topics_tags -->
	

	<!-- forum_replies -->
	";

	
	$query = "SELECT * FROM $t_forum_replies";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_forum_replies: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_forum_replies(
	  	 reply_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(reply_id), 
	  	   reply_user_id INT,
	  	   reply_user_alias VARCHAR(50),
	  	   reply_user_image VARCHAR(50),
	  	   reply_topic_id INT,
	  	   reply_text TEXT,
		   reply_created DATETIME,
		   reply_updated DATETIME, 
	  	   reply_updated_translated VARCHAR(50),
	  	   reply_selected_answer INT,
	  	   reply_likes INT,
	  	   reply_dislikes INT,
	  	   reply_rating INT,
	  	   reply_likes_ip_block TEXT,
		   reply_user_ip VARCHAR(250),
	  	   reply_reported INT,
	  	   reply_reported_by_user_id INT,
	  	   reply_reported_reason VARCHAR(250),
	  	   reply_reported_checked VARCHAR(250))")
		   or die(mysqli_error());

	}
	echo"
	<!-- //forum_replies -->



	<!-- forum_replies_comments -->
	";
	$query = "SELECT * FROM $t_forum_replies_comments";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_forum_replies_comments: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_forum_replies_comments(
	  	 reply_comment_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(reply_comment_id), 
	  	   reply_comment_user_id INT,
	  	   reply_comment_user_alias VARCHAR(50),
	  	   reply_comment_user_image VARCHAR(50),
	  	   reply_comment_topic_id INT,
	  	   reply_comment_reply_id INT,
	  	   reply_comment_text TEXT,
		   reply_comment_created DATETIME,
		   reply_comment_updated DATETIME, 
	  	   reply_comment_updated_translated VARCHAR(50),
	  	   reply_comment_likes INT,
	  	   reply_comment_dislikes INT,
	  	   reply_comment_rating INT,
	  	   reply_comment_likes_ip_block TEXT,
		   reply_comment_user_ip VARCHAR(250),
	  	   reply_comment_reported INT,
	  	   reply_comment_reported_by_user_id INT,
	  	   reply_comment_reported_reason VARCHAR(250),
	  	   reply_comment_reported_checked VARCHAR(250))")
		   or die(mysqli_error());
	}
	echo"
	<!-- //forum_replies_comments -->

	<!-- forum_forms -->
	";

	
	$query = "SELECT * FROM $t_forum_forms";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_forum_forms: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_forum_forms(
	  	 form_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(form_id), 
	  	   form_title VARCHAR(250),
	  	   form_language VARCHAR(50),
	  	   form_introduction TEXT,
	  	   form_insert_title_start VARCHAR(50),
	  	   form_tags VARCHAR(250),
	  	   form_created DATETIME,
		   form_updated DATETIME)")
		   or die(mysqli_error());

		mysqli_query($link, "INSERT INTO $t_forum_forms
		(form_id, form_title, form_language, 
		form_introduction, form_insert_title_start, form_tags, form_created, form_updated) 
		VALUES 
		(NULL, 'F&aring; gratis kostholdsplan', 'no', 
		'&Oslash;nsker du &aring; g&aring; ned i vekt? Vi kan hjelpe deg med en gratis kostholdsplan som er tilpasset deg! Det eneste du beh&oslash;ver er &aring; svare p&aring; noen enkle sp&oslash;rsm&aring;l.', 
		'Kostholdsplan for', 'kosthold', '2018-03-17 14:09:15', '2018-03-17 14:28:49')
		")
		or die(mysqli_error($link));
	
	}
	echo"
	<!-- //forum_forms -->
	<!-- forum_forms_questions -->
	";

	
	$query = "SELECT * FROM $t_forum_forms_questions";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_forum_forms_questions: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_forum_forms_questions(
	  	 form_question_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(form_question_id), 
	  	   form_id INT,
	  	   form_question VARCHAR(250),
	  	   form_question_type VARCHAR(50),
	  	   form_question_options TEXT,
	  	   form_question_help_text VARCHAR(250))")
		   or die(mysqli_error());

		mysqli_query($link, "INSERT INTO $t_forum_forms_questions
		(form_question_id, form_id, form_question, form_question_type, form_question_options, form_question_help_text) 
		VALUES 
		(NULL, 1, 'Kj&oslash;nn', 'select', 'Mann\nKvinne', ''),
		(NULL, 1, 'H&oslash;yde', 'text', '', 'cm'),
		(NULL, 1, 'Nåværende vekt', 'text', '', 'kg'),
		(NULL, 1, 'Vektm&aring;l', 'text', '', 'kg'),
		(NULL, 1, 'Hvilken mat liker du?', 'textarea', '', '')
		")
		or die(mysqli_error($link));
	}
	echo"
	<!-- //forum_forms_questions -->

	<!-- forum_titles -->
	";

	
	$query = "SELECT * FROM $t_forum_titles";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_forum_titles: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_forum_titles(
	  	 title_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(title_id), 
	  	   title_language VARCHAR(250),
	  	   title_value VARCHAR(50))")
		   or die(mysqli_error());

		mysqli_query($link, "INSERT INTO $t_forum_titles
		(title_id, title_language, title_value) 
		VALUES 
		(NULL, 'en', 'Discuss'),
		(NULL, 'no', 'Diskuter')
		")
		or die(mysqli_error($link));
	}
	echo"
	<!-- //forum_titles -->


	<!-- $t_forum_top_users_monthly -->
	";

	
	$query = "SELECT * FROM $t_forum_top_users_monthly";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_forum_top_users_monthly: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_forum_top_users_monthly(
	  	 top_monthly_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(top_monthly_id), 
	  	   top_monthly_user_id INT,
	  	   top_monthly_year INT,
	  	   top_monthly_month INT,
	  	   top_monthly_topics INT,
	  	   top_monthly_replies INT,
	  	   top_monthly_times_voted INT,
	  	   top_monthly_points INT,
	  	   top_monthly_user_alias VARCHAR(250),
	  	   top_monthly_user_image VARCHAR(250))")
		   or die(mysqli_error());

	}
	echo"
	<!-- //$t_forum_top_users_monthly -->

	<!-- $t_forum_top_users_yearly -->
	";

	
	$query = "SELECT * FROM $t_forum_top_users_yearly";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_forum_top_users_yearly: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_forum_top_users_yearly(
	  	 top_yearly_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(top_yearly_id), 
	  	   top_yearly_user_id INT,
	  	   top_yearly_year INT,
	  	   top_yearly_topics INT,
	  	   top_yearly_replies INT,
	  	   top_yearly_times_voted INT,
	  	   top_yearly_points INT,
	  	   top_yearly_user_alias VARCHAR(250),
	  	   top_yearly_user_image VARCHAR(250))")
		   or die(mysqli_error());

	}
	echo"
	<!-- //$t_forum_top_users_yearly -->

	<!-- $t_forum_top_users_all_time -->
	";

	
	$query = "SELECT * FROM $t_forum_top_users_all_time";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_forum_top_users_all_time: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_forum_top_users_all_time(
	  	 top_all_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(top_all_id), 
	  	   top_all_user_id INT,
	  	   top_all_topics INT,
	  	   top_all_replies INT,
	  	   top_all_times_voted INT,
	  	   top_all_points INT,
	  	   top_all_user_alias VARCHAR(250),
	  	   top_all_user_image VARCHAR(250))")
		   or die(mysqli_error());
	}
	echo"
	<!-- //$t_forum_top_users_all_time -->


	<!-- $t_forum_subscriptions -->
	";
	$query = "SELECT * FROM $t_forum_subscriptions";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_forum_subscriptions: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_forum_subscriptions(
	  	 forum_subscription_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(forum_subscription_id), 
	  	   forum_subscription_user_id VARCHAR(250),
	  	   forum_subscription_user_email VARCHAR(250),
	  	   forum_subscription_last_sendt_datetime DATETIME,
	  	   forum_subscription_last_sendt_time VARCHAR(200))")
		   or die(mysqli_error());

		// Find all admins, put them into subscription list
		$datetime = date("Y-m-d H:i:s");
		$time = time();
		$query = "SELECT user_id, user_email, user_name, user_rank FROM $t_users WHERE user_rank='admin'";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_user_id, $get_user_email, $get_user_name, $get_user_rank) = $row;

			$inp_email_mysql = quote_smart($link, $get_user_email);

			mysqli_query($link, "INSERT INTO $t_forum_subscriptions
			(forum_subscription_id, forum_subscription_user_id, forum_subscription_user_email, forum_subscription_last_sendt_datetime, forum_subscription_last_sendt_time) 
			VALUES 
			(NULL, '$get_user_id', $inp_email_mysql, '$datetime', '$time')")
			or die(mysqli_error($link));
		}
	}
	echo"
	<!-- //$t_forum_subscriptions -->
	
	<!-- tags_index -->
	";
	$query = "SELECT * FROM $t_forum_tags_index";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_forum_tags_index: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_forum_tags_index(
	  	 tag_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(tag_id), 
	  	   tag_title VARCHAR(250),
	  	   tag_title_clean VARCHAR(250),
	  	   tag_introduction TEXT,
	  	   tag_description TEXT,
	  	   tag_created DATETIME,
	  	   tag_updated DATETIME,
	  	   tag_topics_total_counter INT,
	  	   tag_topics_today_counter INT,
	  	   tag_topics_today_day INT,
	  	   tag_topics_this_week_counter INT,
	  	   tag_topics_this_week_week INT,
	  	   tag_is_official INT,
	  	   tag_icon_path VARCHAR(250),
	  	   tag_icon_file_16 VARCHAR(250),
	  	   tag_icon_file_32 VARCHAR(250),
	  	   tag_icon_file_256 VARCHAR(250))")
		   or die(mysqli_error());

	}
	echo"
	<!-- //tags_index -->

	<!-- tags_index_translation -->
	";
	$query = "SELECT * FROM $t_forum_tags_index_translation";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_forum_tags_index_translation: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_forum_tags_index_translation(
	  	 tag_translation_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(tag_translation_id), 
	  	   tag_id INT,
	  	   tag_translation_language VARCHAR(250),
	  	   tag_translation_introduction TEXT,
	  	   tag_translation_description TEXT)")
		   or die(mysqli_error());

	}
	echo"
	<!-- //tags_index_translation -->

	<!-- forum_tags_watch -->
	";
	$query = "SELECT * FROM $t_forum_tags_watch";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_forum_tags_watch: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_forum_tags_watch(
	  	 watch_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(watch_id), 
	  	   watch_tag_id INT,
	  	   watch_user_id INT,
	  	   watch_user_name VARCHAR(250),
	  	   watch_user_email VARCHAR(250),
	  	   watch_user_email_notification INT,
	  	   watch_user_last_sent_email_datetime DATETIME,
	  	   watch_user_last_sent_email_time VARCHAR(250))")
		   or die(mysqli_error());

	}
	echo"
	<!-- //forum_tags_watch -->

	<!-- forum_tags_ignore -->
	";
	$query = "SELECT * FROM $t_forum_tags_ignore";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_forum_tags_ignore: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_forum_tags_ignore(
	  	 ignore_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(ignore_id), 
	  	   ignore_tag_id INT,
	  	   ignore_user_id INT)")
		   or die(mysqli_error());

	}
	echo"
	<!-- //forum_tags_ignore -->


	";
?>