<?php
/**
*
* File: _admin/_inc/contact_forms/edit_contact_form_images.php
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
			<a href=\"index.php?open=contact_forms&amp;page=edit_contact_form_general&amp;form_id=$form_id&amp;editor_language=$editor_language\">Images</a>
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

		<p>
		<a href=\"index.php?open=contact_forms&amp;page=edit_contact_form_new_image_uploader&amp;form_id=$form_id&amp;editor_language=$editor_language\"><img src=\"_inc/contact_forms/_gfx/icons/image-x-generic.png\" alt=\"image-x-generic.png\" /></a>
		<a href=\"index.php?open=contact_forms&amp;page=edit_contact_form_new_image_uploader&amp;form_id=$form_id&amp;editor_language=$editor_language\">Upload image</a>
		</p>



		<!-- Images -->
	
			<table>
			";
	
			$query = "SELECT image_id, image_contact_form_id, image_path, image_file FROM $t_contact_forms_images WHERE image_contact_form_id=$get_current_form_id";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_image_id, $get_image_contact_form_id, $get_image_path, $get_image_file) = $row;

				echo"
				 <tr>
				  <td>
					<p>
					<a href=\"../$get_image_path/$get_image_file\"><img src=\"../$get_image_path/$get_image_file\" alt=\"$get_image_file\" width=\"180\" height=\"90\" /></a>
					</p>
				  </td>
				  <td style=\"padding-left: 10px;vertical-align:top;\">
					<p>
					<b>URL:</b><br />
					<a href=\"../$get_image_path/$get_image_file\">$configSiteURLSav/$get_image_path/$get_image_file</a>
					</p>

					<p>
					<a href=\"index.php?open=contact_forms&amp;page=edit_contact_form_images&amp;action=delete_image&amp;form_id=$form_id&amp;image_id=$get_image_id&amp;editor_language=$editor_language\">Delete image</a>
					</p>
				  </td>
				 </tr>
				";
			}
			echo"
			</table>
		<!-- //Images -->

		";
	} // action == ""
	elseif($action == "delete_image"){
		if(isset($_GET['image_id'])){
			$image_id = $_GET['image_id'];
			$image_id = output_html($image_id);
		}
		else{
			$image_id = "";
		}
		$image_id_mysql = quote_smart($link, $image_id);

		$query = "SELECT image_id, image_contact_form_id, image_path, image_file FROM $t_contact_forms_images WHERE image_id=$image_id_mysql AND image_contact_form_id=$form_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_image_id, $get_current_image_contact_form_id, $get_current_image_path, $get_current_image_file ) = $row;

		if($get_current_image_id == ""){
			echo"
			<h1>Image not found</h1>
			";

		}
		else{
			if($process == "1"){
				// Delete image
				unlink("../$get_current_image_path/$get_current_image_file");

				// MySQL
				$result = mysqli_query($link, "DELETE FROM $t_contact_forms_images WHERE image_id=$image_id_mysql AND image_contact_form_id=$form_id_mysql");

				// Header
				$url = "index.php?open=$open&page=edit_contact_form_images&form_id=$form_id&editor_language=$inp_language&ft=success&fm=image_deleted";
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
				<a href=\"index.php?open=contact_forms&amp;page=edit_contact_form_general&amp;form_id=$form_id&amp;editor_language=$editor_language\">Images</a>
				</p>
			<!-- //Where am I? -->

			<p>
			<a href=\"../$get_current_image_path/$get_current_image_file\"><img src=\"../$get_current_image_path/$get_current_image_file\" alt=\"$get_current_image_file\" width=\"180\" height=\"90\" /></a>
			</p>


			<p>
			Are you sure you want to delete the image?
			</p>


			<p>
			<a href=\"index.php?open=contact_forms&amp;page=edit_contact_form_images&amp;action=delete_image&amp;form_id=$form_id&amp;image_id=$get_current_image_id&amp;editor_language=$editor_language&amp;process=1\" class=\"btn_warning\">Confirm delete</a>
			</p>
			";		
		} // image found
	} // delete image
} // form found
?>