<?php 
/**
*
* File: forum/reply.php
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
if(isset($_GET['show'])) {
	$show = $_GET['show'];
	$show = strip_tags(stripslashes($show));
}
else{
	$show = "";
}

/*- Title ---------------------------------------------------------------------------------- */
$query_t = "SELECT title_id, title_language, title_value FROM $t_forum_titles WHERE title_language=$l_mysql";
$result_t = mysqli_query($link, $query_t);
$row_t = mysqli_fetch_row($result_t);
list($get_current_title_id, $get_current_title_language, $get_current_title_value) = $row_t;



// Get topic
$topic_id_mysql = quote_smart($link, $topic_id);
$query = "SELECT topic_id, topic_user_id, topic_user_alias, topic_user_image, topic_language, topic_title, topic_text, topic_created, topic_updated, topic_updated_translated, topic_replies, topic_views, topic_views_ip_block, topic_likes, topic_dislikes, topic_rating, topic_likes_ip_block, topic_user_ip FROM $t_forum_topics WHERE topic_id=$topic_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_topic_id, $get_current_topic_user_id, $get_current_topic_user_alias, $get_current_topic_user_image, $get_current_topic_language, $get_current_topic_title, $get_current_topic_text, $get_current_topic_created, $get_current_topic_updated, $get_current_topic_updated_translated, $get_current_topic_replies, $get_current_topic_views, $get_current_topic_views_ip_block, $get_current_topic_likes, $get_current_topic_dislikes, $get_current_topic_rating, $get_current_topic_likes_ip_block, $get_current_topic_user_ip) = $row;

if($get_current_topic_id == ""){
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "Server error 404 - $get_current_title_value";
	include("$root/_webdesign/header.php");
	echo"<p>Topic not found.</p>";
	
}
else{
	
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$get_current_topic_title - $get_current_title_value";
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
	
			// Text
			$inp_text = $_POST['inp_text'];
			if(empty($inp_text)){
				$url = "reply.php?topic_id=$topic_id&l=$l&ft=error&fm=insert_some_text#answer_form";
				header("Location: $url");
				exit;
			}

			// Rest	
			$datetime = date("Y-m-d H:i:s");

			$inp_user_ip = $_SERVER['REMOTE_ADDR'];
			$inp_user_ip = output_html($inp_user_ip);
			$inp_user_ip_mysql = quote_smart($link, $inp_user_ip);
	
			$inp_reply_user_alias_mysql = quote_smart($link, $get_my_user_alias);


			// Get my photo
			$query = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_photo_id, $get_photo_destination) = $row;

			$inp_reply_user_image_mysql = quote_smart($link, $get_photo_destination);
	
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

			$inp_reply_updated_translated = "$day $month_saying $year";
		
			mysqli_query($link, "INSERT INTO $t_forum_replies
			(reply_id, reply_user_id, reply_user_alias, reply_user_image, reply_topic_id, reply_created, reply_updated, reply_updated_translated, reply_selected_answer, reply_likes, reply_dislikes, reply_rating, reply_user_ip) 
			VALUES 
			(NULL, $my_user_id_mysql, $inp_reply_user_alias_mysql, $inp_reply_user_image_mysql, $get_current_topic_id, '$datetime', '$datetime', '$inp_reply_updated_translated', '0', '0', '0', '0', $inp_user_ip_mysql)")
			or die(mysqli_error($link));


			// Get reply ID
			$query = "SELECT reply_id FROM $t_forum_replies WHERE reply_user_id=$my_user_id_mysql AND reply_created='$datetime'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_reply_id) = $row;

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
			
			$sql = "UPDATE $t_forum_replies SET reply_text=? WHERE reply_id=$get_reply_id";
			$stmt = $link->prepare($sql);
			$stmt->bind_param("s", $inp_text);
			$stmt->execute();
			if ($stmt->errno) {
				echo "FAILURE!!! " . $stmt->error; die;
			}
	


			// E-mail subscribers
			$query_w = "SELECT * FROM $t_forum_topics_subscribers WHERE topic_id='$get_current_topic_id'";
			$result_w = mysqli_query($link, $query_w);
			while($row_w = mysqli_fetch_row($result_w)) {
				list($get_topic_subscriber_id, $get_topic_id, $get_topic_subscriber_user_id, $get_topic_subscriber_user_email) = $row_w;

				// Mail
				$view_link = $configSiteURLSav . "/forum/view_topic.php?topic_id=$get_current_topic_id";
				$unsubscribe_link = $configSiteURLSav . "/forum/unsubscribe_from_topic.php?topic_subscriber_id=$get_topic_subscriber_id&topic_id=$get_current_topic_id";
			
				$user_agent = $_SERVER['HTTP_USER_AGENT'];
				$user_agent = output_html($user_agent);

				$subject = "New reply for $get_current_topic_title at $configWebsiteTitleSav";

				$message = "<html>\n";
				$message = $message. "<head>\n";
				$message = $message. "  <title>$subject</title>\n";
				$message = $message. " </head>\n";
				$message = $message. "<body>\n";

				$message = $message . "<span><b>$get_current_topic_title</b></span>\n\n";
				$message = $message . "$get_current_topic_text\n\n";


				$message = $message . "<span><b>New reply</b></span>\n\n";
				$message = $message . "$inp_text\n\n";


				$message = $message . "<p><b>Links</b><br />\n\n";
				$message = $message . "View the topic by visiting the URL <a href=\"$view_link\">$view_link</a></p>\n\n";
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
		
				if($forumEmailSendingOnOffSav == "on"){
					mail($get_topic_subscriber_user_email, $subject, $message, $headers);
				}


			}


			// Subscription
			if(isset($_POST['inp_notify_me_when_a_reply_is_posted'])){

			$inp_notify_me_when_a_reply_is_posted = $_POST['inp_notify_me_when_a_reply_is_posted'];
			$inp_notify_me_when_a_reply_is_posted = output_html($inp_notify_me_when_a_reply_is_posted);


			if($inp_notify_me_when_a_reply_is_posted == "on"){

				$inp_email_mysql = quote_smart($link, $get_my_user_email);

				// Do I exists in subscription list?
				$query = "SELECT topic_subscriber_id FROM $t_forum_topics_subscribers WHERE topic_id='$get_current_topic_id' AND topic_subscriber_user_id=$my_user_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_topic_subscriber_id) = $row;


				if($get_topic_subscriber_id == ""){

					mysqli_query($link, "INSERT INTO $t_forum_topics_subscribers
					(topic_subscriber_id, topic_id, topic_subscriber_user_id, topic_subscriber_user_email) 
					VALUES 
					(NULL, '$get_current_topic_id', $my_user_id_mysql, $inp_email_mysql)")
					or die(mysqli_error($link));
				}
			}
			}




			// Update replies, date of reply
			$inp_topic_replies = $get_current_topic_replies + 1;
			$time = time();
			$result = mysqli_query($link, "UPDATE $t_forum_topics SET topic_replies=$inp_topic_replies, topic_last_replied='$datetime', topic_last_replied_time='$time' WHERE topic_id=$topic_id_mysql");


			// Top users monthly
			$year = date("Y");
			$month = date("m");
			$inp_my_user_alias_mysql = quote_smart($link, $get_my_user_alias);
			$query = "SELECT top_monthly_id, top_monthly_user_id, top_monthly_year, top_monthly_month, top_monthly_topics, top_monthly_replies, top_monthly_times_voted, top_monthly_points FROM $t_forum_top_users_monthly WHERE top_monthly_user_id=$my_user_id_mysql AND top_monthly_year=$year AND top_monthly_month=$month";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_top_monthly_id, $get_top_monthly_user_id, $get_top_monthly_year, $get_top_monthly_month, $get_top_monthly_topics, $get_top_monthly_replies, $get_top_monthly_times_voted, $get_top_monthly_points) = $row;
			if($get_top_monthly_id == ""){
				// First time I posted this month
				mysqli_query($link, "INSERT INTO $t_forum_top_users_monthly 
				(top_monthly_id, top_monthly_user_id, top_monthly_year, top_monthly_month, top_monthly_topics, top_monthly_replies, top_monthly_times_voted, top_monthly_points, top_monthly_user_alias, top_monthly_user_image) 
				VALUES 
				(NULL, $my_user_id_mysql, '$year', '$month', '0', '1', '0', 5, $inp_my_user_alias_mysql, $inp_reply_user_image_mysql)")
				or die(mysqli_error($link));
			}
			else{
				$inp_top_monthly_replies = $get_top_monthly_replies + 1;
				$inp_top_monthly_points = $get_top_monthly_points + 5;

				$result = mysqli_query($link, "UPDATE $t_forum_top_users_monthly SET top_monthly_replies=$inp_top_monthly_replies, top_monthly_points=$inp_top_monthly_points, top_monthly_user_alias=$inp_my_user_alias_mysql, top_monthly_user_image=$inp_reply_user_image_mysql WHERE top_monthly_id='$get_top_monthly_id'");

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
				(NULL, $my_user_id_mysql, '0', '1', '0', '$year', 5, $inp_my_user_alias_mysql, $inp_reply_user_image_mysql)")
				or die(mysqli_error($link));
			}
			else{
				$inp_top_yearly_replies = $get_top_yearly_replies + 1;
				$inp_top_yearly_points = $get_top_yearly_points + 5;

				$result = mysqli_query($link, "UPDATE $t_forum_top_users_yearly SET top_yearly_replies=$inp_top_yearly_replies, top_yearly_points=$inp_top_yearly_points, top_yearly_user_alias=$inp_my_user_alias_mysql, top_yearly_user_image=$inp_reply_user_image_mysql WHERE top_yearly_id='$get_top_yearly_id'") or die(mysqli_error($link));
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
				(NULL, $my_user_id_mysql, '0', '1', '0', 5, $inp_my_user_alias_mysql, $inp_reply_user_image_mysql)")
				or die(mysqli_error($link));
			}
			else{
				$inp_top_all_replies = $get_top_all_replies + 1;
				$inp_top_all_points = $get_top_all_points + 5;

				$result = mysqli_query($link, "UPDATE $t_forum_top_users_all_time SET top_all_replies=$inp_top_all_replies, top_all_points=$inp_top_all_points, top_all_user_alias=$inp_my_user_alias_mysql, top_all_user_image=$inp_reply_user_image_mysql WHERE top_all_id='$get_top_all_id'");
			}


			// Update topic "last updated"
			// This will give the topic 2 more weeks before a answer is picked and the topic is marked as solved
			$datetime = date("Y-m-d H:i:s");
			$time = time();
			$result = mysqli_query($link, "UPDATE $t_forum_topics SET topic_updated='$datetime', topic_updated_time='$time' WHERE topic_id='$get_current_topic_id'") or die(mysqli_error($link));
		




			$url = "view_topic.php?topic_id=$topic_id&show=$show&l=$l#reply$get_reply_id";
			header("Location: $url");
			exit;
		} // process


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
				echo"<a href=\"index.php?l=$l\">$get_current_title_value</a>";
			}
			echo"
			&gt;
			<a href=\"view_topic.php?topic_id=$topic_id&amp;l=$l\">$get_current_topic_title</a>
			&gt;
			<a href=\"reply.php?topic_id=$topic_id&amp;l=$l\">$l_reply</a>
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
			<a id=\"answer_form\"></a>
			<h2>$l_your_answer</h2>

			
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
					height: 400,
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
					\$('[name=\"inp_text\"]').focus();
				});
				</script>
	
				<form method=\"post\" action=\"reply.php?l=$l&amp;topic_id=$topic_id&amp;process=1\" enctype=\"multipart/form-data\">
				<p>
				<textarea name=\"inp_text\" rows=\"5\" cols=\"50\" class=\"editor\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea>
				</p>
		
				<p>
				<input type=\"checkbox\" name=\"inp_notify_me_when_a_reply_is_posted\" "; if($get_es_on_off == "1"){ echo" checked=\"checked\""; } echo"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> $l_notify_me_when_a_reply_is_posted
				</p>

				<p><input type=\"submit\" value=\"$l_post_your_answer\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
				</form>
				<!-- //Form -->
			";
	}
	else{
		echo"
		<p>Not logged in.</p>
		";
	}
} //  post found



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>