<?php
/**
*
* File: _admin/_liquidbase/db_scripts/webdesign/frontpage_grid_items.php
* Version 1.0.0
* Date 09:11 04.05.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// Access check
if(isset($_SESSION['admin_user_id'])){

	/*- Tables ---------------------------------------------------------------------------- */


	$t_grid_items	= $mysqlPrefixSav . "grid_items";


	$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_grid_items") or die(mysqli_error($link)); 



	echo"

	<!-- grid_items -->
	";

	$query = "SELECT * FROM $t_grid_items LIMIT 1";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_grid_items: $row_cnt</p>
		";
	}
	else{

		mysqli_query($link, "CREATE TABLE $t_grid_items(
		  item_id INT NOT NULL AUTO_INCREMENT,
		  PRIMARY KEY(item_id), 
		   item_language VARCHAR(20),
		   item_group_id INT,
		   item_title VARCHAR(200), 
		   item_url VARCHAR(200),
		   item_weight INT,
		   item_icon_path VARCHAR(200),
		   item_icon_18x18 VARCHAR(100),
		   item_icon_hover_18x18 VARCHAR(100),
		   item_icon_24x24 VARCHAR(100),
		   item_icon_hover_24x24 VARCHAR(100),
		   item_icon_36x36 VARCHAR(100),
		   item_icon_hover_36x36 VARCHAR(100),
		   item_icon_48x48 VARCHAR(100),
		   item_icon_hover_48x48 VARCHAR(100),
		   item_created_datetime DATETIME,
		   item_created_user_id INT,
		   item_updated_datetime DATETIME,
		   item_updated_user_id INT
		   )")
		   or die(mysqli_error());

	}
	echo"
	<!-- //grid_items -->
	";
} // access
?>