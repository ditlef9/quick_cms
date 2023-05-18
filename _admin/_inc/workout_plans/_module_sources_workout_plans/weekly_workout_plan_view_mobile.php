<?php 
/**
*
* File: workout_plans/weekly_workout_plan_view_mobile.php
* Version 1.0.0
* Date 12:05 10.02.2018
* Copyright (c) 2011-2018 S. A. Ditlefsen
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
include("_tables.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/workout_plans/ts_new_workout_plan.php");
include("$root/_admin/_translations/site/$l/workout_plans/ts_yearly_workout_plan_edit.php");
include("$root/_admin/_translations/site/$l/workout_plans/ts_weekly_workout_plan_view.php");

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

	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$get_current_workout_weekly_title ($l_mobile) - $l_workout_plans";
	include("$root/_webdesign/header.php");


	/* Hits */

	$inp_ip = $_SERVER['REMOTE_ADDR'];
	$inp_ip = output_html($inp_ip);

	$ip_array = explode("\n", $get_current_workout_weekly_unique_hits_ip_block);
	$ip_array_size = sizeof($ip_array);

	$has_seen_this_food_before = 0;

	for($x=0;$x<$ip_array_size;$x++){
		if($ip_array[$x] == "$inp_ip"){
			$has_seen_this_food_before = 1;
			break;
		}
		if($x > 5){
			break;
		}
	}
	
	if($has_seen_this_food_before == 0){
		$inp_workout_weekly_unique_hits_ip_block = $inp_ip . "\n" . $get_current_workout_weekly_unique_hits_ip_block;
		$inp_workout_weekly_unique_hits_ip_block_mysql = quote_smart($link, $inp_workout_weekly_unique_hits_ip_block);
		$inp_workout_weekly_unique_hits = $get_current_workout_weekly_unique_hits + 1;
		$result = mysqli_query($link, "UPDATE $t_workout_plans_weekly SET workout_weekly_unique_hits=$inp_workout_weekly_unique_hits, workout_weekly_unique_hits_ip_block=$inp_workout_weekly_unique_hits_ip_block_mysql WHERE workout_weekly_id=$weekly_id_mysql") or die(mysqli_error($link));
	}



	/*- Content ---------------------------------------------------------------------------------- */

	echo"
	<h1>$get_current_workout_weekly_title</h1>
	


	<!-- Where am I ? -->
		<div style=\"float: left\">
			<p><b>$l_you_are_here:</b><br />
			<a href=\"index.php?l=$l\">$l_workout_plans</a>
			&gt;
			";
			// Have period parent?
			if($get_current_workout_weekly_period_id != 0){
				$query = "SELECT workout_period_id, workout_period_yearly_id, workout_period_title FROM $t_workout_plans_period WHERE workout_period_id=$get_current_workout_weekly_period_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_workout_period_id, $get_current_workout_period_yearly_id, $get_current_workout_period_title) = $row;


				// Have yearly parent?
				if($get_current_workout_period_yearly_id != 0){
					$query = "SELECT workout_yearly_id, workout_yearly_title FROM $t_workout_plans_yearly WHERE workout_yearly_id=$get_current_workout_period_yearly_id";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_workout_yearly_id, $get_current_workout_yearly_title) = $row;
					echo"
					<a href=\"yearly_workout_plan_view.php?yearly_id=$get_current_workout_yearly_id&amp;l=$l\">$get_current_workout_yearly_title</a>
					&gt;
					";
				}


				echo"
				<a href=\"period_workout_plan_view.php?period_id=$get_current_workout_period_id&amp;l=$l\">$get_current_workout_period_title</a>
				&gt;
				";
			}



			echo"
			<a href=\"weekly_workout_plan_view.php?weekly_id=$weekly_id&amp;l=$l\">$get_current_workout_weekly_title</a>
			</p>
		</div>
	<!-- //Where am I ? -->


	<!-- Export etc -->
		<div style=\"float: right\">
			<p>
			<a href=\"weekly_workout_plan_view_print.php?weekly_id=$weekly_id&amp;l=$l\"><img src=\"_gfx/icons/print.png\" alt=\"print.png\" /></a>
			&nbsp;
			";
			if(file_exists("$root/_cache/workout_weekly_id_compact_$get_current_workout_weekly_id.txt") && file_exists("$root/_cache/workout_weekly_id_$get_current_workout_weekly_id.txt")){
				echo"
				<a href=\"$root/_cache/workout_weekly_id_compact_$get_current_workout_weekly_id.txt\"><img src=\"_gfx/icons/txt_compact.png\" alt=\"txt_compact.png\" /></a>
				&nbsp;
				<a href=\"$root/_cache/workout_weekly_id_$get_current_workout_weekly_id.txt\"><img src=\"_gfx/icons/txt.png\" alt=\"txt.png\" /></a>";
			}
			else{
				echo"
				<a href=\"weekly_workout_plan_view_text_compact.php?weekly_id=$weekly_id&amp;l=$l\"><img src=\"_gfx/icons/txt_compact.png\" alt=\"txt_compact.png\" /></a>
				&nbsp;
				<a href=\"weekly_workout_plan_view_text.php?weekly_id=$weekly_id&amp;l=$l\"><img src=\"_gfx/icons/txt.png\" alt=\"txt.png\" /></a>";
			}
			echo"
			&nbsp;
			<a href=\"weekly_workout_plan_view_table.php?weekly_id=$weekly_id&amp;l=$l\"><img src=\"_gfx/icons/table.png\" alt=\"table.png\" /></a>
			</p>
		</div>
	<!-- //Export etc -->


	<!-- Intro and Image -->
		<div class=\"clear\"></div>
		";
		if($get_current_workout_weekly_image_file != "" && file_exists("$root/$get_current_workout_weekly_image_path/$get_current_workout_weekly_image_file")){
			echo"
			<p style=\"margin-bottom: 0px;padding-bottom:0\">
			<img src=\"$root/$get_current_workout_weekly_image_path/$get_current_workout_weekly_image_file\" alt=\"$get_current_workout_weekly_image_file\" />
			</p>
			<p style=\"margin-top: 0px;padding-top:0\">
			<img src=\"_gfx/icons/eye_dark_grey.png\" alt=\"eye.png\" /> $get_current_workout_weekly_unique_hits $l_unique_views_lovercase
			</p>
			<p style=\"margin-top: 0px;padding-top:0\">
			$get_current_workout_weekly_introduction
			</p>
			
			";
		}
		echo"
	<!-- //Intro and Image -->

	<!-- Goals -->
		<div class=\"clear\"></div>
		";
		if($get_current_workout_weekly_goal != ""){
			echo"
			<h2>$l_goal</h2>
			$get_current_workout_weekly_goal
			";
		}
		echo"
	<!-- //Goals -->


	<!-- Sessions -->

		";
		$query = "SELECT workout_session_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity FROM $t_workout_plans_sessions WHERE workout_session_weekly_id=$get_current_workout_weekly_id ORDER BY workout_session_weight ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_workout_session_id, $get_workout_session_weight, $get_workout_session_title, $get_workout_session_title_clean, $get_workout_session_duration, $get_workout_session_intensity) = $row;


			echo"
			<div style=\"margin-top: 8px;\"></div>
			<table class=\"hor-zebra\">
			 <tbody>
			  <tr>
			   <td>
				<h2 style=\"padding:0;margin:0;\">$get_workout_session_title</h2>
			   </td>
			  </tr>
			 </tbody>
			</table>


			<!-- List sessions_main -->
			
				<table style=\"width: 100%;\">
				 <tbody>";

				$human_counter = 1;
				$query_sessions = "SELECT workout_session_main_id, workout_session_main_user_id, workout_session_main_session_id, workout_session_main_weight, workout_session_main_exercise_id, workout_session_main_exercise_title, workout_session_main_sets, workout_session_main_reps, workout_session_main_velocity_a, workout_session_main_velocity_b, workout_session_main_distance, workout_session_main_duration, workout_session_main_intensity, workout_session_main_text FROM $t_workout_plans_sessions_main WHERE workout_session_main_session_id=$get_workout_session_id ORDER BY workout_session_main_weight ASC";
				$result_sessions = mysqli_query($link, $query_sessions);
				while($row_sessions = mysqli_fetch_row($result_sessions)) {
					list($get_workout_session_main_id, $get_workout_session_main_user_id, $get_workout_session_main_session_id, $get_workout_session_main_weight, $get_workout_session_main_exercise_id, $get_workout_session_main_exercise_title, $get_workout_session_main_sets, $get_workout_session_main_reps, $get_workout_session_main_velocity_a, $get_workout_session_main_velocity_b, $get_workout_session_main_distance, $get_workout_session_main_duration, $get_workout_session_main_intensity, $get_workout_session_main_text) = $row_sessions;

					// Style
					if(isset($style) && $style == ""){
						$style = "odd";
					}
					else{
						$style = "";
					}


					echo"
					  <tr>
					   <td style=\"border-bottom: #ccc 1px solid;padding: 0px 4px 0px 5px;width: 2%;vertical-align: top;\">
						<p>
						<a href=\"$root/exercises/view_exercise.php?exercise_id=$get_workout_session_main_exercise_id&amp;l=$l\" class=\"h2\">$human_counter</a>
						</p>
					   </td>
					   <td style=\"border-bottom: #ccc 1px solid;vertical-align: top;\">
						<p style=\"padding-bottom:0;margin-bottom:0;\">
						<a href=\"$root/exercises/view_exercise.php?exercise_id=$get_workout_session_main_exercise_id&amp;l=$l\" class=\"h2\">$get_workout_session_main_exercise_title</a>
						</p>
						";
						if($get_workout_session_main_text != ""){
							echo"
							<p style=\"padding:0;margin:0;\">
							$get_workout_session_main_text
							</p>";
						}
						echo"
						<p style=\"padding:0px 0px 0px 0px;margin:0;\">
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
						</p>
						
						";
						// Images
						$query_images = "SELECT exercise_image_id, exercise_image_type, exercise_image_path, exercise_image_file FROM $t_exercise_index_images WHERE exercise_image_exercise_id='$get_workout_session_main_exercise_id' ORDER BY exercise_image_type DESC LIMIT 0,2";
						$result_images = mysqli_query($link, $query_images);
						while($row_images = mysqli_fetch_row($result_images)) {
							list($get_exercise_image_id, $get_exercise_image_type, $get_exercise_image_path, $get_exercise_image_file) = $row_images;
							if($get_exercise_image_file != ""){
								echo"			<a href=\"$root/exercises/view_exercise.php?exercise_id=$get_workout_session_main_exercise_id&amp;l=$l\"><img src=\"$root/image.php?width=119&amp;height=80&amp;image=/$get_exercise_image_path/$get_exercise_image_file\" alt=\"$get_exercise_image_type\" /></a>\n";
							}
						}
						echo"
						";
						// Muscle image
						$query_muscle = "SELECT exercise_muscle_image_id, exercise_muscle_image_file FROM $t_exercise_index_muscles_images WHERE exercise_muscle_image_exercise_id='$get_workout_session_main_exercise_id'";
						$result_muscle = mysqli_query($link, $query_muscle);
						$row_muscle = mysqli_fetch_row($result_muscle);
						list($get_exercise_muscle_image_id, $get_exercise_muscle_image_file) = $row_muscle;
						echo"
						<img src=\"$root/image.php?width=80&amp;height=80&amp;image=/_uploads/exercises/muscle_image/$get_exercise_muscle_image_file\" alt=\"$get_exercise_muscle_image_file\" />

					   </td>
				 	  </tr>
					";
					$human_counter++;
				} // get session main
				echo"
				 </tbody>
				</table>
			
			<!-- //List sessions_main -->
			";
		} // get sessions
		echo"

	<!-- //Sessions -->


	";
}


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>