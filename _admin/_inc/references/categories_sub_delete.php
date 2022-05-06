<?php
/**
*
* File: _admin/_inc/courses/categories_sub_delete.php
* Version 
* Date 11:39 15.09.2019
* Copyright (c) 2008-2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}
/*- Tables ---------------------------------------------------------------------------- */
$t_references_title_translations = $mysqlPrefixSav . "references_title_translations";
$t_references_categories_main	 = $mysqlPrefixSav . "references_categories_main";
$t_references_categories_sub 	 = $mysqlPrefixSav . "references_categories_sub";
$t_references_index		 = $mysqlPrefixSav . "references_index";
$t_references_index_groups	 = $mysqlPrefixSav . "references_index_groups";
$t_references_index_guides	 = $mysqlPrefixSav . "references_index_guides";


/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;
if(isset($_GET['sub_category_id'])){
	$sub_category_id = $_GET['sub_category_id'];
	$sub_category_id = strip_tags(stripslashes($sub_category_id));
}
else{
	$sub_category_id = "";
}
$sub_category_id_mysql = quote_smart($link, $sub_category_id);


if($action == ""){
	$query = "SELECT sub_category_id, sub_category_title, sub_category_title_clean, sub_category_description, sub_category_main_category_id, sub_category_main_category_title, sub_category_language, sub_category_created, sub_category_updated FROM $t_references_categories_sub WHERE sub_category_id=$sub_category_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_sub_category_id, $get_current_sub_category_title, $get_current_sub_category_title_clean, $get_current_sub_category_description, $get_current_sub_category_main_category_id, $get_current_sub_category_main_category_title, $get_current_sub_category_language, $get_current_sub_category_created, $get_current_sub_category_updated) = $row;

	if($get_current_sub_category_id == ""){
		echo"<p>Server error 404.</p>";
	}
	else{
		// Find main category
		$query = "SELECT main_category_id, main_category_title, main_category_title_clean, main_category_description, main_category_language, main_category_created, main_category_updated FROM $t_references_categories_main WHERE main_category_id=$get_current_sub_category_main_category_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_title_clean, $get_current_main_category_description, $get_current_main_category_language, $get_current_main_category_created, $get_current_main_category_updated) = $row;


		if($process == "1"){
			
			
			$result = mysqli_query($link, "DELETE FROM $t_references_categories_sub WHERE sub_category_id=$get_current_sub_category_id") or die(mysqli_error($link));


			// Header
			$url = "index.php?open=$open&page=categories_main_open&main_category_id=$get_current_main_category_id&editor_language=$editor_language&ft=success&fm=sub_category_deleted";
			header("Location: $url");
			exit;
		}

		echo"
		<h1>Delete sub category</h1>
					

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
	



		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=references&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">References</a>
			&gt;
			<a href=\"index.php?open=references&amp;page=categories_main&amp;editor_language=$editor_language&amp;l=$l\">Main categories</a>
			&gt;
			<a href=\"index.php?open=references&amp;page=categories_main_open&amp;main_category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_main_category_title</a>
			&gt;
			<a href=\"index.php?open=references&amp;page=categories_sub_edit&amp;sub_category_id=$get_current_sub_category_id&amp;editor_language=$editor_language&amp;l=$l\">Delete $get_current_sub_category_title</a>
			</p>
		<!-- //Where am I? -->


		<!-- Delete course form -->
			<p>
			Are you sure you want to delete the sub category?
			</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;sub_category_id=$get_current_sub_category_id&amp;editor_language=$editor_language&amp;process=1\" class=\"btn_danger\">Confirm</a>
			</p>
		<!-- //Delete course form -->
		";
	} // found
} // action == 
?>