<?php
/**
*
* File: _admin/_inc/workout_diary/default.php
* Version 15.00 03.03.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Functions ----------------------------------------------------------------------- */


/*- Variables ------------------------------------------------------------------------ */

/*- Tables ---------------------------------------------------------------------------- */
$t_workout_diary_liquidbase 	 = $mysqlPrefixSav . "workout_diary_liquidbase";
$t_workout_plans_liquidbase 	 = $mysqlPrefixSav . "workout_plans_liquidbase";


/*- Check if setup is run ------------------------------------------------------------- */
$query = "SELECT * FROM $t_workout_diary_liquidbase LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){

	$query = "SELECT * FROM $t_workout_plans_liquidbase LIMIT 1";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){

		echo"
		<h1>Workout diary</h1>

		<!-- Feedback -->
		";
		if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		else{
			$fm = str_replace("_", " ", $fm);
			$fm = ucfirst($fm);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->
	
		<!-- Workout diary buttons -->";
		// Navigation
		$query = "SELECT navigation_id FROM $t_pages_navigation WHERE navigation_url_path='workout_diary/index.php'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_navigation_id) = $row;
		if($get_navigation_id == ""){
			echo"
			<p>
			<a href=\"index.php?open=pages&amp;page=navigation&amp;action=new_auto_insert&amp;module=workout_diary&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_default\">Create navigation</a>
			</p>
			";
		}
		echo"
		<!-- //Workout diary buttons -->
		";
	}
	else{
		echo"
		<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Installing Workout plans</p></div>
		<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=workout_plans&amp;page=tables&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
		";

	}

}
else{
	echo"
	<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Running setup</p></div>
	<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=$open&amp;page=tables&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
	";
} // setup has not runned
?>