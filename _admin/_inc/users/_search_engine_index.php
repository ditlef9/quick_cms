<?php
/**
*
* File: _admin/_inc/users/_search_engine_index.php
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

/*- Translation ------------------------------------------------------------------------------ */
include("_translations/site/$l/users/ts_users.php");

/*- Tables ---------------------------------------------------------------------------- */
$t_users		= $mysqlPrefixSav . "users";
$t_users_professional 	= $mysqlPrefixSav . "users_professional";

/*- Variables ---------------------------------------------------------------------------- */
$datetime = date("Y-m-d H:i:s");
$datetime_saying = date("j. M Y H:i");

$query_exists = "SELECT * FROM $t_users";
$result_exists = mysqli_query($link, $query);
if($result_exists !== FALSE){

	// Config
	include("_data/config/user_system.php");

	if($configShowUsersOnSearchEngineIndexSav == "1"){

		/* users */
		$query_w = "SELECT user_id, user_email, user_name, user_alias FROM $t_users";
		$result_w = mysqli_query($link, $query_w);
		while($row_w = mysqli_fetch_row($result_w)) {
			list($get_user_id, $get_user_email, $get_user_name, $get_user_alias) = $row_w;
			
			// Fetch profile
			$query_exists = "SELECT profile_id, profile_user_id, profile_first_name, profile_middle_name, profile_last_name, profile_address_line_a, profile_address_line_b, profile_zip, profile_city, profile_country, profile_phone, profile_work, profile_university, profile_high_school, profile_languages, profile_website, profile_interested_in, profile_relationship, profile_about, profile_newsletter, profile_views, profile_views_ip_block, profile_privacy FROM $t_users_profile WHERE profile_user_id=$get_user_id";
			$result_exists = mysqli_query($link, $query_exists);
			$row_exists = mysqli_fetch_row($result_exists);
			list($get_profile_id, $get_profile_user_id, $get_profile_first_name, $get_profile_middle_name, $get_profile_last_name, $get_profile_address_line_a, $get_profile_address_line_b, $get_profile_zip, $get_profile_city, $get_profile_country, $get_profile_phone, $get_profile_work, $get_profile_university, $get_profile_high_school, $get_profile_languages, $get_profile_website, $get_profile_interested_in, $get_profile_relationship, $get_profile_about, $get_profile_newsletter, $get_profile_views, $get_profile_views_ip_block, $get_profile_privacy) = $row_exists;

			// Fetch professional
			$query_exists = "SELECT professional_id, professional_user_id, professional_company, professional_company_location, professional_department, professional_work_email, professional_position, professional_position_abbr, professional_district FROM $t_users_professional WHERE professional_user_id=$get_user_id";
			$result_exists = mysqli_query($link, $query_exists);
			$row_exists = mysqli_fetch_row($result_exists);
			list($get_professional_id, $get_professional_user_id, $get_professional_company, $get_professional_company_location, $get_professional_department, $get_professional_work_email, $get_professional_position, $get_professional_position_abbr, $get_professional_district) = $row_exists;


			$inp_index_title = "$get_user_name";
			if($configIncludeFirstNameLastNameOnSearchEngineIndexSav == "1"){
				if($get_profile_first_name != "" OR  $get_profile_middle_name != "" OR $get_profile_last_name != ""){
					$inp_index_title = $inp_index_title . " | $get_profile_first_name $get_profile_middle_name $get_profile_last_name";
				}
			}
			if($configIncludeProfessionalOnSearchEngineIndexSav == "1"){
				if($get_professional_company != ""){
					$inp_index_title = $inp_index_title . " | $get_professional_company";
				}
				if($get_professional_company_location != ""){
					$inp_index_title = $inp_index_title . " | $get_professional_company_location";
				}
				if($get_professional_department != ""){
					$inp_index_title = $inp_index_title . " | $get_professional_department";
				}
				if($get_professional_position_abbr != ""){
					$inp_index_title = $inp_index_title . " | $get_professional_position_abbr";
				}
				if($get_professional_district != ""){
					$inp_index_title = $inp_index_title . " | $get_professional_district";
				}
			}
			$inp_index_title = $inp_index_title . " | $l_users";
			$inp_index_title_mysql = quote_smart($link, $inp_index_title);

			$inp_index_url = "users/view_profile.php?user_id=$get_user_id";
			$inp_index_url_mysql = quote_smart($link, $inp_index_url);

			$inp_index_short_description = "";
			$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

			// tags
			$inp_index_keywords = "";
			$inp_index_keywords_mysql = quote_smart($link, "$inp_index_keywords");

			$inp_index_module_name_mysql = quote_smart($link, "users");

			$inp_index_module_part_name_mysql = quote_smart($link, "");

			$inp_index_reference_name_mysql = quote_smart($link, "user_id");
			$inp_index_reference_id_mysql = quote_smart($link, "$get_user_id");

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

		} // users

	} // $configShowUsersOnSearchEngineIndexSav == 1

} // table exists
?>