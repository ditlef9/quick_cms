<?php
/**
*
* File: _admin/_inc/workout_plans/_liquidbase_db_scripts/entries.php
* Version 1.0.0
* Date 14:28 25.03.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

mysqli_query($link, "DROP TABLE IF EXISTS $t_workout_plans_weekly");


echo"

	<!-- workout_weekly_plans -->
	";
	$query = "SELECT * FROM $t_workout_plans_weekly";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_workout_plans_weekly: $row_cnt</p>
		";
	}
	else{

		mysqli_query($link, "CREATE TABLE $t_workout_plans_weekly(
	  	 workout_weekly_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(workout_weekly_id), 
	  	   workout_weekly_user_id INT,
	  	   workout_weekly_period_id INT,
	  	   workout_weekly_weight INT,
		   workout_weekly_language VARCHAR(20),
	  	   workout_weekly_title VARCHAR(250),
	  	   workout_weekly_title_clean VARCHAR(250),
	  	   workout_weekly_introduction TEXT,
	  	   workout_weekly_text TEXT,
	  	   workout_weekly_goal TEXT,
	  	   workout_weekly_image_path VARCHAR(250),
	  	   workout_weekly_image_file VARCHAR(250),
	  	   workout_weekly_image_thumb_400x225 VARCHAR(250),
	  	   workout_weekly_created DATETIME,
	  	   workout_weekly_updated DATETIME,
	  	   workout_weekly_unique_hits INT,
	  	   workout_weekly_unique_hits_ip_block TEXT,
	  	   workout_weekly_comments INT,
	  	   workout_weekly_likes INT,
	  	   workout_weekly_dislikes INT,
	  	   workout_weekly_rating INT,
	  	   workout_weekly_ip_block TEXT,
		   workout_weekly_user_ip VARCHAR(250),
	  	   workout_weekly_notes TEXT,
	  	   workout_weekly_number_of_sessions INT)")
		   or die(mysqli_error());

		$date = date("Y-m-d");
		$datetime = date("Y-m-d H:i:s");
		/*
		mysqli_query($link, "INSERT INTO $t_workout_plans_weekly
		(workout_weekly_id, workout_weekly_user_id, workout_weekly_language, workout_weekly_title, workout_weekly_introduction, workout_weekly_goal, workout_weekly_created, workout_weekly_updated, workout_weekly_unique_hits, workout_weekly_unique_hits_ip_block, workout_weekly_comments, workout_weekly_likes, workout_weekly_dislikes, workout_weekly_rating, workout_weekly_ip_block, workout_weekly_user_ip, workout_weekly_notes) 
		VALUES 
		(NULL, '1', 'en', '7 days strenght training', 'Here is a 7 day strenght training plan to increase muscle strenght', 'Increase muscle strenght', '$datetime', '$datetime', '0', '', '0', '0', '0', '0', '', '', '')")
		or die(mysqli_error($link));
		*/
	}
	echo"
	<!-- //workout_weekly -->
";
?>