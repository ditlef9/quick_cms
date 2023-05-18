<?php
/**
*
* File: rebus/my_games_view_game.php
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
	$query = "SELECT game_id, game_title, game_language, game_description, game_privacy, game_published, game_group_id, game_group_name, game_times_played, game_image_path, game_image_file, game_created_by_user_id, game_created_by_user_name, game_created_by_user_email, game_created_by_ip, game_created_by_hostname, game_created_by_user_agent, game_created_datetime, game_created_date_saying, game_updated_by_user_id, game_updated_by_user_name, game_updated_by_user_email, game_updated_by_ip, game_updated_by_hostname, game_updated_by_user_agent, game_updated_datetime, game_updated_date_saying FROM $t_rebus_games_index WHERE game_id=$game_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_game_id, $get_current_game_title, $get_current_game_language, $get_current_game_description, $get_current_game_privacy, $get_current_game_published, $get_current_game_group_id, $get_current_game_group_name, $get_current_game_times_played, $get_current_game_image_path, $get_current_game_image_file, $get_current_game_created_by_user_id, $get_current_game_created_by_user_name, $get_current_game_created_by_user_email, $get_current_game_created_by_ip, $get_current_game_created_by_hostname, $get_current_game_created_by_user_agent, $get_current_game_created_datetime, $get_current_game_created_date_saying, $get_current_game_updated_by_user_id, $get_current_game_updated_by_user_name, $get_current_game_updated_by_user_email, $get_current_game_updated_by_ip, $get_current_game_updated_by_hostname, $get_current_game_updated_by_user_agent, $get_current_game_updated_datetime, $get_current_game_updated_date_saying) = $row;
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
	$website_title = "$get_current_game_title - $l_my_games";
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

		<!-- Actions -->";
			if($get_current_game_privacy == "private"){
				if($get_current_game_group_id == ""){
					echo"
					<p>
					$l_this_game_is_private.
					$l_only_invited_players_can_play.
					$l_you_might_want_to
					<a href=\"group_new.php?l=$l\">$l_create_a_group_lowercase</a> 
					$l_and_lowercase
					<a href=\"edit_game_general.php?game_id=$get_current_game_id&amp;l=$l\">$l_assign_the_group_to_the_game_lowercase</a>,
					$l_this_will_allow_members_of_the_group_have_access_to_play_the_game 
					</p>
					";
				}
				else{
					echo"
					<p>
					$l_this_game_is_private.
					$l_only_invited_players_and_group_members_of_the_group
					<a href=\"group_open.php?group_id=$get_current_game_group_id&amp;l=$l\">$get_current_game_group_name</a>
					$l_can_play_lowercase.
					</p>
					";
				}
			}
			echo"
		<!-- //Actions -->

		<!-- My game menu -->
			<div class=\"vertical\">
				<ul>
					<li><a href=\"play_game.php?game_id=$get_current_game_id&amp;l=$l\">$l_play</a></li>
					<li><a href=\"edit_game_general.php?game_id=$get_current_game_id&amp;l=$l\">$l_general</a></li>
					<li><a href=\"edit_game_assignments.php?game_id=$get_current_game_id&amp;l=$l\">$l_assignments</a></li>
					<li><a href=\"edit_game_location.php?game_id=$get_current_game_id&amp;l=$l\">$l_location</a></li>
					<li><a href=\"edit_game_invited_players.php?game_id=$get_current_game_id&amp;l=$l\">$l_invited_players</a></li>
					<li><a href=\"edit_game_image.php?game_id=$get_current_game_id&amp;l=$l\">$l_image</a></li>
					<li><a href=\"edit_game_owners.php?game_id=$get_current_game_id&amp;l=$l\">$l_owners</a></li>
					<li><a href=\"edit_game_delete_game.php?game_id=$get_current_game_id&amp;l=$l\">$l_delete_game</a></li>
				</ul>
			</div>\n";
			if($get_current_game_privacy == "private" && $get_current_game_group_id != ""){
				echo"
				<p><b>$get_current_game_group_name:</b></p>
				<div class=\"vertical\">
					<ul>
						<li><a href=\"group_members.php?group_id=$get_current_game_group_id&amp;l=$l\">$get_current_game_group_name $l_members_lowercase</a></li>
					</ul>
				</div>\n";
			}
			echo"
		<!-- //My game menu -->
		";
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