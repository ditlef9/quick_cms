<?php
if(isset($_SESSION['admin_user_id'])){


	$t_stats_user_agents_index = $mysqlPrefixSav . "stats_user_agents_index";

	mysqli_query($link,"DROP TABLE IF EXISTS $t_stats_user_agents_index") or die(mysqli_error());


	// Stats :: User agents
	$query = "SELECT * FROM $t_stats_user_agents_index LIMIT 1";
	$result = mysqli_query($link, $query);

	if($result !== FALSE){
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_stats_user_agents_index(
					stats_user_agent_id INT NOT NULL AUTO_INCREMENT,
					PRIMARY KEY(stats_user_agent_id), 
					stats_user_agent_string VARCHAR(500),
					stats_user_agent_type VARCHAR(250),
					stats_user_agent_browser VARCHAR(250),
					stats_user_agent_browser_version VARCHAR(25),
					stats_user_agent_browser_icon VARCHAR(250),
					stats_user_agent_os VARCHAR(250),
					stats_user_agent_os_version VARCHAR(25),
					stats_user_agent_os_icon VARCHAR(250),
					stats_user_agent_bot VARCHAR(250),
					stats_user_agent_bot_version VARCHAR(25),
					stats_user_agent_bot_icon VARCHAR(250),
					stats_user_agent_bot_website VARCHAR(250),
					stats_user_agent_banned INT)")
					or die(mysqli_error($link));
	}

}
?>