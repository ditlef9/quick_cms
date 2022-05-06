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


/*- MySQL Tables -------------------------------------------------- */
$t_users 	 		= $mysqlPrefixSav . "users";
$t_users_profile 		= $mysqlPrefixSav . "users_profile";


/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['user_name'])) {
	$user_name = $_GET['user_name'];
	$user_name = strip_tags(stripslashes($user_name));
	$user_name_mysql = quote_smart($link, $user_name);
	
	$query = "SELECT user_id, user_email, user_name, user_alias, user_password, user_salt, user_security, user_language, user_synchronized FROM $t_users WHERE user_name=$user_name_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_password, $get_user_salt, $get_user_security, $get_user_language, $get_user_synchronized) = $row;

	if($get_user_id == ""){
		// Ucfirst
		$user_name = ucfirst($user_name);
		$user_name_mysql = quote_smart($link, $user_name);
	
		$query = "SELECT user_id, user_email, user_name, user_alias, user_password, user_salt, user_security, user_language, user_synchronized FROM $t_users WHERE user_name=$user_name_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_password, $get_user_salt, $get_user_security, $get_user_language, $get_user_synchronized) = $row;

		if($get_user_id == ""){
			echo"User name is available";
		}
		else{
			echo"User name is taken";
		}
	}
	else{
		echo"User name is taken";
	}

}
else{
	echo"Missing variable user_name";
}



?>