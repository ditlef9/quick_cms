<?php
if(isset($_SESSION['admin_user_id'])){
	$t_users_known_devices = $mysqlPrefixSav . "users_known_devices";


	mysqli_query($link,"DROP TABLE IF EXISTS $t_users_known_devices") or die(mysqli_error());


	mysqli_query($link, "CREATE TABLE $t_users_known_devices(
			   known_device_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(known_device_id), 
			   known_device_user_id INT,
			   known_device_fingerprint TEXT,
			   known_device_created_datetime DATETIME,
			   known_device_created_datetime_saying VARCHAR(200),
			   known_device_updated_datetime DATETIME,
			   known_device_updated_datetime_saying VARCHAR(200),
			   known_device_updated_year INT,
			   known_device_created_ip VARCHAR(200),
			   known_device_created_hostname VARCHAR(200),
			   known_device_last_ip VARCHAR(200),
			   known_device_last_hostname VARCHAR(200),
			   known_device_user_agent VARCHAR(200),
			   known_device_country VARCHAR(200),
			   known_device_type VARCHAR(200),
			   known_device_os VARCHAR(200),
			   known_device_os_icon VARCHAR(200),
			   known_device_browser VARCHAR(200),
			   known_device_accepted_language VARCHAR(200),
			   known_device_language VARCHAR(200),
			   known_device_last_url VARCHAR(500),
			   known_device_reported INT)")
			   or die(mysqli_error($link));



}
?>