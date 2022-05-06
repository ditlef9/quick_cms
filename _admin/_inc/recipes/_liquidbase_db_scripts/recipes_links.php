<?php
/**
*
* File: _admin/_inc/recipes/_liquidbase_db_scripts/recipes_links.php
* Version 1.0.0
* Date 17:21 31.12.2020
* Copyright (c) 2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

echo"


	<!-- links -->
	";
	$query = "SELECT * FROM $t_recipes_links";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_recipes_links: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_recipes_links(
	  	 link_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(link_id), 
	  	   link_language VARCHAR(20),
	  	   link_recipe_id INT,
		   link_title VARCHAR(250),
		   link_url VARCHAR(250),
	  	   link_unique_click INT,
		   link_unique_click_ipblock TEXT,
	  	   link_user_id INT
	  	   )")
		   or die(mysqli_error());
	}
	echo"
	<!-- //links -->
";
?>