<?php
/**
*
* File: _admin/_inc/rebus/_liquibase/rebus/games_index_geo_distance_measurements
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_rebus_games_index_geo_distance_measurements") or die(mysqli_error($link)); 


echo"
<!-- games_index_geo_distance_measurements -->
";

$query = "SELECT * FROM $t_rebus_games_index_geo_distance_measurements LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_rebus_games_index_geo_distance_measurements: $row_cnt</p>
	";
}
else{

	echo"<p><em>Creating table $t_rebus_games_index_geo_distance_measurements</em></p>\n";

	mysqli_query($link, "CREATE TABLE $t_rebus_games_index_geo_distance_measurements(
	  measurement_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(measurement_id), 
	   measurement_from_latitude DOUBLE,
	   measurement_from_longitude DOUBLE,
	   measurement_game_id INT,
	   measurement_game_language VARCHAR(5),
	   measurement_game_latitude DOUBLE,
	   measurement_game_longitude DOUBLE,
	   measurement_distance_meters INT,
	   measurement_distance_metric_saying VARCHAR(50),
	   measurement_distance_imperial_saying VARCHAR(50),
	   measurement_updated_year INT
	   )")
	   or die(mysqli_error());


}
echo"
<!-- //games_index_geo_distance_measurements -->

";
?>