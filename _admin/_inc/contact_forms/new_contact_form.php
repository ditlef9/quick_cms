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



/*- Translations --------------------------------------------------------------------- */
if($action == ""){
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

		$datetime = date("Y-m-d H:i:s");

		// Me
		$my_user_id = $_SESSION['admin_user_id'];
		$my_user_id  = output_html($my_user_id );
		$my_user_id_mysql = quote_smart($link, $my_user_id);

		// API Password
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    		$charactersLength = strlen($characters);
    		$inp_api_password = '';
    		for ($i = 0; $i < 6; $i++) {
        		$inp_api_password .= $characters[rand(0, $charactersLength - 1)];
    		}
		$inp_api_password_mysql = quote_smart($link, $inp_api_password);

		mysqli_query($link, "INSERT INTO $t_contact_forms_index
		(form_id, form_title, form_language, form_mail_to, form_text_before_form, form_text_left_of_form, form_text_right_of_form, form_text_after_form, form_created_datetime, form_created_by_user_id, form_updated_datetime, form_updated_by_user_id, form_api_avaible, form_api_password, form_ipblock, form_used_times) 
		VALUES 
		(NULL, $inp_title_mysql, $inp_language_mysql, $inp_form_mail_to_mysql, '', '', '', '', '$datetime', $my_user_id_mysql, '$datetime', $my_user_id_mysql, $inp_api_avaible_mysql, $inp_api_password_mysql,'', '0')")
		or die(mysqli_error($link));

		// Get ID
		$query = "SELECT form_id FROM $t_contact_forms_index WHERE form_created_datetime='$datetime'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_form_id) = $row;
	
		// Header
		$url = "index.php?open=$open&page=open_contact_form&form_id=$get_form_id&editor_language=$inp_language";
		header("Location: $url");
		exit;
		
	}
	echo"
	<h1>New contact form</h1>


	<!-- Form -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_title\"]').focus();
		});
		</script>
			
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">
		
		<p><b>Title:</b><br />
		<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>
	
		<p><b>Language:</b><br />
		<select name=\"inp_language\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">\n";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;
			echo"	<option value=\"$get_language_active_iso_two\""; if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\""; } echo">$get_language_active_name</option>\n";
		}
		echo"
		</select>

		<p><b>E-mail to:</b><br />
		<input type=\"text\" name=\"inp_form_mail_to\" value=\"\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>API avaible:</b><br />
		<input type=\"radio\" name=\"inp_api_avaible\" value=\"1\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" checked=\"checked\" /> Yes
		&nbsp;
		<input type=\"radio\" name=\"inp_api_avaible\" value=\"0\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> No
		</p>


		<p><input type=\"submit\" value=\"Create from\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
		</form>
	<!-- //Form -->

	";
} // action == ""
?>