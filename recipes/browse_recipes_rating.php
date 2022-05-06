<?php 
/**
*
* File: recipes/browse_recipes_rating.php
* Version 1.0.0
* Date 23:50 12.01.2021
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

/*- Tables ------------------------------------------------------------------------ */
$t_recipes_tags_unique			= $mysqlPrefixSav . "recipes_tags_unique";

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/recipes/ts_index.php");
include("$root/_admin/_translations/site/$l/recipes/ts_browse_recipes_newest.php");

/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);



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
if(isset($_GET['period'])) {
	$period = $_GET['period'];
	$period = strip_tags(stripslashes($period));
}
else{
	$period = "";
}
if(isset($_GET['year'])) {
	$year = $_GET['year'];
	$year = strip_tags(stripslashes($year));
	if(!(is_numeric($year))){
		echo"year not numeric";
		die;
	}
}
else{
	$year = date("Y");
}

$month = date("m");

/*- Tables ---------------------------------------------------------------------------------- */
include("_tables.php");

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_rating - $l_browse_recipes - $l_recipes";
include("$root/_webdesign/header.php");


/*- Content ---------------------------------------------------------------------------------- */
echo"
<!-- Headline, buttons, search -->
	<div class=\"recipes_headline\">
		<h1>$l_browse_recipes</h1>
		<!-- Where am I? -->
			<p><b>$l_you_are_here:</b><br />
			<a href=\"index.php?l=$l\">$l_recipes</a>
			&gt;
			<a href=\"browse_recipes_rating.php?l=$l\">$l_rating</a>
			</p>
		<!-- //Where am I? -->

	</div>
	<div class=\"recipes_menu\">
		<!-- Recipes menu -->
			<script>
			\$(document).ready(function() {
				\$('#toggle_recipes_search').click(function() {
					\$(\".recipes_search\").fadeIn();
					\$(\"#inp_recipe_query\").focus();
				})
			});
			</script>


			<p>
			<a href=\"#\" id=\"toggle_recipes_search\" class=\"btn_default\">$l_search</a>
			<a href=\"$root/recipes/my_recipes.php?l=$l\" class=\"btn_default\">$l_my_recipes</a>
			<a href=\"$root/recipes/my_favorites.php?l=$l\" class=\"btn_default\">$l_my_favorites</a>
			<a href=\"$root/recipes/submit_recipe.php?l=$l\" class=\"btn_default\">$l_submit_recipe</a>
			</p>
		<!-- //Recipes menu -->
	</div>
	<div class=\"clear\"></div>
<!-- //Headline, buttons, search -->


<!-- Search -->
	<div class=\"recipes_search\">
		<form method=\"get\" action=\"search.php\" enctype=\"multipart/form-data\">
		<p>
		<b>$l_search_for_recipe:</b><br />
		
		<input type=\"text\" name=\"q\" value=\"\" size=\"15\" style=\"width: 50%;\" id=\"inp_recipe_query\" />
		<input type=\"submit\" value=\"$l_search\" class=\"btn_default\" />
		</p>
		</form>


		<!-- Search for recipe Autocomplete -->
			<script id=\"source\" language=\"javascript\" type=\"text/javascript\">
			\$(document).ready(function () {
				\$('#inp_recipe_query').keyup(function () {
					// getting the value that user typed
       					var searchString    = \$(\"#inp_recipe_query\").val();

 					// forming the queryString
      					var data            = 'l=$l&q='+ searchString;
         
        				// if searchString is not empty
        				if(searchString) {
						\$(\"#inp_recipe_query_results\").css('visibility','visible');

           					// ajax call
            					\$.ajax({
                					type: \"GET\",
               						url: \"search_for_recipe_autocomplete.php\",
                					data: data,
							beforeSend: function(html) { // this happens before actual call
								\$(\"#inp_recipe_query_results\").html(''); 
							},
               						success: function(html){
                    						\$(\"#inp_recipe_query_results\").append(html);
              						}
            					});
       					}
        				return false;
            			});
         		   });
			</script>
			<div id=\"inp_recipe_query_results\"></div>
			<div class=\"clear\"></div>
		<!-- //Search for recipe Autocomplete -->
		
	</div>
<!-- //Search -->

		<!-- Order -->
			<script>
			\$(function(){
				\$('.on_select_go_to_url').on('change', function () {
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
				<select name=\"inp_selected\" class=\"on_select_go_to_url\">
					<option value=\"browse_recipes_newest.php?l=$l\">- $l_selected -</option>
					<option value=\"browse_recipes_newest.php?order_by=recipe_id&amp;order_method=desc&amp;l=$l\">$l_newest</option>
					<option value=\"browse_recipes_views.php?l=$l\">$l_views</option>
					<option value=\"browse_recipes_comments.php?l=$l\">$l_comments</option>
					<option value=\"browse_recipes_rating.php?l=$l\" selected=\"selected\">$l_rating</option>
				</select>
				<select name=\"inp_period\" class=\"on_select_go_to_url\">
					<option value=\"browse_recipes_rating.php?l=$l\">$l_all_time</option>
				</select>
		<!-- //Order -->


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
	<input type=\"checkbox\" name=\"inp_show_hundred_metric\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=hundred_metric&amp;process=1&amp;referer=browse_recipes_rating&amp;l=$l\""; if($get_current_view_hundred_metric == "1"){ echo" checked=\"checked\""; } echo" /> $l_hundred
	<input type=\"checkbox\" name=\"inp_show_serving\" class=\"onclick_go_to_url\" data-target=\"user_adapted_view.php?set=serving&amp;process=1&amp;referer=browse_recipes_rating&amp;l=$l\""; if($get_current_view_serving == "1"){ echo" checked=\"checked\""; } echo" /> $l_serving
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

<!-- Browse recipes views -->
	";
	

	// Select recipes
	$x = 0;

		$query = "SELECT $t_recipes_rating.rating_id, $t_recipes.recipe_id, $t_recipes.recipe_title, $t_recipes.recipe_category_id, $t_recipes.recipe_introduction, $t_recipes.recipe_image_path, $t_recipes.recipe_image_h_a, $t_recipes.recipe_thumb_h_a_278x156, $t_recipes.recipe_unique_hits FROM $t_recipes_rating JOIN $t_recipes ON $t_recipes_rating.rating_recipe_id=$t_recipes.recipe_id WHERE $t_recipes_rating.rating_recipe_lang=$l_mysql AND $t_recipes.recipe_published=1";
		$query = $query . " ORDER BY $t_recipes_rating.rating_votes_plus_average DESC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_rating_id, $get_recipe_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_introduction, $get_recipe_image_path, $get_recipe_image_h_a, $get_recipe_thumb_h_a_278x156, $get_recipe_unique_hits) = $row;



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

		
				if(!(file_exists("$root/$get_recipe_image_path/$get_recipe_thumb_h_a_278x156"))){
					if($get_recipe_thumb_h_a_278x156 == ""){
						echo"<div class=\"info\">Thumb 278x156 is blank</div>";
						die;
					}
					$inp_new_x = 278;
					$inp_new_y = 156;
					resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_recipe_image_path/$get_recipe_image_h_a", "$root/$get_recipe_image_path/$get_recipe_thumb_h_a_278x156");
				}

				if($x == "0"){
					echo"
					<div class=\"clear\"></div>
					<div class=\"left_center_center_right_left\">
					";
				}
				elseif($x == "1"){
					echo"
					<div class=\"left_center_center_left_right_center\">
					";
				}
				elseif($x == "2"){
					echo"
					<div class=\"left_center_center_right_right_center\">
					";
				}
				elseif($x == "3"){
					echo"
					<div class=\"left_center_center_right_right\">
					";
				}
				echo"
						<p class=\"frontpage_post_image\"><a id=\"recipe$get_recipe_id\"></a>
							<a href=\"$root/recipes/view_recipe.php?recipe_id=$get_recipe_id&amp;l=$l\"><img src=\"$root/$get_recipe_image_path/$get_recipe_thumb_h_a_278x156\" alt=\"$get_recipe_image_h_a\" /></a><br />
						</p>

						<p class=\"frontpage_post_title\">
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
				$x = $x+1;

				if($x == "4"){
					$x = 0;
				}

			} // image
		} // while




	echo"
		<div class=\"clear\"></div>
<!-- //Browse recipes -->


	";


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>