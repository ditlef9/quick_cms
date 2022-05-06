<?php
/**
*
* File: users/my_profile_edit_user.php
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

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/users/ts_index.php");

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_edit_user - $l_my_profile - $l_users";
include("$root/_webdesign/header.php");



/*- Content --------------------------------------------------------------------------- */


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

	if($action == "save"){
		
		// Name
		$inp_user_alias = $_POST['inp_user_alias'];
		$inp_user_alias = output_html($inp_user_alias);
		$inp_user_alias = ucfirst($inp_user_alias);
		$inp_user_alias = substr($inp_user_alias, 0, 20);
		$inp_user_alias_lower = strtolower($inp_user_alias);
		$inp_user_alias_mysql = quote_smart($link, $inp_user_alias);
		if($inp_user_alias != "$get_my_user_alias"){
			
			if(empty($inp_user_alias)){
				$fm_alias = "users_please_enter_a_alias";
			}
			else{
				// Is the alias taken?
				if($inp_user_alias_lower != "$get_my_user_alias"){
					$query = "SELECT user_id FROM $t_users WHERE user_alias=$inp_user_alias_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_check_user_id) = $row;
					if($get_check_user_id != ""){
						$fm_alias = "user_alias_taken";
					}
					else{
						// Update alias
						$result = mysqli_query($link, "UPDATE $t_users SET user_alias=$inp_user_alias_mysql WHERE user_id=$user_id_mysql");
					}
				}
			}
		}

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


		// Personal
		$inp_user_gender = $_POST['inp_user_gender'];
		$inp_user_gender = output_html($inp_user_gender);
		$inp_user_gender_mysql = quote_smart($link, $inp_user_gender);

		// Personal :: Dob
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
			$result = mysqli_query($link, "UPDATE $t_users SET user_dob=$inp_user_dob_mysql WHERE user_id=$user_id_mysql");
		}

		// Update
		$result = mysqli_query($link, "UPDATE $t_users SET 
						user_first_name=$inp_user_first_name_mysql, 
						user_middle_name=$inp_user_middle_name_mysql, 
						user_last_name=$inp_user_last_name_mysql, 
						user_country_id=$inp_user_country_id_mysql, 
						user_country_name=$inp_user_country_name_mysql, 
						user_city_name=$inp_user_city_name_mysql, 
						user_gender=$inp_user_gender_mysql
						WHERE user_id=$get_my_user_id") or die(mysqli_error($link));


		

		$url = "my_profile_edit_user.php?l=$l&ft=success&fm=changes_saved";
		if(isset($fm_alias)){
			$url = $url . "&fm_alias=$fm_alias";
		}
		header("Location: $url");
		exit;
	}
	if($action == ""){
		echo"
		<h1>$l_profile</h1>

		<!-- You are here -->
			<div class=\"you_are_here\">
				<p>
				<b>$l_you_are_here:</b><br />
				<a href=\"my_profile.php?l=$l\">$l_my_profile</a>
				&gt; 
				<a href=\"my_profile_edit_user.php?l=$l\">$l_edit_user</a>
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
					$fm = "$ft";
				}

				
				if(isset($_GET['fm_alias'])) {
					$fm_alias = $_GET['fm_alias'];
					$fm_alias = strip_tags(stripslashes($fm_alias));
					if($fm_alias == "users_please_enter_a_alias"){
						$fm = $fm . "<br /><br /><b>$l_users_please_enter_a_alias</b>";
					}
					else{
						$fm = $fm . "<br /><br /><b>$l_user_alias_taken</b>";
					}

				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->


		<!-- Edit profile form -->
			<form method=\"POST\" action=\"my_profile_edit_user.php?action=save&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\" name=\"nameform\">

			<!-- Name -->
				<hr />
				<h2>$l_name</h2>

				<p>$l_alias:<br />
				<input type=\"text\" name=\"inp_user_alias\" size=\"25\" style=\"width: 99%;\" value=\"$get_my_user_alias\" /><br />
				</p>

				<p>$l_first_name:<br />
				<input type=\"text\" name=\"inp_user_first_name\" size=\"25\" style=\"width: 99%;\" value=\"$get_my_user_first_name\" />
				</p>

				<p>$l_middle_name:<br />
				<input type=\"text\" name=\"inp_user_middle_name\" size=\"25\" style=\"width: 99%;\" value=\"$get_my_user_middle_name\" />
				</p>

				<p>$l_last_name:<br />
				<input type=\"text\" name=\"inp_user_last_name\" size=\"25\" style=\"width: 99%;\" value=\"$get_my_user_last_name\" />
				</p>
			<!-- //Name -->

			<!-- Localization -->
				<hr />
				<h2>$l_localization</h2>


				<p>$l_country:<br />
				<select name=\"inp_user_country\">
					<option value=\"-\">- Please select -</option>\n";
					$query = "SELECT country_id, country_name FROM $t_languages_countries ORDER BY country_name ASC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_country_id, $get_country_name) = $row;
						echo"			";
						echo"<option value=\"$get_country_name\""; if($get_country_name == "$get_my_user_country_name"){ echo" selected=\"selected\""; } echo">$get_country_name</option>\n";
					}
					echo"
					</select>
					</p>
	
				<p>$l_city:<br />
				<input type=\"text\" name=\"inp_user_city_name\" size=\"25\" style=\"width: 99%;\" value=\"$get_my_user_city_name\" />
				</p>
			<!-- //Localization -->
			

			<!-- Personal -->
				<hr />
				<h2>$l_personal</h2>

				<p>$l_gender:<br />
				<select name=\"inp_user_gender\"> 
					<option value=\"\""; if($get_my_user_gender == ""){ echo" selected=\"selected\""; } echo">- $l_please_select -</option>
					<option value=\"male\""; if($get_my_user_gender == "male"){ echo" selected=\"selected\""; } echo">$l_male</option>
					<option value=\"female\""; if($get_my_user_gender == "female"){ echo" selected=\"selected\""; } echo">$l_female</option>
				</select>
				</p>


				<p>
				$l_birthday:<br />";
				$dob_array = explode("-", $get_my_user_dob);
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
					$l_month_array[1] = "$l_month_january";
					$l_month_array[2] = "$l_month_february";
					$l_month_array[3] = "$l_month_march";
					$l_month_array[4] = "$l_month_april";
					$l_month_array[5] = "$l_month_may";
					$l_month_array[6] = "$l_month_june";
					$l_month_array[7] = "$l_month_juli";
					$l_month_array[8] = "$l_month_august";
					$l_month_array[9] = "$l_month_september";
					$l_month_array[10] = "$l_month_october";
					$l_month_array[11] = "$l_month_november";
					$l_month_array[12] = "$l_month_december";
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


			<p>
			<input type=\"submit\" value=\"$l_save\" class=\"btn\" />
			</p>

			</form>
		<!-- //Edit profile form -->

		";
	}
}
else{
	echo"
	<table>
	 <tr> 
	  <td style=\"padding-right: 6px;\">
		<p>
		<img src=\"_gfx/loading_22.gif\" alt=\"Loading\" />
		</p>
	  </td>
	  <td>
		<h1>Loading</h1>
	  </td>
	 </tr>
	</table>
	<p>Please log in.</p>
	<meta http-equiv=\"refresh\" content=\"1;url=login.php?l=$l&referer=edit_profile.php\">
	";
}
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>