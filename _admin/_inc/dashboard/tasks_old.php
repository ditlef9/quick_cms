<?php
/**
*
* File: _admin/_inc/tasks.php
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




/*- Variables -------------------------------------------------------------------------- */

if(isset($_GET['task_id'])) {
	$task_id = $_GET['task_id'];
	$task_id = strip_tags(stripslashes($task_id));
}
else{
	$task_id = "";
}


if($action == ""){
	if(isset($_GET['status_code_id'])) {
		$status_code_id = $_GET['status_code_id'];
		$status_code_id = strip_tags(stripslashes($status_code_id));
	}
	else{
		$status_code_id = "";
	}
	if(isset($_GET['show_archive'])) {
		$show_archive = $_GET['show_archive'];
		$show_archive = strip_tags(stripslashes($show_archive));
	}
	else{
		$show_archive = "";
	}
	if(isset($_GET['assigned_to_user_id'])) {
		$assigned_to_user_id = $_GET['assigned_to_user_id'];
			$assigned_to_user_id = strip_tags(stripslashes($assigned_to_user_id));
	}
	else{
		$assigned_to_user_id = "";
	}
	echo"
	<h1>Tasks</h1>

	<!-- Menu -->
		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_task&amp;l=$l\" class=\"btn_default\">New task</a>
		<a href=\"index.php?open=$open&amp;page=tasks_projects&amp;l=$l\" class=\"btn_default\">Projects</a>
		<a href=\"index.php?open=$open&amp;page=tasks_systems&amp;l=$l\" class=\"btn_default\">Systems</a>
		<a href=\"index.php?open=dashboard&amp;page=tasks&status_code_id=$status_code_id&amp;assigned_to_user_id=$assigned_to_user_id&amp;show_archive="; if($show_archive == "1"){ echo"0"; } else{ echo"1"; } echo"&amp;l=$l&amp;editor_language=$editor_language\""; if($show_archive == "1"){ echo" style=\"font-weight: bold;\""; } echo" class=\"btn_default\">"; if($show_archive == "1"){ echo"Hide"; } else{ echo"Show"; } echo" archive</a>
		<a href=\"index.php?open=dashboard&amp;page=tasks_subscriptions&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn_default\">Subscriptions</a>
		<a href=\"index.php?open=dashboard&amp;page=tasks_statuses&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn_default\">Statuses</a>
		</p>
	<!-- Menu -->

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

	<!-- Task tabs -->
		<div class=\"clear\" style=\"height: 10px;\"></div>
		<div class=\"tabs\">
			<ul>";

			$query = "SELECT status_code_id, status_code_title, status_code_text_color, status_code_count_tasks FROM $t_tasks_status_codes ORDER BY status_code_weight ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_status_code_id, $get_status_code_title, $get_status_text_code_color, $get_status_code_count_tasks) = $row;
				if($status_code_id == ""){ $status_code_id = $get_status_code_id; }
				echo"				";
				echo"<li><a href=\"index.php?open=$open&amp;page=$page&amp;status_code_id=$get_status_code_id&amp;show_archive=$show_archive&amp;l=$l\""; if($status_code_id == "$get_status_code_id"){ echo" class=\"active\""; } echo">$get_status_code_title ($get_status_code_count_tasks)</a></li>\n";
			}
			echo"
			</ul>
		</div>
		<div class=\"clear\" style=\"height: 20px;\"></div>
		
	<!-- //Task tabs -->

	<!-- Tasks -->
		
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span><b>Assigned to</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>ID</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Task</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Priority</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Hours planned</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Hours used</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Due</b></span>
		   </td>
		  </tr>
		 </thead>
		";

		// Me
		$my_user_id = $_SESSION['user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);
		
		$time = time();	

		$status_code_id_mysql = quote_smart($link, $status_code_id);
		$x = 0;
		$query_tasks = "SELECT task_id, task_system_task_abbr, task_system_incremented_number, task_project_task_abbr, task_project_incremented_number, task_title, task_text, task_status_code_id, task_priority_id, task_created_datetime, task_created_by_user_id, task_created_by_user_alias, task_created_by_user_image, task_created_by_user_email, task_updated_datetime, task_due_datetime, task_due_time, task_due_translated, task_due_warning_sent, task_assigned_to_user_id, task_assigned_to_user_alias, task_assigned_to_user_image, task_assigned_to_user_thumb_40, task_assigned_to_user_email, task_hours_planned, task_hours_used, task_qa_datetime, task_qa_by_user_id, task_qa_by_user_alias, task_qa_by_user_image, task_qa_by_user_email, task_finished_datetime, task_finished_by_user_id, task_finished_by_user_alias, task_finished_by_user_image, task_finished_by_user_email, task_is_archived, task_comments, task_project_id, task_project_part_id, task_system_id, task_system_part_id FROM $t_tasks_index ";
		$query_tasks = $query_tasks . "WHERE task_status_code_id=$status_code_id_mysql";
		if($assigned_to_user_id != "" && is_numeric($assigned_to_user_id)){
			$assigned_to_user_id_mysql = quote_smart($link, $assigned_to_user_id);
			$query_tasks = $query_tasks . " AND task_assigned_to_user_id=$assigned_to_user_id_mysql";
		}
		if($show_archive == "1"){
			$query_tasks = $query_tasks . " AND task_is_archived='1'";
		}
		else{
			$query_tasks = $query_tasks . " AND task_is_archived='0'";
		}

		$query_tasks = $query_tasks . " ORDER BY task_priority_id, task_id DESC";
		$result_tasks = mysqli_query($link, $query_tasks);
		while($row_tasks = mysqli_fetch_row($result_tasks)) {
			list($get_task_id, $get_task_system_task_abbr, $get_task_system_incremented_number, $get_task_project_task_abbr, $get_task_project_incremented_number, $get_task_title, $get_task_text, $get_task_status_code_id, $get_task_priority_id, $get_task_created_datetime, $get_task_created_by_user_id, $get_task_created_by_user_alias, $get_task_created_by_user_image, $get_task_created_by_user_email, $get_task_updated_datetime, $get_task_due_datetime, $get_task_due_time, $get_task_due_translated, $get_task_due_warning_sent, $get_task_assigned_to_user_id, $get_task_assigned_to_user_alias, $get_task_assigned_to_user_image, $get_task_assigned_to_user_thumb_40, $get_task_assigned_to_user_email, $get_task_hours_planned, $get_task_hours_used, $get_task_qa_datetime, $get_task_qa_by_user_id, $get_task_qa_by_user_alias, $get_task_qa_by_user_image, $get_task_qa_by_user_email, $get_task_finished_datetime, $get_task_finished_by_user_id, $get_task_finished_by_user_alias, $get_task_finished_by_user_image, $get_task_finished_by_user_email, $get_task_is_archived, $get_task_comments, $get_task_project_id, $get_task_project_part_id, $get_task_system_id, $get_task_system_part_id) = $row_tasks;
			
			// Style
			if(isset($style) && $style == ""){
				$style = "odd";
			}
			else{
				$style = "";
			}
			if($get_task_priority_id == "1"){
				$style = "danger";
			}
			elseif($get_task_priority_id == "2"){
				$style = "important";
			}

			// Number
			$number = "";
			if($get_task_project_incremented_number == "0" OR $get_task_project_incremented_number == ""){
				if($get_task_system_incremented_number == "0" OR $get_task_system_incremented_number == ""){
					$number = "$get_task_id";
				}
				else{
					$number = "$get_task_system_task_abbr-$get_task_system_incremented_number";
				}
			}
			else{
				$number = "$get_task_project_task_abbr-$get_task_project_incremented_number";
			}

				
			// Read?
			$query_r = "SELECT read_id FROM $t_tasks_read WHERE read_task_id=$get_task_id AND read_user_id=$my_user_id_mysql";
			$result_r = mysqli_query($link, $query_r);
			$row_r = mysqli_fetch_row($result_r);
			list($get_read_id) = $row_r;

			// Due?
			if($get_task_due_warning_sent != "1" && $get_task_due_time < "$time" && $get_task_assigned_to_user_id != "0" && $get_task_finished_by_user_id == ""){
				$result_update = mysqli_query($link, "UPDATE $t_tasks_index SET task_due_warning_sent=1 WHERE task_id=$get_task_id") or die(mysqli_error($link));

				$inp_notification_reference_id_text = "task_" . $get_task_id;
				$inp_notification_reference_id_text_mysql = quote_smart($link, $inp_notification_reference_id_text);

				$inp_notification_url = "$configControlPanelURLSav/index.php?open=dashboard&page=tasks&action=open_task&task_id=$get_task_id&editor_language=$editor_language&l=$l";
				$inp_notification_url_mysql = quote_smart($link, $inp_notification_url);

				$inp_notification_text = "Task &quot;$get_task_title&quot; is due $get_task_due_translated";
				$inp_notification_text_mysql = quote_smart($link, $inp_notification_text);

				$datetime = date("Y-m-d H:i:s");
				$datetime_saying = date("j M Y H:i");
				$week = date("W");

				// Check if notification already exists, if it does, then delete, then insert
				$query = "SELECT notification_id FROM $t_users_notifications WHERE notification_user_id=$get_task_assigned_to_user_id AND notification_reference_id_text=$inp_notification_reference_id_text_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_notification_id) = $row;
				if($get_notification_id != ""){
					$result = mysqli_query($link, "DELETE FROM $t_users_notifications WHERE notification_id=$get_notification_id") or die(mysqli_error($link));
				}
					
				mysqli_query($link, "INSERT INTO $t_users_notifications
				(notification_id, notification_user_id, notification_reference_id_text, notification_seen, notification_url, notification_text, notification_datetime, notification_datetime_saying, notification_emailed, notification_week) 
				VALUES 
				(NULL, $get_task_assigned_to_user_id, $inp_notification_reference_id_text_mysql, 0, $inp_notification_url_mysql, $inp_notification_text_mysql, '$datetime', '$datetime_saying', 0, $week)")
				or die(mysqli_error($link));
			}
		
			echo"
			 <tr>
			  <td class=\"$style\">
				<table>
				  <tr>
				   <td style=\"padding: 0px 6px 0px 0px;\">";
				// Assigned to image
				if($get_task_assigned_to_user_id == "" OR $get_task_assigned_to_user_id == "0"){
					// echo"<a href=\"index.php?open=$open&amp;page=$page&amp;task_id=$get_task_id&amp;status_code_id=$get_task_status_code_id&amp;assigned_to_user_id=$get_task_assigned_to_user_id&amp;show_archive=$show_archive&amp;l=$l&amp;editor_language=$editor_language\"><img src=\"_inc/dashboard/_img/avatar_none_40.png\" alt=\"avatar_blank_40.png\" /></a>";
				}
				else{
					if($get_task_assigned_to_user_thumb_40 != "" && file_exists("../_uploads/users/images/$get_task_assigned_to_user_id/$get_task_assigned_to_user_thumb_40")){
						echo"
						<a href=\"index.php?open=$open&amp;page=$page&amp;task_id=$get_task_id&amp;status_code_id=$get_task_status_code_id&amp;assigned_to_user_id=$get_task_assigned_to_user_id&amp;show_archive=$show_archive&amp;l=$l&amp;editor_language=$editor_language\"><img src=\"../_uploads/users/images/$get_task_assigned_to_user_id/$get_task_assigned_to_user_thumb_40\" alt=\"../$get_task_assigned_to_user_thumb_40/_uploads/users/images/$get_task_assigned_to_user_id/$get_task_assigned_to_user_thumb_40\" /></a>
						";
					}
					else{
						echo"
						<a href=\"index.php?open=$open&amp;page=$page&amp;task_id=$get_task_id&amp;status_code_id=$get_task_status_code_id&amp;assigned_to_user_id=$get_task_assigned_to_user_id&amp;show_archive=$show_archive&amp;l=$l&amp;editor_language=$editor_language\"><img src=\"_inc/dashboard/_img/avatar_blank_40.png\" alt=\"avatar_blank_40.png\" /></a>
						";
					}

				}
				echo"
				   </td>
				   <td>
					<span>
					<a href=\"index.php?open=$open&amp;page=$page&amp;task_id=$get_task_id&amp;status_code_id=$get_task_status_code_id&amp;assigned_to_user_id=$get_task_assigned_to_user_id&amp;show_archive=$show_archive&amp;l=$l&amp;editor_language=$editor_language\">$get_task_assigned_to_user_alias</a>
					</span>
				   </td>
				  </tr>
				</table>
			  </td>
			  <td class=\"$style\">
				<a id=\"#task$get_task_id\"></a>
				<span>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_task&amp;task_id=$get_task_id&amp;l=$l&amp;editor_language=$editor_language\""; if($get_read_id == ""){ echo" style=\"font-weight: bold;\""; } echo">$number</a>
				</span>
			  </td>
			  <td class=\"$style\">
				<span>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_task&amp;task_id=$get_task_id&amp;l=$l&amp;editor_language=$editor_language\""; if($get_read_id == ""){ echo" style=\"font-weight: bold;\""; } echo">$get_task_title</a>
				</span>
			  </td>
			  <td class=\"$style\">
				<span>";
				if($get_task_priority_id == "1"){
					echo"Immediate Priority";
				}
				elseif($get_task_priority_id == "2"){
					echo"High Priority";
				}
				elseif($get_task_priority_id == "3"){
					echo"Normal Priority";
				}
				elseif($get_task_priority_id == "4"){
					echo"Low Priority";
				}
				elseif($get_task_priority_id == "5"){
					echo"Non-attendance";
				}
				echo"
				</span>
			  </td>
			  <td class=\"$style\">
				<span>
				$get_task_hours_planned 
				</span>
			  </td>
			  <td class=\"$style\">
				<span>
				$get_task_hours_used
				</span>
			  </td>
			  <td class=\"$style\">
				<span>
				$get_task_due_translated
				</span>
			  </td>
			 </tr>";

			$x++;
		}
		
		// Check that counter for status is correct		
		if($show_archive != "1" && $assigned_to_user_id == ""){

			$query_r = "SELECT status_code_id, status_code_count_tasks FROM $t_tasks_status_codes WHERE status_code_id=$status_code_id_mysql ";
			$result_r = mysqli_query($link, $query_r);
			$row_r = mysqli_fetch_row($result_r);
			list($get_status_code_id, $get_status_code_count_tasks) = $row_r;	

			if($x != $get_status_code_count_tasks){
				$result_update = mysqli_query($link, "UPDATE $t_tasks_status_codes SET status_code_count_tasks=$x WHERE status_code_id=$get_status_code_id");


				echo"<div class=\"info\"><p>Updated counter of status codes to $x.</p></div>\n";
			}
		}
		echo"
			</table>
		  </td>
		 </tr>
		</table>
	<!-- //Tasks -->

	";
}
elseif($action == "new_task"){
	if($process == "1"){

		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

		$inp_text = $_POST['inp_text'];

		$inp_status_code_id = $_POST['inp_status_code_id'];
		$inp_status_code_id = output_html($inp_status_code_id);
		$inp_status_code_id_mysql = quote_smart($link, $inp_status_code_id);

		
		// Update status_code_count_tasks
		$query = "SELECT status_code_id, status_code_title, status_code_count_tasks FROM $t_tasks_status_codes WHERE status_code_id=$inp_status_code_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_status_code_id, $get_status_code_title, $get_status_code_count_tasks) = $row;
		
		// Update new status code with +1
		$inp_status_code_count_tasks = $get_status_code_count_tasks+1;
		$result = mysqli_query($link, "UPDATE $t_tasks_status_codes SET status_code_count_tasks='$inp_status_code_count_tasks' WHERE status_code_id=$get_status_code_id");
	
		$inp_status_code_title_mysql = quote_smart($link, $get_status_code_title);

		$inp_priority_id = $_POST['inp_priority_id'];
		$inp_priority_id = output_html($inp_priority_id);
		$inp_priority_id_mysql = quote_smart($link, $inp_priority_id);
			
		$inp_due_day = $_POST['inp_due_day'];
		$inp_due_month = $_POST['inp_due_month'];
		$inp_due_year = $_POST['inp_due_year'];
		$inp_due_datetime = $inp_due_year . "-" . $inp_due_month . "-" . $inp_due_day . " 23:00:00";
		$inp_due_datetime = output_html($inp_due_datetime);
		$inp_due_datetime_mysql = quote_smart($link, $inp_due_datetime);

		$inp_due_time = strtotime($inp_due_datetime);
		$inp_due_time_mysql = quote_smart($link, $inp_due_time);

		$inp_due_translated = "$inp_due_day";
		if($inp_due_month == "1" OR $inp_due_month == "01"){
				$inp_due_translated = $inp_due_translated . " $l_january";
		}
			elseif($inp_due_month == "2" OR $inp_due_month == "02"){
				$inp_due_translated = $inp_due_translated . " $l_february";
		}
			elseif($inp_due_month == "3" OR $inp_due_month == "03"){
				$inp_due_translated = $inp_due_translated . " $l_march";
		}
			elseif($inp_due_month == "4" OR $inp_due_month == "04"){
				$inp_due_translated = $inp_due_translated . " $l_april";
			}
			elseif($inp_due_month == "5" OR $inp_due_month == "05"){
				$inp_due_translated = $inp_due_translated . " $l_may";
			}
			elseif($inp_due_month == "6" OR $inp_due_month == "06"){
				$inp_due_translated = $inp_due_translated . " $l_june";
			}
			elseif($inp_due_month == "7" OR $inp_due_month == "07"){
				$inp_due_translated = $inp_due_translated . " $l_juli";
			}
			elseif($inp_due_month == "8" OR $inp_due_month == "08"){
				$inp_due_translated = $inp_due_translated . " $l_august";
			}
			elseif($inp_due_month == "9" OR $inp_due_month == "09"){
				$inp_due_translated = $inp_due_translated . " $l_september";
			}
			elseif($inp_due_month == "10"){
				$inp_due_translated = $inp_due_translated . " $l_october";
			}
			elseif($inp_due_month == "11"){
				$inp_due_translated = $inp_due_translated . " $l_november";
		}
		elseif($inp_due_month == "12"){
				$inp_due_translated = $inp_due_translated . " $l_december";
		}
		$inp_due_translated = $inp_due_translated . " $inp_due_year";
		$inp_due_translated = output_html($inp_due_translated);
		$inp_due_translated_mysql = quote_smart($link, $inp_due_translated);



		// Assigned to
		$inp_assigned_to_user_alias = $_POST['inp_assigned_to_user_alias'];
		$inp_assigned_to_user_alias = output_html($inp_assigned_to_user_alias);
		$inp_assigned_to_user_alias_mysql = quote_smart($link, $inp_assigned_to_user_alias);
	
		$query = "SELECT user_id, user_email, user_name, user_alias FROM $t_users WHERE user_alias=$inp_assigned_to_user_alias_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_user_id, $get_user_email, $get_user_name, $get_user_alias) = $row;

			if($get_user_id == ""){
				$get_user_id = 0;
			}
			$inp_assigned_to_user_id = $get_user_id;
			$inp_assigned_to_user_id = output_html($inp_assigned_to_user_id);
			$inp_assigned_to_user_id_mysql = quote_smart($link, $inp_assigned_to_user_id);

			$inp_assigned_to_user_name = "$get_user_name";
			$inp_assigned_to_user_name = output_html($inp_assigned_to_user_name);
			$inp_assigned_to_user_name_mysql = quote_smart($link, $inp_assigned_to_user_name);

			$inp_assigned_to_user_email = "$get_user_email";
			$inp_assigned_to_user_email = output_html($inp_assigned_to_user_email);
			$inp_assigned_to_user_email_mysql = quote_smart($link, $inp_assigned_to_user_email);

			// Get assigned to photo
			$query = "SELECT photo_id, photo_destination, photo_thumb_40, photo_thumb_50 FROM $t_users_profile_photo WHERE photo_user_id=$inp_assigned_to_user_id_mysql AND photo_profile_image='1'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_photo_id, $get_photo_destination, $get_photo_thumb_40, $get_photo_thumb_50) = $row;

			$inp_assigned_to_user_image_mysql = quote_smart($link, $get_photo_destination);
			$inp_assigned_to_user_thumb_a_mysql = quote_smart($link, $get_photo_thumb_40);
			$inp_assigned_to_user_thumb_b_mysql = quote_smart($link, $get_photo_thumb_50);

		// System id
		$inp_system_id = $_POST['inp_system_id'];
		$inp_system_id = output_html($inp_system_id);
		$inp_system_id_mysql = quote_smart($link, $inp_system_id);
			
			if($inp_system_id != "0"){

				$query = "SELECT system_id, system_title, system_task_abbr, system_description, system_logo, system_is_active, system_increment_tasks_counter, system_created, system_updated FROM $t_tasks_systems WHERE system_id=$inp_system_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_system_id, $get_system_title, $get_system_task_abbr, $get_system_description, $get_system_logo, $get_system_is_active, $get_system_increment_tasks_counter, $get_system_created, $get_system_updated) = $row;
			}
			else{
				// Pick random system
				$query = "SELECT system_id, system_title, system_task_abbr, system_description, system_logo, system_is_active, system_increment_tasks_counter, system_created, system_updated FROM $t_tasks_systems LIMIT 0,1";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_system_id, $get_system_title, $get_system_task_abbr, $get_system_description, $get_system_logo, $get_system_is_active, $get_system_increment_tasks_counter, $get_system_created, $get_system_updated) = $row;
				$inp_system_id = "$get_system_id";
				$inp_system_id_mysql = quote_smart($link, $inp_system_id);

			}

			$inp_system_title_mysql = quote_smart($link, $get_system_title);
			if($get_system_id == ""){
				$inp_system_task_abbr_mysql = quote_smart($link, "");
				$inp_system_increment_tasks_counter_mysql = quote_smart($link, "0");
			}
			else{
				// Update increment tasks counter
				$inp_system_task_abbr_mysql = quote_smart($link, $get_system_task_abbr);
				$inp_system_increment_tasks_counter_mysql = quote_smart($link, $get_system_increment_tasks_counter);

				// Update counter
				$inp_increment_tasks_counter = $get_system_increment_tasks_counter+1;

				$result = mysqli_query($link, "UPDATE $t_tasks_systems SET system_increment_tasks_counter=$inp_increment_tasks_counter WHERE system_id=$get_system_id") or die(mysqli_error($link));
			}
			

		// Insert last used system
		$query = "SELECT last_used_system_id, last_used_system_user_id, last_used_system_system_id FROM $t_tasks_last_used_systems WHERE last_used_system_user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_last_used_system_id, $get_last_used_system_user_id, $get_last_used_system_system_id) = $row;
		if($get_last_used_system_id == ""){
				// No last used system
				mysqli_query($link, "INSERT INTO $t_tasks_last_used_systems 
				(last_used_system_id, last_used_system_user_id, last_used_system_system_id) 
				VALUES 
				(NULL, $my_user_id_mysql, $inp_system_id_mysql)")
				or die(mysqli_error($link));
		}
		else{
			$result = mysqli_query($link, "UPDATE $t_tasks_last_used_systems SET last_used_system_system_id=$inp_system_id_mysql WHERE last_used_system_id=$get_last_used_system_id") or die(mysqli_error($link));
		}

			// Project id
			$inp_project_id = $_POST['inp_project_id'];
			$inp_project_id = output_html($inp_project_id);
			$inp_project_id_mysql = quote_smart($link, $inp_project_id);

			$query = "SELECT project_id, project_system_id, project_title, project_task_abbr, project_description, project_logo, project_is_active, project_increment_tasks_counter, project_created, project_updated FROM $t_tasks_projects WHERE project_id=$inp_project_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_project_id, $get_project_system_id, $get_project_title, $get_project_task_abbr, $get_project_description, $get_project_logo, $get_project_is_active, $get_project_increment_tasks_counter, $get_project_created, $get_project_updated ) = $row;

			$inp_project_title_mysql = quote_smart($link, $get_project_title);

			if($get_project_id == ""){
				$inp_project_task_abbr_mysql = quote_smart($link, "");
				$inp_project_increment_tasks_counter_mysql = quote_smart($link, "0");
			}
			else{
				// Update increment tasks counter
				$inp_project_task_abbr_mysql = quote_smart($link, $get_project_task_abbr);
				if($get_project_increment_tasks_counter == ""){ $get_project_increment_tasks_counter = "0"; } 
				$inp_project_increment_tasks_counter_mysql = quote_smart($link, $get_project_increment_tasks_counter);

				// Update counter
				$inp_project_increment_tasks_counter = $get_project_increment_tasks_counter+1;
				$result = mysqli_query($link, "UPDATE $t_tasks_projects SET project_increment_tasks_counter=$inp_project_increment_tasks_counter WHERE project_id=$get_project_id") or die(mysqli_error($link));
			}

			// Insert last used project
			$query = "SELECT last_used_project_id, last_used_project_user_id, last_used_project_project_id FROM $t_tasks_last_used_projects WHERE last_used_project_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_last_used_project_id, $get_last_used_project_user_id, $get_last_used_project_project_id) = $row;
			if($get_last_used_project_id == ""){
				// No last used system
				mysqli_query($link, "INSERT INTO $t_tasks_last_used_projects 
				(last_used_project_id, last_used_project_user_id, last_used_project_project_id) 
				VALUES 
				(NULL, $my_user_id_mysql, $inp_project_id_mysql)")
				or die(mysqli_error($link));
			}
			else{
				$result = mysqli_query($link, "UPDATE $t_tasks_last_used_projects SET last_used_project_project_id=$inp_project_id_mysql WHERE last_used_project_id=$get_last_used_project_id") or die(mysqli_error($link));

			}

		
			// Updated
			$datetime = date("Y-m-d H:i:s");


			$inp_updated_translated = date("d");
			$month = date("m");
			$year = date("Y");
			if($month == "01"){
				$inp_updated_translated = $inp_updated_translated . " $l_january";
			}
			elseif($month == "2"){
				$inp_updated_translated = $inp_updated_translated . " $l_february";
			}
			elseif($month == "03"){
				$inp_updated_translated = $inp_updated_translated . " $l_march";
			}
			elseif($month == "04"){
				$inp_updated_translated = $inp_updated_translated . " $l_april";
			}
			elseif($month == "05"){
				$inp_updated_translated = $inp_updated_translated . " $l_may";
			}
			elseif($month == "06"){
				$inp_updated_translated = $inp_updated_translated . " $l_june";
			}
			elseif($month == "07"){
				$inp_updated_translated = $inp_updated_translated . " $l_juli";
			}
			elseif($month == "08"){
				$inp_updated_translated = $inp_updated_translated . " $l_august";
			}
			elseif($month == "09"){
				$inp_updated_translated = $inp_updated_translated . " $l_september";
			}
			elseif($month == "10"){
				$inp_updated_translated = $inp_updated_translated . " $l_october";
			}
			elseif($month == "11"){
				$inp_updated_translated = $inp_updated_translated . " $l_november";
			}
			elseif($month == "12"){
				$inp_updated_translated = $inp_updated_translated . " $l_december";
			}
			$inp_updated_translated = $inp_updated_translated . " $year";
			$inp_updated_translated = output_html($inp_updated_translated);
			$inp_updated_translated_mysql = quote_smart($link, $inp_updated_translated);

			// Hours planned
			$inp_hours_planned = $_POST['inp_hours_planned'];
			$inp_hours_planned = output_html($inp_hours_planned);
			$inp_hours_planned = str_replace(",", ".", $inp_hours_planned);
			if($inp_hours_planned == ""){
				$inp_hours_planned = "0";
			}
			$inp_hours_planned_mysql = quote_smart($link, $inp_hours_planned);

			// Hours used
			$inp_hours_used = "0";
			$inp_hours_used_mysql = quote_smart($link, $inp_hours_used);

			// Diff
			$inp_hours_diff_number = $inp_hours_used - $inp_hours_planned ;
			$inp_hours_diff_number_mysql = quote_smart($link, $inp_hours_diff_number);

			if($inp_hours_planned == "0"){
				$inp_hours_diff_percentage = 0;
			}
			else{
				$inp_hours_diff_percentage = ($inp_hours_used / $inp_hours_planned) * 100;
			}
			$inp_hours_diff_percentage = round($inp_hours_diff_percentage);
			$inp_hours_diff_percentage_mysql = quote_smart($link, $inp_hours_diff_percentage);

		// me
		$my_user_id = $_SESSION['user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);

		$query = "SELECT user_id, user_email, user_name, user_alias FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias) = $row;

		$query = "SELECT photo_id, photo_destination, photo_thumb_40, photo_thumb_50 FROM $t_users_profile_photo WHERE photo_user_id=$my_user_id_mysql AND photo_profile_image='1'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_40, $get_my_photo_thumb_50) = $row;

		// Created by
		$inp_created_by_user_id = "$get_my_user_id";
		$inp_created_by_user_id = output_html($inp_created_by_user_id);
		$inp_created_by_user_id_mysql = quote_smart($link, $inp_created_by_user_id);

		$inp_created_by_user_name = "$get_my_user_name";
		$inp_created_by_user_name = output_html($inp_created_by_user_name);
		$inp_created_by_user_name_mysql = quote_smart($link, $inp_created_by_user_name);

		$inp_created_by_user_alias = "$get_my_user_alias";
		$inp_created_by_user_alias = output_html($inp_created_by_user_alias);
		$inp_created_by_user_alias_mysql = quote_smart($link, $inp_created_by_user_alias);

		$inp_created_by_user_image = "$get_photo_destination";
		$inp_created_by_user_image = output_html($inp_created_by_user_image);
		$inp_created_by_user_image_mysql = quote_smart($link, $inp_created_by_user_image);

		$inp_created_by_user_thumb_a_mysql = quote_smart($link, $get_my_photo_thumb_40);
		$inp_created_by_user_thumb_b_mysql = quote_smart($link, $get_my_photo_thumb_50);

		$inp_created_by_user_email = "$get_my_user_email";
		$inp_created_by_user_email = output_html($inp_created_by_user_email);
		$inp_created_by_user_email_mysql = quote_smart($link, $inp_created_by_user_email);

		// Created and updated
		$datetime = date("Y-m-d H:i:s");

		$inp_created_translated = date("d");
		$month = date("m");
		$year = date("Y");
		if($month == "01"){
			$inp_created_translated = $inp_created_translated . " $l_january";
		}
		elseif($month == "02"){
			$inp_created_translated = $inp_created_translated . " $l_february";
		}
		elseif($month == "03"){
			$inp_created_translated = $inp_created_translated . " $l_march";
		}
		elseif($month == "04"){
			$inp_created_translated = $inp_created_translated . " $l_april";
		}
		elseif($month == "05"){
			$inp_created_translated = $inp_created_translated . " $l_may";
		}
		elseif($month == "06"){
			$inp_created_translated = $inp_created_translated . " $l_june";
		}
		elseif($month == "07"){
			$inp_created_translated = $inp_created_translated . " $l_juli";
		}
		elseif($month == "08"){
			$inp_created_translated = $inp_created_translated . " $l_august";
		}
		elseif($month == "09"){
			$inp_created_translated = $inp_created_translated . " $l_september";
		}
		elseif($month == "10"){
			$inp_created_translated = $inp_created_translated . " $l_october";
		}
		elseif($month == "11"){
			$inp_created_translated = $inp_created_translated . " $l_november";
		}
		elseif($month == "12"){
			$inp_created_translated = $inp_created_translated . " $l_december";
		}
		$inp_created_translated = $inp_created_translated . " $year";
		$inp_created_translated = output_html($inp_created_translated);
		$inp_created_translated_mysql = quote_smart($link, $inp_created_translated);


		// Insert
		mysqli_query($link, "INSERT INTO $t_tasks_index
		(task_id, task_system_task_abbr, task_system_incremented_number, task_project_task_abbr, task_project_incremented_number, 
		task_title, task_text, task_status_code_id, 
		task_status_code_title, task_priority_id, task_created_datetime, task_created_translated, task_created_by_user_id, 
		task_created_by_user_name, task_created_by_user_alias, task_created_by_user_image, task_created_by_user_thumb_40, task_created_by_user_thumb_50, 
		task_created_by_user_email, task_system_id, task_system_title, task_system_part_id, task_system_part_title, 
		task_project_id, task_project_title, task_project_part_id, task_project_part_title, task_updated_datetime, 

		task_updated_translated, task_due_datetime, task_due_time, task_due_translated, task_due_warning_sent, 
		task_assigned_to_user_id, task_assigned_to_user_name, task_assigned_to_user_alias, task_assigned_to_user_image, task_assigned_to_user_thumb_40, 
		task_assigned_to_user_thumb_50, task_assigned_to_user_email, task_hours_planned, task_is_archived) 
		VALUES 
		(NULL, $inp_system_task_abbr_mysql, $inp_system_increment_tasks_counter_mysql, $inp_project_task_abbr_mysql, $inp_project_increment_tasks_counter_mysql, 
		$inp_title_mysql, '', $inp_status_code_id_mysql, 
		$inp_status_code_title_mysql, $inp_priority_id_mysql, '$datetime', $inp_created_translated_mysql, $inp_created_by_user_id_mysql, 
		$inp_created_by_user_name_mysql,$inp_created_by_user_alias_mysql, $inp_created_by_user_image_mysql, $inp_created_by_user_thumb_a_mysql, $inp_created_by_user_thumb_b_mysql, 
		$inp_created_by_user_email_mysql, $inp_system_id_mysql, $inp_system_title_mysql, 0, '', 
		$inp_project_id_mysql, $inp_project_title_mysql, 0, '', '$datetime',
		$inp_created_translated_mysql, $inp_due_datetime_mysql, $inp_due_time_mysql, $inp_due_translated_mysql, 0,
		$inp_assigned_to_user_id_mysql, $inp_assigned_to_user_name_mysql, $inp_assigned_to_user_alias_mysql, $inp_assigned_to_user_image_mysql, $inp_assigned_to_user_thumb_a_mysql, 
		$inp_assigned_to_user_thumb_b_mysql, $inp_assigned_to_user_email_mysql, $inp_hours_planned_mysql, 0)")
		or die(mysqli_error($link));


		// Get ID
		$query = "SELECT task_id FROM $t_tasks_index WHERE task_created_datetime='$datetime'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_task_id) = $row;



		// Text
		$sql = "UPDATE $t_tasks_index SET task_text=? WHERE task_id='$get_current_task_id'";
		$stmt = $link->prepare($sql);
		$stmt->bind_param("s", $inp_text);
		$stmt->execute();
		if ($stmt->errno) {
			echo "FAILURE!!! " . $stmt->error; die;
		}



			
		// Email if assigned to other than me
		$fm_email = "";
		if($inp_assigned_to_user_id != "$get_my_user_id"){
				
			$subject = "New task $inp_title created and assigned to you | $configWebsiteTitleSav";
			$subject = str_replace('&quot;', '"', $subject);

			$message = "<html>\n";
			$message = $message. "<head>\n";
			$message = $message. "  <title>$subject</title>\n";

			$message = $message. "  <style type=\"text/css\">\n";
			$message = $message. "  tr td:first-child {\n";
			$message = $message. "      width: 1%;\n";
			$message = $message. "      white-space: nowrap;\n";
			$message = $message. "  }\n";
			$message = $message. "  </style>\n";

			$message = $message. " </head>\n";
				$message = $message. "<body>\n";
				$message = $message. "<p>An assignment has been reassigned to you by <a href=\"$configSiteURLSav/users/view_profile.php?user_id=$get_my_user_id&amp;l=$l\">$get_my_user_alias</a>.</p>\n";
				$message = $message. "<table style='width: 100%'>\n";
				$message = $message. " <tr>\n";
				$message = $message. "  <td><span>ID:</span></td>\n";
				$message = $message. "  <td><span>$get_current_task_id</span></td>\n";
				$message = $message. " </tr>\n";
				$message = $message. " <tr>\n";
				$message = $message. "  <td><span>Title:</span></td>\n";
				$message = $message. "  <td><span><a href=\"$configControlPanelURLSav/index.php?open=dashboard&amp;page=tasks&amp;action=open_task&amp;task_id=$get_current_task_id&amp;editor_language=$editor_language&amp;l=$l\">$inp_title</a></span></td>\n";
				$message = $message. " </tr>\n";
				$message = $message. " <tr>\n";
				$message = $message. "  <td><span>Due:</span></td>\n";
				$message = $message. "  <td><span>$inp_due_translated</span></td>\n";
				$message = $message. " </tr>\n";
				$message = $message. " <tr>\n";
				$message = $message. "  <td><span>Priority:</span></td>\n";
				$message = $message. "  <td><span>$inp_priority_id</span></td>\n";
				$message = $message. " </tr>\n";
				$message = $message. " <tr>\n";
				$message = $message. "  <td><span>Status:</span></td>\n";
				$message = $message. "  <td><span>$inp_status_code_id</span></td>\n";
				$message = $message. " </tr>\n";
				$message = $message. "</table>\n";
			$message = $message. "$inp_text";

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

			// $myfile = fopen("../_cache/mail_debug.html", "w") or die("Unable to open file!");
			// fwrite($myfile, $message);
			// fclose($myfile);

			// Notification to assigned to user
			$inp_notification_reference_id_text = "task_" . $get_current_task_id;
			$inp_notification_reference_id_text_mysql = quote_smart($link, $inp_notification_reference_id_text);

			$inp_notification_url = "$configControlPanelURLSav/index.php?open=dashboard&page=tasks&action=open_task&task_id=$get_current_task_id&editor_language=$editor_language&l=$l";
			$inp_notification_url_mysql = quote_smart($link, $inp_notification_url);
	
			$inp_notification_text = "Task &quot;$inp_title&quot; has been reassigned to you by $get_my_user_alias";
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
		} // email

		$url = "index.php?open=dashboard&page=tasks&action=open_task&task_id=$get_current_task_id&l=$l&editor_language=$editor_language";
		header("Location: $url");
		exit;
	
	} // process (create new task

	// Me
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;
	$date_saying = date("j M Y");
	echo"
	<h1>New task</h1>

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_task&amp;l=$l\">New task</a>
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


	<!-- Focus -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_title\"]').focus();
		});
		</script>
	<!-- //Focus -->

	<!-- TinyMCE -->
		<script type=\"text/javascript\" src=\"_javascripts/tinymce/tinymce.min.js\"></script>
				<script>
				tinymce.init({
					selector: 'textarea.editor',
					plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern help',
					toolbar: 'formatselect | bold italic strikethrough forecolor backcolor permanentpen formatpainter | link image media pageembed | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment',
					image_advtab: true,
					content_css: [
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

	<!-- Edit task form -->
		<form method=\"post\" action=\"index.php?open=dashboard&amp;page=$page&amp;action=$action&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
		<p>Title:<br />
			<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" style=\"width: 99%;\" />
		</p>


		<p>Text:<br /><textarea name=\"inp_text\" rows=\"10\" cols=\"80\" class=\"editor\"></textarea><br />
			</p>


		<p>Status:<br />
		<select name=\"inp_status_code_id\">";
			$query = "SELECT status_code_id, status_code_title FROM $t_tasks_status_codes ORDER BY status_code_weight ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
			list($get_status_code_id, $get_status_code_title) = $row;
				echo"			<option value=\"$get_status_code_id\">$get_status_code_title</option>\n";
			}
			echo"
		</select>
		</p>

		<p>Assign to:<br />
		<input type=\"text\" name=\"inp_assigned_to_user_alias\" value=\"$get_my_user_name\" size=\"25\" id=\"assigned_to_user_alias_search_query\" autocomplete=\"off\" />
		</p>
  		<div id=\"assigned_to_user_alias_search_results\"></div>

			<!-- Assign to search script -->
			<script id=\"source\" language=\"javascript\" type=\"text/javascript\">
			\$(document).ready(function () {
				\$('#assigned_to_user_alias_search_query').keyup(function () {
        				// getting the value that user typed
        				var searchString    = \$(\"#assigned_to_user_alias_search_query\").val();
        				// forming the queryString
       					var data            = 'inp_search_query='+ searchString;
         
        				// if searchString is not empty
        				if(searchString) {
           					// ajax call
            					\$.ajax({
                					type: \"POST\",
               						url: \"_inc/dashboard/tasks_search_for_user.php\",
                					data: data,
							beforeSend: function(html) { // this happens before actual call
								\$(\"#assigned_to_user_alias_search_results\").html(''); 
							},
               						success: function(html){
                    						\$(\"#assigned_to_user_alias_search_results\").append(html);
              						}
            					});
       					}
        				return false;
            			});
            		});
		</script>
		<!-- //Assign to search script -->
	
		<p>Priority:<br />
		<select name=\"inp_priority_id\">
			<option value=\"1\">Immediate Priority</option>
			<option value=\"2\">High Priority</option>
			<option value=\"3\" selected=\"selected\">Normal Priority</option>
			<option value=\"4\">Low Priority</option>
			<option value=\"5\">Non-attendance</option>
		</select>
		</p>

		";
		// Due
		$inp_due_day = date("d");

		$month = date("m");
		$next_month = $month+1;
		if($next_month == "13"){
			$next_month = "01";
		}
		$inp_due_month = "$next_month";
		$inp_due_month_lenght = strlen($inp_due_month);
		if($inp_due_month_lenght == "1"){
			$inp_due_month = "0" . $inp_due_month;
		}
		if($inp_due_day > 28){
			$inp_due_day = "27";
		}
		$inp_due_year = date("Y");

		echo"
		<p>Due:<br />
		<select name=\"inp_due_day\">
			<option value=\"\">- Day -</option>\n";
			for($x=1;$x<32;$x++){
				if($x<10){
					$y = 0 . $x;
				}
				else{
					$y = $x;
				}
				echo"<option value=\"$y\""; if($inp_due_day == "$y"){ echo" selected=\"selected\""; } echo">$x</option>\n";
			}
			echo"
		</select>

		<select name=\"inp_due_month\">
			<option value=\"\">- Month -</option>\n";

			$due_month = substr($get_current_task_due_datetime, 5, 2); 

			$month = date("m");
			$l_month_array[0] = "";
			$l_month_array[1] = "$l_january";
			$l_month_array[2] = "$l_february";
			$l_month_array[3] = "$l_march";
			$l_month_array[4] = "$l_april";
			$l_month_array[5] = "$l_may";
			$l_month_array[6] = "$l_june";
			$l_month_array[7] = "$l_juli";
			$l_month_array[8] = "$l_august";
			$l_month_array[9] = "$l_september";
			$l_month_array[10] = "$l_october";
			$l_month_array[11] = "$l_november";
			$l_month_array[12] = "$l_december";
			for($x=1;$x<13;$x++){
				if($x<10){
					$y = 0 . $x;
				}
				else{
					$y = $x;
				}
				echo"<option value=\"$y\""; if($inp_due_month == "$x"){ echo" selected=\"selected\""; } echo">$l_month_array[$x]</option>\n";
			}
		echo"
		</select>

		<select name=\"inp_due_year\">
		<option value=\"\">- Year -</option>\n";
			$due_year = substr($get_current_task_due_datetime, 0, 4); 
			$year = date("Y");
			for($x=0;$x<150;$x++){
				echo"<option value=\"$year\""; if($inp_due_year == "$year"){ echo" selected=\"selected\""; } echo">$year</option>\n";
				$year = $year-1;

			}
			echo"
		</select>
		</p>



		<p>System: <a href=\"index.php?open=$open&amp;page=tasks_systems&amp;action=new_system&amp;l=$l\" target=\"_blank\">New</a><br />
		<select name=\"inp_system_id\">
			<option value=\"0\">None</option>\n";


		$query = "SELECT last_used_system_id, last_used_system_user_id, last_used_system_system_id FROM $t_tasks_last_used_systems WHERE last_used_system_user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_last_used_system_id, $get_last_used_system_user_id, $get_last_used_system_system_id) = $row;

		$x = 0;
		$query = "SELECT system_id, system_title FROM $t_tasks_systems WHERE system_is_active=1 ORDER BY system_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_system_id, $get_system_title) = $row;
			echo"			<option value=\"$get_system_id\""; if($get_system_id == "$get_last_used_system_id"){ echo" selected=\"selected\""; } echo">$get_system_title</option>\n";
		}
		echo"
		</select>
		</p>

		<p>Project: <a href=\"index.php?open=$open&amp;page=tasks_projects&amp;action=new_project&amp;l=$l\" target=\"_blank\">New</a><br />
		<select name=\"inp_project_id\">
			<option value=\"0\">None</option>\n";


		$query = "SELECT last_used_project_id, last_used_project_user_id, last_used_project_project_id FROM $t_tasks_last_used_projects WHERE last_used_project_user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_last_used_project_id, $get_last_used_project_user_id, $get_last_used_project_project_id) = $row;


		$query = "SELECT project_id, project_title FROM $t_tasks_projects WHERE project_is_active=1 ORDER BY project_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_project_id, $get_project_title) = $row;
			echo"			<option value=\"$get_project_id\""; if($get_project_id == "$get_last_used_project_id"){ echo" selected=\"selected\""; } echo">$get_project_title</option>\n";
		}
		echo"
		</select>
		</p>

		<p>Hours expected:<br />
		<input type=\"text\" name=\"inp_hours_planned\" value=\"\" size=\"5\" />
		</p>

		<p><input type=\"submit\" value=\"Create task\" class=\"btn\" /></p>

		</form>
	<!-- //New task form -->
	";
	
} // new_task
elseif($action == "open_task"){
	// Get task
	$task_id_mysql = quote_smart($link, $task_id);
	$query = "SELECT task_id, task_system_task_abbr, task_system_incremented_number, task_project_task_abbr, task_project_incremented_number, task_title, task_text, task_status_code_id, task_priority_id, task_created_datetime, task_created_translated,  task_created_by_user_id, task_created_by_user_alias, task_created_by_user_image, task_created_by_user_email, task_updated_datetime, task_updated_translated, task_due_datetime, task_due_time, task_due_translated, task_assigned_to_user_id, task_assigned_to_user_alias, task_assigned_to_user_image, task_assigned_to_user_email, task_hours_planned, task_hours_used, task_hours_diff_number, task_hours_diff_percentage, task_qa_datetime, task_qa_by_user_id, task_qa_by_user_alias, task_qa_by_user_image, task_qa_by_user_email, task_finished_datetime, task_finished_by_user_id, task_finished_by_user_alias, task_finished_by_user_image, task_finished_by_user_email, task_is_archived, task_comments, task_project_id, task_project_part_id, task_system_id, task_system_part_id FROM $t_tasks_index WHERE task_id=$task_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_task_id, $get_current_task_system_task_abbr, $get_current_task_system_incremented_number, $get_current_task_project_task_abbr, $get_current_task_project_incremented_number, $get_current_task_title, $get_current_task_text, $get_current_task_status_code_id, $get_current_task_priority_id, $get_current_task_created_datetime, $get_current_task_created_translated, $get_current_task_created_by_user_id, $get_current_task_created_by_user_alias, $get_current_task_created_by_user_image, $get_current_task_created_by_user_email, $get_current_task_updated_datetime, $get_current_task_updated_translated, $get_current_task_due_datetime, $get_current_task_due_time, $get_current_task_due_translated, $get_current_task_assigned_to_user_id, $get_current_task_assigned_to_user_alias, $get_current_task_assigned_to_user_image, $get_current_task_assigned_to_user_email, $get_current_task_hours_planned, $get_current_task_hours_used, $get_current_task_hours_diff_number, $get_current_task_hours_diff_percentage, $get_current_task_qa_datetime, $get_current_task_qa_by_user_id, $get_current_task_qa_by_user_alias, $get_current_task_qa_by_user_image, $get_current_task_qa_by_user_email, $get_current_task_finished_datetime, $get_current_task_finished_by_user_id, $get_current_task_finished_by_user_alias, $get_current_task_finished_by_user_image, $get_current_task_finished_by_user_email, $get_current_task_is_archived, $get_current_task_comments, $get_current_task_project_id, $get_current_task_project_part_id, $get_current_task_system_id, $get_current_task_system_part_id) = $row;
	if($get_current_task_id == ""){
		echo"<p>Server error 404</p>";
	}
	else{

		// Read?
		$my_user_id = $_SESSION['user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);
		$query_r = "SELECT read_id FROM $t_tasks_read WHERE read_task_id=$get_current_task_id AND read_user_id=$my_user_id_mysql";
		$result_r = mysqli_query($link, $query_r);
		$row_r = mysqli_fetch_row($result_r);
		list($get_read_id) = $row_r;
		if($get_read_id == ""){
			// Insert read
			mysqli_query($link, "INSERT INTO $t_tasks_read 
			(read_id, read_task_id, read_user_id) 
			VALUES 
			(NULL, $get_current_task_id, $my_user_id_mysql)")
			or die(mysqli_error($link));
		}

		// Number
		$number = "";
		if($get_current_task_project_incremented_number == "0" OR $get_current_task_project_incremented_number == ""){
			if($get_current_task_system_incremented_number == "0" OR $get_current_task_system_incremented_number == ""){
				$number = "$get_current_task_id";
			}
			else{
				$number = "$get_current_task_system_task_abbr-$get_current_task_system_incremented_number";
			}
		}
		else{
			$number = "$get_current_task_project_task_abbr-$get_current_task_project_incremented_number";
		}


		echo"
		<h1>$number $get_current_task_title</h1>



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

		<!-- Menu -->
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_task&amp;l=$l\" class=\"btn_default\">New task</a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_task&amp;task_id=$get_current_task_id&amp;l=$l\" class=\"btn_default\">Edit task</a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_task&amp;task_id=$get_current_task_id&amp;l=$l\" class=\"btn_default\">Delete task</a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=archive_task&amp;task_id=$get_current_task_id&amp;l=$l\" class=\"btn_default\">Archive task</a>
			</p> 
		<!-- Menu -->

		<!-- Task tabs -->
		<div class=\"clear\" style=\"height: 10px;\"></div>
		<div class=\"tabs\">
			<ul>";

			$query = "SELECT status_code_id, status_code_title, status_code_text_color, status_code_count_tasks FROM $t_tasks_status_codes ORDER BY status_code_weight ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_status_code_id, $get_status_code_title, $get_status_text_code_color, $get_status_code_count_tasks) = $row;
				echo"				";
				echo"<li><a href=\"index.php?open=$open&amp;page=$page&amp;status_code_id=$get_status_code_id&amp;l=$l\""; if($get_status_code_id == "$get_current_task_status_code_id"){ echo" class=\"active\""; } echo">$get_status_code_title ($get_status_code_count_tasks)</a></li>\n";
			}
			echo"
			</ul>
		</div>
		<div class=\"clear\" style=\"height: 20px;\"></div>
		<!-- //Task tabs -->
		<!-- View task header-->
			<div style=\"display: flex;\">
				<div style=\"flex: 1;\">
					<table>
					 <tr>
					  <td style=\"padding-right: 4px;\">
						<span style=\"font-weight: bold;\">System:</span>
					  </td>
					  <td>
						<span>";
						$query = "SELECT system_id, system_title FROM $t_tasks_systems WHERE system_id=$get_current_task_system_id";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_system_id, $get_system_title) = $row;
						echo"$get_system_title</span>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"padding-right: 4px;\">
						<span style=\"font-weight: bold;\">Project:</span>
					  </td>
					  <td>
						<span>";
						$query = "SELECT project_id, project_title FROM $t_tasks_projects WHERE project_id=$get_current_task_project_id";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_project_id, $get_project_title) = $row;
						echo"$get_project_title</span>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"padding-right: 4px;\">
						<span style=\"font-weight: bold;\">Priority:</span>
					  </td>
					  <td>
						<span>";
						if($get_current_task_priority_id == "1"){
							echo"Immediate Priority";
						}
						elseif($get_current_task_priority_id == "2"){
							echo"High Priority";
						}
						elseif($get_current_task_priority_id == "3"){
							echo"Normal Priority";
						}
						elseif($get_current_task_priority_id == "4"){
							echo"Low Priority";
						}
						elseif($get_current_task_priority_id == "5"){
							echo"Non-attendance";
						}
						echo"</span>
					  </td>
					 </tr>
					</table>
				</div>
				<div style=\"flex: 1;\">
					<table>
					 <tr>
					  <td style=\"padding-right: 4px;\">
						<span style=\"font-weight: bold;\">Assigned to:</span>
					  </td>
					  <td>
						<span><select name=\"inp_assigned_to_id\" class=\"on_select_go_to_url\">
							<option value=\"index.php?open=dashboard&amp;page=tasks&amp;action=edit_task_assigned_to&amp;task_id=$get_current_task_id&amp;assigned_to_user_id=&amp;l=$l&amp;process=1\""; if($get_current_task_assigned_to_user_id == ""){ echo" selected=\"selected\""; } echo">&nbsp;</option>\n";


						$query = "SELECT user_id, user_email, user_name FROM $t_users WHERE user_rank='admin' OR user_rank='moderator' OR user_rank='editor' ORDER BY user_name ASC";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
						list($get_user_id, $get_user_email, $get_user_name) = $row;

							$query_p = "SELECT profile_id, profile_user_id, profile_first_name, profile_middle_name, profile_last_name FROM $t_users_profile WHERE profile_user_id=$get_user_id";
							$result_p = mysqli_query($link, $query_p);
							$row_p = mysqli_fetch_row($result_p);
							list($get_profile_id, $get_profile_user_id, $get_profile_first_name, $get_profile_middle_name, $get_profile_last_name) = $row;



							echo"			<option value=\"index.php?open=dashboard&amp;page=tasks&amp;action=edit_task_assigned_to&amp;task_id=$get_current_task_id&amp;assigned_to_user_id=$get_user_id&amp;l=$l&amp;process=1\""; if($get_current_task_assigned_to_user_id == "$get_user_id"){ echo" selected=\"selected\""; } echo">";
							if($get_profile_first_name == ""){
								echo"$get_user_name";
							}
							else{
								echo"$get_profile_first_name $get_profile_middle_name $get_profile_last_name ($get_user_name)";
							}
							echo"</option>\n";
						}
						echo"
						</select></span>

						<span><a href=\"../users/view_profile.php?user_id=$get_current_task_assigned_to_user_id&amp;l=$l\">$get_current_task_assigned_to_user_alias</a></span>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"padding-right: 4px;\">
						<span style=\"font-weight: bold;\">Updated:</span>
					  </td>
					  <td>
						<span>$get_current_task_updated_translated</span>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"padding-right: 4px;\">
						<span style=\"font-weight: bold;\">Status:</span>
					  </td>
					  <td>
						<span><select name=\"inp_status_code_id\" class=\"on_select_go_to_url\">";
						$query = "SELECT status_code_id, status_code_title FROM $t_tasks_status_codes ORDER BY status_code_weight ASC";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
						list($get_status_code_id, $get_status_code_title) = $row;
							echo"			<option value=\"index.php?open=dashboard&amp;page=tasks&amp;action=edit_task_status&amp;task_id=$get_current_task_id&amp;status_code_id=$get_status_code_id&amp;l=$l&amp;process=1\""; if($get_current_task_status_code_id == "$get_status_code_id"){ echo" selected=\"selected\""; } echo">$get_status_code_title</option>\n";
						}
						echo"
						</select></span>

						<!-- On select go to url -->
						<script>
							\$(function(){
								// bind change event to select
								\$('.on_select_go_to_url').on('change', function () {
       									var url = \$(this).val(); // get selected value
      									if (url) { // require a URL
       										window.location = url; // redirect
      									}
									return false;
								});
							});
						</script>
						<!-- //On select go to url -->

					  </td>
					 </tr>
					</table>
				</div>
				<div style=\"flex: 1;\">
					<table>
					 <tr>
					  <td style=\"padding-right: 4px;\">
						<span style=\"font-weight: bold;\">Due:</span>
					  </td>
					  <td>
						<span>$get_current_task_due_translated</span>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"padding-right: 4px;\">
						<span style=\"font-weight: bold;\">Created by:</span>
					  </td>
					  <td>
						<span><a href=\"../users/view_profile.php?user_id=$get_current_task_created_by_user_id&amp;l=$l\">$get_current_task_created_by_user_alias</a></span>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"padding-right: 4px;\">
						<span style=\"font-weight: bold;\">Created date:</span>
					  </td>
					  <td>
						<span>$get_current_task_created_translated</span>
					  </td>
					 </tr>

					</table>
				</div>
				<div style=\"flex: 1;\">
					<table>
					 <tr>
					  <td style=\"padding-right: 4px;\">
						<span style=\"font-weight: bold;\">Hours planned:</span>
					  </td>
					  <td>
						<span>$get_current_task_hours_planned</span>
					  </td>
					 </tr>

					 <tr>
					  <td style=\"padding-right: 4px;\">
						<span style=\"font-weight: bold;\">Hours used:</span>
					  </td>
					  <td>
						<span>$get_current_task_hours_used</span>
					  </td>
					 </tr>

					 <tr>
					  <td style=\"padding-right: 4px;\">
						<span style=\"font-weight: bold;\">Diff:</span>
					  </td>
					  <td>
						<span>$get_current_task_hours_diff_number ($get_current_task_hours_diff_percentage %)</span>
					  </td>
					 </tr>
					</table>
				</div>

			</div>
			<div class=\"clear\" style=\"height: 20px;\"></div>
		<!-- //View task header -->

		<!-- Text + history -->
			<div style=\"display: flex;\">
				<div style=\"flex: 1;\">
					<!-- Text -->
						$get_current_task_text
					<!-- //Text -->
				</div>
				<div style=\"flex: 1;\">
					<!-- History -->
						<table class=\"hor-zebra\">
						 <thead>
						  <tr>
						   <th scope=\"col\">
							<span><b>Date</b></span>
						   </th>
						   <th scope=\"col\">
							<span><b>Edited by</b></span>
						   </th>
						   <th scope=\"col\">
							<span><b>Changes</b></span>
						   </th>
						  </tr>
						 </thead>
						 <tbody>
						";
						$query = "SELECT history_id, history_task_id, history_updated_by_user_id, history_updated_by_user_name, history_updated_datetime_saying, history_summary FROM $t_tasks_history WHERE history_task_id=$get_current_task_id ORDER BY history_id DESC";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_history_id, $get_history_task_id, $get_history_updated_by_user_id, $get_history_updated_by_user_name, $get_history_updated_datetime_saying, $get_history_summary) = $row;
			
							// Style
							if(isset($style) && $style == ""){
								$style = "odd";
							}
							else{
								$style = "";
							}
							echo"
							 <tr>
							  <td class=\"$style\" style=\"vertical-align:top;\">
								<span>
								$get_history_updated_datetime_saying
								</span>
							  </td>
							  <td class=\"$style\" style=\"vertical-align:top;\">
								<span>
								$get_history_updated_by_user_name
								</span>
							  </td>
							  <td class=\"$style\" style=\"vertical-align:top;\">
								<span>
								$get_history_summary
								</span>
							  </td>
							 </tr>
							";
						}
						echo"
						 </tbody>
						</table>
					<!-- //History  -->
				</div>
			</div>
			<div class=\"clear\" style=\"height: 20px;\"></div>
		<!-- //Text -->
		";
	} // task foud
} // open_task
elseif($action == "edit_task"){
	// Get task
	$task_id_mysql = quote_smart($link, $task_id);
	$query = "SELECT task_id, task_system_task_abbr, task_system_incremented_number, task_project_task_abbr, task_project_incremented_number, task_title, task_text, task_status_code_id, task_priority_id, task_created_datetime, task_created_translated,  task_created_by_user_id, task_created_by_user_alias, task_created_by_user_image, task_created_by_user_email, task_updated_datetime, task_updated_translated, task_due_datetime, task_due_time, task_due_translated, task_assigned_to_user_id, task_assigned_to_user_alias, task_assigned_to_user_image, task_assigned_to_user_email, task_hours_planned, task_hours_used, task_qa_datetime, task_qa_by_user_id, task_qa_by_user_alias, task_qa_by_user_image, task_qa_by_user_email, task_finished_datetime, task_finished_by_user_id, task_finished_by_user_alias, task_finished_by_user_image, task_finished_by_user_email, task_is_archived, task_comments, task_project_id, task_project_part_id, task_system_id, task_system_part_id FROM $t_tasks_index WHERE task_id=$task_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_task_id, $get_current_task_system_task_abbr, $get_current_task_system_incremented_number, $get_current_task_project_task_abbr, $get_current_task_project_incremented_number, $get_current_task_title, $get_current_task_text, $get_current_task_status_code_id, $get_current_task_priority_id, $get_current_task_created_datetime, $get_current_task_created_translated, $get_current_task_created_by_user_id, $get_current_task_created_by_user_alias, $get_current_task_created_by_user_image, $get_current_task_created_by_user_email, $get_current_task_updated_datetime, $get_current_task_updated_translated, $get_current_task_due_datetime, $get_current_task_due_time, $get_current_task_due_translated, $get_current_task_assigned_to_user_id, $get_current_task_assigned_to_user_alias, $get_current_task_assigned_to_user_image, $get_current_task_assigned_to_user_email, $get_current_task_hours_planned, $get_current_task_hours_used, $get_current_task_qa_datetime, $get_current_task_qa_by_user_id, $get_current_task_qa_by_user_alias, $get_current_task_qa_by_user_image, $get_current_task_qa_by_user_email, $get_current_task_finished_datetime, $get_current_task_finished_by_user_id, $get_current_task_finished_by_user_alias, $get_current_task_finished_by_user_image, $get_current_task_finished_by_user_email, $get_current_task_is_archived, $get_current_task_comments, $get_current_task_project_id, $get_current_task_project_part_id, $get_current_task_system_id, $get_current_task_system_part_id) = $row;
	if($get_current_task_id == ""){
		echo"<p>Server error 404</p>";
	}
	else{
		if($process == 1){

			// History
			$inp_history_summary = "";

			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_history_new_title = "";
			if($get_current_task_title != "$inp_title"){
				$inp_history_new_title = "$inp_title";

				if($inp_history_summary != ""){ $inp_history_summary = $inp_history_summary . " &middot; "; } 
				$inp_history_summary = $inp_history_summary . "New title: $inp_title";
			}
			$inp_history_new_title_mysql = quote_smart($link, $inp_history_new_title);
				
		
			$inp_text = $_POST['inp_text'];

			$inp_history_new_text = "";
			if($get_current_task_text != "$inp_text"){
				$inp_history_new_text = output_html($inp_text);

				if($inp_history_summary != ""){ $inp_history_summary = $inp_history_summary . " &middot; "; } 
				$inp_history_summary = $inp_history_summary . "New text: $inp_text";
			}
			$inp_history_new_text = str_replace("\xBD", "", $inp_history_new_text);
			$inp_history_new_text_mysql = quote_smart($link, $inp_history_new_text);

			$inp_status_code_id = $_POST['inp_status_code_id'];
			$inp_status_code_id = output_html($inp_status_code_id);
			$inp_status_code_id_mysql = quote_smart($link, $inp_status_code_id);

		
			// Update status_code_count_tasks
			$query = "SELECT status_code_id, status_code_title, status_code_count_tasks FROM $t_tasks_status_codes WHERE status_code_id=$inp_status_code_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_status_code_id, $get_status_code_title, $get_status_code_count_tasks) = $row;
			if($get_status_code_id != "$get_current_task_status_code_id"){
				// Update new status code with +1
				$inp_status_code_count_tasks = $get_status_code_count_tasks+1;
				$result = mysqli_query($link, "UPDATE $t_tasks_status_codes SET status_code_count_tasks='$inp_status_code_count_tasks' WHERE status_code_id=$get_status_code_id");

				// Update old status code with -1
				$query = "SELECT status_code_id, status_code_count_tasks FROM $t_tasks_status_codes WHERE status_code_id=$get_current_task_status_code_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_status_code_id, $get_status_code_count_tasks) = $row;
				$inp_status_code_count_tasks = $get_status_code_count_tasks-1;
				$result = mysqli_query($link, "UPDATE $t_tasks_status_codes SET status_code_count_tasks='$inp_status_code_count_tasks' WHERE status_code_id=$get_current_task_status_code_id");

			}
			$inp_status_code_title_mysql = quote_smart($link, $get_status_code_title);

			$inp_history_new_status_code_id = "0";
			$inp_history_new_status_code_title = "";
			if($get_current_task_status_code_id != "$inp_status_code_id"){
				$inp_history_new_status_code_id = "$inp_status_code_id";
				$inp_history_new_status_code_title = "$get_status_code_title";

				if($inp_history_summary != ""){ $inp_history_summary = $inp_history_summary . " &middot; "; } 
				$inp_history_summary = $inp_history_summary . "New status: $get_status_code_title";
			}
			$inp_history_new_status_code_id_mysql = quote_smart($link, $inp_history_new_status_code_id);
			$inp_history_new_status_code_title_mysql = quote_smart($link, $inp_history_new_status_code_title);
			

			$inp_priority_id = $_POST['inp_priority_id'];
			$inp_priority_id = output_html($inp_priority_id);
			$inp_priority_id_mysql = quote_smart($link, $inp_priority_id);

			
			$inp_history_new_priority_id = "0";
			if($get_current_task_priority_id != "$inp_priority_id"){
				$inp_history_new_priority_id = "$inp_priority_id";

				if($inp_history_summary != ""){ $inp_history_summary = $inp_history_summary . " &middot; "; } 
				$inp_history_summary = $inp_history_summary . "New priority id: $inp_priority_id";
			}
			$inp_history_new_priority_id_mysql = quote_smart($link, $inp_history_new_priority_id);
			
			$inp_due_day = $_POST['inp_due_day'];
			$inp_due_month = $_POST['inp_due_month'];
			$inp_due_year = $_POST['inp_due_year'];
			$inp_due_datetime = $inp_due_year . "-" . $inp_due_month . "-" . $inp_due_day . " 23:00:00";
			$inp_due_datetime = output_html($inp_due_datetime);
			$inp_due_datetime_mysql = quote_smart($link, $inp_due_datetime);

			$inp_due_time = strtotime($inp_due_datetime);
			$inp_due_time_mysql = quote_smart($link, $inp_due_time);

			$inp_due_translated = "$inp_due_day";
			if($inp_due_month == "1" OR $inp_due_month == "01"){
				$inp_due_translated = $inp_due_translated . " $l_january";
			}
			elseif($inp_due_month == "2" OR $inp_due_month == "02"){
				$inp_due_translated = $inp_due_translated . " $l_february";
			}
			elseif($inp_due_month == "3" OR $inp_due_month == "03"){
				$inp_due_translated = $inp_due_translated . " $l_march";
			}
			elseif($inp_due_month == "4" OR $inp_due_month == "04"){
				$inp_due_translated = $inp_due_translated . " $l_april";
			}
			elseif($inp_due_month == "5" OR $inp_due_month == "05"){
				$inp_due_translated = $inp_due_translated . " $l_may";
			}
			elseif($inp_due_month == "6" OR $inp_due_month == "06"){
				$inp_due_translated = $inp_due_translated . " $l_june";
			}
			elseif($inp_due_month == "7" OR $inp_due_month == "07"){
				$inp_due_translated = $inp_due_translated . " $l_juli";
			}
			elseif($inp_due_month == "8" OR $inp_due_month == "08"){
				$inp_due_translated = $inp_due_translated . " $l_august";
			}
			elseif($inp_due_month == "9" OR $inp_due_month == "09"){
				$inp_due_translated = $inp_due_translated . " $l_september";
			}
			elseif($inp_due_month == "10"){
				$inp_due_translated = $inp_due_translated . " $l_october";
			}
			elseif($inp_due_month == "11"){
				$inp_due_translated = $inp_due_translated . " $l_november";
			}
			elseif($inp_due_month == "12"){
				$inp_due_translated = $inp_due_translated . " $l_december";
			}
			$inp_due_translated = $inp_due_translated . " $inp_due_year";
			$inp_due_translated = output_html($inp_due_translated);
			$inp_due_translated_mysql = quote_smart($link, $inp_due_translated);



			// Assigned to
			$inp_assigned_to_user_alias = $_POST['inp_assigned_to_user_alias'];
			$inp_assigned_to_user_alias = output_html($inp_assigned_to_user_alias);
			$inp_assigned_to_user_alias_mysql = quote_smart($link, $inp_assigned_to_user_alias);
	
			$query = "SELECT user_id, user_email, user_name, user_alias FROM $t_users WHERE user_alias=$inp_assigned_to_user_alias_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_user_id, $get_user_email, $get_user_name, $get_user_alias) = $row;

			if($get_user_id == ""){
				$get_user_id = 0;
			}
			$inp_assigned_to_user_id = $get_user_id;
			$inp_assigned_to_user_id = output_html($inp_assigned_to_user_id);
			$inp_assigned_to_user_id_mysql = quote_smart($link, $inp_assigned_to_user_id);

			$inp_assigned_to_user_name = "$get_user_name";
			$inp_assigned_to_user_name = output_html($inp_assigned_to_user_name);
			$inp_assigned_to_user_name_mysql = quote_smart($link, $inp_assigned_to_user_name);

			$inp_assigned_to_user_email = "$get_user_email";
			$inp_assigned_to_user_email = output_html($inp_assigned_to_user_email);
			$inp_assigned_to_user_email_mysql = quote_smart($link, $inp_assigned_to_user_email);

			// Get assigned to photo
			$query = "SELECT photo_id, photo_destination, photo_thumb_40, photo_thumb_50 FROM $t_users_profile_photo WHERE photo_user_id=$inp_assigned_to_user_id_mysql AND photo_profile_image='1'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_photo_id, $get_photo_destination, $get_photo_thumb_40, $get_photo_thumb_50) = $row;

			$inp_assigned_to_user_image_mysql = quote_smart($link, $get_photo_destination);
			$inp_assigned_to_user_thumb_a_mysql = quote_smart($link, $get_photo_thumb_40);
			$inp_assigned_to_user_thumb_b_mysql = quote_smart($link, $get_photo_thumb_50);

			// assigned to history

			$inp_history_new_assigned_to_user_id = "0";
			$inp_history_new_assigned_to_user_name = "";
			$inp_history_new_assigned_to_user_alias = "";
			$inp_history_new_assigned_to_user_image = "";
			$inp_history_new_assigned_to_user_thumb_a = "";
			$inp_history_new_assigned_to_user_thumb_b = "";
			$inp_history_new_assigned_to_user_email = "";
			if($get_current_task_assigned_to_user_id != "$inp_assigned_to_user_id"){
				$inp_history_new_assigned_to_user_id = "$inp_assigned_to_user_id";
				$inp_history_new_assigned_to_user_name = "$inp_assigned_to_user_name";
				$inp_history_new_assigned_to_user_alias = "$inp_assigned_to_user_alias";
				$inp_history_new_assigned_to_user_image = "$get_photo_destination";
				$inp_history_new_assigned_to_user_thumb_a = "$get_photo_thumb_40";
				$inp_history_new_assigned_to_user_thumb_b = "$get_photo_thumb_50";
				$inp_history_new_assigned_to_user_email = "$inp_assigned_to_user_email";

				if($inp_history_summary != ""){ $inp_history_summary = $inp_history_summary . " &middot; "; } 
				$inp_history_summary = $inp_history_summary . "Assigned to $inp_history_new_assigned_to_user_name";
			}
			$inp_history_new_assigned_to_user_id_mysql = quote_smart($link, $inp_history_new_assigned_to_user_id);
			$inp_history_new_assigned_to_user_name_mysql = quote_smart($link, $inp_history_new_assigned_to_user_name);
			$inp_history_new_assigned_to_user_alias_mysql = quote_smart($link, $inp_history_new_assigned_to_user_alias);
			$inp_history_new_assigned_to_user_image_mysql = quote_smart($link, $inp_history_new_assigned_to_user_image);
			$inp_history_new_assigned_to_user_thumb_a_mysql = quote_smart($link, $inp_history_new_assigned_to_user_thumb_a);
			$inp_history_new_assigned_to_user_thumb_b_mysql = quote_smart($link, $inp_history_new_assigned_to_user_thumb_b);
			$inp_history_new_assigned_to_user_email_mysql = quote_smart($link, $inp_history_new_assigned_to_user_email);

			// System id
			$inp_system_id = $_POST['inp_system_id'];
			$inp_system_id = output_html($inp_system_id);
			$inp_system_id_mysql = quote_smart($link, $inp_system_id);
			
			if($inp_system_id != "0"){

				$query = "SELECT system_id, system_title, system_task_abbr, system_description, system_logo, system_is_active, system_increment_tasks_counter, system_created, system_updated FROM $t_tasks_systems WHERE system_id=$inp_system_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_system_id, $get_system_title, $get_system_task_abbr, $get_system_description, $get_system_logo, $get_system_is_active, $get_system_increment_tasks_counter, $get_system_created, $get_system_updated) = $row;
			}
			else{
				// Pick random system
				$query = "SELECT system_id, system_title, system_task_abbr, system_description, system_logo, system_is_active, system_increment_tasks_counter, system_created, system_updated FROM $t_tasks_systems LIMIT 0,1";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_system_id, $get_system_title, $get_system_task_abbr, $get_system_description, $get_system_logo, $get_system_is_active, $get_system_increment_tasks_counter, $get_system_created, $get_system_updated) = $row;
				$inp_system_id = "$get_system_id";
				$inp_system_id_mysql = quote_smart($link, $inp_system_id);

			}

			$inp_system_title_mysql = quote_smart($link, $get_system_title);
			if($get_current_task_system_id != "$inp_system_id"){
				if($get_system_id == ""){
					$inp_system_task_abbr_mysql = quote_smart($link, "");
					$inp_system_increment_tasks_counter_mysql = quote_smart($link, "0");

				}
				else{
					// Update increment tasks counter
					$inp_system_task_abbr_mysql = quote_smart($link, $get_system_task_abbr);
					$inp_system_increment_tasks_counter_mysql = quote_smart($link, $get_system_increment_tasks_counter);

					// Update counter
					$inp_increment_tasks_counter = $get_system_increment_tasks_counter+1;

					$result = mysqli_query($link, "UPDATE $t_tasks_systems SET system_increment_tasks_counter=$inp_increment_tasks_counter WHERE system_id=$get_system_id") or die(mysqli_error($link));
				}
			}
			else{
				// Use old 
				$inp_system_task_abbr_mysql = quote_smart($link, $get_current_task_system_task_abbr);
				$inp_system_increment_tasks_counter_mysql = quote_smart($link, $get_current_task_system_incremented_number);
			}

			// Insert last used system
			$query = "SELECT last_used_system_id, last_used_system_user_id, last_used_system_system_id FROM $t_tasks_last_used_systems WHERE last_used_system_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_last_used_system_id, $get_last_used_system_user_id, $get_last_used_system_system_id) = $row;	
			if($get_last_used_system_id == ""){
				// No last used system
				mysqli_query($link, "INSERT INTO $t_tasks_last_used_systems 
				(last_used_system_id, last_used_system_user_id, last_used_system_system_id) 
				VALUES 
				(NULL, $my_user_id_mysql, $inp_system_id_mysql)")
				or die(mysqli_error($link));
			}
			else{
				$result = mysqli_query($link, "UPDATE $t_tasks_last_used_systems SET last_used_system_system_id=$inp_system_id_mysql WHERE last_used_system_id=$get_last_used_system_id") or die(mysqli_error($link));

			}

			// Project id
			$inp_project_id = $_POST['inp_project_id'];
			$inp_project_id = output_html($inp_project_id);
			$inp_project_id_mysql = quote_smart($link, $inp_project_id);

			$query = "SELECT project_id, project_system_id, project_title, project_task_abbr, project_description, project_logo, project_is_active, project_increment_tasks_counter, project_created, project_updated FROM $t_tasks_projects WHERE project_id=$inp_project_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_project_id, $get_project_system_id, $get_project_title, $get_project_task_abbr, $get_project_description, $get_project_logo, $get_project_is_active, $get_project_increment_tasks_counter, $get_project_created, $get_project_updated ) = $row;

			$inp_project_title_mysql = quote_smart($link, $get_project_title);

			if($get_current_task_project_id != "$inp_project_id"){
				if($get_project_id == ""){
					$inp_project_task_abbr_mysql = quote_smart($link, "");
					$inp_project_increment_tasks_counter_mysql = quote_smart($link, "0");
				}
				else{
					// Update increment tasks counter
					$inp_project_task_abbr_mysql = quote_smart($link, $get_project_task_abbr);
					if($get_project_increment_tasks_counter == ""){ $get_project_increment_tasks_counter = "0"; } 
					$inp_project_increment_tasks_counter_mysql = quote_smart($link, $get_project_increment_tasks_counter);

					// Update counter
					$inp_project_increment_tasks_counter = $get_project_increment_tasks_counter+1;
					$result = mysqli_query($link, "UPDATE $t_tasks_projects SET project_increment_tasks_counter=$inp_project_increment_tasks_counter WHERE project_id=$get_project_id") or die(mysqli_error($link));
				}
			}
			else{
				// Use old 
				$inp_project_task_abbr_mysql = quote_smart($link, $get_current_task_project_task_abbr);
				$inp_project_increment_tasks_counter_mysql = quote_smart($link, $get_current_task_project_incremented_number);
			}

			// Insert last used project
			$query = "SELECT last_used_project_id, last_used_project_user_id, last_used_project_project_id FROM $t_tasks_last_used_projects WHERE last_used_project_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_last_used_project_id, $get_last_used_project_user_id, $get_last_used_project_project_id) = $row;
			if($get_last_used_project_id == ""){
				// No last used system
				mysqli_query($link, "INSERT INTO $t_tasks_last_used_projects 
				(last_used_project_id, last_used_project_user_id, last_used_project_project_id) 
				VALUES 
				(NULL, $my_user_id_mysql, $inp_project_id_mysql)")
				or die(mysqli_error($link));
			}
			else{
				$result = mysqli_query($link, "UPDATE $t_tasks_last_used_projects SET last_used_project_project_id=$inp_project_id_mysql WHERE last_used_project_id=$get_last_used_project_id") or die(mysqli_error($link));

			}

		
			// Updated
			$datetime = date("Y-m-d H:i:s");


			$inp_updated_translated = date("d");
			$month = date("m");
			$year = date("Y");
			if($month == "01"){
				$inp_updated_translated = $inp_updated_translated . " $l_january";
			}
			elseif($month == "2"){
				$inp_updated_translated = $inp_updated_translated . " $l_february";
			}
			elseif($month == "03"){
				$inp_updated_translated = $inp_updated_translated . " $l_march";
			}
			elseif($month == "04"){
				$inp_updated_translated = $inp_updated_translated . " $l_april";
			}
			elseif($month == "05"){
				$inp_updated_translated = $inp_updated_translated . " $l_may";
			}
			elseif($month == "06"){
				$inp_updated_translated = $inp_updated_translated . " $l_june";
			}
			elseif($month == "07"){
				$inp_updated_translated = $inp_updated_translated . " $l_juli";
			}
			elseif($month == "08"){
				$inp_updated_translated = $inp_updated_translated . " $l_august";
			}
			elseif($month == "09"){
				$inp_updated_translated = $inp_updated_translated . " $l_september";
			}
			elseif($month == "10"){
				$inp_updated_translated = $inp_updated_translated . " $l_october";
			}
			elseif($month == "11"){
				$inp_updated_translated = $inp_updated_translated . " $l_november";
			}
			elseif($month == "12"){
				$inp_updated_translated = $inp_updated_translated . " $l_december";
			}
			$inp_updated_translated = $inp_updated_translated . " $year";
			$inp_updated_translated = output_html($inp_updated_translated);
			$inp_updated_translated_mysql = quote_smart($link, $inp_updated_translated);

			// Hours planned
			$inp_hours_planned = $_POST['inp_hours_planned'];
			$inp_hours_planned = output_html($inp_hours_planned);
			$inp_hours_planned = str_replace(",", ".", $inp_hours_planned);
			if($inp_hours_planned == ""){
				$inp_hours_planned = "0";
			}
			$inp_hours_planned_mysql = quote_smart($link, $inp_hours_planned);

			// Hours used
			$inp_hours_used = $_POST['inp_hours_used'];
			$inp_hours_used = output_html($inp_hours_used);
			$inp_hours_used = str_replace(",", ".", $inp_hours_used);
			if($inp_hours_used == ""){
				$inp_hours_used = "0";
			}
			$inp_hours_used_mysql = quote_smart($link, $inp_hours_used);

			// Diff
			$inp_hours_diff_number = $inp_hours_used - $inp_hours_planned ;
			$inp_hours_diff_number_mysql = quote_smart($link, $inp_hours_diff_number);

			if($inp_hours_planned == "0"){
				$inp_hours_diff_percentage = 0;
			}
			else{
				$inp_hours_diff_percentage = ($inp_hours_used / $inp_hours_planned) * 100;
			}
			$inp_hours_diff_percentage = round($inp_hours_diff_percentage);
			$inp_hours_diff_percentage_mysql = quote_smart($link, $inp_hours_diff_percentage);


			$inp_history_new_hours_planned = "0";
			$inp_history_new_hours_used = "0";
			if($get_current_task_assigned_to_user_id != "$inp_assigned_to_user_id"){
				$inp_history_new_hours_planned = "$inp_hours_planned";
				$inp_history_new_hours_used = "$inp_hours_used";

				if($inp_history_summary != ""){ $inp_history_summary = $inp_history_summary . " &middot; "; } 
				$inp_history_summary = $inp_history_summary . "Hours planned $inp_history_new_hours_planned and used $inp_history_new_hours_used";
			}
			$inp_history_new_hours_planned_mysql = quote_smart($link, $inp_history_new_hours_planned);
			$inp_history_new_hours_used_mysql = quote_smart($link, $inp_history_new_hours_used);

			// Update
			$result = mysqli_query($link, "UPDATE $t_tasks_index SET 
							task_system_task_abbr=$inp_system_task_abbr_mysql, 
							task_system_incremented_number=$inp_system_increment_tasks_counter_mysql, 
							task_project_task_abbr=$inp_project_task_abbr_mysql, 
							task_project_incremented_number=$inp_project_increment_tasks_counter_mysql, 
							task_title=$inp_title_mysql, 
							task_status_code_id=$inp_status_code_id_mysql, 
							task_status_code_title=$inp_status_code_title_mysql,
							task_priority_id=$inp_priority_id_mysql, 
							task_updated_datetime='$datetime', 
							task_updated_translated=$inp_updated_translated_mysql, 
							task_due_datetime=$inp_due_datetime_mysql, 
							task_due_time=$inp_due_time_mysql, 
							task_due_translated=$inp_due_translated_mysql, 
							task_assigned_to_user_id=$inp_assigned_to_user_id_mysql, 
							task_assigned_to_user_name=$inp_assigned_to_user_name_mysql, 
							task_assigned_to_user_alias=$inp_assigned_to_user_alias_mysql, 
							task_assigned_to_user_image=$inp_assigned_to_user_image_mysql, 
							task_assigned_to_user_thumb_40=$inp_assigned_to_user_thumb_a_mysql, 
							task_assigned_to_user_thumb_50=$inp_assigned_to_user_thumb_b_mysql, 
							task_assigned_to_user_email=$inp_assigned_to_user_email_mysql, 
							task_hours_planned=$inp_hours_planned_mysql, 
							task_hours_used=$inp_hours_used_mysql, 
							task_hours_diff_number=$inp_hours_diff_number_mysql, 
							task_hours_diff_percentage=$inp_hours_diff_percentage_mysql, 
							task_project_id=$inp_project_id_mysql, 
							task_project_title=$inp_project_title_mysql, 
							task_system_id=$inp_system_id_mysql,
							task_system_title=$inp_system_title_mysql
							 WHERE task_id=$get_current_task_id") or die(mysqli_error($link));



			// Text
			$sql = "UPDATE $t_tasks_index SET task_text=? WHERE task_id='$get_current_task_id'";
			$stmt = $link->prepare($sql);
			$stmt->bind_param("s", $inp_text);
			$stmt->execute();
			if ($stmt->errno) {
				echo "FAILURE!!! " . $stmt->error; die;
			}

			// Delete read
			$result = mysqli_query($link, "DELETE FROM $t_tasks_read WHERE read_task_id='$get_current_task_id'");




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

			$inp_history_summary_mysql = quote_smart($link, $inp_history_summary);
			mysqli_query($link, "INSERT INTO $t_tasks_history 
			(history_id, history_task_id, history_updated_by_user_id, history_updated_by_user_name, history_updated_by_user_alias, 
			history_updated_by_user_email, history_updated_datetime, history_updated_datetime_saying, history_summary, history_new_title, history_new_text, 
			history_new_status_code_id, history_new_status_code_title, history_new_priority_id, history_new_assigned_to_user_id, history_new_assigned_to_user_name, 
			history_new_assigned_to_user_alias, history_new_assigned_to_user_image, history_new_assigned_to_user_email, history_new_hours_planned, history_new_hours_used) 
			VALUES 
			(NULL, $get_current_task_id, $my_user_id_mysql, $inp_updated_by_user_name_mysql, $inp_updated_by_user_alias_mysql,
			$inp_updated_by_user_email_mysql, '$datetime', '$inp_datetime_saying',  $inp_history_summary_mysql, $inp_history_new_title_mysql, $inp_history_new_text_mysql, 

$inp_history_new_status_code_id_mysql, $inp_history_new_status_code_title_mysql, $inp_history_new_priority_id_mysql, $inp_history_new_assigned_to_user_id_mysql, $inp_history_new_assigned_to_user_name_mysql,
			$inp_history_new_assigned_to_user_alias_mysql, $inp_history_new_assigned_to_user_image_mysql, $inp_history_new_assigned_to_user_email_mysql,
$inp_history_new_hours_planned_mysql , $inp_history_new_hours_used_mysql )")
			or die(mysqli_error($link));


			
			// Email if assigned to new person
			$fm_email = "";
			if($get_current_task_assigned_to_user_id != "$inp_assigned_to_user_id" && $inp_assigned_to_user_email != "" && $inp_assigned_to_user_id != "$get_my_user_id"){
				
				$subject = "Task $inp_title has been reassigned to you | $configWebsiteTitleSav";
				$subject = str_replace('&quot;', '"', $subject);

				$message = "<html>\n";
				$message = $message. "<head>\n";
				$message = $message. "  <title>$subject</title>\n";

				$message = $message. "  <style type=\"text/css\">\n";
				$message = $message. "  tr td:first-child {\n";
				$message = $message. "      width: 1%;\n";
				$message = $message. "      white-space: nowrap;\n";
				$message = $message. "  }\n";
				$message = $message. "  </style>\n";

				$message = $message. " </head>\n";
				$message = $message. "<body>\n";
				$message = $message. "<p>An assignment has been reassigned to you by <a href=\"$configSiteURLSav/users/view_profile.php?user_id=$get_my_user_id&amp;l=$l\">$get_my_user_alias</a>.</p>\n";
				$message = $message. "<table style='width: 100%'>\n";
				$message = $message. " <tr>\n";
				$message = $message. "  <td><span>ID:</span></td>\n";
				$message = $message. "  <td><span>$get_current_task_id</span></td>\n";
				$message = $message. " </tr>\n";
				$message = $message. " <tr>\n";
				$message = $message. "  <td><span>Title:</span></td>\n";
				$message = $message. "  <td><span><a href=\"$configControlPanelURLSav/index.php?open=dashboard&amp;page=tasks&amp;action=open_task&amp;task_id=$get_current_task_id&amp;editor_language=$editor_language&amp;l=$l\">$inp_title</a></span></td>\n";
				$message = $message. " </tr>\n";
				$message = $message. " <tr>\n";
				$message = $message. "  <td><span>Due:</span></td>\n";
				$message = $message. "  <td><span>$inp_due_translated</span></td>\n";
				$message = $message. " </tr>\n";
				$message = $message. " <tr>\n";
				$message = $message. "  <td><span>Priority:</span></td>\n";
				$message = $message. "  <td><span>$inp_priority_id</span></td>\n";
				$message = $message. " </tr>\n";
				$message = $message. " <tr>\n";
				$message = $message. "  <td><span>Status:</span></td>\n";
				$message = $message. "  <td><span>$inp_status_code_id</span></td>\n";
				$message = $message. " </tr>\n";
				$message = $message. "</table>\n";
				$message = $message. "$inp_text";

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

				// $myfile = fopen("../_cache/mail_debug.html", "w") or die("Unable to open file!");
				// fwrite($myfile, $message);
				// fclose($myfile);

				// Notification to assigned to user
				$inp_notification_reference_id_text = "task_" . $get_current_task_id;
				$inp_notification_reference_id_text_mysql = quote_smart($link, $inp_notification_reference_id_text);

				$inp_notification_url = "$configControlPanelURLSav/index.php?open=dashboard&page=tasks&action=open_task&task_id=$get_current_task_id&editor_language=$editor_language&l=$l";
				$inp_notification_url_mysql = quote_smart($link, $inp_notification_url);
	
				$inp_notification_text = "Task &quot;$inp_title&quot; has been reassigned to you by $get_my_user_alias";
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

			// Make sure that I am a task subscriber
			$query = "SELECT subscription_id FROM $t_tasks_subscriptions WHERE subscription_task_id=$get_current_task_id AND subscription_user_id=$inp_updated_by_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_notification_id) = $row;
			if($get_notification_id == ""){
				mysqli_query($link, "INSERT INTO $t_tasks_subscriptions
				(subscription_id, subscription_task_id, subscription_user_id, subscription_user_email) 
				VALUES 
				(NULL, $get_current_task_id, $inp_updated_by_user_id_mysql, $inp_updated_by_user_email_mysql)")
				or die(mysqli_error($link));
			}

			// Email to all task subscribers
			$query = "SELECT subscription_id, subscription_task_id, subscription_user_id, subscription_user_email FROM $t_tasks_subscriptions WHERE subscription_task_id=$get_current_task_id";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_subscription_id, $get_subscription_task_id, $get_subscription_user_id, $get_subscription_user_email) = $row;

				/*
				if($get_subscription_user_email != "$get_my_user_email" && $get_subscription_user_email != "$inp_assigned_to_user_id"){
					$subject = "Task $inp_title changed at $configWebsiteTitleSav";

					$message = "<html>\n";
					$message = $message. "<head>\n";
					$message = $message. "  <title>$subject</title>\n";

					$message = $message. "  <style type=\"text/css\"></style>\n";
					$message = $message. " </head>\n";
					$message = $message. "<body>\n";
					$message = $message. "<p>The user <a href=\"$configSiteURLSav/users/view_profile.php?user_id=$get_my_user_id&amp;l=$l\">$get_my_user_alias</a> has made changes to the task.</p>\n";
					$message = $message. "<table>\n";
					$message = $message. " <tr>\n";
					$message = $message. "  <td><span>ID:</span></td>\n";
					$message = $message. "  <td><span>$get_current_task_id</span></td>\n";
					$message = $message. " </tr>\n";
					$message = $message. " <tr>\n";
					$message = $message. "  <td><span>Title:</span></td>\n";
					$message = $message. "  <td><span><a href=\"$configControlPanelURLSav/index.php?open=dashboard&amp;page=tasks&amp;action=open_task&amp;task_id=$get_current_task_id&amp;editor_language=$editor_language&amp;l=$l\">$inp_title</a></span></td>\n";
					$message = $message. " </tr>\n";
					$message = $message. " <tr>\n";
					$message = $message. "  <td><span>Due:</span></td>\n";
					$message = $message. "  <td><span>$inp_due_translated</span></td>\n";
					$message = $message. " </tr>\n";
					$message = $message. " <tr>\n";
					$message = $message. "  <td><span>Priority:</span></td>\n";
					$message = $message. "  <td><span>$inp_priority_id</span></td>\n";
					$message = $message. " </tr>\n";
					$message = $message. " <tr>\n";
					$message = $message. "  <td><span>Status:</span></td>\n";
					$message = $message. "  <td><span>$inp_status_code_id</span></td>\n";
					$message = $message. " </tr>\n";
					$message = $message. "</table>\n";
					$message = $message. "$inp_text";

					$message = $message. "</body>\n";
					$message = $message. "</html>\n";


					$headers = "MIME-Version: 1.0" . "\r\n" .
		  			  "Content-type: text/html; charset=iso-8859-1" . "\r\n" .
					    "To: $get_subscription_user_email " . "\r\n" .
					    "From: $configFromEmailSav" . "\r\n" .
					    "Reply-To: $configFromEmailSav" . "\r\n" .
					    'X-Mailer: PHP/' . phpversion();

					if($configMailSendActiveSav == "1"){
						mail($get_subscription_user_email, $subject, $message, $headers);
					}


					// Notification to users
					$inp_notification_reference_id_text = "task_" . $get_current_task_id;
					$inp_notification_reference_id_text_mysql = quote_smart($link, $inp_notification_reference_id_text);

					$inp_notification_url = "$configControlPanelURLSav/index.php?open=dashboard&page=tasks&action=open_task&task_id=$get_current_task_id&editor_language=$editor_language&l=$l";
					$inp_notification_url_mysql = quote_smart($link, $inp_notification_url);
	
					$inp_notification_text = "Task &quot;$inp_title&quot; has been edited by $get_my_user_alias";
					$inp_notification_text_mysql = quote_smart($link, $inp_notification_text);

					$week = date("W");
					$datetime_saying = date("j M Y H:i");

					// Check if notification already exists, if it does, then delete, then insert
					$query = "SELECT notification_id FROM $t_users_notifications WHERE notification_user_id=$get_subscription_user_id AND notification_reference_id_text=$inp_notification_reference_id_text_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_notification_id) = $row;
					if($get_notification_id != ""){
						$result = mysqli_query($link, "DELETE FROM $t_users_notifications WHERE notification_id=$get_notification_id") or die(mysqli_error($link));
					}
					
					mysqli_query($link, "INSERT INTO $t_users_notifications
					(notification_id, notification_user_id, notification_reference_id_text, notification_seen, notification_url, notification_text, notification_datetime, notification_datetime_saying, notification_emailed, notification_week) 
					VALUES 
					(NULL, $get_subscription_user_id, $inp_notification_reference_id_text_mysql, 0, $inp_notification_url_mysql, $inp_notification_text_mysql, '$datetime', '$datetime_saying', 0, $week)")
					or die(mysqli_error($link));

				} // not extra emails
				*/

			} // while

				

			header("Location: index.php?open=dashboard&page=tasks&action=open_task&task_id=$get_current_task_id&ft=success&fm=changes_saved&fm_email=$fm_email");
			exit;
		}


		// Number
		$number = "";
		if($get_current_task_project_incremented_number == "0" OR $get_current_task_project_incremented_number == ""){
			if($get_current_task_system_incremented_number == "0" OR $get_current_task_system_incremented_number == ""){
				$number = "$get_current_task_id";
			}
			else{
				$number = "$get_current_task_system_task_abbr-$get_current_task_system_incremented_number";
			}
		}
		else{
			$number = "$get_current_task_project_task_abbr-$get_current_task_project_incremented_number";
		}


		echo"
		<h1>$number $get_current_task_title</h1>
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
			<a href=\"index.php?open=$open&amp;page=$page&amp;status_code_id=$get_status_code_id&amp;l=$l\">$get_status_code_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_task&amp;task_id=$get_current_task_id&amp;l=$l\">$get_current_task_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_task&amp;task_id=$get_current_task_id&amp;l=$l\">Edit</a>
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


		<!-- Focus -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_title\"]').focus();
		});
		</script>
		<!-- //Focus -->

		<!-- TinyMCE -->
		<script type=\"text/javascript\" src=\"_javascripts/tinymce/tinymce.min.js\"></script>
				<script>
				tinymce.init({
					selector: 'textarea.editor',
					plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern help',
					toolbar: 'formatselect | bold italic strikethrough forecolor backcolor permanentpen formatpainter | link image media pageembed | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment',
					image_advtab: true,
					content_css: [
					],
					link_list: [\n";
						$x = 0;
						$file_picker_callback_file_fullpath = "https://www.google.com/logos/google.jpg";
						$file_picker_callback_file_alt = "My alt text";
						$file_picker_callback_video_fullpath = "https://www.google.com/logos/google.jpg";
						$file_picker_callback_video_alt = "My alt text";
						$query = "SELECT attachment_id, attachment_title, attachment_file_path, attachment_file_name, attachment_file_thumb FROM $t_tasks_attachments WHERE attachment_task_id=$get_current_task_id AND attachment_file_type != 'image' ORDER BY attachment_title ASC";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_attachment_id, $get_attachment_title, $get_attachment_file_path, $get_attachment_file_name, $get_attachment_file_thumb) = $row;


							if($x != "0"){
							echo",
";
							}
							echo"								";
							echo"{ title: '$get_attachment_title', value: '../$get_attachment_file_path/$get_attachment_file_name' }";
								
							// Transfer values to callback
							$file_picker_callback_file_fullpath = "../$get_attachment_file_path/$get_attachment_file_name";
							$file_picker_callback_file_alt = "$get_attachment_title";

							$x++;
						} // while
					echo"
					],
					image_list: [\n";

						$x = 0;
						$file_picker_callback_image_fullpath = "https://www.google.com/logos/google.jpg";
						$file_picker_callback_image_alt = "My alt text";
						$query = "SELECT attachment_id, attachment_title, attachment_file_path, attachment_file_name, attachment_file_thumb FROM $t_tasks_attachments WHERE attachment_task_id=$get_current_task_id AND attachment_file_type='image' ORDER BY attachment_title ASC";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_attachment_id, $get_attachment_title, $get_attachment_file_path, $get_attachment_file_name, $get_attachment_file_thumb) = $row;


							if($x != "0"){
								echo",
";
							}
							echo"								";
							echo"{ title: '$get_attachment_title', value: '../$get_attachment_file_path/$get_attachment_file_name' }";
								
							// Transfer values to callback
							$file_picker_callback_image_fullpath = "../$get_attachment_file_path/$get_attachment_file_name";
							$file_picker_callback_image_alt = "$get_attachment_title";

							$x++;
						} // while

						echo"
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

		<!-- Edit task form -->
			<form method=\"post\" action=\"index.php?open=dashboard&amp;page=$page&amp;action=$action&amp;task_id=$get_current_task_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			<p>Title:<br />
			<input type=\"text\" name=\"inp_title\" value=\"$get_current_task_title\" size=\"25\" style=\"width: 99%;\" />
			</p>

			<p>Text: (<a href=\"index.php?open=dashboard&amp;page=tasks_attachments&amp;task_id=$get_current_task_id&amp;l=$l\" target=\"_blank\">Attachments</a>)<br />
			";
			$my_user_id = $_SESSION['user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);
			$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;
			$date_saying = date("j M Y");
			echo"<textarea name=\"inp_text\" rows=\"10\" cols=\"80\" class=\"editor\">$get_current_task_text
			<p><br /><br /><b>$date_saying $get_my_user_name</b><br />
			-</p></textarea><br />
			</p>


			<p>Status:<br />
			<select name=\"inp_status_code_id\">";
			$query = "SELECT status_code_id, status_code_title FROM $t_tasks_status_codes ORDER BY status_code_weight ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
			list($get_status_code_id, $get_status_code_title) = $row;
				echo"			<option value=\"$get_status_code_id\""; if($get_current_task_status_code_id == "$get_status_code_id"){ echo" selected=\"selected\""; } echo">$get_status_code_title</option>\n";
			}
			echo"
			</select>
			</p>

			<p>Assign to:<br />
			<input type=\"text\" name=\"inp_assigned_to_user_alias\" value=\"$get_current_task_assigned_to_user_alias\" size=\"25\" id=\"assigned_to_user_alias_search_query\" autocomplete=\"off\" />
			</p>
  			<div id=\"assigned_to_user_alias_search_results\"></div>

			<!-- Assign to search script -->
			<script id=\"source\" language=\"javascript\" type=\"text/javascript\">
			\$(document).ready(function () {
				\$('#assigned_to_user_alias_search_query').keyup(function () {
        				// getting the value that user typed
        				var searchString    = \$(\"#assigned_to_user_alias_search_query\").val();
        				// forming the queryString
       					var data            = 'inp_search_query='+ searchString;
         
        				// if searchString is not empty
        				if(searchString) {
           					// ajax call
            					\$.ajax({
                					type: \"POST\",
               						url: \"_inc/dashboard/tasks_search_for_user.php\",
                					data: data,
							beforeSend: function(html) { // this happens before actual call
								\$(\"#assigned_to_user_alias_search_results\").html(''); 
							},
               						success: function(html){
                    						\$(\"#assigned_to_user_alias_search_results\").append(html);
              						}
            					});
       					}
        				return false;
            			});
            		});
			</script>
			<!-- //Assign to search script -->
	


			<p>Priority:<br />
			<select name=\"inp_priority_id\">
				<option value=\"1\""; if($get_current_task_priority_id == "1"){ echo" selected=\"selected\""; } echo">Immediate Priority</option>
				<option value=\"2\""; if($get_current_task_priority_id == "2"){ echo" selected=\"selected\""; } echo">High Priority</option>
				<option value=\"3\""; if($get_current_task_priority_id == "3"){ echo" selected=\"selected\""; } echo">Normal Priority</option>
				<option value=\"4\""; if($get_current_task_priority_id == "4"){ echo" selected=\"selected\""; } echo">Low Priority</option>
				<option value=\"5\""; if($get_current_task_priority_id == "5"){ echo" selected=\"selected\""; } echo">Non-attendance</option>
			</select>
			</p>

			<p>Due:<br />
			<select name=\"inp_due_day\">
				<option value=\"\">- Day -</option>\n";

			$due_day = substr($get_current_task_due_datetime, 8, 2); 
			for($x=1;$x<32;$x++){
				if($x<10){
					$y = 0 . $x;
				}
				else{
					$y = $x;
				}
				echo"<option value=\"$y\""; if($due_day == "$y"){ echo" selected=\"selected\""; } echo">$x</option>\n";
			}
			echo"
			</select>

		<select name=\"inp_due_month\">
			<option value=\"\">- Month -</option>\n";

			$due_month = substr($get_current_task_due_datetime, 5, 2); 

			$month = date("m");
			$l_month_array[0] = "";
			$l_month_array[1] = "$l_january";
			$l_month_array[2] = "$l_february";
			$l_month_array[3] = "$l_march";
			$l_month_array[4] = "$l_april";
			$l_month_array[5] = "$l_may";
			$l_month_array[6] = "$l_june";
			$l_month_array[7] = "$l_juli";
			$l_month_array[8] = "$l_august";
			$l_month_array[9] = "$l_september";
			$l_month_array[10] = "$l_october";
			$l_month_array[11] = "$l_november";
			$l_month_array[12] = "$l_december";
			for($x=1;$x<13;$x++){
				if($x<10){
					$y = 0 . $x;
				}
				else{
					$y = $x;
				}
				echo"<option value=\"$y\""; if($due_month == "$x"){ echo" selected=\"selected\""; } echo">$l_month_array[$x]</option>\n";
			}
		echo"
		</select>

		<select name=\"inp_due_year\">
		<option value=\"\">- Year -</option>\n";
			$due_year = substr($get_current_task_due_datetime, 0, 4); 
			$year = date("Y");
			for($x=0;$x<150;$x++){
				echo"<option value=\"$year\""; if($due_year == "$year"){ echo" selected=\"selected\""; } echo">$year</option>\n";
				$year = $year-1;

			}
			echo"
		</select>
		</p>



		<p>System: <a href=\"index.php?open=$open&amp;page=tasks_systems&amp;action=new_system&amp;l=$l\" target=\"_blank\">New</a><br />";
		if($get_current_task_system_id == "0"){
			echo"<span style=\"color: red;font-weight:bold;\">Warning!</span> <span style=\"color: red;\">No system is selected!<br /> Please select a system in order 
			to make it easier to differentiate between systems.</span><br />\n";
		}

		echo"
		<select name=\"inp_system_id\">
			<option value=\"0\">None</option>\n";
		$x = 0;
		$query = "SELECT system_id, system_title FROM $t_tasks_systems WHERE system_is_active=1 ORDER BY system_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_system_id, $get_system_title) = $row;
			echo"			<option value=\"$get_system_id\""; if($get_system_id == "$get_current_task_system_id"){ echo" selected=\"selected\""; } echo">$get_system_title</option>\n";
		}
		echo"
		</select>
		</p>

		<p>Project: <a href=\"index.php?open=$open&amp;page=tasks_projects&amp;action=new_project&amp;l=$l\" target=\"_blank\">New</a><br />
		<select name=\"inp_project_id\">
			<option value=\"0\">None</option>\n";

		$query = "SELECT project_id, project_title FROM $t_tasks_projects WHERE project_is_active=1 ORDER BY project_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_project_id, $get_project_title) = $row;
			echo"			<option value=\"$get_project_id\""; if($get_project_id == "$get_current_task_project_id"){ echo" selected=\"selected\""; } echo">$get_project_title</option>\n";
		}
		echo"
		</select>
		</p>

		<table>
		 <tr>
		  <td style=\"padding-right: 20px;\">
			<p>Hours expected:<br />
			<input type=\"text\" name=\"inp_hours_planned\" value=\"$get_current_task_hours_planned\" size=\"5\" />
			</p>
		  </td>
		  <td style=\"padding-right: 20px;\">
			<p>Hours used:<br />
			<input type=\"text\" name=\"inp_hours_used\" value=\"$get_current_task_hours_used\" size=\"5\" />
			</p>
		 </tr>
		</table>

		<p><input type=\"submit\" value=\"$l_save\" class=\"btn\" /></p>

		</form>
		<!-- //New task form -->
		";
	}
} // edit task
elseif($action == "edit_task_assigned_to"){
	// Get task
	$task_id_mysql = quote_smart($link, $task_id);
	$query = "SELECT task_id, task_system_task_abbr, task_system_incremented_number, task_project_task_abbr, task_project_incremented_number, task_title, task_text, task_status_code_id, task_priority_id, task_created_datetime, task_created_translated,  task_created_by_user_id, task_created_by_user_alias, task_created_by_user_image, task_created_by_user_email, task_updated_datetime, task_updated_translated, task_due_datetime, task_due_time, task_due_translated, task_assigned_to_user_id, task_assigned_to_user_alias, task_assigned_to_user_image, task_assigned_to_user_email, task_hours_planned, task_hours_used, task_qa_datetime, task_qa_by_user_id, task_qa_by_user_alias, task_qa_by_user_image, task_qa_by_user_email, task_finished_datetime, task_finished_by_user_id, task_finished_by_user_alias, task_finished_by_user_image, task_finished_by_user_email, task_is_archived, task_comments, task_project_id, task_project_part_id, task_system_id, task_system_part_id FROM $t_tasks_index WHERE task_id=$task_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_task_id, $get_current_task_system_task_abbr, $get_current_task_system_incremented_number, $get_current_task_project_task_abbr, $get_current_task_project_incremented_number, $get_current_task_title, $get_current_task_text, $get_current_task_status_code_id, $get_current_task_priority_id, $get_current_task_created_datetime, $get_current_task_created_translated, $get_current_task_created_by_user_id, $get_current_task_created_by_user_alias, $get_current_task_created_by_user_image, $get_current_task_created_by_user_email, $get_current_task_updated_datetime, $get_current_task_updated_translated, $get_current_task_due_datetime, $get_current_task_due_time, $get_current_task_due_translated, $get_current_task_assigned_to_user_id, $get_current_task_assigned_to_user_alias, $get_current_task_assigned_to_user_image, $get_current_task_assigned_to_user_email, $get_current_task_hours_planned, $get_current_task_hours_used, $get_current_task_qa_datetime, $get_current_task_qa_by_user_id, $get_current_task_qa_by_user_alias, $get_current_task_qa_by_user_image, $get_current_task_qa_by_user_email, $get_current_task_finished_datetime, $get_current_task_finished_by_user_id, $get_current_task_finished_by_user_alias, $get_current_task_finished_by_user_image, $get_current_task_finished_by_user_email, $get_current_task_is_archived, $get_current_task_comments, $get_current_task_project_id, $get_current_task_project_part_id, $get_current_task_system_id, $get_current_task_system_part_id) = $row;
	if($get_current_task_id == ""){
		echo"<p>Server error 404</p>";
	}
	else{
		if($process == 1){

			// Assigned to
			$inp_assigned_to_user_id = $_GET['assigned_to_user_id'];
			if($inp_assigned_to_user_id == ""){
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
			

			// Updated
			$datetime = date("Y-m-d H:i:s");
			$inp_updated_translated = date("d");
			$month = date("m");
			$year = date("Y");
			if($month == "01"){
				$inp_updated_translated = $inp_updated_translated . " $l_january";
			}
			elseif($month == "2"){
				$inp_updated_translated = $inp_updated_translated . " $l_february";
			}
			elseif($month == "03"){
				$inp_updated_translated = $inp_updated_translated . " $l_march";
			}
			elseif($month == "04"){
				$inp_updated_translated = $inp_updated_translated . " $l_april";
			}
			elseif($month == "05"){
				$inp_updated_translated = $inp_updated_translated . " $l_may";
			}
			elseif($month == "06"){
				$inp_updated_translated = $inp_updated_translated . " $l_june";
			}
			elseif($month == "07"){
				$inp_updated_translated = $inp_updated_translated . " $l_juli";
			}
			elseif($month == "08"){
				$inp_updated_translated = $inp_updated_translated . " $l_august";
			}
			elseif($month == "09"){
				$inp_updated_translated = $inp_updated_translated . " $l_september";
			}
			elseif($month == "10"){
				$inp_updated_translated = $inp_updated_translated . " $l_october";
			}
			elseif($month == "11"){
				$inp_updated_translated = $inp_updated_translated . " $l_november";
			}
			elseif($month == "12"){
				$inp_updated_translated = $inp_updated_translated . " $l_december";
			}
			$inp_updated_translated = $inp_updated_translated . " $year";
			$inp_updated_translated = output_html($inp_updated_translated);
			$inp_updated_translated_mysql = quote_smart($link, $inp_updated_translated);

			// Update
			$result = mysqli_query($link, "UPDATE $t_tasks_index SET 
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
			$inp_history_summary = "Assigned to $inp_assigned_to_user_name by $inp_updated_by_user_name";
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


			header("Location: index.php?open=dashboard&page=tasks&action=open_task&task_id=$get_current_task_id&ft=success&fm=changes_saved&fm_email=$fm_email");
			exit;
		}
	}
} // edit_task_assigned_to
elseif($action == "edit_task_status"){
	// Get task
	$task_id_mysql = quote_smart($link, $task_id);
	$query = "SELECT task_id, task_system_task_abbr, task_system_incremented_number, task_project_task_abbr, task_project_incremented_number, task_title, task_text, task_status_code_id, task_priority_id, task_created_datetime, task_created_translated,  task_created_by_user_id, task_created_by_user_alias, task_created_by_user_image, task_created_by_user_email, task_updated_datetime, task_updated_translated, task_due_datetime, task_due_time, task_due_translated, task_assigned_to_user_id, task_assigned_to_user_alias, task_assigned_to_user_image, task_assigned_to_user_email, task_hours_planned, task_hours_used, task_qa_datetime, task_qa_by_user_id, task_qa_by_user_alias, task_qa_by_user_image, task_qa_by_user_email, task_finished_datetime, task_finished_by_user_id, task_finished_by_user_alias, task_finished_by_user_image, task_finished_by_user_email, task_is_archived, task_comments, task_project_id, task_project_part_id, task_system_id, task_system_part_id FROM $t_tasks_index WHERE task_id=$task_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_task_id, $get_current_task_system_task_abbr, $get_current_task_system_incremented_number, $get_current_task_project_task_abbr, $get_current_task_project_incremented_number, $get_current_task_title, $get_current_task_text, $get_current_task_status_code_id, $get_current_task_priority_id, $get_current_task_created_datetime, $get_current_task_created_translated, $get_current_task_created_by_user_id, $get_current_task_created_by_user_alias, $get_current_task_created_by_user_image, $get_current_task_created_by_user_email, $get_current_task_updated_datetime, $get_current_task_updated_translated, $get_current_task_due_datetime, $get_current_task_due_time, $get_current_task_due_translated, $get_current_task_assigned_to_user_id, $get_current_task_assigned_to_user_alias, $get_current_task_assigned_to_user_image, $get_current_task_assigned_to_user_email, $get_current_task_hours_planned, $get_current_task_hours_used, $get_current_task_qa_datetime, $get_current_task_qa_by_user_id, $get_current_task_qa_by_user_alias, $get_current_task_qa_by_user_image, $get_current_task_qa_by_user_email, $get_current_task_finished_datetime, $get_current_task_finished_by_user_id, $get_current_task_finished_by_user_alias, $get_current_task_finished_by_user_image, $get_current_task_finished_by_user_email, $get_current_task_is_archived, $get_current_task_comments, $get_current_task_project_id, $get_current_task_project_part_id, $get_current_task_system_id, $get_current_task_system_part_id) = $row;
	if($get_current_task_id == ""){
		echo"<p>Server error 404</p>";
	}
	else{
		if($process == 1){


			$inp_status_code_id = $_GET['status_code_id'];
			$inp_status_code_id = output_html($inp_status_code_id);
			$inp_status_code_id_mysql = quote_smart($link, $inp_status_code_id);

		
			// Update status_code_count_tasks
			$query = "SELECT status_code_id, status_code_title, status_code_text_color, status_code_bg_color, status_code_border_color, status_code_weight, status_code_show_on_board, status_code_on_status_close_task, status_code_count_tasks FROM $t_tasks_status_codes WHERE status_code_id=$inp_status_code_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_status_code_id, $get_status_code_title, $get_status_code_text_color, $get_status_code_bg_color, $get_status_code_border_color, $get_status_code_weight, $get_status_code_show_on_board, $get_status_code_on_status_close_task, $get_status_code_count_tasks) = $row;
			if($get_status_code_id != "$get_current_task_status_code_id"){
				// Update new status code with +1
				$inp_status_code_count_tasks = $get_status_code_count_tasks+1;
				$result = mysqli_query($link, "UPDATE $t_tasks_status_codes SET status_code_count_tasks='$inp_status_code_count_tasks' WHERE status_code_id=$get_status_code_id");

				// Update old status code with -1
				$query = "SELECT status_code_id, status_code_count_tasks FROM $t_tasks_status_codes WHERE status_code_id=$get_current_task_status_code_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_status_code_id, $get_status_code_count_tasks) = $row;
				$inp_status_code_count_tasks = $get_status_code_count_tasks-1;
				$result = mysqli_query($link, "UPDATE $t_tasks_status_codes SET status_code_count_tasks='$inp_status_code_count_tasks' WHERE status_code_id=$get_current_task_status_code_id");

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
			

			// Updated
			$datetime = date("Y-m-d H:i:s");
			$inp_updated_translated = date("d");
			$month = date("m");
			$week = date("W");
			$year = date("Y");
			if($month == "01"){
				$inp_updated_translated = $inp_updated_translated . " $l_january";
				$inp_month_saying = "$l_january";
			}
			elseif($month == "2"){
				$inp_updated_translated = $inp_updated_translated . " $l_february";
				$inp_month_saying = "$l_february";
			}
			elseif($month == "03"){
				$inp_updated_translated = $inp_updated_translated . " $l_march";
				$inp_month_saying = "$l_march";
			}
			elseif($month == "04"){
				$inp_updated_translated = $inp_updated_translated . " $l_april";
				$inp_month_saying = "$l_april";
			}
			elseif($month == "05"){
				$inp_updated_translated = $inp_updated_translated . " $l_may";
				$inp_month_saying = "$l_may";
			}
			elseif($month == "06"){
				$inp_updated_translated = $inp_updated_translated . " $l_june";
				$inp_month_saying = "$l_june";
			}
			elseif($month == "07"){
				$inp_updated_translated = $inp_updated_translated . " $l_juli";
				$inp_month_saying = "$l_juli";
			}
			elseif($month == "08"){
				$inp_updated_translated = $inp_updated_translated . " $l_august";
				$inp_month_saying = "$l_august";
			}
			elseif($month == "09"){
				$inp_updated_translated = $inp_updated_translated . " $l_september";
				$inp_month_saying = "$l_september";
			}
			elseif($month == "10"){
				$inp_updated_translated = $inp_updated_translated . " $l_october";
				$inp_month_saying = "$l_october";
			}
			elseif($month == "11"){
				$inp_updated_translated = $inp_updated_translated . " $l_november";
				$inp_month_saying = "$l_november";
			}
			elseif($month == "12"){
				$inp_updated_translated = $inp_updated_translated . " $l_december";
				$inp_month_saying = "$l_december";
			}
			$inp_updated_translated = $inp_updated_translated . " $year";
			$inp_updated_translated = output_html($inp_updated_translated);
			$inp_updated_translated_mysql = quote_smart($link, $inp_updated_translated);

			$inp_month_saying = output_html($inp_month_saying);
			$inp_month_saying_mysql = quote_smart($link, $inp_month_saying);

			// Update
			$result = mysqli_query($link, "UPDATE $t_tasks_index SET 
							task_status_code_id=$inp_status_code_id_mysql, 
							task_status_code_title=$inp_status_code_title_mysql,
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

			$inp_history_summary_mysql = quote_smart($link, $inp_history_summary);
			mysqli_query($link, "INSERT INTO $t_tasks_history 
			(history_id, history_task_id, history_updated_by_user_id, history_updated_by_user_name, history_updated_by_user_alias, 
			history_updated_by_user_email, history_updated_datetime, history_updated_datetime_saying, history_summary, 
			history_new_status_code_id, history_new_status_code_title) 
			VALUES 
			(NULL, $get_current_task_id, $my_user_id_mysql, $inp_updated_by_user_name_mysql, $inp_updated_by_user_alias_mysql,
			$inp_updated_by_user_email_mysql, '$datetime', '$inp_datetime_saying',  $inp_history_summary_mysql, 
			$inp_history_new_status_code_id_mysql, $inp_history_new_status_code_title_mysql)")
			or die(mysqli_error($link));


			// Make sure that I am a task subscriber
			$query = "SELECT subscription_id FROM $t_tasks_subscriptions WHERE subscription_task_id=$get_current_task_id AND subscription_user_id=$inp_updated_by_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_notification_id) = $row;
			if($get_notification_id == ""){
				mysqli_query($link, "INSERT INTO $t_tasks_subscriptions
				(subscription_id, subscription_task_id, subscription_user_id, subscription_user_email) 
				VALUES 
				(NULL, $get_current_task_id, $inp_updated_by_user_id_mysql, $inp_updated_by_user_email_mysql)")
				or die(mysqli_error($link));
			}


			/*
			// Email to all task subscribers
			$query = "SELECT subscription_id, subscription_task_id, subscription_user_id, subscription_user_email FROM $t_tasks_subscriptions WHERE subscription_task_id=$get_current_task_id";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_subscription_id, $get_subscription_task_id, $get_subscription_user_id, $get_subscription_user_email) = $row;

				if($get_subscription_user_email != "$get_my_user_email"){
					$subject = "Task $get_current_task_title changed status to $inp_history_new_status_code_title at $configWebsiteTitleSav";

					$message = "<html>\n";
					$message = $message. "<head>\n";
					$message = $message. "  <title>$subject</title>\n";

					$message = $message. "  <style type=\"text/css\"></style>\n";
					$message = $message. " </head>\n";
					$message = $message. "<body>\n";
					$message = $message. "<p>The user <a href=\"$configSiteURLSav/users/view_profile.php?user_id=$get_my_user_id&amp;l=$l\">$get_my_user_alias</a> has made changes to the task.</p>\n";
					$message = $message. "<table>\n";
					$message = $message. " <tr>\n";
					$message = $message. "  <td><span>ID:</span></td>\n";
					$message = $message. "  <td><span>$get_current_task_id</span></td>\n";
					$message = $message. " </tr>\n";
					$message = $message. " <tr>\n";
					$message = $message. "  <td><span>Title:</span></td>\n";
					$message = $message. "  <td><span><a href=\"$configControlPanelURLSav/index.php?open=dashboard&amp;page=tasks&amp;action=open_task&amp;task_id=$get_current_task_id&amp;editor_language=$editor_language&amp;l=$l\">$inp_title</a></span></td>\n";
					$message = $message. " </tr>\n";
					$message = $message. " <tr>\n";
					$message = $message. "  <td><span>Due:</span></td>\n";
					$message = $message. "  <td><span>$inp_due_translated</span></td>\n";
					$message = $message. " </tr>\n";
					$message = $message. " <tr>\n";
					$message = $message. "  <td><span>Priority:</span></td>\n";
					$message = $message. "  <td><span>$inp_priority_id</span></td>\n";
					$message = $message. " </tr>\n";
					$message = $message. " <tr>\n";
					$message = $message. "  <td><span>Status:</span></td>\n";
					$message = $message. "  <td><span>$inp_history_new_status_code_id $inp_history_new_status_code_title</span></td>\n";
					$message = $message. " </tr>\n";
					$message = $message. "</table>\n";
					$message = $message. "$inp_text";

					$message = $message. "</body>\n";
					$message = $message. "</html>\n";


					$headers = "MIME-Version: 1.0" . "\r\n" .
		  			  "Content-type: text/html; charset=iso-8859-1" . "\r\n" .
					    "To: $get_subscription_user_email " . "\r\n" .
					    "From: $configFromEmailSav" . "\r\n" .
					    "Reply-To: $configFromEmailSav" . "\r\n" .
					    'X-Mailer: PHP/' . phpversion();

					if($configMailSendActiveSav == "1"){
						mail($get_subscription_user_email, $subject, $message, $headers);
					}


					// Notification to users
					$inp_notification_reference_id_text = "task_" . $get_current_task_id;
					$inp_notification_reference_id_text_mysql = quote_smart($link, $inp_notification_reference_id_text);

					$inp_notification_url = "$configControlPanelURLSav/index.php?open=dashboard&page=tasks&action=open_task&task_id=$get_current_task_id&editor_language=$editor_language&l=$l";
					$inp_notification_url_mysql = quote_smart($link, $inp_notification_url);
	
					$inp_notification_text = "Task &quot;$inp_title&quot; has been edited by $get_my_user_alias";
					$inp_notification_text_mysql = quote_smart($link, $inp_notification_text);

					$week = date("W");
					$datetime_saying = date("j M Y H:i");

					// Check if notification already exists, if it does, then delete, then insert
					$query = "SELECT notification_id FROM $t_users_notifications WHERE notification_user_id=$get_subscription_user_id AND notification_reference_id_text=$inp_notification_reference_id_text_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_notification_id) = $row;
					if($get_notification_id != ""){
						$result = mysqli_query($link, "DELETE FROM $t_users_notifications WHERE notification_id=$get_notification_id") or die(mysqli_error($link));
					}
					
					mysqli_query($link, "INSERT INTO $t_users_notifications
					(notification_id, notification_user_id, notification_reference_id_text, notification_seen, notification_url, notification_text, notification_datetime, notification_datetime_saying, notification_emailed, notification_week) 
					VALUES 
					(NULL, $get_subscription_user_id, $inp_notification_reference_id_text_mysql, 0, $inp_notification_url_mysql, $inp_notification_text_mysql, '$datetime', '$datetime_saying', 0, $week)")
					or die(mysqli_error($link));

				} // not extra emails

			} // while
			*/
			
			// Is the new status "finished"?
			if($get_status_code_on_status_close_task == "1"){
				$result = mysqli_query($link, "UPDATE $t_tasks_index SET
							task_finished_is_finished=1, 
							task_finished_datetime='$datetime', 
							task_finished_year=$year,
							task_finished_month=$month,
							task_finished_month_saying=$inp_month_saying_mysql,
							task_finished_week=$week,
							task_finished_by_user_id=$my_user_id_mysql,
							task_finished_by_user_name=$inp_updated_by_user_name_mysql, 
							task_finished_by_user_alias=$inp_updated_by_user_alias_mysql,
							task_finished_by_user_image=$inp_updated_by_user_image_mysql, 
							task_finished_by_user_thumb_40=$inp_updated_by_user_thumb_a_mysql, 
							task_finished_by_user_thumb_50=$inp_updated_by_user_thumb_b_mysql, 
							task_finished_by_user_email=$inp_updated_by_user_email_mysql
							 WHERE task_id=$get_current_task_id") or die(mysqli_error($link));
			}

			header("Location: index.php?open=dashboard&page=tasks&action=open_task&task_id=$get_current_task_id&ft=success&fm=changes_saved");
			exit;
		}
	}
} // change_task_status
elseif($action == "delete_task"){
	// Get task
	$task_id_mysql = quote_smart($link, $task_id);
	$query = "SELECT task_id, task_title, task_text, task_status_code_id, task_priority_id, task_created_datetime, task_created_translated,  task_created_by_user_id, task_created_by_user_alias, task_created_by_user_image, task_created_by_user_email, task_updated_datetime, task_updated_translated, task_due_datetime, task_due_time, task_due_translated, task_assigned_to_user_id, task_assigned_to_user_alias, task_assigned_to_user_image, task_assigned_to_user_email, task_qa_datetime, task_qa_by_user_id, task_qa_by_user_alias, task_qa_by_user_image, task_qa_by_user_email, task_finished_datetime, task_finished_by_user_id, task_finished_by_user_alias, task_finished_by_user_image, task_finished_by_user_email, task_is_archived, task_comments, task_project_id, task_project_part_id, task_system_id, task_system_part_id FROM $t_tasks_index WHERE task_id=$task_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_task_id, $get_current_task_title, $get_current_task_text, $get_current_task_status_code_id, $get_current_task_priority_id, $get_current_task_created_datetime, $get_current_task_created_translated, $get_current_task_created_by_user_id, $get_current_task_created_by_user_alias, $get_current_task_created_by_user_image, $get_current_task_created_by_user_email, $get_current_task_updated_datetime, $get_current_task_updated_translated, $get_current_task_due_datetime, $get_current_task_due_time, $get_current_task_due_translated, $get_current_task_assigned_to_user_id, $get_current_task_assigned_to_user_alias, $get_current_task_assigned_to_user_image, $get_current_task_assigned_to_user_email, $get_current_task_qa_datetime, $get_current_task_qa_by_user_id, $get_current_task_qa_by_user_alias, $get_current_task_qa_by_user_image, $get_current_task_qa_by_user_email, $get_current_task_finished_datetime, $get_current_task_finished_by_user_id, $get_current_task_finished_by_user_alias, $get_current_task_finished_by_user_image, $get_current_task_finished_by_user_email, $get_current_task_is_archived, $get_current_task_comments, $get_current_task_project_id, $get_current_task_project_part_id, $get_current_task_system_id, $get_current_task_system_part_id) = $row;
	if($get_current_task_id == ""){
		echo"<p>Server error 404</p>";
	}
	else{	if($process == 1){

		

			// Delete
			$result = mysqli_query($link, "DELETE FROM $t_tasks_index WHERE task_id=$get_current_task_id") or die(mysqli_error($link));

			// Delete read
			$result = mysqli_query($link, "DELETE FROM $t_tasks_read WHERE read_task_id='$get_current_task_id'");

			// Update status code with -1
			$query = "SELECT status_code_id, status_code_count_tasks FROM $t_tasks_status_codes WHERE status_code_id=$get_current_task_status_code_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_status_code_id, $get_status_code_count_tasks) = $row;
			$inp_status_code_count_tasks = $get_status_code_count_tasks-1;
			$result = mysqli_query($link, "UPDATE $t_tasks_status_codes SET status_code_count_tasks='$inp_status_code_count_tasks' WHERE status_code_id=$get_current_task_status_code_id");


			header("Location: index.php?open=dashboard&page=tasks&status_code_id=$get_current_task_status_code_id&ft=success&fm=task_deleted");
			exit;
		}
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
			<a href=\"index.php?open=$open&amp;page=$page&amp;status_code_id=$get_status_code_id&amp;l=$l\">$get_status_code_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_task&amp;task_id=$get_current_task_id&amp;l=$l\">$get_current_task_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_task&amp;task_id=$get_current_task_id&amp;l=$l\">Delete</a>
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


		
		<!-- Delete task form -->
			<p>
			Are you sure you want to delete the task?
			</p>

			<p>
			<a href=\"index.php?open=dashboard&amp;page=$page&amp;action=$action&amp;task_id=$get_current_task_id&amp;l=$l&amp;process=1\" class=\"btn_danger\">Delete</a>
			</p>
		<!-- //Delete task form -->
		";
	}
} // delete task
elseif($action == "archive_task"){
	// Get task
	$task_id_mysql = quote_smart($link, $task_id);
	$query = "SELECT task_id, task_title, task_text, task_status_code_id, task_priority_id, task_created_datetime, task_created_translated,  task_created_by_user_id, task_created_by_user_alias, task_created_by_user_image, task_created_by_user_email, task_updated_datetime, task_updated_translated, task_due_datetime, task_due_time, task_due_translated, task_assigned_to_user_id, task_assigned_to_user_alias, task_assigned_to_user_image, task_assigned_to_user_email, task_qa_datetime, task_qa_by_user_id, task_qa_by_user_alias, task_qa_by_user_image, task_qa_by_user_email, task_finished_datetime, task_finished_by_user_id, task_finished_by_user_alias, task_finished_by_user_image, task_finished_by_user_email, task_is_archived, task_comments, task_project_id, task_project_part_id, task_system_id, task_system_part_id FROM $t_tasks_index WHERE task_id=$task_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_task_id, $get_current_task_title, $get_current_task_text, $get_current_task_status_code_id, $get_current_task_priority_id, $get_current_task_created_datetime, $get_current_task_created_translated, $get_current_task_created_by_user_id, $get_current_task_created_by_user_alias, $get_current_task_created_by_user_image, $get_current_task_created_by_user_email, $get_current_task_updated_datetime, $get_current_task_updated_translated, $get_current_task_due_datetime, $get_current_task_due_time, $get_current_task_due_translated, $get_current_task_assigned_to_user_id, $get_current_task_assigned_to_user_alias, $get_current_task_assigned_to_user_image, $get_current_task_assigned_to_user_email, $get_current_task_qa_datetime, $get_current_task_qa_by_user_id, $get_current_task_qa_by_user_alias, $get_current_task_qa_by_user_image, $get_current_task_qa_by_user_email, $get_current_task_finished_datetime, $get_current_task_finished_by_user_id, $get_current_task_finished_by_user_alias, $get_current_task_finished_by_user_image, $get_current_task_finished_by_user_email, $get_current_task_is_archived, $get_current_task_comments, $get_current_task_project_id, $get_current_task_project_part_id, $get_current_task_system_id, $get_current_task_system_part_id) = $row;
	if($get_current_task_id == ""){
		echo"<p>Server error 404</p>";
	}
	else{	if($process == 1){

		

			// Set archived
			$result = mysqli_query($link, "UPDATE $t_tasks_index SET task_is_archived='1' WHERE task_id=$get_current_task_id") or die(mysqli_error($link));

			// Update status code with -1
			$query = "SELECT status_code_id, status_code_count_tasks FROM $t_tasks_status_codes WHERE status_code_id=$get_current_task_status_code_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_status_code_id, $get_status_code_count_tasks) = $row;
			$inp_status_code_count_tasks = $get_status_code_count_tasks-1;
			$result = mysqli_query($link, "UPDATE $t_tasks_status_codes SET status_code_count_tasks='$inp_status_code_count_tasks' WHERE status_code_id=$get_current_task_status_code_id");


			header("Location: index.php?open=dashboard&page=tasks&status_code_id=$get_current_task_status_code_id&ft=success&fm=task_archived");
			exit;
		}
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
			<a href=\"index.php?open=$open&amp;page=$page&amp;status_code_id=$get_status_code_id&amp;l=$l\">$get_status_code_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_task&amp;task_id=$get_current_task_id&amp;l=$l\">$get_current_task_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_task&amp;task_id=$get_current_task_id&amp;l=$l\">Delete</a>
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


		
		<!-- Archive task form -->
			<p>
			Are you sure you want to archive the task?
			</p>

			<p>
			<a href=\"index.php?open=dashboard&amp;page=$page&amp;action=$action&amp;task_id=$get_current_task_id&amp;l=$l&amp;process=1\" class=\"btn_success\">Archive</a>
			</p>
		<!-- //Archive task form -->
		";
	}
} // archive_task
?>