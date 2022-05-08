<?php 
/**
*
* File: forum/view_topic.php
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

/*- Forum config ------------------------------------------------------------------------ */
include("$root/_admin/_data/forum.php");
include("_include_tables.php");


if($forumWritingMethodSav == "bbcode"){
	// BBCode
	include("$root/_admin/_functions/bbcode.php");
	include("$root/_admin/_functions/bbcode_tag.php");
}


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
$query = "SELECT topic_id, topic_user_id, topic_user_alias, topic_user_image, topic_language, topic_title, topic_text, topic_created, topic_created_time, topic_updated, topic_updated_time, topic_updated_translated, topic_replies, topic_views, topic_views_ip_block, topic_likes, topic_dislikes, topic_rating, topic_likes_ip_block, topic_user_ip, topic_solved FROM $t_forum_topics WHERE topic_id=$topic_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_topic_id, $get_current_topic_user_id, $get_current_topic_user_alias, $get_current_topic_user_image, $get_current_topic_language, $get_current_topic_title, $get_current_topic_text, $get_current_topic_created, $get_current_topic_created_time, $get_current_topic_updated, $get_current_topic_updated_time, $get_current_topic_updated_translated, $get_current_topic_replies, $get_current_topic_views, $get_current_topic_views_ip_block, $get_current_topic_likes, $get_current_topic_dislikes, $get_current_topic_rating, $get_current_topic_likes_ip_block, $get_current_topic_user_ip, $get_current_topic_solved) = $row;

if($get_current_topic_id == ""){

	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "Server error 404 - $get_current_title_value";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");
	echo"<p>Topic not found.</p>";
	
}
else{
	// Topic title substr
	$get_current_topic_title_len = strlen($get_current_topic_title);
	if($get_current_topic_title_len > 80){
		$get_current_topic_title = substr($get_current_topic_title, 0, 75);
		$get_current_topic_title = $get_current_topic_title . "...";
	}

	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$get_current_topic_title - $get_current_title_value";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
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

		// Mark it read
		$query = "SELECT topic_read_id FROM $t_forum_topics_read_by_user WHERE topic_read_topic_id=$get_current_topic_id AND topic_read_user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_topic_read_id) = $row;
		if($get_topic_read_id == ""){
			mysqli_query($link, "INSERT INTO $t_forum_topics_read_by_user
			(topic_read_id, topic_read_topic_id, topic_read_user_id) 
			VALUES 
			(NULL, '$get_current_topic_id', $my_user_id_mysql)")
			or die(mysqli_error($link));
		}
	}
	else{
		// By IP
		$query = "SELECT topic_read_id FROM $t_forum_topics_read_by_ip WHERE topic_read_topic_id=$get_current_topic_id AND topic_read_ip=$my_ip_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_topic_read_id) = $row;
		if($get_topic_read_id == ""){
			$year = date("Y");
			mysqli_query($link, "INSERT INTO $t_forum_topics_read_by_ip
			(topic_read_id, topic_read_topic_id, topic_read_ip, topic_read_year) 
			VALUES 
			(NULL, '$get_current_topic_id', $my_ip_mysql, $year)")
			or die(mysqli_error($link));


			// Clean up last years IP
			$last_year = $year - 1;
			
			$result = mysqli_query($link, "DELETE FROM $t_forum_topics_read_by_ip WHERE topic_read_year=$last_year") or die(mysqli_error($link));
			
		}
	}
		


	// Unique hits topic
	$inp_date = date("ymd");

	$ip_block_array = explode("\n", $get_current_topic_views_ip_block);
	$ip_block_array_size = sizeof($ip_block_array);

	if($ip_block_array_size > 30){
		$ip_block_array_size = 20;
	}
	
	$has_seen_this_before = 0;

	for($x=0;$x<$ip_block_array_size;$x++){
		if($ip_block_array[$x] == "$my_ip$inp_date"){
			$has_seen_this_before = 1;
			break;
		}
	}
	if($has_seen_this_before == 0){
		$ip_block = $my_ip.$inp_date . "\n" . $get_current_topic_views_ip_block;
		$ip_block_mysql = quote_smart($link, $ip_block);
		$inp_unique_hits = $get_current_topic_views + 1;
		$result = mysqli_query($link, "UPDATE $t_forum_topics SET topic_views=$inp_unique_hits, topic_views_ip_block=$ip_block_mysql WHERE topic_id=$get_current_topic_id") or die(mysqli_error($link));
	}




	// Is the topic solved?
	$timestamp = time();
	$days_since_written = $timestamp-$get_current_topic_updated_time;
	$days_since_written = floor($days_since_written/86400);



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

	<!-- Prism Javascript>
		<script type=\"text/javascript\" src=\"_css/prism.js\"></script>
	<!-- //Prism Javascript>


	<!-- Topic -->
		<table style=\"width: 100%;\">
		 <tr>
		  <td style=\"width: 80px;padding-right:25px;text-align: center;vertical-align:top;\">

			<!-- Topic owner avatar -->

					";
					
					// Avatar
					$inp_new_x = 40; // 950
					$inp_new_y = 40; // 640
					$thumb = "user_" . $get_current_topic_user_image . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
					if($get_current_topic_user_image != "" && !(file_exists("$root/_cache/$thumb")) && file_exists("$root/_uploads/users/images/$get_current_topic_user_id/$get_current_topic_user_image")){
						resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_current_topic_user_id/$get_current_topic_user_image", "$root/_cache/$thumb");
					}
					echo"
					<p style=\"padding: 8px 0px 0px 0px;margin:0;\">
					<a href=\"$root/users/view_profile.php?user_id=$get_current_topic_user_id&amp;l=$l\">";
					if($get_current_topic_user_image != "" && file_exists("$root/_cache/$thumb")){
						echo"<img src=\"$root/_cache/$thumb\" alt=\"$get_current_topic_user_image\" class=\"image\" />";
					}
					else{
						echo"<img src=\"_gfx/avatar_blank_40.png\" alt=\"avatar_blank_40.png\" />";
					}
					echo"</a><br />
					<a href=\"$root/users/view_profile.php?user_id=$get_current_topic_user_id&amp;l=$l\">$get_current_topic_user_alias</a>
					</p>
			<!-- //Topic owner avatar -->

			<!-- Vote -->
					";

					// Have I voted?
					$my_vote = "";
					
					if(isset($get_my_user_id)){
						$voted_array = explode("\n", $get_current_topic_likes_ip_block);
						$voted_array_size = sizeof($voted_array);
		
						for($x=0;$x<$voted_array_size;$x++){
							$temp = explode(":", $voted_array[$x]);
							$user_id = $temp[0];
							if(isset($temp[1])){
								$like_dislike = $temp[1];
								if($user_id == "$get_my_user_id"){
									$my_vote = "$like_dislike";
								}
							}
						}

						if($my_vote == ""){
							echo"
							<p>
							<a href=\"vote_topic_up.php?topic_id=$topic_id&amp;show=$show&amp;l=$l&amp;process=1\"><img src=\"_gfx/vote_up.png\" alt=\"vote_up\" /></a>
							</p>
							<h2 class=\"grey\" style=\"padding:0;margin:0;\">$get_current_topic_rating</h2>
							<p>
							<a href=\"vote_topic_down.php?topic_id=$topic_id&amp;show=$show&amp;l=$l&amp;process=1\"><img src=\"_gfx/vote_down.png\" alt=\"vote_down\" /></a>
							</p>
							";
						}
						elseif($my_vote == "1"){
							echo"
							<p>
							<img src=\"_gfx/vote_up_active.png\" alt=\"vote_up\" />
							</p>
							<h2 class=\"grey\" style=\"padding:0;margin:0;\">$get_current_topic_rating</h2>
							<p>
							<a href=\"vote_topic_down.php?topic_id=$topic_id&amp;show=$show&amp;l=$l&amp;process=1\"><img src=\"_gfx/vote_down.png\" alt=\"vote_down\" /></a>
							</p>
							";
						}
						elseif($my_vote == "0"){
							echo"
							<p>
							<a href=\"vote_topic_up.php?topic_id=$topic_id&amp;show=$show&amp;l=$l&amp;process=1\"><img src=\"_gfx/vote_up.png\" alt=\"vote_up\" /></a>
							</p>
							<h2 class=\"grey\" style=\"padding:0;margin:0;\">$get_current_topic_rating</h2>
							<p>
							<img src=\"_gfx/vote_down_active.png\" alt=\"vote_down\" />
							</p>
							";
						}
					}
					else{
						echo"
						<p>
						<a href=\"$root/users/login.php?l=$l&amp;referer=forum/vote_topic_up.php?topic_id=$topic_id&amp;show=$show&amp;l=$l\"><img src=\"_gfx/vote_up.png\" alt=\"vote_up\" /></a>
						</p>
						<h2 class=\"grey\" style=\"padding:0;margin:0;\">$get_current_topic_rating</h2>
						<p>
						<a href=\"$root/users/login.php?l=$l&amp;referer=$root/forum/vote_topic_down.php?topic_id=$topic_id&amp;show=$show&amp;l=$l\"><img src=\"_gfx/vote_down.png\" alt=\"vote_down\" /></a>
						</p>
						";
					}
					echo"
			<!-- //Vote -->
		  </td>
		  <td style=\"vertical-align:top;\">

			<!-- Topic header -->
				<p class=\"grey_small\">
						$l_posted_by
						<a href=\"$root/users/index.php?category=users&amp;page=view_profile&amp;user_id=$get_current_topic_user_id&amp;l=$l\" class=\"grey_small\">$get_current_topic_user_alias</a> 
						&middot;
						$get_current_topic_updated_translated ($days_since_written $l_days_ago_lowercase)
						&middot;
						<a href=\"report_topic.php?topic_id=$topic_id&amp;l=$l\" class=\"grey_small\">$l_report</a>
					";
					if(isset($get_my_user_id)){

						if($my_user_id == "$get_current_topic_user_id"){
							echo"
							&middot;
							<a href=\"edit_topic.php?topic_id=$topic_id&amp;l=$l\" class=\"grey_small\">$l_edit</a>
							&middot;
							<a href=\"delete_topic.php?topic_id=$topic_id&amp;l=$l\" class=\"grey_small\">$l_delete</a>
							";
						}
						else{
							if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
								echo"
								&middot;
								<a href=\"edit_topic.php?topic_id=$topic_id&amp;l=$l\" class=\"grey_small\">$l_edit</a>
								&middot;
								<a href=\"delete_topic.php?topic_id=$topic_id&amp;l=$l\" class=\"grey_small\">$l_delete</a>
								";
							}
						}

					}
					echo"
					&middot;
					<a href=\"share_topic_with_email.php?topic_id=$topic_id&amp;l=$l\" class=\"grey_small\" title=\"$l_share_with_email\">$l_email</a>
				</p>
			<!-- //Topic header -->

			<!-- Topic text -->
				";
				if($forumWritingMethodSav == "what_you_see_is_what_you_get"){
					echo"
					$get_current_topic_text
					";
				} // What you see is what you get
				else{
					$bbcode = new ChrisKonnertz\BBCode\BBCode();
					$rendered = $bbcode->render($get_current_topic_text);
					echo $rendered;
				} // BBCode
				echo"
			<!-- //Topic -->
		  </td>
		 </tr>
		</table>


	<!-- //Topic -->

	<!-- Replies -->
		
		<div class=\"clear\" style=\"height:20px;\"></div>
		";


		if($get_current_topic_replies == "0"){

		}
		elseif($get_current_topic_replies == "1"){
			echo"<h2>$l_one_reply</h2>";
		}
		else{
			echo"<h2>$get_current_topic_replies $l_replies_lowercase</h2>";
		}

		$query_w = "SELECT reply_id, reply_user_id, reply_user_alias, reply_user_image, reply_topic_id, reply_text, reply_created, reply_updated, reply_updated_translated, reply_selected_answer, reply_likes, reply_dislikes, reply_rating, reply_likes_ip_block, reply_user_ip FROM $t_forum_replies WHERE reply_topic_id=$get_current_topic_id ORDER BY reply_rating DESC";
		$result_w = mysqli_query($link, $query_w);
		while($row_w = mysqli_fetch_row($result_w)) {
			list($get_reply_id, $get_reply_user_id, $get_reply_user_alias, $get_reply_user_image, $get_reply_topic_id, $get_reply_text, $get_reply_created, $get_reply_updated, $get_reply_updated_translated, $get_reply_selected_answer, $get_reply_likes, $get_reply_dislikes, $get_reply_rating, $get_reply_likes_ip_block, $get_reply_user_ip) = $row_w;

			echo"
			<a id=\"reply$get_reply_id\"></a>
			<hr />
			";

			// Solved?
			if($get_current_topic_solved == "0" && $days_since_written > 14){
				$result_update = mysqli_query($link, "UPDATE $t_forum_topics SET topic_solved='1' WHERE topic_id=$get_current_topic_id") or die(mysqli_error($link));
				echo"<div class=\"info\"><p>$l_this_answer_solved_the_topic $l_the_topic_was_started $days_since_written $l_days_ago_lowercase</p></div>";
	
				// Pass new information
				$get_current_topic_solved = "1";
			}
			echo"
			<table style=\"width: 100%;\">
			 <tr>
			  <td style=\"width: 80px;padding-right:25px;text-align: center;vertical-align:top;\">
				<!-- Reply owner avatar -->

					";
					
					// Avatar
					$inp_new_x = 40; // 950
					$inp_new_y = 40; // 640
					$thumb = "user_" . $get_reply_user_image . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
					if(!(file_exists("$root/_cache/$thumb")) && file_exists("$root/_uploads/users/images/$get_reply_user_id/$get_reply_user_image") && $get_reply_user_image != ""){
						resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_reply_user_id/$get_reply_user_image", "$root/_cache/$thumb");
					}
					echo"
					<p style=\"padding: 8px 0px 0px 0px;margin:0;\">
					<a href=\"$root/users/view_profile.php?user_id=$get_reply_user_id&amp;l=$l\">";
					if($get_reply_user_image != "" && file_exists("$root/_cache/$thumb")){
						echo"<img src=\"$root/_cache/$thumb\" alt=\"$get_current_topic_user_image\" class=\"image\" />";
					}
					else{
						echo"<img src=\"_gfx/avatar_blank_40.png\" alt=\"avatar_blank_40.png\" />";
					}
					echo"</a><br />
					<a href=\"$root/users/view_profile.php?user_id=$get_reply_user_id&amp;l=$l\">$get_reply_user_alias</a>
					</p>
				<!-- //Reply owner avatar -->


				<!-- Vote -->
					";

					// Have I voted?
					$my_vote = "";
					
					if(isset($get_my_user_id)){
						$voted_array = explode("\n", $get_reply_likes_ip_block);
						$voted_array_size = sizeof($voted_array);
		
						for($x=0;$x<$voted_array_size;$x++){
							$temp = explode(":", $voted_array[$x]);
							$user_id = $temp[0];

							if(isset($temp[1])){
								$like_dislike = $temp[1];
								if($user_id == "$get_my_user_id"){
									$my_vote = "$like_dislike";
								}
							}
						}

						if($my_vote == ""){
							echo"
							<p>
							<a href=\"vote_reply_up.php?reply_id=$get_reply_id&amp;topic_id=$topic_id&amp;show=$show&amp;l=$l&amp;process=1\"><img src=\"_gfx/vote_up.png\" alt=\"vote_up\" /></a>
							</p>
							<h2 class=\"grey\" style=\"padding:0;margin:0;\">$get_reply_rating</h2>
							<p>
							<a href=\"vote_reply_down.php?reply_id=$get_reply_id&amp;topic_id=$topic_id&amp;show=$show&amp;l=$l&amp;process=1\"><img src=\"_gfx/vote_down.png\" alt=\"vote_down\" /></a>
							</p>
							";
						}
						elseif($my_vote == "1"){
							echo"
							<p>
							<img src=\"_gfx/vote_up_active.png\" alt=\"vote_up\" />
							</p>
							<h2 class=\"grey\" style=\"padding:0;margin:0;\">$get_reply_rating</h2>
							<p>
							<a href=\"vote_reply_down.php?reply_id=$get_reply_id&amp;topic_id=$topic_id&amp;show=$show&amp;l=$l&amp;process=1\"><img src=\"_gfx/vote_down.png\" alt=\"vote_down\" /></a>
							</p>
							";
						}
						elseif($my_vote == "0"){
							echo"
							<p>
							<a href=\"vote_reply_up.php?reply_id=$get_reply_id&amp;topic_id=$topic_id&amp;show=$show&amp;l=$l&amp;process=1\"><img src=\"_gfx/vote_up.png\" alt=\"vote_up\" /></a>
							</p>
							<h2 class=\"grey\" style=\"padding:0;margin:0;\">$get_reply_rating</h2>
							<p>
							<img src=\"_gfx/vote_down_active.png\" alt=\"vote_down\" />
							</p>
							";
						}
					}
					else{
						echo"
						<p>
						<a href=\"$root/users/login.php?l=$l&amp;referer=$root/forum/vote_reply_up.php?reply_id=$get_reply_id&amp;topic_id=$topic_id&amp;show=$show&amp;l=$l\"><img src=\"_gfx/vote_up.png\" alt=\"vote_up\" /></a>
						</p>
						<h2 class=\"grey\" style=\"padding:0;margin:0;\">$get_reply_rating</h2>
						<p>
						<a href=\"$root/users/login.php?l=$l&amp;referer=$root/forum/vote_reply_down.php?reply_id=$get_reply_id&amp;topic_id=$topic_id&amp;show=$show&amp;l=$l\"><img src=\"_gfx/vote_down.png\" alt=\"vote_down\" /></a>
						</p>
						";
					}
					echo"
				<!-- //Vote -->
			  </td>
			  <td style=\"padding-right: 20px;vertical-align:top;\">

				<!-- Tools -->
					<p class=\"grey_small\">
						$l_posted_by
						<a href=\"$root/users/view_profile.php?user_id=$get_reply_user_id&amp;l=$l\" class=\"smal_grey\">$get_reply_user_alias</a> 
						&middot;
						$get_reply_updated_translated
						&middot;
						<a href=\"report_reply.php?topic_id=$topic_id&amp;reply_id=$get_reply_id&amp;l=$l\" class=\"grey_small\">$l_report</a>
					";
					if(isset($get_my_user_id)){

						if($my_user_id == "$get_reply_user_id"){
							echo"
							&middot;
							<a href=\"edit_reply.php?topic_id=$topic_id&amp;reply_id=$get_reply_id&amp;l=$l\" class=\"grey_small\">$l_edit</a>
							&middot;
							<a href=\"delete_reply.php?topic_id=$topic_id&amp;reply_id=$get_reply_id&amp;l=$l\" class=\"grey_small\">$l_delete</a>
							";
						}
						else{
							if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
								echo"
								&middot;
								<a href=\"edit_reply.php?topic_id=$topic_id&amp;reply_id=$get_reply_id&amp;l=$l\" class=\"grey_small\">$l_edit</a>
								&middot;
								<a href=\"delete_reply.php?topic_id=$topic_id&amp;reply_id=$get_reply_id&amp;l=$l\" class=\"grey_small\">$l_delete</a>
								";
							}
						}

					}
					echo"
						
					</p>
				<!-- //Tools -->
				<!-- Reply -->
					";
					if($forumWritingMethodSav == "what_you_see_is_what_you_get"){
						echo"
						$get_reply_text
						";
					} // What you see is what you get
					else{
						$bbcode = new ChrisKonnertz\BBCode\BBCode();
						$rendered = $bbcode->render($get_reply_text);
						echo $rendered;
					} // BBCode
					echo"
				<!-- //Reply -->

				<!-- View reply comments -->";
					$reply_comments_count = 0;
					$query_c = "SELECT reply_comment_id, reply_comment_user_id, reply_comment_user_alias, reply_comment_user_image, reply_comment_topic_id, reply_comment_reply_id, reply_comment_text, reply_comment_created, reply_comment_updated, reply_comment_updated_translated, reply_comment_likes, reply_comment_dislikes, reply_comment_rating, reply_comment_likes_ip_block, reply_comment_user_ip, reply_comment_reported, reply_comment_reported_by_user_id, reply_comment_reported_reason, reply_comment_reported_checked FROM $t_forum_replies_comments WHERE reply_comment_topic_id=$get_current_topic_id AND reply_comment_reply_id=$get_reply_id ORDER BY reply_comment_id ASC";
					$result_c = mysqli_query($link, $query_c);
					while($row_c = mysqli_fetch_row($result_c)) {
						list($get_reply_comment_id, $get_reply_comment_user_id, $get_reply_comment_user_alias, $get_reply_comment_user_image, $get_reply_comment_topic_id, $get_reply_comment_reply_id, $get_reply_comment_text, $get_reply_comment_created, $get_reply_comment_updated, $get_reply_comment_updated_translated, $get_reply_comment_likes, $get_reply_comment_dislikes, $get_reply_comment_rating, $get_reply_comment_likes_ip_block, $get_reply_comment_user_ip, $get_reply_comment_reported, $get_reply_comment_reported_by_user_id, $get_reply_comment_reported_reason, $get_reply_comment_reported_checked) = $row_c;

						echo"
						<a id=\"replycomment$get_reply_comment_id\"></a>
						<hr />
						<div style=\"padding-left: 20px;\">
							<p class=\"dark_grey\" style=\"padding: 2px 0px 2px 0px;margin:0;\">
							$get_reply_comment_text
							&dash; <a href=\"$root/users/view_profile.php?user_id=$get_reply_comment_user_id&amp;l=$l\">$get_reply_comment_user_alias</a>
							$get_reply_comment_updated_translated
							&middot;
							<a href=\"reply_comment_report.php?reply_comment_id=$get_reply_comment_id&amp;l=$l\" class=\"grey_small\">$l_report</a>
							&middot;
							<a href=\"reply_comment_edit.php?reply_comment_id=$get_reply_comment_id&amp;l=$l\" class=\"grey_small\">$l_edit</a>
							&middot;
							<a href=\"reply_comment_delete.php?reply_comment_id=$get_reply_comment_id&amp;l=$l\" class=\"grey_small\">$l_delete</a>
							</p>
						</div>
						";

						$reply_comments_count++;
					} // reply comments
					echo"
				<!-- //View reply comments -->

				<!-- Add a comment -->
					";
					if($reply_comments_count > 0){
						echo"
						<div style=\"padding-left: 20px;\">
						";
					}
					
					if(isset($get_my_user_id)){
						echo"
						<p>
						<a href=\"reply_add_a_comment.php?topic_id=$topic_id&amp;reply_id=$get_reply_id&amp;l=$l\" class=\"small\">$l_add_a_comment</a>
						</p>
						";
					}
					else{
						echo"
						<p>
						<a href=\"$root/users/login.php?l=$l&amp;refer=forum/reply_add_a_comment.php?reply_id=$get_reply_id&amp;topic_id=$topic_id&amp;l=$l\" class=\"small\">$l_add_a_comment</a>
						</p>
						";
					}
					if($reply_comments_count > 0){
						echo"
						</div>
						";
					}
					echo"
				<!-- //Add a comment -->


			  </td>
			 </tr>
			</table>
			";
		}
		echo"
	<!-- Replies -->

	<!-- Reply -->
		<div class=\"clear\"></div>";

		if(isset($get_my_user_id)){
			echo"
			<p>
			<a href=\"reply.php?topic_id=$topic_id&amp;l=$l\" class=\"btn_default\">$l_reply</a>
			</p>
			";
		}
		else{
			echo"
			<p>
			<a href=\"$root/users/login.php?l=$l&amp;refer=forum/view_topic.php?topic_id=$topic_id\" class=\"btn_default\">$l_reply</a>
			</p>
			";
		}
		echo"
	<!-- //Reply -->
	";
} //  post found



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>