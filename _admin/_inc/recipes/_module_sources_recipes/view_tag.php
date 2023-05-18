<?php 
/**
*
* File: recipes/view_tag.php
* Version 1.0.0
* Date 13:43 18.11.2017
* Copyright (c) 2011-2017 Localhost
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


/*- Tables ------------------------------------------------------------------------ */
$t_recipes_tags_unique			= $mysqlPrefixSav . "recipes_tags_unique";

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/recipes/ts_index.php");

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['tag'])) {
	$tag = $_GET['tag'];
	$tag = output_html($tag);
}
else{
	$tag = "";
}
$l_mysql = quote_smart($link, $l);

// Find tag
$year = date("Y");
$month = date("m");
$week = date("W");
$tag_title_clean_mysql = quote_smart($link, $tag);
$query = "SELECT tag_id, tag_language, tag_title, tag_title_clean, tag_number_of_recipes, tag_last_clicked_week, tag_unique_views_counter, tag_unique_views_ip_block FROM $t_recipes_tags_unique WHERE tag_language=$l_mysql AND tag_title_clean=$tag_title_clean_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_tag_id, $get_tag_language, $get_tag_title, $get_tag_title_clean, $get_tag_number_of_recipes, $get_tag_last_clicked_week, $get_tag_unique_views_counter, $get_tag_unique_views_ip_block) = $row;
if($get_tag_id == ""){
	


	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$l_recipes - Server error 404";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");
	echo"<p>Tag not found.</p>";

	// Go trough all recipes, find all tags
	$result = mysqli_query($link, "TRUNCATE $t_recipes_tags_unique") or die(mysqli_error($link));
	
	// Loop trought tags and insert them
	$query_t = "SELECT tag_id, tag_language, tag_recipe_id, tag_title, tag_title_clean, tag_user_id FROM $t_recipes_tags";
	$result_t = mysqli_query($link, $query_t);
	while($row_t = mysqli_fetch_row($result_t)) {
		list($get_tag_id, $get_tag_language, $get_tag_recipe_id, $get_tag_title, $get_tag_title_clean, $get_tag_user_id) = $row_t;

		// Check that recipe exists
		$query_rating = "SELECT recipe_id FROM $t_recipes WHERE recipe_id=$get_tag_recipe_id";
		$result_rating = mysqli_query($link, $query_rating);
		$row_rating = mysqli_fetch_row($result_rating);
		list($get_recipe_id) = $row_rating;
		if($get_recipe_id == ""){
			echo"<div class=\"warning\"><p>Recipe not found for tag $get_tag_title (recipe id was $get_tag_recipe_id and tag id was $get_tag_id)</p></div>";
			$result = mysqli_query($link, "DELETE FROM $t_recipes_tags WHERE tag_id=$get_tag_id") or die(mysqli_error($link));
		}
		else{
			// Check for unique tag
			$inp_tite_mysql = quote_smart($link, $get_tag_title); 
			$inp_tite_clean_mysql = quote_smart($link, $get_tag_title_clean); 
			$inp_language_mysql = quote_smart($link, $get_tag_language); 
			$query_rating = "SELECT tag_id FROM $t_recipes_tags_unique WHERE tag_language=$inp_language_mysql AND tag_title_clean=$inp_tite_clean_mysql";
			$result_rating = mysqli_query($link, $query_rating);
			$row_rating = mysqli_fetch_row($result_rating);
			list($get_recipes_tags_unique_tag_id) = $row_rating;
			if($get_recipes_tags_unique_tag_id == ""){
				echo"<div class=\"success\"><p>Creating unique tag $get_tag_title.</p></div>";

				mysqli_query($link, "INSERT INTO $t_recipes_tags_unique (tag_id, tag_language, tag_title, tag_title_clean) 
							VALUES(NULL, $inp_language_mysql, $inp_tite_mysql, $inp_tite_clean_mysql)") or die(mysqli_error($link));
			} // create unique tag
		}
	}
	
}
else{
	// Update hits
	$inp_ip = $_SERVER['REMOTE_ADDR'];
	$inp_ip = output_html($inp_ip);

	$ip_block_array = explode("\n", $get_tag_unique_views_ip_block);
	$ip_block_array_size = sizeof($ip_block_array);

	if($ip_block_array_size > 10){
		$ip_block_array_size = 5;
	}

	$has_seen_this_before = 0;
	$inp_unique_hits_ip_block = "";
	for($x=0;$x<$ip_block_array_size;$x++){
		if($ip_block_array[$x] == "$inp_ip"){
			$has_seen_this_before = 1;
			break;
		}
		if($inp_unique_hits_ip_block == ""){
			$inp_unique_hits_ip_block = $ip_block_array[$x];
		}
		else{
			$inp_unique_hits_ip_block = $inp_unique_hits_ip_block . "\n" . $ip_block_array[$x];
		}
	}

	if($has_seen_this_before == 0){
		$inp_unique_hits_ip_block = $inp_ip . "\n" . $inp_unique_hits_ip_block;
		$inp_unique_hits_ip_block_mysql = quote_smart($link, $inp_unique_hits_ip_block);
		$inp_unique_hits = $get_tag_unique_views_counter + 1;
		$result = mysqli_query($link, "UPDATE $t_recipes_tags_unique SET tag_unique_views_counter=$inp_unique_hits, tag_unique_views_ip_block=$inp_unique_hits_ip_block_mysql WHERE tag_id=$get_tag_id") or die(mysqli_error($link));
	}



/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$get_tag_title - $l_recipes";
include("$root/_webdesign/header.php");

// Language
include("$root/_admin/_translations/site/$l/recipes/ts_search.php");

/*- Variables ------------------------------------------------------------------------- */
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



/*- Content ---------------------------------------------------------------------------------- */
echo"
<!-- Headline -->
	<div class=\"recipes_headline\">
		<h1>$get_tag_title</h1>

		<!-- Where am I? -->
			<p><b>$l_you_are_here:</b><br />
			<a href=\"index.php?l=$l\">$l_recipes</a>
			&gt;
			<a href=\"view_tag.php?tag=$tag&amp;l=$l\">$get_tag_title</a>
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
					<option value=\"view_tag.php?tag=$tag&amp;l=$l\">- $l_order_by -</option>
					<option value=\"view_tag.php?tag=$tag&amp;order_by=recipe_id&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "recipe_id" OR $order_by == ""){ echo" selected=\"selected\""; } echo">$l_date</option>
					<option value=\"view_tag.php?tag=$tag&amp;order_by=recipe_title&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "recipe_title"){ echo" selected=\"selected\""; } echo">$l_title</option>
					<option value=\"view_tag.php?tag=$tag&amp;order_by=recipe_comments&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "recipe_comments"){ echo" selected=\"selected\""; } echo">$l_comments</option>
					<option value=\"view_tag.php?tag=$tag&amp;order_by=recipe_unique_hits&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "recipe_unique_hits"){ echo" selected=\"selected\""; } echo">$l_unique_hits</option>
					<option value=\"view_tag.php?tag=$tag&amp;order_by=number_serving_calories&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "number_serving_calories"){ echo" selected=\"selected\""; } echo">$l_calories</option>
					<option value=\"view_tag.php?tag=$tag&amp;order_by=number_serving_fat&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "number_serving_fat"){ echo" selected=\"selected\""; } echo">$l_fat</option>
					<option value=\"view_tag.php?tag=$tag&amp;order_by=number_serving_carbs&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "number_serving_carbs"){ echo" selected=\"selected\""; } echo">$l_carbs</option>
					<option value=\"view_tag.php?tag=$tag&amp;order_by=number_serving_proteins&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "number_serving_proteins"){ echo" selected=\"selected\""; } echo">$l_proteins</option>
				</select>
				<select name=\"inp_order_method\" id=\"inp_order_method_select\">
					<option value=\"view_tag.php?tag=$tag&amp;order_by=$order_by&amp;order_method=asc&amp;l=$l\""; if($order_method == "asc"){ echo" selected=\"selected\""; } echo">$l_asc</option>
					<option value=\"view_tag.php?tag=$tag&amp;order_by=$order_by&amp;order_method=desc&amp;l=$l\""; if($order_method == "desc"){ echo" selected=\"selected\""; } echo">$l_desc</option>
				</select> 
				</p>
        		</form>
		<!-- //Order -->
	</div>
	<div class=\"clear\"></div>
<!-- //Headline -->
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
		<input type=\"checkbox\" name=\"inp_show_hundred_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=hundred_metric&amp;process=1&amp;referer=view_tag&amp;tag=$tag&amp;l=$l\""; if($get_current_view_hundred_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_hundred
		<input type=\"checkbox\" name=\"inp_show_pcs_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=serving&amp;process=1&amp;referer=view_tag&amp;tag=$tag&amp;l=$l\""; if($get_current_view_serving == "1"){ echo" checked=\"checked\""; } echo" /> $l_serving
		<input type=\"checkbox\" name=\"inp_show_metric_us_and_or_pcs\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=eight_us&amp;process=1&amp;referer=view_tag&amp;tag=$tag&amp;l=$l\""; if($get_current_view_eight_us == "1"){ echo" checked=\"checked\""; } echo" /> $l_eight
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

	// Select recipes
	$x = 0;
	$count_recipes = 0;
	$query = "SELECT $t_recipes_tags.tag_id, $t_recipes.recipe_id, $t_recipes.recipe_title, $t_recipes.recipe_introduction, $t_recipes.recipe_image_path, $t_recipes.recipe_image_h_a, $t_recipes.recipe_thumb_h_a_278x156, $t_recipes.recipe_unique_hits FROM $t_recipes_tags INNER JOIN $t_recipes ON $t_recipes_tags.tag_recipe_id=$t_recipes.recipe_id WHERE $t_recipes_tags.tag_language=$l_mysql AND $t_recipes_tags.tag_title_clean=$tag_title_clean_mysql";

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
		list($get_tag_id, $get_recipe_id, $get_recipe_title, $get_recipe_introduction, $get_recipe_image_path, $get_recipe_image_h_a, $get_recipe_thumb_h_a_278x156, $get_recipe_unique_hits) = $row;

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


			

			// Recipe thumb
			if($get_recipe_thumb_h_a_278x156 == "" OR !(file_exists("$root/$get_recipe_image_path/$get_recipe_thumb_h_a_278x156"))){
				$inp_new_x = 278; // from HD 1920x1080
				$inp_new_y = 156;

				$ext = get_extension($get_recipe_image_h_a);

				echo"<div class=\"info\"><p>Creating recipe thumb $inp_new_x x $inp_new_y  px</p></div>";

				$thumb = $get_recipe_id . "_thumb_h_a_" . $inp_new_x . "x" . $inp_new_y . ".$ext";
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
					<p><a id=\"recipe$get_recipe_id\"></a>
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
							}
							echo"
					<!-- //Numbers -->

				</div>
			";

			// Increment
			$x++;
			$count_recipes = $count_recipes+1;
		
			// Reset
			if($x == 4){
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
	echo"
	<div class=\"clear\"></div>
	";



	// Update count, year, month, week
	if($count_recipes != "$get_tag_number_of_recipes" OR $week != "$get_tag_last_clicked_week"){
		$result = mysqli_query($link, "UPDATE $t_recipes_tags_unique SET 
					tag_number_of_recipes=$count_recipes, 
					tag_last_clicked_year=$year, 
					tag_last_clicked_month=$month, 
					tag_last_clicked_week=$week
					WHERE tag_id=$get_tag_id") or die(mysqli_error($link));
	}
	if($count_recipes == "0"){
		echo"<div class=\"info\"><p>No recipes found for this tag</p></div>";
		$result = mysqli_query($link, "TRUNCATE $t_recipes_tags_unique") or die(mysqli_error($link));
	
		// Loop trought tags and insert them
		
		$query_t = "SELECT tag_id, tag_language, tag_recipe_id, tag_title, tag_title_clean, tag_user_id FROM $t_recipes_tags";
		$result_t = mysqli_query($link, $query_t);
		while($row_t = mysqli_fetch_row($result_t)) {
			list($get_tag_id, $get_tag_language, $get_tag_recipe_id, $get_tag_title, $get_tag_title_clean, $get_tag_user_id) = $row_t;

			// Check that recipe exists
			$query_rating = "SELECT recipe_id FROM $t_recipes WHERE recipe_id=$get_tag_recipe_id";
			$result_rating = mysqli_query($link, $query_rating);
			$row_rating = mysqli_fetch_row($result_rating);
			list($get_recipe_id) = $row_rating;
			if($get_recipe_id == ""){
				echo"<div class=\"warning\"><p>Recipe not found for tag $get_tag_title (recipe id was $get_tag_recipe_id and tag id was $get_tag_id)</p></div>";
				$result = mysqli_query($link, "DELETE FROM $t_recipes_tags WHERE tag_id=$get_tag_id") or die(mysqli_error($link));
			}
			else{
				// Check for unique tag
				$inp_tite_mysql = quote_smart($link, $get_tag_title); 
				$inp_tite_clean_mysql = quote_smart($link, $get_tag_title_clean); 
				$inp_language_mysql = quote_smart($link, $get_tag_language); 

				$query_rating = "SELECT tag_id FROM $t_recipes_tags_unique WHERE tag_language=$inp_language_mysql AND tag_title_clean=$inp_tite_clean_mysql";
				$result_rating = mysqli_query($link, $query_rating);
				$row_rating = mysqli_fetch_row($result_rating);
				list($get_recipes_tags_unique_tag_id) = $row_rating;
				if($get_recipes_tags_unique_tag_id == ""){
					echo"<div class=\"success\"><p>Creating unique tag $get_tag_title.</p></div>";

					mysqli_query($link, "INSERT INTO $t_recipes_tags_unique (tag_id, tag_language, tag_title, tag_title_clean) 
								VALUES(NULL, $inp_language_mysql, $inp_tite_mysql, $inp_tite_clean_mysql)") or die(mysqli_error($link));
				} // create unique tag
			}
		}
	} // no recipes
} // tag found

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>