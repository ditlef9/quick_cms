<?php 
/**
*
* File: recipes/step_2_directions.php
* Version 1.0.0
* Date 23:59 27.11.2017
* Copyright (c) 2011-2017 Localhost
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration --------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

/*- Root dir -------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config -------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Tables ---------------------------------------------------------------------------- */
include("_tables.php");

/*- Tables ---------------------------------------------------------------------------------- */
include("_tables.php");
/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index = $mysqlPrefixSav . "search_engine_index";



/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/recipes/ts_index.php");


/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['mode'])){
	$mode = $_GET['mode'];
	$mode = output_html($mode);
}
else{
	$mode = "";
}
if(isset($_GET['recipe_id'])){
	$recipe_id = $_GET['recipe_id'];
	$recipe_id = output_html($recipe_id);
}
else{
	$recipe_id = "";
}
$tabindex = 0;
$l_mysql = quote_smart($link, $l);

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_submit_recipe - $l_recipes";
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	// Dates
	$week = date("W");
	$year = date("Y");
	$month = date("m");
	$month_full = date("F");
	$month_short = date("M");

	// Get recipe
	$recipe_id_mysql = quote_smart($link, $recipe_id);
	$inp_recipe_user_id = $_SESSION['user_id'];
	$inp_recipe_user_id = output_html($inp_recipe_user_id);
	$inp_recipe_user_id_mysql = quote_smart($link, $inp_recipe_user_id);



	$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_country, recipe_introduction, recipe_directions, recipe_image_path, recipe_image_h_a, recipe_image_h_b, recipe_image_v_a, recipe_thumb_h_a_278x156, recipe_thumb_h_b_278x156, recipe_video_h, recipe_video_v, recipe_date, recipe_date_saying, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_times_favorited, recipe_user_ip, recipe_notes, recipe_password, recipe_last_viewed, recipe_age_restriction, recipe_published FROM $t_recipes WHERE recipe_user_id=$inp_recipe_user_id_mysql AND recipe_id=$recipe_id_mysql AND recipe_user_id=$inp_recipe_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_country, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image_h_a, $get_recipe_image_h_b, $get_recipe_image_v_a, $get_recipe_thumb_h_a_278x156, $get_recipe_thumb_h_b_278x156, $get_recipe_video_h, $get_recipe_video_v, $get_recipe_date, $get_recipe_date_saying, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_times_favorited, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password, $get_recipe_last_viewed, $get_recipe_age_restriction, $get_recipe_published) = $row;

	if($get_recipe_id == ""){
		echo"
		<h1>Server error</h1>

		<p>
		Recipe not found.
		</p>
		";
	}
	else{
		// Dates
		$datetime = date("Y-m-d H:i:s");
		$datetime_saying = date("j. M Y H:i");

		// Author
		$query = "SELECT user_name, user_alias FROM $t_users WHERE user_id=$inp_recipe_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_user_name, $get_user_alias) = $row;

		// Author Photo
		$q = "SELECT photo_id, photo_user_id, photo_destination, photo_thumb_50, photo_thumb_60, photo_thumb_200 FROM $t_users_profile_photo WHERE photo_user_id=$inp_recipe_user_id_mysql AND photo_profile_image='1'";
		$r = mysqli_query($link, $q);
		$rowb = mysqli_fetch_row($r);
		list($get_photo_id, $get_photo_user_id, $get_photo_destination, $get_photo_thumb_50, $get_photo_thumb_60, $get_photo_thumb_200) = $rowb;
	

		


		// Who is moderator of the week?
		$query = "SELECT moderator_user_id, moderator_user_email, moderator_user_name FROM $t_users_moderator_of_the_week WHERE moderator_week=$week AND moderator_year=$year";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name) = $row;
		if($get_moderator_user_id == ""){
			// Create moderator of the week
			include("$root/_admin/_functions/create_moderator_of_the_week.php");
					
			$query = "SELECT moderator_user_id, moderator_user_email, moderator_user_name FROM $t_users_moderator_of_the_week WHERE moderator_week=$week AND moderator_year=$year";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name) = $row;
		}




		if($get_recipe_notes == "E-mail not sent to administrators"){

			// Mail from
			$host = $_SERVER['HTTP_HOST'];
			$from = "post@" . $_SERVER['HTTP_HOST'];
			$reply = "post@" . $_SERVER['HTTP_HOST'];
			
			$view_link = $configSiteURLSav . "/recipes/view_recipe.php?recipe_id=$get_recipe_id";
			$edit_link = $configControlPanelURLSav . "/index.php?open=recipes&page=edit_recipe&recipe_id=$get_recipe_id";
			$delete_link = $configControlPanelURLSav . "/index.php?open=recipes&page=delete_recipe&recipe_id=$get_recipe_id";
			
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			$user_agent = output_html($user_agent);

			$subject = "New recipe $get_recipe_title at $host";

			$message = "<html>\n";
			$message = $message. "<head>\n";
			$message = $message. "  <title>$subject</title>\n";
			$message = $message. " </head>\n";
			$message = $message. "<body>\n";

			$message = $message . "<p>Hi $get_moderator_user_name,</p>\n\n";
			$message = $message . "<p><b>Summary:</b><br />There is a new recipe at $host for lanugage $l. This is a information e-mail. No action is needed.</p>\n\n";

			$message = $message . "<p style='padding-bottom:0;margin-bottom:0'><b>Information:</b></p>\n";
			$message = $message . "<table>\n";
			$message = $message . " <tr><td><span>Recipe ID:</span></td><td><span>$get_recipe_language</span></td></tr>\n";
			$message = $message . " <tr><td><span>Category:</span></td><td><span>$get_recipe_category_id</span></td></tr>\n";
			$message = $message . " <tr><td><span>Title:</span></td><td><span>$get_recipe_title</span></td></tr>\n";
			$message = $message . " <tr><td><span>Language:</span></td><td><span>$get_recipe_language</span></td></tr>\n";
			$message = $message . " <tr><td><span>Introduction:</span></td><td><span>$get_recipe_introduction</span></td></tr>\n";
			$message = $message . " <tr><td><span>Date time:</span></td><td><span>$get_recipe_date $get_recipe_time</span></td></tr>\n";
			$message = $message . " <tr><td><span>Cusine:</span></td><td><span>$get_recipe_cusine_id</span></td></tr>\n";
			$message = $message . " <tr><td><span>Season:</span></td><td><span>$get_recipe_season_id</span></td></tr>\n";
			$message = $message . " <tr><td><span>Occasion:</span></td><td><span>$get_recipe_occasion_id</span></td></tr>\n";
			$message = $message . "</table>\n";
		

			$message = $message . "<p style='padding-bottom:0;margin-bottom:0'><b>User:</b></p>\n";
			$message = $message . "<table>\n";
			$message = $message . " <tr><td><span>User:</span></td><td><span>$get_recipe_user_id</span></td></tr>\n";
			$message = $message . " <tr><td><span>User IP:</span></td><td><span>$get_recipe_user_ip</span></td></tr>\n";
			$message = $message . " <tr><td><span>User agent:</span></td><td><span>$user_agent</span></td></tr>\n";
			$message = $message . "</table>\n";
		
			$message = $message . "<p><b>Image:</b><br />\n";
			if($get_recipe_image_h_a != ""){
				$message = $message . "<img src='$configSiteURLSav/$get_recipe_image_path/$get_recipe_image_h_a' alt='$get_recipe_image_h_a' /></p>\n";
			}
			if($get_recipe_image_v_a != ""){
				$message = $message . "<img src='$configSiteURLSav/$get_recipe_image_path/$get_recipe_image_v_a' alt='$get_recipe_image_v_a' /></p>\n";
			}

			$message = $message . "<p><b>Videoes:</b><br />\n";
			if($get_recipe_video_h != ""){
				$message = $message . "<a href='$get_recipe_video_h'>$get_recipe_video_h</a></p>\n";
			}
			if($get_recipe_video_v != ""){
				$message = $message . "<a href='$get_recipe_video_v'>$get_recipe_video_v</a></p>\n";
			}

			$message = $message . "<p><b>Information:</b><br />\n";
			$message = $message . "$get_recipe_directions</p>\n";

			$message = $message . "<p><b>Actions:</b><br />\n";
			$message = $message . "View: <a href=\"$view_link\">$view_link</a><br />\n";
			$message = $message . "Edit: <a href=\"$edit_link\">$edit_link</a><br />\n";
			$message = $message . "Delete: <a href=\"$delete_link\">$delete_link</a></p>";
			$message = $message . "<p>\n\n--<br />\nBest regards<br />\n$host</p>";
			$message = $message. "</body>\n";
			$message = $message. "</html>\n";


			// Preferences for Subject field
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=utf-8';
			$headers[] = "From: $configFromNameSav <" . $configFromEmailSav . ">";
			mail($get_moderator_user_email, $subject, $message, implode("\r\n", $headers));


			// Update
			$result = mysqli_query($link, "UPDATE $t_recipes SET recipe_notes='' WHERE recipe_id=$recipe_id_mysql");


			// Set user points
			$query = "SELECT user_id, user_name, user_alias, user_language, user_rank, user_gender, user_dob, user_points FROM $t_users WHERE user_id='$get_recipe_user_id'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_user_id, $get_current_user_name, $get_current_user_alias, $get_current_user_language, $get_current_user_rank, $get_user_gender, $get_current_user_dob, $get_user_points) = $row;

			$inp_user_points = $get_user_points+1;

			$result = mysqli_query($link, "UPDATE $t_users SET user_points='$inp_user_points' WHERE user_id='$get_recipe_user_id'") or die(mysqli_error($link));



			// Chef of the month
			$query = "SELECT stats_chef_of_the_month_id, stats_chef_of_the_month_recipes_posted_count, stats_chef_of_the_month_recipes_posted_points, stats_chef_of_the_month_got_visits_count, stats_chef_of_the_month_got_visits_points, stats_chef_of_the_month_got_favorites_count, stats_chef_of_the_month_got_favorites_points, stats_chef_of_the_month_got_comments_count, stats_chef_of_the_month_got_comments_points, stats_chef_of_the_month_total_points FROM $t_recipes_stats_chef_of_the_month WHERE stats_chef_of_the_month_month=$month AND stats_chef_of_the_month_year=$year AND stats_chef_of_the_month_user_id=$get_recipe_user_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_stats_chef_of_the_month_id, $get_stats_chef_of_the_month_recipes_posted_count, $get_stats_chef_of_the_month_recipes_posted_points, $get_stats_chef_of_the_month_got_visits_count, $get_stats_chef_of_the_month_got_visits_points, $get_stats_chef_of_the_month_got_favorites_count, $get_stats_chef_of_the_month_got_favorites_points, $get_stats_chef_of_the_month_got_comments_count, $get_stats_chef_of_the_month_got_comments_points, $get_stats_chef_of_the_month_total_points) = $row;
			if($get_stats_chef_of_the_month_id == ""){
				// Insert chef of the month
				$inp_user_name_mysql = quote_smart($link, $get_user_name);
				$inp_user_photo_path_mysql = quote_smart($link, "_uploads/users/images/$get_recipe_user_id");
				$inp_user_photo_thumb_mysql = quote_smart($link, $get_photo_thumb_200);

				mysqli_query($link, "INSERT INTO $t_recipes_stats_chef_of_the_month 
				(stats_chef_of_the_month_id, stats_chef_of_the_month_month, stats_chef_of_the_month_month_full, stats_chef_of_the_month_month_short, stats_chef_of_the_month_year, 
				stats_chef_of_the_month_user_id, stats_chef_of_the_month_user_name, stats_chef_of_the_month_user_photo_path, stats_chef_of_the_month_user_photo_thumb, stats_chef_of_the_month_recipes_posted_count, 
				stats_chef_of_the_month_recipes_posted_points, stats_chef_of_the_month_got_visits_count, stats_chef_of_the_month_got_visits_points, stats_chef_of_the_month_got_favorites_count, stats_chef_of_the_month_got_favorites_points, 
				stats_chef_of_the_month_got_comments_count, stats_chef_of_the_month_got_comments_points, stats_chef_of_the_month_total_points) 
				VALUES 
				(NULL, $month, '$month_full', '$month_short', $year,
				$get_recipe_user_id, $inp_user_name_mysql, $inp_user_photo_path_mysql, $inp_user_photo_thumb_mysql, 1,
				7, 0, 0, 0, 0, 
				0, 0, 0)")
				or die(mysqli_error($link));
			}
			else{
				// Update visit
				$inp_count = $get_stats_chef_of_the_month_recipes_posted_count+1;
				$inp_points = $inp_count*7;
				$inp_total_points = $inp_points+$get_stats_chef_of_the_month_got_visits_points+$get_stats_chef_of_the_month_got_favorites_points+$get_stats_chef_of_the_month_got_comments_points;
				mysqli_query($link, "UPDATE $t_recipes_stats_chef_of_the_month SET stats_chef_of_the_month_recipes_posted_count=$inp_count, stats_chef_of_the_month_recipes_posted_points=$inp_points, stats_chef_of_the_month_total_points=$inp_total_points WHERE stats_chef_of_the_month_id=$get_stats_chef_of_the_month_id") or die(mysqli_error($link)); 
			}

		}
		
		// Search engine
		$inp_index_title = "$get_recipe_title"; 
		$inp_index_title_mysql = quote_smart($link, $inp_index_title);

		$inp_index_url = "recipes/view_recipe.php?recipe_id=$get_recipe_id";
		$inp_index_url_mysql = quote_smart($link, $inp_index_url);

		$inp_index_short_description = substr($get_recipe_introduction, 0, 200);
		$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

		// tags
		$inp_index_keywords = "";
		$query_r = "SELECT tag_id, tag_language, tag_recipe_id, tag_title, tag_title_clean, tag_user_id FROM $t_recipes_tags WHERE tag_recipe_id=$get_recipe_id";
		$result_r = mysqli_query($link, $query_r);
		while($row_r = mysqli_fetch_row($result_r)) {
			list($get_tag_id, $get_tag_language, $get_tag_recipe_id, $get_tag_title, $get_tag_title_clean, $get_tag_user_id) = $row_r;
			if($inp_index_keywords == ""){
				$inp_index_keywords  = "$get_tag_title";
			}
			else{
				$inp_index_keywords  = $inp_index_keywords . ", $get_tag_title";
			}
		}
		$inp_index_keywords_mysql = quote_smart($link, $inp_index_keywords);

		// Image
		$inp_index_image_path_mysql = quote_smart($link, $get_recipe_image_path);
		$inp_index_image_file_mysql = quote_smart($link, $get_recipe_image_h_a);

		// Thumb
		$thumb = "";
		if($get_recipe_image_h_a != ""){
			$ext = get_extension($get_recipe_image_h_a);
			$thumb = str_replace(".$ext", "", $get_recipe_image_h_a);
			$thumb = $thumb . "_235x132." . $ext;
		}
		$inp_index_image_thumb_mysql = quote_smart($link, $thumb);



		$inp_index_module_name_mysql = quote_smart($link, "recipes");

		$inp_index_module_part_name_mysql = quote_smart($link, "recipes");

		$inp_index_reference_name_mysql = quote_smart($link, "recipe_id");
		$inp_index_reference_id_mysql = quote_smart($link, "$get_recipe_id");

		$inp_index_has_access_control_mysql = quote_smart($link, 0);

		$inp_index_is_ad_mysql = quote_smart($link, 0);
	
		$inp_index_language_mysql = quote_smart($link, "$get_recipe_language");

		// Check if exists
		$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name=$inp_index_module_name_mysql AND index_reference_name=$inp_index_reference_name_mysql AND index_reference_id=$inp_index_reference_id_mysql";
		$result_exists = mysqli_query($link, $query_exists);
		$row_exists = mysqli_fetch_row($result_exists);
		list($get_index_id) = $row_exists;
		if($get_index_id == ""){
			// Insert
			// echo"<span>Insert $inp_index_title<br /></span>\n";
			mysqli_query($link, "INSERT INTO $t_search_engine_index 
			(index_id, index_title, index_url, index_short_description, index_keywords, 
			index_image_path, index_image_file, index_image_thumb_235x132, 
			index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
			index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
			index_unique_hits) 
			VALUES 
			(NULL, $inp_index_title_mysql, $inp_index_url_mysql, $inp_index_short_description_mysql, $inp_index_keywords_mysql, 
			$inp_index_image_path_mysql, $inp_index_image_file_mysql, $inp_index_image_thumb_mysql, 
			$inp_index_module_name_mysql, $inp_index_module_part_name_mysql, '0', $inp_index_reference_name_mysql, $inp_index_reference_id_mysql,
			'0', $inp_index_is_ad_mysql, '$datetime', '$datetime_saying', $inp_index_language_mysql,
			0)")
			or die(mysqli_error($link));
		}

		// Feed
		$inp_feed_title_mysql = quote_smart($link, $get_recipe_title);
		$inp_feed_text_mysql = quote_smart($link, "");
		$inp_feed_image_path_mysql = quote_smart($link, $get_recipe_image_path);
		$inp_feed_image_file_mysql = quote_smart($link, $get_recipe_image_h_a);

		// Feed Thumb 300x169
		$ext = get_extension($get_recipe_image_h_a);
		$img_name = str_replace(".$ext", "", $get_recipe_image_h_a);
		$inp_feed_image_thumb_a = $img_name . "_thumb_300x169." . $ext;
		$inp_feed_image_thumb_a_mysql = quote_smart($link, $inp_feed_image_thumb_a);

		// Feed Thumb 540x304
		$inp_feed_image_thumb_b = $img_name . "_thumb_540x304." . $ext;
		$inp_feed_image_thumb_b_mysql = quote_smart($link, $inp_feed_image_thumb_b);

		$inp_feed_link_url = "recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l";
		$inp_feed_link_url_mysql = quote_smart($link, $inp_feed_link_url);

		$inp_feed_link_name_mysql = quote_smart($link, "$l_view");

		// Feed category name
		$query = "SELECT category_translation_title FROM $t_recipes_categories_translations WHERE category_id=$get_recipe_category_id AND category_translation_language=$l_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_category_translation_title) = $row;
		$inp_feed_category_name_mysql = quote_smart($link, $get_category_translation_title);

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


		$query = "SELECT feed_id FROM $t_users_feeds_index WHERE feed_module_name='recipes' AND feed_module_part_name='recipe' AND feed_module_part_id=$get_recipe_id AND feed_user_id=$get_recipe_user_id";
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
			$inp_feed_image_thumb_a_mysql, $inp_feed_image_thumb_b_mysql, $inp_feed_link_url_mysql, $inp_feed_link_name_mysql, 'recipes', 
			'recipe', $get_recipe_id, $get_recipe_category_id, $inp_feed_category_name_mysql, 
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


		// Location
		echo"
		<h1>
		<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
		Loading...</h1>
		<meta http-equiv=\"refresh\" content=\"1;url=$root/recipes/view_recipe.php?recipe_id=$get_recipe_id\">
		";
		

	} // recipe found
}
else{
	$action = "noshow";
	echo"
	<h1>
	<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/recipes/submit_recipe.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>