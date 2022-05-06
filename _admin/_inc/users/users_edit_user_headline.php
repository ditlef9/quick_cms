<?php
/*- MySQL Tables -------------------------------------------------- */
$t_users 	 		= $mysqlPrefixSav . "users";
$t_users_profile 		= $mysqlPrefixSav . "users_profile";
$t_users_professional		= $mysqlPrefixSav . "users_professional";
$t_users_friends 		= $mysqlPrefixSav . "users_friends";
$t_users_friends_requests 	= $mysqlPrefixSav . "users_friends_requests";
$t_users_profile		= $mysqlPrefixSav . "users_profile";
$t_users_profile_photo 		= $mysqlPrefixSav . "users_profile_photo";
$t_users_status 		= $mysqlPrefixSav . "users_status";
$t_users_status_comments 	= $mysqlPrefixSav . "users_status_comments";
$t_users_status_comments_likes 	= $mysqlPrefixSav . "users_status_comments_likes";
$t_users_status_likes 		= $mysqlPrefixSav . "users_status_likes";

$t_users_professional_allowed_companies			= $mysqlPrefixSav . "users_professional_allowed_companies";
$t_users_professional_allowed_company_locations		= $mysqlPrefixSav . "users_professional_allowed_company_locations";
$t_users_professional_allowed_departments		= $mysqlPrefixSav . "users_professional_allowed_departments";
$t_users_professional_allowed_positions			= $mysqlPrefixSav . "users_professional_allowed_positions";
$t_users_professional_allowed_districts			= $mysqlPrefixSav . "users_professional_allowed_districts";
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

/*- Config  ----------------------------------------------------------------------------- */
include("_data/user_professional_allowed_settings.php");

/*- Language ------------------------------------------------------ */
include("_translations/admin/$l/users/t_users_edit_user.php");

/*- Varialbes  ---------------------------------------------------- */
$tabindex = 0;

if(isset($_GET['user_id'])) {
	$user_id = $_GET['user_id'];
	$user_id = strip_tags(stripslashes($user_id));
}
else{
	$user_id = "";
	echo"
	<h1>Error</h1>

	<p>Missing user id</p>
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
if(isset($_GET['headline_id'])) {
	$headline_id = $_GET['headline_id'];
	$headline_id = strip_tags(stripslashes($headline_id));
	if(!is_numeric($headline_id)){
		echo"Headline id is not numeric";
		die;
	}
}
else{
	echo"Missing headline id";
	die;
}
$headline_id_mysql = quote_smart($link, $headline_id);

// Get user
$user_id_mysql = quote_smart($link, $user_id);
$query = "SELECT user_id, user_email, user_name, user_alias, user_password, user_salt, user_security, user_language, user_gender, user_measurement, user_dob, user_date_format, user_registered, user_last_online, user_rank, user_points, user_likes, user_dislikes, user_status, user_login_tries, user_last_ip, user_synchronized, user_verified_by_moderator FROM $t_users WHERE user_id=$user_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_password, $get_user_salt, $get_user_security, $get_user_language, $get_user_gender, $get_user_measurement, $get_user_dob, $get_user_date_format, $get_user_registered, $get_user_last_online, $get_user_rank, $get_user_points, $get_user_likes, $get_user_dislikes, $get_user_status, $get_user_login_tries, $get_user_last_ip, $get_user_synchronized, $get_user_verified_by_moderator) = $row;

$query = "SELECT professional_id, professional_user_id, professional_company, professional_company_location, professional_department, professional_work_email, professional_position, professional_position_abbr, professional_district FROM $t_users_professional WHERE professional_user_id=$get_user_id";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_professional_id, $get_professional_user_id, $get_professional_company, $get_professional_company_location, $get_professional_department, $get_professional_work_email, $get_professional_position, $get_professional_position_abbr, $get_professional_district) = $row;

if($get_user_id == ""){
	echo"<h1>Error</h1><p>Error with user id.</p>"; 
	die;
}
if($get_professional_id == ""){
	// Create professional profile
	mysqli_query($link, "INSERT INTO $t_users_professional 
	(professional_id, professional_user_id) 
	VALUES 
	(NULL, $get_user_id)")
	or die(mysqli_error($link));
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

// Get headline	// Get headline
$query = "SELECT headline_id, headline_title, headline_title_clean, headline_icon_path_18x18, headline_icon_file_18x18, headline_weight, headline_user_can_view_headline, headline_show_on_profile FROM $t_users_profile_headlines WHERE headline_id=$headline_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_headline_id, $get_current_headline_title, $get_current_headline_title_clean, $get_current_headline_icon_path_18x18, $get_current_headline_icon_file_18x18, $get_current_headline_weight, $get_current_headline_user_can_view_headline, $get_current_headline_show_on_profile) = $row;

if($get_current_headline_id == ""){
	echo"
	<h1>Server error 404</h1>
	<p>Headline not found.</p>
	";
}
else{	
	// Data
	$t_users_profile_data = $mysqlPrefixSav . "users_profile_data_" . $get_current_headline_title_clean;
	$query_data = "SELECT * FROM $t_users_profile_data WHERE data_user_id=$get_user_id";
	$result_data = mysqli_query($link, $query_data);
	$row_data = mysqli_fetch_row($result_data);
	if(!(isset($row_data[0]))){
		mysqli_query($link, "INSERT INTO $t_users_profile_data 
		(data_id, data_user_id) 
		VALUES 
		(NULL, $get_user_id)")
		or die(mysqli_error($link));

		$query_data = "SELECT * FROM $t_users_profile_data WHERE data_user_id=$get_user_id";
		$result_data = mysqli_query($link, $query_data);
		$row_data = mysqli_fetch_row($result_data);
	}

	


	if($process == "1"){
		// Check if row exists
		$update_query = "UPDATE $t_users_profile_data SET";
		$x = 0;
		$query = "SELECT field_id, field_headline_id, field_title, field_title_clean, field_weight, field_height, field_type, field_size, field_width, field_cols, field_rows FROM $t_users_profile_fields WHERE field_headline_id=$get_current_headline_id AND field_user_can_view_field=1 ORDER BY field_weight ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_field_id, $get_field_headline_id, $get_field_title, $get_field_title_clean, $get_field_weight, $get_field_height, $get_field_type, $get_field_size, $get_field_width, $get_field_cols, $get_field_rows) = $row;
				
			$inp_data = $_POST["inp_$get_field_title_clean"];
			$inp_data = output_html($inp_data);
			$inp_data_mysql = quote_smart($link, $inp_data);

			if($x == "0"){
				$update_query = $update_query . "
						$get_field_title_clean=$inp_data_mysql";
			}
			else{
				$update_query = $update_query . ", 
						$get_field_title_clean=$inp_data_mysql";
			}
			$x++;
		}
		$update_query = $update_query . " WHERE data_user_id=$get_user_id";
		mysqli_query($link, "$update_query")or die(mysqli_error($link));


		// Header
		$url = "index.php?open=users&page=users_edit_user_headline&action=headline&user_id=$get_user_id&headline_id=$get_current_headline_id&l=$l&ft=success&fm=changes_saved";
		header("Location: $url");
		exit;
	}
	echo"
	<h1>$l_edit $get_user_name</h1>

	<!-- Menu -->
	";
	include("_inc/users/users_edit_user_menu.php");
	echo"
	<!-- //Menu -->

	<!-- Feedback -->
		";
		if($ft != "" && $fm != ""){
			if($fm == "changes_saved"){
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
			\$('[name=\"inp_company\"]').focus();
		});
		</script>
	<!-- //Focus -->

	<!-- Edit user headline form -->
		<form method=\"POST\" action=\"index.php?open=users&amp;page=users_edit_user_headline&amp;action=headline&amp;user_id=$get_user_id&amp;headline_id=$get_current_headline_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">



		";
		$col_no = 2;
		$query = "SELECT field_id, field_headline_id, field_title, field_title_clean, field_weight, field_height, field_type, field_size, field_width, field_cols, field_rows FROM $t_users_profile_fields WHERE field_headline_id=$get_current_headline_id AND field_user_can_view_field=1 ORDER BY field_weight ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_field_id, $get_field_headline_id, $get_field_title, $get_field_title_clean, $get_field_weight, $get_field_height, $get_field_type, $get_field_size, $get_field_width, $get_field_cols, $get_field_rows) = $row;
				
			// Get translation
			$query_t = "SELECT translation_id, translation_field_id, translation_language, translation_value FROM $t_users_profile_fields_translations WHERE translation_field_id=$get_field_id AND translation_language=$l_mysql";
			$result_t = mysqli_query($link, $query_t);
			$row_t = mysqli_fetch_row($result_t);
			list($get_translation_id, $get_translation_field_id, $get_translation_language, $get_translation_value) = $row_t;
			if($get_translation_id == ""){
				$inp_title_mysql = quote_smart($link, $get_field_title);
				mysqli_query($link, "INSERT INTO $t_users_profile_fields_translations
				(translation_id, translation_field_id, translation_headline_id, translation_language, translation_value) 
				VALUES 
				(NULL, $get_current_headline_id, $get_field_id, $l_mysql, $inp_title_mysql)")
				or die(mysqli_error($link));
				$get_translation_value = "$get_field_title";
			}

				
			echo"
			<p>$get_translation_value:<br />\n";
			if($get_field_type == "text" OR $get_field_type == "url"){
				echo"				";
				echo"<input type=\"text\" name=\"inp_$get_field_title_clean\" value=\"$row_data[$col_no]\" size=\"$get_field_size\" style=\"width: $get_field_width\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />\n";
			}
			elseif($get_field_type == "textarea"){
				echo"				";
				echo"<textarea name=\"inp_$get_field_title_clean\" rows=\"$get_field_rows\" cols=\"$get_field_cols\" style=\"width: $get_field_width\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\">";
				$data = str_replace("<br />", "\n", $row_data[$col_no]);
				echo"$data</textarea>\n";
			}
			elseif($get_field_type == "select"){
				echo"				";
				echo"<select name=\"inp_$get_field_title_clean\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\">
					<option value=\"\">- $l_please_select -</option>\n";
					$query = "SELECT option_id, option_field_id, option_headline_id, option_title, option_title_clean, option_weight FROM $t_users_profile_fields_options WHERE option_field_id=$get_field_id ORDER BY option_weight ASC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_option_id, $get_option_field_id, $get_option_headline_id, $get_option_title, $get_option_title_clean, $get_option_weight) = $row;
						echo"				";
						echo"<option value=\"$get_option_title\""; if($get_option_title == "$row_data[$col_no]"){ echo" selected=\"selected\""; } echo">$get_option_title</option>\n";

				}
				echo"
				</select>
				";
			}
			elseif($get_field_type == "radio"){
				$query = "SELECT option_id, option_field_id, option_headline_id, option_title, option_title_clean, option_weight FROM $t_users_profile_fields_options WHERE option_field_id=$get_field_id ORDER BY option_weight ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_option_id, $get_option_field_id, $get_option_headline_id, $get_option_title, $get_option_title_clean, $get_option_weight) = $row;
					echo"				";
					echo"<input type=\"radio\" name=\"inp_$get_field_title_clean\" value=\"$get_option_title\" "; if($get_option_title == "$row_data[$col_no]"){ echo" checked=\"checked\""; } echo" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /> $get_option_title &nbsp;\n";
				}
			}
			elseif($get_field_type == "checkbox"){
				echo"				";
				echo"<input type=\"checkbox\" name=\"inp_$get_field_title_clean\" "; if($row_data[$col_no] == "1"){ echo" checked=\"checked\""; } echo" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />\n";
			}
			echo"
			</p>
			";
			$col_no++;
		}
		echo"


	
		<p>
		<input type=\"submit\" value=\"$l_save\" class=\"btn_default\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
		</p>
		</form>

	<!-- //Edit user headline form -->
	";
} // headline found

?>