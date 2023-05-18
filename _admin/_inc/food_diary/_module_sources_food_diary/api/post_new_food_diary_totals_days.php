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

if(isset($_POST['inp_total_day_date'])){
	$inp_total_day_date = $_POST['inp_total_day_date'];
	$inp_total_day_date = output_html($inp_total_day_date);
	$inp_total_day_date_mysql = quote_smart($link, $inp_total_day_date);
}
else{
	echo"Missing inp_total_day_date";
	die;
}
if(isset($_POST['inp_total_day_target_sedentary_energy'])){
	$inp_total_day_target_sedentary_energy = $_POST['inp_total_day_target_sedentary_energy'];
	$inp_total_day_target_sedentary_energy = output_html($inp_total_day_target_sedentary_energy);
	$inp_total_day_target_sedentary_energy_mysql = quote_smart($link, $inp_total_day_target_sedentary_energy);
}
else{
	echo"Missing inp_total_day_target_sedentary_energy";
	die;
}
if(isset($_POST['inp_total_day_target_sedentary_fat'])){
	$inp_total_day_target_sedentary_fat = $_POST['inp_total_day_target_sedentary_fat'];
	$inp_total_day_target_sedentary_fat = output_html($inp_total_day_target_sedentary_fat);
	$inp_total_day_target_sedentary_fat_mysql = quote_smart($link, $inp_total_day_target_sedentary_fat);
}
else{
	echo"Missing inp_total_day_target_sedentary_fat";
	die;
}
if(isset($_POST['inp_total_day_target_sedentary_carb'])){
	$inp_total_day_target_sedentary_carb = $_POST['inp_total_day_target_sedentary_carb'];
	$inp_total_day_target_sedentary_carb = output_html($inp_total_day_target_sedentary_carb);
	$inp_total_day_target_sedentary_carb_mysql = quote_smart($link, $inp_total_day_target_sedentary_carb);

}
else{
	echo"Missing inp_total_day_target_sedentary_carb";
	die;
}
if(isset($_POST['inp_total_day_target_sedentary_protein'])){
	$inp_total_day_target_sedentary_protein = $_POST['inp_total_day_target_sedentary_protein'];
	$inp_total_day_target_sedentary_protein = output_html($inp_total_day_target_sedentary_protein);
	$inp_total_day_target_sedentary_protein_mysql = quote_smart($link, $inp_total_day_target_sedentary_protein);
}
else{
	echo"Missing inp_total_day_target_sedentary_protein";
	die;
}

if(isset($_POST['inp_total_day_target_with_activity_energy'])){
	$inp_total_day_target_with_activity_energy = $_POST['inp_total_day_target_with_activity_energy'];
	$inp_total_day_target_with_activity_energy = output_html($inp_total_day_target_with_activity_energy);
	$inp_total_day_target_with_activity_energy_mysql = quote_smart($link, $inp_total_day_target_with_activity_energy);
}
else{
	echo"Missing inp_total_day_target_with_activity_energy";
	die;
}
if(isset($_POST['inp_total_day_target_with_activity_fat'])){
	$inp_total_day_target_with_activity_fat = $_POST['inp_total_day_target_with_activity_fat'];
	$inp_total_day_target_with_activity_fat = output_html($inp_total_day_target_with_activity_fat);
	$inp_total_day_target_with_activity_fat_mysql = quote_smart($link, $inp_total_day_target_with_activity_fat);
}
else{
	echo"Missing inp_total_day_target_with_activity_fat";
	die;
}
if(isset($_POST['inp_total_day_target_with_activity_carb'])){
	$inp_total_day_target_with_activity_carb = $_POST['inp_total_day_target_with_activity_carb'];
	$inp_total_day_target_with_activity_carb = output_html($inp_total_day_target_with_activity_carb);
	$inp_total_day_target_with_activity_carb_mysql = quote_smart($link, $inp_total_day_target_with_activity_carb);
}
else{
	echo"Missing inp_total_day_target_with_activity_carb";
	die;
}
if(isset($_POST['inp_total_day_target_with_activity_protein'])){
	$inp_total_day_target_with_activity_protein = $_POST['inp_total_day_target_with_activity_protein'];
	$inp_total_day_target_with_activity_protein = output_html($inp_total_day_target_with_activity_protein);
	$inp_total_day_target_with_activity_protein_mysql = quote_smart($link, $inp_total_day_target_with_activity_protein);
}
else{
	echo"Missing inp_total_day_target_with_activity_protein";
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


// Insert today or update?
$query = "SELECT total_day_id FROM $t_food_diary_totals_days WHERE total_day_user_id='$get_user_id' AND total_day_date=$inp_total_day_date_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_total_day_id) = $row;


if($get_total_day_id == ""){
	$inp_total_day_updated = date("Y-m-d H:i:s");
	


	mysqli_query($link, "INSERT INTO $t_food_diary_totals_days
	(total_day_id, total_day_user_id, total_day_date, 
	total_day_consumed_energy, total_day_consumed_fat, total_day_consumed_carb, total_day_consumed_protein, 
	total_day_target_sedentary_energy, total_day_target_sedentary_fat, total_day_target_sedentary_carb, total_day_target_sedentary_protein, 
	total_day_target_with_activity_energy, total_day_target_with_activity_fat, total_day_target_with_activity_carb, total_day_target_with_activity_protein, 
	total_day_diff_sedentary_energy, total_day_diff_sedentary_fat, total_day_diff_sedentary_carb, total_day_diff_sedentary_protein, 
	total_day_diff_with_activity_energy, total_day_diff_with_activity_fat, total_day_diff_with_activity_carb, total_day_diff_with_activity_protein,
	total_day_updated) 
	VALUES 
	(NULL, $get_user_id, $inp_total_day_date_mysql, 
	'0', '0', '0', '0',
	$inp_total_day_target_sedentary_energy_mysql, $inp_total_day_target_sedentary_fat_mysql, $inp_total_day_target_sedentary_carb_mysql, $inp_total_day_target_sedentary_protein_mysql,
	$inp_total_day_target_with_activity_energy_mysql, $inp_total_day_target_with_activity_fat_mysql, $inp_total_day_target_with_activity_carb_mysql, $inp_total_day_target_with_activity_protein_mysql,
	$inp_total_day_target_sedentary_energy_mysql, $inp_total_day_target_sedentary_fat_mysql, $inp_total_day_target_sedentary_carb_mysql, $inp_total_day_target_sedentary_protein_mysql,
	$inp_total_day_target_with_activity_energy_mysql, $inp_total_day_target_with_activity_fat_mysql, $inp_total_day_target_with_activity_carb_mysql, $inp_total_day_target_with_activity_protein_mysql,
	'$inp_total_day_updated'
	)")
	or die(mysqli_error($link));
	
	// Get the total_day_id 
	$query = "SELECT total_day_id FROM $t_food_diary_totals_days WHERE total_day_user_id='$get_user_id' AND total_day_date=$inp_total_day_date_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_total_day_id) = $row;
}


echo"$get_total_day_id";

?>