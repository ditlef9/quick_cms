<?php
/**
*
* File: _admin/_inc/discuss/forms_edit.php
* Version 1
* Date 10:34 03.03.2018
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Functions ----------------------------------------------------------------------- */

/*- Tables ---------------------------------------------------------------------------- */
$t_forum_titles			= $mysqlPrefixSav . "forum_titles";
$t_forum_subscriptions 		= $mysqlPrefixSav . "forum_subscriptions";
$t_forum_topics 		= $mysqlPrefixSav . "forum_topics";
$t_forum_topics_subscribers 	= $mysqlPrefixSav . "forum_topics_subscribers";
$t_forum_topics_read_by_user	= $mysqlPrefixSav . "forum_topics_read_by_user";
$t_forum_topics_read_by_ip	= $mysqlPrefixSav . "forum_topics_read_by_ip";
$t_forum_topics_tags 		= $mysqlPrefixSav . "forum_topics_tags";
$t_forum_replies		= $mysqlPrefixSav . "forum_replies";
$t_forum_replies_comments	= $mysqlPrefixSav . "forum_replies_comments";
$t_forum_forms			= $mysqlPrefixSav . "forum_forms";
$t_forum_forms_questions	= $mysqlPrefixSav . "forum_forms_questions";
$t_forum_top_users_yearly	= $mysqlPrefixSav . "forum_top_users_yearly";
$t_forum_top_users_monthly	= $mysqlPrefixSav . "forum_top_users_monthly";
$t_forum_top_users_all_time	= $mysqlPrefixSav . "forum_top_users_all_time";
$t_forum_tags_index		= $mysqlPrefixSav . "forum_tags_index";
$t_forum_tags_index_translation	= $mysqlPrefixSav . "forum_tags_index_translation";
$t_forum_tags_watch		= $mysqlPrefixSav . "forum_tags_watch";
$t_forum_tags_ignore		= $mysqlPrefixSav . "forum_tags_ignore";


/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['form_id'])){
	$form_id = $_GET['form_id'];
	$form_id = output_html($form_id);
}
else{
	$form_id = "";
}
$tabindex = 0;


// Get form
$form_id_mysql = quote_smart($link, $form_id);
$query = "SELECT form_id, form_title, form_language, form_introduction, form_tags, form_created, form_updated FROM $t_forum_forms WHERE form_id=$form_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_form_id, $get_current_form_title, $get_current_form_language, $get_current_form_introduction, $get_current_form_tags, $get_current_form_created, $get_current_form_updated) = $row;

if($get_current_form_id == ""){
	echo"
	<h1>Error</h1>

	<p>
	Not found.
	</p>
	";

}
else{
	if($process == "1"){
		$inp_question = $_POST['inp_question'];
		$inp_question = output_html($inp_question);
		$inp_question_mysql = quote_smart($link, $inp_question);
	
		$inp_type = $_POST['inp_type'];
		$inp_type = output_html($inp_type);
		$inp_type_mysql = quote_smart($link, $inp_type);
	
		$inp_options = $_POST['inp_options'];
		$inp_options = output_html($inp_options);
		$inp_options = str_replace("<br />", "\n", $inp_options);
		$inp_options_mysql = quote_smart($link, $inp_options);
	
		$inp_help_text = $_POST['inp_help_text'];
		$inp_help_text= output_html($inp_help_text);
		$inp_help_text_mysql = quote_smart($link, $inp_help_text);
	
	
		mysqli_query($link, "INSERT INTO $t_forum_forms_questions
		(form_question_id, form_id, form_question, form_question_type, form_question_options, form_question_help_text) 
		VALUES 
		(NULL, $get_current_form_id, $inp_question_mysql, $inp_type_mysql, $inp_options_mysql, $inp_help_text_mysql)")
		or die(mysqli_error($link));


		$url = "index.php?open=$open&page=form_edit_questions&form_id=$get_current_form_id&editor_language=$editor_language&ft=success&fm=question_created";
		header("Location: $url");
		exit;
	}
	echo"
	<h1>$get_current_form_title</h1>

	<!-- Menu -->
		<div class=\"tabs\">
			<ul>
			<li><a href=\"index.php?open=$open&amp;page=forms&amp;editor_language=$editor_language\">Forms</a></li>
			<li><a href=\"index.php?open=$open&amp;page=$page&amp;form_id=$get_current_form_id&amp;editor_language=$editor_language\">Edit form</a></li>
			<li><a href=\"index.php?open=$open&amp;page=form_edit_questions&amp;form_id=$get_current_form_id&amp;editor_language=$editor_language\" class=\"active\">Questions</a></li>
			</ul>
		</div>
		<div class=\"clear\"></div>
	<!-- //Menu -->

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
			\$('[name=\"inp_question\"]').focus();
		});
		</script>
			
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;form_id=$form_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

		<p><b>Question:</b><br />
		<input type=\"text\" name=\"inp_question\" value=\"\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>Type:</b><br />
		<select name=\"inp_type\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
			<option value=\"text\">Text</option>
			<option value=\"textarea\">Textarea</option>
			<option value=\"select\">Select</option>
		</select>

		<p><b>Select options, seperated by line shift:</b><br />
		<textarea name=\"inp_options\" rows=\"10\" cols=\"30\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea>
		</p>

		<p><b>Help text:</b><br />
		<input type=\"text\" name=\"inp_help_text\" value=\"\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><input type=\"submit\" value=\"Save\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
		</form>
	<!-- //Form -->
	";
}
?>