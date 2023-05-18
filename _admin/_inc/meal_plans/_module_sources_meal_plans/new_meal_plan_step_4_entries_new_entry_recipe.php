<?php 
/**
*
* File: meal_plans/new_meal_plan_step_2_entries.php
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
$website_title = "$l_new_meal_plan - $l_meal_plans";
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
		if($process == 1){



			$inp_entry_day_number = output_html($entry_day_number);
			$inp_entry_day_number_mysql = quote_smart($link, $inp_entry_day_number);

			$inp_entry_meal_number = output_html($entry_meal_number);
			$inp_entry_meal_number_mysql = quote_smart($link, $inp_entry_meal_number);


			if(isset($_GET['entry_serving_size'])) {
				$entry_serving_size = $_GET['entry_serving_size'];
			}
			else{
				$entry_serving_size = 1;
			}
			$inp_entry_serving_size = output_html($entry_serving_size);
			$inp_entry_serving_size = str_replace(",", ".", $inp_entry_serving_size);
			$inp_entry_serving_size_mysql = quote_smart($link, $inp_entry_serving_size);
			if($inp_entry_serving_size == ""){
				$url = "new_meal_plan_step_2_entries_new_entry_recipe.php?meal_plan_id=$meal_plan_id&entry_day_number=$entry_day_number&entry_meal_number=$entry_meal_number&l=$l&action=new_entry_food";
				$url = $url . "&ft=error&fm=missing_amount";
				header("Location: $url");
				exit;
			}
				
			// get recipe
			if(isset($_GET['recipe_id'])) {
				$recipe_id = $_GET['recipe_id'];
				$recipe_id = strip_tags(stripslashes($recipe_id));
			}
			else{
				$url = "new_meal_plan_step_2_entries_new_entry_recipe.php?meal_plan_id=$meal_plan_id&entry_day_number=$entry_day_number&entry_meal_number=$entry_meal_number&l=$l&action=new_entry_food";
				$url = $url . "&ft=error&fm=missing_recipe";
				header("Location: $url");
				exit;
			}
			
			// Get recipe	
			$recipe_id_mysql = quote_smart($link, $recipe_id);

			// get recipe
			$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_country, recipe_introduction, recipe_directions, recipe_image_path, recipe_image, recipe_thumb_278x156, recipe_video, recipe_date, recipe_date_saying, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_times_favorited, recipe_user_ip, recipe_notes, recipe_password, recipe_last_viewed, recipe_age_restriction, recipe_published FROM $t_recipes WHERE recipe_id=$recipe_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_country, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb_278x156, $get_recipe_video, $get_recipe_date, $get_recipe_date_saying, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_times_favorited, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password, $get_recipe_last_viewed, $get_recipe_age_restriction, $get_recipe_published) = $row;
			if($get_recipe_id == ""){

				$url = "new_meal_plan_step_2_entries_new_entry_recipe.php?meal_plan_id=$meal_plan_id&entry_day_number=$entry_day_number&entry_meal_number=$entry_meal_number&l=$l&action=new_entry_food";
				$url = $url . "&ft=error&fm=recipe_specified_not_found";
				header("Location: $url");
				exit;
			}

			// get numbers
			$query_n = "SELECT number_id, number_recipe_id, number_servings, number_energy_metric, number_fat_metric, number_saturated_fat_metric, number_monounsaturated_fat_metric, number_polyunsaturated_fat_metric, number_cholesterol_metric, number_carbohydrates_metric, number_carbohydrates_of_which_sugars_metric, number_dietary_fiber_metric, number_proteins_metric, number_salt_metric, number_sodium_metric, number_energy_serving, number_fat_serving, number_saturated_fat_serving, number_monounsaturated_fat_serving, number_polyunsaturated_fat_serving, number_cholesterol_serving, number_carbohydrates_serving, number_carbohydrates_of_which_sugars_serving, number_dietary_fiber_serving, number_proteins_serving, number_salt_serving, number_sodium_serving, number_energy_total, number_fat_total, number_saturated_fat_total, number_monounsaturated_fat_total, number_polyunsaturated_fat_total, number_cholesterol_total, number_carbohydrates_total, number_carbohydrates_of_which_sugars_total, number_dietary_fiber_total, number_proteins_total, number_salt_total, number_sodium_total FROM $t_recipes_numbers WHERE number_recipe_id=$get_recipe_id";
			$result_n = mysqli_query($link, $query_n);
			$row_n = mysqli_fetch_row($result_n);
			list($get_number_id, $get_number_recipe_id, $get_number_servings, $get_number_energy_metric, $get_number_fat_metric, $get_number_saturated_fat_metric, $get_number_monounsaturated_fat_metric, $get_number_polyunsaturated_fat_metric, $get_number_cholesterol_metric, $get_number_carbohydrates_metric, $get_number_carbohydrates_of_which_sugars_metric, $get_number_dietary_fiber_metric, $get_number_proteins_metric, $get_number_salt_metric, $get_number_sodium_metric, $get_number_energy_serving, $get_number_fat_serving, $get_number_saturated_fat_serving, $get_number_monounsaturated_fat_serving, $get_number_polyunsaturated_fat_serving, $get_number_cholesterol_serving, $get_number_carbohydrates_serving, $get_number_carbohydrates_of_which_sugars_serving, $get_number_dietary_fiber_serving, $get_number_proteins_serving, $get_number_salt_serving, $get_number_sodium_serving, $get_number_energy_total, $get_number_fat_total, $get_number_saturated_fat_total, $get_number_monounsaturated_fat_total, $get_number_polyunsaturated_fat_total, $get_number_cholesterol_total, $get_number_carbohydrates_total, $get_number_carbohydrates_of_which_sugars_total, $get_number_dietary_fiber_total, $get_number_proteins_total, $get_number_salt_total, $get_number_sodium_total) = $row_n;

			$inp_entry_name = output_html($get_recipe_title);
			$inp_entry_name_mysql = quote_smart($link, $inp_entry_name);

			$inp_entry_manufacturer_name = output_html("");
			$inp_entry_manufacturer_name_mysql = quote_smart($link, $inp_entry_manufacturer_name);

			if($inp_entry_serving_size == "1"){
				$inp_entry_serving_size_measurement = output_html(strtolower($l_serving_abbreviation));
			}
			else{
				$inp_entry_serving_size_measurement = output_html(strtolower($l_servings_abbreviation));
			}
			$inp_entry_serving_size_measurement_mysql = quote_smart($link, $inp_entry_serving_size_measurement);

			// Number inputs
			$inp_entry_energy_per_entry = round($inp_entry_serving_size*$get_number_energy_serving, 1);
			$inp_entry_energy_per_entry_mysql = quote_smart($link, $inp_entry_energy_per_entry);

			$inp_entry_fat_per_entry = round($inp_entry_serving_size*$get_number_fat_serving, 1);
			$inp_entry_fat_per_entry_mysql = quote_smart($link, $inp_entry_fat_per_entry);

			$inp_entry_saturated_fat_per_entry = round($inp_entry_serving_size*$get_number_saturated_fat_serving, 1);
			$inp_entry_saturated_fat_per_entry_mysql = quote_smart($link, $inp_entry_saturated_fat_per_entry);

			$inp_entry_monounsaturated_fat_per_entry = round($inp_entry_serving_size*$get_number_monounsaturated_fat_serving, 1);
			$inp_entry_monounsaturated_fat_per_entry_mysql = quote_smart($link, $inp_entry_monounsaturated_fat_per_entry);

			$inp_entry_polyunsaturated_fat_per_entry = round($inp_entry_serving_size*$get_number_polyunsaturated_fat_serving, 1);
			$inp_entry_polyunsaturated_fat_per_entry_mysql = quote_smart($link, $inp_entry_polyunsaturated_fat_per_entry);

			$inp_entry_cholesterol_per_entry = round($inp_entry_serving_size*$get_number_cholesterol_serving, 1);
			$inp_entry_cholesterol_per_entry_mysql = quote_smart($link, $inp_entry_cholesterol_per_entry);

			$inp_entry_carbohydrates_per_entry = round($inp_entry_serving_size*$get_number_carbohydrates_serving, 1);
			$inp_entry_carbohydrates_per_entry_mysql = quote_smart($link, $inp_entry_carbohydrates_per_entry);

			$inp_entry_carbohydrates_of_which_sugars_per_entry = round($inp_entry_serving_size*$get_number_carbohydrates_of_which_sugars_serving, 1);
			$inp_entry_carbohydrates_of_which_sugars_per_entry_mysql = quote_smart($link, $inp_entry_carbohydrates_of_which_sugars_per_entry);

			$inp_entry_dietary_fiber_per_entry = round($inp_entry_serving_size*$get_number_dietary_fiber_serving, 1);
			$inp_entry_dietary_fiber_per_entry_mysql = quote_smart($link, $inp_entry_dietary_fiber_per_entry);

			$inp_entry_proteins_per_entry = round($inp_entry_serving_size*$get_number_proteins_serving, 1);
			$inp_entry_proteins_per_entry_mysql = quote_smart($link, $inp_entry_proteins_per_entry);

			$inp_entry_salt_per_entry = round($inp_entry_serving_size*$get_number_salt_serving, 1);
			$inp_entry_salt_per_entry_mysql = quote_smart($link, $inp_entry_salt_per_entry);

			$inp_entry_sodium_per_entry = round($inp_entry_serving_size*$get_number_sodium_serving, 1);
			$inp_entry_sodium_per_entry_mysql = quote_smart($link, $inp_entry_sodium_per_entry);




			// Insert
			mysqli_query($link, "INSERT INTO $t_meal_plans_entries
			(entry_id, entry_meal_plan_id, entry_day_number, entry_meal_number, entry_weight, entry_food_id, entry_recipe_id, 
			entry_name, entry_manufacturer_name, entry_main_category_id, entry_serving_size, entry_serving_size_measurement, 
			entry_energy_per_entry, entry_fat_per_entry, entry_carb_per_entry, entry_protein_per_entry) 
			VALUES 
			(NULL, '$get_current_meal_plan_id', $inp_entry_day_number_mysql, $inp_entry_meal_number_mysql, '0', '0', '$get_recipe_id', 
			$inp_entry_name_mysql, $inp_entry_manufacturer_name_mysql, $get_recipe_category_id, $inp_entry_serving_size_mysql, $inp_entry_serving_size_measurement_mysql, 
			$inp_entry_energy_per_entry_mysql, $inp_entry_fat_per_entry_mysql, $inp_entry_carbohydrates_per_entry_mysql, $inp_entry_proteins_per_entry_mysql)")
			or die(mysqli_error($link));

			$url = "new_meal_plan_step_2_entries.php?meal_plan_id=$meal_plan_id&entry_day_number=$entry_day_number&entry_meal_number=$entry_meal_number&l=$l";
			$url = $url . "&ft=success&fm=recipe_added";
			header("Location: $url");
			exit;
		}

		/*- Recipe categories ----------------------------------------- */
		if(isset($_GET['inp_entry_recipe_query'])){
			$inp_entry_recipe_query = $_GET['inp_entry_recipe_query'];
			$inp_entry_recipe_query = strip_tags(stripslashes($inp_entry_recipe_query));
			$inp_entry_recipe_query = output_html($inp_entry_recipe_query);
		} else{
			$inp_entry_recipe_query = "";
		}
		if(isset($_GET['recipe_category_id'])){
			$recipe_category_id = $_GET['recipe_category_id'];
			$recipe_category_id = strip_tags(stripslashes($recipe_category_id));
		} else{
			$recipe_category_id = "";
		}



		echo"
		<h1>$get_current_meal_plan_title</h1>
	
				<script>
										\$(function(){
											// bind change event to select
											\$('#inp_amount_select').on('change', function () {
												var url = \$(this).val(); // get selected value
												if (url) { // require a URL
 													window.location = url; // redirect
												}
												return false;
											});
										});
										</script>

		<!-- Categories -->
			<div class=\"left\" style=\"width: 15%;padding-right:1%;\">
				<table class=\"hor-zebra\">
				 <tbody>
				  <tr>
				   <td style=\"paddding: 0px 0px 0px 0px;margin: 0px 0px 0px 0px;\">
					<p style=\"paddding: 0px 0px 0px 0px;margin: 0px 0px 0px 0px;\">";

						// Get all categories
						$query = "SELECT category_id, category_name, category_image_path, category_image_file FROM $t_recipes_categories ORDER BY category_name ASC";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_category_id, $get_category_name, $get_category_image_path, $get_category_image_file) = $row;

							// Translations
							$query_t = "SELECT category_translation_id, category_translation_value FROM $t_recipes_categories_translations WHERE category_id=$get_category_id AND category_translation_language=$l_mysql";
							$result_t = mysqli_query($link, $query_t);
							$row_t = mysqli_fetch_row($result_t);
							list($get_category_translation_id, $get_category_translation_value) = $row_t;



							echo"		";
							echo"<a href=\"new_meal_plan_step_2_entries_new_entry_recipe.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;action=new_entry_food&amp;recipe_category_id=$get_category_id&amp;l=$l\""; if($recipe_category_id == "$get_category_id"){ echo" style=\"font-weight: bold;\"";}echo">$get_category_translation_value</a><br />\n";

						}

						echo"
						</p>
					   </td>
					  </tr>
					 </tbody>
					</table>
				</div>
			<!-- //Categories -->

			<!-- Current day -->
				<div class=\"right\" style=\"width: 77%;\">
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

					<!-- User adaptet view -->";
						if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
							$my_user_id = $_SESSION['user_id'];
							$my_user_id = output_html($my_user_id);
							$my_user_id_mysql = quote_smart($link, $my_user_id);
	
							$query_t = "SELECT view_id, view_user_id, view_ip, view_year, view_system, view_hundred_metric, view_serving, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_meal_plans_user_adapted_view WHERE view_user_id=$my_user_id_mysql";
							$result_t = mysqli_query($link, $query_t);
							$row_t = mysqli_fetch_row($result_t);
							list($get_current_view_id, $get_current_view_user_id, $get_current_view_ip, $get_current_view_year, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_serving, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
						}
						else{
							// IP
							$my_user_ip = $_SERVER['REMOTE_ADDR'];
							$my_user_ip = output_html($my_user_ip);
							$my_user_ip_mysql = quote_smart($link, $my_user_ip);
	
							$query_t = "SELECT view_id, view_user_id, view_ip, view_year, view_system, view_hundred_metric, view_serving, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_meal_plans_user_adapted_view WHERE view_ip=$my_user_ip_mysql";
							$result_t = mysqli_query($link, $query_t);
							$row_t = mysqli_fetch_row($result_t);
							list($get_current_view_id, $get_current_view_user_id, $get_current_view_ip, $get_current_view_year, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_serving, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;

						}
						if($get_current_view_id == ""){
							$get_current_view_system = "metric";
							$get_current_view_hundred_metric = 1;
							$get_current_view_pcs_metric = 1;
						}
						$r = "new_meal_plan_step_2_entries_new_entry_recipe.php?meal_plan_id=$meal_plan_id" . "amp;entry_day_number=$entry_day_number" . "amp;entry_meal_number=$entry_meal_number";
						echo"
						<p><a id=\"adapter_view\"></a>
						<b>$l_show_per:</b>
						<input type=\"checkbox\" name=\"inp_show_hundred_metric\" class=\"onclick_go_to_url\"  data-target=\"user_adapted_view.php?set=hundred_metric&amp;value=0&amp;process=1&amp;referer=$r&amp;l=$l\""; if($get_current_view_hundred_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_hundred
						<input type=\"checkbox\" name=\"inp_show_serving\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=serving&amp;value=0&amp;process=1&amp;referer=$r&amp;l=$l\""; if($get_current_view_serving == "1"){ echo" checked=\"checked\""; } echo" /> $l_serving
						<input type=\"checkbox\" name=\"inp_show_eight_us\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=eight_us&amp;value=0&amp;process=1&amp;referer=$r&amp;l=$l\""; if($get_current_view_eight_us == "1"){ echo" checked=\"checked\""; } echo" /> $l_eight
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

					<!-- //User adaptet view -->
					";
					
					echo"
					<form method=\"get\" action=\"new_meal_plan_step_2_entries_new_entry_recipe.php\" enctype=\"multipart/form-data\">
						<p><b>$l_recipe_search</b><br />
						<input type=\"text\" name=\"inp_entry_recipe_query\" value=\"";if(isset($_GET['inp_entry_recipe_query'])){ echo"$inp_entry_recipe_query"; } echo"\" size=\"30\" />
						<input type=\"hidden\" name=\"meal_plan_id\" value=\"$meal_plan_id\" />
						<input type=\"hidden\" name=\"entry_day_number\" value=\"$entry_day_number\" />
						<input type=\"hidden\" name=\"entry_meal_number\" value=\"$entry_meal_number\" />
						<input type=\"hidden\" name=\"l\" value=\"$l\" />
						<input type=\"hidden\" name=\"action\" value=\"$action\" />
						<input type=\"submit\" value=\"$l_search\" class=\"btn btn_default\" />
						<a href=\"$root/recipes/submit_recipe.php?l=$l\" class=\"btn btn_default\">$l_create_new_recipe</a>
						</p>
					</form>


					<!-- Recipe list -->
						";
	
						// Set layout
						$x = 0;

						// Get all recipes
						$query = "SELECT recipe_id, recipe_title, recipe_category_id, recipe_introduction, recipe_image_path, recipe_image, recipe_thumb_278x156, recipe_unique_hits FROM $t_recipes WHERE recipe_language=$l_mysql AND recipe_published=1";
						if($recipe_category_id != ""){
							$recipe_category_id_mysql = quote_smart($link, $recipe_category_id);
							$query = $query . " AND recipe_category_id=$recipe_category_id_mysql";
						}
						if($inp_entry_recipe_query != ""){
							$inp_entry_recipe_query = $inp_entry_recipe_query . "%";
							$inp_entry_recipe_query_mysql = quote_smart($link, $inp_entry_recipe_query);
							$query = $query . " AND recipe_title LIKE $inp_entry_recipe_query_mysql";
						}
						$query = $query . " ORDER BY recipe_last_viewed DESC";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_recipe_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_introduction, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb_278x156, $get_recipe_unique_hits) = $row;

							if($get_recipe_image != "" && file_exists("$root/$get_recipe_image_path/$get_recipe_image")){
								// Category
								$query_cat = "SELECT category_translation_id, category_translation_value FROM $t_recipes_categories_translations WHERE category_id=$get_recipe_category_id AND category_translation_language=$l_mysql";
								$result_cat = mysqli_query($link, $query_cat);
								$row_cat = mysqli_fetch_row($result_cat);
								list($get_category_translation_id, $get_category_translation_value) = $row_cat;

								// Select Nutrients
								$query_n = "SELECT number_id, number_recipe_id, number_servings, number_energy_metric, number_fat_metric, number_saturated_fat_metric, number_monounsaturated_fat_metric, number_polyunsaturated_fat_metric, number_cholesterol_metric, number_carbohydrates_metric, number_carbohydrates_of_which_sugars_metric, number_dietary_fiber_metric, number_proteins_metric, number_salt_metric, number_sodium_metric, number_energy_serving, number_fat_serving, number_saturated_fat_serving, number_monounsaturated_fat_serving, number_polyunsaturated_fat_serving, number_cholesterol_serving, number_carbohydrates_serving, number_carbohydrates_of_which_sugars_serving, number_dietary_fiber_serving, number_proteins_serving, number_salt_serving, number_sodium_serving FROM $t_recipes_numbers WHERE number_recipe_id=$get_recipe_id";
								$result_n = mysqli_query($link, $query_n);
								$row_n = mysqli_fetch_row($result_n);
								list($get_number_id, $get_number_recipe_id, $get_number_servings, $get_number_energy_metric, $get_number_fat_metric, $get_number_saturated_fat_metric, $get_number_monounsaturated_fat_metric, $get_number_polyunsaturated_fat_metric, $get_number_cholesterol_metric, $get_number_carbohydrates_metric, $get_number_carbohydrates_of_which_sugars_metric, $get_number_dietary_fiber_metric, $get_number_proteins_metric, $get_number_salt_metric, $get_number_sodium_metric, $get_number_energy_serving, $get_number_fat_serving, $get_number_saturated_fat_serving, $get_number_monounsaturated_fat_serving, $get_number_polyunsaturated_fat_serving, $get_number_cholesterol_serving, $get_number_carbohydrates_serving, $get_number_carbohydrates_of_which_sugars_serving, $get_number_dietary_fiber_serving, $get_number_proteins_serving, $get_number_salt_serving, $get_number_sodium_serving) = $row_n;



								if($x == 0){
									echo"
									<div class=\"clear\"></div>
									<div class=\"left_center_right_left\" style=\"text-align: center;padding-bottom: 20px;\">
									";
								}
								elseif($x == 1){
									echo"
									<div class=\"left_center_right_center\" style=\"text-align: center;padding-bottom: 20px;\">
									";
								}
								elseif($x == 2){
									echo"
									<div class=\"left_center_right_right\" style=\"text-align: center;padding-bottom: 20px;\">
									";
								}
	

								echo"
								<p style=\"padding-bottom:5px;\">
								<a href=\"$root/recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\"><a href=\"$root/recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\"><img src=\"$root/$get_recipe_image_path/$get_recipe_thumb_278x156\" alt=\"$get_recipe_image\" /></a><br />
								<a href=\"$root/recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\" style=\"font-weight: bold;color: #444444;\">$get_recipe_title</a><br />
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

									<!-- Add food -->
										<form>
										<p>
										<select id=\"inp_amount_select\">
											<option value=\"new_meal_plan_step_2_entries_new_entry_recipe.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;recipe_id=$get_recipe_id&amp;entry_serving_size=1&amp;l=$l&amp;process=1\">1</option>
											<option value=\"new_meal_plan_step_2_entries_new_entry_recipe.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;recipe_id=$get_recipe_id&amp;entry_serving_size=2&amp;l=$l&amp;process=1\">2</option>
											<option value=\"new_meal_plan_step_2_entries_new_entry_recipe.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;recipe_id=$get_recipe_id&amp;entry_serving_size=3&amp;l=$l&amp;process=1\">3</option>
											<option value=\"new_meal_plan_step_2_entries_new_entry_recipe.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;recipe_id=$get_recipe_id&amp;entry_serving_size=4&amp;l=$l&amp;process=1\">4</option>
											<option value=\"new_meal_plan_step_2_entries_new_entry_recipe.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;recipe_id=$get_recipe_id&amp;entry_serving_size=5&amp;l=$l&amp;process=1\">5</option>
											<option value=\"new_meal_plan_step_2_entries_new_entry_recipe.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;recipe_id=$get_recipe_id&amp;entry_serving_size=6&amp;l=$l&amp;process=1\">6</option>
											<option value=\"new_meal_plan_step_2_entries_new_entry_recipe.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;recipe_id=$get_recipe_id&amp;entry_serving_size=7&amp;l=$l&amp;process=1\">7</option>
											<option value=\"new_meal_plan_step_2_entries_new_entry_recipe.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;recipe_id=$get_recipe_id&amp;entry_serving_size=8&amp;l=$l&amp;process=1\">8</option>
										</select>
										<a href=\"new_meal_plan_step_2_entries_new_entry_recipe.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;recipe_id=$get_recipe_id&amp;l=$l&amp;process=1\" class=\"btn btn_default\">$l_add</a>
										</p>
										</form>
										
									<!-- //Add food -->
								</div>
								";

								// Increment
								$x++;
		
								// Reset
								if($x == 3){
									$x = 0;
								}
							} // has image
						} // while

						echo"

					<!-- //Recipe list -->
					
					";
				}
				echo"
				</div>
				<div class=\"clear\"></div>
			<!-- //Current day -->
			";
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