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

mysqli_query($link, "DROP TABLE IF EXISTS $t_domains_monitoring_filters_keywords");


echo"

	<!-- filters_keywords -->
	";
	$query = "SELECT * FROM $t_domains_monitoring_filters_keywords";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_domains_monitoring_filters_keywords: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_domains_monitoring_filters_keywords(
	  	 keyword_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(keyword_id), 
	  	   keyword_filter_id INT,
	  	   keyword_group_id INT,
	  	   keyword_user_id INT,
	  	   keyword_title VARCHAR(200),
	  	   keyword_type VARCHAR(200),
	  	   keyword_value VARCHAR(200),
	  	   keyword_value_length INT,
	  	   keyword_combinations VARCHAR(200),
	  	   keyword_domain_tlds VARCHAR(200),
	  	   keyword_added_datetime DATETIME,
	  	   keyword_updated_datetime DATETIME,
	  	   keyword_notes VARCHAR(200)

			)")
		   or die(mysqli_error());



		mysqli_query($link, "INSERT INTO $t_domains_monitoring_filters_keywords 
		(`keyword_id`, `keyword_filter_id`, `keyword_group_id`, `keyword_user_id`, `keyword_title`, `keyword_type`, `keyword_value`, `keyword_domain_tlds`, `keyword_added_datetime`, `keyword_updated_datetime`) 
		VALUES 
		(NULL, 1, 1, 1, 'text-text-integer', 'regex', '/([a-zA-Z]+)-([a-zA-Z]+)-([0-9])/', 'link, site', '2021-09-23 07:48:20', '2021-09-23 07:48:20')



")
		or die(mysqli_error($link));


	}
	echo"
	<!-- //filters_keywords -->
";
?>