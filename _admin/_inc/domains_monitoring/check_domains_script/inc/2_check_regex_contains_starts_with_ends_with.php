<?php
/* Functions */
function get_domain($host){
	$host_array = explode(".", $host);
	$host_array_size = sizeof($host_array);
	if($host_array_size < 2){
		$domain = "$host";
	}
	else{
		$sld = $host_array[$host_array_size-2]; // one
		$tld = $host_array[$host_array_size-1]; // com
		$domain = "$sld.$tld";
	}
	return $domain;
}

echo"
";

// Check other
$get_current_domain_id = "";
$query_d = "SELECT domain_id, domain_value, domain_sld, domain_tld, domain_sld_length, domain_registered_date, domain_registered_date_saying, domain_registered_datetime, domain_seen_before_times, domain_ip, domain_host_addr, domain_host_name, domain_host_url, domain_filters_activated FROM $t_domains_monitoring_domains_index WHERE domain_checked_other_by_script=0 LIMIT 0,20";
$result_d = mysqli_query($link, $query_d);
while($row_d = mysqli_fetch_row($result_d)) {
	list($get_current_domain_id, $get_current_domain_value, $get_current_domain_sld, $get_current_domain_tld, $get_current_domain_sld_length, $get_current_domain_registered_date, $get_current_domain_registered_date_saying, $get_current_domain_registered_datetime, $get_current_domain_seen_before_times, $get_current_domain_ip, $get_current_domain_host_addr, $get_current_domain_host_name, $get_current_domain_host_url, $get_current_domain_filters_activated) = $row_d;

	if($get_current_domain_id == ""){
		echo"<p>Finished checking all domains:-)</p>";
	}
	else{
		// Domain checked (Update domain)
		mysqli_query($link, "UPDATE $t_domains_monitoring_domains_index SET domain_checked_other_by_script=1 WHERE domain_id=$get_current_domain_id") or die(mysql_error($link));

		// Headline H2 :: Domain
		echo"
		<h2>$get_current_domain_value</h2>
		";

		// Get all keywords to check againts
		$printed_header = 0;
		$inp_domains_filtered = 0; // How many domains we have found that are inserted into filter
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
				$keyword_domain_tlds = str_replace(" ", "", $get_check_keyword_domain_tlds);
				$tlds_array = explode(",", $keyword_domain_tlds);
				$tlds_array_size = sizeof($tlds_array);
				for($x=0;$x<$tlds_array_size;$x++){
					if($tlds_array[$x] == "$get_current_domain_tld"){
						$check_this_keyword = "1";
						$check_result = "";
					}
				}
			}

			// Result
			if($check_this_keyword == "1"){
				// Guess
				$check_result = "False";
				$inp_filters_activated = "$get_current_domain_filters_activated";
				$inp_score = 0;
				$inp_notes = "";
	
				// Run test
				if($get_check_keyword_type == "regex"){
					// Regex
					if(preg_match($get_check_keyword_value, $get_current_domain_sld)){
						$check_result = "True";
						$inp_filters_activated = "Regex $get_check_keyword_title";
						$style = "success";
						$inp_score = 5;
					}					
				}
				elseif($get_check_keyword_type == "contains"){
					// Contains
					if (strpos($get_current_domain_sld, $get_check_keyword_value) !== false) {
						$check_result = "True";
						if($inp_filters_activated == ""){
							$inp_filters_activated = "Contains $get_check_keyword_value";
						}
						else{
							$inp_filters_activated = $inp_filters_activated . ", contains $get_check_keyword_value";
						}
						$style = "success";
						$inp_score = 5;
					}
			
				}
				elseif($get_check_keyword_type == "exact"){
					// Exact
					if ($get_current_domain_sld == "$get_check_keyword_value") {
						$check_result = "True";
						if($inp_filters_activated == ""){
							$inp_filters_activated = "Exact $get_check_keyword_value";
						}
						else{
							$inp_filters_activated = $inp_filters_activated . ", exact $get_check_keyword_value";
						}
						$style = "success";
						$inp_score = 5;
					}
				}
				elseif($get_check_keyword_type == "starts_with"){
					// Starts with it
					if (strpos($get_current_domain_sld, $get_check_keyword_value) === 0) {
	    					$check_result = "True";
						if($inp_filters_activated == ""){
							$inp_filters_activated = "Starts with $get_check_keyword_value";
						}
						else{
							$inp_filters_activated = $inp_filters_activated . ", starts with $get_check_keyword_value";
						}
						$style = "success";
						$inp_score = 5;
					}
				}
				elseif($get_check_keyword_type == "ends_with"){
					// Ends with it
					if(substr_compare($get_current_domain_sld, $get_check_keyword_value, -strlen($get_check_keyword_value)) === 0){
    						$check_result = "True";
						if($inp_filters_activated == ""){
							$inp_filters_activated = "Ends with $get_check_keyword_value";
						}
						else{
							$inp_filters_activated = $inp_filters_activated . ", ends with $get_check_keyword_value";
						}
						$style = "success";
						$inp_score = 5;
					}
				}
				else{
					// Starts with word, ends with another word
					// Exact
					if ($get_current_domain_sld == "$get_check_keyword_value") {
						$check_result = "True";
						$inp_filters_activated = "Exact $get_check_keyword_value";
						$style = "success";
						$inp_score = 5;
					}
				} 
	
				// If result is true then update and insert to watch list
				if($check_result == "True"){
					// Domain checked (Update domain)
					mysqli_query($link, "UPDATE $t_domains_monitoring_domains_index SET domain_filters_activated=1, domain_checked_starts_with_ends_with_by_script=1 WHERE domain_id=$get_current_domain_id") or die(mysql_error($link));
					
					// Values check
					if($get_current_domain_ip == ""){
						$get_current_domain_ip = "0";
					}
					if($get_current_domain_host_name == ""){
						$get_current_domain_host_name = "0";
					}
					if($get_current_domain_host_url == ""){
						$get_current_domain_host_url = "0";
					}
	
					// Check duplicates
					$get_filtered_id = "";
					$domain_value_mysql = quote_smart($link, $get_current_domain_value);
					if($get_check_keyword_group_id != "0"){
						$query = "SELECT filtered_id FROM $t_domains_monitoring_domains_filtered WHERE filtered_domain_value=$domain_value_mysql AND filtered_group_id=$get_check_keyword_group_id";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_filtered_id) = $row;
					}
					else{
						$query = "SELECT filtered_id FROM $t_domains_monitoring_domains_filtered WHERE filtered_domain_value=$domain_value_mysql AND filtered_by_user_id=$get_check_keyword_user_id";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_filtered_id) = $row;
					}
	
					if($get_filtered_id == ""){
						// Insert into filtered list
						$sql = "INSERT INTO $t_domains_monitoring_domains_filtered
						(filtered_id, filtered_domain_id, filtered_domain_value, filtered_group_id, filtered_by_user_id, 
						filtered_date_saying, filtered_datetime, filtered_domain_sld, filtered_domain_tld, filtered_domain_sld_length, 
						filtered_score, filtered_domain_registered_date, filtered_domain_registered_date_saying, filtered_domain_registered_datetime, filtered_domain_seen_before_times,
						filtered_domain_ip, filtered_domain_host_addr, filtered_domain_host_name, filtered_domain_host_url, filtered_domain_filters_activated, 
						filtered_domain_seen_by_group, filtered_domain_emailed, filtered_notes) 
						VALUES 
						(NULL, ?, ?, ?, ?, 
						?, ?, ?, ?, ?, 
						?, ?, ?, ?, ?, 
						?, ?, ?, ?, ?, 
						0, 0, ?)";
						$stmt = $link->prepare($sql);
						$stmt->bind_param("ssssssssssssssssssss", $get_current_domain_id, $get_current_domain_value, $get_check_keyword_group_id, $get_check_keyword_user_id,
									$date_saying, $datetime, $get_current_domain_sld, $get_current_domain_tld, $get_current_domain_sld_length, 
									$inp_score, $get_current_domain_registered_date, $get_current_domain_registered_date_saying, $get_current_domain_registered_datetime, $get_current_domain_seen_before_times, 
									$get_current_domain_ip, $get_current_domain_host_addr, $get_current_domain_host_name, $get_current_domain_host_url, $inp_filters_activated, 
									$inp_notes);
						$stmt->execute();
						if ($stmt->errno) {
							$error = $stmt->errno . ' ' . $stmt->error;
							echo $error;
							die;
						}
						// Increment
						$inp_domains_filtered=$inp_domains_filtered+1;
					}
					else{
						// Update watch list
						mysqli_query($link, "UPDATE $t_domains_monitoring_domains_filtered SET filtered_domain_seen_by_group=0, filtered_domain_emailed=0 WHERE filtered_id=$get_filtered_id") or die(mysql_error($link));
					}
				} // if($check_result == "True"){
				$keyword_number_out = $keyword_number_out+1;
			} // while keywords (all)

			if($check_result == "True"){
				if($printed_header == "0"){
						// Print header
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
						";
						$printed_header = "1";
				} // printed header
					echo"
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
						<span"; if($check_result == "False"){ echo" style=\"color:red;\""; } 
						elseif($check_result == "True"){ echo" style=\"color:green;\""; } 
						else{ echo" style=\"color:grey;\""; } 
						echo">$check_result</span>
			 		  </td>
			 		 </tr>
					";
			} // $check_result == "True"

		} // while keyword
		if($printed_header > 0){
				echo"
				 </tbody>
				</table>
				";
		}

		$domain_number_out = $domain_number_out+1;
		$printed_header =0;
	} // domains not checked
} // while domains

// Header
if($get_current_domain_id != ""){
	echo"
	<meta http-equiv=\"refresh\" content=\"0;url=check_domains_script.php?inc=3_check_starts_with_word_ends_with_another_word&amp;domain_id=$get_current_domain_id&amp;start_time=$start_time\" />
	";
}
else{
	echo"
	<meta http-equiv=\"refresh\" content=\"0;url=../../../index.php?open=domains_monitoring&page=domains_filtered&ft=success&fm=finished&amp;start_time=$start_time\" />
	";
}

?>