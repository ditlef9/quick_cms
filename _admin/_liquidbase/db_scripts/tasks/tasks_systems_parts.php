<?php
if(isset($_SESSION['admin_user_id'])){


	$t_tasks_systems_parts  	= $mysqlPrefixSav . "tasks_systems_parts";
	mysqli_query($link,"DROP TABLE IF EXISTS $t_tasks_systems_parts") or die(mysqli_error());



$query = "SELECT * FROM $t_tasks_systems_parts LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_tasks_systems_parts(
	  system_part_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(system_part_id), 
	   system_part_system_id INT,
	   system_part_title VARCHAR(200),
	   system_part_description TEXT,
	   system_part_logo VARCHAR(200),
	   system_part_is_active INT,
	   system_part_created DATETIME,
	   system_part_updated DATETIME)")
	or die(mysqli_error($link));

	/*
	mysqli_query($link, "INSERT INTO $t_tasks_systems_parts
	(system_part_id, system_part_system_id, system_part_title, system_part_description, system_part_logo, system_part_is_active) 
	VALUES 
	(NULL, 1, 'Recipes', 'Recipes part', 'recipes.jpg', 1),
	(NULL, 2, 'Android', 'Android app', 'android.jpg', 1),
	(NULL, 2, 'iPhone', 'iPhone app', 'iphone.jpg', 1)")
	or die(mysqli_error($link));
	*/
}

}
?>