<?php 
/**
*
* File: food/new_food_9_stores.php
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
		include("$root/_webdesign/header.php");

		/*- Content ---------------------------------------------------------------------------------- */

		if($action == ""){
	
			// Process
			if($process == "1"){
				
				if(isset($_GET['store_id'])){
					$store_id = $_GET['store_id'];
					$store_id = strip_tags(stripslashes($store_id));
					$store_id_mysql = quote_smart($link, $store_id);

					// Fetch store
					$query = "SELECT store_id, store_user_id, store_name, store_country, store_language, store_website, store_logo FROM $t_food_stores WHERE store_id=$store_id_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_store_id, $get_current_store_user_id, $get_current_store_name, $get_current_store_country, $get_current_store_language, $get_current_store_website, $get_current_store_logo) = $row;
					if($get_current_store_id != ""){

						// Name
						$inp_name_mysql = quote_smart($link, $get_current_store_name);

						// Logo
						$inp_logo_mysql = quote_smart($link, $get_current_store_logo);

						// IP 
						$inp_my_ip = $_SERVER['REMOTE_ADDR'];
						$inp_my_ip = output_html($inp_my_ip);
						$inp_my_ip_mysql = quote_smart($link, $inp_my_ip);

						// Datetime (notes)
						$datetime = date("Y-m-d H:i:s");


						// Does the link exists?
						$query = "SELECT food_store_id FROM $t_food_index_stores WHERE food_store_food_id=$food_id_mysql AND food_store_store_id=$store_id_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_current_food_store_id) = $row;

						if($get_current_food_store_id == ""){
							// Insert
							mysqli_query($link, "INSERT INTO $t_food_index_stores
							(food_store_id, food_store_food_id, food_store_store_id, food_store_store_name, food_store_store_logo, food_store_user_id, food_store_user_ip, food_store_updated) 
							VALUES 
							(NULL, $food_id_mysql, $store_id_mysql, $inp_name_mysql, $inp_logo_mysql, $my_user_id_mysql, $inp_my_ip_mysql, '$datetime')") or die(mysqli_error($link));
						}
					} // Store found
				}
				

			
				$url = "new_food_9_stores.php?food_id=$get_current_food_id&l=$l&ft=success&fm=changes_saved";
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

			<!-- Stores -->
				<p>$l_select_the_stores_where_you_can_purcase_the_food</p>
				
				<table>
				 <tr>
				  <td style=\"vertical-align: top;padding-right: 20px;\">

						<!-- Stores not selected -->
							<p><b>$l_available_stores:</b></p>

							<div class=\"vertical\">
								<ul id=\"available_stores\">";
					
							$query = "SELECT $t_food_stores.store_id, $t_food_stores.store_name, $t_food_stores.store_icon_18x18 FROM $t_food_stores WHERE $t_food_stores.store_language=$l_mysql ORDER BY $t_food_stores.store_name ASC";
							$result = mysqli_query($link, $query);
							while($row = mysqli_fetch_row($result)) {
								list($get_store_id, $get_store_name, $get_store_icon_18x18) = $row;
						
								// Do I have it?
								$query_link = "SELECT food_store_id FROM $t_food_index_stores WHERE food_store_food_id=$food_id_mysql AND food_store_store_id=$get_store_id";
								$result_link = mysqli_query($link, $query_link);
								$row_link = mysqli_fetch_row($result_link);
								list($get_current_food_store_id) = $row_link;


								if($get_current_food_store_id == ""){
									echo"
									<li><a href=\"#add_store_$get_store_id\" class=\"click_on_avaible_store\" data-divid=\"$get_store_id\" data-store_name=\"$get_store_name\" data-store_icon=\"$get_store_icon_18x18\">";
									if(file_exists("$root/_uploads/food/stores/$get_store_icon_18x18") && $get_store_icon_18x18 != ""){
										echo"<img src=\"$root/_uploads/food/stores/$get_store_icon_18x18\" alt=\"$get_store_icon_18x18\" /> ";
									}
									echo"$get_store_name</a></li>
									";
								}
							}

							echo"
								</ul>
							</div> <!-- //available_stores -->




							<!-- Click on avaible store script -->
								<script id=\"source\" language=\"javascript\" type=\"text/javascript\">
								\$(document).ready(function () {
									\$('.click_on_avaible_store').click(function(){

 										// forming the queryString
										var storeId = \$(this).data('divid');
										var storeName = \$(this).data('store_name');
										var storeIcon = \$(this).data('store_icon');
										var data = 'food_id=$get_current_food_id&=$l&store_id=' + storeId;

         									// ajax call
         									\$.ajax({
               										type: \"GET\",
         										url: \"edit_food_stores_select_available_store_script.php\",
              										data: data,
											beforeSend: function(html) { // this happens before actual call
											},
               										success: function(html){
                										\$(\"#available_stores_feedback\").html(html);
              										}
										});

										// Remove it
										\$(this).parent().remove(); 

										// Add to other side
										var selected_store = '<li><a href=\"edit_food_stores.php?action=remove_food_store&amp;food_id=$get_current_food_id&amp;food_store_id=' + storeId + '&amp;l=$l&amp;process=1\"><img src=\"$root/_uploads/food/stores/' + storeIcon + '\" alt=\"' + storeIcon + '\" />' + storeName + '</a></li>';
										\$(\"#selected_stores\").append(selected_store);

       										return false;
       									});
    								});
								</script>
							<!-- //Send new message script -->

							<div id=\"available_stores_feedback\"></div>
					
						<!-- //Stores not selected -->
				  </td>
				  <td style=\"vertical-align: top;\">
					<!-- //Selected stores -->
						<p><b>$l_stores_where_you_can_buy_the_food:</b></p>
						
						<div class=\"vertical\">
							<ul id=\"selected_stores\">\n";

							$query = "SELECT food_store_id, food_store_store_name, food_store_store_icon_18x18 FROM $t_food_index_stores WHERE food_store_food_id=$get_current_food_id ORDER BY food_store_store_name ASC";
							$result = mysqli_query($link, $query);
							while($row = mysqli_fetch_row($result)) {
								list($get_current_food_store_id, $get_current_food_store_store_name, $get_food_store_store_icon_18x18) = $row;

								echo"
								<li><a href=\"new_food_9_stores.php?action=remove_food_store&amp;food_id=$get_current_food_id&amp;food_store_id=$get_current_food_store_id&amp;l=$l&amp;process=1\">";
								if(file_exists("$root/_uploads/food/stores/$get_food_store_store_icon_18x18") && $get_food_store_store_icon_18x18 != ""){
									echo"<img src=\"$root/_uploads/food/stores/$get_food_store_store_icon_18x18\" alt=\"$get_food_store_store_icon_18x18\" /> ";
								}
								echo"$get_current_food_store_store_name</a></li>
								";
							}
	

							echo"
							</ul>
						</div>
					<!-- //Selected stores -->
				  </td>
				 </tr>
				</table>
			<!-- //Stores -->

			<!-- New store -->
				<p>
				<a href=\"my_stores_new.php?l=$l\" class=\"btn_default\">$l_new_store</a>
				<a href=\"my_stores.php?l=$l\" class=\"btn_default\">$l_my_stores</a>
				<a href=\"new_food_10_text.php?main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;food_id=$food_id&amp;l=$l\" class=\"btn_default\">$l_continue</a>
				</p>
			<!-- //New store -->
	
			";
		} // action == ""
		elseif($action == "remove_food_store"){
			
			if(isset($_GET['food_store_id'])){
				$food_store_id = $_GET['food_store_id'];
				$food_store_id = strip_tags(stripslashes($food_store_id));
				$food_store_id_mysql = quote_smart($link, $food_store_id);



				// Fetch it
				$query = "SELECT food_store_id, food_store_user_id FROM $t_food_index_stores WHERE food_store_id=$food_store_id_mysql AND food_store_food_id=$food_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_food_store_id, $get_current_food_store_user_id) = $row;
				if($get_current_food_store_id != ""){
					if($get_current_food_store_user_id == "$my_user_id"){
						mysqli_query($link, "DELETE FROM $t_food_index_stores WHERE food_store_id=$get_current_food_store_id") or die(mysqli_error($link));

			
						$url = "new_food_9_stores.php?food_id=$get_current_food_id&l=$l&ft=success&fm=changes_saved";
						header("Location: $url");
						exit;
					}
					else{
						echo"403";
					}
				}
				else{
					echo"404";
				}

			}
		} // remove_food_store
	} // food found
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