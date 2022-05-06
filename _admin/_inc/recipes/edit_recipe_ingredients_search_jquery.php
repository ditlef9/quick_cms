<?php 
/**
*
* File: _admin/_inc/recipes/edit_recipe_ingredients_search_jquery.php
* Version 1.0.0
* Date 01:12 06.01.2021
* Copyright (c) 2021 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/



/*- Functions ------------------------------------------------------------------------ */
include("../../_functions/output_html.php");
include("../../_functions/clean.php");
include("../../_functions/quote_smart.php");
include("../../_functions/resize_crop_image.php");
include("../../_functions/get_extension.php");





/*- Common variables ----------------------------------------------------------------- */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);



/*- MySQL ------------------------------------------------------------ */
$check = substr($server_name, 0, 3);
if($check == "www"){
	$server_name = substr($server_name, 3);
}

$setup_finished_file = "setup_finished_" . $server_name . ".php";
if(!(file_exists("../../_data/$setup_finished_file"))){
	die;
}

else{
	include("../../_data/config/meta.php");
	include("../../_data/config/user_system.php");

}

$mysql_config_file = "../../_data/mysql_" . $server_name . ".php";
include("$mysql_config_file");
$link = mysqli_connect($mysqlHostSav, $mysqlUserNameSav, $mysqlPasswordSav, $mysqlDatabaseNameSav);
if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}







/*- MySQL Tables -------------------------------------------------------------------- */
$t_food_index		= $mysqlPrefixSav . "food_index";
$t_food_queries 	= $mysqlPrefixSav . "food_queries";
$t_recipes 		= $mysqlPrefixSav . "recipes";



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
if(file_exists("../../_translations/site/$l/food/ts_food.php")){
	include("../../_translations/site/$l/food/ts_food.php");
	include("../../_translations/site/$l/recipes/ts_submit_recipe_step_2_group_and_elements.php");
}
else{
	$l = "en";
	include("../../_translations/site/en/food/ts_food.php");
	include("../../_translations/site/en/recipes/ts_submit_recipe_step_2_group_and_elements.php");
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
		$query = "SELECT query_name, query_times FROM $t_food_queries WHERE query_name=$q_mysql";
		$res = mysqli_query($link, $query);
		$row = mysqli_fetch_row($res);
		$get_query_name = $row[0];
		$get_query_times = $row[1];

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
		$q = $q . "%";
		$q_mysql = quote_smart($link, $q);

		// Set layout
		$x = 0;

		// Query
		$query = "SELECT food_id, food_user_id, food_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_country, food_net_content, food_net_content_measurement, food_serving_size_gram, food_serving_size_gram_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_energy, food_proteins, food_carbohydrates, food_carbohydrates_of_which_dietary_fiber, food_carbohydrates_of_which_sugars, food_fat, food_fat_of_which_saturated_fatty_acids, food_salt, food_sodium, food_score, food_energy_calculated, food_proteins_calculated, food_salt_calculated, food_sodium_calculated, food_carbohydrates_calculated, food_carbohydrates_of_which_dietary_fiber_calculated, food_carbohydrates_of_which_sugars_calculated, food_fat_calculated, food_fat_of_which_saturated_fatty_acids_calculated, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small FROM $t_food_index";
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
			list($get_food_id, $get_food_user_id, $get_food_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_country, $get_food_net_content, $get_food_net_content_measurement, $get_food_serving_size_gram, $get_food_serving_size_gram_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_energy, $get_food_proteins, $get_food_carbohydrates, $get_food_carbohydrates_of_which_dietary_fiber, $get_food_carbohydrates_of_which_sugars, $get_food_fat, $get_food_fat_of_which_saturated_fatty_acids, $get_food_salt, $get_food_sodium, $get_food_score, $get_food_energy_calculated, $get_food_proteins_calculated, $get_food_salt_calculated, $get_food_sodium_calculated, $get_food_carbohydrates_calculated, $get_food_carbohydrates_of_which_dietary_fiber_calculated, $get_food_carbohydrates_of_which_sugars_calculated, $get_food_fat_calculated, $get_food_fat_of_which_saturated_fatty_acids_calculated, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small) = $row;

			if($get_food_image_a != "" && file_exists("../../../$get_food_image_path/$get_food_image_a")){

				if($get_food_fat_of_which_saturated_fatty_acids == ""){
					$get_food_fat_of_which_saturated_fatty_acids = "0";
				}
				if($get_food_carbohydrates_of_which_sugars == ""){
					$get_food_carbohydrates_of_which_sugars = "0";
				}
				if($get_food_fat_of_which_saturated_fatty_acids_calculated == ""){
					$get_food_fat_of_which_saturated_fatty_acids_calculated = "0";
				}
				if($get_food_carbohydrates_of_which_dietary_fiber == ""){
					$get_food_carbohydrates_of_which_dietary_fiber = "0";
				}
				if($get_food_carbohydrates_of_which_sugars_calculated == ""){
					$get_food_carbohydrates_of_which_sugars_calculated = "0";
				}
				if($get_food_salt == ""){
					$get_food_salt = "0";
				}
				if($get_food_salt_calculated == ""){
					$get_food_salt_calculated = "0";
				}
				if($get_food_carbohydrates_of_which_dietary_fiber_calculated == ""){
					$get_food_carbohydrates_of_which_dietary_fiber_calculated = "0";
				}
				if($get_food_sodium == ""){
					$get_food_sodium = "0";
				}
				if($get_food_sodium_calculated == ""){
					$get_food_sodium_calculated = "0";
				}

				// Name saying
				$title = "$get_food_manufacturer_name $get_food_name";
				$check = strlen($title);
				if($check > 35){
					$title = substr($title, 0, 35);
					$title = $title . "...";
				}



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

		


				echo"
				<p style=\"padding-bottom:6px;\">
				<a href=\"../food/view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\" class=\"_blank\"><img src=\"../$get_food_image_path/$get_food_thumb_a_small\" alt=\"$get_food_image_a\" style=\"margin-bottom: 5px;\" /></a><br />
				<a href=\"../food/view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\" class=\"_blank\">$title</a>
				</p>

				
				<p style=\"margin:0;padding: 0px 0px 5px 0px;\">\n";
				if($get_recipe_country != "United States"){
					echo"
					<a href=\"#\" id=\"food_click_action_gram_$get_food_id\" class=\"btn_default\">$get_food_serving_size_gram_measurement</a>
					";
				}

				if($get_food_serving_size_pcs_measurement != "$get_food_serving_size_gram_measurement"){
					echo"<a href=\"#\" id=\"food_click_action_pcs_$get_food_id\" class=\"btn_default\">$get_food_serving_size_pcs_measurement</a><br />\n";
				}
				echo"
				</p>\n";

				if($get_food_energy != "0" && $get_food_fat != "0" && $get_food_carbohydrates != "0" && $get_food_proteins != "0"){
					echo"
					<table style=\"margin: 0px auto;\">
					 <tr>
					  <td style=\"padding-right: 10px;text-align: center;\">
						<span class=\"grey_small\">$get_food_energy</span>
					  </td>
					  <td style=\"padding-right: 10px;text-align: center;\">
						<span class=\"grey_small\">$get_food_fat</span>
					  </td>
					  <td style=\"padding-right: 10px;text-align: center;\">
						<span class=\"grey_small\">$get_food_carbohydrates</span>
					  </td>
					  <td style=\"text-align: center;\">
						<span class=\"grey_small\">$get_food_proteins</span>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"padding-right: 10px;text-align: center;\">
						<span class=\"grey_small\">$l_cal_lowercase</span>
					  </td>
					  <td style=\"padding-right: 10px;text-align: center;\">
						<span class=\"grey_small\">$l_fat_lowercase</span>
					  </td>
					  <td style=\"padding-right: 10px;text-align: center;\">
						<span class=\"grey_small\">$l_carb_lowercase</span>
					  </td>
					  <td style=\"text-align: center;\">
						<span class=\"grey_small\">$l_proteins_lowercase</span>
					  </td>
					 </tr>
					</table>";
				}
				echo"

				<!-- If else two numbers -->
				";
				if($get_recipe_country == "United States"){
					// USA start
					echo"

					<script>
					\$(document).ready(function(){

						\$(\"#food_click_action_pcs_$get_food_id\").click(function () {

							var inpAmount = \$('#inp_item_amount').val().replace(',', '.');

							\$(\".inp_item_grocery\").val(\"$get_food_name\");
							\$(\"#inp_item_food_id\").val($get_food_id);
					

							\$(\"#inp_item_calories_calculated\").val(($get_food_energy_calculated / $get_food_serving_size_pcs) * inpAmount);
							\$(\"#inp_item_fat_calculated\").val(($get_food_fat_calculated / $get_food_serving_size_pcs) *  inpAmount);
							\$(\"#inp_item_fat_of_which_saturated_fatty_acids_calculated\").val(($get_food_fat_of_which_saturated_fatty_acids_calculated / $get_food_serving_size_pcs) *  inpAmount);
							\$(\"#inp_item_carbs_calculated\").val(($get_food_carbohydrates_calculated / $get_food_serving_size_pcs) *  inpAmount);
							\$(\"#inp_item_carbs_of_which_dietary_fiber_calculated\").val(($get_food_carbohydrates_of_which_dietary_fiber_calculated / $get_food_serving_size_pcs) *  inpAmount);
							\$(\"#inp_item_carbs_of_which_sugars_calculated\").val(($get_food_carbohydrates_of_which_sugars_calculated / $get_food_serving_size_pcs) *  inpAmount);
							\$(\"#inp_item_proteins_calculated\").val(($get_food_proteins_calculated / $get_food_serving_size_pcs) *  inpAmount);
							\$(\"#inp_item_sodium_calculated\").val(($get_food_sodium_calculated / $get_food_serving_size_pcs) *  inpAmount);
				
							$(\"#nettport_search_results\").hide();

						});
					});

					</script>
					";
				}
				else{
					echo"

					<script>
					\$(document).ready(function(){
						\$(\"#food_click_action_gram_$get_food_id\").click(function () {
							var inpAmount = \$('#inp_item_amount').val().replace(',', '.');

						
							\$(\".inp_item_grocery\").val(\"$get_food_name\");
							\$(\"#inp_item_food_id\").val($get_food_id);
				
							\$(\"#inp_item_calories_per_hundred\").val($get_food_energy);
							\$(\"#inp_item_fat_per_hundred\").val($get_food_fat);
							\$(\"#inp_item_fat_of_which_saturated_fatty_acids_per_hundred\").val($get_food_fat_of_which_saturated_fatty_acids);
							\$(\"#inp_item_carbs_per_hundred\").val($get_food_carbohydrates);
							\$(\"#inp_item_carbs_of_which_dietary_fiber_calculated\").val($get_food_carbohydrates_of_which_dietary_fiber);
							\$(\"#inp_item_carbs_of_which_sugars_per_hundred\").val($get_food_carbohydrates_of_which_sugars);
							\$(\"#inp_item_proteins_per_hundred\").val($get_food_proteins);
							\$(\"#inp_item_salt_per_hundred\").val($get_food_salt);

							\$(\"#inp_item_calories_calculated\").val(($get_food_energy * inpAmount)/100);
							\$(\"#inp_item_fat_calculated\").val(($get_food_fat * inpAmount)/100);
							\$(\"#inp_item_fat_of_which_saturated_fatty_acids_calculated\").val(($get_food_fat_of_which_saturated_fatty_acids * inpAmount)/100);
							\$(\"#inp_item_carbs_calculated\").val(($get_food_carbohydrates * inpAmount)/100);
							\$(\"#inp_item_carbs_of_which_dietary_fiber_calculated\").val(($get_food_carbohydrates_of_which_dietary_fiber * inpAmount)/100);
							\$(\"#inp_item_carbs_of_which_sugars_calculated\").val(($get_food_carbohydrates_of_which_sugars * inpAmount)/100);
							\$(\"#inp_item_proteins_calculated\").val(($get_food_proteins * inpAmount)/100);
							\$(\"#inp_item_salt_calculated\").val(($get_food_salt * inpAmount)/100);
				
							$(\"#nettport_search_results\").hide();

						});

						\$(\"#food_click_action_pcs_$get_food_id\").click(function () {

							var inpAmount = \$('#inp_item_amount').val().replace(',', '.');

							\$(\".inp_item_grocery\").val(\"$get_food_name\");
							\$(\"#inp_item_food_id\").val($get_food_id);
					
							\$(\"#inp_item_calories_per_hundred\").val($get_food_energy);
							\$(\"#inp_item_fat_per_hundred\").val($get_food_fat);
							\$(\"#inp_item_fat_of_which_saturated_fatty_acids_per_hundred\").val($get_food_fat_of_which_saturated_fatty_acids);
							\$(\"#inp_item_carbs_per_hundred\").val($get_food_carbohydrates);
							\$(\"#inp_item_carbs_of_which_dietary_fiber_per_hundred\").val($get_food_carbohydrates_of_which_dietary_fiber);
							\$(\"#inp_item_carbs_of_which_sugars_per_hundred\").val($get_food_carbohydrates_of_which_sugars);
							\$(\"#inp_item_proteins_per_hundred\").val($get_food_proteins);
							\$(\"#inp_item_salt_per_hundred\").val($get_food_salt);

							\$(\"#inp_item_calories_calculated\").val($get_food_energy_calculated * inpAmount);
							\$(\"#inp_item_fat_calculated\").val($get_food_fat_calculated * inpAmount);
							\$(\"#inp_item_fat_of_which_saturated_fatty_acids_calculated\").val($get_food_fat_of_which_saturated_fatty_acids_calculated * inpAmount);
							\$(\"#inp_item_carbs_calculated\").val($get_food_carbohydrates_calculated * inpAmount);
							\$(\"#inp_item_carbs_of_which_dietary_fiber_calculated\").val($get_food_carbohydrates_of_which_dietary_fiber_calculated * inpAmount);
							\$(\"#inp_item_carbs_of_which_sugars_calculated\").val($get_food_carbohydrates_of_which_sugars_calculated * inpAmount);
							\$(\"#inp_item_proteins_calculated\").val($get_food_proteins_calculated * inpAmount);
							\$(\"#inp_item_salt_calculated\").val($get_food_salt_calculated * inpAmount);
				
							$(\"#nettport_search_results\").hide();

						});
					});

					</script>
					";
				}
				echo"
				<!-- //If else two numbers -->



				</div>
				";



				// Increment
				$x++;

		

				// Reset
				if($x == 4){
					$x = 0;
				}

		
			} // food has image
		} // while

		if($x == "2"){

			echo"

			<div class=\"left_center_center_right_right_center\" style=\"text-align: center;padding-bottom: 20px;\">

			</div>

			<div class=\"left_center_center_right_right\" style=\"text-align: center;padding-bottom: 20px;\">

			</div>";

		}

	}

}
else{

	echo"No q";
}



echo"<div id=\"number_action\"></div>";

?>