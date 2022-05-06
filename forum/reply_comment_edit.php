<?php 
/**
*
* File: forum/reply_comment_edit.php
* Version 1.0.0
* Date 09:38 26.04.2019
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
include("$root/_admin/_translations/site/$l/forum/ts_reply_add_a_comment.php");

/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);




/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['reply_comment_id'])){
	$reply_comment_id = $_GET['reply_comment_id'];
	$reply_comment_id = output_html($reply_comment_id);
}
else{
	$reply_comment_id = "";
}
if(isset($_GET['show'])) {
	$show = $_GET['show'];
	$show = strip_tags(stripslashes($show));
}
else{
	$show = "";
}

// Get reply comment id
$reply_comment_id_mysql = quote_smart($link, $reply_comment_id);
$query = "SELECT reply_comment_id, reply_comment_user_id, reply_comment_user_alias, reply_comment_user_image, reply_comment_topic_id, reply_comment_reply_id, reply_comment_text, reply_comment_created, reply_comment_updated, reply_comment_updated_translated, reply_comment_likes, reply_comment_dislikes, reply_comment_rating, reply_comment_likes_ip_block, reply_comment_user_ip, reply_comment_reported, reply_comment_reported_by_user_id, reply_comment_reported_reason, reply_comment_reported_checked FROM $t_forum_replies_comments WHERE reply_comment_id=$reply_comment_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_reply_comment_id, $get_current_reply_comment_user_id, $get_current_reply_comment_user_alias, $get_current_reply_comment_user_image, $get_current_reply_comment_topic_id, $get_current_reply_comment_reply_id, $get_current_reply_comment_text, $get_current_reply_comment_created, $get_current_reply_comment_updated, $get_current_reply_comment_updated_translated, $get_current_reply_comment_likes, $get_current_reply_comment_dislikes, $get_current_reply_comment_rating, $get_current_reply_comment_likes_ip_block, $get_current_reply_comment_user_ip, $get_current_reply_comment_reported, $get_current_reply_comment_reported_by_user_id, $get_current_reply_comment_reported_reason, $get_current_reply_comment_reported_checked) = $row;

if($get_current_reply_comment_id == ""){
	echo"<p>Reply comment not found.</p>";
	
}
else{
	// Find topic
	$topic_id_mysql = quote_smart($link, $get_current_reply_comment_topic_id);
	$query = "SELECT topic_id, topic_user_id, topic_user_alias, topic_user_image, topic_language, topic_title, topic_text, topic_created, topic_updated, topic_updated_translated, topic_replies, topic_views, topic_views_ip_block, topic_likes, topic_dislikes, topic_rating, topic_likes_ip_block, topic_user_ip, topic_reported, topic_reported_by_user_id, topic_reported_reason, topic_reported_checked FROM $t_forum_topics WHERE topic_id=$topic_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_topic_id, $get_current_topic_user_id, $get_current_topic_user_alias, $get_current_topic_user_image, $get_current_topic_language, $get_current_topic_title, $get_current_topic_text, $get_current_topic_created, $get_current_topic_updated, $get_current_topic_updated_translated, $get_current_topic_replies, $get_current_topic_views, $get_current_topic_views_ip_block, $get_current_topic_likes, $get_current_topic_dislikes, $get_current_topic_rating, $get_current_topic_likes_ip_block, $get_current_topic_user_ip, $get_current_topic_reported, $get_current_topic_reported_by_user_id, $get_current_topic_reported_reason, $get_current_topic_reported_checked) = $row;

	// Get reply
	$reply_id_mysql = quote_smart($link, $get_current_reply_comment_reply_id);
	$query = "SELECT reply_id, reply_user_id, reply_user_alias, reply_user_image, reply_topic_id, reply_text, reply_created, reply_updated, reply_updated_translated, reply_selected_answer, reply_likes, reply_dislikes, reply_rating, reply_likes_ip_block, reply_user_ip, reply_reported, reply_reported_by_user_id, reply_reported_reason, reply_reported_checked FROM $t_forum_replies WHERE reply_id=$reply_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_reply_id, $get_current_reply_user_id, $get_current_reply_user_alias, $get_current_reply_user_image, $get_current_reply_topic_id, $get_current_reply_text, $get_current_reply_created, $get_current_reply_updated, $get_current_reply_updated_translated, $get_current_reply_selected_answer, $get_current_reply_likes, $get_current_reply_dislikes, $get_current_reply_rating, $get_current_reply_likes_ip_block, $get_current_reply_user_ip, $get_current_reply_reported, $get_current_reply_reported_by_user_id, $get_current_reply_reported_reason, $get_current_reply_reported_checked) = $row;

	

	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$l_edit_comment - $get_current_topic_title - $l_forum ";
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
		

		if($get_my_user_id == "$get_current_reply_comment_user_id"){
		
			if($process == "1"){
				$inp_text = $_POST['inp_text'];
				$inp_text = output_html($inp_text);
				$inp_text_mysql = quote_smart($link, $inp_text);
				if(empty($inp_text)){
					$url = "reply_comment_edit.php?reply_comment_id=$get_current_reply_comment_id&l=$l&ft=error&fm=insert_some_text";
					header("Location: $url");
					exit;
				}

				// Rest	
				$datetime = date("Y-m-d H:i:s");

				$inp_my_ip = $_SERVER['REMOTE_ADDR'];
				$inp_my_ip = output_html($inp_my_ip);
				$inp_my_ip_mysql = quote_smart($link, $inp_my_ip);

				// Updated_translated
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

				// Update
				$result = mysqli_query($link, "UPDATE $t_forum_replies_comments SET 
						reply_comment_text=$inp_text_mysql,
						reply_comment_updated='$datetime',
						reply_comment_updated_translated='$inp_updated_translated',
						reply_comment_user_ip=$inp_my_ip_mysql,
						reply_comment_reported='0',
						reply_comment_reported_by_user_id='0',
						reply_comment_reported_reason='',
						reply_comment_reported_checked=''
				 WHERE reply_comment_id=$get_current_reply_comment_id") or die(mysqli_error($link));



	
				$url = "view_topic.php?topic_id=$get_current_topic_id&l=$l&ft=success&fm=changes_saved#replycomment$get_current_reply_comment_id";
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
				<a href=\"view_topic.php?topic_id=$get_current_topic_id&amp;l=$l\">$get_current_topic_title</a>
				&gt;
				<a href=\"view_topic.php?topic_id=$get_current_topic_id&amp;l=$l#replycomment$get_current_reply_comment_id\">$l_comment_by $get_current_reply_comment_user_alias</a>
				&gt;
				<a href=\"reply_comment_edit.php?reply_comment_id=$get_current_reply_comment_id&amp;l=$l\">$l_edit_comment</a>
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
			
				<form method=\"post\" action=\"reply_comment_edit.php?reply_comment_id=$get_current_reply_comment_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
					
		
				<p><b>$l_comment:</b><br />
				<textarea name=\"inp_text\" rows=\"5\" cols=\"50\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
				$get_current_reply_comment_text = str_replace("<br />", "\n", $get_current_reply_comment_text);
				echo"$get_current_reply_comment_text</textarea>
				</p>
		

				<p><input type=\"submit\" value=\"$l_save_changes\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
				</form>
			<!-- //Form -->
			";
		} // reported
		else{
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
				<a href=\"view_topic.php?topic_id=$get_current_topic_id&amp;l=$l\">$get_current_topic_title</a>
				&gt;
				<a href=\"view_topic.php?topic_id=$get_current_topic_id&amp;l=$l#replycomment$get_current_reply_comment_id\">$l_comment_by $get_current_reply_comment_user_alias</a>
				&gt;
				<a href=\"reply_comment_edit.php?reply_comment_id=$get_current_reply_comment_id&amp;l=$l\">$l_edit_comment</a>
				</p>
			<!-- //Where am I ? -->

			<p>$l_access_denied $l_only_the_owner_admin_or_moderators_can_edit</p>
			";
		}
	}
	else{
		echo"
		<h1>
		<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
		Loading...</h1>
		<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/forum/report_topic.php?topic_id=$topic_id\">
		";
	}
} //  post found



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>