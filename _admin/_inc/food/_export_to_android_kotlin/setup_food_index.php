<?php
/**
*
* File: _admin/_inc/food/_export_to_android_kotlin/setup_food_index.php
* Version 10:05 18.10.2020
* Copyright (c) 2008-2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Food index -------------------------------------------------------------------------- */
echo"

<!-- Languages -->
	<p>";
	$x = 0;
	$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;

		$flag_path 	= "_design/gfx/flags/16x16/$get_language_active_flag" . "_16x16.png";

		// No language selected?
		if($editor_language == ""){
				$editor_language = "$get_language_active_iso_two";
		}
	
		if($x > 0){
			echo" &middot; ";
		}
	
		echo"
		<a href=\"index.php?open=food&amp;page=$page&amp;inc=$inc&amp;editor_language=$get_language_active_iso_two&amp;l=$l\""; if($editor_language == "$get_language_active_iso_two"){ echo" style=\"font-weight: bold;\""; } echo">$get_language_active_name</a>
		";
		$x++;
	}
	echo"
	
	</p>
<!-- //Languages -->


<textarea cols=\"150\" rows=\"30\" style=\"width: 100%;height:100%;font: normal 11px Consolas;\">
class Setup4FoodIndex"; echo strtoupper($editor_language); echo" {
&nbsp; &nbsp; /*- Categories -------------------------------------------------------------------------- */
&nbsp; &nbsp; fun insertFoodIndex(context: Context){
&nbsp; &nbsp; &nbsp; &nbsp; var db: DatabaseHelper? = DatabaseHelper(context)
";

// Get all categories
$q_count = 0;
$category_count = 0;
$insert_count = 0;
$transfer_main_category_id = 0;

$editor_language_mysql = quote_smart($link, $editor_language);

$query = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_country, food_net_content, food_net_content_measurement, food_serving_size_gram, food_serving_size_gram_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_energy, food_fat, food_fat_of_which_saturated_fatty_acids, food_carbohydrates, food_carbohydrates_of_which_dietary_fiber, food_carbohydrates_of_which_sugars, food_proteins, food_salt, food_sodium, food_score, food_energy_calculated, food_fat_calculated, food_fat_of_which_saturated_fatty_acids_calculated, food_carbohydrates_calculated, food_carbohydrates_of_which_dietary_fiber_calculated, food_carbohydrates_of_which_sugars_calculated, food_proteins_calculated, food_salt_calculated, food_sodium_calculated, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_comments, food_likes, food_dislikes, food_likes_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_language=$editor_language_mysql";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_row($result)) {
	list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_country, $get_food_net_content, $get_food_net_content_measurement, $get_food_serving_size_gram, $get_food_serving_size_gram_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_energy, $get_food_fat, $get_food_fat_of_which_saturated_fatty_acids, $get_food_carbohydrates, $get_food_carbohydrates_of_which_dietary_fiber, $get_food_carbohydrates_of_which_sugars, $get_food_proteins, $get_food_salt, $get_food_sodium, $get_food_score, $get_food_energy_calculated, $get_food_fat_calculated, $get_food_fat_of_which_saturated_fatty_acids_calculated, $get_food_carbohydrates_calculated, $get_food_carbohydrates_of_which_dietary_fiber_calculated, $get_food_carbohydrates_of_which_sugars_calculated, $get_food_proteins_calculated, $get_food_salt_calculated, $get_food_sodium_calculated, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_comments, $get_food_likes, $get_food_dislikes, $get_food_likes_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction
) = $row;

	// Inp
	$inp_food_user_id_mysql = quote_smart($link, $get_food_user_id);

	$inp_food_name = output_html($get_food_name);
	$inp_food_name_mysql = quote_smart($link, $inp_food_name);

	$inp_food_clean_name_mysql = quote_smart($link, $get_food_clean_name);

	$inp_food_manufacturer_name = output_html($get_food_manufacturer_name);
	$inp_food_manufacturer_name_mysql = quote_smart($link, $inp_food_manufacturer_name);

	$inp_food_manufacturer_name_and_food_name = output_html($get_food_manufacturer_name_and_food_name);
	$inp_food_manufacturer_name_and_food_name_mysql = quote_smart($link, $inp_food_manufacturer_name_and_food_name);

	$inp_food_description = output_html($get_food_description);
	$inp_food_description_mysql = quote_smart($link, $inp_food_description);

	$inp_food_country_mysql = quote_smart($link, $get_food_country);
	$inp_food_net_content_mysql = quote_smart($link, $get_food_net_content);
	$inp_food_net_content_measurement_mysql = quote_smart($link, $get_food_net_content_measurement);
	$inp_food_serving_size_gram_mysql = quote_smart($link, $get_food_serving_size_gram);
	$inp_food_serving_size_gram_measurement_mysql = quote_smart($link, $get_food_serving_size_gram_measurement);
	$inp_food_serving_size_pcs_mysql = quote_smart($link, $get_food_serving_size_pcs);
	$inp_food_serving_size_pcs_measurement_mysql = quote_smart($link, $get_food_serving_size_pcs_measurement);
	$inp_food_energy_mysql = quote_smart($link, $get_food_energy);
	$inp_food_fat_mysql = quote_smart($link, $get_food_fat);
	$inp_food_fat_of_which_saturated_fatty_acids_mysql = quote_smart($link, $get_food_fat_of_which_saturated_fatty_acids);
	$inp_food_carbohydrates_mysql = quote_smart($link, $get_food_carbohydrates);
	$inp_food_carbohydrates_of_which_dietary_fiber_mysql = quote_smart($link, $get_food_carbohydrates_of_which_dietary_fiber);
	$inp_food_carbohydrates_of_which_sugars_mysql = quote_smart($link, $get_food_carbohydrates_of_which_sugars);
	$inp_food_proteins_mysql = quote_smart($link, $get_food_proteins);
	$inp_food_salt_mysql = quote_smart($link, $get_food_salt);
	$inp_food_sodium_mysql = quote_smart($link, $get_food_sodium);
	$inp_food_score_mysql = quote_smart($link, $get_food_score);
	$inp_food_energy_calculated_mysql = quote_smart($link, $get_food_energy_calculated);
	$inp_food_fat_calculated_mysql = quote_smart($link, $get_food_fat_calculated);
	$inp_food_fat_of_which_saturated_fatty_acids_calculated_mysql = quote_smart($link, $get_food_fat_of_which_saturated_fatty_acids_calculated);
	$inp_food_carbohydrates_calculated_mysql = quote_smart($link, $get_food_carbohydrates_calculated);
	$inp_food_carbohydrates_of_which_dietary_fiber_calculated_mysql = quote_smart($link, $get_food_carbohydrates_of_which_dietary_fiber_calculated);
	$inp_food_carbohydrates_of_which_sugars_calculated_mysql = quote_smart($link, $get_food_carbohydrates_of_which_sugars_calculated);
	$inp_food_proteins_calculated_mysql = quote_smart($link, $get_food_proteins_calculated);
	$inp_food_salt_calculated_mysql = quote_smart($link, $get_food_salt_calculated);
	$inp_food_sodium_calculated_mysql = quote_smart($link, $get_food_sodium_calculated);
	$inp_food_barcode_mysql = quote_smart($link, $get_food_barcode);
	$inp_food_main_category_id_mysql = quote_smart($link, $get_food_main_category_id);
	$inp_food_sub_category_id_mysql = quote_smart($link, $get_food_sub_category_id);
	$inp_food_image_path_mysql = quote_smart($link, $get_food_image_path);
	$inp_food_image_a_mysql = quote_smart($link, $get_food_image_a);
	$inp_food_thumb_a_small_mysql = quote_smart($link, $get_food_thumb_a_small);
	$inp_food_thumb_a_medium_mysql = quote_smart($link, $get_food_thumb_a_medium);
	$inp_food_thumb_a_large_mysql = quote_smart($link, $get_food_thumb_a_large);

	$inp_food_image_b_mysql = quote_smart($link, $get_food_image_b);
	$inp_food_thumb_b_small_mysql = quote_smart($link, $get_food_thumb_b_small);
	$inp_food_thumb_b_medium_mysql = quote_smart($link, $get_food_thumb_b_medium);
	$inp_food_thumb_b_large_mysql = quote_smart($link, $get_food_thumb_b_large);

	$inp_food_image_c_mysql = quote_smart($link, $get_food_image_c);
	$inp_food_thumb_c_small_mysql = quote_smart($link, $get_food_thumb_c_small);
	$inp_food_thumb_c_medium_mysql = quote_smart($link, $get_food_thumb_c_medium);
	$inp_food_thumb_c_large_mysql = quote_smart($link, $get_food_thumb_c_large);

	$inp_food_image_d_mysql = quote_smart($link, $get_food_image_d);
	$inp_food_thumb_d_small_mysql = quote_smart($link, $get_food_thumb_d_small);
	$inp_food_thumb_d_medium_mysql = quote_smart($link, $get_food_thumb_d_medium);
	$inp_food_thumb_d_large_mysql = quote_smart($link, $get_food_thumb_d_large);

	$inp_food_image_e_mysql = quote_smart($link, $get_food_image_e);
	$inp_food_thumb_e_small_mysql = quote_smart($link, $get_food_thumb_e_small);
	$inp_food_thumb_e_medium_mysql = quote_smart($link, $get_food_thumb_e_medium);
	$inp_food_thumb_e_large_mysql = quote_smart($link, $get_food_thumb_e_large);

	$inp_food_last_used_mysql = quote_smart($link, $get_food_last_used);
	$inp_food_language_mysql = quote_smart($link, $get_food_language);
	$inp_food_synchronized_mysql = quote_smart($link, $get_food_synchronized);
	$inp_food_accepted_as_master_mysql = quote_smart($link, $get_food_accepted_as_master);
	$inp_food_notes_mysql = quote_smart($link, $get_food_notes);
	$inp_food_unique_hits_mysql = quote_smart($link, $get_food_unique_hits);
	// food_unique_hits_ip_block
	$inp_food_comments_mysql = quote_smart($link, $get_food_comments);
	$inp_food_likes_mysql = quote_smart($link, $get_food_likes);
	$inp_food_dislikes_mysql = quote_smart($link, $get_food_dislikes);
	// food_likes_ip_block
	// food_user_ip
	$inp_food_created_date_mysql = quote_smart($link, $get_food_created_date);
	$inp_food_last_viewed_mysql = quote_smart($link, $get_food_last_viewed);
	$inp_food_age_restriction_mysql = quote_smart($link, $get_food_age_restriction);

	if($insert_count == "0"){
		echo"
		&nbsp; &nbsp; &nbsp; &nbsp; val q$q_count = &quot;INSERT INTO food_index(food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;food_manufacturer_name_and_food_name, food_description, food_country, food_net_content, food_net_content_measurement, &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;food_serving_size_gram, food_serving_size_gram_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_energy, &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;food_fat, food_fat_of_which_saturated_fatty_acids, food_carbohydrates, food_carbohydrates_of_which_dietary_fiber, &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;food_carbohydrates_of_which_sugars, food_proteins, food_salt, food_sodium, food_score, &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;food_energy_calculated, food_fat_calculated, food_fat_of_which_saturated_fatty_acids_calculated, food_carbohydrates_calculated, food_carbohydrates_of_which_dietary_fiber_calculated, &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;food_carbohydrates_of_which_sugars_calculated, food_proteins_calculated, food_salt_calculated, food_sodium_calculated, food_barcode, &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;food_comments, food_likes, food_dislikes, food_likes_ip_block, food_user_ip, &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;food_created_date, food_last_viewed, food_age_restriction) &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;VALUES &quot; +";
	}
	else{
		// Next insertion before
		echo",&quot; + \n";
	}

	// Insert food
	echo"
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;(NULL, $inp_food_user_id_mysql, $inp_food_name_mysql, $inp_food_clean_name_mysql, $inp_food_manufacturer_name_mysql, &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;$inp_food_manufacturer_name_and_food_name_mysql, $inp_food_description_mysql, $inp_food_country_mysql, $inp_food_net_content_mysql, $inp_food_net_content_measurement_mysql, &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;$inp_food_serving_size_gram_mysql, $inp_food_serving_size_gram_measurement_mysql, $inp_food_serving_size_pcs_mysql, $inp_food_serving_size_pcs_measurement_mysql, $inp_food_energy_mysql, &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;$inp_food_fat_mysql, $inp_food_fat_of_which_saturated_fatty_acids_mysql, $inp_food_carbohydrates_mysql, $inp_food_carbohydrates_of_which_dietary_fiber_mysql, &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;$inp_food_carbohydrates_of_which_sugars_mysql, $inp_food_proteins_mysql, $inp_food_salt_mysql, $inp_food_sodium_mysql, $inp_food_score_mysql, &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;$inp_food_energy_calculated_mysql, $inp_food_fat_calculated_mysql, $inp_food_fat_of_which_saturated_fatty_acids_calculated_mysql, $inp_food_carbohydrates_calculated_mysql, $inp_food_carbohydrates_of_which_dietary_fiber_calculated_mysql, &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;$inp_food_carbohydrates_of_which_sugars_calculated_mysql, $inp_food_proteins_calculated_mysql, $inp_food_salt_calculated_mysql, $inp_food_sodium_calculated_mysql, $inp_food_barcode_mysql, &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;$inp_food_main_category_id_mysql, $inp_food_sub_category_id_mysql, $inp_food_image_path_mysql, $inp_food_image_a_mysql, $inp_food_thumb_a_small_mysql, &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;$inp_food_thumb_a_medium_mysql, $inp_food_thumb_a_large_mysql, $inp_food_image_b_mysql, $inp_food_thumb_b_small_mysql, $inp_food_thumb_b_medium_mysql, &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;$inp_food_thumb_b_large_mysql, $inp_food_image_c_mysql, $inp_food_thumb_c_small_mysql, $inp_food_thumb_c_medium_mysql, $inp_food_thumb_c_large_mysql, &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;$inp_food_image_d_mysql, $inp_food_thumb_d_small_mysql, $inp_food_thumb_d_medium_mysql, $inp_food_thumb_d_large_mysql, $inp_food_image_e_mysql, &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;$inp_food_thumb_e_small_mysql, $inp_food_thumb_e_medium_mysql, $inp_food_thumb_e_large_mysql, $inp_food_last_used_mysql, $inp_food_language_mysql, &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;$inp_food_synchronized_mysql, $inp_food_accepted_as_master_mysql, $inp_food_notes_mysql, $inp_food_unique_hits_mysql, '', &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;$inp_food_comments_mysql, $inp_food_likes_mysql, $inp_food_dislikes_mysql, '', '', &quot; +
		&nbsp; &nbsp; &nbsp; &nbsp; &quot;$inp_food_created_date_mysql, $inp_food_last_viewed_mysql, $inp_food_age_restriction_mysql)";
	$insert_count++;
	
	// End insert count
	if($insert_count > 10){
		// Insertion block finished
		echo"&quot;
		&nbsp; &nbsp; &nbsp; &nbsp; db!!.query(q$q_count)
		";
		$insert_count = 0;
		$q_count++;
	}
} // while categories

// End insert count
if($insert_count != 0){
	// Insertion block finished
	echo"&quot;
	&nbsp; &nbsp; &nbsp; &nbsp; db!!.query(q$q_count)
	";
}

echo"&nbsp; &nbsp; } // insertFoodIndex




} // class
</textarea>
";
?>