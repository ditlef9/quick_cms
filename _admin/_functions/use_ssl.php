<?php
/**
*
* File: _admin/_functions/is_ssl.php
* Version: 2
* Date: 03.36 08.03.2017
* Copyright (c) 2017 Solo
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/


if(isset($_SERVER['HTTPS'])){
	// SSL in use right now, dont do anything

}
else{
	if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
		$redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: ' . $redirect);
		exit();	
	}
}

?>