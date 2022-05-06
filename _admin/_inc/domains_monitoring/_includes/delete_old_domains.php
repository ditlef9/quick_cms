<?php
/**
*
* File: _admin/_inc/domains_monitoring/_includes/delete_old_domains.php
* Version 09:19 31.08.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Settings --------------------------------------------------------------------------- */
include("_data/domain_monitoring.php");

/*- Tables ---------------------------------------------------------------------------- */
$t_domains_monitoring_liquidbase		= $mysqlPrefixSav . "domains_monitoring_liquidbase";
$t_domains_monitoring_domains_index		= $mysqlPrefixSav . "domains_monitoring_domains_index";
$t_domains_monitoring_domains_tld_count		= $mysqlPrefixSav . "domains_monitoring_domains_tld_count";
$t_domains_monitoring_filters_index		= $mysqlPrefixSav . "domains_monitoring_filters_index";
$t_domains_monitoring_filters_keywords		= $mysqlPrefixSav . "domains_monitoring_filters_keywords";

// Start
$date = date("Y-m-d");
if($date != "$lastCheckedDeleteRoutineSav"){
	// Update date
	$update_file="<?php
// Updated by _admin/_inc/domains_monitoring/_includes/delete_old_domains.php
// General
\$daysToKeepDomainsSav = \"$daysToKeepDomainsSav\";
\$lastCheckedDeleteRoutineSav = \"$date\";
?>";

	$fh = fopen("_data/domain_monitoring.php", "w+") or die("can not open file");
	fwrite($fh, $update_file);
	fclose($fh);
	

	// Count domains to delete
	$query = "SELECT count(domain_id) FROM $t_domains_monitoring_domains_index WHERE domain_created_date  < now() - interval $daysToKeepDomainsSav DAY";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_count_domain_id) = $row;

	if($get_count_domain_id > 0){
		mysqli_query($link, "DELETE FROM $t_domains_monitoring_domains_index WHERE domain_created_date  < now() - interval $daysToKeepDomainsSav DAY") or die(mysqli_error($link));
		mysqli_query($link, "DELETE FROM $t_domains_monitoring_domains_tld_count WHERE count_date < now() - interval $daysToKeepDomainsSav DAY") or die(mysqli_error($link));
		mysqli_query($link, "DELETE FROM $t_domains_monitoring_domains_tld_count WHERE count_domains=0") or die(mysqli_error($link));
		echo"
		<div class=\"info\"><p>Deleted $get_count_domain_id domains</p></div>
		";
	}

} // Delete

?>