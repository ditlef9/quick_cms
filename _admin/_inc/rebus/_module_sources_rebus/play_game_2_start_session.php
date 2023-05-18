<?php
/**
*
* File: rebus/play_game_2_start_session.php
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

    return floatval($parts[0]) / floatval($parts[1]);
}
function seconds_to_time_array($seconds) {
	$dtF = new \DateTime('@0');
	$dtT = new \DateTime("@$seconds");
	return $dtF->diff($dtT)->format('%a|%h|%i|%s');
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
if(isset($_GET['play_as'])) {
	$play_as = $_GET['play_as'];
	$play_as = output_html($play_as);
	if($play_as != "user" && $play_as != "group" && $play_as != "team"){
		echo"play_as out of range";
		die;
	}
}
else{
	$play_as = "user";
}
$team_id = 0;
if($play_as == "team"){
	if(isset($_GET['team_id'])) {
		$team_id = $_GET['team_id'];
		$team_id = output_html($team_id);
		if(!(is_numeric($team_id))){
			echo"team_id not numeric";
			die;
		}
	}
	else{
		echo"Missing team id";
		die;
	}

}

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);


	// Dates
	$datetime = date("Y-m-d H:i:s");
	$time = time();
	$year = date("Y");
	$month = date("m");
	$day = date("d");
	$hour = date("H");
	$minute = date("i");

	/*- Find game ------------------------------------------------------------------------- */
	$game_id_mysql = quote_smart($link, $game_id);
	$query = "SELECT game_id, game_title, game_language, game_introduction, game_description, game_privacy, game_published, game_playable_after_datetime, game_playable_after_datetime_saying, game_playable_after_time, game_group_id, game_group_name, game_times_played, game_times_finished, game_finished_percentage, game_time_used_seconds, game_time_used_saying, game_image_path, game_image_file, game_image_thumb_570x321, game_image_thumb_278x156, game_country_id, game_country_name, game_county_id, game_county_name, game_municipality_id, game_municipality_name, game_city_id, game_city_name, game_place_id, game_place_name, game_number_of_assignments, game_created_by_user_id, game_created_by_user_name, game_created_by_user_email, game_created_by_ip, game_created_by_hostname, game_created_by_user_agent, game_created_datetime, game_created_date_saying, game_updated_by_user_id, game_updated_by_user_name, game_updated_by_user_email, game_updated_by_ip, game_updated_by_hostname, game_updated_by_user_agent, game_updated_datetime, game_updated_date_saying FROM $t_rebus_games_index WHERE game_id=$game_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_game_id, $get_current_game_title, $get_current_game_language, $get_current_game_introduction, $get_current_game_description, $get_current_game_privacy, $get_current_game_published, $get_current_game_playable_after_datetime, $get_current_game_playable_after_datetime_saying, $get_current_game_playable_after_time, $get_current_game_group_id, $get_current_game_group_name, $get_current_game_times_played, $get_current_game_times_finished, $get_current_game_finished_percentage, $get_current_game_time_used_seconds, $get_current_game_time_used_saying, $get_current_game_image_path, $get_current_game_image_file, $get_current_game_image_thumb_570x321, $get_current_game_image_thumb_278x156, $get_current_game_country_id, $get_current_game_country_name, $get_current_game_county_id, $get_current_game_county_name, $get_current_game_municipality_id, $get_current_game_municipality_name, $get_current_game_city_id, $get_current_game_city_name, $get_current_game_place_id, $get_current_game_place_name, $get_current_game_number_of_assignments, $get_current_game_created_by_user_id, $get_current_game_created_by_user_name, $get_current_game_created_by_user_email, $get_current_game_created_by_ip, $get_current_game_created_by_hostname, $get_current_game_created_by_user_agent, $get_current_game_created_datetime, $get_current_game_created_date_saying, $get_current_game_updated_by_user_id, $get_current_game_updated_by_user_name, $get_current_game_updated_by_user_email, $get_current_game_updated_by_ip, $get_current_game_updated_by_hostname, $get_current_game_updated_by_user_agent, $get_current_game_updated_datetime, $get_current_game_updated_date_saying) = $row;
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
	// Create a new session
	$play_as_mysql = quote_smart($link, $play_as);
	if($play_as == "user"){
		mysqli_query($link, "INSERT INTO $t_rebus_games_sessions_index
		(session_id, session_game_id, session_play_as_user_group_team, session_user_id,  session_start_datetime, 
		session_start_year, session_start_month, session_start_day, session_start_hour, session_start_minute, 
		session_start_time, session_is_on_assignment_number, session_points, session_ended_game, session_is_finished) 
		VALUES 
		(NULL, $get_current_game_id, $play_as_mysql, $my_user_id_mysql, '$datetime', 
		$year, $month, $day, $hour, $minute, '$time', 1, 0, 0, 0)")
		or die(mysqli_error($link));
	}
	elseif($play_as == "team"){
		$team_id_mysql = quote_smart($link, $team_id);
		mysqli_query($link, "INSERT INTO $t_rebus_games_sessions_index
		(session_id, session_game_id, session_play_as_user_group_team, session_team_id, session_start_datetime, 
		session_start_year, session_start_month, session_start_day, session_start_hour, session_start_minute, session_start_time, 
		session_is_on_assignment_number, session_points, session_ended_game, session_is_finished) 
		VALUES 
		(NULL, $get_current_game_id, $play_as_mysql, $team_id_mysql, '$datetime', 
		$year, $month, $day, $hour, $minute, 
		'$time', 1, 0, 0, 0)")
		or die(mysqli_error($link));
	}
	elseif($play_as == "group"){
		mysqli_query($link, "INSERT INTO $t_rebus_games_sessions_index
		(session_id, session_game_id, session_play_as_user_group_team, session_group_id, session_start_datetime, 
		session_start_year, session_start_month, session_start_day, session_start_hour, session_start_minute, session_start_time, 
		session_is_on_assignment_number, session_points, session_ended_game, session_is_finished) 
		VALUES 
		(NULL, $get_current_game_id, $play_as_mysql, $group_id_mysql, '$datetime', 
		$year, $month, $day, $hour, $minute, '$time', 1, 0, 0, 0)")
		or die(mysqli_error($link));
	}

	// Get ID 
	$query = "SELECT session_id, session_game_id, session_play_as_user_group_team, session_user_id, session_group_id, session_team_id, session_start_datetime, session_start_time, session_is_on_assignment_number, session_is_finished, session_finished_datetime, session_finished_time, session_seconds_used, session_time_used_saying FROM $t_rebus_games_sessions_index";
	$query = $query . " WHERE session_game_id=$get_current_game_id AND session_play_as_user_group_team=$play_as_mysql";
	if($play_as == "user"){
		$query = $query . " AND session_user_id=$my_user_id_mysql";
	}
	elseif($play_as == "team"){
		$query = $query . " AND session_team_id=$team_id_mysql";
	}
	elseif($play_as == "group"){
		$query = $query . " AND session_group_id=$group_id_mysql";
	}
	$query = $query . " AND session_is_finished=0 ORDER BY session_id DESC LIMIT 0,1";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_session_id, $get_current_session_game_id, $get_current_session_play_as_user_group_team, $get_current_session_user_id, $get_current_session_group_id, $get_current_session_team_id, $get_current_session_start_datetime, $get_current_session_start_time, $get_current_session_is_on_assignment_number, $get_current_session_is_finished, $get_current_session_finished_datetime, $get_current_session_finished_time, $get_current_session_seconds_used, $get_current_session_time_used_saying) = $row;

	// Update game
	$inp_times_played = $get_current_game_times_played+1;
	mysqli_query($link, "UPDATE $t_rebus_games_index SET game_times_played=$inp_times_played WHERE game_id=$get_current_game_id") or die(mysqli_error($link));

	// Header
	$url = "play_game_3_assignments.php?game_id=$get_current_game_id&session_id=$get_current_session_id&l=$l";
	header("Location: $url");
	exit;

}
else{
	echo"
	<h1>
	<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=rebus/index.php\">

	<p>Please log in...</p>
	";
}

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>