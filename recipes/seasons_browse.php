<?php 
/**
*
* File: recipes/seasons_browse.php
* Version 2.0.0
* Date 19:50 04.01.2021
* Copyright (c) 2021 Localhost
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
if(isset($_GET['season_id'])) {
	$season_id = $_GET['season_id'];
	$season_id = strip_tags(stripslashes($season_id));
}
else{
	$season_id = "";
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

$season_id_mysql = quote_smart($link, $season_id);
$query = "SELECT season_id, season_name, season_image, season_last_updated FROM $t_recipes_seasons WHERE season_id=$season_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_season_id, $get_season_name, $get_season_image, $get_season_last_updated) = $row;

if($get_season_id != ""){
	// Translations
	$query = "SELECT season_translation_id, season_translation_value FROM $t_recipes_seasons_translations WHERE season_id='$get_season_id' AND season_translation_language=$l_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_season_translation_id, $get_season_translation_value) = $row;
	
}


/*- Headers ---------------------------------------------------------------------------------- */
if($get_season_id == ""){
	$website_title = "Server error 404";
}
else{
	$website_title = "$get_season_translation_value - $l_seasons - $l_recipes";
}
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */

if($action == ""){
	echo"
	<div class=\"recipes_headline\">
		<h1>$get_season_translation_value</h1>

		<!-- Where am I? -->
			<p><b>$l_you_are_here:</b><br />
			<a href=\"index.php?l=$l\">$l_recipes</a>
			&gt;
			<a href=\"seasons_browse.php?season_id=$get_season_id&amp;l=$l\">$get_season_translation_value</a>
			</p>
		<!-- //Where am I? -->
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
					<option value=\"seasons_browse.php?season_id=$season_id&amp;order_by=recipe_id&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "recipe_id" OR $order_by == ""){ echo" selected=\"selected\""; } echo">$l_date</option>
					<option value=\"seasons_browse.php?season_id=$season_id&amp;order_by=recipe_title&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "recipe_title"){ echo" selected=\"selected\""; } echo">$l_title</option>
					<option value=\"seasons_browse.php?season_id=$season_id&amp;order_by=recipe_comments&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "recipe_comments"){ echo" selected=\"selected\""; } echo">$l_comments</option>
					<option value=\"seasons_browse.php?season_id=$season_id&amp;order_by=recipe_unique_hits&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "recipe_unique_hits"){ echo" selected=\"selected\""; } echo">$l_unique_hits</option>
					<option value=\"seasons_browse.php?season_id=$season_id&amp;order_by=number_serving_calories&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "number_serving_calories"){ echo" selected=\"selected\""; } echo">$l_calories</option>
					<option value=\"seasons_browse.php?season_id=$season_id&amp;order_by=number_serving_fat&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "number_serving_fat"){ echo" selected=\"selected\""; } echo">$l_fat</option>
					<option value=\"seasons_browse.php?season_id=$season_id&amp;order_by=number_serving_carbs&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "number_serving_carbs"){ echo" selected=\"selected\""; } echo">$l_carbs</option>
					<option value=\"seasons_browse.php?season_id=$season_id&amp;order_by=number_serving_proteins&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "number_serving_proteins"){ echo" selected=\"selected\""; } echo">$l_proteins</option>
				</select>
				<select name=\"inp_order_method\" id=\"inp_order_method_select\">
					<option value=\"seasons_browse.php?season_id=$season_id&amp;order_by=$order_by&amp;order_method=asc&amp;l=$l\""; if($order_method == "asc" OR $order_method == ""){ echo" selected=\"selected\""; } echo">$l_asc</option>
					<option value=\"seasons_browse.php?season_id=$season_id&amp;order_by=$order_by&amp;order_method=desc&amp;l=$l\""; if($order_method == "desc"){ echo" selected=\"selected\""; } echo">$l_desc</option>
				</select>
				</p>
        		</form>
		<!-- //Order -->
	</div>


	<!-- Adapter view -->";
		if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
			$my_user_id = $_SESSION['user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);
			
			$query_t = "SELECT view_id, view_user_id, view_ip, view_year, view_system, view_hundred_metric, view_serving, view_eight_us FROM $t_recipes_user_adapted_view WHERE view_user_id=$get_my_user_id";
			$result_t = mysqli_query($link, $query_t);
			$row_t = mysqli_fetch_row($result_t);
			list($get_current_view_id, $get_current_view_user_id, $get_current_view_ip, $get_current_view_year, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_serving, $get_current_view_eight_us) = $row_t;
		}
		else{
			// IP
			$my_user_ip = $_SERVER['REMOTE_ADDR'];
			$my_user_ip = output_html($my_user_ip);
			$my_user_ip_mysql = quote_smart($link, $my_user_ip);
			
			$query_t = "SELECT view_id, view_user_id, view_ip, view_year, view_system, view_hundred_metric, view_serving, view_eight_us FROM $t_recipes_user_adapted_view WHERE view_ip=$my_user_ip_mysql";
			$result_t = mysqli_query($link, $query_t);
			$row_t = mysqli_fetch_row($result_t);
			list($get_current_view_id, $get_current_view_user_id, $get_current_view_ip, $get_current_view_year, $get_current_view_system, $get_current_view_hundred_metric, $get_current_view_serving, $get_current_view_eight_us) = $row_t;
		}
		if($get_current_view_hundred_metric == ""){
			$get_current_view_hundred_metric = "1";
		}
		echo"
		<p><a id=\"adapter_view\"></a>
		<b>$l_show_per:</b>
		<input type=\"checkbox\" name=\"inp_show_hundred_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=hundred_metric&amp;process=1&amp;referer=seasons_browse&amp;season_id=$get_season_id&amp;l=$l\""; if($get_current_view_hundred_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_hundred
		<input type=\"checkbox\" name=\"inp_show_pcs_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=serving&amp;process=1&amp;referer=seasons_browse&amp;season_id=$get_season_id&amp;l=$l\""; if($get_current_view_serving == "1"){ echo" checked=\"checked\""; } echo" /> $l_serving
		<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=eight_us&amp;process=1&amp;referer=seasons_browse&amp;season_id=$get_season_id&amp;l=$l\""; if($get_current_view_eight_us == "1"){ echo" checked=\"checked\""; } echo" /> $l_eight
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

	";

	$date = date("Y-m-d");

	// Select recipes
	$x = 0;
	$query = "SELECT $t_recipes.recipe_id, $t_recipes.recipe_title, $t_recipes.recipe_introduction, $t_recipes.recipe_image_path, $t_recipes.recipe_image_h_a, $t_recipes.recipe_thumb_h_a_278x156, $t_recipes.recipe_unique_hits FROM $t_recipes JOIN $t_recipes_numbers ON $t_recipes.recipe_id=$t_recipes_numbers.number_recipe_id WHERE $t_recipes.recipe_season_id='$get_season_id' AND $t_recipes.recipe_language=$l_mysql AND $t_recipes.recipe_published=1";
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
	elseif($order_by == "number_serving_calories" OR $order_by == "number_serving_fat" OR $order_by == "number_serving_carbs" OR $order_by == "number_serving_proteins"){
		$order_by_mysql = "$t_recipes_numbers.$order_by";
	}
	else{
		$order_by_mysql = "$t_recipes.recipe_id";
	}
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_recipe_id, $get_recipe_title, $get_recipe_introduction, $get_recipe_image_path, $get_recipe_image_h_a, $get_recipe_thumb_h_a_278x156, $get_recipe_unique_hits) = $row;

		if($get_recipe_image_h_a != ""){
		

			// Get rating
			$query_rating = "SELECT rating_id, rating_average FROM $t_recipes_rating WHERE rating_recipe_id='$get_recipe_id'";
			$result_rating = mysqli_query($link, $query_rating);
			$row_rating = mysqli_fetch_row($result_rating);
			list($get_rating_id, $get_rating_average) = $row_rating;

			// Select Nutrients
			$query_n = "SELECT number_id, number_recipe_id, number_servings, number_energy_metric, number_fat_metric, number_saturated_fat_metric, number_monounsaturated_fat_metric, number_polyunsaturated_fat_metric, number_cholesterol_metric, number_carbohydrates_metric, number_carbohydrates_of_which_sugars_metric, number_dietary_fiber_metric, number_proteins_metric, number_salt_metric, number_sodium_metric, number_energy_serving, number_fat_serving, number_saturated_fat_serving, number_monounsaturated_fat_serving, number_polyunsaturated_fat_serving, number_cholesterol_serving, number_carbohydrates_serving, number_carbohydrates_of_which_sugars_serving, number_dietary_fiber_serving, number_proteins_serving, number_salt_serving, number_sodium_serving, number_energy_total, number_fat_total, number_saturated_fat_total, number_monounsaturated_fat_total, number_polyunsaturated_fat_total, number_cholesterol_total, number_carbohydrates_total, number_carbohydrates_of_which_sugars_total, number_dietary_fiber_total, number_proteins_total, number_salt_total, number_sodium_total FROM $t_recipes_numbers WHERE number_recipe_id=$get_recipe_id";
			$result_n = mysqli_query($link, $query_n);
			$row_n = mysqli_fetch_row($result_n);
			list($get_number_id, $get_number_recipe_id, $get_number_servings, $get_number_energy_metric, $get_number_fat_metric, $get_number_saturated_fat_metric, $get_number_monounsaturated_fat_metric, $get_number_polyunsaturated_fat_metric, $get_number_cholesterol_metric, $get_number_carbohydrates_metric, $get_number_carbohydrates_of_which_sugars_metric, $get_number_dietary_fiber_metric, $get_number_proteins_metric, $get_number_salt_metric, $get_number_sodium_metric, $get_number_energy_serving, $get_number_fat_serving, $get_number_saturated_fat_serving, $get_number_monounsaturated_fat_serving, $get_number_polyunsaturated_fat_serving, $get_number_cholesterol_serving, $get_number_carbohydrates_serving, $get_number_carbohydrates_of_which_sugars_serving, $get_number_dietary_fiber_serving, $get_number_proteins_serving, $get_number_salt_serving, $get_number_sodium_serving, $get_number_energy_total, $get_number_fat_total, $get_number_saturated_fat_total, $get_number_monounsaturated_fat_total, $get_number_polyunsaturated_fat_total, $get_number_cholesterol_total, $get_number_carbohydrates_total, $get_number_carbohydrates_of_which_sugars_total, $get_number_dietary_fiber_total, $get_number_proteins_total, $get_number_salt_total, $get_number_sodium_total) = $row_n;
			
			// 4 divs

			// 847 / 4 = 211
			// 847 / 3 = 282

			// Recipe thumb
			if($get_recipe_thumb_h_a_278x156 == "" OR !(file_exists("$root/$get_recipe_image_path/$get_recipe_thumb_h_a_278x156"))){
				$inp_new_x = 278; // from HD 1920x1080
				$inp_new_y = 156;

				$ext = get_extension($get_recipe_image);

				echo"<div class=\"info\"><p>Creating recipe thumb $inp_new_x x $inp_new_y  px</p></div>";

				$thumb = $get_recipe_id . "_thumb_h_a_" . $inp_new_x . "x" . $inp_new_y . ".png";
				$thumb_mysql = quote_smart($link, $thumb);
				resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_recipe_image_path/$get_recipe_image_h_a", "$root/$get_recipe_image_path/$thumb");
				mysqli_query($link, "UPDATE $t_recipes SET recipe_thumb_h_a_278x156=$thumb_mysql WHERE recipe_id=$get_recipe_id") or die(mysqli_error($link));
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

					<!-- Numbers -->";

						if($get_current_view_hundred_metric == "1" OR $get_current_view_serving == "1" OR $get_current_view_eight_us == "1"){
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
								  <td style=\"padding-right: 6px;text-align: center;\">
									<span class=\"nutritional_number\">$l_serving</span>
								  </td>
								  <td style=\"padding-right: 6px;text-align: center;\">
									<span class=\"nutritional_number\">$get_number_energy_serving</span>
								  </td>
								  <td style=\"padding-right: 6px;text-align: center;\">
									<span class=\"nutritional_number\">$get_number_fat_serving</span>
								  </td>
								  <td style=\"padding-right: 6px;text-align: center;\">
									<span class=\"nutritional_number\">$get_number_carbohydrates_serving</span>
								  </td>
								  <td style=\"text-align: center;\">
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
							}
							echo"
					<!-- //Numbers -->

				</div>
			";

			// Increment
			$x++;
		
			// Reset
			if($x == 4){
				$x = 0;
			}




			// Update image?
			if($get_season_last_updated != "$date"){
				$inp_season_image = "$get_recipe_image_path/$get_recipe_image_h_a";
				$inp_season_image_mysql = quote_smart($link, $inp_season_image);
				$result_upd = mysqli_query($link, "UPDATE $t_recipes_seasons SET season_image=$inp_season_image_mysql, season_last_updated='$date' WHERE season_id='$get_season_id'");
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

}
elseif($action == "3"){

echo"
<h1>$get_category_translation_value</h1>
";

// Select recipes

$x = 0;
$query = "SELECT recipe_id, recipe_title, recipe_introduction, recipe_image_path, recipe_image FROM $t_recipes WHERE recipe_category_id='$get_category_id' AND recipe_language=$l_mysql AND recipe_published=1 ORDER BY recipe_last_viewed ASC";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_row($result)) {
	list($get_recipe_id, $get_recipe_title, $get_recipe_introduction, $get_recipe_image_path, $get_recipe_image) = $row;

	if($get_recipe_image != ""){
		
			
		// 4 divs

		// 847 / 4 = 211
		// 847 / 3 = 282

		// Thumb
		$inp_new_x = 277;
		$inp_new_y = 103;
		$thumb = $get_recipe_id . "-" . $inp_new_x . "x" . $inp_new_y . "png";

		if(!(file_exists("$root/_cache/$thumb"))){
			create_thumb("$root/$get_recipe_image_path/$get_recipe_image", "$root/_cache/$thumb", $inp_new_x, $inp_new_y);
		}



		if($x == 0){
			echo"
			<div class=\"clear\"></div>
			<div class=\"left_center_right_left\">
			";
		}
		elseif($x == 1){
			echo"
			<div class=\"left_center_right_center\">
			";
		}
		elseif($x == 2){
			echo"
			<div class=\"left_center_right_right\">
			";
		}
		
		echo"
				<p style=\"margin: 10px 0px 10px 0px;padding: 0;\">
				<a href=\"$root/recipes/view_recipe.php?recipe_id=$get_recipe_id\"><img src=\"$root/_cache/$thumb\" alt=\"$get_recipe_image\" width=\"$inp_new_x\" height=\"$inp_new_y\" /></a><br />
				<a href=\"$root/recipes/view_recipe.php?recipe_id=$get_recipe_id\" class=\"h2\">$get_recipe_title</a>
				</p>


			</div>
		";

		// Increment
		$x++;
		
		// Reset
		if($x == 3){
			$x = 0;
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
	";

}
elseif($x == 2){
	echo"
			<div class=\"left_center_center_right_center\">
			</div>
			<div class=\"left_center_center_right_right\">
			</div>
	";

}
elseif($x == 3){
	echo"
			<div class=\"left_center_center_right_right\">
			</div>
	";

}
}


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>