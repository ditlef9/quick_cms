<?php
/**
*
* File: _admin/_inc/backup/_tables_and_dirs_to_backup/ads.php
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
$t_ads_index 		= $mysqlPrefixSav . "ads_index";
$t_ads_advertisers	= $mysqlPrefixSav . "ads_advertisers";



$tables_truncate_array = array();

$tables_backup_array = array("$t_ads_index",
"$t_ads_advertisers");

/*- Directories ---------------------------------------------------------------------------- */

$directories_array = array("_uploads/a");

?>