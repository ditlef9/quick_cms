<?php
if(isset($_SESSION['admin_user_id'])){


	$t_tasks_priorities		= $mysqlPrefixSav . "tasks_priorities";

	mysqli_query($link,"DROP TABLE IF EXISTS $t_tasks_priorities") or die(mysqli_error());


	$datetime = date("Y-m-d H:i:s");



$query = "SELECT * FROM $t_tasks_priorities LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_tasks_priorities(
	   priority_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(priority_id), 
	   priority_title VARCHAR(200),
	   priority_title_clean VARCHAR(200),
	   priority_weight INT,
	   priority_pre_selected INT,
	   priority_created DATETIME,
	   priority_updated DATETIME)")
	or die(mysqli_error($link));

	mysqli_query($link, "INSERT INTO $t_tasks_priorities
	(priority_id, priority_title, priority_title_clean, priority_weight, priority_pre_selected, priority_created, priority_updated) 
	VALUES 
	(NULL, 'High', 'high', 1, 0, '$datetime', '$datetime'),
	(NULL, 'Medium', 'medium', 2, 1, '$datetime', '$datetime'),
	(NULL, 'Low', 'low', 3, 0, '$datetime', '$datetime')
	")
	or die(mysqli_error($link));


}

}
?>