<?php 
/**
*
* File: blog/comment_reply.php
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


/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/blog/ts_index.php");
include("$root/_admin/_translations/site/$l/blog/ts_my_blog.php");

/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);

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
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$l_blog - 404";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");
	echo"<p>Comment not found.</p>";
}
else{
	// Get blog
	$query = "SELECT blog_info_id, blog_user_id, blog_language, blog_title, blog_description, blog_created, blog_updated, blog_posts, blog_comments, blog_views, blog_views_ipblock, blog_new_comments_email_warning, blog_unsubscribe_password FROM $t_blog_info WHERE blog_info_id=$get_current_comment_blog_info_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_blog_info_id, $get_current_blog_user_id, $get_current_blog_language, $get_current_blog_title, $get_current_blog_description, $get_current_blog_created, $get_current_blog_updated, $get_current_blog_posts, $get_current_blog_comments, $get_current_blog_views, $get_current_blog_views_ipblock, $get_current_blog_new_comments_email_warning, $get_current_blog_unsubscribe_password) = $row;

	// Get post
	$query = "SELECT blog_post_id, blog_post_user_id, blog_post_title_pre, blog_post_title, blog_post_language, blog_post_status, blog_post_category_id, blog_post_category_title, blog_post_introduction, blog_post_privacy_level, blog_post_text, blog_post_image_path, blog_post_image_thumb_small, blog_post_image_thumb_medium, blog_post_image_thumb_large, blog_post_image_file, blog_post_image_ext, blog_post_image_text, blog_post_ad, blog_post_created, blog_post_created_rss, blog_post_updated, blog_post_updated_rss, blog_post_allow_comments, blog_post_comments, blog_post_views, blog_post_views_ipblock, blog_post_user_ip FROM $t_blog_posts WHERE blog_post_id=$get_current_comment_blog_post_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_blog_post_id, $get_current_blog_post_user_id, $get_current_blog_post_title_pre, $get_current_blog_post_title, $get_current_blog_post_language, $get_current_blog_post_status, $get_current_blog_post_category_id, $get_current_blog_post_category_title, $get_current_blog_post_introduction, $get_current_blog_post_privacy_level, $get_current_blog_post_text, $get_current_blog_post_image_path, $get_current_blog_post_image_thumb_small, $get_current_blog_post_image_thumb_medium, $get_current_blog_post_image_thumb_large, $get_current_blog_post_image_file, $get_current_blog_post_image_ext, $get_current_blog_post_image_text, $get_current_blog_post_ad, $get_current_blog_post_created, $get_current_blog_post_created_rss, $get_current_blog_post_updated, $get_current_blog_post_updated_rss, $get_current_blog_post_allow_comments, $get_current_blog_post_comments, $get_current_blog_post_views, $get_current_blog_post_views_ipblock, $get_current_blog_post_user_ip) = $row;
	
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


		if($process == "1"){
			$inp_text = $_POST['inp_text'];
			$inp_text = output_html($inp_text);
			$inp_text_mysql = quote_smart($link, $inp_text);

			$inp_my_user_name = output_html($get_my_user_name);
			$inp_my_user_name_mysql = quote_smart($link, $inp_my_user_name);

			$inp_my_image_path = output_html("_uploads/users/images/$get_my_user_id");
			$inp_my_image_path_mysql = quote_smart($link, $inp_my_image_path);

			$inp_my_image_file = output_html($get_my_photo_destination);
			$inp_my_image_file_mysql = quote_smart($link, $inp_my_image_file);

			$inp_my_image_thumb = output_html($get_my_photo_thumb_60);
			$inp_my_image_thumb_mysql = quote_smart($link, $inp_my_image_thumb);

			$datetime = date("Y-m-d H:i:s");
			$date_saying = date("j M Y");
			$year = date("Y");
			$month = date("m");
			$month_full = date("F");
			$month_short = date("M");


			$my_ip = $_SERVER['REMOTE_ADDR'];
			$my_ip = output_html($my_ip);
			$my_ip_mysql = quote_smart($link, $my_ip);


			if($inp_text == ""){
				$url = "comment_reply.php?comment_id=$get_current_comment_id&l=$l&amp;ft_comment=warning&fm_comment=missing_text";
				header("Location: $url");
				exit;
			} // no text 
			else{
				// Insert reply
				mysqli_query($link, "INSERT INTO $t_blog_posts_comments_replies
				(reply_id, reply_comment_id, reply_blog_post_id, reply_blog_info_id, reply_text, 
				reply_by_user_id, reply_by_user_name, reply_by_user_image_path, reply_by_user_image_file, reply_by_user_image_thumb_60, 
				reply_by_user_ip, reply_created, reply_created_saying, reply_created_timestamp, reply_updated, 
				reply_updated_saying, reply_likes, reply_dislikes, reply_number_of_replies, reply_read_blog_owner, 
				reply_reported) 
				VALUES 
				(NULL, $get_current_comment_id, $get_current_blog_post_id, $get_current_blog_info_id, $inp_text_mysql, 
				$get_my_user_id, $inp_my_user_name_mysql, $inp_my_image_path_mysql, $inp_my_image_file_mysql, $inp_my_image_thumb_mysql, 
				$my_ip_mysql, '$datetime', '$date_saying', '$time', '$datetime', 
				'$date_saying',	0, 0,  0, 0,
				0)")
				or die(mysqli_error($link));

				// Get ID
				$query = "SELECT reply_id FROM $t_blog_posts_comments_replies WHERE reply_created='$datetime'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_reply_id) = $row;


				// Stats :: Comments
				$year = date("Y");
				$month = date("m");
				$month_full = date("F");
				$month_short = date("M");
				$week = date("W");

				// Stats :: Comments :: Year
				$query = "SELECT stats_comments_id, stats_comments_comments_written FROM $t_stats_comments_per_year WHERE stats_comments_year='$year' AND stats_comments_language=$l_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_stats_comments_id, $get_stats_comments_comments_written) = $row;
				if($get_stats_comments_id == ""){
					mysqli_query($link, "INSERT INTO $t_stats_comments_per_year 
					(stats_comments_id, stats_comments_year, stats_comments_language, stats_comments_comments_written) 
					VALUES 
					(NULL, $year, $l_mysql, 1)")
					or die(mysqli_error($link));
				}
				else{
					$inp_counter = $get_stats_comments_comments_written+1;
					mysqli_query($link, "UPDATE $t_stats_comments_per_year 
								SET stats_comments_comments_written=$inp_counter
								WHERE stats_comments_id=$get_stats_comments_id")
								or die(mysqli_error($link));
				}

				// Stats :: Comments :: Month
				$query = "SELECT stats_comments_id, stats_comments_comments_written FROM $t_stats_comments_per_month WHERE stats_comments_month='$month' AND stats_comments_year='$year' AND stats_comments_language=$l_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_stats_comments_id, $get_stats_comments_comments_written) = $row;
				if($get_stats_comments_id == ""){
					mysqli_query($link, "INSERT INTO $t_stats_comments_per_month 
					(stats_comments_id, stats_comments_month, stats_comments_month_full, stats_comments_month_short, stats_comments_year, stats_comments_language, stats_comments_comments_written) 
					VALUES 
					(NULL, $month, '$month_full', '$month_short', $year, $l_mysql, 1)")
					or die(mysqli_error($link));
				}
				else{
					$inp_counter = $get_stats_comments_comments_written+1;
					mysqli_query($link, "UPDATE $t_stats_comments_per_month 
								SET stats_comments_comments_written=$inp_counter
								WHERE stats_comments_id=$get_stats_comments_id")
								or die(mysqli_error($link));
				}

				// Stats :: Comments :: Week
				$query = "SELECT stats_comments_id, stats_comments_comments_written FROM $t_stats_comments_per_week WHERE stats_comments_week='$week' AND stats_comments_year='$year' AND stats_comments_language=$l_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_stats_comments_id, $get_stats_comments_comments_written) = $row;
				if($get_stats_comments_id == ""){
					mysqli_query($link, "INSERT INTO $t_stats_comments_per_week 
					(stats_comments_id, stats_comments_week, stats_comments_month, stats_comments_year, stats_comments_language, stats_comments_comments_written) 
					VALUES 
					(NULL, $week, $month, $year, $l_mysql, 1)")
					or die(mysqli_error($link));
				}
				else{
					$inp_counter = $get_stats_comments_comments_written+1;
					mysqli_query($link, "UPDATE $t_stats_comments_per_week
								SET stats_comments_comments_written=$inp_counter
								WHERE stats_comments_id=$get_stats_comments_id")
								or die(mysqli_error($link));
				}

				// Refresh site
				$url = "view_post.php?post_id=$get_current_blog_post_id&l=$l&ft_comment=success&fm_comment=reply_saved#reply$get_current_reply_id";
				header("Location: $url");
				exit;
			} // text 
			

		} // process == 1


		echo"
		<h1>$l_reply_to_comment</h1>

		<!-- Where am I? -->
			<p><b>$l_you_are_here:</b><br />
			<a href=\"view_blog.php?info_id=$get_current_blog_info_id&amp;l=$l\">$get_current_blog_title</a>
			&gt;
			<a href=\"view_post.php?post_id=$get_current_blog_post_id&amp;l=$l\">$get_current_blog_post_title</a>
			&gt;
			<a href=\"view_post.php?post_id=$get_current_blog_post_id&amp;l=$l#comment$get_current_comment_id\">$l_comment $get_current_comment_id</a>
			&gt;
			<a href=\"comment_reply.php?comment_id=$get_current_comment_id&amp;l=$l\">$l_reply_to_comment $get_current_comment_id</a>
			</p>
		<!-- //Where am I? -->
	
		<!-- View comment -->
			<p><b>$l_original_comment:</b></p>
			<table>
			 <tr>
			  <td style=\"vertical-align: top;padding-right: 10px;text-align:center;\">
				<p>
				";
				if(file_exists("$root/$get_current_comment_by_user_image_path/$get_current_comment_by_user_image_thumb_60") && $get_current_comment_by_user_image_thumb_60 != ""){

				
					echo"
					<a href=\"users/view_profile.php?user_id=$get_current_comment_by_user_id&amp;l=$l\"><img src=\"$root/$get_current_comment_by_user_image_path/$get_current_comment_by_user_image_thumb_60\" alt=\"$get_current_comment_by_user_image_thumb_60\" /></a>
					<br />
					";
				}
				echo"
				</p>
			  </td>
			  <td style=\"vertical-align: top;\">
				<p>
				<a href=\"users/view_profile.php?user_id=$get_current_comment_by_user_id&amp;l=$l\" style=\"font-weight: bold;\">$get_current_comment_by_user_name</a> 
				$get_current_comment_created_saying<br />
				</p>

				<p>
				$get_current_comment_text
				</p>
			  </td>
			 </tr>
		<!-- //View comment -->
	
		<!-- Reply comment form -->

	 		 <tr>
			  <td colspan=\"2\">
				<p style=\"padding-top: 16px\"><b>$l_my_reply:</b></p>
			  </td>
			 </tr>
	 		 <tr>
			  <td style=\"vertical-align: top;padding-right: 10px;text-align:center;\">
				<p>
				";
				if(file_exists("$root/_uploads/users/images/$get_my_user_id/$get_my_photo_destination") && $get_my_photo_destination != ""){

					// Thumb
					if(!(file_exists("$root/_uploads/users/images/$get_my_user_id/$get_my_photo_thumb_60"))){
						resize_crop_image(60, 60, "$root/_uploads/users/images/$get_my_user_id/$get_my_photo_destination", "$root/_uploads/users/images/$get_my_user_id/$get_my_photo_thumb_60");
					}
					echo"
					<img src=\"$root/_uploads/users/images/$get_my_user_id/$get_my_photo_thumb_60\" alt=\"$get_my_photo_thumb_60\" />
					<br />
					";
				}
				echo"
				$get_my_user_name
				</p>
			  </td>
			  <td style=\"vertical-align: top;\">
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_text\"]').focus();
				});
				</script>
				<form method=\"post\" action=\"comment_reply.php?comment_id=$get_current_comment_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
				<p>
				<textarea name=\"inp_text\" rows=\"5\" cols=\"80\"></textarea><br />
				<input type=\"submit\" value=\"$l_save\" class=\"btn_default\" />
				</p>
				</form>
			  </td>
			 </tr>
			</table>

		<!-- //Reply comment form -->
		";
	} // is logged in
	else{
		echo"<p>Not logged in.</p>";
	}
} // comment found


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>