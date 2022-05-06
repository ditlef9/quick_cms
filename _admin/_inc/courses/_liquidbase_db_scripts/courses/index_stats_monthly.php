<?php
/**
*
* File: _admin/_inc/courses/_liquibase/courses/001_courses.php
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

echo"



<!-- courses_index_stats_monthly -->
";

$query = "SELECT * FROM $t_courses_index_stats_monthly LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	// Count rows
	$row_cnt = mysqli_num_rows($result);
	echo"
	<p>$t_courses_index_stats_monthly: $row_cnt</p>
	";
}
else{
	mysqli_query($link, "CREATE TABLE $t_courses_index_stats_monthly(
	  monthly_id INT NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY(monthly_id), 
	   monthly_course_id INT, 
	   monthly_year INT,
	   monthly_month INT,
	   monthly_users_enrolled_count INT, 
	   monthly_read_times INT,
	   monthly_read_times_ip_block TEXT)")
	   or die(mysqli_error());
}
echo"
<!-- //courses_index_stats_monthly -->

";
?>