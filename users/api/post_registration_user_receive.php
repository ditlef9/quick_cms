<?php
/*- Functions ------------------------------------------------------------------------- */
include("../../_admin/_functions/output_html.php");
include("../../_admin/_functions/clean.php");
include("../../_admin/_functions/quote_smart.php");



/*- Website config --------------------------------------------------------------------------- */
include("../../_admin/_data/config/meta.php");
include("../../_admin/_data/config/user_system.php");
include("../../_admin/_data/logo.php");



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
$t_users_api_sessions		= $mysqlPrefixSav . "users_api_sessions";

$t_stats_users_registered_weekly  = $mysqlPrefixSav . "stats_users_registered_weekly";
$t_stats_users_registered_monthly = $mysqlPrefixSav . "stats_users_registered_monthly";
$t_stats_users_registered_yearly  = $mysqlPrefixSav . "stats_users_registered_yearly";

/*- Variables ------------------------------------------------------------------------- */
$fm = "";

if(isset($_POST['inp_user_email'])){
	$inp_user_email = $_POST['inp_user_email'];
	$inp_user_email = output_html($inp_user_email);
	$inp_user_email_mysql = quote_smart($link, $inp_user_email);

	// Check if it alreaddy exists
	$query = "SELECT user_id FROM $t_users WHERE user_email=$inp_user_email_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id) = $row;

	if($get_user_id != ""){
		$fm = "Email already exists";
	}

}
else{
	$fm = "Missing email";
}

if(isset($_POST['inp_user_password'])){
	$inp_user_password = $_POST['inp_user_password'];
	$inp_user_password = output_html($inp_user_password);
	$inp_user_password_mysql = quote_smart($link, $inp_user_password);
}
else{
	$fm = "Missing password";
}

if(isset($_POST['inp_user_salt'])){
	$inp_user_salt = $_POST['inp_user_salt'];
	$inp_user_salt = output_html($inp_user_salt);
	$inp_user_salt_mysql = quote_smart($link, $inp_user_salt);
}
else{
	$fm = "Missing salt";
}

if(isset($_POST['inp_user_alias'])){
	$inp_user_alias = $_POST['inp_user_alias'];
	$inp_user_alias = output_html($inp_user_alias);
	$inp_user_alias_mysql = quote_smart($link, $inp_user_alias);
}
else{
	$fm = "Missing alias";
}

if(isset($_POST['inp_user_dob'])){
	$inp_user_dob = $_POST['inp_user_dob'];
	$inp_user_dob = output_html($inp_user_dob);
	$inp_user_dob_mysql = quote_smart($link, $inp_user_dob);
}
else{
	$fm = "Missing dob";
}

if(isset($_POST['inp_user_gender'])){
	$inp_user_gender = $_POST['inp_user_gender'];
	$inp_user_gender = output_html($inp_user_gender);
	$inp_user_gender_mysql = quote_smart($link, $inp_user_gender);
}
else{
	$fm = "Missing gender";
}


if(isset($_POST['inp_user_measurement'])){
	$inp_user_measurement = $_POST['inp_user_measurement'];
	$inp_user_measurement = output_html($inp_user_measurement);
	$inp_user_measurement_mysql = quote_smart($link, $inp_user_measurement);
}
else{
	$fm = "Missing measurement";
}

if(isset($_POST['inp_user_registered'])){
	$inp_user_registered = $_POST['inp_user_registered'];
	$inp_user_registered = output_html($inp_user_registered);
	$inp_user_registered_mysql = quote_smart($link, $inp_user_registered);
}
else{
	$fm = "Missing registered";
}

if(isset($_POST['inp_user_language'])){
	$inp_user_language = $_POST['inp_user_language'];
	$inp_user_language = output_html($inp_user_language);
	$inp_user_language_mysql = quote_smart($link, $inp_user_language);
}
else{
	$fm = "Missing language";
}


if(isset($_POST['inp_device_source'])){
	$inp_device_source = $_POST['inp_device_source'];
	$inp_device_source = output_html($inp_device_source);
}
else{
	$inp_device_source = "";
}
$inp_device_source_mysql = quote_smart($link, $inp_device_source);


if(isset($_POST['inp_profile_city'])){
	$inp_profile_city = $_POST['inp_profile_city'];
	$inp_profile_city = output_html($inp_profile_city);
}
else{
	$inp_profile_city = "";
}
$inp_profile_city_mysql = quote_smart($link, $inp_profile_city);


if(isset($_POST['inp_profile_country'])){
	$inp_profile_country = $_POST['inp_profile_country'];
	$inp_profile_country = output_html($inp_profile_country);
}
else{
	$inp_profile_country = "";
}
$inp_profile_country_mysql = quote_smart($link, $inp_profile_country);


if(isset($_POST['inp_profile_newsletter'])){
	$inp_profile_newsletter = $_POST['inp_profile_newsletter'];
	$inp_profile_newsletter = output_html($inp_profile_newsletter);
}
else{
	$inp_profile_newsletter = "0";
}
$inp_profile_newsletter_mysql = quote_smart($link, $inp_profile_newsletter);

if(isset($_POST['inp_device_source'])){
	$inp_device_source = $_POST['inp_device_source'];
	$inp_device_source = output_html($inp_device_source);
}
else{
	$inp_device_source = "";
}
$inp_device_source_mysql = quote_smart($link, $inp_device_source);



if($fm == ""){
	$inp_user_user_last_online = date("Y-m-d H:i:s");
	$inp_user_user_last_online_mysql = quote_smart($link, $inp_user_user_last_online);

	$inp_user_user_rank = "user";
	$inp_user_user_rank_mysql = quote_smart($link, $inp_user_user_rank);

	$inp_user_user_last_ip = $_SERVER['REMOTE_ADDR'];
	$inp_user_user_last_ip_mysql = quote_smart($link, $uinp_ser_user_last_ip);

	$inp_user_user_synchronized = date("Y-m-d");
	$inp_user_user_synchronized_mysql = quote_smart($link, $inp_user_user_synchronized);


	$time = time();

	// Insert into users
	mysqli_query($link, "INSERT INTO $t_users 
	(user_id, user_email, user_name, user_alias, user_password, user_salt, user_language, user_gender, user_measurement, user_dob, user_registered, user_registered_time, user_last_online,  user_last_online_time, user_rank, user_last_ip, user_synchronized, user_notes, user_marked_as_spammer) 
	VALUES 
	(NULL, $inp_user_email_mysql, $inp_user_alias_mysql, $inp_user_alias_mysql, $inp_user_password_mysql, $inp_user_salt_mysql, $inp_user_language_mysql, $inp_user_gender_mysql, $inp_user_measurement_mysql, $inp_user_dob_mysql, $inp_user_registered_mysql, '$time', $inp_user_user_last_online_mysql, '$time', $inp_user_user_rank_mysql, $inp_user_user_last_ip_mysql, $inp_user_user_synchronized_mysql, $inp_device_source_mysql, '0')")
	or die(mysqli_error($link));

	// Get my User ID
	$query = "SELECT user_id FROM $t_users WHERE user_email=$inp_user_email_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id) = $row;

			
	// Insert profile			
	mysqli_query($link, "INSERT INTO $t_users_profile
	(profile_id, profile_user_id, profile_city, profile_country, profile_newsletter, profile_views, profile_privacy) 
	VALUES 
	(NULL, '$get_user_id', $inp_profile_city_mysql, $inp_profile_country_mysql, $inp_profile_newsletter_mysql, '0', 'public')")
	or die(mysqli_error($link));




	// Set user verified
	$result = mysqli_query($link, "UPDATE $t_users SET user_verified_by_moderator='1' WHERE user_id='$get_user_id'");

	
	// Create api session
	if(isset($_POST['inp_device_id'])){
		$inp_device_id = $_POST['inp_device_id'];
		$inp_device_id = strip_tags(stripslashes($inp_device_id));
		$inp_device_id_mysql = quote_smart($link, $inp_device_id);

		$inp_device_name = $_POST['inp_device_name'];
		$inp_device_name = strip_tags(stripslashes($inp_device_name));
		$inp_device_name_mysql = quote_smart($link, $inp_device_name);

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
		
		// Check if this exists
		$query = "SELECT session_id, session_user_id, session_device_id, session_device_name, session_device_source, session_user_agent, session_ip, session_hostname, session_start_datetime, session_valid_to_datetime, session_last_used_datetime FROM $t_users_api_sessions WHERE session_device_id=$inp_device_id_mysql AND session_device_name=$inp_device_name_mysql AND session_device_source=$inp_device_source_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_session_id, $get_session_user_id, $get_session_device_id, $get_session_device_name, $get_session_device_source, $get_session_user_agent, $get_session_ip, $get_session_hostname, $get_session_start_datetime, $get_session_valid_to_datetime, $get_session_last_used_datetime) = $row;

		if($get_session_id == ""){
			// Insert
			mysqli_query($link, "INSERT INTO $t_users_api_sessions 
			(session_id, session_user_id, session_device_id, session_device_name, session_device_source, session_user_agent, session_ip, session_hostname, session_start_datetime, session_valid_to_datetime, session_last_used_datetime) 
			VALUES
			(NULL, $get_user_id, $inp_device_id_mysql, $inp_device_name_mysql, $inp_device_source_mysql, $inp_user_agent_mysql, $inp_ip_mysql, $inp_hostname_mysql, '$datetime', '$datetime_valid_to', '$datetime')") or die(mysqli_error($link));
		}
		else{
			// Update
			$result = mysqli_query($link, "UPDATE $t_users_api_sessions SET session_user_id='$get_user_id', session_user_agent=$inp_user_agent_mysql, session_ip=$inp_ip_mysql, session_hostname=$inp_hostname_mysql, session_valid_to_datetime='$datetime', session_last_used_datetime='$datetime_valid_to' WHERE session_id=$get_session_id");
		}
	} // session


	// Statistics
	// --> weekly
	$day = date("d");
	$month = date("m");
	$week = date("W");
	$year = date("Y");

	$query = "SELECT weekly_id, weekly_users_registed FROM $t_stats_users_registered_weekly WHERE weekly_week=$week AND weekly_year=$year";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_weekly_id,  $get_weekly_users_registed) = $row;
	if($get_weekly_id == ""){
		mysqli_query($link, "INSERT INTO $t_stats_users_registered_weekly 
		(weekly_id, weekly_week, weekly_year, weekly_users_registed, weekly_last_updated, weekly_last_updated_day, weekly_last_updated_month, weekly_last_updated_year) 
		VALUES 
		(NULL, $week, $year, 1, '$datetime', $day, $month, $year)")
		or die(mysqli_error($link));
	}
	else{
		$inp_counter = $get_weekly_users_registed+1;
		$result = mysqli_query($link, "UPDATE $t_stats_users_registered_weekly SET weekly_users_registed=$inp_counter, 
						weekly_last_updated='$datetime', weekly_last_updated_day=$day, weekly_last_updated_month=$month, weekly_last_updated_year=$year WHERE weekly_id=$get_weekly_id") or die(mysqli_error($link));
	}

	// --> monthly
	$query = "SELECT monthly_id, monthly_users_registed FROM $t_stats_users_registered_monthly WHERE monthly_month=$month AND monthly_year=$year";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_monthly_id,  $get_monthly_users_registed) = $row;
	if($get_monthly_id == ""){
		mysqli_query($link, "INSERT INTO $t_stats_users_registered_monthly 
		(monthly_id, monthly_month, monthly_year, monthly_users_registed, monthly_last_updated, monthly_last_updated_day, monthly_last_updated_month, monthly_last_updated_year ) 
		VALUES 
		(NULL, $month, $year, 1, '$datetime', $day, $month, $year)")
		or die(mysqli_error($link));
	}
	else{
		$inp_counter = $get_monthly_users_registed+1;
		$result = mysqli_query($link, "UPDATE $t_stats_users_registered_monthly SET monthly_users_registed=$inp_counter, 
					monthly_last_updated='$datetime', monthly_last_updated_day=$day, monthly_last_updated_month=$month, monthly_last_updated_year=$year WHERE monthly_id=$get_monthly_id") or die(mysqli_error($link));
	}

	// --> yearly
	$query = "SELECT yearly_id, yearly_users_registed FROM $t_stats_users_registered_yearly WHERE yearly_year=$year";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_yearly_id, $get_yearly_users_registed) = $row;
	if($get_yearly_id == ""){
		mysqli_query($link, "INSERT INTO $t_stats_users_registered_yearly 
		(yearly_id, yearly_year, yearly_users_registed, yearly_last_updated, yearly_last_updated_day, yearly_last_updated_month, yearly_last_updated_year) 
		VALUES 
		(NULL, $year, 1, '$datetime', $day, $month, $year)")
		or die(mysqli_error($link));
	}
	else{
		$inp_counter = $get_yearly_users_registed+1;
		$result = mysqli_query($link, "UPDATE $t_stats_users_registered_yearly SET yearly_users_registed=$inp_counter, 
			yearly_last_updated='$datetime', yearly_last_updated_day=$day, yearly_last_updated_month=$month, yearly_last_updated_year=$year WHERE yearly_id=$get_yearly_id") or die(mysqli_error($link));
	}


	// Send welcome mail
	include("../../_admin/_translations/site/$inp_user_language/users/ts_create_free_account.php");
		
	$subject = $l_welcome_to . " " . $configWebsiteTitleSav;

	$message = "<html>\n";
	$message = $message. "<head>\n";
	$message = $message. "  <title>$subject</title>\n";
	$message = $message. " </head>\n";
	$message = $message. "<body>\n";

	$message = $message . "<p><a href=\"$configSiteURLSav\"><img src=\"$configSiteURLSav/$logoPathSav/$logoFileSav\" alt=\"$logoFileSav\" /></a></p>\n\n";
	$message = $message . "<h1>$l_welcome_to $configWebsiteTitleSav</h1>\n\n";
	$message = $message . "<p>$l_hi $inp_user_alias,<br /><br />\n";
	$message = $message . "$l_thank_you_for_signing_up\n";
	$message = $message . "$l_we_hope_you_will_be_pleased_with_your_membership</p>";

	$message = $message . "<p><b>$l_your_information</b><br />\n\n";
	$message = $message . "$l_email_address: $inp_user_email<br />\n";
	$message = $message . "$l_alias: $inp_user_alias</p>\n";

	$message = $message . "<p>\n\n--<br />\nBest regards<br />\n$configWebsiteTitleSav<br />\n<a href=\"$configSiteURLSav\">$configSiteURLSav</a></p>";
	$message = $message. "</body>\n";
	$message = $message. "</html>\n";


	$headers_mail[] = 'MIME-Version: 1.0';
	$headers_mail[] = 'Content-type: text/html; charset=utf-8';
	$headers_mail[] = "From: $configFromNameSav <" . $configFromEmailSav . ">";
	mail($inp_user_email, $subject, $message, implode("\r\n", $headers_mail));


	// Email to admin
	$query = "SELECT user_id, user_email, user_name, user_alias, user_language FROM $t_users WHERE user_rank='admin' OR user_rank='moderator'";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_mod_user_id, $get_mod_user_email, $get_mod_user_name, $get_mod_user_alias, $get_user_language) = $row;


		$subject = "New user $inp_user_alias at $configWebsiteTitleSav";

		$message = "<html>\n";
		$message = $message. "<head>\n";
		$message = $message. "  <title>$subject</title>\n";
		$message = $message. " </head>\n";
		$message = $message. "<body>\n";

		$message = $message . "<p><a href=\"$configSiteURLSav\"><img src=\"$configSiteURLSav/$logoPathSav/$logoFileSav\" alt=\"$logoFileSav\" /></a></p>\n\n";
		$message = $message . "<h1>New user</h1>\n\n";
		$message = $message . "<p>\n";
		$message = $message . "E-mail: $inp_user_email<br />\n";
		$message = $message . "Alias: $inp_user_alias<br />\n";
		$message = $message . "DOB: $inp_user_dob<br />\n";
		$message = $message . "Gender: $inp_user_gender<br />\n";
		$message = $message . "Measurement: $inp_user_measurement<br />\n";
		$message = $message . "Language: $inp_user_language<br />\n";
		$message = $message . "Country: $inp_profile_country<br />\n";
		$message = $message . "City: $inp_profile_city<br />\n";
		$message = $message . "Newsletter: $inp_profile_newsletter<br />\n";
		$message = $message . "Source: $inp_device_source<br />\n";
		$message = $message . "</p>\n";
		$message = $message . "<p><a href=\"$configSiteURLSav/users/view_profile.php?user_id=$get_user_id\">View profile</a></p>\n";
		$message = $message . "<p>\n\n--<br />\nBest regards<br />\n$get_mod_user_name at $configWebsiteTitleSav<br />\n";
		$message = $message . "<a href=\"$configSiteURLSav/index.php?l=$inp_user_language\">$configSiteURLSav</a></p>";
		$message = $message. "</body>\n";
		$message = $message. "</html>\n";

		// Preferences for Subject field
		$headers_mail_mod = array();
		$headers_mail_mod[] = 'MIME-Version: 1.0';
		$headers_mail_mod[] = 'Content-type: text/html; charset=utf-8';
		$headers_mail_mod[] = "From: $configFromNameSav <" . $configFromEmailSav . ">";
		mail($get_mod_user_email, $subject, $message, implode("\r\n", $headers_mail_mod));
	}

	echo"$get_user_id";

}
else{
	echo"$fm";
}

?>