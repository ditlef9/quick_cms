<?php
/*- Functions ------------------------------------------------------------------------- */
include("../../_admin/_functions/output_html.php");
include("../../_admin/_functions/clean.php");
include("../../_admin/_functions/quote_smart.php");


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
	(user_id, user_email, user_name, user_alias, user_password, user_salt, user_language, user_gender, user_measurement, user_dob, user_registered, user_registered_time, user_last_online,  user_last_online_time, user_rank, user_last_ip, user_synchronized) 
	VALUES 
	(NULL, $inp_user_email_mysql, $inp_user_alias_mysql, $inp_user_alias_mysql, $inp_user_password_mysql, $inp_user_salt_mysql, $inp_user_language_mysql, $inp_user_gender_mysql, $inp_user_measurement_mysql, $inp_user_dob_mysql, $inp_user_registered_mysql, '$time', $inp_user_user_last_online_mysql, '$time', $inp_user_user_rank_mysql, $inp_user_user_last_ip_mysql, $inp_user_user_synchronized_mysql)")
	or die(mysqli_error($link));

	// Get my User ID
	$query = "SELECT user_id FROM $t_users WHERE user_email=$inp_user_email_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id) = $row;


			
	// Insert profile			
	mysqli_query($link, "INSERT INTO $t_users_profile
	(profile_id, profile_user_id, profile_country, profile_newsletter, profile_views, profile_privacy) 
	VALUES 
	(NULL, '$get_user_id', '', '1', '0', 'public')")
	or die(mysqli_error($link));


	echo"$get_user_id";

}
else{
	echo"$fm";
}

?>