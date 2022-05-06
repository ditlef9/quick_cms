<?php 
/**
*
* File: meal_plans/meal_plan_edit.php
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
if(isset($_GET['entry_day_number'])){
	$entry_day_number = $_GET['entry_day_number'];
	$entry_day_number = output_html($entry_day_number);
}
else{
	$entry_day_number = "";
}
if(isset($_GET['entry_meal_number'])){
	$entry_meal_number = $_GET['entry_meal_number'];
	$entry_meal_number = output_html($entry_meal_number);
}
else{
	$entry_meal_number = "";
}

$tabindex = 0;
$l_mysql = quote_smart($link, $l);




/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_edit_meal_plan - $l_meal_plans";
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */
// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	// Get my user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_rank) = $row;

	// Get meal_plan
	$meal_plan_id_mysql = quote_smart($link, $meal_plan_id);
	$query = "SELECT meal_plan_id, meal_plan_user_id, meal_plan_language, meal_plan_title, meal_plan_title_clean, meal_plan_number_of_days, meal_plan_introduction, meal_plan_total_energy_without_training, meal_plan_total_fat_without_training, meal_plan_total_carb_without_training, meal_plan_total_protein_without_training, meal_plan_total_energy_with_training, meal_plan_total_fat_with_training, meal_plan_total_carb_with_training, meal_plan_total_protein_with_training, meal_plan_average_kcal_without_training, meal_plan_average_fat_without_training, meal_plan_average_carb_without_training, meal_plan_average_protein_without_training, meal_plan_average_kcal_with_training, meal_plan_average_fat_with_training, meal_plan_average_carb_with_training, meal_plan_average_protein_with_training, meal_plan_created, meal_plan_updated, meal_plan_user_ip, meal_plan_image_path, meal_plan_image_file, meal_plan_views, meal_plan_views_ip_block, meal_plan_likes, meal_plan_dislikes, meal_plan_rating, meal_plan_rating_ip_block, meal_plan_comments FROM $t_meal_plans WHERE meal_plan_id=$meal_plan_id_mysql AND meal_plan_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_meal_plan_id, $get_current_meal_plan_user_id, $get_current_meal_plan_language, $get_current_meal_plan_title, $get_current_meal_plan_title_clean, $get_current_meal_plan_number_of_days, $get_current_meal_plan_introduction, $get_current_meal_plan_total_energy_without_training, $get_current_meal_plan_total_fat_without_training, $get_current_meal_plan_total_carb_without_training, $get_current_meal_plan_total_protein_without_training, $get_current_meal_plan_total_energy_with_training, $get_current_meal_plan_total_fat_with_training, $get_current_meal_plan_total_carb_with_training, $get_current_meal_plan_total_protein_with_training, $get_current_meal_plan_average_kcal_without_training, $get_current_meal_plan_average_fat_without_training, $get_current_meal_plan_average_carb_without_training, $get_current_meal_plan_average_protein_without_training, $get_current_meal_plan_average_kcal_with_training, $get_current_meal_plan_average_fat_with_training, $get_current_meal_plan_average_carb_with_training, $get_current_meal_plan_average_protein_with_training, $get_current_meal_plan_created, $get_current_meal_plan_updated, $get_current_meal_plan_user_ip, $get_current_meal_plan_image_path, $get_current_meal_plan_image_file, $get_current_meal_plan_views, $get_current_meal_plan_views_ip_block, $get_current_meal_plan_likes, $get_current_meal_plan_dislikes, $get_current_meal_plan_rating, $get_current_meal_plan_rating_ip_block, $get_current_meal_plan_comments) = $row;
	
	

	if($get_current_meal_plan_id == ""){
		echo"<p>Meal plan not found.</p>";
	}
	else{
		echo"
		<h1>$get_current_meal_plan_title</h1>
	
		<!-- Where am I ? -->
			<p><b>$l_you_are_here</b><br />
			<a href=\"my_meal_plans.php?l=$l\">$l_my_meal_plans</a>
			&gt;
			<a href=\"meal_plan_view_$get_current_meal_plan_number_of_days.php?meal_plan_id=$get_current_meal_plan_id&amp;l=$l\">$get_current_meal_plan_title</a>
			&gt;
			<a href=\"meal_plan_edit.php?meal_plan_id=$get_current_meal_plan_id&amp;entry_day_number=1&amp;l=$l\">$l_edit</a>
			</p>
		<!-- //Where am I ? -->

		<!-- Edit menu -->
			<div class=\"tabs\">
				<ul>
					<li><a href=\"meal_plan_edit.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;l=$l\" class=\"selected\">$l_edit</a></li>
					<li><a href=\"meal_plan_edit_info.php?meal_plan_id=$meal_plan_id&amp;l=$l\">$l_info</a></li>
					<li><a href=\"meal_plan_edit_text.php?meal_plan_id=$meal_plan_id&amp;l=$l\">$l_text</a></li>
					<li><a href=\"meal_plan_edit_image.php?meal_plan_id=$meal_plan_id&amp;l=$l\">$l_image</a></li>
				</ul>
			</div>
			<div class=\"clear\" style=\"height: 20px;\"></div>
		<!-- //Edit menu -->

		";
		if($get_current_meal_plan_number_of_days > 1){
			echo"
			<!-- Days of the week -->
			<div class=\"left\" style=\"width: 15%;\">
				<table class=\"hor-zebra\">
				 <tbody>
				  <tr>
				   <td style=\"paddding: 0px 0px 0px 0px;margin: 0px 0px 0px 0px;\">
					<p style=\"paddding: 0px 0px 0px 0px;margin: 0px 0px 0px 0px;\">
					<a href=\"meal_plan_edit.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=1&amp;l=$l&amp;\""; if($entry_day_number == "1"){ echo" style=\"font-weight: bold;\""; } echo">$l_monday</a><br />
					<a href=\"meal_plan_edit.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=2&amp;l=$l&amp;\""; if($entry_day_number == "2"){ echo" style=\"font-weight: bold;\""; } echo">$l_tuesday</a><br />
					<a href=\"meal_plan_edit.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=3&amp;l=$l&amp;\""; if($entry_day_number == "3"){ echo" style=\"font-weight: bold;\""; } echo">$l_wednesday</a><br />
					<a href=\"meal_plan_edit.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=4&amp;l=$l&amp;\""; if($entry_day_number == "4"){ echo" style=\"font-weight: bold;\""; } echo">$l_thursday</a><br />
					<a href=\"meal_plan_edit.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=5&amp;l=$l&amp;\""; if($entry_day_number == "5"){ echo" style=\"font-weight: bold;\""; } echo">$l_friday</a><br />
					<a href=\"meal_plan_edit.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=6&amp;l=$l&amp;\""; if($entry_day_number == "6"){ echo" style=\"font-weight: bold;\""; } echo">$l_saturday</a><br />
					<a href=\"meal_plan_edit.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=7&amp;l=$l&amp;\""; if($entry_day_number == "7"){ echo" style=\"font-weight: bold;\""; } echo">$l_sunday</a><br />
					</p>
				   </td>
				  </tr>
				 </tbody>
				</table>
			</div>
			<!-- //Days of the week -->
			";
		}
		echo"

		<!-- Current day -->
			<div"; if($get_current_meal_plan_number_of_days > 1){ echo" class=\"right\" style=\"width: 82%;\""; } echo">
			";
			if($entry_day_number > 0 && $entry_day_number < 8){
				if($get_current_meal_plan_number_of_days > 1){
					if($entry_day_number == "1"){
						echo"<h2>$l_monday</h2>";
					}
					elseif($entry_day_number == "2"){
						echo"<h2>$l_tuesday</h2>";
					}
					elseif($entry_day_number == "3"){
						echo"<h2>$l_wednesday</h2>";
					}
					elseif($entry_day_number == "4"){
						echo"<h2>$l_thursday</h2>";
					}
					elseif($entry_day_number == "5"){
						echo"<h2>$l_friday</h2>";
					}
					elseif($entry_day_number == "6"){
						echo"<h2>$l_saturday</h2>";
					}
					elseif($entry_day_number == "7"){
						echo"<h2>$l_sunday</h2>";
					}
				}

				echo"
				<!-- Feedback -->
					";
					if($ft != ""){
						if($fm == "changes_saved"){
							$fm = "$l_changes_saved";
						}
						else{
							$fm = ucfirst($fm);
						}
						echo"<div class=\"$ft\"><span>$fm</span></div>";
					}
					echo"	
				<!-- //Feedback -->
				<table class=\"hor-zebra\">
				";
				$layout = 0;
				$entry_day_number_mysql = quote_smart($link, $entry_day_number);
				$inp_day_energy_without_training = 0;
				$inp_day_fat_without_training = 0;
				$inp_day_carb_without_training = 0;
				$inp_day_protein_without_training = 0;
				$inp_day_energy_with_training = 0;
				$inp_day_fat_with_training = 0;
				$inp_day_carb_with_training = 0;
				$inp_day_protein_with_training = 0;

				for($x=0;$x<9;$x++){
					// Layout 
					if(isset($layout) && $layout == "bodycell"){
						$layout = "subcell";
					}
					else{
						$layout = "bodycell";
					}

					echo"
					 <thead>
					  <tr>
					   <th scope=\"col\"><a id=\"meal_number$x\"></a>
						<span><b>";
						if($x == 0){
							echo"$l_breakfast";
						}
						elseif($x == 1){
							echo"$l_lunch";
						}
						elseif($x == 2){
							echo"$l_before_training";
						}
						elseif($x == 3){
							echo"$l_after_training";
						}
						elseif($x == 4){
							echo"$l_linner";
						}
						elseif($x == 5){
							echo"$l_dinnar";
						}
						elseif($x == 6){
							echo"$l_snacks";
						}
						elseif($x == 7){
							echo"$l_supper";
						}
						elseif($x == 8){
							echo"$l_night_meal";
						}
						else{
							echo"x out of range";
						}
						echo"</b></span>

						<div style=\"float: right;\">
							<span>
							<a href=\"meal_plan_edit_new_entry_food.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$x&amp;l=$l\"><img src=\"_gfx/icons/add.png\" alt=\"add.png\" /></a>
							<a href=\"meal_plan_edit_new_entry_food.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$x&amp;l=$l\">$l_add_food</a>
							&nbsp;
							<a href=\"meal_plan_edit_new_entry_recipe.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$x&amp;l=$l\"><img src=\"_gfx/icons/add.png\" alt=\"add.png\" /></a>
							<a href=\"meal_plan_edit_new_entry_recipe.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$x&amp;l=$l\">$l_add_recipe</a>
							</span>
						</div>
					   </th>
					  </tr>
					 </thead>
					 <tbody>
					  <tr>
					   <td>
						<table style=\"width: 100%;\">
						 <tr>
						  <td class=\"odd\" style=\"text-align: right;width: 9%;padding-right: 1%;\">
						  </td>
						  <td style=\"width: 26%;\">
						  </td>
						  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
							<span>$l_cal_lowercase</span>
						  </td>
						  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
								<span>$l_fat_lowercase</span>
							  </td>
							  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
								<span>$l_carb_lowercase</span>
							  </td>
							  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
								<span>$l_proteins_lowercase</span>
							  </td>
							  <td style=\"width: 10%;text-align: right;\">
							  </td>
							 </tr>
							</table>
						   </td>
						  </tr>
						";

						$count_entries = 0;
						$inp_meal_energy = 0;
						$inp_meal_fat = 0;
						$inp_meal_carbs = 0;
						$inp_meal_proteins = 0;
						$query_e = "SELECT entry_id, entry_food_id, entry_weight, entry_name, entry_manufacturer_name, entry_serving_size, entry_serving_size_measurement, entry_energy_per_entry, entry_fat_per_entry, entry_carb_per_entry, entry_protein_per_entry, entry_text FROM $t_meal_plans_entries WHERE entry_meal_plan_id='$get_current_meal_plan_id' AND entry_day_number=$entry_day_number_mysql AND entry_meal_number=$x ORDER BY entry_weight ASC";
						$result_e = mysqli_query($link, $query_e);
						while($row_e = mysqli_fetch_row($result_e)) {
							list($get_entry_id, $get_entry_food_id, $get_entry_weight, $get_entry_food_name, $get_entry_food_manufacturer_name, $get_entry_food_serving_size, $get_entry_food_serving_size_measurement, $get_entry_food_energy_per_entry, $get_entry_food_fat_per_entry, $get_entry_food_carb_per_entry, $get_entry_food_protein_per_entry, $get_entry_food_text) = $row_e;
							echo"
							  <tr>
							   <td>
								<table style=\"width: 100%;\">
								 <tr>
								  <td class=\"odd\" style=\"text-align: right;width: 9%;padding-right: 1%;\">
									<span>$get_entry_food_serving_size $get_entry_food_serving_size_measurement</span>
							  	</td>
								  <td style=\"width: 26%;\">
									<span>$get_entry_food_manufacturer_name $get_entry_food_name</span>
								  </td>
								  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
									<span>$get_entry_food_energy_per_entry</span>
								  </td>
								  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
									<span>$get_entry_food_fat_per_entry</span>
								  </td>
								  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
									<span>$get_entry_food_carb_per_entry</span>
								  </td>
								  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
									<span>$get_entry_food_protein_per_entry</span>
								  </td>
								  <td style=\"width: 10%;text-align: right;\">
									<span>
									<a href=\"meal_plan_edit_move_entry_up.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$x&amp;l=$l&amp;entry_id=$get_entry_id&amp;process=1\"><img src=\"_gfx/icons/go-up.png\" alt=\"go-up.png\" title=\"Move up\" /></a>
									<a href=\"meal_plan_edit_move_entry_down.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$x&amp;l=$l&amp;entry_id=$get_entry_id&amp;process=1\"><img src=\"_gfx/icons/go-down.png\" alt=\"go-down.png\" title=\"Move down\" /></a>
									<a href=\"meal_plan_edit_edit_entry.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$x&amp;l=$l&amp;entry_id=$get_entry_id\"><img src=\"_gfx/icons/edit.png\" alt=\"edit.png\" title=\"$l_edit\" /></a>
									<a href=\"meal_plan_edit_delete_entry.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$x&amp;l=$l&amp;entry_id=$get_entry_id\"><img src=\"_gfx/icons/delete.png\" alt=\"delete.png\" title=\"$l_delete\" /></a>
									</span>
								  </td>
								 </tr>
								</table>
							   </td>
							  </tr>
							";

							if($get_entry_weight != $count_entries){
								$result = mysqli_query($link, "UPDATE $t_meal_plans_entries SET entry_weight='$count_entries' WHERE entry_id='$get_entry_id'");
							}

							$count_entries++;
							$inp_meal_energy = $inp_meal_energy+$get_entry_food_energy_per_entry;
							$inp_meal_fat = $inp_meal_fat+$get_entry_food_fat_per_entry;
							$inp_meal_carbs = $inp_meal_carbs+$get_entry_food_carb_per_entry;
							$inp_meal_proteins = $inp_meal_proteins+$get_entry_food_protein_per_entry;

						} // while

						// Sum of Meal
						$query_meal = "SELECT meal_id, meal_meal_plan_id, meal_day_number, meal_number, meal_energy, meal_fat, meal_carb, meal_protein FROM $t_meal_plans_meals WHERE meal_meal_plan_id=$get_current_meal_plan_id AND meal_day_number=$entry_day_number_mysql AND meal_number=$x";
						$result_meal = mysqli_query($link, $query_meal);
						$row_meal = mysqli_fetch_row($result_meal);
						list($get_meal_id, $get_meal_meal_plan_id, $get_meal_day_number, $get_meal_number, $get_meal_energy, $get_meal_fat, $get_meal_carb, $get_meal_protein) = $row_meal;
						
						if($get_meal_id == ""){
							mysqli_query($link, "INSERT INTO $t_meal_plans_meals
							(meal_id, meal_meal_plan_id, meal_day_number, meal_number, meal_energy, meal_fat, meal_carb, meal_protein) 
							VALUES 
							(NULL, '$get_current_meal_plan_id', $entry_day_number_mysql, '$x', '$inp_meal_energy', '$inp_meal_fat', '$inp_meal_carbs', '$inp_meal_proteins')")
							or die(mysqli_error($link));

							$get_meal_energy = $inp_meal_energy;
							$get_meal_fat = $inp_meal_fat;
							$get_meal_carbs = $inp_meal_carbs;
							$get_meal_proteins = $inp_meal_proteins;
						}
						else{
							if($get_meal_energy != "$inp_meal_energy" OR $get_meal_fat != "$inp_meal_fat" 
								OR $get_meal_carb != "$inp_meal_carbs" OR $get_meal_protein != "$inp_meal_proteins"){
								$result = mysqli_query($link, "UPDATE $t_meal_plans_meals SET 
									meal_energy='$inp_meal_energy', meal_fat='$inp_meal_fat', meal_carb='$inp_meal_carbs', meal_protein='$inp_meal_proteins'
									 WHERE meal_id='$get_meal_id'");

	

								// Sum of Meal (refreshed)
								$query_meal = "SELECT meal_id, meal_meal_plan_id, meal_day_number, meal_number, meal_energy, meal_fat, meal_carb, meal_protein FROM $t_meal_plans_meals WHERE meal_meal_plan_id=$get_current_meal_plan_id AND meal_day_number=$entry_day_number_mysql AND meal_number=$x";
								$result_meal = mysqli_query($link, $query_meal);
								$row_meal = mysqli_fetch_row($result_meal);
								list($get_meal_id, $get_meal_meal_plan_id, $get_meal_day_number, $get_meal_number, $get_meal_energy, $get_meal_fat, $get_meal_carb, $get_meal_protein) = $row_meal;


							}
						}
						echo"
							  <tr>
							   <td>
								<table style=\"width: 100%;\">
								 <tr>
								  <td class=\"odd\" style=\"text-align: right;width: 9%;padding-right: 1%;\">
							          </td>
								  <td style=\"width: 26%;\">
								  </td>
								  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
									<span><i>$get_meal_energy</i></span>
								  </td>
								  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
									<span><i>$get_meal_fat</i></span>
								  </td>
								  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
									<span><i>$get_meal_carb</i></span>
								  </td>
								  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
									<span><i>$get_meal_protein</i></span>
								  </td>
								  <td style=\"width: 10%;text-align: right;\">
								  </td>
								 </tr>
								</table>
							   </td>
							  </tr>
				
						 </tbody>
						";
						if($x == 2 OR $x == 3){
							$inp_day_energy_with_training = $inp_day_energy_with_training+$get_meal_energy;
							$inp_day_fat_with_training = $inp_day_fat_with_training+$get_meal_fat;
							$inp_day_carb_with_training = $inp_day_carb_with_training+$get_meal_carb;
							$inp_day_protein_with_training = $inp_day_protein_with_training+$get_meal_protein;

						}
						else{
							$inp_day_energy_without_training = $inp_day_energy_without_training+$get_meal_energy;
							$inp_day_fat_without_training = $inp_day_fat_without_training+$get_meal_fat;
							$inp_day_carb_without_training = $inp_day_carb_without_training+$get_meal_carb;
							$inp_day_protein_without_training = $inp_day_protein_without_training+$get_meal_protein;
						}

						
					} // for


					// With trainging is training + everything else
					$inp_day_energy_with_training = $inp_day_energy_with_training+$inp_day_energy_without_training;
					$inp_day_fat_with_training = $inp_day_fat_with_training+$inp_day_fat_without_training;
					$inp_day_carb_with_training = $inp_day_carb_with_training+$inp_day_carb_without_training;
					$inp_day_protein_with_training = $inp_day_protein_with_training+$inp_day_protein_without_training;


					// With training other numbers
					$inp_day_sum_with_training 		  = $inp_day_fat_with_training+$inp_day_carb_with_training+$inp_day_protein_with_training;
					if($inp_day_sum_with_training == 0){
						$inp_day_fat_with_training_percentage	  = 0;
						$inp_day_carb_with_training_percentage	  = 0;
						$inp_day_protein_with_training_percentage = 0;
					}
					else{
						$inp_day_fat_with_training_percentage	  = round(($inp_day_fat_with_training / $inp_day_sum_with_training)*100, 0);
						$inp_day_carb_with_training_percentage	  = round(($inp_day_carb_with_training / $inp_day_sum_with_training)*100, 0);
						$inp_day_protein_with_training_percentage = round(($inp_day_protein_with_training / $inp_day_sum_with_training)*100, 0);
					}

					// Without training other numbers
					$inp_day_sum_without_training 		  = $inp_day_fat_without_training+$inp_day_carb_without_training+$inp_day_protein_without_training;
					if($inp_day_sum_without_training  == 0){
						$inp_day_fat_without_training_percentage	  = 0;
						$inp_day_carb_without_training_percentage	  = 0;
						$inp_day_protein_without_training_percentage = 0;
					}
					else{
						$inp_day_fat_without_training_percentage	  = round(($inp_day_fat_without_training / $inp_day_sum_without_training)*100, 0);
						$inp_day_carb_without_training_percentage	  = round(($inp_day_carb_without_training / $inp_day_sum_without_training)*100, 0);
						$inp_day_protein_without_training_percentage = round(($inp_day_protein_without_training / $inp_day_sum_without_training)*100, 0);
					}

					// Sum of all days
					$query_meal = "SELECT day_id, day_meal_plan_id, day_number, day_energy_without_training, day_fat_without_training, day_carb_without_training, day_protein_without_training, day_sum_without_training, day_fat_without_training_percentage, day_carb_without_training_percentage, day_protein_without_training_percentage, day_energy_with_training, day_fat_with_training, day_carb_with_training, day_protein_with_training, day_sum_with_training, day_fat_with_training_percentage, day_carb_with_training_percentage, day_protein_with_training_percentage FROM $t_meal_plans_days WHERE day_meal_plan_id=$get_current_meal_plan_id AND day_number=$entry_day_number_mysql";
					$result_meal = mysqli_query($link, $query_meal);
					$row_meal = mysqli_fetch_row($result_meal);
					list($get_day_id, $get_day_meal_plan_id, $get_day_number, $get_day_energy_without_training, $get_day_fat_without_training, $get_day_carb_without_training, $get_day_protein_without_training, $get_day_sum_without_training, $get_day_fat_without_training_percentage, $get_day_carb_without_training_percentage, $get_day_protein_without_training_percentage, $get_day_energy_with_training, $get_day_fat_with_training, $get_day_carb_with_training, $get_day_protein_with_training, $get_day_sum_with_training, $get_day_fat_with_training_percentage, $get_day_carb_with_training_percentage, $get_day_protein_with_training_percentage) = $row_meal;
						
					if($get_day_id == ""){


						mysqli_query($link, "INSERT INTO $t_meal_plans_days
						(day_id, day_meal_plan_id, day_number, 
						day_energy_without_training, day_fat_without_training, day_carb_without_training, day_protein_without_training, 
						day_sum_without_training, day_fat_without_training_percentage, day_carb_without_training_percentage, day_protein_without_training_percentage,
						day_energy_with_training, day_fat_with_training, day_carb_with_training, day_protein_with_training,
						day_sum_with_training, day_fat_with_training_percentage, day_carb_with_training_percentage, day_protein_with_training_percentage) 
						VALUES 
						(NULL, '$get_current_meal_plan_id', $entry_day_number_mysql, 
						'$inp_day_energy_without_training', '$inp_day_fat_without_training', '$inp_day_carb_without_training', '$inp_day_protein_without_training',
						'$inp_day_sum_without_training', '$inp_day_fat_without_training_percentage', '$inp_day_carb_without_training_percentage', '$inp_day_protein_without_training_percentage',
						'$inp_day_energy_with_training', '$inp_day_fat_with_training', '$inp_day_carb_with_training', '$inp_day_protein_with_training',
						'$inp_day_sum_with_training', '$inp_day_fat_with_training_percentage', '$inp_day_carb_with_training_percentage', '$inp_day_protein_with_training_percentage')")
						or die(mysqli_error($link));

						$get_day_energy_without_training = $inp_day_energy_without_training; $get_day_fat_without_training = $inp_day_fat_without_training;
						$get_day_carb_without_training = $inp_day_carb_without_training; $get_day_protein_without_training = $inp_day_protein_without_training;
						$get_day_energy_with_training = $inp_day_energy_with_training; $get_day_fat_with_training = $inp_day_fat_with_training;
						$get_day_carb_with_training = $inp_day_carb_with_training; $get_day_protein_with_training = $inp_day_protein_with_training;

						$get_day_fat_with_training_percentage	  = $inp_day_fat_with_training_percentage;
						$get_day_carb_with_training_percentage	  = $inp_day_carb_with_training_percentage;
						$get_day_protein_with_training_percentage = $inp_day_protein_with_training_percentage;

						$get_day_fat_without_training_percentage = $inp_day_fat_without_training_percentage;
						$get_day_carb_without_training_percentage = $inp_day_carb_without_training_percentage;
						$get_day_protein_without_training_percentage = $inp_day_protein_without_training_percentage;
					}
					else{



						if($get_day_energy_without_training != $inp_day_energy_without_training OR $get_day_fat_without_training != $inp_day_fat_without_training OR
						$get_day_carb_without_training != $inp_day_carb_without_training OR $get_day_protein_without_training != $inp_day_protein_without_training OR
						$get_day_energy_with_training != $inp_day_energy_with_training OR $get_day_fat_with_training != $inp_day_fat_with_training OR
						$get_day_carb_with_training != $inp_day_carb_with_training OR $get_day_protein_with_training != $inp_day_protein_with_training){


							$result = mysqli_query($link, "UPDATE $t_meal_plans_days SET 

									day_energy_without_training='$inp_day_energy_without_training', 
									day_fat_without_training='$inp_day_fat_without_training', 
									day_carb_without_training='$inp_day_carb_without_training', 
									day_protein_without_training='$inp_day_protein_without_training', 

									day_sum_with_training='$inp_day_sum_with_training',
									day_fat_with_training_percentage='$inp_day_fat_with_training_percentage',
									day_carb_with_training_percentage='$inp_day_carb_with_training_percentage',
									day_protein_with_training_percentage='$inp_day_protein_with_training_percentage',

									day_energy_with_training='$inp_day_energy_with_training', 
									day_fat_with_training='$inp_day_fat_with_training', 
									day_carb_with_training='$inp_day_carb_with_training', 
									day_protein_with_training='$inp_day_protein_with_training',

									day_sum_without_training='$inp_day_sum_without_training',
									day_fat_without_training_percentage='$inp_day_fat_without_training_percentage',
									day_carb_without_training_percentage='$inp_day_carb_without_training_percentage',
									day_protein_without_training_percentage='$inp_day_protein_without_training_percentage'
									WHERE day_id='$get_day_id'");



							$get_day_energy_without_training = $inp_day_energy_without_training; $get_day_fat_without_training = $inp_day_fat_without_training;
							$get_day_carb_without_training = $inp_day_carb_without_training; $get_day_protein_without_training = $inp_day_protein_without_training;
							$get_day_energy_with_training = $inp_day_energy_with_training; $get_day_fat_with_training = $inp_day_fat_with_training;
							$get_day_carb_with_training = $inp_day_carb_with_training; $get_day_protein_with_training = $inp_day_protein_with_training;

							$get_day_fat_with_training_percentage	  = $inp_day_fat_with_training_percentage;
							$get_day_carb_with_training_percentage	  = $inp_day_carb_with_training_percentage;
							$get_day_protein_with_training_percentage = $inp_day_protein_with_training_percentage;

							$get_day_fat_without_training_percentage = $inp_day_fat_without_training_percentage;
							$get_day_carb_without_training_percentage = $inp_day_carb_without_training_percentage;
							$get_day_protein_without_training_percentage = $inp_day_protein_without_training_percentage;

						}
					}
					echo"
					 <thead>
					  <tr>
					   <th scope=\"col\">

						<table style=\"width: 100%;\">
						 <tr>
						  <td class=\"odd\" style=\"text-align: right;width: 9%;padding-right: 1%;\">
					          </td>
						  <td style=\"width: 26%;\">
							<span>$l_sum_with_traning</span>
						  </td>
						  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
							<span>$get_day_energy_with_training</span>
						  </td>
						  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
							<span>$get_day_fat_with_training</span>
						  </td>
						  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
							<span>$get_day_carb_with_training</span>
						  </td>
						  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
							<span>$get_day_protein_with_training</span>
						  </td>
						  <td style=\"width: 10%;text-align: right;\">
						  </td>
						 </tr>
						</table>

					   </th>
					  </tr>
					 </thead>
					 <tbody>
					  <tr>
					   <td>

						<table style=\"width: 100%;\">
						 <tr>
						  <td class=\"odd\" style=\"text-align: right;width: 9%;padding-right: 1%;\">
					          </td>
						  <td style=\"width: 26%;\">
							<span>$l_percent</span>
						  </td>
						  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
						  </td>
						  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
							<span>$get_day_fat_with_training_percentage</span>
						  </td>
						  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
							<span>$get_day_carb_with_training_percentage</span>
						  </td>
						  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
							<span>$get_day_protein_with_training_percentage</span>
						  </td>
						  <td style=\"width: 10%;text-align: right;\">
						  </td>
						 </tr>
						</table>

					   </td>
					  </tr>
					 </tbody>
					 <thead>
					  <tr>
					   <th scope=\"col\">

						<table style=\"width: 100%;\">
						 <tr>
						  <td class=\"odd\" style=\"text-align: right;width: 9%;padding-right: 1%;\">
					          </td>
						  <td style=\"width: 26%;\">
							<span>$l_sum_without_traning</span>
						  </td>
						  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
							<span>$get_day_energy_without_training</span>
						  </td>
						  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
							<span>$get_day_fat_without_training</span>
						  </td>
						  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
							<span>$get_day_carb_without_training</span>
						  </td>
						  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
							<span>$get_day_protein_without_training</span>
						  </td>
						  <td style=\"width: 10%;text-align: right;\">
						  </td>
						 </tr>
						</table>
					   </th>
					  </tr>
					 </thead>
					 <tbody>
					  <tr>
					   <td>

						<table style=\"width: 100%;\">
						 <tr>
						  <td class=\"odd\" style=\"text-align: right;width: 9%;padding-right: 1%;\">
					          </td>
						  <td style=\"width: 26%;\">
							<span>$l_percent</span>
						  </td>
						  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
						  </td>
						  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
							<span>$get_day_fat_without_training_percentage</span>
						  </td>
						  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
							<span>$get_day_carb_without_training_percentage</span>
						  </td>
						  <td style=\"width: 10%;text-align: right;padding-right: 1%;\">
							<span>$get_day_protein_without_training_percentage</span>
						  </td>
						  <td style=\"width: 10%;text-align: right;\">
						  </td>
						 </tr>
						</table>
					   </td>
					  </tr>
					 </tbody>
				</table>
				";
			} // days in range
			echo"
			</div>
		<!-- //Current day -->
		";

			

		// Sum of all days
		$inp_meal_plan_total_energy_without_training = 0;
		$inp_meal_plan_total_fat_without_training = 0;
		$inp_meal_plan_total_carb_without_training = 0;
		$inp_meal_plan_total_protein_without_training = 0;
		$inp_meal_plan_total_energy_with_training = 0;
		$inp_meal_plan_total_fat_with_training = 0;
		$inp_meal_plan_total_carb_with_training = 0;
		$inp_meal_plan_total_protein_with_training = 0;
		$query_e = "SELECT day_id, day_meal_plan_id, day_number, day_energy_without_training, day_fat_without_training, day_carb_without_training, day_protein_without_training, day_sum_without_training, day_fat_without_training_percentage, day_carb_without_training_percentage, day_protein_without_training_percentage, day_energy_with_training, day_fat_with_training, day_carb_with_training, day_protein_with_training, day_sum_with_training, day_fat_with_training_percentage, day_carb_with_training_percentage, day_protein_with_training_percentage FROM $t_meal_plans_days WHERE day_meal_plan_id='$get_current_meal_plan_id'";
		$result_e = mysqli_query($link, $query_e);
		while($row_e = mysqli_fetch_row($result_e)) {
			list($get_day_id, $get_day_meal_plan_id, $get_day_number, $get_day_energy_without_training, $get_day_fat_without_training, $get_day_carb_without_training, $get_day_protein_without_training, $get_day_sum_without_training, $get_day_fat_without_training_percentage, $get_day_carb_without_training_percentage, $get_day_protein_without_training_percentage, $get_day_energy_with_training, $get_day_fat_with_training, $get_day_carb_with_training, $get_day_protein_with_training, $get_day_sum_with_training, $get_day_fat_with_training_percentage, $get_day_carb_with_training_percentage, $get_day_protein_with_training_percentage) = $row_e;

		
			$inp_meal_plan_total_energy_without_training = $inp_meal_plan_total_energy_without_training + $get_day_energy_without_training;
			$inp_meal_plan_total_fat_without_training = $inp_meal_plan_total_fat_without_training + $get_day_fat_without_training;
			$inp_meal_plan_total_carb_without_training = $inp_meal_plan_total_carb_without_training + $get_day_carb_without_training;
			$inp_meal_plan_total_protein_without_training = $inp_meal_plan_total_protein_without_training + $get_day_protein_without_training;

			$inp_meal_plan_total_energy_with_training = $inp_meal_plan_total_energy_with_training + $get_day_energy_with_training;
			$inp_meal_plan_total_fat_with_training = $inp_meal_plan_total_fat_with_training + $get_day_fat_with_training;
			$inp_meal_plan_total_carb_with_training = $inp_meal_plan_total_carb_with_training + $get_day_carb_with_training;
			$inp_meal_plan_total_protein_with_training = $inp_meal_plan_total_protein_with_training + $get_day_protein_with_training;
		}

		$inp_meal_plan_average_kcal_without_training = $inp_meal_plan_total_energy_without_training/$get_current_meal_plan_number_of_days;
		$inp_meal_plan_average_fat_without_training = $inp_meal_plan_total_fat_without_training/$get_current_meal_plan_number_of_days;
		$inp_meal_plan_average_carb_without_training = $inp_meal_plan_total_carb_without_training/$get_current_meal_plan_number_of_days;
		$inp_meal_plan_average_protein_without_training = $inp_meal_plan_total_protein_without_training/$get_current_meal_plan_number_of_days;
		$inp_meal_plan_average_kcal_with_training = $inp_meal_plan_total_energy_with_training/$get_current_meal_plan_number_of_days;
		$inp_meal_plan_average_fat_with_training = $inp_meal_plan_total_fat_with_training/$get_current_meal_plan_number_of_days;
		$inp_meal_plan_average_carb_with_training = $inp_meal_plan_total_carb_with_training/$get_current_meal_plan_number_of_days;
		$inp_meal_plan_average_protein_with_training = $inp_meal_plan_total_protein_with_training/$get_current_meal_plan_number_of_days;

		$result = mysqli_query($link, "UPDATE $t_meal_plans SET 
			meal_plan_total_energy_without_training = '$inp_meal_plan_total_energy_without_training',
			meal_plan_total_fat_without_training = '$inp_meal_plan_total_fat_without_training',
			meal_plan_total_carb_without_training = '$inp_meal_plan_total_carb_without_training',
			meal_plan_total_protein_without_training = '$inp_meal_plan_total_protein_without_training',
			meal_plan_total_energy_with_training = '$inp_meal_plan_total_energy_with_training',
			meal_plan_total_fat_with_training = '$inp_meal_plan_total_fat_with_training',
			meal_plan_total_carb_with_training = '$inp_meal_plan_total_carb_with_training',
			meal_plan_total_protein_with_training = '$inp_meal_plan_total_protein_with_training',
			meal_plan_average_kcal_without_training = '$inp_meal_plan_average_kcal_without_training',
			meal_plan_average_fat_without_training = '$inp_meal_plan_average_fat_without_training',
			meal_plan_average_carb_without_training = '$inp_meal_plan_average_carb_without_training',
			meal_plan_average_protein_without_training = '$inp_meal_plan_average_protein_without_training',
			meal_plan_average_kcal_with_training = '$inp_meal_plan_average_kcal_with_training',
			meal_plan_average_fat_with_training = '$inp_meal_plan_average_fat_with_training',
			meal_plan_average_carb_with_training = '$inp_meal_plan_average_carb_with_training',
			meal_plan_average_protein_with_training = '$inp_meal_plan_average_protein_with_training'
		WHERE meal_plan_id='$get_current_meal_plan_id'");


	} // found
}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/exercises/new_exercise.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>