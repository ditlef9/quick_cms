<?php
/**
*
* File: _admin/_inc/domains_monitoring/domains.php
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

if($action == ""){
	echo"
	<h1>Domains</h1>


	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=domains_monitoring&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Domains monitoring</a>
		&gt;
		<a href=\"index.php?open=domains_monitoring&amp;page=domains&amp;editor_language=$editor_language&amp;l=$l\">Domains</a>
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
	<a href=\"index.php?open=domains_monitoring&amp;page=domains&amp;action=truncate&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Truncate</a>
	</p>

	<!-- Domains list -->
		
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
			<span>Seen times</span>
		   </th>
		   <th scope=\"col\">
			<span>Actions</span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>";

		$query = "SELECT domain_id, domain_value, domain_tld, domain_registered_date_saying, domain_seen_before_times FROM $t_domains_monitoring_domains_index ORDER BY domain_id DESC LIMIT 0,1000";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_domain_id, $get_domain_value, $get_domain_tld, $get_domain_registered_date_saying, $get_domain_seen_before_times) = $row;

			echo"
			 <tr>
			  <td>
				<span>
				<a href=\"https://$get_domain_value\">$get_domain_value</a>
				</span>
			  </td>
			  <td>
				<span>$get_domain_tld</span>
			  </td>
			  <td>
				<span>$get_domain_registered_date_saying</span>
			  </td>
			  <td>
				<span>$get_domain_seen_before_times
				</span>
			  </td>
			  <td>
				<span>
				<a href=\"index.php?open=domains_monitoring&amp;page=domains&amp;action=edit_domain&amp;domain_id=$get_domain_id&amp;editor_language=no&amp;l=$l\">Edit</a>
				</span>
			  </td>
			 </tr>
			";
		} // while

		echo"
		 </tbody>
		</table>

	<!-- //Domains list -->
	";
}
elseif($action == "truncate"){
	if($process == "1"){
		mysqli_query($link, "TRUNCATE $t_domains_monitoring_domains_index") or die(mysqli_error($link));
		mysqli_query($link, "TRUNCATE $t_domains_monitoring_domains_tld_count") or die(mysqli_error($link)); // Count
		
		$url = "index.php?open=domains_monitoring&page=domains&editor_language=$editor_language&l=$l&ft=success&fm=domains_truncated";
		header("Location: $url");
		exit;
	}
	echo"
	<h1>Domains</h1>


	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=domains_monitoring&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Domains monitoring</a>
		&gt;
		<a href=\"index.php?open=domains_monitoring&amp;page=domains&amp;editor_language=$editor_language&amp;l=$l\">Domains</a>
		&gt;
		<a href=\"index.php?open=domains_monitoring&amp;page=domains&amp;action=truncate&amp;editor_language=$editor_language&amp;l=$l\">Truncate</a>
		</p>
	<!-- //Where am I? -->


	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		else{
			$fm = ucfirst($ft);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->


	<p>
	Are you sure you want to truncate the domains table?
	</p>

	<p>
	<a href=\"index.php?open=domains_monitoring&amp;page=domains&amp;action=truncate&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_danger\">Confirm</a>
	</p>
	";

} // action == truncate
elseif($action == "edit_domain"){
	if (isset($_GET['domain_id'])) {
		$domain_id = $_GET['domain_id'];
		$domain_id = stripslashes(strip_tags($domain_id));
		if(!(is_numeric($domain_id))){
			echo"Domain id not numeric";
			die;
		}
	}
	else{
		echo"Domain id";
		die;
	}
	$domain_id_mysql = quote_smart($link, $domain_id);

	// Get domain
	$query = "SELECT domain_id, domain_value, domain_sld, domain_tld, domain_sld_length, domain_registered_date, domain_registered_date_saying, domain_registered_datetime, domain_seen_before_times, domain_checked_other_by_script, domain_checked_starts_with_ends_with_by_script, domain_ip, domain_host_name, domain_host_url, domain_filters_activated FROM $t_domains_monitoring_domains_index WHERE domain_id=$domain_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_domain_id, $get_current_domain_value, $get_current_domain_sld, $get_current_domain_tld, $get_current_domain_sld_length, $get_current_domain_registered_date, $get_current_domain_registered_date_saying, $get_current_domain_registered_datetime, $get_current_domain_seen_before_times, $get_current_domain_checked_other_by_script, $get_current_domain_checked_starts_with_ends_with_by_script, $get_current_domain_ip, $get_current_domain_host_name, $get_current_domain_host_url, $get_current_domain_filters_activated) = $row;
	if($get_current_domain_id == ""){
		echo"Domain not found";
	}
	else{
		if($process == "1"){
			$inp_checked_other_by_script = $_POST['inp_checked_other_by_script'];
			$inp_checked_other_by_script = output_html($inp_checked_other_by_script);

			$inp_checked_starts_with_ends_with_by_script = $_POST['inp_checked_starts_with_ends_with_by_script'];
			$inp_checked_starts_with_ends_with_by_script = output_html($inp_checked_starts_with_ends_with_by_script);

			$sql = "UPDATE $t_domains_monitoring_domains_index SET 
				domain_checked_other_by_script=?, 
				domain_checked_starts_with_ends_with_by_script=? WHERE domain_id=$get_current_domain_id";
			$stmt = $link->prepare($sql);
			$stmt->bind_param("ss", $inp_checked_other_by_script, $inp_checked_starts_with_ends_with_by_script);
			$stmt->execute();
			if ($stmt->errno) {
				echo "STMT_error: " . $stmt->error; die;
			}

			$url = "index.php?open=domains_monitoring&page=domains&action=edit_domain&domain_id=$get_current_domain_id&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;
		}
		echo"
		<h1>Edit $get_current_domain_value</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=domains_monitoring&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Domains monitoring</a>
			&gt;
			<a href=\"index.php?open=domains_monitoring&amp;page=domains&amp;editor_language=$editor_language&amp;l=$l\">Domains</a>
			&gt;
			<a href=\"index.php?open=domains_monitoring&amp;page=domains&amp;action=edit_domain&amp;domain_id=$get_current_domain_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_domain_value</a>
			</p>
		<!-- //Where am I? -->


		<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($ft);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->


		<!-- Edit domain form -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
			</script>
			
			<form method=\"post\" action=\"index.php?open=domains_monitoring&amp;page=domains&amp;action=edit_domain&amp;domain_id=$get_current_domain_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>Checked other by script:</b><br />
			<input type=\"radio\" name=\"inp_checked_other_by_script\" value=\"1\""; if($get_current_domain_checked_other_by_script == "1"){ echo" checked=\"checked\""; } echo" /> Yes
			<input type=\"radio\" name=\"inp_checked_other_by_script\" value=\"0\""; if($get_current_domain_checked_other_by_script == "0"){ echo" checked=\"checked\""; } echo" /> No
			</p>

			<p><b>Checked starts with, ends with by script:</b><br />
			<input type=\"radio\" name=\"inp_checked_starts_with_ends_with_by_script\" value=\"1\""; if($get_current_domain_checked_starts_with_ends_with_by_script == "1"){ echo" checked=\"checked\""; } echo" /> Yes
			<input type=\"radio\" name=\"inp_checked_starts_with_ends_with_by_script\" value=\"0\""; if($get_current_domain_checked_starts_with_ends_with_by_script == "0"){ echo" checked=\"checked\""; } echo" /> No
			</p>


			<p>
			<input type=\"submit\" value=\"Save changes\" class=\"btn_default\" />
			</p>

			</form>
		
		<!-- //Edit domain form -->
		";
	} // domain
} // action == edit_domain
?>