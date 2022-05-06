<?php
/**
*
* File: _admin/_inc/courses/_liquibase/calendar/001_calendar.php
* Version 1.0.0
* Date 21:19 28.08.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_office_calendar_locations") or die(mysqli_error($link)); 
$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_office_calendar_equipments") or die(mysqli_error($link)); 
$result = mysqli_query($link, "DROP TABLE IF EXISTS $t_office_calendar_events") or die(mysqli_error($link)); 


echo"

<!-- office_calendar_locations -->
";

$query = "SELECT * FROM $t_office_calendar_locations	LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_office_calendar_locations: $row_cnt</p>
	";
}
else{
	mysqli_query($link, "CREATE TABLE $t_office_calendar_locations(
	  location_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(location_id), 
	   location_title VARCHAR(200), 
	   location_bg_color VARCHAR(200), 
	   location_text_color VARCHAR(200))")
	   or die(mysqli_error());	

	mysqli_query($link, "INSERT INTO $t_office_calendar_locations
	(location_id, location_title, location_bg_color, location_text_color) 
	VALUES 
	(NULL, 'Haugesund', '#8e24aa', '#fff'),
	(NULL, 'Stavanger', '#3f51b5', '#fff')
	") or die(mysqli_error($link));


}
echo"
<!-- //office_calendar_locations -->

<!-- office_calendar_equipment -->
";

$query = "SELECT * FROM $t_office_calendar_equipments LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_office_calendar_equipments: $row_cnt</p>
	";
}
else{
	mysqli_query($link, "CREATE TABLE $t_office_calendar_equipments(
	  equipment_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(equipment_id), 
	   equipment_location_id INT,
	   equipment_location_title VARCHAR(200), 
	   equipment_title VARCHAR(200), 
	   equipment_description VARCHAR(200), 
	   equipment_sub_description VARCHAR(200), 
	   equipment_barcode VARCHAR(200))")
	   or die(mysqli_error());

	mysqli_query($link, "INSERT INTO $t_office_calendar_equipments
	(equipment_id, equipment_location_id, equipment_location_title, equipment_title, equipment_description, equipment_sub_description, equipment_barcode) 
	VALUES 
	(NULL, 1, 'Haugesund', 'DPAHaugForensic1', 'Gjennomgangsmaskin 1', '10.1.0.1', '1'),
	(NULL, 1, 'Haugesund', 'DPAHaugForensic2', 'Gjennomgangsmaskin 2', '10.1.0.2', '1'),
	(NULL, 2, 'Stavanger', 'DPAStavForensic1', 'Gjennomgangsmaskin 1', '10.1.0.3', '1'),
	(NULL, 2, 'Stavanger', 'DPAStavForensic2', 'Gjennomgangsmaskin 2', '10.1.0.4', '1')
	") or die(mysqli_error($link));

}
echo"
<!-- //office_calendar_equipments -->


<!-- office_calendar_events -->
";

$query = "SELECT * FROM $t_office_calendar_events LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_office_calendar_events: $row_cnt</p>
	";
}
else{
	mysqli_query($link, "CREATE TABLE $t_office_calendar_events(
	  event_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(event_id), 
	   event_user_id INT,
	   event_user_name VARCHAR(200), 
	   event_user_ip VARCHAR(200), 
	   event_user_remote_addr VARCHAR(200),
	   event_created_datetime DATETIME,
	   event_updated_datetime DATETIME,
	   event_location_id INT,
	   event_location_title VARCHAR(200), 
	   event_equipment_id INT,
	   event_equipment_title VARCHAR(200), 
	   event_text TEXT,
	   event_bg_color VARCHAR(200), 
	   event_text_color VARCHAR(200), 
	   event_from_datetime DATETIME,
	   event_from_time VARCHAR(200), 
	   event_from_day VARCHAR(2), 
	   event_from_month VARCHAR(2), 
	   event_from_year VARCHAR(4), 
	   event_from_hour VARCHAR(2), 
	   event_from_minute VARCHAR(2), 
	   event_from_saying_date_time VARCHAR(200),
	   event_to_datetime DATETIME,
	   event_to_time VARCHAR(200),
	   event_to_day VARCHAR(2), 
	   event_to_month VARCHAR(2), 
	   event_to_year VARCHAR(4), 
	   event_to_hour VARCHAR(2), 
	   event_to_minute VARCHAR(2),
	   event_to_saying_date_time VARCHAR(200))")
	   or die(mysqli_error());
}
echo"
<!-- //office_calendar_events -->
";
?>