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
		$datetime = date("Y-m-d H:i:s");

		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

	

		// Me
		$my_user_id = $_SESSION['user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);


		mysqli_query($link, "UPDATE $t_notes_categories SET 
					category_title=$inp_title_mysql, 
					category_updated_datetime='$datetime', 
					category_updated_by_user_id=$my_user_id_mysql
					WHERE category_id=$get_current_category_id") or die(mysqli_error($link)); 


		// Header
		$url = "index.php?open=dashboard&page=notes_open_category&category_id=$get_current_category_id&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
		header("Location: $url");
		exit;
	
	}
	
	echo"
	<h1>Edit $get_current_category_title</h1>

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

	<!-- Edit category form -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_title\"]').focus();
		});
		</script>
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;category_id=$get_current_category_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
		<p>Title:<br />
		<input type=\"text\" name=\"inp_title\" value=\"$get_current_category_title\" size=\"25\" />
		</p>
		<p><input type=\"submit\" value=\"Save changes\" class=\"btn_default\" /></p>
		</form>
	<!-- //Edit category form -->
		
	
	";
} // found category

?>