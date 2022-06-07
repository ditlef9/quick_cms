<?php

echo"<!DOCTYPE html>
<html lang=\"en-US\">
<head>
	<title>Ask IT";
	if(isset($website_title)){
		echo" - $website_title";
	}
	else{
		$title = $_SERVER['PHP_SELF'];
		$title = str_replace("/", " - ", $title);
		$title = str_replace(".php", "", $title);
		$title = str_replace("_", " ", $title);
		$title = ucwords($title);
		echo" $title";
	}
	echo" (Printer friendly version)</title>
	<link rel=\"stylesheet\" href=\"$root/_webdesign/reset.css\" type=\"text/css\" />
	<link rel=\"icon\" href=\"$root/favicon.ico\" />
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
	<meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0;\"/>




</head>
<body>
<a id=\"top\"></a>

<!-- Header -->
	<a href=\"$root/\"><img src=\"$root/_webdesign/images/logo.png\" alt=\"Ask IT\" style=\"float: right;padding:20px;\" /></a>
	
<!-- //Header -->
<!-- Main -->
	<div style=\"padding: 20px;\">
";
?>