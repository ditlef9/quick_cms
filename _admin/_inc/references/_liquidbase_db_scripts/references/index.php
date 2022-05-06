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
mysqli_query($link, "DROP TABLE IF EXISTS $t_references_index")  or die(mysqli_error($link));

echo"
<!-- references index-->
";

$query = "SELECT * FROM $t_references_index LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_references_index: $row_cnt</p>
	";
}
else{
	mysqli_query($link, "CREATE TABLE $t_references_index(
	  reference_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(reference_id), 
	   reference_title VARCHAR(200), 
	   reference_title_clean VARCHAR(200), 
	   reference_title_short VARCHAR(200), 
	   reference_title_length INT,
	   reference_is_active INT, 
	   reference_front_page_intro TEXT, 
	   reference_description TEXT, 
	   reference_language VARCHAR(10), 
	   reference_main_category_id INT, 
	   reference_main_category_title VARCHAR(200), 
	   reference_sub_category_id INT, 
	   reference_sub_category_title VARCHAR(200), 
	   reference_image_file VARCHAR(200),  
	   reference_image_thumb VARCHAR(200),  
	   reference_icon_16 VARCHAR(200), 
	   reference_icon_32 VARCHAR(200), 
	   reference_icon_48 VARCHAR(200), 
	   reference_icon_64 VARCHAR(200),  
	   reference_icon_96 VARCHAR(200),  
	   reference_icon_260 VARCHAR(200),  
	   reference_groups_count INT, 
	   reference_guides_count INT, 
	   reference_read_times INT,
	   reference_read_times_ip_block TEXT,
	   reference_created DATETIME,
	   reference_updated DATETIME)")
	   or die(mysqli_error());
}
echo"
<!-- //references index-->



";
?>