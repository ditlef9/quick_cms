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
mysqli_query($link, "DROP TABLE IF EXISTS $t_references_categories_sub")  or die(mysqli_error($link));

echo"


<!-- reference categories sub -->
";

$query = "SELECT * FROM $t_references_categories_sub LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_references_categories_sub: $row_cnt</p>
	";
}
else{
	mysqli_query($link, "CREATE TABLE $t_references_categories_sub(
	  sub_category_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(sub_category_id), 
	   sub_category_title VARCHAR(200), 
	   sub_category_title_clean VARCHAR(200), 
	   sub_category_description TEXT, 
	   sub_category_main_category_id INT,
	   sub_category_main_category_title VARCHAR(200), 
	   sub_category_language VARCHAR(10), 
	   sub_category_created DATETIME,
	   sub_category_updated DATETIME)")
	   or die(mysqli_error());
}
echo"
<!-- //reference categories sub -->



";
?>