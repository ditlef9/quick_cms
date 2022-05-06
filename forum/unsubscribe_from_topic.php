<?php 
/**
*
* File: forum/unsubscribe_from_topic.php
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
if(isset($_GET['topic_subscriber_id'])) {
	$topic_subscriber_id = $_GET['topic_subscriber_id'];
	$topic_subscriber_id = strip_tags(stripslashes($topic_subscriber_id));
}
else{
	$topic_subscriber_id = "";
}

// Get topic
$topic_id_mysql = quote_smart($link, $topic_id);
$query = "SELECT topic_id, topic_user_id, topic_user_alias, topic_user_image, topic_language, topic_title, topic_text, topic_created, topic_updated, topic_updated_translated, topic_replies, topic_views, topic_views_ip_block, topic_likes, topic_dislikes, topic_rating, topic_likes_ip_block, topic_user_ip FROM $t_forum_topics WHERE topic_id=$topic_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_topic_id, $get_current_topic_user_id, $get_current_topic_user_alias, $get_current_topic_user_image, $get_current_topic_language, $get_current_topic_title, $get_current_topic_text, $get_current_topic_created, $get_current_topic_updated, $get_current_topic_updated_translated, $get_current_topic_replies, $get_current_topic_views, $get_current_topic_views_ip_block, $get_current_topic_likes, $get_current_topic_dislikes, $get_current_topic_rating, $get_current_topic_likes_ip_block, $get_current_topic_user_ip) = $row;

if($get_current_topic_id == ""){

	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "Server error 404 - $l_forum";
	include("$root/_webdesign/header.php");
	echo"<p>Blog post not found.</p>";
	
}
else{
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$get_current_topic_title - $l_forum";
	include("$root/_webdesign/header.php");
		

	// Get subscriber
	$topic_subscriber_id_mysql = quote_smart($link, $topic_subscriber_id);
	$query = "SELECT topic_subscriber_id, topic_id, topic_subscriber_user_id, topic_subscriber_user_email, topic_subscriber_last_sendt_email FROM $t_forum_topics_subscribers WHERE topic_subscriber_id=$topic_subscriber_id_mysql AND topic_id=$topic_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_topic_subscriber_id, $get_current_topic_id, $get_current_topic_subscriber_user_id, $get_current_topic_subscriber_user_email, $get_current_topic_subscriber_last_sendt_email) = $row;

	if($get_current_topic_subscriber_id == ""){
		echo"
		<h1>$get_current_topic_title</h1>

		<p>
		Subscription not found.
		</p>

		<p>
		<a href=\"view_topic.php?topic_id=$topic_id&amp;l=$l\">View topic</a>
		</p>
		";
	}
	else{
		// Delete unsubscriber
		$result = mysqli_query($link, "DELETE FROM $t_forum_topics_subscribers WHERE topic_subscriber_id=$get_current_topic_subscriber_id");

		echo"
		<h1>$get_current_topic_title</h1>

		<p>
		Subscription deleted.
		</p>

		<p>
		<a href=\"view_topic.php?topic_id=$topic_id&amp;l=$l\">View topic</a>
		</p>
		";
	}	
} //  post found



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>