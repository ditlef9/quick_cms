<?php
/**
*
* File: rebus/team_accept_invitation.php
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
		echo"team id not numeric";
		die;
	}
}
else{
	echo"Missing teamid";
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

	/*- Check that I have invigation to this team --------------------------------------------- */
	$query = "SELECT member_id, member_team_id, member_user_id, member_user_name, member_user_email, member_user_photo_destination, member_user_photo_thumb_50, member_status, member_invited, member_user_accepted_invitation, member_accepted_by_moderator, member_joined_datetime, member_joined_date_saying, member_last_played FROM $t_rebus_teams_members WHERE member_team_id=$get_current_team_id AND member_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_member_id, $get_my_member_team_id, $get_my_member_user_id, $get_my_member_user_name, $get_my_member_user_email, $get_my_member_user_photo_destination, $get_my_member_user_photo_thumb_50, $get_my_member_status, $get_my_member_invited, $get_my_member_user_accepted_invitation, $get_my_member_accepted_by_moderator, $get_my_member_joined_datetime, $get_my_member_joined_date_saying, $get_my_member_last_played) = $row;
	if($get_my_member_id == ""){
		$url = "teams.php?ft=error&fm=no_invitation_pending_to_that_team&l=$l";
		header("Location: $url");
		exit;
	}

	// Check if I have accepted or not
	if($get_my_member_user_accepted_invitation == "0"){
		// Set me to accept

		$inp_status = "member";
		if($get_my_member_status == "member_invited"){
			$inp_status = "member";
		}
		elseif($get_my_member_status == "admin_invited"){
			$inp_status = "admin";
		}
		elseif($get_my_member_status == "moderator_invited"){
			$inp_status = "moderator";
		}
		$inp_status_mysql = quote_smart($link, $inp_status);

		mysqli_query($link, "UPDATE $t_rebus_teams_members SET
					member_status=$inp_status_mysql, 
					member_user_accepted_invitation=1 
					WHERE member_team_id=$get_current_team_id AND member_user_id=$my_user_id_mysql") or die(mysqli_error($link));

		// Email admin to let him know I accepted the invitation
		/*
		$query = "SELECT member_id, member_team_id, member_user_id, member_user_name, member_user_email, member_user_photo_destination, member_user_photo_thumb_50, member_status, member_invited, member_user_accepted_invitation, member_accepted_by_moderator, member_joined_datetime, member_joined_date_saying, member_last_played FROM $t_rebus_teams_members WHERE member_team_id=$get_current_team_id AND member_status='admin'";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_admin_member_id, $get_admin_member_team_id, $get_admin_member_user_id, $get_admin_member_user_name, $get_admin_member_user_email, $get_admin_member_user_photo_destination, $get_admin_member_user_photo_thumb_50, $get_admin_member_status, $get_admin_member_invited, $get_admin_member_user_accepted_invitation, $get_admin_member_accepted_by_moderator, $get_admin_member_joined_datetime, $get_admin_member_joined_date_saying, $get_admin_member_last_played) = $row;

		}
		*/
		
		// Header
		$url = "teams.php?ft=info&fm=invitation_to_team_accepted&l=$l";
		header("Location: $url");
		exit;
		
	}
	else{
		// I have accepted, so I cannot decline (I must instead delete myself from team)
		// Header
		$url = "teams.php?ft=info&fm=you_have_already_accepted_the_invitation&l=$l";
		header("Location: $url");
		exit;
	}
	
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