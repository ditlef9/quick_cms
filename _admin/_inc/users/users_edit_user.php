<?php
/*- MySQL Tables -------------------------------------------------- */
$t_users 	 		= $mysqlPrefixSav . "users";
$t_users_profile 		= $mysqlPrefixSav . "users_profile";
$t_users_friends 		= $mysqlPrefixSav . "users_friends";
$t_users_friends_requests 	= $mysqlPrefixSav . "users_friends_requests";
$t_users_profile		= $mysqlPrefixSav . "users_profile";
$t_users_profile_photo 		= $mysqlPrefixSav . "users_profile_photo";
$t_users_status 		= $mysqlPrefixSav . "users_status";
$t_users_status_comments 	= $mysqlPrefixSav . "users_status_comments";
$t_users_status_comments_likes 	= $mysqlPrefixSav . "users_status_comments_likes";
$t_users_status_likes 		= $mysqlPrefixSav . "users_status_likes";

$t_users_professional 		= $mysqlPrefixSav . "users_professional";

$t_users_profile_headlines			= $mysqlPrefixSav . "users_profile_headlines";
$t_users_profile_headlines_translations		= $mysqlPrefixSav . "users_profile_headlines_translations";
$t_users_profile_fields				= $mysqlPrefixSav . "users_profile_fields";
$t_users_profile_fields_translations		= $mysqlPrefixSav . "users_profile_fields_translations";
$t_users_profile_fields_options			= $mysqlPrefixSav . "users_profile_fields_options";
$t_users_profile_fields_options_translations	= $mysqlPrefixSav . "users_profile_fields_options_translations";

/*- Tables search --------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";



/*- Access check -------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Timezone --------------------------------------------------------------------------- */
function timezone_list() {
    static $timezones = null;

    if ($timezones === null) {
        $timezones = [];
        $offsets = [];
        $now = new DateTime('now', new DateTimeZone('UTC'));

        foreach (DateTimeZone::listIdentifiers() as $timezone) {
            $now->setTimezone(new DateTimeZone($timezone));
            $offsets[] = $offset = $now->getOffset();
            $timezones[$timezone] = '(' . format_GMT_offset($offset) . ') ' . format_timezone_name($timezone);
        }

        array_multisort($offsets, $timezones);
    }

    return $timezones;
}
function format_GMT_offset($offset) {
    $hours = intval($offset / 3600);
    $minutes = abs(intval($offset % 3600 / 60));
    return 'GMT' . ($offset ? sprintf('%+03d:%02d', $hours, $minutes) : '');
}

function format_timezone_name($name) {
    $name = str_replace('/', ', ', $name);
    $name = str_replace('_', ' ', $name);
    $name = str_replace('St ', 'St. ', $name);
    return $name;
}




/*- Varialbes  ---------------------------------------------------- */
if(isset($_GET['user_id'])) {
	$user_id = $_GET['user_id'];
	$user_id = strip_tags(stripslashes($user_id));
}
else{
	$user_id = "";
	echo"
	<h1>Error</h1>
	<p>$l_user_profile_not_found</p>
	";
	die;
}
if(isset($_GET['mode'])) {
	$mode = $_GET['mode'];
	$mode = strip_tags(stripslashes($mode));
}
else{
	$mode = "";
}
if(isset($_GET['refer'])) {
	$refer = $_GET['refer'];
	$refer = strip_tags(stripslashes($refer));
}
else{
	$refer = "";
}
// Get user
$user_id_mysql = quote_smart($link, $user_id);
$query = "SELECT user_id, user_email, user_name, user_alias, user_password, user_password_replacement, user_password_date, user_salt, user_security, user_rank, user_verified_by_moderator, user_first_name, user_middle_name, user_last_name, user_language, user_country_id, user_country_name, user_city_name, user_timezone_utc_diff, user_timezone_value, user_measurement, user_date_format, user_gender, user_height, user_dob, user_registered, user_registered_time, user_newsletter, user_points, user_points_rank, user_likes, user_dislikes, user_status, user_login_tries, user_last_online, user_last_online_time, user_last_ip, user_synchronized, user_notes, user_marked_as_spammer FROM $t_users WHERE user_id=$user_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_user_id, $get_current_user_email, $get_current_user_name, $get_current_user_alias, $get_current_user_password, $get_current_user_password_replacement, $get_current_user_password_date, $get_current_user_salt, $get_current_user_security, $get_current_user_rank, $get_current_user_verified_by_moderator, $get_current_user_first_name, $get_current_user_middle_name, $get_current_user_last_name, $get_current_user_language, $get_current_user_country_id, $get_current_user_country_name, $get_current_user_city_name, $get_current_user_timezone_utc_diff, $get_current_user_timezone_value, $get_current_user_measurement, $get_current_user_date_format, $get_current_user_gender, $get_current_user_height, $get_current_user_dob, $get_current_user_registered, $get_current_user_registered_time, $get_current_user_newsletter, $get_current_user_points, $get_current_user_points_rank, $get_current_user_likes, $get_current_user_dislikes, $get_current_user_status, $get_current_user_login_tries, $get_current_user_last_online, $get_current_user_last_online_time, $get_current_user_last_ip, $get_current_user_synchronized, $get_current_user_notes, $get_current_user_marked_as_spammer) = $row;

	
if($get_current_user_id == ""){
	echo"<h1>Error</h1><p>Error with user id.</p>"; 
	die;
}



// Can I edit?
$my_user_id = $_SESSION['admin_user_id'];
$my_user_id = output_html($my_user_id);
$my_user_id_mysql = quote_smart($link, $my_user_id);
$my_security  = $_SESSION['admin_security'];
$my_security = output_html($my_security);
$my_security_mysql = quote_smart($link, $my_security);
$query = "SELECT user_id, user_name, user_language, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql AND user_security=$my_security_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_my_user_id, $get_my_user_name, $get_my_user_language, $get_my_user_rank) = $row;
if($get_my_user_rank != "moderator" && $get_my_user_rank != "admin"){
	echo"
	<h1>Server error 403</h1>
	<p>Your rank is $get_my_user_rank. You can not edit.</p>
	";
	die;
}
	if($mode == "save"){
		$ft = "";
		$fm = "";

		// User
		$inp_user_email = $_POST['inp_user_email'];
		$inp_user_email = output_html($inp_user_email);
		$inp_user_email = strtolower($inp_user_email);
		$inp_user_email_mysql = quote_smart($link, $inp_user_email);
		if($inp_user_email != "$get_current_user_email"){
			// Check if new email is taken
			
			$query = "SELECT user_id, user_email, user_name FROM $t_users WHERE user_email=$inp_user_email_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($check_user_id, $check_user_email, $check_user_name) = $row;
			if($check_user_id == ""){
				// Update email
				$result = mysqli_query($link, "UPDATE $t_users SET user_email=$inp_user_email_mysql WHERE user_id=$user_id_mysql") or die(mysqli_error($link));
				$fm = "email_address_updated";
				$ft = "success";
			}
			else{
				$fm = "email_alreaddy_in_use";
				$ft = "warning";
			}
		}
			
		$inp_user_name = $_POST['inp_user_name'];
		$inp_user_name = output_html($inp_user_name);
		$inp_user_name = strtolower($inp_user_name);
		$inp_user_name_mysql = quote_smart($link, $inp_user_name);
		if($inp_user_name != "$get_current_user_name"){
			// Check if new email is taken
			
			$query = "SELECT user_id, user_email, user_name FROM $t_users WHERE user_name=$inp_user_name_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($check_user_id, $check_user_email, $check_user_name) = $row;
			if($check_user_id == ""){
				// Update email
				$result = mysqli_query($link, "UPDATE $t_users SET user_name=$inp_user_name_mysql WHERE user_id=$user_id_mysql") or die(mysqli_error($link));
				$fm = "user_name_updated";
				$ft = "success";
			}
			else{
				if($check_user_id != "$user_id"){
					$fm = "user_name_already_in_use";
					$ft = "warning";
				}
			}
		
		}


		$inp_user_alias = $_POST['inp_user_alias'];
		$inp_user_alias = output_html($inp_user_alias);
		$inp_user_alias = strtolower($inp_user_alias);
		$inp_user_alias_mysql = quote_smart($link, $inp_user_alias);
		if($inp_user_alias != "$get_current_user_alias"){
			// Check if new email is taken
			
			$query = "SELECT user_id, user_email, user_alias FROM $t_users WHERE user_alias=$inp_user_alias_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($check_user_id, $check_user_email, $check_user_alias) = $row;
			if($check_user_id == ""){
				// Update email
				$result = mysqli_query($link, "UPDATE $t_users SET user_alias=$inp_user_alias_mysql WHERE user_id=$user_id_mysql") or die(mysqli_error($link));
				$fm = "user_alias_updated";
				$ft = "success";
			}
			else{
				if($check_user_id != "$user_id"){
					$fm = "user_alias_already_in_use";
					$ft = "warning";
				}
			}
		
		}

		$inp_user_rank = $_POST['inp_user_rank'];
		$inp_user_rank = output_html($inp_user_rank);
		$inp_user_rank_mysql = quote_smart($link, $inp_user_rank);

		$inp_user_verified_by_moderator = $_POST['inp_user_verified_by_moderator'];
		$inp_user_verified_by_moderator = output_html($inp_user_verified_by_moderator);
		$inp_user_verified_by_moderator_mysql = quote_smart($link, $inp_user_verified_by_moderator);

		
		// Name
		$inp_user_first_name = $_POST['inp_user_first_name'];
		$inp_user_first_name = output_html($inp_user_first_name);
		$inp_user_first_name = ucwords($inp_user_first_name);
		$inp_user_first_name_mysql = quote_smart($link, $inp_user_first_name);

		$inp_user_middle_name = $_POST['inp_user_middle_name'];
		$inp_user_middle_name = output_html($inp_user_middle_name);
		$inp_user_middle_name = ucwords($inp_user_middle_name);
		$inp_user_middle_name_mysql = quote_smart($link, $inp_user_middle_name);

		$inp_user_last_name = $_POST['inp_user_last_name'];
		$inp_user_last_name = output_html($inp_user_last_name);
		$inp_user_last_name = ucwords($inp_user_last_name);
		$inp_user_last_name_mysql = quote_smart($link, $inp_user_last_name);

		// Location
		$inp_user_language = $_POST['inp_user_language'];
		$inp_user_language = output_html($inp_user_language);
		$inp_user_language_mysql = quote_smart($link, $inp_user_language);

		$inp_user_country = $_POST['inp_user_country'];
		$inp_user_country = output_html($inp_user_country);
		$inp_user_country_mysql = quote_smart($link, $inp_user_country);

		$inp_user_country_id = 0;
		$inp_user_country_name = "";

		$query = "SELECT country_id, country_name FROM $t_languages_countries WHERE country_name=$inp_user_country_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_country_id, $get_country_name) = $row;
		if($get_country_id != ""){
			$inp_user_country_id = "$get_country_id";
			$inp_user_country_name = "$get_country_name";
		}
		$inp_user_country_id_mysql = quote_smart($link, $inp_user_country_id);
		$inp_user_country_name_mysql = quote_smart($link, $inp_user_country_name);

		$inp_user_city_name = $_POST['inp_user_city_name'];
		$inp_user_city_name = output_html($inp_user_city_name);
		$inp_user_city_name_mysql = quote_smart($link, $inp_user_city_name);

		$inp_timezone_value = $_POST['inp_timezone_value'];
		$inp_timezone_value = output_html($inp_timezone_value);
		$inp_timezone_value_mysql = quote_smart($link, $inp_timezone_value);

		$inp_timezone_utc_diff_array = explode(")", $inp_timezone_value);
		$inp_timezone_utc_diff = str_replace("(", "", $inp_timezone_utc_diff_array[0]);
		$inp_timezone_utc_diff = str_replace("GMT", "", $inp_timezone_utc_diff);
		$inp_timezone_utc_diff_array = explode(":", $inp_timezone_utc_diff);
		$inp_timezone_utc_diff = $inp_timezone_utc_diff_array[0];
		if($inp_timezone_utc_diff == ""){
			$inp_timezone_utc_diff = "0";
		}
		$inp_timezone_utc_diff_mysql = quote_smart($link, $inp_timezone_utc_diff);

		$inp_user_measurement = $_POST['inp_user_measurement'];
		$inp_user_measurement = output_html($inp_user_measurement);
		$inp_user_measurement_mysql = quote_smart($link, $inp_user_measurement);

		// Personal
		$inp_user_gender = $_POST['inp_user_gender'];
		$inp_user_gender = output_html($inp_user_gender);
		$inp_user_gender_mysql = quote_smart($link, $inp_user_gender);

		$inp_user_dob_day = $_POST['inp_user_dob_day'];
		$day_len = strlen($inp_user_dob_day);
		$inp_user_dob_month = $_POST['inp_user_dob_month'];
		$month_len = strlen($inp_user_dob_month);
		$inp_user_dob_year = $_POST['inp_user_dob_year'];
		$year_len = strlen($inp_user_dob_year);
		$inp_user_dob = $inp_user_dob_year . "-" . $inp_user_dob_month . "-" . $inp_user_dob_day;
		$inp_user_dob = output_html($inp_user_dob);
		$inp_user_dob_mysql = quote_smart($link, $inp_user_dob);
		if($inp_user_dob != "--"){
			$result = mysqli_query($link, "UPDATE $t_users SET user_dob=$inp_user_dob_mysql WHERE user_id=$user_id_mysql") or die(mysqli_error($link));
		}
	
		// Numbers
		$inp_user_points = $_POST['inp_user_points'];
		$inp_user_points = output_html($inp_user_points);
		if($inp_user_points == ""){
			$inp_user_points = "0";
		}
		$inp_user_points_mysql = quote_smart($link, $inp_user_points);


		$inp_user_likes = $_POST['inp_user_likes'];
		$inp_user_likes = output_html($inp_user_likes);
		if($inp_user_likes == ""){
			$inp_user_likes = "0";
		}
		$inp_user_likes_mysql = quote_smart($link, $inp_user_likes);

		$inp_user_dislikes = $_POST['inp_user_dislikes'];
		$inp_user_dislikes = output_html($inp_user_dislikes);
		if($inp_user_dislikes == ""){
			$inp_user_dislikes = "0";
		}
		$inp_user_dislikes_mysql = quote_smart($link, $inp_user_dislikes);

		// Status
		$inp_user_status = $_POST['inp_user_status'];
		$inp_user_status = output_html($inp_user_status);
		$inp_user_status_mysql = quote_smart($link, $inp_user_status);

		$result = mysqli_query($link, "UPDATE $t_users SET 
					user_language=$inp_user_language_mysql, 
					user_gender=$inp_user_gender_mysql, 
					user_measurement=$inp_user_measurement_mysql, 
					user_timezone_utc_diff=$inp_timezone_utc_diff_mysql, 
					user_timezone_value=$inp_timezone_value_mysql, 
					user_country_id=$inp_user_country_id_mysql, 
					user_country_name=$inp_user_country_name_mysql, 
					user_city_name=$inp_user_city_name_mysql, 
					user_rank=$inp_user_rank_mysql, 
					user_first_name=$inp_user_first_name_mysql, 
					user_middle_name=$inp_user_middle_name_mysql, 
					user_last_name=$inp_user_last_name_mysql, 
					user_points=$inp_user_points_mysql, 
					user_likes=$inp_user_likes_mysql, 
					user_dislikes=$inp_user_dislikes_mysql, 
					user_status=$inp_user_status_mysql, 
					user_verified_by_moderator=$inp_user_verified_by_moderator_mysql 
					WHERE user_id=$user_id_mysql") or die(mysqli_error($link));
		
		if($ft == "" OR $ft == "success"){
			if($fm == ""){
				$fm = "changes_saved";
				$ft = "success";
			}
		}
		// get new information
		$query = "SELECT user_id, user_email, user_name, user_alias, user_password, user_password_replacement, user_password_date, user_salt, user_security, user_language, user_gender, user_height, user_measurement, user_dob, user_date_format, user_country_id, user_country_name, user_city_name, user_timezone_utc_diff, user_timezone_value, user_registered, user_registered_time, user_last_online, user_last_online_time, user_rank, user_first_name, user_middle_name, user_last_name, user_newsletter, user_points, user_points_rank, user_likes, user_dislikes, user_status, user_login_tries, user_last_ip, user_synchronized, user_verified_by_moderator, user_notes, user_marked_as_spammer FROM $t_users WHERE user_id=$user_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_user_id, $get_current_user_email, $get_current_user_name, $get_current_user_alias, $get_current_user_password, $get_current_user_password_replacement, $get_current_user_password_date, $get_current_user_salt, $get_current_user_security, $get_current_user_language, $get_current_user_gender, $get_current_user_height, $get_current_user_measurement, $get_current_user_dob, $get_current_user_date_format, $get_current_user_country_id, $get_current_user_country_name, $get_current_user_city_name, $get_current_user_timezone_utc_diff, $get_current_user_timezone_value, $get_current_user_registered, $get_current_user_registered_time, $get_current_user_last_online, $get_current_user_last_online_time, $get_current_user_rank, $get_current_user_first_name, $get_current_user_middle_name, $get_current_user_last_name, $get_current_user_newsletter, $get_current_user_points, $get_current_user_points_rank, $get_current_user_likes, $get_current_user_dislikes, $get_current_user_status, $get_current_user_login_tries, $get_current_user_last_ip, $get_current_user_synchronized, $get_current_user_verified_by_moderator, $get_current_user_notes, $get_current_user_marked_as_spammer) = $row;

	
		// Get professional
		$query = "SELECT professional_id, professional_user_id, professional_company, professional_company_location, professional_department, professional_work_email, professional_position, professional_position_abbr, professional_district FROM $t_users_professional WHERE professional_user_id=$get_user_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_professional_id, $get_professional_user_id, $get_professional_company, $get_professional_company_location, $get_professional_department, $get_professional_work_email, $get_professional_position, $get_professional_position_abbr, $get_professional_district) = $row;


		// Search engine
		if($configShowUsersOnSearchEngineIndexSav == "1"){
			$inp_index_title = "$get_current_user_name";
			if($configIncludeFirstNameLastNameOnSearchEngineIndexSav == "1"){
				if($get_current_user_first_name != "" OR  $get_current_user_middle_name != "" OR $get_current_user_last_name != ""){
					$inp_index_title = $inp_index_title . " | $get_current_user_first_name $get_current_user_middle_name $get_current_user_last_name";
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

			$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name='users' AND index_reference_name='user_id' AND index_reference_id=$get_current_user_id";
			$result_exists = mysqli_query($link, $query_exists);
			$row_exists = mysqli_fetch_row($result_exists);
			list($get_index_id) = $row_exists;
			if($get_index_id != ""){
				$result = mysqli_query($link, "UPDATE $t_search_engine_index SET 
								index_title=$inp_index_title_mysql 
								WHERE index_id=$get_index_id") or die(mysqli_error($link));
			}
		} // search engine

	}
	echo"
	<h1>$l_edit_user $get_current_user_name</h1>
	<!-- Menu -->
		";
		include("_inc/users/users_edit_user_menu.php");
		echo"
	<!-- //Menu -->
	<form method=\"POST\" action=\"index.php?open=$open&amp;page=users_edit_user&amp;action=&amp;mode=save&amp;user_id=$user_id&amp;l=$l&amp;editor_language=$editor_language\" enctype=\"multipart/form-data\" name=\"nameform\">
	<!-- Feedback -->
		";
		if($ft != "" && $fm != ""){
			if($fm == "email_alreaddy_in_use"){
				$fm = "$l_email_alreaddy_in_use";
			}
			elseif($fm == "user_name_updated"){
				$fm = "$l_user_name_updated";
			}
			elseif($fm == "user_name_alreaddy_in_use"){
				$fm = "$l_user_name_alreaddy_in_use";
			}
			elseif($fm == "email_address_updated"){
				$fm = "$l_email_address_updated";
			}
			elseif($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = "$ft";
			}
			echo"<div class=\"$ft\"><p>$fm</p></div>";
		}
		echo"
	<!-- //Feedback -->
	<!-- Focus -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_user_email\"]').focus();
		});
		</script>
	<!-- //Focus -->

	<!-- User -->
		<h2>User</h2>
		<p>$l_email_address:<br />
		<input type=\"text\" name=\"inp_user_email\" size=\"25\" style=\"width: 99%;\" value=\"$get_current_user_email\" /><br />
		</p>

		<p>$l_user_name:<br />
		<input type=\"text\" name=\"inp_user_name\" size=\"25\" style=\"width: 99%;\" value=\"$get_current_user_name\" /><br />
		</p>

		<p>Alias:<br />
		<input type=\"text\" name=\"inp_user_alias\" size=\"25\" style=\"width: 99%;\" value=\"$get_current_user_alias\" /><br />
		</p>

		<p>
		$l_rank:<br />
		<select name=\"inp_user_rank\">";
		if($get_my_user_rank == "admin"){
			echo"<option value=\"admin\""; if($get_current_user_rank == "admin"){ echo" selected=\"selected\""; } echo">$l_admin</option>\n";
			echo"<option value=\"moderator\""; if($get_current_user_rank == "moderator"){ echo" selected=\"selected\""; } echo">$l_moderator</option>\n";
			echo"<option value=\"editor\""; if($get_current_user_rank == "editor"){ echo" selected=\"selected\""; } echo">$l_editor</option>\n";
			echo"<option value=\"trusted\""; if($get_current_user_rank == "trusted"){ echo" selected=\"selected\""; } echo">$l_trusted</option>\n";
			echo"<option value=\"user\""; if($get_current_user_rank == "user"){ echo" selected=\"selected\""; } echo">$l_user</option>\n";
		}
		elseif($get_my_user_rank == "moderator"){
			echo"<option value=\"moderator\""; if($get_current_user_rank == "moderator"){ echo" selected=\"selected\""; } echo">$l_moderator</option>\n";
			echo"<option value=\"editor\""; if($get_current_user_rank == "editor"){ echo" selected=\"selected\""; } echo">$l_editor</option>\n";
			echo"<option value=\"trusted\""; if($get_current_user_rank == "trusted"){ echo" selected=\"selected\""; } echo">$l_trusted</option>\n";
			echo"<option value=\"user\""; if($get_current_user_rank == "user"){ echo" selected=\"selected\""; } echo">$l_user</option>\n";
		}
		echo"
		</select>
		</p>

		<p>
		$l_user_verified_by_moderator:<br />
		<select name=\"inp_user_verified_by_moderator\">
		<option value=\"\""; if($get_current_user_verified_by_moderator == ""){ echo" selected=\"selected\""; } echo">- $l_please_select -</option>
		<option value=\"1\""; if($get_current_user_verified_by_moderator == "1"){ echo" selected=\"selected\""; } echo">$l_yes</option>
		<option value=\"0\""; if($get_current_user_verified_by_moderator == "0"){ echo" selected=\"selected\""; } echo">$l_no</option>
		</select>
		</p>
	<!-- //User -->

	<!-- Name -->
		<hr />
		<h2>Name</h2>

		<p>$l_first_name:<br />
		<input type=\"text\" name=\"inp_user_first_name\" size=\"25\" style=\"width: 99%;\" value=\"$get_current_user_first_name\" />
		</p>

		<p>$l_middle_name:<br />
		<input type=\"text\" name=\"inp_user_middle_name\" size=\"25\" style=\"width: 99%;\" value=\"$get_current_user_middle_name\" />
		</p>

		<p>$l_last_name:<br />
		<input type=\"text\" name=\"inp_user_last_name\" size=\"25\" style=\"width: 99%;\" value=\"$get_current_user_last_name\" />
		</p>
	<!-- //Name -->

	<!-- Localization -->
		<hr />
		<h2>Localization</h2>

		<p>$l_language:<br />
		<select name=\"inp_user_language\">";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;
			echo"			";
			echo"<option value=\"$get_language_active_iso_two\""; if($get_language_active_iso_two == "$get_current_user_language"){ echo" selected=\"selected\""; } echo">$get_language_active_name</option>\n";
		}
		echo"
		</select>
		</p>

		<p>Country:<br />
		<select name=\"inp_user_country\">
			<option value=\"-\">- Please select -</option>\n";
		$query = "SELECT country_id, country_name FROM $t_languages_countries ORDER BY country_name ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_country_id, $get_country_name) = $row;
			echo"			";
			echo"<option value=\"$get_country_name\""; if($get_country_name == "$get_current_user_country_name"){ echo" selected=\"selected\""; } echo">$get_country_name</option>\n";
		}
		echo"
		</select>
		</p>
	
		<p>City:<br />
		<input type=\"text\" name=\"inp_user_city_name\" size=\"25\" style=\"width: 99%;\" value=\"$get_current_user_city_name\" />
		</p>

		<p>Timezone:<br />
		<select name=\"inp_timezone_value\">\n";
		$timezones = timezone_list();
		foreach ($timezones as $key => $row) {
			echo"			";
			echo"<option value=\"$row\""; if($get_current_user_timezone_value == "$row"){ echo" selected=\"selected\""; } echo">$row</option>\n";
		}
		echo"
		</select>
		</p>

		<p>
		$l_measurement:<br />
		<select name=\"inp_user_measurement\">
		<option value=\"\""; if($get_current_user_measurement == ""){ echo" selected=\"selected\""; } echo">- $l_please_select -</option>
		<option value=\"metric\""; if($get_current_user_measurement == "metric"){ echo" selected=\"selected\""; } echo">Metric</option>
		<option value=\"imperial\""; if($get_current_user_measurement == "imperial"){ echo" selected=\"selected\""; } echo">Imperial</option>
		</select>
		</p>
	<!-- //Localization -->

	<!-- Personal -->
		<hr />
		<h2>Personal</h2>
		<p>
		$l_gender:<br />
		<select name=\"inp_user_gender\"> 
		<option value=\"\""; if($get_current_user_gender == ""){ echo" selected=\"selected\""; } echo">- $l_please_select -</option>
		<option value=\"male\""; if($get_current_user_gender == "male"){ echo" selected=\"selected\""; } echo">$l_male</option>
		<option value=\"female\""; if($get_current_user_gender == "female"){ echo" selected=\"selected\""; } echo">$l_female</option>
		</select>
		</p>

		<p>$l_birthday:<br />";
		$dob_array = explode("-", $get_current_user_dob);
		$dob_year = $dob_array[0];
		if(isset($dob_array[1])){
			$dob_month = $dob_array[1];
		}
		else{
			$dob_month = 0;
		}
		if(isset($dob_array[2])){
			$dob_day = $dob_array[2];
		}
		else{
			$dob_day = 0;
		}
			
		echo"
		<select name=\"inp_user_dob_day\">
		<option value=\"\""; if($dob_day == ""){ echo" selected=\"selected\""; } echo">- $l_day -</option>\n";
		for($x=1;$x<32;$x++){
			if($x<10){
				$y = 0 . $x;
			}
			else{
				$y = $x;
			}
			echo"<option value=\"$y\""; if($dob_day == "$x"){ echo" selected=\"selected\""; } echo">$x</option>\n";
		}
		echo"
		</select>
		<select name=\"inp_user_dob_month\">
		<option value=\"\""; if($dob_month == ""){ echo" selected=\"selected\""; } echo">- $l_month -</option>\n";
			$l_month_array[0] = "";
			$l_month_array[1] = "$l_january";
			$l_month_array[2] = "$l_february";
			$l_month_array[3] = "$l_march";
			$l_month_array[4] = "$l_april";
			$l_month_array[5] = "$l_may";
			$l_month_array[6] = "$l_june";
			$l_month_array[7] = "$l_juli";
			$l_month_array[8] = "$l_august";
			$l_month_array[9] = "$l_september";
			$l_month_array[10] = "$l_october";
			$l_month_array[11] = "$l_november";
			$l_month_array[12] = "$l_december";
			for($x=1;$x<13;$x++){
				if($x<10){
					$y = 0 . $x;
				}
				else{
					$y = $x;
				}
				echo"<option value=\"$y\""; if($dob_month == "$y"){ echo" selected=\"selected\""; } echo">$l_month_array[$x]</option>\n";
			}
		echo"
		</select>
		<select name=\"inp_user_dob_year\">
		<option value=\"\""; if($dob_year == ""){ echo" selected=\"selected\""; } echo">- $l_year -</option>\n";
		$year = date("Y");
			for($x=0;$x<150;$x++){
				echo"<option value=\"$year\""; if($dob_year == "$year"){ echo" selected=\"selected\""; } echo">$year</option>\n";
				$year = $year-1;
			}
			echo"
		</select>
		</p>
	<!-- //Personal -->

	<!-- Numbers -->
		<hr />
		<h2>Numbers</h2>

		<table>
		 <tr>
		  <td style=\"padding-right: 10px;\">
			<p>$l_points:<br />
			<input type=\"text\" name=\"inp_user_points\" size=\"5\" value=\"$get_current_user_points\" /><br />
			</p>
		  </td>
		  <td style=\"padding-right: 10px;\">
			<p>
			$l_likes:<br />
			<input type=\"text\" name=\"inp_user_likes\" size=\"5\" value=\"$get_current_user_likes\" /><br />
			</p>
		  </td>
		  <td>
			<p>
			$l_dislikes:<br />
			<input type=\"text\" name=\"inp_user_dislikes\" size=\"5\" value=\"$get_current_user_dislikes\" /><br />
			</p>
		  </td>
		 </tr>
		</table>

	<!-- //Numbers -->

	<!-- Status -->
		<hr />
		<h2>Status</h2>

		<p>
		$l_status:<br />
		<input type=\"text\" name=\"inp_user_status\" size=\"25\" style=\"width: 99%;\" value=\"$get_current_user_status\" /><br />
		</p>
	<!-- //Status -->

			
	<p>
	<input type=\"submit\" value=\"$l_save\" class=\"btn_default\" />
	</p>
	</form>
	";
?>