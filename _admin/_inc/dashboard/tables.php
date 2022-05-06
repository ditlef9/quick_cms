<?php
/**
*
* File: _admin/_inc/dashboard/tables.php
* Version 1.0.0
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Functions ------------------------------------------------------------------------ */
function fix_utf($value){
	$value = str_replace("Ã¸", "�", $value);
	$value = str_replace("Ã¥", "�", $value);

        return $value;
}
function fix_local($value){
	$value = htmlentities($value);

        return $value;
}
/*- Tables ---------------------------------------------------------------------------- */
$t_banned_hostnames	= $mysqlPrefixSav . "banned_hostnames";
$t_banned_ips	 	= $mysqlPrefixSav . "banned_ips";
$t_banned_user_agents	= $mysqlPrefixSav . "banned_user_agents";

echo"
<h1>Tables</h1>



	



	";
?>