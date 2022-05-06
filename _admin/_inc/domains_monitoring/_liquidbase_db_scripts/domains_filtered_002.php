<?php
/**
*
* File: _admin/_inc/domains_monitoring/_liquidbase_db_scripts/domains_filtered.php
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

mysqli_query($link, "DROP TABLE IF EXISTS $t_domains_monitoring_domains_filtered");


echo"

	<!-- domains_filtered -->
	";
	$query = "SELECT * FROM $t_domains_monitoring_domains_filtered";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_domains_monitoring_domains_filtered: $row_cnt</p>
		";
	}
	else{

		mysqli_query($link, "CREATE TABLE $t_domains_monitoring_domains_filtered(
	  	 filtered_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(filtered_id), 
	  	   filtered_domain_id INT,
	  	   filtered_domain_value VARCHAR(200),
	  	   filtered_group_id INT,
	  	   filtered_by_user_id INT,
	  	   filtered_date DATE,
	  	   filtered_date_saying VARCHAR(100),
	  	   filtered_datetime DATETIME,
	  	   filtered_domain_sld VARCHAR(200),
	  	   filtered_domain_tld VARCHAR(20),
	  	   filtered_domain_sld_length INT,
	  	   filtered_score INT,
	  	   filtered_domain_registered_date DATE,
	  	   filtered_domain_registered_date_saying VARCHAR(100),
	  	   filtered_domain_registered_datetime DATETIME,
	  	   filtered_domain_seen_before_times INT,
	  	   filtered_domain_ip VARCHAR(100),
	  	   filtered_domain_host_addr VARCHAR(100),
	  	   filtered_domain_host_name VARCHAR(100),
	  	   filtered_domain_host_url VARCHAR(100),
	  	   filtered_domain_filters_activated VARCHAR(100),
	  	   filtered_domain_seen_by_group INT,
	  	   filtered_domain_emailed INT,
	  	   filtered_notes VARCHAR(200)
			)")
		   or die(mysqli_error());
	}
	echo"
	<!-- //transactions -->
";
?>