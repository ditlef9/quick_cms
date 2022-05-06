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

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_forgot_password - $l_users";
include("$root/_webdesign/header.php");



/*- Content --------------------------------------------------------------------------- */

if(isset($_GET['user_id'])) {
	$user_id = $_GET['user_id'];
	$user_id = strip_tags(stripslashes($user_id));
}
else{
	$user_id = "";
}
if(isset($_GET['key'])) {
	$key = $_GET['key'];
	$key = strip_tags(stripslashes($key));
}
else{
	$key = "";
}
if(!(isset($_SESSION['user_id']))){


	if($action == "reset_password"){
		// Find user
		$user_id_mysql = quote_smart($link, $user_id);
		$query = "SELECT user_id, user_email, user_name, user_password, user_salt, user_security, user_language, user_registered, user_last_online, user_rank, user_points, user_likes, user_dislikes, user_status, user_login_tries, user_last_ip FROM $t_users WHERE user_id=$user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_user_id, $get_user_email, $get_user_name, $get_user_password, $get_user_salt, $get_user_security, $get_user_language, $get_user_registered, $get_user_last_online, $get_user_rank, $get_user_points, $get_user_likes, $get_user_dislikes, $get_user_status, $get_user_login_tries, $get_user_last_ip) = $row;
		if($get_user_id == ""){
			$ft = "warning";
			$fm = "user_not_found";
			$url ="forgot_password.php?ft=$ft&fm=$fm&l=$l"; 
			header("Location: $url");
			exit;
		}

		// Check key
		$check_key = $get_user_id . $get_user_last_online . $get_user_last_ip;
		$check_key = md5($check_key);
		
		if($check_key == "$key"){
			// Log in user and move to change password site
					
			// Set security pin
			$security = rand(0,9999);

			// -> Logg brukeren inn
			$_SESSION['user_id'] = "$get_user_id";
			$_SESSION['security'] = "$security";
			$user_last_ip = $_SERVER['REMOTE_ADDR'];
			$user_last_ip = output_html($user_last_ip);
			$user_last_ip_mysql = quote_smart($link, $user_last_ip);

			// Update last logged in
			$inp_user_last_online = date("Y-m-d H:i:s");
			$result = mysqli_query($link, "UPDATE $t_users SET user_security='$security', user_last_online='$inp_user_last_online', user_last_ip=$user_last_ip_mysql WHERE user_id='$get_user_id'");

			// Move user
			$url = "edit_password.php?l=$get_user_language"; 
			header("Location: $url");
			exit;
		}
		else{
			// Wrong key
			$ft = "warning";
			$fm = "wrong_key";
			$url ="forgot_password.php?ft=$ft&fm=$fm&l=$l"; 
			header("Location: $url");
			exit;
		}
		
	}
	if($action == "send_new_password"){

		$inp_antispam = $_POST['inp_antispam'];
		$inp_antispam = output_html($inp_antispam);
		$inp_antispam = strtolower($inp_antispam);


		$inp_email = $_POST['inp_email'];
		$inp_email = output_html($inp_email);
		$inp_email = strtolower($inp_email);
		$inp_email_mysql = quote_smart($link, $inp_email);

		if($inp_antispam != "oslo"){
			$ft = "error";
			$fm = "wrong_antispam_question";
			$url ="forgot_password.php?ft=$ft&fm=$fm&l=$l"; 
			header("Location: $url");
			exit;
		}
		if(empty($inp_email)){
			$ft = "warning";
			$fm = "please_enter_your_email_address";
			$url ="forgot_password.php?ft=$ft&fm=$fm&l=$l"; 
			header("Location: $url");
			exit;
		}
		
		// Does that e-mail exists?
		$query = "SELECT user_id, user_email, user_name, user_password, user_salt, user_security, user_language, user_registered, user_last_online, user_rank, user_points, user_likes, user_dislikes, user_status, user_login_tries, user_last_ip FROM $t_users WHERE user_email=$inp_email_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_user_id, $get_user_email, $get_user_name, $get_user_password, $get_user_salt, $get_user_security, $get_user_language, $get_user_registered, $get_user_last_online, $get_user_rank, $get_user_points, $get_user_likes, $get_user_dislikes, $get_user_status, $get_user_login_tries, $get_user_last_ip) = $row;
		if($get_user_id == ""){
			$ft = "warning";
			$fm = "email_not_found";
			$url ="forgot_password.php?ft=$ft&fm=$fm&l=$l"; 
			header("Location: $url");
			exit;
		}

		// Send new password
		$key = $get_user_id . $get_user_last_online . $get_user_last_ip;
		$key = md5($key);


		/*- Logo -------------------------------------------------------------------------- */
		include("$root/_admin/_data/logo.php");

		// Mail from
		$host = $_SERVER['HTTP_HOST'];

		// Link
		$link = $configSiteURLSav . "/users/forgot_password.php?action=reset_password&user_id=$get_user_id&key=$key&l=$l&process=1";

		// Language for message
		$l_email_message = str_replace("%alias%", $get_user_name, $l_email_message);
		$l_email_message = str_replace("%host%", $host, $l_email_message);
	

		// IP
		$ip = $_SERVER['REMOTE_ADDR'];
		$ip = output_html($ip);


			
		$subject = "$l_forgot_password ($host)";

			
		$message = "<html>\n";
		$message = $message. "<head>\n";
		$message = $message. "  <title>$subject</title>\n";
		$message = $message. " </head>\n";
		$message = $message. "<body>\n";

		$message = $message . "<p><a href=\"$configSiteURLSav\"><img src=\"$configSiteURLSav/$logoPathSav/$logoFileSav\" alt=\"$logoFileSav\" /></a></p>\n\n";
		$message = $message . "<h1>$l_hello $get_user_name</h1>\n\n";
		$message = $message . "<p>$l_follow_the_link_below_to_change_your_password<br />\n";
		$message = $message . "<a href=\"$link\">$link</a></p>";

		$message = $message . "<p>IP: $ip</p>\n";
		$message = $message . "<p>$l_if_you_have_any_questions_then_you_can_contact_us_at <a href=\"mailto:$configFromEmailSav\">$configFromEmailSav</a>.</p>\n";

		$message = $message . "<p>\n\n--<br />\nBest regards<br />\n$configWebsiteTitleSav<br />\n<a href=\"$configSiteURLSav\">$configSiteURLSav</a></p>";
		$message = $message. "</body>\n";
		$message = $message. "</html>\n";


		// Preferences for Subject field
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=utf-8';
		$headers[] = "From: $configFromNameSav <" . $configFromEmailSav . ">";
		mail($get_user_email, $subject, $message, implode("\r\n", $headers));

				

		
		$ft = "success";
		$fm = "check_your_email";
		$url ="forgot_password.php?ft=$ft&fm=$fm&l=$l"; 
		header("Location: $url");
		exit;

			
	}
	if($action == ""){
		if(isset($_GET['inp_antispam'])){
			$inp_antispam = $_GET['inp_antispam'];
			$inp_antispam = output_html($inp_antispam);
			$inp_antispam = strtolower($inp_antispam);
		}
		if(isset($_GET['inp_antispam_image'])){
			$inp_antispam_image = $_GET['inp_antispam_image'];
			$inp_antispam_image = output_html($inp_antispam_image);
			$inp_antispam_image = strtolower($inp_antispam_image);
		}
		if(isset($_GET['inp_email'])){
			$inp_email = $_GET['inp_email'];
			$inp_email = output_html($inp_email);
			$inp_email = strtolower($inp_email);
		}



		echo"
		<h1>$l_forgot_password</h1>



		<form method=\"POST\" action=\"forgot_password.php?action=send_new_password&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\" name=\"nameform\">

		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "wrong_antispam_question"){
					$fm = "$l_users_you_answered_wrong_on_antispam_question";
				}
				elseif($fm == "please_enter_your_email_address"){
					$fm = "$l_users_please_enter_your_email_address";
				}
				elseif($fm == "email_not_found"){
					$fm = "$l_email_address_not_found";
				}
				elseif($fm == "check_your_email"){
					$fm = "$l_check_your_email";
				}
				elseif($fm == "user_not_found"){
					$fm = "$l_user_not_found";
				}
				elseif($fm == "wrong_key"){
					$fm = "$l_wrong_key";
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->


		<!-- Focus -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_email\"]').focus();
		});
		</script>
		<!-- //Focus -->



		<p>
		$l_email_address:<br />
		<input type=\"text\" name=\"inp_email\" size=\"40\" value=\""; if(isset($inp_email)){ echo"$inp_email"; } echo"\" /><br />
		</p>



		<p>
		$l_what_is_the_capital_of_norway<br />
		<input type=\"text\" name=\"inp_antispam\" size=\"40\" />
		</p>

		<p>
		<input type=\"submit\" value=\"$l_send_new_password\" class=\"btn\" />
		</p>

		</form>

		";
	}
}
else{
	echo"
	<table>
	 <tr> 
	  <td style=\"padding-right: 6px;\">
		<p>
		<img src=\"$root/_img/loading_22.gif\" alt=\"Loading\" />
		</p>
	  </td>
	  <td>
		<h1>Laster</h1>
	  </td>
	 </tr>
	</table>
		
	<meta http-equiv=\"refresh\" content=\"1;url=index.php\">
	";
}
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>