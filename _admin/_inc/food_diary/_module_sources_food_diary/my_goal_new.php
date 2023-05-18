<?php
/**
*
* File: food_diary/my_goal_new.php
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


/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);


if(isset($_GET['action'])) {
	$action = $_GET['action'];
	$action = strip_tags(stripslashes($action));
}
else{
	$action = "";
}
if (isset($_GET['goal_id'])) {
	$goal_id = $_GET['goal_id'];
	$goal_id = stripslashes(strip_tags($goal_id));
}
else{
	$goal_id = "";
}


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_new_goal - $l_my_goal - $l_food_diary";
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
	if($get_my_user_gender == ""){
		$action = "set_gender";
	}
	if($get_my_user_height == ""){
		$action = "set_height";
	}
	if($get_my_user_dob == "" OR $get_my_user_dob == "0000-00-00"){
		$action = "set_dob";
	}
	
	// Goal
	if($goal_id != ""){
		$goal_id_mysql = quote_smart($link, $goal_id);
		$query = "SELECT goal_id, goal_user_id, goal_current_weight, goal_current_fat_percentage, goal_target_weight, goal_target_fat_percentage, goal_i_want_to, goal_weekly_goal, goal_date, goal_activity_level, goal_current_bmi, goal_target_bmi, goal_current_bmr_calories, goal_current_bmr_fat, goal_current_bmr_carbs, goal_current_bmr_proteins, goal_current_sedentary_calories, goal_current_sedentary_fat, goal_current_sedentary_carbs, goal_current_sedentary_proteins, goal_current_with_activity_calories, goal_current_with_activity_fat, goal_current_with_activity_carbs, goal_current_with_activity_proteins, goal_target_bmr_calories, goal_target_bmr_fat, goal_target_bmr_carbs, goal_target_bmr_proteins, goal_target_sedentary_calories, goal_target_sedentary_fat, goal_target_sedentary_carbs, goal_target_sedentary_proteins, goal_target_with_activity_calories, goal_target_with_activity_fat, goal_target_with_activity_carbs, goal_target_with_activity_proteins, goal_synchronized, goal_notes FROM $t_food_diary_goals WHERE goal_id=$goal_id_mysql AND goal_user_id='$get_my_user_id'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_goal_id, $get_current_goal_user_id, $get_current_goal_current_weight, $get_current_goal_current_fat_percentage, $get_current_goal_target_weight, $get_current_goal_target_fat_percentage, $get_current_goal_i_want_to, $get_current_goal_weekly_goal, $get_current_goal_date, $get_current_goal_activity_level, $get_current_goal_current_bmi, $get_current_goal_target_bmi, $get_current_goal_current_bmr_calories, $get_current_goal_current_bmr_fat, $get_current_goal_current_bmr_carbs, $get_current_goal_current_bmr_proteins, $get_current_goal_current_sedentary_calories, $get_current_goal_current_sedentary_fat, $get_current_goal_current_sedentary_carbs, $get_current_goal_current_sedentary_proteins, $get_current_goal_current_with_activity_calories, $get_current_goal_current_with_activity_fat, $get_current_goal_current_with_activity_carbs, $get_current_goal_current_with_activity_proteins, $get_current_goal_target_bmr_calories, $get_current_goal_target_bmr_fat, $get_current_goal_target_bmr_carbs, $get_current_goal_target_bmr_proteins, $get_current_goal_target_sedentary_calories, $get_current_goal_target_sedentary_fat, $get_current_goal_target_sedentary_carbs, $get_current_goal_target_sedentary_proteins, $get_current_goal_target_with_activity_calories, $get_current_goal_target_with_activity_fat, $get_current_goal_target_with_activity_carbs, $get_current_goal_target_with_activity_proteins, $get_current_goal_synchronized, $get_current_goal_notes) = $row;
		if($get_current_goal_id == ""){
			echo"
			<div id=\"error\"><p>Goal not found.</p></div>
			<p><a href=\"my_goal_new.php?l=$l\">Restart process</a></p>
			";
			die;
		}
	}


	if($process != "1"){
		echo"
		<h1>$l_new_goal</h1>


	
		<!-- Feedback -->
		";
		if($ft != "" && $fm != ""){
			if($fm == "birthday_saved"){
				$fm = "$l_birthday_saved";
			}
			elseif($fm == "height_saved"){
				$fm = "$l_height_saved";
			}
			elseif($fm == "gender_saved"){
				$fm = "$l_gender_saved";
			}
			elseif($fm == "changed_to_metric"){
				$fm = "$l_changed_to_metric";
			}
			elseif($fm == "changed_to_imperial"){
				$fm = "$l_changed_to_imperial";
			}
			elseif($fm == "your_weight_was_saved"){
				$fm = "$l_your_weight_was_saved";
			}
			elseif($fm == "your_target_weight_was_saved"){
				$fm = "$l_your_target_weight_was_saved";
			}
			elseif($fm == "activity_level_saved"){
				$fm = "$l_activity_level_saved";
			}
			elseif($fm == "changes_saved"){
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
		<a href=\"my_goal.php?l=$l\">$l_my_goal</a>
		&gt;
		<a href=\"my_goal_new.php?l=$l\">$l_new_goal</a>
		</p>
		<!-- //You are here -->
		";
	} // process

	if($action == "set_gender"){
		if($process == "1" && isset($_GET['gender'])){
		
			$gender = $_GET['gender'];
			$gender = stripslashes(strip_tags($gender));
			if($gender != "male"){ $gender = "female"; }
			$gender_mysql = quote_smart($link, $gender);

			
			$result = mysqli_query($link, "UPDATE $t_users  SET user_gender=$gender_mysql WHERE user_id=$my_user_id_mysql");

			header("Location: my_goal_new.php?l=$l&ft=success&fm=gender_saved");
		}
		echo"
		<h2>$l_your_gender</h2>

		<div class=\"vertical\">
			<ul>
				<li><a href=\"my_goal_new.php?action=set_gender&amp;gender=male&amp;l=$l&amp;process=1\">$l_male</a></li>
				<li><a href=\"my_goal_new.php?action=set_gender&amp;gender=female&amp;l=$l&amp;process=1\">$l_female</a></li>
			</ul>
		</div>
		";
	}
	elseif($action == "set_height"){
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
			header("Location: my_goal_new.php?l=$l&ft=success&fm=height_saved");
		}
		if($process != "1"){
			echo"
			<h2>$l_your_height</h2>

			";
		}

		if(isset($_GET['measurement'])){

			$measurement = $_GET['measurement'];
			$measurement = stripslashes(strip_tags($measurement));
			$measurement_mysql = quote_smart($link, $measurement);
			
			$result = mysqli_query($link, "UPDATE $t_users SET user_measurement=$measurement_mysql WHERE user_id=$my_user_id_mysql");

			header("Location: my_goal_new.php?l=$l&ft=info&fm=changed_to_$measurement");
		}

		if($get_user_measurement == "imperial"){
			echo"
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_height_feet\"]').focus();
			});
			</script>
			<form method=\"post\" action=\"my_goal_new.php?action=set_height&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			<p>
			<input type=\"text\" name=\"inp_height_feet\" size=\"3\" /> $l_feet
			<input type=\"text\" name=\"inp_height_inches\" size=\"3\" /> $l_inches
			</p>

			<p>
			<input type=\"submit\" value=\"$l_save\" class=\"btn btn_success\" />
			<a href=\"my_goal_new.php?action=set_height&amp;measurement=metric&amp;l=$l&amp;process=1\" class=\"btn btn_default\">$l_change_to_cm</a>
			</p>
			";
		}
		else{
			echo"
			<form method=\"post\" action=\"my_goal_new.php?action=set_height&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_height_cm\"]').focus();
			});
			</script>
			<p>
			<input type=\"text\" name=\"inp_height_cm\" size=\"3\" /> $l_cm
			</p>

			<p>
			<input type=\"submit\" value=\"$l_save\" class=\"btn btn_success\" />
			<a href=\"my_goal_new.php?action=set_height&amp;measurement=imperial&amp;l=$l&amp;process=1\" class=\"btn btn_default\">$l_change_to_feet_and_inches</a>
			</p>
			";
		}
		echo"
			
		</form>
		";
	}
	elseif($action == "set_dob"){
		if($process != "1"){
			echo"
			<h2>$l_when_is_your_bithday</h2>
			";
		}
		
		if (isset($_GET['day'])) {
			$day = $_GET['day'];
			$day = stripslashes(strip_tags($day));
			if (isset($_GET['month'])) {
				$month = $_GET['month'];
				$month = stripslashes(strip_tags($month));

				if (isset($_GET['year'])) {
					$year = $_GET['year'];
					$year = stripslashes(strip_tags($year));

					// Update dob
				
					$inp_user_dob = $year . "-" . $month . "-" . $day;
					$inp_user_dob = output_html($inp_user_dob);
					$inp_user_dob_mysql = quote_smart($link, $inp_user_dob);
					if($inp_user_dob != "--"){
						$result = mysqli_query($link, "UPDATE $t_users SET user_dob=$inp_user_dob_mysql WHERE user_id=$my_user_id_mysql");
					}
					header("Location: my_goal_new.php?l=$l&ft=success&fm=birthday_saved");

				}
				else{
					
					echo"
					<p><b>$l_year</b></p>
					<div class=\"vertical\">
						<ul>";
						$year = date("Y");
						$year = $year-15;
						for($x=0;$x<150;$x++){
							echo"				";
							echo"<li><a href=\"my_goal_new.php?action=set_dob&amp;day=$day&amp;month=$month&amp;year=$year&amp;l=$l&amp;process=1\">$year</a></li>\n";
		
							$year = $year-1;
						}
					echo"
						</ul>
					</div>
					";
				}
			}
			else{
				echo"
				<p><b>$l_month</b></p>
			
				<div class=\"vertical\">
					<ul>";
					$l_month_array[0] = "";
					$l_month_array[1] = "$l_january";
					$l_month_array[2] = "$l_february";
					$l_month_array[3] = "$l_march";
					$l_month_array[4] = "$l_april";
					$l_month_array[5] = "$l_may";
					$l_month_array[6] = "$l_june";
					$l_month_array[7] = "$l_july";
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
						echo"				";
						echo"<li><a href=\"my_goal_new.php?action=set_dob&amp;day=$day&amp;month=$y&amp;l=$l\">$l_month_array[$x]</a></li>\n";
					}
					echo"
					</ul>
				</div>
				";
			}
		}
		else{
			echo"
			<p><b>$l_day</b></p>
			
				<div class=\"vertical\">
					<ul>";
					for($x=1;$x<32;$x++){
						if($x<10){
							$y = 0 . $x;
							}
							else{
								$y = $x;
							}
						echo"				";
						echo"<li><a href=\"my_goal_new.php?action=set_dob&amp;day=$y&amp;l=$l\">$x</a></li>\n";
					}
					echo"
					</ul>
				</div>
			";
		}
		

	}
	elseif($action == ""){
		// Change measurment
		if(isset($_GET['measurement'])){

			$measurement = $_GET['measurement'];
			$measurement = stripslashes(strip_tags($measurement));
			$measurement_mysql = quote_smart($link, $measurement);
			
			$result = mysqli_query($link, "UPDATE $t_users SET user_measurement=$measurement_mysql WHERE user_id=$my_user_id_mysql");

			header("Location: my_goal_new.php?l=$l&ft=info&fm=changed_to_$measurement");
		}
		if($process == "1" && isset($_POST['inp_weight'])){

			$inp_weight = $_POST['inp_weight'];
			$inp_weight = output_html($inp_weight);
			$inp_weight = str_replace(",", ".", $inp_weight);

			if($get_user_measurement == "imperial"){
				// Convert to kg
				$inp_weight = round($inp_weight*0.45359237,0);
			}

			$inp_weight_mysql = quote_smart($link, $inp_weight);

			// Calcualte BMI
			// BMI = m/h^2
			$height_meter = $get_my_user_height/100;
			$height_squared = $height_meter*$height_meter;
			if($height_squared != "0"){
				$current_bmi = round($inp_weight/$height_squared,1);
			}
			else{
				$current_bmi = "0";
			}
			$current_bmi_mysql = quote_smart($link, $current_bmi);
			
			// Insert or update?
			$inp_date = date("Y-m-d");
			$query = "SELECT goal_id FROM $t_food_diary_goals WHERE goal_user_id='$get_my_user_id' AND goal_date='$inp_date'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_goal_id) = $row;

			// Feet or inches
			$inp_kg_feet_inches_mysql = quote_smart($link, $get_user_measurement);

			if($get_goal_id == ""){
				// Insert my goal
				mysqli_query($link, "INSERT INTO $t_food_diary_goals
				(goal_id, goal_user_id, goal_kg_feet_inches, goal_current_weight, goal_date, goal_current_bmi) 
				VALUES 
				(NULL, '$get_my_user_id', $inp_kg_feet_inches_mysql, $inp_weight_mysql, '$inp_date', $current_bmi_mysql)")
				or die(mysqli_error($link));
			}
			else{
				// Update my goal
				$result = mysqli_query($link, "UPDATE $t_food_diary_goals SET goal_current_weight=$inp_weight_mysql, goal_current_bmi=$current_bmi_mysql WHERE goal_user_id='$get_my_user_id'");

			}

			// Get the goal ID
			$query = "SELECT goal_id FROM $t_food_diary_goals WHERE goal_user_id='$get_my_user_id' AND goal_date='$inp_date'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_goal_id) = $row;

			header("Location: my_goal_new.php?action=step_2_target_weight&goal_id=$get_goal_id&l=$l&ft=success&fm=your_weight_was_saved");
			exit;
		}

		
		$inp_date = date("Y-m-d");
		$query = "SELECT goal_id, goal_current_weight FROM $t_food_diary_goals WHERE goal_user_id='$get_my_user_id' AND goal_date='$inp_date'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_goal_id, $get_goal_current_weight) = $row;



		echo"
		<h2>$l_what_is_your_current_weight</h2>

		";

		if($get_user_measurement == "imperial"){
			// Convert weight to imperial
			$get_goal_current_weight = round($get_goal_current_weight/0.45359237,2);
			echo"
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_weight\"]').focus();
			});
			</script>
			<form method=\"post\" action=\"my_goal_new.php?action=&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<p>
			<input type=\"text\" name=\"inp_weight\" size=\"3\" value=\""; if($get_goal_current_weight != "0"){ echo"$get_goal_current_weight"; } echo"\" /> $l_lbs_lowercase
			</p>

			<p>
			<input type=\"submit\" value=\"$l_save\" class=\"btn btn_success\" />
			<a href=\"my_goal_new.php?action=&amp;measurement=metric&amp;l=$l&amp;process=1\" class=\"btn btn_default\">$l_change_to_kg</a>
			</p>
			</form>		
			";
		}
		else{ 
			echo"
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_weight\"]').focus();
			});
			</script>
			<form method=\"post\" action=\"my_goal_new.php?action=&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<p>
			<input type=\"text\" name=\"inp_weight\" size=\"3\" value=\"$get_goal_current_weight\" /> $l_kg_lowercase
			</p>

			<p>
			<input type=\"submit\" value=\"$l_save\" class=\"btn btn_success\" />
			<a href=\"my_goal_new.php?action=&amp;measurement=imperial&amp;l=$l&amp;process=1\" class=\"btn btn_default\">$l_change_to_lbs</a>
			</p>
			</form>		
			";
		}
	} // current weight
	elseif($action == "step_2_target_weight" && $goal_id != ""){


		// Change measurment
		if(isset($_GET['measurement'])){

			$measurement = $_GET['measurement'];
			$measurement = stripslashes(strip_tags($measurement));
			$measurement_mysql = quote_smart($link, $measurement);
			
			$result = mysqli_query($link, "UPDATE $t_users SET user_measurement=$measurement_mysql WHERE user_id=$my_user_id_mysql");

			header("Location: my_goal_new.php?action=step_2_target_weight&goal_id=$goal_id&l=$l&ft=info&fm=changed_to_$measurement");
		}
		if($process == "1" && isset($_POST['inp_target_weight'])){

			$inp_target_weight = $_POST['inp_target_weight'];
			$inp_target_weight = output_html($inp_target_weight);
			$inp_target_weight = str_replace(",", ".", $inp_target_weight);

			if($get_user_measurement == "imperial"){
				// Convert to kg
				$inp_target_weight = round($inp_target_weight*0.45359237,0);
			}

			$inp_target_weight_mysql = quote_smart($link, $inp_target_weight);
			

			// Gain or loose?
			$weight_diff = $get_current_goal_current_weight - $inp_target_weight;
			if($weight_diff > 0){
				$inp_goal_i_want_to = "loose_weight";
				$inp_goal_weekly_goal = "0.5";
			}
			elseif($weight_diff < 0){
				$inp_goal_i_want_to = "gain_weight";
				$inp_goal_weekly_goal = "0.5";
			}
			else{
				$inp_goal_i_want_to = "keep_my_weight";
				$inp_goal_weekly_goal = "0";
			}

			// Calcualte target BMI
			// BMI = m/h^2
			$height_meter = $get_my_user_height/100;
			$height_squared = $height_meter*$height_meter;
			if($height_squared != "0"){
				$target_bmi = round($inp_target_weight/$height_squared,1);
			}
			else { 
				$target_bmi = "0";
			}
			$target_bmi_mysql = quote_smart($link, $target_bmi);
			
			
			// Update target weight
			$result = mysqli_query($link, "UPDATE $t_food_diary_goals SET goal_target_weight=$inp_target_weight_mysql, goal_i_want_to='$inp_goal_i_want_to', goal_weekly_goal='$inp_goal_weekly_goal', goal_target_bmi=$target_bmi_mysql WHERE goal_id=$get_current_goal_id AND goal_user_id='$get_my_user_id'");
			
			header("Location: my_goal_new.php?action=step_3_activity_level&goal_id=$goal_id&l=$l&ft=success&fm=your_target_weight_was_saved");
			exit;
		}



		echo"
		<h2>$l_what_is_your_target_weight</h2>

		";

		if($get_user_measurement == "imperial"){
			// Convert weight to imperial
			$get_current_goal_target_weight = round($get_current_goal_target_weight/0.45359237,2);
			echo"
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_target_weight\"]').focus();
			});
			</script>
			<form method=\"post\" action=\"my_goal_new.php?action=step_2_target_weight&amp;goal_id=$goal_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<p>
			<input type=\"text\" name=\"inp_target_weight\" size=\"3\" value=\""; if($get_current_goal_target_weight != "0"){ echo"$get_current_goal_target_weight"; } echo"\" /> $l_lbs_lowercase
			</p>

			<p>
			<input type=\"submit\" value=\"$l_save\" class=\"btn btn_success\" />
			<a href=\"my_goal_new.php?action=step_2_target_weight&amp;goal_id=$goal_id&amp;measurement=metric&amp;l=$l&amp;process=1\" class=\"btn btn_default\">$l_change_to_kg</a>
			</p>
			</form>		
			";
		}
		else{ 
			echo"
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_target_weight\"]').focus();
			});
			</script>
			<form method=\"post\" action=\"my_goal_new.php?action=step_2_target_weight&amp;goal_id=$goal_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<p>
			<input type=\"text\" name=\"inp_target_weight\" size=\"3\" value=\"$get_current_goal_target_weight\" /> $l_kg_lowercase
			</p>

			<p>
			<input type=\"submit\" value=\"$l_save\" class=\"btn btn_success\" />
			<a href=\"my_goal_new.php?action=step_2_target_weight&amp;goal_id=$goal_id&amp;measurement=imperial&amp;l=$l&amp;process=1\" class=\"btn btn_default\">$l_change_to_lbs</a>
			</p>
			</form>		
			";
		}
	} // step_2_target_weight
	elseif($action == "step_3_activity_level" && $goal_id != ""){

		if($process == "1" && isset($_GET['activity_level'])){

			$inp_activity_level = $_GET['activity_level'];
			$inp_activity_level = output_html($inp_activity_level);
			$inp_activity_level_mysql = quote_smart($link, $inp_activity_level);
			
			$age = date('Y') - substr($get_my_user_dob, 0, 4);
			if (strtotime(date('Y-m-d')) - strtotime(date('Y') . substr($get_my_user_dob, 4, 6)) < 0){
				$age--;
			}

			/* BMR */
			if($get_my_user_gender == "male"){
				// BMR = 66.5 + (13.75 x kg body weight) + (5.003 x height in cm) - (6.755 x age)
				$bmr_calories = 66.5+(13.75*$get_current_goal_current_weight)+(5.003*$get_my_user_height)-(6.755*$age);
				$bmr_calories = round($bmr_calories, 0);
			}
			else{
				// BMR = 55.1 + (9.563 x kg body weight) + (1.850 x height in cm) - (4.676 x age)
				$bmr_calories = 655+(9.563*$get_current_goal_current_weight)+(1.850*$get_my_user_height)-(4.676*$age);
				$bmr_calories = round($bmr_calories, 0);
			}
			$bmr_calories_mysql = quote_smart($link, $bmr_calories);
			
			$bmr_fat            = round(($bmr_calories*13)/100,0); // 13 % fat
			$bmr_fat_mysql      = quote_smart($link, $bmr_fat);
			$bmr_carbs          = round(($bmr_calories*44)/100,0); // 44 % carbs
			$bmr_carbs_mysql    = quote_smart($link, $bmr_carbs);
			$bmr_proteins       = round(($bmr_calories*43)/100,0); // 13 % proteins
			$bmr_proteins_mysql = quote_smart($link, $bmr_proteins);
		

			/* Sedentary */
			$sedentary_calories = $bmr_calories*1.2;
			$sedentary_calories = round($sedentary_calories, 0);
			$sedentary_calories_mysql = quote_smart($link, $sedentary_calories);

			$sedentary_fat            = round(($sedentary_calories*13)/100,0); // 13 % fat
			$sedentary_fat_mysql      = quote_smart($link, $sedentary_fat);

			$sedentary_carbs          = round(($sedentary_calories*44)/100,0); // 44 % carbs
			$sedentary_carbs_mysql    = quote_smart($link, $sedentary_carbs);

			$sedentary_proteins       = round(($sedentary_calories*43)/100,0); // 13 % proteins
			$sedentary_proteins_mysql = quote_smart($link, $sedentary_proteins);
			
			
			/* My activity level */
			$with_activity_calories = $bmr_calories*$inp_activity_level;
			$with_activity_calories = round($with_activity_calories, 0);
			$with_activity_calories_mysql = quote_smart($link, $with_activity_calories);

			$with_activity_fat            = round(($with_activity_calories*13)/100,0); // 13 % fat
			$with_activity_fat_mysql      = quote_smart($link, $with_activity_fat);
			$with_activity_carbs          = round(($with_activity_calories*44)/100,0); // 44 % carbs
			$with_activity_carbs_mysql    = round(($with_activity_calories*44)/100,0);
			$with_activity_proteins       = round(($with_activity_calories*43)/100,0); // 13 % proteins
			$with_activity_proteins_mysql = quote_smart($link, $with_activity_proteins);


			// Update current weight
			$result = mysqli_query($link, "UPDATE $t_food_diary_goals SET goal_activity_level=$inp_activity_level_mysql,
			goal_current_bmr_calories=$bmr_calories_mysql,
			goal_current_bmr_fat=$bmr_fat_mysql,
			goal_current_bmr_carbs=$bmr_carbs_mysql,
			goal_current_bmr_proteins=$bmr_proteins_mysql,
			goal_current_sedentary_calories=$sedentary_calories_mysql,
			goal_current_sedentary_fat=$sedentary_fat_mysql,
			goal_current_sedentary_carbs=$sedentary_carbs_mysql,
			goal_current_sedentary_proteins=$sedentary_proteins_mysql
			 WHERE goal_id=$get_current_goal_id AND goal_user_id='$get_my_user_id'");

			$result = mysqli_query($link, "UPDATE $t_food_diary_goals SET 
			goal_current_with_activity_calories=$with_activity_calories_mysql,
			goal_current_with_activity_fat=$with_activity_fat_mysql,
			goal_current_with_activity_carbs=$with_activity_carbs_mysql,
			goal_current_with_activity_proteins=$with_activity_proteins_mysql
			 WHERE goal_id=$get_current_goal_id AND goal_user_id='$get_my_user_id'");



			/* Loose or gain weight? */
			if($get_current_goal_i_want_to == "loose_weight"){
				$kcal_per_week = 7700*0.5;
				$kcal_per_day  = $kcal_per_week/7;
				$target_bmr_calories = round($bmr_calories - $kcal_per_day, 0);
				$target_bmr_calories_mysql = quote_smart($link, $target_bmr_calories);

				$target_sedentary_calories = round($sedentary_calories - $kcal_per_day, 0);
				$target_sedentary_calories_mysql = quote_smart($link, $target_sedentary_calories);

				$target_with_activity_calories = round($with_activity_calories - $kcal_per_day, 0);
				$target_with_activity_calories_mysql = quote_smart($link, $target_with_activity_calories);

			}
			elseif($get_current_goal_i_want_to == "gain_weight"){
				$kcal_per_week = 7700*0.5;
				$kcal_per_day  = $kcal_per_week/7;
				$target_bmr_calories = round($bmr_calories + $kcal_per_day, 0);
				$target_bmr_calories_mysql = quote_smart($link, $target_bmr_calories);

				$target_sedentary_calories = round($sedentary_calories + $kcal_per_day, 0);
				$target_sedentary_calories_mysql = quote_smart($link, $target_sedentary_calories);

				$target_with_activity_calories = round($with_activity_calories + $kcal_per_day, 0);
				$target_with_activity_calories_mysql = quote_smart($link, $target_with_activity_calories);
			}
			else{
				$target_bmr_calories = "0";
				$target_bmr_calories_mysql = quote_smart($link, $target_bmr_calories);

				$target_sedentary_calories = "0";
				$target_sedentary_calories_mysql = quote_smart($link, $target_sedentary_calories);

				$target_with_activity_calories = "0";
				$target_with_activity_calories_mysql = quote_smart($link, $target_with_activity_calories);

			}
			
			$target_bmr_fat            = round(($target_bmr_calories*13)/100,0); // 13 % fat
			$target_bmr_fat_mysql      = quote_smart($link, $target_bmr_fat);
			$target_bmr_carbs          = round(($target_bmr_calories*44)/100,0); // 44 % carbs
			$target_bmr_carbs_mysql    = quote_smart($link, $target_bmr_carbs);
			$target_bmr_proteins       = round(($target_bmr_calories*43)/100,0); // 13 % proteins
			$target_bmr_proteins_mysql = quote_smart($link, $target_bmr_proteins);


			/* Loose or gain weight?: Sedentary */

			$target_sedentary_fat            = round(($target_sedentary_calories*13)/100,0); // 13 % fat
			$target_sedentary_fat_mysql      = quote_smart($link, $target_sedentary_fat);
			$target_sedentary_carbs          = round(($target_sedentary_calories*44)/100,0); // 44 % carbs
			$target_sedentary_carbs_mysql    = quote_smart($link, $target_sedentary_carbs);
			$target_sedentary_proteins       = round(($target_sedentary_calories*43)/100,0); // 13 % proteins
			$target_sedentary_proteins_mysql = quote_smart($link, $target_sedentary_proteins);
			
			
			/* Loose or gain weight?:  My activity level */
			$target_with_activity_fat            = round(($target_with_activity_calories*13)/100,0); // 13 % fat
			$target_with_activity_fat_mysql      = quote_smart($link, $target_with_activity_fat);
			$target_with_activity_carbs          = round(($target_with_activity_calories*44)/100,0); // 44 % carbs
			$target_with_activity_carbs_mysql    = quote_smart($link, $target_with_activity_carbs);
			$target_with_activity_proteins       = round(($target_with_activity_calories*43)/100,0); // 13 % proteins
			$target_with_activity_proteins_mysql = quote_smart($link, $target_with_activity_proteins);


			// Update target weight
			$result = mysqli_query($link, "UPDATE $t_food_diary_goals SET 
			goal_target_bmr_calories=$target_bmr_calories_mysql,
			goal_target_bmr_fat=$target_bmr_fat_mysql,
			goal_target_bmr_carbs=$target_bmr_carbs_mysql,
			goal_target_bmr_proteins=$target_bmr_proteins_mysql,
			goal_target_sedentary_calories=$target_sedentary_calories_mysql,
			goal_target_sedentary_fat=$target_sedentary_fat_mysql,
			goal_target_sedentary_carbs=$target_sedentary_carbs_mysql,
			goal_target_sedentary_proteins=$target_sedentary_proteins_mysql
			 WHERE goal_id=$get_current_goal_id AND goal_user_id='$get_my_user_id'");

			$result = mysqli_query($link, "UPDATE $t_food_diary_goals SET 
			goal_target_with_activity_calories=$target_with_activity_calories_mysql,
			goal_target_with_activity_fat=$target_with_activity_fat_mysql,
			goal_target_with_activity_carbs=$target_with_activity_carbs_mysql,
			goal_target_with_activity_proteins=$target_with_activity_proteins_mysql
			 WHERE goal_id=$get_current_goal_id AND goal_user_id='$get_my_user_id'");


			
			header("Location: my_goal_new.php?action=step_4_results&goal_id=$goal_id&l=$l&ft=success&fm=activity_level_saved");
			exit;


		
		}



		echo"
		<h2>$l_please_select_an_activity_level</h2>
		
		<div class=\"vertical\">
			<ul>
				<li><a href=\"my_goal_new.php?action=step_3_activity_level&amp;goal_id=$goal_id&amp;activity_level=1.2&amp;l=$l&amp;process=1\""; if($get_current_goal_activity_level == "1.2"){ echo" style=\"font-weight:bold;\""; } echo">$l_sedentary - $l_little_or_no_exercise_lowercase</a></li>
				<li><a href=\"my_goal_new.php?action=step_3_activity_level&amp;goal_id=$goal_id&amp;activity_level=1.375&amp;l=$l&amp;process=1\""; if($get_current_goal_activity_level == "1.375"){ echo" style=\"font-weight:bold;\""; } echo">$l_lightly_active - $l_exercise_sports_1_3_times_week_lowercase</a></li>
				<li><a href=\"my_goal_new.php?action=step_3_activity_level&amp;goal_id=$goal_id&amp;activity_level=1.55&amp;l=$l&amp;process=1\""; if($get_current_goal_activity_level == "1.55"){ echo" style=\"font-weight:bold;\""; } echo">$l_moderatetely_active - $l_exercise_sports_3_5_times_week_lowercase</a></li>
				<li><a href=\"my_goal_new.php?action=step_3_activity_level&amp;goal_id=$goal_id&amp;activity_level=1.725&amp;l=$l&amp;process=1\""; if($get_current_goal_activity_level == "1.725"){ echo" style=\"font-weight:bold;\""; } echo">$l_very_active - $l_hard_exercise_sports_6_7_times_week_lowercase</a></li>
				<li><a href=\"my_goal_new.php?action=step_3_activity_level&amp;goal_id=$goal_id&amp;activity_level=1.9&amp;l=$l&amp;process=1\""; if($get_current_goal_activity_level == "1.9"){ echo" style=\"font-weight:bold;\""; } echo">$l_extra_active - $l_very_hard_exercise_sports_or_physical_job_lowercase</a></li>
			</ul>
		</div>
		";
	} // step_3_activity_level
	elseif($action == "step_4_results" && $goal_id != ""){
		if($process == "1"){
			// Current sedentary
			$inp_edit_goal_current_sedentary_calories = $_POST['inp_edit_goal_current_sedentary_calories'];
			$inp_edit_goal_current_sedentary_calories = output_html($inp_edit_goal_current_sedentary_calories);
			$inp_edit_goal_current_sedentary_calories_mysql = quote_smart($link, $inp_edit_goal_current_sedentary_calories);

			$inp_edit_goal_current_sedentary_fat		= round(($inp_edit_goal_current_sedentary_calories*13)/100,0); // 13 % fat
			$inp_edit_goal_current_sedentary_fat_mysql      = quote_smart($link, $inp_edit_goal_current_sedentary_fat);

			$inp_edit_goal_current_sedentary_carbs 		= round(($inp_edit_goal_current_sedentary_calories*44)/100,0); // 44 % carbs
			$inp_edit_goal_current_sedentary_carbs_mysql    = quote_smart($link, $inp_edit_goal_current_sedentary_carbs);

			$inp_edit_goal_current_sedentary_proteins 	= round(($inp_edit_goal_current_sedentary_calories*43)/100,0); // 13 % proteins
			$inp_edit_goal_current_sedentary_proteins_mysql = quote_smart($link, $inp_edit_goal_current_sedentary_proteins);

			$result = mysqli_query($link, "UPDATE $t_food_diary_goals SET
			goal_current_sedentary_calories=$inp_edit_goal_current_sedentary_calories_mysql,
			goal_current_sedentary_fat=$inp_edit_goal_current_sedentary_fat_mysql,
			goal_current_sedentary_carbs=$inp_edit_goal_current_sedentary_carbs_mysql,
			goal_current_sedentary_proteins=$inp_edit_goal_current_sedentary_proteins_mysql
			 WHERE goal_id=$get_current_goal_id AND goal_user_id='$get_my_user_id'");

			
			// Target sedentary
			$inp_edit_goal_target_sedentary_calories = $_POST['inp_edit_goal_target_sedentary_calories'];
			$inp_edit_goal_target_sedentary_calories = output_html($inp_edit_goal_target_sedentary_calories);
			$inp_edit_goal_target_sedentary_calories_mysql = quote_smart($link, $inp_edit_goal_target_sedentary_calories);

			$inp_edit_goal_target_sedentary_fat		= round(($inp_edit_goal_target_sedentary_calories*13)/100,0); // 13 % fat
			$inp_edit_goal_target_sedentary_fat_mysql      = quote_smart($link, $inp_edit_goal_target_sedentary_fat);

			$inp_edit_goal_target_sedentary_carbs 		= round(($inp_edit_goal_target_sedentary_calories*44)/100,0); // 44 % carbs
			$inp_edit_goal_target_sedentary_carbs_mysql    = quote_smart($link, $inp_edit_goal_target_sedentary_carbs);

			$inp_edit_goal_target_sedentary_proteins 	= round(($inp_edit_goal_target_sedentary_calories*43)/100,0); // 13 % proteins
			$inp_edit_goal_target_sedentary_proteins_mysql = quote_smart($link, $inp_edit_goal_target_sedentary_proteins);

			$result = mysqli_query($link, "UPDATE $t_food_diary_goals SET
			goal_target_sedentary_calories=$inp_edit_goal_target_sedentary_calories_mysql,
			goal_target_sedentary_fat=$inp_edit_goal_target_sedentary_fat_mysql,
			goal_target_sedentary_carbs=$inp_edit_goal_target_sedentary_carbs_mysql,
			goal_target_sedentary_proteins=$inp_edit_goal_target_sedentary_proteins_mysql
			 WHERE goal_id=$get_current_goal_id AND goal_user_id='$get_my_user_id'");

			
			// Current with activity
			$inp_edit_goal_current_with_activity_calories = $_POST['inp_edit_goal_current_with_activity_calories'];
			$inp_edit_goal_current_with_activity_calories = output_html($inp_edit_goal_current_with_activity_calories);
			$inp_edit_goal_current_with_activity_calories_mysql = quote_smart($link, $inp_edit_goal_current_with_activity_calories);

			$inp_edit_goal_current_with_activity_fat		= round(($inp_edit_goal_current_with_activity_calories*13)/100,0); // 13 % fat
			$inp_edit_goal_current_with_activity_fat_mysql      = quote_smart($link, $inp_edit_goal_current_with_activity_fat);

			$inp_edit_goal_current_with_activity_carbs 		= round(($inp_edit_goal_current_with_activity_calories*44)/100,0); // 44 % carbs
			$inp_edit_goal_current_with_activity_carbs_mysql    = quote_smart($link, $inp_edit_goal_current_with_activity_carbs);

			$inp_edit_goal_current_with_activity_proteins 	= round(($inp_edit_goal_current_with_activity_calories*43)/100,0); // 13 % proteins
			$inp_edit_goal_current_with_activity_proteins_mysql = quote_smart($link, $inp_edit_goal_current_with_activity_proteins);

			$result = mysqli_query($link, "UPDATE $t_food_diary_goals SET
			goal_current_with_activity_calories=$inp_edit_goal_current_with_activity_calories_mysql,
			goal_current_with_activity_fat=$inp_edit_goal_current_with_activity_fat_mysql,
			goal_current_with_activity_carbs=$inp_edit_goal_current_with_activity_carbs_mysql,
			goal_current_with_activity_proteins=$inp_edit_goal_current_with_activity_proteins_mysql
			 WHERE goal_id=$get_current_goal_id AND goal_user_id='$get_my_user_id'");

			
			// Target with acitivty
			$inp_edit_goal_target_with_activity_calories = $_POST['inp_edit_goal_target_with_activity_calories'];
			$inp_edit_goal_target_with_activity_calories = output_html($inp_edit_goal_target_with_activity_calories);
			$inp_edit_goal_target_with_activity_calories_mysql = quote_smart($link, $inp_edit_goal_target_with_activity_calories);

			$inp_edit_goal_target_with_activity_fat		= round(($inp_edit_goal_target_with_activity_calories*13)/100,0); // 13 % fat
			$inp_edit_goal_target_with_activity_fat_mysql      = quote_smart($link, $inp_edit_goal_target_with_activity_fat);

			$inp_edit_goal_target_with_activity_carbs 		= round(($inp_edit_goal_target_with_activity_calories*44)/100,0); // 44 % carbs
			$inp_edit_goal_target_with_activity_carbs_mysql    = quote_smart($link, $inp_edit_goal_target_with_activity_carbs);

			$inp_edit_goal_target_with_activity_proteins 	= round(($inp_edit_goal_target_with_activity_calories*43)/100,0); // 13 % proteins
			$inp_edit_goal_target_with_activity_proteins_mysql = quote_smart($link, $inp_edit_goal_target_with_activity_proteins);

			$result = mysqli_query($link, "UPDATE $t_food_diary_goals SET
			goal_target_with_activity_calories=$inp_edit_goal_target_with_activity_calories_mysql,
			goal_target_with_activity_fat=$inp_edit_goal_target_with_activity_fat_mysql,
			goal_target_with_activity_carbs=$inp_edit_goal_target_with_activity_carbs_mysql,
			goal_target_with_activity_proteins=$inp_edit_goal_target_with_activity_proteins_mysql
			 WHERE goal_id=$get_current_goal_id AND goal_user_id='$get_my_user_id'");

			header("Location: my_goal_new.php?action=step_4_results&goal_id=$goal_id&l=$l&ft=success&fm=changes_saved");
			exit;

		}
		
		echo"
		<h2>$l_your_results</h2>

		

		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_edit_goal_current_bmr_calories\"]').focus();
		});
		</script>
		<form method=\"post\" action=\"my_goal_new.php?action=step_4_results&amp;goal_id=$goal_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

		<p>
		$l_to_keep_your_current_weight_you_should_eat
		<input type=\"text\" name=\"inp_edit_goal_current_sedentary_calories\" value=\"$get_current_goal_current_sedentary_calories\" size=\"4\" class=\"goal_result\" /> $l_calories_lowercase</b>
		$l_on_days_without_activity_lowercase 
		$l_with_activity_you_should_eat
		<input type=\"text\" name=\"inp_edit_goal_current_with_activity_calories\" value=\"$get_current_goal_current_with_activity_calories\" size=\"4\" class=\"goal_result\" />
		 $l_calories_lowercase</b>.
		</p>

		";
		if($get_current_goal_i_want_to == "loose_weight"){
			echo"
			<p>
			";
			if($get_user_measurement == "imperial"){
				echo"$l_to_loose_one_point_one_lbs_a_week";
			}
			else{
				echo"$l_to_loose_zero_point_five_kg_a_week";
			}
			echo"
			$l_your_body_should_have_lowercase 
			<input type=\"text\" name=\"inp_edit_goal_target_sedentary_calories\" value=\"$get_current_goal_target_sedentary_calories\" size=\"4\" class=\"goal_result\" />
			$l_calories_lowercase $l_without_activity_lowercase.
			$l_thats
			<input type=\"text\" name=\"inp_edit_goal_target_with_activity_calories\" value=\"$get_current_goal_target_with_activity_calories\" size=\"4\" class=\"goal_result\" />
			$l_calories_lowercase
			$l_with_activity_lowerase</p>
			";
		}
		elseif($get_current_goal_i_want_to == "gain_weight"){
			echo"
			<p>
			";
			if($get_user_measurement == "imperial"){
				echo"$l_to_gain_one_point_one_lbs_a_week";
			}
			else{
				echo"$l_to_gain_zero_point_five_kg_a_week";
			}
			echo"
			$l_your_body_should_have_lowercase 
			<input type=\"text\" name=\"inp_edit_goal_target_sedentary_calories\" value=\"$get_current_goal_target_sedentary_calories\" size=\"4\" class=\"goal_result\" />	
			$l_calories_lowercase $l_without_activity_lowercase.
			$l_thats
			<input type=\"text\" name=\"inp_edit_goal_target_with_activity_calories\" value=\"$get_current_goal_target_with_activity_calories\" size=\"4\" class=\"goal_result\" />	
			$l_calories_lowercase
			$l_with_activity_lowerase</p>
			";
		}
		echo"
		<p>
		<input type=\"submit\" value=\"$l_edit_numbers\" class=\"btn btn_default\" />
		<a href=\"index.php?l=$l\" class=\"btn btn_default\">$l_sounds_good -&gt;</a></p>
		</form>

		<hr />
		<h2>$l_detailed_results</h2>

			<table class=\"hor-zebra\">
			 <thead>
			  <tr>
			   <th scope=\"col\">
			   </th>
			   <th scope=\"col\">
				<span><b>$l_calories</b></span>
			   </th>
			   <th scope=\"col\">
				<span><b>$l_fat</b></span>
			   </th>
			   <th scope=\"col\">
				<span><b>$l_carbs</b></span>
			   </th>
			   <th scope=\"col\">
				<span><b>$l_proteins</b></span>
			   </th>
			  </tr>
			 </thead>
			 <tbody>
			  <tr>
			   <td colspan=\"5\">
				<span><b>BMR</b></span>
			   </td>
			  </tr>
			  <tr>
			   <td>
				<span>$l_to_keep_your_weight</span>
			   </td>
			   <td>
				<span><input type=\"text\" name=\"inp_goal_current_bmr_calories\" value=\"$get_current_goal_current_bmr_calories\" size=\"4\" class=\"no_frame\" /></span>
			   </td>
			   <td>
				<span><input type=\"text\" name=\"inp_goal_current_bmr_fat\" value=\"$get_current_goal_current_bmr_fat\" size=\"4\" class=\"no_frame\" /></span>
			   </td>
			   <td>
			 	<span><input type=\"text\" name=\"inp_goal_current_bmr_carbs\" value=\"$get_current_goal_current_bmr_carbs\" size=\"4\" class=\"no_frame\" /></span>
			   </td>
			   <td>
				<span><input type=\"text\" name=\"inp_goal_current_bmr_proteins\" value=\"$get_current_goal_current_bmr_proteins\" size=\"4\" class=\"no_frame\" /></span>
			   </td>
			  </tr>";
			if($get_current_goal_i_want_to == "loose_weight" OR $get_current_goal_i_want_to == "gain_weight"){
				 echo"
				 <tr>
				   <td>
					<span>"; 
					if($get_current_goal_i_want_to == "loose_weight"){
						if($get_user_measurement == "imperial"){
							echo"$l_to_loose_one_point_one_lbs_a_week";
						}
						else{
							echo"$l_to_loose_zero_point_five_kg_a_week";
						}
					}
					else{
						if($get_user_measurement == "imperial"){
							echo"$l_to_gain_one_point_one_lbs_a_week";
						}
						else{
							echo"$l_to_gain_zero_point_five_kg_a_week";
						}
					}

					echo"</span>
				   </td>
				   <td>
					<span><input type=\"text\" name=\"inp_goal_target_bmr_calories\" value=\"$get_current_goal_target_bmr_calories\" size=\"4\" class=\"no_frame\" /></span>
				   </td>
				   <td>
					<span><input type=\"text\" name=\"inp_goal_target_bmr_fat\" value=\"$get_current_goal_target_bmr_fat\" size=\"4\" class=\"no_frame\" /></span>
			 	  </td>
				   <td>
			 		<span><input type=\"text\" name=\"inp_goal_target_bmr_carbs\" value=\"$get_current_goal_target_bmr_carbs\" size=\"4\" class=\"no_frame\" /></span>
			 	  </td>
			 	  <td>
					<span><input type=\"text\" name=\"inp_goal_target_bmr_proteins\" value=\"$get_current_goal_target_bmr_proteins\" size=\"4\" class=\"no_frame\" /></span>
			 	  </td>
				  </tr>
				";
			}
			echo"
			  <tr>
			   <td colspan=\"5\">
				<span><b>$l_sedentary</b></span>
			   </td>
			  </tr>
			  <tr>
			   <td>
				<span>$l_to_keep_your_weight</span>
			   </td>
			   <td>
				<span><input type=\"text\" name=\"inp_goal_current_sedentary_calories\" value=\"$get_current_goal_current_sedentary_calories\" size=\"4\" class=\"no_frame\" /></span>
			   </td>
			   <td>
				<span><input type=\"text\" name=\"inp_goal_current_sedentary_fat\" value=\"$get_current_goal_current_sedentary_fat\" size=\"4\" class=\"no_frame\" /></span>
			   </td>
			   <td>
			 	<span><input type=\"text\" name=\"inp_goal_current_sedentary_carbs\" value=\"$get_current_goal_current_sedentary_carbs\" size=\"4\" class=\"no_frame\" /></span>
			   </td>
			   <td>
				<span><input type=\"text\" name=\"inp_goal_current_sedentary_proteins\" value=\"$get_current_goal_current_sedentary_proteins\" size=\"4\" class=\"no_frame\" /></span>
			   </td>
			  </tr>";
			if($get_current_goal_i_want_to == "loose_weight" OR $get_current_goal_i_want_to == "gain_weight"){
				 echo"
				 <tr>
				   <td>
					<span>"; 
					if($get_current_goal_i_want_to == "loose_weight"){
						if($get_user_measurement == "imperial"){
							echo"$l_to_loose_one_point_one_lbs_a_week";
						}
						else{
							echo"$l_to_loose_zero_point_five_kg_a_week";
						}
					}
					else{
						if($get_user_measurement == "imperial"){
							echo"$l_to_gain_one_point_one_lbs_a_week";
						}
						else{
							echo"$l_to_gain_zero_point_five_kg_a_week";
						}
					}

					echo"</span>
				   </td>
				   <td>
					<span><input type=\"text\" name=\"inp_goal_target_sedentary_calories\" value=\"$get_current_goal_target_sedentary_calories\" size=\"4\" class=\"no_frame\" /></span>
				   </td>
				   <td>
					<span><input type=\"text\" name=\"inp_goal_target_sedentary_fat\" value=\"$get_current_goal_target_sedentary_fat\" size=\"4\" class=\"no_frame\" /></span>
			 	  </td>
				   <td>
			 		<span><input type=\"text\" name=\"inp_goal_target_sedentary_carbs\" value=\"$get_current_goal_target_sedentary_carbs\" size=\"4\" class=\"no_frame\" /></span>
			 	  </td>
			 	  <td>
					<span><input type=\"text\" name=\"inp_goal_target_sedentary_proteins\" value=\"$get_current_goal_target_sedentary_proteins\" size=\"4\" class=\"no_frame\" /></span>
			 	  </td>
				  </tr>
				";
			}
			if($get_current_goal_activity_level != "1.2"){
				if($get_current_goal_activity_level == "1.375"){
					$activity_level_saying = "$l_lightly_active";
				}
				elseif($get_current_goal_activity_level == "1.55"){
					$activity_level_saying = "$l_moderatetely_active";
				}
				elseif($get_current_goal_activity_level == "1.72"){
					$activity_level_saying = "$l_very_active";
				}
				else{
					$activity_level_saying = "$l_extra_active";
				}

				echo"
				  <tr>
				   <td colspan=\"5\">
					<span><b>$activity_level_saying</b></span>
				   </td>
				  </tr>
				  <tr>
				   <td>
					<span>$l_to_keep_your_weight</span>
				   </td>
				   <td>
					<span><input type=\"text\" name=\"inp_goal_current_with_activity_calories\" value=\"$get_current_goal_current_with_activity_calories\" size=\"4\" class=\"no_frame\" /></span>
				   </td>
				   <td>
					<span><input type=\"text\" name=\"inp_goal_current_with_activity_fat\" value=\"$get_current_goal_current_with_activity_fat\" size=\"4\" class=\"no_frame\" /></span>
				   </td>
				   <td>
				 	<span><input type=\"text\" name=\"inp_goal_current_with_activity_carbs\" value=\"$get_current_goal_current_with_activity_carbs\" size=\"4\" class=\"no_frame\" /></span>
				   </td>
				   <td>
					<span><input type=\"text\" name=\"inp_goal_current_with_activity_proteins\" value=\"$get_current_goal_current_with_activity_proteins\" size=\"4\" class=\"no_frame\" /></span>
				   </td>
				  </tr>";
					if($get_current_goal_i_want_to == "loose_weight" OR $get_current_goal_i_want_to == "gain_weight"){
						 echo"
						 <tr>
						   <td>
							<span>"; 
							if($get_current_goal_i_want_to == "loose_weight"){
									if($get_user_measurement == "imperial"){
							echo"$l_to_loose_one_point_one_lbs_a_week";
							}
							else{
								echo"$l_to_loose_zero_point_five_kg_a_week";
							}
						}
						else{
							if($get_user_measurement == "imperial"){
								echo"$l_to_gain_one_point_one_lbs_a_week";
							}
							else{
								echo"$l_to_gain_zero_point_five_kg_a_week";
							}
						}

						echo"</span>
					   </td>
					   <td>
						<span><input type=\"text\" name=\"inp_goal_target_with_activity_calories\" value=\"$get_current_goal_target_with_activity_calories\" size=\"4\" class=\"no_frame\" /></span>
					   </td>
					   <td>
						<span><input type=\"text\" name=\"inp_goal_target_with_activity_fat\" value=\"$get_current_goal_target_with_activity_fat\" size=\"4\" class=\"no_frame\" /></span>
			 		  </td>
					   <td>
			 			<span><input type=\"text\" name=\"inp_goal_target_with_activity_carbs\" value=\"$get_current_goal_target_with_activity_carbs\" size=\"4\" class=\"no_frame\" /></span>
			 		  </td>
			 		  <td>
						<span><input type=\"text\" name=\"inp_goal_target_with_activity_proteins\" value=\"$get_current_goal_target_with_activity_proteins\" size=\"4\" class=\"no_frame\" /></span>
			 		  </td>
					  </tr>
					";
				}
			} // activity_level_over_1.2
			echo"
			 </tbody>
			</table>
			";
	}
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