<?php
/*- Functions ------------------------------------------------------------------------- */
include("../../_admin/_functions/output_html.php");
include("../../_admin/_functions/clean.php");
include("../../_admin/_functions/quote_smart.php");
include("../../_admin/_data/config/meta.php");


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


/*- Variables ------------------------------------------------------------------------ */
if(isset($_POST['user_email'])) {
	$inp_user_email = $_POST['user_email'];
	$inp_user_email = strip_tags(stripslashes($inp_user_email));
	$inp_user_email_mysql = quote_smart($link, $inp_user_email);


	$query = "SELECT user_id, user_email, user_name, user_alias, user_password, user_password_replacement, user_password_date, user_salt, user_security, user_language, user_synchronized FROM $t_users WHERE user_email=$inp_user_email_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_password, $get_user_password_replacement, $get_user_password_date, $get_user_salt, $get_user_security, $get_user_language, $get_user_synchronized) = $row;

	if($get_user_id == ""){
		echo"E-mail not found";
	}
	else{
		// Variables
		$current_day = date("Y-m-d");


		// Did I request a password today alreaddy?
		if($get_user_password_date == "$current_day"){
			echo"You have alreaddy requested a new password today";
			exit;
		}
		
	
		
		// Generate a new password
		$characters = '0123456789abcdefghjkmnopqrstuvwxyzABCDEFGHJKMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$password_replacement = '';
		for ($i = 0; $i < 6; $i++) {
			$password_replacement .= $characters[rand(0, $charactersLength - 1)];
		}


		// Mail
		$host = $_SERVER['HTTP_HOST'];
		$sender_ip = $_SERVER['REMOTE_ADDR'];
		$subject = "Password from $configWebsiteTitleSav";
            	$from = "FROM: $configFromEmailSav";
		$contents = "New password: $password_replacement \nIP: $sender_ip\n";
 
 
 		if(mail($inp_user_email, $subject, $contents, $from)){
		}
		else{
			echo"ERROR";
		}


		// Update MySQL
		$password_replacement_encrypted = sha1($password_replacement);
		$password_replacement_encrypted_mysql = quote_smart($link, $password_replacement_encrypted);

		$user_password_date_mysql = quote_smart($link, $current_day);

		$remote_addr = $_SERVER['REMOTE_ADDR'];
		$remote_addr = output_html($remote_addr);
		$user_last_ip_mysql = quote_smart($link, $remote_addr);
		
		$result = mysqli_query($link, "UPDATE $t_users SET user_password_replacement=$password_replacement_encrypted_mysql, user_password_date=$user_password_date_mysql, user_last_ip=$user_last_ip_mysql WHERE user_id='$get_user_id'");
	
		// Feedback
		echo"New password sent";
	}

}
else{
	echo"Missing variables";
}



?>