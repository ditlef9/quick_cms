<?php
/**
*
* File: users/index.php
* Version 17.46 18.02.2017
* Copyright (c) 2009-2017 Sindre Andre Ditlefsen
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

/*- Tables ---------------------------------------------------------------------------------- */
include("_tables_users.php");


$t_users_professional_allowed_companies			= $mysqlPrefixSav . "users_professional_allowed_companies";
$t_users_professional_allowed_company_locations		= $mysqlPrefixSav . "users_professional_allowed_company_locations";
$t_users_professional_allowed_departments		= $mysqlPrefixSav . "users_professional_allowed_departments";
$t_users_professional_allowed_positions			= $mysqlPrefixSav . "users_professional_allowed_positions";
$t_users_professional_allowed_districts			= $mysqlPrefixSav . "users_professional_allowed_districts";

$t_users_professional 		= $mysqlPrefixSav . "users_professional";

$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/users/ts_index.php");

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_edit_address - $l_users";
include("$root/_webdesign/header.php");



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
	list($get_user_id, $get_user_name, $get_user_language, $get_user_rank) = $row;

	$query = "SELECT profile_id, profile_user_id, profile_first_name, profile_middle_name, profile_last_name, profile_address_line_a, profile_address_line_b, profile_zip, profile_city, profile_country, profile_phone, profile_work, profile_university, profile_high_school, profile_languages, profile_website, profile_interested_in, profile_relationship, profile_about, profile_newsletter FROM $t_users_profile WHERE profile_user_id=$user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_profile_id, $get_profile_user_id, $get_profile_first_name, $get_profile_middle_name, $get_profile_last_name, $get_profile_address_line_a, $get_profile_address_line_b, $get_profile_zip, $get_profile_city, $get_profile_country, $get_profile_phone, $get_profile_work, $get_profile_university, $get_profile_high_school, $get_profile_languages, $get_profile_website, $get_profile_interested_in, $get_profile_relationship, $get_profile_about, $get_profile_newsletter) = $row;

	if($get_user_id == ""){
		echo"<h1>Error</h1><p>Error with user id.</p>"; 
		$_SESSION = array();
		session_destroy();
		die;
	}

	$query = "SELECT professional_id, professional_user_id, professional_company, professional_company_location, professional_department, professional_work_email, professional_position, professional_position_abbr, professional_district FROM $t_users_professional WHERE professional_user_id=$get_user_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_professional_id, $get_my_professional_user_id, $get_my_professional_company, $get_my_professional_company_location, $get_my_professional_department, $get_my_professional_work_email, $get_my_professional_position, $get_my_professional_position_abbr, $get_my_professional_district) = $row;
	if($get_my_professional_id == ""){
		// Create professional profile
		mysqli_query($link, "INSERT INTO $t_users_professional 
		(professional_id, professional_user_id) 
		VALUES 
		(NULL, $get_user_id)")
		or die(mysqli_error($link));
	}




	if($action == "save"){

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

		$result = mysqli_query($link, "UPDATE $t_users_profile SET profile_first_name=$inp_profile_first_name_mysql, profile_middle_name=$inp_profile_middle_name_mysql, profile_last_name=$inp_profile_last_name_mysql, profile_address_line_a=$inp_profile_address_line_a_mysql, profile_address_line_b=$inp_profile_address_line_b_mysql, profile_zip=$inp_profile_zip_mysql, profile_city=$inp_profile_city_mysql, profile_country=$inp_profile_country_mysql, profile_phone=$inp_profile_phone_mysql WHERE profile_user_id=$user_id_mysql");
		

		// Search engine
		if($configShowUsersOnSearchEngineIndexSav == "1"){
			$inp_index_title = "$get_user_name";
			if($configIncludeFirstNameLastNameOnSearchEngineIndexSav == "1"){
				if($inp_profile_first_name != "" OR  $inp_profile_middle_name != "" OR $inp_profile_last_name != ""){
					$inp_index_title = $inp_index_title . " | $inp_profile_first_name $inp_profile_middle_name $inp_profile_last_name";
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

		$url = "edit_address.php?l=$l&ft=success&fm=changes_saved"; 
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
		<h1>$l_edit_address</h1>

		<!-- You are here -->
			<div class=\"you_are_here\">
				<p>
				<b>$l_you_are_here:</b><br />
				<a href=\"my_profile.php?l=$l\">$l_my_profile</a>
				&gt; 
				<a href=\"edit_address.php?l=$l\">$l_edit_address</a>
				</p>
			</div>
		<!-- //You are here -->

		<form method=\"POST\" action=\"edit_address.php?action=save&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\" name=\"nameform\">

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
		<input type=\"text\" name=\"inp_profile_first_name\" size=\"40\" value=\"$get_profile_first_name\" /><br />
		</p>

		<p>
		$l_middle_name:<br />
		<input type=\"text\" name=\"inp_profile_middle_name\" size=\"40\" value=\"$get_profile_middle_name\" /><br />
		</p>

		<p>
		$l_last_name:<br />
		<input type=\"text\" name=\"inp_profile_last_name\" size=\"40\" value=\"$get_profile_last_name\" /><br />
		</p>

		<p>
		$l_address_line_a:<br />
		<input type=\"text\" name=\"inp_profile_address_line_a\" size=\"40\" value=\"$get_profile_address_line_a\" /><br />
		</p>

		<p>
		$l_address_line_b:<br />
		<input type=\"text\" name=\"inp_profile_address_line_b\" size=\"40\" value=\"$get_profile_address_line_b\" /><br />
		</p>

		<p>
		$l_zip_and_city:<br />
		<input type=\"text\" name=\"inp_profile_zip\" size=\"5\" value=\"$get_profile_zip\" />
		<input type=\"text\" name=\"inp_profile_city\" size=\"28\" value=\"$get_profile_city\" /><br />
		</p>

		<p>
		$l_country:<br />
		<select name=\"inp_profile_country\">
			<option value=\"\""; if($get_profile_country == ""){ echo" selected=\"selected\""; } echo">- $l_please_select -</option>";


			
			$query = "SELECT country_id, country_name FROM $t_languages_countries ORDER BY country_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_country_id, $get_country_name) = $row;

				echo"			";
				echo"<option value=\"$get_country_name\""; if($get_profile_country == "$get_country_name"){ echo" selected=\"selected\""; } echo">$get_country_name</option>\n";
			}


		echo"
		</select>
		</p>



		<p>
		$l_phone:<br />
		<input type=\"text\" name=\"inp_profile_phone\" size=\"40\" value=\"$get_profile_phone\" /><br />
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