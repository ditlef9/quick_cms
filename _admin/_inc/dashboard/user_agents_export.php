<?php
/**
*
* File: _admin/_inc/user_agents_export.php
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
function delete_directory($dirname) {
	if (is_dir($dirname))
		$dir_handle = opendir($dirname);
		if (!$dir_handle)
			return false;
		while($file = readdir($dir_handle)) {
			if ($file != "." && $file != "..") {
				if (!is_dir($dirname."/".$file))
				unlink($dirname."/".$file);
              		else
                    		delete_directory($dirname.'/'.$file);
        	}
     	}
	closedir($dir_handle);
	rmdir($dirname);
	return true;
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
				<li><a href=\"index.php?open=$open&amp;page=user_agents_desktop&amp;editor_language=$editor_language\">Desktop</a></li>
				<li><a href=\"index.php?open=$open&amp;page=user_agents_mobile&amp;editor_language=$editor_language\">Mobile</a></li>
				<li><a href=\"index.php?open=$open&amp;page=user_agents_bots&amp;editor_language=$editor_language\">Bots</a></li>
				<li><a href=\"index.php?open=$open&amp;page=user_agents_export&amp;editor_language=$editor_language\" class=\"active\">Export</a></li>
				<li><a href=\"index.php?open=$open&amp;page=user_agents_test&amp;editor_language=$editor_language\">Test</a></li>
			</ul>
		</div>
		<div class=\"clear\" style=\"height:10px;\"></div>
	<!-- //Buttons -->


<!-- Files -->
	<p>Files:</p>

	<ul>
		
";

// Make dir
if(!(is_dir("_liquidbase/db_scripts/stats/user_agents"))){
	mkdir("_liquidbase/db_scripts/stats/user_agents");
}

// Truncate dir
delete_directory("_liquidbase/db_scripts/stats/user_agents");
if(!(is_dir("_liquidbase/db_scripts/stats/user_agents"))){
	mkdir("_liquidbase/db_scripts/stats/user_agents");
}

$files_counter = 0;
$rows_in_file_counter = 0;
$query = "SELECT stats_user_agent_id, stats_user_agent_string, stats_user_agent_type, stats_user_agent_browser, stats_user_agent_browser_version, stats_user_agent_browser_icon, stats_user_agent_os, stats_user_agent_os_version, stats_user_agent_os_icon, stats_user_agent_bot, stats_user_agent_bot_version, stats_user_agent_bot_icon, stats_user_agent_bot_website, stats_user_agent_banned FROM $t_stats_user_agents_index ORDER BY stats_user_agent_string ASC";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_row($result)) {
	list($get_stats_user_agent_id, $get_stats_user_agent_string, $get_stats_user_agent_type, $get_stats_user_agent_browser, $get_stats_user_agent_browser_version, $get_stats_user_agent_browser_icon, $get_stats_user_agent_os, $get_stats_user_agent_os_version, $get_stats_user_agent_os_icon, $get_stats_user_agent_bot, $get_stats_user_agent_bot_version, $get_stats_user_agent_bot_icon, $get_stats_user_agent_bot_website, $get_stats_user_agent_banned) = $row;

	$inp_string_mysql 	= quote_smart($link, $get_stats_user_agent_string);
	$inp_type_mysql 	= quote_smart($link, $get_stats_user_agent_type);
	$inp_browser_mysql 	= quote_smart($link, $get_stats_user_agent_browser);
	$inp_browser_version_mysql = quote_smart($link, $get_stats_user_agent_browser_version);
	$inp_browser_icon_mysql = quote_smart($link, $get_stats_user_agent_browser_icon);
	$inp_os_mysql 		= quote_smart($link, $get_stats_user_agent_os);
	$inp_os_version_mysql 	= quote_smart($link, $get_stats_user_agent_os_version);
	$inp_os_icon_mysql 	= quote_smart($link, $get_stats_user_agent_os_icon);
	$inp_bot_mysql 		= quote_smart($link, $get_stats_user_agent_bot);
	$inp_bot_version_mysql 	= quote_smart($link, $get_stats_user_agent_bot_version);
	$inp_bot_icon_mysql	= quote_smart($link, $get_stats_user_agent_bot_icon);
	$inp_bot_website_mysql 	= quote_smart($link, $get_stats_user_agent_bot_website);
	$inp_banned_mysql 	= quote_smart($link, $get_stats_user_agent_banned);


	/*
	TEXT FILES:

	if($rows_in_file_counter == "0"){
		$input = "(NULL, $inp_string_mysql, $inp_type_mysql, $inp_browser_mysql, $inp_browser_version_mysql, $inp_browser_icon_mysql, $inp_os_mysql, $inp_os_version_mysql, $inp_os_icon_mysql, $inp_bot_mysql, $inp_bot_version_mysql, $inp_bot_icon_mysql, $inp_bot_website_mysql, $inp_banned_mysql)";
	}
	else{
		$input = $input . ",
(NULL, $inp_string_mysql, $inp_type_mysql, $inp_browser_mysql, $inp_browser_version_mysql, $inp_browser_icon_mysql, $inp_os_mysql, $inp_os_version_mysql, $inp_os_icon_mysql, $inp_bot_mysql, $inp_bot_version_mysql, $inp_bot_icon_mysql, $inp_bot_website_mysql, $inp_banned_mysql)";
	}

	// Write
	if($rows_in_file_counter == "1000"){
		echo"
		<li><a href=\"_liquidbase/db_scripts/stats/user_agents/$files_counter.txt\">_liquidbase/db_scripts/stats/user_agents/$files_counter.txt</a></li>
		";
		$fh = fopen("_liquidbase/db_scripts/stats/user_agents/$files_counter.txt", "w+") or die("can not open file");
		fwrite($fh, $input);
		fclose($fh);

		$input = "";
		$rows_in_file_counter = -1;
		$files_counter++;
	}
	*/


	// Array files:
	if($rows_in_file_counter == "0"){
		$input = "<?php
\$user_agents_index = array(
array('stats_user_agent_string' => '$get_stats_user_agent_string','stats_user_agent_type' => '$get_stats_user_agent_type','stats_user_agent_browser' => '$get_stats_user_agent_browser','stats_user_agent_browser_version' => '$get_stats_user_agent_browser_version','stats_user_agent_browser_icon' => '$get_stats_user_agent_browser_icon','stats_user_agent_os' => '$get_stats_user_agent_os', 'stats_user_agent_os_version' => '$get_stats_user_agent_os_version', 'stats_user_agent_os_icon' => '$get_stats_user_agent_os_icon', 'stats_user_agent_bot' => '$get_stats_user_agent_bot', 'stats_user_agent_bot_version' => '$get_stats_user_agent_bot_version', 'stats_user_agent_bot_icon' => '$get_stats_user_agent_bot_icon', 'stats_user_agent_bot_website' => '$get_stats_user_agent_bot_website', 'stats_user_agent_banned' => '$get_stats_user_agent_banned')";
	}
	else{
$input = $input . ",
array('stats_user_agent_string' => '$get_stats_user_agent_string','stats_user_agent_type' => '$get_stats_user_agent_type','stats_user_agent_browser' => '$get_stats_user_agent_browser','stats_user_agent_browser_version' => '$get_stats_user_agent_browser_version','stats_user_agent_browser_icon' => '$get_stats_user_agent_browser_icon','stats_user_agent_os' => '$get_stats_user_agent_os', 'stats_user_agent_os_version' => '$get_stats_user_agent_os_version', 'stats_user_agent_os_icon' => '$get_stats_user_agent_os_icon', 'stats_user_agent_bot' => '$get_stats_user_agent_bot', 'stats_user_agent_bot_version' => '$get_stats_user_agent_bot_version', 'stats_user_agent_bot_icon' => '$get_stats_user_agent_bot_icon', 'stats_user_agent_bot_website' => '$get_stats_user_agent_bot_website', 'stats_user_agent_banned' => '$get_stats_user_agent_banned')";
	}

	$rows_in_file_counter++;


} // while

// footer
$input = $input . "
);
?>
";


// Write last
echo"
	<li><a href=\"_liquidbase/db_scripts/stats/user_agents/$files_counter.php\">_liquidbase/db_scripts/stats/user_agents/$files_counter.php</a></li>
";
$fh = fopen("_liquidbase/db_scripts/stats/user_agents/$files_counter.php", "w+") or die("can not open file");
fwrite($fh, $input);
fclose($fh);


echo"
</ul>
<p>Exported!</p>
<!-- //Files -->
";
?>