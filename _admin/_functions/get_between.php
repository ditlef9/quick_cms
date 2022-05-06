<?php
/**
*
* File: _admin/_functions/get_between.php
* Version: 2
* Date: 03.36 08.03.2017
* Copyright (c) 2017 Solo
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

function get_between($content,$start,$end){
	$r = explode($start, $content);
	if (isset($r[1])){
		$r = explode($end, $r[1]);
		return $r[0];
	}
	return '';
}
?>