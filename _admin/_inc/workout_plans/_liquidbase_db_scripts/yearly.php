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

mysqli_query($link, "DROP TABLE IF EXISTS $t_workout_plans_yearly");


echo"

	<!-- $t_workout_plans_yearly -->
	";
	$query = "SELECT * FROM $t_workout_plans_yearly";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_workout_plans_yearly: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_workout_plans_yearly(
	  	 workout_yearly_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(workout_yearly_id), 
	  	   workout_yearly_user_id INT,
	  	   workout_yearly_weight INT,
		   workout_yearly_language VARCHAR(20),
	  	   workout_yearly_title VARCHAR(250),
	  	   workout_yearly_title_clean VARCHAR(250),
	  	   workout_yearly_introduction TEXT,
	  	   workout_yearly_goal TEXT,
	  	   workout_yearly_text TEXT,
	  	   workout_yearly_year INT,
	  	   workout_yearly_image_path VARCHAR(250),
	  	   workout_yearly_image_file VARCHAR(250),
	  	   workout_yearly_created DATETIME,
	  	   workout_yearly_updated DATETIME,
	  	   workout_yearly_unique_hits INT,
	  	   workout_yearly_unique_hits_ip_block TEXT,
	  	   workout_yearly_comments INT,
	  	   workout_yearly_likes INT,
	  	   workout_yearly_dislikes INT,
	  	   workout_yearly_rating INT,
	  	   workout_yearly_ip_block TEXT,
		   workout_yearly_user_ip VARCHAR(250),
	  	   workout_yearly_notes TEXT)")
		   or die(mysqli_error());
	}
	echo"
	<!-- //$t_workout_plans_yearly -->
";
?>