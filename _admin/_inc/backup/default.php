<?php
/**
*
* File: _admin/_inc/backup/default.php
* Version 20:18 12.01.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Functions ----------------------------------------------------------------------- */


/*- Scriptsrat ------------------------------------------------------------------------ */


/*- Check if setup is run ------------------------------------------------------------- */
$t_backup_liquidbase	= $mysqlPrefixSav . "backup_liquidbase";

$query = "SELECT * FROM $t_backup_liquidbase LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){

	echo"
	<h1>Backup</h1>


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

	<!-- Backup menu -->
	<div class=\"vertical\">
		<ul>
			";
			include("_inc/backup/menu.php");
			echo"
		</ul>
	</div>
	<!-- //Backup menu -->

	";
}
else{
	echo"
	<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Running setup for backup</p></div>
	<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=$open&amp;page=tables&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
	";
} // setup has not runned
?>