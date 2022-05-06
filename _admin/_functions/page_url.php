<?php
/**
*
* File: _admin/_functions/page_url.php
* Version 16:05 25.08.2011
* Copyright (c) 2008-2011 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Page URL --------------------------------------------------------------------------- */

if ($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443") {
	$page = $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
} 
else {
	$page = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
}
if(isset($configSLLActiveSav) && $configSLLActiveSav == 1){
	$page_url = 'https://' . $page;
}
else{
	$page_url = 'http://' . $page;
}

/*- Check URL --------------------------------------------------------------------------- */
if(isset($configSiteURLLenSav)){
	$page_url_substr = substr($page_url, 0, $configSiteURLLenSav);
	if($configSiteURLSav != "$page_url_substr"){
		// Check for localhost
		$check_localhost = substr($page_url, 0, 16);
		if($check_localhost != "http://localhost"){


			header("Location: $configSiteURLSav");
			exit;
	
			echo"<p>Security error. Page url is not the same as configured. Please log in to control panel and go to Settings where
			you can set &quot;Site URL&quot;.	$check_localhost 
			</p>

			<p>
			<a href=\"$configSiteURLSav\">$configSiteURLSav</a> != $page_url_substr
			</p>
			";
			die;
		}
	}
}


/*- Current page for menu -------------------------------------------------------------- */
$request_url 		= $_SERVER["REQUEST_URI"];
$request_url_array	= explode("/", $request_url);
$request_url_array_size = sizeof($request_url_array);
$minus_one		= $request_url_array_size-2;
$current_active_a	= $request_url_array[$minus_one];

// If it is another
$minus_two		= $request_url_array_size-3;
if(isset($request_url_array[$minus_two])){
	$current_active_b	= $request_url_array[$minus_two];
}
$minus_three		= $request_url_array_size-4;
if(isset($request_url_array[$minus_three])){
	$current_active_c = $request_url_array[$minus_three];
}
else{
	$current_active_c = "";
}


?>