<?php
error_reporting(E_ALL & ~E_STRICT);
session_start();
ini_set('arg_separator.output', '&amp;');
/**
*
* File: _admin/setup/index.php
* Version 1.2
* Date 17:58 17.07.2019
* Copyright (c) 2008-2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Make sure we are on the correct web site ----------------------------------------- */
if(file_exists("../_data/config/meta.php")){
	include("../_data/config/meta.php");

	// Page URL
	$page_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$page_url = htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8');


	$page_url_substr = substr($page_url, 0, strlen($configControlPanelURLSav));

	if($configControlPanelURLSav != "$page_url_substr"){
		// Check for localhost
		$check_localhost = substr($page_url, 0, 16);
		if($check_localhost != "http://localhost"){
	
			echo"<p>Security error. Page url is not the same as configured. Please fix meta.php.
			</p>

			<p>
			<a href=\"$configControlPanelURLSav\">$configControlPanelURLSav</a> != $page_url_substr
			</p>
			";
			die;
		}
	}
}
/*- Functions ------------------------------------------------------------------------ */
include("../_functions/output_html.php");
include("../_functions/clean.php");
include("../_functions/quote_smart.php");
include("../global_variables.php");


/*- Check if setup is run ------------------------------------------------------------ */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);
$check = substr($server_name, 0, 3);
if($check == "www"){
	$server_name = substr($server_name, 3);
}
$setup_finished_file = "setup_finished_" . $server_name . ".php";
if(file_exists("../_data/$setup_finished_file")){
	echo"<p style=\"color:#fff;background:#000;font-size:100px;\">Setup is finished.</p><META HTTP-EQUIV=Refresh CONTENT=\"1; URL=../../index.php\">";
	die;
}

// Config file ------------------------------------------------------------------------ */
// (temporary)
if(file_exists("../../_cache/setup_data.php")){
	include("../../_cache/setup_data.php");
}

/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['page'])) {
	$page = $_GET['page'];
	$page = strip_tags(stripslashes($page));
}
else{
	// Go to start
	$url = "index.php?page=00_start&language=en&process=1";
	header("Location: $url");
	exit;
}
if(isset($_GET['process'])) {
	$process = $_GET['process'];
	$process = strip_tags(stripslashes($process));
}
else{
	$process = "";
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
if(isset($_GET['action'])) {
	$action = $_GET['action'];
	$action = strip_tags(stripslashes($action));
}
else{
	$action = "";
}
if(isset($_GET['language'])) {
	$language = $_GET['language'];
	$language = strip_tags(stripslashes($language));
}
else{
	$language = "en";
}



/*- Language and translation ---------------------------------------------------------- */
if($language == ""){
	if($page == ""){
		include("../_translations/admin/en/setup/t_01_select_language.php");

	}
	else{
		if(!(file_exists("../_translations/admin/$language/setup/t_$page.php"))){
			$fh = fopen("../_translations/admin/$language/setup/t_$page.php", "w+") or die("can not open file");
			fwrite($fh, "<?php ?>");
			fclose($fh);

		}
		include("../_translations/admin/en/setup/t_$page.php");
	}
}
else{
	if($page == ""){
		include("../_translations/admin/$language/setup/t_01_select_language.php");

	}
	else{
		if(!(file_exists("../_translations/admin/$language/setup/t_$page.php"))){
		
			if(!(is_dir("../_translations/admin/$language/setup"))){
				mkdir("../_translations/admin/$language/setup");
			}

			$fh = fopen("../_translations/admin/$language/setup/t_$page.php", "w+") or die("can not open file");
			fwrite($fh, "<?php ?>");
			fclose($fh);
		}
		include("../_translations/admin/$language/setup/t_$page.php");
	}
}



/*- Design ---------------------------------------------------------------------------- */
if($process != "1"){
echo"<!DOCTYPE html>
<html lang=\"en\">
<head>
	<title>$cmsNameSav $cmsVersionSav";
	if($page != ""){
		$page_saying = ucfirst($page);
		echo" - $page_saying";
	}
	echo"</title>

	<link rel=\"icon\" href=\"favicon.ico\" />
	<meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0;\"/>

	<link rel=\"stylesheet\" href=\"_setup_design/reset.css\" type=\"text/css\" />
	<link rel=\"stylesheet\" href=\"_setup_design/setup.css?date="; echo date("ymdhis"); echo"\" type=\"text/css\" />
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UFT-8\" />


	<!-- jQuery -->
	<script type=\"text/javascript\" src=\"../_javascripts/jquery/jquery.min.js\"></script>
	<!-- //jQuery -->

</head>
<body>

<!-- Wrapper -->
<div id=\"wrapper\">
	
	<!-- Wrapper -->
	<div id=\"wrapper\">
	

		<!-- Header -->
			<header>
				<p>
				<a href=\"$cmsWebsiteSav\">$cmsNameSav</a>
				<span>$cmsVersionSav</span> 
				</p>
			</header>
		<!-- //Header -->


		<!-- Main -->
			<div id=\"main\">

			<!-- Navigation -->
				<div id=\"navigation\">
					<ul>
						<li><span"; if($page == "" OR $page == "01_select_language"){ echo" class=\"active\" "; } echo">1. Language</span></li>
						<li><span"; if($page == "02_licence"){ echo" class=\"active\" "; } echo">2. Licence</span></li>
						<li><span"; if($page == "03_chmod"){ echo" class=\"active\" "; } echo">3. Chmod</span></li>
						<li><span"; if($page == "04_database"){ echo" class=\"active\" "; } echo">4. Database</span></li>
						<li><span"; if($page == "05_site"){ echo" class=\"active\" "; } echo">5. Site</span></li>
						<li><span"; if($page == "06_administrator"){ echo" class=\"active\" "; } echo">6. Administrator</span></li>
						<li><span"; if($page == "07_web_design"){ echo" class=\"active\" "; } echo">7. Web design</span></li>
					</ul>
				</div>
			<!-- //Navigation -->

			<!-- Content -->
				<div id=\"content\">
					<!-- Page -->
					";
} // process
					if($page != ""){

						if (preg_match('/(http:\/\/|^\/|\.+?\/)/', $page)){
							echo"Server error 403";
						}
						else{
							if(file_exists("_setup_pages/$page.php")){
								include("_setup_pages/$page.php");
							}
							else{
								echo"Server error 404";
							}
						}
					}
					else{
						include("_setup_pages/01_select_language.php");
					}
if($process != "1"){
					echo"
					<!-- //Page -->
				</div>
			<!-- //Content -->
		</div>
		<!-- //Main -->

</div> <!--// Wrapper -->
</body>
</html>";

} // process
?>