<?php 
/**
*
* File: food/new_food_7_serving_size.php
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

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/food/ts_index.php");
include("$root/_admin/_translations/site/$l/food/ts_new_food.php");


/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['mode'])){
	$mode = $_GET['mode'];
	$mode = output_html($mode);
}
else{
	$mode = "";
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
if(isset($_GET['food_id'])){
	$food_id = $_GET['food_id'];
	$food_id = strip_tags(stripslashes($food_id));
	$food_id_mysql = quote_smart($link, $food_id);
}
else{
	$food_id = "";
}




$tabindex = 0;
$l_mysql = quote_smart($link, $l);


// Title
$query = "SELECT title_id, title_value FROM $t_food_titles WHERE title_language=$l_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_title_id, $get_current_title_value) = $row;

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);

	// Select food
	$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_text, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_numbers_entered_method, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_trans_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_added_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_trans_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_added_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_score_place_in_sub_category, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_trans_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_added_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_trans_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_added_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_energy_net_content, food_fat_net_content, food_saturated_fat_net_content, food_trans_fat_net_content, food_monounsaturated_fat_net_content, food_polyunsaturated_fat_net_content, food_cholesterol_net_content, food_carbohydrates_net_content, food_carbohydrates_of_which_sugars_net_content, food_added_sugars_net_content, food_dietary_fiber_net_content, food_proteins_net_content, food_salt_net_content, food_sodium_net_content, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_no_of_comments, food_stars, food_stars_sum, food_comments_multiplied_stars, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_id=$food_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_food_id, $get_current_food_user_id, $get_current_food_name, $get_current_food_clean_name, $get_current_food_manufacturer_name, $get_current_food_manufacturer_name_and_food_name, $get_current_food_description, $get_current_food_text, $get_current_food_country, $get_current_food_net_content_metric, $get_current_food_net_content_measurement_metric, $get_current_food_net_content_us, $get_current_food_net_content_measurement_us, $get_current_food_net_content_added_measurement, $get_current_food_serving_size_metric, $get_current_food_serving_size_measurement_metric, $get_current_food_serving_size_us, $get_current_food_serving_size_measurement_us, $get_current_food_serving_size_added_measurement, $get_current_food_serving_size_pcs, $get_current_food_serving_size_pcs_measurement, $get_current_food_numbers_entered_method, $get_current_food_energy_metric, $get_current_food_fat_metric, $get_current_food_saturated_fat_metric, $get_current_food_trans_fat_metric, $get_current_food_monounsaturated_fat_metric, $get_current_food_polyunsaturated_fat_metric, $get_current_food_cholesterol_metric, $get_current_food_carbohydrates_metric, $get_current_food_carbohydrates_of_which_sugars_metric, $get_current_food_added_sugars_metric, $get_current_food_dietary_fiber_metric, $get_current_food_proteins_metric, $get_current_food_salt_metric, $get_current_food_sodium_metric, $get_current_food_energy_us, $get_current_food_fat_us, $get_current_food_saturated_fat_us, $get_current_food_trans_fat_us, $get_current_food_monounsaturated_fat_us, $get_current_food_polyunsaturated_fat_us, $get_current_food_cholesterol_us, $get_current_food_carbohydrates_us, $get_current_food_carbohydrates_of_which_sugars_us, $get_current_food_added_sugars_us, $get_current_food_dietary_fiber_us, $get_current_food_proteins_us, $get_current_food_salt_us, $get_current_food_sodium_us, $get_current_food_score, $get_current_food_score_place_in_sub_category, $get_current_food_energy_calculated_metric, $get_current_food_fat_calculated_metric, $get_current_food_saturated_fat_calculated_metric, $get_current_food_trans_fat_calculated_metric, $get_current_food_monounsaturated_fat_calculated_metric, $get_current_food_polyunsaturated_fat_calculated_metric, $get_current_food_cholesterol_calculated_metric, $get_current_food_carbohydrates_calculated_metric, $get_current_food_carbohydrates_of_which_sugars_calculated_metric, $get_current_food_added_sugars_calculated_metric, $get_current_food_dietary_fiber_calculated_metric, $get_current_food_proteins_calculated_metric, $get_current_food_salt_calculated_metric, $get_current_food_sodium_calculated_metric, $get_current_food_energy_calculated_us, $get_current_food_fat_calculated_us, $get_current_food_saturated_fat_calculated_us, $get_current_food_trans_fat_calculated_us, $get_current_food_monounsaturated_fat_calculated_us, $get_current_food_polyunsaturated_fat_calculated_us, $get_current_food_cholesterol_calculated_us, $get_current_food_carbohydrates_calculated_us, $get_current_food_carbohydrates_of_which_sugars_calculated_us, $get_current_food_added_sugars_calculated_us, $get_current_food_dietary_fiber_calculated_us, $get_current_food_proteins_calculated_us, $get_current_food_salt_calculated_us, $get_current_food_sodium_calculated_us, $get_current_food_energy_net_content, $get_current_food_fat_net_content, $get_current_food_saturated_fat_net_content, $get_current_food_trans_fat_net_content, $get_current_food_monounsaturated_fat_net_content, $get_current_food_polyunsaturated_fat_net_content, $get_current_food_cholesterol_net_content, $get_current_food_carbohydrates_net_content, $get_current_food_carbohydrates_of_which_sugars_net_content, $get_current_food_added_sugars_net_content, $get_current_food_dietary_fiber_net_content, $get_current_food_proteins_net_content, $get_current_food_salt_net_content, $get_current_food_sodium_net_content, $get_current_food_barcode, $get_current_food_main_category_id, $get_current_food_sub_category_id, $get_current_food_image_path, $get_current_food_image_a, $get_current_food_thumb_a_small, $get_current_food_thumb_a_medium, $get_current_food_thumb_a_large, $get_current_food_image_b, $get_current_food_thumb_b_small, $get_current_food_thumb_b_medium, $get_current_food_thumb_b_large, $get_current_food_image_c, $get_current_food_thumb_c_small, $get_current_food_thumb_c_medium, $get_current_food_thumb_c_large, $get_current_food_image_d, $get_current_food_thumb_d_small, $get_current_food_thumb_d_medium, $get_current_food_thumb_d_large, $get_current_food_image_e, $get_current_food_thumb_e_small, $get_current_food_thumb_e_medium, $get_current_food_thumb_e_large, $get_current_food_last_used, $get_current_food_language, $get_current_food_no_of_comments, $get_current_food_stars, $get_current_food_stars_sum, $get_current_food_comments_multiplied_stars, $get_current_food_synchronized, $get_current_food_accepted_as_master, $get_current_food_notes, $get_current_food_unique_hits, $get_current_food_unique_hits_ip_block, $get_current_food_user_ip, $get_current_food_created_date, $get_current_food_last_viewed, $get_current_food_age_restriction) = $row;

	if($get_current_food_user_id != "$my_user_id"){
		echo"Access denied";
		die;
	}

	if($get_current_food_id == ""){
		/*- Headers ---------------------------------------------------------------------------------- */
		$website_title = "Server error 404 - $get_current_title_value";
		include("$root/_webdesign/header.php");


		echo"
		<h1>Food not found</h1>

		<p>
		Sorry, the food was not found.
		</p>

		<p>
		<a href=\"index.php\">Back</a>
		</p>
		";
	}
	else{


		/*- Headers ---------------------------------------------------------------------------------- */
		$website_title = "$get_current_food_manufacturer_name $get_current_food_name - $l_new_food - $get_current_title_value";
		if(file_exists("./favicon.ico")){ $root = "."; }
		elseif(file_exists("../favicon.ico")){ $root = ".."; }
		elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
		elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
		include("$root/_webdesign/header.php");

		/*- Content ---------------------------------------------------------------------------------- */

		// Process
		if($process == "1"){

			$inp_food_net_content = $_POST['inp_food_net_content'];
			$inp_food_net_content = output_html($inp_food_net_content);
			$inp_food_net_content = str_replace(",", ".", $inp_food_net_content);
			if(empty($inp_food_net_content)){
				$ft = "error";
				$fm = "missing_net_content";
			}
			else{
				if(!(is_numeric($inp_food_net_content))){
					$ft = "error";
					$fm = "net_content_is_not_numeric";
				}
			}

			$inp_food_net_content_measurement = $_POST['inp_food_net_content_measurement'];
			$inp_food_net_content_measurement = output_html($inp_food_net_content_measurement);
			$inp_food_net_content_measurement_mysql = quote_smart($link, $inp_food_net_content_measurement);

			/* Net content g, ml, oz, fl oz */
			if($inp_food_net_content_measurement == "g"){
				// We have gram below
				$inp_food_net_content_metric = "$inp_food_net_content";
				$inp_food_net_content_measurement_metric = "g";

				// We need to calculate oz
				$inp_food_net_content_us = round($inp_food_net_content/28.35, 1);
				$inp_food_net_content_measurement_us = "oz";

				
			}
			elseif($inp_food_net_content_measurement == "ml"){
				// We have gram below
				$inp_food_net_content_metric = "$inp_food_net_content";
				$inp_food_net_content_measurement_metric = "ml";

				// We need to calculate oz
				$inp_food_net_content_us = round($inp_food_net_content/29.574, 1);
				$inp_food_net_content_measurement_us = "fl oz";

			}
			elseif($inp_food_net_content_measurement == "oz_us"){
				// We have gram below
				$inp_food_net_content_metric = round($inp_food_net_content*28.35, 0);
				$inp_food_net_content_measurement_metric = "g";

				// We need to calculate oz
				$inp_food_net_content_us = "$inp_food_net_content";
				$inp_food_net_content_measurement_us = "oz";
			}
			elseif($inp_food_net_content_measurement == "fl_oz_us"){
				// We have gram below
				$inp_food_net_content_metric = round($inp_food_net_content*29.574, 0);
				$inp_food_net_content_measurement_metric = "ml";

				// We need to calculate oz
				$inp_food_net_content_us = "$inp_food_net_content";
				$inp_food_net_content_measurement_us = "fl oz";
			}

			$inp_food_net_content_metric_mysql = quote_smart($link, $inp_food_net_content_metric);
			$inp_food_net_content_measurement_metric_mysql = quote_smart($link, $inp_food_net_content_measurement_metric);
			$inp_food_net_content_us_mysql = quote_smart($link, $inp_food_net_content_us);
			$inp_food_net_content_measurement_us_mysql = quote_smart($link, $inp_food_net_content_measurement_us);

			/* Serving size */
			$inp_food_serving_size = $_POST['inp_food_serving_size'];
			$inp_food_serving_size = output_html($inp_food_serving_size);
			$inp_food_serving_size = str_replace(",", ".", $inp_food_serving_size);
			$inp_food_serving_size_mysql = quote_smart($link, $inp_food_serving_size);
			if(empty($inp_food_serving_size)){
				$ft = "error";
				$fm = "missing_serving_size";
			}
			else{
				if(!(is_numeric($inp_food_serving_size))){
					$ft = "error";
					$fm = "food_serving_size_is_not_numeric";
				}
			}

			$inp_food_serving_size_measurement = $_POST['inp_food_serving_size_measurement'];
			$inp_food_serving_size_measurement = output_html($inp_food_serving_size_measurement);
			$inp_food_serving_size_measurement_mysql = quote_smart($link, $inp_food_serving_size_measurement);
			if(empty($inp_food_serving_size_measurement)){
				$ft = "error";
				$fm = "missing_serving_size_measurement";
			}

			/* Serving Size g, ml, oz, fl oz */
			if($inp_food_serving_size_measurement == "g"){
				// We have gram below
				$inp_food_serving_size_metric = "$inp_food_serving_size";
				$inp_food_serving_size_measurement_metric = "g";

				// We need to calculate oz
				$inp_food_serving_size_us = round($inp_food_serving_size/28.35, 1);
				$inp_food_serving_size_measurement_us = "oz";

			}
			elseif($inp_food_serving_size_measurement == "ml"){
				// We have gram below
				$inp_food_serving_size_metric = "$inp_food_serving_size";
				$inp_food_serving_size_measurement_metric = "ml";

				// We need to calculate oz
				$inp_food_serving_size_us = round($inp_food_serving_size/29.574, 1);
				$inp_food_serving_size_measurement_us = "fl oz";

			}
			elseif($inp_food_serving_size_measurement == "oz_us"){
				// We have gram below
				$inp_food_serving_size_metric = round($inp_food_serving_size*28.35, 0);
				$inp_food_serving_size_measurement_metric = "g";

				// We need to calculate oz
				$inp_food_serving_size_us = "$inp_food_serving_size";
				$inp_food_serving_size_measurement_us = "oz";
			}
			elseif($inp_food_serving_size_measurement == "fl_oz_us"){
				// We have gram below
				$inp_food_serving_size_metric = round($inp_food_serving_size*29.574, 0);
				$inp_food_serving_size_measurement_metric = "ml";

				// We need to calculate oz
				$inp_food_serving_size_us = "$inp_food_serving_size";
				$inp_food_serving_size_measurement_us = "fl oz";
			}

			$inp_food_serving_size_metric_mysql = quote_smart($link, $inp_food_serving_size_metric);
			$inp_food_serving_size_measurement_metric_mysql = quote_smart($link, $inp_food_serving_size_measurement_metric);
			$inp_food_serving_size_us_mysql = quote_smart($link, $inp_food_serving_size_us);
			$inp_food_serving_size_measurement_us_mysql = quote_smart($link, $inp_food_serving_size_measurement_us);



			$inp_food_serving_size_pcs = $_POST['inp_food_serving_size_pcs'];
			$inp_food_serving_size_pcs = output_html($inp_food_serving_size_pcs);
			$inp_food_serving_size_pcs = str_replace(",", ".", $inp_food_serving_size_pcs);
			$inp_food_serving_size_pcs_mysql = quote_smart($link, $inp_food_serving_size_pcs);
			if(empty($inp_food_serving_size_pcs)){
				$ft = "error";
				$fm = "missing_serving_size_pcs";
			}
			else{
				if(!(is_numeric($inp_food_serving_size_pcs))){
					$ft = "error";
					$fm = "pcs_is_not_numeric";
				}
			}

			$inp_food_serving_size_pcs_measurement = $_POST['inp_food_serving_size_pcs_measurement'];
			$inp_food_serving_size_pcs_measurement = output_html($inp_food_serving_size_pcs_measurement);
			$inp_food_serving_size_pcs_measurement_mysql = quote_smart($link, $inp_food_serving_size_pcs_measurement);
			if(empty($inp_food_serving_size_pcs_measurement)){
				$ft = "error";
				$fm = "missing_serving_size_pcs_measurement";
			}

			$inp_food_description = $_POST['inp_food_description'];
			$inp_food_description = output_html($inp_food_description);
			$inp_food_description_mysql = quote_smart($link, $inp_food_description);

			if($ft == ""){


				// Update food_id
				$result = mysqli_query($link, "UPDATE $t_food_index SET 
								food_description=$inp_food_description_mysql, 

								food_net_content_metric=$inp_food_net_content_metric_mysql,
								food_net_content_measurement_metric=$inp_food_net_content_measurement_metric_mysql, 

								food_net_content_us=$inp_food_net_content_us_mysql, 
								food_net_content_measurement_us=$inp_food_net_content_measurement_us_mysql, 

								food_net_content_added_measurement=$inp_food_net_content_measurement_mysql, 

								food_serving_size_metric=$inp_food_serving_size_metric_mysql,
								food_serving_size_measurement_metric=$inp_food_serving_size_measurement_metric_mysql, 

								food_serving_size_us=$inp_food_serving_size_us_mysql, 
								food_serving_size_measurement_us=$inp_food_serving_size_measurement_us_mysql,

								food_serving_size_added_measurement=$inp_food_serving_size_measurement_mysql, 

								food_serving_size_pcs=$inp_food_serving_size_pcs_mysql, 
								food_serving_size_pcs_measurement=$inp_food_serving_size_pcs_measurement_mysql
								WHERE food_id='$get_current_food_id'") or die(mysqli_error($link));


				// Header
				if($inp_food_serving_size_measurement == "oz_us" OR $inp_food_serving_size_measurement == "fl_oz_us"){
					// Oz or fl oz: US only
					$url = "new_food_7_numbers_us_pcs.php?main_category_id=$main_category_id&sub_category_id=$sub_category_id&food_id=$get_current_food_id&el=$l";
				}
				else{
					// Use what I last used (last food)
					$query = "SELECT food_id, food_numbers_entered_method FROM $t_food_index WHERE food_user_id=$my_user_id_mysql ORDER BY food_id DESC LIMIT 1,1";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_last_food_id, $get_last_food_numbers_entered_method) = $row;

					// Default
					$url = "new_food_7_numbers_eu_hundred_metric.php?main_category_id=$main_category_id&sub_category_id=$sub_category_id&food_id=$get_current_food_id&el=$l";

					if($get_last_food_id != ""){
						if($get_last_food_numbers_entered_method == "eu_hundred_metric"){
							$url = "new_food_7_numbers_eu_hundred_metric.php?main_category_id=$main_category_id&sub_category_id=$sub_category_id&food_id=$get_current_food_id&el=$l";
						}
						elseif($get_last_food_numbers_entered_method == "eu_pcs_metric"){
							$url = "new_food_7_numbers_eu_pcs_metric.php?main_category_id=$main_category_id&sub_category_id=$sub_category_id&food_id=$get_current_food_id&el=$l";
						}
						elseif($get_last_food_numbers_entered_method == "us_pcs"){
							$url = "new_food_7_numbers_us_pcs.php?main_category_id=$main_category_id&sub_category_id=$sub_category_id&food_id=$get_current_food_id&el=$l";
						}
					}
				}
				header("Location: $url");
				exit;
			}
			else{
				$url = "new_food_6_serving_size.php?main_category_id=$main_category_id&sub_category_id=$sub_category_id&food_id=$get_current_food_id&l=$l";
				$url = $url . "&ft=$ft&fm=$fm";
				$url = $url . "&inp_food_net_content=$inp_food_net_content";
				$url = $url . "&inp_food_net_content_measurement=$inp_food_net_content_measurement";
				$url = $url . "&inp_food_serving_size=$inp_food_serving_size";
				$url = $url . "&inp_food_serving_size_measurement=$inp_food_serving_size_measurement";
				$url = $url . "&inp_food_serving_size_pcs=$inp_food_serving_size_pcs";
				$url = $url . "&inp_food_serving_size_pcs_measurement=$inp_food_serving_size_pcs_measurement";
				$url = $url . "&inp_food_description=$inp_food_description";

				header("Location: $url");
				exit;
			}
		}	


		echo"
		<h1>$get_current_food_manufacturer_name $get_current_food_name - $get_current_food_country</h1>
		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				$fm = ucfirst($fm);
				$fm = str_replace("_", " ", $fm);
				echo"<div class=\"$ft\"><p>$fm</p></div>";	
			}
			echo"
		<!-- //Feedback -->

		<!-- Serving size form + Food image -->
				
			<div class=\"two_rows\">
				<!-- Serving size form -->
				<div class=\"two_columns\">
					<!-- Focus -->
					<script>
						\$(document).ready(function(){
							\$('[name=\"inp_food_net_content\"]').focus();
						});
					</script>
					<!-- //Focus -->

					<form method=\"post\" action=\"new_food_6_serving_size.php?food_id=$food_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

					<table>
					 <tr>
					  <td style=\"text-align: right;padding: 0px 4px 0px 0px;vertical-align:top;\">
						<p><b>$l_net_content*:</b></p>
					  </td>
					  <td>
						<p><input type=\"text\" name=\"inp_food_net_content\" value=\"";
						if(isset($_GET['inp_food_net_content'])){
							$inp_food_net_content = $_GET['inp_food_net_content'];
							$inp_food_net_content = output_html($inp_food_net_content);
							echo"$inp_food_net_content";
						}
						else{
							if($get_current_food_net_content_added_measurement == "g" OR $get_current_food_net_content_added_measurement == "ml"){
								echo"$get_current_food_net_content_metric";
							}
							else{
								echo"$get_current_food_net_content_us";
							}
						}
						echo"\" size=\"3\" />
				
						<select name=\"inp_food_net_content_measurement\">";

						if(isset($_GET['inp_food_net_content_added_measurement'])){
							$inp_food_net_content_added_measurement = $_GET['inp_food_net_content_added_measurement'];
							$get_current_food_net_content_added_measurement = output_html($inp_food_net_content_added_measurement);
						}

						echo"
							<option value=\"g\""; if($get_current_food_net_content_added_measurement == "g"){ echo" selected=\"selected\""; } echo">g</option>
							<option value=\"ml\""; if($get_current_food_net_content_added_measurement == "ml"){ echo" selected=\"selected\""; } echo">ml</option>
							<option value=\"oz_us\""; if($get_current_food_net_content_added_measurement == "oz_us"){ echo" selected=\"selected\""; } echo">oz (US)</option>
							<option value=\"fl_oz_us\""; if($get_current_food_net_content_added_measurement == "fl_oz_us"){ echo" selected=\"selected\""; } echo">fl oz (US)</option>
						</select><br />
						<span class=\"small\">$l_how_much_does_an_entire_package_weighs<br />
						$l_if_it_is_fruit_then_enter_the_weight_of_one_piece<br />
						$l_example_one_banana_one_tomato_one_iceberg_lettuce_etc</span>
						</p>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"text-align: right;padding: 0px 4px 0px 0px;vertical-align:top;\">
						<p><b>$l_serving*:</b></p>
					  </td>
					  <td>
						<p><input type=\"text\" name=\"inp_food_serving_size\" value=\"";
						if(isset($_GET['inp_food_serving_size'])){
							$inp_food_serving_size = $_GET['inp_food_serving_size'];
							$inp_food_serving_size = output_html($inp_food_serving_size);
							echo"$inp_food_serving_size";
						}
						else{
							if($get_current_food_net_content_added_measurement == "g" OR $get_current_food_net_content_added_measurement == "ml"){
								echo"$get_current_food_serving_size_metric";
							}
							else{
								echo"$get_current_food_serving_size_us";
							}
						}
						echo"\" size=\"3\" />
						<select name=\"inp_food_serving_size_measurement\">";

						if(isset($_GET['inp_food_serving_size_measurement'])){
							$inp_food_serving_size_measurement = $_GET['inp_food_serving_size_measurement'];
							$get_current_food_serving_size_added_measurement = output_html($inp_food_serving_size_measurement);
						}
						echo"
							<option value=\"g\""; if($get_current_food_serving_size_added_measurement == "g"){ echo" selected=\"selected\""; } echo">g</option>
							<option value=\"ml\""; if($get_current_food_serving_size_added_measurement == "ml"){ echo" selected=\"selected\""; } echo">ml</option>
							<option value=\"oz_us\""; if($get_current_food_serving_size_added_measurement == "oz_us"){ echo" selected=\"selected\""; } echo">oz (US)</option>
							<option value=\"fl_oz_us\""; if($get_current_food_serving_size_added_measurement == "fl_oz_us"){ echo" selected=\"selected\""; } echo">fl oz (US)</option>
						</select><br />
						<span class=\"small\">$l_examples_g_ml</span>
						</p>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"text-align: right;padding: 0px 4px 0px 0px;vertical-align:top;\">
						<p><b>$l_serving_pcs*:</b></p> 
					  </td>
					  <td>
						<p><input type=\"text\" name=\"inp_food_serving_size_pcs\" value=\"";

						if(isset($_GET['inp_food_serving_size_pcs'])){
							$inp_food_serving_size_pcs = $_GET['inp_food_serving_size_pcs'];
							$get_current_food_serving_size_pcs = output_html($inp_food_serving_size_pcs);
						}

						echo"$get_current_food_serving_size_pcs\" size=\"3\" />
						<select name=\"inp_food_serving_size_pcs_measurement\">\n";



						if(isset($_GET['inp_food_serving_size_pcs_measurement'])){
							$inp_food_serving_size_pcs_measurement = $_GET['inp_food_serving_size_pcs_measurement'];
							$get_current_food_serving_size_pcs_measurement = output_html($inp_food_serving_size_pcs_measurement);
						}


						// Get measurements
						$query = "SELECT measurement_translation_id, measurement_id, measurement_translation_value FROM $t_food_measurements_translations WHERE measurement_translation_language=$l_mysql ORDER BY measurement_translation_value ASC";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_measurement_translation_id, $get_measurement_id, $get_measurement_translation_value) = $row;


						
							echo"				";
							echo"<option value=\"$get_measurement_translation_value\""; if($get_current_food_serving_size_pcs_measurement == "$get_measurement_translation_value"){ echo" selected=\"selected\""; } echo">$get_measurement_translation_value</option>\n";
						}
						echo"
						</select><br />
						<span class=\"small\">$l_examples_package_slice_pcs_plate</span>
						</p>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"text-align: right;padding: 0px 4px 0px 0px;vertical-align:top;\">
						<p><b>$l_description:</b></p> 
					  </td>
					  <td>
						<p><textarea name=\"inp_food_description\" rows=\"5\" cols=\"60\" style=\"width: 100%;\">";

						if(isset($_GET['inp_food_description'])){
							$inp_food_description = $_GET['inp_food_description'];
							$get_current_food_description  = output_html($inp_food_description);
						}

						$get_current_food_description = str_replace("<br />", "\n", $get_current_food_description);
						echo"$get_current_food_description</textarea>
						</p>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"text-align: right;padding: 0px 4px 0px 0px;\">
						
					  </td>
					  <td>
						<p><input type=\"submit\" value=\"$l_save\" class=\"btn btn-success btn-sm\" /></p>
					  </td>
					 </tr>
					</table>
					</form>
				</div> <!-- // two_columns -->
				<!-- //Serving size form -->


				<!-- Food image -->
				<div class=\"two_columns\">
					";
					if(file_exists("$root/$get_current_food_image_path/$get_current_food_image_a") && $get_current_food_image_a != ""){
						$imgsize = getimagesize("../$get_current_food_image_path/$get_current_food_image_a");
						echo"
					
						<a href=\"$root/$get_current_food_image_path/$get_current_food_image_a\"><img src=\"$root/$get_current_food_image_path/$get_current_food_image_a\" alt=\"$root/$get_current_food_image_path/$get_current_food_image_a\" id=\"food_image_a\" width=\"$imgsize[0]\" height=\"$imgsize[1]\" /></a>	
					";
					}
					if(file_exists("$root/$get_current_food_image_path/$get_current_food_image_b") && $get_current_food_image_b != ""){
						$imgsize = getimagesize("../$get_current_food_image_path/$get_current_food_image_b");
						echo"
				
						<a href=\"$root/$get_current_food_image_path/$get_current_food_image_b\"><img src=\"$root/$get_current_food_image_path/$get_current_food_image_b\" alt=\"$root/$get_current_food_image_path/$get_current_food_image_b\" id=\"food_image_b\" width=\"$imgsize[0]\" height=\"$imgsize[1]\" /></a>
					
					";
					}
					if(file_exists("$root/$get_current_food_image_path/$get_current_food_image_c") && $get_current_food_image_c != ""){
						$imgsize = getimagesize("../$get_current_food_image_path/$get_current_food_image_c");
						echo"
						<a href=\"$root/$get_current_food_image_path/$get_current_food_image_c\"><img src=\"$root/$get_current_food_image_path/$get_current_food_image_c\" alt=\"$root/$get_current_food_image_path/$get_current_food_image_c\" id=\"food_image_c\" width=\"$imgsize[0]\" height=\"$imgsize[1]\" /></a>
						
						";
					}
					if(file_exists("$root/$get_current_food_image_path/$get_current_food_image_d") && $get_current_food_image_d != ""){
						$imgsize = getimagesize("../$get_current_food_image_path/$get_current_food_image_d");
						echo"
						<a href=\"$root/$get_current_food_image_path/$get_current_food_image_d\"><img src=\"$root/$get_current_food_image_path/$get_current_food_image_d\" alt=\"$root/$get_current_food_image_path/$get_current_food_image_d\" id=\"food_image_d\" width=\"$imgsize[0]\" height=\"$imgsize[1]\" /></a>
						
						";
					}
					echo"
					</p>
				</div> <!-- // two_columns -->
				<!-- //Food image -->
			</div> <!-- //two_rows -->
		<!-- Serving size form + Food image -->

		<div class=\"clear\"></div>
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