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
	$str = str_replace("ยซ","ซ","$str");
	$str = str_replace("ยป","ป","$str");
	$str = str_replace("รฅ","ๅ","$str");
	$str = str_replace("รธ","๘","$str");
	$str = str_replace("รฆ","ๆ","$str");
	$str = str_replace("ร","ุ","$str");
	$str = str_replace("ร…","ล","$str");
	$str = str_replace("ร…","ล","$str");
	$str = str_replace("โ€“","","$str");
	$str = str_replace("รก","แ","$str");
	$str = str_replace("รถ","๖","$str");
	$str = str_replace("รฉ","้","$str");
	$str = str_replace("\n","<br />","$str");
	$str = str_replace("โ€","&rdquo;","$str");
	return $str;
}
?>