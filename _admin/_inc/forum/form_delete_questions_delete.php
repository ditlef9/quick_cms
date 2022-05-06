<?php
/**
*
* File: _admin/_inc/discuss/form_edit_questions_delete.php
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
if(isset($_GET['question_id'])){
	$question_id = $_GET['question_id'];
	$question_id = output_html($question_id);
}
else{
	$question_id = "";
}
$tabindex = 0;


// Get form
$form_id_mysql = quote_smart($link, $form_id);
$query = "SELECT form_id, form_title FROM $t_forum_forms WHERE form_id=$form_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_form_id, $get_current_form_title) = $row;

if($get_current_form_id == ""){
	echo"
	<h1>Error</h1>

	<p>
	Not found.
	</p>
	";

}
else{
	// Get question
	$question_id_mysql = quote_smart($link, $question_id);
	$query = "SELECT form_question_id, form_id, form_question FROM $t_forum_forms_questions WHERE form_question_id=$question_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_form_question_id, $get_current_form_id, $get_current_form_question) = $row;

	if($get_current_form_question_id == ""){
		echo"
		<h1>Error</h1>

		<p>
		Q found.
		</p>
		";

	}
	else{


		if($process == "1"){

			$result = mysqli_query($link, "DELETE FROM $t_forum_forms_questions 
			 WHERE form_question_id=$get_current_form_question_id");



			$url = "index.php?open=$open&page=form_edit_questions&form_id=$get_current_form_id&editor_language=$editor_language&ft=success&fm=deleted";
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
			<p>Are you sure you want to delete?</p>
			
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;form_id=$form_id&amp;question_id=$question_id&amp;editor_language=$editor_language&amp;process=1\">Yes</a>
			</p>
		<!-- //Form -->
		";
	}
}
?>