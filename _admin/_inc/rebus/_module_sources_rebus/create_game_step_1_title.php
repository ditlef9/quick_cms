<?php
/**
*
* File: rebus/create_game_step_1_name.php
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
$tabindex = 0;

/*- Translation ------------------------------------------------------------------------ */


/*- Headers ---------------------------------------------------------------------------- */
$website_title = "$l_create_game";
include("$root/_webdesign/header.php");


// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);

	$query = "SELECT user_id, user_email, user_name, user_language, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_language, $get_my_user_rank) = $row;


	if($process == "1"){
		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);
		if($inp_title == ""){
			$url = "create_game_step_1_title.php?l=$l&ft=error&fm=missing_name";
			header("Location: $url");
			exit;
		}
			
		$l_mysql = quote_smart($link, $l);

		$inp_privacy = $_POST['inp_privacy'];
		$inp_privacy = output_html($inp_privacy);
		$inp_privacy_mysql = quote_smart($link, $inp_privacy);

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

		// Me
		$inp_my_user_name_mysql = quote_smart($link, $get_my_user_name);
		$inp_my_user_email_mysql = quote_smart($link, $get_my_user_email);

		// Profile photo
		$query = "SELECT photo_id, photo_destination, photo_thumb_50 FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_50) = $row;

		$inp_my_photo_destination_mysql = quote_smart($link, $get_my_photo_destination);
		$inp_my_photo_thumb_50_mysql = quote_smart($link, $get_my_photo_thumb_50);

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
		$inp_country = $_POST['inp_country'];
		$inp_country = output_html($inp_country);
		$inp_country_mysql = quote_smart($link, $inp_country);

		// Check if country exists in table
		$query = "SELECT country_id FROM $t_rebus_games_geo_countries WHERE country_name=$inp_country_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_country_id) = $row;
		if($get_country_id == ""){
			// Insert it
			$query = "SELECT country_id, country_name, country_flag_path_16x16, country_flag_16x16 FROM $t_languages_countries WHERE country_name=$inp_country_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_country_id, $get_country_name, $get_country_flag_path_16x16, $get_country_flag_16x16) = $row;

			$inp_country_flag_path_16x16_mysql = quote_smart($link, $get_country_flag_path_16x16);
			$inp_country_flag_16x16_mysql = quote_smart($link, $get_country_flag_16x16);


			mysqli_query($link, "INSERT INTO $t_rebus_games_geo_countries 
			(country_id, country_name, country_flag_path_16x16, country_flag_16x16, country_created_datetime, 
			country_created_by_user_id, country_created_by_user_name, country_created_by_user_email, country_created_by_ip, country_created_by_hostname, 
			country_created_by_user_agent) 
			VALUES 
			(NULL, $inp_country_mysql, $inp_country_flag_path_16x16_mysql, $inp_country_flag_16x16_mysql, '$datetime',
			$get_my_user_id, $inp_my_user_name_mysql, $inp_my_user_email_mysql, $my_ip_mysql, $my_hostname_mysql, 
			$my_user_agent_mysql)")
			or die(mysqli_error($link));

			// Get id
			$query = "SELECT country_id FROM $t_rebus_games_geo_countries WHERE country_name=$inp_country_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_country_id) = $row;
			
		}

		$inp_difficulty = $_POST['inp_difficulty'];
		$inp_difficulty = output_html($inp_difficulty);
		$inp_difficulty_mysql = quote_smart($link, $inp_difficulty);


		$inp_age_limit = $_POST['inp_age_limit'];
		$inp_age_limit = output_html($inp_age_limit);
		$inp_age_limit_mysql = quote_smart($link, $inp_age_limit);

		// Check if game exists
		$query = "SELECT game_id FROM $t_rebus_games_index WHERE game_title=$inp_title_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_game_id) = $row;
		if($get_game_id!= ""){
			$url = "create_game_step_1_title.php?privacy=$inp_privacy&group_id=$inp_group_id&l=$l&ft=error&fm=there_is_already_a_game_with_that_title_(" . $inp_title . ")";
			header("Location: $url");
			exit;
		}

		// Create game
		mysqli_query($link, "INSERT INTO $t_rebus_games_index
		(game_id, game_title, game_language, game_description, game_privacy, 
		game_difficulty, game_age_limit, game_published, game_group_id, game_group_name, 
		game_country_id, game_country_name, game_times_played, game_times_finished, game_finished_percentage, 
		game_time_used_seconds, game_time_used_saying, game_created_by_user_id, game_created_by_user_name, game_created_by_user_email,
		game_created_by_ip, game_created_by_hostname, game_created_by_user_agent, game_created_datetime, game_created_date_saying) 
		VALUES 
		(NULL, $inp_title_mysql, $l_mysql, '', $inp_privacy_mysql, 
		$inp_difficulty_mysql, $inp_age_limit_mysql, 0, $inp_group_id_mysql, $inp_group_name_mysql, $get_country_id, $inp_country_mysql, 
		0, 0, 0, 0, '-', 
		$get_my_user_id, $inp_my_user_name_mysql, $inp_my_user_email_mysql, $my_ip_mysql, $my_hostname_mysql, 
		$my_user_agent_mysql, '$datetime', '$date_saying')")
		or die(mysqli_error($link));


		// Get id
		$query = "SELECT game_id FROM $t_rebus_games_index WHERE game_created_by_user_id=$get_my_user_id AND game_created_datetime='$datetime'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_game_id) = $row;

		// Add me as owner
		mysqli_query($link, "INSERT INTO $t_rebus_games_owners
		(owner_id, owner_game_id, owner_user_id, owner_user_name, owner_user_email) 
		VALUES 
		(NULL, $get_current_game_id, $get_my_user_id, $inp_my_user_name_mysql, $inp_my_user_email_mysql)")
		or die(mysqli_error($link));

		// Truncate geo distance measurements
		mysqli_query($link, "TRUNCATE $t_rebus_games_index_geo_distance_measurements") or die(mysqli_error($link));


		// Open team
		$url = "create_game_step_2_county.php?game_id=$get_current_game_id&l=$l&ft=success&fm=game_created";
		header("Location: $url");
		exit;


	} // process

	echo"
	<section>
	<!-- Headline -->
		<h1>$l_create_game</h1>
	<!-- //Headline -->

	<!-- Where am I ? -->
		<p><b>$l_you_are_here:</b><br />
		<a href=\"index.php?l=$l\">$l_rebus</a>
		&gt;
		<a href=\"create_game_step_1_title.php?l=$l\">$l_create_game</a>
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
			\$('[name=\"inp_title\"]').focus();
		});
		</script>
	<!-- //Focus -->

	<!-- New game form -->
		<form method=\"post\" action=\"create_game_step_1_title.php?l=$l&amp;process=1\" enctype=\"multipart/form-data\">



		<!-- Javascript on select go to url -->
			<script type=\"text/javascript\">
			\$(function() {
				\$('.onselect_go_to_url').on('change', function () {
					var url = $(this).val(); // get selected value
					if (url) { // require a URL
						window.location = url; // redirect
					}
					return false;
				});

			});
			</script>
		<!-- //Javascript on select go to url -->


		<p><b>$l_language:</b><br />
		<select name=\"l\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" class=\"onselect_go_to_url\">\n";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag_path_16x16, language_active_flag_16x16, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag_path_16x16, $get_language_active_flag_16x16, $get_language_active_default) = $row;
			echo"		<option value=\"create_game_step_1_title.php?l=$get_language_active_iso_two\""; if($get_language_active_iso_two == "$l"){ echo" selected=\"selected\""; } echo">$get_language_active_name</option>\n";
		}
		echo"
		</select>
		</p>

		<p><b>$l_game_title:</b><br />
		<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
		</p>

		<p><b>$l_privacy:</b><br />";
		if(isset($_GET['privacy'])) {
			$privacy = $_GET['privacy'];
			$privacy = output_html($privacy);
		}
		else{
			$privacy = "public";
		}
		echo"
		<input type=\"radio\" name=\"inp_privacy\" value=\"public\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\""; if($privacy == "public"){ echo" checked=\"checked\""; } echo" /> $l_public &nbsp;
		<input type=\"radio\" name=\"inp_privacy\" value=\"private\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\""; if($privacy == "private"){ echo" checked=\"checked\""; } echo" /> $l_private
		</p>


		<p><b>$l_game_belongs_to_group:</b>";
		if(isset($_GET['group_id'])) {
			$group_id = $_GET['group_id'];
			$group_id = output_html($group_id);
			if(!(is_numeric($group_id))){
				echo"Group id not numeric";
				die;
			}
		}
		else{
			$group_id = "0";
		}
		echo"
		(<a href=\"group_new.php?l=$l\">$l_create_group</a>)<br />
		<select name=\"inp_group_id\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\">
			<option value=\"0\""; if($group_id == "0"){ echo" selected=\"selected\""; } echo">$l_none</selected>";
			$query = "SELECT member_id, member_group_id, group_name FROM $t_rebus_groups_members JOIN $t_rebus_groups_index ON $t_rebus_groups_members.member_group_id=$t_rebus_groups_index.group_id WHERE member_user_id=$my_user_id_mysql ORDER BY $t_rebus_groups_index.group_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_member_id, $get_member_group_id, $get_group_name) = $row;
				echo"			<option value=\"$get_member_group_id\""; if($group_id == "$get_member_group_id"){ echo" selected=\"selected\""; } echo">$get_group_name</selected>\n";
			}
			echo"
		</select></p>


		<p><b>$l_game_can_be_played_in_country:</b><br />";
		if(!(isset($inp_country))){
			// Find the last country used by me
			$query = "SELECT game_id, game_country_id, game_country_name FROM $t_rebus_games_index WHERE game_created_by_user_id=$my_user_id_mysql ORDER BY game_id DESC LIMIT 0,1";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_game_id, $get_game_country_id, $inp_country) = $row;

		}
		if($inp_country == ""){
			// Find the country the last person registrered used
			$query = "SELECT user_country_name FROM $t_users WHERE user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($inp_country) = $row;
			
		}
		echo"
		<select name=\"inp_country\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\">
		";
		$query = "SELECT country_id, country_name FROM $t_languages_countries ORDER BY country_name ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_country_id, $get_country_name) = $row;
			echo"			";
			echo"<option value=\"$get_country_name\""; if(isset($inp_country) && $inp_country == "$get_country_name"){ echo" selected=\"selected\""; } echo">$get_country_name</option>\n";
		}
		echo"
		</select>";
		if($inp_country == ""){
			echo"<br />
			$l_next_time_you_will_have_this_country_preselected
			";
		}
		echo"
		</p>


		<p><b>$l_difficulty:</b><br />";
		if(isset($_GET['difficulty'])) {
			$difficulty = $_GET['difficulty'];
			$difficulty = output_html($difficulty);
		}
		else{
			$difficulty = "tourist";
		}
		echo"
		<input type=\"radio\" name=\"inp_difficulty\" value=\"tourist\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\""; if($difficulty == "tourist"){ echo" checked=\"checked\""; } echo" /> $l_tourist &nbsp;
		<input type=\"radio\" name=\"inp_difficulty\" value=\"locally_known\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\""; if($difficulty == "locally_known"){ echo" checked=\"checked\""; } echo" /> $l_locally_known
		</p>


		<p><b>$l_age_limit:</b><br />
		$l_example_because_of_alcohol<br />";
		if(isset($_GET['age_limit'])) {
			$age_limit = $_GET['age_limit'];
			$age_limit = output_html($age_limit);
		}
		else{
			$age_limit = 0;
		}
		echo"
		<input type=\"radio\" name=\"inp_age_limit\" value=\"1\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\""; if($age_limit == "1"){ echo" checked=\"checked\""; } echo" /> $l_yes &nbsp;
		<input type=\"radio\" name=\"inp_age_limit\" value=\"0\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\""; if($age_limit == "0"){ echo" checked=\"checked\""; } echo" /> $l_no
		</p>

		<p><input type=\"submit\" value=\"$l_create_game\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></p>
		
		</form>
	<!-- //New game form -->
	</section>
	";
}
else{
	echo"
	<h1>
	<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=rebus/create_game_step_1_title.php\">

	<p>Please log in...</p>
	";
}

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>