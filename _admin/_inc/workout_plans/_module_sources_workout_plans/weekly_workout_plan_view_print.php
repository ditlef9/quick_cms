<?php 
/**
*
* File: workout_plans/weekly_workout_plan_view_print.php
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


$tabindex = 0;
$l_mysql = quote_smart($link, $l);


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
	/*- Print variables */
	include("$root/_admin/_data/logo.php");
	$server_name = $_SERVER["SERVER_NAME"];
	$server_name = ucfirst($server_name);
	$year = date("Y");
	


	/*- Headers ---------------------------------------------------------------------------------- */
	echo"<!DOCTYPE html>
<html lang=\"en\">
<head>
	<meta charset=iso-8859-1 />
	<title>$get_current_workout_weekly_title (Print version)</title>
	<style type=\"text/css\">
	h1 {
		color: #000;
		font: normal 25px 'Open Sans',sans-serif;
	}
	h2 {
		color: #000;
		font: normal 20px 'Open Sans',sans-serif;
	}
	p {
		color: #000;
		font: normal 15px 'Open Sans',sans-serif;
	}
	span {
		color: #000;
		font: normal 15px 'Open Sans',sans-serif;
	}
	a {
		color: #194d94;
		font: normal 15px 'Open Sans',sans-serif;
		text-decoration: none;
	}
	a:hover {
		color: #c1452b;
		text-decoration: none;
	}
	.clear{
		clear: both;
	}
	.pagebreak{
		page-break-before: always
	}

	</style>
</head>
<body>
<section id=\"wrapper\">";


	/*- Content ---------------------------------------------------------------------------------- */

	echo"
	<!-- Headline -->
		<table style=\"width: 100%;\">
		 <tr>
		  <td>
			<h1>$get_current_workout_weekly_title</h1>
		  </td>
		  <td>
			<img src=\"$root/$logoPathSav/$logoFilePdfSav\" alt=\"$logoFilePdfSav\" style=\"float: right;padding-bottom: 10px;\" />
		  </td>
		 </tr>
		</table>
	<!-- //Headline -->
	
	



	<!-- Sessions -->

		<div style=\"float: left;width: 48%;margin-right:2%;\">
		";

		$exercise_ids_array = array();

		$session_count = 0;
		$query = "SELECT workout_session_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity FROM $t_workout_plans_sessions WHERE workout_session_weekly_id=$get_current_workout_weekly_id ORDER BY workout_session_weight ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_workout_session_id, $get_workout_session_weight, $get_workout_session_title, $get_workout_session_title_clean, $get_workout_session_duration, $get_workout_session_intensity) = $row;


			echo"
			<p style=\"padding-bottom:0;margin-bottom:0;\"><b>$get_workout_session_title</b></p>
			<table>

			<!-- List sessions_main -->
				";

				$human_counter = 1;
				$query_sessions = "SELECT workout_session_main_id, workout_session_main_user_id, workout_session_main_session_id, workout_session_main_weight, workout_session_main_exercise_id, workout_session_main_exercise_title, workout_session_main_sets, workout_session_main_reps, workout_session_main_velocity_a, workout_session_main_velocity_b, workout_session_main_distance, workout_session_main_duration, workout_session_main_intensity, workout_session_main_text FROM $t_workout_plans_sessions_main WHERE workout_session_main_session_id=$get_workout_session_id ORDER BY workout_session_main_weight ASC";
				$result_sessions = mysqli_query($link, $query_sessions);
				while($row_sessions = mysqli_fetch_row($result_sessions)) {
					list($get_workout_session_main_id, $get_workout_session_main_user_id, $get_workout_session_main_session_id, $get_workout_session_main_weight, $get_workout_session_main_exercise_id, $get_workout_session_main_exercise_title, $get_workout_session_main_sets, $get_workout_session_main_reps, $get_workout_session_main_velocity_a, $get_workout_session_main_velocity_b, $get_workout_session_main_distance, $get_workout_session_main_duration, $get_workout_session_main_intensity, $get_workout_session_main_text) = $row_sessions;

					// Workout images
					$exercise_ids_array[] = "$get_workout_session_main_exercise_title|$get_workout_session_main_exercise_id";


					// Style
					if(isset($style) && $style == ""){
						$style = "odd";
					}
					else{
						$style = "";
					}


					echo"
					 <tr>
					  <td style=\"width:1%;vertical-align: top;\">
						<span><a href=\"$root/exercises/view_exercise.php?exercise_id=$get_workout_session_main_exercise_id&amp;l=$l\" class=\"h2\">$human_counter</a></span>
					  </td>
					  <td style=\"padding-right: 4px;\">
						<span><a href=\"$root/exercises/view_exercise.php?exercise_id=$get_workout_session_main_exercise_id&amp;l=$l\" class=\"h2\">$get_workout_session_main_exercise_title</a>";
					if($get_workout_session_main_text != ""){
						echo"<br /> ($get_workout_session_main_text)";
					}	
					echo"
						</span>
					  </td>
					  <td style=\"vertical-align: top;text-align: right;\">
						<span>
					";
					$middot = false;
					if($get_workout_session_main_sets != 0 && $get_workout_session_main_reps != 0){
						echo"$get_workout_session_main_sets x $get_workout_session_main_reps\n";
						$middot = true;
					}
					if($get_workout_session_main_velocity_a != 0 && $get_workout_session_main_velocity_b != 0){
						if($middot == "true"){
							echo" &middot; ";
						}
						$middot = true;
						echo"$get_workout_session_main_velocity_a - $get_workout_session_main_velocity_b km/h\n";
					}
					else{
						if($get_workout_session_main_velocity_a != 0){
						if($middot == "true"){
							echo" &middot; ";
						}
						$middot = true;
							echo"$get_workout_session_main_velocity_akm/h\n";
						}
						if($get_workout_session_main_velocity_b != 0){
						if($middot == "true"){
							echo" &middot; ";
						}
						$middot = true;
							echo"$get_workout_session_main_velocity_b km/h\n";
						}
					}
					if($get_workout_session_main_distance != 0){
						if($middot == "true"){
							echo" &middot; ";
						}
						$middot = true;
						echo"$get_workout_session_main_distance m\n";
					}
					if($get_workout_session_main_duration != 0){
						if($middot == "true"){
							echo" &middot; ";
						}
						$middot = true;
						echo"$get_workout_session_main_duration $l_min_lowercase\n";
					}
					if($get_workout_session_main_intensity != 0){
						if($middot == "true"){
							echo" &middot; ";
						}
						$middot = true;
						echo"$get_workout_session_main_intensity %\n";
					}
					echo"
						</span>
					   </td>
					 </tr>";
					$human_counter++;
			} // get session main
			echo"
				</table>
			<!-- //List sessions_main -->

			";

			if($session_count == "2"){
				echo"
				</div>
				<div style=\"float: right;width: 48%;margin-left:2%;\">
				";	
			}

			$session_count = $session_count+1;

		} // get sessions
		echo"
				</div>
		<div class=\"clear\"></div>
	<!-- //Sessions -->


	<!-- All exercises images -->
		<h2 class=\"pagebreak\">$l_exercises</h2>
		<table>
		";
		
		sort($exercise_ids_array);
		$size = sizeof($exercise_ids_array);
		$prevoius_exercise_id = "0"; // removes duplicates
		$layout = 0;
		for($x=0;$x<$size;$x++){
			$temp = explode("|", $exercise_ids_array[$x]);
			$exercise_title = $temp[0];
			$exercise_id    = $temp[1];
			if($prevoius_exercise_id != "$exercise_id"){

				if($layout == "0"){
					echo"
					 <tr>
					  <td style=\"width: 50%;text-align: center;\">
					";
				}
				elseif($layout == "1"){
					echo"
					  <td style=\"width: 50%;text-align: center;\">
					";
				}

				echo"
				<p><b>$exercise_title</b><br />
				";

				// Image
				$query_images = "SELECT exercise_image_id, exercise_image_user_id, exercise_image_exercise_id, exercise_image_type, exercise_image_path, exercise_image_file, exercise_image_thumb_120x120, exercise_image_thumb_150x150, exercise_image_thumb_350x350 FROM $t_exercise_index_images WHERE exercise_image_exercise_id='$exercise_id' ORDER BY exercise_image_type ASC LIMIT 0,2";
				$result_images = mysqli_query($link, $query_images);
				while($row_images = mysqli_fetch_row($result_images)) {
					list($get_exercise_image_id, $get_exercise_image_user_id, $get_exercise_image_exercise_id, $get_exercise_image_type, $get_exercise_image_path, $get_exercise_image_file, $get_exercise_image_thumb_120x120, $get_exercise_image_thumb_150x150, $get_exercise_image_thumb_350x350) = $row_images;
					if($get_exercise_image_file != ""){
						echo"			<a href=\"$root/exercises/view_exercise.php?exercise_id=$exercise_id&amp;l=$l\"><img src=\"$root/$get_exercise_image_path/$get_exercise_image_thumb_150x150\" alt=\"$get_exercise_image_thumb_150x150\" /></a>\n";
					}
				}
				echo"
					
				";
				// Muscle image
				$query_muscle = "SELECT exercise_muscle_image_id, exercise_muscle_image_file FROM $t_exercise_index_muscles_images WHERE exercise_muscle_image_exercise_id='$exercise_id'";
				$result_muscle = mysqli_query($link, $query_muscle);
				$row_muscle = mysqli_fetch_row($result_muscle);
				list($get_exercise_muscle_image_id, $get_exercise_muscle_image_file) = $row_muscle;
				if($get_exercise_muscle_image_file != "" && file_exists("$root/_uploads/exercises/muscle_image/$get_exercise_muscle_image_file")){
					echo"
					<img src=\"$root/_uploads/exercises/muscle_image/$get_exercise_muscle_image_file\" alt=\"$get_exercise_muscle_image_file\" width=\"150\" height=\"150\" />
					";
				}
				echo"
				</p>
				";


				if($layout == "0"){
					echo"
					  </td>
					";
				}
				elseif($layout == "1"){
					echo"
					  </td>
					 </tr>
					";
				}

				$layout++;
				if($layout == "2"){
					$layout = 0;
				}
			}

			// For next iteration
			$prevoius_exercise_id = "$exercise_id";

			
		}
		echo"	
		</table>
	<!-- //All exercises images -->

	<!-- Copyright -->
		";
		echo"
		<p>
		&copy;  $server_name	$year 
		</p>
		
	<!-- //Copyright -->
		
	</body>
	</html>";
}

?>