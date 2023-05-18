<?php
/**
*
* File: _food/my_food.php
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

/*- Tables ---------------------------------------------------------------------------- */
include("_tables_food.php");


/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);

if(isset($_GET['order_by'])) {
	$order_by = $_GET['order_by'];
	$order_by = strip_tags(stripslashes($order_by));
}
else{
	$order_by = "food_id";
}
if(isset($_GET['order_method'])) {
	$order_method = $_GET['order_method'];
	$order_method = strip_tags(stripslashes($order_method));
}
else{
	$order_method = "DESC";
}

// Title
$query = "SELECT title_id, title_value FROM $t_food_titles WHERE title_language=$l_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_title_id, $get_current_title_value) = $row;

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/food/ts_index.php");
include("$root/_admin/_translations/site/$l/food/ts_new_food.php");
include("$root/_admin/_translations/site/$l/food/ts_search.php");



/*- Functions ------------------------------------------------------------------------ */
	// Get extention
	function getExtension($str) {
		$i = strrpos($str,".");
		if (!$i) { return ""; } 
		$l = strlen($str) - $i;
		$ext = substr($str,$i+1,$l);
		return $ext;
	}



/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_my_food - $get_current_title_value";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");




// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);

	echo"
	<!-- Headline -->
		<h1>$l_my_food</h1>
	<!-- //Headline -->

	<!-- Where am I ? -->
		<p><b>$l_you_are_here:</b><br />
		<a href=\"index.php?l=$l\">$get_current_title_value</a>
		&gt;
		<a href=\"my_food.php?l=$l\">$l_my_food</a>
		</p>
	<!-- //Where am I ? -->




	<!-- Actions and Sorting -->
		<div style=\"float: left;\">
			<p>
			<a href=\"new_food.php?l=$l\" class=\"btn_default\">$l_new_food</a>
			<a href=\"my_favorites.php?l=$l\" class=\"btn_default\">$l_my_favorites</a>
			<a href=\"my_stores.php?l=$l\" class=\"btn_default\">$l_my_stores</a>
			</p>
		</div>
		<div style=\"float: right;\">
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
				<option value=\"my_food.php?l=$l\">- $l_order_by -</option>
				<option value=\"my_food.php?order_by=food_id&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "food_id" OR $order_by == ""){ echo" selected=\"selected\""; } echo">$l_date</option>
				<option value=\"my_food.php?order_by=food_name&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "food_name"){ echo" selected=\"selected\""; } echo">$l_name</option>
				<option value=\"my_food.php?order_by=food_unique_hits&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "food_unique_hits"){ echo" selected=\"selected\""; } echo">$l_unique_hits</option>
				<option value=\"my_food.php?order_by=food_energy&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "food_energy"){ echo" selected=\"selected\""; } echo">$l_calories</option>
				<option value=\"my_food.php?order_by=food_fat&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "food_fat"){ echo" selected=\"selected\""; } echo">$l_fat</option>
				<option value=\"my_food.php?order_by=food_carbohydrates&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "food_carbohydrates"){ echo" selected=\"selected\""; } echo">$l_carbs</option>
				<option value=\"my_food.php?order_by=food_proteins&amp;order_method=$order_method&amp;l=$l\""; if($order_by == "food_proteins"){ echo" selected=\"selected\""; } echo">$l_proteins</option>
			</select>
			<select name=\"inp_order_method\" id=\"inp_order_method_select\">
				<option value=\"my_food.php?order_by=$order_by&amp;order_method=asc&amp;l=$l\""; if($order_method == "asc"){ echo" selected=\"selected\""; } echo">$l_asc</option>
				<option value=\"my_food.php?order_by=$order_by&amp;order_method=desc&amp;l=$l\""; if($order_method == "desc" OR $order_method == ""){ echo" selected=\"selected\""; } echo">$l_desc</option>
			</select>
			</p>
        		</form>
		</div>
		<div class=\"clear\" style=\"height:10px;\"></div>
	<!-- //Actions and Sorting -->

	<!-- My food -->
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>$l_food</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_per_hundred</span>
		   </th>
		   <th scope=\"col\">
			<span title=\"$l_unique_hits\">$l_unique</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_actions</span>
		   </th>
		  </tr>
		</thead>
		<tbody>


		";
		// Set layout
		$x = 0;

		// Get food
		$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_text, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_numbers_entered_method, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_trans_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_added_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_trans_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_added_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_score_place_in_sub_category, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_trans_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_added_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_trans_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_added_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_energy_net_content, food_fat_net_content, food_saturated_fat_net_content, food_trans_fat_net_content, food_monounsaturated_fat_net_content, food_polyunsaturated_fat_net_content, food_cholesterol_net_content, food_carbohydrates_net_content, food_carbohydrates_of_which_sugars_net_content, food_added_sugars_net_content, food_dietary_fiber_net_content, food_proteins_net_content, food_salt_net_content, food_sodium_net_content, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_no_of_comments, food_stars, food_stars_sum, food_comments_multiplied_stars, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_user_id=$my_user_id_mysql AND food_language=$l_mysql";
		// Order
		if($order_by != ""){
			if($order_method == "asc"){
				$order_method_mysql = "ASC";
			}
			else{
				$order_method_mysql = "DESC";
			}

			if($order_by == "food_id" OR $order_by == "food_name" OR $order_by == "food_unique_hits"){
				$order_by_mysql = "$order_by";
			}
			else{
				$order_by_mysql = "food_id";
			}
			$query = $query . " ORDER BY $order_by_mysql $order_method_mysql";
		}


		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_text, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_numbers_entered_method, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_trans_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_added_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_trans_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_added_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_score_place_in_sub_category, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_trans_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_added_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_trans_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_added_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_energy_net_content, $get_food_fat_net_content, $get_food_saturated_fat_net_content, $get_food_trans_fat_net_content, $get_food_monounsaturated_fat_net_content, $get_food_polyunsaturated_fat_net_content, $get_food_cholesterol_net_content, $get_food_carbohydrates_net_content, $get_food_carbohydrates_of_which_sugars_net_content, $get_food_added_sugars_net_content, $get_food_dietary_fiber_net_content, $get_food_proteins_net_content, $get_food_salt_net_content, $get_food_sodium_net_content, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_no_of_comments, $get_food_stars, $get_food_stars_sum, $get_food_comments_multiplied_stars, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row;
			
			// Style
			if(isset($style) && $style == ""){
				$style = "odd";
			}
			else{
				$style = "";
			}

		
			// Thumb small
			if(file_exists("$root/$get_food_image_path/$get_food_image_a") && $get_food_image_a != ""){
				if(!(file_exists("../$get_food_image_path/$get_food_thumb_a_small")) OR $get_food_thumb_a_small == ""){

					$ext = get_extension("$get_food_image_a");
					$inp_thumb_name = str_replace(".$ext", "", $get_food_image_a);
					$get_food_thumb_a_small = $inp_thumb_name . "_thumb_132x132." . $ext;
					$inp_food_thumb_a_small_mysql = quote_smart($link, $get_food_thumb_a_small);
					$result_update = mysqli_query($link, "UPDATE $t_food_index SET food_thumb_a_small=$inp_food_thumb_a_small_mysql WHERE food_id=$get_food_id") or die(mysqli_error($link));
				
					resize_crop_image(132, 132, "$root/$get_food_image_path/$get_food_image_a", "$root/$get_food_image_path/$get_food_thumb_a_small");
				}

				// Thumb medium
				if(!(file_exists("../$get_food_image_path/$get_food_thumb_a_medium")) OR $get_food_thumb_a_medium == ""){
					$ext = get_extension("$get_food_image_a");
					$inp_thumb_name = str_replace(".$ext", "", $get_food_image_a);
					$get_food_thumb_a_medium = $inp_thumb_name . "_thumb_200x200." . $ext;
					$inp_food_thumb_a_medium_mysql = quote_smart($link, $get_food_thumb_a_medium);
					$result_update = mysqli_query($link, "UPDATE $t_food_index SET food_thumb_a_medium=$inp_food_thumb_a_medium_mysql WHERE food_id=$get_food_id") or die(mysqli_error($link));
				
					resize_crop_image(200, 200, "$root/$get_food_image_path/$get_food_image_a", "$root/$get_food_image_path/$get_food_thumb_a_medium");
				}

				// Thumb large
				if(!(file_exists("../$get_food_image_path/$get_food_thumb_a_large")) OR $get_food_thumb_a_large == ""){
					$ext = get_extension("$get_food_image_a");
					$inp_thumb_name = str_replace(".$ext", "", $get_food_image_a);
					$get_food_thumb_a_large = $inp_thumb_name . "_thumb_420x283." . $ext;
					$inp_food_thumb_a_large_mysql = quote_smart($link, $get_food_thumb_a_large);
					$result_update = mysqli_query($link, "UPDATE $t_food_index SET food_thumb_a_large=$inp_food_thumb_a_large_mysql WHERE food_id=$get_food_id") or die(mysqli_error($link));
				
					resize_crop_image(420, 283, "$root/$get_food_image_path/$get_food_image_a", "$root/$get_food_image_path/$get_food_thumb_a_large");
				}
			}
			else{
				if(!(file_exists("$root/$get_food_image_path/$get_food_image_a")) OR $get_food_image_a == ""){
					echo"<div class=\"info\"><p>Main image of <a href=\"edit_food.php?food_id=$get_food_id&amp;l=$l\" style=\"font-weight:bold;\">$get_food_manufacturer_name $get_food_name</a> doesnt exists. Please upload a new image! </p></div>";
					$result_update = mysqli_query($link, "UPDATE $t_food_index SET food_image_a='', food_thumb_a_small='', food_thumb_a_medium='', food_thumb_a_large='' WHERE food_id=$get_food_id") or die(mysqli_error($link));
				}
			}

			echo"
			<tr>
			  <td class=\"$style\">
				 <table>
				  <tr>
				   <td style=\"padding-right: 10px;\">
					";
					
					// Thumb
					if($get_food_image_a != "" && file_exists("../$get_food_image_path/$get_food_image_a")){
						
						echo"<a href=\"$root/food/view_food.php?food_id=$get_food_id&amp;main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;l=$get_food_language\"><img src=\"../$get_food_image_path/$get_food_thumb_a_small\" alt=\"$get_food_thumb_a_small\" style=\"margin-bottom: 5px;\" /></a><br />";
					}
					else{
						echo"<a href=\"$root/food/view_food.php?food_id=$get_food_id&amp;main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;l=$get_food_language\"><img src=\"_gfx/no_thumb.png\" alt=\"$get_food_image_a\" style=\"margin-bottom: 5px;\" /></a><br />";
					}


					echo"
				   </td>
				   <td>
					<a href=\"$root/food/view_food.php?food_id=$get_food_id&amp;main_category_id=$get_food_main_category_id&amp;sub_category_id=$get_food_sub_category_id&amp;l=$get_food_language\" class=\"recipe_open_category_a\">$get_food_manufacturer_name $get_food_name</a><br />
					$get_food_description
					</p>
				   </td>
				  </tr>
				 </table>
			
			  </td>
			  <td class=\"$style\" style=\"text-align: center;\">
				<table>
				 <tr>
				  <td style=\"padding-right: 10px;text-align: center;\">
					<span>$get_food_energy_metric</span>
				  </td>
				  <td style=\"padding-right: 10px;text-align: center;\">
					<span>$get_food_fat_metric</span>
				  </td>
				  <td style=\"padding-right: 10px;text-align: center;\">
					<span>$get_food_carbohydrates_metric</span>
				  </td>
				  <td style=\"text-align: center;\">
					<span>$get_food_proteins_metric</span>
				  </td>
				 </tr>
				 <tr>
				  <td style=\"padding-right: 10px;text-align: center;\">
					<span class=\"grey_smal\">$l_cal_lowercase</span>
				  </td>
				  <td style=\"padding-right: 10px;text-align: center;\">
					<span class=\"grey_smal\">$l_fat_lowercase</span>
				  </td>
				  <td style=\"padding-right: 10px;text-align: center;\">
					<span class=\"grey_smal\">$l_carb_lowercase</span>
				  </td>
				  <td style=\"text-align: center;\">
					<span class=\"grey_smal\">$l_proteins_lowercase</span>
				  </td>
				 </tr>
				</table>
			  </td>
			  <td class=\"$style\" style=\"text-align: center;\">
				<span>$get_food_unique_hits</span>
			  </td>
			  <td class=\"$style\">
				<span>
				<a href=\"edit_food.php?food_id=$get_food_id&amp;l=$l\">$l_edit</a>
				&middot;
				<a href=\"delete_food.php?food_id=$get_food_id&amp;l=$l\">$l_delete</a>
				</span>
			 </td>
			</tr>
			";
		}

		echo"
		 </tbody>
		</table>
	";
	echo"
	<!-- //My food -->
	";

}
else{
	echo"
	<h1>
	<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=food/my_food.php\">

	<p>Please log in...</p>
	";
}

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>