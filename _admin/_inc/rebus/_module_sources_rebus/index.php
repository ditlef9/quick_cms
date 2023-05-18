<?php
/**
*
* File: rebus/index.php
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

$filters_box = "";
if(isset($_GET['filters_box'])) {
	$filters_box = $_GET['filters_box'];
	$filters_box = output_html($filters_box);
	if($filters_box != "open"){
		echo"Unknown state of filters_box";
		die;
	}
}

$filter_location_box = "";
if(isset($_GET['filter_location_box'])) {
	$filter_location_box = $_GET['filter_location_box'];
	$filter_location_box = output_html($filter_location_box);
	if($filter_location_box != "open"){
		echo"Unknown state of filter_location_box";
		die;
	}
}

if(isset($_GET['countries'])) {
	$countries = $_GET['countries'];
	$countries = output_html($countries);
	if($countries != "" && !(is_numeric($countries))){
		echo"Countries not numeric";
		die;
	}
}
else{
	$countries = "";
}

if(isset($_GET['counties'])) {
	$counties = $_GET['counties'];
	$counties = output_html($counties);
	if($counties != "" && !(is_numeric($counties))){
		echo"Counties not numeric";
		die;
	}
}
else{
	$counties = "";
}
if(isset($_GET['municipalities'])) {
	$municipalities = $_GET['municipalities'];
	$municipalities = output_html($municipalities);
	if($municipalities != "" && !(is_numeric($municipalities))){
		echo"Municipalities not numeric";
		die;
	}
}
else{
	$municipalities = "";
}
if(isset($_GET['cities'])) {
	$cities = $_GET['cities'];
	$cities = output_html($cities);
	if($cities != "" && !(is_numeric($cities))){
		echo"Cities not numeric";
		die;
	}
}
else{
	$cities = "";
}
if(isset($_GET['sort_by'])) {
	$sort_by = $_GET['sort_by'];
	$sort_by = output_html($sort_by);	
}
else{
	$sort_by = "times_finished";
}
if(isset($_GET['fm'])) {
	$fm = $_GET['fm'];
	$fm = output_html($fm);	
}
else{
	$fm = "";
}

if(isset($_GET['latitude'])) {
	$latitude = $_GET['latitude'];
	$latitude = output_html($latitude);
	if(!(is_numeric($latitude))){
		echo"latitude not numeric";
		die;
	}
}
else{
	$latitude = "";
}
if(isset($_GET['longitude'])) {
	$longitude = $_GET['longitude'];
	$longitude = output_html($longitude);
	if(!(is_numeric($longitude))){
		echo"longitude not numeric";
		die;
	}
}
else{
	$longitude = "";
}


/*- Translation ------------------------------------------------------------------------ */


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_rebus";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
$main_class = "no_bg";
include("$root/_webdesign/header.php");


echo"
<!-- Headline -->
	<h1>$l_rebus</h1>
<!-- //Headline -->

<!-- Feedback -->
	";
	if($ft != "" && $fm != ""){
		$fm = ucfirst($fm);
		$fm = str_replace("_", " ", $fm);
		echo"<div class=\"$ft\" id=\"feedback_type\"><p id=\"feedback_text\">$fm</p></div>";
	}
	else{
		echo"<div id=\"feedback_type\"><p id=\"feedback_text\"></p></div>\n";
	}
	echo"
<!-- //Feedback -->



<!-- Actions and Sorting -->
	<p>
	<a href=\"create_game_step_1_title.php?l=$l\" class=\"btn_default\">$l_create_game</a>
	<a href=\"my_games.php?l=$l\" class=\"btn_default\">$l_my_games</a>
	<a href=\"groups.php?l=$l\" class=\"btn_default\">$l_groups</a>
	<a href=\"teams.php?l=$l\" class=\"btn_default\">$l_teams</a>
	</p>
<!-- //Actions and Sorting -->


<!-- Team and group inviations -->";
	if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
		$my_user_id = $_SESSION['user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);


		// My user
		$query = "SELECT user_id, user_email, user_name, user_rank, user_notes FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_rank, $get_my_user_notes) = $row;
		if($get_my_user_notes == "can_change_username"){
			echo"
			<section>
				<p>
				$l_hello $get_my_user_name,<br />
				$l_your_username_is_automatically_generated.
				$l_you_may_change_it.
				</p>
				<p>
				<a href=\"change_username.php?l=$l\" class=\"btn_default\">$l_change_username</a>
				</p>
			</section>
			";
		}


		// Get teams where I am member but hasnt accepted
		$count_invitations = 0;
		$query = "SELECT member_id, member_team_id, team_name FROM $t_rebus_teams_members JOIN $t_rebus_teams_index ON $t_rebus_teams_members.member_team_id=$t_rebus_teams_index.team_id WHERE member_user_id=$my_user_id_mysql AND member_invited=1 AND member_user_accepted_invitation=0 ORDER BY $t_rebus_teams_index.team_name ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_member_id, $get_member_team_id, $get_team_name) = $row;

			if($count_invitations == "0"){
				echo"
				<!-- Team invitaions -->
					<section>

						<h2>$l_team_invitations</h2>

						<p>$l_you_are_invitited_to_the_join_the_following_teams</p>
						<table>
				";
			}
			echo"
						 <tr>
						  <td>
							<p><a href=\"team_open.php?team_id=$get_member_team_id&amp;l=$l\">$get_team_name</a></p>
						  </td>
						  <td>
							<p>
							<a href=\"team_accept_invitation.php?team_id=$get_member_team_id&amp;l=$l\" class=\"btn_default\"><img src=\"_gfx/checked.png\" alt=\"checked.png\" /> $l_accept</a>
							<a href=\"team_decline_invitation.php?team_id=$get_member_team_id&amp;l=$l\" class=\"btn_default\"><img src=\"_gfx/decline.png\" alt=\"decline.png\" /> $l_decline</a>
							</p>
						  </td>
						 </tr>
			";
			$count_invitations++;
		}
		if($count_invitations > 0){
			echo"
						</table>
					</section>
				<!-- //Team invitaions -->
			";
		}



		// Get groups where I am member but hasnt accepted
		$count_invitations = 0;
		$query = "SELECT member_id, member_group_id, group_name FROM $t_rebus_groups_members";
		$query = $query . " JOIN $t_rebus_groups_index ON $t_rebus_groups_members.member_group_id=$t_rebus_groups_index.group_id";
		$query = $query . " WHERE member_user_id=$my_user_id_mysql AND member_invited=1 AND member_user_accepted_invitation=0";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_member_id, $get_member_group_id, $get_group_name) = $row;

			if($count_invitations == "0"){
				echo"
				<!-- Team invitaions -->
					<section>

						<h2>$l_group_invitations</h2>

						<p>$l_you_are_invitited_to_the_join_the_following_groups</p>
						<table>
				";
			}
			echo"
						 <tr>
						  <td>
							<p><a href=\"group_open.php?group_id=$get_member_group_id&amp;l=$l\">$get_group_name</a></p>
						  </td>
						  <td>
							<p>
							<a href=\"group_accept_invitation.php?group_id=$get_member_group_id&amp;l=$l\" class=\"btn_default\"><img src=\"_gfx/checked.png\" alt=\"checked.png\" /> $l_accept</a>
							<a href=\"group_decline_invitation.php?group_id=$get_member_group_id&amp;l=$l\" class=\"btn_default\"><img src=\"_gfx/decline.png\" alt=\"decline.png\" /> $l_decline</a>
							</p>
						  </td>
						 </tr>
			";
			$count_invitations++;
		}
		if($count_invitations > 0){
			echo"
						</table>
					</section>
				<!-- //Group invitaions -->
			";
		}

		// Get single games where I am invited
		$count_invitations = 0;
		$query = "SELECT invited_player_id, invited_player_game_id, invited_player_game_title FROM $t_rebus_games_invited_players";
		$query = $query . " WHERE invited_player_user_id=$my_user_id_mysql AND invited_player_invited=1 AND invited_player_user_accepted_invitation=0";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_invited_player_id, $get_invited_player_game_id, $get_invited_player_game_title) = $row;

			if($count_invitations == "0"){
				echo"
				<!-- Team invitaions -->
					<section>

						<h2>$l_game_invitations</h2>

						<p>$l_you_are_invitited_to_the_join_the_following_games</p>
						<table>
				";
			}
			echo"
						 <tr>
						  <td>
							<p><a href=\"play_game.php?game_id=$get_invited_player_game_id&amp;l=$l\">$get_invited_player_game_title</a></p>
						  </td>
						  <td>
							<p>
							<a href=\"game_accept_invitation.php?game_id=$get_invited_player_game_id&amp;l=$l\" class=\"btn_default\"><img src=\"_gfx/checked.png\" alt=\"checked.png\" /> $l_accept</a>
							<a href=\"game_decline_invitation.php?game_id=$get_invited_player_game_id&amp;l=$l\" class=\"btn_default\"><img src=\"_gfx/decline.png\" alt=\"decline.png\" /> $l_decline</a>
							</p>
						  </td>
						 </tr>
			";
			$count_invitations++;
		}
		if($count_invitations > 0){
			echo"
						</table>
					</section>
				<!-- //Group invitaions -->
			";
		}
		


	} // logged in
	echo"
<!-- //Team and group inviations -->

<!-- Check games my teams are playing so I can join them -->
	";
	if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
		$my_user_id = $_SESSION['user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);


		// Get teams
		$count_sessions = 0;
		$query = "SELECT member_id, member_team_id, team_name FROM $t_rebus_teams_members JOIN $t_rebus_teams_index ON $t_rebus_teams_members.member_team_id=$t_rebus_teams_index.team_id WHERE member_user_id=$my_user_id_mysql AND member_user_accepted_invitation=1 ORDER BY $t_rebus_teams_index.team_name ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_member_id, $get_member_team_id, $get_team_name) = $row;

			// Find games
			$query_g = "SELECT session_id, session_game_id, session_play_as_user_group_team, session_user_id, session_group_id, session_team_id, session_start_datetime, session_start_time, session_is_on_assignment_number, session_is_finished, session_finished_datetime, session_finished_time, session_seconds_used, session_time_used_saying FROM $t_rebus_games_sessions_index WHERE session_play_as_user_group_team='team' AND session_team_id=$get_member_team_id AND session_is_finished=0 ORDER BY session_id DESC";
			$result_g = mysqli_query($link, $query_g);
			while($row_g = mysqli_fetch_row($result_g)) {
				list($get_session_id, $get_session_game_id, $get_session_play_as_user_group_team, $get_session_user_id, $get_session_group_id, $get_session_team_id, $get_session_start_datetime, $get_session_start_time, $get_session_is_on_assignment_number, $get_session_is_finished, $get_session_finished_datetime, $get_session_finished_time, $get_session_seconds_used, $get_session_time_used_saying) = $row_g;
			

				// Game name
				$query = "SELECT game_id, game_title FROM $t_rebus_games_index WHERE game_id=$get_session_game_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_game_id, $get_game_title) = $row;


				// How old? 
				$how_old_seconds = $time-$get_session_start_time;
				$how_old_days = round($how_old_seconds/86400, 0);

				if($count_sessions == "0"){
					echo"
					<!-- Game sessoions -->
						<section>

							<h2>$l_games_your_team_are_playing</h2>

					";
				}
				echo"

							<p style=\"padding-bottom: 4px;margin-bottom:0px;\">
							<a href=\"play_game.php?game_id=$get_member_team_id\" style=\"font-weight: bold;\">$get_game_title</a><br />
							<a href=\"team_open.php?team_id=$get_member_team_id\">$get_team_name</a><br />
							$how_old_days ";
							if($how_old_days == "1"){
								echo"$l_day_ago_lowercase";
							}
							else{
								echo"$l_days_ago_lowercase";
							}
							echo"
							</p>
							
							<p style=\"padding-top: 0px;margin-top:0px;\">
							<a href=\"play_game_3_assignments.php?game_id=$get_session_game_id&amp;session_id=$get_session_id&amp;l=$l\" class=\"btn_default\"><img src=\"_gfx/checked.png\" alt=\"checked.png\" /> $l_join</a>
							";
							if($how_old_days > 1){
								echo"
								<a href=\"remove_game_session.php?session_id=$get_session_id&amp;l=$l\" class=\"btn_default\"><img src=\"_gfx/decline.png\" alt=\"decline.png\" /> $l_remove</a>
								";
							}
							echo"</p>
				";
				$count_sessions++;
			} // game sessions
			if($count_sessions > 0){
				echo"
						</section>
					<!-- //Game sessoions -->
				";
			}
		} // while teams

		// My sessions
		$count_sessions = 0;
		$prev_session_game_id = -1;
		$query_g = "SELECT session_id, session_game_id, session_play_as_user_group_team, session_user_id, session_group_id, session_team_id, session_start_datetime, session_start_time, session_is_on_assignment_number, session_is_finished, session_finished_datetime, session_finished_time, session_seconds_used, session_time_used_saying FROM $t_rebus_games_sessions_index WHERE session_play_as_user_group_team='user' AND session_user_id=$my_user_id_mysql AND session_is_finished=0 ORDER BY session_id DESC";
		$result_g = mysqli_query($link, $query_g);
		while($row_g = mysqli_fetch_row($result_g)) {
			list($get_session_id, $get_session_game_id, $get_session_play_as_user_group_team, $get_session_user_id, $get_session_group_id, $get_session_team_id, $get_session_start_datetime, $get_session_start_time, $get_session_is_on_assignment_number, $get_session_is_finished, $get_session_finished_datetime, $get_session_finished_time, $get_session_seconds_used, $get_session_time_used_saying) = $row_g;
			
			// Game name
			$query = "SELECT game_id, game_title FROM $t_rebus_games_index WHERE game_id=$get_session_game_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_game_id, $get_game_title) = $row;

			// How old? 
			$how_old_seconds = $time-$get_session_start_time;
			$how_old_days = round($how_old_seconds/86400, 0);

			if($count_sessions == "0"){
				echo"
				<!-- My game sessoions -->
					<section>
						<h2>$l_continue_your_game</h2>
						
					";
			}
			if($prev_session_game_id == "$get_session_game_id"){
				mysqli_query($link, "DELETE FROM $t_rebus_games_sessions_index WHERE session_id=$get_session_id") or die(mysqli_error($link));
				mysqli_query($link, "DELETE FROM $t_rebus_games_sessions_answers WHERE answer_session_id=$get_session_id") or die(mysqli_error($link));
			}
			else{
				echo"
						<p style=\"padding-bottom: 4px;margin-bottom:0px;\">
						<a href=\"play_game_3_assignments.php?game_id=$get_session_game_id&amp;session_id=$get_session_id&amp;l=$l\" style=\"font-weight: bold;\">$get_game_title</a><br />
						$how_old_days ";
						if($how_old_days == "1"){
							echo"$l_day_ago_lowercase";
						}
						else{
							echo"$l_days_ago_lowercase";
						}
						echo"
						</p>
						<p style=\"padding-top: 0px;margin-top:0px;\">
						<a href=\"play_game_3_assignments.php?game_id=$get_session_game_id&amp;session_id=$get_session_id&amp;l=$l\" class=\"btn_default\"><img src=\"_gfx/checked.png\" alt=\"checked.png\" /> $l_continue</a>
						<a href=\"remove_game_session.php?session_id=$get_session_id&amp;process=1&amp;l=$l\" class=\"btn_default\"><img src=\"_gfx/decline.png\" alt=\"decline.png\" /> $l_remove</a>
						</p>
				";
			}

			// Transfer
			$prev_session_game_id = $get_session_game_id;
			$count_sessions++;
		} // game sessions
		if($count_sessions > 0){
			echo"
					</section>
				<!-- //My game sessoions -->
			";
		}


	} // logged in
	echo"
<!-- //Check games my teams are playing so I can join them -->

<!-- Filters and sorint -->
	<div class=\"filter_and_sort_by\">


		<!-- Javascript on click activate_filter -->
			<script type=\"text/javascript\">
			\$(function() {
				\$('.activate_filter').click(function() {
					var divclass = \$(this).data('divclass');
            				$(\".\" + divclass).toggle();
      					return false;
				});
				\$('.activate_sort_by').click(function() {
					var divclass = \$(this).data('divclass');
            				$(\".\" + divclass).toggle();
      					return false;
				});
				\$('.activate_find_games_nearby').click(function() {
					// Get updated location
					if (navigator.geolocation) {
						navigator.geolocation.getCurrentPosition(geoSucc, geoErr, geoOpt);
					} else {
						\$(\"#feedback_text\").append('Geolocation is not supported by this browser.');
						\$(\"#feedback_type\").show();
						\$(\"#feedback_type\").removeClass();
						\$(\"#feedback_type\").addClass(\"error\");
					}
				
				});

				function geoSucc(position) {
					var latitude = position.coords.latitude;
					var longitude = position.coords.longitude;
					var url = \"index.php?l=$l&latitude=\" + latitude + \"&longitude=\" + longitude
					window.location.href = url;	
				}
				function geoErr(error) {
					switch(error.code) {
						case error.PERMISSION_DENIED:
							";
							if($fm != ""){
								echo"\$(\"#feedback_text\").append(\".<br />\");\n";
							}
							echo"
							\$(\"#feedback_text\").append(\"$l_user_denied_request_for_geolocation.\");
							\$(\"#feedback_type\").show();
							\$(\"#feedback_type\").removeClass();
							\$(\"#feedback_type\").addClass(\"error\");
							break;
						case error.POSITION_UNAVAILABLE:
							";
							if($fm != ""){
								echo"\$(\"#feedback_text\").append(\".<br />\");\n";
							}
							echo"
							\$(\"#feedback_text\").append(\"$l_location_information_is_unavailable.\");
							\$(\"#feedback_type\").show();
							\$(\"#feedback_type\").removeClass();
							\$(\"#feedback_type\").addClass(\"error\");
							break;
						case error.TIMEOUT:
							";
							if($fm != ""){
								echo"\$(\"#feedback_text\").append(\".<br />\");\n";
							}
							echo"
							\$(\"#feedback_text\").append(\"$l_the_request_to_get_user_location_timed_out.\");
							\$(\"#feedback_type\").show();
							\$(\"#feedback_type\").removeClass();
							\$(\"#feedback_type\").addClass(\"error\");
							break;
						case error.UNKNOWN_ERROR:
							";
							if($fm != ""){
								echo"\$(\"#feedback_text\").append(\".<br />\");\n";
							}
							echo"
							\$(\"#feedback_text\").append(\"$l_an_unknown_error_occurred.\");
							\$(\"#feedback_type\").show();
							\$(\"#feedback_type\").removeClass();
							\$(\"#feedback_type\").addClass(\"error\");
							break;
					}

				}
				var geoOpt = {
					timeout: 3000,
					maximumAge: 30
				}

			});
			</script>
		<!-- //Javascript on click activate_filter-->

		<ul>
			<li><a href=\"#\" class=\"activate_find_games_nearby\"";
			if($latitude != "" && $longitude != ""){
				echo" style=\"background: url('_gfx/my_location_outline_black_24x24.png') no-repeat left center #fff;\"";
			}
			echo">$l_find_games_nearby</a></li>
		</ul>
		<ul>
			<li><a href=\"#\" class=\"activate_filter\" data-divclass=\"filters_box\">$l_filter</a></li>
			<li><a href=\"#\" class=\"activate_sort_by\" data-divclass=\"sort_by_box\">$l_sort_by: ";
			if($sort_by == "times_finished"){
				echo" $l_popularity";
			}
			elseif($sort_by == "title"){
				echo" $l_alphabetical";
			}
			elseif($sort_by == "updated_datetime"){
				echo" $l_newest";
			}
			else{
				echo" ??";
				die;
			}
			echo"</a></li>
		</ul>
	</div>


	<!-- Filters -->
		<div class=\"filters_box\""; if($filters_box  == "open"){ echo" style=\"display: block\""; } echo">
			<!-- Javascript on click filter close or open corresponding div -->
				<script type=\"text/javascript\">
				\$(function() {
					\$('.expand_or_close_filter').click(function() {
						// Close or open div
						var divid = \$(this).data('divid');
            					$(\"#\" + divid).toggle();

						// Change icon
						\$(this).find(\"img\").attr(\"src\", function() {
							var src = \$(this).attr('src');
							var newsrc = (src=='_gfx/expand_more_black_18x18.png') ? '_gfx/close_black_18x18.png' : '_gfx/expand_more_black_18x18.png';
							\$(this).attr('src', newsrc );
						});
            					return false;
       					});
    				});
				</script>
			<!-- //Javascript on click filter close or open corresponding div -->



			<!-- Javascript on click go to url -->
				<script type=\"text/javascript\">
				\$(function() {
					\$(\".onclick_go_to_url\").change(function(){
						var item=\$(this);
						if(item.is(\":checked\")){
							window.location.href= item.data(\"target\")
						}
					});
				});
				</script>
			<!-- //Javascript on click go to url -->

			<!-- Filter language -->
				<p>
				<a href=\"#\" class=\"expand_or_close_filter\" data-divid=\"filter_language\">$l_language <img src=\"_gfx/expand_more_black_18x18.png\" alt=\"expand_more_black_24x24.png\" /></a>
				</p>
				<div class=\"filter_inside\" id=\"filter_language\">
					<p>\n";
					$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag_path_16x16, language_active_flag_16x16, language_active_default FROM $t_languages_active";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag_path_16x16, $get_language_active_flag_16x16, $get_language_active_default) = $row;
						echo"
						<a href=\"index.php?l=$get_language_active_iso_two&amp;filters_box=open\"><img src=\"$root/$get_language_active_flag_path_16x16/$get_language_active_flag_16x16\" alt=\"$get_language_active_flag_16x16\" /></a>
						<a href=\"index.php?l=$get_language_active_iso_two&amp;filters_box=open\"";
						if($get_language_active_iso_two == "$l"){
							echo" class=\"filter_active\"";
						} 
						echo">$get_language_active_name</a><br />\n";
					}
					echo"
					</p>
				</div>
			<!-- //Filter language -->

			<!-- Filter country -->
				<p>
				<a href=\"#\" class=\"expand_or_close_filter\" data-divid=\"filter_location\">$l_location <img src=\"_gfx/expand_more_black_18x18.png\" alt=\"expand_more_black_24x24.png\" /></a>
				</p>
				<div class=\"filter_inside\" id=\"filter_location\" "; if($filter_location_box  == "open"){ echo" style=\"display: block\""; } echo">
					<ul>\n";
					$query = "SELECT country_id, country_name, country_flag_path_16x16, country_flag_16x16 FROM $t_rebus_games_geo_countries ORDER BY country_name ASC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_country_id, $get_country_name, $get_country_flag_path_16x16, $get_country_flag_16x16) = $row;
						echo"
						<li><span>
							<a id=\"country$get_country_id\"></a>
							<input type=\"radio\" name=\"filter_country_$get_country_id\" class=\"onclick_go_to_url\" data-target=\"index.php?l=$l&amp;countries=$get_country_id&amp;sort_by=$sort_by&amp;filters_box=open&amp;filter_location_box=open#country$get_country_id\""; if($countries == "$get_country_id"){ echo" checked=\"checked\""; } echo" />
							<a href=\"index.php?l=$l&amp;countries=$get_country_id&amp;sort_by=$sort_by&amp;filters_box=open&amp;filter_location_box=open#country$get_country_id\"><img src=\"$root/$get_country_flag_path_16x16/$get_country_flag_16x16\" alt=\"$get_country_flag_16x16\" /></a>
							<a href=\"index.php?l=$l&amp;countries=$get_country_id&amp;sort_by=$sort_by&amp;filters_box=open&amp;filter_location_box=open#country$get_country_id\""; if($countries == "$get_country_id"){ echo" style=\"font-weight:bold;\""; } echo">$get_country_name</a>
						</span></li>\n";

						// Counties
						if($get_country_id == "$countries"){
							$query_c = "SELECT county_id, county_name FROM $t_rebus_games_geo_counties WHERE county_country_id=$get_country_id ORDER BY county_name ASC";
							$result_c = mysqli_query($link, $query_c);
							while($row_c = mysqli_fetch_row($result_c)) {
								list($get_county_id, $get_county_name) = $row_c;
								echo"
								<li style=\"padding-left: 10px;\"><span>
								<a id=\"county$get_county_id\"></a>
								<input type=\"radio\" name=\"filter_county\" class=\"onclick_go_to_url\" data-target=\"index.php?l=$l&amp;countries=$countries&amp;counties=$get_county_id&amp;sort_by=$sort_by&amp;filters_box=open&amp;filter_location_box=open#country$get_country_id\""; if($counties == "$get_county_id"){ echo" checked=\"checked\""; } echo" />
								<a href=\"index.php?l=$l&amp;countries=$countries&amp;counties=$get_county_id&amp;sort_by=$sort_by&amp;filters_box=open&amp;filter_location_box=open#county$get_county_id\""; if($counties == "$get_county_id"){ echo" style=\"font-weight:bold;\""; } echo">$get_county_name</a>
								</span></li>\n";



								// Municipalities
								if($get_county_id == "$counties"){
									$query_m = "SELECT municipality_id, municipality_name FROM $t_rebus_games_geo_municipalities WHERE municipality_county_id=$get_county_id ORDER BY municipality_name ASC";
									$result_m = mysqli_query($link, $query_m);
									while($row_m = mysqli_fetch_row($result_m)) {
										list($get_municipality_id, $get_municipality_name) = $row_m;
										echo"
										<li style=\"padding-left: 20px;\"><span>
										<a id=\"municipality$get_municipality_id\"></a>
										<input type=\"radio\" name=\"filter_municipality\" class=\"onclick_go_to_url\" data-target=\"index.php?l=$l&amp;countries=$countries&amp;counties=$counties&amp;municipalities=$get_municipality_id&amp;sort_by=$sort_by&amp;filters_box=open&amp;filter_location_box=open#municipality$get_municipality_id\""; if($get_municipality_id == "$municipalities"){ echo" checked=\"checked\""; } echo" />
										<a href=\"index.php?l=$l&amp;countries=$countries&amp;counties=$counties&amp;municipalities=$get_municipality_id&amp;sort_by=$sort_by&amp;filters_box=open&amp;filter_location_box=open#municipality$get_municipality_id\""; if($get_municipality_id == "$municipalities"){ echo" style=\"font-weight:bold;\""; } echo">$get_municipality_name</a>
										</span></li>\n";


										// Municipalities
										if($get_municipality_id == "$municipalities"){
											$query_ci = "SELECT city_id, city_name FROM $t_rebus_games_geo_cities WHERE city_municipality_id=$get_municipality_id ORDER BY city_name ASC";
											$result_ci = mysqli_query($link, $query_ci);
											while($row_ci = mysqli_fetch_row($result_ci)) {
												list($get_city_id, $get_city_name) = $row_ci;
												echo"
												<li style=\"padding-left: 30px;\"><span>
												<a id=\"city$get_city_id\"></a>
												<input type=\"radio\" name=\"filter_city\" class=\"onclick_go_to_url\" data-target=\"index.php?l=$l&amp;countries=$countries&amp;counties=$counties&amp;municipalities=$municipalities&amp;cities=$get_city_id&amp;sort_by=$sort_by&amp;filters_box=open&amp;filter_location_box=open#city$get_city_id\""; if($get_city_id == "$cities"){ echo" checked=\"checked\""; } echo" />
												<a href=\"index.php?l=$l&amp;countries=$countries&amp;counties=$counties&amp;municipalities=$municipalities&amp;cities=$get_city_id&amp;sort_by=$sort_by&amp;filters_box=open&amp;filter_location_box=open#city$get_city_id\""; if($get_city_id == "$cities"){ echo" style=\"font-weight:bold;\""; } echo">$get_city_name</a>
												</span></li>\n";
											} // cities
										} // municipality open
		


									} // municipalities
								} // country open


							} // counties
						} // country open
					} // while countries
					echo"
					</ul>
				</div>
			<!-- //Filter country -->
		</div>
	<!-- //Filters -->

	<!-- Sort by -->
		<div class=\"sort_by_box\">
			<ul>
				";
				$url = "index.php?l=$l";
				if($countries != ""){ $url = $url . "&amp;countries=$countries"; }
				if($counties != ""){ $url = $url . "&amp;counties=$counties"; }
				if($municipalities != ""){ $url = $url . "&amp;municipalities=$municipalities"; }
				if($cities != ""){ $url = $url . "&amp;cities=$cities"; } 
				if($latitude != "" && $longitude != ""){  $url = $url . "&amp;latitude=$latitude&amp;longitude=$longitude"; } 
				$url = $url . "&amp;sort_by=times_finished";
				echo"<li><a href=\"$url\""; if($sort_by == "times_finished"){ echo" class=\"active\""; } echo">
				<input type=\"radio\" name=\"sort_by\" class=\"onclick_go_to_url\" data-target=\"$url\""; if($sort_by == "times_finished"){ echo" checked=\"checked\""; } echo" />
				$l_popularity</a></li>

				";
				$url = "index.php?l=$l";
				if($countries != ""){ $url = $url . "&amp;countries=$countries"; }
				if($counties != ""){ $url = $url . "&amp;counties=$counties"; }
				if($municipalities != ""){ $url = $url . "&amp;municipalities=$municipalities"; }
				if($cities != ""){ $url = $url . "&amp;cities=$cities"; } 
				if($latitude != "" && $longitude != ""){  $url = $url . "&amp;latitude=$latitude&amp;longitude=$longitude"; } 
				$url = $url . "&amp;sort_by=title";
				echo"<li><a href=\"$url\""; if($sort_by == "title"){ echo" class=\"active\""; } echo">
				<input type=\"radio\" name=\"sort_by\" class=\"onclick_go_to_url\" data-target=\"$url\""; if($sort_by == "title"){ echo" checked=\"checked\""; } echo" />
				$l_alphabetical</a></li>

				";
				$url = "index.php?l=$l";
				if($countries != ""){ $url = $url . "&amp;countries=$countries"; }
				if($counties != ""){ $url = $url . "&amp;counties=$counties"; }
				if($municipalities != ""){ $url = $url . "&amp;municipalities=$municipalities"; }
				if($cities != ""){ $url = $url . "&amp;cities=$cities"; } 
				if($latitude != "" && $longitude != ""){  $url = $url . "&amp;latitude=$latitude&amp;longitude=$longitude"; } 
				$url = $url . "&amp;sort_by=updated_datetime";
				echo"<li><a href=\"$url\""; if($sort_by == "updated_datetime"){ echo" class=\"active\""; } echo">
				<input type=\"radio\" name=\"sort_by\" class=\"onclick_go_to_url\" data-target=\"$url\""; if($sort_by == "updated_datetime"){ echo" checked=\"checked\""; } echo" />
				$l_newest</a></li>
			</ul>
		</div>
	<!-- //Sort by -->


<!-- //Filters and sorting -->

<!-- Games -->

	<div class=\"games_row\">
	";
	// Games using lat long
	if($latitude != "" && $longitude != ""){
		// Calculate all games according to my lat and long
		$latitude_mysql = quote_smart($link, $latitude);
		$longitude_mysql = quote_smart($link, $longitude);
		$query = "SELECT count(measurement_id) FROM $t_rebus_games_index_geo_distance_measurements WHERE measurement_from_latitude=$latitude_mysql AND measurement_from_longitude=$longitude_mysql AND measurement_game_language=$l_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_count_measurement_id) = $row;

		// Calculate all games according to my lat and long
		if($get_count_measurement_id == "0"){
			$year = date("Y");
			$query = "SELECT game_id, game_title, game_latitude, game_longitude FROM $t_rebus_games_index WHERE game_language=$l_mysql AND game_privacy='public' AND game_published=1";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_game_id, $get_game_title, $get_game_latitude, $get_game_longitude) = $row;

				if($get_game_latitude == ""){
					echo"<div class=\"info\"><p>Game <a href=\"edit_game_location.php?game_id=$get_game_id&amp;l=$l\">$get_game_title</a> missing latitude/longitude.</p></div>\n";
				}
				else{
					$distance_km = getDistance($latitude, $longitude, $get_game_latitude, $get_game_longitude);
					$distance_m = $distance_km*1000;
					$distance_m = round($distance_m, 0);
					$distance_km = round($distance_km, 0);

					if($distance_m != "0"){
						// Insert
						$inp_measurement_game_latitude_mysql = quote_smart($link, $get_game_latitude);
						$inp_measurement_game_longitude_mysql = quote_smart($link, $get_game_longitude);
						$inp_measurement_distance_meters_mysql = quote_smart($link, $distance_m);

						// Distance metric saying
						$inp_measurement_distance_metric_saying = "";
						if($distance_m > 1000){
							$inp_measurement_distance_metric_saying = "$distance_km km";
						}
						else{
							$inp_measurement_distance_metric_saying = "$distance_m m";
						}
						$inp_measurement_distance_metric_saying_mysql = quote_smart($link, $inp_measurement_distance_metric_saying);

						// Distance imperial saying
						$inp_measurement_distance_imperial_saying = "";
						$inp_measurement_distance_imperial_saying = round($distance_km/0.62137);
						$inp_measurement_distance_imperial_saying = "$inp_measurement_distance_imperial_saying mi";
						$inp_measurement_distance_imperial_saying_mysql = quote_smart($link, $inp_measurement_distance_imperial_saying);


						mysqli_query($link, "INSERT INTO $t_rebus_games_index_geo_distance_measurements 
						(measurement_id, measurement_from_latitude, measurement_from_longitude, measurement_game_id, measurement_game_language, 
						measurement_game_latitude, measurement_game_longitude, measurement_distance_meters, measurement_distance_metric_saying, measurement_distance_imperial_saying, 
						measurement_updated_year) 
						VALUES 
						(NULL, $latitude_mysql, $longitude_mysql, $get_game_id, $l_mysql,
						$inp_measurement_game_latitude_mysql, $inp_measurement_game_longitude_mysql, $inp_measurement_distance_meters_mysql, $inp_measurement_distance_metric_saying_mysql, $inp_measurement_distance_imperial_saying_mysql, 
						$year)")
						or die(mysqli_error($link));
					}
				} // game has latitude
			} // while all games
		} // new calculation
		// Games (near me)
		$query = "SELECT measurement_id, measurement_game_id, measurement_distance_metric_saying, measurement_distance_imperial_saying, game_id, game_title, game_language, game_introduction, game_description, game_privacy, game_difficulty, game_published, game_playable_after_datetime, game_playable_after_time, game_group_id, game_group_name, game_times_played, game_times_finished, game_finished_percentage, game_time_used_saying, game_image_path, game_image_file, game_image_thumb_570x380, game_image_thumb_278x156, game_country_id, game_country_name, game_county_id, game_county_name, game_municipality_id, game_municipality_name, game_city_id, game_city_name, game_place_id, game_place_name FROM $t_rebus_games_index_geo_distance_measurements JOIN $t_rebus_games_index ON $t_rebus_games_index_geo_distance_measurements.measurement_game_id=$t_rebus_games_index.game_id WHERE measurement_from_latitude=$latitude_mysql AND measurement_from_longitude=$longitude_mysql AND measurement_game_language=$l_mysql ORDER BY measurement_distance_meters ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_measurement_id, $get_measurement_game_id, $get_measurement_distance_metric_saying, $get_measurement_distance_imperial_saying, $get_game_id, $get_game_title, $get_game_language, $get_game_introduction, $get_game_description, $get_game_privacy, $get_game_difficulty, $get_game_published, $get_game_playable_after_datetime, $get_game_playable_after_time, $get_game_group_id, $get_game_group_name, $get_game_times_played, $get_game_times_finished, $get_game_finished_percentage, $get_game_time_used_saying, $get_game_image_path, $get_game_image_file, $get_game_image_thumb_570x380, $get_game_image_thumb_278x156, $get_game_country_id, $get_game_country_name, $get_game_county_id, $get_game_county_name, $get_game_municipality_id, $get_game_municipality_name, $get_game_city_id, $get_game_city_name, $get_game_place_id, $get_game_place_name) = $row;


			if(file_exists("$root/$get_game_image_path/$get_game_image_file") && $get_game_image_file != ""){


				// Thumb
				if(!(file_exists("$root/$get_game_image_path/$get_game_image_thumb_570x380")) OR $get_game_image_thumb_570x380 == ""){
					$ext = get_extension($get_game_image_file);
					$org_file_name = str_replace(".$ext", "", $get_game_image_file);
				
					$inp_thumb = $org_file_name . "_thumb_570x380" . ".$ext";
					$inp_thumb_mysql = quote_smart($link, $inp_thumb);

					resize_crop_image(570, 380, "$root/$get_game_image_path/$get_game_image_file", "$root/$get_game_image_path/$inp_thumb");
					mysqli_query($link, "UPDATE $t_rebus_games_index SET game_image_thumb_570x380=$inp_thumb_mysql WHERE game_id=$get_game_id") or die(mysqli_error($link));

					// Transfer
					$get_game_image_thumb_570x380 = "$inp_thumb";
				}

	
				echo"
				<div class=\"games_column\">
					<div class=\"games_column_inner\">
						<p>
						<a href=\"$root/rebus/play_game.php?game_id=$get_game_id&amp;l=$get_game_language\"><img src=\"$root/$get_game_image_path/$get_game_image_thumb_570x380\" alt=\"$get_game_image_thumb_570x380\" /></a><br />
						<a href=\"$root/rebus/play_game.php?game_id=$get_game_id&amp;l=$get_game_language\" class=\"h2\">$get_game_title</a><br />
						
						<span class=\"grey\">$get_game_city_name</span>
						</p>

						<ul>
							<li><span class=\"grey\"><img src=\"_gfx/near_me_outline_grey_18x18.png\" alt=\"near_me_outline_black_18x18.png\" /> $get_measurement_distance_metric_saying</span></li>\n";
							if($get_game_difficulty == "tourist"){
								echo"							";
								echo"<li><span class=\"grey\"><img src=\"_gfx/travel_explore_outline_grey_18x18.png\" alt=\"travel_explore_outline_black_18x18.png\" /> $l_tourist</span></li>\n";
							}
							else{
								echo"							";
								echo"<li><span class=\"grey\"><img src=\"_gfx/wb_shade_outline_grey_18x18.png\" alt=\"wb_shade_outline_black_18x18.png\" /> $l_local</span></li>\n";
							}
							echo"							";
							echo"<li><span class=\"grey\"><img src=\"_gfx/games_outline_grey_18x18.png\" alt=\"games_outline_grey_18x18.png\" /> $get_game_times_played</span></li>
							<li><span class=\"grey\"><img src=\"_gfx/games_round_grey_18x18.png\" alt=\"games_round_grey_18x18.png\" /> $get_game_times_finished ($get_game_finished_percentage %)</span></li>
							<li><span class=\"grey\"><img src=\"_gfx/timer_grey_outline_18x18.png\" alt=\"timer_black_outline_18x18.png\" /> $get_game_time_used_saying</span></li>
						</ul>
					</div>
				</div>";
			} // has image
		} // games list near me

		echo"
	

		";
	} // games near me
	else{
		// Games
		$query = "SELECT game_id, game_title, game_language, game_introduction, game_description, game_privacy, game_difficulty, game_published, game_playable_after_datetime, game_playable_after_time, game_group_id, game_group_name, game_times_played, game_times_finished, game_finished_percentage, game_time_used_saying, game_image_path, game_image_file, game_image_thumb_570x380, game_image_thumb_278x156, game_country_id, game_country_name, game_county_id, game_county_name, game_municipality_id, game_municipality_name, game_city_id, game_city_name, game_place_id, game_place_name FROM $t_rebus_games_index WHERE game_language=$l_mysql AND game_privacy='public' AND game_published=1";

		if($countries != ""){
			$country_mysql = quote_smart($link, $countries);
			$query = $query . " AND game_country_id=$country_mysql";
		}
		if($counties != ""){
			$county_mysql = quote_smart($link, $counties);
			$query = $query . " AND game_county_id=$county_mysql";
		}
		if($municipalities != ""){
			$municipality_mysql = quote_smart($link, $municipalities);
			$query = $query . " AND game_municipality_id=$municipality_mysql";
		}
		if($cities != ""){
			$city_mysql = quote_smart($link, $cities);
			$query = $query . " AND game_city_id=$city_mysql";
		}

		if($sort_by == "title"){
			$query = $query . " ORDER BY game_title ASC";
		}
		elseif($sort_by == "updated_datetime"){
			$query = $query . " ORDER BY game_updated_datetime DESC";
		}
		else{
			$query = $query . " ORDER BY game_times_finished DESC";
		}

		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_game_id, $get_game_title, $get_game_language, $get_game_introduction, $get_game_description, $get_game_privacy, $get_game_difficulty, $get_game_published, $get_game_playable_after_datetime, $get_game_playable_after_time, $get_game_group_id, $get_game_group_name, $get_game_times_played, $get_game_times_finished, $get_game_finished_percentage, $get_game_time_used_saying, $get_game_image_path, $get_game_image_file, $get_game_image_thumb_570x380, $get_game_image_thumb_278x156, $get_game_country_id, $get_game_country_name, $get_game_county_id, $get_game_county_name, $get_game_municipality_id, $get_game_municipality_name, $get_game_city_id, $get_game_city_name, $get_game_place_id, $get_game_place_name) = $row;

			if(file_exists("$root/$get_game_image_path/$get_game_image_file") && $get_game_image_file != ""){


				// Thumb
				if(!(file_exists("$root/$get_game_image_path/$get_game_image_thumb_570x380")) OR $get_game_image_thumb_570x380 == ""){
					$ext = get_extension($get_game_image_file);
					$org_file_name = str_replace(".$ext", "", $get_game_image_file);
				
					$inp_thumb = $org_file_name . "_thumb_570x380" . ".$ext";
					$inp_thumb_mysql = quote_smart($link, $inp_thumb);

					resize_crop_image(570, 380, "$root/$get_game_image_path/$get_game_image_file", "$root/$get_game_image_path/$inp_thumb");
					mysqli_query($link, "UPDATE $t_rebus_games_index SET game_image_thumb_570x380=$inp_thumb_mysql WHERE game_id=$get_game_id") or die(mysqli_error($link));

					// Transfer
					$get_game_image_thumb_570x380 = "$inp_thumb";
				}

	
				echo"
				<div class=\"games_column\">
					<div class=\"games_column_inner\">
						<p>
						<a href=\"$root/rebus/play_game.php?game_id=$get_game_id&amp;l=$get_game_language\"><img src=\"$root/$get_game_image_path/$get_game_image_thumb_570x380\" alt=\"$get_game_image_thumb_570x380\" /></a><br />
						<a href=\"$root/rebus/play_game.php?game_id=$get_game_id&amp;l=$get_game_language\" class=\"h2\">$get_game_title</a><br />
						
						<span class=\"grey\">$get_game_city_name</span>
						</p>

						<ul>\n";
							if($get_game_difficulty == "tourist"){
								echo"							";
								echo"<li><span class=\"grey\"><img src=\"_gfx/travel_explore_outline_grey_18x18.png\" alt=\"travel_explore_outline_black_18x18.png\" /> $l_tourist</span></li>\n";
							}
							else{
								echo"							";
								echo"<li><span class=\"grey\"><img src=\"_gfx/wb_shade_outline_grey_18x18.png\" alt=\"wb_shade_outline_black_18x18.png\" /> $l_local</span></li>\n";
							}
							echo"							";
							echo"<li><span class=\"grey\"><img src=\"_gfx/games_outline_grey_18x18.png\" alt=\"games_outline_grey_18x18.png\" /> $get_game_times_played</span></li>
							<li><span class=\"grey\"><img src=\"_gfx/games_round_grey_18x18.png\" alt=\"games_round_grey_18x18.png\" /> $get_game_times_finished ($get_game_finished_percentage %)</span></li>
							<li><span class=\"grey\"><img src=\"_gfx/timer_grey_outline_18x18.png\" alt=\"timer_black_outline_18x18.png\" /> $get_game_time_used_saying</span></li>
						</ul>
					</div>
				</div>";
			} // has image
		} // games
	} // games not near me
	echo"
	</div> <!-- //games_row -->
<!-- //Games -->
";

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>