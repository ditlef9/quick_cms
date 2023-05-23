<?php
/**
*
* File: _admin/_inc/courses/courses_category_delete.php
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
/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;
if(isset($_GET['category_id'])){
	$category_id = $_GET['category_id'];
	$category_id = strip_tags(stripslashes($category_id));
}
else{
	$category_id = "";
}
$category_id_mysql = quote_smart($link, $category_id);


if($action == ""){
	$query = "SELECT category_id, category_title, category_dir_name, category_description, category_language, category_created, category_updated FROM $t_courses_categories WHERE category_id=$category_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_category_id, $get_current_category_title, $get_current_category_dir_name, $get_current_category_description, $get_current_category_language, $get_current_category_created, $get_current_category_updated) = $row;

	if($get_current_category_id == ""){
		echo"<p>Server error 404.</p>";
	}
	else{

		if($process == "1"){
			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_language = $_POST['inp_language'];
			$inp_language = output_html($inp_language);
			$inp_language_mysql = quote_smart($link, $inp_language);

			$inp_dir_name = $_POST['inp_dir_name'];
			$inp_dir_name = output_html($inp_dir_name);
			$inp_dir_name_mysql = quote_smart($link, $inp_dir_name);

			$datetime = date("Y-m-d H:i:s");
			
			$result = mysqli_query($link, "DELETE FROM $t_courses_categories WHERE category_id=$get_current_category_id") or die(mysqli_error($link));


			// Header
			$url = "index.php?open=$open&page=default&editor_language=$editor_language&ft=success&fm=category_deleted";
			header("Location: $url");
			exit;
		}

		echo"
		<h1>Delete category</h1>
					

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
			<a href=\"index.php?open=courses&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Courses</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">All courses</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=courses_category_delete&amp;category_id=$category_id&amp;editor_language=$editor_language&amp;l=$l\">Delete category</a>
			</p>
		<!-- //Where am I? -->


		<!-- Delete course form -->
			<p>Are you sure?</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;category_id=$category_id&amp;editor_language=$editor_language&amp;process=1\" class=\"btn_danger\">Delete</a>
			</p>

		<!-- //Edit course form -->
		";
	} // found
} // action == 
?>