<?php
/**
*
* File: food_diary/food_diary_add_recipe.php
* Version 2
* Date 20:30 16.03.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
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
			<a href=\"food_diary_add.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_new_entry</a>
			&gt;
			<a href=\"food_diary_add_recipe.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_recipes</a>
			</p>
		<!-- //You are here -->

		
		<!-- Recipe search -->
				
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
					\$('[name=\"inp_entry_recipe_query\"]').keyup(function () {


        					// getting the value that user typed
        					var searchString    = $(\"#inp_entry_recipe_query\").val();
        					// forming the queryString
       						var data            = 'l=$l&date=$date&hour_name=$hour_name&q='+ searchString;
         
        					// if searchString is not empty
        					if(searchString) {
        						// ajax call
          						\$.ajax({
                						type: \"POST\",
               							url: \"food_diary_add_recipe_query.php\",
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
				<form method=\"get\" action=\"food_diary_add_recipe.php\" enctype=\"multipart/form-data\" id=\"inp_entry_food_query_form\">
					<p style=\"padding-top:0;\"><b>$l_recipe_search</b><br />
					<input type=\"text\" name=\"inp_entry_recipe_query\" id=\"inp_entry_recipe_query\" value=\"";if(isset($_GET['inp_entry_recipe_query'])){ echo"$inp_entry_recipe_query"; } echo"\" size=\"5\" />
					<input type=\"hidden\" name=\"action\" value=\"recipe_search\" />
					<input type=\"hidden\" name=\"date\" value=\"$date\" />
					<input type=\"hidden\" name=\"hour_name\" value=\"$hour_name\" />
					<input type=\"submit\" value=\"$l_search\" class=\"btn_default\" />
					<a href=\"$root/recipes/submit_recipe.php?l=$l\" class=\"btn_default\" title=\"$l_new_recipe\">$l_new_recipe</a>
					</p>
				</form>
			<!-- //Food Search -->
		<!-- //Recipe search -->

		<!-- Menu -->
			<div class=\"tabs\">
				<ul>
					<li><a href=\"food_diary_add.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_recent</a></li>
					<li><a href=\"food_diary_add_food.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_food</a></li>
					<li><a href=\"food_diary_add_recipe.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\" class=\"selected\">$l_recipes</a></li>
				</ul>
			</div>
			<div class=\"clear\" style=\"height: 20px;\"></div>
		<!-- //Menu -->
		
		<!-- Recipes categories -->
			<div class=\"vertical\">
				<ul>\n";
					
				// Get all categories
				$query = "SELECT category_id, category_name FROM $t_recipes_categories ORDER BY category_name ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_category_id, $get_category_name) = $row;
				
					// Translations
					$query_t = "SELECT category_translation_id, category_translation_value FROM $t_recipes_categories_translations WHERE category_id=$get_category_id AND category_translation_language=$l_mysql";
					$result_t = mysqli_query($link, $query_t);
					$row_t = mysqli_fetch_row($result_t);
					list($get_category_translation_id, $get_category_translation_value) = $row_t;

					echo"		";
					echo"<li><a href=\"food_diary_add_recipe.php?action=open_recipe_category&amp;date=$date&amp;hour_name=$hour_name&amp;recipe_category_id=$get_category_id&amp;l=$l\""; if($recipe_category_id == "$get_category_id"){ echo" style=\"font-weight: bold;\"";}echo">$get_category_translation_value</a></li>\n";
				}
				echo"
		
				</ul>
			</div>
		<!-- //Recipes Categories -->


		<!-- Adapter view -->";
			
			$query_t = "SELECT view_id, view_user_id, view_system, view_hundred_metric, view_serving, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_food_diary_user_adapted_view WHERE view_user_id=$get_my_user_id";
			$result_t = mysqli_query($link, $query_t);
			$row_t = mysqli_fetch_row($result_t);
			list($get_current_view_id, $get_current_view_user_id, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_serving, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
			echo"
			<p><a id=\"adapter_view\"></a>
			<b>$l_show_per:</b>
			<input type=\"checkbox\" name=\"inp_show_hundred_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=hundred_metric&amp;process=1&amp;referer=food_diary_add_recipe&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l\""; if($get_current_view_hundred_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_hundred
			<input type=\"checkbox\" name=\"inp_show_serving\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=serving&amp;process=1&amp;referer=food_diary_add_recipe&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l\""; if($get_current_view_serving == "1"){ echo" checked=\"checked\""; } echo" /> $l_serving
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

		<!-- All recipes list -->
			<!-- Select list go to URL -->
				<script>
				\$(document).ready(function(){
					\$(\"select\").bind('change',function(){
						window.location = \$(':selected',this).attr('href'); // redirect
					})
				});
				</script>
			<!-- //Select list go to URL -->


			<div id=\"nettport_search_results\">
				";
				// Set layout
				$x = 0;

				// Get all recipes
				$query = "SELECT recipe_id, recipe_title, recipe_category_id, recipe_introduction, recipe_image_path, recipe_image, recipe_thumb_278x156 FROM $t_recipes WHERE recipe_language=$l_mysql";

				if(isset($_GET['inp_entry_recipe_query'])){
					$inp_entry_recipe_query = $_GET['inp_entry_recipe_query'];
					$inp_entry_recipe_query = strip_tags(stripslashes($inp_entry_recipe_query));
					$inp_entry_recipe_query = output_html($inp_entry_recipe_query);
		
					$inp_entry_recipe_query = "%" . $inp_entry_recipe_query . "%";
					$inp_entry_recipe_query_mysql = quote_smart($link, $inp_entry_recipe_query);
					$query = $query . " AND recipe_title LIKE $inp_entry_recipe_query_mysql";
				}
				if($recipe_category_id != ""){
					$recipe_category_id_mysql = quote_smart($link, $recipe_category_id);
					$query = $query . " AND recipe_category_id=$recipe_category_id_mysql";
				}
				$query = $query . " ORDER BY recipe_last_viewed ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_recipe_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_introduction, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb_278x156) = $row;
		
					// Select Nutrients
					$query_n = "SELECT number_id, number_recipe_id, number_servings, number_energy_metric, number_fat_metric, number_saturated_fat_metric, number_monounsaturated_fat_metric, number_polyunsaturated_fat_metric, number_cholesterol_metric, number_carbohydrates_metric, number_carbohydrates_of_which_sugars_metric, number_dietary_fiber_metric, number_proteins_metric, number_salt_metric, number_sodium_metric, number_energy_serving, number_fat_serving, number_saturated_fat_serving, number_monounsaturated_fat_serving, number_polyunsaturated_fat_serving, number_cholesterol_serving, number_carbohydrates_serving, number_carbohydrates_of_which_sugars_serving, number_dietary_fiber_serving, number_proteins_serving, number_salt_serving, number_sodium_serving, number_energy_total, number_fat_total, number_saturated_fat_total, number_monounsaturated_fat_total, number_polyunsaturated_fat_total, number_cholesterol_total, number_carbohydrates_total, number_carbohydrates_of_which_sugars_total, number_dietary_fiber_total, number_proteins_total, number_salt_total, number_sodium_total FROM $t_recipes_numbers WHERE number_recipe_id=$get_recipe_id";
					$result_n = mysqli_query($link, $query_n);
					$row_n = mysqli_fetch_row($result_n);
					list($get_number_id, $get_number_recipe_id, $get_number_servings, $get_number_energy_metric, $get_number_fat_metric, $get_number_saturated_fat_metric, $get_number_monounsaturated_fat_metric, $get_number_polyunsaturated_fat_metric, $get_number_cholesterol_metric, $get_number_carbohydrates_metric, $get_number_carbohydrates_of_which_sugars_metric, $get_number_dietary_fiber_metric, $get_number_proteins_metric, $get_number_salt_metric, $get_number_sodium_metric, $get_number_energy_serving, $get_number_fat_serving, $get_number_saturated_fat_serving, $get_number_monounsaturated_fat_serving, $get_number_polyunsaturated_fat_serving, $get_number_cholesterol_serving, $get_number_carbohydrates_serving, $get_number_carbohydrates_of_which_sugars_serving, $get_number_dietary_fiber_serving, $get_number_proteins_serving, $get_number_salt_serving, $get_number_sodium_serving, $get_number_energy_total, $get_number_fat_total, $get_number_saturated_fat_total, $get_number_monounsaturated_fat_total, $get_number_polyunsaturated_fat_total, $get_number_cholesterol_total, $get_number_carbohydrates_total, $get_number_carbohydrates_of_which_sugars_total, $get_number_dietary_fiber_total, $get_number_proteins_total, $get_number_salt_total, $get_number_sodium_total) = $row_n;


					if($get_recipe_image != "" && file_exists("$root/$get_recipe_image_path/$get_recipe_image")){

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
				echo"
			</div> <!-- //nettport_search_results -->
			<div class=\"clear\"></div>
					
		<!-- //All recipes list -->
		";
	} // action == ""
	elseif($action == "recipe_search"){
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
			<a href=\"food_diary_add.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_new_entry</a>
			&gt;
			<a href=\"food_diary_add_recipe.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_recipes</a>
			&gt;
			<a href=\"food_diary_add_recipe.php?actino=$action&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l&amp;inp_entry_recipe_query=$inp_entry_recipe_query\">$inp_entry_recipe_query</a>
			</p>
		<!-- //You are here -->

		
		<!-- Recipe search -->
				
			<!-- Search engines Autocomplete -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_entry_recipe_query\"]').focus();
				});
				\$(document).ready(function () {
					\$('[name=\"inp_entry_recipe_query\"]').keyup(function () {


        					// getting the value that user typed
        					var searchString    = $(\"#inp_entry_recipe_query\").val();
        					// forming the queryString
       						var data            = 'l=$l&date=$date&mhour_name=$hour_name&q='+ searchString;
         
        					// if searchString is not empty
        					if(searchString) {
        						// ajax call
          						\$.ajax({
                						type: \"POST\",
               							url: \"food_diary_add_recipe_query.php\",
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
				<form method=\"get\" action=\"food_diary_add_recipe.php\" enctype=\"multipart/form-data\" id=\"inp_entry_food_query_form\">
					<p style=\"padding-top:0;\"><b>$l_recipe_search</b><br />
					<input type=\"text\" name=\"inp_entry_recipe_query\" id=\"inp_entry_recipe_query\" value=\"";if(isset($_GET['inp_entry_recipe_query'])){ echo"$inp_entry_recipe_query"; } echo"\" size=\"15\" />
					<input type=\"hidden\" name=\"action\" value=\"recipe_search\" />
					<input type=\"hidden\" name=\"date\" value=\"$date\" />
					<input type=\"hidden\" name=\"hour_name\" value=\"$hour_name\" />
					<input type=\"submit\" value=\"$l_search\" class=\"btn btn_default\" />
					<a href=\"$root/recipes/submit_recipe.php?l=$l\" class=\"btn btn_default\">$l_new_recipe</a>
					</p>
				</form>
			<!-- //Food Search -->
		<!-- //Recipe search -->

		<!-- Menu -->
			<div class=\"tabs\">
				<ul>
					<li><a href=\"food_diary_add.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_recent</a></li>
					<li><a href=\"food_diary_add_food.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_food</a></li>
					<li><a href=\"food_diary_add_recipe.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\" class=\"selected\">$l_recipes</a></li>
				</ul>
			</div>
			<div class=\"clear\" style=\"height: 20px;\"></div>
		<!-- //Menu -->

		<!-- Adapter view -->";
			
			$query_t = "SELECT view_id, view_user_id, view_system, view_hundred_metric, view_serving, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_food_diary_user_adapted_view WHERE view_user_id=$get_my_user_id";
			$result_t = mysqli_query($link, $query_t);
			$row_t = mysqli_fetch_row($result_t);
			list($get_current_view_id, $get_current_view_user_id, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_serving, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
			echo"
			<p><a id=\"adapter_view\"></a>
			<b>$l_show_per:</b>
			<input type=\"checkbox\" name=\"inp_show_hundred_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=hundred_metric&amp;process=1&amp;referer=food_diary_add_recipe&amp;inp_entry_recipe_query=$inp_entry_recipe_query&amp;action=$action&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l\""; if($get_current_view_hundred_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_hundred
			<input type=\"checkbox\" name=\"inp_show_serving\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=serving&amp;process=1&amp;referer=food_diary_add_recipe&amp;inp_entry_recipe_query=$inp_entry_recipe_query&amp;action=$action&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l\""; if($get_current_view_serving == "1"){ echo" checked=\"checked\""; } echo" /> $l_serving
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


		<!-- Search for that recipe -->
			<!-- Select list go to URL -->
				<script>
				\$(document).ready(function(){
					\$(\"select\").bind('change',function(){
						window.location = \$(':selected',this).attr('href'); // redirect
					})
				});
				</script>
			<!-- //Select list go to URL -->


			<div id=\"nettport_search_results\">
				";
				// Set layout
				$x = 0;

				// Get all recipes
				$query = "SELECT recipe_id, recipe_title, recipe_introduction, recipe_image_path, recipe_image, recipe_thumb_278x156 FROM $t_recipes WHERE recipe_language=$l_mysql";

				if(isset($_GET['inp_entry_recipe_query'])){
					$inp_entry_recipe_query = $_GET['inp_entry_recipe_query'];
					$inp_entry_recipe_query = strip_tags(stripslashes($inp_entry_recipe_query));
					$inp_entry_recipe_query = output_html($inp_entry_recipe_query);
		
					$inp_entry_recipe_query = "%" . $inp_entry_recipe_query . "%";
					$inp_entry_recipe_query_mysql = quote_smart($link, $inp_entry_recipe_query);
					$query = $query . " AND recipe_title LIKE $inp_entry_recipe_query_mysql";
				}
				if($recipe_category_id != ""){
					$recipe_category_id_mysql = quote_smart($link, $recipe_category_id);
					$query = $query . " AND recipe_category_id=$recipe_category_id_mysql";
				}
				$query = $query . " ORDER BY recipe_last_viewed ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_recipe_id, $get_recipe_title, $get_recipe_introduction, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb_278x156) = $row;
		
					$query_n = "SELECT number_id, number_recipe_id, number_servings, number_energy_metric, number_fat_metric, number_saturated_fat_metric, number_monounsaturated_fat_metric, number_polyunsaturated_fat_metric, number_cholesterol_metric, number_carbohydrates_metric, number_carbohydrates_of_which_sugars_metric, number_dietary_fiber_metric, number_proteins_metric, number_salt_metric, number_sodium_metric, number_energy_serving, number_fat_serving, number_saturated_fat_serving, number_monounsaturated_fat_serving, number_polyunsaturated_fat_serving, number_cholesterol_serving, number_carbohydrates_serving, number_carbohydrates_of_which_sugars_serving, number_dietary_fiber_serving, number_proteins_serving, number_salt_serving, number_sodium_serving, number_energy_total, number_fat_total, number_saturated_fat_total, number_monounsaturated_fat_total, number_polyunsaturated_fat_total, number_cholesterol_total, number_carbohydrates_total, number_carbohydrates_of_which_sugars_total, number_dietary_fiber_total, number_proteins_total, number_salt_total, number_sodium_total FROM $t_recipes_numbers WHERE number_recipe_id=$get_recipe_id";
					$result_n = mysqli_query($link, $query_n);
					$row_n = mysqli_fetch_row($result_n);
					list($get_number_id, $get_number_recipe_id, $get_number_servings, $get_number_energy_metric, $get_number_fat_metric, $get_number_saturated_fat_metric, $get_number_monounsaturated_fat_metric, $get_number_polyunsaturated_fat_metric, $get_number_cholesterol_metric, $get_number_carbohydrates_metric, $get_number_carbohydrates_of_which_sugars_metric, $get_number_dietary_fiber_metric, $get_number_proteins_metric, $get_number_salt_metric, $get_number_sodium_metric, $get_number_energy_serving, $get_number_fat_serving, $get_number_saturated_fat_serving, $get_number_monounsaturated_fat_serving, $get_number_polyunsaturated_fat_serving, $get_number_cholesterol_serving, $get_number_carbohydrates_serving, $get_number_carbohydrates_of_which_sugars_serving, $get_number_dietary_fiber_serving, $get_number_proteins_serving, $get_number_salt_serving, $get_number_sodium_serving, $get_number_energy_total, $get_number_fat_total, $get_number_saturated_fat_total, $get_number_monounsaturated_fat_total, $get_number_polyunsaturated_fat_total, $get_number_cholesterol_total, $get_number_carbohydrates_total, $get_number_carbohydrates_of_which_sugars_total, $get_number_dietary_fiber_total, $get_number_proteins_total, $get_number_salt_total, $get_number_sodium_total) = $row_n;


					if($get_recipe_image != "" && file_exists("$root/$get_recipe_image_path/$get_recipe_image")){

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
				if($x == "1"){
					echo"
							<div class=\"left_center_center_left_right_center\" style=\"text-align: center;padding-bottom: 20px;\">
							</div>
							<div class=\"left_center_center_right_right_center\" style=\"text-align: center;padding-bottom: 20px;\">
							</div>
							<div class=\"left_center_center_right_right\" style=\"text-align: center;padding-bottom: 20px;\">
							</div>
							<div class=\"clear\">
							</div>
					";
				}
				echo"
			</div> <!-- //nettport_search_results -->
			
					
		<!-- //Search for that recipe -->
		";
	} // recipe_search
	elseif($action == "open_recipe_category"){
		// Find category
		$recipe_category_id_mysql = quote_smart($link, $recipe_category_id);
		$query = "SELECT category_id, category_name, category_age_restriction FROM $t_recipes_categories WHERE category_id=$recipe_category_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_category_id, $get_category_name, $get_category_age_restriction) = $row;

		if($get_category_id == ""){
			echo"Server error 404";
		}
		else{
			// Get translation
			$query = "SELECT category_translation_id, category_translation_value FROM $t_recipes_categories_translations WHERE category_id=$get_category_id AND category_translation_language=$l_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_category_translation_id, $get_category_translation_value) = $row;
		
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
				<a href=\"food_diary_add.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_new_entry</a>
				&gt;
				<a href=\"food_diary_add_recipe.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_recipes</a>
				&gt;
				<a href=\"food_diary_add_recipe.php?action=$action&amp;date=$date&amp;hour_name=$hour_name&amp;recipe_category_id=$recipe_category_id&amp;l=$l\">$get_category_translation_value</a>
				</p>
			<!-- //You are here -->

		
			<!-- Recipe search -->
				
			<!-- Search engines Autocomplete -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_entry_recipe_query\"]').focus();
				});
				\$(document).ready(function () {
					\$('[name=\"inp_entry_recipe_query\"]').keyup(function () {


        					// getting the value that user typed
        					var searchString    = $(\"#inp_entry_recipe_query\").val();
        					// forming the queryString
       						var data            = 'l=$l&date=$date&hour_name=$hour_name&q='+ searchString;
         
        					// if searchString is not empty
        					if(searchString) {
        						// ajax call
          						\$.ajax({
                						type: \"POST\",
               							url: \"food_diary_add_recipe_query.php\",
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
				<form method=\"get\" action=\"food_diary_add_recipe.php\" enctype=\"multipart/form-data\" id=\"inp_entry_food_query_form\">
					<p style=\"padding-top:0;\"><b>$l_recipe_search</b><br />
					<input type=\"text\" name=\"inp_entry_recipe_query\" id=\"inp_entry_recipe_query\" value=\"";if(isset($_GET['inp_entry_recipe_query'])){ echo"$inp_entry_recipe_query"; } echo"\" size=\"15\" />
					<input type=\"hidden\" name=\"action\" value=\"recipe_search\" />
					<input type=\"hidden\" name=\"date\" value=\"$date\" />
					<input type=\"hidden\" name=\"hour_name\" value=\"$hour_name\" />
					<input type=\"submit\" value=\"$l_search\" class=\"btn btn_default\" />
					<a href=\"$root/recipes/submit_recipe.php?l=$l\" class=\"btn btn_default\">$l_new_recipe</a>
					</p>
				</form>
			<!-- //Food Search -->
			<!-- //Recipe search -->

			<!-- Menu -->
			<div class=\"tabs\">
				<ul>
					<li><a href=\"food_diary_add.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_recent</a></li>
					<li><a href=\"food_diary_add_food.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\">$l_food</a></li>
					<li><a href=\"food_diary_add_recipe.php?date=$date&amp;hour_name=$hour_name&amp;l=$l\" class=\"selected\">$l_recipes</a></li>
				</ul>
			</div>
			<div class=\"clear\" style=\"height: 20px;\"></div>
			<!-- //Menu -->
		
			
			<!-- Adapter view -->";
			
				$query_t = "SELECT view_id, view_user_id, view_system, view_hundred_metric, view_serving, view_pcs_metric, view_eight_us, view_pcs_us FROM $t_food_diary_user_adapted_view WHERE view_user_id=$get_my_user_id";
				$result_t = mysqli_query($link, $query_t);
				$row_t = mysqli_fetch_row($result_t);
				list($get_current_view_id, $get_current_view_user_id, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_serving, $get_current_view_pcs_metric, $get_current_view_eight_us, $get_current_view_pcs_us) = $row_t;
				echo"
				<p><a id=\"adapter_view\"></a>
				<b>$l_show_per:</b>
				<input type=\"checkbox\" name=\"inp_show_hundred_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=hundred_metric&amp;process=1&amp;referer=food_diary_add_recipe&amp;action=$action&amp;recipe_category_id=$recipe_category_id&amp;date=$date&amp;hour_name=$hour_name&amp;l=$l\""; if($get_current_view_hundred_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_hundred
				<input type=\"checkbox\" name=\"inp_show_serving\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=serving&amp;process=1&amp;referer=food_diary_add_recipe&amp;date=$date&amp;action=$action&amp;recipe_category_id=$recipe_category_id&amp;hour_name=$hour_name&amp;l=$l\""; if($get_current_view_serving == "1"){ echo" checked=\"checked\""; } echo" /> $l_serving
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

			<!-- Recipes in category list -->
			<!-- Select list go to URL -->
				<script>
				\$(document).ready(function(){
					\$(\"select\").bind('change',function(){
						window.location = \$(':selected',this).attr('href'); // redirect
					})
				});
				</script>
			<!-- //Select list go to URL -->


			<div id=\"nettport_search_results\">
				";
				// Set layout
				$x = 0;

				// Get all recipes
				$query = "SELECT recipe_id, recipe_title, recipe_category_id, recipe_introduction, recipe_image_path, recipe_image, recipe_thumb_278x156 FROM $t_recipes WHERE recipe_language=$l_mysql";

				if($recipe_category_id != ""){
					$recipe_category_id_mysql = quote_smart($link, $recipe_category_id);
					$query = $query . " AND recipe_category_id=$recipe_category_id_mysql";
				}
				$query = $query . " ORDER BY recipe_last_viewed ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_recipe_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_introduction, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb_278x156) = $row;
		
					// Select Nutrients
					$query_n = "SELECT number_id, number_recipe_id, number_servings, number_energy_metric, number_fat_metric, number_saturated_fat_metric, number_monounsaturated_fat_metric, number_polyunsaturated_fat_metric, number_cholesterol_metric, number_carbohydrates_metric, number_carbohydrates_of_which_sugars_metric, number_dietary_fiber_metric, number_proteins_metric, number_salt_metric, number_sodium_metric, number_energy_serving, number_fat_serving, number_saturated_fat_serving, number_monounsaturated_fat_serving, number_polyunsaturated_fat_serving, number_cholesterol_serving, number_carbohydrates_serving, number_carbohydrates_of_which_sugars_serving, number_dietary_fiber_serving, number_proteins_serving, number_salt_serving, number_sodium_serving, number_energy_total, number_fat_total, number_saturated_fat_total, number_monounsaturated_fat_total, number_polyunsaturated_fat_total, number_cholesterol_total, number_carbohydrates_total, number_carbohydrates_of_which_sugars_total, number_dietary_fiber_total, number_proteins_total, number_salt_total, number_sodium_total FROM $t_recipes_numbers WHERE number_recipe_id=$get_recipe_id";
					$result_n = mysqli_query($link, $query_n);
					$row_n = mysqli_fetch_row($result_n);
					list($get_number_id, $get_number_recipe_id, $get_number_servings, $get_number_energy_metric, $get_number_fat_metric, $get_number_saturated_fat_metric, $get_number_monounsaturated_fat_metric, $get_number_polyunsaturated_fat_metric, $get_number_cholesterol_metric, $get_number_carbohydrates_metric, $get_number_carbohydrates_of_which_sugars_metric, $get_number_dietary_fiber_metric, $get_number_proteins_metric, $get_number_salt_metric, $get_number_sodium_metric, $get_number_energy_serving, $get_number_fat_serving, $get_number_saturated_fat_serving, $get_number_monounsaturated_fat_serving, $get_number_polyunsaturated_fat_serving, $get_number_cholesterol_serving, $get_number_carbohydrates_serving, $get_number_carbohydrates_of_which_sugars_serving, $get_number_dietary_fiber_serving, $get_number_proteins_serving, $get_number_salt_serving, $get_number_sodium_serving, $get_number_energy_total, $get_number_fat_total, $get_number_saturated_fat_total, $get_number_monounsaturated_fat_total, $get_number_polyunsaturated_fat_total, $get_number_cholesterol_total, $get_number_carbohydrates_total, $get_number_carbohydrates_of_which_sugars_total, $get_number_dietary_fiber_total, $get_number_proteins_total, $get_number_salt_total, $get_number_sodium_total) = $row_n;


					if($get_recipe_image != "" && file_exists("$root/$get_recipe_image_path/$get_recipe_image")){

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
				echo"
			</div> <!-- //nettport_search_results -->
			<div class=\"clear\"></div>
					
			<!-- //Recipes in category list -->
			";
		} // Category found
	} // open_recipe_category
	elseif($action == "add_recipe_to_diary"){
		if($process == 1){
			$inp_updated = date("Y-m-d H:i:s");
			$inp_updated_mysql = quote_smart($link, $inp_updated);

			$inp_entry_date = output_html($date);
			$inp_entry_date_mysql = quote_smart($link, $inp_entry_date);

			$inp_entry_date_saying = date("j M Y");
			$inp_entry_date_saying_mysql = quote_smart($link, $inp_entry_date_saying);

			$inp_entry_hour_name = output_html($hour_name);
			$inp_entry_hour_name_mysql = quote_smart($link, $inp_entry_hour_name);

			$inp_entry_recipe_id = $_GET['recipe_id'];
			$inp_entry_recipe_id = output_html($inp_entry_recipe_id);
			$inp_entry_recipe_id_mysql = quote_smart($link, $inp_entry_recipe_id);


			$inp_entry_serving_size = $_GET['entry_serving_size'];
			$inp_entry_serving_size = output_html($inp_entry_serving_size);
			$inp_entry_serving_size = str_replace(",", ".", $inp_entry_serving_size);
			$inp_entry_serving_size_mysql = quote_smart($link, $inp_entry_serving_size);
			if($inp_entry_serving_size == ""){
				$url = "food_diary_add_recipe.php?date=$date&hour_name=$hour_name&l=$l";
				$url = $url . "&ft=error&fm=missing_amount";
				header("Location: $url");
				exit;
			}


			// get recipe
			$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_country, recipe_introduction, recipe_directions, recipe_image_path, recipe_image, recipe_thumb_278x156, recipe_video, recipe_date, recipe_date_saying, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_times_favorited, recipe_user_ip, recipe_notes, recipe_password, recipe_last_viewed, recipe_age_restriction, recipe_published FROM $t_recipes WHERE recipe_id=$inp_entry_recipe_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_country, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb_278x156, $get_recipe_video, $get_recipe_date, $get_recipe_date_saying, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_times_favorited, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password, $get_recipe_last_viewed, $get_recipe_age_restriction, $get_recipe_published) = $row;
			if($get_recipe_id == ""){
				$url = "food_diary_add_recipe.php?date=$date&meal_id=$meal_id&l=$l";
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




			// 1) Insert recipe into entry
			mysqli_query($link, "INSERT INTO $t_food_diary_entires
			(entry_id, entry_user_id, entry_date, entry_date_saying, entry_hour_name, entry_food_id, 
			entry_recipe_id, entry_meal_id, entry_name, entry_manufacturer_name, entry_serving_size, entry_serving_size_measurement, 
			entry_energy_per_entry, entry_fat_per_entry, entry_saturated_fat_per_entry, entry_monounsaturated_fat_per_entry, entry_polyunsaturated_fat_per_entry, 
			entry_cholesterol_per_entry, entry_carbohydrates_per_entry, entry_carbohydrates_of_which_sugars_per_entry, entry_dietary_fiber_per_entry, entry_proteins_per_entry, 
			entry_salt_per_entry, entry_sodium_per_entry, entry_updated_datetime, entry_synchronized) 
			VALUES 
			(NULL, '$get_my_user_id', $inp_entry_date_mysql, $inp_entry_date_saying_mysql, $inp_entry_hour_name_mysql, 0, 
			$inp_entry_recipe_id_mysql, 0, $inp_entry_name_mysql, '', $inp_entry_serving_size_mysql, $inp_entry_serving_size_measurement_mysql, 
			$inp_entry_energy_per_entry_mysql, $inp_entry_fat_per_entry_mysql, $inp_entry_saturated_fat_per_entry_mysql, $inp_entry_monounsaturated_fat_per_entry_mysql, $inp_entry_polyunsaturated_fat_per_entry_mysql, 
			$inp_entry_cholesterol_per_entry_mysql, $inp_entry_carbohydrates_per_entry_mysql, $inp_entry_carbohydrates_of_which_sugars_per_entry_mysql, $inp_entry_dietary_fiber_per_entry_mysql, $inp_entry_proteins_per_entry_mysql,
			$inp_entry_salt_per_entry_mysql, $inp_entry_sodium_per_entry_mysql, '$datetime', '0')")
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


			// 4) Insert into last used recipe
			$inp_last_used_name_mysql = quote_smart($link, $get_recipe_title);
			$inp_last_used_manufacturer = quote_smart($link, "");
			$inp_last_used_image_path = quote_smart($link, $get_recipe_image_path);
			$inp_last_used_image_thumb_132x132 = quote_smart($link, $get_recipe_thumb_278x156);  // Todo

			$inp_last_used_metric_or_us_mysql = quote_smart($link, "");
			$inp_last_used_selected_measurement_mysql = quote_smart($link, "");

			$inp_last_used_selected_serving_size_mysql = quote_smart($link, $inp_entry_serving_size);

			$inp_last_used_serving_size_metric_mysql = quote_smart($link, -1);
			$inp_last_used_serving_size_measurement_metric_mysql = quote_smart($link, "");
			$inp_last_used_serving_size_us_mysql = quote_smart($link, -1);
			$inp_last_used_serving_size_measurement_us_mysql = quote_smart($link, "");
			$inp_last_used_serving_size_pcs_mysql = quote_smart($link, $inp_entry_serving_size);
			$inp_last_used_serving_size_pcs_measurement_mysql = quote_smart($link,  "");




			$inp_last_used_energy_metric_mysql = quote_smart($link, $get_number_energy_metric);
			$inp_last_used_fat_metric_mysql = quote_smart($link, $get_number_fat_metric);
			$inp_last_used_saturated_fat_metric_mysql = quote_smart($link, $get_number_saturated_fat_metric);
			$inp_last_used_monounsaturated_fat_metric_mysql = quote_smart($link, $get_number_monounsaturated_fat_metric);
			$inp_last_used_polyunsaturated_fat_metric_mysql = quote_smart($link, $get_number_polyunsaturated_fat_metric);
			$inp_last_used_cholesterol_metric_mysql = quote_smart($link, $get_number_cholesterol_metric);
			$inp_last_used_carbohydrates_metric_mysql = quote_smart($link, $get_number_carbohydrates_metric);
			$inp_last_used_carbohydrates_of_which_sugars_metric_mysql = quote_smart($link, $get_number_carbohydrates_of_which_sugars_metric);
			$inp_last_used_dietary_fiber_metric_mysql = quote_smart($link, $get_number_dietary_fiber_metric);
			$inp_last_used_proteins_metric_mysql = quote_smart($link, $get_number_proteins_metric);
			$inp_last_used_salt_metric_mysql = quote_smart($link, $get_number_salt_metric);
			$inp_last_used_sodium_metric_mysql = quote_smart($link, $get_number_sodium_metric);

			$inp_last_used_energy_us_mysql = quote_smart($link, -1);
			$inp_last_used_fat_us_mysql = quote_smart($link, -1);
			$inp_last_used_saturated_fat_us_mysql = quote_smart($link, -1);
			$inp_last_used_monounsaturated_fat_us_mysql = quote_smart($link, -1);
			$inp_last_used_polyunsaturated_fat_us_mysql = quote_smart($link, -1);
			$inp_last_used_cholesterol_us_mysql = quote_smart($link, -1);
			$inp_last_used_carbohydrates_us_mysql = quote_smart($link, -1);
			$inp_last_used_carbohydrates_of_which_sugars_us_mysql = quote_smart($link, -1);
			$inp_last_used_dietary_fiber_us_mysql = quote_smart($link, -1);
			$inp_last_used_proteins_us_mysql = quote_smart($link, -1);
			$inp_last_used_salt_us_mysql = quote_smart($link, -1);
			$inp_last_used_sodium_us_mysql = quote_smart($link, -1);

			$query = "SELECT last_used_id, last_used_times FROM $t_food_diary_last_used WHERE last_used_user_id=$my_user_id_mysql AND last_used_hour_name=$inp_entry_hour_name_mysql AND last_used_recipe_id=$inp_entry_recipe_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_last_used_id, $get_last_used_times) = $row;
			if($get_last_used_id == ""){
				// First time use this recipe
				mysqli_query($link, "INSERT INTO $t_food_diary_last_used
				(last_used_id, last_used_user_id, last_used_hour_name, last_used_food_id, last_used_recipe_id, 
				last_used_meal_id, last_used_times, last_used_created_datetime, last_used_updated_datetime, last_used_name, last_used_manufacturer, 
				last_used_image_path, last_used_image_thumb_132x132, last_used_main_category_id, last_used_metric_or_us, 

				last_used_selected_serving_size, last_used_selected_measurement, last_used_serving_size_metric, last_used_serving_size_measurement_metric, last_used_serving_size_us, 
				last_used_serving_size_measurement_us, last_used_serving_size_pcs, last_used_serving_size_pcs_measurement, last_used_energy_metric, last_used_fat_metric, 
				last_used_saturated_fat_metric, last_used_monounsaturated_fat_metric, last_used_polyunsaturated_fat_metric, last_used_cholesterol_metric, last_used_carbohydrates_metric, 
				last_used_carbohydrates_of_which_sugars_metric, last_used_dietary_fiber_metric, last_used_proteins_metric, last_used_salt_metric, last_used_sodium_metric, last_used_energy_serving, last_used_fat_serving, last_used_saturated_fat_serving, 
				last_used_monounsaturated_fat_serving, last_used_polyunsaturated_fat_serving, last_used_cholesterol_serving, last_used_carbohydrates_serving, last_used_carbohydrates_of_which_sugars_serving, 
				last_used_dietary_fiber_serving, last_used_proteins_serving, last_used_salt_serving, last_used_sodium_serving) 
				VALUES 
				(NULL, '$get_my_user_id', $inp_entry_hour_name_mysql, 0, $get_recipe_id, 
				0, 1, '$datetime', '$datetime', $inp_last_used_name_mysql, $inp_last_used_manufacturer, 
				$inp_last_used_image_path, $inp_last_used_image_thumb_132x132, $get_recipe_category_id, $inp_last_used_metric_or_us_mysql, 

				$inp_last_used_selected_serving_size_mysql, $inp_last_used_selected_measurement_mysql, $inp_last_used_serving_size_metric_mysql, $inp_last_used_serving_size_measurement_metric_mysql, $inp_last_used_serving_size_us_mysql, 
				$inp_last_used_serving_size_measurement_us_mysql, $inp_last_used_serving_size_pcs_mysql, $inp_last_used_serving_size_pcs_measurement_mysql, $inp_last_used_energy_metric_mysql, $inp_last_used_fat_metric_mysql,
				$inp_last_used_saturated_fat_metric_mysql, $inp_last_used_monounsaturated_fat_metric_mysql, $inp_last_used_polyunsaturated_fat_metric_mysql, $inp_last_used_cholesterol_metric_mysql, $inp_last_used_carbohydrates_metric_mysql, 

$inp_last_used_carbohydrates_of_which_sugars_metric_mysql, 
				$inp_last_used_dietary_fiber_metric_mysql, $inp_last_used_proteins_metric_mysql, $inp_last_used_salt_metric_mysql, 
				$inp_last_used_sodium_us_mysql, $inp_entry_energy_per_entry_mysql, $inp_entry_fat_per_entry_mysql, $inp_entry_saturated_fat_per_entry_mysql, $inp_entry_monounsaturated_fat_per_entry_mysql, $inp_entry_polyunsaturated_fat_per_entry_mysql, 
			$inp_entry_cholesterol_per_entry_mysql, $inp_entry_carbohydrates_per_entry_mysql, $inp_entry_carbohydrates_of_which_sugars_per_entry_mysql, $inp_entry_dietary_fiber_per_entry_mysql, $inp_entry_proteins_per_entry_mysql,
			$inp_entry_salt_per_entry_mysql, $inp_entry_sodium_per_entry_mysql)")
				or die(mysqli_error($link));

			}
			else{
				// Update counter and date
				$inp_last_used_times = $get_last_used_times + 1;

				$result = mysqli_query($link, "UPDATE $t_food_diary_last_used SET 
								last_used_times='$inp_last_used_times', 
								last_used_updated_datetime='$datetime', 
								last_used_selected_serving_size=$inp_entry_serving_size_mysql
				 WHERE last_used_id='$get_last_used_id'") or die(mysqli_error($link));

			}

			$url = "index.php?action=food_diary&date=$date";
			$url = $url . "&ft=success&fm=food_added";
			if($hour_name == "breakfast"){
				
			}
			elseif($hour_name == "lunch"){
			}
			elseif($hour_name == "before_training"){
				$url = $url . "#hour_lunch";
			}
			elseif($hour_name == "after_training"){
				$url = $url . "#hour_before_training";
			}
			elseif($hour_name == "linner"){
				$url = $url . "#hour_after_training";
			}
			elseif($hour_name == "snacks"){
				$url = $url . "#hour_linner";
			}
			elseif($hour_name == "before_supper"){
				$url = $url . "#hour_snacks";
			}
			elseif($hour_name == "supper"){
				$url = $url . "#hour_before_supper";
			}
			elseif($hour_name == "night_meal"){
				$url = $url . "#hour_supper";
			}
			header("Location: $url");
			exit;
		}
	} // add_recipe_to_diary
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