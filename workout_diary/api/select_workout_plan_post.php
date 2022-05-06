<?php
/*- Functions ------------------------------------------------------------------------- */
include("../../_admin/_functions/output_html.php");
include("../../_admin/_functions/clean.php");
include("../../_admin/_functions/quote_smart.php");


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
	echo"Missing email";
	die;
}

if(isset($_POST['inp_weight'])){
	$inp_weight = $_POST['inp_weight'];
	$inp_weight = output_html($inp_weight);
	$inp_weight_mysql = quote_smart($link, $inp_weight);
}
else{
	echo"Missing weight";
	die;
}


if(isset($_POST['inp_period_id'])){
	$inp_period_id = $_POST['inp_period_id'];
	$inp_period_id = output_html($inp_period_id);
	$inp_period_id_mysql = quote_smart($link, $inp_period_id);
}
else{
	echo"Missing period_id";
	die;
}


if(isset($_POST['inp_session_id'])){
	$inp_session_id = $_POST['inp_session_id'];
	$inp_session_id = output_html($inp_session_id);
	$inp_session_id_mysql = quote_smart($link, $inp_session_id);
}
else{
	echo"Missing session_id";
	die;
}

if(isset($_POST['inp_weekly_id'])){
	$inp_weekly_id = $_POST['inp_weekly_id'];
	$inp_weekly_id = output_html($inp_weekly_id);
	$inp_weekly_id_mysql = quote_smart($link, $inp_weekly_id);
}
else{
	echo"Missing weekly_id";
	die;
}

if(isset($_POST['inp_yearly_id'])){
	$inp_yearly_id = $_POST['inp_yearly_id'];
	$inp_yearly_id = output_html($inp_yearly_id);
	$inp_yearly_id_mysql = quote_smart($link, $inp_yearly_id);
}
else{
	echo"Missing yearly_id";
	die;
}

if(isset($_POST['inp_title'])){
	$inp_title = $_POST['inp_title'];
	$inp_title = output_html($inp_title);
	$inp_title_mysql = quote_smart($link, $inp_title);
}
else{
	echo"Missing title";
	die;
}

if(isset($_POST['inp_date'])){
	$inp_date = $_POST['inp_date'];
	$inp_date = output_html($inp_date);
	$inp_date_mysql = quote_smart($link, $inp_date);
}
else{
	echo"Missing date";
	die;
}
if(isset($_POST['inp_notes'])){
	$inp_notes = $_POST['inp_notes'];
	$inp_notes = output_html($inp_notes);
	$inp_notes_mysql = quote_smart($link, $inp_notes);
}
else{
	echo"Missing notes";
	die;
}

// Check if I already have this,
$query = "SELECT workout_diary_plan_id FROM $t_workout_diary_plans WHERE workout_diary_plan_user_id=$inp_user_id_mysql AND workout_diary_plan_weekly_id=$inp_weekly_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_workout_diary_plan_id) = $row;
if($get_workout_diary_plan_id == ""){

	// Insert
	mysqli_query($link, "INSERT INTO $t_workout_diary_plans
	(workout_diary_plan_id, workout_diary_plan_user_id, workout_diary_plan_weight, workout_diary_plan_period_id, workout_diary_plan_session_id, workout_diary_plan_weekly_id, 
	workout_diary_plan_yearly_id, workout_diary_plan_title, workout_diary_plan_date, workout_diary_plan_notes) 
	VALUES 
	(NULL, $inp_user_id_mysql, $inp_weight_mysql, $inp_period_id_mysql, $inp_session_id_mysql, $inp_weekly_id_mysql, 
	$inp_yearly_id_mysql, $inp_title_mysql, $inp_date_mysql, $inp_notes_mysql)")
	or die(mysqli_error($link));

	// Get plan ID
	$query = "SELECT workout_diary_plan_id FROM $t_workout_diary_plans WHERE workout_diary_plan_user_id=$inp_user_id_mysql AND workout_diary_plan_weekly_id=$inp_weekly_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_workout_diary_plan_id) = $row;
}


echo"$get_workout_diary_plan_id";

?>