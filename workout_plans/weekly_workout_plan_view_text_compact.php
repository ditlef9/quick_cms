<?php 
/**
*
* File: workout_plans/weekly_workout_plan_view_text_compact.php
* Version 1.0.0
* Date 12:05 10.02.2018
* Copyright (c) 2011-2018 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/



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

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/workout_plans/ts_new_workout_plan.php");
include("$root/_admin/_translations/site/$l/workout_plans/ts_yearly_workout_plan_edit.php");

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['weekly_id'])){
	$weekly_id = $_GET['weekly_id'];
	$weekly_id = output_html($weekly_id);
}
else{
	$weekly_id = "";
}
if(isset($_GET['max_session_main_lenght'])){
	$max_session_main_lenght = $_GET['max_session_main_lenght'];
	$max_session_main_lenght = output_html($max_session_main_lenght);
}
else{
	$max_session_main_lenght = "";
}

$tabindex = 0;
$l_mysql = quote_smart($link, $l);

/*- Function ------------------------------------------------------------------------- */
function remove_html_tags($value){
	
	$value = str_replace("&aelig;", "æ", "$value"); // &#230;
	$value = str_replace("&oslash;","ø", "$value"); // &#248;
	$value = str_replace("&aring;", "å", "$value"); // &#229;
	$value = str_replace("&Aelig;", "Æ", "$value"); // &#198;
	$value = str_replace("&Oslash;","Ø", "$value"); // &#216;
	$value = str_replace("&Aring;", "Å", "$value"); // &#197;

	$value = str_replace("·", "·", "$value"); // &#197;

	return $value;
}


// Get workout plan weekly
$weekly_id_mysql = quote_smart($link, $weekly_id);
$query = "SELECT workout_weekly_id, workout_weekly_user_id, workout_weekly_period_id, workout_weekly_weight, workout_weekly_language, workout_weekly_title, workout_weekly_title_clean, workout_weekly_introduction, workout_weekly_goal, workout_weekly_image_path, workout_weekly_image_file, workout_weekly_created, workout_weekly_updated, workout_weekly_unique_hits, workout_weekly_unique_hits_ip_block, workout_weekly_comments, workout_weekly_likes, workout_weekly_dislikes, workout_weekly_rating, workout_weekly_ip_block, workout_weekly_user_ip, workout_weekly_notes FROM $t_workout_plans_weekly WHERE workout_weekly_id=$weekly_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_workout_weekly_id, $get_current_workout_weekly_user_id, $get_current_workout_weekly_period_id, $get_current_workout_weekly_weight, $get_current_workout_weekly_language, $get_current_workout_weekly_title, $get_current_workout_weekly_title_clean, $get_current_workout_weekly_introduction, $get_current_workout_weekly_goal, $get_current_workout_weekly_image_path, $get_current_workout_weekly_image_file, $get_current_workout_weekly_created, $get_current_workout_weekly_updated, $get_current_workout_weekly_unique_hits, $get_current_workout_weekly_unique_hits_ip_block, $get_current_workout_weekly_comments, $get_current_workout_weekly_likes, $get_current_workout_weekly_dislikes, $get_current_workout_weekly_rating, $get_current_workout_weekly_ip_block, $get_current_workout_weekly_user_ip, $get_current_workout_weekly_notes) = $row;
if($get_current_workout_weekly_id == ""){

	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "Server error 404 - $l_workout_plans";
	include("$root/_webdesign/header.php");
	echo"<h1>Server error 404</h1><p>Plan not found</p>";
	include("$root/_webdesign/footer.php");
	
}
else{

	/*- Header ---------------------------------------------------------------------------------- */


	$fh = fopen("$root/_cache/workout_weekly_id_compact_$get_current_workout_weekly_id.txt", "w") or die("can not open file");
	fwrite($fh, "$get_current_workout_weekly_title
");
	fclose($fh);

	$title_len = strlen($get_current_workout_weekly_title);
	for($x=0;$x<$title_len;$x++){
		$fh = fopen("$root/_cache/workout_weekly_id_compact_$get_current_workout_weekly_id.txt", "a+") or die("can not open file");
		fwrite($fh, "-");
		fclose($fh);

	}
	$fh = fopen("$root/_cache/workout_weekly_id_compact_$get_current_workout_weekly_id.txt", "a+") or die("can not open file");
	fwrite($fh, "
");
	fclose($fh);
	

	/* Sessions */
	$query = "SELECT workout_session_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity FROM $t_workout_plans_sessions WHERE workout_session_weekly_id=$get_current_workout_weekly_id ORDER BY workout_session_weight ASC";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_workout_session_id, $get_workout_session_weight, $get_workout_session_title, $get_workout_session_title_clean, $get_workout_session_duration, $get_workout_session_intensity) = $row;

		// Remove HTML tags
		$get_workout_session_title = remove_html_tags($get_workout_session_title);

		$fh = fopen("$root/_cache/workout_weekly_id_compact_$get_current_workout_weekly_id.txt", "a+") or die("can not open file");
		fwrite($fh, "
$get_workout_session_title
");
		fclose($fh);

		
		/* List sessions_main */
		$human_counter = 1;
		$query_sessions = "SELECT workout_session_main_id, workout_session_main_user_id, workout_session_main_session_id, workout_session_main_weight, workout_session_main_exercise_id, workout_session_main_exercise_title, workout_session_main_sets, workout_session_main_reps, workout_session_main_velocity_a, workout_session_main_velocity_b, workout_session_main_distance, workout_session_main_duration, workout_session_main_intensity, workout_session_main_text FROM $t_workout_plans_sessions_main WHERE workout_session_main_session_id=$get_workout_session_id ORDER BY workout_session_main_weight ASC";
		$result_sessions = mysqli_query($link, $query_sessions);
		while($row_sessions = mysqli_fetch_row($result_sessions)) {
			list($get_workout_session_main_id, $get_workout_session_main_user_id, $get_workout_session_main_session_id, $get_workout_session_main_weight, $get_workout_session_main_exercise_id, $get_workout_session_main_exercise_title, $get_workout_session_main_sets, $get_workout_session_main_reps, $get_workout_session_main_velocity_a, $get_workout_session_main_velocity_b, $get_workout_session_main_distance, $get_workout_session_main_duration, $get_workout_session_main_intensity, $get_workout_session_main_text) = $row_sessions;

			// Remove HTML tags
			$get_workout_session_main_exercise_title = remove_html_tags($get_workout_session_main_exercise_title);
			$get_workout_session_main_text = remove_html_tags($get_workout_session_main_text);

			// Lenght
			$session_main_len = strlen("$human_counter $get_workout_session_main_exercise_title ($get_workout_session_main_text)");
			if($max_session_main_lenght < $session_main_len){
				$max_session_main_lenght = "$session_main_len";
			}


			// Session main title
			$fh = fopen("$root/_cache/workout_weekly_id_compact_$get_current_workout_weekly_id.txt", "a+") or die("can not open file");
			fwrite($fh, "$human_counter $get_workout_session_main_exercise_title");
			fclose($fh);
			
			if($get_workout_session_main_text != ""){
				$fh = fopen("$root/_cache/workout_weekly_id_compact_$get_current_workout_weekly_id.txt", "a+") or die("can not open file");
				fwrite($fh, " ($get_workout_session_main_text)");
				fclose($fh);
			}

			// Spaces before extra data
			$rest = $max_session_main_lenght-$session_main_len;
			$rest = $rest+1;
			for($x=0;$x<$rest;$x++){
				$fh = fopen("$root/_cache/workout_weekly_id_compact_$get_current_workout_weekly_id.txt", "a+") or die("can not open file");
				fwrite($fh, " ");
				fclose($fh);
			}
			
			$extra_data = "";
			$middot = false;
			if($get_workout_session_main_sets != 0 && $get_workout_session_main_reps != 0){
				$extra_data = $extra_data . "$get_workout_session_main_sets" . "x" . "$get_workout_session_main_reps";
				$middot = true;
			}
			if($get_workout_session_main_velocity_a != 0 && $get_workout_session_main_velocity_b != 0){
				if($middot == "true"){
					$extra_data = $extra_data . " · ";
				}
				$middot = true;
				$extra_data = $extra_data . "$get_workout_session_main_velocity_a - $get_workout_session_main_velocity_b km/h";
			}
			else{
				if($get_workout_session_main_velocity_a != 0){
				if($middot == "true"){
					$extra_data = $extra_data .  "· ";
				}
				$middot = true;
				$extra_data = $extra_data . "$get_workout_session_main_velocity_a km/h";
				}
				if($get_workout_session_main_velocity_b != 0){
					if($middot == "true"){
						$extra_data = $extra_data .  "· ";
					}
					$middot = true;
					$extra_data = $extra_data . "$get_workout_session_main_velocity_b km/h";
				}
			}
			if($get_workout_session_main_distance != 0){
				if($middot == "true"){
					$extra_data = $extra_data .  "· ";
				}
				$middot = true;
				$extra_data = $extra_data . "$get_workout_session_main_distance m";
			}
			if($get_workout_session_main_duration != 0){
				if($middot == "true"){
					$extra_data = $extra_data .  "· ";
				}
				$middot = true;
				$extra_data = $extra_data . "$get_workout_session_main_duration $l_min_lowercase";
			}
			if($get_workout_session_main_intensity != 0){
				if($middot == "true"){
					$extra_data = $extra_data . " · ";
				}
				$middot = true;
				$extra_data = $extra_data . "$get_workout_session_main_intensity %";
			}
			$fh = fopen("$root/_cache/workout_weekly_id_compact_$get_current_workout_weekly_id.txt", "a+") or die("can not open file");
			fwrite($fh, "$extra_data");
			fclose($fh);

			// Column after extra data
			$fh = fopen("$root/_cache/workout_weekly_id_compact_$get_current_workout_weekly_id.txt", "a+") or die("can not open file");
			fwrite($fh, ": 
");
			fclose($fh);

			$human_counter++;
		} // get session main

	} // get sessions
	
	/* Copyright */
	$server_name = $_SERVER["SERVER_NAME"];
	$server_name = ucfirst($server_name);
	$year = date("Y");

	$fh = fopen("$root/_cache/workout_weekly_id_compact_$get_current_workout_weekly_id.txt", "a+") or die("can not open file");
	fwrite($fh, "
Copyright $year $server_name
");
	fclose($fh);

	if(isset($_GET['max_session_main_lenght'])){
		header("Location: $root/_cache/workout_weekly_id_compact_$get_current_workout_weekly_id.txt");
	}
	else{
		$website_title = "$l_workout_plans - $get_workout_session_title (Txt file version)";
		if(file_exists("./favicon.ico")){ $root = "."; }
		elseif(file_exists("../favicon.ico")){ $root = ".."; }
		elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
		elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
		include("$root/_webdesign/header.php");
		echo"<h1>Loading</h1><p>Generating....</p>";
		echo"<meta http-equiv=refresh content=\"1; URL=weekly_workout_plan_view_text_compact.php?weekly_id=$weekly_id&amp;l=$l&amp;max_session_main_lenght=$max_session_main_lenght\">";
		include("$root/_webdesign/footer.php");

	}
}

?>