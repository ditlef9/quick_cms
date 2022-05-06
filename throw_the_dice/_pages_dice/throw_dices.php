<?php
/**
*
* File: throw_the_dice/_pages_dice/throw_dices.php
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

// Find member
$query = "SELECT member_id, member_name, member_number_of_throws FROM $t_dice_game_members WHERE member_game_id=$get_current_game_id AND member_security_code=$dice_member_security_code_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_my_member_id, $get_my_member_name, $get_my_member_number_of_throws) = $row;
if($get_my_member_id == ""){
	echo"Cannot find member... <a href=\"index.php?page=home&amp;l=$l\">Home</a>";
	die;
}

// Find next to throw
$query = "SELECT member_id, member_game_id, member_name, member_ip, member_security_code, member_number_of_throws, member_last_seen_time, member_joined_datetime, member_joined_saying FROM $t_dice_game_members WHERE member_game_id=$get_current_game_id ORDER BY member_number_of_throws ASC LIMIT 0,1";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_member_id, $get_member_game_id, $get_member_name, $get_member_ip, $get_member_security_code, $get_member_number_of_throws, $get_member_last_seen_time, $get_member_joined_datetime, $get_member_joined_saying) = $row;

if($get_member_id != "$get_my_member_id"){
	$url = "index.php?page=show_game&game_id=$get_current_game_id&ft=error&fm=its_not_your_turn_to_throw&l=$l";
	header("Location: $url");
	exit;
}
else{
	// Inp throws
	$inp_number_of_throws = $get_my_member_number_of_throws+1;
	
	// Throw
	$inp_result = "";
	$inp_result_saying = "";
	for($x=0;$x<$get_current_game_number_of_dices;$x++){
		$rand = rand(1,6);
		if($inp_result == ""){
			$inp_result = "$rand";
			$inp_result_saying = "<b>$rand</b>";
		}
		else{
			$inp_result = $inp_result . ",$rand";


			$x_human = $x+1; 
			if($x_human == "$get_current_game_number_of_dices"){
				$inp_result_saying = "$inp_result_saying $l_and_lowercase <b>$rand</b>";
			}
			else{
				$inp_result_saying = ", <b>$rand</b>";
			}
		}
	}

	$inp_result = output_html($inp_result);
	$inp_result_mysql = quote_smart($link, $inp_result);
	
	mysqli_query($link, "UPDATE $t_dice_game_members SET 
				member_number_of_throws=$inp_number_of_throws,
				member_last_throw_result=$inp_result_mysql,
				member_last_seen_time='$time' WHERE member_id=$get_member_id") or die(mysqli_error($link));



	// Update throw log
	$inp_throw_log = "[$hour_minute] <b>$get_member_name</b> $l_threw_lowercase $inp_result_saying<br />
$get_current_game_throw_log";
	$inp_throw_log_mysql = quote_smart($link, $inp_throw_log);
	mysqli_query($link, "UPDATE $t_dice_game_index SET 
				game_throw_log=$inp_throw_log_mysql
				WHERE game_id=$get_current_game_id") or die(mysqli_error($link));

	
	

	$url = "index.php?page=show_game&game_id=$get_current_game_id&ft=success&fm=you_threw&throw_result=$inp_result&l=$l";
	header("Location: $url");
	exit;
}


/*- Footer ----------------------------------------------------------------------------------- */
include("_design/footer_dice.php");
?>