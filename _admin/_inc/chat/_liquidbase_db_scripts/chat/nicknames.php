<?php
/**
*
* File: _admin/_inc/talk/_liquibase/talk/005_nicknames.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_chat_nicknames") or die(mysqli_error($link)); 
$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_chat_nicknames_changes") or die(mysqli_error($link)); 


echo"
<!-- talk_nicknames -->
";

$query = "SELECT * FROM $t_chat_nicknames LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_chat_nicknames: $row_cnt</p>
	";
}
else{


	mysqli_query($link, "CREATE TABLE $t_chat_nicknames(
	  nickname_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(nickname_id), 
	   nickname_user_id INT,
	   nickname_value VARCHAR(200),
	   nickname_datetime DATETIME,
	   nickname_datetime_saying VARCHAR(200)
	   )")
	   or die(mysqli_error());
}
echo"
<!-- //talk_nicknames -->

<!-- talk_nicknames_changes -->
";

$query = "SELECT * FROM $t_chat_nicknames_changes LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_chat_nicknames_changes: $row_cnt</p>
	";
}
else{


	mysqli_query($link, "CREATE TABLE $t_chat_nicknames_changes(
	  change_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(change_id), 
	   change_user_id INT,
	   change_from_value VARCHAR(200),
	   change_to_value VARCHAR(200),
	   change_datetime DATETIME,
	   change_datetime_saying VARCHAR(200)
	   )")
	   or die(mysqli_error());
}
echo"
<!-- //talk_nicknames_changes -->
";
?>