<?php
/**
*
* File: _layout/header.php
* Version 12:28 25.09.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/


/*- Root dir -------------------------------------------------------------------------- */
if(!(isset($root))){
	if(file_exists("_scripts/index.html")){
		$root = ".";
	}
	elseif(file_exists("../_scripts/index.html")){
		$root = "..";
	}
	elseif(file_exists("../../_scripts/index.html")){
		$root = "../..";
	}
	elseif(file_exists("../../../_scripts/index.html")){
		$root = "../../..";
	}
	elseif(file_exists("../../../../_scripts/index.html")){
		$root = "../../../..";
	}
	else{
		$root = "../../..";
	}
}


/*- Variables -------------------------------------------------------------------------------- */
if(isset($_GET['process'])){
	$process = $_GET['process'];
	if(!(is_numeric($process))){
		echo"Process not numeric";
		die;
	}
}
else{
	$process = "0";
}


$random = date("ymdhis");

if($process != "1"){
echo"<!DOCTYPE html>
<html lang=\"en-US\">
<head>
	<title>Crypto</title>

	<!-- Site CSS -->
		<link rel=\"stylesheet\" type=\"text/css\" href=\"$root/_layout/crypto_analyzer.css?rand=$random\" />
	<!-- //Site CSS -->

	<!-- Favicon -->
		<link rel=\"icon\" href=\"$root/_layout/favicon/16x16.png\" type=\"image/png\" sizes=\"16x16\" />
		<link rel=\"icon\" href=\"$root/_layout/favicon/32x32.png\" type=\"image/png\" sizes=\"32x32\" />
		<link rel=\"icon\" href=\"$root/_layout/favicon/260x260.png\" type=\"image/png\" sizes=\"260x260\" />
	<!-- //Favicon -->

	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
	<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"/>

	<!-- Drawer -->
		<script type=\"text/javascript\" src=\"$root/_scripts/drawer/drawer.js?rand=$random\"></script>
	<!-- //Drawer -->


</head>
<body>
<a id=\"top\"></a>

<!-- Left -->
	<header>
		<div class=\"header_left\">
			<a href=\"index.php\">Cran</a>
		</div>
		<div class=\"header_right\">
			Login
		</div>


	</header>
<!-- //Left -->


<!-- Left -->
	<aside>
		xxx
	</aside>
<!-- //Left -->



<!-- Main -->
	<main>

	
	";


} // process != 1

?>