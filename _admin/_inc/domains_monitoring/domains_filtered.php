<?php
/**
*
* File: _admin/_inc/domains_monitoring/domains_filtered.php
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
$t_domains_monitoring_domains_tld_count		= $mysqlPrefixSav . "domains_monitoring_domains_tld_count";
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
	<h1>Domains filtered ($get_current_total_domains_filtered)</h1>


	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=domains_monitoring&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Domains monitoring</a>
		&gt;
		<a href=\"index.php?open=domains_monitoring&amp;page=domains_filtered&amp;editor_language=$editor_language&amp;l=$l\">Domains filtered</a>
		</p>
	<!-- //Where am I? -->


	<!-- Feedback -->
	";
	if($ft != ""){
		$fm = str_replace("_", " ", $fm);
		$fm = ucfirst($fm);
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->

	<p>
	<a href=\"index.php?open=domains_monitoring&amp;page=check_domains&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_default\">Start domain check</a>
	</p>

	<!-- Domains filtered list -->
		<!-- On check check all -->
			<script>
			\$(document).ready(function(){
				\$(\".on_check_check_all\").click(function(){
					\$('input:checkbox').not(this).prop('checked', this.checked);
				});
			});
			</script>
		<!-- //On check check all -->

		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=with_checked_filtered&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span><input type=\"checkbox\" name=\"inp_check_all\" class=\"on_check_check_all\" />
			Domain</span>
		   </th>
		   <th scope=\"col\">
			<span>Host</span>
		   </th>
		   <th scope=\"col\">
			<span>IP</span>
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

		$count_domains_filtered = 0;
		$query = "SELECT filtered_id, filtered_domain_id, filtered_domain_value, filtered_group_id, filtered_by_user_id, filtered_date_saying, filtered_datetime, filtered_domain_sld, filtered_domain_tld, filtered_domain_sld_length, filtered_score, filtered_domain_registered_date, filtered_domain_registered_date_saying, filtered_domain_registered_datetime, filtered_domain_seen_before_times, filtered_domain_ip, filtered_domain_host_addr, filtered_domain_host_name, filtered_domain_host_url, filtered_domain_filters_activated, filtered_domain_seen_by_group, filtered_domain_emailed, filtered_notes FROM $t_domains_monitoring_domains_filtered ORDER BY filtered_score DESC LIMIT 0,1000";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_filtered_id, $get_filtered_domain_id, $get_filtered_domain_value, $get_filtered_group_id, $get_filtered_by_user_id, $get_filtered_date_saying, $get_filtered_datetime, $get_filtered_domain_sld, $get_filtered_domain_tld, $get_filtered_domain_sld_length, $get_filtered_score, $get_filtered_domain_registered_date, $get_filtered_domain_registered_date_saying, $get_filtered_domain_registered_datetime, $get_filtered_domain_seen_before_times, $get_filtered_domain_ip, $get_filtered_domain_host_addr, $get_filtered_domain_host_name, $get_filtered_domain_host_url, $get_filtered_domain_filters_activated, $get_filtered_domain_seen_by_group, $get_filtered_domain_emailed, $get_filtered_notes) = $row;

			echo"
			 <tr>
			  <td>
				<a id=\"filtered_id$get_filtered_id\"></a>
				<span>
				<input type=\"checkbox\" name=\"inp_filtered_$get_filtered_id\" />
				<a href=\"https://$get_filtered_domain_value\" style=\"font-weight: bold; color: black;\">$get_filtered_domain_value</a>
				</span>
			  </td>
			  <td>
				<span>$get_filtered_domain_host_name</span>
			  </td>
			  <td>
				<span>$get_filtered_domain_ip</span>
			  </td>
			  <td>
				<span>$get_filtered_domain_filters_activated</span>
			  </td>
			  <td>
				<span>
				<a href=\"https://whois.domaintools.com/$get_filtered_domain_value\">Whois</a>
				|
				<a href=\"https://transparencyreport.google.com/https/certificates?cert_search_auth=&amp;cert_search_cert=&amp;cert_search=include_subdomains:true;domain:$get_filtered_domain_value&amp;lu=cert_search\">Subdomains</a>
				|


				<a href=\"index.php?open=$open&amp;page=domains_filtered&amp;action=monitor_domain&amp;filtered_id=$get_filtered_id&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\">Monitor domain</a>
				|
				<a href=\"index.php?open=$open&amp;page=domains_filtered&amp;action=remove&amp;filtered_id=$get_filtered_id&amp;l=$l&amp;editor_language=$editor_language\">Remove</a>

				</span>
			  </td>
			 </tr>

			";
			$count_domains_filtered++;
		} // while
		if($count_domains_filtered != "$get_current_total_domains_filtered"){
			mysqli_query($link, "UPDATE $t_domains_monitoring_stats_total SET 
							total_domains_filtered=$count_domains_filtered 
							WHERE total_id=$get_current_total_id") or die(mysql_error($link));

		}

		echo"
		 </tbody>
		 <thead>
		  <tr>
		   <th scope=\"col\" colspan=\"5\">
			<span><input type=\"checkbox\" name=\"inp_check_all\" class=\"on_check_check_all\" />
			</span>
		   </th>
		  </tr>
		 </thead>
		</table>
		
		<p>
		<input type=\"submit\" name=\"submit\" value=\"Remove selected\" class=\"btn_default\" />
		</form>
	<!-- //Domains filtered list -->

	<meta http-equiv=\"refresh\" content=\"3600;url=index.php?open=domains_monitoring&amp;page=domains_filtered&amp;editor_language=$editor_language&amp;l=$l\" />
	
	";
}
elseif($action == "remove"){
	if (isset($_GET['filtered_id'])) {
		$filtered_id = $_GET['filtered_id'];
		$filtered_id = stripslashes(strip_tags($filtered_id));
		if(!(is_numeric($filtered_id))){
			echo"filtered_id not numeric";
			die;
		}
	}
	else{
		echo"Missing filtered_id";
		die;
	}
	$filtered_id_mysql = quote_smart($link, $filtered_id);

	

	// Get filtered
	$query = "SELECT filtered_id, filtered_domain_id, filtered_domain_value, filtered_group_id, filtered_by_user_id, filtered_notes, filtered_date_saying, filtered_datetime, filtered_domain_sld, filtered_domain_tld, filtered_domain_sld_length, filtered_domain_registered_date, filtered_domain_registered_date_saying, filtered_domain_registered_datetime, filtered_domain_seen_before_times, filtered_domain_ip, filtered_domain_host_addr, filtered_domain_host_name, filtered_domain_host_url, filtered_domain_filters_activated, filtered_domain_seen_by_group, filtered_domain_emailed FROM $t_domains_monitoring_domains_filtered WHERE filtered_id=$filtered_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_filtered_id, $get_current_filtered_domain_id, $get_current_filtered_domain_value, $get_current_filtered_group_id, $get_current_filtered_by_user_id, $get_current_filtered_notes, $get_current_filtered_date_saying, $get_current_filtered_datetime, $get_current_filtered_domain_sld, $get_current_filtered_domain_tld, $get_current_filtered_domain_sld_length, $get_current_filtered_domain_registered_date, $get_current_filtered_domain_registered_date_saying, $get_current_filtered_domain_registered_datetime, $get_current_filtered_domain_seen_before_times, $get_current_filtered_domain_ip, $get_current_filtered_domain_host_addr, $get_current_filtered_domain_host_name, $get_current_filtered_domain_host_url, $get_current_filtered_domain_filters_activated, $get_current_filtered_domain_seen_by_group, $get_current_filtered_domain_emailed) = $row;
	if($get_current_filtered_id == ""){
		echo"current_filtered_id not found";
	}
	else{
		if($process == "1"){
			// Get next ID
			$query = "SELECT filtered_id FROM $t_domains_monitoring_domains_filtered WHERE filtered_id > $get_current_filtered_id LIMIT 0,1";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_next_filtered_id) = $row;



			// Delete
			mysqli_query($link, "DELETE FROM $t_domains_monitoring_domains_filtered WHERE filtered_id=$get_current_filtered_id") or die(mysqli_error($link));
			

			

			$url = "index.php?open=domains_monitoring&page=domains_filtered&editor_language=$editor_language&l=$l&ft=success&fm=filtered_deleted#filtered_id$get_next_filtered_id";
			header("Location: $url");
			exit;
		}
		echo"
		<h1>Remove $get_current_filtered_domain_value</h1>


		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=domains_monitoring&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Domains monitoring</a>
			&gt;
			<a href=\"index.php?open=domains_monitoring&amp;page=domains_filtereds&amp;editor_language=$editor_language&amp;l=$l\">Domains filtered</a>
			&gt;
			<a href=\"index.php?open=domains_monitoring&amp;page=domains_filtered&amp;action=remove&amp;filtered_id=$get_current_filtered_id&amp;editor_language=$editor_language&amp;l=$l\">Remove $get_current_filtered_domain_value</a>
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

		<!-- Delete filtered form -->
			<p>Are you sure you want to delete <b>$get_current_filtered_domain_value</b>?</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=domains_filtered&amp;action=remove&amp;filtered_id=$get_current_filtered_id&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" class=\"btn_danger\">Confirm</a>
			</p>
		<!-- //Delete filtered form -->
		";
	} // filtered found
} // remove
elseif($action == "monitor_domain"){
	if (isset($_GET['filtered_id'])) {
		$filtered_id = $_GET['filtered_id'];
		$filtered_id = stripslashes(strip_tags($filtered_id));
		if(!(is_numeric($filtered_id))){
			echo"filtered_id not numeric";
			die;
		}
	}
	else{
		echo"Missing filtered_id";
		die;
	}
	$filtered_id_mysql = quote_smart($link, $filtered_id);

	

	// Get filtered
	$query = "SELECT filtered_id, filtered_domain_id, filtered_domain_value, filtered_group_id, filtered_by_user_id, filtered_notes, filtered_date_saying, filtered_datetime, filtered_domain_sld, filtered_domain_tld, filtered_domain_sld_length, filtered_domain_registered_date, filtered_domain_registered_date_saying, filtered_domain_registered_datetime, filtered_domain_seen_before_times, filtered_domain_ip, filtered_domain_host_addr, filtered_domain_host_name, filtered_domain_host_url, filtered_domain_filters_activated, filtered_domain_seen_by_group, filtered_domain_emailed FROM $t_domains_monitoring_domains_filtered WHERE filtered_id=$filtered_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_filtered_id, $get_current_filtered_domain_id, $get_current_filtered_domain_value, $get_current_filtered_group_id, $get_current_filtered_by_user_id, $get_current_filtered_notes, $get_current_filtered_date_saying, $get_current_filtered_datetime, $get_current_filtered_domain_sld, $get_current_filtered_domain_tld, $get_current_filtered_domain_sld_length, $get_current_filtered_domain_registered_date, $get_current_filtered_domain_registered_date_saying, $get_current_filtered_domain_registered_datetime, $get_current_filtered_domain_seen_before_times, $get_current_filtered_domain_ip, $get_current_filtered_domain_host_addr, $get_current_filtered_domain_host_name, $get_current_filtered_domain_host_url, $get_current_filtered_domain_filters_activated, $get_current_filtered_domain_seen_by_group, $get_current_filtered_domain_emailed) = $row;
	if($get_current_filtered_id == ""){
		echo"current_filtered_id not found";
	}
	else{
		if($process == "1"){
			// Get next ID
			$query = "SELECT filtered_id FROM $t_domains_monitoring_domains_filtered WHERE filtered_id > $get_current_filtered_id LIMIT 0,1";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_next_filtered_id) = $row;


			
			// Insert it to monitored
			$sql = "INSERT INTO $t_domains_monitoring_domains_monitored
				(monitored_id, monitored_domain_id, monitored_domain_value, monitored_group_id, monitored_by_user_id, 
				monitored_date_saying, monitored_datetime, monitored_domain_sld, monitored_domain_tld, monitored_domain_sld_length, 
				monitored_domain_registered_date, monitored_domain_registered_date_saying, monitored_domain_registered_datetime, monitored_domain_seen_before_times, monitored_domain_ip,
				monitored_domain_host_addr, monitored_domain_filters_activated, monitored_domain_seen_by_group, monitored_domain_emailed) 
				VALUES 
				(NULL, ?, ?, ?, ?, 
				?, ?, ?, ?, ?, 
				?, ?, ?, ?, ?, 
				?,?, 0, 0)";
				$stmt = $link->prepare($sql);
				$stmt->bind_param("ssssssssssssssss", $get_current_filtered_domain_id, $get_current_filtered_domain_value, $get_current_filtered_group_id, $get_current_filtered_by_user_id,
									$get_current_filtered_date_saying, $get_current_filtered_datetime, $get_current_filtered_domain_sld, $get_current_filtered_domain_tld, $get_current_filtered_domain_sld_length, 
									$get_current_filtered_domain_registered_date, $get_current_filtered_domain_registered_date_saying, $get_current_filtered_domain_registered_datetime, $get_current_filtered_domain_seen_before_times, $get_current_filtered_domain_ip,
									$get_current_filtered_domain_host_addr, $get_current_filtered_domain_filters_activated);
				$stmt->execute();
				if ($stmt->errno) {
					$error = $stmt->errno . ' ' . $stmt->error;
					echo "Insert error: " . $error;
					die;
				}
			


			// Delete
			mysqli_query($link, "DELETE FROM $t_domains_monitoring_domains_filtered WHERE filtered_id=$get_current_filtered_id") or die(mysqli_error($link));
			
			$url = "index.php?open=domains_monitoring&page=domains_filtered&editor_language=$editor_language&l=$l&ft=success&fm=domain_moved_to_monitor_list#filtered_id$get_next_filtered_id";
			header("Location: $url");
			exit;
		}
	} // filtered found
} // monitor_domain
elseif($action == "with_checked_filtered"){
	$submit = $_POST['submit'];
	$submit = output_html($submit);
	if($submit == "Remove selected"){
		
		$query = "SELECT filtered_id, filtered_domain_id, filtered_domain_value, filtered_group_id, filtered_by_user_id, filtered_notes, filtered_date_saying, filtered_datetime, filtered_domain_sld, filtered_domain_tld, filtered_domain_sld_length, filtered_domain_registered_date, filtered_domain_registered_date_saying, filtered_domain_registered_datetime, filtered_domain_seen_before_times, filtered_domain_ip, filtered_domain_host_name, filtered_domain_host_url, filtered_domain_filters_activated, filtered_domain_seen_by_group, filtered_domain_emailed FROM $t_domains_monitoring_domains_filtered ORDER BY filtered_id DESC LIMIT 0,1000";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_filtered_id, $get_filtered_domain_id, $get_filtered_domain_value, $get_filtered_group_id, $get_filtered_by_user_id, $get_filtered_notes, $get_filtered_date_saying, $get_filtered_datetime, $get_filtered_domain_sld, $get_filtered_domain_tld, $get_filtered_domain_sld_length, $get_filtered_domain_registered_date, $get_filtered_domain_registered_date_saying, $get_filtered_domain_registered_datetime, $get_filtered_domain_seen_before_times, $get_filtered_domain_ip, $get_filtered_domain_host_name, $get_filtered_domain_host_url, $get_filtered_domain_filters_activated, $get_filtered_domain_seen_by_group, $get_filtered_domain_emailed) = $row;

			if(isset($_POST["inp_filtered_$get_filtered_id"])){
				// Delete
				mysqli_query($link, "DELETE FROM $t_domains_monitoring_domains_filtered WHERE filtered_id=$get_filtered_id") or die(mysqli_error($link));
			}
		} // while


		$url = "index.php?open=domains_monitoring&page=domains_filtered&editor_language=$editor_language&l=$l&ft=success&fm=selected_removed";
		header("Location: $url");
		exit;


	} // remove selected
} // with_checked_filtered
?>