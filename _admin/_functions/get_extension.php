<?php
/**
*
* File: _admin/_functions/get_extension.php
* Version: 2
* Date: 03.36 08.03.2017
* Copyright (c) 2017 Solo
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/




// Get extention
function get_extension($str) {
	$i = strrpos($str,".");
	if (!$i) { return ""; } 
	$l = strlen($str) - $i;
	$ext = substr($str,$i+1,$l);
	return $ext;
}

?>