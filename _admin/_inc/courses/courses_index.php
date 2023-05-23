<?php
/**
*
* File: _admin/_inc/comments/courses_index.php
* Version 
* Date 20:17 30.10.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
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


if($action == ""){
	$stmt = $mysqli->prepare("SELECT category_id, category_title, category_dir_name, category_description, category_language, category_created, category_updated FROM $t_courses_categories WHERE category_id=?"); 
	$stmt->bind_param("s", $category_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_current_category_id, $get_current_category_title, $get_current_category_dir_name, $get_current_category_description, $get_current_category_language, $get_current_category_created, $get_current_category_updated) = $row;

	if($get_current_category_id == ""){
		echo"<p>Server error 404.</p>";
	}
	else{
		echo"
		<h1>$get_current_category_title</h1>
			

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
			<a href=\"index.php?open=courses&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Categories</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=courses_index&amp;category_id=$category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_category_title</a>
			</p>
		<!-- //Where am I? -->

		<!-- Menu -->
			<p>
			<a href=\"index.php?open=courses&amp;page=courses_new&amp;category_id=$category_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">New course</a>
			<a href=\"index.php?open=courses&amp;page=courses_scan_for_courses&amp;category_id=$category_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Scan for courses</a>
			</p>
		<!-- //Menu -->

		<!-- List all courses -->
		
        	
			<table class=\"hor-zebra\">
			 <thead>
			  <tr>
			   <th scope=\"col\">
				<span>Title</span>
			   </th>
			   <th scope=\"col\">
				<span>Actions</span>
			   </th>
			  </tr>
			 </thead>
			 <tbody>
			";
	


			$editor_language_mysql = quote_smart($link, $editor_language);
			$query = "SELECT course_id, course_title, course_description, course_language, course_dir_name, course_category_id, course_intro_video_embedded, course_icon_48, course_icon_64, course_icon_96, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times, course_created, course_updated FROM $t_courses_index WHERE course_category_id=$category_id_mysql ORDER BY course_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_course_id, $get_course_title, $get_course_description, $get_course_language, $get_course_dir_name, $get_course_category_id, $get_course_intro_video_embedded, $get_course_icon_48, $get_course_icon_64, $get_course_icon_96, $get_course_modules_count, $get_course_lessons_count, $get_course_quizzes_count, $get_course_users_enrolled_count, $get_course_read_times, $get_course_created, $get_course_updated) = $row;

				if(isset($odd) && $odd == false){
					$odd = true;
				}
				else{
					$odd = false;
				}

				echo"
				<tr>
				  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
					<a href=\"index.php?open=$open&amp;page=courses_open&amp;course_id=$get_course_id&amp;editor_language=$editor_language&amp;l=$l\">$get_course_title</a>
				  </td>
				  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
					<span>
					<a href=\"index.php?open=$open&amp;page=courses_edit&amp;course_id=$get_course_id&amp;editor_language=$editor_language\">Edit</a>
					&middot;
					<a href=\"index.php?open=$open&amp;page=courses_delete&amp;course_id=$get_course_id&amp;editor_language=$editor_language\">Delete</a>
					</span>
				 </td>
				</tr>
				";
			}
			echo"
			 </tbody>
			</table>
		<!-- //List all courses -->
		";
	} // category found
} // action
?>