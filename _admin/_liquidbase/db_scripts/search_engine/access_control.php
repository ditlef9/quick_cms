<?php
if(isset($_SESSION['admin_user_id'])){
	$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
	$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";
	$t_search_engine_searches	= $mysqlPrefixSav . "search_engine_searches";


	mysqli_query($link,"DROP TABLE IF EXISTS $t_search_engine_access_control") or die(mysqli_error());


	mysqli_query($link, "CREATE TABLE $t_search_engine_access_control(
			   control_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(control_id), 
			   control_user_id INT,
			   control_user_name VARCHAR(200),
			   control_has_access_to_module_name VARCHAR(200),
			   control_has_access_to_module_part_name VARCHAR(200),
			   control_has_access_to_module_part_id INT,
			   control_created_datetime DATETIME,
			   control_created_datetime_print VARCHAR(200),
			   control_updated_datetime DATETIME,
			   control_updated_datetime_print VARCHAR(200)
			   )")
			   or die(mysqli_error($link));



}
?>