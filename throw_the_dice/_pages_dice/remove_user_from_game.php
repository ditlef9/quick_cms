<?php
/**
*
* File: throw_the_dice/_pages_dice/remove_user_from_game.php
* Version 1.0.0.
* Date 10:33 09.12.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// Language
include("$root/_admin/_translations/site/$l/throw_the_dice/ts_remove_user_from_game.php");


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
if(isset($_GET['member_id'])) {
	$member_id = $_GET['member_id'];
	$member_id = strip_tags(stripslashes($member_id));
	if(!(is_numeric($member_id))){
		echo"member_id not numeric";
		die;
	}
}
else{
	$member_id = "";
	echo"Missing member_id";
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

// Find me
$query = "SELECT member_id FROM $t_dice_game_members WHERE member_game_id=$get_current_game_id AND member_security_code=$dice_member_security_code_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_my_member_id) = $row;
if($get_my_member_id == ""){
	echo"Cannot find member... <a href=\"index.php?page=home&amp;l=$l\">Home</a>";
	die;
}


// Find member to remove
$member_id_mysql = quote_smart($link, $member_id);
$query = "SELECT member_id, member_game_id, member_name, member_ip, member_security_code, member_number_of_throws, member_last_seen_time, member_joined_datetime, member_joined_saying FROM $t_dice_game_members WHERE member_id=$member_id_mysql AND member_game_id=$get_current_game_id";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_member_id, $get_current_member_game_id, $get_current_member_name, $get_current_member_ip, $get_current_member_security_code, $get_current_member_number_of_throws, $get_current_member_last_seen_time, $get_current_member_joined_datetime, $get_current_member_joined_saying) = $row;
if($get_current_member_id == ""){
	echo"Cannot find member... <a href=\"index.php?page=show_game&amp;game_id=$get_current_game_id&amp;l=$l\">Show game</a>";
	die;
}

// Check that I am admin
$i_am_admin = "0";
if(isset($_SESSION['dice_game_security_code'])){
	$dice_game_security_code = $_SESSION['dice_game_security_code'];
	$dice_game_security_code = output_html($dice_game_security_code);
	if($dice_game_security_code != "$get_current_game_security_code"){
		echo"Your not the admin of this game... <a href=\"index.php?page=show_game&amp;game_id=$get_current_game_id&amp;l=$l\">Show game</a>";
		die;
	}
}
else{
	echo"Missing admin session... <a href=\"index.php?page=show_game&amp;game_id=$get_current_game_id&amp;l=$l\">Show game</a>";
	die;
}

if($process == "1"){
	mysqli_query($link, "DELETE FROM $t_dice_game_members WHERE member_id=$get_current_member_id") or die(mysqli_error($link));

	// Header
	$url = "index.php?page=show_game&game_id=$get_current_game_id&l=$l&ft=success&fm=member_deleted";
	header("Location: $url");
	exit;
}




	echo"

	<!-- Headline -->
		<div style=\"float: left;\">
			<h1>$get_current_game_title</h1>
		</div>
	<!-- //Headline -->

	<!-- Pin -->
		<div style=\"float: right;text-align: center;\">
			<p style=\"padding-bottom:0;margin-bottom:0;\"><b>$l_pin:</b></p>
			<h2 style=\"padding-top:0;margin-top:0;\">$get_current_game_join_pin</h2>
		</div>
	<!-- //Pin -->
	<div class=\"clear\"></div>


	<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "you_threw"){
				if(isset($_GET['throw_result'])) {
					$throw_result = $_GET['throw_result'];
					$throw_result = strip_tags(stripslashes($throw_result));

					$fm = "$l_you_threw $throw_result";
				}
			}
			else{
				$fm = str_replace("_", " ", $fm);
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
	<!-- //Feedback -->



	<!-- remove_user_from_game -->
		<h2>$l_remove_user_from_game</h2>
		<section>
			<p>
			$l_please_confirm_that_you_want_to_remove 
			<b>$get_current_member_name</b>
			$l_from_the_game_lowercase.
			</p>

			<p>
			<a href=\"index.php?page=remove_user_from_game&amp;game_id=$get_current_game_id&amp;member_id=$get_current_member_id&amp;l=$l&amp;process=1\" class=\"btn_danger\">$l_confirm</a>
			<a href=\"index.php?page=show_game&amp;game_id=$get_current_game_id&amp;l=$l\" class=\"btn_default\">$l_cancel</a>
			</p>
		</section>
	<!-- //remove_user_from_game -->

	<!-- Members -->
		<!-- Get members script -->
				<script language=\"javascript\" type=\"text/javascript\">
					\$(document).ready(function () {
						function get_members(){
							var data = 'game_id=$get_current_game_id';
            						\$.ajax({
                						type: \"GET\",
               							url: \"_pages_dice/show_game_script_get_members.php\",
                						data: data,
								beforeSend: function(html) { // this happens before actual call
                    							\$(\"#game_members\").html(\"Loading!\");
								},
               							success: function(html){
                    							\$(\"#game_members\").html(html);
              							}
       							});
						}
						\$(\"#game_members\").html(\"Loading...\");
						get_members();
						setInterval(get_members,30000);
         				   });
				</script>
		<!-- //Get members script -->

		<h2>$l_members</h2>
		<section>
			<div id=\"game_members\"></div>
		</section>
	<!-- //Members -->

	";

/*- Footer ----------------------------------------------------------------------------------- */
include("_design/footer_dice.php");
?>