<?php
/**
*
* File: _admin/_inc/talk/_liquibase/talk/001c_talk.php
* Version 1.0.0
* Date 14:33 07.01.2020
* Copyright (c) 2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_chat_channels_index") or die(mysqli_error($link));  
$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_chat_users_starred_channels") or die(mysqli_error($link)); 


echo"


<!-- talk_channels_index -->
";

$query = "SELECT * FROM $t_chat_channels_index LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_chat_channels_index: $row_cnt</p>
	";
}
else{


	mysqli_query($link, "CREATE TABLE $t_chat_channels_index(
	  channel_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(channel_id), 
	   channel_name VARCHAR(200),
	   channel_topic TEXT,
	   channel_topic_datetime DATETIME,
	   channel_topic_datetime_saying VARCHAR(200),
	   channel_topic_user_id INT,
	   channel_topic_user_name VARCHAR(200),
	   channel_password VARCHAR(200),
	   channel_created_by_user_id INT,
	   channel_created_by_user_ip VARCHAR(200),
	   channel_created_datetime DATETIME,
	   channel_created_saying VARCHAR(200),
	   channel_last_message_time VARCHAR(200),
	   channel_last_message_saying VARCHAR(200),
	   channel_users_online INT,
	   channel_encryption_key VARCHAR(200),
	   channel_encryption_key_year INT,
	   channel_encryption_key_month INT
	   )")
	   or die(mysqli_error());

}
echo"
<!-- //talk_channels_index -->



<!-- talk_users_starred_channels -->
";

$query = "SELECT * FROM $t_chat_users_starred_channels LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_chat_users_starred_channels: $row_cnt</p>
	";
}
else{


	mysqli_query($link, "CREATE TABLE $t_chat_users_starred_channels(
	  starred_channel_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(starred_channel_id), 
	   channel_id INT,
	   channel_name VARCHAR(200),
	   new_messages INT,
	   user_id INT
	   )")
	   or die(mysqli_error());

}
echo"
<!-- //talk_users_starred_channels -->



";
?>