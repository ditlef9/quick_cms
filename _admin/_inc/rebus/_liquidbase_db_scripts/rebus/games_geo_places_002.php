<?php
/**
*
* File: _admin/_inc/rebus/_liquibase/rebus/places.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_rebus_games_geo_places") or die(mysqli_error($link)); 


echo"
<!-- games_index -->
";

$query = "SELECT * FROM $t_rebus_games_geo_places LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_rebus_games_geo_places: $row_cnt</p>
	";
}
else{


	mysqli_query($link, "CREATE TABLE $t_rebus_games_geo_places(
	  place_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(place_id), 
	   place_name VARCHAR(200),
	   place_latitude DOUBLE,
	   place_longitude DOUBLE,
	   place_country_id INT, 
	   place_country_name VARCHAR(200), 
	   place_county_id INT, 
	   place_county_name VARCHAR(200), 
	   place_municipality_id INT, 
	   place_municipality_name VARCHAR(200), 
	   place_city_id INT, 
	   place_city_name VARCHAR(200), 
	   place_created_by_user_id INT,
	   place_created_by_user_name VARCHAR(200),
	   place_created_by_user_email VARCHAR(200), 
	   place_created_by_ip VARCHAR(200), 
	   place_created_by_hostname VARCHAR(200), 
	   place_created_by_user_agent VARCHAR(200)
	   )")
	   or die(mysqli_error());

	// Sofiemyr
	mysqli_query($link, "INSERT INTO $t_rebus_games_geo_places(`place_id`, `place_name`, `place_country_id`, `place_country_name`, `place_county_id`, `place_county_name`, `place_municipality_id`, `place_municipality_name`, `place_city_id`, `place_city_name`, `place_created_by_user_id`, `place_created_by_user_name`, `place_created_by_user_email`, `place_created_by_ip`, `place_created_by_hostname`, `place_created_by_user_agent`) VALUES
	(1, 'Sofiemyr', 166, 'Norway', 1, 'Viken', 1, 'Nordre Follo', 1, 'Sofiemyr', 1, 'Sigma', 'siditlef@gmail.com', '127.0.0.1', 'DESKTOP-B', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0')
	")
	or die(mysqli_error());

}
echo"
<!-- //games_index -->

";
?>