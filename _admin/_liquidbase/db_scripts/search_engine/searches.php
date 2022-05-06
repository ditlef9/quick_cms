<?php
if(isset($_SESSION['admin_user_id'])){
	$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
	$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";
	$t_search_engine_searches	= $mysqlPrefixSav . "search_engine_searches";


	mysqli_query($link,"DROP TABLE IF EXISTS $t_search_engine_searches") or die(mysqli_error());


	mysqli_query($link, "CREATE TABLE $t_search_engine_searches(
			   search_id INT NOT NULL AUTO_INCREMENT,
			   PRIMARY KEY(search_id), 
			   search_query VARCHAR(200),
			   search_unique_counter INT,
			   search_unique_ip_block TEXT,
			   search_number_of_results INT,
			   search_language_used VARCHAR(200),
			   search_created_datetime DATETIME,
			   search_created_datetime_print VARCHAR(200),
			   search_updated_datetime DATETIME,
			   search_updated_datetime_print VARCHAR(200)
			   )")
			   or die(mysqli_error($link));


}
?>