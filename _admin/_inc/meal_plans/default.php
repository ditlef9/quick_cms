<?php
/**
*
* File: _admin/_inc/meal_plans/default.php
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
$t_meal_plans_liquidbase 		= $mysqlPrefixSav . "meal_plans_liquidbase";
$t_meal_plans 		= $mysqlPrefixSav . "meal_plans";
$t_meal_plans_days	= $mysqlPrefixSav . "meal_plans_days";
$t_meal_plans_meals	= $mysqlPrefixSav . "meal_plans_meals";
$t_meal_plans_entries	= $mysqlPrefixSav . "meal_plans_entries";

/*- Check if setup is run ------------------------------------------------------------- */
$query = "SELECT * FROM $t_meal_plans_liquidbase LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	echo"
	<h1>Meal plans</h1>
	
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

	<!-- Meal plans buttons -->";
		// Navigation
		$query = "SELECT navigation_id FROM $t_pages_navigation WHERE navigation_url_path='meal_plans/index.php'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_navigation_id) = $row;
		if($get_navigation_id == ""){
			echo"
			<p>
			<a href=\"index.php?open=pages&amp;page=navigation&amp;action=new_auto_insert&amp;module=meal_plans&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_default\">Create navigation</a>
			</p>
			";
		}
	echo"
	<!-- //Meal plans buttons -->

	<!-- Meal plans menu -->
	<div class=\"vertical\">
		<ul>
			";
			include("_inc/meal_plans/menu.php");
			echo"
		</ul>
	</div>
	<!-- //Meal plans menu -->
	";
} // setup ok
else{
	echo"
	<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Running setup</p></div>
	<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=$open&amp;page=tables&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
	";
} // setup has not runned
?>