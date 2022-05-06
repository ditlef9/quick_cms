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
$t_users_profile_photo		= $mysqlPrefixSav . "users_profile_photo";
$t_users_status			= $mysqlPrefixSav . "users_status";

/*- Variables ------------------------------------------------------------------------- */
$fm = "";

if(isset($_POST['inp_my_user_id'])){
	$inp_my_user_id = $_POST['inp_my_user_id'];
	$inp_my_user_id = output_html($inp_my_user_id);
	$inp_my_user_id_mysql = quote_smart($link, $inp_my_user_id);

}
else{
	echo"Missing user id";
	die;
}

if(isset($_POST['inp_my_user_password'])){
	$inp_my_user_password = $_POST['inp_my_user_password'];
	$inp_my_user_password = output_html($inp_my_user_password);
	$inp_my_user_password_mysql = quote_smart($link, $inp_my_user_password);
}
else{
	echo"Missing password";
	die;
}

// Check if my user id and password is ok
$query = "SELECT user_id, user_email, user_alias, user_language, user_date_format FROM $t_users WHERE user_id=$inp_my_user_id_mysql AND user_password=$inp_my_user_password_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_my_user_id, $get_my_user_email, $get_my_user_alias, $get_my_user_language, $get_my_user_date_format) = $row;
if($get_my_user_id == ""){
	echo"User not found. Wrong user id or password?";
	die;
}

// My image
$q = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id=$get_my_user_id AND photo_profile_image='1'";
$r = mysqli_query($link, $q);
$rowb = mysqli_fetch_row($r);
list($get_my_photo_id, $get_my_photo_destination) = $rowb;	

$inp_new_x = 40; // 950
$inp_new_y = 40; // 640
if(file_exists("../../_uploads/users/images/$get_my_user_id/$get_my_photo_destination") && $get_my_photo_destination != ""){
	$thumb_full_path = "../../_cache/user_" . $get_my_photo_destination . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
	if(!(file_exists("$thumb_full_path"))){
		resize_crop_image($inp_new_x, $inp_new_y, "../../_uploads/users/images/$get_my_user_id/$get_my_photo_destination", "$thumb_full_path");
	}
	$thumb_full_path = "_cache/user_" . $get_my_photo_destination . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
}
else{
	$thumb_full_path = "users/_gfx/avatar_blank_40.png";
}


// IP block
$my_ip = $_SERVER['REMOTE_ADDR'];
$my_ip = output_html($my_ip);
$my_ip_mysql = quote_smart($link, $my_ip);
			
$time = time();

$ip_block = "false";

$q = "SELECT status_id, status_time FROM $t_users_status WHERE status_created_by_ip=$my_ip_mysql ORDER BY status_id DESC LIMIT 0,1";
$r = mysqli_query($link, $q);
$rowb = mysqli_fetch_row($r);
list($get_status_id, $get_status_time) = $rowb;	

if($get_status_id != ""){
	$time_since_last_status = $time-$get_status_time;
	$remaining = 60-$time_since_last_status;
	if($time_since_last_status < 60){
		$ip_block = "true";
	}
}
if($ip_block == "true"){
	echo"IP Block. Please wait $remaining minutes before trying again.";
	die;
}



// Text
if(isset($_POST['inp_text'])){
	$inp_text = $_POST['inp_text'];
	$inp_text = output_html($inp_text);
	$inp_text_mysql = quote_smart($link, $inp_text);
}
else{
	echo"Missing text";
	die;
}

// Other variables
$inp_my_user_alias_mysql = quote_smart($link, $get_my_user_alias);
$inp_my_user_image_mysql = quote_smart($link, $get_my_photo_destination);
$l = "$get_my_user_language";
$l_mysql = quote_smart($link, $get_my_user_language);

$datetime = date("Y-m-d H:i:s");
$datetime_print = date('j M y');

// Current user ID
if(isset($_POST['inp_current_user_id'])){
	$inp_current_user_id = $_POST['inp_current_user_id'];
	$inp_current_user_id = output_html($inp_current_user_id);
	$inp_current_user_id_mysql = quote_smart($link, $inp_current_user_id);

}
else{
	echo"Missing current_user_id";
	die;
}


// Insert status
mysqli_query($link, "INSERT INTO $t_users_status
(status_id, status_user_id, status_created_by_user_id, status_created_by_user_alias, status_created_by_user_image, status_created_by_ip, status_text, status_photo, 
status_datetime, status_datetime_print, status_time, status_language, status_likes, status_comments, status_reported, status_reported_checked, status_reported_reason, status_seen) 
VALUES 
(NULL, $inp_current_user_id_mysql, '$get_my_user_id', $inp_my_user_alias_mysql, $inp_my_user_image_mysql, $my_ip_mysql, $inp_text_mysql, '', 
'$datetime', '$datetime_print', '$time', $l_mysql, '0', '0', '0', '0', '', '0')")
or die(mysqli_error($link));



// Get status ID
$q = "SELECT status_id FROM $t_users_status WHERE status_user_id=$inp_current_user_id_mysql AND status_datetime='$datetime'";
$r = mysqli_query($link, $q);
$rowb = mysqli_fetch_row($r);
list($get_status_id) = $rowb;

echo"$get_status_id";
?>