<?php
/**
*
* File: _inc\blog\_liquidbase_db_scripts/default_categories.php
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



echo"
	<!-- default_categories -->
	";

	
	$query = "SELECT * FROM $t_blog_default_categories";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_blog_default_categories: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_blog_default_categories(
	  	 default_category_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(default_category_id), 
	  	   default_category_title VARCHAR(50),
	  	   default_category_language VARCHAR(250))")
		   or die(mysqli_error());

	}
	echo"
	<!-- //default_categories -->

";
?>