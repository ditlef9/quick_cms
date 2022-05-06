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
$t_chat_dm_conversations = $mysqlPrefixSav . "chat_dm_conversations";



$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_chat_dm_conversations") or die(mysqli_error($link)); 

echo"

<!-- talk_dm_conversations -->
";

$query = "SELECT * FROM $t_chat_dm_conversations LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_chat_dm_conversations: $row_cnt</p>
	";
}
else{

	mysqli_query($link, "CREATE TABLE $t_chat_dm_conversations(
	  conversation_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(conversation_id), 
	   conversation_key VARCHAR(100),
	   conversation_f_user_id INT,
	   conversation_f_user_nickname VARCHAR(200),
	   conversation_f_user_name VARCHAR(200),
	   conversation_f_user_alias VARCHAR(200),
	   conversation_f_image_path VARCHAR(200),
	   conversation_f_image_file VARCHAR(200),
	   conversation_f_image_thumb40 VARCHAR(200),
	   conversation_f_image_thumb50 VARCHAR(200),
	   conversation_f_has_blocked INT,
	   conversation_f_unread_messages INT,
	   conversation_f_last_online_time VARCHAR(200),
	   conversation_t_user_id INT,
	   conversation_t_user_nickname VARCHAR(200),
	   conversation_t_user_name VARCHAR(200),
	   conversation_t_user_alias VARCHAR(200),
	   conversation_t_image_path VARCHAR(200),
	   conversation_t_image_file VARCHAR(200),
	   conversation_t_image_thumb40 VARCHAR(200),
	   conversation_t_image_thumb50 VARCHAR(200),
	   conversation_t_has_blocked INT,
	   conversation_t_unread_messages INT,
	   conversation_t_last_online_time VARCHAR(200),
	   conversation_encryption_key VARCHAR(200),
	   conversation_encryption_key_year INT,
	   conversation_encryption_key_month INT
	   )")
	   or die(mysqli_error());

}
echo"
<!-- //talk_dm_conversations -->


";
?>