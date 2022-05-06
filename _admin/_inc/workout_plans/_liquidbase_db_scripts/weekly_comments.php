<?php
/**
*
* File: _admin/_inc/workout_plans/_liquidbase_db_scripts/weekly_comments.php
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

mysqli_query($link, "DROP TABLE IF EXISTS $t_workout_plans_weekly_comments");


echo"

	<!-- workout_plans_weekly_comments -->
	";
	$query = "SELECT * FROM $t_workout_plans_weekly_comments";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_workout_plans_weekly_comments: $row_cnt</p>
		";
	}
	else{
		mysqli_query($link, "CREATE TABLE $t_workout_plans_weekly_comments(
	  	 comment_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(comment_id), 
	  	   comment_plan_id INT,
	  	   comment_language VARCHAR(20),
	  	   comment_approved INT,
		   comment_datetime DATETIME,
		   comment_time VARCHAR(200),
		   comment_date_print VARCHAR(200),
		   comment_user_id INT,
		   comment_user_name VARCHAR(250),
		   comment_user_alias VARCHAR(250),
		   comment_user_image_path VARCHAR(250),
		   comment_user_image_file VARCHAR(250),
		   comment_user_ip VARCHAR(250),
		   comment_user_hostname VARCHAR(250),
		   comment_user_agent VARCHAR(250),
		   comment_title VARCHAR(250),
		   comment_text TEXT, 
		   comment_rating INT, 
	  	   comment_helpful_clicks INT,
	  	   comment_useless_clicks INT,
	  	   comment_reported INT,
	  	   comment_reported_by_user_id INT,
	  	   comment_reported_reason TEXT,
		   comment_report_checked INT,
		   comment_report_checked_comment TEXT
	  	   )")
		   or die(mysqli_error());
	}
	echo"
	<!-- //workout_plans_weekly_comments -->
";
?>