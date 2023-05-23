<?php
/**
*
* File: _admin/_inc/courses/categories_sub_new.php
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


		if($process == "1"){
			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);

			$inp_title_clean = clean($inp_title);

			$inp_description = "";

			$inp_language_mysql = "$get_current_main_category_language";

			$datetime = date("Y-m-d H:i:s");

			$inp_main_category_title = "$get_current_main_category_title";
		
			$stmt = $mysqli->prepare("INSERT INTO $t_courses_categories_sub
				(sub_category_id, sub_category_title, sub_category_title_clean, sub_category_description, sub_category_main_category_id, 
				sub_category_main_category_title, sub_category_language, sub_category_created, sub_category_updated) 
				VALUES 
				(NULL,?,?,?,?,
				?,?,?,?)");
			$stmt->bind_param("ssssssss", $inp_title, $inp_title_clean, $inp_description, $get_current_main_category_id, $inp_main_category_title, 
					$inp_language, $datetime, $datetime
				); 
			$stmt->execute();


			// Get ID
			$query = "SELECT sub_category_id FROM $t_courses_categories_sub WHERE sub_category_created='$datetime'";
			$result = $mysqli->query($query);
			$row = $result->fetch_row();
			list($get_current_sub_category_id) = $row;



			// Header
			$url = "index.php?open=$open&page=categories_main_open&main_category_id=$get_current_main_category_id&editor_language=$editor_language&ft=success&fm=sub_category_" . $inp_title_clean . "_created";
			header("Location: $url");
			exit;
		}

		echo"
		<h1>New sub category</h1>
				

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
		<a href=\"index.php?open=courses&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Courses menu</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=categories_main&amp;editor_language=$editor_language&amp;l=$l\">Main Categories</a>
		&gt;
		<a href=\"index.php?open=courses&amp;page=categories_main_open&amp;main_category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_main_category_title</a>
		&gt;
		<a href=\"index.php?open=courses&amp;page=categories_sub_new&amp;main_category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">New sub category</a>
		</p>
		<!-- //Where am I? -->


		<!-- New sub category -->
		
			<p style=\"padding-bottom:0;margin-bottom:0;\"><b>About sub categories:</b></p>
			<ul>
				<li><p>
				When a sub category is created it is inserted into the database.
				</p></li>

				<li><p>
				The sub category can be visited on the URL <tt>courses/open_sub_category.php?main_category_id=$get_current_main_category_id&amp;sub_category_id=x&amp;l=$get_current_main_category_language</tt>.
				</p></li>
			</ul>

			<script>
			window.onload = function() {
				document.getElementById(\"inp_title\").focus();
			}
			</script>
			
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;main_category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>Title:</b><br />
			<input type=\"text\" name=\"inp_title\" id=\"inp_title\" value=\"\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" style=\"width: 100%;\" />
			</p>

			<p><input type=\"submit\" value=\"Create\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>

			</form>
		<!-- //New sub category form -->
		";
	} // action == ""
} // main category found
?>