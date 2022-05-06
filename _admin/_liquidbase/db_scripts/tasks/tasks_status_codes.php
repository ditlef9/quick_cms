<?php
if(isset($_SESSION['admin_user_id'])){


	$t_tasks_status_codes 	= $mysqlPrefixSav . "tasks_status_codes";


	mysqli_query($link,"DROP TABLE IF EXISTS $t_tasks_status_codes") or die(mysqli_error());



$query = "SELECT * FROM $t_tasks_status_codes LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_tasks_status_codes(
	   status_code_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(status_code_id), 
	   status_code_title VARCHAR(200),
	   status_code_text_color VARCHAR(200),
	   status_code_bg_color VARCHAR(200),
	   status_code_border_color VARCHAR(200),
	   status_code_weight INT,
	   status_code_show_on_board INT,
	   status_code_task_is_assigned INT,
	   status_code_on_status_close_task INT,
	   status_code_count_tasks INT)")
	or die(mysqli_error($link));

	// Tasks: Blue #b2def7
	// Ongoing: Orange #faa64b
	// Blocked: Red #f96868
	// Quality assurance: Pink #efb3e6
	// Finished: Green #15c377
	mysqli_query($link, "INSERT INTO $t_tasks_status_codes
	(`status_code_id`, `status_code_title`, `status_code_text_color`, `status_code_bg_color`, `status_code_border_color`, `status_code_weight`, `status_code_show_on_board`, `status_code_task_is_assigned`, `status_code_on_status_close_task`, `status_code_count_tasks`) VALUES
	(1, 'Tasks', '#000000', '#ffffff', '#b731ff', 1, 1, 0, 0, 16),
	(2, 'Waiting', '#000000', '#ffffff', '#5b8df4', 2, 1, 1, NULL, 1),
	(3, 'Ongoing', '#000000', '#ffffff', '#32b53c', 3, 1, 1, 0, 0),
	(4, 'Quality assurance', '#000000', '#ffffff', '#ffcc00', 4, 1, 1, 0, 1),
	(5, 'Blocked', '#000000', '#ffffff', '#ff7d48', 5, 1, 1, 0, 58),
	(6, 'Finished', '#000000', '#ffffff', '#ef3030', 6, 0, 0, 1, 0)
	")
	or die(mysqli_error($link));
}


}
?>