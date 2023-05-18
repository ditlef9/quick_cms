<?php 
/**
*
* File: food/view_food_include_new_rating.php
* Version 1.0.0
* Date 10:28 15.11.2020
* Copyright (c) 2020 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
if(!(isset($get_current_food_id))){
	echo"error, food id not found";
	die;
}




// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	// Link
	if($process != "1"){
		echo"
		<a id=\"new_rating_form\"></a>
		";
	}
	

	// Get my user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;

	$query = "SELECT photo_id, photo_destination, photo_thumb_60 FROM $t_users_profile_photo WHERE photo_user_id=$my_user_id_mysql AND photo_profile_image='1'";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_60) = $row;


	
	// Dates
	$time = time();
	$datetime = date("Y-m-d H:i:s");
	$date_saying = date("j M Y");
	$year = date("Y");
	$month = date("m");
	$month_full = date("F");
	$month_short = date("M");
	$week = date("W");

	// IP Check
	$my_ip = $_SERVER['REMOTE_ADDR'];
	$my_ip = output_html($my_ip);
	$my_ip_mysql = quote_smart($link, $my_ip);
	$q = "SELECT rating_id, rating_created_timestamp FROM $t_food_index_ratings WHERE rating_by_user_ip=$my_ip_mysql ORDER BY rating_id DESC LIMIT 0,1";
	$r = mysqli_query($link, $q);
	$rowb = mysqli_fetch_row($r);
	list($get_rating_id, $get_rating_created_timestamp) = $rowb;	
	
	$can_rate = 1;

	if($get_rating_id != ""){
		$time_since_written = $time-$get_rating_created_timestamp;
		$remaining = 60-$time_since_written;
		if($time_since_written < 60){ // 60 seconds
			$can_rate = 0;
			
		}
	}

	if($can_rate == 1){

		// Post rating
		if(isset($_GET['rating_action'])){

			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);			

			$inp_stars = $_POST['inp_stars'];
			$inp_stars = output_html($inp_stars);
			$inp_stars_mysql = quote_smart($link, $inp_stars);


			$inp_text = $_POST['inp_text'];
			$inp_text = output_html($inp_text);
			$inp_text_mysql = quote_smart($link, $inp_text);

			$inp_my_user_name = output_html($get_my_user_name);
			$inp_my_user_name_mysql = quote_smart($link, $inp_my_user_name);

			$inp_my_image_path = output_html("_uploads/users/images/$get_my_user_id");
			$inp_my_image_path_mysql = quote_smart($link, $inp_my_image_path);

			$inp_my_image_file = output_html($get_my_photo_destination);
			$inp_my_image_file_mysql = quote_smart($link, $inp_my_image_file);

			$inp_my_image_thumb = output_html($get_my_photo_thumb_60);
			$inp_my_image_thumb_mysql = quote_smart($link, $inp_my_image_thumb);


			if($inp_text == ""){
				$url = "view_food.php?main_category_id=$get_current_main_category_id&sub_category_id=$get_current_sub_category_id&food_id=$get_current_food_id&l=$l&ft_rating=warning&fm_rating=missing_text#new_rating_form";
				header("Location: $url");
				exit;
			} // no text 
			else{

				
				// Insert comment
				mysqli_query($link, "INSERT INTO $t_food_index_ratings
				(rating_id, rating_food_id, rating_language, rating_title, rating_text, rating_by_user_id, 
				rating_by_user_name, rating_by_user_image_path, rating_by_user_image_file, rating_by_user_image_thumb_60, rating_by_user_ip, 
				rating_stars, rating_created, rating_created_saying, rating_created_timestamp, rating_updated, 
				rating_updated_saying, rating_read_blog_owner, rating_reported) 
				VALUES 
				(NULL, $get_current_food_id, $l_mysql, $inp_title_mysql, $inp_text_mysql, $get_my_user_id, 
				$inp_my_user_name_mysql, $inp_my_image_path_mysql, $inp_my_image_file_mysql, $inp_my_image_thumb_mysql, $my_ip_mysql, 
				$inp_stars_mysql, '$datetime', '$date_saying', '$time', '$datetime', 
				'$date_saying', 0, 0)")
				or die(mysqli_error($link));

				// Get ID
				$query = "SELECT rating_id FROM $t_food_index_ratings WHERE rating_created='$datetime'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_rating_id) = $row;

				// Count comments
				$query = "SELECT count(rating_id) FROM $t_food_index_ratings WHERE rating_food_id=$get_current_food_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_count_comments) = $row;

				// Count and calculate all stars
				$query = "SELECT count(rating_id) FROM $t_food_index_ratings WHERE rating_food_id=$get_current_food_id AND rating_stars=1";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_count_star_1) = $row;

				$query = "SELECT count(rating_id) FROM $t_food_index_ratings WHERE rating_food_id=$get_current_food_id AND rating_stars=2";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_count_star_2) = $row;

				$query = "SELECT count(rating_id) FROM $t_food_index_ratings WHERE rating_food_id=$get_current_food_id AND rating_stars=3";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_count_star_3) = $row;
				
				$query = "SELECT count(rating_id) FROM $t_food_index_ratings WHERE rating_food_id=$get_current_food_id AND rating_stars=4";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_count_star_4) = $row;

				$query = "SELECT count(rating_id) FROM $t_food_index_ratings WHERE rating_food_id=$get_current_food_id AND rating_stars=5";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_count_star_5) = $row;

				$inp_stars_sum = $get_count_star_1+$get_count_star_2+$get_count_star_3+$get_count_star_4+$get_count_star_5;
				$inp_stars = round((($get_count_star_1*1) + ($get_count_star_2*2) + ($get_count_star_3*3) + ($get_count_star_4*4) + ($get_count_star_5*5))/$inp_stars_sum);
				$inp_food_comments_multiplied_stars = $get_count_comments*$inp_stars;
				mysqli_query($link, "UPDATE $t_food_index SET 
							food_no_of_comments=$get_count_comments,
							food_stars=$inp_stars,
							food_stars_sum=$inp_stars_sum,
							food_comments_multiplied_stars=$inp_food_comments_multiplied_stars
							WHERE food_id=$get_current_food_id") or die(mysqli_error($link));

				// Email to download owner
				if($configMailSendActiveSav == "1"){
					// Find admin
					$query = "SELECT user_id, user_email, user_name FROM $t_users WHERE user_rank='admin' LIMIT 0,1";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_admin_user_id, $get_admin_user_email, $get_admin_user_name) = $row;

					// Admin subscriptions
					$query = "SELECT es_id, es_user_id, es_type, es_on_off FROM $t_users_email_subscriptions WHERE es_user_id=$get_admin_user_id";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_admin_es_id, $get_admin_es_user_id, $get_admin_es_type, $get_admin_es_on_off) = $row;
					if($get_admin_es_on_off == "1"){


						// Send mail
						$host = $_SERVER['HTTP_HOST'];

						$subject = "$l_new_rating_for_food $get_current_food_manufacturer_name_and_food_name";
			
						$message = "<html>\n";
						$message = $message. "<head>\n";
						$message = $message. "  <title>$subject</title>\n";
						$message = $message. " </head>\n";
						$message = $message. "<body>\n";

						$message = $message . "<h1>$l_new_rating_for_food $get_current_food_manufacturer_name_and_food_name</h1>\n\n";
						$message = $message . "<table>\n";
						$message = $message . " <tr>\n";
						$message = $message . "  <td style=\"padding: 0px 6px 0px 0x;text-align:center;vertical-align:top;\">\n";
						$message = $message . "		<p>\n";
						if(file_exists("$root/_uploads/users/images/$get_my_user_id/$get_my_photo_destination") && $get_my_photo_destination != ""){
							$message = $message . "		<a href=\"$configSiteURLSav/users/view_profile.php?user_id=$get_my_user_id\"><img src=\"$configSiteURLSav/_uploads/users/images/$get_my_user_id/$get_my_photo_thumb_60\" alt=\"$get_my_photo_thumb_60\" /></a><br />\n";

						}
						$message = $message . "		<a href=\"$configSiteURLSav/users/view_profile.php?user_id=$get_my_user_id\">$get_my_user_name</a>\n";
						$message = $message . "		</p>\n\n";
						$message = $message . "  </td>\n";
						$message = $message . "  <td style=\"padding: 0px 6px 0px 0x;vertical-align:top;\">\n";
						$message = $message . "		<p>$inp_text</p>\n\n";
						$message = $message . "  </td>\n";
						$message = $message . " </tr>\n";
						$message = $message . "</table>\n\n";
						$message = $message . "<p><b>$l_actions:</b><br />\n";
						$message = $message . "1. <a href=\"$configSiteURLSav/food/view_food.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;food_id=$get_current_food_id\">$l_view_food</a><br />\n";
						$message = $message . "2. <a href=\"$configSiteURLSav/food/view_food.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;food_id=$get_current_food_id#rating$get_current_rating_id\">$l_view_rating</a><br />\n";
						$message = $message . "5. <a href=\"$configSiteURLSav/food/rating_report.php?rating_id=$get_current_rating_id\">$l_report</a><br />\n";
						$message = $message . "</p>\n\n";

			

						$message = $message . "<p>\n\n--<br />\nBest regards<br />\n$configWebsiteTitleSav<br />\n<a href=\"$configSiteURLSav\">$configSiteURLSav</a></p>";
						$message = $message . "<p><a href=\"$configSiteURLSav/users/edit_subscriptions.php\">$l_unsubscribe</a></p>";
						$message = $message. "</body>\n";
						$message = $message. "</html>\n";



						// Preferences for Subject field
						$headers = 'MIME-Version: 1.0\n';
						$headers = $headers . 'Content-type: text/html; charset=utf-8\n';
						$headers = $headers . "From: $configFromNameSav <" . $configFromEmailSav . ">";
						mail($get_admin_user_email, $subject, $message, $headers);
					} // admin wants mail
				} // $get_current_blog_new_comments_email_warning == 1



				// Stats :: Comments
				$year = date("Y");
				$month = date("m");
				$month_full = date("F");
				$month_short = date("M");
				$week = date("W");

				// Stats :: Comments :: Year
				$query = "SELECT stats_comments_id, stats_comments_comments_written FROM $t_stats_comments_per_year WHERE stats_comments_year='$year' AND stats_comments_language=$l_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_stats_comments_id, $get_stats_comments_comments_written) = $row;
				if($get_stats_comments_id == ""){
					mysqli_query($link, "INSERT INTO $t_stats_comments_per_year 
					(stats_comments_id, stats_comments_year, stats_comments_language, stats_comments_comments_written) 
					VALUES 
					(NULL, $year, $l_mysql, 1)")
					or die(mysqli_error($link));
				}
				else{
					$inp_counter = $get_stats_comments_comments_written+1;
					mysqli_query($link, "UPDATE $t_stats_comments_per_year 
								SET stats_comments_comments_written=$inp_counter
								WHERE stats_comments_id=$get_stats_comments_id")
								or die(mysqli_error($link));
				}

				// Stats :: Comments :: Month
				$query = "SELECT stats_comments_id, stats_comments_comments_written FROM $t_stats_comments_per_month WHERE stats_comments_month='$month' AND stats_comments_year='$year' AND stats_comments_language=$l_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_stats_comments_id, $get_stats_comments_comments_written) = $row;
				if($get_stats_comments_id == ""){
					mysqli_query($link, "INSERT INTO $t_stats_comments_per_month 
					(stats_comments_id, stats_comments_month, stats_comments_month_full, stats_comments_month_short, stats_comments_year, stats_comments_language, stats_comments_comments_written) 
					VALUES 
					(NULL, $month, '$month_full', '$month_short', $year, $l_mysql, 1)")
					or die(mysqli_error($link));
				}
				else{
					$inp_counter = $get_stats_comments_comments_written+1;
					mysqli_query($link, "UPDATE $t_stats_comments_per_month 
								SET stats_comments_comments_written=$inp_counter
								WHERE stats_comments_id=$get_stats_comments_id")
								or die(mysqli_error($link));
				}

				// Stats :: Comments :: Week
				$query = "SELECT stats_comments_id, stats_comments_comments_written FROM $t_stats_comments_per_week WHERE stats_comments_week='$week' AND stats_comments_year='$year' AND stats_comments_language=$l_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_stats_comments_id, $get_stats_comments_comments_written) = $row;
				if($get_stats_comments_id == ""){
					mysqli_query($link, "INSERT INTO $t_stats_comments_per_week 
					(stats_comments_id, stats_comments_week, stats_comments_month, stats_comments_year, stats_comments_language, stats_comments_comments_written) 
					VALUES 
					(NULL, $week, $month, $year, $l_mysql, 1)")
					or die(mysqli_error($link));
				}
				else{
					$inp_counter = $get_stats_comments_comments_written+1;
					mysqli_query($link, "UPDATE $t_stats_comments_per_week
								SET stats_comments_comments_written=$inp_counter
								WHERE stats_comments_id=$get_stats_comments_id")
								or die(mysqli_error($link));
				}


				// Add to feed

				// Feed Category 
				$query = "SELECT category_id, category_user_id, category_name, category_parent_id FROM $t_food_categories WHERE category_id=$get_current_food_sub_category_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_sub_category_id, $get_current_sub_category_user_id, $get_current_sub_category_name, $get_current_sub_category_parent_id) = $row;

				$query_t = "SELECT category_translation_id, category_id, category_translation_language, category_translation_value FROM $t_food_categories_translations WHERE category_id=$get_current_sub_category_id AND category_translation_language=$l_mysql";
				$result_t = mysqli_query($link, $query_t);
				$row_t = mysqli_fetch_row($result_t);
				list($get_current_sub_category_translation_id, $get_current_sub_category_id, $get_current_sub_category_translation_language, $get_current_sub_category_translation_value) = $row_t;
		
	
				$inp_feed_category_name_mysql = quote_smart($link, $get_current_sub_category_translation_value);


				// Feed title
				$inp_feed_title = "$inp_title";
				$inp_feed_title_mysql = quote_smart($link, $inp_feed_title);

				// Feed text
				$inp_feed_text = substr($inp_text, 0, 200);
				$inp_feed_text_mysql = quote_smart($link, $inp_feed_text);

				// Feed image path
				$inp_feed_image_path_mysql = quote_smart($link, $get_current_food_image_path);

				// Feed image file
				$inp_feed_image_file_mysql = quote_smart($link, $get_current_food_image_a);

				// Feed image thumb 300x169
				$extension = get_extension($get_current_food_image_a);
				$inp_feed_image_thumb = str_replace(".$extension", "", $get_current_food_image_a);
				$inp_feed_image_thumb_a = $inp_feed_image_thumb . "_300x169." . $extension;
				$inp_feed_image_thumb_a_mysql = quote_smart($link, $inp_feed_image_thumb_a);

				// Feed image thumb 540x304
				$inp_feed_image_thumb = str_replace(".$extension", "", $get_current_food_image_a);
				$inp_feed_image_thumb_b = $inp_feed_image_thumb . "_540x304." . $extension;
				$inp_feed_image_thumb_b_mysql = quote_smart($link, $inp_feed_image_thumb_b);

				// Feed link URL
				$inp_feed_link_url = "food/view_food.php?main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$get_current_food_id&amp;l=$l#rating$get_current_rating_id";
				$inp_feed_link_url_mysql = quote_smart($link, $inp_feed_link_url);

				// Feed link name
				$inp_feed_link_name = "$l_read_more";
				$inp_feed_link_name_mysql = quote_smart($link, $inp_feed_link_name);


				// Get current user
				// $query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$get_current_food_user_id";
				// $result = mysqli_query($link, $query);
				// $row = mysqli_fetch_row($result);
				// list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;

				// Author image
				$query = "SELECT photo_id, photo_destination, photo_thumb_40, photo_thumb_50, photo_thumb_60, photo_thumb_200 FROM $t_users_profile_photo WHERE photo_user_id='$get_current_food_user_id' AND photo_profile_image='1'";
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


				// My IP
				$inp_my_ip = $_SERVER['REMOTE_ADDR'];
				$inp_my_ip = output_html($inp_my_ip);
				$inp_my_ip_mysql = quote_smart($link, $inp_my_ip);

				// My hostname
				$inp_my_hostname = "$inp_my_ip";
				if($configSiteUseGethostbyaddrSav == "1"){
					$inp_my_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); // Some servers in local network cant use getostbyaddr because of nameserver missing
				}
				$inp_my_hostname = output_html($inp_my_hostname);
				$inp_my_hostname_mysql = quote_smart($link, $inp_my_hostname);
					
				// Lang
				$inp_feed_language = output_html($l);
				$inp_feed_language_mysql = quote_smart($link, $inp_feed_language);
					
				// Subscribe
				$query = "SELECT es_id, es_user_id, es_type, es_on_off FROM $t_users_email_subscriptions WHERE es_user_id='$get_current_food_user_id' AND es_type='users_feed'";
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
					
				$year = date("Y");
				$date_saying = date("j M Y");

				// Check if exists
				if($get_current_food_age_restriction == "0"){
					$query = "SELECT feed_id FROM $t_users_feeds_index WHERE feed_module_name='food' AND feed_module_part_name='comments' AND feed_module_part_id=$get_current_rating_id AND feed_user_id=$get_my_user_id";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_feed_id) = $row;
					if($get_current_feed_id == ""){
						// Insert feed
						mysqli_query($link, "INSERT INTO $t_users_feeds_index
						(feed_id, feed_title, feed_text, feed_image_path, feed_image_file, 
						feed_image_thumb_300x169, feed_image_thumb_540x304, feed_link_url, feed_link_name, feed_module_name, 
						feed_module_part_name, feed_module_part_id, feed_main_category_id, feed_main_category_name, 
						feed_user_id, feed_user_email, feed_user_name, feed_user_alias, 
						feed_user_photo_file, feed_user_photo_thumb_40, feed_user_photo_thumb_50, feed_user_photo_thumb_60, feed_user_photo_thumb_200, 
						feed_user_subscribe, feed_user_ip, feed_user_hostname, feed_language, feed_created_datetime, 
						feed_created_year, feed_created_time, feed_created_date_saying, feed_likes, feed_dislikes, feed_comments) 
						VALUES 
						(NULL, $inp_feed_title_mysql, $inp_feed_text_mysql, $inp_feed_image_path_mysql, $inp_feed_image_file_mysql, 
						$inp_feed_image_thumb_a_mysql, $inp_feed_image_thumb_b_mysql, $inp_feed_link_url_mysql, $inp_feed_link_name_mysql, 'food', 
						'comment', $get_current_rating_id, $get_current_food_sub_category_id, $inp_feed_category_name_mysql, 
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
				} // age restriction	


				// Refresh site
				$url = "view_food.php?main_category_id=$get_current_main_category_id&sub_category_id=$get_current_sub_category_id&food_id=$get_current_food_id&&l=$l&ft_rating=success&fm_rating=rating_saved#rating$get_current_rating_id";
				header("Location: $url");
				exit;
			} // text 
			

		} // action == post_rating
		echo"
		<!-- New rating form -->
			<hr />
			<h2>$l_rate_this_food</h2>
			<form method=\"post\" action=\"view_food.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;food_id=$get_current_food_id&amp;rating_action=post_rating&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
		
			<table>
	 		 <tr>
			  <td style=\"vertical-align: top;padding-right: 10px;text-align:center;\">
				<p>
				";
				if(file_exists("$root/_uploads/users/images/$get_my_user_id/$get_my_photo_destination") && $get_my_photo_destination != ""){

					// Thumb
					if(!(file_exists("$root/_uploads/users/images/$get_my_user_id/$get_my_photo_thumb_60"))){
						resize_crop_image(60, 60, "$root/_uploads/users/images/$get_my_user_id/$get_my_photo_destination", "$root/_uploads/users/images/$get_my_user_id/$get_my_photo_thumb_60");
					}


					echo"
					<img src=\"$root/_uploads/users/images/$get_my_user_id/$get_my_photo_thumb_60\" alt=\"$get_my_photo_thumb_60\" />
					<br />
					";
				}
				echo"
				$get_my_user_name
				</p>
			  </td>
			  <td style=\"vertical-align: top;\">
				
				<p><b>Title:</b><br />
				<input type=\"text\" name=\"inp_title\" size=\"25\" style=\"width: 80%\" />
				</p>
				
				<!-- Rating -->
					<script>
					\$(document).ready(function(){
						\$(\".inp_rating_image_1\").click(function(){
							\$(\".inp_rating_radio_1\").prop(\"checked\", true);
							 \$(\".inp_rating_image_1\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_2\").attr('src','_gfx/icons/star_off.png');
							 \$(\".inp_rating_image_3\").attr('src','_gfx/icons/star_off.png');
							 \$(\".inp_rating_image_4\").attr('src','_gfx/icons/star_off.png');
							 \$(\".inp_rating_image_5\").attr('src','_gfx/icons/star_off.png');
						});
						\$(\".inp_rating_image_2\").click(function(){
							\$(\".inp_rating_radio_2\").prop(\"checked\", true);
							 \$(\".inp_rating_image_1\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_2\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_3\").attr('src','_gfx/icons/star_off.png');
							 \$(\".inp_rating_image_4\").attr('src','_gfx/icons/star_off.png');
							 \$(\".inp_rating_image_5\").attr('src','_gfx/icons/star_off.png');
						});
						\$(\".inp_rating_image_3\").click(function(){
							\$(\".inp_rating_radio_3\").prop(\"checked\", true);
							 \$(\".inp_rating_image_1\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_2\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_3\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_4\").attr('src','_gfx/icons/star_off.png');
							 \$(\".inp_rating_image_5\").attr('src','_gfx/icons/star_off.png');
						});
						\$(\".inp_rating_image_4\").click(function(){
							\$(\".inp_rating_radio_4\").prop(\"checked\", true);
							 \$(\".inp_rating_image_1\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_2\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_3\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_4\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_5\").attr('src','_gfx/icons/star_off.png');
						});
						\$(\".inp_rating_image_5\").click(function(){
							\$(\".inp_rating_radio_5\").prop(\"checked\", true);
							 \$(\".inp_rating_image_1\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_2\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_3\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_4\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_5\").attr('src','_gfx/icons/star_on.png');
						});
					});
					</script>
				<!-- //Rating -->

				<p><b>$l_set_rating:</b><br />
					";
					if(isset($_GET['inp_stars'])) { 
						$inp_stars = $_GET['inp_stars']; 
						$inp_stars = strip_tags(stripslashes($inp_stars));
					}
					else{
						$inp_stars = "5";
					}
					echo"
					<input type=\"radio\" name=\"inp_stars\" value=\"1\""; if($inp_stars == "1"){ echo" checked=\"checked\""; } echo" class=\"inp_rating_radio_1\" />
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" class=\"inp_rating_image_1\" />

					<input type=\"radio\" name=\"inp_stars\" value=\"2\""; if($inp_stars == "2"){ echo" checked=\"checked\""; } echo" class=\"inp_rating_radio_2\" />
					<img src=\"_gfx/icons/star_"; if($inp_stars > 1){ echo"on"; } else{ echo"off"; } echo".png\" alt=\"star_off.png\" class=\"inp_rating_image_2\" />

					<input type=\"radio\" name=\"inp_stars\" value=\"3\""; if($inp_stars == "3"){ echo" checked=\"checked\""; } echo" class=\"inp_rating_radio_3\" />
					<img src=\"_gfx/icons/star_"; if($inp_stars > 2){ echo"on"; } else{ echo"off"; } echo".png\" alt=\"star_off.png\" class=\"inp_rating_image_3\" />

					<input type=\"radio\" name=\"inp_stars\" value=\"4\""; if($inp_stars == "4"){ echo" checked=\"checked\""; } echo" class=\"inp_rating_radio_4\" />
					<img src=\"_gfx/icons/star_"; if($inp_stars > 3){ echo"on"; } else{ echo"off"; } echo".png\" alt=\"star_off.png\" class=\"inp_rating_image_4\" />

					<input type=\"radio\" name=\"inp_stars\" value=\"5\""; if($inp_stars == "5"){ echo" checked=\"checked\""; } echo" class=\"inp_rating_radio_5\" />
					<img src=\"_gfx/icons/star_"; if($inp_stars > 4){ echo"on"; } else{ echo"off"; } echo".png\" alt=\"star_off.png\" class=\"inp_rating_image_5\" />
				</p>

				
				<p><b>$l_comment:</b><br />
				<textarea name=\"inp_text\" rows=\"6\" cols=\"80\" style=\"width: 80%;\"></textarea><br />
				<input type=\"submit\" value=\"$l_save_rating\" class=\"btn_default\" />
				</p>
			  </td>
			 </tr>
			</table>

			</form>
		<!-- //New rating form -->
		";
	} // can rate
} // logged in
else{
	echo"
	<p>
	<a href=\"$root/users/login.php?l=$l&amp;referer=food/view_food.php?food_id=$food_id&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id\" class=\"btn_default\">$l_login_to_post_comment</a>
	<a href=\"$root/users/create_free_account.php?l=$l\" class=\"btn_default\">$l_create_new_account</a>
	";
}
?>