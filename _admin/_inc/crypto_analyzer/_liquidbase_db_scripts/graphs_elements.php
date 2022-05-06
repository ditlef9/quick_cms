<?php
/**
*
* File: _scripts/db/_liquidbase_db_scripts/graphs_elements.php
* Version 1.0.0
* Date 14:28 25.03.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

mysqli_query($link, "DROP TABLE IF EXISTS $t_cran_graphs_elements");


echo"

	<!-- graphs_elements -->
	";
	$query = "SELECT * FROM $t_cran_graphs_elements";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_cran_graphs_elements: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_cran_graphs_elements(
	  	 element_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(element_id), 
	  	   element_graph_id INT,
	  	   element_group VARCHAR(200), 
	  	   element_type VARCHAR(200), 
	  	   element_headline VARCHAR(200), 
	  	   element_text TEXT, 
	  	   element_date VARCHAR(10), 
	  	   element_time VARCHAR(8), 
	  	   element_datetime_saying VARCHAR(100), 
	  	   element_position_top INT,
	  	   element_position_left INT,
	  	   element_path VARCHAR(200), 
	  	   element_path_left VARCHAR(200), 
	  	   element_path_right VARCHAR(200), 
	  	   element_connection_top_to_element_ids VARCHAR(200), 
	  	   element_connection_right_to_element_ids VARCHAR(200), 
	  	   element_connection_bottom_to_element_ids VARCHAR(200), 
	  	   element_connection_left_to_element_ids VARCHAR(200), 
	  	   element_width INT,
	  	   element_height INT,
	  	   element_thickness INT,
	  	   element_border_color VARCHAR(200), 
	  	   element_background_color VARCHAR(200), 
	  	   element_text_color VARCHAR(200), 
	  	   element_arrow_left_type VARCHAR(200), 
	  	   element_arrow_left_path VARCHAR(200), 
	  	   element_arrow_left_color VARCHAR(200), 
	  	   element_arrow_right_type VARCHAR(200), 
	  	   element_arrow_right_path VARCHAR(200), 
	  	   element_arrow_right_color VARCHAR(200), 
	  	   
	  	   element_added_by_user_id INT,
	  	   element_added_datetime DATETIME,
	  	   element_updated_by_user_id INT,
	  	   element_updated_datetime DATETIME)")
		   or die(mysqli_error());



		mysqli_query($link, "INSERT INTO $t_cran_graphs_elements
		(`element_id`, `element_graph_id`, `element_group`, `element_type`, `element_headline`, `element_text`, `element_date`, `element_time`, `element_datetime_saying`, `element_position_top`, `element_position_left`, `element_path`, `element_path_left`, `element_path_right`, `element_connection_top_to_element_ids`, `element_connection_right_to_element_ids`, `element_connection_bottom_to_element_ids`, `element_connection_left_to_element_ids`, `element_width`, `element_height`, `element_thickness`, `element_border_color`, `element_background_color`, `element_text_color`, `element_arrow_left_type`, `element_arrow_left_path`, `element_arrow_left_color`, `element_arrow_right_type`, `element_arrow_right_path`, `element_arrow_right_color`, `element_added_by_user_id`, `element_added_datetime`, `element_updated_by_user_id`, `element_updated_datetime`) 
		VALUES 
		(NULL, 1, 'text_boxes', 'text_box', '', 'Start box', '', '', '', 0, 0, NULL, '', '', '', '', '', '', 150, 100, NULL, '#000000', '#ffffff', '#000000', '', '', '', '', '', '', 1, '2021-10-07 12:40:39', 1, '2021-10-07 12:40:39'),
		(NULL, 1, 'text_boxes', 'text_box', '', 'Box 2', '', '', '', 0, 250, NULL, '', '', '', '', '', '', 150, 100, NULL, '#000000', '#ffffff', '#000000', '', '', '', '', '', '', 1, '2021-10-07 12:41:10', 1, '2021-10-07 12:41:10')
		")  or die(mysqli_error());



	}
	echo"
	<!-- //graphs_elements -->
";
?>