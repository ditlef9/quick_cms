<?php 
/**
*
* File: food/new_food_3_select_sub_category.php
* Version 1.0.0
* Date 23:59 27.11.2017
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
include("_tables_food.php");

/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";



/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/food/ts_index.php");
include("$root/_admin/_translations/site/$l/food/ts_new_food.php");


/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['barcode'])){
	$barcode = $_GET['barcode'];
	$barcode = output_html($barcode);
	if($barcode != "" && !(is_numeric($barcode))){
		echo"barcode_have_to_be_numeric";
		exit;
	}
}
else{
	$barcode = "";
}

if(isset($_GET['main_category_id'])){
	$main_category_id= $_GET['main_category_id'];
	$main_category_id = strip_tags(stripslashes($main_category_id));
}
else{
	$main_category_id = "";
}

$tabindex = 0;
$l_mysql = quote_smart($link, $l);

// Title
$query = "SELECT title_id, title_value FROM $t_food_titles WHERE title_language=$l_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_title_id, $get_current_title_value) = $row;


/*- Main category ------------------------------------------------------------------------- */
// Select category
$main_category_id_mysql = quote_smart($link, $main_category_id);
$query = "SELECT main_category_id, main_category_name, main_category_icon_path, main_category_icon_inactive_32x32, main_category_icon_active_32x32, main_category_icon_inactive_48x48, main_category_icon_active_48x48, main_category_age_limit FROM $t_food_categories_main WHERE main_category_id=$main_category_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_main_category_id, $get_current_main_category_name, $get_current_main_category_icon_path, $get_current_main_category_icon_inactive_32x32, $get_current_main_category_icon_active_32x32, $get_current_main_category_icon_inactive_48x48, $get_current_main_category_icon_active_48x48, $get_current_main_category_age_limit) = $row;
if($get_current_main_category_id == ""){
	$website_title = "Server error 404 - $get_current_title_value";
}
else{
	// Translation
	$query_t = "SELECT main_category_translation_id, main_category_id, main_category_translation_language, main_category_translation_value, main_category_translation_no_food, main_category_unique_hits, main_category_unique_hits_ip_block, main_category_unique_hits_this_year, main_category_unique_hits_this_year_year, main_category_calories_min_100_g, main_category_calories_med_100_g, main_category_calories_max_100_g, main_category_calories_p_ten_percentage_100_g, main_category_calories_m_ten_percentage_100_g, main_category_fat_min_100_g, main_category_fat_med_100_g, main_category_fat_max_100_g, main_category_fat_p_ten_percentage_100_g, main_category_fat_m_ten_percentage_100_g, main_category_saturated_fat_min_100_g, main_category_saturated_fat_med_100_g, main_category_saturated_fat_max_100_g, main_category_saturated_fat_p_ten_percentage_100_g, main_category_saturated_fat_m_ten_percentage_100_g, main_category_trans_fat_min_100_g, main_category_trans_fat_med_100_g, main_category_trans_fat_max_100_g, main_category_trans_fat_p_ten_percentage_100_g, main_category_trans_fat_m_ten_percentage_100_g, main_category_monounsaturated_fat_min_100_g, main_category_monounsaturated_fat_med_100_g, main_category_monounsaturated_fat_max_100_g, main_category_monounsaturated_fat_p_ten_percentage_100_g, main_category_monounsaturated_fat_m_ten_percentage_100_g, main_category_polyunsaturated_fat_min_100_g, main_category_polyunsaturated_fat_med_100_g, main_category_polyunsaturated_fat_max_100_g, main_category_polyunsaturated_fat_p_ten_percentage_100_g, main_category_polyunsaturated_fat_m_ten_percentage_100_g, main_category_cholesterol_min_100_g, main_category_cholesterol_med_100_g, main_category_cholesterol_max_100_g, main_category_cholesterol_p_ten_percentage_100_g, main_category_cholesterol_m_ten_percentage_100_g, main_category_carb_min_100_g, main_category_carb_med_100_g, main_category_carb_max_100_g, main_category_carb_p_ten_percentage_100_g, main_category_carb_m_ten_percentage_100_g, main_category_carb_of_which_sugars_min_100_g, main_category_carb_of_which_sugars_med_100_g, main_category_carb_of_which_sugars_max_100_g, main_category_carb_of_which_sugars_p_ten_percentage_100_g, main_category_carb_of_which_sugars_m_ten_percentage_100_g, main_category_added_sugars_min_100_g, main_category_added_sugars_med_100_g, main_category_added_sugars_max_100_g, main_category_added_sugars_p_ten_percentage_100_g, main_category_added_sugars_m_ten_percentage_100_g, main_category_dietary_fiber_min_100_g, main_category_dietary_fiber_med_100_g, main_category_dietary_fiber_max_100_g, main_category_dietary_fiber_p_ten_percentage_100_g, main_category_dietary_fiber_m_ten_percentage_100_g, main_category_proteins_min_100_g, main_category_proteins_med_100_g, main_category_proteins_max_100_g, main_category_proteins_p_ten_percentage_100_g, main_category_proteins_m_ten_percentage_100_g, main_category_salt_min_100_g, main_category_salt_med_100_g, main_category_salt_max_100_g, main_category_salt_p_ten_percentage_100_g, main_category_salt_m_ten_percentage_100_g, main_category_sodium_min_100_g, main_category_sodium_med_100_g, main_category_sodium_max_100_g, main_category_sodium_p_ten_percentage_100_g, main_category_sodium_m_ten_percentage_100_g FROM $t_food_categories_main_translations WHERE main_category_id=$get_current_main_category_id AND main_category_translation_language=$l_mysql";
	$result_t = mysqli_query($link, $query_t);
	$row_t = mysqli_fetch_row($result_t);
	list($get_current_main_category_translation_id, $get_current_main_category_id, $get_current_main_category_translation_language, $get_current_main_category_translation_value, $get_current_main_category_translation_no_food, $get_current_main_category_unique_hits, $get_current_main_category_unique_hits_ip_block, $get_current_main_category_unique_hits_this_year, $get_current_main_category_unique_hits_this_year_year, $get_current_main_category_calories_min_100_g, $get_current_main_category_calories_med_100_g, $get_current_main_category_calories_max_100_g, $get_current_main_category_calories_p_ten_percentage_100_g, $get_current_main_category_calories_m_ten_percentage_100_g, $get_current_main_category_fat_min_100_g, $get_current_main_category_fat_med_100_g, $get_current_main_category_fat_max_100_g, $get_current_main_category_fat_p_ten_percentage_100_g, $get_current_main_category_fat_m_ten_percentage_100_g, $get_current_main_category_saturated_fat_min_100_g, $get_current_main_category_saturated_fat_med_100_g, $get_current_main_category_saturated_fat_max_100_g, $get_current_main_category_saturated_fat_p_ten_percentage_100_g, $get_current_main_category_saturated_fat_m_ten_percentage_100_g, $get_current_main_category_trans_fat_min_100_g, $get_current_main_category_trans_fat_med_100_g, $get_current_main_category_trans_fat_max_100_g, $get_current_main_category_trans_fat_p_ten_percentage_100_g, $get_current_main_category_trans_fat_m_ten_percentage_100_g, $get_current_main_category_monounsaturated_fat_min_100_g, $get_current_main_category_monounsaturated_fat_med_100_g, $get_current_main_category_monounsaturated_fat_max_100_g, $get_current_main_category_monounsaturated_fat_p_ten_percentage_100_g, $get_current_main_category_monounsaturated_fat_m_ten_percentage_100_g, $get_current_main_category_polyunsaturated_fat_min_100_g, $get_current_main_category_polyunsaturated_fat_med_100_g, $get_current_main_category_polyunsaturated_fat_max_100_g, $get_current_main_category_polyunsaturated_fat_p_ten_percentage_100_g, $get_current_main_category_polyunsaturated_fat_m_ten_percentage_100_g, $get_current_main_category_cholesterol_min_100_g, $get_current_main_category_cholesterol_med_100_g, $get_current_main_category_cholesterol_max_100_g, $get_current_main_category_cholesterol_p_ten_percentage_100_g, $get_current_main_category_cholesterol_m_ten_percentage_100_g, $get_current_main_category_carb_min_100_g, $get_current_main_category_carb_med_100_g, $get_current_main_category_carb_max_100_g, $get_current_main_category_carb_p_ten_percentage_100_g, $get_current_main_category_carb_m_ten_percentage_100_g, $get_current_main_category_carb_of_which_sugars_min_100_g, $get_current_main_category_carb_of_which_sugars_med_100_g, $get_current_main_category_carb_of_which_sugars_max_100_g, $get_current_main_category_carb_of_which_sugars_p_ten_percentage_100_g, $get_current_main_category_carb_of_which_sugars_m_ten_percentage_100_g, $get_current_main_category_added_sugars_min_100_g, $get_current_main_category_added_sugars_med_100_g, $get_current_main_category_added_sugars_max_100_g, $get_current_main_category_added_sugars_p_ten_percentage_100_g, $get_current_main_category_added_sugars_m_ten_percentage_100_g, $get_current_main_category_dietary_fiber_min_100_g, $get_current_main_category_dietary_fiber_med_100_g, $get_current_main_category_dietary_fiber_max_100_g, $get_current_main_category_dietary_fiber_p_ten_percentage_100_g, $get_current_main_category_dietary_fiber_m_ten_percentage_100_g, $get_current_main_category_proteins_min_100_g, $get_current_main_category_proteins_med_100_g, $get_current_main_category_proteins_max_100_g, $get_current_main_category_proteins_p_ten_percentage_100_g, $get_current_main_category_proteins_m_ten_percentage_100_g, $get_current_main_category_salt_min_100_g, $get_current_main_category_salt_med_100_g, $get_current_main_category_salt_max_100_g, $get_current_main_category_salt_p_ten_percentage_100_g, $get_current_main_category_salt_m_ten_percentage_100_g, $get_current_main_category_sodium_min_100_g, $get_current_main_category_sodium_med_100_g, $get_current_main_category_sodium_max_100_g, $get_current_main_category_sodium_p_ten_percentage_100_g, $get_current_main_category_sodium_m_ten_percentage_100_g) = $row_t;
	$website_title = "$get_current_main_category_translation_value - $get_current_title_value";
}


/*- Headers ---------------------------------------------------------------------------------- */
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	


	if($get_current_main_category_id == ""){
		echo"
		<h1>Server error 404</h1>
		<p>Category not found.</p>

		<p><a href=\"index.php?l=$l\">Categories</a></p>
		";
	}
	else{
		if($process == "1"){
			$inp_sub_category_id = $_POST['inp_sub_category_id'];
			$inp_sub_category_id = output_html($inp_sub_category_id);
	
			$sub_id = substr($inp_sub_category_id, strrpos($inp_sub_category_id, 'sub_category_id=') + 1); // ub_category_id=7&l=no 
			$sub_id = str_replace("ub_category_id=", "", $sub_id);
			$sub_id = str_replace("&amp;main_category_id=$main_category_id", "", $sub_id);
			$sub_id = str_replace("&amp;barcode=$barcode", "", $sub_id);
			$sub_id = str_replace("&amp;l=$l", "", $sub_id);
			
			if(!(is_numeric($sub_id))){
				echo"sub_id_have_to_be_numeric";
				exit;
			}
		
			$url = "new_food_4_general_information.php?main_category_id=$main_category_id&sub_category_id=$sub_id"; 
			if($barcode != ""){ $url = $url . "&barcode=$barcode"; }
			$url = $url . "&l=$l";
			header("Location: $url");
			exit;
		}

		echo"
		<h1>$l_new_food</h1>
	

		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_recipe_title\"]').focus();
			});
			</script>
		<!-- //Focus -->

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

		<!-- Form -->


			<!-- Scripts-->
			<script>
				\$(document).ready(function(){
					\$('[name=\"sub_category_id\"]').focus();
				});
				\$(function(){
					\$('.on_select_go_to_url').on('change', function () {
						var url = \$(this).val(); // get selected value
						if (url) { // require a URL
 							window.location = url; // redirect
						}
						return false;
					});
				});
			</script>
			<!-- //Scripts---->

			<h2>$l_categorization</h2>
			<form method=\"post\" action=\"new_food_3_select_sub_category.php?main_category_id=$main_category_id"; if($barcode != ""){ echo"&amp;barcode=$barcode"; } echo"&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			<table>
			 <tr>
			  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
				<p><b>$l_category:</b></p>
			  </td>
			  <td>

				<p>
				<select name=\"main_category_id\" class=\"on_select_go_to_url\">
				<option value=\"$l\">- $l_please_select -</option>
				<option value=\"$l\"> </option>
				";

				// Get main categories
				$query = "SELECT main_category_id, main_category_name FROM $t_food_categories_main ORDER BY main_category_name ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_main_category_id, $get_main_category_name) = $row;
				
					// Translation
					$query_t = "SELECT main_category_translation_id, main_category_translation_value FROM $t_food_categories_main_translations WHERE main_category_id=$get_main_category_id AND main_category_translation_language=$l_mysql";
					$result_t = mysqli_query($link, $query_t);
					$row_t = mysqli_fetch_row($result_t);
					list($get_main_category_translation_id, $get_main_category_translation_value) = $row_t;

					echo"
					<option value=\"new_food_3_select_sub_category.php?main_category_id=$get_main_category_id"; if($barcode != ""){ echo"&amp;barcode=$barcode"; } echo"&amp;l=$l\""; if($main_category_id == "$get_main_category_id"){ echo" selected=\"selected\""; } echo">$get_main_category_translation_value</option>\n";
				
				}


				echo"
				</select>
				</p>
			  </td>
			 </tr>
			 <tr>
			  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
				<p><b>$l_sub_category:</b></p>
			  </td>
			  <td>
				<p>
				<select name=\"inp_sub_category_id\" class=\"on_select_go_to_url\">
				<option value=\"$l\">- $l_please_select -</option>
				<option value=\"$l\"> </option>

				";

				// Get sub categories
				$query = "SELECT sub_category_id, sub_category_name, sub_category_symbolic_link_to_category_id FROM $t_food_categories_sub WHERE sub_category_parent_id=$get_current_main_category_id ORDER BY sub_category_name ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_sub_category_id, $get_sub_category_name, $get_sub_category_symbolic_link_to_category_id) = $row;
				
					// Translation
					$query_t = "SELECT sub_category_translation_id, sub_category_translation_value FROM $t_food_categories_sub_translations WHERE sub_category_id=$get_sub_category_id AND sub_category_translation_language=$l_mysql";
					$result_t = mysqli_query($link, $query_t);
					$row_t = mysqli_fetch_row($result_t);
					list($get_sub_category_translation_id, $get_sub_category_translation_value) = $row_t;

					// Link
					if($get_sub_category_symbolic_link_to_category_id == "0"){
						$category_id = "$get_sub_category_id";
					}
					else{
						$category_id = "$get_sub_category_symbolic_link_to_category_id";
					}



					echo"
					<option value=\"new_food_4_general_information.php?main_category_id=$main_category_id&amp;sub_category_id=$category_id"; if($barcode != ""){ echo"&amp;barcode=$barcode"; } echo"&amp;l=$l\">$get_sub_category_translation_value</option>\n";
					
				}
				echo"
				</select>
				</p>

			  </td>
			 </tr>
			</table>

			<p>
			<input type=\"submit\" value=\"$l_continue\" class=\"btn_default\" />
			</p>

			</form>
		<!-- //Form -->
		";
	} // mode == ""
}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/food/new_food.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>