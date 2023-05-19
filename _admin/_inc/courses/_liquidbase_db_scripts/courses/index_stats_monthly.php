<?php
/**
*
* File: _admin/_inc/courses/_liquibase/courses/001_courses.php
* Version 2
* Copyright (c) 2019-2023 Sindre Andre Ditlefsen
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
	<p>Create table: $t_courses_index_stats_monthly</p>
";



$mysqli->query("DROP TABLE IF EXISTS $t_courses_index_stats_monthly");

if (!$mysqli -> query("CREATE TABLE $t_courses_index_stats_monthly(
	monthly_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(monthly_id), 
	 monthly_course_id INT, 
	 monthly_year INT,
	 monthly_month INT,
	 monthly_users_enrolled_count INT, 
	 monthly_read_times INT,
	 monthly_read_times_ip_block TEXT)")) {
	echo("MySQLI create table error: " . $mysqli -> error); die;
}


echo"
<!-- //courses_index_stats_monthly -->

";
?>