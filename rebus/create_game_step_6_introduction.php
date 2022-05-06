<?php
/**
*
* File: rebus/create_game_step_6_introduction.php
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
	$query = "SELECT game_id, game_title, game_language, game_introduction, game_description, game_privacy, game_published, game_group_id, game_group_name, game_times_played, game_image_path, game_image_file, game_created_by_user_id, game_created_by_user_name, game_created_by_user_email, game_created_by_ip, game_created_by_hostname, game_created_by_user_agent, game_created_datetime, game_created_date_saying, game_updated_by_user_id, game_updated_by_user_name, game_updated_by_user_email, game_updated_by_ip, game_updated_by_hostname, game_updated_by_user_agent, game_updated_datetime, game_updated_date_saying FROM $t_rebus_games_index WHERE game_id=$game_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_game_id, $get_current_game_title, $get_current_game_language, $get_current_game_introduction, $get_current_game_description, $get_current_game_privacy, $get_current_game_published, $get_current_game_group_id, $get_current_game_group_name, $get_current_game_times_played, $get_current_game_image_path, $get_current_game_image_file, $get_current_game_created_by_user_id, $get_current_game_created_by_user_name, $get_current_game_created_by_user_email, $get_current_game_created_by_ip, $get_current_game_created_by_hostname, $get_current_game_created_by_user_agent, $get_current_game_created_datetime, $get_current_game_created_date_saying, $get_current_game_updated_by_user_id, $get_current_game_updated_by_user_name, $get_current_game_updated_by_user_email, $get_current_game_updated_by_ip, $get_current_game_updated_by_hostname, $get_current_game_updated_by_user_agent, $get_current_game_updated_datetime, $get_current_game_updated_date_saying) = $row;
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
	$website_title = "$l_introduction - $get_current_game_title - $l_create_game";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");
	
	if($action == ""){
		if($process == "1"){
			$inp_introduction = $_POST['inp_introduction'];
			$inp_introduction = output_html($inp_introduction);
			$inp_introduction_mysql = quote_smart($link, $inp_introduction);


			// Me
			$query = "SELECT user_id, user_email, user_name, user_language, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_language, $get_my_user_rank) = $row;
			
			$inp_my_user_name_mysql = quote_smart($link, $get_my_user_name);
			$inp_my_user_email_mysql = quote_smart($link, $get_my_user_email);

			// Ip 
			$my_ip = $_SERVER['REMOTE_ADDR'];
			$my_ip = output_html($my_ip);
			$my_ip_mysql = quote_smart($link, $my_ip);

			$my_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
			$my_hostname = output_html($my_hostname);
			$my_hostname_mysql = quote_smart($link, $my_hostname);

			$my_user_agent = $_SERVER['HTTP_USER_AGENT'];
			$my_user_agent = output_html($my_user_agent);
			$my_user_agent_mysql = quote_smart($link, $my_user_agent);
	
			// Dates
			$datetime = date("Y-m-d H:i:s");
			$date_saying = date("j M Y");

			mysqli_query($link, "UPDATE $t_rebus_games_index SET
						game_introduction=$inp_introduction_mysql,
						game_updated_by_user_id=$get_my_user_id,
					 	game_updated_by_user_name=$inp_my_user_name_mysql,
					 	game_updated_by_user_email=$inp_my_user_email_mysql,
					 	game_updated_by_ip=$my_ip_mysql,
					 	game_updated_by_hostname=$my_hostname_mysql,
					 	game_updated_by_user_agent=$my_user_agent_mysql,
					 	game_updated_datetime='$datetime',
					 	game_updated_date_saying='$date_saying'
						WHERE game_id=$get_current_game_id")
						or die(mysqli_error($link));

			// Header
			$url = "create_game_step_7_image.php?game_id=$get_current_game_id&l=$l&ft=success&fm=introduction_saved";
			header("Location: $url");
			exit;

		} // process == 1

		echo"
		<section>
		<!-- Headline -->
			<h1>$get_current_game_title</h1>
		<!-- //Headline -->

		<!-- Where am I ? -->
			<p><b>$l_you_are_here:</b><br />
			<a href=\"index.php?l=$l\">$l_rebus</a>
			&gt;
			<a href=\"my_games.php?l=$l\">$l_my_games</a>
			&gt;
			<a href=\"my_games_view_game.php?game_id=$get_current_game_id&amp;l=$l\">$get_current_game_title</a>
			&gt;
			<a href=\"my_games_edit_introduction.php?game_id=$get_current_game_id&amp;l=$l\">$l_introduction</a>
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

		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_introduction\"]').focus();
			});
			</script>
		<!-- //Focus -->

		<!-- Introduction form -->
			<form method=\"post\" action=\"create_game_step_6_introduction.php?game_id=$get_current_game_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">


			<p><b>$l_game_introduction:</b><br />
			$l_please_explain_in_a_few_sentences_what_your_game_is_about_who_is_it_for_where_it_can_be_played_and_how_to_play_it. <br />
			<textarea name=\"inp_introduction\" rows=\"6\" cols=\"40\" style=\"width:99%;\">"; 
			$get_current_game_introduction = str_replace("<br />", "\n", $get_current_game_introduction);
			echo"$get_current_game_introduction</textarea>
			</p>

			<p><input type=\"submit\" value=\"$l_save\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></p>

			</form>
			
		<!-- //Add question form -->
		</section>
		";
	} // action == ""
}
else{
	echo"
	<h1>
	<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=rebus/team_new.php\">

	<p>Please log in...</p>
	";
}

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>