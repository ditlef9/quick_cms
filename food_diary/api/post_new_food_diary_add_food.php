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


	/*- Tables recipes -------------------------------------------------------------------- */
	$t_recipes 	 			= $mysqlPrefixSav . "recipes";
	$t_recipes_ingredients			= $mysqlPrefixSav . "recipes_ingredients";
	$t_recipes_favorites			= $mysqlPrefixSav . "recipes_favorites";
	$t_recipes_groups			= $mysqlPrefixSav . "recipes_groups";
	$t_recipes_items			= $mysqlPrefixSav . "recipes_items";
	$t_recipes_numbers			= $mysqlPrefixSav . "recipes_numbers";
	$t_recipes_rating			= $mysqlPrefixSav . "recipes_rating";
	$t_recipes_cuisines			= $mysqlPrefixSav . "recipes_cuisines";
	$t_recipes_cuisines_translations	= $mysqlPrefixSav . "recipes_cuisines_translations";
	$t_recipes_seasons			= $mysqlPrefixSav . "recipes_seasons";
	$t_recipes_seasons_translations		= $mysqlPrefixSav . "recipes_seasons_translations";
	$t_recipes_occasions			= $mysqlPrefixSav . "recipes_occasions";
	$t_recipes_occasions_translations	= $mysqlPrefixSav . "recipes_occasions_translations";
	$t_recipes_categories			= $mysqlPrefixSav . "recipes_categories";
	$t_recipes_categories_translations	= $mysqlPrefixSav . "recipes_categories_translations";
	$t_recipes_weekly_special		= $mysqlPrefixSav . "recipes_weekly_special";
	$t_recipes_of_the_day			= $mysqlPrefixSav . "recipes_of_the_day";
	$t_recipes_comments			= $mysqlPrefixSav . "recipes_comments";
	$t_recipes_tags				= $mysqlPrefixSav . "recipes_tags";
	$t_recipes_links			= $mysqlPrefixSav . "recipes_links";

	/*- Tables Food -------------------------------------------------------------------- */
	$t_food_categories		  = $mysqlPrefixSav . "food_categories";
	$t_food_categories_translations	  = $mysqlPrefixSav . "food_categories_translations";
	$t_food_index			  = $mysqlPrefixSav . "food_index";
	$t_food_index_stores		  = $mysqlPrefixSav . "food_index_stores";
	$t_food_index_ads		  = $mysqlPrefixSav . "food_index_ads";
	$t_food_index_tags		  = $mysqlPrefixSav . "food_index_tags";
	$t_food_index_prices		  = $mysqlPrefixSav . "food_index_prices";
	$t_food_index_contents		  = $mysqlPrefixSav . "food_index_contents";
	$t_food_stores		  	  = $mysqlPrefixSav . "food_stores";
	$t_food_prices_currencies	  = $mysqlPrefixSav . "food_prices_currencies";
	$t_food_favorites 		  = $mysqlPrefixSav . "food_favorites";
	$t_food_measurements	 	  = $mysqlPrefixSav . "food_measurements";
	$t_food_measurements_translations = $mysqlPrefixSav . "food_measurements_translations";

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



if(isset($_POST['inp_entry_date'])){
	$inp_entry_date = $_POST['inp_entry_date'];
	$inp_entry_date = output_html($inp_entry_date);
	$inp_entry_date_mysql = quote_smart($link, $inp_entry_date);
}
else{
	echo"Missing inp_entry_date";
	die;
}
if(isset($_POST['inp_entry_meal_id'])){
	$inp_entry_meal_id = $_POST['inp_entry_meal_id'];
	$inp_entry_meal_id = output_html($inp_entry_meal_id);
	$inp_entry_meal_id_mysql = quote_smart($link, $inp_entry_meal_id);
}
else{
	echo"Missing inp_entry_meal_id";
	die;
}
if(isset($_POST['inp_entry_food_id'])){
	$inp_entry_food_id = $_POST['inp_entry_food_id'];
	$inp_entry_food_id = output_html($inp_entry_food_id);
	$inp_entry_food_id_mysql = quote_smart($link, $inp_entry_food_id);
}
else{
	echo"Missing inp_entry_food_id";
	die;
}
if(isset($_POST['inp_entry_recipe_id'])){
	$inp_entry_recipe_id = $_POST['inp_entry_recipe_id'];
	$inp_entry_recipe_id = output_html($inp_entry_recipe_id);
	$inp_entry_recipe_id_mysql = quote_smart($link, $inp_entry_recipe_id);

}
else{
	echo"Missing inp_entry_recipe_id";
	die;
}
if(isset($_POST['inp_entry_name'])){
	$inp_entry_name = $_POST['inp_entry_name'];
	$inp_entry_name = output_html($inp_entry_name);
	$inp_entry_name_mysql = quote_smart($link, $inp_entry_name);
}
else{
	echo"Missing inp_entry_name";
	die;
}

if(isset($_POST['inp_entry_manufacturer_name'])){
	$inp_entry_manufacturer_name = $_POST['inp_entry_manufacturer_name'];
	$inp_entry_manufacturer_name = output_html($inp_entry_manufacturer_name);
	$inp_entry_manufacturer_name_mysql = quote_smart($link, $inp_entry_manufacturer_name);
}
else{
	echo"Missing inp_entry_manufacturer_name";
	die;
}
if(isset($_POST['inp_entry_serving_size'])){
	$inp_entry_serving_size = $_POST['inp_entry_serving_size'];
	$inp_entry_serving_size = output_html($inp_entry_serving_size);
	$inp_entry_serving_size_mysql = quote_smart($link, $inp_entry_serving_size);
}
else{
	echo"Missing inp_entry_serving_size";
	die;
}
if(isset($_POST['inp_entry_serving_size_measurement'])){
	$inp_entry_serving_size_measurement = $_POST['inp_entry_serving_size_measurement'];
	$inp_entry_serving_size_measurement = output_html($inp_entry_serving_size_measurement);
	$inp_entry_serving_size_measurement_mysql = quote_smart($link, $inp_entry_serving_size_measurement);
}
else{
	echo"Missing inp_entry_serving_size_measurement";
	die;
}
if(isset($_POST['inp_entry_energy_per_entry'])){
	$inp_entry_energy_per_entry = $_POST['inp_entry_energy_per_entry'];
	$inp_entry_energy_per_entry = output_html($inp_entry_energy_per_entry);
	$inp_entry_energy_per_entry_mysql = quote_smart($link, $inp_entry_energy_per_entry);
}
else{
	echo"Missing inp_entry_energy_per_entry";
	die;
}

if(isset($_POST['inp_entry_fat_per_entry'])){
	$inp_entry_fat_per_entry = $_POST['inp_entry_fat_per_entry'];
	$inp_entry_fat_per_entry = output_html($inp_entry_fat_per_entry);
	$inp_entry_fat_per_entry_mysql = quote_smart($link, $inp_entry_fat_per_entry);
}
else{
	echo"Missing inp_entry_fat_per_entry";
	die;
}

if(isset($_POST['inp_entry_carb_per_entry'])){
	$inp_entry_carb_per_entry = $_POST['inp_entry_carb_per_entry'];
	$inp_entry_carb_per_entry = output_html($inp_entry_carb_per_entry);
	$inp_entry_carb_per_entry_mysql = quote_smart($link, $inp_entry_carb_per_entry);
}
else{
	echo"Missing inp_entry_carb_per_entry";
	die;
}

if(isset($_POST['inp_entry_protein_per_entry'])){
	$inp_entry_protein_per_entry = $_POST['inp_entry_protein_per_entry'];
	$inp_entry_protein_per_entry = output_html($inp_entry_protein_per_entry);
	$inp_entry_protein_per_entry_mysql = quote_smart($link, $inp_entry_protein_per_entry);
}
else{
	echo"Missing inp_entry_protein_per_entry";
	die;
}

if(isset($_POST['inp_entry_updated'])){
	$inp_entry_updated = $_POST['inp_entry_updated'];
	$inp_entry_updated = output_html($inp_entry_updated);
	$inp_entry_updated_mysql = quote_smart($link, $inp_entry_updated);
}
else{
	echo"Missing inp_entry_updated";
	die;
}

if(isset($_POST['inp_total_meal_energy'])){
	$inp_total_meal_energy = $_POST['inp_total_meal_energy'];
	$inp_total_meal_energy = output_html($inp_total_meal_energy);
	$inp_total_meal_energy_mysql = quote_smart($link, $inp_total_meal_energy);
}
else{
	echo"Missing inp_total_meal_energy";
	die;
}
if(isset($_POST['inp_total_meal_fat'])){
	$inp_total_meal_fat = $_POST['inp_total_meal_fat'];
	$inp_total_meal_fat = output_html($inp_total_meal_fat);
	$inp_total_meal_fat_mysql = quote_smart($link, $inp_total_meal_fat);
}
else{
	echo"Missing inp_total_meal_fat";
	die;
}
if(isset($_POST['inp_total_meal_carb'])){
	$inp_total_meal_carb = $_POST['inp_total_meal_carb'];
	$inp_total_meal_carb = output_html($inp_total_meal_carb);
	$inp_total_meal_carb_mysql = quote_smart($link, $inp_total_meal_carb);
}
else{
	echo"Missing inp_total_meal_carb";
	die;
}
if(isset($_POST['inp_total_meal_protein'])){
	$inp_total_meal_protein = $_POST['inp_total_meal_protein'];
	$inp_total_meal_protein = output_html($inp_total_meal_protein);
	$inp_total_meal_protein_mysql = quote_smart($link, $inp_total_meal_protein);
}
else{
	echo"Missing inp_total_meal_protein";
	die;
}


if(isset($_POST['inp_total_day_consumed_energy'])){
	$inp_total_day_consumed_energy = $_POST['inp_total_day_consumed_energy'];
	$inp_total_day_consumed_energy = output_html($inp_total_day_consumed_energy);
	$inp_total_day_consumed_energy_mysql = quote_smart($link, $inp_total_day_consumed_energy);
}
else{
	echo"Missing inp_total_day_consumed_energy";
	die;
}
if(isset($_POST['inp_total_day_consumed_fat'])){
	$inp_total_day_consumed_fat = $_POST['inp_total_day_consumed_fat'];
	$inp_total_day_consumed_fat = output_html($inp_total_day_consumed_fat);
	$inp_total_day_consumed_fat_mysql = quote_smart($link, $inp_total_day_consumed_fat);
}
else{
	echo"Missing inp_total_day_consumed_fat";
	die;
}
if(isset($_POST['inp_total_day_consumed_carb'])){
	$inp_total_day_consumed_carb = $_POST['inp_total_day_consumed_carb'];
	$inp_total_day_consumed_carb = output_html($inp_total_day_consumed_carb);
	$inp_total_day_consumed_carb_mysql = quote_smart($link, $inp_total_day_consumed_carb);
}
else{
	echo"Missing inp_total_day_consumed_carb";
	die;
}
if(isset($_POST['inp_total_day_consumed_protein'])){
	$inp_total_day_consumed_protein = $_POST['inp_total_day_consumed_protein'];
	$inp_total_day_consumed_protein = output_html($inp_total_day_consumed_protein);
	$inp_total_day_consumed_protein_mysql = quote_smart($link, $inp_total_day_consumed_protein);
}
else{
	echo"Missing inp_total_day_consumed_protein";
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

if(isset($_POST['inp_total_day_diff_with_activity_energy'])){
	$inp_total_day_diff_with_activity_energy = $_POST['inp_total_day_diff_with_activity_energy'];
	$inp_total_day_diff_with_activity_energy = output_html($inp_total_day_diff_with_activity_energy);
	$inp_total_day_diff_with_activity_energy_mysql = quote_smart($link, $inp_total_day_diff_with_activity_energy);
}
else{
	echo"Missing inp_total_day_diff_with_activity_energy";
	die;
}
if(isset($_POST['inp_total_day_diff_with_activity_fat'])){
	$inp_total_day_diff_with_activity_fat = $_POST['inp_total_day_diff_with_activity_fat'];
	$inp_total_day_diff_with_activity_fat = output_html($inp_total_day_diff_with_activity_fat);
	$inp_total_day_diff_with_activity_fat_mysql = quote_smart($link, $inp_total_day_diff_with_activity_fat);
}
else{
	echo"Missing inp_total_day_diff_with_activity_fat";
	die;
}
if(isset($_POST['inp_total_day_diff_with_activity_carb'])){
	$inp_total_day_diff_with_activity_carb = $_POST['inp_total_day_diff_with_activity_carb'];
	$inp_total_day_diff_with_activity_carb = output_html($inp_total_day_diff_with_activity_carb);
	$inp_total_day_diff_with_activity_carb_mysql = quote_smart($link, $inp_total_day_diff_with_activity_carb);
}
else{
	echo"Missing inp_total_day_diff_with_activity_carb";
	die;
}
if(isset($_POST['inp_total_day_diff_with_activity_protein'])){
	$inp_total_day_diff_with_activity_protein = $_POST['inp_total_day_diff_with_activity_protein'];
	$inp_total_day_diff_with_activity_protein = output_html($inp_total_day_diff_with_activity_protein);
	$inp_total_day_diff_with_activity_protein_mysql = quote_smart($link, $inp_total_day_diff_with_activity_protein);
}
else{
	echo"Missing inp_total_day_diff_with_activity_protein";
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


// Insert into food diary
mysqli_query($link, "INSERT INTO $t_food_diary_entires
(entry_id, entry_user_id, entry_date, entry_meal_id, entry_food_id, entry_recipe_id, 
entry_name, entry_manufacturer_name, entry_serving_size, entry_serving_size_measurement, 
entry_energy_per_entry, entry_fat_per_entry, entry_carb_per_entry, entry_protein_per_entry,
entry_updated, entry_synchronized) 
VALUES 
(NULL, '$get_user_id', $inp_entry_date_mysql, $inp_entry_meal_id_mysql, $inp_entry_food_id_mysql, $inp_entry_recipe_id_mysql,
$inp_entry_name_mysql, $inp_entry_manufacturer_name_mysql, $inp_entry_serving_size_mysql, $inp_entry_serving_size_measurement_mysql, 
$inp_entry_energy_per_entry_mysql, $inp_entry_fat_per_entry_mysql, $inp_entry_carb_per_entry_mysql, $inp_entry_protein_per_entry_mysql,
$inp_entry_updated_mysql, $inp_entry_updated_mysql)")
or die(mysqli_error($link));

// Get food diary id
$query = "SELECT entry_id FROM $t_food_diary_entires WHERE entry_user_id='$get_user_id' ORDER BY entry_id DESC LIMIT 0,1";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_entry_id) = $row;
echo"$get_entry_id";

// Fetch total meal
$query = "SELECT total_meal_id FROM $t_food_diary_totals_meals WHERE total_meal_user_id='$get_user_id' AND total_meal_date=$inp_entry_date_mysql AND total_meal_meal_id=$inp_entry_meal_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_total_meal_id) = $row;
if($get_total_meal_id == ""){
	mysqli_query($link, "INSERT INTO $t_food_diary_totals_meals 
	(total_meal_id, total_meal_user_id, total_meal_date, total_meal_meal_id, total_meal_energy, total_meal_fat, total_meal_carb, total_meal_protein, total_meal_updated, total_meal_synchronized) 
	VALUES 
	(NULL, '$get_user_id', $inp_entry_date_mysql, $inp_entry_meal_id_mysql, $inp_total_meal_energy_mysql, $inp_total_meal_fat_mysql, $inp_total_meal_carb_mysql, $inp_total_meal_protein_mysql,
	$inp_entry_updated_mysql, $inp_entry_updated_mysql)")
	or die(mysqli_error($link));

	$query = "SELECT total_meal_id FROM $t_food_diary_totals_meals WHERE total_meal_user_id='$get_user_id' AND total_meal_date=$inp_entry_date_mysql AND total_meal_meal_id=$inp_entry_meal_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_total_meal_id) = $row;
}
else{
	$result = mysqli_query($link, "UPDATE $t_food_diary_totals_meals SET 
			total_meal_energy=$inp_total_meal_energy_mysql, 
			total_meal_fat=$inp_total_meal_fat_mysql, 
			total_meal_carb=$inp_total_meal_carb_mysql, 
			total_meal_protein=$inp_total_meal_protein_mysql,
			total_meal_updated=$inp_entry_updated_mysql, 
			total_meal_synchronized=$inp_entry_updated_mysql 
			WHERE total_meal_id='$get_total_meal_id'") or die(mysqli_error($link));
}

echo"-$get_total_meal_id";


// food_diary_totals_days
$query = "SELECT total_day_id FROM $t_food_diary_totals_days WHERE total_day_user_id='$get_user_id' AND total_day_date=$inp_entry_date_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_total_day_id) = $row;
if($get_total_day_id == ""){
	mysqli_query($link, "INSERT INTO $t_food_diary_totals_days 
	(total_day_id, total_day_user_id, total_day_date, 
	total_day_consumed_energy, total_day_consumed_fat, total_day_consumed_carb, total_day_consumed_protein, 

	total_day_target_sedentary_energy, total_day_target_sedentary_fat, total_day_target_sedentary_carb, total_day_target_sedentary_protein, 
	total_day_target_with_activity_energy, total_day_target_with_activity_fat, total_day_target_with_activity_carb, total_day_target_with_activity_protein, 
	total_day_diff_sedentary_energy, total_day_diff_sedentary_fat, total_day_diff_sedentary_carb, total_day_diff_sedentary_protein, 
	total_day_diff_with_activity_energy, total_day_diff_with_activity_fat, total_day_diff_with_activity_carb, total_day_diff_with_activity_protein, 
	total_day_updated, total_day_synchronized) 
	VALUES 
	(NULL, '$get_user_id', $inp_entry_date_mysql, 
	$inp_total_day_consumed_energy_mysql, $inp_total_day_consumed_fat_mysql, $inp_total_day_consumed_carb_mysql, $inp_total_day_consumed_protein_mysql,
$inp_total_day_target_sedentary_energy_mysql, $inp_total_day_target_sedentary_fat_mysql, $inp_total_day_target_sedentary_carb_mysql, $inp_total_day_target_sedentary_protein_mysql,
$inp_total_day_target_with_activity_energy_mysql, $inp_total_day_target_with_activity_fat_mysql, $inp_total_day_target_with_activity_carb_mysql, $inp_total_day_target_with_activity_protein_mysql, 
	$inp_total_day_diff_sedentary_energy_mysql, $inp_total_day_diff_sedentary_fat_mysql, $inp_total_day_diff_sedentary_carb_mysql, $inp_total_day_diff_sedentary_protein_mysql, 
	$inp_total_day_diff_with_activity_energy_mysql, $inp_total_day_diff_with_activity_fat_mysql, $inp_total_day_diff_with_activity_carb_mysql, $inp_total_day_diff_with_activity_protein_mysql, 
	$inp_entry_updated_mysql, $inp_entry_updated_mysql)")
	or die(mysqli_error($link));

	$query = "SELECT total_day_id FROM $t_food_diary_totals_days WHERE total_day_user_id='$get_user_id' AND total_day_date=$inp_entry_date_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_total_day_id) = $row;
}
else{
	$result = mysqli_query($link, "UPDATE $t_food_diary_totals_days SET 
	total_day_consumed_energy=$inp_total_day_consumed_energy_mysql, total_day_consumed_fat=$inp_total_day_consumed_fat_mysql, total_day_consumed_carb=$inp_total_day_consumed_carb_mysql, total_day_consumed_protein=$inp_total_day_consumed_protein_mysql,
	total_day_diff_sedentary_energy=$inp_total_day_diff_sedentary_energy_mysql, total_day_diff_sedentary_fat=$inp_total_day_diff_sedentary_fat_mysql, total_day_diff_sedentary_carb=$inp_total_day_target_sedentary_carb_mysql, total_day_diff_sedentary_protein=$inp_total_day_target_sedentary_protein_mysql,
	total_day_diff_with_activity_energy=$inp_total_day_diff_with_activity_energy_mysql, total_day_diff_with_activity_fat=$inp_total_day_diff_with_activity_fat_mysql, total_day_diff_with_activity_carb=$inp_total_day_diff_with_activity_carb_mysql, total_day_diff_with_activity_protein=$inp_total_day_diff_with_activity_protein_mysql,
	total_day_updated=$inp_entry_updated_mysql, total_day_synchronized=$inp_entry_updated_mysql
	 WHERE total_day_user_id='$get_user_id' AND total_day_date=$inp_entry_date_mysql");
}

echo"-$get_total_day_id";



// Last used
$day_of_the_week = date("N");

if($inp_entry_recipe_id == "0"){
	$query = "SELECT last_used_id, last_used_times FROM $t_food_diary_last_used WHERE last_used_user_id='$get_user_id' AND last_used_day_of_week='$day_of_the_week' AND last_used_meal_id=$inp_entry_meal_id_mysql AND last_used_food_id=$inp_entry_food_id_mysql";
}
else{
	$query = "SELECT last_used_id, last_used_times FROM $t_food_diary_last_used WHERE last_used_user_id='$get_user_id' AND last_used_day_of_week='$day_of_the_week' AND last_used_meal_id=$inp_entry_meal_id_mysql AND last_used_recipe_id=$inp_entry_recipe_id_mysql";
}
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_last_used_id, $get_last_used_times) = $row;
if($get_last_used_id == ""){
	// Get info about food or recipe
	if($inp_entry_recipe_id == "0"){
		$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_net_content, food_net_content_measurement, food_serving_size_gram, food_serving_size_gram_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_energy, food_proteins, food_carbohydrates, food_carbohydrates_of_which_sugars, food_fat, food_fat_of_which_saturated_fatty_acids, food_salt, food_score, food_energy_calculated, food_proteins_calculated, food_salt_calculated, food_carbohydrates_calculated, food_carbohydrates_of_which_sugars_calculated, food_fat_calculated, food_fat_of_which_saturated_fatty_acids_calculated, food_barcode, food_category_id, food_image_path, food_thumb, food_image_a, food_image_b, food_image_c, food_image_d, food_image_e, food_last_used, food_language, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_comments, food_likes, food_dislikes, food_likes_ip_block, food_user_ip, food_date, food_time, food_last_viewed FROM $t_food_index WHERE food_id=$inp_entry_food_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_net_content, $get_food_net_content_measurement, $get_food_serving_size_gram, $get_food_serving_size_gram_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_energy, $get_food_proteins, $get_food_carbohydrates, $get_food_carbohydrates_of_which_sugars, $get_food_fat, $get_food_fat_of_which_saturated_fatty_acids, $get_food_salt, $get_food_score, $get_food_energy_calculated, $get_food_proteins_calculated, $get_food_salt_calculated, $get_food_carbohydrates_calculated, $get_food_carbohydrates_of_which_sugars_calculated, $get_food_fat_calculated, $get_food_fat_of_which_saturated_fatty_acids_calculated, $get_food_barcode, $get_food_category_id, $get_food_image_path, $get_food_thumb, $get_food_image_a, $get_food_image_b, $get_food_image_c, $get_food_image_d, $get_food_image_e, $get_food_last_used, $get_food_language, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_comments, $get_food_likes, $get_food_dislikes, $get_food_likes_ip_block, $get_food_user_ip, $get_food_date, $get_food_time, $get_food_last_viewed) = $row;

		$inp_last_used_image_path_mysql = quote_smart($link, $get_food_image_path);
		$inp_last_used_image_thumb_mysql = quote_smart($link, $get_food_thumb);

		$inp_last_used_net_content_mysql = quote_smart($link, $get_food_net_content);
		$inp_last_used_net_content_measurement_mysql = quote_smart($link, $get_food_net_content_measurement);

		$inp_last_used_serving_size_gram_mysql = quote_smart($link, $get_food_serving_size_gram);
		$inp_last_used_serving_size_gram_measurement_mysql = quote_smart($link, $get_food_serving_size_gram_measurement); 
		$inp_last_used_serving_size_pcs_mysql = quote_smart($link, $get_food_serving_size_pcs);
		$inp_last_used_serving_size_pcs_measurement_mysql = quote_smart($link, $get_food_serving_size_pcs_measurement);

		$inp_last_used_calories_per_hundred = quote_smart($link, $get_food_energy);
		$inp_last_used_fat_per_hundred = quote_smart($link, $get_food_fat);
		$inp_last_used_saturated_fatty_acids_per_hundred = quote_smart($link, $get_food_fat_of_which_saturated_fatty_acids);
		$inp_last_used_carbs_per_hundred = quote_smart($link, $get_food_carbohydrates);
		$inp_last_used_sugar_per_hundred = quote_smart($link, $get_food_carbohydrates_of_which_sugars);
		$inp_last_used_proteins_per_hundred = quote_smart($link, $get_food_proteins);
		$inp_last_used_salt_per_hundred = quote_smart($link, $get_food_salt);
		$inp_last_used_calories_per_serving = quote_smart($link, $get_food_energy_calculated);
		$inp_last_used_fat_per_serving = quote_smart($link, $get_food_fat_calculated);
		$inp_last_used_saturated_fatty_acids_per_serving = quote_smart($link, $get_food_fat_of_which_saturated_fatty_acids_calculated);
		$inp_last_used_carbs_per_serving = quote_smart($link, $get_food_carbohydrates_calculated);
		$inp_last_used_sugar_per_serving = quote_smart($link, $get_food_carbohydrates_of_which_sugars_calculated);
		$inp_last_used_proteins_per_serving = quote_smart($link, $get_food_proteins_calculated);
		$inp_last_used_salt_per_serving_mysql = quote_smart($link, $get_food_salt_calculated);

	}
	else{
		$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_introduction, recipe_directions, recipe_image_path, recipe_image, recipe_thumb, recipe_video, recipe_date, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_user_ip, recipe_notes, recipe_password FROM $t_recipes WHERE recipe_id=$inp_entry_recipe_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb, $get_recipe_video, $get_recipe_date, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password) = $row;

		// Select Nutrients
		$query = "SELECT number_id, number_recipe_id, number_hundred_calories, number_hundred_proteins, number_hundred_fat, number_hundred_carbs, number_serving_calories, number_serving_proteins, number_serving_fat, number_serving_carbs, number_total_weight, number_total_calories, number_total_proteins, number_total_fat, number_total_carbs, number_servings FROM $t_recipes_numbers WHERE number_recipe_id=$inp_entry_recipe_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_number_id, $get_number_recipe_id, $get_number_hundred_calories, $get_number_hundred_proteins, $get_number_hundred_fat, $get_number_hundred_carbs, $get_number_serving_calories, $get_number_serving_proteins, $get_number_serving_fat, $get_number_serving_carbs, $get_number_total_weight, $get_number_total_calories, $get_number_total_proteins, $get_number_total_fat, $get_number_total_carbs, $get_number_servings) = $row;



		$inp_last_used_image_path_mysql = quote_smart($link, $get_recipe_image_path);
		$inp_last_used_image_thumb_mysql = quote_smart($link, $get_recipe_image);

		$inp_last_used_net_content_mysql = quote_smart($link, "100");
		$inp_last_used_net_content_measurement_mysql = quote_smart($link, "g");

		$inp_last_used_serving_size_gram_mysql = quote_smart($link, "100"); // just a guess
		$inp_last_used_serving_size_gram_measurement_mysql = quote_smart($link, "g");
		$inp_last_used_serving_size_pcs_mysql = quote_smart($link, "1");
		$inp_last_used_serving_size_pcs_measurement_mysql = quote_smart($link, "serving");

		$inp_last_used_calories_per_hundred = quote_smart($link, $get_number_hundred_calories);
		$inp_last_used_fat_per_hundred = quote_smart($link, $get_number_hundred_fat);
		$inp_last_used_saturated_fatty_acids_per_hundred = quote_smart($link, "0");

		$inp_last_used_carbs_per_hundred = quote_smart($link, $get_number_hundred_carbs);
		$inp_last_used_sugar_per_hundred = quote_smart($link, "0");
		$inp_last_used_proteins_per_hundred = quote_smart($link, $get_number_hundred_proteins);

		$inp_last_used_salt_per_hundred = quote_smart($link, "0");
		$inp_last_used_calories_per_serving = quote_smart($link, $get_number_serving_calories);
		$inp_last_used_fat_per_serving = quote_smart($link, $get_number_serving_fat);

		$inp_last_used_saturated_fatty_acids_per_serving = quote_smart($link, "0");
		$inp_last_used_carbs_per_serving = quote_smart($link, $get_number_serving_carbs);
		$inp_last_used_sugar_per_serving = quote_smart($link, "0");

		$inp_last_used_proteins_per_serving = quote_smart($link, $get_number_serving_proteins);
		$inp_last_used_salt_per_serving_mysql = quote_smart($link,"0");



	}


	// First time used this food
	mysqli_query($link, "INSERT INTO $t_food_diary_last_used
	(last_used_id, last_used_user_id, last_used_day_of_week, last_used_meal_id, last_used_food_id, last_used_recipe_id, 
	last_used_serving_size, last_used_times, last_used_date, last_used_updated, last_used_synchronized, last_used_name, 
	last_used_manufacturer, last_used_image_path, last_used_image_thumb, 
	last_used_net_content, last_used_net_content_measurement, last_used_serving_size_gram, 
	last_used_serving_size_gram_measurement, last_used_serving_size_pcs, last_used_serving_size_pcs_measurement, 
	last_used_calories_per_hundred, last_used_fat_per_hundred, last_used_saturated_fatty_acids_per_hundred, 
	last_used_carbs_per_hundred, last_used_sugar_per_hundred, last_used_proteins_per_hundred, 
	last_used_salt_per_hundred, last_used_calories_per_serving, last_used_fat_per_serving, 
	last_used_saturated_fatty_acids_per_serving, last_used_carbs_per_serving, last_used_sugar_per_serving, 
	last_used_proteins_per_serving, last_used_salt_per_serving) 
	VALUES 
	(NULL, '$get_user_id', '$day_of_the_week', 
	$inp_entry_meal_id_mysql, $inp_entry_food_id_mysql, $inp_entry_recipe_id_mysql, 
	$inp_entry_serving_size_mysql, '1', $inp_entry_date_mysql,
	$inp_entry_updated_mysql, $inp_entry_updated_mysql, $inp_entry_name_mysql, 
	$inp_entry_manufacturer_name_mysql, $inp_last_used_image_path_mysql, $inp_last_used_image_thumb_mysql, 
	$inp_last_used_net_content_mysql, $inp_last_used_net_content_measurement_mysql, $inp_last_used_serving_size_gram_mysql, 
	$inp_last_used_serving_size_gram_measurement_mysql, $inp_last_used_serving_size_pcs_mysql, $inp_last_used_serving_size_pcs_measurement_mysql, 
$inp_last_used_calories_per_hundred, $inp_last_used_fat_per_hundred, $inp_last_used_saturated_fatty_acids_per_hundred,
$inp_last_used_carbs_per_hundred, $inp_last_used_sugar_per_hundred, $inp_last_used_proteins_per_hundred,
$inp_last_used_salt_per_hundred, $inp_last_used_calories_per_serving, $inp_last_used_fat_per_serving,
$inp_last_used_saturated_fatty_acids_per_serving, $inp_last_used_carbs_per_serving, $inp_last_used_sugar_per_serving, 
$inp_last_used_proteins_per_serving, $inp_last_used_salt_per_serving_mysql)")
	or die(mysqli_error($link));


	if($inp_entry_recipe_id == "0"){
		$query = "SELECT last_used_id, last_used_times FROM $t_food_diary_last_used WHERE last_used_user_id='$get_user_id' AND last_used_day_of_week='$day_of_the_week' AND last_used_meal_id=$inp_entry_meal_id_mysql AND last_used_food_id=$inp_entry_food_id_mysql";
	}
	else{
		$query = "SELECT last_used_id, last_used_times FROM $t_food_diary_last_used WHERE last_used_user_id='$get_user_id' AND last_used_day_of_week='$day_of_the_week' AND last_used_meal_id=$inp_entry_meal_id_mysql AND last_used_recipe_id=$inp_entry_recipe_id_mysql";
	}
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_last_used_id, $get_last_used_times) = $row;
}
else{
	// Update counter and date
	$inp_last_used_times = $get_last_used_times + 1;

	$result = mysqli_query($link, "UPDATE $t_food_diary_last_used SET 
	last_used_times='$inp_last_used_times', last_serving_size=$inp_entry_serving_size_mysql, last_used_date=$inp_entry_date_mysql,
	last_used_updated=$inp_entry_updated_mysql, last_used_synchronized=$inp_entry_updated_mysql
	WHERE last_used_id='$get_last_used_id'");
}
echo"-$get_last_used_id";
?>