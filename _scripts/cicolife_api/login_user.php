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
if(isset($_POST['inp_user_email']) && isset($_POST['inp_user_password'])) {
	$inp_user_email = $_POST['inp_user_email'];
	$inp_user_email = strip_tags(stripslashes($inp_user_email));
	$inp_user_email_mysql = quote_smart($link, $inp_user_email);

	$inp_user_password = $_POST['inp_user_password'];
	$inp_user_password = strip_tags(stripslashes($inp_user_password));
	$inp_user_password_mysql = quote_smart($link, $inp_user_password);


	/* About password:
	* Consists of a user password
	* password is encrypted with sha1
	* $inp_user_password_encrypted =  sha1($inp_user_password);
	* $inp_user_password_mysql = quote_smart($link, $inp_user_password_encrypted);
	*/

	$query = "SELECT user_id, user_email, user_name, user_alias, user_password, user_password_replacement, user_password_date, user_salt, user_security, user_language, user_synchronized FROM $t_users WHERE user_email=$inp_user_email_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_password, $get_user_password_replacement, $get_user_password_date, $get_user_salt, $get_user_security, $get_user_language, $get_user_synchronized) = $row;

	if($get_user_id == ""){
		echo"E-mail not found";
	}
	else{
		$inp_user_password_rem = substr($inp_user_password, 1, -1);
		if($get_user_password == "$inp_user_password" OR $inp_user_password_rem == "$get_user_password"){
			$show_user = 1; 
		}
		else{
			// Did we use a replacement password?
			if($inp_user_password == "$get_user_password_replacement"  OR $inp_user_password_rem == "$get_user_password_replacement"){

				// Is replacement password out of date?
				$current_date = date("Y-m-d");
				if($get_user_password_date == "$current_date"){
					$show_user = 1;
				}
				else{
					echo"Replacement password expired";
				}
			} // correct replacement password
			else{
				echo"Wrong password";
			}
		} // correct password
	} // user not found



	if(isset($show_user) && $show_user == 1){
		// Update syncronized
		$inp_user_synchronized = date("Y-m-d");
		$inp_user_synchronized_mysql = quote_smart($link, $inp_user_synchronized);
		$result = mysqli_query($link, "UPDATE $t_users SET user_synchronized=$inp_user_synchronized_mysql WHERE user_email=$inp_user_email_mysql");

		// Create array
		$rows = array();

		// Get user information
		$query = "SELECT user_id, user_name, user_alias, user_salt, user_language, user_gender, user_height, user_measurement, user_dob, user_date_format, user_registered, user_rank, user_synchronized FROM $t_users WHERE user_email=$inp_user_email_mysql LIMIT 0,1";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_array($result,MYSQL_ASSOC)) {
			array_push($rows,$row);
		}
		

		
		// Json everything
		$rows_json = json_encode($rows);
		$rows_json = trim($rows_json);
		$rows_json = str_replace("\n", ",", $rows_json);
		$rows_json = str_replace("\r", ",", $rows_json);
		$rows_json = str_replace("},{", ",", $rows_json);
		$rows_json = str_replace("[", "", $rows_json);
		$rows_json = str_replace("]", "", $rows_json);
		

		// Print 
		echo"$rows_json";
	}



}
else{
	echo"Missing variables";
}



?>