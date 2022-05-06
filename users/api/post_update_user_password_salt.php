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
include("config.php");

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

if(isset($_POST['inp_user_old_password'])){
	$inp_user_old_password = $_POST['inp_user_old_password'];
	$inp_user_old_password = output_html($inp_user_old_password);
	$inp_user_old_password_mysql = quote_smart($link, $inp_user_old_password);

	// Check old password
	$query = "SELECT user_id FROM $t_users WHERE user_email=$inp_user_email_mysql AND user_password=$inp_user_old_password_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id) = $row;

	if($get_user_id == ""){
		// Can it be that Android has stored the replacement password?
		$query = "SELECT user_id FROM $t_users WHERE user_email=$inp_user_email_mysql AND user_password_replacement=$inp_user_old_password_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_user_id) = $row;


		if($get_user_id == ""){

			$fm = "Old password not correct";
		}
	}
}
else{
	$fm = "Missing old password";
}

if(isset($_POST['inp_user_old_salt'])){
	$inp_user_old_salt = $_POST['inp_user_old_salt'];
	$inp_user_old_salt = output_html($inp_user_old_salt);
	$inp_user_old_salt_mysql = quote_smart($link, $inp_user_old_salt);

	// Check old password
	$query = "SELECT user_id FROM $t_users WHERE user_email=$inp_user_email_mysql AND user_salt=$inp_user_old_salt_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id) = $row;

	if($get_user_id == ""){
		$fm = "Old password not correct";
	}
}
else{
	$fm = "Missing old salt";
}


if(isset($_POST['inp_user_new_password'])){
	$inp_user_new_password = $_POST['inp_user_new_password'];
	$inp_user_new_password = output_html($inp_user_new_password);
	$inp_user_new_password_mysql = quote_smart($link, $inp_user_new_password);
}
else{
	$fm = "Missing new password";
}

if(isset($_POST['inp_user_new_salt'])){
	$inp_user_new_salt = $_POST['inp_user_new_salt'];
	$inp_user_new_salt = output_html($inp_user_new_salt);
	$inp_user_new_salt_mysql = quote_smart($link, $inp_user_new_salt);
}
else{
	$fm = "Missing new salt";
}

if($fm == ""){
	$inp_user_last_online = date("Y-m-d H:i:s");
	$inp_user_last_online_mysql = quote_smart($link, $inp_user_last_online);

	$inp_user_last_ip = $_SERVER['REMOTE_ADDR'];
	$inp_user_last_ip_mysql = quote_smart($link, $inp_user_last_ip);

	$inp_user_synchronized = date("Y-m-d");
	$inp_user_synchronized_mysql = quote_smart($link, $inp_user_synchronized);


	// Update user
	$result = mysqli_query($link, "UPDATE $t_users SET user_password=$inp_user_new_password_mysql, user_salt=$inp_user_new_salt_mysql, user_password_replacement='', user_password_date='', user_last_online=$inp_user_last_online_mysql, user_last_ip=$inp_user_last_ip_mysql, user_synchronized=$inp_user_synchronized_mysql WHERE user_email=$inp_user_email_mysql");


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