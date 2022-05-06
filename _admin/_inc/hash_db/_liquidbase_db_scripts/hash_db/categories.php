<?php
/**
*
* File: _admin/_inc/hash_db/_liquibase/hash_db/categories.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_hash_db_categories") or die(mysqli_error($link)); 


echo"
<!-- hash_db_categories -->
";

$query = "SELECT * FROM $t_hash_db_categories LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_hash_db_categories: $row_cnt</p>
	";
}
else{


	mysqli_query($link, "CREATE TABLE $t_hash_db_categories(
	  category_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(category_id), 
	   category_title VARCHAR(250), 
	   category_bg_color VARCHAR(250), 
	   category_border_color VARCHAR(250), 
	   category_text_color VARCHAR(250), 
	   category_is_illegal INT,
	   category_is_interesting INT
	   )")
	   or die(mysqli_error());

	// Create categories
	mysqli_query($link, "INSERT INTO $t_hash_db_categories
	(category_id, category_title, category_bg_color, category_border_color, category_text_color, category_is_illegal, category_is_interesting) 
	VALUES 
	(NULL, 'Illict images', '#f8cecc', '#b85450', '#000000', 1, 1), 
	(NULL, 'Windows', '#dae8fc', '#6c8ebf', '#000000', 0, 0),
	(NULL, 'Mac', '#dae8fc', '#6c8ebf', '#000000', 0, 0),
	(NULL, 'Linux', '#dae8fc', '#6c8ebf', '#000000', 0, 0)")
	or die(mysqli_error($link)); 


}
echo"
<!-- //hash_db_categories -->

";
?>