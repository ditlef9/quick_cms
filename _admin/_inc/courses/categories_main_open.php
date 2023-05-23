<?php
/**
*
* File: _admin/_inc/comments/categories_main_open.php
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
$t_courses_liquidbase 	 = $mysqlPrefixSav . "courses_liquidbase";


$t_courses_title_translations	 = $mysqlPrefixSav . "courses_title_translations";
$t_courses_index		 = $mysqlPrefixSav . "courses_index";
$t_courses_users_enrolled 	 = $mysqlPrefixSav . "courses_users_enrolled";

$t_courses_categories_main	 = $mysqlPrefixSav . "courses_categories_main";
$t_courses_categories_sub 	 = $mysqlPrefixSav . "courses_categories_sub";
$t_courses_modules		 = $mysqlPrefixSav . "courses_modules";
$t_courses_modules_read		 = $mysqlPrefixSav . "courses_modules_read";

$t_courses_modules_contents 	 = $mysqlPrefixSav . "courses_modules_contents";
$t_courses_modules_contents_read = $mysqlPrefixSav . "courses_modules_contents_read";
$t_courses_modules_contents_comments	= $mysqlPrefixSav . "courses_modules_contents_comments";

$t_courses_modules_quizzes_index  	= $mysqlPrefixSav . "courses_modules_quizzes_index";
$t_courses_modules_quizzes_qa 		= $mysqlPrefixSav . "courses_modules_quizzes_qa";
$t_courses_modules_quizzes_user_records	= $mysqlPrefixSav . "courses_modules_quizzes_user_records";

$t_courses_exams_index  		= $mysqlPrefixSav . "courses_exams_index";
$t_courses_exams_qa			= $mysqlPrefixSav . "courses_exams_qa";
$t_courses_exams_user_tries		= $mysqlPrefixSav . "courses_exams_user_tries";
$t_courses_exams_user_tries_qa		= $mysqlPrefixSav . "courses_exams_user_tries_qa";

/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;
if(isset($_GET['main_category_id'])){
	$main_category_id = $_GET['main_category_id'];
	$main_category_id = strip_tags(stripslashes($main_category_id));
}
else{
	$main_category_id = "";
}


if($action == ""){
	$stmt = $mysqli->prepare("SELECT main_category_id, main_category_title, main_category_title_clean, main_category_description, main_category_language, main_category_icon_path, main_category_icon_16x16, main_category_icon_18x18, main_category_icon_24x24, main_category_icon_32x32, main_category_icon_36x36, main_category_icon_48x48, main_category_icon_96x96, main_category_icon_192x192, main_category_icon_260x260, main_category_header_logo, main_category_webdesign, main_category_created, main_category_updated FROM $t_courses_categories_main WHERE main_category_id=?"); 
	$stmt->bind_param("s", $main_category_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_title_clean, $get_current_main_category_description, $get_current_main_category_language, $get_current_main_category_icon_path, $get_current_main_category_icon_16x16, $get_current_main_category_icon_18x18, $get_current_main_category_icon_24x24, $get_current_main_category_icon_32x32, $get_current_main_category_icon_36x36, $get_current_main_category_icon_48x48, $get_current_main_category_icon_96x96, $get_current_main_category_icon_192x192, $get_current_main_category_icon_260x260, $get_current_main_category_header_logo, $get_current_main_category_webdesign, $get_current_main_category_created, $get_current_main_category_updated) = $row;

	if($get_current_main_category_id == ""){
		echo"<p>Server error 404.</p>";
	}
	else{
		echo"
		<h1>$get_current_main_category_title</h1>
			

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
			<a href=\"index.php?open=courses&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Courses menu</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=categories_main&amp;editor_language=$editor_language&amp;l=$l\">Main categories</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=categories_main_open&amp;main_category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_main_category_title</a>
			
			</p>
		<!-- //Where am I? -->

		<!-- Menu -->
			<p>
			<a href=\"index.php?open=courses&amp;page=categories_sub_new&amp;main_category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">New sub category</a>
			<a href=\"index.php?open=courses&amp;page=courses_new&amp;action=step_2_select_sub_category&amp;main_category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">New course</a>
			</p>
		<!-- //Menu -->

		<!-- List sub categories -->
		
        	
			<table class=\"hor-zebra\">
			 <thead>
			  <tr>
			   <th scope=\"col\">
				<span>Sub category title</span>
			   </th>
			   <th scope=\"col\">
				<span>URL</span>
			   </th>
			   <th scope=\"col\">
				<span>Actions</span>
			   </th>
			  </tr>
			 </thead>
			 <tbody>
			";
	


			$query = "SELECT sub_category_id, sub_category_title FROM $t_courses_categories_sub WHERE sub_category_main_category_id=$get_current_main_category_id ORDER BY sub_category_title ASC";
			$result = $mysqli->query($query);
			while($row = $result->fetch_row()) {
				list($get_sub_category_id, $get_sub_category_title) = $row;

				if(isset($odd) && $odd == false){
					$odd = true;
				}
				else{
					$odd = false;
				}

				echo"
				<tr>
				  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
					<span>$get_sub_category_title</span>
				  </td>
				  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
					<span><a href=\"../courses/open_sub_category.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_sub_category_id&amp;l=$get_current_main_category_language\">courses/open_sub_category.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_sub_category_id&amp;l=$get_current_main_category_language</a></span>
				  </td>
				  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
					<span>
					<a href=\"index.php?open=$open&amp;page=categories_sub_edit&amp;sub_category_id=$get_sub_category_id&amp;editor_language=$editor_language\">Edit</a>
					&middot;
					<a href=\"index.php?open=$open&amp;page=categories_sub_delete&amp;sub_category_id=$get_sub_category_id&amp;editor_language=$editor_language\">Delete</a>
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