<?php
error_reporting(E_ALL);
session_start();
ini_set('arg_separator.output', '&amp;');

/**
*
* File: _admin/_inc/domains_monitoring/insert_domains_step_2_check_domains.php
* Version 09:19 31.08.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Functions ------------------------------------------------------------------------ */
include("../../../_functions/output_html.php");
include("../../../_functions/clean.php");
include("../../../_functions/quote_smart.php");
include("../../../_functions/resize_crop_image.php");

/*- Check if setup is run ------------------------------------------------------------ */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);
$setup_finished_file = "setup_finished_" . $server_name . ".php";
if(!(file_exists("../../../_data/$setup_finished_file"))){
	header("Location: ../../../setup/");
	exit;
}


/*- MySQL ----------------------------------------------------------------------------- */
$mysql_config_file = "../../../_data/mysql_" . $server_name . ".php";
if(file_exists($mysql_config_file)){
	include("$mysql_config_file");
	$link = mysqli_connect($mysqlHostSav, $mysqlUserNameSav, $mysqlPasswordSav, $mysqlDatabaseNameSav);
	if (!$link) {
		echo "
		<div class=\"alert alert-danger\"><span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span><strong>MySQL connection error</strong>"; 
		echo PHP_EOL;
   		echo "<br />Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    		echo "<br />Debugging error: " . mysqli_connect_error() . PHP_EOL;
    		echo"
		</div>
		";
	}
}
else{
	echo"DB error"; die;
}

/*- Tables ---------------------------------------------------------------------------- */
$t_domains_monitoring_liquidbase	= $mysqlPrefixSav . "domains_monitoring_liquidbase";
$t_domains_monitoring_domains_index	= $mysqlPrefixSav . "domains_monitoring_domains_index";
$t_domains_monitoring_filters_keywords	= $mysqlPrefixSav . "domains_monitoring_filters_keywords";


$t_domains_monitoring_domains_filtered		= $mysqlPrefixSav . "domains_monitoring_domains_filtered";
$t_domains_monitoring_domains_monitored		= $mysqlPrefixSav . "domains_monitoring_domains_monitored";

$t_domains_monitoring_stats_total = $mysqlPrefixSav . "domains_monitoring_stats_total";

/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['inc'])) {
	$inc = $_GET['inc'];
	$inc = output_html($inc);
}
else{
	$inc = "1_check_ip_and_host";
}


if(isset($_GET['domain_number'])) {
	$domain_number = $_GET['domain_number'];
	$domain_number = strip_tags(stripslashes($domain_number));
}
else{
	$domain_number = "0";
}
$domain_number_in  = "$domain_number";
$domain_number_out = "$domain_number";
if(isset($_GET['keyword_number'])) {
	$keyword_number = $_GET['keyword_number'];
	$keyword_number = strip_tags(stripslashes($keyword_number));
	if(!(is_numeric($keyword_number))){
		echo"Keyword number is not numeric!";
		die;
	}
}
else{
	$keyword_number = "0";
}
$keyword_number_in  = "$keyword_number";
$keyword_number_out = "$keyword_number";

if(isset($_GET['start_time'])) {
	$start_time = $_GET['start_time'];
	$start_time = strip_tags(stripslashes($start_time));
	if(!(is_numeric($start_time))){
		echo"start_time is not numeric!";
		die;
	}
}
else{
	$start_time = time();
}


// Dates
$datetime = date("Y-m-d H:i:s");
$date_saying = date("j M Y");
$rand = date("ymdhis");
$time = time();

// Stats
$query = "SELECT total_id, total_domains, total_domains_other_checked, total_domains_other_checked_percentage, total_domains_other_not_checked, total_domains_starts_with_ends_with_checked, total_domains_starts_with_ends_with_checked_percentage, total_domains_starts_with_ends_with_not_checked, total_domains_filtered, total_domains_monitored, total_last_checked_time FROM $t_domains_monitoring_stats_total LIMIT 0,1";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_total_id, $get_current_total_domains, $get_current_total_domains_other_checked, $get_current_total_domains_other_checked_percentage, $get_current_total_domains_other_not_checked, $get_current_total_domains_starts_with_ends_with_checked, $get_current_total_domains_starts_with_ends_with_checked_percentage, $get_current_total_domains_starts_with_ends_with_not_checked, $get_current_total_domains_filtered, $get_current_total_domains_monitored, $get_current_total_last_checked_time) = $row;

// Stats :: Last checked
$last_checked_diff = $time-$get_current_total_last_checked_time;
if($last_checked_diff > 60){
	include("inc/update_stats_total.php");
}

// Head
echo"<!DOCTYPE html>\n";
echo"<html lang=\"en\">\n";
echo"<head>\n";
echo"	<title>$get_current_total_domains_other_checked_percentage %</title>\n";
echo"	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
echo"	<meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0;\"/>\n";
echo"	<link rel=\"stylesheet\" href=\"matrix.css?rand=$rand\" type=\"text/css\" />\n";
echo"</head>\n";
echo"<body>\n";

// Results
echo"


<!-- Header -->
	<header>
		<div class=\"header_inner\">
				<div class=\"header_inner_col\">
					<!-- Percentage -->
						<div id=\"progressbar\">
							<div class=\"progressbar_inner_red\" style=\"width: $get_current_total_domains_other_checked_percentage"; echo"%;\"></div>
						</div>

						<p>
						$get_current_total_domains_other_checked_percentage %
						($get_current_total_domains_other_not_checked to check)
						</p>
					<!-- //Percentage -->
				</div>
				<div class=\"header_inner_col\">
					<!-- Time used -->
						";
						$seconds = $time - $start_time;
						$seconds = round($seconds);
						// $time_used = sprintf('%02d h %02d m', ($seconds/ 3600),($seconds/ 60 % 60));
						$minutes = floor($seconds/60); 
						echo"
						<p>Time used:<br /> $minutes minutes</p>
					<!-- //Time used -->
				</div>
				<div class=\"header_inner_col\">
					<!-- Clock -->
					<div><p id=\"clock\">8:10:45</p></div>
					<script>
					setInterval(showTime, 1000);
					function showTime() {
						let time = new Date();
						let hour = time.getHours();
						let min = time.getMinutes();
						let sec = time.getSeconds();

  
						hour = hour < 10 ? \"0\" + hour : hour;
						min = min < 10 ? \"0\" + min : min;
						sec = sec < 10 ? \"0\" + sec : sec;
  
						let currentTime = hour + \":\" + min + \":\" + sec;
  
						document.getElementById(\"clock\").innerHTML = currentTime;
					}
					showTime();
					</script>
					<!-- //Clock -->
				</div>
		</div> <!-- //header inner -->
	</header>
<!-- //Header -->

<div class=\"wrapper\">
	<div class=\"content_left\">
		";

		// Inc
		if($inc == "1_check_ip_and_host" OR $inc == "2_check_regex_contains_starts_with_ends_with" OR $inc == "3_check_starts_with_word_ends_with_another_word" OR $inc == "4_remove_filtered_domains_that_doesnt_starts_with_word_ends_with_another_word" OR $inc == "5_find_ip_addresses"){
			include("inc/$inc.php");
		}
		else{
			echo"<p>Unknown $inc</p>";
		}


		// Results end
		echo"
			</div> <!-- //left -->
			<div class=\"content_right\">
				<!-- Todays domains -->
					<h2>Filtered domains ($get_current_total_domains_filtered)</h2>
					<table class=\"hor-zebra\">
					 <tbody>
					";
					
					$query_d = "SELECT filtered_id, filtered_domain_value, filtered_domain_ip, filtered_domain_filters_activated, filtered_score, filtered_domain_ip, filtered_domain_host_addr, filtered_domain_host_name FROM $t_domains_monitoring_domains_filtered WHERE filtered_date_saying='$date_saying' AND filtered_notes='' ORDER BY filtered_id DESC";
					$result_d = mysqli_query($link, $query_d);
					while($row_d = mysqli_fetch_row($result_d)) {
						list($get_filtered_id, $get_filtered_domain_value, $get_filtered_domain_ip, $get_filtered_domain_filters_activated, $get_filtered_score, $get_filtered_domain_ip, $get_filtered_domain_host_addr, $get_filtered_domain_host_name) = $row_d;
					
						echo"
						  <tr>
						   <td>
							<span>$get_filtered_domain_value</span>
						   </td>
						   <td>
							<span>$get_filtered_domain_ip</span>
						   </td>
						   <td>
							<span>$get_filtered_domain_host_name</span>
						   </td>
						   <td>
							<span>$get_filtered_domain_filters_activated</span>
						   </td>
						   <td>
							<span>$get_filtered_score</span>
						   </td>
						  </tr>
						";
					}
					echo"
					 </tbody>
					</table>
				<!-- //Todays domains -->





			</div> <!-- //right -->
		</div> <!-- //wrapper -->


		<!-- Footer -->";

			// Update 
			/*
			$inp_domains_not_checked = $get_current_total_domains_not_checked-$count_domains_checks;
			$inp_domains_checked  = $get_current_total_domains_checked+$count_domains_checks;
			$inp_domains_checked_percentage = ($inp_domains_checked/$inp_domains_not_checked)*100;
			$inp_domains_checked_percentage = round($inp_domains_checked_percentage);

			mysqli_query($link, "UPDATE $t_domains_monitoring_stats_total SET 
							total_domains_not_checked=$inp_domains_not_checked, 
							total_domains_checked=$inp_domains_checked, 
							total_domains_checked_percentage=$inp_domains_checked_percentage, 
							total_domains_filtered=$inp_domains_filtered
							WHERE total_id=$get_current_total_id") or die(mysql_error($link));
			*/

			echo"
		<!-- //Footer -->
		";

		// Footer
		echo"</body>\n";
		echo"</html>";
	

?>