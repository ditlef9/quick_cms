<?php
/**
*
* File: _admin/_functions/fix_rss.php
* Version 16:05 25.08.2011
* Copyright (c) 2008-2011 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
function decode_rss($str){
	$str = utf8_encode($str);
	$str = utf8_decode($str);
	$str = str_replace("«","�","$str");
	$str = str_replace("»","�","$str");
	$str = str_replace("å","�","$str");
	$str = str_replace("ø","�","$str");
	$str = str_replace("æ","�","$str");
	$str = str_replace("Ø","�","$str");
	$str = str_replace("Å�","�","$str");
	$str = str_replace("Å","�","$str");
	$str = str_replace("–","","$str");
	$str = str_replace("á","�","$str");
	$str = str_replace("ö","�","$str");
	$str = str_replace("é","�","$str");
	$str = str_replace("\n","<br />","$str");
	$str = str_replace("”","&rdquo;","$str");
	return $str;
}
?>