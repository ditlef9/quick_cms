<?php
/**
*
* File: _admin/_inc/rebus/_liquibase/rebus/counties.php
* Version 1.0.0
* Date 07:23 01.07.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_rebus_games_geo_countries") or die(mysqli_error($link)); 


echo"
<!-- games_geo_countries -->
";

$query = "SELECT * FROM $t_rebus_games_geo_countries LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_rebus_games_geo_countries: $row_cnt</p>
	";
}
else{


	mysqli_query($link, "CREATE TABLE $t_rebus_games_geo_countries(
	  country_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(country_id), 
	   country_name VARCHAR(200),
	   country_flag_path_16x16 VARCHAR(200),
	   country_flag_16x16 VARCHAR(200),
	   country_created_datetime DATETIME,
	   country_created_by_user_id INT,
	   country_created_by_user_name VARCHAR(200),
	   country_created_by_user_email VARCHAR(200), 
	   country_created_by_ip VARCHAR(200), 
	   country_created_by_hostname VARCHAR(200), 
	   country_created_by_user_agent VARCHAR(200)
	   )")
	   or die(mysqli_error());

}
echo"
<!-- //games_geo_countries -->

";
?>