<?php
/**
*
* File: throw_the_dice/_pages_dice/show_game.php
* Version 1.0.0.
* Date 10:33 09.12.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// Language
include("$root/_admin/_translations/site/$l/throw_the_dice/ts_show_game.php");


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
	echo"Game not found
	<meta http-equiv=\"refresh\" content=\"2;url=index.php?ft=info&fm=game_quitted&l=$l\" />";
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
	<meta http-equiv=\"refresh\" content=\"2;url=index.php?ft=info&fm=game_quitted&l=$l\" />
	";
	die;
}
$dice_member_security_code_mysql = quote_smart($link, $dice_member_security_code);

// Find member
$query = "SELECT member_id, member_game_id, member_name, member_ip, member_security_code, member_number_of_throws, member_last_seen_time, member_joined_datetime, member_joined_saying FROM $t_dice_game_members WHERE member_game_id=$get_current_game_id AND member_security_code=$dice_member_security_code_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_member_id, $get_current_member_game_id, $get_current_member_name, $get_current_member_ip, $get_current_member_security_code, $get_current_member_number_of_throws, $get_current_member_last_seen_time, $get_current_member_joined_datetime, $get_current_member_joined_saying) = $row;
if($get_current_member_id == ""){
	echo"Cannot find member... <a href=\"index.php?page=home&amp;l=$l\">Home</a>
	<meta http-equiv=\"refresh\" content=\"2;url=index.php?ft=info&fm=game_quitted&l=$l\" />";
	die;
}




if($action == ""){
	echo"

	<!-- Headline -->
		<div style=\"float: left;padding-left: 10px;\">
			<h1>$get_current_game_title</h1>
		</div>
	<!-- //Headline -->

	<!-- Pin -->
		<div style=\"float: right;text-align: center;padding-right: 10px;\">
			<p style=\"padding-bottom:0;margin-bottom:0;\"><b>$l_pin:</b></p>
			<h2 style=\"padding-top:0;margin-top:0;\">";
			$first = substr($get_current_game_join_pin, 0, 3);
			$last = substr($get_current_game_join_pin, 3, 3);
			echo"$first $last</h2>

			<p><a href=\"index.php?page=quit_game&amp;game_id=$get_current_game_id\"><img src=\"_design/gfx/logout_black_18x18.png\" alt=\"logout_black_18x18.png\" /></a></p>
		</div>
	<!-- //Pin -->
	<div class=\"clear\"></div>


	<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "you_threw"){
				$fm = "$l_you_threw ";
				if(isset($_GET['throw_result'])) {
					$throw_result = $_GET['throw_result'];
					$throw_result = strip_tags(stripslashes($throw_result));
					$throw_result_array = explode(",", $throw_result);
					$throw_result_array_size = sizeof($throw_result_array);
					for($x=0;$x<$throw_result_array_size;$x++){
						if(isset($throw_result_array[$x])){
							$value = $throw_result_array[$x];
							if($value == "1" OR $value == "2" OR $value == "3" OR $value == "4" OR $value == "5" OR $value == "6"){
								$fm = $fm . "<img src=\"_design/gfx/dice/dice_$value.svg\" width=\"48\" height=\"48\" alt=\"dice_$value.svg\" style=\"vertical-align:middle;\" />$value &nbsp; ";
							}
						}
					}
				}
				echo"<div class=\"$ft\"><span style=\"vertical-align:middle;font-size:150%;font-weight: bold;\">$fm</span></div>";
			}
			else{
				$fm = str_replace("_", " ", $fm);
				$fm = ucfirst($fm);
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
		}
		echo"	
	<!-- //Feedback -->



	<!-- Throw dices -->
		<!-- Get whos turn it is to throw script -->
			<script language=\"javascript\" type=\"text/javascript\">
			\$(document).ready(function () {
				function get_whos_turn_it_is_to_throw(){
					var data = 'game_id=$get_current_game_id&l=$l';
            				\$.ajax({
                				type: \"GET\",
               					url: \"_pages_dice/show_game_script_get_whos_turn_it_is_to_throw.php\",
                				data: data,
						beforeSend: function(html) { // this happens before actual call
						},
               					success: function(html){
                    					\$(\"#whos_turn_it_is_to_throw_data\").html(html);
              					}
       					});
				}
				\$(\"#whos_turn_it_is_to_throw_data\").html(\"Loading...\");
				get_whos_turn_it_is_to_throw();
				setInterval(get_whos_turn_it_is_to_throw,5000);
         		});
			</script>
		<!-- //Get whos turn it is to throw script -->


		<div id=\"whos_turn_it_is_to_throw_data\"></div>
	<!-- //Throw dices -->

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
}


?>