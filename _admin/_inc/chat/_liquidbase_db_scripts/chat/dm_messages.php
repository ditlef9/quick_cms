<?php
/**
*
* File: _admin/_inc/talk/_liquibase/talk/002_dm.php
* Version 1.0.0
* Date 21:19 28.08.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */
$t_chat_dm_messages	 = $mysqlPrefixSav . "chat_dm_messages";



$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_chat_dm_messages") or die(mysqli_error($link)); 

echo"
<!-- talk_dm_messages -->
";

$query = "SELECT * FROM $t_chat_dm_messages LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_chat_dm_messages: $row_cnt</p>
	";
}
else{


	mysqli_query($link, "CREATE TABLE $t_chat_dm_messages(
	  message_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(message_id), 
	   message_conversation_key VARCHAR(100),
	   message_type VARCHAR(200),
	   message_text TEXT,
	   message_datetime DATETIME,
	   message_date_saying VARCHAR(200),
	   message_time_saying VARCHAR(200),
	   message_time VARCHAR(200),
	   message_year INT,
	   message_day INT,
	   message_seen INT,
	   message_attachment_type VARCHAR(200),
	   message_attachment_path VARCHAR(200),
	   message_attachment_file VARCHAR(200),
	   message_from_user_id INT,
	   message_from_ip VARCHAR(200),
	   message_from_hostname VARCHAR(200),
	   message_from_user_agent VARCHAR(200)
	   )")
	   or die(mysqli_error());

}
echo"
<!-- //talk_dm_messages -->



";
?>