<?php
/**
*
* File: _admin/_inc/recipes/edit_recipe_include_update_search_engine.php
* Version 1.0.0
* Date 11:43 12.11.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/



if(isset($get_recipe_id) && $process == 1){
	/*- Tables ---------------------------------------------------------------------------- */
	$t_search_engine_index = $mysqlPrefixSav . "search_engine_index";


	// Find updated recipe info
	$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_country, recipe_introduction, recipe_directions, recipe_image_path, recipe_image_h_a, recipe_image_h_b, recipe_image_v_a, recipe_thumb_h_a_278x156, recipe_thumb_h_b_278x156, recipe_video_h, recipe_video_v, recipe_date, recipe_date_saying, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_times_favorited, recipe_user_ip, recipe_notes, recipe_password, recipe_last_viewed, recipe_age_restriction, recipe_published FROM $t_recipes WHERE recipe_id=$get_recipe_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_country, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image_h_a, $get_recipe_image_h_b, $get_recipe_image_v_a, $get_recipe_thumb_h_a_278x156, $get_recipe_thumb_h_b_278x156, $get_recipe_video_h, $get_recipe_video_v, $get_recipe_date, $get_recipe_date_saying, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_times_favorited, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password, $get_recipe_last_viewed, $get_recipe_age_restriction, $get_recipe_published) = $row;

	if($get_recipe_id != ""){
	
		// Dates
		$datetime = date("Y-m-d H:i:s");
		$datetime_saying = date("j. M Y H:i");



		// Search engine
		$inp_index_title = "$get_recipe_title"; 
		$inp_index_title_mysql = quote_smart($link, $inp_index_title);

		$inp_index_url = "recipes/view_recipe.php?recipe_id=$get_recipe_id";
		$inp_index_url_mysql = quote_smart($link, $inp_index_url);

		$inp_index_short_description = substr($get_recipe_introduction, 0, 200);
		$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

		// tags
		$inp_index_keywords = "";
		$query_r = "SELECT tag_id, tag_language, tag_recipe_id, tag_title, tag_title_clean, tag_user_id FROM $t_recipes_tags WHERE tag_recipe_id=$get_recipe_id";
		$result_r = mysqli_query($link, $query_r);
		while($row_r = mysqli_fetch_row($result_r)) {
			list($get_tag_id, $get_tag_language, $get_tag_recipe_id, $get_tag_title, $get_tag_title_clean, $get_tag_user_id) = $row_r;
			if($inp_index_keywords == ""){
				$inp_index_keywords  = "$get_tag_title";
			}
			else{
				$inp_index_keywords  = $inp_index_keywords . ", $get_tag_title";
			}
		}
		$inp_index_keywords_mysql = quote_smart($link, $inp_index_keywords);

		// Image
		$inp_index_image_path_mysql = quote_smart($link, $get_recipe_image_path);
		$inp_index_image_file_mysql = quote_smart($link, $get_recipe_image_h_a);

		// Thumb
		$thumb = "";
		if($get_recipe_image_h_a != ""){
			$ext = get_extension($get_recipe_image_h_a);
			$thumb = str_replace(".$ext", "", $get_recipe_image_h_a);
			$thumb = $thumb . "_235x132." . $ext;
		}
		$inp_index_image_thumb_mysql = quote_smart($link, $thumb);


		$inp_index_module_name_mysql = quote_smart($link, "recipes");

		$inp_index_module_part_name_mysql = quote_smart($link, "recipes");

		$inp_index_reference_name_mysql = quote_smart($link, "recipe_id");
		$inp_index_reference_id_mysql = quote_smart($link, "$get_recipe_id");

		$inp_index_has_access_control_mysql = quote_smart($link, 0);

		$inp_index_is_ad_mysql = quote_smart($link, 0);
	
		$inp_index_language_mysql = quote_smart($link, "$get_recipe_language");

		// Check if exists
		$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name=$inp_index_module_name_mysql AND index_reference_name=$inp_index_reference_name_mysql AND index_reference_id=$inp_index_reference_id_mysql";
		$result_exists = mysqli_query($link, $query_exists);
		$row_exists = mysqli_fetch_row($result_exists);
		list($get_index_id) = $row_exists;
		if($get_index_id == ""){
			// Insert
			// echo"<span>Insert $inp_index_title<br /></span>\n";
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
			// echo"<span>Update $inp_index_title<br /></span>\n";
			mysqli_query($link, "UPDATE $t_search_engine_index SET
						index_title=$inp_index_title_mysql,
						index_short_description=$inp_index_short_description_mysql,
						index_keywords=$inp_index_keywords_mysql, 
						index_image_path=$inp_index_image_path_mysql, 
						index_image_file=$inp_index_image_file_mysql, 
						index_image_thumb_235x132=$inp_index_image_thumb_mysql
						WHERE index_id=$get_index_id") or die(mysqli_error($link));


		}
	} // recipe found
} // isset recipe
?>