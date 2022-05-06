<?php
/**
*
* File: _admin/_inc/contact_forms/new_contact_form.php
* Version 1.0.0
* Date 11:43 12.11.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
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
$t_contact_forms_auto_replies		= $mysqlPrefixSav . "contact_forms_auto_replies";


/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['form_id'])){
	$form_id = $_GET['form_id'];
	$form_id = output_html($form_id);
}
else{
	$form_id = "";
}
$form_id_mysql = quote_smart($link, $form_id);



// Get contact form
$query = "SELECT form_id, form_title, form_language, form_mail_to, form_text_before_form, form_text_left_of_form, form_text_right_of_form, form_text_after_form, form_created_datetime, form_created_by_user_id, form_updated_datetime, form_updated_by_user_id, form_api_avaible, form_api_password, form_ipblock, form_used_times FROM $t_contact_forms_index WHERE form_id=$form_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_form_id, $get_current_form_title, $get_current_form_language, $get_current_form_mail_to, $get_current_form_text_before_form, $get_current_form_text_left_of_form, $get_current_form_text_right_of_form, $get_current_form_text_after_form, $get_current_form_created_datetime, $get_current_form_created_by_user_id, $get_current_form_updated_datetime, $get_current_form_updated_by_user_id, $get_current_form_api_avaible, $get_current_form_api_password, $get_current_form_ipblock, $get_current_form_used_times) = $row;

if($get_current_form_id == ""){
	echo"
	<h1>Form not found</h1>
	";

}
else{

	echo"
	<h1>$get_current_form_title</h1>

	<!-- Where am I? -->
		<p>
		<b>You are here:</b><br />
		<a href=\"index.php?open=contact_forms&amp;editor_language=$editor_language\">Contact forms</a>
		&gt;
		<a href=\"index.php?open=contact_forms&amp;page=open_contact_form&amp;form_id=$form_id&amp;editor_language=$editor_language\">$get_current_form_title</a>
		</p>
	<!-- //Where am I? -->


	<!-- Form navigation -->
		<p style=\"margin-bottom:0;padding-bottom:0;\"><b>Navigation:</b></p>
		<div class=\"vertical\">
			<ul>
				<li><a href=\"index.php?open=contact_forms&amp;page=edit_contact_form_general&amp;form_id=$form_id&amp;editor_language=$editor_language\">General</a></li>
				<li><a href=\"index.php?open=contact_forms&amp;page=edit_contact_form_images&amp;form_id=$form_id&amp;editor_language=$editor_language\">Images</a></li>
				<li><a href=\"index.php?open=contact_forms&amp;page=edit_contact_form_text_before_form&amp;form_id=$form_id&amp;editor_language=$editor_language\">Text before form</a></li>
				<li><a href=\"index.php?open=contact_forms&amp;page=edit_contact_form_text_left_of_form&amp;form_id=$form_id&amp;editor_language=$editor_language\">Text left of form</a></li>
				<li><a href=\"index.php?open=contact_forms&amp;page=edit_contact_form_text_right_of_form&amp;form_id=$form_id&amp;editor_language=$editor_language\">Text right of form</a></li>
				<li><a href=\"index.php?open=contact_forms&amp;page=edit_contact_form_text_after_form&amp;form_id=$form_id&amp;editor_language=$editor_language\">Text after form</a></li>
				<li><a href=\"index.php?open=contact_forms&amp;page=edit_contact_form_questions&amp;form_id=$form_id&amp;editor_language=$editor_language\">Questions</a></li>
				<li><a href=\"index.php?open=contact_forms&amp;page=edit_contact_form_auto_replies&amp;form_id=$form_id&amp;editor_language=$editor_language\">Auto replies</a></li>
				<li><a href=\"index.php?open=contact_forms&amp;page=edit_contact_form_api_info&amp;form_id=$form_id&amp;editor_language=$editor_language\">API info</a></li>
			</ul>
		</div>
	<!-- //Form navigation -->

	<!-- Info -->
		<p><b>URL:</b><br />
		<a href=\"../contact_forms/view_form.php?form_id=$get_current_form_id&amp;l=$get_current_form_language\">contact_forms/view_form.php?form_id=$get_current_form_id&amp;l=$get_current_form_language</a>
		</p>
		<p><b>API URL:</b><br />
		<a href=\"../contact_forms/api/post_new_post.php\">contact_forms/api/post_new_post.php</a>
		</p>
	<!-- //Info -->


	<p>
	<a href=\"index.php?open=contact_forms&amp;editor_language=$editor_language\" class=\"btn_default\">Contact forms</a>
	</p>

	";
} // form found
?>