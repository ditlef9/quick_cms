<?php
/*
* This wil take in 
* user id, workout session id, workout session main id, year, week, exercise id
* and all info about session
*
* and llok it up, if it got it it will return ID, if not it will create it and return new id
*
*               q = "SELECT workout_diary_entry_id FROM workout_diary_entries " +
*                        "WHERE workout_diary_entry_user_id='" + myUserId + "' " +
*                        "AND workout_diary_entry_session_id='" + currentWorkoutSessionId + "' " +
*                        "AND workout_diary_entry_session_main_id='" + workoutSessionMainId + "' " +
*                        "AND workout_diary_entry_year='" + dateyyyy + "' " +
*                        "AND workout_diary_entry_week='" + datew + "' " +
*                        "AND workout_diary_entry_exercise_id='" + workoutSessionMainExerciseId + "'";
*                Cursor cursorEntries = db.rawQuery(q);
*
*
* Returns: workout_diary_entry_id
*/

/*- Functions ------------------------------------------------------------------------- */
include("../../_admin/_functions/output_html.php");
include("../../_admin/_functions/clean.php");
include("../../_admin/_functions/quote_smart.php");


function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}


/*- MySQL ----------------------------------------------------------------------------- */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);

$mysql_config_file = "../../_admin/_data/mysql_" . $server_name . ".php";
include("$mysql_config_file");
$link = mysqli_connect($mysqlHostSav, $mysqlUserNameSav, $mysqlPasswordSav, $mysqlDatabaseNameSav);
if (!$link) {
	echo "Error MySQL link";
	die;
}


/*- MySQL Tables ---------------------------------------------------------------------- */

$t_users 	 		= $mysqlPrefixSav . "users";
$t_users_profile 		= $mysqlPrefixSav . "users_profile";
$t_users_profile_photo 		= $mysqlPrefixSav . "users_profile_photo";

	/*- Workout plans ------------------------------------------------------------- */
	$t_workout_plans_yearly  				= $mysqlPrefixSav . "workout_plans_yearly";
	$t_workout_plans_period  				= $mysqlPrefixSav . "workout_plans_period";
	$t_workout_plans_weekly  				= $mysqlPrefixSav . "workout_plans_weekly";
	$t_workout_plans_sessions 				= $mysqlPrefixSav . "workout_plans_sessions";
	$t_workout_plans_sessions_main 				= $mysqlPrefixSav . "workout_plans_sessions_main";
	$t_workout_plans_favorites 				= $mysqlPrefixSav . "workout_plans_favorites";

	/*- Workout diary ------------------------------------------------------------- */
	$t_workout_diary_entries 	= $mysqlPrefixSav . "workout_diary_entries";
	$t_workout_diary_plans 		= $mysqlPrefixSav . "workout_diary_plans";



/*- Variables ------------------------------------------------------------------------- */
$fm = "";

if(isset($_POST['inp_user_id'])){
	$inp_user_id = $_POST['inp_user_id'];
	$inp_user_id = output_html($inp_user_id);
	$inp_user_id_mysql = quote_smart($link, $inp_user_id);

	// Check if it alreaddy exists
	$query = "SELECT user_id FROM $t_users WHERE user_id=$inp_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id) = $row;

	if($get_user_id == ""){
		echo"Unknown user";
		die;
	}

}
else{
	echo"Missing user id";
	die;
}

if(isset($_POST['inp_session_id'])){
	$inp_session_id = $_POST['inp_session_id'];
	$inp_session_id = output_html($inp_session_id);
	$inp_session_id_mysql = quote_smart($link, $inp_session_id);
}
else{
	echo"Missing inp_session_id";
	die;
}


// Dates
$date = date("Y-m-d");
$year = date("Y");
$month = date("m");
$day = date("d");
$week = date("W");

// Build array
$rows_array = array();

// Find session
// Example: Mandag - Bryst og skuldre
$query = "SELECT workout_session_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity FROM $t_workout_plans_sessions WHERE workout_session_id=$inp_session_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_workout_session_id, $get_workout_session_weight, $get_workout_session_title, $get_workout_session_title_clean, $get_workout_session_duration, $get_workout_session_intensity) = $row;
if($get_workout_session_id == ""){
	echo"Cant find workout session id";
	die;
}

// Find session main
// Example 
$query_sessions = "SELECT workout_session_main_id, workout_session_main_user_id, workout_session_main_session_id, workout_session_main_weight, workout_session_main_exercise_id, workout_session_main_exercise_title, workout_session_main_sets, workout_session_main_reps, workout_session_main_velocity_a, workout_session_main_velocity_b, workout_session_main_distance, workout_session_main_duration, workout_session_main_intensity, workout_session_main_text FROM $t_workout_plans_sessions_main WHERE workout_session_main_session_id=$get_workout_session_id ORDER BY workout_session_main_weight ASC";
$result_sessions = mysqli_query($link, $query_sessions);
while($row_sessions = mysqli_fetch_row($result_sessions)) {
	list($get_workout_session_main_id, $get_workout_session_main_user_id, $get_workout_session_main_session_id, $get_workout_session_main_weight, $get_workout_session_main_exercise_id, $get_workout_session_main_exercise_title, $get_workout_session_main_sets, $get_workout_session_main_reps, $get_workout_session_main_velocity_a, $get_workout_session_main_velocity_b, $get_workout_session_main_distance, $get_workout_session_main_duration, $get_workout_session_main_intensity, $get_workout_session_main_text) = $row_sessions;


	// Fetch workout diary entry ID
	$query_entry = "SELECT workout_diary_entry_id, workout_diary_entry_user_id, workout_diary_entry_session_id, workout_diary_entry_session_main_id, workout_diary_entry_date, workout_diary_entry_year, workout_diary_entry_month, workout_diary_entry_day, workout_diary_entry_week, workout_diary_entry_exercise_id, workout_diary_entry_exercise_title, workout_diary_entry_measurement, workout_diary_entry_set_a_weight, workout_diary_entry_set_a_reps, workout_diary_entry_set_b_weight, workout_diary_entry_set_b_reps, workout_diary_entry_set_c_weight, workout_diary_entry_set_c_reps, workout_diary_entry_set_d_weight, workout_diary_entry_set_d_reps, workout_diary_entry_set_e_weight, workout_diary_entry_set_e_reps, workout_diary_entry_set_avg_weight, workout_diary_entry_set_avg_reps, workout_diary_entry_velocity_a, workout_diary_entry_velocity_b, workout_diary_entry_distance, workout_diary_entry_distance_measurement, workout_diary_entry_duration_hh, workout_diary_entry_duration_mm, workout_diary_entry_duration_ss, workout_diary_entry_notes FROM $t_workout_diary_entries WHERE workout_diary_entry_user_id=$inp_user_id_mysql AND workout_diary_entry_session_id=$get_workout_session_id AND workout_diary_entry_session_main_id=$get_workout_session_main_id AND workout_diary_entry_year=$year AND workout_diary_entry_week=$week AND workout_diary_entry_exercise_id=$get_workout_session_main_exercise_id";
	$result_entry = mysqli_query($link, $query_entry);
	$row_entry = mysqli_fetch_row($result_entry);
	list($get_workout_diary_entry_id, $get_workout_diary_entry_user_id, $get_workout_diary_entry_session_id, $get_workout_diary_entry_session_main_id, $get_workout_diary_entry_date, $get_workout_diary_entry_year, $get_workout_diary_entry_month, $get_workout_diary_entry_day, $get_workout_diary_entry_week, $get_workout_diary_entry_exercise_id, $get_workout_diary_entry_exercise_title, $get_workout_diary_entry_measurement, $get_workout_diary_entry_set_a_weight, $get_workout_diary_entry_set_a_reps, $get_workout_diary_entry_set_b_weight, $get_workout_diary_entry_set_b_reps, $get_workout_diary_entry_set_c_weight, $get_workout_diary_entry_set_c_reps, $get_workout_diary_entry_set_d_weight, $get_workout_diary_entry_set_d_reps, $get_workout_diary_entry_set_e_weight, $get_workout_diary_entry_set_e_reps, $get_workout_diary_entry_set_avg_weight, $get_workout_diary_entry_set_avg_reps, $get_workout_diary_entry_velocity_a, $get_workout_diary_entry_velocity_b, $get_workout_diary_entry_distance, $get_workout_diary_entry_distance_measurement, $get_workout_diary_entry_duration_hh, $get_workout_diary_entry_duration_mm, $get_workout_diary_entry_duration_ss, $get_workout_diary_entry_notes) = $row_entry;

	if($get_workout_diary_entry_id == ""){
		// Create it
		$inp_exercise_title_mysql = quote_smart($link, $get_workout_session_main_exercise_title);
		mysqli_query($link, "INSERT INTO $t_workout_diary_entries
		(workout_diary_entry_id, workout_diary_entry_user_id, workout_diary_entry_session_id, workout_diary_entry_session_main_id, workout_diary_entry_date, workout_diary_entry_year, workout_diary_entry_month, workout_diary_entry_day, workout_diary_entry_week, workout_diary_entry_exercise_id, workout_diary_entry_exercise_title) 
		VALUES 
		(NULL, $inp_user_id_mysql, $get_workout_session_id, $get_workout_session_main_id, '$date', '$year', '$month', '$day', '$week', $get_workout_session_main_exercise_id, $inp_exercise_title_mysql)")
		or die(mysqli_error($link));

		// Fetch the new ID
		$query_entry = "SELECT workout_diary_entry_id, workout_diary_entry_user_id, workout_diary_entry_session_id, workout_diary_entry_session_main_id, workout_diary_entry_date, workout_diary_entry_year, workout_diary_entry_month, workout_diary_entry_day, workout_diary_entry_week, workout_diary_entry_exercise_id, workout_diary_entry_exercise_title, workout_diary_entry_measurement, workout_diary_entry_set_a_weight, workout_diary_entry_set_a_reps, workout_diary_entry_set_b_weight, workout_diary_entry_set_b_reps, workout_diary_entry_set_c_weight, workout_diary_entry_set_c_reps, workout_diary_entry_set_d_weight, workout_diary_entry_set_d_reps, workout_diary_entry_set_e_weight, workout_diary_entry_set_e_reps, workout_diary_entry_set_avg_weight, workout_diary_entry_set_avg_reps, workout_diary_entry_velocity_a, workout_diary_entry_velocity_b, workout_diary_entry_distance, workout_diary_entry_distance_measurement, workout_diary_entry_duration_hh, workout_diary_entry_duration_mm, workout_diary_entry_duration_ss, workout_diary_entry_notes FROM $t_workout_diary_entries WHERE workout_diary_entry_user_id=$inp_user_id_mysql AND workout_diary_entry_session_id=$get_workout_session_id AND workout_diary_entry_session_main_id=$get_workout_session_main_id AND workout_diary_entry_year=$year AND workout_diary_entry_week=$week AND workout_diary_entry_exercise_id=$get_workout_session_main_exercise_id";
		$result_entry = mysqli_query($link, $query_entry);
		$row_entry = mysqli_fetch_row($result_entry);
		list($get_workout_diary_entry_id, $get_workout_diary_entry_user_id, $get_workout_diary_entry_session_id, $get_workout_diary_entry_session_main_id, $get_workout_diary_entry_date, $get_workout_diary_entry_year, $get_workout_diary_entry_month, $get_workout_diary_entry_day, $get_workout_diary_entry_week, $get_workout_diary_entry_exercise_id, $get_workout_diary_entry_exercise_title, $get_workout_diary_entry_measurement, $get_workout_diary_entry_set_a_weight, $get_workout_diary_entry_set_a_reps, $get_workout_diary_entry_set_b_weight, $get_workout_diary_entry_set_b_reps, $get_workout_diary_entry_set_c_weight, $get_workout_diary_entry_set_c_reps, $get_workout_diary_entry_set_d_weight, $get_workout_diary_entry_set_d_reps, $get_workout_diary_entry_set_e_weight, $get_workout_diary_entry_set_e_reps, $get_workout_diary_entry_set_avg_weight, $get_workout_diary_entry_set_avg_reps, $get_workout_diary_entry_velocity_a, $get_workout_diary_entry_velocity_b, $get_workout_diary_entry_distance, $get_workout_diary_entry_distance_measurement, $get_workout_diary_entry_duration_hh, $get_workout_diary_entry_duration_mm, $get_workout_diary_entry_duration_ss, $get_workout_diary_entry_notes) = $row_entry;

	}


	// Get fields from request, and update
	if($get_workout_session_main_sets != "0" && $get_workout_session_main_reps != "0"){
		// Ready variables
		$inp_set_a_weight = "";
		$inp_measurement = "";
		$inp_set_a_reps = "";
		$inp_set_b_reps = "";
		$inp_set_c_reps = "";
		$inp_set_d_reps = "";
						
		if(isset($_POST["inp_set_a_weight_$get_workout_session_main_id"])){
			$inp_set_a_weight = $_POST["inp_set_a_weight_$get_workout_session_main_id"];
			$inp_set_a_weight = str_replace(",", ".", $inp_set_a_weight);
			$inp_set_a_weight = output_html($inp_set_a_weight);
		}
		if(isset($_POST["inp_measurement_$get_workout_session_main_id"])){
			$inp_measurement = $_POST["inp_measurement_$get_workout_session_main_id"];
			$inp_measurement = str_replace(",", ".", $inp_measurement);
			$inp_measurement = output_html($inp_measurement);
		}

		if(isset($_POST["inp_set_a_reps_$get_workout_session_main_id"])){
			$inp_set_a_reps = $_POST["inp_set_a_reps_$get_workout_session_main_id"];
			$inp_set_a_reps = str_replace(",", ".", $inp_set_a_reps);
			$inp_set_a_reps = output_html($inp_set_a_reps);
		}
		if(isset($_POST["inp_set_b_reps_$get_workout_session_main_id"])){
			$inp_set_b_reps = $_POST["inp_set_b_reps_$get_workout_session_main_id"];
			$inp_set_b_reps = str_replace(",", ".", $inp_set_b_reps);
			$inp_set_b_reps = output_html($inp_set_b_reps);
			$inp_set_b_reps_mysql = quote_smart($link, $inp_set_b_reps);
		}
		if(isset($_POST["inp_set_c_reps_$get_workout_session_main_id"])){
			$inp_set_c_reps = $_POST["inp_set_c_reps_$get_workout_session_main_id"];
			$inp_set_c_reps = str_replace(",", ".", $inp_set_c_reps);
			$inp_set_c_reps = output_html($inp_set_c_reps);
			$inp_set_c_reps_mysql = quote_smart($link, $inp_set_c_reps);
		}
		if(isset($_POST["inp_set_d_reps_$get_workout_session_main_id"])){
			$inp_set_d_reps = $_POST["inp_set_d_reps_$get_workout_session_main_id"];
			$inp_set_d_reps = str_replace(",", ".", $inp_set_d_reps);
			$inp_set_d_reps = output_html($inp_set_d_reps);
			$inp_set_d_reps_mysql = quote_smart($link, $inp_set_d_reps);
		}

		// Do something?
		if($inp_set_a_weight != "" OR $inp_measurement != "" OR $inp_set_a_reps != "" OR 
			   $inp_set_b_reps != "" OR $inp_set_c_reps != "" OR $inp_set_d_reps != ""){

			if($inp_set_a_weight != "" && $inp_set_a_weight != "$get_workout_diary_entry_set_a_weight"){
				$inp_set_a_weight_mysql = quote_smart($link, $inp_set_a_weight);
				$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_set_a_weight=$inp_set_a_weight_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");
			}
			if($inp_measurement != "" && $inp_measurement != "$get_workout_diary_entry_measurement"){
				$inp_measurement_mysql = quote_smart($link, $inp_measurement);
				$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_measurement=$inp_measurement_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");
			}
			if($inp_set_a_reps != "" && $inp_set_a_reps != "$get_workout_diary_entry_set_a_reps"){
				$inp_set_a_reps_mysql = quote_smart($link, $inp_set_a_reps);
				$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_set_a_reps=$inp_set_a_reps_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");
			}
			if($inp_set_b_reps != "" && $inp_set_b_reps != "$get_workout_diary_entry_set_b_reps"){
				$inp_set_b_reps_mysql = quote_smart($link, $inp_set_b_reps);
				$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_set_b_reps=$inp_set_b_reps_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");
			}
			if($inp_set_c_reps != "" && $inp_set_c_reps != "$get_workout_diary_entry_set_c_reps"){
				$inp_set_c_reps_mysql = quote_smart($link, $inp_set_c_reps);
				$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_set_c_reps=$inp_set_c_reps_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");
			}
			if($inp_set_d_reps != "" && $inp_set_d_reps != "$get_workout_diary_entry_set_d_reps"){
				$inp_set_d_reps_mysql = quote_smart($link, $inp_set_d_reps);
				$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_set_d_reps=$inp_set_d_reps_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");
			}
		}
							

	} // sets reps



						// Cardio?
						if(($get_workout_session_main_distance != "" && $get_workout_session_main_distance != "0") OR $get_workout_session_main_duration != "" && $get_workout_session_main_duration != "0"){

							// Ready variables
							$inp_distance = "";
							$inp_distance_measurement = "";
							$inp_velocity_a = "";
							$inp_measurement = "";
							$inp_duration_hh = "";
							$inp_duration_mm = "";
							$inp_duration_ss = "";
							$inp_notes = "";
							if(isset($_POST["inp_distance_$get_workout_session_main_id"])){
								$inp_distance = $_POST["inp_distance_$get_workout_session_main_id"];
								$inp_distance = str_replace(",", ".", $inp_distance);
								$inp_distance = output_html($inp_distance);
							} 

							if(isset($_POST["inp_distance_measurement_$get_workout_session_main_id"])){
								$inp_distance_measurement = $_POST["inp_distance_measurement_$get_workout_session_main_id"];
								$inp_distance_measurement = str_replace(",", ".", $inp_distance_measurement);
								$inp_distance_measurement = output_html($inp_distance_measurement);
							}

							if(isset($_POST["inp_velocity_a_$get_workout_session_main_id"])){
								$inp_velocity_a = $_POST["inp_velocity_a_$get_workout_session_main_id"];
								$inp_velocity_a = str_replace(",", ".", $inp_velocity_a);
								$inp_velocity_a = output_html($inp_velocity_a);
							}
							if(isset($_POST["inp_measurement_$get_workout_session_main_id"])){
								$inp_measurement = $_POST["inp_measurement_$get_workout_session_main_id"];
								$inp_measurement = str_replace(",", ".", $inp_measurement);
								$inp_measurement = output_html($inp_measurement);

							}

							if(isset($_POST["inp_duration_hh_$get_workout_session_main_id"])){
								$inp_duration_hh = $_POST["inp_duration_hh_$get_workout_session_main_id"];
								$inp_duration_hh = str_replace(",", ".", $inp_duration_hh);
								$inp_duration_hh = output_html($inp_duration_hh);
							}
							if(isset($_POST["inp_duration_mm_$get_workout_session_main_id"])){
								$inp_duration_mm = $_POST["inp_duration_mm_$get_workout_session_main_id"];
								$inp_duration_mm = str_replace(",", ".", $inp_duration_mm);
								$inp_duration_mm = output_html($inp_duration_mm);
							}
							if(isset($_POST["inp_duration_ss_$get_workout_session_main_id"])){
								$inp_duration_ss = $_POST["inp_duration_ss_$get_workout_session_main_id"];
								$inp_duration_ss = str_replace(",", ".", $inp_duration_ss);
								$inp_duration_ss = output_html($inp_duration_ss);
							}
							if(isset($_POST["inp_notes_$get_workout_session_main_id"])){
								$inp_notes = $_POST["inp_notes_$get_workout_session_main_id"];
								$inp_notes = str_replace(",", ".", $inp_notes);
								$inp_notes = output_html($inp_notes);
							}


							if($inp_distance != "" OR $inp_distance_measurement != "" OR $inp_velocity_a != "" OR
							   $inp_measurement != "" OR $inp_duration_hh != "" OR $inp_duration_mm != "" OR $inp_duration_ss != "" OR $inp_notes != ""){

		

								if($inp_distance != ""){
									$inp_distance_mysql = quote_smart($link, $inp_distance);
									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_distance=$inp_distance_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");
							
									$saved_animation = "inp_distance_" . $get_workout_session_main_id;
									$focus = "inp_distance_measurement_" . $get_workout_session_main_id;
								}
								if($inp_distance_measurement != ""){
									$inp_distance_measurement_mysql = quote_smart($link, $inp_distance_measurement);
									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_distance_measurement=$inp_distance_measurement_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");

									$saved_animation = "inp_distance_measurement_" . $get_workout_session_main_id;
									$focus = "inp_velocity_a_" . $get_workout_session_main_id;
								}
								if($inp_velocity_a != ""){
									$inp_velocity_a_mysql = quote_smart($link, $inp_velocity_a);
									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_velocity_a=$inp_velocity_a_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");

									$saved_animation = "inp_velocity_a_" . $get_workout_session_main_id;
									$focus = "inp_duration_mm_" . $get_workout_session_main_id;
								}
								if($inp_measurement != ""){
									$inp_measurement_mysql = quote_smart($link, $inp_measurement);
									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_measurement=$inp_measurement_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");

									$saved_animation = "inp_measurement_" . $get_workout_session_main_id;
								}
								if($inp_duration_hh != ""){
									$inp_duration_hh_mysql = quote_smart($link, $inp_duration_hh);
									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_duration_hh=$inp_duration_hh_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");

									$saved_animation = "inp_duration_hh_" . $get_workout_session_main_id;
								}
								if($inp_duration_mm != ""){
									$inp_duration_mm_mysql = quote_smart($link, $inp_duration_mm);
									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_duration_mm=$inp_duration_mm_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");

									$saved_animation = "inp_duration_mm_" . $get_workout_session_main_id;
									$focus = "inp_duration_ss_" . $get_workout_session_main_id;
								}
								if($inp_duration_ss != ""){
									$inp_duration_ss_mysql = quote_smart($link, $inp_duration_ss);
									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_duration_ss=$inp_duration_ss_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");

									$saved_animation = "inp_duration_ss_" . $get_workout_session_main_id;
									$focus = "inp_notes_" . $get_workout_session_main_id;
								}
								if($inp_notes != ""){
									$inp_notes_mysql = quote_smart($link, $inp_notes);
									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_notes=$inp_notes_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");

									$saved_animation = "inp_notes_" . $get_workout_session_main_id;
									$focus = "inp_distance_"; // the rest will be filled in in next iteration
									$focus_on_next = "true";
								}


								// Check if we have 2 of 3, if we have then we can calculate the last
								$query_entry = "SELECT workout_diary_entry_measurement, workout_diary_entry_velocity_a, workout_diary_entry_distance, workout_diary_entry_distance_measurement, workout_diary_entry_duration_hh, workout_diary_entry_duration_mm, workout_diary_entry_duration_ss FROM $t_workout_diary_entries WHERE workout_diary_entry_id='$get_workout_diary_entry_id'";
								$result_entry = mysqli_query($link, $query_entry);
								$row_entry = mysqli_fetch_row($result_entry);
								list($get_workout_diary_entry_measurement, $get_workout_diary_entry_velocity_a, $get_workout_diary_entry_distance, $get_workout_diary_entry_distance_measurement, $get_workout_diary_entry_duration_hh, $get_workout_diary_entry_duration_mm, $get_workout_diary_entry_duration_ss) = $row_entry;
							
								// Time
								$get_hh_seconds = $get_workout_diary_entry_duration_hh*360;
								$get_mm_seconds = $get_workout_diary_entry_duration_mm*60;
								$get_time_si = $get_hh_seconds+$get_mm_seconds+$get_workout_diary_entry_duration_ss;

								// Velocity
								$get_velocity_si = "$get_workout_diary_entry_measurement";
								if($get_workout_diary_entry_measurement == "km/h"){
									$get_velocity_si = $get_workout_diary_entry_velocity_a/3.6;
								}
								elseif($get_workout_diary_entry_measurement == "mi/h"){
									$get_velocity_si = $get_workout_diary_entry_velocity_a/0.44704;
								}
								elseif($get_workout_diary_entry_measurement == "mi/s"){
									$get_velocity_si = $get_workout_diary_entry_velocity_a/1609.344;
								}

								// Lenght
								$get_distance_si = "$get_workout_diary_entry_distance";
								if($get_workout_diary_entry_distance_measurement == "mi"){
									$get_distance_si = $get_workout_diary_entry_distance/1609.344;
								}
	
								// v = s t 

								if($get_time_si == "0" && $get_workout_diary_entry_distance != "" && $get_workout_diary_entry_velocity_a != ""){
									// Calcualte time
									// t= s/v
									$t_seconds = $get_distance_si/$get_velocity_si;

								
									$hours = floor($t_seconds / 3600);
									$minutes = floor(($t_seconds / 60) % 60);
									$seconds = $t_seconds % 60;

									$inp_workout_diary_entry_duration_hh = output_html($hours);
									$inp_workout_diary_entry_duration_hh_mysql = quote_smart($link, $inp_workout_diary_entry_duration_hh);

									$inp_workout_diary_entry_duration_mm = output_html($minutes);
									$inp_workout_diary_entry_duration_mm_mysql = quote_smart($link, $inp_workout_diary_entry_duration_mm);
								

									$inp_workout_diary_entry_duration_ss = output_html($seconds);
									$inp_workout_diary_entry_duration_ss_mysql = quote_smart($link, $inp_workout_diary_entry_duration_ss);
								
	
									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_duration_hh=$inp_workout_diary_entry_duration_hh_mysql, workout_diary_entry_duration_mm=$inp_workout_diary_entry_duration_mm_mysql, workout_diary_entry_duration_ss=$inp_workout_diary_entry_duration_ss_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");

								}

								if($get_workout_diary_entry_distance == "" && $get_time_si != "0" && $get_workout_diary_entry_velocity_a != ""){
									// Calcualte distance
									// s=vt
									$distance = $get_velocity_si * $get_time_si;
								

								
									if($get_workout_diary_entry_distance_measurement == "mi"){
										$distance = round($distance/1609.344, 0);
									}
									else{
										$distance = round($distance, 0);
									}

								
	
									$inp_distance = output_html($distance);
									$inp_distance_mysql = quote_smart($link, $inp_distance);
								

									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_distance=$inp_distance_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");

								}
								if($get_workout_diary_entry_distance != "" && $get_time_si != "0" && $get_workout_diary_entry_velocity_a == ""){
									// Calcualte velocity
									// v=s/t
									$velocity = $get_distance_si / $get_time_si;
								

									if($get_workout_diary_entry_measurement == "km/h"){
										$velocity = round($velocity*3.6, 1);
									}
									elseif($get_workout_diary_entry_measurement == "mi/h"){
										$velocity = round($velocity*0.44704, 1);
									}
									elseif($get_workout_diary_entry_measurement == "mi/s"){
										$velocity = round($velocity*1609.344, 1);
									}
									else{
										$velocity = round($velocity, 1);
									}



									$inp_velocity = output_html($velocity);
									$inp_velocity_mysql = quote_smart($link, $inp_velocity);
								
	
									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_velocity_a=$inp_velocity_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");

								}

							} // cardio changes

	} // cardio
} // while





// Give back result to Android
$query = "SELECT workout_diary_entry_id, workout_diary_entry_user_id, workout_diary_entry_session_id, workout_diary_entry_session_main_id, workout_diary_entry_date, workout_diary_entry_year, workout_diary_entry_month, workout_diary_entry_day, workout_diary_entry_week, workout_diary_entry_exercise_id, workout_diary_entry_exercise_title, workout_diary_entry_measurement, workout_diary_entry_set_a_weight, workout_diary_entry_set_a_reps, workout_diary_entry_set_b_weight, workout_diary_entry_set_b_reps, workout_diary_entry_set_c_weight, workout_diary_entry_set_c_reps, workout_diary_entry_set_d_weight, workout_diary_entry_set_d_reps, workout_diary_entry_set_e_weight, workout_diary_entry_set_e_reps, workout_diary_entry_set_avg_weight, workout_diary_entry_set_avg_reps, workout_diary_entry_velocity_a, workout_diary_entry_velocity_b, workout_diary_entry_distance, workout_diary_entry_distance_measurement, workout_diary_entry_duration_hh, workout_diary_entry_duration_mm, workout_diary_entry_duration_ss, workout_diary_entry_notes FROM $t_workout_diary_entries WHERE workout_diary_entry_user_id=$inp_user_id_mysql AND workout_diary_entry_session_id=$inp_session_id_mysql AND workout_diary_entry_year=$year AND workout_diary_entry_week=$week";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_array($result)) {
	$rows_array[] = $row;	
}

// Json everything
$rows_json = json_encode(utf8ize($rows_array));

echo"$rows_json";

?>