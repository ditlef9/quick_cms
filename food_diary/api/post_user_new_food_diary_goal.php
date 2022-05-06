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

if(isset($_POST['inp_current_weight'])){
	$inp_current_weight = $_POST['inp_current_weight'];
	$inp_current_weight = output_html($inp_current_weight);
	$inp_current_weight_mysql = quote_smart($link, $inp_current_weight);
}
else{
	echo"Missing current weight";
	die;
}
if(isset($_POST['inp_current_bmi'])){
	$inp_current_bmi = $_POST['inp_current_bmi'];
	$inp_current_bmi = output_html($inp_current_bmi);
	$inp_current_bmi_mysql = quote_smart($link, $inp_current_bmi);
}
else{
	echo"Missing current bmi";
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


// Insert goal
// Insert or update?

$inp_date = date("Y-m-d");
$query = "SELECT goal_id FROM $t_food_diary_goals WHERE goal_user_id='$get_user_id' AND goal_date='$inp_date'";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_goal_id) = $row;

if($get_goal_id == ""){
	// Insert my goal
	mysqli_query($link, "INSERT INTO $t_food_diary_goals
		(goal_id, goal_user_id, goal_current_weight, goal_date, goal_current_bmi) 
		VALUES 
		(NULL, '$get_user_id', $inp_current_weight_mysql, '$inp_date', $inp_current_bmi_mysql)")
		or die(mysqli_error($link));
}
else{
	// Update my goal
	$result = mysqli_query($link, "UPDATE $t_food_diary_goals SET goal_current_weight=$inp_current_weight_mysql, goal_current_bmi=$inp_current_bmi_mysql WHERE goal_id='$get_goal_id'") or die(mysqli_error());
}
// Get the goal ID
$query = "SELECT goal_id FROM $t_food_diary_goals WHERE goal_user_id='$get_user_id' AND goal_date='$inp_date'";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_goal_id) = $row;

echo"$get_goal_id";

?>