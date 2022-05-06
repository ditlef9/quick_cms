<?php
if(isset($_SESSION['admin_user_id'])){

	$t_tasks_last_used_projects	= $mysqlPrefixSav . "tasks_last_used_projects";

	mysqli_query($link,"DROP TABLE IF EXISTS $t_tasks_last_used_projects") or die(mysqli_error());


$query = "SELECT * FROM $t_tasks_last_used_projects LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_tasks_last_used_projects(
	   last_used_project_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(last_used_project_id), 
	   last_used_project_user_id INT,
	   last_used_project_project_id INT
	)")
	or die(mysqli_error($link));
}

}
?>