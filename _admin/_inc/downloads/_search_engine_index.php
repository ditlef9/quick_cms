<?php
/**
*
* File: _admin/_inc/downloads/_search_engine_index.php
* Version 15.00 03.03.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables ---------------------------------------------------------------------------- */
$t_downloads_index 				= $mysqlPrefixSav . "downloads_index";

/*- Variables ---------------------------------------------------------------------------- */
$datetime = date("Y-m-d H:i:s");
$datetime_saying = date("j M Y H:i");


$query_exists = "SELECT * FROM $t_downloads_index";
$result_exists = mysqli_query($link, $query);
if($result_exists !== FALSE){


	/* Find all downloads */
	$query_w = "SELECT download_id, download_title, download_title_short, download_title_length, download_language, download_introduction, download_description, download_video, download_image_path, download_image_store, download_image_store_thumb, download_image_thumb_a, download_image_thumb_b, download_image_thumb_c, download_image_thumb_d, download_image_file_a, download_image_file_b, download_image_file_c, download_image_file_d, download_read_more_url, download_main_category_id, download_sub_category_id, download_dir, download_file, download_type, download_version, download_file_size, download_file_date, download_file_date_print, download_last_download, download_hits, download_unique_hits, download_ip_block, download_tag_a, download_tag_b, download_tag_c, download_created_datetime, download_updated_datetime, download_updated_print, download_have_to_be_logged_in_to_download FROM $t_downloads_index";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_download_id, $get_download_title, $get_download_title_short, $get_download_title_length, $get_download_language, $get_download_introduction, $get_download_description, $get_download_video, $get_download_image_path, $get_download_image_store, $get_download_image_store_thumb, $get_download_image_thumb_a, $get_download_image_thumb_b, $get_download_image_thumb_c, $get_download_image_thumb_d, $get_download_image_file_a, $get_download_image_file_b, $get_download_image_file_c, $get_download_image_file_d, $get_download_read_more_url, $get_download_main_category_id, $get_download_sub_category_id, $get_download_dir, $get_download_file, $get_download_type, $get_download_version, $get_download_file_size, $get_download_file_date, $get_download_file_date_print, $get_download_last_download, $get_download_hits, $get_download_unique_hits, $get_download_ip_block, $get_download_tag_a, $get_download_tag_b, $get_download_tag_c, $get_download_created_datetime, $get_download_updated_datetime, $get_download_updated_print, $get_download_have_to_be_logged_in_to_download) = $row_w;

		$inp_index_title = "$get_download_title";
		$inp_index_title_mysql = quote_smart($link, $inp_index_title);
	
		$inp_index_url = "downloads/view_download.php?download_id=$get_download_id";
		$inp_index_url_mysql = quote_smart($link, $inp_index_url);

		$inp_index_short_description = substr($get_download_introduction, 0, 200);
		$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

		$inp_index_keywords = "$get_download_tag_a";
		if($get_download_tag_b != ""){
			$inp_index_keywords = $inp_index_keywords . ", $get_download_tag_b";
		}
		if($get_download_tag_c != ""){
			$inp_index_keywords = $inp_index_keywords . ", $get_download_tag_c";
		}
		$inp_index_keywords_mysql = quote_smart($link, "$inp_index_keywords");

		$inp_index_module_name_mysql = quote_smart($link, "downloads");

		$inp_index_module_part_name_mysql = quote_smart($link, "downloads");

		$inp_index_reference_name_mysql = quote_smart($link, "download_id");

		$inp_index_reference_id_mysql = quote_smart($link, "$get_download_id");

		$inp_index_is_ad_mysql = quote_smart($link, 0);
		
		$inp_index_language_mysql = quote_smart($link, $get_download_language);

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
	
	} // all downloads


}
?>