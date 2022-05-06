<?php
/**
*
* File: _admin/_inc/exercises/_search_engine_index.php
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
$t_exercise_index 				= $mysqlPrefixSav . "exercise_index";
$t_exercise_index_images			= $mysqlPrefixSav . "exercise_index_images";
$t_exercise_equipments 				= $mysqlPrefixSav . "exercise_equipments";

/*- Functions ---------------------------------------------------------------------------- */
include("_functions/get_extension.php");


/*- Variables ---------------------------------------------------------------------------- */
$datetime = date("Y-m-d H:i:s");
$datetime_saying = date("j. M Y H:i");

$query_exists = "SELECT * FROM $t_exercise_index";
$result_exists = mysqli_query($link, $query);
if($result_exists !== FALSE){

	/* Exercise index */
	$query_w = "SELECT exercise_id, exercise_title, exercise_title_clean, exercise_title_alternative, exercise_user_id, exercise_language, exercise_muscle_group_id_main, exercise_muscle_group_id_sub, exercise_muscle_part_of_id, exercise_equipment_id, exercise_type_id, exercise_level_id, exercise_preparation, exercise_guide, exercise_important, exercise_created_datetime, exercise_updated_datetime, exercise_user_ip, exercise_uniqe_hits, exercise_uniqe_hits_ip_block, exercise_likes, exercise_dislikes, exercise_rating, exercise_rating_ip_block, exercise_number_of_comments, exercise_reported, exercise_reported_checked, exercise_reported_reason, exercise_last_viewed FROM $t_exercise_index";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_exercise_id, $get_exercise_title, $get_exercise_title_clean, $get_exercise_title_alternative, $get_exercise_user_id, $get_exercise_language, $get_exercise_muscle_group_id_main, $get_exercise_muscle_group_id_sub, $get_exercise_muscle_part_of_id, $get_exercise_equipment_id, $get_exercise_type_id, $get_exercise_level_id, $get_exercise_preparation, $get_exercise_guide, $get_exercise_important, $get_exercise_created_datetime, $get_exercise_updated_datetime, $get_exercise_user_ip, $get_exercise_uniqe_hits, $get_exercise_uniqe_hits_ip_block, $get_exercise_likes, $get_exercise_dislikes, $get_exercise_rating, $get_exercise_rating_ip_block, $get_exercise_number_of_comments, $get_exercise_reported, $get_exercise_reported_checked, $get_exercise_reported_reason, $get_exercise_last_viewed) = $row_w;

		// Image
		$query_exists = "SELECT exercise_image_id, exercise_image_path, exercise_image_file FROM $t_exercise_index_images WHERE exercise_image_exercise_id=$get_exercise_id AND exercise_image_type='guide_1'";
		$result_exists = mysqli_query($link, $query_exists);
		$row_exists = mysqli_fetch_row($result_exists);
		list($get_exercise_image_id, $get_exercise_image_path, $get_exercise_image_file) = $row_exists;


		// Input fields
		$inp_index_title = "$get_exercise_title";
		$inp_index_title_mysql = quote_smart($link, $inp_index_title);

		$inp_index_url = "exercises/view_exercise.php?exercise_id=$get_exercise_id&type_id=$get_exercise_type_id&main_muscle_group_id=$get_exercise_muscle_group_id_main";
		$inp_index_url_mysql = quote_smart($link, $inp_index_url);

		$inp_index_short_description = substr($get_exercise_preparation, 0, 200);
		$inp_index_short_description = output_html($inp_index_short_description);
		$inp_index_short_description = str_replace("\xc2\xa0", ' ', $inp_index_short_description);
		$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

		$inp_index_keywords = "";
		$inp_index_keywords_mysql = quote_smart($link, "$inp_index_keywords");

		$inp_index_module_name_mysql = quote_smart($link, "exercises");

		$inp_index_module_part_name_mysql = quote_smart($link, "exercises");

		$inp_index_reference_name_mysql = quote_smart($link, "exercise_id");

		$inp_index_reference_id_mysql = quote_smart($link, "$get_exercise_id");

		$inp_index_is_ad_mysql = quote_smart($link, 0);
	
		$inp_index_language_mysql = quote_smart($link, $get_exercise_language);


		// Image
		$inp_index_image_path_mysql = quote_smart($link, $get_exercise_image_path);
		$inp_index_image_file_mysql = quote_smart($link, $get_exercise_image_file);

		// Thumb
		$thumb = "";
		if($get_exercise_image_file != ""){
			$ext = get_extension($get_exercise_image_file);
			$thumb = str_replace(".$ext", "", $get_exercise_image_file);
			$thumb = $thumb . "_235x132." . $ext;
		}
		$inp_index_image_thumb_mysql = quote_smart($link, $thumb);



		// Check if exists
		$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name=$inp_index_module_name_mysql AND index_reference_name=$inp_index_reference_name_mysql AND index_reference_id=$inp_index_reference_id_mysql";
		$result_exists = mysqli_query($link, $query_exists);
		$row_exists = mysqli_fetch_row($result_exists);
		list($get_index_id) = $row_exists;
		if($get_index_id == ""){
			// Insert
			echo"<span>Insert $inp_index_title with index_short_description=$inp_index_short_description<br /></span>\n";
			mysqli_query($link, "INSERT INTO $t_search_engine_index 
			(index_id, index_title, index_url, index_short_description, index_keywords, 
			index_image_path, index_image_file, index_image_thumb_235x132, 
			index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
			index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
			index_unique_hits) 
			VALUES 
			(NULL, $inp_index_title_mysql, $inp_index_url_mysql, $inp_index_short_description_mysql, $inp_index_keywords_mysql, 
			$inp_index_image_path_mysql, $inp_index_image_file_mysql, $inp_index_image_thumb_mysql, 
			$inp_index_module_name_mysql, $inp_index_module_part_name_mysql, '0', $inp_index_reference_name_mysql, $inp_index_reference_id_mysql,
			'0', $inp_index_is_ad_mysql, '$datetime', '$datetime_saying', $inp_index_language_mysql,
			0)")
			or die(mysqli_error($link));
		}
	} // all exercise 


	/* Equipments */
	$query_w = "SELECT equipment_id, equipment_title, equipment_title_clean, equipment_user_id, equipment_language, equipment_muscle_group_id_main, equipment_muscle_group_id_sub, equipment_muscle_part_of_id, equipment_type_id, equipment_text, equipment_image_path, equipment_image_file, equipment_created_datetime, equipment_updated_datetime, equipment_user_ip, equipment_uniqe_hits, equipment_uniqe_hits_ip_block, equipment_likes, equipment_dislikes, equipment_rating, equipment_rating_ip_block, equipment_number_of_comments, equipment_reported, equipment_reported_checked, equipment_reported_reason FROM $t_exercise_equipments";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_equipment_id, $get_equipment_title, $get_equipment_title_clean, $get_equipment_user_id, $get_equipment_language, $get_equipment_muscle_group_id_main, $get_equipment_muscle_group_id_sub, $get_equipment_muscle_part_of_id, $get_equipment_type_id, $get_equipment_text, $get_equipment_image_path, $get_equipment_image_file, $get_equipment_created_datetime, $get_equipment_updated_datetime, $get_equipment_user_ip, $get_equipment_uniqe_hits, $get_equipment_uniqe_hits_ip_block, $get_equipment_likes, $get_equipment_dislikes, $get_equipment_rating, $get_equipment_rating_ip_block, $get_equipment_number_of_comments, $get_equipment_reported, $get_equipment_reported_checked, $get_equipment_reported_reason) = $row_w;

		$inp_index_title = "$get_equipment_title";
		$inp_index_title_mysql = quote_smart($link, $inp_index_title);

		$inp_index_url = "exercises/view_equipment.php?equipment_id=$get_equipment_id";
		$inp_index_url_mysql = quote_smart($link, $inp_index_url);

		$inp_index_short_description = substr($get_equipment_text, 0, 200);
		$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

		$inp_index_keywords = "";
		$inp_index_keywords_mysql = quote_smart($link, "$inp_index_keywords");

		$inp_index_module_name_mysql = quote_smart($link, "exercises");

		$inp_index_module_part_name_mysql = quote_smart($link, "equipments");

		$inp_index_reference_name_mysql = quote_smart($link, "equipment_id");

		$inp_index_reference_id_mysql = quote_smart($link, "$get_equipment_id");

		$inp_index_is_ad_mysql = quote_smart($link, 0);
		
		$inp_index_language_mysql = quote_smart($link, $get_equipment_language);

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
	
	} // all equipments

}
?>