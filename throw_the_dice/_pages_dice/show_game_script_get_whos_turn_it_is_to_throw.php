<?php 
/**
*
* File: _pages_dice/show_game_script_get_whos_turn_it_is_to_throw.php
* Version 1.0.0
* Date 13:42 09.12.2021
* Copyright (c) 2021 S. A. Ditlefsen
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

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/throw_the_dice/ts_show_game.php");


/*- Tables ---------------------------------------------------------------------------- */
include("../_tables_dice.php");

/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['game_id'])) {
	$game_id = $_GET['game_id'];
	$game_id = strip_tags(stripslashes($game_id));
	if(!(is_numeric($game_id))){
		echo"Game id not numeric";
		die;
	}
}
else{
	$game_id = "";
	echo"Missing game id";
	die;
}

// Get Game
$game_id_mysql = quote_smart($link, $game_id);
$query = "SELECT game_id, game_title, game_owner_name, game_owner_ip, game_password, game_security_code, game_join_pin, game_number_of_dices, game_throw_log, game_created_datetime, game_created_saying, game_notes FROM $t_dice_game_index WHERE game_id=$game_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_game_id, $get_current_game_title, $get_current_game_owner_name, $get_current_game_owner_ip, $get_current_game_password, $get_current_game_security_code, $get_current_game_join_pin, $get_current_game_number_of_dices, $get_current_game_throw_log, $get_current_game_created_datetime, $get_current_game_created_saying, $get_current_game_notes) = $row;
if($get_current_game_id == ""){
	echo"Game not found";
	die;
}


// Check that session is correct
$dice_member_security_code = "";
if(isset($_SESSION['dice_member_security_code'])){
	// dice_member_security_code
	$dice_member_security_code = $_SESSION['dice_member_security_code'];
	$dice_member_security_code = output_html($dice_member_security_code);
}
else{
	echo"
	Missing member security
	";
	die;
}
$dice_member_security_code_mysql = quote_smart($link, $dice_member_security_code);

// Find my memmber
$query = "SELECT member_id FROM $t_dice_game_members WHERE member_game_id=$get_current_game_id AND member_security_code=$dice_member_security_code_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_my_member_id) = $row;
if($get_my_member_id == ""){
	echo"Cannot find member... <a href=\"../index.php\">Home</a>";
	die;
}

// Find next to throw
$query = "SELECT member_id, member_game_id, member_name, member_ip, member_security_code, member_number_of_throws, member_last_seen_time, member_joined_datetime, member_joined_saying FROM $t_dice_game_members WHERE member_game_id=$get_current_game_id ORDER BY member_number_of_throws ASC LIMIT 0,1";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_member_id, $get_member_game_id, $get_member_name, $get_member_ip, $get_member_security_code, $get_member_number_of_throws, $get_member_last_seen_time, $get_member_joined_datetime, $get_member_joined_saying) = $row;


// Print data
$l_s_turn_to_throw_lowercase = str_replace("&amp;nbsp;", " ", $l_s_turn_to_throw_lowercase);
echo"
		<h2 style=\"padding-left: 10px;padding-right: 10px;\">$get_member_name$l_s_turn_to_throw_lowercase</h2>
		<section>
			";
			if($get_member_id == "$get_my_member_id"){

				echo"
				<p>
				<a href=\"index.php?page=throw_dices&amp;game_id=$get_current_game_id&amp;l=$l&amp;l=$l&amp;process=1\" class=\"btn\" style=\"font-size: 200%;display: inline-block;\"><img src=\"_design/gfx/throw_the_dice_white_36x36.svg\" alt=\"dice_16x16.png\" /> ";
				if($get_current_game_number_of_dices == "1"){
					echo"$l_throw_dice";
				}
				else{
					echo"$l_throw_dices";
				}
				echo" <img src=\"_design/gfx/throw_the_dice_white_36x36.svg\" alt=\"dice_16x16.png\" /></a>
				</p>
				";
			}
			echo"

			<p>
			$get_current_game_throw_log
			</p>
		</section>



";
?>