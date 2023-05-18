<?php 
/**
*
* File: recipes/view_recipe_write_comment.php
* Version 2.0.0
* Date 22:33 05.02.2019
* Copyright (c) 2011-2019 Localhost
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
include("$root/_admin/_data/logo.php");

/*- Tables ---------------------------------------------------------------------------- */
include("_tables.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/recipes/ts_index.php");
include("$root/_admin/_translations/site/$l/recipes/ts_view_recipe.php");
include("$root/_admin/_translations/site/$l/recipes/ts_view_recipe_write_comment.php");



/*- Tables ---------------------------------------------------------------------------------- */
include("_tables.php");


/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['recipe_id'])) {
	$recipe_id = $_GET['recipe_id'];
	$recipe_id = strip_tags(stripslashes($recipe_id));
}
else{
	$recipe_id = "";
}
$l_mysql = quote_smart($link, $l);


/*- Get recipe ------------------------------------------------------------------------- */
// Select
$recipe_id_mysql = quote_smart($link, $recipe_id);
$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_country, recipe_introduction, recipe_directions, recipe_image_path, recipe_image_h_a, recipe_image_h_b, recipe_image_v_a, recipe_thumb_h_a_278x156, recipe_thumb_h_b_278x156, recipe_video_h, recipe_video_v, recipe_date, recipe_date_saying, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_ingredient_id, recipe_ingredient_title, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_times_favorited, recipe_user_ip, recipe_notes, recipe_password, recipe_last_viewed, recipe_age_restriction, recipe_published FROM $t_recipes WHERE recipe_id=$recipe_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_country, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image_h_a, $get_recipe_image_h_b, $get_recipe_image_v_a, $get_recipe_thumb_h_a_278x156, $get_recipe_thumb_h_b_278x156, $get_recipe_video_h, $get_recipe_video_v, $get_recipe_date, $get_recipe_date_saying, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_ingredient_id, $get_recipe_ingredient_title, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_times_favorited, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password, $get_recipe_last_viewed, $get_recipe_age_restriction, $get_recipe_published) = $row;

/*- Headers ---------------------------------------------------------------------------------- */
if($get_recipe_id == ""){
	$website_title = "Server error 404";
}
else{
	$website_title = "$get_recipe_title $l_add_comment - $l_recipes";
}

if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */

if($get_recipe_id == ""){
	echo"
	<h1>Recipe not found</h1>

	<p>
	The recipe you are trying to view was not found.
	</p>

	<p>
	<a href=\"index.php\">Back</a>
	</p>
	";
}
else{

	if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
		// Category
		$recipe_language_mysql = quote_smart($link, $get_recipe_language);
		$query = "SELECT category_translation_title FROM $t_recipes_categories_translations WHERE category_id=$get_recipe_category_id AND category_translation_language=$recipe_language_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_category_translation_title) = $row;

		// Find recipe owner
		$query = "SELECT user_id, user_email, user_name, user_alias FROM $t_users WHERE user_id=$get_recipe_user_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_recipe_user_id, $get_recipe_user_email, $get_recipe_user_name, $get_recipe_user_alias) = $row;

		// Find me
		$my_user_id = $_SESSION['user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);

		$query = "SELECT user_id, user_email, user_name, user_alias FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias) = $row;


		// Get my photo
		$query = "SELECT photo_id, photo_destination, photo_thumb_40, photo_thumb_50, photo_thumb_60, photo_thumb_200 FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_40, $get_my_photo_thumb_50, $get_my_photo_thumb_60, $get_my_photo_thumb_200) = $row;




		// Check anti spam
		$can_write_comment = 1;
		$query = "SELECT comment_id, comment_time FROM $t_recipes_comments WHERE comment_user_id=$my_user_id_mysql ORDER BY comment_id DESC LIMIT 0,1";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_comment_id, $get_comment_time) = $row;
		if($get_comment_id != ""){
			$time = time();

			$diff = $time-$get_comment_time;

			if($diff < 120){
				echo"
				<h1>$l_anti_spam</h1>

				<h2>$l_hello</h2>
				<p>$l_please_wait_five_minutes_before_posting_a_new_comment</p>
				";
				$can_write_comment = 0;
			}
		}


		if($can_write_comment == 1){
			if($process == "1"){
				$inp_title = $_POST['inp_title'];
				$inp_title = output_html($inp_title);
				$inp_title_mysql = quote_smart($link, $inp_title);

				$inp_rating = $_POST['inp_rating'];
				$inp_rating = output_html($inp_rating);
				$inp_rating_mysql = quote_smart($link, $inp_rating);

				$inp_text = $_POST['inp_text'];
				$inp_text = output_html($inp_text);
				$inp_text_mysql = quote_smart($link, $inp_text);

				if(empty($inp_title)){
					$url = "view_recipe_write_comment.php?recipe_id=$get_recipe_id&l=$l&ft=error&fm=missing_title&inp_rating=$inp_rating&inp_text=$inp_text";
					header("Location: $url");
					exit;
				}
				if(empty($inp_rating)){
					$url = "view_recipe_write_comment.php?recipe_id=$get_recipe_id&l=$l&ft=error&fm=missing_rating&inp_title=$inp_title&inp_text=$inp_text";
					header("Location: $url");
					exit;
				}
				if(empty($inp_text)){
					$url = "view_recipe_write_comment.php?recipe_id=$get_recipe_id&l=$l&ft=error&fm=missing_text&inp_title=$inp_title&inp_rating=$inp_rating";
					header("Location: $url");
					exit;
				}

				// Number of comments
				$inp_recipe_comments = $get_recipe_comments+1;
				$result = mysqli_query($link, "UPDATE $t_recipes SET recipe_comments=$inp_recipe_comments WHERE recipe_id=$recipe_id_mysql") or die(mysqli_error($link));

					
	
				// lang
				$l_mysql = quote_smart($link, $l);

				// Datetime and time
				$datetime = date("Y-m-d H:i:s");
				$time = time();

				// Datetime print
				$year = substr($datetime, 0, 4);
				$month = substr($datetime, 5, 2);
				$day = substr($datetime, 8, 2);

				if($day < 10){
					$day = substr($day, 1, 1);
				}
		
				if($month == "01"){
					$month_saying = $l_january;
				}
				elseif($month == "02"){
					$month_saying = $l_february;
				}
				elseif($month == "03"){
					$month_saying = $l_march;
				}
				elseif($month == "04"){
					$month_saying = $l_april;
				}
				elseif($month == "05"){
					$month_saying = $l_may;
				}
				elseif($month == "06"){
					$month_saying = $l_june;
				}
				elseif($month == "07"){
					$month_saying = $l_july;
				}
				elseif($month == "08"){
					$month_saying = $l_august;
				}
				elseif($month == "09"){
					$month_saying = $l_september;
				}
				elseif($month == "10"){
					$month_saying = $l_october;
				}
				elseif($month == "11"){
					$month_saying = $l_november;
				}
				else{
					$month_saying = $l_december;
				}

				$inp_comment_date_print = "$day $month_saying $year";

				// Alias
				$inp_comment_user_alias_mysql = quote_smart($link, $get_my_user_alias);

				// Image
				$inp_comment_user_image_path_mysql = quote_smart($link, "_uploads/users/images/$get_my_user_id");

				// Image make a thumb
				$inp_comment_user_image_file = "$get_my_photo_thumb_60";
				$inp_comment_user_image_file_mysql = quote_smart($link, $inp_comment_user_image_file);
	
				// Ip 
				$inp_ip = $_SERVER['REMOTE_ADDR'];
				$inp_ip = output_html($inp_ip);
				$inp_ip_mysql = quote_smart($link, $inp_ip);

				$inp_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
				$inp_hostname = output_html($inp_hostname);
				$inp_hostname_mysql = quote_smart($link, $inp_hostname);

				$inp_user_agent = $_SERVER['HTTP_USER_AGENT'];
				$inp_user_agent = output_html($inp_user_agent);
				$inp_user_agent_mysql = quote_smart($link, $inp_user_agent);


				mysqli_query($link, "INSERT INTO $t_recipes_comments
				(comment_id, comment_recipe_id, comment_language, comment_approved, comment_datetime, comment_time, comment_date_print, comment_user_id, comment_user_alias, 
				comment_user_image_path, comment_user_image_file, comment_user_ip, comment_user_hostname, comment_user_agent, comment_title, 
				comment_text, comment_rating, comment_helpful_clicks, comment_useless_clicks, comment_marked_as_spam, comment_spam_checked, comment_spam_checked_comment) 
				VALUES 
				(NULL, $get_recipe_id, $l_mysql, '1', '$datetime', '$time', '$inp_comment_date_print', '$get_my_user_id', $inp_comment_user_alias_mysql, 
				$inp_comment_user_image_path_mysql, $inp_comment_user_image_file_mysql, $inp_ip_mysql, $inp_hostname_mysql, $inp_user_agent_mysql, $inp_title_mysql,
				$inp_text_mysql, $inp_rating_mysql, '0', '0', '0', '0', '')")
				or die(mysqli_error($link));
				
				// Get comment id
				$query = "SELECT comment_id FROM $t_recipes_comments WHERE comment_time='$time'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_comment_id) = $row;


				// Rating
				$query_rating = "SELECT rating_id, rating_recipe_id, rating_1, rating_2, rating_3, rating_4, rating_5, rating_total_votes, rating_average, rating_votes_plus_average, rating_ip_block FROM $t_recipes_rating WHERE rating_recipe_id='$get_recipe_id'";
				$result_rating = mysqli_query($link, $query_rating);
				$row_rating = mysqli_fetch_row($result_rating);
				list($get_rating_id, $get_rating_recipe_id, $get_rating_1, $get_rating_2, $get_rating_3, $get_rating_4, $get_rating_5, $get_rating_total_votes, $get_rating_average, $get_rating_votes_plus_average, $get_rating_ip_block) = $row_rating;
				if($get_rating_id == ""){
					// Create rating
					$inp_language = output_html($get_recipe_language);
					$inp_language_mysql = quote_smart($link, $inp_language);
					mysqli_query($link, "INSERT INTO $t_recipes_rating
					(rating_id, rating_recipe_id, rating_recipe_lang, rating_1, rating_2, rating_3, rating_4, rating_5, rating_total_votes, rating_average, rating_votes_plus_average, rating_ip_block) 
					VALUES 
					(NULL, '$get_recipe_id', $inp_language_mysql, '0', '0', '0', '0', '0', '0', '0', '0', '')")
					or die(mysqli_error($link));
			
				}



				// Edit ratings
				$query = "SELECT count(comment_rating) FROM $t_recipes_comments WHERE comment_recipe_id=$get_recipe_id AND comment_rating='1'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_comment_rating_stars_1) = $row;

				$query = "SELECT count(comment_rating) FROM $t_recipes_comments WHERE comment_recipe_id=$get_recipe_id AND comment_rating='2'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_comment_rating_stars_2) = $row;

				$query = "SELECT count(comment_rating) FROM $t_recipes_comments WHERE comment_recipe_id=$get_recipe_id AND comment_rating='3'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_comment_rating_stars_3) = $row;

				$query = "SELECT count(comment_rating) FROM $t_recipes_comments WHERE comment_recipe_id=$get_recipe_id AND comment_rating='4'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_comment_rating_stars_4) = $row;

				$query = "SELECT count(comment_rating) FROM $t_recipes_comments WHERE comment_recipe_id=$get_recipe_id AND comment_rating='5'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_comment_rating_stars_5) = $row;



				$inp_rating_total_votes = $get_comment_rating_stars_1+$get_comment_rating_stars_2+$get_comment_rating_stars_3+$get_comment_rating_stars_4+$get_comment_rating_stars_5;
				$inp_rating_average     = round((($get_comment_rating_stars_1*1) + ($get_comment_rating_stars_2*2) + ($get_comment_rating_stars_3*3) + ($get_comment_rating_stars_4*4) + ($get_comment_rating_stars_5*5))/$inp_rating_total_votes);

				
				$rating_votes_plus_average = $inp_rating_total_votes+$inp_rating_average;

				$result = mysqli_query($link, "UPDATE $t_recipes_rating SET rating_1=$get_comment_rating_stars_1, 
								rating_2=$get_comment_rating_stars_2, 
								rating_3=$get_comment_rating_stars_3, 
								rating_4=$get_comment_rating_stars_4, 
								rating_5=$get_comment_rating_stars_5,
								rating_total_votes=$inp_rating_total_votes, 
								rating_average=$inp_rating_average, 
								rating_votes_plus_average=$rating_votes_plus_average, 
								rating_ip_block='' 
								WHERE rating_recipe_id=$get_recipe_id") or die(mysqli_error($link));
					




				
				// Email to owner
				$subject = "$get_recipe_title $l_new_comment_lowercase ($inp_comment_date_print)";


				$message = "<html>\n";
				$message = $message. "<head>\n";
				$message = $message. "  <title>$subject</title>\n";
				$message = $message. " </head>\n";
				$message = $message. "<body>\n";



				$message = $message. "<p>$l_hello $get_recipe_user_alias,</p>

<p>
$l_there_is_a_new_comment_to_the_recipe $get_recipe_title $l_at_lowercase $configWebsiteTitleSav.<br />
$l_follow_the_url_to_read_the_comment<br />
<a href=\"$configSiteURLSav/recipes/view_recipe.php?recipe_id=$get_recipe_id&l=$l#comment$get_comment_id\">$configSiteURLSav/recipes/view_recipe.php?recipe_id=$get_recipe_id&l=$l#comment$get_comment_id</a>
</p>

<p>
--<br />
$l_regards<br />
$configFromNameSav<br />
$l_email: $configFromEmailSav<br />
$l_web: $configWebsiteTitleSav
</p>";
				$message = $message. "</body>\n";
				$message = $message. "</html>\n";


				$headers_mail_mod = array();
				$headers_mail_mod[] = 'MIME-Version: 1.0';
				$headers_mail_mod[] = 'Content-type: text/html; charset=utf-8';
				$headers_mail_mod[] = "From: $configFromNameSav <" . $configFromEmailSav . ">";
				mail($get_recipe_user_email, $subject, $message, implode("\r\n", $headers_mail_mod));



				// Email to moderators
				$query = "SELECT user_id, user_email, user_name, user_alias, user_language FROM $t_users WHERE user_rank='admin' OR user_rank='moderator'";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_mod_user_id, $get_mod_user_email, $get_mod_user_name, $get_mod_user_alias, $get_user_language) = $row;



					$subject = "$get_recipe_title $l_new_comment_lowercase $inp_comment_date_print at $configWebsiteTitleSav";

					$message = "<html>\n";
					$message = $message. "<head>\n";
					$message = $message. "  <title>$subject</title>\n";
					$message = $message. " </head>\n";
					$message = $message. "<body>\n";

					$message = $message . "<p><a href=\"$configSiteURLSav\"><img src=\"$configSiteURLSav/$logoPathSav/$logoFileSav\" alt=\"$logoFileSav\" /></a></p>\n\n";
					$message = $message . "<h1>New Recipe Comment</h1>\n\n";

						
					$message = $message . "<p>\n";
					$message = $message . "$l_there_is_a_new_comment_to_the_recipe $get_recipe_title $l_at_lowercase $configWebsiteTitleSav.<br />\n";
					$message = $message . "$l_follow_the_url_to_read_the_comment<br />\n";
					$message = $message . "<a href=\"$configSiteURLSav/recipes/view_recipe.php?recipe_id=$get_recipe_id&l=$l#comment$get_comment_id\">$configSiteURLSav/recipes/view_recipe.php?recipe_id=$get_recipe_id&l=$l#comment$get_comment_id</a>\n";
					$message = $message . "</p>\n";

					$message = $message . "<p>\n";
					$message = $message . "Recipe ID: <a href=\"$configSiteURLSav/recipes/view_recipe.php?recipe_id=$get_recipe_id&l=$l\">$get_recipe_id</a><br />\n";
					$message = $message . "Recipe title: $get_recipe_title<br />\n";
					$message = $message . "</p>\n";

					$message = $message . "<p>\n";
					$message = $message . "Comment ID: $get_comment_id<br />\n";
					$message = $message . "Language: $l<br />\n";
					$message = $message . "Datetime: $datetime<br />\n";
					$message = $message . "User ID: <a href=\"$configSiteURLSav/users/view_profile.php?user_id=$get_my_user_id\">$get_my_user_id</a><br />\n";
					$message = $message . "Email: $get_my_user_email<br />\n";
					$message = $message . "Alias: $get_my_user_alias ($get_my_user_name)<br />\n";
					$message = $message . "IP: $inp_ip <br />\n";
					$message = $message . "Hostname: $inp_hostname<br />\n";
					$message = $message . "User agent: $inp_user_agent <br />\n";
					$message = $message . "Title: $inp_title <br />\n";
					$message = $message . "Rating: $inp_rating<br />\n";
					$message = $message . "Text: $inp_text\n";
					$message = $message . "</p>\n";

					$message = $message . "<p>\n\n--<br />\nBest regards<br />\n$get_mod_user_name at $configWebsiteTitleSav<br />\n";
					$message = $message . "<a href=\"$configSiteURLSav/index.php?l=$l\">$configSiteURLSav</a></p>";
					$message = $message. "</body>\n";
					$message = $message. "</html>\n";

					// Preferences for Subject field
					$headers_mail_mod = array();
					$headers_mail_mod[] = 'MIME-Version: 1.0';
					$headers_mail_mod[] = 'Content-type: text/html; charset=utf-8';
					$headers_mail_mod[] = "From: $configFromNameSav <" . $configFromEmailSav . ">";


					if($get_recipe_user_email != "$get_mod_user_email"){
						mail($get_mod_user_email, $subject, $message, implode("\r\n", $headers_mail_mod));
					}
				} 

				// Chef of the month
				$year = date("Y");
				$month = date("m");
				$month_full = date("F");
				$month_short = date("M");
				$year = date("Y");
				$month = date("m");
				$month_full = date("F");
				$month_short = date("M");
				$week = date("W");
				
				$query = "SELECT stats_chef_of_the_month_id, stats_chef_of_the_month_recipes_posted_count, stats_chef_of_the_month_recipes_posted_points, stats_chef_of_the_month_got_visits_count, stats_chef_of_the_month_got_visits_points, stats_chef_of_the_month_got_favorites_count, stats_chef_of_the_month_got_favorites_points, stats_chef_of_the_month_got_comments_count, stats_chef_of_the_month_got_comments_points, stats_chef_of_the_month_total_points FROM $t_recipes_stats_chef_of_the_month WHERE stats_chef_of_the_month_month=$month AND stats_chef_of_the_month_year=$year AND stats_chef_of_the_month_user_id=$get_recipe_user_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_stats_chef_of_the_month_id, $get_stats_chef_of_the_month_recipes_posted_count, $get_stats_chef_of_the_month_recipes_posted_points, $get_stats_chef_of_the_month_got_visits_count, $get_stats_chef_of_the_month_got_visits_points, $get_stats_chef_of_the_month_got_favorites_count, $get_stats_chef_of_the_month_got_favorites_points, $get_stats_chef_of_the_month_got_comments_count, $get_stats_chef_of_the_month_got_comments_points, $get_stats_chef_of_the_month_total_points) = $row;
				if($get_stats_chef_of_the_month_id == ""){
					// Author Photo
					$q = "SELECT photo_id, photo_user_id, photo_destination, photo_thumb_50, photo_thumb_60, photo_thumb_200 FROM $t_users_profile_photo WHERE photo_user_id='$get_recipe_user_id' AND photo_profile_image='1'";
					$r = mysqli_query($link, $q);
					$rowb = mysqli_fetch_row($r);
					list($get_photo_id, $get_photo_user_id, $get_photo_destination, $get_photo_thumb_50, $get_photo_thumb_60, $get_photo_thumb_200) = $rowb;

					// Insert chef of the month
					$inp_user_name_mysql = quote_smart($link, $get_recipe_user_name);
					$inp_user_photo_path_mysql = quote_smart($link, "_uploads/users/images/$get_recipe_user_id");
					$inp_user_photo_thumb_mysql = quote_smart($link, $get_photo_thumb_200);

					mysqli_query($link, "INSERT INTO $t_recipes_stats_chef_of_the_month 
					(stats_chef_of_the_month_id, stats_chef_of_the_month_month, stats_chef_of_the_month_month_full, stats_chef_of_the_month_month_short, stats_chef_of_the_month_year, 
					stats_chef_of_the_month_user_id, stats_chef_of_the_month_user_name, stats_chef_of_the_month_user_photo_path, stats_chef_of_the_month_user_photo_thumb, stats_chef_of_the_month_recipes_posted_count, 
					stats_chef_of_the_month_recipes_posted_points, stats_chef_of_the_month_got_visits_count, stats_chef_of_the_month_got_visits_points, stats_chef_of_the_month_got_favorites_count, stats_chef_of_the_month_got_favorites_points, 
					stats_chef_of_the_month_got_comments_count, stats_chef_of_the_month_got_comments_points, stats_chef_of_the_month_total_points) 
					VALUES 
					(NULL, $month, '$month_full', '$month_short', $year,
					$get_recipe_user_id, $inp_user_name_mysql, $inp_user_photo_path_mysql, $inp_user_photo_thumb_mysql, 0,
					0,0, 0, 0, 0, 1,
					5, 5)")
					or die(mysqli_error($link));
				}
				else{
					// Update visit
					$inp_count = $get_stats_chef_of_the_month_got_comments_count+1;
					$inp_points = $inp_count*5;
					$inp_total_points = $get_stats_chef_of_the_month_recipes_posted_points+$get_stats_chef_of_the_month_got_visits_points+$get_stats_chef_of_the_month_got_favorites_points+$inp_points;
					mysqli_query($link, "UPDATE $t_recipes_stats_chef_of_the_month SET stats_chef_of_the_month_got_comments_count=$inp_count, stats_chef_of_the_month_got_comments_points=$inp_points, stats_chef_of_the_month_total_points=$inp_total_points WHERE stats_chef_of_the_month_id=$get_stats_chef_of_the_month_id") or die(mysqli_error($link)); 
				}

				// Feed
				$inp_feed_title_mysql = quote_smart($link, $inp_title);
				$inp_feed_text_mysql = quote_smart($link, $inp_text);
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

				$inp_feed_link_url = "recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l#comment$get_comment_id";
				$inp_feed_link_url_mysql = quote_smart($link, $inp_feed_link_url);

				$inp_feed_link_name_mysql = quote_smart($link, "$l_read_comment");

				// Feed category name
				$query = "SELECT category_translation_title FROM $t_recipes_categories_translations WHERE category_id=$get_recipe_category_id AND category_translation_language=$l_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_category_translation_title) = $row;
				$inp_feed_category_name_mysql = quote_smart($link, $get_category_translation_title);

				/*
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
				*/

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


				$query = "SELECT feed_id FROM $t_users_feeds_index WHERE feed_module_name='recipes' AND feed_module_part_name='comment' AND feed_module_part_id=$get_comment_id AND feed_user_id=$get_my_user_id";
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
					'comment', $get_comment_id, $get_recipe_category_id, $inp_feed_category_name_mysql, 
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


				// Recipes Stats Comments per Month
				$inp_recipe_title_mysql = quote_smart($link, $get_recipe_title);
				$inp_recipe_image_path_mysql = quote_smart($link, $get_recipe_image_path);
				$inp_recipe_thumb_278x156_mysql = quote_smart($link, $get_recipe_thumb_h_a_278x156);
				$inp_recipe_language_mysql = quote_smart($link, $get_recipe_language);
				
				
				$query = "SELECT stats_comment_per_month_id, stats_comment_per_month_count FROM $t_recipes_stats_comments_per_month WHERE stats_comment_per_month_month=$month AND stats_comment_per_month_year=$year AND stats_comment_per_month_recipe_id=$get_recipe_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_stats_comment_per_month_id, $get_stats_comment_per_month_count) = $row;
				if($get_stats_comment_per_month_id == ""){
					// First visit this month
					$inp_category_translated_mysql = quote_smart($link, $get_category_translation_title);
					mysqli_query($link, "INSERT INTO $t_recipes_stats_comments_per_month
					(stats_comment_per_month_id, stats_comment_per_month_month, stats_comment_per_month_month_full, stats_comment_per_month_month_short, stats_comment_per_month_year, 
					stats_comment_per_month_recipe_id, stats_comment_per_month_recipe_title, stats_comment_per_month_recipe_image_path, stats_comment_per_month_recipe_thumb_278x156, stats_comment_per_month_recipe_language, 
					stats_comment_per_month_recipe_category_id, stats_comment_per_month_recipe_category_translated, stats_comment_per_month_count) 
					VALUES 
					(NULL, $month, '$month_full', '$month_short', $year,
					$get_recipe_id, $inp_recipe_title_mysql, $inp_recipe_image_path_mysql, $inp_recipe_thumb_278x156_mysql, $inp_recipe_language_mysql,
					$get_recipe_category_id, $inp_category_translated_mysql, 1)")
					or die(mysqli_error($link)); 
				}
				else{
					// Update visit
					$inp_count = $get_stats_comment_per_month_count+1;
					mysqli_query($link, "UPDATE $t_recipes_stats_comments_per_month SET stats_comment_per_month_count=$inp_count WHERE stats_comment_per_month_id=$get_stats_comment_per_month_id") or die(mysqli_error($link)); 
				}

				// Recipes Stats Comments per Year
				$query = "SELECT stats_comment_per_year_id, stats_comment_per_year_count FROM $t_recipes_stats_comments_per_year WHERE stats_comment_per_year_year=$year AND stats_comment_per_year_recipe_id=$get_recipe_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_stats_comment_per_year_id, $get_stats_comment_per_year_count) = $row;
				if($get_stats_comment_per_year_id == ""){
					// First visit this month
					$inp_category_translated_mysql = quote_smart($link, $get_category_translation_title);
					mysqli_query($link, "INSERT INTO $t_recipes_stats_comments_per_year
					(stats_comment_per_year_id, stats_comment_per_year_year, stats_comment_per_year_recipe_id, stats_comment_per_year_recipe_title, stats_comment_per_year_recipe_image_path, 
					stats_comment_per_year_recipe_thumb_278x156, stats_comment_per_year_recipe_language, stats_comment_per_year_recipe_category_id, stats_comment_per_year_recipe_category_translated, stats_comment_per_year_count) 
					VALUES 
					(NULL, $year, $get_recipe_id, $inp_recipe_title_mysql, $inp_recipe_image_path_mysql, 
					$inp_recipe_thumb_278x156_mysql, $inp_recipe_language_mysql, $get_recipe_category_id, $inp_category_translated_mysql, 1)")
					or die(mysqli_error($link)); 
				}
				else{
					// Update visit
					$inp_count = $get_stats_comment_per_year_count+1;
					mysqli_query($link, "UPDATE $t_recipes_stats_comments_per_year SET stats_comment_per_year_count=$inp_count WHERE stats_comment_per_year_id=$get_stats_comment_per_year_id") or die(mysqli_error($link)); 
				}



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
				$url = "view_recipe.php?recipe_id=$get_recipe_id&l=$l&ft=success&fm=comment_saved#comment$get_comment_id";
				header("Location: $url");
				exit;

			} // process

        		echo" 
			<h1>$get_recipe_title</h1>

			<!-- Where am I? -->
				<p>$l_you_are_here:<br />
				<a href=\"index.php?l=$l\">$l_recipes</a>
				&gt;
				<a href=\"view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\">$get_recipe_title</a>
				&gt;
				<a href=\"view_recipe_write_comment.php?recipe_id=$get_recipe_id&amp;l=$l\">$l_add_comment</a>
				</p>
			<!-- //Where am I? -->

			<!-- Feedback -->
				";
				if($ft != ""){
					if($fm == "changes_saved"){
						$fm = "$l_changes_saved";
					}
					else{
						$fm = ucfirst($fm);
					}
					echo"<div class=\"$ft\"><span>$fm</span></div>";
				}
				echo"	
			<!-- //Feedback -->


			<!-- New comment form -->

				<form method=\"post\" action=\"view_recipe_write_comment.php?recipe_id=$get_recipe_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			
				<!-- Focus -->
					<script>
					\$(document).ready(function(){
						\$('[name=\"inp_title\"]').focus();
					});
					</script>
				<!-- //Focus -->
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


				<p><b>$l_title:</b><br />
				<input type=\"text\" name=\"inp_title\" ";if(isset($_GET['inp_title'])) { $inp_title = $_GET['inp_title']; $inp_title = strip_tags(stripslashes($inp_title)); echo"value=\"$inp_title\""; } echo" size=\"25\" style=\"width: 98%;\" />
				</p>

				<p><b>$l_set_rating:</b><br />
					";
					if(isset($_GET['inp_rating'])) { 
						$inp_rating = $_GET['inp_rating']; 
						$inp_rating = strip_tags(stripslashes($inp_rating));
					}
					else{
						$inp_rating = "1";
					}
					echo"
					<input type=\"radio\" name=\"inp_rating\" value=\"1\""; if($inp_rating == "1"){ echo" checked=\"checked\""; } echo" class=\"inp_rating_radio_1\" />
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" class=\"inp_rating_image_1\" />

					<input type=\"radio\" name=\"inp_rating\" value=\"2\""; if($inp_rating == "2"){ echo" checked=\"checked\""; } echo" class=\"inp_rating_radio_2\" />
					<img src=\"_gfx/icons/star_off.png\" alt=\"star_off.png\" class=\"inp_rating_image_2\" />

					<input type=\"radio\" name=\"inp_rating\" value=\"3\""; if($inp_rating == "3"){ echo" checked=\"checked\""; } echo" class=\"inp_rating_radio_3\" />
					<img src=\"_gfx/icons/star_off.png\" alt=\"star_off.png\" class=\"inp_rating_image_3\" />

					<input type=\"radio\" name=\"inp_rating\" value=\"4\""; if($inp_rating == "4"){ echo" checked=\"checked\""; } echo" class=\"inp_rating_radio_4\" />
					<img src=\"_gfx/icons/star_off.png\" alt=\"star_off.png\" class=\"inp_rating_image_4\" />

					<input type=\"radio\" name=\"inp_rating\" value=\"5\""; if($inp_rating == "5"){ echo" checked=\"checked\""; } echo" class=\"inp_rating_radio_5\" />
					<img src=\"_gfx/icons/star_off.png\" alt=\"star_off.png\" class=\"inp_rating_image_5\" />
				</p>

				<p><b>$l_comment:</b><br />
				<textarea name=\"inp_text\" rows=\"8\" cols=\"30\" style=\"width: 98%;\">";
				if(isset($_GET['inp_text'])) { $inp_text = $_GET['inp_text']; $inp_text = strip_tags(stripslashes($inp_text)); echo"$inp_text"; } echo"</textarea>
				</p>

				<p>
				<input type=\"submit\" value=\"$l_save\" class=\"btn_default\" />
				</p>
				</form>
			<!-- //New comment form -->
			";


		} // can write comment
	} // logged in
	else{
		echo"
		<h1>
		<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
		Loading...</h1>
		<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=$root/recipes/view_recipe_write_comment.php?recipe_id=$get_recipe_id\">
		";	
	}
} // recipe found

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>