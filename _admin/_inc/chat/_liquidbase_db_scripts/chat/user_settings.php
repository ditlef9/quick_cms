<?php
/**
*
* File: _admin/_inc/talk/_liquibase/talk/006_user_settings.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_chat_user_settings") or die(mysqli_error($link)); 


echo"
<!-- talk_user_settings -->
";

$query = "SELECT * FROM $t_chat_user_settings LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_chat_user_settings: $row_cnt</p>
	";
}
else{


	mysqli_query($link, "CREATE TABLE $t_chat_user_settings(
	  user_setting_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(user_setting_id), 
	   user_setting_user_id INT,
	   user_setting_show_channel_info INT
	   )")
	   or die(mysqli_error());
}
echo"
<!-- //talk_user_settings -->
";
?>