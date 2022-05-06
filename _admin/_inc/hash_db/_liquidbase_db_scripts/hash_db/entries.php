<?php
/**
*
* File: _admin/_inc/hash_db/_liquibase/hash_db/entries.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_hash_db_entries") or die(mysqli_error($link)); 


echo"
<!-- hash_db_entries -->
";

$query = "SELECT * FROM $t_hash_db_entries LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_hash_db_entries: $row_cnt</p>
	";
}
else{


	mysqli_query($link, "CREATE TABLE $t_hash_db_entries(
	  entry_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(entry_id), 
	   entry_category_id INT,
	   entry_category_title VARCHAR(250),
	   entry_file_path VARCHAR(250),
	   entry_file_name VARCHAR(250),
	   entry_file_extension VARCHAR(250),
	   entry_file_mime VARCHAR(250),
	   entry_file_size_bytes VARCHAR(250),
	   entry_file_size_human VARCHAR(250),
	   entry_file_created_datetime DATETIME,
	   entry_file_created_saying VARCHAR(250),
	   entry_file_last_changed_datetime DATETIME,
	   entry_file_last_changed_saying VARCHAR(250),
	   entry_file_name_md5 VARCHAR(250),
	   entry_file_name_sha1 VARCHAR(250),
	   entry_file_content_md5 VARCHAR(250),
	   entry_file_content_sha1 VARCHAR(250),
	   entry_created_datetime DATETIME,
	   entry_created_saying VARCHAR(250),
	   entry_created_by_user_id VARCHAR(250),
	   entry_created_by_user_name VARCHAR(250),
	   entry_updated_datetime DATETIME,
	   entry_updated_saying VARCHAR(250),
	   entry_updated_by_user_id VARCHAR(250),
	   entry_updated_by_user_name VARCHAR(250),
	   entry_hits INT
	   )")
	   or die(mysqli_error());
}
echo"
<!-- //hash_db_entries -->

";
?>