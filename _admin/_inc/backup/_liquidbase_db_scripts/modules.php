<?php
/**
*
* File: _admin/_inc/backup/_liquibase/modules.php
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


<!-- backup_modules -->
";

mysqli_query($link, "DROP TABLE IF EXISTS $t_backup_modules");

$query = "SELECT * FROM $t_backup_modules LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_backup_modules: $row_cnt</p>
	";
}
else{
	mysqli_query($link, "CREATE TABLE $t_backup_modules(
	  module_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(module_id), 
	   module_backup_id INT,
	   module_name VARCHAR(200),
	   module_name_clean VARCHAR(200),
	   module_icon_black_18x18 VARCHAR(200),
	   module_tables_finished INT,
	   module_files_finished INT,
	   module_backup_completed INT)")
	   or die(mysqli_error());
}
echo"
<!-- //backup_modules -->

";
?>