<?php
/**
*
* File: users/my_profile_edit_headline.php
* Version 12:15 08.08.2021
* Copyright (c) 2009-2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "0";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

/*- Root dir --------------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config --------------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/users/ts_index.php");

/*- Tables ----------------------------------------------------------------------------------- */
include("_tables_users.php");

/*- Varialbes  ---------------------------------------------------- */
$tabindex = 0;

if (isset($_GET['headline_id'])) {
	$headline_id = $_GET['headline_id'];
	$headline_id = stripslashes(strip_tags($headline_id));
	if(!(is_numeric($headline_id))){
		echo"Headline id not numeric";
		die;
	}
}
else{
	echo"Missing headline id";
	die;
}
$headline_id_mysql = quote_smart($link, $headline_id);


// Logged in
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	// Get user
	$user_id = $_SESSION['user_id'];
	$user_id_mysql = quote_smart($link, $user_id);
	$security = $_SESSION['security'];
	$security_mysql = quote_smart($link, $security);

	$query = "SELECT user_id, user_email, user_name, user_alias, user_password, user_password_replacement, user_password_date, user_salt, user_security, user_rank, user_verified_by_moderator, user_first_name, user_middle_name, user_last_name, user_language, user_country_id, user_country_name, user_city_name, user_timezone_utc_diff, user_timezone_value, user_measurement, user_date_format, user_gender, user_height, user_dob, user_registered, user_registered_time, user_newsletter, user_privacy, user_views, user_views_ipblock, user_points, user_points_rank, user_likes, user_dislikes, user_status, user_login_tries, user_last_online, user_last_online_time, user_last_ip, user_synchronized, user_notes, user_marked_as_spammer FROM $t_users WHERE user_id=$user_id_mysql AND user_security=$security_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_password, $get_my_user_password_replacement, $get_my_user_password_date, $get_my_user_salt, $get_my_user_security, $get_my_user_rank, $get_my_user_verified_by_moderator, $get_my_user_first_name, $get_my_user_middle_name, $get_my_user_last_name, $get_my_user_language, $get_my_user_country_id, $get_my_user_country_name, $get_my_user_city_name, $get_my_user_timezone_utc_diff, $get_my_user_timezone_value, $get_my_user_measurement, $get_my_user_date_format, $get_my_user_gender, $get_my_user_height, $get_my_user_dob, $get_my_user_registered, $get_my_user_registered_time, $get_my_user_newsletter, $get_my_user_privacy, $get_my_user_views, $get_my_user_views_ipblock, $get_my_user_points, $get_my_user_points_rank, $get_my_user_likes, $get_my_user_dislikes, $get_my_user_status, $get_my_user_login_tries, $get_my_user_last_online, $get_my_user_last_online_time, $get_my_user_last_ip, $get_my_user_synchronized, $get_my_user_notes, $get_my_user_marked_as_spammer) = $row;
	if($get_my_user_id == ""){
		echo"<h1>Error</h1><p>Error with user id.</p>"; 
		$_SESSION = array();
		session_destroy();
		die;
	}

	// Get headline
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
		// Translation
		$l_mysql = quote_smart($link, $l);
		$query_t = "SELECT translation_id, translation_headline_id, translation_language, translation_value FROM $t_users_profile_headlines_translations WHERE translation_headline_id=$get_current_headline_id AND translation_language=$l_mysql";
		$result_t = mysqli_query($link, $query_t);
		$row_t = mysqli_fetch_row($result_t);
		list($get_translation_id, $get_translation_headline_id, $get_translation_language, $get_translation_value) = $row_t;

		// Data
		$t_users_profile_data = $mysqlPrefixSav . "users_profile_data_" . $get_current_headline_title_clean;
		$query_data = "SELECT * FROM $t_users_profile_data WHERE data_user_id=$get_my_user_id";
		$result_data = mysqli_query($link, $query_data);
		$row_data = mysqli_fetch_row($result_data);
		

		/*- Headers ---------------------------------------------------------------------------------- */
		$website_title = "$get_translation_value - $l_my_profile";
		include("$root/_webdesign/header.php");



		/*- Content --------------------------------------------------------------------------- */
		if($process == "1"){

			// Check if row exists
			if(!(isset($row_data[0]))){
				mysqli_query($link, "INSERT INTO $t_users_profile_data 
				(data_id, data_user_id) 
				VALUES 
				(NULL, $get_my_user_id)")
				or die(mysqli_error($link));
			}

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
			$update_query = $update_query . " WHERE data_user_id=$get_my_user_id";
			mysqli_query($link, "$update_query")or die(mysqli_error($link));


			// Header
			$url = "my_profile_edit_headline.php?headline_id=$get_current_headline_id&l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;
		}

		echo"
		<h1>$get_translation_value</h1>

		<!-- You are here -->
			<div class=\"you_are_here\">
				<p>
				<b>$l_you_are_here:</b><br />
				<a href=\"index.php?l=$l\">$l_users</a>
				&gt;
				<a href=\"my_profile.php?l=$l\">$l_my_profile</a>
				&gt; 
				<a href=\"my_profile_edit_headline.php?headline_id=$get_current_headline_id&amp;l=$l\">$get_translation_value</a>
				</p>
			</div>
		<!-- //You are here -->

		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_user_alias\"]').focus();
			});
			</script>
		<!-- //Focus -->



		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				else{
					$fm = str_replace("_", " ", $fm);
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->


		<!-- Edit headline form -->
			<form method=\"POST\" action=\"my_profile_edit_headline.php?headline_id=$get_current_headline_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\" name=\"nameform\">

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
					echo"<input type=\"text\" name=\"inp_$get_field_title_clean\" value=\""; if(isset($row_data[$col_no])){ echo"$row_data[$col_no]"; } echo"\" size=\"$get_field_size\" style=\"width: $get_field_width\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />\n";
				}
				elseif($get_field_type == "textarea"){
					echo"				";
					echo"<textarea name=\"inp_$get_field_title_clean\" rows=\"$get_field_rows\" cols=\"$get_field_cols\" style=\"width: $get_field_width\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\">";
					$data = "";
					if(isset($row_data[$col_no])){
						$data = str_replace("<br />", "\n", $row_data[$col_no]);
					}
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
						echo"<option value=\"$get_option_title\""; if(isset($row_data[$col_no]) && $get_option_title == "$row_data[$col_no]"){ echo" selected=\"selected\""; } echo">$get_option_title</option>\n";

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
						echo"<input type=\"radio\" name=\"inp_$get_field_title_clean\" value=\""; if(isset($row_data[$col_no])){ echo"$row_data[$col_no]"; } echo"\" "; if(isset($row_data[$col_no]) && $get_option_title == "$row_data[$col_no]"){ echo" checked=\"checked\""; } echo" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /> $get_option_title &nbsp;\n";

					}
				}
				elseif($get_field_type == "checkbox"){
					echo"				";
					echo"<input type=\"checkbox\" name=\"inp_$get_field_title_clean\" "; if(isset($row_data[$col_no]) && $row_data[$col_no] == "1"){ echo" checked=\"checked\""; } echo" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />\n";

				}
				echo"
				</p>
				";
				$col_no++;
			}
			echo"

			<p>
			<input type=\"submit\" value=\"$l_save_changes\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</form>
		<!-- //Edit headline form -->

		";
	} // headline found
} // logged in
else{
	$url = "login.php?l=$l&referer=my_profile_edit_headline.php?headline_id=$get_current_headline_id";
	header("Location: $url");
	exit;
}
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>