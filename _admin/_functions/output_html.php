<?php
/**
*
* File: _admin/_functions/output_html.php
* Version 2 - Updated 19:31 06.03.2015
* Copyright (c) 2008-2015 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*
*
* -----> Please also see: encode_national_letters.php <-----
* encode_national_letters.php are used when this class cannot be used, because
* this class also removes html entities. Example SQL with links, bold text etc
*
*
*/
function output_html($value){
	// Trim and line space
	$value = trim($value);

	// Check last, if it is backslash, then replace it...
	$check  = substr($value, -1);
	$check  =  "^" . $check . "^";
	if($check == "^\^"){
		$new_value = substr($value, 0, -1);
		$value	 = $new_value . "&#92";
	}
	
	// 1. Characpters that needs to be converted

	// 1. A
	$value = str_replace('æ', '&aelig;', $value);
	$value = str_replace("Ã¦", "&aelig;", $value);
	$value = str_replace("&amp;aelig;", "&aelig;", $value);
	$value = str_replace('Ã¸', '&oslash;', $value);
	$value = str_replace('&amp;oslash;', '&oslash;', $value);
	$value = str_replace("å", "&aring;", $value);
	$value = str_replace("Ã¥", "&aring;", $value);
	$value = str_replace("&amp;aring;", "&aring;", $value);
	$value = str_replace('Æ', '&AElig;', $value);
	$value = str_replace('Ã†', '&AElig;', $value);
	$value = str_replace('&amp;AElig;', '&AElig;', $value);
	$value = str_replace('Å', '&Aring;', $value);
	$value = str_replace('Ã…', '&Aring;', $value);
	$value = str_replace('&amp;Aring;', '&Aring;', $value);

	$value = str_replace('á', '&aacute;', $value);
	$value = str_replace('à', '&agrave;', $value);
	$value = str_replace('À', '&Agrave;', $value);
	$value = str_replace('â', '&acirc;', $value);
	$value = str_replace('Â', '&Acirc;', $value);
	$value = str_replace('Á', '&Aacute;', $value);

	$value = str_replace('À', '&#192;', $value);
	$value = str_replace('Á', '&#193;', $value);
	$value = str_replace('Ä', '&#196;', $value);
	$value = str_replace('à', '&#224;', $value);
	$value = str_replace('á', '&#225;', $value);
	$value = str_replace('â', '&#226;', $value);
	$value = str_replace('ã', '&#227;', $value);
	$value = str_replace('ä', '&#228;', $value);

	// 1. C
	$value = str_replace('Ç', '&#199;', $value);
	$value = str_replace('ç', '&#231;', $value);


	// 1. E
	$value = str_replace('è', '&egrave;', $value);
	$value = str_replace('È', '&Egrave;', $value);
	$value = str_replace('é', '&eacute;', $value);
	$value = str_replace('É', '&Eacute;', $value);
	$value = str_replace('ê', '&ecirc;', $value);
	$value = str_replace('Ê', '&Ecirc;', $value);
	$value = str_replace('ë', '&euml;', $value);
	$value = str_replace('Ë', '&Euml;', $value);
	$value = str_replace('È', '&#200;', $value);
	$value = str_replace('É', '&#201;', $value);
	$value = str_replace('Ê', '&#202;', $value);
	$value = str_replace('Ë', '&#203;', $value);
	$value = str_replace('è', '&#232;', $value);
	$value = str_replace('é', '&#233;', $value);
	$value = str_replace('ê', '&#234;', $value);
	$value = str_replace('ë', '&#235;', $value);

	// 1. I
	$value = str_replace('î', '&icirc;', $value);
	$value = str_replace('Î', '&Icirc;', $value);
	$value = str_replace('ï', '&iuml;', $value);
	$value = str_replace('Ï', '&Iuml;', $value);
	$value = str_replace('Í', '&Iacute;', $value);
	$value = str_replace('í', '&iacute;', $value);
	$value = str_replace('¿', '&iquest;', $value);
	$value = str_replace('¡', '&iexcl;', $value);
	$value = str_replace('Ì', '&#204;', $value);
	$value = str_replace('Í', '&#205;', $value);
	$value = str_replace('Î', '&#206;', $value);
	$value = str_replace('Ï', '&#207;', $value);
	$value = str_replace('ì', '&#236;', $value);
	$value = str_replace('í', '&#237;', $value);
	$value = str_replace('î', '&#238;', $value);
	$value = str_replace('ï', '&#239;', $value);

	// 1. D
	$value = str_replace('Ð', '&#208;', $value);

	// 1. N
	$value = str_replace('Ñ', '&Ntilde;', $value);
	$value = str_replace('ñ', '&ntilde;', $value);
	$value = str_replace('Ñ', '&#209;', $value);

	// 1. O
	$value = str_replace('Ø', '&Oslash;', $value);
	$value = str_replace('Ã˜', '&Oslash;', $value);
	$value = str_replace('&amp;Oslash;', '&Oslash;', $value);
	$value = str_replace('ô', '&ocirc;', $value);
	$value = str_replace('Ô', '&Ocirc;', $value);
	$value = str_replace('Ó', '&Oacute;', $value);
	$value = str_replace('ó', '&oacute;', $value);
	$value = str_replace('º', '&ordm;', $value);
	$value = str_replace('ª', '&ordf;', $value);
	$value = str_replace('Ö', '&Ouml;', $value);
	$value = str_replace('ö', '&ouml;', $value);
	$value = str_replace('Ò', '&#210;', $value);
	$value = str_replace('Ó', '&#211;', $value);
	$value = str_replace('Ô', '&#212;', $value);
	$value = str_replace('Õ', '&#213;', $value);
	$value = str_replace('Ö', '&#214;', $value);
	$value = str_replace('ð', '&#240;', $value);
	$value = str_replace('ñ', '&#241;', $value);
	$value = str_replace('ò', '&#242;', $value);
	$value = str_replace('ó', '&#243;', $value);
	$value = str_replace('ô', '&#244;', $value);
	$value = str_replace('õ', '&#245;', $value);
	$value = str_replace('ö', '&#246;', $value);

	// 1. P
	$value = str_replace('Þ', '&#222;', $value);
	$value = str_replace('þ', '&#254;', $value);

	// 1. S
	$value = str_replace('ß', '&#223;', $value);

	// 1. U
	$value = str_replace('ù', '&ugrave;', $value);
	$value = str_replace('Ù', '&Ugrave;', $value);
	$value = str_replace('û', '&ucirc;', $value);
	$value = str_replace('Û', '&Ucirc;', $value);
	$value = str_replace('ü', '&uuml;', $value);
	$value = str_replace('Ü', '&Uuml;', $value);
	$value = str_replace('Ú', '&Uacute;', $value);
	$value = str_replace('ú', '&uacute;', $value);
	$value = str_replace('ü', '&uuml;', $value);
	$value = str_replace('Ù', '&#217;', $value);
	$value = str_replace('Ú', '&#218;', $value);
	$value = str_replace('Û', '&#219;', $value);
	$value = str_replace('Ü', '&#220;', $value);
	$value = str_replace('ù', '&#249;', $value);
	$value = str_replace('ú', '&#250;', $value);
	$value = str_replace('û', '&#251;', $value);
	$value = str_replace('ü', '&#252;', $value);

	// 1. Y
	$value = str_replace('ÿ', '&yuml;', $value);
	$value = str_replace('Ÿ', '&Yuml;', $value);
	$value = str_replace('Ý', '&#221;', $value);
	$value = str_replace('ý', '&#253;', $value);
	$value = str_replace('ÿ', '&#255;', $value);
 
	// 1. X
	$value = str_replace('×', '&#215;', $value);  // Yeah, I know.  But otherwise the gap is confusing.  --Kris

	// 1. Other
	$value = str_replace('ç', '&ccedil;', $value);
	$value = str_replace('Ç', '&Ccedil;', $value);
	$value = str_replace('œ', '&oelig;', $value);
	$value = str_replace('Œ', '&OElig;', $value);

	// 1. Misc signs
	$value = str_replace('"',"&quot;","$value");
	$value = str_replace("'","&#039;","$value");
	$value = str_replace("<", "&lt;", "$value"); // less than
	$value = str_replace(">", "&gt;", "$value"); // greater than
	
	// 1. Punctuation
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

	// 1. Money
	$value = str_replace('€', '&euro;', $value);

	// 1. Degree
	$value = str_replace('Â°', '&deg;', $value);


	// 2. Remove rest of HTML tags
	// Remove HTML tags
	$value = htmlentities($value, ENT_COMPAT, "UTF-8");


	// 3. Replace double & for characters that needs to be converted
	// A
	$value = str_replace('&amp;aelig;', '&aelig;', $value); // æ
	$value = str_replace("&amp;aring;", "&aring;", $value); // å
	$value = str_replace('&amp;AElig;', '&AElig;', $value); // Æ
	$value = str_replace('&amp;Aring;', '&Aring;', $value); // Å

	$value = str_replace('&amp;aacute;', '&aacute;', $value); // á
	$value = str_replace('&amp;agrave;', '&agrave;', $value); // à
	$value = str_replace('&amp;Agrave;', '&Agrave;', $value); // À
	$value = str_replace('&amp;acirc;', '&acirc;', $value); // â
	$value = str_replace('&amp;Acirc;', '&Acirc;', $value); // Â
	$value = str_replace('&amp;Aacute;', '&Aacute;', $value); // Á

	$value = str_replace('&amp;#192;', '&#192;', $value); // À
	$value = str_replace('&amp;#193;', '&#193;', $value); // Á
	$value = str_replace('&amp;#196;', '&#196;', $value); // Ä
	$value = str_replace('&amp;#224;', '&#224;', $value); // à
	$value = str_replace('&amp;#225;', '&#225;', $value); // á
	$value = str_replace('&amp;#226;', '&#226;', $value); // â
	$value = str_replace('&amp;#227;', '&#227;', $value); // ã
	$value = str_replace('&amp;#228;', '&#228;', $value); // ä

	// C
	$value = str_replace('&amp;#199;', '&#199;', $value); // Ç
	$value = str_replace('&amp;#231;', '&#231;', $value); // ç

	// E
	$value = str_replace('&amp;egrave;', '&egrave;', $value); // è
	$value = str_replace('&amp;Egrave;', '&Egrave;', $value); // È
	$value = str_replace('&amp;eacute;', '&eacute;', $value); // é
	$value = str_replace('&amp;Eacute;', '&Eacute;', $value); // É
	$value = str_replace('&amp;ecirc;', '&ecirc;', $value); // ê
	$value = str_replace('&amp;Ecirc;', '&Ecirc;', $value); // Ê
	$value = str_replace('&amp;euml;', '&euml;', $value); // ë
	$value = str_replace('&amp;Euml;', '&Euml;', $value); // Ë
	$value = str_replace('&amp;#200;', '&#200;', $value); // È
	$value = str_replace('&amp;#201;', '&#201;', $value); // É
	$value = str_replace('&amp;#202;', '&#202;', $value); // Ê
	$value = str_replace('&amp;#203;', '&#203;', $value); // Ë
	$value = str_replace('&amp;#232;', '&#232;', $value); // è
	$value = str_replace('&amp;#233;', '&#233;', $value); // é
	$value = str_replace('&amp;#234;', '&#234;', $value); // ê
	$value = str_replace('&amp;#235;', '&#235;', $value); // ë

	// I
	$value = str_replace('&amp;icirc;', '&icirc;', $value); // î
	$value = str_replace('&amp;Icirc;', '&Icirc;', $value); // Î
	$value = str_replace('&amp;iuml;', '&iuml;', $value); // ï
	$value = str_replace('&amp;Iuml;', '&Iuml;', $value); // Ï
	$value = str_replace('&amp;Iacute;', '&Iacute;', $value); // Í
	$value = str_replace('&amp;iacute;', '&iacute;', $value); // í
	$value = str_replace('&amp;iquest;', '&iquest;', $value); // ¿
	$value = str_replace('&amp;iexcl;', '&iexcl;', $value); // ¡
	$value = str_replace('&amp;#204;', '&#204;', $value); // Ì
	$value = str_replace('&amp;#205;', '&#205;', $value); // Í
	$value = str_replace('&amp;#206;', '&#206;', $value); // Î
	$value = str_replace('&amp;#207;', '&#207;', $value); // Ï
	$value = str_replace('&amp;#236;', '&#236;', $value); // ì
	$value = str_replace('&amp;#237;', '&#237;', $value); // í
	$value = str_replace('&amp;#238;', '&#238;', $value); // î
	$value = str_replace('&amp;#239;', '&#239;', $value); // ï

	// D
	$value = str_replace('&amp;#208;', '&#208;', $value); // Ð

	// N
	$value = str_replace('&amp;Ntilde;', '&Ntilde;', $value); // Ñ
	$value = str_replace('&amp;ntilde;', '&ntilde;', $value); // ñ
	$value = str_replace('&amp;#209;', '&#209;', $value); // Ñ

	// O
	$value = str_replace('&amp;Oslash;', '&Oslash;', $value); // Ø
	$value = str_replace('&amp;oslash;', '&oslash;', $value); // ø
	$value = str_replace('&amp;ocirc;', '&ocirc;', $value); // ô
	$value = str_replace('&amp;Ocirc;', '&Ocirc;', $value); // Ô
	$value = str_replace('&amp;Oacute;', '&Oacute;', $value); // Ó
	$value = str_replace('&amp;oacute;', '&oacute;', $value); // ó
	$value = str_replace('&amp;ordm;', '&ordm;', $value); // º
	$value = str_replace('&amp;ordf;', '&ordf;', $value); // ª
	$value = str_replace('&amp;Ouml;', '&Ouml;', $value); // Ö
	$value = str_replace('&amp;ouml;', '&ouml;', $value); // ö
	$value = str_replace('&amp;#210;', '&#210;', $value); // Ò
	$value = str_replace('&amp;#211;', '&#211;', $value); // Ó
	$value = str_replace('&amp;#212;', '&#212;', $value); // Ô
	$value = str_replace('&amp;#213;', '&#213;', $value); // Õ
	$value = str_replace('&amp;#214;', '&#214;', $value); // Ö
	$value = str_replace('&amp;#240;', '&#240;', $value); // ð
	$value = str_replace('&amp;#241;', '&#241;', $value); // ñ
	$value = str_replace('&amp;#242;', '&#242;', $value); // ò
	$value = str_replace('&amp;#243;', '&#243;', $value); // ó
	$value = str_replace('&amp;#244;', '&#244;', $value); // ô
	$value = str_replace('&amp;#245;', '&#245;', $value); // õ
	$value = str_replace('&amp;#246;', '&#246;', $value); // ö

	// P
	$value = str_replace('&amp;#222;', '&#222;', $value); // Þ
	$value = str_replace('&amp;#254;', '&#254;', $value); // þ

	// S
	$value = str_replace('&amp;#223;', '&#223;', $value); // ß

	// U
	$value = str_replace('&amp;ugrave;', '&ugrave;', $value); // ù
	$value = str_replace('&amp;Ugrave;', '&Ugrave;', $value); // Ù
	$value = str_replace('&amp;ucirc;', '&ucirc;', $value); // û
	$value = str_replace('&amp;Ucirc;', '&Ucirc;', $value); // Û
	$value = str_replace('&amp;uuml;', '&uuml;', $value); // ü
	$value = str_replace('&amp;Uuml;', '&Uuml;', $value); // Ü
	$value = str_replace('&amp;Uacute;', '&Uacute;', $value); // Ú
	$value = str_replace('&amp;uacute;', '&uacute;', $value); // ú
	$value = str_replace('&amp;uuml;', '&uuml;', $value); // ü
	$value = str_replace('&amp;#217;', '&#217;', $value); // Ù
	$value = str_replace('&amp;#218;', '&#218;', $value); // Ú
	$value = str_replace('&amp;#219;', '&#219;', $value); // Û
	$value = str_replace('&amp;#220;', '&#220;', $value); // Ü
	$value = str_replace('&amp;#249;', '&#249;', $value); // ù
	$value = str_replace('&amp;#250;', '&#250;', $value); // ú
	$value = str_replace('&amp;#251;', '&#251;', $value); // û
	$value = str_replace('&amp;#252;', '&#252;', $value); // ü

	// Y
	$value = str_replace('&amp;yuml;', '&yuml;', $value); // ÿ
	$value = str_replace('&amp;Yuml;', '&Yuml;', $value); // Ÿ
	$value = str_replace('&amp;#221;', '&#221;', $value); // Ý
	$value = str_replace('&amp;#253;', '&#253;', $value); // ý
	$value = str_replace('&#255;', '&#255;', $value); // ÿ

	// X
	$value = str_replace('&amp;#215;', '&#215;', $value);  // ×

	// Other
	$value = str_replace('&amp;ccedil;', '&ccedil;', $value); // ç
	$value = str_replace('&amp;Ccedil;', '&Ccedil;', $value); // Ç
	$value = str_replace('&amp;oelig;', '&oelig;', $value); // œ
	$value = str_replace('&amp;OElig;', '&OElig;', $value); // Œ

	// Misc signs
	$value = str_replace('&amp;quot;', "&quot;","$value"); 
	$value = str_replace("&amp;#039;","&#039;","$value"); 
	$value = str_replace("&amp;gt;", "&gt;", "$value"); 
	$value = str_replace("&amp;lt;", "&lt;", $value); 
	
	//Punctuation
	$value = str_replace('&amp;laquo;', '&laquo;', $value); // «
	$value = str_replace('&amp;raquo;', '&raquo;', $value); // »
	$value = str_replace('&amp;lsaquo;', '&lsaquo;', $value); // ‹
	$value = str_replace('&amp;rsaquo;', '&rsaquo;', $value); // ›
	$value = str_replace('&amp;ldquo;', '&ldquo;', $value); // “
	$value = str_replace('&amp;rdquo;', '&rdquo;', $value); // ”
	$value = str_replace('&amp;lsquo;', '&lsquo;', $value); // ‘
	$value = str_replace('&amp;rsquo;', '&rsquo;', $value); // ’
	$value = str_replace('&amp;mdash;', '&mdash;', $value); // —
	$value = str_replace('&amp;ndash;', '&ndash;', $value); // –

	// Money
	$value = str_replace('&amp;euro;', '&euro;', $value); // €

	// Degree
	$value = str_replace('&amp;deg;', '&deg;', $value); // °


	// &
	$value = str_replace('&amp;amp;', '&amp;', $value); // &

	// 4. Misc sings
	$value = str_replace("\n","<br />", "$value");

	// Return
	return $value;
}
?>