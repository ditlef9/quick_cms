<?php
/**
*
* File: rebus/play_game_3_finnished.php
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
		<div class=\"in_game\">

			<!-- Headline -->
				<h1>$get_current_game_title</h1>
			<!-- //Headline -->

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
					<div class=\"time_used\"><span id=\"time_used_span\">$get_current_session_time_used_saying</span></div>
				<!-- //Time used -->
			</div>
			<!-- //Points, time used -->


			<!-- Congratulations -->
				<div class=\"clear\"></div>
				<p>$_congratulations!
				$l_you_got <b>$get_current_session_points ";
				if($get_current_session_points == "0" OR $get_current_session_points == "1"){ 
					echo" $l_point_lowercase";
				}
				else{
					echo" $l_points_lowercase";
				}
				echo"</b> 
				$l_and_you_solved_the_rebus_in 
				<b>$get_current_session_time_used_saying</b>. 
				</p>

				<p>
				<a href=\"index.php?l=$l\" class=\"btn_default\">$l_home</a>
				</p>
			<!-- //Congratulations -->
		</div>

		<!-- High Score -->
			<div class=\"in_game\">
				<h2>$l_high_score</h2>

				<table class=\"hor-zebra\">
				 <thead>
				  <tr>
				   <th>
				   </th>
				   <th>
					<span>$l_name</span>
				   </th>
				   <th>
					<span>$l_points</span>
				   </th>
				   <th>
					<span>$l_time</span>
				   </th>
				   <th>
					<span>$l_score</span>
				   </th>
				  </thead>
				  <tbody>\n";

				$x = 1;
				$query = "SELECT high_score_id, high_score_game_id, high_score_session_id, high_score_play_as_user_group_team, high_score_user_id, high_score_team_id, high_score_group_id, high_score_name, high_score_image_path, high_score_image_file, high_score_image_thumb_50x50, high_score_ip, high_score_created, high_score_created_saying, high_score_points, high_score_seconds_used, high_score_time_used_saying, high_score_sum, high_score_sum_saying, high_score_place FROM $t_rebus_games_high_scores WHERE high_score_game_id=$get_current_game_id ORDER BY high_score_sum ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_high_score_id, $get_high_score_game_id, $get_high_score_session_id, $get_high_score_play_as_user_group_team, $get_high_score_user_id, $get_high_score_team_id, $get_high_score_group_id, $get_high_score_name, $get_high_score_image_path, $get_high_score_image_file, $get_high_score_image_thumb_50x50, $get_high_score_ip, $get_high_score_created, $get_high_score_created_saying, $get_high_score_points, $get_high_score_seconds_used, $get_high_score_time_used_saying, $get_high_score_sum, $get_high_score_sum_saying, $get_high_score_place) = $row;

					if($x != "$get_high_score_place"){
						$get_high_score_place = "$x";
						mysqli_query($link, "UPDATE $t_rebus_games_high_scores SET high_score_place=$x WHERE high_score_id=$get_high_score_id") OR die(mysqli_error($link));
					}
					echo"
					  <tr>
					   <td"; if($get_high_score_session_id == "$get_current_session_id"){ echo" class=\"important\""; } echo">
						<span>$get_high_score_place</span>
					   </td>
					   <td"; if($get_high_score_session_id == "$get_current_session_id"){ echo" class=\"important\""; } echo">
						<span>$get_high_score_name</span>
					   </td>
					   <td"; if($get_high_score_session_id == "$get_current_session_id"){ echo" class=\"important\""; } echo">
						<span>$get_high_score_points</span>
				 	   </td>
					   <td"; if($get_high_score_session_id == "$get_current_session_id"){ echo" class=\"important\""; } echo">
						<span>$get_high_score_time_used_saying</span>
					   </td>
					   <td"; if($get_high_score_session_id == "$get_current_session_id"){ echo" class=\"important\""; } echo">
						<span>$get_high_score_sum_saying</span>
					   </td>
					";
					$x++;
				}
				echo"
				  </tbody>

				</table>


			</div>

		<!-- High Score  -->";
	} // action == ""
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