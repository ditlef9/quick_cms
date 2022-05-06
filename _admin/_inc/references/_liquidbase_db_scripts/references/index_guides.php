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
mysqli_query($link, "DROP TABLE IF EXISTS $t_references_index_guides")  or die(mysqli_error($link));

echo"

<!-- references_index_guides -->
";

$query = "SELECT * FROM $t_references_index_guides LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_references_index_guides: $row_cnt</p>
	";
}
else{

	mysqli_query($link, "CREATE TABLE $t_references_index_guides(
	  guide_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(guide_id), 
	   guide_number INT, 
	   guide_title VARCHAR(200),
	   guide_title_clean VARCHAR(200),
	   guide_title_short VARCHAR(200),
	   guide_title_length INT,
	   guide_short_description VARCHAR(200),
	   guide_content TEXT,
	   guide_group_id INT, 
	   guide_group_title VARCHAR(200), 
	   guide_reference_id INT, 
	   guide_reference_title VARCHAR(200), 
	   guide_read_times INT,
	   guide_read_ipblock TEXT,
	   guide_created DATETIME,
	   guide_updated DATETIME,
	   guide_updated_formatted VARCHAR(200), 
	   guide_last_read DATETIME,
	   guide_last_read_formatted VARCHAR(200), 
	   guide_comments INT)")
	   or die(mysqli_error());
}
echo"
<!-- //references_index_guides -->




";
?>