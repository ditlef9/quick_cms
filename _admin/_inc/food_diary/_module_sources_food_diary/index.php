<?php
/**
*
* File: food_diary/index.php
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


if(isset($_GET['date'])) {
	$date = $_GET['date'];
	$date = strip_tags(stripslashes($date));
}
else{
	$date = date("Y-m-d");
}


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_food_diary";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");


/*- Content ---------------------------------------------------------------------------------- */
// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){

	// Get my profile
	$my_user_id = $_SESSION['user_id'];
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_alias, user_email, user_date_format FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_alias, $get_my_user_email, $get_my_user_date_format) = $row;


	// Do I have a goal?
	$query = "SELECT goal_id, goal_user_id, goal_current_weight, goal_current_fat_percentage, goal_target_weight, goal_target_fat_percentage, goal_i_want_to, goal_weekly_goal, goal_date, goal_activity_level, goal_current_bmi, goal_target_bmi, goal_current_bmr_calories, goal_current_bmr_fat, goal_current_bmr_carbs, goal_current_bmr_proteins, goal_current_sedentary_calories, goal_current_sedentary_fat, goal_current_sedentary_carbs, goal_current_sedentary_proteins, goal_current_with_activity_calories, goal_current_with_activity_fat, goal_current_with_activity_carbs, goal_current_with_activity_proteins, goal_target_bmr_calories, goal_target_bmr_fat, goal_target_bmr_carbs, goal_target_bmr_proteins, goal_target_sedentary_calories, goal_target_sedentary_fat, goal_target_sedentary_carbs, goal_target_sedentary_proteins, goal_target_with_activity_calories, goal_target_with_activity_fat, goal_target_with_activity_carbs, goal_target_with_activity_proteins, goal_synchronized, goal_notes FROM $t_food_diary_goals WHERE goal_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_goal_id, $get_goal_user_id, $get_goal_current_weight, $get_goal_current_fat_percentage, $get_goal_target_weight, $get_goal_target_fat_percentage, $get_goal_i_want_to, $get_goal_weekly_goal, $get_goal_date, $get_goal_activity_level, $get_goal_current_bmi, $get_goal_target_bmi, $get_goal_current_bmr_calories, $get_goal_current_bmr_fat, $get_goal_current_bmr_carbs, $get_goal_current_bmr_proteins, $get_goal_current_sedentary_calories, $get_goal_current_sedentary_fat, $get_goal_current_sedentary_carbs, $get_goal_current_sedentary_proteins, $get_goal_current_with_activity_calories, $get_goal_current_with_activity_fat, $get_goal_current_with_activity_carbs, $get_goal_current_with_activity_proteins, $get_goal_target_bmr_calories, $get_goal_target_bmr_fat, $get_goal_target_bmr_carbs, $get_goal_target_bmr_proteins, $get_goal_target_sedentary_calories, $get_goal_target_sedentary_fat, $get_goal_target_sedentary_carbs, $get_goal_target_sedentary_proteins, $get_goal_target_with_activity_calories, $get_goal_target_with_activity_fat, $get_goal_target_with_activity_carbs, $get_goal_target_with_activity_proteins, $get_goal_synchronized, $get_goal_notes) = $row;
	if($get_goal_id == ""){
		echo"
		<h1>
		<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
		$l_please_set_your_goal...</h1>
		<meta http-equiv=\"refresh\" content=\"1;url=my_goal_new.php?l=$l\">
		";
	}
	else{
		// Variables
		$date_mysql = quote_smart($link, $date);


		echo"

		<!-- Food diary Quick menu -->
			<div style=\"float: right;padding-top: 8px;\">
				<p>
				<a href=\"$root/food_diary/my_goal.php?l=$l\" class=\"btn_default\">$l_my_goal</a>
				<a href=\"$root/food_diary/my_profile_data.php?l=$l\" class=\"btn_default\">$l_my_profile_data</a>
				</p>
			</div>
		<!-- //Food diary Quick menu -->
	
		<h1>$l_food_diary</h1>


		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "food_added"){
					// What food was added?
					$query = "SELECT entry_id, entry_name, entry_manufacturer_name, entry_serving_size, entry_serving_size_measurement FROM $t_food_diary_entires WHERE entry_user_id=$my_user_id_mysql ORDER BY entry_id DESC LIMIT 0,1";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_entry_id, $get_entry_name, $get_entry_manufacturer_name, $get_entry_serving_size, $get_entry_serving_size_measurement) = $row;
					
					$fm = "$get_entry_serving_size $get_entry_serving_size_measurement";
					if($get_entry_manufacturer_name != ""){
						$fm = $fm . " $get_entry_manufacturer_name ";
					}
					
					$fm = $fm . " $get_entry_name $l_added_to_your_diary_lowercase.";

				}
				else{
					$fm = str_replace("_", " ", $fm);
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->


		<!-- Todays Consumed Totals -->";

			$today = date("Y-m-d");
			if($date == "$today"){
				$timestamp = time();
			}
			else{
				$timestamp = strtotime($date);
			}

			$yesterday = mktime(0, 0, 0, date("m", $timestamp), date("d", $timestamp)-1, date("Y", $timestamp));
			$yesterday = date('Y-m-d', $yesterday);

			$now_array = explode("-", $date);
			

			$month = $now_array[1];
			if($month > 12 OR !(is_numeric($month))){
				echo"error"; die;
			}
			
			if($month == "01"){
				$month_saying = $l_january;
			}
			elseif($month == "02"){
				$month_saying = $l_february;
			}
			elseif($month == "03"){
				$month_saying = $l_march;
			}
			elseif($month == "04"){
				$month_saying = $l_april;
			}
			elseif($month == "05"){
				$month_saying = $l_may;
			}
			elseif($month == "06"){
				$month_saying = $l_june;
			}
			elseif($month == "07"){
				$month_saying = $l_july;
			}
			elseif($month == "08"){
				$month_saying = $l_august;
			}
			elseif($month == "09"){
				$month_saying = $l_september;
			}
			elseif($month == "10"){
				$month_saying = $l_october;
			}
			elseif($month == "11"){
				$month_saying = $l_november;
			}
			else{
				$month_saying = $l_december;
			}


			if(isset($now_array[2])){
				$day = $now_array[2];
			}
			else{
				echo"error"; die;
			}
			if($day < 10){
				$day = substr($day, 1, 1);
			}
			$year = $now_array[0];

			$tomorrow = mktime(0, 0, 0, date("m", $timestamp), date("d", $timestamp)+1, date("Y", $timestamp));
			$tomorrow = date('Y-m-d', $tomorrow);


			$query = "SELECT consumed_day_id, consumed_day_user_id, consumed_day_year, consumed_day_month, consumed_day_month_saying, consumed_day_day, consumed_day_day_saying, consumed_day_date, consumed_day_lifestyle, consumed_day_energy, consumed_day_fat, consumed_day_saturated_fat, consumed_day_monounsaturated_fat, consumed_day_polyunsaturated_fat, consumed_day_cholesterol, consumed_day_carbohydrates, consumed_day_carbohydrates_of_which_sugars, consumed_day_dietary_fiber, consumed_day_proteins, consumed_day_salt, consumed_day_sodium, consumed_day_target_sedentary_energy, consumed_day_target_sedentary_fat, consumed_day_target_sedentary_carb, consumed_day_target_sedentary_protein, consumed_day_target_with_activity_energy, consumed_day_target_with_activity_fat, consumed_day_target_with_activity_carb, consumed_day_target_with_activity_protein, consumed_day_diff_sedentary_energy, consumed_day_diff_sedentary_fat, consumed_day_diff_sedentary_carb, consumed_day_diff_sedentary_protein, consumed_day_diff_with_activity_energy, consumed_day_diff_with_activity_fat, consumed_day_diff_with_activity_carb, consumed_day_diff_with_activity_protein, consumed_day_updated_datetime, consumed_day_synchronized FROM $t_food_diary_consumed_days WHERE consumed_day_user_id=$my_user_id_mysql AND consumed_day_date=$date_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_consumed_day_id, $get_consumed_day_user_id, $get_consumed_day_year, $get_consumed_day_month, $get_consumed_day_month_saying, $get_consumed_day_day, $get_consumed_day_day_saying, $get_consumed_day_date, $get_consumed_day_lifestyle, $get_consumed_day_energy, $get_consumed_day_fat, $get_consumed_day_saturated_fat, $get_consumed_day_monounsaturated_fat, $get_consumed_day_polyunsaturated_fat, $get_consumed_day_cholesterol, $get_consumed_day_carbohydrates, $get_consumed_day_carbohydrates_of_which_sugars, $get_consumed_day_dietary_fiber, $get_consumed_day_proteins, $get_consumed_day_salt, $get_consumed_day_sodium, $get_consumed_day_target_sedentary_energy, $get_consumed_day_target_sedentary_fat, $get_consumed_day_target_sedentary_carb, $get_consumed_day_target_sedentary_protein, $get_consumed_day_target_with_activity_energy, $get_consumed_day_target_with_activity_fat, $get_consumed_day_target_with_activity_carb, $get_consumed_day_target_with_activity_protein, $get_consumed_day_diff_sedentary_energy, $get_consumed_day_diff_sedentary_fat, $get_consumed_day_diff_sedentary_carb, $get_consumed_day_diff_sedentary_protein, $get_consumed_day_diff_with_activity_energy, $get_consumed_day_diff_with_activity_fat, $get_consumed_day_diff_with_activity_carb, $get_consumed_day_diff_with_activity_protein, $get_consumed_day_updated_datetime, $get_consumed_day_synchronized) = $row;
			if($get_consumed_day_id == ""){
				// Insert this day
				$year = date("Y");
				$month = date("m");
				$month_saying = date("M");
				$day = date("d");
				$day_saying = date("D");
				$date = date("Y-m-d");
				$datetime = date("Y-m-d H:i:s");

				// What do we normaly do on this day?
				$query = "SELECT lifestyle_id, lifestyle_user_id, lifestyle_count_active_mon, lifestyle_count_active_tue, lifestyle_count_active_wed, lifestyle_count_active_thu, lifestyle_count_active_fri, lifestyle_count_active_sat, lifestyle_count_active_sun, lifestyle_count_sedentary_mon, lifestyle_count_sedentary_tue, lifestyle_count_sedentary_wed, lifestyle_count_sedentary_thu, lifestyle_count_sedentary_fri, lifestyle_count_sedentary_sat, lifestyle_count_sedentary_sun FROM $t_food_diary_lifestyle_selected_per_day WHERE lifestyle_user_id=$get_my_user_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_lifestyle_id, $get_current_lifestyle_user_id, $get_current_lifestyle_count_active_mon, $get_current_lifestyle_count_active_tue, $get_current_lifestyle_count_active_wed, $get_current_lifestyle_count_active_thu, $get_current_lifestyle_count_active_fri, $get_current_lifestyle_count_active_sat, $get_current_lifestyle_count_active_sun, $get_current_lifestyle_count_sedentary_mon, $get_current_lifestyle_count_sedentary_tue, $get_current_lifestyle_count_sedentary_wed, $get_current_lifestyle_count_sedentary_thu, $get_current_lifestyle_count_sedentary_fri, $get_current_lifestyle_count_sedentary_sat, $get_current_lifestyle_count_sedentary_sun) = $row;
			
				$inp_lifestyle = 1;
				if($day_saying == "Mon"){
					if($get_current_lifestyle_count_active_mon < $get_current_lifestyle_count_sedentary_mon){
						$inp_lifestyle = 0;
					}
				}
				elseif($day_saying == "Tue"){
					if($get_current_lifestyle_count_active_tue < $get_current_lifestyle_count_sedentary_tue){
						$inp_lifestyle = 0;
					}
				}
				elseif($day_saying == "Wed"){
					if($get_current_lifestyle_count_active_wed < $get_current_lifestyle_count_sedentary_wed){
						$inp_lifestyle = 0;
					}
				}
				elseif($day_saying == "Thu"){
					if($get_current_lifestyle_count_active_thu < $get_current_lifestyle_count_sedentary_thu){
						$inp_lifestyle = 0;
					}
				}
				elseif($day_saying == "Fri"){
					if($get_current_lifestyle_count_active_fri < $get_current_lifestyle_count_sedentary_fri){
						$inp_lifestyle = 0;
					}
				}
				elseif($day_saying == "Sat"){
					if($get_current_lifestyle_count_active_sat < $get_current_lifestyle_count_sedentary_sat){
						$inp_lifestyle = 0;
					}
				}
				elseif($day_saying == "Sun"){
					if($get_current_lifestyle_count_active_sun < $get_current_lifestyle_count_sedentary_sun){
						$inp_lifestyle = 0;
					}
				}
				else{
					echo"Unknown day";
				}
				
				mysqli_query($link, "INSERT INTO $t_food_diary_consumed_days
				(consumed_day_id, consumed_day_user_id, consumed_day_year, consumed_day_month, consumed_day_month_saying, 
				consumed_day_day, consumed_day_day_saying, consumed_day_date, consumed_day_lifestyle, consumed_day_energy, consumed_day_fat, 
				consumed_day_saturated_fat, consumed_day_monounsaturated_fat, consumed_day_polyunsaturated_fat, consumed_day_cholesterol, consumed_day_carbohydrates, 
				consumed_day_carbohydrates_of_which_sugars, consumed_day_dietary_fiber, consumed_day_proteins, consumed_day_salt, consumed_day_sodium, 
				consumed_day_target_sedentary_energy, consumed_day_target_sedentary_fat, consumed_day_target_sedentary_carb, consumed_day_target_sedentary_protein, consumed_day_target_with_activity_energy, 
				consumed_day_target_with_activity_fat, consumed_day_target_with_activity_carb, consumed_day_target_with_activity_protein, consumed_day_diff_sedentary_energy, consumed_day_diff_sedentary_fat, 
				consumed_day_diff_sedentary_carb, consumed_day_diff_sedentary_protein, consumed_day_diff_with_activity_energy, consumed_day_diff_with_activity_fat, consumed_day_diff_with_activity_carb, 
				consumed_day_diff_with_activity_protein, consumed_day_updated_datetime, consumed_day_synchronized) 
				VALUES 
				(NULL, $my_user_id_mysql, $year, $month, '$month_saying',
				$day, '$day_saying', '$date', $inp_lifestyle, '0', '0',
				 '0', '0',  '0', '0',  '0',
				 '0', '0',  '0', '0',  '0',
			
				'$get_goal_target_sedentary_calories', '$get_goal_target_sedentary_fat', '$get_goal_target_sedentary_carbs', '$get_goal_target_sedentary_proteins',
				'$get_goal_target_with_activity_calories', '$get_goal_target_with_activity_fat', '$get_goal_target_with_activity_carbs', '$get_goal_target_with_activity_proteins',
				'$get_goal_target_sedentary_calories', '$get_goal_target_sedentary_fat', '$get_goal_target_sedentary_carbs', '$get_goal_target_sedentary_proteins',
				'$get_goal_target_with_activity_calories', '$get_goal_target_with_activity_fat', '$get_goal_target_with_activity_carbs', 
				'$get_goal_target_with_activity_proteins', '$datetime', 0
				)")
				or die(mysqli_error($link));
				// echo"Ny dag, nye muligheter";

				// Get data
				$query = "SELECT consumed_day_id, consumed_day_user_id, consumed_day_year, consumed_day_month, consumed_day_month_saying, consumed_day_day, consumed_day_day_saying, consumed_day_date, consumed_day_lifestyle, consumed_day_energy, consumed_day_fat, consumed_day_saturated_fat, consumed_day_monounsaturated_fat, consumed_day_polyunsaturated_fat, consumed_day_cholesterol, consumed_day_carbohydrates, consumed_day_carbohydrates_of_which_sugars, consumed_day_dietary_fiber, consumed_day_proteins, consumed_day_salt, consumed_day_sodium, consumed_day_target_sedentary_energy, consumed_day_target_sedentary_fat, consumed_day_target_sedentary_carb, consumed_day_target_sedentary_protein, consumed_day_target_with_activity_energy, consumed_day_target_with_activity_fat, consumed_day_target_with_activity_carb, consumed_day_target_with_activity_protein, consumed_day_diff_sedentary_energy, consumed_day_diff_sedentary_fat, consumed_day_diff_sedentary_carb, consumed_day_diff_sedentary_protein, consumed_day_diff_with_activity_energy, consumed_day_diff_with_activity_fat, consumed_day_diff_with_activity_carb, consumed_day_diff_with_activity_protein, consumed_day_updated_datetime, consumed_day_synchronized FROM $t_food_diary_consumed_days WHERE consumed_day_user_id=$my_user_id_mysql AND consumed_day_date=$date_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_consumed_day_id, $get_consumed_day_user_id, $get_consumed_day_year, $get_consumed_day_month, $get_consumed_day_month_saying, $get_consumed_day_day, $get_consumed_day_day_saying, $get_consumed_day_date, $get_consumed_day_lifestyle, $get_consumed_day_energy, $get_consumed_day_fat, $get_consumed_day_saturated_fat, $get_consumed_day_monounsaturated_fat, $get_consumed_day_polyunsaturated_fat, $get_consumed_day_cholesterol, $get_consumed_day_carbohydrates, $get_consumed_day_carbohydrates_of_which_sugars, $get_consumed_day_dietary_fiber, $get_consumed_day_proteins, $get_consumed_day_salt, $get_consumed_day_sodium, $get_consumed_day_target_sedentary_energy, $get_consumed_day_target_sedentary_fat, $get_consumed_day_target_sedentary_carb, $get_consumed_day_target_sedentary_protein, $get_consumed_day_target_with_activity_energy, $get_consumed_day_target_with_activity_fat, $get_consumed_day_target_with_activity_carb, $get_consumed_day_target_with_activity_protein, $get_consumed_day_diff_sedentary_energy, $get_consumed_day_diff_sedentary_fat, $get_consumed_day_diff_sedentary_carb, $get_consumed_day_diff_sedentary_protein, $get_consumed_day_diff_with_activity_energy, $get_consumed_day_diff_with_activity_fat, $get_consumed_day_diff_with_activity_carb, $get_consumed_day_diff_with_activity_protein, $get_consumed_day_updated_datetime, $get_consumed_day_synchronized) = $row;

			}
		echo"
		<!-- //Todays Consumed Totals -->

		<!-- Yesterday, tomorrow -->
			";

			// Print todays date
			

			echo"
			<table style=\"width: 100%;\">
			 <tr>
			  <td style=\"padding: 0px 0px 18px 8px;\">
				<a href=\"index.php?action=food_diary&amp;date=$yesterday\"><img src=\"_gfx/arrow_left.jpg\" alt=\"arrow_left.jpg\" /></a>
			  </td>
			  <td style=\"padding: 0px 0px 18px 0px;text-align: center;\">
				<span>$day $month_saying $year</span>
			  </td>
			  <td style=\"text-align: right;padding: 0px 8px 18px 0px;\">
				<a href=\"index.php?action=food_diary&amp;date=$tomorrow\"><img src=\"_gfx/arrow_right.jpg\" alt=\"arrow_right.jpg\" /></a>
			  </td>
			 </tr>
			</table>
	<!-- //Yesterday, tomorrow -->



	<!-- Rest -->
		";
		if($get_consumed_day_id != ""){
			echo"

			<!-- sedentary -->
				<table style=\"width: 100%;\">
				 <tr>
				  <td style=\"text-align:center;\">
					<span style=\"font-weight:bold;\">$l_lifestyle</span>
				  </td>
				  <td style=\"text-align:center;\">
					<span style=\"font-weight:bold;\">$l_target</span>
				  </td>
				  <td>
				  </td>
				  <td style=\"text-align:center;\">
					<span style=\"font-weight:bold;\">$l_food</span>
				  </td>
				  <td>
				  </td>
				  <td style=\"text-align:center;\">
					<span style=\"font-weight:bold;\">$l_remaining</span>
				  </td>
				 </tr>
				 <tr>
				  <td style=\"text-align:center;\">
					<form method=\"post\" id=\"change_lifestyle_form\" action=\"consumed_day_has_lifestyle_edit.php?day_id=$get_consumed_day_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
					<span>
					<input type=\"radio\" name=\"inp_lifestyle\" value=\"1\""; if($get_consumed_day_lifestyle == "1"){ echo" checked=\"checked\""; } echo" /> $l_active<br />
					<input type=\"radio\" name=\"inp_lifestyle\" value=\"0\""; if($get_consumed_day_lifestyle == "0"){ echo" checked=\"checked\""; } echo" /> $l_sedentary<br />
					</span>
					</form>
					<!-- On check radio send form -->
						<script>
						\$(document).ready( function() {
							\$('input[type=radio]').on('change', function() {
								\$(this).closest(\"form\").submit();
							});
						});
						</script>
					<!-- //On check radio send form -->
					
				  </td>
				  <td style=\"text-align:center;\">
					<span>
					$get_consumed_day_target_with_activity_energy<br />
					$get_consumed_day_target_sedentary_energy<br />
					</span>
				  </td>
				  <td style=\"text-align:center;\">
					<span>-</span>
				  </td>
				  <td style=\"text-align:center;\">
					<span>$get_consumed_day_energy</span>
				  </td>
				  <td>
					<span>=</span>
				  </td>
				  <td style=\"text-align:center;\">
					<span";
					if($get_consumed_day_energy > "$get_consumed_day_target_with_activity_energy"){
						echo" style=\"color:red;\"";
					}
					else{
						if($get_consumed_day_diff_with_activity_energy < 100){
							echo" style=\"color: #996600;\"";
						}
						else{
							echo" style=\"color: green;\"";
						}
					}
					echo">$get_consumed_day_diff_with_activity_energy<br /></span>

					<span";
					if($get_consumed_day_energy > "$get_consumed_day_target_sedentary_energy"){
						echo" style=\"color:red;\"";
					}
					else{
						if($get_consumed_day_diff_sedentary_energy < 100){
							echo" style=\"color: #996600;\"";
						}
						else{
							echo" style=\"color: green;\"";
						}
					}
					echo">$get_consumed_day_diff_sedentary_energy<br /></span>
					
				  </td>
				 </tr>
				</table>


			";
		}
		echo"	
	<!-- //Rest -->

	<!-- Meals -->
	";
	// Start loop
	$hour_names = array("breakfast", "lunch", "before_training", "after_training", "linner", "dinner", "snacks", "before_supper", "supper", "night_meal");
	$hour_names_translated = array("$l_breakfast", "$l_lunch", "$l_before_training", "$l_after_training", "$l_linner", "$l_dinner", "$l_snacks", "$l_before_supper", "$l_supper", "$l_night_meal");
	$date_mysql = quote_smart($link, $date);
	for($x=0;$x<10;$x++){
		// Find out how many calories I have eaten for this meal
		$query_c = "SELECT consumed_hour_id, consumed_hour_user_id, consumed_hour_date, consumed_hour_name, consumed_hour_energy, consumed_hour_fat, consumed_hour_saturated_fat, consumed_hour_monounsaturated_fat, consumed_hour_polyunsaturated_fat, consumed_hour_cholesterol, consumed_hour_carbohydrates, consumed_hour_carbohydrates_of_which_sugars, consumed_hour_dietary_fiber, consumed_hour_proteins, consumed_hour_salt, consumed_hour_sodium, consumed_hour_updated_datetime, consumed_hour_synchronized FROM $t_food_diary_consumed_hours WHERE consumed_hour_user_id=$my_user_id_mysql AND consumed_hour_date=$date_mysql AND consumed_hour_name='$hour_names[$x]'";
		$result_c = mysqli_query($link, $query_c);
		$row_c = mysqli_fetch_row($result_c);
		list($get_consumed_hour_id, $get_consumed_hour_user_id, $get_consumed_hour_date, $get_consumed_hour_name, $get_consumed_hour_energy, $get_consumed_hour_fat, $get_consumed_hour_saturated_fat, $get_consumed_hour_monounsaturated_fat, $get_consumed_hour_polyunsaturated_fat, $get_consumed_hour_cholesterol, $get_consumed_hour_carbohydrates, $get_consumed_hour_carbohydrates_of_which_sugars, $get_consumed_hour_dietary_fiber, $get_consumed_hour_proteins, $get_consumed_hour_salt, $get_consumed_hour_sodium, $get_consumed_hour_updated_datetime, $get_consumed_hour_synchronized) = $row_c;
		if($get_consumed_hour_id == ""){
			// Insert this hour
			mysqli_query($link, "INSERT INTO $t_food_diary_consumed_hours
			(consumed_hour_id, consumed_hour_user_id, consumed_hour_date, consumed_hour_name, consumed_hour_energy, 
			consumed_hour_fat, consumed_hour_saturated_fat, consumed_hour_monounsaturated_fat, consumed_hour_polyunsaturated_fat, consumed_hour_cholesterol, 
			consumed_hour_carbohydrates, consumed_hour_carbohydrates_of_which_sugars, consumed_hour_dietary_fiber, consumed_hour_proteins, consumed_hour_salt, 
			consumed_hour_sodium, consumed_hour_updated_datetime, consumed_hour_synchronized) 
			VALUES 
			(NULL, $my_user_id_mysql, $date_mysql, '$hour_names[$x]', '0', 
			'0', '0', '0', '0', '0',
			'0', '0', '0', '0', '0',
			'0', '$datetime', 0)")
			or die(mysqli_error($link));
		}

		echo"
		<div style=\"height: 8px;\"></div>
		<a id=\"hour_$hour_names[$x]\"></a>
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\" id=\"thhour_$hour_names[$x]\">
			";
			if($date == "$today"){
				echo"
				<span style=\"font-weight: bold;\"><a href=\"food_diary_add.php?date=$date&amp;hour_name=$hour_names[$x]&amp;l=$l\"><img src=\"_gfx/list-add.png\" alt=\"list-add.png\" style=\"float: left;padding: 1px 4px 0px 0px;\" /></a></span>  
				  
					<script>
					\$(document).ready(function(){
						\$('#thhour_$hour_names[$x]').click(function(){
							window.location= \"food_diary_add.php?date=$date&hour_name=$hour_names[$x]&l=$l\";
						});
					});
					</script>


					<span><a href=\"food_diary_add.php?date=$date&amp;hour_name=$hour_names[$x]&amp;l=$l\" style=\"font-weight: bold;color: #000;\">$hour_names_translated[$x]</a></span>  
				  ";
			}
			else{
				echo"
				<span style=\"font-weight: bold;color: #000;\">$hour_names_translated[$x]</span>  
				";
			}
			echo"
		   </th>
		   <th style=\"text-align: right;vertical-align: top;padding: 0px 4px 0px 0px;\">
			<span style=\"font-weight: bold;\">$get_consumed_hour_energy</span>  
		   </th>
		  </tr>
		 </thead>
		 <tbody>
		";
	
		$inp_consumed_hour_energy = 0;
		$query = "SELECT entry_id, entry_user_id, entry_date, entry_hour_name, entry_food_id, entry_recipe_id, entry_name, entry_manufacturer_name, entry_serving_size, entry_serving_size_measurement, entry_energy_per_entry, entry_fat_per_entry, entry_saturated_fat_per_entry, entry_monounsaturated_fat_per_entry, entry_polyunsaturated_fat_per_entry, entry_cholesterol_per_entry, entry_carbohydrates_per_entry, entry_carbohydrates_of_which_sugars_per_entry, entry_dietary_fiber_per_entry, entry_proteins_per_entry, entry_salt_per_entry, entry_sodium_per_entry, entry_text, entry_deleted, entry_updated_datetime, entry_synchronized FROM $t_food_diary_entires WHERE entry_user_id=$my_user_id_mysql AND entry_date=$date_mysql AND entry_hour_name='$hour_names[$x]'";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
    			list($get_entry_id, $get_entry_user_id, $get_entry_date, $get_entry_hour_name, $get_entry_food_id, $get_entry_recipe_id, $get_entry_name, $get_entry_manufacturer_name, $get_entry_serving_size, $get_entry_serving_size_measurement, $get_entry_energy_per_entry, $get_entry_fat_per_entry, $get_entry_saturated_fat_per_entry, $get_entry_monounsaturated_fat_per_entry, $get_entry_polyunsaturated_fat_per_entry, $get_entry_cholesterol_per_entry, $get_entry_carbohydrates_per_entry, $get_entry_carbohydrates_of_which_sugars_per_entry, $get_entry_dietary_fiber_per_entry, $get_entry_proteins_per_entry, $get_entry_salt_per_entry, $get_entry_sodium_per_entry, $get_entry_text, $get_entry_deleted, $get_entry_updated_datetime, $get_entry_synchronized) = $row;
			if(isset($style) && $style == ""){
				$style = "odd";
			}
			else{
				$style = "";
			}


				
			echo"
			  <tr>
			   <td class=\"$style\" id=\"a_entry_$get_entry_id\">
				<script>
				\$(document).ready(function(){
					\$('#a_entry_$get_entry_id').click(function(){
						window.location= \"\";
					});
					\$('#a_entry_$get_entry_id').click(function(){
						window.location= \"food_diary_edit_entry.php?entry_id=$get_entry_id\";
					});
				});
				</script>
					
				<span>
				$get_entry_serving_size  $get_entry_serving_size_measurement
				<a href=\"food_diary_edit_entry.php?entry_id=$get_entry_id\">";
				if($get_entry_manufacturer_name != ""){
					echo"$get_entry_manufacturer_name ";
				}

				echo"$get_entry_name</a>
				</span>
			  </td>
			  <td class=\"$style\" style=\"text-align:right;vertical-align: top;padding: 0px 4px 0px 0px;\"  id=\"b_entry_$get_entry_id\">
				<span>$get_entry_energy_per_entry</span>  
			  </td>
			 </tr>";

			$inp_consumed_hour_energy = $inp_consumed_hour_energy+$get_entry_energy_per_entry;
		} // entries
		// Control check $get_consumed_hour_energy
		if($get_consumed_hour_energy != "$inp_consumed_hour_energy"){
			mysqli_query($link, "UPDATE $t_food_diary_consumed_hours SET consumed_hour_energy=$inp_consumed_hour_energy WHERE consumed_hour_user_id=$my_user_id_mysql AND consumed_hour_date=$date_mysql AND consumed_hour_name='$hour_names[$x]'") or die(mysqli_error($link));
		}
		echo"
		 </tbody>
		</table>
		";
		
		
	} // meals
	echo"
	<!-- //Meals -->

	<!-- Summary -->

		<div style=\"height: 8px;\"></div>
		<table style=\"width: 100%\">
		 <tr>
		  <td class=\"outline\">
			<table style=\"border-spacing: 1px;width: 100%;\">
			 <tr>
			  <td class=\"bodycell\" style=\"padding: 4px;\">
				<span style=\"font-weight: bold;\">$l_summary</span>
			  </td>
			 </tr>
			 <tr>
			  <td class=\"subcell\" style=\"padding: 4px;\">
				";

				$total_fat_carb_proteins = $get_consumed_day_fat+$get_consumed_day_carbohydrates+$get_consumed_day_proteins;
				if($get_consumed_day_id != "" && $get_consumed_day_fat != "0"){
					$get_consumed_day_fat_percentage = round(($get_consumed_day_fat/$total_fat_carb_proteins)*100, 0);
				}
				else{
					$get_consumed_day_fat_percentage = 0;
				}
				if($get_consumed_day_id != "" && $get_consumed_day_carbohydrates != "0"){
					$get_consumed_day_carb_percentage = round(($get_consumed_day_carbohydrates/$total_fat_carb_proteins)*100, 0);
				}
				else{
					$get_consumed_day_carb_percentage = 0;
				}
				
				if($get_consumed_day_id != "" && $get_consumed_day_proteins != "0"){
					$get_consumed_day_protein_percentage = round(($get_consumed_day_proteins/$total_fat_carb_proteins)*100, 0);
				}
				else{
					$get_consumed_day_protein_percentage = 0;
				}
				$sum_consumed_fat_carb_proteins_percentage  = $get_consumed_day_fat_percentage + $get_consumed_day_carb_percentage + $get_consumed_day_protein_percentage;
				if($sum_consumed_fat_carb_proteins_percentage != "0" && $sum_consumed_fat_carb_proteins_percentage != "100"){
					 $get_consumed_day_protein_percentage = $get_consumed_day_protein_percentage+1;
				}

				echo"
				<table style=\"width: 100%\">
				 <tr>
				  <td style=\"vertical-align: top;\">
					<table>
					 <tr>
					  <td style=\"padding: 0px 4px 0px 0px;\">
						<span style=\"font-weight: bold;\">$l_energy:</span>
					  </td>
					  <td>
						<span>$get_consumed_day_energy&nbsp;$l_kcal_lowercase</span>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"padding: 0px 4px 0px 0px;\">
						<span style=\"font-weight: bold;\">$l_fat:</span>
					  </td>
					  <td>
						<span>$get_consumed_day_fat</span>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"padding: 0px 4px 0px 0px;\">
						<span style=\"font-weight: bold;\">$l_carb:</span>
					  </td>
					  <td>
						<span>$get_consumed_day_carbohydrates</span>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"padding: 0px 4px 0px 0px;\">
						<span style=\"font-weight: bold;\">$l_proteins:</span>
					  </td>
					  <td>
						<span>$get_consumed_day_proteins</span>
					  </td>
					 </tr>
					</table>
				  </td>
				  <td style=\"text-align: right;width:100px;padding-top: 6px;\">
					<span>
					<img src=\"_gfx/pie_chart.php?numbers=$get_consumed_day_protein_percentage,$get_consumed_day_carb_percentage,$get_consumed_day_fat_percentage\" alt=\"pie_chart.php\" style=\"padding-bottom: 4px;\" />
					
					</span>
				  </td>
				  <td style=\"vertical-align: top;text-align: right;width:170px;padding-top: 16px;\">
					<table style=\"width: 100%\">
					 <tr>
					  <td style=\"padding: 0px 4px 0px 0px;\">
						<span>$l_fat ($get_consumed_day_fat_percentage %)</span>
					  </td>
					  <td>
						<span style=\"font-weight: bold;\"><img src=\"_gfx/dot_blue.png\" alt=\"dot_blue.png\" /></span>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"padding: 0px 4px 0px 0px;\">
						<span>$l_carbs ($get_consumed_day_carb_percentage %)</span>
					  </td>
					  <td>
						<span style=\"font-weight: bold;\"><img src=\"_gfx/dot_red.png\" alt=\"dot_red.png\" /></span>
					  </td>
					 </tr>
					 <tr>
					  <td style=\"padding: 0px 4px 0px 0px;\">
						<span>$l_proteins ($get_consumed_day_protein_percentage %)</span>
					  </td>
					  <td>
						<span style=\"font-weight: bold;\"><img src=\"_gfx/dot_green.png\" alt=\"dot_green.png\" /></span>
					  </td>
					 </tr>
					</table>
				  </td>
				 </tr>
				</table>
			  </td>
			 </tr>
			</table>
		  </td>
		 </tr>
		</table>
		<!-- //Summary -->


		<!-- Yesterdays meals as meals -->
			";
			$yesterday_mysql = quote_smart($link, $yesterday);
			for($x=0;$x<10;$x++){
				$inp_hour_name_mysql = quote_smart($link, $hour_names[$x]);
				$check_meal_entries = "";
				$inp_meal_entries_count= 0;
				$inp_meal_energy_total = 0;
				$inp_meal_fat_total = 0;
				$inp_meal_saturated_total = 0;
				$inp_meal_monounsaturated_fat_total = 0;
				$inp_meal_polyunsaturated_fat_total = 0;
				$inp_meal_cholesterol_total = 0;
				$inp_meal_carbohydrates_total = 0;
				$inp_meal_carbohydrates_of_which_sugars_total = 0;
				$inp_meal_dietary_fiber_total = 0;
				$inp_meal_proteins_total = 0;
				$inp_meal_salt_total = 0;
				$inp_meal_sodium_total = 0;
				
				$query = "SELECT entry_id, entry_user_id, entry_date, entry_hour_name, entry_food_id, entry_recipe_id, entry_name, entry_manufacturer_name, entry_serving_size, entry_serving_size_measurement, entry_energy_per_entry, entry_fat_per_entry, entry_saturated_fat_per_entry, entry_monounsaturated_fat_per_entry, entry_polyunsaturated_fat_per_entry, entry_cholesterol_per_entry, entry_carbohydrates_per_entry, entry_carbohydrates_of_which_sugars_per_entry, entry_dietary_fiber_per_entry, entry_proteins_per_entry, entry_salt_per_entry, entry_sodium_per_entry, entry_text, entry_deleted, entry_updated_datetime, entry_synchronized FROM $t_food_diary_entires WHERE entry_user_id=$my_user_id_mysql AND entry_date=$yesterday_mysql AND entry_hour_name=$inp_hour_name_mysql";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
    					list($get_entry_id, $get_entry_user_id, $get_entry_date, $get_entry_hour_name, $get_entry_food_id, $get_entry_recipe_id, $get_entry_name, $get_entry_manufacturer_name, $get_entry_serving_size, $get_entry_serving_size_measurement, $get_entry_energy_per_entry, $get_entry_fat_per_entry, $get_entry_saturated_fat_per_entry, $get_entry_monounsaturated_fat_per_entry, $get_entry_polyunsaturated_fat_per_entry, $get_entry_cholesterol_per_entry, $get_entry_carbohydrates_per_entry, $get_entry_carbohydrates_of_which_sugars_per_entry, $get_entry_dietary_fiber_per_entry, $get_entry_proteins_per_entry, $get_entry_salt_per_entry, $get_entry_sodium_per_entry, $get_entry_text, $get_entry_deleted, $get_entry_updated_datetime, $get_entry_synchronized) = $row;

					// We have food or recipe from yesterday at hour x, lets look for that as a meal
					if($check_meal_entries == ""){
						if($get_entry_food_id != "0" && $get_entry_recipe_id == "0"){
							$check_meal_entries = "f$get_entry_food_id";
						}
						elseif($get_entry_food_id == "0" && $get_entry_recipe_id != "0"){
							$check_meal_entries = "r$get_entry_recipe_id";
						}
					}
					else{
						if($get_entry_food_id != "0" && $get_entry_recipe_id == "0"){
							$check_meal_entries = $check_meal_entries . "|f$get_entry_food_id";
						}
						elseif($get_entry_food_id == "0" && $get_entry_recipe_id != "0"){
							$check_meal_entries = $check_meal_entries . "|r$get_entry_recipe_id";
						}
					}

					// Sum
					$inp_meal_energy_total = $inp_meal_energy_total+$get_entry_energy_per_entry;
					$inp_meal_fat_total = $inp_meal_fat_total+$get_entry_fat_per_entry;
					$inp_meal_saturated_total = $inp_meal_saturated_total+$get_entry_saturated_fat_per_entry;
					$inp_meal_monounsaturated_fat_total = $inp_meal_monounsaturated_fat_total+$get_entry_monounsaturated_fat_per_entry;
					$inp_meal_polyunsaturated_fat_total = $inp_meal_polyunsaturated_fat_total+$get_entry_polyunsaturated_fat_per_entry;
					$inp_meal_cholesterol_total = $inp_meal_cholesterol_total+$get_entry_cholesterol_per_entry;
					$inp_meal_carbohydrates_total = $inp_meal_carbohydrates_total+$get_entry_carbohydrates_per_entry;
					$inp_meal_carbohydrates_of_which_sugars_total = $inp_meal_carbohydrates_of_which_sugars_total+$get_entry_carbohydrates_of_which_sugars_per_entry;
					$inp_meal_dietary_fiber_total = $inp_meal_dietary_fiber_total+$get_entry_dietary_fiber_per_entry;
					$inp_meal_proteins_total = $inp_meal_proteins_total+$get_entry_proteins_per_entry;
					$inp_meal_salt_total = $inp_meal_salt_total+$get_entry_salt_per_entry;
					$inp_meal_sodium_total = $inp_meal_sodium_total+$get_entry_sodium_per_entry;

					// Counter
					$inp_meal_entries_count++;
				}
				if($check_meal_entries != "" && $inp_meal_entries_count > 1){
					// Look for meal
					
					$query_m = "SELECT meal_id, meal_user_id, meal_hour_name, meal_last_used_date, meal_energy_total, meal_fat_total, meal_saturated_total, meal_monounsaturated_fat_total, meal_polyunsaturated_fat_total, meal_cholesterol_total, meal_carbohydrates_total, meal_carbohydrates_of_which_sugars_total, meal_dietary_fiber_total, meal_proteins_total, meal_salt_total, meal_sodium_total FROM $t_food_diary_meals_index WHERE meal_user_id=$my_user_id_mysql AND meal_hour_name=$inp_hour_name_mysql AND meal_entries='$check_meal_entries'";
					$result_m = mysqli_query($link, $query_m);
					$row_m = mysqli_fetch_row($result_m);
					list($get_meal_id, $get_meal_user_id, $get_meal_hour_name, $get_meal_last_used_date, $get_meal_energy_total, $get_meal_fat_total, $get_meal_saturated_total, $get_meal_monounsaturated_fat_total, $get_meal_polyunsaturated_fat_total, $get_meal_cholesterol_total, $get_meal_carbohydrates_total, $get_meal_carbohydrates_of_which_sugars_total, $get_meal_dietary_fiber_total, $get_meal_proteins_total, $get_meal_salt_total, $get_meal_sodium_total) = $row_m;
					if($get_meal_id == ""){
						// Insert the food as meal
						// Serving and total is the same, because we have eaten 1 of this meal
						$inp_meal_selected_measurement_mysql = quote_smart($link, $l_pcs_lowercase);
						mysqli_query($link, "INSERT INTO $t_food_diary_meals_index 
						(meal_id, meal_user_id, meal_hour_name, meal_last_used_date, meal_used_times,
						meal_entries, meal_entries_count, meal_selected_serving_size, meal_selected_measurement, meal_energy_serving, 
						meal_fat_serving, meal_saturated_fat_serving, meal_monounsaturated_fat_serving, meal_polyunsaturated_fat_serving, meal_cholesterol_serving, 
						meal_carbohydrates_serving, meal_carbohydrates_of_which_sugars_serving, meal_dietary_fiber_serving, meal_proteins_serving, meal_salt_serving, 
						meal_sodium_serving, meal_energy_total, meal_fat_total, meal_saturated_total, meal_monounsaturated_fat_total, 
						meal_polyunsaturated_fat_total, meal_cholesterol_total, meal_carbohydrates_total, meal_carbohydrates_of_which_sugars_total, meal_dietary_fiber_total, 
						meal_proteins_total, meal_salt_total, meal_sodium_total) 
						VALUES 
						(NULL, '$get_my_user_id', $inp_hour_name_mysql, $yesterday_mysql, 0,
						'$check_meal_entries', $inp_meal_entries_count, 1, $inp_meal_selected_measurement_mysql, $inp_meal_energy_total,  
						$inp_meal_fat_total, $inp_meal_saturated_total, $inp_meal_monounsaturated_fat_total, $inp_meal_polyunsaturated_fat_total, $inp_meal_cholesterol_total,
						$inp_meal_carbohydrates_total, $inp_meal_carbohydrates_of_which_sugars_total, $inp_meal_dietary_fiber_total, $inp_meal_proteins_total, $inp_meal_salt_total,
						$inp_meal_sodium_total,
						$inp_meal_energy_total,  
						$inp_meal_fat_total, $inp_meal_saturated_total, $inp_meal_monounsaturated_fat_total, $inp_meal_polyunsaturated_fat_total, $inp_meal_cholesterol_total,
						$inp_meal_carbohydrates_total, $inp_meal_carbohydrates_of_which_sugars_total, $inp_meal_dietary_fiber_total, $inp_meal_proteins_total, $inp_meal_salt_total,
						$inp_meal_sodium_total)")
						or die(mysqli_error($link));
						
						// Get ID
						$query_m = "SELECT meal_id FROM $t_food_diary_meals_index WHERE meal_user_id=$my_user_id_mysql AND meal_hour_name=$inp_hour_name_mysql AND meal_entries='$check_meal_entries'";
						$result_m = mysqli_query($link, $query_m);
						$row_m = mysqli_fetch_row($result_m);
						list($get_meal_id) = $row_m;

						// Echo
						echo"<div class=\"info\"><p>Created meal $get_meal_id</p></div>\n";
						
						// Ready meal variables
						$inp_meal_energy_metric = 0;
						$inp_meal_fat_metric = 0;
						$inp_meal_saturated_fat_metric = 0;
						$inp_meal_monounsaturated_fat_metric = 0;
						$inp_meal_polyunsaturated_fat_metric = 0;
						$inp_meal_cholesterol_metric = 0;
						$inp_meal_carbohydrates_metric = 0;
						$inp_meal_carbohydrates_of_which_sugars_metric = 0;
						$inp_meal_dietary_fiber_metric = 0;
						$inp_meal_proteins_metric = 0;
						$inp_meal_salt_metric = 0;
						$inp_meal_sodium_metric = 0;

						$inp_meal_energy_us = 0;
						$inp_meal_fat_us = 0;
						$inp_meal_saturated_fat_us = 0;
						$inp_meal_monounsaturated_fat_us = 0;
						$inp_meal_polyunsaturated_fat_us = 0;
						$inp_meal_cholesterol_us = 0;
						$inp_meal_carbohydrates_us = 0;
						$inp_meal_carbohydrates_of_which_sugars_us = 0;
						$inp_meal_dietary_fiber_us = 0;
						$inp_meal_proteins_us = 0;
						$inp_meal_salt_us = 0;
						$inp_meal_sodium_us = 0;


						// Insert into meal items from entries	
						$inp_last_used_name = "";
						$query_e = "SELECT entry_id, entry_user_id, entry_date, entry_hour_name, entry_food_id, entry_recipe_id, entry_name, entry_manufacturer_name, entry_serving_size, entry_serving_size_measurement, entry_energy_per_entry, entry_fat_per_entry, entry_saturated_fat_per_entry, entry_monounsaturated_fat_per_entry, entry_polyunsaturated_fat_per_entry, entry_cholesterol_per_entry, entry_carbohydrates_per_entry, entry_carbohydrates_of_which_sugars_per_entry, entry_dietary_fiber_per_entry, entry_proteins_per_entry, entry_salt_per_entry, entry_sodium_per_entry, entry_text, entry_deleted, entry_updated_datetime, entry_synchronized FROM $t_food_diary_entires WHERE entry_user_id=$my_user_id_mysql AND entry_date=$yesterday_mysql AND entry_hour_name=$inp_hour_name_mysql";
						$result_e = mysqli_query($link, $query_e);
						while($row_e = mysqli_fetch_row($result_e)) {
    							list($get_entry_id, $get_entry_user_id, $get_entry_date, $get_entry_hour_name, $get_entry_food_id, $get_entry_recipe_id, $get_entry_name, $get_entry_manufacturer_name, $get_entry_serving_size, $get_entry_serving_size_measurement, $get_entry_energy_per_entry, $get_entry_fat_per_entry, $get_entry_saturated_fat_per_entry, $get_entry_monounsaturated_fat_per_entry, $get_entry_polyunsaturated_fat_per_entry, $get_entry_cholesterol_per_entry, $get_entry_carbohydrates_per_entry, $get_entry_carbohydrates_of_which_sugars_per_entry, $get_entry_dietary_fiber_per_entry, $get_entry_proteins_per_entry, $get_entry_salt_per_entry, $get_entry_sodium_per_entry, $get_entry_text, $get_entry_deleted, $get_entry_updated_datetime, $get_entry_synchronized) = $row_e;
							
							// Cats
							$inp_item_name = "";
							$inp_item_main_category_id = -1;
							$inp_item_sub_category_id = -1;

							// Find picture
							if($get_entry_food_id != "0"){
								$query_f = "SELECT food_id, food_user_id, food_name, food_clean_name, food_manufacturer_name, food_manufacturer_name_and_food_name, food_description, food_text, food_country, food_net_content_metric, food_net_content_measurement_metric, food_net_content_us, food_net_content_measurement_us, food_net_content_added_measurement, food_serving_size_metric, food_serving_size_measurement_metric, food_serving_size_us, food_serving_size_measurement_us, food_serving_size_added_measurement, food_serving_size_pcs, food_serving_size_pcs_measurement, food_numbers_entered_method, food_nutrition_facts_view_method, food_energy_metric, food_fat_metric, food_saturated_fat_metric, food_trans_fat_metric, food_monounsaturated_fat_metric, food_polyunsaturated_fat_metric, food_cholesterol_metric, food_carbohydrates_metric, food_carbohydrates_of_which_sugars_metric, food_added_sugars_metric, food_dietary_fiber_metric, food_proteins_metric, food_salt_metric, food_sodium_metric, food_energy_us, food_fat_us, food_saturated_fat_us, food_trans_fat_us, food_monounsaturated_fat_us, food_polyunsaturated_fat_us, food_cholesterol_us, food_carbohydrates_us, food_carbohydrates_of_which_sugars_us, food_added_sugars_us, food_dietary_fiber_us, food_proteins_us, food_salt_us, food_sodium_us, food_score, food_score_place_in_sub_category, food_energy_calculated_metric, food_fat_calculated_metric, food_saturated_fat_calculated_metric, food_trans_fat_calculated_metric, food_monounsaturated_fat_calculated_metric, food_polyunsaturated_fat_calculated_metric, food_cholesterol_calculated_metric, food_carbohydrates_calculated_metric, food_carbohydrates_of_which_sugars_calculated_metric, food_added_sugars_calculated_metric, food_dietary_fiber_calculated_metric, food_proteins_calculated_metric, food_salt_calculated_metric, food_sodium_calculated_metric, food_energy_calculated_us, food_fat_calculated_us, food_saturated_fat_calculated_us, food_trans_fat_calculated_us, food_monounsaturated_fat_calculated_us, food_polyunsaturated_fat_calculated_us, food_cholesterol_calculated_us, food_carbohydrates_calculated_us, food_carbohydrates_of_which_sugars_calculated_us, food_added_sugars_calculated_us, food_dietary_fiber_calculated_us, food_proteins_calculated_us, food_salt_calculated_us, food_sodium_calculated_us, food_energy_net_content, food_fat_net_content, food_saturated_fat_net_content, food_trans_fat_net_content, food_monounsaturated_fat_net_content, food_polyunsaturated_fat_net_content, food_cholesterol_net_content, food_carbohydrates_net_content, food_carbohydrates_of_which_sugars_net_content, food_added_sugars_net_content, food_dietary_fiber_net_content, food_proteins_net_content, food_salt_net_content, food_sodium_net_content, food_barcode, food_main_category_id, food_sub_category_id, food_image_path, food_image_a, food_thumb_a_small, food_thumb_a_medium, food_thumb_a_large, food_image_b, food_thumb_b_small, food_thumb_b_medium, food_thumb_b_large, food_image_c, food_thumb_c_small, food_thumb_c_medium, food_thumb_c_large, food_image_d, food_thumb_d_small, food_thumb_d_medium, food_thumb_d_large, food_image_e, food_thumb_e_small, food_thumb_e_medium, food_thumb_e_large, food_last_used, food_language, food_no_of_comments, food_stars, food_stars_sum, food_comments_multiplied_stars, food_synchronized, food_accepted_as_master, food_notes, food_unique_hits, food_unique_hits_ip_block, food_user_ip, food_created_date, food_last_viewed, food_age_restriction FROM $t_food_index WHERE food_id=$get_entry_food_id";
								$result_f = mysqli_query($link, $query_f);
								$row_f = mysqli_fetch_row($result_f);
								list($get_food_id, $get_food_user_id, $get_food_name, $get_food_clean_name, $get_food_manufacturer_name, $get_food_manufacturer_name_and_food_name, $get_food_description, $get_food_text, $get_food_country, $get_food_net_content_metric, $get_food_net_content_measurement_metric, $get_food_net_content_us, $get_food_net_content_measurement_us, $get_food_net_content_added_measurement, $get_food_serving_size_metric, $get_food_serving_size_measurement_metric, $get_food_serving_size_us, $get_food_serving_size_measurement_us, $get_food_serving_size_added_measurement, $get_food_serving_size_pcs, $get_food_serving_size_pcs_measurement, $get_food_numbers_entered_method, $get_food_nutrition_facts_view_method, $get_food_energy_metric, $get_food_fat_metric, $get_food_saturated_fat_metric, $get_food_trans_fat_metric, $get_food_monounsaturated_fat_metric, $get_food_polyunsaturated_fat_metric, $get_food_cholesterol_metric, $get_food_carbohydrates_metric, $get_food_carbohydrates_of_which_sugars_metric, $get_food_added_sugars_metric, $get_food_dietary_fiber_metric, $get_food_proteins_metric, $get_food_salt_metric, $get_food_sodium_metric, $get_food_energy_us, $get_food_fat_us, $get_food_saturated_fat_us, $get_food_trans_fat_us, $get_food_monounsaturated_fat_us, $get_food_polyunsaturated_fat_us, $get_food_cholesterol_us, $get_food_carbohydrates_us, $get_food_carbohydrates_of_which_sugars_us, $get_food_added_sugars_us, $get_food_dietary_fiber_us, $get_food_proteins_us, $get_food_salt_us, $get_food_sodium_us, $get_food_score, $get_food_score_place_in_sub_category, $get_food_energy_calculated_metric, $get_food_fat_calculated_metric, $get_food_saturated_fat_calculated_metric, $get_food_trans_fat_calculated_metric, $get_food_monounsaturated_fat_calculated_metric, $get_food_polyunsaturated_fat_calculated_metric, $get_food_cholesterol_calculated_metric, $get_food_carbohydrates_calculated_metric, $get_food_carbohydrates_of_which_sugars_calculated_metric, $get_food_added_sugars_calculated_metric, $get_food_dietary_fiber_calculated_metric, $get_food_proteins_calculated_metric, $get_food_salt_calculated_metric, $get_food_sodium_calculated_metric, $get_food_energy_calculated_us, $get_food_fat_calculated_us, $get_food_saturated_fat_calculated_us, $get_food_trans_fat_calculated_us, $get_food_monounsaturated_fat_calculated_us, $get_food_polyunsaturated_fat_calculated_us, $get_food_cholesterol_calculated_us, $get_food_carbohydrates_calculated_us, $get_food_carbohydrates_of_which_sugars_calculated_us, $get_food_added_sugars_calculated_us, $get_food_dietary_fiber_calculated_us, $get_food_proteins_calculated_us, $get_food_salt_calculated_us, $get_food_sodium_calculated_us, $get_food_energy_net_content, $get_food_fat_net_content, $get_food_saturated_fat_net_content, $get_food_trans_fat_net_content, $get_food_monounsaturated_fat_net_content, $get_food_polyunsaturated_fat_net_content, $get_food_cholesterol_net_content, $get_food_carbohydrates_net_content, $get_food_carbohydrates_of_which_sugars_net_content, $get_food_added_sugars_net_content, $get_food_dietary_fiber_net_content, $get_food_proteins_net_content, $get_food_salt_net_content, $get_food_sodium_net_content, $get_food_barcode, $get_food_main_category_id, $get_food_sub_category_id, $get_food_image_path, $get_food_image_a, $get_food_thumb_a_small, $get_food_thumb_a_medium, $get_food_thumb_a_large, $get_food_image_b, $get_food_thumb_b_small, $get_food_thumb_b_medium, $get_food_thumb_b_large, $get_food_image_c, $get_food_thumb_c_small, $get_food_thumb_c_medium, $get_food_thumb_c_large, $get_food_image_d, $get_food_thumb_d_small, $get_food_thumb_d_medium, $get_food_thumb_d_large, $get_food_image_e, $get_food_thumb_e_small, $get_food_thumb_e_medium, $get_food_thumb_e_large, $get_food_last_used, $get_food_language, $get_food_no_of_comments, $get_food_stars, $get_food_stars_sum, $get_food_comments_multiplied_stars, $get_food_synchronized, $get_food_accepted_as_master, $get_food_notes, $get_food_unique_hits, $get_food_unique_hits_ip_block, $get_food_user_ip, $get_food_created_date, $get_food_last_viewed, $get_food_age_restriction) = $row_f;

								$inp_item_name = "$get_food_manufacturer_name $get_food_name";


								$inp_item_image_path_mysql = quote_smart($link, $get_food_image_path);
								$inp_item_image_file_mysql = quote_smart($link, $get_food_image_a);
								$ext = get_extension($get_food_image_a);

								$thumb = str_replace(".$ext", "_thumb_66x132.$ext", $get_food_image_a);
								$inp_item_image_thumb_66x132_mysql = quote_smart($link, $thumb);

								$thumb = str_replace(".$ext", "_thumb_100x100.$ext", $get_food_image_a);
								$inp_item_image_thumb_100x100_mysql = quote_smart($link, $thumb);

								$thumb = str_replace(".$ext", "_thumb_100x200.$ext", $get_food_image_a);
								$inp_item_image_thumb_100x200_mysql = quote_smart($link, $thumb);

								$thumb = str_replace(".$ext", "_thumb_132x132.$ext", $get_food_image_a);
								$inp_item_image_thumb_132x132_mysql = quote_smart($link, $thumb);

								$inp_item_main_category_id = "$get_food_main_category_id";
								$inp_item_sub_category_id = "$get_food_sub_category_id";


							}
							else{
								// get recipe
								$query_r = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_country, recipe_introduction, recipe_directions, recipe_image_path, recipe_image, recipe_thumb_278x156, recipe_video, recipe_date, recipe_date_saying, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_times_favorited, recipe_user_ip, recipe_notes, recipe_password, recipe_last_viewed, recipe_age_restriction, recipe_published FROM $t_recipes WHERE recipe_id=$get_entry_recipe_id";
								$result_r = mysqli_query($link, $query_r);
								$row_r = mysqli_fetch_row($result_r);
								list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_country, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb_278x156, $get_recipe_video, $get_recipe_date, $get_recipe_date_saying, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_times_favorited, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password, $get_recipe_last_viewed, $get_recipe_age_restriction, $get_recipe_published) = $row_r;

								$inp_item_name = "$get_recipe_title";

								$inp_item_image_path_mysql = quote_smart($link, $get_recipe_image_path);
								$inp_item_image_file_mysql = quote_smart($link, $get_recipe_image);

								$ext = get_extension($get_recipe_image);


								$thumb = str_replace(".$ext", "_thumb_66x132.$ext", $get_recipe_image);
								$inp_item_image_thumb_66x132_mysql = quote_smart($link, $thumb);

								$thumb = str_replace(".$ext", "_thumb_66x132.$ext", $get_recipe_image);
								$inp_item_image_thumb_100x100_mysql = quote_smart($link, $thumb);

								$thumb = str_replace(".$ext", "_thumb_66x132.$ext", $get_recipe_image);
								$inp_item_image_thumb_100x200_mysql = quote_smart($link, $thumb);

								$thumb = str_replace(".$ext", "_thumb_132x132.$ext", $get_recipe_image);
								$inp_item_image_thumb_132x132_mysql = quote_smart($link, $thumb);

								$inp_item_main_category_id = "$get_recipe_category_id";
							}

							$inp_item_name_mysql = quote_smart($link, $inp_item_name);
							$inp_item_name_short = "$inp_item_name";
							$inp_item_name_len = strlen($inp_item_name);
							if($inp_item_name_len > 20){
								$inp_item_name_short = substr($inp_item_name_short, 0, 17);
								$inp_item_name_short = $inp_item_name_short . "...";
							}
							$inp_item_name_short_mysql = quote_smart($link, $inp_item_name_short);

							$inp_item_manufacturer_name_mysql = quote_smart($link, $get_entry_manufacturer_name);
							$inp_item_main_category_id_mysql = quote_smart($link, $inp_item_main_category_id);
							$inp_item_sub_category_id_mysql = quote_smart($link, $inp_item_sub_category_id);

							$inp_item_serving_size_mysql = quote_smart($link, $get_entry_serving_size);
							$inp_item_serving_size_measurement_mysql = quote_smart($link, $get_entry_serving_size_measurement);

							$inp_item_energy_serving_mysql = quote_smart($link, $get_entry_energy_per_entry);
							$inp_item_fat_serving_mysql = quote_smart($link, $get_entry_fat_per_entry);
							$inp_item_saturated_fat_serving_mysql = quote_smart($link, $get_entry_saturated_fat_per_entry);
							$inp_item_monounsaturated_fat_serving_mysql = quote_smart($link, $get_entry_monounsaturated_fat_per_entry);
							$inp_item_polyunsaturated_fat_serving_mysql = quote_smart($link, $get_entry_polyunsaturated_fat_per_entry);
							$inp_item_cholesterol_serving_mysql = quote_smart($link, $get_entry_cholesterol_per_entry);
							$inp_item_carbohydrates_serving_mysql = quote_smart($link, $get_entry_carbohydrates_per_entry);
							$inp_item_carbohydrates_of_which_sugars_serving_mysql = quote_smart($link, $get_entry_carbohydrates_of_which_sugars_per_entry);
							$inp_item_dietary_fiber_serving_mysql = quote_smart($link, $get_entry_dietary_fiber_per_entry);
							$inp_item_proteins_serving_mysql = quote_smart($link, $get_entry_proteins_per_entry);
							$inp_item_salt_serving_mysql = quote_smart($link, $get_entry_salt_per_entry);
							$inp_item_sodium_serving_mysql = quote_smart($link, $get_entry_sodium_per_entry);


							mysqli_query($link, "INSERT INTO $t_food_diary_meals_items
							(item_id, item_user_id, item_meal_id, item_food_id, item_recipe_id, 
							item_name, item_name_short, item_manufacturer_name, item_main_category_id, item_sub_category_id, item_image_path, item_image_file, item_image_thumb_66x132, item_image_thumb_100x100, item_image_thumb_100x200, item_image_thumb_132x132, item_serving_size, 
							item_serving_size_measurement, item_energy_serving, item_fat_serving, item_saturated_fat_serving, item_monounsaturated_fat_serving, 
							item_polyunsaturated_fat_serving, item_cholesterol_serving, item_carbohydrates_serving, item_carbohydrates_of_which_sugars_serving, item_dietary_fiber_serving, 
							item_proteins_serving, item_salt_serving, item_sodium_serving) 
							VALUES 
							(NULL, '$get_my_user_id', $get_meal_id, $get_entry_food_id, $get_entry_recipe_id, 
							$inp_item_name_mysql, $inp_item_name_short_mysql, $inp_item_manufacturer_name_mysql, $inp_item_main_category_id_mysql, $inp_item_sub_category_id_mysql, $inp_item_image_path_mysql, $inp_item_image_file_mysql, $inp_item_image_thumb_66x132_mysql, $inp_item_image_thumb_100x100_mysql, $inp_item_image_thumb_100x200_mysql, $inp_item_image_thumb_132x132_mysql, $inp_item_serving_size_mysql, 
							$inp_item_serving_size_measurement_mysql, $inp_item_energy_serving_mysql,
							$inp_item_fat_serving_mysql,
							$inp_item_saturated_fat_serving_mysql,
							$inp_item_monounsaturated_fat_serving_mysql,
							$inp_item_polyunsaturated_fat_serving_mysql,
							$inp_item_cholesterol_serving_mysql,
							$inp_item_carbohydrates_serving_mysql,
							$inp_item_carbohydrates_of_which_sugars_serving_mysql,
							$inp_item_dietary_fiber_serving_mysql,
							$inp_item_proteins_serving_mysql,
							$inp_item_salt_serving_mysql,
							$inp_item_sodium_serving_mysql
							)")
							or die(mysqli_error($link));

							// Name
							if($inp_last_used_name == ""){
								$inp_last_used_name = "$get_entry_serving_size $get_entry_name";
							}
							else{
								$inp_last_used_name = $inp_last_used_name . ", " . "$get_entry_serving_size $get_entry_name";
							}


						} // entries
						


						// Add the meal to last used table
						$inp_last_used_name_mysql = quote_smart($link, $inp_last_used_name);
						mysqli_query($link, "INSERT INTO $t_food_diary_last_used
						(last_used_id, last_used_user_id, last_used_hour_name, last_used_food_id, last_used_recipe_id, 
						last_used_meal_id, last_used_times, last_used_created_datetime, last_used_updated_datetime, last_used_name, last_used_selected_serving_size, 
						
						last_used_selected_measurement, last_used_serving_size_pcs, last_used_serving_size_pcs_measurement, last_used_energy_serving, last_used_fat_serving, 
						last_used_saturated_fat_serving, last_used_monounsaturated_fat_serving, last_used_polyunsaturated_fat_serving, last_used_cholesterol_serving, last_used_carbohydrates_serving, last_used_carbohydrates_of_which_sugars_serving, 
						
						last_used_dietary_fiber_serving, last_used_proteins_serving, last_used_salt_serving, last_used_sodium_serving) 
						VALUES 
						(NULL, '$get_my_user_id', $inp_hour_name_mysql, 0, 0, 
						$get_meal_id, 1, '$datetime', '$datetime', $inp_last_used_name_mysql, 1, 
						$inp_meal_selected_measurement_mysql, 1, $inp_meal_selected_measurement_mysql, $inp_meal_energy_total,  
						$inp_meal_fat_total, $inp_meal_saturated_total, $inp_meal_monounsaturated_fat_total, $inp_meal_polyunsaturated_fat_total, $inp_meal_cholesterol_total,
						$inp_meal_carbohydrates_total, $inp_meal_carbohydrates_of_which_sugars_total, $inp_meal_dietary_fiber_total, $inp_meal_proteins_total, $inp_meal_salt_total,
						$inp_meal_sodium_total)")
						or die(mysqli_error($link));


						mysqli_query($link, "UPDATE $t_food_diary_meals_index SET meal_name=$inp_last_used_name_mysql WHERE meal_id=$get_meal_id")
						or die(mysqli_error($link));
					} // meal doesnt exists
				} // food and recipes found
			} // for hour names

			echo"
		<!-- //Yesterdays meals as meals -->
		<!-- Graph -->
			";
			include("index_include_graph.php");
			echo"
		<!-- //Graph -->	
		";
	} // goal
} // logged in
else{
	include("index_not_logged_in.php");
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>