<?php
/*- Functions ------------------------------------------------------------------------- */
include("../../_admin/_functions/output_html.php");
include("../../_admin/_functions/clean.php");
include("../../_admin/_functions/quote_smart.php");


/*- Config ----------------------------------------------------------------------------- */
include("../../_admin/_data/config/meta.php");
include("../../_admin/_data/config/user_system.php");
include("../../_admin/_data/logo.php");


/*- MySQL ----------------------------------------------------------------------------- */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);

$mysql_config_file = "../../_admin/_data/mysql_" . $server_name . ".php";
include("$mysql_config_file");
$link = mysqli_connect($mysqlHostSav, $mysqlUserNameSav, $mysqlPasswordSav, $mysqlDatabaseNameSav);
if (!$link) {
	echo "Error MySQL link";
	die;
}


/*- MySQL Tables ---------------------------------------------------------------------- */
$t_users 	 		= $mysqlPrefixSav . "users";
$t_users_profile_photo		= $mysqlPrefixSav . "users_profile_photo";
$t_users_email_subscriptions 	= $mysqlPrefixSav . "users_email_subscriptions";
$t_users_moderator_of_the_week	= $mysqlPrefixSav . "users_moderator_of_the_week";

$t_stats_comments_weekly  = $mysqlPrefixSav . "stats_comments_weekly";
$t_stats_comments_monthly = $mysqlPrefixSav . "stats_comments_monthly";
$t_stats_comments_yearly  = $mysqlPrefixSav . "stats_comments_yearly";


/*- Tables recipes -------------------------------------------------------------------- */
$t_recipes 	 			= $mysqlPrefixSav . "recipes";
$t_recipes_ingredients			= $mysqlPrefixSav . "recipes_ingredients";
$t_recipes_favorites			= $mysqlPrefixSav . "recipes_favorites";
$t_recipes_groups			= $mysqlPrefixSav . "recipes_groups";
$t_recipes_items			= $mysqlPrefixSav . "recipes_items";
$t_recipes_numbers			= $mysqlPrefixSav . "recipes_numbers";
$t_recipes_rating			= $mysqlPrefixSav . "recipes_rating";
$t_recipes_cuisines			= $mysqlPrefixSav . "recipes_cuisines";
$t_recipes_cuisines_translations	= $mysqlPrefixSav . "recipes_cuisines_translations";
$t_recipes_seasons			= $mysqlPrefixSav . "recipes_seasons";
$t_recipes_seasons_translations		= $mysqlPrefixSav . "recipes_seasons_translations";
$t_recipes_occasions			= $mysqlPrefixSav . "recipes_occasions";
$t_recipes_occasions_translations	= $mysqlPrefixSav . "recipes_occasions_translations";
$t_recipes_categories			= $mysqlPrefixSav . "recipes_categories";
$t_recipes_categories_translations	= $mysqlPrefixSav . "recipes_categories_translations";
$t_recipes_weekly_special		= $mysqlPrefixSav . "recipes_weekly_special";
$t_recipes_of_the_day			= $mysqlPrefixSav . "recipes_of_the_day";
$t_recipes_comments			= $mysqlPrefixSav . "recipes_comments";
$t_recipes_tags				= $mysqlPrefixSav . "recipes_tags";
$t_recipes_links			= $mysqlPrefixSav . "recipes_links";
$t_recipes_comments			= $mysqlPrefixSav . "recipes_comments";

/*- Find user ------------------------------------------------------------------------- */
if(isset($_POST['inp_user_id'])){
	$inp_user_id = $_POST['inp_user_id'];
	$inp_user_id = output_html($inp_user_id);
	$inp_user_id_mysql = quote_smart($link, $inp_user_id);
}
else{
	echo"Missing user id";
	die;
}
if(isset($_POST['inp_user_password'])){
	$inp_user_password = $_POST['inp_user_password']; // Already encrypted
}
else{
	echo"Missing user password";
	die;
}

// Check for user
$query = "SELECT user_id, user_password, user_email, user_name, user_alias, user_date_format FROM $t_users WHERE user_id=$inp_user_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_my_user_id, $get_my_user_password, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_date_format) = $row;






if($get_my_user_id == ""){
	echo"User id not found";
	die;
}
if($get_my_user_password != "$inp_user_password"){
	echo"Wrong user password";
	die;
}	

// Get my profile image
$q = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id=$get_my_user_id AND photo_profile_image='1'";
$r = mysqli_query($link, $q);
$rowb = mysqli_fetch_row($r);
list($get_my_photo_id, $get_my_photo_destination) = $rowb;


// Get my subscription status
$q = "SELECT es_id, es_on_off FROM $t_users_email_subscriptions WHERE es_user_id=$inp_user_id_mysql AND es_type='comments'";
$r = mysqli_query($link, $q);
$rowb = mysqli_fetch_row($r);
list($get_my_es_id, $get_my_es_on_off) = $rowb;

if($get_my_es_id == ""){
	mysqli_query($link, "INSERT INTO $t_users_email_subscriptions
	(es_id, es_user_id, es_type, es_on_off) 
	VALUES 
	(NULL, $inp_user_id_mysql, 'comments', '1')") or die(mysqli_error($link));
	
	$get_my_es_on_off = "1";
}






/*- Find recipe ------------------------------------------------------------------------- */
if(isset($_POST['inp_recipe_id'])) {
	$inp_recipe_id = $_POST['inp_recipe_id'];
	$inp_recipe_id = strip_tags(stripslashes($inp_recipe_id));
	$inp_recipe_id_mysql = quote_smart($link, $inp_recipe_id);

	$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_introduction, recipe_directions, recipe_image_path, recipe_image, recipe_thumb, recipe_video, recipe_date, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_user_ip, recipe_notes, recipe_password FROM $t_recipes  WHERE recipe_id=$inp_recipe_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb, $get_recipe_video, $get_recipe_date, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password) = $row;

	if($get_recipe_id == ""){
		echo"Recipe not found";
		die;
	}
}
else{
	echo"Missing inp recipe id";
	die;
}


/*- Translation ------------------------------------------------------------------------ */
include("../../_admin/_translations/site/$get_recipe_language/common/ts_common.php");
include("../../_admin/_translations/site/$get_recipe_language/recipes/ts_recipes.php");
include("../../_admin/_translations/site/$get_recipe_language/recipes/ts_view_recipe.php");
include("../../_admin/_translations/site/$get_recipe_language/recipes/ts_view_recipe_write_comment.php");



/*- Comment --------------------------------------------------------------------------- */
$inp_title = $_POST['inp_title'];
$inp_title = output_html($inp_title);
$inp_title_mysql = quote_smart($link, $inp_title);
if(empty($inp_title)){
	echo"Missing title";
	die;

}

$inp_rating = $_POST['inp_rating'];
$inp_rating = output_html($inp_rating);
$inp_rating_mysql = quote_smart($link, $inp_rating);
if(empty($inp_rating)){
	echo"Missing rating";
	die;
}

$inp_text = $_POST['inp_text'];
$inp_text = output_html($inp_text);
$inp_text_mysql = quote_smart($link, $inp_text);
if(empty($inp_text)){
	echo"Missing comment";
	die;
}



// Number of comments
$inp_recipe_comments = $get_recipe_comments+1;
$result = mysqli_query($link, "UPDATE $t_recipes SET recipe_comments=$inp_recipe_comments WHERE recipe_id=$get_recipe_id") or die(mysqli_error($link));

					


	
	
				// lang
				$l_mysql = quote_smart($link, $get_recipe_language);

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
				if($get_photo_destination != ""){
					$inp_new_x = 65; // 950
					$inp_new_y = 65; // 640
					$thumb_full_path = "$root/$get_comment_user_image_path/user_" . $get_my_user_id . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
					if(!(file_exists("$thumb_full_path"))){
						resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_my_user_id/$get_photo_destination", "$thumb_full_path");
					}
					$inp_comment_user_image_file = "user_" . $get_my_user_id . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
				}
				else{
					$inp_comment_user_image_file = "";
				}
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
				$query_rating = "SELECT rating_id, rating_recipe_id, rating_1, rating_2, rating_3, rating_4, rating_5, rating_total_votes, rating_average, rating_popularity, rating_ip_block FROM $t_recipes_rating WHERE rating_recipe_id='$get_recipe_id'";
				$result_rating = mysqli_query($link, $query_rating);
				$row_rating = mysqli_fetch_row($result_rating);
				list($get_rating_id, $get_rating_recipe_id, $get_rating_1, $get_rating_2, $get_rating_3, $get_rating_4, $get_rating_5, $get_rating_total_votes, $get_rating_average, $get_rating_popularity, $get_rating_ip_block) = $row_rating;
				if($get_rating_id == ""){
					// Create rating
					mysqli_query($link, "INSERT INTO $t_recipes_rating
					(rating_id, rating_recipe_id, rating_1, rating_2, rating_3, rating_4, rating_5, rating_total_votes, rating_average, rating_popularity, rating_ip_block) 
					VALUES 
					(NULL, '$get_recipe_id', '0', '0', '0', '0', '0', '0', '0', '0', '')")
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

				
				$positive = $get_comment_rating_stars_4+$get_comment_rating_stars_5;
				$negative = $get_comment_rating_stars_1+$get_comment_rating_stars_2;
				$total    = $positive+$negative;
				if($total == "0"){
					$inp_rating_popularity  = 0;
				}
				else{
					$inp_rating_popularity  = round(($positive/$total*100));
				}					
				$result = mysqli_query($link, "UPDATE $t_recipes_rating SET rating_1=$get_comment_rating_stars_1, 
								rating_2=$get_comment_rating_stars_2, 
								rating_3=$get_comment_rating_stars_3, 
								rating_4=$get_comment_rating_stars_4, 
								rating_5=$get_comment_rating_stars_5,
								rating_total_votes=$inp_rating_total_votes, rating_average=$inp_rating_average , rating_popularity=$inp_rating_popularity, rating_ip_block='' WHERE rating_recipe_id=$get_recipe_id") or die(mysqli_error($link));
					




				
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
					$message = $message . "Language: $get_recipe_language<br />\n";
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






			// Statistics
			// --> weekly
			$day = date("d");
			$month = date("m");
			$week = date("W");
			$year = date("Y");

			$query = "SELECT weekly_id, weekly_comments_written FROM $t_stats_comments_weekly WHERE weekly_week=$week AND weekly_year=$year";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_weekly_id,  $get_weekly_comments_written) = $row;
			if($get_weekly_id == ""){
				mysqli_query($link, "INSERT INTO $t_stats_comments_weekly 
				(weekly_id, weekly_week, weekly_year, weekly_comments_written, weekly_comments_written_diff_from_last_week, weekly_last_updated, weekly_last_updated_day, weekly_last_updated_month, weekly_last_updated_year) 
				VALUES 
				(NULL, $week, $year, 1, 1, '$datetime', $day, $month, $year)")
				or die(mysqli_error($link));
			}
			else{
				$inp_counter = $get_weekly_comments_written+1;
				$result = mysqli_query($link, "UPDATE $t_stats_comments_weekly SET weekly_comments_written=$inp_counter, 
						weekly_last_updated='$datetime', weekly_last_updated_day=$day, weekly_last_updated_month=$month, weekly_last_updated_year=$year WHERE weekly_id=$get_weekly_id") or die(mysqli_error($link));
			}

			// --> monthly
			$query = "SELECT monthly_id, monthly_comments_written FROM $t_stats_comments_monthly WHERE monthly_month=$month AND monthly_year=$year";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_monthly_id,  $get_monthly_comments_written) = $row;
			if($get_monthly_id == ""){
				mysqli_query($link, "INSERT INTO $t_stats_comments_monthly 
				(monthly_id, monthly_month, monthly_year, monthly_comments_written, monthly_last_updated, monthly_last_updated_day, monthly_last_updated_month, monthly_last_updated_year ) 
				VALUES 
				(NULL, $month, $year, 1, '$datetime', $day, $month, $year)")
				or die(mysqli_error($link));
			}
			else{
				$inp_counter = $get_monthly_comments_written+1;
				$result = mysqli_query($link, "UPDATE $t_stats_comments_monthly SET monthly_comments_written=$inp_counter, 
						monthly_last_updated='$datetime', monthly_last_updated_day=$day, monthly_last_updated_month=$month, monthly_last_updated_year=$year WHERE monthly_id=$get_monthly_id") or die(mysqli_error($link));
			}

			// --> yearly
			$query = "SELECT yearly_id, yearly_comments_written FROM $t_stats_comments_yearly WHERE yearly_year=$year";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_yearly_id, $get_yearly_comments_written) = $row;
			if($get_yearly_id == ""){
				mysqli_query($link, "INSERT INTO $t_stats_comments_yearly 
				(yearly_id, yearly_year, yearly_comments_written, yearly_last_updated, yearly_last_updated_day, yearly_last_updated_month, yearly_last_updated_year) 
				VALUES 
				(NULL, $year, 1, '$datetime', $day, $month, $year)")
				or die(mysqli_error($link));
			}
			else{
				$inp_counter = $get_yearly_comments_written+1;
				$result = mysqli_query($link, "UPDATE $t_stats_comments_yearly SET yearly_comments_written=$inp_counter, 
						yearly_last_updated='$datetime', yearly_last_updated_day=$day, yearly_last_updated_month=$month, yearly_last_updated_year=$year WHERE yearly_id=$get_yearly_id") or die(mysqli_error($link));
			}



echo"Comment saved";


?>