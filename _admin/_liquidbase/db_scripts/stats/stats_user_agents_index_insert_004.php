<?php
if(isset($_SESSION['admin_user_id'])){


	$t_stats_user_agents_index = $mysqlPrefixSav . "stats_user_agents_index";

	// Insert...
	include("../_liquidbase/db_scripts/stats/user_agents/0.php");

	foreach ($user_agents_index as $u){
		$inp_string_mysql = quote_smart($link, $u['stats_user_agent_string']);
		$inp_type_mysql = quote_smart($link, $u['stats_user_agent_type']);

		$inp_browser_mysql = quote_smart($link, $u['stats_user_agent_browser']);
		$inp_browser_version_mysql = quote_smart($link, $u['stats_user_agent_browser_version']);
		$inp_browser_icon_mysql = quote_smart($link, $u['stats_user_agent_browser_icon']);

		$inp_os_mysql = quote_smart($link, $u['stats_user_agent_os']);
		$inp_os_version_mysql = quote_smart($link, $u['stats_user_agent_os_version']);
		$inp_os_icon_mysql = quote_smart($link, $u['stats_user_agent_os_icon']);

		$inp_bot_mysql = quote_smart($link, $u['stats_user_agent_bot']);
		$inp_bot_version_mysql = quote_smart($link, $u['stats_user_agent_bot_version']);
		$inp_bot_icon_mysql = quote_smart($link, $u['stats_user_agent_bot_icon']);
		$inp_bot_website_mysql = quote_smart($link, $u['stats_user_agent_bot_website']);

		$inp_banned_mysql = quote_smart($link, $u['stats_user_agent_banned']);

		// Check if exists
		$query = "SELECT stats_user_agent_id FROM $t_stats_user_agents_index WHERE stats_user_agent_string=$inp_string_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_stats_user_agent_id) = $row;
		if($get_stats_user_agent_id == ""){
			mysqli_query($link, "INSERT INTO $t_stats_user_agents_index (`stats_user_agent_id`, `stats_user_agent_string`, `stats_user_agent_type`, `stats_user_agent_browser`, `stats_user_agent_browser_version`, `stats_user_agent_browser_icon`, `stats_user_agent_os`, `stats_user_agent_os_version`, `stats_user_agent_os_icon`, `stats_user_agent_bot`, `stats_user_agent_bot_version`, `stats_user_agent_bot_icon`, `stats_user_agent_bot_website`, `stats_user_agent_banned`) VALUES
			(NULL, $inp_string_mysql, $inp_type_mysql, $inp_browser_mysql, $inp_browser_version_mysql, $inp_browser_icon_mysql, $inp_os_mysql, $inp_os_version_mysql, $inp_os_icon_mysql, $inp_bot_mysql, $inp_bot_version_mysql, $inp_bot_icon_mysql, $inp_bot_website_mysql, $inp_banned_mysql)")
		   	or die(mysqli_error());
		}
		else{
			// Update
			mysqli_query($link, "UPDATE $t_stats_user_agents_index SET
						stats_user_agent_string=$inp_string_mysql,
						stats_user_agent_type=$inp_type_mysql, 
						stats_user_agent_browser=$inp_browser_mysql, 
						stats_user_agent_browser_version=$inp_browser_version_mysql, 
						stats_user_agent_browser_icon=$inp_browser_icon_mysql, 
						stats_user_agent_os=$inp_os_mysql, 
						stats_user_agent_os_version=$inp_os_version_mysql, 
						stats_user_agent_os_icon=$inp_os_icon_mysql, 
						stats_user_agent_bot=$inp_bot_mysql, 
						stats_user_agent_bot_version=$inp_bot_version_mysql, 
						stats_user_agent_bot_icon=$inp_bot_icon_mysql, 
						stats_user_agent_bot_website=$inp_bot_website_mysql, 
						stats_user_agent_banned=$inp_banned_mysql
						WHERE stats_user_agent_id=$get_stats_user_agent_id")
		   				or die(mysqli_error());

		}
	}

}
?>