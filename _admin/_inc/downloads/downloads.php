<?php
/**
*
* File: _admin/_inc/downloads/downloads.php
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

/*- Tables ---------------------------------------------------------------------------- */
$t_downloads_index 				= $mysqlPrefixSav . "downloads_index";
$t_downloads_main_categories 			= $mysqlPrefixSav . "downloads_main_categories";
$t_downloads_main_categories_translations 	= $mysqlPrefixSav . "downloads_main_categories_translations";

$t_downloads_sub_categories 			= $mysqlPrefixSav . "downloads_sub_categories";
$t_downloads_sub_categories_translations 	= $mysqlPrefixSav . "downloads_sub_categories_translations";



/*- Varialbes  ---------------------------------------------------- */
if(isset($_GET['main_category_id'])) {
	$main_category_id = $_GET['main_category_id'];
	$main_category_id = strip_tags(stripslashes($main_category_id));
}
else{
	$main_category_id = "";
}
if(isset($_GET['sub_category_id'])) {
	$sub_category_id = $_GET['sub_category_id'];
	$sub_category_id = strip_tags(stripslashes($sub_category_id));
}
else{
	$sub_category_id = "";
}


/*- Functuons ---------------------------------------------------- */


/*- Scriptstart ---------------------------------------------------------------------- */
if($action == ""){
	echo"

	<h1>Downloads</h1>

	<!-- Feedback -->
		";
		if($ft != "" && $fm != ""){
			if($fm == "category_deleted"){
				$fm = "Category deleted";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><p>$fm</p></div>";
		}
		echo"
	<!-- //Feedback -->
		


	<div class=\"vertical\">
		<ul>
		";
		// Get all categories
		$query = "SELECT main_category_id, main_category_title, main_category_icon_path, main_category_icon_file FROM $t_downloads_main_categories ORDER BY main_category_title ASC";
		$result = $mysqli->query($query);
		while($row = $result->fetch_row()) {
			list($get_main_category_id, $get_main_category_title, $get_main_category_icon_path, $get_main_category_icon_file) = $row;

			echo"			";
			echo"<li><a href=\"index.php?open=$open&amp;page=downloads_2_open_main_category&amp;main_category_id=$get_main_category_id&amp;l=$l&amp;editor_language=$editor_language\">$get_main_category_title</a></li>\n";
		}
		echo"
		</ul>
	</div>
	";
} // action == ""
?>