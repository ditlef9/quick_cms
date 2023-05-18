<?php
/**
*
* File: food/open_sub_category_nutritional_facts_include_header.php
* Version 1.0.0.
* Date 09:51 10.04.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
if(!(isset($website_title)) OR !(isset($l))){
	echo"Error";
	die;
}
echo"<!DOCTYPE html>
<html lang=\"$l\">
<head>
	<title>$website_title | $configWebsiteTitleSav</title>

	<!-- Site CSS-->
		<link rel=\"stylesheet\" type=\"text/css\" href=\"_css/open_sub_category_nutritional_facts_eu.css?"; echo filesize("_css/open_sub_category_nutritional_facts_eu.css"); echo"\" />
	<!-- //Site CSS -->

	<!-- Favicon -->
		<link rel=\"icon\" href=\"$root/_uploads/favicon/16x16.png\" type=\"image/png\" sizes=\"16x16\" />
		<link rel=\"icon\" href=\"$root/_uploads/favicon/32x32.png\" type=\"image/png\" sizes=\"32x32\" />
		<link rel=\"icon\" href=\"$root/_uploads/favicon/260x260.png\" type=\"image/png\" sizes=\"260x260\" />
	<!-- //Favicon -->

	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
	<meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0;\"/>

	<!-- jQuery -->
		<script type=\"text/javascript\" src=\"$root/_admin/_javascripts/jquery/jquery.min.js\"></script>
	<!-- //jQuery -->

</head>
<body>

<main>
";
?>