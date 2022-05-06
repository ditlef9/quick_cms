<?php
/**
*
* File: food_diary/food_diary_add_meal.php
* Version 1.0.0.
* Date 16:22 04.04.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration --------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

/*- Root dir -------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config -------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Tables --------------------------------------------------------------------------- */
include("_tables.php");


/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);


if(isset($_GET['action'])) {
	$action = $_GET['action'];
	$action = strip_tags(stripslashes($action));
}
else{
	$action = "";
}
if(isset($_GET['date'])) {
	$date = $_GET['date'];
	$date = strip_tags(stripslashes($date));
}
else{
	$date = "";
}
if(isset($_GET['hour_name'])) {
	$hour_name = $_GET['hour_name'];
	$hour_name = stripslashes(strip_tags($hour_name));
	if($hour_name != "breakfast" && $hour_name != "lunch" && $hour_name != "before_training" && $hour_name != "after_training" && $hour_name != "linner" && $hour_name != "dinner" && $hour_name != "snacks" && $hour_name != "before_supper" && $hour_name != "supper" && $hour_name != "night_meal"){
		echo"Unknown hour name";
		die;
	}
}
else{
	echo"Missing hour name";
	die;
}

if(isset($_GET['meal_id'])){
	$meal_id = $_GET['meal_id'];
	$meal_id = strip_tags(stripslashes($meal_id));
	if(!(is_numeric($meal_id))){
		echo"meal_id not numeric";
		die;
	}
}
else{
	$meal_id = "";
}
$meal_id_mysql = quote_smart($link, $meal_id);




/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_meal - $l_new_entry - $l_food_diary";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");


/*- Content ---------------------------------------------------------------------------------- */
// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){

	// Get my profile
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_alias, user_email, user_gender, user_height, user_measurement, user_dob FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_alias, $get_my_user_email, $get_my_user_gender, $get_my_user_height, $get_user_measurement, $get_my_user_dob) = $row;
	


	if($action == "add_meal_to_diary"){
		if($process == 1){
			$datetime = date("Y-m-d H:i:s");

			$inp_entry_date = output_html($date);
			$inp_entry_date_mysql = quote_smart($link, $inp_entry_date);

			$inp_entry_date_saying = date("j M Y");
			$inp_entry_date_saying_mysql = quote_smart($link, $inp_entry_date_saying);

			$inp_entry_hour_name = output_html($hour_name);
			$inp_entry_hour_name_mysql = quote_smart($link, $inp_entry_hour_name);

			$inp_entry_meal_id = $_POST['inp_entry_meal_id'];
			$inp_entry_meal_id = output_html($inp_entry_meal_id);
			$inp_entry_meal_id_mysql = quote_smart($link, $inp_entry_meal_id);

			$inp_entry_serving_size = $_POST['inp_entry_serving_size'];
			$inp_entry_serving_size = output_html($inp_entry_serving_size);
			$inp_entry_serving_size = str_replace(",", ".", $inp_entry_serving_size);
			$inp_entry_serving_size_mysql = quote_smart($link, $inp_entry_serving_size);
			if($inp_entry_serving_size == ""){
				$url = "food_diary_add.php?date=$date&hour_name=$hour_name&l=$l";
				$url = $url . "&ft=error&fm=missing_amount";
				header("Location: $url");
				exit;
			}


			// get meal
			$query = "SELECT meal_id, meal_user_id, meal_hour_name, meal_last_used_date, meal_used_times, meal_entries, meal_entries_count, meal_selected_serving_size, meal_selected_measurement, meal_energy_metric, meal_fat_metric, meal_saturated_fat_metric, meal_monounsaturated_fat_metric, meal_polyunsaturated_fat_metric, meal_cholesterol_metric, meal_carbohydrates_metric, meal_carbohydrates_of_which_sugars_metric, meal_dietary_fiber_metric, meal_proteins_metric, meal_salt_metric, meal_sodium_metric, meal_energy_us, meal_fat_us, meal_saturated_fat_us, meal_monounsaturated_fat_us, meal_polyunsaturated_fat_us, meal_cholesterol_us, meal_carbohydrates_us, meal_carbohydrates_of_which_sugars_us, meal_dietary_fiber_us, meal_proteins_us, meal_salt_us, meal_sodium_us, meal_energy_serving, meal_fat_serving, meal_saturated_fat_serving, meal_monounsaturated_fat_serving, meal_polyunsaturated_fat_serving, meal_cholesterol_serving, meal_carbohydrates_serving, meal_carbohydrates_of_which_sugars_serving, meal_dietary_fiber_serving, meal_proteins_serving, meal_salt_serving, meal_sodium_serving, meal_energy_total, meal_fat_total, meal_saturated_total, meal_monounsaturated_fat_total, meal_polyunsaturated_fat_total, meal_cholesterol_total, meal_carbohydrates_total, meal_carbohydrates_of_which_sugars_total, meal_dietary_fiber_total, meal_proteins_total, meal_salt_total, meal_sodium_total FROM $t_food_diary_meals_index WHERE meal_id=$inp_entry_meal_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_meal_id, $get_meal_user_id, $get_meal_hour_name, $get_meal_last_used_date, $get_meal_used_times, $get_meal_entries, $get_meal_entries_count, $get_meal_selected_serving_size, $get_meal_selected_measurement, $get_meal_energy_metric, $get_meal_fat_metric, $get_meal_saturated_fat_metric, $get_meal_monounsaturated_fat_metric, $get_meal_polyunsaturated_fat_metric, $get_meal_cholesterol_metric, $get_meal_carbohydrates_metric, $get_meal_carbohydrates_of_which_sugars_metric, $get_meal_dietary_fiber_metric, $get_meal_proteins_metric, $get_meal_salt_metric, $get_meal_sodium_metric, $get_meal_energy_us, $get_meal_fat_us, $get_meal_saturated_fat_us, $get_meal_monounsaturated_fat_us, $get_meal_polyunsaturated_fat_us, $get_meal_cholesterol_us, $get_meal_carbohydrates_us, $get_meal_carbohydrates_of_which_sugars_us, $get_meal_dietary_fiber_us, $get_meal_proteins_us, $get_meal_salt_us, $get_meal_sodium_us, $get_meal_energy_serving, $get_meal_fat_serving, $get_meal_saturated_fat_serving, $get_meal_monounsaturated_fat_serving, $get_meal_polyunsaturated_fat_serving, $get_meal_cholesterol_serving, $get_meal_carbohydrates_serving, $get_meal_carbohydrates_of_which_sugars_serving, $get_meal_dietary_fiber_serving, $get_meal_proteins_serving, $get_meal_salt_serving, $get_meal_sodium_serving, $get_meal_energy_total, $get_meal_fat_total, $get_meal_saturated_total, $get_meal_monounsaturated_fat_total, $get_meal_polyunsaturated_fat_total, $get_meal_cholesterol_total, $get_meal_carbohydrates_total, $get_meal_carbohydrates_of_which_sugars_total, $get_meal_dietary_fiber_total, $get_meal_proteins_total, $get_meal_salt_total, $get_meal_sodium_total) = $row;

			if($get_meal_id == ""){
				$url = "food_diary_add.php?date=$date&hour_name=$hour_name&l=$l";
				$url = $url . "&ft=error&fm=meal_not_found";
				header("Location: $url");
				exit;
			}
			
			// update meal amount
			$inp_meal_used_times = $get_meal_used_times+1;
			$result = mysqli_query($link, "UPDATE $t_food_diary_meals_index SET 
							meal_last_used_date='$date', 
							meal_used_times=$inp_meal_used_times,
							meal_selected_serving_size=$inp_entry_serving_size_mysql
							 WHERE meal_id=$get_meal_id") or die(mysqli_error($link));

			// Insert food into diary	
			$inp_entry_name = "";		
			$query_i = "SELECT item_id, item_user_id, item_meal_id, item_food_id, item_recipe_id, item_name, item_manufacturer_name, item_image_path, item_image_file, item_image_thumb_132x132, item_image_thumb_66x132, item_serving_size, item_serving_size_measurement, item_energy_serving, item_fat_serving, item_saturated_fat_serving, item_monounsaturated_fat_serving, item_polyunsaturated_fat_serving, item_cholesterol_serving, item_carbohydrates_serving, item_carbohydrates_of_which_sugars_serving, item_dietary_fiber_serving, item_proteins_serving, item_salt_serving, item_sodium_serving FROM $t_food_diary_meals_items WHERE item_meal_id=$get_meal_id";
			$result_i = mysqli_query($link, $query_i);
			while($row_i = mysqli_fetch_row($result_i)) {
				list($get_item_id, $get_item_user_id, $get_item_meal_id, $get_item_food_id, $get_item_recipe_id, $get_item_name, $get_item_manufacturer_name, $get_item_image_path, $get_item_image_file, $get_item_image_thumb_132x132, $get_item_image_thumb_66x132, $get_item_serving_size, $get_item_serving_size_measurement, $get_item_energy_serving, $get_item_fat_serving, $get_item_saturated_fat_serving, $get_item_monounsaturated_fat_serving, $get_item_polyunsaturated_fat_serving, $get_item_cholesterol_serving, $get_item_carbohydrates_serving, $get_item_carbohydrates_of_which_sugars_serving, $get_item_dietary_fiber_serving, $get_item_proteins_serving, $get_item_salt_serving, $get_item_sodium_serving) = $row_i;

				// Names
				if($inp_entry_name == ""){
					$inp_entry_name = "$get_item_serving_size $get_item_name";
				}
				else{
					$inp_entry_name = $inp_entry_name . ", " . "$get_item_serving_size $get_item_name";
				}
				
			} // items in meal
			$inp_entry_name_mysql = quote_smart($link, $inp_entry_name);

			// PCS
			$inp_entry_serving_size_measurement_mysql = quote_smart($link, $get_meal_selected_measurement);

			$inp_entry_energy_per_entry = round($get_meal_energy_serving*$inp_entry_serving_size, 0);
			$inp_entry_energy_per_entry_mysql = quote_smart($link, $inp_entry_energy_per_entry);

			$inp_entry_fat_per_entry = round($get_meal_fat_serving*$inp_entry_serving_size, 0);
			$inp_entry_fat_per_entry_mysql = quote_smart($link, $inp_entry_fat_per_entry);

			$inp_entry_saturated_fat_per_entry = round($get_meal_saturated_fat_serving*$inp_entry_serving_size, 0);
			$inp_entry_saturated_fat_per_entry_mysql = quote_smart($link, $inp_entry_saturated_fat_per_entry);

			$inp_entry_monounsaturated_fat_per_entry = round($get_meal_monounsaturated_fat_serving*$inp_entry_serving_size, 0);
			$inp_entry_monounsaturated_fat_per_entry_mysql = quote_smart($link, $inp_entry_monounsaturated_fat_per_entry);

			$inp_entry_polyunsaturated_fat_per_entry = round($get_meal_polyunsaturated_fat_serving*$inp_entry_serving_size, 0);
			$inp_entry_polyunsaturated_fat_per_entry_mysql = quote_smart($link, $inp_entry_polyunsaturated_fat_per_entry);

			$inp_entry_cholesterol_per_entry = round($get_meal_cholesterol_serving*$inp_entry_serving_size, 0);
			$inp_entry_cholesterol_per_entry_mysql = quote_smart($link, $inp_entry_cholesterol_per_entry);

			$inp_entry_carb_per_entry = round($get_meal_carbohydrates_serving*$inp_entry_serving_size, 0);
			$inp_entry_carb_per_entry_mysql = quote_smart($link, $inp_entry_carb_per_entry);

			$inp_entry_carbohydrates_of_which_sugars_per_entry = round($get_meal_carbohydrates_of_which_sugars_serving*$inp_entry_serving_size, 0);
			$inp_entry_carbohydrates_of_which_sugars_per_entry_mysql = quote_smart($link, $inp_entry_carbohydrates_of_which_sugars_per_entry);

			$inp_entry_dietary_fiber_per_entry = round($get_meal_dietary_fiber_serving*$inp_entry_serving_size, 0);
			$inp_entry_dietary_fiber_per_entry_mysql = quote_smart($link, $inp_entry_dietary_fiber_per_entry);

			$inp_entry_protein_per_entry = round($get_meal_proteins_serving*$inp_entry_serving_size, 0);
			$inp_entry_protein_per_entry_mysql = quote_smart($link, $inp_entry_protein_per_entry);

			$inp_entry_salt_per_entry = round($get_meal_salt_serving*$inp_entry_serving_size, 0);
			$inp_entry_salt_per_entry_mysql = quote_smart($link, $inp_entry_salt_per_entry);

			$inp_entry_sodium_per_entry = round($get_meal_sodium_serving*$inp_entry_serving_size, 0);
			$inp_entry_sodium_per_entry_mysql = quote_smart($link, $inp_entry_sodium_per_entry);


			// 1) Insert meal into entry
			mysqli_query($link, "INSERT INTO $t_food_diary_entires
			(entry_id, entry_user_id, entry_date, entry_date_saying, entry_hour_name, entry_food_id, 
			entry_recipe_id, entry_meal_id, entry_name, entry_serving_size, entry_serving_size_measurement, 
			entry_energy_per_entry, entry_fat_per_entry, entry_saturated_fat_per_entry, entry_monounsaturated_fat_per_entry, entry_polyunsaturated_fat_per_entry, 
			entry_cholesterol_per_entry, entry_carbohydrates_per_entry, entry_carbohydrates_of_which_sugars_per_entry, entry_dietary_fiber_per_entry, entry_proteins_per_entry, 
			entry_salt_per_entry, entry_sodium_per_entry, entry_updated_datetime, entry_synchronized) 
			VALUES 
			(NULL, '$get_my_user_id', $inp_entry_date_mysql, $inp_entry_date_saying_mysql, $inp_entry_hour_name_mysql, 0, 
			'0', $get_meal_id, $inp_entry_name_mysql, $inp_entry_serving_size_mysql, $inp_entry_serving_size_measurement_mysql, 
			$inp_entry_energy_per_entry_mysql, $inp_entry_fat_per_entry_mysql, $inp_entry_saturated_fat_per_entry_mysql, $inp_entry_monounsaturated_fat_per_entry_mysql, $inp_entry_polyunsaturated_fat_per_entry_mysql, 
			$inp_entry_cholesterol_per_entry_mysql, $inp_entry_carb_per_entry_mysql, $inp_entry_carbohydrates_of_which_sugars_per_entry_mysql, $inp_entry_dietary_fiber_per_entry_mysql, $inp_entry_protein_per_entry_mysql,
			$inp_entry_salt_per_entry_mysql, $inp_entry_sodium_per_entry_mysql, '$datetime', '0')")
			or die(mysqli_error($link));


			// 2) Update Consumed Hours (Example breakfast, lunch, dinner)
			$inp_hour_energy = 0;
			$inp_hour_fat = 0;
			$inp_hour_saturated_fat = 0;
			$inp_hour_monounsaturated_fat = 0;
			$inp_hour_polyunsaturated_fat = 0;
			$inp_hour_cholesterol = 0;
			$inp_hour_carbohydrates = 0;
			$inp_hour_carbohydrates_of_which_sugars = 0;
			$inp_hour_dietary_fiber = 0;
			$inp_hour_proteins = 0;
			$inp_hour_salt = 0;
			$inp_hour_sodium = 0;
			
			$query = "SELECT entry_id, entry_energy_per_entry, entry_fat_per_entry, entry_saturated_fat_per_entry, entry_monounsaturated_fat_per_entry, entry_polyunsaturated_fat_per_entry, entry_cholesterol_per_entry, entry_carbohydrates_per_entry, entry_carbohydrates_of_which_sugars_per_entry, entry_dietary_fiber_per_entry, entry_proteins_per_entry, entry_salt_per_entry, entry_sodium_per_entry FROM $t_food_diary_entires WHERE entry_user_id=$my_user_id_mysql AND entry_date=$inp_entry_date_mysql AND entry_hour_name=$inp_entry_hour_name_mysql";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
    				list($get_entry_id, $get_entry_energy_per_entry, $get_entry_fat_per_entry, $get_entry_saturated_fat_per_entry, $get_entry_monounsaturated_fat_per_entry, $get_entry_polyunsaturated_fat_per_entry, $get_entry_cholesterol_per_entry, $get_entry_carbohydrates_per_entry, $get_entry_carbohydrates_of_which_sugars_per_entry, $get_entry_dietary_fiber_per_entry, $get_entry_proteins_per_entry, $get_entry_salt_per_entry, $get_entry_sodium_per_entry) = $row;

				$inp_hour_energy = $inp_hour_energy+$get_entry_energy_per_entry;
				$inp_hour_fat = $inp_hour_fat+$get_entry_fat_per_entry;
				$inp_hour_saturated_fat = $inp_hour_saturated_fat+$get_entry_saturated_fat_per_entry;
				$inp_hour_monounsaturated_fat = $inp_hour_monounsaturated_fat+$get_entry_monounsaturated_fat_per_entry;
				$inp_hour_polyunsaturated_fat = $inp_hour_polyunsaturated_fat+$get_entry_polyunsaturated_fat_per_entry;
				$inp_hour_cholesterol = $inp_hour_cholesterol+$get_entry_cholesterol_per_entry;
				$inp_hour_carbohydrates = $inp_hour_carbohydrates+$get_entry_carbohydrates_per_entry;
				$inp_hour_carbohydrates_of_which_sugars = $inp_hour_carbohydrates_of_which_sugars+$get_entry_carbohydrates_of_which_sugars_per_entry;
				$inp_hour_dietary_fiber = $inp_hour_dietary_fiber+$get_entry_dietary_fiber_per_entry;
				$inp_hour_proteins = $inp_hour_proteins+$get_entry_proteins_per_entry;
				$inp_hour_salt = $inp_hour_salt+$get_entry_salt_per_entry;
				$inp_hour_sodium = $inp_hour_sodium+$get_entry_sodium_per_entry;
				
			}
			
			$date = date("Y-m-d");
			$datetime = date("Y-m-d H:i:s");
			$hour_name_mysql = quote_smart($link, $hour_name);

			$result = mysqli_query($link, "UPDATE $t_food_diary_consumed_hours SET 
							consumed_hour_energy=$inp_hour_energy,
							consumed_hour_fat=$inp_hour_fat,
							consumed_hour_saturated_fat='$inp_hour_saturated_fat',
							consumed_hour_monounsaturated_fat='$inp_hour_monounsaturated_fat',
							consumed_hour_polyunsaturated_fat='$inp_hour_polyunsaturated_fat',
							consumed_hour_cholesterol='$inp_hour_cholesterol',
							consumed_hour_carbohydrates='$inp_hour_carbohydrates',
							consumed_hour_carbohydrates_of_which_sugars='$inp_hour_carbohydrates_of_which_sugars',
							consumed_hour_dietary_fiber='$inp_hour_dietary_fiber',
							consumed_hour_proteins='$inp_hour_proteins',
							consumed_hour_salt='$inp_hour_salt',
							consumed_hour_sodium='$inp_hour_sodium',
							consumed_hour_updated_datetime='$datetime',
							consumed_hour_synchronized=0
							 WHERE consumed_hour_user_id=$my_user_id_mysql AND consumed_hour_date='$date' AND consumed_hour_name=$hour_name_mysql") or die(mysqli_error($link));

			// 3) Update Consumed Days (first calculate calories, fat etc used)
			$inp_consumed_day_energy = 0;
			$inp_consumed_day_fat = 0;
			$inp_consumed_day_saturated_fat = 0;
			$inp_consumed_day_monounsaturated_fat = 0;
			$inp_consumed_day_polyunsaturated_fat = 0;
			$inp_consumed_day_cholesterol = 0;
			$inp_consumed_day_carbohydrates = 0;
			$inp_consumed_day_carbohydrates_of_which_sugars = 0;
			$inp_consumed_day_dietary_fiber = 0;
			$inp_consumed_day_proteins = 0;
			$inp_consumed_day_salt = 0;
			$inp_consumed_day_sodium = 0;
			
			$query = "SELECT entry_id, entry_energy_per_entry, entry_fat_per_entry, entry_saturated_fat_per_entry, entry_monounsaturated_fat_per_entry, entry_polyunsaturated_fat_per_entry, entry_cholesterol_per_entry, entry_carbohydrates_per_entry, entry_carbohydrates_of_which_sugars_per_entry, entry_dietary_fiber_per_entry, entry_proteins_per_entry, entry_salt_per_entry, entry_sodium_per_entry FROM $t_food_diary_entires WHERE entry_user_id=$my_user_id_mysql AND entry_date=$inp_entry_date_mysql";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
    				list($get_entry_id, $get_entry_energy_per_entry, $get_entry_fat_per_entry, $get_entry_saturated_fat_per_entry, $get_entry_monounsaturated_fat_per_entry, $get_entry_polyunsaturated_fat_per_entry, $get_entry_cholesterol_per_entry, $get_entry_carbohydrates_per_entry, $get_entry_carbohydrates_of_which_sugars_per_entry, $get_entry_dietary_fiber_per_entry, $get_entry_proteins_per_entry, $get_entry_salt_per_entry, $get_entry_sodium_per_entry) = $row;

				$inp_consumed_day_energy 			= $inp_consumed_day_energy+$get_entry_energy_per_entry;
				$inp_consumed_day_fat 				= $inp_consumed_day_fat+$get_entry_fat_per_entry;
				$inp_consumed_day_saturated_fat 		= $inp_consumed_day_saturated_fat+$get_entry_saturated_fat_per_entry;
				$inp_consumed_day_monounsaturated_fat 		= $inp_consumed_day_monounsaturated_fat+$get_entry_monounsaturated_fat_per_entry;
				$inp_consumed_day_polyunsaturated_fat 		= $inp_consumed_day_polyunsaturated_fat+$get_entry_polyunsaturated_fat_per_entry;
				$inp_consumed_day_cholesterol 			= $inp_consumed_day_cholesterol+$get_entry_cholesterol_per_entry;
				$inp_consumed_day_carbohydrates 		= $inp_consumed_day_carbohydrates+$get_entry_carbohydrates_per_entry;
				$inp_consumed_day_carbohydrates_of_which_sugars = $inp_consumed_day_carbohydrates_of_which_sugars+$get_entry_carbohydrates_of_which_sugars_per_entry;
				$inp_consumed_day_dietary_fiber 		= $inp_consumed_day_dietary_fiber+$get_entry_dietary_fiber_per_entry;
				$inp_consumed_day_proteins 			= $inp_consumed_day_proteins+$get_entry_proteins_per_entry;
				$inp_consumed_day_salt 				= $inp_consumed_day_salt+$get_entry_salt_per_entry;
				$inp_consumed_day_sodium 			= $inp_consumed_day_sodium+$get_entry_sodium_per_entry;
				
			}
			
			$query = "SELECT consumed_day_id, consumed_day_user_id, consumed_day_year, consumed_day_month, consumed_day_month_saying, consumed_day_day, consumed_day_day_saying, consumed_day_date, consumed_day_energy, consumed_day_fat, consumed_day_saturated_fat, consumed_day_monounsaturated_fat, consumed_day_polyunsaturated_fat, consumed_day_cholesterol, consumed_day_carbohydrates, consumed_day_carbohydrates_of_which_sugars, consumed_day_dietary_fiber, consumed_day_proteins, consumed_day_salt, consumed_day_sodium, consumed_day_target_sedentary_energy, consumed_day_target_sedentary_fat, consumed_day_target_sedentary_carb, consumed_day_target_sedentary_protein, consumed_day_target_with_activity_energy, consumed_day_target_with_activity_fat, consumed_day_target_with_activity_carb, consumed_day_target_with_activity_protein, consumed_day_diff_sedentary_energy, consumed_day_diff_sedentary_fat, consumed_day_diff_sedentary_carb, consumed_day_diff_sedentary_protein, consumed_day_diff_with_activity_energy, consumed_day_diff_with_activity_fat, consumed_day_diff_with_activity_carb, consumed_day_diff_with_activity_protein, consumed_day_updated_datetime, consumed_day_synchronized FROM $t_food_diary_consumed_days WHERE consumed_day_user_id=$my_user_id_mysql AND consumed_day_date=$inp_entry_date_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_consumed_day_id, $get_consumed_day_user_id, $get_consumed_day_year, $get_consumed_day_month, $get_consumed_day_month_saying, $get_consumed_day_day, $get_consumed_day_day_saying, $get_consumed_day_date, $get_consumed_day_energy, $get_consumed_day_fat, $get_consumed_day_saturated_fat, $get_consumed_day_monounsaturated_fat, $get_consumed_day_polyunsaturated_fat, $get_consumed_day_cholesterol, $get_consumed_day_carbohydrates, $get_consumed_day_carbohydrates_of_which_sugars, $get_consumed_day_dietary_fiber, $get_consumed_day_proteins, $get_consumed_day_salt, $get_consumed_day_sodium, $get_consumed_day_target_sedentary_energy, $get_consumed_day_target_sedentary_fat, $get_consumed_day_target_sedentary_carb, $get_consumed_day_target_sedentary_protein, $get_consumed_day_target_with_activity_energy, $get_consumed_day_target_with_activity_fat, $get_consumed_day_target_with_activity_carb, $get_consumed_day_target_with_activity_protein, $get_consumed_day_diff_sedentary_energy, $get_consumed_day_diff_sedentary_fat, $get_consumed_day_diff_sedentary_carb, $get_consumed_day_diff_sedentary_protein, $get_consumed_day_diff_with_activity_energy, $get_consumed_day_diff_with_activity_fat, $get_consumed_day_diff_with_activity_carb, $get_consumed_day_diff_with_activity_protein, $get_consumed_day_updated_datetime, $get_consumed_day_synchronized) = $row;



			$inp_consumed_day_diff_sedentary_energy 	= $get_consumed_day_target_sedentary_energy-$inp_consumed_day_energy;
			$inp_consumed_day_diff_sedentary_fat 		= $get_consumed_day_target_sedentary_fat-$inp_consumed_day_fat;
			$inp_consumed_day_diff_sedentary_carb		= $get_consumed_day_target_sedentary_carb-$inp_consumed_day_carbohydrates;
			$inp_consumed_day_diff_sedentary_protein 	= $get_consumed_day_target_sedentary_protein-$inp_consumed_day_proteins;
	

			$inp_consumed_day_diff_with_activity_energy = $get_consumed_day_target_with_activity_energy-$inp_consumed_day_energy;
			$inp_consumed_day_diff_with_activity_fat = $get_consumed_day_target_with_activity_fat-$inp_consumed_day_fat;
			$inp_consumed_day_diff_with_activity_carb = $get_consumed_day_target_with_activity_carb-$inp_consumed_day_carbohydrates;
			$inp_consumed_day_diff_with_activity_protein = $get_consumed_day_target_with_activity_protein-$inp_consumed_day_proteins;

			$result = mysqli_query($link, "UPDATE $t_food_diary_consumed_days SET 
							consumed_day_energy='$inp_consumed_day_energy', 
							consumed_day_fat='$inp_consumed_day_fat', 
							consumed_day_saturated_fat='$inp_consumed_day_saturated_fat', 
							consumed_day_monounsaturated_fat='$inp_consumed_day_monounsaturated_fat', 
							consumed_day_polyunsaturated_fat='$inp_consumed_day_polyunsaturated_fat', 
							consumed_day_cholesterol='$inp_consumed_day_cholesterol', 
							consumed_day_carbohydrates='$inp_consumed_day_carbohydrates', 
							consumed_day_carbohydrates_of_which_sugars='$inp_consumed_day_carbohydrates_of_which_sugars', 
							consumed_day_dietary_fiber='$inp_consumed_day_dietary_fiber', 
							consumed_day_proteins='$inp_consumed_day_proteins', 
							consumed_day_salt='$inp_consumed_day_salt', 
							consumed_day_sodium='$inp_consumed_day_sodium', 
						
							consumed_day_diff_sedentary_energy='$inp_consumed_day_diff_sedentary_energy', 
							consumed_day_diff_sedentary_fat='$inp_consumed_day_diff_sedentary_fat', 
							consumed_day_diff_sedentary_carb='$inp_consumed_day_diff_sedentary_carb', 
							consumed_day_diff_sedentary_protein='$inp_consumed_day_diff_sedentary_protein',

							consumed_day_diff_with_activity_energy='$inp_consumed_day_diff_with_activity_energy', 
							consumed_day_diff_with_activity_fat='$inp_consumed_day_diff_with_activity_fat', 
							consumed_day_diff_with_activity_carb='$inp_consumed_day_diff_with_activity_carb', 
							consumed_day_diff_with_activity_protein='$inp_consumed_day_diff_with_activity_protein',

							consumed_day_updated_datetime='$datetime', 
							consumed_day_synchronized='0'
							 WHERE consumed_day_user_id=$my_user_id_mysql AND consumed_day_date=$inp_entry_date_mysql") or die(mysqli_error($link));


			// 4) Update last used food
			$query = "SELECT last_used_id, last_used_times FROM $t_food_diary_last_used WHERE last_used_user_id=$my_user_id_mysql AND last_used_hour_name=$inp_entry_hour_name_mysql AND last_used_meal_id=$inp_entry_meal_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_last_used_id, $get_last_used_times) = $row;
			$inp_last_used_times = $get_last_used_times + 1;

			$result = mysqli_query($link, "UPDATE $t_food_diary_last_used SET 
								last_used_times='$inp_last_used_times', 
								last_used_updated_datetime='$datetime', 
								last_used_selected_serving_size=$inp_entry_serving_size_mysql
								 WHERE last_used_id='$get_last_used_id'") or die(mysqli_error($link));



			// Header
			$url = "index.php?action=food_diary&date=$date";
			$url = $url . "&ft=success&fm=food_added";
			if($hour_name == "breakfast"){
				
			}
			elseif($hour_name == "lunch"){
				$url = $url . "#hour_breakfast";
			}
			elseif($hour_name == "before_training"){
				$url = $url . "#hour_lunch";
			}
			elseif($hour_name == "after_training"){
				$url = $url . "#hour_before_training";
			}
			elseif($hour_name == "linner"){
				$url = $url . "#hour_after_training";
			}
			elseif($hour_name == "snacks"){
				$url = $url . "#hour_linner";
			}
			elseif($hour_name == "before_supper"){
				$url = $url . "#hour_snacks";
			}
			elseif($hour_name == "supper"){
				$url = $url . "#hour_before_supper";
			}
			elseif($hour_name == "night_meal"){
				$url = $url . "#hour_supper";
			}
			header("Location: $url");
			exit;
		}


	} // add_food_to_diary
}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login?l=$l&amp;referer=$root/food_diary/index.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>