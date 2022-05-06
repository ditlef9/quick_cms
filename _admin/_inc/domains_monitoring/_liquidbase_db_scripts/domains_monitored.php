<?php
/**
*
* File: _admin/_inc/domains_monitoring/_liquidbase_db_scripts/domains_monitored.php
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

mysqli_query($link, "DROP TABLE IF EXISTS $t_domains_monitoring_domains_monitored");


echo"

	<!-- domains_monitored -->
	";
	$query = "SELECT * FROM $t_domains_monitoring_domains_monitored";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_domains_monitoring_domains_monitored: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_domains_monitoring_domains_monitored(
	  	 monitored_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(monitored_id), 
	  	   monitored_domain_id INT,
	  	   monitored_domain_value VARCHAR(200),
	  	   monitored_group_id INT,
	  	   monitored_by_user_id INT,
	  	   monitored_notes TEXT,
	  	   monitored_date DATE,
	  	   monitored_date_saying VARCHAR(100),
	  	   monitored_datetime DATETIME,
	  	   monitored_domain_sld VARCHAR(200),
	  	   monitored_domain_tld VARCHAR(20),
	  	   monitored_domain_sld_length INT,
	  	   monitored_domain_registered_date DATE,
	  	   monitored_domain_registered_date_saying VARCHAR(100),
	  	   monitored_domain_registered_datetime DATETIME,
	  	   monitored_domain_seen_before_times INT,
	  	   monitored_domain_ip VARCHAR(100),
	  	   monitored_domain_host_addr VARCHAR(100),
	  	   monitored_domain_host_name VARCHAR(100),
	  	   monitored_domain_host_url VARCHAR(100),
	  	   monitored_domain_filters_activated VARCHAR(100),
	  	   monitored_domain_seen_by_group INT,
	  	   monitored_domain_emailed INT
			)")
		   or die(mysqli_error());
	}
	echo"
	<!-- //transactions -->
";
?>