<?php
/**
*
* File: _admin/_inc/contact_forms/edit_contact_form_auto_replies.php
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
	// Get auto reply
	$query = "SELECT auto_reply_id, auto_reply_form_id, auto_reply_from_email, auto_reply_from_name, auto_reply_subject, auto_reply_text, auto_reply_delay, auto_reply_active FROM $t_contact_forms_auto_replies WHERE auto_reply_form_id=$form_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_auto_reply_id, $get_auto_reply_form_id, $get_auto_reply_from_email, $get_auto_reply_from_name, $get_auto_reply_subject, $get_auto_reply_text, $get_auto_reply_delay, $get_auto_reply_active) = $row;



	if($process == "1"){
		$inp_from_email = $_POST['inp_from_email'];
		$inp_from_email = output_html($inp_from_email);
		$inp_from_email_mysql = quote_smart($link, $inp_from_email);

		$inp_from_name = $_POST['inp_from_name'];
		$inp_from_name = output_html($inp_from_name);
		$inp_from_name_mysql = quote_smart($link, $inp_from_name);

		$inp_subject = $_POST['inp_subject'];
		$inp_subject = output_html($inp_subject);
		$inp_subject_mysql = quote_smart($link, $inp_subject);

		$inp_active = $_POST['inp_active'];
		$inp_active = output_html($inp_active);
		$inp_active_mysql = quote_smart($link, $inp_active);

		$inp_text = $_POST['inp_text'];
		$inp_text = str_replace("../", "$configSiteURLSav/", $inp_text);


		// exists?
		if($get_auto_reply_id == ""){
			mysqli_query($link, "INSERT INTO $t_contact_forms_auto_replies
			(auto_reply_id, auto_reply_form_id, auto_reply_from_email, auto_reply_from_name, auto_reply_subject, auto_reply_text, auto_reply_delay, auto_reply_active) 
			VALUES 
			(NULL, $get_current_form_id, $inp_from_email_mysql, $inp_from_name_mysql, $inp_subject_mysql, '', '0', $inp_active_mysql)")
			or die(mysqli_error($link));
		}
		else{
			$result = mysqli_query($link, "UPDATE $t_contact_forms_auto_replies SET 
					auto_reply_from_email=$inp_from_email_mysql, auto_reply_from_name=$inp_from_name_mysql, auto_reply_subject=$inp_subject_mysql, auto_reply_active=$inp_active_mysql 
					 WHERE auto_reply_id=$get_auto_reply_id");
		}
		
		// Text
		$sql = "UPDATE $t_contact_forms_auto_replies SET auto_reply_text=? WHERE auto_reply_form_id='$get_current_form_id'";
		$stmt = $link->prepare($sql);
		$stmt->bind_param("s", $inp_text);
		$stmt->execute();
		if ($stmt->errno) {
			echo "FAILURE!!! " . $stmt->error; die;
		}
		

		// Header
		$url = "index.php?open=$open&page=$page&form_id=$form_id&editor_language=$editor_language&ft=success&fm=changes_saved";
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
		<a href=\"index.php?open=contact_forms&amp;page=edit_contact_auto_replies&amp;form_id=$form_id&amp;editor_language=$editor_language\">Auto reply</a>
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

	
		<!-- TinyMCE -->
		<script type=\"text/javascript\" src=\"_javascripts/tinymce/tinymce_4.7.1/tinymce.min.js\"></script>
		<script>
		tinymce.init({
			selector: \"textarea\",  // change this value according to your HTML
			plugins: \"image\",
			menubar: \"insert\",
			toolbar: \"image\",
			height: 500,
			menubar: false,
			plugins: [
			    'advlist autolink lists link image charmap print preview anchor textcolor',
			    'searchreplace visualblocks code fullscreen',
			    'insertdatetime media table contextmenu paste code help'
			  ],
			  toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
			  content_css: [
			    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
			    '//www.tinymce.com/css/codepen.min.css']
		});
		</script>
		<!-- //TinyMCE -->
		

		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_active\"]').focus();
		});
		</script>
			
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;form_id=$form_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">
		 

		<p><b>Auto reply active:</b><br />
		<input type=\"radio\" name=\"inp_active\" value=\"1\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\""; if($get_auto_reply_active == "1"){ echo" checked=\"checked\""; } echo" />
		Yes

		&nbsp;

		<input type=\"radio\" name=\"inp_active\" value=\"0\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\""; if($get_auto_reply_active == "0" OR $get_auto_reply_active == ""){ echo" checked=\"checked\""; } echo" />
		No
		</p>

		<p><b>From e-mail:</b><br />
		<input type=\"text\" name=\"inp_from_email\" value=\"$get_auto_reply_from_email\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>From name:</b><br />
		<input type=\"text\" name=\"inp_from_name\" value=\"$get_auto_reply_from_name\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>Subject:</b><br />
		<input type=\"text\" name=\"inp_subject\" value=\"$get_auto_reply_subject\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>Text:</b><br />
		<textarea name=\"inp_text\" rows=\"10\" cols=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">$get_auto_reply_text</textarea>
		</p>

		<p><input type=\"submit\" value=\"Save changes\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>
		</form>
	<!-- //Form -->

	";
} // form found
?>