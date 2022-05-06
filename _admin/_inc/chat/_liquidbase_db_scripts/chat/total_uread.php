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
echo"

<!-- talk_total_unread -->
";

$query = "SELECT * FROM $t_chat_total_unread LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_chat_total_unread: $row_cnt</p>
	";
}
else{

	mysqli_query($link, "CREATE TABLE $t_chat_total_unread(
	  total_unread_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(total_unread_id), 
	   total_unread_user_id INT,
	   total_unread_count INT,
	   total_unread_message VARCHAR(200)
	   )")
	   or die(mysqli_error());

}
echo"
<!-- //talk_total_unread -->

";
?>