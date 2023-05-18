<?php
/**
*
* File: rebus/edit_game_invited_players.php
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

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);



	/*- Find game ------------------------------------------------------------------------- */
	$game_id_mysql = quote_smart($link, $game_id);
	$query = "SELECT game_id, game_title, game_language, game_introduction, game_description, game_privacy, game_published, game_playable_after_datetime, game_playable_after_time, game_group_id, game_group_name, game_times_played, game_image_path, game_image_file, game_created_by_user_id, game_created_by_user_name, game_created_by_user_email, game_created_by_ip, game_created_by_hostname, game_created_by_user_agent, game_created_datetime, game_created_date_saying, game_updated_by_user_id, game_updated_by_user_name, game_updated_by_user_email, game_updated_by_ip, game_updated_by_hostname, game_updated_by_user_agent, game_updated_datetime, game_updated_date_saying FROM $t_rebus_games_index WHERE game_id=$game_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_game_id, $get_current_game_title, $get_current_game_language, $get_current_game_introduction, $get_current_game_description, $get_current_game_privacy, $get_current_game_published, $get_current_game_playable_after_datetime, $get_current_game_playable_after_time, $get_current_game_group_id, $get_current_game_group_name, $get_current_game_times_played, $get_current_game_image_path, $get_current_game_image_file, $get_current_game_created_by_user_id, $get_current_game_created_by_user_name, $get_current_game_created_by_user_email, $get_current_game_created_by_ip, $get_current_game_created_by_hostname, $get_current_game_created_by_user_agent, $get_current_game_created_datetime, $get_current_game_created_date_saying, $get_current_game_updated_by_user_id, $get_current_game_updated_by_user_name, $get_current_game_updated_by_user_email, $get_current_game_updated_by_ip, $get_current_game_updated_by_hostname, $get_current_game_updated_by_user_agent, $get_current_game_updated_datetime, $get_current_game_updated_date_saying) = $row;
	if($get_current_game_id == ""){
		$url = "index.php?ft=error&fm=game_not_found&l=$l";
		header("Location: $url");
		exit;
	}

	/*- Check that I am a owner of this game --------------------------------------------- */
	$query = "SELECT owner_id, owner_game_id, owner_user_id, owner_user_name, owner_user_email FROM $t_rebus_games_owners WHERE owner_game_id=$get_current_game_id AND owner_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_owner_id, $get_my_owner_game_id, $get_my_owner_user_id, $get_my_owner_user_name, $get_my_owner_user_email) = $row;
	if($get_my_owner_id == ""){
		$url = "index.php?ft=error&fm=your_not_a_owner_of_that_game&l=$l";
		header("Location: $url");
		exit;
	}


	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$l_invited_players - $get_current_game_title - $l_my_games";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");
	
	if($action == ""){
		echo"
		<!-- Headline -->
			<h1>$get_current_game_title</h1>
		<!-- //Headline -->

		<!-- Where am I ? -->
			<p><b>$l_you_are_here:</b><br />
			<a href=\"index.php?l=$l\">$l_rebus</a>
			&gt;
			<a href=\"my_games.php?l=$l\">$l_my_games</a>
			&gt;
			<a href=\"edit_game.php?game_id=$get_current_game_id&amp;l=$l\">$get_current_game_title</a>
			&gt;
			<a href=\"edit_game_invited_players.php?game_id=$get_current_game_id&amp;l=$l\">$l_invited_players</a>
			</p>
		<!-- //Where am I ? -->

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

		<!-- Actions -->
			<p>
			<a href=\"edit_game_invite_player.php?game_id=$get_current_game_id&amp;l=$l\" class=\"btn_default\">$l_invite_player</a>
			</p>
		<!-- //Actions -->

		<!-- Invited players -->
			<table class=\"hor-zebra\">
			 <thead>
			  <tr>
			   <th>
				<span>$l_username</span>
			   </th>
			   <th>
				<span>$l_accepted_invitation</span>
			   </th>
			   <th>
				<span>$l_accepted_by_moderator</span>
			   </th>
			   <th>
				<span>$l_actions</span>
			   </th>
			  </tr>
			 </thead>
			 <tbody>";
			$query = "SELECT invited_player_id, invited_player_user_id, invited_player_user_name, invited_player_invited, invited_player_user_accepted_invitation, invited_player_accepted_by_moderator FROM $t_rebus_games_invited_players WHERE invited_player_game_id=$get_current_game_id ORDER BY invited_player_user_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_invited_player_id, $get_invited_player_user_id, $get_invited_player_user_name, $get_invited_player_invited, $get_invited_player_user_accepted_invitation, $get_invited_player_accepted_by_moderator) = $row;

				echo"
				 <tr>
				  <td>
					<span>$get_invited_player_user_name</span>
				  </td>
				  <td>
					";
					if($get_invited_player_user_accepted_invitation == "1"){
						echo"<span>$l_accepted_invitation</span>";
					}
					else{
						echo"<span style=\"color: orange;\">$l_pending_acceptance</span>";
					}
					echo"
				  </td>
				  <td>
					";
					if($get_invited_player_accepted_by_moderator == "1"){
						echo"<span>$l_accepted_by_moderator</span>";

					}
					else{
						echo"<span style=\"color: orange;\">$l_pending_acceptance_by_moderator</span>";
					}
					echo"
				  </td>
				  <td>
					<span><a href=\"edit_game_invited_players.php?action=remove_invited_player&amp;game_id=$get_current_game_id&amp;invited_player_id=$get_invited_player_id&amp;l=$l\">$l_remove</a></span>
				  </td>
				 </tr>

				";
			}	
			echo"
			 </tbody>
			</table>
		<!-- //Invited players -->
		";

	} // action == ""
	elseif($action == "remove_invited_player"){
		if(isset($_GET['invited_player_id'])) {
			$invited_player_id = $_GET['invited_player_id'];
			$invited_player_id = output_html($invited_player_id);
			if(!(is_numeric($invited_player_id))){
				echo"invited_player_id not numeric";
				die;
			}
		}
		else{
			echo"Missing invited_player_id";
			die;
		}

		// Get owner
		$invited_player_id_mysql = quote_smart($link, $invited_player_id);
		$query = "SELECT invited_player_id, invited_player_game_id, invited_player_user_id, invited_player_user_name, invited_player_user_email, invited_player_user_photo_destination, invited_player_user_photo_thumb_50, invited_player_invited, invited_player_user_accepted_invitation, invited_player_accepted_by_moderator, invited_player_added_datetime, invited_player_added_date_saying, invited_player_last_played FROM $t_rebus_games_invited_players WHERE invited_player_id=$invited_player_id_mysql AND invited_player_game_id=$get_current_game_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_invited_player_id, $get_current_invited_player_game_id, $get_current_invited_player_user_id, $get_current_invited_player_user_name, $get_current_invited_player_user_email, $get_current_invited_player_user_photo_destination, $get_current_invited_player_user_photo_thumb_50, $get_current_invited_player_invited, $get_current_invited_player_user_accepted_invitation, $get_current_invited_player_accepted_by_moderator, $get_current_invited_player_added_datetime, $get_current_invited_player_added_date_saying, $get_current_invited_player_last_played) = $row;
		if($get_current_invited_player_id == ""){
			echo"invited_player not found";
			exit;
		}
		
		if($process == "1"){
			mysqli_query($link, "DELETE FROM $t_rebus_games_invited_players WHERE invited_player_id=$invited_player_id_mysql") or die(mysqli_error($link));

			// Header
			$url = "edit_game_invited_players.php?game_id=$get_current_game_id&l=$l&ft=success&fm=invitied_player_removed";
			header("Location: $url");
			exit;
		} // process == 1

		echo"
		<!-- Headline -->
			<h1>$get_current_game_title</h1>
		<!-- //Headline -->

		<!-- Where am I ? -->
			<p><b>$l_you_are_here:</b><br />
			<a href=\"index.php?l=$l\">$l_rebus</a>
			&gt;
			<a href=\"my_games.php?l=$l\">$l_my_games</a>
			&gt;
			<a href=\"edit_game.php?game_id=$get_current_game_id&amp;l=$l\">$get_current_game_title</a>
			&gt;
			<a href=\"edit_game_invited_players.php?game_id=$get_current_game_id&amp;l=$l\">$l_invited_players</a>
			&gt;
			<a href=\"edit_game_invited_players.php?action=remove_invited_player&amp;game_id=$get_current_game_id&amp;invited_player_id=$get_current_invited_player_id&amp;l=$l\">$l_remove $get_current_invited_player_user_name</a>
			</p>
		<!-- //Where am I ? -->



		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				$fm = ucfirst($fm);
				$fm = str_replace("_", " ", $fm);
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->

		<!-- Delete form -->
			<p>
			$l_are_you_sure_you_want_to_remove_the_invited_player
			</p>

			<p>
			<a href=\"edit_game_invited_players.php?action=remove_invited_player&amp;game_id=$get_current_game_id&amp;invited_player_id=$get_current_invited_player_id&amp;l=$l&amp;process=1\" class=\"btn_danger\">$l_confirm</a>
			<a href=\"edit_game_invited_players.php?action=remove_invited_player&amp;game_id=$get_current_game_id&amp;l=$l\" class=\"btn_default\">$l_cancel</a>
			</p>
			

		<!-- //Delete form -->
		";
	} // action == remove invited player
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