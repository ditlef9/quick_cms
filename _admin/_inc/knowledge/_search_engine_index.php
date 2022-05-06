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
$t_knowledge_spaces_index			= $mysqlPrefixSav . "knowledge_spaces_index";
$t_knowledge_spaces_categories			= $mysqlPrefixSav . "knowledge_spaces_categories";
$t_knowledge_spaces_members			= $mysqlPrefixSav . "knowledge_spaces_members";
$t_knowledge_spaces_requested_memberships	= $mysqlPrefixSav . "knowledge_spaces_requested_memberships";
$t_knowledge_spaces_favorites			= $mysqlPrefixSav . "knowledge_spaces_favorites";


$t_knowledge_pages_index			= $mysqlPrefixSav . "knowledge_pages_index";
$t_knowledge_pages_edit_history			= $mysqlPrefixSav . "knowledge_pages_edit_history";
$t_knowledge_pages_tags	    			= $mysqlPrefixSav . "knowledge_pages_tags";
$t_knowledge_pages_comments			= $mysqlPrefixSav . "knowledge_pages_comments";
$t_knowledge_pages_favorites    		= $mysqlPrefixSav . "knowledge_pages_favorites";
$t_knowledge_pages_view_history 		= $mysqlPrefixSav . "knowledge_pages_view_history";
$t_knowledge_pages_media	 		= $mysqlPrefixSav . "knowledge_pages_media";
$t_knowledge_pages_diagrams	 		= $mysqlPrefixSav . "knowledge_pages_diagrams";

$t_knowledge_preselected_subscribe		= $mysqlPrefixSav . "knowledge_preselected_subscribe";


$t_knowledge_home_page_user_remember 		= $mysqlPrefixSav . "knowledge_home_page_user_remember";

/*- Variables ---------------------------------------------------------------------------- */
$datetime = date("Y-m-d H:i:s");
$datetime_saying = date("j. M Y H:i");

$query_exists = "SELECT * FROM $t_knowledge_pages_index";
$result_exists = mysqli_query($link, $query);
if($result_exists !== FALSE){

	


	/* pages index */
	$query_w = "SELECT page_id, page_space_id, page_title, page_title_clean, page_description, page_text, page_language, page_parent_id, page_no_of_children, page_weight, page_allow_comments, page_no_of_comments, page_unique_hits, page_unique_hits_ip_block, page_unique_hits_user_id_block, page_created_datetime, page_created_date_saying, page_created_user_id, page_created_user_alias, page_created_user_email, page_created_user_image, page_created_subscribe_to_comments, page_updated_datetime, page_updated_date_saying, page_updated_user_id, page_updated_user_alias, page_updated_user_email, page_updated_user_image, page_updated_subscribe_to_comments, page_updated_info, page_version FROM $t_knowledge_pages_index";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_page_id, $get_page_space_id, $get_page_title, $get_page_title_clean, $get_page_description, $get_page_text, $get_page_language, $get_page_parent_id, $get_page_no_of_children, $get_page_weight, $get_page_allow_comments, $get_page_no_of_comments, $get_page_unique_hits, $get_page_unique_hits_ip_block, $get_page_unique_hits_user_id_block, $get_page_created_datetime, $get_page_created_date_saying, $get_page_created_user_id, $get_page_created_user_alias, $get_page_created_user_email, $get_page_created_user_image, $get_page_created_subscribe_to_comments, $get_page_updated_datetime, $get_page_updated_date_saying, $get_page_updated_user_id, $get_page_updated_user_alias, $get_page_updated_user_email, $get_page_updated_user_image, $get_page_updated_subscribe_to_comments, $get_page_updated_info, $get_page_version) = $row_w;

	

		$inp_index_title = "$get_page_title";
		$inp_index_title_mysql = quote_smart($link, $inp_index_title);

		$inp_index_url = "knowledge/view_page.php?space_id=$get_page_space_id&page_id=$get_page_id";
		$inp_index_url_mysql = quote_smart($link, $inp_index_url);

		$inp_index_short_description = substr($get_page_description, 0, 200);
		$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

		// tags
		$inp_index_keywords = "";
		$query_tags = "SELECT tag_id, tag_page_id, tag_title, tag_title_clean FROM $t_knowledge_pages_tags WHERE tag_page_id=$get_page_id";
		$result_tags = mysqli_query($link, $query_tags);
		while($row_tags = mysqli_fetch_row($result_tags)) {
			list($get_tag_id, $get_tag_page_id, $get_tag_title, $get_tag_title_clean) = $row_tags;

			if($inp_index_keywords == ""){
				$inp_index_keywords = "$get_tag_title";
			}
			else{
				$inp_index_keywords = $inp_index_keywords . ", $get_tag_title";
			}
		}
		
		$inp_index_keywords_mysql = quote_smart($link, "$inp_index_keywords");

		$inp_index_module_name_mysql = quote_smart($link, "knowledge");

		$inp_index_module_part_name_mysql = quote_smart($link, "spaces");

		if($get_page_space_id == ""){ $get_page_space_id = "0"; } 
		$inp_index_module_part_id_mysql = quote_smart($link, "$get_page_space_id");

		$inp_index_reference_name_mysql = quote_smart($link, "page_id");

		$inp_index_reference_id_mysql = quote_smart($link, "$get_page_id");

		$inp_index_has_access_control_mysql = quote_smart($link, 1);

		$inp_index_is_ad_mysql = quote_smart($link, 0);
	
		$inp_index_language_mysql = quote_smart($link, $get_page_language);

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
			$inp_index_module_name_mysql, $inp_index_module_part_name_mysql, $inp_index_module_part_id_mysql, $inp_index_reference_name_mysql, $inp_index_reference_id_mysql,
			'0', $inp_index_is_ad_mysql, '$datetime', '$datetime_saying', $inp_index_language_mysql,
			0)")
			or die(mysqli_error($link));
		}

		
	} // all pages 

	// Spaces access
	$query_w = "SELECT member_id, member_space_id, member_rank, member_user_id, member_user_alias, member_user_email, member_user_image, member_user_position, member_user_department, member_user_location, member_user_about, member_added_datetime, member_added_date_saying, member_added_by_user_id, member_added_by_user_alias, member_added_by_user_image FROM $t_knowledge_spaces_members";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_member_id, $get_member_space_id, $get_member_rank, $get_member_user_id, $get_member_user_alias, $get_member_user_email, $get_member_user_image, $get_member_user_position, $get_member_user_department, $get_member_user_location, $get_member_user_about, $get_member_added_datetime, $get_member_added_date_saying, $get_member_added_by_user_id, $get_member_added_by_user_alias, $get_member_added_by_user_image) = $row_w;

		$inp_control_user_id_mysql 				= quote_smart($link, $get_member_id);
		$inp_control_user_name_mysql 				= quote_smart($link, $get_member_user_alias);
		$inp_control_has_access_to_module_name_mysql 		= quote_smart($link, "knowledge");
		$inp_control_has_access_to_module_part_name_mysql 	= quote_smart($link, "spaces");
		$inp_control_has_access_to_module_part_id_mysql		= quote_smart($link, "$get_page_space_id");



		// Check if exists
		$query_exists = "SELECT control_id FROM $t_search_engine_access_control WHERE control_user_id=$inp_control_user_id_mysql AND control_has_access_to_module_name=$inp_control_has_access_to_module_name_mysql AND control_has_access_to_module_part_name=$inp_control_has_access_to_module_part_name_mysql AND control_has_access_to_module_part_id=$inp_control_has_access_to_module_part_id_mysql";
		$result_exists = mysqli_query($link, $query_exists);
		$row_exists = mysqli_fetch_row($result_exists);
		list($get_index_id) = $row_exists;
		if($get_index_id == ""){
			// Insert
			echo"<span>Insert access $get_member_user_alias for space $get_page_space_id<br /></span>\n";

			mysqli_query($link, "INSERT INTO $t_search_engine_access_control 
			(control_id, control_user_id, control_user_name, control_has_access_to_module_name, control_has_access_to_module_part_name, 
			control_has_access_to_module_part_id, control_created_datetime, control_created_datetime_print) 
			VALUES 
			(NULL, $inp_control_user_id_mysql, $inp_control_user_name_mysql, $inp_control_has_access_to_module_name_mysql, $inp_control_has_access_to_module_part_name_mysql, 
			$inp_control_has_access_to_module_part_id_mysql, '$datetime', '$datetime_saying')")
			or die(mysqli_error($link));
		}
	} // access



} // table exists
?>