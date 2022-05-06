<?php
/**
*
* File: users/edit_professional.php
* Version 19:21 06.08.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
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
include("$root/_admin/_data/logo.php");
include("$root/_admin/_data/config/user_system.php");
include("$root/_admin/_data/user_professional_allowed_settings.php");

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/users/ts_index.php");

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_edit_professional - $l_my_profile - $l_users";
include("$root/_webdesign/header.php");


/*- Tables ---------------------------------------------------------------------------------- */
$t_users_professional_allowed_companies			= $mysqlPrefixSav . "users_professional_allowed_companies";
$t_users_professional_allowed_company_locations		= $mysqlPrefixSav . "users_professional_allowed_company_locations";
$t_users_professional_allowed_departments		= $mysqlPrefixSav . "users_professional_allowed_departments";
$t_users_professional_allowed_positions			= $mysqlPrefixSav . "users_professional_allowed_positions";
$t_users_professional_allowed_districts			= $mysqlPrefixSav . "users_professional_allowed_districts";

$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";



/*- Content --------------------------------------------------------------------------- */

if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	// Get user
	$user_id = $_SESSION['user_id'];
	$user_id_mysql = quote_smart($link, $user_id);
	$security = $_SESSION['security'];
	$security_mysql = quote_smart($link, $security);

	$query = "SELECT user_id, user_name, user_language, user_rank FROM $t_users WHERE user_id=$user_id_mysql AND user_security=$security_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_name, $get_my_user_language, $get_my_user_rank) = $row;
	if($get_my_user_id == ""){
		echo"<h1>Error</h1><p>Error with user id.</p>"; 
		$_SESSION = array();
		session_destroy();
		die;
	}

	$query = "SELECT profile_id, profile_user_id, profile_first_name, profile_middle_name, profile_last_name, profile_address_line_a, profile_address_line_b, profile_zip, profile_city, profile_country, profile_phone, profile_work, profile_university, profile_high_school, profile_languages, profile_website, profile_interested_in, profile_relationship, profile_about, profile_newsletter FROM $t_users_profile WHERE profile_user_id=$get_my_user_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_profile_id, $get_my_profile_user_id, $get_my_profile_first_name, $get_my_profile_middle_name, $get_my_profile_last_name, $get_my_profile_address_line_a, $get_my_profile_address_line_b, $get_my_profile_zip, $get_my_profile_city, $get_my_profile_country, $get_my_profile_phone, $get_my_profile_work, $get_my_profile_university, $get_my_profile_high_school, $get_my_profile_languages, $get_my_profile_website, $get_my_profile_interested_in, $get_my_profile_relationship, $get_my_profile_about, $get_my_profile_newsletter) = $row;

	$query = "SELECT professional_id, professional_user_id, professional_company, professional_company_location, professional_department, professional_work_email, professional_position, professional_position_abbr, professional_district FROM $t_users_professional WHERE professional_user_id=$user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_professional_id, $get_my_professional_user_id, $get_my_professional_company, $get_my_professional_company_location, $get_my_professional_department, $get_my_professional_work_email, $get_my_professional_position, $get_my_professional_position_abbr, $get_my_professional_district) = $row;
	if($get_my_professional_id == ""){

		// Create professional profile
		mysqli_query($link, "INSERT INTO $t_users_professional 
		(professional_id, professional_user_id) 
		VALUES 
		(NULL, $get_my_user_id)")
		or die(mysqli_error($link));
	}


	if($action == "save"){

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

		$inp_position_abbr = "";
		if($configUsersCanOnlyUseAllowedPositionsSav == "0"){
			$inp_position_abbr = $_POST['inp_position_abbr'];
			$inp_position_abbr = output_html($inp_position_abbr);
		}
		else{
		
			$query = "SELECT allowed_position_id, allowed_position_title, allowed_position_title_clean, allowed_position_title_abbr, allowed_position_title_abbr_clean FROM $t_users_professional_allowed_positions WHERE allowed_position_title=$inp_position_mysql ";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_row_id, $get_current_title, $get_current_title_clean, $get_current_title_abbr, $get_current_title_abbr_clean) = $row;
			if($get_current_row_id != ""){
				$inp_position_abbr = "$get_current_title_abbr";

			}
		}
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
			
		// Get new information
		$query = "SELECT professional_id, professional_user_id, professional_company, professional_company_location, professional_department, professional_work_email, professional_position, professional_position_abbr, professional_district FROM $t_users_professional WHERE professional_user_id=$user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_professional_id, $get_my_professional_user_id, $get_my_professional_company, $get_my_professional_company_location, $get_my_professional_department, $get_my_professional_work_email, $get_my_professional_position, $get_my_professional_position_abbr, $get_my_professional_district) = $row;


		// Search engine
		if($configShowUsersOnSearchEngineIndexSav == "1"){
			$inp_index_title = "$get_my_user_name";
			if($configIncludeFirstNameLastNameOnSearchEngineIndexSav == "1"){
				if($get_my_profile_first_name != "" OR  $get_my_profile_middle_name != "" OR $get_my_profile_last_name != ""){
					$inp_index_title = $inp_index_title . " | $get_my_profile_first_name $get_my_profile_middle_name $inp_profile_last_name";
				}
			}
			if($configIncludeProfessionalOnSearchEngineIndexSav == "1"){
				if($get_my_professional_company != ""){
					$inp_index_title = $inp_index_title . " | $get_my_professional_company";
				}
				if($get_my_professional_company_location != ""){
					$inp_index_title = $inp_index_title . " | $get_my_professional_company_location";
				}
				if($get_my_professional_department != ""){
					$inp_index_title = $inp_index_title . " | $get_my_professional_department";
				}
				if($get_my_professional_position_abbr != ""){
					$inp_index_title = $inp_index_title . " | $get_my_professional_position_abbr";
				}
				if($get_my_professional_district != ""){
					$inp_index_title = $inp_index_title . " | $get_my_professional_district";
				}
			}
			$inp_index_title = $inp_index_title . " | $l_users";
			$inp_index_title_mysql = quote_smart($link, $inp_index_title);


			$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name='users' AND index_reference_name='user_id' AND index_reference_id=$get_my_user_id";
			$result_exists = mysqli_query($link, $query_exists);
			$row_exists = mysqli_fetch_row($result_exists);
			list($get_index_id) = $row_exists;
			if($get_index_id != ""){
				$result = mysqli_query($link, "UPDATE $t_search_engine_index SET 
								index_title=$inp_index_title_mysql 
								WHERE index_id=$get_index_id") or die(mysqli_error($link));
			}
		} // search engine


		$url = "edit_professional.php?l=$l&ft=success&fm=changes_saved"; 
		if($process == "1"){
			header("Location: $url");
		}
		else{
			echo"<meta http-equiv=\"refresh\" content=\"1;url=$url\">";
		}
		exit;
	}
	if($action == ""){
		echo"
		<h1>$l_professional</h1>

		<!-- You are here -->
			<div class=\"you_are_here\">
				<p>
				<b>$l_you_are_here:</b><br />
				<a href=\"my_profile.php?l=$l\">$l_my_profile</a>
				&gt; 
				<a href=\"edit_professional.php?l=$l\">$l_edit_professional</a>
				</p>
			</div>
		<!-- //You are here -->

		<form method=\"POST\" action=\"edit_professional.php?action=save&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\" name=\"nameform\">

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
		$l_company:\n";
		if($configUsersCanOnlyUseAllowedCompaniesSav == "0"){
			echo"<br />		<input type=\"text\" name=\"inp_company\" size=\"25\" value=\"$get_my_professional_company\" />\n";
		}
		else{
			echo"<br />
			<select name=\"inp_company\">
				<option value=\"\""; if($get_my_professional_company == ""){ echo" selected=\"selected\""; } echo">-</option>\n";

			$query = "SELECT allowed_company_id, allowed_company_title, allowed_company_title_clean FROM $t_users_professional_allowed_companies ORDER BY allowed_company_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_row_id, $get_title, $get_title_clean) = $row;
				echo"				";
				echo"<option value=\"$get_title\""; if($get_my_professional_company == "$get_title"){ echo" selected=\"selected\""; } echo">$get_title</option>\n";
			}
			echo"
			</select>
			
			";
		}
		echo"
		</p>

		<p>
		$l_company_location:\n";
		if($configUsersCanOnlyUseAllowedCompaniesSav == "0"){
			echo"<br />		<input type=\"text\" name=\"inp_company_location\" size=\"25\" value=\"$get_my_professional_company_location\" />\n";
		}
		else{
			echo"<br />
			<select name=\"inp_company_location\">
				<option value=\"\""; if($get_my_professional_company_location == ""){ echo" selected=\"selected\""; } echo">-</option>\n";

			$query = "SELECT allowed_company_location_id, allowed_company_location_title, allowed_company_location_title_clean FROM $t_users_professional_allowed_company_locations ORDER BY allowed_company_location_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_row_id, $get_title,  $get_title_clean) = $row;
				echo"				";
				echo"<option value=\"$get_title\""; if($get_my_professional_company_location == "$get_title"){ echo" selected=\"selected\""; } echo">$get_title</option>\n";
			}
			echo"
			</select>
			";
		}
		echo"
		</p>

		<p>
		$l_department\n";
		if($configUsersCanOnlyUseAllowedDepartmentsSav == "0"){
			echo"<br />		<input type=\"text\" name=\"inp_department\" size=\"25\" value=\"$get_my_professional_department\" />\n";
		}
		else{
			echo"<br />
			<select name=\"inp_department\">
				<option value=\"\""; if($get_my_professional_department == ""){ echo" selected=\"selected\""; } echo">-</option>\n";

			
			$query = "SELECT allowed_department_id, allowed_department_title, allowed_department_title_clean FROM $t_users_professional_allowed_departments ORDER BY allowed_department_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_row_id, $get_title,  $get_title_clean) = $row;
				echo"				";
				echo"<option value=\"$get_title\""; if($get_my_professional_department == "$get_title"){ echo" selected=\"selected\""; } echo">$get_title</option>\n";
			}
			echo"
			</select>
			
			";
		}
		echo"
		</p>

		<p>
		$l_work_email:<br />
		<input type=\"text\" name=\"inp_work_email\" size=\"25\" value=\"$get_my_professional_work_email\" /><br />
		</p>

		\n";
		if($configUsersCanOnlyUseAllowedPositionsSav == "0"){
			echo"
			<p>
			$l_position:<br />
			<input type=\"text\" name=\"inp_position\" size=\"25\" value=\"$get_my_professional_position\" />
			</p>

			<p>
			$l_position_abbreviation:<br />
			<input type=\"text\" name=\"inp_position_abbr\" size=\"25\" value=\"$get_my_professional_position_abbr\" />
			</p>";
		}
		else{
			echo"
			<p>$l_position:<br />
			<select name=\"inp_position\">
				<option value=\"\""; if($get_my_professional_position == ""){ echo" selected=\"selected\""; } echo">-</option>\n";

		
			$query = "SELECT allowed_position_id, allowed_position_title, allowed_position_title_clean, allowed_position_title_abbr, allowed_position_title_abbr_clean FROM $t_users_professional_allowed_positions ORDER BY allowed_position_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_row_id, $get_title,  $get_title_clean, $get_title_abbr, $get_title_abbr_clean) = $row;
				echo"				";
				echo"<option value=\"$get_title\""; if($get_my_professional_position == "$get_title"){ echo" selected=\"selected\""; } echo">$get_title</option>\n";
			}
			echo"
			</select>
			<input type=\"hidden\" name=\"inp_position_abbr\" value=\"$get_my_professional_position_abbr\" />
			</p>
			";
		}
		echo"


		<p>
		$l_district:\n";
		if($configUsersCanOnlyUseAllowedDistrictsSav == "0"){
			echo"<br />		<input type=\"text\" name=\"inp_district\" size=\"25\" value=\"$get_my_professional_district\" />\n";
		}
		else{
			echo"<br />
			<select name=\"inp_district\">
				<option value=\"\""; if($get_my_professional_district == ""){ echo" selected=\"selected\""; } echo">-</option>\n";

			
			$query = "SELECT allowed_district_id, allowed_district_title, allowed_district_title_clean, allowed_district_title_abbr, allowed_district_title_abbr_clean FROM $t_users_professional_allowed_districts ORDER BY allowed_district_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_row_id, $get_title, $get_title_clean, $get_title_abbr, $get_title_abbr_clean) = $row;

				echo"				";
				echo"<option value=\"$get_title\""; if($get_my_professional_district == "$get_title"){ echo" selected=\"selected\""; } echo">$get_title</option>\n";
			}
			echo"
			</select>
			
			";
		}
		echo"
		</p>

		<p>
		<input type=\"submit\" value=\"$l_save\" class=\"btn\" />
		</p>

		</form>

		";
	}
}
else{
	echo"
	<table>
	 <tr> 
	  <td style=\"padding-right: 6px;\">
		<p>
		<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"Loading\" />
		</p>
	  </td>
	  <td>
		<h1>Loading</h1>
	  </td>
	 </tr>
	</table>
		
	<meta http-equiv=\"refresh\" content=\"1;url=index.php\">
	";
}
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>