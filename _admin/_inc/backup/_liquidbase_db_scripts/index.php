<?php
/**
*
* File: _admin/_inc/backup/_liquibase/index.php
* Version 1.0.0
* Date 20:36 12.01.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

echo"


<!-- backup_index -->
";
mysqli_query($link, "DROP TABLE IF EXISTS $t_backup_index");

$query = "SELECT * FROM $t_backup_index LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_backup_index: $row_cnt</p>
	";
}
else{
	mysqli_query($link, "CREATE TABLE $t_backup_index(
	  backup_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(backup_id), 
	   backup_created_datetime DATETIME,
	   backup_created_datetime_saying VARCHAR(200), 
	   backup_created_date DATE, 
	   backup_zip_dir VARCHAR(200),
	   backup_zip_file VARCHAR(200),
	   backup_zip_size VARCHAR(200),
	   backup_zip_size_human VARCHAR(200),
	   backup_zip_md5 VARCHAR(200),
	   backup_secret VARCHAR(200), 
	   backup_is_finished INT,
	   backup_no_of_modules_total INT, 
	   backup_no_of_modules_finished INT, 
	   backup_start VARCHAR(200),
	   backup_end VARCHAR(200),
	   backup_time_used VARCHAR(200),
	   backup_test INT)")
	   or die(mysqli_error());
}
echo"
<!-- //backup_index -->

";
?>