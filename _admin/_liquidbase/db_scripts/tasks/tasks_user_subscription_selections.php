<?php
if(isset($_SESSION['admin_user_id'])){


	$t_users				= $mysqlPrefixSav . "users";
	$t_tasks_user_subscription_selections	= $mysqlPrefixSav . "tasks_user_subscription_selections";

	mysqli_query($link,"DROP TABLE IF EXISTS $t_tasks_user_subscription_selections") or die(mysqli_error());


$query = "SELECT * FROM $t_tasks_user_subscription_selections LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{


	mysqli_query($link, "CREATE TABLE $t_tasks_user_subscription_selections(
	   selection_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(selection_id), 
	   selection_user_id INT,
	   selection_user_email VARCHAR(250),
	   selection_subscribe_to_new_tasks INT,
	   selection_subscribe_to_monthly_newsletter INT,
	   selection_unsubscribe_code VARCHAR(20),
	   selection_last_sendt_monthly_newsletter_month INT,
	   selection_last_sendt_datetime DATETIME,
	   selection_last_sendt_time VARCHAR(200))")
	or die(mysqli_error($link));


	// Find all admins, put them into subscription list
	$datetime = date("Y-m-d H:i:s");
	$time = time();
	$month = date("m");
	$query = "SELECT user_id, user_email, user_name, user_rank FROM $t_users WHERE user_rank='admin'";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_user_id, $get_user_email, $get_user_name, $get_user_rank) = $row;
		$inp_email_mysql = quote_smart($link, $get_user_email);

		mysqli_query($link, "INSERT INTO $t_tasks_user_subscription_selections
		(selection_id, selection_user_id, selection_user_email, selection_subscribe_to_new_tasks, selection_subscribe_to_monthly_newsletter, 
		selection_unsubscribe_code, selection_last_sendt_monthly_newsletter_month, selection_last_sendt_datetime, selection_last_sendt_time) 
		VALUES 
		(NULL, '$get_user_id', $inp_email_mysql, 0, 1, 
		$time, $month, '$datetime', '$time')")
		or die(mysqli_error($link));
	}


}





}
?>