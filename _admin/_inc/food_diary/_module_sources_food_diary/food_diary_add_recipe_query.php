<?php 
error_reporting(E_ALL & ~E_STRICT);
@session_start();
ini_set('arg_separator.output', '&amp;');
/**
*
* File: food_diary/food_diary_add_recipe_query.php
* Version 1.0.0
* Date 15:38 21.01.2018
* Copyright (c) 2018 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/




/*- Functions ------------------------------------------------------------------------ */
include("../_admin/_functions/output_html.php");
include("../_admin/_functions/clean.php");
include("../_admin/_functions/quote_smart.php");
include("../_admin/_functions/resize_crop_image.php");


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
/*- Tables --------------------------------------------------------------------------- */
include("_tables.php");

/*- Variables ------------------------------------------------------------------------- */
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



if(isset($_GET['date']) OR isset($_POST['date'])) {
	if(isset($_GET['date'])){
		$date = $_GET['date'];
	}
	else{
		$date = $_POST['date'];
	}
	$date = output_html($date);
}
else{
	echo"Missing meal plan id";
	die;
}
if(isset($_GET['hour_name']) OR isset($_POST['hour_name'])) {
	if(isset($_GET['hour_name'])){
		$hour_name = $_GET['hour_name'];
	}
	else{
		$hour_name = $_POST['hour_name'];
	}
	
	$hour_name = stripslashes(strip_tags($hour_name));
	if($hour_name != "breakfast" && $hour_name != "lunch" && $hour_name != "before_training" && $hour_name != "after_training" && $hour_name != "linner" && $hour_name != "dinner" && $hour_name != "snacks" && $hour_name != "supper" && $hour_name != "night_meal"){
		echo"Unknown hour name";
		die;
	}
}
else{
	echo"Missing hour name";
	die;
}

/*- Language ------------------------------------------------------------------------ */
include("../_admin/_translations/site/$l/food/ts_index.php");
include("../_admin/_translations/site/$l/food_diary/ts_food_diary_add.php");
include("../_admin/_translations/site/$l/food_diary/ts_food_diary_add_recipe.php");



/*- User adapted view ---------------------------------------------------------------- */
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
			
	$query_t = "SELECT view_id, view_user_id, view_system, view_hundred_metric, view_serving, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_food_diary_user_adapted_view WHERE view_user_id=$my_user_id_mysql";
	$result_t = mysqli_query($link, $query_t);
	$row_t = mysqli_fetch_row($result_t);
	list($get_current_view_id, $get_current_view_user_id, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_serving, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
}
else{
	echo"<div class=\"warning\"><p>Your not logged in</p></div>";
}
/*- Query --------------------------------------------------------------------------- */
if(isset($_GET['q']) OR isset($_POST['q'])){
	if(isset($_GET['q'])) {
		$q = $_GET['q'];
	}
	else{
		$q = $_POST['q'];
	}
	$q = utf8_decode($q);
	$q = trim($q);
	$q = strtolower($q);
	$inp_datetime = date("Y-m-d H:i:s");
	$q = output_html($q);
	$q_mysql = quote_smart($link, $q);



	if($q != ""){

		echo"
		<!-- Select list go to URL -->
			<script>
			\$(document).ready(function(){
				\$(\"select\").bind('change',function(){
					window.location = \$(':selected',this).attr('href'); // redirect
				})
			});
			</script>
		<!-- //Select list go to URL -->

		";

		// Ready for MySQL search
		$q = "%" . $q . "%";
		$q_mysql = quote_smart($link, $q);


		// Set layout
		$x = 0;

		// Query
		$query = "SELECT recipe_id, recipe_title, recipe_introduction, recipe_image_path, recipe_image, recipe_thumb_278x156 FROM $t_recipes WHERE recipe_language=$l_mysql AND recipe_title LIKE $q_mysql";
		$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_recipe_id, $get_recipe_title, $get_recipe_introduction, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb_278x156) = $row;

					// Select Nutrients
					$query_n = "SELECT number_id, number_recipe_id, number_servings, number_energy_metric, number_fat_metric, number_saturated_fat_metric, number_monounsaturated_fat_metric, number_polyunsaturated_fat_metric, number_cholesterol_metric, number_carbohydrates_metric, number_carbohydrates_of_which_sugars_metric, number_dietary_fiber_metric, number_proteins_metric, number_salt_metric, number_sodium_metric, number_energy_serving, number_fat_serving, number_saturated_fat_serving, number_monounsaturated_fat_serving, number_polyunsaturated_fat_serving, number_cholesterol_serving, number_carbohydrates_serving, number_carbohydrates_of_which_sugars_serving, number_dietary_fiber_serving, number_proteins_serving, number_salt_serving, number_sodium_serving, number_energy_total, number_fat_total, number_saturated_fat_total, number_monounsaturated_fat_total, number_polyunsaturated_fat_total, number_cholesterol_total, number_carbohydrates_total, number_carbohydrates_of_which_sugars_total, number_dietary_fiber_total, number_proteins_total, number_salt_total, number_sodium_total FROM $t_recipes_numbers WHERE number_recipe_id=$get_recipe_id";
					$result_n = mysqli_query($link, $query_n);
					$row_n = mysqli_fetch_row($result_n);
					list($get_number_id, $get_number_recipe_id, $get_number_servings, $get_number_energy_metric, $get_number_fat_metric, $get_number_saturated_fat_metric, $get_number_monounsaturated_fat_metric, $get_number_polyunsaturated_fat_metric, $get_number_cholesterol_metric, $get_number_carbohydrates_metric, $get_number_carbohydrates_of_which_sugars_metric, $get_number_dietary_fiber_metric, $get_number_proteins_metric, $get_number_salt_metric, $get_number_sodium_metric, $get_number_energy_serving, $get_number_fat_serving, $get_number_saturated_fat_serving, $get_number_monounsaturated_fat_serving, $get_number_polyunsaturated_fat_serving, $get_number_cholesterol_serving, $get_number_carbohydrates_serving, $get_number_carbohydrates_of_which_sugars_serving, $get_number_dietary_fiber_serving, $get_number_proteins_serving, $get_number_salt_serving, $get_number_sodium_serving, $get_number_energy_total, $get_number_fat_total, $get_number_saturated_fat_total, $get_number_monounsaturated_fat_total, $get_number_polyunsaturated_fat_total, $get_number_cholesterol_total, $get_number_carbohydrates_total, $get_number_carbohydrates_of_which_sugars_total, $get_number_dietary_fiber_total, $get_number_proteins_total, $get_number_salt_total, $get_number_sodium_total) = $row_n;


					if($get_recipe_image != "" && file_exists("../$get_recipe_image_path/$get_recipe_image")){

						// Thumb
						if(!(file_exists("../$get_recipe_image_path/$get_recipe_thumb_278x156"))){
							if($get_recipe_thumb_278x156 == ""){
								echo"<div class=\"info\">Thumb 278x156 is blank</div>";
								die;
							}
							$inp_new_x = 278;
							$inp_new_y = 156;
							resize_crop_image($inp_new_x, $inp_new_y, "../$get_recipe_image_path/$get_recipe_image", "../$get_recipe_image_path/$get_recipe_thumb_278x156");
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
						<p style=\"padding-bottom:5px;\">
						<a href=\"../recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\"><img src=\"../$get_recipe_image_path/$get_recipe_thumb_278x156\" alt=\"$get_recipe_image\" style=\"margin-bottom: 5px;\" /></a><br />
						<a href=\"../recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\" style=\"font-weight: bold;color: #444444;\">$get_recipe_title</a><br />
						</p>


						<!-- Recipe numbers -->
							";
							if($get_current_view_hundred_metric == "1" OR $get_current_view_serving == "1"){
				
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
										<span class=\"nutritional_number\">$get_number_energy_metric</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;\">
										<span class=\"nutritional_number\">$get_number_fat_metric</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;\">
										<span class=\"nutritional_number\">$get_number_carbohydrates_metric</span>
									  </td>
									  <td style=\"text-align: center;\">
										<span class=\"nutritional_number\">$get_number_proteins_metric</span>
									  </td>
									 </tr>
									";
								}
								if($get_current_view_serving == "1"){
									echo"
									 <tr>
									  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">$l_serving</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">$get_number_energy_serving</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">$get_number_fat_serving</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">$get_number_carbohydrates_serving</span>
									  </td>
									  <td style=\"text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
										<span class=\"nutritional_number\">$get_number_proteins_serving</span>
									  </td>
									 </tr>
									";
								}
								echo"
									 <tr>
									  <td style=\"padding-right: 6px;text-align: center;\">
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;\">
										<span class=\"nutritional_number\">$l_calories_abbr_short_lowercase</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;\">
										<span class=\"nutritional_number\">$l_fat_abbr_short_lowercase</span>
									  </td>
									  <td style=\"padding-right: 6px;text-align: center;\">
										<span class=\"nutritional_number\">$l_carbohydrates_abbr_short_lowercase</span>
									  </td>
									  <td style=\"text-align: center;\">
										<span class=\"nutritional_number\">$l_proteins_abbr_short_lowercase</span>
									  </td>
									 </tr>
									</table>
								";
							} // show numbers
							echo"
							<!-- //Recipe numbers -->
							<!-- Add Recipe -->
							<form>
							<p>
							<select classs=\"inp_amount_select\">
								<option value=\"1\" href=\"food_diary_add_recipe.php?action=add_recipe_to_diary&amp;date=$date&amp;hour_name=$hour_name&amp;recipe_id=$get_recipe_id&amp;entry_serving_size=1&amp;l=$l&amp;process=1\">1</option>
								<option value=\"2\" href=\"food_diary_add_recipe.php?action=add_recipe_to_diary&amp;date=$date&amp;hour_name=$hour_name&amp;recipe_id=$get_recipe_id&amp;entry_serving_size=2&amp;l=$l&amp;process=1\">2</option>
								<option value=\"3\" href=\"food_diary_add_recipe.php?action=add_recipe_to_diary&amp;date=$date&amp;hour_name=$hour_name&amp;recipe_id=$get_recipe_id&amp;entry_serving_size=3&amp;l=$l&amp;process=1\">3</option>
								<option value=\"4\" href=\"food_diary_add_recipe.php?action=add_recipe_to_diary&amp;date=$date&amp;hour_name=$hour_name&amp;recipe_id=$get_recipe_id&amp;entry_serving_size=4&amp;l=$l&amp;process=1\">4</option>
								<option value=\"5\" href=\"food_diary_add_recipe.php?action=add_recipe_to_diary&amp;date=$date&amp;hour_name=$hour_name&amp;recipe_id=$get_recipe_id&amp;entry_serving_size=5&amp;l=$l&amp;process=1\">5</option>
								<option value=\"6\" href=\"food_diary_add_recipe.php?action=add_recipe_to_diary&amp;date=$date&amp;hour_name=$hour_name&amp;recipe_id=$get_recipe_id&amp;entry_serving_size=6&amp;l=$l&amp;process=1\">6</option>
								<option value=\"7\" href=\"food_diary_add_recipe.php?action=add_recipe_to_diary&amp;date=$date&amp;hour_name=$hour_name&amp;recipe_id=$get_recipe_id&amp;entry_serving_size=7&amp;l=$l&amp;process=1\">7</option>
								<option value=\"8\" href=\"food_diary_add_recipe.php?action=add_recipe_to_diary&amp;date=$date&amp;hour_name=$hour_name&amp;recipe_id=$get_recipe_id&amp;entry_serving_size=8&amp;l=$l&amp;process=1\">8</option>
							</select>
							<a href=\"food_diary_add_recipe.php?action=add_recipe_to_diary&amp;date=$date&amp;hour_name=$hour_name&amp;recipe_id=$get_recipe_id&amp;entry_serving_size=1&amp;l=$l&amp;process=1\" class=\"btn btn_default\">$l_add</a>
							</p>
							</form>
							<!-- //Add Recipe -->

						</div>
						";
						// Increment
						$x++;
			
						// Reset
						if($x == 4){
							$x = 0;
						}
					} // has image
				} // while
	}
	else{
		echo"Q is blank";
	}
}
else{
	echo"No q";
}


?>