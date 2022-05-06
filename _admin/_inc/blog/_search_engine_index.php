<?php
/**
*
* File: _admin/_inc/blog/_search_engine_index.php
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
$t_blog_info 		= $mysqlPrefixSav . "blog_info";
$t_blog_categories	= $mysqlPrefixSav . "blog_categories";
$t_blog_posts 		= $mysqlPrefixSav . "blog_posts";
$t_blog_posts_tags 	= $mysqlPrefixSav . "blog_posts_tags";
$t_blog_images 		= $mysqlPrefixSav . "blog_images";
$t_blog_logos		= $mysqlPrefixSav . "blog_logos";

$t_blog_links_index		= $mysqlPrefixSav . "blog_links_index";
$t_blog_links_categories	= $mysqlPrefixSav . "blog_links_categories";

$t_blog_ping_list_per_blog	= $mysqlPrefixSav . "blog_ping_list_per_blog";

/*- Functions ---------------------------------------------------------------------------- */
include("_functions/get_extension.php");


/*- Variables ---------------------------------------------------------------------------- */
$datetime = date("Y-m-d H:i:s");
$datetime_saying = date("j. M Y H:i");

$query_exists = "SELECT * FROM $t_blog_posts";
$result_exists = mysqli_query($link, $query);
if($result_exists !== FALSE){

	/* Find all entries */
	$query_w = "SELECT blog_post_id, blog_post_user_id, blog_post_title, blog_post_language, blog_post_category_id, blog_post_introduction, blog_post_privacy_level, blog_post_image_path, blog_post_image_thumb_small, blog_post_image_thumb_medium, blog_post_image_thumb_large, blog_post_image_file, blog_post_ad, blog_post_updated, blog_post_comments FROM $t_blog_posts WHERE blog_post_privacy_level='everyone' ORDER BY blog_post_id DESC";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_blog_post_id, $get_blog_post_user_id, $get_blog_post_title, $get_blog_post_language, $get_blog_post_category_id, $get_blog_post_introduction, $get_blog_post_privacy_level, $get_blog_post_image_path, $get_blog_post_image_thumb_small, $get_blog_post_image_thumb_medium, $get_blog_post_image_thumb_large, $get_blog_post_image_file, $get_blog_post_ad, $get_blog_post_updated, $get_blog_post_comments) = $row_w;


		// Find blog info
		$l_mysql = quote_smart($link, $get_blog_post_language);
		$query = "SELECT blog_info_id, blog_user_id, blog_language, blog_title, blog_description, blog_created, blog_updated, blog_posts, blog_comments, blog_views, blog_views_ipblock FROM $t_blog_info WHERE blog_user_id=$get_blog_post_user_id AND blog_language=$l_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_blog_info_id, $get_current_blog_user_id, $get_current_blog_language, $get_current_blog_title, $get_current_blog_description, $get_current_blog_created, $get_current_blog_updated, $get_current_blog_posts, $get_current_blog_comments, $get_current_blog_views, $get_current_blog_views_ipblock) = $row;
	
	
		$inp_index_title = "$get_blog_post_title | $get_current_blog_title";
		$inp_index_title_mysql = quote_smart($link, $inp_index_title);

		$inp_index_url = "blog/view_post.php?post_id=$get_blog_post_id";
		$inp_index_url_mysql = quote_smart($link, $inp_index_url);

		$inp_index_short_description_mysql = quote_smart($link, $get_blog_post_introduction);

		$inp_index_keywords_mysql = quote_smart($link, "");

		$inp_index_module_name_mysql = quote_smart($link, "blog");
	
		$inp_index_module_part_name_mysql = quote_smart($link, "post");

		$inp_index_reference_name_mysql = quote_smart($link, "blog_post_id");

		$inp_index_reference_id_mysql = quote_smart($link, "$get_blog_post_id");

		$inp_index_is_ad_mysql = quote_smart($link, $get_blog_post_ad);
	
		$inp_index_language_mysql = quote_smart($link, $get_blog_post_language);


		// Image
		$inp_index_image_path_mysql = quote_smart($link, $get_blog_post_image_path);
		$inp_index_image_file_mysql = quote_smart($link, $get_blog_post_image_file);

		// Thumb
		$thumb = "";
		if($get_blog_post_image_file != ""){
			$ext = get_extension($get_blog_post_image_file);
			$thumb = str_replace(".$ext", "", $get_blog_post_image_file);
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
	} // all blog posts

	/* Find all blogs */
	$query_w = "SELECT blog_info_id, blog_user_id, blog_language, blog_title, blog_description, blog_created, blog_updated, blog_posts, blog_comments, blog_views, blog_views_ipblock FROM $t_blog_info";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_blog_info_id, $get_blog_user_id, $get_blog_language, $get_blog_title, $get_blog_description, $get_blog_created, $get_blog_updated, $get_blog_posts, $get_blog_comments, $get_blog_views, $get_blog_views_ipblock) = $row_w;

	
		$inp_index_title = "$get_blog_title";
		$inp_index_title_mysql = quote_smart($link, $inp_index_title);

		$inp_index_url = "blog/view_blog.php?info_id=$get_blog_info_id";
		$inp_index_url_mysql = quote_smart($link, $inp_index_url);

		$inp_index_short_description = substr($get_blog_description, 0, 200);
		$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

		$inp_index_keywords_mysql = quote_smart($link, "");

		$inp_index_module_name_mysql = quote_smart($link, "blog");
	
		$inp_index_module_part_name_mysql = quote_smart($link, "info");

		$inp_index_reference_name_mysql = quote_smart($link, "blog_info_id");

		$inp_index_reference_id_mysql = quote_smart($link, "$get_blog_info_id");

		$inp_index_is_ad_mysql = quote_smart($link, 0);
	
		$inp_index_language_mysql = quote_smart($link, $get_blog_language);

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
	} // all blogs



}
?>