<?php 
/**
*
* File: forum/new_topic.php
* Version 1.0.0
* Date 12:05 10.02.2018
* Copyright (c) 2011-2018 S. A. Ditlefsen
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
include("$root/_admin/_data/forum.php");

/*- Functions ------------------------------------------------------------------------- */
include("$root/_admin/_functions/encode_national_letters.php");
include("$root/_admin/_functions/decode_national_letters.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/forum/ts_index.php");

/*- Forum config ------------------------------------------------------------------------ */
include("$root/_admin/_data/forum.php");
include("_include_tables.php");

/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";


/*- Variables ------------------------------------------------------------------------- */


$tabindex = 0;
$l_mysql = quote_smart($link, $l);




/*- Title ---------------------------------------------------------------------------------- */
$query_t = "SELECT title_id, title_language, title_value FROM $t_forum_titles WHERE title_language=$l_mysql";
$result_t = mysqli_query($link, $query_t);
$row_t = mysqli_fetch_row($result_t);
list($get_current_title_id, $get_current_title_language, $get_current_title_value) = $row_t;

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_new_topic - $get_current_title_value";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */
// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	// Get my user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;
	
	// Get my subscriptions
	$query = "SELECT es_id, es_on_off FROM $t_users_email_subscriptions WHERE es_user_id=$my_user_id_mysql AND es_type='forum_notify_on_replies'";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_es_id, $get_es_on_off) = $row;
	if($get_es_id == ""){
		mysqli_query($link, "INSERT INTO $t_users_email_subscriptions
		(es_id, es_user_id, es_type, es_on_off) 
		VALUES 
		(NULL, $my_user_id_mysql, 'forum_notify_on_replies', '1')")
		or die(mysqli_error($link));

		$get_es_on_off = "1";
	}
	


	if($process == "1"){
		// Title
		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

		if($inp_title == ""){
			$inp_title = $_POST['inp_text'];
			$inp_title = strip_tags($inp_title); // Remove HTML
			$inp_title = str_replace("\n", "", $inp_title);
			$inp_title = substr($inp_title, 0, 60);
			$inp_title = output_html($inp_title);
			$inp_title = str_replace("&amp;quot;", "&quot;", $inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);
		}


		// Text
		$inp_text = $_POST['inp_text'];

		// Tags
		$inp_tags = $_POST['inp_tags'];
		$inp_tags = output_html(strtolower($inp_tags));


		// Empty check
		if($inp_title == ""){
			$url = "new_topic.php?l=$l&ft=error&fm=insert_a_title&inp_text=$inp_text&inp_tags=$inp_tags";
			header("Location: $url");
			exit;
		}

		if($inp_text == ""){
			$url = "new_topic.php?l=$l&ft=error&fm=insert_some_text&inp_title=$inp_title&inp_tags=$inp_tags";
			header("Location: $url");
			exit;
		}

		// Rest	
		$datetime = date("Y-m-d H:i:s");
		$time = time();

		$inp_user_ip = $_SERVER['REMOTE_ADDR'];
		$inp_user_ip = output_html($inp_user_ip);
		$inp_user_ip_mysql = quote_smart($link, $inp_user_ip);
	
		$inp_topic_user_alias_mysql = quote_smart($link, $get_my_user_alias);

		// Get my photo
		$query = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_photo_id, $get_photo_destination) = $row;

		$inp_topic_user_image_mysql = quote_smart($link, $get_photo_destination);

		// Topic_updated_translated
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

		$inp_topic_updated_translated = "$day $month_saying $year";
		
		mysqli_query($link, "INSERT INTO $t_forum_topics
		(topic_id, topic_user_id, topic_user_alias, topic_user_image, topic_language, topic_title, topic_created, topic_created_time, topic_updated, topic_updated_time, topic_updated_translated, topic_last_replied, topic_last_replied_time, topic_replies, topic_views, topic_rating, topic_user_ip, topic_solved) 
		VALUES 
		(NULL, $my_user_id_mysql, $inp_topic_user_alias_mysql, $inp_topic_user_image_mysql, $l_mysql, $inp_title_mysql, '$datetime', '$time', '$datetime', '$time', '$inp_topic_updated_translated', '$datetime', '$time', '0', '0', '0', $inp_user_ip_mysql, '0')")
		or die(mysqli_error($link));


		// Get ID
		$query = "SELECT topic_id, topic_title FROM $t_forum_topics WHERE topic_user_id=$my_user_id_mysql AND topic_created='$datetime'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_topic_id, $get_topic_title) = $row;

		require_once "$root/_admin/_functions/htmlpurifier/HTMLPurifier.auto.php";
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);

	
		if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator" OR $get_my_user_rank == "editor"){
		}
		elseif($get_my_user_rank == "trusted"){
		}
		else{
			// a b c d e f g h i j k l m n o p q r s t u v w x y z
			// Updated: 19:16 26.04.2019
			// Files:
			// edit_reply.php, reply.php, edit_topic.php, new_topic.php
			$config->set('HTML.Allowed', 'a[href],b,code,img[src],i,ul,li,p,pre,pre[class]');
		}

		// Inp text
		$inp_text = $purifier->purify($inp_text);
		$inp_text = encode_national_letters($inp_text);
			
		$sql = "UPDATE $t_forum_topics SET topic_text=? WHERE topic_id=$get_topic_id";
		$stmt = $link->prepare($sql);
		$stmt->bind_param("s", $inp_text);
		$stmt->execute();
		if ($stmt->errno) {
			echo "FAILURE!!! " . $stmt->error; die;
		}

		// Subscription
		if(isset($_POST['inp_notify_me_when_a_reply_is_posted'])){

			$inp_notify_me_when_a_reply_is_posted = $_POST['inp_notify_me_when_a_reply_is_posted'];
			$inp_notify_me_when_a_reply_is_posted = output_html($inp_notify_me_when_a_reply_is_posted);


			if($inp_notify_me_when_a_reply_is_posted == "on"){

				$inp_email_mysql = quote_smart($link, $get_my_user_email);

				mysqli_query($link, "INSERT INTO $t_forum_topics_subscribers
				(topic_subscriber_id, topic_id, topic_subscriber_user_id, topic_subscriber_user_email) 
				VALUES 
				(NULL, '$get_topic_id', $my_user_id_mysql, $inp_email_mysql)")
				or die(mysqli_error($link));

			}
		}




		
		// Tags
		$inp_tags_array = explode(" ", $inp_tags);
		$size = sizeof($inp_tags_array);

		if($size > 0){
			for($x=0;$x<$size;$x++){
				$inp_tag_title = $inp_tags_array[$x];
				$inp_tag_title_mysql = quote_smart($link, $inp_tag_title);

				$inp_tag_clean = clean($inp_tags_array[$x]);
				$inp_tag_clean_mysql = quote_smart($link, $inp_tag_clean);

				if($inp_tag_title != ""){
					// Check if I have this tag from before
					$query = "SELECT topic_tag_id FROM $t_forum_topics_tags WHERE topic_id=$get_topic_id AND topic_tag_clean=$inp_tag_clean_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_topic_tag_id) = $row;
					if($get_topic_tag_id == ""){
						// Insert
						mysqli_query($link, "INSERT INTO $t_forum_topics_tags 
						(topic_tag_id, topic_id, topic_tag_title, topic_tag_clean) 
						VALUES 
						(NULL, $get_topic_id, $inp_tag_title_mysql, $inp_tag_clean_mysql)")
						or die(mysqli_error($link));
					}

					// Tag index
					$datetime = date("Y-m-d H:i:s");
					$day = date("d");
					$week = date("W");

					$query = "SELECT tag_id, tag_topics_total_counter, tag_topics_today_counter, tag_topics_today_day, tag_topics_this_week_counter, tag_topics_this_week_week FROM $t_forum_tags_index WHERE tag_title_clean=$inp_tag_clean_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_tag_id, $get_tag_topics_total_counter, $get_tag_topics_today_counter, $get_tag_topics_today_day, $get_tag_topics_this_week_counter, $get_tag_topics_this_week_week) = $row;
					if($get_tag_id == ""){
						// Insert
						mysqli_query($link, "INSERT INTO $t_forum_tags_index 
						(tag_id, tag_title, tag_title_clean, tag_introduction, tag_description, tag_created, tag_updated, tag_topics_total_counter, tag_topics_today_counter, tag_topics_today_day, tag_topics_this_week_counter, tag_topics_this_week_week, tag_is_official, tag_icon_path) 
						VALUES 
						(NULL, $inp_tag_title_mysql, $inp_tag_clean_mysql, '', '', '$datetime', '$datetime', '1', '1', '$day', '1', '$week', -1, '_uploads/forum/tags_icons')")
						or die(mysqli_error($link));

						// Get new Tag ID
						$query = "SELECT tag_id, tag_topics_total_counter, tag_topics_today_counter, tag_topics_today_day, tag_topics_this_week_counter, tag_topics_this_week_week FROM $t_forum_tags_index WHERE tag_title_clean=$inp_tag_clean_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_tag_id, $get_tag_topics_total_counter, $get_tag_topics_today_counter, $get_tag_topics_today_day, $get_tag_topics_this_week_counter, $get_tag_topics_this_week_week) = $row;
			

						// Email to moderators about new tag
						$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_rank='admin' OR user_rank='moderator'";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_mod_user_id, $get_mod_user_email, $get_mod_user_name, $get_mod_user_alias, $get_mod_user_rank) = $row;

							$subject = "$configWebsiteTitleSav - New tag $inp_tag_title";
							$message = "Hello $get_mod_user_name,\n\n";
							$message = $message . "There is a new tag $inp_tag_title.\n";
							$message = $message . "View: $configSiteURLSav/forum/open_tag.php?tag=$inp_tag_clean&l=$l\n";
							$message = $message . "Admin: $configControlPanelURLSav/index.php?open=forum&page=tags&action=open_tag&tag_id=$get_tag_id&editor_language=$l\n\n";
							$message = $message . "Best regards\n$configWebsiteTitleSav\n$configSiteURLSav";
							$headers = "From: $forumFromEmailSav" . "\r\n" .
						    "Reply-To: $forumFromEmailSav" . "\r\n" .
						    'X-Mailer: PHP/' . phpversion();

							if($forumEmailSendingOnOffSav == "on"){
								mail($get_mod_user_email, $subject, $message, $headers);
							}
						}

					}
					else{
						$inp_tag_topics_total_counter = $get_tag_topics_total_counter+1;

						if($get_tag_topics_today_day == "$day"){
							$inp_tag_topics_today_counter = $get_tag_topics_today_counter+1;
						}
							else{
							$inp_tag_topics_today_counter = 0;
						}

						if($get_tag_topics_this_week_week == "$week"){
							$inp_tag_topics_this_week_counter = $get_tag_topics_this_week_counter+1;
						}
						else{
							$inp_tag_topics_this_week_counter = $get_tag_topics_this_week_counter+1;
						}

						$r_update = mysqli_query($link, "UPDATE $t_forum_tags_index SET tag_topics_total_counter=$inp_tag_topics_total_counter, tag_topics_today_counter=$inp_tag_topics_today_counter, tag_topics_today_day=$day, tag_topics_this_week_counter=$inp_tag_topics_this_week_counter, tag_topics_this_week_week=$week WHERE tag_id=$get_tag_id");
					}

				} // tag not empty
			} // for size
		} // size > 0


		// Email to all board subscribers
		$query_w = "SELECT forum_subscription_id, forum_subscription_user_id, forum_subscription_user_email, forum_subscription_last_sendt_datetime, forum_subscription_last_sendt_time FROM $t_forum_subscriptions";
		$result_w = mysqli_query($link, $query_w);
		while($row_w = mysqli_fetch_row($result_w)) {
			list($get_forum_subscription_id, $get_forum_subscription_user_id, $get_forum_subscription_user_email, $get_forum_subscription_last_sendt_datetime, $get_forum_subscription_last_sendt_time) = $row_w;

			// Dont send email to myself
			if($get_forum_subscription_user_id != "$my_user_id"){


				// Links
				$view_link = $configSiteURLSav . "/forum/view_topic.php?topic_id=$get_topic_id";
				$unsubscribe_link = $configSiteURLSav . "/forum/unsubscribe_from_forum.php?subscription_id=$get_forum_subscription_id&subscription_user_id=$get_forum_subscription_user_id";
		
			
				// Topic title substr
				$title_len = strlen($inp_title);
				if($title_len < 80){
					$inp_title = substr($inp_title, 0, 75);
					$inp_title = $inp_title . "...";
				}

				// Subject
				$subject = "$configWebsiteTitleSav $get_current_title_value - $inp_title";


				// Avatar
				$inp_new_x = 40; // 950
				$inp_new_y = 40; // 640
				if(file_exists("$root/_uploads/users/images/$my_user_id/$get_photo_destination") && $get_photo_destination != ""){
				$thumb_full_path = "$root/_cache/user_" . $get_photo_destination . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
				if(!(file_exists("$thumb_full_path"))){
					resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$my_user_id/$get_photo_destination", "$thumb_full_path");
				}
				$thumb_full_path = "_cache/user_" . $get_photo_destination . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
				
				}
				else{
				$thumb_full_path = "forum/_gfx/avatar_blank_40.png";
				}




				$message = "<html>\n";
				$message = $message. "<head>\n";
				$message = $message. "  <title>$subject</title>\n";

				$message = $message. "  <style type=\"text/css\">
/*- Basic */
table{
	border-spacing:0px; 
}
td{
	padding: 0px;
}

/*- P, Links -*/
p{
	margin: 0;
	font: normal 16px 'Open Sans', sans-serif;
        color: #000;
	padding: 10px 0px 10px 0px;
	line-height: 1.6;
}
a {
	color: #375962;
	font: normal 16px 'Open Sans', sans-serif;
	text-decoration: none;
}
a:hover {
	color: #c1452b;
	text-decoration: none;
}

td.topics_chat_view_author_cell {
	width: 50px;
	margin-bottom: 10px;
	padding-top: 10px;
}
img.topics_chat_view_author_image {
	border-radius: 50%;
}
td.topics_chat_view_img_cell{
	width: 11px;
}
img.topics_chat_view_text_arrow_left{
	position: relative;
	top: 0;
	left: 1px;
}
div.topics_chat_view_text {
	background: #fff;
	padding: 15px 20px 10px 20px;
	border: 0 none;
	box-sizing: border-box;
	border: #dcdcdc 1px solid;
	border-radius: 5px;
	margin-bottom: 10px;
}

	</style>\n";
				$message = $message. " </head>\n";
				$message = $message. "<body>\n";



				$message = $message . "
				<table>
				 <tr>
				  <td class=\"topics_chat_view_author_cell\">
					<a href=\"$configSiteURLSav/users/view_profile.php?user_id=$my_user_id&amp;l=$l\"><img src=\"$configSiteURLSav/$thumb_full_path\" alt=\"$thumb_full_path\" class=\"topics_chat_view_author_image\" /></a>
				  </td>
				  <td class=\"topics_chat_view_img_cell\">
					<img src=\"$configSiteURLSav/forum/_gfx/last_topic_text_arrow_left.png\" alt=\"last_topic_text_arrow_left.png\" class=\"topics_chat_view_text_arrow_left\" />
				  </td>
				  <td class=\"topics_chat_view_text_cell\">
					<div class=\"topics_chat_view_text\">
						$inp_text
						";

						$size = sizeof($inp_tags_array);
						if($size > 0){
							for($x=0;$x<$size;$x++){
								$tag_title = $inp_tags_array[$x];
								$tag_clean = clean($inp_tags_array[$x]);

								$message = $message . "
								<a href=\"$configSiteURLSav/forum/open_tag.php?tag=$tag_clean&amp;l=$l\" class=\"last_topic_text_tag\">#$tag_title</a>
								";
							}
						}

						$message = $message . "
					</div>
				  </td>
				 </tr>
				</table>";
		
				$message = $message . "<p>View topic: <a href=\"$view_link\">$view_link</a></p>\n";
				$message = $message . "<hr />\n";
				$message = $message . "<p>Dont want any more emails? Then you can <a href=\"$unsubscribe_link\">unsubscribe</a>.</p>\n";

				$message = $message . "<p>\n\n--<br />\nBest regards<br />\n$configWebsiteTitleSav<br />\n$configSiteURLSav</p>\n";
				$message = $message. "</body>\n";
				$message = $message. "</html>\n";




				// Send mail
				$headers = "MIME-Version: 1.0" . "\r\n" .
				    "Content-type: text/html; charset=iso-8859-1" . "\r\n" .
				    "To: $get_forum_subscription_user_email" . "\r\n" .
				    "Reply-To: $forumFromEmailSav" . "\r\n" .
				    "From: $forumFromEmailSav" . "\r\n" .
				    'X-Mailer: PHP/' . phpversion();

				if($forumEmailSendingOnOffSav == "on"){
					mail($get_forum_subscription_user_email, $subject, $message, $headers);
				}
			} // dont send email to myself
		} // emails

		// Top users monthly
		$year = date("Y");
		$month = date("m");
		$inp_my_user_alias_mysql = quote_smart($link, $get_my_user_alias);
		$query = "SELECT top_monthly_id, top_monthly_user_id, top_monthly_topics, top_monthly_replies, top_monthly_times_voted, top_monthly_year, top_monthly_month, top_monthly_points FROM $t_forum_top_users_monthly WHERE top_monthly_user_id=$my_user_id_mysql AND top_monthly_year=$year AND top_monthly_month=$month";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_top_monthly_id, $get_top_monthly_user_id, $get_top_monthly_topics, $get_top_monthly_replies, $get_top_monthly_times_voted, $get_top_monthly_year, $get_top_monthly_month, $get_top_monthly_points) = $row;
		if($get_top_monthly_id == ""){
			// First time I posted this month
			mysqli_query($link, "INSERT INTO $t_forum_top_users_monthly 
			(top_monthly_id, top_monthly_user_id, top_monthly_topics, top_monthly_replies, top_monthly_times_voted, top_monthly_year, top_monthly_month, top_monthly_points, top_monthly_user_alias, top_monthly_user_image) 
			VALUES 
			(NULL, $my_user_id_mysql, '1', '0', '0', '$year', '$month', 10, $inp_my_user_alias_mysql, $inp_topic_user_image_mysql)")
			or die(mysqli_error($link));
		}
		else{
			$inp_top_monthly_topics = $get_top_monthly_topics + 1;
			$inp_top_monthly_points = $get_top_monthly_points + 10;

			$result = mysqli_query($link, "UPDATE $t_forum_top_users_monthly SET top_monthly_topics=$inp_top_monthly_topics, top_monthly_points=$inp_top_monthly_points, top_monthly_user_alias=$inp_my_user_alias_mysql, top_monthly_user_image=$inp_topic_user_image_mysql WHERE top_monthly_id='$get_top_monthly_id'");

		}

		// Top users yearly
		$query = "SELECT top_yearly_id, top_yearly_user_id, top_yearly_topics, top_yearly_replies, top_yearly_times_voted, top_yearly_year, top_yearly_points FROM $t_forum_top_users_yearly WHERE top_yearly_user_id=$my_user_id_mysql AND top_yearly_year=$year";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_top_yearly_id, $get_top_yearly_user_id, $get_top_yearly_topics, $get_top_yearly_replies, $get_top_yearly_times_voted, $get_top_yearly_year, $get_top_yearly_points) = $row;
		if($get_top_yearly_id == ""){
			// First time I posted this month
			mysqli_query($link, "INSERT INTO $t_forum_top_users_yearly 
			(top_yearly_id, top_yearly_user_id, top_yearly_topics, top_yearly_replies, top_yearly_times_voted, top_yearly_year, top_yearly_points, top_yearly_user_alias, top_yearly_user_image) 
			VALUES 
			(NULL, $my_user_id_mysql, '1', '0', '0', '$year', 10, $inp_my_user_alias_mysql, $inp_topic_user_image_mysql)")
			or die(mysqli_error($link));
		}
		else{
			$inp_top_yearly_topics = $get_top_yearly_topics + 1;
			$inp_top_yearly_points = $get_top_yearly_points + 10;

			$result = mysqli_query($link, "UPDATE $t_forum_top_users_yearly SET top_yearly_topics=$inp_top_yearly_topics, top_yearly_points=$inp_top_yearly_points, top_yearly_user_alias=$inp_my_user_alias_mysql, top_yearly_user_image=$inp_topic_user_image_mysql WHERE top_yearly_id='$get_top_yearly_id'") or die(mysqli_error($link));
		}


		// Top users all time
		$query = "SELECT top_all_id, top_all_user_id, top_all_topics, top_all_replies, top_all_times_voted, top_all_points FROM $t_forum_top_users_all_time WHERE top_all_user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_top_all_id, $get_top_all_user_id, $get_top_all_topics, $get_top_all_replies, $get_top_all_times_voted, $get_top_all_points) = $row;
		if($get_top_all_id == ""){
			// First time I posted at all
			mysqli_query($link, "INSERT INTO $t_forum_top_users_all_time
			(top_all_id, top_all_user_id, top_all_topics, top_all_replies, top_all_times_voted, top_all_points, top_all_user_alias, top_all_user_image) 
			VALUES 
			(NULL, $my_user_id_mysql, '1', '0', '0',  10, $inp_my_user_alias_mysql, $inp_topic_user_image_mysql)")
			or die(mysqli_error($link));
		}
		else{
			$inp_top_all_topics = $get_top_all_topics + 1;
			$inp_top_all_points = $get_top_all_points + 10;

			$result = mysqli_query($link, "UPDATE $t_forum_top_users_all_time SET top_all_topics=$inp_top_all_topics, top_all_points=$inp_top_all_points, top_all_user_alias=$inp_my_user_alias_mysql, top_all_user_image=$inp_topic_user_image_mysql WHERE top_all_id='$get_top_all_id'");

		}


		// Search engine
		$inp_index_title = "$inp_title | $get_current_title_value";
		$inp_index_title_mysql = quote_smart($link, $inp_index_title);

		$inp_index_url = "forum/view_topic.php?topic_id=$get_topic_id";
		$inp_index_url_mysql = quote_smart($link, $inp_index_url);

		$inp_index_short_description = substr($inp_text, 0, 200);
		$inp_index_short_description = output_html($inp_index_short_description);
		$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

		$inp_index_keywords = "$inp_tags";
		$inp_index_keywords_mysql = quote_smart($link, $inp_index_keywords);
		
		
		$datetime = date("Y-m-d H:i:s");
		$datetime_saying = date("j. M Y H:i");

		$inp_index_language_mysql = quote_smart($link, $l);

		mysqli_query($link, "INSERT INTO $t_search_engine_index 
		(index_id, index_title, index_url, index_short_description, index_keywords, 
		index_module_name, index_reference_name, index_reference_id, index_is_ad, index_created_datetime, index_created_datetime_print, 
		index_language) 
		VALUES 
		(NULL, $inp_index_title_mysql, $inp_index_url_mysql, $inp_index_short_description_mysql, $inp_index_keywords_mysql, 
		'forum', 'topic_id','$get_topic_id', 0, '$datetime', '$datetime_saying', $inp_index_language_mysql)")
		or die(mysqli_error($link));



		// Feed
		$inp_feed_category_name_mysql = quote_smart($link, "");


		// Feed title
		$inp_feed_title = "$get_topic_title";
		$inp_feed_title_mysql = quote_smart($link, $inp_feed_title);

		// Feed text
		$inp_feed_text = substr($inp_text, 0, 200);
		$inp_feed_text = output_html($inp_feed_text);
		$inp_feed_text = str_replace("&lt;p&gt;", "", $inp_feed_text);
		$inp_feed_text = str_replace("&lt;/p&gt;", "", $inp_feed_text);
		$inp_feed_text_mysql = quote_smart($link, $inp_feed_text);

		// Feed image path
		$inp_feed_image_path_mysql = quote_smart($link, "");

		// Feed image file
		$inp_feed_image_file_mysql = quote_smart($link, "");

		// Feed image thumb 300x169
		$inp_feed_image_thumb_a_mysql = quote_smart($link, "");

		// Feed image thumb 540x304
		$inp_feed_image_thumb_b_mysql = quote_smart($link, "");

		// Feed link URL
		$inp_feed_link_url = "forum/view_topic.php?topic_id=$get_topic_id&amp;l=$l";
		$inp_feed_link_url_mysql = quote_smart($link, $inp_feed_link_url);

		// Feed link name
		$inp_feed_link_name = "$l_view_topic";
		$inp_feed_link_name_mysql = quote_smart($link, $inp_feed_link_name);


		// Get current user
		// Already fetched

		// Author image
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
					
		$year = date("Y");
		$date_saying = date("j M Y");

		// Check if exists
		$query = "SELECT feed_id FROM $t_users_feeds_index WHERE feed_module_name='forum' AND feed_module_part_name='topic' AND feed_module_part_id=$get_topic_id AND feed_user_id=$get_my_user_id";
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
			$inp_feed_image_thumb_a_mysql, $inp_feed_image_thumb_b_mysql, $inp_feed_link_url_mysql, $inp_feed_link_name_mysql, 'forum', 
			'topic', $get_topic_id, 0, $inp_feed_category_name_mysql, 
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
		



		// Header
		$url = "view_topic.php?topic_id=$get_topic_id&l=$l";
		header("Location: $url");
		exit;

	}
	echo"
	<h1>$l_new_topic</h1>
		
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


	<!-- TinyMCE -->
		<script type=\"text/javascript\" src=\"$root/_admin/_javascripts/tinymce/tinymce.min.js\"></script>
		<script>
		tinymce.init({
			selector: 'textarea.editor',
			plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern help',
			toolbar: 'formatselect | bold italic strikethrough forecolor backcolor permanentpen formatpainter | link image media pageembed | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment',
			image_advtab: true,
			content_css: [
				'$root/_admin/_javascripts/tinymce_includes/fonts/lato/lato_300_300i_400_400i.css',
				'$root/_admin/_javascripts/tinymce_includes/codepen.min.css'
			],
			link_list: [
				{ title: 'My page 1', value: 'http://www.tinymce.com' },
				{ title: 'My page 2', value: 'http://www.moxiecode.com' }
			],
			image_list: [
				{ title: 'My page 1', value: 'http://www.tinymce.com' },
				{ title: 'My page 2', value: 'http://www.moxiecode.com' }
			],
				image_class_list: [
				{ title: 'None', value: '' },
				{ title: 'Some class', value: 'class-name' }
			],
			importcss_append: true,
			height: 500,
			file_picker_callback: function (callback, value, meta) {
				/* Provide file and text for the link dialog */
				if (meta.filetype === 'file') {
					callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
				}
				/* Provide image and alt text for the image dialog */
				if (meta.filetype === 'image') {
					callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
				}
				/* Provide alternative source and posted for the media dialog */
				if (meta.filetype === 'media') {
					callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
				}
			}
		});
		</script>
	<!-- //TinyMCE -->
	
	<!-- Form -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_title\"]').focus();
		});
		</script>
	
		<form method=\"post\" action=\"new_topic.php?l=$l&amp;process=1\" enctype=\"multipart/form-data\">

		<p>
		<a href=\"new_topic_image_uploader.php?l=$l\" target=\"_blank\"><img src=\"_gfx/image-x-generic.png\" alt=\"image-x-generic.png\" /></a>
		<a href=\"new_topic_image_uploader.php?l=$l\" target=\"_blank\">$l_upload_image</a>
		</p>

		<p><b>$l_title:</b><br />
		<input type=\"text\" name=\"inp_title\" value=\"";
		if(isset($_GET['inp_title'])){
			$inp_title = $_GET['inp_title'];
			$inp_title = output_html($inp_title);
			echo"$inp_title";
		}
		echo"\" size=\"25\" style=\"width: 99%;\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>$l_tags</b><br />
		<input type=\"text\" name=\"inp_tags\" value=\"";
		if(isset($_GET['inp_tags'])){
			$inp_tags = $_GET['inp_tags'];
			$inp_tags = output_html($inp_tags);
			echo"$inp_tags";
		}
		echo"\" size=\"25\" style=\"width: 99%;\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>$l_post:</b><br />
		<textarea name=\"inp_text\" rows=\"5\" cols=\"50\" class=\"editor\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
		if(isset($_GET['inp_text'])){
			$inp_text = $_GET['inp_text'];
			$inp_text = output_html($inp_text);
			echo"$inp_text";
		}
		echo"</textarea>
		</p>
		
		<p>
		<input type=\"checkbox\" name=\"inp_notify_me_when_a_reply_is_posted\" "; if($get_es_on_off == "1"){ echo" checked=\"checked\""; } echo"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> $l_notify_me_when_a_reply_is_posted
		</p>

		<p><input type=\"submit\" value=\"$l_publish\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
		</form>
		<!-- //Form -->
	";
	
}
else{
	echo"
	<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" /> Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=forum/new_topic.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>