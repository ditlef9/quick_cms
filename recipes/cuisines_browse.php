<?php 
/**
*
* File: recipes/cuisines_browse.php
* Version 2.0.0
* Date 19:10 04.01.2021
* Copyright (c) 2020 S. A. Ditlefsen
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

// Language
include("$root/_admin/_translations/site/$l/recipes/ts_search.php");
include("$root/_admin/_translations/site/$l/recipes/ts_index.php");



/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['cuisine_id'])) {
	$cuisine_id = $_GET['cuisine_id'];
	$cuisine_id = strip_tags(stripslashes($cuisine_id));
}
else{
	$cuisine_id = "";
}
if(isset($_GET['order_by'])) {
	$order_by = $_GET['order_by'];
	$order_by = strip_tags(stripslashes($order_by));
}
else{
	$order_by = "recipe_id";
}
if(isset($_GET['order_method'])) {
	$order_method = $_GET['order_method'];
	$order_method = strip_tags(stripslashes($order_method));
}
else{
	$order_method = "desc";
}

$l_mysql = quote_smart($link, $l);


// Select
$cuisine_id_mysql = quote_smart($link, $cuisine_id);
$query = "SELECT cuisine_id, cuisine_name, cuisine_image, cuisine_last_updated FROM $t_recipes_cuisines WHERE cuisine_id=$cuisine_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_cuisine_id, $get_cuisine_name, $get_cuisine_image, $get_cuisine_last_updated) = $row;

if($get_cuisine_id != ""){
	// Translations
	$query = "SELECT cuisine_translation_id, cuisine_translation_value FROM $t_recipes_cuisines_translations WHERE cuisine_id='$get_cuisine_id' AND cuisine_translation_language=$l_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_cuisine_translation_id, $get_cuisine_translation_value) = $row;
	
}


/*- Headers ---------------------------------------------------------------------------------- */
if($get_cuisine_id == ""){
	$website_title = "Server error 404";
}
else{
	$website_title = "$get_cuisine_translation_value - $l_cuisines - $l_recipes";
}

if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */

if($action == ""){
	echo"
	<div class=\"recipes_headline\">
		<h1>$get_cuisine_translation_value</h1>

		<!-- You are here -->
			<p><b>$l_you_are_here:</b><br />
			<a href=\"index.php?l=$l\">$l_recipes</a>
			&gt;
			<a href=\"cuisines.php?l=$l\">$l_cuisines</a>
			&gt;
			<a href=\"cuisines_browse.php?cuisine_id=$cuisine_id&amp;l=$l\">$get_cuisine_translation_value</a>
			</p>
		<!-- //You are here -->
	</div>
	<div class=\"recipes_menu\">
		<!-- Order -->
			<script>
			\$(function(){
				\$('#inp_order_by_select').on('change', function () {
					var url = \$(this).val();
					if (url) { // require a URL
 						window.location = url;
					}
					return false;
				});
				\$('#inp_order_method_select').on('change', function () {
					var url = \$(this).val();
					if (url) { // require a URL
 						window.location = url;
					}
					return false;
				});
			});
			</script>

			<form method=\"get\" action=\"search.php\" enctype=\"multipart/form-data\">
				<p>
				<select name=\"inp_order_by\" id=\"inp_order_by_select\">
					<option value=\"index.php\">- $l_order_by -</option>
					<option value=\"cuisines_browse.php?cuisine_id=$cuisine_id&amp;order_by=recipe_id&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "recipe_id" OR $order_by == ""){ echo" selected=\"selected\""; } echo">$l_date</option>
					<option value=\"cuisines_browse.php?cuisine_id=$cuisine_id&amp;order_by=recipe_title&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "recipe_title"){ echo" selected=\"selected\""; } echo">$l_title</option>
					<option value=\"cuisines_browse.php?cuisine_id=$cuisine_id&amp;order_by=recipe_comments&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "recipe_comments"){ echo" selected=\"selected\""; } echo">$l_comments</option>
					<option value=\"cuisines_browse.php?cuisine_id=$cuisine_id&amp;order_by=recipe_unique_hits&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "recipe_unique_hits"){ echo" selected=\"selected\""; } echo">$l_unique_hits</option>
				</select>
				<select name=\"inp_order_method\" id=\"inp_order_method_select\">
					<option value=\"cuisines_browse.php?cuisine_id=$cuisine_id&amp;order_by=$order_by&amp;order_method=asc&amp;l=$l\""; if($order_method == "asc" OR $order_method == ""){ echo" selected=\"selected\""; } echo">$l_asc</option>
					<option value=\"cuisines_browse.php?cuisine_id=$cuisine_id&amp;order_by=$order_by&amp;order_method=desc&amp;l=$l\""; if($order_method == "desc"){ echo" selected=\"selected\""; } echo">$l_desc</option>
				</select>
				</p>
        		</form>
		<!-- //Order -->
	</div>
	<div class=\"clear\"></div>


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
	<input type=\"checkbox\" name=\"inp_show_hundred_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=hundred_metric&amp;process=1&amp;referer=cuisines_browse.php&amp;cuisine_id=$get_cuisine_id&amp;l=$l\""; if($get_current_view_hundred_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_hundred
	<input type=\"checkbox\" name=\"inp_show_serving\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=serving&amp;process=1&amp;referer=cuisines_browse.php&amp;cuisine_id=$get_cuisine_id&amp;l=$l\""; if($get_current_view_serving == "1"){ echo" checked=\"checked\""; } echo" /> $l_serving
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



	";

	$date = date("Y-m-d");
	
	// Select recipes
	$x = 0;
	$query = "SELECT $t_recipes.recipe_id, $t_recipes.recipe_title, $t_recipes.recipe_category_id, $t_recipes.recipe_introduction, $t_recipes.recipe_image_path, $t_recipes.recipe_image_h_a, $t_recipes.recipe_thumb_h_a_278x156, $t_recipes.recipe_unique_hits FROM $t_recipes JOIN $t_recipes_numbers ON $t_recipes.recipe_id=$t_recipes_numbers.number_recipe_id WHERE $t_recipes.recipe_language=$l_mysql AND $t_recipes.recipe_cusine_id='$get_cuisine_id' AND $t_recipes.recipe_published=1";
	// Order
	if($order_method == "desc"){
		$order_method_mysql = "DESC";
	}
	else{
		$order_method_mysql = "ASC";
	}

	if($order_by == "recipe_id" OR $order_by == "recipe_title" OR $order_by == "recipe_unique_hits" OR $order_by == "recipe_unique_hits"){
		$order_by_mysql = "$t_recipes.$order_by";
	}
	else{
		$order_by_mysql = "$t_recipes.recipe_id";
	}

	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_recipe_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_introduction, $get_recipe_image_path, $get_recipe_image_h_a, $get_recipe_thumb_h_a_278x156, $get_recipe_unique_hits) = $row;

		if($get_recipe_image_h_a != "" && file_exists("$root/$get_recipe_image_path/$get_recipe_image_h_a")){
			// Category
			$query_cat = "SELECT category_translation_id, category_translation_title FROM $t_recipes_categories_translations WHERE category_id=$get_recipe_category_id AND category_translation_language=$l_mysql";
			$result_cat = mysqli_query($link, $query_cat);
			$row_cat = mysqli_fetch_row($result_cat);
			list($get_category_translation_id, $get_category_translation_title) = $row_cat;
	
			// Select Nutrients
			$query_n = "SELECT number_id, number_recipe_id, number_servings, number_energy_metric, number_fat_metric, number_saturated_fat_metric, number_monounsaturated_fat_metric, number_polyunsaturated_fat_metric, number_cholesterol_metric, number_carbohydrates_metric, number_carbohydrates_of_which_sugars_metric, number_dietary_fiber_metric, number_proteins_metric, number_salt_metric, number_sodium_metric, number_energy_serving, number_fat_serving, number_saturated_fat_serving, number_monounsaturated_fat_serving, number_polyunsaturated_fat_serving, number_cholesterol_serving, number_carbohydrates_serving, number_carbohydrates_of_which_sugars_serving, number_dietary_fiber_serving, number_proteins_serving, number_salt_serving, number_sodium_serving FROM $t_recipes_numbers WHERE number_recipe_id=$get_recipe_id";
			$result_n = mysqli_query($link, $query_n);
			$row_n = mysqli_fetch_row($result_n);
			list($get_number_id, $get_number_recipe_id, $get_number_servings, $get_number_energy_metric, $get_number_fat_metric, $get_number_saturated_fat_metric, $get_number_monounsaturated_fat_metric, $get_number_polyunsaturated_fat_metric, $get_number_cholesterol_metric, $get_number_carbohydrates_metric, $get_number_carbohydrates_of_which_sugars_metric, $get_number_dietary_fiber_metric, $get_number_proteins_metric, $get_number_salt_metric, $get_number_sodium_metric, $get_number_energy_serving, $get_number_fat_serving, $get_number_saturated_fat_serving, $get_number_monounsaturated_fat_serving, $get_number_polyunsaturated_fat_serving, $get_number_cholesterol_serving, $get_number_carbohydrates_serving, $get_number_carbohydrates_of_which_sugars_serving, $get_number_dietary_fiber_serving, $get_number_proteins_serving, $get_number_salt_serving, $get_number_sodium_serving) = $row_n;



			// Thumb
			if(!(file_exists("$root/$get_recipe_image_path/$get_recipe_thumb_h_a_278x156"))){
				if($get_recipe_thumb_h_a_278x156 == ""){
					echo"<div class=\"info\">Thumb 278x156 is blank</div>";
					die;
				}
				$inp_new_x = 278;
				$inp_new_y = 156;
				resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_recipe_image_path/$get_recipe_image_h_a", "$root/$get_recipe_image_path/$get_recipe_thumb_h_a_278x156");
			}

			if($x == 0){
				echo"
				<div class=\"clear\"></div>
				<div class=\"left_center_center_right_left\">
				";
			}
			elseif($x == 1){
				echo"
				<div class=\"left_center_center_left_right_center\">
				";
			}
			elseif($x == 2){
				echo"
				<div class=\"left_center_center_right_right_center\">
				";
			}
			elseif($x == 3){
				echo"
				<div class=\"left_center_center_right_right\">
				";
			}
		
			echo"

					<!-- skriver ut bilder per oppskrift -->
					<p>
					<a href=\"$root/recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\"><img src=\"$root/$get_recipe_image_path/$get_recipe_thumb_h_a_278x156\" alt=\"$get_recipe_image_h_a\" /></a><br />
					<a href=\"$root/recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\" class=\"h2\">$get_recipe_title</a>
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




				</div>
			";

			// Increment
			$x++;
		
			// Reset
			if($x == 4){
				$x = 0;
			}


			// Update image?
			if($get_cuisine_last_updated != "$date"){
				$inp_cuisine_image = "$get_recipe_image_path/$get_recipe_image_h_a";
				$inp_cuisine_image_mysql = quote_smart($link, $inp_cuisine_image);
				$result_upd = mysqli_query($link, "UPDATE $t_recipes_cuisines SET cuisine_image=$inp_cuisine_image_mysql, cuisine_last_updated='$date' WHERE cuisine_id='$get_cuisine_id'");



			}


		} // get_recipe_image

	}

	if($x == 1){
		echo"
			<div class=\"left_center_center_right_center\">
			</div>
			<div class=\"left_center_center_right_center\">
			</div>
			<div class=\"left_center_center_right_right\">
			</div>
			<div class=\"clear\"></div>
		";
	
	}
	elseif($x == 2){
		echo"
			<div class=\"left_center_center_right_center\">
			</div>
			<div class=\"left_center_center_right_right\">
			</div>
			<div class=\"clear\"></div>
		";

	}
	elseif($x == 3){
		echo"
			<div class=\"left_center_center_right_right\">
			</div>
			<div class=\"clear\"></div>
		";

	}
	elseif($x == 4){
		echo"
			<div class=\"clear\"></div>
		";

	}

}


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>