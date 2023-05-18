<?php
/**
*
* File: rebus/play_game_6_calcualte_game_high_score.php
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
	$date_saying = date("j M Y");
	$time = time();

	/*- Find game ------------------------------------------------------------------------- */
	$game_id_mysql = quote_smart($link, $game_id);
	$query = "SELECT game_id, game_title, game_language, game_introduction, game_description, game_privacy, game_published, game_playable_after_datetime, game_playable_after_datetime_saying, game_playable_after_time, game_group_id, game_group_name, game_times_played, game_times_finished, game_finished_percentage, game_time_used_seconds, game_time_used_saying, game_image_path, game_image_file, game_image_thumb_570x321, game_image_thumb_278x156, game_country_id, game_country_name, game_county_id, game_county_name, game_municipality_id, game_municipality_name, game_city_id, game_city_name, game_place_id, game_place_name, game_number_of_assignments, game_rating, game_created_by_user_id, game_created_by_user_name, game_created_by_user_email, game_created_by_ip, game_created_by_hostname, game_created_by_user_agent, game_created_datetime, game_created_date_saying, game_updated_by_user_id, game_updated_by_user_name, game_updated_by_user_email, game_updated_by_ip, game_updated_by_hostname, game_updated_by_user_agent, game_updated_datetime, game_updated_date_saying FROM $t_rebus_games_index WHERE game_id=$game_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_game_id, $get_current_game_title, $get_current_game_language, $get_current_game_introduction, $get_current_game_description, $get_current_game_privacy, $get_current_game_published, $get_current_game_playable_after_datetime, $get_current_game_playable_after_datetime_saying, $get_current_game_playable_after_time, $get_current_game_group_id, $get_current_game_group_name, $get_current_game_times_played, $get_current_game_times_finished, $get_current_game_finished_percentage, $get_current_game_time_used_seconds, $get_current_game_time_used_saying, $get_current_game_image_path, $get_current_game_image_file, $get_current_game_image_thumb_570x321, $get_current_game_image_thumb_278x156, $get_current_game_country_id, $get_current_game_country_name, $get_current_game_county_id, $get_current_game_county_name, $get_current_game_municipality_id, $get_current_game_municipality_name, $get_current_game_city_id, $get_current_game_city_name, $get_current_game_place_id, $get_current_game_place_name, $get_current_game_number_of_assignments, $get_current_game_rating, $get_current_game_created_by_user_id, $get_current_game_created_by_user_name, $get_current_game_created_by_user_email, $get_current_game_created_by_ip, $get_current_game_created_by_hostname, $get_current_game_created_by_user_agent, $get_current_game_created_datetime, $get_current_game_created_date_saying, $get_current_game_updated_by_user_id, $get_current_game_updated_by_user_name, $get_current_game_updated_by_user_email, $get_current_game_updated_by_ip, $get_current_game_updated_by_hostname, $get_current_game_updated_by_user_agent, $get_current_game_updated_datetime, $get_current_game_updated_date_saying) = $row;
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
	$query = $query . " AND session_is_finished=1";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_session_id, $get_current_session_game_id, $get_current_session_play_as_user_group_team, $get_current_session_user_id, $get_current_session_group_id, $get_current_session_team_id, $get_current_session_start_datetime, $get_current_session_start_year, $get_current_session_start_month, $get_current_session_start_day, $get_current_session_start_hour, $get_current_session_start_minute, $get_current_session_start_time, $get_current_session_is_on_assignment_number, $get_current_session_points, $get_current_session_ended_game, $get_current_session_is_finished, $get_current_session_finished_datetime, $get_current_session_finished_time, $get_current_session_seconds_used, $get_current_session_time_used_saying) = $row;

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

	/*- Insert me into high score */
	$inp_play_as_user_group_team_mysql = quote_smart($link, $get_current_session_play_as_user_group_team);

	// User ID, Team ID, Group ID
	$inp_team_id = "0";
	$inp_group_id = "0";
	if($get_current_session_play_as_user_group_team  == "team"){
		$inp_team_id = "$get_current_session_team_id";
	}
	elseif($get_current_session_play_as_user_group_team  == "group"){
		$inp_group_id = "$get_current_session_group_id";
	}

	// User Name, Team Name, Group Name
	$inp_name = "";
	$inp_image_path = "";
	$inp_image_file = "";
	$inp_image_thumb_50x50 = "";
	if($get_current_session_play_as_user_group_team  == "user"){
		$query = "SELECT user_id, user_email, user_name, user_language, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_language, $get_my_user_rank) = $row;


		$query = "SELECT photo_id, photo_destination, photo_thumb_50 FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_50) = $row;



		$inp_name = "$get_my_user_name";
		$inp_image_path = "_uploads/users/images/$get_my_user_id";
		$inp_image_file = "$get_my_photo_destination";
		$inp_image_thumb_50x50 = "$get_my_photo_thumb_50";
	}
	elseif($get_current_session_play_as_user_group_team  == "team"){
		$query = "SELECT team_id, team_name FROM $t_rebus_teams_index WHERE team_id=$inp_team_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_team_id, $get_team_name) = $row;
		$inp_name = "$get_team_name";
	}
	elseif($get_current_session_play_as_user_group_team  == "group"){
		$query = "SELECT group_id, group_name FROM $t_rebus_groups_index WHERE group_id=$inp_group_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_group_id, $get_group_name) = $row;
		$inp_name = "$get_group_name";
	}
	$inp_name_mysql = quote_smart($link, $inp_name);
	$inp_image_path_mysql = quote_smart($link, $inp_image_path);
	$inp_image_file_mysql = quote_smart($link, $inp_image_file);
	$inp_image_thumb_50x50_mysql = quote_smart($link, $inp_image_thumb_50x50);

	// Ip 
	$my_ip = $_SERVER['REMOTE_ADDR'];
	$my_ip = output_html($my_ip);
	$my_ip_mysql = quote_smart($link, $my_ip);

	// Time
	$inp_seconds_used_mysql = quote_smart($link, $get_current_session_seconds_used);
	$inp_time_used_saying_mysql = quote_smart($link, $get_current_session_time_used_saying);

	// Points + seconds
	$points_loss_per_seconds = $get_current_session_seconds_used*0.001;
	$high_score_sum = $get_current_session_points-$points_loss_per_seconds;
	$high_score_sum = $high_score_sum*100;
	$high_score_sum = round($high_score_sum, 0);
	$high_score_sum_mysql = quote_smart($link, $high_score_sum);

	$high_score_sum_saying = number_format($high_score_sum);
	$high_score_sum_saying_mysql = quote_smart($link, $high_score_sum_saying);

	// Check if exits, if not, then insert
	$query = "SELECT high_score_id FROM $t_rebus_games_high_scores WHERE high_score_game_id=$get_current_game_id AND high_score_session_id=$get_current_session_id";

	if($get_current_session_play_as_user_group_team  == "user"){
		 $query = $query . " AND high_score_user_id=$my_user_id_mysql";
	}
	elseif($get_current_session_play_as_user_group_team  == "team"){
		 $query = $query . " AND high_score_team_id=$inp_team_id";
	}
	elseif($get_current_session_play_as_user_group_team  == "group"){
		 $query = $query . " AND high_score_group_id=$inp_group_id";
	}
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_high_score_id) = $row;
	if($get_high_score_id == ""){

		// Find my nearest place
		$query = "SELECT high_score_id, high_score_place FROM $t_rebus_games_high_scores WHERE high_score_game_id=$get_current_game_id AND high_score_sum > $high_score_sum_mysql LIMIT 0,1";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_near_high_score_id, $get_near_high_score_place) = $row;
		$inp_place = $get_near_high_score_place-1;

		mysqli_query($link, "INSERT INTO $t_rebus_games_high_scores
		(high_score_id, high_score_game_id, high_score_session_id, high_score_play_as_user_group_team, high_score_user_id, 
		high_score_team_id, high_score_group_id, high_score_name, high_score_image_path, high_score_image_file, 
		high_score_image_thumb_50x50, high_score_ip, high_score_created, high_score_created_saying, high_score_points, 
		high_score_seconds_used, high_score_sum, high_score_sum_saying, high_score_time_used_saying, high_score_place) 
		VALUES 
		(NULL, $get_current_game_id, $get_current_session_id, $inp_play_as_user_group_team_mysql, $my_user_id_mysql, 
		$inp_team_id, $inp_group_id, $inp_name_mysql, $inp_image_path_mysql, $inp_image_file_mysql, 
		$inp_image_thumb_50x50_mysql, $my_ip_mysql, '$datetime', '$date_saying', $get_current_session_points, 
		$inp_seconds_used_mysql, $high_score_sum_mysql, $high_score_sum_saying_mysql, $inp_time_used_saying_mysql, $inp_place)")
		or die(mysqli_error($link));

	}

	// Calculate game_finished_percentage, game_time_used_seconds, game_time_used_saying
	$inp_finished_percentage = ($get_current_game_times_finished/$get_current_game_times_played)*100;
	$inp_finished_percentage = round($inp_finished_percentage, 0);
	$inp_finished_percentage_mysql = quote_smart($link, $inp_finished_percentage);

	if($get_current_game_time_used_seconds == ""){
		$inp_time_used_seconds = "$get_current_session_seconds_used";
		$inp_time_used_saying = "$get_current_session_time_used_saying";
	}
	else{
		// Someone has played this before
		$query = "SELECT AVG(high_score_seconds_used) FROM $t_rebus_games_high_scores WHERE high_score_game_id=$get_current_game_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_avg_high_score_seconds_used) = $row;
		
		$inp_time_used_seconds = round($get_avg_high_score_seconds_used, 0);

		$time_used = seconds_to_time_array($inp_time_used_seconds);
		$time_used_array = explode("|", $time_used);
		$time_used_days = $time_used_array[0];
		$time_used_hours = $time_used_array[1];
		$time_used_minutes = $time_used_array[2];
		$time_used_seconds = $time_used_array[3];

		$inp_time_used_saying = "";
		if($time_used_days != "0"){
			// Days, hours, minutes
			$inp_time_used_saying = "$time_used_days $l_days_lowercase, $time_used_hours $l_hours_lowercase $l_and_lowercase $time_used_minutes $l_minutes_lowercase";
		}
		else{
			if($time_used_hours != "0"){
				// Hours, minutes
				if($time_used_hours == "1"){
					if($time_used_minutes == "1"){
						$inp_time_used_saying = "$time_used_hours $l_hour_lowercase $l_and_lowercase $time_used_minute $l_minutes_lowercase";
					}
					else{
						$inp_time_used_saying = "$time_used_hours $l_hour_lowercase $l_and_lowercase $time_used_minutes $l_minutes_lowercase";
					}
				}
				else{
					if($time_used_minutes == "1"){
						$inp_time_used_saying = "$time_used_hours $l_hours_lowercase $l_and_lowercase $time_used_minute $l_minutes_lowercase";
					}
					else{
						$inp_time_used_saying = "$time_used_hours $l_hours_lowercase $l_and_lowercase $time_used_minutes $l_minutes_lowercase";
					}
				}
			}
			else{
				// Minutes
				if($time_used_minutes == "1"){
					$inp_time_used_saying = "$time_used_minutes $l_minute_lowercase";
				}
				else{
					$inp_time_used_saying = "$time_used_minutes $l_minutes_lowercase";
				}
			}
		}
		$inp_time_used_saying = output_html($inp_time_used_saying);


	}
	$inp_time_used_seconds_mysql = quote_smart($link, $inp_time_used_seconds);
	$inp_time_used_saying_mysql = quote_smart($link, $inp_time_used_saying);

	mysqli_query($link, "UPDATE $t_rebus_games_index SET 
				game_finished_percentage=$inp_finished_percentage_mysql,
				game_time_used_seconds=$inp_time_used_seconds_mysql,
				game_time_used_saying=$inp_time_used_saying_mysql
				WHERE game_id=$get_current_game_id") or die(mysqli_error($link));



	// Finish
	$url = "play_game_7_finished.php?game_id=$get_current_game_id&session_id=$get_current_session_id&l=$l";
	header("Location: $url");
	exit;


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