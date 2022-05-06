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

/*- Config ---------------------------------------------------------------------------- */
include("../../_admin/_data/config/meta.php");

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

	if($get_user_id == ""){
		$fm = "Email not found";
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


if(isset($_POST['inp_user_measurement'])){
	$inp_user_measurement = $_POST['inp_user_measurement'];
	$inp_user_measurement = output_html($inp_user_measurement);
	$inp_user_measurement_mysql = quote_smart($link, $inp_user_measurement);
}
else{
	$fm = "Missing measurement";
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
	$inp_user_last_online = date("Y-m-d H:i:s");
	$inp_user_last_online_mysql = quote_smart($link, $inp_user_last_online);

	$inp_user_last_ip = $_SERVER['REMOTE_ADDR'];
	$inp_user_last_ip_mysql = quote_smart($link, $inp_user_last_ip);

	$inp_user_synchronized = date("Y-m-d");
	$inp_user_synchronized_mysql = quote_smart($link, $inp_user_synchronized);


	// Update user
	$result = mysqli_query($link, "UPDATE $t_users SET user_measurement=$inp_user_measurement_mysql, user_language=$inp_user_language_mysql, user_last_online=$inp_user_last_online_mysql, user_last_ip=$inp_user_last_ip_mysql, user_synchronized=$inp_user_synchronized_mysql WHERE user_email=$inp_user_email_mysql");


	// Get my User ID
	$query = "SELECT user_id FROM $t_users WHERE user_email=$inp_user_email_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id) = $row;

	echo"$get_user_id";

}
else{
	echo"$fm";
}

?>