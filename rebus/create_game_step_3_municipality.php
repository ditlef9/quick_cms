<?php
/**
*
* File: rebus/create_game_step_3_municipality.php
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
	$query = "SELECT game_id, game_title, game_language, game_introduction, game_description, game_privacy, game_published, game_playable_after_datetime, game_playable_after_time, game_group_id, game_group_name, game_times_played, game_image_path, game_image_file, game_country_id, game_country_name, game_county_id, game_county_name, game_municipality_id, game_municipality_name, game_city_id, game_city_name, game_place_id, game_place_name, game_created_by_user_id, game_created_by_user_name, game_created_by_user_email, game_created_by_ip, game_created_by_hostname, game_created_by_user_agent, game_created_datetime, game_created_date_saying, game_updated_by_user_id, game_updated_by_user_name, game_updated_by_user_email, game_updated_by_ip, game_updated_by_hostname, game_updated_by_user_agent, game_updated_datetime, game_updated_date_saying FROM $t_rebus_games_index WHERE game_id=$game_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_game_id, $get_current_game_title, $get_current_game_language, $get_current_game_introduction, $get_current_game_description, $get_current_game_privacy, $get_current_game_published, $get_current_game_playable_after_datetime, $get_current_game_playable_after_time, $get_current_game_group_id, $get_current_game_group_name, $get_current_game_times_played, $get_current_game_image_path, $get_current_game_image_file, $get_current_game_country_id, $get_current_game_country_name, $get_current_game_county_id, $get_current_game_county_name, $get_current_game_municipality_id, $get_current_game_municipality_name, $get_current_game_city_id, $get_current_game_city_name, $get_current_game_place_id, $get_current_game_place_name, $get_current_game_created_by_user_id, $get_current_game_created_by_user_name, $get_current_game_created_by_user_email, $get_current_game_created_by_ip, $get_current_game_created_by_hostname, $get_current_game_created_by_user_agent, $get_current_game_created_datetime, $get_current_game_created_date_saying, $get_current_game_updated_by_user_id, $get_current_game_updated_by_user_name, $get_current_game_updated_by_user_email, $get_current_game_updated_by_ip, $get_current_game_updated_by_hostname, $get_current_game_updated_by_user_agent, $get_current_game_updated_datetime, $get_current_game_updated_date_saying) = $row;
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
	$website_title = "$l_municipality - $get_current_game_title - $l_create_game";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");
	
	if($action == ""){
		
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
			<a href=\"edit_game.php?game_id=$get_current_game_id&amp;l=$l\">$get_current_game_title</a>
			&gt;
			<a href=\"edit_game_general.php?game_id=$get_current_game_id&amp;l=$l\">$l_municipality</a>
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
				\$('[name=\"inp_municipality_id\"]').focus();
			});
			</script>
		<!-- //Focus -->

		<!-- Use existing municipality form -->
			<h2 style=\"padding-bottom:0;margin-bottom:0;\">$l_use_existing_municipality</h2>
			<form method=\"post\" action=\"create_game_step_3_municipality.php?action=use_existing_municipality&amp;game_id=$get_current_game_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>$l_game_can_be_played_in_municipality_of $get_current_game_county_name:</b><br />
			<select name=\"inp_municipality_id\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\">";
			$query = "SELECT municipality_id, municipality_name FROM $t_rebus_games_geo_municipalities WHERE municipality_country_id=$get_current_game_country_id AND municipality_county_id=$get_current_game_county_id ORDER BY municipality_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_municipality_id, $get_municipality_name) = $row;
				echo"<option value=\"$get_municipality_id\""; if($get_municipality_id == "$get_current_game_municipality_id"){ echo" selected=\"selected\""; } echo">$get_municipality_name</option>\n";
			}
			echo"
			</select>
			<input type=\"submit\" value=\"$l_use_municipality\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></p>
			</form>
		<!-- //Use existing municipality form -->

		<!-- Add new county form -->
			<h2 style=\"padding-bottom:0;margin-bottom:0;\">$l_add_new_municipality</h2>
			<form method=\"post\" action=\"create_game_step_3_municipality.php?action=add_new_municipality&amp;game_id=$get_current_game_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>$l_new_municipality_name:</b><br />
			<input type=\"text\" name=\"inp_name\" size=\"25\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			<input type=\"submit\" value=\"$l_add_municipality\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></p>
			</form>
		<!-- //Add new county form -->
		</section>
		";
	} // action == ""
	elseif($action == "use_existing_municipality"){
		$inp_municipality_id = $_POST['inp_municipality_id'];
		$inp_municipality_id = output_html($inp_municipality_id);
		$inp_municipality_id_mysql = quote_smart($link, $inp_municipality_id);

		$query = "SELECT municipality_id, municipality_name FROM $t_rebus_games_geo_municipalities WHERE municipality_id=$inp_municipality_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_municipality_id, $get_municipality_name) = $row;
		if($get_municipality_id == ""){
			$url = "create_game_step_3_municipality.php?game_id=$get_current_game_id&l=$l&ft=error&fm=no_municipality_selected";
			header("Location: $url");
			exit;
		}
		$inp_municipality_name_mysql = quote_smart($link, $get_municipality_name);
		
		// Use county
		mysqli_query($link, "UPDATE $t_rebus_games_index SET
					game_municipality_id=$inp_municipality_id_mysql,
					game_municipality_name=$inp_municipality_name_mysql
					WHERE game_id=$get_current_game_id")
					or die(mysqli_error($link));
		
		// Header	
		$url = "create_game_step_4_city.php?game_id=$get_current_game_id&l=$l&ft=success&fm=municipality_selected";
		header("Location: $url");
		exit;
		
	} // action == use_existing_municipality
	elseif($action == "add_new_municipality"){
		// Dates
		$datetime = date("Y-m-d H:i:s");
		$date_saying = date("j M Y");

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
	

		// Name
		$inp_name = $_POST['inp_name'];
		$inp_name = output_html($inp_name);
		$inp_name_mysql = quote_smart($link, $inp_name);
		if($inp_name == ""){
			$url = "create_game_step_3_municipality.php?game_id=$get_current_game_id&l=$l&ft=error&fm=no_name_specified";
			header("Location: $url");
			exit;
		}
		
		// Look for duplicates
		$query = "SELECT municipality_id, municipality_name FROM $t_rebus_games_geo_municipalities WHERE municipality_name=$inp_name_mysql AND municipality_country_id=$get_current_game_country_id AND municipality_county_id=$get_current_game_county_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_municipality_id, $get_municipality_name) = $row;
		if($get_municipality_id != ""){
			$url = "create_game_step_3_municipality.php?game_id=$get_current_game_id&l=$l&ft=error&fm=that_municipality_already_exists";
			header("Location: $url");
			exit;
		}

		// Country and county
		$inp_country_name_mysql = quote_smart($link, $get_current_game_country_name);
		$inp_county_name_mysql = quote_smart($link, $get_current_game_county_name);
	
		// Insert municipality
		mysqli_query($link, "INSERT INTO $t_rebus_games_geo_municipalities
		(municipality_id, municipality_name, municipality_country_id, municipality_country_name, municipality_county_id, 
		municipality_county_name, municipality_created_by_user_id, municipality_created_by_user_name, municipality_created_by_user_email, municipality_created_by_ip, 
		municipality_created_by_hostname, municipality_created_by_user_agent) 
		VALUES 
		(NULL, $inp_name_mysql, $get_current_game_country_id, $inp_country_name_mysql, $get_current_game_county_id, 
		$inp_county_name_mysql, $get_my_user_id, $inp_my_user_name_mysql, $inp_my_user_email_mysql, $my_ip_mysql,
		$my_hostname_mysql, $my_user_agent_mysql)")
		or die(mysqli_error($link));

		// Find county ID
		$query = "SELECT municipality_id, municipality_name FROM $t_rebus_games_geo_municipalities WHERE municipality_name=$inp_name_mysql AND municipality_country_id=$get_current_game_country_id AND municipality_county_id=$get_current_game_county_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_municipality_id, $get_municipality_name) = $row;
		
		
		// Use county
		mysqli_query($link, "UPDATE $t_rebus_games_index SET
					game_municipality_id=$get_municipality_id,
					game_municipality_name=$inp_name_mysql
					WHERE game_id=$get_current_game_id")
					or die(mysqli_error($link));
		
		// Header	
		$url = "create_game_step_4_city.php?game_id=$get_current_game_id&l=$l&ft=success&fm=municipality_added";
		header("Location: $url");
		exit;
		
	} // action == use_existing_municipality
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