<?php
/**
*
* File: _admin/_functions/encode_national_letters_from_html_to_xml_for_rss.php
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
function encode_national_letters_from_html_to_xml_for_rss($value){

	// Entity Code to Number Code
	$value = str_replace('&quot;', '&#34;', $value); // "	Quotation Mark
	$value = str_replace('&amp;', '&#38;', $value); // &	Ampersand
	$value = str_replace('&frasl;', '&#47;', $value); // /	Slash
	$value = str_replace('&lt;', '&#60;', $value); // <	Less Than Sign
	$value = str_replace('&gt;', '&#62;', $value); // >	Greater Than Sign
	$value = str_replace('&sbquo;', '&#130;', $value); // ‚	Single Low-9 Quote
	$value = str_replace('&bdquo;', '&#132;', $value); // „	Double Low-9 Quote
	$value = str_replace('&dagger;', '&#134;', $value); // †	Dagger
	$value = str_replace('&Dagger;', '&#135;', $value); // ‡	Double Dagger


	$value = str_replace('&permil;', '&#137;', $value); // ‰	Per Mill Sign
	$value = str_replace('&lsaquo;', '&#139;', $value); // ‹	Single Left Angle Quote
	$value = str_replace('&lsquo;', '&#145;', $value); // ‘	Left Single Quote
	$value = str_replace('&rsquo;', '&#146;', $value); // ’	Right Single Quote
	$value = str_replace('&ldquo;', '&#147;', $value); // “	Left Double Quote
	$value = str_replace('&rdquo;', '&#148;', $value); // ”	Right Double Quote
	$value = str_replace('&trade;', '&#153;', $value); // ™	Trademark Symbol
	$value = str_replace('&rsaquo;', '&#155;', $value); // ›	Single Right Angle Quote
	$value = str_replace('&nbsp;', '&#160;', $value); //  	Non Breaking Space
	$value = str_replace('&iexcl;', '&#161;', $value); // ¡	Inverted Exclamation Point
	$value = str_replace('&cent;', '&#162;', $value); // ¢	Cent Sign
	$value = str_replace('&pound;', '&#163;', $value); // £	Pound Sterling
	$value = str_replace('&curren;', '&#164;', $value); // ¤	General Currency Sign
	$value = str_replace('&yen;', '&#165;', $value); // ¥	Yen Sign
	$value = str_replace('&brvbar;', '&#166;', $value); // ¦	Broken Vertical Bar
	$value = str_replace('&sect;', '&#167;', $value); // §	Section Sign
	$value = str_replace('&uml;', '&#168;', $value); // ¨	Umlaut (Dieresis)
	$value = str_replace('&copy;', '&#169;', $value); // ©	Copyright Symbol
	$value = str_replace('&ordf;', '&#170;', $value); // ª	Feminine Ordinal
	$value = str_replace('&laquo;', '&#171;', $value); // «	Left Angle Quote, Left Guillemet
	$value = str_replace('&not;', '&#172;', $value); // ¬	Not Sign
	$value = str_replace('&shy;', '&#173;', $value); // ­	Soft Hyphen
	$value = str_replace('&reg;', '&#174;', $value); // ®	Registered Trademark
	$value = str_replace('&macr;', '&#175;', $value); // ¯	Macron, Overline
	$value = str_replace('&deg;', '&#176;', $value); // °	Degree Sign
	$value = str_replace('&plusmn;', '&#177;', $value); // ±	Plus or Minus
	$value = str_replace('&sup2;', '&#178;', $value); // ²	Superscript Two
	$value = str_replace('&sup3;', '&#179;', $value); // ³	Superscript Three
	$value = str_replace('&acute;', '&#180;', $value); // ´	Acute Accent
	$value = str_replace('&micro;', '&#181;', $value); // µ	Micro Symbol

	$value = str_replace('&para;', '&#182;', $value); // ¶	Paragraph Symbol
	$value = str_replace('&middot;', '&#183;', $value); // ·	Middle Dot
	$value = str_replace('&cedil;', '&#184;', $value); // ¸	Cedilla
	$value = str_replace('&sup1;', '&#185;', $value); // ¹	Superscript One
	$value = str_replace('&ordm;', '&#186;', $value); // º	Masculine Ordinal
	$value = str_replace('&raquo;', '&#187;', $value); // »	Right Angle Quote, Right Guillemet
	$value = str_replace('&frac14;', '&#188;', $value); // ¼	One Fourth Fraction
	$value = str_replace('&frac12;', '&#189;', $value); // ½	One Half Fraction
	$value = str_replace('&frac34;', '&#190;', $value); // ¾	Three Forths Fraction
	$value = str_replace('&iquest;', '&#191;', $value); // ¿	Inverted Question Mark
	$value = str_replace('&Agrave;', '&#192;', $value); // À	Capital A with Grave Accent
	$value = str_replace('&Aacute;', '&#193;', $value); // Á	Capital A with Acute Accent
	$value = str_replace('&Acirc;', '&#194;', $value); // Â	Capital A with Circumflex Accent
	$value = str_replace('&Atilde;', '&#195;', $value); // Ã	Capital A with Tilde
	$value = str_replace('&Auml;', '&#196;', $value); // Ä	Capital A with Dieresis/Umlaut
	$value = str_replace('&Aring;', '&#197;', $value); // Å	Capital A with Ring
	$value = str_replace('&AElig;', '&#198;', $value); // Æ	Capital AE Dipthong

	$value = str_replace('&Ccedil;', '&#199;', $value); // Ç	Capital C with Cedilla

	$value = str_replace('&Egrave;', '&#200;', $value); // È	Capital E with Grave Accent
	$value = str_replace('&Eacute;', '&#201;', $value); // É	Capital E with Acute Accent
	$value = str_replace('&Ecirc;', '&#202;', $value); // Ê	Capital E with Circumflex Accent
	$value = str_replace('&Euml;', '&#203;', $value); // Ë	Capital E with Dieresis/Umlaut

	$value = str_replace('&Igrave;', '&#204;', $value); // Ì	Capital I with Grave Accent
	$value = str_replace('&Iacute;', '&#205;', $value); // Í	Capital I with Acute Accent
	$value = str_replace('&Icirc;', '&#206;', $value); // Î	Capital I with Circumflex Accent
	$value = str_replace('&Iuml;', '&#207;', $value); // Ï	Capital I with Dieresis/Umlaut

	$value = str_replace('&ETH;', '&#208;', $value); // Ð	Capital Eth

	$value = str_replace('&Ntilde;', '&#209;', $value); // Ñ	Capital N with Tilde

	$value = str_replace('&Ograve;', '&#210;', $value); // Ò	Capital O with Grave Accent
	$value = str_replace('&Oacute;', '&#211;', $value); // Ó	Capital O with Acute Accent
	$value = str_replace('&Ocirc;', '&#212;', $value); // Ô	Capital O with Circumflex Accent
	$value = str_replace('&Otilde;', '&#213;', $value); // Õ	Capital O with Tilde
	$value = str_replace('&Ouml;', '&#214;', $value); // Ö	Capital O with Dieresis/Umlaut

	$value = str_replace('&times;', '&#215;', $value); // ×	Multiplication Sign

	$value = str_replace('&Oslash;', '&#216;', $value); // Ø	Capital O with a Slash

	$value = str_replace('&Ugrave;', '&#217;', $value); // Ù	Capital U with Grave Accent
	$value = str_replace('&Uacute;', '&#218;', $value); // Ú	Capital U with Acute Accent
	$value = str_replace('&Ucirc;', '&#219;', $value); // Û	Capital U with Circumflex Accent
	$value = str_replace('&Uuml;', '&#220;', $value); // Ü	Capital U with Dieresis/Umlaut

	$value = str_replace('&Yacute;', '&#221;', $value); // Ý	Capital Ywith Acute Accent
	$value = str_replace('&THORN;', '&#222;', $value); // Þ	Capital Thorn
	$value = str_replace('&szlig;', '&#223;', $value); // ß	Small Sharp s
	$value = str_replace('&agrave;', '&#224;', $value); // à	Small a with Grave Accent
	$value = str_replace('&aacute;', '&#225;', $value); // á	Small a with Acute Accent
	$value = str_replace('&acirc;', '&#226;', $value); // â	Small a with Circumflex Accent
	$value = str_replace('&atilde;', '&#227;', $value); // ã	Small a with Tilde
	$value = str_replace('&auml;', '&#228;', $value); // ä	Small a with Dieresis/Umlaut
	$value = str_replace('&aring;', '&#229;', $value); // å	Small a with Ring
	$value = str_replace('&aelig;', '&#230;', $value); // æ	Small ae Dipthong
	$value = str_replace('&ccedil;', '&#231;', $value); // ç	Small c with Cedilla
	$value = str_replace('&egrave;', '&#232;', $value); // è	Small e with Grave Accent
	$value = str_replace('&eacute;', '&#233;', $value); // é	Small e with Acute Accent
	$value = str_replace('&ecirc;', '&#234;', $value); // ê	Small e with Circumflex Accent
	$value = str_replace('&euml;', '&#235;', $value); // ë	Small e with Dieresis/Umlaut
	$value = str_replace('&igrave;', '&#236;', $value); // ì	Small i with Grave Accent
	$value = str_replace('&iacute;', '&#237;', $value); // í	Small i with Acute Accent
	$value = str_replace('&icirc;', '&#238;', $value); // î	Small i with Circumflex Accent
	$value = str_replace('&iuml;', '&#239;', $value); // ï	Small i with Dieresis/Umlaut
	$value = str_replace('&eth;', '&#240;', $value); // ð	Small eth
	$value = str_replace('&ntilde;', '&#241;', $value); // ñ	Small n with Tilde
	$value = str_replace('&ograve;', '&#242;', $value); // ò	Small o with Grave Accent
	$value = str_replace('&oacute;', '&#243;', $value); // ó	Small o with Acute Accent
	$value = str_replace('&ocirc;', '&#244;', $value); // ô	Small o with Circumflex Accent
	$value = str_replace('&otilde;', '&#245;', $value); // õ	Small o with Tilde
	$value = str_replace('&ouml;', '&#246;', $value); // ö	Small o with Dieresis/Umlaut
	$value = str_replace('&divide;', '&#247;', $value); // ÷	Division Sign
	$value = str_replace('&oslash;', '&#248;', $value); // ø	Small o with a Slash
	$value = str_replace('&ugrave;', '&#249;', $value); // ù	Small u with Grave Accent
	$value = str_replace('&uacute;', '&#250;', $value); // ú	Small u with Acute Accent
	$value = str_replace('&ucirc;', '&#251;', $value); // û	Small u with Circumflex Accent
	$value = str_replace('&uuml;', '&#252;', $value); // ü	Small u with Dieresis/Umlaut
	$value = str_replace('&yacute;', '&#253;', $value); // ý	Small y with Acute Accent
	$value = str_replace('&thorn;', '&#254;', $value); // þ	Small Thorn
	$value = str_replace('&yuml;', '&#255;', $value); // ÿ	Small y with Dieresis/Umlaut

	$value = str_replace('&fnof;', '&#402;', $value); // ƒ	Small f with hook
	$value = str_replace('&Alpha;', '&#913;', $value); // ?	Greek Capital Letter Alpha
	$value = str_replace('&Beta;', '&#914;', $value); // ?	Greek Capital Letter Beta
	$value = str_replace('&Gamma;', '&#915;', $value); // G	Greek Capital Letter Gamma
	$value = str_replace('&Delta;', '&#916;', $value); // ?	Greek Capital Letter Delta
	$value = str_replace('&Epsilon;', '&#917;', $value); // ?	Greek Capital Letter Epsilon
	$value = str_replace('&Zeta;', '&#918;', $value); // ?	Greek Capital Letter Zeta
	$value = str_replace('&Eta;', '&#919;', $value); // ?	Greek Capital Letter Eta
	$value = str_replace('&Theta;', '&#920;', $value); // T	Greek Capital Letter Theta
	$value = str_replace('&Iota;', '&#921;', $value); // ?	Greek Capital Letter Iota
	$value = str_replace('&Kappa;', '&#922;', $value); // ?	Greek Capital Letter Kappa
	$value = str_replace('&Lambda;', '&#923;', $value); // ?	Greek Capital Letter Lambda
	$value = str_replace('&Mu;', '&#924;', $value); // ?	Greek Capital Letter Mu
	$value = str_replace('&Nu;', '&#925;', $value); // ?	Greek Capital Letter Nu
	$value = str_replace('&Xi;', '&#926;', $value); // ?	Greek Capital Letter Xi
	$value = str_replace('&Omicron;', '&#927;', $value); // 	Greek Capital Letter Omicron
	$value = str_replace('&Pi;', '&#928;', $value); // ?	Greek Capital Letter Pi
	$value = str_replace('&Rho;', '&#929;', $value); // ?	Greek Capital Letter Rho
	$value = str_replace('&Sigma;', '&#931;', $value); // S	Greek Capital Letter Sigma
	$value = str_replace('&Tau;', '&#932;', $value); // ?	Greek Capital Letter Tau
	$value = str_replace('&Upsilon;', '&#933;', $value); // ?	Greek Capital Letter Upsilon
	$value = str_replace('&Phi;', '&#934;', $value); // F	Greek Capital Letter Phi
	$value = str_replace('&Chi;', '&#935;', $value); // ?	Greek Capital Letter Chi
	$value = str_replace('&Psi;', '&#936;', $value); // ?	Greek Capital Letter Psi
	$value = str_replace('&Omega;', '&#937;', $value); // O	Greek Capital Letter Omega
	$value = str_replace('&alpha;', '&#945;', $value); // a	Greek Small Letter Alpha
	$value = str_replace('&beta;', '&#946;', $value); // ß	Greek Small Letter Beta
	$value = str_replace('&gamma;', '&#947;', $value); // ?	Greek Small Letter Gamma
	$value = str_replace('&delta;', '&#948;', $value); // d	Greek Small Letter Delta
	$value = str_replace('&epsilon;', '&#949;', $value); // e	Greek Small Letter Epsilon
	$value = str_replace('&zeta;', '&#950;', $value); // ?	Greek Small Letter Zeta
	$value = str_replace('&eta;', '&#951;', $value); // ?	Greek Small Letter Eta
	$value = str_replace('&theta;', '&#952;', $value); // ?	Greek Small Letter Theta
	$value = str_replace('&iota;', '&#953;', $value); // ?	Greek Small Letter Iota
	$value = str_replace('&kappa;', '&#954;', $value); // ?	Greek Small Letter Kappa
	$value = str_replace('&lambda;', '&#955;', $value); // ?	Greek Small Letter Lambda
	$value = str_replace('&mu;', '&#956;', $value); // µ	Greek Small Letter Mu
	$value = str_replace('&nu;', '&#957;', $value); // ?	Greek Small Letter Nu
	$value = str_replace('&xi;', '&#958;', $value); // ?	Greek Small Letter Xi
	$value = str_replace('&omicron;', '&#959;', $value); // ?	Greek Small Letter Omicron
	$value = str_replace('&pi;', '&#960;', $value); // p	Greek Small Letter Pi
	$value = str_replace('&rho;', '&#961;', $value); // ?	Greek Small Letter Rho
	$value = str_replace('&sigmaf;', '&#962;', $value); // ?	Greek Small Letter Final Sigma
	$value = str_replace('&sigma;', '&#963;', $value); // s	Greek Small Letter Sigma
	$value = str_replace('&tau;', '&#964;', $value); // t	Greek Small Letter Tau
	$value = str_replace('&upsilon;', '&#965;', $value); // ?	Greek Small Letter Upsilon
	$value = str_replace('&phi;', '&#966;', $value); // f	Greek Small Letter Phi
	$value = str_replace('&chi;', '&#967;', $value); // ?	Greek Small Letter Chi
	$value = str_replace('&psi;', '&#968;', $value); // ?	Greek Small Letter Psi
	$value = str_replace('&omega;', '&#969;', $value); // ?	Greek Small Letter Omega
	$value = str_replace('&thetasym;', '&#977;', $value); // ?	Greek Small Letter Theta Symbol
	$value = str_replace('&upsih;', '&#978;', $value); // ?	Greek Upsilon with Hook Symbol
	$value = str_replace('&piv;', '&#982;', $value); // ?	Greek pi Symbol
	$value = str_replace('&bull;', '&#8226;', $value); // •	Bullet / Black Small Circle
	$value = str_replace('&hellip;', '&#8230;', $value); // …	Horizontal Ellipsis

	$value = str_replace('&prime;', '&#8242;', $value); // '	Prime / Minutes / Feet
	$value = str_replace('&Prime;', '&#8243;', $value); // ?	Double Prime / Seconds / Inches
	$value = str_replace('&oline;', '&#8254;', $value); // ?	Overline
	$value = str_replace('&image;', '&#8465;', $value); // I	Blackletter Capital I / Imaginary Part
	$value = str_replace('&weierp;', '&#8472;', $value); // P	Script Capital P / Power Set
	$value = str_replace('&real;', '&#8476;', $value); // R	Blackletter Capital R / Real Part Symbol
	$value = str_replace('&alefsym;', '&#8501;', $value); // ?	Alef Symbol / First Transfinite Cardinal
	$value = str_replace('&larr;', '&#8592;', $value); // ?	Left Arrow
	$value = str_replace('&uarr;', '&#8593;', $value); // ?	Up Arrow
	$value = str_replace('&rarr;', '&#8594;', $value); // ?	Right Arrow
	$value = str_replace('&darr;', '&#8595;', $value); // ?	Down Arrow
	$value = str_replace('&harr;', '&#8596;', $value); // ?	Left-Right Arrow
	$value = str_replace('&crarr;', '&#8629;', $value); // ?	Carrige Return / Downward Left Arrow
	$value = str_replace('&lArr;', '&#8656;', $value); // ?	Double Left Arrow
	$value = str_replace('&uArr;', '&#8657;', $value); // ?	Double Up Arrow
	$value = str_replace('&rArr;', '&#8658;', $value); // ?	Double Right Arrow
	$value = str_replace('&dArr;', '&#8659;', $value); // ?	Double Down Arrow
	$value = str_replace('&hArr;', '&#8660;', $value); // ?	Double Left-Right Arrow

	$value = str_replace('&forall;', '&#8704;', $value); // ?	For All
	$value = str_replace('&part;', '&#8706;', $value); // ?	Partial Differential
	$value = str_replace('&exist;', '&#8707;', $value); // ?	There Exists
	$value = str_replace('&empty;', '&#8709;', $value); // Ø	Empty Set
	$value = str_replace('&nabla;', '&#8711;', $value); // ?	Nabla / Backwards Difference
	$value = str_replace('&isin;', '&#8712;', $value); // ?	Element Of
	$value = str_replace('&notin;', '&#8713;', $value); // ?	Not An Element of
	$value = str_replace('&ni;', '&#8715;', $value); // ?	Contains As Member
	$value = str_replace('&prod;', '&#8719;', $value); // ?	Product Sign
	$value = str_replace('&sum;', '&#8721;', $value); // ?	Sumation
	$value = str_replace('&minus;', '&#8722;', $value); // -	Minus Sign
	$value = str_replace('&lowast;', '&#8727;', $value); // *	Asterisk Operator
	$value = str_replace('&radic;', '&#8730;', $value); // v	Square Root / Radical Sign
	$value = str_replace('&prop;', '&#8733;', $value); // ?	Proportional To
	$value = str_replace('&infin;', '&#8734;', $value); // 8	Infinity
	$value = str_replace('&ang;', '&#8736;', $value); // ?	Angle
	$value = str_replace('&and;', '&#8743;', $value); // ?	Logical And / Wedge
	$value = str_replace('&or;', '&#8744;', $value); // ?	Logical Or / Vee
	$value = str_replace('&cap;', '&#8745;', $value); // n	Inersection / Cap
	$value = str_replace('&cup;', '&#8746;', $value); // ?	Union / Cup
	$value = str_replace('&int;', '&#8747;', $value); // ?	Integral
	$value = str_replace('&there4;', '&#8756;', $value); // ?	Therefore
	$value = str_replace('&sim;', '&#8764;', $value); // ~	Tilde Operator / Similar To / Varies With
	$value = str_replace('&cong;', '&#8773;', $value); // ?	Approximately Equal To
	$value = str_replace('&asymp;', '&#8776;', $value); // ˜	Almost Equal To / Asymptotic To
	$value = str_replace('&ne;', '&#8800;', $value); // ?	Not Equal To
	$value = str_replace('&equiv;', '&#8801;', $value); // =	Identical To
	$value = str_replace('&le;', '&#8804;', $value); // =	Less Than or Equal To
	$value = str_replace('&ge;', '&#8805;', $value); // =	Greater Than or Equal To
	$value = str_replace('&sub;', '&#8834;', $value); // ?	Subset Of
	$value = str_replace('&sup;', '&#8835;', $value); // ?	Superset Of
	$value = str_replace('&nsub;', '&#8836;', $value); // ?	Not A Subset Of
	$value = str_replace('&sube;', '&#8838;', $value); // ?	Subset Of Or Equal To
	$value = str_replace('&supe;', '&#8839;', $value); // ?	Superset Of Or Equal To
	$value = str_replace('&oplus;', '&#8853;', $value); // ?	Circled Plus / Direct Sum
	$value = str_replace('&otimes;', '&#8855;', $value); // ?	Circled Times / Vector Product
	$value = str_replace('&perp;', '&#8869;', $value); // ?	Up Tack / Orthogonal To / Perpendicular
	$value = str_replace('&sdot;', '&#8901;', $value); // ·	Dot Operator
	$value = str_replace('&lceil;', '&#8968;', $value); // ?	Left Ceiling / Apl Upstile
	$value = str_replace('&rceil;', '&#8969;', $value); // ?	Right Ceiling
	$value = str_replace('&lfloor;', '&#8970;', $value); // ?	Left Floor / Apl Downstile
	$value = str_replace('&rfloor;', '&#8971;', $value); // ?	Right Floor
	$value = str_replace('&lang;', '&#9001;', $value); // <	Left-Pointing Angle Bracket / Bra
	$value = str_replace('&rang;', '&#9002;', $value); // >	Right-Pointing Angle Bracket / Ket
	$value = str_replace('&loz;', '&#9674;', $value); // ?	Lozenge
	$value = str_replace('&spades;', '&#9824;', $value); // ?	Black Spade Suit
	$value = str_replace('&clubs;', '&#9827;', $value); // ?	Black Club Suit
	$value = str_replace('&hearts;', '&#9829;', $value); // ?	Black Heart Suit
	$value = str_replace('&diams;', '&#9830;', $value); // ?	Black Diamond Suit 


	// Return
        return $value;
}
?>