<?php
/**
*
* File: legal/index.php
* Version 1.0.0
* Date 16:42 30.01.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Root dir -------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Language ------------------------------------------------------------------------- */
if(isset($_GET['l'])) {
	$l = $_GET['l'];
	$l = strip_tags(stripslashes($l));
}
else{
	$l = "en";
}
if(isset($_GET['doc'])) {
	$doc = $_GET['doc'];
	$doc = strip_tags(stripslashes($doc));
}
else{
	$url = "index.php?doc=cookies_policy&l=$l";
	header("Location: $url");
	exit;
}

/*- Lang ---------------------------------------------------------------------------------- */
if(file_exists("$root/_admin/_translations/site/$l/legal/ts_legal.php")){
	include("$root/_admin/_translations/site/$l/legal/ts_legal.php");
}
else{
	echo"Lang not found";
	die;
}

/*- Headers ---------------------------------------------------------------------------------- */
if($doc == "privacy_policy"){
	$website_title = "$l_privacy_policy";
}
elseif($doc == "terms_of_use"){
	$website_title = "$l_terms_of_use";
}
else{
	$website_title = "$l_cookies_policy";
}
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");


/*- Tables ---------------------------------------------------------------------------- */
$t_pages_cookies_policy 		= $mysqlPrefixSav . "pages_cookies_policy";
$t_pages_cookies_policy_accepted 	= $mysqlPrefixSav . "pages_cookies_policy_accepted";
$t_pages_privacy_policy 		= $mysqlPrefixSav . "pages_privacy_policy";
$t_pages_terms_of_use			= $mysqlPrefixSav . "pages_terms_of_use";


/*- Scriptstart ------------------------------------------------------------------------------ */
if($doc == "privacy_policy"){
	// Get policy
	$query = "SELECT privacy_policy_id, privacy_policy_title, privacy_policy_language, privacy_policy_text, privacy_policy_is_active, privacy_policy_created_date, privacy_policy_created_date_saying, privacy_policy_created_by_user_id, privacy_policy_created_by_user_name, privacy_policy_created_by_user_email, privacy_policy_created_by_name, privacy_policy_updated_date, privacy_policy_updated_date_saying, privacy_policy_updated_by_user_id, privacy_policy_updated_by_user_name, privacy_policy_updated_by_user_email, privacy_policy_updated_by_name FROM $t_pages_privacy_policy WHERE privacy_policy_language=$l_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_id, $get_current_title, $get_current_language, $get_current_text, $get_current_is_active, $get_current_created_date, $get_current_created_date_saying, $get_current_created_by_user_id, $get_current_created_by_user_name, $get_current_created_by_user_email, $get_current_created_by_name, $get_current_updated_date, $get_current_updated_date_saying, $get_current_updated_by_user_id, $get_current_updated_by_user_name, $get_current_updated_by_user_email, $get_current_updated_by_name) = $row;
}
elseif($doc == "terms_of_use"){
	// Get policy
	$query = "SELECT terms_of_use_id, terms_of_use_title, terms_of_use_text, terms_of_use_is_active, terms_of_use_created_date, terms_of_use_created_date_saying, terms_of_use_created_by_user_id, terms_of_use_created_by_user_name, terms_of_use_created_by_user_email, terms_of_use_created_by_name, terms_of_use_updated_date, terms_of_use_updated_date_saying, terms_of_use_updated_by_user_id, terms_of_use_updated_by_user_name, terms_of_use_updated_by_user_email, terms_of_use_updated_by_name FROM $t_pages_terms_of_use WHERE terms_of_use_language=$l_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_id, $get_current_title, $get_current_text, $get_current_is_active, $get_current_created_date, $get_current_created_date_saying, $get_current_created_by_user_id, $get_current_created_by_user_name, $get_current_created_by_user_email, $get_current_created_by_name, $get_current_updated_date, $get_current_updated_date_saying, $get_current_updated_by_user_id, $get_current_updated_by_user_name, $get_current_updated_by_user_email, $get_current_updated_by_name) = $row;
}
else{
	// Get policy
	$query = "SELECT cookies_policy_id, cookies_policy_title, cookies_policy_language, cookies_policy_text, cookies_policy_is_active, cookies_policy_created_date, cookies_policy_created_date_saying, cookies_policy_created_by_user_id, cookies_policy_created_by_user_name, cookies_policy_created_by_user_email, cookies_policy_created_by_name, cookies_policy_updated_date, cookies_policy_updated_date_saying, cookies_policy_updated_by_user_id, cookies_policy_updated_by_user_name, cookies_policy_updated_by_user_email, cookies_policy_updated_by_name FROM $t_pages_cookies_policy WHERE cookies_policy_language=$l_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_id, $get_current_title, $get_current_language, $get_current_text, $get_current_is_active, $get_current_created_date, $get_current_created_date_saying, $get_current_created_by_user_id, $get_current_created_by_user_name, $get_current_created_by_user_email, $get_current_created_by_name, $get_current_updated_date, $get_current_updated_date_saying, $get_current_updated_by_user_id, $get_current_updated_by_user_name, $get_current_updated_by_user_email, $get_current_updated_by_name) = $row;

	// Process?
	if($action == "accept" && $process == "1"){
		$datetime = date("Y-m-d H:i:s");
		$year = date("Y");

		$my_ip = $_SERVER['REMOTE_ADDR'];
		$my_ip = output_html($my_ip);
		$my_ip_mysql = quote_smart($link, $my_ip);


		// Check if exists, if not then insert
		$query = "SELECT cookies_policy_accepted_id FROM $t_pages_cookies_policy_accepted WHERE cookies_policy_accepted_ip=$my_ip_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_cookies_policy_accepted_id) = $row;
		if($get_cookies_policy_accepted_id == ""){
			// Insert
			mysqli_query($link, "INSERT INTO $t_pages_cookies_policy_accepted 
				(cookies_policy_accepted_id, cookies_policy_accepted_year, cookies_policy_accepted_datetime, cookies_policy_accepted_ip) 
				VALUES 
				(NULL, $year, '$datetime', $my_ip_mysql)")
				or die(mysqli_error($link));

			// Delete old
			mysqli_query($link, "DELETE FROM $t_pages_cookies_policy_accepted WHERE cookies_policy_accepted_year < $year")
				or die(mysqli_error($link));

			exit;
		}
	}
}

if($get_current_id == ""){
	echo"<p>Page not found</p>";
}
else{
	if($get_current_is_active == "0"){
		echo"<p>Policy not active</p>";
	}
	else{
		echo"$get_current_text";
	}
}


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>