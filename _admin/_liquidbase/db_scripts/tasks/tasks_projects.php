<?php
if(isset($_SESSION['admin_user_id'])){


	$t_tasks_projects  		= $mysqlPrefixSav . "tasks_projects";
	mysqli_query($link,"DROP TABLE IF EXISTS $t_tasks_projects") or die(mysqli_error());



$query = "SELECT * FROM $t_tasks_projects LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_tasks_projects(
	   project_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(project_id), 
	   project_system_id INT,
	   project_title VARCHAR(200),
	   project_task_abbr VARCHAR(200),
	   project_description TEXT,
	   project_logo VARCHAR(200),
	   project_is_active INT,
	   project_increment_tasks_counter INT,
	   project_created DATETIME,
	   project_updated DATETIME)")
	or die(mysqli_error($link));


	mysqli_query($link, "INSERT INTO $t_tasks_projects
	(project_id, project_system_id, project_title, project_is_active, project_increment_tasks_counter) 
	VALUES 
	(NULL, '1', 'New website', 1, 1)")
	or die(mysqli_error($link));

}

}
?>