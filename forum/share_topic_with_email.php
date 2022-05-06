<?php 
/**
*
* File: forum/share_topic_with_email.php
* Version 1.0.0
* Date 22:43 08.03.2019
* Copyright (c) 2019 S. A. Ditlefsen
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
include("$root/_admin/_functions/decode_national_letters.php");


/*- Forum config ------------------------------------------------------------------------ */
include("$root/_admin/_data/forum.php");
include("_include_tables.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/forum/ts_index.php");

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


/*- Title ---------------------------------------------------------------------------------- */
$query_t = "SELECT title_id, title_language, title_value FROM $t_forum_titles WHERE title_language=$l_mysql";
$result_t = mysqli_query($link, $query_t);
$row_t = mysqli_fetch_row($result_t);
list($get_current_title_id, $get_current_title_language, $get_current_title_value) = $row_t;


// Get topic
$topic_id_mysql = quote_smart($link, $topic_id);
$query = "SELECT topic_id, topic_user_id, topic_user_alias, topic_user_image, topic_language, topic_title, topic_text, topic_created, topic_created_time, topic_updated, topic_updated_time, topic_updated_translated, topic_replies, topic_views, topic_views_ip_block, topic_likes, topic_dislikes, topic_rating, topic_likes_ip_block, topic_user_ip, topic_solved, topic_tag_a, topic_tag_b, topic_tag_c FROM $t_forum_topics WHERE topic_id=$topic_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_topic_id, $get_current_topic_user_id, $get_current_topic_user_alias, $get_current_topic_user_image, $get_current_topic_language, $get_current_topic_title, $get_current_topic_text, $get_current_topic_created, $get_current_topic_created_time, $get_current_topic_updated, $get_current_topic_updated_time, $get_current_topic_updated_translated, $get_current_topic_replies, $get_current_topic_views, $get_current_topic_views_ip_block, $get_current_topic_likes, $get_current_topic_dislikes, $get_current_topic_rating, $get_current_topic_likes_ip_block, $get_current_topic_user_ip, $get_current_topic_solved, $get_current_topic_tag_a, $get_current_topic_tag_b, $get_current_topic_tag_c) = $row;

if($get_current_topic_id == ""){

	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "Server error 404 - $get_current_title_value";
	include("$root/_webdesign/header.php");
	echo"<p>Blog post not found.</p>";
	
}
else{
	// Topic title substr
	$get_current_topic_title_len = strlen($get_current_topic_title);
	if($get_current_topic_title_len > 80){
		$get_current_topic_title = substr($get_current_topic_title, 0, 75);
		$get_current_topic_title = $get_current_topic_title . "...";
	}

	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$l_share_with_email - $get_current_topic_title - $get_current_title_value";
	include("$root/_webdesign/header.php");
		
	// My ip
	$my_ip = $_SERVER['REMOTE_ADDR'];
	$my_ip = output_html($my_ip);
	$my_ip_mysql = quote_smart($link, $my_ip);


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

		
		if($process == "1"){
			$inp_to_email = $_POST['inp_to_email'];
			$inp_to_email = output_html($inp_to_email);

			$inp_message = $_POST['inp_message'];
			$inp_message = output_html($inp_message);
			$inp_message = str_replace("<br />", "\n", $inp_message);

			if(empty($inp_to_email)){
				$url = "share_topic_with_email.php?topic_id=$topic_id&l=$l&ft=error&fm=missing_email&inp_message=$inp_message";
				header("Location: $url");
				exit;
			}


			$datetime = date("Y-m-d H:i:s");

			$inp_user_ip = $_SERVER['REMOTE_ADDR'];
			$inp_user_ip = output_html($inp_user_ip);


			$subject = "$l_topic_shared_with_you - $l_from $get_my_user_alias";
			$subject = decode_national_letters($subject);

			$message = "$l_hello\n\n\n";
			$message = $message . "$get_my_user_alias $l_has_shared_the_following_topic_with_you_lowercase:\n";
			$message = $message . "$get_current_topic_title\n\n";
			$message = $message . "$l_you_can_read_it_here:\n";
			$message = $message . "$configSiteURLSav/forum/view_topic.php?topic_id=$get_current_topic_id&l=$l\n\n";
			$message = $message . "$l_message_from $get_my_user_alias:\n";
			$message = $message . "$inp_message\n\n";
			$message = $message . "--\n";
			$message = $message . "Regards\n";
			$message = $message . "$forumFromNameSav\n";
			$message = $message . "E-mail: $forumFromEmailSav\n";
			$message = $message . "Ip: $inp_user_ip\n";
			$message = $message . "Datetime: $datetime";
			$message = decode_national_letters($message);

			$headers = "From: $forumFromEmailSav" . "\r\n" .
			    "Reply-To: $forumFromEmailSav" . "\r\n" .
			    'X-Mailer: PHP/' . phpversion();

			mail($inp_to_email, $subject, $message, $headers);			


			$url = "view_topic.php?topic_id=$topic_id&l=$l&ft=success&fm=topic_shared&inp_to_email=$inp_to_email";
			header("Location: $url");
			exit;

		} // process


		echo"
		<h1>$get_current_topic_title</h1>

		<!-- Where am I ? -->
			<p><b>$l_you_are_here</b><br />
			<a href=\"index.php?l=$l\">$get_current_title_value</a>
			&gt;
			<a href=\"view_topic.php?topic_id=$topic_id&amp;l=$l\">$get_current_topic_title</a>
			&gt;
			<a href=\"share_topic_with_email.php?topic_id=$topic_id&amp;l=$l\">$l_share_with_email</a>
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


		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_to_email\"]').focus();
		});
		</script>
	
		<form method=\"post\" action=\"share_topic_with_email.php?topic_id=$topic_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

		<p>$l_to_email<br />
		<input type=\"text\" name=\"inp_to_email\" value=\"\" size=\"25\" />
		</p>

		<p>$l_message<br />
		<textarea name=\"inp_message\" rows=\"4\" cols=\"50\"></textarea>
		</p>

		<p>
		<input type=\"submit\" value=\"$l_send\" class=\"btn_default\" />
		</p>

		";
	} // logged in
	else{
		echo"
		<h1>
		<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
		Loading...</h1>
		<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?referer=forum/share_topic_with_email.php?topic_id=$topic_id&amp;l=$l\">
		";
	} // not logged in		
} //  post found



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>