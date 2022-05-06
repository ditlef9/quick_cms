<?php 
/**
*
* File: blog/view_post_include_new_comment.php
* Version 1.0.0
* Date 12:05 10.02.2018
* Copyright (c) 2011-2018 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
if(!(isset($get_current_blog_post_id))){
	echo"error";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */



// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	// Link
	if($process != "1"){
		echo"
		<a id=\"new_comment_form\"></a>
		";
	}
	
	// Dates
	$time = time();

	// IP Check
	$q = "SELECT comment_id, comment_created_timestamp FROM $t_blog_posts_comments WHERE comment_by_user_ip=$my_ip_mysql ORDER BY comment_id DESC LIMIT 0,1";
	$r = mysqli_query($link, $q);
	$rowb = mysqli_fetch_row($r);
	list($get_comment_id, $get_comment_created_timestamp) = $rowb;	
	
	$can_comment = 1;

	if($get_comment_id != ""){
		$time_since_written = $time-$get_comment_created_timestamp;
		$remaining = 60-$time_since_written;
		if($time_since_written < 60){ // 60 seconds
			$can_comment = 0;
			
		}
	}

	if($can_comment == 1){

		// Post comment
		if($action == "post_comment"){

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
			$month_full = date("F");
			$month_short = date("M");

			if($inp_text == ""){
				$url = "view_post.php?post_id=$get_current_blog_post_id&amp;l=$l&amp;ft_comment=warning&fm_comment=missing_text#new_blog_comment_form";
				header("Location: $url");
				exit;
			} // no text 
			else{

				
				// Insert comment
				mysqli_query($link, "INSERT INTO $t_blog_posts_comments
				(comment_id, comment_blog_post_id, comment_blog_info_id, comment_text, comment_by_user_id, 
				comment_by_user_name, comment_by_user_image_path, comment_by_user_image_file, comment_by_user_image_thumb_60, comment_by_user_ip, 
				comment_created, comment_created_saying, comment_created_timestamp, comment_updated, comment_updated_saying, 
				comment_read_blog_owner, comment_reported) 
				VALUES 
				(NULL, $get_current_blog_post_id, $get_current_blog_info_id, $inp_text_mysql, $get_my_user_id, 
				$inp_my_user_name_mysql, $inp_my_image_path_mysql, $inp_my_image_file_mysql, $inp_my_image_thumb_mysql, $my_ip_mysql, 
				'$datetime', '$date_saying', '$time', '$datetime', '$date_saying',
				0, 0)")
				or die(mysqli_error($link));

				// Get ID
				$query = "SELECT comment_id FROM $t_blog_posts_comments WHERE comment_created='$datetime'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_comment_id) = $row;



				// Email to blog owner
				if($get_current_blog_new_comments_email_warning == "1" && $configMailSendActiveSav == "1" && $get_my_user_id != "$get_current_blog_user_id"){

					// Get blog owner user
					$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$get_current_blog_user_id";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_blog_owner_user_id, $get_blog_owner_user_email, $get_blog_owner_user_name, $get_blog_owner_user_alias, $get_blog_owner_user_rank) = $row;

					// Get blog logo
					$query = "SELECT logo_id, logo_blog_info_id, logo_user_id, logo_path, logo_thumb, logo_file, logo_uploaded_datetime, logo_uploaded_ip, logo_reported, logo_reported_checked FROM $t_blog_logos WHERE logo_blog_info_id=$get_current_blog_info_id";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_logo_id, $get_logo_blog_info_id, $get_logo_user_id, $get_logo_path, $get_logo_thumb, $get_logo_file, $get_logo_uploaded_datetime, $get_logo_uploaded_ip, $get_logo_reported, $get_logo_reported_checked) = $row;

					
					// Send mail
					$host = $_SERVER['HTTP_HOST'];

					$subject = "$l_new_comment_for_entry $get_current_blog_post_title $l_at_lowercase $get_current_blog_title";
			
					$message = "<html>\n";
					$message = $message. "<head>\n";
					$message = $message. "  <title>$subject</title>\n";
					$message = $message. " </head>\n";
					$message = $message. "<body>\n";

					if($get_logo_id != "" && file_exists("$root/$get_logo_path/$get_logo_file")){
						$message = $message . "<p><a href=\"$configSiteURLSav/blog/view_blog.php?info_id=$get_current_blog_info_id\"><img src=\"$configSiteURLSav/$get_logo_path/$get_logo_file\" alt=\"($get_current_blog_title logo)\" /></a></p>\n\n";
					}
					$message = $message . "<h1>$l_new_comment_for_entry $get_current_blog_post_title $l_at_lowercase $get_current_blog_title</h1>\n\n";
					$message = $message . "<table>\n";
					$message = $message . " <tr>\n";
					$message = $message . "  <td style=\"padding: 0px 6px 0px 0x;text-align:center;vertical-align:top;\">\n";
					$message = $message . "		<p>\n";
					if(file_exists("$root/_uploads/users/images/$get_my_user_id/$get_my_photo_destination") && $get_my_photo_destination != ""){
						$message = $message . "		<a href=\"$configSiteURLSav/users/view_profile.php?user_id=$get_my_user_id\"><img src=\"$configSiteURLSav/_uploads/users/images/$get_my_user_id/$get_my_photo_thumb_60\" alt=\"$get_my_photo_thumb_60\" /></a><br />\n";

					}
					$message = $message . "		<a href=\"$configSiteURLSav/users/view_profile.php?user_id=$get_my_user_id\">$get_my_user_name</a>\n";
					$message = $message . "		</p>\n\n";
					$message = $message . "  </td>\n";
					$message = $message . "  <td style=\"padding: 0px 6px 0px 0x;vertical-align:top;\">\n";
					$message = $message . "		<p>$inp_text</p>\n\n";
					$message = $message . "  </td>\n";
					$message = $message . " </tr>\n";
					$message = $message . "</table>\n\n";
					$message = $message . "<p><b>$l_actions:</b><br />\n";
					$message = $message . "1. <a href=\"$configSiteURLSav/blog/view_post.php?post_id=$get_current_blog_post_id\">$l_view_post</a><br />\n";
					$message = $message . "2. <a href=\"$configSiteURLSav/blog/view_post.php?post_id=$get_current_blog_post_id#comment$get_current_comment_id\">$l_view_comment</a><br />\n";
					$message = $message . "3. <a href=\"$configSiteURLSav/blog/comment_reply.php?comment_id=$get_current_comment_id\">$l_reply_to_comment</a><br />\n";
					$message = $message . "5. <a href=\"$configSiteURLSav/blog/comment_mark_as_spam.php?comment_id=$get_current_comment_id\">$l_mark_as_spam</a><br />\n";
					$message = $message . "</p>\n\n";

			

					$message = $message . "<p>\n\n--<br />\nBest regards<br />\n$configWebsiteTitleSav<br />\n<a href=\"$configSiteURLSav\">$configSiteURLSav</a></p>";
					$message = $message . "<p><a href=\"$configSiteURLSav/blog/unsubscribe_from_new_comments_warning.php?info_id=$get_current_blog_info_id&amp;unsubscribe_password=$get_current_blog_unsubscribe_password\">$l_unsubscribe</a></p>";
					$message = $message. "</body>\n";
					$message = $message. "</html>\n";



					// Preferences for Subject field
					$headers = 'MIME-Version: 1.0\n';
					$headers = $headers . 'Content-type: text/html; charset=utf-8\n';
					$headers = $headers . "From: $configFromNameSav <" . $configFromEmailSav . ">";
					mail($get_blog_owner_user_email, $subject, $message, $headers);
				
				} // $get_current_blog_new_comments_email_warning == 1

				// Stats :: Comments :: Year
				$year = date("Y");
				$month = date("m");
				$month_full = date("F");
				$month_short = date("M");
				$week = date("W");

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
				$url = "view_post.php?post_id=$get_current_blog_post_id&l=$l&ft_comment=success&fm_comment=comment_saved#comment$get_current_comment_id";
				header("Location: $url");
				exit;
				/*
				echo"
				<h2><img src=\"_gfx/loading.gif\" alt=\"loading.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" /> $l_posting_comment..</h1>
				<p>$l_thank_you!</p>

				<p>$l_your_comment_is_saved.</p>
				<meta http-equiv=\"refresh\" content=\"1;url=$configSiteURLSav/blog/view_post.php?post_id=$get_current_blog_post_id#comment$get_current_comment_id&amp;l=$l\">
				";
				*/
			} // text 
			

		} // action == post_comment
		echo"
		<!-- New blog comment form -->
			<hr />
			<h2>$l_write_a_comment</h2>
			<form method=\"post\" action=\"view_post.php?post_id=$get_current_blog_post_id&amp;action=post_comment&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
		
			<table>
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
				<p>
				<textarea name=\"inp_text\" rows=\"5\" cols=\"80\" style=\"width: 90%;\"></textarea><br />
				<input type=\"submit\" value=\"$l_save_comment\" class=\"btn_default\" />
				</p>
			  </td>
			 </tr>
			</table>

			</form>
		<!-- //New blog comment form -->
		";
	} // can comment
} // logged in
else{
	echo"
	<p>
	<a href=\"$root/users/login.php?l=$l&amp;referer=../blog/view_post.php?post_id=$get_current_blog_post_id\" class=\"btn_default\">$l_login_to_post_comment</a>
	<a href=\"$root/users/create_free_account.php?l=$l\" class=\"btn_default\">$l_create_new_account</a>
	";
}
?>