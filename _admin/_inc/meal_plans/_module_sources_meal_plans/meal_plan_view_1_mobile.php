<?php 
/**
*
* File: meal_plans/meal_plan_view_1_mobile.php
* Version 1.0.0
* Date 12:05 10.02.2018
* Copyright (c) 2011-2018 S. A. Ditlefsen
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
include("_tables_meal_plans.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/meal_plans/ts_new_meal_plan.php");
include("$root/_admin/_translations/site/$l/meal_plans/ts_meal_plan_view_1.php");

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['meal_plan_id'])){
	$meal_plan_id = $_GET['meal_plan_id'];
	$meal_plan_id = output_html($meal_plan_id);
}
else{
	$meal_plan_id = "";
}

$tabindex = 0;
$l_mysql = quote_smart($link, $l);



// Get meal_plan
$meal_plan_id_mysql = quote_smart($link, $meal_plan_id);
$query = "SELECT meal_plan_id, meal_plan_user_id, meal_plan_language, meal_plan_title, meal_plan_title_clean, meal_plan_number_of_days, meal_plan_introduction, meal_plan_total_energy_without_training, meal_plan_total_fat_without_training, meal_plan_total_carb_without_training, meal_plan_total_protein_without_training, meal_plan_total_energy_with_training, meal_plan_total_fat_with_training, meal_plan_total_carb_with_training, meal_plan_total_protein_with_training, meal_plan_average_kcal_without_training, meal_plan_average_fat_without_training, meal_plan_average_carb_without_training, meal_plan_average_protein_without_training, meal_plan_average_kcal_with_training, meal_plan_average_fat_with_training, meal_plan_average_carb_with_training, meal_plan_average_protein_with_training, meal_plan_created, meal_plan_updated, meal_plan_user_ip, meal_plan_image_path, meal_plan_image_file, meal_plan_views, meal_plan_views_ip_block, meal_plan_likes, meal_plan_dislikes, meal_plan_rating, meal_plan_rating_ip_block, meal_plan_comments FROM $t_meal_plans WHERE meal_plan_id=$meal_plan_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_meal_plan_id, $get_current_meal_plan_user_id, $get_current_meal_plan_language, $get_current_meal_plan_title, $get_current_meal_plan_title_clean, $get_current_meal_plan_number_of_days, $get_current_meal_plan_introduction, $get_current_meal_plan_total_energy_without_training, $get_current_meal_plan_total_fat_without_training, $get_current_meal_plan_total_carb_without_training, $get_current_meal_plan_total_protein_without_training, $get_current_meal_plan_total_energy_with_training, $get_current_meal_plan_total_fat_with_training, $get_current_meal_plan_total_carb_with_training, $get_current_meal_plan_total_protein_with_training, $get_current_meal_plan_average_kcal_without_training, $get_current_meal_plan_average_fat_without_training, $get_current_meal_plan_average_carb_without_training, $get_current_meal_plan_average_protein_without_training, $get_current_meal_plan_average_kcal_with_training, $get_current_meal_plan_average_fat_with_training, $get_current_meal_plan_average_carb_with_training, $get_current_meal_plan_average_protein_with_training, $get_current_meal_plan_created, $get_current_meal_plan_updated, $get_current_meal_plan_user_ip, $get_current_meal_plan_image_path, $get_current_meal_plan_image_file, $get_current_meal_plan_views, $get_current_meal_plan_views_ip_block, $get_current_meal_plan_likes, $get_current_meal_plan_dislikes, $get_current_meal_plan_rating, $get_current_meal_plan_rating_ip_block, $get_current_meal_plan_comments) = $row;
	
if($get_current_meal_plan_id == ""){

	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$l_meal_plans - Server error 404";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");

	echo"<p>Meal plan not found.</p>";
	include("$root/_webdesign/footer.php");
}
else{
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$get_current_meal_plan_title - $l_meal_plans";
	include("$root/_webdesign/header.php");


	
	// Unique hits
	$inp_ip = $_SERVER['REMOTE_ADDR'];
	$inp_ip = output_html($inp_ip);

	$ip_array = explode("\n", $get_current_meal_plan_views_ip_block);
	$ip_array_size = sizeof($ip_array);

	$has_seen_this_food_before = 0;

	for($x=0;$x<$ip_array_size;$x++){
		if($ip_array[$x] == "$inp_ip"){
			$has_seen_this_food_before = 1;
			break;
		}
		if($x > 5){
			break;
		}
	}
	
	if($has_seen_this_food_before == 0){
		$inp_ip_block = $inp_ip . "\n" . $get_current_meal_plan_views_ip_block;
		$inp_ip_block_mysql = quote_smart($link, $inp_ip_block);
		$inp_views = $get_current_meal_plan_views + 1;
		$result = mysqli_query($link, "UPDATE $t_meal_plans SET meal_plan_views=$inp_views, meal_plan_views_ip_block=$inp_ip_block_mysql WHERE meal_plan_id=$meal_plan_id_mysql") or die(mysqli_error($link));
	}



	/*- Content ---------------------------------------------------------------------------------- */
	echo"
	<h1>$get_current_meal_plan_title</h1>
	

	<!-- Image and info -->
		<p>
		";
		if($get_current_meal_plan_image_file != "" && file_exists("$root/$get_current_meal_plan_image_path/$get_current_meal_plan_image_file")){
			// 950 x 640
			echo"
			<img src=\"$root/$get_current_meal_plan_image_path/$get_current_meal_plan_image_file\" alt=\"$get_current_meal_plan_image_path/$get_current_meal_plan_image_file\" /><br />
			\n";
		}
		echo"
		<img src=\"_gfx/icons/eye_dark_grey.png\" alt=\"eye.png\" /> $get_current_meal_plan_views $l_unique_views_lovercase
		</p>

		<p>
		$get_current_meal_plan_introduction
		</p>
	<!-- //Image and info -->

	<!-- Meal plan -->
			<table class=\"hor-zebra\">
		";


		for($meal_number=0;$meal_number<7;$meal_number++){

			
			// Totals meal
			$query_meal = "SELECT meal_id, meal_meal_plan_id, meal_day_number, meal_number, meal_energy, meal_fat, meal_carb, meal_protein FROM $t_meal_plans_meals WHERE meal_meal_plan_id=$get_current_meal_plan_id AND meal_day_number=1 AND meal_number=$meal_number";
			$result_meal = mysqli_query($link, $query_meal);
			$row_meal = mysqli_fetch_row($result_meal);
			list($get_meal_id, $get_meal_meal_plan_id, $get_meal_day_number, $get_meal_number, $get_meal_energy, $get_meal_fat, $get_meal_carb, $get_meal_protein) = $row_meal;
					
		

			echo"

			 <thead>
			  <tr>
			   <th scope\"col\">
				<span style=\"color: #000\">";
				if($meal_number == 0){
					echo"$l_breakfast ";
				}
				elseif($meal_number == 1){
					echo"$l_lunch";
				}
				elseif($meal_number == 2){
					echo"$l_before_training  ";
				}
				elseif($meal_number == 3){
					echo"$l_after_training ";
				}
				elseif($meal_number == 4){
					echo"$l_dinnar";
				}
				elseif($meal_number == 5){
					echo"$l_snacks";
				}
				elseif($meal_number == 6){
					echo"$l_supper ";
				}
				else{
					echo"x out of range";
				}
				echo"</span>
			   </th>
			   <th scope=\"col\" style=\"text-align:center;\">
				<span>$get_meal_energy</span>
			   </th>
			  </tr>
			 </thead>
			 <tbody>
			";
			
			$query_e = "SELECT entry_id, entry_food_id, entry_recipe_id, entry_weight, entry_name, entry_manufacturer_name, entry_serving_size, entry_serving_size_measurement, entry_energy_per_entry, entry_fat_per_entry, entry_carb_per_entry, entry_protein_per_entry, entry_text FROM $t_meal_plans_entries WHERE entry_meal_plan_id='$get_current_meal_plan_id' AND entry_day_number=1 AND entry_meal_number=$meal_number ORDER BY entry_weight ASC";
			$result_e = mysqli_query($link, $query_e);
			while($row_e = mysqli_fetch_row($result_e)) {
				list($get_entry_id, $get_entry_food_id, $get_entry_recipe_id, $get_entry_weight, $get_entry_food_name, $get_entry_food_manufacturer_name, $get_entry_food_serving_size, $get_entry_food_serving_size_measurement, $get_entry_food_energy_per_entry, $get_entry_food_fat_per_entry, $get_entry_food_carb_per_entry, $get_entry_food_protein_per_entry, $get_entry_food_text) = $row_e;
	
				if(isset($style) && $style == "odd"){
					$style = "";
				}
				else{
					$style = "odd";
				}
			
				echo"
				 <tr>
				  <td class=\"$style\" style=\"padding: 8px;\">
					<span><b>$get_entry_food_serving_size $get_entry_food_serving_size_measurement
					";
					if($get_entry_food_id != ""){
						echo"<a href=\"$root/food/view_food.php?food_id=$get_entry_food_id&amp;l=$l\" style=\"color: #000;font-weight:bold;\">";
					}
					else{
						echo"<a href=\"$root/recipes/view_recipe.php?recipe_id=$get_recipe_id_id&amp;l=$l\" style=\"color: #000;font-weight:bold;\">";
					}
					if($get_entry_food_manufacturer_name != ""){
						echo"$get_entry_food_manufacturer_name ";
					}
					echo"$get_entry_food_name</a></b><br /></span>

					<span class=\"smal\">
					$get_entry_food_fat_per_entry $l_fat_lowercase, 
					$get_entry_food_carb_per_entry $l_carb_lowercase, 
					$get_entry_food_protein_per_entry $l_proteins_lowercase</span>
				  </td>
				  <td class=\"$style\" style=\"padding: 8px;text-align:center;\">
					<span>$get_entry_food_energy_per_entry</span>
				  </td>
				 </tr>
				";
			}

			echo"
			 </tbody>
			";
			
		} // meal_number
		echo"
			</table>
		";

		// Total meal plan
		$query_meal = "SELECT day_id, day_meal_plan_id, day_number, day_energy_without_training, day_fat_without_training, day_carb_without_training, day_protein_without_training, day_sum_without_training, day_fat_without_training_percentage, day_carb_without_training_percentage, day_protein_without_training_percentage, day_energy_with_training, day_fat_with_training, day_carb_with_training, day_protein_with_training, day_sum_with_training, day_fat_with_training_percentage, day_carb_with_training_percentage, day_protein_with_training_percentage FROM $t_meal_plans_days WHERE day_meal_plan_id=$get_current_meal_plan_id AND day_number=1";
		$result_meal = mysqli_query($link, $query_meal);
		$row_meal = mysqli_fetch_row($result_meal);
		list($get_day_id, $get_day_meal_plan_id, $get_day_number, $get_day_energy_without_training, $get_day_fat_without_training, $get_day_carb_without_training, $get_day_protein_without_training, $get_day_sum_without_training, $get_day_fat_without_training_percentage, $get_day_carb_without_training_percentage, $get_day_protein_without_training_percentage, $get_day_energy_with_training, $get_day_fat_with_training, $get_day_carb_with_training, $get_day_protein_with_training, $get_day_sum_with_training, $get_day_fat_with_training_percentage, $get_day_carb_with_training_percentage, $get_day_protein_with_training_percentage) = $row_meal;

		echo"
		<div style=\"height: 20px;\"></div>
		<h2>$l_meal_plan_summary</h2>

			<table>
			 <tr>
			  <td>
					
			  </td>
			  <td style=\"padding: 0px 4px 0px 4px;text-align: center;\" colspan=\"2\">
				<span style=\"font-weight: bold;\">$l_without_training</span>
			  </td>
			  <td style=\"padding: 0px 4px 0px 4px;text-align: center;\" colspan=\"2\">
				<span style=\"font-weight: bold;\">$l_with_training</span>
			  </td>
			 </tr>

			 <tr>
			  <td style=\"padding: 0px 4px 0px 4px;text-align: center;\">
				<span><b>$l_energy</b></span>
			  </td>
			  <td style=\"padding: 0px 4px 0px 4px;text-align: center;\">
				<span>$get_day_energy_with_training</span>
			  </td>
			  <td style=\"padding: 0px 4px 0px 4px;text-align: center;\">
				
			  </td>
			  <td style=\"padding: 0px 4px 0px 4px;text-align: center;\">
				<span>$get_day_energy_with_training<br /></span>
			  </td>
			  <td style=\"padding: 0px 4px 0px 4px;text-align: center;\">
				
			  </td>
			 </tr>



			 <tr>
			  <td style=\"padding: 0px 4px 0px 4px;text-align: center;\">
				<span><b>$l_fat</b></span>
			  </td>
			  <td style=\"padding: 0px 4px 0px 4px;text-align: center;\">
				<span>$get_day_fat_without_training</span>
			  </td>
			  <td style=\"padding: 0px 4px 0px 0px;\">
				<span>($get_day_fat_without_training_percentage %)</span>
			  </td>
			  <td style=\"padding: 0px 4px 0px 4px;text-align: center;\">
				<span>$get_day_fat_with_training</span>
			  </td>
			  <td style=\"padding: 0px 4px 0px 0px;\">
				<span>($get_day_fat_with_training_percentage %)</span>
			  </td>
			 </tr>


			 <tr>
			  <td style=\"padding: 0px 4px 0px 4px;text-align: center;\">
				<span><b>$l_carbs</b></span>
			  </td>
			  <td style=\"padding: 0px 4px 0px 4px;text-align: center;\">
				<span>$get_day_carb_without_training</span>
			  </td>
			  <td style=\"padding: 0px 4px 0px 0px;\">
				<span>($get_day_carb_without_training_percentage %)</span>
			  </td>
			  <td style=\"padding: 0px 4px 0px 4px;text-align: center;\">
				<span>$get_day_carb_with_training</span>
			  </td>
			  <td style=\"padding: 0px 4px 0px 0px;\">
				<span>($get_day_carb_with_training_percentage %)</span>
			  </td>
			 </tr>
			 </tr>


			 <tr>
			  <td style=\"padding: 0px 4px 0px 4px;text-align: center;\">
				<span><b>$l_proteins</b></span>
			  </td>
			  <td style=\"padding: 0px 4px 0px 4px;text-align: center;\">
				<span>$get_day_protein_without_training</span>
			  </td>
			  <td style=\"padding: 0px 4px 0px 0px;\">
				<span>($get_day_protein_without_training_percentage %)</span>
			  </td>
			  <td style=\"padding: 0px 4px 0px 4px;text-align: center;\">
				<span>$get_day_protein_with_training</span>
			  </td>
			  <td style=\"padding: 0px 4px 0px 0px;\">
				<span>($get_day_protein_with_training_percentage %)</span>
			  </td>
			 </tr>
			</table>

			";


		echo"

	<!-- //Meal plan -->

	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>