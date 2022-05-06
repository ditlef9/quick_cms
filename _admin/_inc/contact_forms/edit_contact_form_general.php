<?php
/**
*
* File: _admin/_inc/contact_forms/edit_contact_form_general.php
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
	if($process == "1"){
		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

		$inp_language = $_POST['inp_language'];
		$inp_language = output_html($inp_language);
		$inp_language_mysql = quote_smart($link, $inp_language);

		$inp_form_mail_to = $_POST['inp_form_mail_to'];
		$inp_form_mail_to = output_html($inp_form_mail_to);
		$inp_form_mail_to_mysql = quote_smart($link, $inp_form_mail_to);

		$inp_api_avaible = $_POST['inp_api_avaible'];
		$inp_api_avaible = output_html($inp_api_avaible);
		$inp_api_avaible_mysql = quote_smart($link, $inp_api_avaible);

		$inp_api_password = $_POST['inp_api_password'];
		$inp_api_password = output_html($inp_api_password);
		$inp_api_password_mysql = quote_smart($link, $inp_api_password);

		$datetime = date("Y-m-d H:i:s");

		// Me
		$my_user_id = $_SESSION['admin_user_id'];
		$my_user_id  = output_html($my_user_id );
		$my_user_id_mysql = quote_smart($link, $my_user_id);

		

		$result = mysqli_query($link, "UPDATE $t_contact_forms_index SET form_title=$inp_title_mysql, form_language=$inp_language_mysql, form_mail_to=$inp_form_mail_to_mysql,
form_updated_datetime='$datetime', form_updated_by_user_id=$my_user_id_mysql, form_api_avaible=$inp_api_avaible_mysql, form_api_password=$inp_api_password_mysql WHERE form_id=$form_id_mysql");

		// Header
		$url = "index.php?open=$open&page=edit_contact_form_general&form_id=$form_id&editor_language=$inp_language&ft=success&fm=changes_saved";
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
		<a href=\"index.php?open=contact_forms&amp;page=edit_contact_form_general&amp;form_id=$form_id&amp;editor_language=$editor_language\">General</a>
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
			
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;form_id=$form_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">
		
		<p><b>Title:</b><br />
		<input type=\"text\" name=\"inp_title\" value=\"$get_current_form_title\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>
	
		<p><b>Language:</b><br />
		<select name=\"inp_language\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">\n";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;
			echo"	<option value=\"$get_language_active_iso_two\""; if($get_current_form_language == "$get_language_active_iso_two"){ echo" selected=\"selected\""; } echo">$get_language_active_name</option>\n";
		}
		echo"
		</select>

		<p><b>E-mail to:</b><br />
		<input type=\"text\" name=\"inp_form_mail_to\" value=\"$get_current_form_mail_to\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>API avaible:</b><br />
		<input type=\"radio\" name=\"inp_api_avaible\" value=\"1\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\""; if($get_current_form_api_avaible == "1"){ echo" checked=\"checked\""; } echo" /> Yes
		&nbsp;
		<input type=\"radio\" name=\"inp_api_avaible\" value=\"0\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\""; if($get_current_form_api_avaible == "0"){ echo" checked=\"checked\""; } echo" /> No
		</p>

		<p><b>API password:</b><br />
		<input type=\"text\" name=\"inp_api_password\" value=\"$get_current_form_api_password\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>


		<p><input type=\"submit\" value=\"Save changes\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
		</form>
	<!-- //Form -->

	";
} // form found
?>