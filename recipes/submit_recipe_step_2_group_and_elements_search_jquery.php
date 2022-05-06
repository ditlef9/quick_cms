<?php 
/**
*
* File: food/search_jquery.php
* Version 1.0.0
* Date 11:24 04.02.2019
* Copyright (c) 2018-2019 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/



/*- Functions ------------------------------------------------------------------------ */
include("../_admin/_functions/output_html.php");
include("../_admin/_functions/clean.php");
include("../_admin/_functions/quote_smart.php");
include("../_admin/_functions/resize_crop_image.php");
include("../_admin/_functions/get_extension.php");
include("../_admin/_functions/decode_national_letters.php");





/*- Common variables ----------------------------------------------------------------- */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);



/*- MySQL ------------------------------------------------------------ */
$check = substr($server_name, 0, 3);
if($check == "www"){
	$server_name = substr($server_name, 3);
}

$setup_finished_file = "setup_finished_" . $server_name . ".php";
if(!(file_exists("../_admin/_data/$setup_finished_file"))){
	die;
}

else{
	include("../_admin/_data/config/meta.php");
	include("../_admin/_data/config/user_system.php");

}

$mysql_config_file = "../_admin/_data/mysql_" . $server_name . ".php";
include("$mysql_config_file");
$link = mysqli_connect($mysqlHostSav, $mysqlUserNameSav, $mysqlPasswordSav, $mysqlDatabaseNameSav);
if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}







/*- Tables ---------------------------------------------------------------------------- */
include("_tables.php");

/*- MySQL Tables -------------------------------------------------------------------- */
$t_food_index		= $mysqlPrefixSav . "food_index";
$t_food_queries 	= $mysqlPrefixSav . "food_queries";
$t_recipes 		= $mysqlPrefixSav . "recipes";
$t_recipes_user_adapted_view 	= $mysqlPrefixSav . "recipes_user_adapted_view";



/*- Variables ------------------------------------------------------------------------- */

if(isset($_GET['recipe_id'])){
	$recipe_id = $_GET['recipe_id'];
	$recipe_id = output_html($recipe_id);
}
elseif(isset($_POST['recipe_id'])){
	$recipe_id = $_POST['recipe_id'];
	$recipe_id = output_html($recipe_id);
}
else{
	$recipe_id = "";
}

if(isset($_GET['columns']) OR isset($_POST['columns'])){
	if(isset($_GET['columns'])){
		$columns = $_GET['columns'];
	}
	else{
		$columns = $_POST['columns'];
	}
	
	$columns = output_html($columns);
	if(!(is_numeric($columns))){
		echo"columns not numeric";
		die;
	}
}
else{
	$columns = "4";
}
if(isset($_GET['l']) OR isset($_POST['l'])) {
	if(isset($_GET['l'])){
		$l = $_GET['l'];
	}
	else{
		$l = $_POST['l'];
	}
	$l = strip_tags(stripslashes($l));
}
else{
	$l = "";
}
$l_mysql = quote_smart($link, $l);


if(isset($_GET['order_by']) OR isset($_POST['order_by'])) {
	if(isset($_GET['order_by'])){
		$order_by = $_GET['order_by'];
	}
	else{
		$order_by = $_POST['order_by'];
	}
	$order_by = strip_tags(stripslashes($order_by));
}
else{
	$order_by = "";
}
if(isset($_GET['order_method']) OR isset($_POST['order_method'])) {
	if(isset($_GET['order_method'])){
		$order_method = $_GET['order_method'];
	}
	else{
		$order_method = $_POST['order_method'];
	}
	$order_method = strip_tags(stripslashes($order_method));
}
else{
	$order_method = "";
}
if(isset($_GET['view_id']) OR isset($_POST['view_id'])) {
	if(isset($_GET['view_id'])){
		$view_id = $_GET['view_id'];
	}
	else{
		$view_id = $_POST['view_id'];
	}
	$view_id = strip_tags(stripslashes($view_id));
	if(!(is_numeric($view_id))){
		$view_id = 0;
	}
}
else{
	$view_id = 0;
}


$root = "..";



// Get recipe
$recipe_id_mysql = quote_smart($link, $recipe_id);

$query = "SELECT recipe_id, recipe_country, recipe_directions FROM $t_recipes WHERE recipe_id=$recipe_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_recipe_id, $get_recipe_country, $get_recipe_directions) = $row;

if($get_recipe_id == ""){
	echo"
	<h1>Server error</h1>

	<p>
	Recipe not found.
	</p>
	";
	die;
}


/*- Language ------------------------------------------------------------------------ */
if(file_exists("../_admin/_translations/site/$l/food/ts_index.php")){
	include("../_admin/_translations/site/$l/food/ts_index.php");
	include("../_admin/_translations/site/$l/recipes/ts_submit_recipe_step_2_group_and_elements.php");
}
else{
	$l = "en";
	include("../_admin/_translations/site/en/food/ts_index.php");
	include("../_admin/_translations/site/en/recipes/ts_submit_recipe_step_2_group_and_elements.php");
}
/*- Table exists? -------------------------------------------------------------------- */
$query = "SELECT * FROM $t_food_queries LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{
	echo"Table created";
	mysqli_query($link, "CREATE TABLE $t_food_queries(
	 query_id INT NOT NULL AUTO_INCREMENT,
	 PRIMARY KEY(query_id), 
	 query_name VARCHAR(90) NOT NULL,
	 query_times BIGINT,
	 query_last_use DATETIME,
	 query_hidden INT)")
	 or die(mysql_error());
}


/*- User adapted view ---------------------------------------------------------------- */
$view_id_mysql = quote_smart($link, $view_id);
$query_t = "SELECT view_id, view_user_id, view_ip, view_year, view_system, view_hundred_metric, view_serving, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_recipes_user_adapted_view WHERE view_id=$view_id_mysql";
$result_t = mysqli_query($link, $query_t);
$row_t = mysqli_fetch_row($result_t);
list($get_current_view_id, $get_current_view_user_id, $get_current_view_ip, $get_current_view_year, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_serving, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
if($get_current_view_id == ""){
	echo"<div class=\"error\"><p>No view</p></div>";
	die;
}


/*- Query --------------------------------------------------------------------------- */
if(isset($_GET['q']) OR isset($_POST['q'])){
	if(isset($_GET['q'])) {
		$q = $_GET['q'];
	}
	else{
		$q = $_POST['q'];
	}
	$q = trim($q);
	$q = strtolower($q);
	$inp_datetime = date("Y-m-d H:i:s");
	$q = output_html($q);
	$q_mysql = quote_smart($link, $q);
	if($q != ""){
		$query_t = "SELECT query_name, query_times FROM $t_food_queries WHERE query_name=$q_mysql";
		$result_t = mysqli_query($link, $query_t);
		$row_t = mysqli_fetch_row($result_t);
		list($get_query_name, $get_query_times) = $row_t;


		if($get_query_name == ""){
			// Insert
			$insert_error = "0";
			mysqli_query($link, "INSERT INTO $t_food_queries
			(query_name, query_times, query_last_use) 
			VALUES
			($q_mysql, '1', '$inp_datetime') ")
			or $insert_error = 1;
		}
		else{
			$inp_query_times = $get_query_times+1;
			$result = mysqli_query($link, "UPDATE $t_food_queries SET query_times='$inp_query_times', query_last_use='$inp_datetime' WHERE query_name=$q_mysql");
		}


		// Ready for MySQL search
		$q = "%" . $q . "%";
		$q_mysql = quote_smart($link, $q);

		// Set layout
		$x = 0;

		// Query
		$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_text, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_numbers_entered_method, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_trans_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_added_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_trans_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_added_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_score_place_in_sub_category, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_trans_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_added_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_trans_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_added_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_energy_net_content, food_fat_net_content, food_saturated_fat_net_content, food_trans_fat_net_content, food_monounsaturated_fat_net_content, food_polyunsaturated_fat_net_content, food_cholesterol_net_content, food_carbohydrates_net_content, food_carbohydrates_of_which_sugars_net_content, food_added_sugars_net_content, food_dietary_fiber_net_content, food_proteins_net_content, food_salt_net_content, food_sodium_net_content, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_no_of_comments, food_stars, food_stars_sum, food_comments_multiplied_stars, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction
 FROM $t_food_index";
		$query = $query  . " WHERE food_language=$l_mysql AND (food_manufacturer_name_and_food_name LIKE $q_mysql OR food_name LIKE $q_mysql)";
		// Order
		if($order_by != ""){
			if($order_method == "desc"){
				$order_method_mysql = "DESC";
			}
			else{
				$order_method_mysql = "ASC";
			}
			if($order_by == "food_id" OR $order_by == "food_name" OR $order_by == "food_unique_hits" 
			OR $order_by == "food_energy" OR $order_by == "food_proteins" OR $order_by == "food_carbohydrates" OR $order_by == "food_fat"){
				$order_by_mysql = "$order_by";
			}
			else{
				$order_by_mysql = "food_id";
			}
			$query = $query . " ORDER BY $order_by_mysql $order_method_mysql";

		}

		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_text, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_numbers_entered_method, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_trans_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_added_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_trans_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_added_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_score_place_in_sub_category, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_trans_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_added_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_trans_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_added_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_energy_net_content, $get_food_fat_net_content, $get_food_saturated_fat_net_content, $get_food_trans_fat_net_content, $get_food_monounsaturated_fat_net_content, $get_food_polyunsaturated_fat_net_content, $get_food_cholesterol_net_content, $get_food_carbohydrates_net_content, $get_food_carbohydrates_of_which_sugars_net_content, $get_food_added_sugars_net_content, $get_food_dietary_fiber_net_content, $get_food_proteins_net_content, $get_food_salt_net_content, $get_food_sodium_net_content, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_no_of_comments, $get_food_stars, $get_food_stars_sum, $get_food_comments_multiplied_stars, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row;

			if($get_food_image_a != "" && file_exists("../$get_food_image_path/$get_food_image_a")){


				// Name saying
				$title = "$get_food_manufacturer_name $get_food_name";
				$check = strlen($title);
				if($check > 35){
					$title = substr($title, 0, 35);
					$title = $title . "...";
				}


				if($columns == "4"){
					if($x == 0){
						echo"
						<div class=\"clear\"></div>
						<div class=\"left_center_center_right_left\" style=\"text-align: center;padding-bottom: 20px;\">
						";
					}
					elseif($x == 1){
						echo"
						<div class=\"left_center_center_left_right_center\" style=\"text-align: center;padding-bottom: 20px;\">
						";
					}
					elseif($x == 2){
						echo"
						<div class=\"left_center_center_right_right_center\" style=\"text-align: center;padding-bottom: 20px;\">
						";
					}
					elseif($x == 3){
						echo"
						<div class=\"left_center_center_right_right\" style=\"text-align: center;padding-bottom: 20px;\">
						";
					}
				}
				elseif($columns == "2"){
					if($x == 0){
						echo"
						<div class=\"clear\"></div>
						<div class=\"left_right_left\" style=\"text-align: center;padding-bottom: 20px;\">
						";
					}
					elseif($x == 1){
						echo"
						<div class=\"left_right_right\" style=\"text-align: center;padding-bottom: 20px;\">
						";
					}
				}
		


				echo"
				<p style=\"padding-bottom:6px;\">
				<a href=\"../food/view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\" class=\"_blank\"><img src=\"../$get_food_image_path/$get_food_thumb_a_small\" alt=\"$get_food_image_a\" style=\"margin-bottom: 5px;\" /></a><br />
				<a href=\"../food/view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\" class=\"_blank\">$title</a>
				</p>

				<!-- Add food to recipe buttons -->
					<p style=\"margin:0;padding: 0px 0px 5px 0px;\">
					<a href=\"#\" id=\"food_click_action_gram_$get_food_id\" class=\"btn_default\">$get_food_serving_size_measurement_metric</a>
					";

					if($get_food_serving_size_pcs_measurement != "$get_food_serving_size_measurement_metric"){
						echo"<a href=\"#\" id=\"food_click_action_pcs_$get_food_id\" class=\"btn_default\">$get_food_serving_size_pcs $get_food_serving_size_pcs_measurement</a><br />\n";
					}
					echo"
					</p>
				<!-- //Add food to recipe buttons -->\n";

	
				if($get_current_view_hundred_metric == "1" OR $get_current_view_pcs_metric == "1" OR $get_current_view_eight_us == "1" OR $get_current_view_pcs_us == "1"){
				
					echo"
					<table style=\"margin: 0px auto;\">
					";
					if($get_current_view_hundred_metric == "1"){
						echo"
						 <tr>
						  <td style=\"padding-right: 6px;text-align: center;\">
						<span class=\"nutritional_number\">$l_hundred</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;\">
						<span class=\"nutritional_number\">$get_food_energy_metric</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;\">
						<span class=\"nutritional_number\">$get_food_fat_metric</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;\">
						<span class=\"nutritional_number\">$get_food_carbohydrates_metric</span>
						  </td>
						  <td style=\"text-align: center;\">
						<span class=\"nutritional_number\">$get_food_proteins_metric</span>
						  </td>
						 </tr>
						";
					}
					if($get_current_view_pcs_metric == "1"){
						echo"
						 <tr>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
							<span class=\"nutritional_number\" title=\"$get_food_serving_size_metric $get_food_serving_size_measurement_metric\">$get_food_serving_size_pcs $get_food_serving_size_pcs_measurement</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_energy_calculated_metric</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_fat_calculated_metric</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_carbohydrates_calculated_metric</span>
						  </td>
						  <td style=\"text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_proteins_calculated_metric</span>
						  </td>
						 </tr>
						";
					}
					if($get_current_view_eight_us == "1"){
						echo"
						 <tr>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$l_per_eight_abbr_lowercase</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_energy_us</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_fat_us</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_carbohydrates_us</span>
						  </td>
						  <td style=\"text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_proteins_us</span>
						  </td>
						 </tr>
						";
					}
					if($get_current_view_pcs_us == "1"){
						echo"
						 <tr>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\" title=\"$get_food_serving_size_us $get_food_serving_size_measurement_us\">$get_food_serving_size_pcs $get_food_serving_size_pcs_measurement</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_energy_calculated_us</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
							<span class=\"nutritional_number\">$get_food_fat_calculated_us</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_carbohydrates_calculated_us</span>
						  </td>
						  <td style=\"text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
						<span class=\"nutritional_number\">$get_food_proteins_calculated_us</span>
						  </td>
						 </tr>
						";
					}
					echo"
						 <tr>
						  <td style=\"padding-right: 6px;text-align: center;\">
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;\">
							<span class=\"nutritional_number\">$l_calories_abbr_lowercase</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;\">
							<span class=\"nutritional_number\">$l_fat_abbr_lowercase</span>
						  </td>
						  <td style=\"padding-right: 6px;text-align: center;\">
							<span class=\"nutritional_number\">$l_carbohydrates_abbr_lowercase</span>
						  </td>
						  <td style=\"text-align: center;\">
							<span class=\"nutritional_number\">$l_proteins_abbr_lowercase</span>
						  </td>
						 </tr>
						</table>
					";
				}
				echo"
				<!-- If else two numbers -->
				
					<script>
					\$(document).ready(function(){
						\$(\"#food_click_action_gram_$get_food_id\").click(function () {
							var inpAmount = \$('#inp_item_amount').val().replace(',', '.');


							\$(\"#inp_item_measurement\").val(\"$get_food_serving_size_measurement_metric\");
							\$(\".inp_item_grocery\").val(\"$get_food_name\");
							\$(\"#inp_item_food_id\").val($get_food_id);

							\$(\"#inp_item_calories_metric\").val($get_food_energy_metric);
							\$(\"#inp_item_fat_metric\").val($get_food_fat_metric);
							\$(\"#inp_item_saturated_fat_metric\").val($get_food_saturated_fat_metric);\n";
							if($get_food_monounsaturated_fat_metric != ""){
								echo"							";
								echo"\$(\"#inp_item_monounsaturated_fat_metric\").val($get_food_monounsaturated_fat_metric);\n";
							}
							if($get_food_polyunsaturated_fat_metric != ""){
								echo"							";
								echo"\$(\"#inp_item_polyunsaturated_fat_metric\").val($get_food_polyunsaturated_fat_metric);\n";
							}
							echo"
							\$(\"#inp_item_carbohydrates_metric\").val($get_food_carbohydrates_metric);
							\$(\"#inp_item_carbohydrates_of_which_sugars_metric\").val($get_food_carbohydrates_of_which_sugars_metric);
							\$(\"#inp_item_dietary_fiber_metric\").val($get_food_dietary_fiber_metric);
							\$(\"#inp_item_proteins_metric\").val($get_food_proteins_metric);
							\$(\"#inp_item_salt_metric\").val($get_food_salt_metric);
							\$(\"#inp_item_sodium_metric\").val($get_food_sodium_metric);

							\$(\"#inp_item_calories_calculated\").val(($get_food_energy_metric * inpAmount)/100);
							\$(\"#inp_item_fat_calculated\").val(($get_food_fat_metric * inpAmount)/100);
							\$(\"#inp_item_saturated_fat_calculated\").val(($get_food_saturated_fat_metric * inpAmount)/100);\n";
							if($get_food_monounsaturated_fat_metric != ""){
								echo"							";
								echo"\$(\"#inp_item_monounsaturated_fat_calculated\").val(($get_food_monounsaturated_fat_metric * inpAmount)/100);\n";
							}
							if($get_food_polyunsaturated_fat_metric != ""){
								echo"							";
								echo"\$(\"#inp_item_polyunsaturated_fat_calculated\").val(($get_food_polyunsaturated_fat_metric * inpAmount)/100);\n";
							}
							echo"
							\$(\"#inp_item_carbohydrates_calculated\").val(($get_food_carbohydrates_metric * inpAmount)/100);
							\$(\"#inp_item_carbohydrates_of_which_sugars_calculated\").val(($get_food_dietary_fiber_metric * inpAmount)/100);
							\$(\"#inp_item_dietary_fiber_calculated\").val(($get_food_dietary_fiber_metric * inpAmount)/100);
							\$(\"#inp_item_proteins_calculated\").val(($get_food_proteins_metric * inpAmount)/100);
							\$(\"#inp_item_salt_calculated\").val(($get_food_salt_metric * inpAmount)/100);
							\$(\"#inp_item_sodium_calculated\").val(($get_food_sodium_metric * inpAmount)/100);

							$(\"#nettport_search_results\").hide();

						});

						\$(\"#food_click_action_pcs_$get_food_id\").click(function () {

							var inpAmount = \$('#inp_item_amount').val().replace(',', '.');


							\$(\"#inp_item_measurement\").val(\"$get_food_serving_size_pcs_measurement\");
							\$(\".inp_item_grocery\").val(\"$get_food_name\");
							\$(\"#inp_item_food_id\").val($get_food_id);
					
							\$(\"#inp_item_calories_metric\").val($get_food_energy_metric);
							\$(\"#inp_item_fat_metric\").val($get_food_fat_metric);
							\$(\"#inp_item_saturated_fat_metric\").val($get_food_saturated_fat_metric);\n";
							if($get_food_monounsaturated_fat_metric != ""){
								echo"							";
								echo"\$(\"#inp_item_monounsaturated_fat_metric\").val($get_food_monounsaturated_fat_metric);\n";
							}
							if($get_food_polyunsaturated_fat_metric != ""){
								echo"							";
								echo"\$(\"#inp_item_polyunsaturated_fat_metric\").val($get_food_polyunsaturated_fat_metric);\n";
							}
							echo"
							\$(\"#inp_item_carbohydrates_metric\").val($get_food_carbohydrates_metric);
							\$(\"#inp_item_carbohydrates_of_which_sugars_metric\").val($get_food_carbohydrates_of_which_sugars_metric);
							\$(\"#inp_item_dietary_fiber_metric\").val($get_food_dietary_fiber_metric);
							\$(\"#inp_item_proteins_metric\").val($get_food_proteins_metric);
							\$(\"#inp_item_salt_metric\").val($get_food_salt_metric);
							\$(\"#inp_item_sodium_metric\").val($get_food_sodium_metric);

							\$(\"#inp_item_calories_calculated\").val($get_food_energy_calculated_metric * inpAmount * (1/$get_food_serving_size_pcs));
							\$(\"#inp_item_fat_calculated\").val($get_food_fat_calculated_metric * inpAmount * (1/$get_food_serving_size_pcs));
							\$(\"#inp_item_saturated_fat_calculated\").val($get_food_saturated_fat_calculated_metric * inpAmount * (1/$get_food_serving_size_pcs));\n";
							if($get_food_monounsaturated_fat_calculated_metric != ""){
								echo"							";
								echo"\$(\"#inp_item_monounsaturated_fat_calculated\").val($get_food_monounsaturated_fat_calculated_metric * inpAmount * (1/$get_food_serving_size_pcs));\n";
							}
							if($get_food_polyunsaturated_fat_calculated_metric != ""){
								echo"							";
								echo"\$(\"#inp_item_polyunsaturated_fat_calculated\").val($get_food_polyunsaturated_fat_calculated_metric * inpAmount * (1/$get_food_serving_size_pcs));\n";
							}
							echo"
							\$(\"#inp_item_carbohydrates_calculated\").val($get_food_carbohydrates_calculated_metric * inpAmount * (1/$get_food_serving_size_pcs));
							\$(\"#inp_item_carbohydrates_of_which_sugars_calculated\").val($get_food_dietary_fiber_calculated_metric * inpAmount * (1/$get_food_serving_size_pcs));
							\$(\"#inp_item_dietary_fiber_calculated\").val($get_food_dietary_fiber_calculated_metric * inpAmount * (1/$get_food_serving_size_pcs));
							\$(\"#inp_item_proteins_calculated\").val($get_food_proteins_calculated_metric * inpAmount * (1/$get_food_serving_size_pcs));
							\$(\"#inp_item_salt_calculated\").val($get_food_salt_calculated_metric * inpAmount * (1/$get_food_serving_size_pcs));
							\$(\"#inp_item_sodium_calculated\").val($get_food_sodium_calculated_metric * inpAmount * (1/$get_food_serving_size_pcs));
				
							$(\"#nettport_search_results\").hide();

						});
					});

					</script>



				</div>
				";



				// Increment
				$x++;

		

				// Reset
				if($columns == "4"){
					if($x == 4){
						$x = 0;
					}
				}
				elseif($columns == "2"){
					if($x == 2){
						$x = 0;
					}
				}		
			} // food has image
		} // while

		if($columns == "4"){
			if($x == "2"){

				echo"

				<div class=\"left_center_center_right_right_center\" style=\"text-align: center;padding-bottom: 20px;\">

				</div>

				<div class=\"left_center_center_right_right\" style=\"text-align: center;padding-bottom: 20px;\">
	
				</div>";

			}
		} // columns == 4
	}

}

else{

	echo"No q";

}



echo"<div id=\"number_action\"></div>";

?>