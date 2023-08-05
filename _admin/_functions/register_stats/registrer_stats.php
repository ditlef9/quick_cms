<?php
/**
*
* File: _admin/_functions/registrer_stats.php
* Version 2.0.0
* Date 09:46 15.10.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
* We have the following tables for statistics:
*    q_stats_accepted_languages_per_month
*    q_stats_accepted_languages_per_year
*    q_stats_bots_per_month
*    q_stats_bots_per_year
*    q_stats_browsers_per_month
*    q_stats_browsers_per_year
*    q_stats_comments_per_month
*    q_stats_comments_per_week-
*    q_stats_comments_per_year
*    q_stats_countries_per_month
*    q_stats_countries_per_year
*    q_stats_ip_to_country_lookup_ipv4
*    q_stats_ip_to_country_lookup_ipv6
*    q_stats_languages_per_month
*    q_stats_languages_per_year
*    q_stats_os_per_month
*    q_stats_os_per_year
*    q_stats_pages_visits_per_year
*    q_stats_pages_visits_per_year_ips
*    q_stats_referers_per_month
*    q_stats_referers_per_year
*    q_stats_tracker_index
*    q_stats_tracker_urls
*    q_stats_unprocessed
*    q_stats_users_registered_per_month
*    q_stats_users_registered_per_week
*    q_stats_users_registered_per_year
*    q_stats_user_agents_index
*    q_stats_visists_per_day
*    q_stats_visists_per_day_ips
*    q_stats_visists_per_month
*    q_stats_visists_per_month_ips
*    q_stats_visists_per_week
*    q_stats_visists_per_week_ips
*    q_stats_visists_per_year
*    q_stats_visists_per_year_ips 
*
* Our routine is the following:
* a) Select from User Agents (q_stats_user_agents_index) (if not exists, then create and email that there is a new agent)
*  -> Human mobile, human desktop or bot?
* b) Bots
* c) Humans:
*  -> Accepted_languages
*  -> Browsers
*  -> Countries
*  -> Languages
*  -> OS
*  -> Pages
*  -> Referers
*  -> Visits
*/


/*- Tables ---------------------------------------------------------------------------------- */
$t_stats_unprocessed 	   		= $mysqlPrefixSav . "stats_unprocessed";
$t_stats_accepted_languages_per_month	= $mysqlPrefixSav . "stats_accepted_languages_per_month";
$t_stats_accepted_languages_per_year	= $mysqlPrefixSav . "stats_accepted_languages_per_year";

$t_stats_browsers_per_month	= $mysqlPrefixSav . "stats_browsers_per_month";
$t_stats_browsers_per_year	= $mysqlPrefixSav . "stats_browsers_per_year";

$t_stats_comments_per_month 	= $mysqlPrefixSav . "stats_comments_per_month";
$t_stats_comments_per_year 	= $mysqlPrefixSav . "stats_comments_per_year";

$t_stats_countries_per_year  = $mysqlPrefixSav . "stats_countries_per_year";
$t_stats_countries_per_month = $mysqlPrefixSav . "stats_countries_per_month";

$t_stats_ip_to_country_lookup_ipv4 = $mysqlPrefixSav . "stats_ip_to_country_lookup_ipv4";
$t_stats_ip_to_country_lookup_ipv6 = $mysqlPrefixSav . "stats_ip_to_country_lookup_ipv6";

$t_languages_countries	      = $mysqlPrefixSav . "languages_countries";

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

$t_stats_pages_visits_per_year		= $mysqlPrefixSav . "stats_pages_visits_per_year";
$t_stats_pages_visits_per_year_ips 	= $mysqlPrefixSav . "stats_pages_visits_per_year_ips";

$t_stats_visists_per_day 	= $mysqlPrefixSav . "stats_visists_per_day";
$t_stats_visists_per_day_ips 	= $mysqlPrefixSav . "stats_visists_per_day_ips";
$t_stats_visists_per_week 	= $mysqlPrefixSav . "stats_visists_per_week";
$t_stats_visists_per_week_ips 	= $mysqlPrefixSav . "stats_visists_per_week_ips";
$t_stats_visists_per_month 	= $mysqlPrefixSav . "stats_visists_per_month";
$t_stats_visists_per_month_ips 	= $mysqlPrefixSav . "stats_visists_per_month_ips";
$t_stats_visists_per_year 	= $mysqlPrefixSav . "stats_visists_per_year";
$t_stats_visists_per_year_ips 	= $mysqlPrefixSav . "stats_visists_per_year_ips";

$t_stats_tracker_index = $mysqlPrefixSav . "stats_tracker_index";
$t_stats_tracker_urls  = $mysqlPrefixSav . "stats_tracker_urls";

$t_languages_active  = $mysqlPrefixSav . "languages_active";


/*- My variables ------------------------------------------------------------------- */
$my_user_agent = $_SERVER['HTTP_USER_AGENT'];
$my_user_agent = output_html($my_user_agent);
if($my_user_agent == ""){ echo"406 Not Acceptable"; die; }

$my_ip = $_SERVER['REMOTE_ADDR'];
$my_ip = output_html($my_ip);


/*- Banned IP? ------------------------------------------------------------------- */
// Check if banned IP
$stmt = $mysqli->prepare("SELECT banned_ip_id FROM $t_banned_ips WHERE banned_ip=?"); 
$stmt->bind_param("s", $my_ip);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_banned_ip_id) = $row;
if($get_banned_ip_id != ""){
	echo"Server error 403 - Your IP is banned";	
	die;
}


/*- Start register stats ------------------------------------------------------------------- */
// A) Find user Agent
include("$root/_admin/_functions/register_stats/a_find_user_agent.php");


// Create _stats_visists_per_day 
include("$root/_admin/_functions/register_stats/c_create_stats_visists_per_day.php");

// Bot
if($sql_stats_user_agent_type == "bot"){
    include("$root/_admin/_functions/register_stats/g_register_bots.php");
}
else{
    // Visits
    include("$root/_admin/_functions/register_stats/h_register_human_visits_per_day.php");
	

} // not bot

echo"Ok stats!";
die;



?>