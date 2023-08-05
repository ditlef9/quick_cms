<?php
/* Find user agent 
*/



$stmt = $mysqli->prepare("SELECT stats_user_agent_id, stats_user_agent_string, stats_user_agent_type, stats_user_agent_browser, stats_user_agent_browser_version, stats_user_agent_browser_icon, stats_user_agent_os, stats_user_agent_os_version, stats_user_agent_os_icon, stats_user_agent_bot, stats_user_agent_bot_version, stats_user_agent_bot_icon, stats_user_agent_bot_website, stats_user_agent_banned FROM $t_stats_user_agents_index WHERE stats_user_agent_string=?"); 
$stmt->bind_param("s", $my_user_agent);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($sql_stats_user_agent_id, $sql_stats_user_agent_string, $sql_stats_user_agent_type, $sql_stats_user_agent_browser, $sql_stats_user_agent_browser_version, $sql_stats_user_agent_browser_icon, $sql_stats_user_agent_os, $sql_stats_user_agent_os_version, $sql_stats_user_agent_os_icon, $sql_stats_user_agent_bot, $sql_stats_user_agent_bot_version, $sql_stats_user_agent_bot_icon, $sql_stats_user_agent_bot_website, $sql_stats_user_agent_banned) = $row;
if($sql_stats_user_agent_id == ""){
    // New user agent (inserts new user agent)
    include("$root/_admin/_functions/register_stats/b_insert_new_user_agent.php");

    // Find user agent 
    $stmt = $mysqli->prepare("SELECT stats_user_agent_id, stats_user_agent_string, stats_user_agent_type, stats_user_agent_browser, stats_user_agent_browser_version, stats_user_agent_browser_icon, stats_user_agent_os, stats_user_agent_os_version, stats_user_agent_os_icon, stats_user_agent_bot, stats_user_agent_bot_version, stats_user_agent_bot_icon, stats_user_agent_bot_website, stats_user_agent_banned FROM $t_stats_user_agents_index WHERE stats_user_agent_string=?"); 
    $stmt->bind_param("s", $my_user_agent);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_row();
    list($sql_stats_user_agent_id, $sql_stats_user_agent_string, $sql_stats_user_agent_type, $sql_stats_user_agent_browser, $sql_stats_user_agent_browser_version, $sql_stats_user_agent_browser_icon, $sql_stats_user_agent_os, $sql_stats_user_agent_os_version, $sql_stats_user_agent_os_icon, $sql_stats_user_agent_bot, $sql_stats_user_agent_bot_version, $sql_stats_user_agent_bot_icon, $sql_stats_user_agent_bot_website, $sql_stats_user_agent_banned) = $row;

}

?>