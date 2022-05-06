<?php
/**
*
* File: _admin/_inc/media/statistics_month.php
* Version 4.0
* Date 01:58 02.04.2022
* Copyright (c) 2008-2022 Sindre Andre Ditlefsen
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
$t_stats_comments_per_week 	= $mysqlPrefixSav . "stats_comments_per_week";

$t_stats_countries_per_year  = $mysqlPrefixSav . "stats_countries_per_year";
$t_stats_countries_per_month = $mysqlPrefixSav . "stats_countries_per_month";

$t_stats_ip_to_country_lookup_ipv4 = $mysqlPrefixSav . "stats_ip_to_country_lookup_ipv4";
$t_stats_ip_to_country_lookup_ipv6 = $mysqlPrefixSav . "stats_ip_to_country_lookup_ipv6";
$t_stats_ip_to_country_geonames 	= $mysqlPrefixSav . "stats_ip_to_country_geonames";

$t_stats_languages_per_year	= $mysqlPrefixSav . "stats_languages_per_year";
$t_stats_languages_per_month	= $mysqlPrefixSav . "stats_languages_per_month";

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

$t_stats_pages_visits_per_year = $mysqlPrefixSav . "stats_pages_visits_per_year";

$t_search_engine_searches = $mysqlPrefixSav . "search_engine_searches";

$t_stats_tracker_index = $mysqlPrefixSav . "stats_tracker_index";

/*- Translation ----------------------------------------------------------------------- */
include("_translations/admin/$l/dashboard/t_default.php");

/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['stats_year'])) {
	$stats_year = $_GET['stats_year'];
	$stats_year = strip_tags(stripslashes($stats_year));
}
else{
	$stats_year = date("Y");
}
$stats_year_mysql = quote_smart($link, $stats_year);

if(isset($_GET['stats_month'])) {
	$stats_month = $_GET['stats_month'];
	$stats_month = strip_tags(stripslashes($stats_month));
}
else{
	$stats_month = date("m");
}
$stats_month_mysql = quote_smart($link, $stats_month);

$editor_language_mysql = quote_smart($link, $editor_language);

/*- Functions ----------------------------------------------------------------------- */
function get_title($url) {
	$url = str_replace("&amp;", "&", $url);

	$options = array(
	  'http'=>array(
	    'method'=>"GET",
	    'header'=>"Accept-language: en\r\n" .
	              "Cookie: foo=bar\r\n" .  // check function.stream-context-create on php.net
	              "User-Agent: Mozilla/5.0 (compatible; QuickCMS/1; +https://ditlef.net)\r\n"
	  )
	);

	$context = stream_context_create($options);
	$page = file_get_contents($url, false, $context);
	$title = preg_match('/<title[^>]*>(.*?)<\/title>/ims', $page, $match) ? $match[1] : null;
	return $title;
}


// Find month
$query = "SELECT stats_visit_per_month_id, stats_visit_per_month_month, stats_visit_per_month_month_full, stats_visit_per_month_month_short, stats_visit_per_month_year, stats_visit_per_month_human_unique, stats_visit_per_month_human_unique_diff_from_last_month, stats_visit_per_month_human_average_duration, stats_visit_per_month_human_new_visitor_unique, stats_visit_per_month_human_returning_visitor_unique, stats_visit_per_month_unique_desktop, stats_visit_per_month_unique_mobile, stats_visit_per_month_unique_bots, stats_visit_per_month_hits_total, stats_visit_per_month_hits_human, stats_visit_per_month_hits_desktop, stats_visit_per_month_hits_mobile, stats_visit_per_month_hits_bots FROM $t_stats_visists_per_month WHERE stats_visit_per_month_month=$stats_month_mysql AND stats_visit_per_month_year=$stats_year_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_stats_visit_per_month_id, $get_current_stats_visit_per_month_month, $get_current_stats_visit_per_month_month_full, $get_current_stats_visit_per_month_month_short, $get_current_stats_visit_per_month_year, $get_current_stats_visit_per_month_human_unique, $get_current_stats_visit_per_month_human_unique_diff_from_last_month, $get_current_stats_visit_per_month_human_average_duration, $get_current_stats_visit_per_month_human_new_visitor_unique, $get_current_stats_visit_per_month_human_returning_visitor_unique, $get_current_stats_visit_per_month_unique_desktop, $get_current_stats_visit_per_month_unique_mobile, $get_current_stats_visit_per_month_unique_bots, $get_current_stats_visit_per_month_hits_total, $get_current_stats_visit_per_month_hits_human, $get_current_stats_visit_per_month_hits_desktop, $get_current_stats_visit_per_month_hits_mobile, $get_current_stats_visit_per_month_hits_bots) = $row;

if($get_current_stats_visit_per_month_id == ""){
	echo"<p>Server error 404</p>";
}
else{	
	echo"
	<!-- Headline -->
		<h1>Statistics $get_current_stats_visit_per_month_month_full $get_current_stats_visit_per_month_year</h1>
	<!-- //Headline -->
	
	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=statistics&amp;l=$l\">Statistics</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=statistics_year&amp;stats_year=$get_current_stats_visit_per_month_year&amp;l=$l\">$get_current_stats_visit_per_month_year</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;stats_year=$get_current_stats_visit_per_month_year&amp;stats_month=$get_current_stats_visit_per_month_month&amp;l=$l\">$get_current_stats_visit_per_month_month_full $get_current_stats_visit_per_month_year</a>
		</p>
	<!-- //Where am I? -->

	

	<!-- Charts javascript -->
		<script src=\"_javascripts/amcharts/index.js\"></script>
		<script src=\"_javascripts/amcharts/xy.js\"></script>
		<script src=\"_javascripts/amcharts/themes/Animated.js\"></script>
		<script src=\"_javascripts/amcharts/percent.js\"></script>
		<script src=\"_javascripts/amcharts/map.js\"></script>
		<script src=\"_javascripts/amcharts/geodata/worldLow.js\"></script>
	<!-- //Charts javascript -->



	<!-- Language -->
		<div class=\"language_select\">
			<ul>";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default, language_active_flag_path_32x32, language_active_flag_active_32x32, language_active_flag_inactive_32x32 FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default, $get_language_active_flag_path_32x32, $get_language_active_flag_active_32x32, $get_language_active_flag_inactive_32x32) = $row;

			// No language selected?
			if($editor_language == ""){
				$editor_language = "$get_language_active_iso_two";
			}

			// Active/Inactive
			if($get_language_active_iso_two == "$editor_language"){
				echo"
				<li class=\"active\"><a href=\"index.php?open=dashboard&amp;page=$page&amp;stats_year=$get_current_stats_visit_per_month_year&amp;stats_month=$get_current_stats_visit_per_month_month&amp;editor_language=$get_language_active_iso_two&amp;l=$l\"><img src=\"../$get_language_active_flag_path_32x32/$get_language_active_flag_active_32x32\" alt=\"$get_language_active_flag_active_32x32\" /><br />$get_language_active_name</a></li>\n";

			}
			else{
				echo"
				<li><a href=\"index.php?open=dashboard&amp;page=$page&amp;stats_year=$get_current_stats_visit_per_month_year&amp;stats_month=$get_current_stats_visit_per_month_month&amp;editor_language=$get_language_active_iso_two&amp;l=$l\"><img src=\"../$get_language_active_flag_path_32x32/$get_language_active_flag_inactive_32x32\" alt=\"$get_language_active_flag_inactive_32x32\" /><br />$get_language_active_name</a></li>\n";
			}
		}
		echo"
			</ul>
		</div> <!-- //language_select -->
	<!-- //Language -->

	<!-- Visits per day -->
		<h2 style=\"padding-bottom:0;margin-bottom:0;\">Visits per day</h2>

		<div id=\"chartdiv_visits_per_day\" style=\"width: 100%;height: 400px;\"></div>
		";
		$cache_file = "visits_per_day_" . $stats_year . "_" . $editor_language . "_" . $configSecurityCodeSav . ".js";
		include("_inc/dashboard/statistics_month_generate/visits_per_day.php");
		echo"
		<script src=\"../_cache/stats_month/$cache_file?rand=$rand\"></script>
	<!-- //Visits per day -->



	<!-- Countries -->
		<h2 style=\"margin-top:20px;padding-bottom:0;margin-bottom:0;\">Unique Visits per Country for $get_current_stats_visit_per_month_month_full</h2>

		<div id=\"chartdiv_unique_visits_per_country\" style=\"width: 100%;max-height: 600px;height: 100vh;\"></div>
		";
		$cache_file = "visits_per_country_" . $stats_year . "_" . $stats_month. "_" . $editor_language . "_" . $configSecurityCodeSav . ".js";
		include("_inc/dashboard/statistics_month_generate/unique_visits_per_country.php");
		echo"
		<script src=\"../_cache/stats_month/$cache_file?rand=$rand\"></script>
		
	<!-- //Countries -->

	<!-- Accepted languages + Language used -->
		<div class=\"flex_row\">
			<!-- Accepted languages -->
				<div class=\"flex_col_50\">
					<h2 style=\"margin-top: 20px;\">$l_accepted_languages</h2>
       		
					<div id=\"chartdiv_accepted_language_per_month\" style=\"height: 250px;margin-top:10px;\"></div>
					";
					$cache_file = "accepted_language_per_month_" . $stats_year . "_" . $stats_month. "_" . $editor_language . "_" . $configSecurityCodeSav . ".js";
					include("_inc/dashboard/statistics_month_generate/accepted_language_per_month.php");
					echo"
					<script src=\"../_cache/stats_month/$cache_file?rand=$rand\"></script>
				</div>
			<!-- //Accepted languages -->

			<!-- Languages used -->
				<div class=\"flex_col_50\">

					<h2 style=\"margin-top: 20px;\">Language used</h2>

					<div id=\"chartdiv_languages_per_month\" style=\"height: 250px;margin-top:10px;\"></div>
					";
					$cache_file = "languages_per_month_" . $stats_year . "_" . $stats_month . "_" . $configSecurityCodeSav . ".js";
					include("_inc/dashboard/statistics_month_generate/languages_per_month.php");
					echo"
					<script src=\"../_cache/stats_month/$cache_file?rand=$rand\"></script>
				</div>
			<!-- //Languages used -->

		</div>
	<!-- //Accepted languages + Language used -->


	<!-- OS + Mobile vs desktop -->
		<div class=\"flex_row\">

			<!-- Os -->
				<div class=\"flex_col_50\">
					<h2 style=\"margin-top: 20px;\">$l_os</h2>

       				<div id=\"chartdiv_os_per_month\" style=\"height: 250px;margin-top:10px;\"></div>
					";
					$cache_file = "os_per_month_" . $stats_year . "_" . $stats_month. "_" . $editor_language . "_" . $configSecurityCodeSav . ".js";
					include("_inc/dashboard/statistics_month_generate/os_per_month.php");
					echo"
					<script src=\"../_cache/stats_month/$cache_file?rand=$rand\"></script>

				</div>
			<!-- //Os -->


			<!-- Mobile vs desktop -->
				<div class=\"flex_col_50\">
					<h2 style=\"margin-top: 20px;\">Mobile vs desktop</h2>
       		
					<div id=\"chartdiv_mobile_vs_desktop_per_month\" style=\"height: 250px;margin-top:10px;\"></div>
					";
					$cache_file = "mobile_vs_desktop_per_month_" . $stats_year . "_" . $stats_month. "_" . $editor_language . "_" . $configSecurityCodeSav . ".js";
					include("_inc/dashboard/statistics_month_generate/mobile_vs_desktop_per_month.php");
					echo"
					<script src=\"../_cache/stats_month/$cache_file?rand=$rand\"></script>
				</div>
			<!-- //Mobile vs desktop -->

		</div>
	<!-- //OS + Mobile vs desktop -->

	<!-- Browser + Human vs bots unique -->
		<div class=\"flex_row\">
			<!-- Browsers -->
				<div class=\"flex_col_50\">
					<h2 style=\"margin-top: 20px;\">$l_browsers</h2>
       		
					<div id=\"chartdiv_browsers_per_month\" style=\"height: 250px;margin-top:10px;\"></div>
					";
					$cache_file = "browsers_per_month_" . $stats_year . "_" . $stats_month. "_" . $editor_language . "_" . $configSecurityCodeSav . ".js";
					include("_inc/dashboard/statistics_month_generate/browsers_per_month.php");
					echo"
					<script src=\"../_cache/stats_month/$cache_file?rand=$rand\"></script>
				</div>
			<!-- //Browsers -->


			<!-- Humans vs bots unique -->
				<div class=\"flex_col_50\">
					<h2 style=\"margin-top: 20px;\">Human vs bots unique</h2>

		       		<div id=\"chartdiv_humans_vs_bots_unique_per_month\" style=\"height: 250px;margin-top:10px;\"></div>
					";
					$cache_file = "humans_vs_bots_unique_per_month_" . $stats_year . "_" . $stats_month. "_" . $editor_language . "_" . $configSecurityCodeSav . ".js";
					include("_inc/dashboard/statistics_month_generate/humans_vs_bots_unique_per_month.php");
					echo"
					<script src=\"../_cache/stats_month/$cache_file?rand=$rand\"></script>
				</div>
			<!-- //Humans vs bots unique -->
		</div>
	<!-- //Browser + Human vs bots unique -->
		
	<!-- Comments this month -->
		<a id=\"comments_this_month\"></a>
		<h2 style=\"margin-top: 20px;\">Comments per week 
		<a href=\"#comments_this_month\" class=\"toggle\" data-divid=\"comments_per_month_information\"><img src=\"_inc/dashboard/_gfx/information_16x16.png\" alt=\"information.png\" /></a></h2>
		<div class=\"comments_per_month_information\">
			<p>
			Comments are from the following modules: blog, courses, downloads, exercises, food, recipes and references
			</p>
		</div>




		<div id=\"chartdiv_comments_per_week\" style=\"height: 400px;\"></div>
		";
		$cache_file = "comments_per_week_" . $stats_year . "_" . $stats_month. "_" . $editor_language . "_" . $configSecurityCodeSav . ".js";
		include("_inc/dashboard/statistics_month_generate/comments_per_week.php");
		echo"
		<script src=\"../_cache/stats_month/$cache_file?rand=$rand\"></script>

	<!-- //Comments this month -->


	<!-- Bots -->
		<h2>$l_bots</h2>
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\" style=\"width: 40%;\">
			<span>$l_bot</span>
		   </th>
		   <th scope=\"col\" style=\"width: 30%;\">
			<span>$l_unique</span>
		   </th>
		   <th scope=\"col\" style=\"width: 30%;\">
			<span>$l_hits</span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>
		";

		$query = "SELECT stats_bot_id, stats_bot_year, stats_bot_name, stats_bot_unique, stats_bot_hits FROM $t_stats_bots_per_month WHERE stats_bot_month=$get_current_stats_visit_per_month_month AND stats_bot_year=$get_current_stats_visit_per_month_year AND stats_bot_language=$editor_language_mysql ORDER BY stats_bot_unique DESC LIMIT 0,5";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_stats_bot_id, $get_stats_bot_year, $get_stats_bot_name, $get_stats_bot_unique, $get_stats_bot_hits) = $row;
			

			$percent = round(($get_stats_bot_unique/$get_current_stats_visit_per_month_unique_bots)*100);
			if($percent > 90){
				$width = 90;
			}
			elseif($percent == 0){
				$width = 1;
			}
			else{
				$width = $percent;
			}
			$div_width = $width . "px";

			echo"
			 <tr>
			  <td>
				<span>$get_stats_bot_name</span>
			  </td>
			  <td>
				<span style=\"float:left;margin-right:10px;\">$get_stats_bot_unique</span>
				<div class=\"stats_bar\" style=\"float:left;margin-right:10px;width: $div_width\">
					<div class=\"stats_bar_inner\"><span>&nbsp;</span></div>
				</div>
			  </td>
			  <td>
				<span>$get_stats_bot_hits</span>
			  </td>
			 </tr>
			";
		}
		echo"
		 </tbody>
		</table>
	<!-- //Bots -->



	<!-- Pages -->
		<h2>Page Visits</h2>


		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\" style=\"width: 40%;\">
			<span>$l_bot</span>
		   </th>
		   <th scope=\"col\" style=\"width: 30%;\">
			<span>Human unique</span>
		   </th>
		   <th scope=\"col\" style=\"width: 30%;\">
			<span>Bots unique</span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>
		";
		if($get_current_stats_visit_per_month_month < 10){
			$get_current_stats_visit_per_month_month = 0 . $get_current_stats_visit_per_month_month;
		}
		$first_day_time = strtotime("$get_current_stats_visit_per_month_year-$get_current_stats_visit_per_month_month-01");
		$query = "SELECT stats_pages_per_year_id, stats_pages_per_year_url, stats_pages_per_year_title, stats_pages_per_year_title_fetched, stats_pages_per_year_human_unique, stats_pages_per_year_unique_desktop, stats_pages_per_year_unique_mobile, stats_pages_per_year_unique_bots FROM $t_stats_pages_visits_per_year WHERE stats_pages_per_year_year=$get_current_stats_visit_per_month_year AND stats_pages_per_year_language=$editor_language_mysql AND stats_pages_per_year_updated_time > $first_day_time ORDER BY stats_pages_per_year_human_unique DESC LIMIT 0,50";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_stats_pages_per_year_id, $get_stats_pages_per_year_url, $get_stats_pages_per_year_title, $get_stats_pages_per_year_title_fetched, $get_stats_pages_per_year_human_unique, $get_stats_pages_per_year_unique_desktop, $get_stats_pages_per_year_unique_mobile, $get_stats_pages_per_year_unique_bots) = $row;
			

			// We need to visit the site in order to get the correct page title
			if($get_stats_pages_per_year_title_fetched == "0"){
				$get_stats_pages_per_year_title = get_title($get_stats_pages_per_year_url);
				$get_stats_pages_per_year_title = output_html($get_stats_pages_per_year_title);

				if($get_stats_pages_per_year_title == ""){
					$get_stats_pages_per_year_title = "$get_stats_pages_per_year_url";
				}

				$inp_title_mysql = quote_smart($link, $get_stats_pages_per_year_title);
				mysqli_query($link, "UPDATE $t_stats_pages_visits_per_year SET stats_pages_per_year_title=$inp_title_mysql, stats_pages_per_year_title_fetched=1 WHERE stats_pages_per_year_id=$get_stats_pages_per_year_id") or die(mysqli_error($link));
				
			}


			echo"
			 <tr>
			  <td>
				<span><a href=\"$get_stats_pages_per_year_url\">$get_stats_pages_per_year_title</a></span>
			  </td>
			  <td>
				<span>$get_stats_pages_per_year_human_unique</span>
			  </td>
			  <td>
				<span>$get_stats_pages_per_year_unique_bots</span>
			  </td>
			 </tr>
			";
		}
		echo"
		 </tbody>
		</table>
	<!-- //Pages -->

	<!-- Searches -->
		<h2>Searches</h2>
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>Query</span>
		   </th>
		   <th scope=\"col\">
			<span>Search counter</span>
		   </th>
		   <th scope=\"col\">
			<span>Results</span>
		   </th>
		   <th scope=\"col\">
			<span>Created</span>
		   </th>
		   <th scope=\"col\">
			<span>Updated</span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>
		";

		// Calendar
		$between_from = "$get_current_stats_visit_per_month_year-$get_current_stats_visit_per_month_month-01 00:00:00";
		if($get_current_stats_visit_per_month_month < "10"){
			$between_from = "$get_current_stats_visit_per_month_year-0$get_current_stats_visit_per_month_month-01 00:00:00";
		}
		$between_from_mysql = quote_smart($link, $between_from);

		$between_to = "$get_current_stats_visit_per_month_year-$get_current_stats_visit_per_month_month-31 00:00:00";
		if($get_current_stats_visit_per_month_month == "2"){
			$between_to = "$get_current_stats_visit_per_month_year-$get_current_stats_visit_per_month_month-28 00:00:00";
		}
		elseif($get_current_stats_visit_per_month_month == "4" OR $get_current_stats_visit_per_month_month == "6" OR $get_current_stats_visit_per_month_month == "9" OR $get_current_stats_visit_per_month_month == "11"){
			$between_to = "$get_current_stats_visit_per_month_year-$get_current_stats_visit_per_month_month-30 00:00:00";
		}
		else{
			$between_to = "$get_current_stats_visit_per_month_year-$get_current_stats_visit_per_month_month-31 00:00:00";
		}
		$between_to_mysql = quote_smart($link, $between_to);

		$query = "SELECT search_id, search_query, search_unique_counter, search_language_used, search_unique_ip_block, search_number_of_results, search_created_datetime, search_created_datetime_print, search_updated_datetime, search_updated_datetime_print FROM $t_search_engine_searches WHERE search_language_used=$editor_language_mysql AND search_updated_datetime > $between_from_mysql AND search_updated_datetime < $between_to_mysql ORDER BY search_updated_datetime DESC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_search_id, $get_search_query, $get_search_unique_counter, $get_search_language_used, $get_search_unique_ip_block, $get_search_number_of_results, $get_search_created_datetime, $get_search_created_datetime_print, $get_search_updated_datetime, $get_search_updated_datetime_print) = $row;
			
			// Style
			if(isset($style) && $style == ""){
				$style = "odd";
			}
			else{
				$style = "";
			}


		
			echo"
			 <tr>
			  <td class=\"$style\">
				<span>
				<a href=\"../search/search.php?inp_search_query=$get_search_query&amp;l=$get_search_language_used\">$get_search_query</a>
				</span>
			  </td>
			  <td class=\"$style\">
				<span>
				$get_search_unique_counter
				</span>
			  </td>
			  <td class=\"$style\">
				<span>
				$get_search_number_of_results
				</span>
			  </td>
			  <td class=\"$style\">
				<span>
				$get_search_created_datetime_print
				</span>
			  </td>
			  <td class=\"$style\">
				<span>
				$get_search_updated_datetime_print
				</span>
			  </td>
			 </tr>";
		}
		


		echo"
		 </tbody>
		</table>
	<!-- //Searches -->

	<!-- Referers-->
		<h2>Referrers</h2>
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\" style=\"width: 40%;\">
			<span>From URL</span>
		   </th>
		   <th scope=\"col\" style=\"width: 30%;\">
			<span>To URL</span>
		   </th>
		   <th scope=\"col\" style=\"width: 30%;\">
			<span>Unique</span>
		   </th>
		   <th scope=\"col\" style=\"width: 30%;\">
			<span>Hits</span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>
		";

		$query = "SELECT stats_referer_id, stats_referer_year, stats_referer_from_url, stats_referer_to_url, stats_referer_unique, stats_referer_hits FROM $t_stats_referers_per_month WHERE stats_referer_month=$get_current_stats_visit_per_month_month AND stats_referer_year=$get_current_stats_visit_per_month_year AND stats_referer_language=$editor_language_mysql ORDER BY stats_referer_unique DESC LIMIT 0,30";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_stats_referer_id, $get_stats_referer_year, $get_stats_referer_from_url, $get_stats_referer_to_url, $get_stats_referer_unique, $get_stats_referer_hits) = $row;
			


			echo"
			 <tr>
			  <td>
				<span><a href=\"$get_stats_referer_from_url\">$get_stats_referer_from_url</a></span>
			  </td>
			  <td>
				<span><a href=\"$get_stats_referer_to_url\">$get_stats_referer_to_url</a></span>
			  </td>
			  <td>
				<span>$get_stats_referer_unique</span>
			  </td>
			  <td>
				<span>$get_stats_referer_hits</span>
			  </td>
			 </tr>
			";
		}
		echo"
		 </tbody>
		</table>
	<!-- //Referers-->


	<!-- Trackers -->
		<a id=\"trackers\"></a>
		<h2>Trackers</h2>
		<p>Trackers log the last visitors and how they use your site.</p>

		<!-- Select language -->
			<script>
			\$(function(){
				// bind change event to select
				\$('#inp_l').on('change', function () {
					var url = \$(this).val(); // get selected value
					if (url) { // require a URL
 						window.location = url; // redirect
					}
					return false;
				});
			});
			</script>

			<select id=\"inp_l\">
				<option value=\"index.php?open=dashboard&amp;page=statistics_month&amp;stats_year=$stats_year&amp;stats_month=$stats_month&amp;editor_language=$editor_language&amp;l=$l\">$l_editor_language</option>
				<option value=\"index.php?open=dashboard&amp;page=statistics_month&amp;stats_year=$stats_year&amp;stats_month=$stats_month&amp;editor_language=$editor_language&amp;l=$l\">-</option>\n";

				$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;

					// No language selected?
					if($editor_language == ""){
							$editor_language = "$get_language_active_iso_two";
					}
					echo"	<option value=\"index.php?open=dashboard&amp;page=statistics_year&amp;stats_year=$stats_year&amp;stats_month=$stats_month&amp;editor_language=$get_language_active_iso_two&amp;l=$l#trackers\"";if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
				}
			echo"
			</select>
			</p>
			</form>
		<!-- //Select language -->

		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>ID</span>
		   </th>
		   <th scope=\"col\">
			<span>Type</span>
		   </th>
		   <th scope=\"col\">
			<span>IP</span>
		   </th>
		   <th scope=\"col\">
			<span>Last URL</span>
		   </th>
		   <th scope=\"col\">
			<span>OS</span>
		   </th>
		   <th scope=\"col\">
			<span>Browser</span>
		   </th>
		   <th scope=\"col\">
			<span>Country</span>
		   </th>
		   <th scope=\"col\">
			<span>Accepted language</span>
		   </th>
		   <th scope=\"col\">
			<span>Language</span>
		   </th>
		   <th scope=\"col\">
			<span>Time spent</span>
		   </th>
		   <th scope=\"col\">
			<span>Hits</span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>
		";
		
		$query_t = "SELECT tracker_id, tracker_ip, tracker_ip_masked, tracker_hostname, tracker_start_day, tracker_start_month, tracker_start_month_short, tracker_start_year, tracker_start_time, tracker_start_hour_minute, tracker_last_day, tracker_last_month, tracker_last_month_short, tracker_last_year, tracker_last_time, tracker_last_hour_minute, tracker_seconds_spent, tracker_time_spent, tracker_user_agent, tracker_os, tracker_browser, tracker_type, tracker_country_name, tracker_accept_language, tracker_language, tracker_last_url_value, tracker_last_url_title, tracker_last_url_title_fetched, tracker_hits FROM $t_stats_tracker_index WHERE tracker_language=$editor_language_mysql ORDER BY tracker_id DESC LIMIT 0,300";
		$result_t = mysqli_query($link, $query_t);
		while($row_t = mysqli_fetch_row($result_t)) {
			list($get_tracker_id, $get_tracker_ip, $get_tracker_ip_masked, $get_tracker_hostname, $get_tracker_start_day, $get_tracker_start_month, $get_tracker_start_month_short, $get_tracker_start_year, $get_tracker_start_time, $get_tracker_start_hour_minute, $get_tracker_last_day, $get_tracker_last_month, $get_tracker_last_month_short, $get_tracker_last_year, $get_tracker_last_time, $get_tracker_last_hour_minute, $get_tracker_seconds_spent, $get_tracker_time_spent, $get_tracker_user_agent, $get_tracker_os, $get_tracker_browser, $get_tracker_type, $get_tracker_country_name, $get_tracker_accept_language, $get_tracker_language, $get_tracker_last_url_value, $get_tracker_last_url_title, $get_tracker_last_url_title_fetched, $get_tracker_hits) = $row_t;

			// Last URL
			if($get_tracker_last_url_title_fetched == "0"){
				$get_tracker_last_url_title = get_title($get_tracker_last_url_value);
				$get_tracker_last_url_title = output_html($get_tracker_last_url_title);
				if($get_tracker_last_url_title == ""){
					$get_tracker_last_url_title = "$get_tracker_last_url_value";
				}
				$inp_url_title_mysql = quote_smart($link, $get_tracker_last_url_title);

				mysqli_query($link, "UPDATE $t_stats_tracker_index SET 
								tracker_last_url_title=$inp_url_title_mysql, 
								tracker_last_url_title_fetched=1 WHERE tracker_id=$get_tracker_id") or die(mysqli_error());

			}

			// Hostname
			if($get_tracker_hostname == "-1"){
				// Hostname
				$inp_hostname = "$get_tracker_ip";
				if($configSiteUseGethostbyaddrSav == "1"){
					$inp_hostname =gethostbyaddr($get_tracker_ip); // Some servers in local network cant use getostbyaddr because of nameserver missing
				}
				$inp_hostname = output_html($inp_hostname);
				$inp_hostname_mysql = quote_smart($link, $inp_hostname);
				
				// Month
				if($get_tracker_start_month_short == "-1"){
					if($get_tracker_start_month == "01" OR $get_tracker_start_month == "1"){
						$get_tracker_start_month_short = "Jan";
					}
					elseif($get_tracker_start_month == "02" OR $get_tracker_start_month == "2"){
						$get_tracker_start_month_short = "Feb";
					}
					elseif($get_tracker_start_month == "03" OR $get_tracker_start_month == "3"){
						$get_tracker_start_month_short = "Mar";
					}
					elseif($get_tracker_start_month == "04" OR $get_tracker_start_month == "4"){
						$get_tracker_start_month_short = "Apr";
					}
					elseif($get_tracker_start_month == "05" OR $get_tracker_start_month == "5"){
						$get_tracker_start_month_short = "May";
					}
					elseif($get_tracker_start_month == "06" OR $get_tracker_start_month == "6"){
						$get_tracker_start_month_short = "Jun";
					}
					elseif($get_tracker_start_month == "07" OR $get_tracker_start_month == "7"){
						$get_tracker_start_month_short = "Jul";
					}
					elseif($get_tracker_start_month == "08" OR $get_tracker_start_month == "8"){
						$get_tracker_start_month_short = "Aug";
					}
					elseif($get_tracker_start_month == "09" OR $get_tracker_start_month == "9"){
						$get_tracker_start_month_short = "Sep";
					}
					elseif($get_tracker_start_month == "10"){
						$get_tracker_start_month_short = "Oct";
					}
					elseif($get_tracker_start_month == "11"){
						$get_tracker_start_month_short = "Nov";
					}
					elseif($get_tracker_start_month == "12"){
						$get_tracker_start_month_short = "Dec";
					}
				}

				// OS, Browser, Type, Country
				if($get_tracker_os == "-1"){
					// Find user agent. By looking for user agent we can know if it is human or bot
					$my_user_agent = "$get_tracker_user_agent";
					$my_user_agent_mysql = quote_smart($link, $my_user_agent);
					$query = "SELECT stats_user_agent_id, stats_user_agent_string, stats_user_agent_type, stats_user_agent_browser, stats_user_agent_browser_version, stats_user_agent_browser_icon, stats_user_agent_os, stats_user_agent_os_version, stats_user_agent_os_icon, stats_user_agent_bot, stats_user_agent_bot_icon, stats_user_agent_bot_website, stats_user_agent_banned FROM $t_stats_user_agents_index WHERE stats_user_agent_string=$my_user_agent_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_stats_user_agent_id, $get_stats_user_agent_string, $get_stats_user_agent_type, $get_stats_user_agent_browser, $get_stats_user_agent_browser_version, $get_stats_user_agent_browser_icon, $get_stats_user_agent_os, $get_stats_user_agent_os_version, $get_stats_user_agent_os_icon, $get_stats_user_agent_bot, $get_stats_user_agent_bot_icon, $get_stats_user_agent_bot_website, $get_stats_user_agent_banned) = $row;

					if($get_stats_user_agent_id == ""){
						include("_inc/dashboard/_stats/autoinsert_new_user_agent.php");

						$query = "SELECT stats_user_agent_id, stats_user_agent_string, stats_user_agent_type, stats_user_agent_browser, stats_user_agent_browser_version, stats_user_agent_browser_icon, stats_user_agent_os, stats_user_agent_os_version, stats_user_agent_os_icon, stats_user_agent_bot, stats_user_agent_bot_icon, stats_user_agent_bot_website, stats_user_agent_banned FROM $t_stats_user_agents_index WHERE stats_user_agent_string=$my_user_agent_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_stats_user_agent_id, $get_stats_user_agent_string, $get_stats_user_agent_type, $get_stats_user_agent_browser, $get_stats_user_agent_browser_version, $get_stats_user_agent_browser_icon, $get_stats_user_agent_os, $get_stats_user_agent_os_version, $get_stats_user_agent_os_icon, $get_stats_user_agent_bot, $get_stats_user_agent_bot_icon, $get_stats_user_agent_bot_website, $get_stats_user_agent_banned) = $row;
					}

					// Country :: Find my country based on IP
					// Country :: IP Type
					$ip_type = "";
					$get_ip_id = "";
					if (ip2long($get_tracker_ip) !== false) {
						$ip_type = "ipv4";

						$in_addr = inet_pton($get_tracker_ip);
						$in_addr_mysql = quote_smart($link, $in_addr);

						$query = "select * from $t_stats_ip_to_country_lookup_ipv4 where addr_type = '$ip_type' and ip_start <= $in_addr_mysql order by ip_start desc limit 1";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_ip_id, $get_addr_type, $get_ip_start, $get_ip_end, $get_country) = $row;
					} else if (preg_match('/^[0-9a-fA-F:]+$/', $get_tracker_ip) && @inet_pton($get_tracker_ip)) {
						$ip_type = "ipv6";

						$in_addr = inet_pton($get_tracker_ip);
						$in_addr_mysql = quote_smart($link, $in_addr);

						$query = "select * from $t_stats_ip_to_country_lookup_ipv6 where addr_type = '$ip_type' and ip_start <= $in_addr_mysql order by ip_start desc limit 1";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_ip_id, $get_addr_type, $get_ip_start, $get_ip_end, $get_country) = $row;
					}

					// echo"Type=$ip_type<br />";
					// echo"in_addr=$in_addr<br />";

					$get_tracker_country_name = "";
					$get_tracker_country_name_iso_two = "";
					if($get_ip_id != ""){
						$country_iso_two_mysql = quote_smart($link, $get_country);
						$query = "SELECT country_id, country_name, country_iso_two FROM $t_languages_countries WHERE country_iso_two=$country_iso_two_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_country_id, $get_tracker_country_name, $get_tracker_country_name_iso_two) = $row;
					}


					$get_tracker_os = "$get_stats_user_agent_os $get_stats_user_agent_os_version";
					$get_tracker_browser = "$get_stats_user_agent_browser $get_stats_user_agent_browser_version";
					$get_tracker_type = "$get_stats_user_agent_type";
				}

				$inp_os_mysql = quote_smart($link, $get_tracker_os);
				$inp_browser_mysql = quote_smart($link, $get_tracker_browser);
				$inp_type_mysql = quote_smart($link, $get_tracker_type);
				$inp_country_name_mysql = quote_smart($link, $get_tracker_country_name);

				mysqli_query($link, "UPDATE $t_stats_tracker_index SET 
								tracker_hostname=$inp_hostname_mysql,
								tracker_start_month_short='$get_tracker_start_month_short',
								tracker_os=$inp_os_mysql,
								tracker_browser=$inp_browser_mysql,
								tracker_type=$inp_type_mysql,
								tracker_country_name=$inp_country_name_mysql
								WHERE tracker_id=$get_tracker_id") or die(mysqli_error());
				
			}

			echo"
			 <tr>
			  <td>
				<span><a href=\"index.php?open=dashboard&amp;page=statistics_tracker&amp;tracker_id=$get_tracker_id&amp;editor_language=$editor_language&amp;l=$l\">$get_tracker_id</a></span>
			  </td>
			  <td>
				<span>$get_tracker_type</span>
			  </td>
			  <td>
				<span>$get_tracker_ip</span>
			  </td>
			  <td>
				<span><a href=\"$get_tracker_last_url_value\">$get_tracker_last_url_title</a></span>
			  </td>
			  <td>
				<span>$get_tracker_os</span>
			  </td>
			  <td>
				<span>$get_tracker_browser</span>
			  </td>
			  <td>
				<span>$get_tracker_country_name</span>
			  </td>
			  <td>
				<span>$get_tracker_accept_language</span>
			  </td>
			  <td>
				<span>$get_tracker_language</span>
			  </td>
			  <td>
				<span>$get_tracker_time_spent</span>
			  </td>
			  <td>
				<span>$get_tracker_hits</span>
			  </td>
			 </tr>
			";

		}
		echo"
		 </tbody>
		</table>
	<!-- //Trackers -->
	";
	
} // year found

?>