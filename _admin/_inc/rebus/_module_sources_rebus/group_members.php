<?php
/**
*
* File: rebus/group_members.php
* Version 1.0.0.
* Date 09:50 01.07.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
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
include("$root/_admin/_data/logo.php");
include("$root/_admin/_data/config/user_system.php");

/*- Tables ---------------------------------------------------------------------------- */
include("_tables_rebus.php");


/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);

if(isset($_GET['group_id'])) {
	$group_id = $_GET['group_id'];
	$group_id = output_html($group_id);
	if(!(is_numeric($group_id))){
		echo"Group id not numeric";
		die;
	}
}
else{
	echo"Missing group id";
	die;
}

$tabindex = 0;


/*- Translation ------------------------------------------------------------------------------- */


// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);



	/*- Find group ------------------------------------------------------------------------- */
	$group_id_mysql = quote_smart($link, $group_id);
	$query = "SELECT group_id, group_name, group_language, group_description, group_privacy, group_key, group_logo_path, group_logo_file, group_created_by_user_id, group_created_by_user_name, group_created_by_user_email, group_created_by_ip, group_created_by_hostname, group_created_by_user_agent, group_created_datetime, group_created_date_saying, group_updated_by_user_id, group_updated_by_user_name, group_updated_by_user_email, group_updated_by_ip, group_updated_by_hostname, group_updated_by_user_agent, group_updated_datetime, group_updated_date_saying FROM $t_rebus_groups_index WHERE group_id=$group_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_group_id, $get_current_group_name, $get_current_group_language, $get_current_group_description, $get_current_group_privacy, $get_current_group_key, $get_current_group_logo_path, $get_current_group_logo_file, $get_current_group_created_by_user_id, $get_current_group_created_by_user_name, $get_current_group_created_by_user_email, $get_current_group_created_by_ip, $get_current_group_created_by_hostname, $get_current_group_created_by_user_agent, $get_current_group_created_datetime, $get_current_group_created_date_saying, $get_current_group_updated_by_user_id, $get_current_group_updated_by_user_name, $get_current_group_updated_by_user_email, $get_current_group_updated_by_ip, $get_current_group_updated_by_hostname, $get_current_group_updated_by_user_agent, $get_current_group_updated_datetime, $get_current_group_updated_date_saying) = $row;
	if($get_current_group_id == ""){
		$url = "groups.php?ft=error&fm=group_not_found&l=$l";
		header("Location: $url");
		exit;
	}

	/*- Check that I am a member of this group --------------------------------------------- */
	$query = "SELECT member_id, member_group_id, member_user_id, member_user_name, member_user_email, member_user_photo_destination, member_user_photo_thumb_50, member_status, member_invited, member_user_accepted_invitation, member_accepted_by_moderator, member_joined_datetime, member_joined_date_saying FROM $t_rebus_groups_members WHERE member_group_id=$group_id_mysql AND member_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_member_id, $get_my_member_group_id, $get_my_member_user_id, $get_my_member_user_name, $get_my_member_user_email, $get_my_member_user_photo_destination, $get_my_member_user_photo_thumb_50, $get_my_member_status, $get_my_member_invited, $get_my_member_user_accepted_invitation, $get_my_member_accepted_by_moderator, $get_my_member_joined_datetime, $get_my_member_joined_date_saying) = $row;
	if($get_current_group_id == ""){
		$url = "groups.php?ft=error&fm=your_not_a_member_of_that_group&l=$l";
		header("Location: $url");
		exit;
	}

	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$get_current_group_name - $l_groups - $l_rebus";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");

	if($action == ""){
		echo"
		<!-- Headline -->
			<h1>$get_current_group_name</h1>
		<!-- //Headline -->

		<!-- Where am I ? -->
			<p><b>$l_you_are_here:</b><br />
			<a href=\"index.php?l=$l\">$l_rebus</a>
			&gt;
			<a href=\"groups.php?l=$l\">$l_groups</a>
			&gt;
			<a href=\"group_open.php?group_id=$get_current_group_id&amp;l=$l\">$get_current_group_name</a>
			&gt;
			<a href=\"group_open.php?group_id=$get_current_group_id&amp;l=$l\">$l_group_members</a>
			</p>
		<!-- //Where am I ? -->

		<!-- Description and logo -->
		";
		if($get_current_group_description != ""){
			echo"<p>$get_current_group_description</p>\n";
		}
		echo"
		<!-- //Description and logo -->
		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				$fm = ucfirst($fm);
				$fm = str_replace("_", " ", $fm);
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->

		<!-- Actions -->";
			if($get_my_member_status == "admin" OR $get_my_member_status == "moderator"){
				echo"
				<p>
				<a href=\"group_members.php?group_id=$get_current_group_id&amp;action=invite_member&amp;l=$l\" class=\"btn_default\">$l_invite_member</a>
				</p>
				";
			}
			echo"
		<!-- //Actions -->

		<!-- Group members-->
			<table class=\"hor-zebra\">
			 <thead>
			  <tr>
			   <th>
				<span>$l_username</span>
			   </th>
			   <th>
				<span>$l_status</span>
			   </th>
			  </tr>
			 </thead>
			 <tbody>";
			$query = "SELECT member_id, member_group_id, member_user_id, member_user_name, member_user_email, member_user_photo_destination, member_user_photo_thumb_50, member_status, member_invited, member_user_accepted_invitation, member_accepted_by_moderator, member_joined_datetime, member_joined_date_saying FROM $t_rebus_groups_members WHERE member_group_id=$get_current_group_id ORDER BY member_user_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_member_id, $get_member_group_id, $get_member_user_id, $get_member_user_name, $get_member_user_email, $get_member_user_photo_destination, $get_member_user_photo_thumb_50, $get_member_status, $get_member_invited, $get_member_user_accepted_invitation, $get_member_accepted_by_moderator, $get_member_joined_datetime, $get_member_joined_date_saying) = $row;
				// Style
				$style = "";
				if($get_member_status == "admin_invited" OR $get_member_status == "moderator_invited" OR $get_member_status == "member_invited"){
					$style = "important";
				}
				if($get_member_accepted_by_moderator == "0"){
					$style = "danger";
				}
				echo"
				 <tr>
				  <td"; if($style != ""){ echo" class=\"$style\""; } echo">
					<span>";
					if($get_my_member_status == "admin" OR $get_my_member_status == "moderator"){
						echo"<a href=\"group_members.php?action=view_member&amp;group_id=$get_current_group_id&amp;member_id=$get_member_id&amp;l=$l\">$get_member_user_name</a>";
					}
					else{
						echo"$get_member_user_name";
					}
					echo"</span>
					
				  </td>
				  <td"; if($style != ""){ echo" class=\"$style\""; } echo">
					<span>";
					if($get_member_status == "admin"){
						echo"$l_admin";
					}
					elseif($get_member_status == "admin_invited"){
						echo"$l_waiting_for_user_to_accept_invitation";
					}
					elseif($get_member_status == "moderator"){
						echo"$l_moderator";
					}
					elseif($get_member_status == "moderator_invited"){
						echo"$l_waiting_for_user_to_accept_invitation";
					}
					elseif($get_member_status == "member"){
						echo"$l_member";
					}
					elseif($get_member_status == "member_invited"){
						echo"$l_waiting_for_user_to_accept_invitation";
					}
					else{
						echo"?";
					}
					
					if($get_member_accepted_by_moderator == "0"){
						echo" - $l_not_verified_by_moderator";
						if($get_my_member_status == "admin" OR $get_my_member_status == "moderator"){
							echo"<a href=\"group_members.php?action=verify_member&amp;group_id=$get_current_group_id&amp;member_id=$get_member_id&amp;l=$l&amp;process=1\" class=\"btn_default\">$l_verify_user</a>";
						}
					}
					echo"</span>
				  </td>
				 </tr>

				";
			}	
			echo"
			 </tbody>
			</table>
		<!-- //Group members-->
		";
	} // action == ""
	elseif($action == "invite_member"){
		if($get_my_member_status == "admin" OR $get_my_member_status == "moderator"){
			if($process == "1"){
				// Dates
				$datetime = date("Y-m-d H:i:s");
				$time = time();
				$date_saying = date("j M Y");


				$inp_username_or_email = $_POST['inp_username_or_email'];
				$inp_username_or_email = output_html($inp_username_or_email);
				$inp_username_or_email_mysql = quote_smart($link, $inp_username_or_email);
				if($inp_username_or_email == ""){
					$url = "group_members.php?group_id=$get_current_group_id&action=invite_member&l=$l&ft=error&fm=missing_username_or_email";
					header("Location: $url");
					exit;
				}

				$inp_status = $_POST['inp_status'];
				$inp_status = output_html($inp_status);
				if($inp_status != "member" && $inp_status != "moderator" && $inp_status != "admin"){
					if($inp_status == "admin" && $get_my_member_status == "admin"){
					}
					else{
						$url = "group_members.php?group_id=$get_current_group_id&action=invite_member&l=$l&ft=error&fm=unknown_status";
						header("Location: $url");
						exit;
					}
				}

				// Look for username
				$query = "SELECT user_id, user_email, user_name, user_alias, user_language FROM $t_users WHERE user_email=$inp_username_or_email_mysql OR user_name=$inp_username_or_email_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_language) = $row;

				if($get_user_id == ""){

					// Check for @
					if(strpos($inp_username_or_email, "@") !== false){
						// Send email to invite person
						$inp_email = "$inp_username_or_email";
						$inp_email_mysql = quote_smart($link, $inp_email);
	
						// Username
						$inp_username = explode("@", $inp_email);
						$inp_username = $inp_username[0];
						$inp_username_mysql = quote_smart($link, $inp_username);

						// Check that username is available
						$query = "SELECT user_id FROM $t_users WHERE user_name=$inp_username_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_user_id) = $row;
						if($get_user_id != ""){
							// Create random username
							$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    							$charactersLength = strlen($characters);
    							$inp_username = '';
    							for ($i = 0; $i < 16; $i++) {
        							$inp_username .= $characters[rand(0, $charactersLength - 1)];
    							}
							$inp_username_mysql = quote_smart($link, $inp_username);
						}
					

						// Language
						$inp_language = output_html($l);
						$inp_language_mysql = quote_smart($link, $inp_language);

						// Create salt
						$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    						$charactersLength = strlen($characters);
    						$salt = '';
    						for ($i = 0; $i < 6; $i++) {
        						$salt .= $characters[rand(0, $charactersLength - 1)];
    						}
						$inp_salt_mysql = quote_smart($link, $salt);

						// Password
    						$inp_password = '';
    						for ($i = 0; $i < 8; $i++) {
        						$inp_password .= $characters[rand(0, $charactersLength - 1)];
    						}
						$inp_password_encrypted =  sha1($inp_password);
						$inp_password_mysql = quote_smart($link, $inp_password_encrypted);


						// Security
						$inp_security = rand(0,9999);



						// Date format
						if($l == "no"){
							$inp_date_format = "l d. f Y";
						}
						else{
							$inp_date_format = "l jS \of F Y";
						}
						$inp_date_format_mysql = quote_smart($link, $inp_date_format);


						// Insert user
						mysqli_query($link, "INSERT INTO $t_users
						(user_id, user_email, user_name, user_alias, user_password, user_salt, user_security, user_language, user_measurement, user_date_format, user_registered, user_registered_time, user_last_online, user_last_online_time, user_rank, user_points, user_points_rank, user_likes, user_dislikes, user_last_ip, user_verified_by_moderator, user_notes, user_marked_as_spammer) 
						VALUES 
						(NULL, $inp_email_mysql, $inp_username_mysql, $inp_username_mysql, $inp_password_mysql, $inp_salt_mysql, '$inp_security', $inp_language_mysql, 'metric', $inp_date_format_mysql, '$datetime', '$time', '$datetime', '$time', 'user', '0', 'Newbie', '0', '0', '', 1, 'can_change_username', '0')")
						or die(mysqli_error($link));

						// Get user id
						$query = "SELECT user_id FROM $t_users WHERE user_email=$inp_email_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_user_id) = $row;
			
						// Insert profile			
						mysqli_query($link, "INSERT INTO $t_users_profile
						(profile_id, profile_user_id, profile_newsletter, profile_views, profile_privacy) 
						VALUES 
						(NULL, '$get_user_id', 0, '0', 'public')")
						or die(mysqli_error($link));


						// Email
						$host = $_SERVER['HTTP_HOST'];

						$subject = $l_rebus_invite_at . " " . $configWebsiteTitleSav;
			
						$message = "<html>\n";
						$message = $message. "<head>\n";
						$message = $message. "  <title>$subject</title>\n";
						$message = $message. " </head>\n";
						$message = $message. "<body>\n";

						$message = $message . "<p><a href=\"$configSiteURLSav\"><img src=\"$configSiteURLSav/$logoPathSav/$logoFileSav\" alt=\"($configWebsiteTitleSav logo)\" /></a></p>\n\n";
						$message = $message . "<h1>$l_hello</h1>\n\n";
						$message = $message . "<p><b>$get_my_member_user_name</b> $l_has_invited_you_to_rebus_lowercase.<br /><br />\n";
						$message = $message . "$l_join_the_group_by_following_the_link_below:<br />\n";
						$message = $message . "<a href=\"$configSiteURLSav/rebus/accept_group_invitation.php?user_id=$get_user_id&amp;l=$l\">$l_accept</a>";
						$message = $message . "</a>";

						$message = $message . "<p><b>$l_your_information</b><br />\n\n";
						$message = $message . "$l_email: $inp_email<br />\n";
						$message = $message . "$l_password: $inp_password</p>\n";


	
						$message = $message . "<p>\n\n--<br />\ns<br />\n$configWebsiteTitleSav<br />\n<a href=\"$configSiteURLSav\">$configSiteURLSav</a></p>";
						$message = $message. "</body>\n";
						$message = $message. "</html>\n";


						// Preferences for Subject field
						$headers[] = 'MIME-Version: 1.0';
						$headers[] = 'Content-type: text/html; charset=utf-8';
						$headers[] = "From: $configFromNameSav <" . $configFromEmailSav . ">";
						if($configMailSendActiveSav == "1"){
							mail($inp_email, $subject, $message, implode("\r\n", $headers));
						}
				
						// Insert into members
						$inp_status = $inp_status . "_invited";
						$inp_status_mysql = quote_smart($link, $inp_status);

						mysqli_query($link, "INSERT INTO $t_rebus_groups_members
						(member_id, member_group_id, member_status, member_invited, member_user_accepted_invitation, 
						member_accepted_by_moderator, member_user_id, member_user_name, member_user_email, member_user_photo_destination, 
						member_user_photo_thumb_50, member_joined_datetime, member_joined_date_saying) 
						VALUES 
						(NULL, $get_current_group_id, $inp_status_mysql, 1, 0, 
						1, $get_user_id, $inp_username_mysql, $inp_email_mysql, '', 
						'', '$datetime', '$date_saying')")
						or die(mysqli_error($link));


						$url = "group_members.php?group_id=$get_current_group_id&action=invite_member&l=$l&ft=info&fm=user_invited";
						header("Location: $url");
						exit;
					
					}
					else{
						// @ not found
						$url = "group_members.php?group_id=$get_current_group_id&action=invite_member&l=$l&ft=error&fm=user_not_found";
						header("Location: $url");
						exit;
					}
				}
				else{
					// Check that user is not already member
					$query = "SELECT member_id FROM $t_rebus_groups_members WHERE member_group_id=$get_current_group_id AND member_user_id=$get_user_id";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_member_id) = $row;
					if($get_member_id != ""){
						$url = "group_members.php?group_id=$get_current_group_id&action=invite_member&l=$l&ft=error&fm=user_already_member_of_group";
						header("Location: $url");
						exit;
					}
					else{
						// Invite member

						$inp_user_name_mysql = quote_smart($link, $get_user_name);
						$inp_user_email_mysql = quote_smart($link, $get_user_email);

						// Profile photo
						$query = "SELECT photo_id, photo_destination, photo_thumb_50 FROM $t_users_profile_photo WHERE photo_user_id='$get_user_id' AND photo_profile_image='1'";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_photo_id, $get_photo_destination, $get_photo_thumb_50) = $row;

						$inp_photo_destination_mysql = quote_smart($link, $get_photo_destination);
						$inp_photo_thumb_50_mysql = quote_smart($link, $get_photo_thumb_50);

						$inp_status = $inp_status . "_invited";
						$inp_status_mysql = quote_smart($link, $inp_status);

						mysqli_query($link, "INSERT INTO $t_rebus_groups_members
						(member_id, member_group_id, member_status, member_invited, member_user_accepted_invitation, 
						member_accepted_by_moderator, member_user_id, member_user_name, member_user_email, member_user_photo_destination, 
						member_user_photo_thumb_50, member_joined_datetime, member_joined_date_saying) 
						VALUES 
						(NULL, $get_current_group_id, $inp_status_mysql, 1, 0, 
						1, $get_user_id, 
						$inp_user_name_mysql, $inp_user_email_mysql, $inp_photo_destination_mysql, $inp_photo_thumb_50_mysql, '$datetime', 
						'$date_saying')")
						or die(mysqli_error($link));


						$url = "group_members.php?group_id=$get_current_group_id&action=invite_member&l=$l&ft=info&fm=user_invited";
						header("Location: $url");
						exit;
					}
				
				}
	

			} // process == 1
			echo"
			<!-- Headline -->
				<h1>$get_current_group_name</h1>
			<!-- //Headline -->

			<!-- Where am I ? -->
				<p><b>$l_you_are_here:</b><br />
				<a href=\"index.php?l=$l\">$l_rebus</a>
				&gt;
				<a href=\"groups.php?l=$l\">$l_groups</a>
				&gt;
				<a href=\"group_open.php?group_id=$get_current_group_id&amp;l=$l\">$get_current_group_name</a>
				&gt;
				<a href=\"group_members.php?group_id=$get_current_group_id&amp;l=$l\">$l_group_members</a>
				&gt;
				<a href=\"group_members.php?group_id=$get_current_group_id&amp;action=invite_member&amp;l=$l\">$l_invite_member</a>
				</p>
			<!-- //Where am I ? -->

			<!-- Description and logo -->
				";
				if($get_current_group_description != ""){
					echo"<p>$get_current_group_description</p>\n";
				}
				echo"
			<!-- //Description and logo -->

			<!-- Focus -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_username_or_email\"]').focus();
				});
				</script>
			<!-- //Focus -->
			<!-- Feedback -->
				";
				if($ft != "" && $fm != ""){
					$fm = ucfirst($fm);
					$fm = str_replace("_", " ", $fm);
					echo"<div class=\"$ft\"><p>$fm</p></div>";
				}
				echo"
			<!-- //Feedback -->

			<!-- Invite member form -->
			<form method=\"post\" action=\"group_members.php?group_id=$get_current_group_id&amp;action=invite_member&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>$l_username_or_email:</b><br />
			<input type=\"text\" name=\"inp_username_or_email\" value=\"\" size=\"25\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</p>

			<p><b>$l_status:</b><br />
			<select name=\"inp_status\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\">
				<option value=\"member\">$l_member</option>
				<option value=\"moderator\">$l_moderator</option>\n";
			if($get_my_member_status == "admin"){
				echo"				<option value=\"admin\">$l_admin</option>\n";

			}
			echo"
			</select>
			</p>

			<p><input type=\"submit\" value=\"$l_invite_member\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></p>
		
			</form>
			<!-- //Invite member form -->

			";


		} // my status admin or moderator
		else{
			echo"<p>Access only for admin/moderator of group";
		}
	} // action == "invite_member";
	elseif($action == "view_member"){
		if($get_my_member_status == "admin" OR $get_my_member_status == "moderator"){
			if(isset($_GET['member_id'])) {
				$member_id = $_GET['member_id'];
				$member_id = output_html($member_id);
				if(!(is_numeric($member_id))){
					echo"member id not numeric";
					die;
				}
			}
			else{
				echo"Missing member id";
				die;
			}
			$member_id_mysql = quote_smart($link, $member_id);

			// Find member
			$query = "SELECT member_id, member_group_id, member_user_id, member_user_name, member_user_email, member_user_photo_destination, member_user_photo_thumb_50, member_status, member_invited, member_user_accepted_invitation, member_accepted_by_moderator, member_joined_datetime, member_joined_date_saying FROM $t_rebus_groups_members WHERE member_group_id=$get_current_group_id AND member_id=$member_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_member_id, $get_current_member_group_id, $get_current_member_user_id, $get_current_member_user_name, $get_current_member_user_email, $get_current_member_user_photo_destination, $get_current_member_user_photo_thumb_50, $get_current_member_status, $get_current_member_invited, $get_current_member_user_accepted_invitation, $get_current_member_accepted_by_moderator, $get_current_member_joined_datetime, $get_current_member_joined_date_saying) = $row;

			if($get_current_member_id == ""){
				echo"<p>Member not found";

			}
			else{
				if($process == "1"){
					$inp_status = $_POST['inp_status'];
					$inp_status = output_html($inp_status);
					if($inp_status != "member" && $inp_status != "moderator" && $inp_status != "admin"){
						if($inp_status == "admin" && $get_my_member_status == "admin"){
						}
						else{
							$url = "group_members.php?group_id=$get_current_group_id&action=$action&member_id=$get_current_member_id&l=$l&ft=error&fm=unknown_status";
							header("Location: $url");
							exit;
						}
					}

					// Invited?
					if($get_current_member_invited == "1" && $get_current_member_user_accepted_invitation == "0"){
						$inp_status = $inp_status . "_invited";
					}
					$inp_status_mysql = quote_smart($link, $inp_status);

					// Accepted
					$inp_accepted_by_moderator = $_POST['inp_accepted_by_moderator'];
					$inp_accepted_by_moderator = output_html($inp_accepted_by_moderator);
					$inp_accepted_by_moderator_mysql = quote_smart($link, $inp_accepted_by_moderator);

					
					mysqli_query($link, "UPDATE $t_rebus_groups_members SET
						member_status=$inp_status_mysql, 
						member_accepted_by_moderator=$inp_accepted_by_moderator_mysql
						WHERE member_id=$get_current_member_id") or die(mysqli_error($link));


					$url = "group_members.php?group_id=$get_current_group_id&action=$action&member_id=$get_current_member_id&l=$l&ft=info&fm=changes_saved";
					header("Location: $url");
					exit;


				} // process == 1

				echo"
				<!-- Headline -->
					<h1>$get_current_group_name</h1>
				<!-- //Headline -->
	
				<!-- Where am I ? -->
					<p><b>$l_you_are_here:</b><br />
					<a href=\"index.php?l=$l\">$l_rebus</a>
					&gt;
					<a href=\"groups.php?l=$l\">$l_groups</a>
					&gt;
					<a href=\"group_open.php?group_id=$get_current_group_id&amp;l=$l\">$get_current_group_name</a>
					&gt;
					<a href=\"group_members.php?group_id=$get_current_group_id&amp;l=$l\">$l_group_members</a>
					&gt;
					<a href=\"group_members.php?group_id=$get_current_group_id&amp;action=$action&amp;member_id=$get_current_member_id&amp;l=$l\">$get_current_member_user_name</a>
					</p>
				<!-- //Where am I ? -->

				
				<!-- Focus -->
					<script>
					\$(document).ready(function(){
						\$('[name=\"inp_username_or_email\"]').focus();
					});
					</script>
				<!-- //Focus -->
				<!-- Feedback -->
				";
				if($ft != "" && $fm != ""){
					$fm = ucfirst($fm);
					$fm = str_replace("_", " ", $fm);
					echo"<div class=\"$ft\"><p>$fm</p></div>";
				}
				echo"
				<!-- //Feedback -->

				<!-- Editmember form -->
				<form method=\"post\" action=\"group_members.php?group_id=$get_current_group_id&amp;action=$action&amp;member_id=$get_current_member_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

				<p><b>$l_status:</b><br />
				<select name=\"inp_status\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\">
					<option value=\"member\""; if($get_current_member_status == "member" OR $get_current_member_status == "member_invited"){ echo" selected=\"selected\""; } echo">$l_member</option>
					<option value=\"moderator\""; if($get_current_member_status == "moderator" OR $get_current_member_status == "moderator_invited"){ echo" selected=\"selected\""; } echo">$l_moderator</option>\n";
				if($get_my_member_status == "admin"){
					echo"				<option value=\"admin\""; if($get_current_member_status == "admin" OR $get_current_member_status == "admin_invited"){ echo" selected=\"selected\""; } echo">$l_admin</option>\n";
				}
				echo"
				</select>
				</p>

				<p>
				<input type=\"radio\" name=\"inp_accepted_by_moderator\" value=\"1\""; if($get_current_member_accepted_by_moderator == "1"){ echo" checked=\"checked\""; } echo" /> $l_yes
				<input type=\"radio\" name=\"inp_accepted_by_moderator\" value=\"0\""; if($get_current_member_accepted_by_moderator == "0"){ echo" checked=\"checked\""; } echo" /> $l_no
				</p>

				<p><input type=\"submit\" value=\"$l_edit_member\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
				<a href=\"group_members.php?group_id=$get_current_group_id&amp;action=delete_member&amp;member_id=$get_current_member_id&amp;l=$l\" class=\"btn_warning\">$l_delete</a>
				</p>
		
				</form>
				<!-- //Edit member form -->
				";
			} // member found
		} // my status admin or moderator
		else{
			echo"<p>Access only for admin/moderator of group";
		}
	} // view member
	elseif($action == "delete_member"){
		if($get_my_member_status == "admin" OR $get_my_member_status == "moderator"){
			if(isset($_GET['member_id'])) {
				$member_id = $_GET['member_id'];
				$member_id = output_html($member_id);
				if(!(is_numeric($member_id))){
					echo"member id not numeric";
					die;
				}
			}
			else{
				echo"Missing member id";
				die;
			}
			$member_id_mysql = quote_smart($link, $member_id);

			// Find member
			$query = "SELECT member_id, member_group_id, member_user_id, member_user_name, member_user_email, member_user_photo_destination, member_user_photo_thumb_50, member_status, member_invited, member_user_accepted_invitation, member_accepted_by_moderator, member_joined_datetime, member_joined_date_saying FROM $t_rebus_groups_members WHERE member_group_id=$get_current_group_id AND member_id=$member_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_member_id, $get_current_member_group_id, $get_current_member_user_id, $get_current_member_user_name, $get_current_member_user_email, $get_current_member_user_photo_destination, $get_current_member_user_photo_thumb_50, $get_current_member_status, $get_current_member_invited, $get_current_member_user_accepted_invitation, $get_current_member_accepted_by_moderator, $get_current_member_joined_datetime, $get_current_member_joined_date_saying) = $row;

			if($get_current_member_id == ""){
				echo"<p>Member not found";

			}
			else{
				if($process == "1"){
					
					mysqli_query($link, "DELETE FROM $t_rebus_groups_members 
						WHERE member_id=$get_current_member_id") or die(mysqli_error($link));


					$url = "group_members.php?group_id=$get_current_group_id&l=$l&ft=info&fm=member_deleted";
					header("Location: $url");
					exit;


				} // process == 1

				echo"
				<!-- Headline -->
					<h1>$get_current_group_name</h1>
				<!-- //Headline -->
	
				<!-- Where am I ? -->
					<p><b>$l_you_are_here:</b><br />
					<a href=\"index.php?l=$l\">$l_rebus</a>
					&gt;
					<a href=\"groups.php?l=$l\">$l_groups</a>
					&gt;
					<a href=\"group_open.php?group_id=$get_current_group_id&amp;l=$l\">$get_current_group_name</a>
					&gt;
					<a href=\"group_members.php?group_id=$get_current_group_id&amp;l=$l\">$l_group_members</a>
					&gt;
					<a href=\"group_members.php?group_id=$get_current_group_id&amp;action=view_member&amp;member_id=$get_current_member_id&amp;l=$l\">$get_current_member_user_name</a>
					&gt;
					<a href=\"group_members.php?group_id=$get_current_group_id&amp;action=$action&amp;member_id=$get_current_member_id&amp;l=$l\">$l_delete</a>
					</p>
				<!-- //Where am I ? -->

				
				<!-- Delete member form -->

					<p>$l_are_you_sure</p>


					<p>
					<a href=\"group_members.php?group_id=$get_current_group_id&amp;action=$action&amp;member_id=$get_current_member_id&amp;l=$l&amp;process=1\" class=\"btn_danger\">$l_confirm</a>
					</p>

				<!-- //Delete member form -->
				";
			} // member found
		} // my status admin or moderator
		else{
			echo"<p>Access only for admin/moderator of group";
		}
	} // delete member
}
else{
	echo"
	<h1>
	<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=rebus/groups.php\">

	<p>Please log in...</p>
	";
}

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>