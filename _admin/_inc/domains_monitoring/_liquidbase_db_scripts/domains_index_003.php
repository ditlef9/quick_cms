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

mysqli_query($link, "DROP TABLE IF EXISTS $t_domains_monitoring_domains_index");


echo"

	<!-- domains_index -->
	";
	$query = "SELECT * FROM $t_domains_monitoring_domains_index";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_domains_monitoring_domains_index: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_domains_monitoring_domains_index(
	  	 domain_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(domain_id), 
	  	   domain_value VARCHAR(200),
	  	   domain_sld VARCHAR(200),
	  	   domain_tld VARCHAR(20),
	  	   domain_sld_length INT,
	  	   domain_registered_date DATE,
	  	   domain_registered_date_saying VARCHAR(100),
	  	   domain_registered_datetime DATETIME,
	  	   domain_created_date DATE,
	  	   domain_seen_before_times INT,
	  	   domain_checked_ip_by_script INT,
	  	   domain_checked_other_by_script INT,
	  	   domain_checked_starts_with_ends_with_by_script INT,
	  	   domain_ip VARCHAR(100),
	  	   domain_host_addr VARCHAR(100),
	  	   domain_host_name VARCHAR(100),
	  	   domain_host_url VARCHAR(100),
	  	   domain_filters_activated VARCHAR(100)
			)")
		   or die(mysqli_error());
	}
	echo"
	<!-- //transactions -->
";
?>