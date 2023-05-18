<?php 
/**
*
* File: blog/comment_like.php
* Version 1.0.0
* Date 11:30 01.11.2020
* Copyright (c) 2011-2020 S. A. Ditlefsen
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




/*- Blog config -------------------------------------------------------------------- */
include("$root/_admin/_data/blog.php");


/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['comment_id'])){
	$comment_id = $_GET['comment_id'];
	$comment_id = output_html($comment_id);
}
else{
	$comment_id = "";
}

// Get comment
$comment_id_mysql = quote_smart($link, $comment_id);
$query = "SELECT comment_id, comment_blog_post_id, comment_blog_info_id, comment_text, comment_by_user_id, comment_by_user_name, comment_by_user_image_path, comment_by_user_image_file, comment_by_user_image_thumb_60, comment_by_user_ip, comment_created, comment_created_saying, comment_created_timestamp, comment_updated, comment_updated_saying, comment_likes, comment_dislikes, comment_read_blog_owner FROM $t_blog_posts_comments WHERE comment_id=$comment_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_comment_id, $get_current_comment_blog_post_id, $get_current_comment_blog_info_id, $get_current_comment_text, $get_current_comment_by_user_id, $get_current_comment_by_user_name, $get_current_comment_by_user_image_path, $get_current_comment_by_user_image_file, $get_current_comment_by_user_image_thumb_60, $get_current_comment_by_user_ip, $get_current_comment_created, $get_current_comment_created_saying, $get_current_comment_created_timestamp, $get_current_comment_updated, $get_current_comment_updated_saying, $get_current_comment_likes, $get_current_comment_dislikes, $get_current_comment_read_blog_owner) = $row;

if($get_current_comment_id == ""){
	echo"<p>Comment not found.</p>";
}
else{
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
		$query = "SELECT likes_dislike_id, likes_dislike_post_id, likes_dislike_info_id, likes_dislike_comment_id, likes_dislike_user_id, likes_dislike_direction FROM $t_blog_posts_comments_likes_dislikes WHERE likes_dislike_comment_id=$get_current_comment_id AND likes_dislike_user_id=$get_my_user_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_likes_dislike_id, $get_likes_dislike_post_id, $get_likes_dislike_info_id, $get_likes_dislike_comment_id, $get_likes_dislike_user_id, $get_likes_dislike_direction) = $row;
		if($get_likes_dislike_id == ""){
			// Insert vote block
			mysqli_query($link, "INSERT INTO $t_blog_posts_comments_likes_dislikes 
			(likes_dislike_id, likes_dislike_post_id, likes_dislike_info_id, likes_dislike_comment_id, likes_dislike_user_id, likes_dislike_direction) 
			VALUES 
			(NULL, $get_current_comment_blog_post_id, $get_current_comment_blog_info_id, $get_current_comment_id, $get_my_user_id, 'like')")
			or die(mysqli_error($link));

			// Update blog post
			$inp_comment_likes = $get_current_comment_likes+1;
			mysqli_query($link, "UPDATE $t_blog_posts_comments SET comment_likes=$inp_comment_likes WHERE comment_id=$get_current_comment_id") or die(mysqli_error($link));

			// Header
			$url = "view_post.php?post_id=$get_current_comment_blog_post_id&l=$l&ft_comment=success&fm_comment=comment_liked#comment$get_current_comment_id";
			header("Location: $url");
			exit;
		}
		else{
			// Did i "like" it - then no changes
			if($get_likes_dislike_direction == "like"){
				// No changes
				// Header
				$url = "view_post.php?post_id=$get_current_comment_blog_post_id&l=$l&ft_comment=info&fm_comment=you_have_alreaddy_liked_the_comment#comment$get_current_comment_id";
				header("Location: $url");
				exit;
			}
			elseif($get_likes_dislike_direction == "dislike"){
				// Change from dislike to like

				// Update blog post
				$inp_comment_likes = $get_current_comment_likes+1;
				$inp_comment_dislikes = $get_current_comment_dislikes-1;
				mysqli_query($link, "UPDATE $t_blog_posts_comments SET comment_likes=$inp_comment_likes, comment_dislikes=$inp_comment_dislikes WHERE comment_id=$get_current_comment_id") or die(mysqli_error($link));

				mysqli_query($link, "UPDATE $t_blog_posts_comments_likes_dislikes SET likes_dislike_direction='like' WHERE likes_dislike_id=$get_likes_dislike_id") or die(mysqli_error($link));



				// Header
				$url = "view_post.php?post_id=$get_current_comment_blog_post_id&l=$l&ft_comment=success&fm_comment=vote_changed_from_like_to_dislike#comment$get_current_comment_id";
				header("Location: $url");
				exit;
			}
		}
		


	}
	else{
		echo"Not logged in";
	} // not logged in
} // comment found
?>