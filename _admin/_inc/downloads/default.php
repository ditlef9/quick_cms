<?php
/**
*
* File: _admin/_inc/downloads/default.php
* Version 2
* Copyright (c) 2008-2023 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Check if setup is run ------------------------------------------------------------- */
$t_downloads_liquidbase		= $mysqlPrefixSav . "downloads_liquidbase";
$t_ads_index 				= $mysqlPrefixSav . "ads_index";

$liquidbase_exists = false;
$ads_index_exists = false;
$query = "SHOW TABLES";
$result = $mysqli->query($query);
if($result !== false) {
	if($result->num_rows > 0) {
		while($row = $result->fetch_row()) {
			if($row[0] == "$t_downloads_liquidbase"){
				$liquidbase_exists = true;
			}
			if($row[0] == "$t_ads_index"){
				$ads_index_exists = true;
			}
		}
  	}
}
else echo "Error Unable to check tables " . $mysqli->error;

if(!($liquidbase_exists)){
	echo"
	<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Running downloads setup</p></div>
	<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=$open&amp;page=tables&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
	";
} // setup has not runned

if(!($ads_index_exists)){
	echo"
	<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Running ads setup</p></div>
	<meta http-equiv=\"refresh\" content=\"2;url=index.php?open=ads&amp;page=tables&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
	";
} // setup has not runned

/*- Scriptstart ---------------------------------------------------------------------- */
echo"

<h1>Downloads</h1>

<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		else{
			$fm = ucfirst($fm);
			$fm = str_replace("_", " ", $fm);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
<!-- //Feedback -->

<!-- Downloads menu buttons -->
	<p>";

	// Navigation
	$query = "SELECT navigation_id FROM $t_pages_navigation WHERE navigation_url_path='downloads/index.php'";
	$result = $mysqli->query($query);
	$row = $result->fetch_row();
	if($get_navigation_id == ""){
		echo"
		<a href=\"index.php?open=pages&amp;page=navigation&amp;action=new_auto_insert&amp;module=downloads&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_default\">Create navigation</a>
		";
	}
	echo"
	</p>
<!-- //Downloads menu buttons -->

<div class=\"vertical\">
	<ul>
		<li><a href=\"index.php?open=$open&amp;page=downloads&amp;l=$l&amp;editor_language=$editor_language\">Downloads</a></li>
		<li><a href=\"index.php?open=$open&amp;page=new_download&amp;l=$l&amp;editor_language=$editor_language\">New download</a></li>
		<li><a href=\"index.php?open=$open&amp;page=categories&amp;l=$l&amp;editor_language=$editor_language\">Categories</a></li>
		<li><a href=\"index.php?open=$open&amp;page=scan_for_new_files&amp;l=$l&amp;editor_language=$editor_language\">Scan for new files</a></li>
		<li><a href=\"index.php?open=$open&amp;page=tables&amp;l=$l&amp;editor_language=$editor_language\">Tables</a></li>
		<li><a href=\"index.php?open=$open&amp;page=backup&amp;l=$l&amp;editor_language=$editor_language\">Backup</a></li>
	</ul>
</div>
";
?>