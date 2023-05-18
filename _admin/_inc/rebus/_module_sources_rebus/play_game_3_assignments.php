<?php
/**
*
* File: rebus/play_game_3_assignments.php
* Version 1.0.0.
* Date 09:50 01.07.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
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
include("_tables_rebus.php");


/*- Functions ------------------------------------------------------------------------- */
function getGps($exifCoord, $hemi) {

    $degrees = count($exifCoord) > 0 ? gps2Num($exifCoord[0]) : 0;
    $minutes = count($exifCoord) > 1 ? gps2Num($exifCoord[1]) : 0;
    $seconds = count($exifCoord) > 2 ? gps2Num($exifCoord[2]) : 0;

    $flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;

    return $flip * ($degrees + $minutes / 60 + $seconds / 3600);

}
function gps2Num($coordPart) {

    $parts = explode('/', $coordPart);

    if (count($parts) <= 0)
        return 0;

    if (count($parts) == 1)
        return $parts[0];

	$parts0 = floatval($parts[0]);
	$parts1 = floatval($parts[1]);
    
	if($parts1 == 0){
		return 0;
	}
	else{
		return $parts0 / $parts1;
	}
}
function seconds_to_time_array($seconds) {
	$dtF = new \DateTime('@0');
	$dtT = new \DateTime("@$seconds");
	return $dtF->diff($dtT)->format('%a|%h|%i|%s');
}

function getDistance($latitude1, $longitude1, $latitude2, $longitude2) {  
  $earth_radius = 6371;

  $dLat = deg2rad($latitude2 - $latitude1);  
  $dLon = deg2rad($longitude2 - $longitude1);  

  $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);  
  $c = 2 * asin(sqrt($a));  
  $d = $earth_radius * $c;  

  return $d;  
}



/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);

if(isset($_GET['game_id'])) {
	$game_id = $_GET['game_id'];
	$game_id = output_html($game_id);
	if(!(is_numeric($game_id))){
		echo"Game id not numeric";
		die;
	}
}
else{
	echo"Missing game id";
	die;
}
if(isset($_GET['session_id'])) {
	$session_id = $_GET['session_id'];
	$session_id = output_html($session_id);
	if(!(is_numeric($session_id))){
		echo"session_id not numeric";
		die;
	}
}
else{
	echo"Missing session id";
	die;
}
if(isset($_GET['fm'])) {
	$fm = $_GET['fm'];
	$fm = output_html($fm);
}
else{
	$fm = "";
}

$tabindex = 0;

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);

	$query = "SELECT user_id, user_name, user_language, user_rank, user_measurement FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_name, $get_user_language, $get_user_rank, $get_user_measurement) = $row;


	// Dates
	$datetime = date("Y-m-d H:i:s");
	$time = time();

	/*- Find game ------------------------------------------------------------------------- */
	$game_id_mysql = quote_smart($link, $game_id);
	$query = "SELECT game_id, game_title, game_language, game_introduction, game_description, game_privacy, game_published, game_playable_after_datetime, game_playable_after_datetime_saying, game_playable_after_time, game_group_id, game_group_name, game_times_played, game_times_finished, game_finished_percentage, game_time_used_seconds, game_time_used_saying, game_image_path, game_image_file, game_image_thumb_570x321, game_image_thumb_278x156, game_country_id, game_country_name, game_county_id, game_county_name, game_municipality_id, game_municipality_name, game_city_id, game_city_name, game_place_id, game_place_name, game_number_of_assignments, game_created_by_user_id, game_created_by_user_name, game_created_by_user_email, game_created_by_ip, game_created_by_hostname, game_created_by_user_agent, game_created_datetime, game_created_date_saying, game_updated_by_user_id, game_updated_by_user_name, game_updated_by_user_email, game_updated_by_ip, game_updated_by_hostname, game_updated_by_user_agent, game_updated_datetime, game_updated_date_saying FROM $t_rebus_games_index WHERE game_id=$game_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_game_id, $get_current_game_title, $get_current_game_language, $get_current_game_introduction, $get_current_game_description, $get_current_game_privacy, $get_current_game_published, $get_current_game_playable_after_datetime, $get_current_game_playable_after_datetime_saying, $get_current_game_playable_after_time, $get_current_game_group_id, $get_current_game_group_name, $get_current_game_times_played, $get_current_game_times_finished, $get_current_game_finished_percentage, $get_current_game_time_used_seconds, $get_current_game_time_used_saying, $get_current_game_image_path, $get_current_game_image_file, $get_current_game_image_thumb_570x321, $get_current_game_image_thumb_278x156, $get_current_game_country_id, $get_current_game_country_name, $get_current_game_county_id, $get_current_game_county_name, $get_current_game_municipality_id, $get_current_game_municipality_name, $get_current_game_city_id, $get_current_game_city_name, $get_current_game_place_id, $get_current_game_place_name, $get_current_game_number_of_assignments, $get_current_game_created_by_user_id, $get_current_game_created_by_user_name, $get_current_game_created_by_user_email, $get_current_game_created_by_ip, $get_current_game_created_by_hostname, $get_current_game_created_by_user_agent, $get_current_game_created_datetime, $get_current_game_created_date_saying, $get_current_game_updated_by_user_id, $get_current_game_updated_by_user_name, $get_current_game_updated_by_user_email, $get_current_game_updated_by_ip, $get_current_game_updated_by_hostname, $get_current_game_updated_by_user_agent, $get_current_game_updated_datetime, $get_current_game_updated_date_saying) = $row;
	if($get_current_game_id == ""){
		$url = "index.php?ft=error&fm=game_not_found&l=$l&id=1";
		header("Location: $url");
		exit;
	}


	// Is public?
	if($get_current_game_privacy == "private"){
		echo"Private!!";
	}


	/*- Find my game session ---------------------------------------------------------------- */
	$session_id_mysql = quote_smart($link, $session_id);
	$query = "SELECT session_id, session_game_id, session_play_as_user_group_team, session_user_id, session_group_id, session_team_id, session_start_datetime, session_start_year, session_start_month, session_start_day, session_start_hour, session_start_minute, session_start_time, session_is_on_assignment_number, session_points, session_ended_game, session_is_finished, session_finished_datetime, session_finished_time, session_seconds_used, session_time_used_saying FROM $t_rebus_games_sessions_index";
	$query = $query . " WHERE session_id=$session_id_mysql AND session_game_id=$get_current_game_id";
	$query = $query . " AND session_is_finished=0";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_session_id, $get_current_session_game_id, $get_current_session_play_as_user_group_team, $get_current_session_user_id, $get_current_session_group_id, $get_current_session_team_id, $get_current_session_start_datetime, $get_current_session_start_year, $get_current_session_start_month, $get_current_session_start_day, $get_current_session_start_hour, $get_current_session_start_minute, $get_current_session_start_time, $get_current_session_is_on_assignment_number, $get_current_session_points, $get_current_session_ended_game, $get_current_session_is_finished, $get_current_session_finished_datetime, $get_current_session_finished_time, $get_current_session_seconds_used, $get_current_session_time_used_saying) = $row;

	if($get_current_session_id == ""){
		echo"Sorry, could not find your game session.
		<p>
		<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
		Loading...</p>
		<meta http-equiv=\"refresh\" content=\"2;url=play_game.php?game_id=$get_current_game_id&l=$l\">";
		die;
	}

	// Make sure I can play this session (that I am owner or team member)
	if($get_current_session_play_as_user_group_team == "user"){
		if($get_current_session_user_id != "$my_user_id"){
			echo"Sorry, not your game session.";
			die;
		}
	}
	elseif($get_current_session_play_as_user_group_team == "team"){
		// Make sure I am a team member
		$query = "SELECT member_id FROM $t_rebus_teams_members WHERE member_team_id=$get_current_session_team_id AND member_user_id=$my_user_id_mysql ";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_member_id) = $row;
		if($get_member_id == ""){
			echo"Sorry, your not a member of the team that has this session.";
			die;
		}

	}
	else{
		echo"Not implimented";
		die;
	}

	/* Find assignment */
	$query = "SELECT assignment_id, assignment_game_id, assignment_number, assignment_type, assignment_value, assignment_address, assignment_answer_a, assignment_answer_a_clean, assignment_answer_b, assignment_answer_b_clean, assignment_radius_metric, assignment_radius_imperial, assignment_radius_user_measurment, assignment_hint_a_value, assignment_hint_a_price, assignment_hint_b_value, assignment_hint_b_price, assignment_hint_c_value, assignment_hint_c_price, assignment_points, assignment_text_when_correct_answer, assignment_time_to_solve_seconds, assignment_time_to_solve_saying, assignment_created_by_user_id, assignment_created_by_ip, assignment_created_datetime, assignment_updated_by_user_id, assignment_updated_by_ip, assignment_updated_datetime FROM $t_rebus_games_assignments WHERE assignment_game_id=$get_current_game_id AND assignment_number=$get_current_session_is_on_assignment_number";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_assignment_id, $get_current_assignment_game_id, $get_current_assignment_number, $get_current_assignment_type, $get_current_assignment_value, $get_current_assignment_address, $get_current_assignment_answer_a, $get_current_assignment_answer_a_clean, $get_current_assignment_answer_b, $get_current_assignment_answer_b_clean, $get_current_assignment_radius_metric, $get_current_assignment_radius_imperial, $get_current_assignment_radius_user_measurment, $get_current_assignment_hint_a_value, $get_current_assignment_hint_a_price, $get_current_assignment_hint_b_value, $get_current_assignment_hint_b_price, $get_current_assignment_hint_c_value, $get_current_assignment_hint_c_price, $get_current_assignment_points, $get_current_assignment_text_when_correct_answer, $get_current_assignment_time_to_solve_seconds, $get_current_assignment_time_to_solve_saying, $get_current_assignment_created_by_user_id, $get_current_assignment_created_by_ip, $get_current_assignment_created_datetime, $get_current_assignment_updated_by_user_id, $get_current_assignment_updated_by_ip, $get_current_assignment_updated_datetime) = $row;

	if($get_current_assignment_id == ""){
		// Finished

		// Calculate time used, set as finished
		$seconds_used = $time-$get_current_session_start_time;
		$time_used = seconds_to_time_array($seconds_used);
		$time_used_array = explode("|", $time_used);
		$time_used_days = $time_used_array[0];
		$time_used_hours = $time_used_array[1];
		$time_used_minutes = $time_used_array[2];
		$time_used_seconds = $time_used_array[3];

		$inp_time_used_saying = "";
		if($time_used_days != "0"){
			$inp_time_used_saying = $inp_time_used_saying . "$time_used_days $l_days_lowercase";
		}
		if($time_used_hours != "0"){
			if($inp_time_used_saying  == ""){
				$inp_time_used_saying = "$time_used_hours $l_hours_lowercase";
			}
			else{
				$inp_time_used_saying = $inp_time_used_saying . ", $time_used_hours $l_hours_lowercase";
			}
		}
		if($time_used_minutes != "0"){
			if($inp_time_used_saying  == ""){
				$inp_time_used_saying = "$time_used_minutes $l_minutes_lowercase";
			}
			else{
				$inp_time_used_saying = $inp_time_used_saying . ", $time_used_minutes $l_minutes_lowercase";
			}
		}
		if($time_used_seconds != "0"){
			if($inp_time_used_saying  == ""){
				$inp_time_used_saying = "$time_used_seconds $l_seconds_lowercase";
			}
			else{
				$inp_time_used_saying = $inp_time_used_saying . " $l_and_lowercase $time_used_seconds $l_seconds_lowercase";
			}
		}
		$inp_time_used_saying = output_html($inp_time_used_saying);
		$inp_time_used_saying_mysql = quote_smart($link, $inp_time_used_saying);
		
		mysqli_query($link, "UPDATE $t_rebus_games_sessions_index  SET 
					session_ended_game=1, 
					session_is_finished=1, 
					session_finished_datetime='$datetime', 
					session_finished_time='$time', 
					session_seconds_used='$seconds_used', 
					session_time_used_saying=$inp_time_used_saying_mysql 
					WHERE session_id=$get_current_session_id") or die(mysqli_error($link));


		// Update game
		$inp_times_finished = $get_current_game_times_finished+1;
		mysqli_query($link, "UPDATE $t_rebus_games_index SET game_times_finished=$inp_times_finished WHERE game_id=$get_current_game_id") or die(mysqli_error($link));

		// Header
		$url = "play_game_6_calcualte_game_high_score.php?game_id=$get_current_game_id&session_id=$get_current_session_id&l=$l&id=4";
		header("Location: $url");
		exit;
	}


	/* Check my answer */
	$query = "SELECT answer_id, answer_session_id, answer_assignment_id, answer_assignment_number, answer_by_user_group_team, answer_by_user_id, answer_by_group_id, answer_by_team_id, answer_by_ip, answer_datetime, answer_path, answer_file, answer_text, answer_i_have_flagged_it, answer_is_checked, answer_is_correct, answer_used_hint_a, answer_used_hint_b, answer_used_hint_c, answer_score FROM $t_rebus_games_sessions_answers WHERE answer_session_id=$get_current_session_id AND answer_assignment_id=$get_current_assignment_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_answer_id, $get_current_answer_session_id, $get_current_answer_assignment_id, $get_current_answer_assignment_number, $get_current_answer_by_user_group_team, $get_current_answer_by_user_id, $get_current_answer_by_group_id, $get_current_answer_by_team_id, $get_current_answer_by_ip, $get_current_answer_datetime, $get_current_answer_path, $get_current_answer_file, $get_current_answer_text, $get_current_answer_i_have_flagged_it, $get_current_answer_is_checked, $get_current_answer_is_correct, $get_current_answer_used_hint_a, $get_current_answer_used_hint_b, $get_current_answer_used_hint_c, $get_current_answer_score) = $row;





	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$get_current_game_title";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	$main_class = "no_bg";
	include("$root/_webdesign/header.php");
	
	if($action == ""){
		echo"


		<!-- Assignment number x of y -->
			<div class=\"assignment_x_of_y\">
				<ul>";
				$stop = $get_current_game_number_of_assignments+1;
				for($x=1;$x<$stop;$x++){
					echo"					<li"; if($x == "$get_current_assignment_number"){ echo" class=\"active\""; } echo"><span>$x</span></li>\n";
				}
				echo"
				</ul>
			</div>
		<!-- //Assignment number x of y -->

		<!-- Points, time used -->
			<div class=\"points_and_time_used\">
				<!-- Points -->
					<div class=\"points\"><span class=\"points_span\">$get_current_session_points";
					if($get_current_session_points == "0" OR $get_current_session_points == "1"){ 
						echo" $l_point_lowercase";
					}
					else{
						echo" $l_points_lowercase";
					}
					echo"</span></div>
				<!-- //Points -->


				<!-- Time used -->
					<div class=\"time_used\"><span id=\"time_used_span\"></span></div>
					<script type=\"text/javascript\">
					\$(document).ready(function () {
						function timeDiffCalc() {
							dateFuture = new Date('$get_current_session_start_year/$get_current_session_start_month/$get_current_session_start_day $get_current_session_start_hour:$get_current_session_start_minute:00');
							

							localDate = new Date();
							var month = localDate.getUTCMonth() + 1; // months from 1-12
							var day = localDate.getUTCDate();
							var year = localDate.getUTCFullYear();
							var hour = localDate.getUTCHours();
							var minute = localDate.getUTCMinutes();
							dateNow  = new Date(year + '/' + month + '/' + day + ' ' + hour + ':' + minute + ':00');
							

							let diffInMilliSeconds = Math.abs(dateFuture - dateNow) / 1000;

							// calculate days
							const days = Math.floor(diffInMilliSeconds / 86400);
							diffInMilliSeconds -= days * 86400;

							// calculate hours
							const hours = Math.floor(diffInMilliSeconds / 3600) % 24;
							diffInMilliSeconds -= hours * 3600;

							// calculate minutes
							const minutes = Math.floor(diffInMilliSeconds / 60) % 60;
							
							// print
							let difference = '';
							if (days > 0) {
								difference += (days === 1) ? `\${days} day, ` : `\${days} days, `;
							}
							if (hours > 0) {
								difference += (hours === 0 || hours === 1) ? `\${hours} hour, ` : `\${hours} hours, `;
							}
							
							difference += (minutes === 0 || hours === 1) ? `\${minutes} minutes` : `\${minutes} minutes`; 

								
							\$(\"#time_used_span\").html(difference);
						}

						setInterval(timeDiffCalc, 1000);
       					});
					</script>
				<!-- //Time used -->
			</div>
		<!-- //Points, time used -->

		<div class=\"assignment_in_game\">
			<!-- Assignment -->
				$get_current_assignment_value
			<!-- //Assignment -->


			<!-- Feedback -->
				<a id=\"feedback\"></a>
				";
			if($ft != "" && $fm != ""){
				$fm = ucfirst($fm);
				$fm = str_replace("_", " ", $fm);

				// Wrong image feedback
				if($fm == "sorry_the_coordinates_are_not_correct" OR $fm == "Sorry the coordinates are not correct"){
					if(isset($_GET['answer_latitude']) && isset($_GET['answer_longitude'])) {
						$answer_latitude = $_GET['answer_latitude'];
						$answer_latitude = output_html($answer_latitude);
						if(!(is_numeric($answer_latitude))){
							echo"answer_latitude not numeric";
							die;
						}

						$answer_longitude = $_GET['answer_longitude'];
						$answer_longitude = output_html($answer_longitude);
						if(!(is_numeric($answer_longitude))){
							echo"answer_longitude not numeric";
							die;
						}

						$answer_distance_m = $_GET['answer_distance_m'];
						$answer_distance_m = output_html($answer_distance_m);
						if(!(is_numeric($answer_distance_m))){
							echo"answer_distance_m not numeric";
							die;
						}

						// Distance
						if($get_user_measurement == "metric"){
							$distance_saying = "$answer_distance_m $l_meters_lowercase";
						}
						else{
							$distance_f = $distance_m/3.2808399;
							$distance_f = round($distance_f, 0);
							$distance_saying = "$answer_distance_f feet";
						}
						echo"
						<div class=\"$ft\" style=\"display: block;\"><p>$l_sorry_the_coordinates_are_not_correct. $l_you_are <b>$distance_saying</b> $l_away_from_the_target_lowercase.</p></div>
						";
					}
				} // fm
				else{
		
					echo"
					<div class=\"$ft\" style=\"display: block;\"><p>$fm</p></div>
					";
				}
			}
			echo"
			<!-- //Feedback -->


			<!-- Help -->
				";
				if($get_current_answer_used_hint_a == "1"){
					echo"
					<p style=\"padding-bottom:0;margin-bottom:0;\"><b>$l_hint 1:</b></p>
					$get_current_assignment_hint_a_value
					";
				}
				if($get_current_answer_used_hint_b == "1"){
					echo"
					<p style=\"padding-bottom:0;margin-bottom:0;\"><b>$l_hint 2:</b></p>
					$get_current_assignment_hint_b_value
					";
				}
				if($get_current_answer_used_hint_c == "1"){
					echo"
					<p style=\"padding-bottom:0;margin-bottom:0;\"><b>$l_hint 3:</b></p>
					$get_current_assignment_hint_c_value
					";
				}

				if($get_current_assignment_hint_a_value != "" && $get_current_answer_used_hint_a == ""){
					echo"
					<a href=\"play_game_4_use_hint.php?game_id=$get_current_game_id&amp;session_id=$get_current_session_id&amp;l=$l\" class=\"btn_hint\">$l_use_hint_no 1 $l_for_lowercase $get_current_assignment_hint_a_price $l_points_lowercase</a>
					";
				}
				else{

					if($get_current_assignment_hint_b_value != "" && $get_current_answer_used_hint_b == ""){
						echo"
						<a href=\"play_game_4_use_hint.php?game_id=$get_current_game_id&amp;session_id=$get_current_session_id&amp;l=$l\" class=\"btn_hint\">$l_use_hint_no 2 $l_for_lowercase $get_current_assignment_hint_b_price $l_points_lowercase</a>
						";
					}
					else{
						if($get_current_assignment_hint_c_value != "" && $get_current_answer_used_hint_c == ""){
							echo"
							<a href=\"play_game_4_use_hint.php?game_id=$get_current_game_id&amp;session_id=$get_current_session_id&amp;l=$l\" class=\"btn_hint\">$l_use_hint_no 3 $l_for_lowercase $get_current_assignment_hint_c_price $l_points_lowercase</a>
							";
						}
					}
				}	
				echo"
				</p>
			<!-- //Help -->



			<!-- Answer assignment form -->
				";
				if($get_current_assignment_type == "answer_a_question"){
					
					echo"

					<!-- Focus -->
						<script>
						\$(document).ready(function(){
							\$('[name=\"inp_answer\"]').focus();
						});
						</script>
					<!-- //Focus -->
					<form method=\"post\" action=\"play_game_3_assignments.php?action=answer_a_question&amp;game_id=$get_current_game_id&amp;session_id=$get_current_session_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
				
					<p><b>$l_your_answer:</b><br />
					<input type=\"text\" name=\"inp_answer\" value=\"\" size=\"25\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
					</p>
					<p><input type=\"submit\" value=\"$l_submit_answer\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></p>
					</form>
					";

				}
				elseif($get_current_assignment_type == "take_a_picture_with_coordinates"){

					echo"
					<form method=\"post\" action=\"play_game_3_assignments.php?action=answer_take_a_picture_with_coordinates&amp;game_id=$get_current_game_id&amp;session_id=$get_current_session_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
				


					<p><b>$l_take_a_picture_of_your_answer:</b></p>

					<label for=\"inp_answer\" class=\"custom-file-upload\">
						$l_open_camera
					</label>

					<p>
					<input type=\"file\" id=\"inp_answer\" name=\"inp_answer\" accept=\"image/*;capture=camera\"/>					
					</p>

					<!-- Capture image script + Get location script -->
						<script>
						\$(document).ready(function () {
							\$(\"#inp_answer\").click(function () {
								\$(\"#inp_answer\").fadeToggle();
							});
							\$(\"#inp_answer\").change(function() {
								// Loading
								\$(\"#loading_image\").fadeToggle();
								\$(\"#inp_submit\").fadeToggle();
								
								// Submit form
								this.form.submit();
							});
       						});
						</script>
					<!-- //Capture image script + Get location script -->
					<p>
					<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" id=\"loading_image\" />
					<input type=\"submit\" value=\"$l_submit_answer\" id=\"inp_submit\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
					</p>
					
					<p class=\"inp_latitude_longitude\">
					<input type=\"text\" name=\"inp_latitude\" id=\"inp_latitude\" value=\"0\" size=\"6\" />,
					<input type=\"text\" name=\"inp_longitude\" id=\"inp_longitude\" value=\"0\" size=\"6\" />
					</p>
					
					</form>
					";
				}  // take_a_picture_with_coordinates
				echo"
			<!-- //Answer assignment form -->


			<!-- Map -->";
				if($get_current_assignment_type == "take_a_picture_with_coordinates"){
					echo"
					<!-- Get location script -->
					<script type=\"text/javascript\">
					\$(document).ready(function () {
						function getGeoLocation() {
							// Get the users location
							if (navigator.geolocation) {
								navigator.geolocation.getCurrentPosition(geoSucc, geoErr, geoOpt);
							} else {
								\$(\"#geo_feedback_text\").html(\"$l_geolocation_is_not_supported_by_this_browser. $l_the_game_needs_your_location_in_order_to_know_where_you_are_when_you_are_taking_pictures. $l_the_game_will_try_to_get_your_location_again_later.\");
								\$(\"#geo_feedback_type\").show();
								\$(\"#geo_feedback_type\").removeClass();
								\$(\"#geo_feedback_type\").addClass(\"error\");
							}
						}

						function geoSucc(position) {
							var latitude = position.coords.latitude;
							var longitude = position.coords.longitude;
							\$(\"#inp_latitude\").val(latitude);
							\$(\"#inp_longitude\").val(longitude);
							\$(\"#geo_feedback_type\").hide();
								
							var data = 'game_id=$get_current_game_id&session_id=$get_current_session_id&latitude=' + latitude + '&longitude=' + longitude;
            						\$.ajax({
                						type: \"GET\",
               							url: \"play_game_3_assignments_show_map.php\",
                						data: data,
								beforeSend: function(html) { // this happens before actual call
                    							\$(\"#leaflet_map\").html(\"\");
								},
               							success: function(html){
                    							\$(\"#leaflet_map\").html(html);
								}
							});
						}
						function geoErr(error) {
								switch(error.code) {
									case error.PERMISSION_DENIED:
										\$(\"#geo_feedback_text\").html(\"$l_user_denied_request_for_geolocation. $l_the_game_needs_your_location_in_order_to_know_where_you_are_when_you_are_taking_pictures. $l_the_game_will_try_to_get_your_location_again_later.\");
										\$(\"#geo_feedback_type\").show();
										\$(\"#geo_feedback_type\").removeClass();
										\$(\"#geo_feedback_type\").addClass(\"error\");
									break;
								case error.POSITION_UNAVAILABLE:
										\$(\"#geo_feedback_text\").html(\"$l_location_information_is_unavailable. $l_the_game_needs_your_location_in_order_to_know_where_you_are_when_you_are_taking_pictures. $l_the_game_will_try_to_get_your_location_again_later.\");
										\$(\"#geo_feedback_type\").show();
										\$(\"#geo_feedback_type\").removeClass();
										\$(\"#geo_feedback_type\").addClass(\"error\");
									break;
								case error.TIMEOUT:
										\$(\"#geo_feedback_text\").html(\"$l_the_request_to_get_user_location_timed_out. $l_the_game_needs_your_location_in_order_to_know_where_you_are_when_you_are_taking_pictures. $l_the_game_will_try_to_get_your_location_again_later.\");
										\$(\"#geo_feedback_type\").show();
										\$(\"#geo_feedback_type\").removeClass();
										\$(\"#geo_feedback_type\").addClass(\"error\");
									break;
								case error.UNKNOWN_ERROR:
										\$(\"#geo_feedback_text\").html(\"$l_an_unknown_error_occurred. $l_the_game_needs_your_location_in_order_to_know_where_you_are_when_you_are_taking_pictures. $l_the_game_will_try_to_get_your_location_again_later.\");
										\$(\"#geo_feedback_type\").show();
										\$(\"#geo_feedback_type\").removeClass();
										\$(\"#geo_feedback_type\").addClass(\"error\");
									break;
								}
						}
						var geoOpt = {
							timeout: 3000,
							maximumAge: 30
						}
						setInterval(getGeoLocation, 30000);
						getGeoLocation();
       					});
					</script>
					<!-- //Get location script -->

					<div id=\"geo_feedback_type\"><p id=\"geo_feedback_text\"></p></div>
					<div id=\"leaflet_map\"></div>
					";
				} // if($get_current_assignment_type == "take_a_picture_with_coordinates"){
				echo"
			<!-- //Map -->


		</div> <!-- //div.assignment_in_game -->

		<!-- Check if teamates has answered -->
			";
			if($get_current_session_play_as_user_group_team == "team"){
				echo"
				<script language=\"javascript\" type=\"text/javascript\">
				\$(document).ready(function () {
					function check_if_assignment_is_solved(){
						var data = 'game_id=$get_current_game_id&session_id=$get_current_session_id&im_on_assignment_number=$get_current_session_is_on_assignment_number';
            					\$.ajax({
                					type: \"GET\",
               						url: \"play_game_3_assignments_check_if_assignment_is_solved.php\",
                					data: data,
							beforeSend: function(html) { // this happens before actual call
							},
               						success: function(html){
                    						\$(\"#check_for_solved\").append(html);
							}
						});
					}
					setInterval(check_if_assignment_is_solved,1000);
       				});
				</script>
				<div id=\"check_for_solved\"></div>
				";	
			}
		echo"
		<!-- //Check if teamates has answered -->
		";

	} // action == ""
	elseif($action == "answer_a_question"){
		$inp_answer = $_POST['inp_answer'];
		$inp_answer = output_html($inp_answer);
		$inp_answer_mysql = quote_smart($link, $inp_answer);
		if($inp_answer == ""){
			$url = "play_game_3_assignments.php?game_id=$get_current_game_id&session_id=$get_current_session_id&l=$l&ft=info&fm=no_answer_given&id=6";
			header("Location: $url");
			exit;
		}

		$inp_answer_clean = clean($inp_answer);
		$inp_answer_clean_mysql = quote_smart($link, $inp_answer_clean);


		// Check if I have answered something before
		$query = "SELECT answer_id, answer_session_id, answer_assignment_id, answer_assignment_number, answer_by_user_group_team, answer_by_user_id, answer_by_group_id, answer_by_team_id, answer_datetime, answer_path, answer_file, answer_text, answer_i_have_flagged_it, answer_is_checked, answer_is_correct, answer_score FROM $t_rebus_games_sessions_answers WHERE answer_session_id=$get_current_session_id AND answer_assignment_id=$get_current_assignment_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_answer_id, $get_current_answer_session_id, $get_current_answer_assignment_id, $get_current_answer_assignment_number, $get_current_answer_by_user_group_team, $get_current_answer_by_user_id, $get_current_answer_by_group_id, $get_current_answer_by_team_id, $get_current_answer_datetime, $get_current_answer_path, $get_current_answer_file, $get_current_answer_text, $get_current_answer_i_have_flagged_it, $get_current_answer_is_checked, $get_current_answer_is_correct, $get_current_answer_score) = $row;
			


		// Check if correct
		if($inp_answer_clean == "$get_current_assignment_answer_a_clean" OR $inp_answer_clean == "$get_current_assignment_answer_b_clean"){
			// Correct!
			if($get_current_answer_id == ""){
				$play_as_mysql = quote_smart($link, $get_current_session_play_as_user_group_team);
				if($get_current_session_group_id == ""){ $get_current_session_group_id = 0; }
				if($get_current_session_team_id == ""){ $get_current_session_team_id = 0; }

				mysqli_query($link, "INSERT INTO $t_rebus_games_sessions_answers 
				(answer_id, answer_session_id, answer_assignment_id, answer_assignment_number, answer_by_user_group_team, 
				answer_by_user_id, answer_by_group_id, answer_by_team_id, answer_datetime, answer_text, 
				answer_i_have_flagged_it, answer_is_checked, answer_is_correct, answer_score) 
				VALUES 
				(NULL, $get_current_session_id, $get_current_assignment_id, $get_current_assignment_number, $play_as_mysql,
				$my_user_id_mysql, $get_current_session_group_id, $get_current_session_team_id, '$datetime', $inp_answer_mysql, 
				0, 1, 1, $get_current_assignment_points)")
				or die(mysqli_error($link));
			}
			else{

				mysqli_query($link, "UPDATE $t_rebus_games_sessions_answers SET 
						answer_datetime='$datetime', 
						answer_text=$inp_answer_mysql, 
						answer_i_have_flagged_it=0, 
						answer_is_checked=1, 
						answer_is_correct=1, 
						answer_score=$get_current_assignment_points
						WHERE answer_id=$get_current_answer_id") or die(mysqli_error($link));
			}
			
			// Next assignment
			$next_assignment_number = $get_current_assignment_number+1;
			$inp_points = $get_current_session_points+$get_current_assignment_points;
			mysqli_query($link, "UPDATE $t_rebus_games_sessions_index SET 
						session_is_on_assignment_number=$next_assignment_number,
						session_points=$inp_points
						WHERE session_id=$get_current_session_id") or die(mysqli_error($link));

			// Correct answer animation
			$url = "play_game_5_correct_answer_animation.php?game_id=$get_current_game_id&session_id=$get_current_session_id&id=7";
			$url = $url . "&l=$l&ft=success&fm=correct_answer";
			header("Location: $url");
			exit;

		}
		else{
			// Incorrect!
			$url = "play_game_3_assignments.php?game_id=$get_current_game_id&session_id=$get_current_session_id&l=$l&ft=error&fm=wrong_answer_given&id=8#feedback";
			header("Location: $url");
			exit;
		}
	} // action == "answer_a_question"
	elseif($action == "answer_take_a_picture_with_coordinates"){

		// Check if I have answered something before
		$query = "SELECT answer_id, answer_session_id, answer_assignment_id, answer_assignment_number, answer_by_user_group_team, answer_by_user_id, answer_by_group_id, answer_by_team_id, answer_datetime, answer_path, answer_file, answer_text, answer_i_have_flagged_it, answer_is_checked, answer_is_correct, answer_score FROM $t_rebus_games_sessions_answers WHERE answer_session_id=$get_current_session_id AND answer_assignment_id=$get_current_assignment_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_answer_id, $get_current_answer_session_id, $get_current_answer_assignment_id, $get_current_answer_assignment_number, $get_current_answer_by_user_group_team, $get_current_answer_by_user_id, $get_current_answer_by_group_id, $get_current_answer_by_team_id, $get_current_answer_datetime, $get_current_answer_path, $get_current_answer_file, $get_current_answer_text, $get_current_answer_i_have_flagged_it, $get_current_answer_is_checked, $get_current_answer_is_correct, $get_current_answer_score) = $row;
		

		// Delete old image
		if($get_current_answer_id != ""){
			if(file_exists("../$get_current_answer_path/$get_current_answer_file") && $get_current_answer_file != ""){
				unlink("../$get_current_answer_path/$get_current_answer_file");
			}
		}

		// Me
		$query = "SELECT user_id, user_email, user_name, user_language, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_language, $get_my_user_rank) = $row;
			
		$inp_my_user_name_mysql = quote_smart($link, $get_my_user_name);
		$inp_my_user_email_mysql = quote_smart($link, $get_my_user_email);

		// Ip 
		$my_ip = $_SERVER['REMOTE_ADDR'];
		$my_ip = output_html($my_ip);
		$my_ip_mysql = quote_smart($link, $my_ip);

		// Directory for storing
		if(!(is_dir("../_uploads"))){
			mkdir("../_uploads");
		}
		if(!(is_dir("../_uploads/rebus"))){
			mkdir("../_uploads/rebus");
		}
		if(!(is_dir("../_uploads/rebus/game_sessions"))){
			mkdir("../_uploads/rebus/game_sessions");
		}
		if(!(is_dir("../_uploads/rebus/game_sessions/$get_current_session_id"))){
			mkdir("../_uploads/rebus/game_sessions/$get_current_session_id");
		}
	
		/*- Image upload ------------------------------------------------------------------------------------------ */
		if(isset($_FILES['inp_answer']['name'])){
			$name = stripslashes($_FILES['inp_answer']['name']);
		}
		else{
			$url = "play_game_3_assignments.php?game_id=$get_current_game_id&session_id=$get_current_session_id&l=$l&ft=error&fm=no_image_uploaded#feedback";
			header("Location: $url");
			exit;
			
		}
		$extension = get_extension($name);
		$extension = strtolower($extension);

		$ft_image = "";
		$fm_image = "";
		if($name){
			if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
				$ft_image = "warning";
				$fm_image = "unknown_image_file_extension";

				$url = "play_game_3_assignments.php?game_id=$get_current_game_id&session_id=$get_current_session_id&l=$l&ft=$ft_image&fm=$fm_image&id=9#feedback";
				header("Location: $url");
				exit;
			}
			else{
				// Read exif
				$exif = @exif_read_data($_FILES['inp_answer']['tmp_name']);


				$new_path = "../_uploads/rebus/game_sessions/$get_current_session_id/";
				$new_name = date("ymdhis");
				$uploaded_file = $new_path . $new_name . "." . $extension;

				// Upload file
				if (move_uploaded_file($_FILES['inp_answer']['tmp_name'], $uploaded_file)) {
					// Get image size
					$file_size = filesize($uploaded_file);
						
					// Check with and height
					list($width,$height) = getimagesize($uploaded_file);
	
					if($width == "" OR $height == ""){
						$ft_image = "warning";
						$fm_image = "getimagesize_failed";
						unlink($uploaded_file);

						$url = "play_game_3_assignments.php?game_id=$get_current_game_id&session_id=$get_current_session_id&&l=$l&ft=$ft_image&fm=$fm_image&id=10#feedback";
						header("Location: $url");
						exit;
					}
					else{
						// Check rotation
						/*
						if (!empty($exif['Orientation'])) {
							if($extension == "jpg" OR $extension == "jpeg"){
								$image_resource = imagecreatefromjpeg($uploaded_file);
							}
							elseif($extension == "png"){
								$image_resource = imagecreatefrompng($uploaded_file);
							}
							elseif($extension == "gif"){
								$image_resource = imagecreatefromgif($uploaded_file);
							}
							switch ($exif['Orientation']) {
								case 3:
									$image = imagerotate($image_resource, 180, 0);
									break;
								case 6:
									$image = imagerotate($image_resource, -90, 0);
									break;
								case 8:
									$image = imagerotate($image_resource, 90, 0);
									break;
								default:
									$image = $image_resource;
							}
							imagejpeg($image, $uploaded_file, 90);
							imagedestroy($image_resource);
							@imagedestroy($image);
						}
						*/
						$rotation = 0;
						if (!empty($exif['Orientation'])) {
							switch ($exif['Orientation']) {
								case 3:
									$rotation = 180;
									break;
								case 6:
									$rotation = -90;
									break;
								case 8:
									$rotation = 90;
									break;
								default:
									$rotation = 0;
							}
						}

						// Fetch exif from browser
						$inp_latitude = $_POST['inp_latitude'];
						$inp_latitude = output_html($inp_latitude);

						$inp_longitude = $_POST['inp_longitude'];
						$inp_longitude = output_html($inp_longitude);

						if($exif == ""){
							// unlink($uploaded_file);
							// $url = "play_game_3_assignments.php?game_id=$get_current_game_id&session_id=$get_current_session_id&&l=$l&ft=warning&fm=cannot_read_exif_gps_coordinates_from_the_picture&id=11#feedback";
							// header("Location: $url");
							// exit;
							$gps_longitude = "$inp_longitude"; // Use failsafe
							$gps_latitude = "$inp_latitude"; // Use failsafe
						}
						else{
							if(!(isset($exif["GPSLongitude"]))){
								// unlink($uploaded_file);
								// $url = "play_game_3_assignments.php?game_id=$get_current_game_id&session_id=$get_current_session_id&&l=$l&ft=warning&fm=cannot_read_exif_gps_longitude_from_the_picture&id=11b#feedback";
								// header("Location: $url");
								// exit;
								$gps_longitude = "$inp_longitude"; // Use failsafe
								$gps_latitude = "$inp_latitude"; // Use failsafe
							}
							else{
								$gps_longitude = getGps($exif["GPSLongitude"], $exif['GPSLongitudeRef']);
								$gps_latitude = getGps($exif["GPSLatitude"], $exif['GPSLatitudeRef']);
							}
						}

						$distance_km = getDistance($gps_longitude, $gps_latitude, $get_current_assignment_answer_b, $get_current_assignment_answer_a);
						$distance_m = $distance_km*1000;
						// echo"$distance_normal <br />$distance_switched ";

						if ($distance_m < $get_current_assignment_radius_metric) {
							// Resize to 1280x720
							$uploaded_file_new = $uploaded_file;
							if($width > "1281" OR $height > "720"){

								$resize_width = "1280";
								$resize_height = "720";
								if($rotation != "0"){
									$resize_height = "1280";
								}
								resize_crop_image($resize_width, $resize_height, $uploaded_file, $uploaded_file_new, $quality = 80);
							}

							// Rotation
							if($rotation != "0"){
								$image_resource = imagecreatefromjpeg($uploaded_file);
								$image = imagerotate($image_resource, $rotation, 0);
								imagejpeg($image, $uploaded_file, 90);
								imagedestroy($image_resource);
								@imagedestroy($image);
							}

							// MySQL
							$inp_path = "_uploads/rebus/game_sessions/$get_current_session_id";
							$inp_path = output_html($inp_path);
							$inp_path_mysql = quote_smart($link, $inp_path);

							$inp_file = $new_name . "." . $extension;
							$inp_file_mysql = quote_smart($link, $inp_file);

							$inp_text = stripslashes($_FILES['inp_answer']['name']);
							$inp_text = output_html($inp_text);
							$inp_text_mysql = quote_smart($link, $inp_text);

							$play_as_mysql = quote_smart($link, $get_current_session_play_as_user_group_team);

							if($get_current_answer_id == ""){
								if($get_current_session_group_id == ""){ $get_current_session_group_id = 0; }
								if($get_current_session_team_id == ""){ $get_current_session_team_id = 0; }

								mysqli_query($link, "INSERT INTO $t_rebus_games_sessions_answers 
								(answer_id, answer_session_id, answer_assignment_id, answer_assignment_number, answer_by_user_group_team, 
								answer_by_user_id, answer_by_group_id, answer_by_team_id, answer_by_ip, answer_datetime, 
								answer_path, answer_file, answer_text, answer_i_have_flagged_it, answer_is_checked, 
								answer_is_correct, answer_score) 
								VALUES 
								(NULL, $get_current_session_id, $get_current_assignment_id, $get_current_assignment_number, $play_as_mysql,
								$my_user_id_mysql, $get_current_session_group_id, $get_current_session_team_id, $my_ip_mysql, '$datetime', 
								$inp_path_mysql, $inp_file_mysql, $inp_text_mysql, 0, 1, 
								1, $get_current_assignment_points)")
								or die(mysqli_error($link));
							}
							else{
								mysqli_query($link, "UPDATE $t_rebus_games_sessions_answers SET 
									answer_by_user_id=$my_user_id_mysql, 
									answer_by_ip=$my_ip_mysql,  
									answer_datetime='$datetime', 
									answer_path=$inp_path_mysql, 
									answer_file=$inp_file_mysql, 
									answer_text=$inp_text_mysql, 
									answer_i_have_flagged_it=0, 
									answer_is_checked=1, 
									answer_is_correct=1, 
									answer_score=$get_current_assignment_points
									WHERE answer_id=$get_current_answer_id") or die(mysqli_error($link));
							}

							// Next assignment
							$next_assignment_number = $get_current_assignment_number+1;
							$inp_points = $get_current_session_points+$get_current_assignment_points;
							mysqli_query($link, "UPDATE $t_rebus_games_sessions_index SET 
										session_is_on_assignment_number=$next_assignment_number,
										session_points=$inp_points
										WHERE session_id=$get_current_session_id") or die(mysqli_error($link));

							// Correct answer animation
							$url = "play_game_5_correct_answer_animation.php?game_id=$get_current_game_id&session_id=$get_current_session_id&next_assignment_number=$next_assignment_number&l=$l&ft=success&fm=correct_answer&id=12";
							header("Location: $url");
							exit;


						
						} // answer accepted
						else{
							// echo"Wrong answer.
							// <table><tr><td>$gps_latitude_approximate </td><td>!=</td><td>$get_current_answer_latitude_approximate </td><tr>
							// <tr><td>$gps_longitude_approximate </td><td>!=</td><td>$get_current_answer_longitude_approximate </td><tr></table>";

							$distance_m = round($distance_m, 0);
							$url = "play_game_3_assignments.php?game_id=$get_current_game_id&session_id=$get_current_session_id&l=$l&ft=error&fm=sorry_the_coordinates_are_not_correct&answer_latitude=$gps_latitude&answer_longitude=$gps_longitude&answer_distance_m=$distance_m#feedback";
							header("Location: $url");
							exit;
						}
					}
				} // move_uploaded_file
				else{
					$ft_image = "error";
					switch ($_FILES['inp_image']['error']) {
						case UPLOAD_ERR_OK:
							$ft_image = "info";
           						$fm_image = "There is no error, the file uploaded with success.";
							break;
						case UPLOAD_ERR_NO_FILE:
           						$fm_image = "no_file_uploaded";
							break;
						case UPLOAD_ERR_INI_SIZE:
           						$fm_image = "to_big_size_in_configuration";
							break;
						case UPLOAD_ERR_FORM_SIZE:
           						$fm_image = "to_big_size_in_form";
							break;
						default:
           						$fm_image = "unknown_error";
							break;
					}
						
					$url = "play_game_3_assignments.php?game_id=$get_current_game_id&session_id=$get_current_session_id&l=$l&ft=$ft_image&fm=$fm_image&id=14#feedback";
					header("Location: $url");
					exit;
				}
			}
		} // name
		else{
			$url = "play_game_3_assignments.php?game_id=$get_current_game_id&session_id=$get_current_session_id&l=$l&ft=error&fm=no_file_selected&id=15#feedback";
			header("Location: $url");
			exit;

		}
		echo"Hmm..";

	} // action == "answer_take_a_picture_with_coordinates"
}
else{
	echo"
	<h1>
	<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=rebus/my_games.php\">

	<p>Please log in...</p>
	";
}

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>