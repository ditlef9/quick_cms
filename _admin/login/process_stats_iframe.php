<?php
session_start();
ini_set('arg_separator.output', '&amp;');
/**
*
* File: _admin/login/process_stats_iframe.php
* Version 2.0
* Date 21:53 29.10.2019
* Copyright (c) 2008-2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Functions ------------------------------------------------------------------------ */
include("../_functions/output_html.php");
include("../_functions/clean.php");
include("../_functions/quote_smart.php");
include("../global_variables.php");

/*- Website config --------------------------------------------------------------------------- */
if(file_exists("../_data/logo.php")){
	include("../_data/logo.php");
}
if(file_exists("../_data/config/meta.php")){
	include("../_data/config/meta.php");
}

/*- Check if setup is run ------------------------------------------------------------ */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);
$setup_finished_file = "setup_finished_" . $server_name . ".php";
if(!(file_exists("../_data/$setup_finished_file"))){
	header("Location: ../setup/");
	exit;
}

/*- MySQL ------------------------------------------------------------------------- */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);
$mysql_config_file = "../_data/mysql_" . $server_name . ".php";
if(file_exists("$mysql_config_file")){
	include("$mysql_config_file");
	$link = mysqli_connect($mysqlHostSav, $mysqlUserNameSav, $mysqlPasswordSav, $mysqlDatabaseNameSav);
	if (!$link) {
		echo "
		<div class=\"alert alert-danger\"><span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span><strong>MySQL connection error</strong>"; 
		echo PHP_EOL;
   		echo "<br />Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    		echo "<br />Debugging error: " . mysqli_connect_error() . PHP_EOL;
    		echo"
		</div>
		";
		die;
	}

	/*- MySQL Tables -------------------------------------------------- */
	$t_users 	 		= $mysqlPrefixSav . "users";
	$t_users_profile 		= $mysqlPrefixSav . "users_profile";
	$t_users_friends 		= $mysqlPrefixSav . "users_friends";
	$t_users_friends_requests 	= $mysqlPrefixSav . "users_friends_requests";
	$t_users_profile		= $mysqlPrefixSav . "users_profile";
	$t_users_profile_photo 		= $mysqlPrefixSav . "users_profile_photo";
	$t_users_status 		= $mysqlPrefixSav . "users_status";
	$t_users_status_comments 	= $mysqlPrefixSav . "users_status_comments";
	$t_users_status_comments_likes 	= $mysqlPrefixSav . "users_status_comments_likes";
	$t_users_status_likes 		= $mysqlPrefixSav . "users_status_likes";
	$t_users_profile 		= $mysqlPrefixSav . "users_profile";
	$t_users_cover_photos 		= $mysqlPrefixSav . "users_cover_photos";
	$t_users_email_subscriptions 	= $mysqlPrefixSav . "users_email_subscriptions";

	$t_users_known_devices 		= $mysqlPrefixSav . "users_known_devices";
	$t_users_logins 		= $mysqlPrefixSav . "users_logins";

	$t_stats_bot_visitor 	= $mysqlPrefixSav . "stats_bot_visitor";
	$t_stats_human_visitor 	= $mysqlPrefixSav . "stats_human_visitor";

	$t_pages 	= $mysqlPrefixSav . "pages";
	$t_navigation 	= $mysqlPrefixSav . "navigation";

	$t_banned_ips 			= $mysqlPrefixSav . "banned_ips";
	$t_banned_hostnames 		= $mysqlPrefixSav . "banned_hostnames";
	$t_banned_user_agents		= $mysqlPrefixSav . "banned_user_agents";
	$t_stats_user_agents_index 	= $mysqlPrefixSav . "stats_user_agents_index";
	$t_stats_ip_to_country_lookup_ipv4 = $mysqlPrefixSav . "stats_ip_to_country_lookup_ipv4";
	$t_stats_ip_to_country_lookup_ipv6 = $mysqlPrefixSav . "stats_ip_to_country_lookup_ipv6";

	$t_languages_countries		= $mysqlPrefixSav . "languages_countries";
	$t_users_moderator_of_the_week  = $mysqlPrefixSav . "users_moderator_of_the_week";
}
else{
	echo"No MySQL connection. Missing mysql_";echo"$server_name file";
	die;
}

/*- Script start ------------------------------------------------------------------------ */

echo"<!DOCTYPE html>
<html lang=\"en\">
<head>
	<title>Quick CMS Process Stats</title>
	<meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0;\" />
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UFT-8\" />
	<style>
	body {background-color: powderblue;}
	</style>
</head>
<body>
</body>
</html>";

$root = "../../";
include("../_inc/dashboard/_stats/unprocessed.php");

?>