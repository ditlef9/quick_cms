<?php 
/**
*
* File: food/new_food_8_tags.php
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
		$website_title = "Server error 404 - $l_food";
		if(file_exists("./favicon.ico")){ $root = "."; }
		elseif(file_exists("../favicon.ico")){ $root = ".."; }
		elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
		elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
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
			// Delete all old tags
			$result = mysqli_query($link, "DELETE FROM $t_food_index_tags WHERE tag_food_id=$get_current_food_id");
				
			// Lang
			$inp_tag_language_mysql = quote_smart($link, $get_current_food_language);

			$inp_tag_a = $_POST['inp_tag_a'];
			$inp_tag_a = strtolower($inp_tag_a);
			$inp_tag_a = output_html($inp_tag_a);
			$inp_tag_a_mysql = quote_smart($link, $inp_tag_a);

			$inp_tag_a_clean = clean($inp_tag_a);
			$inp_tag_a_clean = strtolower($inp_tag_a);
			$inp_tag_a_clean_mysql = quote_smart($link, $inp_tag_a_clean);

			if($inp_tag_a != ""){
				// Insert
				mysqli_query($link, "INSERT INTO $t_food_index_tags 
				(tag_id, tag_language, tag_food_id, tag_title, tag_title_clean, tag_user_id) 
				VALUES 
				(NULL, $inp_tag_language_mysql, $get_current_food_id, $inp_tag_a_mysql, $inp_tag_a_clean_mysql, $my_user_id_mysql)")
				or die(mysqli_error($link));
			}

			$inp_tag_b = $_POST['inp_tag_b'];
			$inp_tag_b = strtolower($inp_tag_b);
			$inp_tag_b = output_html($inp_tag_b);
			$inp_tag_b_mysql = quote_smart($link, $inp_tag_b);

			$inp_tag_b_clean = clean($inp_tag_b);
			$inp_tag_b_clean = strtolower($inp_tag_b);
			$inp_tag_b_clean_mysql = quote_smart($link, $inp_tag_b_clean);

			if($inp_tag_b != ""){
				// Insert
				mysqli_query($link, "INSERT INTO $t_food_index_tags 
				(tag_id, tag_language, tag_food_id, tag_title, tag_title_clean, tag_user_id) 
				VALUES 
				(NULL, $inp_tag_language_mysql, $get_current_food_id, $inp_tag_b_mysql, $inp_tag_b_clean_mysql, $my_user_id_mysql)")
				or die(mysqli_error($link));
			}

			$inp_tag_c = $_POST['inp_tag_c'];
			$inp_tag_c = strtolower($inp_tag_c);
			$inp_tag_c = output_html($inp_tag_c);
			$inp_tag_c_mysql = quote_smart($link, $inp_tag_c);

			$inp_tag_c_clean = clean($inp_tag_c);
			$inp_tag_c_clean = strtolower($inp_tag_c);
			$inp_tag_c_clean_mysql = quote_smart($link, $inp_tag_c_clean);

			if($inp_tag_c != ""){
				// Insert
				mysqli_query($link, "INSERT INTO $t_food_index_tags 
				(tag_id, tag_language, tag_food_id, tag_title, tag_title_clean, tag_user_id) 
				VALUES 
				(NULL, $inp_tag_language_mysql, $get_current_food_id, $inp_tag_c_mysql, $inp_tag_c_clean_mysql, $my_user_id_mysql)")
				or die(mysqli_error($link));
			}

			// Search engine
			include("new_food_00_add_update_search_engine.php");


			$url = "new_food_9_stores.php?main_category_id=$main_category_id&sub_category_id=$sub_category_id&food_id=$get_current_food_id&l=$l";
			header("Location: $url");
			exit;
		}


		echo"
		<h1>$get_current_food_manufacturer_name $get_current_food_name</h1>
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

		<!-- Tags -->
			<!-- Focus -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_tag_a\"]').focus();
				});
				</script>
			<!-- //Focus -->

			<form method=\"post\" action=\"new_food_8_tags.php?main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;food_id=$food_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">


				<!-- Search engines Autocomplete -->
					<script id=\"source\" language=\"javascript\" type=\"text/javascript\">
						\$(document).ready(function () {
							\$('.inp_tag').keyup(function () {
								// getting the value that user typed
								var searchString	= \$(this).val();
								var name 		= \$(this).attr(\"name\");

								// forming the queryString
      								var data            = 'l=$l&tag_field=' + name + '&search_query='+ searchString;
         
        							// if searchString is not empty
        							if(searchString) {
									\$(\"#search_result_\" + name).css('visibility','visible');

									// ajax call
        								\$.ajax({
        									type: \"GET\",
        									url: \"edit_food_tags_search_autocomplete.php\",
                								data: data,
										beforeSend: function(html) { // this happens before actual call
											\$(\"#search_result_\" + name).html(''); 
										},
               									success: function(html){
                    									\$(\"#search_result_\" + name).html(html);
              									}
            								});
       								}
        							return false;

            						});
         					});
					</script>
				<!-- //Search engines Autocomplete -->

			<h2>$l_tags</h2>


				";
				// Fetch tags
				$y = 1;
				$query = "SELECT tag_id, tag_title FROM $t_food_index_tags WHERE tag_food_id=$get_current_food_id ORDER BY tag_id ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_tag_id, $get_tag_title) = $row;
				
					if($y == "1"){
						$name = "inp_tag_a";
					}
					elseif($y == "2"){
						$name = "inp_tag_b";
					}
					elseif($y == "3"){
						$name = "inp_tag_c";
					}
					echo"
					<p><b>$l_tag $y:</b><br />
					<input type=\"text\" name=\"$name\" class=\"inp_tag\" id=\"$name\" value=\"$get_tag_title\" size=\"25\" autocomplete=\"off\" /></p>
					<div id=\"search_result_$name\"></div>
					";
					$y++;
				}
				
				
				if($y == 1){
					echo"
					<p><b>$l_tag 1:</b><br />
					<input type=\"text\" name=\"inp_tag_a\" class=\"inp_tag\" id=\"inp_tag_a\" value=\"\" size=\"25\" autocomplete=\"off\" /></p>
					<div id=\"search_result_inp_tag_a\"></div>
					
					<p><b>$l_tag 2:</b><br />
					<input type=\"text\" name=\"inp_tag_b\" class=\"inp_tag\" id=\"inp_tag_b\" value=\"\" size=\"25\" autocomplete=\"off\" /></p>
					<div id=\"search_result_inp_tag_b\"></div>
					
					<p><b>$l_tag 3:</b><br />
					<input type=\"text\" name=\"inp_tag_c\" class=\"inp_tag\" id=\"inp_tag_c\" value=\"\" size=\"25\" autocomplete=\"off\" /></p>
					<div id=\"search_result_inp_tag_c\"></div>
					";

				}
				elseif($y == 2){
					echo"
					
					<p><b>$l_tag 2:</b><br />
					<input type=\"text\" name=\"inp_tag_b\" class=\"inp_tag\" id=\"inp_tag_b\" value=\"\" size=\"25\" autocomplete=\"off\" /></p>
					<div id=\"search_result_inp_tag_b\"></div>
					
					<p><b>$l_tag 3:</b><br />
					<input type=\"text\" name=\"inp_tag_c\" class=\"inp_tag\" id=\"inp_tag_c\" value=\"\" size=\"25\" autocomplete=\"off\" /></p>
					<div id=\"search_result_inp_tag_c\"></div>
					";

				}
				elseif($y == 3){
					echo"
					<p><b>$l_tag 3:</b><br />
					<input type=\"text\" name=\"inp_tag_c\" class=\"inp_tag\" id=\"inp_tag_c\" value=\"\" size=\"25\" autocomplete=\"off\" /></p>
					<div id=\"search_result_inp_tag_c\"></div>
					";

				}
			echo"
			<p><input type=\"submit\" value=\"$l_save\" class=\"btn btn-success btn-sm\" /></p>
				
		<!-- //Tags -->

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