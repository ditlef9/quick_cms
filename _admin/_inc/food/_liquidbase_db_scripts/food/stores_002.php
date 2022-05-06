<?php
/**
*
* File: _admin/_inc/food/_liquibase/food/stores.php
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

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_food_stores") or die(mysqli_error($link)); 


echo"

	<!-- food_stores -->
	";
	$query = "SELECT * FROM $t_food_stores";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_food_stores: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_food_stores(
	  	 store_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(store_id), 
	  	   store_user_id INT, 
	  	   store_name VARCHAR(200), 
	  	   store_country VARCHAR(200), 
	  	   store_language VARCHAR(200), 
	  	   store_website VARCHAR(200), 
	  	   store_logo VARCHAR(200), 
	  	   store_icon_18x18 VARCHAR(200), 
	  	   store_added_datetime DATETIME, 
	  	   store_added_datetime_print VARCHAR(200), 
	  	   store_updatet_datetime DATETIME, 
	  	   store_updatet_datetime_print VARCHAR(200), 
	  	   store_user_ip VARCHAR(200),
	  	   store_reported VARCHAR(200), 
	  	   store_reported_checked VARCHAR(200))")
		   or die(mysqli_error());



		mysqli_query($link, "INSERT INTO $t_food_stores(
		store_id, store_user_id, store_name, store_country, store_language, store_website, store_logo, store_added_datetime, store_added_datetime_print, store_updatet_datetime, store_updatet_datetime_print, store_user_ip, store_reported, store_reported_checked)
		VALUES 
		(1, 1, 'Rema 1000', 'Norway', 'no', 'https://rema1000.no', '', '2020-10-19 17:07:32', '19 Oct 2020', '2020-10-19 17:07:32', '19 Oct 2020', '79.160.26.221', 0, ''),
		(2, 1, 'SPAR', 'Norway', 'no', 'https://spar.no', '', '2020-10-19 17:07:40', '19 Oct 2020', '2020-10-19 17:07:40', '19 Oct 2020', '79.160.26.221', 0, ''),
		(3, 1, 'Kiwi', 'Norway', 'no', 'https://kiwi.no', '', '2020-10-19 17:07:49', '19 Oct 2020', '2020-10-19 17:07:49', '19 Oct 2020', '79.160.26.221', 0, ''),
		(4, 1, 'Coop Extra', 'Norway', 'no', 'https://coop.no/extra', '', '2020-10-24 10:22:44', '24 Oct 2020', '2020-10-24 10:22:44', '24 Oct 2020', '79.160.26.221', 0, ''),
		(5, 1, 'Coop Obs', 'Norway', 'no', 'https://obs.no', '', '2021-04-13 13:45:10', '13 Apr 2021', '2021-04-13 13:46:12', '13 Apr 2021', '81.166.21.168', 0, ''),
		(6, 1, 'Proteinfabrikken', 'Norway', 'no', 'https://www.proteinfabrikken.no', '', '2021-04-14 17:02:57', '14 Apr 2021', '2021-04-14 17:02:57', '14 Apr 2021', '81.166.21.168', 0, ''),
		(7, 1, 'Oda', 'Norway', 'no', 'https://oda.com', '', '2021-04-26 11:30:35', '26 Apr 2021', '2021-06-02 07:41:11', '2 Jun 2021', '81.166.21.168', 0, ''),
		(8, 1, 'Meny', 'Norway', 'no', 'https://meny.no', '', '2021-04-26 16:30:50', '26 Apr 2021', '2021-04-26 16:30:50', '26 Apr 2021', '2a01:799:111c:c00:f5ea:bcb0:7594:fd73', 0, ''),
		(9, 1, 'Coop Mega', 'Norway', 'no', 'https://coop.no/mega', '', '2021-04-28 07:30:56', '28 Apr 2021', '2021-04-28 07:30:56', '28 Apr 2021', '2a01:799:111c:c00:d104:e1b8:c525:825c', 0, ''),
		(10, 1, 'Mathallen Oslo', 'Norway', 'no', 'https://mathallenoslo.no', '', '2021-08-14 09:03:39', '14 Aug 2021', '2021-08-14 09:11:47', '14 Aug 2021', '37.191.249.39', 0, ''),
		(11, 1, 'Cemo', 'Norway', 'no', 'https://cemo.no', '', '2021-12-25 09:25:37', '25 Dec 2021', '2021-12-25 09:25:37', '25 Dec 2021', '46.46.202.49', 0, ''),
		(12, 1, 'Dellback', 'Norway', 'no', 'https://dellback.se', '', '2021-12-26 21:06:27', '26 Dec 2021', '2021-12-26 21:06:27', '26 Dec 2021', '46.46.202.49', 0, ''),
		(13, 1, 'Vinmonopolet', 'Norway', 'no', 'https://vinmonopolet.no', '', '2022-01-05 11:37:10', '5 Jan 2022', '2022-01-05 11:37:10', '5 Jan 2022', '46.46.202.156', 0, '')")
		or die(mysqli_error());



	}
	echo"
	<!-- //food_stores -->


";
?>