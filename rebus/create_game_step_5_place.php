<?php
/**
*
* File: rebus/create_game_step_5_place.php
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
	$website_title = "$l_place - $get_current_game_title - $l_create_game";
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
			<a href=\"edit_game_general.php?game_id=$get_current_game_id&amp;l=$l\">$l_place</a>
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
				\$('[name=\"inp_place_id\"]').focus();
			});
			</script>
		<!-- //Focus -->

		<!-- Use existing place form -->
			<h2 style=\"padding-bottom:0;margin-bottom:0;\">$l_use_existing_place</h2>
			<form method=\"post\" action=\"create_game_step_5_place.php?action=use_existing_place&amp;game_id=$get_current_game_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>$l_game_can_be_played_in_place_of $get_current_game_city_name:</b><br />
			<select name=\"inp_place_id\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\">";
			$query = "SELECT place_id, place_name FROM $t_rebus_games_geo_places WHERE place_country_id=$get_current_game_country_id AND place_county_id=$get_current_game_county_id AND place_municipality_id=$get_current_game_municipality_id AND place_city_id=$get_current_game_city_id ORDER BY place_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_place_id, $get_place_name) = $row;
				echo"<option value=\"$get_place_id\""; if($get_place_id == "$get_current_game_place_id"){ echo" selected=\"selected\""; } echo">$get_place_name</option>\n";
			}
			echo"
			</select>
			<input type=\"submit\" value=\"$l_use_place\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></p>
			</form>
		<!-- //Use existing place form -->

		<!-- Add new place form -->
			<h2 style=\"padding-bottom:0;margin-bottom:0;\">$l_add_new_place</h2>
			<form method=\"post\" action=\"create_game_step_5_place.php?action=add_new_place&amp;game_id=$get_current_game_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>$l_new_place_name:</b><br />
			<input type=\"text\" name=\"inp_name\" size=\"25\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			<input type=\"submit\" value=\"$l_add_place\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></p>
			</form>
		<!-- //Add new place form -->
		</section>
		";
	} // action == ""
	elseif($action == "use_existing_place"){
		$inp_place_id = $_POST['inp_place_id'];
		$inp_place_id = output_html($inp_place_id);
		$inp_place_id_mysql = quote_smart($link, $inp_place_id);

		$query = "SELECT place_id, place_name, place_latitude, place_longitude FROM $t_rebus_games_geo_places WHERE place_id=$inp_place_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_place_id, $get_place_name, $get_place_latitude, $get_place_longitude) = $row;
		if($get_place_id == ""){
			$url = "create_game_step_5_place.php?game_id=$get_current_city_id&l=$l&ft=error&fm=no_place_selected";
			header("Location: $url");
			exit;
		}
		$inp_place_name_mysql = quote_smart($link, $get_place_name);
		$inp_place_latitude_mysql = quote_smart($link, $get_place_latitude);
		$inp_place_longitude_mysql = quote_smart($link, $get_place_longitude);
		
		// Use place
		mysqli_query($link, "UPDATE $t_rebus_games_index SET
					game_place_id=$inp_place_id_mysql,
					game_place_name=$inp_place_name_mysql,
					game_place_latitude=$inp_place_latitude_mysql,
					game_place_longitude=$inp_place_longitude_mysql,
					game_latitude=$inp_place_latitude_mysql,
					game_longitude=$inp_place_longitude_mysql
					WHERE game_id=$get_current_game_id")
					or die(mysqli_error($link));
		
		// Truncate geo distance measurements
		mysqli_query($link, "TRUNCATE $t_rebus_games_index_geo_distance_measurements") or die(mysqli_error($link));

		// Header	
		$url = "create_game_step_6_introduction.php?game_id=$get_current_game_id&l=$l&ft=success&fm=place_selected";
		header("Location: $url");
		exit;
		
	} // action == use_existing_place
	elseif($action == "add_new_place"){
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
			$url = "create_game_step_5_place.php?game_id=$get_current_game_id&l=$l&ft=error&fm=no_name_specified";
			header("Location: $url");
			exit;
		}
		
		// Look for duplicates
		$query = "SELECT place_id, place_name FROM $t_rebus_games_geo_places WHERE place_name=$inp_name_mysql AND place_country_id=$get_current_game_country_id AND place_county_id=$get_current_game_county_id AND place_municipality_id=$get_current_game_municipality_id AND place_city_id=$get_current_game_city_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_place_id, $get_place_name) = $row;
		if($get_place_id != ""){
			$url = "create_game_step_5_place.php?game_id=$get_current_game_id&l=$l&ft=error&fm=that_place_already_exists";
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
		
		
		// Use city
		mysqli_query($link, "UPDATE $t_rebus_games_index SET
					game_place_id=$get_place_id,
					game_place_name=$inp_name_mysql
					WHERE game_id=$get_current_game_id")
					or die(mysqli_error($link));
		
		// Truncate geo distance measurements
		mysqli_query($link, "TRUNCATE $t_rebus_games_index_geo_distance_measurements") or die(mysqli_error($link));

		// Header	
		$url = "create_game_step_5_place.php?action=select_coordinates_for_place&game_id=$get_current_game_id&l=$l&ft=success&fm=place_added";
		header("Location: $url");
		exit;
	} // action == add new city
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
					$url = "create_game_step_5_place.php?action=select_coordinates_for_place&game_id=$get_current_game_id&l=$l&ft=error&fm=please_add_coordinates";
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
		
				// Truncate geo distance measurements
				mysqli_query($link, "TRUNCATE $t_rebus_games_index_geo_distance_measurements") or die(mysqli_error($link));


				// Header	
				$url = "create_game_step_6_introduction.php?game_id=$get_current_game_id&l=$l&ft=success&fm=place_added";
				header("Location: $url");
				exit;


			} // process
				
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
				<a href=\"edit_game_general.php?game_id=$get_current_game_id&amp;l=$l\">$l_place</a>
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

			<h2>$l_please_select_coordinates_for_the_place $get_current_place_name</h2>

			<form method=\"post\" action=\"create_game_step_5_place.php?action=$action&amp;game_id=$get_current_game_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">


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
			
			";
		} // place found


	} // action == select coordinates for place
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