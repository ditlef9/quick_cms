<?php
/**
*
* File: throw_the_dice/_pages_dice/quit_game.php
* Version 1.0.0.
* Date 10:33 09.12.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/


// Lang
include("$root/_admin/_translations/site/$l/throw_the_dice/ts_throw_dices.php");


/*- Variables ------------------------------------------------------------------------ */
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

// Find me member
$query = "SELECT member_id, member_name, member_number_of_throws FROM $t_dice_game_members WHERE member_game_id=$get_current_game_id AND member_security_code=$dice_member_security_code_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_my_member_id, $get_my_member_name, $get_my_member_number_of_throws) = $row;
if($get_my_member_id == ""){
	echo"Cannot find member... <a href=\"index.php?page=home&amp;l=$l\">Home</a>";
	die;
}

if($get_my_member_id == ""){
	$url = "index.php?ft=error&fm=game_not_found&l=$l";
	header("Location: $url");
	exit;
}
else{
	
	mysqli_query($link, "DELETE FROM $t_dice_game_members WHERE member_id=$get_my_member_id") or die(mysqli_error($link));

	// Unset session
	$_SESSION = array();
	session_destroy();

	echo"<meta http-equiv=\"refresh\" content=\"1;url=index.php?ft=info&fm=game_quitted&l=$l\" />";
	exit;
}


/*- Footer ----------------------------------------------------------------------------------- */
include("_design/footer_dice.php");
?>