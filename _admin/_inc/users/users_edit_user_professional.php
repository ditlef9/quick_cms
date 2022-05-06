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

if($mode == "save"){

		$inp_company = $_POST['inp_company'];
		$inp_company = output_html($inp_company);
		$inp_company_mysql = quote_smart($link, $inp_company);

		$inp_company_location = $_POST['inp_company_location'];
		$inp_company_location = output_html($inp_company_location);
		$inp_company_location_mysql = quote_smart($link, $inp_company_location);

		$inp_department = $_POST['inp_department'];
		$inp_department = output_html($inp_department);
		$inp_department_mysql = quote_smart($link, $inp_department);

		$inp_work_email = $_POST['inp_work_email'];
		$inp_work_email = output_html($inp_work_email);
		$inp_work_email_mysql = quote_smart($link, $inp_work_email);

		$inp_position = $_POST['inp_position'];
		$inp_position = output_html($inp_position);
		$inp_position_mysql = quote_smart($link, $inp_position);


		$inp_position_abbr = $_POST['inp_position_abbr'];
		$inp_position_abbr = output_html($inp_position_abbr);
		$inp_position_abbr_mysql = quote_smart($link, $inp_position_abbr);


		$inp_district = $_POST['inp_district'];
		$inp_district = output_html($inp_district);
		$inp_district_mysql = quote_smart($link, $inp_district);



		$result = mysqli_query($link, "UPDATE $t_users_professional SET 
					professional_company=$inp_company_mysql, 
					professional_company_location=$inp_company_location_mysql, 
					professional_department=$inp_department_mysql, 
					professional_work_email=$inp_work_email_mysql, 
					professional_position=$inp_position_mysql,
					professional_position_abbr=$inp_position_abbr_mysql,
 					professional_district=$inp_district_mysql 
					WHERE professional_user_id=$user_id_mysql");


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


				// Send success
				$fm = "changes_saved";
				$ft = "success";
				
				// Get new information
				$query = "SELECT professional_id, professional_user_id, professional_company, professional_company_location, professional_department, professional_work_email, professional_position, professional_position_abbr, professional_district FROM $t_users_professional WHERE professional_user_id=$get_user_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_professional_id, $get_professional_user_id, $get_professional_company, $get_professional_company_location, $get_professional_department, $get_professional_work_email, $get_professional_position, $get_professional_position_abbr, $get_professional_district) = $row;


}
echo"
<h1>$l_edit $get_user_name</h1>

<!-- Menu -->
	";
	include("_inc/users/users_edit_user_menu.php");
	echo"
<!-- //Menu -->

<!-- Edit -->
	<form method=\"POST\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;mode=save&amp;user_id=$user_id&amp;l=$l&amp;editor_language=$editor_language\" enctype=\"multipart/form-data\" name=\"nameform\">
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


		<p>
		Company:\n";
		if($configUsersCanOnlyUseAllowedCompaniesSav == "0"){
			echo"<br />		<input type=\"text\" name=\"inp_company\" size=\"25\" value=\"$get_professional_company\" />\n";
		}
		else{
			echo"(<a href=\"index.php?open=users&amp;page=professional_allowed_companies&amp;editor_language=$editor_language&amp;l=$l\">edit</a>)<br />
			<select name=\"inp_company\">
				<option value=\"\""; if($get_professional_company == ""){ echo" selected=\"selected\""; } echo">-</option>\n";

			$query = "SELECT allowed_company_id, allowed_company_title, allowed_company_title_clean FROM $t_users_professional_allowed_companies ORDER BY allowed_company_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_row_id, $get_title, $get_title_clean) = $row;
				echo"				";
				echo"<option value=\"$get_title\""; if($get_professional_company == "$get_title"){ echo" selected=\"selected\""; } echo">$get_title</option>\n";
			}
			echo"
			</select>
			
			";
		}
		echo"
		</p>

		<p>
		Company location:\n";
		if($configUsersCanOnlyUseAllowedCompanyLocationsSav == "0"){
			echo"<br />		<input type=\"text\" name=\"inp_company_location\" size=\"25\" value=\"$get_professional_company_location\" />\n";
		}
		else{
			echo"(<a href=\"index.php?open=users&amp;page=professional_allowed_company_locations&amp;editor_language=$editor_language&amp;l=$l\">edit</a>)<br />
			<select name=\"inp_company_location\">
				<option value=\"\""; if($get_professional_company_location == ""){ echo" selected=\"selected\""; } echo">-</option>\n";

			$query = "SELECT allowed_company_location_id, allowed_company_location_title, allowed_company_location_title_clean FROM $t_users_professional_allowed_company_locations ORDER BY allowed_company_location_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_row_id, $get_title,  $get_title_clean) = $row;
				echo"				";
				echo"<option value=\"$get_title\""; if($get_professional_company_location == "$get_title"){ echo" selected=\"selected\""; } echo">$get_title</option>\n";
			}
			echo"
			</select>
			";
		}
		echo"
		</p>

		<p>
		Department:\n";
		if($configUsersCanOnlyUseAllowedDepartmentsSav == "0"){
			echo"<br />		<input type=\"text\" name=\"inp_department\" size=\"25\" value=\"$get_professional_department\" />\n";
		}
		else{
			echo"(<a href=\"index.php?open=users&amp;page=professional_allowed_departments&amp;editor_language=$editor_language&amp;l=$l\">edit</a>)<br />
			<select name=\"inp_department\">
				<option value=\"\""; if($get_professional_department == ""){ echo" selected=\"selected\""; } echo">-</option>\n";

			
			$query = "SELECT allowed_department_id, allowed_department_title, allowed_department_title_clean FROM $t_users_professional_allowed_departments ORDER BY allowed_department_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_row_id, $get_title,  $get_title_clean) = $row;
				echo"				";
				echo"<option value=\"$get_title\""; if($get_professional_department == "$get_title"){ echo" selected=\"selected\""; } echo">$get_title</option>\n";
			}
			echo"
			</select>
			
			";
		}
		echo"
		<br />
		</p>

		<p>
		Work email:<br />
		<input type=\"text\" name=\"inp_work_email\" size=\"25\" value=\"$get_professional_work_email\" /><br />
		</p>

		<p>
		Position:\n";
		if($configUsersCanOnlyUseAllowedPositionsSav == "0"){
			echo"<br />		<input type=\"text\" name=\"inp_position\" size=\"25\" value=\"$get_professional_position\" />\n";
		}
		else{
			echo"(<a href=\"index.php?open=users&amp;page=professional_allowed_positions&amp;editor_language=$editor_language&amp;l=$l\">edit</a>)<br />
			<select name=\"inp_position\">
				<option value=\"\""; if($get_professional_position == ""){ echo" selected=\"selected\""; } echo">-</option>\n";

		
			$query = "SELECT allowed_position_id, allowed_position_title, allowed_position_title_clean, allowed_position_title_abbr, allowed_position_title_abbr_clean FROM $t_users_professional_allowed_positions ORDER BY allowed_position_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_row_id, $get_title,  $get_title_clean, $get_title_abbr, $get_title_abbr_clean) = $row;
				echo"				";
				echo"<option value=\"$get_title\""; if($get_professional_position == "$get_title"){ echo" selected=\"selected\""; } echo">$get_title</option>\n";
			}
			echo"
			</select>
			
			";
		}
		echo"
		<br />
		</p>

		<p>
		Position abbreviation:\n";
		if($configUsersCanOnlyUseAllowedPositionsSav == "0"){
			echo"<br />		<input type=\"text\" name=\"inp_position_abbr\" size=\"25\" value=\"$get_professional_position_abbr\" />\n";
		}
		else{
			echo"(<a href=\"index.php?open=users&amp;page=professional_allowed_positions&amp;editor_language=$editor_language&amp;l=$l\">edit</a>)<br />
			<select name=\"inp_position_abbr\">
				<option value=\"\""; if($get_professional_position_abbr == ""){ echo" selected=\"selected\""; } echo">-</option>\n";

		
			$query = "SELECT allowed_position_id, allowed_position_title, allowed_position_title_clean, allowed_position_title_abbr, allowed_position_title_abbr_clean FROM $t_users_professional_allowed_positions ORDER BY allowed_position_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_row_id, $get_title,  $get_title_clean, $get_title_abbr, $get_title_abbr_clean) = $row;
				echo"				";
				echo"<option value=\"$get_title_abbr\""; if($get_professional_position_abbr == "$get_title_abbr"){ echo" selected=\"selected\""; } echo">$get_title_abbr</option>\n";
			}
			echo"
			</select>
			";
		}
		echo"
		</p>

		<p>
		District:\n";
		if($configUsersCanOnlyUseAllowedDistrictsSav == "0"){
			echo"<br />		<input type=\"text\" name=\"inp_district\" size=\"25\" value=\"$get_professional_district\" />\n";
		}
		else{
			echo"(<a href=\"index.php?open=users&amp;page=professional_allowed_districts&amp;editor_language=$editor_language&amp;l=$l\">edit</a>)<br />
			<select name=\"inp_district\">
				<option value=\"\""; if($get_professional_district == ""){ echo" selected=\"selected\""; } echo">-</option>\n";

			
			$query = "SELECT allowed_district_id, allowed_district_title, allowed_district_title_clean, allowed_district_title_abbr, allowed_district_title_abbr_clean FROM $t_users_professional_allowed_districts ORDER BY allowed_district_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_row_id, $get_title, $get_title_clean, $get_title_abbr, $get_title_abbr_clean) = $row;

				echo"				";
				echo"<option value=\"$get_title\""; if($get_professional_district == "$get_title"){ echo" selected=\"selected\""; } echo">$get_title</option>\n";
			}
			echo"
			</select>
			
			";
		}
		echo"
		<br />
		</p>

			<p>
			<input type=\"submit\" value=\"$l_save\" class=\"btn_default\" />
			</p>

			</form>

";

?>