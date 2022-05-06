<?php
/**
*
* File: _admin/_inc/stram/default.php
* Version 15.00 03.03.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables ---------------------------------------------------------------------------- */
$t_food_liquidbase		  = $mysqlPrefixSav . "food_liquidbase";
$t_food_categories		  = $mysqlPrefixSav . "food_categories";
$t_food_categories_translations	  = $mysqlPrefixSav . "food_categories_translations";
$t_food_index			  = $mysqlPrefixSav . "food_index";
$t_food_index_stores		  = $mysqlPrefixSav . "food_index_stores";
$t_food_index_ads		  = $mysqlPrefixSav . "food_index_ads";
$t_food_index_tags		  = $mysqlPrefixSav . "food_index_tags";
$t_food_index_prices		  = $mysqlPrefixSav . "food_index_prices";
$t_food_index_contents		  = $mysqlPrefixSav . "food_index_contents";
$t_food_stores		  	  = $mysqlPrefixSav . "food_stores";
$t_food_prices_currencies	  = $mysqlPrefixSav . "food_prices_currencies";
$t_food_favorites 		  = $mysqlPrefixSav . "food_favorites";
$t_food_measurements	 	  = $mysqlPrefixSav . "food_measurements";
$t_food_measurements_translations = $mysqlPrefixSav . "food_measurements_translations";

/*- Functions ----------------------------------------------------------------------- */

/*- Config ----------------------------------------------------------------------- */
if(!(file_exists("_data/food.php"))){
	$update_file="<?php
\$foodPrintLogoOnImagesSav = \"0\";
?>";
	$fh = fopen("_data/food.php", "w+") or die("can not open file");
	fwrite($fh, $update_file);
	fclose($fh);
}



/*- Variables ------------------------------------------------------------------------ */

/*- Check if setup is run ------------------------------------------------------------- */
$query = "SELECT * FROM $t_food_liquidbase LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){


	if($action == ""){
		echo"
		<h1>Food</h1>


		<!-- Food buttons and Select language -->

			<script>
			\$(function(){
				// bind change event to select
				\$('#inp_l').on('change', function () {
					var url = \$(this).val(); // get selected value
					if (url) { // require a URL
 						window.location = url; // redirect
					}
					return false;
				});
			});
			</script>

			<form method=\"get\" enctype=\"multipart/form-data\">
			<p>";
			// Navigation
			$query = "SELECT navigation_id FROM $t_pages_navigation WHERE navigation_url_path='food/index.php'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_navigation_id) = $row;
			if($get_navigation_id == ""){
				echo"
				<a href=\"index.php?open=pages&amp;page=navigation&amp;action=new_auto_insert&amp;module=food&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_default\">Create navigation</a>
				";
			}

			// Language
			echo"
			$l_language:
			<select id=\"inp_l\">
				<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">$l_editor_language</option>
				<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">-</option>\n";


				$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;

					// No language selected?
					if($editor_language == ""){
						$editor_language = "$get_language_active_iso_two";
					}
				
				
					echo"	<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$get_language_active_iso_two&amp;l=$l\"";if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
				}
			echo"
			</select>
			</p>
			</form>
		<!-- //Food buttons and Select language -->

		<!-- Food Main categories -->
			<div class=\"vertical\">
				<ul>";
				// Get all categories
				$editor_language = output_html($editor_language);
				$editor_language_mysql = quote_smart($link, $editor_language);
				$query = "SELECT category_id, category_name, category_parent_id FROM $t_food_categories WHERE category_user_id='0' AND category_parent_id='0'";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_category_id, $get_category_name, $get_category_parent_id) = $row;


					// Translation
					$query_translation = "SELECT category_translation_id, category_translation_language, category_translation_value FROM $t_food_categories_translations WHERE category_id=$get_category_id AND category_translation_language=$editor_language_mysql";
					$result_translation = mysqli_query($link, $query_translation);
					$row_translation = mysqli_fetch_row($result_translation);
					list($get_category_translation_id, $get_category_translation_language, $get_category_translation_value) = $row_translation;


					echo"				";
					echo"<li><a href=\"index.php?open=food&amp;page=food&amp;action=open_main_category&amp;main_category_id=$get_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_category_translation_value</a>\n";
				}
				echo"
				</ul>
			</div>
		<!-- // FoodMain categories -->
		";
	}
	elseif($action == "open_main_category"){
		// Variables
		$main_category_id = $_GET['main_category_id'];
		$main_category_id = strip_tags(stripslashes($main_category_id));
		$main_category_id_mysql = quote_smart($link, $main_category_id);
	
		$editor_language = output_html($editor_language);
		$editor_language_mysql = quote_smart($link, $editor_language);

		// Select category
		$query = "SELECT category_id, category_name FROM $t_food_categories WHERE category_id=$main_category_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_main_category_id, $get_current_main_category_name) = $row;

		if($get_current_main_category_id == ""){
			echo"
			<h1>Server error 404</h1>
			<p>Main category not found.</p>
			";
		}
		else{
			// Translated value
			$query_translation = "SELECT category_translation_id, category_translation_language, category_translation_value FROM $t_food_categories_translations WHERE category_id=$get_current_main_category_id AND category_translation_language=$editor_language_mysql";
			$result_translation = mysqli_query($link, $query_translation);
			$row_translation = mysqli_fetch_row($result_translation);
			list($get_main_category_translation_id, $get_main_category_translation_language, $get_main_category_translation_value) = $row_translation;


			echo"
			<h1>Food</h1>

			<!-- Where am I ? -->
				<p><b>You are here:</b><br />
				<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Food</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_main_category&amp;main_category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_main_category_translation_value</a>
				</p>
			<!-- //Where am I ? -->

			<!-- Select language -->
			<script>
			\$(function(){
				// bind change event to select
				\$('#inp_l').on('change', function () {
					var url = \$(this).val(); // get selected value
					if (url) { // require a URL
 						window.location = url; // redirect
					}
					return false;
				});
			});
			</script>

			<form method=\"get\" enctype=\"multipart/form-data\">
			<p>
			$l_language:
			<select id=\"inp_l\">
				<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">$l_editor_language</option>
				<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">-</option>\n";
				$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;

					echo"	<option value=\"index.php?open=$open&amp;page=$page&amp;action=open_main_category&amp;main_category_id=$get_current_main_category_id&amp;editor_language=$get_language_active_iso_two&amp;l=$l\"";if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
				}
			echo"
			</select>
			</p>
			</form>
			<!-- //Select language -->
	
			<!-- Food Main categories -->
			<div class=\"vertical\">
				<ul>";
				// Get food sub categories
				$query = "SELECT category_id, category_name, category_parent_id FROM $t_food_categories WHERE category_user_id='0' AND category_parent_id=$get_current_main_category_id";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_sub_category_id, $get_sub_category_name, $get_sub_category_parent_id) = $row;

	
					// Translation
					$query_translation = "SELECT category_translation_id, category_translation_language, category_translation_value FROM $t_food_categories_translations WHERE category_id=$get_sub_category_id AND category_translation_language=$editor_language_mysql";
					$result_translation = mysqli_query($link, $query_translation);
					$row_translation = mysqli_fetch_row($result_translation);
					list($get_sub_category_translation_id, $get_sub_category_translation_language, $get_sub_category_translation_value) = $row_translation;


					echo"				";
					echo"<li><a href=\"index.php?open=food&amp;page=food&amp;action=open_sub_category&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_sub_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_sub_category_translation_value</a>\n";
				}
				echo"
				</ul>
			</div>
			<!-- // Food Main categories -->
			";
		} // main category found
	} // action == open_main_category
	elseif($action == "open_sub_category"){
		// Variables
		$main_category_id = $_GET['main_category_id'];
		$main_category_id = strip_tags(stripslashes($main_category_id));
		$main_category_id_mysql = quote_smart($link, $main_category_id);

		$sub_category_id = $_GET['sub_category_id'];
		$sub_category_id = strip_tags(stripslashes($sub_category_id));
		$sub_category_id_mysql = quote_smart($link, $sub_category_id);

		$editor_language = output_html($editor_language);
		$editor_language_mysql = quote_smart($link, $editor_language);

		// Select main category
		$query = "SELECT category_id, category_name FROM $t_food_categories WHERE category_id=$main_category_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_main_category_id, $get_current_main_category_name) = $row;

		if($get_current_main_category_id == ""){
			echo"
			<h1>Server error 404</h1>
			<p>Main category not found.</p>
			";
		}
		else{
			// Select sub category
			$query = "SELECT category_id, category_name FROM $t_food_categories WHERE category_id=$sub_category_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_sub_category_id, $get_current_sub_category_name) = $row;

			if($get_current_main_category_id == ""){
				echo"
				<h1>Server error 404</h1>
				<p>Sub category not found.</p>
				";
			}
			else{
				// Translated value
				$query_translation = "SELECT category_translation_id, category_translation_language, category_translation_value FROM $t_food_categories_translations WHERE category_id=$get_current_main_category_id AND category_translation_language=$editor_language_mysql";
				$result_translation = mysqli_query($link, $query_translation);
				$row_translation = mysqli_fetch_row($result_translation);
				list($get_main_category_translation_id, $get_main_category_translation_language, $get_main_category_translation_value) = $row_translation;

				$query_translation = "SELECT category_translation_id, category_translation_language, category_translation_value FROM $t_food_categories_translations WHERE category_id=$get_current_sub_category_id AND category_translation_language=$editor_language_mysql";
				$result_translation = mysqli_query($link, $query_translation);
				$row_translation = mysqli_fetch_row($result_translation);
				list($get_sub_category_translation_id, $get_sub_category_translation_language, $get_sub_category_translation_value) = $row_translation;


				echo"
				<h1>$get_sub_category_translation_value</h1>

				<!-- Where am I ? -->
					<p><b>You are here:</b><br />
					<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Food</a>
					&gt;
					<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_main_category&amp;main_category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_main_category_translation_value</a>
					&gt;
					<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_sub_category&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_sub_category_translation_value</a>
					</p>
				<!-- //Where am I ? -->
	
				<!-- Feedback -->
				";
				if($ft != ""){
					if($fm == "changes_saved"){
						$fm = "$l_changes_saved";
					}
					else{
						$fm = str_replace("_", " ", $fm);
						$fm = ucfirst($fm);
					}
					echo"<div class=\"$ft\"><span>$fm</span></div>";
				}
				echo"	
				<!-- //Feedback -->

				<!-- Select language -->
				<script>
				\$(function(){
					// bind change event to select
					\$('#inp_l').on('change', function () {
						var url = \$(this).val(); // get selected value
						if (url) { // require a URL
 							window.location = url; // redirect
						}
						return false;
					});
				});
				</script>

				<form method=\"get\" enctype=\"multipart/form-data\">
				<p>
				$l_language:
				<select id=\"inp_l\">
					<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">$l_editor_language</option>
					<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">-</option>\n";
					$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;

						echo"	<option value=\"index.php?open=$open&amp;page=$page&amp;action=open_sub_category&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;editor_language=$get_language_active_iso_two&amp;l=$l\"";if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
					}
				echo"
				</select>
				</p>
				</form>
				<!-- //Select language -->

				<!-- Food found in sub category -->
				";
				// Set layout
				$x = 0;
		
				// Get food
				$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_comments, food_likes, food_dislikes, food_likes_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_sub_category_id=$get_current_sub_category_id AND food_language=$editor_language_mysql";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_comments, $get_food_likes, $get_food_dislikes, $get_food_likes_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row;
				

					if($x == "0"){
						echo"
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
					<p style=\"padding-bottom:5px;\">
					<a href=\"index.php?open=food&amp;page=open_food&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;food_id=$get_food_id&amp;editor_language=$editor_language&amp;l=$l\"><img src=\"../$get_food_image_path/$get_food_thumb_a_small\" alt=\"$get_food_image_a\" style=\"margin-bottom: 5px;\" /></a><br />
					<a href=\"index.php?open=food&amp;page=open_food&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;food_id=$get_food_id&amp;editor_language=$editor_language&amp;l=$l\" style=\"font-weight: bold;color: #444444;\">$get_food_manufacturer_name $get_food_name</a><br />
				
					</div>
					";
	
					// Increment
					$x = $x+1;
					if($x == "4"){ 
						echo"<div class=\"clear\"></div>\n";
						$x = 0; 
					}

				} // while food


				if($x == "0"){
				}
				elseif($x == "1"){
					echo"

					<div class=\"left_center_center_right_right_center\">
					</div> <!-- //left_center_center_right_right_center -->

					<div class=\"left_center_center_right_right_center\">
					</div> <!-- //left_center_center_right_right_center -->

					<div class=\"left_center_center_right_right\">
					</div> <!-- //left_center_center_right_right -->
					<div class=\"clear\"></div>
					";
				}
				elseif($x == "2"){
					echo"
					<div class=\"left_center_center_right_right_center\">
					</div> <!-- //left_center_center_right_right_center -->

					<div class=\"left_center_center_right_right\">
					</div> <!-- //left_center_center_right_right -->
					<div class=\"clear\"></div>
					";
				}
				elseif($x == "3"){
					echo"
					<div class=\"left_center_center_right_right\">
					</div> <!-- //left_center_center_right_right -->
					<div class=\"clear\"></div>
					";
				}

				echo"
				<!-- // Food found in sub category -->
				";
			} // sub category found
		} // main category found
	} // action == open_sub_category
} // setup ok
else{
	echo"
	<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Running setup</p></div>
	<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=$open&amp;page=tables&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
	";
} // setup has not runned

?>