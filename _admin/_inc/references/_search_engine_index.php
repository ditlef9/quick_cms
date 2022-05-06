<?php
/**
*
* File: _admin/_inc/references/_search_engine_index.php
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
$t_references_liquidbase 	 = $mysqlPrefixSav . "references_liquidbase";


$t_references_title_translations 	= $mysqlPrefixSav . "references_title_translations";
$t_references_categories_main	 	= $mysqlPrefixSav . "references_categories_main";
$t_references_categories_sub 	 	= $mysqlPrefixSav . "references_categories_sub";
$t_references_index		 	= $mysqlPrefixSav . "references_index";
$t_references_index_groups	 	= $mysqlPrefixSav . "references_index_groups";
$t_references_index_guides	 	= $mysqlPrefixSav . "references_index_guides";
$t_references_index_guides_comments	= $mysqlPrefixSav . "references_index_guides_comments";

/*- Variables ---------------------------------------------------------------------------- */
$datetime = date("Y-m-d H:i:s");
$datetime_saying = date("j M Y H:i");

$query_exists = "SELECT * FROM $t_references_index";
$result_exists = mysqli_query($link, $query);
if($result_exists !== FALSE){

	


	/* references index */
	$query_w = "SELECT reference_id, reference_title, reference_title_clean, reference_title_short, reference_title_length, reference_is_active, reference_front_page_intro, reference_description, reference_language, reference_main_category_id, reference_main_category_title, reference_sub_category_id, reference_sub_category_title, reference_image_file, reference_image_thumb, reference_icon_16, reference_icon_32, reference_icon_48, reference_icon_64, reference_icon_96, reference_icon_260, reference_groups_count, reference_guides_count, reference_read_times, reference_read_times_ip_block, reference_created, reference_updated FROM $t_references_index";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_reference_id, $get_reference_title, $get_reference_title_clean, $get_reference_title_short, $get_reference_title_length, $get_reference_is_active, $get_reference_front_page_intro, $get_reference_description, $get_reference_language, $get_reference_main_category_id, $get_reference_main_category_title, $get_reference_sub_category_id, $get_reference_sub_category_title, $get_reference_image_file, $get_reference_image_thumb, $get_reference_icon_16, $get_reference_icon_32, $get_reference_icon_48, $get_reference_icon_64, $get_reference_icon_96, $get_reference_icon_260, $get_reference_groups_count, $get_reference_guides_count, $get_reference_read_times, $get_reference_read_times_ip_block, $get_reference_created, $get_reference_updated) = $row_w;

	
		// Reference title
		$l_mysql = quote_smart($link, $get_reference_language);
		$query = "SELECT reference_title_translation_id, reference_title_translation_title FROM $t_references_title_translations WHERE reference_title_translation_language=$l_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_reference_title_translation_id, $get_current_reference_title_translation_title) = $row;


		$inp_index_title = "$get_reference_title | $get_current_reference_title_translation_title";
		$inp_index_title_mysql = quote_smart($link, $inp_index_title);

		$inp_index_url = "$get_reference_title_clean";
		$inp_index_url_mysql = quote_smart($link, $inp_index_url);

		$inp_index_short_description = substr($get_reference_front_page_intro, 0, 200);
		$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

		// tags
		$inp_index_keywords = "";
		$inp_index_keywords_mysql = quote_smart($link, "$inp_index_keywords");

		$inp_index_module_name_mysql = quote_smart($link, "references");

		$inp_index_module_part_name_mysql = quote_smart($link, "references");

		$inp_index_reference_name_mysql = quote_smart($link, "reference_id");
		$inp_index_reference_id_mysql = quote_smart($link, "$get_reference_id");

		$inp_index_has_access_control_mysql = quote_smart($link, 0);

		$inp_index_is_ad_mysql = quote_smart($link, 0);
	
		$inp_index_language_mysql = quote_smart($link, $get_reference_language);


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


		// Groups
		$query_g = "SELECT group_id, group_title, group_title_clean, group_title_short, group_title_length, group_number, group_reference_id, group_reference_title, group_read_times, group_read_times_ip_block, group_created_datetime, group_updated_datetime, group_updated_formatted, group_last_read, group_last_read_formatted FROM $t_references_index_groups WHERE group_reference_id=$get_reference_id";
		$result_g = mysqli_query($link, $query_g);
		while($row_g = mysqli_fetch_row($result_g)) {
			list($get_group_id, $get_group_title, $get_group_title_clean, $get_group_title_short, $get_group_title_length, $get_group_number, $get_group_reference_id, $get_group_reference_title, $get_group_read_times, $get_group_read_times_ip_block, $get_group_created_datetime, $get_group_updated_datetime, $get_group_updated_formatted, $get_group_last_read, $get_group_last_read_formatted) = $row_g;




			$inp_index_title = "$get_group_title | $get_reference_title | $get_current_reference_title_translation_title";
			$inp_index_title_mysql = quote_smart($link, $inp_index_title);

			$inp_index_url = "$get_reference_title_clean/index.php?reference_id=$get_reference_id&group_id=$get_group_id";
			$inp_index_url_mysql = quote_smart($link, $inp_index_url);

			$inp_index_short_description = "";
			$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

			// tags
			$inp_index_keywords = "";
			$inp_index_keywords_mysql = quote_smart($link, "$inp_index_keywords");

			$inp_index_module_name_mysql = quote_smart($link, "references");

			$inp_index_module_part_name_mysql = quote_smart($link, "groups");

			$inp_index_reference_name_mysql = quote_smart($link, "group_id");
			$inp_index_reference_id_mysql = quote_smart($link, "$get_group_id");

			$inp_index_has_access_control_mysql = quote_smart($link, 0);

			$inp_index_is_ad_mysql = quote_smart($link, 0);
	
			$inp_index_language_mysql = quote_smart($link, $get_reference_language);

			
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


			// Guides
			$query_guides = "SELECT guide_id, guide_number, guide_title, guide_title_clean, guide_title_short, guide_title_length, guide_short_description, guide_group_id, guide_group_title, guide_reference_id, guide_reference_title, guide_read_times, guide_read_ipblock, guide_created, guide_updated, guide_updated_formatted, guide_last_read, guide_last_read_formatted, guide_comments FROM $t_references_index_guides WHERE guide_group_id=$get_group_id";
			$result_guides = mysqli_query($link, $query_guides);
			while($row_guides = mysqli_fetch_row($result_guides)) {
				list($get_guide_id, $get_guide_number, $get_guide_title, $get_guide_title_clean, $get_guide_title_short, $get_guide_title_length, $get_guide_short_description, $get_guide_group_id, $get_guide_group_title, $get_guide_reference_id, $get_guide_reference_title, $get_guide_read_times, $get_guide_read_ipblock, $get_guide_created, $get_guide_updated, $get_guide_updated_formatted, $get_guide_last_read, $get_guide_last_read_formatted, $get_guide_comments) = $row_guides;


				$inp_index_title = "$get_guide_title | $get_group_title | $get_reference_title | $get_current_reference_title_translation_title";
				$inp_index_title_mysql = quote_smart($link, $inp_index_title);

				$inp_index_url = "$get_reference_title_clean/$get_group_title_clean/$get_guide_title_clean.php?reference_id=$get_reference_id&group_id=$get_group_id&guide_id=$get_guide_id";
				$inp_index_url_mysql = quote_smart($link, $inp_index_url);

				$inp_index_short_description = "";
				$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

				// tags
				$inp_index_keywords = "";
				$inp_index_keywords_mysql = quote_smart($link, "$inp_index_keywords");

				$inp_index_module_name_mysql = quote_smart($link, "references");

				$inp_index_module_part_name_mysql = quote_smart($link, "guides");

				$inp_index_reference_name_mysql = quote_smart($link, "guide_id");
				$inp_index_reference_id_mysql = quote_smart($link, "$get_guide_id");

				$inp_index_has_access_control_mysql = quote_smart($link, 0);

				$inp_index_is_ad_mysql = quote_smart($link, 0);
	
				$inp_index_language_mysql = quote_smart($link, $get_reference_language);

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


			} // guides

		} // groups

	} // all references

	

} // table exists
?>