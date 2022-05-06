<?php 
/**
*
* File: forum/delete_topic.php
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

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/forum/ts_index.php");
include("$root/_admin/_translations/site/$l/forum/ts_new_topic.php");

/*- Forum config ------------------------------------------------------------------------ */
include("_include_tables.php");

/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";

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

// Get topic
$topic_id_mysql = quote_smart($link, $topic_id);
$query = "SELECT topic_id, topic_user_id, topic_user_alias, topic_user_image, topic_language, topic_title, topic_text, topic_created, topic_updated, topic_updated_translated, topic_replies, topic_views, topic_views_ip_block, topic_likes, topic_dislikes, topic_rating, topic_likes_ip_block, topic_user_ip FROM $t_forum_topics WHERE topic_id=$topic_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_topic_id, $get_current_topic_user_id, $get_current_topic_user_alias, $get_current_topic_user_image, $get_current_topic_language, $get_current_topic_title, $get_current_topic_text, $get_current_topic_created, $get_current_topic_updated, $get_current_topic_updated_translated, $get_current_topic_replies, $get_current_topic_views, $get_current_topic_views_ip_block, $get_current_topic_likes, $get_current_topic_dislikes, $get_current_topic_rating, $get_current_topic_likes_ip_block, $get_current_topic_user_ip) = $row;

if($get_current_topic_id == ""){
	echo"<p>Topic post not found.</p>";
	
}
else{
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$get_current_topic_title - $l_forum";
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

		// Get topic owners subscription status
		$query = "SELECT topic_subscriber_id FROM $t_forum_topics_subscribers WHERE topic_id=$get_current_topic_id AND topic_subscriber_user_id=$get_current_topic_user_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_topic_subscriber_id) = $row;

		
		if($my_user_id == "$get_current_topic_user_id"){
			$can_edit = "true";
		}
		else{
			if($get_user_rank == "admin" OR $get_user_rank == "moderator"){
				$can_edit = "true";
			}
		}
		if(isset($can_edit) && $can_edit == "true"){
			if($process == "1"){
				// Delete
						
				$result = mysqli_query($link, "DELETE FROM $t_forum_topics WHERE topic_id=$topic_id_mysql");
				$result = mysqli_query($link, "DELETE FROM $t_forum_replies WHERE reply_topic_id=$topic_id_mysql");
				$result = mysqli_query($link, "DELETE FROM $t_forum_topics_read_by_user WHERE topic_read_topic_id=$topic_id_mysql");
				$result = mysqli_query($link, "DELETE FROM $t_forum_topics_subscribers WHERE topic_id=$topic_id_mysql");
				$result = mysqli_query($link, "DELETE FROM $t_forum_topics_tags WHERE topic_id=$topic_id_mysql");

				// Search engine
				$result = mysqli_query($link, "DELETE FROM $t_search_engine_index WHERE index_module_name='forum' AND index_reference_name='topic_id' AND index_reference_id=$get_current_topic_id");

				$url = "index.php?l=$l&ft=success&fm=topic_deleted";
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
				<a href=\"delete_topic.php?topic_id=$topic_id&amp;l=$l\">$l_delete_topic</a>
				</p>
			<!-- //Where am I ? -->

			<p>
			$l_are_you_sure
			</p>

			<p>
			<a href=\"delete_topic.php?topic_id=$topic_id&amp;l=$l&amp;process=1\" class=\"btn btn_warning\">$l_confirm</a>
			</p>
			";
		}
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