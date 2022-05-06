<?php
if(isset($_SESSION['admin_user_id'])){


	$t_tasks_read			= $mysqlPrefixSav . "tasks_read";
	mysqli_query($link,"DROP TABLE IF EXISTS $t_tasks_read") or die(mysqli_error());




$query = "SELECT * FROM $t_tasks_read LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_tasks_read(
	   read_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(read_id), 
	   read_task_id INT,
	   read_user_id INT)")
	or die(mysqli_error($link));
}


}
?>