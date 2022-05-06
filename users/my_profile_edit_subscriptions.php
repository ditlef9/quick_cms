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
$website_title = "$l_subscriptions - $l_my_profile - $l_users";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");



/*- Content --------------------------------------------------------------------------- */

if($action == "unsubscribe_and_source_is_emails"){
	// The user has clicked "unsubscribe" on a email link.
	$user_id = $_GET['user_id'];
	$user_id = output_html($user_id);
	$user_id_mysql = quote_smart($link, $user_id);
	
	$registered_time = $_GET['registered_time'];
	$registered_time = output_html($registered_time);
	$registered_time_mysql = quote_smart($link, $registered_time);
	
	$query = "SELECT user_id FROM $t_users WHERE user_id=$user_id_mysql AND user_registered_time=$registered_time_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id) = $row;
	if($get_user_id != ""){
		// Remove from all lists
		$result = mysqli_query($link, "UPDATE $t_users_email_subscriptions SET es_on_off=0 WHERE es_user_id=$user_id_mysql");
		

		echo"
		<h1>Unsubscribe</h1>

		<p>
		You have unsubscribed successfully.
		We will no longer send you e-mails.
		</p>
		";
	}
	else{
		echo"<p>Wrong parameters.</p>";
	}
}


if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	// Get user
	$user_id = $_SESSION['user_id'];
	$user_id_mysql = quote_smart($link, $user_id);
	$security = $_SESSION['security'];
	$security_mysql = quote_smart($link, $security);

	$query = "SELECT user_id, user_email, user_name, user_alias, user_password, user_password_replacement, user_password_date, user_salt, user_security, user_rank, user_verified_by_moderator, user_first_name, user_middle_name, user_last_name, user_language, user_country_id, user_country_name, user_city_name, user_timezone_utc_diff, user_timezone_value, user_measurement, user_date_format, user_gender, user_height, user_dob, user_registered, user_registered_time, user_newsletter, user_privacy, user_views, user_views_ipblock, user_points, user_points_rank, user_likes, user_dislikes, user_status, user_login_tries, user_last_online, user_last_online_time, user_last_ip, user_synchronized, user_notes, user_marked_as_spammer FROM $t_users WHERE user_id=$user_id_mysql AND user_security=$security_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_password, $get_my_user_password_replacement, $get_my_user_password_date, $get_my_user_salt, $get_my_user_security, $get_my_user_rank, $get_my_user_verified_by_moderator, $get_my_user_first_name, $get_my_user_middle_name, $get_my_user_last_name, $get_my_user_language, $get_my_user_country_id, $get_my_user_country_name, $get_my_user_city_name, $get_my_user_timezone_utc_diff, $get_my_user_timezone_value, $get_my_user_measurement, $get_my_user_date_format, $get_my_user_gender, $get_my_user_height, $get_my_user_dob, $get_my_user_registered, $get_my_user_registered_time, $get_my_user_newsletter, $get_my_user_privacy, $get_my_user_views, $get_my_user_views_ipblock, $get_my_user_points, $get_my_user_points_rank, $get_my_user_likes, $get_my_user_dislikes, $get_my_user_status, $get_my_user_login_tries, $get_my_user_last_online, $get_my_user_last_online_time, $get_my_user_last_ip, $get_my_user_synchronized, $get_my_user_notes, $get_my_user_marked_as_spammer) = $row;

	if($get_my_user_id == ""){
		echo"<h1>Error</h1><p>Error with user id.</p>"; 
		$_SESSION = array();
		session_destroy();
		die;
	}

	if($action == "save"){

		$inp_newsletter = $_POST['inp_newsletter'];
		$inp_newsletter = output_html($inp_newsletter);
		$inp_newsletter_mysql = quote_smart($link, $inp_newsletter);
		$result = mysqli_query($link, "UPDATE $t_users SET user_newsletter=$inp_newsletter_mysql WHERE user_id=$get_my_user_id");

		$inp_es_friend_request = $_POST['inp_es_friend_request'];
		$inp_es_friend_request = output_html($inp_es_friend_request);
		$inp_es_friend_request_mysql = quote_smart($link, $inp_es_friend_request);
		$result = mysqli_query($link, "UPDATE $t_users_email_subscriptions SET es_on_off=$inp_es_friend_request_mysql WHERE es_user_id=$user_id_mysql AND es_type='friend_request'");

		$inp_es_status_comments = $_POST['inp_es_status_comments'];
		$inp_es_status_comments = output_html($inp_es_status_comments);
		$inp_es_status_comments_mysql = quote_smart($link, $inp_es_status_comments);
		$result = mysqli_query($link, "UPDATE $t_users_email_subscriptions SET es_on_off=$inp_es_status_comments_mysql WHERE es_user_id=$user_id_mysql AND es_type='status_comments'");


		$inp_es_status_replies = $_POST['inp_es_status_replies'];
		$inp_es_status_replies = output_html($inp_es_status_replies);
		$inp_es_status_replies_mysql = quote_smart($link, $inp_es_status_replies);
		$result = mysqli_query($link, "UPDATE $t_users_email_subscriptions SET es_on_off=$inp_es_status_replies_mysql WHERE es_user_id=$user_id_mysql AND es_type='status_replies'");


		$inp_es_my_birthday = $_POST['inp_es_my_birthday'];
		$inp_es_my_birthday = output_html($inp_es_my_birthday);
		$inp_es_my_birthday_mysql = quote_smart($link, $inp_es_my_birthday);
		$result = mysqli_query($link, "UPDATE $t_users_email_subscriptions SET es_on_off=$inp_es_my_birthday_mysql WHERE es_user_id=$user_id_mysql AND es_type='my_birthday'");


		$url = "my_profile_edit_subscriptions.php?l=$l&ft=success&fm=changes_saved"; 
		if($process == "1"){
			header("Location: $url");
		}
		else{
			echo"<meta http-equiv=\"refresh\" content=\"1;url=$url\">";
		}
		exit;
	}
	if($action == ""){
		// Check friend request
		$q = "SELECT es_on_off FROM $t_users_email_subscriptions WHERE es_user_id=$user_id_mysql AND es_type='friend_request'";
		$r = mysqli_query($link, $q);
		$rowb = mysqli_fetch_row($r);
		list($get_es_friend_request) = $rowb;
		
		$q = "SELECT es_on_off FROM $t_users_email_subscriptions WHERE es_user_id=$user_id_mysql AND es_type='status_comments'";
		$r = mysqli_query($link, $q);
		$rowb = mysqli_fetch_row($r);
		list($get_es_status_comments) = $rowb;


		
		$q = "SELECT es_on_off FROM $t_users_email_subscriptions WHERE es_user_id=$user_id_mysql AND es_type='status_replies'";
		$r = mysqli_query($link, $q);
		$rowb = mysqli_fetch_row($r);
		list($get_es_status_replies) = $rowb;
		if($get_es_status_replies == ""){
			$inp_es_user_id = quote_smart($link, $get_my_user_id);
			mysqli_query($link, "INSERT INTO $t_users_email_subscriptions
			(es_id, es_user_id, es_type, es_on_off) 
			VALUES 
			(NULL, $inp_es_user_id, 'status_replies', '1')")
			or die(mysqli_error($link));

			$get_es_status_replies = "1";
		}

		$q = "SELECT es_on_off FROM $t_users_email_subscriptions WHERE es_user_id=$user_id_mysql AND es_type='my_birthday'";
		$r = mysqli_query($link, $q);
		$rowb = mysqli_fetch_row($r);
		list($get_es_my_birthday) = $rowb;
		if($get_es_my_birthday == ""){
			$inp_es_user_id = quote_smart($link, $get_my_user_id);
			mysqli_query($link, "INSERT INTO $t_users_email_subscriptions
			(es_id, es_user_id, es_type, es_on_off) 
			VALUES 
			(NULL, $inp_es_user_id, 'my_birthday', '1')")
			or die(mysqli_error($link));

			$get_es_my_birthday = "1";
		}

		echo"
		<h1>$l_subscriptions</h1>


		<!-- You are here -->
			<div class=\"you_are_here\">
				<p>
				<b>$l_you_are_here:</b><br />
				<a href=\"my_profile.php?l=$l\">$l_my_profile</a>
				&gt; 
				<a href=\"my_profile_edit_subscriptions.php?l=$l\">$l_subscriptions</a>
				</p>
			</div>
		<!-- //You are here -->

		<form method=\"POST\" action=\"my_profile_edit_subscriptions.php?action=save&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\" name=\"nameform\">

		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				else{
					$fm = "$ft";
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->


		<!-- Focus -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_profile_newsletter\"]').focus();
		});
		</script>
		<!-- //Focus -->



		<p>
		$l_newsletter:<br />
		<input type=\"radio\" name=\"inp_newsletter\" value=\"1\""; if($get_my_user_newsletter == "1" OR $get_my_user_newsletter == ""){ echo" checked=\"checked\""; } echo" /> $l_yes
		&nbsp;
		<input type=\"radio\" name=\"inp_newsletter\" value=\"0\""; if($get_my_user_newsletter == "0"){ echo" checked=\"checked\""; } echo" /> $l_no
		</p>

		<p>
		$l_send_me_email_when_i_get_a_friend_request:<br />
		<input type=\"radio\" name=\"inp_es_friend_request\" value=\"1\""; if($get_es_friend_request == "1"){ echo" checked=\"checked\""; } echo" /> $l_yes
		&nbsp;
		<input type=\"radio\" name=\"inp_es_friend_request\" value=\"0\""; if($get_es_friend_request == "0" OR $get_es_friend_request == ""){ echo" checked=\"checked\""; } echo" /> $l_no
		</p>

		<p>
		$l_send_me_email_when_i_get_a_comment:<br />
		<input type=\"radio\" name=\"inp_es_status_comments\" value=\"1\""; if($get_es_status_comments == "1"){ echo" checked=\"checked\""; } echo" /> $l_yes
		&nbsp;
		<input type=\"radio\" name=\"inp_es_status_comments\" value=\"0\""; if($get_es_status_comments == "0" OR $get_es_status_comments == ""){ echo" checked=\"checked\""; } echo" /> $l_no
		</p>

		<p>
		$l_send_me_email_when_someone_replies_to_a_status_that_i_have_written:<br />
		<input type=\"radio\" name=\"inp_es_status_replies\" value=\"1\""; if($get_es_status_replies == "1"){ echo" checked=\"checked\""; } echo" /> $l_yes
		&nbsp;
		<input type=\"radio\" name=\"inp_es_status_replies\" value=\"0\""; if($get_es_status_replies == "0" OR $get_es_status_comments == ""){ echo" checked=\"checked\""; } echo" /> $l_no
		</p>

		<p>
		$l_send_me_email_on_my_birthday:<br />
		<input type=\"radio\" name=\"inp_es_my_birthday\" value=\"1\""; if($get_es_my_birthday == "1"){ echo" checked=\"checked\""; } echo" /> $l_yes
		&nbsp;
		<input type=\"radio\" name=\"inp_es_my_birthday\" value=\"0\""; if($get_es_my_birthday == "0" OR $get_es_my_birthday == ""){ echo" checked=\"checked\""; } echo" /> $l_no
		</p>


		<p>
		<input type=\"submit\" value=\"$l_save\" class=\"btn btn-success\" />
		</p>

		</form>

		";
	}
}

/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>