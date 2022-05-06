<?php
/**
*
* File: _admin/_inc/workout_plans/default.php
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



/*- Check if setup is run ------------------------------------------------------------- */
$t_workout_plans_liquidbase	= $mysqlPrefixSav . "workout_plans_liquidbase";
$t_exercise_liquidbase		= $mysqlPrefixSav . "exercise_liquidbase";
$query = "SELECT * FROM $t_workout_plans_liquidbase LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	$query = "SELECT * FROM $t_exercise_liquidbase LIMIT 1";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		echo"
		<h1>Workout plans</h1>

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


		<!-- Workout plans buttons -->";
		// Navigation
		$query = "SELECT navigation_id FROM $t_pages_navigation WHERE navigation_url_path='workout_plans/index.php'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_navigation_id) = $row;
		if($get_navigation_id == ""){
			echo"
			<p>
			<a href=\"index.php?open=pages&amp;page=navigation&amp;action=new_auto_insert&amp;module=workout_plans&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_default\">Create navigation</a>
			</p>
			";
		}
		echo"
		<!-- //Workout plans buttons -->

		<!-- Workout plan menu -->
		<div class=\"vertical\">
		<ul>
			";
			include("_inc/workout_plans/menu.php");
			echo"
		</ul>
		</div>
		<!-- //Workout plan menu -->
		";
	}
	else{
		echo"
		<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Running setup for exercises</p></div>
		<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=exercises&amp;page=tables&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
		";
	} // setup has not runned
}
else{
	echo"
	<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Running setup workout plans</p></div>
	<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=$open&amp;page=tables&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
	";
} // setup has not runned
?>