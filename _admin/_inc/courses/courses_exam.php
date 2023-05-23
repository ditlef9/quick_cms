<?php
/**
*
* File: _admin/_inc/comments/courses_open.php
* Version 2
* Copyright (c) 2019-2023 Sindre Andre Ditlefsen
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
if(isset($_GET['course_id'])){
	$course_id = $_GET['course_id'];
	$course_id = strip_tags(stripslashes($course_id));
}
else{
	$course_id = "";
}
$stmt = $mysqli->prepare("SELECT course_id, course_title, course_title_clean, course_is_active, course_front_page_intro, course_description, course_contents, course_language, course_main_category_id, course_main_category_title, course_sub_category_id, course_sub_category_title, course_intro_video_embedded, course_image_file, course_image_thumb, course_icon_48, course_icon_64, course_icon_96, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times, course_read_times_ip_block, course_created, course_updated FROM $t_courses_index WHERE course_id=?"); 
$stmt->bind_param("s", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_current_course_id, $get_current_course_title, $get_current_course_title_clean, $get_current_course_is_active, $get_current_course_front_page_intro, $get_current_course_description, $get_current_course_contents, $get_current_course_language, $get_current_course_main_category_id, $get_current_course_main_category_title, $get_current_course_sub_category_id, $get_current_course_sub_category_title, $get_current_course_intro_video_embedded, $get_current_course_image_file, $get_current_course_image_thumb, $get_current_course_icon_48, $get_current_course_icon_64, $get_current_course_icon_96, $get_current_course_modules_count, $get_current_course_lessons_count, $get_current_course_quizzes_count, $get_current_course_users_enrolled_count, $get_current_course_read_times, $get_current_course_read_times_ip_block, $get_current_course_created, $get_current_course_updated) = $row;

if($get_current_course_id == ""){
	echo"<p>Server error 404.</p>";
}
else{
	// Find category
	$query = "SELECT main_category_id, main_category_title, main_category_title_clean, main_category_description, main_category_language, main_category_created, main_category_updated FROM $t_courses_categories_main WHERE main_category_id=$get_current_course_main_category_id";
	$result = $mysqli->query($query);
	$row = $result->fetch_row();
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_title_clean, $get_current_main_category_description, $get_current_main_category_language, $get_current_main_category_created, $get_current_main_category_updated) = $row;

	$query = "SELECT sub_category_id, sub_category_title, sub_category_title_clean, sub_category_description, sub_category_main_category_id, sub_category_main_category_title, sub_category_language, sub_category_created, sub_category_updated FROM $t_courses_categories_sub WHERE sub_category_id=$get_current_course_sub_category_id";
	$result = $mysqli->query($query);
	$row = $result->fetch_row();
	list($get_current_sub_category_id, $get_current_sub_category_title, $get_current_sub_category_title_clean, $get_current_sub_category_description, $get_current_sub_category_main_category_id, $get_current_sub_category_main_category_title, $get_current_sub_category_language, $get_current_sub_category_created, $get_current_sub_category_updated) = $row;

	// Find exam
	$query = "SELECT exam_id, exam_course_id, exam_course_title, exam_language, exam_total_questions, exam_total_points, exam_points_needed_to_pass FROM $t_courses_exams_index WHERE exam_course_id=$get_current_course_id";
	$result = $mysqli->query($query);
	$row = $result->fetch_row();
	list($get_current_exam_id, $get_current_exam_course_id, $get_current_exam_course_title, $get_current_exam_language, $get_current_exam_total_questions, $get_current_exam_total_points, $get_current_exam_points_needed_to_pass) = $row;
	if($get_current_exam_id == ""){
		// Insert exam
		$inp_title = "$get_current_course_title";
		$inp_title_clean = "$get_current_course_title_clean";
		$inp_language = "$get_current_course_language";

		$zero = 0;

		$stmt = $mysqli->prepare("INSERT INTO $t_courses_exams_index 
			(exam_id, exam_course_id, exam_course_title, exam_language, exam_total_questions, 
			exam_total_points, exam_points_needed_to_pass) 
			VALUES 
			(NULL,?,?,?,?,
			?,?)");
		$stmt->bind_param("ssssss", $get_current_course_id, $inp_title, $inp_language, $zero,
			$zero, $zero); 
		$stmt->execute();

		
		$query = "SELECT exam_id, exam_course_id, exam_course_title, exam_language, exam_total_questions, exam_total_points, exam_points_needed_to_pass FROM $t_courses_exams_index WHERE exam_course_id=$get_current_course_id";
		$result = $mysqli->query($query);
		$row = $result->fetch_row();
		list($get_current_exam_id, $get_current_exam_course_id, $get_current_exam_course_title, $get_current_exam_language, $get_current_exam_total_questions, $get_current_exam_total_points, $get_current_exam_points_needed_to_pass) = $row;

	}

	if($action == ""){
		if($process == "1"){
			$inp_points_needed_to_pass = $_POST['inp_points_needed_to_pass'];
			$inp_points_needed_to_pass = output_html($inp_points_needed_to_pass);
			

			$stmt = $mysqli->prepare("UPDATE $t_courses_exams_index SET exam_points_needed_to_pass=? WHERE exam_id=?");
			$stmt->bind_param("ss", $inp_points_needed_to_pass, $get_current_exam_id); 
			$stmt->execute();

			$url = "index.php?open=$open&page=$page&course_id=$course_id&editor_language=$editor_language&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;
		}
		echo"
		<h1>$get_current_course_title</h1>
				

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
			<a href=\"index.php?open=courses&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Courses</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=open_main_category&amp;main_category_id=$get_current_course_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_course_main_category_title</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=open_sub_category&amp;sub_category_id=$get_current_course_sub_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_course_sub_category_title</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=courses_open&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_course_title</a>
			</p>
		<!-- //Where am I? -->

		<!-- Course navigation -->
			<div class=\"tabs\">
				<ul>
					<li><a href=\"index.php?open=courses&amp;page=courses_open&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Info</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_modules_and_lessons&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Modules and lessons</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_image&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Image</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_icon&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Icon</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_exam&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"active\">Exam</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_read_from_file&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Read from file</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_write_to_file&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Write to file</a>
					<li><a href=\"index.php?open=courses&amp;page=courses_delete&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
				</ul>
			</div>
			<div class=\"clear\" style=\"height: 10px;\"></div>
		<!-- //Course navigation -->

		<!-- Actions -->
			<table>
			  <tr>
			   <td style=\"padding-right: 40px;vertical-align: top;\">
				<p>
				<br />
				<a href=\"index.php?open=courses&amp;page=courses_exam&amp;course_id=$course_id&amp;action=new_question&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">New question</a>
				</p>
			   </td>
			   <td style=\"padding-right: 40px;vertical-align: top;\">
				<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">
				<p>
				Points needed to pass:<br />
				<input type=\"text\" name=\"inp_points_needed_to_pass\" value=\"$get_current_exam_points_needed_to_pass\" size=\"2\" />
				<input type=\"submit\" value=\"Save\" class=\"btn_default\" />
				</p>
				</form>
			   </td>
			  </tr>
			</table>
		<!-- //Actions -->


		<!-- Questions -->

			

			<table class=\"hor-zebra\">
			 <thead>
			  <tr>
			   <th scope=\"col\">
				<span>No</span>
			   </th>
			   <th scope=\"col\">
				<span>Question</span>
			   </th>
			   <th scope=\"col\">
				<span>Points</span>
			   </th>
			   <th scope=\"col\">
				<span>Actions</span>
			   </th>
			  </tr>
			 </thead>
			 <tbody>";
			$total_questions = 0;
			$total_points = 0;
			$query = "SELECT qa_id, qa_course_id, qa_course_title, qa_exam_id, qa_question_number, qa_question, qa_text, qa_type, qa_alt_a, qa_alt_b, qa_alt_c, qa_alt_d, qa_alt_e, qa_alt_f, qa_alt_g, qa_alt_h, qa_alt_i, qa_alt_j, qa_alt_k, qa_alt_l, qa_alt_m, qa_alt_n, qa_correct_alternatives, qa_points, qa_hint, qa_explanation FROM $t_courses_exams_qa WHERE qa_course_id=$get_current_course_id ORDER BY qa_question_number ASC";
			$result = $mysqli->query($query);
			while($row = $result->fetch_row()) {
				list($get_qa_id, $get_qa_course_id, $get_qa_course_title, $get_qa_exam_id, $get_qa_question_number, $get_qa_question, $get_qa_text, $get_qa_type, $get_qa_alt_a, $get_qa_alt_b, $get_qa_alt_c, $get_qa_alt_d, $get_qa_alt_e, $get_qa_alt_f, $get_qa_alt_g, $get_qa_alt_h, $get_qa_alt_i, $get_qa_alt_j, $get_qa_alt_k, $get_qa_alt_l, $get_qa_alt_m, $get_qa_alt_n, $get_qa_correct_alternatives, $get_qa_points, $get_qa_hint, $get_qa_explanation) = $row;
	
				// Style
				if(isset($style) && $style == ""){
					$style = "odd";
				}
				else{
					$style = "";
				}

				// Question number
				$total_questions = $total_questions+1;
				if($total_questions != "$get_qa_question_number"){
					
					$mysqli->query("UPDATE $t_courses_exams_qa SET qa_question_number=$total_questions WHERE qa_id=$get_qa_id") or die($mysqli->error);
					
					$get_qa_question_number = "$total_questions";
				}

				echo"
				 <tr>
				  <td class=\"$style\">
					<span>$get_qa_question_number</span>
				  </td>
				  <td class=\"$style\">
					<span>$get_qa_question</span>
				  </td>
				  <td class=\"$style\">
					<span>$get_qa_points</span>
				  </td>
				  <td class=\"$style\">
					<span>
					<a href=\"index.php?open=courses&amp;page=courses_exam&amp;course_id=$course_id&amp;action=edit_question&amp;qa_id=$get_qa_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
					&middot;
					<a href=\"index.php?open=courses&amp;page=courses_exam&amp;course_id=$course_id&amp;action=delete_question&amp;qa_id=$get_qa_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
					</span>
				  </td>
				 </tr>";

				// Counter
				$total_points = $total_points+$get_qa_points;
			} // while
			if($total_points != "$get_current_exam_total_points"){
				$mysqli->query("UPDATE $t_courses_exams_index SET exam_total_points=$total_points WHERE exam_id=$get_current_exam_id") or die($mysqli->error);
			}
			if($total_questions != "$get_current_exam_total_questions"){
				$mysqli->query("UPDATE $t_courses_exams_index SET exam_total_questions=$total_questions WHERE exam_id=$get_current_exam_id") or die($mysqli->error);
			}
			echo"
			 </tbody>
			</table>
		<!-- //Questions -->
		";
	} // action ==""
	elseif($action == "new_question"){
		if($process == "1"){
			$inp_question = $_POST['inp_question'];
			$inp_question = output_html($inp_question);

			$inp_text = $_POST['inp_text'];
			$inp_text = output_html($inp_text);

			$inp_type = $_POST['inp_type'];
			$inp_type = output_html($inp_type);


			$letters = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N");
			$inp_correct_alternatives = "";
			for($x=0;$x<sizeof($letters);$x++){
				$letter_lowercase = strtolower($letters[$x]);
				$inp_alt = $_POST["inp_alt_$letter_lowercase"];
				$inp_alt = output_html($inp_alt);

				if(isset($_POST["inp_correct_alternative_$letter_lowercase"])){
					if($inp_correct_alternatives == ""){
						$inp_correct_alternatives = "$letter_lowercase";
					}
					else{
						$inp_correct_alternatives = $inp_correct_alternatives . ",$letter_lowercase";
					}
				}

				if($letter_lowercase == "a"){
					$inp_alt_a = "$inp_alt";
				}
				elseif($letter_lowercase == "b"){
					$inp_alt_b = "$inp_alt";
				}
				elseif($letter_lowercase == "c"){
					$inp_alt_c = "$inp_alt";
				}
				elseif($letter_lowercase == "d"){
					$inp_alt_d = "$inp_alt";
				}
				elseif($letter_lowercase == "e"){
					$inp_alt_e = "$inp_alt";
				}
				elseif($letter_lowercase == "f"){
					$inp_alt_f = "$inp_alt";
				}
				elseif($letter_lowercase == "g"){
					$inp_alt_g = "$inp_alt";
				}
				elseif($letter_lowercase == "h"){
					$inp_alt_h = "$inp_alt";
				}
				elseif($letter_lowercase == "i"){
					$inp_alt_i = "$inp_alt";
				}
				elseif($letter_lowercase == "j"){
					$inp_alt_j = "$inp_alt";
				}
				elseif($letter_lowercase == "k"){
					$inp_alt_k = "$inp_alt";
				}
				elseif($letter_lowercase == "l"){
					$inp_alt_l = "$inp_alt";
				}
				elseif($letter_lowercase == "m"){
					$inp_alt_m = "$inp_alt";
				}
				elseif($letter_lowercase == "n"){
					$inp_alt_n = "$inp_alt";
				}
			}

			$inp_points = $_POST['inp_points'];
			$inp_points = output_html($inp_points);

			$inp_hint = $_POST['inp_hint'];
			$inp_hint = output_html($inp_hint);

			$inp_explanation = $_POST['inp_explanation'];
			$inp_explanation = output_html($inp_explanation);

			$inp_course_title = "$get_current_course_title";
			$inp_course_title_clean = "$get_current_course_title_clean";

			$inp_qa_question_number = 99;

			$stmt = $mysqli->prepare("INSERT INTO $t_courses_exams_qa
				(qa_id, qa_course_id, qa_course_title, qa_exam_id, qa_question_number, 
				qa_question, qa_text, qa_type, qa_alt_a, qa_alt_b, 
				qa_alt_c, qa_alt_d, qa_alt_e, qa_alt_f, qa_alt_g,
				qa_alt_h, qa_alt_i, qa_alt_j, qa_alt_k, qa_alt_l, 
				qa_alt_m, qa_alt_n, qa_correct_alternatives, qa_points, qa_hint, 
				qa_explanation) 
				VALUES 
				(NULL,?,?,?,?,
				?,?,?,?,?,
				?,?,?,?,?,
				?,?,?,?,?,
				?,?,?,?,?,
				?)");
			$stmt->bind_param("sssssssssssssssssssssssss", $get_current_course_id, $inp_course_title, $get_current_exam_id, $inp_qa_question_number, 
				$inp_question, $inp_text, $inp_type, $inp_alt_a, $inp_alt_b, 
				$inp_alt_c, $inp_alt_d, $inp_alt_e, $inp_alt_f, $inp_alt_g,
				$inp_alt_h, $inp_alt_i, $inp_alt_j, $inp_alt_k, $inp_alt_l,
				$inp_alt_m, $inp_alt_n, $inp_correct_alternatives, $inp_points, $inp_hint, 
				$inp_explanation); 
			$stmt->execute();

			// Update question number, total questions, and points needed
			$total_questions = 0;
			$total_points = 0;
			$query = "SELECT qa_id, qa_course_id, qa_course_title, qa_exam_id, qa_question_number, qa_question, qa_text, qa_type, qa_alt_a, qa_alt_b, qa_alt_c, qa_alt_d, qa_alt_e, qa_alt_f, qa_alt_g, qa_alt_h, qa_alt_i, qa_alt_j, qa_alt_k, qa_alt_l, qa_alt_m, qa_alt_n, qa_correct_alternatives, qa_points, qa_hint, qa_explanation FROM $t_courses_exams_qa WHERE qa_course_id=$get_current_course_id ORDER BY qa_question_number ASC";
			$result = $mysqli->query($query);
			while($row = $result->fetch_row()) {
				list($get_qa_id, $get_qa_course_id, $get_qa_course_title, $get_qa_exam_id, $get_qa_question_number, $get_qa_question, $get_qa_text, $get_qa_type, $get_qa_alt_a, $get_qa_alt_b, $get_qa_alt_c, $get_qa_alt_d, $get_qa_alt_e, $get_qa_alt_f, $get_qa_alt_g, $get_qa_alt_h, $get_qa_alt_i, $get_qa_alt_j, $get_qa_alt_k, $get_qa_alt_l, $get_qa_alt_m, $get_qa_alt_n, $get_qa_correct_alternatives, $get_qa_points, $get_qa_hint, $get_qa_explanation) = $row;
	

				// Question number
				$total_questions = $total_questions+1;
				if($total_questions != "$get_qa_question_number"){
					
					$mysqli->query("UPDATE $t_courses_exams_qa SET qa_question_number=$total_questions WHERE qa_id=$get_qa_id") or die($mysqli->error);

					$get_qa_question_number = "$total_questions";
				}

				// Counter
				$total_points = $total_points+$get_qa_points;
			} // while
			// Total points needed to pass = 90 %
			$points_needed_to_pass = floor(($total_points*90)/100);

			$mysqli->query("UPDATE $t_courses_exams_index SET exam_total_points=$total_points, exam_total_questions=$total_questions, exam_points_needed_to_pass=$points_needed_to_pass WHERE exam_id=$get_current_exam_id") or die($mysqli->error);


			$url = "index.php?open=$open&page=$page&course_id=$course_id&action=new_question&editor_language=$editor_language&ft=success&fm=question_$inp_question" . "_saved";
			header("Location: $url");
			exit;
		}
		echo"
		<h1>$get_current_course_title</h1>
				

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
			<a href=\"index.php?open=courses&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Courses</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=open_main_category&amp;main_category_id=$get_current_course_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_course_main_category_title</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=open_sub_category&amp;sub_category_id=$get_current_course_sub_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_course_sub_category_title</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=courses_open&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_course_title</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=courses_exam&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Exam</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=courses_exam&amp;course_id=$course_id&amp;action=$action&amp;editor_language=$editor_language&amp;l=$l\">New question</a>
			</p>
		<!-- //Where am I? -->

		<!-- New question form -->
			<h2>New exam question</h2>
			<script>
			window.onload = function() {
				document.getElementById(\"inp_title\").focus();
			}
			</script>
			
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;course_id=$course_id&amp;action=new_question&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>Question:</b><br />
			<span class=\"small\">Example: What datatype does the program get here?</span><br />
			<input type=\"text\" name=\"inp_question\" value=\"\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" style=\"width: 95%\" />
			</p>

			<p><b>Text:</b><br />
			<span class=\"small\">Example: dietPlanId = b.getInt(&quot;dietPlanId&quot;);</span><br />
			<textarea name=\"inp_text\" rows=\"5\" cols=\"80\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea>
			</p>

			<p><b>Type:</b><br />
			<select name=\"inp_type\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
				<option value=\"radio\">Radio (one correct answer)</option>
				<option value=\"checkbox\">Checkbox (many correct answers)</option>
			</select>
			</p>

			<table style=\"width: 100%\">";
			$letters = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N");
			for($x=0;$x<sizeof($letters);$x++){
				$letter_lowercase = strtolower($letters[$x]);
				echo"
				 <tr>
				  <td style=\"padding-right: 5px;\">
					<p><b>Alternative $letters[$x]:</b><br />
					<input type=\"text\" name=\"inp_alt_$letter_lowercase\" value=\"\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" style=\"width: 100%\" />
					</p>
				  </td>
				  <td>
					<p><b>Correct?</b><br />
					<input type=\"checkbox\" name=\"inp_correct_alternative_$letter_lowercase\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>
					
				  </td>
				";
			}
			echo"
			</table>

			<p><b>Points:</b><br />
			<input type=\"text\" name=\"inp_points\" value=\"1\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p><b>Hint:</b><br />
			<input type=\"text\" name=\"inp_hint\" value=\"\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p><b>Explanation:</b><br />
			<textarea name=\"inp_explanation\" rows=\"5\" cols=\"80\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea>
			</p>

			<p>
			<input type=\"submit\" value=\"Create\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>
		<!-- //New question form -->
		
		";
	} // action == new question
	elseif($action == "edit_question"){
		if(isset($_GET['qa_id'])){
			$qa_id = $_GET['qa_id'];
			$qa_id = strip_tags(stripslashes($qa_id));
		}
		else{
			$qa_id = "";
		}
		$stmt = $mysqli->prepare("SELECT qa_id, qa_course_id, qa_course_title, qa_exam_id, qa_question_number, qa_question, qa_text, qa_type, qa_alt_a, qa_alt_b, qa_alt_c, qa_alt_d, qa_alt_e, qa_alt_f, qa_alt_g, qa_alt_h, qa_alt_i, qa_alt_j, qa_alt_k, qa_alt_l, qa_alt_m, qa_alt_n, qa_correct_alternatives, qa_points, qa_hint, qa_explanation FROM $t_courses_exams_qa WHERE qa_id=? AND qa_course_id=?"); 
		$stmt->bind_param("ss", $qa_id, $get_current_course_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_row();
		list($get_current_qa_id, $get_current_qa_course_id, $get_current_qa_course_title, $get_current_qa_exam_id, $get_current_qa_question_number, $get_current_qa_question, $get_current_qa_text, $get_current_qa_type, $get_current_qa_alt_a, $get_current_qa_alt_b, $get_current_qa_alt_c, $get_current_qa_alt_d, $get_current_qa_alt_e, $get_current_qa_alt_f, $get_current_qa_alt_g, $get_current_qa_alt_h, $get_current_qa_alt_i, $get_current_qa_alt_j, $get_current_qa_alt_k, $get_current_qa_alt_l, $get_current_qa_alt_m, $get_current_qa_alt_n, $get_current_qa_correct_alternatives, $get_current_qa_points, $get_current_qa_hint, $get_current_qa_explanation) = $row;

		if($get_current_qa_id == ""){
			echo"<p>Server error 404.</p>";
		}
		else{
			if($process == "1"){
				$inp_question = $_POST['inp_question'];
				$inp_question = output_html($inp_question);

				$inp_text = $_POST['inp_text'];
				$inp_text = output_html($inp_text);
	
				$inp_type = $_POST['inp_type'];
				$inp_type = output_html($inp_type);


				$letters = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N");
				$inp_correct_alternatives = "";
				for($x=0;$x<sizeof($letters);$x++){
					$letter_lowercase = strtolower($letters[$x]);
					$inp_alt = $_POST["inp_alt_$letter_lowercase"];
					$inp_alt = output_html($inp_alt);

					if(isset($_POST["inp_correct_alternative_$letter_lowercase"])){
						if($inp_correct_alternatives == ""){
							$inp_correct_alternatives = "$letter_lowercase";
						}
						else{
							$inp_correct_alternatives = $inp_correct_alternatives . ",$letter_lowercase";
						}
					}

					if($letter_lowercase == "a"){
						$inp_alt_a = "$inp_alt";
					}
					elseif($letter_lowercase == "b"){
						$inp_alt_b = "$inp_alt";
					}
					elseif($letter_lowercase == "c"){
						$inp_alt_c = "$inp_alt";
					}
					elseif($letter_lowercase == "d"){
						$inp_alt_d = "$inp_alt";
					}
					elseif($letter_lowercase == "e"){
						$inp_alt_e = "$inp_alt";
					}
					elseif($letter_lowercase == "f"){
						$inp_alt_f = "$inp_alt";
					}
					elseif($letter_lowercase == "g"){
						$inp_alt_g  = "$inp_alt";
					}
					elseif($letter_lowercase == "h"){
						$inp_alt_h = "$inp_alt";
					}
					elseif($letter_lowercase == "i"){
						$inp_alt_i = "$inp_alt";
					}
					elseif($letter_lowercase == "j"){
						$inp_alt_j = "$inp_alt";
					}
					elseif($letter_lowercase == "k"){
						$inp_alt_k = "$inp_alt";
					}
					elseif($letter_lowercase == "l"){
						$inp_alt_l = "$inp_alt";
					}
					elseif($letter_lowercase == "m"){
						$inp_alt_m = "$inp_alt";
					}
					elseif($letter_lowercase == "n"){
						$inp_alt_n = "$inp_alt";
					}
				}
				

				$inp_points = $_POST['inp_points'];
				$inp_points = output_html($inp_points);
				

				$inp_hint = $_POST['inp_hint'];
				$inp_hint = output_html($inp_hint);
				

				$inp_explanation = $_POST['inp_explanation'];
				$inp_explanation = output_html($inp_explanation);
				
	
				$stmt = $mysqli->prepare("UPDATE $t_courses_exams_qa SET 
					qa_question=?, 
					qa_text=?, 
					qa_type=?, 
					qa_alt_a=?, 
					qa_alt_b=?, 
					qa_alt_c=?, 
					qa_alt_d=?, 
					qa_alt_e=?, 
					qa_alt_f=?, 
					qa_alt_g=?, 
					qa_alt_h=?, 
					qa_alt_i=?, 
					qa_alt_j=?, 
					qa_alt_k=?, 
					qa_alt_l=?, 
					qa_alt_m=?, 
					qa_alt_n=?, 
					qa_correct_alternatives=?, 
					qa_points=?, 
					qa_hint=?, 
					qa_explanation=? 
					WHERE qa_id=?");
				$stmt->bind_param("ssssssssssssssssssss", $inp_question, 
					$inp_text, 
					$inp_type, 
					$inp_alt_a, 
					$inp_alt_b, 
					$inp_alt_c,  
					$inp_alt_d, 
					$inp_alt_e, 
					$inp_alt_f, 
					$inp_alt_g, 
					$inp_alt_h, 
					$inp_alt_i, 
					$inp_alt_j, 
					$inp_alt_k, 
					$inp_alt_l, 
					$inp_alt_m, 
					$inp_alt_n, 
					$inp_correct_alternatives, 
					$inp_points, 
					$inp_hint, 
					$inp_explanation, 
					$get_current_qa_id); 
				$stmt->execute();


				$url = "index.php?open=$open&page=$page&course_id=$course_id&action=edit_question&qa_id=$get_current_qa_id&editor_language=$editor_language&ft=success&fm=question_saved";
				header("Location: $url");
				exit;
			}
			echo"
			<h1>$get_current_course_title</h1>
				

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
				<a href=\"index.php?open=courses&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Courses</a>
				&gt;
				<a href=\"index.php?open=courses&amp;page=open_main_category&amp;main_category_id=$get_current_course_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_course_main_category_title</a>
				&gt;
				<a href=\"index.php?open=courses&amp;page=open_sub_category&amp;sub_category_id=$get_current_course_sub_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_course_sub_category_title</a>
				&gt;
				<a href=\"index.php?open=courses&amp;page=courses_open&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_course_title</a>
				&gt;
				<a href=\"index.php?open=courses&amp;page=courses_exam&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Exam</a>
				&gt;
				<a href=\"index.php?open=courses&amp;page=courses_exam&amp;course_id=$course_id&amp;action=$action&amp;qa_id=$get_current_qa_id&amp;editor_language=$editor_language&amp;l=$l\">Edit question</a>
				</p>
			<!-- //Where am I? -->

			<!-- Edit question form -->
				<h2>Edit exam question</h2>
				<script>
				window.onload = function() {
					document.getElementById(\"inp_question\").focus();
				}
				</script>
			
				<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;course_id=$course_id&amp;action=edit_question&amp;qa_id=$get_current_qa_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

				<p><b>Question:</b><br />
				<input type=\"text\" name=\"inp_question\" id=\"inp_question\" value=\"$get_current_qa_question\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>

				<p><b>Text:</b><br />
				<textarea name=\"inp_text\" rows=\"5\" cols=\"80\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
				$get_current_qa_text = str_replace("<br />", "\n", $get_current_qa_text);
				echo"$get_current_qa_text</textarea>
				</p>

				<p><b>Type:</b><br />
				<select name=\"inp_type\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
				<option value=\"radio\""; if($get_current_qa_type == "radio"){ echo" selected=\"selected\""; } echo">Radio (one correct answer)</option>
				<option value=\"checkbox\""; if($get_current_qa_type == "checkbox"){ echo" selected=\"selected\""; } echo">Checkbox (many correct answers)</option>
				</select>
				</p>

				";
				$alternatives = explode(",", $get_current_qa_correct_alternatives);
				echo"
				<table>
				 <tr>
				  <td style=\"padding-right: 5px;\">
					<p><b>Alternative a:</b><br />
					<input type=\"text\" name=\"inp_alt_a\" value=\"$get_current_qa_alt_a\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>
				  </td>
				  <td>
					<p><b>Correct?</b><br />
					<input type=\"checkbox\" name=\"inp_correct_alternative_a\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"";
					for($x=0;$x<sizeof($alternatives);$x++){
						$temp = $alternatives[$x];
						if($temp == "a"){
							echo" checked=\"checked\""; 
							break;
						}
					}
					echo" />
					</p>
				  </td>
				 </tr>

				 <tr>
				  <td style=\"padding-right: 5px;\">
					<p><b>Alternative b:</b><br />
					<input type=\"text\" name=\"inp_alt_b\" value=\"$get_current_qa_alt_b\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>
				  </td>
				  <td>
					<p><b>Correct?</b><br />
					<input type=\"checkbox\" name=\"inp_correct_alternative_b\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"";
					for($x=0;$x<sizeof($alternatives);$x++){
						$temp = $alternatives[$x];
						if($temp == "b"){
							echo" checked=\"checked\""; 
							break;
						}
					}
					echo" />
					</p>
				  </td>
				 </tr>

				 <tr>
				  <td style=\"padding-right: 5px;\">
					<p><b>Alternative c:</b><br />
					<input type=\"text\" name=\"inp_alt_c\" value=\"$get_current_qa_alt_c\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>
				  </td>
				  <td>
					<p><b>Correct?</b><br />
					<input type=\"checkbox\" name=\"inp_correct_alternative_c\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"";
					for($x=0;$x<sizeof($alternatives);$x++){
						$temp = $alternatives[$x];
						if($temp == "c"){
							echo" checked=\"checked\""; 
							break;
						}
					}
					echo" />
					</p>
				  </td>
				 </tr>

				 <tr>
				  <td style=\"padding-right: 5px;\">
					<p><b>Alternative d:</b><br />
					<input type=\"text\" name=\"inp_alt_d\" value=\"$get_current_qa_alt_d\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>
				  </td>
				  <td>
					<p><b>Correct?</b><br />
					<input type=\"checkbox\" name=\"inp_correct_alternative_d\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"";
					for($x=0;$x<sizeof($alternatives);$x++){
						$temp = $alternatives[$x];
						if($temp == "d"){
							echo" checked=\"checked\""; 
							break;
						}
					}
					echo" />
					</p>
				  </td>
				 </tr>

				 <tr>
				  <td style=\"padding-right: 5px;\">
					<p><b>Alternative e:</b><br />
					<input type=\"text\" name=\"inp_alt_e\" value=\"$get_current_qa_alt_e\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>
				  </td>
				  <td>
					<p><b>Correct?</b><br />
					<input type=\"checkbox\" name=\"inp_correct_alternative_e\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"";
					for($x=0;$x<sizeof($alternatives);$x++){
						$temp = $alternatives[$x];
						if($temp == "e"){
							echo" checked=\"checked\""; 
							break;
						}
					}
					echo" />
					</p>
				  </td>
				 </tr>

				 <tr>
				  <td style=\"padding-right: 5px;\">
					<p><b>Alternative f:</b><br />
					<input type=\"text\" name=\"inp_alt_f\" value=\"$get_current_qa_alt_f\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>
				  </td>
				  <td>
					<p><b>Correct?</b><br />
					<input type=\"checkbox\" name=\"inp_correct_alternative_f\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"";
					for($x=0;$x<sizeof($alternatives);$x++){
						$temp = $alternatives[$x];
						if($temp == "f"){
							echo" checked=\"checked\""; 
							break;
						}
					}
					echo" />
					</p>
				  </td>
				 </tr>

				 <tr>
				  <td style=\"padding-right: 5px;\">
					<p><b>Alternative g:</b><br />
					<input type=\"text\" name=\"inp_alt_g\" value=\"$get_current_qa_alt_g\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>
				  </td>
				  <td>
					<p><b>Correct?</b><br />
					<input type=\"checkbox\" name=\"inp_correct_alternative_g\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"";
					for($x=0;$x<sizeof($alternatives);$x++){
						$temp = $alternatives[$x];
						if($temp == "g"){
							echo" checked=\"checked\""; 
							break;
						}
					}
					echo" />
					</p>
				  </td>
				 </tr>

				 <tr>
				  <td style=\"padding-right: 5px;\">
					<p><b>Alternative h:</b><br />
					<input type=\"text\" name=\"inp_alt_h\" value=\"$get_current_qa_alt_h\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>
				  </td>
				  <td>
					<p><b>Correct?</b><br />
					<input type=\"checkbox\" name=\"inp_correct_alternative_h\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"";
					for($x=0;$x<sizeof($alternatives);$x++){
						$temp = $alternatives[$x];
						if($temp == "h"){
							echo" checked=\"checked\""; 
							break;
						}
					}
					echo" />
					</p>
				  </td>
				 </tr>

				 <tr>
				  <td style=\"padding-right: 5px;\">
					<p><b>Alternative i:</b><br />
					<input type=\"text\" name=\"inp_alt_i\" value=\"$get_current_qa_alt_i\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>
				  </td>
				  <td>
					<p><b>Correct?</b><br />
					<input type=\"checkbox\" name=\"inp_correct_alternative_i\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"";
					for($x=0;$x<sizeof($alternatives);$x++){
						$temp = $alternatives[$x];
						if($temp == "i"){
							echo" checked=\"checked\""; 
							break;
						}
					}
					echo" />
					</p>
				  </td>
				 </tr>

				 <tr>
				  <td style=\"padding-right: 5px;\">
					<p><b>Alternative j:</b><br />
					<input type=\"text\" name=\"inp_alt_j\" value=\"$get_current_qa_alt_j\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>
				  </td>
				  <td>
					<p><b>Correct?</b><br />
					<input type=\"checkbox\" name=\"inp_correct_alternative_j\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"";
					for($x=0;$x<sizeof($alternatives);$x++){
						$temp = $alternatives[$x];
						if($temp == "j"){
							echo" checked=\"checked\""; 
							break;
						}
					}
					echo" />
					</p>
				  </td>
				 </tr>

				 <tr>
				  <td style=\"padding-right: 5px;\">
					<p><b>Alternative k:</b><br />
					<input type=\"text\" name=\"inp_alt_k\" value=\"$get_current_qa_alt_k\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>
				  </td>
				  <td>
					<p><b>Correct?</b><br />
					<input type=\"checkbox\" name=\"inp_correct_alternative_k\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"";
					for($x=0;$x<sizeof($alternatives);$x++){
						$temp = $alternatives[$x];
						if($temp == "k"){
							echo" checked=\"checked\""; 
							break;
						}
					}
					echo" />
					</p>
				  </td>
				 </tr>

				 <tr>
				  <td style=\"padding-right: 5px;\">
					<p><b>Alternative l:</b><br />
					<input type=\"text\" name=\"inp_alt_l\" value=\"$get_current_qa_alt_l\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>
				  </td>
				  <td>
					<p><b>Correct?</b><br />
					<input type=\"checkbox\" name=\"inp_correct_alternative_l\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"";
					for($x=0;$x<sizeof($alternatives);$x++){
						$temp = $alternatives[$x];
						if($temp == "l"){
							echo" checked=\"checked\""; 
							break;
						}
					}
					echo" />
					</p>
				  </td>
				 </tr>

				 <tr>
				  <td style=\"padding-right: 5px;\">
					<p><b>Alternative m:</b><br />
					<input type=\"text\" name=\"inp_alt_m\" value=\"$get_current_qa_alt_m\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>
				  </td>
				  <td>
					<p><b>Correct?</b><br />
					<input type=\"checkbox\" name=\"inp_correct_alternative_m\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"";
					for($x=0;$x<sizeof($alternatives);$x++){
						$temp = $alternatives[$x];
						if($temp == "m"){
							echo" checked=\"checked\""; 
							break;
						}
					}
					echo" />
					</p>
				  </td>
				 </tr>

				 <tr>
				  <td style=\"padding-right: 5px;\">
					<p><b>Alternative n:</b><br />
					<input type=\"text\" name=\"inp_alt_n\" value=\"$get_current_qa_alt_n\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>
				  </td>
				  <td>
					<p><b>Correct?</b><br />
					<input type=\"checkbox\" name=\"inp_correct_alternative_n\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"";
					for($x=0;$x<sizeof($alternatives);$x++){
						$temp = $alternatives[$x];
						if($temp == "n"){
							echo" checked=\"checked\""; 
							break;
						}
					}
					echo" />
					</p>
				  </td>
				 </tr>
				</table>

				<p><b>Points:</b><br />
				<input type=\"text\" name=\"inp_points\" value=\"$get_current_qa_points\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>

				<p><b>Hint:</b><br />
				<input type=\"text\" name=\"inp_hint\" value=\"$get_current_qa_hint\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>

				<p><b>Explanation:</b><br />
				<textarea name=\"inp_explanation\" rows=\"5\" cols=\"80\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
				$get_current_qa_explanation = str_replace("<br />", "\n", $get_current_qa_explanation);
				echo"$get_current_qa_explanation</textarea>
				</p>

				<p>
				<input type=\"submit\" value=\"Save changes\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>
			<!-- //New question form -->
		
			";
		} // question found
	} // action == edit question
	elseif($action == "delete_question"){
		if(isset($_GET['qa_id'])){
			$qa_id = $_GET['qa_id'];
			$qa_id = strip_tags(stripslashes($qa_id));
		}
		else{
			$qa_id = "";
		}

		
		$stmt = $mysqli->prepare("SELECT qa_id, qa_course_id, qa_course_title, qa_exam_id, qa_question_number, qa_question, qa_text, qa_type, qa_alt_a, qa_alt_b, qa_alt_c, qa_alt_d, qa_alt_e, qa_alt_f, qa_alt_g, qa_alt_h, qa_alt_i, qa_alt_j, qa_alt_k, qa_alt_l, qa_alt_m, qa_alt_n, qa_correct_alternatives, qa_points, qa_hint, qa_explanation FROM $t_courses_exams_qa WHERE qa_id=? AND qa_course_id=?"); 
		$stmt->bind_param("ss", $qa_id, $get_current_course_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_row();
		list($get_current_qa_id, $get_current_qa_course_id, $get_current_qa_course_title, $get_current_qa_exam_id, $get_current_qa_question_number, $get_current_qa_question, $get_current_qa_text, $get_current_qa_type, $get_current_qa_alt_a, $get_current_qa_alt_b, $get_current_qa_alt_c, $get_current_qa_alt_d, $get_current_qa_alt_e, $get_current_qa_alt_f, $get_current_qa_alt_g, $get_current_qa_alt_h, $get_current_qa_alt_i, $get_current_qa_alt_j, $get_current_qa_alt_k, $get_current_qa_alt_l, $get_current_qa_alt_m, $get_current_qa_alt_n, $get_current_qa_correct_alternatives, $get_current_qa_points, $get_current_qa_hint, $get_current_qa_explanation) = $row;

		if($get_current_qa_id == ""){
			echo"<p>Server error 404.</p>";
		}
		else{
			if($process == "1"){
				$mysqli->query("DELETE FROM $t_courses_exams_qa WHERE qa_id=$get_current_qa_id") or die($mysqli->error);

				$url = "index.php?open=$open&page=$page&course_id=$course_id&editor_language=$editor_language&ft=success&fm=question_deleted";
				header("Location: $url");
				exit;
			}
			echo"
			<h1>$get_current_course_title</h1>
				

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
				<a href=\"index.php?open=courses&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Courses</a>
				&gt;
				<a href=\"index.php?open=courses&amp;page=open_main_category&amp;main_category_id=$get_current_course_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_course_main_category_title</a>
				&gt;
				<a href=\"index.php?open=courses&amp;page=open_sub_category&amp;sub_category_id=$get_current_course_sub_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_course_sub_category_title</a>
				&gt;
				<a href=\"index.php?open=courses&amp;page=courses_open&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_course_title</a>
				&gt;
				<a href=\"index.php?open=courses&amp;page=courses_exam&amp;course_id=$course_id&amp;editor_language=$editor_language&amp;l=$l\">Exam</a>
				&gt;
				<a href=\"index.php?open=courses&amp;page=courses_exam&amp;course_id=$course_id&amp;action=$action&amp;qa_id=$get_current_qa_id&amp;editor_language=$editor_language&amp;l=$l\">Delete question</a>
				</p>
			<!-- //Where am I? -->

			<!-- Edit question form -->
				<h2>Delete exam question</h2>
				
				<p>Are you sure you want to delete the question?</p>
				
				<p>
				<a href=\"index.php?open=$open&amp;page=$page&amp;course_id=$course_id&amp;action=delete_question&amp;qa_id=$get_current_qa_id&amp;editor_language=$editor_language&amp;process=1\" class=\"btn_danger\">Confirm</a>
				
				</p>
			<!-- //New question form -->
		
			";
		} // question found
	} // action == edit question
} // found
?>