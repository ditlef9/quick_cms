<?php
/**
*
* File: _admin/_functions/decode_national_letters.php
* Version 2 - Updated 19:31 06.03.2015
* Copyright (c) 2008-2015 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
* Will typically be used for e-mail subjects
*
*/
function decode_national_letters($value){
	$value = str_replace("&aring;", "å", $value);
	$value = str_replace('&aelig;', 'æ', $value);
	$value = str_replace("&Aring;", "Å", $value);
	$value = str_replace('&Aelig;', 'Æ', $value);
	$value = str_replace( '&#192;', 'À', $value);
	$value = str_replace( '&#193;', 'Á', $value);
	$value = str_replace( '&#194;', 'Â', $value);
	$value = str_replace( '&#195;', 'Ã', $value);
	$value = str_replace( '&#196;', 'Ä', $value);
	$value = str_replace( '&#197;', 'Å', $value);
	$value = str_replace( '&#198;', 'Æ', $value);
	$value = str_replace( '&#199;', 'Ç',  $value);
	$value = str_replace( '&#200;', 'È',  $value);
	$value = str_replace( '&#201;', 'É', $value);
	$value = str_replace( '&#202;', 'Ê', $value);
	$value = str_replace( '&#203;', 'Ë', $value);
	$value = str_replace( '&#204;', 'Ì', $value);
	$value = str_replace( '&#205;', 'Í', $value);
	$value = str_replace( '&#206;', 'Î', $value);
	$value = str_replace( '&#207;', 'Ï', $value);
	$value = str_replace( '&#208;', 'Ð', $value);
	$value = str_replace( '&#209;', 'Ñ', $value);
	$value = str_replace( '&#210;', 'Ò', $value);
	$value = str_replace( '&#211;', 'Ó', $value);
	$value = str_replace( '&#212;', 'Ô', $value);
	$value = str_replace( '&#213;', 'Õ', $value);
	$value = str_replace( '&#214;', 'Ö', $value);
	$value = str_replace( '&#215;', '×', $value);  
	$value = str_replace( '&#216;', 'Ø', $value);
	$value = str_replace('&Oslash;', 'Ø', $value);
	$value = str_replace('&oslash;', 'ø', $value);
	$value = str_replace( '&#217;', 'Ù', $value);
	$value = str_replace( '&#218;', 'Ú', $value);
	$value = str_replace( '&#219;', 'Û', $value);
	$value = str_replace( '&#220;', 'Ü', $value);
	$value = str_replace( '&#221;', 'Ý', $value);
	$value = str_replace( '&#222;', 'Þ', $value);
	$value = str_replace( '&#223;', 'ß', $value);
	$value = str_replace( '&#224;', 'à', $value);
	$value = str_replace( '&#225;', 'á', $value);
	$value = str_replace( '&#226;', 'â', $value);
	$value = str_replace( '&#227;', 'ã', $value);
	$value = str_replace( '&#228;', 'ä', $value);
	$value = str_replace( '&#229;', 'å', $value);
	$value = str_replace( '&#230;', 'æ', $value);
	$value = str_replace( '&#231;', 'ç', $value);
	$value = str_replace( '&#232;', 'è', $value);
	$value = str_replace( '&#233;', 'é', $value);
	$value = str_replace( '&#234;', 'ê', $value);
	$value = str_replace( '&#235;', 'ë', $value);
	$value = str_replace( '&#236;', 'ì', $value);
	$value = str_replace( '&#237;', 'í', $value);
	$value = str_replace( '&#238;', 'î', $value);
	$value = str_replace( '&#239;', 'ï', $value);
	$value = str_replace( '&#240;', 'ð', $value);
	$value = str_replace( '&#241;', 'ñ', $value);
	$value = str_replace( '&#242;', 'ò', $value);
	$value = str_replace( '&#243;', 'ó', $value);
	$value = str_replace( '&#244;', 'ô', $value);
	$value = str_replace( '&#245;', 'õ', $value);
	$value = str_replace( '&#246;', 'ö', $value);
	$value = str_replace( '&#247;', '÷', $value); 
	$value = str_replace( '&#248;', 'ø', $value);
	$value = str_replace( '&#249;', 'ù', $value);
	$value = str_replace( '&#250;', 'ú', $value);
	$value = str_replace( '&#251;', 'û', $value);
	$value = str_replace( '&#252;', 'ü', $value);
	$value = str_replace( '&#253;', 'ý', $value);
	$value = str_replace( '&#254;', 'þ', $value);
	$value = str_replace( '&#255;', 'ÿ', $value);
	

	// Return
		return $value;
}
?>