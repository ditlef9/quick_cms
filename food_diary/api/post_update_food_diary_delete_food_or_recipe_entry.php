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



if(isset($_POST['inp_entry_id'])){
	$inp_entry_id = $_POST['inp_entry_id'];
	$inp_entry_id = output_html($inp_entry_id);
	$inp_entry_id_mysql = quote_smart($link, $inp_entry_id);
}
else{
	echo"Missing inp_entry_id";
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


// Get the food diary entry
$query = "SELECT entry_id, entry_user_id, entry_date, entry_meal_id, entry_food_id, entry_recipe_id, entry_name, entry_manufacturer_name, entry_serving_size, entry_serving_size_measurement, entry_energy_per_entry, entry_fat_per_entry, entry_carb_per_entry, entry_protein_per_entry, entry_text, entry_deleted, entry_updated, entry_synchronized
 FROM $t_food_diary_entires WHERE entry_id=$inp_entry_id_mysql AND entry_user_id='$get_user_id'";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_entry_id, $get_entry_user_id, $get_entry_date, $get_entry_meal_id, $get_entry_food_id, $get_entry_recipe_id, $get_entry_name, $get_entry_manufacturer_name, $get_entry_serving_size, $get_entry_serving_size_measurement, $get_entry_energy_per_entry, $get_entry_fat_per_entry, $get_entry_carb_per_entry, $get_entry_protein_per_entry, $get_entry_text, $get_entry_deleted, $get_entry_updated, $get_entry_synchronized) = $row;
if($get_entry_id == ""){
	echo"Entry not found";
	die;
}
$inp_entry_date_mysql = quote_smart($link, $get_entry_date);
$inp_entry_meal_id_mysql = quote_smart($link, $get_entry_meal_id);
echo"$get_entry_id";


// Delete from food diary
$datetime = date("Y-m-d H:i:s");
mysqli_query($link, "DELETE FROM $t_food_diary_entires WHERE entry_id=$inp_entry_id_mysql AND entry_user_id='$get_user_id'") or die(mysqli_error($link));



// Fetch total meal

// food_diary_totals_meals :: Calcualte :: Get all meals for that day, and update numbers
$inp_total_meal_energy = 0;
$inp_total_meal_fat = 0;
$inp_total_meal_carb = 0;
$inp_total_meal_protein = 0;
			
$query = "SELECT entry_id, entry_energy_per_entry, entry_fat_per_entry, entry_carb_per_entry, entry_protein_per_entry FROM $t_food_diary_entires WHERE entry_user_id='$get_user_id' AND entry_date=$inp_entry_date_mysql AND entry_meal_id=$inp_entry_meal_id_mysql";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_row($result)) {
	list($get_entry_id, $get_entry_energy_per_entry, $get_entry_fat_per_entry, $get_entry_carb_per_entry, $get_entry_protein_per_entry) = $row;

	$inp_total_meal_energy 		= $inp_total_meal_energy+$get_entry_energy_per_entry;
	$inp_total_meal_fat 		= $inp_total_meal_fat+$get_entry_fat_per_entry;
	$inp_total_meal_carb		= $inp_total_meal_carb+$get_entry_carb_per_entry;
	$inp_total_meal_protein 	= $inp_total_meal_protein+$get_entry_protein_per_entry;
}
			
$result = mysqli_query($link, "UPDATE $t_food_diary_totals_meals SET 
total_meal_energy='$inp_total_meal_energy', total_meal_fat='$inp_total_meal_fat', total_meal_carb='$inp_total_meal_carb', total_meal_protein='$inp_total_meal_protein',
total_meal_updated='$datetime', total_meal_synchronized='0'
 WHERE total_meal_user_id='$get_user_id' AND total_meal_date=$inp_entry_date_mysql AND total_meal_meal_id=$inp_entry_meal_id_mysql") or die(mysqli_error($link));

// food_diary_totals_days
$query = "SELECT total_day_id, total_day_user_id, total_day_date, total_day_consumed_energy, total_day_consumed_fat, total_day_consumed_carb, total_day_consumed_protein, total_day_target_sedentary_energy, total_day_target_sedentary_fat, total_day_target_sedentary_carb, total_day_target_sedentary_protein, total_day_target_with_activity_energy, total_day_target_with_activity_fat, total_day_target_with_activity_carb, total_day_target_with_activity_protein, total_day_diff_sedentary_energy, total_day_diff_sedentary_fat, total_day_diff_sedentary_carb, total_day_diff_sedentary_protein, total_day_diff_with_activity_energy, total_day_diff_with_activity_fat, total_day_diff_with_activity_carb, total_day_diff_with_activity_protein FROM $t_food_diary_totals_days WHERE total_day_user_id='$get_user_id' AND total_day_date=$inp_entry_date_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_total_day_id, $get_total_day_user_id, $get_total_day_date, $get_total_day_consumed_energy, $get_total_day_consumed_fat, $get_total_day_consumed_carb, $get_total_day_consumed_protein, $get_total_day_target_sedentary_energy, $get_total_day_target_sedentary_fat, $get_total_day_target_sedentary_carb, $get_total_day_target_sedentary_protein, $get_total_day_target_with_activity_energy, $get_total_day_target_with_activity_fat, $get_total_day_target_with_activity_carb, $get_total_day_target_with_activity_protein, $get_total_day_diff_sedentary_energy, $get_total_day_diff_sedentary_fat, $get_total_day_diff_sedentary_carb, $get_total_day_diff_sedentary_protein, $get_total_day_diff_with_activity_energy, $get_total_day_diff_with_activity_fat, $get_total_day_diff_with_activity_carb, $get_total_day_diff_with_activity_protein) = $row;

$inp_total_day_consumed_energy = 0;
$inp_total_day_consumed_fat = 0;
$inp_total_day_consumed_carb = 0;
$inp_total_day_consumed_protein = 0;
$query = "SELECT total_meal_id, total_meal_energy, total_meal_fat, total_meal_carb, total_meal_protein FROM $t_food_diary_totals_meals WHERE total_meal_user_id='$get_user_id' AND total_meal_date=$inp_entry_date_mysql";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_row($result)) {
   		list($get_total_meal_id, $get_total_meal_energy, $get_total_meal_fat, $get_total_meal_carb, $get_total_meal_protein) = $row;
				
			$inp_total_day_consumed_energy  = $inp_total_day_consumed_energy+$get_total_meal_energy;
			$inp_total_day_consumed_fat     = $inp_total_day_consumed_fat+$get_total_meal_fat;
			$inp_total_day_consumed_carb    = $inp_total_day_consumed_carb+$get_total_meal_carb;
			$inp_total_day_consumed_protein = $inp_total_day_consumed_protein+$get_total_meal_protein;
}


			$inp_total_day_diff_sedentary_energy = $get_total_day_target_sedentary_energy-$inp_total_day_consumed_energy;
			$inp_total_day_diff_sedentary_fat = $get_total_day_target_sedentary_fat-$inp_total_day_consumed_fat;
			$inp_total_day_diff_sedentary_carb = $get_total_day_target_sedentary_energy-$inp_total_day_consumed_carb;
			$inp_total_day_diff_sedentary_protein = $get_total_day_target_sedentary_energy-$inp_total_day_consumed_protein;
	

			$inp_total_day_diff_with_activity_energy = $get_total_day_target_with_activity_energy-$inp_total_day_consumed_energy;
			$inp_total_day_diff_with_activity_fat = $get_total_day_target_with_activity_fat-$inp_total_day_consumed_fat;
			$inp_total_day_diff_with_activity_carb = $get_total_day_target_with_activity_energy-$inp_total_day_consumed_carb;
			$inp_total_day_diff_with_activity_protein = $get_total_day_target_with_activity_energy-$inp_total_day_consumed_protein;

$result = mysqli_query($link, "UPDATE $t_food_diary_totals_days SET 
	total_day_consumed_energy='$inp_total_day_consumed_energy', total_day_consumed_fat='$inp_total_day_consumed_fat', total_day_consumed_carb='$inp_total_day_consumed_carb', total_day_consumed_protein=$inp_total_day_consumed_protein,
	total_day_diff_sedentary_energy='$inp_total_day_diff_sedentary_energy', total_day_diff_sedentary_fat='$inp_total_day_diff_sedentary_fat', total_day_diff_sedentary_carb='$inp_total_day_diff_sedentary_carb', total_day_diff_sedentary_protein='$inp_total_day_diff_sedentary_protein',
	total_day_diff_with_activity_energy='$inp_total_day_diff_with_activity_energy', total_day_diff_with_activity_fat='$inp_total_day_diff_with_activity_fat', total_day_diff_with_activity_carb='$inp_total_day_diff_with_activity_carb', total_day_diff_with_activity_protein='$inp_total_day_diff_with_activity_protein',
	total_day_updated='$datetime', total_day_synchronized='0'
	 WHERE total_day_user_id='$get_user_id' AND total_day_date=$inp_entry_date_mysql") or die(mysqli_error($link));




?>