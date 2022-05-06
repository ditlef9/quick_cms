<?php
/**
*
* File: _admin/_inc/domains_monitoring/_liquidbase_db_scripts/domains_tld_count.php
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

mysqli_query($link, "DROP TABLE IF EXISTS $t_domains_monitoring_domains_tld_count");


echo"

	<!-- tld_count -->
	";
	$query = "SELECT * FROM $t_domains_monitoring_domains_tld_count";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_domains_monitoring_domains_tld_count: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_domains_monitoring_domains_tld_count(
	  	 count_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(count_id), 
	  	   count_date DATE,
	  	   count_date_saying VARCHAR(100),
	  	   count_tld VARCHAR(100),
	  	   count_domains INT)")
		   or die(mysqli_error());

		// Dates
		$date = date("Y-m-d");
		$date_saying = date("j M Y");

		// Count
		$query = "SELECT domain_tld, count(*) FROM $t_domains_monitoring_domains_index GROUP BY domain_tld";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_domain_tld, $get_domains) = $row;

			$inp_domain_tld = output_html($get_domain_tld);

			// Insert
			$sql = "INSERT INTO $t_domains_monitoring_domains_tld_count
						(count_id, count_date, count_date_saying, count_tld, count_domains) 
						VALUES 
						(NULL, ?, ?, ?, ?)";
			$stmt = $link->prepare($sql);
			$stmt->bind_param("ssss", $date, $date_saying, $inp_domain_tld, $get_domains);
			$stmt->execute();
			if ($stmt->errno) {
				echo "DB failure " . $stmt->error; die;
			}

		} // while
	}
	echo"
	<!-- //tld_count -->
";
?>