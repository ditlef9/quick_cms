<?php 
/**
*
* File: meal_plans/new_meal_plan.php
* Version 1.0.0
* Date 14:20 19.01.2020
* Copyright (c) 2011-2020 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration --------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

/*- Root dir -------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config -------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Tables ---------------------------------------------------------------------------- */
include("_tables_meal_plans.php");


/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";


/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);

if(isset($_GET['action'])) {
	$action = $_GET['action'];
	$action = strip_tags(stripslashes($action));
}
else{
	$action = "";
}
if (isset($_GET['inp_height_feet'])) {
	$inp_height_feet = $_GET['inp_height_feet'];
	$inp_height_feet = stripslashes(strip_tags($inp_height_feet));
	$inp_height_feet = str_replace(",", ".", $inp_height_feet);
	if(!(is_numeric($inp_height_feet))){
		$inp_height_feet = "";
	}
	else{
		$inp_height_feet = substr($inp_height_feet, 0, 3);
	}
}
else{
	$inp_height_feet = "";
}
if (isset($_GET['inp_height_inches'])) {
	$inp_height_inches = $_GET['inp_height_inches'];
	$inp_height_inches = stripslashes(strip_tags($inp_height_inches));
	$inp_height_inches = str_replace(",", ".", $inp_height_inches);
	if(!(is_numeric($inp_height_inches))){
		$inp_height_inches = "";
	}
	else{
		$inp_height_inches = substr($inp_height_inches, 0, 3);
	}
}
else{
	$inp_height_inches = "";
}
if (isset($_GET['inp_height_cm'])) {
	$inp_height_cm = $_GET['inp_height_cm'];
	$inp_height_cm = stripslashes(strip_tags($inp_height_cm));
	$inp_height_cm = str_replace(",", ".", $inp_height_cm);
	if(!(is_numeric($inp_height_cm))){
		$inp_height_cm = "";
	}
	else{
		$inp_height_cm = substr($inp_height_cm, 0, 3);
	}
}
else{
	$inp_height_cm = "";
}
if (isset($_GET['inp_mass'])) {
	$inp_mass = $_GET['inp_mass'];
	$inp_mass = stripslashes(strip_tags($inp_mass));
	$inp_mass = str_replace(",", ".", $inp_mass);
	if(!(is_numeric($inp_mass))){
		$inp_mass = "";
	}
	else{
		$inp_mass = substr($inp_mass, 0, 4);
	}
}
else{
	$inp_mass = "";
}

if (isset($_GET['inp_age'])) {
	$inp_age = $_GET['inp_age'];
	$inp_age = stripslashes(strip_tags($inp_age));
	$inp_age = str_replace(",", ".", $inp_age);
	if(!(is_numeric($inp_age))){
		$inp_age = "";
	}
	else{
		$inp_age = substr($inp_age, 0, 3);
	}
}
else{
	$inp_age = "";
}

if (isset($_GET['inp_gender'])) {
	$inp_gender = $_GET['inp_gender'];
	if($inp_gender != "male"){
		$inp_gender = "female";
	}
}
else{
	$inp_gender = "";
}

if (isset($_GET['inp_purpose'])) {
	$inp_purpose = $_GET['inp_purpose'];
	$inp_purpose = stripslashes(strip_tags($inp_purpose));
}
else{
	$inp_purpose = "";
}
if (isset($_GET['inp_measurment'])) {
	$inp_measurment = $_GET['inp_measurment'];
	$inp_measurment = stripslashes(strip_tags($inp_measurment));
}
else{
	$inp_measurment = "";
}

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_new_meal_plan - $l_meal_plans";
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */
// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	// Get my user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_alias, user_email, user_gender, user_height, user_measurement, user_dob FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_alias, $get_my_user_email, $get_my_user_gender, $get_my_user_height, $get_my_user_measurement, $get_my_user_dob) = $row;

	if($action == ""){
		echo"
		<h1>$l_new_meal_plan</h1>
		
		<form method=\"get\" action=\"new_meal_plan.php\" enctype=\"multipart/form-data\">
		
		<table>
		 <tr>
		  <td style=\"text-align: right;padding-right: 4px;\">
			<p>
			$l_height:
			</p>
		  </td>
		  <td>";
			if($get_my_user_measurement == "imperial"){
				echo"
				<script>
					\$(document).ready(function(){
						\$('[name=\"inp_height_feet\"]').focus();
					});
				</script>
				<p>
				<input type=\"text\" name=\"inp_height_feet\" value=\"$inp_height_feet\" size=\"3\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> $l_feet_lowercase
				<input type=\"text\" name=\"inp_height_inches\" value=\"$inp_height_inches\" size=\"3\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> $l_inches_lowercase (<a href=\"new_meal_plan.php?action=switch_measurment&amp;l=$l&amp;process=1\">$l_change_to_cm</a>)
				</p>
				";
			}
			else{
				echo"
				<script>
					\$(document).ready(function(){
						\$('[name=\"inp_height_cm\"]').focus();
					});
				</script>
				<p>
				<input type=\"text\" name=\"inp_height_cm\" value=\"$inp_height_cm\" size=\"3\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> cm (<a href=\"new_meal_plan.php?action=switch_measurment&amp;l=$l&amp;process=1\">$l_change_to_feet_and_inches</a>)
				</p>
				";
			}
			echo"
		  </td>	
		 </tr>
		 <tr>
		  <td style=\"text-align: right;padding-right: 4px;\">
			<p>
			$l_weight:
			</p>
		  </td>
		  <td>
			<p>
			<input type=\"text\" name=\"inp_mass\" value=\"$inp_mass\" size=\"3\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> ";
			if($get_my_user_measurement == "imperial"){
				echo"$l_lbs_lowercase";
			}
			else{
				echo"$l_kg_lowercase";
			}
			echo"
			</p>
		  </td>	
		 </tr>
		 <tr>
		  <td style=\"text-align: right;padding-right: 4px;\">
			<p>
			$l_age:
			</p>
		  </td>
		  <td>
			<p>
			<input type=\"text\" name=\"inp_age\" value=\"$inp_age\" size=\"3\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /> 
			</p>
		  </td>	
		 </tr>
		 <tr>
		  <td style=\"text-align: right;padding-right: 4px;\">
			<p>
			$l_gender:
			</p>
		  </td>
		  <td>
			<p>
			<select name=\"inp_gender\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
				<option value=\"male\""; if($inp_gender == "male"){ echo" selected=\"selected\""; } echo">$l_male</option>
				<option value=\"female\""; if($inp_gender == "female"){ echo" selected=\"selected\""; } echo">$l_female</option>
			</select>
			</p>
		  </td>	
		 </tr>
		 <tr>
		  <td style=\"text-align: right;padding-right: 4px;\">
			<p>
			$l_purpose:
			</p>
		  </td>
		  <td>
			<p>
			<select name=\"inp_purpose\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
				<option value=\"loose_weight\""; if($inp_purpose == "loose_weight"){ echo" selected=\"selected\""; } echo">$l_loose_weight</option>
				<option value=\"loose_weight\""; if($inp_purpose == "loose_weight"){ echo" selected=\"selected\""; } echo">$l_gain_weight</option>
				<option value=\"stay_at_current_weight\""; if($inp_purpose == "stay_at_current_weight"){ echo" selected=\"selected\""; } echo">$l_stay_at_current_weight</option>
			</select>
			</p>
		  </td>	
		 </tr>
		 <tr>
		  <td style=\"text-align: right;padding-right: 4px;\">
		
		  </td>
		  <td>
			<p>
			<input type=\"hidden\" name=\"action\" value=\"fill_in_general_information\" />
			<input type=\"hidden\" name=\"l\" value=\"$l\" />
			<input type=\"hidden\" name=\"inp_measurment\" value=\"$get_my_user_measurement\" />
			<input type=\"submit\" value=\"$l_next\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>
		
		  </td>	
		 </tr>
		</table>
		";
	}
	elseif($action == "switch_measurment"){
		
		// Change measurment
		if($get_my_user_measurement == "imperial"){
			$measurement = "metric";
		}
		else{
			$measurement = "imperial";
		}
		$measurement = stripslashes(strip_tags($measurement));
		$measurement_mysql = quote_smart($link, $measurement);
		
		$result = mysqli_query($link, "UPDATE $t_users SET user_measurement=$measurement_mysql WHERE user_id=$my_user_id_mysql");

		header("Location: new_meal_plan.php?l=$l");
		exit;
	
	}
	elseif($action == "fill_in_general_information"){

		if($process == "1"){

			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);
			if(empty($inp_title)){
				$url = "new_meal_plan.php?action=fill_in_general_information&l=$l";
				$url = $url . "&ft=error&fm=missing_title";
				header("Location: $url");
				exit;
			}
		
			$inp_title_clean = clean($inp_title);
			$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

			$inp_introduction = $_POST['inp_introduction'];
			$inp_introduction = output_html($inp_introduction);
			$inp_introduction_mysql = quote_smart($link, $inp_introduction);

			$inp_language = $_POST['inp_language'];
			$inp_language = output_html($inp_language);
			$inp_language_mysql = quote_smart($link, $inp_language);
			$l = $inp_language;
			if(empty($inp_language)){
				$url = "new_meal_plan.php?action=fill_in_general_information&l=$l";
				$url = $url . "&ft=error&fm=missing_language";
				header("Location: $url");
				exit;
			}

			$inp_number_of_days = $_POST['inp_number_of_days'];
			$inp_number_of_days = output_html($inp_number_of_days);
			$inp_number_of_days_mysql = quote_smart($link, $inp_number_of_days);

			// Dates
			$datetime = date("Y-m-d H:i:s");
			$datetime_saying = date("j. M Y H:i");

			$inp_user_ip = $_SERVER['REMOTE_ADDR'];
			$inp_user_ip = output_html($inp_user_ip);
			$inp_user_ip_mysql = quote_smart($link, $inp_user_ip);



			mysqli_query($link, "INSERT INTO $t_meal_plans
			(meal_plan_id, meal_plan_user_id, meal_plan_language, meal_plan_title, meal_plan_title_clean, meal_plan_number_of_days, meal_plan_introduction, 
			meal_plan_created, meal_plan_updated, meal_plan_user_ip, meal_plan_views, meal_plan_likes, meal_plan_dislikes, meal_plan_rating, meal_plan_comments) 
			VALUES 
			(NULL, $my_user_id_mysql, $inp_language_mysql, $inp_title_mysql, $inp_title_clean_mysql, $inp_number_of_days_mysql, $inp_introduction_mysql, 
			'$datetime', '$datetime', $inp_user_ip_mysql, '0', '0', '0', '0', '0')
			")
			or die(mysqli_error($link));

			// Get ID
			$query_t = "SELECT meal_plan_id FROM $t_meal_plans WHERE meal_plan_user_id=$my_user_id_mysql AND meal_plan_created='$datetime'";
			$result_t = mysqli_query($link, $query_t);
			$row_t = mysqli_fetch_row($result_t);
			list($get_meal_plan_id) = $row_t;
			
			// Search engine
			$inp_index_title = "$inp_title | $l_meal_plans";
			$inp_index_title_mysql = quote_smart($link, $inp_index_title);

			$inp_index_url = "meal_plans/meal_plan_view_1.php?meal_plan_id=$get_meal_plan_id";
			$inp_index_url_mysql = quote_smart($link, $inp_index_url);


			mysqli_query($link, "INSERT INTO $t_search_engine_index 
			(index_id, index_title, index_url, index_short_description, index_keywords, 
			index_module_name, index_reference_name, index_reference_id, index_is_ad, index_created_datetime, index_created_datetime_print, 
			index_language) 
			VALUES 
			(NULL, $inp_index_title_mysql, $inp_index_url_mysql, $inp_introduction_mysql , '', 
			'meal_plans', 'meal_plan_id', '$get_meal_plan_id', 0, '$datetime', '$datetime_saying', $inp_language_mysql)")
			or die(mysqli_error($link));

			// Header
			$url = "new_meal_plan_step_2_text.php?meal_plan_id=$get_meal_plan_id&entry_day_number=1&l=$l";
			header("Location: $url");
		}
		
		// Get title from url
		if($inp_purpose == "loose_weight"){
			$inp_title = "$l_loose_weight";
		}
		elseif($inp_purpose == "gain_weight"){
			$inp_title = "$l_gain__weight";
		}
		else{
			$inp_title = "$l_stay_at_current_weight";
		}
		if($inp_gender == "male"){
			$inp_title = $inp_title . ", $l_male_lowercase";
		}
		else{
			$inp_title = $inp_title . ", $l_female_lowercase";
		}

		$inp_title = $inp_title . ", $inp_age $l_years_old_lowercase";

		if($inp_measurment == "metric"){
			$inp_title = $inp_title . ", $inp_height_cm $l_cm_lowercase";
			$inp_title = $inp_title . ", $inp_mass $l_kg_lowercase";
		}
		else{
			$inp_title = $inp_title . ", $inp_height_feet $l_feet_lowercase $inp_height_inches $l_inches_lowercase";
			$inp_title = $inp_title . ", $inp_mass $l_lbs_lowercase";
		}
		
		
	
		echo"
		<h1>$l_new_meal_plan</h1>
	
		<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->

		<!-- Form -->
		<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
		</script>
		<form method=\"post\" action=\"new_meal_plan.php?action=fill_in_general_information&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">


		<p><b>$l_title*:</b><br />
		<input type=\"text\" name=\"inp_title\" value=\"$inp_title\" size=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" style=\"width: 99%;\" />
		</p>

		<p><b>$l_introduction*:</b><br />
		<textarea name=\"inp_introduction\" rows=\"4\" cols=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea>
		</p>

		<p><b>$l_number_of_days*:</b><br />
		<select name=\"inp_number_of_days\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
			<option value=\"1\">$l_one_day</option>
			<option value=\"7\">$l_full_week</option>
		</select>
		</p>


		<p><b>$l_language*:</b><br />
		<select name=\"inp_language\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">\n";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;
			$flag_path 	= "$root/_webdesign/images/flags/16x16/$get_language_active_flag" . "_16x16.png";
				
			echo"	<option value=\"$get_language_active_iso_two\"";if($l == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
		}
		echo"
		</select>
		</p>

		<p>
		<input type=\"submit\" value=\"$l_create\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		</form>
		<!-- //Form -->
		";
	}

}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=meal_plans/new_meal_plan.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>