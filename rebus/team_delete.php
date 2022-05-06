<?php
/**
*
* File: rebus/team_delete.php
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
include("$root/_admin/_data/logo.php");
include("$root/_admin/_data/config/user_system.php");

/*- Tables ---------------------------------------------------------------------------- */
include("_tables_rebus.php");


/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);

if(isset($_GET['team_id'])) {
	$team_id = $_GET['team_id'];
	$team_id = output_html($team_id);
	if(!(is_numeric($team_id))){
		echo"Team id not numeric";
		die;
	}
}
else{
	echo"Missing team id";
	die;
}

$tabindex = 0;


/*- Translation ------------------------------------------------------------------------------- */


// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);


	/*- Find team ------------------------------------------------------------------------- */
	$team_id_mysql = quote_smart($link, $team_id);
	$query = "SELECT team_id, team_name, team_language, team_description, team_privacy, team_key, team_group_id, team_group_name, team_logo_path, team_logo_file, team_color, team_created_by_user_id, team_created_by_user_name, team_created_by_user_email, team_created_by_ip, team_created_by_hostname, team_created_by_user_agent, team_created_datetime, team_created_date_saying, team_updated_by_user_id, team_updated_by_user_name, team_updated_by_user_email, team_updated_by_ip, team_updated_by_hostname, team_updated_by_user_agent, team_updated_datetime, team_updated_date_saying FROM $t_rebus_teams_index WHERE team_id=$team_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_team_id, $get_current_team_name, $get_current_team_language, $get_current_team_description, $get_current_team_privacy, $get_current_team_key, $get_current_team_group_id, $get_current_team_group_name, $get_current_team_logo_path, $get_current_team_logo_file, $get_current_team_color, $get_current_team_created_by_user_id, $get_current_team_created_by_user_name, $get_current_team_created_by_user_email, $get_current_team_created_by_ip, $get_current_team_created_by_hostname, $get_current_team_created_by_user_agent, $get_current_team_created_datetime, $get_current_team_created_date_saying, $get_current_team_updated_by_user_id, $get_current_team_updated_by_user_name, $get_current_team_updated_by_user_email, $get_current_team_updated_by_ip, $get_current_team_updated_by_hostname, $get_current_team_updated_by_user_agent, $get_current_team_updated_datetime, $get_current_team_updated_date_saying) = $row;
	if($get_current_team_id == ""){
		$url = "teams.php?ft=error&fm=team_not_found&l=$l";
		header("Location: $url");
		exit;
	}

	/*- Check that I am a member of this team --------------------------------------------- */
	$query = "SELECT member_id, member_team_id, member_status, member_user_id, member_user_name, member_user_email, member_user_photo_destination, member_user_photo_thumb_50, member_joined_datetime, member_joined_date_saying FROM $t_rebus_teams_members WHERE member_team_id=$get_current_team_id AND member_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_member_id, $get_my_member_team_id, $get_my_member_status, $get_my_member_user_id, $get_my_member_user_name, $get_my_member_user_email, $get_my_member_user_photo_destination, $get_my_member_user_photo_thumb_50, $get_my_member_joined_datetime, $get_my_member_joined_date_saying) = $row;
	if($get_my_member_id == ""){
		$url = "teams.php?ft=error&fm=your_not_a_member_of_that_team&l=$l";
		header("Location: $url");
		exit;
	}

	// Access
	if($get_my_member_status != "admin" && $get_my_member_status != "moderator"){
		$url = "groups.php?ft=error&fm=access_denied&l=$l";
		header("Location: $url");
		exit;
	}

	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$get_current_team_name - $l_teams";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");

	if($process == "1"){
		
		// Delete group
		mysqli_query($link, "DELETE FROM $t_rebus_teams_index WHERE team_id=$get_current_team_id") or die(mysqli_error($link));


		// Delete logo
		if(file_exists("../$get_current_team_logo_path/$get_current_team_logo_file") && $get_current_team_logo_file != ""){
			unlink("../$get_current_team_logo_path/$get_current_team_logo_file");
		}

		// Delete members
		mysqli_query($link, "DELETE FROM $t_rebus_teams_members WHERE member_team_id=$get_current_team_id") or die(mysqli_error($link));


		$url = "teams.php?ft=success&fm=deleted&l=$l";
		header("Location: $url");
		exit;
	} // process

	echo"
	<!-- Headline -->
		<h1>$get_current_team_name</h1>
	<!-- //Headline -->

	<!-- Where am I ? -->
		<p><b>$l_you_are_here:</b><br />
		<a href=\"index.php?l=$l\">$l_rebus</a>
		&gt;
		<a href=\"teams.php?l=$l\">$l_teams</a>
		&gt;
		<a href=\"team_open.php?team_id=$get_current_team_id&amp;l=$l\">$get_current_team_name</a>
		&gt;
		<a href=\"team_delete.php?team_id=$get_current_team_id&amp;l=$l\">$l_delete_team</a>
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
	<!-- Delete team form-->
		<p>$l_are_you_sure</p>

		<p>
		<a href=\"team_delete.php?team_id=$get_current_team_id&amp;l=$l&amp;process=1\" class=\"btn_danger\">$l_confirm</a>
		</p>
	<!-- //Delete team form -->
	";
}
else{
	echo"
	<h1>
	<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=rebus/teams.php\">

	<p>Please log in...</p>
	";
}

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>