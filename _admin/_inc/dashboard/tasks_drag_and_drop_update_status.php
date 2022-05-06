<?php
/**
*
* File: _admin/_inc/tasks_drag_and_drop_update_status.php
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
$t_tasks_user_subscription_selections	= $mysqlPrefixSav . "tasks_user_subscription_selections";
$t_tasks_history		 	= $mysqlPrefixSav . "tasks_history";
$t_tasks_priorities		 	= $mysqlPrefixSav . "tasks_priorities";
$t_tasks_templates		 	= $mysqlPrefixSav . "tasks_templates";

$t_tasks_last_used_systems	= $mysqlPrefixSav . "tasks_last_used_systems";
$t_tasks_last_used_projects	= $mysqlPrefixSav . "tasks_last_used_projects";

$t_users_notifications 		= $mysqlPrefixSav . "users_notifications";




/*- Variables -------------------------------------------------------------------------- */
if(isset($_POST['task_id'])) {
	$task_id = $_POST['task_id'];
	$task_id = strip_tags(stripslashes($task_id));
	$task_id = str_replace("task_id", "", $task_id );
}
else{
	echo"Missing task id";
	die;
}
if(isset($_POST['to_status_and_user'])) {
	$to_status_and_user = $_POST['to_status_and_user'];
	$to_status_and_user = strip_tags(stripslashes($to_status_and_user));
}
else{
	echo"Missing to status and user";
	die;
}


// Get task
$task_id_mysql = quote_smart($link, $task_id);
$query = "SELECT task_id, task_system_task_abbr, task_system_incremented_number, task_project_task_abbr, task_project_incremented_number, task_title, task_text, task_status_code_id, task_priority_id, task_created_datetime, task_created_translated,  task_created_by_user_id, task_created_by_user_alias, task_created_by_user_image, task_created_by_user_email, task_updated_datetime, task_updated_translated, task_due_datetime, task_due_time, task_due_translated, task_assigned_to_user_id, task_assigned_to_user_alias, task_assigned_to_user_image, task_assigned_to_user_email, task_hours_planned, task_hours_used, task_qa_datetime, task_qa_by_user_id, task_qa_by_user_alias, task_qa_by_user_image, task_qa_by_user_email, task_finished_datetime, task_finished_by_user_id, task_finished_by_user_alias, task_finished_by_user_image, task_finished_by_user_email, task_is_archived, task_comments, task_project_id, task_project_part_id, task_system_id, task_system_part_id FROM $t_tasks_index WHERE task_id=$task_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_task_id, $get_current_task_system_task_abbr, $get_current_task_system_incremented_number, $get_current_task_project_task_abbr, $get_current_task_project_incremented_number, $get_current_task_title, $get_current_task_text, $get_current_task_status_code_id, $get_current_task_priority_id, $get_current_task_created_datetime, $get_current_task_created_translated, $get_current_task_created_by_user_id, $get_current_task_created_by_user_alias, $get_current_task_created_by_user_image, $get_current_task_created_by_user_email, $get_current_task_updated_datetime, $get_current_task_updated_translated, $get_current_task_due_datetime, $get_current_task_due_time, $get_current_task_due_translated, $get_current_task_assigned_to_user_id, $get_current_task_assigned_to_user_alias, $get_current_task_assigned_to_user_image, $get_current_task_assigned_to_user_email, $get_current_task_hours_planned, $get_current_task_hours_used, $get_current_task_qa_datetime, $get_current_task_qa_by_user_id, $get_current_task_qa_by_user_alias, $get_current_task_qa_by_user_image, $get_current_task_qa_by_user_email, $get_current_task_finished_datetime, $get_current_task_finished_by_user_id, $get_current_task_finished_by_user_alias, $get_current_task_finished_by_user_image, $get_current_task_finished_by_user_email, $get_current_task_is_archived, $get_current_task_comments, $get_current_task_project_id, $get_current_task_project_part_id, $get_current_task_system_id, $get_current_task_system_part_id) = $row;
if($get_current_task_id == ""){
	echo"<p>Server error 404 - task not found</p>";
}
else{
	// Find status and user
	// if user id is present, then we want to also assign it to the user
	$inp_status_code_id 	 = $to_status_and_user;
	$inp_assigned_to_user_id = 0;
	if(strstr($to_status_and_user, 'user_id')) {
		// Remove user id from string
		$array = explode("user_id", $to_status_and_user);
		$inp_status_code_id	 = str_replace("status_code_id", "", $array[0]);
		$inp_assigned_to_user_id = $array[1];
	}

	// Updated
	$datetime = date("Y-m-d H:i:s");
	$inp_updated_translated = date("j M Y");
	$month = date("m");
	$week = date("W");
	$year = date("Y");
	$inp_updated_translated_mysql = quote_smart($link, $inp_updated_translated);

	// Assigned to
	if($inp_assigned_to_user_id == "" OR $inp_assigned_to_user_id == "0"){
		$inp_assigned_to_user_id_mysql = quote_smart($link, 0);
		$inp_assigned_to_user_name_mysql = quote_smart($link, "");
		$inp_assigned_to_user_alias_mysql = quote_smart($link, "");
		$inp_assigned_to_user_image_mysql = quote_smart($link, "");
		$inp_assigned_to_user_thumb_a_mysql = quote_smart($link, "");
		$inp_assigned_to_user_thumb_b_mysql = quote_smart($link, "");
		$inp_assigned_to_user_email_mysql = quote_smart($link, "");

	}
	else{
		$inp_assigned_to_user_id = output_html($inp_assigned_to_user_id);
		$inp_assigned_to_user_id_mysql = quote_smart($link, $inp_assigned_to_user_id);

		$query = "SELECT user_id, user_email, user_name, user_alias FROM $t_users WHERE user_id=$inp_assigned_to_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_user_id, $get_user_email, $get_user_name, $get_user_alias) = $row;

		$inp_assigned_to_user_email = "$get_user_email";
		$inp_assigned_to_user_email = output_html($inp_assigned_to_user_email);
		$inp_assigned_to_user_email_mysql = quote_smart($link, $inp_assigned_to_user_email);
		$inp_assigned_to_user_name = "$get_user_name";
		$inp_assigned_to_user_name = output_html($inp_assigned_to_user_name);
		$inp_assigned_to_user_name_mysql = quote_smart($link, $inp_assigned_to_user_name);
		$inp_assigned_to_user_alias = "$get_user_alias";
		$inp_assigned_to_user_alias = output_html($inp_assigned_to_user_alias);
		$inp_assigned_to_user_alias_mysql = quote_smart($link, $inp_assigned_to_user_alias);

		// Get assigned to photo
		$query = "SELECT photo_id, photo_destination, photo_thumb_40, photo_thumb_50 FROM $t_users_profile_photo WHERE photo_user_id=$inp_assigned_to_user_id_mysql AND photo_profile_image='1'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_photo_id, $get_photo_destination, $get_photo_thumb_40, $get_photo_thumb_50) = $row;

		$inp_assigned_to_user_image_mysql = quote_smart($link, $get_photo_destination);
		$inp_assigned_to_user_thumb_a_mysql = quote_smart($link, $get_photo_thumb_40);
		$inp_assigned_to_user_thumb_b_mysql = quote_smart($link, $get_photo_thumb_50);
	}

	
	// Update status_code_count_tasks
	$inp_status_code_id_mysql = quote_smart($link, $inp_status_code_id);
	$query = "SELECT status_code_id, status_code_title, status_code_text_color, status_code_bg_color, status_code_border_color, status_code_weight, status_code_show_on_board, status_code_on_status_close_task, status_code_count_tasks FROM $t_tasks_status_codes WHERE status_code_id=$inp_status_code_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_status_code_id, $get_status_code_title, $get_status_code_text_color, $get_status_code_bg_color, $get_status_code_border_color, $get_status_code_weight, $get_status_code_show_on_board, $get_status_code_on_status_close_task, $get_status_code_count_tasks) = $row;
	if($get_status_code_id == ""){
		echo"<div class=\"error\"><p>Status code not found!</p></div>\n";
	}
	$inp_status_code_title_mysql = quote_smart($link, $get_status_code_title);


	$inp_history_new_status_code_id = "0";
	$inp_history_new_status_code_title = "";
	if($get_current_task_status_code_id != "$inp_status_code_id"){
		$inp_history_new_status_code_id = "$inp_status_code_id";
		$inp_history_new_status_code_title = "$get_status_code_title";
		$inp_history_summary = "New status: $get_status_code_title";
	}
	$inp_history_new_status_code_id_mysql = quote_smart($link, $inp_history_new_status_code_id);
	$inp_history_new_status_code_title_mysql = quote_smart($link, $inp_history_new_status_code_title);

	// Update
	$result = mysqli_query($link, "UPDATE $t_tasks_index SET 
					task_status_code_id=$get_status_code_id, 
					task_status_code_title=$inp_status_code_title_mysql, 
					task_assigned_to_user_id=$inp_assigned_to_user_id_mysql, 
					task_assigned_to_user_name=$inp_assigned_to_user_name_mysql, 
					task_assigned_to_user_alias=$inp_assigned_to_user_alias_mysql, 
					task_assigned_to_user_image=$inp_assigned_to_user_image_mysql, 
					task_assigned_to_user_thumb_40=$inp_assigned_to_user_thumb_a_mysql, 
					task_assigned_to_user_thumb_50=$inp_assigned_to_user_thumb_b_mysql, 
					task_assigned_to_user_email=$inp_assigned_to_user_email_mysql, 
					task_updated_datetime='$datetime', 
					task_updated_translated=$inp_updated_translated_mysql
					 WHERE task_id=$get_current_task_id") or die(mysqli_error($link));


			// Fetch my id and alias
			$my_user_id = $_SESSION['user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);
			$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;

			$query = "SELECT photo_id, photo_destination, photo_thumb_40, photo_thumb_50 FROM $t_users_profile_photo WHERE photo_user_id=$get_my_user_id AND photo_profile_image='1'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_photo_id, $get_photo_destination, $get_photo_thumb_40, $get_photo_thumb_50) = $row;

			$inp_updated_by_user_id = $get_my_user_id;
			$inp_updated_by_user_id = output_html($inp_updated_by_user_id);
			$inp_updated_by_user_id_mysql = quote_smart($link, $inp_updated_by_user_id);

			$inp_updated_by_user_name = "$get_my_user_name";
			$inp_updated_by_user_name = output_html($inp_updated_by_user_name);
			$inp_updated_by_user_name_mysql = quote_smart($link, $inp_updated_by_user_name);

			$inp_updated_by_user_alias = "$get_my_user_alias";
			$inp_updated_by_user_alias = output_html($inp_updated_by_user_alias);
			$inp_updated_by_user_alias_mysql = quote_smart($link, $inp_updated_by_user_alias);

			$inp_updated_by_user_image = "$get_photo_destination";
			$inp_updated_by_user_image = output_html($inp_updated_by_user_image);
			$inp_updated_by_user_image_mysql = quote_smart($link, $inp_updated_by_user_image);

			$inp_updated_by_user_thumb_a_mysql = quote_smart($link, $get_photo_thumb_40);
			$inp_updated_by_user_thumb_b_mysql = quote_smart($link, $get_photo_thumb_50);

			$inp_updated_by_user_email = "$get_my_user_email";
			$inp_updated_by_user_email = output_html($inp_updated_by_user_email);
			$inp_updated_by_user_email_mysql = quote_smart($link, $inp_updated_by_user_email);


			// What has been changed? (t_tasks_history)
			$inp_datetime_saying = date("d. M. Y H:i");	
			
			// History summary
			if(!(isset($inp_assigned_to_user_name))){
				$inp_assigned_to_user_name = "";
				$inp_assigned_to_user_email = "";
			}
			if($inp_assigned_to_user_name == ""){
				$inp_history_summary = "Unassigned";
			}
			else{
				$inp_history_summary = "Assigned to $inp_assigned_to_user_name by $inp_updated_by_user_name";
			}
			$inp_history_summary_mysql = quote_smart($link, $inp_history_summary); 	

			$inp_history_summary_mysql = quote_smart($link, $inp_history_summary);
			mysqli_query($link, "INSERT INTO $t_tasks_history 
			(history_id, history_task_id, history_updated_by_user_id, history_updated_by_user_name, history_updated_by_user_alias, 
			history_updated_by_user_email, history_updated_datetime, history_updated_datetime_saying, history_summary, 
			history_new_assigned_to_user_id, history_new_assigned_to_user_name, 
			history_new_assigned_to_user_alias, history_new_assigned_to_user_image, history_new_assigned_to_user_email) 
			VALUES 
			(NULL, $get_current_task_id, $my_user_id_mysql, $inp_updated_by_user_name_mysql, $inp_updated_by_user_alias_mysql,
			$inp_updated_by_user_email_mysql, '$datetime', '$inp_datetime_saying',  $inp_history_summary_mysql, 
			$inp_assigned_to_user_id_mysql, $inp_assigned_to_user_name_mysql, $inp_assigned_to_user_alias_mysql, 
			$inp_assigned_to_user_image_mysql, $inp_assigned_to_user_email_mysql )")
			or die(mysqli_error($link));

			
			// Email if assigned to new person
			$fm_email = "";
			if($get_current_task_assigned_to_user_id != "$inp_assigned_to_user_id" && $inp_assigned_to_user_email != "" && $inp_assigned_to_user_id != "$get_my_user_id"){
				
				$subject = "$get_current_task_title quick assigned to you by $get_my_user_name | $configWebsiteTitleSav";
				$subject = str_replace('&quot;', '"', $subject);

				$message = "<html>\n";
				$message = $message. "<head>\n";
				$message = $message. "  <title>$subject</title>\n";

				$message = $message. "  <style type=\"text/css\"></style>\n";
				$message = $message. " </head>\n";
				$message = $message. "<body>\n";
				$message = $message. "<p>An assignment has been reassigned to you by <a href=\"$configSiteURLSav/users/view_profile.php?user_id=$get_my_user_id&amp;l=$l\">$get_my_user_alias</a>.</p>\n";
				$message = $message. "<table>\n";
				$message = $message. " <tr>\n";
				$message = $message. "  <td><span>ID:</span></td>\n";
				$message = $message. "  <td><span>$get_current_task_id</span></td>\n";
				$message = $message. " </tr>\n";
				$message = $message. " <tr>\n";
				$message = $message. "  <td><span>Title:</span></td>\n";
				$message = $message. "  <td><span><a href=\"$configControlPanelURLSav/index.php?open=dashboard&amp;page=tasks&amp;action=open_task&amp;task_id=$get_current_task_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_task_title</a></span></td>\n";
				$message = $message. " </tr>\n";
				$message = $message. " <tr>\n";
				$message = $message. "  <td><span>Due:</span></td>\n";
				$message = $message. "  <td><span>$get_current_task_due_translated</span></td>\n";
				$message = $message. " </tr>\n";
				$message = $message. " <tr>\n";
				$message = $message. "  <td><span>Priority:</span></td>\n";
				$message = $message. "  <td><span>$get_current_task_priority_id</span></td>\n";
				$message = $message. " </tr>\n";
				$message = $message. " <tr>\n";
				$message = $message. "  <td><span>Status:</span></td>\n";
				$message = $message. "  <td><span>$get_current_task_status_code_id</span></td>\n";
				$message = $message. " </tr>\n";
				$message = $message. "</table>\n";
				$message = $message. "$get_current_task_text";

				$message = $message. "</body>\n";
				$message = $message. "</html>\n";

				$headers = "MIME-Version: 1.0" . "\r\n" .
		  		  "Content-type: text/html; charset=iso-8859-1" . "\r\n" .
				    "To: $inp_assigned_to_user_email " . "\r\n" .
				    "From: $configFromEmailSav" . "\r\n" .
				    "Reply-To: $configFromEmailSav" . "\r\n" .
				    'X-Mailer: PHP/' . phpversion();
				
				if($configMailSendActiveSav == "1"){
					mail($inp_assigned_to_user_email, $subject, $message, $headers);
				}

				$fm_email = "email_sent_to_" . "$inp_assigned_to_user_email" . "_from_" . $configFromEmailSav;


				// Notification to assigned to user
				$inp_notification_reference_id_text = "task_" . $get_current_task_id;
				$inp_notification_reference_id_text_mysql = quote_smart($link, $inp_notification_reference_id_text);

				$inp_notification_url = "$configControlPanelURLSav/index.php?open=dashboard&page=tasks&action=open_task&task_id=$get_current_task_id&editor_language=$editor_language&l=$l";
				$inp_notification_url_mysql = quote_smart($link, $inp_notification_url);
	
				$inp_notification_text = "Task &quot;$get_current_task_title&quot; has been reassigned to you by $get_my_user_alias";
				$inp_notification_text_mysql = quote_smart($link, $inp_notification_text);

				$week = date("W");
				$datetime_saying = date("j M Y H:i");
				
				// Check if notification already exists, if it does, then delete, then insert
				$query = "SELECT notification_id FROM $t_users_notifications WHERE notification_user_id=$inp_assigned_to_user_id AND notification_reference_id_text=$inp_notification_reference_id_text_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_notification_id) = $row;
				if($get_notification_id != ""){
					$result = mysqli_query($link, "DELETE FROM $t_users_notifications WHERE notification_id=$get_notification_id") or die(mysqli_error($link));
				}

				mysqli_query($link, "INSERT INTO $t_users_notifications
				(notification_id, notification_user_id, notification_reference_id_text, notification_seen, notification_url, notification_text, notification_datetime, notification_datetime_saying, notification_emailed, notification_week) 
				VALUES 
				(NULL, $inp_assigned_to_user_id, $inp_notification_reference_id_text_mysql, 0, $inp_notification_url_mysql, $inp_notification_text_mysql, '$datetime', '$datetime_saying', 0, $week)")
				or die(mysqli_error($link));
			}


	exit;
} // task found
?>