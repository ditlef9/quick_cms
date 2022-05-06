<?php
/**
*
* File: users/index.php
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

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/users/ts_index.php");
include("$root/_admin/_translations/site/$l/users/ts_friend_requests.php");

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_friend_requests - $l_users";
include("$root/_webdesign/header.php");



/*- Content --------------------------------------------------------------------------- */

/*- Variables ------------------------------------------------------------------------- */
if (isset($_GET['fr_id'])) {
	$fr_id = $_GET['fr_id'];
	$fr_id = stripslashes(strip_tags($fr_id));
}
else{
	$fr_id = "";
}


if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	// Get user
	$user_id = $_SESSION['user_id'];
	$user_id_mysql = quote_smart($link, $user_id);
	$security = $_SESSION['security'];
	$security_mysql = quote_smart($link, $security);

	$query = "SELECT user_id, user_name, user_alias, user_language, user_rank FROM $t_users WHERE user_id=$user_id_mysql AND user_security=$security_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_name, $get_my_user_alias, $get_my_user_language, $get_my_user_rank) = $row;

	$query = "SELECT profile_id, profile_user_id, profile_first_name, profile_middle_name, profile_last_name, profile_address_line_a, profile_address_line_b, profile_zip, profile_city, profile_country, profile_phone, profile_work, profile_university, profile_high_school, profile_languages, profile_website, profile_interested_in, profile_relationship, profile_about, profile_newsletter FROM $t_users_profile WHERE profile_user_id=$user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_profile_id, $get_profile_user_id, $get_profile_first_name, $get_profile_middle_name, $get_profile_last_name, $get_profile_address_line_a, $get_profile_address_line_b, $get_profile_zip, $get_profile_city, $get_profile_country, $get_profile_phone, $get_profile_work, $get_profile_university, $get_profile_high_school, $get_profile_languages, $get_profile_website, $get_profile_interested_in, $get_profile_relationship, $get_profile_about, $get_profile_newsletter) = $row;

	// Get my photo
	$q = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id=$user_id_mysql AND photo_profile_image='1'";
	$r = mysqli_query($link, $q);
	$rowb = mysqli_fetch_row($r);
	list($get_my_photo_id, $get_my_photo_destination) = $rowb;


	if($get_my_user_id == ""){
		echo"<h1>Error</h1><p>Error with user id.</p>"; 
		$_SESSION = array();
		session_destroy();
		die;
	}

	if($action == "decline"){
		// Get that friend request
		$fr_id_mysql = quote_smart($link, $fr_id);

		$query = "SELECT fr_id, fr_from_user_id, fr_to_user_id FROM $t_users_friends_requests WHERE fr_id=$fr_id_mysql AND fr_to_user_id=$get_my_user_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_fr_id, $get_fr_from_user_id, $get_fr_to_user_id) = $row;
		if($get_fr_id == ""){
			$url = "friend_requests.php?ft=warning&fm=friend_request_not_found&l=$l";
			header("Location: $url");
			exit;
		}
		else{
			// Delete from mysql
			$result = mysqli_query($link, "DELETE FROM $t_users_friends_requests WHERE fr_id=$fr_id_mysql AND fr_to_user_id=$user_id_mysql");

			$url = "friend_requests.php?ft=success&fm=friend_request_declined&l=$l";
			header("Location: $url");
			exit;
		}
	}
	elseif($action == "do_accept_friend_request" && $process == "1"){
		// Get that friend request
		$fr_id_mysql = quote_smart($link, $fr_id);

		$query = "SELECT fr_id, fr_from_user_id, fr_to_user_id, fr_text FROM $t_users_friends_requests WHERE fr_id=$fr_id_mysql AND fr_to_user_id=$user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_fr_id, $get_fr_from_user_id, $get_fr_to_user_id, $get_fr_text) = $row;
		if($get_fr_id == ""){
			$url = "friend_requests.php?ft=warning&fm=friend_request_not_found&l=$l";
			header("Location: $url");
			exit;
		}
		else{
			// Get user alias of from friend
			$query = "SELECT user_email, user_alias, user_language FROM $t_users WHERE user_id='$get_fr_from_user_id'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_from_user_email, $get_from_user_alias, $get_from_user_language) = $row;
			

			// Get friends Photo
			$q = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id='$get_fr_from_user_id' AND photo_profile_image='1'";
			$r = mysqli_query($link, $q);
			$rowb = mysqli_fetch_row($r);
			list($get_from_photo_id, $get_from_photo_destination) = $rowb;



			// Get variables
			$inp_text = $_POST['inp_text'];
			$inp_text = output_html($inp_text);

			$my_user_id = $_SESSION['user_id'];
			$my_user_id = output_html($my_user_id);
			
			$inp_friend_datetime = date("Y-m-d H:i:s");


			// Insert friends
			
			// user_A = Lowest ID
			// user_B = Higher ID

			if($get_fr_from_user_id > $my_user_id){
				$inp_friend_user_id_a_mysql = quote_smart($link, $my_user_id);
				$inp_friend_user_id_b_mysql = quote_smart($link, $get_fr_from_user_id);

				$inp_friend_user_alias_a_mysql = quote_smart($link, $get_my_user_alias);
				$inp_friend_user_alias_b_mysql = quote_smart($link, $get_from_user_alias);

				$inp_friend_user_image_a_mysql = quote_smart($link, $get_my_photo_destination);
				$inp_friend_user_image_b_mysql = quote_smart($link, $get_from_photo_destination);

				$inp_friend_text_a_mysql = quote_smart($link, $inp_text);
				$inp_friend_text_b_mysql = quote_smart($link, $get_fr_text);
			}
			else{
				$inp_friend_user_id_a_mysql = quote_smart($link, $get_fr_from_user_id);
				$inp_friend_user_id_b_mysql = quote_smart($link, $my_user_id);

				$inp_friend_user_alias_a_mysql = quote_smart($link, $get_from_user_alias);
				$inp_friend_user_alias_b_mysql = quote_smart($link, $get_my_user_alias);

				$inp_friend_user_image_a_mysql = quote_smart($link, $get_from_photo_destination);
				$inp_friend_user_image_b_mysql = quote_smart($link, $get_my_photo_destination);

				$inp_friend_text_a_mysql = quote_smart($link, $get_fr_text);
				$inp_friend_text_b_mysql = quote_smart($link, $inp_text);
			}
		
			// Insert
			mysqli_query($link, "INSERT INTO $t_users_friends
			(friend_id, friend_user_id_a, friend_user_id_b, friend_user_alias_a, friend_user_alias_b, friend_user_image_a, friend_user_image_b, friend_text_a, friend_text_b, friend_datetime) 
			VALUES 
			(NULL, $inp_friend_user_id_a_mysql, $inp_friend_user_id_b_mysql, $inp_friend_user_alias_a_mysql, $inp_friend_user_alias_b_mysql, $inp_friend_user_image_a_mysql, $inp_friend_user_image_b_mysql, $inp_friend_text_a_mysql, $inp_friend_text_b_mysql, '$inp_friend_datetime')")
			or die(mysqli_error($link));


			// Delete request
			$result = mysqli_query($link, "DELETE FROM $t_users_friends_requests WHERE fr_id=$fr_id_mysql AND fr_to_user_id=$user_id_mysql");


			// Send e-mail to the "from" friend
			// Include his language
			include("$root/_admin/_translations/site/$l/users/ts_users.php");



			// Mail from
			$host = $_SERVER['HTTP_HOST'];

			// Link
			$link = $configSiteURLSav . "/users/view_profile&user_id=$my_user_id";

			// IP
			$ip = $_SERVER['REMOTE_ADDR'];
			$ip = output_html($ip);
	
			$to      = "$get_from_user_email";
			$subject = str_replace("%alias%", "$get_my_user_alias", $l_alias_accepted_your_friend_request);
			$l_email_accepted_your_friend_request = str_replace("%alias%", "$get_my_user_alias", $l_email_accepted_your_friend_request);
			$message = "$l_email_accepted_your_friend_request\n\n$link\n\n--\n$host";
		
			$headers = "From: $configFromEmailSav" . "\r\n" .
			    "Reply-To: $configFromEmailSav" . "\r\n" .
			    'X-Mailer: PHP/' . phpversion();
	
			mail($to, $subject, $message, $headers);



			
			// Header
			$url = "friend_requests.php?ft=success&fm=friend_request_accepted";
			header("Location: $url");
			exit;
		}
	}
	elseif($action == "accept"){
		// Get that friend request
		$fr_id_mysql = quote_smart($link, $fr_id);

		$query = "SELECT fr_id, fr_from_user_id, fr_to_user_id FROM $t_users_friends_requests WHERE fr_id=$fr_id_mysql AND fr_to_user_id=$user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_fr_id, $get_fr_from_user_id, $get_fr_to_user_id) = $row;
		if($get_fr_id == ""){

			echo"
			<h1>$l_friend_requests</h1>
			
			<!-- Feedback -->
				<div class=\"warning\"><p>$l_friend_request_not_found</p></div>
			<!-- //Feedback -->

			<p>
			<a href=\"friend_requests.php?l=$l\"><img src=\"_gfx/go-previous.png\" alt=\"go-previous.png\" /></a>
			<a href=\"friend_requests.php?l=$l\">$l_previous</a>
			</p>
			";
		}
		else{	

			// User info
			$q = "SELECT user_id, user_name, user_alias, user_language, user_rank FROM $t_users WHERE user_id='$get_fr_from_user_id'";
			$r = mysqli_query($link, $q);
			$rowb = mysqli_fetch_row($r);
			list($get_user_id, $get_user_name, $get_user_alias, $get_user_language, $get_user_rank) = $rowb;

			// Profile
			$q = "SELECT profile_id, profile_city, profile_country, profile_work, profile_university, profile_high_school, profile_relationship FROM $t_users_profile WHERE profile_user_id='$get_fr_from_user_id'";
			$r = mysqli_query($link, $q);
			$rowb = mysqli_fetch_row($r);
			list($get_profile_id, $get_profile_city, $get_profile_country, $get_profile_work, $get_profile_university, $get_profile_high_school, $get_profile_relationship) = $rowb;
	
			// Photo
			$q = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id='$get_fr_from_user_id' AND photo_profile_image='1'";
			$r = mysqli_query($link, $q);
			$rowb = mysqli_fetch_row($r);
			list($get_photo_id, $get_photo_destination) = $rowb;


	
			echo"
			<h1>$l_friend_requests</h1>


			<!-- Focus -->
				<script type=\"text/javascript\">  
					\$(document).ready(function() {
						\$('[name=\"inp_text\"]').focus();
					});
				</script>
			<!-- //Focus -->
			
			<!-- Accept friend form -->
				<div style=\"float:left;padding-right: 15px;\">
					<p style=\"padding:0;margin: 20px 0px 8px 0px;\">
					";
					if($get_photo_id != ""){
						echo"
						<a href=\"view_profile.php?user_id=$get_fr_from_user_id&amp;l=$l\"><img src=\"$root/image.php?width=85&amp;height=85&amp;cropratio=1:1&amp;image=/_uploads/users/images/$get_fr_from_user_id/$get_photo_destination\" alt=\"$get_photo_destination\" class=\"image_rounded\" /></a>
						";
					}
					else{
						echo"
						<a href=\"view_profile.php?user_id=$get_fr_from_user_id&amp;l=$l\"><img src=\"_gfx/avatar_blank_85.png\" style=\"position: relative; top: 0; left: 0;\" alt=\"Avatar\" class=\"image_rounded\" /></a>
						";
					}
					echo"
					</p>
				</div>
				<div style=\"float:left;padding-right: 15px;\">
					<form method=\"POST\" action=\"friend_requests.php?action=do_accept_friend_request&amp;fr_id=$fr_id&amp;process=1&amp;l=$l\" enctype=\"multipart/form-data\">

					";
					echo"
					<p>$l_how_do_you_know_this_friend<br />
					<textarea name=\"inp_text\" rows=\"4\" cols=\"40\"></textarea>
					</p>

					<p>
					<input class=\"btn btn-primary\" type=\"submit\" value=\"$l_accept_friend_request\" />
					</p>

					</form>
				</div>
				<div class=\"clear\"></div>
			<!-- //Accept friend form -->

			<p><br />
			<a href=\"friend_requests.php?l=$l\"><img src=\"_gfx/go-previous.png\" alt=\"go-previous.png\" /></a>
			<a href=\"friend_requests.php?l=$l\">$l_previous</a>
			</p>
			";
		}
	}
	elseif($action == ""){

		echo"
		<h1>$l_friend_requests</h1>


		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "friend_request_not_found"){
					$fm = "$l_friend_request_not_found";
				}
				elseif($fm == "friend_request_declined"){
					$fm = "$l_friend_request_declined";
				}
				elseif($fm == "friend_request_accepted"){
					$fm = "$l_friend_request_accepted";
				}
				else{
					$fm = "$ft";
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->


		<!-- Display requests -->
			";
			$count = 0;
			$query = "SELECT fr_id, fr_from_user_id, fr_to_user_id, fr_text, fr_datetime FROM $t_users_friends_requests WHERE fr_to_user_id=$user_id_mysql";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_fr_id, $get_fr_from_user_id, $get_fr_to_user_id, $get_fr_text, $get_fr_datetime) = $row;

				// User info
				$q = "SELECT user_id, user_name, user_alias, user_language, user_rank FROM $t_users WHERE user_id='$get_fr_from_user_id'";
				$r = mysqli_query($link, $q);
				$rowb = mysqli_fetch_row($r);
				list($get_user_id, $get_user_name, $get_user_alias, $get_user_language, $get_user_rank) = $rowb;


				// Profile
				$q = "SELECT profile_id, profile_city, profile_country, profile_work, profile_university, profile_high_school, profile_relationship FROM $t_users_profile WHERE profile_user_id='$get_fr_from_user_id'";
				$r = mysqli_query($link, $q);
				$rowb = mysqli_fetch_row($r);
				list($get_profile_id, $get_profile_city, $get_profile_country, $get_profile_work, $get_profile_university, $get_profile_high_school, $get_profile_relationship) = $rowb;
	

				// Photo
				$q = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id='$get_fr_from_user_id' AND photo_profile_image='1'";
				$r = mysqli_query($link, $q);
				$rowb = mysqli_fetch_row($r);
				list($get_photo_id, $get_photo_destination) = $rowb;
	
				echo"
				<div style=\"float: left;padding-right: 10px;\">
					<p style=\"padding:0;margin: 8px 0px 8px 0px;\">
					";
					if($get_photo_id != ""){
						echo"
						<a href=\"view_profile.php?user_id=$get_fr_from_user_id&amp;l=$l\"><img src=\"$root/image.php?width=85&amp;height=85&amp;cropratio=1:1&amp;image=/_uploads/users/images/$get_fr_from_user_id/$get_photo_destination\" alt=\"$get_photo_destination\" class=\"image_rounded\" /></a>
						";
					}
					else{
						echo"
						<a href=\"view_profile.php?user_id=$get_fr_from_user_id&amp;l=$l\"><img src=\"_gfx/avatar_blank_85.png\" style=\"position: relative; top: 0; left: 0;\" alt=\"Avatar\" class=\"image_rounded\" /></a>
						";
					}
					echo"
					</p>
				</div>
				<div style=\"float: left;\">
					<p style=\"padding:0;margin: 8px 0px 8px 0px;\" class=\"grey\"><a href=\"view_profile.php?user_id=$get_fr_from_user_id&amp;l=$l\" style=\"font-weight:bold;color:#000;\">$get_user_alias</a> @$get_user_name</p>
					

					<p>
					$get_fr_text
					</p>


					<p>
					<a href=\"friend_requests.php?action=accept&amp;fr_id=$get_fr_id&amp;l=$l\" class=\"btn btn-default\"><span class=\"glyphicon glyphicon-ok\"></span> $l_accept</a>
					<a href=\"friend_requests.php?action=decline&amp;fr_id=$get_fr_id&amp;process=1&amp;l=$l\" class=\"btn btn-default\"><span class=\"glyphicon glyphicon-remove\"></span> $l_decline</a>
					</p>
				</div>
				<div class=\"clear\"></div>
				";
	
				$count = $count+1;
			}

			if($count == 0){
				echo"<p>$l_there_are_no_pending_friend_request</p>";
			}
			echo"
		<!-- //Display requests -->
		";
	}
}
else{
	echo"
	<table>
	 <tr> 
	  <td style=\"padding-right: 6px;\">
		<p>
		<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"Loading\" />
		</p>
	  </td>
	  <td>
		<h1>Loading</h1>
	  </td>
	 </tr>
	</table>
		
	<meta http-equiv=\"refresh\" content=\"1;url=login.php?l=$l\">
	";
}
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>