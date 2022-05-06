<?php
/**
*
* File: _admin/_inc/references/_liquibase/courses/001_references.php
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
mysqli_query($link, "DROP TABLE IF EXISTS $t_references_index_groups")  or die(mysqli_error($link));

echo"


<!-- references_index_groups -->
";

$query = "SELECT * FROM $t_references_index_groups LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_references_index_groups: $row_cnt</p>
	";
}
else{
	mysqli_query($link, "CREATE TABLE $t_references_index_groups(
	  group_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(group_id), 
	   group_title VARCHAR(200), 
	   group_title_clean VARCHAR(200), 
	   group_title_short VARCHAR(200),
	   group_title_length INT,
	   group_number INT, 
	   group_content TEXT,
	   group_reference_id INT, 
	   group_reference_title VARCHAR(200), 
	   group_read_times INT,
	   group_read_times_ip_block TEXT,
	   group_created_datetime DATETIME,
	   group_updated_datetime DATETIME, 
	   group_updated_formatted VARCHAR(200),
	   group_last_read DATETIME,
	   group_last_read_formatted VARCHAR(200))")
	   or die(mysqli_error());
}
echo"
<!-- //references_index_groups -->





";
?>