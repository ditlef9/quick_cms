<?php 
/**
*
* File: meal_plans/meal_plan_edit.php
* Version 2.0.0
* Date 20:48 01.04.2021
* Copyright (c) 2021 S. A. Ditlefsen
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
include("$root/_admin/_translations/site/$l/meal_plans/ts_meal_plan_edit.php");

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

			/*- Food categories ----------------------------------------- */
			if(isset($_GET['main_category_id'])){
				$main_category_id= $_GET['main_category_id'];
				$main_category_id = strip_tags(stripslashes($main_category_id));
			}
			else{
				$main_category_id = "";
			}
			if(isset($_GET['sub_category_id'])){
				$sub_category_id= $_GET['sub_category_id'];
				$sub_category_id = strip_tags(stripslashes($sub_category_id));
			}
			else{
				$sub_category_id = "";
			}
			if(isset($_GET['inp_entry_food_query'])){
				$inp_entry_food_query = $_GET['inp_entry_food_query'];
				$inp_entry_food_query = strip_tags(stripslashes($inp_entry_food_query));
				$inp_entry_food_query = output_html($inp_entry_food_query);
			} else{
				$inp_entry_food_query = "";
			}



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
		
		if($action == ""){

			echo"
			<h1>$get_current_meal_plan_title</h1>
	
			<!-- You are here -->
				<p><b>$l_you_are_here:</b><br />
				<a href=\"my_meal_plans.php?l=$l\">$l_my_meal_plans</a>
				&gt;
				<a href=\"meal_plan_edit.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;l=$l\">$l_meal_plan</a>
				&gt;
				<a href=\"meal_plan_edit_new_entry_food.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;l=$l\">$l_add_food</a>
				</p>
			<!-- //You are here -->
				
				
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
			

			<!-- Search -->
				<!-- Search engines Autocomplete -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_entry_food_query\"]').focus();
				});
				\$(document).ready(function () {
					\$('#inp_entry_food_query').keyup(function () {
        					// getting the value that user typed
        					var searchString    = $(\"#inp_entry_food_query\").val();
        					// forming the queryString
       						var data            = 'l=$l&meal_plan_id=$meal_plan_id&entry_day_number=$entry_day_number&entry_meal_number=$entry_meal_number&q='+ searchString;
         
        					// if searchString is not empty
        					if(searchString) {
        						// ajax call
        						\$.ajax({
        							type: \"POST\",
        							url: \"meal_plan_edit_new_entry_food_jquery.php\",
        							data: data,
								beforeSend: function(html) { // this happens before actual call
									\$(\"#nettport_search_results\").html(''); 
								},
        							success: function(html){
        								\$(\"#nettport_search_results\").append(html);
        							}
        						});
       						}
        					return false;
        				});
        			});
				</script>
				<!-- //Search engines Autocomplete -->

				<form method=\"get\" action=\"meal_plan_edit_new_entry_food.php\" enctype=\"multipart/form-data\" id=\"inp_entry_food_query_form\">
					<p><b>$l_food</b><br />
					<input type=\"text\" id=\"inp_entry_food_query\" name=\"inp_entry_food_query\" value=\"";if(isset($_GET['inp_entry_food_query'])){ echo"$inp_entry_food_query"; } echo"\" size=\"20\" />
					<input type=\"hidden\" name=\"meal_plan_id\" value=\"$meal_plan_id\" />
					<input type=\"hidden\" name=\"entry_day_number\" value=\"$entry_day_number\" />
					<input type=\"hidden\" name=\"entry_meal_number\" value=\"$entry_meal_number\" />
					<input type=\"hidden\" name=\"l\" value=\"$l\" />
					<input type=\"hidden\" name=\"action\" value=\"search_for_food\" />
					<input type=\"submit\" value=\"$l_search\" class=\"btn btn_default\" />
					<a href=\"$root/food/new_food.php?l=$l\" class=\"btn btn_default\">$l_new_food</a>
					</p>
				</form>
			<!-- //Search -->

			<!-- Meal plans Food categories -->
				<div class=\"vertical\">
					<ul>";

					// Get all categories
					$query = "SELECT $t_food_categories.category_id, $t_food_categories.category_name, $t_food_categories.category_parent_id FROM $t_food_categories";
					$query = $query . " WHERE category_user_id='0' AND category_parent_id='0' ORDER BY category_name ASC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_category_id, $get_category_name, $get_category_parent_id) = $row;

						// Translation
						$query_t = "SELECT category_translation_value FROM $t_food_categories_translations WHERE category_id=$get_category_id AND category_translation_language=$l_mysql";
						$result_t = mysqli_query($link, $query_t);
						$row_t = mysqli_fetch_row($result_t);
						list($get_category_translation_value) = $row_t;

							echo"		";
							echo"<li><a href=\"meal_plan_edit_new_entry_food.php?action=open_main_food_category&amp;meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;main_category_id=$get_category_id&amp;l=$l\""; if($main_category_id == "$get_category_id"){ echo" style=\"font-weight: bold;\"";}echo">$get_category_translation_value</a></li>\n";



						}

						echo"
					</ul>
				</div>
			<!-- //Meal plans :: Food categories -->


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
				$r = "new_meal_plan_step_2_entries_new_entry_food.php?meal_plan_id=$meal_plan_id" . "amp;entry_day_number=$entry_day_number" . "amp;entry_meal_number=$entry_meal_number";
				echo"
				<p><a id=\"adapter_view\"></a>
				<b>$l_show_per:</b>
				<input type=\"checkbox\" name=\"inp_show_hundred_metric\" class=\"onclick_go_to_url\"  data-target=\"user_adapted_view.php?set=hundred_metric&amp;value=0&amp;process=1&amp;referer=$r&amp;l=$l\""; if($get_current_view_hundred_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_hundred
				<input type=\"checkbox\" name=\"inp_show_pcs_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=pcs_metric&amp;value=0&amp;process=1&amp;referer=$r&amp;l=$l\""; if($get_current_view_pcs_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_pcs_g
				<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=eight_us&amp;value=0&amp;process=1&amp;referer=$r&amp;l=$l\""; if($get_current_view_eight_us == "1"){ echo" checked=\"checked\""; } echo" /> $l_eight
				<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=pcs_us&amp;value=0&amp;process=1&amp;referer=$r&amp;l=$l\""; if($get_current_view_pcs_us == "1"){ echo" checked=\"checked\""; }  echo" /> $l_pcs_oz
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

			<!-- Food list -->
				<div id=\"nettport_search_results\">";


					// Get all food
					$x = 0;
					$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_comments, food_likes, food_dislikes, food_likes_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_language=$l_mysql ORDER BY food_last_used DESC LIMIT 0,60";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_comments, $get_food_likes, $get_food_dislikes, $get_food_likes_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row;
			
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

						// Thumb
						if($get_food_image_a != "" && file_exists("../$get_food_image_path/$get_food_image_a")){
							$thumb = "../$get_food_image_path/$get_food_thumb_a_small";
						}
						else{
							$thumb = "_gfx/no_thumb.png";
						}


						echo"
						<p style=\"padding-bottom:5px;\">
						<a href=\"$root/food/view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\"><img src=\"$thumb\" alt=\"$get_food_image_a\" style=\"margin-bottom: 5px;\" /></a><br />
						<a href=\"$root/food/view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\" style=\"font-weight: bold;color: #444444;\">$title</a><br />
						</p>";

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
						} // get_current_view_hundred_metric
						echo"
						<!-- Add food to  -->
							<form method=\"post\" action=\"meal_plan_edit_new_entry_food.php?action=do_add_entry_to_meal_plan&amp;meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
							<p>
							<input type=\"hidden\" name=\"inp_entry_food_id\" value=\"$get_food_id\" />
							";
							if($get_current_view_hundred_metric == "1" OR $get_current_view_pcs_metric == "1"){
								if($get_food_serving_size_pcs_measurement == "g"){
									echo"
									<input type=\"text\" name=\"inp_entry_food_serving_size\" size=\"2\" value=\"$get_food_serving_size_metric\" />
									<input type=\"submit\" name=\"inp_submit_metric\" value=\"$get_food_serving_size_measurement_metric\" class=\"btn btn_default\" />
									";
								}
								else{
									echo"
									<input type=\"text\" name=\"inp_entry_food_serving_size\" size=\"2\" value=\"$get_food_serving_size_pcs\" />
									<input type=\"submit\" name=\"inp_submit_metric\" value=\"$get_food_serving_size_measurement_metric\" class=\"btn btn_default\" />
									<input type=\"submit\" name=\"inp_submit_pcs\" value=\"$get_food_serving_size_pcs_measurement\" class=\"btn btn_default\" />
									";
								}
							} // metric
							if($get_current_view_eight_us == "1" OR $get_current_view_pcs_us == "1"){
								echo"
								<input type=\"text\" name=\"inp_entry_food_serving_size\" size=\"2\" value=\"$get_food_serving_size_pcs\" />
								<input type=\"submit\" name=\"inp_submit_us\" value=\"$get_food_serving_size_measurement_metric\" class=\"btn btn_default\" />
								<input type=\"submit\" name=\"inp_submit_pcs\" value=\"$get_food_serving_size_pcs_measurement\" class=\"btn btn_default\" />
								";
							} // us
							echo"
							</p>
							</form>
						<!-- //Add food -->
						</div>
						";
						// Increment
						$x++;
		
						// Reset
						if($x == 4){
							$x = 0;
						}
					} // while


				echo"
				</div> <!-- //nettport_search_results -->
			<!-- //Food list -->
			";
		}
		elseif($action == "open_main_food_category"){
			// Get main category
			$main_category_id_mysql = quote_smart($link, $main_category_id);
			$query = "SELECT category_id, category_user_id, category_name, category_parent_id FROM $t_food_categories WHERE category_id=$main_category_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_main_category_id, $get_current_main_category_user_id, $get_current_main_category_name, $get_current_main_category_parent_id) = $row;
			if($get_current_main_category_id == ""){
				echo"Server error 404";
			}
			else{
				// Translation
				$query_t = "SELECT category_translation_value FROM $t_food_categories_translations WHERE category_id=$get_current_main_category_id AND category_translation_language=$l_mysql";
				$result_t = mysqli_query($link, $query_t);
				$row_t = mysqli_fetch_row($result_t);
				list($get_current_main_category_translation_value) = $row_t;
				echo"
				<h1>$get_current_meal_plan_title</h1>
	
				<!-- You are here -->
				<p><b>$l_you_are_here:</b><br />
				<a href=\"my_meal_plans.php?l=$l\">$l_my_meal_plans</a>
				&gt;
				<a href=\"meal_plan_edit.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;l=$l\">$l_meal_plan</a>
				&gt;
				<a href=\"meal_plan_edit_new_entry_food.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;l=$l\">$l_add_food</a>
				&gt;
				<a href=\"meal_plan_edit_new_entry_food.php?action=open_main_food_category&amp;meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;main_category_id=$main_category_id&amp;l=$l\">$get_current_main_category_translation_value</a>
				</p>
				<!-- //You are here -->
				
				
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
			

				<!-- Search -->
				<!-- Search engines Autocomplete -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_entry_food_query\"]').focus();
				});
				\$(document).ready(function () {
					\$('#inp_entry_food_query').keyup(function () {
        					// getting the value that user typed
        					var searchString    = $(\"#inp_entry_food_query\").val();
        					// forming the queryString
       						var data            = 'l=$l&meal_plan_id=$meal_plan_id&entry_day_number=$entry_day_number&entry_meal_number=$entry_meal_number&q='+ searchString;
         
        					// if searchString is not empty
        					if(searchString) {
        						// ajax call
        						\$.ajax({
        							type: \"POST\",
        							url: \"meal_plan_edit_new_entry_food_jquery.php\",
        							data: data,
								beforeSend: function(html) { // this happens before actual call
									\$(\"#nettport_search_results\").html(''); 
								},
        							success: function(html){
        								\$(\"#nettport_search_results\").append(html);
        							}
        						});
       						}
        					return false;
        				});
        			});
				</script>
				<!-- //Search engines Autocomplete -->

				<form method=\"get\" action=\"meal_plan_edit_new_entry_food.php\" enctype=\"multipart/form-data\" id=\"inp_entry_food_query_form\">
					<p><b>$l_food</b><br />
					<input type=\"text\" id=\"inp_entry_food_query\" name=\"inp_entry_food_query\" value=\"";if(isset($_GET['inp_entry_food_query'])){ echo"$inp_entry_food_query"; } echo"\" size=\"20\" />
					<input type=\"hidden\" name=\"meal_plan_id\" value=\"$meal_plan_id\" />
					<input type=\"hidden\" name=\"entry_day_number\" value=\"$entry_day_number\" />
					<input type=\"hidden\" name=\"entry_meal_number\" value=\"$entry_meal_number\" />
					<input type=\"hidden\" name=\"l\" value=\"$l\" />
					<input type=\"hidden\" name=\"action\" value=\"search_for_food\" />
					<input type=\"submit\" value=\"$l_search\" class=\"btn btn_default\" />
					<a href=\"$root/food/new_food.php?l=$l\" class=\"btn btn_default\">$l_new_food</a>
					</p>
				</form>
				<!-- //Search -->


				<!-- Meal plans Food sub categories -->
					<div class=\"vertical\">
						<ul>";

						// Get sub categories
				
						$queryb = "SELECT category_id, category_name, category_parent_id FROM $t_food_categories WHERE category_user_id='0' AND category_parent_id='$get_current_main_category_id' ORDER BY category_name ASC";
						$resultb = mysqli_query($link, $queryb);
						while($rowb = mysqli_fetch_row($resultb)) {
							list($get_sub_category_id, $get_sub_category_name, $get_sub_category_parent_id) = $rowb;

							// Translation
							$query_t = "SELECT category_translation_value FROM $t_food_categories_translations WHERE category_id=$get_sub_category_id AND category_translation_language=$l_mysql";
							$result_t = mysqli_query($link, $query_t);
							$row_t = mysqli_fetch_row($result_t);
							list($get_sub_category_translation_value) = $row_t;

							$get_sub_category_translation_value_len = strlen($get_sub_category_translation_value);
							echo"		";
							echo"<li><a href=\"meal_plan_edit_new_entry_food.php?action=open_sub_food_category&amp;meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;main_category_id=$main_category_id&amp;sub_category_id=$get_sub_category_id&amp;l=$l\">$get_sub_category_translation_value</a></li>\n";


							// In category for mysql
							if(isset($in_category)){
								$in_category = $in_category . ",$get_sub_category_id";
							}
							else{
								$in_category = "$get_sub_category_id";
							}

						}
						echo"
						</ul>
					</div>
				<!-- //Meal plans :: Food categories -->

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
					$r = "meal_plan_edit_new_entry_food.php?action=open_main_food_categoryamp;meal_plan_id=$meal_plan_id" . "amp;entry_day_number=$entry_day_number" . "amp;entry_meal_number=$entry_meal_number" . "amp;main_category_id=$main_category_id" . "amp;l=$l";
					echo"
					<p><a id=\"adapter_view\"></a>
					<b>$l_show_per:</b>
					<input type=\"checkbox\" name=\"inp_show_hundred_metric\" class=\"onclick_go_to_url\"  data-target=\"user_adapted_view.php?set=hundred_metric&amp;value=0&amp;process=1&amp;referer=$r&amp;l=$l\""; if($get_current_view_hundred_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_hundred
					<input type=\"checkbox\" name=\"inp_show_pcs_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=pcs_metric&amp;value=0&amp;process=1&amp;referer=$r&amp;l=$l\""; if($get_current_view_pcs_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_pcs_g
					<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=eight_us&amp;value=0&amp;process=1&amp;referer=$r&amp;l=$l\""; if($get_current_view_eight_us == "1"){ echo" checked=\"checked\""; } echo" /> $l_eight
					<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=pcs_us&amp;value=0&amp;process=1&amp;referer=$r&amp;l=$l\""; if($get_current_view_pcs_us == "1"){ echo" checked=\"checked\""; }  echo" /> $l_pcs_oz
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

				<!-- Food list -->
					<div id=\"nettport_search_results\">
					";
	

					// Get all food
					$x = 0;
					$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_comments, food_likes, food_dislikes, food_likes_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_language=$l_mysql AND food_main_category_id=$get_current_main_category_id ORDER BY food_last_used DESC LIMIT 0,60";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_comments, $get_food_likes, $get_food_dislikes, $get_food_likes_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row;
			
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

						// Thumb
						if($get_food_image_a != "" && file_exists("../$get_food_image_path/$get_food_image_a")){
							$thumb = "../$get_food_image_path/$get_food_thumb_a_small";
						}
						else{
							$thumb = "_gfx/no_thumb.png";
						}


						echo"
						<p style=\"padding-bottom:5px;\">
						<a href=\"$root/food/view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\"><img src=\"$thumb\" alt=\"$get_food_image_a\" style=\"margin-bottom: 5px;\" /></a><br />
						<a href=\"$root/food/view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\" style=\"font-weight: bold;color: #444444;\">$title</a><br />
						</p>";

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
						} // get_current_view_hundred_metric
						echo"
						<!-- Add food to  -->
							<form method=\"post\" action=\"meal_plan_edit_new_entry_food.php?action=do_add_entry_to_meal_plan&amp;meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
							<p>
							<input type=\"hidden\" name=\"inp_entry_food_id\" value=\"$get_food_id\" />
							";
							if($get_current_view_hundred_metric == "1" OR $get_current_view_pcs_metric == "1"){
								if($get_food_serving_size_pcs_measurement == "g"){
									echo"
									<input type=\"text\" name=\"inp_entry_food_serving_size\" size=\"2\" value=\"$get_food_serving_size_metric\" />
									<input type=\"submit\" name=\"inp_submit_metric\" value=\"$get_food_serving_size_measurement_metric\" class=\"btn btn_default\" />
									";
								}
								else{
									echo"
									<input type=\"text\" name=\"inp_entry_food_serving_size\" size=\"2\" value=\"$get_food_serving_size_pcs\" />
									<input type=\"submit\" name=\"inp_submit_metric\" value=\"$get_food_serving_size_measurement_metric\" class=\"btn btn_default\" />
									<input type=\"submit\" name=\"inp_submit_pcs\" value=\"$get_food_serving_size_pcs_measurement\" class=\"btn btn_default\" />
									";
								}
							} // metric
							if($get_current_view_eight_us == "1" OR $get_current_view_pcs_us == "1"){
								echo"
								<input type=\"text\" name=\"inp_entry_food_serving_size\" size=\"2\" value=\"$get_food_serving_size_pcs\" />
								<input type=\"submit\" name=\"inp_submit_us\" value=\"$get_food_serving_size_measurement_metric\" class=\"btn btn_default\" />
								<input type=\"submit\" name=\"inp_submit_pcs\" value=\"$get_food_serving_size_pcs_measurement\" class=\"btn btn_default\" />
								";
							} // us
							echo"
							</p>
							</form>
						<!-- //Add food -->
						</div>
						";
						// Increment
						$x++;
		
						// Reset
						if($x == 4){
							$x = 0;
						}
					} // while

					echo"

				</div> <!--  //nettport_search_results ->
				<!-- //Food list -->
				";
			} // main food category found
		} // open_main_food_category
		elseif($action == "open_sub_food_category"){
			// Get main category
			$main_category_id_mysql = quote_smart($link, $main_category_id);
			$query = "SELECT category_id, category_user_id, category_name, category_parent_id FROM $t_food_categories WHERE category_id=$main_category_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_main_category_id, $get_current_main_category_user_id, $get_current_main_category_name, $get_current_main_category_parent_id) = $row;
			if($get_current_main_category_id == ""){
				echo"Server error 404";
			}
			else{
				// Main Translation
				$query_t = "SELECT category_translation_value FROM $t_food_categories_translations WHERE category_id=$get_current_main_category_id AND category_translation_language=$l_mysql";
				$result_t = mysqli_query($link, $query_t);
				$row_t = mysqli_fetch_row($result_t);
				list($get_current_main_category_translation_value) = $row_t;

				// Find sub
				$sub_category_id_mysql = quote_smart($link, $sub_category_id);
				$query = "SELECT category_id, category_user_id, category_name, category_parent_id FROM $t_food_categories WHERE category_id=$sub_category_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_sub_category_id, $get_current_sub_category_user_id, $get_current_sub_category_name, $get_current_sub_category_parent_id) = $row;
				if($get_current_sub_category_id== ""){
					echo"Server error 404";
				}
				else{
					// Sub category Translation
					$query_t = "SELECT category_translation_value FROM $t_food_categories_translations WHERE category_id=$get_current_sub_category_id AND category_translation_language=$l_mysql";
					$result_t = mysqli_query($link, $query_t);
					$row_t = mysqli_fetch_row($result_t);
					list($get_current_sub_category_translation_value) = $row_t;
	

					echo"
					<h1>$get_current_meal_plan_title</h1>
	
					<!-- You are here -->
						<p><b>$l_you_are_here:</b><br />
						<a href=\"my_meal_plans.php?l=$l\">$l_my_meal_plans</a>
						&gt;
						<a href=\"meal_plan_edit.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;l=$l\">$l_meal_plan</a>
						&gt;
						<a href=\"meal_plan_edit_new_entry_food.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;l=$l\">$l_add_food</a>
						&gt;
						<a href=\"meal_plan_edit_new_entry_food.php?action=open_main_food_category&amp;meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;main_category_id=$main_category_id&amp;l=$l\">$get_current_main_category_translation_value</a>
						&gt;
						<a href=\"meal_plan_edit_new_entry_food.php?action=open_sub_food_category&amp;meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;l=$l\">$get_current_sub_category_translation_value</a>
						</p>
					<!-- //You are here -->
				
				
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
			

					<!-- Search -->
						<!-- Search engines Autocomplete -->
						<script>
						\$(document).ready(function(){
							\$('[name=\"inp_entry_food_query\"]').focus();
						});
						\$(document).ready(function () {
							\$('#inp_entry_food_query').keyup(function () {
        							// getting the value that user typed
        							var searchString    = $(\"#inp_entry_food_query\").val();
        							// forming the queryString
       								var data            = 'l=$l&meal_plan_id=$meal_plan_id&entry_day_number=$entry_day_number&entry_meal_number=$entry_meal_number&q='+ searchString;
         
        							// if searchString is not empty
        							if(searchString) {
        								// ajax call
        								\$.ajax({
        									type: \"POST\",
        									url: \"meal_plan_edit_new_entry_food_jquery.php\",
        									data: data,
										beforeSend: function(html) { // this happens before actual call
											\$(\"#nettport_search_results\").html(''); 
										},
        									success: function(html){
        										\$(\"#nettport_search_results\").append(html);
        									}
        								});
       								}
        							return false;
        						});
        					});
						</script>
						<!-- //Search engines Autocomplete -->

						<form method=\"get\" action=\"meal_plan_edit_new_entry_food.php\" enctype=\"multipart/form-data\" id=\"inp_entry_food_query_form\">
							<p><b>$l_food</b><br />
							<input type=\"text\" id=\"inp_entry_food_query\" name=\"inp_entry_food_query\" value=\"";if(isset($_GET['inp_entry_food_query'])){ echo"$inp_entry_food_query"; } echo"\" size=\"20\" />
							<input type=\"hidden\" name=\"meal_plan_id\" value=\"$meal_plan_id\" />
							<input type=\"hidden\" name=\"entry_day_number\" value=\"$entry_day_number\" />
							<input type=\"hidden\" name=\"entry_meal_number\" value=\"$entry_meal_number\" />
							<input type=\"hidden\" name=\"l\" value=\"$l\" />
							<input type=\"hidden\" name=\"action\" value=\"search_for_food\" />
							<input type=\"submit\" value=\"$l_search\" class=\"btn btn_default\" />
							<a href=\"$root/food/new_food.php?l=$l\" class=\"btn btn_default\">$l_new_food</a>
							</p>
						</form>
					<!-- //Search -->


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
						$r = "new_meal_plan_step_2_entries_new_entry_food.php?meal_plan_id=$meal_plan_id" . "amp;entry_day_number=$entry_day_number" . "amp;entry_meal_number=$entry_meal_number";
						echo"
						<p><a id=\"adapter_view\"></a>
						<b>$l_show_per:</b>
						<input type=\"checkbox\" name=\"inp_show_hundred_metric\" class=\"onclick_go_to_url\"  data-target=\"user_adapted_view.php?set=hundred_metric&amp;value=0&amp;process=1&amp;referer=$r&amp;l=$l\""; if($get_current_view_hundred_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_hundred
						<input type=\"checkbox\" name=\"inp_show_pcs_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=pcs_metric&amp;value=0&amp;process=1&amp;referer=$r&amp;l=$l\""; if($get_current_view_pcs_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_pcs_g
						<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=eight_us&amp;value=0&amp;process=1&amp;referer=$r&amp;l=$l\""; if($get_current_view_eight_us == "1"){ echo" checked=\"checked\""; } echo" /> $l_eight
						<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=pcs_us&amp;value=0&amp;process=1&amp;referer=$r&amp;l=$l\""; if($get_current_view_pcs_us == "1"){ echo" checked=\"checked\""; }  echo" /> $l_pcs_oz
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

					<!-- Food list -->
						<div id=\"nettport_search_results\">
						";
	

						// Get all food
						$x = 0;
						$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_comments, food_likes, food_dislikes, food_likes_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_language=$l_mysql AND food_sub_category_id=$get_current_sub_category_id ORDER BY food_last_used DESC LIMIT 0,60";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_comments, $get_food_likes, $get_food_dislikes, $get_food_likes_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row;
			
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

							// Thumb
							if($get_food_image_a != "" && file_exists("../$get_food_image_path/$get_food_image_a")){
								$thumb = "../$get_food_image_path/$get_food_thumb_a_small";
							}
							else{
								$thumb = "_gfx/no_thumb.png";
							}
	

							echo"
							<p style=\"padding-bottom:5px;\">
							<a href=\"$root/food/view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\"><img src=\"$thumb\" alt=\"$get_food_image_a\" style=\"margin-bottom: 5px;\" /></a><br />
							<a href=\"$root/food/view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\" style=\"font-weight: bold;color: #444444;\">$title</a><br />
							</p>";

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
							} // get_current_view_hundred_metric
							echo"
							<!-- Add food to  -->
							<form method=\"post\" action=\"meal_plan_edit_new_entry_food.php?action=do_add_entry_to_meal_plan&amp;meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
							<p>
							<input type=\"hidden\" name=\"inp_entry_food_id\" value=\"$get_food_id\" />
							";
							if($get_current_view_hundred_metric == "1" OR $get_current_view_pcs_metric == "1"){
								if($get_food_serving_size_pcs_measurement == "g"){
									echo"
									<input type=\"text\" name=\"inp_entry_food_serving_size\" size=\"2\" value=\"$get_food_serving_size_metric\" />
									<input type=\"submit\" name=\"inp_submit_metric\" value=\"$get_food_serving_size_measurement_metric\" class=\"btn btn_default\" />
									";
								}
								else{
									echo"
									<input type=\"text\" name=\"inp_entry_food_serving_size\" size=\"2\" value=\"$get_food_serving_size_pcs\" />
									<input type=\"submit\" name=\"inp_submit_metric\" value=\"$get_food_serving_size_measurement_metric\" class=\"btn btn_default\" />
									<input type=\"submit\" name=\"inp_submit_pcs\" value=\"$get_food_serving_size_pcs_measurement\" class=\"btn btn_default\" />
									";
								}
							} // metric
							if($get_current_view_eight_us == "1" OR $get_current_view_pcs_us == "1"){
								echo"
								<input type=\"text\" name=\"inp_entry_food_serving_size\" size=\"2\" value=\"$get_food_serving_size_pcs\" />
								<input type=\"submit\" name=\"inp_submit_us\" value=\"$get_food_serving_size_measurement_metric\" class=\"btn btn_default\" />
								<input type=\"submit\" name=\"inp_submit_pcs\" value=\"$get_food_serving_size_pcs_measurement\" class=\"btn btn_default\" />
								";
							} // us
							echo"
							</p>
							</form>
							<!-- //Add food -->
							</div>
							";
							// Increment
							$x++;
		
							// Reset
							if($x == 4){
								$x = 0;
							}
						} // while
						echo"

						<!-- //Food list -->
					
						";
						echo"
						</div> <!--  //nettport_search_results ->
					<!-- //Food list -->
					";
				} // sub food category found
			} // main food category found
		} // open_main_food_category
		elseif($action == "search_for_food"){
			echo"
			<h1>$get_current_meal_plan_title</h1>
	
			<!-- You are here -->
				<p><b>$l_you_are_here:</b><br />
				<a href=\"my_meal_plans.php?l=$l\">$l_my_meal_plans</a>
				&gt;
				<a href=\"meal_plan_edit.php?meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;l=$l\">$l_meal_plan</a>
				&gt;
				<a href=\"meal_plan_edit_new_entry_food.php?action=search_for_food&amp;meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;l=$l\">$l_search</a>
				</p>
			<!-- //You are here -->
				
				
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
			
			<!-- Search -->
				<!-- Search engines Autocomplete -->
				<script>
				\$(document).ready(function(){
							\$('[name=\"inp_entry_food_query\"]').focus();
						});
						\$(document).ready(function () {
							\$('#inp_entry_food_query').keyup(function () {
        							// getting the value that user typed
        							var searchString    = $(\"#inp_entry_food_query\").val();
        							// forming the queryString
       								var data            = 'l=$l&meal_plan_id=$meal_plan_id&entry_day_number=$entry_day_number&entry_meal_number=$entry_meal_number&q='+ searchString;
         
        							// if searchString is not empty
        							if(searchString) {
        								// ajax call
        								\$.ajax({
        									type: \"POST\",
        									url: \"meal_plan_edit_new_entry_food_jquery.php\",
        									data: data,
										beforeSend: function(html) { // this happens before actual call
											\$(\"#nettport_search_results\").html(''); 
										},
        									success: function(html){
        										\$(\"#nettport_search_results\").append(html);
        									}
        								});
       								}
        							return false;
        						});
        					});
						</script>
						<!-- //Search engines Autocomplete -->

						<form method=\"get\" action=\"meal_plan_edit_new_entry_food.php\" enctype=\"multipart/form-data\" id=\"inp_entry_food_query_form\">
							<p><b>$l_food</b><br />
							<input type=\"text\" id=\"inp_entry_food_query\" name=\"inp_entry_food_query\" value=\"";if(isset($_GET['inp_entry_food_query'])){ echo"$inp_entry_food_query"; } echo"\" size=\"20\" />
							<input type=\"hidden\" name=\"meal_plan_id\" value=\"$meal_plan_id\" />
							<input type=\"hidden\" name=\"entry_day_number\" value=\"$entry_day_number\" />
							<input type=\"hidden\" name=\"entry_meal_number\" value=\"$entry_meal_number\" />
							<input type=\"hidden\" name=\"l\" value=\"$l\" />
							<input type=\"hidden\" name=\"action\" value=\"search_for_food\" />
							<input type=\"submit\" value=\"$l_search\" class=\"btn btn_default\" />
							<a href=\"$root/food/new_food.php?l=$l\" class=\"btn btn_default\">$l_new_food</a>
							</p>
						</form>
			<!-- //Search -->

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
				$r = "meal_plan_edit_new_entry_food.php?inp_entry_food_query=$inp_entry_food_query" . "amp;meal_plan_id=$meal_plan_id" . "amp;entry_day_number=$entry_day_number" . "amp;entry_meal_number=$entry_meal_number" . "amp;l=$l" . "amp;action=$action";
				echo"
				<p><a id=\"adapter_view\"></a>
				<b>$l_show_per:</b>
				<input type=\"checkbox\" name=\"inp_show_hundred_metric\" class=\"onclick_go_to_url\"  data-target=\"user_adapted_view.php?set=hundred_metric&amp;value=0&amp;process=1&amp;referer=$r&amp;l=$l\""; if($get_current_view_hundred_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_hundred
				<input type=\"checkbox\" name=\"inp_show_pcs_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=pcs_metric&amp;value=0&amp;process=1&amp;referer=$r&amp;l=$l\""; if($get_current_view_pcs_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_pcs_g
				<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=eight_us&amp;value=0&amp;process=1&amp;referer=$r&amp;l=$l\""; if($get_current_view_eight_us == "1"){ echo" checked=\"checked\""; } echo" /> $l_eight
				<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=pcs_us&amp;value=0&amp;process=1&amp;referer=$r&amp;l=$l\""; if($get_current_view_pcs_us == "1"){ echo" checked=\"checked\""; }  echo" /> $l_pcs_oz
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

			<!-- Food list -->
				<div id=\"nettport_search_results\">
				";

				// Get all food
				$x = 0;
				$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_comments, food_likes, food_dislikes, food_likes_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_language=$l_mysql";

				if(isset($_GET['inp_entry_food_query'])){
					$inp_entry_food_query = $_GET['inp_entry_food_query'];
					$inp_entry_food_query = strip_tags(stripslashes($inp_entry_food_query));
					$inp_entry_food_query = output_html($inp_entry_food_query);

					$inp_entry_food_query = $inp_entry_food_query . "%";
					$inp_entry_food_query_mysql = quote_smart($link, $inp_entry_food_query);

					$query = $query . " AND (food_name LIKE $inp_entry_food_query_mysql OR food_manufacturer_name_and_food_name LIKE $inp_entry_food_query_mysql)";
				}
				if($sub_category_id != ""){
					$sub_category_id_mysql = quote_smart($link, $sub_category_id);
					$query = $query . " AND food_category_id=$sub_category_id_mysql";
				}
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_comments, $get_food_likes, $get_food_dislikes, $get_food_likes_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row;
			
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

						// Thumb
						if($get_food_image_a != "" && file_exists("../$get_food_image_path/$get_food_image_a")){
							$thumb = "../$get_food_image_path/$get_food_thumb_a_small";
						}
						else{
							$thumb = "_gfx/no_thumb.png";
						}


						echo"
						<p style=\"padding-bottom:5px;\">
						<a href=\"$root/food/view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\"><img src=\"$thumb\" alt=\"$get_food_image_a\" style=\"margin-bottom: 5px;\" /></a><br />
						<a href=\"$root/food/view_food.php?main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;food_id=$get_food_id&amp;l=$l\" style=\"font-weight: bold;color: #444444;\">$title</a><br />
						</p>";

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
						} // get_current_view_hundred_metric
						echo"
						<!-- Add food to  -->
							<form method=\"post\" action=\"meal_plan_edit_new_entry_food.php?action=do_add_entry_to_meal_plan&amp;meal_plan_id=$meal_plan_id&amp;entry_day_number=$entry_day_number&amp;entry_meal_number=$entry_meal_number&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
							<p>
							<input type=\"hidden\" name=\"inp_entry_food_id\" value=\"$get_food_id\" />
							";
							if($get_current_view_hundred_metric == "1" OR $get_current_view_pcs_metric == "1"){
								if($get_food_serving_size_pcs_measurement == "g"){
									echo"
									<input type=\"text\" name=\"inp_entry_food_serving_size\" size=\"2\" value=\"$get_food_serving_size_metric\" />
									<input type=\"submit\" name=\"inp_submit_metric\" value=\"$get_food_serving_size_measurement_metric\" class=\"btn btn_default\" />
									";
								}
								else{
									echo"
									<input type=\"text\" name=\"inp_entry_food_serving_size\" size=\"2\" value=\"$get_food_serving_size_pcs\" />
									<input type=\"submit\" name=\"inp_submit_metric\" value=\"$get_food_serving_size_measurement_metric\" class=\"btn btn_default\" />
									<input type=\"submit\" name=\"inp_submit_pcs\" value=\"$get_food_serving_size_pcs_measurement\" class=\"btn btn_default\" />
									";
								}
							} // metric
							if($get_current_view_eight_us == "1" OR $get_current_view_pcs_us == "1"){
								echo"
								<input type=\"text\" name=\"inp_entry_food_serving_size\" size=\"2\" value=\"$get_food_serving_size_pcs\" />
								<input type=\"submit\" name=\"inp_submit_us\" value=\"$get_food_serving_size_measurement_metric\" class=\"btn btn_default\" />
								<input type=\"submit\" name=\"inp_submit_pcs\" value=\"$get_food_serving_size_pcs_measurement\" class=\"btn btn_default\" />
								";
							} // us
							echo"
							</p>
							</form>
						<!-- //Add food -->
						</div>
						";
						// Increment
						$x++;
		
						// Reset
						if($x == 4){
							$x = 0;
						}
					} // while

				echo"
				</div> <!--  //nettport_search_results -->
			<!-- //Food list -->
			";
		} // search
		elseif($action == "do_add_entry_to_meal_plan"){

			if($process == 1){
				$inp_entry_day_number = output_html($entry_day_number);
				$inp_entry_day_number_mysql = quote_smart($link, $inp_entry_day_number);

				$inp_entry_meal_number = output_html($entry_meal_number);
				$inp_entry_meal_number_mysql = quote_smart($link, $inp_entry_meal_number);

				$inp_entry_food_id = $_POST['inp_entry_food_id'];
				$inp_entry_food_id = output_html($inp_entry_food_id);
				$inp_entry_food_id_mysql = quote_smart($link, $inp_entry_food_id);

				$inp_entry_food_serving_size = $_POST['inp_entry_food_serving_size'];
				$inp_entry_food_serving_size = output_html($inp_entry_food_serving_size);
				$inp_entry_food_serving_size = str_replace(",", ".", $inp_entry_food_serving_size);
				$inp_entry_food_serving_size_mysql = quote_smart($link, $inp_entry_food_serving_size);
				if($inp_entry_food_serving_size == ""){
					$url = "meal_plan_edit_new_entry_food.php?meal_plan_id=$meal_plan_id&entry_day_number=$entry_day_number&entry_meal_number=$entry_meal_number&l=$l&action=new_entry_food";
					$url = $url . "&ft=error&fm=missing_amount";
					header("Location: $url");
					exit;
				}

				// get food
				$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_comments, food_likes, food_dislikes, food_likes_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_id=$inp_entry_food_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_comments, $get_food_likes, $get_food_dislikes, $get_food_likes_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row;

				if($get_food_id == ""){
					$url = "meal_plan_edit_new_entry_food.php?meal_plan_id=$meal_plan_id&entry_day_number=$entry_day_number&entry_meal_number=$entry_meal_number&l=$l&action=new_entry_food";
					$url = $url . "&ft=error&fm=food_not_found";
					header("Location: $url");
					exit;
				}
				
				$inp_entry_food_name = output_html($get_food_name);
				$inp_entry_food_name = str_replace("&amp;amp;", "&amp;", $inp_entry_food_name);
				$inp_entry_food_name_mysql = quote_smart($link, $inp_entry_food_name);

				$inp_entry_food_manufacturer_name = output_html($get_food_manufacturer_name);
				$inp_entry_food_manufacturer_name_mysql = quote_smart($link, $inp_entry_food_manufacturer_name);


				// Gram or pcs?
				if (isset($_POST['inp_submit_metric'])) {
					// Gram
					$inp_entry_food_serving_size_measurement = output_html($get_food_serving_size_measurement_metric);
					$inp_entry_food_serving_size_measurement_mysql = quote_smart($link, $inp_entry_food_serving_size_measurement);
	
					$inp_entry_food_energy_per_entry = round(($inp_entry_food_serving_size*$get_food_energy_metric)/100, 1);
					$inp_entry_food_energy_per_entry_mysql = quote_smart($link, $inp_entry_food_energy_per_entry);

					$inp_entry_food_fat_per_entry = round(($inp_entry_food_serving_size*$get_food_fat_metric)/100, 1);
					$inp_entry_food_fat_per_entry_mysql = quote_smart($link, $inp_entry_food_fat_per_entry);

					$inp_entry_food_saturated_fat_per_entry = round(($inp_entry_food_serving_size*$get_food_saturated_fat_metric)/100, 1);
					$inp_entry_food_saturated_fat_per_entry_mysql = quote_smart($link, $inp_entry_food_saturated_fat_per_entry);

					$inp_entry_food_monounsaturated_fat_per_entry = round(($inp_entry_food_serving_size*$get_food_monounsaturated_fat_metric)/100, 1);
					$inp_entry_food_monounsaturated_fat_per_entry_mysql = quote_smart($link, $inp_entry_food_monounsaturated_fat_per_entry);

					$inp_entry_food_polyunsaturated_fat_per_entry = round(($inp_entry_food_serving_size*$get_food_polyunsaturated_fat_metric)/100, 1);
					$inp_entry_food_polyunsaturated_fat_per_entry_mysql = quote_smart($link, $inp_entry_food_polyunsaturated_fat_per_entry);

					$inp_entry_food_cholesterol_per_entry = round(($inp_entry_food_serving_size*$get_food_cholesterol_metric)/100, 0);
					$inp_entry_food_cholesterol_per_entry_mysql = quote_smart($link, $inp_entry_food_cholesterol_per_entry);

					$inp_entry_food_carb_per_entry = round(($inp_entry_food_serving_size*$get_food_carbohydrates_metric)/100, 1);
					$inp_entry_food_carb_per_entry_mysql = quote_smart($link, $inp_entry_food_carb_per_entry);

					$inp_entry_food_carbohydrates_of_which_sugars_per_entry = round(($inp_entry_food_serving_size*$get_food_carbohydrates_of_which_sugars_metric)/100, 1);
					$inp_entry_food_carbohydrates_of_which_sugars_per_entry_mysql = quote_smart($link, $inp_entry_food_carbohydrates_of_which_sugars_per_entry);

					$inp_entry_food_dietary_fiber_per_entry = round(($inp_entry_food_serving_size*$get_food_dietary_fiber_metric)/100, 1);
					$inp_entry_food_dietary_fiber_per_entry_mysql = quote_smart($link, $inp_entry_food_dietary_fiber_per_entry);

					$inp_entry_food_protein_per_entry = round(($inp_entry_food_serving_size*$get_food_proteins_metric)/100, 1);
					$inp_entry_food_protein_per_entry_mysql = quote_smart($link, $inp_entry_food_protein_per_entry);

					$inp_entry_food_salt_per_entry = round(($inp_entry_food_serving_size*$get_food_salt_metric)/100, 1);
					$inp_entry_food_salt_per_entry_mysql = quote_smart($link, $inp_entry_food_salt_per_entry);

					$inp_entry_food_sodium_per_entry = round(($inp_entry_food_serving_size*$get_food_sodium_metric)/100, 1);
					$inp_entry_food_sodium_per_entry_mysql = quote_smart($link, $inp_entry_food_sodium_per_entry);

			
				} // metric gram/ml
				else{
					if (isset($_POST['inp_submit_us'])) {
						echo"No yet implimented";
						die;
					}
					else{
						// PCS
						$inp_entry_food_serving_size_measurement = output_html($get_food_serving_size_pcs_measurement);
						$inp_entry_food_serving_size_measurement_mysql = quote_smart($link, $inp_entry_food_serving_size_measurement);

						$inp_entry_food_energy_per_entry = round(($inp_entry_food_serving_size*$get_food_energy_calculated_metric)/$get_food_serving_size_pcs, 1);
						$inp_entry_food_energy_per_entry_mysql = quote_smart($link, $inp_entry_food_energy_per_entry);

						$inp_entry_food_fat_per_entry = round(($inp_entry_food_serving_size*$get_food_fat_calculated_metric)/$get_food_serving_size_pcs, 1);
						$inp_entry_food_fat_per_entry_mysql = quote_smart($link, $inp_entry_food_fat_per_entry);

						$inp_entry_food_saturated_fat_per_entry = round(($inp_entry_food_serving_size*$get_food_saturated_fat_metric)/$get_food_serving_size_pcs, 1);
						$inp_entry_food_saturated_fat_per_entry_mysql = quote_smart($link, $inp_entry_food_saturated_fat_per_entry);

						$inp_entry_food_monounsaturated_fat_per_entry = round(($inp_entry_food_serving_size*$get_food_monounsaturated_fat_metric)/$get_food_serving_size_pcs, 1);
						$inp_entry_food_monounsaturated_fat_per_entry_mysql = quote_smart($link, $inp_entry_food_monounsaturated_fat_per_entry);

						$inp_entry_food_polyunsaturated_fat_per_entry = round(($inp_entry_food_serving_size*$get_food_polyunsaturated_fat_metric)/$get_food_serving_size_pcs, 1);
						$inp_entry_food_polyunsaturated_fat_per_entry_mysql = quote_smart($link, $inp_entry_food_polyunsaturated_fat_per_entry);

						$inp_entry_food_cholesterol_per_entry = round(($inp_entry_food_serving_size*$get_food_cholesterol_metric)/$get_food_serving_size_pcs, 1);
						$inp_entry_food_cholesterol_per_entry_mysql = quote_smart($link, $inp_entry_food_cholesterol_per_entry);


						$inp_entry_food_carb_per_entry = round(($inp_entry_food_serving_size*$get_food_carbohydrates_calculated_metric)/$get_food_serving_size_pcs, 1);
						$inp_entry_food_carb_per_entry_mysql = quote_smart($link, $inp_entry_food_carb_per_entry);

						$inp_entry_food_carbohydrates_of_which_sugars_per_entry = round(($inp_entry_food_serving_size*$get_food_carbohydrates_of_which_sugars_metric)/$get_food_serving_size_pcs, 1);
						$inp_entry_food_carbohydrates_of_which_sugars_per_entry_mysql = quote_smart($link, $inp_entry_food_carbohydrates_of_which_sugars_per_entry);

						$inp_entry_food_dietary_fiber_per_entry = round(($inp_entry_food_serving_size*$get_food_dietary_fiber_metric)/$get_food_serving_size_pcs, 1);
						$inp_entry_food_dietary_fiber_per_entry_mysql = quote_smart($link, $inp_entry_food_dietary_fiber_per_entry);

						$inp_entry_food_protein_per_entry = round(($inp_entry_food_serving_size*$get_food_proteins_calculated_metric)/$get_food_serving_size_pcs, 1);
						$inp_entry_food_protein_per_entry_mysql = quote_smart($link, $inp_entry_food_protein_per_entry);

						$inp_entry_food_salt_per_entry = round(($inp_entry_food_serving_size*$get_food_salt_metric)/$get_food_serving_size_pcs, 1);
						$inp_entry_food_salt_per_entry_mysql = quote_smart($link, $inp_entry_food_salt_per_entry);

						$inp_entry_food_sodium_per_entry = round(($inp_entry_food_serving_size*$get_food_sodium_metric)/$get_food_serving_size_pcs, 1);
						$inp_entry_food_sodium_per_entry_mysql = quote_smart($link, $inp_entry_food_sodium_per_entry);
					} // metric PCS
				} // US oz, US fl oz, US pcs, metric pcs

				// Get weight of previously food added
				$query = "SELECT entry_weight FROM $t_meal_plans_entries WHERE 
				entry_meal_plan_id='$get_current_meal_plan_id' AND entry_day_number=$inp_entry_day_number_mysql AND entry_meal_number=$inp_entry_meal_number_mysql ORDER BY entry_weight DESC";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_entry_weight) = $row;
				$inp_entry_weight = $get_entry_weight + 1;


				// Insert
				mysqli_query($link, "INSERT INTO $t_meal_plans_entries
				(entry_id, entry_meal_plan_id, entry_day_number, entry_meal_number, entry_weight, entry_food_id, entry_recipe_id,
				entry_name, entry_manufacturer_name, entry_main_category_id, entry_sub_category_id, entry_serving_size, entry_serving_size_measurement, 
				entry_energy_per_entry, entry_fat_per_entry, entry_carb_per_entry, entry_protein_per_entry) 
				VALUES 
				(NULL, '$get_current_meal_plan_id', $inp_entry_day_number_mysql, $inp_entry_meal_number_mysql, $inp_entry_weight, $inp_entry_food_id_mysql, '0',
				$inp_entry_food_name_mysql, $inp_entry_food_manufacturer_name_mysql, $get_food_main_category_id, $get_food_sub_category_id, $inp_entry_food_serving_size_mysql, $inp_entry_food_serving_size_measurement_mysql, 
				$inp_entry_food_energy_per_entry_mysql, $inp_entry_food_fat_per_entry_mysql, $inp_entry_food_carb_per_entry_mysql, $inp_entry_food_protein_per_entry_mysql)")
				or die(mysqli_error($link));


				$url = "meal_plan_edit.php?meal_plan_id=$meal_plan_id&entry_day_number=$entry_day_number&entry_meal_number=$entry_meal_number&l=$l";
				$url = $url . "&ft=success&fm=food_added#meal_number$entry_meal_number";
				header("Location: $url");
				exit;
			}


		} // action == save
		else{
			echo"Unknown action.. ";
		}
	} // meal_planfound
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