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
$t_users_professional		= $mysqlPrefixSav . "users_professional";

$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";

$t_users_profile_headlines			= $mysqlPrefixSav . "users_profile_headlines";
$t_users_profile_headlines_translations		= $mysqlPrefixSav . "users_profile_headlines_translations";
$t_users_profile_fields				= $mysqlPrefixSav . "users_profile_fields";
$t_users_profile_fields_translations		= $mysqlPrefixSav . "users_profile_fields_translations";
$t_users_profile_fields_options			= $mysqlPrefixSav . "users_profile_fields_options";
$t_users_profile_fields_options_translations	= $mysqlPrefixSav . "users_profile_fields_options_translations";

/*- Access check -------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Language ------------------------------------------------------ */
include("_translations/admin/$l/users/t_users_edit_user.php");

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

$query = "SELECT user_id, user_email, user_name, user_alias, user_password, user_salt, user_security, user_language, user_gender, user_measurement, user_dob, user_date_format, user_registered, user_last_online, user_rank, user_points, user_likes, user_dislikes, user_status, user_login_tries, user_last_ip, user_synchronized, user_verified_by_moderator FROM $t_users WHERE user_id=$user_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_password, $get_user_salt, $get_user_security, $get_user_language, $get_user_gender, $get_user_measurement, $get_user_dob, $get_user_date_format, $get_user_registered, $get_user_last_online, $get_user_rank, $get_user_points, $get_user_likes, $get_user_dislikes, $get_user_status, $get_user_login_tries, $get_user_last_ip, $get_user_synchronized, $get_user_verified_by_moderator) = $row;

$query = "SELECT profile_id, profile_user_id, profile_first_name, profile_middle_name, profile_last_name, profile_address_line_a, profile_address_line_b, profile_zip, profile_city, profile_country, profile_phone, profile_work, profile_university, profile_high_school, profile_languages, profile_website, profile_interested_in, profile_relationship, profile_about, profile_newsletter FROM $t_users_profile WHERE profile_user_id=$user_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_profile_id, $get_profile_user_id, $get_profile_first_name, $get_profile_middle_name, $get_profile_last_name, $get_profile_address_line_a, $get_profile_address_line_b, $get_profile_zip, $get_profile_city, $get_profile_country, $get_profile_phone, $get_profile_work, $get_profile_university, $get_profile_high_school, $get_profile_languages, $get_profile_website, $get_profile_interested_in, $get_profile_relationship, $get_profile_about, $get_profile_newsletter) = $row;
	
if($get_user_id == ""){
	echo"<h1>Error</h1><p>Error with user id.</p>"; 
	die;
}
if($get_profile_id == ""){
	// Profile not found, create it
	mysqli_query($link, "INSERT INTO $t_users_profile 
	(profile_id, profile_user_id) 
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

if($mode == "save"){

				$inp_profile_first_name = $_POST['inp_profile_first_name'];
				$inp_profile_first_name = output_html($inp_profile_first_name);
				$inp_profile_first_name = ucwords($inp_profile_first_name);
				$inp_profile_first_name_mysql = quote_smart($link, $inp_profile_first_name);

				$inp_profile_middle_name = $_POST['inp_profile_middle_name'];
				$inp_profile_middle_name = output_html($inp_profile_middle_name);
				$inp_profile_middle_name = ucwords($inp_profile_middle_name);
				$inp_profile_middle_name_mysql = quote_smart($link, $inp_profile_middle_name);

				$inp_profile_last_name = $_POST['inp_profile_last_name'];
				$inp_profile_last_name = output_html($inp_profile_last_name);
				$inp_profile_last_name = ucwords($inp_profile_last_name);
				$inp_profile_last_name_mysql = quote_smart($link, $inp_profile_last_name);

				$inp_profile_address_line_a = $_POST['inp_profile_address_line_a'];
				$inp_profile_address_line_a = output_html($inp_profile_address_line_a);
				$inp_profile_address_line_a_mysql = quote_smart($link, $inp_profile_address_line_a);

				$inp_profile_address_line_b = $_POST['inp_profile_address_line_b'];
				$inp_profile_address_line_b = output_html($inp_profile_address_line_b);
				$inp_profile_address_line_b_mysql = quote_smart($link, $inp_profile_address_line_b);

				$inp_profile_zip = $_POST['inp_profile_zip'];
				$inp_profile_zip = output_html($inp_profile_zip);
				$inp_profile_zip_mysql = quote_smart($link, $inp_profile_zip);

				$inp_profile_city = $_POST['inp_profile_city'];
				$inp_profile_city = output_html($inp_profile_city);
				$inp_profile_city = ucfirst($inp_profile_city);
				$inp_profile_city_mysql = quote_smart($link, $inp_profile_city);

				$inp_profile_country = $_POST['inp_profile_country'];
				$inp_profile_country = output_html($inp_profile_country);
				$inp_profile_country = ucfirst($inp_profile_country);
				$inp_profile_country_mysql = quote_smart($link, $inp_profile_country);

				$inp_profile_phone = $_POST['inp_profile_phone'];
				$inp_profile_phone = output_html($inp_profile_phone);
				$inp_profile_phone_mysql = quote_smart($link, $inp_profile_phone);

				$inp_profile_work = $_POST['inp_profile_work'];
				$inp_profile_work = output_html($inp_profile_work);
				$inp_profile_work_mysql = quote_smart($link, $inp_profile_work);

				$inp_profile_university = $_POST['inp_profile_university'];
				$inp_profile_university = output_html($inp_profile_university);
				$inp_profile_university_mysql = quote_smart($link, $inp_profile_university);

				$inp_profile_high_school = $_POST['inp_profile_high_school'];
				$inp_profile_high_school = output_html($inp_profile_high_school);
				$inp_profile_high_school_mysql = quote_smart($link, $inp_profile_high_school);
	
				$inp_profile_languages = $_POST['inp_profile_languages'];
				$inp_profile_languages = output_html($inp_profile_languages);
				$inp_profile_languages_mysql = quote_smart($link, $inp_profile_languages);

				$inp_profile_website = $_POST['inp_profile_website'];
				$inp_profile_website = output_html($inp_profile_website);
				$inp_profile_website_mysql = quote_smart($link, $inp_profile_website);

				if(isset($_POST['inp_interested_in_men'])){
					$inp_interested_in_men = $_POST['inp_interested_in_men'];
				}
				else{
					$inp_interested_in_men = "0";
				}
				if(isset($_POST['inp_interested_in_women'])){
					$inp_interested_in_women = $_POST['inp_interested_in_women'];
				}
				else{
					$inp_interested_in_women = "0";
				}
		
				$inp_interested_in = $inp_interested_in_men . "|" . $inp_interested_in_women;
				$inp_interested_in = output_html($inp_interested_in);
				$inp_interested_in_mysql = quote_smart($link, $inp_interested_in);

				$inp_profile_relationship = $_POST['inp_profile_relationship'];
				$inp_profile_relationship = output_html($inp_profile_relationship);
				$inp_profile_relationship_mysql = quote_smart($link, $inp_profile_relationship);

				$inp_profile_about_me = $_POST['inp_profile_about_me'];
				$inp_profile_about_me = output_html($inp_profile_about_me);
				$inp_profile_about_me_mysql = quote_smart($link, $inp_profile_about_me);



				$result = mysqli_query($link, "UPDATE $t_users_profile SET profile_first_name=$inp_profile_first_name_mysql, profile_middle_name=$inp_profile_middle_name_mysql, profile_last_name=$inp_profile_last_name_mysql, profile_address_line_a=$inp_profile_address_line_a_mysql, profile_address_line_b=$inp_profile_address_line_b_mysql, profile_zip=$inp_profile_zip_mysql, profile_city=$inp_profile_city_mysql, profile_country=$inp_profile_country_mysql, profile_phone=$inp_profile_phone_mysql, profile_work=$inp_profile_work_mysql, profile_university=$inp_profile_university_mysql, profile_high_school=$inp_profile_high_school_mysql, profile_languages=$inp_profile_languages_mysql, profile_website=$inp_profile_website_mysql, profile_interested_in=$inp_interested_in_mysql, profile_relationship=$inp_profile_relationship_mysql, profile_about=$inp_profile_about_me_mysql WHERE profile_user_id=$user_id_mysql") or die(mysqli_error($link));
				
				// Send success
				$fm = "changes_saved";
				$ft = "success";
				
				// Get new information
				$query = "SELECT * FROM $t_users_profile WHERE profile_user_id=$user_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_profile_id, $get_profile_user_id, $get_profile_first_name, $get_profile_middle_name, $get_profile_last_name, $get_profile_address_line_a, $get_profile_address_line_b, $get_profile_zip, $get_profile_city, $get_profile_country, $get_profile_phone, $get_profile_work, $get_profile_university, $get_profile_high_school, $get_profile_languages, $get_profile_website, $get_profile_interested_in, $get_profile_relationship, $get_profile_about, $get_profile_newsletter) = $row;


		// Get professional
		$query = "SELECT professional_id, professional_user_id, professional_company, professional_company_location, professional_department, professional_work_email, professional_position, professional_position_abbr, professional_district FROM $t_users_professional WHERE professional_user_id=$get_user_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_professional_id, $get_professional_user_id, $get_professional_company, $get_professional_company_location, $get_professional_department, $get_professional_work_email, $get_professional_position, $get_professional_position_abbr, $get_professional_district) = $row;


		// Search engine
		if($configShowUsersOnSearchEngineIndexSav == "1"){
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

			$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name='users' AND index_reference_name='user_id' AND index_reference_id=$get_user_id";
			$result_exists = mysqli_query($link, $query_exists);
			$row_exists = mysqli_fetch_row($result_exists);
			list($get_index_id) = $row_exists;
			if($get_index_id != ""){
				$result = mysqli_query($link, "UPDATE $t_search_engine_index SET 
								index_title=$inp_index_title_mysql 
								WHERE index_id=$get_index_id") or die(mysqli_error($link));
			}
		} // search engine


} // mode == save
	echo"
			<h1>$l_edit $get_user_name</h1>

	<!-- Menu -->
		";
		include("_inc/users/users_edit_user_menu.php");
		echo"
	<!-- //Menu -->

			<form method=\"POST\" action=\"index.php?open=$open&amp;page=$page&amp;action=edit_profile&amp;mode=save&amp;user_id=$user_id&amp;l=$l&amp;editor_language=$editor_language\" enctype=\"multipart/form-data\" name=\"nameform\">

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
				\$('[name=\"inp_profile_first_name\"]').focus();
			});
			</script>
			<!-- //Focus -->

			<p>
			$l_first_name:<br />
			<input type=\"text\" name=\"inp_profile_first_name\" size=\"78\" value=\"$get_profile_first_name\" /><br />
			</p>

			<p>
			$l_middle_name:<br />
			<input type=\"text\" name=\"inp_profile_middle_name\" size=\"78\" value=\"$get_profile_middle_name\" /><br />
			</p>

			<p>
			$l_last_name:<br />
			<input type=\"text\" name=\"inp_profile_last_name\" size=\"78\" value=\"$get_profile_last_name\" /><br />
			</p>

			<p>
			$l_address_line_a:<br />
			<input type=\"text\" name=\"inp_profile_address_line_a\" size=\"78\" value=\"$get_profile_address_line_a\" /><br />
			</p>

			<p>
			$l_address_line_b:<br />
			<input type=\"text\" name=\"inp_profile_address_line_b\" size=\"78\" value=\"$get_profile_address_line_b\" /><br />
			</p>

			<p>
			$l_zip_and_city:<br />
			<input type=\"text\" name=\"inp_profile_zip\" size=\"5\" value=\"$get_profile_zip\" />
			<input type=\"text\" name=\"inp_profile_city\" size=\"68\" value=\"$get_profile_city\" /><br />
			</p>

			<p>
			$l_country:<br />
			<select name=\"inp_profile_country\">
			<option value=\"\""; if($get_profile_country == ""){ echo" selected=\"selected\""; } echo">- $l_please_select -</option>";
			$query = "SELECT language_flag FROM $t_languages ORDER BY language_flag ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_flag) = $row;

				$country = str_replace("_", " ", $get_language_flag);
				$country = ucwords($country);
				if($country != "$prev_country"){
					echo"			";
					echo"<option value=\"$country\""; if($get_profile_country == "$country"){ echo" selected=\"selected\""; } echo">$country</option>\n";
				}
				$prev_country = "$country";
			}
			echo"
			</select>
			</p>

			<p>
			$l_phone:<br />
			<input type=\"text\" name=\"inp_profile_phone\" size=\"78\" value=\"$get_profile_phone\" /><br />
			</p>



			<p>
			$l_work:<br />
			<input type=\"text\" name=\"inp_profile_work\" size=\"78\" value=\"$get_profile_work\" /><br />
			</p>


			<p>
			$l_university:<br />
			<input type=\"text\" name=\"inp_profile_university\" size=\"78\" value=\"$get_profile_university\" /><br />
			</p>

			<p>
			$l_high_school:<br />
			<input type=\"text\" name=\"inp_profile_high_school\" size=\"78\" value=\"$get_profile_high_school\" /><br />
			</p>

			<p>
			$l_languages:<br />
			<input type=\"text\" name=\"inp_profile_languages\" size=\"78\" value=\"$get_profile_languages\" /><br />
			</p>

			<p>
			$l_website:<br />
			<input type=\"text\" name=\"inp_profile_website\" size=\"78\" value=\"$get_profile_website\" /><br />
			</p>


			<p>
			$l_interested_in:<br />";
			$intrested_in_array = explode("|", $get_profile_interested_in);
			echo"
			<input type=\"checkbox\" name=\"inp_interested_in_men\""; if($intrested_in_array[0] == "on"){ echo" checked=\"checked\""; } echo" /> $l_men
			&nbsp;
			<input type=\"checkbox\" name=\"inp_interested_in_women\""; if(isset($intrested_in_array[1]) && $intrested_in_array[1] == "on"){ echo" checked=\"checked\""; } echo" /> $l_women
			</p>


			<p>
			$l_relationship_status:<br />
			<select name=\"inp_profile_relationship\"> 
			<option value=\"\""; if($get_profile_relationship == ""){ echo" selected=\"selected\""; } echo">- $l_please_select -</option>
			<option value=\"single\""; if($get_profile_relationship == "single"){ echo" selected=\"selected\""; } echo">$l_single</option>
			<option value=\"in_a_relationship\""; if($get_profile_relationship == "in_a_relationship"){ echo" selected=\"selected\""; } echo">$l_in_a_relationship</option>
			<option value=\"engaged\""; if($get_profile_relationship == "engaged"){ echo" selected=\"selected\""; } echo">$l_engaged</option>
			<option value=\"married\""; if($get_profile_relationship == "married"){ echo" selected=\"selected\""; } echo">$l_married</option>
			<option value=\"in_a_open_relationship\""; if($get_profile_relationship == "in_a_open_relationship"){ echo" selected=\"selected\""; } echo">$l_in_a_open_relationship</option>
			<option value=\"its_complicated\""; if($get_profile_relationship == "its_complicated"){ echo" selected=\"selected\""; } echo">$l_its_complicated</option>
			<option value=\"seperated\""; if($get_profile_relationship == "seperated"){ echo" selected=\"selected\""; } echo">$l_seperated</option>
			<option value=\"divorced\""; if($get_profile_relationship == "divorced"){ echo" selected=\"selected\""; } echo">$l_divorced</option>
			<option value=\"widow_widower\""; if($get_profile_relationship == "widow_widower"){ echo" selected=\"selected\""; } echo">$l_widow_widower</option>
			</select>
			</p>

			<p>
			$l_about_me:<br />
			<textarea name=\"inp_profile_about_me\" rows=\"5\" cols=\"40\">"; $get_profile_about = str_replace("<br />", "\n", $get_profile_about); echo"$get_profile_about</textarea>
			</p>




			<p>
			<input type=\"submit\" value=\"$l_save\" />
			</p>

			</form>

";

?>