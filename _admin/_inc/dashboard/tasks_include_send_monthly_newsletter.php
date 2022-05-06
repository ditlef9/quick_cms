<?php
/**
*
* File: _admin/_inc/tasks_include_send_monthly_newsletter.php
* Version 1.0.0
* Date 21:19 22.03.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables ---------------------------------------------------------------------------- */
$t_tasks_user_subscription_selections	= $mysqlPrefixSav . "tasks_user_subscription_selections";

/*- Variables  ---------------------------------------------------- */
$month = date("m");
$year = date("Y");
$month_year_saying = date("M Y");
$time = time();
$datetime = date("Y-m-d H:i:s");

// Check when last time we sendt e-mail was
$query = "SELECT selection_id, selection_user_id, selection_user_email, selection_subscribe_to_new_tasks, selection_subscribe_to_monthly_newsletter, selection_unsubscribe_code, selection_last_sendt_monthly_newsletter_month, selection_last_sendt_datetime, selection_last_sendt_time FROM $t_tasks_user_subscription_selections WHERE selection_subscribe_to_monthly_newsletter=1 AND selection_last_sendt_monthly_newsletter_month!= $month LIMIT 0,1";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_selection_id, $get_selection_user_id, $get_selection_user_email, $get_selection_subscribe_to_new_tasks, $get_selection_subscribe_to_monthly_newsletter, $get_selection_unsubscribe_code, $get_selection_last_sendt_monthly_newsletter_month, $get_selection_last_sendt_datetime, $get_selection_last_sendt_time) = $row;

if($get_selection_id != ""){
	// Last month and year
	$last_month = $month-1;
	$last_year = $year;
	if($last_month == "0"){
		$last_month = "12";
		$last_year = $year-1;
	}
	if($last_month == "01" OR $last_month == "1"){
		$last_month_short_saying = "Jan";
	}
	elseif($last_month == "02" OR $last_month == "2"){
		$last_month_short_saying = "Feb";
	}
	elseif($last_month == "03" OR $last_month == "3"){
		$last_month_short_saying = "Mar";
	}
	elseif($last_month == "04" OR $last_month == "4"){
		$last_month_short_saying = "Apr";
	}
	elseif($last_month == "05" OR $last_month == "5"){
		$last_month_short_saying = "May";
	}
	elseif($last_month == "06" OR $last_month == "6"){
		$last_month_short_saying = "Jun";
	}
	elseif($last_month == "07" OR $last_month == "7"){
		$last_month_short_saying = "Jul";
	}
	elseif($last_month == "08" OR $last_month == "8"){
		$last_month_short_saying = "Aug";
	}
	elseif($last_month == "09" OR $last_month == "9"){
		$last_month_short_saying = "Sep";
	}
	elseif($last_month == "10"){
		$last_month_short_saying = "Oct";
	}
	elseif($last_month == "11"){
		$last_month_short_saying = "Nov";
	}
	else{
		$last_month_short_saying = "Dec";
	}
	
	// Between
	$between_datetime_from = "$last_year-$last_month-01 00:00:00";
	$between_datetime_from_mysql = quote_smart($link, $between_datetime_from);

	$days_in_month = cal_days_in_month(CAL_GREGORIAN, $last_month, $last_year); // 31
	$between_datetime_to = "$last_year-$last_month-$days_in_month 00:00:00";
	$between_datetime_to_mysql = quote_smart($link, $between_datetime_to);

	// Count :: Number of open tasks
	$query = "SELECT count(task_id) FROM $t_tasks_index WHERE task_finished_is_finished=0 AND task_is_archived=0";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_count_number_of_open_tasks) = $row;

	// Count :: Number of solved tasks last month
	$query = "SELECT count(task_id) FROM $t_tasks_index WHERE task_finished_is_finished=1 AND task_finished_year=$last_year AND task_finished_month=$last_month";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_count_number_of_tasks_solved_last_month) = $row;

	// Count :: Number of created tasks last month
	$query = "SELECT count(task_id) FROM $t_tasks_index WHERE task_created_datetime > $between_datetime_from_mysql AND task_created_datetime < $between_datetime_to_mysql AND task_finished_is_finished=0 AND task_is_archived=0";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_count_number_of_tasks_created_last_month) = $row;

	// Logo
	include("_data/logo.php");

	$subject = "Monthly task newsletter for $month_year_saying | $configWebsiteTitleSav";
	
	$message = "<html>\n";
	$message = $message. "<head>\n";
	$message = $message. "  <title>$subject</title>\n";

	$message = $message. "  <style type=\"text/css\">\n";
	$message = $message. "/*- Head ------------------------------------------------ */\n";
	$message = $message. "  div.logo {\n";
	$message = $message. "   text-align: center;\n";
	$message = $message. "  }\n";
	$message = $message. "/*- Hor zebra ------------------------------------------------ */\n";
	$message = $message. "  table.hor-zebra {\n";
	$message = $message. "    width: 100%;\n";
	$message = $message. "    text-align: left;\n";
	$message = $message. "    border-spacing:0;\n";
	$message = $message. "    border: #cccccc 1px solid;\n";
	$message = $message. "  }\n";

	$message = $message. "  table.hor-zebra>thead {\n";
	$message = $message. "    border-top: #cccccc 1px solid;\n";
	$message = $message. "  }\n";

	$message = $message. "  table.hor-zebra>thead>tr>th {\n";
	$message = $message. "  	background: #e2e2e2;\n";
	$message = $message. "  	border-top: #ffffff 1px solid;\n";
	$message = $message. "  	border-bottom: #cccccc 1px solid;\n";
	$message = $message. "  	padding: 4px;\n";
	$message = $message. "  	color: #000;\n";
	$message = $message. "  }\n";

	$message = $message. "  table.hor-zebra>tbody>tr>td {\n";
	$message = $message. "  	background: #f3f3f3;\n";
	$message = $message. "  	border-bottom: #cccccc 1px solid;\n";
	$message = $message. "  	padding: 8px 4px 8px 4px;\n";
	$message = $message. "  }\n";

	$message = $message. "  table.hor-zebra>tbody>tr>td.odd {\n";
	$message = $message. "  	background: #f8f8f8;\n";
	$message = $message. "  	border-bottom: #cccccc 1px solid;\n";
	$message = $message. "  }\n";
	$message = $message. "  table.hor-zebra>tbody>tr>td.important {\n";
	$message = $message. "  	background: #fff7e5;\n";
	$message = $message. "  	border-bottom: #eabc63 1px solid;\n";
	$message = $message. "  }\n";
	$message = $message. "  table.hor-zebra>tbody>tr>td.danger {\n";
	$message = $message. "  	background: #ffe7e5;\n";
	$message = $message. "  	border-bottom: #ff4940 1px solid;\n";
	$message = $message. "  	border-top: #ff4940 1px solid;\n";
	$message = $message. "  }\n";

	$message = $message. "  table.hor-zebra>tbody>tr:hover td {\n";
	$message = $message. "  	background: #faf4f2;\n";
	$message = $message. "  }\n";


	$message = $message. "  div.task_content_info{\n";
	$message = $message. "  	float: right;\n";
	$message = $message. "  }\n";
	$message = $message. "  div.task_content_info > span{\n";
	$message = $message. "  	color: #d6685d;\n";
	$message = $message. "  	font-weight: bold;\n";
	$message = $message. "  }\n";

	$message = $message. "/*- Flex ------------------------------------------------ */\n";
	$message = $message. "div.flex_row {\n";
	$message = $message. "	display: flex;\n";
	$message = $message. "}\n";
	$message = $message. "div.flex_row > div.flex_col {\n";
	$message = $message. "	flex: 1;\n";
	$message = $message. "	background: #fff;\n";
	$message = $message. "	border-radius: 5px;\n";
	$message = $message. "	border: #eaeef2 1px solid;\n";
	$message = $message. "	box-shadow: 0 2px 3px rgba(0,0,0,0.03);\n";
	$message = $message. "	padding: 10px 10px 10px 10px;\n";
	$message = $message. "	margin: 0px 20px 20px 20px;\n";
	$message = $message. "	text-align: center;\n";
	$message = $message. "}\n";
	$message = $message. "div.flex_row > div.flex_col > h2{\n";
	$message = $message. "	font-size: 22px;\n";
	$message = $message. "	padding: 0px 0px 0px 0px;\n";
	$message = $message. "	margin: 0px 0px 0px 0px;\n";
	$message = $message. "}\n";

	$message = $message. "div.flex_row > div.flex_col > p{\n";
	$message = $message. "	font-size: 14px;\n";
	$message = $message. "	padding: 0px 0px 0px 0px;\n";
	$message = $message. "	margin: 0px 0px 0px 0px;\n";
	$message = $message. "}\n";

	$message = $message. "  </style>\n";

	$message = $message. " </head>\n";
	$message = $message. "<body>\n";

	$message = $message. "<div class=\"logo\">\n";
	$message = $message. "	<a href=\"$configSiteURLSav\"><img src=\"$configSiteURLSav/$logoPathSav/$logoFileEmailSav\" alt=\"$logoFileEmailSav\" /></a>\n";
	$message = $message. "</div>\n";
	$message = $message. "<h2>Quick Facts</h2>\n";
	$message = $message. "<div class=\"flex_row\">\n";
	$message = $message. "	<div class=\"flex_col\">\n";
	$message = $message. "		<h2>$get_count_number_of_open_tasks</h2>\n";
	$message = $message. "		<p>tasks open now</p>\n";
	$message = $message. "	</div>\n";
	$message = $message. "	<div class=\"flex_col\">\n";
	$message = $message. "		<h2>$get_count_number_of_tasks_created_last_month</h2>\n";
	$message = $message. "		<p>created last month</p>\n";
	$message = $message. "	</div>\n";
	$message = $message. "	<div class=\"flex_col\">\n";
	$message = $message. "		<h2>$get_count_number_of_tasks_solved_last_month</h2>\n";
	$message = $message. "		<p>solved last month</p>\n";
	$message = $message. "	</div>\n";
	$message = $message. "</div>\n";


	$message = $message. "<h2>Your Tasks</h2>\n";
	$message = $message. "<p>Here are a list of tasks that are assigned to you.</p>\n";
	$message = $message. "<table class=\"hor-zebra\">\n";
	$message = $message. " <thead>\n";
	$message = $message. "  <tr>\n";
	$message = $message. "   <th>\n";
	$message = $message. "		<span>ID</span>\n";
	$message = $message. "   </th>\n";
	$message = $message. "   <th>\n";
	$message = $message. "		<span>Title</span>\n";
	$message = $message. "   </th>\n";
	$message = $message. "  </tr>\n";
	$message = $message. " </thead>\n";
	$message = $message. " <tbody>\n";


	// Fetch tasks thats not done
	$count_tasks = 0;
	$query = "SELECT task_id, task_system_task_abbr, task_system_incremented_number, task_project_task_abbr, task_project_incremented_number, task_title, task_text, task_status_code_id, task_priority_id, task_priority_weight, task_created_datetime, task_created_by_user_id, task_created_by_user_alias, task_created_by_user_image, task_created_by_user_email, task_updated_datetime, task_due_datetime, task_due_time, task_due_translated, task_assigned_to_user_id, task_assigned_to_user_alias, task_assigned_to_user_image, task_assigned_to_user_thumb_40, task_assigned_to_user_email, task_qa_datetime, task_qa_by_user_id, task_qa_by_user_alias, task_qa_by_user_image, task_qa_by_user_email, task_finished_datetime, task_finished_by_user_id, task_finished_by_user_alias, task_finished_by_user_image, task_finished_by_user_email, task_is_archived, task_comments, task_project_id, task_project_part_id, task_system_id, task_system_part_id FROM $t_tasks_index ";
	$query = $query . "WHERE task_assigned_to_user_id=$get_selection_user_id AND task_finished_is_finished=0 AND task_is_archived='0' ORDER BY task_priority_id, task_id ASC";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_task_id, $get_task_system_task_abbr, $get_task_system_incremented_number, $get_task_project_task_abbr, $get_task_project_incremented_number, $get_task_title, $get_task_text, $get_task_status_code_id, $get_task_priority_id, $get_task_priority_weight, $get_task_created_datetime, $get_task_created_by_user_id, $get_task_created_by_user_alias, $get_task_created_by_user_image, $get_task_created_by_user_email, $get_task_updated_datetime, $get_task_due_datetime, $get_task_due_time, $get_task_due_translated, $get_task_assigned_to_user_id, $get_task_assigned_to_user_alias, $get_task_assigned_to_user_image, $get_task_assigned_to_user_thumb_40, $get_task_assigned_to_user_email, $get_task_qa_datetime, $get_task_qa_by_user_id, $get_task_qa_by_user_alias, $get_task_qa_by_user_image, $get_task_qa_by_user_email, $get_task_finished_datetime, $get_task_finished_by_user_id, $get_task_finished_by_user_alias, $get_task_finished_by_user_image, $get_task_finished_by_user_email, $get_task_is_archived, $get_task_comments, $get_task_project_id, $get_task_project_part_id, $get_task_system_id, $get_task_system_part_id) = $row;
			
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
		$message = $message. " <tr>\n";
		$message = $message. "  <td"; 
		if($get_task_priority_weight == "1"){ 
			$message = $message. " class=\"danger\""; 
		} 
		elseif($get_task_priority_weight == "2"){ 
			$message = $message. " class=\"important\""; 
		} 
		$message = $message. ">\n";
		$message = $message. "		<span><a href=\"$configControlPanelURLSav/index.php?open=dashboard&amp;page=tasks&amp;action=open_task&amp;task_id=$get_task_id\">$number</a></span>\n";
		$message = $message. "  </td>\n";
		$message = $message. "  <td"; 
		if($get_task_priority_weight == "1"){ 
			$message = $message. " class=\"danger\""; 
		} 
		elseif($get_task_priority_weight == "2"){ 
			$message = $message. " class=\"important\""; 
		} 
		$message = $message. ">\n";
		$message = $message. "		<span><a href=\"$configControlPanelURLSav/index.php?open=dashboard&amp;page=tasks&amp;action=open_task&amp;task_id=$get_task_id\">$get_task_title</a></span>\n";
		
		if($time > $get_task_due_time){
			$message = $message . "<div class=\"task_content_info\">
				<span>$get_task_due_translated</span>
			</div>\n";
		}
		$message = $message. "  </td>\n";
		$message = $message. " </tr>\n";
		

		$count_tasks++;
	} // while tasks
	$message = $message. " </tbody>\n";
	$message = $message. "</table>\n";
	$message = $message. "<hr />";


	// Fetched solved tasks last month
	$message = $message. "<h2>Solved tasks in $last_month_short_saying $last_year</h2>\n";
	$message = $message. "<p>Here are a list of tasks that where finished last month.</p>\n";
	$message = $message. "<table class=\"hor-zebra\">\n";
	$message = $message. " <thead>\n";
	$message = $message. "  <tr>\n";
	$message = $message. "   <th>\n";
	$message = $message. "		<span>ID</span>\n";
	$message = $message. "   </th>\n";
	$message = $message. "   <th>\n";
	$message = $message. "		<span>Title</span>\n";
	$message = $message. "   </th>\n";
	$message = $message. "   <th>\n";
	$message = $message. "		<span>Finished by</span>\n";
	$message = $message. "   </th>\n";
	$message = $message. "  </tr>\n";
	$message = $message. " </thead>\n";
	$message = $message. " <tbody>\n";
	$count_tasks = 0;
	$query = "SELECT task_id, task_system_task_abbr, task_system_incremented_number, task_project_task_abbr, task_project_incremented_number, task_title, task_text, task_status_code_id, task_priority_id, task_priority_weight, task_created_datetime, task_created_by_user_id, task_finished_by_user_name, task_created_by_user_alias, task_created_by_user_image, task_created_by_user_email, task_updated_datetime, task_due_datetime, task_due_time, task_due_translated, task_assigned_to_user_id, task_assigned_to_user_alias, task_assigned_to_user_image, task_assigned_to_user_thumb_40, task_assigned_to_user_email, task_qa_datetime, task_qa_by_user_id, task_qa_by_user_alias, task_qa_by_user_image, task_qa_by_user_email, task_finished_datetime, task_finished_by_user_id, task_finished_by_user_alias, task_finished_by_user_image, task_finished_by_user_email, task_is_archived, task_comments, task_project_id, task_project_part_id, task_system_id, task_system_part_id FROM $t_tasks_index ";
	$query = $query . "WHERE task_finished_is_finished=1 AND task_finished_year=$last_year AND task_finished_month=$last_month ORDER BY task_priority_id, task_id ASC";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_task_id, $get_task_system_task_abbr, $get_task_system_incremented_number, $get_task_project_task_abbr, $get_task_project_incremented_number, $get_task_title, $get_task_text, $get_task_status_code_id, $get_task_priority_id, $get_task_priority_weight, $get_task_created_datetime, $get_task_created_by_user_id, $get_task_finished_by_user_name, $get_task_created_by_user_alias, $get_task_created_by_user_image, $get_task_created_by_user_email, $get_task_updated_datetime, $get_task_due_datetime, $get_task_due_time, $get_task_due_translated, $get_task_assigned_to_user_id, $get_task_assigned_to_user_alias, $get_task_assigned_to_user_image, $get_task_assigned_to_user_thumb_40, $get_task_assigned_to_user_email, $get_task_qa_datetime, $get_task_qa_by_user_id, $get_task_qa_by_user_alias, $get_task_qa_by_user_image, $get_task_qa_by_user_email, $get_task_finished_datetime, $get_task_finished_by_user_id, $get_task_finished_by_user_alias, $get_task_finished_by_user_image, $get_task_finished_by_user_email, $get_task_is_archived, $get_task_comments, $get_task_project_id, $get_task_project_part_id, $get_task_system_id, $get_task_system_part_id) = $row;
			
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
		$message = $message. " <tr>\n";
		$message = $message. "  <td"; 
		if($get_task_priority_weight == "1"){ 
			$message = $message. " class=\"danger\""; 
		} 
		elseif($get_task_priority_weight == "2"){ 
			$message = $message. " class=\"important\""; 
		} 
		$message = $message. ">\n";
		$message = $message. "		<span><a href=\"$configControlPanelURLSav/index.php?open=dashboard&amp;page=tasks&amp;action=open_task&amp;task_id=$get_task_id\">$number</a></span>\n";
		$message = $message. "  </td>\n";
		$message = $message. "  <td"; 
		if($get_task_priority_weight == "1"){ 
			$message = $message. " class=\"danger\""; 
		} 
		elseif($get_task_priority_weight == "2"){ 
			$message = $message. " class=\"important\""; 
		} 
		$message = $message. ">\n";
		$message = $message. "		<span><a href=\"$configControlPanelURLSav/index.php?open=dashboard&amp;page=tasks&amp;action=open_task&amp;task_id=$get_task_id\">$get_task_title</a></span>\n";
		
		if($time > $get_task_due_time){
			$message = $message . "<div class=\"task_content_info\">
				<span>$get_task_due_translated</span>
			</div>\n";
		}
		$message = $message. "  </td>\n";

		$message = $message. "  <td"; 
		if($get_task_priority_weight == "1"){ 
			$message = $message. " class=\"danger\""; 
		} 
		elseif($get_task_priority_weight == "2"){ 
			$message = $message. " class=\"important\""; 
		} 
		$message = $message. ">\n";
		$message = $message. "		<span><a href=\"$configSiteURLSav/users/view_profile.php?user_id=$get_task_finished_by_user_id\">$get_task_finished_by_user_name</a></span>\n";
		$message = $message. "  </td>\n";

		$message = $message. " </tr>\n";
		$count_tasks++;
	} // while tasks


	$message = $message. " </tbody>\n";
	$message = $message. "</table>\n";
	$message = $message. "<hr />";
	$message = $message. "<p>No longer want montly updates? Then you can ";
	$message = $message. "<a href=\"$configControlPanelURLSav/_inc/dashboard/tasks_subscriptions.php?user_id=$get_selection_user_id&amp;unsubscribe_code=$get_selection_unsubscribe_code\">unsubscribe</a>.\n";
	$message = $message. "</p>";
	$message = $message. "";
	$message = $message. "<p>Regards,<br />";
	$message = $message. "$configWebsiteWebmasterSav<br />\n";
	$message = $message. "$configWebsiteWebmasterEmailSav<br />\n";
	$message = $message. "<a href=\"$configSiteURLSav\">$configSiteURLSav</a>\n";
	$message = $message. "</p>";


	
	$message = $message. "</body>\n";
	$message = $message. "</html>\n";

	$headers = "MIME-Version: 1.0" . "\r\n" .
  		  "Content-type: text/html; charset=iso-8859-1" . "\r\n" .
		    "To: $get_selection_user_email " . "\r\n" .
		    "From: $configFromEmailSav" . "\r\n" .
		    "Reply-To: $configFromEmailSav" . "\r\n" .
		    'X-Mailer: PHP/' . phpversion();

	if($configMailSendActiveSav == "1" && $count_tasks != "0"){
		mail($get_selection_user_email, $subject, $message, $headers);
	
		echo"
		<div class=\"info\"><p>Sending monthly newsletter to $get_selection_user_email:</p>
			<p><b>Subject:</b>  $subject<br />
			<b>Message:</b></p> $message </div>
		";

	}
	elseif($configMailSendActiveSav == "1" && $count_tasks == "0"){
	
		echo"
		<div class=\"info\"><p>No need to send monthly newsletter email to $get_selection_user_email because there are no tasks:</p>
			<p><b>Subject:</b>  $subject<br />
			<b>Message:</b></p> $message </div>
		";

	}
	
	// Update
	mysqli_query($link, "UPDATE $t_tasks_user_subscription_selections SET
					selection_last_sendt_monthly_newsletter_month=$month,
					selection_last_sendt_datetime='$datetime',
					selection_last_sendt_time='$time'
					WHERE selection_id=$get_selection_id")
					or die(mysqli_error($link));

} // no last found
?>