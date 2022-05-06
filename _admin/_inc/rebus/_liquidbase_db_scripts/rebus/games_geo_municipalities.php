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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_rebus_games_geo_municipalities") or die(mysqli_error($link)); 


echo"
<!-- games_index -->
";

$query = "SELECT * FROM $t_rebus_games_geo_municipalities LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_rebus_games_geo_municipalities: $row_cnt</p>
	";
}
else{


	mysqli_query($link, "CREATE TABLE $t_rebus_games_geo_municipalities(
	  municipality_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(municipality_id), 
	   municipality_name VARCHAR(200),
	   municipality_country_id INT, 
	   municipality_country_name VARCHAR(200), 
	   municipality_county_id INT, 
	   municipality_county_name VARCHAR(200), 
	   municipality_created_by_user_id INT,
	   municipality_created_by_user_name VARCHAR(200),
	   municipality_created_by_user_email VARCHAR(200), 
	   municipality_created_by_ip VARCHAR(200), 
	   municipality_created_by_hostname VARCHAR(200), 
	   municipality_created_by_user_agent VARCHAR(200)
	   )")
	   or die(mysqli_error());

	// Nordre Follo
	mysqli_query($link, "INSERT INTO $t_rebus_games_geo_municipalities (`municipality_id`, `municipality_name`, `municipality_country_id`, `municipality_country_name`, `municipality_county_id`, `municipality_county_name`, `municipality_created_by_user_id`, `municipality_created_by_user_name`, `municipality_created_by_user_email`, `municipality_created_by_ip`, `municipality_created_by_hostname`, `municipality_created_by_user_agent`) VALUES
	(1, 'Nordre Follo', 166, 'Norway', 1, 'Viken', 1, 'Sigma', 'siditlef@gmail.com', '127.0.0.1', 'DESKTOP-B', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0')
	")
	or die(mysqli_error());
}
echo"
<!-- //games_index -->

";
?>