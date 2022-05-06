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
$t_contact_forms_images			= $mysqlPrefixSav . "contact_forms_images";
$t_contact_forms_questions		= $mysqlPrefixSav . "contact_forms_questions";
$t_contact_forms_questions_alternatives	= $mysqlPrefixSav . "contact_forms_questions_alternatives";

/*- Functions --------------------------------------------------------------------------- */
include("_functions/get_extension.php");

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
		// Dir
		if(!(is_dir("../_uploads"))){
			mkdir("../_uploads");
		}
		if(!(is_dir("../_uploads/contact_forms_images"))){
			mkdir("../_uploads/contact_forms_images");
		}

		// Logo
		$image = $_FILES['inp_image']['name'];
				
		$filename = stripslashes($_FILES['inp_image']['name']);
		$extension = get_extension($filename);
		$extension = strtolower($extension);

		$ft_image = "";
           	$fm_image = "";
		if($image){
			if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
				$ft_image = "warning";
				$fm_image = "unknown_file_format";
			}
			else{
       
				$size = filesize($_FILES['inp_image']['tmp_name']);
				$tmp_name = $_FILES["inp_image"]["tmp_name"];
				$datetime = date("ymdhis");
				$target_file = "../_uploads/contact_forms_images/". $get_current_form_id . "_" . $datetime  . "." . $extension;

				if(move_uploaded_file($tmp_name, "$target_file")){
 
					list($width,$height) = @getimagesize($target_file);

					if($width == "" OR $height == ""){
						$ft_image = "warning";
						$fm_image = "photo_could_not_be_uploaded_please_check_file_size";
						unlink("$target_file");
					}
					else{
						// Inp path
						$inp_path = "_uploads/contact_forms_images";
						$inp_path_mysql = quote_smart($link, $inp_path);				

						// Inp img
						$inp_image = $get_current_form_id . "_" . $datetime  . "." . $extension;
						$inp_image_mysql = quote_smart($link, $inp_image);
	
						// Insert into MySQL						
						mysqli_query($link, "INSERT INTO $t_contact_forms_images
						(image_id, image_contact_form_id, image_path, image_file) 
						VALUES 
						(NULL, $get_current_form_id, $inp_path_mysql, $inp_image_mysql)")
						or die(mysqli_error($link));



						// Send feedback
						$ft_image = "success";
						$fm_image  = "image_uploaded";
					}  // if($width == "" OR $height == ""){
				} // move uploaded file
			}
		} // if($image){
		else{
			switch ($_FILES['inp_image']['error']) {
				case UPLOAD_ERR_OK:
					$ft_image = "warning";
           				$fm_image = "photo_unknown_error";
					break;
				case UPLOAD_ERR_NO_FILE:
					$ft_image = "warning";
           				$fm_image = "no_file_selected";
					break;
				case UPLOAD_ERR_INI_SIZE:
					$ft_image = "warning";
           				$fm_image = "photo_exceeds_filesize";
					break;
				case UPLOAD_ERR_FORM_SIZE:
					$ft_image = "warning";
           				$fm_image = "photo_exceeds_filesize_form";
					break;
				default:
					$ft_image = "warning";
           				$fm_image = "unknown_upload_error";
					break;
			}
						
				
		}

		// Header
		$url = "index.php?open=$open&page=edit_contact_form_images&form_id=$form_id&editor_language=$inp_language&ft=$ft_image&fm=$fm_image";
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
		<a href=\"index.php?open=contact_forms&amp;page=edit_contact_form_images&amp;form_id=$form_id&amp;editor_language=$editor_language\">Images</a>
		&gt;
		<a href=\"index.php?open=contact_forms&amp;page=$page&amp;form_id=$form_id&amp;editor_language=$editor_language\">Upload image</a>
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
		
		<p><b>Image:</b><br />
		<input name=\"inp_image\" type=\"file\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><input type=\"submit\" value=\"Upload image\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
		</form>
	<!-- //Form -->

	";
} // form found
?>