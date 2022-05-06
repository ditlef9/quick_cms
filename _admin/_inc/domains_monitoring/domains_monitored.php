<?php
/**
*
* File: _admin/_inc/domains_monitoring/domains_monitored.php
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
$t_domains_monitoring_liquidbase		= $mysqlPrefixSav . "domains_monitoring_liquidbase";
$t_domains_monitoring_domains_index		= $mysqlPrefixSav . "domains_monitoring_domains_index";
$t_domains_monitoring_filters_index		= $mysqlPrefixSav . "domains_monitoring_filters_index";
$t_domains_monitoring_filters_keywords		= $mysqlPrefixSav . "domains_monitoring_filters_keywords";


$t_domains_monitoring_domains_filtered		= $mysqlPrefixSav . "domains_monitoring_domains_filtered";
$t_domains_monitoring_domains_monitored		= $mysqlPrefixSav . "domains_monitoring_domains_monitored";

$t_domains_monitoring_stats_total = $mysqlPrefixSav . "domains_monitoring_stats_total";

// Stats
$query = "SELECT total_id, total_domains, total_domains_other_checked, total_domains_other_checked_percentage, total_domains_other_not_checked, total_domains_starts_with_ends_with_checked, total_domains_starts_with_ends_with_checked_percentage, total_domains_starts_with_ends_with_not_checked, total_domains_filtered, total_domains_monitored, total_last_checked_time FROM $t_domains_monitoring_stats_total LIMIT 0,1";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_total_id, $get_current_total_domains, $get_current_total_domains_other_checked, $get_current_total_domains_other_checked_percentage, $get_current_total_domains_other_not_checked, $get_current_total_domains_starts_with_ends_with_checked, $get_current_total_domains_starts_with_ends_with_checked_percentage, $get_current_total_domains_starts_with_ends_with_not_checked, $get_current_total_domains_filtered, $get_current_total_domains_monitored, $get_current_total_last_checked_time) = $row;

if($action == ""){
	echo"
	<h1>Domains monitored ($get_current_total_domains_monitored)</h1>


	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=domains_monitoring&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Domains monitoring</a>
		&gt;
		<a href=\"index.php?open=domains_monitoring&amp;page=domains_filtered&amp;editor_language=$editor_language&amp;l=$l\">Domains monitored</a>
		</p>
	<!-- //Where am I? -->


	<!-- Feedback -->
	";
	if($ft != ""){
		$fm = ucfirst($fm);
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->

	<!-- Domains monitored list -->
		
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>Value</span>
		   </th>
		   <th scope=\"col\">
			<span>TLD</span>
		   </th>
		   <th scope=\"col\">
			<span>Date</span>
		   </th>
		   <th scope=\"col\">
			<span>IP</span>
		   </th>
		   <th scope=\"col\">
			<span>Seen times</span>
		   </th>
		   <th scope=\"col\">
			<span>Filter</span>
		   </th>
		   <th scope=\"col\">
			<span>Actions</span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>";


		$count_domains_monitored = 0;
		$query = "SELECT monitored_id, monitored_domain_id, monitored_domain_value, monitored_group_id, monitored_by_user_id, monitored_notes, monitored_date_saying, monitored_datetime, monitored_domain_sld, monitored_domain_tld, monitored_domain_sld_length, monitored_domain_registered_date, monitored_domain_registered_date_saying, monitored_domain_registered_datetime, monitored_domain_seen_before_times, monitored_domain_ip, monitored_domain_host_name, monitored_domain_host_url, monitored_domain_filters_activated, monitored_domain_seen_by_group, monitored_domain_emailed FROM $t_domains_monitoring_domains_monitored ORDER BY monitored_id DESC LIMIT 0,1000";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_monitored_id, $get_monitored_domain_id, $get_monitored_domain_value, $get_monitored_group_id, $get_monitored_by_user_id, $get_monitored_notes, $get_monitored_date_saying, $get_monitored_datetime, $get_monitored_domain_sld, $get_monitored_domain_tld, $get_monitored_domain_sld_length, $get_monitored_domain_registered_date, $get_monitored_domain_registered_date_saying, $get_monitored_domain_registered_datetime, $get_monitored_domain_seen_before_times, $get_monitored_domain_ip, $get_monitored_domain_host_name, $get_monitored_domain_host_url, $get_monitored_domain_filters_activated, $get_monitored_domain_seen_by_group, $get_monitored_domain_emailed) = $row;

			echo"
			 <tr>
			  <td>
				<span>
				<a id=\"monitored_id$get_monitored_id\"></a>
				<a href=\"https://$get_monitored_domain_value\">$get_monitored_domain_value</a>
				</span>
			  </td>
			  <td>
				<span>$get_monitored_domain_tld</span>
			  </td>
			  <td>
				<span>$get_monitored_domain_registered_date_saying</span>
			  </td>
			  <td>
				<span>$get_monitored_domain_ip
				</span>
			  </td>
			  <td>
				<span>$get_monitored_domain_seen_before_times
				</span>
			  </td>
			  <td>
				<span>$get_monitored_domain_filters_activated</span>
			  </td>
			  <td>
				<span>
				<a href=\"https://whois.domaintools.com/$get_monitored_domain_value\">Whois</a>
				|
				<a href=\"https://transparencyreport.google.com/https/certificates?cert_search_auth=&amp;cert_search_cert=&amp;cert_search=include_subdomains:true;domain:$get_monitored_domain_value&amp;lu=cert_search\">Subdomains</a>
				|
				<a href=\"index.php?open=$open&amp;page=domains_monitored&amp;action=remove&amp;monitored_id=$get_monitored_id&amp;l=$l&amp;editor_language=$editor_language\">Remove</a>

				</span>
			  </td>
			 </tr>

			";
			$count_domains_monitored++;
		} // while
		if($count_domains_monitored != "$get_current_total_domains_monitored"){
			mysqli_query($link, "UPDATE $t_domains_monitoring_stats_total SET 
							total_domains_monitored=$count_domains_monitored
							WHERE total_id=$get_current_total_id") or die(mysql_error($link));

		}
		echo"
		 </tbody>
		</table>

	<!-- //Domains monitored list -->
	";
}
elseif($action == "remove"){
	if (isset($_GET['monitored_id'])) {
		$monitored_id = $_GET['monitored_id'];
		$monitored_id = stripslashes(strip_tags($monitored_id));
		if(!(is_numeric($monitored_id))){
			echo"monitored id not numeric";
			die;
		}
	}
	else{
		echo"Missing monitored id";
		die;
	}
	$monitored_id_mysql = quote_smart($link, $monitored_id);

	

	// Get monitored
	$query = "SELECT monitored_id, monitored_domain_id, monitored_domain_value, monitored_group_id, monitored_by_user_id, monitored_notes, monitored_date_saying, monitored_datetime, monitored_domain_sld, monitored_domain_tld, monitored_domain_sld_length, monitored_domain_registered_date, monitored_domain_registered_date_saying, monitored_domain_registered_datetime, monitored_domain_seen_before_times, monitored_domain_ip, monitored_domain_host_name, monitored_domain_host_url, monitored_domain_filters_activated, monitored_domain_seen_by_group, monitored_domain_emailed FROM $t_domains_monitoring_domains_monitored WHERE monitored_id=$monitored_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_monitored_id, $get_current_monitored_domain_id, $get_current_monitored_domain_value, $get_current_monitored_group_id, $get_current_monitored_by_user_id, $get_current_monitored_notes, $get_current_monitored_date_saying, $get_current_monitored_datetime, $get_current_monitored_domain_sld, $get_current_monitored_domain_tld, $get_current_monitored_domain_sld_length, $get_current_monitored_domain_registered_date, $get_current_monitored_domain_registered_date_saying, $get_current_monitored_domain_registered_datetime, $get_current_monitored_domain_seen_before_times, $get_current_monitored_domain_ip, $get_current_monitored_domain_host_name, $get_current_monitored_domain_host_url, $get_current_monitored_domain_filters_activated, $get_current_monitored_domain_seen_by_group, $get_current_monitored_domain_emailed) = $row;
	if($get_current_monitored_id == ""){
		echo"current monitored id not found";
	}
	else{
		if($process == "1"){
			// Get next ID
			$query = "SELECT monitored_id FROM $t_domains_monitoring_domains_monitored WHERE monitored_id > $get_current_monitored_id LIMIT 0,1";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_next_monitored_id) = $row;



			// Delete
			mysqli_query($link, "DELETE FROM $t_domains_monitoring_domains_monitored WHERE monitored_id=$get_current_monitored_id") or die(mysqli_error($link));
			

			

			$url = "index.php?open=domains_monitoring&page=domains_monitored&editor_language=$editor_language&l=$l&ft=success&fm=monitored_deleted#monitored_id$get_next_monitored_id";
			header("Location: $url");
			exit;
		}
		echo"
		<h1>Remove $get_current_monitored_domain_value</h1>


		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=domains_monitoring&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Domains monitoring</a>
			&gt;
			<a href=\"index.php?open=domains_monitoring&amp;page=domains_monitored&amp;editor_language=$editor_language&amp;l=$l\">Domains monitored</a>
			&gt;
			<a href=\"index.php?open=domains_monitoring&amp;page=domains_monitored&amp;action=remove&amp;monitored_id=$get_current_monitored_id&amp;editor_language=$editor_language&amp;l=$l\">Remove $get_current_monitored_domain_value</a>
			</p>
		<!-- //Where am I? -->


		<!-- Feedback -->
			";
			if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->

		<!-- Delete monitored form -->
			<p>Are you sure you want to delete <b>$get_current_monitored_domain_value</b>?</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=domains_monitored&amp;action=remove&amp;monitored_id=$get_current_monitored_id&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" class=\"btn_danger\">Confirm</a>
			</p>
		<!-- //Delete monitored form -->
		";
	} // monitored found
} // remove
?>