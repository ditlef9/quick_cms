<?php
/**
*
* File: _admin/_inc/domains_monitoring/_liquidbase_db_scripts/index.php
* Version 1.0.0
* Date 14:28 25.03.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

mysqli_query($link, "DROP TABLE IF EXISTS $t_domains_monitoring_filters_index");


echo"

	<!-- filters_index -->
	";
	$query = "SELECT * FROM $t_domains_monitoring_filters_index";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_domains_monitoring_filters_index: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_domains_monitoring_filters_index(
	  	 filter_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(filter_id), 
	  	   filter_title VARCHAR(200),
	  	   filter_group_id INT,
	  	   filter_active INT,
	  	   filter_created_date DATE,
	  	   filter_created_date_saying VARCHAR(100),
	  	   filter_created_by_user_id INT,
	  	   filter_updated_date DATE,
	  	   filter_updated_date_saying VARCHAR(100),
	  	   filter_updated_by_user_id INT
		   )")
		   or die(mysqli_error());
	}


	// Insert filters
	mysqli_query($link, "INSERT INTO $t_domains_monitoring_filters_index
		(`filter_id`, `filter_group_id`, `filter_title`, `filter_active`, `filter_created_date`, `filter_created_date_saying`, `filter_created_by_user_id`, `filter_updated_date`, `filter_updated_date_saying`, `filter_updated_by_user_id`)
		VALUES 
		(NULL, 1, 'Bedrageri', 1, '2021-09-22', '22 Sep 2021', 1, '2021-09-22', '22 Sep 2021', 1)")
		or die(mysqli_error($link));


	echo"
	<!-- //filters_index -->
";
?>