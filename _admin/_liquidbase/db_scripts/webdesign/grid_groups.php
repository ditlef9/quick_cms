<?php
/**
*
* File: _admin/_liquidbase/db_scripts/webdesign/frontpage_grid_groups.php
* Version 1.0.0
* Date 09:11 04.05.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// Access check
if(isset($_SESSION['admin_user_id'])){

	/*- Tables ---------------------------------------------------------------------------- */


	$t_grid_groups	= $mysqlPrefixSav . "grid_groups";
	$t_languages_active =  $mysqlPrefixSav . "languages_active";

	$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_grid_groups") or die(mysqli_error($link)); 



	echo"

	<!-- grid_groups -->
	";

	$query = "SELECT * FROM $t_grid_groups LIMIT 1";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_grid_groups: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_grid_groups(
		  group_id INT NOT NULL AUTO_INCREMENT,
		  PRIMARY KEY(group_id), 
		   group_language VARCHAR(20),
		   group_title VARCHAR(200), 
		   group_title_english VARCHAR(200), 
		   group_active INT,
		   group_preferred_icon_size VARCHAR(20), 
		   group_created_datetime DATETIME,
		   group_created_user_id INT,
		   group_updated_datetime DATETIME,
		   group_updated_user_id INT
		   )")
		   or die(mysqli_error());

		$my_user_id = $_SESSION['admin_user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);

		$datetime = date("Y-m-d H:i:s");

		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;
			mysqli_query($link, "INSERT INTO $t_grid_groups(group_id, group_language, group_title, group_title_english, group_active, group_preferred_icon_size, group_created_user_id, group_created_datetime)
					VALUES 
					(NULL, '$get_language_active_iso_two', 'Frontpage', 'Frontpage', 0, '36x36', $my_user_id_mysql, '$datetime')
					") or die(mysqli_error());
		} // while languages


	}
	echo"
	<!-- //grid_groups -->
	";
} // access
?>