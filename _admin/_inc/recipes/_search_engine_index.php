<?php
/**
*
* File: _admin/_inc/recipes/_search_engine_index.php
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
$t_recipes 	 			= $mysqlPrefixSav . "recipes";
$t_recipes_ingredients			= $mysqlPrefixSav . "recipes_ingredients";
$t_recipes_groups			= $mysqlPrefixSav . "recipes_groups";
$t_recipes_items			= $mysqlPrefixSav . "recipes_items";
$t_recipes_numbers			= $mysqlPrefixSav . "recipes_numbers";
$t_recipes_rating			= $mysqlPrefixSav . "recipes_rating";
$t_recipes_cuisines			= $mysqlPrefixSav . "recipes_cuisines";
$t_recipes_cuisines_translations	= $mysqlPrefixSav . "recipes_cuisines_translations";
$t_recipes_seasons			= $mysqlPrefixSav . "recipes_seasons";
$t_recipes_seasons_translations		= $mysqlPrefixSav . "recipes_seasons_translations";
$t_recipes_occasions			= $mysqlPrefixSav . "recipes_occasions";
$t_recipes_occasions_translations	= $mysqlPrefixSav . "recipes_occasions_translations";
$t_recipes_categories			= $mysqlPrefixSav . "recipes_categories";
$t_recipes_categories_translations	= $mysqlPrefixSav . "recipes_categories_translations";
$t_recipes_measurements			= $mysqlPrefixSav . "recipes_measurements";
$t_recipes_measurements_translations	= $mysqlPrefixSav . "recipes_measurements_translations";
$t_recipes_weekly_special		= $mysqlPrefixSav . "recipes_weekly_special";
$t_recipes_of_the_day			= $mysqlPrefixSav . "recipes_of_the_day";
$t_recipes_comments			= $mysqlPrefixSav . "recipes_comments";
$t_recipes_favorites			= $mysqlPrefixSav . "recipes_favorites";
$t_recipes_tags				= $mysqlPrefixSav . "recipes_tags";
$t_recipes_links			= $mysqlPrefixSav . "recipes_links";
$t_recipes_comments			= $mysqlPrefixSav . "recipes_comments";
$t_recipes_searches			= $mysqlPrefixSav . "recipes_searches";
$t_recipes_age_restrictions 	 	= $mysqlPrefixSav . "recipes_age_restrictions";
$t_recipes_age_restrictions_accepted	= $mysqlPrefixSav . "recipes_age_restrictions_accepted";

/*- Functions ---------------------------------------------------------------------------- */
include("_functions/get_extension.php");

/*- Variables ---------------------------------------------------------------------------- */
$datetime = date("Y-m-d H:i:s");
$datetime_saying = date("j. M Y H:i");

$query_exists = "SELECT * FROM $t_recipes";
$result_exists = mysqli_query($link, $query);
if($result_exists !== FALSE){

	
	/* recipes */
	$query_w = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_country, recipe_introduction, recipe_directions, recipe_image_path, recipe_image, recipe_video, recipe_date, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_user_ip, recipe_notes, recipe_password, recipe_last_viewed, recipe_age_restriction FROM $t_recipes";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_country, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image, $get_recipe_video, $get_recipe_date, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password, $get_recipe_last_viewed, $get_recipe_age_restriction) = $row_w;


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
		$inp_index_image_file_mysql = quote_smart($link, $get_recipe_image);

		// Thumb
		$thumb = "";
		if($get_recipe_image != ""){
			$ext = get_extension($get_recipe_image);
			$thumb = str_replace(".$ext", "", $get_recipe_image);
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
			echo"<span>Insert $inp_index_title<br /></span>\n";
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

		
	} // recipes



	/* recipes categories */
	$query_w = "SELECT category_translation_id, category_id, category_translation_language, category_translation_value, category_translation_no_recipes, category_translation_image_path, category_translation_image, category_translation_image_updated_week, category_translation_last_updated FROM $t_recipes_categories_translations";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_category_translation_id, $get_category_id, $get_category_translation_language, $get_category_translation_value, $get_category_translation_no_recipes, $get_category_translation_image_path, $get_category_translation_image, $get_category_translation_image_updated_week, $get_category_translation_last_updated) = $row_w;


		$inp_index_title = "$get_category_translation_value"; 
		$inp_index_title_mysql = quote_smart($link, $inp_index_title);

		$inp_index_url = "recipes/categories_browse.php?category_id=$get_category_id";
		$inp_index_url_mysql = quote_smart($link, $inp_index_url);

		$inp_index_short_description = substr("", 0, 200);
		$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

		// tags
		$inp_index_keywords = "";
		$inp_index_keywords_mysql = quote_smart($link, $inp_index_keywords);

		$inp_index_module_name_mysql = quote_smart($link, "recipes");

		$inp_index_module_part_name_mysql = quote_smart($link, "categories");

		$inp_index_reference_name_mysql = quote_smart($link, "category_id");
		$inp_index_reference_id_mysql = quote_smart($link, "$get_category_id");

		$inp_index_has_access_control_mysql = quote_smart($link, 0);

		$inp_index_is_ad_mysql = quote_smart($link, 0);
	
		$inp_index_language_mysql = quote_smart($link, "$get_category_translation_language");

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



		
	} // categories



	/* recipes cusines */
	$query_w = "SELECT cuisine_translation_id, cuisine_id, cuisine_translation_language, cuisine_translation_value, cuisine_translation_no_recipes, cuisine_translation_last_updated FROM $t_recipes_cuisines_translations";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_cuisine_translation_id, $get_cuisine_id, $get_cuisine_translation_language, $get_cuisine_translation_value, $get_cuisine_translation_no_recipes, $get_cuisine_translation_last_updated) = $row_w;


		$inp_index_title = "$get_cuisine_translation_value"; 
		$inp_index_title_mysql = quote_smart($link, $inp_index_title);

		$inp_index_url = "recipes/cuisines_browse.php?cuisine_id=$get_cuisine_id";
		$inp_index_url_mysql = quote_smart($link, $inp_index_url);

		$inp_index_short_description = substr("", 0, 200);
		$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

		// tags
		$inp_index_keywords = "";
		$inp_index_keywords_mysql = quote_smart($link, $inp_index_keywords);

		$inp_index_module_name_mysql = quote_smart($link, "recipes");

		$inp_index_module_part_name_mysql = quote_smart($link, "cuisines");

		$inp_index_reference_name_mysql = quote_smart($link, "cuisine_id");
		$inp_index_reference_id_mysql = quote_smart($link, "$get_cuisine_id");

		$inp_index_has_access_control_mysql = quote_smart($link, 0);

		$inp_index_is_ad_mysql = quote_smart($link, 0);
	
		$inp_index_language_mysql = quote_smart($link, "$get_cuisine_translation_language");

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

		
	} // cuisine



	/* recipes occasions */
	$query_w = "SELECT occasion_translation_id, occasion_id, occasion_translation_language, occasion_translation_value, occasion_translation_no_recipes, occasion_translation_last_updated FROM $t_recipes_occasions_translations";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_occasion_translation_id, $get_occasion_id, $get_occasion_translation_language, $get_occasion_translation_value, $get_occasion_translation_no_recipes, $get_occasion_translation_last_updated) = $row_w;


		$inp_index_title = "$get_occasion_translation_value"; 
		$inp_index_title_mysql = quote_smart($link, $inp_index_title);

		$inp_index_url = "recipes/occasions_browse.php?occasion_id=$get_occasion_id";
		$inp_index_url_mysql = quote_smart($link, $inp_index_url);

		$inp_index_short_description = substr("", 0, 200);
		$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

		// tags
		$inp_index_keywords = "";
		$inp_index_keywords_mysql = quote_smart($link, $inp_index_keywords);

		$inp_index_module_name_mysql = quote_smart($link, "recipes");

		$inp_index_module_part_name_mysql = quote_smart($link, "occasions");

		$inp_index_reference_name_mysql = quote_smart($link, "occasion_id");
		$inp_index_reference_id_mysql = quote_smart($link, "$get_occasion_id");

		$inp_index_has_access_control_mysql = quote_smart($link, 0);

		$inp_index_is_ad_mysql = quote_smart($link, 0);
	
		$inp_index_language_mysql = quote_smart($link, "$get_occasion_translation_language");

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


		
	} // occasions



	/* recipes seasons */
	$query_w = "SELECT season_translation_id, season_id, season_translation_language, season_translation_value, season_translation_no_recipes, season_translation_last_updated FROM $t_recipes_seasons_translations";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_season_translation_id, $get_season_id, $get_season_translation_language, $get_season_translation_value, $get_season_translation_no_recipes, $get_season_translation_last_updated) = $row_w;


		$inp_index_title = "$get_season_translation_value"; 
		$inp_index_title_mysql = quote_smart($link, $inp_index_title);

		$inp_index_url = "recipes/seasons_browse.php?season_id=$get_season_id";
		$inp_index_url_mysql = quote_smart($link, $inp_index_url);

		$inp_index_short_description = substr("", 0, 200);
		$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

		// tags
		$inp_index_keywords = "";
		$inp_index_keywords_mysql = quote_smart($link, $inp_index_keywords);

		$inp_index_module_name_mysql = quote_smart($link, "recipes");

		$inp_index_module_part_name_mysql = quote_smart($link, "seasons");

		$inp_index_reference_name_mysql = quote_smart($link, "season_id");
		$inp_index_reference_id_mysql = quote_smart($link, "$get_season_id");

		$inp_index_has_access_control_mysql = quote_smart($link, 0);

		$inp_index_is_ad_mysql = quote_smart($link, 0);
	
		$inp_index_language_mysql = quote_smart($link, "$get_season_translation_language");

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

		
	} // seasons

} // table exists
?>