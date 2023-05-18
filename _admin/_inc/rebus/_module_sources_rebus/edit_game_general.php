<?php
/**
*
* File: rebus/my_games_edit_general.php
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
	
	// Me
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);

	$my_security = $_SESSION['security'];
	$my_security= output_html($my_security);
	$my_security_mysql = quote_smart($link, $my_security);

	$query = "SELECT user_id, user_name, user_alias, user_language, user_date_format, user_timezone_utc_diff, user_timezone_value, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql AND user_security=$my_security_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_name, $get_my_user_alias, $get_my_user_language, $get_my_user_date_format, $get_my_user_timezone_utc_diff, $get_my_user_timezone_value, $get_my_user_rank) = $row;



	/*- Find game ------------------------------------------------------------------------- */
	$game_id_mysql = quote_smart($link, $game_id);
	$query = "SELECT game_id, game_title, game_language, game_introduction, game_description, game_privacy, game_difficulty, game_published, game_age_limit, game_playable_after_datetime, game_playable_after_datetime_saying, game_playable_after_time, game_group_id, game_group_name, game_times_played, game_times_finished, game_finished_percentage, game_time_used_seconds, game_time_used_saying, game_image_path, game_image_file, game_image_thumb_278x156, game_image_thumb_570x321, game_image_thumb_570x380, game_country_id, game_country_name, game_county_id, game_county_name, game_municipality_id, game_municipality_name, game_city_id, game_city_name, game_place_id, game_place_name, game_number_of_assignments, game_rating, game_created_by_user_id, game_created_by_user_name, game_created_by_user_email, game_created_by_ip, game_created_by_hostname, game_created_by_user_agent, game_created_datetime, game_created_date_saying, game_updated_by_user_id, game_updated_by_user_name, game_updated_by_user_email, game_updated_by_ip, game_updated_by_hostname, game_updated_by_user_agent, game_updated_datetime, game_updated_date_saying FROM $t_rebus_games_index WHERE game_id=$game_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_game_id, $get_current_game_title, $get_current_game_language, $get_current_game_introduction, $get_current_game_description, $get_current_game_privacy, $get_current_game_difficulty, $get_current_game_published, $get_current_game_age_limit, $get_current_game_playable_after_datetime, $get_current_game_playable_after_datetime_saying, $get_current_game_playable_after_time, $get_current_game_group_id, $get_current_game_group_name, $get_current_game_times_played, $get_current_game_times_finished, $get_current_game_finished_percentage, $get_current_game_time_used_seconds, $get_current_game_time_used_saying, $get_current_game_image_path, $get_current_game_image_file, $get_current_game_image_thumb_278x156, $get_current_game_image_thumb_570x321, $get_current_game_image_thumb_570x380, $get_current_game_country_id, $get_current_game_country_name, $get_current_game_county_id, $get_current_game_county_name, $get_current_game_municipality_id, $get_current_game_municipality_name, $get_current_game_city_id, $get_current_game_city_name, $get_current_game_place_id, $get_current_game_place_name, $get_current_game_number_of_assignments, $get_current_game_rating, $get_current_game_created_by_user_id, $get_current_game_created_by_user_name, $get_current_game_created_by_user_email, $get_current_game_created_by_ip, $get_current_game_created_by_hostname, $get_current_game_created_by_user_agent, $get_current_game_created_datetime, $get_current_game_created_date_saying, $get_current_game_updated_by_user_id, $get_current_game_updated_by_user_name, $get_current_game_updated_by_user_email, $get_current_game_updated_by_ip, $get_current_game_updated_by_hostname, $get_current_game_updated_by_user_agent, $get_current_game_updated_datetime, $get_current_game_updated_date_saying) = $row;
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
	$website_title = "$l_general - $get_current_game_title - $l_my_games";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");
	
	if($process == "1"){

		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

		$inp_introduction = $_POST['inp_introduction'];
		$inp_introduction = output_html($inp_introduction);
		$inp_introduction_mysql = quote_smart($link, $inp_introduction);


		$inp_privacy = $_POST['inp_privacy'];
		$inp_privacy = output_html($inp_privacy);
		$inp_privacy_mysql = quote_smart($link, $inp_privacy);

		$inp_published = $_POST['inp_published'];
		$inp_published = output_html($inp_published);
		$inp_published_mysql = quote_smart($link, $inp_published);

		// Playable after
		$inp_playable_after_date = $_POST['inp_playable_after_date'];
		$inp_playable_after_date = output_html($inp_playable_after_date);

		$inp_playable_after_hour = $_POST['inp_playable_after_hour'];
		$inp_playable_after_hour = output_html($inp_playable_after_hour);

		$inp_playable_after_minute = $_POST['inp_playable_after_minute'];
		$inp_playable_after_minute = output_html($inp_playable_after_minute);

		$inp_playable_after_datetime = $inp_playable_after_date . " " . $inp_playable_after_hour . ":" . $inp_playable_after_minute  . ":00";
		$inp_playable_after_datetime_mysql = quote_smart($link, $inp_playable_after_datetime);

		$inp_playable_after_time = strtotime($inp_playable_after_datetime);
		$inp_playable_after_time_mysql = quote_smart($link, $inp_playable_after_time);

		$year = substr($inp_playable_after_date, 0, 4);
		$month = substr($inp_playable_after_date, 5, 2);
		$day = substr($inp_playable_after_date, 8, 2);

		$inp_playable_after_datetime_saying = "$day";
		if($day < 10){
			$inp_playable_after_datetime_saying = substr($day, 1, 1);
		}
		if($month == "01"){
			$inp_playable_after_datetime_saying = $inp_playable_after_datetime_saying . " $l_january";
		}
		elseif($month == "02"){
			$inp_playable_after_datetime_saying = $inp_playable_after_datetime_saying . " $l_february";
		}
		elseif($month == "03"){
			$inp_playable_after_datetime_saying = $inp_playable_after_datetime_saying . " $l_mars";
		}
		elseif($month == "04"){
			$inp_playable_after_datetime_saying = $inp_playable_after_datetime_saying . " $l_april";
		}
		elseif($month == "05"){
			$inp_playable_after_datetime_saying = $inp_playable_after_datetime_saying . " $l_may";
		}
		elseif($month == "06"){
			$inp_playable_after_datetime_saying = $inp_playable_after_datetime_saying . " $l_june";
		}
		elseif($month == "07"){
			$inp_playable_after_datetime_saying = $inp_playable_after_datetime_saying . " $l_july";
		}
		elseif($month == "08"){
			$inp_playable_after_datetime_saying = $inp_playable_after_datetime_saying . " $l_august";
		}
		elseif($month == "09"){
			$inp_playable_after_datetime_saying = $inp_playable_after_datetime_saying . " $l_september";
		}
		elseif($month == "10"){
			$inp_playable_after_datetime_saying = $inp_playable_after_datetime_saying . " $l_october";
		}
		elseif($month == "11"){
			$inp_playable_after_datetime_saying = $inp_playable_after_datetime_saying . " $l_november";
		}
		elseif($month == "12"){
			$inp_playable_after_datetime_saying = $inp_playable_after_datetime_saying . " $l_december";
		}
		$inp_playable_after_datetime_saying = $inp_playable_after_datetime_saying . " $year $inp_playable_after_hour:$inp_playable_after_minute";
		$inp_playable_after_datetime_saying_mysql = quote_smart($link, $inp_playable_after_datetime_saying);

		// Group
		$inp_group_id = $_POST['inp_group_id'];
		$inp_group_id = output_html($inp_group_id);
		$inp_group_id_mysql = quote_smart($link, $inp_group_id);

		$inp_group_name = "";

		if($inp_group_id != "0"){
			// Find group
			$query = "SELECT group_id, group_name FROM $t_rebus_groups_index WHERE group_id=$inp_group_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_group_id, $get_group_name) = $row;
			
			// Check that I am a member of that group
			$query = "SELECT member_id FROM $t_rebus_groups_members WHERE member_group_id=$get_group_id AND member_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_member_id) = $row;

			if($get_member_id != ""){
				$inp_group_id = "$get_group_id";
				$inp_group_id = output_html($inp_group_id);
				$inp_group_id_mysql = quote_smart($link, $inp_group_id);

				$inp_group_name = output_html($get_group_name);
			}
		}
		$inp_group_name_mysql = quote_smart($link, $inp_group_name);

		$inp_difficulty = $_POST['inp_difficulty'];
		$inp_difficulty = output_html($inp_difficulty);
		$inp_difficulty_mysql = quote_smart($link, $inp_difficulty);

		$inp_age_limit = $_POST['inp_age_limit'];
		$inp_age_limit = output_html($inp_age_limit);
		$inp_age_limit_mysql = quote_smart($link, $inp_age_limit);

		// Language
		$inp_language = $_POST['inp_language'];
		$inp_language = output_html($inp_language);
		$inp_language_mysql = quote_smart($link, $inp_language);
		if($inp_language == ""){
			echo"Error: missing language";
			die;
		}

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
		$datetime_saying  = date("j M Y H:i:s");
		$date_saying = date("j M Y");
		$year = date("Y");

		mysqli_query($link, "UPDATE $t_rebus_games_index SET
					game_title=$inp_title_mysql,
					game_language=$inp_language_mysql,
					game_introduction=$inp_introduction_mysql,
					game_privacy=$inp_privacy_mysql, 
					game_published=$inp_published_mysql,
					game_difficulty=$inp_difficulty_mysql, 
					game_age_limit=$inp_age_limit_mysql, 
					game_playable_after_datetime=$inp_playable_after_datetime_mysql, 
					game_playable_after_datetime_saying=$inp_playable_after_datetime_saying_mysql, 
					game_playable_after_time=$inp_playable_after_time_mysql, 
					game_group_id=$inp_group_id_mysql, 
					game_group_name=$inp_group_name_mysql, 
					game_updated_by_user_id=$get_my_user_id,
					game_updated_by_user_name=$inp_my_user_name_mysql,
					game_updated_by_user_email=$inp_my_user_email_mysql,
					game_updated_by_ip=$my_ip_mysql,
					game_updated_by_hostname=$my_hostname_mysql,
					game_updated_by_user_agent=$my_user_agent_mysql,
					game_updated_datetime='$datetime',
					game_updated_date_saying='$date_saying'
					WHERE game_id=$get_current_game_id") or die(mysqli_error($link));




		// Search engine
		// Search engine :: Module
		$inp_module_name_mysql = quote_smart($link, "rebus");
		$inp_module_part_name_mysql = quote_smart($link, "game_id");
		$inp_module_part_id_mysql	    = quote_smart($link, $get_current_game_id);
		if($get_current_game_privacy == "public" && $inp_privacy == "private"){
			// Remove from search engine
			$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name=$inp_module_name_mysql AND index_module_part_id=$inp_module_part_id_mysql";
			$result_exists = mysqli_query($link, $query_exists);
			$row_exists = mysqli_fetch_row($result_exists);
			list($get_index_id) = $row_exists;
			if($get_index_id != ""){
				mysqli_query($link, "DELETE FROM $t_search_engine_index WHERE index_id=$get_index_id") or die(mysqli_error($link));
			}
		}

		if($inp_published == "1" && $inp_privacy == "public"){

			// Search engine and feed data
			// $inp_title = "$get_current_game_title"; 
			// $inp_title_mysql = quote_smart($link, $inp_title);
			$inp_short_description = substr($inp_introduction, 0, 200);
			$inp_short_description_mysql = quote_smart($link, $inp_short_description);

			// Keywords
			$inp_keywords = "$get_current_game_municipality_name, $get_current_game_city_name, $get_current_game_place_name";
			$inp_keywords_mysql = quote_smart($link, $inp_keywords);

			// Image
			$inp_image_path_mysql = quote_smart($link, $get_current_game_image_path);
			$inp_image_file_mysql = quote_smart($link, $get_current_game_image_file);
	
			// Thumb
			$inp_thumb_235x132 = "";
			$inp_thumb_300x169 = "";
			$inp_thumb_540x304 = "";
			if($get_current_game_image_file != ""){
				$ext = get_extension($get_current_game_image_file);
				$thumb = str_replace(".$ext", "", $get_current_game_image_file);
				$inp_thumb_235x132 = $thumb . "_235x132." . $ext;
				$inp_thumb_300x169 = $thumb . "_300x169." . $ext;
				$inp_thumb_540x304 = $thumb . "_540x304." . $ext;
			}
			$inp_thumb_235x132_mysql = quote_smart($link, $inp_thumb_235x132);
			$inp_thumb_300x169_mysql = quote_smart($link, $inp_thumb_300x169);
			$inp_thumb_540x304_mysql = quote_smart($link, $inp_thumb_540x304);

			// URL
			$inp_url = "rebus/play_game.php?game_id=$get_current_game_id";
			$inp_url_mysql = quote_smart($link, $inp_url);

			// Link name
			$inp_link_name_mysql = quote_smart($link, "$l_play");

			//$inp_reference_name_mysql = quote_smart($link, "game_id");
			//$inp_reference_id_mysql = quote_smart($link, $get_current_game_id);
			
			// Category
			$inp_main_category_id_mysql = quote_smart($link, $get_current_game_municipality_id);
			$inp_main_category_name_mysql = quote_smart($link, $get_current_game_municipality_name);

			$inp_sub_category_id_mysql = quote_smart($link, $get_current_game_city_id);
			$inp_sub_category_name_mysql = quote_smart($link, $get_current_game_city_name);

			// Access
			$inp_has_access_control_mysql = quote_smart($link, 0);

			// Ad
			$inp_is_ad_mysql = quote_smart($link, 0);
	

			// Check if exists
			$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name=$inp_module_name_mysql AND index_module_part_id=$inp_module_part_id_mysql";
			$result_exists = mysqli_query($link, $query_exists);
			$row_exists = mysqli_fetch_row($result_exists);
			list($get_index_id) = $row_exists;
			if($get_index_id == ""){
				// Insert
				// echo"<span>Insert $inp_index_title<br /></span>\n";
				mysqli_query($link, "INSERT INTO $t_search_engine_index 
				(index_id, index_title, index_url, index_short_description, index_keywords, 
				index_image_path, index_image_file, index_image_thumb_235x132, 
				index_module_name, index_module_part_name, index_module_part_id, 
				index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
				index_unique_hits) 
				VALUES 
				(NULL, $inp_title_mysql, $inp_url_mysql, $inp_short_description_mysql, $inp_keywords_mysql, 
				$inp_image_path_mysql, $inp_image_file_mysql, $inp_thumb_235x132_mysql, 
				$inp_module_name_mysql, $inp_module_part_name_mysql, $inp_module_part_id_mysql, 
				'0', $inp_is_ad_mysql, '$datetime', '$datetime_saying', $inp_language_mysql,
				0)")
				or die(mysqli_error($link));
			}
			else{
				mysqli_query($link, "UPDATE $t_search_engine_index SET
							index_title=$inp_title_mysql, 
							index_short_description=$inp_short_description_mysql,
							index_language=$inp_language_mysql
							WHERE index_id=$get_index_id") or die(mysqli_error($link));
			}
		} // search engine


		// Header
		$url = "edit_game_general.php?game_id=$get_current_game_id&l=$inp_language&ft=success&fm=changes_saved";
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
		<a href=\"edit_game_general.php?game_id=$get_current_game_id&amp;l=$l\">$l_general</a>
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

	<!-- General form -->
		<h2>$l_edit_general</h2>
		<form method=\"post\" action=\"edit_game_general.php?game_id=$get_current_game_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

		<p><b>$l_title:</b><br />
		<input type=\"text\" name=\"inp_title\" value=\"$get_current_game_title\" size=\"25\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
		</p>

		<p><b>$l_game_introduction:</b><br />
		$l_please_explain_in_a_few_sentences_what_your_game_is_about_who_is_it_for_where_it_can_be_played_and_how_to_play_it. <br />
		<textarea name=\"inp_introduction\" rows=\"6\" cols=\"40\" style=\"width:99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\">"; 
		$get_current_game_introduction = str_replace("<br />", "\n", $get_current_game_introduction);
		echo"$get_current_game_introduction</textarea>
		</p>

		<p><b>$l_privacy:</b><br />
		<input type=\"radio\" name=\"inp_privacy\" value=\"public\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\""; if($get_current_game_privacy == "public"){ echo" checked=\"checked\""; } echo" /> $l_public &nbsp;
		<input type=\"radio\" name=\"inp_privacy\" value=\"private\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\""; if($get_current_game_privacy == "private"){ echo" checked=\"checked\""; } echo" /> $l_private
		</p>

	
		<p><b>$l_published:</b><br />
		<input type=\"radio\" name=\"inp_published\" value=\"1\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\""; if($get_current_game_published == "1"){ echo" checked=\"checked\""; } echo" /> $l_yes &nbsp;
		<input type=\"radio\" name=\"inp_published\" value=\"0\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\""; if($get_current_game_published == "0"){ echo" checked=\"checked\""; } echo" /> $l_no
		</p>

		<p><b>$l_game_is_playable_after: (<a href=\"$root/users/settings.php?l=$l\" title=\"$l_your_timezone_is $get_my_user_timezone_value\">$_change_timezone</a>)</b><br />
		";
		if($get_current_game_playable_after_datetime == ""){
			$get_current_game_playable_after_date = date("Y-m-d");
			$get_current_game_playable_after_hour = date("H");
			$get_current_game_playable_after_minute = "00";
		}
		else{
			$date_array = explode(" ", $get_current_game_playable_after_datetime);
			$get_current_game_playable_after_date = $date_array[0];
			$get_current_game_playable_after_hour = substr($date_array[1], 0, 2);
			$get_current_game_playable_after_minute = substr($date_array[1], 3, 2);
		}
		echo"
		<input type=\"date\" name=\"inp_playable_after_date\" value=\"$get_current_game_playable_after_date\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\"/>
		&nbsp;
		<!-- Hour -->
			<select name=\"inp_playable_after_hour\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\">\n";
			for($x=0;$x<24;$x++){
				$y = "$x";
				if($x < 10){
					$y = "0$x";
				}
			
				echo"			<option value=\"$y\""; if($y == "$get_current_game_playable_after_hour"){ echo" selected=\"selected\""; } echo">$y</option>\n";
			}
			echo"
			</select>
		<!-- //Hour -->
		:
		<!-- Minute -->
			<select name=\"inp_playable_after_minute\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\">
			<option value=\"00\""; if($get_current_game_playable_after_minute == "00"){ echo" selected=\"selected\""; } echo">00</option>
			<option value=\"15\""; if($get_current_game_playable_after_minute == "15"){ echo" selected=\"selected\""; } echo">15</option>
			<option value=\"30\""; if($get_current_game_playable_after_minute == "30"){ echo" selected=\"selected\""; } echo">30</option>
			<option value=\"45\""; if($get_current_game_playable_after_minute == "45"){ echo" selected=\"selected\""; } echo">45</option>
			</select>
		<!-- //Minute -->
		</p>

		<p><b>$l_game_belongs_to_group:</b>
		(<a href=\"new_group.php?l=$l\">$l_create_group</a>)<br />
		<select name=\"inp_group_id\">
			<option value=\"0\""; if($get_current_game_group_id == "0"){ echo" selected=\"selected\""; } echo">$l_none</selected>";
			$query = "SELECT member_id, member_group_id, group_name FROM $t_rebus_groups_members JOIN $t_rebus_groups_index ON $t_rebus_groups_members.member_group_id=$t_rebus_groups_index.group_id WHERE member_user_id=$my_user_id_mysql ORDER BY $t_rebus_groups_index.group_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_member_id, $get_member_group_id, $get_group_name) = $row;
				echo"			<option value=\"$get_member_group_id\""; if($get_current_game_group_id == "$get_member_group_id"){ echo" selected=\"selected\""; } echo">$get_group_name</selected>\n";
			}
			echo"
		</select></p>


		<p><b>$l_difficulty:</b><br />
		<input type=\"radio\" name=\"inp_difficulty\" value=\"tourist\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\""; if($get_current_game_difficulty == "tourist"){ echo" checked=\"checked\""; } echo" /> $l_tourist &nbsp;
		<input type=\"radio\" name=\"inp_difficulty\" value=\"locally_known\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\""; if($get_current_game_difficulty == "locally_known"){ echo" checked=\"checked\""; } echo" /> $l_locally_known
		</p>


		<p><b>$l_age_limit:</b><br />
		$l_example_because_of_alcohol<br />
		<input type=\"radio\" name=\"inp_age_limit\" value=\"1\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\""; if($get_current_game_age_limit == "1"){ echo" checked=\"checked\""; } echo" /> $l_yes &nbsp;
		<input type=\"radio\" name=\"inp_age_limit\" value=\"0\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\""; if($get_current_game_age_limit == "0"){ echo" checked=\"checked\""; } echo" /> $l_no
		</p>



		<p><b>$l_language:</b><br />
		<select name=\"inp_language\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\">\n";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag_path_16x16, language_active_flag_16x16, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag_path_16x16, $get_language_active_flag_16x16, $get_language_active_default) = $row;
			echo"		<option value=\"$get_language_active_iso_two\""; if($get_language_active_iso_two == "$get_current_game_language"){ echo" selected=\"selected\""; } echo">$get_language_active_name</option>\n";
		}
		echo"
		</select>
		</p>


		<p><input type=\"submit\" value=\"$l_save_changes\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></p>

		</form>
	<!-- //General form -->
	";
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