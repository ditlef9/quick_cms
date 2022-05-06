<?php
/**
*
* File: _admin/_inc/tasks_attachments.php
* Version 1.0.1
* Date 12:54 28.04.2019
* Copyright (c) 2008-2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables ---------------------------------------------------------------------------- */
$t_tasks_index  			= $mysqlPrefixSav . "tasks_index";
$t_tasks_attachments 			= $mysqlPrefixSav . "tasks_attachments";
$t_tasks_subscriptions			= $mysqlPrefixSav . "tasks_subscriptions";
$t_tasks_status_codes  			= $mysqlPrefixSav . "tasks_status_codes";
$t_tasks_projects  			= $mysqlPrefixSav . "tasks_projects";
$t_tasks_projects_parts  		= $mysqlPrefixSav . "tasks_projects_parts";
$t_tasks_systems  			= $mysqlPrefixSav . "tasks_systems";
$t_tasks_systems_parts  		= $mysqlPrefixSav . "tasks_systems_parts";
$t_tasks_read				= $mysqlPrefixSav . "tasks_read";
$t_tasks_subscriptions_to_new_tasks 	= $mysqlPrefixSav . "tasks_subscriptions_to_new_tasks";
$t_tasks_history		 	= $mysqlPrefixSav . "tasks_history";

$t_tasks_last_used_systems	= $mysqlPrefixSav . "tasks_last_used_systems";
$t_tasks_last_used_projects	= $mysqlPrefixSav . "tasks_last_used_projects";

$t_users_notifications 		= $mysqlPrefixSav . "users_notifications";


/*- Functions --------------------------------------------------------------------------- */
include("_functions/get_extension.php");


/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['task_id'])) {
	$task_id = $_GET['task_id'];
	$task_id = strip_tags(stripslashes($task_id));
}
else{
	$task_id = "";
}


if($action == ""){
	// Get task
	$task_id_mysql = quote_smart($link, $task_id);
	$query = "SELECT task_id, task_title, task_text, task_status_code_id, task_priority_id, task_created_datetime, task_created_translated,  task_created_by_user_id, task_created_by_user_alias, task_created_by_user_image, task_created_by_user_email, task_updated_datetime, task_updated_translated, task_due_datetime, task_due_time, task_due_translated, task_assigned_to_user_id, task_assigned_to_user_alias, task_assigned_to_user_image, task_assigned_to_user_email, task_hours_planned, task_hours_used, task_hours_diff_number, task_hours_diff_percentage, task_qa_datetime, task_qa_by_user_id, task_qa_by_user_alias, task_qa_by_user_image, task_qa_by_user_email, task_finished_datetime, task_finished_by_user_id, task_finished_by_user_alias, task_finished_by_user_image, task_finished_by_user_email, task_is_archived, task_comments, task_project_id, task_project_part_id, task_system_id, task_system_part_id FROM $t_tasks_index WHERE task_id=$task_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_task_id, $get_current_task_title, $get_current_task_text, $get_current_task_status_code_id, $get_current_task_priority_id, $get_current_task_created_datetime, $get_current_task_created_translated, $get_current_task_created_by_user_id, $get_current_task_created_by_user_alias, $get_current_task_created_by_user_image, $get_current_task_created_by_user_email, $get_current_task_updated_datetime, $get_current_task_updated_translated, $get_current_task_due_datetime, $get_current_task_due_time, $get_current_task_due_translated, $get_current_task_assigned_to_user_id, $get_current_task_assigned_to_user_alias, $get_current_task_assigned_to_user_image, $get_current_task_assigned_to_user_email, $get_current_task_hours_planned, $get_current_task_hours_used, $get_current_task_hours_diff_number, $get_current_task_hours_diff_percentage, $get_current_task_qa_datetime, $get_current_task_qa_by_user_id, $get_current_task_qa_by_user_alias, $get_current_task_qa_by_user_image, $get_current_task_qa_by_user_email, $get_current_task_finished_datetime, $get_current_task_finished_by_user_id, $get_current_task_finished_by_user_alias, $get_current_task_finished_by_user_image, $get_current_task_finished_by_user_email, $get_current_task_is_archived, $get_current_task_comments, $get_current_task_project_id, $get_current_task_project_part_id, $get_current_task_system_id, $get_current_task_system_part_id) = $row;
	if($get_current_task_id == ""){
		echo"<p>Server error 404</p>";
	}
	else{

		echo"
		<h1>$get_current_task_title</h1>
		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
			";

			// Status
			$query = "SELECT status_code_id, status_code_title FROM $t_tasks_status_codes WHERE status_code_id=$get_current_task_status_code_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_status_code_id, $get_status_code_title) = $row;
			echo"
			&gt;
			<a href=\"index.php?open=$open&amp;page=tasks&amp;status_code_id=$get_status_code_id&amp;l=$l\">$get_status_code_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=tasks&amp;action=open_task&amp;task_id=$get_current_task_id&amp;l=$l\">$get_current_task_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=tasks&amp;action=edit_task&amp;task_id=$get_current_task_id&amp;l=$l\">Edit</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=tasks_attachments&amp;task_id=$get_current_task_id&amp;l=$l\">Attachments</a>
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

		<!-- Upload form -->
			<form method=\"POST\" action=\"index.php?open=$open&amp;page=tasks_attachments&amp;action=open_task&amp;task_id=$get_current_task_id&amp;l=$l&amp;action=upload&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			<p>
			<input name=\"inp_image\" type=\"file\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			<input type=\"submit\" value=\"Upload\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>
			</form>
		<!-- //Upload form -->

		<!-- Attachments -->
			";
			$x = 0;
			$query = "SELECT attachment_id, attachment_task_id, attachment_title, attachment_file_path, attachment_file_name, attachment_file_type, attachment_file_thumb, attachment_file_ext, attachment_file_size, attachment_uploaded_by_user_id, attachment_uploaded_by_user_name, attachment_uploaded_datetime, attachment_uploaded_saying FROM $t_tasks_attachments WHERE attachment_task_id=$get_current_task_id ORDER BY attachment_id DESC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_attachment_id, $get_attachment_task_id, $get_attachment_title, $get_attachment_file_path, $get_attachment_file_name, $get_attachment_file_type, $get_attachment_file_thumb, $get_attachment_file_ext, $get_attachment_file_size, $get_attachment_uploaded_by_user_id, $get_attachment_uploaded_by_user_name, $get_attachment_uploaded_datetime, $get_attachment_uploaded_saying) = $row;

				// Look for image
				if(!(file_exists("../$get_attachment_file_path/$get_attachment_file_name")) OR $get_attachment_file_name == ""){
					echo"<div class=\"info\"><p>Media &quot;../$get_attachment_file_path/$get_attachment_file_name&quot; doesnt exists. Deleting database reference.</p></div>\n";
					$result_delete = mysqli_query($link, "DELETE FROM $t_tasks_attachments WHERE attachment_id=$get_attachment_id");
				}

				// Look for thumb
				if($get_attachment_file_type == "image" && !(file_exists("../$get_attachment_file_path/$get_attachment_file_thumb")) && $get_attachment_file_thumb != ""){
					resize_crop_image(100, 100, "../$get_attachment_file_path/$get_attachment_file_name", "../$get_attachment_file_path/$get_attachment_file_thumb");
				}

				// Layout
				if($x == 0){
					echo"
					<div class=\"image_gallery_folder_browse_row\">
					";
				}
			
				// Title
				$title_len = strlen($get_attachment_file_name);
				if($title_len > 15){
					$get_attachment_file_name = substr($get_attachment_file_name, 0, 15);
				}

				echo"
						<div class=\"image_gallery_folder_browse_col\">
							<p>
							<a href=\"index.php?open=dashboard&amp;page=tasks_attachments&amp;task_id=$get_current_task_id&amp;action=view_attachment&amp;attachment_id=$get_attachment_id&amp;l=$l\">";
							if($get_attachment_file_type == "image"){
								echo"<img src=\"../$get_attachment_file_path/$get_attachment_file_thumb\" alt=\"$get_attachment_file_thumb\" />";
							}
							else{
								echo"<img src=\"_design/gfx/icons/100x100/$get_attachment_file_ext\" alt=\"_gfx/icons/100x100/$get_attachment_file_ext\" />";
							}
							echo"</a><br />
							<a href=\"index.php?open=dashboard&amp;page=tasks_attachments&amp;task_id=$get_current_task_id&amp;action=view_attachment&amp;attachment_id=$get_attachment_id&amp;l=$l\">$get_attachment_title</a>
							</p>
						</div>
				";

				// Layout
				if($x == 3){
					echo"
					</div>
					";
					$x = -1;
				}
				$x++;
			}
			if($x != 0){
			echo"
					</div>
					";
			}
			echo"
		<!-- Attachments -->
		";
	} // task found
} // action == ""
elseif($action == "upload"){
	// Get task
	$task_id_mysql = quote_smart($link, $task_id);
	$query = "SELECT task_id, task_title, task_text, task_status_code_id, task_priority_id, task_created_datetime, task_created_translated,  task_created_by_user_id, task_created_by_user_alias, task_created_by_user_image, task_created_by_user_email, task_updated_datetime, task_updated_translated, task_due_datetime, task_due_time, task_due_translated, task_assigned_to_user_id, task_assigned_to_user_alias, task_assigned_to_user_image, task_assigned_to_user_email, task_hours_planned, task_hours_used, task_hours_diff_number, task_hours_diff_percentage, task_qa_datetime, task_qa_by_user_id, task_qa_by_user_alias, task_qa_by_user_image, task_qa_by_user_email, task_finished_datetime, task_finished_by_user_id, task_finished_by_user_alias, task_finished_by_user_image, task_finished_by_user_email, task_is_archived, task_comments, task_project_id, task_project_part_id, task_system_id, task_system_part_id FROM $t_tasks_index WHERE task_id=$task_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_task_id, $get_current_task_title, $get_current_task_text, $get_current_task_status_code_id, $get_current_task_priority_id, $get_current_task_created_datetime, $get_current_task_created_translated, $get_current_task_created_by_user_id, $get_current_task_created_by_user_alias, $get_current_task_created_by_user_image, $get_current_task_created_by_user_email, $get_current_task_updated_datetime, $get_current_task_updated_translated, $get_current_task_due_datetime, $get_current_task_due_time, $get_current_task_due_translated, $get_current_task_assigned_to_user_id, $get_current_task_assigned_to_user_alias, $get_current_task_assigned_to_user_image, $get_current_task_assigned_to_user_email, $get_current_task_hours_planned, $get_current_task_hours_used, $get_current_task_hours_diff_number, $get_current_task_hours_diff_percentage, $get_current_task_qa_datetime, $get_current_task_qa_by_user_id, $get_current_task_qa_by_user_alias, $get_current_task_qa_by_user_image, $get_current_task_qa_by_user_email, $get_current_task_finished_datetime, $get_current_task_finished_by_user_id, $get_current_task_finished_by_user_alias, $get_current_task_finished_by_user_image, $get_current_task_finished_by_user_email, $get_current_task_is_archived, $get_current_task_comments, $get_current_task_project_id, $get_current_task_project_part_id, $get_current_task_system_id, $get_current_task_system_part_id) = $row;
	if($get_current_task_id == ""){
		echo"<p>Server error 404</p>";
	}
	else{


		// Create dir
		$year = date("Y");
		if(!is_dir("../_uploads")){
			mkdir("../_uploads");
		}
		if(!is_dir("../_uploads/tasks")){
			mkdir("../_uploads/tasks");
		}
		if(!is_dir("../_uploads/tasks/$year")){
			mkdir("../_uploads/tasks/$year");
		}
		if(!is_dir("../_uploads/tasks/$year/$get_current_task_id")){
			mkdir("../_uploads/tasks/$year/$get_current_task_id");
		}




		$tmp_name = $_FILES["inp_image"]["tmp_name"];
		$filename = stripslashes($_FILES['inp_image']['name']);
		$extension = get_extension($filename);
		$extension = strtolower($extension);

		// Transfer
		$ft = "";
		$fm = "";
		$attachment_id = "";


		// Me
		$my_user_id = $_SESSION['user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);

		$query = "SELECT user_id, user_email, user_name, user_alias, user_date_format FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_date_format) = $row;

		$inp_my_username_mysql = quote_smart($link, $get_my_user_name);


		$inp_ext = "$extension";
		$inp_ext = output_html($inp_ext);
		$inp_ext_mysql = quote_smart($link, $inp_ext);

		$inp_title = output_html($filename);
		$inp_title_mysql = quote_smart($link, $inp_title);

		$inp_file_path = "_uploads/tasks/$year/$get_current_task_id";
		$inp_file_path_mysql = quote_smart($link, $inp_file_path);

		$datetime = date("Y-m-d H:i:s");
		$date_saying = date("j M Y");

		$inp_my_alias_mysql = quote_smart($link, $get_my_user_alias);
		$inp_my_email_mysql = quote_smart($link, $get_my_user_email);
		$inp_my_image_mysql = quote_smart($link, $get_my_photo_destination);


		if($filename){
			if ($extension == "jpg" OR $extension == "jpeg" OR $extension == "png" OR $extension == "gif") {
				$inp_type = "image";
				$inp_type_mysql = quote_smart($link, $inp_type);

				$size=filesize($_FILES['inp_image']['tmp_name']);

				if($extension=="jpg" || $extension=="jpeg" ){
					ini_set ('gd.jpeg_ignore_warning', 1);
					error_reporting(0);
					$uploadedfile = $_FILES['inp_image']['tmp_name'];
					$src = imagecreatefromjpeg($uploadedfile);
				}
				elseif($extension=="png"){
					$uploadedfile = $_FILES['inp_image']['tmp_name'];
					$src = @imagecreatefrompng($uploadedfile);
				}
				else{
					$src = @imagecreatefromgif($uploadedfile);
				}
				list($width,$height) = @getimagesize($uploadedfile);
				if($width == "" OR $height == ""){
					$ft = "warning";
					$fm = "photo_could_not_be_uploaded_please_check_file_size";
				}
				else{

					mysqli_query($link, "INSERT INTO $t_tasks_attachments
					(attachment_id, attachment_task_id, attachment_title, attachment_file_path, attachment_file_type, 
					attachment_file_thumb, attachment_file_ext, attachment_file_size, attachment_uploaded_by_user_id, attachment_uploaded_by_user_name, 
					attachment_uploaded_datetime, attachment_uploaded_saying) 
					VALUES 
					(NULL, $get_current_task_id,  $inp_title_mysql, $inp_file_path_mysql, $inp_type_mysql, 
					'', $inp_ext_mysql, '', $get_my_user_id, $inp_my_username_mysql, 
					'$datetime', '$date_saying')")
					or die(mysqli_error($link));


					// Get ID
					$q = "SELECT attachment_id FROM $t_tasks_attachments WHERE attachment_uploaded_datetime='$datetime' AND attachment_uploaded_by_user_id=$my_user_id_mysql";
					$r = mysqli_query($link, $q);
					$rowb = mysqli_fetch_row($r);
					list($get_current_attachment_id) = $rowb;

					// Transfer
					$attachment_id = "$get_current_attachment_id";
						

					// Update values
					$inp_file_name = $get_current_attachment_id . "." . $extension;
					$inp_file_name_mysql = quote_smart($link, $inp_file_name);

					$inp_file_thumb = $get_current_attachment_id . "_thumb_100." . $extension;
					$inp_file_thumb_mysql = quote_smart($link, $inp_file_thumb);

					$result = mysqli_query($link, "UPDATE $t_tasks_attachments SET
									attachment_file_name=$inp_file_name_mysql,
									attachment_file_thumb=$inp_file_thumb_mysql
									 WHERE attachment_id=$get_current_attachment_id");

					if(move_uploaded_file($tmp_name, "../$inp_file_path/$inp_file_name")){
								
						// Header
						$ft = "success";
						$fm = "image_uploaded";

					} // move_uploaded_file
					else{
						$ft = "warning";
						$fm = "move_uploaded_file_failed";
					} // move_uploaded_file failed
				}  // if($width == "" OR $height == ""){
			} // image
			elseif($extension == "doc" OR $extension == "docx" OR $extension == "pdf" OR $extension == "txt" OR $extension == "xlsx") {
				if($extension == "doc" OR $extension == "docx") {
					$inp_type = "Word";
				}
				elseif($extension == "pdf") {
					$inp_type = "PDF";
				}
				elseif($extension == "xlsx") {
					$inp_type = "PDF";
				}
				else{
					$inp_type = "Text";
				}
				$inp_type_mysql = quote_smart($link, $inp_type);


				mysqli_query($link, "INSERT INTO $t_tasks_attachments
				(attachment_id, attachment_task_id, attachment_title, attachment_file_path, attachment_file_type, 
				attachment_file_thumb, attachment_file_ext, attachment_file_size, attachment_uploaded_by_user_id, attachment_uploaded_by_user_name, 
				attachment_uploaded_datetime, attachment_uploaded_saying) 
				VALUES 
				(NULL, $get_current_task_id,  $inp_title_mysql, $inp_file_path_mysql, $inp_type_mysql, 
				'', $inp_ext_mysql, '', $get_my_user_id, $inp_my_username_mysql, 
				'$datetime', '$date_saying')")
				or die(mysqli_error($link));

				// Get ID
				$q = "SELECT attachment_id FROM $t_tasks_attachments WHERE attachment_uploaded_datetime='$datetime' AND attachment_uploaded_by_user_id=$my_user_id_mysql";
				$r = mysqli_query($link, $q);
				$rowb = mysqli_fetch_row($r);
				list($get_current_attachment_id) = $rowb;

				// Transfer
				$attachment_id = "$get_current_attachment_id";



				// Update values
				$inp_file_name = $get_current_attachment_id . "." . $extension;
				$inp_file_name_mysql = quote_smart($link, $inp_file_name);

				$result = mysqli_query($link, "UPDATE $t_tasks_attachments SET
									attachment_file_name=$inp_file_name_mysql
									 WHERE attachment_id=$get_current_attachment_id");


				if(move_uploaded_file($tmp_name, "../$inp_file_path/$inp_file_name")){
					// Header
					$ft = "success";
					$fm = "document_$inp_file_name" . "_uploaded";
				}
				else{
					$ft = "warning";
					$fm = "move_uploaded_file_failed_for_docx_file";
				} // move_uploaded_file failed
			} // docx
			else{
				$ft = "warning";
				$fm = "unknown_file_format";
			}
		} // if($image){
		else{
					switch ($_FILES['inp_image']['error']) {
						case UPLOAD_ERR_OK:
								$fm = "photo_unknown_error";
								$ft = "warning";
								break;
						case UPLOAD_ERR_NO_FILE:
       								$fm = "no_file_selected";
								$ft = "warning";
								break;
						case UPLOAD_ERR_INI_SIZE:
           							$fm = "photo_exceeds_filesize";
								$ft = "warning";
								break;
						case UPLOAD_ERR_FORM_SIZE:
           							$fm = "photo_exceeds_filesize_form";
								$ft = "warning";
								break;
						default:
           							$fm = "unknown_upload_error";
								$ft = "warning";
								break;
					}


		} // else

		$url = "index.php?open=dashboard&page=tasks_attachments&task_id=$get_current_task_id&l=$l&ft=$ft&fm=$fm";
		header("Location: $url");
		exit;

	} // task found
} // action == "upload"
elseif($action == "view_attachment"){

	// Get task
	$task_id_mysql = quote_smart($link, $task_id);
	$query = "SELECT task_id, task_title, task_text, task_status_code_id, task_priority_id, task_created_datetime, task_created_translated,  task_created_by_user_id, task_created_by_user_alias, task_created_by_user_image, task_created_by_user_email, task_updated_datetime, task_updated_translated, task_due_datetime, task_due_time, task_due_translated, task_assigned_to_user_id, task_assigned_to_user_alias, task_assigned_to_user_image, task_assigned_to_user_email, task_hours_planned, task_hours_used, task_hours_diff_number, task_hours_diff_percentage, task_qa_datetime, task_qa_by_user_id, task_qa_by_user_alias, task_qa_by_user_image, task_qa_by_user_email, task_finished_datetime, task_finished_by_user_id, task_finished_by_user_alias, task_finished_by_user_image, task_finished_by_user_email, task_is_archived, task_comments, task_project_id, task_project_part_id, task_system_id, task_system_part_id FROM $t_tasks_index WHERE task_id=$task_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_task_id, $get_current_task_title, $get_current_task_text, $get_current_task_status_code_id, $get_current_task_priority_id, $get_current_task_created_datetime, $get_current_task_created_translated, $get_current_task_created_by_user_id, $get_current_task_created_by_user_alias, $get_current_task_created_by_user_image, $get_current_task_created_by_user_email, $get_current_task_updated_datetime, $get_current_task_updated_translated, $get_current_task_due_datetime, $get_current_task_due_time, $get_current_task_due_translated, $get_current_task_assigned_to_user_id, $get_current_task_assigned_to_user_alias, $get_current_task_assigned_to_user_image, $get_current_task_assigned_to_user_email, $get_current_task_hours_planned, $get_current_task_hours_used, $get_current_task_hours_diff_number, $get_current_task_hours_diff_percentage, $get_current_task_qa_datetime, $get_current_task_qa_by_user_id, $get_current_task_qa_by_user_alias, $get_current_task_qa_by_user_image, $get_current_task_qa_by_user_email, $get_current_task_finished_datetime, $get_current_task_finished_by_user_id, $get_current_task_finished_by_user_alias, $get_current_task_finished_by_user_image, $get_current_task_finished_by_user_email, $get_current_task_is_archived, $get_current_task_comments, $get_current_task_project_id, $get_current_task_project_part_id, $get_current_task_system_id, $get_current_task_system_part_id) = $row;
	if($get_current_task_id == ""){
		echo"<p>Server error 404</p>";
	}
	else{
		if(isset($_GET['attachment_id'])) {
			$attachment_id = $_GET['attachment_id'];
			$attachment_id = strip_tags(stripslashes($attachment_id));
		}
		else{
			$attachment_id = "";
		}
		$attachment_id_mysql = quote_smart($link, $attachment_id);

		$query = "SELECT attachment_id, attachment_task_id, attachment_title, attachment_file_path, attachment_file_name, attachment_file_type, attachment_file_thumb, attachment_file_ext, attachment_file_size, attachment_uploaded_by_user_id, attachment_uploaded_by_user_name, attachment_uploaded_datetime, attachment_uploaded_saying FROM $t_tasks_attachments WHERE attachment_id=$attachment_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_attachment_id, $get_current_attachment_task_id, $get_current_attachment_title, $get_current_attachment_file_path, $get_current_attachment_file_name, $get_current_attachment_file_type, $get_current_attachment_file_thumb, $get_current_attachment_file_ext, $get_current_attachment_file_size, $get_current_attachment_uploaded_by_user_id, $get_current_attachment_uploaded_by_user_name, $get_current_attachment_uploaded_datetime, $get_current_attachment_uploaded_saying) = $row;

		if($get_current_attachment_id == ""){
			echo"<p>Server error 404</p>";
		}
		else{

			echo"
			<h1>Attachment</h1>

			<!-- Where am I? -->
				<p><b>You are here:</b><br />
				<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
				";

				// Status
				$query = "SELECT status_code_id, status_code_title FROM $t_tasks_status_codes WHERE status_code_id=$get_current_task_status_code_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_status_code_id, $get_status_code_title) = $row;
				echo"
				&gt;
				<a href=\"index.php?open=$open&amp;page=tasks&amp;status_code_id=$get_status_code_id&amp;l=$l\">$get_status_code_title</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=tasks&amp;action=open_task&amp;task_id=$get_current_task_id&amp;l=$l\">$get_current_task_title</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=tasks&amp;action=edit_task&amp;task_id=$get_current_task_id&amp;l=$l\">Edit</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=tasks_attachments&amp;task_id=$get_current_task_id&amp;l=$l\">Attachments</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=tasks_attachments&amp;action=view_attachment&amp;attachment_id=$get_current_attachment_id&amp;task_id=$get_current_task_id&amp;l=$l\">$get_current_attachment_title</a>
				</p>
			<!-- //Where am I? -->
	

			<!-- View file -->
				";

				echo"
				<h2>$get_current_attachment_title</h2>
				
				<!-- Feedback -->
					";
					if($ft != ""){
						if($fm == "changes_saved"){
							$fm = "$l_changes_saved";
						}
						else{
							$fm = ucfirst($fm);
							$fm = str_replace("_", " ", $fm);
						}
						echo"<div class=\"$ft\"><span>$fm</span></div>";
					}
					echo"	
				<!-- //Feedback -->

									
				<script>
					\$(document).ready(function(){
						\$('[name=\"inp_copy\"]').focus().select();
					});
				</script>
				
				<p>
				<a href=\"../$get_current_attachment_file_path/$get_current_attachment_file_name\">";
				if($get_current_attachment_file_type == "image"){
					echo"<img src=\"../$get_current_attachment_file_path/$get_current_attachment_file_thumb\" alt=\"$get_current_attachment_file_thumb\" />";
				}
				else{
					echo"<img src=\"_design/gfx/icons/100x100/$get_current_attachment_file_ext\" alt=\"_gfx/icons/100x100/$get_current_attachment_file_ext\" />";
				}
				echo"</a><br />
				<a href=\"../$get_current_attachment_file_path/$get_current_attachment_file_name\">$get_current_attachment_title</a>
				</p>

				<p><b>URL:</b><br />
				<input type=\"text\" name=\"inp_copy\" value=\"../$get_current_attachment_file_path/$get_current_attachment_file_name\" size=\"25\" style=\"width: 100%;border: #fff 1px solid;border-bottom: #ccc 1px dashed;\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>
			<!-- //View file -->
									

			<!-- Edit File -->
				<form method=\"POST\" action=\"index.php?open=$open&amp;page=tasks_attachments&amp;action=edit_attachment&amp;attachment_id=$get_current_attachment_id&amp;task_id=$get_current_task_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
				
				<p><b>Title:</b><br />
				<input type=\"text\" name=\"inp_title\" value=\"$get_current_attachment_title\" size=\"25\" style=\"width: 100%;\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>
											  
				<p>
				<input type=\"submit\" value=\"Save changes\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				<a href=\"index.php?open=$open&amp;page=tasks_attachments&amp;action=delete_attachment&amp;attachment_id=$get_current_attachment_id&amp;task_id=$get_current_task_id&amp;l=$l\" class=\"btn_warning\">Delete</a>
				</p>
				</form>
			<!-- //Edit File -->
			";
		} // attachment found
	} // task found
} // action ==view_attachment
elseif($action == "edit_attachment"){

	// Get task
	$task_id_mysql = quote_smart($link, $task_id);
	$query = "SELECT task_id, task_title, task_text, task_status_code_id, task_priority_id, task_created_datetime, task_created_translated,  task_created_by_user_id, task_created_by_user_alias, task_created_by_user_image, task_created_by_user_email, task_updated_datetime, task_updated_translated, task_due_datetime, task_due_time, task_due_translated, task_assigned_to_user_id, task_assigned_to_user_alias, task_assigned_to_user_image, task_assigned_to_user_email, task_hours_planned, task_hours_used, task_hours_diff_number, task_hours_diff_percentage, task_qa_datetime, task_qa_by_user_id, task_qa_by_user_alias, task_qa_by_user_image, task_qa_by_user_email, task_finished_datetime, task_finished_by_user_id, task_finished_by_user_alias, task_finished_by_user_image, task_finished_by_user_email, task_is_archived, task_comments, task_project_id, task_project_part_id, task_system_id, task_system_part_id FROM $t_tasks_index WHERE task_id=$task_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_task_id, $get_current_task_title, $get_current_task_text, $get_current_task_status_code_id, $get_current_task_priority_id, $get_current_task_created_datetime, $get_current_task_created_translated, $get_current_task_created_by_user_id, $get_current_task_created_by_user_alias, $get_current_task_created_by_user_image, $get_current_task_created_by_user_email, $get_current_task_updated_datetime, $get_current_task_updated_translated, $get_current_task_due_datetime, $get_current_task_due_time, $get_current_task_due_translated, $get_current_task_assigned_to_user_id, $get_current_task_assigned_to_user_alias, $get_current_task_assigned_to_user_image, $get_current_task_assigned_to_user_email, $get_current_task_hours_planned, $get_current_task_hours_used, $get_current_task_hours_diff_number, $get_current_task_hours_diff_percentage, $get_current_task_qa_datetime, $get_current_task_qa_by_user_id, $get_current_task_qa_by_user_alias, $get_current_task_qa_by_user_image, $get_current_task_qa_by_user_email, $get_current_task_finished_datetime, $get_current_task_finished_by_user_id, $get_current_task_finished_by_user_alias, $get_current_task_finished_by_user_image, $get_current_task_finished_by_user_email, $get_current_task_is_archived, $get_current_task_comments, $get_current_task_project_id, $get_current_task_project_part_id, $get_current_task_system_id, $get_current_task_system_part_id) = $row;
	if($get_current_task_id == ""){
		echo"<p>Server error 404</p>";
	}
	else{
		if(isset($_GET['attachment_id'])) {
			$attachment_id = $_GET['attachment_id'];
			$attachment_id = strip_tags(stripslashes($attachment_id));
		}
		else{
			$attachment_id = "";
		}
		$attachment_id_mysql = quote_smart($link, $attachment_id);

		$query = "SELECT attachment_id, attachment_task_id, attachment_title, attachment_file_path, attachment_file_name, attachment_file_type, attachment_file_thumb, attachment_file_ext, attachment_file_size, attachment_uploaded_by_user_id, attachment_uploaded_by_user_name, attachment_uploaded_datetime, attachment_uploaded_saying FROM $t_tasks_attachments WHERE attachment_id=$attachment_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_attachment_id, $get_current_attachment_task_id, $get_current_attachment_title, $get_current_attachment_file_path, $get_current_attachment_file_name, $get_current_attachment_file_type, $get_current_attachment_file_thumb, $get_current_attachment_file_ext, $get_current_attachment_file_size, $get_current_attachment_uploaded_by_user_id, $get_current_attachment_uploaded_by_user_name, $get_current_attachment_uploaded_datetime, $get_current_attachment_uploaded_saying) = $row;

		if($get_current_attachment_id == ""){
			echo"<p>Server error 404</p>";
		}
		else{
			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$result_update = mysqli_query($link, "UPDATE $t_tasks_attachments SET attachment_title=$inp_title_mysql WHERE attachment_id=$attachment_id_mysql") or die(mysqli_error($link));


			$url = "index.php?open=dashboard&page=tasks_attachments&task_id=$get_current_task_id&action=view_attachment&attachment_id=$get_current_attachment_id&l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;
		} // attachment found
	} // task found
} // action ==view_attachment
elseif($action == "delete_attachment"){

	// Get task
	$task_id_mysql = quote_smart($link, $task_id);
	$query = "SELECT task_id, task_title, task_text, task_status_code_id, task_priority_id, task_created_datetime, task_created_translated,  task_created_by_user_id, task_created_by_user_alias, task_created_by_user_image, task_created_by_user_email, task_updated_datetime, task_updated_translated, task_due_datetime, task_due_time, task_due_translated, task_assigned_to_user_id, task_assigned_to_user_alias, task_assigned_to_user_image, task_assigned_to_user_email, task_hours_planned, task_hours_used, task_hours_diff_number, task_hours_diff_percentage, task_qa_datetime, task_qa_by_user_id, task_qa_by_user_alias, task_qa_by_user_image, task_qa_by_user_email, task_finished_datetime, task_finished_by_user_id, task_finished_by_user_alias, task_finished_by_user_image, task_finished_by_user_email, task_is_archived, task_comments, task_project_id, task_project_part_id, task_system_id, task_system_part_id FROM $t_tasks_index WHERE task_id=$task_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_task_id, $get_current_task_title, $get_current_task_text, $get_current_task_status_code_id, $get_current_task_priority_id, $get_current_task_created_datetime, $get_current_task_created_translated, $get_current_task_created_by_user_id, $get_current_task_created_by_user_alias, $get_current_task_created_by_user_image, $get_current_task_created_by_user_email, $get_current_task_updated_datetime, $get_current_task_updated_translated, $get_current_task_due_datetime, $get_current_task_due_time, $get_current_task_due_translated, $get_current_task_assigned_to_user_id, $get_current_task_assigned_to_user_alias, $get_current_task_assigned_to_user_image, $get_current_task_assigned_to_user_email, $get_current_task_hours_planned, $get_current_task_hours_used, $get_current_task_hours_diff_number, $get_current_task_hours_diff_percentage, $get_current_task_qa_datetime, $get_current_task_qa_by_user_id, $get_current_task_qa_by_user_alias, $get_current_task_qa_by_user_image, $get_current_task_qa_by_user_email, $get_current_task_finished_datetime, $get_current_task_finished_by_user_id, $get_current_task_finished_by_user_alias, $get_current_task_finished_by_user_image, $get_current_task_finished_by_user_email, $get_current_task_is_archived, $get_current_task_comments, $get_current_task_project_id, $get_current_task_project_part_id, $get_current_task_system_id, $get_current_task_system_part_id) = $row;
	if($get_current_task_id == ""){
		echo"<p>Server error 404</p>";
	}
	else{
		if(isset($_GET['attachment_id'])) {
			$attachment_id = $_GET['attachment_id'];
			$attachment_id = strip_tags(stripslashes($attachment_id));
		}
		else{
			$attachment_id = "";
		}
		$attachment_id_mysql = quote_smart($link, $attachment_id);

		$query = "SELECT attachment_id, attachment_task_id, attachment_title, attachment_file_path, attachment_file_name, attachment_file_type, attachment_file_thumb, attachment_file_ext, attachment_file_size, attachment_uploaded_by_user_id, attachment_uploaded_by_user_name, attachment_uploaded_datetime, attachment_uploaded_saying FROM $t_tasks_attachments WHERE attachment_id=$attachment_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_attachment_id, $get_current_attachment_task_id, $get_current_attachment_title, $get_current_attachment_file_path, $get_current_attachment_file_name, $get_current_attachment_file_type, $get_current_attachment_file_thumb, $get_current_attachment_file_ext, $get_current_attachment_file_size, $get_current_attachment_uploaded_by_user_id, $get_current_attachment_uploaded_by_user_name, $get_current_attachment_uploaded_datetime, $get_current_attachment_uploaded_saying) = $row;

		if($get_current_attachment_id == ""){
			echo"<p>Server error 404</p>";
		}
		else{
			if($process == "1"){

				// Look for image
				if(!(file_exists("../$get_attachment_file_path/$get_attachment_file_name")) OR $get_attachment_file_name == ""){
					unlink("../$get_attachment_file_path/$get_attachment_file_name");
				}
				// Look for thumb
				if(!(file_exists("../$get_attachment_file_path/$get_attachment_file_thumb")) OR $get_attachment_file_thumb == ""){
					unlink("../$get_attachment_file_path/$get_attachment_file_thumb");
				}


				$result_update = mysqli_query($link, "DELETE FROM $t_tasks_attachments WHERE attachment_id=$attachment_id_mysql") or die(mysqli_error($link));


				$url = "index.php?open=dashboard&page=tasks_attachments&task_id=$get_current_task_id&l=$l&ft=success&fm=deleted";
				header("Location: $url");
				exit;
			}

			echo"
			<h1>Attachment</h1>

			<!-- Where am I? -->
				<p><b>You are here:</b><br />
				<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
				";

				// Status
				$query = "SELECT status_code_id, status_code_title FROM $t_tasks_status_codes WHERE status_code_id=$get_current_task_status_code_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_status_code_id, $get_status_code_title) = $row;
				echo"
				&gt;
				<a href=\"index.php?open=$open&amp;page=tasks&amp;status_code_id=$get_status_code_id&amp;l=$l\">$get_status_code_title</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=tasks&amp;action=open_task&amp;task_id=$get_current_task_id&amp;l=$l\">$get_current_task_title</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=tasks&amp;action=edit_task&amp;task_id=$get_current_task_id&amp;l=$l\">Edit</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=tasks_attachments&amp;task_id=$get_current_task_id&amp;l=$l\">Attachments</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=tasks_attachments&amp;action=view_attachment&amp;attachment_id=$get_current_attachment_id&amp;task_id=$get_current_task_id&amp;l=$l\">$get_current_attachment_title</a>
				</p>
			<!-- //Where am I? -->
	

			<!-- View file -->
				";

				echo"
				<h2>$get_current_attachment_title</h2>
				
				<!-- Feedback -->
					";
					if($ft != ""){
						if($fm == "changes_saved"){
							$fm = "$l_changes_saved";
						}
						else{
							$fm = ucfirst($fm);
							$fm = str_replace("_", " ", $fm);
						}
						echo"<div class=\"$ft\"><span>$fm</span></div>";
					}
					echo"	
				<!-- //Feedback -->

									
				<script>
					\$(document).ready(function(){
						\$('[name=\"inp_copy\"]').focus().select();
					});
				</script>
				
				<p>
				<a href=\"index.php?open=dashboard&amp;page=tasks_attachments&amp;task_id=$get_current_task_id&amp;action=view_attachment&amp;attachment_id=$get_current_attachment_id&amp;l=$l\">";
				if($get_current_attachment_file_type == "image"){
					echo"<img src=\"../$get_current_attachment_file_path/$get_current_attachment_file_thumb\" alt=\"$get_current_attachment_file_thumb\" />";
				}
				else{
					echo"<img src=\"_design/gfx/icons/100x100/$get_current_attachment_file_ext\" alt=\"_gfx/icons/100x100/$get_current_attachment_file_ext\" />";
				}
				echo"</a><br />
				<a href=\"index.php?open=dashboard&amp;page=tasks_attachments&amp;task_id=$get_current_task_id&amp;action=view_attachment&amp;attachment_id=$get_current_attachment_id&amp;l=$l\">$get_current_attachment_title</a>
				</p>

				<p><b>URL:</b><br />
				<input type=\"text\" name=\"inp_copy\" value=\"../$get_current_attachment_file_path/$get_current_attachment_file_name\" size=\"25\" style=\"width: 100%;border: #fff 1px solid;border-bottom: #ccc 1px dashed;\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>
			<!-- //View file -->
									

			<!-- Delete File -->
				<p>Are you sure you want to delete the file?</p>

				<p>
				<a href=\"index.php?open=$open&amp;page=tasks_attachments&amp;action=delete_attachment&amp;attachment_id=$get_current_attachment_id&amp;task_id=$get_current_task_id&amp;l=$l&amp;process=1\" class=\"btn_danger\">Confirm</a>
				</p>
			<!-- //Delete  File -->
			";

		} // attachment found
	} // task found
} // action ==view_attachment
?>