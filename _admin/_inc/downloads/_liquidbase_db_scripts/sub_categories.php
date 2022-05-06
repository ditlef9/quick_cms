<?php
/**
*
* File: _admin/_inc/downloads/_liquibase/sub_categories.php
* Version 1.0.0
* Date 12:57 24.03.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_downloads_sub_categories") or die(mysqli_error($link)); 


echo"
	<!-- downloads_sub_categories -->
	";
	$query = "SELECT * FROM $t_downloads_sub_categories";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_downloads_sub_categories: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_downloads_sub_categories(
	  	 sub_category_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(sub_category_id), 
	  	   sub_category_parent_id INT,
	  	   sub_category_title VARCHAR(200),
	  	   sub_category_title_clean VARCHAR(200),
	  	   sub_category_icon_path VARCHAR(100),
	  	   sub_category_icon_file VARCHAR(100),
	  	   sub_category_created DATETIME)");

		$datetime = date("Y-m-d H:i:s");

	}
	echo"
	<!-- //downloads_sub_categories -->
";
?>