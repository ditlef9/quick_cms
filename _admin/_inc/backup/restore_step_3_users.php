<?php
/**
*
* File: _admin/_inc/backup/restore_step_3_users.php
* Version 20:18 12.01.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ------------------------------------------------------------------------ */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables Backup ----------------------------------------------------------------------- */
$t_backup_liquidbase	 = $mysqlPrefixSav . "backup_liquidbase";
$t_backup_index 	 = $mysqlPrefixSav . "backup_index";
$t_backup_modules	 = $mysqlPrefixSav . "backup_modules";
$t_backup_directories	 = $mysqlPrefixSav . "backup_directories";
$t_backup_files		 = $mysqlPrefixSav . "backup_files";



/*- Variables -------------------------------------------------------------------------- */


/*- Script start ------------------------------------------------------------------------ */

if($action == ""){
	echo"
	<h1><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Restore users</h1>
	
	
	<!-- List all temp users -->
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>ID</span>
		   </th>
		   <th scope=\"col\">
			<span>Username</span>
		   </th>
		   <th scope=\"col\">
			<span>Exists</span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>";


		$t_users_tmp = $mysqlPrefixSav . "users_tmp";
		$query = "SELECT user_id, user_email, user_name, user_alias, user_password, user_password_replacement, user_password_date, user_salt, user_security, user_rank, user_verified_by_moderator, user_first_name, user_middle_name, user_last_name, user_language, user_country_id, user_country_name, user_city_name, user_timezone_utc_diff, user_timezone_value, user_measurement, user_date_format, user_gender, user_height, user_dob, user_registered, user_registered_time, user_registered_date_saying, user_newsletter, user_privacy, user_views, user_views_ipblock, user_points, user_points_rank, user_likes, user_dislikes, user_status, user_login_tries, user_last_online, user_last_online_time, user_last_ip, user_synchronized, user_notes, user_marked_as_spammer FROM $t_users_tmp";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_tmp_user_id, $get_tmp_user_email, $get_tmp_user_name, $get_tmp_user_alias, $get_tmp_user_password, $get_tmp_user_password_replacement, $get_tmp_user_password_date, $get_tmp_user_salt, $get_tmp_user_security, $get_tmp_user_rank, $get_tmp_user_verified_by_moderator, $get_tmp_user_first_name, $get_tmp_user_middle_name, $get_tmp_user_last_name, $get_tmp_user_language, $get_tmp_user_country_id, $get_tmp_user_country_name, $get_tmp_user_city_name, $get_tmp_user_timezone_utc_diff, $get_tmp_user_timezone_value, $get_tmp_user_measurement, $get_tmp_user_date_format, $get_tmp_user_gender, $get_tmp_user_height, $get_tmp_user_dob, $get_tmp_user_registered, $get_tmp_user_registered_time, $get_tmp_user_registered_date_saying, $get_tmp_user_newsletter, $get_tmp_user_privacy, $get_tmp_user_views, $get_tmp_user_views_ipblock, $get_tmp_user_points, $get_tmp_user_points_rank, $get_tmp_user_likes, $get_tmp_user_dislikes, $get_tmp_user_status, $get_tmp_user_login_tries, $get_tmp_user_last_online, $get_tmp_user_last_online_time, $get_tmp_user_last_ip, $get_tmp_user_synchronized, $get_tmp_user_notes, $get_tmp_user_marked_as_spammer) = $row;

			$inp_user_email_mysql = quote_smart($link, $get_tmp_user_email);
			$inp_user_name_mysql = quote_smart($link, $get_tmp_user_name);
			$inp_user_alias_mysql = quote_smart($link, $get_tmp_user_alias);
			$inp_user_password_mysql = quote_smart($link, $get_tmp_user_password);
			$inp_user_password_replacement_mysql = quote_smart($link, $get_tmp_user_password_replacement);
			$inp_user_password_date_mysql = quote_smart($link, $get_tmp_user_password_date);
			$inp_user_salt_mysql = quote_smart($link, $get_tmp_user_salt);
			$inp_user_security_mysql = quote_smart($link, $get_tmp_user_security); 
			$inp_user_rank_mysql = quote_smart($link, $get_tmp_user_rank);
			$inp_user_verified_by_moderator_mysql = quote_smart($link, $get_tmp_user_verified_by_moderator);
			$inp_user_first_name_mysql = quote_smart($link, $get_tmp_user_first_name);
			$inp_user_middle_name_mysql = quote_smart($link, $get_tmp_user_middle_name);
			$inp_user_last_name_mysql = quote_smart($link, $get_tmp_user_last_name);
			$inp_user_language_mysql = quote_smart($link, $get_tmp_user_language);
			$inp_user_country_id_mysql = quote_smart($link, $get_tmp_user_country_id);
			$inp_user_country_name_mysql = quote_smart($link, $get_tmp_user_country_name);
			$inp_user_city_name_mysql = quote_smart($link, $get_tmp_user_city_name); 
			$inp_user_timezone_utc_diff_mysql = quote_smart($link, $get_tmp_user_timezone_utc_diff);
			$inp_user_timezone_value_mysql = quote_smart($link, $get_tmp_user_timezone_value);
			$inp_user_measurement_mysql = quote_smart($link, $get_tmp_user_measurement);
			$inp_user_date_format_mysql = quote_smart($link, $get_tmp_user_date_format);
			$inp_user_gender_mysql = quote_smart($link, $get_tmp_user_gender); 
			$inp_user_height_mysql = quote_smart($link, $get_tmp_user_height);
			$inp_user_dob_mysql = quote_smart($link, $get_tmp_user_dob);
			$inp_user_registered_mysql = quote_smart($link, $get_tmp_user_registered);
			$inp_user_registered_time_mysql = quote_smart($link, $get_tmp_user_registered_time);
			$inp_user_registered_date_saying_mysql = quote_smart($link, $get_tmp_user_registered_date_saying);
			$inp_user_newsletter_mysql = quote_smart($link, $get_tmp_user_newsletter);
			$inp_user_privacy_mysql = quote_smart($link, $get_tmp_user_privacy);
			$inp_user_views_mysql = quote_smart($link, $get_tmp_user_views);
			$inp_user_views_ipblock_mysql = quote_smart($link, "");
			$inp_user_points_mysql = quote_smart($link, $get_tmp_user_points);
			$inp_user_points_rank_mysql = quote_smart($link, $get_tmp_user_points_rank);
			$inp_user_likes_mysql = quote_smart($link, $get_tmp_user_likes);
			$inp_user_dislikes_mysql = quote_smart($link, $get_tmp_user_dislikes); 
			$inp_user_status_mysql = quote_smart($link, $get_tmp_user_status); 
			$inp_user_login_tries_mysql = quote_smart($link, "");
			$inp_user_last_online_mysql = quote_smart($link, $get_tmp_user_last_online);
			$inp_user_last_online_time_mysql = quote_smart($link, $get_tmp_user_last_online_time);
			$inp_user_last_ip_mysql = quote_smart($link, "");
			$inp_user_synchronized_mysql = quote_smart($link, $get_tmp_user_synchronized);
			$inp_user_notes_mysql = quote_smart($link, "");
			$inp_user_marked_as_spammer = quote_smart($link, $get_tmp_user_marked_as_spammer);
			
		
			// Check if this user exists, if not then insert
			$q = "SELECT user_id FROM $t_users WHERE user_name=$inp_user_name_mysql";
			$r = mysqli_query($link, $q);
			$rowb = mysqli_fetch_row($r);
			list($get_user_id) = $rowb;
			if($get_user_id == "" && $get_tmp_user_id != "1"){
				mysqli_query($link, "INSERT INTO $t_users
				(user_id, user_email, user_name, user_alias, user_password, user_password_replacement, user_password_date, user_salt, user_security, user_rank, user_verified_by_moderator, user_first_name, user_middle_name, user_last_name, user_language, user_country_id, user_country_name, user_city_name, user_timezone_utc_diff, user_timezone_value, user_measurement, user_date_format, user_gender, user_height, user_dob, user_registered, user_registered_time, user_registered_date_saying, user_newsletter, user_privacy, user_views, user_views_ipblock, user_points, user_points_rank, user_likes, user_dislikes, user_status, user_login_tries, user_last_online, user_last_online_time, user_last_ip, user_synchronized, user_notes, user_marked_as_spammer) 
				VALUES 
				($get_tmp_user_id, $inp_user_email_mysql, $inp_user_name_mysql, $inp_user_alias_mysql, $inp_user_password_mysql, $inp_user_password_replacement_mysql, $inp_user_password_date_mysql, $inp_user_salt_mysql, $inp_user_security_mysql, $inp_user_rank_mysql, $inp_user_verified_by_moderator_mysql, $inp_user_first_name_mysql, $inp_user_middle_name_mysql, $inp_user_last_name_mysql, $inp_user_language_mysql, $inp_user_country_id_mysql, $inp_user_country_name_mysql, $inp_user_city_name_mysql, $inp_user_timezone_utc_diff_mysql, $inp_user_timezone_value_mysql, $inp_user_measurement_mysql, $inp_user_date_format_mysql, $inp_user_gender_mysql, $inp_user_height_mysql, $inp_user_dob_mysql, $inp_user_registered_mysql, $inp_user_registered_time_mysql, $inp_user_registered_date_saying_mysql, $inp_user_newsletter_mysql, $inp_user_privacy_mysql, $inp_user_views_mysql, $inp_user_views_ipblock_mysql, $inp_user_points_mysql, $inp_user_points_rank_mysql, $inp_user_likes_mysql, $inp_user_dislikes_mysql, $inp_user_status_mysql, $inp_user_login_tries_mysql, $inp_user_last_online_mysql, $inp_user_last_online_time_mysql, $inp_user_last_ip_mysql, $inp_user_synchronized_mysql, $inp_user_notes_mysql, $inp_user_marked_as_spammer)")
				or print(mysqli_error($link));

			}

			// Delete from temp
			// mysqli_query($link, "DELETE FROM $t_users_tmp WHERE user_id=$get_tmp_user_id") or die(mysqli_error($link));
		}
		echo"

		 </tbody>
		</table>
		  
	<!-- //List all temp users -->

	";
} // action == ""
					
?>