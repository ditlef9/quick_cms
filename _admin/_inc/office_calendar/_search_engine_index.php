<?php
/**
*
* File: _admin/_inc/office_calendar/_search_engine_index.php
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
$t_office_calendar_liquidbase	= $mysqlPrefixSav . "office_calendar_liquidbase";

$t_office_calendar_locations	= $mysqlPrefixSav . "office_calendar_locations";
$t_office_calendar_equipments	= $mysqlPrefixSav . "office_calendar_equipments";
$t_office_calendar_events	= $mysqlPrefixSav . "office_calendar_events";


/*- Variables ---------------------------------------------------------------------------- */
$datetime = date("Y-m-d H:i:s");
$datetime_saying = date("j. M Y H:i");

$query_exists = "SELECT * FROM $t_office_calendar_events";
$result_exists = mysqli_query($link, $query);
if($result_exists !== FALSE){

	


	/* muscles index */
	$query_w = "SELECT event_id, event_user_id, event_user_name, event_location_id, event_location_title, event_equipment_id, event_equipment_title, event_text, event_bg_color, event_text_color, event_from_datetime, event_from_time, event_from_day, event_from_month, event_from_year, event_from_hour, event_from_minute, event_from_saying_date_time, event_to_datetime, event_to_time, event_to_day, event_to_month, event_to_year, event_to_hour, event_to_minute, event_to_saying_date_time FROM $t_office_calendar_events";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_event_id, $get_event_user_id, $get_event_user_name, $get_event_location_id, $get_event_location_title, $get_event_equipment_id, $get_event_equipment_title, $get_event_text, $get_event_bg_color, $get_event_text_color, $get_event_from_datetime, $get_event_from_time, $get_event_from_day, $get_event_from_month, $get_event_from_year, $get_event_from_hour, $get_event_from_minute, $get_event_from_saying_date_time, $get_event_to_datetime, $get_event_to_time, $get_event_to_day, $get_event_to_month, $get_event_to_year, $get_event_to_hour, $get_event_to_minute, $get_event_to_saying_date_time) = $row_w;


		$inp_index_title = "$get_event_from_saying_date_time $inp_index_title"; 
		$inp_index_title = substr($inp_index_title, 0, 50);
		$inp_index_title_mysql = quote_smart($link, $inp_index_title);

		$inp_index_url = "office_calendar/index.php?year=$get_event_from_year&month=$get_event_from_month";
		$inp_index_url_mysql = quote_smart($link, $inp_index_url);

		$inp_index_short_description = substr($get_event_text, 0, 200);
		$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

		// tags
		$inp_index_keywords = "";
		$inp_index_keywords_mysql = quote_smart($link, "$get_event_location_title, $get_event_equipment_title");

		$inp_index_module_name_mysql = quote_smart($link, "office_calendar");

		$inp_index_module_part_name_mysql = quote_smart($link, "");

		$inp_index_reference_name_mysql = quote_smart($link, "event_id");

		$inp_index_reference_id_mysql = quote_smart($link, "$get_event_id");

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


		
	} // events


} // table exists
?>