<?php
/**
*
* File: users/my_profile_settings.php
* Version 11:15 08.08.2021
* Copyright (c) 2009-2021 Sindre Andre Ditlefsen
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
$website_title = "$l_settings - $l_my_profile - $l_users";
include("$root/_webdesign/header.php");


/*- Timezone --------------------------------------------------------------------------- */
function timezone_list() {
    static $timezones = null;

    if ($timezones === null) {
        $timezones = [];
        $offsets = [];
        $now = new DateTime('now', new DateTimeZone('UTC'));

        foreach (DateTimeZone::listIdentifiers() as $timezone) {
            $now->setTimezone(new DateTimeZone($timezone));
            $offsets[] = $offset = $now->getOffset();
            $timezones[$timezone] = '(' . format_GMT_offset($offset) . ') ' . format_timezone_name($timezone);
        }

        array_multisort($offsets, $timezones);
    }

    return $timezones;
}

function format_GMT_offset($offset) {
    $hours = intval($offset / 3600);
    $minutes = abs(intval($offset % 3600 / 60));
    return 'GMT' . ($offset ? sprintf('%+03d:%02d', $hours, $minutes) : '');
}

function format_timezone_name($name) {
    $name = str_replace('/', ', ', $name);
    $name = str_replace('_', ' ', $name);
    $name = str_replace('St ', 'St. ', $name);
    return $name;
}


/*- Content --------------------------------------------------------------------------- */


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

		
		$inp_user_measurement = $_POST['inp_user_measurement'];
		$inp_user_measurement = output_html($inp_user_measurement);
		$inp_user_measurement_mysql = quote_smart($link, $inp_user_measurement);


		$inp_user_language = $_POST['inp_user_language'];
		$inp_user_language = output_html($inp_user_language);
		$inp_user_language_mysql = quote_smart($link, $inp_user_language);

		$inp_user_date_format = $_POST['inp_user_date_format'];
		$inp_user_date_format = output_html($inp_user_date_format);
		$inp_user_date_format_mysql = quote_smart($link, $inp_user_date_format);

		$inp_timezone_value = $_POST['inp_timezone_value'];
		$inp_timezone_value = output_html($inp_timezone_value);
		$inp_timezone_value_mysql = quote_smart($link, $inp_timezone_value);

		$inp_timezone_utc_diff_array = explode(")", $inp_timezone_value);
		$inp_timezone_utc_diff = str_replace("(", "", $inp_timezone_utc_diff_array[0]);
		$inp_timezone_utc_diff = str_replace("GMT", "", $inp_timezone_utc_diff);
		$inp_timezone_utc_diff_array = explode(":", $inp_timezone_utc_diff);
		$inp_timezone_utc_diff = $inp_timezone_utc_diff_array[0];
		if($inp_timezone_utc_diff == ""){
			$inp_timezone_utc_diff = "0";
		}
		$inp_timezone_utc_diff_mysql = quote_smart($link, $inp_timezone_utc_diff);


		$inp_privacy = $_POST['inp_privacy'];
		$inp_privacy = output_html($inp_privacy);
		$inp_privacy_mysql = quote_smart($link, $inp_privacy);



		$result = mysqli_query($link, "UPDATE $t_users SET 
						user_language=$inp_user_language_mysql, 
						user_date_format=$inp_user_date_format_mysql, 
						user_timezone_utc_diff=$inp_timezone_utc_diff_mysql, 
						user_timezone_value=$inp_timezone_value_mysql, 
						user_measurement=$inp_user_measurement_mysql, 
						user_privacy=$inp_privacy_mysql
						WHERE user_id=$get_my_user_id");

		
		$url = "my_profile_settings.php?l=$inp_user_language&ft=success&fm=changes_saved"; 
		if($process == "1"){
			header("Location: $url");
		}
		else{
			echo"<meta http-equiv=\"refresh\" content=\"1;url=$url\">";
		}
		exit;
	}
	if($action == ""){
		echo"
		<h1>$l_settings</h1>


		<!-- You are here -->
			<div class=\"you_are_here\">
				<p>
				<b>$l_you_are_here:</b><br />
				<a href=\"my_profile.php?l=$l\">$l_my_profile</a>
				&gt; 
				<a href=\"my_profile_settings.php?l=$l\">$l_settings</a>
				</p>
			</div>
		<!-- //You are here -->


		<form method=\"POST\" action=\"my_profile_settings.php?action=save&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\" name=\"nameform\">

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
			\$('[name=\"inp_user_measurement\"]').focus();
		});
		</script>
		<!-- //Focus -->


		<p>
		$l_prefered_messurement:<br />
		<select name=\"inp_user_measurement\"> 
			<option value=\"imperial\""; if($get_my_user_measurement == "imperial"){ echo" selected=\"selected\""; } echo">$l_imperial_units</option>
			<option value=\"metric\""; if($get_my_user_measurement == "metric"){ echo" selected=\"selected\""; } echo">$l_metric_system</option>
		</select>
		</p>

		<p>
		$l_language:<br />
		<select name=\"inp_user_language\">";

		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;

			echo"			";
			echo"<option value=\"$get_language_active_iso_two\""; if($get_language_active_iso_two == "$get_my_user_language"){ echo" selected=\"selected\""; } echo">$get_language_active_name</option>\n";
		}
		echo"
		</select>
		</p>


		<p>
		$l_date_format:<br />
		<select name=\"inp_user_date_format\"> 
			<option value=\"l jS \of F Y\""; if($get_my_user_date_format == "l jS \of F Y"){ echo" selected=\"selected\""; } echo">Monday 14th of August 2005</option>
			<option value=\"Y-m-d\""; if($get_my_user_date_format == "Y-m-d"){ echo" selected=\"selected\""; } echo">2005-08-14</option>
			<option value=\"l d. f Y\""; if($get_my_user_date_format == "l d. f Y"){ echo" selected=\"selected\""; } echo">Monday 14. august 2005</option>
		</select>
		</p>

		<p>
		$l_timezone:<br />
		<select name=\"inp_timezone_value\">\n";
		$timezones = timezone_list();
		foreach ($timezones as $key => $row) {
			echo"			";
			echo"<option value=\"$row\""; if($get_my_user_timezone_value == "$row"){ echo" selected=\"selected\""; } echo">$row</option>\n";
		}
		echo"
		</select>
		</p>

		<p>
		$l_profile_privacy:<br />
		<select name=\"inp_privacy\"> 
			<option value=\"public\""; if($get_my_user_privacy == "public"){ echo" selected=\"selected\""; } echo">$l_public</option>
			<option value=\"registered_users\""; if($get_my_user_privacy == "registered_users"){ echo" selected=\"selected\""; } echo">$l_registered_users</option>
			<option value=\"friends\""; if($get_my_user_privacy == "friends"){ echo" selected=\"selected\""; } echo">$l_friends</option>
		</select>
		</p>


		<p>
		<input type=\"submit\" value=\"$l_save\" class=\"btn btn-success\" />
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
		<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"Loading\" />
		</p>
	  </td>
	  <td>
		<h1>Loading</h1>
	  </td>
	 </tr>
	</table>
		
	<meta http-equiv=\"refresh\" content=\"1;url=index.php\">
	";
}
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>