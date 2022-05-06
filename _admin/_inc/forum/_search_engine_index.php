<?php
/**
*
* File: _admin/_inc/discuss/_search_engine_index.php
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
$t_discuss_topics	= $mysqlPrefixSav . "discuss_topics";

/*- Variables ---------------------------------------------------------------------------- */
$datetime = date("Y-m-d H:i:s");
$datetime_saying = date("j. M Y H:i");

$query_exists = "SELECT * FROM $t_discuss_topics";
$result_exists = mysqli_query($link, $query);
if($result_exists !== FALSE){

	

	/* Find all entries */
	$query_w = "SELECT topic_id, topic_user_id, topic_user_alias, topic_user_image, topic_language, topic_title, topic_text FROM $t_discuss_topics ORDER BY topic_last_replied DESC";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_topic_id, $get_topic_user_id, $get_topic_user_alias, $get_topic_user_image, $get_topic_language, $get_topic_title, $get_topic_text) = $row_w;

		$inp_index_title = "$get_topic_title";
		$inp_index_title_mysql = quote_smart($link, $inp_index_title);
	
		$inp_index_url = "discuss/view_topic.php?topic_id=$get_topic_id";
		$inp_index_url_mysql = quote_smart($link, $inp_index_url);

		$inp_index_short_description = substr($get_topic_text, 0, 200);
		$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

		$inp_index_keywords_mysql = quote_smart($link, "");

		$inp_index_module_name_mysql = quote_smart($link, "discuss");

		$inp_index_module_part_name_mysql = quote_smart($link, "");

		$inp_index_reference_name_mysql = quote_smart($link, "topic_id");

		$inp_index_reference_id_mysql = quote_smart($link, "$get_topic_id");

		$inp_index_is_ad_mysql = quote_smart($link, 0);
	
		$inp_index_language_mysql = quote_smart($link, $get_topic_language);

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

	
	} // all discuess topics

}
?>