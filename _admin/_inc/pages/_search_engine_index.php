<?php
/**
*
* File: _admin/_inc/pages/_search_engine_index.php
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
$t_pages	= $mysqlPrefixSav . "pages";


/*- Variables ---------------------------------------------------------------------------- */
$datetime = date("Y-m-d H:i:s");
$datetime_saying = date("j. M Y H:i");

$query_exists = "SELECT * FROM $t_pages";
$result_exists = mysqli_query($link, $query);
if($result_exists !== FALSE){

	
	/* pages index */
	$query_w = "SELECT page_id, page_title, page_language, page_path, page_file_name, page_slug, page_parent_id, page_content, page_no_of_children, page_child_level, page_no_of_columns, page_created, page_created_by_user_id, page_updated, page_updated_by_user_id, page_allow_comments, page_no_of_comments, page_uniqe_hits, page_uniqe_hits_ip_block, page_show_on_control_panel FROM $t_pages";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_page_id, $get_page_title, $get_page_language, $get_page_path, $get_page_file_name, $get_page_slug, $get_page_parent_id, $get_page_content, $get_page_no_of_children, $get_page_child_level, $get_page_no_of_columns, $get_page_created, $get_page_created_by_user_id, $get_page_updated, $get_page_updated_by_user_id, $get_page_allow_comments, $get_page_no_of_comments, $get_page_uniqe_hits, $get_page_uniqe_hits_ip_block, $get_page_show_on_control_panel) = $row_w;


		$inp_index_title = "$get_page_title"; 
		$inp_index_title_mysql = quote_smart($link, $inp_index_title);

		$inp_index_url = "$get_page_path/$get_page_file_name";
		$inp_index_url_mysql = quote_smart($link, $inp_index_url);

		$inp_index_short_description = substr($get_page_content, 0, 200);
		$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

		// tags
		$inp_index_keywords = "";
		$inp_index_keywords_mysql = quote_smart($link, "");

		$inp_index_module_name_mysql = quote_smart($link, "pages");

		$inp_index_module_part_name_mysql = quote_smart($link, "");

		$inp_index_reference_name_mysql = quote_smart($link, "page_id");
		$inp_index_reference_id_mysql = quote_smart($link, "$get_page_id");

		$inp_index_has_access_control_mysql = quote_smart($link, 0);

		$inp_index_is_ad_mysql = quote_smart($link, 0);
	
		$inp_index_language_mysql = quote_smart($link, "$get_page_language");

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

		
	} // pages


} // table exists
?>