<?php
if(isset($_SESSION['admin_user_id'])){
	$t_users_notifications	= $mysqlPrefixSav . "users_notifications";
	

	mysqli_query($link,"DROP TABLE IF EXISTS $t_users_notifications") or die(mysqli_error());


	$query = "SELECT * FROM $t_users_notifications LIMIT 1";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_users_notifications(
			   notification_id INT NOT NULL AUTO_INCREMENT,
		 	  PRIMARY KEY(notification_id), 
		 	   notification_user_id INT,
		  	   notification_reference_id_text VARCHAR(200),
		 	   notification_seen INT,
		 	   notification_displayed_to_user_count INT,
		 	   notification_url VARCHAR(200),
		 	   notification_text TEXT,
		  	   notification_datetime DATETIME,
		  	   notification_datetime_saying VARCHAR(200),
		  	   notification_emailed INT,
		  	   notification_week INT)")
		  	 or die(mysqli_error($link));
	}



}
?>