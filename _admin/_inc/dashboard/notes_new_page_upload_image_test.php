<?php
/**
*
* File: _admin/_inc/notes_new_page_upload_image_test.php
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

$query = "SELECT category_id, category_title, category_weight, category_bg_color, category_border_color, category_title_color, category_pages_bg_color, category_pages_bg_color_hover, category_pages_bg_color_active, category_pages_border_color, category_pages_border_color_hover, category_pages_border_color_active, category_pages_title_color, category_pages_title_color_hover, category_pages_title_color_active, category_created_datetime, category_created_by_user_id, category_updated_datetime, category_updated_by_user_id FROM $t_notes_categories ORDER BY category_weight ASC";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_category_id, $get_current_category_title, $get_current_category_weight, $get_current_category_bg_color, $get_current_category_border_color, $get_current_category_title_color, $get_current_category_pages_bg_color, $get_current_category_pages_bg_color_hover, $get_current_category_pages_bg_color_active, $get_current_category_pages_border_color, $get_current_category_pages_border_color_hover, $get_current_category_pages_border_color_active, $get_current_category_pages_title_color, $get_current_category_pages_title_color_hover, $get_current_category_pages_title_color_active, $get_current_category_created_datetime, $get_current_category_created_by_user_id, $get_current_category_updated_datetime, $get_current_category_updated_by_user_id) = $row;
if($get_current_category_id == ""){
	echo"Category not found";
}
else{
	// Get last page id
	$query = "SELECT page_id FROM $t_notes_pages ORDER BY page_id DESC LIMIT 0,1";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_last_page_id) = $row;
	$get_next_page_id = $get_last_page_id+1;

	echo"
				<!-- Upload form -->
					<form method=\"POST\" action=\"index.php?open=dashboard&amp;page=notes_new_page_upload_image&amp;category_id=$get_current_category_id&amp;page_id=$get_next_page_id&amp;process=1\" enctype=\"multipart/form-data\">
					<p>
					<input name=\"inp_image\" type=\"file\" tabindex=\"1\" />
					<input type=\"submit\" value=\"Upload\" tabindex=\"2\" />
					</p>
					</form>
				<!-- //Upload form -->
	";
	
} // found category


?>