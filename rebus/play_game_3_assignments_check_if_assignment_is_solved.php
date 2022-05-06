<?php
/**
*
* File: rebus/play_game_3_assignments_check_if_assignment_is_solved.php
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
if(isset($_GET['im_on_assignment_number'])) {
	$im_on_assignment_number = $_GET['im_on_assignment_number'];
	$im_on_assignment_number = output_html($im_on_assignment_number);
	if(!(is_numeric($im_on_assignment_number))){
		echo"im_on_assignment_number not numeric";
		die;
	}
}
else{
	echo"Missing im_on_assignment_number";
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
	$query = "SELECT game_id, game_title, game_language, game_introduction, game_description, game_privacy, game_published, game_playable_after_datetime, game_playable_after_datetime_saying, game_playable_after_time, game_group_id, game_group_name, game_times_played, game_times_finished, game_finished_percentage, game_time_used_seconds, game_time_used_saying, game_image_path, game_image_file, game_image_thumb_570x321, game_image_thumb_278x156, game_country_id, game_country_name, game_county_id, game_county_name, game_municipality_id, game_municipality_name, game_city_id, game_city_name, game_place_id, game_place_name, game_number_of_assignments, game_created_by_user_id, game_created_by_user_name, game_created_by_user_email, game_created_by_ip, game_created_by_hostname, game_created_by_user_agent, game_created_datetime, game_created_date_saying, game_updated_by_user_id, game_updated_by_user_name, game_updated_by_user_email, game_updated_by_ip, game_updated_by_hostname, game_updated_by_user_agent, game_updated_datetime, game_updated_date_saying FROM $t_rebus_games_index WHERE game_id=$game_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_game_id, $get_current_game_title, $get_current_game_language, $get_current_game_introduction, $get_current_game_description, $get_current_game_privacy, $get_current_game_published, $get_current_game_playable_after_datetime, $get_current_game_playable_after_datetime_saying, $get_current_game_playable_after_time, $get_current_game_group_id, $get_current_game_group_name, $get_current_game_times_played, $get_current_game_times_finished, $get_current_game_finished_percentage, $get_current_game_time_used_seconds, $get_current_game_time_used_saying, $get_current_game_image_path, $get_current_game_image_file, $get_current_game_image_thumb_570x321, $get_current_game_image_thumb_278x156, $get_current_game_country_id, $get_current_game_country_name, $get_current_game_county_id, $get_current_game_county_name, $get_current_game_municipality_id, $get_current_game_municipality_name, $get_current_game_city_id, $get_current_game_city_name, $get_current_game_place_id, $get_current_game_place_name, $get_current_game_number_of_assignments, $get_current_game_created_by_user_id, $get_current_game_created_by_user_name, $get_current_game_created_by_user_email, $get_current_game_created_by_ip, $get_current_game_created_by_hostname, $get_current_game_created_by_user_agent, $get_current_game_created_datetime, $get_current_game_created_date_saying, $get_current_game_updated_by_user_id, $get_current_game_updated_by_user_name, $get_current_game_updated_by_user_email, $get_current_game_updated_by_ip, $get_current_game_updated_by_hostname, $get_current_game_updated_by_user_agent, $get_current_game_updated_datetime, $get_current_game_updated_date_saying) = $row;
	if($get_current_game_id == ""){
		echo"Game not found";
		die;
	}


	// Is public?
	if($get_current_game_privacy == "private"){
		echo"Private!!";
	}


	/*- Find my game session ---------------------------------------------------------------- */
	$session_id_mysql = quote_smart($link, $session_id);
	$query = "SELECT session_id, session_game_id, session_play_as_user_group_team, session_user_id, session_group_id, session_team_id, session_start_datetime, session_start_time, session_is_on_assignment_number, session_is_finished, session_finished_datetime, session_finished_time, session_seconds_used, session_time_used_saying FROM $t_rebus_games_sessions_index";
	$query = $query . " WHERE session_id=$session_id_mysql AND session_game_id=$get_current_game_id";
	$query = $query . " AND session_is_finished=0";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_session_id, $get_current_session_game_id, $get_current_session_play_as_user_group_team, $get_current_session_user_id, $get_current_session_group_id, $get_current_session_team_id, $get_current_session_start_datetime, $get_current_session_start_time, $get_current_session_is_on_assignment_number, $get_current_session_is_finished, $get_current_session_finished_datetime, $get_current_session_finished_time, $get_current_session_seconds_used, $get_current_session_time_used_saying) = $row;

	if($get_current_session_id == ""){
		echo"Sorry, could not find your game session.";
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
		echo"
		<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
		<meta http-equiv=\"refresh\" content=\"1;url=play_game_5_finished.php?game_id=$get_current_game_id&amp;session_id=$get_current_session_id\">";

	}
	else{
		if($get_current_assignment_number != "$im_on_assignment_number"){
			// Teamates have solved assignment, refresh
			echo"
			<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
			<meta http-equiv=\"refresh\" content=\"1;url=play_game_5_correct_answer_animation.php?game_id=$get_current_game_id&amp;session_id=$get_current_session_id\">
			";
		}
	}
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