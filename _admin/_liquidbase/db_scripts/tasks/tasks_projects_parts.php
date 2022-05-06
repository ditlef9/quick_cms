<?php
if(isset($_SESSION['admin_user_id'])){


	$t_tasks_projects_parts  	= $mysqlPrefixSav . "tasks_projects_parts";
	mysqli_query($link,"DROP TABLE IF EXISTS $t_tasks_projects_parts") or die(mysqli_error());

$query = "SELECT * FROM $t_tasks_projects_parts LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_tasks_projects_parts(
	   project_part_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(project_part_id), 
	   project_part_project_id INT,
	   project_part_system_id INT,
	   project_part_title VARCHAR(200),
	   project_part_description TEXT,
	   project_part_logo VARCHAR(200),
	   project_part_is_active INT,
	   project_part_created DATETIME,
	   project_part_updated DATETIME)")
	or die(mysqli_error($link));
}


}
?>