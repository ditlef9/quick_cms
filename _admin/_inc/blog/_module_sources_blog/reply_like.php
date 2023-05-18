<?php 
/**
*
* File: blog/reply_like.php
* Version 1.0.0
* Date 11:51 01.11.2020
* Copyright (c) 2020 S. A. Ditlefsen
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

/*- Tables ---------------------------------------------------------------------------- */
include("_tables_blog.php");


/*- Tables ---------------------------------------------------------------------------- */

$t_blog_liquidbase			= $mysqlPrefixSav . "blog_liquidbase";

$t_blog_info 				= $mysqlPrefixSav . "blog_info";
$t_blog_default_categories		= $mysqlPrefixSav . "blog_default_categories";
$t_blog_categories			= $mysqlPrefixSav . "blog_categories";
$t_blog_posts 				= $mysqlPrefixSav . "blog_posts";
$t_blog_posts_tags 			= $mysqlPrefixSav . "blog_posts_tags";
$t_blog_posts_comments			= $mysqlPrefixSav . "blog_posts_comments";
$t_blog_posts_comments_likes_dislikes	= $mysqlPrefixSav . "blog_posts_comments_likes_dislikes";

$t_blog_posts_comments_replies			= $mysqlPrefixSav . "blog_posts_comments_replies";
$t_blog_posts_comments_replies_likes_dislikes	= $mysqlPrefixSav . "blog_posts_comments_replies_likes_dislikes";

$t_blog_images 				= $mysqlPrefixSav . "blog_images";
$t_blog_logos				= $mysqlPrefixSav . "blog_logos";

$t_blog_links_index			= $mysqlPrefixSav . "blog_links_index";
$t_blog_links_categories		= $mysqlPrefixSav . "blog_links_categories";

$t_blog_ping_list_per_blog		= $mysqlPrefixSav . "blog_ping_list_per_blog";

$t_blog_stats_most_used_categories	= $mysqlPrefixSav . "blog_stats_most_used_categories";

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/blog/ts_index.php");
include("$root/_admin/_translations/site/$l/blog/ts_my_blog.php");

/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);

/*- Blog config -------------------------------------------------------------------- */
include("$root/_admin/_data/blog.php");


/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['reply_id'])){
	$reply_id = $_GET['reply_id'];
	$reply_id = output_html($reply_id);
}
else{
	$reply_id = "";
}

// Get reply
$reply_id_mysql = quote_smart($link, $reply_id);
$query = "SELECT reply_id, reply_comment_id, reply_blog_post_id, reply_blog_info_id, reply_text, reply_by_user_id, reply_by_user_name, reply_by_user_image_path, reply_by_user_image_file, reply_by_user_image_thumb_60, reply_by_user_ip, reply_created, reply_created_saying, reply_created_timestamp, reply_updated, reply_updated_saying, reply_likes, reply_dislikes, reply_number_of_replies, reply_read_blog_owner FROM $t_blog_posts_comments_replies WHERE reply_id=$reply_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_reply_id, $get_current_reply_comment_id, $get_current_reply_blog_post_id, $get_current_reply_blog_info_id, $get_current_reply_text, $get_current_reply_by_user_id, $get_current_reply_by_user_name, $get_current_reply_by_user_image_path, $get_current_reply_by_user_image_file, $get_current_reply_by_user_image_thumb_60, $get_current_reply_by_user_ip, $get_current_reply_created, $get_current_reply_created_saying, $get_current_reply_created_timestamp, $get_current_reply_updated, $get_current_reply_updated_saying, $get_current_reply_likes, $get_current_reply_dislikes, $get_current_reply_number_of_replies, $get_current_reply_read_blog_owner) = $row;

if($get_current_reply_id == ""){
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$l_blog - 404";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");
	echo"<p>Reply not found.</p>";
}
else{
	// Get blog
	$query = "SELECT blog_info_id, blog_user_id, blog_language, blog_title, blog_description, blog_created, blog_updated, blog_posts, blog_comments, blog_views, blog_views_ipblock, blog_new_comments_email_warning, blog_unsubscribe_password FROM $t_blog_info WHERE blog_info_id=$get_current_reply_blog_info_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_blog_info_id, $get_current_blog_user_id, $get_current_blog_language, $get_current_blog_title, $get_current_blog_description, $get_current_blog_created, $get_current_blog_updated, $get_current_blog_posts, $get_current_blog_comments, $get_current_blog_views, $get_current_blog_views_ipblock, $get_current_blog_new_comments_email_warning, $get_current_blog_unsubscribe_password) = $row;

	// Get post
	$query = "SELECT blog_post_id, blog_post_user_id, blog_post_title_pre, blog_post_title, blog_post_language, blog_post_status, blog_post_category_id, blog_post_category_title, blog_post_introduction, blog_post_privacy_level, blog_post_text, blog_post_image_path, blog_post_image_thumb_small, blog_post_image_thumb_medium, blog_post_image_thumb_large, blog_post_image_file, blog_post_image_ext, blog_post_image_text, blog_post_ad, blog_post_created, blog_post_created_rss, blog_post_updated, blog_post_updated_rss, blog_post_allow_comments, blog_post_comments, blog_post_views, blog_post_views_ipblock, blog_post_user_ip FROM $t_blog_posts WHERE blog_post_id=$get_current_reply_blog_post_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_blog_post_id, $get_current_blog_post_user_id, $get_current_blog_post_title_pre, $get_current_blog_post_title, $get_current_blog_post_language, $get_current_blog_post_status, $get_current_blog_post_category_id, $get_current_blog_post_category_title, $get_current_blog_post_introduction, $get_current_blog_post_privacy_level, $get_current_blog_post_text, $get_current_blog_post_image_path, $get_current_blog_post_image_thumb_small, $get_current_blog_post_image_thumb_medium, $get_current_blog_post_image_thumb_large, $get_current_blog_post_image_file, $get_current_blog_post_image_ext, $get_current_blog_post_image_text, $get_current_blog_post_ad, $get_current_blog_post_created, $get_current_blog_post_created_rss, $get_current_blog_post_updated, $get_current_blog_post_updated_rss, $get_current_blog_post_allow_comments, $get_current_blog_post_comments, $get_current_blog_post_views, $get_current_blog_post_views_ipblock, $get_current_blog_post_user_ip) = $row;
	
	// Get comment
	$query = "SELECT comment_id, comment_blog_post_id, comment_blog_info_id, comment_text, comment_by_user_id, comment_by_user_name, comment_by_user_image_path, comment_by_user_image_file, comment_by_user_image_thumb_60, comment_by_user_ip, comment_created, comment_created_saying, comment_created_timestamp, comment_updated, comment_updated_saying, comment_likes, comment_dislikes, comment_read_blog_owner, comment_marked_as_spam, comment_spam_reason, comment_spam_checked FROM $t_blog_posts_comments WHERE comment_id=$get_current_reply_comment_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_comment_id, $get_current_comment_blog_post_id, $get_current_comment_blog_info_id, $get_current_comment_text, $get_current_comment_by_user_id, $get_current_comment_by_user_name, $get_current_comment_by_user_image_path, $get_current_comment_by_user_image_file, $get_current_comment_by_user_image_thumb_60, $get_current_comment_by_user_ip, $get_current_comment_created, $get_current_comment_created_saying, $get_current_comment_created_timestamp, $get_current_comment_updated, $get_current_comment_updated_saying, $get_current_comment_likes, $get_current_comment_dislikes, $get_current_comment_read_blog_owner, $get_current_comment_marked_as_spam, $get_current_comment_spam_reason, $get_current_comment_spam_checked) = $row;

	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$l_blog - $get_current_blog_title - $get_current_blog_post_title";
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
		list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;

		$query = "SELECT photo_id, photo_destination, photo_thumb_60 FROM $t_users_profile_photo WHERE photo_user_id=$my_user_id_mysql AND photo_profile_image='1'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_60) = $row;


		// Alreaddy voted?
		$query = "SELECT likes_dislike_id, likes_dislike_direction FROM $t_blog_posts_comments_replies_likes_dislikes WHERE likes_dislike_reply_id=$get_current_reply_id AND likes_dislike_user_id=$get_my_user_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_likes_dislike_id, $get_likes_dislike_direction) = $row;
		if($get_likes_dislike_id == ""){
			// Insert vote block
			mysqli_query($link, "INSERT INTO $t_blog_posts_comments_replies_likes_dislikes 
			(likes_dislike_id, likes_dislike_post_id, likes_dislike_info_id, likes_dislike_comment_id, likes_dislike_reply_id, likes_dislike_user_id, likes_dislike_direction) 
			VALUES 
			(NULL, $get_current_comment_blog_post_id, $get_current_comment_blog_info_id, $get_current_comment_id, $get_current_reply_id, $get_my_user_id, 'like')")
			or die(mysqli_error($link));

			// Update blog post
			$inp_reply_likes = $get_current_reply_likes+1;
			mysqli_query($link, "UPDATE $t_blog_posts_comments_replies SET reply_likes=$inp_reply_likes WHERE reply_id=$get_current_reply_id") or die(mysqli_error($link));

			// Header
			$url = "view_post.php?post_id=$get_current_comment_blog_post_id&l=$l&ft_comment=success&fm_comment=reply_liked#reply$get_current_reply_id";
			header("Location: $url");
			exit;
		}
		else{
			// Did i "like" it - then no changes
			if($get_likes_dislike_direction == "like"){
				// No changes
				// Header
				$url = "view_post.php?post_id=$get_current_comment_blog_post_id&l=$l&ft_comment=info&fm_comment=you_have_alreaddy_liked_the_reply#reply$get_current_reply_id";
				header("Location: $url");
				exit;
			}
			elseif($get_likes_dislike_direction == "dislike"){
				// Change from dislike to like

				// Update blog post
				$inp_reply_likes = $get_current_reply_likes+1;
				$inp_reply_dislikes = $get_current_reply_dislikes-1;
				mysqli_query($link, "UPDATE $t_blog_posts_comments_replies SET reply_likes=$inp_reply_likes, reply_dislikes=$inp_reply_dislikes WHERE reply_id=$get_current_reply_id") or die(mysqli_error($link));

				mysqli_query($link, "UPDATE $t_blog_posts_comments_replies_likes_dislikes SET likes_dislike_direction='like' WHERE likes_dislike_id=$get_likes_dislike_id") or die(mysqli_error($link));



				// Header
				$url = "view_post.php?post_id=$get_current_comment_blog_post_id&l=$l&ft_comment=success&fm_comment=vote_changed_from_like_to_dislike#reply$get_current_reply_id";
				header("Location: $url");
				exit;
			}
		}
		

	}
	else{
		echo"<p>Not logged in.</p>";
	}
} // reply found


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>