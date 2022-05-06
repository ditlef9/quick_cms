<?php
/**
*
* File: users/create_free_account_step_5_gender.php
* Version 08:38 22.08.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
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
$website_title = "$l_gender - $l_create_free_account";
include("$root/_webdesign/header.php");


/*- Variables ----------------------------------------------------------------------- */
if(isset($_GET['referer'])) {
	$referer = $_GET["referer"];
	$referer = output_html($referer);
}
else{
	$referer = "";
}


/*- Content --------------------------------------------------------------------------- */





if(isset($_SESSION['user_id'])){
	// Get user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$my_security = $_SESSION['security'];
	$my_security_mysql = quote_smart($link, $my_security);

	$query = "SELECT user_id, user_email, user_name, user_alias, user_password, user_password_replacement, user_password_date, user_salt, user_security, user_rank, user_verified_by_moderator, user_first_name, user_middle_name, user_last_name, user_language, user_country_id, user_country_name, user_city_name, user_timezone_utc_diff, user_timezone_value, user_measurement, user_date_format, user_gender, user_height, user_dob, user_registered, user_registered_time, user_newsletter, user_privacy, user_views, user_views_ipblock, user_points, user_points_rank, user_likes, user_dislikes, user_status, user_login_tries, user_last_online, user_last_online_time, user_last_ip, user_synchronized, user_notes, user_marked_as_spammer FROM $t_users WHERE user_id=$my_user_id_mysql AND user_security=$my_security_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_password, $get_my_user_password_replacement, $get_my_user_password_date, $get_my_user_salt, $get_my_user_security, $get_my_user_rank, $get_my_user_verified_by_moderator, $get_my_user_first_name, $get_my_user_middle_name, $get_my_user_last_name, $get_my_user_language, $get_my_user_country_id, $get_my_user_country_name, $get_my_user_city_name, $get_my_user_timezone_utc_diff, $get_my_user_timezone_value, $get_my_user_measurement, $get_my_user_date_format, $get_my_user_gender, $get_my_user_height, $get_my_user_dob, $get_my_user_registered, $get_my_user_registered_time, $get_my_user_newsletter, $get_my_user_privacy, $get_my_user_views, $get_my_user_views_ipblock, $get_my_user_points, $get_my_user_points_rank, $get_my_user_likes, $get_my_user_dislikes, $get_my_user_status, $get_my_user_login_tries, $get_my_user_last_online, $get_my_user_last_online_time, $get_my_user_last_ip, $get_my_user_synchronized, $get_my_user_notes, $get_my_user_marked_as_spammer) = $row;

	if($get_my_user_id == ""){
		echo"<h1>Error</h1><p>Error with user id.</p>"; 
		$_SESSION = array();
		session_destroy();
		die;
	}


	if($process == "1"){
		// Dob
		$inp_gender = $_POST['inp_gender'];
		$inp_gender = output_html($inp_gender);
		$inp_gender_mysql = quote_smart($link, $inp_gender);

		$result = mysqli_query($link, "UPDATE $t_users SET user_gender=$inp_gender_mysql WHERE user_id=$get_my_user_id");
		

		if($referer != ""){
			$referer = stripslashes(strip_tags($referer));
			$referer = str_replace("&amp;", "&", $referer);
			$referer = str_replace("amp;", "&", $referer);
			$url = "../$referer";
		}
		else{
			$url = "view_profile.php?user_id=$get_my_user_id&l=$l";
		}
		header("Location: $url");
		exit;
	}
	if($action == ""){
		echo"
		<h1>$l_hello $get_my_user_name</h1>


		<!-- Form -->
			<form method=\"POST\" action=\"create_free_account_step_5_gender.php?l=$l&amp;process=1"; if($referer != ""){ echo"&amp;referer=$referer"; } echo"\" enctype=\"multipart/form-data\">

			<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_gender\"]').focus();
			});
			</script>
			<!-- //Focus -->

			<p><b>$l_gender:</b><br />
			<label><input type=\"radio\" name=\"inp_gender\" value=\"female\""; if($get_my_user_gender == "female"){ echo" checked=\"checked\""; } echo" />
			$l_female</label>

			<label><input type=\"radio\" name=\"inp_gender\" value=\"male\""; if($get_my_user_gender == "male"){ echo" checked=\"checked\""; } echo" />
			$l_male</label>

			<label>
			<input type=\"radio\" name=\"inp_gender\" value=\"other\""; if($get_my_user_gender == "other"){ echo" checked=\"checked\""; } echo" />
			$l_other</label>

			<label>
			<input type=\"radio\" name=\"inp_gender\" value=\"id_rather_not_say\""; if($get_my_user_gender == "id_rather_not_say"){ echo" checked=\"checked\""; } echo" />
			$l_id_rather_not_say</label>
			</p>

			<p>
			<input type=\"submit\" value=\"$l_save\" class=\"btn\" />
			</p>
			</form>

		<!-- //Form -->

		<hr />
		<p>";
		if($referer != ""){
			$referer = stripslashes(strip_tags($referer));
			$referer = str_replace("&amp;", "&", $referer);
			$referer = str_replace("amp;", "&", $referer);
			echo"
			<a href=\"$referer\" class=\"btn btn_default\">$l_skip_this_step</a>
			";
		}
		else{
			echo"
			<a href=\"create_free_account_step_5_gender.php?user_id=$my_user_id&amp;l=$l"; if($referer != ""){ echo"&amp;referer=$referer"; } echo"\" class=\"btn btn_default\">$l_skip_this_step</a>
			";
		}
		echo"
		</p>
		";
	}
}
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>