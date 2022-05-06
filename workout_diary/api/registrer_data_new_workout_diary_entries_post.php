<?php
/*
* This wil take in 
* user id, workout session id, workout session main id, year, week, exercise id
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


if(isset($_POST['inp_session_main_id'])){
	$inp_session_main_id = $_POST['inp_session_main_id'];
	$inp_session_main_id = output_html($inp_session_main_id);
	$inp_session_main_id_mysql = quote_smart($link, $inp_session_main_id);
}
else{
	echo"Missing inp_session_main_id";
	die;
}


if(isset($_POST['inp_exercise_id'])){
	$inp_exercise_id = $_POST['inp_exercise_id'];
	$inp_exercise_id = output_html($inp_exercise_id);
	$inp_exercise_id_mysql = quote_smart($link, $inp_exercise_id);
}
else{
	echo"Missing inp_exercise_id";
	die;
}
if(isset($_POST['inp_exercise_title'])){
	$inp_exercise_title = $_POST['inp_exercise_title'];
	$inp_exercise_title = output_html($inp_exercise_title);
	$inp_exercise_title_mysql = quote_smart($link, $inp_exercise_title);
}
else{
	echo"Missing inp_exercise_title";
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

// Fetch workout diary entry ID
$query_entry = "SELECT workout_diary_entry_id FROM $t_workout_diary_entries WHERE workout_diary_entry_user_id=$inp_user_id_mysql AND workout_diary_entry_session_id=$inp_session_id_mysql AND workout_diary_entry_session_main_id=$inp_session_main_id_mysql AND workout_diary_entry_year=$year AND workout_diary_entry_week=$week AND workout_diary_entry_exercise_id=$inp_exercise_id_mysql";
$result_entry = mysqli_query($link, $query_entry);
$row_entry = mysqli_fetch_row($result_entry);
list($get_workout_diary_entry_id) = $row_entry;

if($get_workout_diary_entry_id == ""){
	// Create it
	mysqli_query($link, "INSERT INTO $t_workout_diary_entries
	(workout_diary_entry_id, workout_diary_entry_user_id, workout_diary_entry_session_id, workout_diary_entry_session_main_id, workout_diary_entry_date, workout_diary_entry_year, workout_diary_entry_month, workout_diary_entry_day, workout_diary_entry_week, workout_diary_entry_exercise_id, workout_diary_entry_exercise_title) 
	VALUES 
	(NULL, $inp_user_id_mysql, $inp_session_id_mysql, $inp_session_main_id_mysql, '$date', '$year', '$month', '$day', '$week', $inp_exercise_id_mysql, $inp_exercise_title_mysql)")
	or die(mysqli_error($link));


}

// Fetch it 
$query = "SELECT workout_diary_entry_id, workout_diary_entry_user_id, workout_diary_entry_session_id, workout_diary_entry_session_main_id, workout_diary_entry_date, workout_diary_entry_year, workout_diary_entry_month, workout_diary_entry_day, workout_diary_entry_week, workout_diary_entry_exercise_id, workout_diary_entry_exercise_title, workout_diary_entry_measurement, workout_diary_entry_set_a_weight, workout_diary_entry_set_a_reps, workout_diary_entry_set_b_weight, workout_diary_entry_set_b_reps, workout_diary_entry_set_c_weight, workout_diary_entry_set_c_reps, workout_diary_entry_set_d_weight, workout_diary_entry_set_d_reps, workout_diary_entry_set_e_weight, workout_diary_entry_set_e_reps, workout_diary_entry_set_avg_weight, workout_diary_entry_set_avg_reps, workout_diary_entry_velocity_a, workout_diary_entry_velocity_b, workout_diary_entry_distance, workout_diary_entry_distance_measurement, workout_diary_entry_duration_hh, workout_diary_entry_duration_mm, workout_diary_entry_duration_ss, workout_diary_entry_notes FROM $t_workout_diary_entries WHERE workout_diary_entry_user_id=$inp_user_id_mysql AND workout_diary_entry_session_id=$inp_session_id_mysql AND workout_diary_entry_session_main_id=$inp_session_main_id_mysql AND workout_diary_entry_year=$year AND workout_diary_entry_week=$week AND workout_diary_entry_exercise_id=$inp_exercise_id_mysql";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_array($result)) {
	$rows_array[] = $row;	
}

// Json everything
$rows_json = json_encode(utf8ize($rows_array));

echo"$rows_json";

?>