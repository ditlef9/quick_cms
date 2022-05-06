<?php
/**
*
* File: _admin/_inc/user_agents_test.php
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

/*- Functions -------------------------------------------------------------------------- */
include("_functions/get_between.php");


/*- Tables ------------------------------------------------------------------------ */
$t_stats_user_agents_index = $mysqlPrefixSav . "stats_user_agents_index";

/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['user_agent'])) {
	$user_agent = $_GET['user_agent'];
	$user_agent = output_html($user_agent);
}
else{
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$user_agent = output_html($user_agent);
}


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
				<li><a href=\"index.php?open=$open&amp;page=user_agents_desktop&amp;editor_language=$editor_language\">Desktop</a></li>
				<li><a href=\"index.php?open=$open&amp;page=user_agents_mobile&amp;editor_language=$editor_language\">Mobile</a></li>
				<li><a href=\"index.php?open=$open&amp;page=user_agents_bots&amp;editor_language=$editor_language\">Bots</a></li>
				<li><a href=\"index.php?open=$open&amp;page=user_agents_export&amp;editor_language=$editor_language\">Export</a></li>
				<li><a href=\"index.php?open=$open&amp;page=user_agents_test&amp;editor_language=$editor_language\" class=\"active\">Test</a></li>
			</ul>
		</div>
		<div class=\"clear\" style=\"height:10px;\"></div>
<!-- //Buttons -->



<!-- User agents test -->	
	<p>
	This form will test the PHP script autoinsert_new_user_agent.php.
	The script takes in a user agent string and returns Bot or OS and Browser.
	</p>
	";

	if($action == "test"){
		
	}
	
	echo"
			<!-- Focus -->
				<script>
				window.onload = function() {
					document.getElementById(\"inp_browser\").focus();
				}
				</script>
			<!-- //Focus -->
	
	<form method=\"get\" action=\"index.php\" enctype=\"multipart/form-data\">

	<p><b>User agent</b><br />
	<input type=\"hidden\" name=\"open\" value=\"$open\" />
	<input type=\"hidden\" name=\"page\" value=\"$page\" />
	<input type=\"hidden\" name=\"editor_language\" value=\"$editor_language\" />
	<input type=\"hidden\" name=\"l\" value=\"$l\" />
	<input type=\"text\" name=\"user_agent\" size=\"20\" value=\"$user_agent\" style=\"width: 100%;\" />
	</p>
	<p>
	<input type=\"submit\" value=\"Find\" class=\"btn_default\" />
	</p>
	</form>

	";
	// Find agent
	$get_stats_user_agent_id = "";
	$my_user_agent = "$user_agent";
	$test = 1;
	
	include("_inc/dashboard/_stats/autoinsert_new_user_agent.php");
	echo"

<!-- //User agents test -->
";
?>