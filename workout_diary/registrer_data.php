<?php
/**
*
* File: workout_diary/registrer_data.php
* Version 1.0.0.
* Date 19:42 08.02.2018
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration --------------------------------------------------------------------- */
$pageIdSav            = "201808251131";
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
include("_tables.php");


/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['plan_id'])){
	$plan_id = $_GET['plan_id'];
	$plan_id = output_html($plan_id);
}
else{
	$plan_id = "";
}
if(isset($_GET['weekly_id'])){
	$weekly_id = $_GET['weekly_id'];
	$weekly_id = output_html($weekly_id);
}
else{
	$weekly_id = "";
}
if(isset($_GET['session_id'])){
	$session_id = $_GET['session_id'];
	$session_id = output_html($session_id);
}
else{
	$session_id = "";
}

$l_mysql = quote_smart($link, $l);


$year = date("Y");
$week = date("W");



/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_register_data - $l_workout_diary";
include("$root/_webdesign/header.php");

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){

	// Get my profile
	$my_user_id = $_SESSION['user_id'];
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_alias, user_email, user_measurement, user_date_format FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_alias, $get_my_user_email, $get_my_user_measurement, $get_my_user_date_format) = $row;

	$plan_id_mysql = quote_smart($link, $plan_id);
	$query = "SELECT workout_diary_plan_id, workout_diary_plan_user_id, workout_diary_plan_weight, workout_diary_plan_period_id, workout_diary_plan_session_id, workout_diary_plan_weekly_id, workout_diary_plan_yearly_id, workout_diary_plan_title, workout_diary_plan_date, workout_diary_plan_notes FROM $t_workout_diary_plans WHERE workout_diary_plan_id=$plan_id_mysql AND workout_diary_plan_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_workout_diary_plan_id, $get_workout_diary_plan_user_id, $get_workout_diary_plan_weight, $get_workout_diary_plan_period_id, $get_workout_diary_plan_session_id, $get_workout_diary_plan_weekly_id, $get_workout_diary_plan_yearly_id, $get_workout_diary_plan_title, $get_workout_diary_plan_date, $get_workout_diary_plan_notes) = $row;

	if($get_workout_diary_plan_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>Workout diary plan not found.</p>
		";	
	}
	else{
		// Find weekly
		$query = "SELECT workout_weekly_id, workout_weekly_user_id, workout_weekly_period_id, workout_weekly_weight, workout_weekly_language, workout_weekly_title, workout_weekly_title_clean, workout_weekly_introduction, workout_weekly_goal, workout_weekly_image_path, workout_weekly_image_file, workout_weekly_created, workout_weekly_updated, workout_weekly_unique_hits, workout_weekly_unique_hits_ip_block, workout_weekly_comments, workout_weekly_likes, workout_weekly_dislikes, workout_weekly_rating, workout_weekly_ip_block, workout_weekly_user_ip, workout_weekly_notes FROM $t_workout_plans_weekly WHERE workout_weekly_id=$get_workout_diary_plan_weekly_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_workout_weekly_id, $get_current_workout_weekly_user_id, $get_current_workout_weekly_period_id, $get_current_workout_weekly_weight, $get_current_workout_weekly_language, $get_current_workout_weekly_title, $get_current_workout_weekly_title_clean, $get_current_workout_weekly_introduction, $get_current_workout_weekly_goal, $get_current_workout_weekly_image_path, $get_current_workout_weekly_image_file, $get_current_workout_weekly_created, $get_current_workout_weekly_updated, $get_current_workout_weekly_unique_hits, $get_current_workout_weekly_unique_hits_ip_block, $get_current_workout_weekly_comments, $get_current_workout_weekly_likes, $get_current_workout_weekly_dislikes, $get_current_workout_weekly_rating, $get_current_workout_weekly_ip_block, $get_current_workout_weekly_user_ip, $get_current_workout_weekly_notes) = $row;
		if($get_current_workout_weekly_id == ""){
			// Delete refrence
			$result = mysqli_query($link, "DELETE FROM $t_workout_diary_plans WHERE workout_diary_plan_id=$plan_id_mysql AND workout_diary_plan_user_id=$my_user_id_mysql");
			
			echo"<h1>Server error 404</h1>

			<p>
			The weekly workout plan has been removed.
			You need to select another plan to have as your favorite.
			</p>

			<p>
			<a href=\"index.php?l=$l\">Select another plan</a>
			</p>
			";
		}
		else{
			// Find session
			$session_id_mysql = quote_smart($link, $session_id);
			$query = "SELECT workout_session_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity FROM $t_workout_plans_sessions WHERE workout_session_id=$session_id_mysql AND workout_session_weekly_id=$get_current_workout_weekly_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_workout_session_id, $get_workout_session_weight, $get_workout_session_title, $get_workout_session_title_clean, $get_workout_session_duration, $get_workout_session_intensity) = $row;

			if($get_workout_session_id == ""){
				echo"<h1>Server error 404</h1>

				<p>
				Session not found.
				</p>
				";
			}
			else{
				if($process == "1"){
					$date = date("Y-m-d");
					$year = date("Y");
					$month = date("m");
					$day = date("d");
					$week = date("W");
					$focus = "";
					$saved_animation = "";
					$query_sessions = "SELECT workout_session_main_id, workout_session_main_user_id, workout_session_main_session_id, workout_session_main_weight, workout_session_main_exercise_id, workout_session_main_exercise_title, workout_session_main_sets, workout_session_main_reps, workout_session_main_velocity_a, workout_session_main_velocity_b, workout_session_main_distance, workout_session_main_duration, workout_session_main_intensity, workout_session_main_text FROM $t_workout_plans_sessions_main WHERE workout_session_main_session_id=$get_workout_session_id ORDER BY workout_session_main_weight ASC";
					$result_sessions = mysqli_query($link, $query_sessions);
					while($row_sessions = mysqli_fetch_row($result_sessions)) {
						list($get_workout_session_main_id, $get_workout_session_main_user_id, $get_workout_session_main_session_id, $get_workout_session_main_weight, $get_workout_session_main_exercise_id, $get_workout_session_main_exercise_title, $get_workout_session_main_sets, $get_workout_session_main_reps, $get_workout_session_main_velocity_a, $get_workout_session_main_velocity_b, $get_workout_session_main_distance, $get_workout_session_main_duration, $get_workout_session_main_intensity, $get_workout_session_main_text) = $row_sessions;

						// Focus
						if(isset($focus_on_next) && $focus_on_next == "true"){
							$focus = $focus . $get_workout_session_main_id;
							$focus_on_next = "false";
						}

						// Sets and reps?
						if($get_workout_session_main_sets != "0" && $get_workout_session_main_reps != "0"){

							// Ready variables
							$inp_set_a_weight = "";
							$inp_measurement = "";
							$inp_set_a_reps = "";
							$inp_set_b_reps = "";
							$inp_set_c_reps = "";
							$inp_set_d_reps = "";
						

							if(isset($_POST["inp_set_a_weight_$get_workout_session_main_id"])){
								$inp_set_a_weight = $_POST["inp_set_a_weight_$get_workout_session_main_id"];
								$inp_set_a_weight = str_replace(",", ".", $inp_set_a_weight);
								$inp_set_a_weight = output_html($inp_set_a_weight);
							}
							if(isset($_POST["inp_measurement_$get_workout_session_main_id"])){
								$inp_measurement = $_POST["inp_measurement_$get_workout_session_main_id"];
								$inp_measurement = str_replace(",", ".", $inp_measurement);
								$inp_measurement = output_html($inp_measurement);

								
							}


							if(isset($_POST["inp_set_a_reps_$get_workout_session_main_id"])){
								$inp_set_a_reps = $_POST["inp_set_a_reps_$get_workout_session_main_id"];
								$inp_set_a_reps = str_replace(",", ".", $inp_set_a_reps);
								$inp_set_a_reps = output_html($inp_set_a_reps);

							}
							if(isset($_POST["inp_set_b_reps_$get_workout_session_main_id"])){
								$inp_set_b_reps = $_POST["inp_set_b_reps_$get_workout_session_main_id"];
								$inp_set_b_reps = str_replace(",", ".", $inp_set_b_reps);
								$inp_set_b_reps = output_html($inp_set_b_reps);
								$inp_set_b_reps_mysql = quote_smart($link, $inp_set_b_reps);

							}
							if(isset($_POST["inp_set_c_reps_$get_workout_session_main_id"])){
								$inp_set_c_reps = $_POST["inp_set_c_reps_$get_workout_session_main_id"];
								$inp_set_c_reps = str_replace(",", ".", $inp_set_c_reps);
								$inp_set_c_reps = output_html($inp_set_c_reps);
								$inp_set_c_reps_mysql = quote_smart($link, $inp_set_c_reps);
							}
							if(isset($_POST["inp_set_d_reps_$get_workout_session_main_id"])){
								$inp_set_d_reps = $_POST["inp_set_d_reps_$get_workout_session_main_id"];
								$inp_set_d_reps = str_replace(",", ".", $inp_set_d_reps);
								$inp_set_d_reps = output_html($inp_set_d_reps);
								$inp_set_d_reps_mysql = quote_smart($link, $inp_set_d_reps);
							}

							// Do something?
							if($inp_set_a_weight != "" OR $inp_measurement != "" OR $inp_set_a_reps != "" OR 
							   $inp_set_b_reps != "" OR $inp_set_c_reps != "" OR $inp_set_d_reps != ""){



								// Fetch workout diary entry ID
								$query_entry = "SELECT workout_diary_entry_id, workout_diary_entry_user_id, workout_diary_entry_session_id, workout_diary_entry_session_main_id, workout_diary_entry_date, workout_diary_entry_year, workout_diary_entry_month, workout_diary_entry_day, workout_diary_entry_week, workout_diary_entry_exercise_id, workout_diary_entry_exercise_title, workout_diary_entry_measurement, workout_diary_entry_set_a_weight, workout_diary_entry_set_a_reps, workout_diary_entry_set_b_weight, workout_diary_entry_set_b_reps, workout_diary_entry_set_c_weight, workout_diary_entry_set_c_reps, workout_diary_entry_set_d_weight, workout_diary_entry_set_d_reps, workout_diary_entry_set_e_weight, workout_diary_entry_set_e_reps, workout_diary_entry_set_avg_weight, workout_diary_entry_set_avg_reps, workout_diary_entry_velocity_a, workout_diary_entry_velocity_b, workout_diary_entry_distance, workout_diary_entry_distance_measurement, workout_diary_entry_duration_hh, workout_diary_entry_duration_mm, workout_diary_entry_duration_ss, workout_diary_entry_notes FROM $t_workout_diary_entries WHERE workout_diary_entry_user_id=$my_user_id_mysql AND workout_diary_entry_session_id=$session_id_mysql AND workout_diary_entry_session_main_id=$get_workout_session_main_id AND workout_diary_entry_year=$year AND workout_diary_entry_week=$week AND workout_diary_entry_exercise_id=$get_workout_session_main_exercise_id";
								$result_entry = mysqli_query($link, $query_entry);
								$row_entry = mysqli_fetch_row($result_entry);
								list($get_workout_diary_entry_id, $get_workout_diary_entry_user_id, $get_workout_diary_entry_session_id, $get_workout_diary_entry_session_main_id, $get_workout_diary_entry_date, $get_workout_diary_entry_year, $get_workout_diary_entry_month, $get_workout_diary_entry_day, $get_workout_diary_entry_week, $get_workout_diary_entry_exercise_id, $get_workout_diary_entry_exercise_title, $get_workout_diary_entry_measurement, $get_workout_diary_entry_set_a_weight, $get_workout_diary_entry_set_a_reps, $get_workout_diary_entry_set_b_weight, $get_workout_diary_entry_set_b_reps, $get_workout_diary_entry_set_c_weight, $get_workout_diary_entry_set_c_reps, $get_workout_diary_entry_set_d_weight, $get_workout_diary_entry_set_d_reps, $get_workout_diary_entry_set_e_weight, $get_workout_diary_entry_set_e_reps, $get_workout_diary_entry_set_avg_weight, $get_workout_diary_entry_set_avg_reps, $get_workout_diary_entry_velocity_a, $get_workout_diary_entry_velocity_b, $get_workout_diary_entry_distance, $get_workout_diary_entry_distance_measurement, $get_workout_diary_entry_duration_hh, $get_workout_diary_entry_duration_mm, $get_workout_diary_entry_duration_ss, $get_workout_diary_entry_notes) = $row_entry;
								if($get_workout_diary_entry_id == ""){
									// Create it
									$inp_exercise_title_mysql = quote_smart($link, $get_workout_session_main_exercise_title);
									mysqli_query($link, "INSERT INTO $t_workout_diary_entries
									(workout_diary_entry_id, workout_diary_entry_user_id, workout_diary_entry_session_id, workout_diary_entry_session_main_id, workout_diary_entry_date, workout_diary_entry_year, workout_diary_entry_month, workout_diary_entry_day, workout_diary_entry_week, workout_diary_entry_exercise_id, workout_diary_entry_exercise_title) 
									VALUES 
									(NULL, $my_user_id_mysql, $get_workout_session_id, $get_workout_session_main_id, '$date', '$year', '$month', '$day', '$week', $get_workout_session_main_exercise_id, $inp_exercise_title_mysql)")
									or die(mysqli_error($link));

									// Get the new ID
									$query_entry = "SELECT workout_diary_entry_id FROM $t_workout_diary_entries WHERE workout_diary_entry_user_id=$my_user_id_mysql AND workout_diary_entry_session_id=$session_id_mysql AND workout_diary_entry_session_main_id=$get_workout_session_main_id AND workout_diary_entry_year=$year AND workout_diary_entry_week=$week AND workout_diary_entry_exercise_id=$get_workout_session_main_exercise_id";
									$result_entry = mysqli_query($link, $query_entry);
									$row_entry = mysqli_fetch_row($result_entry);
									list($get_workout_diary_entry_id) = $row_entry;
								}


								if($inp_set_a_weight != "" && $inp_set_a_weight != "$get_workout_diary_entry_set_a_weight"){
									$inp_set_a_weight_mysql = quote_smart($link, $inp_set_a_weight);
									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_set_a_weight=$inp_set_a_weight_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");

									$saved_animation = "inp_set_a_weight_" . $get_workout_session_main_id;
									$focus = "inp_set_a_reps_" . $get_workout_session_main_id;
								}
								if($inp_measurement != "" && $inp_measurement != "$get_workout_diary_entry_measurement"){
									$inp_measurement_mysql = quote_smart($link, $inp_measurement);
									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_measurement=$inp_measurement_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");
								}
								if($inp_set_a_reps != "" && $inp_set_a_reps != "$get_workout_diary_entry_set_a_reps"){
									$inp_set_a_reps_mysql = quote_smart($link, $inp_set_a_reps);
									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_set_a_reps=$inp_set_a_reps_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");


									$saved_animation = "inp_set_a_reps_" . $get_workout_session_main_id;
									$focus = "inp_set_b_reps_" . $get_workout_session_main_id;
								}
								if($inp_set_b_reps != "" && $inp_set_b_reps != "$get_workout_diary_entry_set_b_reps"){
									$inp_set_b_reps_mysql = quote_smart($link, $inp_set_b_reps);
									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_set_b_reps=$inp_set_b_reps_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");


									$saved_animation = "inp_set_b_reps_" . $get_workout_session_main_id;
									$focus = "inp_set_c_reps_" . $get_workout_session_main_id;
								}
								if($inp_set_c_reps != "" && $inp_set_c_reps != "$get_workout_diary_entry_set_c_reps"){
									$inp_set_c_reps_mysql = quote_smart($link, $inp_set_c_reps);
									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_set_c_reps=$inp_set_c_reps_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");


									$saved_animation = "inp_set_c_reps_" . $get_workout_session_main_id;
									$focus = "inp_set_d_reps_" . $get_workout_session_main_id;
								}
								if($inp_set_d_reps != "" && $inp_set_d_reps != "$get_workout_diary_entry_set_d_reps"){
									$inp_set_d_reps_mysql = quote_smart($link, $inp_set_d_reps);
									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_set_d_reps=$inp_set_d_reps_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");


									$saved_animation = "inp_set_d_reps_" . $get_workout_session_main_id;
									$focus = "inp_set_a_weight_"; // the rest will be filled in in next iteration
									$focus_on_next = "true";
								}
								
							}

							
						} // sets reps


						// Cardio?
						if(($get_workout_session_main_distance != "" && $get_workout_session_main_distance != "0") OR $get_workout_session_main_duration != "" && $get_workout_session_main_duration != "0"){

							// Ready variables
							$inp_distance = "";
							$inp_distance_measurement = "";
							$inp_velocity_a = "";
							$inp_measurement = "";
							$inp_duration_hh = "";
							$inp_duration_mm = "";
							$inp_duration_ss = "";
							$inp_notes = "";
							if(isset($_POST["inp_distance_$get_workout_session_main_id"])){
								$inp_distance = $_POST["inp_distance_$get_workout_session_main_id"];
								$inp_distance = str_replace(",", ".", $inp_distance);
								$inp_distance = output_html($inp_distance);
							} 

							if(isset($_POST["inp_distance_measurement_$get_workout_session_main_id"])){
								$inp_distance_measurement = $_POST["inp_distance_measurement_$get_workout_session_main_id"];
								$inp_distance_measurement = str_replace(",", ".", $inp_distance_measurement);
								$inp_distance_measurement = output_html($inp_distance_measurement);
							}

							if(isset($_POST["inp_velocity_a_$get_workout_session_main_id"])){
								$inp_velocity_a = $_POST["inp_velocity_a_$get_workout_session_main_id"];
								$inp_velocity_a = str_replace(",", ".", $inp_velocity_a);
								$inp_velocity_a = output_html($inp_velocity_a);
							}
							if(isset($_POST["inp_measurement_$get_workout_session_main_id"])){
								$inp_measurement = $_POST["inp_measurement_$get_workout_session_main_id"];
								$inp_measurement = str_replace(",", ".", $inp_measurement);
								$inp_measurement = output_html($inp_measurement);

							}

							if(isset($_POST["inp_duration_hh_$get_workout_session_main_id"])){
								$inp_duration_hh = $_POST["inp_duration_hh_$get_workout_session_main_id"];
								$inp_duration_hh = str_replace(",", ".", $inp_duration_hh);
								$inp_duration_hh = output_html($inp_duration_hh);
							}
							if(isset($_POST["inp_duration_mm_$get_workout_session_main_id"])){
								$inp_duration_mm = $_POST["inp_duration_mm_$get_workout_session_main_id"];
								$inp_duration_mm = str_replace(",", ".", $inp_duration_mm);
								$inp_duration_mm = output_html($inp_duration_mm);
							}
							if(isset($_POST["inp_duration_ss_$get_workout_session_main_id"])){
								$inp_duration_ss = $_POST["inp_duration_ss_$get_workout_session_main_id"];
								$inp_duration_ss = str_replace(",", ".", $inp_duration_ss);
								$inp_duration_ss = output_html($inp_duration_ss);
							}
							if(isset($_POST["inp_notes_$get_workout_session_main_id"])){
								$inp_notes = $_POST["inp_notes_$get_workout_session_main_id"];
								$inp_notes = str_replace(",", ".", $inp_notes);
								$inp_notes = output_html($inp_notes);
							}


							if($inp_distance != "" OR $inp_distance_measurement != "" OR $inp_velocity_a != "" OR
							   $inp_measurement != "" OR $inp_duration_hh != "" OR $inp_duration_mm != "" OR $inp_duration_ss != "" OR $inp_notes != ""){

							
								// Fetch workout diary entry ID
								$query_entry = "SELECT workout_diary_entry_id FROM $t_workout_diary_entries WHERE workout_diary_entry_user_id=$my_user_id_mysql AND workout_diary_entry_session_id=$session_id_mysql AND workout_diary_entry_session_main_id=$get_workout_session_main_id AND workout_diary_entry_year=$year AND workout_diary_entry_week=$week AND workout_diary_entry_exercise_id=$get_workout_session_main_exercise_id";
								$result_entry = mysqli_query($link, $query_entry);
								$row_entry = mysqli_fetch_row($result_entry);
								list($get_workout_diary_entry_id) = $row_entry;
								if($get_workout_diary_entry_id == ""){
									// Create it
									$inp_exercise_title_mysql = quote_smart($link, $get_workout_session_main_exercise_title);
									mysqli_query($link, "INSERT INTO $t_workout_diary_entries
									(workout_diary_entry_id, workout_diary_entry_user_id, workout_diary_entry_session_id, workout_diary_entry_session_main_id, workout_diary_entry_date, workout_diary_entry_year, workout_diary_entry_month, workout_diary_entry_day, workout_diary_entry_week, workout_diary_entry_exercise_id, workout_diary_entry_exercise_title) 
									VALUES 
									(NULL, $my_user_id_mysql, $get_workout_session_id, $get_workout_session_main_id, '$date', '$year', '$month', '$day', '$week', $get_workout_session_main_exercise_id, $inp_exercise_title_mysql)")
									or die(mysqli_error($link));

									// Get the new ID
									$query_entry = "SELECT workout_diary_entry_id FROM $t_workout_diary_entries WHERE workout_diary_entry_user_id=$my_user_id_mysql AND workout_diary_entry_session_id=$session_id_mysql AND workout_diary_entry_session_main_id=$get_workout_session_main_id AND workout_diary_entry_year=$year AND workout_diary_entry_week=$week AND workout_diary_entry_exercise_id=$get_workout_session_main_exercise_id";
									$result_entry = mysqli_query($link, $query_entry);
									$row_entry = mysqli_fetch_row($result_entry);
									list($get_workout_diary_entry_id) = $row_entry;
								}


								if($inp_distance != ""){
									$inp_distance_mysql = quote_smart($link, $inp_distance);
									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_distance=$inp_distance_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");
							
									$saved_animation = "inp_distance_" . $get_workout_session_main_id;
									$focus = "inp_distance_measurement_" . $get_workout_session_main_id;
								}
								if($inp_distance_measurement != ""){
									$inp_distance_measurement_mysql = quote_smart($link, $inp_distance_measurement);
									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_distance_measurement=$inp_distance_measurement_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");

									$saved_animation = "inp_distance_measurement_" . $get_workout_session_main_id;
									$focus = "inp_velocity_a_" . $get_workout_session_main_id;
								}
								if($inp_velocity_a != ""){
									$inp_velocity_a_mysql = quote_smart($link, $inp_velocity_a);
									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_velocity_a=$inp_velocity_a_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");

									$saved_animation = "inp_velocity_a_" . $get_workout_session_main_id;
									$focus = "inp_duration_mm_" . $get_workout_session_main_id;
								}
								if($inp_measurement != ""){
									$inp_measurement_mysql = quote_smart($link, $inp_measurement);
									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_measurement=$inp_measurement_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");

									$saved_animation = "inp_measurement_" . $get_workout_session_main_id;
								}
								if($inp_duration_hh != ""){
									$inp_duration_hh_mysql = quote_smart($link, $inp_duration_hh);
									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_duration_hh=$inp_duration_hh_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");

									$saved_animation = "inp_duration_hh_" . $get_workout_session_main_id;
								}
								if($inp_duration_mm != ""){
									$inp_duration_mm_mysql = quote_smart($link, $inp_duration_mm);
									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_duration_mm=$inp_duration_mm_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");

									$saved_animation = "inp_duration_mm_" . $get_workout_session_main_id;
									$focus = "inp_duration_ss_" . $get_workout_session_main_id;
								}
								if($inp_duration_ss != ""){
									$inp_duration_ss_mysql = quote_smart($link, $inp_duration_ss);
									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_duration_ss=$inp_duration_ss_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");

									$saved_animation = "inp_duration_ss_" . $get_workout_session_main_id;
									$focus = "inp_notes_" . $get_workout_session_main_id;
								}
								if($inp_notes != ""){
									$inp_notes_mysql = quote_smart($link, $inp_notes);
									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_notes=$inp_notes_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");

									$saved_animation = "inp_notes_" . $get_workout_session_main_id;
									$focus = "inp_distance_"; // the rest will be filled in in next iteration
									$focus_on_next = "true";
								}


								// Check if we have 2 of 3, if we have then we can calculate the last
								$query_entry = "SELECT workout_diary_entry_measurement, workout_diary_entry_velocity_a, workout_diary_entry_distance, workout_diary_entry_distance_measurement, workout_diary_entry_duration_hh, workout_diary_entry_duration_mm, workout_diary_entry_duration_ss FROM $t_workout_diary_entries WHERE workout_diary_entry_id='$get_workout_diary_entry_id'";
								$result_entry = mysqli_query($link, $query_entry);
								$row_entry = mysqli_fetch_row($result_entry);
								list($get_workout_diary_entry_measurement, $get_workout_diary_entry_velocity_a, $get_workout_diary_entry_distance, $get_workout_diary_entry_distance_measurement, $get_workout_diary_entry_duration_hh, $get_workout_diary_entry_duration_mm, $get_workout_diary_entry_duration_ss) = $row_entry;
							
								// Time
								$get_hh_seconds = $get_workout_diary_entry_duration_hh*360;
								$get_mm_seconds = $get_workout_diary_entry_duration_mm*60;
								$get_time_si = $get_hh_seconds+$get_mm_seconds+$get_workout_diary_entry_duration_ss;

								// Velocity
								$get_velocity_si = "$get_workout_diary_entry_measurement";
								if($get_workout_diary_entry_measurement == "km/h"){
									$get_velocity_si = $get_workout_diary_entry_velocity_a/3.6;
								}
								elseif($get_workout_diary_entry_measurement == "mi/h"){
									$get_velocity_si = $get_workout_diary_entry_velocity_a/0.44704;
								}
								elseif($get_workout_diary_entry_measurement == "mi/s"){
									$get_velocity_si = $get_workout_diary_entry_velocity_a/1609.344;
								}

								// Lenght
								$get_distance_si = "$get_workout_diary_entry_distance";
								if($get_workout_diary_entry_distance_measurement == "mi"){
									$get_distance_si = $get_workout_diary_entry_distance/1609.344;
								}
	
								// v = s t 

								if($get_time_si == "0" && $get_workout_diary_entry_distance != "" && $get_workout_diary_entry_velocity_a != ""){
									// Calcualte time
									// t= s/v
									$t_seconds = $get_distance_si/$get_velocity_si;

								
									$hours = floor($t_seconds / 3600);
									$minutes = floor(($t_seconds / 60) % 60);
									$seconds = $t_seconds % 60;

									$inp_workout_diary_entry_duration_hh = output_html($hours);
									$inp_workout_diary_entry_duration_hh_mysql = quote_smart($link, $inp_workout_diary_entry_duration_hh);

									$inp_workout_diary_entry_duration_mm = output_html($minutes);
									$inp_workout_diary_entry_duration_mm_mysql = quote_smart($link, $inp_workout_diary_entry_duration_mm);
								

									$inp_workout_diary_entry_duration_ss = output_html($seconds);
									$inp_workout_diary_entry_duration_ss_mysql = quote_smart($link, $inp_workout_diary_entry_duration_ss);
								
	
									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_duration_hh=$inp_workout_diary_entry_duration_hh_mysql, workout_diary_entry_duration_mm=$inp_workout_diary_entry_duration_mm_mysql, workout_diary_entry_duration_ss=$inp_workout_diary_entry_duration_ss_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");

								}

								if($get_workout_diary_entry_distance == "" && $get_time_si != "0" && $get_workout_diary_entry_velocity_a != ""){
									// Calcualte distance
									// s=vt
									$distance = $get_velocity_si * $get_time_si;
								

								
									if($get_workout_diary_entry_distance_measurement == "mi"){
										$distance = round($distance/1609.344, 0);
									}
									else{
										$distance = round($distance, 0);
									}

								
	
									$inp_distance = output_html($distance);
									$inp_distance_mysql = quote_smart($link, $inp_distance);
								

									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_distance=$inp_distance_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");

								}
								if($get_workout_diary_entry_distance != "" && $get_time_si != "0" && $get_workout_diary_entry_velocity_a == ""){
									// Calcualte velocity
									// v=s/t
									$velocity = $get_distance_si / $get_time_si;
								

									if($get_workout_diary_entry_measurement == "km/h"){
										$velocity = round($velocity*3.6, 1);
									}
									elseif($get_workout_diary_entry_measurement == "mi/h"){
										$velocity = round($velocity*0.44704, 1);
									}
									elseif($get_workout_diary_entry_measurement == "mi/s"){
										$velocity = round($velocity*1609.344, 1);
									}
									else{
										$velocity = round($velocity, 1);
									}



									$inp_velocity = output_html($velocity);
									$inp_velocity_mysql = quote_smart($link, $inp_velocity);
								
	
									$result = mysqli_query($link, "UPDATE $t_workout_diary_entries SET workout_diary_entry_velocity_a=$inp_velocity_mysql WHERE workout_diary_entry_id='$get_workout_diary_entry_id'");

								}

							} // cardio changes

						} // cardio
					}

					

					$url = "registrer_data.php?plan_id=$plan_id&weekly_id=$weekly_id&session_id=$session_id&focus=$focus&ft=success&fm=changes_saved&saved_animation=$saved_animation";
					header("Location: $url");
					exit;
				}			
				echo"
				<div style=\"float: right;padding-top: 15px;\">
					<p>$week / $year</p>
				</div>
				<h1>$get_workout_session_title</h1>

				<!-- Where am I ? -->
					<p><b>$l_you_are_here</b><br />
					<a href=\"select_session.php?plan_id=$plan_id&amp;l=$l\">$get_current_workout_weekly_title</a>
					-&gt;
					<a href=\"registrer_data.php?plan_id=$plan_id&amp;weekly_id=$weekly_id&amp;session_id=$session_id&amp;l=$l\">$get_workout_session_title</a>
					</p>
				<!-- //Where am I ? -->

				<!-- List all session main -->

					
					<!-- Focus -->";
					if(isset($_GET['focus'])){
						$focus = $_GET['focus'];
						$focus = output_html($focus);
						echo"
						<script>
						\$(document).ready(function(){
							\$('[name=\"$focus\"]').focus();
						});
						</script>";
					}
					echo"
					<!-- //Focus -->

					<!-- Saved animation -->";
					if(isset($_GET['saved_animation'])){
						$saved_animation = $_GET['saved_animation'];
						$saved_animation = output_html($saved_animation);
						
						echo"
						<script>
						\$(document).ready(function(){
							\$( \".saved_animation\" ).fadeOut().fadeIn().fadeOut().fadeIn(\"fast\").fadeOut(\"fast\");

							
						});
						</script>";


					}
					else{
						$saved_animation = "";
					}
					echo"
					<!-- //Saved animation -->

					<form method=\"post\" action=\"registrer_data.php?plan_id=$plan_id&amp;weekly_id=$weekly_id&amp;session_id=$session_id&amp;process=1\" enctype=\"multipart/form-data\">
	
						<table>	
					";
					$tabindex = 0;
					$previous_week = $week-1;
					$previous_year = $year;
					if($previous_week == "0"){
						$previous_week = "52";
						$previous_year = $year-1;
					}
					$query_sessions = "SELECT workout_session_main_id, workout_session_main_user_id, workout_session_main_session_id, workout_session_main_weight, workout_session_main_exercise_id, workout_session_main_exercise_title, workout_session_main_sets, workout_session_main_reps, workout_session_main_velocity_a, workout_session_main_velocity_b, workout_session_main_distance, workout_session_main_duration, workout_session_main_intensity, workout_session_main_text FROM $t_workout_plans_sessions_main WHERE workout_session_main_session_id=$get_workout_session_id ORDER BY workout_session_main_weight ASC";
					$result_sessions = mysqli_query($link, $query_sessions);
					while($row_sessions = mysqli_fetch_row($result_sessions)) {
						list($get_workout_session_main_id, $get_workout_session_main_user_id, $get_workout_session_main_session_id, $get_workout_session_main_weight, $get_workout_session_main_exercise_id, $get_workout_session_main_exercise_title, $get_workout_session_main_sets, $get_workout_session_main_reps, $get_workout_session_main_velocity_a, $get_workout_session_main_velocity_b, $get_workout_session_main_distance, $get_workout_session_main_duration, $get_workout_session_main_intensity, $get_workout_session_main_text) = $row_sessions;


						// Fetch this weeks weight, sets, velocity etc
						$query_entry = "SELECT workout_diary_entry_id, workout_diary_entry_user_id, workout_diary_entry_session_id, workout_diary_entry_session_main_id, workout_diary_entry_date, workout_diary_entry_year, workout_diary_entry_month, workout_diary_entry_day, workout_diary_entry_week, workout_diary_entry_exercise_id, workout_diary_entry_exercise_title, workout_diary_entry_measurement, workout_diary_entry_set_a_weight, workout_diary_entry_set_a_reps, workout_diary_entry_set_b_weight, workout_diary_entry_set_b_reps, workout_diary_entry_set_c_weight, workout_diary_entry_set_c_reps, workout_diary_entry_set_d_weight, workout_diary_entry_set_d_reps, workout_diary_entry_set_e_weight, workout_diary_entry_set_e_reps, workout_diary_entry_set_avg_weight, workout_diary_entry_set_avg_reps, workout_diary_entry_velocity_a, workout_diary_entry_velocity_b, workout_diary_entry_distance, workout_diary_entry_distance_measurement, workout_diary_entry_duration_hh, workout_diary_entry_duration_mm, workout_diary_entry_duration_ss, workout_diary_entry_notes FROM $t_workout_diary_entries WHERE workout_diary_entry_user_id=$my_user_id_mysql AND workout_diary_entry_session_id=$session_id_mysql AND workout_diary_entry_session_main_id=$get_workout_session_main_id AND workout_diary_entry_year=$year AND workout_diary_entry_week=$week AND workout_diary_entry_exercise_id=$get_workout_session_main_exercise_id";
						$result_entry = mysqli_query($link, $query_entry);
						$row_entry = mysqli_fetch_row($result_entry);
						list($get_this_week_workout_diary_entry_id, $get_this_week_workout_diary_entry_user_id, $get_this_week_workout_diary_entry_session_id, $get_this_week_workout_diary_entry_session_main_id, $get_this_week_workout_diary_entry_date, $get_this_week_workout_diary_entry_year, $get_this_week_workout_diary_entry_month, $get_this_week_workout_diary_entry_day, $get_this_week_workout_diary_entry_week, $get_this_week_workout_diary_entry_exercise_id, $get_this_week_workout_diary_entry_exercise_title, $get_this_week_workout_diary_entry_measurement, $get_this_week_workout_diary_entry_set_a_weight, $get_this_week_workout_diary_entry_set_a_reps, $get_this_week_workout_diary_entry_set_b_weight, $get_this_week_workout_diary_entry_set_b_reps, $get_this_week_workout_diary_entry_set_c_weight, $get_this_week_workout_diary_entry_set_c_reps, $get_this_week_workout_diary_entry_set_d_weight, $get_this_week_workout_diary_entry_set_d_reps, $get_this_week_workout_diary_entry_set_e_weight, $get_this_week_workout_diary_entry_set_e_reps, $get_this_week_workout_diary_entry_set_avg_weight, $get_this_week_workout_diary_entry_set_avg_reps, $get_this_week_workout_diary_entry_velocity_a, $get_this_week_workout_diary_entry_velocity_b, $get_this_week_workout_diary_entry_distance, $get_this_week_workout_diary_entry_distance_measurement, $get_this_week_workout_diary_entry_duration_hh, $get_this_week_workout_diary_entry_duration_mm, $get_this_week_workout_diary_entry_duration_ss, $get_this_week_workout_diary_entry_notes) = $row_entry;



						// Fetch last weeks sets, velocity etc
						$query_entry = "SELECT workout_diary_entry_id, workout_diary_entry_user_id, workout_diary_entry_session_id, workout_diary_entry_session_main_id, workout_diary_entry_date, workout_diary_entry_year, workout_diary_entry_month, workout_diary_entry_day, workout_diary_entry_week, workout_diary_entry_exercise_id, workout_diary_entry_exercise_title, workout_diary_entry_measurement, workout_diary_entry_set_a_weight, workout_diary_entry_set_a_reps, workout_diary_entry_set_b_weight, workout_diary_entry_set_b_reps, workout_diary_entry_set_c_weight, workout_diary_entry_set_c_reps, workout_diary_entry_set_d_weight, workout_diary_entry_set_d_reps, workout_diary_entry_set_e_weight, workout_diary_entry_set_e_reps, workout_diary_entry_set_avg_weight, workout_diary_entry_set_avg_reps, workout_diary_entry_velocity_a, workout_diary_entry_velocity_b, workout_diary_entry_distance, workout_diary_entry_distance_measurement, workout_diary_entry_duration_hh, workout_diary_entry_duration_mm, workout_diary_entry_duration_ss, workout_diary_entry_notes FROM $t_workout_diary_entries WHERE workout_diary_entry_user_id=$my_user_id_mysql AND workout_diary_entry_session_id=$session_id_mysql AND workout_diary_entry_session_main_id=$get_workout_session_main_id AND workout_diary_entry_exercise_id=$get_workout_session_main_exercise_id AND workout_diary_entry_year=$previous_year AND workout_diary_entry_week < $previous_week LIMIT 0,1";
						$result_entry = mysqli_query($link, $query_entry);
						$row_entry = mysqli_fetch_row($result_entry);
						list($get_previous_workout_diary_entry_id, $get_previous_workout_diary_entry_user_id, $get_previous_workout_diary_entry_session_id, $get_previous_workout_diary_entry_session_main_id, $get_previous_workout_diary_entry_date, $get_previous_workout_diary_entry_year, $get_previous_workout_diary_entry_month, $get_previous_workout_diary_entry_day, $get_previous_workout_diary_entry_week, $get_previous_workout_diary_entry_exercise_id, $get_previous_workout_diary_entry_exercise_title, $get_previous_workout_diary_entry_measurement, $get_previous_workout_diary_entry_set_a_weight, $get_previous_workout_diary_entry_set_a_reps, $get_previous_workout_diary_entry_set_b_weight, $get_previous_workout_diary_entry_set_b_reps, $get_previous_workout_diary_entry_set_c_weight, $get_previous_workout_diary_entry_set_c_reps, $get_previous_workout_diary_entry_set_d_weight, $get_previous_workout_diary_entry_set_d_reps, $get_previous_workout_diary_entry_set_e_weight, $get_previous_workout_diary_entry_set_e_reps, $get_previous_workout_diary_entry_set_avg_weight, $get_previous_workout_diary_entry_set_avg_reps, $get_previous_workout_diary_entry_velocity_a, $get_previous_workout_diary_entry_velocity_b, $get_previous_workout_diary_entry_distance, $get_previous_workout_diary_entry_distance_measurement, $get_previous_workout_diary_entry_duration_hh, $get_previous_workout_diary_entry_duration_mm, $get_previous_workout_diary_entry_duration_ss, $get_previous_workout_diary_entry_notes) = $row_entry;

						echo"
						 <tr>
						  <td class=\"new_workout_session_main_id\">
							<p class=\"workout_session_main_exercise_title_p\">
							<a href=\"$root/exercises/view_exercise.php?exercise_id=$get_workout_session_main_exercise_id&amp;l=$l\" class=\"workout_session_main_exercise_title_link\">$get_workout_session_main_exercise_title</a>
							</p>
						  </td>
						  <td class=\"new_workout_session_main_id\" style=\"padding-left: 10px\">
							<p class=\"workout_session_main_exercise_title_p\">";
							// Sets and reps?
							if($get_workout_session_main_sets != "0" && $get_workout_session_main_reps != "0"){

								echo"$get_workout_session_main_sets&nbsp;x&nbsp;$get_workout_session_main_reps";
							}
							else{
								if($get_workout_session_main_distance != "" && $get_workout_session_main_distance != "0"){


									echo"$get_workout_session_main_distance ";
									if($get_my_user_measurement == "m" OR $get_my_user_measurement == "metric"){
										echo"m";
									}
									else{
										echo"mi";
									}
								}
								else{
									if($get_workout_session_main_duration != "" && $get_workout_session_main_duration != "0"){
										echo"$get_workout_session_main_duration ";
										echo"$l_min_lowercase";
									}
								}

							}
							echo"</p>
						  </td>
						 </tr>
						 <tr>
						  <td colspan=\"2\">
						";
						// Sets and reps?
						if($get_workout_session_main_sets != "0" && $get_workout_session_main_reps != "0"){
							// Measurment
							$this_week_measurement = "";
							if($get_this_week_workout_diary_entry_measurement == "kg"){
								$this_week_measurement = "kg";
							}
							elseif($get_this_week_workout_diary_entry_measurement == "lbs"){
								$this_week_measurement = "lbs";
							}
							else{
								if($get_previous_workout_diary_entry_measurement == "kg"){
									$this_week_measurement = "kg";
								}
								elseif($get_previous_workout_diary_entry_measurement == "lbs"){
									$this_week_measurement = "lbs";
								}
								else{
									if($get_my_user_measurement == "m" OR $get_my_user_measurement == "metric"){
										$this_week_measurement = "kg";
									}
									else{
										$this_week_measurement = "lbs";
									}
								}
							}


							// Strenght Now and Previous
							echo"
							<table>
							 <tr>
							  <td style=\"padding: 0px 4px 8px 0px;\">
								<span><input type=\"text\" name=\"inp_set_a_weight_$get_workout_session_main_id\" size=\"3\" value=\"$get_this_week_workout_diary_entry_set_a_weight\" class=\"inp_set_weight\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></span>\n";

								if($saved_animation == "inp_set_a_weight_$get_workout_session_main_id"){
									echo"<img src=\"_gfx/saved_animation.png\" class=\"saved_animation\" alt=\"saved_animation.png\" />";
								}
								echo"
							  </td>
							  <td style=\"padding: 0px 4px 8px 0px;\">
								<span>
								<select name=\"inp_measurement_$get_workout_session_main_id\" class=\"inp_measurement\">
									<option value=\"kg\""; if($this_week_measurement == "kg"){ echo" selected=\"selected\""; } echo">kg</option>
									<option value=\"lbs\""; if($this_week_measurement == "lbs"){ echo" selected=\"selected\""; } echo">lbs</option>
								</select>
								</span>";

								if($saved_animation == "inp_measurement_$get_workout_session_main_id"){
									echo"<img src=\"_gfx/saved_animation.png\" class=\"saved_animation\" alt=\"saved_animation.png\" />";
								}
								echo"
							  </td>
							  <td style=\"padding: 0px 4px 8px 0px;\">
								<span><input type=\"text\" name=\"inp_set_a_reps_$get_workout_session_main_id\" size=\"2\" value=\"$get_this_week_workout_diary_entry_set_a_reps\" class=\"inp_set_reps\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></span>\n";

								if($saved_animation == "inp_set_a_reps_$get_workout_session_main_id"){
									echo"<img src=\"_gfx/saved_animation.png\" class=\"saved_animation\" alt=\"saved_animation.png\" />";
								}
								echo"
							  </td>
							  <td style=\"padding: 0px 4px 8px 0px;\">
								
								";
								if($get_workout_session_main_sets > 1){
									echo"<span><input type=\"text\" name=\"inp_set_b_reps_$get_workout_session_main_id\" size=\"2\" value=\"$get_this_week_workout_diary_entry_set_b_reps\" class=\"inp_set_reps\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></span>\n";
								}

								if($saved_animation == "inp_set_b_reps_$get_workout_session_main_id"){
									echo"<img src=\"_gfx/saved_animation.png\" class=\"saved_animation\" alt=\"saved_animation.png\" />";
								}
								echo"
							  </td>
							  <td style=\"padding: 0px 4px 8px 0px;\">
								";
								if($get_workout_session_main_sets > 2){
									echo"<span><input type=\"text\" name=\"inp_set_c_reps_$get_workout_session_main_id\" size=\"2\" value=\"$get_this_week_workout_diary_entry_set_c_reps\" class=\"inp_set_reps\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></span>\n";
								}
								if($saved_animation == "inp_set_c_reps_$get_workout_session_main_id"){
									echo"<img src=\"_gfx/saved_animation.png\" class=\"saved_animation\" alt=\"saved_animation.png\" />";
								}
								echo"
							  </td>
							  <td style=\"padding: 0px 4px 8px 0px;\">
								";
								if($get_workout_session_main_sets > 3){ 
									echo"<span><input type=\"text\" name=\"inp_set_d_reps_$get_workout_session_main_id\" size=\"2\" value=\"$get_this_week_workout_diary_entry_set_d_reps\" class=\"inp_set_reps\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></span>\n";
								}
								if($saved_animation == "inp_set_d_reps_$get_workout_session_main_id"){
									echo"<img src=\"_gfx/saved_animation.png\" class=\"saved_animation\" alt=\"saved_animation.png\" />";
								}
								echo"
							  </td>
							 </tr>

							 <tr>
							  <td style=\"padding: 0px 4px 8px 0px;text-align: right;\">
								<span class=\"set_weight_previous\">$get_previous_workout_diary_entry_set_a_weight</span>
							  </td>
							  <td style=\"padding: 0px 4px 8px 0px;text-align:center;\">
								<span class=\"kg_lbs_previous\">$get_previous_workout_diary_entry_measurement</span>
							  </td>
							  <td style=\"padding: 0px 4px 8px 0px;text-align: center;\">
								<span class=\"set_reps_previous\">$get_previous_workout_diary_entry_set_a_reps</span>
							  </td>
							  <td style=\"padding: 0px 4px 8px 0px;text-align: center;\">
								<span class=\"set_reps_previous\">$get_previous_workout_diary_entry_set_b_reps</span>
							  </td>
							  <td style=\"padding: 0px 4px 8px 0px;text-align: center;\">
								<span class=\"set_reps_previous\">$get_previous_workout_diary_entry_set_c_reps</span>
							  </td>
							  <td style=\"padding: 0px 4px 8px 0px;text-align: center;\">
								<span class=\"set_reps_previous\">$get_previous_workout_diary_entry_set_d_reps</span>
							  </td>
							 </tr>
							</table>
							";
						}
						// Cardio?
						if(($get_workout_session_main_distance != "" && $get_workout_session_main_distance != "0") OR $get_workout_session_main_duration != "" && $get_workout_session_main_duration != "0"){

							// Distance Measurment
							$this_week_distance_measurement = "";
							if($get_this_week_workout_diary_entry_distance_measurement == "m"){
								$this_week_distance_measurement = "m";
							}
							elseif($get_this_week_workout_diary_entry_distance_measurement == "mi"){
								$this_week_distance_measurement = "mi";
							}
							else{
								if($get_previous_workout_diary_entry_distance_measurement == "m"){
									$this_week_distance_measurement = "m";
								}
								elseif($get_previous_workout_diary_entry_distance_measurement == "mi"){
									$this_week_distance_measurement = "mi";
								}
								else{
									if($get_my_user_measurement == "m" OR $get_my_user_measurement == "metric"){
										$this_week_distance_measurement = "m";
									}
									else{
										$this_week_distance_measurement = "mi";
									}
								}
							}


							// Measurment
							$this_week_measurement = "";
							if($get_this_week_workout_diary_entry_measurement == "km/h"){
								$this_week_measurement = "km/h";
							}
							elseif($get_this_week_workout_diary_entry_measurement == "m/s"){
								$this_week_measurement = "m/s";
							}
							elseif($get_this_week_workout_diary_entry_measurement == "mi/h"){
								$this_week_measurement = "mi/h";
							}
							elseif($get_this_week_workout_diary_entry_measurement == "mi/s"){
								$this_week_measurement = "mi/s";
							}
							else{
								if($get_previous_workout_diary_entry_measurement == "kg"){
									$this_week_measurement = "km/h";
								}
								elseif($get_previous_workout_diary_entry_measurement == "m/s"){
									$this_week_measurement = "m/s";
								}
								elseif($get_previous_workout_diary_entry_measurement == "mi/h"){
									$this_week_measurement = "mi/h";
								}
								elseif($get_previous_workout_diary_entry_measurement == "mi/s"){
									$this_week_measurement = "mi/s";
								}
								else{
									if($get_my_user_measurement == "m" OR $get_my_user_measurement == "metric"){
										$this_week_measurement = "km/h";
									}
									else{
										$this_week_measurement = "mi/h";
									}
								}
							}


							// Cardio Now and Previous
							echo"
							<table>
							 <tr>
							  <td style=\"padding: 0px 4px 8px 0px;\">
								<img src=\"_gfx/icons/18x18/outline_transfer_within_a_station_black_18dp.png\" alt=\"outline_transfer_within_a_station_black_18dp.png\" />
							  </td>
							  <td style=\"padding: 0px 4px 8px 0px;\">
								<span>
								<input type=\"text\" name=\"inp_distance_$get_workout_session_main_id\" size=\"3\" value=\"$get_this_week_workout_diary_entry_distance\" class=\"inp_distance\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
							
								<select name=\"inp_distance_measurement_$get_workout_session_main_id\" class=\"inp_measurement\">
									<option value=\"m\""; if($this_week_distance_measurement == "m"){ echo" selected=\"selected\""; } echo">m</option>
									<option value=\"mi\""; if($this_week_distance_measurement == "mi"){ echo" selected=\"selected\""; } echo">mi</option>
								</select>
								</span>
							  </td>
							  <td style=\"padding: 0px 0px 8px 0px;\">
								<span class=\"distance_prev\">
									$get_previous_workout_diary_entry_distance
									$get_previous_workout_diary_entry_distance_measurement
								</span>
							  </td>
							 </tr>

							 <tr>
							  <td style=\"padding: 0px 4px 8px 0px;\">
								<img src=\"_gfx/icons/18x18/outline_network_check_black_18dp.png\" alt=\"outline_network_check_black_18dp.png\" />
							  </td>
							  <td style=\"padding: 0px 4px 8px 0px;\">
								<span>
								<input type=\"text\" name=\"inp_velocity_a_$get_workout_session_main_id\" size=\"2\" value=\"$get_this_week_workout_diary_entry_velocity_a\" class=\"inp_velocity_a\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
							
								<select name=\"inp_measurement_$get_workout_session_main_id\" class=\"inp_measurement\">
									<option value=\"km/h\""; if($this_week_measurement == "km/h"){ echo" selected=\"selected\""; } echo">km/h</option>
									<option value=\"m/s\""; if($this_week_measurement == "m/s"){ echo" selected=\"selected\""; } echo">m/s</option>
									<option value=\"mi/h\""; if($this_week_measurement == "mi/h"){ echo" selected=\"selected\""; } echo">mi/h</option>
									<option value=\"mi/s\""; if($this_week_measurement == "mi/s"){ echo" selected=\"selected\""; } echo">mi/s</option>
								</select>
								</span>
							  </td>
							  <td style=\"padding: 0px 0px 8px 0px;\">
								<span class=\"velocity_a_prev\">$get_previous_workout_diary_entry_velocity_a
								$get_previous_workout_diary_entry_measurement</span>
							  </td>
							 </tr>
							 <tr>
							  <td style=\"padding: 0px 4px 8px 0px;\">
								<img src=\"_gfx/icons/18x18/outline_access_time_black_18dp.png\" alt=\"outline_access_time_black_18dp.png\" />
							  </td>
							  <td style=\"padding: 0px 4px 8px 0px;\">
								<span class=\"duration\">
								<input type=\"text\" name=\"inp_duration_mm_$get_workout_session_main_id\" size=\"2\" value=\"$get_this_week_workout_diary_entry_duration_mm\" class=\"inp_duration_mm\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
								:
								<input type=\"text\" name=\"inp_duration_ss_$get_workout_session_main_id\" size=\"2\" value=\"$get_this_week_workout_diary_entry_duration_ss\" class=\"inp_duration_ss\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
								</span>
							  </td>
							  <td style=\"padding: 0px 0px 6px 0px;\">
								<span class=\"duration_prev\">
								";
								if($get_previous_workout_diary_entry_duration_mm != ""){
									echo"$get_previous_workout_diary_entry_duration_mm";
								}
								if($get_previous_workout_diary_entry_duration_mm != "" && $get_previous_workout_diary_entry_duration_ss != ""){
									echo":";
								}
								if($get_previous_workout_diary_entry_duration_ss != ""){
									echo"$get_previous_workout_diary_entry_duration_ss";
								}
								echo"
								</span>
							  </td>
							 </tr>
							</table>

							<table>
							 <tr>
							  <td style=\"padding: 0px 4px 8px 0px;\">
								<img src=\"_gfx/icons/18x18/outline_insert_comment_black_18dp.png\" alt=\"outline_insert_comment_black_18dp.png\" />
							  </td>
							  <td style=\"padding: 0px 4px 8px 0px;\">
								<span>
								<input type=\"text\" name=\"inp_notes_$get_workout_session_main_id\" value=\"$get_this_week_workout_diary_entry_notes\" size=\"20\" class=\"inp_notes\" /><br />
								</span>
								<span class=\"notes_prev\">$get_previous_workout_diary_entry_notes</span>
							  </td>
							 </tr>
							</table>

							";
						}
						echo"
						  </td>
						 </tr>
						";
					}
					echo"
						</table>
					<p>
					<input type=\"submit\" value=\"$l_save_changes\" class=\"btn\" />
					</p>
					</form>
				<!-- //List all session main -->
				";
			} // session found
		} // weekly found

	} // workout diary plan found

} // logged in
else{
	include("index_not_logged_in.php");
}


/*- Footer ----------------------------------------------------------------------------------- */
if(!(isset($define_in_index))){
	include("$root/_webdesign/footer.php");
}
?>