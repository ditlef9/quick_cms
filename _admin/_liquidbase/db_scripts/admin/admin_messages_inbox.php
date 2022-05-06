<?php
if(isset($_SESSION['admin_user_id'])){

	$t_admin_messages_inbox		= $mysqlPrefixSav . "admin_messages_inbox";


	mysqli_query($link,"DROP TABLE IF EXISTS $t_admin_messages_inbox") or die(mysqli_error());
	

$query = "SELECT * FROM $t_admin_messages_inbox LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{
	mysqli_query($link, "CREATE TABLE $t_admin_messages_inbox(
	   message_id INT NOT NULL AUTO_INCREMENT,
	   PRIMARY KEY(message_id), 
	   message_title VARCHAR(240),
	   message_text TEXT,
	   message_language VARCHAR(240),
	   message_datetime DATETIME,
	   message_year INT,
	   message_month INT,
	   message_day INT,
	   message_date_sayning VARCHAR(200),
	   message_sent_email_warning INT,
	   message_replied INT,
	   message_from_user_id INT,
	   message_from_name VARCHAR(240),
	   message_from_image VARCHAR(240),
	   message_from_ip VARCHAR(240),
	   message_read INT,
	   message_read_by_user_id INT,
	   message_read_by_user_name VARCHAR(240),
	   message_comment VARCHAR(240),
	   message_archived INT,
	   message_spam INT,
	   message_action_needed VARCHAR(240),
	   message_tags VARCHAR(240))")
	or die(mysqli_error($link));
}




}
?>