<?php
set_time_limit(0);

/**
*
* File: _admin/_inc/domains_monitoring/insert_domains.php
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
function fix_utf($value){
	$value = str_replace("Ã¸", "�", $value);
	$value = str_replace("Ã¥", "�", $value);

        return $value;
}
function fix_local($value){
	$value = htmlentities($value);

        return $value;
}


include("_includes/delete_old_domains.php");

/*- Tables ---------------------------------------------------------------------------- */
$t_domains_monitoring_liquidbase		= $mysqlPrefixSav . "domains_monitoring_liquidbase";
$t_domains_monitoring_domains_index		= $mysqlPrefixSav . "domains_monitoring_domains_index";
$t_domains_monitoring_domains_tld_count		= $mysqlPrefixSav . "domains_monitoring_domains_tld_count";


/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['format'])) {
	$format = $_GET['format'];
	$format = strip_tags(stripslashes($format));
}
else{
	$format = "";
}
if(isset($_GET['mode'])) {
	$mode = $_GET['mode'];
	$mode = strip_tags(stripslashes($mode));
}
else{
	$mode = "";
}

if($format == ""){
	if($process == "1"){
		$date = date("Y-m-d");
		if($mode == ""){
			
			$inp_domains = $_POST['inp_domains'];
			$inp_domains = output_html($inp_domains);

			$inp_date = $_POST['inp_date'];
			$inp_date = output_html($inp_date);


			$file = "insert_domains_format_space_" . $inp_date . ".txt";
			if(!(is_dir("../_cache"))){
				mkdir("../_cache");

				$fp = fopen("../_cache/index.html", 'w');
				fwrite($fp, "403 server error");
				fclose($fp);
			}

			if(file_exists("../_cache/$file")){
				unlink("../_cache/$file");
			}

			$fp = fopen("../_cache/$file", 'w');
			fwrite($fp, $inp_domains);
			fclose($fp);

			// Move to read from file
			$url = "index.php?open=domains_monitoring&page=insert_domains&mode=read_file&inp_date=$inp_date&start=0&editor_language=$editor_language&l=$l&process=1";
			echo"<meta http-equiv=\"refresh\" content=\"1; url=$url\" />";
			// header("Location: $url");
			exit;
			
		} // mode == ""
		elseif($mode == "read_file"){
			$inp_date = $_GET['inp_date'];
			$inp_date = output_html($inp_date);

			$start = $_GET['start'];
			$start = output_html($start);

			
			$file = "insert_domains_format_space_" . $inp_date . ".txt";
			if(file_exists("../_cache/$file")){
				// Read file
				$myfile = fopen("../_cache/$file", "r") or die("Unable to open file!");
				$data = fread($myfile,filesize("../_cache/$file"));
				fclose($myfile);
				
				// Vars
				$hour_minute_second = date("H:i:s");
				
				// Loop
				$domains = explode(" ", $data);
				$domains_length = sizeof($domains);
				
				// Start stop
				$stop = $start+1000;
				if($stop > $domains_length){
					$stop = "$domains_length";
				}

				$percentage = round(($start/$domains_length)*100);
				echo"
				<table>
				 <tr>
				  <td>
					<img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" />
				  </td>
				  <td>
					<p>
					Domains: $domains_length<br />
					Now at: $start<br />
					Percentage done = $percentage %			
					</p>
				  </td>
				 </tr>
				</table>
				";

				$domains_inserted_counter = 0;
				for($x=$start;$x<$stop;$x++){
					
					$domain = "$domains[$x]";
					$length = strlen($domain);

					$inp_value = output_html($domain);
					$inp_value_mysql = quote_smart($link, $inp_value);

					$value_arr = explode(".", $inp_value);

					$inp_sld = $value_arr[0];
					$inp_sld_mysql = quote_smart($link, $inp_sld);

					$inp_tld = $value_arr[1];
					$inp_tld_mysql = quote_smart($link, $inp_tld);

					$inp_sld_len = strlen($inp_sld);
					$inp_sld_len_mysql = quote_smart($link, $inp_sld_len);

					$inp_registered_date_mysql = quote_smart($link, $inp_date);

					// Date saying
					$year = substr($inp_date, 0, 4);
					$month = substr($inp_date, 5, 2);
					$day = substr($inp_date, 8, 2);
					$month_saying = "";
					if($month == "01"){
						$month_saying = "Jan";
					}
					elseif($month == "02"){
						$month_saying = "Feb";
					}
					elseif($month == "03"){
						$month_saying = "Mar";
					}
					elseif($month == "04"){
						$month_saying = "Apr";
					}
					elseif($month == "05"){
						$month_saying = "May";
					}
					elseif($month == "06"){
						$month_saying = "Jun";
					}
					elseif($month == "07"){
						$month_saying = "Jul";
					}
					elseif($month == "08"){
						$month_saying = "Aug";
					}
					elseif($month == "09"){
						$month_saying = "Sep";
					}
					elseif($month == "10"){
						$month_saying = "Oct";
					}
					elseif($month == "11"){
						$month_saying = "Nov";
					}
					elseif($month == "12"){
						$month_saying = "Dec";
					}
					$inp_registered_date_saying = "$day $month_saying $year";
					$inp_registered_date_saying = output_html($inp_registered_date_saying);
					$inp_registered_date_saying_mysql = quote_smart($link, $inp_registered_date_saying);
			
					// Datetime
					$inp_registered_datetime = "$inp_date $hour_minute_second";
					$inp_registered_datetime = output_html($inp_registered_datetime);
					$inp_registered_datetime_mysql = quote_smart($link, $inp_registered_datetime);

					// Check duplicates
					$query = "SELECT domain_id FROM $t_domains_monitoring_domains_index WHERE domain_value=$inp_value_mysql AND domain_registered_date=$inp_registered_date_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_domain_id) = $row;
					if($get_domain_id == ""){
						// Insert new domain
						$tld_len = strlen($inp_tld);
						if($tld_len < 20){

							$sql = "INSERT INTO $t_domains_monitoring_domains_index
							(domain_id, domain_value, domain_sld, domain_tld, domain_sld_length,
							domain_registered_date, domain_registered_date_saying, domain_registered_datetime, domain_created_date, domain_seen_before_times, 
							domain_checked_ip_by_script, domain_checked_other_by_script, domain_checked_starts_with_ends_with_by_script, domain_ip, domain_host_addr, 
							domain_host_name, domain_host_url) 
							VALUES 
							(NULL, ?, ?, ?, ?,
							?, ?, ?, ?, 0,
							0, 0, 0, '', '', '',
							'')";
							$stmt = $link->prepare($sql);
							$stmt->bind_param("ssssssss", $inp_value, $inp_sld, $inp_tld, $inp_sld_len,
										$inp_date, $inp_registered_date_saying, $inp_registered_datetime, $date);
							$stmt->execute();
							if ($stmt->errno) {
								echo "(1) Insert new domain db failure " . $stmt->error; die;
							}
							// echo"$x: $domain<br />\n";

						} // tld length less than 20
					} // duplicates
					else{
						// Time seen before?
						$query = "SELECT count(domain_id) FROM $t_domains_monitoring_domains_index WHERE domain_value=$inp_value_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($inp_seen_before_times) = $row;

						// Insert domain seen before
						$sql = "INSERT INTO $t_domains_monitoring_domains_index
						(domain_id, domain_value, domain_sld, domain_tld, domain_sld_length, 
						domain_registered_date, domain_registered_date_saying, domain_registered_datetime, domain_created_date, domain_seen_before_times, 
						domain_checked_ip_by_script, domain_checked_other_by_script,  domain_checked_starts_with_ends_with_by_script, domain_ip, domain_host_addr,
						domain_host_name, domain_host_url) 
						VALUES 
						(NULL, ?, ?, ?, ?, 
						?, ?, ?, ?, ?, 
						0, 0, 0, '', '',
						'', '')";
						$stmt = $link->prepare($sql);
						$stmt->bind_param("sssssssss", $inp_value, $inp_sld, $inp_tld, $inp_sld_len, 
										$inp_date, $inp_registered_date_saying, $inp_registered_datetime, $date, $inp_seen_before_times);
						$stmt->execute();
						if ($stmt->errno) {
							echo "Insert domain seen before db failure " . $stmt->error; die;
						}
					} // is duplicate
					$domains_inserted_counter++;
				} // for


				// Move to read from file
				if($domains_inserted_counter == "0"){
					$url = "index.php?open=domains_monitoring&page=check_domains&editor_language=$editor_language&l=$l&process=1";
					echo"<meta http-equiv=\"refresh\" content=\"0; url=$url\" />";
					exit;
				}
				else{
					$new_start = $start+1000;
					$url = "index.php?open=domains_monitoring&page=insert_domains&mode=read_file&inp_date=$inp_date&start=$new_start&editor_language=$editor_language&l=$l&process=1";
					echo"<meta http-equiv=\"refresh\" content=\"0; url=$url\" />";
					exit;
				}


			} // file exists
			else{
				echo"complted readled";
			}

		} // mode == read_file


		

	} // process == 1
	echo"
	<h1>Insert domains</h1>


	<!-- Where am I? -->
	<p><b>You are here:</b><br />
	<a href=\"index.php?open=domains_monitoring&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Domains monitoring</a>
	&gt;
	<a href=\"index.php?open=domains_monitoring&amp;page=insert_domains&amp;editor_language=$editor_language&amp;l=$l\">Insert domains</a>
	</p>
	<!-- //Where am I? -->


	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "domains_inserted"){
		}
		else{
			$fm = ucfirst($fm);
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
	}
	echo"	
	<!-- //Feedback -->

	<!-- Formats -->
		";
		include("_inc/domains_monitoring/insert_domains_format_navigation.php");
		echo"
	<!-- //Formats -->

	<!-- Form -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_domains\"]').focus();
		});
		</script>
			
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

		<p><b>Domains:</b><br />
		<span>From <a href=\"https://whoisdatabasedownload.com/newly-registered-domains\">whoisdatabasedownload.com</a><br />
		Format: Domain space Domain space Domain etc</span>
		<br />		
		<textarea name=\"inp_domains\" rows=\"10\" cols=\"50\" style=\"width: 100%;\"></textarea>
		</p>

		<p><b>Date:</b><br />
		<input type=\"date\" name=\"inp_date\" value=\""; echo date("Y-m-d"); echo"\" />
		</p>

		<p>
		<input type=\"submit\" value=\"Save\" class=\"btn_default\" />
		</p>
	
		</form>
	<!-- //Form -->
	";
}
elseif($format == "number_domain_length_idn_date"){
	if($process == "1"){
		$date = date("Y-m-d");
		$date_saying = date("j M Y");
		$hour_minute_second = date("H:i:s");

		$inp_domains = $_POST['inp_domains'];
		$lines = explode("\n", $inp_domains);
		$lines_length = sizeof($lines);
		// echo"Size=$lines_length<br />";
		for($x=0;$x<$lines_length;$x++){
			
			$temp = explode("	", $lines[$x]);
			if(isset($temp[4])){
				$no = $temp[0];
				$domain = $temp[1];
				$length = $temp[2];
				$idn = $temp[3];
				$date = $temp[4];

				$inp_value = output_html($domain);
				$inp_value_mysql = quote_smart($link, $inp_value);

				$value_arr = explode(".", $inp_value);

				if(isset($value_arr[0]) && isset($value_arr[1])){
	
					$inp_sld = $value_arr[0];
					$inp_sld_mysql = quote_smart($link, $inp_sld);

					$inp_tld = $value_arr[1];
					$inp_tld_mysql = quote_smart($link, $inp_tld);

					$inp_sld_len = strlen($inp_sld);
					$inp_sld_len_mysql = quote_smart($link, $inp_sld_len);

					$inp_registered_date = output_html($date);
					$inp_registered_date_mysql = quote_smart($link, $inp_registered_date);

					// Date saying
					$year = substr($inp_registered_date, 0, 4);
					$month = substr($inp_registered_date, 5, 2);
					$day = substr($inp_registered_date, 8, 2);
					$month_saying = "";
					if($month == "01"){
						$month_saying = "Jan";
					}
					elseif($month == "02"){
						$month_saying = "Feb";
					}
					elseif($month == "03"){
						$month_saying = "Mar";
					}
					elseif($month == "04"){
						$month_saying = "Apr";
					}
					elseif($month == "05"){
						$month_saying = "May";
					}
					elseif($month == "06"){
						$month_saying = "Jun";
					}
					elseif($month == "07"){
						$month_saying = "Jul";
					}
					elseif($month == "08"){
						$month_saying = "Aug";
					}
					elseif($month == "09"){
						$month_saying = "Sep";
					}
					elseif($month == "10"){
						$month_saying = "Oct";
					}
					elseif($month == "11"){
						$month_saying = "Nov";
					}
					elseif($month == "12"){
						$month_saying = "Dec";
					}
					$inp_registered_date_saying = "$day $month_saying $year";
					$inp_registered_date_saying = output_html($inp_registered_date_saying);
					$inp_registered_date_saying_mysql = quote_smart($link, $inp_registered_date_saying);
			
					// Datetime
					$inp_registered_datetime = "$inp_registered_date $hour_minute_second";
					$inp_registered_datetime = output_html($inp_registered_datetime);
					$inp_registered_datetime_mysql = quote_smart($link, $inp_registered_datetime);

					// Check duplicates
					$query = "SELECT domain_id FROM $t_domains_monitoring_domains_index WHERE domain_value=$inp_value_mysql AND domain_registered_date=$inp_registered_date_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_domain_id) = $row;
					if($get_domain_id == ""){


						// Insert new domain
						$sql = "INSERT INTO $t_domains_monitoring_domains_index
						(domain_id, domain_value, domain_sld, domain_tld, domain_sld_length, 
						domain_registered_date, domain_registered_date_saying, domain_registered_datetime, domain_created_date, domain_seen_before_times, domain_checked_ip_by_script, 
						domain_checked_other_by_script, domain_checked_starts_with_ends_with_by_script, domain_ip, domain_host_addr, domain_host_name, 
							domain_host_url) 
						VALUES 
						(NULL, ?, ?, ?, ?, 
						?, ?, ?,?, 0, 0,  0, 
						0, '', '', '', '')";
						$stmt = $link->prepare($sql);
						$stmt->bind_param("ssssssss", $inp_value, $inp_sld, $inp_tld, $inp_sld_len, 
						$inp_registered_date, $inp_registered_date_saying, $inp_registered_datetime, $date);
						$stmt->execute();
						if ($stmt->errno) {
							echo "(2) Insert new domain db failure " . $stmt->error; die;
						}
					}
					else{
						// Check number of times seen
						$query = "SELECT count(domain_id) FROM $t_domains_monitoring_domains_index WHERE domain_value=$inp_value_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($inp_seen_before_times) = $row;


						// Insert domain seen before
						$sql = "INSERT INTO $t_domains_monitoring_domains_index
						(domain_id, domain_value, domain_sld, domain_tld, domain_sld_length, 
						domain_registered_date, domain_registered_date_saying, domain_registered_datetime, domain_created_date, domain_seen_before_times, 
						domain_checked_ip_by_script, domain_checked_other_by_script, domain_checked_starts_with_ends_with_by_script, domain_ip, domain_host_addr, domain_host_name, 
							domain_host_url) 
						VALUES 
						(NULL, ?, ?, ?, ?, 
						?, ?, ?, ?, ?, 
						0, 0, 0, '', '', '', '')";
						$stmt = $link->prepare($sql);
						$stmt->bind_param("sssssssss", $inp_value, $inp_sld, $inp_tld, $inp_sld_len, 
								$inp_registered_date, $inp_registered_date_saying, $inp_registered_datetime, $date, $inp_seen_before_times);
						$stmt->execute();
						if ($stmt->errno) {
							echo "Insert domain seen before db failure " . $stmt->error; die;
						}



					/*
					echo"No: $no<br />
					Domain: $domain<br />
					length: $length<br />
					Idn: $idn<br />
					Date: $date<br />";
					*/
					} // duplicates
				} // if isset domain and tld
			} // if isset date
		} // for

		// Count domains :: Find all types of tlds
		$date = date("Y-m-d");
		$date_saying = date("j M Y");
		$query_tlds = "SELECT domain_tld FROM $t_domains_monitoring_domains_index GROUP BY domain_tld";
		$result_tlds = mysqli_query($link, $query_tlds);
		while($row_tlds = mysqli_fetch_row($result_tlds)) {
			list($get_domain_tld) = $row_tlds;

			// Count todays
			$inp_domain_tld_mysql = quote_smart($link, $get_domain_tld);
			$query = "SELECT count(domain_id) FROM $t_domains_monitoring_domains_index WHERE domain_tld=$inp_domain_tld_mysql AND domain_created_date='$date'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_count_domains_for_tld) = $row;


			// Check if exists, if not create it
			$query = "SELECT count_id FROM $t_domains_monitoring_domains_tld_count WHERE count_date='$date' AND count_tld=$inp_domain_tld_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_count_id) = $row;
			if($get_count_id == ""){
				// Insert
				mysqli_query($link, "INSERT INTO $t_domains_monitoring_domains_tld_count 
				(count_id, count_date, count_date_saying, count_tld, count_domains) 
				VALUES 
				(NULL, '$date', '$date_saying', $inp_domain_tld_mysql, $get_count_domains_for_tld)")
				or die(mysqli_error($link));
			}
			else{
				// Update
				mysqli_query($link, "UPDATE $t_domains_monitoring_domains_tld_count SET
						count_domains=$get_count_domains_for_tld
						WHERE count_id=$get_count_id") or die(mysqli_error($link));
			}
		}

		

		// Header
		$url = "index.php?open=domains_monitoring&page=insert_domains&format=$format&editor_language=$editor_language&l=$l&ft=success&fm=domains_inserted";
		header("Location: $url");
		exit;

	} // process == 1
	echo"
	<h1>Insert domains</h1>


	<!-- Where am I? -->
	<p><b>You are here:</b><br />
	<a href=\"index.php?open=domains_monitoring&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Domains monitoring</a>
	&gt;
	<a href=\"index.php?open=domains_monitoring&amp;page=insert_domains&amp;editor_language=$editor_language&amp;l=$l\">Insert domains</a>
	&gt;
	<a href=\"index.php?open=domains_monitoring&amp;page=insert_domains&amp;format=number_domain_length_idn_date&amp;editor_language=$editor_language&amp;l=$l\">Number &nbsp; Domain &nbsp; length &nbsp; IDN &nbsp; Date</a>
	</p>
	<!-- //Where am I? -->


	<!-- Last 3 days of -->
		<div style=\"height:10px;\"></div>
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>Date</span>
		   </th>";
		$query_tlds = "SELECT count_tld FROM $t_domains_monitoring_domains_tld_count GROUP BY count_tld";
		$result_tlds = mysqli_query($link, $query_tlds);
		while($row_tlds = mysqli_fetch_row($result_tlds)) {
			list($get_count_tld) = $row_tlds;
			echo"
				   <th scope=\"col\">
					<span>$get_count_tld</span>
				   </th>";
		}
		echo"
		  </tr>
		 </thead>
		 <tbody>
		";

		// Dates
		$query = "SELECT count_date_saying FROM $t_domains_monitoring_domains_tld_count GROUP BY count_date_saying";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_count_date_saying) = $row;
			echo"
			 <tr>
			  <td>
				<span>$get_count_date_saying</span>
			  </td>		
			";

			$query_c = "SELECT count_id, count_date, count_date_saying, count_tld, count_domains FROM $t_domains_monitoring_domains_tld_count WHERE count_date_saying='$get_count_date_saying' ORDER BY count_tld";
			$result_c = mysqli_query($link, $query_c);
			while($row_c = mysqli_fetch_row($result_c)) {
				list($get_count_id, $get_count_date, $get_count_date_saying, $get_count_tld, $get_count_domains) = $row_c;
				echo"
				  <td>
					<span title=\"$get_count_tld\">$get_count_domains</span>
				  </td>		
				";
			} // while count
			echo"
			 </tr>
			";

		} // while Dates

		echo"
		 </tbody>
		</table>
	<!-- //Last 3 days of -->

	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "domains_inserted"){



			echo"
			<p><b>Success!</b><br />
			Domains where insterted. This is the last five domains:</p>
			<!-- Last 5 domains -->

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

				$query = "SELECT domain_id, domain_value, domain_tld, domain_registered_date_saying, domain_seen_before_times FROM $t_domains_monitoring_domains_index ORDER BY domain_id DESC LIMIT 0,5";
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
			<!-- //Last 5 domains -->
			";
		}
		else{
			$fm = ucfirst($fm);
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
	}
	echo"	
	<!-- //Feedback -->

	<!-- Formats -->
		";
		include("_inc/domains_monitoring/insert_domains_format_navigation.php");
		echo"
	<!-- //Formats -->

	<!-- Actions -->
		<p>
		<a href=\"index.php?open=domains_monitoring&page=check_domains&editor_language=$editor_language&l=$l&process=1\" class=\"btn_default\">Check domains</a>
		</p>
	<!-- //Actions -->

	<!-- Form -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_domains\"]').focus();
		});
		</script>
			
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;format=$format&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

		<p><b>Domains:</b><br />
		<span>From <a href=\"https://dnpedia.com/tlds/daily.php\">dnpedia.com</a><br />
		Format: Number &nbsp; Domain &nbsp; length &nbsp; IDN &nbsp; Date</span>
		<br />		
		<textarea name=\"inp_domains\" rows=\"10\" cols=\"50\" style=\"width: 100%;\"></textarea>
		</p>

		<p>
		<input type=\"submit\" value=\"Save\" class=\"btn_default\" />
		</p>
	
		</form>
	<!-- //Form -->
	";

}
?>