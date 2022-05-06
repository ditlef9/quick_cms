<?php
/**
*
* File: _admin/_inc/contact_forms/edit_contact_form_text_after_form.php
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

		// Me
		$datetime = date("Y-m-d H:i:s");

		$my_user_id = $_SESSION['admin_user_id'];
		$my_user_id  = output_html($my_user_id );
		$my_user_id_mysql = quote_smart($link, $my_user_id);

		$result = mysqli_query($link, "UPDATE $t_contact_forms_index SET form_updated_datetime='$datetime', form_updated_by_user_id=$my_user_id_mysql WHERE form_id=$form_id_mysql");

		// Text
		$inp_text = $_POST['inp_text'];
		$sql = "UPDATE $t_contact_forms_index SET form_text_after_form=? WHERE form_id='$get_current_form_id'";
		$stmt = $link->prepare($sql);
		$stmt->bind_param("s", $inp_text);
		$stmt->execute();
		if ($stmt->errno) {
			echo "FAILURE!!! " . $stmt->error; die;
		}


		// Header
		$url = "index.php?open=$open&page=$page&form_id=$form_id&editor_language=$inp_language&ft=success&fm=changes_saved";
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
		<a href=\"index.php?open=contact_forms&amp;page=$page&amp;form_id=$form_id&amp;editor_language=$editor_language\">Text after form</a>
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
				<script type=\"text/javascript\" src=\"_javascripts/tinymce/tinymce.min.js\"></script>
				<script>
				tinymce.init({
					selector: 'textarea.editor',
					plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern help',
					toolbar: 'formatselect | bold italic strikethrough forecolor backcolor permanentpen formatpainter | link image media pageembed | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment',
					image_advtab: true,
					content_css: [
						'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
						'//www.tiny.cloud/css/codepen.min.css'
					],
					link_list: [
						{ title: 'My page 1', value: 'http://www.tinymce.com' },
						{ title: 'My page 2', value: 'http://www.moxiecode.com' }
					],
					image_list: [
						{ title: 'My page 1', value: 'http://www.tinymce.com' },
						{ title: 'My page 2', value: 'http://www.moxiecode.com' }
					],
						image_class_list: [
						{ title: 'None', value: '' },
						{ title: 'Some class', value: 'class-name' }
					],
					importcss_append: true,
					height: 500,
					file_picker_callback: function (callback, value, meta) {
						/* Provide file and text for the link dialog */
						if (meta.filetype === 'file') {
							callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
						}
						/* Provide image and alt text for the image dialog */
						if (meta.filetype === 'image') {
							callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
						}
						/* Provide alternative source and posted for the media dialog */
						if (meta.filetype === 'media') {
							callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
						}
					}
				});
				</script>
		<!-- //TinyMCE -->

		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_title\"]').focus();
		});
		</script>
			
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;form_id=$form_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">
		
		<p>
		<a href=\"index.php?open=contact_forms&amp;page=edit_contact_form_new_image_uploader&amp;form_id=$form_id&amp;editor_language=$editor_language\" target=\"_blank\"><img src=\"_inc/contact_forms/_gfx/icons/image-x-generic.png\" alt=\"image-x-generic.png\" /></a>
		<a href=\"index.php?open=contact_forms&amp;page=edit_contact_form_new_image_uploader&amp;form_id=$form_id&amp;editor_language=$editor_language\" target=\"_blank\">Upload image</a>
		</p>

		<p><b>Text before form:</b><br />
		<textarea name=\"inp_text\" rows=\"40\" cols=\"120\" class=\"editor\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">$get_current_form_text_after_form</textarea>
		</p>

		<p><input type=\"submit\" value=\"Save changes\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
		</form>
	<!-- //Form -->

	";
} // form found
?>