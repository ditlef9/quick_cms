<?php
/**
*
* File: _admin/_inc/food/_liquibase/food/measurements.php
* Version 1.0.0
* Date 15:43 18.10.2020
* Copyright (c) 2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_food_measurements") or die(mysqli_error($link)); 


echo"

	<!-- food_measurements -->
	";

	
	$query = "SELECT * FROM $t_food_measurements";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_food_measurements: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_food_measurements(
	  	 measurement_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(measurement_id), 
	  	   measurement_name VARCHAR(50), 
	  	   measurement_last_updated DATETIME)")
		   or die(mysqli_error());


		
/* `thefitpot_com`.`tfp_food_measurements` */
$food_measurements = array(
  array('measurement_id' => '1','measurement_name' => 'bag','measurement_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_id' => '2','measurement_name' => 'bowl','measurement_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_id' => '3','measurement_name' => 'box','measurement_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_id' => '4','measurement_name' => 'handful','measurement_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_id' => '5','measurement_name' => 'package','measurement_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_id' => '6','measurement_name' => 'piece','measurement_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_id' => '7','measurement_name' => 'pizza','measurement_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_id' => '8','measurement_name' => 'slice','measurement_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_id' => '9','measurement_name' => 'spoon','measurement_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_id' => '10','measurement_name' => 'teaspoon','measurement_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_id' => '11','measurement_name' => 'tablespoon','measurement_last_updated' => '2020-10-18 21:05:59'),
  array('measurement_id' => '12','measurement_name' => 'cup','measurement_last_updated' => '2020-10-19 18:13:24'),
  array('measurement_id' => '13','measurement_name' => 'roll','measurement_last_updated' => '2020-10-24 10:21:55'),
  array('measurement_id' => '14','measurement_name' => 'whole','measurement_last_updated' => '2021-01-31 23:40:03'),
  array('measurement_id' => '15','measurement_name' => 'spray','measurement_last_updated' => '2021-02-04 20:21:01'),
  array('measurement_id' => '16','measurement_name' => 'stick','measurement_last_updated' => '2021-02-05 19:40:19'),
  array('measurement_id' => '17','measurement_name' => 'baguette','measurement_last_updated' => '2021-03-07 20:26:43'),
  array('measurement_id' => '18','measurement_name' => 'glas','measurement_last_updated' => '2021-03-21 17:28:11'),
  array('measurement_id' => '19','measurement_name' => 'g','measurement_last_updated' => '2021-03-21 18:06:29'),
  array('measurement_id' => '20','measurement_name' => 'sausage bread','measurement_last_updated' => '2021-04-12 18:35:41'),
  array('measurement_id' => '21','measurement_name' => 'tortilla','measurement_last_updated' => '2021-04-12 18:47:47'),
  array('measurement_id' => '23','measurement_name' => 'filet','measurement_last_updated' => '2021-04-13 12:47:01'),
  array('measurement_id' => '24','measurement_name' => 'lump','measurement_last_updated' => '2021-04-13 12:57:55'),
  array('measurement_id' => '25','measurement_name' => 'measuring spoon 60 ml','measurement_last_updated' => '2021-04-14 17:00:20'),
  array('measurement_id' => '26','measurement_name' => 'melon','measurement_last_updated' => '2021-04-14 17:24:08'),
  array('measurement_id' => '27','measurement_name' => 'piece of cake','measurement_last_updated' => '2021-04-26 16:29:57'),
  array('measurement_id' => '28','measurement_name' => 'sausage','measurement_last_updated' => '2021-04-28 07:54:50'),
  array('measurement_id' => '29','measurement_name' => 'potato','measurement_last_updated' => '2021-04-28 08:30:57'),
  array('measurement_id' => '30','measurement_name' => 'cookie','measurement_last_updated' => '2021-05-27 10:29:40'),
  array('measurement_id' => '31','measurement_name' => 'ice stick','measurement_last_updated' => '2021-05-28 10:31:37'),
  array('measurement_id' => '32','measurement_name' => 'porsjon','measurement_last_updated' => '2021-12-19 22:17:21'),
  array('measurement_id' => '33','measurement_name' => 'cheese','measurement_last_updated' => '2021-12-20 13:11:41'),
  array('measurement_id' => '34','measurement_name' => 'bottle','measurement_last_updated' => '2021-12-24 12:28:20'),
  array('measurement_id' => '35','measurement_name' => 'gingerbread','measurement_last_updated' => '2021-12-25 08:40:02'),
  array('measurement_id' => '36','measurement_name' => 'steak','measurement_last_updated' => '2022-01-05 11:47:05'),
  array('measurement_id' => '37','measurement_name' => 'cake','measurement_last_updated' => '2022-02-06 20:19:12'),
  array('measurement_id' => '38','measurement_name' => 'egg','measurement_last_updated' => '2022-02-19 12:40:21'),
  array('measurement_id' => '39','measurement_name' => 'mushroom','measurement_last_updated' => '2022-03-06 09:11:38'),
  array('measurement_id' => '40','measurement_name' => 'burger','measurement_last_updated' => '2022-03-06 09:58:57'),
  array('measurement_id' => '41','measurement_name' => 'stalks','measurement_last_updated' => '2022-03-06 12:43:19'),
  array('measurement_id' => '42','measurement_name' => 'piece of fish','measurement_last_updated' => '2022-03-11 11:56:26'),
  array('measurement_id' => '43','measurement_name' => 'pita bread','measurement_last_updated' => '2022-03-16 17:08:14'),
  array('measurement_id' => '44','measurement_name' => 'flat bread','measurement_last_updated' => '2022-04-07 09:57:06'),
  array('measurement_id' => '45','measurement_name' => 'bar','measurement_last_updated' => '2022-04-07 12:59:48'),
  array('measurement_id' => '46','measurement_name' => 'chips','measurement_last_updated' => '2022-04-09 19:04:54'),
  array('measurement_id' => '47','measurement_name' => 'taco shell','measurement_last_updated' => '2022-04-11 12:13:47')
);

		

		foreach ($food_measurements as $measurements) {
			$measurement_id			= quote_smart($link, $measurements['measurement_id']);
			$measurement_name		= quote_smart($link, $measurements['measurement_name']);
			$measurement_last_updated	= quote_smart($link, $measurements['measurement_last_updated']);


			mysqli_query($link, "INSERT INTO $t_food_measurements
			(measurement_id, measurement_name, measurement_last_updated) 
			VALUES 
			($measurement_id, $measurement_name, $measurement_last_updated)") or die(mysqli_error($link));

		} // foreach

	}

	echo"
";
?>