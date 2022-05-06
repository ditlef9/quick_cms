<?php
/**
*
* File: _scripts/db/_liquidbase_db_scripts/graphs_index.php
* Version 1.0.0
* Date 14:28 25.03.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
mysqli_query($link, "DROP TABLE IF EXISTS $t_cran_graphs_index");


echo"

	<!-- graphs_index -->
	";
	$query = "SELECT * FROM $t_cran_graphs_index";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_cran_graphs_index: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_cran_graphs_index(
	  	 graph_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(graph_id), 
	  	   graph_title VARCHAR(200),
	  	   graph_group_id INT,
	  	   graph_created_by_user_id INT,
	  	   graph_created_datetime DATETIME,
	  	   graph_created_date_saying VARCHAR(100),
	  	   graph_updated_by_user_id INT,
	  	   graph_updated_datetime DATETIME,
	  	   graph_updated_date_saying VARCHAR(100))")
		   or die(mysqli_error());



		mysqli_query($link, "INSERT INTO $t_cran_graphs_index 
		(`graph_id`, `graph_title`, `graph_group_id`, `graph_created_by_user_id`, `graph_updated_by_user_id`, `graph_updated_datetime`) 
		VALUES 
		(NULL, 'Demo Graph', '1', '1', '1', '2021-10-03 10:17:02')")  or die(mysqli_error());


	}
	echo"
	<!-- //graphs_index -->
";
?>