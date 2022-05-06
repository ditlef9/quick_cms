<?php
/**
*
* File: _admin/_inc/notes.php
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
if(isset($_GET['category_id'])) {
	$category_id = $_GET['category_id'];
	$category_id = strip_tags(stripslashes($category_id));
	if(!(is_numeric($category_id))){
		echo"Category id not numeric";
		die;
	}
}
else{
	$category_id = "";
}
$category_id_mysql = quote_smart($link, $category_id);

$query = "SELECT category_id, category_title, category_weight, category_bg_color, category_border_color, category_title_color, category_pages_bg_color, category_pages_bg_color_hover, category_pages_bg_color_active, category_pages_border_color, category_pages_border_color_hover, category_pages_border_color_active, category_pages_title_color, category_pages_title_color_hover, category_pages_title_color_active, category_created_datetime, category_created_by_user_id, category_updated_datetime, category_updated_by_user_id FROM $t_notes_categories WHERE category_id=$category_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_category_id, $get_current_category_title, $get_current_category_weight, $get_current_category_bg_color, $get_current_category_border_color, $get_current_category_title_color, $get_current_category_pages_bg_color, $get_current_category_pages_bg_color_hover, $get_current_category_pages_bg_color_active, $get_current_category_pages_border_color, $get_current_category_pages_border_color_hover, $get_current_category_pages_border_color_active, $get_current_category_pages_title_color, $get_current_category_pages_title_color_hover, $get_current_category_pages_title_color_active, $get_current_category_created_datetime, $get_current_category_created_by_user_id, $get_current_category_updated_datetime, $get_current_category_updated_by_user_id) = $row;
if($get_current_category_id == ""){
	echo"Category not found";
}
else{
	if($process == "1"){
		// Delete category
		mysqli_query($link, "DELETE FROM $t_notes_categories WHERE category_id=$get_current_category_id") or die(mysqli_error($link)); 

		// Delete pages
		mysqli_query($link, "DELETE FROM $t_notes_pages WHERE page_category_id=$get_current_category_id") or die(mysqli_error($link));
		
		// Delete images
		$query = "SELECT image_id, image_category_id, image_page_id, image_title, image_text, image_path, image_file FROM $t_notes_pages_images WHERE image_category_id=$get_current_category_id";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_image_id, $get_image_category_id, $get_image_page_id, $get_image_title, $get_image_text, $get_image_path, $get_image_file) = $row;
			
			if(file_exists("$get_image_path/$get_image_file") && $get_image_file != ""){
				unlink("$get_image_path/$get_image_file");
			}
			
			mysqli_query($link, "DELETE FROM $t_notes_pages_images WHERE image_id=$get_image_id") or die(mysqli_error($link));
		}

		// Header
		$url = "index.php?open=dashboard&page=notes&editor_language=$editor_language&l=$l&ft=success&fm=category_deleted";
		header("Location: $url");
		exit;
	
	}
	
	echo"
	<h1>Delete $get_current_category_title</h1>

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

	<!-- Delete category form -->
		<p>Are you sure you want to delete the category and all pages related to the category?</p>
		
		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;category_id=$get_current_category_id&amp;l=$l&amp;process=1\" class=\"btn_danger\">Confirm</a>
		</p>
	<!-- //Delete category form -->
		
	
	";
} // found category

?>