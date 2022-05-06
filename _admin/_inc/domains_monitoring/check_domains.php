<?php
set_time_limit(0);

/**
*
* File: _admin/_inc/domains_monitoring/insert_domains_step_2_check_domains.php
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

/*- Functions ------------------------------------------------------------------------ */

/*- Tables ---------------------------------------------------------------------------- */
$t_domains_monitoring_liquidbase	= $mysqlPrefixSav . "domains_monitoring_liquidbase";
$t_domains_monitoring_domains_index	= $mysqlPrefixSav . "domains_monitoring_domains_index";
$t_domains_monitoring_filters_keywords	= $mysqlPrefixSav . "domains_monitoring_filters_keywords";


$t_domains_monitoring_domains_filtered		= $mysqlPrefixSav . "domains_monitoring_domains_filtered";
$t_domains_monitoring_domains_monitored		= $mysqlPrefixSav . "domains_monitoring_domains_monitored";

$t_domains_monitoring_stats_total		= $mysqlPrefixSav . "domains_monitoring_stats_total";


/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['count_new_domains_filtered'])) {
	$count_new_domains_filtered = $_GET['count_new_domains_filtered'];
	$count_new_domains_filtered = strip_tags(stripslashes($count_new_domains_filtered));
}
else{
	$count_new_domains_filtered = "0";
}



/*- Variables ------------------------------------------------------------------------ */
if($action == ""){
	if($process == "1"){
		

		$url = "_inc/domains_monitoring/check_domains_script/check_domains_script.php?domain_number=0&keyword_number=0";
		header("Location: $url");
		exit;
	} // process == 1

	echo"
	<h1>Check domains</h2>


	<!-- Feedback -->
	";
	if($ft != ""){
		$fm = ucfirst($fm);
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->

	<p>
	<a href=\"index.php?open=domains_monitoring&amp;page=check_domains&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_default\">Start domain check</a>
	<a href=\"index.php?open=domains_monitoring&amp;page=check_domains&amp;action=set_all_domains_not_checked&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_default\">Set all domains not checked</a>
	</p>

	";

}
elseif($action == "set_all_domains_not_checked"){

	mysqli_query($link, "UPDATE $t_domains_monitoring_domains_index SET 
				domain_checked_other_by_script=0, 
				domain_checked_starts_with_ends_with_by_script=0, 
				domain_filters_activated=0") or die(mysql_error($link));

	$url = "index.php?open=domains_monitoring&page=check_domains&editor_language=$editor_language&l=$l&process=1";
	header("Location: $url");
	exit;		
} // action == "reset script
?>