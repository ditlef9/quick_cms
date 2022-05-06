<?php
/**
*
* File: _admin/_inc/user_agents.php
* Version 2
* Date 20:54 27.04.2019
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Language -------------------------------------------------------------------------- */
include("_translations/admin/$l/dashboard/t_unknown_agents.php");


/*- Tables ------------------------------------------------------------------------ */
$t_stats_user_agents_index = $mysqlPrefixSav . "stats_user_agents_index";

/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['user_agent_id'])) {
	$user_agent_id = $_GET['user_agent_id'];
	$user_agent_id = strip_tags(stripslashes($user_agent_id));
}
else{
	$user_agent_id = "";
}

/*- Variables -------------------------------------------------------------------------- */

	echo"

	<h1>User Agents</h1>
	
	<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
	<!-- //Feedback -->

	<!-- Buttons -->
		<div class=\"tabs\">
			<ul>
				<li><a href=\"index.php?open=$open&amp;page=user_agents&amp;editor_language=$editor_language\">Unknown user agents</a></li>
				<li><a href=\"index.php?open=$open&amp;page=user_agents_desktop&amp;editor_language=$editor_language\" class=\"active\">Desktop</a></li>
				<li><a href=\"index.php?open=$open&amp;page=user_agents_mobile&amp;editor_language=$editor_language\">Mobile</a></li>
				<li><a href=\"index.php?open=$open&amp;page=user_agents_bots&amp;editor_language=$editor_language\">Bots</a></li>
				<li><a href=\"index.php?open=$open&amp;page=user_agents_export&amp;editor_language=$editor_language\">Export</a></li>
				<li><a href=\"index.php?open=$open&amp;page=user_agents_test&amp;editor_language=$editor_language\">Test</a></li>
			</ul>
		</div>
		<div class=\"clear\" style=\"height:10px;\"></div>
	<!-- //Buttons -->


	<!-- User agents desktop -->

		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span><b>Agent</b></span>
		   </td>
		   <th scope=\"col\" colspan=\"2\" style=\"text-align: center;\">
			<span><b>Browser</b></span>
		   </td>
		   <th scope=\"col\" colspan=\"2\" style=\"text-align: center;\">
			<span><b>OS</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Action</b></span>
		   </td>
		  </tr>
		 </thead>";
		$query = "SELECT stats_user_agent_id, stats_user_agent_string, stats_user_agent_type, stats_user_agent_browser, stats_user_agent_browser_version, stats_user_agent_browser_icon, stats_user_agent_os, stats_user_agent_os_version, stats_user_agent_os_icon, stats_user_agent_bot, stats_user_agent_bot_icon, stats_user_agent_bot_website, stats_user_agent_banned FROM $t_stats_user_agents_index WHERE stats_user_agent_type='desktop' ORDER BY stats_user_agent_string ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_stats_user_agent_id, $get_stats_user_agent_string, $get_stats_user_agent_type, $get_stats_user_agent_browser, $get_stats_user_agent_browser_version, $get_stats_user_agent_browser_icon, $get_stats_user_agent_os, $get_stats_user_agent_os_version, $get_stats_user_agent_os_icon, $get_stats_user_agent_bot, $get_stats_user_agent_bot_icon, $get_stats_user_agent_bot_website, $get_stats_user_agent_banned) = $row;

			// Style
			if(isset($style) && $style == ""){
				$style = "odd";
			}
			else{
				$style = "";
			}
			
			// Look for problems
			if($get_stats_user_agent_type == "desktop" OR $get_stats_user_agent_type == "mobile"){
				if($get_stats_user_agent_browser_version == "" OR $get_stats_user_agent_os_version == ""){
					$style = "danger";
				}
			}
				
		
			echo"
			 <tr>
			  <td class=\"$style\">
				<a id=\"#user_agent$get_stats_user_agent_id\"></a>
				<span>$get_stats_user_agent_string</span>
			  </td>
			  <td class=\"$style\">
				<span>$get_stats_user_agent_browser</span>
			  </td>
			  <td class=\"$style\">
				<span>$get_stats_user_agent_browser_version</span>
			  </td>
			  <td class=\"$style\">
				<span>$get_stats_user_agent_os</span>
			  </td>
			  <td class=\"$style\">
				<span>$get_stats_user_agent_os_version</span>
			  </td>
			  <td class=\"$style\">
				<span>
				<a href=\"index.php?open=$open&amp;page=user_agents_edit&amp;user_agent_id=$get_stats_user_agent_id&amp;referer_page=$page&amp;editor_language=$editor_language\">Edit</a>
				</span>
			  </td>
			 </tr>";

		}

			echo"
			</table>
		  </td>
		 </tr>
		</table>
	<!-- //User agents desktop -->
	";
	
?>