<?php
/**
*
* File: _admin/_inc/exercises/default.php
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

/*- Tables exercises ---------------------------------------------------------------- */
$t_exercise_liquidbase				= $mysqlPrefixSav . "exercise_liquidbase";
$t_exercise_index 				= $mysqlPrefixSav . "exercise_index";
$t_exercise_index_images			= $mysqlPrefixSav . "exercise_index_images";
$t_exercise_index_videos			= $mysqlPrefixSav . "exercise_index_videos";
$t_exercise_equipments 				= $mysqlPrefixSav . "exercise_equipments";
$t_exercise_equipments_translations 		= $mysqlPrefixSav . "exercise_equipments_translations";
$t_exercise_types				= $mysqlPrefixSav . "exercise_types";
$t_exercise_types_translations 			= $mysqlPrefixSav . "exercise_types_translations";
$t_exercise_levels				= $mysqlPrefixSav . "exercise_levels";
$t_exercise_levels_translations 		= $mysqlPrefixSav . "exercise_levels_translations";



/*- Check if setup is run ------------------------------------------------------------- */
$query = "SELECT * FROM $t_exercise_liquidbase LIMIT 1";
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



echo"
<h1>Exercises</h1>


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

<!-- Exercises buttons -->";
	// Navigation
	$query = "SELECT navigation_id FROM $t_pages_navigation WHERE navigation_url_path='exercises/index.php'";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_navigation_id) = $row;
	if($get_navigation_id == ""){
		echo"
		<p>
		<a href=\"index.php?open=pages&amp;page=navigation&amp;action=new_auto_insert&amp;module=exercises&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_default\">Create navigation</a>
		<p>
		";
	}
echo"
<!-- //Exercises buttons -->

<!-- Exercises menu -->
	<div class=\"vertical\">
		<ul>
			";
			include("_inc/exercises/menu.php");
			echo"
		</ul>
	</div>
<!-- //Exercises menu -->

";
?>