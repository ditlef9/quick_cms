<?php
/**
*
* File: exercises/new_exercise_step_00_add_update_search_engine.php
* Version 1.0.0
* Date 19:40 18.10.2020
* Copyright (c) 2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/


/*- Tables ---------------------------------------------------------------------------- */
include("_tables_exercises.php");
/*- Tables ------------------------------------------------------------------------- */
$t_search_engine_index = $mysqlPrefixSav . "search_engine_index";


// Select food
if(isset($get_exercise_id) && $get_exercise_id != "" && is_numeric($get_exercise_id)){
	$query = "SELECT exercise_id, exercise_title, exercise_title_clean, exercise_title_alternative, exercise_user_id, exercise_language, exercise_muscle_group_id_main, exercise_muscle_group_id_sub, exercise_muscle_part_of_id, exercise_equipment_id, exercise_type_id, exercise_level_id, exercise_preparation, exercise_guide, exercise_important, exercise_created_datetime, exercise_updated_datetime, exercise_user_ip, exercise_uniqe_hits, exercise_uniqe_hits_ip_block, exercise_likes, exercise_dislikes, exercise_rating, exercise_rating_ip_block, exercise_number_of_comments, exercise_reported, exercise_reported_checked, exercise_reported_reason, exercise_last_viewed FROM $t_exercise_index WHERE exercise_id=$get_exercise_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_exercise_id, $get_exercise_title, $get_exercise_title_clean, $get_exercise_title_alternative, $get_exercise_user_id, $get_exercise_language, $get_exercise_muscle_group_id_main, $get_exercise_muscle_group_id_sub, $get_exercise_muscle_part_of_id, $get_exercise_equipment_id, $get_exercise_type_id, $get_exercise_level_id, $get_exercise_preparation, $get_exercise_guide, $get_exercise_important, $get_exercise_created_datetime, $get_exercise_updated_datetime, $get_exercise_user_ip, $get_exercise_uniqe_hits, $get_exercise_uniqe_hits_ip_block, $get_exercise_likes, $get_exercise_dislikes, $get_exercise_rating, $get_exercise_rating_ip_block, $get_exercise_number_of_comments, $get_exercise_reported, $get_exercise_reported_checked, $get_exercise_reported_reason, $get_exercise_last_viewed) = $row;

	if($get_exercise_id != ""){
		// Dates
		$datetime = date("Y-m-d H:i:s");
		$datetime_saying = date("j. M Y H:i");

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
		else{
			// Update 
			mysqli_query($link, "UPDATE $t_search_engine_index SET 
					index_title=$inp_index_title_mysql, 
					index_url=$inp_index_url_mysql, 
					index_short_description=$inp_index_short_description_mysql, 
					index_keywords=$inp_index_keywords_mysql, 
					index_image_path=$inp_index_image_path_mysql, 
					index_image_file=$inp_index_image_file_mysql, 
					index_image_thumb_235x132=$inp_index_image_thumb_mysql
				WHERE index_id=$get_index_id") or die(mysqli_error($link));

		}
	} // exersice found
} // get exersice id found
/*- Footer ----------------------------------------------------------------------------------- */

?>