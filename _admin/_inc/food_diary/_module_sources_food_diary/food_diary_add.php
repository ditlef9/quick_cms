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
$hour_name_mysql = quote_smart($link, $hour_name);

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
	

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/food_diary/ts_index.php");


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
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_alias, user_email, user_gender, user_height, user_measurement, user_dob FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_alias, $get_my_user_email, $get_my_user_gender, $get_my_user_height, $get_user_measurement, $get_my_user_dob) = $row;
	
	if($action == ""){

		echo"
		<h1>$l_new_entry</h1>

	
		<!-- Feedback -->
		";
		if($ft != "" && $fm != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><p>$fm</p></div>";
		}
		echo"
		<!-- //Feedback -->


		<!-- You are here -->
			<p><b>$l_you_are_here</b><br />
			<a href=\"index.php?l=$l\">$l_food_diary</a>
			&gt;
			<a href=\"food_diary_add_food.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_new_entry</a>
			</p>
		<!-- //You are here -->


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
					<input type=\"submit\" value=\"$l_search\" class=\"btn_default\" />
					<a href=\"$root/food/new_food.php?l=$l\" class=\"btn_default\">$l_new_food</a>
					</p>
				</form>
			<!-- //Food Search -->
		<!-- //Search -->


	
		<div class=\"tabs\" style=\"margin-top: 10px;\">
			<ul>
				<li><a href=\"food_diary_add.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\" class=\"selected\">$l_recent</a></li>
				<li><a href=\"food_diary_add_food.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_food</a></li>
				<li><a href=\"food_diary_add_recipe.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_recipes</a></li>
			</ul>
		</div>
		<div class=\"clear\" style=\"height: 20px;\"></div>
	
		<!-- Adapter view -->";
			
			$query_t = "SELECT view_id, view_user_id, view_system, view_hundred_metric, view_serving, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_food_diary_user_adapted_view WHERE view_user_id=$get_my_user_id";
			$result_t = mysqli_query($link, $query_t);
			$row_t = mysqli_fetch_row($result_t);
			list($get_current_view_id, $get_current_view_user_id, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_serving, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
			echo"
			<p><a id=\"adapter_view\"></a>
			<b>$l_show_per:</b>
			<input type=\"checkbox\" name=\"inp_show_hundred_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=hundred_metric&amp;process=1&amp;referer=food_diary_add&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l\""; if($get_current_view_hundred_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_hundred
			<input type=\"checkbox\" name=\"inp_show_serving\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=serving&amp;process=1&amp;referer=food_diary_add&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l\""; if($get_current_view_serving == "1"){ echo" checked=\"checked\""; } echo" /> $l_serving
			<input type=\"checkbox\" name=\"inp_show_pcs_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=pcs_metric&amp;process=1&amp;referer=food_diary_add&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l\""; if($get_current_view_pcs_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_pcs_g
			<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=eight_us&amp;process=1&amp;referer=food_diary_add&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l\""; if($get_current_view_eight_us == "1"){ echo" checked=\"checked\""; } echo" /> $l_eight
			<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=pcs_us&amp;process=1&amp;referer=food_diary_add&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l\""; if($get_current_view_pcs_us == "1"){ echo" checked=\"checked\""; } echo" /> $l_pcs_oz
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



		<!-- Search results -->
			<div id=\"nettport_search_results\">
				";
				// Set layout
				$x = 0;
				$count_last_used = 0;
					
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

				<!-- Last used meals x 8 -->
					";
					// Last used meals
					$query = "SELECT last_used_id, last_used_user_id, last_used_hour_name, last_used_food_id, last_used_recipe_id, last_used_meal_id, last_used_times, last_used_updated_datetime, last_used_name, last_used_manufacturer, last_used_image_path, last_used_image_thumb_132x132, last_used_main_category_id, last_used_sub_category_id, last_used_metric_or_us, last_used_selected_serving_size, last_used_selected_measurement, last_used_serving_size_metric, last_used_serving_size_measurement_metric, last_used_serving_size_us, last_used_serving_size_measurement_us, last_used_serving_size_pcs, last_used_serving_size_pcs_measurement, last_used_energy_metric, last_used_fat_metric, last_used_saturated_fat_metric, last_used_monounsaturated_fat_metric, last_used_polyunsaturated_fat_metric, last_used_cholesterol_metric, last_used_carbohydrates_metric, last_used_carbohydrates_of_which_sugars_metric, last_used_dietary_fiber_metric, last_used_proteins_metric, last_used_salt_metric, last_used_sodium_metric, last_used_energy_us, last_used_fat_us, last_used_saturated_fat_us, last_used_monounsaturated_fat_us, last_used_polyunsaturated_fat_us, last_used_cholesterol_us, last_used_carbohydrates_us, last_used_carbohydrates_of_which_sugars_us, last_used_dietary_fiber_us, last_used_proteins_us, last_used_salt_us, last_used_sodium_us, last_used_energy_serving, last_used_fat_serving, last_used_saturated_fat_serving, last_used_monounsaturated_fat_serving, last_used_polyunsaturated_fat_serving, last_used_cholesterol_serving, last_used_carbohydrates_serving, last_used_carbohydrates_of_which_sugars_serving, last_used_dietary_fiber_serving, last_used_proteins_serving, last_used_salt_serving, last_used_sodium_serving FROM $t_food_diary_last_used WHERE last_used_user_id='$get_my_user_id' AND last_used_hour_name=$hour_name_mysql AND last_used_food_id=0 AND last_used_recipe_id=0 ORDER BY last_used_updated_datetime DESC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_last_used_id, $get_last_used_user_id, $get_last_used_hour_name, $get_last_used_food_id, $get_last_used_recipe_id, $get_last_used_meal_id, $get_last_used_times, $get_last_used_updated_datetime, $get_last_used_name, $get_last_used_manufacturer, $get_last_used_image_path, $get_last_used_image_thumb_132x132, $get_last_used_main_category_id, $get_last_used_sub_category_id, $get_last_used_metric_or_us, $get_last_used_selected_serving_size, $get_last_used_selected_measurement, $get_last_used_serving_size_metric, $get_last_used_serving_size_measurement_metric, $get_last_used_serving_size_us, $get_last_used_serving_size_measurement_us, $get_last_used_serving_size_pcs, $get_last_used_serving_size_pcs_measurement, $get_last_used_energy_metric, $get_last_used_fat_metric, $get_last_used_saturated_fat_metric, $get_last_used_monounsaturated_fat_metric, $get_last_used_polyunsaturated_fat_metric, $get_last_used_cholesterol_metric, $get_last_used_carbohydrates_metric, $get_last_used_carbohydrates_of_which_sugars_metric, $get_last_used_dietary_fiber_metric, $get_last_used_proteins_metric, $get_last_used_salt_metric, $get_last_used_sodium_metric, $get_last_used_energy_us, $get_last_used_fat_us, $get_last_used_saturated_fat_us, $get_last_used_monounsaturated_fat_us, $get_last_used_polyunsaturated_fat_us, $get_last_used_cholesterol_us, $get_last_used_carbohydrates_us, $get_last_used_carbohydrates_of_which_sugars_us, $get_last_used_dietary_fiber_us, $get_last_used_proteins_us, $get_last_used_salt_us, $get_last_used_sodium_us, $get_last_used_energy_serving, $get_last_used_fat_serving, $get_last_used_saturated_fat_serving, $get_last_used_monounsaturated_fat_serving, $get_last_used_polyunsaturated_fat_serving, $get_last_used_cholesterol_serving, $get_last_used_carbohydrates_serving, $get_last_used_carbohydrates_of_which_sugars_serving, $get_last_used_dietary_fiber_serving, $get_last_used_proteins_serving, $get_last_used_salt_serving, $get_last_used_sodium_serving) = $row;

						// Select meal
						$query_n = "SELECT meal_id, meal_user_id, meal_hour_name, meal_name, meal_last_used_date, meal_used_times, meal_entries, meal_entries_count, meal_image_path, meal_image_file, meal_selected_serving_size, meal_selected_measurement, meal_energy_metric, meal_fat_metric, meal_saturated_fat_metric, meal_monounsaturated_fat_metric, meal_polyunsaturated_fat_metric, meal_cholesterol_metric, meal_carbohydrates_metric, meal_carbohydrates_of_which_sugars_metric, meal_dietary_fiber_metric, meal_proteins_metric, meal_salt_metric, meal_sodium_metric, meal_energy_us, meal_fat_us, meal_saturated_fat_us, meal_monounsaturated_fat_us, meal_polyunsaturated_fat_us, meal_cholesterol_us, meal_carbohydrates_us, meal_carbohydrates_of_which_sugars_us, meal_dietary_fiber_us, meal_proteins_us, meal_salt_us, meal_sodium_us, meal_energy_serving, meal_fat_serving, meal_saturated_fat_serving, meal_monounsaturated_fat_serving, meal_polyunsaturated_fat_serving, meal_cholesterol_serving, meal_carbohydrates_serving, meal_carbohydrates_of_which_sugars_serving, meal_dietary_fiber_serving, meal_proteins_serving, meal_salt_serving, meal_sodium_serving, meal_energy_total, meal_fat_total, meal_saturated_total, meal_monounsaturated_fat_total, meal_polyunsaturated_fat_total, meal_cholesterol_total, meal_carbohydrates_total, meal_carbohydrates_of_which_sugars_total, meal_dietary_fiber_total, meal_proteins_total, meal_salt_total, meal_sodium_total FROM $t_food_diary_meals_index WHERE meal_id=$get_last_used_meal_id";
						$result_n = mysqli_query($link, $query_n);
						$row_n = mysqli_fetch_row($result_n);
						list($get_meal_id, $get_meal_user_id, $get_meal_hour_name, $get_meal_name, $get_meal_last_used_date, $get_meal_used_times, $get_meal_entries, $get_meal_entries_count, $get_meal_image_path, $get_meal_image_file, $get_meal_selected_serving_size, $get_meal_selected_measurement, $get_meal_energy_metric, $get_meal_fat_metric, $get_meal_saturated_fat_metric, $get_meal_monounsaturated_fat_metric, $get_meal_polyunsaturated_fat_metric, $get_meal_cholesterol_metric, $get_meal_carbohydrates_metric, $get_meal_carbohydrates_of_which_sugars_metric, $get_meal_dietary_fiber_metric, $get_meal_proteins_metric, $get_meal_salt_metric, $get_meal_sodium_metric, $get_meal_energy_us, $get_meal_fat_us, $get_meal_saturated_fat_us, $get_meal_monounsaturated_fat_us, $get_meal_polyunsaturated_fat_us, $get_meal_cholesterol_us, $get_meal_carbohydrates_us, $get_meal_carbohydrates_of_which_sugars_us, $get_meal_dietary_fiber_us, $get_meal_proteins_us, $get_meal_salt_us, $get_meal_sodium_us, $get_meal_energy_serving, $get_meal_fat_serving, $get_meal_saturated_fat_serving, $get_meal_monounsaturated_fat_serving, $get_meal_polyunsaturated_fat_serving, $get_meal_cholesterol_serving, $get_meal_carbohydrates_serving, $get_meal_carbohydrates_of_which_sugars_serving, $get_meal_dietary_fiber_serving, $get_meal_proteins_serving, $get_meal_salt_serving, $get_meal_sodium_serving, $get_meal_energy_total, $get_meal_fat_total, $get_meal_saturated_total, $get_meal_monounsaturated_fat_total, $get_meal_polyunsaturated_fat_total, $get_meal_cholesterol_total, $get_meal_carbohydrates_total, $get_meal_carbohydrates_of_which_sugars_total, $get_meal_dietary_fiber_total, $get_meal_proteins_total, $get_meal_salt_total, $get_meal_sodium_total) = $row_n;
						if($get_meal_id == ""){
							echo"Error with IDS..";
							mysqli_query($link, "TRUNCATE $t_food_diary_last_used") or die(mysqli_error($link));
							mysqli_query($link, "TRUNCATE $t_food_diary_meals_index") or die(mysqli_error($link));
							mysqli_query($link, "TRUNCATE $t_food_diary_meals_items") or die(mysqli_error($link));
						}

						// Loop trough items in meal
						$meal_name = "";
						$meal_image = "";
						$i = 0;
						$query_i = "SELECT item_id, item_user_id, item_meal_id, item_food_id, item_recipe_id, item_name, item_name_short, item_manufacturer_name, item_main_category_id, item_sub_category_id, item_image_path, item_image_file, item_image_thumb_66x132, item_image_thumb_100x100, item_image_thumb_100x200, item_image_thumb_132x132, item_serving_size, item_serving_size_measurement, item_metric_or_us, item_selected_serving_size, item_selected_measurement, item_serving_size_metric, item_serving_size_measurement_metric, item_serving_size_us, item_serving_size_measurement_us, item_serving_size_pcs, item_serving_size_pcs_measurement, item_energy_metric, item_fat_metric, item_saturated_fat_metric, item_monounsaturated_fat_metric, item_polyunsaturated_fat_metric, item_cholesterol_metric, item_carbohydrates_metric, item_carbohydrates_of_which_sugars_metric, item_dietary_fiber_metric, item_proteins_metric, item_salt_metric, item_sodium_metric, item_energy_us, item_fat_us, item_saturated_fat_us, item_monounsaturated_fat_us, item_polyunsaturated_fat_us, item_cholesterol_us, item_carbohydrates_us, item_carbohydrates_of_which_sugars_us, item_dietary_fiber_us, item_proteins_us, item_salt_us, item_sodium_us, item_energy_serving, item_fat_serving, item_saturated_fat_serving, item_monounsaturated_fat_serving, item_polyunsaturated_fat_serving, item_cholesterol_serving, item_carbohydrates_serving, item_carbohydrates_of_which_sugars_serving, item_dietary_fiber_serving, item_proteins_serving, item_salt_serving, item_sodium_serving FROM $t_food_diary_meals_items WHERE item_meal_id=$get_meal_id";
						$result_i = mysqli_query($link, $query_i);
						while($row_i = mysqli_fetch_row($result_i)) {
							list($get_item_id, $get_item_user_id, $get_item_meal_id, $get_item_food_id, $get_item_recipe_id, $get_item_name, $get_item_name_short, $get_item_manufacturer_name, $get_item_main_category_id, $get_item_sub_category_id, $get_item_image_path, $get_item_image_file, $get_item_image_thumb_66x132, $get_item_image_thumb_100x100, $get_item_image_thumb_100x200, $get_item_image_thumb_132x132, $get_item_serving_size, $get_item_serving_size_measurement, $get_item_metric_or_us, $get_item_selected_serving_size, $get_item_selected_measurement, $get_item_serving_size_metric, $get_item_serving_size_measurement_metric, $get_item_serving_size_us, $get_item_serving_size_measurement_us, $get_item_serving_size_pcs, $get_item_serving_size_pcs_measurement, $get_item_energy_metric, $get_item_fat_metric, $get_item_saturated_fat_metric, $get_item_monounsaturated_fat_metric, $get_item_polyunsaturated_fat_metric, $get_item_cholesterol_metric, $get_item_carbohydrates_metric, $get_item_carbohydrates_of_which_sugars_metric, $get_item_dietary_fiber_metric, $get_item_proteins_metric, $get_item_salt_metric, $get_item_sodium_metric, $get_item_energy_us, $get_item_fat_us, $get_item_saturated_fat_us, $get_item_monounsaturated_fat_us, $get_item_polyunsaturated_fat_us, $get_item_cholesterol_us, $get_item_carbohydrates_us, $get_item_carbohydrates_of_which_sugars_us, $get_item_dietary_fiber_us, $get_item_proteins_us, $get_item_salt_us, $get_item_sodium_us, $get_item_energy_serving, $get_item_fat_serving, $get_item_saturated_fat_serving, $get_item_monounsaturated_fat_serving, $get_item_polyunsaturated_fat_serving, $get_item_cholesterol_serving, $get_item_carbohydrates_serving, $get_item_carbohydrates_of_which_sugars_serving, $get_item_dietary_fiber_serving, $get_item_proteins_serving, $get_item_salt_serving, $get_item_sodium_serving) = $row_i;


							// Image
							if(file_exists("$root/$get_item_image_path/$get_item_image_file") && $get_item_image_file != ""){
								if(!(file_exists("$root/$get_item_image_path/$get_item_image_thumb_66x132")) && $get_item_image_thumb_66x132 != ""){
									resize_crop_image(66, 132, "$root/$get_item_image_path/$get_item_image_file", "$root/$get_item_image_path/$get_item_image_thumb_66x132");
								}
								if(!(file_exists("$root/$get_item_image_path/$get_item_image_thumb_132x132")) && $get_item_image_thumb_132x132 != ""){
									resize_crop_image(132, 132, "$root/$get_item_image_path/$get_item_image_file", "$root/$get_item_image_path/$get_item_image_thumb_132x132");
								}
								if($get_meal_entries_count == "2"){
									// Size is 2, width= 132/2=66
									$meal_image = $meal_image . "<img src=\"$root/$get_item_image_path/$get_item_image_thumb_66x132\" alt=\"$get_item_image_thumb_66x132\" width=\"100\" height=\"132\" />";
								}
								elseif($get_meal_entries_count == "3"){
									// Size is 3
									$meal_image = $meal_image . "<img src=\"$root/$get_item_image_path/$get_item_image_thumb_66x132\" alt=\"$get_item_image_thumb_66x132\" width=\"66\" height=\"132\" />";
								}
								elseif($get_meal_entries_count == "4"){
									// Size is 4, width= 132/4=33
									$meal_image = $meal_image . "<img src=\"$root/$get_item_image_path/$get_item_image_thumb_132x132\" alt=\"$get_item_image_thumb_132x132\" width=\"100\" height=\"100\" />";
									if($i == "1"){
										$meal_image = $meal_image . "<br />\n";
									}
								}
								else{
									echo"<p style=\"color:red;\">Error with meal_entries_count: $get_meal_entries_count</p>";
								}
							}

							// Name
							if($get_item_name == ""){
								echo"<p style=\"color:red;\">Could not find item name</p>";
							}
							if($meal_name == ""){
								$meal_name = "<a href=\"$root/view_food.php?main_category_id=$get_item_main_category_id&amp;sub_category_id=$get_item_sub_category_id&amp;food_id=$get_item_food_id&amp;l=$l\">$get_item_serving_size $get_item_name</a>";
							}
							else{
								$meal_name = $meal_name . "<br />\n " . "<a href=\"$root/view_food.php?main_category_id=$get_item_main_category_id&amp;sub_category_id=$get_item_sub_category_id&amp;food_id=$get_item_food_id&amp;l=$l\">$get_item_serving_size $get_item_name</a>";
							}
			
							$i++;
						}

						echo"
						<div class=\"last_meal\">
							<div class=\"last_meal_image\">
								<p>$meal_image</p>
							</div>
							<div class=\"last_meal_txt\">
								<p>$meal_name</p>

								<!-- Meal nutrition numbers -->
								";

									if($get_current_view_hundred_metric == "1" OR $get_current_view_pcs_metric == "1" OR $get_current_view_eight_us == "1" OR $get_current_view_pcs_us == "1"){
				
										echo"
										<table style=\"margin: 0px auto;\">
										";
										if($get_current_view_pcs_metric == "1"){
											echo"
											 <tr>
											  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
												<span class=\"nutritional_number\">1 $l_pcs_lowercase</span>
											  </td>
											  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
												<span class=\"nutritional_number\">$get_meal_energy_serving</span>
											  </td>
											  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
												<span class=\"nutritional_number\">$get_meal_fat_serving</span>
											  </td>
											  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
												<span class=\"nutritional_number\">$get_meal_saturated_fat_serving</span>
											  </td>
											  <td style=\"text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
												<span class=\"nutritional_number\">$get_meal_monounsaturated_fat_serving</span>
											  </td>
											 </tr>
											";
										}
										if($get_current_view_pcs_us == "1"){
											echo"
											 <tr>
											  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
												<span class=\"nutritional_number\">1 $l_pcs_lowercase</span>
											  </td>
											  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
												<span class=\"nutritional_number\">$get_meal_energy_serving</span>
											  </td>
											  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
												<span class=\"nutritional_number\">$get_meal_fat_serving</span>
											  </td>
											  <td style=\"padding-right: 6px;text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
												<span class=\"nutritional_number\">$get_meal_saturated_fat_serving</span>
											  </td>
											  <td style=\"text-align: center;"; if($get_current_view_hundred_metric == "1"){ echo"padding-top:6px;"; } echo"\">
												<span class=\"nutritional_number\">$get_meal_monounsaturated_fat_serving</span>
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
								<!-- Meal nutrition numbers -->

								<!-- Add meal -->
									<form method=\"post\" action=\"food_diary_add_meal.php?action=add_meal_to_diary&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
									<p>
									<input type=\"hidden\" name=\"inp_entry_meal_id\" value=\"$get_meal_id\" />
							
									<input type=\"text\" name=\"inp_entry_serving_size\" size=\"2\" value=\"$get_meal_selected_serving_size\" />
									<input type=\"submit\" name=\"inp_submit_pcs\" value=\"$get_meal_selected_measurement\" class=\"btn btn_default\" />
							
									</p>
									</form>
								<!-- //Add meal -->


							</div> <!-- //div last meal txt -->
						</div>
						<div class=\"clear\"></div>
						<hr />
						";

						$count_last_used++;
					} // last used meals

					echo"
				<!-- //Last used meals x 8 -->

				<!-- Food, recipes and meals -->
				";
					// Last used food, recipes and meals
					$query = "SELECT last_used_id, last_used_user_id, last_used_hour_name, last_used_food_id, last_used_recipe_id, last_used_meal_id, last_used_times, last_used_updated_datetime, last_used_name, last_used_manufacturer, last_used_image_path, last_used_image_thumb_132x132, last_used_main_category_id, last_used_sub_category_id, last_used_metric_or_us, last_used_selected_serving_size, last_used_selected_measurement, last_used_serving_size_metric, last_used_serving_size_measurement_metric, last_used_serving_size_us, last_used_serving_size_measurement_us, last_used_serving_size_pcs, last_used_serving_size_pcs_measurement, last_used_energy_metric, last_used_fat_metric, last_used_saturated_fat_metric, last_used_monounsaturated_fat_metric, last_used_polyunsaturated_fat_metric, last_used_cholesterol_metric, last_used_carbohydrates_metric, last_used_carbohydrates_of_which_sugars_metric, last_used_dietary_fiber_metric, last_used_proteins_metric, last_used_salt_metric, last_used_sodium_metric, last_used_energy_us, last_used_fat_us, last_used_saturated_fat_us, last_used_monounsaturated_fat_us, last_used_polyunsaturated_fat_us, last_used_cholesterol_us, last_used_carbohydrates_us, last_used_carbohydrates_of_which_sugars_us, last_used_dietary_fiber_us, last_used_proteins_us, last_used_salt_us, last_used_sodium_us, last_used_energy_serving, last_used_fat_serving, last_used_saturated_fat_serving, last_used_monounsaturated_fat_serving, last_used_polyunsaturated_fat_serving, last_used_cholesterol_serving, last_used_carbohydrates_serving, last_used_carbohydrates_of_which_sugars_serving, last_used_dietary_fiber_serving, last_used_proteins_serving, last_used_salt_serving, last_used_sodium_serving FROM $t_food_diary_last_used WHERE last_used_user_id='$get_my_user_id' AND last_used_hour_name=$hour_name_mysql AND last_used_meal_id=0 ORDER BY last_used_updated_datetime DESC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_last_used_id, $get_last_used_user_id, $get_last_used_hour_name, $get_last_used_food_id, $get_last_used_recipe_id, $get_last_used_meal_id, $get_last_used_times, $get_last_used_updated_datetime, $get_last_used_name, $get_last_used_manufacturer, $get_last_used_image_path, $get_last_used_image_thumb_132x132, $get_last_used_main_category_id, $get_last_used_sub_category_id, $get_last_used_metric_or_us, $get_last_used_selected_serving_size, $get_last_used_selected_measurement, $get_last_used_serving_size_metric, $get_last_used_serving_size_measurement_metric, $get_last_used_serving_size_us, $get_last_used_serving_size_measurement_us, $get_last_used_serving_size_pcs, $get_last_used_serving_size_pcs_measurement, $get_last_used_energy_metric, $get_last_used_fat_metric, $get_last_used_saturated_fat_metric, $get_last_used_monounsaturated_fat_metric, $get_last_used_polyunsaturated_fat_metric, $get_last_used_cholesterol_metric, $get_last_used_carbohydrates_metric, $get_last_used_carbohydrates_of_which_sugars_metric, $get_last_used_dietary_fiber_metric, $get_last_used_proteins_metric, $get_last_used_salt_metric, $get_last_used_sodium_metric, $get_last_used_energy_us, $get_last_used_fat_us, $get_last_used_saturated_fat_us, $get_last_used_monounsaturated_fat_us, $get_last_used_polyunsaturated_fat_us, $get_last_used_cholesterol_us, $get_last_used_carbohydrates_us, $get_last_used_carbohydrates_of_which_sugars_us, $get_last_used_dietary_fiber_us, $get_last_used_proteins_us, $get_last_used_salt_us, $get_last_used_sodium_us, $get_last_used_energy_serving, $get_last_used_fat_serving, $get_last_used_saturated_fat_serving, $get_last_used_monounsaturated_fat_serving, $get_last_used_polyunsaturated_fat_serving, $get_last_used_cholesterol_serving, $get_last_used_carbohydrates_serving, $get_last_used_carbohydrates_of_which_sugars_serving, $get_last_used_dietary_fiber_serving, $get_last_used_proteins_serving, $get_last_used_salt_serving, $get_last_used_sodium_serving) = $row;

						// Layout
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


						if($get_last_used_food_id != "0" && $get_last_used_recipe_id == "0" && $get_last_used_meal_id == "0"){
							// Get food
							$query_f = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_text, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_numbers_entered_method, food_nutrition_facts_view_method, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_trans_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_added_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_trans_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_added_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_score_place_in_sub_category, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_trans_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_added_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_trans_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_added_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_energy_net_content, food_fat_net_content, food_saturated_fat_net_content, food_trans_fat_net_content, food_monounsaturated_fat_net_content, food_polyunsaturated_fat_net_content, food_cholesterol_net_content, food_carbohydrates_net_content, food_carbohydrates_of_which_sugars_net_content, food_added_sugars_net_content, food_dietary_fiber_net_content, food_proteins_net_content, food_salt_net_content, food_sodium_net_content, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_no_of_comments, food_stars, food_stars_sum, food_comments_multiplied_stars, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_id=$get_last_used_food_id";
							$result_f = mysqli_query($link, $query_f);
							$row_f = mysqli_fetch_row($result_f);
							list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_text, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_numbers_entered_method, $get_food_nutrition_facts_view_method, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_trans_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_added_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_trans_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_added_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_score_place_in_sub_category, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_trans_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_added_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_trans_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_added_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_energy_net_content, $get_food_fat_net_content, $get_food_saturated_fat_net_content, $get_food_trans_fat_net_content, $get_food_monounsaturated_fat_net_content, $get_food_polyunsaturated_fat_net_content, $get_food_cholesterol_net_content, $get_food_carbohydrates_net_content, $get_food_carbohydrates_of_which_sugars_net_content, $get_food_added_sugars_net_content, $get_food_dietary_fiber_net_content, $get_food_proteins_net_content, $get_food_salt_net_content, $get_food_sodium_net_content, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_no_of_comments, $get_food_stars, $get_food_stars_sum, $get_food_comments_multiplied_stars, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row_f;

							// Name saying
							$title = "$get_food_manufacturer_name $get_food_name";
							$check = strlen($title);
							if($check > 35){
								$title = substr($title, 0, 35);
								$title = $title . "...";
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
									<input type=\"text\" name=\"inp_entry_food_serving_size\" size=\"2\" value=\"$get_last_used_selected_serving_size\" />
									<input type=\"submit\" name=\"inp_submit_metric\" value=\"$get_food_serving_size_measurement_metric\" class=\"btn btn_default\" />
									";
								}
								else{
									echo"
									<input type=\"text\" name=\"inp_entry_food_serving_size\" size=\"2\" value=\"$get_last_used_selected_serving_size\" />
									<input type=\"submit\" name=\"inp_submit_metric\" value=\"$get_food_serving_size_measurement_metric\" class=\"btn btn_default\" />
									<input type=\"submit\" name=\"inp_submit_pcs\" value=\"$get_food_serving_size_pcs_measurement\" class=\"btn btn_default\" />
									";
								}
							} // metric
							if($get_current_view_eight_us == "1" OR $get_current_view_pcs_us == "1"){
								echo"
								<input type=\"text\" name=\"inp_entry_food_serving_size\" size=\"2\" value=\"$get_last_used_selected_serving_size\" />
								<input type=\"submit\" name=\"inp_submit_us\" value=\"$get_food_serving_size_measurement_metric\" class=\"btn btn_default\" />
								<input type=\"submit\" name=\"inp_submit_pcs\" value=\"$get_food_serving_size_pcs_measurement\" class=\"btn btn_default\" />
								";
							} // us
							echo"
							</p>
							</form>
						<!-- //Add food -->";
					} // food
					elseif($get_last_used_food_id == "0" && $get_last_used_recipe_id != "0" && $get_last_used_meal_id == "0"){
						// Select Recipe
						$query_n = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_country, recipe_introduction, recipe_directions, recipe_image_path, recipe_image, recipe_thumb_278x156, recipe_video, recipe_date, recipe_date_saying, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_times_favorited, recipe_user_ip, recipe_notes, recipe_password, recipe_last_viewed, recipe_age_restriction, recipe_published FROM $t_recipes WHERE recipe_id=$get_last_used_recipe_id";
						$result_n = mysqli_query($link, $query_n);
						$row_n = mysqli_fetch_row($result_n);
						list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_country, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb_278x156, $get_recipe_video, $get_recipe_date, $get_recipe_date_saying, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_times_favorited, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password, $get_recipe_last_viewed, $get_recipe_age_restriction, $get_recipe_published) = $row_n;

						// Select Nutrients
						$query_n = "SELECT number_id, number_recipe_id, number_servings, number_energy_metric, number_fat_metric, number_saturated_fat_metric, number_monounsaturated_fat_metric, number_polyunsaturated_fat_metric, number_cholesterol_metric, number_carbohydrates_metric, number_carbohydrates_of_which_sugars_metric, number_dietary_fiber_metric, number_proteins_metric, number_salt_metric, number_sodium_metric, number_energy_serving, number_fat_serving, number_saturated_fat_serving, number_monounsaturated_fat_serving, number_polyunsaturated_fat_serving, number_cholesterol_serving, number_carbohydrates_serving, number_carbohydrates_of_which_sugars_serving, number_dietary_fiber_serving, number_proteins_serving, number_salt_serving, number_sodium_serving, number_energy_total, number_fat_total, number_saturated_fat_total, number_monounsaturated_fat_total, number_polyunsaturated_fat_total, number_cholesterol_total, number_carbohydrates_total, number_carbohydrates_of_which_sugars_total, number_dietary_fiber_total, number_proteins_total, number_salt_total, number_sodium_total FROM $t_recipes_numbers WHERE number_recipe_id=$get_last_used_recipe_id";
						$result_n = mysqli_query($link, $query_n);
						$row_n = mysqli_fetch_row($result_n);
						list($get_number_id, $get_number_recipe_id, $get_number_servings, $get_number_energy_metric, $get_number_fat_metric, $get_number_saturated_fat_metric, $get_number_monounsaturated_fat_metric, $get_number_polyunsaturated_fat_metric, $get_number_cholesterol_metric, $get_number_carbohydrates_metric, $get_number_carbohydrates_of_which_sugars_metric, $get_number_dietary_fiber_metric, $get_number_proteins_metric, $get_number_salt_metric, $get_number_sodium_metric, $get_number_energy_serving, $get_number_fat_serving, $get_number_saturated_fat_serving, $get_number_monounsaturated_fat_serving, $get_number_polyunsaturated_fat_serving, $get_number_cholesterol_serving, $get_number_carbohydrates_serving, $get_number_carbohydrates_of_which_sugars_serving, $get_number_dietary_fiber_serving, $get_number_proteins_serving, $get_number_salt_serving, $get_number_sodium_serving, $get_number_energy_total, $get_number_fat_total, $get_number_saturated_fat_total, $get_number_monounsaturated_fat_total, $get_number_polyunsaturated_fat_total, $get_number_cholesterol_total, $get_number_carbohydrates_total, $get_number_carbohydrates_of_which_sugars_total, $get_number_dietary_fiber_total, $get_number_proteins_total, $get_number_salt_total, $get_number_sodium_total) = $row_n;

						// Thumb
						if(!(file_exists("$root/$get_recipe_image_path/$get_recipe_thumb_278x156"))){
							if($get_recipe_thumb_278x156 == ""){
								echo"<div class=\"info\">Thumb 278x156 is blank</div>";
								die;
							}
							$inp_new_x = 278;
							$inp_new_y = 156;
							resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_recipe_image_path/$get_recipe_image", "$root/$get_recipe_image_path/$get_recipe_thumb_278x156");
						}


						echo"
						<p style=\"padding-bottom:5px;\">
						<a href=\"$root/recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\"><img src=\"$root/$get_recipe_image_path/$get_recipe_thumb_278x156\" alt=\"$get_recipe_image\" style=\"margin-bottom: 5px;\" /></a><br />
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
						";


						} // recipe


						// Layout end
						echo"
							</div>
						";
						// Increment
						$x++;
						$count_last_used++;
		
						// Reset
						if($x == 4){
							$x = 0;
						}
				
					} // while recent food, recipes, meals
					if($x == "2"){
						echo"
						<div class=\"left_center_center_right_right_center\" style=\"text-align: center;padding-bottom: 20px;\">
						</div>
						<div class=\"left_center_center_right_right\" style=\"text-align: center;padding-bottom: 20px;\">
						</div>
						";
					}

					echo"
				<!-- //Food, recipes and meals -->
			</div> <!-- //nettport_search_results -->
		<!-- //Search results -->
		<div class=\"clear\"></div>
		
		";
		if($count_last_used  == "0"){
			// No food
			echo"
			<p>$l_on_this_page_you_will_see_food_you_have_added_before</p>

				<p>
				$l_the_page_is_smart_so_it_will_remember_what_you_usually_have_for_your ";
				if($hour_name == "breakfast"){
					echo"$l_breakfast_lowercase";
				}
				elseif($hour_name == "lunch"){
					echo"$l_lunch_lowercase";
				}
				elseif($hour_name == "before_training"){
					echo"$l_before_training_lowercase";
				}
				elseif($hour_name == "after_training"){
					echo"$l_after_training_lowercase";
				}
				elseif($hour_name == "dinner"){
					echo"$l_dinner_lowercase";
				}
				elseif($hour_name == "snacks"){
					echo"$l_snacks_lowercase";
				}
				else{
					echo"$l_supper";
				}
				echo"
				$l_on_lowercase
				";
				$dow = date("N",strtotime($date));
				
				if($dow == "1"){
					echo"$l_mondays_lowercase";
				}
				elseif($dow == "2"){
					echo"$l_tuesdays_lowercase";
				}
				elseif($dow == "3"){
					echo"$l_wednesdays_lowercase";
				}
				elseif($dow == "4"){
					echo"$l_thursdays_lowercase";
				}
				elseif($dow == "5"){
					echo"$l_fridays_lowercase";
				}
				elseif($dow == "6"){
					echo"$l_saturdays_lowercase";
				}
				else{
					echo"$l_sundays_lowercase";
				}
				echo".
				</p>

				<p>$l_the_more_you_use_the_food_diary_the_smarter_it_gets </p>
				";
		}
		echo"
		";
	} // action == ""
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