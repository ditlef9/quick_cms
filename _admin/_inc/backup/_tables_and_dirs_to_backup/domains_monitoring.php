<?php
/**
*
* File: _admin/_inc/backup/_tables_and_dirs_to_backup/domains_monitoring.php
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
$t_domains_monitoring_liquidbase		= $mysqlPrefixSav . "domains_monitoring_liquidbase";
$t_domains_monitoring_domains_index		= $mysqlPrefixSav . "domains_monitoring_domains_index";
$t_domains_monitoring_domains_tld_count		= $mysqlPrefixSav . "domains_monitoring_domains_tld_count";
$t_domains_monitoring_filters_index		= $mysqlPrefixSav . "domains_monitoring_filters_index";
$t_domains_monitoring_filters_keywords		= $mysqlPrefixSav . "domains_monitoring_filters_keywords";

$t_domains_monitoring_domains_filtered		= $mysqlPrefixSav . "domains_monitoring_domains_filtered";
$t_domains_monitoring_domains_monitored		= $mysqlPrefixSav . "domains_monitoring_domains_monitored";

$t_domains_monitoring_stats_total		= $mysqlPrefixSav . "domains_monitoring_stats_total";



$tables_truncate_array = array(
			"$t_domains_monitoring_domains_index", 
			"$t_domains_monitoring_domains_tld_count", 
			"$t_domains_monitoring_domains_filtered", 
			"$t_domains_monitoring_stats_total");

$tables_backup_array = array(
			"$t_domains_monitoring_liquidbase", 
			"$t_domains_monitoring_domains_index", 
			"$t_domains_monitoring_domains_tld_count", 
			"$t_domains_monitoring_filters_index", 
			"$t_domains_monitoring_filters_keywords", 

			"$t_domains_monitoring_domains_filtered", 
			"$t_domains_monitoring_domains_monitored", 

			"$t_domains_monitoring_stats_total");

/*- Directories ---------------------------------------------------------------------------- */

$directories_array = array();

?>