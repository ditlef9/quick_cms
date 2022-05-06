<?php
/**
*
* File: _admin/_inc/talk/_search_engine_index.php
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
$t_talk_liquidbase				= $mysqlPrefixSav . "talk_liquidbase";

$t_talk_channels_index		= $mysqlPrefixSav . "talk_channels_index";
$t_talk_channels_messages	= $mysqlPrefixSav . "talk_channels_messages";
$t_talk_channels_users_online	= $mysqlPrefixSav . "talk_channels_users_online";
$t_talk_users_starred_channels	= $mysqlPrefixSav . "talk_users_starred_channels";

$t_talk_dm_conversations = $mysqlPrefixSav . "talk_dm_conversations";
$t_talk_dm_messages	 = $mysqlPrefixSav . "talk_dm_messages";

$t_talk_total_unread = $mysqlPrefixSav . "talk_total_unread";

$t_talk_nicknames 		= $mysqlPrefixSav . "talk_nicknames";
$t_talk_nicknames_changes 	= $mysqlPrefixSav . "talk_nicknames_changes";


/*- Variables ---------------------------------------------------------------------------- */
$datetime = date("Y-m-d H:i:s");
$datetime_saying = date("j. M Y H:i");

$query_exists = "SELECT * FROM $t_talk_channels_index";
$result_exists = mysqli_query($link, $query);
if($result_exists !== FALSE){

	
	if(file_exists("_data/talk.php")){
		include("_data/talk.php");
	}

	/* channels */
	$query_w = "SELECT channel_id, channel_name, channel_password, channel_created_by_user_id, channel_created_by_user_ip, channel_created_datetime, channel_created_saying, channel_last_message_time, channel_last_message_saying, channel_users_online, channel_encryption_key, channel_encryption_key_year, channel_encryption_key_month FROM $t_talk_channels_index";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_channel_id, $get_channel_name, $get_channel_password, $get_channel_created_by_user_id, $get_channel_created_by_user_ip, $get_channel_created_datetime, $get_channel_created_saying, $get_channel_last_message_time, $get_channel_last_message_saying, $get_channel_users_online, $get_channel_encryption_key, $get_channel_encryption_key_year, $get_channel_encryption_key_month) = $row_w;


		$inp_index_title = "$get_channel_name | $talkTitleSav";
		$inp_index_title_mysql = quote_smart($link, $inp_index_title);

		$inp_index_url = "talk/channel_list.php?action=join_without_password&channel_id=$get_channel_id&process=1";
		$inp_index_url_mysql = quote_smart($link, $inp_index_url);

		$inp_index_short_description = "";
		$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

		// tags
		$inp_index_keywords = "";
		$inp_index_keywords_mysql = quote_smart($link, "$inp_index_keywords");

		$inp_index_module_name_mysql = quote_smart($link, "talk");

		$inp_index_module_part_name_mysql = quote_smart($link, "");

		$inp_index_reference_name_mysql = quote_smart($link, "channel_id");
		$inp_index_reference_id_mysql = quote_smart($link, "$get_channel_id");

		$inp_index_has_access_control_mysql = quote_smart($link, 0);

		$inp_index_is_ad_mysql = quote_smart($link, 0);
	
		$inp_index_language_mysql = quote_smart($link, "");

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


	} // channels

	

} // table exists
?>