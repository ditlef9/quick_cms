<?php
/**
*
* File: _admin/_inc/recipes/_liquidbase_db_scripts/recipes_occasions.php
* Version 1.0.0
* Date 17:21 31.12.2020
* Copyright (c) 2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

echo"

	<!-- $t_recipes_occasions -->
	";
	$query = "SELECT * FROM $t_recipes_occasions";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_recipes_occasions: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_recipes_occasions(
	  	occasion_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(occasion_id), 
	  	   occasion_name VARCHAR(250),
	  	   occasion_day INT,
	  	   occasion_month INT,
	  	   occasion_image VARCHAR(250), 
	  	   occasion_last_updated DATE)")
		   or die(mysqli_error());

		mysqli_query($link, "INSERT INTO $t_recipes_occasions
		(occasion_id, occasion_name, occasion_day, occasion_month) 
		VALUES 
		(NULL, 'Thanksgiving', '23', '11'),
		(NULL, 'Christmas', '24', '12'),
		(NULL, 'Hanukkah', '12', '12'),
		(NULL, 'New Year', '31', '12'),
		(NULL, 'Burns Night', '25', '01'),
		(NULL, 'Valentines Day', '14', '02'),
		(NULL, 'Chinese New Year', '16', '02'),
		(NULL, 'Pancake Day', '28', '02'),
		(NULL, 'St Davids Day', '01', '03'),
		(NULL, 'Mothers Day', '11', '03'),
		(NULL, 'St Patricks Day', '17', '03'),
		(NULL, 'Passover', '30', '03'),
		(NULL, 'Easter', '01', '04'),
		(NULL, 'Thai New Year', '13', '04'),
		(NULL, 'Baisakhi', '14', '04'),
		(NULL, 'St Georges Day', '23', '04'),
		(NULL, 'Eid', '14', '05'),
		(NULL, 'Fathers Day', '17', '06'),
		(NULL, 'Barbecue', '22', '06'),
		(NULL, 'Picnic', '23', '06'),
		(NULL, 'Student food', '01', '09'),
		(NULL, 'Diwali', '09', '10'),
		(NULL, 'Halloween', '31', '10'),
		(NULL, 'Bonfire Night', '05', '11')")
		or die(mysqli_error($link));
	}
	echo"
	<!-- //$t_recipes_occasions -->


";
?>