<?php
echo"
";

// Check starts_with_word_ends_with_another_word
$get_current_filtered_id = "";
$query_d = "SELECT filtered_id, filtered_domain_id, filtered_domain_value, filtered_group_id, filtered_by_user_id, filtered_date_saying, filtered_datetime, filtered_domain_sld, filtered_domain_tld, filtered_domain_sld_length, filtered_score, filtered_domain_registered_date, filtered_domain_registered_date_saying, filtered_domain_registered_datetime, filtered_domain_seen_before_times, filtered_domain_ip, filtered_domain_host_name, filtered_domain_host_url, filtered_domain_filters_activated, filtered_domain_seen_by_group, filtered_domain_emailed, filtered_notes FROM $t_domains_monitoring_domains_filtered WHERE filtered_notes='check_if_ends_with_another_word' OR filtered_notes='check_if_starts_with_another_word' ORDER BY filtered_id DESC LIMIT 0,20";
$result_d = mysqli_query($link, $query_d);
while($row_d = mysqli_fetch_row($result_d)) {
	list($get_current_filtered_id, $get_current_filtered_domain_id, $get_current_filtered_domain_value, $get_current_filtered_group_id, $get_current_filtered_by_user_id, $get_current_filtered_date_saying, $get_current_filtered_datetime, $get_current_filtered_domain_sld, $get_current_filtered_domain_tld, $get_current_filtered_domain_sld_length, $get_current_filtered_score, $get_current_filtered_domain_registered_date, $get_current_filtered_domain_registered_date_saying, $get_current_filtered_domain_registered_datetime, $get_current_filtered_domain_seen_before_times, $get_current_filtered_domain_ip, $get_current_filtered_domain_host_name, $get_current_filtered_domain_host_url, $get_current_filtered_domain_filters_activated, $get_current_filtered_domain_seen_by_group, $get_current_filtered_domain_emailed, $get_current_filtered_notes) = $row_d;

	// Headline H3 :: Domain
	echo"
	<h2>$get_current_filtered_domain_value</h2>
	
		<!-- Filtered domain info -->
		<table>
		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span>Filtered ID:</span>
		  </td>
		  <td style=\"padding: 0px 10px 0px 0px;\">
			<span>$get_current_filtered_id</span>
		  </td>

		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span>Domain ID:</span>
		  </td>
		  <td>
			<span>$get_current_filtered_domain_id</span>
		  </td>
		 </tr>

		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span>Group ID:</span>
		  </td>
		  <td style=\"padding: 0px 10px 0px 0px;\">
			<span>$get_current_filtered_group_id</span>
		  </td>

		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span>User ID:</span>
		  </td>
		  <td>
			<span>$get_current_filtered_by_user_id</span>
		  </td>
		 </tr>

		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span>Date:</span>
		  </td>
		  <td style=\"padding: 0px 10px 0px 0px;\">
			<span>$get_current_filtered_date_saying</span>
		  </td>

		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span>SLD:</span>
		  </td>
		  <td>
			<span>$get_current_filtered_domain_sld</span>
		  </td>
		 </tr>

		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span>TLD:</span>
		  </td>
		  <td style=\"padding: 0px 10px 0px 0px;\">
			<span>$get_current_filtered_domain_tld</span>
		  </td>

		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span>SLD enght:</span>
		  </td>
		  <td>
			<span>$get_current_filtered_domain_sld_length</span>
		  </td>
		 </tr>

		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span>Score:</span>
		  </td>
		  <td style=\"padding: 0px 10px 0px 0px;\">
			<span>$get_current_filtered_score</span>
		  </td>

		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span>Domain registered:</span>
		  </td>
		  <td>
			<span>$get_current_filtered_domain_registered_date_saying</span>
		  </td>
		 </tr>


		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span>Seen:</span>
		  </td>
		  <td style=\"padding: 0px 10px 0px 0px;\">
			<span>$get_current_filtered_domain_seen_before_times</span>
		  </td>

		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span>IP:</span>
		  </td>
		  <td>
			<span>$get_current_filtered_domain_ip</span>
		  </td>
		 </tr>


		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span>Host:</span>
		  </td>
		  <td style=\"padding: 0px 10px 0px 0px;\">
			<span>$get_current_filtered_domain_host_name</span>
		  </td>

		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span>Filtered activated:</span>
		  </td>
		  <td>
			<span>$get_current_filtered_domain_filters_activated</span>
		  </td>
		 </tr>

		 <tr>
		  <td style=\"padding: 0px 4px 0px 0px;\">
			<span>Notes:</span>
		  </td>
		  <td style=\"padding: 0px 10px 0px 0px;\">
			<span>$get_current_filtered_notes</span>
		  </td>

		  <td style=\"padding: 0px 4px 0px 0px;\">
		  </td>
		  <td>
		  </td>
		 </tr>

		</table>
	";

	// Get all keywords to check againts
	$check_result = "False"; // Guess
	$print_results = "";
	$query_k = "SELECT keyword_id, keyword_group_id, keyword_user_id, keyword_title, keyword_type, keyword_value, keyword_value_length, keyword_combinations, keyword_domain_tlds FROM $t_domains_monitoring_filters_keywords";
	$result_k = mysqli_query($link, $query_k);
	while($row_k = mysqli_fetch_row($result_k)) {
		list($get_check_keyword_id, $get_check_keyword_group_id, $get_check_keyword_user_id, $get_check_keyword_title, $get_check_keyword_type, $get_check_keyword_value, $get_check_keyword_value_length, $get_check_keyword_combinations, $get_check_keyword_domain_tlds) = $row_k;

		// Headline H4 :: Keyword
		/*
		echo"
		<span>$get_check_keyword_value<br /></span>
		";
		*/
		// Should we check this keyword??
		$check_this_keyword = "1";
		$style = "";
		if($get_check_keyword_domain_tlds == ""){
			$check_this_keyword = "1";
		} // Check all domains
		else{
			// Guess
			$check_this_keyword = "0";
			$check_result = "Skipped";
			$style = "grey";
				
			// Se if domain is part of list
			$keyword_domain_tlds = str_replace(" ", "", $get_current_filtered_domain_tld);
			$tlds_array = explode(",", $keyword_domain_tlds);
			$tlds_array_size = sizeof($tlds_array);
			for($x=0;$x<$tlds_array_size;$x++){
				if($tlds_array[$x] == "$get_current_filtered_domain_tld"){
					$check_this_keyword = "1";
					$check_result = "";
				}
			}
		}

		// Result
		if($check_this_keyword == "1"){

			// Run test
			if($get_current_filtered_notes == "check_if_starts_with_another_word"){
				// Starts with it
				$filtered_domain_sld_substr = substr($get_current_filtered_domain_sld, 0, $get_check_keyword_value_length);

				// echo"$get_current_filtered_domain_sld -&gt; $filtered_domain_sld_substr == $get_check_keyword_value<br />";
				if ($filtered_domain_sld_substr == "$get_check_keyword_value") {
					// echo"$filtered_domain_sld_substr == $get_check_keyword_value<br />";
    					$check_result = "True";
					$inp_filters_activated = $get_current_filtered_domain_filters_activated . ", starts with $get_check_keyword_value";
					$style = "success";
					$inp_score = $get_current_filtered_score + 1;


					$print_results = $print_results ."
					  <tr>
					   <td>
						<span>$get_check_keyword_type</span>
			 		  </td>
					   <td>
						<span>$get_check_keyword_value</span>
					   </td>
					   <td>
						<span>$get_check_keyword_domain_tlds</span>
					   </td>
					   <td>
						<span style=\"color:green;\">$check_result</span>
			 		  </td>
			 		 </tr>
					";
				}


			}
			elseif($get_current_filtered_notes == "check_if_ends_with_another_word"){
				// Ends with it
				// echo"$get_current_filtered_domain_sld, $get_check_keyword_value<br />";
				if(substr_compare($get_current_filtered_domain_sld, $get_check_keyword_value, -strlen($get_check_keyword_value)) === 0){
    					$check_result = "True";
					$inp_filters_activated = $get_current_filtered_domain_filters_activated . ", ends with $get_check_keyword_value";
					$style = "success";
					$inp_score = $get_current_filtered_score + 1;
				}
			}
		} // check this keyword

	} // while keywords (all)


		
	// Update result
	// Update result :: Get newest information
	$query = "SELECT filtered_id, filtered_domain_id, filtered_domain_value, filtered_group_id, filtered_by_user_id, filtered_date_saying, filtered_datetime, filtered_domain_sld, filtered_domain_tld, filtered_domain_sld_length, filtered_score, filtered_domain_registered_date, filtered_domain_registered_date_saying, filtered_domain_registered_datetime, filtered_domain_seen_before_times, filtered_domain_ip, filtered_domain_host_name, filtered_domain_host_url, filtered_domain_filters_activated, filtered_domain_seen_by_group, filtered_domain_emailed, filtered_notes FROM $t_domains_monitoring_domains_filtered WHERE filtered_id=$get_current_filtered_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_filtered_id, $get_current_filtered_domain_id, $get_current_filtered_domain_value, $get_current_filtered_group_id, $get_current_filtered_by_user_id, $get_current_filtered_date_saying, $get_current_filtered_datetime, $get_current_filtered_domain_sld, $get_current_filtered_domain_tld, $get_current_filtered_domain_sld_length, $get_current_filtered_score, $get_current_filtered_domain_registered_date, $get_current_filtered_domain_registered_date_saying, $get_current_filtered_domain_registered_datetime, $get_current_filtered_domain_seen_before_times, $get_current_filtered_domain_ip, $get_current_filtered_domain_host_name, $get_current_filtered_domain_host_url, $get_current_filtered_domain_filters_activated, $get_current_filtered_domain_seen_by_group, $get_current_filtered_domain_emailed, $get_current_filtered_notes) = $row;

	if($get_current_filtered_notes != ""){
		if($check_result == "True"){
			// Domain checked (Update domain)
			echo"<p>Updated result!</p>\n";
			
			$inp_filters_activated_mysql = quote_smart($link, $inp_filters_activated);
			mysqli_query($link, "UPDATE $t_domains_monitoring_domains_filtered SET 
						filtered_score=$inp_score,
						filtered_domain_filters_activated=$inp_filters_activated_mysql,
						filtered_notes=''
						 WHERE filtered_id=$get_current_filtered_id") or die(mysql_error($link));
		}
		else{
			// Delete it from filter list
			echo"<p>Delete filtered from list....</p>";
			
			mysqli_query($link, "DELETE FROM $t_domains_monitoring_domains_filtered WHERE filtered_id=$get_current_filtered_id") or die(mysql_error($link));
		}
	}
		
	// Print result of keyword check
	echo"
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th>
			<span>Type</span>
		   </th>
		   <th>
			<span>Value</span>
		   </th>
		   <th>
			<span>Tlds</span>
		   </th>
		   <th>
			<span>Result</span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>
		$print_results 
		 </tbody>
		</table>
	";
} // while filtered


// Header
if($get_current_filtered_id != ""){

	// Count if we need to find ip adresses
	$query = "SELECT count(filtered_id) FROM $t_domains_monitoring_domains_filtered WHERE filtered_domain_ip=''";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($count_filtered_domain_ip) = $row;

	if($count_filtered_domain_ip > 0){
		echo"
		<meta http-equiv=\"refresh\" content=\"0;url=check_domains_script.php?inc=5_find_ip_addresses&amp;filtered_id=$get_current_filtered_id&amp;start_time=$start_time\" />
		";
	}
	else{
		echo"
		<meta http-equiv=\"refresh\" content=\"0;url=check_domains_script.php?inc=1_check_ip_and_host&amp;filtered_id=$get_current_filtered_id&amp;start_time=$start_time\" />
		";
	}
}
else{
	// <p>All done...</p>
	echo"
	<meta http-equiv=\"refresh\" content=\"0;url=check_domains_script.php?inc=5_find_ip_addresses&amp;filtered_id=$get_current_filtered_id&amp;start_time=$start_time\" />
	";
	
}

?>