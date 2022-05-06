<?php 
/**
*
* File: meal_plans/view_meal_plan.php
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
$query = "SELECT meal_plan_id, meal_plan_user_id, meal_plan_language, meal_plan_title, meal_plan_title_clean, meal_plan_number_of_days, meal_plan_introduction, meal_plan_text, meal_plan_total_energy_without_training, meal_plan_total_fat_without_training, meal_plan_total_carb_without_training, meal_plan_total_protein_without_training, meal_plan_total_energy_with_training, meal_plan_total_fat_with_training, meal_plan_total_carb_with_training, meal_plan_total_protein_with_training, meal_plan_average_kcal_without_training, meal_plan_average_fat_without_training, meal_plan_average_carb_without_training, meal_plan_average_protein_without_training, meal_plan_average_kcal_with_training, meal_plan_average_fat_with_training, meal_plan_average_carb_with_training, meal_plan_average_protein_with_training, meal_plan_created, meal_plan_updated, meal_plan_user_ip, meal_plan_image_path, meal_plan_image_file, meal_plan_views, meal_plan_views_ip_block, meal_plan_likes, meal_plan_dislikes, meal_plan_rating, meal_plan_rating_ip_block, meal_plan_comments FROM $t_meal_plans WHERE meal_plan_id=$meal_plan_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_meal_plan_id, $get_current_meal_plan_user_id, $get_current_meal_plan_language, $get_current_meal_plan_title, $get_current_meal_plan_title_clean, $get_current_meal_plan_number_of_days, $get_current_meal_plan_introduction, $get_current_meal_plan_text, $get_current_meal_plan_total_energy_without_training, $get_current_meal_plan_total_fat_without_training, $get_current_meal_plan_total_carb_without_training, $get_current_meal_plan_total_protein_without_training, $get_current_meal_plan_total_energy_with_training, $get_current_meal_plan_total_fat_with_training, $get_current_meal_plan_total_carb_with_training, $get_current_meal_plan_total_protein_with_training, $get_current_meal_plan_average_kcal_without_training, $get_current_meal_plan_average_fat_without_training, $get_current_meal_plan_average_carb_without_training, $get_current_meal_plan_average_protein_without_training, $get_current_meal_plan_average_kcal_with_training, $get_current_meal_plan_average_fat_with_training, $get_current_meal_plan_average_carb_with_training, $get_current_meal_plan_average_protein_with_training, $get_current_meal_plan_created, $get_current_meal_plan_updated, $get_current_meal_plan_user_ip, $get_current_meal_plan_image_path, $get_current_meal_plan_image_file, $get_current_meal_plan_views, $get_current_meal_plan_views_ip_block, $get_current_meal_plan_likes, $get_current_meal_plan_dislikes, $get_current_meal_plan_rating, $get_current_meal_plan_rating_ip_block, $get_current_meal_plan_comments) = $row;
	
if($get_current_meal_plan_id == ""){

	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "Server error 404 - $l_meal_plans";
	include("$root/_webdesign/header.php");

	echo"<p>Meal plan not found.</p>";
	include("$root/_webdesign/footer.php");
}
else{
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$get_current_meal_plan_title - $l_meal_plans";
	include("$root/_webdesign/header.php");

	// Logged in?
	$get_my_user_id = 0;
	$get_my_user_rank = "";
	if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
		// Get my user
		$my_user_id = $_SESSION['user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);
		$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;
	}

	
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
	
	<!-- Where am I ? -->
		<p><b>$l_you_are_here</b><br />
		<a href=\"index.php?l=$l\">$l_meal_plans</a>
		&gt;
		<a href=\"meal_plan_view_1.php?meal_plan_id=$get_current_meal_plan_id&amp;l=$l\">$get_current_meal_plan_title</a>
		</p>
	<!-- //Where am I ? -->

	<!-- Meal plan Quick menu -->
		<p>
		<a href=\"$root/meal_plans/my_meal_plans.php?l=$l\" class=\"btn_default\">$l_my_meal_plans</a>
		<a href=\"$root/meal_plans/new_meal_plan.php?l=$l\" class=\"btn_default\">$l_new_meal_plan</a>
		</p>
	<!-- //Meal plan Quick menu -->

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
		<img src=\"_gfx/icons/eye_dark_grey.png\" alt=\"eye.png\" /> $get_current_meal_plan_views $l_unique_views_lovercase";

		// Edit, delete
		if($get_current_meal_plan_user_id == "$get_my_user_id" OR ($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator" OR $get_my_user_rank == "editor")){
			echo"
			<a href=\"meal_plan_edit.php?meal_plan_id=$get_current_meal_plan_id&amp;entry_day_number=1&amp;l=$l\"><img src=\"_gfx/icons/edit.png\" alt=\"edit.png\" /></a>
			<a href=\"meal_plan_delete.php?meal_plan_id=$get_current_meal_plan_id&amp;entry_day_number=1&amp;l=$l\"><img src=\"_gfx/icons/delete.png\" alt=\"delete.png\" /></a>
			";
		}
		echo"
		</p>

		<p>
		$get_current_meal_plan_introduction
		</p>

		$get_current_meal_plan_text
	<!-- //Image and info -->

	<!-- Meal plan -->

		<table style=\"width: 98%;\">
		";
		for($meal_number=0;$meal_number<9;$meal_number++){
			
			$count_entries_for_meal = 0;
			$query_e = "SELECT entry_id, entry_food_id, entry_recipe_id, entry_weight, entry_name, entry_manufacturer_name, entry_main_category_id, entry_sub_category_id, entry_serving_size, entry_serving_size_measurement, entry_energy_per_entry, entry_fat_per_entry, entry_carb_per_entry, entry_protein_per_entry, entry_text FROM $t_meal_plans_entries WHERE entry_meal_plan_id='$get_current_meal_plan_id' AND entry_day_number=1 AND entry_meal_number=$meal_number ORDER BY entry_weight ASC";
			$result_e = mysqli_query($link, $query_e);
			while($row_e = mysqli_fetch_row($result_e)) {
				list($get_entry_id, $get_entry_food_id, $get_entry_recipe_id, $get_entry_weight, $get_entry_food_name, $get_entry_food_manufacturer_name, $get_entry_main_category_id, $get_entry_sub_category_id, $get_entry_food_serving_size, $get_entry_food_serving_size_measurement, $get_entry_food_energy_per_entry, $get_entry_food_fat_per_entry, $get_entry_food_carb_per_entry, $get_entry_food_protein_per_entry, $get_entry_food_text) = $row_e;
				if($count_entries_for_meal == "0"){
					// Header
					echo"
					 <tr>
					  <td>
						<span style=\"color: #1b3550;border-bottom: #fdfdfd 1px solid;font-size: 20px;\">";
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
							echo"$l_linner";
						}
						elseif($meal_number == 5){
							echo"$l_dinnar";
						}
						elseif($meal_number == 6){
							echo"$l_snacks";
						}
						elseif($meal_number == 7){
							echo"$l_supper ";
						}
						elseif($meal_number == 8){
							echo"$l_night_meal";
						}
						else{
							echo"x out of range";
						}
						echo"</span>
					  </td>
					  <td"; if($meal_number == 0){ echo" style=\"background: #095382;border-bottom: #fff 1px solid;padding: 4px;text-align:center;\""; }echo">
						"; if($meal_number == 0){ echo"<span style=\"color: #fdfdfd;font-weight: bold;\">$l_calories</span>"; }echo"
					  </td>
					  <td"; if($meal_number == 0){ echo" style=\"background: #095382;border-bottom: #fff 1px solid;padding: 4px;text-align:center;\""; }echo">
						"; if($meal_number == 0){ echo"<span style=\"color: #fdfdfd;font-weight: bold;\">$l_fat</span>"; }echo"
					  </td>
					  <td"; if($meal_number == 0){ echo" style=\"background: #095382;border-bottom: #fff 1px solid;padding: 4px;text-align:center;\""; }echo">
						"; if($meal_number == 0){ echo"<span style=\"color: #fdfdfd;font-weight: bold;\">$l_carbs</span>"; }echo"
					  </td>
					  <td"; if($meal_number == 0){ echo" style=\"background: #095382;border-bottom: #fff 1px solid;padding: 4px;text-align:center;\""; }echo">
						"; if($meal_number == 0){ echo"<span style=\"color: #fdfdfd;font-weight: bold;\">$l_proteins</span>"; }echo"
					  </td>
					 </tr>";
				} // Header
				

				echo"
				 <tr>
				  <td style=\"background: #f2f2f2;border-top: #fbfbfb 1px solid;border-right: #fbfbfb 1px solid;padding: 8px;\">
					<span>$get_entry_food_serving_size $get_entry_food_serving_size_measurement</span>
					<span>";
					if($get_entry_food_id != "0"){
						echo"<a href=\"$root/food/view_food.php?main_category_id=$get_entry_main_category_id&amp;sub_category_id=$get_entry_sub_category_id&amp;food_id=$get_entry_food_id&amp;l=$l\" style=\"color: #000\">";
					}
					else{
						echo"<a href=\"$root/recipes/view_recipe.php?recipe_id=$get_entry_recipe_id&amp;l=$l\" style=\"color: #000\">";
					}
					echo"$get_entry_food_name $get_entry_food_manufacturer_name</a></span>
				  </td>
				  <td style=\"background: #f2f2f2;border-top: #fbfbfb 1px solid;border-right: #fbfbfb 1px solid;padding: 8px;text-align:center;\">
					<span>$get_entry_food_energy_per_entry</span>
				  </td>
				  <td style=\"background: #f2f2f2;border-top: #fbfbfb 1px solid;border-right: #fbfbfb 1px solid;padding: 8px;text-align:center;\">
					<span>$get_entry_food_fat_per_entry</span>
				  </td>
				  <td style=\"background: #f2f2f2;border-top: #fbfbfb 1px solid;border-right: #fbfbfb 1px solid;padding: 8px;text-align:center;\">
					<span>$get_entry_food_carb_per_entry</span>
				  </td>
				  <td style=\"background: #f2f2f2;border-top: #fbfbfb 1px solid;border-right: #fbfbfb 1px solid;padding: 8px;text-align:center;\">
					<span>$get_entry_food_protein_per_entry</span>
				  </td>
				 </tr>";
				$count_entries_for_meal = $count_entries_for_meal+1;
			}


			// Totals meal
			$query_meal = "SELECT meal_id, meal_meal_plan_id, meal_day_number, meal_number, meal_energy, meal_fat, meal_carb, meal_protein FROM $t_meal_plans_meals WHERE meal_meal_plan_id=$get_current_meal_plan_id AND meal_day_number=1 AND meal_number=$meal_number";
			$result_meal = mysqli_query($link, $query_meal);
			$row_meal = mysqli_fetch_row($result_meal);
			list($get_meal_id, $get_meal_meal_plan_id, $get_meal_day_number, $get_meal_number, $get_meal_energy, $get_meal_fat, $get_meal_carb, $get_meal_protein) = $row_meal;
						
			if($get_meal_energy != "0" OR $get_meal_fat != "0" OR $get_meal_carb != "0" OR $get_meal_protein != "0"){
				echo"
				 <tr>
				  <td style=\"border-top: #fbfbfb 1px solid;border-right: #fbfbfb 1px solid;padding: 8px;\">
					
				  </td>
				  <td style=\"border-top: #fbfbfb 1px solid;border-right: #fbfbfb 1px solid;padding: 8px;text-align:center;\">
					<span style=\"color: #3f5f76;font-weight: bold;\">$get_meal_energy</span>
				  </td>
				  <td style=\"border-top: #fbfbfb 1px solid;border-right: #fbfbfb 1px solid;padding: 8px;text-align:center;\">
					<span style=\"color: #3f5f76;font-weight: bold;\">$get_meal_fat</span>
				  </td>
				  <td style=\"border-top: #fbfbfb 1px solid;border-right: #fbfbfb 1px solid;padding: 8px;text-align:center;\">
					<span style=\"color: #3f5f76;font-weight: bold;\">$get_meal_carb</span>
				  </td>
				  <td style=\"border-top: #fbfbfb 1px solid;border-right: #fbfbfb 1px solid;padding: 8px;text-align:center;\">
					<span style=\"color: #3f5f76;font-weight: bold;\">$get_meal_protein</span>
				  </td>
				 </tr>";
			}
		} // meal_number

		// Total meal plan
		$query_meal = "SELECT day_id, day_meal_plan_id, day_number, day_energy_without_training, day_fat_without_training, day_carb_without_training, day_protein_without_training, day_sum_without_training, day_fat_without_training_percentage, day_carb_without_training_percentage, day_protein_without_training_percentage, day_energy_with_training, day_fat_with_training, day_carb_with_training, day_protein_with_training, day_sum_with_training, day_fat_with_training_percentage, day_carb_with_training_percentage, day_protein_with_training_percentage FROM $t_meal_plans_days WHERE day_meal_plan_id=$get_current_meal_plan_id AND day_number=1";
		$result_meal = mysqli_query($link, $query_meal);
		$row_meal = mysqli_fetch_row($result_meal);
		list($get_day_id, $get_day_meal_plan_id, $get_day_number, $get_day_energy_without_training, $get_day_fat_without_training, $get_day_carb_without_training, $get_day_protein_without_training, $get_day_sum_without_training, $get_day_fat_without_training_percentage, $get_day_carb_without_training_percentage, $get_day_protein_without_training_percentage, $get_day_energy_with_training, $get_day_fat_with_training, $get_day_carb_with_training, $get_day_protein_with_training, $get_day_sum_with_training, $get_day_fat_with_training_percentage, $get_day_carb_with_training_percentage, $get_day_protein_with_training_percentage) = $row_meal;

				echo"
				 <tr>
				  <td style=\"border-top: #f2f2f2 1px solid;border-right: #fbfbfb 1px solid;padding: 8px 8px 8px 8px;\">
					
				  </td>
				  <td style=\"border-top: #f2f2f2 1px solid;border-right: #fbfbfb 1px solid;padding: 8px 8px 8px 8px;\">
				  </td>
				  <td style=\"border-top: #f2f2f2 1px solid;border-right: #fbfbfb 1px solid;padding: 8px 8px 8px 8px;\">
				  </td>
				  <td style=\"border-top: #f2f2f2 1px solid;border-right: #fbfbfb 1px solid;padding: 8px 8px 8px 8px;\">
				  </td>
				  <td style=\"border-top: #f2f2f2 1px solid;border-right: #fbfbfb 1px solid;padding: 8px 8px 8px 8px;\">
				  </td>
				 </tr>";

				echo"
				 <tr>
				  <td style=\"background: #f7f7f7;border-top: #f2f2f2 1px solid;border-right: #fbfbfb 1px solid;padding: 8px 8px 8px 8px;text-align: right;\">
					<span style=\"font-weight: bold;\">$l_sum_with_traning</span>
				  </td>
				  <td style=\"background: #f7f7f7;border-top: #f2f2f2 1px solid;border-right: #fbfbfb 1px solid;padding: 8px 8px 8px 8px;text-align: center;\">
					<span style=\"font-weight: bold;\">$get_day_energy_with_training</span>
				  </td>
				  <td style=\"background: #f7f7f7;border-top: #f2f2f2 1px solid;border-right: #fbfbfb 1px solid;padding: 8px 8px 8px 8px;text-align: center;\">
					<span style=\"font-weight: bold;\">$get_day_fat_with_training</span>
				  </td>
				  <td style=\"background: #f7f7f7;border-top: #f2f2f2 1px solid;border-right: #fbfbfb 1px solid;padding: 8px 8px 8px 8px;text-align: center;\">
					<span style=\"font-weight: bold;\">$get_day_carb_with_training</span>
				  </td>
				  <td style=\"background: #f7f7f7;border-top: #f2f2f2 1px solid;border-right: #fbfbfb 1px solid;padding: 8px 8px 8px 8px;text-align: center;\">
					<span style=\"font-weight: bold;\">$get_day_protein_with_training</span>
				  </td>
				 </tr>

				 <tr>
				  <td style=\"background: #f7f7f7;border-top: #f2f2f2 1px solid;border-right: #fbfbfb 1px solid;padding: 8px 8px 8px 8px;text-align: right;\">
					<span>$l_percent</span>
				  </td>
				  <td style=\"background: #f7f7f7;border-top: #f2f2f2 1px solid;border-right: #fbfbfb 1px solid;padding: 8px 8px 8px 8px;text-align: center;\">
					
				  </td>
				  <td style=\"background: #f7f7f7;border-top: #f2f2f2 1px solid;border-right: #fbfbfb 1px solid;padding: 8px 8px 8px 8px;text-align: center;\">
					<span>$get_day_fat_with_training_percentage</span>
				  </td>
				  <td style=\"background: #f7f7f7;border-top: #f2f2f2 1px solid;border-right: #fbfbfb 1px solid;padding: 8px 8px 8px 8px;text-align: center;\">
					<span>$get_day_carb_with_training_percentage</span>
				  </td>
				  <td style=\"background: #f7f7f7;border-top: #f2f2f2 1px solid;border-right: #fbfbfb 1px solid;padding: 8px 8px 8px 8px;text-align: center;\">
					<span>$get_day_protein_with_training_percentage</span>
				  </td>
				 </tr>


				 <tr>
				  <td style=\"background: #f7f7f7;border-top: #f2f2f2 1px solid;border-right: #fbfbfb 1px solid;padding: 8px 8px 8px 8px;text-align: right;\">
					<span style=\"font-weight: bold;\">$l_sum_without_traning</span>
				  </td>
				  <td style=\"background: #f7f7f7;border-top: #f2f2f2 1px solid;border-right: #fbfbfb 1px solid;padding: 8px 8px 8px 8px;text-align: center;\">
					<span style=\"font-weight: bold;\">$get_day_energy_without_training</span>
				  </td>
				  <td style=\"background: #f7f7f7;border-top: #f2f2f2 1px solid;border-right: #fbfbfb 1px solid;padding: 8px 8px 8px 8px;text-align: center;\">
					<span style=\"font-weight: bold;\">$get_day_fat_without_training</span>
				  </td>
				  <td style=\"background: #f7f7f7;border-top: #f2f2f2 1px solid;border-right: #fbfbfb 1px solid;padding: 8px 8px 8px 8px;text-align: center;\">
					<span style=\"font-weight: bold;\">$get_day_carb_without_training</span>
				  </td>
				  <td style=\"background: #f7f7f7;border-top: #f2f2f2 1px solid;border-right: #fbfbfb 1px solid;padding: 8px 8px 8px 8px;text-align: center;\">
					<span style=\"font-weight: bold;\">$get_day_protein_without_training</span>
				  </td>
				 </tr>

				 <tr>
				  <td style=\"background: #f7f7f7;border-top: #f2f2f2 1px solid;border-right: #fbfbfb 1px solid;padding: 8px 8px 8px 8px;text-align: right;\">
					<span>$l_percent</span>
				  </td>
				  <td style=\"background: #f7f7f7;border-top: #f2f2f2 1px solid;border-right: #fbfbfb 1px solid;padding: 8px 8px 8px 8px;text-align: center;\">
					
				  </td>
				  <td style=\"background: #f7f7f7;border-top: #f2f2f2 1px solid;border-right: #fbfbfb 1px solid;padding: 8px 8px 8px 8px;text-align: center;\">
					<span>$get_day_fat_without_training_percentage</span>
				  </td>
				  <td style=\"background: #f7f7f7;border-top: #f2f2f2 1px solid;border-right: #fbfbfb 1px solid;padding: 8px 8px 8px 8px;text-align: center;\">
					<span>$get_day_carb_without_training_percentage</span>
				  </td>
				  <td style=\"background: #f7f7f7;border-top: #f2f2f2 1px solid;border-right: #fbfbfb 1px solid;padding: 8px 8px 8px 8px;text-align: center;\">
					<span>$get_day_protein_without_training_percentage</span>
				  </td>
				 </tr>
				";


		echo"
		</table>

	<!-- //Meal plan -->

	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>