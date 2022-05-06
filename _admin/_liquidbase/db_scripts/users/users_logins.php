<?php
if(isset($_SESSION['admin_user_id'])){
	$t_users_logins = $mysqlPrefixSav . "users_logins";


	mysqli_query($link,"DROP TABLE IF EXISTS $t_users_logins") or die(mysqli_error());


	mysqli_query($link, "CREATE TABLE $t_users_logins(
			   login_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(login_id), 
			   login_user_id INT,
			   login_datetime DATETIME,
			   login_datetime_saying VARCHAR(200),
			   login_year INT,
			   login_month INT,
			   login_ip VARCHAR(200),
			   login_hostname VARCHAR(200),
			   login_user_agent VARCHAR(200),
			   login_country VARCHAR(200),
			   login_browser VARCHAR(200),
			   login_os VARCHAR(200),
			   login_type VARCHAR(200),
			   login_accepted_language VARCHAR(200),
			   login_language VARCHAR(200),
			   login_successfully VARCHAR(200),
			   login_unsuccessfully_reason VARCHAR(200),
			   login_url VARCHAR(500),
			   login_warning_sent INT)")
			   or die(mysqli_error($link));



}
?>