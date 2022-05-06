<?php
/**
*
* File: _admin/_inc/backup/_tables_and_dirs_to_backup/pages.php
* Version 18:17 13.01.2022
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
$t_pages				 = $mysqlPrefixSav . "pages";
$t_pages_cookies_policy			 = $mysqlPrefixSav . "pages_cookies_policy";
$t_pages_cookies_policy_accepted	 = $mysqlPrefixSav . "pages_cookies_policy_accepted";
$t_pages_navigation			 = $mysqlPrefixSav . "pages_navigation";
$t_pages_privacy_policy			 = $mysqlPrefixSav . "pages_privacy_policy";
$t_pages_terms_of_use			 = $mysqlPrefixSav . "pages_terms_of_use";


$tables_truncate_array = array(
			"$t_pages_cookies_policy_accepted"
			);

$tables_backup_array = array(
			"$t_pages",
			"$t_pages_cookies_policy",
			"$t_pages_cookies_policy_accepted",
			"$t_pages_navigation",
			"$t_pages_privacy_policy",
			"$t_pages_terms_of_use");

/*- Directories ---------------------------------------------------------------------------- */

$directories_array = array();

?>