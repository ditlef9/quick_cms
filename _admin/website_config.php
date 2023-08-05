<?php
error_reporting(E_ALL & ~E_STRICT);
@session_start();
ini_set('arg_separator.output', '&amp;');

/**
*
* File: _admin/website_config.php
* Version 1.0
* Date 2023
* Copyright (c) 2008-2023 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Datettime ----------------------------------------------------------------- */
$datetime = date("Y-m-d H:i:s");
$datetime_day = date("d");
$datetime_saying = date("d.m.Y H:i");
$datetime_ymd = date("Y-m-d");
$datetime_date_saying = date("d.m.Y");
$datetime_year = date("Y");
$datetime_month = date("m");
$datetime_month_full = date("F");
$datetime_month_short = date("M");
$datetime_day = date("d");
$datetime_day_full = date('l');
$datetime_day_short = date('D');
$datetime_week = date("W");
$time_saying = date("H:i");
$timestamp = time();


/*- Important functions ---------------------------------------------------------------- */
include("$root/_admin/_functions/output_html.php");
include("$root/_admin/_functions/clean.php");




/*- Other Functions ------------------------------------------------------------------- */
include("$root/_admin/_functions/clean_dir_reverse.php");
include("$root/_admin/_functions/resize_crop_image.php");
include("$root/_admin/_functions/quote_smart.php");
include("$root/_admin/_functions/page_url.php");
include("$root/_admin/_functions/get_extension.php");


/*- Configs ------------------------------------------------------------------------ */
if(file_exists("$root/_admin/_data/config/meta.php")){
	include("$root/_admin/_data/config/meta.php");
	include("$root/_admin/_data/config/user_system.php");
	include("$root/_admin/_data/webdesign.php");


	// Page URL
	$page_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$page_url = htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8');

	$page_url_substr = substr($page_url, 0, strlen($configSiteURLSav));

	if($configSiteURLSav != "$page_url_substr"){
		// Check for localhost
		$check_localhost = substr($page_url, 0, 16);
		if($check_localhost != "http://localhost"){
			header("Location: $configSiteURLSav");
			exit;
			/*
			echo"<p style=\"color:#fff;background:#000;font-size:100px;\"><a href=\"$configSiteURLSav\" style=\"color:#fff;background:#000;font-size:100px;\">$configSiteURLSav</a></p><meta http-equiv=refresh content=\"5; URL=$configSiteURLSav\">";
			echo"<p>Security error. Page url is not the same as configured. Please fix meta.php.
			</p>

			<p>
			<a href=\"$configSiteURLSav\">$configSiteURLSav</a> != $page_url_substr
			</p>
			";
			*/
		}
	}
}


/*- Common variables ----------------------------------------------------------------- */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);



/*- Check if setup is run ------------------------------------------------------------ */
$check = substr($server_name, 0, 3);
if($check == "www"){
	$server_name = substr($server_name, 3);
}
$setup_finished_file = "setup_finished_" . $server_name . ".php";
if(!(file_exists("$root/_admin/_data/$setup_finished_file"))){
	echo"<p style=\"color:#fff;background:#000;font-size:100px;\">Setup required.</p><meta http-equiv=refresh content=\"1; URL=$root/_admin/index.php\">";
	die;
}



/*- Variables ------------------------------------------------------------------------ */
if (isset($_GET['page'])) {
	$page = $_GET['page'];
	$page = stripslashes(strip_tags($page));
}
else{
	$page = "";
}
if(isset($_GET['action'])) {
	$action = $_GET['action'];
	$action = strip_tags(stripslashes($action));
}
else{
	$action = "";
}
if(isset($_GET['mode'])) {
	$mode = $_GET['mode'];
	$mode = strip_tags(stripslashes($mode));
}
else{
	$mode = "";
}
if(isset($_GET['process'])) {
	$process = $_GET['process'];
	$process = strip_tags(stripslashes($process));
}
else{
	$process = "";
}

if(isset($_GET['print'])) {
	$print = $_GET['print'];
	$print = strip_tags(stripslashes($print));
}
else{
	$print = "";
}
if(isset($_GET['ft'])) {
	$ft = $_GET['ft'];
	$ft = strip_tags(stripslashes($ft));
	if($ft != "error" && $ft != "warning" && $ft != "success" && $ft != "info"){
		echo"Server error 403 feedback error";die;
	}
}
else{
	$ft = "";
}
if(isset($_GET['fm'])) {
	$fm = $_GET['fm'];
	$fm = strip_tags(stripslashes($fm));
}


/*- MySQL ---------------------------------------------------------------------------- */
$mysql_config_file = "$root/_admin/_data/mysql_" . $server_name . ".php";
if(file_exists($mysql_config_file)){
	include("$mysql_config_file");
	$mysqli = new mysqli($mysqlHostSav, $mysqlUserNameSav, $mysqlPasswordSav, $mysqlDatabaseNameSav);

	if ($mysqli -> connect_errno) {
		$error = $mysqli -> connect_error;
		echo "
		<div class=\"error\"><p><b>MySQL connection error</b>: $error</p>
		</div>
		";
	}

	/*- MySQL Tables -------------------------------------------------- */
	$t_users 	 		= $mysqlPrefixSav . "users";
	$t_users_profile 		= $mysqlPrefixSav . "users_profile";
	$t_users_friends 		= $mysqlPrefixSav . "users_friends";
	$t_users_friends_requests 	= $mysqlPrefixSav . "users_friends_requests";
	$t_users_profile		= $mysqlPrefixSav . "users_profile";
	$t_users_profile_photo 		= $mysqlPrefixSav . "users_profile_photo";
	$t_users_status 		= $mysqlPrefixSav . "users_status";
	$t_users_status_subscriptions	= $mysqlPrefixSav . "users_status_subscriptions";
	$t_users_status_replies 	= $mysqlPrefixSav . "users_status_replies";
	$t_users_status_replies_likes 	= $mysqlPrefixSav . "users_status_replies_likes";
	$t_users_status_likes 		= $mysqlPrefixSav . "users_status_likes";
	$t_users_professional		= $mysqlPrefixSav . "users_professional";
	$t_users_cover_photos 		= $mysqlPrefixSav . "users_cover_photos";
	$t_users_email_subscriptions 	= $mysqlPrefixSav . "users_email_subscriptions";
	$t_users_notifications 		= $mysqlPrefixSav . "users_notifications";
	$t_users_moderator_of_the_week	= $mysqlPrefixSav . "users_moderator_of_the_week";

	$t_users_antispam_questions	= $mysqlPrefixSav . "users_antispam_questions";
	$t_users_antispam_answers	= $mysqlPrefixSav . "users_antispam_answers";

	$t_users_feeds_index		= $mysqlPrefixSav . "users_feeds_index";
	$t_users_api_sessions		= $mysqlPrefixSav . "users_api_sessions";

	$t_banned_hostnames	= $mysqlPrefixSav . "banned_hostnames";
	$t_banned_ips	 	= $mysqlPrefixSav . "banned_ips";
	$t_banned_user_agents	= $mysqlPrefixSav . "banned_user_agents";


	$t_pages 		= $mysqlPrefixSav . "pages";
	$t_pages_navigation 	= $mysqlPrefixSav . "pages_navigation";

	$t_comments		= $mysqlPrefixSav . "comments";
	$t_comments_users_block	= $mysqlPrefixSav . "comments_users_block";

	$t_images	= $mysqlPrefixSav . "images";
	$t_images_paths	= $mysqlPrefixSav . "images_paths";

	$t_languages		 = $mysqlPrefixSav . "languages";
	$t_languages_active 	= $mysqlPrefixSav . "languages_active";


}
/*- Language ------------------------------------------------------------------------- */


if(isset($_GET['l'])) {
	$l = $_GET['l'];
	// Look for hacker in string (this will ban user if hacker string is used more than 5 times)
	$variable = "$l";
	$is_numeric = false;
	include("_functions/look_for_hacker_in_string.php");
	
	$length = strlen($l);
	if($length > 5){
		echo"l lenght to long";
		die;
	}
	$l = strip_tags(stripslashes($l));

	// Is that language in list of languages?
	$stmt = $mysqli->prepare("SELECT language_active_id, language_active_name, language_active_iso_two FROM $t_languages_active WHERE language_active_iso_two=?"); 
	$stmt->bind_param("s", $l);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_current_language_active_id, $get_current_language_active_name, $get_current_language_active_iso_two) = $row;
	if($get_current_language_active_iso_two  != ""){
		
		$_SESSION['l'] = "$l";
	
	}
	else{
		echo"<div class=\"error\"><p>Unknown language</p></div>";
		die;
	}
}
else{
	// Find the pre defined language
	$query = "SELECT language_active_id, language_active_name, language_active_iso_two FROM $t_languages_active WHERE language_active_default='1'";
	$result = $mysqli->query($query);
	$row = $result->fetch_row();
	list($get_current_language_active_id, $get_current_language_active_name, $get_current_language_active_iso_two) = $row;
	if($get_current_language_active_iso_two == ""){
		echo"<div class=\"error\"><a href=\"$root/_admin\">Please select a default language</a></div>"; 
		die;
	}

	$l = $get_current_language_active_iso_two;
}

/*- Translation and CSS -------------------------------------------------------------------------- */
// 1. Common
if(!(file_exists("$root/_admin/_translations/site/$l/common/ts_common.php"))){
	echo"<p>Language not found (missing <a href=\"$root/_admin/_translations/site/$l/common/ts_common.php\">sites common</a> ts_common for $l). Language set to english.</p>";
	echo"<p><a href=\"index.php?l=en\">index.php?l=en</a></p>";
	if($l == ""){
		$l = "en";
		$l = $_SESSION['l'];
	}
	die;
}
include("$root/_admin/_translations/site/$l/common/ts_common.php");

// 2. Site defined by title
if(file_exists("$root/_admin/_translations/site/$l/$configWebsiteTitleCleanSav/ts_$configWebsiteTitleCleanSav.php")){
	include("$root/_admin/_translations/site/$l/$configWebsiteTitleCleanSav/ts_$configWebsiteTitleCleanSav.php");
	$pageCSSFile = "_css/$configWebsiteTitleCleanSav.css";
}

// 3. Special
$self 		= $_SERVER['PHP_SELF'];
$request_url 	= $_SERVER["REQUEST_URI"];
$self_array     = explode("/", $self);
$array_size     = sizeof($self_array);
$minus_one	= $array_size-1;
$minus_two	= $array_size-2;
$minus_three	= $array_size-3;
$url_minus_one	= $self_array[$minus_one];
$url_minus_two	= $self_array[$minus_two];
if(isset($self_array[$minus_three])){ $url_minus_three= $self_array[$minus_three]; }

$language_file = "$root/_admin/_translations/site/$l";
$language_case = "";

if($url_minus_one == "index.php"){
	// Either	index.php
	// or    	A: stores/index.php
	// or		C: stores/troms/index.php
	
	if($root == "."){
		// index.php
		$language_case = "A";
		$language_file = $language_file . "/welcome/ts_welcome.php";
		$pageCSSFile = "_css/$configWebsiteTitleCleanSav.css";
	}
	elseif($root == ".."){
		// stores/index.php
		$language_case = "A";
		$language_file = $language_file . "/$url_minus_two/ts_$url_minus_one";
		$pageCSSFile = "_css/$url_minus_one.css";

	}
	else{
		$language_case = "C";
		$language_file = $language_file . "/$url_minus_two/ts_$url_minus_two.php";
		$pageCSSFile = "_css/$url_minus_one.css";
	}
	// CONTINUE HERE
	
	
}
else{
	// Either B: stores/north.php
	// or     D: stores/troms/tromso.php
	if($root == ".."){
		// index.php
		$language_case = "B";
		$language_file = $language_file . "/$url_minus_two/ts_$url_minus_one";
		$pageCSSFile = "_css/$url_minus_one.css";
	}
	elseif($root == "../.."){
		// index.php
		$language_case = "D";
		$language_file = $language_file . "/$url_minus_two/ts_$url_minus_one";
		$pageCSSFile = "_css/$url_minus_one.css";
	}
	else{
		$pageCSSFile = "";
	}


}
	
// Include language
if(file_exists("$language_file") && !(is_dir($language_file))){
	include("$language_file");
	// echo"L: ($root) ($language_case) $language_file<br />";
}
else{
	// echo"Not found: ($root) ($language_case) $language_file<br />";
}

// Remove PHP from css
$pageCSSFile = str_replace(".php", "", $pageCSSFile);


/*- Website title ---------------------------------------------------------------------------- */
if(isset($website_title)){
	// Well keep that title
}
else{
	$website_title = "";

	// Page
	$page = $array_size-2;
	$page = $self_array[$page];
	$page = clean_dir_reverse($page);
	$page = str_replace(".php", "", $page);

	if($page == "" OR $page == "$configWebsiteTitleSav"){
		if(file_exists("$root/_admin/_data/slogan/$l.php")){
			include("$root/_admin/_data/slogan/$l.php");
			$website_title = "$SloganSav";
		}
	}
	else {
		$website_title = "$page";
	}
}






/*- Stats ---------------------------------------------------------------------------- */
include("$root/_admin/_functions/register_stats/registrer_stats.php");

/*- Cookie? -------------------------------------------------------------------------- */
if(isset($_SESSION['user_id'])) {
	// Last seend
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);

	$user_last_ip = $_SERVER['REMOTE_ADDR'];
	$user_last_ip = output_html($user_last_ip);
	$user_last_ip_mysql = quote_smart($link, $user_last_ip);

	$datetime = date("Y-m-d H:i:s");
	$time = time();

	$result = mysqli_query($link, "UPDATE $t_users SET user_last_online='$datetime', user_last_online_time='$time', user_last_ip=$user_last_ip_mysql WHERE user_id=$my_user_id_mysql");
				
}
else{

	if(isset($_COOKIE['remember_user'])) {
	        $cookie_encoded = $_COOKIE['remember_user'];
		// $salt = substr (md5($get_user_password), 0, 2);
		// $cookie = base64_encode ("$get_user_id:" . md5 ($get_user_password, $salt));		

		$cookie_decoded = base64_decode($cookie_encoded);

		$cookie_array = explode(":", $cookie_decoded);
		$cookie_user_id = output_html($cookie_array[0]);
		$cookie_user_id_mysql = quote_smart($link, $cookie_user_id);
		if(isset($cookie_array[1])){
			$cookie_password = $cookie_array[1];

			// Get that user
			$query = "SELECT user_id, user_password, user_salt, user_language, user_verified_by_moderator FROM $t_users WHERE user_id=$cookie_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_user_id, $get_user_password, $get_user_salt, $get_user_language, $get_user_verified_by_moderator) = $row;

			if($get_user_id == ""){
				// echo"<p style=\"color: red;\">Cookie error. User not found.</p>";
			}
			else{
				$salt = substr (md5($get_user_password), 0, 2);
				$user_cookie_password = md5 ($get_user_password, $salt);


				if($cookie_password == "$user_cookie_password"){
					
					// -> Logg brukeren inn
					$security = rand(0,9999);
					$_SESSION['user_id'] = "$get_user_id";
					$_SESSION['security'] = "$security";
					$user_last_ip = $_SERVER['REMOTE_ADDR'];
					$user_last_ip = output_html($user_last_ip);
					$user_last_ip_mysql = quote_smart($link, $user_last_ip);

					// Update last logged in + security pin code
					$inp_user_last_online = date("Y-m-d H:i:s");
					$time = time();
					$result = mysqli_query($link, "UPDATE $t_users SET user_security='$security', user_last_online='$inp_user_last_online', user_last_online_time='$time', user_last_ip=$user_last_ip_mysql WHERE user_id='$get_user_id'");
				
				}
				else{
					// echo"<p style=\"color: red;\">Cookie error: Uncorrect password.</p>";
				}
			}
		}
	}
}

?>