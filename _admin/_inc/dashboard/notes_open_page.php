<?php
/**
*
* File: _admin/_inc/notes_open_page.php
* Version 1
* Date 14:58 02.04.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */
$t_notes_categories   = $mysqlPrefixSav . "notes_categories";
$t_notes_pages	      = $mysqlPrefixSav . "notes_pages";
$t_notes_pages_images = $mysqlPrefixSav . "notes_pages_images";
$t_notes_pages_files  = $mysqlPrefixSav . "notes_pages_files";

/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['page_id'])) {
	$page_id = $_GET['page_id'];
	$page_id = strip_tags(stripslashes($page_id));
	if(!(is_numeric($page_id))){
		echo"page id not numeric";
		die;
	}
}
else{
	$page_id = "";
}
$page_id_mysql = quote_smart($link, $page_id);
$query = "SELECT page_id, page_title, page_category_id, page_weight, page_parent_id, page_text, page_created_datetime, page_created_by_user_id, page_updated_datetime, page_updated_by_user_id FROM $t_notes_pages WHERE page_id=$page_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_page_id, $get_current_page_title, $get_current_page_category_id, $get_current_page_weight, $get_current_page_parent_id, $get_current_page_text, $get_current_page_created_datetime, $get_current_page_created_by_user_id, $get_current_page_updated_datetime, $get_current_page_updated_by_user_id) = $row;


if($get_current_page_id == ""){
	echo"page not found";
}
else{
	// Get category
	$query = "SELECT category_id, category_title, category_weight, category_bg_color, category_border_color, category_title_color, category_pages_bg_color, category_pages_bg_color_hover, category_pages_bg_color_active, category_pages_border_color, category_pages_border_color_hover, category_pages_border_color_active, category_pages_title_color, category_pages_title_color_hover, category_pages_title_color_active, category_created_datetime, category_created_by_user_id, category_updated_datetime, category_updated_by_user_id FROM $t_notes_categories WHERE category_id=$get_current_page_category_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_category_id, $get_current_category_title, $get_current_category_weight, $get_current_category_bg_color, $get_current_category_border_color, $get_current_category_title_color, $get_current_category_pages_bg_color, $get_current_category_pages_bg_color_hover, $get_current_category_pages_bg_color_active, $get_current_category_pages_border_color, $get_current_category_pages_border_color_hover, $get_current_category_pages_border_color_active, $get_current_category_pages_title_color, $get_current_category_pages_title_color_hover, $get_current_category_pages_title_color_active, $get_current_category_created_datetime, $get_current_category_created_by_user_id, $get_current_category_updated_datetime, $get_current_category_updated_by_user_id) = $row;


	echo"
	<h1>$get_current_page_title</h1>

	<!-- Categories -->
		<div class=\"tabs\">
			<ul>";
			$query_u = "SELECT category_id, category_title, category_weight, category_bg_color, category_border_color, category_title_color, category_pages_bg_color, category_pages_bg_color_hover, category_pages_bg_color_active, category_pages_border_color, category_pages_border_color_hover, category_pages_border_color_active, category_pages_title_color, category_pages_title_color_hover, category_pages_title_color_active, category_created_datetime, category_created_by_user_id, category_updated_datetime, category_updated_by_user_id FROM $t_notes_categories ORDER BY category_weight ASC";
			$result_u = mysqli_query($link, $query_u);
			while($row_u = mysqli_fetch_row($result_u)) {
				list($get_category_id, $get_category_title, $get_category_weight, $get_category_bg_color, $get_category_border_color, $get_category_title_color, $get_category_pages_bg_color, $get_category_pages_bg_color_hover, $get_category_pages_bg_color_active, $get_category_pages_border_color, $get_category_pages_border_color_hover, $get_category_pages_border_color_active, $get_category_pages_title_color, $get_category_pages_title_color_hover, $get_category_pages_title_color_active, $get_category_created_datetime, $get_category_created_by_user_id, $get_category_updated_datetime, $get_category_updated_by_user_id) = $row_u;
				echo"				";
				echo"<li><a href=\"index.php?open=$open&amp;page=notes_open_category&amp;category_id=$get_category_id&amp;editor_language=$editor_language&amp;l=$l\""; if($get_category_id == "$get_current_category_id"){ echo" class=\"active\""; } echo">$get_category_title</a>\n";
			}
			echo"
				<li><a href=\"index.php?open=$open&amp;page=notes_new_category&amp;editor_language=$editor_language&amp;l=$l\">+</a>
			</ul>
		</div>
		<div class=\"clear\"></div>
	<!-- //Categories -->

	<!-- Category actions -->
		<p>
		<a href=\"index.php?open=$open&amp;page=notes_new_page&amp;category_id=$get_current_category_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">New page</a>
		<a href=\"index.php?open=$open&amp;page=notes_edit_page&amp;page_id=$get_current_page_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Edit page</a>
		<a href=\"index.php?open=$open&amp;page=notes_delete_page&amp;page_id=$get_current_page_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Delete page</a>
		<a href=\"index.php?open=$open&amp;page=notes_edit_category&amp;category_id=$get_current_category_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Edit category</a>
		<a href=\"index.php?open=$open&amp;page=notes_delete_category&amp;category_id=$get_current_category_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Delete category</a>
		</p>
	<!-- //Category actions -->

	<!-- View note and note pages -->
		<div class=\"notes_page_and_pages_row\">
			<!-- View note -->
			<div class=\"notes_page_and_pages_col_left\">
				$get_current_page_text 
			</div> <!-- //left -->
			<!-- //View note -->

			<!-- View note pages -->
			<div class=\"notes_page_and_pages_col_right\">
				<ul>";
				$query_p = "SELECT page_id, page_title, page_category_id, page_weight, page_parent_id FROM $t_notes_pages WHERE page_category_id=$get_current_category_id ORDER BY page_weight ASC";
				$result_p = mysqli_query($link, $query_p);
				while($row_p = mysqli_fetch_row($result_p)) {
					list($get_page_id, $get_page_title, $get_page_category_id, $get_page_weight, $get_page_parent_id) = $row_p;
					echo"				";
					echo"<li><a href=\"index.php?open=$open&amp;page=notes_open_page&amp;page_id=$get_page_id&amp;editor_language=$editor_language&amp;l=$l\""; if($get_page_id == "$get_current_page_id"){ echo" class=\"active\""; } echo">$get_page_title</a>\n";
				}
				echo"
				</ul>
			</div> <!-- //right -->
			<!-- //View note pages -->
		</div> <!-- //notes_page_and_pages_row -->
	<!-- //View note and note pages  -->
	

	";
} // found category

?>