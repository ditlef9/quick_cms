<?php
error_reporting(E_ALL);
session_start();
ini_set('arg_separator.output', '&amp;');
/**
*
* File: _admin/liquidbase/liquidbase.php
* Version 1
* Date 14.16 03.03.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Admin? --------------------------------------------------------------------------- */
if(!(isset($_SESSION['admin_user_id']))){
	echo"Not logged in";	
	die;
}

/*- Functions ------------------------------------------------------------------------ */
include("../_functions/output_html.php");
include("../_functions/clean.php");
include("../_functions/quote_smart.php");
include("../_functions/resize_crop_image.php");

/*- Config ------------------------------------------------------------------------ */
include("../global_variables.php");
include("../_data/config/meta.php");

/*- Common variables ----------------------------------------------------------------- */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);


/*- MySQL ---------------------------------------------------------------------------- */
$mysql_config_file = "../_data/mysql_" . $server_name . ".php";
if(file_exists($mysql_config_file)){
	include("$mysql_config_file");
	$link = mysqli_connect($mysqlHostSav, $mysqlUserNameSav, $mysqlPasswordSav, $mysqlDatabaseNameSav);
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	/*- MySQL Tables -------------------------------------------------- */
	$t_admin_liquidbase		  = $mysqlPrefixSav . "admin_liquidbase";
}
else{
	echo"No mysql"; die;
}


/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['refererer_open'])) {
	$refererer_open = $_GET['refererer_open'];
	$refererer_open = strip_tags(stripslashes($refererer_open));
}
else{
	$refererer_open = "";
}
if(isset($_GET['refererer_page'])) {
	$refererer_page = $_GET['refererer_page'];
	$refererer_page = strip_tags(stripslashes($refererer_page));
}
else{
	$refererer_page = "";
}
if(isset($_GET['counter'])) {
	$counter = $_GET['counter'];
	$counter = strip_tags(stripslashes($counter));
}
else{
	$counter = "1";
}

/*- Select language ------------------------------------------------------------------ */
if(isset($_GET['l'])) {
	$l = $_GET['l'];
	if($l == ""){
		$l = "en";
	}

	if(file_exists("../_translations/admin/$l/login/t_login.php") && $l != ""){
		$_SESSION['l'] = $l;
	}
	else{
		echo"
		<div class=\"warning\"><p>Missing <a href=\"_translations/admin/$l/login/t_login.php\">_translations/admin/$l/login/login.php</a></div>
		";
		$_SESSION['l'] = "en";
	}
}
if(isset($_SESSION['l'])){
	$l = $_SESSION['l'];
}
else{
	if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
		$accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		$accept_language = output_html($accept_language);
		$accept_language = strtolower($accept_language);
		$accept_language_prefered = substr("$accept_language", 0,2);

		if(file_exists("../_translations/admin/$accept_language_prefered/cp/cp.php")){
			$l = "$accept_language_prefered";
		}
		else{
			$l = "en";
		}
	}
	else{
		$l = "en";
	}
}


/*- Start --------------------------------------------------------------------------- */
// Loop trough years
$path = "db_scripts";
if(!(is_dir("$path"))){
	echo"$path doesnt exists";
	die;
}
if ($handle = opendir($path)) {
	$modules = array();   
	while (false !== ($module = readdir($handle))) {
		if ($module === '.') continue;
		if ($module === '..') continue;
		array_push($modules, $module);
	}
	sort($modules);
	foreach ($modules as $module){
	
		// Open that year folder
		$path_module = "./db_scripts/$module";
		if ($handle_year = opendir($path_module)) {
			$liquidbase_names = array();   
			while (false !== ($liquidbase_name = readdir($handle_year))) {
				if ($liquidbase_name === '.') continue;
				if ($liquidbase_name === '..') continue;
				array_push($liquidbase_names, $liquidbase_name);
			}
	
			sort($liquidbase_names);
			foreach ($liquidbase_names as $liquidbase_name){


				
				if(!(is_dir("./db_scripts/$module/$liquidbase_name"))){

					// Has it been executed?
					$inp_liquidbase_module_mysql = quote_smart($link, $module);
					$inp_liquidbase_name_mysql = quote_smart($link, $liquidbase_name);
					
					$query = "SELECT liquidbase_id FROM $t_admin_liquidbase WHERE liquidbase_module=$inp_liquidbase_module_mysql AND liquidbase_name=$inp_liquidbase_name_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_liquidbase_id) = $row;
					if($get_liquidbase_id == ""){
						// Date
						$datetime = date("Y-m-d H:i:s");
						$run_saying = date("j M Y H:i");


						// Design
						echo"<!DOCTYPE html>
<html lang=\"en\">
<head>
	<title>$cmsNameSav Liquidbase</title>
	<meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0;\"/>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UFT-8\" />
	<link rel=\"stylesheet\" href=\"liquidbase.css\" type=\"text/css\" />
</head>
<body>
<div id=\"wrapper\">
	<div id=\"content\">

	<!-- Header -->
	<header>
		<p class=\"header_right\">$run_saying</p>
		<p>Liquidbase Database Maintenance Tool</p>
	</header>
	<!-- //Header -->

		
	<!-- Main -->
	<div id=\"main\">
		<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" /> $module &middot; $liquidbase_name</h1>
		<p><a href=\"liquidbase.php?counter=$counter&amp;refererer_open=$refererer_open&amp;refererer_page=$refererer_page&amp;datetime=$datetime&amp;l=$l\">Liquidbase is loading</a></p>";


						// Insert
						mysqli_query($link, "INSERT INTO $t_admin_liquidbase
						(liquidbase_id, liquidbase_module, liquidbase_name, liquidbase_run_datetime, liquidbase_run_saying) 
						VALUES 
						(NULL, $inp_liquidbase_module_mysql, $inp_liquidbase_name_mysql, '$datetime', '$run_saying')")
						or die(mysqli_error($link));

						// Run code
						include("db_scripts/$module/$liquidbase_name");

						// Refresh and load again
						$refresh_after = rand(0,1);
						echo"
	<meta http-equiv=refresh content=\"$refresh_after; url=liquidbase.php?counter=$counter&amp;refererer_open=$refererer_open&amp;refererer_page=$refererer_page&amp;last_module=$module&amp;last_name=$liquidbase_name&amp;last_datetime=$datetime&amp;l=$l\">
	</div>
	<!-- //Main -->

	<!-- Footer -->
	<footer>
		<p>
		<a href=\"$cmsWebsiteSav\">&copy; 2019-2020 $cmsNameSav $cmsVersionSav</a>
		</p>
	</footer>
	<!-- //Footer -->

	</div> <!-- //Content -->
</div> <!-- //Wrapper -->";
						die;
					}
				} // module not dir
			} // while liquidbase name
			closedir($handle_year);
		} // handle liquidbase
	} // while years
	closedir($handle);
} // handle years


/*- Control panel - */
if($refererer_open == "" && $refererer_page == ""){
	header("Location: ../index.php");
	exit;
}
else{
	header("Location: ../index.php?open=$refererer_open&page=$refererer_page&ft=success&fm=Liquidbase_run&l=$l");
	exit;
}
?>