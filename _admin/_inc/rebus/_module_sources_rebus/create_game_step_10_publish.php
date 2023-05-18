<?php
/**
*
* File: rebus/create_game_step_10_publish.php
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
	$query = "SELECT game_id, game_title, game_language, game_introduction, game_description, game_privacy, game_published, game_playable_after_datetime, game_playable_after_datetime_saying, game_playable_after_time, game_group_id, game_group_name, game_times_played, game_times_finished, game_finished_percentage, game_time_used_seconds, game_time_used_saying, game_image_path, game_image_file, game_image_thumb_278x156, game_image_thumb_570x321, game_image_thumb_570x380, game_country_id, game_country_name, game_county_id, game_county_name, game_municipality_id, game_municipality_name, game_city_id, game_city_name, game_place_id, game_place_name, game_number_of_assignments, game_rating, game_created_by_user_id, game_created_by_user_name, game_created_by_user_email, game_created_by_ip, game_created_by_hostname, game_created_by_user_agent, game_created_datetime, game_created_date_saying, game_updated_by_user_id, game_updated_by_user_name, game_updated_by_user_email, game_updated_by_ip, game_updated_by_hostname, game_updated_by_user_agent, game_updated_datetime, game_updated_date_saying FROM $t_rebus_games_index WHERE game_id=$game_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_game_id, $get_current_game_title, $get_current_game_language, $get_current_game_introduction, $get_current_game_description, $get_current_game_privacy, $get_current_game_published, $get_current_game_playable_after_datetime, $get_current_game_playable_after_datetime_saying, $get_current_game_playable_after_time, $get_current_game_group_id, $get_current_game_group_name, $get_current_game_times_played, $get_current_game_times_finished, $get_current_game_finished_percentage, $get_current_game_time_used_seconds, $get_current_game_time_used_saying, $get_current_game_image_path, $get_current_game_image_file, $get_current_game_image_thumb_278x156, $get_current_game_image_thumb_570x321, $get_current_game_image_thumb_570x380, $get_current_game_country_id, $get_current_game_country_name, $get_current_game_county_id, $get_current_game_county_name, $get_current_game_municipality_id, $get_current_game_municipality_name, $get_current_game_city_id, $get_current_game_city_name, $get_current_game_place_id, $get_current_game_place_name, $get_current_game_number_of_assignments, $get_current_game_rating, $get_current_game_created_by_user_id, $get_current_game_created_by_user_name, $get_current_game_created_by_user_email, $get_current_game_created_by_ip, $get_current_game_created_by_hostname, $get_current_game_created_by_user_agent, $get_current_game_created_datetime, $get_current_game_created_date_saying, $get_current_game_updated_by_user_id, $get_current_game_updated_by_user_name, $get_current_game_updated_by_user_email, $get_current_game_updated_by_ip, $get_current_game_updated_by_hostname, $get_current_game_updated_by_user_agent, $get_current_game_updated_datetime, $get_current_game_updated_date_saying) = $row;
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
	$website_title = "$l_publish - $get_current_game_title - $l_create_game";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");

	if($process == "1"){
		// Publish game
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

		// Dates
		$datetime = date("Y-m-d H:i:s");
		$datetime_saying  = date("j M Y H:i:s");
		$date_saying = date("j M Y");
		$year = date("Y");


		// Me
		$query = "SELECT user_id, user_email, user_name, user_alias, user_language, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_language, $get_my_user_rank) = $row;
	
		$inp_my_user_name_mysql = quote_smart($link, $get_my_user_name);
		$inp_my_user_email_mysql = quote_smart($link, $get_my_user_email);

		// Author image
		$query = "SELECT photo_id, photo_destination, photo_thumb_40, photo_thumb_50, photo_thumb_60, photo_thumb_200 FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_40, $get_my_photo_thumb_50, $get_my_photo_thumb_60, $get_my_photo_thumb_200) = $row;

		$inp_my_user_email_mysql = quote_smart($link, $get_my_user_email);
		$inp_my_user_name_mysql = quote_smart($link, $get_my_user_name);
		$inp_my_user_alias_mysql = quote_smart($link, $get_my_user_alias);
		$inp_my_user_photo_file_mysql = quote_smart($link, $get_my_photo_destination);
		$inp_my_user_photo_thumb_40_mysql = quote_smart($link, $get_my_photo_thumb_40);
		$inp_my_user_photo_thumb_50_mysql = quote_smart($link, $get_my_photo_thumb_50);
		$inp_my_user_photo_thumb_60_mysql = quote_smart($link, $get_my_photo_thumb_60);
		$inp_my_user_photo_thumb_200_mysql = quote_smart($link, $get_my_photo_thumb_200);




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

		// My hostname
		$my_hostname = "$my_ip";
		if($configSiteUseGethostbyaddrSav == "1"){
			$my_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); // Some servers in local network cant use getostbyaddr because of nameserver missing
		}
		$my_hostname = output_html($my_hostname);
		$my_hostname_mysql = quote_smart($link, $my_hostname);


		mysqli_query($link, "UPDATE $t_rebus_games_index SET 
				game_published=$inp_published_mysql, 
				game_playable_after_datetime=$inp_playable_after_datetime_mysql, 
				game_playable_after_datetime_saying=$inp_playable_after_datetime_saying_mysql, 
				game_playable_after_time=$inp_playable_after_time_mysql, 
				game_updated_by_user_id=$get_my_user_id, 
				game_updated_by_user_name=$inp_my_user_name_mysql, 
				game_updated_by_user_email=$inp_my_user_email_mysql, 
				game_updated_by_ip=$my_ip_mysql,
				game_updated_by_hostname=$my_hostname_mysql,
				game_updated_by_user_agent=$my_user_agent_mysql,
				game_updated_datetime='$datetime',
				game_updated_date_saying='$date_saying'
						WHERE game_id=$get_current_game_id") or die(mysqli_error($link));


		// Search engine and feed
		if($inp_published == "1" && $get_current_game_privacy == "public"){

			// Search engine and feed data
			$inp_title = "$get_current_game_title"; 
			$inp_title_mysql = quote_smart($link, $inp_title);
			$inp_short_description = substr($get_current_game_introduction, 0, 200);
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
			
			// Module
			$inp_module_name_mysql = quote_smart($link, "rebus");

			$inp_module_part_name_mysql = quote_smart($link, "game_id");
			$inp_module_part_id_mysql	    = quote_smart($link, $get_current_game_id);

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
	
			// Language
			$inp_language_mysql = quote_smart($link, $get_current_game_language);

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



			// Feed Subscribe
			$query = "SELECT es_id, es_user_id, es_type, es_on_off FROM $t_users_email_subscriptions WHERE es_user_id='$get_my_user_id' AND es_type='users_feed'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_es_id, $get_es_user_id, $get_es_type, $get_es_on_off) = $row;
			if($get_es_id == ""){
				// Dont know
				mysqli_query($link, "INSERT INTO $t_users_email_subscriptions 
				(es_id, es_user_id, es_type, es_on_off) 
				VALUES 
				(NULL, $get_my_user_id, 'users_feed', 0)") or die(mysqli_error($link));
				$get_es_on_off = 0;
			}

			// Feed dates

			$query = "SELECT feed_id FROM $t_users_feeds_index WHERE feed_module_name=$inp_module_name_mysql AND feed_module_part_name=$inp_module_part_name_mysql AND feed_module_part_id=$get_current_game_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_feed_id) = $row;
			if($get_current_feed_id == ""){
				// Insert feed
				mysqli_query($link, "INSERT INTO $t_users_feeds_index
				(feed_id, feed_title, feed_text, feed_image_path, feed_image_file, 
				feed_image_thumb_300x169, feed_image_thumb_540x304, feed_link_url, feed_link_name, feed_module_name, 
				feed_module_part_name, feed_module_part_id, feed_main_category_id, feed_main_category_name, 
				feed_user_id, feed_user_email, feed_user_name, feed_user_alias, 
				feed_user_photo_file, feed_user_photo_thumb_40, feed_user_photo_thumb_50, feed_user_photo_thumb_60, feed_user_photo_thumb_200, 
				feed_user_subscribe, feed_user_ip, feed_user_hostname, feed_language, feed_created_datetime, 
				feed_created_year, feed_created_time, feed_created_date_saying, feed_likes, feed_dislikes, feed_comments) 
				VALUES 
				(NULL, $inp_title_mysql, $inp_short_description_mysql, $inp_image_path_mysql, $inp_image_file_mysql, 
				$inp_thumb_300x169_mysql, $inp_thumb_540x304_mysql, $inp_url_mysql, $inp_link_name_mysql, $inp_module_name_mysql, 
				$inp_module_part_name_mysql, $inp_module_part_id_mysql, $inp_main_category_id_mysql, $inp_main_category_name_mysql, 
				$get_my_user_id, $inp_my_user_email_mysql, $inp_my_user_name_mysql, $inp_my_user_alias_mysql, 
				$inp_my_user_photo_file_mysql, $inp_my_user_photo_thumb_40_mysql, $inp_my_user_photo_thumb_50_mysql, $inp_my_user_photo_thumb_60_mysql, $inp_my_user_photo_thumb_200_mysql, 
				$get_es_on_off, $my_ip_mysql, $my_hostname_mysql, $inp_language_mysql, '$datetime',
				'$year', '$time', '$date_saying', 0, 0, 0)")
				or die(mysqli_error($link));
							
			} // Create feed
			else{
				// Update feed
				mysqli_query($link, "UPDATE $t_users_feeds_index SET
						feed_title=$inp_title_mysql, 
						feed_text=$inp_short_description_mysql, 
						feed_image_path=$inp_image_path_mysql, 
						feed_image_file=$inp_image_file_mysql, 
						feed_image_thumb_300x169=$inp_thumb_300x169_mysql, 
						feed_image_thumb_540x304=$inp_thumb_540x304_mysql, 
						feed_modified_datetime='$datetime'
						WHERE feed_id=$get_current_feed_id")
						or die(mysqli_error($link));
			} // Update feed

		} // search engine and feed

		// Header
		if($inp_published == "1"){
			$url = "edit_game.php?game_id=$get_current_game_id&l=$l&ft=success&fm=game_published";
		}
		else{
			$url = "edit_game.php?game_id=$get_current_game_id&l=$l&ft=info&fm=game_not_published";
		}
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
		<a href=\"edit_game_general.php?game_id=$get_current_game_id&amp;l=$l\">$l_publish</a>
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
			\$('[name=\"inp_published\"]').focus();
		});
		</script>
	<!-- //Focus -->

	<!-- Publish form -->
		<form method=\"post\" action=\"create_game_step_10_publish.php?game_id=$get_current_game_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
		
		<p>$l_are_you_ready_to_publish_your_game</p>


		<p><b>$l_published:</b><br />
		<input type=\"radio\" name=\"inp_published\" value=\"1\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\""; if($get_current_game_published == "1"){ echo" checked=\"checked\""; } echo" /> $l_yes &nbsp;
		<input type=\"radio\" name=\"inp_published\" value=\"0\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\""; if($get_current_game_published == "0"){ echo" checked=\"checked\""; } echo" /> $l_no
		</p>

		<p><b>$l_game_is_playable_after</b><br />";

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


		<p>
		<input type=\"submit\" value=\"$l_save\" class=\"btn_default\" />
		</p>

		</form>
	<!-- Publish form -->
	";
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