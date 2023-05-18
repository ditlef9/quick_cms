<?php 
/**
*
* File: food/new_workout_plan_weekly_insert_into_feed.php
* Version 1.0.0
* Date 12:05 10.02.2018
* Copyright (c) 2011-2018 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
if($get_current_workout_weekly_id != ""){
	// Fetch newest data (as example image)

	$query = "SELECT workout_weekly_id, workout_weekly_user_id, workout_weekly_period_id, workout_weekly_weight, workout_weekly_language, workout_weekly_title, workout_weekly_title_clean, workout_weekly_introduction, workout_weekly_goal, workout_weekly_image_path, workout_weekly_image_file, workout_weekly_created, workout_weekly_updated, workout_weekly_unique_hits, workout_weekly_unique_hits_ip_block, workout_weekly_comments, workout_weekly_likes, workout_weekly_dislikes, workout_weekly_rating, workout_weekly_ip_block, workout_weekly_user_ip, workout_weekly_notes FROM $t_workout_plans_weekly WHERE workout_weekly_id=$get_current_workout_weekly_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_workout_weekly_id, $get_current_workout_weekly_user_id, $get_current_workout_weekly_period_id, $get_current_workout_weekly_weight, $get_current_workout_weekly_language, $get_current_workout_weekly_title, $get_current_workout_weekly_title_clean, $get_current_workout_weekly_introduction, $get_current_workout_weekly_goal, $get_current_workout_weekly_image_path, $get_current_workout_weekly_image_file, $get_current_workout_weekly_created, $get_current_workout_weekly_updated, $get_current_workout_weekly_unique_hits, $get_current_workout_weekly_unique_hits_ip_block, $get_current_workout_weekly_comments, $get_current_workout_weekly_likes, $get_current_workout_weekly_dislikes, $get_current_workout_weekly_rating, $get_current_workout_weekly_ip_block, $get_current_workout_weekly_user_ip, $get_current_workout_weekly_notes) = $row;
	

	



	// Feed
	$inp_feed_title_mysql = quote_smart($link, $get_current_workout_weekly_title);


	$inp_feed_text_mysql = quote_smart($link, $get_current_workout_weekly_introduction);

	$inp_feed_image_path_mysql = quote_smart($link, $get_current_workout_weekly_image_path);
	$inp_feed_image_file_mysql = quote_smart($link, $get_current_workout_weekly_image_file);

	// Feed Thumb 300x169
	$ext = get_extension($get_current_workout_weekly_image_file);
	$img_name = str_replace(".$ext", "", $get_current_workout_weekly_image_file);
	$inp_feed_image_thumb_a = $img_name . "_thumb_300x169." . $ext;
	$inp_feed_image_thumb_a_mysql = quote_smart($link, $inp_feed_image_thumb_a);

	// Feed Thumb 540x304
	$inp_feed_image_thumb_b = $img_name . "_thumb_540x304." . $ext;
	$inp_feed_image_thumb_b_mysql = quote_smart($link, $inp_feed_image_thumb_b);

	$inp_feed_link_url = "workout_plans/weekly_workout_plan_view.php?weekly_id=$get_current_workout_weekly_id&amp;l=$l";
	$inp_feed_link_url_mysql = quote_smart($link, $inp_feed_link_url);
	
	// Link
	include("$root/_admin/_translations/site/$get_current_workout_weekly_language/workout_plans/ts_new_workout_plan_weekly_include_insert_into_feed.php");
	$inp_feed_link_name_mysql = quote_smart($link, "$l_view");
 

	// Feed user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);

	// Feed Get current user
	$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;

	// Feed Author image
	$query = "SELECT photo_id, photo_destination, photo_thumb_40, photo_thumb_50, photo_thumb_60, photo_thumb_200 FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_40, $get_my_photo_thumb_50, $get_my_photo_thumb_60, $get_my_photo_thumb_200) = $row;


	$inp_feed_user_email_mysql = quote_smart($link, $get_my_user_email);
	$inp_feed_user_name_mysql = quote_smart($link, $get_my_user_name);
	$inp_feed_user_alias_mysql = quote_smart($link, $get_my_user_alias);
	$inp_feed_user_photo_file_mysql = quote_smart($link, $get_my_photo_destination);
	$inp_feed_user_photo_thumb_40_mysql = quote_smart($link, $get_my_photo_thumb_40);
	$inp_feed_user_photo_thumb_50_mysql = quote_smart($link, $get_my_photo_thumb_50);
	$inp_feed_user_photo_thumb_60_mysql = quote_smart($link, $get_my_photo_thumb_60);
	$inp_feed_user_photo_thumb_200_mysql = quote_smart($link, $get_my_photo_thumb_200);

	// Feed My IP
	$inp_my_ip = $_SERVER['REMOTE_ADDR'];
	$inp_my_ip = output_html($inp_my_ip);
	$inp_my_ip_mysql = quote_smart($link, $inp_my_ip);

	// Feed My hostname
	$inp_my_hostname = "$my_ip";
	if($configSiteUseGethostbyaddrSav == "1"){
		$inp_my_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); // Some servers in local network cant use getostbyaddr because of nameserver missing
	}
	$inp_my_hostname = output_html($inp_my_hostname);
	$inp_my_hostname_mysql = quote_smart($link, $inp_my_hostname);
				
	// Feed Lang
	$inp_feed_language = output_html($l);
	$inp_feed_language_mysql = quote_smart($link, $inp_feed_language);

	// Feed Subscribe
	$query = "SELECT es_id, es_user_id, es_type, es_on_off FROM $t_users_email_subscriptions WHERE es_user_id='$get_my_user_id' AND es_type='users_feed'";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_es_id, $get_es_user_id, $get_es_type, $get_es_on_off) = $row;
	if($get_es_id == ""){
		// Dont know
		mysqli_query($link, "INSERT INTO $t_users_email_subscriptions 
		(es_id, es_user_id, es_type, es_on_off) 
		VALUES 
		(NULL, $get_my_user_id, 'users_feed', 0)") or die(mysqli_error($link));
		$get_es_on_off = 0;
	}

	// Feed dates
	$year = date("Y");
	$date_saying = date("j M Y");

	$query = "SELECT feed_id FROM $t_users_feeds_index WHERE feed_module_name='workout_plans' AND feed_module_part_name='weekly' AND feed_module_part_id=$get_current_workout_weekly_id AND feed_user_id=$get_my_user_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_feed_id) = $row;
	if($get_current_feed_id == ""){
		// Insert feed
		mysqli_query($link, "INSERT INTO $t_users_feeds_index
		(feed_id, feed_title, feed_text, feed_image_path, feed_image_file, 
		feed_image_thumb_300x169, feed_image_thumb_540x304, feed_link_url, feed_link_name, feed_module_name, 
		feed_module_part_name, feed_module_part_id, 
		feed_user_id, feed_user_email, feed_user_name, feed_user_alias, 
		feed_user_photo_file, feed_user_photo_thumb_40, feed_user_photo_thumb_50, feed_user_photo_thumb_60, feed_user_photo_thumb_200, 
		feed_user_subscribe, feed_user_ip, feed_user_hostname, feed_language, feed_created_datetime, 
		feed_created_year, feed_created_time, feed_created_date_saying, feed_likes, feed_dislikes, feed_comments) 
		VALUES 
		(NULL, $inp_feed_title_mysql, $inp_feed_text_mysql, $inp_feed_image_path_mysql, $inp_feed_image_file_mysql, 
		$inp_feed_image_thumb_a_mysql, $inp_feed_image_thumb_b_mysql, $inp_feed_link_url_mysql, $inp_feed_link_name_mysql, 'workout_plans', 
		'weekly', $get_current_workout_weekly_id, 
		$get_my_user_id, $inp_feed_user_email_mysql, $inp_feed_user_name_mysql, $inp_feed_user_alias_mysql, 
		$inp_feed_user_photo_file_mysql, $inp_feed_user_photo_thumb_40_mysql, $inp_feed_user_photo_thumb_50_mysql, $inp_feed_user_photo_thumb_60_mysql, $inp_feed_user_photo_thumb_200_mysql, 
		$get_es_on_off, $inp_my_ip_mysql, $inp_my_hostname_mysql, $inp_feed_language_mysql, '$datetime',
		'$year', '$time', '$date_saying', 0, 0, 0)")
		or die(mysqli_error($link));
	} // Create feed
	else{
		// Update feed
		mysqli_query($link, "UPDATE $t_users_feeds_index SET
					feed_title=$inp_feed_title_mysql, 
					feed_text=$inp_feed_text_mysql, 
					feed_image_path=$inp_feed_image_path_mysql, 
					feed_image_file=$inp_feed_image_file_mysql, 
					feed_image_thumb_300x169=$inp_feed_image_thumb_a_mysql, 
					feed_image_thumb_540x304=$inp_feed_image_thumb_b_mysql, 
					feed_modified_datetime='$datetime'
					WHERE feed_id=$get_current_feed_id")
					or die(mysqli_error($link));
	} // Update feed

}

?>