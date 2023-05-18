<?php 
/**
*
* File: forum/form_view.php
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

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/forum/ts_index.php");

/*- Forum config ------------------------------------------------------------------------ */
include("_include_tables.php");

/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);




/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['form_id'])){
	$form_id = $_GET['form_id'];
	$formc_id = output_html($form_id);
}
else{
	$form_id = "";
}

/*- Content ---------------------------------------------------------------------------------- */

// Get form
$form_id_mysql = quote_smart($link, $form_id);
$query = "SELECT form_id, form_title, form_language, form_introduction, form_insert_title_start, form_tags, form_created, form_updated FROM $t_forum_forms WHERE form_id=$form_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_form_id, $get_current_form_title, $get_current_form_language, $get_current_form_introduction, $get_current_form_insert_title_start, $get_current_form_tags, $get_current_form_created, $get_current_form_updated) = $row;

if($get_current_form_id == ""){
	echo"
	<h1>Error</h1>

	<p>
	Not found.
	</p>
	";

}
else{
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$get_current_form_title";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
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
		list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_rank) = $row;
	
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


		if($action == "send"){
			$inp_title = "$get_current_form_insert_title_start";
			$inp_text = "<p>";

			$query = "SELECT form_question_id, form_question, form_question_type, form_question_options, form_question_help_text FROM $t_forum_forms_questions WHERE form_id=$get_current_form_id";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_form_question_id, $get_form_question, $get_form_question_type, $get_form_question_options, $get_form_question_help_text) = $row;
			
				$data = $_POST["inp_q_$get_form_question_id"];
				$data = output_html($data);
				
				if($data == ""){
					$ft = "error";
					$fm = "<b>$l_please_answer_the_question:</b> $get_form_question";
					$action = "";
				}
				
				if($get_form_question_type == "text"){
					$inp_title = $inp_title . " " . $data;
					if($get_form_question_help_text != ""){
						$inp_title = $inp_title . " " . $get_form_question_help_text;
					}
					$inp_text = $inp_text . "$get_form_question: $data<br />";
				}
				elseif($get_form_question_type == "textarea"){
					$inp_text = $inp_text . "<br /><br />$data<br />";
				}
				elseif($get_form_question_type == "select"){
					$inp_title = $inp_title . " " . $data;
					if($get_form_question_help_text != ""){
						$inp_title = $inp_title . " " . $get_form_question_help_text;
					}
					$inp_text = $inp_text . "$get_form_question: $data<br />";
				}
			}
			$inp_text = $inp_text . "</p>";

	
			if($action == "send"){
				// Title
				$inp_title = trim($inp_title);
				$inp_title = output_html($inp_title);
				$size = strlen($inp_title);
				if($size > 40){
					$inp_title = substr($inp_title, 0, 35);
					$inp_title = $inp_title . "...";
				}
				$inp_title = $inp_title . " ($get_user_alias)";
				$inp_title = str_replace("&lt;br /&gt;", "", $inp_title);
				$inp_title = str_replace("&amp;lt;br /&amp;gt;", "", $inp_title);
				$inp_title_mysql = quote_smart($link, $inp_title);

				
				// Rest	
				$datetime = date("Y-m-d H:i:s");

				$inp_user_ip = $_SERVER['REMOTE_ADDR'];
				$inp_user_ip = output_html($inp_user_ip);
				$inp_user_ip_mysql = quote_smart($link, $inp_user_ip);
	
				$inp_topic_user_alias_mysql = quote_smart($link, $get_user_alias);


				// Get my photo
				$query = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id='$get_user_id' AND photo_profile_image='1'";
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
				(topic_id, topic_user_id, topic_user_alias, topic_user_image, topic_language, topic_title, topic_created, topic_updated, topic_updated_translated, topic_replies, topic_views, topic_rating, topic_user_ip) 
				VALUES 
				(NULL, $my_user_id_mysql, $inp_topic_user_alias_mysql, $inp_topic_user_image_mysql, $l_mysql, $inp_title_mysql, '$datetime', '$datetime', '$inp_topic_updated_translated', '0', '0', '0', $inp_user_ip_mysql)")
				or die(mysqli_error($link));



				// Get ID
				$query = "SELECT topic_id FROM $t_forum_topics WHERE topic_user_id=$my_user_id_mysql AND topic_created='$datetime'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_topic_id) = $row;

				require_once "$root/_admin/_functions/htmlpurifier/HTMLPurifier.auto.php";
				$config = HTMLPurifier_Config::createDefault();
				$purifier = new HTMLPurifier($config);

	
				if($get_user_rank == "admin" OR $get_user_rank == "moderator" OR $get_user_rank == "editor"){
				}
				elseif($get_user_rank == "trusted"){
				}
				else{
					// p, ul, li, b
					$config->set('HTML.Allowed', 'p,br,b,a[href],i,ul,li');
				}

				$inp_text = $purifier->purify($inp_text);
			
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
		
						$inp_email_mysql = quote_smart($link, $get_user_email);

						mysqli_query($link, "INSERT INTO $t_forum_topics_subscribers
						(topic_subscriber_id, topic_id, topic_subscriber_user_id, topic_subscriber_user_email) 
						VALUES 
						(NULL, '$get_topic_id', $my_user_id_mysql, $inp_email_mysql)")
						or die(mysqli_error($link));

					}
				}


				// Email
		

				// Who is moderator of the week?
				$week = date("W");
				$year = date("Y");

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

		
				// Mail from
				$view_link = $configSiteURLSav . "/forum/view_topic.php?topic_id=$get_topic_id";
				$edit_link = $configSiteURLSav . "/forum/edit_topic.php?topic_id=$get_topic_id";
				$delete_link = $configSiteURLSav . "/forum/delete_topic.php?topic_id=$get_topic_id";
			
				$user_agent = $_SERVER['HTTP_USER_AGENT'];
				$user_agent = output_html($user_agent);

				$subject = "forum - New topic $inp_title at $configWebsiteTitleSav";

				$message = "<html>\n";
				$message = $message. "<head>\n";
				$message = $message. "  <title>$subject</title>\n";
				$message = $message. " </head>\n";
				$message = $message. "<body>\n";

				$message = $message . "<p>Hi $get_moderator_user_name,</p>\n\n";
				$message = $message . "<p><b>Summary:</b><br />There is a new topic on your web site. This is information e-mail only.</p>\n\n";

				$message = $message . "<p style='padding-bottom:0;margin-bottom:0'><b>Topic Information:</b></p>\n";
				$message = $message . "<table>\n";
				$message = $message . " <tr><td><span>Topic ID:</span></td><td><span>$get_topic_id</span></td></tr>\n";
				$message = $message . " <tr><td><span>Language:</span></td><td><span>$l</span></td></tr>\n";
				$message = $message . " <tr><td><span>Title:</span></td><td><span>$inp_title</span></td></tr>\n";
				$message = $message . " <tr><td><span>Updated:</span></td><td><span>$day $month_saying $year</span></td></tr>\n";
				$message = $message . " <tr><td><span>Text:</span></td><td>$inp_text</td></tr>\n";
				$message = $message . "</table>\n";
		
				$message = $message . "<p style='padding-bottom:0;margin-bottom:0'><b>Topic author:</b></p>\n";
				$message = $message . "<table>\n";
				$message = $message . " <tr><td><span>User:</span></td><td><span>$get_user_id</span></td></tr>\n";
				$message = $message . " <tr><td><span>Alias:</span></td><td><span>$get_user_alias</span></td></tr>\n";
				$message = $message . " <tr><td><span>IP:</span></td><td><span>$inp_user_ip</span></td></tr>\n";
				$message = $message . "</table>\n";
		
				$message = $message . "<p><b>Actions:</b><br />\n";
				$message = $message . "View: <a href=\"$view_link\">$view_link</a><br />\n";
				$message = $message . "Edit: <a href=\"$edit_link\">$edit_link</a><br />\n";
				$message = $message . "Delete: <a href=\"$delete_link\">$delete_link</a></p>";
				$message = $message . "<p>\n\n--<br />\nBest regards<br />\n$configFromNameSav</p>";
				$message = $message. "</body>\n";
				$message = $message. "</html>\n";

				$encoding = "utf-8";

				// Preferences for Subject field
				$subject_preferences = array(
			       "input-charset" => $encoding,
			       "output-charset" => $encoding,
			       "line-length" => 76,
			       "line-break-chars" => "\r\n"
				);
				$header = "Content-type: text/html; charset=".$encoding." \r\n";
				$header .= "From: ".$forumFromNameSav." <".$forumFromEmailSav."> \r\n";
				$header .= "MIME-Version: 1.0 \r\n";
				$header .= "Content-Transfer-Encoding: 8bit \r\n";
				$header .= "Date: ".date("r (T)")." \r\n";
				$header .= iconv_mime_encode("Subject", $subject, $subject_preferences);

				mail($get_moderator_user_email, $subject, $message, $header);


				echo"
				<h1>
				<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
				Loading...</h1>
				<meta http-equiv=\"refresh\" content=\"1;url=$root/forum/view_topic.php?topic_id=$get_topic_id&amp;l=$l\">
				";


			}
		}
		if($action == ""){
			echo"
			<h1>$get_current_form_title</h1>

			<p>$get_current_form_introduction</p>

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
					\$('[name=\"inp_question\"]').focus();
				});
				</script>
			
				<form method=\"post\" action=\"form_view.php?form_id=$form_id&amp;l=$l&amp;action=send\" enctype=\"multipart/form-data\">
				";

				$query = "SELECT form_question_id, form_question, form_question_type, form_question_options, form_question_help_text FROM $t_forum_forms_questions WHERE form_id=$get_current_form_id";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_form_question_id, $get_form_question, $get_form_question_type, $get_form_question_options, $get_form_question_help_text) = $row;

					if(isset($_POST["inp_q_$get_form_question_id"])){
						$data = $_POST["inp_q_$get_form_question_id"];
						$data = output_html($data);
						$data = str_replace("<br />", "\n", $data);
					}
					else{
						$data = "";
					}

					echo"
					<p><b>$get_form_question</b><br />
					";
					if($get_form_question_type == "text"){
						echo"<input type=\"text\" name=\"inp_q_$get_form_question_id\" value=\"$data\" size=\"35\" /> $get_form_question_help_text\n";
					}
					elseif($get_form_question_type == "textarea"){
						echo"<textarea name=\"inp_q_$get_form_question_id\" rows=\"7\" cols=\"45\">$data</textarea>\n";
					}
					elseif($get_form_question_type == "select"){
						echo"<select name=\"inp_q_$get_form_question_id\">\n";
						$array = explode("\n", $get_form_question_options);
						$size = sizeof($array);
						for($x=0;$x<$size;$x++){
							echo"	<option value=\"$array[$x]\""; if($data == "$array[$x]"){ echo" selected=\"selected\""; } echo">$array[$x]</option>\n";
						}
						echo"</select>\n";
					}
					echo"
					</p>
					";
				}
				echo"

				<p>
				<input type=\"submit\" value=\"$l_send\" class=\"btn\" />
				</p>

				</form>
			<!-- //Form -->

				<hr />
				<p class=\"smal\">
				$l_when_sending_this_form_it_will_create_a_new_post_on_the_discussion_board
				$l_the_members_of_the_site_can_then_reply_to_it 
				</p>
			";
		} // action == ""
	}
	else{
		echo"
		<h1>
		<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
		Loading...</h1>
		<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=forum/form_view.php?form_id=$form_id&amp;l=$l\">
		";
	}

} // forum found



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>