<?php
if(isset($_SESSION['admin_user_id'])){


	$t_tasks_index  		= $mysqlPrefixSav . "tasks_index";


	mysqli_query($link,"DROP TABLE IF EXISTS $t_tasks_index") or die(mysqli_error());


$query = "SELECT * FROM $t_tasks_index LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_tasks_index(
	   task_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(task_id), 
	   task_system_task_abbr VARCHAR(200),
	   task_system_incremented_number INT,
	   task_project_task_abbr VARCHAR(200),
	   task_project_incremented_number INT,
	   task_title VARCHAR(200),
	   task_text TEXT,
	   task_solution TEXT,
	   task_qa TEXT,
	   task_status_code_id INT,
	   task_status_code_title VARCHAR(200),
	   task_priority_id INT,
	   task_priority_weight INT,
	   task_created_datetime DATETIME,
	   task_created_translated VARCHAR(200),
	   task_created_by_user_id INT,
	   task_created_by_user_name VARCHAR(200),
	   task_created_by_user_alias VARCHAR(200),
	   task_created_by_user_image VARCHAR(200),
	   task_created_by_user_thumb_40 VARCHAR(200),
	   task_created_by_user_thumb_50 VARCHAR(200),
	   task_created_by_user_email VARCHAR(200),
	   task_system_id INT,
	   task_system_title VARCHAR(200),
	   task_system_part_id INT,
	   task_system_part_title VARCHAR(200),
	   task_project_id INT,
	   task_project_title VARCHAR(200),
	   task_project_part_id INT,
	   task_project_part_title VARCHAR(200),
	   task_updated_datetime DATETIME,
	   task_updated_translated VARCHAR(200),
	   task_due_datetime DATETIME,
	   task_due_time VARCHAR(200),
	   task_due_translated VARCHAR(200),
	   task_due_warning_sent INT,
	   task_assigned_to_user_id INT,
	   task_assigned_to_user_name VARCHAR(200),
	   task_assigned_to_user_alias VARCHAR(200),
	   task_assigned_to_user_image VARCHAR(200),
	   task_assigned_to_user_thumb_40 VARCHAR(200),
	   task_assigned_to_user_thumb_50 VARCHAR(200),
	   task_assigned_to_user_email VARCHAR(200),
	   task_hours_planned VARCHAR(20),
	   task_hours_used VARCHAR(20),
	   task_hours_diff_number VARCHAR(20),
	   task_hours_diff_percentage VARCHAR(20),
	   task_qa_datetime DATETIME,
	   task_qa_by_user_id INT,
	   task_qa_by_user_name VARCHAR(200),
	   task_qa_by_user_alias VARCHAR(200),
	   task_qa_by_user_image VARCHAR(200),
	   task_qa_by_user_thumb_40 VARCHAR(200),
	   task_qa_by_user_thumb_50 VARCHAR(200),
	   task_qa_by_user_email VARCHAR(200),
	   task_finished_is_finished INT,
	   task_finished_datetime DATETIME,
	   task_finished_year INT,
	   task_finished_month INT,
	   task_finished_month_saying VARCHAR(20),
	   task_finished_week INT,
	   task_finished_by_user_id INT,
	   task_finished_by_user_name VARCHAR(200),
	   task_finished_by_user_alias VARCHAR(200),
	   task_finished_by_user_image VARCHAR(200),
	   task_finished_by_user_thumb_40 VARCHAR(200),
	   task_finished_by_user_thumb_50 VARCHAR(200),
	   task_finished_by_user_email VARCHAR(200),
	   task_is_archived INT,
	   task_comments INT
		)")
	or die(mysqli_error($link));
}


}
?>