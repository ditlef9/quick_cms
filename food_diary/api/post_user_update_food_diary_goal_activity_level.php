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
if(isset($_POST['inp_goal_activity_level'])){
	$inp_goal_activity_level = $_POST['inp_goal_activity_level'];
	$inp_goal_activity_level = output_html($inp_goal_activity_level);
	$inp_goal_activity_level_mysql = quote_smart($link, $inp_goal_activity_level);
}
else{
	echo"Missing inp_goal_activity_level";
	die;
}
if(isset($_POST['inp_goal_current_bmr_calories'])){
	$inp_goal_current_bmr_calories = $_POST['inp_goal_current_bmr_calories'];
	$inp_goal_current_bmr_calories = output_html($inp_goal_current_bmr_calories);
	$inp_goal_current_bmr_calories_mysql = quote_smart($link, $inp_goal_current_bmr_calories);
}
else{
	echo"Missing inp_goal_current_bmr_calories";
	die;
}
if(isset($_POST['inp_goal_current_bmr_fat'])){
	$inp_goal_current_bmr_fat = $_POST['inp_goal_current_bmr_fat'];
	$inp_goal_current_bmr_fat = output_html($inp_goal_current_bmr_fat);
	$inp_goal_current_bmr_fat_mysql = quote_smart($link, $inp_goal_current_bmr_fat);
}
else{
	echo"Missing inp_goal_current_bmr_fat";
	die;
}
if(isset($_POST['inp_goal_current_bmr_carbs'])){
	$inp_goal_current_bmr_carbs = $_POST['inp_goal_current_bmr_carbs'];
	$inp_goal_current_bmr_carbs = output_html($inp_goal_current_bmr_carbs);
	$inp_goal_current_bmr_carbs_mysql = quote_smart($link, $inp_goal_current_bmr_carbs);
}
else{
	echo"Missing inp_goal_current_bmr_carbs";
	die;
}

if(isset($_POST['inp_goal_current_bmr_proteins'])){
	$inp_goal_current_bmr_proteins = $_POST['inp_goal_current_bmr_proteins'];
	$inp_goal_current_bmr_proteins = output_html($inp_goal_current_bmr_proteins);
	$inp_goal_current_bmr_proteins_mysql = quote_smart($link, $inp_goal_current_bmr_proteins);
}
else{
	echo"Missing inp_goal_current_bmr_proteins";
	die;
}

if(isset($_POST['inp_goal_current_sedentary_calories'])){
	$inp_goal_current_sedentary_calories = $_POST['inp_goal_current_sedentary_calories'];
	$inp_goal_current_sedentary_calories = output_html($inp_goal_current_sedentary_calories);
	$inp_goal_current_sedentary_calories_mysql = quote_smart($link, $inp_goal_current_sedentary_calories);
}
else{
	echo"Missing inp_goal_current_sedentary_calories";
	die;
}

if(isset($_POST['inp_goal_current_sedentary_fat'])){
	$inp_goal_current_sedentary_fat = $_POST['inp_goal_current_sedentary_fat'];
	$inp_goal_current_sedentary_fat = output_html($inp_goal_current_sedentary_fat);
	$inp_goal_current_sedentary_fat_mysql = quote_smart($link, $inp_goal_current_sedentary_fat);
}
else{
	echo"Missing inp_goal_current_sedentary_fat";
	die;
}

if(isset($_POST['inp_goal_current_sedentary_carbs'])){
	$inp_goal_current_sedentary_carbs = $_POST['inp_goal_current_sedentary_carbs'];
	$inp_goal_current_sedentary_carbs = output_html($inp_goal_current_sedentary_carbs);
	$inp_goal_current_sedentary_carbs_mysql = quote_smart($link, $inp_goal_current_sedentary_carbs);
}
else{
	echo"Missing inp_goal_current_sedentary_carbs";
	die;
}

if(isset($_POST['inp_goal_current_sedentary_proteins'])){
	$inp_goal_current_sedentary_proteins = $_POST['inp_goal_current_sedentary_proteins'];
	$inp_goal_current_sedentary_proteins = output_html($inp_goal_current_sedentary_proteins);
	$inp_goal_current_sedentary_proteins_mysql = quote_smart($link, $inp_goal_current_sedentary_proteins);
}
else{
	echo"Missing inp_goal_current_sedentary_proteins";
	die;
}


if(isset($_POST['inp_goal_current_with_activity_calories'])){
	$inp_goal_current_with_activity_calories = $_POST['inp_goal_current_with_activity_calories'];
	$inp_goal_current_with_activity_calories = output_html($inp_goal_current_with_activity_calories);
	$inp_goal_current_with_activity_calories_mysql = quote_smart($link, $inp_goal_current_with_activity_calories);
}
else{
	echo"Missing inp_goal_current_with_activity_calories";
	die;
}

if(isset($_POST['inp_goal_current_with_activity_fat'])){
	$inp_goal_current_with_activity_fat = $_POST['inp_goal_current_with_activity_fat'];
	$inp_goal_current_with_activity_fat = output_html($inp_goal_current_with_activity_fat);
	$inp_goal_current_with_activity_fat_mysql = quote_smart($link, $inp_goal_current_with_activity_fat);
}
else{
	echo"Missing inp_goal_current_with_activity_fat";
	die;
}

if(isset($_POST['inp_goal_current_with_activity_carbs'])){
	$inp_goal_current_with_activity_carbs = $_POST['inp_goal_current_with_activity_carbs'];
	$inp_goal_current_with_activity_carbs = output_html($inp_goal_current_with_activity_carbs);
	$inp_goal_current_with_activity_carbs_mysql = quote_smart($link, $inp_goal_current_with_activity_carbs);
}
else{
	echo"Missing inp_goal_current_with_activity_carbs";
	die;
}

if(isset($_POST['inp_goal_current_with_activity_proteins'])){
	$inp_goal_current_with_activity_proteins = $_POST['inp_goal_current_with_activity_proteins'];
	$inp_goal_current_with_activity_proteins = output_html($inp_goal_current_with_activity_proteins);
	$inp_goal_current_with_activity_proteins_mysql = quote_smart($link, $inp_goal_current_with_activity_proteins);
}
else{
	echo"Missing inp_goal_current_with_activity_proteins";
	die;
}



if(isset($_POST['inp_goal_target_bmr_calories'])){
	$inp_goal_target_bmr_calories = $_POST['inp_goal_target_bmr_calories'];
	$inp_goal_target_bmr_calories = output_html($inp_goal_target_bmr_calories);
	$inp_goal_target_bmr_calories_mysql = quote_smart($link, $inp_goal_target_bmr_calories);
}
else{
	echo"Missing inp_goal_target_bmr_calories";
	die;
}

if(isset($_POST['inp_goal_target_bmr_fat'])){
	$inp_goal_target_bmr_fat = $_POST['inp_goal_target_bmr_fat'];
	$inp_goal_target_bmr_fat = output_html($inp_goal_target_bmr_fat);
	$inp_goal_target_bmr_fat_mysql = quote_smart($link, $inp_goal_target_bmr_fat);
}
else{
	echo"Missing inp_goal_target_bmr_fat";
	die;
}

if(isset($_POST['inp_goal_target_bmr_carbs'])){
	$inp_goal_target_bmr_carbs = $_POST['inp_goal_target_bmr_carbs'];
	$inp_goal_target_bmr_carbs = output_html($inp_goal_target_bmr_carbs);
	$inp_goal_target_bmr_carbs_mysql = quote_smart($link, $inp_goal_target_bmr_carbs);
}
else{
	echo"Missing inp_goal_target_bmr_carbs";
	die;
}

if(isset($_POST['inp_goal_target_bmr_proteins'])){
	$inp_goal_target_bmr_proteins = $_POST['inp_goal_target_bmr_proteins'];
	$inp_goal_target_bmr_proteins = output_html($inp_goal_target_bmr_proteins);
	$inp_goal_target_bmr_proteins_mysql = quote_smart($link, $inp_goal_target_bmr_proteins);
}
else{
	echo"Missing inp_goal_target_bmr_proteins";
	die;
}

if(isset($_POST['inp_goal_target_sedentary_calories'])){
	$inp_goal_target_sedentary_calories = $_POST['inp_goal_target_sedentary_calories'];
	$inp_goal_target_sedentary_calories = output_html($inp_goal_target_sedentary_calories);
	$inp_goal_target_sedentary_calories_mysql = quote_smart($link, $inp_goal_target_sedentary_calories);
}
else{
	echo"Missing inp_goal_target_sedentary_calories";
	die;
}

if(isset($_POST['inp_goal_target_sedentary_fat'])){
	$inp_goal_target_sedentary_fat = $_POST['inp_goal_target_sedentary_fat'];
	$inp_goal_target_sedentary_fat = output_html($inp_goal_target_sedentary_fat);
	$inp_goal_target_sedentary_fat_mysql = quote_smart($link, $inp_goal_target_sedentary_fat);
}
else{
	echo"Missing inp_goal_target_sedentary_fat";
	die;
}

if(isset($_POST['inp_goal_target_sedentary_carbs'])){
	$inp_goal_target_sedentary_carbs = $_POST['inp_goal_target_sedentary_carbs'];
	$inp_goal_target_sedentary_carbs = output_html($inp_goal_target_sedentary_carbs);
	$inp_goal_target_sedentary_carbs_mysql = quote_smart($link, $inp_goal_target_sedentary_carbs);
}
else{
	echo"Missing inp_goal_target_sedentary_carbs";
	die;
}

if(isset($_POST['inp_goal_target_sedentary_proteins'])){
	$inp_goal_target_sedentary_proteins = $_POST['inp_goal_target_sedentary_proteins'];
	$inp_goal_target_sedentary_proteins = output_html($inp_goal_target_sedentary_proteins);
	$inp_goal_target_sedentary_proteins_mysql = quote_smart($link, $inp_goal_target_sedentary_proteins);
}
else{
	echo"Missing inp_goal_target_sedentary_proteins";
	die;
}




if(isset($_POST['inp_goal_target_with_activity_calories'])){
	$inp_goal_target_with_activity_calories = $_POST['inp_goal_target_with_activity_calories'];
	$inp_goal_target_with_activity_calories = output_html($inp_goal_target_with_activity_calories);
	$inp_goal_target_with_activity_calories_mysql = quote_smart($link, $inp_goal_target_with_activity_calories);
}
else{
	echo"Missing inp_goal_target_with_activity_calories";
	die;
}

if(isset($_POST['inp_goal_target_with_activity_fat'])){
	$inp_goal_target_with_activity_fat = $_POST['inp_goal_target_with_activity_fat'];
	$inp_goal_target_with_activity_fat = output_html($inp_goal_target_with_activity_fat);
	$inp_goal_target_with_activity_fat_mysql = quote_smart($link, $inp_goal_target_with_activity_fat);
}
else{
	echo"Missing inp_goal_target_with_activity_fat";
	die;
}

if(isset($_POST['inp_goal_target_with_activity_carbs'])){
	$inp_goal_target_with_activity_carbs = $_POST['inp_goal_target_with_activity_carbs'];
	$inp_goal_target_with_activity_carbs = output_html($inp_goal_target_with_activity_carbs);
	$inp_goal_target_with_activity_carbs_mysql = quote_smart($link, $inp_goal_target_with_activity_carbs);
}
else{
	echo"Missing inp_goal_target_with_activity_carbs";
	die;
}

if(isset($_POST['inp_goal_target_with_activity_proteins'])){
	$inp_goal_target_with_activity_proteins = $_POST['inp_goal_target_with_activity_proteins'];
	$inp_goal_target_with_activity_proteins = output_html($inp_goal_target_with_activity_proteins);
	$inp_goal_target_with_activity_proteins_mysql = quote_smart($link, $inp_goal_target_with_activity_proteins);
}
else{
	echo"Missing inp_goal_target_with_activity_proteins";
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

			// Update current weight
			$result = mysqli_query($link, "UPDATE $t_food_diary_goals SET 
			goal_activity_level=$inp_goal_activity_level_mysql,
			goal_current_bmr_calories=$inp_goal_current_bmr_calories_mysql,
			goal_current_bmr_fat=$inp_goal_current_bmr_fat_mysql,
			goal_current_bmr_carbs=$inp_goal_current_bmr_carbs_mysql,
			goal_current_bmr_proteins=$inp_goal_current_bmr_proteins_mysql,
			goal_current_sedentary_calories=$inp_goal_current_sedentary_calories_mysql,
			goal_current_sedentary_fat=$inp_goal_current_sedentary_fat_mysql,
			goal_current_sedentary_carbs=$inp_goal_current_sedentary_carbs_mysql,
			goal_current_sedentary_proteins=$inp_goal_current_sedentary_proteins_mysql
			 WHERE goal_id=$get_goal_id") or die(mysqli_error());

			$result = mysqli_query($link, "UPDATE $t_food_diary_goals SET 
			goal_current_with_activity_calories=$inp_goal_current_with_activity_calories_mysql,
			goal_current_with_activity_fat=$inp_goal_current_with_activity_fat_mysql,
			goal_current_with_activity_carbs=$inp_goal_current_with_activity_carbs_mysql,
			goal_current_with_activity_proteins=$inp_goal_current_with_activity_proteins_mysql
			 WHERE goal_id=$get_goal_id") or die(mysqli_error());

			// Update target weight
			$result = mysqli_query($link, "UPDATE $t_food_diary_goals SET 
			goal_target_bmr_calories=$inp_goal_target_bmr_calories_mysql,
			goal_target_bmr_fat=$inp_goal_target_bmr_fat_mysql,
			goal_target_bmr_carbs=$inp_goal_target_bmr_carbs_mysql,
			goal_target_bmr_proteins=$inp_goal_target_bmr_proteins_mysql,
			goal_target_sedentary_calories=$inp_goal_target_sedentary_calories_mysql,
			goal_target_sedentary_fat=$inp_goal_target_sedentary_fat_mysql,
			goal_target_sedentary_carbs=$inp_goal_target_sedentary_carbs_mysql,
			goal_target_sedentary_proteins=$inp_goal_target_sedentary_proteins_mysql
			 WHERE goal_id=$get_goal_id") or die(mysqli_error());

			$result = mysqli_query($link, "UPDATE $t_food_diary_goals SET 
			goal_target_with_activity_calories=$inp_goal_target_with_activity_calories_mysql,
			goal_target_with_activity_fat=$inp_goal_target_with_activity_fat_mysql,
			goal_target_with_activity_carbs=$inp_goal_target_with_activity_carbs_mysql,
			goal_target_with_activity_proteins=$inp_goal_target_with_activity_proteins_mysql
			 WHERE goal_id=$get_goal_id") or die(mysqli_error());



echo"Updated activity level";

?>