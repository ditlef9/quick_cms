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
if(isset($_POST['inp_user_id'])) {
	$inp_user_id = $_POST['inp_user_id'];
	$inp_user_id = strip_tags(stripslashes($inp_user_id));
	$inp_user_id_mysql = quote_smart($link, $inp_user_id);
} else {
	echo"Missing user id";
	die;
}
if(isset($_POST['inp_user_password'])) {
	$inp_user_password = $_POST['inp_user_password'];
	$inp_user_password = strip_tags(stripslashes($inp_user_password));
} else {
	echo"Missing user password";
	die;
}

if(isset($_POST['inp_user_dob'])){
	$inp_user_dob = $_POST['inp_user_dob'];
	$inp_user_dob = output_html($inp_user_dob);
	$inp_user_dob_mysql = quote_smart($link, $inp_user_dob);
}
else{
	$fm = "Missing dob";
}



// Check that user exists
$query = "SELECT user_id, user_password FROM $t_users WHERE user_id=$inp_user_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_user_id, $get_user_password) = $row;
if($get_user_id == ""){
	echo"User not found";
	die;
}

// Check password
if($inp_user_password != "$get_user_password"){
	echo"Wrong password for user ID $inp_user_id";
	die;
}


// Update user
$inp_user_last_online = date("Y-m-d H:i:s");
$inp_user_last_online_mysql = quote_smart($link, $inp_user_last_online);

$inp_user_last_ip = $_SERVER['REMOTE_ADDR'];
$inp_user_last_ip_mysql = quote_smart($link, $inp_user_last_ip);

$inp_user_synchronized = date("Y-m-d");
$inp_user_synchronized_mysql = quote_smart($link, $inp_user_synchronized);

$result = mysqli_query($link, "UPDATE $t_users SET user_dob=$inp_user_dob_mysql, user_last_online=$inp_user_last_online_mysql, user_last_ip=$inp_user_last_ip_mysql, user_synchronized=$inp_user_synchronized_mysql WHERE user_id=$get_user_id");

echo"Dob updated to $inp_user_dob ok";

?>