<?php
if(isset($_SESSION['admin_user_id'])){


	$t_tasks_subscriptions	= $mysqlPrefixSav . "tasks_subscriptions";

	mysqli_query($link,"DROP TABLE IF EXISTS $t_tasks_subscriptions") or die(mysqli_error());


$query = "SELECT * FROM $t_tasks_subscriptions LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{


	mysqli_query($link, "CREATE TABLE $t_tasks_subscriptions(
	   subscription_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(subscription_id), 
	   subscription_task_id INT,
	   subscription_user_id INT,
	   subscription_user_email VARCHAR(250),
	   subscription_last_sendt_datetime DATETIME,
	   subscription_last_sendt_time VARCHAR(200))")
	or die(mysqli_error($link));




}





}
?>