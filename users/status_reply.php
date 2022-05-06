<?php
/**
*
* File: users/status_reply.php
* Version 17.46 18.02.2017
* Copyright (c) 2009-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "0";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

/*- Root dir --------------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config --------------------------------------------------------------------------- */
include("$root/_admin/website_config.php");
include("$root/_admin/_data/logo.php");

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/users/ts_index.php");

/*- Variables --------------------------------------------------------------------------- */
if(isset($_GET['status_id'])) {
	$status_id = $_GET['status_id'];
	$status_id = strip_tags(stripslashes($status_id));
}
else{
	$status_id = "";
	echo"
	<h1>Error</h1>
	
	<p>Status not found</p>
	";
	die;
}
$status_id_mysql = quote_smart($link, $status_id);



/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_reply_to_status - $l_users";
include("$root/_webdesign/header.php");

// Get status
$q = "SELECT status_id, status_user_id, status_created_by_user_id, status_created_by_user_alias, status_created_by_user_image, status_created_by_ip, status_text, status_photo, status_datetime, status_datetime_print, status_time, status_language, status_likes, status_comments, status_reported, status_reported_checked, status_reported_reason, status_seen FROM $t_users_status WHERE status_id=$status_id_mysql";
$r = mysqli_query($link, $q);
$rowb = mysqli_fetch_row($r);
list($get_status_id, $get_status_user_id, $get_status_created_by_user_id, $get_status_created_by_user_alias, $get_status_created_by_user_image, $get_status_created_by_ip, $get_status_text, $get_status_photo, $get_status_datetime, $get_status_datetime_print, $get_status_time, $get_status_language, $get_status_likes, $get_status_comments, $get_status_reported, $get_status_reported_checked, $get_status_reported_reason, $get_status_seen) = $rowb;

// Who owns that status?
$query_u = "SELECT user_id, user_email, user_alias, user_language FROM $t_users WHERE user_id=$get_status_user_id";
$result_u = mysqli_query($link, $query_u);
$row_u = mysqli_fetch_row($result_u);
list($get_status_user_id, $get_status_user_email, $get_status_user_alias, $get_status_user_language) = $row_u;



if($get_status_id == ""){
	echo"
	<h1>Error</h1>
	
	<p>Status not found</p>
	";
	
}
else{
	// Status author image
	$inp_new_x = 40; // 950
	$inp_new_y = 40; // 640
	if(file_exists("$root/_uploads/users/images/$get_status_created_by_user_id/$get_status_created_by_user_image") && $get_status_created_by_user_image != ""){
		$status_created_by_thumb_full_path = "$root/_cache/user_" . $get_status_created_by_user_image . "-" . $inp_new_x . "x" . $inp_new_y . ".png";

		if(!(file_exists("$status_created_by_thumb_full_path"))){
			resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_status_created_by_user_id/$get_status_created_by_user_image", "$status_created_by_thumb_full_path");
		}
		$status_created_by_thumb_full_path = "_cache/user_" . $get_status_created_by_user_image . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
	}
	else{
		$status_created_by_thumb_full_path = "users/_gfx/avatar_blank_40.png";
	}


	if(isset($_SESSION['user_id'])){
		$my_user_id = $_SESSION['user_id'];
		$my_user_id_mysql = quote_smart($link, $my_user_id);
		$query = "SELECT user_id, user_alias, user_date_format FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_alias, $get_my_user_date_format) = $row;

		// My image
		$q = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id=$my_user_id_mysql AND photo_profile_image='1'";
		$r = mysqli_query($link, $q);
		$rowb = mysqli_fetch_row($r);
		list($get_my_photo_id, $get_my_photo_destination) = $rowb;	

		$inp_new_x = 40; // 950
		$inp_new_y = 40; // 640
		if(file_exists("$root/_uploads/users/images/$get_my_user_id/$get_my_photo_destination") && $get_my_photo_destination != ""){
			$my_thumb_full_path = "$root/_cache/user_" . $get_my_photo_destination . "-" . $inp_new_x . "x" . $inp_new_y . ".png";

			if(!(file_exists("$my_thumb_full_path"))){
				resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_my_user_id/$get_my_photo_destination", "$my_thumb_full_path");
			}
			$my_thumb_full_path = "_cache/user_" . $get_my_photo_destination  . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
		}
		else{
			$my_thumb_full_path = "users/_gfx/avatar_blank_40.png";
		}


		if($process == "1"){
				
			// IP block
			$my_ip = $_SERVER['REMOTE_ADDR'];
			$my_ip = output_html($my_ip);
			$my_ip_mysql = quote_smart($link, $my_ip);
			
			$time = time();

			$q = "SELECT reply_id, reply_time FROM $t_users_status_replies WHERE reply_created_by_ip=$my_ip_mysql ORDER BY reply_id DESC LIMIT 0,1";
			$r = mysqli_query($link, $q);
			$rowb = mysqli_fetch_row($r);
			list($get_reply_id, $get_reply_time) = $rowb;	
				
			if($get_reply_id != ""){
				$time_since_last_reply = $time-$get_reply_time;
				$remaining = 60-$time_since_last_reply;
				if($time_since_last_reply < 60){
					$url = "status_reply.php?status_id=$status_id&l=$l&ft=error&fm=ip_block&remaining=$remaining";
					header("Location: $url");
					die;
				}
			}

			// Reply
			$inp_text = $_POST['inp_text'];
			$inp_text = output_html($inp_text);
			$inp_text_mysql = quote_smart($link, $inp_text);

			if($inp_text == ""){
				$url = "status_new.php?user_id=$user_id&l=$l&ft=error&fm=missing_text";
				header("Location: $url");
				die;
			}

			// Other variables
			$inp_my_user_alias_mysql = quote_smart($link, $get_my_user_alias);
			$inp_my_user_image_mysql = quote_smart($link, $get_my_photo_destination);

			$datetime = date("Y-m-d H:i:s");
			$datetime_print = date('j M y');

			$l_mysql = quote_smart($link, $l);

			// Insert reply
			mysqli_query($link, "INSERT INTO $t_users_status_replies
			(reply_id, reply_status_id, reply_user_id, reply_parent_id, reply_created_by_user_id, reply_created_by_user_alias, reply_created_by_user_image, reply_created_by_ip, reply_text, 
			reply_likes, reply_datetime, reply_datetime_print, reply_time, reply_reported, reply_reported_checked, reply_reported_reason, reply_seen) 
			VALUES 
			(NULL, '$get_status_id', '$get_status_user_id', '0', '$get_my_user_id', $inp_my_user_alias_mysql, $inp_my_user_image_mysql, $my_ip_mysql, $inp_text_mysql, 
			'0', '$datetime', '$datetime_print', '$time', '0', '0', '', '0')")
			or die(mysqli_error($link));

			// Get reply ID
			$q = "SELECT reply_id FROM $t_users_status_replies WHERE reply_created_by_user_id='$get_my_user_id' AND reply_datetime='$datetime'";
			$r = mysqli_query($link, $q);
			$rowb = mysqli_fetch_row($r);
			list($get_reply_id) = $rowb;	


			// We want to send e-mail to all subscribers
			$query = "SELECT subscription_id, subscription_status_id, subscription_user_id, subscription_user_email, subscription_user_alias, subscription_user_email_sent, subscription_user_email_sent_time, subscription_user_email_seen FROM $t_users_status_subscriptions WHERE subscription_status_id='$get_status_id'";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_subscription_id, $get_subscription_status_id, $get_subscription_user_id, $get_subscription_user_email, $get_subscription_user_alias, $get_subscription_user_email_sent, $get_subscription_user_email_sent_time, $get_subscription_user_email_seen) = $row;

				if($get_subscription_user_id != "$get_my_user_id"){
					if($get_subscription_user_email_seen == "" OR $get_subscription_user_email_seen == "0"){
						// Include that person, so we get correct language
						$query_u = "SELECT user_id, user_email, user_alias, user_language, user_registered_time FROM $t_users WHERE user_id=$my_user_id_mysql";
						$result_u = mysqli_query($link, $query_u);
						$row_u = mysqli_fetch_row($result_u);
						list($get_sub_user_id, $get_sub_user_email, $get_sub_user_alias, $get_sub_user_language, $get_sub_user_registered_time) = $row_u;
				
						// Lang
						include("$root/_admin/_translations/site/$get_sub_user_language/users/ts_status_reply.php");


						$subject = "$get_my_user_alias $l_answered_to_lowercase $get_status_user_alias$l_s_status_lowercase";
						$subject = str_replace("&amp;nbsp;", " ", $subject);
						$message = "$l_hello $get_sub_user_alias, 

$l_you_can_view_the_status_and_replies_by_following_the_link_below
$configSiteURLSav/users/view_profile.php?user_id=$get_status_user_id&l=$get_sub_user_language#reply$get_reply_id

--
$l_kind_regards
$configWebsiteTitleSav

$l_dont_want_any_more_emails $l_stop_your_subscriptions_by_follow_this_link
$configSiteURLSav/users/edit_subscriptions.php?action=unsubscribe_and_source_is_emails&user_id=$get_sub_user_id&registered_time=$get_sub_user_registered_time
";
						$headers = "From: $configFromEmailSav" . "\r\n" .
						    'X-Mailer: PHP/' . phpversion();

						mail($get_subscription_user_email, $subject, $message, $headers);

	
						// Update
						mysqli_query($link, "UPDATE $t_users_status_subscriptions SET 
						subscription_user_email_sent='$datetime', subscription_user_email_sent_time='$time', subscription_user_email_seen='0' 
						WHERE subscription_id=$get_subscription_id")
						or die(mysqli_error($link));

					} // not seen (to avoid spam)
				}

			}


			$url = "view_profile.php?user_id=$get_status_user_id&l=$l&ft=success&fm=reply_added#reply$get_reply_id";
			header("Location: $url");
			die;
		} // process


		echo"
		<h1>$l_reply_to_status</h1>
		<!-- Status -->
				<table style=\"width: 100%;\">
				 <tr>
				  <td style=\"width: 45px;vertical-align: top;\">
					<p><img src=\"$root/$status_created_by_thumb_full_path\" alt=\"$root/$status_created_by_thumb_full_path\" /></p>
				  </td>
				  <td class=\"status\" style=\"vertical-align: top;\">
					<p>
					$get_status_text
					</p>

				  </td>
				 </tr>
				</table>
		<!-- //Status -->
	
		<!-- My reply -->";
				// IP block
				$my_ip = $_SERVER['REMOTE_ADDR'];
				$my_ip = output_html($my_ip);
				$my_ip_mysql = quote_smart($link, $my_ip);
			
				$time = time();

				$ip_block = "false";

				$q = "SELECT reply_id, reply_time FROM $t_users_status_replies WHERE reply_created_by_ip=$my_ip_mysql ORDER BY reply_id DESC LIMIT 0,1";
				$r = mysqli_query($link, $q);
				$rowb = mysqli_fetch_row($r);
				list($get_reply_id, $get_reply_time) = $rowb;	
				
				if($get_reply_id != ""){
					$time_since_last_reply = $time-$get_reply_time;
					$remaining = 60-$time_since_last_reply;
					if($time_since_last_reply < 60){
						$ip_block  = "true";

						echo"
						<div class=\"info\"><p>$l_please_wait $remaining $l_seconds_before_posting_a_reply_lowercase</p></div>
						";
					}
				}
				if($ip_block == "false"){
					echo"
					<h2 style=\"padding-bottom:0;margin-bottom:0;\">$l_my_reply</h2>
					<form method=\"post\" action=\"status_reply.php?status_id=$get_status_id&amp;l=$l&amp;process=1\" />


					<!-- Focus -->
						<script>
						\$(document).ready(function(){
							\$('[name=\"inp_text\"]').focus();
						});
					</script>
					<!-- //Focus -->

					<table style=\"width: 100%;\">
					 <tr>
					  <td style=\"width: 45px;vertical-align: top;\">
						<p><img src=\"$root/$my_thumb_full_path\" alt=\"$my_thumb_full_path\" /></p>
					  </td>
					  <td class=\"status\" style=\"vertical-align: top;\">
						<p>
						<textarea name=\"inp_text\" rows=\"3\" cols=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea>
						</p>

						<p>
						<input type=\"submit\" value=\"$l_send\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						</p>
					  </td>
					 </tr>
					</table>

					</form>
					";
				} // ip block
				echo"
		<!-- //My reply -->

		<!-- Back -->
				<p>
				<a href=\"view_profile.php?user_id=$get_status_user_id&amp;l=$l\"><img src=\"_gfx/go-previous.png\" alt=\"go-previous.png\" /></a>
				<a href=\"view_profile.php?user_id=$get_status_user_id&amp;l=$l\">$l_previous</a>
				</p>
		<!-- //Back -->
		";

		
	} // logged in
	else{
		$url = "$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/users/stats_edit.php?status_id=$status_id";
		header("Location: $url");
		die;
	}
} // status found
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>