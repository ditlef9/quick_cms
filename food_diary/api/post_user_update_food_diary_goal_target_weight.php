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

$t_food_diary_goals   		= $mysqlPrefixSav . "food_diary_goals";
$t_food_diary_entires 		= $mysqlPrefixSav . "food_diary_entires";
$t_food_diary_totals_meals  	= $mysqlPrefixSav . "food_diary_totals_meals";
$t_food_diary_totals_days  	= $mysqlPrefixSav . "food_diary_totals_days";
$t_food_diary_last_used  	= $mysqlPrefixSav . "food_diary_last_used";

/*- Variables ------------------------------------------------------------------------- */
if(isset($_POST['inp_user_id'])) {
	$inp_user_id = $_POST['inp_user_id'];
	$inp_user_id = strip_tags(stripslashes($inp_user_id));
	$inp_user_id_mysql = quote_smart($link, $inp_user_id);
} else {
	echo"Missing user id";
	die;
}
if(isset($_POST['inp_user_password'])) {
	$inp_user_password = $_POST['inp_user_password'];
	$inp_user_password = strip_tags(stripslashes($inp_user_password));
} else {
	echo"Missing user password";
	die;
}

if(isset($_POST['inp_goal_id'])){
	$inp_goal_id = $_POST['inp_goal_id'];
	$inp_goal_id = output_html($inp_goal_id);
	$inp_goal_id_mysql = quote_smart($link, $inp_goal_id);
}
else{
	echo"Missing goal id";
	die;
}
if(isset($_POST['inp_goal_target_weight'])){
	$inp_goal_target_weight = $_POST['inp_goal_target_weight'];
	$inp_goal_target_weight = output_html($inp_goal_target_weight);
	$inp_goal_target_weight_mysql = quote_smart($link, $inp_goal_target_weight);
}
else{
	echo"Missing goal target weight";
	die;
}
if(isset($_POST['inp_goal_i_want_to'])){
	$inp_goal_i_want_to = $_POST['inp_goal_i_want_to'];
	$inp_goal_i_want_to = output_html($inp_goal_i_want_to);
	$inp_goal_i_want_to_mysql = quote_smart($link, $inp_goal_i_want_to);
}
else{
	echo"Missing goal i want to";
	die;
}
if(isset($_POST['inp_goal_weekly_goal'])){
	$inp_goal_weekly_goal = $_POST['inp_goal_weekly_goal'];
	$inp_goal_weekly_goal = output_html($inp_goal_weekly_goal);
	$inp_goal_weekly_goal_mysql = quote_smart($link, $inp_goal_weekly_goal);
}
else{
	echo"Missing goal weekly goal";
	die;
}
if(isset($_POST['inp_goal_target_bmi'])){
	$inp_goal_target_bmi = $_POST['inp_goal_target_bmi'];
	$inp_goal_target_bmi = output_html($inp_goal_target_bmi);
	$inp_goal_target_bmi_mysql = quote_smart($link, $inp_goal_target_bmi);
}
else{
	echo"Missing goal target bmi";
	die;
}



// Check that user exists
$query = "SELECT user_id, user_password FROM $t_users WHERE user_id=$inp_user_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_user_id, $get_user_password) = $row;
if($get_user_id == ""){
	echo"User not found";
	die;
}

// Check password
if($inp_user_password != "$get_user_password"){
	echo"Wrong password for user ID $inp_user_id";
	die;
}


// Check that goal exists
$query = "SELECT goal_id FROM $t_food_diary_goals WHERE goal_id=$inp_goal_id_mysql AND goal_user_id='$get_user_id'";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_goal_id) = $row;

if($get_goal_id == ""){
	echo"Goal not found";
	die;
}

// Update my goal
$result = mysqli_query($link, "UPDATE $t_food_diary_goals SET goal_target_weight=$inp_goal_target_weight_mysql, goal_i_want_to=$inp_goal_i_want_to_mysql, goal_weekly_goal=$inp_goal_weekly_goal_mysql, goal_target_bmi=$inp_goal_target_bmi_mysql WHERE goal_id=$get_goal_id AND goal_user_id='$get_user_id'") or die(mysqli_error());

echo"Updated target weight";

?>