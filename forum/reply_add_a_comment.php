<?php 
/**
*
* File: forum/reply_add_a_comment.php
* Version 1.0.0
* Date 09:28 25.04.2019
* Copyright (c) 2011-2019 S. A. Ditlefsen
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

/*- Forum config ------------------------------------------------------------------------ */
include("$root/_admin/_data/forum.php");
include("_include_tables.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/forum/ts_index.php");
include("$root/_admin/_translations/site/$l/forum/ts_new_topic.php");

/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);




/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['topic_id'])){
	$topic_id = $_GET['topic_id'];
	$topic_id = output_html($topic_id);
}
else{
	$topic_id = "";
}
if(isset($_GET['reply_id'])){
	$reply_id = $_GET['reply_id'];
	$reply_id = output_html($reply_id);
}
else{
	$reply_id = "";
}
if(isset($_GET['show'])) {
	$show = $_GET['show'];
	$show = strip_tags(stripslashes($show));
}
else{
	$show = "";
}


// Get topic
if($topic_id == "" && $reply_id != ""){
	// Can we search with reply id?
	$reply_id_mysql = quote_smart($link, $reply_id);
	$query = "SELECT reply_id, reply_topic_id FROM $t_forum_replies WHERE reply_id=$reply_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_reply_id, $topic_id) = $row;
}
$topic_id_mysql = quote_smart($link, $topic_id);
$query = "SELECT topic_id, topic_user_id, topic_user_alias, topic_user_image, topic_language, topic_title, topic_text, topic_created, topic_updated, topic_updated_translated, topic_replies, topic_views, topic_views_ip_block, topic_likes, topic_dislikes, topic_rating, topic_likes_ip_block, topic_user_ip, topic_reported, topic_reported_by_user_id, topic_reported_reason, topic_reported_checked FROM $t_forum_topics WHERE topic_id=$topic_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_topic_id, $get_current_topic_user_id, $get_current_topic_user_alias, $get_current_topic_user_image, $get_current_topic_language, $get_current_topic_title, $get_current_topic_text, $get_current_topic_created, $get_current_topic_updated, $get_current_topic_updated_translated, $get_current_topic_replies, $get_current_topic_views, $get_current_topic_views_ip_block, $get_current_topic_likes, $get_current_topic_dislikes, $get_current_topic_rating, $get_current_topic_likes_ip_block, $get_current_topic_user_ip, $get_current_topic_reported, $get_current_topic_reported_by_user_id, $get_current_topic_reported_reason, $get_current_topic_reported_checked) = $row;

if($get_current_topic_id == ""){
	echo"<p>Topic post not found.</p>";
	
}
else{
	// Get reply
	$reply_id_mysql = quote_smart($link, $reply_id);
	$query = "SELECT reply_id, reply_user_id, reply_user_alias, reply_user_image, reply_topic_id, reply_text, reply_created, reply_updated, reply_updated_translated, reply_selected_answer, reply_likes, reply_dislikes, reply_rating, reply_likes_ip_block, reply_user_ip, reply_reported, reply_reported_by_user_id, reply_reported_reason, reply_reported_checked FROM $t_forum_replies WHERE reply_id=$reply_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_reply_id, $get_current_reply_user_id, $get_current_reply_user_alias, $get_current_reply_user_image, $get_current_reply_topic_id, $get_current_reply_text, $get_current_reply_created, $get_current_reply_updated, $get_current_reply_updated_translated, $get_current_reply_selected_answer, $get_current_reply_likes, $get_current_reply_dislikes, $get_current_reply_rating, $get_current_reply_likes_ip_block, $get_current_reply_user_ip, $get_current_reply_reported, $get_current_reply_reported_by_user_id, $get_current_reply_reported_reason, $get_current_reply_reported_checked) = $row;

	if($get_current_reply_id == ""){
		echo"<p>Reply not found.</p>";
	}
	else{
		/*- Headers ---------------------------------------------------------------------------------- */
		$website_title = "$l_add_a_comment - $get_current_topic_title - $l_forum";
		include("$root/_webdesign/header.php");
		
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
				$inp_text = $_POST['inp_text'];
				$inp_text = output_html($inp_text);
				$inp_text_mysql = quote_smart($link, $inp_text);
				if(empty($inp_text)){
					$url = "reply_add_a_comment.php?topic_id=$topic_id&reply_id=$reply_id&l=$l&ft=error&fm=add_some_text";
					header("Location: $url");
					exit;
				}


				// Rest	
				$datetime = date("Y-m-d H:i:s");

				$inp_my_ip = $_SERVER['REMOTE_ADDR'];
				$inp_my_ip = output_html($inp_my_ip);
				$inp_my_ip_mysql = quote_smart($link, $inp_my_ip);
	
				$inp_my_user_alias_mysql = quote_smart($link, $get_my_user_alias);


				// Get my photo
				$query = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_photo_id, $get_photo_destination) = $row;

				$inp_my_user_image_mysql = quote_smart($link, $get_photo_destination);
	
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

				$inp_updated_translated = "$day $month_saying $year";


				// Insert comment
				mysqli_query($link, "INSERT INTO $t_forum_replies_comments
				(reply_comment_id, reply_comment_user_id, reply_comment_user_alias, reply_comment_user_image, reply_comment_topic_id, reply_comment_reply_id, reply_comment_text, reply_comment_created, reply_comment_updated, reply_comment_updated_translated, reply_comment_likes, reply_comment_dislikes, reply_comment_rating, reply_comment_user_ip) 
				VALUES 
				(NULL, $my_user_id_mysql, $inp_my_user_alias_mysql, $inp_my_user_image_mysql, $get_current_topic_id, $get_current_reply_id, $inp_text_mysql, '$datetime', '$datetime', '$inp_updated_translated', '0', '0', '0', $inp_my_ip_mysql)")
				or die(mysqli_error($link));

				// Get ID
				$query = "SELECT reply_comment_id FROM $t_forum_replies_comments WHERE reply_comment_user_id=$my_user_id_mysql AND reply_comment_created='$datetime'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_reply_comment_id) = $row;

				
				// Send mail
				$query_w = "SELECT * FROM $t_forum_topics_subscribers WHERE topic_id='$get_current_topic_id'";
				$result_w = mysqli_query($link, $query_w);
				while($row_w = mysqli_fetch_row($result_w)) {
					list($get_topic_subscriber_id, $get_topic_id, $get_topic_subscriber_user_id, $get_topic_subscriber_user_email) = $row_w;

					// Mail
					$topic_link = $configSiteURLSav . "/forum/view_topic.php?topic_id=$get_current_topic_id";
					$comment_link = $configSiteURLSav . "/forum/view_topic.php?topic_id=$get_current_topic_id#replycomment$get_current_reply_comment_id";
					$unsubscribe_link = $configSiteURLSav . "/forum/unsubscribe_from_topic.php?topic_subscriber_id=$get_topic_subscriber_id&topic_id=$get_current_topic_id";
			
					$user_agent = $_SERVER['HTTP_USER_AGENT'];
					$user_agent = output_html($user_agent);

					$subject = "New comment to reply from $get_current_reply_user_alias for $get_current_topic_title at $configWebsiteTitleSav";

					$message = "<html>\n";
					$message = $message. "<head>\n";
					$message = $message. "  <title>$subject</title>\n";
					$message = $message. " </head>\n";
					$message = $message. "<body>\n";

					$message = $message . "<p><b>New comment:</b><br />\n\n";
					$message = $message . "$inp_text</p>\n\n";


					$message = $message . "<p><b>Links</b><br />\n\n";
					$message = $message . "Topic: <a href=\"$topic_link\">$topic_link</a><br />\n\n";
					$message = $message . "Comment: <a href=\"$comment_link\">$comment_link</a></p>\n\n";
					$message = $message . "<p>\n\n--<br />\nBest regards<br />\n$forumFromNameSav<br />\nE-mail: $forumFromEmailSav<br />\n";
					$message = $message . "Web: $configSiteURLSav</p>";
					$message = $message . "<p style=\"font-size: 80%;\">Dont want any more emails? You can unsubscribe by following this link:\n";
					$message = $message . "<a href=\"$unsubscribe_link\">$unsubscribe_link</a></p>\n\n";
					$message = $message. "</body>\n";
					$message = $message. "</html>\n";

					// Send mail
					$headers = "MIME-Version: 1.0" . "\r\n" .
				   	    "Content-type: text/html; charset=iso-8859-1" . "\r\n" .
				   	    "To: $get_topic_subscriber_user_email" . "\r\n" .
					    "Reply-To: $forumFromEmailSav" . "\r\n" .
					    "From: $forumFromEmailSav" . "\r\n" .
					    "Reply-To: $forumFromEmailSav" . "\r\n" .
					    'X-Mailer: PHP/' . phpversion();

					if($get_topic_subscriber_user_email != "$get_my_user_email"){
						mail($get_topic_subscriber_user_email, $subject, $message, $headers);
					}

	
				}


				
	
				$url = "view_topic.php?topic_id=$topic_id&l=$l&ft=success&fm=comment_saved#reply$get_current_reply_id";
				header("Location: $url");
				exit;

			}
			echo"
			<h1>$get_current_topic_title</h1>


			<!-- Where am I ? -->
					<p><b>$l_you_are_here</b><br />";
					if($show == "popular"){
						echo"<a href=\"index.php?show=$show&amp;l=$l\">$l_popular</a>";
					}
					elseif($show == "unanswered"){
						echo"<a href=\"index.php?show=$show&amp;l=$l\">$l_unanswered</a>";
					}
					elseif($show == "active"){
						echo"<a href=\"index.php?show=$show&amp;l=$l\">$l_active</a>";
					}
					else{
						echo"<a href=\"index.php?l=$l\">$l_forum</a>";
					}
					echo"
					&gt;
					<a href=\"view_topic.php?topic_id=$topic_id&amp;l=$l\">$get_current_topic_title</a>
					&gt;
					<a href=\"view_topic.php?topic_id=$topic_id&amp;l=$l#reply$get_current_reply_id\">$l_reply_by $get_current_reply_user_alias</a>
					&gt;
					<a href=\"report_reply.php?topic_id=$topic_id&amp;reply_id=$reply_id&amp;l=$l\">$l_add_a_comment</a>
					</p>
			<!-- //Where am I ? -->

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


			<!-- Form -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_text\"]').focus();
				});
				</script>
			
				<form method=\"post\" action=\"reply_add_a_comment.php?topic_id=$topic_id&amp;reply_id=$reply_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
					
		
				<p><b>$l_comment:</b><br />
				<textarea name=\"inp_text\" rows=\"5\" cols=\"50\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea>
				</p>
		

				<p><input type=\"submit\" value=\"$l_add_comment\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
				</form>
			<!-- //Form -->
			";
			
		}
		else{
			echo"
			<h1>
			<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
			Loading...</h1>
			<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/forum/report_reply.php?topic_id=$topic_id&amp;reply_id=$reply_id\">
			";
		}
	} // reply found
} //  post found



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>