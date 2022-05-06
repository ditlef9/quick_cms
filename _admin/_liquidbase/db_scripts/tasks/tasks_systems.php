<?php
if(isset($_SESSION['admin_user_id'])){


	$t_tasks_systems  		= $mysqlPrefixSav . "tasks_systems";

	mysqli_query($link,"DROP TABLE IF EXISTS $t_tasks_systems") or die(mysqli_error());




$query = "SELECT * FROM $t_tasks_systems LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_tasks_systems(
	   system_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(system_id), 
	   system_title VARCHAR(200),
	   system_task_abbr VARCHAR(200),
	   system_description TEXT,
	   system_logo VARCHAR(200),
	   system_is_active INT,
	   system_increment_tasks_counter INT,
	   system_created DATETIME,
	   system_updated DATETIME)")
	or die(mysqli_error($link));

	mysqli_query($link, "INSERT INTO $t_tasks_systems
	(system_id, system_title, system_task_abbr, system_description, system_logo, system_is_active, system_increment_tasks_counter) 
	VALUES 
	(NULL, 'Website', 'SWCR', 'This webside', 'website.jpg', 1, 1)")
	or die(mysqli_error($link));


}

}
?>