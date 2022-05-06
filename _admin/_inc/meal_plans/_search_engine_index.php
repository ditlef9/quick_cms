<?php
/**
*
* File: _admin/_inc/food/_search_engine_index.php
* Version 21:08 16.01.2020
* Copyright (c) 2008-2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables ---------------------------------------------------------------------------- */
$t_meal_plans 		= $mysqlPrefixSav . "meal_plans";
$t_meal_plans_days	= $mysqlPrefixSav . "meal_plans_days";
$t_meal_plans_meals	= $mysqlPrefixSav . "meal_plans_meals";
$t_meal_plans_entries	= $mysqlPrefixSav . "meal_plans_entries";

/*- Variables ---------------------------------------------------------------------------- */
$datetime = date("Y-m-d H:i:s");
$datetime_saying = date("j. M Y H:i");

$query_exists = "SELECT * FROM $t_meal_plans";
$result_exists = mysqli_query($link, $query);
if($result_exists !== FALSE){

	


	/* meal plans index */
	$query_w = "SELECT meal_plan_id, meal_plan_user_id, meal_plan_language, meal_plan_title, meal_plan_title_clean, meal_plan_number_of_days, meal_plan_introduction, meal_plan_total_energy_without_training, meal_plan_total_fat_without_training, meal_plan_total_carb_without_training, meal_plan_total_protein_without_training, meal_plan_total_energy_with_training, meal_plan_total_fat_with_training, meal_plan_total_carb_with_training, meal_plan_total_protein_with_training, meal_plan_average_kcal_without_training, meal_plan_average_fat_without_training, meal_plan_average_carb_without_training, meal_plan_average_protein_without_training, meal_plan_average_kcal_with_training, meal_plan_average_fat_with_training, meal_plan_average_carb_with_training, meal_plan_average_protein_with_training, meal_plan_created, meal_plan_updated, meal_plan_user_ip, meal_plan_image_path, meal_plan_image_thumb, meal_plan_image_file, meal_plan_views, meal_plan_views_ip_block, meal_plan_likes, meal_plan_dislikes, meal_plan_rating, meal_plan_rating_ip_block, meal_plan_comments FROM $t_meal_plans";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_meal_plan_id, $get_meal_plan_user_id, $get_meal_plan_language, $get_meal_plan_title, $get_meal_plan_title_clean, $get_meal_plan_number_of_days, $get_meal_plan_introduction, $get_meal_plan_total_energy_without_training, $get_meal_plan_total_fat_without_training, $get_meal_plan_total_carb_without_training, $get_meal_plan_total_protein_without_training, $get_meal_plan_total_energy_with_training, $get_meal_plan_total_fat_with_training, $get_meal_plan_total_carb_with_training, $get_meal_plan_total_protein_with_training, $get_meal_plan_average_kcal_without_training, $get_meal_plan_average_fat_without_training, $get_meal_plan_average_carb_without_training, $get_meal_plan_average_protein_without_training, $get_meal_plan_average_kcal_with_training, $get_meal_plan_average_fat_with_training, $get_meal_plan_average_carb_with_training, $get_meal_plan_average_protein_with_training, $get_meal_plan_created, $get_meal_plan_updated, $get_meal_plan_user_ip, $get_meal_plan_image_path, $get_meal_plan_image_thumb, $get_meal_plan_image_file, $get_meal_plan_views, $get_meal_plan_views_ip_block, $get_meal_plan_likes, $get_meal_plan_dislikes, $get_meal_plan_rating, $get_meal_plan_rating_ip_block, $get_meal_plan_comments) = $row_w;

	

		$inp_index_title = "$get_meal_plan_title";
		$inp_index_title_mysql = quote_smart($link, $inp_index_title);

		$inp_index_url = "meal_plans/meal_plan_view_1.php?meal_plan_id=$get_meal_plan_id";
		$inp_index_url_mysql = quote_smart($link, $inp_index_url);

		$inp_index_short_description = substr($get_meal_plan_introduction, 0, 200);
		$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

		// tags
		$inp_index_keywords = "";
		$inp_index_keywords_mysql = quote_smart($link, "$inp_index_keywords");

		$inp_index_module_name_mysql = quote_smart($link, "meal_plans");

		$inp_index_module_part_name_mysql = quote_smart($link, "");

		$inp_index_reference_name_mysql = quote_smart($link, "meal_plan_id");

		$inp_index_reference_id_mysql = quote_smart($link, "$get_meal_plan_id");

		$inp_index_has_access_control_mysql = quote_smart($link, 0);

		$inp_index_is_ad_mysql = quote_smart($link, 0);
	
		$inp_index_language_mysql = quote_smart($link, $get_meal_plan_language);

		// Check if exists
		$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name=$inp_index_module_name_mysql AND index_reference_name=$inp_index_reference_name_mysql AND index_reference_id=$inp_index_reference_id_mysql";
		$result_exists = mysqli_query($link, $query_exists);
		$row_exists = mysqli_fetch_row($result_exists);
		list($get_index_id) = $row_exists;
		if($get_index_id == ""){
			// Insert
			echo"<span>Insert $inp_index_title<br /></span>\n";
			mysqli_query($link, "INSERT INTO $t_search_engine_index 
			(index_id, index_title, index_url, index_short_description, index_keywords, 
			index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
			index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
			index_unique_hits) 
			VALUES 
			(NULL, $inp_index_title_mysql, $inp_index_url_mysql, $inp_index_short_description_mysql, $inp_index_keywords_mysql, 
			$inp_index_module_name_mysql, $inp_index_module_part_name_mysql, '0', $inp_index_reference_name_mysql, $inp_index_reference_id_mysql,
			'0', $inp_index_is_ad_mysql, '$datetime', '$datetime_saying', $inp_index_language_mysql,
			0)")
			or die(mysqli_error($link));
		}

		
	} // meal plans




} // table exists
?>