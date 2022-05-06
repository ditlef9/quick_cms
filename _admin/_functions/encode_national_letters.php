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

	// æ
	$value = str_replace('æ', '&aelig;', $value);
	$value = str_replace("Ã¦", "&aelig;", $value);
	
	// ø
	$value = str_replace('ø', '&oslash;', $value);
	$value = str_replace('Ã¸', '&oslash;', $value);

	// å
	$value = str_replace("å", "&aring;", $value);
	$value = str_replace("Ã¥", "&aring;", $value);

	// Æ
	$value = str_replace('Æ', '&AElig;', $value);
	$value = str_replace('Ã†', '&AElig;', $value);

	// Ø
	$value = str_replace('Ø', '&Oslash;', $value);
	$value = str_replace('Ã˜', '&Oslash;', $value);

	// Å
	$value = str_replace('Å', '&Aring;', $value);
	$value = str_replace('Ã…', '&Aring;', $value);

	// Degree
	$value = str_replace('Â°', '&deg;', $value);


	// Other
	$value = str_replace('À', '&#192;', $value);
	$value = str_replace('Á', '&#193;', $value);
	// $value = str_replace('Â', '&#194;', $value);
	// $value = str_replace('Ã', '&#195;', $value);
	$value = str_replace('Ä', '&#196;', $value);
	$value = str_replace('Ç', '&#199;', $value);
	$value = str_replace('È', '&#200;', $value);
	$value = str_replace('É', '&#201;', $value);
	$value = str_replace('Ê', '&#202;', $value);
	$value = str_replace('Ë', '&#203;', $value);
	$value = str_replace('Ì', '&#204;', $value);
	$value = str_replace('Í', '&#205;', $value);
	$value = str_replace('Î', '&#206;', $value);
	$value = str_replace('Ï', '&#207;', $value);
	$value = str_replace('Ð', '&#208;', $value);
	$value = str_replace('Ñ', '&#209;', $value);
	$value = str_replace('Ò', '&#210;', $value);
	$value = str_replace('Ó', '&#211;', $value);
	$value = str_replace('Ô', '&#212;', $value);
	$value = str_replace('Õ', '&#213;', $value);
	$value = str_replace('Ö', '&#214;', $value);
	$value = str_replace('×', '&#215;', $value);  // Yeah, I know.  But otherwise the gap is confusing.  --Kris
	$value = str_replace('Ù', '&#217;', $value);
	$value = str_replace('Ú', '&#218;', $value);
	$value = str_replace('Û', '&#219;', $value);
	$value = str_replace('Ü', '&#220;', $value);
	$value = str_replace('Ý', '&#221;', $value);
	$value = str_replace('Þ', '&#222;', $value);
	$value = str_replace('ß', '&#223;', $value);
	$value = str_replace('à', '&#224;', $value);
	$value = str_replace('á', '&#225;', $value);
	$value = str_replace('â', '&#226;', $value);
	$value = str_replace('ã', '&#227;', $value);
	$value = str_replace('ä', '&#228;', $value);
	$value = str_replace('ç', '&#231;', $value);
	$value = str_replace('è', '&#232;', $value);
	$value = str_replace('é', '&#233;', $value);
	$value = str_replace('ê', '&#234;', $value);
	$value = str_replace('ë', '&#235;', $value);
	$value = str_replace('ì', '&#236;', $value);
	$value = str_replace('í', '&#237;', $value);
	$value = str_replace('î', '&#238;', $value);
	$value = str_replace('ï', '&#239;', $value);
	$value = str_replace('ð', '&#240;', $value);
	$value = str_replace('ñ', '&#241;', $value);
	$value = str_replace('ò', '&#242;', $value);
	$value = str_replace('ó', '&#243;', $value);
	$value = str_replace('ô', '&#244;', $value);
	$value = str_replace('õ', '&#245;', $value);
	$value = str_replace('ö', '&#246;', $value);
	$value = str_replace('÷', '&#247;', $value);  // Yeah, I know.  But otherwise the gap is confusing.  --Kris
	$value = str_replace('ù', '&#249;', $value);
	$value = str_replace('ú', '&#250;', $value);
	$value = str_replace('û', '&#251;', $value);
	$value = str_replace('ü', '&#252;', $value);
	$value = str_replace('ý', '&#253;', $value);
	$value = str_replace('þ', '&#254;', $value);
	$value = str_replace('ÿ', '&#255;', $value);

	// Other
	$value = str_replace('\x92', '&#x92;', $value); // ’
	$value = str_replace('\xC3', '&#xc3;', $value); // Ã
	$value = str_replace('\xA2', '&#xa2;', $value); // ¢
	$value = str_replace('\x80', '&#x80;', $value); // €
	$value = str_replace('\x9A', '&#x9a;', $value); // š
	$value = str_replace('\xE2', '&#xe2;', $value); // â
	$value = str_replace('\xE2', '&#xe2;', $value); // â
	$value = str_replace('\x82', '&#x82;', $value); // ‚
	$value = str_replace('\xAC', '&#xac;', $value); // ¬
	$value = str_replace('\xAF', '&macr;', $value); // ¯

	// Punctuation
	$value = str_replace('«', '&laquo;', $value);
	$value = str_replace('»', '&raquo;', $value);
	$value = str_replace('‹', '&lsaquo;', $value);
	$value = str_replace('›', '&rsaquo;', $value);
	$value = str_replace('“', '&ldquo;', $value);
	$value = str_replace('”', '&rdquo;', $value);
	$value = str_replace('‘', '&lsquo;', $value);
	$value = str_replace('’', '&rsquo;', $value);
	$value = str_replace('—', '&mdash;', $value);
	$value = str_replace('–', '&ndash;', $value);

	// Money
	$value = str_replace('€', '&euro;', $value);


	// Return
        return $value;
}
?>