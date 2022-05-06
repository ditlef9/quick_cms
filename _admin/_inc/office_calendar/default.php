<?php
/**
*
* File: _admin/_inc/office_calendar/default.php
* Version 
* Date 19:59 02.08.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Check if setup is run ------------------------------------------------------------- */
$t_office_calendar_liquidbase	= $mysqlPrefixSav . "office_calendar_liquidbase";
$query = "SELECT * FROM $t_office_calendar_liquidbase LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){

}
else{
	echo"
	<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Running setup</p></div>
	<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=$open&amp;page=tables&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
	";
} // setup has not runned



/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;


if($action == ""){
	echo"
	<h1>Office calendar</h1>
				

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



	<!-- Office calendar buttons -->";
		// Navigation
		$query = "SELECT navigation_id FROM $t_pages_navigation WHERE navigation_url_path='office_calendar/index.php'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_navigation_id) = $row;
		if($get_navigation_id == ""){
			echo"
			<p>
			<a href=\"index.php?open=pages&amp;page=navigation&amp;action=new_auto_insert&amp;module=office_calendar&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_default\">Create navigation</a>
			</p>
			";
		}
		echo"
	<!-- //Office calendar buttons -->

	<!-- Office calendar menu -->
	<div class=\"vertical\">
		<ul>
			";
			include("_inc/office_calendar/menu.php");
			echo"
		</ul>
	</div>
	<!-- //Office calendar menu -->
	";
}
?>