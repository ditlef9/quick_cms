<?php
/**
*
* File: rebus/group_accept_invitation.php
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
include("$root/_admin/_data/logo.php");
include("$root/_admin/_data/config/user_system.php");

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


/*- Translation ------------------------------------------------------------------------------- */


// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);



	/*- Find game ------------------------------------------------------------------------- */
	$game_id_mysql = quote_smart($link, $game_id);
	$query = "SELECT game_id, game_title, game_language, game_introduction, game_description, game_privacy, game_published, game_playable_after_datetime, game_playable_after_datetime_saying, game_playable_after_time, game_group_id, game_group_name, game_times_played, game_times_finished, game_finished_percentage, game_time_used_seconds, game_time_used_saying, game_image_path, game_image_file, game_image_thumb_278x156, game_image_thumb_570x321, game_image_thumb_570x380, game_country_id, game_country_name, game_county_id, game_county_name, game_municipality_id, game_municipality_name, game_city_id, game_city_name, game_place_id, game_place_name, game_number_of_assignments, game_rating, game_created_by_user_id, game_created_by_user_name, game_created_by_user_email, game_created_by_ip, game_created_by_hostname, game_created_by_user_agent, game_created_datetime, game_created_date_saying, game_updated_by_user_id, game_updated_by_user_name, game_updated_by_user_email, game_updated_by_ip, game_updated_by_hostname, game_updated_by_user_agent, game_updated_datetime, game_updated_date_saying FROM $t_rebus_games_index WHERE game_id=$game_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_game_id, $get_current_game_title, $get_current_game_language, $get_current_game_introduction, $get_current_game_description, $get_current_game_privacy, $get_current_game_published, $get_current_game_playable_after_datetime, $get_current_game_playable_after_datetime_saying, $get_current_game_playable_after_time, $get_current_game_group_id, $get_current_game_group_name, $get_current_game_times_played, $get_current_game_times_finished, $get_current_game_finished_percentage, $get_current_game_time_used_seconds, $get_current_game_time_used_saying, $get_current_game_image_path, $get_current_game_image_file, $get_current_game_image_thumb_278x156, $get_current_game_image_thumb_570x321, $get_current_game_image_thumb_570x380, $get_current_game_country_id, $get_current_game_country_name, $get_current_game_county_id, $get_current_game_county_name, $get_current_game_municipality_id, $get_current_game_municipality_name, $get_current_game_city_id, $get_current_game_city_name, $get_current_game_place_id, $get_current_game_place_name, $get_current_game_number_of_assignments, $get_current_game_rating, $get_current_game_created_by_user_id, $get_current_game_created_by_user_name, $get_current_game_created_by_user_email, $get_current_game_created_by_ip, $get_current_game_created_by_hostname, $get_current_game_created_by_user_agent, $get_current_game_created_datetime, $get_current_game_created_date_saying, $get_current_game_updated_by_user_id, $get_current_game_updated_by_user_name, $get_current_game_updated_by_user_email, $get_current_game_updated_by_ip, $get_current_game_updated_by_hostname, $get_current_game_updated_by_user_agent, $get_current_game_updated_datetime, $get_current_game_updated_date_saying) = $row;
	if($get_current_game_id == ""){
		$url = "index.php?ft=error&fm=game_not_found&l=$l";
		header("Location: $url");
		exit;
	}

	/*- Check my invitation --------------------------------------------- */
	$query = "SELECT invited_player_id, invited_player_game_id, invited_player_user_id, invited_player_user_name, invited_player_user_email, invited_player_user_photo_destination, invited_player_user_photo_thumb_50, invited_player_invited, invited_player_user_accepted_invitation, invited_player_accepted_by_moderator, invited_player_added_datetime, invited_player_added_date_saying, invited_player_last_played FROM $t_rebus_games_invited_players WHERE invited_player_game_id=$get_current_game_id AND invited_player_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_invited_player_id, $get_current_invited_player_game_id, $get_current_invited_player_user_id, $get_current_invited_player_user_name, $get_current_invited_player_user_email, $get_current_invited_player_user_photo_destination, $get_current_invited_player_user_photo_thumb_50, $get_current_invited_player_invited, $get_current_invited_player_user_accepted_invitation, $get_current_invited_player_accepted_by_moderator, $get_current_invited_player_added_datetime, $get_current_invited_player_added_date_saying, $get_current_invited_player_last_played) = $row;
	if($get_current_invited_player_id == ""){
		$url = "index.php?ft=error&fm=could_not_find_your_game_invitation&l=$l";
		header("Location: $url");
		exit;
	}

	// Accept
	if($get_current_invited_player_invited == "1" && $get_current_invited_player_user_accepted_invitation == "0"){

		// Accept invitation
		mysqli_query($link, "UPDATE $t_rebus_games_invited_players SET 
					invited_player_user_accepted_invitation=1 
					 WHERE invited_player_id=$get_current_invited_player_id") or die(mysqli_error($link));


		// Welcome
		$url = "play_game.php?game_id=$get_current_game_id&l=$l&ft=success&fm=accepted";
		header("Location: $url");
		exit;		
	}
	else{
		// Already accepted
		echo"
		<h1>
		<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
		Loading...</h1>
		<meta http-equiv=\"refresh\" content=\"1;url=play_game.php?game_id=$get_current_game_id&amp;l=$l\">

		<p>You already accepted the game invitation.</p>
		";
	}
	
}
else{
	echo"
	<h1>
	<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=rebus/groups.php\">

	<p>Please log in...</p>
	";
}

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>