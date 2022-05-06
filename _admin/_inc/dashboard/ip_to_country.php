<?php
/**
*
* File: _admin/_inc/ip_to_country.php
* Version 1
* Date 10:05 26.12.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}



/*- Tables ------------------------------------------------------------------------ */
$t_languages_countries 			= $mysqlPrefixSav . "languages_countries";
$t_stats_ip_to_country_lookup_ipv4 	= $mysqlPrefixSav . "stats_ip_to_country_lookup_ipv4";
$t_stats_ip_to_country_lookup_ipv6 	= $mysqlPrefixSav . "stats_ip_to_country_lookup_ipv6";

/*- Variables -------------------------------------------------------------------------- */


/*- Script start -------------------------------------------------------------------------- */
if($action == ""){
	echo"
	<h1>IP to Country</h1>

	<!-- Buttons -->
		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=find_ipv4s_to_country&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Find IPv4s to Country</a>
		</p>
	<!-- //Buttons -->
	";
}
elseif($action == "find_ipv4s_to_country"){
	if(isset($_GET['ip_1'])) {
		$ip_1 = $_GET['ip_1'];
		$ip_1 = strip_tags(stripslashes($ip_1));
		if(!(is_numeric($ip_1))){
			echo"<p>Error: ip_1 is not numeric</p>";
			die;
		}
	}
	else{
		$ip_1 = "0";
	}
	if(isset($_GET['ip_2'])) {
		$ip_2 = $_GET['ip_2'];
		$ip_2 = strip_tags(stripslashes($ip_2));
		if(!(is_numeric($ip_2))){
			echo"<p>Error: ip_2 is not numeric</p>";
			die;
		}
	}
	else{
		$ip_2 = "0";
	}
	if(isset($_GET['ip_3'])) {
		$ip_3 = $_GET['ip_3'];
		$ip_3 = strip_tags(stripslashes($ip_3));
		if(!(is_numeric($ip_3))){
			echo"<p>Error: ip_3 is not numeric</p>";
			die;
		}
	}
	else{
		$ip_3 = "0";
	}
	if(isset($_GET['ip_4'])) {
		$ip_4 = $_GET['ip_4'];
		$ip_4 = strip_tags(stripslashes($ip_4));
		if(!(is_numeric($ip_4))){
			echo"<p>Error: ip_4 is not numeric</p>";
			die;
		}
	}
	else{
		$ip_4 = "0";
	}

	for($a=0;$a<256;$a++){
		echo"
		$a.<br />
		";
	}
	

	

} // find_ipv4s_to_country
?>