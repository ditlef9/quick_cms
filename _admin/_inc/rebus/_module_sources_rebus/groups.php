<?php
/**
*
* File: rebus/groups.php
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

if(isset($_GET['order_by'])) {
	$order_by = $_GET['order_by'];
	$order_by = strip_tags(stripslashes($order_by));
}
else{
	$order_by = "food_id";
}
if(isset($_GET['order_method'])) {
	$order_method = $_GET['order_method'];
	$order_method = strip_tags(stripslashes($order_method));
}
else{
	$order_method = "DESC";
}

/*- Translation ------------------------------------------------------------------------ */


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_groups - $l_rebus";
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
		<h1>$l_groups</h1>
	<!-- //Headline -->

	<!-- Where am I ? -->
		<p><b>$l_you_are_here:</b><br />
		<a href=\"index.php?l=$l\">$l_rebus</a>
		&gt;
		<a href=\"groups.php?l=$l\">$l_groups</a>
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



	<!-- Actions and Sorting -->
		<p>
		<a href=\"group_new.php?l=$l\" class=\"btn_default\">$l_new_group</a>
		</p>
	<!-- //Actions and Sorting -->
		


	<!-- Group inviations -->";

		// Get groups where I am member but hasnt accepted
		$count_invitations = 0;
		$query = "SELECT member_id, member_group_id, group_name FROM $t_rebus_groups_members";
		$query = $query . " JOIN $t_rebus_groups_index ON $t_rebus_groups_members.member_group_id=$t_rebus_groups_index.group_id";
		$query = $query . " WHERE member_user_id=$my_user_id_mysql AND member_invited=1 AND member_user_accepted_invitation=0";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_member_id, $get_member_group_id, $get_group_name) = $row;

			if($count_invitations == "0"){
				echo"
				<!-- Team invitaions -->
					<h2>$l_group_invitations</h2>

						<p>$l_you_are_invitited_to_the_join_the_following_groups</p>
						<table>
				";
			}
			echo"
						 <tr>
						  <td>
							<p><a href=\"group_open.php?group_id=$get_member_group_id&amp;l=$l\">$get_group_name</a></p>
						  </td>
						  <td>
							<p>
							<a href=\"group_accept_invitation.php?group_id=$get_member_group_id&amp;l=$l\" class=\"btn_default\"><img src=\"_gfx/checked.png\" alt=\"checked.png\" /> $l_accept</a>
							<a href=\"group_decline_invitation.php?group_id=$get_member_group_id&amp;l=$l\" class=\"btn_default\"><img src=\"_gfx/decline.png\" alt=\"decline.png\" /> $l_decline</a>
							</p>
						  </td>
						 </tr>
			";
			$count_invitations++;
		}
		if($count_invitations > 0){
			echo"
						</table>
					<hr />
					<h2>$l_my_groups</h2>
				<!-- //Group invitaions -->
			";
		}
	echo"
	<!-- //Group inviations -->



	<!-- My groups -->
		<div class=\"vertical\">
			<ul>\n";
			// Get groups where I am member
			$query = "SELECT member_id, member_group_id, group_name FROM $t_rebus_groups_members JOIN $t_rebus_groups_index ON $t_rebus_groups_members.member_group_id=$t_rebus_groups_index.group_id WHERE member_user_id=$my_user_id_mysql AND member_user_accepted_invitation=1 AND member_accepted_by_moderator=1 ORDER BY $t_rebus_groups_index.group_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_member_id, $get_member_group_id, $get_group_name) = $row;

				echo"				";
				echo"<li><a href=\"group_open.php?group_id=$get_member_group_id\">$get_group_name</a></li>\n";
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
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=rebus/groups.php\">

	<p>Please log in...</p>
	";
}

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>