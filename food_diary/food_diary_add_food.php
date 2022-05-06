<?php
/**
*
* File: food_diary/food_diary_add_food.php
* Version 1.0.0.
* Date 12:42 21.01.2018
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
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
} 
else{
	$inp_entry_food_query = "";
}

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/food_diary/ts_index.php");
include("$root/_admin/_translations/site/$l/food_diary/ts_food_diary_add.php");


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_new_entry - $l_food_diary";
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
	


	if($action == ""){

		echo"
		<h1>$l_new_entry</h1>

	
		<!-- You are here -->
			<p><b>$l_you_are_here</b><br />
			<a href=\"index.php?l=$l\">$l_food_diary</a>
			&gt;
			<a href=\"food_diary_add.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_new_entry</a>
			&gt;
			<a href=\"food_diary_add_food.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_food</a>
			</p>
		<!-- //You are here -->
		
		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				$fm = str_replace("_", " ", $fm);
				$fm = ucfirst($fm);
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->

		<!-- Search -->	
			<!-- Search engines Autocomplete -->
				<script>";
				if(!(isset($_GET['focus']))){
					echo"
					\$(document).ready(function(){
						\$('[name=\"inp_entry_food_query\"]').focus();
					});
					";
				}
				echo"
				\$(document).ready(function () {
					\$('#inp_entry_food_query').keyup(function () {
						\$(\"#food_categories\").hide();

        					// getting the value that user typed
        					var searchString    = $(\"#inp_entry_food_query\").val();
        					// forming the queryString
       						var data            = 'l=$l&date=$date&hour_name=$hour_name&search_query='+ searchString;
         
        					// if searchString is not empty
        					if(searchString) {
        						// ajax call
          						\$.ajax({
                						type: \"GET\",
               							url: \"food_diary_add_food_query.php\",
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

			<!-- Food Search -->
				<form method=\"get\" action=\"food_diary_add_food.php\" enctype=\"multipart/form-data\" id=\"inp_entry_food_query_form\">
					<p style=\"padding-top: 0;\"><b>$l_food_search</b><br />
					<input type=\"text\" id=\"inp_entry_food_query\" name=\"inp_entry_food_query\" value=\"";if(isset($_GET['inp_entry_food_query'])){ echo"$inp_entry_food_query"; } echo"\" size=\"12\" />
					<input type=\"hidden\" name=\"action\" value=\"search\" />
					<input type=\"hidden\" name=\"date\" value=\"$date\" />
					<input type=\"hidden\" name=\"hour_name\" value=\"$hour_name\" />
					<input type=\"submit\" value=\"$l_search\" class=\"btn btn_default\" />
					<a href=\"$root/food/new_food.php?l=$l\" class=\"btn btn_default\">$l_new_food</a>
					</p>
				</form>
			<!-- //Food Search -->
		<!-- //Search -->


		<!-- Menu -->
			<div class=\"tabs\">
				<ul>
					<li><a href=\"food_diary_add.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_recent</a></li>
					<li><a href=\"food_diary_add_food.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\" class=\"selected\">$l_food</a></li>
					<li><a href=\"food_diary_add_recipe.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_recipes</a></li>
				</ul>
			</div>
			<div class=\"clear\" style=\"height: 20px;\"></div>
		<!-- //Menu -->
	
		<!-- Food main categories -->
			<div class=\"vertical\" id=\"food_categories\">
				<ul>\n";
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

					echo"					";
					echo"<li><a href=\"food_diary_add_food.php?action=open_main_category&amp;date=$date&amp;hour_name=$hour_name&amp;inp_entry_food_query=$inp_entry_food_query&amp;main_category_id=$get_category_id&amp;l=$l\""; if($main_category_id == "$get_category_id"){ echo" style=\"font-weight: bold;\"";}echo">$get_category_translation_value</a></li>\n";
				}
			echo"
				</ul>
			</div>
		<!-- //Food main categories -->
		
		<!-- Adapter view -->";
			
			$query_t = "SELECT view_id, view_user_id, view_system, view_hundred_metric, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_food_diary_user_adapted_view WHERE view_user_id=$get_my_user_id";
			$result_t = mysqli_query($link, $query_t);
			$row_t = mysqli_fetch_row($result_t);
			list($get_current_view_id, $get_current_view_user_id, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
			echo"
			<p><a id=\"adapter_view\"></a>
			<b>$l_show_per:</b>
			<input type=\"checkbox\" name=\"inp_show_hundred_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=hundred_metric&amp;process=1&amp;referer=food_diary_add_food&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l\""; if($get_current_view_hundred_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_hundred
			<input type=\"checkbox\" name=\"inp_show_pcs_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=pcs_metric&amp;process=1&amp;referer=food_diary_add_food&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l\""; if($get_current_view_pcs_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_pcs_g
			<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=eight_us&amp;process=1&amp;referer=food_diary_add_food&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l\""; if($get_current_view_eight_us == "1"){ echo" checked=\"checked\""; } echo" /> $l_eight
			<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=pcs_us&amp;process=1&amp;referer=food_diary_add_food&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l\""; if($get_current_view_pcs_us == "1"){ echo" checked=\"checked\""; } echo" /> $l_pcs_oz
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
		<!-- //Adapter view -->

		<!-- Search result box -->
			<div id=\"nettport_search_results\">
		
				<!-- List all food in all categories -->

				";
	
					// Set layout
					$x = 0;

					// Get all food
					$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_text, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_numbers_entered_method, food_nutrition_facts_view_method, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_trans_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_added_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_trans_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_added_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_score_place_in_sub_category, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_trans_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_added_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_trans_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_added_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_energy_net_content, food_fat_net_content, food_saturated_fat_net_content, food_trans_fat_net_content, food_monounsaturated_fat_net_content, food_polyunsaturated_fat_net_content, food_cholesterol_net_content, food_carbohydrates_net_content, food_carbohydrates_of_which_sugars_net_content, food_added_sugars_net_content, food_dietary_fiber_net_content, food_proteins_net_content, food_salt_net_content, food_sodium_net_content, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_no_of_comments, food_stars, food_stars_sum, food_comments_multiplied_stars, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_language=$l_mysql ORDER BY food_last_used DESC LIMIT 0,60";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_text, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_numbers_entered_method, $get_food_nutrition_facts_view_method, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_trans_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_added_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_trans_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_added_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_score_place_in_sub_category, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_trans_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_added_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_trans_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_added_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_energy_net_content, $get_food_fat_net_content, $get_food_saturated_fat_net_content, $get_food_trans_fat_net_content, $get_food_monounsaturated_fat_net_content, $get_food_polyunsaturated_fat_net_content, $get_food_cholesterol_net_content, $get_food_carbohydrates_net_content, $get_food_carbohydrates_of_which_sugars_net_content, $get_food_added_sugars_net_content, $get_food_dietary_fiber_net_content, $get_food_proteins_net_content, $get_food_salt_net_content, $get_food_sodium_net_content, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_no_of_comments, $get_food_stars, $get_food_stars_sum, $get_food_comments_multiplied_stars, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row;
			
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
						} // get_current_view_hundred_metric
						echo"
						<!-- Add food -->
							<form method=\"post\" action=\"food_diary_add_food.php?action=add_food_to_diary&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
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
				<!-- //List all food in all categories -->

			</div> <!-- //nettport_search_results -->
		<!-- Search result box -->


		";
	} // action == ""
	elseif($action == "search"){

		echo"
		<h1>$l_new_entry</h1>

	
		<!-- You are here -->
			<p><b>$l_you_are_here</b><br />
			<a href=\"index.php?l=$l\">$l_food_diary</a>
			&gt;
			<a href=\"food_diary_add.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_new_entry</a>
			&gt;
			<a href=\"food_diary_add_food.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_food</a>
			&gt;
			<a href=\"food_diary_add_food.php?action=search&amp;date=$date&amp;hour_name=$hour_name&amp;inp_entry_food_query=$inp_entry_food_query&amp;l=$l\">$inp_entry_food_query</a>
			</p>
		<!-- //You are here -->


		<!-- Search -->	
			<!-- Search engines Autocomplete -->
				<script>";
				if(!(isset($_GET['focus']))){
					echo"
					\$(document).ready(function(){
						\$('[name=\"inp_entry_food_query\"]').focus();
					});
					";
				}
				echo"
				\$(document).ready(function () {
					\$('#inp_entry_food_query').keyup(function () {
        					// getting the value that user typed
        					var searchString    = $(\"#inp_entry_food_query\").val();
        					// forming the queryString
       						var data            = 'l=$l&date=$date&hour_name=$hour_name&search_query='+ searchString;
         
        					// if searchString is not empty
        					if(searchString) {
        						// ajax call
          						\$.ajax({
                						type: \"GET\",
               							url: \"food_diary_add_food_query.php\",
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

			<!-- Food Search -->
				<form method=\"get\" action=\"food_diary_add_food.php\" enctype=\"multipart/form-data\" id=\"inp_entry_food_query_form\">

					<p style=\"padding-top: 0;\"><b>$l_food_search</b><br />
					<input type=\"text\" id=\"inp_entry_food_query\" name=\"inp_entry_food_query\" value=\"";if(isset($_GET['inp_entry_food_query'])){ echo"$inp_entry_food_query"; } echo"\" size=\"17\" />
					<input type=\"hidden\" name=\"action\" value=\"search\" />
					<input type=\"hidden\" name=\"date\" value=\"$date\" />
					<input type=\"hidden\" name=\"hour_name\" value=\"$hour_name\" />
					<input type=\"submit\" value=\"$l_search\" class=\"btn btn_default\" />
					<a href=\"$root/food/new_food.php?l=$l\" class=\"btn btn_default\">$l_new_food</a>
					</p>
				</form>
			<!-- //Food Search -->
		<!-- //Search -->


		<!-- Menu -->
			<div class=\"tabs\">
				<ul>
					<li><a href=\"food_diary_add.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_recent</a></li>
					<li><a href=\"food_diary_add_food.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\" class=\"selected\">$l_food</a></li>
					<li><a href=\"food_diary_add_recipe.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_recipes</a></li>
				</ul>
			</div>
			<div class=\"clear\" style=\"height: 20px;\"></div>
		<!-- //Menu -->
		
		<!-- Adapter view -->";
			
			$query_t = "SELECT view_id, view_user_id, view_system, view_hundred_metric, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_food_diary_user_adapted_view WHERE view_user_id=$get_my_user_id";
			$result_t = mysqli_query($link, $query_t);
			$row_t = mysqli_fetch_row($result_t);
			list($get_current_view_id, $get_current_view_user_id, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
			echo"
			<p><a id=\"adapter_view\"></a>
			<b>$l_show_per:</b>
			<input type=\"checkbox\" name=\"inp_show_hundred_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=hundred_metric&amp;process=1&amp;referer=food_diary_add_food&amp;action=search&amp;inp_entry_food_query=$inp_entry_food_query&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l\""; if($get_current_view_hundred_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_hundred
			<input type=\"checkbox\" name=\"inp_show_pcs_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=pcs_metric&amp;process=1&amp;referer=food_diary_add_food&amp;action=search&amp;inp_entry_food_query=$inp_entry_food_query&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l\""; if($get_current_view_pcs_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_pcs_g
			<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=eight_us&amp;process=1&amp;referer=food_diary_add_food&amp;action=search&amp;inp_entry_food_query=$inp_entry_food_query&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l\""; if($get_current_view_eight_us == "1"){ echo" checked=\"checked\""; } echo" /> $l_eight
			<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=pcs_us&amp;process=1&amp;referer=food_diary_add_food&amp;action=search&amp;inp_entry_food_query=$inp_entry_food_query&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l\""; if($get_current_view_pcs_us == "1"){ echo" checked=\"checked\""; } echo" /> $l_pcs_oz
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
		<!-- //Adapter view -->


		<!-- Food that fits that search -->

			<div id=\"nettport_search_results\">
			";
	
			// Set layout
			$x = 0;

			// Get all food
			$inp_entry_food_query = "" . $inp_entry_food_query . "%";
			$inp_entry_food_query_mysql = quote_smart($link, $inp_entry_food_query);

			$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_text, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_numbers_entered_method, food_nutrition_facts_view_method, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_trans_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_added_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_trans_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_added_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_score_place_in_sub_category, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_trans_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_added_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_trans_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_added_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_energy_net_content, food_fat_net_content, food_saturated_fat_net_content, food_trans_fat_net_content, food_monounsaturated_fat_net_content, food_polyunsaturated_fat_net_content, food_cholesterol_net_content, food_carbohydrates_net_content, food_carbohydrates_of_which_sugars_net_content, food_added_sugars_net_content, food_dietary_fiber_net_content, food_proteins_net_content, food_salt_net_content, food_sodium_net_content, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_no_of_comments, food_stars, food_stars_sum, food_comments_multiplied_stars, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_language=$l_mysql";
			$query = $query . " AND food_name LIKE $inp_entry_food_query_mysql";
			$query = $query . " ORDER BY food_manufacturer_name, food_name ASC";

			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_text, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_numbers_entered_method, $get_food_nutrition_facts_view_method, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_trans_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_added_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_trans_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_added_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_score_place_in_sub_category, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_trans_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_added_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_trans_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_added_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_energy_net_content, $get_food_fat_net_content, $get_food_saturated_fat_net_content, $get_food_trans_fat_net_content, $get_food_monounsaturated_fat_net_content, $get_food_polyunsaturated_fat_net_content, $get_food_cholesterol_net_content, $get_food_carbohydrates_net_content, $get_food_carbohydrates_of_which_sugars_net_content, $get_food_added_sugars_net_content, $get_food_dietary_fiber_net_content, $get_food_proteins_net_content, $get_food_salt_net_content, $get_food_sodium_net_content, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_no_of_comments, $get_food_stars, $get_food_stars_sum, $get_food_comments_multiplied_stars, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row;
			
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
				} // get_current_view_hundred_metric
				echo"
				<!-- Add food -->
					<form method=\"post\" action=\"food_diary_add_food.php?action=add_food_to_diary&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
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

			// Broader search
			$inp_entry_food_query = "%" . $inp_entry_food_query . "";
			$inp_entry_food_query_mysql = quote_smart($link, $inp_entry_food_query);

			$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_text, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_numbers_entered_method, food_nutrition_facts_view_method, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_trans_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_added_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_trans_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_added_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_score_place_in_sub_category, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_trans_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_added_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_trans_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_added_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_energy_net_content, food_fat_net_content, food_saturated_fat_net_content, food_trans_fat_net_content, food_monounsaturated_fat_net_content, food_polyunsaturated_fat_net_content, food_cholesterol_net_content, food_carbohydrates_net_content, food_carbohydrates_of_which_sugars_net_content, food_added_sugars_net_content, food_dietary_fiber_net_content, food_proteins_net_content, food_salt_net_content, food_sodium_net_content, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_no_of_comments, food_stars, food_stars_sum, food_comments_multiplied_stars, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_language=$l_mysql";
			$query = $query . " AND food_name LIKE $inp_entry_food_query_mysql";
			$query = $query . " ORDER BY food_manufacturer_name, food_name ASC";

			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_text, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_numbers_entered_method, $get_food_nutrition_facts_view_method, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_trans_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_added_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_trans_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_added_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_score_place_in_sub_category, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_trans_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_added_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_trans_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_added_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_energy_net_content, $get_food_fat_net_content, $get_food_saturated_fat_net_content, $get_food_trans_fat_net_content, $get_food_monounsaturated_fat_net_content, $get_food_polyunsaturated_fat_net_content, $get_food_cholesterol_net_content, $get_food_carbohydrates_net_content, $get_food_carbohydrates_of_which_sugars_net_content, $get_food_added_sugars_net_content, $get_food_dietary_fiber_net_content, $get_food_proteins_net_content, $get_food_salt_net_content, $get_food_sodium_net_content, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_no_of_comments, $get_food_stars, $get_food_stars_sum, $get_food_comments_multiplied_stars, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row;
			
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
				} // get_current_view_hundred_metric
				echo"
				<!-- Add food -->
					<form method=\"post\" action=\"food_diary_add_food.php?action=add_food_to_diary&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
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
			} // while broader search
			if($x == "1"){
				echo"
					<div class=\"left_center_center_left_right_center\" style=\"text-align: center;padding-bottom: 20px;\">
					</div>
					<div class=\"left_center_center_right_right_center\" style=\"text-align: center;padding-bottom: 20px;\">
					</div>
					<div class=\"left_center_center_right_right\" style=\"text-align: center;padding-bottom: 20px;\">
					</div>
					<div class=\"clear\"></div>
				";
			}
			echo"
			</div> <!-- //nettport_search_results -->
		
		<!-- //Food that fits that search -->


		";
	} // action == ""
	elseif($action == "open_main_category"){	
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
			<h1>$l_new_entry</h1>

	
			<!-- You are here -->
				<p><b>$l_you_are_here</b><br />
				<a href=\"index.php?l=$l\">$l_food_diary</a>
				&gt;
				<a href=\"food_diary_add.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_new_entry</a>
				&gt;
				<a href=\"food_diary_add_food.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_food</a>
				&gt;
				<a href=\"food_diary_add_food.php?action=$action&amp;date=$date&amp;hour_name=$hour_name&amp;main_category_id=$main_category_id&amp;l=$l\">$get_current_main_category_translation_value</a>
				</p>
			<!-- //You are here -->


			<!-- Search -->	
				<!-- Search engines Autocomplete -->
				<script>";
				if(!(isset($_GET['focus']))){
					echo"
					\$(document).ready(function(){
						\$('[name=\"inp_entry_food_query\"]').focus();
					});
					";
				}
				echo"
				\$(document).ready(function () {
					\$('#inp_entry_food_query').keyup(function () {
						\$(\"#food_categories\").hide();

        					// getting the value that user typed
        					var searchString    = $(\"#inp_entry_food_query\").val();
        					// forming the queryString
       						var data            = 'l=$l&date=$date&hour_name=$hour_name&search_query='+ searchString;
         
        					// if searchString is not empty
        					if(searchString) {
        						// ajax call
          						\$.ajax({
                						type: \"GET\",
               							url: \"food_diary_add_food_query.php\",
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

				<!-- Food Search -->
				<form method=\"get\" action=\"food_diary_add_food.php\" enctype=\"multipart/form-data\" id=\"inp_entry_food_query_form\">
					<p style=\"padding-top: 0;\"><b>$l_food_search</b><br />
					<input type=\"text\" id=\"inp_entry_food_query\" name=\"inp_entry_food_query\" value=\"";if(isset($_GET['inp_entry_food_query'])){ echo"$inp_entry_food_query"; } echo"\" size=\"17\" />
					<input type=\"hidden\" name=\"action\" value=\"search\" />
					<input type=\"hidden\" name=\"date\" value=\"$date\" />
					<input type=\"hidden\" name=\"hour_name\" value=\"$hour_name\" />
					<input type=\"submit\" value=\"$l_search\" class=\"btn btn_default\" />
					<a href=\"$root/food/new_food.php?l=$l\" class=\"btn btn_default\">$l_new_food</a>
					</p>
				</form>
				<!-- //Food Search -->
			<!-- //Search -->


			<!-- Menu -->
			<div class=\"tabs\">
				<ul>
					<li><a href=\"food_diary_add.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_recent</a></li>
					<li><a href=\"food_diary_add_food.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\" class=\"selected\">$l_food</a></li>
					<li><a href=\"food_diary_add_recipe.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_recipes</a></li>
				</ul>
			</div>
			<div class=\"clear\" style=\"height: 20px;\"></div>
			<!-- //Menu -->

			<!-- Food sub categories -->
			<div class=\"vertical\" id=\"food_categories\">
				<ul>
					<li><a href=\"food_diary_add_food.php?action=$action&amp;date=$date&amp;hour_name=$hour_name&amp;main_category_id=$main_category_id&amp;l=$l\">$get_current_main_category_translation_value</a>\n";
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

					echo"						";
					echo"<li><a href=\"food_diary_add_food.php?action=open_sub_category&amp;date=$date&amp;hour_name=$hour_name&amp;inp_entry_food_query=$inp_entry_food_query&amp;main_category_id=$main_category_id&amp;sub_category_id=$get_sub_category_id&amp;l=$l\""; if($sub_category_id == "$get_sub_category_id"){ echo" style=\"font-weight: bold;\"";}echo">&nbsp; &nbsp; $get_sub_category_translation_value</a></li>\n";


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
			<!-- //Food sub categories -->
	
			
			<!-- Adapter view -->";
			
				$query_t = "SELECT view_id, view_user_id, view_system, view_hundred_metric, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_food_diary_user_adapted_view WHERE view_user_id=$get_my_user_id";
				$result_t = mysqli_query($link, $query_t);
				$row_t = mysqli_fetch_row($result_t);
				list($get_current_view_id, $get_current_view_user_id, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
				echo"
				<p><a id=\"adapter_view\"></a>
				<b>$l_show_per:</b>
				<input type=\"checkbox\" name=\"inp_show_hundred_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=hundred_metric&amp;process=1&amp;referer=food_diary_add_food&amp;action=open_main_category&amp;main_category_id=$main_category_id&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l\""; if($get_current_view_hundred_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_hundred
				<input type=\"checkbox\" name=\"inp_show_pcs_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=pcs_metric&amp;process=1&amp;referer=food_diary_add_food&amp;action=open_main_category&amp;main_category_id=$main_category_id&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l\""; if($get_current_view_pcs_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_pcs_g
				<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=eight_us&amp;process=1&amp;referer=food_diary_add_food&amp;action=open_main_category&amp;main_category_id=$main_category_id&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l\""; if($get_current_view_eight_us == "1"){ echo" checked=\"checked\""; } echo" /> $l_eight
				<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=pcs_us&amp;process=1&amp;referer=food_diary_add_food&amp;action=open_main_category&amp;main_category_id=$main_category_id&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l\""; if($get_current_view_pcs_us == "1"){ echo" checked=\"checked\""; } echo" /> $l_pcs_oz
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
			<!-- //Adapter view -->

			<!-- List all food in main category -->



					<div id=\"nettport_search_results\">
					";
	
					// Set layout
					$x = 0;

					// Get all food
					$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_text, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_numbers_entered_method, food_nutrition_facts_view_method, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_trans_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_added_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_trans_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_added_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_score_place_in_sub_category, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_trans_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_added_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_trans_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_added_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_energy_net_content, food_fat_net_content, food_saturated_fat_net_content, food_trans_fat_net_content, food_monounsaturated_fat_net_content, food_polyunsaturated_fat_net_content, food_cholesterol_net_content, food_carbohydrates_net_content, food_carbohydrates_of_which_sugars_net_content, food_added_sugars_net_content, food_dietary_fiber_net_content, food_proteins_net_content, food_salt_net_content, food_sodium_net_content, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_no_of_comments, food_stars, food_stars_sum, food_comments_multiplied_stars, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_language=$l_mysql ";
					if(isset($_GET['inp_entry_food_query'])){
						$inp_entry_food_query = $_GET['inp_entry_food_query'];
						$inp_entry_food_query = strip_tags(stripslashes($inp_entry_food_query));
						$inp_entry_food_query = output_html($inp_entry_food_query);
						if($inp_entry_food_query != ""){
							$inp_entry_food_query = "%" . $inp_entry_food_query . "%";
							$inp_entry_food_query_mysql = quote_smart($link, $inp_entry_food_query);

							$query = $query . " AND food_name LIKE $inp_entry_food_query_mysql";
						}
					}
					$query = $query . " AND food_main_category_id=$get_current_main_category_id";
					$query = $query . " ORDER BY food_last_used, food_manufacturer_name, food_name ASC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_text, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_numbers_entered_method, $get_food_nutrition_facts_view_method, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_trans_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_added_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_trans_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_added_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_score_place_in_sub_category, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_trans_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_added_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_trans_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_added_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_energy_net_content, $get_food_fat_net_content, $get_food_saturated_fat_net_content, $get_food_trans_fat_net_content, $get_food_monounsaturated_fat_net_content, $get_food_polyunsaturated_fat_net_content, $get_food_cholesterol_net_content, $get_food_carbohydrates_net_content, $get_food_carbohydrates_of_which_sugars_net_content, $get_food_added_sugars_net_content, $get_food_dietary_fiber_net_content, $get_food_proteins_net_content, $get_food_salt_net_content, $get_food_sodium_net_content, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_no_of_comments, $get_food_stars, $get_food_stars_sum, $get_food_comments_multiplied_stars, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row;
			
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
						} // get_current_view_hundred_metric
						echo"
						<!-- Add food -->
							<form method=\"post\" action=\"food_diary_add_food.php?action=add_food_to_diary&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
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
		
			<!-- //List all food in main category -->
			";
		} // main category found
	} // open main category
	elseif($action == "open_sub_category"){	
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
				<h1>$l_new_entry</h1>

	
				<!-- You are here -->
					<p><b>$l_you_are_here</b><br />
					<a href=\"index.php?l=$l\">$l_food_diary</a>
					&gt;
					<a href=\"food_diary_add.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_new_entry</a>
					&gt;
					<a href=\"food_diary_add_food.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_food</a>
					&gt;
					<a href=\"food_diary_add_food.php?action=open_main_category&amp;date=$date&amp;hour_name=$hour_name&amp;main_category_id=$main_category_id&amp;l=$l\">$get_current_main_category_translation_value</a>
					&gt;
					<a href=\"food_diary_add_food.php?action=$action&amp;date=$date&amp;hour_name=$hour_name&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&am;l=$l\">$get_current_sub_category_translation_value</a>
					</p>
				<!-- //You are here -->


				<!-- Search -->	
				<!-- Search engines Autocomplete -->
				<script>";
				if(!(isset($_GET['focus']))){
					echo"
					\$(document).ready(function(){
						\$('[name=\"inp_entry_food_query\"]').focus();
					});
					";
				}
				echo"
				\$(document).ready(function () {
					\$('#inp_entry_food_query').keyup(function () {
        					// getting the value that user typed
        					var searchString    = $(\"#inp_entry_food_query\").val();
        					// forming the queryString
       						var data            = 'l=$l&date=$date&hour_name=$hour_name&search_query='+ searchString;
         
        					// if searchString is not empty
        					if(searchString) {
        						// ajax call
          						\$.ajax({
                						type: \"GET\",
               							url: \"food_diary_add_food_query.php\",
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

				<!-- Food Search -->
				<form method=\"get\" action=\"food_diary_add_food.php\" enctype=\"multipart/form-data\" id=\"inp_entry_food_query_form\">

					<p style=\"padding-top: 0;\"><b>$l_food_search</b><br />
					<input type=\"text\" id=\"inp_entry_food_query\" name=\"inp_entry_food_query\" value=\"";if(isset($_GET['inp_entry_food_query'])){ echo"$inp_entry_food_query"; } echo"\" size=\"17\" />
					<input type=\"hidden\" name=\"action\" value=\"search\" />
					<input type=\"hidden\" name=\"date\" value=\"$date\" />
					<input type=\"hidden\" name=\"hour_name\" value=\"$hour_name\" />
					<input type=\"submit\" value=\"$l_search\" class=\"btn btn_default\" />
					<a href=\"$root/food/new_food.php?l=$l\" class=\"btn btn_default\">$l_new_food</a>
					</p>
				</form>
				<!-- //Food Search -->
				<!-- //Search -->


				<!-- Menu -->
					<div class=\"tabs\">
						<ul>
							<li><a href=\"food_diary_add.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_recent</a></li>
							<li><a href=\"food_diary_add_food.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\" class=\"selected\">$l_food</a></li>
							<li><a href=\"food_diary_add_recipe.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_recipes</a></li>
						</ul>
					</div>
					<div class=\"clear\" style=\"height: 20px;\"></div>
				<!-- //Menu -->
				

				<!-- Adapter view -->";
					$query_t = "SELECT view_id, view_user_id, view_system, view_hundred_metric, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_food_diary_user_adapted_view WHERE view_user_id=$get_my_user_id";
					$result_t = mysqli_query($link, $query_t);
					$row_t = mysqli_fetch_row($result_t);
					list($get_current_view_id, $get_current_view_user_id, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
					echo"
					<p><a id=\"adapter_view\"></a>
					<b>$l_show_per:</b>
					<input type=\"checkbox\" name=\"inp_show_hundred_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=hundred_metric&amp;process=1&amp;referer=food_diary_add_food&amp;action=open_main_category&amp;main_category_id=$main_category_id&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l\""; if($get_current_view_hundred_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_hundred
					<input type=\"checkbox\" name=\"inp_show_pcs_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=pcs_metric&amp;process=1&amp;referer=food_diary_add_food&amp;action=open_main_category&amp;main_category_id=$main_category_id&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l\""; if($get_current_view_pcs_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_pcs_g
					<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=eight_us&amp;process=1&amp;referer=food_diary_add_food&amp;action=open_main_category&amp;main_category_id=$main_category_id&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l\""; if($get_current_view_eight_us == "1"){ echo" checked=\"checked\""; } echo" /> $l_eight
					<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=pcs_us&amp;process=1&amp;referer=food_diary_add_food&amp;action=open_main_category&amp;main_category_id=$main_category_id&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l\""; if($get_current_view_pcs_us == "1"){ echo" checked=\"checked\""; } echo" /> $l_pcs_oz
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
				<!-- //Adapter view -->

				<!-- List all food in sub category -->



					<div id=\"nettport_search_results\">
					";
	
					// Set layout
					$x = 0;

					// Get all food
					$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_text, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_numbers_entered_method, food_nutrition_facts_view_method, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_trans_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_added_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_trans_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_added_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_score_place_in_sub_category, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_trans_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_added_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_trans_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_added_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_energy_net_content, food_fat_net_content, food_saturated_fat_net_content, food_trans_fat_net_content, food_monounsaturated_fat_net_content, food_polyunsaturated_fat_net_content, food_cholesterol_net_content, food_carbohydrates_net_content, food_carbohydrates_of_which_sugars_net_content, food_added_sugars_net_content, food_dietary_fiber_net_content, food_proteins_net_content, food_salt_net_content, food_sodium_net_content, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_no_of_comments, food_stars, food_stars_sum, food_comments_multiplied_stars, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_language=$l_mysql";
					if(isset($_GET['inp_entry_food_query'])){
						$inp_entry_food_query = $_GET['inp_entry_food_query'];
						$inp_entry_food_query = strip_tags(stripslashes($inp_entry_food_query));
						$inp_entry_food_query = output_html($inp_entry_food_query);
						
						if($inp_entry_food_query != ""){
							$inp_entry_food_query = "%" . $inp_entry_food_query . "%";
							$inp_entry_food_query_mysql = quote_smart($link, $inp_entry_food_query);

							$query = $query . " AND food_name LIKE $inp_entry_food_query_mysql";
						}
					}
					if($sub_category_id != ""){
						$sub_category_id_mysql = quote_smart($link, $sub_category_id);
						$query = $query . " AND food_sub_category_id=$sub_category_id_mysql";
					}

					$query = $query . " ORDER BY food_last_used, food_manufacturer_name, food_name ASC";

					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_text, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_numbers_entered_method, $get_food_nutrition_facts_view_method, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_trans_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_added_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_trans_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_added_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_score_place_in_sub_category, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_trans_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_added_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_trans_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_added_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_energy_net_content, $get_food_fat_net_content, $get_food_saturated_fat_net_content, $get_food_trans_fat_net_content, $get_food_monounsaturated_fat_net_content, $get_food_polyunsaturated_fat_net_content, $get_food_cholesterol_net_content, $get_food_carbohydrates_net_content, $get_food_carbohydrates_of_which_sugars_net_content, $get_food_added_sugars_net_content, $get_food_dietary_fiber_net_content, $get_food_proteins_net_content, $get_food_salt_net_content, $get_food_sodium_net_content, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_no_of_comments, $get_food_stars, $get_food_stars_sum, $get_food_comments_multiplied_stars, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row;
			
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
						} // get_current_view_hundred_metric
						echo"
						<!-- Add food -->
							<form method=\"post\" action=\"food_diary_add_food.php?action=add_food_to_diary&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
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
					<div class=\"clear\"></div>
				<!-- //List all food in sub category -->
					
				";
			} // sub category found
		} // main category found
	} // open sub category
	elseif($action == "add_food_to_diary"){
		if($process == 1){
			$datetime = date("Y-m-d H:i:s");

			$inp_entry_date = output_html($date);
			$inp_entry_date_mysql = quote_smart($link, $inp_entry_date);

			$inp_entry_date_saying = date("j M Y");
			$inp_entry_date_saying_mysql = quote_smart($link, $inp_entry_date_saying);

			$inp_entry_hour_name = output_html($hour_name);
			$inp_entry_hour_name_mysql = quote_smart($link, $inp_entry_hour_name);

			$inp_entry_food_id = $_POST['inp_entry_food_id'];
			$inp_entry_food_id = output_html($inp_entry_food_id);
			$inp_entry_food_id_mysql = quote_smart($link, $inp_entry_food_id);

			$inp_entry_food_serving_size = $_POST['inp_entry_food_serving_size'];
			$inp_entry_food_serving_size = output_html($inp_entry_food_serving_size);
			$inp_entry_food_serving_size = str_replace(",", ".", $inp_entry_food_serving_size);
			$inp_entry_food_serving_size_mysql = quote_smart($link, $inp_entry_food_serving_size);
			if($inp_entry_food_serving_size == ""){
				$url = "food_diary_add_food.php?date=$date&hour_name=$hour_name&l=$l";
				$url = $url . "&ft=error&fm=missing_amount";
				header("Location: $url");
				exit;
			}


			// get food
			$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_text, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_numbers_entered_method, food_nutrition_facts_view_method, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_trans_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_added_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_trans_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_added_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_score_place_in_sub_category, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_trans_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_added_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_trans_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_added_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_energy_net_content, food_fat_net_content, food_saturated_fat_net_content, food_trans_fat_net_content, food_monounsaturated_fat_net_content, food_polyunsaturated_fat_net_content, food_cholesterol_net_content, food_carbohydrates_net_content, food_carbohydrates_of_which_sugars_net_content, food_added_sugars_net_content, food_dietary_fiber_net_content, food_proteins_net_content, food_salt_net_content, food_sodium_net_content, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_no_of_comments, food_stars, food_stars_sum, food_comments_multiplied_stars, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_id=$inp_entry_food_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_text, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_numbers_entered_method, $get_food_nutrition_facts_view_method, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_trans_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_added_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_trans_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_added_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_score_place_in_sub_category, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_trans_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_added_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_trans_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_added_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_energy_net_content, $get_food_fat_net_content, $get_food_saturated_fat_net_content, $get_food_trans_fat_net_content, $get_food_monounsaturated_fat_net_content, $get_food_polyunsaturated_fat_net_content, $get_food_cholesterol_net_content, $get_food_carbohydrates_net_content, $get_food_carbohydrates_of_which_sugars_net_content, $get_food_added_sugars_net_content, $get_food_dietary_fiber_net_content, $get_food_proteins_net_content, $get_food_salt_net_content, $get_food_sodium_net_content, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_no_of_comments, $get_food_stars, $get_food_stars_sum, $get_food_comments_multiplied_stars, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row;

			if($get_food_id == ""){
				$url = "food_diary_add_food.php?date=$date&hour_name=$hour_name&l=$l";
				$url = $url . "&ft=error&fm=food_not_found";
				header("Location: $url");
				exit;
			}
			
			// Update food last used date
			mysqli_query($link, "UPDATE $t_food_index SET food_last_viewed='$date' WHERE food_id=$get_food_id") or die(mysqli_error($link));
			
			
			$inp_entry_food_name = output_html($get_food_name);
			$inp_entry_food_name = str_replace("&amp;amp;", "&amp;", $inp_entry_food_name);
			$len = strlen($inp_entry_food_name);
			if($len > 23){
				$inp_entry_food_name = substr($inp_entry_food_name, 0, 20);
				$inp_entry_food_name = $inp_entry_food_name . "...";
			}
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


			// 1) Insert food into entry
			mysqli_query($link, "INSERT INTO $t_food_diary_entires
			(entry_id, entry_user_id, entry_date, entry_date_saying, entry_hour_name, entry_food_id, 
			entry_recipe_id, entry_meal_id, entry_name, entry_manufacturer_name, entry_serving_size, entry_serving_size_measurement, 
			entry_energy_per_entry, entry_fat_per_entry, entry_saturated_fat_per_entry, entry_monounsaturated_fat_per_entry, entry_polyunsaturated_fat_per_entry, 
			entry_cholesterol_per_entry, entry_carbohydrates_per_entry, entry_carbohydrates_of_which_sugars_per_entry, entry_dietary_fiber_per_entry, entry_proteins_per_entry, 
			entry_salt_per_entry, entry_sodium_per_entry, entry_updated_datetime, entry_synchronized) 
			VALUES 
			(NULL, '$get_my_user_id', $inp_entry_date_mysql, $inp_entry_date_saying_mysql, $inp_entry_hour_name_mysql, $inp_entry_food_id_mysql, 
			'0', '0', $inp_entry_food_name_mysql, $inp_entry_food_manufacturer_name_mysql, $inp_entry_food_serving_size_mysql, $inp_entry_food_serving_size_measurement_mysql, 
			$inp_entry_food_energy_per_entry_mysql, $inp_entry_food_fat_per_entry_mysql, $inp_entry_food_saturated_fat_per_entry_mysql, $inp_entry_food_monounsaturated_fat_per_entry_mysql, $inp_entry_food_polyunsaturated_fat_per_entry_mysql, 
			$inp_entry_food_cholesterol_per_entry_mysql, $inp_entry_food_carb_per_entry_mysql, $inp_entry_food_carbohydrates_of_which_sugars_per_entry_mysql, $inp_entry_food_dietary_fiber_per_entry_mysql, $inp_entry_food_protein_per_entry_mysql,
			$inp_entry_food_salt_per_entry_mysql, $inp_entry_food_sodium_per_entry_mysql, '$datetime', '0')")
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
			
			$inp_hour_energy = round($inp_hour_energy, 0);
			$inp_hour_fat = round($inp_hour_fat, 0);
			$inp_hour_saturated_fat = round($inp_hour_saturated_fat, 0);
			$inp_hour_monounsaturated_fat = round($inp_hour_monounsaturated_fat, 0);
			$inp_hour_polyunsaturated_fat = round($inp_hour_polyunsaturated_fat, 0);
			$inp_hour_cholesterol = round($inp_hour_cholesterol, 0);
			$inp_hour_carbohydrates = round($inp_hour_carbohydrates, 0);
			$inp_hour_carbohydrates_of_which_sugars = round($inp_hour_carbohydrates_of_which_sugars, 0);
			$inp_hour_dietary_fiber = round($inp_hour_dietary_fiber, 0);
			$inp_hour_proteins = round($inp_hour_proteins, 0);
			$inp_hour_salt = round($inp_hour_salt, 0);
			$inp_hour_sodium = round($inp_hour_sodium, 0);

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
			
			$inp_consumed_day_energy 			= round($inp_consumed_day_energy, 0);
			$inp_consumed_day_fat 				= round($inp_consumed_day_fat, 0);
			$inp_consumed_day_saturated_fat 		= round($inp_consumed_day_saturated_fat, 0);
			$inp_consumed_day_monounsaturated_fat 		= round($inp_consumed_day_monounsaturated_fat, 0);
			$inp_consumed_day_polyunsaturated_fat 		= round($inp_consumed_day_polyunsaturated_fat, 0);
			$inp_consumed_day_cholesterol 			= round($inp_consumed_day_cholesterol, 0);
			$inp_consumed_day_carbohydrates 		= round($inp_consumed_day_carbohydrates, 0);
			$inp_consumed_day_carbohydrates_of_which_sugars = round($inp_consumed_day_carbohydrates_of_which_sugars, 0);
			$inp_consumed_day_dietary_fiber 		= round($inp_consumed_day_dietary_fiber, 0);
			$inp_consumed_day_proteins 			= round($inp_consumed_day_proteins, 0);
			$inp_consumed_day_salt 				= round($inp_consumed_day_salt, 0);
			$inp_consumed_day_sodium 			= round($inp_consumed_day_sodium, 0);

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


			// 4) Insert into last used food
			$inp_last_used_name_mysql = quote_smart($link, $get_food_name);
			$inp_last_used_manufacturer = quote_smart($link, $get_food_manufacturer_name);
			$inp_last_used_image_path = quote_smart($link, $get_food_image_path);
			$inp_last_used_image_thumb_132x132 = quote_smart($link, $get_food_thumb_a_small); 

			// last_used_metric_or_us
			$inp_last_used_metric_or_us = "";
			$inp_last_used_selected_measurement = "";
			if (isset($_POST['inp_submit_metric'])) {
				$inp_last_used_metric_or_us = "metric";
				$inp_last_used_selected_measurement = "$get_food_serving_size_measurement_metric";
			}
			else{
				if (isset($_POST['inp_submit_us'])) {
					$inp_last_used_metric_or_us = "us";
					$inp_last_used_selected_measurement = "$get_food_serving_size_measurement_us";
				}
				else{
					$inp_last_used_metric_or_us = "metric";
					$inp_last_used_selected_measurement = "$get_food_serving_size_pcs_measurement";
				}
			}
			$inp_last_used_metric_or_us_mysql = quote_smart($link, $inp_last_used_metric_or_us);
			$inp_last_used_selected_measurement_mysql = quote_smart($link, $inp_last_used_selected_measurement);

			$inp_last_used_selected_serving_size_mysql = quote_smart($link, $inp_entry_food_serving_size);

			$inp_last_used_serving_size_metric_mysql = quote_smart($link, $get_food_serving_size_metric);
			$inp_last_used_serving_size_measurement_metric_mysql = quote_smart($link, $get_food_serving_size_measurement_metric);
			$inp_last_used_serving_size_us_mysql = quote_smart($link, $get_food_serving_size_us);
			$inp_last_used_serving_size_measurement_us_mysql = quote_smart($link, $get_food_serving_size_measurement_us);
			$inp_last_used_serving_size_pcs_mysql = quote_smart($link, $get_food_serving_size_pcs);
			$inp_last_used_serving_size_pcs_measurement_mysql = quote_smart($link,  $get_food_serving_size_pcs_measurement);




			$inp_last_used_energy_metric_mysql = quote_smart($link, $get_food_energy_metric);
			$inp_last_used_fat_metric_mysql = quote_smart($link, $get_food_fat_metric);
			$inp_last_used_saturated_fat_metric_mysql = quote_smart($link, $get_food_saturated_fat_metric);
			$inp_last_used_monounsaturated_fat_metric_mysql = quote_smart($link, $get_food_monounsaturated_fat_metric);
			$inp_last_used_polyunsaturated_fat_metric_mysql = quote_smart($link, $get_food_polyunsaturated_fat_metric);
			$inp_last_used_cholesterol_metric_mysql = quote_smart($link, $get_food_cholesterol_metric);
			$inp_last_used_carbohydrates_metric_mysql = quote_smart($link, $get_food_carbohydrates_metric);
			$inp_last_used_carbohydrates_of_which_sugars_metric_mysql = quote_smart($link, $get_food_carbohydrates_of_which_sugars_metric);
			$inp_last_used_dietary_fiber_metric_mysql = quote_smart($link, $get_food_dietary_fiber_metric);
			$inp_last_used_proteins_metric_mysql = quote_smart($link, $get_food_proteins_metric);
			$inp_last_used_salt_metric_mysql = quote_smart($link, $get_food_salt_metric);
			$inp_last_used_sodium_metric_mysql = quote_smart($link, $get_food_sodium_metric);

			$inp_last_used_energy_us_mysql = quote_smart($link, $get_food_energy_us);
			$inp_last_used_fat_us_mysql = quote_smart($link, $get_food_fat_us);
			$inp_last_used_saturated_fat_us_mysql = quote_smart($link, $get_food_saturated_fat_us);
			$inp_last_used_monounsaturated_fat_us_mysql = quote_smart($link, $get_food_monounsaturated_fat_us);
			$inp_last_used_polyunsaturated_fat_us_mysql = quote_smart($link, $get_food_polyunsaturated_fat_us);
			$inp_last_used_cholesterol_us_mysql = quote_smart($link, $get_food_cholesterol_us);
			$inp_last_used_carbohydrates_us_mysql = quote_smart($link, $get_food_carbohydrates_us);
			$inp_last_used_carbohydrates_of_which_sugars_us_mysql = quote_smart($link, $get_food_carbohydrates_of_which_sugars_us);
			$inp_last_used_dietary_fiber_us_mysql = quote_smart($link, $get_food_dietary_fiber_us);
			$inp_last_used_proteins_us_mysql = quote_smart($link, $get_food_proteins_us);
			$inp_last_used_salt_us_mysql = quote_smart($link, $get_food_salt_us);
			$inp_last_used_sodium_us_mysql = quote_smart($link, $get_food_sodium_us);




			$query = "SELECT last_used_id, last_used_times FROM $t_food_diary_last_used WHERE last_used_user_id=$my_user_id_mysql AND last_used_hour_name=$inp_entry_hour_name_mysql AND last_used_food_id=$inp_entry_food_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_last_used_id, $get_last_used_times) = $row;
			if($get_last_used_id == ""){
				// First time used this food
				mysqli_query($link, "INSERT INTO $t_food_diary_last_used
				(last_used_id, last_used_user_id, last_used_hour_name, last_used_food_id, last_used_recipe_id, 
				last_used_meal_id, last_used_times, last_used_created_datetime, last_used_updated_datetime, last_used_name, last_used_manufacturer, 
				last_used_image_path, last_used_image_thumb_132x132, last_used_main_category_id, last_used_sub_category_id, last_used_metric_or_us, 
				last_used_selected_serving_size, last_used_selected_measurement, last_used_serving_size_metric, last_used_serving_size_measurement_metric, last_used_serving_size_us, 
				last_used_serving_size_measurement_us, last_used_serving_size_pcs, last_used_serving_size_pcs_measurement, last_used_energy_metric, last_used_fat_metric, 
				last_used_saturated_fat_metric, last_used_monounsaturated_fat_metric, last_used_polyunsaturated_fat_metric, last_used_cholesterol_metric, last_used_carbohydrates_metric, 
				last_used_carbohydrates_of_which_sugars_metric, last_used_dietary_fiber_metric, last_used_proteins_metric, last_used_salt_metric, last_used_sodium_metric, 
				last_used_energy_us, last_used_fat_us, last_used_saturated_fat_us, last_used_monounsaturated_fat_us, last_used_polyunsaturated_fat_us, 
				last_used_cholesterol_us, last_used_carbohydrates_us, last_used_carbohydrates_of_which_sugars_us, last_used_dietary_fiber_us, last_used_proteins_us, 
				last_used_salt_us, last_used_sodium_us, last_used_energy_serving, last_used_fat_serving, last_used_saturated_fat_serving, 
				last_used_monounsaturated_fat_serving, last_used_polyunsaturated_fat_serving, last_used_cholesterol_serving, last_used_carbohydrates_serving, last_used_carbohydrates_of_which_sugars_serving, 
				last_used_dietary_fiber_serving, last_used_proteins_serving, last_used_salt_serving, last_used_sodium_serving) 
				VALUES 
				(NULL, '$get_my_user_id', $inp_entry_hour_name_mysql, $inp_entry_food_id_mysql, 0, 
				0, 1, '$datetime', '$datetime', $inp_last_used_name_mysql, $inp_last_used_manufacturer, $inp_last_used_image_path, 
				$inp_last_used_image_thumb_132x132, $get_food_main_category_id, $get_food_sub_category_id, $inp_last_used_metric_or_us_mysql, $inp_last_used_selected_serving_size_mysql, 
				$inp_last_used_selected_measurement_mysql, $inp_last_used_serving_size_metric_mysql, $inp_last_used_serving_size_measurement_metric_mysql, $inp_last_used_serving_size_us_mysql, $inp_last_used_serving_size_measurement_us_mysql,
				$inp_last_used_serving_size_pcs_mysql, $inp_last_used_serving_size_pcs_measurement_mysql, $inp_last_used_energy_metric_mysql, $inp_last_used_fat_metric_mysql, $inp_last_used_saturated_fat_metric_mysql, 
				$inp_last_used_monounsaturated_fat_metric_mysql, $inp_last_used_polyunsaturated_fat_metric_mysql, $inp_last_used_cholesterol_metric_mysql, $inp_last_used_carbohydrates_metric_mysql, $inp_last_used_carbohydrates_of_which_sugars_metric_mysql, 
				$inp_last_used_dietary_fiber_metric_mysql, $inp_last_used_proteins_metric_mysql, $inp_last_used_salt_metric_mysql, $inp_last_used_sodium_metric_mysql, $inp_last_used_energy_us_mysql,	
				$inp_last_used_fat_us_mysql, $inp_last_used_saturated_fat_us_mysql, $inp_last_used_monounsaturated_fat_us_mysql, $inp_last_used_polyunsaturated_fat_us_mysql, $inp_last_used_cholesterol_us_mysql, 
				$inp_last_used_carbohydrates_us_mysql, $inp_last_used_carbohydrates_of_which_sugars_us_mysql, $inp_last_used_dietary_fiber_us_mysql, $inp_last_used_proteins_us_mysql, $inp_last_used_salt_us_mysql, 
				$inp_last_used_sodium_us_mysql, $inp_entry_food_energy_per_entry_mysql, $inp_entry_food_fat_per_entry_mysql, $inp_entry_food_saturated_fat_per_entry_mysql, $inp_entry_food_monounsaturated_fat_per_entry_mysql, 
				$inp_entry_food_polyunsaturated_fat_per_entry_mysql, $inp_entry_food_cholesterol_per_entry_mysql, $inp_entry_food_carb_per_entry_mysql, $inp_entry_food_carbohydrates_of_which_sugars_per_entry_mysql, $inp_entry_food_dietary_fiber_per_entry_mysql, 
				$inp_entry_food_protein_per_entry_mysql, $inp_entry_food_salt_per_entry_mysql, $inp_entry_food_sodium_per_entry_mysql)")
				or die(mysqli_error($link));
			}
			else{
				// Update counter and date
				$inp_last_used_times = $get_last_used_times + 1;

				$result = mysqli_query($link, "UPDATE $t_food_diary_last_used SET 
								last_used_times='$inp_last_used_times', 
								last_used_updated_datetime='$datetime', 
								last_used_selected_serving_size=$inp_entry_food_serving_size_mysql
				 WHERE last_used_id='$get_last_used_id'") or die(mysqli_error($link));

			}

			// Update food last used date
			$result = mysqli_query($link, "UPDATE $t_food_index SET 
							food_last_used='$date'
							 WHERE food_id=$get_food_id") or die(mysqli_error($link));


			// Header back to add food page
			$url = "food_diary_add_food.php?date=$date&hour_name=$hour_name&l=$l&ft=success&fm=food_added&food_name=$inp_entry_food_name";
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