<?php
/**
*
* File: _admin/_inc/talk/_liquibase/talk/003_total_unread.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_chat_channels_users_online") or die(mysqli_error($link)); 


echo"
<!-- talk_channels_users_online -->
";

$query = "SELECT * FROM $t_chat_channels_users_online LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_chat_channels_users_online: $row_cnt</p>
	";
}
else{


	mysqli_query($link, "CREATE TABLE $t_chat_channels_users_online(
	  online_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(online_id), 
	   online_channel_id INT,
	   online_time VARCHAR(200),
	   online_is_online VARCHAR(200),
	   online_user_id INT,
	   online_user_nickname VARCHAR(200),
	   online_user_name VARCHAR(200),
	   online_user_alias VARCHAR(200),
	   online_user_image_path VARCHAR(200),
	   online_user_image_file VARCHAR(200),
	   online_user_image_thumb_40 VARCHAR(200),
	   online_user_image_thumb_50 VARCHAR(200),
	   online_ip VARCHAR(200),
	   online_hostname VARCHAR(200),
	   online_user_agent VARCHAR(200)
	   )")
	   or die(mysqli_error());
}
echo"
<!-- //talk_channels_users_online -->
";
?>