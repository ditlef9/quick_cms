<?php
/**
*
* File: _admin/_inc/workout_plans/_search_engine_index.php
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
$t_workout_plans_yearly  		= $mysqlPrefixSav . "workout_plans_yearly";
$t_workout_plans_period  		= $mysqlPrefixSav . "workout_plans_period";
$t_workout_plans_weekly  		= $mysqlPrefixSav . "workout_plans_weekly";
$t_workout_plans_weekly_tags  		= $mysqlPrefixSav . "workout_plans_weekly_tags";
$t_workout_plans_weekly_tags_unique  	= $mysqlPrefixSav . "workout_plans_weekly_tags_unique";
$t_workout_plans_sessions 		= $mysqlPrefixSav . "workout_plans_sessions";
$t_workout_plans_sessions_main 		= $mysqlPrefixSav . "workout_plans_sessions_main";
$t_workout_plans_favorites 		= $mysqlPrefixSav . "workout_plans_favorites";

/*- Variables ---------------------------------------------------------------------------- */
$datetime = date("Y-m-d H:i:s");
$datetime_saying = date("j. M Y H:i");

$query_exists = "SELECT * FROM $t_workout_plans_weekly";
$result_exists = mysqli_query($link, $query);
if($result_exists !== FALSE){


	/* $t_workout_plans_weekly */
	$query_w = "SELECT workout_weekly_id, workout_weekly_user_id, workout_weekly_period_id, workout_weekly_weight, workout_weekly_language, workout_weekly_title, workout_weekly_title_clean, workout_weekly_introduction, workout_weekly_goal, workout_weekly_image_path, workout_weekly_image_thumb_medium, workout_weekly_image_thumb_big, workout_weekly_image_file, workout_weekly_created, workout_weekly_updated, workout_weekly_unique_hits, workout_weekly_unique_hits_ip_block, workout_weekly_comments, workout_weekly_likes, workout_weekly_dislikes, workout_weekly_rating, workout_weekly_ip_block, workout_weekly_user_ip, workout_weekly_notes, workout_weekly_number_of_sessions FROM $t_workout_plans_weekly";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_workout_weekly_id, $get_workout_weekly_user_id, $get_workout_weekly_period_id, $get_workout_weekly_weight, $get_workout_weekly_language, $get_workout_weekly_title, $get_workout_weekly_title_clean, $get_workout_weekly_introduction, $get_workout_weekly_goal, $get_workout_weekly_image_path, $get_workout_weekly_image_thumb_medium, $get_workout_weekly_image_thumb_big, $get_workout_weekly_image_file, $get_workout_weekly_created, $get_workout_weekly_updated, $get_workout_weekly_unique_hits, $get_workout_weekly_unique_hits_ip_block, $get_workout_weekly_comments, $get_workout_weekly_likes, $get_workout_weekly_dislikes, $get_workout_weekly_rating, $get_workout_weekly_ip_block, $get_workout_weekly_user_ip, $get_workout_weekly_notes, $get_workout_weekly_number_of_sessions) = $row_w;
			
		
		$inp_index_title = "$get_workout_weekly_title";
		$inp_index_title_mysql = quote_smart($link, $inp_index_title);

		$inp_index_url = "workout_plans/weekly_workout_plan_view.php?weekly_id=$get_workout_weekly_id";
		$inp_index_url_mysql = quote_smart($link, $inp_index_url);

		$inp_index_short_description = substr($get_workout_weekly_introduction, 0, 200);
		$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

		// tags
		$inp_index_keywords = "";
		$inp_index_keywords_mysql = quote_smart($link, "$inp_index_keywords");

		$inp_index_module_name_mysql = quote_smart($link, "workout_plans");

		$inp_index_module_part_name_mysql = quote_smart($link, "workout_plan_weekly");

		$inp_index_reference_name_mysql = quote_smart($link, "workout_weekly_id");
		$inp_index_reference_id_mysql = quote_smart($link, "$get_workout_weekly_id");

		$inp_index_has_access_control_mysql = quote_smart($link, 0);

		$inp_index_is_ad_mysql = quote_smart($link, 0);
	
		$inp_index_language_mysql = quote_smart($link, $get_workout_weekly_language);

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

	} // workout plans weekly

} // table exists
?>