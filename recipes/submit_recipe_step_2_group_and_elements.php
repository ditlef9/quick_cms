<?php 
/**
*
* File: recipes/my_recipes.php
* Version 1.0.0
* Date 23:59 27.11.2017
* Copyright (c) 2011-2017 Localhost
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

/*- Tables ---------------------------------------------------------------------------- */
include("_tables.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/recipes/ts_index.php");


/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['recipe_id'])){
	$recipe_id = $_GET['recipe_id'];
	$recipe_id = output_html($recipe_id);
}
else{
	$recipe_id = "";
}
if(isset($_GET['group_id'])){
	$group_id = $_GET['group_id'];
	$group_id = output_html($group_id);
}
else{
	$group_id = "";
}
if(isset($_GET['item_id'])){
	$item_id = $_GET['item_id'];
	$item_id = output_html($item_id);
}
else{
	$item_id = "";
}
$tabindex = 0;
$l_mysql = quote_smart($link, $l);

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_submit_recipe - $l_recipes";
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	

// Get recipe
$recipe_id_mysql = quote_smart($link, $recipe_id);

	$inp_recipe_user_id = $_SESSION['user_id'];
	$inp_recipe_user_id = output_html($inp_recipe_user_id);
	$inp_recipe_user_id_mysql = quote_smart($link, $inp_recipe_user_id);

$query = "SELECT recipe_id, recipe_country, recipe_directions FROM $t_recipes WHERE recipe_user_id=$inp_recipe_user_id_mysql AND recipe_id=$recipe_id_mysql";
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
}
else{
	// Get numbers
	$query = "SELECT number_id, number_recipe_id, number_servings, number_energy_metric, number_fat_metric, number_saturated_fat_metric, number_monounsaturated_fat_metric, number_polyunsaturated_fat_metric, number_cholesterol_metric, number_carbohydrates_metric, number_carbohydrates_of_which_sugars_metric, number_dietary_fiber_metric, number_proteins_metric, number_salt_metric, number_sodium_metric, number_energy_serving, number_fat_serving, number_saturated_fat_serving, number_monounsaturated_fat_serving, number_polyunsaturated_fat_serving, number_cholesterol_serving, number_carbohydrates_serving, number_carbohydrates_of_which_sugars_serving, number_dietary_fiber_serving, number_proteins_serving, number_salt_serving, number_sodium_serving, number_energy_total, number_fat_total, number_saturated_fat_total, number_monounsaturated_fat_total, number_polyunsaturated_fat_total, number_cholesterol_total, number_carbohydrates_total, number_carbohydrates_of_which_sugars_total, number_dietary_fiber_total, number_proteins_total, number_salt_total, number_sodium_total FROM $t_recipes_numbers WHERE number_recipe_id=$recipe_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_number_id, $get_number_recipe_id, $get_number_servings, $get_number_energy_metric, $get_number_fat_metric, $get_number_saturated_fat_metric, $get_number_monounsaturated_fat_metric, $get_number_polyunsaturated_fat_metric, $get_number_cholesterol_metric, $get_number_carbohydrates_metric, $get_number_carbohydrates_of_which_sugars_metric, $get_number_dietary_fiber_metric, $get_number_proteins_metric, $get_number_salt_metric, $get_number_sodium_metric, $get_number_energy_serving, $get_number_fat_serving, $get_number_saturated_fat_serving, $get_number_monounsaturated_fat_serving, $get_number_polyunsaturated_fat_serving, $get_number_cholesterol_serving, $get_number_carbohydrates_serving, $get_number_carbohydrates_of_which_sugars_serving, $get_number_dietary_fiber_serving, $get_number_proteins_serving, $get_number_salt_serving, $get_number_sodium_serving, $get_number_energy_total, $get_number_fat_total, $get_number_saturated_fat_total, $get_number_monounsaturated_fat_total, $get_number_polyunsaturated_fat_total, $get_number_cholesterol_total, $get_number_carbohydrates_total, $get_number_carbohydrates_of_which_sugars_total, $get_number_dietary_fiber_total, $get_number_proteins_total, $get_number_salt_total, $get_number_sodium_total) = $row;
	if($get_number_id == ""){
		mysqli_query($link, "INSERT INTO $t_recipes_numbers
		(number_id, number_recipe_id, number_servings) 
		VALUES 
		(NULL, '$get_recipe_id', '1')")
		or die(mysqli_error($link));
	}


	if($action == ""){
		if($process == 1){
			$inp_group_title = $_POST['inp_group_title'];
			$inp_group_title = output_html($inp_group_title);
			$inp_group_title_mysql = quote_smart($link, $inp_group_title);

			if(empty($inp_group_title)){
				$ft = "error";
				$fm = "group_title_cant_be_empty";
				$url = "submit_recipe_step_2_group_and_elements.php?recipe_id=$get_recipe_id&l=$l&ft=$ft&fm=$fm";
				header("Location: $url");
				exit;
			}
			
			// Does that group already exists?
			$query = "SELECT group_id, group_recipe_id, group_title FROM $t_recipes_groups WHERE group_recipe_id=$get_recipe_id AND group_title=$inp_group_title_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_group_id, $get_group_recipe_id, $get_group_title) = $row;

			if($get_group_id != ""){
				$ft = "error";
				$fm = "you_already_have_a_group_with_that_name";
				$url = "submit_recipe_step_2_group_and_elements.php?recipe_id=$get_recipe_id&l=$l&ft=$ft&fm=$fm";
				header("Location: $url");
				exit;
			}


			// Insert
			mysqli_query($link, "INSERT INTO $t_recipes_groups
			(group_id, group_recipe_id, group_title) 
			VALUES 
			(NULL, '$get_recipe_id', $inp_group_title_mysql)")
			or die(mysqli_error($link));
			
			// Get group ID
			$query = "SELECT group_id, group_recipe_id, group_title FROM $t_recipes_groups WHERE group_recipe_id=$get_recipe_id AND group_title=$inp_group_title_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_group_id, $get_group_recipe_id, $get_group_title) = $row;


			// Header
			$url = "submit_recipe_step_2_group_and_elements.php?recipe_id=$get_recipe_id&action=add_items&group_id=$get_group_id&l=$l";
			header("Location: $url");
			exit;
		}


		echo"
		<h1>$l_add_another_group</h1>
	
		

		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_group_title\"]').focus();
			});
			</script>
		<!-- //Focus -->

		<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				elseif($fm == "group_title_cant_be_empty"){
					$fm = "$l_group_title_cant_be_empty";
				}
				elseif($fm == "you_already_have_a_group_with_that_name"){
					$fm = "$l_you_already_have_a_group_with_that_name";
				}
				else{
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->

		<!-- Add group -->

			<form method=\"post\" action=\"submit_recipe_step_2_group_and_elements.php?recipe_id=$get_recipe_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			

			<p><b>$l_ingredients_title:</b>
			<input type=\"text\" name=\"inp_group_title\" size=\"30\" value=\"\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			
			<input type=\"submit\" value=\"$l_create\" class=\"btn\" />
			</p>
			</form>
		<!-- //Add group -->


		<p>
		<a href=\"submit_recipe_step_3_main_ingredient.php?recipe_id=$get_recipe_id&amp;l=$l\">$l_continue</a>
		</p>
		";
	} 
	elseif($action == "add_items"){
		// Get group
		$group_id_mysql = quote_smart($link, $group_id);
		$query = "SELECT group_id, group_recipe_id, group_title FROM $t_recipes_groups WHERE group_id=$group_id_mysql AND group_recipe_id=$get_recipe_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_group_id, $get_group_recipe_id, $get_group_title) = $row;

		if($get_group_id == ""){
			echo"
			<h1>Server error</h1>

			<p>
			Group not found.
			</p>
			";
		}
		else{
			if($process == "1"){
				$inp_item_amount = $_POST['inp_item_amount'];
				$inp_item_amount = output_html($inp_item_amount);
				$inp_item_amount = str_replace(",", ".", $inp_item_amount);
				$inp_item_amount_mysql = quote_smart($link, $inp_item_amount);
				if(empty($inp_item_amount)){
					$ft = "error";
					$fm = "amound_cant_be_empty";
				}
				else{
					if(!(is_numeric($inp_item_amount))){
						// Do we have math? Example 1/8 ts
						$check_for_fraction = explode("/", $inp_item_amount);

						if(isset($check_for_fraction[0]) && isset($check_for_fraction[1])){
							if(is_numeric($check_for_fraction[0]) && is_numeric($check_for_fraction[1])){
								$inp_item_amount = $check_for_fraction[0] / $check_for_fraction[1];
							}
							else{
								$ft = "error";
								$fm = "amound_has_to_be_a_number";
							}
						}
						else{
							$ft = "error";
							$fm = "amound_has_to_be_a_number";
						}
					}
				}

				$inp_item_measurement = $_POST['inp_item_measurement'];
				$inp_item_measurement = output_html($inp_item_measurement);
				$inp_item_measurement = str_replace(",", ".", $inp_item_measurement);
				$inp_item_measurement_mysql = quote_smart($link, $inp_item_measurement);
				if(empty($inp_item_measurement)){
					$ft = "error";
					$fm = "measurement_cant_be_empty";
				}

				$inp_item_grocery = $_POST['inp_item_grocery'];
				$inp_item_grocery = output_html($inp_item_grocery);
				$inp_item_grocery_mysql = quote_smart($link, $inp_item_grocery);
				if(empty($inp_item_grocery)){
					$ft = "error";
					$fm = "grocery_cant_be_empty";
				}


				$inp_item_food_id = $_POST['inp_item_food_id'];
				$inp_item_food_id = output_html($inp_item_food_id);
				if($inp_item_food_id == ""){
					$inp_item_food_id = "0";
				}
				$inp_item_food_id_mysql = quote_smart($link, $inp_item_food_id);


				// Calories
				if(isset($_POST['inp_item_calories_metric'])){
					$inp_item_calories_metric = $_POST['inp_item_calories_metric'];
				}
				else{
					$inp_item_calories_metric = "0";
				}
				$inp_item_calories_metric = output_html($inp_item_calories_metric);
				$inp_item_calories_metric = str_replace(",", ".", $inp_item_calories_metric);
				if(empty($inp_item_calories_metric)){
					$inp_item_calories_metric = "0";
				}
				else{
					if(!(is_numeric($inp_item_calories_metric))){
						$ft = "error";
						$fm = "calories_have_to_be_a_number";
					}
				}
				$inp_item_calories_metric = round($inp_item_calories_metric, 0);
				$inp_item_calories_metric_mysql = quote_smart($link, $inp_item_calories_metric);


				$inp_item_calories_calculated = $_POST['inp_item_calories_calculated'];
				$inp_item_calories_calculated = output_html($inp_item_calories_calculated);
				$inp_item_calories_calculated = str_replace(",", ".", $inp_item_calories_calculated);
				if(empty($inp_item_calories_calculated)){
					$inp_item_calories_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_calories_calculated))){
						$ft = "error";
						$fm = "calories_have_to_be_a_number";
					}
				}
				$inp_item_calories_calculated = round($inp_item_calories_calculated, 0);
				$inp_item_calories_calculated_mysql = quote_smart($link, $inp_item_calories_calculated);

				// Fat
				if(isset($_POST['inp_item_fat_metric'])){
					$inp_item_fat_metric = $_POST['inp_item_fat_metric'];
				}
				else{
					$inp_item_fat_metric = "0";
				}
				$inp_item_fat_metric = output_html($inp_item_fat_metric);
				$inp_item_fat_metric = str_replace(",", ".", $inp_item_fat_metric);
				if(empty($inp_item_fat_metric)){
					$inp_item_fat_metric = "0";
				}
				else{
					if(!(is_numeric($inp_item_calories_metric))){
						$ft = "error";
						$fm = "fat_have_to_be_a_number";
					}
				}
				$inp_item_fat_metric = round($inp_item_fat_metric, 0);
				$inp_item_fat_metric_mysql = quote_smart($link, $inp_item_fat_metric);

				$inp_item_fat_calculated = $_POST['inp_item_fat_calculated'];
				$inp_item_fat_calculated = output_html($inp_item_fat_calculated);
				$inp_item_fat_calculated = str_replace(",", ".", $inp_item_fat_calculated);
				if(empty($inp_item_fat_calculated)){
					$inp_item_fat_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_calories_calculated))){
						$ft = "error";
						$fm = "fat_have_to_be_a_number";
					}
				}
				$inp_item_fat_calculated = round($inp_item_fat_calculated, 0);
				$inp_item_fat_calculated_mysql = quote_smart($link, $inp_item_fat_calculated);


				// Saturated fat
				if(isset($_POST['inp_item_saturated_fat_metric'])){
					$inp_item_saturated_fat_metric = $_POST['inp_item_saturated_fat_metric'];
				}
				else{
					$inp_item_saturated_fat_metric = "0";
				}
				$inp_item_saturated_fat_metric = output_html($inp_item_saturated_fat_metric);
				$inp_item_saturated_fat_metric = str_replace(",", ".", $inp_item_saturated_fat_metric);
				if(empty($inp_item_saturated_fat_metric)){
					$inp_item_saturated_fat_metric = "0";
				}
				else{
					if(!(is_numeric($inp_item_saturated_fat_metric))){
						$ft = "error";
						$fm = "saturated_fat_metric_have_to_be_a_number";
					}
				}
				$inp_item_saturated_fat_metric = round($inp_item_saturated_fat_metric, 0);
				$inp_item_saturated_fat_metric_mysql = quote_smart($link, $inp_item_saturated_fat_metric);


				$inp_item_saturated_fat_calculated = $_POST['inp_item_saturated_fat_calculated'];
				$inp_item_saturated_fat_calculated = output_html($inp_item_saturated_fat_calculated);
				$inp_item_saturated_fat_calculated = str_replace(",", ".", $inp_item_saturated_fat_calculated);
				if(empty($inp_item_saturated_fat_calculated)){
					$inp_item_saturated_fat_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_saturated_fat_calculated))){
						$ft = "error";
						$fm = "fat_of_which_saturated_fatty_acids_calculated_have_to_be_a_number";
					}
				}
				$inp_item_saturated_fat_calculated = round($inp_item_saturated_fat_calculated, 0);
				$inp_item_saturated_fat_calculated_mysql = quote_smart($link, $inp_item_saturated_fat_calculated);


				// Monounsaturated fat
				if(isset($_POST['inp_item_monounsaturated_fat_metric'])){
					$inp_item_monounsaturated_fat_metric = $_POST['inp_item_monounsaturated_fat_metric'];
				}
				else{
					$inp_item_monounsaturated_fat_metric = "0";
				}
				$inp_item_monounsaturated_fat_metric = output_html($inp_item_monounsaturated_fat_metric);
				$inp_item_monounsaturated_fat_metric = str_replace(",", ".", $inp_item_monounsaturated_fat_metric);
				if(empty($inp_item_monounsaturated_fat_metric)){
					$inp_item_monounsaturated_fat_metric = "0";
				}
				else{
					if(!(is_numeric($inp_item_monounsaturated_fat_metric))){
						$ft = "error";
						$fm = "monounsaturated_fat_metric_have_to_be_a_number";
					}
				}
				$inp_item_monounsaturated_fat_metric = round($inp_item_monounsaturated_fat_metric, 0);
				$inp_item_monounsaturated_fat_metric_mysql = quote_smart($link, $inp_item_monounsaturated_fat_metric);


				$inp_item_monounsaturated_fat_calculated = $_POST['inp_item_monounsaturated_fat_calculated'];
				$inp_item_monounsaturated_fat_calculated = output_html($inp_item_monounsaturated_fat_calculated);
				$inp_item_monounsaturated_fat_calculated = str_replace(",", ".", $inp_item_monounsaturated_fat_calculated);
				if(empty($inp_item_monounsaturated_fat_calculated)){
					$inp_item_monounsaturated_fat_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_monounsaturated_fat_calculated))){
						$ft = "error";
						$fm = "fat_of_which_monounsaturated_fatty_acids_calculated_have_to_be_a_number";
					}
				}
				$inp_item_monounsaturated_fat_calculated = round($inp_item_monounsaturated_fat_calculated, 0);
				$inp_item_monounsaturated_fat_calculated_mysql = quote_smart($link, $inp_item_monounsaturated_fat_calculated);


				// Saturated fat
				if(isset($_POST['inp_item_polyunsaturated_fat_metric'])){
					$inp_item_polyunsaturated_fat_metric = $_POST['inp_item_polyunsaturated_fat_metric'];
				}
				else{
					$inp_item_polyunsaturated_fat_metric = "0";
				}
				$inp_item_polyunsaturated_fat_metric = output_html($inp_item_polyunsaturated_fat_metric);
				$inp_item_polyunsaturated_fat_metric = str_replace(",", ".", $inp_item_polyunsaturated_fat_metric);
				if(empty($inp_item_polyunsaturated_fat_metric)){
					$inp_item_polyunsaturated_fat_metric = "0";
				}
				else{
					if(!(is_numeric($inp_item_polyunsaturated_fat_metric))){
						$ft = "error";
						$fm = "polyunsaturated_fat_metric_have_to_be_a_number";
					}
				}
				$inp_item_polyunsaturated_fat_metric = round($inp_item_polyunsaturated_fat_metric, 0);
				$inp_item_polyunsaturated_fat_metric_mysql = quote_smart($link, $inp_item_polyunsaturated_fat_metric);


				$inp_item_polyunsaturated_fat_calculated = $_POST['inp_item_polyunsaturated_fat_calculated'];
				$inp_item_polyunsaturated_fat_calculated = output_html($inp_item_polyunsaturated_fat_calculated);
				$inp_item_polyunsaturated_fat_calculated = str_replace(",", ".", $inp_item_polyunsaturated_fat_calculated);
				if(empty($inp_item_polyunsaturated_fat_calculated)){
					$inp_item_polyunsaturated_fat_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_polyunsaturated_fat_calculated))){
						$ft = "error";
						$fm = "fat_of_which_polyunsaturated_fatty_acids_calculated_have_to_be_a_number";
					}
				}
				$inp_item_polyunsaturated_fat_calculated = round($inp_item_polyunsaturated_fat_calculated, 0);
				$inp_item_polyunsaturated_fat_calculated_mysql = quote_smart($link, $inp_item_polyunsaturated_fat_calculated);


				// Carbohydrates
				if(isset($_POST['inp_item_carbohydrates_metric'])){
					$inp_item_carbohydrates_metric = $_POST['inp_item_carbohydrates_metric'];
				}
				else{
					$inp_item_carbohydrates_metric = "0";
				}
				$inp_item_carbohydrates_metric = output_html($inp_item_carbohydrates_metric);
				$inp_item_carbohydrates_metric = str_replace(",", ".", $inp_item_carbohydrates_metric);
				if(empty($inp_item_carbohydrates_metric)){
					$inp_item_carbohydrates_metric = "0";
				}
				else{
					if(!(is_numeric($inp_item_carbohydrates_metric))){
						$ft = "error";
						$fm = "carbohydrates_metric_have_to_be_a_number";
					}
				}
				$inp_item_carbohydrates_metric = round($inp_item_carbohydrates_metric, 0);
				$inp_item_carbohydrates_metric_mysql = quote_smart($link, $inp_item_carbohydrates_metric);


				$inp_item_carbohydrates_calculated = $_POST['inp_item_carbohydrates_calculated'];
				$inp_item_carbohydrates_calculated = output_html($inp_item_carbohydrates_calculated);
				$inp_item_carbohydrates_calculated = str_replace(",", ".", $inp_item_carbohydrates_calculated);
				if(empty($inp_item_carbohydrates_calculated)){
					$inp_item_carbohydrates_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_carbohydrates_calculated))){
						$ft = "error";
						$fm = "fat_of_which_carbohydratesty_acids_calculated_have_to_be_a_number";
					}
				}
				$inp_item_carbohydrates_calculated = round($inp_item_carbohydrates_calculated, 0);
				$inp_item_carbohydrates_calculated_mysql = quote_smart($link, $inp_item_carbohydrates_calculated);


				// Carbohydrates of which sugars
				if(isset($_POST['inp_item_carbohydrates_of_which_sugars_metric'])){
					$inp_item_carbohydrates_of_which_sugars_metric = $_POST['inp_item_carbohydrates_of_which_sugars_metric'];
				}
				else{
					$inp_item_carbohydrates_of_which_sugars_metric = "0";
				}
				$inp_item_carbohydrates_of_which_sugars_metric = output_html($inp_item_carbohydrates_of_which_sugars_metric);
				$inp_item_carbohydrates_of_which_sugars_metric = str_replace(",", ".", $inp_item_carbohydrates_of_which_sugars_metric);
				if(empty($inp_item_carbohydrates_of_which_sugars_metric)){
					$inp_item_carbohydrates_of_which_sugars_metric = "0";
				}
				else{
					if(!(is_numeric($inp_item_carbohydrates_of_which_sugars_metric))){
						$ft = "error";
						$fm = "carbohydrates_of_which_sugars_metric_have_to_be_a_number";
					}
				}
				$inp_item_carbohydrates_of_which_sugars_metric = round($inp_item_carbohydrates_of_which_sugars_metric, 0);
				$inp_item_carbohydrates_of_which_sugars_metric_mysql = quote_smart($link, $inp_item_carbohydrates_of_which_sugars_metric);


				$inp_item_carbohydrates_of_which_sugars_calculated = $_POST['inp_item_carbohydrates_of_which_sugars_calculated'];
				$inp_item_carbohydrates_of_which_sugars_calculated = output_html($inp_item_carbohydrates_of_which_sugars_calculated);
				$inp_item_carbohydrates_of_which_sugars_calculated = str_replace(",", ".", $inp_item_carbohydrates_of_which_sugars_calculated);
				if(empty($inp_item_carbohydrates_of_which_sugars_calculated)){
					$inp_item_carbohydrates_of_which_sugars_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_carbohydrates_of_which_sugars_calculated))){
						$ft = "error";
						$fm = "fat_of_which_carbohydrates_of_which_sugarsty_acids_calculated_have_to_be_a_number";
					}
				}
				$inp_item_carbohydrates_of_which_sugars_calculated = round($inp_item_carbohydrates_of_which_sugars_calculated, 0);
				$inp_item_carbohydrates_of_which_sugars_calculated_mysql = quote_smart($link, $inp_item_carbohydrates_of_which_sugars_calculated);




				// Dietary fiber
				if(isset($_POST['inp_item_dietary_fiber_metric'])){
					$inp_item_dietary_fiber_metric = $_POST['inp_item_dietary_fiber_metric'];
				}
				else{
					$inp_item_dietary_fiber_metric = "0";
				}
				$inp_item_dietary_fiber_metric = output_html($inp_item_dietary_fiber_metric);
				$inp_item_dietary_fiber_metric = str_replace(",", ".", $inp_item_dietary_fiber_metric);
				if(empty($inp_item_dietary_fiber_metric)){
					$inp_item_dietary_fiber_metric = "0";
				}
				else{
					if(!(is_numeric($inp_item_dietary_fiber_metric))){
						$ft = "error";
						$fm = "dietary_fiber_metric_have_to_be_a_number";
					}
				}
				$inp_item_dietary_fiber_metric = round($inp_item_dietary_fiber_metric, 0);
				$inp_item_dietary_fiber_metric_mysql = quote_smart($link, $inp_item_dietary_fiber_metric);


				$inp_item_dietary_fiber_calculated = $_POST['inp_item_dietary_fiber_calculated'];
				$inp_item_dietary_fiber_calculated = output_html($inp_item_dietary_fiber_calculated);
				$inp_item_dietary_fiber_calculated = str_replace(",", ".", $inp_item_dietary_fiber_calculated);
				if(empty($inp_item_dietary_fiber_calculated)){
					$inp_item_dietary_fiber_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_dietary_fiber_calculated))){
						$ft = "error";
						$fm = "fat_of_which_dietary_fiberty_acids_calculated_have_to_be_a_number";
					}
				}
				$inp_item_dietary_fiber_calculated = round($inp_item_dietary_fiber_calculated, 0);
				$inp_item_dietary_fiber_calculated_mysql = quote_smart($link, $inp_item_dietary_fiber_calculated);


				// Proteins
				if(isset($_POST['inp_item_proteins_metric'])){
					$inp_item_proteins_metric = $_POST['inp_item_proteins_metric'];
				}
				else{
					$inp_item_proteins_metric = "0";
				}
				$inp_item_proteins_metric = output_html($inp_item_proteins_metric);
				$inp_item_proteins_metric = str_replace(",", ".", $inp_item_proteins_metric);
				if(empty($inp_item_proteins_metric)){
					$inp_item_proteins_metric = "0";
				}
				else{
					if(!(is_numeric($inp_item_proteins_metric))){
						$ft = "error";
						$fm = "proteins_metric_have_to_be_a_number";
					}
				}
				$inp_item_proteins_metric = round($inp_item_proteins_metric, 0);
				$inp_item_proteins_metric_mysql = quote_smart($link, $inp_item_proteins_metric);


				$inp_item_proteins_calculated = $_POST['inp_item_proteins_calculated'];
				$inp_item_proteins_calculated = output_html($inp_item_proteins_calculated);
				$inp_item_proteins_calculated = str_replace(",", ".", $inp_item_proteins_calculated);
				if(empty($inp_item_proteins_calculated)){
					$inp_item_proteins_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_proteins_calculated))){
						$ft = "error";
						$fm = "fat_of_which_proteinsty_acids_calculated_have_to_be_a_number";
					}
				}
				$inp_item_proteins_calculated = round($inp_item_proteins_calculated, 0);
				$inp_item_proteins_calculated_mysql = quote_smart($link, $inp_item_proteins_calculated);



				// Salt
				if(isset($_POST['inp_item_salt_metric'])){
					$inp_item_salt_metric = $_POST['inp_item_salt_metric'];
				}
				else{
					$inp_item_salt_metric = "0";
				}
				$inp_item_salt_metric = output_html($inp_item_salt_metric);
				$inp_item_salt_metric = str_replace(",", ".", $inp_item_salt_metric);
				if(empty($inp_item_salt_metric)){
					$inp_item_salt_metric = "0";
				}
				else{
					if(!(is_numeric($inp_item_salt_metric))){
						$ft = "error";
						$fm = "salt_metric_have_to_be_a_number";
					}
				}
				$inp_item_salt_metric = round($inp_item_salt_metric, 0);
				$inp_item_salt_metric_mysql = quote_smart($link, $inp_item_salt_metric);


				$inp_item_salt_calculated = $_POST['inp_item_salt_calculated'];
				$inp_item_salt_calculated = output_html($inp_item_salt_calculated);
				$inp_item_salt_calculated = str_replace(",", ".", $inp_item_salt_calculated);
				if(empty($inp_item_salt_calculated)){
					$inp_item_salt_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_salt_calculated))){
						$ft = "error";
						$fm = "fat_of_which_saltty_acids_calculated_have_to_be_a_number";
					}
				}
				$inp_item_salt_calculated = round($inp_item_salt_calculated, 0);
				$inp_item_salt_calculated_mysql = quote_smart($link, $inp_item_salt_calculated);


				// Sodium
				if(isset($_POST['inp_item_sodium_metric'])){
					$inp_item_sodium_metric = $_POST['inp_item_sodium_metric'];
				}
				else{
					$inp_item_sodium_metric = "0";
				}
				$inp_item_sodium_metric = output_html($inp_item_sodium_metric);
				$inp_item_sodium_metric = str_replace(",", ".", $inp_item_sodium_metric);
				if(empty($inp_item_sodium_metric)){
					$inp_item_sodium_metric = "0";
				}
				else{
					if(!(is_numeric($inp_item_sodium_metric))){
						$ft = "error";
						$fm = "sodium_metric_have_to_be_a_number";
					}
				}
				$inp_item_sodium_metric = round($inp_item_sodium_metric, 0);
				$inp_item_sodium_metric_mysql = quote_smart($link, $inp_item_sodium_metric);


				$inp_item_sodium_calculated = $_POST['inp_item_sodium_calculated'];
				$inp_item_sodium_calculated = output_html($inp_item_sodium_calculated);
				$inp_item_sodium_calculated = str_replace(",", ".", $inp_item_sodium_calculated);
				if(empty($inp_item_sodium_calculated)){
					$inp_item_sodium_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_sodium_calculated))){
						$ft = "error";
						$fm = "fat_of_which_sodiumty_acids_calculated_have_to_be_a_number";
					}
				}
				$inp_item_sodium_calculated = round($inp_item_sodium_calculated, 0);
				$inp_item_sodium_calculated_mysql = quote_smart($link, $inp_item_sodium_calculated);

				if(isset($fm) && $fm != ""){
					$url = "submit_recipe_step_2_group_and_elements.php?recipe_id=$get_recipe_id&action=add_items&group_id=$get_group_id&l=$l";
					$url = $url . "&ft=$ft&fm=$fm";
					$url = $url . "&amount=$inp_item_amount&measurement=$inp_item_measurement&grocery=$inp_item_grocery&calories=$inp_item_calories_calculated";
					$url = $url . "&proteins=$inp_item_proteins_calculated&fat=$inp_item_fat_calculated&carbs=$inp_item_carbohydrates_calculated";

					header("Location: $url");
					exit;
				}

				// Have I already this item?
				$query = "SELECT item_id FROM $t_recipes_items WHERE item_recipe_id=$get_recipe_id AND item_group_id=$get_group_id AND item_grocery=$inp_item_grocery_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_item_id) = $row;
				if($get_item_id != ""){
					$ft = "error";
					$fm = "you_have_already_added_that_item";

					$url = "submit_recipe_step_2_group_and_elements.php?recipe_id=$get_recipe_id&action=add_items&group_id=$get_group_id&l=$l";
					$url = $url . "&ft=$ft&fm=$fm";
					$url = $url . "&amount=$inp_item_amount&measurement=$inp_item_measurement&grocery=$inp_item_grocery&calories=$inp_item_calories_calculated";
					$url = $url . "&proteins=$inp_item_proteins_calculated&fat=$inp_item_fat_calculated&carbs=$inp_item_carbs_calculated";

					header("Location: $url");
					exit;

				}

				// Get weight
				$query = "SELECT item_weight FROM $t_recipes_items WHERE item_recipe_id=$get_recipe_id AND item_group_id=$get_group_id ORDER BY item_weight DESC LIMIT 0,1";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_item_weight) = $row;
				$inp_weight = $get_item_weight+1;

				// Insert
				mysqli_query($link, "INSERT INTO $t_recipes_items
				(item_id, item_recipe_id, item_group_id, item_amount, item_measurement, 
				item_grocery, item_grocery_explanation, item_food_id, item_weight, item_energy_metric, item_fat_metric, 
				item_saturated_fat_metric, item_monounsaturated_fat_metric, item_polyunsaturated_fat_metric, item_cholesterol_metric, item_carbohydrates_metric, 
				item_carbohydrates_of_which_sugars_metric, item_dietary_fiber_metric, item_proteins_metric, item_salt_metric, item_sodium_metric, 
				item_energy_calculated, item_fat_calculated, item_saturated_fat_calculated, item_monounsaturated_fat_calculated, item_polyunsaturated_fat_calculated, 
				item_cholesterol_calculated, item_carbohydrates_calculated, item_carbohydrates_of_which_sugars_calculated, item_dietary_fiber_calculated, item_proteins_calculated, 
				item_salt_calculated, item_sodium_calculated) 
				VALUES 
				(NULL, '$get_recipe_id', '$get_group_id', $inp_item_amount_mysql, $inp_item_measurement_mysql, 
				$inp_item_grocery_mysql, '', $inp_item_food_id_mysql, $inp_weight, $inp_item_calories_metric_mysql, $inp_item_fat_metric_mysql, 
				$inp_item_saturated_fat_metric_mysql, $inp_item_monounsaturated_fat_metric_mysql, $inp_item_polyunsaturated_fat_metric_mysql, 0, $inp_item_carbohydrates_metric_mysql,
				$inp_item_carbohydrates_of_which_sugars_metric_mysql, $inp_item_dietary_fiber_metric_mysql, $inp_item_proteins_metric_mysql, $inp_item_salt_metric_mysql, $inp_item_sodium_metric_mysql, 
				$inp_item_calories_calculated_mysql, $inp_item_fat_calculated_mysql, $inp_item_saturated_fat_calculated_mysql, $inp_item_monounsaturated_fat_calculated_mysql, $inp_item_polyunsaturated_fat_calculated_mysql, 
				0, $inp_item_carbohydrates_calculated_mysql, $inp_item_carbohydrates_of_which_sugars_calculated_mysql, $inp_item_dietary_fiber_calculated_mysql, $inp_item_proteins_calculated_mysql, 
				$inp_item_salt_calculated_mysql, $inp_item_sodium_calculated_mysql
				)")
				or die(mysqli_error($link));
			

				// Calculating total numbers
				$inp_number_energy_metric		= 0;
				$inp_number_fat_metric	 		= 0;
				$inp_number_saturated_fat_metric	= 0;
				$inp_number_monounsaturated_fat_metric	= 0;
				$inp_number_polyunsaturated_fat_metric	= 0;
				$inp_number_cholesterol_metric	 	= 0;
				$inp_number_carbohydrates_metric	= 0;
				$inp_number_carbohydrates_of_which_sugars_metric  = 0;
				$inp_number_dietary_fiber_metric	= 0;
				$inp_number_proteins_metric	 	= 0;
				$inp_number_salt_metric			= 0;
				$inp_number_sodium_metric		= 0;

				$inp_number_energy_serving		= 0;
				$inp_number_fat_serving			= 0;
				$inp_number_saturated_fat_serving	= 0;
				$inp_number_monounsaturated_fat_serving	= 0;
				$inp_number_polyunsaturated_fat_serving	= 0;
				$inp_number_cholesterol_serving		= 0;
				$inp_number_carbohydrates_serving	= 0;
				$inp_number_carbohydrates_of_which_sugars_serving	 = 0;
				$inp_number_dietary_fiber_serving	= 0;
				$inp_number_proteins_serving	 	= 0;
				$inp_number_salt_serving		= 0;
				$inp_number_sodium_serving		= 0;

				$inp_number_energy_total		= 0;
				$inp_number_fat_total			= 0;
				$inp_number_saturated_fat_total	 	= 0;
				$inp_number_monounsaturated_fat_total	= 0;
				$inp_number_polyunsaturated_fat_total	= 0;
				$inp_number_cholesterol_total		= 0;
				$inp_number_carbohydrates_total		= 0;
				$inp_number_carbohydrates_of_which_sugars_total = 0;
				$inp_number_dietary_fiber_total		= 0;
				$inp_number_proteins_total		= 0;
				$inp_number_salt_total			= 0;
				$inp_number_sodium_total		= 0;

					
				$query_groups = "SELECT group_id, group_title FROM $t_recipes_groups WHERE group_recipe_id=$get_recipe_id";
				$result_groups = mysqli_query($link, $query_groups);
				while($row_groups = mysqli_fetch_row($result_groups)) {
					list($get_group_id, $get_group_title) = $row_groups;

					$query_items = "SELECT item_id, item_recipe_id, item_group_id, item_amount, item_measurement, item_grocery, item_grocery_explanation, item_food_id, item_energy_metric, item_fat_metric, item_saturated_fat_metric, item_monounsaturated_fat_metric, item_polyunsaturated_fat_metric, item_cholesterol_metric, item_carbohydrates_metric, item_carbohydrates_of_which_sugars_metric, item_dietary_fiber_metric, item_proteins_metric, item_salt_metric, item_sodium_metric, item_energy_calculated, item_fat_calculated, item_saturated_fat_calculated, item_monounsaturated_fat_calculated, item_polyunsaturated_fat_calculated, item_cholesterol_calculated, item_carbohydrates_calculated, item_carbohydrates_of_which_sugars_calculated, item_dietary_fiber_calculated, item_proteins_calculated, item_salt_calculated, item_sodium_calculated FROM $t_recipes_items WHERE item_group_id=$get_group_id";
					$result_items = mysqli_query($link, $query_items);
					$row_cnt = mysqli_num_rows($result_items);
					while($row_items = mysqli_fetch_row($result_items)) {
						list($get_item_id, $get_item_recipe_id, $get_item_group_id, $get_item_amount, $get_item_measurement, $get_item_grocery, $get_item_grocery_explanation, $get_item_food_id, $get_item_energy_metric, $get_item_fat_metric, $get_item_saturated_fat_metric, $get_item_monounsaturated_fat_metric, $get_item_polyunsaturated_fat_metric, $get_item_cholesterol_metric, $get_item_carbohydrates_metric, $get_item_carbohydrates_of_which_sugars_metric, $get_item_dietary_fiber_metric, $get_item_proteins_metric, $get_item_salt_metric, $get_item_sodium_metric, $get_item_energy_calculated, $get_item_fat_calculated, $get_item_saturated_fat_calculated, $get_item_monounsaturated_fat_calculated, $get_item_polyunsaturated_fat_calculated, $get_item_cholesterol_calculated, $get_item_carbohydrates_calculated, $get_item_carbohydrates_of_which_sugars_calculated, $get_item_dietary_fiber_calculated, $get_item_proteins_calculated, $get_item_salt_calculated, $get_item_sodium_calculated) = $row_items;



						$inp_number_energy_metric		= $inp_number_energy_metric+$get_item_energy_metric;
						$inp_number_fat_metric	 		= $inp_number_fat_metric+$get_item_fat_metric;
						$inp_number_saturated_fat_metric	= $inp_number_saturated_fat_metric+$get_item_saturated_fat_metric;
						$inp_number_monounsaturated_fat_metric	= $inp_number_monounsaturated_fat_metric+$get_item_monounsaturated_fat_metric;
						$inp_number_polyunsaturated_fat_metric	= $inp_number_polyunsaturated_fat_metric+$get_item_polyunsaturated_fat_metric;
						$inp_number_cholesterol_metric	 	= $inp_number_cholesterol_metric+$get_item_cholesterol_metric;
						$inp_number_carbohydrates_metric	= $inp_number_carbohydrates_metric+$get_item_carbohydrates_metric;
						$inp_number_carbohydrates_of_which_sugars_metric  = $inp_number_carbohydrates_of_which_sugars_metric+$get_item_carbohydrates_of_which_sugars_metric;
						$inp_number_dietary_fiber_metric	= $inp_number_dietary_fiber_metric+$get_item_dietary_fiber_metric;
						$inp_number_proteins_metric	 	= $inp_number_proteins_metric+$get_item_proteins_metric;
						$inp_number_salt_metric			= $inp_number_salt_metric+$get_item_salt_metric;
						$inp_number_sodium_metric		= $inp_number_sodium_metric+$get_item_sodium_metric;

						$inp_number_energy_total		= $inp_number_energy_total+$get_item_energy_calculated;
						$inp_number_fat_total			= $inp_number_fat_total+$get_item_fat_calculated;
						$inp_number_saturated_fat_total	 	= $inp_number_saturated_fat_total+$get_item_saturated_fat_calculated;
						$inp_number_monounsaturated_fat_total	= $inp_number_monounsaturated_fat_total+$get_item_monounsaturated_fat_calculated;
						$inp_number_polyunsaturated_fat_total	= $inp_number_polyunsaturated_fat_total+$get_item_polyunsaturated_fat_calculated;
						$inp_number_cholesterol_total		= $inp_number_cholesterol_total+$get_item_cholesterol_calculated;
						$inp_number_carbohydrates_total		= $inp_number_carbohydrates_total+$get_item_carbohydrates_calculated;
						$inp_number_carbohydrates_of_which_sugars_total = $inp_number_carbohydrates_of_which_sugars_total+$get_item_carbohydrates_of_which_sugars_calculated;
						$inp_number_dietary_fiber_total		= $inp_number_dietary_fiber_total+$get_item_dietary_fiber_calculated;
						$inp_number_proteins_total		= $inp_number_proteins_total+$get_item_proteins_calculated;
						$inp_number_salt_total			= $inp_number_salt_total+$get_item_salt_calculated;
						$inp_number_sodium_total		= $inp_number_sodium_total+$get_item_sodium_calculated;

					


	
					} // items
				} // groups
					
				

	
				// Numbers : Per hundred
				$inp_number_energy_metric_mysql			= quote_smart($link, $inp_number_energy_metric);
				$inp_number_fat_metric_mysql 			= quote_smart($link, $inp_number_fat_metric);
				$inp_number_saturated_fat_metric_mysql		= quote_smart($link, $inp_number_saturated_fat_metric);
				$inp_number_monounsaturated_fat_metric_mysql	= quote_smart($link, $inp_number_monounsaturated_fat_metric);
				$inp_number_polyunsaturated_fat_metric_mysql	= quote_smart($link, $inp_number_polyunsaturated_fat_metric);
				$inp_number_cholesterol_metric_mysql	 	= quote_smart($link, $inp_number_cholesterol_metric);
				$inp_number_carbohydrates_metric_mysql		= quote_smart($link, $inp_number_carbohydrates_metric);
				$inp_number_carbohydrates_of_which_sugars_metric_mysql  = quote_smart($link, $inp_number_carbohydrates_of_which_sugars_metric);
				$inp_number_dietary_fiber_metric_mysql		= quote_smart($link, $inp_number_dietary_fiber_metric);
				$inp_number_proteins_metric_mysql	 	= quote_smart($link, $inp_number_proteins_metric);
				$inp_number_salt_metric_mysql			= quote_smart($link, $inp_number_salt_metric);
				$inp_number_sodium_metric_mysql			= quote_smart($link, $inp_number_sodium_metric);


					
				// Numbers : Total 
				$inp_number_energy_total_mysql			= quote_smart($link, $inp_number_energy_total);
				$inp_number_fat_total_mysql			= quote_smart($link, $inp_number_fat_total);
				$inp_number_saturated_fat_total_mysql	 	= quote_smart($link, $inp_number_saturated_fat_total);
				$inp_number_monounsaturated_fat_total_mysql	= quote_smart($link, $inp_number_monounsaturated_fat_total);
				$inp_number_polyunsaturated_fat_total_mysql	= quote_smart($link, $inp_number_polyunsaturated_fat_total);
				$inp_number_cholesterol_total_mysql		= quote_smart($link, $inp_number_cholesterol_total);
				$inp_number_carbohydrates_total_mysql		= quote_smart($link, $inp_number_carbohydrates_total);
				$inp_number_carbohydrates_of_which_sugars_total_mysql = quote_smart($link, $inp_number_carbohydrates_of_which_sugars_total);
				$inp_number_dietary_fiber_total_mysql		= quote_smart($link, $inp_number_dietary_fiber_total);
				$inp_number_proteins_total_mysql		= quote_smart($link, $inp_number_proteins_total);
				$inp_number_salt_total_mysql			= quote_smart($link, $inp_number_salt_total);
				$inp_number_sodium_total_mysql			= quote_smart($link, $inp_number_sodium_total);


				// Numbers : Per serving
				$inp_number_energy_serving	 = round($inp_number_energy_total/$get_number_servings);
				$inp_number_energy_serving_mysql = quote_smart($link, $inp_number_energy_serving);

				$inp_number_fat_serving	 = round($inp_number_fat_total/$get_number_servings);
				$inp_number_fat_serving_mysql = quote_smart($link, $inp_number_fat_serving);

				$inp_number_saturated_fat_serving	 = round($inp_number_saturated_fat_total/$get_number_servings);
				$inp_number_saturated_fat_serving_mysql = quote_smart($link, $inp_number_saturated_fat_serving);

				$inp_number_monounsaturated_fat_serving	 = round($inp_number_monounsaturated_fat_total/$get_number_servings);
				$inp_number_monounsaturated_fat_serving_mysql = quote_smart($link, $inp_number_monounsaturated_fat_serving);

				$inp_number_polyunsaturated_fat_serving	 = round($inp_number_polyunsaturated_fat_total/$get_number_servings);
				$inp_number_polyunsaturated_fat_serving_mysql = quote_smart($link, $inp_number_polyunsaturated_fat_serving);

				$inp_number_cholesterol_serving	 = round($inp_number_cholesterol_total/$get_number_servings);
				$inp_number_cholesterol_serving_mysql = quote_smart($link, $inp_number_cholesterol_serving);

				$inp_number_carbohydrates_serving	 = round($inp_number_carbohydrates_total/$get_number_servings);
				$inp_number_carbohydrates_serving_mysql = quote_smart($link, $inp_number_carbohydrates_serving);

				$inp_number_carbohydrates_of_which_sugars_serving	 = round($inp_number_carbohydrates_of_which_sugars_total/$get_number_servings);
				$inp_number_carbohydrates_of_which_sugars_serving_mysql = quote_smart($link, $inp_number_carbohydrates_of_which_sugars_serving);

				$inp_number_dietary_fiber_serving	 = round($inp_number_dietary_fiber_total/$get_number_servings);
				$inp_number_dietary_fiber_serving_mysql = quote_smart($link, $inp_number_dietary_fiber_serving);

				$inp_number_proteins_serving	 = round($inp_number_proteins_total/$get_number_servings);
				$inp_number_proteins_serving_mysql = quote_smart($link, $inp_number_proteins_serving);

				$inp_number_salt_serving	 = round($inp_number_salt_total/$get_number_servings);
				$inp_number_salt_serving_mysql = quote_smart($link, $inp_number_salt_serving);

				$inp_number_sodium_serving	 = round($inp_number_sodium_total/$get_number_servings);
				$inp_number_sodium_serving_mysql = quote_smart($link, $inp_number_sodium_serving);



				$result = mysqli_query($link, "UPDATE $t_recipes_numbers SET 

								number_energy_metric=$inp_number_energy_metric_mysql, 
								number_fat_metric=$inp_number_fat_metric_mysql, 
								number_saturated_fat_metric=$inp_number_saturated_fat_metric_mysql, 
								number_monounsaturated_fat_metric=$inp_number_monounsaturated_fat_metric_mysql, 
								number_polyunsaturated_fat_metric=$inp_number_polyunsaturated_fat_metric_mysql, 
								number_cholesterol_metric=$inp_number_cholesterol_metric_mysql, 
								number_carbohydrates_metric=$inp_number_carbohydrates_metric_mysql, 
								number_carbohydrates_of_which_sugars_metric=$inp_number_carbohydrates_of_which_sugars_metric_mysql, 
								number_dietary_fiber_metric=$inp_number_dietary_fiber_metric_mysql, 
								number_proteins_metric=$inp_number_proteins_metric_mysql, 
								number_salt_metric=$inp_number_salt_metric_mysql, 
								number_sodium_metric=$inp_number_sodium_metric_mysql, 

								number_energy_serving=$inp_number_energy_serving_mysql, 
								number_fat_serving=$inp_number_fat_serving_mysql, 
								number_saturated_fat_serving=$inp_number_saturated_fat_serving_mysql, 
								number_monounsaturated_fat_serving=$inp_number_monounsaturated_fat_serving_mysql, 
								number_polyunsaturated_fat_serving=$inp_number_polyunsaturated_fat_serving_mysql, 
								number_cholesterol_serving=$inp_number_cholesterol_serving_mysql, 
								number_carbohydrates_serving=$inp_number_carbohydrates_serving_mysql, 
								number_carbohydrates_of_which_sugars_serving=$inp_number_carbohydrates_of_which_sugars_serving_mysql, 
								number_dietary_fiber_serving=$inp_number_dietary_fiber_serving_mysql, 
								number_proteins_serving=$inp_number_proteins_serving_mysql, 
								number_salt_serving=$inp_number_salt_serving_mysql, 

								number_sodium_serving=$inp_number_sodium_serving_mysql, 
								number_energy_total=$inp_number_energy_total_mysql, 
								number_fat_total=$inp_number_fat_total_mysql, 
								number_saturated_fat_total=$inp_number_saturated_fat_total_mysql, 
								number_monounsaturated_fat_total=$inp_number_monounsaturated_fat_total_mysql, 
								number_polyunsaturated_fat_total=$inp_number_polyunsaturated_fat_total_mysql, 
								number_cholesterol_total=$inp_number_cholesterol_total_mysql, 
								number_carbohydrates_total=$inp_number_carbohydrates_total_mysql, 
								number_carbohydrates_of_which_sugars_total=$inp_number_carbohydrates_of_which_sugars_total_mysql, 
								number_dietary_fiber_total=$inp_number_dietary_fiber_total_mysql, 
								number_proteins_total=$inp_number_proteins_total_mysql, 
								number_salt_total=$inp_number_salt_total_mysql, 
								number_sodium_total=$inp_number_sodium_total_mysql

					 WHERE number_recipe_id=$recipe_id_mysql") or die(mysqli_error($link));



	

				// Header
				$ft = "success";
				$fm = "ingredient_added";

				$url = "submit_recipe_step_2_group_and_elements.php?recipe_id=$get_recipe_id&action=add_items&group_id=$get_group_id&l=$l";
				$url = $url . "&ft=$ft&fm=$fm";
				header("Location: $url");
				exit;	

				

			}
			echo"
			<h1>$l_add_ingredients</h1>



			<!-- Feedback -->
				";
				if($ft != ""){
					if($fm == "changes_saved"){
						$fm = "$l_changes_saved";
					}
					elseif($fm == "amound_cant_be_empty"){
						$fm = "$l_amound_cant_be_empty";
					}
					elseif($fm == "amound_has_to_be_a_number"){
						$fm = "$l_amound_has_to_be_a_number";
					}
					elseif($fm == "measurement_cant_be_empty"){
						$fm = "$l_measurement_cant_be_empty";
					}
					elseif($fm == "grocery_cant_be_empty"){
						$fm = "$l_grocery_cant_be_empty";
					}
					elseif($fm == "calories_cant_be_empty"){
						$fm = "$l_calories_cant_be_empty";
					}
					elseif($fm == "proteins_cant_be_empty"){
						$fm = "$l_proteins_cant_be_empty";
					}
					elseif($fm == "fat_cant_be_empty"){
						$fm = "$l_fat_cant_be_empty";
					}
					elseif($fm == "carbs_cant_be_empty"){
						$fm = "$l_carbs_cant_be_empty";
					}
					elseif($fm == "calories_have_to_be_a_number"){
						$fm = "$l_calories_have_to_be_a_number";
					}
					elseif($fm == "proteins_have_to_be_a_number"){
						$fm = "$l_proteins_have_to_be_a_number";
					}
					elseif($fm == "carbs_have_to_be_a_number"){
						$fm = "$l_carbs_have_to_be_a_number";
					}
					elseif($fm == "fat_have_to_be_a_number"){
						$fm = "$l_fat_have_to_be_a_number";
					}
					elseif($fm == "you_have_already_added_that_item"){
						$fm = "$l_you_have_already_added_that_item";
					}
					elseif($fm == "ingredient_added"){
						$fm = "$l_ingredient_added";
					}
					else{
						$fm = ucfirst($fm);
					}
					echo"<div class=\"$ft\"><span>$fm</span></div>";
				}
				echo"	
			<!-- //Feedback -->


			<div style=\"float: left\">
				<h2>$get_group_title</h2>
			</div>
			<div style=\"float: left;padding-left: 10px;\">
					<p style=\"padding: 15px 0px 0px 0px;margin: 0;\">
					<a href=\"submit_recipe_step_2_group_and_elements.php?action=edit_group&amp;recipe_id=$recipe_id&amp;group_id=$get_group_id&amp;l=$l\" class=\"grey\">$l_edit</a>
					&middot;
					<a href=\"submit_recipe_step_2_group_and_elements.php?action=delete_group&amp;recipe_id=$recipe_id&amp;group_id=$get_group_id&amp;l=$l\" class=\"grey\">$l_delete</a>
					</p>
			</div>
			<div class=\"clear\"></div>



			<!-- Add item + Items -->
				<div class=\"row_two\">
					<!-- Add item -->
						<div class=\"columns_two_wrapper\">
							<!-- Focus -->
								<script>
								\$(document).ready(function(){
									\$('[name=\"inp_item_amount\"]').focus();
								});
								</script>
							<!-- //Focus -->

							<!-- Var -->
								";
								if(isset($_GET['amount'])){
									$inp_item_amount = $_GET['amount'];
									$inp_item_amount = output_html($inp_item_amount);
								}
								else{
									$inp_item_amount = "";
								}

								if(isset($_GET['measurement'])){
									$inp_item_measurement = $_GET['measurement'];
									$inp_item_measurement = output_html($inp_item_measurement);
								}
								else{
									$inp_item_measurement = "";
								}

								if(isset($_GET['grocery'])){
									$inp_item_grocery = $_GET['grocery'];
									$inp_item_grocery = output_html($inp_item_grocery);
								}
								else{
									$inp_item_grocery = "";
								}

								// Calories
								if(isset($_GET['calories_metric'])){
									$inp_item_calories_metric = $_GET['calories_metric'];
									$inp_item_calories_metric = output_html($inp_item_calories_metric);
								}
								else{
									$inp_item_calories_metric = "";
								}
								if(isset($_GET['calories_calculated'])){
									$inp_item_calories_calculated = $_GET['calories_calculated'];
									$inp_item_calories_calculated = output_html($inp_item_calories_calculated);
								}
								else{
									$inp_item_calories_calculated = "";
								}

								// Fat
								if(isset($_GET['fat_metric'])){
									$inp_item_fat_metric = $_GET['fat_metric'];
									$inp_item_fat_metric = output_html($inp_item_fat_metric);
								}
								else{
									$inp_item_fat_metric = "";
								}
								if(isset($_GET['fat_calculated'])){
									$inp_item_fat_calculated = $_GET['fat_calculated'];
									$inp_item_fat_calculated = output_html($inp_item_fat_calculated);
								}
								else{
									$inp_item_fat_calculated = "";
								}


								// Saturated Fat
								if(isset($_GET['saturated_fat_metric'])){
									$inp_item_saturated_fat_metric = $_GET['saturated_fat_metric'];
									$inp_item_saturated_fat_metric = output_html($inp_item_saturated_fat_metric);
								}
								else{
									$inp_item_saturated_fat_metric = "";
								}
								if(isset($_GET['saturated_fat_calculated'])){
									$inp_item_saturated_fat_calculated = $_GET['saturated_fat_calculated'];
									$inp_item_saturated_fat_calculated = output_html($inp_item_saturated_fat_calculated);
								}
								else{
									$inp_item_saturated_fat_calculated = "";
								}



								// Monounsaturated Fat
								if(isset($_GET['monounsaturated_fat_metric'])){
									$inp_item_monounsaturated_fat_metric = $_GET['monounsaturated_fat_metric'];
									$inp_item_monounsaturated_fat_metric = output_html($inp_item_monounsaturated_fat_metric);
								}
								else{
									$inp_item_monounsaturated_fat_metric = "";
								}
								if(isset($_GET['monounsaturated_fat_calculated'])){
									$inp_item_monounsaturated_fat_calculated = $_GET['monounsaturated_fat_calculated'];
									$inp_item_monounsaturated_fat_calculated = output_html($inp_item_monounsaturated_fat_calculated);
								}
								else{
									$inp_item_monounsaturated_fat_calculated = "";
								}

								// Polyunsaturated Fat
								if(isset($_GET['polyunsaturated_fat_metric'])){
									$inp_item_polyunsaturated_fat_metric = $_GET['polyunsaturated_fat_metric'];
									$inp_item_polyunsaturated_fat_metric = output_html($inp_item_polyunsaturated_fat_metric);
								}
								else{
									$inp_item_polyunsaturated_fat_metric = "";
								}
								if(isset($_GET['polyunsaturated_fat_calculated'])){
									$inp_item_polyunsaturated_fat_calculated = $_GET['polyunsaturated_fat_calculated'];
									$inp_item_polyunsaturated_fat_calculated = output_html($inp_item_polyunsaturated_fat_calculated);
								}
								else{
									$inp_item_polyunsaturated_fat_calculated = "";
								}



								// Carbohydrates
								if(isset($_GET['carbohydrates_metric'])){
									$inp_item_carbohydrates_metric = $_GET['carbohydrates_metric'];
									$inp_item_carbohydrates_metric = output_html($inp_item_carbohydrates_metric);
								}
								else{
									$inp_item_carbohydrates_metric = "";
								}
								if(isset($_GET['carbohydrates_calculated'])){
									$inp_item_carbohydrates_calculated = $_GET['carbohydrates_calculated'];
									$inp_item_carbohydrates_calculated = output_html($inp_item_carbohydrates_calculated);
								}
								else{
									$inp_item_carbohydrates_calculated = "";
								}
			
								// Carbohydrates of which sugars
								if(isset($_GET['carbohydrates_of_which_sugars_metric'])){
									$inp_item_carbohydrates_of_which_sugars_metric = $_GET['carbohydrates_of_which_sugars_metric'];
									$inp_item_carbohydrates_of_which_sugars_metric = output_html($inp_item_carbohydrates_of_which_sugars_metric);
								}
								else{
									$inp_item_carbohydrates_of_which_sugars_metric = "";
								}
								if(isset($_GET['carbohydrates_of_which_sugars_calculated'])){
									$inp_item_carbohydrates_of_which_sugars_calculated = $_GET['carbohydrates_of_which_sugars_calculated'];
									$inp_item_carbohydrates_of_which_sugars_calculated = output_html($inp_item_carbohydrates_of_which_sugars_calculated);
								}
								else{
									$inp_item_carbohydrates_of_which_sugars_calculated = "";
								}


								// Dietary fiber
								if(isset($_GET['dietary_fiber_metric'])){
									$inp_item_dietary_fiber_metric = $_GET['dietary_fiber_metric'];
									$inp_item_dietary_fiber_metric = output_html($inp_item_dietary_fiber_metric);
								}
								else{
									$inp_item_dietary_fiber_metric = "";
								}
								if(isset($_GET['dietary_fiber_calculated'])){
									$inp_item_dietary_fiber_calculated = $_GET['dietary_fiber_calculated'];
									$inp_item_dietary_fiber_calculated = output_html($inp_item_dietary_fiber_calculated);
								}
								else{
									$inp_item_dietary_fiber_calculated = "";
								}

								// Proteins
								if(isset($_GET['proteins_metric'])){
									$inp_item_proteins_metric = $_GET['proteins_metric'];
									$inp_item_proteins_metric = output_html($inp_item_proteins_metric);
								}
								else{
									$inp_item_proteins_metric = "";
								}
								if(isset($_GET['proteins_calculated'])){
									$inp_item_proteins_calculated = $_GET['proteins_calculated'];
									$inp_item_proteins_calculated = output_html($inp_item_proteins_calculated);
								}
								else{
									$inp_item_proteins_calculated = "";
								}

								// Salt
								if(isset($_GET['salt_metric'])){
									$inp_item_salt_metric = $_GET['salt_metric'];
									$inp_item_salt_metric = output_html($inp_item_salt_metric);
								}
								else{
									$inp_item_salt_metric = "";
								}
								if(isset($_GET['salt_calculated'])){
									$inp_item_salt_calculated = $_GET['salt_calculated'];
									$inp_item_salt_calculated = output_html($inp_item_salt_calculated);
								}
								else{
									$inp_item_salt_calculated = "";
								}

								// Sodium
								if(isset($_GET['sodium_metric'])){
									$inp_item_sodium_metric = $_GET['sodium_metric'];
									$inp_item_sodium_metric = output_html($inp_item_sodium_metric);
								}
								else{
									$inp_item_sodium_metric = "";
								}
								if(isset($_GET['sodium_calculated'])){
									$inp_item_sodium_calculated = $_GET['sodium_calculated'];
									$inp_item_sodium_calculated = output_html($inp_item_sodium_calculated);
								}
								else{
									$inp_item_sodium_calculated = "";
								}
 
								echo"
							<!-- //Var -->

							<p><b>- $l_add_ingredient -</b></p>
							<form method=\"post\" action=\"submit_recipe_step_2_group_and_elements.php?recipe_id=$get_recipe_id&amp;action=add_items&amp;group_id=$group_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			


							<h2 style=\"padding-bottom:0;margin-bottom:0;\">$l_food</h2>
							<p>$l_amount<br />
							<input type=\"text\" name=\"inp_item_amount\" id=\"inp_item_amount\" size=\"3\" value=\"$inp_item_amount\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
							<input type=\"text\" name=\"inp_item_measurement\" class=\"inp_item_measurement\" id=\"inp_item_measurement\" size=\"3\" value=\"$inp_item_measurement\" style=\"width:auto;border: #fff 1px solid;border-bottom: #ddd 1px dashed\" />
							</p>

							<p>$l_grocery &middot; <a href=\"$root/food/new_food.php?l=$l\" target=\"_blank\">$l_new_food</a><br />
							<input type=\"text\" name=\"inp_item_grocery\" class=\"inp_item_grocery\" id=\"inp_item_grocery\" size=\"25\" value=\"$inp_item_grocery\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
							<input type=\"hidden\" name=\"inp_item_food_id\" id=\"inp_item_food_id\" /></p>

							<!-- Special character replacer -->
								<script>

								\$(document).ready(function(){
									window.setInterval(function(){
										var inp_item_grocery = \$(\".inp_item_grocery\").val();
										var inp_item_grocery = inp_item_grocery.replace(\"&aring;\", \"å\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&aelig;\", \"æ\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&Aring;\", \"Å\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&Aelig;\", \"Æ\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#192;\", \"À\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#193;\", \"Á\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#194;\", \"Â\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#195;\", \"Ã\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#196;\", \"Ä\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#197;\", \"Å\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#198;\", \"Æ\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#199;\", \"Ç\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#200;\", \"È\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#201;\", \"É\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#202;\", \"Ê\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#203;\", \"Ë\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#204;\", \"Ì\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#205;\", \"Í\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#206;\", \"Î\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#207;\", \"Ï\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#208;\", \"Ð\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#209;\", \"Ñ\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#210;\", \"Ò\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#211;\", \"Ó\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#212;\", \"Ô\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#213;\", \"Õ\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#214;\", \"Ö\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#215;\", \"×\");   
										var inp_item_grocery = inp_item_grocery.replace(\"&#216;\", \"Ø\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&Oslash;\", \"Ø\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&oslash;\", \"ø\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#217;\", \"Ù\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#218;\", \"Ú\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#219;\", \"Û\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#220;\", \"Ü\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#221;\", \"Ý\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#222;\", \"Þ\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#223;\", \"ß\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#224;\", \"à\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#225;\", \"á\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#226;\", \"â\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#227;\", \"ã\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#228;\", \"ä\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#229;\", \"å\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#230;\", \"æ\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#231;\", \"ç\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#232;\", \"è\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#233;\", \"é\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#234;\", \"ê\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#235;\", \"ë\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#236;\", \"ì\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#237;\", \"í\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#238;\", \"î\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#239;\", \"ï\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#240;\", \"ð\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#241;\", \"ñ\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&ntilde;\", \"ñ\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#242;\", \"ò\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#243;\", \"ó\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#244;\", \"ô\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#245;\", \"õ\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#246;\", \"ö\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#247;\", \"÷\");  
										var inp_item_grocery = inp_item_grocery.replace(\"&#248;\", \"ø\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#249;\", \"ù\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#250;\", \"ú\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#251;\", \"û\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#252;\", \"ü\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#253;\", \"ý\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#254;\", \"þ\"); 
										var inp_item_grocery = inp_item_grocery.replace(\"&#255;\", \"ÿ\"); 

										var inp_item_grocery = inp_item_grocery.replace(\"&#039;\", \"'\"); 

										\$(\"#inp_item_grocery\").val(inp_item_grocery);
								
									}, 1000);

							
								});
								</script>

						<!-- //Special character replacer -->

						<!-- User adapted view -->";
							if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
								$my_user_id = $_SESSION['user_id'];
								$my_user_id = output_html($my_user_id);
								$my_user_id_mysql = quote_smart($link, $my_user_id);
			
								$query_t = "SELECT view_id, view_user_id, view_ip, view_year, view_system, view_hundred_metric, view_serving, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_recipes_user_adapted_view WHERE view_user_id=$get_my_user_id";
								$result_t = mysqli_query($link, $query_t);
								$row_t = mysqli_fetch_row($result_t);
								list($get_current_view_id, $get_current_view_user_id, $get_current_view_ip, $get_current_view_year, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_serving, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
							}
							else{
								// IP
								$my_user_ip = $_SERVER['REMOTE_ADDR'];
								$my_user_ip = output_html($my_user_ip);
								$my_user_ip_mysql = quote_smart($link, $my_user_ip);
			
								$query_t = "SELECT view_id, view_user_id, view_ip, view_year, view_system, view_hundred_metric, view_serving, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_recipes_user_adapted_view WHERE view_ip=$my_user_ip_mysql";
								$result_t = mysqli_query($link, $query_t);
								$row_t = mysqli_fetch_row($result_t);
								list($get_current_view_id, $get_current_view_user_id, $get_current_view_ip, $get_current_view_year, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_serving, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
							}
							if($get_current_view_hundred_metric == ""){
								$get_current_view_hundred_metric = "1";
							}
							echo"
							<p><a id=\"adapter_view\"></a>
							<input type=\"checkbox\" name=\"inp_show_hundred_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=hundred_metric&amp;process=1&amp;referer=submit_recipe_step_2_group_and_elements&amp;action=$action&amp;recipe_id=$recipe_id&amp;group_id=$group_id&amp;l=$l\""; if($get_current_view_hundred_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_hundred
							<input type=\"checkbox\" name=\"inp_show_pcs_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=pcs_metric&amp;process=1&amp;referer=submit_recipe_step_2_group_and_elements&amp;action=$action&amp;recipe_id=$recipe_id&amp;group_id=$group_id&amp;l=$l\""; if($get_current_view_pcs_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_pcs_g
							<input type=\"checkbox\" name=\"inp_show_eight_us\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=eight_us&amp;process=1&amp;referer=submit_recipe_step_2_group_and_elements&amp;action=$action&amp;recipe_id=$recipe_id&amp;group_id=$group_id&amp;l=$l\""; if($get_current_view_eight_us == "1"){ echo" checked=\"checked\""; } echo" /> $l_eight
							<input type=\"checkbox\" name=\"inp_show_pcs_us\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=pcs_us&amp;process=1&amp;referer=submit_recipe_step_2_group_and_elements&amp;action=$action&amp;recipe_id=$recipe_id&amp;group_id=$group_id&amp;l=$l\""; if($get_current_view_pcs_us == "1"){ echo" checked=\"checked\""; } echo" /> $l_pcs_oz
							</p>

							<!-- On check go to URL -->
								<script>
								\$(function() {
									\$(\".onclick_go_to_url\").change(function(){
										var item=\$(this);
										window.location.href= item.data(\"target\")
									});
  								});
								</script>
							<!-- //On check go to URL -->
						<!-- //User adapted view -->

						<div id=\"nettport_search_results\">
						</div><div class=\"clear\"></div>
				

						<hr />
						<h2 style=\"padding-bottom:0;margin-bottom:0;\">$l_numbers</h2>
						<table class=\"hor-zebra\" style=\"width: 350px\">
						 <thead>
						  <tr>
						   <th scope=\"col\">
						   </th>
						   <th scope=\"col\" style=\"text-align: center;padding: 6px 4px 6px 4px;vertical-align: bottom;\">
					<span>$l_per_hundred</span>
				   </th>
				   <th scope=\"col\" style=\"text-align: center;padding: 6px 4px 6px 4px;vertical-align: bottom;\">
					<span>$l_calculated</span>
				   </th>
				  </tr>
				 </thead>


				 <tbody>
				  <tr>
				   <td style=\"padding: 8px 4px 6px 8px;\">
					<span>$l_calories</span>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<span><input type=\"text\" name=\"inp_item_calories_metric\" id=\"inp_item_calories_metric\" size=\"5\" value=\"$inp_item_calories_metric\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></span>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<span><input type=\"text\" name=\"inp_item_calories_calculated\" id=\"inp_item_calories_calculated\" size=\"5\" value=\"$inp_item_calories_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></span>
				   </td>
				  </tr>

				  <tr>
				   <td style=\"padding: 8px 4px 6px 8px;\">
					<p style=\"margin:0;padding: 0px 0px 4px 0px;\">$l_fat</p>
					<p style=\"margin:0;padding: 0;\">$l_dash_saturated_fat</p>
					<p style=\"margin:0;padding: 0;\">$l_dash_monounsaturated_fat</p>
					<p style=\"margin:0;padding: 0;\">$l_dash_polyunsaturated_fat</p>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<p style=\"margin:0;padding: 0px 0px 4px 0px;\"><input type=\"text\" name=\"inp_item_fat_metric\" id=\"inp_item_fat_metric\" size=\"5\" value=\"$inp_item_fat_metric\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_saturated_fat_metric\" id=\"inp_item_saturated_fat_metric\" size=\"5\" value=\"$inp_item_saturated_fat_metric\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_monounsaturated_fat_metric\" id=\"inp_item_monounsaturated_fat_metric\" size=\"5\" value=\"$inp_item_monounsaturated_fat_metric\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_polyunsaturated_fat_metric\" id=\"inp_item_polyunsaturated_fat_metric\" size=\"5\" value=\"$inp_item_polyunsaturated_fat_metric\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<p style=\"margin:0;padding: 0px 0px 4px 0px;\"><input type=\"text\" name=\"inp_item_fat_calculated\" id=\"inp_item_fat_calculated\" size=\"5\" value=\"$inp_item_fat_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_saturated_fat_calculated\" id=\"inp_item_saturated_fat_calculated\" size=\"5\" value=\"$inp_item_saturated_fat_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_monounsaturated_fat_calculated\" id=\"inp_item_monounsaturated_fat_calculated\" size=\"5\" value=\"$inp_item_monounsaturated_fat_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_polyunsaturated_fat_calculated\" id=\"inp_item_polyunsaturated_fat_calculated\" size=\"5\" value=\"$inp_item_saturated_fat_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
				   </td>
				  </tr>

				  <tr>
		 		   <td style=\"padding: 8px 4px 6px 8px;\">
					<p style=\"margin:0;padding: 0px 0px 4px 0px;\">$l_carbs</p>
					<p style=\"margin:0;padding: 0;\">$l_dash_of_which_sugars</p>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<p style=\"margin:0;padding: 0px 0px 4px 0px;\"><input type=\"text\" name=\"inp_item_carbohydrates_metric\" id=\"inp_item_carbohydrates_metric\" size=\"5\" value=\"$inp_item_carbohydrates_metric\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_carbohydrates_of_which_sugars_metric\" id=\"inp_item_carbohydrates_of_which_sugars_metric\" size=\"5\" value=\"$inp_item_carbohydrates_of_which_sugars_metric\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<p style=\"margin:0;padding: 0px 0px 4px 0px;\"><input type=\"text\" name=\"inp_item_carbohydrates_calculated\" id=\"inp_item_carbohydrates_calculated\" size=\"5\" value=\"$inp_item_carbohydrates_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_carbohydrates_of_which_sugars_calculated\" id=\"inp_item_carbohydrates_of_which_sugars_calculated\" size=\"5\" value=\"$inp_item_carbohydrates_of_which_sugars_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
				   </td>
				  </tr>

				 <tr>
	 			  <td style=\"padding: 8px 4px 6px 8px;\">
					<p style=\"margin:0;padding: 0;\">$l_dietary_fiber</p>
				   </td>
				 	  <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_dietary_fiber_metric\" id=\"inp_item_dietary_fiber_metric\" size=\"5\" value=\"$inp_item_dietary_fiber_metric\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_dietary_fiber_calculated\" id=\"inp_item_dietary_fiber_calculated\" size=\"5\" value=\"$inp_item_dietary_fiber_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
				   </td>
				  </tr>


				  <tr>
				   <td style=\"padding: 8px 4px 6px 8px;\">
					<span>$l_proteins</span>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<span><input type=\"text\" name=\"inp_item_proteins_metric\" id=\"inp_item_proteins_metric\" size=\"5\" value=\"$inp_item_proteins_metric\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></span>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<span><input type=\"text\" name=\"inp_item_proteins_calculated\" id=\"inp_item_proteins_calculated\" size=\"5\" value=\"$inp_item_proteins_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></span>
				   </td>
				 </tr>

				  <tr>
				   <td style=\"padding: 8px 4px 6px 8px;\">
					<span>$l_salt_in_gram</span>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<span><input type=\"text\" name=\"inp_item_salt_metric\" id=\"inp_item_salt_metric\" value=\"$inp_item_salt_metric\" size=\"5\" /></span>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<span><input type=\"text\" name=\"inp_item_salt_calculated\" id=\"inp_item_salt_calculated\" value=\"$inp_item_salt_calculated\" size=\"5\" /></span>
				   </td>
				  </tr>

				  <tr>
				   <td style=\"padding: 8px 4px 6px 8px;\">
					<span>$l_sodium_in_mg</span>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<span><input type=\"text\" name=\"inp_item_sodium_metric\" id=\"inp_item_sodium_metric\" value=\"$inp_item_sodium_metric\" size=\"5\" /></span>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<span><input type=\"text\" name=\"inp_item_sodium_calculated\" id=\"inp_item_sodium_calculated\" value=\"$inp_item_sodium_calculated\" size=\"5\" /></span>
				   </td>
				  </tr>
				 </tbody>
				</table>
			
				<p>
				<input type=\"submit\" value=\"$l_add_item\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>


				</form>
				<!-- Search script -->
					<script id=\"source\" language=\"javascript\" type=\"text/javascript\">
					\$(document).ready(function () {
						\$('#inp_item_grocery').keyup(function () {
							$(\"#nettport_search_results\").show();
       							// getting the value that user typed
       							var searchString    = $(\"#inp_item_grocery\").val();
 							// forming the queryString
      							var data            = 'l=$l&recipe_id=$recipe_id&view_id=$get_current_view_id&columns=2&q='+ searchString;
         
        						// if searchString is not empty
        						if(searchString) {
           							// ajax call
            							\$.ajax({
                							type: \"POST\",
               								url: \"submit_recipe_step_2_group_and_elements_search_jquery.php\",
                							data: data,
									beforeSend: function(html) { // this happens before actual call
										\$(\"#nettport_search_results\").html(''); 
									},
               								success: function(html){
                    								\$(\"#nettport_search_results\").html(html);
              								}
            							});
       							}
        						return false;
            					});
         				   });
							</script>
							<!-- //Search script -->

						</div> <!-- //columns_two_wrapper -->
					<!-- Add item -->
					<!-- Summary -->
						<div class=\"columns_two_wrapper\">

							<p><b>- $l_summary -</b></p>
							<ul style=\"padding: 6px 0px 20px 35px;margin: 0;\">
							";
							$x = 0;
							$group_id_mysql = quote_smart($link, $group_id);
							$query_items = "SELECT item_id, item_recipe_id, item_group_id, item_amount, item_measurement, item_grocery, item_grocery_explanation, item_food_id, item_weight, item_energy_metric, item_fat_metric, item_saturated_fat_metric, item_monounsaturated_fat_metric, item_polyunsaturated_fat_metric, item_cholesterol_metric, item_carbohydrates_metric, item_carbohydrates_of_which_sugars_metric, item_dietary_fiber_metric, item_proteins_metric, item_salt_metric, item_sodium_metric, item_energy_calculated, item_fat_calculated, item_saturated_fat_calculated, item_monounsaturated_fat_calculated, item_polyunsaturated_fat_calculated, item_cholesterol_calculated, item_carbohydrates_calculated, item_carbohydrates_of_which_sugars_calculated, item_dietary_fiber_calculated, item_proteins_calculated, item_salt_calculated, item_sodium_calculated FROM $t_recipes_items WHERE item_group_id=$group_id_mysql ORDER BY item_weight ASC";
							$result_items = mysqli_query($link, $query_items);
							$row_cnt = mysqli_num_rows($result_items);
							while($row_items = mysqli_fetch_row($result_items)) {
								list($get_item_id, $get_item_recipe_id, $get_item_group_id, $get_item_amount, $get_item_measurement, $get_item_grocery, $get_item_grocery_explanation, $get_item_food_id, $get_item_weight, $get_item_energy_metric, $get_item_fat_metric, $get_item_saturated_fat_metric, $get_item_monounsaturated_fat_metric, $get_item_polyunsaturated_fat_metric, $get_item_cholesterol_metric, $get_item_carbohydrates_metric, $get_item_carbohydrates_of_which_sugars_metric, $get_item_dietary_fiber_metric, $get_item_proteins_metric, $get_item_salt_metric, $get_item_sodium_metric, $get_item_energy_calculated, $get_item_fat_calculated, $get_item_saturated_fat_calculated, $get_item_monounsaturated_fat_calculated, $get_item_polyunsaturated_fat_calculated, $get_item_cholesterol_calculated, $get_item_carbohydrates_calculated, $get_item_carbohydrates_of_which_sugars_calculated, $get_item_dietary_fiber_calculated, $get_item_proteins_calculated, $get_item_salt_calculated, $get_item_sodium_calculated) = $row_items;

								// Weight
								if($get_item_weight != "$x"){
									mysqli_query($link, "UPDATE $t_recipes_items SET item_weight=$x WHERE item_id=$get_item_id") or die(mysqli_error($link));
								}

								echo"					";
								echo"<li><span>$get_item_amount $get_item_measurement $get_item_grocery</span>
								<span>
								
								";
								if($x != "0"){
									echo"<a href=\"submit_recipe_step_2_group_and_elements.php?action=move_item_up&amp;recipe_id=$recipe_id&amp;group_id=$get_group_id&amp;item_id=$get_item_id&amp;l=$l&amp;process=1\" class=\"grey\">$l_up</a>
									&middot;";
								}
								echo"
								<a href=\"submit_recipe_step_2_group_and_elements.php?action=edit_item&amp;recipe_id=$recipe_id&amp;group_id=$get_group_id&amp;item_id=$get_item_id&amp;l=$l\" class=\"grey\">$l_edit</a>
								&middot;
								<a href=\"submit_recipe_step_2_group_and_elements.php?action=delete_item&amp;recipe_id=$recipe_id&amp;group_id=$get_group_id&amp;item_id=$get_item_id&amp;l=$l\" class=\"grey\">$l_delete</a>
								
								<br /></span>

								<span class=\"grey_small\">$get_item_amount $get_item_measurement:
								$get_item_energy_calculated $l_cal_lowercase,
								$get_item_fat_calculated $l_fat_lowercase, 
								$get_item_saturated_fat_calculated $l_saturated_fatty_acids_lowercase, 
								$get_item_carbohydrates_calculated $l_carbs_lowercase, 
								$get_item_carbohydrates_of_which_sugars_calculated $l_sugar_lowercase, 
								$get_item_dietary_fiber_calculated $l_dietary_fiber_lowercase, 
								$get_item_proteins_calculated $l_proteins_lowercase, 
								$get_item_salt_calculated $l_salt_lowercase, 
								$get_item_sodium_calculated $l_sodium_lowercase<br /><br />
								</span>
								</li>\n";


								$x++;
							} // items

							echo"
							</ul>
						</div> <!-- //columns_two_wrapper -->
					<!-- Summary -->
				</div> <!-- //row_two -->
			<!-- //Add item + Items -->



			<!-- Buttons -->
				<div class=\"clear\"></div>
				<p>
				<a href=\"submit_recipe_step_2_group_and_elements.php?recipe_id=$get_recipe_id&amp;action=summary&amp;l=$l\" class=\"btn btn_default\">$l_summary</a>

				<a href=\"submit_recipe_step_2_group_and_elements.php?recipe_id=$get_recipe_id&amp;l=$l\" class=\"btn btn_default\">$l_add_another_group</a>
			
				<a href=\"submit_recipe_step_3_main_ingredient.php?recipe_id=$get_recipe_id&amp;l=$l\" class=\"btn btn_default\">$l_continue</a>
	
				</p>
			<!-- //Buttons -->


			";
		} // group found
	} // action == "add_items")
	elseif($action == "summary"){

		echo"
		<h1>$l_summary</h1>


		<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				elseif($fm == "group_deleted"){
					$fm = "$l_group_deleted";
				}
				elseif($fm == "item_deleted"){
					$fm = "$l_item_deleted";
				}
				else{
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
		<!-- //Feedback -->


		<!-- Groups -->
			";

			$query_groups = "SELECT group_id, group_title FROM $t_recipes_groups WHERE group_recipe_id=$get_recipe_id";
			$result_groups = mysqli_query($link, $query_groups);
			while($row_groups = mysqli_fetch_row($result_groups)) {
				list($get_group_id, $get_group_title) = $row_groups;
				echo"
				<h2 style=\"padding: 10px 0px 0px 0px;margin: 0;\">$get_group_title</h2>
				<p style=\"padding: 0px 0px 0px 0px;margin: 0;\">
					<a href=\"submit_recipe_step_2_group_and_elements.php?action=add_items&amp;recipe_id=$get_recipe_id&amp;group_id=$get_group_id&amp;l=$l\">$l_ingredient</a>
					&middot;
					<a href=\"submit_recipe_step_2_group_and_elements.php?action=edit_group&amp;recipe_id=$recipe_id&amp;group_id=$get_group_id&amp;l=$l\" class=\"grey\">$l_edit</a>
					&middot;
					<a href=\"submit_recipe_step_2_group_and_elements.php?action=delete_group&amp;recipe_id=$recipe_id&amp;group_id=$get_group_id&amp;l=$l\" class=\"grey\">$l_delete</a>
				</p>
				

				<div class=\"clear\"></div>
				<ul style=\"padding: 6px 0px 20px 35px;margin: 0;\">
				";

				$query_items = "SELECT item_id, item_amount, item_measurement, item_grocery FROM $t_recipes_items WHERE item_group_id=$get_group_id ORDER BY item_weight ASC";
				$result_items = mysqli_query($link, $query_items);
				$row_cnt = mysqli_num_rows($result_items);
				while($row_items = mysqli_fetch_row($result_items)) {
					list($get_item_id, $get_item_amount, $get_item_measurement, $get_item_grocery) = $row_items;
					echo"					";
					echo"<li><span>$get_item_amount $get_item_measurement $get_item_grocery</span>
					<span>
					<a href=\"submit_recipe_step_2_group_and_elements.php?action=edit_item&amp;recipe_id=$recipe_id&amp;group_id=$get_group_id&amp;item_id=$get_item_id&amp;l=$l\" class=\"grey\">$l_edit</a>
					&middot;
					<a href=\"submit_recipe_step_2_group_and_elements.php?action=delete_item&amp;recipe_id=$recipe_id&amp;group_id=$get_group_id&amp;item_id=$get_item_id&amp;&amp;l=$l\" class=\"grey\">$l_delete</a>
					</span></li>\n";
				} // items

				echo"
				</ul>
				";
			} // groups

			echo"
		<!-- //Groups -->
		<!-- Buttons -->
			<p>
			<a href=\"submit_recipe_step_2_group_and_elements.php?recipe_id=$get_recipe_id&amp;l=$l\" class=\"btn btn_default\">$l_add_another_group</a>
			<a href=\"submit_recipe_step_3_main_ingredient.php?recipe_id=$get_recipe_id&amp;l=$l\" class=\"btn btn_default\">$l_continue</a>
			</p>
		<!-- //Buttons -->
	

		";
	} // action == summary
	elseif($action == "edit_group"){
		// Get group
		$group_id_mysql = quote_smart($link, $group_id);
		$query = "SELECT group_id, group_recipe_id, group_title FROM $t_recipes_groups WHERE group_id=$group_id_mysql AND group_recipe_id=$get_recipe_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_group_id, $get_group_recipe_id, $get_group_title) = $row;

		if($get_group_id == ""){
			echo"
			<h1>Server error</h1>

			<p>
			Group not found.
			</p>
			";
		}
		else{
			if($process == "1"){
				$inp_group_title = $_POST['inp_group_title'];
				$inp_group_title = output_html($inp_group_title);
				$inp_group_title_mysql = quote_smart($link, $inp_group_title);
				if(empty($inp_group_title)){
					$ft = "error";
					$fm = "title_cant_be_empty";

					$url = "submit_recipe_step_2_group_and_elements.php?action=edit_group&recipe_id=$get_recipe_id&group_id=$get_group_id&l=$l";
					$url = $url . "&ft=$ft&fm=$fm";

					header("Location: $url");
					exit;
				}

				// Update
				$result = mysqli_query($link, "UPDATE $t_recipes_groups SET group_title=$inp_group_title_mysql WHERE group_id=$get_group_id");


				// Header
				$ft = "success";
				$fm = "changes_saved";

				$url = "submit_recipe_step_2_group_and_elements.php?action=summary&recipe_id=$get_recipe_id&group_id=$get_group_id&l=$l";
				$url = $url . "&ft=$ft&fm=$fm";
				header("Location: $url");
				exit;	

				

			}
			echo"
			<h1>$l_edit_group</h1>



			<!-- Feedback -->
				";
				if($ft != ""){
					if($fm == "changes_saved"){
						$fm = "$l_changes_saved";
					}
					elseif($fm == "amound_cant_be_empty"){
						$fm = "$l_amound_cant_be_empty";
					}
					else{
						$fm = ucfirst($fm);
					}
					echo"<div class=\"$ft\"><span>$fm</span></div>";
				}
				echo"	
			<!-- //Feedback -->

			<!-- Edit group form -->
				<!-- Focus -->
					<script>
					\$(document).ready(function(){
						\$('[name=\"inp_group_title\"]').focus();
					});
					</script>
				<!-- //Focus -->


				<form method=\"post\" action=\"submit_recipe_step_2_group_and_elements.php?action=edit_group&amp;recipe_id=$get_recipe_id&amp;group_id=$group_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			
				<p><b>$l_title:</b><br />
				<input type=\"text\" name=\"inp_group_title\" size=\"30\" value=\"$get_group_title\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				<input type=\"submit\" value=\"$l_save_changes\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>


				</form>
			<!-- //Add item -->

			<!-- Buttons -->
				<p>
				<a href=\"submit_recipe_step_2_group_and_elements.php?recipe_id=$get_recipe_id&amp;action=summary&amp;l=$l\" class=\"btn btn_default\">$l_summary</a>

				<a href=\"submit_recipe_step_2_group_and_elements.php?recipe_id=$get_recipe_id&amp;l=$l\" class=\"btn btn_default\">$l_add_another_group</a>
			
				<a href=\"submit_recipe_step_3_main_ingredient.php?recipe_id=$get_recipe_id&amp;l=$l\" class=\"btn btn_default\">$l_continue</a>
	
				</p>
			<!-- //Buttons -->


			";
		} // group found
	} // action == edit_group
	elseif($action == "delete_group"){
		// Get group
		$group_id_mysql = quote_smart($link, $group_id);
		$query = "SELECT group_id, group_recipe_id, group_title FROM $t_recipes_groups WHERE group_id=$group_id_mysql AND group_recipe_id=$get_recipe_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_group_id, $get_group_recipe_id, $get_group_title) = $row;

		if($get_group_id == ""){
			echo"
			<h1>Server error</h1>

			<p>
			Group not found.
			</p>
			";
		}
		else{
			if($process == "1"){
				
				// Update
				$result = mysqli_query($link, "DELETE FROM $t_recipes_groups WHERE group_id=$get_group_id");
				$result = mysqli_query($link, "DELETE FROM $t_recipes_items WHERE item_group_id=$get_group_id");

				
				// Calculating total numbers

				// Calculating total numbers
				$inp_number_energy_metric		= 0;
				$inp_number_fat_metric	 		= 0;
				$inp_number_saturated_fat_metric	= 0;
				$inp_number_monounsaturated_fat_metric	= 0;
				$inp_number_polyunsaturated_fat_metric	= 0;
				$inp_number_cholesterol_metric	 	= 0;
				$inp_number_carbohydrates_metric	= 0;
				$inp_number_carbohydrates_of_which_sugars_metric  = 0;
				$inp_number_dietary_fiber_metric	= 0;
				$inp_number_proteins_metric	 	= 0;
				$inp_number_salt_metric			= 0;
				$inp_number_sodium_metric		= 0;

				$inp_number_energy_serving		= 0;
				$inp_number_fat_serving			= 0;
				$inp_number_saturated_fat_serving	= 0;
				$inp_number_monounsaturated_fat_serving	= 0;
				$inp_number_polyunsaturated_fat_serving	= 0;
				$inp_number_cholesterol_serving		= 0;
				$inp_number_carbohydrates_serving	= 0;
				$inp_number_carbohydrates_of_which_sugars_serving	 = 0;
				$inp_number_dietary_fiber_serving	= 0;
				$inp_number_proteins_serving	 	= 0;
				$inp_number_salt_serving		= 0;
				$inp_number_sodium_serving		= 0;

				$inp_number_energy_total		= 0;
				$inp_number_fat_total			= 0;
				$inp_number_saturated_fat_total	 	= 0;
				$inp_number_monounsaturated_fat_total	= 0;
				$inp_number_polyunsaturated_fat_total	= 0;
				$inp_number_cholesterol_total		= 0;
				$inp_number_carbohydrates_total		= 0;
				$inp_number_carbohydrates_of_which_sugars_total = 0;
				$inp_number_dietary_fiber_total		= 0;
				$inp_number_proteins_total		= 0;
				$inp_number_salt_total			= 0;
				$inp_number_sodium_total		= 0;

					
				$query_groups = "SELECT group_id, group_title FROM $t_recipes_groups WHERE group_recipe_id=$get_recipe_id";
				$result_groups = mysqli_query($link, $query_groups);
				while($row_groups = mysqli_fetch_row($result_groups)) {
					list($get_group_id, $get_group_title) = $row_groups;

					$query_items = "SELECT item_id, item_recipe_id, item_group_id, item_amount, item_measurement, item_grocery, item_grocery_explanation, item_food_id, item_energy_metric, item_fat_metric, item_saturated_fat_metric, item_monounsaturated_fat_metric, item_polyunsaturated_fat_metric, item_cholesterol_metric, item_carbohydrates_metric, item_carbohydrates_of_which_sugars_metric, item_dietary_fiber_metric, item_proteins_metric, item_salt_metric, item_sodium_metric, item_energy_calculated, item_fat_calculated, item_saturated_fat_calculated, item_monounsaturated_fat_calculated, item_polyunsaturated_fat_calculated, item_cholesterol_calculated, item_carbohydrates_calculated, item_carbohydrates_of_which_sugars_calculated, item_dietary_fiber_calculated, item_proteins_calculated, item_salt_calculated, item_sodium_calculated FROM $t_recipes_items WHERE item_group_id=$get_group_id";
					$result_items = mysqli_query($link, $query_items);
					$row_cnt = mysqli_num_rows($result_items);
					while($row_items = mysqli_fetch_row($result_items)) {
						list($get_item_id, $get_item_recipe_id, $get_item_group_id, $get_item_amount, $get_item_measurement, $get_item_grocery, $get_item_grocery_explanation, $get_item_food_id, $get_item_energy_metric, $get_item_fat_metric, $get_item_saturated_fat_metric, $get_item_monounsaturated_fat_metric, $get_item_polyunsaturated_fat_metric, $get_item_cholesterol_metric, $get_item_carbohydrates_metric, $get_item_carbohydrates_of_which_sugars_metric, $get_item_dietary_fiber_metric, $get_item_proteins_metric, $get_item_salt_metric, $get_item_sodium_metric, $get_item_energy_calculated, $get_item_fat_calculated, $get_item_saturated_fat_calculated, $get_item_monounsaturated_fat_calculated, $get_item_polyunsaturated_fat_calculated, $get_item_cholesterol_calculated, $get_item_carbohydrates_calculated, $get_item_carbohydrates_of_which_sugars_calculated, $get_item_dietary_fiber_calculated, $get_item_proteins_calculated, $get_item_salt_calculated, $get_item_sodium_calculated) = $row_items;



						$inp_number_energy_metric		= $inp_number_energy_metric+$get_item_energy_metric;
						$inp_number_fat_metric	 		= $inp_number_fat_metric+$get_item_fat_metric;
						$inp_number_saturated_fat_metric	= $inp_number_saturated_fat_metric+$get_item_saturated_fat_metric;
						$inp_number_monounsaturated_fat_metric	= $inp_number_monounsaturated_fat_metric+$get_item_monounsaturated_fat_metric;
						$inp_number_polyunsaturated_fat_metric	= $inp_number_polyunsaturated_fat_metric+$get_item_polyunsaturated_fat_metric;
						$inp_number_cholesterol_metric	 	= $inp_number_cholesterol_metric+$get_item_cholesterol_metric;
						$inp_number_carbohydrates_metric	= $inp_number_carbohydrates_metric+$get_item_carbohydrates_metric;
						$inp_number_carbohydrates_of_which_sugars_metric  = $inp_number_carbohydrates_of_which_sugars_metric+$get_item_carbohydrates_of_which_sugars_metric;
						$inp_number_dietary_fiber_metric	= $inp_number_dietary_fiber_metric+$get_item_dietary_fiber_metric;
						$inp_number_proteins_metric	 	= $inp_number_proteins_metric+$get_item_proteins_metric;
						$inp_number_salt_metric			= $inp_number_salt_metric+$get_item_salt_metric;
						$inp_number_sodium_metric		= $inp_number_sodium_metric+$get_item_sodium_metric;

						$inp_number_energy_total		= $inp_number_energy_total+$get_item_energy_calculated;
						$inp_number_fat_total			= $inp_number_fat_total+$get_item_fat_calculated;
						$inp_number_saturated_fat_total	 	= $inp_number_saturated_fat_total+$get_item_saturated_fat_calculated;
						$inp_number_monounsaturated_fat_total	= $inp_number_monounsaturated_fat_total+$get_item_monounsaturated_fat_calculated;
						$inp_number_polyunsaturated_fat_total	= $inp_number_polyunsaturated_fat_total+$get_item_polyunsaturated_fat_calculated;
						$inp_number_cholesterol_total		= $inp_number_cholesterol_total+$get_item_cholesterol_calculated;
						$inp_number_carbohydrates_total		= $inp_number_carbohydrates_total+$get_item_carbohydrates_calculated;
						$inp_number_carbohydrates_of_which_sugars_total = $inp_number_carbohydrates_of_which_sugars_total+$get_item_carbohydrates_of_which_sugars_calculated;
						$inp_number_dietary_fiber_total		= $inp_number_dietary_fiber_total+$get_item_dietary_fiber_calculated;
						$inp_number_proteins_total		= $inp_number_proteins_total+$get_item_proteins_calculated;
						$inp_number_salt_total			= $inp_number_salt_total+$get_item_salt_calculated;
						$inp_number_sodium_total		= $inp_number_sodium_total+$get_item_sodium_calculated;

					


	
					} // items
				} // groups
					
				

	
				// Numbers : Per hundred
				$inp_number_energy_metric_mysql			= quote_smart($link, $inp_number_energy_metric);
				$inp_number_fat_metric_mysql 			= quote_smart($link, $inp_number_fat_metric);
				$inp_number_saturated_fat_metric_mysql		= quote_smart($link, $inp_number_saturated_fat_metric);
				$inp_number_monounsaturated_fat_metric_mysql	= quote_smart($link, $inp_number_monounsaturated_fat_metric);
				$inp_number_polyunsaturated_fat_metric_mysql	= quote_smart($link, $inp_number_polyunsaturated_fat_metric);
				$inp_number_cholesterol_metric_mysql	 	= quote_smart($link, $inp_number_cholesterol_metric);
				$inp_number_carbohydrates_metric_mysql		= quote_smart($link, $inp_number_carbohydrates_metric);
				$inp_number_carbohydrates_of_which_sugars_metric_mysql  = quote_smart($link, $inp_number_carbohydrates_of_which_sugars_metric);
				$inp_number_dietary_fiber_metric_mysql		= quote_smart($link, $inp_number_dietary_fiber_metric);
				$inp_number_proteins_metric_mysql	 	= quote_smart($link, $inp_number_proteins_metric);
				$inp_number_salt_metric_mysql			= quote_smart($link, $inp_number_salt_metric);
				$inp_number_sodium_metric_mysql			= quote_smart($link, $inp_number_sodium_metric);


					
				// Numbers : Total 
				$inp_number_energy_total_mysql			= quote_smart($link, $inp_number_energy_total);
				$inp_number_fat_total_mysql			= quote_smart($link, $inp_number_fat_total);
				$inp_number_saturated_fat_total_mysql	 	= quote_smart($link, $inp_number_saturated_fat_total);
				$inp_number_monounsaturated_fat_total_mysql	= quote_smart($link, $inp_number_monounsaturated_fat_total);
				$inp_number_polyunsaturated_fat_total_mysql	= quote_smart($link, $inp_number_polyunsaturated_fat_total);
				$inp_number_cholesterol_total_mysql		= quote_smart($link, $inp_number_cholesterol_total);
				$inp_number_carbohydrates_total_mysql		= quote_smart($link, $inp_number_carbohydrates_total);
				$inp_number_carbohydrates_of_which_sugars_total_mysql = quote_smart($link, $inp_number_carbohydrates_of_which_sugars_total);
				$inp_number_dietary_fiber_total_mysql		= quote_smart($link, $inp_number_dietary_fiber_total);
				$inp_number_proteins_total_mysql		= quote_smart($link, $inp_number_proteins_total);
				$inp_number_salt_total_mysql			= quote_smart($link, $inp_number_salt_total);
				$inp_number_sodium_total_mysql			= quote_smart($link, $inp_number_sodium_total);


				// Numbers : Per serving
				$inp_number_energy_serving	 = round($inp_number_energy_total/$get_number_servings);
				$inp_number_energy_serving_mysql = quote_smart($link, $inp_number_energy_serving);

				$inp_number_fat_serving	 = round($inp_number_fat_total/$get_number_servings);
				$inp_number_fat_serving_mysql = quote_smart($link, $inp_number_fat_serving);

				$inp_number_saturated_fat_serving	 = round($inp_number_saturated_fat_total/$get_number_servings);
				$inp_number_saturated_fat_serving_mysql = quote_smart($link, $inp_number_saturated_fat_serving);

				$inp_number_monounsaturated_fat_serving	 = round($inp_number_monounsaturated_fat_total/$get_number_servings);
				$inp_number_monounsaturated_fat_serving_mysql = quote_smart($link, $inp_number_monounsaturated_fat_serving);

				$inp_number_polyunsaturated_fat_serving	 = round($inp_number_polyunsaturated_fat_total/$get_number_servings);
				$inp_number_polyunsaturated_fat_serving_mysql = quote_smart($link, $inp_number_polyunsaturated_fat_serving);

				$inp_number_cholesterol_serving	 = round($inp_number_cholesterol_total/$get_number_servings);
				$inp_number_cholesterol_serving_mysql = quote_smart($link, $inp_number_cholesterol_serving);

				$inp_number_carbohydrates_serving	 = round($inp_number_carbohydrates_total/$get_number_servings);
				$inp_number_carbohydrates_serving_mysql = quote_smart($link, $inp_number_carbohydrates_serving);

				$inp_number_carbohydrates_of_which_sugars_serving	 = round($inp_number_carbohydrates_of_which_sugars_total/$get_number_servings);
				$inp_number_carbohydrates_of_which_sugars_serving_mysql = quote_smart($link, $inp_number_carbohydrates_of_which_sugars_serving);

				$inp_number_dietary_fiber_serving	 = round($inp_number_dietary_fiber_total/$get_number_servings);
				$inp_number_dietary_fiber_serving_mysql = quote_smart($link, $inp_number_dietary_fiber_serving);

				$inp_number_proteins_serving	 = round($inp_number_proteins_total/$get_number_servings);
				$inp_number_proteins_serving_mysql = quote_smart($link, $inp_number_proteins_serving);

				$inp_number_salt_serving	 = round($inp_number_salt_total/$get_number_servings);
				$inp_number_salt_serving_mysql = quote_smart($link, $inp_number_salt_serving);

				$inp_number_sodium_serving	 = round($inp_number_sodium_total/$get_number_servings);
				$inp_number_sodium_serving_mysql = quote_smart($link, $inp_number_sodium_serving);



				$result = mysqli_query($link, "UPDATE $t_recipes_numbers SET 

								number_energy_metric=$inp_number_energy_metric_mysql, 
								number_fat_metric=$inp_number_fat_metric_mysql, 
								number_saturated_fat_metric=$inp_number_saturated_fat_metric_mysql, 
								number_monounsaturated_fat_metric=$inp_number_monounsaturated_fat_metric_mysql, 
								number_polyunsaturated_fat_metric=$inp_number_polyunsaturated_fat_metric_mysql, 
								number_cholesterol_metric=$inp_number_cholesterol_metric_mysql, 
								number_carbohydrates_metric=$inp_number_carbohydrates_metric_mysql, 
								number_carbohydrates_of_which_sugars_metric=$inp_number_carbohydrates_of_which_sugars_metric_mysql, 
								number_dietary_fiber_metric=$inp_number_dietary_fiber_metric_mysql, 
								number_proteins_metric=$inp_number_proteins_metric_mysql, 
								number_salt_metric=$inp_number_salt_metric_mysql, 
								number_sodium_metric=$inp_number_sodium_metric_mysql, 

								number_energy_serving=$inp_number_energy_serving_mysql, 
								number_fat_serving=$inp_number_fat_serving_mysql, 
								number_saturated_fat_serving=$inp_number_saturated_fat_serving_mysql, 
								number_monounsaturated_fat_serving=$inp_number_monounsaturated_fat_serving_mysql, 
								number_polyunsaturated_fat_serving=$inp_number_polyunsaturated_fat_serving_mysql, 
								number_cholesterol_serving=$inp_number_cholesterol_serving_mysql, 
								number_carbohydrates_serving=$inp_number_carbohydrates_serving_mysql, 
								number_carbohydrates_of_which_sugars_serving=$inp_number_carbohydrates_of_which_sugars_serving_mysql, 
								number_dietary_fiber_serving=$inp_number_dietary_fiber_serving_mysql, 
								number_proteins_serving=$inp_number_proteins_serving_mysql, 
								number_salt_serving=$inp_number_salt_serving_mysql, 

								number_sodium_serving=$inp_number_sodium_serving_mysql, 
								number_energy_total=$inp_number_energy_total_mysql, 
								number_fat_total=$inp_number_fat_total_mysql, 
								number_saturated_fat_total=$inp_number_saturated_fat_total_mysql, 
								number_monounsaturated_fat_total=$inp_number_monounsaturated_fat_total_mysql, 
								number_polyunsaturated_fat_total=$inp_number_polyunsaturated_fat_total_mysql, 
								number_cholesterol_total=$inp_number_cholesterol_total_mysql, 
								number_carbohydrates_total=$inp_number_carbohydrates_total_mysql, 
								number_carbohydrates_of_which_sugars_total=$inp_number_carbohydrates_of_which_sugars_total_mysql, 
								number_dietary_fiber_total=$inp_number_dietary_fiber_total_mysql, 
								number_proteins_total=$inp_number_proteins_total_mysql, 
								number_salt_total=$inp_number_salt_total_mysql, 
								number_sodium_total=$inp_number_sodium_total_mysql

					 WHERE number_recipe_id=$recipe_id_mysql") or die(mysqli_error($link));

				// Header
				$ft = "success";
				$fm = "group_deleted";

				$url = "submit_recipe_step_2_group_and_elements.php?action=summary&recipe_id=$get_recipe_id&l=$l";
				$url = $url . "&ft=$ft&fm=$fm";
				header("Location: $url");
				exit;	

				

			}
			echo"
			<h1>$l_delete_group</h1>



			<!-- Feedback -->
				";
				if($ft != ""){
					if($fm == "changes_saved"){
						$fm = "$l_changes_saved";
					}
					elseif($fm == "amound_cant_be_empty"){
						$fm = "$l_amound_cant_be_empty";
					}
					else{
						$fm = ucfirst($fm);
					}
					echo"<div class=\"$ft\"><span>$fm</span></div>";
				}
				echo"	
			<!-- //Feedback -->

			<!-- Delete group -->
				<h2>$get_group_title</h2>
				<p>
				$l_are_you_sure_you_want_to_delete
				$l_the_action_cant_be_undone
				</p>

				<p>
				<a href=\"submit_recipe_step_2_group_and_elements.php?action=delete_group&amp;recipe_id=$get_recipe_id&amp;group_id=$group_id&amp;l=$l&amp;process=1\" class=\"btn btn_warning\">$l_delete</a>
				<a href=\"submit_recipe_step_2_group_and_elements.php?action=summary&amp;recipe_id=$get_recipe_id&amp;l=$l\" class=\"btn btn_default\">$l_cancel</a>
				</p>
			<!-- //Delete group -->

			<!-- Buttons -->
				<p style=\"margin-top: 20px\">
				<a href=\"submit_recipe_step_2_group_and_elements.php?recipe_id=$get_recipe_id&amp;action=summary&amp;l=$l\" class=\"btn btn_default\">$l_summary</a>

				<a href=\"submit_recipe_step_2_group_and_elements.php?recipe_id=$get_recipe_id&amp;l=$l\" class=\"btn btn_default\">$l_add_another_group</a>
			
				<a href=\"submit_recipe_step_3_main_ingredient.php?recipe_id=$get_recipe_id&amp;l=$l\" class=\"btn btn_default\">$l_continue</a>
	
				</p>
			<!-- //Buttons -->


			";
		} // group found
	} // action == delete_group
	elseif($action == "move_item_up"){
		// Get group
		$group_id_mysql = quote_smart($link, $group_id);
		$query = "SELECT group_id, group_recipe_id, group_title FROM $t_recipes_groups WHERE group_id=$group_id_mysql AND group_recipe_id=$get_recipe_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_group_id, $get_group_recipe_id, $get_group_title) = $row;

		if($get_group_id == ""){
			echo"
			<h1>Server error</h1>

			<p>
			Group not found.
			</p>
			";
		}
		else{
			// Get item
			$item_id_mysql = quote_smart($link, $item_id);
			$query = "SELECT item_id, item_weight FROM $t_recipes_items WHERE item_id=$item_id_mysql AND item_recipe_id=$get_recipe_id AND item_group_id=$get_group_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_item_id, $get_current_item_weight) = $row;


			if($get_current_item_id == ""){
				echo"
				<h1>Server error</h1>

				<p>
				Item not found.
				</p>
				";
			} // item found
			else{
				// Find to switch with
				$switch_weight = $get_current_item_weight-1;
				$query = "SELECT item_id, item_weight FROM $t_recipes_items WHERE item_recipe_id=$get_recipe_id AND item_group_id=$get_group_id AND item_weight=$switch_weight";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_switch_item_id, $get_switch_item_weight) = $row;
				if($get_switch_item_id == ""){
					$url = "submit_recipe_step_2_group_and_elements.php?recipe_id=$get_recipe_id&action=add_items&group_id=$get_group_id&l=$l&ft=error&fm=could_not_find_any_item_to_switch_with";
					header("Location: $url");
					exit;
				}
				
				// Make the switch
				mysqli_query($link, "UPDATE $t_recipes_items SET item_weight=$get_switch_item_weight WHERE item_id=$get_current_item_id") or die(mysqli_error($link));
				mysqli_query($link, "UPDATE $t_recipes_items SET item_weight=$get_current_item_weight WHERE item_id=$get_switch_item_id") or die(mysqli_error($link));


				$url = "submit_recipe_step_2_group_and_elements.php?recipe_id=$get_recipe_id&action=add_items&group_id=$get_group_id&l=$l&ft=success&fm=item_moved_up";
				header("Location: $url");
				exit;
			}
		} // group found
	} // move_item_up
	elseif($action == "edit_item"){
		// Get group
		$group_id_mysql = quote_smart($link, $group_id);
		$query = "SELECT group_id, group_recipe_id, group_title FROM $t_recipes_groups WHERE group_id=$group_id_mysql AND group_recipe_id=$get_recipe_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_group_id, $get_group_recipe_id, $get_group_title) = $row;

		if($get_group_id == ""){
			echo"
			<h1>Server error</h1>

			<p>
			Group not found.
			</p>
			";
		}
		else{
			// Get item
			$item_id_mysql = quote_smart($link, $item_id);
			$query = "SELECT item_id, item_recipe_id, item_group_id, item_amount, item_measurement, item_grocery, item_grocery_explanation, item_food_id, item_energy_metric, item_fat_metric, item_saturated_fat_metric, item_monounsaturated_fat_metric, item_polyunsaturated_fat_metric, item_cholesterol_metric, item_carbohydrates_metric, item_carbohydrates_of_which_sugars_metric, item_dietary_fiber_metric, item_proteins_metric, item_salt_metric, item_sodium_metric, item_energy_calculated, item_fat_calculated, item_saturated_fat_calculated, item_monounsaturated_fat_calculated, item_polyunsaturated_fat_calculated, item_cholesterol_calculated, item_carbohydrates_calculated, item_carbohydrates_of_which_sugars_calculated, item_dietary_fiber_calculated, item_proteins_calculated, item_salt_calculated, item_sodium_calculated FROM $t_recipes_items WHERE item_id=$item_id_mysql AND item_recipe_id=$get_recipe_id AND item_group_id=$get_group_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_item_id, $get_item_recipe_id, $get_item_group_id, $get_item_amount, $get_item_measurement, $get_item_grocery, $get_item_grocery_explanation, $get_item_food_id, $get_item_energy_metric, $get_item_fat_metric, $get_item_saturated_fat_metric, $get_item_monounsaturated_fat_metric, $get_item_polyunsaturated_fat_metric, $get_item_cholesterol_metric, $get_item_carbohydrates_metric, $get_item_carbohydrates_of_which_sugars_metric, $get_item_dietary_fiber_metric, $get_item_proteins_metric, $get_item_salt_metric, $get_item_sodium_metric, $get_item_energy_calculated, $get_item_fat_calculated, $get_item_saturated_fat_calculated, $get_item_monounsaturated_fat_calculated, $get_item_polyunsaturated_fat_calculated, $get_item_cholesterol_calculated, $get_item_carbohydrates_calculated, $get_item_carbohydrates_of_which_sugars_calculated, $get_item_dietary_fiber_calculated, $get_item_proteins_calculated, $get_item_salt_calculated, $get_item_sodium_calculated) = $row;



			if($get_item_id == ""){
				echo"
				<h1>Server error</h1>

				<p>
				Items not found.
				</p>
				";
			}
			else{

				if($process == "1"){

					$inp_item_amount = $_POST['inp_item_amount'];
					$inp_item_amount = output_html($inp_item_amount);
					$inp_item_amount = str_replace(",", ".", $inp_item_amount);
					$inp_item_amount_mysql = quote_smart($link, $inp_item_amount);
					if(empty($inp_item_amount)){
						$ft = "error";
						$fm = "amound_cant_be_empty";
					}
					else{
						if(!(is_numeric($inp_item_amount))){
							// Do we have math? Example 1/8 ts
							$check_for_fraction = explode("/", $inp_item_amount);

							if(isset($check_for_fraction[0]) && isset($check_for_fraction[1])){
								if(is_numeric($check_for_fraction[0]) && is_numeric($check_for_fraction[1])){
									$inp_item_amount = $check_for_fraction[0] / $check_for_fraction[1];
								}
								else{
									$ft = "error";
									$fm = "amound_has_to_be_a_number";
								}
							}
							else{
								$ft = "error";
								$fm = "amound_has_to_be_a_number";
							}
						}
					}
	
					$inp_item_measurement = $_POST['inp_item_measurement'];
					$inp_item_measurement = output_html($inp_item_measurement);
					$inp_item_measurement = str_replace(",", ".", $inp_item_measurement);
					$inp_item_measurement_mysql = quote_smart($link, $inp_item_measurement);
					if(empty($inp_item_measurement)){
						$ft = "error";
						$fm = "measurement_cant_be_empty";
					}

					$inp_item_grocery = $_POST['inp_item_grocery'];
					$inp_item_grocery = output_html($inp_item_grocery);
					$inp_item_grocery = str_replace(",", ".", $inp_item_grocery);
					$inp_item_grocery_mysql = quote_smart($link, $inp_item_grocery);
					if(empty($inp_item_grocery)){
						$ft = "error";
						$fm = "grocery_cant_be_empty";
					}

					$inp_item_food_id = $_POST['inp_item_food_id'];
					$inp_item_food_id = output_html($inp_item_food_id);
					if($inp_item_food_id == ""){
						$inp_item_food_id = "0";
					}
					$inp_item_food_id_mysql = quote_smart($link, $inp_item_food_id);


				// Calories
				if(isset($_POST['inp_item_calories_metric'])){
					$inp_item_calories_metric = $_POST['inp_item_calories_metric'];
				}
				else{
					$inp_item_calories_metric = "0";
				}
				$inp_item_calories_metric = output_html($inp_item_calories_metric);
				$inp_item_calories_metric = str_replace(",", ".", $inp_item_calories_metric);
				if(empty($inp_item_calories_metric)){
					$inp_item_calories_metric = "0";
				}
				else{
					if(!(is_numeric($inp_item_calories_metric))){
						$ft = "error";
						$fm = "calories_have_to_be_a_number";
					}
				}
				$inp_item_calories_metric = round($inp_item_calories_metric, 0);
				$inp_item_calories_metric_mysql = quote_smart($link, $inp_item_calories_metric);


				$inp_item_calories_calculated = $_POST['inp_item_calories_calculated'];
				$inp_item_calories_calculated = output_html($inp_item_calories_calculated);
				$inp_item_calories_calculated = str_replace(",", ".", $inp_item_calories_calculated);
				if(empty($inp_item_calories_calculated)){
					$inp_item_calories_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_calories_calculated))){
						$ft = "error";
						$fm = "calories_have_to_be_a_number";
					}
				}
				$inp_item_calories_calculated = round($inp_item_calories_calculated, 0);
				$inp_item_calories_calculated_mysql = quote_smart($link, $inp_item_calories_calculated);

				// Fat
				if(isset($_POST['inp_item_fat_metric'])){
					$inp_item_fat_metric = $_POST['inp_item_fat_metric'];
				}
				else{
					$inp_item_fat_metric = "0";
				}
				$inp_item_fat_metric = output_html($inp_item_fat_metric);
				$inp_item_fat_metric = str_replace(",", ".", $inp_item_fat_metric);
				if(empty($inp_item_fat_metric)){
					$inp_item_fat_metric = "0";
				}
				else{
					if(!(is_numeric($inp_item_calories_metric))){
						$ft = "error";
						$fm = "fat_have_to_be_a_number";
					}
				}
				$inp_item_fat_metric = round($inp_item_fat_metric, 0);
				$inp_item_fat_metric_mysql = quote_smart($link, $inp_item_fat_metric);

				$inp_item_fat_calculated = $_POST['inp_item_fat_calculated'];
				$inp_item_fat_calculated = output_html($inp_item_fat_calculated);
				$inp_item_fat_calculated = str_replace(",", ".", $inp_item_fat_calculated);
				if(empty($inp_item_fat_calculated)){
					$inp_item_fat_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_calories_calculated))){
						$ft = "error";
						$fm = "fat_have_to_be_a_number";
					}
				}
				$inp_item_fat_calculated = round($inp_item_fat_calculated, 0);
				$inp_item_fat_calculated_mysql = quote_smart($link, $inp_item_fat_calculated);


				// Saturated fat
				if(isset($_POST['inp_item_saturated_fat_metric'])){
					$inp_item_saturated_fat_metric = $_POST['inp_item_saturated_fat_metric'];
				}
				else{
					$inp_item_saturated_fat_metric = "0";
				}
				$inp_item_saturated_fat_metric = output_html($inp_item_saturated_fat_metric);
				$inp_item_saturated_fat_metric = str_replace(",", ".", $inp_item_saturated_fat_metric);
				if(empty($inp_item_saturated_fat_metric)){
					$inp_item_saturated_fat_metric = "0";
				}
				else{
					if(!(is_numeric($inp_item_saturated_fat_metric))){
						$ft = "error";
						$fm = "saturated_fat_metric_have_to_be_a_number";
					}
				}
				$inp_item_saturated_fat_metric = round($inp_item_saturated_fat_metric, 0);
				$inp_item_saturated_fat_metric_mysql = quote_smart($link, $inp_item_saturated_fat_metric);


				$inp_item_saturated_fat_calculated = $_POST['inp_item_saturated_fat_calculated'];
				$inp_item_saturated_fat_calculated = output_html($inp_item_saturated_fat_calculated);
				$inp_item_saturated_fat_calculated = str_replace(",", ".", $inp_item_saturated_fat_calculated);
				if(empty($inp_item_saturated_fat_calculated)){
					$inp_item_saturated_fat_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_saturated_fat_calculated))){
						$ft = "error";
						$fm = "fat_of_which_saturated_fatty_acids_calculated_have_to_be_a_number";
					}
				}
				$inp_item_saturated_fat_calculated = round($inp_item_saturated_fat_calculated, 0);
				$inp_item_saturated_fat_calculated_mysql = quote_smart($link, $inp_item_saturated_fat_calculated);


				// Monounsaturated fat
				if(isset($_POST['inp_item_monounsaturated_fat_metric'])){
					$inp_item_monounsaturated_fat_metric = $_POST['inp_item_monounsaturated_fat_metric'];
				}
				else{
					$inp_item_monounsaturated_fat_metric = "0";
				}
				$inp_item_monounsaturated_fat_metric = output_html($inp_item_monounsaturated_fat_metric);
				$inp_item_monounsaturated_fat_metric = str_replace(",", ".", $inp_item_monounsaturated_fat_metric);
				if(empty($inp_item_monounsaturated_fat_metric)){
					$inp_item_monounsaturated_fat_metric = "0";
				}
				else{
					if(!(is_numeric($inp_item_monounsaturated_fat_metric))){
						$ft = "error";
						$fm = "monounsaturated_fat_metric_have_to_be_a_number";
					}
				}
				$inp_item_monounsaturated_fat_metric = round($inp_item_monounsaturated_fat_metric, 0);
				$inp_item_monounsaturated_fat_metric_mysql = quote_smart($link, $inp_item_monounsaturated_fat_metric);


				$inp_item_monounsaturated_fat_calculated = $_POST['inp_item_monounsaturated_fat_calculated'];
				$inp_item_monounsaturated_fat_calculated = output_html($inp_item_monounsaturated_fat_calculated);
				$inp_item_monounsaturated_fat_calculated = str_replace(",", ".", $inp_item_monounsaturated_fat_calculated);
				if(empty($inp_item_monounsaturated_fat_calculated)){
					$inp_item_monounsaturated_fat_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_monounsaturated_fat_calculated))){
						$ft = "error";
						$fm = "fat_of_which_monounsaturated_fatty_acids_calculated_have_to_be_a_number";
					}
				}
				$inp_item_monounsaturated_fat_calculated = round($inp_item_monounsaturated_fat_calculated, 0);
				$inp_item_monounsaturated_fat_calculated_mysql = quote_smart($link, $inp_item_monounsaturated_fat_calculated);


				// Saturated fat
				if(isset($_POST['inp_item_polyunsaturated_fat_metric'])){
					$inp_item_polyunsaturated_fat_metric = $_POST['inp_item_polyunsaturated_fat_metric'];
				}
				else{
					$inp_item_polyunsaturated_fat_metric = "0";
				}
				$inp_item_polyunsaturated_fat_metric = output_html($inp_item_polyunsaturated_fat_metric);
				$inp_item_polyunsaturated_fat_metric = str_replace(",", ".", $inp_item_polyunsaturated_fat_metric);
				if(empty($inp_item_polyunsaturated_fat_metric)){
					$inp_item_polyunsaturated_fat_metric = "0";
				}
				else{
					if(!(is_numeric($inp_item_polyunsaturated_fat_metric))){
						$ft = "error";
						$fm = "polyunsaturated_fat_metric_have_to_be_a_number";
					}
				}
				$inp_item_polyunsaturated_fat_metric = round($inp_item_polyunsaturated_fat_metric, 0);
				$inp_item_polyunsaturated_fat_metric_mysql = quote_smart($link, $inp_item_polyunsaturated_fat_metric);


				$inp_item_polyunsaturated_fat_calculated = $_POST['inp_item_polyunsaturated_fat_calculated'];
				$inp_item_polyunsaturated_fat_calculated = output_html($inp_item_polyunsaturated_fat_calculated);
				$inp_item_polyunsaturated_fat_calculated = str_replace(",", ".", $inp_item_polyunsaturated_fat_calculated);
				if(empty($inp_item_polyunsaturated_fat_calculated)){
					$inp_item_polyunsaturated_fat_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_polyunsaturated_fat_calculated))){
						$ft = "error";
						$fm = "fat_of_which_polyunsaturated_fatty_acids_calculated_have_to_be_a_number";
					}
				}
				$inp_item_polyunsaturated_fat_calculated = round($inp_item_polyunsaturated_fat_calculated, 0);
				$inp_item_polyunsaturated_fat_calculated_mysql = quote_smart($link, $inp_item_polyunsaturated_fat_calculated);


				// Carbohydrates
				if(isset($_POST['inp_item_carbohydrates_metric'])){
					$inp_item_carbohydrates_metric = $_POST['inp_item_carbohydrates_metric'];
				}
				else{
					$inp_item_carbohydrates_metric = "0";
				}
				$inp_item_carbohydrates_metric = output_html($inp_item_carbohydrates_metric);
				$inp_item_carbohydrates_metric = str_replace(",", ".", $inp_item_carbohydrates_metric);
				if(empty($inp_item_carbohydrates_metric)){
					$inp_item_carbohydrates_metric = "0";
				}
				else{
					if(!(is_numeric($inp_item_carbohydrates_metric))){
						$ft = "error";
						$fm = "carbohydrates_metric_have_to_be_a_number";
					}
				}
				$inp_item_carbohydrates_metric = round($inp_item_carbohydrates_metric, 0);
				$inp_item_carbohydrates_metric_mysql = quote_smart($link, $inp_item_carbohydrates_metric);


				$inp_item_carbohydrates_calculated = $_POST['inp_item_carbohydrates_calculated'];
				$inp_item_carbohydrates_calculated = output_html($inp_item_carbohydrates_calculated);
				$inp_item_carbohydrates_calculated = str_replace(",", ".", $inp_item_carbohydrates_calculated);
				if(empty($inp_item_carbohydrates_calculated)){
					$inp_item_carbohydrates_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_carbohydrates_calculated))){
						$ft = "error";
						$fm = "fat_of_which_carbohydratesty_acids_calculated_have_to_be_a_number";
					}
				}
				$inp_item_carbohydrates_calculated = round($inp_item_carbohydrates_calculated, 0);
				$inp_item_carbohydrates_calculated_mysql = quote_smart($link, $inp_item_carbohydrates_calculated);


				// Carbohydrates of which sugars
				if(isset($_POST['inp_item_carbohydrates_of_which_sugars_metric'])){
					$inp_item_carbohydrates_of_which_sugars_metric = $_POST['inp_item_carbohydrates_of_which_sugars_metric'];
				}
				else{
					$inp_item_carbohydrates_of_which_sugars_metric = "0";
				}
				$inp_item_carbohydrates_of_which_sugars_metric = output_html($inp_item_carbohydrates_of_which_sugars_metric);
				$inp_item_carbohydrates_of_which_sugars_metric = str_replace(",", ".", $inp_item_carbohydrates_of_which_sugars_metric);
				if(empty($inp_item_carbohydrates_of_which_sugars_metric)){
					$inp_item_carbohydrates_of_which_sugars_metric = "0";
				}
				else{
					if(!(is_numeric($inp_item_carbohydrates_of_which_sugars_metric))){
						$ft = "error";
						$fm = "carbohydrates_of_which_sugars_metric_have_to_be_a_number";
					}
				}
				$inp_item_carbohydrates_of_which_sugars_metric = round($inp_item_carbohydrates_of_which_sugars_metric, 0);
				$inp_item_carbohydrates_of_which_sugars_metric_mysql = quote_smart($link, $inp_item_carbohydrates_of_which_sugars_metric);


				$inp_item_carbohydrates_of_which_sugars_calculated = $_POST['inp_item_carbohydrates_of_which_sugars_calculated'];
				$inp_item_carbohydrates_of_which_sugars_calculated = output_html($inp_item_carbohydrates_of_which_sugars_calculated);
				$inp_item_carbohydrates_of_which_sugars_calculated = str_replace(",", ".", $inp_item_carbohydrates_of_which_sugars_calculated);
				if(empty($inp_item_carbohydrates_of_which_sugars_calculated)){
					$inp_item_carbohydrates_of_which_sugars_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_carbohydrates_of_which_sugars_calculated))){
						$ft = "error";
						$fm = "fat_of_which_carbohydrates_of_which_sugarsty_acids_calculated_have_to_be_a_number";
					}
				}
				$inp_item_carbohydrates_of_which_sugars_calculated = round($inp_item_carbohydrates_of_which_sugars_calculated, 0);
				$inp_item_carbohydrates_of_which_sugars_calculated_mysql = quote_smart($link, $inp_item_carbohydrates_of_which_sugars_calculated);




				// Dietary fiber
				if(isset($_POST['inp_item_dietary_fiber_metric'])){
					$inp_item_dietary_fiber_metric = $_POST['inp_item_dietary_fiber_metric'];
				}
				else{
					$inp_item_dietary_fiber_metric = "0";
				}
				$inp_item_dietary_fiber_metric = output_html($inp_item_dietary_fiber_metric);
				$inp_item_dietary_fiber_metric = str_replace(",", ".", $inp_item_dietary_fiber_metric);
				if(empty($inp_item_dietary_fiber_metric)){
					$inp_item_dietary_fiber_metric = "0";
				}
				else{
					if(!(is_numeric($inp_item_dietary_fiber_metric))){
						$ft = "error";
						$fm = "dietary_fiber_metric_have_to_be_a_number";
					}
				}
				$inp_item_dietary_fiber_metric = round($inp_item_dietary_fiber_metric, 0);
				$inp_item_dietary_fiber_metric_mysql = quote_smart($link, $inp_item_dietary_fiber_metric);


				$inp_item_dietary_fiber_calculated = $_POST['inp_item_dietary_fiber_calculated'];
				$inp_item_dietary_fiber_calculated = output_html($inp_item_dietary_fiber_calculated);
				$inp_item_dietary_fiber_calculated = str_replace(",", ".", $inp_item_dietary_fiber_calculated);
				if(empty($inp_item_dietary_fiber_calculated)){
					$inp_item_dietary_fiber_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_dietary_fiber_calculated))){
						$ft = "error";
						$fm = "fat_of_which_dietary_fiberty_acids_calculated_have_to_be_a_number";
					}
				}
				$inp_item_dietary_fiber_calculated = round($inp_item_dietary_fiber_calculated, 0);
				$inp_item_dietary_fiber_calculated_mysql = quote_smart($link, $inp_item_dietary_fiber_calculated);


				// Proteins
				if(isset($_POST['inp_item_proteins_metric'])){
					$inp_item_proteins_metric = $_POST['inp_item_proteins_metric'];
				}
				else{
					$inp_item_proteins_metric = "0";
				}
				$inp_item_proteins_metric = output_html($inp_item_proteins_metric);
				$inp_item_proteins_metric = str_replace(",", ".", $inp_item_proteins_metric);
				if(empty($inp_item_proteins_metric)){
					$inp_item_proteins_metric = "0";
				}
				else{
					if(!(is_numeric($inp_item_proteins_metric))){
						$ft = "error";
						$fm = "proteins_metric_have_to_be_a_number";
					}
				}
				$inp_item_proteins_metric = round($inp_item_proteins_metric, 0);
				$inp_item_proteins_metric_mysql = quote_smart($link, $inp_item_proteins_metric);


				$inp_item_proteins_calculated = $_POST['inp_item_proteins_calculated'];
				$inp_item_proteins_calculated = output_html($inp_item_proteins_calculated);
				$inp_item_proteins_calculated = str_replace(",", ".", $inp_item_proteins_calculated);
				if(empty($inp_item_proteins_calculated)){
					$inp_item_proteins_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_proteins_calculated))){
						$ft = "error";
						$fm = "fat_of_which_proteinsty_acids_calculated_have_to_be_a_number";
					}
				}
				$inp_item_proteins_calculated = round($inp_item_proteins_calculated, 0);
				$inp_item_proteins_calculated_mysql = quote_smart($link, $inp_item_proteins_calculated);



				// Salt
				if(isset($_POST['inp_item_salt_metric'])){
					$inp_item_salt_metric = $_POST['inp_item_salt_metric'];
				}
				else{
					$inp_item_salt_metric = "0";
				}
				$inp_item_salt_metric = output_html($inp_item_salt_metric);
				$inp_item_salt_metric = str_replace(",", ".", $inp_item_salt_metric);
				if(empty($inp_item_salt_metric)){
					$inp_item_salt_metric = "0";
				}
				else{
					if(!(is_numeric($inp_item_salt_metric))){
						$ft = "error";
						$fm = "salt_metric_have_to_be_a_number";
					}
				}
				$inp_item_salt_metric = round($inp_item_salt_metric, 0);
				$inp_item_salt_metric_mysql = quote_smart($link, $inp_item_salt_metric);


				$inp_item_salt_calculated = $_POST['inp_item_salt_calculated'];
				$inp_item_salt_calculated = output_html($inp_item_salt_calculated);
				$inp_item_salt_calculated = str_replace(",", ".", $inp_item_salt_calculated);
				if(empty($inp_item_salt_calculated)){
					$inp_item_salt_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_salt_calculated))){
						$ft = "error";
						$fm = "fat_of_which_saltty_acids_calculated_have_to_be_a_number";
					}
				}
				$inp_item_salt_calculated = round($inp_item_salt_calculated, 0);
				$inp_item_salt_calculated_mysql = quote_smart($link, $inp_item_salt_calculated);


				// Sodium
				if(isset($_POST['inp_item_sodium_metric'])){
					$inp_item_sodium_metric = $_POST['inp_item_sodium_metric'];
				}
				else{
					$inp_item_sodium_metric = "0";
				}
				$inp_item_sodium_metric = output_html($inp_item_sodium_metric);
				$inp_item_sodium_metric = str_replace(",", ".", $inp_item_sodium_metric);
				if(empty($inp_item_sodium_metric)){
					$inp_item_sodium_metric = "0";
				}
				else{
					if(!(is_numeric($inp_item_sodium_metric))){
						$ft = "error";
						$fm = "sodium_metric_have_to_be_a_number";
					}
				}
				$inp_item_sodium_metric = round($inp_item_sodium_metric, 0);
				$inp_item_sodium_metric_mysql = quote_smart($link, $inp_item_sodium_metric);


				$inp_item_sodium_calculated = $_POST['inp_item_sodium_calculated'];
				$inp_item_sodium_calculated = output_html($inp_item_sodium_calculated);
				$inp_item_sodium_calculated = str_replace(",", ".", $inp_item_sodium_calculated);
				if(empty($inp_item_sodium_calculated)){
					$inp_item_sodium_calculated = "0";
				}
				else{
					if(!(is_numeric($inp_item_sodium_calculated))){
						$ft = "error";
						$fm = "fat_of_which_sodiumty_acids_calculated_have_to_be_a_number";
					}
				}
				$inp_item_sodium_calculated = round($inp_item_sodium_calculated, 0);
				$inp_item_sodium_calculated_mysql = quote_smart($link, $inp_item_sodium_calculated);




					// Update
					$result = mysqli_query($link, "UPDATE $t_recipes_items SET 
								item_amount=$inp_item_amount_mysql, 
								item_measurement=$inp_item_measurement_mysql, 
								item_grocery=$inp_item_grocery_mysql, 
								item_food_id=$inp_item_food_id_mysql, 


								item_energy_metric=$inp_item_calories_metric_mysql, 
								item_fat_metric=$inp_item_fat_metric_mysql,  
								item_saturated_fat_metric=$inp_item_saturated_fat_metric_mysql, 
								item_monounsaturated_fat_metric=$inp_item_monounsaturated_fat_metric_mysql, 
								item_polyunsaturated_fat_metric=$inp_item_polyunsaturated_fat_metric_mysql, 
								item_cholesterol_metric=0,
								item_carbohydrates_metric=$inp_item_carbohydrates_metric_mysql, 
								item_carbohydrates_of_which_sugars_metric=$inp_item_carbohydrates_of_which_sugars_metric_mysql,  
								item_dietary_fiber_metric=$inp_item_dietary_fiber_metric_mysql, 
								item_proteins_metric=$inp_item_proteins_metric_mysql, 
								item_salt_metric=$inp_item_salt_metric_mysql, 
								item_sodium_metric=$inp_item_sodium_metric_mysql, 

								item_energy_calculated=$inp_item_calories_calculated_mysql, 
								item_fat_calculated=$inp_item_fat_calculated_mysql,  
								item_saturated_fat_calculated=$inp_item_saturated_fat_calculated_mysql, 
								item_monounsaturated_fat_calculated=$inp_item_monounsaturated_fat_calculated_mysql, 
								item_polyunsaturated_fat_calculated=$inp_item_polyunsaturated_fat_calculated_mysql, 
								item_cholesterol_calculated=0,
								item_carbohydrates_calculated=$inp_item_carbohydrates_calculated_mysql, 
								item_carbohydrates_of_which_sugars_calculated=$inp_item_carbohydrates_of_which_sugars_calculated_mysql,  
								item_dietary_fiber_calculated=$inp_item_dietary_fiber_calculated_mysql, 
								item_proteins_calculated=$inp_item_proteins_calculated_mysql, 
								item_salt_calculated=$inp_item_salt_calculated_mysql, 
								item_sodium_calculated=$inp_item_sodium_calculated_mysql
								 WHERE item_id=$get_item_id") or die(mysqli_error($link));


				
			

				// Calculating total numbers
				$inp_number_energy_metric		= 0;
				$inp_number_fat_metric	 		= 0;
				$inp_number_saturated_fat_metric	= 0;
				$inp_number_monounsaturated_fat_metric	= 0;
				$inp_number_polyunsaturated_fat_metric	= 0;
				$inp_number_cholesterol_metric	 	= 0;
				$inp_number_carbohydrates_metric	= 0;
				$inp_number_carbohydrates_of_which_sugars_metric  = 0;
				$inp_number_dietary_fiber_metric	= 0;
				$inp_number_proteins_metric	 	= 0;
				$inp_number_salt_metric			= 0;
				$inp_number_sodium_metric		= 0;

				$inp_number_energy_serving		= 0;
				$inp_number_fat_serving			= 0;
				$inp_number_saturated_fat_serving	= 0;
				$inp_number_monounsaturated_fat_serving	= 0;
				$inp_number_polyunsaturated_fat_serving	= 0;
				$inp_number_cholesterol_serving		= 0;
				$inp_number_carbohydrates_serving	= 0;
				$inp_number_carbohydrates_of_which_sugars_serving	 = 0;
				$inp_number_dietary_fiber_serving	= 0;
				$inp_number_proteins_serving	 	= 0;
				$inp_number_salt_serving		= 0;
				$inp_number_sodium_serving		= 0;

				$inp_number_energy_total		= 0;
				$inp_number_fat_total			= 0;
				$inp_number_saturated_fat_total	 	= 0;
				$inp_number_monounsaturated_fat_total	= 0;
				$inp_number_polyunsaturated_fat_total	= 0;
				$inp_number_cholesterol_total		= 0;
				$inp_number_carbohydrates_total		= 0;
				$inp_number_carbohydrates_of_which_sugars_total = 0;
				$inp_number_dietary_fiber_total		= 0;
				$inp_number_proteins_total		= 0;
				$inp_number_salt_total			= 0;
				$inp_number_sodium_total		= 0;

					
				$query_groups = "SELECT group_id, group_title FROM $t_recipes_groups WHERE group_recipe_id=$get_recipe_id";
				$result_groups = mysqli_query($link, $query_groups);
				while($row_groups = mysqli_fetch_row($result_groups)) {
					list($get_group_id, $get_group_title) = $row_groups;

					$query_items = "SELECT item_id, item_recipe_id, item_group_id, item_amount, item_measurement, item_grocery, item_grocery_explanation, item_food_id, item_energy_metric, item_fat_metric, item_saturated_fat_metric, item_monounsaturated_fat_metric, item_polyunsaturated_fat_metric, item_cholesterol_metric, item_carbohydrates_metric, item_carbohydrates_of_which_sugars_metric, item_dietary_fiber_metric, item_proteins_metric, item_salt_metric, item_sodium_metric, item_energy_calculated, item_fat_calculated, item_saturated_fat_calculated, item_monounsaturated_fat_calculated, item_polyunsaturated_fat_calculated, item_cholesterol_calculated, item_carbohydrates_calculated, item_carbohydrates_of_which_sugars_calculated, item_dietary_fiber_calculated, item_proteins_calculated, item_salt_calculated, item_sodium_calculated FROM $t_recipes_items WHERE item_group_id=$get_group_id";
					$result_items = mysqli_query($link, $query_items);
					$row_cnt = mysqli_num_rows($result_items);
					while($row_items = mysqli_fetch_row($result_items)) {
						list($get_item_id, $get_item_recipe_id, $get_item_group_id, $get_item_amount, $get_item_measurement, $get_item_grocery, $get_item_grocery_explanation, $get_item_food_id, $get_item_energy_metric, $get_item_fat_metric, $get_item_saturated_fat_metric, $get_item_monounsaturated_fat_metric, $get_item_polyunsaturated_fat_metric, $get_item_cholesterol_metric, $get_item_carbohydrates_metric, $get_item_carbohydrates_of_which_sugars_metric, $get_item_dietary_fiber_metric, $get_item_proteins_metric, $get_item_salt_metric, $get_item_sodium_metric, $get_item_energy_calculated, $get_item_fat_calculated, $get_item_saturated_fat_calculated, $get_item_monounsaturated_fat_calculated, $get_item_polyunsaturated_fat_calculated, $get_item_cholesterol_calculated, $get_item_carbohydrates_calculated, $get_item_carbohydrates_of_which_sugars_calculated, $get_item_dietary_fiber_calculated, $get_item_proteins_calculated, $get_item_salt_calculated, $get_item_sodium_calculated) = $row_items;



						$inp_number_energy_metric		= $inp_number_energy_metric+$get_item_energy_metric;
						$inp_number_fat_metric	 		= $inp_number_fat_metric+$get_item_fat_metric;
						$inp_number_saturated_fat_metric	= $inp_number_saturated_fat_metric+$get_item_saturated_fat_metric;
						$inp_number_monounsaturated_fat_metric	= $inp_number_monounsaturated_fat_metric+$get_item_monounsaturated_fat_metric;
						$inp_number_polyunsaturated_fat_metric	= $inp_number_polyunsaturated_fat_metric+$get_item_polyunsaturated_fat_metric;
						$inp_number_cholesterol_metric	 	= $inp_number_cholesterol_metric+$get_item_cholesterol_metric;
						$inp_number_carbohydrates_metric	= $inp_number_carbohydrates_metric+$get_item_carbohydrates_metric;
						$inp_number_carbohydrates_of_which_sugars_metric  = $inp_number_carbohydrates_of_which_sugars_metric+$get_item_carbohydrates_of_which_sugars_metric;
						$inp_number_dietary_fiber_metric	= $inp_number_dietary_fiber_metric+$get_item_dietary_fiber_metric;
						$inp_number_proteins_metric	 	= $inp_number_proteins_metric+$get_item_proteins_metric;
						$inp_number_salt_metric			= $inp_number_salt_metric+$get_item_salt_metric;
						$inp_number_sodium_metric		= $inp_number_sodium_metric+$get_item_sodium_metric;

						$inp_number_energy_total		= $inp_number_energy_total+$get_item_energy_calculated;
						$inp_number_fat_total			= $inp_number_fat_total+$get_item_fat_calculated;
						$inp_number_saturated_fat_total	 	= $inp_number_saturated_fat_total+$get_item_saturated_fat_calculated;
						$inp_number_monounsaturated_fat_total	= $inp_number_monounsaturated_fat_total+$get_item_monounsaturated_fat_calculated;
						$inp_number_polyunsaturated_fat_total	= $inp_number_polyunsaturated_fat_total+$get_item_polyunsaturated_fat_calculated;
						$inp_number_cholesterol_total		= $inp_number_cholesterol_total+$get_item_cholesterol_calculated;
						$inp_number_carbohydrates_total		= $inp_number_carbohydrates_total+$get_item_carbohydrates_calculated;
						$inp_number_carbohydrates_of_which_sugars_total = $inp_number_carbohydrates_of_which_sugars_total+$get_item_carbohydrates_of_which_sugars_calculated;
						$inp_number_dietary_fiber_total		= $inp_number_dietary_fiber_total+$get_item_dietary_fiber_calculated;
						$inp_number_proteins_total		= $inp_number_proteins_total+$get_item_proteins_calculated;
						$inp_number_salt_total			= $inp_number_salt_total+$get_item_salt_calculated;
						$inp_number_sodium_total		= $inp_number_sodium_total+$get_item_sodium_calculated;

					


	
					} // items
				} // groups
					
				

	
				// Numbers : Per hundred
				$inp_number_energy_metric_mysql			= quote_smart($link, $inp_number_energy_metric);
				$inp_number_fat_metric_mysql 			= quote_smart($link, $inp_number_fat_metric);
				$inp_number_saturated_fat_metric_mysql		= quote_smart($link, $inp_number_saturated_fat_metric);
				$inp_number_monounsaturated_fat_metric_mysql	= quote_smart($link, $inp_number_monounsaturated_fat_metric);
				$inp_number_polyunsaturated_fat_metric_mysql	= quote_smart($link, $inp_number_polyunsaturated_fat_metric);
				$inp_number_cholesterol_metric_mysql	 	= quote_smart($link, $inp_number_cholesterol_metric);
				$inp_number_carbohydrates_metric_mysql		= quote_smart($link, $inp_number_carbohydrates_metric);
				$inp_number_carbohydrates_of_which_sugars_metric_mysql  = quote_smart($link, $inp_number_carbohydrates_of_which_sugars_metric);
				$inp_number_dietary_fiber_metric_mysql		= quote_smart($link, $inp_number_dietary_fiber_metric);
				$inp_number_proteins_metric_mysql	 	= quote_smart($link, $inp_number_proteins_metric);
				$inp_number_salt_metric_mysql			= quote_smart($link, $inp_number_salt_metric);
				$inp_number_sodium_metric_mysql			= quote_smart($link, $inp_number_sodium_metric);


					
				// Numbers : Total 
				$inp_number_energy_total_mysql			= quote_smart($link, $inp_number_energy_total);
				$inp_number_fat_total_mysql			= quote_smart($link, $inp_number_fat_total);
				$inp_number_saturated_fat_total_mysql	 	= quote_smart($link, $inp_number_saturated_fat_total);
				$inp_number_monounsaturated_fat_total_mysql	= quote_smart($link, $inp_number_monounsaturated_fat_total);
				$inp_number_polyunsaturated_fat_total_mysql	= quote_smart($link, $inp_number_polyunsaturated_fat_total);
				$inp_number_cholesterol_total_mysql		= quote_smart($link, $inp_number_cholesterol_total);
				$inp_number_carbohydrates_total_mysql		= quote_smart($link, $inp_number_carbohydrates_total);
				$inp_number_carbohydrates_of_which_sugars_total_mysql = quote_smart($link, $inp_number_carbohydrates_of_which_sugars_total);
				$inp_number_dietary_fiber_total_mysql		= quote_smart($link, $inp_number_dietary_fiber_total);
				$inp_number_proteins_total_mysql		= quote_smart($link, $inp_number_proteins_total);
				$inp_number_salt_total_mysql			= quote_smart($link, $inp_number_salt_total);
				$inp_number_sodium_total_mysql			= quote_smart($link, $inp_number_sodium_total);


				// Numbers : Per serving
				$inp_number_energy_serving	 = round($inp_number_energy_total/$get_number_servings);
				$inp_number_energy_serving_mysql = quote_smart($link, $inp_number_energy_serving);

				$inp_number_fat_serving	 = round($inp_number_fat_total/$get_number_servings);
				$inp_number_fat_serving_mysql = quote_smart($link, $inp_number_fat_serving);

				$inp_number_saturated_fat_serving	 = round($inp_number_saturated_fat_total/$get_number_servings);
				$inp_number_saturated_fat_serving_mysql = quote_smart($link, $inp_number_saturated_fat_serving);

				$inp_number_monounsaturated_fat_serving	 = round($inp_number_monounsaturated_fat_total/$get_number_servings);
				$inp_number_monounsaturated_fat_serving_mysql = quote_smart($link, $inp_number_monounsaturated_fat_serving);

				$inp_number_polyunsaturated_fat_serving	 = round($inp_number_polyunsaturated_fat_total/$get_number_servings);
				$inp_number_polyunsaturated_fat_serving_mysql = quote_smart($link, $inp_number_polyunsaturated_fat_serving);

				$inp_number_cholesterol_serving	 = round($inp_number_cholesterol_total/$get_number_servings);
				$inp_number_cholesterol_serving_mysql = quote_smart($link, $inp_number_cholesterol_serving);

				$inp_number_carbohydrates_serving	 = round($inp_number_carbohydrates_total/$get_number_servings);
				$inp_number_carbohydrates_serving_mysql = quote_smart($link, $inp_number_carbohydrates_serving);

				$inp_number_carbohydrates_of_which_sugars_serving	 = round($inp_number_carbohydrates_of_which_sugars_total/$get_number_servings);
				$inp_number_carbohydrates_of_which_sugars_serving_mysql = quote_smart($link, $inp_number_carbohydrates_of_which_sugars_serving);

				$inp_number_dietary_fiber_serving	 = round($inp_number_dietary_fiber_total/$get_number_servings);
				$inp_number_dietary_fiber_serving_mysql = quote_smart($link, $inp_number_dietary_fiber_serving);

				$inp_number_proteins_serving	 = round($inp_number_proteins_total/$get_number_servings);
				$inp_number_proteins_serving_mysql = quote_smart($link, $inp_number_proteins_serving);

				$inp_number_salt_serving	 = round($inp_number_salt_total/$get_number_servings);
				$inp_number_salt_serving_mysql = quote_smart($link, $inp_number_salt_serving);

				$inp_number_sodium_serving	 = round($inp_number_sodium_total/$get_number_servings);
				$inp_number_sodium_serving_mysql = quote_smart($link, $inp_number_sodium_serving);



				$result = mysqli_query($link, "UPDATE $t_recipes_numbers SET 

								number_energy_metric=$inp_number_energy_metric_mysql, 
								number_fat_metric=$inp_number_fat_metric_mysql, 
								number_saturated_fat_metric=$inp_number_saturated_fat_metric_mysql, 
								number_monounsaturated_fat_metric=$inp_number_monounsaturated_fat_metric_mysql, 
								number_polyunsaturated_fat_metric=$inp_number_polyunsaturated_fat_metric_mysql, 
								number_cholesterol_metric=$inp_number_cholesterol_metric_mysql, 
								number_carbohydrates_metric=$inp_number_carbohydrates_metric_mysql, 
								number_carbohydrates_of_which_sugars_metric=$inp_number_carbohydrates_of_which_sugars_metric_mysql, 
								number_dietary_fiber_metric=$inp_number_dietary_fiber_metric_mysql, 
								number_proteins_metric=$inp_number_proteins_metric_mysql, 
								number_salt_metric=$inp_number_salt_metric_mysql, 
								number_sodium_metric=$inp_number_sodium_metric_mysql, 

								number_energy_serving=$inp_number_energy_serving_mysql, 
								number_fat_serving=$inp_number_fat_serving_mysql, 
								number_saturated_fat_serving=$inp_number_saturated_fat_serving_mysql, 
								number_monounsaturated_fat_serving=$inp_number_monounsaturated_fat_serving_mysql, 
								number_polyunsaturated_fat_serving=$inp_number_polyunsaturated_fat_serving_mysql, 
								number_cholesterol_serving=$inp_number_cholesterol_serving_mysql, 
								number_carbohydrates_serving=$inp_number_carbohydrates_serving_mysql, 
								number_carbohydrates_of_which_sugars_serving=$inp_number_carbohydrates_of_which_sugars_serving_mysql, 
								number_dietary_fiber_serving=$inp_number_dietary_fiber_serving_mysql, 
								number_proteins_serving=$inp_number_proteins_serving_mysql, 
								number_salt_serving=$inp_number_salt_serving_mysql, 

								number_sodium_serving=$inp_number_sodium_serving_mysql, 
								number_energy_total=$inp_number_energy_total_mysql, 
								number_fat_total=$inp_number_fat_total_mysql, 
								number_saturated_fat_total=$inp_number_saturated_fat_total_mysql, 
								number_monounsaturated_fat_total=$inp_number_monounsaturated_fat_total_mysql, 
								number_polyunsaturated_fat_total=$inp_number_polyunsaturated_fat_total_mysql, 
								number_cholesterol_total=$inp_number_cholesterol_total_mysql, 
								number_carbohydrates_total=$inp_number_carbohydrates_total_mysql, 
								number_carbohydrates_of_which_sugars_total=$inp_number_carbohydrates_of_which_sugars_total_mysql, 
								number_dietary_fiber_total=$inp_number_dietary_fiber_total_mysql, 
								number_proteins_total=$inp_number_proteins_total_mysql, 
								number_salt_total=$inp_number_salt_total_mysql, 
								number_sodium_total=$inp_number_sodium_total_mysql

					 WHERE number_recipe_id=$recipe_id_mysql") or die(mysqli_error($link));



					// Header
					$ft = "success";
					$fm = "changes_saved_for_$get_item_grocery";

					$url = "submit_recipe_step_2_group_and_elements.php?action=edit_item&recipe_id=$get_recipe_id&group_id=$get_group_id&item_id=$get_item_id&l=$l";
					$url = $url . "&ft=$ft&fm=$fm";
					header("Location: $url");
					exit;	

				

				}
				echo"
				<h1>$l_edit_ingredients</h1>
				<h2>$get_group_title</h2>



				<!-- Feedback -->
				";
				if($ft != ""){
					if($fm == "changes_saved"){
						$fm = "$l_changes_saved";
					}
					elseif($fm == "amound_cant_be_empty"){
						$fm = "$l_amound_cant_be_empty";
					}
					elseif($fm == "amound_has_to_be_a_number"){
						$fm = "$l_amound_has_to_be_a_number";
					}
					elseif($fm == "measurement_cant_be_empty"){
						$fm = "$l_measurement_cant_be_empty";
					}
					elseif($fm == "grocery_cant_be_empty"){
						$fm = "$l_grocery_cant_be_empty";
					}
					elseif($fm == "calories_cant_be_empty"){
						$fm = "$l_calories_cant_be_empty";
					}
					elseif($fm == "proteins_cant_be_empty"){
						$fm = "$l_proteins_cant_be_empty";
					}
					elseif($fm == "fat_cant_be_empty"){
						$fm = "$l_fat_cant_be_empty";
					}
					elseif($fm == "carbs_cant_be_empty"){
						$fm = "$l_carbs_cant_be_empty";
					}
					elseif($fm == "calories_have_to_be_a_number"){
						$fm = "$l_calories_have_to_be_a_number";
					}
					elseif($fm == "proteins_have_to_be_a_number"){
						$fm = "$l_proteins_have_to_be_a_number";
					}
					elseif($fm == "carbs_have_to_be_a_number"){
						$fm = "$l_carbs_have_to_be_a_number";
					}
					elseif($fm == "fat_have_to_be_a_number"){
						$fm = "$l_fat_have_to_be_a_number";
					}
					else{
						$fm = ucfirst($fm);
					}
					echo"<div class=\"$ft\"><span>$fm</span></div>";
				}
				echo"	
				<!-- //Feedback -->

				<!-- Edit item -->
					<!-- Focus -->
						<script>
						\$(document).ready(function(){
							\$('[name=\"inp_item_amount\"]').focus();
						});
						</script>
					<!-- //Focus -->

				
					<form method=\"post\" action=\"submit_recipe_step_2_group_and_elements.php?action=$action&amp;recipe_id=$get_recipe_id&amp;group_id=$get_group_id&amp;item_id=$get_item_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			

					<h2 style=\"padding-bottom:0;margin-bottom:0;\">$l_food</h2>
					<table>
					 <tbody>
					  <tr>
					   <td style=\"padding: 0px 20px 0px 0px;\">
						<p>$l_amount<br />
						<input type=\"text\" name=\"inp_item_amount\" id=\"inp_item_amount\" size=\"3\" value=\"$get_item_amount\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						</p>
					   </td>
					   <td>
						<p>$l_measurement<br />
						<input type=\"text\" name=\"inp_item_measurement\" size=\"3\" value=\"$get_item_measurement\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						</p>
					   </td>
					  </tr>
					</table>
					<p>$l_grocery &middot; <a href=\"$root/food/new_food.php?l=$l\" target=\"_blank\">$l_new_food</a><br />
					<input type=\"text\" name=\"inp_item_grocery\" class=\"inp_item_grocery\" id=\"inp_item_grocery\" size=\"25\" value=\"$get_item_grocery\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					<input type=\"hidden\" name=\"inp_item_food_id\" id=\"inp_item_food_id\" /></p>

					<!-- Special character replacer -->
						<script>

						\$(document).ready(function(){
							window.setInterval(function(){
								var inp_item_grocery = \$(\".inp_item_grocery\").val();
								var inp_item_grocery = inp_item_grocery.replace(\"&aring;\", \"å\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&aelig;\", \"æ\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&Aring;\", \"Å\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&Aelig;\", \"Æ\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#192;\", \"À\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#193;\", \"Á\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#194;\", \"Â\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#195;\", \"Ã\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#196;\", \"Ä\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#197;\", \"Å\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#198;\", \"Æ\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#199;\", \"Ç\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#200;\", \"È\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#201;\", \"É\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#202;\", \"Ê\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#203;\", \"Ë\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#204;\", \"Ì\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#205;\", \"Í\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#206;\", \"Î\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#207;\", \"Ï\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#208;\", \"Ð\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#209;\", \"Ñ\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#210;\", \"Ò\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#211;\", \"Ó\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#212;\", \"Ô\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#213;\", \"Õ\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#214;\", \"Ö\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#215;\", \"×\");   
								var inp_item_grocery = inp_item_grocery.replace(\"&#216;\", \"Ø\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&Oslash;\", \"Ø\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&oslash;\", \"ø\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#217;\", \"Ù\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#218;\", \"Ú\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#219;\", \"Û\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#220;\", \"Ü\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#221;\", \"Ý\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#222;\", \"Þ\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#223;\", \"ß\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#224;\", \"à\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#225;\", \"á\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#226;\", \"â\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#227;\", \"ã\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#228;\", \"ä\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#229;\", \"å\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#230;\", \"æ\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#231;\", \"ç\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#232;\", \"è\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#233;\", \"é\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#234;\", \"ê\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#235;\", \"ë\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#236;\", \"ì\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#237;\", \"í\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#238;\", \"î\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#239;\", \"ï\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#240;\", \"ð\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#241;\", \"ñ\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&ntilde;\", \"ñ\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#242;\", \"ò\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#243;\", \"ó\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#244;\", \"ô\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#245;\", \"õ\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#246;\", \"ö\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#247;\", \"÷\");  
								var inp_item_grocery = inp_item_grocery.replace(\"&#248;\", \"ø\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#249;\", \"ù\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#250;\", \"ú\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#251;\", \"û\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#252;\", \"ü\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#253;\", \"ý\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#254;\", \"þ\"); 
								var inp_item_grocery = inp_item_grocery.replace(\"&#255;\", \"ÿ\"); 

								var inp_item_grocery = inp_item_grocery.replace(\"&#039;\", \"'\"); 

								\$(\"#inp_item_grocery\").val(inp_item_grocery);
								
							}, 1000);

							
						});
						</script>

					<!-- //Special character replacer -->


					<!-- User adapted view -->";
						if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
							$my_user_id = $_SESSION['user_id'];
							$my_user_id = output_html($my_user_id);
							$my_user_id_mysql = quote_smart($link, $my_user_id);
			
							$query_t = "SELECT view_id, view_user_id, view_ip, view_year, view_system, view_hundred_metric, view_serving, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_recipes_user_adapted_view WHERE view_user_id=$get_my_user_id";
							$result_t = mysqli_query($link, $query_t);
							$row_t = mysqli_fetch_row($result_t);
							list($get_current_view_id, $get_current_view_user_id, $get_current_view_ip, $get_current_view_year, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_serving, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
						}
						else{
							// IP
							$my_user_ip = $_SERVER['REMOTE_ADDR'];
							$my_user_ip = output_html($my_user_ip);
							$my_user_ip_mysql = quote_smart($link, $my_user_ip);
			
							$query_t = "SELECT view_id, view_user_id, view_ip, view_year, view_system, view_hundred_metric, view_serving, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_recipes_user_adapted_view WHERE view_ip=$my_user_ip_mysql";
							$result_t = mysqli_query($link, $query_t);
							$row_t = mysqli_fetch_row($result_t);
							list($get_current_view_id, $get_current_view_user_id, $get_current_view_ip, $get_current_view_year, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_serving, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
						}
						if($get_current_view_hundred_metric == ""){
							$get_current_view_hundred_metric = "1";
						}
						echo"
						<p><a id=\"adapter_view\"></a>
						<input type=\"checkbox\" name=\"inp_show_hundred_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=hundred_metric&amp;process=1&amp;referer=submit_recipe_step_2_group_and_elements&amp;action=$action&amp;recipe_id=$recipe_id&amp;group_id=$group_id&amp;l=$l\""; if($get_current_view_hundred_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_hundred
						<input type=\"checkbox\" name=\"inp_show_pcs_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=pcs_metric&amp;process=1&amp;referer=submit_recipe_step_2_group_and_elements&amp;action=$action&amp;recipe_id=$recipe_id&amp;group_id=$group_id&amp;l=$l\""; if($get_current_view_pcs_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_pcs_g
						<input type=\"checkbox\" name=\"inp_show_eight_us\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=eight_us&amp;process=1&amp;referer=submit_recipe_step_2_group_and_elements&amp;action=$action&amp;recipe_id=$recipe_id&amp;group_id=$group_id&amp;l=$l\""; if($get_current_view_eight_us == "1"){ echo" checked=\"checked\""; } echo" /> $l_eight
						<input type=\"checkbox\" name=\"inp_show_pcs_us\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=pcs_us&amp;process=1&amp;referer=submit_recipe_step_2_group_and_elements&amp;action=$action&amp;recipe_id=$recipe_id&amp;group_id=$group_id&amp;l=$l\""; if($get_current_view_pcs_us == "1"){ echo" checked=\"checked\""; } echo" /> $l_pcs_oz
						</p>

						<!-- On check go to URL -->
						<script>
						\$(function() {
							\$(\".onclick_go_to_url\").change(function(){
								var item=\$(this);
								window.location.href= item.data(\"target\")
							});
  						});
						</script>
						<!-- //On check go to URL -->
					<!-- //User adapted view -->

					<div id=\"nettport_search_results\">
					</div><div class=\"clear\"></div>




					<h2 style=\"padding-bottom:0;margin-bottom:0;\">$l_numbers</h2>
					<table class=\"hor-zebra\" style=\"width: 350px\">
					 <thead>
					  <tr>
					   <th scope=\"col\">
					   </th>
					   <th scope=\"col\" style=\"text-align: center;padding: 6px 4px 6px 4px;vertical-align: bottom;\">
						<span>$l_per_hundred</span>
					   </th>
					   <th scope=\"col\" style=\"text-align: center;padding: 6px 4px 6px 4px;vertical-align: bottom;\">
						<span>$l_calculated</span>
					   </th>
					  </tr>
					 </thead>

				 <tbody>
				  <tr>
				   <td style=\"padding: 8px 4px 6px 8px;\">
					<span>$l_calories</span>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<span><input type=\"text\" name=\"inp_item_calories_metric\" id=\"inp_item_calories_metric\" size=\"5\" value=\"$get_item_energy_metric\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></span>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<span><input type=\"text\" name=\"inp_item_calories_calculated\" id=\"inp_item_calories_calculated\" size=\"5\" value=\"$get_item_energy_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></span>
				   </td>
				  </tr>

				  <tr>
				   <td style=\"padding: 8px 4px 6px 8px;\">
					<p style=\"margin:0;padding: 0px 0px 4px 0px;\">$l_fat</p>
					<p style=\"margin:0;padding: 0;\">$l_dash_saturated_fat</p>
					<p style=\"margin:0;padding: 0;\">$l_dash_monounsaturated_fat</p>
					<p style=\"margin:0;padding: 0;\">$l_dash_polyunsaturated_fat</p>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<p style=\"margin:0;padding: 0px 0px 4px 0px;\"><input type=\"text\" name=\"inp_item_fat_metric\" id=\"inp_item_fat_metric\" size=\"5\" value=\"$get_item_fat_metric\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_saturated_fat_metric\" id=\"inp_item_saturated_fat_metric\" size=\"5\" value=\"$get_item_saturated_fat_metric\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_monounsaturated_fat_metric\" id=\"inp_item_monounsaturated_fat_metric\" size=\"5\" value=\"$get_item_monounsaturated_fat_metric\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_polyunsaturated_fat_metric\" id=\"inp_item_polyunsaturated_fat_metric\" size=\"5\" value=\"$get_item_polyunsaturated_fat_metric\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<p style=\"margin:0;padding: 0px 0px 4px 0px;\"><input type=\"text\" name=\"inp_item_fat_calculated\" id=\"inp_item_fat_calculated\" size=\"5\" value=\"$get_item_fat_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_saturated_fat_calculated\" id=\"inp_item_saturated_fat_calculated\" size=\"5\" value=\"$get_item_saturated_fat_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_monounsaturated_fat_calculated\" id=\"inp_item_monounsaturated_fat_calculated\" size=\"5\" value=\"$get_item_monounsaturated_fat_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_polyunsaturated_fat_calculated\" id=\"inp_item_saturated_fat_calculated\" size=\"5\" value=\"$get_item_saturated_fat_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
				   </td>
				  </tr>

				  <tr>
		 		   <td style=\"padding: 8px 4px 6px 8px;\">
					<p style=\"margin:0;padding: 0px 0px 4px 0px;\">$l_carbs</p>
					<p style=\"margin:0;padding: 0;\">$l_dash_of_which_sugars_calculated</p>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<p style=\"margin:0;padding: 0px 0px 4px 0px;\"><input type=\"text\" name=\"inp_item_carbohydrates_metric\" id=\"inp_item_carbohydrates_metric\" size=\"5\" value=\"$get_item_carbohydrates_metric\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_carbohydrates_of_which_sugars_metric\" id=\"inp_item_carbohydrates_of_which_sugars_metric\" size=\"5\" value=\"$get_item_carbohydrates_of_which_sugars_metric\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<p style=\"margin:0;padding: 0px 0px 4px 0px;\"><input type=\"text\" name=\"inp_item_carbohydrates_calculated\" id=\"inp_item_carbohydrates_calculated\" size=\"5\" value=\"$get_item_carbohydrates_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_carbohydrates_of_which_sugars_calculated\" id=\"inp_item_carbohydrates_of_which_sugars_calculated\" size=\"5\" value=\"$get_item_carbohydrates_of_which_sugars_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
				   </td>
				  </tr>

				 <tr>
	 			  <td style=\"padding: 8px 4px 6px 8px;\">
					<p style=\"margin:0;padding: 0;\">$l_dietary_fiber</p>
				   </td>
				 	  <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
						<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_dietary_fiber_metric\" id=\"inp_item_dietary_fiber_metric\" size=\"5\" value=\"$get_item_dietary_fiber_metric\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
					   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<p style=\"margin:0;padding: 0;\"><input type=\"text\" name=\"inp_item_dietary_fiber_calculated\" id=\"inp_item_dietary_fiber_calculated\" size=\"5\" value=\"$get_item_dietary_fiber_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
				   </td>
				  </tr>


				  <tr>
				   <td style=\"padding: 8px 4px 6px 8px;\">
					<span>$l_proteins</span>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<span><input type=\"text\" name=\"inp_item_proteins_metric\" id=\"inp_item_proteins_metric\" size=\"5\" value=\"$get_item_proteins_metric\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></span>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<span><input type=\"text\" name=\"inp_item_proteins_calculated\" id=\"inp_item_proteins_calculated\" size=\"5\" value=\"$get_item_proteins_calculated\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></span>
				   </td>
				 </tr>

				  <tr>
				   <td style=\"padding: 8px 4px 6px 8px;\">
					<span>$l_salt_in_gram</span>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<span><input type=\"text\" name=\"inp_item_salt_metric\" id=\"inp_item_salt_metric\" value=\"$get_item_salt_metric\" size=\"5\" /></span>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<span><input type=\"text\" name=\"inp_item_salt_calculated\" id=\"inp_item_salt_calculated\" value=\"$get_item_salt_calculated\" size=\"5\" /></span>
				   </td>
				  </tr>

				  <tr>
				   <td style=\"padding: 8px 4px 6px 8px;\">
					<span>$l_sodium_in_mg</span>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<span><input type=\"text\" name=\"inp_item_sodium_metric\" id=\"inp_item_sodium_metric\" value=\"$get_item_sodium_metric\" size=\"5\" /></span>
				   </td>
				   <td style=\"text-align: center;padding: 0px 4px 0px 4px;\">
					<span><input type=\"text\" name=\"inp_item_sodium_calculated\" id=\"inp_item_sodium_calculated\" value=\"$get_item_sodium_calculated\" size=\"5\" /></span>
				   </td>
				  </tr>
				 </tbody>
					</table>

					<p>
					<input type=\"submit\" value=\"$l_save\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>


					</form>
					<!-- Search script -->
					<script id=\"source\" language=\"javascript\" type=\"text/javascript\">
					\$(document).ready(function () {
						\$('#inp_item_grocery').keyup(function () {
							$(\"#nettport_search_results\").show();
       							// getting the value that user typed
       							var searchString    = $(\"#inp_item_grocery\").val();
 							// forming the queryString
      							var data            = 'l=$l&recipe_id=$recipe_id&view_id=$get_current_view_id&q='+ searchString;
         
        						// if searchString is not empty
        						
           						// ajax call
            							\$.ajax({
                							type: \"POST\",
               								url: \"submit_recipe_step_2_group_and_elements_search_jquery.php\",
                							data: data,
									beforeSend: function(html) { // this happens before actual call
										\$(\"#nettport_search_results\").html(); 
									},
               								success: function(html){
                    								\$(\"#nettport_search_results\").html(html);
              								}
            							});
        						return false;
            					});
         				   });
					</script>
					<!-- //Search script -->
				<!-- //Edit item -->

				<!-- Buttons -->
					<p>
					<a href=\"submit_recipe_step_2_group_and_elements.php?recipe_id=$get_recipe_id&amp;action=summary&amp;l=$l\" class=\"btn btn_default\">$l_summary</a>

					<a href=\"submit_recipe_step_2_group_and_elements.php?recipe_id=$get_recipe_id&amp;l=$l\" class=\"btn btn_default\">$l_add_another_group</a>
				
					<a href=\"submit_recipe_step_3_main_ingredient.php?recipe_id=$get_recipe_id&amp;l=$l\" class=\"btn btn_default\">$l_continue</a>
	
					</p>
				<!-- //Buttons -->


				";
			} // item found
		} // group found
	} // action == "edit_item")
	elseif($action == "delete_item"){
		// Get group
		$group_id_mysql = quote_smart($link, $group_id);
		$query = "SELECT group_id, group_recipe_id, group_title FROM $t_recipes_groups WHERE group_id=$group_id_mysql AND group_recipe_id=$get_recipe_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_group_id, $get_group_recipe_id, $get_group_title) = $row;

		if($get_group_id == ""){
			echo"
			<h1>Server error</h1>

			<p>
			Group not found.
			</p>
			";
		}
		else{
			// Get item
			$item_id_mysql = quote_smart($link, $item_id);
			$query = "SELECT item_id, item_recipe_id, item_group_id, item_amount, item_measurement, item_grocery, item_grocery_explanation, item_food_id, item_energy_metric, item_fat_metric, item_saturated_fat_metric, item_monounsaturated_fat_metric, item_polyunsaturated_fat_metric, item_cholesterol_metric, item_carbohydrates_metric, item_carbohydrates_of_which_sugars_metric, item_dietary_fiber_metric, item_proteins_metric, item_salt_metric, item_sodium_metric, item_energy_calculated, item_fat_calculated, item_saturated_fat_calculated, item_monounsaturated_fat_calculated, item_polyunsaturated_fat_calculated, item_cholesterol_calculated, item_carbohydrates_calculated, item_carbohydrates_of_which_sugars_calculated, item_dietary_fiber_calculated, item_proteins_calculated, item_salt_calculated, item_sodium_calculated FROM $t_recipes_items WHERE item_id=$item_id_mysql AND item_recipe_id=$get_recipe_id AND item_group_id=$get_group_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_item_id, $get_item_recipe_id, $get_item_group_id, $get_item_amount, $get_item_measurement, $get_item_grocery, $get_item_grocery_explanation, $get_item_food_id, $get_item_energy_metric, $get_item_fat_metric, $get_item_saturated_fat_metric, $get_item_monounsaturated_fat_metric, $get_item_polyunsaturated_fat_metric, $get_item_cholesterol_metric, $get_item_carbohydrates_metric, $get_item_carbohydrates_of_which_sugars_metric, $get_item_dietary_fiber_metric, $get_item_proteins_metric, $get_item_salt_metric, $get_item_sodium_metric, $get_item_energy_calculated, $get_item_fat_calculated, $get_item_saturated_fat_calculated, $get_item_monounsaturated_fat_calculated, $get_item_polyunsaturated_fat_calculated, $get_item_cholesterol_calculated, $get_item_carbohydrates_calculated, $get_item_carbohydrates_of_which_sugars_calculated, $get_item_dietary_fiber_calculated, $get_item_proteins_calculated, $get_item_salt_calculated, $get_item_sodium_calculated) = $row;


			if($get_item_id == ""){
				echo"
				<h1>Server error</h1>

				<p>
				Items not found.
				</p>
				";
			}
			else{

				if($process == "1"){
					

					// Delete
					$result = mysqli_query($link, "DELETE FROM $t_recipes_items WHERE item_id=$get_item_id");

			

					// Calculating total numbers
					$inp_number_hundred_calories = 0;
					$inp_number_hundred_proteins = 0;
					$inp_number_hundred_fat = 0;
					$inp_number_hundred_carbs = 0;
					
					$inp_number_serving_calories = 0;
					$inp_number_serving_proteins = 0;
					$inp_number_serving_fat = 0;
					$inp_number_serving_carbs = 0;
					
					$inp_number_total_weight = 0;
	
					$inp_number_total_calories = 0;
					$inp_number_total_proteins = 0;
					$inp_number_total_fat = 0;

					$inp_number_total_carbs = 0;
					
					$query_groups = "SELECT group_id, group_title FROM $t_recipes_groups WHERE group_recipe_id=$get_recipe_id";
					$result_groups = mysqli_query($link, $query_groups);
					while($row_groups = mysqli_fetch_row($result_groups)) {
						list($get_group_id, $get_group_title) = $row_groups;

						$query_items = "SELECT item_id, item_recipe_id, item_group_id, item_amount, item_measurement, item_grocery, item_grocery_explanation, item_food_id, item_energy_metric, item_fat_metric, item_saturated_fat_metric, item_monounsaturated_fat_metric, item_polyunsaturated_fat_metric, item_cholesterol_metric, item_carbohydrates_metric, item_carbohydrates_of_which_sugars_metric, item_dietary_fiber_metric, item_proteins_metric, item_salt_metric, item_sodium_metric, item_energy_calculated, item_fat_calculated, item_saturated_fat_calculated, item_monounsaturated_fat_calculated, item_polyunsaturated_fat_calculated, item_cholesterol_calculated, item_carbohydrates_calculated, item_carbohydrates_of_which_sugars_calculated, item_dietary_fiber_calculated, item_proteins_calculated, item_salt_calculated, item_sodium_calculated FROM $t_recipes_items WHERE item_group_id=$get_group_id";
						$result_items = mysqli_query($link, $query_items);
						$row_cnt = mysqli_num_rows($result_items);
						while($row_items = mysqli_fetch_row($result_items)) {
							list($get_item_id, $get_item_recipe_id, $get_item_group_id, $get_item_amount, $get_item_measurement, $get_item_grocery, $get_item_grocery_explanation, $get_item_food_id, $get_item_energy_metric, $get_item_fat_metric, $get_item_saturated_fat_metric, $get_item_monounsaturated_fat_metric, $get_item_polyunsaturated_fat_metric, $get_item_cholesterol_metric, $get_item_carbohydrates_metric, $get_item_carbohydrates_of_which_sugars_metric, $get_item_dietary_fiber_metric, $get_item_proteins_metric, $get_item_salt_metric, $get_item_sodium_metric, $get_item_energy_calculated, $get_item_fat_calculated, $get_item_saturated_fat_calculated, $get_item_monounsaturated_fat_calculated, $get_item_polyunsaturated_fat_calculated, $get_item_cholesterol_calculated, $get_item_carbohydrates_calculated, $get_item_carbohydrates_of_which_sugars_calculated, $get_item_dietary_fiber_calculated, $get_item_proteins_calculated, $get_item_salt_calculated, $get_item_sodium_calculated) = $row_items;

							$inp_number_hundred_calories = $inp_number_hundred_calories+$get_item_energy_metric;
							$inp_number_hundred_proteins = $inp_number_hundred_proteins+$get_item_proteins_metric;
							$inp_number_hundred_fat      = $inp_number_hundred_fat+$get_item_fat_metric;
							$inp_number_hundred_carbs    = $inp_number_hundred_carbs+$get_item_carbohydrates_metric;
					
							$inp_number_total_weight     = $inp_number_total_weight+$get_item_amount;

							$inp_number_total_calories = $inp_number_total_calories+$get_item_energy_calculated;
							$inp_number_total_proteins = $inp_number_total_proteins+$get_item_proteins_calculated;
							$inp_number_total_fat      = $inp_number_total_fat+$get_item_fat_calculated;
							$inp_number_total_carbs    = $inp_number_total_carbs+$get_item_carbohydrates_metric;
	
						} // items
					} // groups
					
					$inp_number_serving_calories = round($inp_number_total_calories/$get_number_servings);
					$inp_number_serving_proteins = round($inp_number_total_proteins/$get_number_servings);
					$inp_number_serving_fat      = round($inp_number_total_fat/$get_number_servings);
					$inp_number_serving_carbs    = round($inp_number_total_carbs/$get_number_servings);

	
					// Ready numbers for MySQL
					$inp_number_hundred_calories_mysql = quote_smart($link, $inp_number_hundred_calories);
					$inp_number_hundred_proteins_mysql = quote_smart($link, $inp_number_hundred_proteins);
					$inp_number_hundred_fat_mysql      = quote_smart($link, $inp_number_hundred_fat);
					$inp_number_hundred_carbs_mysql    = quote_smart($link, $inp_number_hundred_carbs);
					
					$inp_number_total_weight_mysql     = quote_smart($link, $inp_number_total_weight);

					$inp_number_total_calories_mysql = quote_smart($link, $inp_number_total_calories);
					$inp_number_total_proteins_mysql = quote_smart($link, $inp_number_total_proteins);
					$inp_number_total_fat_mysql      = quote_smart($link, $inp_number_total_fat);
					$inp_number_total_carbs_mysql    = quote_smart($link, $inp_number_total_carbs);

						
					$inp_number_serving_calories_mysql = quote_smart($link, $inp_number_serving_calories);
					$inp_number_serving_proteins_mysql = quote_smart($link, $inp_number_serving_proteins);
					$inp_number_serving_fat_mysql      = quote_smart($link, $inp_number_serving_fat);
					$inp_number_serving_carbs_mysql    = quote_smart($link, $inp_number_serving_carbs);

					$result = mysqli_query($link, "UPDATE $t_recipes_numbers SET number_hundred_calories=$inp_number_hundred_calories_mysql, number_hundred_proteins=$inp_number_hundred_proteins_mysql, number_hundred_fat=$inp_number_hundred_fat_mysql, number_hundred_carbs=$inp_number_hundred_carbs_mysql, 
								number_serving_calories=$inp_number_serving_calories_mysql, number_serving_proteins=$inp_number_serving_proteins_mysql, number_serving_fat=$inp_number_serving_fat_mysql, number_serving_carbs=$inp_number_serving_carbs_mysql,
								number_total_weight=$inp_number_total_weight_mysql, 
								number_total_calories=$inp_number_total_calories_mysql, number_total_proteins=$inp_number_total_proteins_mysql, number_total_fat=$inp_number_total_fat_mysql, number_total_carbs=$inp_number_total_carbs_mysql WHERE number_recipe_id=$recipe_id_mysql");

	

					// Header
					$ft = "success";
					$fm = "item_deleted";

					$url = "submit_recipe_step_2_group_and_elements.php?recipe_id=$get_recipe_id&action=summary&l=$l";
					$url = $url . "&ft=$ft&fm=$fm";
					header("Location: $url");
					exit;	

				

				}
				echo"
				<h1>$l_delete_ingredients</h1>
				<h2>$get_group_title - $get_item_grocery</h2>



				

				<!-- Delete item -->
					
					<p>
					$l_are_you_sure_you_want_to_delete
					$l_the_action_cant_be_undone
					</p>

					<p>
					<a href=\"submit_recipe_step_2_group_and_elements.php?action=$action&amp;recipe_id=$get_recipe_id&amp;group_id=$get_group_id&amp;item_id=$get_item_id&amp;l=$l&amp;process=1\" class=\"btn btn_warning\">$l_delete</a>
					
					<a href=\"submit_recipe_step_2_group_and_elements.php?action=summary&amp;recipe_id=$get_recipe_id&amp;group_id=$get_group_id&amp;item_id=$get_item_id&amp;l=$l\" class=\"btn btn_default\">$l_cancel</a>
			
					</p>
				<!-- //Edit item -->

				<!-- Buttons -->
					<p style=\"margin-top: 20px;\">
					<a href=\"submit_recipe_step_2_group_and_elements.php?recipe_id=$get_recipe_id&amp;action=summary&amp;l=$l\" class=\"btn btn_default\">$l_summary</a>

					<a href=\"submit_recipe_step_2_group_and_elements.php?recipe_id=$get_recipe_id&amp;l=$l\" class=\"btn btn_default\">$l_add_another_group</a>
				
					<a href=\"submit_recipe_step_3_main_ingredient.php?recipe_id=$get_recipe_id&amp;l=$l\" class=\"btn btn_default\">$l_continue</a>
	
					</p>
				<!-- //Buttons -->


				";
			} // item found
		} // group found
	} // action == "edit_item")
}// recipe found

}
else{
	$action = "noshow";
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/recipes/submit_recipe.php\">
	";
}
/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>