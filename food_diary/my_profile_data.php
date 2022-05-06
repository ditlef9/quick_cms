<?php
/**
*
* File: food_diary/my_profile_data.php
* Version 1.0.0.
* Date 12:42 21.01.2018
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
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

/*- Tables --------------------------------------------------------------------------- */
include("_tables.php");


/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/users/ts_users.php");
include("$root/_admin/_translations/site/$l/users/ts_edit_profile.php");
include("$root/_admin/_translations/site/$l/food_diary/ts_my_goal_new.php");

/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_my_profile_data - $l_food_diary";
include("$root/_webdesign/header.php");


/*- Content ---------------------------------------------------------------------------------- */
// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){

	// Get my profile
	$my_user_id = $_SESSION['user_id'];
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_alias, user_email, user_gender, user_height, user_measurement, user_dob FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_alias, $get_my_user_email, $get_my_user_gender, $get_my_user_height, $get_user_measurement, $get_my_user_dob) = $row;
	
	// Change measurment?
	if(isset($_GET['measurement'])){
		$measurement = $_GET['measurement'];
		$measurement = stripslashes(strip_tags($measurement));
		$measurement_mysql = quote_smart($link, $measurement);
		
		$result = mysqli_query($link, "UPDATE $t_users SET user_measurement=$measurement_mysql WHERE user_id=$my_user_id_mysql");

		// Transfer 
		$get_user_measurement = "$measurement";
	}
	if($process == "1"){
		
		if($get_user_measurement == "imperial"){
			$inp_height_feet = $_POST['inp_height_feet'];
			$inp_height_feet = stripslashes(strip_tags($inp_height_feet));

			$inp_height_inches = $_POST['inp_height_inches'];
			$inp_height_inches = stripslashes(strip_tags($inp_height_inches));

			$inp_height_feet_cm = $inp_height_feet*30.48;
			$inp_height_inches_cm = $inp_height_inches*2.54;
			$inp_height_cm = $inp_height_feet_cm+$inp_height_inches_cm;
			$inp_height_cm = round($inp_height_cm, 0);
			$inp_height_cm_mysql = quote_smart($link, $inp_height_cm);

			$result = mysqli_query($link, "UPDATE $t_users SET user_height=$inp_height_cm_mysql WHERE user_id=$my_user_id_mysql");

		}
		else{
			$inp_height_cm = $_POST['inp_height_cm'];
			$inp_height_cm = stripslashes(strip_tags($inp_height_cm));
			$inp_height_cm_mysql = quote_smart($link, $inp_height_cm);
			$result = mysqli_query($link, "UPDATE $t_users SET user_height=$inp_height_cm_mysql WHERE user_id=$my_user_id_mysql");
		}


		
		$inp_user_gender = $_POST['inp_user_gender'];
		$inp_user_gender = output_html($inp_user_gender);
		$inp_user_gender_mysql = quote_smart($link, $inp_user_gender);
		$result = mysqli_query($link, "UPDATE $t_users SET user_gender=$inp_user_gender_mysql WHERE user_id=$my_user_id_mysql");

		// Dob
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
			$result = mysqli_query($link, "UPDATE $t_users SET user_dob=$inp_user_dob_mysql WHERE user_id=$my_user_id_mysql");
		}



		$url = "my_profile_data.php?l=$l&ft=success&fm=changes_saved";
		header("Location: $url");
		exit;
	}

	echo"
	<h1>$l_my_profile_data</h1>


	
	<!-- Feedback -->
		";
		if($ft != "" && $fm != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><p>$fm</p></div>";
		}
		echo"
	<!-- //Feedback -->


	<!-- You are here -->
		<p><b>$l_you_are_here</b><br />
		<a href=\"index.php?l=$l\">$l_food_diary</a>
		&gt;
		<a href=\"my_profile_data.php?l=$l\">$l_my_profile_data</a>
		</p>
	<!-- //You are here -->
	

	<!-- Form -->


		<form method=\"POST\" action=\"my_profile_data.php?l=$l&amp;process=1\" enctype=\"multipart/form-data\" name=\"nameform\">

		<p>
		$l_height
		";
		if($get_user_measurement == "imperial"){
			$inches = $get_my_user_height/2.54;
			$feet = intval($inches/12);
			$inches = $inches%12;

			echo" (<a href=\"my_profile_data.php?measurement=metric&amp;l=$l\">$l_change_to_cm</a>):<br />
			<input type=\"text\" name=\"inp_height_feet\" size=\"3\" value=\"$feet\" /> $l_feet
			<input type=\"text\" name=\"inp_height_inches\" size=\"3\" value=\"$inches\" /> $l_inches
			<br />
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_height_feet\"]').focus();
			});
			</script>
			";
		}
		else{
			echo"
			(<a href=\"my_profile_data.php?measurement=imperial&amp;l=$l\">$l_change_to_feet_and_inches</a>):<br />
			<input type=\"text\" name=\"inp_height_cm\" size=\"3\" value=\"$get_my_user_height\" /> $l_cm
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_height_cm\"]').focus();
			});
			</script>
			
			";
		}
		echo"
		</p>

		<p>
		$l_gender:<br />
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


		<p>
		<input type=\"submit\" value=\"$l_save\" class=\"btn\" />
		</p>

		</form>
	";
}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login?l=$l&amp;referer=$root/food_diary/index.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>