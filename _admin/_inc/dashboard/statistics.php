<?php
/**
*
* File: _admin/_inc/media/statistics.php
* Version 2.0.0
* Date 18:16 28.04.2019
* Copyright (c) 2008-2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables ---------------------------------------------------------------------------------- */
$t_stats_accepted_languages_per_month	= $mysqlPrefixSav . "stats_accepted_languages_per_month";
$t_stats_accepted_languages_per_year	= $mysqlPrefixSav . "stats_accepted_languages_per_year";

$t_stats_browsers_per_month	= $mysqlPrefixSav . "stats_browsers_per_month";
$t_stats_browsers_per_year	= $mysqlPrefixSav . "stats_browsers_per_year";

$t_stats_comments_per_month 	= $mysqlPrefixSav . "stats_comments_per_month";
$t_stats_comments_per_year 	= $mysqlPrefixSav . "stats_comments_per_year";

$t_stats_countries_per_year  = $mysqlPrefixSav . "stats_visits_per_year";
$t_stats_countries_per_month = $mysqlPrefixSav . "stats_visits_per_month";

$t_stats_ip_to_country_lookup = $mysqlPrefixSav . "stats_ip_to_country_lookup";
$t_languages_countries	      = $mysqlPrefixSav . "languages_countries";

$t_stats_os_per_month = $mysqlPrefixSav . "stats_os_per_month";
$t_stats_os_per_year = $mysqlPrefixSav . "stats_os_per_year";

$t_stats_referers_per_year  = $mysqlPrefixSav . "stats_referers_per_year";
$t_stats_referers_per_month = $mysqlPrefixSav . "stats_referers_per_month";

$t_stats_user_agents_index = $mysqlPrefixSav . "stats_user_agents_index";

$t_stats_users_registered_per_month = $mysqlPrefixSav . "stats_users_registered_per_month";
$t_stats_users_registered_per_year = $mysqlPrefixSav . "stats_users_registered_per_year";

$t_stats_bots_per_month	= $mysqlPrefixSav . "stats_bots_per_month";
$t_stats_bots_per_year	= $mysqlPrefixSav . "stats_bots_per_year";

$t_stats_visists_per_day 	= $mysqlPrefixSav . "stats_visists_per_day";
$t_stats_visists_per_day_ips 	= $mysqlPrefixSav . "stats_visists_per_day_ips";
$t_stats_visists_per_month 	= $mysqlPrefixSav . "stats_visists_per_month";
$t_stats_visists_per_month_ips 	= $mysqlPrefixSav . "stats_visists_per_month_ips";
$t_stats_visists_per_year 	= $mysqlPrefixSav . "stats_visists_per_year";
$t_stats_visists_per_year_ips 	= $mysqlPrefixSav . "stats_visists_per_year_ips";



/*- Translation ----------------------------------------------------------------------- */
include("_translations/admin/$l/dashboard/t_default.php");

/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['week'])) {
	$week = $_GET['week'];
	$week = strip_tags(stripslashes($week));
}
else{
	$week = date("W");
}
$week_mysql = quote_smart($link, $week);

if(isset($_GET['month'])) {
	$month = $_GET['month'];
	$month = strip_tags(stripslashes($month));
}
else{
	$month = date("m");
}
$month_mysql = quote_smart($link, $month);

if(isset($_GET['year'])) {
	$year = $_GET['year'];
	$year = strip_tags(stripslashes($year));
}
else{
	$year = date("Y");
}
$year_mysql = quote_smart($link, $year);

if($action == ""){
	echo"
	<h1>Statistics</h1>

	<!-- Menu -->
		<div class=\"tabs\">
			<ul>
				<li><a href=\"index.php?open=dashboard&amp;page=statistics&amp;l=$l\" class=\"active\">Statistics</a></li>
				<li><a href=\"index.php?open=dashboard&amp;page=statistics&amp;action=ip_to_country&amp;l=$l\">IP to country</a></li>
			</ul>
		</div>
		<div class=\"clear\" style=\"height: 10px;\"></div>
	<!-- Menu -->


	<!-- Unknown agents -->
		";
		// Unknown type
		$unknown_type = 0;
		$query = "SELECT * FROM $t_stats_user_agents_index WHERE stats_user_agent_type=''";
		if ($result = mysqli_query($link, "$query")) {
			$unknown_type = mysqli_num_rows($result);
			mysqli_free_result($result);
		}

		// Unknown browsers
		$unknown_browser = 0;
		$query = "SELECT * FROM $t_stats_user_agents_index WHERE stats_user_agent_browser='' AND stats_user_agent_bot=''";
		if ($result = mysqli_query($link, "$query")) {
			$unknown_browser = mysqli_num_rows($result);
			mysqli_free_result($result);
		}

		// Unknown os
		$unknown_os = 0;
		$query = "SELECT * FROM $t_stats_user_agents_index WHERE stats_user_agent_os='' AND stats_user_agent_bot=''";
		if ($result = mysqli_query($link, "$query")) {
			$unknown_os = mysqli_num_rows($result);
			mysqli_free_result($result);
		}

		// Unknown flag
		$unknown_flag = 0;
		$query = "SELECT * FROM $t_stats_user_agents_index WHERE stats_user_agent_type='unknown'";
		if ($result = mysqli_query($link, "$query")) {
			$unknown_flag = mysqli_num_rows($result);
			mysqli_free_result($result);
		}



		$unknown_total = $unknown_type+$unknown_browser+$unknown_os+$unknown_flag;
		if($unknown_total != 0){
			echo"
			<div class=\"warning\"><p>$l_unknown_agents_has $unknown_total $l_new_unknown_user_agents:
			$unknown_type $l_unknown_types,
			$unknown_browser $l_unknown_browsers,
			$unknown_os $l_unknown_os,
			$l_and $unknown_flag $l_marked_with_unknown_flag.
			
			</p><p><a href=\"index.php?open=$open&amp;page=user_agents&amp;action=fix_agents&amp;editor_language=$editor_language\" class=\"btn\">$l_fix_problems</a></p></div>
			";
		}


		echo"
	<!-- //Unknown agents -->


	<!-- Periode selection -->
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span><b>Year</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Unique</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Unique desktop</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Unique mobile</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Unique bots</b></span>
		   </td>
		  </tr>
		 </thead>";

		// Fetch all years ower all languages
		$query_y = "SELECT DISTINCT stats_visit_per_year_year FROM $t_stats_visists_per_year ORDER BY stats_visit_per_year_year DESC";
		$result_y = mysqli_query($link, $query_y);
		while($row_y = mysqli_fetch_row($result_y)) {
			list($get_year) = $row_y;


			$visit_per_year_human_unique = 0;
			$visit_per_year_unique_desktop = 0;
			$visit_per_year_unique_mobile = 0;
			$visit_per_year_unique_bots = 0;
			
			$query = "SELECT stats_visit_per_year_id, stats_visit_per_year_year, stats_visit_per_year_human_unique, stats_visit_per_year_human_unique_diff_from_last_year, stats_visit_per_year_human_average_duration, stats_visit_per_year_human_new_visitor_unique, stats_visit_per_year_human_returning_visitor_unique, stats_visit_per_year_unique_desktop, stats_visit_per_year_unique_mobile, stats_visit_per_year_unique_bots, stats_visit_per_year_hits_total, stats_visit_per_year_hits_human, stats_visit_per_year_hits_desktop, stats_visit_per_year_hits_mobile, stats_visit_per_year_hits_bots FROM $t_stats_visists_per_year WHERE stats_visit_per_year_year=$get_year";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_stats_visit_per_year_id, $get_stats_visit_per_year_year, $get_stats_visit_per_year_human_unique, $get_stats_visit_per_year_human_unique_diff_from_last_year, $get_stats_visit_per_year_human_average_duration, $get_stats_visit_per_year_human_new_visitor_unique, $get_stats_visit_per_year_human_returning_visitor_unique, $get_stats_visit_per_year_unique_desktop, $get_stats_visit_per_year_unique_mobile, $get_stats_visit_per_year_unique_bots, $get_stats_visit_per_year_hits_total, $get_stats_visit_per_year_hits_human, $get_stats_visit_per_year_hits_desktop, $get_stats_visit_per_year_hits_mobile, $get_stats_visit_per_year_hits_bots) = $row;

				$visit_per_year_human_unique = $visit_per_year_human_unique + $get_stats_visit_per_year_human_unique ;
				$visit_per_year_unique_desktop = $visit_per_year_unique_desktop + $get_stats_visit_per_year_unique_desktop ;
				$visit_per_year_unique_mobile = $visit_per_year_unique_mobile + $get_stats_visit_per_year_unique_mobile;
				$visit_per_year_unique_bots = $visit_per_year_unique_bots + $get_stats_visit_per_year_unique_bots;
			}
			echo"
				 <tr>
				  <td>
					<a id=\"#year$get_year\"></a>
					<span>
					<a href=\"index.php?open=$open&amp;page=statistics_year&amp;stats_year=$get_year&amp;&amp;l=$l&amp;editor_language=$editor_language\">$get_year</a>
					</span>
				  </td>
				  <td>
					<span>
					$visit_per_year_human_unique
					</span>
				  </td>
				  <td>
					<span>
					$visit_per_year_unique_desktop
					</span>
				  </td>
				  <td>
					<span>
					$visit_per_year_unique_mobile
					</span>
				  </td>
				  <td>
					<span>
					$visit_per_year_unique_bots
					</span>
				  </td>
				 </tr>";

			// Months
			// Fetch all months ower all languages
			$query_months = "SELECT DISTINCT stats_visit_per_month_month FROM $t_stats_visists_per_month WHERE stats_visit_per_month_year=$get_year";
			$result_months = mysqli_query($link, $query_months);
			while($row_months = mysqli_fetch_row($result_months)) {
				list($get_month) = $row_months;

				$visit_per_month_month_full = "";
				$visit_per_month_human_unique = 0;
				$visit_per_month_unique_desktop = 0;
				$visit_per_month_unique_mobile = 0;
				$visit_per_month_unique_bots = 0;
					
				$query_m = "SELECT stats_visit_per_month_id, stats_visit_per_month_month, stats_visit_per_month_month_full, stats_visit_per_month_month_short, stats_visit_per_month_year, stats_visit_per_month_human_unique, stats_visit_per_month_human_unique_diff_from_last_month, stats_visit_per_month_human_average_duration, stats_visit_per_month_human_new_visitor_unique, stats_visit_per_month_human_returning_visitor_unique, stats_visit_per_month_unique_desktop, stats_visit_per_month_unique_mobile, stats_visit_per_month_unique_bots, stats_visit_per_month_hits_total, stats_visit_per_month_hits_human, stats_visit_per_month_hits_desktop, stats_visit_per_month_hits_mobile, stats_visit_per_month_hits_bots FROM $t_stats_visists_per_month WHERE stats_visit_per_month_month=$get_month AND stats_visit_per_month_year=$get_stats_visit_per_year_year";
				$result_m = mysqli_query($link, $query_m);
				while($row_m = mysqli_fetch_row($result_m)) {
					list($get_stats_visit_per_month_id, $get_stats_visit_per_month_month, $get_stats_visit_per_month_month_full, $get_stats_visit_per_month_month_short, $get_stats_visit_per_month_year, $get_stats_visit_per_month_human_unique, $get_stats_visit_per_month_human_unique_diff_from_last_month, $get_stats_visit_per_month_human_average_duration, $get_stats_visit_per_month_human_new_visitor_unique, $get_stats_visit_per_month_human_returning_visitor_unique, $get_stats_visit_per_month_unique_desktop, $get_stats_visit_per_month_unique_mobile, $get_stats_visit_per_month_unique_bots, $get_stats_visit_per_month_hits_total, $get_stats_visit_per_month_hits_human, $get_stats_visit_per_month_hits_desktop, $get_stats_visit_per_month_hits_mobile, $get_stats_visit_per_month_hits_bots) = $row_m;

					$visit_per_month_month_full = "$get_stats_visit_per_month_month_full";
					$visit_per_month_human_unique = $visit_per_month_human_unique + $get_stats_visit_per_month_human_unique;
					$visit_per_month_unique_desktop = $visit_per_month_unique_desktop + $get_stats_visit_per_month_unique_desktop;
					$visit_per_month_unique_mobile = $visit_per_month_unique_mobile + $get_stats_visit_per_month_unique_mobile;
					$visit_per_month_unique_bots = $visit_per_month_unique_bots + $get_stats_visit_per_month_unique_bots;
				}
				echo"
				 <tr>
				  <td style=\"padding-left: 10px;\">
					<a id=\"#month$get_year$get_month\"></a>
					<span>
					<a href=\"index.php?open=$open&amp;page=statistics_month&amp;stats_year=$get_year&amp;stats_month=$get_month&amp;l=$l&amp;editor_language=$editor_language\">$visit_per_month_month_full $get_year</a>
					</span>
				  </td>
				  <td>
					<span>
					$visit_per_month_human_unique
					</span>
				  </td>
				  <td>
					<span>
					$visit_per_month_unique_desktop
					</span>
				  </td>
				  <td>
					<span>
					$visit_per_month_unique_mobile
					</span>
				  </td>
				  <td>
					<span>
					$visit_per_month_unique_bots
					</span>
				  </td>
				 </tr>";
			} // while months distinct
		} // while years distinct
		echo"
			</table>
		  </td>
		 </tr>
		</table>
	<!-- //Periode selection -->

	";
}
elseif($action == "online_now"){
	echo"
	<h1>Online now</h1>

	<!-- Menu -->
		<div class=\"tabs\">
			<ul>
				<li><a href=\"index.php?open=dashboard&amp;page=statistics&amp;l=$l\">Statistics</a></li>
				<li><a href=\"index.php?open=dashboard&amp;page=statistics&amp;action=online_now&amp;l=$l\" class=\"active\">Online now</a></li>
				<li><a href=\"index.php?open=dashboard&amp;page=statistics&amp;action=ip_to_country&amp;l=$l\">IP to country</a></li>
			</ul>
		</div>
		<div class=\"clear\" style=\"height: 10px;\"></div>
	<!-- Menu -->

				<!-- Online now -->


					<h2 class=\"online_now_headline\">$l_right_now</h2>

					";

					// Get record
					if(!(isset($date)) OR $date == ""){
						$date = date("Y-m-d");
					}
					$unix_time = time();
					$unix_time_minus_ten = 60*5;
					$unix_time = $unix_time - $unix_time_minus_ten;

					$query = "SELECT stats_human_online_record_id, stats_human_online_record_count FROM $t_stats_human_online_records WHERE stats_human_online_record_date='$date'";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_stats_human_online_record_id, $get_stats_human_online_record_count) = $row;
			
					// Online now
					$query = "SELECT * FROM $t_stats_human_ipblock WHERE stats_human_visitor_timestamp_last_seen > $unix_time";
					$result = mysqli_query($link, $query);
					$online_now = mysqli_num_rows($result);

					echo"
					<p class=\"online_now_number\">$online_now</p>

					<p class=\"online_now_sub_text\">$l_active_users_on_site</p>
				<!-- //Online now -->

	<!-- Online now -->
		<h2>$l_online_now</h2>
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>$l_page</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_os</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_browser</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_language</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_type</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_hits</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_ip</span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>
		";
		

		$query = "SELECT stats_human_visitor_id, stats_human_visitor_date, stats_human_visitor_time, stats_human_visitor_month, stats_human_visitor_year, stats_human_visitor_timestamp_first_seen, stats_human_visitor_timestamp_last_seen, stats_human_visitor_hits, stats_human_visitor_ip, stats_human_visitor_browser, stats_human_visitor_os, stats_human_visitor_language, stats_human_visitor_type, stats_human_visitor_page FROM $t_stats_human_ipblock WHERE stats_human_visitor_timestamp_last_seen > '$unix_time'";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_stats_human_visitor_id, $get_stats_human_visitor_date, $get_stats_human_visitor_time, $get_stats_human_visitor_month, $get_stats_human_visitor_year, $get_stats_human_visitor_timestamp_first_seen, $get_stats_human_visitor_timestamp_last_seen, $get_stats_human_visitor_hits, $get_stats_human_visitor_ip, $get_stats_human_visitor_browser, $get_stats_human_visitor_os, $get_stats_human_visitor_language, $get_stats_human_visitor_type, $get_stats_human_visitor_page) = $row;

			if(isset($style) && $style == "odd"){
				$style = "";
			}
			else{
				$style = "odd";
			}

			$diff = $get_stats_human_visitor_timestamp_last_seen-$unix_time;

			

			echo"
			 <tr>
			  <td class=\"$style\">
				<span><a href=\"../..$get_stats_human_visitor_page\">$get_stats_human_visitor_page</a></span>
			  </td>
			  <td class=\"$style\">
				<span>$get_stats_human_visitor_browser</span>
			  </td>
			  <td class=\"$style\">
				<span>$get_stats_human_visitor_os</span>
			  </td>
			  <td class=\"$style\">
				<span>$get_stats_human_visitor_language</span>
			  </td>
			  <td class=\"$style\">
				<span>$get_stats_human_visitor_type</span>
			  </td>
			  <td class=\"$style\">
				<span>$get_stats_human_visitor_hits</span>
			  </td>
			  <td class=\"$style\">
				<span>$get_stats_human_visitor_ip</span>
			  </td>
			 </tr>
			";
		}
		echo"
		 </tbody>
		</table>
	<!-- //Online now -->

	";
} // online now
elseif($action == "ip_to_country"){
	echo"
	<h1>IPv6 to country</h1>
	<!-- Menu -->
		<div class=\"tabs\">
			<ul>
				<li><a href=\"index.php?open=dashboard&amp;page=statistics&amp;l=$l\">Statistics</a></li>
				<li><a href=\"index.php?open=dashboard&amp;page=statistics&amp;action=ip_to_country&amp;l=$l\" class=\"active\">IP to country</a></li>
			</ul>
		</div>
		<div class=\"clear\" style=\"height: 10px;\"></div>
	<!-- Menu -->

	<!-- Example ipv6 -->
		";
		if(isset($_GET['inp_ip'])) {
			$inp_ip = $_GET['inp_ip'];
			$inp_ip = strip_tags(stripslashes($inp_ip));
		}
		else{
			$inp_ip = $_SERVER['REMOTE_ADDR'];
			if($inp_ip == "127.0.0.1" OR $inp_ip == "::1"){
				$inp_ip = "2a01:799:1120:2a00:1971:4c1:f51f:a4b9";
			}
		}
		echo"
		<!-- Form -->
			<form method=\"get\" action=\"index.php?open=dashboard&amp;page=statistics&amp;action=ipv6_to_country&amp;l=$l\" enctype=\"multipart/form-data\">
			<p>
			<input type=\"hidden\" name=\"open\" value=\"$open\" />
			<input type=\"hidden\" name=\"page\" value=\"$page\" />
			<input type=\"hidden\" name=\"action\" value=\"$action\" />
			<input type=\"hidden\" name=\"l\" value=\"$l\" />
			<b>IP:</b> <input type=\"text\" name=\"inp_ip\" value=\"$inp_ip\" size=\"25\" style=\"width: 90%;\" />
			<input type=\"submit\" value=\"Search\" class=\"btn_default\" />
			</p>
			</form>
		<!-- //Form -->
		";

		$inp_ip = output_html($inp_ip);



		if($inp_ip != ""){
		
			$ip_type = "";
			if (ip2long($inp_ip) !== false) {
				$ip_type = "ipv4";
			} else if (preg_match('/^[0-9a-fA-F:]+$/', $inp_ip) && @inet_pton($inp_ip)) {
				$ip_type = "ipv6";
			}

			// In addr
			$in_addr = inet_pton($inp_ip); // 32bit or 128bit binary structure
			$in_addr_mysql = quote_smart($link, $in_addr);
			
		
			$query = "select * from $t_stats_ip_to_country_lookup where addr_type = '$ip_type' and ip_start <= $in_addr_mysql order by ip_start desc limit 1";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_ip_id, $get_addr_type, $get_ip_start, $get_ip_end, $get_country) = $row;

			$get_country_name = "";
			$get_country_iso_two = "";
			$query_c = "";
			if($get_ip_id != ""){
				$country_iso_two_mysql = quote_smart($link, $get_country);
				$query_c = "SELECT country_id, country_name, country_iso_two FROM $t_languages_countries WHERE country_iso_two=$country_iso_two_mysql";
				$result_c = mysqli_query($link, $query_c);
				$row_c = mysqli_fetch_row($result_c);
				list($get_country_id, $get_country_name, $get_country_iso_two) = $row_c;
			}
		
			echo"
			<p>
			Query: $query<br />
			Query: $query_c<br />
			Type: $ip_type<br />
			IP ID: $get_ip_id<br />
			ISO 2: $get_country_iso_two<br />
			Country name: $get_country_name<br />
			</p>
			";
		}
		else{
			echo"<p>Invalid IP address.</p>";
		}
		echo"
	<!-- //Example ipv6 -->

	";
} // ip_to_country

?>