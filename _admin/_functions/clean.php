<?php
/**
*
* File: _admin/_functions/clean.php
* Version 1
* Date 20:02 11.01.2018
* Copyright (c) 2008-2018 Solo at Nettport
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
function clean($value){
	$value = strtolower($value);


	// Norwegian letters
	$value = str_replace("&aelig;", "ae", "$value");
	$value = str_replace("&oslash;", "o", "$value");
	$value = str_replace("&aring;", "a", "$value");

	// Special
	$value = htmlspecialchars("$value");
	$value = htmlentities($value);


	// Signs
	$value = str_replace(" ", "_", "$value");
	$value = str_replace("!", "", "$value");
	$value = str_replace("(", "", "$value");
	$value = str_replace(")", "", "$value");
	$value = str_replace("[", "", "$value");
	$value = str_replace("]", "", "$value");
	$value = str_replace(".", "", "$value");
	$value = str_replace("/", "_", "$value");
	$value = str_replace("#", "_", "$value");
	$value = str_replace(",", "_", "$value");
	$value = str_replace("+", "_", "$value");
	$value = str_replace(":", "_", "$value");
	$value = str_replace(";", "", "$value");
	$value = str_replace("?", "", "$value");
	$value = str_replace(";", "_", "$value");
	$value = str_replace("’", "", "$value");
	$value = str_replace("'", "", "$value");
	$value = str_replace("%", "", "$value");
	$value = str_replace('$', "", "$value");
	
	// Special
	$value = str_replace("&#39;", "", "$value"); // Apostrof

	// Amperstand
	$value = str_replace("&", "", "$value");
	$value = str_replace("ampamp", "", "$value");

        return $value;
}
?>
