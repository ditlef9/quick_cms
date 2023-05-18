<?php
/**
*
* File: rebus/play_game_4_use_hint.php
* Version 1.0.0.
* Date 09:50 01.07.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
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
include("_tables_rebus.php");



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

$tabindex = 0;

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);


	// Dates
	$datetime = date("Y-m-d H:i:s");
	$time = time();

	/*- Find game ------------------------------------------------------------------------- */
	$game_id_mysql = quote_smart($link, $game_id);
	$query = "SELECT game_id, game_title, game_language, game_introduction, game_description, game_privacy, game_published, game_playable_after_datetime, game_playable_after_datetime_saying, game_playable_after_time, game_group_id, game_group_name, game_times_played, game_image_path, game_image_file, game_created_by_user_id, game_created_by_user_name, game_created_by_user_email, game_created_by_ip, game_created_by_hostname, game_created_by_user_agent, game_created_datetime, game_created_date_saying, game_updated_by_user_id, game_updated_by_user_name, game_updated_by_user_email, game_updated_by_ip, game_updated_by_hostname, game_updated_by_user_agent, game_updated_datetime, game_updated_date_saying FROM $t_rebus_games_index WHERE game_id=$game_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_game_id, $get_current_game_title, $get_current_game_language, $get_current_game_introduction, $get_current_game_description, $get_current_game_privacy, $get_current_game_published, $get_current_game_playable_after_datetime, $get_current_game_playable_after_datetime_saying, $get_current_game_playable_after_time, $get_current_game_group_id, $get_current_game_group_name, $get_current_game_times_played, $get_current_game_image_path, $get_current_game_image_file, $get_current_game_created_by_user_id, $get_current_game_created_by_user_name, $get_current_game_created_by_user_email, $get_current_game_created_by_ip, $get_current_game_created_by_hostname, $get_current_game_created_by_user_agent, $get_current_game_created_datetime, $get_current_game_created_date_saying, $get_current_game_updated_by_user_id, $get_current_game_updated_by_user_name, $get_current_game_updated_by_user_email, $get_current_game_updated_by_ip, $get_current_game_updated_by_hostname, $get_current_game_updated_by_user_agent, $get_current_game_updated_datetime, $get_current_game_updated_date_saying) = $row;
	if($get_current_game_id == ""){
		$url = "index.php?ft=error&fm=game_not_found&l=$l";
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
		echo"Could not find session";
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

	/* Find the assignment I answered */
	$query = "SELECT assignment_id, assignment_game_id, assignment_number, assignment_type, assignment_value, assignment_address, assignment_answer_a, assignment_answer_a_clean, assignment_answer_b, assignment_answer_b_clean, assignment_radius_metric, assignment_radius_imperial, assignment_radius_user_measurment, assignment_hint_a_value, assignment_hint_a_price, assignment_hint_b_value, assignment_hint_b_price, assignment_hint_c_value, assignment_hint_c_price, assignment_points, assignment_text_when_correct_answer, assignment_time_to_solve_seconds, assignment_time_to_solve_saying, assignment_created_by_user_id, assignment_created_by_ip, assignment_created_datetime, assignment_updated_by_user_id, assignment_updated_by_ip, assignment_updated_datetime FROM $t_rebus_games_assignments WHERE assignment_game_id=$get_current_game_id AND assignment_number=$get_current_session_is_on_assignment_number";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_assignment_id, $get_current_assignment_game_id, $get_current_assignment_number, $get_current_assignment_type, $get_current_assignment_value, $get_current_assignment_address, $get_current_assignment_answer_a, $get_current_assignment_answer_a_clean, $get_current_assignment_answer_b, $get_current_assignment_answer_b_clean, $get_current_assignment_radius_metric, $get_current_assignment_radius_imperial, $get_current_assignment_radius_user_measurment, $get_current_assignment_hint_a_value, $get_current_assignment_hint_a_price, $get_current_assignment_hint_b_value, $get_current_assignment_hint_b_price, $get_current_assignment_hint_c_value, $get_current_assignment_hint_c_price, $get_current_assignment_points, $get_current_assignment_text_when_correct_answer, $get_current_assignment_time_to_solve_seconds, $get_current_assignment_time_to_solve_saying, $get_current_assignment_created_by_user_id, $get_current_assignment_created_by_ip, $get_current_assignment_created_datetime, $get_current_assignment_updated_by_user_id, $get_current_assignment_updated_by_ip, $get_current_assignment_updated_datetime) = $row;

	if($get_current_assignment_id == ""){
		echo"Could not find previous assignment";
		die;
	}

	// Find my answer
	$query = "SELECT answer_id, answer_session_id, answer_assignment_id, answer_assignment_number, answer_by_user_group_team, answer_by_user_id, answer_by_group_id, answer_by_team_id, answer_by_ip, answer_datetime, answer_path, answer_file, answer_text, answer_i_have_flagged_it, answer_is_checked, answer_is_correct, answer_used_hint_a, answer_used_hint_b, answer_used_hint_c, answer_score FROM $t_rebus_games_sessions_answers WHERE answer_session_id=$get_current_session_id AND answer_assignment_id=$get_current_assignment_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_answer_id, $get_current_answer_session_id, $get_current_answer_assignment_id, $get_current_answer_assignment_number, $get_current_answer_by_user_group_team, $get_current_answer_by_user_id, $get_current_answer_by_group_id, $get_current_answer_by_team_id, $get_current_answer_by_ip, $get_current_answer_datetime, $get_current_answer_path, $get_current_answer_file, $get_current_answer_text, $get_current_answer_i_have_flagged_it, $get_current_answer_is_checked, $get_current_answer_is_correct, $get_current_answer_used_hint_a, $get_current_answer_used_hint_b, $get_current_answer_used_hint_c, $get_current_answer_score) = $row;
	if($get_current_answer_id == ""){
		// Insert
		$play_as_mysql = quote_smart($link, $get_current_session_play_as_user_group_team);
		if($get_current_session_group_id == ""){ $get_current_session_group_id = 0; }
		if($get_current_session_team_id == ""){ $get_current_session_team_id = 0; }
		mysqli_query($link, "INSERT INTO $t_rebus_games_sessions_answers 
				(answer_id, answer_session_id, answer_assignment_id, answer_assignment_number, answer_by_user_group_team, 
				answer_by_user_id, answer_by_group_id, answer_by_team_id, answer_datetime, answer_text, 
				answer_i_have_flagged_it, answer_is_checked, answer_is_correct, answer_score) 
				VALUES 
				(NULL, $get_current_session_id, $get_current_assignment_id, $get_current_assignment_number, $play_as_mysql,
				$my_user_id_mysql, $get_current_session_group_id, $get_current_session_team_id, '$datetime', '', 
				0, 0, 0, 0)")
				or die(mysqli_error($link));

		// Get ID
		$query = "SELECT answer_id, answer_session_id, answer_assignment_id, answer_assignment_number, answer_by_user_group_team, answer_by_user_id, answer_by_group_id, answer_by_team_id, answer_by_ip, answer_datetime, answer_path, answer_file, answer_text, answer_i_have_flagged_it, answer_is_checked, answer_is_correct, answer_used_hint_a, answer_used_hint_b, answer_used_hint_c, answer_score FROM $t_rebus_games_sessions_answers WHERE answer_session_id=$get_current_session_id AND answer_assignment_id=$get_current_assignment_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_answer_id, $get_current_answer_session_id, $get_current_answer_assignment_id, $get_current_answer_assignment_number, $get_current_answer_by_user_group_team, $get_current_answer_by_user_id, $get_current_answer_by_group_id, $get_current_answer_by_team_id, $get_current_answer_by_ip, $get_current_answer_datetime, $get_current_answer_path, $get_current_answer_file, $get_current_answer_text, $get_current_answer_i_have_flagged_it, $get_current_answer_is_checked, $get_current_answer_is_correct, $get_current_answer_used_hint_a, $get_current_answer_used_hint_b, $get_current_answer_used_hint_c, $get_current_answer_score) = $row;
	}


	// Are there hints available?
	$hint_text = "...hint...";
	if($get_current_assignment_hint_a_value != ""){
		// A is available
		if($get_current_answer_used_hint_a == ""){
			// Show hint A
			$hint_text = "$get_current_assignment_hint_a_value";
			
			// Update that I used hint
			mysqli_query($link, "UPDATE $t_rebus_games_sessions_answers SET answer_used_hint_a=1 WHERE answer_id=$get_current_answer_id") or die(mysqli_error($link));

			// Update sessions points
			$inp_session_points = $get_current_session_points-$get_current_assignment_hint_a_price;
			if($inp_session_points > 0){
				mysqli_query($link, "UPDATE $t_rebus_games_sessions_index SET session_points=$inp_session_points WHERE session_id=$get_current_session_id") or die(mysqli_error($link));
			}
		}
		else{
			if($get_current_assignment_hint_b_value != ""){
				// A is available
				if($get_current_answer_used_hint_b == ""){

					// Show hint B
					$hint_text = "$get_current_assignment_hint_b_value";
				
					// Update that I used hint
					mysqli_query($link, "UPDATE $t_rebus_games_sessions_answers SET answer_used_hint_b=1 WHERE answer_id=$get_current_answer_id") or die(mysqli_error($link));
	
					// Update sessions points
					$inp_session_points = $get_current_session_points-$get_current_assignment_hint_b_price;
					if($inp_session_points > 0){
						mysqli_query($link, "UPDATE $t_rebus_games_sessions_index SET session_points=$inp_session_points WHERE session_id=$get_current_session_id") or die(mysqli_error($link));
					}
				}
				else{
					if($get_current_assignment_hint_c_value != ""){
						// Show hint C
						$hint_text = "$get_current_assignment_hint_c_value";
			
						// Update that I used hint
						mysqli_query($link, "UPDATE $t_rebus_games_sessions_answers SET answer_used_hint_c=1 WHERE answer_id=$get_current_answer_id") or die(mysqli_error($link));

						// Update sessions points
						$inp_session_points = $get_current_session_points-$get_current_assignment_hint_c_price;
						if($inp_session_points > 0){
							mysqli_query($link, "UPDATE $t_rebus_games_sessions_index SET session_points=$inp_session_points WHERE session_id=$get_current_session_id") or die(mysqli_error($link));
						}
					}
				}
			}
		}
	}

	// Random success sound
	$dir = "_sounds/play_game_4_use_hint";
	$files = glob($dir . '/*.*');
	$file = array_rand($files);
	$random_sound = $files[$file];
	

	echo"<!DOCTYPE html>\n";
	echo"<html lang=\"en\">\n";
	echo"<head>\n";
	echo"	<title>$get_current_game_title</title>\n";
	$rand = uniqid();
	echo"	<link rel=\"stylesheet\" type=\"text/css\" href=\"_css/play_game_4_use_hint.css?r=$rand\" />\n";
	echo"	<link rel=\"icon\" href=\"../_uploads/favicon/16x16.png\" type=\"image/png\" sizes=\"16x16\" />\n";
	echo"	<link rel=\"icon\" href=\"../_uploads/favicon/32x32.png\" type=\"image/png\" sizes=\"32x32\" />\n";
	echo"	<link rel=\"icon\" href=\"../_uploads/favicon/260x260.png\" type=\"image/png\" sizes=\"260x260\" />	\n";
	echo"	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n";
	echo"	<meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0;\"/>\n";
	
	echo"	<meta http-equiv=\"refresh\" content=\"6;url=play_game_3_assignments.php?game_id=$get_current_game_id&amp;session_id=$get_current_session_id&amp;l=$l\" />\n";
	echo"</head>\n";
	echo"<body>\n";
	echo"<div class=\"background\""; 
	if(file_exists("../$get_current_game_image_path/$get_current_game_image_file") && $get_current_game_image_file != ""){
		echo" style=\"background: url('../$get_current_game_image_path/$get_current_game_image_file');background-size: cover;\"";
	}
	echo">
	<!-- Center -->
		<div class=\"center\">

			<!-- Random font -->
				<script>
				var fontType = [ \"Arial\", \"Verdana\", \"Helvetica\", \"Tahoma\", \"Trebuchet MS\", \"Times New Roman\", \"Georgia\", \"Garamond\", \"Courier New\", \"Brush Script MT\"];
				var num;
				num=Math.floor(Math.random()*10);
				document.getElementById(\"fontfamily\").style.fontFamily =fontType[num];
				console.log(num)

				</script>
			<!-- //Random font -->

			<div class=\"hint_value\">
				$hint_text
				<p>
				<a href=\"play_game_3_assignments.php?game_id=$get_current_game_id&amp;session_id=$get_current_session_id&amp;l=$l\" class=\"btn_default\">$l_continue</a>
				</p>
			</div>

			<!-- Sound -->
				<div class=\"audio\"></div>
				<audio controls autoplay>
  					<source src=\"$random_sound\">
					Your browser does not support the audio element.
				</audio>
			<!-- //Sound -->
		</div>

	<!-- //Center -->";

	echo"</div>";
	echo"</body>\n";
	echo"</html>";
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
?>