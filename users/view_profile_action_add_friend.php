<?php

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/users/ts_friend_requests.php");

/*- Config -------------------------------------------------------------------------- */
include("$root/_admin/_data/logo.php");

/*- Content --------------------------------------------------------------------------- */
if(isset($can_view_profile)){
	if($mode == "send_request"){
		// Get my profile
		$my_user_id = $_SESSION['user_id'];
		$my_user_id_mysql = quote_smart($link, $my_user_id);

		$query = "SELECT user_id, user_name, user_alias, user_language, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_name, $get_my_user_alias, $get_my_user_language, $get_my_user_rank) = $row;


		// My Profile photo
		$query = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id=$my_user_id_mysql AND photo_profile_image='1'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_photo_id, $get_my_photo_destination) = $row;


		// Are we friends?
		// user_A = Lowest ID
		// user_B = Higher ID

		if($get_current_user_id > $my_user_id){
			$inp_friend_user_id_a_mysql = quote_smart($link, $my_user_id);
			$inp_friend_user_id_b_mysql = quote_smart($link, $get_current_user_id);
		}
		else{
			$inp_friend_user_id_a_mysql = quote_smart($link, $get_current_user_id);
			$inp_friend_user_id_b_mysql = quote_smart($link, $my_user_id);
		}
		$query = "SELECT friend_id FROM $t_users_friends WHERE friend_user_id_a=$inp_friend_user_id_a_mysql AND friend_user_id_b=$inp_friend_user_id_b_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_friend_id) = $row;
		
		if($get_friend_id == ""){
			// We are not friends

			// Did I alreaddy send a request?
			$query = "SELECT fr_id FROM $t_users_friends_requests WHERE fr_from_user_id=$my_user_id_mysql AND fr_to_user_id=$current_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_fr_id) = $row;
			

			if($get_fr_id == ""){
				// Friend request not sent
				
				// Variables
				$inp_fr_text = $_POST['inp_text'];
				$inp_fr_text = output_html($inp_fr_text);
				$inp_fr_text_mysql = quote_smart($link, $inp_fr_text);

				$inp_fr_datetime = date("y-m-d H:i:s");
				
				// Insert
				mysqli_query($link, "INSERT INTO $t_users_friends_requests
				(fr_id, fr_from_user_id, fr_to_user_id, fr_text, fr_datetime) 
				VALUES 
				(NULL, $my_user_id_mysql, $get_current_user_id, $inp_fr_text_mysql, '$inp_fr_datetime')")
				or die(mysqli_error($link));
				
				// Get ID
				$query = "SELECT fr_id FROM $t_users_friends_requests WHERE fr_from_user_id=$my_user_id_mysql AND fr_to_user_id=$get_current_user_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_fr_id) = $row;
			

				// Send email to tell about friend request
				$q = "SELECT es_on_off FROM $t_users_email_subscriptions WHERE es_user_id=$get_current_user_id AND es_type='friend_request'";
				$r = mysqli_query($link, $q);
				$rowb = mysqli_fetch_row($r);
				list($get_es_friend_request) = $rowb;
				if($get_es_friend_request == "1"){


					// Send welcome mail
					$host = $_SERVER['HTTP_HOST'];
			
					$subject = $l_friend_request_from . " " . $get_my_user_alias . " - " . $configWebsiteTitleSav;
					$subject = str_replace("&aelig;", "æ", $subject);
					$subject = str_replace("&oslash;", "ø", $subject);
					$subject = str_replace("&aring;", "å", $subject);
			
					$message = "<html>\n";
					$message = $message. "<head>\n";
					$message = $message. "  <title>$subject</title>\n";
					$message = $message. " </head>\n";
					$message = $message. "<body>\n";

					$message = $message . "<p><a href=\"$configSiteURLSav\"><img src=\"$configSiteURLSav/$logoPathSav/$logoFileSav\" alt=\"$logoFileSav\" /></a></p>\n\n";
					$message = $message . "<h1>$l_friend_request</h1>\n\n";
					$message = $message . "<table>";
					$message = $message . " <tr>";
					$message = $message . "  <td style=\"width: 160px;vertical-align: top;\">";
					$message = $message . "		<p>\n";
					
					if($get_current_photo_id != ""){
						$message = $message . "<a href=\"$configSiteURLSav/users/view_profile.php?user_id=$get_my_user_id\"><img src=\"$configSiteURLSav/image.php?width=150&amp;image=/_uploads/users/images/$get_my_user_id/$get_my_photo_destination\" alt=\"$get_my_photo_destination\" /></a>\n";
					}
					else{
						$message = $message . "<a href=\"$configSiteURLSav/users/view_profile.php?user_id=$get_my_user_id\"><img src=\"$configSiteURLSav/users/_gfx/avatar_blank_150.png\" alt=\"avatar_blank_150.png\" /></a>\n";
					}
					$message = $message . "		</p>\n";
					$message = $message . "  </td>";
					$message = $message . "  <td style=\"vertical-align: top;\">";
					$message = $message . "		<p><b><a href=\"$configSiteURLSav/users/view_profile.php?user_id=$get_my_user_id\">$get_my_user_alias</a> $l_sent_you_a_friend_request_lowercase</b></p>\n";
					$message = $message . "		<p>$inp_fr_text\n";
					$message = $message . "		</p>\n";
					$message = $message . "		<p>\n";
					$message = $message . "		<a href=\"$configSiteURLSav/users/friend_requests.php?action=accept&amp;fr_id=$get_fr_id\">$l_accept</a> \n";
					$message = $message . "		&middot;\n";
					$message = $message . "		<a href=\"$configSiteURLSav/users/friend_requests.php?action=decline&amp;fr_id=$get_fr_id&amp;process=1\">$l_decline</a> \n";
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


				


				}

				// Friend request sent
				$url = "view_profile.php?user_id=$get_current_user_id&l=$l&ft=success&fm=friend_request_sent";
				echo"
				<h1>
				<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
				Loading...</h1>
				<meta http-equiv=\"refresh\" content=\"1;url=$url\">
				";
				
				
			}
			else{
				// Friend request already sent
				$url = "view_profile.php?user_id=$get_current_user_id&l=$l&ft=warning&fm=friend_request_already_sent";
				echo"
				<h1>
				<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
				Loading...</h1>
				<meta http-equiv=\"refresh\" content=\"1;url=$url\">
				";
				
			}

		}
		else{
			// We are friends
			$url = "view_profile.php?user_id=$get_current_user_id&l=$l&ft=warning&fm=you_are_already_friends";
			echo"
			<h1>
			<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
			Loading...</h1>
			<meta http-equiv=\"refresh\" content=\"1;url=$url\">
			";
		}
		

	} // send request
	if($mode == ""){
		$host = $_SERVER['HTTP_HOST'];
		$l_user_is_on_site = str_replace("%alias%", $get_current_user_alias, $l_user_is_on_site);
		$l_user_is_on_site = str_replace("%site%", $host, $l_user_is_on_site);
		$l_to_view_profile_then_add_user_as_friend = str_replace("%alias%", $get_current_user_alias, $l_to_view_profile_then_add_user_as_friend);
		echo"
		<p><b>$l_user_is_on_site</b></p>
		<p>$l_to_view_profile_then_add_user_as_friend</p>

		";

		// Did I alreaddy send a request?
		$query = "SELECT fr_id FROM $t_users_friends_requests WHERE fr_from_user_id=$my_user_id_mysql AND fr_to_user_id=$current_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_fr_id) = $row;
			
		if($get_fr_id == ""){
			echo"
			

			<!-- Friend request form -->

				<script type=\"text/javascript\">  
				\$(document).ready(function() {
					
						\$('[name=\"inp_text\"]').focus();
					});
				});
				</script>

			
				<form method=\"POST\" action=\"view_profile.php?action=add_friend&amp;user_id=$get_current_user_id&amp;mode=send_request&amp;l=$l\" enctype=\"multipart/form-data\">


				<p>$l_how_did_you_meet<br />
				<textarea name=\"inp_text\" rows=\"4\" cols=\"40\"></textarea>
				</p>

				<p>
				<input class=\"btn btn-primary\" type=\"submit\" value=\"$l_send_friend_request\" />
				</p>
				</form>
			<!-- //Friend request form -->
			";
		}
		else{
			echo"
			<div class=\"alert alert-info\" role=\"alert\">
			  <span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span>
			  <span>$l_friend_request_sent</span>
			</div>
			";
		}
		echo"
		";
	} // mode
} // can view profile
?>