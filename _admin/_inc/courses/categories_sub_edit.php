<?php
/**
*
* File: _admin/_inc/courses/categories_sub_edit.php
* Version 2
* Copyright (c) 2008-223 Sindre Andre Ditlefsen
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
if(isset($_GET['sub_category_id'])){
	$sub_category_id = $_GET['sub_category_id'];
	$sub_category_id = strip_tags(stripslashes($sub_category_id));
}
else{
	$sub_category_id = "";
}



if($action == ""){
	$stmt = $mysqli->prepare("SELECT sub_category_id, sub_category_title, sub_category_title_clean, sub_category_description, sub_category_main_category_id, sub_category_main_category_title, sub_category_language, sub_category_created, sub_category_updated FROM $t_courses_categories_sub WHERE sub_category_id=?"); 
	$stmt->bind_param("s", $sub_category_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_current_sub_category_id, $get_current_sub_category_title, $get_current_sub_category_title_clean, $get_current_sub_category_description, $get_current_sub_category_main_category_id, $get_current_sub_category_main_category_title, $get_current_sub_category_language, $get_current_sub_category_created, $get_current_sub_category_updated) = $row;

	
	if($get_current_sub_category_id == ""){
		echo"<p>Server error 404.</p>";
	}
	else{
		// Find main category
		$stmt = $mysqli->prepare("SELECT main_category_id, main_category_title, main_category_title_clean, main_category_description, main_category_language, main_category_created, main_category_updated FROM $t_courses_categories_main WHERE main_category_id=?"); 
		$stmt->bind_param("s", $get_current_sub_category_main_category_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_row();
		list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_title_clean, $get_current_main_category_description, $get_current_main_category_language, $get_current_main_category_created, $get_current_main_category_updated) = $row;


		if($process == "1"){
			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);

			$inp_title_clean = clean($inp_title);

			$inp_main_category_id = $_POST['inp_main_category_id'];
			$inp_main_category_id = output_html($inp_main_category_id);

			// Find (new) main category
			$stmt = $mysqli->prepare("SELECT main_category_id, main_category_title FROM $t_courses_categories_main WHERE main_category_id=?"); 
			$stmt->bind_param("s", $inp_main_category_id);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_row();
			list($get_new_main_category_id, $get_new_main_category_title) = $row;

			$inp_main_category_title = "$get_new_main_category_title";


			$datetime = date("Y-m-d H:i:s");
			$stmt = $mysqli->prepare("UPDATE $t_courses_categories_sub SET 
						sub_category_title=?, 
						sub_category_title_clean=?, 
						sub_category_main_category_id=?,
						sub_category_main_category_title=?,
						sub_category_updated=?
						WHERE sub_category_id=?");
			$stmt->bind_param("ssssss", $inp_title, $inp_title_clean, $inp_main_category_id, 
						$inp_main_category_title, $datetime, $get_current_sub_category_id); 
			$stmt->execute();


			// Header
			$url = "index.php?open=$open&page=categories_main_open&main_category_id=$inp_main_category_id&editor_language=$editor_language&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;
		}

		echo"
		<h1>Edit sub category</h1>
					

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
			&gt;
			<a href=\"index.php?open=courses&amp;page=categories_sub_edit&amp;sub_category_id=$get_current_sub_category_id&amp;editor_language=$editor_language&amp;l=$l\">Edit $get_current_sub_category_title</a>
			</p>
		<!-- //Where am I? -->


		<!-- Edit course form -->
		

		<script>
		window.onload = function() {
			document.getElementById(\"inp_title\").focus();
		}
		</script>
			
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;sub_category_id=$get_current_sub_category_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

		<p><b>Title:</b><br />
		<input type=\"text\" name=\"inp_title\" id=\"inp_title\" value=\"$get_current_sub_category_title\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>Main category:</b><br />
		<select name=\"inp_main_category_id\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">\n";
		$stmt = $mysqli->prepare("SELECT main_category_id, main_category_title FROM $t_courses_categories_main WHERE main_category_language=? ORDER BY main_category_title ASC"); 
		$stmt->bind_param("s", $get_current_sub_category_language);
		$stmt->execute();
		$result = $stmt->get_result();
		while($row = $result->fetch_row()) {
			list($get_main_category_id, $get_main_category_title) = $row;
			echo"	<option value=\"$get_main_category_id\""; if($get_main_category_id == "$get_current_sub_category_main_category_id"){ echo" selected=\"selected\""; } echo">$get_main_category_title</option>\n";
		}
		echo"
		</select>


		<p><input type=\"submit\" value=\"Save changes\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>

		</form>
		<!-- //Edit course form -->
		";
	} // found
} // action == 
?>