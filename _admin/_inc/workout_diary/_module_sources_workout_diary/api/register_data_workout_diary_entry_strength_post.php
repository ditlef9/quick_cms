<?php
/**
*
* File: register_data_workout_diary_entry_strength_post.php
* Version 1.0.0.
* Date 16:18 05.03.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*
* This wil take in 
* user_id, session_id,
* session_main_id, date, year,
* month, week, exercise_id,
* exercise_title, measurement
*
* from SINGLE entry
*
* and look it up, if it got it it will return ID, if not it will create it and return new id
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
if(isset($_POST['inp_date'])){
	$inp_date = $_POST['inp_date'];
	$inp_date = output_html($inp_date);
	$inp_date_mysql = quote_smart($link, $inp_date);
}
else{
	echo"Missing inp_date";
	die;
}
if(isset($_POST['inp_year'])){
	$inp_year = $_POST['inp_year'];
	$inp_year = output_html($inp_year);
	$inp_year_mysql = quote_smart($link, $inp_year);
}
else{
	echo"Missing inp_year";
	die;
}
if(isset($_POST['inp_month'])){
	$inp_month = $_POST['inp_month'];
	$inp_month = output_html($inp_month);
	$inp_month_mysql = quote_smart($link, $inp_month);
}
else{
	echo"Missing inp_month";
	die;
}
if(isset($_POST['inp_day'])){
	$inp_day = $_POST['inp_day'];
	$inp_day = output_html($inp_day);
	$inp_day_mysql = quote_smart($link, $inp_day);
}
else{
	echo"Missing inp_day";
	die;
}
if(isset($_POST['inp_week'])){
	$inp_week = $_POST['inp_week'];
	$inp_week = output_html($inp_week);
	$inp_week_mysql = quote_smart($link, $inp_week);
}
else{
	echo"Missing inp_week";
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
if(isset($_POST['inp_measurement'])){
	$inp_measurement = $_POST['inp_measurement'];
	$inp_measurement = output_html($inp_measurement);
	$inp_emeasurement_mysql = quote_smart($link, $inp_measurement);
}
else{
	echo"Missing inp_measurement";
	die;
}


if(isset($_POST['inp_field_name'])){
	$inp_field_name = $_POST['inp_field_name'];
	$inp_field_name = output_html($inp_field_name);
}
else{
	echo"Missing inp_field_name";
	die;
}
if(isset($_POST['inp_field_value'])){
	$inp_field_value = $_POST['inp_field_value'];
	$inp_field_value = output_html($inp_field_value);
	$inp_field_value_mysql = quote_smart($link, $inp_field_value);
}
else{
	echo"Missing inp_field_value";
	die;
}


// Build array
$rows_array = array();

// Fetch workout diary entry ID
$query_entry = "SELECT workout_diary_entry_id FROM $t_workout_diary_entries WHERE workout_diary_entry_user_id=$inp_user_id_mysql AND workout_diary_entry_session_id=$inp_session_id_mysql AND workout_diary_entry_session_main_id=$inp_session_main_id_mysql AND workout_diary_entry_year=$inp_year_mysql AND workout_diary_entry_week=$inp_week_mysql AND workout_diary_entry_exercise_id=$inp_exercise_id_mysql";
$result_entry = mysqli_query($link, $query_entry);
$row_entry = mysqli_fetch_row($result_entry);
list($get_workout_diary_entry_id) = $row_entry;

if($get_workout_diary_entry_id == ""){
	// Create it
	mysqli_query($link, "INSERT INTO $t_workout_diary_entries
	(workout_diary_entry_id, workout_diary_entry_user_id, workout_diary_entry_session_id, workout_diary_entry_session_main_id, workout_diary_entry_date, workout_diary_entry_year, workout_diary_entry_month, workout_diary_entry_day, workout_diary_entry_week, workout_diary_entry_exercise_id, workout_diary_entry_exercise_title, workout_diary_entry_measurement) 
	VALUES 
	(NULL, $inp_user_id_mysql, $inp_session_id_mysql, $inp_session_main_id_mysql, $inp_date_mysql, $inp_year_mysql, $inp_month_mysql, $inp_day_mysql, $inp_week_mysql, $inp_exercise_id_mysql, $inp_exercise_title_mysql, $inp_measurement)")
	or die(mysqli_error($link));


	// Get ID
	$query_entry = "SELECT workout_diary_entry_id FROM $t_workout_diary_entries WHERE workout_diary_entry_user_id=$inp_user_id_mysql AND workout_diary_entry_session_id=$inp_session_id_mysql AND workout_diary_entry_session_main_id=$inp_session_main_id_mysql AND workout_diary_entry_year=$inp_year_mysql AND workout_diary_entry_week=$inp_week_mysql AND workout_diary_entry_exercise_id=$inp_exercise_id_mysql";
	$result_entry = mysqli_query($link, $query_entry);
	$row_entry = mysqli_fetch_row($result_entry);
	list($get_workout_diary_entry_id) = $row_entry;
}


// Field
if($inp_field_name == "workout_diary_entry_measurement" OR $inp_field_name == "workout_diary_entry_set_a_weight" 
OR $inp_field_name == "workout_diary_entry_set_a_reps" OR $inp_field_name == "workout_diary_entry_set_b_weight" 
OR $inp_field_name == "workout_diary_entry_set_b_reps" OR $inp_field_name == "workout_diary_entry_set_c_weight" 
OR $inp_field_name == "workout_diary_entry_set_c_reps" OR $inp_field_name == "workout_diary_entry_set_d_weight" 
OR $inp_field_name == "workout_diary_entry_set_d_reps" OR $inp_field_name == "workout_diary_entry_set_e_weight" 
OR $inp_field_name == "workout_diary_entry_set_e_reps" OR $inp_field_name == "workout_diary_entry_set_avg_weight" 
OR $inp_field_name == "workout_diary_entry_set_avg_reps" OR $inp_field_name == "workout_diary_entry_velocity_a" 
OR $inp_field_name == "workout_diary_entry_velocity_b" OR $inp_field_name == "workout_diary_entry_distance" 
OR $inp_field_name == "workout_diary_entry_distance_measurement" OR $inp_field_name == "workout_diary_entry_duration_hh" 
OR $inp_field_name == "workout_diary_entry_duration_mm" OR $inp_field_name == "workout_diary_entry_duration_ss" 
OR $inp_field_name == "workout_diary_entry_notes"){
	$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET $inp_field_name=$inp_field_value_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'") or die(mysqli_error($link));

}
else{
	echo"Unknown field name";
	die;
}


// Fetch it 
$query = "SELECT * FROM $t_workout_diary_entries WHERE workout_diary_entry_id=$get_workout_diary_entry_id";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_array($result);
$rows_array[] = $row;


// Json everything
$rows_json = json_encode(utf8ize($rows_array));

echo"$rows_json";

?>