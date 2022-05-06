<?php
/**
*
* File: _admin/_functions/encode_national_letters.php
* Version 2 - Updated 19:31 06.03.2015
* Copyright (c) 2008-2015 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*
* encode_national_letters.php are used when output_html method cannot be used, because
* this method also removes html entities. Example SQL with links, bold text, api etc
*
*
*/
function encode_national_letters($value){

	// �
	$value = str_replace('�', '&aelig;', $value);
	$value = str_replace("æ", "&aelig;", $value);
	
	// �
	$value = str_replace('�', '&oslash;', $value);
	$value = str_replace('ø', '&oslash;', $value);

	// �
	$value = str_replace("�", "&aring;", $value);
	$value = str_replace("å", "&aring;", $value);

	// �
	$value = str_replace('�', '&AElig;', $value);
	$value = str_replace('Æ', '&AElig;', $value);

	// �
	$value = str_replace('�', '&Oslash;', $value);
	$value = str_replace('Ø', '&Oslash;', $value);

	// �
	$value = str_replace('�', '&Aring;', $value);
	$value = str_replace('Å', '&Aring;', $value);

	// Degree
	$value = str_replace('°', '&deg;', $value);


	// Other
	$value = str_replace('�', '&#192;', $value);
	$value = str_replace('�', '&#193;', $value);
	// $value = str_replace('�', '&#194;', $value);
	// $value = str_replace('�', '&#195;', $value);
	$value = str_replace('�', '&#196;', $value);
	$value = str_replace('�', '&#199;', $value);
	$value = str_replace('�', '&#200;', $value);
	$value = str_replace('�', '&#201;', $value);
	$value = str_replace('�', '&#202;', $value);
	$value = str_replace('�', '&#203;', $value);
	$value = str_replace('�', '&#204;', $value);
	$value = str_replace('�', '&#205;', $value);
	$value = str_replace('�', '&#206;', $value);
	$value = str_replace('�', '&#207;', $value);
	$value = str_replace('�', '&#208;', $value);
	$value = str_replace('�', '&#209;', $value);
	$value = str_replace('�', '&#210;', $value);
	$value = str_replace('�', '&#211;', $value);
	$value = str_replace('�', '&#212;', $value);
	$value = str_replace('�', '&#213;', $value);
	$value = str_replace('�', '&#214;', $value);
	$value = str_replace('�', '&#215;', $value);  // Yeah, I know.  But otherwise the gap is confusing.  --Kris
	$value = str_replace('�', '&#217;', $value);
	$value = str_replace('�', '&#218;', $value);
	$value = str_replace('�', '&#219;', $value);
	$value = str_replace('�', '&#220;', $value);
	$value = str_replace('�', '&#221;', $value);
	$value = str_replace('�', '&#222;', $value);
	$value = str_replace('�', '&#223;', $value);
	$value = str_replace('�', '&#224;', $value);
	$value = str_replace('�', '&#225;', $value);
	$value = str_replace('�', '&#226;', $value);
	$value = str_replace('�', '&#227;', $value);
	$value = str_replace('�', '&#228;', $value);
	$value = str_replace('�', '&#231;', $value);
	$value = str_replace('�', '&#232;', $value);
	$value = str_replace('�', '&#233;', $value);
	$value = str_replace('�', '&#234;', $value);
	$value = str_replace('�', '&#235;', $value);
	$value = str_replace('�', '&#236;', $value);
	$value = str_replace('�', '&#237;', $value);
	$value = str_replace('�', '&#238;', $value);
	$value = str_replace('�', '&#239;', $value);
	$value = str_replace('�', '&#240;', $value);
	$value = str_replace('�', '&#241;', $value);
	$value = str_replace('�', '&#242;', $value);
	$value = str_replace('�', '&#243;', $value);
	$value = str_replace('�', '&#244;', $value);
	$value = str_replace('�', '&#245;', $value);
	$value = str_replace('�', '&#246;', $value);
	$value = str_replace('�', '&#247;', $value);  // Yeah, I know.  But otherwise the gap is confusing.  --Kris
	$value = str_replace('�', '&#249;', $value);
	$value = str_replace('�', '&#250;', $value);
	$value = str_replace('�', '&#251;', $value);
	$value = str_replace('�', '&#252;', $value);
	$value = str_replace('�', '&#253;', $value);
	$value = str_replace('�', '&#254;', $value);
	$value = str_replace('�', '&#255;', $value);

	// Other
	$value = str_replace('\x92', '&#x92;', $value); // �
	$value = str_replace('\xC3', '&#xc3;', $value); // �
	$value = str_replace('\xA2', '&#xa2;', $value); // �
	$value = str_replace('\x80', '&#x80;', $value); // �
	$value = str_replace('\x9A', '&#x9a;', $value); // �
	$value = str_replace('\xE2', '&#xe2;', $value); // �
	$value = str_replace('\xE2', '&#xe2;', $value); // �
	$value = str_replace('\x82', '&#x82;', $value); // �
	$value = str_replace('\xAC', '&#xac;', $value); // �
	$value = str_replace('\xAF', '&macr;', $value); // �

	// Punctuation
	$value = str_replace('�', '&laquo;', $value);
	$value = str_replace('�', '&raquo;', $value);
	$value = str_replace('�', '&lsaquo;', $value);
	$value = str_replace('�', '&rsaquo;', $value);
	$value = str_replace('�', '&ldquo;', $value);
	$value = str_replace('�', '&rdquo;', $value);
	$value = str_replace('�', '&lsquo;', $value);
	$value = str_replace('�', '&rsquo;', $value);
	$value = str_replace('�', '&mdash;', $value);
	$value = str_replace('�', '&ndash;', $value);

	// Money
	$value = str_replace('�', '&euro;', $value);


	// Return
        return $value;
}
?>