<?php
/**
*
* File: _admin/_inc/edit_contact_form_questions.php
* Version 1.0.0
* Date 20:20 23.01.2019
* Copyright (c) 2008-2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables --------------------------------------------------------------------------- */
$t_contact_forms_index			= $mysqlPrefixSav . "contact_forms_index";
$t_contact_forms_questions		= $mysqlPrefixSav . "contact_forms_questions";
$t_contact_forms_questions_alternatives	= $mysqlPrefixSav . "contact_forms_questions_alternatives";


/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['form_id'])){
	$form_id = $_GET['form_id'];
	$form_id = output_html($form_id);
}
else{
	$form_id = "";
}
$form_id_mysql = quote_smart($link, $form_id);

if(isset($_GET['question_id'])){
	$question_id = $_GET['question_id'];
	$question_id = output_html($question_id);
}
else{
	$question_id = "";
}
$question_id_mysql = quote_smart($link, $question_id);


if(isset($_GET['alternative_id'])){
	$alternative_id = $_GET['alternative_id'];
	$alternative_id = output_html($alternative_id);
}
else{
	$alternative_id = "";
}
$alternative_id_mysql = quote_smart($link, $alternative_id);



// Get contact form
$query = "SELECT form_id, form_title, form_language, form_mail_to, form_text_before_form, form_text_left_of_form, form_text_right_of_form, form_text_after_form, form_first_field_name, form_created_datetime, form_created_by_user_id, form_updated_datetime, form_updated_by_user_id, form_api_avaible, form_api_password, form_ipblock, form_used_times FROM $t_contact_forms_index WHERE form_id=$form_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_form_id, $get_current_form_title, $get_current_form_language, $get_current_form_mail_to, $get_current_form_text_before_form, $get_current_form_text_left_of_form, $get_current_form_text_right_of_form, $get_current_form_text_after_form, $get_current_form_first_field_name, $get_current_form_created_datetime, $get_current_form_created_by_user_id, $get_current_form_updated_datetime, $get_current_form_updated_by_user_id, $get_current_form_api_avaible, $get_current_form_api_password, $get_current_form_ipblock, $get_current_form_used_times) = $row;

if($get_current_form_id == ""){
	echo"
	<h1>Form not found</h1>
	";

}
else{
	if($action == ""){
		echo"
		<h1>$get_current_form_title</h1>

		<!-- Where am I? -->
			<p>
			<b>You are here:</b><br />
			<a href=\"index.php?open=contact_forms&amp;editor_language=$editor_language\">Contact forms</a>
			&gt;
			<a href=\"index.php?open=contact_forms&amp;page=open_contact_form&amp;form_id=$form_id&amp;editor_language=$editor_language\">$get_current_form_title</a>
			&gt;
			<a href=\"index.php?open=contact_forms&amp;page=$page&amp;form_id=$form_id&amp;editor_language=$editor_language\">Questions</a>
			</p>
		<!-- //Where am I? -->

		<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($ft);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->

		<p>
		<a href=\"index.php?open=contact_forms&amp;page=edit_contact_form_questions&amp;form_id=$form_id&amp;action=add_question&amp;editor_language=$editor_language\" class=\"btn\">Add question</a>
		</p>

		<!-- Questions -->
		";
		$x = 0;
		$query = "SELECT question_id, question_form_id, question_title, question_field_name, question_weight, question_type, question_size, question_rows, question_cols, question_required, question_answer FROM $t_contact_forms_questions WHERE question_form_id=$get_current_form_id ORDER BY question_weight ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_question_id, $get_question_form_id, $get_question_title, $get_question_field_name, $get_question_weight, $get_question_type, $get_question_size, $get_question_rows, $get_question_cols, $get_question_required, $get_question_answer) = $row;

			// Count
			if($get_question_weight != "$x"){
				$result_update = mysqli_query($link, "UPDATE $t_contact_forms_questions SET question_weight=$x WHERE question_id=$get_question_id");
			}
			// First
			if($x == "0"){
				$field_name = "inp_q_" . $get_question_field_name;
				if($get_current_form_first_field_name != "$field_name"){
					$inp_form_first_field_name_mysql = quote_smart($link, $field_name);
					$result_update = mysqli_query($link, "UPDATE $t_contact_forms_index SET form_first_field_name=$inp_form_first_field_name_mysql WHERE form_id=$get_question_form_id");
				}

			}

			echo"
			<p><b>$get_question_title";
			if($get_question_required == "1"){ echo" *"; } 
			echo"</b><br />
			";
			if($get_question_type == "text" OR $get_question_type == "email"){
				echo"<input type=\"text\" name=\"inp_$get_question_id\" size=\"$get_question_size\" /><br />\n";
			}
			elseif($get_question_type == "textarea"){
				echo"<textarea name=\"inp_$get_question_id\" rows=\"$get_question_rows\" cols=\"$get_question_cols\"></textarea><br />\n";
			}
			elseif($get_question_type == "radio"){
				$y = 0;
				$query_alt = "SELECT alternative_id, form_id, question_id, alternative_title, alternative_preselected FROM $t_contact_forms_questions_alternatives WHERE form_id=$get_question_form_id AND question_id=$get_question_id";
				$result_alt = mysqli_query($link, $query_alt);
				while($row_alt = mysqli_fetch_row($result_alt)) {
					list($get_alternative_id, $get_form_id, $get_question_id, $get_alternative_title, $get_alternative_preselected) = $row_alt;

					echo"
					<input type=\"radio\" value=\"$get_alternative_title\""; if($y == "0"){ echo" checked=\"checked\""; } echo" /> $get_alternative_title
					";

					$y++;
				}

				echo"
				<br />
				<a href=\"index.php?open=contact_forms&amp;page=$page&amp;form_id=$form_id&amp;action=alternatives&amp;question_id=$get_question_id&amp;editor_language=$editor_language\">Alternatives</a>
				&middot;
				";	
			}
			elseif($get_question_type == "select"){
				echo"<select name=\"inp_$get_question_id\">";

				$y = 0;
				$query_alt = "SELECT alternative_id, form_id, question_id, alternative_title, alternative_preselected FROM $t_contact_forms_questions_alternatives WHERE form_id=$get_question_form_id AND question_id=$get_question_id";
				$result_alt = mysqli_query($link, $query_alt);
				while($row_alt = mysqli_fetch_row($result_alt)) {
					list($get_alternative_id, $get_form_id, $get_question_id, $get_alternative_title, $get_alternative_preselected) = $row_alt;

					echo"
					<option value=\"$get_alternative_title\""; if($y == "0"){ echo" selected=\"selected\""; } echo">$get_alternative_title</option>
					";

					$y++;
				}

				echo"
				</select>
				<br />
				<a href=\"index.php?open=contact_forms&amp;page=$page&amp;form_id=$form_id&amp;action=alternatives&amp;question_id=$get_question_id&amp;editor_language=$editor_language\">Alternatives</a>
				&middot;
				";	
	

			}
			echo"
			
			<a href=\"index.php?open=contact_forms&amp;page=$page&amp;form_id=$form_id&amp;action=edit_question&amp;question_id=$get_question_id&amp;editor_language=$editor_language\">Edit</a>
			&middot;
			<a href=\"index.php?open=contact_forms&amp;page=$page&amp;form_id=$form_id&amp;action=delete_question&amp;question_id=$get_question_id&amp;editor_language=$editor_language\">Delete</a>
			</p>
			";


			$x++;
		}
		echo"
		<!-- //Questions -->

		";
	} // action == ""
	elseif($action == "add_question"){
		if($process == "1"){
			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_field_name = clean($inp_title);
			$inp_field_name = str_replace("-", "_", $inp_field_name);
			$inp_field_name_mysql = quote_smart($link, $inp_field_name);

			$inp_type = $_POST['inp_type'];
			$inp_type = output_html($inp_type);
			$inp_type_mysql = quote_smart($link, $inp_type);

			$inp_size = $_POST['inp_size'];
			$inp_size = output_html($inp_size);
			$inp_size_mysql = quote_smart($link, $inp_size);

			$inp_rows = $_POST['inp_rows'];
			$inp_rows = output_html($inp_rows);
			$inp_rows_mysql = quote_smart($link, $inp_rows);

			$inp_cols = $_POST['inp_cols'];
			$inp_cols = output_html($inp_cols);
			$inp_cols_mysql = quote_smart($link, $inp_cols);

			$inp_required = $_POST['inp_required'];
			$inp_required = output_html($inp_required);
			$inp_required_mysql = quote_smart($link, $inp_required);

			$inp_answer = $_POST['inp_answer'];
			$inp_answer = strtolower($inp_answer);
			$inp_answer = output_html($inp_answer);
			$inp_answer_mysql = quote_smart($link, $inp_answer);


			mysqli_query($link, "INSERT INTO $t_contact_forms_questions
			(question_id, question_form_id, question_title, question_field_name, question_weight, question_type, question_size, question_rows, question_cols, question_required, question_answer) 
			VALUES 
			(NULL, $get_current_form_id, $inp_title_mysql, $inp_field_name_mysql, '999', $inp_type_mysql, $inp_size_mysql, $inp_rows_mysql, $inp_cols_mysql, $inp_required_mysql, $inp_answer_mysql)")
			or die(mysqli_error($link));
			

			// Header
			$url = "index.php?open=$open&page=$page&form_id=$form_id&editor_language=$editor_language&ft=success&fm=question_saved";
			header("Location: $url");
			exit;
		}
		echo"
		<h1>$get_current_form_title</h1>

		<!-- Where am I? -->
			<p>
			<b>You are here:</b><br />
			<a href=\"index.php?open=contact_forms&amp;editor_language=$editor_language\">Contact forms</a>
			&gt;
			<a href=\"index.php?open=contact_forms&amp;page=open_contact_form&amp;form_id=$form_id&amp;editor_language=$editor_language\">$get_current_form_title</a>
			&gt;
			<a href=\"index.php?open=contact_forms&amp;page=$page&amp;form_id=$form_id&amp;editor_language=$editor_language\">Questions</a>
			&gt;
			<a href=\"index.php?open=contact_forms&amp;page=$page&amp;form_id=$form_id&amp;action=$action&amp;editor_language=$editor_language\">Add question</a>
			</p>
		<!-- //Where am I? -->

		

		<!-- Form -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
			</script>
			
			<form method=\"post\" action=\"index.php?open=contact_forms&amp;page=$page&amp;form_id=$form_id&amp;action=$action&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>Title:</b><br />
			<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>
	
			<p><b>Type:</b><br />
			<select name=\"inp_type\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
				<option value=\"text\">Text</option>
				<option value=\"email\">Email</option>
				<option value=\"textarea\">Textarea</option>
				<option value=\"radio\">Radio</option>
				<option value=\"select\">Select</option>
			</select>

			<p><b>Size:</b><br />
			<input type=\"text\" name=\"inp_size\" value=\"25\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p><b>Rows:</b><br />
			<input type=\"text\" name=\"inp_rows\" value=\"8\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p><b>Cols:</b><br />
			<input type=\"text\" name=\"inp_cols\" value=\"40\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p><b>Required:</b><br />
			<input type=\"radio\" name=\"inp_required\" value=\"1\" checked=\"checked\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> Yes
			<input type=\"radio\" name=\"inp_required\" value=\"0\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> No
			</p>

			<p><b>Answer:</b><br />
			<span class=\"small\">An answer that the user has to type. In order to prevent spam. Example &quot;What is the capital of Norway?&quot; answer will be &quot;Oslo&quot;</span><br />
			<input type=\"text\" name=\"inp_answer\" value=\"\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>


			<p><input type=\"submit\" value=\"Create question\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
			</form>
		<!-- //Form -->
		";
	} // action == add_question
	elseif($action == "edit_question"){


		// Get question
		$query = "SELECT question_id, question_form_id, question_title, question_weight, question_type, question_size, question_rows, question_cols, question_required, question_answer FROM $t_contact_forms_questions WHERE question_id=$question_id_mysql AND question_form_id=$form_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_question_id, $get_current_question_form_id, $get_current_question_title, $get_current_question_weight, $get_current_question_type, $get_current_question_size, $get_current_question_rows, $get_current_question_cols, $get_current_question_required, $get_current_question_answer) = $row;

		if($get_current_question_id == ""){
			echo"
			<h1>Q not found</h1>
			";

		}
		else{
			if($process == "1"){
				$inp_title = $_POST['inp_title'];
				$inp_title = output_html($inp_title);
				$inp_title_mysql = quote_smart($link, $inp_title);

				$inp_field_name = clean($inp_title);
				$inp_field_name = str_replace("-", "_", $inp_field_name);
				$inp_field_name_mysql = quote_smart($link, $inp_field_name);

				$inp_type = $_POST['inp_type'];
				$inp_type = output_html($inp_type);
				$inp_type_mysql = quote_smart($link, $inp_type);

				$inp_size = $_POST['inp_size'];
				$inp_size = output_html($inp_size);
				$inp_size_mysql = quote_smart($link, $inp_size);

				$inp_rows = $_POST['inp_rows'];
				$inp_rows = output_html($inp_rows);
				$inp_rows_mysql = quote_smart($link, $inp_rows);

				$inp_cols = $_POST['inp_cols'];
				$inp_cols = output_html($inp_cols);
				$inp_cols_mysql = quote_smart($link, $inp_cols);

				$inp_required = $_POST['inp_required'];
				$inp_required = output_html($inp_required);
				$inp_required_mysql = quote_smart($link, $inp_required);

				$inp_answer = $_POST['inp_answer'];
				$inp_answer = strtolower($inp_answer);
				$inp_answer = output_html($inp_answer);
				$inp_answer_mysql = quote_smart($link, $inp_answer);

				$result = mysqli_query($link, "UPDATE $t_contact_forms_questions SET 
				question_title=$inp_title_mysql, 
				question_field_name=$inp_field_name_mysql,
				question_type=$inp_type_mysql, 
				question_size=$inp_size_mysql, 
				question_rows=$inp_rows_mysql, 
				question_cols=$inp_cols_mysql, 
				question_required=$inp_required_mysql, 
				question_answer=$inp_answer_mysql WHERE question_id=$get_current_question_id");


				// Header
				$url = "index.php?open=$open&page=$page&form_id=$form_id&action=$action&question_id=$question_id&editor_language=$editor_language&ft=success&fm=changes_saved";
				header("Location: $url");
				exit;
			}
			
			echo"
			<h1>$get_current_form_title</h1>

			<!-- Where am I? -->
				<p>
				<b>You are here:</b><br />
				<a href=\"index.php?open=contact_forms&amp;editor_language=$editor_language\">Contact forms</a>
				&gt;
				<a href=\"index.php?open=contact_forms&amp;page=open_contact_form&amp;form_id=$form_id&amp;editor_language=$editor_language\">$get_current_form_title</a>
				&gt;
				<a href=\"index.php?open=contact_forms&amp;page=$page&amp;form_id=$form_id&amp;editor_language=$editor_language\">Questions</a>
				&gt;
				<a href=\"index.php?open=contact_forms&amp;page=$page&amp;form_id=$form_id&amp;action=$action&amp;question_id=$question_id&amp;editor_language=$editor_language\">$get_current_question_title</a>
				</p>
			<!-- //Where am I? -->

			<!-- Feedback -->
				";
				if($ft != ""){
					if($fm == "changes_saved"){
						$fm = "$l_changes_saved";
					}
					else{
						$fm = ucfirst($ft);
					}
					echo"<div class=\"$ft\"><span>$fm</span></div>";
				}
				echo"	
			<!-- //Feedback -->

			<!-- Form -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_title\"]').focus();
				});
				</script>
			
				<form method=\"post\" action=\"index.php?open=contact_forms&amp;page=$page&amp;form_id=$form_id&amp;action=$action&amp;question_id=$question_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

				<p><b>Title:</b><br />
				<input type=\"text\" name=\"inp_title\" value=\"$get_current_question_title\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>
	
				<p><b>Type:</b><br />
				<select name=\"inp_type\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
					<option value=\"text\""; if($get_current_question_type == "text"){ echo" selected=\"selected\""; } echo">Text</option>
					<option value=\"email\""; if($get_current_question_type == "email"){ echo" selected=\"selected\""; } echo">Email</option>
					<option value=\"textarea\""; if($get_current_question_type == "textarea"){ echo" selected=\"selected\""; } echo">Textarea</option>
					<option value=\"radio\""; if($get_current_question_type == "radio"){ echo" selected=\"selected\""; } echo">Radio</option>
					<option value=\"select\""; if($get_current_question_type == "select"){ echo" selected=\"selected\""; } echo">Select</option>
				</select>

				<p><b>Size:</b><br />
				<input type=\"text\" name=\"inp_size\" value=\"$get_current_question_size\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>

				<p><b>Rows:</b><br />
				<input type=\"text\" name=\"inp_rows\" value=\"$get_current_question_rows\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>

				<p><b>Cols:</b><br />
				<input type=\"text\" name=\"inp_cols\" value=\"$get_current_question_cols\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>

				<p><b>Required:</b><br />
				<input type=\"radio\" name=\"inp_required\" value=\"1\" "; if($get_current_question_required == "1"){ echo" checked=\"checked\""; } echo" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> Yes
				<input type=\"radio\" name=\"inp_required\" value=\"0\" "; if($get_current_question_required == "0"){ echo" checked=\"checked\""; } echo"tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> No
				</p>

				<p><b>Answer:</b><br />
				<span class=\"small\">An answer that the user has to type. In order to prevent spam. Example &quot;What is the capital of Norway?&quot; answer will be &quot;Oslo&quot;</span><br />
				<input type=\"text\" name=\"inp_answer\" value=\"$get_current_question_answer\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>


				<p><input type=\"submit\" value=\"Save changes\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
				</form>
			<!-- //Form -->
			";
			
		} // question found

	} // action == edit_question
	elseif($action == "delete_question"){


		// Get question
		$query = "SELECT question_id, question_form_id, question_title, question_weight, question_type, question_size, question_rows, question_cols, question_required, question_answer FROM $t_contact_forms_questions WHERE question_id=$question_id_mysql AND question_form_id=$form_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_question_id, $get_current_question_form_id, $get_current_question_title, $get_current_question_weight, $get_current_question_type, $get_current_question_size, $get_current_question_rows, $get_current_question_cols, $get_current_question_required, $get_current_question_answer) = $row;

		if($get_current_question_id == ""){
			echo"
			<h1>Q not found</h1>
			";

		}
		else{
			if($process == "1"){

				$result = mysqli_query($link, "DELETE FROM $t_contact_forms_questions WHERE question_id=$get_current_question_id");


				// Header
				$url = "index.php?open=$open&page=$page&form_id=$form_id&editor_language=$inp_language&ft=success&fm=question_deleted";
				header("Location: $url");
				exit;
			}
			
			echo"
			<h1>$get_current_form_title</h1>

			<!-- Where am I? -->
				<p>
				<b>You are here:</b><br />
				<a href=\"index.php?open=contact_forms&amp;editor_language=$editor_language\">Contact forms</a>
				&gt;
				<a href=\"index.php?open=contact_forms&amp;page=open_contact_form&amp;form_id=$form_id&amp;editor_language=$editor_language\">$get_current_form_title</a>
				&gt;
				<a href=\"index.php?open=contact_forms&amp;page=$page&amp;form_id=$form_id&amp;editor_language=$editor_language\">Questions</a>
				&gt;
				<a href=\"index.php?open=contact_forms&amp;page=$page&amp;form_id=$form_id&amp;action=$action&amp;question_id=$question_id&amp;editor_language=$editor_language\">$get_current_question_title</a>
				</p>
			<!-- //Where am I? -->

			<!-- Feedback -->
				";
				if($ft != ""){
					if($fm == "changes_saved"){
						$fm = "$l_changes_saved";
					}
					else{
						$fm = ucfirst($ft);
					}
					echo"<div class=\"$ft\"><span>$fm</span></div>";
				}
				echo"	
			<!-- //Feedback -->

			<!-- Form -->
				<p>Are you sure you want to delete the question &quot;$get_current_question_title&quot;?</p>

				<p>
				<a href=\"index.php?open=contact_forms&amp;page=$page&amp;form_id=$form_id&amp;action=$action&amp;question_id=$question_id&amp;editor_language=$editor_language&amp;process=1\" class=\"btn_warning\">Confirm</a>
				</p>
			<!-- //Form -->
			";
			
		} // question found
	} // action == delete_question
	elseif($action == "alternatives"){


		// Get question
		$query = "SELECT question_id, question_form_id, question_title, question_weight, question_type, question_size, question_rows, question_cols, question_required, question_answer FROM $t_contact_forms_questions WHERE question_id=$question_id_mysql AND question_form_id=$form_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_question_id, $get_current_question_form_id, $get_current_question_title, $get_current_question_weight, $get_current_question_type, $get_current_question_size, $get_current_question_rows, $get_current_question_cols, $get_current_question_required, $get_current_question_answer) = $row;

		if($get_current_question_id == ""){
			echo"
			<h1>Q not found</h1>
			";

		}
		else{
			if($process == "1"){

				
				$inp_title = $_POST['inp_title'];
				$inp_title = output_html($inp_title);
				$inp_title_mysql = quote_smart($link, $inp_title);

				mysqli_query($link, "INSERT INTO $t_contact_forms_questions_alternatives
				(alternative_id, form_id, question_id, alternative_title, alternative_preselected) 
				VALUES 
				(NULL, $get_current_question_form_id, $get_current_question_id, $inp_title_mysql, '0')")
				or die(mysqli_error($link));

				// Header
				$url = "index.php?open=$open&page=$page&form_id=$form_id&action=$action&question_id=$question_id&editor_language=$inp_language&ft=success&fm=alternative_added";
				header("Location: $url");
				exit;
			}
			
			echo"
			<h1>$get_current_form_title</h1>

			<!-- Where am I? -->
				<p>
				<b>You are here:</b><br />
				<a href=\"index.php?open=contact_forms&amp;editor_language=$editor_language\">Contact forms</a>
				&gt;
				<a href=\"index.php?open=contact_forms&amp;page=open_contact_form&amp;form_id=$form_id&amp;editor_language=$editor_language\">$get_current_form_title</a>
				&gt;
				<a href=\"index.php?open=contact_forms&amp;page=$page&amp;form_id=$form_id&amp;editor_language=$editor_language\">Questions</a>
				&gt;
				<a href=\"index.php?open=contact_forms&amp;page=$page&amp;form_id=$form_id&amp;action=$action&amp;question_id=$question_id&amp;editor_language=$editor_language\">$get_current_question_title</a>
				</p>
			<!-- //Where am I? -->

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

			<!-- Add alternative -->

				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_title\"]').focus();
				});
				</script>
			
				<form method=\"post\" action=\"index.php?open=contact_forms&amp;page=$page&amp;form_id=$form_id&amp;action=$action&amp;question_id=$question_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

				<p><b>New alternative:</b><br />
				<input type=\"text\" name=\"inp_title\" value=\"\" size=\"20\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				<input type=\"submit\" value=\"Add\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />	
				</p>
				</form>
			<!-- //Add alternative -->

			<!-- List of alternatives -->
				<p><b>Alternatives:</b><br />
				";
				$query = "SELECT alternative_id, form_id, question_id, alternative_title, alternative_preselected FROM $t_contact_forms_questions_alternatives WHERE form_id=$get_current_question_form_id AND question_id=$get_current_question_id";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_alternative_id, $get_form_id, $get_question_id, $get_alternative_title, $get_alternative_preselected) = $row;

					echo"
					$get_alternative_title
					<a href=\"index.php?open=contact_forms&amp;page=$page&amp;form_id=$form_id&amp;action=delete_alternative&amp;question_id=$question_id&amp;alternative_id=$get_alternative_id&amp;editor_language=$editor_language&amp;process=1\"><img src=\"_design/gfx/icons/16x16/delete.png\" alt=\"delete.png\" /></a><br />
					";
				}
				echo"
				</p>
			<!-- //List of alternatives -->
			";
			
		} // question found
	} // action == alternatives
	elseif($action == "delete_alternative"){


		// Get question
		$query = "SELECT question_id, question_form_id, question_title, question_weight, question_type, question_size, question_rows, question_cols, question_required, question_answer FROM $t_contact_forms_questions WHERE question_id=$question_id_mysql AND question_form_id=$form_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_question_id, $get_current_question_form_id, $get_current_question_title, $get_current_question_weight, $get_current_question_type, $get_current_question_size, $get_current_question_rows, $get_current_question_cols, $get_current_question_required, $get_current_question_answer) = $row;

		if($get_current_question_id == ""){
			echo"
			<h1>Q not found</h1>
			";

		}
		else{
			// Get alternative
			$query = "SELECT alternative_id, form_id, question_id, alternative_title, alternative_preselected FROM $t_contact_forms_questions_alternatives WHERE alternative_id=$alternative_id_mysql AND form_id=$get_current_question_form_id AND question_id=$get_current_question_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_alternative_id, $get_current_form_id, $get_current_question_id, $get_current_alternative_title, $get_current_alternative_preselected) = $row;

			if($get_current_alternative_id == ""){
				echo"
				<h1>A not found</h1>
				";

			}
			else{
				if($process == "1"){

					$result = mysqli_query($link, "DELETE FROM $t_contact_forms_questions_alternatives WHERE alternative_id=$alternative_id_mysql AND form_id=$get_current_question_form_id AND question_id=$get_current_question_id");

				
					// Header
					$url = "index.php?open=$open&page=$page&form_id=$form_id&action=$action&question_id=$question_id&action=alternatives&editor_language=$inp_language&ft=success&fm=alternative_deleted";
					header("Location: $url");
					exit;
				}
			
			} // alternative found
		} // question found
	} // action == alternatives
} // form found
?>