<?php 
/**
*
* File: food/rating_edit.php
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

		// Can edit?
		$can_edit = 0;
		if($get_my_user_id == "$get_current_rating_by_user_id"){
			$can_edit = 1;
		}
		if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
			$can_edit = 1;
		}
		if($can_edit == "0"){
			echo"<p>Access denied.</p>";
		}
		else{
			if($process == "1"){
				$inp_title = $_POST['inp_title'];
				$inp_title = output_html($inp_title);
				$inp_title_mysql = quote_smart($link, $inp_title);

				$inp_text = $_POST['inp_text'];
				$inp_text = output_html($inp_text);
				$inp_text_mysql = quote_smart($link, $inp_text);

				$inp_stars = $_POST['inp_stars'];
				$inp_stars = output_html($inp_stars);
				$inp_stars_mysql = quote_smart($link, $inp_stars);

				$datetime = date("Y-m-d H:i:s");
				$date_saying = date("j M Y");

				// Update
				mysqli_query($link, "UPDATE $t_food_index_ratings SET 
							rating_title=$inp_title_mysql,
							rating_text=$inp_text_mysql,
							rating_stars=$inp_stars_mysql,
							rating_updated='$datetime',
							rating_updated_saying='$date_saying'
							WHERE rating_id=$get_current_rating_id")
							or die(mysqli_error($link));

				

				// Count comments
				$query = "SELECT count(rating_id) FROM $t_food_index_ratings WHERE rating_food_id=$get_current_food_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_count_comments) = $row;

				// Count and calculate all stars
				$query = "SELECT count(rating_id) FROM $t_food_index_ratings WHERE rating_food_id=$get_current_food_id AND rating_stars=1";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_count_star_1) = $row;

				$query = "SELECT count(rating_id) FROM $t_food_index_ratings WHERE rating_food_id=$get_current_food_id AND rating_stars=2";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_count_star_2) = $row;

				$query = "SELECT count(rating_id) FROM $t_food_index_ratings WHERE rating_food_id=$get_current_food_id AND rating_stars=3";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_count_star_3) = $row;
				
				$query = "SELECT count(rating_id) FROM $t_food_index_ratings WHERE rating_food_id=$get_current_food_id AND rating_stars=4";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_count_star_4) = $row;

				$query = "SELECT count(rating_id) FROM $t_food_index_ratings WHERE rating_food_id=$get_current_food_id AND rating_stars=5";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_count_star_5) = $row;

				$inp_stars_sum = $get_count_star_1+$get_count_star_2+$get_count_star_3+$get_count_star_4+$get_count_star_5;
				$inp_stars = round((($get_count_star_1*1) + ($get_count_star_2*2) + ($get_count_star_3*3) + ($get_count_star_4*4) + ($get_count_star_5*5))/$inp_stars_sum);
				$inp_food_comments_multiplied_stars = $get_count_comments*$inp_stars;

				mysqli_query($link, "UPDATE $t_food_index SET 
							food_no_of_comments=$get_count_comments,
							food_stars=$inp_stars,
							food_stars_sum=$inp_stars_sum,
							food_comments_multiplied_stars=$inp_food_comments_multiplied_stars
							WHERE food_id=$get_current_food_id") or die(mysqli_error($link));

				// Header
				$url = "view_food.php?food_id=$get_current_food_id&main_category_id=$get_current_food_main_category_id&sub_category_id=$get_current_food_sub_category_id&l=$l&ft_rating=success&fm_rating=changes_saved#rating$get_current_rating_id";
				header("Location: $url");
				exit;
			}


			echo"
			<h1>$l_edit_rating</h1>

			<!-- Where am I? -->
				<p>
				<b>$l_you_are_here:</b><br />
				<a href=\"$root/food/index.php?l=$l\">$l_food</a>
				&gt;
				<a href=\"$root/food/view_food.php?food_id=$get_current_food_id&amp;main_category_id=$get_current_food_main_category_id&amp;sub_category_id=$get_current_food_sub_category_id&amp;l=$l\">$get_current_food_manufacturer_name_and_food_name</a>
				&gt;
				<a href=\"rating_edit.php?comment_id=$get_current_rating_id&amp;l=$l\">$l_edit_rating $get_current_rating_id</a>
				</p>
			<!-- //Where am I? -->
	
			<!-- Edit rating form -->

				<form method=\"post\" action=\"rating_edit.php?rating_id=$get_current_rating_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
		
				<table>
	 			 <tr>
				  <td style=\"vertical-align: top;padding-right: 10px;text-align:center;\">
					<p>
					";
					if(file_exists("$root/$get_current_rating_by_user_image_path/$get_current_rating_by_user_image_thumb_60") && $get_current_rating_by_user_image_thumb_60 != ""){

				
						echo"
						<img src=\"$root/$get_current_rating_by_user_image_path/$get_current_rating_by_user_image_thumb_60\" alt=\"$get_current_rating_by_user_image_thumb_60\" />
						<br />
						";
					}
					echo"
					$get_current_rating_by_user_name
					</p>
				  </td>
				  <td style=\"vertical-align: top;\">

					<p><b>Title:</b><br />
					<input type=\"text\" name=\"inp_title\" size=\"25\" style=\"width: 80%\" value=\"$get_current_rating_title\" />
					</p>

					<!-- Rating -->
					<script>
					\$(document).ready(function(){
						\$(\".inp_rating_image_1\").click(function(){
							\$(\".inp_rating_radio_1\").prop(\"checked\", true);
							 \$(\".inp_rating_image_1\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_2\").attr('src','_gfx/icons/star_off.png');
							 \$(\".inp_rating_image_3\").attr('src','_gfx/icons/star_off.png');
							 \$(\".inp_rating_image_4\").attr('src','_gfx/icons/star_off.png');
							 \$(\".inp_rating_image_5\").attr('src','_gfx/icons/star_off.png');
						});
						\$(\".inp_rating_image_2\").click(function(){
							\$(\".inp_rating_radio_2\").prop(\"checked\", true);
							 \$(\".inp_rating_image_1\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_2\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_3\").attr('src','_gfx/icons/star_off.png');
							 \$(\".inp_rating_image_4\").attr('src','_gfx/icons/star_off.png');
							 \$(\".inp_rating_image_5\").attr('src','_gfx/icons/star_off.png');
						});
						\$(\".inp_rating_image_3\").click(function(){
							\$(\".inp_rating_radio_3\").prop(\"checked\", true);
							 \$(\".inp_rating_image_1\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_2\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_3\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_4\").attr('src','_gfx/icons/star_off.png');
							 \$(\".inp_rating_image_5\").attr('src','_gfx/icons/star_off.png');
						});
						\$(\".inp_rating_image_4\").click(function(){
							\$(\".inp_rating_radio_4\").prop(\"checked\", true);
							 \$(\".inp_rating_image_1\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_2\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_3\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_4\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_5\").attr('src','_gfx/icons/star_off.png');
						});
						\$(\".inp_rating_image_5\").click(function(){
							\$(\".inp_rating_radio_5\").prop(\"checked\", true);
							 \$(\".inp_rating_image_1\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_2\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_3\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_4\").attr('src','_gfx/icons/star_on.png');
							 \$(\".inp_rating_image_5\").attr('src','_gfx/icons/star_on.png');
						});
					});
					</script>
					<!-- //Rating -->

					<p><b>$l_set_rating:</b><br />
					<input type=\"radio\" name=\"inp_stars\" value=\"1\""; if($get_current_rating_stars == "1"){ echo" checked=\"checked\""; } echo" class=\"inp_rating_radio_1\" />
					<img src=\"_gfx/icons/star_on.png\" alt=\"star_on.png\" class=\"inp_rating_image_1\" />

					<input type=\"radio\" name=\"inp_stars\" value=\"2\""; if($get_current_rating_stars == "2"){ echo" checked=\"checked\""; } echo" class=\"inp_rating_radio_2\" />
					<img src=\"_gfx/icons/star_"; if($get_current_rating_stars > 1){ echo"on"; } else{ echo"off"; } echo".png\" alt=\"star_off.png\" class=\"inp_rating_image_2\" />

					<input type=\"radio\" name=\"inp_stars\" value=\"3\""; if($get_current_rating_stars == "3"){ echo" checked=\"checked\""; } echo" class=\"inp_rating_radio_3\" />
					<img src=\"_gfx/icons/star_"; if($get_current_rating_stars > 2){ echo"on"; } else{ echo"off"; } echo".png\" alt=\"star_off.png\" class=\"inp_rating_image_3\" />

					<input type=\"radio\" name=\"inp_stars\" value=\"4\""; if($get_current_rating_stars == "4"){ echo" checked=\"checked\""; } echo" class=\"inp_rating_radio_4\" />
					<img src=\"_gfx/icons/star_"; if($get_current_rating_stars > 3){ echo"on"; } else{ echo"off"; } echo".png\" alt=\"star_off.png\" class=\"inp_rating_image_4\" />

					<input type=\"radio\" name=\"inp_stars\" value=\"5\""; if($get_current_rating_stars == "5"){ echo" checked=\"checked\""; } echo" class=\"inp_rating_radio_5\" />
					<img src=\"_gfx/icons/star_"; if($get_current_rating_stars > 4){ echo"on"; } else{ echo"off"; } echo".png\" alt=\"star_off.png\" class=\"inp_rating_image_5\" />
					</p>

				
					<p><b>$l_comment:</b><br />
					<textarea name=\"inp_text\" rows=\"6\" cols=\"80\" style=\"width: 80%;\">";
					$get_current_rating_text = str_replace("<br />", "\n", $get_current_rating_text);
					echo"$get_current_rating_text</textarea><br />
					<input type=\"submit\" value=\"$l_save_changes\" class=\"btn_default\" />
					</p>
				  </td>
				 </tr>
				</table>

				</form>
			<!-- //Edit rating form -->
			";
		} // can edit
	}
	else{
		echo"<p>Not logged in.</p>";
	}
} // comment found


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>