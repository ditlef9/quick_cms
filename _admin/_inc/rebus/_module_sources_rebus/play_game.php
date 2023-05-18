<?php
/**
*
* File: rebus/play_game.php
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

$tabindex = 0;


// Dates
$time = time();

/*- Find game ------------------------------------------------------------------------- */
$game_id_mysql = quote_smart($link, $game_id);
$query = "SELECT game_id, game_title, game_language, game_introduction, game_description, game_privacy, game_difficulty, game_age_limit, game_published, game_playable_after_datetime, game_playable_after_datetime_saying, game_playable_after_time, game_group_id, game_group_name, game_times_played, game_times_finished, game_finished_percentage, game_time_used_seconds, game_time_used_saying, game_image_path, game_image_file, game_image_thumb_278x156, game_image_thumb_570x321, game_image_thumb_570x380, game_country_id, game_country_name, game_county_id, game_county_name, game_municipality_id, game_municipality_name, game_city_id, game_city_name, game_place_id, game_place_name, game_place_latitude, game_place_longitude, game_latitude, game_longitude, game_number_of_assignments, game_rating, game_created_by_user_id, game_created_by_user_name, game_created_by_user_email, game_created_by_ip, game_created_by_hostname, game_created_by_user_agent, game_created_datetime, game_created_date_saying, game_updated_by_user_id, game_updated_by_user_name, game_updated_by_user_email, game_updated_by_ip, game_updated_by_hostname, game_updated_by_user_agent, game_updated_datetime, game_updated_date_saying FROM $t_rebus_games_index WHERE game_id=$game_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_game_id, $get_current_game_title, $get_current_game_language, $get_current_game_introduction, $get_current_game_description, $get_current_game_privacy, $get_current_game_difficulty, $get_current_game_age_limit, $get_current_game_published, $get_current_game_playable_after_datetime, $get_current_game_playable_after_datetime_saying, $get_current_game_playable_after_time, $get_current_game_group_id, $get_current_game_group_name, $get_current_game_times_played, $get_current_game_times_finished, $get_current_game_finished_percentage, $get_current_game_time_used_seconds, $get_current_game_time_used_saying, $get_current_game_image_path, $get_current_game_image_file, $get_current_game_image_thumb_278x156, $get_current_game_image_thumb_570x321, $get_current_game_image_thumb_570x380, $get_current_game_country_id, $get_current_game_country_name, $get_current_game_county_id, $get_current_game_county_name, $get_current_game_municipality_id, $get_current_game_municipality_name, $get_current_game_city_id, $get_current_game_city_name, $get_current_game_place_id, $get_current_game_place_name, $get_current_game_place_latitude, $get_current_game_place_longitude, $get_current_game_latitude, $get_current_game_longitude, $get_current_game_number_of_assignments, $get_current_game_rating, $get_current_game_created_by_user_id, $get_current_game_created_by_user_name, $get_current_game_created_by_user_email, $get_current_game_created_by_ip, $get_current_game_created_by_hostname, $get_current_game_created_by_user_agent, $get_current_game_created_datetime, $get_current_game_created_date_saying, $get_current_game_updated_by_user_id, $get_current_game_updated_by_user_name, $get_current_game_updated_by_user_email, $get_current_game_updated_by_ip, $get_current_game_updated_by_hostname, $get_current_game_updated_by_user_agent, $get_current_game_updated_datetime, $get_current_game_updated_date_saying) = $row;
if($get_current_game_id == ""){
	$url = "index.php?ft=error&fm=game_not_found&l=$l";
	header("Location: $url");
	exit;
}


// Me
$my_user_id = "";
$my_user_id_mysq = "";
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
}


// Is public?
$can_view_game = 0;
if($get_current_game_privacy == "public"){
	$can_view_game = 1;
}
elseif($get_current_game_privacy == "private"){
	
	// Logged in?
	if($my_user_id != ""){

		// Check that I am invited member
		$query = "SELECT invited_player_id, invited_player_game_id, invited_player_user_id, invited_player_user_name, invited_player_user_email, invited_player_user_photo_destination, invited_player_user_photo_thumb_50, invited_player_invited, invited_player_user_accepted_invitation, invited_player_accepted_by_moderator, invited_player_added_datetime, invited_player_added_date_saying, invited_player_last_played FROM $t_rebus_games_invited_players WHERE invited_player_game_id=$get_current_game_id AND invited_player_user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_invited_player_id, $get_current_invited_player_game_id, $get_current_invited_player_user_id, $get_current_invited_player_user_name, $get_current_invited_player_user_email, $get_current_invited_player_user_photo_destination, $get_current_invited_player_user_photo_thumb_50, $get_current_invited_player_invited, $get_current_invited_player_user_accepted_invitation, $get_current_invited_player_accepted_by_moderator, $get_current_invited_player_added_datetime, $get_current_invited_player_added_date_saying, $get_current_invited_player_last_played) = $row;
		if($get_current_invited_player_id != ""){
			if($get_current_invited_player_accepted_by_moderator == "1"){
				if($get_current_invited_player_user_accepted_invitation == "1"){
					$can_view_game = 1;
				}
				else{
					$can_view_game = 0;
					/*- Headers ---------------------------------------------------------------------------------- */
					$website_title = "$get_current_game_title";
					if(file_exists("./favicon.ico")){ $root = "."; }
					elseif(file_exists("../favicon.ico")){ $root = ".."; }
					elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
					elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
					include("$root/_webdesign/header.php");
					echo"

						<h1>$get_current_game_title</h1>

						<p>$l_you_are_invited_to_play_this_game.
						$l_do_you_want_to_accept_the_invitation
						</p>
						<p>
						<a href=\"game_accept_invitation.php?game_id=$get_current_game_id&amp;l=$l\" class=\"btn_default\"><img src=\"_gfx/checked.png\" alt=\"checked.png\" /> $l_accept</a>
						<a href=\"game_decline_invitation.php?game_id=$get_current_game_id&amp;l=$l\" class=\"btn_default\"><img src=\"_gfx/decline.png\" alt=\"decline.png\" /> $l_decline</a>
						</p>
					";
					include("$root/_webdesign/footer.php");
				}
			}
		}
		else{
			// Check that I am in a group of this game
			if($get_current_game_group_id != ""){
				$query = "SELECT member_id, member_group_id, member_user_id, member_user_name, member_user_email, member_user_photo_destination, member_user_photo_thumb_50, member_status, member_invited, member_user_accepted_invitation, member_accepted_by_moderator, member_joined_datetime, member_joined_date_saying FROM $t_rebus_groups_members WHERE member_group_id=$get_current_game_group_id AND member_user_id=$my_user_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_member_id, $get_current_member_group_id, $get_current_member_user_id, $get_current_member_user_name, $get_current_member_user_email, $get_current_member_user_photo_destination, $get_current_member_user_photo_thumb_50, $get_current_member_status, $get_current_member_invited, $get_current_member_user_accepted_invitation, $get_current_member_accepted_by_moderator, $get_current_member_joined_datetime, $get_current_member_joined_date_saying) = $row;
				if($get_current_member_id != ""){
					if($get_current_member_accepted_by_moderator == "1"){
						if($get_current_member_user_accepted_invitation == "1"){
							$can_view_game = 1;
						}
						else{
							$can_view_game = 0;
							/*- Headers ---------------------------------------------------------------------------------- */
							$website_title = "$get_current_game_title";
							if(file_exists("./favicon.ico")){ $root = "."; }
							elseif(file_exists("../favicon.ico")){ $root = ".."; }
							elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
							elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
							include("$root/_webdesign/header.php");
							echo"
							<h1>$get_current_game_title</h1>

							<p>$l_you_are_invited_to_join_the_group $get_current_game_group_name.
							
							$l_after_you_have_joined_the_group_you_may_play_this_game
							</p>
							<p>
							<a href=\"group_accept_invitation.php?group_id=$get_current_member_group_id&amp;l=$l\" class=\"btn_default\"><img src=\"_gfx/checked.png\" alt=\"checked.png\" /> $l_accept</a>
							<a href=\"group_decline_invitation.php?group_id=$get_current_member_group_id&amp;l=$l\" class=\"btn_default\"><img src=\"_gfx/decline.png\" alt=\"decline.png\" /> $l_decline</a>
							</p>
							";
							include("$root/_webdesign/footer.php");
						}
					}
				}

			}
		} // is invitied
	} // logged in
	else{
		$url = "$root/users/login.php?l=$l&amp;referer=rebus/play_game.php?game_id=$get_current_game_id&ft=info&fm=please_log_in_to_play_this_game";
		header("Location: $url");
		exit;
		
	}
}

if($can_view_game  == "1"){

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
		<!-- Headline -->";
			if(file_exists("$root/$get_current_game_image_path/$get_current_game_image_file") && $get_current_game_image_file != ""){
				echo"
				<p>
				<img src=\"$root/$get_current_game_image_path/$get_current_game_image_file\" alt=\"$get_current_game_image_file\" /><br />
				</p>
				";
			}
			echo"
			<h1>$get_current_game_title</h1>
		<!-- //Headline -->

		<!-- Tags, Edit, delete -->
				
		";
		// Check that I am owner
		if($my_user_id != ""){
			$query = "SELECT owner_id, owner_game_id, owner_user_id, owner_user_name, owner_user_email FROM $t_rebus_games_owners WHERE owner_game_id=$get_current_game_id AND owner_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_owner_id, $get_my_owner_game_id, $get_my_owner_user_id, $get_my_owner_user_name, $get_my_owner_user_email) = $row;
			if($get_my_owner_id != ""){
				echo"
				<section style=\"text-align: center;\">
					<p>
					<a href=\"edit_game.php?game_id=$get_current_game_id&amp;l=$l\" class=\"btn_default\">$l_edit</a>
					</p>
				</section>
				";
			}
		}

		echo"
		<!-- //Tags, Edit, delete -->

		<!-- Time, etc -->
			<div class=\"metadata_row\">

				<div class=\"metadata_column\">
					<p>
					<img src=\"_gfx/location_city_outline_black_24x24.png\" alt=\"location_city_outline_black_24x24.png\" /><br />
					<a href=\"index.php?l=$get_current_game_language&amp;countries=$get_current_game_country_id&amp;counties=$get_current_game_county_id&amp;municipalities=$get_current_game_municipality_id&amp;cities=$get_current_game_city_id&amp;l=$l\">$get_current_game_city_name</a>
					</p>
				</div>

				<div class=\"metadata_column\">
					<p>
					";
					if($get_current_game_difficulty == "tourist"){
						echo"<img src=\"_gfx/travel_explore_outline_black_24x24.png\" alt=\"travel_explore_outline_black_18x18.png\" /><br />
						$l_for_tourists";
					}
					else{
						echo"<img src=\"_gfx/wb_shade_outline_black_24x24.png\" alt=\"wb_shade_outline_black_18x18.png\" /><br />
						$l_for_locals";
					}
					echo"
					</p>
				</div>
				<div class=\"metadata_column\">
					<p>
					<img src=\"_gfx/assignment_black_24x24.png\" alt=\"assignment_black_24x24.png\" /><br />
					$get_current_game_number_of_assignments $l_assignments_lowercase
					</p>
				</div>
				<div class=\"metadata_column\">
					<p>
					<img src=\"_gfx/games_outline_black_24x24.png\" alt=\"games_outline_black_24x24.png\" /><br />
					$l_played $get_current_game_times_played $l_times_lowercase
					</p>
				</div>
				<div class=\"metadata_column\">
					<p>
					<img src=\"_gfx/games_round_black_24x24.png\" alt=\"games_round_black_24x24.png\" /><br />
					$l_finished $get_current_game_times_finished $l_times_lowercase ($get_current_game_finished_percentage %)
					</p>
				</div>
				<div class=\"metadata_column\">
					<p>
					<img src=\"_gfx/timer_black_24x24.png\" alt=\"timer_black_24x24.png\" /><br />
					$get_current_game_time_used_saying
					</p>
				</div>
			</div>
		<!-- //Time, etc -->

		<!-- Feedback -->
		";
		if($ft != "" && $fm != ""){
			$fm = ucfirst($fm);
			$fm = str_replace("_", " ", $fm);
			echo"<div class=\"$ft\"><p>$fm</p>";

			echo"</div>";
		}
		echo"
		<!-- //Feedback -->

		<!-- Game overview -->
			<section>";
			if($get_current_game_introduction != ""){
				echo"

				<p>$get_current_game_introduction</p>\n";
			}
			if($get_current_game_description != ""){
				echo"<p>$get_current_game_description</p>\n";
			}
		echo"
			</section>
		<!-- //Game overview -->



		<!-- Start -->
			<div class=\"start_game\">
			";
			if($get_current_game_playable_after_time > $time){


				echo"
				<div class=\"countdown\">
					<p>
					<span class=\"get_ready\">$l_get_ready</span><br />
					<span class=\"game_starts_in\">$l_game_starts_in</span>
					</p>
					<!-- Countdown script -->
					<script>
					function makeTimer() {

						var endTime = new Date(\"$get_current_game_playable_after_datetime_saying GMT+00:00\");			
						endTime = (Date.parse(endTime) / 1000);

						var now = new Date();
						now = (Date.parse(now) / 1000);

						var timeLeft = endTime - now;

						var days = Math.floor(timeLeft / 86400); 
						var hours = Math.floor((timeLeft - (days * 86400)) / 3600);
						var minutes = Math.floor((timeLeft - (days * 86400) - (hours * 3600 )) / 60);
						var seconds = Math.floor((timeLeft - (days * 86400) - (hours * 3600) - (minutes * 60)));
  
						if (hours < \"10\") { hours = \"0\" + hours; }
						if (minutes < \"10\") { minutes = \"0\" + minutes; }
						if (seconds < \"10\") { seconds = \"0\" + seconds; }

						\$(\"#days\").html(days + \"<span>Days</span>\");
						\$(\"#hours\").html(hours + \"<span>Hours</span>\");
						\$(\"#minutes\").html(minutes + \"<span>Minutes</span>\");
						\$(\"#seconds\").html(seconds + \"<span>Seconds</span>\");

						// Show next button
						if(days == 0 && hours == 0 && minutes == 0 && seconds == 0){
							var x = document.getElementById(\"start_game_buttons\");
							x.style.display = \"block\";
						}	
					}

					setInterval(function() { makeTimer(); }, 1000);

					// Hide start game buttons
					var x = document.getElementById(\"start_game_buttons\");
					x.style.display = \"none\";
					</script>
					<!-- //Countdown script -->

					<ul class=\"timer\">
						<li id=\"days\"></li>
						<li id=\"hours\"></li>
						<li id=\"minutes\"></li>
						<li id=\"seconds\"></li>
					</ul>

				</div> <!-- //countdown -->
				
				";
			}


			// Continue game + Check my teams
			if($my_user_id != ""){
				echo"
				<div id=\"start_game_buttons\">
					<a id=\"get_ready_to_start\"></a>
					<h2>$l_get_ready_to_start</h2>

					<ul class=\"start_buttons_list\">
					
				";

				// Start game :: Single Player :: Continue 
				// Continue game?
				$count_sessions = 0;
				$query_g = "SELECT session_id, session_game_id, session_play_as_user_group_team, session_user_id, session_group_id, session_team_id, session_start_datetime, session_start_time, session_is_on_assignment_number, session_is_finished, session_finished_datetime, session_finished_time, session_seconds_used, session_time_used_saying FROM $t_rebus_games_sessions_index WHERE session_game_id=$get_current_game_id AND session_play_as_user_group_team='user' AND session_user_id=$my_user_id_mysql AND session_is_finished=0 ORDER BY session_id DESC";
				$result_g = mysqli_query($link, $query_g);
				while($row_g = mysqli_fetch_row($result_g)) {
					list($get_session_id, $get_session_game_id, $get_session_play_as_user_group_team, $get_session_user_id, $get_session_group_id, $get_session_team_id, $get_session_start_datetime, $get_session_start_time, $get_session_is_on_assignment_number, $get_session_is_finished, $get_session_finished_datetime, $get_session_finished_time, $get_session_seconds_used, $get_session_time_used_saying) = $row_g;
			
					

					// How old? 
					$how_old_seconds = $time-$get_session_start_time;
					$how_old_days = round($how_old_seconds/86400, 0);

					if($count_sessions == "0"){
						echo"
						<li>
						<a href=\"play_game_3_assignments.php?game_id=$get_session_game_id&amp;session_id=$get_session_id&amp;l=$l\"><img src=\"_gfx/person_round_pink_24x24.png\" alt=\"person_round_pink_24x24.png\" /> $l_contine_single_player</a>
						<a href=\"remove_game_session.php?session_id=$get_session_id&amp;process=1&amp;referer=rebus/play_game.php?game_id=$get_current_game_id&amp;l=$l\" class=\"start_buttons_list_option\"><img src=\"_gfx/decline.png\" alt=\"decline.png\" /> $l_remove</a>
						</li>
						";
						$count_sessions++;
					} // game sessions
					else{
						mysqli_query($link, "DELETE FROM $t_rebus_games_sessions_index WHERE session_id=$get_session_id") or die(mysqli_error($link));
						mysqli_query($link, "DELETE FROM $t_rebus_games_sessions_answers WHERE answer_session_id=$get_session_id") or die(mysqli_error($link));
					}
				}

				// Start game :: Single Player :: New
				echo"<li><a href=\"play_game_2_start_session.php?game_id=$get_current_game_id&amp;play_as=user&amp;l=$l\" class=\"play_as_single_player\"><img src=\"_gfx/person_outline_light_green_24x24.png\" alt=\"person_outline_light_green_24x24.png\" /> $l_single_player</a></li>
				";
				
				// Count teams
				$query = "SELECT COUNT(member_id) FROM $t_rebus_teams_members WHERE member_user_id=$my_user_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_count_my_teams) = $row;
				


				// Get team games
				$count_sessions = 0;
				$query = "SELECT member_id, member_team_id, team_name FROM $t_rebus_teams_members JOIN $t_rebus_teams_index ON $t_rebus_teams_members.member_team_id=$t_rebus_teams_index.team_id WHERE member_user_id=$my_user_id_mysql AND member_user_accepted_invitation=1 ORDER BY $t_rebus_teams_index.team_name ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_member_id, $get_member_team_id, $get_team_name) = $row;

					// Find games
					$query_g = "SELECT session_id, session_game_id, session_play_as_user_group_team, session_user_id, session_group_id, session_team_id, session_start_datetime, session_start_time, session_is_on_assignment_number, session_is_finished, session_finished_datetime, session_finished_time, session_seconds_used, session_time_used_saying FROM $t_rebus_games_sessions_index WHERE session_game_id=$get_current_game_id AND session_play_as_user_group_team='team' AND session_team_id=$get_member_team_id AND session_is_finished=0 ORDER BY session_id DESC";
					$result_g = mysqli_query($link, $query_g);
					while($row_g = mysqli_fetch_row($result_g)) {
						list($get_session_id, $get_session_game_id, $get_session_play_as_user_group_team, $get_session_user_id, $get_session_group_id, $get_session_team_id, $get_session_start_datetime, $get_session_start_time, $get_session_is_on_assignment_number, $get_session_is_finished, $get_session_finished_datetime, $get_session_finished_time, $get_session_seconds_used, $get_session_time_used_saying) = $row_g;

						// How old? 
						$how_old_seconds = $time-$get_session_start_time;
						$how_old_days = round($how_old_seconds/86400, 0);

						echo"
							<li><a href=\"play_game_3_assignments.php?game_id=$get_session_game_id&amp;session_id=$get_session_id&amp;l=$l\"><img src=\"_gfx/group_round_light_purple_24x24.png\" alt=\"group_round_light_purple_24x24.png\" /> $l_join_team <b>$get_team_name</b><br />($how_old_days ";
						if($how_old_days == "1"){
							echo"$l_day_ago_lowercase";
						}
						else{
							echo"$l_days_ago_lowercase";
						}
						echo")</a>";


						if($how_old_days > 1){
							echo"
							<a href=\"remove_game_session.php?session_id=$get_session_id&amp;referer=rebus/play_game.php?game_id=$get_current_game_id&amp;l=$l\" class=\"start_buttons_list_option\"><img src=\"_gfx/decline.png\" alt=\"decline.png\" /> $l_remove</a>
							";
						}


						echo"							";
						echo"</li>
						";
						$count_sessions++;
					} // game sessions
				} // while teams
				echo"
						
						";
					if($get_count_my_teams == "0"){
						echo"<li><a href=\"play_game.php?action=create_team_to_play_with&amp;game_id=$get_current_game_id&amp;l=$l\" class=\"play_as_team\"><img src=\"_gfx/group_outline_yellow_24x24.png\" alt=\"group_outline_yellow_24x24.png\" /> $l_play_as_team</a></li>\n";
					}
					else{
						echo"<li><a href=\"play_game.php?action=select_team_to_play_with&amp;game_id=$get_current_game_id&amp;l=$l\" class=\"play_as_team\"><img src=\"_gfx/group_outline_yellow_24x24.png\" alt=\"group_outline_yellow_24x24.png\" /> $l_play_as_team</a></li>\n";
					}
					echo"
					</ul>
				</div>
				";
			}
			else{

				echo"
				<div id=\"start_game_buttons\">
					<a id=\"get_ready_to_start\"></a>
					<h2>$l_get_ready_to_start</h2>


					<ul class=\"start_buttons_list\">
						<li><a href=\"$root/users/login.php?l=$l&amp;referer=rebus/play_game.php?game_id=$get_current_game_id&amp;l=$l\"><img src=\"_gfx/person_outline_light_green_24x24.png\" alt=\"person_outline_light_green_24x24.png\" /> $l_single_player</a></li>
						<li><a href=\"$root/users/login.php?l=$l&amp;referer=rebus/play_game.php?game_id=$get_current_game_id&amp;l=$l\" class=\"play_as_team\"><img src=\"_gfx/group_outline_yellow_24x24.png\" alt=\"group_outline_yellow_24x24.png\" /> $l_play_as_team</a></li>
					</ul>
				</div>
				";
			}

			echo"
			
			</div> <!-- //start_game -->
		<!-- //Start -->



		<!-- High Score -->
			<div class=\"high_score\">
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
					   <td>
						<span>$get_high_score_place</span>
					   </td>
					   <td>
						<span>$get_high_score_name</span>
					   </td>
					   <td>
						<span>$get_high_score_points</span>
				 	   </td>
					   <td>
						<span>$get_high_score_time_used_saying</span>
					   </td>
					   <td>
						<span>$get_high_score_sum_saying</span>
					   </td>
					  </tr>
					";
					$x++;
				}
				echo"
				 </tbody>
				</table>


			</div>

		<!-- High Score  -->

		";

	} // action == ""
	elseif($action == "create_team_to_play_with"){
		if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
			$my_user_id = $_SESSION['user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);
			echo"
			<section>
			<h1>$l_play_as_team</h2>
			<p>$l_play_with_your_friends_family_or_colleagues.
			$l_if_you_are_managing_many_teams_then_you_should_start_by_creating_a
			<a href=\"groups.php?l=$l\">$l_group_lowercase</a>.</p>

			<!-- Focus -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_name\"]').focus();
				});
				</script>
			<!-- //Focus -->

			<!-- Create a team form -->

				<form method=\"post\" action=\"team_new.php?l=$l&amp;process=1\" enctype=\"multipart/form-data\">

				<p><b>$l_name:</b><br />
				<input type=\"text\" name=\"inp_name\" value=\"\" size=\"25\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
				</p>

				<p><b>$l_privacy:</b><br />";
				if(isset($_GET['privacy'])) {
					$privacy = $_GET['privacy'];
					$privacy = output_html($privacy);
				}
				else{
					$privacy = "private";
				}
				echo"
				<input type=\"radio\" name=\"inp_privacy\" value=\"public\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\""; if($privacy == "public"){ echo" checked=\"checked\""; } echo" /> $l_public &nbsp;
				<input type=\"radio\" name=\"inp_privacy\" value=\"private\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\""; if($privacy == "private"){ echo" checked=\"checked\""; } echo" /> $l_private
				</p>

				<p><b>$l_team_is_a_part_of_group:</b>";
				if(isset($_GET['group_id'])) {
					$group_id = $_GET['group_id'];
					$group_id = output_html($group_id);
					if(!(is_numeric($group_id))){
						echo"Group id not numeric";
						die;
					}
				}
				else{
					$group_id = "0";
				}
				echo"
				(<a href=\"new_group.php?l=$l\">$l_create_group</a>)<br />
				<select name=\"inp_group_id\">
					<option value=\"0\""; if($group_id == "0"){ echo" selected=\"selected\""; } echo">$l_none</selected>";
					$query = "SELECT member_id, member_group_id, group_name FROM $t_rebus_groups_members JOIN $t_rebus_groups_index ON $t_rebus_groups_members.member_group_id=$t_rebus_groups_index.group_id WHERE member_user_id=$my_user_id_mysql ORDER BY $t_rebus_groups_index.group_name ASC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_member_id, $get_member_group_id, $get_group_name) = $row;
						echo"			<option value=\"$get_member_group_id\""; if($group_id == "$get_member_group_id"){ echo" selected=\"selected\""; } echo">$get_group_name</selected>\n";
					}
					echo"
				</select></p>

				<p><input type=\"submit\" value=\"$l_create_team\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></p>
		
				</form>

			<!-- //Create a team form -->
			</section>
			";
		}
		else{
			echo"Login";
		}
	} // action == "create_team_to_play_with"
	elseif($action == "select_team_to_play_with"){

		if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
			$my_user_id = $_SESSION['user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);

			echo"
			<section>
			<h2>$l_please_select_your_team</h2>

			
			<!-- Check games my teams are playing so I can join them -->
			";
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
								<div class=\"bodycell\">

									<h2>$l_games_your_team_are_playing</h2>

							";
						}
						echo"
									<p style=\"padding-bottom: 4px;margin-bottom:0px;\">
									<a href=\"play_game.php?game_id=$get_member_team_id\" style=\"font-weight: bold;\">$get_game_title</a><br />
									<a href=\"team_show.php?team_id=$get_member_team_id\">$get_team_name</a><br />
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
								</div>								
								<hr />

								<h2>$l_start_a_new_game</h2>
							<!-- //Game sessoions -->
						";
					}
				} // while teams
				echo"
			<!-- //Check games my teams are playing so I can join them -->

			<!-- Teams -->
			<div class=\"vertical\">
				<ul>";

				// Get teams where I am member
				$query = "SELECT member_id, member_team_id, team_name FROM $t_rebus_teams_members JOIN $t_rebus_teams_index ON $t_rebus_teams_members.member_team_id=$t_rebus_teams_index.team_id WHERE member_user_id=$my_user_id_mysql ORDER BY $t_rebus_teams_index.team_name ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_member_id, $get_member_team_id, $get_team_name) = $row;

					echo"				";
					echo"<li><a href=\"play_game_2_start_session.php?game_id=$get_current_game_id&amp;play_as=team&amp;team_id=$get_member_team_id&amp;l=$l\">$get_team_name</a></li>\n";
				}
				echo"
				</ul>
			</div>
			<!-- //Teams -->
			</section>

			";
		}
		else{
			echo"
			<h1>
			<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
			Loading...</h1>
			<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=rebus/play_game.php?game_id=$get_current_game_id\">

			<p>Please log in...</p>
			";
		}
	} // action == "select_team_to_play_with"

	/*- Footer ----------------------------------------------------------------------------------- */
	include("$root/_webdesign/footer.php");
} // can view game

?>