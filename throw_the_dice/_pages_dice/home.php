<?php
/**
*
* File: throw_the_dice/_pages_dice/home.php
* Version 1.0.0.
* Date 10:33 09.12.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// Language
include("$root/_admin/_translations/site/$l/throw_the_dice/ts_home.php");


if($action == "join_game"){
	// Datetime
	$datetime_saying = date("j M Y H:i:s");

	// PIN
	$inp_pin = $_POST['inp_pin'];
	$inp_pin = output_html($inp_pin);
	if($inp_pin == ""){
		$url = "index.php?page=home&ft=error&fm=missing_pin&l=$l";
		header("Location: $url");
		exit;
	}
	$inp_pin_mysql = quote_smart($link, $inp_pin);


	// Owner name
	$inp_name = $_POST['inp_name'];
	$inp_name = output_html($inp_name);
	if($inp_name == ""){
		$rand = rand(0,100);
		$inp_name = "No name $rand";
	}
	$inp_name_mysql = quote_smart($link, $inp_name);

	// Find game
	$query = "SELECT game_id, game_title, game_owner_name, game_owner_ip, game_password, game_security_code, game_join_pin, game_number_of_dices, game_created_datetime, game_created_saying, game_notes FROM $t_dice_game_index WHERE game_join_pin=$inp_pin_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_game_id, $get_current_game_title, $get_current_game_owner_name, $get_current_game_owner_ip, $get_current_game_password, $get_current_game_security_code, $get_current_game_join_pin, $get_current_game_number_of_dices, $get_current_game_created_datetime, $get_current_game_created_saying, $get_current_game_notes) = $row;
	if($get_current_game_id == ""){
		$url = "index.php?page=home&ft=error&fm=could_not_find_game_with_that_pin&l=$l";
		header("Location: $url");
		exit;
	}
	
	// Check for duplicate names
	$query = "SELECT member_id FROM $t_dice_game_members WHERE member_game_id=$get_current_game_id AND member_name=$inp_name_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_member_id) = $row;
	if($get_member_id != ""){
		$url = "index.php?page=home&ft=error&fm=that_name_is_not_available&l=$l";
		header("Location: $url");
		exit;
	}

	// Get number_of_throws
	$query = "SELECT member_number_of_throws FROM $t_dice_game_members WHERE member_game_id=$get_current_game_id ORDER BY member_number_of_throws ASC";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_member_number_of_throws) = $row;
	if($get_member_number_of_throws == ""){
		$get_member_number_of_throws = 0;
	}


	// Member security
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$charactersLength = strlen($characters);
    	$inp_member_security = '';
    	for ($i = 0; $i < 6; $i++) {
        	$inp_member_security .= $characters[rand(0, $charactersLength - 1)];
    	}
	$inp_member_security = output_html($inp_member_security);
	$inp_member_security_mysql = quote_smart($link, $inp_member_security);

	
	// Insert member
	mysqli_query($link, "INSERT INTO $t_dice_game_members
				(member_id, member_game_id, member_name, member_ip, member_security_code, 
				member_number_of_throws, member_last_seen_time, member_joined_datetime, member_joined_saying) 
				VALUES 
				(NULL, $get_current_game_id, $inp_name_mysql, $my_ip_mysql, $inp_member_security_mysql, 
				$get_member_number_of_throws, '$time', '$datetime', '$datetime_saying')")
				or die(mysqli_error($link));

	// Get my member id
	$query = "SELECT member_id FROM $t_dice_game_members WHERE member_game_id=$get_current_game_id AND member_name=$inp_name_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_member_id) = $row;

	// Set session
	$_SESSION['dice_member_security_code'] = "$inp_member_security";



	// Header
	$url = "index.php?page=show_game&game_id=$get_current_game_id&member_id=$get_current_member_id&l=$l";
	header("Location: $url");
	exit;
}
if($action == "create_game"){
	// Title
	$inp_title = $_POST['inp_title'];
	$inp_title = output_html($inp_title);
	if($inp_title == ""){
		$url = "index.php?page=home&ft=error&fm=missing_title&l=$l";
		header("Location: $url");
		exit;
	}
	$inp_title_mysql = quote_smart($link, $inp_title);

	// Owner name
	$inp_name = $_POST['inp_name'];
	$inp_name = output_html($inp_name);
	if($inp_name == ""){
		$inp_name = "No name";
	}
	$inp_name_mysql = quote_smart($link, $inp_name);

	// No of dices
	$inp_number_of_dices = $_POST['inp_number_of_dices'];
	$inp_number_of_dices = output_html($inp_number_of_dices);
	if($inp_number_of_dices == ""){
		$inp_number_of_dices = "1";
	}
	$inp_number_of_dices_mysql = quote_smart($link, $inp_number_of_dices);

	// Password
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$charactersLength = strlen($characters);
    	$inp_password = '';
    	for ($i = 0; $i < 6; $i++) {
        	$inp_password .= $characters[rand(0, $charactersLength - 1)];
    	}
	$inp_password = output_html($inp_password);

	$inp_password_encrypted = sha1($inp_password);
	$inp_password_encrypted_mysql = quote_smart($link, $inp_password_encrypted);

	
	$inp_notes = "Password=$inp_password";
	$inp_notes_mysql = quote_smart($link, $inp_notes);

	// Security
    	$inp_security = '';
    	for ($i = 0; $i < 6; $i++) {
        	$inp_security .= $characters[rand(0, $charactersLength - 1)];
    	}
	$inp_security = output_html($inp_security);
	$inp_security_mysql = quote_smart($link, $inp_security);

	// Member Security
    	$inp_member_security = '';
    	for ($i = 0; $i < 6; $i++) {
        	$inp_member_security .= $characters[rand(0, $charactersLength - 1)];
    	}
	$inp_member_security = output_html($inp_member_security);
	$inp_member_security_mysql = quote_smart($link, $inp_member_security);


	// PIN
	$found_pin = 0;
	while($found_pin < 1){
    		$inp_pin = rand(100000, 999999);
		$query = "SELECT game_id FROM $t_dice_game_index WHERE game_join_pin=$inp_pin";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_game_id) = $row;
		if($get_game_id == ""){
			$found_pin = 1;
    		}
	}
	$inp_pin_mysql = quote_smart($link, $inp_pin);
	
	// Datetime
	$datetime_saying = date("j M Y H:i");

	
	// Create game
	mysqli_query($link, "INSERT INTO $t_dice_game_index
				(game_id, game_title, game_owner_name, game_owner_ip, game_password, 
				game_security_code, game_join_pin, game_number_of_dices, game_created_datetime, game_created_saying, 
				game_notes) 
				VALUES 
				(NULL, $inp_title_mysql, $inp_name_mysql, $my_ip_mysql, $inp_password_encrypted_mysql, 
				$inp_security_mysql, $inp_pin_mysql, $inp_number_of_dices_mysql, '$datetime', '$datetime_saying', $inp_notes_mysql)")
				or die(mysqli_error($link));

	// Get ID
	$query = "SELECT game_id FROM $t_dice_game_index WHERE game_created_datetime='$datetime'";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_game_id) = $row;

	// Set session
	$_SESSION['dice_game_security_code'] = "$inp_security";
	$_SESSION['dice_member_security_code'] = "$inp_member_security";
	

	// Insert me as member
	mysqli_query($link, "INSERT INTO $t_dice_game_members
				(member_id, member_game_id, member_name, member_ip, member_security_code, 
				member_number_of_throws, member_last_seen_time, member_joined_datetime, member_joined_saying) 
				VALUES 
				(NULL, $get_current_game_id, $inp_name_mysql, $my_ip_mysql, $inp_member_security_mysql, 
				0, '$time', '$datetime', '$datetime_saying')")
				or die(mysqli_error($link));
	

	// Get member ID
	$query = "SELECT member_id FROM $t_dice_game_members WHERE member_security_code=$inp_member_security_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_member_id) = $row;


	// Delete old games (older than x weeks)
	$two_weeks = date("Y-m-d", strtotime("- 2 weeks"));
	$query = "SELECT game_id FROM $t_dice_game_index WHERE game_created_datetime < '$two_weeks'";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_game_id) = $row;
		
		mysqli_query($link, "DELETE FROM $t_dice_game_index WHERE game_id=$get_game_id") or die(mysqli_error($link));
		mysqli_query($link, "DELETE FROM $t_dice_game_members WHERE member_game_id=$get_game_id") or die(mysqli_error($link));
	}


	// Header
	$url = "index.php?page=show_game&game_id=$get_current_game_id&member_id=$get_current_member_id&l=$l";
	header("Location: $url");
	exit;
}
if($action == ""){
	echo"
	<h1>$l_throw_the_dice</h1>


	<!-- Feedback -->
		";
		if($ft != ""){
			$fm = str_replace("_", " ", $fm);
			$fm = ucfirst($fm);
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
	<!-- //Feedback -->

	<!-- Join game -->
		<h2>$l_join_game</h2>
		<section>

		<!-- Focus -->
			<script>
			window.onload = function() {
				document.getElementById(\"inp_pin\").focus();
			}
			</script>
		<!-- //Focus -->

		<form method=\"post\" action=\"index.php?page=home&amp;action=join_game&amp;process=1&amp;l=$l\" enctype=\"multipart/form-data\">

		<p><b>$l_pin:</b>
		<input type=\"text\" name=\"inp_pin\" value=\"\" size=\"6\" id=\"inp_pin\" />
		</p>

		<p><b>$l_your_name:</b>
		<input type=\"text\" name=\"inp_name\" value=\"\" size=\"20\" />
		</p>

		<p>
		<input type=\"submit\" value=\"$l_join\" class=\"btn_default\" />
		</p>	

		</form>
		</section>
	<!-- //Join game -->

	<!-- Create game -->
		<h2>$l_create_game</h2>
		<section>


		<form method=\"post\" action=\"index.php?page=home&amp;action=create_game&amp;process=1&amp;l=$l\" enctype=\"multipart/form-data\">

		<p><b>$l_game_title:</b>
		<input type=\"text\" name=\"inp_title\" value=\"\" size=\"20\" />
		</p>

		<p><b>$l_your_name:</b>
		<input type=\"text\" name=\"inp_name\" value=\"\" size=\"20\" />
		</p>

		<p><b>$l_number_of_dices:</b><br />
		<input type=\"radio\" name=\"inp_number_of_dices\" id=\"inp_number_of_dices\" value=\"1\" checked=\"checked\" /> 1
		&nbsp;
		<input type=\"radio\" name=\"inp_number_of_dices\" value=\"2\" /> 2
		</p>

		<p>
		<input type=\"submit\" value=\"$l_create_game\" class=\"btn_default\" />
		</p>	

		</form>
		</section>
	<!-- //Create game -->

	";
}


?>