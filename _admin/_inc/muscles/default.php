<?php
/**
*
* File: _admin/_inc/muscles/default.php
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


/*- Tables ----------------------------------------------------------------------------- */
$t_muscles_liquidbase			= $mysqlPrefixSav . "muscles_liquidbase";
$t_muscles				= $mysqlPrefixSav . "muscles";
$t_muscles_translations 		= $mysqlPrefixSav . "muscles_translations";
$t_muscle_groups 			= $mysqlPrefixSav . "muscle_groups";
$t_muscle_groups_translations	 	= $mysqlPrefixSav . "muscle_groups_translations";

/*- Check if setup is run ------------------------------------------------------------- */
$query = "SELECT * FROM $t_muscles_liquidbase LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){

}
else{
	echo"
	<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Running setup</p></div>
	<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=$open&amp;page=tables&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
	";
} // setup has not runned


/*- Variables -------------------------------------------------------------------------- */
$editor_language_mysql = quote_smart($link, $editor_language);

if(isset($_GET['id'])){
	$id = $_GET['id'];
	$id = strip_tags(stripslashes($id));
}
else{
	$id = "";
}
if(isset($_GET['main_group_id'])){
	$main_group_id = $_GET['main_group_id'];
	$main_group_id = strip_tags(stripslashes($main_group_id));
}
else{
	$main_group_id = "";
}
if(isset($_GET['sub_group_id'])){
	$sub_group_id = $_GET['sub_group_id'];
	$sub_group_id = strip_tags(stripslashes($sub_group_id));
}
else{
	$sub_group_id = "";
}


/*- Scriptstart ---------------------------------------------------------------------- */
echo"

<h1>Muscles</h1>


<!-- Muscles buttons -->";
	// Navigation
	$query = "SELECT navigation_id FROM $t_pages_navigation WHERE navigation_url_path='muscles/index.php'";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_navigation_id) = $row;
	if($get_navigation_id == ""){
		echo"
		<p>
		<a href=\"index.php?open=pages&amp;page=navigation&amp;action=new_auto_insert&amp;module=muscles&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_default\">Create navigation</a>
		</p>
		";
	}
echo"
<!-- //Muscles buttons -->


<!-- Muscles menu -->
	<div class=\"vertical\">
		<ul>
			";
			include("_inc/muscles/menu.php");
			echo"
		</ul>
	</div>
<!-- //Muscles menu -->
";
?>