<?php
/*- Functions ------------------------------------------------------------------------- */
include("../../_admin/_functions/output_html.php");
include("../../_admin/_functions/clean.php");
include("../../_admin/_functions/quote_smart.php");

function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}


/*- MySQL ----------------------------------------------------------------------------- */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);

$mysql_config_file = "../../_admin/_data/mysql_" . $server_name . ".php";
include("$mysql_config_file");
$link = mysqli_connect($mysqlHostSav, $mysqlUserNameSav, $mysqlPasswordSav, $mysqlDatabaseNameSav);
if (!$link) {
	echo "Error MySQL link";
	die;
}


/*- MySQL Tables ---------------------------------------------------------------------- */
$t_users 	 		= $mysqlPrefixSav . "users";
$t_users_profile 		= $mysqlPrefixSav . "users_profile";
$t_users_profile_photo		= $mysqlPrefixSav . "users_profile_photo";
$t_users_api_sessions 		= $mysqlPrefixSav . "users_api_sessions";



/*- Variables ------------------------------------------------------------------------- */
// Check for session
if(isset($_POST['inp_device_id']) && isset($_POST['inp_device_name']) && isset($_POST['inp_device_source'])){
	$inp_device_id = $_POST['inp_device_id'];
	$inp_device_id = strip_tags(stripslashes($inp_device_id));
	$inp_device_id_mysql = quote_smart($link, $inp_device_id);

	$inp_device_name = $_POST['inp_device_name'];
	$inp_device_name = strip_tags(stripslashes($inp_device_name));
	$inp_device_name_mysql = quote_smart($link, $inp_device_name);

	$inp_device_source = $_POST['inp_device_source'];
	$inp_device_source = strip_tags(stripslashes($inp_device_source));
	$inp_device_source_mysql = quote_smart($link, $inp_device_source);

	// Check if this exists
	$query = "SELECT session_id, session_user_id, session_device_id, session_device_name, session_device_source, session_user_agent, session_ip, session_hostname, session_start_datetime, session_valid_to_datetime, session_last_used_datetime FROM $t_users_api_sessions WHERE session_device_id=$inp_device_id_mysql AND session_device_name=$inp_device_name_mysql AND session_device_source=$inp_device_source_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_session_id, $get_session_user_id, $get_session_device_id, $get_session_device_name, $get_session_device_source, $get_session_user_agent, $get_session_ip, $get_session_hostname, $get_session_start_datetime, $get_session_valid_to_datetime, $get_session_last_used_datetime) = $row;

	if($get_session_id == ""){
		echo"No session";
	}
	else{


		$inp_user_agent = $_SERVER['HTTP_USER_AGENT'];
		$inp_user_agent = output_html($inp_user_agent);
		$inp_user_agent_mysql = quote_smart($link, $inp_user_agent);

		$inp_ip = $_SERVER['REMOTE_ADDR'];
		$inp_ip = output_html($inp_ip);
		$inp_ip_mysql = quote_smart($link, $inp_ip);

		$inp_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$inp_hostname = output_html($inp_hostname);
		$inp_hostname_mysql = quote_smart($link, $inp_hostname);
			
		$datetime = date("Y-m-d H:i:s");
		$datetime_valid_to = date('Y-m-d H:i:s', strtotime('+1 year'));

		// Update
		$result = mysqli_query($link, "UPDATE $t_users_api_sessions SET session_user_agent=$inp_user_agent_mysql, session_ip=$inp_ip_mysql, session_hostname=$inp_hostname_mysql, session_valid_to_datetime='$datetime', session_last_used_datetime='$datetime_valid_to' WHERE session_id=$get_session_id");

		// Give user
		
		// Create array
		$rows_array = array();

		// Get user information
		$query = "SELECT user_id, user_email, user_name, user_alias, user_password, user_password_date, user_salt, user_security, user_language, user_gender, user_height, user_measurement, user_dob, user_date_format, user_registered, user_registered_time, user_last_online, user_last_online_time, user_rank, user_points, user_points_rank, user_likes, user_dislikes, user_status, user_login_tries, user_last_ip, user_synchronized, user_verified_by_moderator, user_notes FROM $t_users WHERE user_id=$get_session_user_id LIMIT 0,1";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_array($result);
		$rows_array['user'] = $row;

		// Profile
		$query = "SELECT profile_id, profile_user_id, profile_first_name, profile_middle_name, profile_last_name, profile_address_line_a, profile_address_line_b, profile_zip, profile_city, profile_country, profile_phone, profile_work, profile_university, profile_high_school, profile_languages, profile_website, profile_interested_in, profile_relationship, profile_about, profile_newsletter, profile_views, profile_privacy FROM $t_users_profile WHERE profile_user_id=$get_session_user_id LIMIT 0,1";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_array($result);
		$rows_array['profile'] = $row;

		// Image
		$query = "SELECT photo_id, photo_user_id, photo_profile_image, photo_destination, photo_uploaded, photo_uploaded_ip, photo_views, photo_likes, photo_comments, photo_x_offset, photo_y_offset, photo_text FROM $t_users_profile_photo WHERE photo_user_id=$get_session_user_id AND photo_profile_image='1' LIMIT 0,1";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_array($result);
		$rows_array['photo'] = $row;

		// Json everything
		$rows_json = json_encode(utf8ize($rows_array));

		echo"$rows_json";
	}
}
else{
	echo"Missing variable";
}
?>