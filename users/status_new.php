<?php
/**
*
* File: users/status_new.php
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
if(isset($_GET['user_id'])) {
	$user_id = $_GET['user_id'];
	$user_id = strip_tags(stripslashes($user_id));
}
else{
	$user_id = "";
	echo"
	<h1>Error</h1>
	
	<p>$l_user_profile_not_found</p>
	";
	die;
}
$user_id_mysql = quote_smart($link, $user_id);


// Get user
$query = "SELECT user_id, user_email, user_name, user_alias, user_language, user_rank, user_points, user_points_rank, user_gender, user_dob, user_registered, user_registered_time, user_last_online, user_last_online_time FROM $t_users WHERE user_id=$user_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_user_id, $get_current_user_email, $get_current_user_name, $get_current_user_alias, $get_current_user_language, $get_current_user_rank, $get_current_user_points, $get_current_user_points_rank, $get_current_user_gender, $get_current_user_dob, $get_current_user_registered, $get_current_user_registered_time, $get_current_user_last_online, $get_current_user_last_online_time) = $row;



/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_new_status - $l_users";
include("$root/_webdesign/header.php");




if($get_current_user_id == ""){
	echo"
	<h1>Error</h1>
	
	<p>$l_user_profile_not_found</p>
	";
	
}
else{
	
	if(isset($_SESSION['user_id'])){
		$my_user_id = $_SESSION['user_id'];
		$my_user_id_mysql = quote_smart($link, $my_user_id);
		$query = "SELECT user_id, user_email, user_alias, user_date_format FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_alias, $get_my_user_date_format) = $row;

		// My image
		$q = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id=$my_user_id_mysql AND photo_profile_image='1'";
		$r = mysqli_query($link, $q);
		$rowb = mysqli_fetch_row($r);
		list($get_my_photo_id, $get_my_photo_destination) = $rowb;	

		$inp_new_x = 40; // 950
		$inp_new_y = 40; // 640
		if(file_exists("$root/_uploads/users/images/$get_my_user_id/$get_my_photo_destination") && $get_my_photo_destination != ""){
			$thumb_full_path = "$root/_cache/user_" . $get_my_photo_destination . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
			if(!(file_exists("$thumb_full_path"))){
				resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_my_user_id/$get_my_photo_destination", "$thumb_full_path");
			}
			$thumb_full_path = "_cache/user_" . $get_my_photo_destination . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
		}
		else{
			$thumb_full_path = "users/_gfx/avatar_blank_40.png";
		}

		if($process == "1"){
			// IP block
			$my_ip = $_SERVER['REMOTE_ADDR'];
			$my_ip = output_html($my_ip);
			$my_ip_mysql = quote_smart($link, $my_ip);
			
			$time = time();

			$ip_block = "false";

			$q = "SELECT status_id, status_time FROM $t_users_status WHERE status_created_by_ip=$my_ip_mysql ORDER BY status_id DESC LIMIT 0,1";
			$r = mysqli_query($link, $q);
			$rowb = mysqli_fetch_row($r);
			list($get_status_id, $get_status_time) = $rowb;	

			if($get_status_id != ""){
				$time_since_last_status = $time-$get_status_time;
				$remaining = 60-$time_since_last_status;
				if($time_since_last_status < 60){
					$ip_block = "true";
				}
			}
			if($ip_block == "true"){
				$url = "status_new.php?user_id=$user_id&l=$l&ft=error&fm=ip_block&remaining=$remaining";
				header("Location: $url");
				die;
			}
			

			// Comment
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

			// Insert status
			mysqli_query($link, "INSERT INTO $t_users_status
			(status_id, status_user_id, status_created_by_user_id, status_created_by_user_alias, status_created_by_user_image, status_created_by_ip, status_text, status_photo, 
			status_datetime, status_datetime_print, status_time, status_language, status_likes, status_comments, status_reported, status_reported_checked, status_reported_reason, status_seen) 
			VALUES 
			(NULL, '$get_current_user_id', '$get_my_user_id', $inp_my_user_alias_mysql, $inp_my_user_image_mysql, $my_ip_mysql, $inp_text_mysql, '', 
			'$datetime', '$datetime_print', '$time', $l_mysql, '0', '0', '0', '0', '', '0')")
			or die(mysqli_error($link));

			// Get status ID
			$q = "SELECT status_id FROM $t_users_status WHERE status_user_id=$get_current_user_id AND status_datetime='$datetime'";
			$r = mysqli_query($link, $q);
			$rowb = mysqli_fetch_row($r);
			list($get_status_id) = $rowb;	


			// Add e-mail subscription for myself
			$q = "SELECT es_id, es_user_id, es_type, es_on_off FROM $t_users_email_subscriptions WHERE es_user_id='$get_my_user_id' AND es_type='status_replies'";
			$r = mysqli_query($link, $q);
			$rowb = mysqli_fetch_row($r);
			list($get_my_es_id, $get_my_es_user_id, $get_my_es_type, $get_my_es_on_off) = $rowb;
			if($get_my_es_id == ""){
				// Insert email subscriptions
				mysqli_query($link, "INSERT INTO $t_users_email_subscriptions
				(es_id, es_user_id, es_type, es_on_off) 
				VALUES 
				(NULL, '$get_my_user_id', 'status_replies', '1')")
				or die(mysqli_error($link));
			}
			if($get_my_es_on_off == "1"){
				// Add subscription for myself
				$inp_my_user_email_mysql = quote_smart($link, $get_my_user_email);

				mysqli_query($link, "INSERT INTO $t_users_status_subscriptions
				(subscription_id, subscription_status_id, subscription_user_id, subscription_user_email, subscription_user_alias) 
				VALUES 
				(NULL, '$get_status_id', '$get_my_user_id', $inp_my_user_email_mysql, $inp_my_user_alias_mysql)")
				or die(mysqli_error($link));
			
			}




			// E-mail to the person who recieved the status?
			$q = "SELECT es_id, es_user_id, es_type, es_on_off FROM $t_users_email_subscriptions WHERE es_user_id='$get_current_user_id' AND es_type='status_comments'";
			$r = mysqli_query($link, $q);
			$rowb = mysqli_fetch_row($r);
			list($get_es_id, $get_es_user_id, $get_es_type, $get_es_on_off) = $rowb;
			if($get_es_id == ""){
				// Insert email subscriptions
				mysqli_query($link, "INSERT INTO $t_users_email_subscriptions
				(es_id, es_user_id, es_type, es_on_off) 
				VALUES 
				(NULL, '$get_current_user_id', 'status_comments', '1')")
				or die(mysqli_error($link));
			}

			if($get_es_on_off == "1"){

				
				// Include his language
				include("$root/_admin/_translations/site/$get_current_user_language/users/ts_users.php");
			
				$subject = $configWebsiteTitleSav . " - $get_my_user_alias $l_has_said_hello_lowercase";
				$subject = str_replace("&aelig;", "æ", $subject);
				$subject = str_replace("&oslash;", "ø", $subject);
				$subject = str_replace("&aring;", "å", $subject);
			
				$message = "<html>\n";
				$message = $message. "<head>\n";
				$message = $message. "  <title>$subject</title>\n";
				$message = $message. "  <style type=\"text/css\">\n";
				$message = $message. "  h1 {\n";
				$message = $message. "  		font: normal 20px Tahoma, Arial, Helvetica, sans-serif;\n";
				$message = $message. "  	}\n";
				$message = $message. "  	p {\n";
				$message = $message. "  		font: normal 16px Tahoma, Arial, Helvetica, sans-serif;\n";
				$message = $message. "  	}\n";
				$message = $message. "  	a {\n";
				$message = $message. "  		font: normal 16px Tahoma, Arial, Helvetica, sans-serif;\n";
				$message = $message. "  	}\n";
				$message = $message. "  	</style>\n";
				$message = $message. " </head>\n";
				$message = $message. "<body>\n";

				$message = $message . "<p><a href=\"$configSiteURLSav\"><img src=\"$configSiteURLSav/$logoPathSav/$logoFileSav\" alt=\"$logoFileSav\" /></a></p>\n\n";
				$message = $message . "<h1>$subject</h1>\n\n";
				$message = $message . "<table>";
				$message = $message . " <tr>";
				$message = $message . "  <td style=\"width: 45px;vertical-align: top;\">";
				$message = $message . "		<p>\n";
				$message = $message . "			<a href=\"$configSiteURLSav/users/view_profile.php?user_id=$get_my_user_id\"><img src=\"$configSiteURLSav/$thumb_full_path\" alt=\"$thumb_full_path\" /></a>\n";
				$message = $message . "		</p>\n";
				$message = $message . "  </td>";
				$message = $message . "  <td style=\"vertical-align: top;\">";
				$message = $message . "		<p><b><a href=\"$configSiteURLSav/users/view_profile.php?user_id=$get_my_user_id\">$get_my_user_alias</a></b><br />\n";
				$message = $message . "		$inp_text</p>\n";
				$message = $message . "		<p>\n";
				$message = $message . "		<a href=\"$configSiteURLSav/users/view_profile.php?user_id=$get_current_user_id&amp;l=$get_current_user_language#status$get_status_id\">$l_view</a>\n";
				$message = $message . "		</p>\n";
				$message = $message . "  </td>";
				$message = $message . " </tr>";
				$message = $message . "</table>";


				$message = $message . "<p>\n\n--<br />\nBest regards<br />\n$configWebsiteTitleSav<br />\n<a href=\"$configSiteURLSav\">$configSiteURLSav</a></p>";
				$message = $message . "<p><hr /></p>";
				$message = $message . "<p>$l_dont_want_any_more_emails\n";
				$message = $message . "<a href=\"$configSiteURLSav/users/edit_subscriptions.php?action=unsubscribe_and_source_is_emails&amp;user_id=$get_current_user_id&amp;registered_time=$get_current_user_registered_time\">$l_unsubscribe</a>";
				$message = $message . "</p>";
				$message = $message. "</body>\n";
				$message = $message. "</html>\n";



				// Preferences for Subject field
				$headers[] = 'MIME-Version: 1.0';
				$headers[] = 'Content-type: text/html; charset=utf-8';
				$headers[] = "From: $configFromNameSav <" . $configFromEmailSav . ">";
				mail($get_current_user_email, $subject, $message, implode("\r\n", $headers));



			} // send e-mail


			$url = "view_profile.php?user_id=$get_current_user_id&amp;l=$l&ft=success&fm=comment_added#status$get_status_id";
			header("Location: $url");
			die;

		} // process


		echo"
		<h1>$l_say_hello_to $get_current_user_alias</h1>

		
		<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			elseif($fm == "ip_block"){
				if(isset($_GET['user_id'])) {
					$user_id = $_GET['user_id'];
					$user_id = strip_tags(stripslashes($user_id));
				}
				$fm = "$l_ip_block";
			}
			else{
				$fm = ucfirst($ft);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->


		<!-- New status -->
			";
			// IP block
			$my_ip = $_SERVER['REMOTE_ADDR'];
			$my_ip = output_html($my_ip);
			$my_ip_mysql = quote_smart($link, $my_ip);
			
			$time = time();

			$ip_block = "false";

			$q = "SELECT status_id, status_time FROM $t_users_status WHERE status_created_by_ip=$my_ip_mysql ORDER BY status_id DESC LIMIT 0,1";
			$r = mysqli_query($link, $q);
			$rowb = mysqli_fetch_row($r);
			list($get_status_id, $get_status_time) = $rowb;	

			if($get_status_id != ""){
				$time_since_last_status = $time-$get_status_time;
				$remaining = 60-$time_since_last_status;
				if($time_since_last_status < 60){
					$ip_block = "true";
				}
			}
			if($ip_block == "false"){
				echo"
				<form method=\"post\" action=\"status_new.php?user_id=$get_current_user_id&amp;l=$l&amp;process=1\" />
				<table style=\"width: 100%;\">
				 <tr>
				  <td style=\"width: 45px;vertical-align: top;\">
					<p><img src=\"$root/$thumb_full_path\" alt=\"$thumb_full_path\" /></p>
				  </td>
				  <td style=\"vertical-align: top;\">
					<p><textarea name=\"inp_text\" rows=\"3\" cols=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea><br />
					<input type=\"submit\" value=\"$l_send\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>
				  </td>
				 </tr>
				</table>
				";
			}
			echo"
		<!-- //New status -->



		<!-- Back -->";
			$l_s_profile_lowercase = str_replace("&amp;nbsp;", " ", $l_s_profile_lowercase);
			echo"
			<p>
			<a href=\"view_profile.php?user_id=$get_current_user_id&amp;l=$get_current_user_language\"><img src=\"_gfx/go-previous.png\" alt=\"go-previous.png\" /></a>
			<a href=\"view_profile.php?user_id=$get_current_user_id&amp;l=$get_current_user_language\">$get_current_user_alias$l_s_profile_lowercase</a>
			</p>
		<!-- //Back -->
		";

	} // im logged in
} // user found
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>