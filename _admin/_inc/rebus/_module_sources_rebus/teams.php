<?php
/**
*
* File: rebus/teams.php
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


/*- Translation ------------------------------------------------------------------------ */


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_teams - $l_rebus";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");


// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);

	echo"
	<!-- Headline -->
		<h1>$l_teams</h1>
	<!-- //Headline -->

	<!-- Where am I ? -->
		<p><b>$l_you_are_here:</b><br />
		<a href=\"index.php?l=$l\">$l_rebus</a>
		&gt;
		<a href=\"teams.php?l=$l\">$l_teams</a>
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

	<!-- About teams -->
		<p>
		$l_a_team_is_a_collection_of_your_friends_or_colleagues.
		$l_it_can_be_a_part_of_a 
		<a href=\"groups.php?l=$l\">$l_group_lowercase</a>. 	
		$l_if_you_are_working_at_a_company_then_you_can_first_create_a_group_for_your_company_then_add_teams_to_the_group.
		$l_teams_play_togheter.
		</p>
	<!-- //About teams -->

	<!-- Actions and Sorting -->
		<p>
		<a href=\"team_new.php?l=$l\" class=\"btn_default\">$l_new_team</a>
		</p>
	<!-- //Actions and Sorting -->
		
	<!-- Team and group inviations -->";
		// Get teams where I am member but hasnt accepted
		$count_invitations = 0;
		$query = "SELECT member_id, member_team_id, team_name FROM $t_rebus_teams_members JOIN $t_rebus_teams_index ON $t_rebus_teams_members.member_team_id=$t_rebus_teams_index.team_id WHERE member_user_id=$my_user_id_mysql AND member_invited=1 AND member_user_accepted_invitation=0 ORDER BY $t_rebus_teams_index.team_name ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_member_id, $get_member_team_id, $get_team_name) = $row;

			if($count_invitations == "0"){
				echo"
				<!-- Team invitaions -->
					<div class=\"bodycell\">

						<h2>$l_team_invitations</h2>

						<p>$l_you_are_invitited_to_the_join_the_following_teams</p>
						<table>
				";
			}
			echo"
						 <tr>
						  <td>
							<p><a href=\"team_open.php?team_id=$get_member_team_id\">$get_team_name</a></p>
						  </td>
						  <td>
							<p>
							<a href=\"team_accept_invitation.php?team_id=$get_member_team_id&amp;l=$l\" class=\"btn_default\"><img src=\"_gfx/checked.png\" alt=\"checked.png\" /> $l_accept</a>
							<a href=\"team_decline_invitation.php?team_id=$get_member_team_id&amp;l=$l\" class=\"btn_default\"><img src=\"_gfx/decline.png\" alt=\"decline.png\" /> $l_decline</a>
							</p>
						  </td>
						 </tr>
			";
			$count_invitations++;
		}
		if($count_invitations > 0){
			echo"
						</table>
					</div>
				<!-- //Team invitaions -->
			";
		}
		echo"
	<!-- //Team and group inviations -->
	<!-- My groups -->
		<div class=\"vertical\">
			<ul>\n";
			// Get teams where I am member
			$query = "SELECT member_id, member_team_id, team_name FROM $t_rebus_teams_members JOIN $t_rebus_teams_index ON $t_rebus_teams_members.member_team_id=$t_rebus_teams_index.team_id WHERE $t_rebus_teams_members.member_user_id=$my_user_id_mysql AND $t_rebus_teams_members.member_user_accepted_invitation=1 ORDER BY $t_rebus_teams_index.team_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_member_id, $get_member_team_id, $get_team_name) = $row;

				echo"				";
				echo"<li><a href=\"team_open.php?team_id=$get_member_team_id\">$get_team_name</a></li>\n";
			}

		echo"
			</ul>
		</div>
	<!-- //My groups -->
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