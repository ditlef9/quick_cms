<?php 
/**
*
* File: food/rating_report.php
* Version 1.0.0
* Date 11:51 01.11.2020
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
include("_tables_food.php");



/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);

if(isset($_GET['rating_id'])){
	$rating_id = $_GET['rating_id'];
	$rating_id = output_html($rating_id);
}
else{
	$rating_id = "";
}

// Title
$query = "SELECT title_id, title_value FROM $t_food_titles WHERE title_language=$l_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_title_id, $get_current_title_value) = $row;

// Get rating
$rating_id_mysql = quote_smart($link, $rating_id);
$query = "SELECT rating_id, rating_food_id, rating_title, rating_text, rating_by_user_id, rating_by_user_name, rating_by_user_image_path, rating_by_user_image_file, rating_by_user_image_thumb_60, rating_by_user_ip, rating_stars, rating_created, rating_created_saying, rating_created_timestamp, rating_updated, rating_updated_saying, rating_likes, rating_dislikes, rating_number_of_replies, rating_read_blog_owner, rating_reported, rating_reported_by_user_id, rating_reported_reason, rating_reported_checked FROM $t_food_index_ratings WHERE rating_id=$rating_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_rating_id, $get_current_rating_food_id, $get_current_rating_title, $get_current_rating_text, $get_current_rating_by_user_id, $get_current_rating_by_user_name, $get_current_rating_by_user_image_path, $get_current_rating_by_user_image_file, $get_current_rating_by_user_image_thumb_60, $get_current_rating_by_user_ip, $get_current_rating_stars, $get_current_rating_created, $get_current_rating_created_saying, $get_current_rating_created_timestamp, $get_current_rating_updated, $get_current_rating_updated_saying, $get_current_rating_likes, $get_current_rating_dislikes, $get_current_rating_number_of_replies, $get_current_rating_read_blog_owner, $get_current_rating_reported, $get_current_rating_reported_by_user_id, $get_current_rating_reported_reason, $get_current_rating_reported_checked) = $row;

if($get_current_rating_id == ""){
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "404 - $get_current_title_value";
	include("$root/_webdesign/header.php");
	echo"<p>Comment not found.</p>";
}
else{
	// Get food
	$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_text, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_numbers_entered_method, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_trans_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_added_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_trans_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_added_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_score_place_in_sub_category, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_trans_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_added_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_trans_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_added_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_energy_net_content, food_fat_net_content, food_saturated_fat_net_content, food_trans_fat_net_content, food_monounsaturated_fat_net_content, food_polyunsaturated_fat_net_content, food_cholesterol_net_content, food_carbohydrates_net_content, food_carbohydrates_of_which_sugars_net_content, food_added_sugars_net_content, food_dietary_fiber_net_content, food_proteins_net_content, food_salt_net_content, food_sodium_net_content, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_no_of_comments, food_stars, food_stars_sum, food_comments_multiplied_stars, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_id=$get_current_rating_food_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_food_id, $get_current_food_user_id, $get_current_food_name, $get_current_food_clean_name, $get_current_food_manufacturer_name, $get_current_food_manufacturer_name_and_food_name, $get_current_food_description, $get_current_food_text, $get_current_food_country, $get_current_food_net_content_metric, $get_current_food_net_content_measurement_metric, $get_current_food_net_content_us, $get_current_food_net_content_measurement_us, $get_current_food_net_content_added_measurement, $get_current_food_serving_size_metric, $get_current_food_serving_size_measurement_metric, $get_current_food_serving_size_us, $get_current_food_serving_size_measurement_us, $get_current_food_serving_size_added_measurement, $get_current_food_serving_size_pcs, $get_current_food_serving_size_pcs_measurement, $get_current_food_numbers_entered_method, $get_current_food_energy_metric, $get_current_food_fat_metric, $get_current_food_saturated_fat_metric, $get_current_food_trans_fat_metric, $get_current_food_monounsaturated_fat_metric, $get_current_food_polyunsaturated_fat_metric, $get_current_food_cholesterol_metric, $get_current_food_carbohydrates_metric, $get_current_food_carbohydrates_of_which_sugars_metric, $get_current_food_added_sugars_metric, $get_current_food_dietary_fiber_metric, $get_current_food_proteins_metric, $get_current_food_salt_metric, $get_current_food_sodium_metric, $get_current_food_energy_us, $get_current_food_fat_us, $get_current_food_saturated_fat_us, $get_current_food_trans_fat_us, $get_current_food_monounsaturated_fat_us, $get_current_food_polyunsaturated_fat_us, $get_current_food_cholesterol_us, $get_current_food_carbohydrates_us, $get_current_food_carbohydrates_of_which_sugars_us, $get_current_food_added_sugars_us, $get_current_food_dietary_fiber_us, $get_current_food_proteins_us, $get_current_food_salt_us, $get_current_food_sodium_us, $get_current_food_score, $get_current_food_score_place_in_sub_category, $get_current_food_energy_calculated_metric, $get_current_food_fat_calculated_metric, $get_current_food_saturated_fat_calculated_metric, $get_current_food_trans_fat_calculated_metric, $get_current_food_monounsaturated_fat_calculated_metric, $get_current_food_polyunsaturated_fat_calculated_metric, $get_current_food_cholesterol_calculated_metric, $get_current_food_carbohydrates_calculated_metric, $get_current_food_carbohydrates_of_which_sugars_calculated_metric, $get_current_food_added_sugars_calculated_metric, $get_current_food_dietary_fiber_calculated_metric, $get_current_food_proteins_calculated_metric, $get_current_food_salt_calculated_metric, $get_current_food_sodium_calculated_metric, $get_current_food_energy_calculated_us, $get_current_food_fat_calculated_us, $get_current_food_saturated_fat_calculated_us, $get_current_food_trans_fat_calculated_us, $get_current_food_monounsaturated_fat_calculated_us, $get_current_food_polyunsaturated_fat_calculated_us, $get_current_food_cholesterol_calculated_us, $get_current_food_carbohydrates_calculated_us, $get_current_food_carbohydrates_of_which_sugars_calculated_us, $get_current_food_added_sugars_calculated_us, $get_current_food_dietary_fiber_calculated_us, $get_current_food_proteins_calculated_us, $get_current_food_salt_calculated_us, $get_current_food_sodium_calculated_us, $get_current_food_energy_net_content, $get_current_food_fat_net_content, $get_current_food_saturated_fat_net_content, $get_current_food_trans_fat_net_content, $get_current_food_monounsaturated_fat_net_content, $get_current_food_polyunsaturated_fat_net_content, $get_current_food_cholesterol_net_content, $get_current_food_carbohydrates_net_content, $get_current_food_carbohydrates_of_which_sugars_net_content, $get_current_food_added_sugars_net_content, $get_current_food_dietary_fiber_net_content, $get_current_food_proteins_net_content, $get_current_food_salt_net_content, $get_current_food_sodium_net_content, $get_current_food_barcode, $get_current_food_main_category_id, $get_current_food_sub_category_id, $get_current_food_image_path, $get_current_food_image_a, $get_current_food_thumb_a_small, $get_current_food_thumb_a_medium, $get_current_food_thumb_a_large, $get_current_food_image_b, $get_current_food_thumb_b_small, $get_current_food_thumb_b_medium, $get_current_food_thumb_b_large, $get_current_food_image_c, $get_current_food_thumb_c_small, $get_current_food_thumb_c_medium, $get_current_food_thumb_c_large, $get_current_food_image_d, $get_current_food_thumb_d_small, $get_current_food_thumb_d_medium, $get_current_food_thumb_d_large, $get_current_food_image_e, $get_current_food_thumb_e_small, $get_current_food_thumb_e_medium, $get_current_food_thumb_e_large, $get_current_food_last_used, $get_current_food_language, $get_current_food_no_of_comments, $get_current_food_stars, $get_current_food_stars_sum, $get_current_food_comments_multiplied_stars, $get_current_food_synchronized, $get_current_food_accepted_as_master, $get_current_food_notes, $get_current_food_unique_hits, $get_current_food_unique_hits_ip_block, $get_current_food_user_ip, $get_current_food_created_date, $get_current_food_last_viewed, $get_current_food_age_restriction) = $row;


	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$get_current_food_manufacturer_name_and_food_name - $get_current_title_value";
	include("$root/_webdesign/header.php");
	
	// Logged in?
	if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
		// Get my user
		$my_user_id = $_SESSION['user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);
		$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;

		$query = "SELECT photo_id, photo_destination, photo_thumb_60 FROM $t_users_profile_photo WHERE photo_user_id=$my_user_id_mysql AND photo_profile_image='1'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_60) = $row;

		if($get_current_rating_reported == "1"){

			echo"
			<h1>$l_report_rating</h1>

			<!-- Where am I? -->
				<p>
				<b>$l_you_are_here:</b><br />
				<a href=\"$root/food/index.php?l=$l\">$l_food</a>
				&gt;
				<a href=\"$root/food/view_food.php?food_id=$get_current_food_id&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;l=$l\">$get_current_food_manufacturer_name_and_food_name</a>
				&gt;
				<a href=\"rating_report.php?rating_id=$get_current_rating_id&amp;l=$l\">$l_report_rating</a>
				</p>
			<!-- //Where am I? -->

			<p>
			$l_this_rating_is_already_reported.
			</p>
			";
		}
		else{
			if($process == "1"){
				$inp_text = $_POST['inp_text'];
				$inp_text = output_html($inp_text);
				$inp_text_mysql = quote_smart($link, $inp_text);

				if($inp_text == ""){
					$url = "rating_report.php?rating_id=$get_current_rating_id&l=$l&ft_rating=warning&fm_rating=missing_text";
					header("Location: $url");
					exit;
				} // no text 
				else{
					// Update
					mysqli_query($link, "UPDATE $t_food_index_ratings SET
							rating_reported=1,
							rating_reported_by_user_id=$get_my_user_id,
							rating_reported_reason=$inp_text_mysql,
							rating_reported_checked=0
							WHERE 
							rating_id=$get_current_rating_id")
							or die(mysqli_error($link));

					// Refresh site
					$url = "view_food.php?food_id=$get_current_food_id&main_category_id=$get_current_food_main_category_id&sub_category_id=$get_current_food_sub_category_id&l=$l&ft_rating=success&fm_rating=rating_reported#rating$get_current_rating_id";
					header("Location: $url");
					exit;
				} // text
			} // process == 1


			echo"
			<h1>$l_report_rating</h1>

			<!-- Where am I? -->
				<p>
				<b>$l_you_are_here:</b><br />
				<a href=\"$root/food/index.php?l=$l\">$l_food</a>
				&gt;
				<a href=\"$root/food/view_food.php?food_id=$get_current_food_id&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;l=$l\">$get_current_food_manufacturer_name_and_food_name</a>
				&gt;
				<a href=\"rating_report.php?rating_id=$get_current_rating_id&amp;l=$l\">$l_report_rating</a>
				</p>
			<!-- //Where am I? -->
	
			<!-- View rating -->
			<p><b>$l_original_rating:</b></p>
			<table>
			 <tr>
			  <td style=\"vertical-align: top;padding-right: 10px;text-align:center;\">
				<p>
				";
				if(file_exists("$root/$get_current_rating_by_user_image_path/$get_current_rating_by_user_image_thumb_60") && $get_current_rating_by_user_image_thumb_60 != ""){

				
					echo"
					<a href=\"users/view_profile.php?user_id=$get_current_rating_by_user_id&amp;l=$l\"><img src=\"$root/$get_current_rating_by_user_image_path/$get_current_rating_by_user_image_thumb_60\" alt=\"$get_current_rating_by_user_image_thumb_60\" /></a>
					<br />
					";
				}
				echo"
				</p>
			  </td>
			  <td style=\"vertical-align: top;\">
				<p>
				<a href=\"users/view_profile.php?user_id=$get_current_rating_by_user_id&amp;l=$l\" style=\"font-weight: bold;\">$get_current_rating_by_user_name</a> 
				$get_current_rating_created_saying<br />
				</p>

				<p>
				$get_current_rating_text
				</p>
			  </td>
			 </tr>
			<!-- //View rating -->
	
			<!-- Reason to mark it as spam form -->

	 		 <tr>
			  <td colspan=\"2\">
				<p style=\"padding-top: 16px\"><b>$l_reason:</b></p>
			  </td>
			 </tr>
	 		 <tr>
			  <td style=\"vertical-align: top;padding-right: 10px;text-align:center;\">
				<p>
				";
				if(file_exists("$root/_uploads/users/images/$get_my_user_id/$get_my_photo_destination") && $get_my_photo_destination != ""){

					// Thumb
					if(!(file_exists("$root/_uploads/users/images/$get_my_user_id/$get_my_photo_thumb_60"))){
						resize_crop_image(60, 60, "$root/_uploads/users/images/$get_my_user_id/$get_my_photo_destination", "$root/_uploads/users/images/$get_my_user_id/$get_my_photo_thumb_60");
					}
					echo"
					<img src=\"$root/_uploads/users/images/$get_my_user_id/$get_my_photo_thumb_60\" alt=\"$get_my_photo_thumb_60\" />
					<br />
					";
				}
				echo"
				$get_my_user_name
				</p>
			  </td>
			  <td style=\"vertical-align: top;\">
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_text\"]').focus();
				});
				</script>
				<form method=\"post\" action=\"rating_report.php?rating_id=$get_current_rating_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
				<p>
				<textarea name=\"inp_text\" rows=\"5\" cols=\"80\"></textarea><br />
				<input type=\"submit\" value=\"$l_send_report\" class=\"btn_default\" />
				</p>
				</form>
			  </td>
			 </tr>
			</table>

			<!-- //Reason to mark it as spam form -->
			";
		} // not previously reported
	} // is logged in
	else{
		echo"<p>Not logged in.</p>";
	}
} // comment found


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>