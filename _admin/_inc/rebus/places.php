<?php
/**
*
* File: _admin/_inc/rebus/places.php
* Version 
* Date 07:54 01.07.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables ---------------------------------------------------------------------------- */
$t_rebus_liquidbase	= $mysqlPrefixSav . "rebus_liquidbase";

$t_rebus_games_index			= $mysqlPrefixSav . "rebus_games_index";
$t_rebus_games_index_geo_distance_measurements	= $mysqlPrefixSav . "rebus_games_index_geo_distance_measurements";
$t_rebus_games_owners			= $mysqlPrefixSav . "rebus_games_owners";
$t_rebus_games_assignments		= $mysqlPrefixSav . "rebus_games_assignments";
$t_rebus_games_assignments_images	= $mysqlPrefixSav . "rebus_games_assignments_images";
$t_rebus_games_invited_players 		= $mysqlPrefixSav . "rebus_games_invited_players";
$t_rebus_games_comments			= $mysqlPrefixSav . "rebus_games_comments";
$t_rebus_games_high_scores 		= $mysqlPrefixSav . "rebus_games_high_scores";

$t_rebus_games_geo_countries		= $mysqlPrefixSav . "rebus_games_geo_countries";
$t_rebus_games_geo_counties		= $mysqlPrefixSav . "rebus_games_geo_counties";
$t_rebus_games_geo_municipalities	= $mysqlPrefixSav . "rebus_games_geo_municipalities";
$t_rebus_games_geo_cities		= $mysqlPrefixSav . "rebus_games_geo_cities";
$t_rebus_games_geo_places		= $mysqlPrefixSav . "rebus_games_geo_places";

$t_rebus_games_sessions_index		= $mysqlPrefixSav . "rebus_games_sessions_index";
$t_rebus_games_sessions_answers		= $mysqlPrefixSav . "rebus_games_sessions_answers";

$t_rebus_groups_index	= $mysqlPrefixSav . "rebus_groups_index";
$t_rebus_groups_members	= $mysqlPrefixSav . "rebus_groups_members";

$t_rebus_teams_index	= $mysqlPrefixSav . "rebus_teams_index";
$t_rebus_teams_members	= $mysqlPrefixSav . "rebus_teams_members";



/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['place_id'])){
	$place_id = $_GET['place_id'];
	$place_id = output_html($place_id);
	if(!(is_numeric($place_id))){
		echo"Place id not numeric";
		die;
	}
}
else{
	$place_id = "";
}



if($action == ""){
	echo"
	<h1>Places</h1>
				

	<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
	<!-- //Feedback -->

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=rebus&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Rebus</a>
		&gt;
		<a href=\"index.php?open=rebus&amp;page=places&amp;editor_language=$editor_language&amp;l=$l\">Places</a>
		</p>
	<!-- //Where am I? -->

	<!-- Places -->
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th>
			<span>ID</span>
		   </th>
		   <th>
			<span>Name</span>
		   </th>
		   <th>
			<span>Coordinates</span>
		   </th>
		   <th>
			<span>Country</span>
		   </th>
		   <th>
			<span>County</span>
		   </th>
		   <th>
			<span>Municipality</span>
		   </th>
		   <th>
			<span>City</span>
		   </th>
		   <th>
			<span>Actions</span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>";
		$query = "SELECT place_id, place_name, place_latitude, place_longitude, place_country_id, place_country_name, place_county_id, place_county_name, place_municipality_id, place_municipality_name, place_city_id, place_city_name, place_created_by_user_id, place_created_by_user_name, place_created_by_user_email, place_created_by_ip, place_created_by_hostname, place_created_by_user_agent FROM $t_rebus_games_geo_places ORDER BY place_name ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_place_id, $get_place_name, $get_place_latitude, $get_place_longitude, $get_place_country_id, $get_place_country_name, $get_place_county_id, $get_place_county_name, $get_place_municipality_id, $get_place_municipality_name, $get_place_city_id, $get_place_city_name, $get_place_created_by_user_id, $get_place_created_by_user_name, $get_place_created_by_user_email, $get_place_created_by_ip, $get_place_created_by_hostname, $get_place_created_by_user_agent) = $row;

				echo"
				 <tr>
				  <td>
					<span>$get_place_id</span>
				  </td>
				  <td>
					<span>$get_place_name</span>
				  </td>
				  <td>
					<span><a href=\"https://maps.google.com/maps?client=firefox-b-d&amp;q=$get_place_latitude, $get_place_longitude\">$get_place_latitude, $get_place_longitude</a></span>
					
				  </td>
				  <td>
					<span>$get_place_country_name</span>
				  </td>
				  <td>
					<span>$get_place_county_name</span>
				  </td>
				  <td>
					<span>$get_place_municipality_name</span>
				  </td>
				  <td>
					<span>$get_place_city_name</span>
				  </td>
				  <td>
					<span>
					<a href=\"index.php?open=rebus&amp;page=places&amp;action=edit_place&amp;place_id=$get_place_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
					&middot;
					<a href=\"index.php?open=rebus&amp;page=places&amp;action=delete_place&amp;place_id=$get_place_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
					</span>
				  </td>
				 </tr>
				";

			}
			echo"
			 </tbody>
			</table>

	<!-- //Places -->
	";
}
elseif($action == "edit_place"){
	// Get place
	$place_id_mysql = quote_smart($link, $place_id);
	$query = "SELECT place_id, place_name, place_latitude, place_longitude, place_country_id, place_country_name, place_county_id, place_county_name, place_municipality_id, place_municipality_name, place_city_id, place_city_name, place_created_by_user_id, place_created_by_user_name, place_created_by_user_email, place_created_by_ip, place_created_by_hostname, place_created_by_user_agent FROM $t_rebus_games_geo_places WHERE place_id=$place_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_place_id, $get_current_place_name, $get_current_place_latitude, $get_current_place_longitude, $get_current_place_country_id, $get_current_place_country_name, $get_current_place_county_id, $get_current_place_county_name, $get_current_place_municipality_id, $get_current_place_municipality_name, $get_current_place_city_id, $get_current_place_city_name, $get_current_place_created_by_user_id, $get_current_place_created_by_user_name, $get_current_place_created_by_user_email, $get_current_place_created_by_ip, $get_current_place_created_by_hostname, $get_current_place_created_by_user_agent) = $row;
	if($get_current_place_id == ""){
		echo"Place not found";
	}
	else{
		if($process == "1"){
			$inp_place_name = $_POST['inp_place_name'];
			$inp_place_name = output_html($inp_place_name);
			$inp_place_name_mysql = quote_smart($link, $inp_place_name);

			$inp_place_latitude = $_POST['inp_place_latitude'];
			$inp_place_latitude = output_html($inp_place_latitude);
			$inp_place_latitude_mysql = quote_smart($link, $inp_place_latitude);

			$inp_place_longitude = $_POST['inp_place_longitude'];
			$inp_place_longitude = output_html($inp_place_longitude);
			$inp_place_longitude_mysql = quote_smart($link, $inp_place_longitude);



			// Country
			$inp_place_country_id = $_POST['inp_place_country_id'];
			$inp_place_country_id = output_html($inp_place_country_id);
			$inp_place_country_id_mysql = quote_smart($link, $inp_place_country_id);

			$query = "SELECT country_id, country_name FROM $t_rebus_games_geo_countries WHERE country_id=$inp_place_country_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_country_id, $get_country_name) = $row;
			if($get_country_id == ""){
				$get_country_id = "0";
			}
			$inp_place_country_id_mysql = quote_smart($link, $get_country_id);
			$inp_place_country_name_mysql = quote_smart($link, $get_country_name);


			// County
			$inp_place_county_id = $_POST['inp_place_county_id'];
			$inp_place_county_id = output_html($inp_place_county_id);
			$inp_place_county_id_mysql = quote_smart($link, $inp_place_county_id);

			$query = "SELECT county_id, county_name FROM $t_rebus_games_geo_counties WHERE county_id=$inp_place_county_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_county_id, $get_county_name) = $row;
			if($get_county_id == ""){
				$get_county_id = "0";
			}
			$inp_place_county_id_mysql = quote_smart($link, $get_county_id);
			$inp_place_county_name_mysql = quote_smart($link, $get_county_name);

			// Municipality
			$inp_place_municipality_id = $_POST['inp_place_municipality_id'];
			$inp_place_municipality_id = output_html($inp_place_municipality_id);
			$inp_place_municipality_id_mysql = quote_smart($link, $inp_place_municipality_id);

			$query = "SELECT municipality_id, municipality_name FROM $t_rebus_games_geo_municipalities WHERE municipality_id=$inp_place_municipality_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_municipality_id, $get_municipality_name) = $row;
			if($get_municipality_id == ""){
				$get_municipality_id = "0";
			}
			$inp_place_municipality_id_mysql = quote_smart($link, $get_municipality_id);
			$inp_place_municipality_name_mysql = quote_smart($link, $get_municipality_name);


			// City
			$inp_place_city_id = $_POST['inp_place_city_id'];
			$inp_place_city_id = output_html($inp_place_city_id);
			$inp_place_city_id_mysql = quote_smart($link, $inp_place_city_id);

			$query = "SELECT city_id, city_name FROM $t_rebus_games_geo_cities WHERE city_id=$inp_place_city_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_city_id, $get_city_name) = $row;
			if($get_city_id == ""){
				$get_city_id = "0";
			}
			$inp_place_city_id_mysql = quote_smart($link, $get_city_id);
			$inp_place_city_name_mysql = quote_smart($link, $get_city_name);

			// Update
			mysqli_query($link, "UPDATE $t_rebus_games_geo_places SET 
						place_name=$inp_place_name_mysql, 
						place_latitude=$inp_place_latitude_mysql, 
						place_longitude=$inp_place_longitude_mysql, 
						place_country_id=$inp_place_country_id_mysql, 
						place_country_name=$inp_place_country_name_mysql, 
						place_county_id=$inp_place_county_id_mysql, 
						place_county_name=$inp_place_county_name_mysql, 
						place_municipality_id=$inp_place_municipality_id_mysql, 
						place_municipality_name=$inp_place_municipality_name_mysql, 
						place_city_id=$inp_place_city_id_mysql, 
						place_city_name=$inp_place_city_name_mysql 
						WHERE place_id=$get_current_place_id") or die(mysqli_error($link));

			// Header
			$time = date("H:i:s");
			$url = "index.php?open=rebus&page=places&action=edit_place&place_id=$get_current_place_id&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved_at_$time";
			header("Location: $url");
			exit;

		} // process
		echo"
		<h1>$get_current_place_name</h1>
				

		<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=rebus&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Rebus</a>
			&gt;
			<a href=\"index.php?open=rebus&amp;page=places&amp;editor_language=$editor_language&amp;l=$l\">Places</a>
			&gt;
			<a href=\"index.php?open=rebus&amp;page=places&amp;action=edit_place&amp;place_id=$get_current_place_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_place_name</a>
			</p>
		<!-- //Where am I? -->

		<!-- Edit place form -->

			<form method=\"post\" action=\"index.php?open=rebus&amp;page=places&amp;action=edit_place&amp;place_id=$get_current_place_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>Name:</b><br />
			<input type=\"text\" name=\"inp_place_name\" value=\"$get_current_place_name\" size=\"25\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /><br />
			</p>

			<p style=\"padding-bottom:0;margin-bottom:0;\"><b>Coordinates:</b></p>
			<table>
			 <tr>
			  <td style=\"padding-right: 10px;\">
				<span>Latitude<br />
				<input type=\"text\" name=\"inp_place_latitude\" value=\"$get_current_place_latitude\" size=\"19\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /><br />
				</span>
			  </td>
			  <td>
				<span>Longitude<br />
				<input type=\"text\" name=\"inp_place_longitude\" value=\"$get_current_place_longitude\" size=\"19\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
				</span>
			  </td>
			 </tr>
			</table>

			<p><b>Country:</b><br />
			<select name=\"inp_place_country_id\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />\n";
			$query = "SELECT country_id, country_name FROM $t_rebus_games_geo_countries ORDER BY country_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_country_id, $get_country_name) = $row;
				echo"			";
				echo"<option value=\"$get_country_id\""; if($get_country_id == "$get_current_place_country_id"){ echo" selected=\"selected\""; } echo">$get_country_name</option>\n";
			}
			echo"
			</select>
			</p>

			<p><b>County:</b><br />
			<select name=\"inp_place_county_id\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />\n";
			$query = "SELECT county_id, county_name FROM $t_rebus_games_geo_counties WHERE county_country_id=$get_current_place_country_id ORDER BY county_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_county_id, $get_county_name) = $row;
				echo"<option value=\"$get_county_id\""; if($get_county_id == "$get_current_place_county_id"){ echo" selected=\"selected\""; } echo">$get_county_name</option>\n";
			}
			echo"
			</select>
			</p>

			<p><b>Municipality:</b><br />
			<select name=\"inp_place_municipality_id\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />\n";
			$query = "SELECT municipality_id, municipality_name FROM $t_rebus_games_geo_municipalities WHERE municipality_country_id=$get_current_place_country_id AND municipality_county_id=$get_current_place_county_id ORDER BY municipality_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_municipality_id, $get_municipality_name) = $row;
				echo"<option value=\"$get_municipality_id\""; if($get_municipality_id == "$get_current_place_municipality_id"){ echo" selected=\"selected\""; } echo">$get_municipality_name</option>\n";
			}
			echo"
			</select>
			</p>


			<p><b>City:</b><br />
			<select name=\"inp_place_city_id\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />\n";
			$query = "SELECT city_id, city_name FROM $t_rebus_games_geo_cities WHERE city_country_id=$get_current_place_country_id AND city_county_id=$get_current_place_county_id AND city_municipality_id=$get_current_place_municipality_id ORDER BY city_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_city_id, $get_city_name) = $row;
				echo"<option value=\"$get_city_id\""; if($get_city_id == "$get_current_place_city_id"){ echo" selected=\"selected\""; } echo">$get_city_name</option>\n";
			}
			echo"
			</select>
			</p>


			<p>
			<input type=\"submit\" value=\"Save changes\" class=\"btn_default\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</p>

			</form>
		<!-- //Edit place form -->

		";
	}
} // edit place
elseif($action == "delete_place"){
	// Get place
	$place_id_mysql = quote_smart($link, $place_id);
	$query = "SELECT place_id, place_name, place_latitude, place_longitude, place_country_id, place_country_name, place_county_id, place_county_name, place_municipality_id, place_municipality_name, place_city_id, place_city_name, place_created_by_user_id, place_created_by_user_name, place_created_by_user_email, place_created_by_ip, place_created_by_hostname, place_created_by_user_agent FROM $t_rebus_games_geo_places WHERE place_id=$place_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_place_id, $get_current_place_name, $get_current_place_latitude, $get_current_place_longitude, $get_current_place_country_id, $get_current_place_country_name, $get_current_place_county_id, $get_current_place_county_name, $get_current_place_municipality_id, $get_current_place_municipality_name, $get_current_place_city_id, $get_current_place_city_name, $get_current_place_created_by_user_id, $get_current_place_created_by_user_name, $get_current_place_created_by_user_email, $get_current_place_created_by_ip, $get_current_place_created_by_hostname, $get_current_place_created_by_user_agent) = $row;
	if($get_current_place_id == ""){
		echo"Place not found";
	}
	else{
		if($process == "1"){
			
			// Update
			mysqli_query($link, "DELETE FROM $t_rebus_games_geo_places WHERE place_id=$get_current_place_id") or die(mysqli_error($link));

			// Header
			$time = date("H:i:s");
			$url = "index.php?open=rebus&page=places&editor_language=$editor_language&l=$l&ft=success&fm=place_deleted_at_$time";
			header("Location: $url");
			exit;

		} // process
		echo"
		<h1>$get_current_place_name</h1>
				

		<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=rebus&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Rebus</a>
			&gt;
			<a href=\"index.php?open=rebus&amp;page=places&amp;editor_language=$editor_language&amp;l=$l\">Places</a>
			&gt;
			<a href=\"index.php?open=rebus&amp;page=places&amp;action=delete_place&amp;place_id=$get_current_place_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_place_name</a>
			</p>
		<!-- //Where am I? -->

		<!-- Delete place form -->

			<p>
			Are you sure you want to delete the place?
			</p>

			<p>
			<a href=\"index.php?open=rebus&amp;page=places&amp;action=delete_place&amp;place_id=$get_current_place_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_danger\">Confirm</a>
			</p>

		<!-- //Delete place form -->

		";
	}
} // Delete place
?>