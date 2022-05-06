<?php 
/**
*
* File: _pages_dice/show_game_script_get_members.php
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

if(isset($_GET['last_time'])){
	$last_time = $_GET['last_time'];
	$last_time = output_html($last_time);
}
else{
	$last_time = "";
}
$last_time_mysql = quote_smart($link, $last_time);


$time = time();

// Get Game
$game_id_mysql = quote_smart($link, $game_id);
$query = "SELECT game_id, game_title, game_owner_name, game_owner_ip, game_password, game_security_code, game_join_pin, game_number_of_dices, game_created_datetime, game_created_saying, game_notes FROM $t_dice_game_index WHERE game_id=$game_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_game_id, $get_current_game_title, $get_current_game_owner_name, $get_current_game_owner_ip, $get_current_game_password, $get_current_game_security_code, $get_current_game_join_pin, $get_current_game_number_of_dices, $get_current_game_created_datetime, $get_current_game_created_saying, $get_current_game_notes) = $row;
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
$query = "SELECT member_id FROM $t_dice_game_members WHERE member_game_id=$get_current_game_id AND member_security_code=$dice_member_security_code_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_my_member_id) = $row;
if($get_my_member_id == ""){
	echo"Cannot find member... <a href=\"../index.php\">Home</a>";
	die;
}

// Check if I am admin
$i_am_admin = "0";
if(isset($_SESSION['dice_game_security_code'])){
	$dice_game_security_code = $_SESSION['dice_game_security_code'];
	$dice_game_security_code = output_html($dice_game_security_code);
	if($dice_game_security_code == "$get_current_game_security_code"){
		$i_am_admin = "1";
	}
}

// Fetch all members
echo"
<ul>
";
$query = "SELECT member_id, member_game_id, member_name, member_ip, member_security_code, member_number_of_throws, member_last_seen_time, member_joined_datetime, member_joined_saying FROM $t_dice_game_members WHERE member_game_id=$get_current_game_id ORDER BY member_number_of_throws ASC";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_row($result)) {
	list($get_member_id, $get_member_game_id, $get_member_name, $get_member_ip, $get_member_security_code, $get_member_number_of_throws, $get_member_last_seen_time, $get_member_joined_datetime, $get_member_joined_saying) = $row;

	if($i_am_admin == "1"){
		echo"	";
		echo"<li><span><a href=\"index.php?page=remove_user_from_game&amp;game_id=$get_current_game_id&amp;member_id=$get_member_id&amp;l=$l\">$get_member_name</a>";
	}
	else{
		echo"	";
		echo"<li><span>$get_member_name</span>";
	}


	// If me then update seen
	if($get_member_id == "$get_my_member_id"){
		mysqli_query($link, "UPDATE $t_dice_game_members SET member_last_seen_time='$time' WHERE member_id=$get_member_id") or die(mysqli_error($link));
		$get_member_last_seen_time = "$time";
	}

	// Older than 3 min? Delete
	$seconds_since_online = $time-$get_member_last_seen_time;
	if($seconds_since_online > 180){
		mysqli_query($link, "DELETE FROM $t_dice_game_members WHERE member_id=$get_member_id") or die(mysqli_error($link));
		echo"<span style=\"color:red;\">Timeout!</span>";
	}

	echo"</li>";
}
echo"
</ul>";

?>