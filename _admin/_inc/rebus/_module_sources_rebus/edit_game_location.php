<?php
/**
*
* File: rebus/my_games_edit_location.php
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
	$query = "SELECT game_id, game_title, game_language, game_introduction, game_description, game_privacy, game_difficulty, game_age_limit, game_published, game_playable_after_datetime, game_playable_after_datetime_saying, game_playable_after_time, game_group_id, game_group_name, game_times_played, game_times_finished, game_finished_percentage, game_time_used_seconds, game_time_used_saying, game_image_path, game_image_file, game_image_thumb_278x156, game_image_thumb_570x321, game_image_thumb_570x380, game_country_id, game_country_name, game_county_id, game_county_name, game_municipality_id, game_municipality_name, game_city_id, game_city_name, game_place_id, game_place_name, game_place_latitude, game_place_longitude, game_latitude, game_longitude, game_number_of_assignments, game_rating, game_created_by_user_id, game_created_by_user_name, game_created_by_user_email, game_created_by_ip, game_created_by_hostname, game_created_by_user_agent, game_created_datetime, game_created_date_saying, game_updated_by_user_id, game_updated_by_user_name, game_updated_by_user_email, game_updated_by_ip, game_updated_by_hostname, game_updated_by_user_agent, game_updated_datetime, game_updated_date_saying FROM $t_rebus_games_index WHERE game_id=$game_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_game_id, $get_current_game_title, $get_current_game_language, $get_current_game_introduction, $get_current_game_description, $get_current_game_privacy, $get_current_game_difficulty, $get_current_game_age_limit, $get_current_game_published, $get_current_game_playable_after_datetime, $get_current_game_playable_after_datetime_saying, $get_current_game_playable_after_time, $get_current_game_group_id, $get_current_game_group_name, $get_current_game_times_played, $get_current_game_times_finished, $get_current_game_finished_percentage, $get_current_game_time_used_seconds, $get_current_game_time_used_saying, $get_current_game_image_path, $get_current_game_image_file, $get_current_game_image_thumb_278x156, $get_current_game_image_thumb_570x321, $get_current_game_image_thumb_570x380, $get_current_game_country_id, $get_current_game_country_name, $get_current_game_county_id, $get_current_game_county_name, $get_current_game_municipality_id, $get_current_game_municipality_name, $get_current_game_city_id, $get_current_game_city_name, $get_current_game_place_id, $get_current_game_place_name, $get_current_game_place_latitude, $get_current_game_place_longitude, $get_current_game_latitude, $get_current_game_longitude, $get_current_game_number_of_assignments, $get_current_game_rating, $get_current_game_created_by_user_id, $get_current_game_created_by_user_name, $get_current_game_created_by_user_email, $get_current_game_created_by_ip, $get_current_game_created_by_hostname, $get_current_game_created_by_user_agent, $get_current_game_created_datetime, $get_current_game_created_date_saying, $get_current_game_updated_by_user_id, $get_current_game_updated_by_user_name, $get_current_game_updated_by_user_email, $get_current_game_updated_by_ip, $get_current_game_updated_by_hostname, $get_current_game_updated_by_user_agent, $get_current_game_updated_datetime, $get_current_game_updated_date_saying) = $row;
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
	$website_title = "$l_location - $get_current_game_title - $l_my_games";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");


	if($action == ""){
	
		if($process == "1"){
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
	
			// Country
			$inp_country_id = $_POST['inp_country_id'];
			$inp_country_id = output_html($inp_country_id);
			$inp_country_id_mysql = quote_smart($link, $inp_country_id);

			$query = "SELECT country_id, country_name FROM $t_languages_countries WHERE country_id=$inp_country_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_country_id, $get_country_name) = $row;
			if($get_country_id == ""){
				$url = "edit_game_location.php?game_id=$get_current_game_id&l=$l&ft=error&fm=no_country_selected";
				header("Location: $url");
				exit;
			}
			$inp_country_name_mysql = quote_smart($link, $get_country_name);

			// County
			$inp_county_id = $_POST['inp_county_id'];
			$inp_county_id = output_html($inp_county_id);
			$inp_county_id_mysql = quote_smart($link, $inp_county_id);

			$query = "SELECT county_id, county_name FROM $t_rebus_games_geo_counties WHERE county_id=$inp_county_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_county_id, $get_county_name) = $row;
			if($get_county_id == ""){
				$get_county_id = 0;
			}
			$inp_county_name_mysql = quote_smart($link, $get_county_name);


			// Municipality
			$inp_municipality_id = $_POST['inp_municipality_id'];
			$inp_municipality_id = output_html($inp_municipality_id);
			$inp_municipality_id_mysql = quote_smart($link, $inp_municipality_id);

			$query = "SELECT municipality_id, municipality_name FROM $t_rebus_games_geo_municipalities WHERE municipality_id=$inp_municipality_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_municipality_id, $get_municipality_name) = $row;
			if($get_municipality_id == ""){
				$get_municipality_id = 0;
			}
			$inp_municipality_name_mysql = quote_smart($link, $get_municipality_name);

			// City
			$inp_city_id = $_POST['inp_city_id'];
			$inp_city_id = output_html($inp_city_id);
			$inp_city_id_mysql = quote_smart($link, $inp_city_id);

			$query = "SELECT city_id, city_name FROM $t_rebus_games_geo_cities WHERE city_id=$inp_city_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_city_id, $get_city_name) = $row;
			if($get_city_id == ""){
				$get_city_id = 0;
			}
			$inp_city_name_mysql = quote_smart($link, $get_city_name);

			// Place
			$inp_place_id = $_POST['inp_place_id'];
			$inp_place_id = output_html($inp_place_id);
			$inp_place_id_mysql = quote_smart($link, $inp_place_id);
	
			$query = "SELECT place_id, place_name, place_latitude, place_longitude FROM $t_rebus_games_geo_places WHERE place_id=$inp_place_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_place_id, $get_place_name, $get_place_latitude, $get_place_longitude) = $row;
			if($get_place_id == ""){
				$get_place_id = 0;
			}
			$inp_place_name_mysql = quote_smart($link, $get_place_name);
			$inp_place_latitude_mysql = quote_smart($link, $get_place_latitude);
			$inp_place_longitude_mysql = quote_smart($link, $get_place_longitude);

			mysqli_query($link, "UPDATE $t_rebus_games_index SET
					game_country_id=$inp_country_id_mysql,
					game_country_name=$inp_country_name_mysql, 
					game_county_id=$inp_county_id_mysql,
					game_county_name=$inp_county_name_mysql, 
					game_municipality_id=$inp_municipality_id_mysql,
					game_municipality_name=$inp_municipality_name_mysql,
					game_city_id=$inp_city_id_mysql,
					game_city_name=$inp_city_name_mysql,
					game_place_id=$inp_place_id_mysql,
					game_place_name=$inp_place_name_mysql, 
					game_place_latitude=$inp_place_latitude_mysql, 
					game_place_longitude=$inp_place_longitude_mysql, 
					game_latitude=$inp_place_latitude_mysql, 
					game_longitude=$inp_place_longitude_mysql, 
					game_updated_by_user_id=$get_my_user_id,
					game_updated_by_user_name=$inp_my_user_name_mysql,
					game_updated_by_user_email=$inp_my_user_email_mysql,
					game_updated_by_ip=$my_ip_mysql,
					game_updated_by_hostname=$my_hostname_mysql,
					game_updated_by_user_agent=$my_user_agent_mysql,
					game_updated_datetime='$datetime',
					game_updated_date_saying='$date_saying'
					WHERE game_id=$get_current_game_id") or die(mysqli_error($link));


			// Truncate geo distance measurements
			mysqli_query($link, "TRUNCATE $t_rebus_games_index_geo_distance_measurements") or die(mysqli_error($link));


			// Header
			$url = "edit_game_location.php?game_id=$get_current_game_id&l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;
		}
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
			&gt;
			<a href=\"edit_game_location.php?game_id=$get_current_game_id&amp;l=$l\">$l_location</a>
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
	
		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
			</script>
		<!-- //Focus -->

		<!-- Location form -->
			<form method=\"post\" action=\"edit_game_location.php?game_id=$get_current_game_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<!-- On select send form -->
				<script>
				\$(function(){
					// bind change event to select
					\$('.on_select_submit_form').on('change', function () {
						this.form.submit();
						return false;
					});
				});
				</script>
			<!-- //On select send form -->

	
			<p><b>$l_country:</b><br />
			<select name=\"inp_country_id\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" class=\"on_select_submit_form\">\n";
			$query = "SELECT country_id, country_name FROM $t_rebus_games_geo_countries ORDER BY country_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_country_id, $get_country_name) = $row;
				echo"			";
				echo"<option value=\"$get_country_id\""; if($get_country_id == "$get_current_game_country_id"){ echo" selected=\"selected\""; } echo">$get_country_name</option>\n";
			}
			echo"
			</select></p>


			<p><b>$l_county:</b> (<a href=\"edit_game_location.php?action=new_county&amp;game_id=$get_current_game_id&amp;l=$l\">$l_new</a>)<br />
			<select name=\"inp_county_id\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" class=\"on_select_submit_form\">
				<option value=\"0\">- $l_please_select -</option>\n";
				$query = "SELECT county_id, county_name FROM $t_rebus_games_geo_counties WHERE county_country_id=$get_current_game_country_id ORDER BY county_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_county_id, $get_county_name) = $row;
				echo"<option value=\"$get_county_id\""; if($get_county_id == "$get_current_game_county_id"){ echo" selected=\"selected\""; } echo">$get_county_name</option>\n";
			}
			echo"
			</select>
			</p>

			<p><b>$l_municipality:</b> (<a href=\"edit_game_location.php?action=new_municipality&amp;game_id=$get_current_game_id&amp;l=$l\">$l_new</a>)<br />
			<select name=\"inp_municipality_id\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" class=\"on_select_submit_form\">
				<option value=\"0\">- $l_please_select -</option>\n";
			$query = "SELECT municipality_id, municipality_name FROM $t_rebus_games_geo_municipalities WHERE municipality_country_id=$get_current_game_country_id AND municipality_county_id=$get_current_game_county_id ORDER BY municipality_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_municipality_id, $get_municipality_name) = $row;
				echo"<option value=\"$get_municipality_id\""; if($get_municipality_id == "$get_current_game_municipality_id"){ echo" selected=\"selected\""; } echo">$get_municipality_name</option>\n";
			}
			echo"
			</select>
			</p>

			<p><b>$l_city:</b> (<a href=\"edit_game_location.php?action=new_city&amp;game_id=$get_current_game_id&amp;l=$l\">$l_new</a>)<br />
			<select name=\"inp_city_id\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" class=\"on_select_submit_form\">
			<option value=\"0\">- $l_please_select -</option>\n";
			$query = "SELECT city_id, city_name FROM $t_rebus_games_geo_cities WHERE city_country_id=$get_current_game_country_id AND city_county_id=$get_current_game_county_id AND city_municipality_id=$get_current_game_municipality_id ORDER BY city_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_city_id, $get_city_name) = $row;
				echo"<option value=\"$get_city_id\""; if($get_city_id == "$get_current_game_city_id"){ echo" selected=\"selected\""; } echo">$get_city_name</option>\n";
			}
			echo"
			</select>
			</p>

			<p><b>$l_place:</b> (<a href=\"edit_game_location.php?action=new_place&amp;game_id=$get_current_game_id&amp;l=$l\">$l_new</a>)<br />
			<select name=\"inp_place_id\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" class=\"on_select_submit_form\">
				<option value=\"0\">- $l_please_select -</option>\n";
			$query = "SELECT place_id, place_name FROM $t_rebus_games_geo_places WHERE place_country_id=$get_current_game_country_id AND place_county_id=$get_current_game_county_id AND place_municipality_id=$get_current_game_municipality_id AND place_city_id=$get_current_game_city_id ORDER BY place_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_place_id, $get_place_name) = $row;
				echo"<option value=\"$get_place_id\""; if($get_place_id == "$get_current_game_place_id"){ echo" selected=\"selected\""; } echo">$get_place_name</option>\n";
			}
			echo"
			</select>
			</p>

			<p style=\"padding-bottom:0;margin-bottom:0;\"><b>$l_game_coordinates:</b></p>
			<table>
			 <tr>
			  <td>
				<span>$l_latitude<br />
				<input type=\"text\" name=\"inp_latitude\" id=\"inp_latitude\" value=\"$get_current_game_latitude\" size=\"19\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /><br />
				</span>
			  </td>
			  <td>
				<span>
				$l_longitude<br />
				<input type=\"text\" name=\"inp_longitude\" id=\"inp_longitude\" value=\"$get_current_game_longitude\" size=\"19\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
				</span>
			  </td>
			 </tr>
			</table>
			<p><input type=\"submit\" value=\"$l_save_changes\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></p>
			
			</form>
		<!-- //Location form -->
		";
	} // action == ""
	elseif($action == "new_county"){
		if($process == "1"){
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
				$url = "create_game_step_2_county.php?game_id=$get_current_game_id&l=$l&ft=error&fm=no_name_specified";
				header("Location: $url");
				exit;
			}
		
			// Look for duplicates
			$query = "SELECT county_id, county_name FROM $t_rebus_games_geo_counties WHERE county_name=$inp_name_mysql AND county_country_id=$get_current_game_country_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_county_id, $get_county_name) = $row;
			if($get_county_id != ""){
				$url = "edit_game_location.php?action=$action&game_id=$get_current_game_id&l=$l&ft=error&fm=that_county_already_exists";
				header("Location: $url");
				exit;
			}

			// Country
			$inp_country_name_mysql = quote_smart($link, $get_current_game_country_name);
	
			// Insert county
			mysqli_query($link, "INSERT INTO $t_rebus_games_geo_counties
			(county_id, county_name, county_country_id, county_country_name, county_created_by_user_id, 
			county_created_by_user_name, county_created_by_user_email, county_created_by_ip, county_created_by_hostname, county_created_by_user_agent) 
			VALUES 
			(NULL, $inp_name_mysql, $get_current_game_country_id, $inp_country_name_mysql, $get_my_user_id, 
			$inp_my_user_name_mysql, $inp_my_user_email_mysql, $my_ip_mysql, $my_hostname_mysql, $my_user_agent_mysql)")
			or die(mysqli_error($link));

			// Find county ID
			$query = "SELECT county_id, county_name FROM $t_rebus_games_geo_counties WHERE county_name=$inp_name_mysql AND county_country_id=$get_current_game_country_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_county_id, $get_county_name) = $row;
		
		
			// Use county
			mysqli_query($link, "UPDATE $t_rebus_games_index SET
					game_county_id=$get_county_id,
					game_county_name=$inp_name_mysql
					WHERE game_id=$get_current_game_id")
					or die(mysqli_error($link));
		
			// Header	
			$url = "edit_game_location.php?game_id=$get_current_game_id&l=$l&ft=success&fm=county_added";
			header("Location: $url");
			exit;
		}
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
			&gt;
			<a href=\"edit_game_location.php?game_id=$get_current_game_id&amp;l=$l\">$l_location</a>
			&gt;
			<a href=\"edit_game_location.php?action=$action&amp;game_id=$get_current_game_id&amp;l=$l\">$l_new_county</a>
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
	
		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_name\"]').focus();
			});
			</script>
		<!-- //Focus -->

		<!-- Location form -->
			<form method=\"post\" action=\"edit_game_location.php?action=$action&amp;game_id=$get_current_game_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>$l_new_county_name:</b><br />
			<input type=\"text\" name=\"inp_name\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</p>

			<p><input type=\"submit\" value=\"$l_create\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></p>
			
			</form>
		<!-- //New county form -->
		";
	} // action == "new_county"
	elseif($action == "new_municipality"){
		if($process == "1"){
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
				$url = "create_game_step_2_county.php?game_id=$get_current_game_id&l=$l&ft=error&fm=no_name_specified";
				header("Location: $url");
				exit;
			}
		
			// Look for duplicates
			$query = "SELECT municipality_id, municipality_name FROM $t_rebus_games_geo_municipalities WHERE municipality_name=$inp_name_mysql AND municipality_country_id=$get_current_game_country_id AND municipality_county_id=$get_current_game_county_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_municipality_id, $get_municipality_name) = $row;
			if($get_municipality_id != ""){
				$url = "create_game_step_2_county.php?action=$action&game_id=$get_current_game_id&l=$l&ft=error&fm=that_municipality_already_exists";
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

			// Find municipality ID
			$query = "SELECT municipality_id, municipality_name FROM $t_rebus_games_geo_municipalities WHERE municipality_name=$inp_name_mysql AND municipality_country_id=$get_current_game_country_id AND municipality_county_id=$get_current_game_county_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_municipality_id, $get_municipality_name) = $row;
		
		
			// Use municipality
			mysqli_query($link, "UPDATE $t_rebus_games_index SET
					game_municipality_id=$get_municipality_id,
					game_municipality_name=$inp_name_mysql
					WHERE game_id=$get_current_game_id")
					or die(mysqli_error($link));
		
		
			// Header	
			$url = "edit_game_location.php?game_id=$get_current_game_id&l=$l&ft=success&fm=municipality_added";
			header("Location: $url");
			exit;
		}
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
			&gt;
			<a href=\"edit_game_location.php?game_id=$get_current_game_id&amp;l=$l\">$l_location</a>
			&gt;
			<a href=\"edit_game_location.php?action=$action&amp;game_id=$get_current_game_id&amp;l=$l\">$l_new_municipality</a>
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
	
		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_name\"]').focus();
			});
			</script>
		<!-- //Focus -->

		<!-- Location form -->
			<form method=\"post\" action=\"edit_game_location.php?action=$action&amp;game_id=$get_current_game_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>$l_new_municipality_name_in $get_current_game_county_name:</b><br />
			<input type=\"text\" name=\"inp_name\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</p>

			<p><input type=\"submit\" value=\"$l_create\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></p>
			
			</form>
		<!-- //New county form -->
		";
	} // action == "new_municipality"
	elseif($action == "new_city"){
		if($process == "1"){
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
				$url = "edit_game_location.php?action=$action&game_id=$get_current_game_id&l=$l&ft=error&fm=no_name_specified";
				header("Location: $url");
				exit;
			}
		
			// Look for duplicates
			$query = "SELECT city_id, city_name FROM $t_rebus_games_geo_cities WHERE city_name=$inp_name_mysql AND city_country_id=$get_current_game_country_id AND city_county_id=$get_current_game_county_id AND city_municipality_id=$get_current_game_municipality_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_city_id, $get_city_name) = $row;
			if($get_city_id != ""){
				$url = "edit_game_location.php?action=$action&game_id=$get_current_game_id&l=$l&ft=error&fm=that_city_already_exists";
				header("Location: $url");
				exit;
			}

			// Country and county
			$inp_country_name_mysql = quote_smart($link, $get_current_game_country_name);
			$inp_county_name_mysql = quote_smart($link, $get_current_game_county_name);
			$inp_municipality_name_mysql = quote_smart($link, $get_current_game_municipality_name);
	
			// Insert city
			mysqli_query($link, "INSERT INTO $t_rebus_games_geo_cities
			(city_id, city_name, city_country_id, city_country_name, city_county_id, 
			city_county_name, city_municipality_id, city_municipality_name, city_created_by_user_id, city_created_by_user_name, 
			city_created_by_user_email, city_created_by_ip, city_created_by_hostname, city_created_by_user_agent) 
			VALUES 
			(NULL, $inp_name_mysql, $get_current_game_country_id, $inp_country_name_mysql, $get_current_game_county_id, 
			$inp_county_name_mysql, $get_current_game_municipality_id, $inp_municipality_name_mysql, $get_my_user_id, $inp_my_user_name_mysql,
			$inp_my_user_email_mysql, $my_ip_mysql, $my_hostname_mysql, $my_user_agent_mysql)")
			or die(mysqli_error($link));

			// Find city ID
			$query = "SELECT city_id, city_name FROM $t_rebus_games_geo_cities WHERE city_name=$inp_name_mysql AND city_country_id=$get_current_game_country_id AND city_county_id=$get_current_game_county_id AND city_municipality_id=$get_current_game_municipality_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_city_id, $get_city_name) = $row;
		
		
			// Use city
			mysqli_query($link, "UPDATE $t_rebus_games_index SET
					game_city_id=$get_city_id,
					game_city_name=$inp_name_mysql
					WHERE game_id=$get_current_game_id")
					or die(mysqli_error($link));
		
			// Header	
			$url = "edit_game_location.php?game_id=$get_current_game_id&l=$l&ft=success&fm=city_added";
			header("Location: $url");
			exit;
		}
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
			&gt;
			<a href=\"edit_game_location.php?game_id=$get_current_game_id&amp;l=$l\">$l_location</a>
			&gt;
			<a href=\"edit_game_location.php?action=$action&amp;game_id=$get_current_game_id&amp;l=$l\">$l_new_city</a>
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
	
		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_name\"]').focus();
			});
			</script>
		<!-- //Focus -->

		<!-- Location form -->
			<form method=\"post\" action=\"edit_game_location.php?action=$action&amp;game_id=$get_current_game_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>$l_new_city_name_in $get_current_game_municipality_name:</b><br />
			<input type=\"text\" name=\"inp_name\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</p>

			<p><input type=\"submit\" value=\"$l_create\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></p>
			
			</form>
		<!-- //New county form -->
		";
	} // action == "new_city"
	elseif($action == "new_place"){
		if($process == "1"){
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
				$url = "edit_game_location.php?action=$action&game_id=$get_current_game_id&l=$l&ft=error&fm=no_name_specified";
				header("Location: $url");
				exit;
			}

			// Look for duplicates
			$query = "SELECT place_id, place_name FROM $t_rebus_games_geo_places WHERE place_name=$inp_name_mysql AND place_country_id=$get_current_game_country_id AND place_county_id=$get_current_game_county_id AND place_municipality_id=$get_current_game_municipality_id AND place_city_id=$get_current_game_city_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_place_id, $get_place_name) = $row;
			if($get_place_id != ""){
				$url = "edit_game_location.php?action=$action&game_id=$get_current_game_id&l=$l&ft=error&fm=that_place_already_exists";
				header("Location: $url");
				exit;
			}

			// Country and county
			$inp_country_name_mysql = quote_smart($link, $get_current_game_country_name);
			$inp_county_name_mysql = quote_smart($link, $get_current_game_county_name);	
			$inp_municipality_name_mysql = quote_smart($link, $get_current_game_municipality_name);
			$inp_city_name_mysql = quote_smart($link, $get_current_game_city_name);
	
			// Insert place
			mysqli_query($link, "INSERT INTO $t_rebus_games_geo_places
			(place_id, place_name, place_country_id, place_country_name, place_county_id, 
			place_county_name, place_municipality_id, place_municipality_name, place_city_id, place_city_name, 
			place_created_by_user_id, place_created_by_user_name, place_created_by_user_email, place_created_by_ip, place_created_by_hostname, 
			place_created_by_user_agent) 
			VALUES 
			(NULL, $inp_name_mysql, $get_current_game_country_id, $inp_country_name_mysql, $get_current_game_county_id, 
			$inp_county_name_mysql, $get_current_game_municipality_id, $inp_municipality_name_mysql, $get_current_game_city_id, $inp_city_name_mysql, 
			$get_my_user_id, $inp_my_user_name_mysql, $inp_my_user_email_mysql, $my_ip_mysql, $my_hostname_mysql,
			 $my_user_agent_mysql)")
			or die(mysqli_error($link));

			// Find place ID
			$query = "SELECT place_id, place_name FROM $t_rebus_games_geo_places WHERE place_name=$inp_name_mysql AND place_country_id=$get_current_game_country_id AND place_county_id=$get_current_game_county_id AND place_municipality_id=$get_current_game_municipality_id AND place_city_id=$get_current_game_city_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_place_id, $get_place_name) = $row;
		
		
			// Use place
			mysqli_query($link, "UPDATE $t_rebus_games_index SET
					game_place_id=$get_place_id,
					game_place_name=$inp_name_mysql
					WHERE game_id=$get_current_game_id")
					or die(mysqli_error($link));
		
			// Header	
			$url = "edit_game_location.php?action=select_coordinates_for_place&game_id=$get_current_game_id&l=$l&ft=success&fm=place_added";
			header("Location: $url");
			exit;
		}
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
			&gt;
			<a href=\"edit_game_location.php?game_id=$get_current_game_id&amp;l=$l\">$l_location</a>
			&gt;
			<a href=\"edit_game_location.php?action=$action&amp;game_id=$get_current_game_id&amp;l=$l\">$l_new_place</a>
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
	
		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_name\"]').focus();
			});
			</script>
		<!-- //Focus -->

		<!-- New place form -->
			<form method=\"post\" action=\"edit_game_location.php?action=$action&amp;game_id=$get_current_game_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>$l_new_place_name_in $get_current_game_city_name:</b><br />
			<input type=\"text\" name=\"inp_name\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</p>

			<p><input type=\"submit\" value=\"$l_create\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></p>
			
			</form>
		<!-- //New place form -->
		";
	} // action == "new_place"
	elseif($action == "select_coordinates_for_place"){
		// Get place
		$query = "SELECT place_id, place_name, place_latitude, place_longitude FROM $t_rebus_games_geo_places WHERE place_id=$get_current_game_place_id AND place_created_by_user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_place_id, $get_current_place_name, $get_current_place_latitude, $get_current_place_longitude) = $row;

		if($get_current_place_id == ""){
			echo"Could not find place";
		}
		else{

			if($process == "1"){
				$inp_latitude = $_POST['inp_latitude'];
				$inp_latitude = output_html($inp_latitude);
				$inp_latitude_mysql = quote_smart($link, $inp_latitude);

				$inp_longitude = $_POST['inp_longitude'];
				$inp_longitude = output_html($inp_longitude);
				$inp_longitude_mysql = quote_smart($link, $inp_longitude);

				if($inp_latitude == "" OR $inp_longitude == ""){
					// Header	
					$url = "edit_game_location.php?action=select_coordinates_for_place&game_id=$get_current_game_id&l=$l&ft=error&fm=please_add_coordinates";
					header("Location: $url");
					exit;
				}
				

				mysqli_query($link, "UPDATE $t_rebus_games_index SET
					game_place_latitude=$inp_latitude_mysql,
					game_place_longitude=$inp_longitude_mysql,
					game_latitude=$inp_latitude_mysql,
					game_longitude=$inp_longitude_mysql
					WHERE game_id=$get_current_game_id")
					or die(mysqli_error($link));

				mysqli_query($link, "UPDATE $t_rebus_games_geo_places SET
					place_latitude=$inp_latitude_mysql,
					place_longitude=$inp_longitude_mysql
					WHERE place_id=$get_current_place_id")
					or die(mysqli_error($link));
		

				// Header	
				$url = "edit_game_location.php?game_id=$get_current_game_id&l=$l&ft=success&fm=coordinates_saved";
				header("Location: $url");
				exit;
			}
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
			&gt;
			<a href=\"edit_game_location.php?game_id=$get_current_game_id&amp;l=$l\">$l_location</a>
			&gt;
			<a href=\"edit_game_location.php?action=$action&amp;game_id=$get_current_game_id&amp;l=$l\">$l_select_coordinates_for_place</a>
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
	
			<!-- Focus -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_name\"]').focus();
				});
				</script>
			<!-- //Focus -->

			<!-- Coordinates for place form -->

				<p>$l_please_select_coordinates_for $get_current_place_name:</p>
			<form method=\"post\" action=\"edit_game_location.php?action=$action&amp;game_id=$get_current_game_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">


				<!-- leaflet -->
				";
				// Get my last used coordinate, if it doesnt exist, then get the last used in my country
				if($get_current_place_latitude == "" OR $get_current_place_longitude == ""){
					$query = "SELECT assignment_id, assignment_answer_a, assignment_answer_b FROM $t_rebus_games_assignments WHERE assignment_type='take_a_picture_with_coordinates' AND assignment_created_by_user_id=$my_user_id_mysql ORDER BY assignment_id DESC LIMIT 0,1";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_last_assignment_id, $get_last_assignment_answer_a, $get_last_assignment_answer_b) = $row;
					if($get_last_assignment_id == "" OR $get_last_assignment_answer_a == ""){
						$get_current_place_latitude = "51.505";
						$get_current_place_longitude = "-0.09";
					}
					else{
						$get_current_place_latitude = "$get_last_assignment_answer_a";
						$get_current_place_longitude = "$get_last_assignment_answer_b";
					}
				}

				echo"
				<script src=\"$root/_admin/_javascripts/leaflet/leaflet.js\" crossorigin=\"\"></script>

				<div id=\"map\" style=\"width: 100%; height: 400px;\"></div>

				<!-- Add game assignment - Map script -->
				<script>";
					if($get_current_place_latitude == "" OR $get_current_place_longitude == ""){
						echo"
						var map = L.map('map').fitWorld();

						L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
							maxZoom: 18,
							attribution: 'Map data &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors, ' +
							'Imagery © <a href=\"https://www.mapbox.com/\">Mapbox</a>',
							id: 'mapbox/streets-v11',
							tileSize: 512,
							zoomOffset: -1
							}).addTo(map);

							function onLocationFound(e) {
							var radius = e.accuracy / 2;

							L.marker(e.latlng).addTo(map)
							.bindPopup(\"Your location\").openPopup();

							L.circle(e.latlng, radius).addTo(map);
							}

							function onLocationError(e) {
							alert(e.message);
							}

							map.on('locationfound', onLocationFound);
							map.on('locationerror', onLocationError);

							map.locate({setView: true, maxZoom: 16});

						";
					}
					else{
							echo"
							var map = L.map('map').setView([$get_current_place_latitude, $get_current_place_longitude], 13);
				
							L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
								maxZoom: 18,
								attribution: 'Map data &copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors, ' +
								'Imagery © <a href=\"https://www.mapbox.com/\">Mapbox</a>',
								id: 'mapbox/streets-v11',
								tileSize: 512,
								zoomOffset: -1
							}).addTo(map);


						";
					}
					echo"


						var popup = L.popup();
						function onMapClick(e) {
							popup
							.setLatLng(e.latlng)
							.setContent(\"You clicked the map at \" + e.latlng.toString())
							.openOn(map);

							// Fetch coordinates
							var coordinates = e.latlng.toString();
							coordinates = coordinates.replace(\"LatLng(\", \"\"); 
							coordinates = coordinates.replace(\")\", \"\"); 
							coordinates = coordinates.replace(\" \", \"\"); 

							// Split coordinates to lat and lng
							var coordinates_split = coordinates.split(\",\");

							document.getElementById(\"inp_latitude\").value=coordinates_split[0];
							document.getElementById(\"inp_longitude\").value=coordinates_split[1];
						}
						map.on('click', onMapClick);


					</script>
				<!-- //Add game assignment - Map script -->
				<!-- //leaflet -->

				<p style=\"padding-bottom:0;margin-bottom:0;\"><b>$l_coordinates*:</b></p>
				<table>
				 <tr>
				  <td>
					<span>$l_latitude<br />
					<input type=\"text\" name=\"inp_latitude\" id=\"inp_latitude\" value=\"$get_current_place_latitude\" size=\"10\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /><br />
					</span>
				  </td>
				  <td>
					<span>
					$l_longitude<br />
					<input type=\"text\" name=\"inp_longitude\" id=\"inp_longitude\" value=\"$get_current_place_longitude\" size=\"10\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
					</span>
				  </td>
				 </tr>
				</table>



				<p><input type=\"submit\" value=\"$l_save\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></p>
				</form>
			</form>
			<!-- //New place form -->
			";
		} // place found
	} // action == "select_coordinates_for_place"
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