<?php
/**
*
* File: _design/header_dice.php
* Version 13:20 13.05.2021
* Copyright (c) 2009-2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if($process != "1"){
echo"<!DOCTYPE html>
<html lang=\"en-US\">
<head>
	<title>$website_title</title>
	<link rel=\"stylesheet\" type=\"text/css\" href=\"_design/dice.css?rand="; $datetime = date("Y-m-d H:i:s"); echo"$datetime\" />

	<!-- Favicon -->
		<link rel=\"icon\" href=\"_design/gfx/favicon/dice_16x16.png\" type=\"image/png\" sizes=\"16x16\" />
		<link rel=\"icon\" href=\"_design/gfx/favicon/dice_32x32.png\" type=\"image/png\" sizes=\"32x32\" />
		<link rel=\"icon\" href=\"_design/gfx/favicon/dice_260x260.png\" type=\"image/png\" sizes=\"260x260\" />
	<!-- //Favicon -->

	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
	<meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0;\" />

	<script type=\"text/javascript\" src=\"_design/jquery-3.6.0.min.js\"></script>
</head>
<body>


<main>
	<div class=\"main_inner\">
";
}
?>