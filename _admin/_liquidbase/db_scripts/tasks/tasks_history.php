<?php
if(isset($_SESSION['admin_user_id'])){


	$t_tasks_history		= $mysqlPrefixSav . "tasks_history";

	mysqli_query($link,"DROP TABLE IF EXISTS $t_tasks_history") or die(mysqli_error());



$query = "SELECT * FROM $t_tasks_history LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_tasks_history(
	   history_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(history_id), 
	   history_task_id INT,
	   history_updated_by_user_id INT,
	   history_updated_by_user_name VARCHAR(200),
	   history_updated_by_user_alias VARCHAR(200),
	   history_updated_by_user_email VARCHAR(200),
	   history_updated_datetime DATETIME,
	   history_updated_datetime_saying VARCHAR(200),
	   history_summary TEXT,
	   history_new_title VARCHAR(200),
	   history_new_text TEXT,
	   history_new_status_code_id INT,
	   history_new_status_code_title VARCHAR(200),
	   history_new_priority_id INT,
	   history_new_assigned_to_user_id INT,
	   history_new_assigned_to_user_name VARCHAR(200),
	   history_new_assigned_to_user_alias VARCHAR(200),
	   history_new_assigned_to_user_image VARCHAR(200),
	   history_new_assigned_to_user_email VARCHAR(200),
	   history_new_hours_planned INT,
	   history_new_hours_used INT
	)")
	or die(mysqli_error($link));
}


}
?>