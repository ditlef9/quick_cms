<?php
/**
*
* File: _admin/_inc/comments/courses_new.php
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


/*- Tables search --------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";



/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;


if($action == ""){


	echo"
	<h1>New course</h1>
				

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
		<a href=\"index.php?open=courses&amp;page=courses_new&amp;editor_language=$editor_language&amp;l=$l\">New course</a>
		</p>
	<!-- //Where am I? -->

	<p>
	<a href=\"index.php?open=courses&amp;page=categories_main_new&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">New main category</a>
	</p>

	<!-- Language -->
		<p><b>Editor language:</b><br />
		";
		$found_editor_language = "";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag_path_16x16, language_active_flag_active_16x16, language_active_default FROM $t_languages_active";
		$result = $mysqli->query($query);
		while($row = $result->fetch_row()) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag_path_16x16, $get_language_active_flag_active_16x16, $get_language_active_default) = $row;
				
			echo"	<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$get_language_active_iso_two&amp;l=$l\"><img src=\"../$get_language_active_flag_path_16x16/$get_language_active_flag_active_16x16\" alt=\"$get_language_active_flag_active_16x16\" /></a>\n";
			if($editor_language == "$get_language_active_iso_two"){
				$found_editor_language = "1";
			}
		}
		if($found_editor_language == ""){
			// Editor language not found
			if(isset($get_language_active_iso_two)){
				$editor_language = "$get_language_active_iso_two";
			}
			else{
				echo"<p>Editor language not found</p>";
			}
		}
		echo"
		</p>

	<!-- //Language -->

	<!-- New course form -->

		<p><b>Please select main category ($editor_language):</b></p>

		<div class=\"vertical\">
			<ul>\n";
		$stmt = $mysqli->prepare("SELECT main_category_id, main_category_title FROM $t_courses_categories_main WHERE main_category_language=? ORDER BY main_category_title ASC"); 
		$stmt->bind_param("s", $editor_language);
		$stmt->execute();
		$result = $stmt->get_result();
		while($row = $result->fetch_row()) {
			list($get_main_category_id, $get_main_category_title) = $row;

			echo"	<li><a href=\"index.php?open=courses&amp;page=courses_new&amp;action=step_2_select_sub_category&amp;main_category_id=$get_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_main_category_title</a></li>\n";

		}
		echo"
			</ul>
		</div>
	<!-- //New course form -->
	";
}
elseif($action == "step_2_select_sub_category"){
	if(isset($_GET['main_category_id'])){
		$main_category_id = $_GET['main_category_id'];
		$main_category_id = strip_tags(stripslashes($main_category_id));
	}
	else{
		$main_category_id = "";
	}
	$stmt = $mysqli->prepare("SELECT main_category_id, main_category_title, main_category_title_clean, main_category_description, main_category_language, main_category_created, main_category_updated FROM $t_courses_categories_main WHERE main_category_id=?"); 
	$stmt->bind_param("s", $main_category_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_title_clean, $get_current_main_category_description, $get_current_main_category_language, $get_current_main_category_created, $get_current_main_category_updated) = $row;

	if($get_current_main_category_id == ""){
		echo"<p>Server error 404.</p>";
	}
	else{

		echo"
		<h1>New course</h1>
				

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
			<a href=\"index.php?open=courses&amp;page=courses_new&amp;editor_language=$editor_language&amp;l=$l\">New course</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=courses_new&amp;action=step_2_select_sub_category&amp;main_category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_main_category_title</a>
			</p>
		<!-- //Where am I? -->


		<!-- Select sub category -->
			<p>
			<a href=\"index.php?open=courses&amp;page=categories_main_open&amp;main_category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Sub categories</a>
			<a href=\"index.php?open=courses&amp;page=categories_sub_new&amp;main_category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">New sub category</a>
			</p>

			<p><b>Please select sub category</b></p>

			<div class=\"vertical\">
				<ul>\n";
		

			$query_sub = "SELECT sub_category_id, sub_category_title FROM $t_courses_categories_sub WHERE sub_category_main_category_id=$get_current_main_category_id ORDER BY sub_category_title ASC";
			$result_sub = $mysqli->query($query_sub);
			while($row_sub = $result_sub->fetch_row()) {
				list($get_sub_category_id, $get_sub_category_title) = $row_sub;

				echo"	<li><a href=\"index.php?open=courses&amp;page=courses_new&amp;action=step_3_course_info&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_sub_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_sub_category_title</a></li>\n";
			}
			echo"
				</ul>
			</div>
		
		<!-- //Select sub category -->
		";
	} // main category found
} // action == step_2_select_sub_category
elseif($action == "step_3_course_info"){
	if(isset($_GET['main_category_id'])){
		$main_category_id = $_GET['main_category_id'];
		$main_category_id = strip_tags(stripslashes($main_category_id));
	}
	else{
		$main_category_id = "";
	}
	$stmt = $mysqli->prepare("SELECT main_category_id, main_category_title, main_category_title_clean, main_category_description, main_category_language, main_category_created, main_category_updated FROM $t_courses_categories_main WHERE main_category_id=?"); 
	$stmt->bind_param("s", $main_category_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_title_clean, $get_current_main_category_description, $get_current_main_category_language, $get_current_main_category_created, $get_current_main_category_updated) = $row;

	if($get_current_main_category_id == ""){
		echo"<p>Server error 404.</p>";
	}
	else{
		if(isset($_GET['sub_category_id'])){
			$sub_category_id = $_GET['sub_category_id'];
			$sub_category_id = strip_tags(stripslashes($sub_category_id));
		}
		else{
			$sub_category_id = "";
		}
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

			if($process == "1"){
				$inp_title = $_POST['inp_title'];
				$inp_title = output_html($inp_title);

				$inp_title_clean = clean($inp_title);

				$inp_front_page_intro = $_POST['inp_front_page_intro'];
				$inp_front_page_intro = output_html($inp_front_page_intro);

				$inp_description = $_POST['inp_description'];
				$inp_description = output_html($inp_description);

				$inp_contents = $_POST['inp_contents'];
				$inp_contents = output_html($inp_contents);

				$inp_language = $_GET['editor_language'];
				$inp_language = output_html($inp_language);

				$inp_main_category_title = "$get_current_main_category_title";

				$inp_sub_category_title = "$get_current_sub_category_title";

				$inp_intro_video_embedded = $_POST['inp_intro_video_embedded'];
				$inp_intro_video_embedded = output_html($inp_intro_video_embedded);

				$inp_image_file  = $inp_title_clean . ".png";

				$inp_image_thumb  = $inp_title_clean . "_thumb.png";

				$inp_icon_a = $inp_title_clean . "_16x16.png";

				$inp_icon_b = $inp_title_clean . "_32x32.png";

				$inp_icon_c = $inp_title_clean . "_48x48.png";

				$inp_icon_d = $inp_title_clean . "_64x64.png";

				$inp_icon_e = $inp_title_clean . "_96x96.png";

				$inp_icon_f = $inp_title_clean . "_260x260.png";

				$datetime = date("Y-m-d H:i:s");
				$datetime_saying = date("j M Y H:i");
				$zero = 0;

				$stmt = $mysqli->prepare("INSERT INTO $t_courses_index
					(course_id, course_title, course_title_clean, course_front_page_intro, course_description,
					course_contents, course_language, course_main_category_id, course_main_category_title, course_sub_category_id, 
					course_sub_category_title, course_intro_video_embedded, course_image_file, course_image_thumb, course_icon_16, 	
					course_icon_32, course_icon_48, course_icon_64, course_icon_96, course_icon_260, 
					course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times,
					course_created, course_updated) 
					VALUES 
					(NULL,?,?,?,?,
					?,?,?,?,?,
					?,?,?,?,?,
					?,?,?,?,?,
					?,?,?,?,?,
					?,?)");
				$stmt->bind_param("ssssssssssssssssssssssssss", $inp_title, $inp_title_clean, $inp_front_page_intro, $inp_description, 
					$inp_contents, $inp_language, $get_current_main_category_id, $inp_main_category_title, $get_current_sub_category_id, 
					$inp_sub_category_title, $inp_intro_video_embedded, $inp_image_file, $inp_image_thumb, $inp_icon_a, 
					$inp_icon_b, $inp_icon_c, $inp_icon_d, $inp_icon_e, $inp_icon_f, 
					$zero, $zero, $zero, $zero, $zero, 
					$datetime, $datetime); 
				$stmt->execute();

				// Get ID
				$query = "SELECT course_id, course_title, course_title_clean, course_is_active, course_front_page_intro, course_description, course_contents, course_language, course_main_category_id, course_main_category_title, course_sub_category_id, course_sub_category_title, course_intro_video_embedded, course_image_file, course_image_thumb, course_icon_16, course_icon_32, course_icon_48, course_icon_64, course_icon_96, course_icon_260, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times, course_read_times_ip_block, course_created, course_updated FROM $t_courses_index WHERE course_created='$datetime'";
				$result = $mysqli->query($query);
				$row = $result->fetch_row();
				list($get_current_course_id, $get_current_course_title, $get_current_course_title_clean, $get_current_course_is_active, $get_current_course_front_page_intro, $get_current_course_description, $get_current_course_contents, $get_current_course_language, $get_current_course_main_category_id, $get_current_course_main_category_title, $get_current_course_sub_category_id, $get_current_course_sub_category_title, $get_current_course_intro_video_embedded, $get_current_course_image_file, $get_current_course_image_thumb, $get_current_course_icon_16, $get_current_course_icon_32, $get_current_course_icon_48, $get_current_course_icon_64, $get_current_course_icon_96, $get_current_course_icon_260, $get_current_course_modules_count, $get_current_course_lessons_count, $get_current_course_quizzes_count, $get_current_course_users_enrolled_count, $get_current_course_read_times, $get_current_course_read_times_ip_block, $get_current_course_created, $get_current_course_updated) = $row;

				// Title
				$stmt = $mysqli->prepare("SELECT courses_title_translation_id, courses_title_translation_title FROM $t_courses_title_translations WHERE courses_title_translation_language=?");
				echo"SELECT courses_title_translation_id, courses_title_translation_title FROM $t_courses_title_translations WHERE courses_title_translation_language=$inp_language"; 
				$stmt->bind_param("s", $inp_language);
				$stmt->execute();
				$result = $stmt->get_result();
				$row = $result->fetch_row();
				list($get_current_courses_title_translation_id, $get_current_courses_title_translation_title) = $row;
				if($get_current_courses_title_translation_id == ""){

					$inp_translation_title = "Courses";
					$stmt = $mysqli->prepare("INSERT INTO $t_courses_title_translations
						(courses_title_translation_id, courses_title_translation_title, courses_title_translation_language) 
						VALUES 
						(NULL,?,?)");
					$stmt->bind_param("ss",  $inp_translation_title, $inp_language); 
					$stmt->execute();

					$get_current_courses_title_translation_title = "Courses";
				}



				// Search engine
				$inp_index_title = "$inp_title | $get_current_courses_title_translation_title";

				$inp_index_url = "$inp_title_clean";
				$inp_index_keywords = "";
				$inp_index_module_name = "courses";
				$inp_index_module_part_name = "course";
				$inp_index_reference_name = "course_id";

				$stmt = $mysqli->prepare("INSERT INTO $t_search_engine_index 
					(index_id, index_title, index_url, index_short_description, index_keywords, 
					index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
					index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
					index_unique_hits) 
					VALUES 
					(NULL,?,?,?,?,
					?,?,?,?,?,
					?,?,?,?,?,
					?)");
				$stmt->bind_param("sssssssssssssss", $inp_index_title, $inp_index_url, $inp_front_page_intro, $inp_index_keywords, 
					$inp_index_module_name, $inp_index_module_part_name, $zero, $inp_index_reference_name, $get_current_course_id, 
					$zero, $zero, $datetime, $datetime_saying, $inp_language,
					$zero); 
				$stmt->execute();


				// Write to files
				include("_inc/courses/courses_write_to_file_include.php");


				// Header
				$url = "index.php?open=$open&page=courses_open&course_id=$get_current_course_id&editor_language=$editor_language&ft=success&fm=course_created";
				header("Location: $url");
				exit;
			}

			echo"
			<h1>New course</h1>
				

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
				<a href=\"index.php?open=courses&amp;page=courses_new&amp;editor_language=$editor_language&amp;l=$l\">New course</a>
				&gt;
				<a href=\"index.php?open=courses&amp;page=courses_new&amp;action=step_2_select_sub_category&amp;main_category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_main_category_title</a>
				&gt;
				<a href=\"index.php?open=courses&amp;page=courses_new&amp;action=step_3_course_info&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_sub_category_title</a>
				</p>
			<!-- //Where am I? -->


			<!-- About create course -->
				<p style=\"padding-bottom: 0;margin-bottom: 0;\"><b>About new course:</b></p>

				<ul>
					<li><p>When you create a course a directory with course title will be created</p></li>
					<li><p>A index.php file <tt>{course_title}/index.php</tt> file will be created that includes <tt>courses/_includes/course.php</tt></p></li>
					<li><p>A course backup file _course.php <tt>{course_title}/_course.php</tt> will be created with course information.</p></li>
				</ul>
			<!-- //About create course -->

			<!-- New course form -->
		
				<script>
				window.onload = function() {
					document.getElementById(\"inp_title\").focus();
				}
				</script>
			
				<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=step_3_course_info&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

				<p><b>Title:</b><br />
				<input type=\"text\" name=\"inp_title\" id=\"inp_title\" value=\"\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" style=\"width: 100%;\" />
				</p>

				<p><b>Front page intro:</b><br />
				<textarea name=\"inp_front_page_intro\" rows=\"8\" cols=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea>
				</p>

				<p><b>Description:</b><br />
				<textarea name=\"inp_description\" rows=\"8\" cols=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea>
				</p>

				<p><b>Contents (one bullet point at each line):</b><br />
				<textarea name=\"inp_contents\" rows=\"8\" cols=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea>
				</p>

				<p><b>Intro video embedded:</b><br />
				<input type=\"text\" name=\"inp_intro_video_embedded\" value=\"\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>
	
				<p><input type=\"submit\" value=\"Create\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>

				</form>
			<!-- //New course form -->
			";
		} // sub category found
	} // main category found
} // action == "step_3_course_info"
?>