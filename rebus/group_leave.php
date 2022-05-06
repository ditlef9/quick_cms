<?php
/**
*
* File: rebus/group_leave.php
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

if(isset($_GET['group_id'])) {
	$group_id = $_GET['group_id'];
	$group_id = output_html($group_id);
	if(!(is_numeric($group_id))){
		echo"Group id not numeric";
		die;
	}
}
else{
	echo"Missing group id";
	die;
}

$tabindex = 0;


/*- Translation ------------------------------------------------------------------------------- */


// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);



	/*- Find group ------------------------------------------------------------------------- */
	$group_id_mysql = quote_smart($link, $group_id);
	$query = "SELECT group_id, group_name, group_language, group_description, group_privacy, group_key, group_logo_path, group_logo_file, group_created_by_user_id, group_created_by_user_name, group_created_by_user_email, group_created_by_ip, group_created_by_hostname, group_created_by_user_agent, group_created_datetime, group_created_date_saying, group_updated_by_user_id, group_updated_by_user_name, group_updated_by_user_email, group_updated_by_ip, group_updated_by_hostname, group_updated_by_user_agent, group_updated_datetime, group_updated_date_saying FROM $t_rebus_groups_index WHERE group_id=$group_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_group_id, $get_current_group_name, $get_current_group_language, $get_current_group_description, $get_current_group_privacy, $get_current_group_key, $get_current_group_logo_path, $get_current_group_logo_file, $get_current_group_created_by_user_id, $get_current_group_created_by_user_name, $get_current_group_created_by_user_email, $get_current_group_created_by_ip, $get_current_group_created_by_hostname, $get_current_group_created_by_user_agent, $get_current_group_created_datetime, $get_current_group_created_date_saying, $get_current_group_updated_by_user_id, $get_current_group_updated_by_user_name, $get_current_group_updated_by_user_email, $get_current_group_updated_by_ip, $get_current_group_updated_by_hostname, $get_current_group_updated_by_user_agent, $get_current_group_updated_datetime, $get_current_group_updated_date_saying) = $row;
	if($get_current_group_id == ""){
		$url = "groups.php?ft=error&fm=group_not_found&l=$l";
		header("Location: $url");
		exit;
	}

	/*- Check that I am a member of this group --------------------------------------------- */
	$query = "SELECT member_id, member_group_id, member_user_id, member_user_name, member_user_email, member_user_photo_destination, member_user_photo_thumb_50, member_status, member_invited, member_user_accepted_invitation, member_accepted_by_moderator, member_joined_datetime, member_joined_date_saying FROM $t_rebus_groups_members WHERE member_group_id=$group_id_mysql AND member_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_member_id, $get_my_member_group_id, $get_my_member_user_id, $get_my_member_user_name, $get_my_member_user_email, $get_my_member_user_photo_destination, $get_my_member_user_photo_thumb_50, $get_my_member_status, $get_my_member_invited, $get_my_member_user_accepted_invitation, $get_my_member_accepted_by_moderator, $get_my_member_joined_datetime, $get_my_member_joined_date_saying) = $row;
	if($get_my_member_id == ""){
		$url = "groups.php?ft=error&fm=your_not_a_member_of_that_group&l=$l";
		header("Location: $url");
		exit;
	}

	if($get_my_member_status == "admin"){
		// Check that I am not the only admin of the group
		// If I am, then I first need to select another group
		$query = "SELECT count(member_id) FROM $t_rebus_groups_members WHERE member_group_id=$get_current_group_id AND member_status='admin'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_count_admins) = $row;

		if($get_count_admins == "1"){
			$url = "group_members.php?group_id=$get_current_group_id&ft=info&fm=please_select_another_person_as_admin_before_you_leave_the_group&l=$l";
			header("Location: $url");
			exit;
		}
	}

	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$l_leave_group - $get_current_group_name - $l_groups - $l_rebus";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");

	if($process == "1"){
		mysqli_query($link, "DELETE FROM $t_rebus_groups_members WHERE member_group_id=$get_current_group_id AND member_user_id=$my_user_id_mysql") or die(mysqli_error($link));

		
		$url = "groups.php?ft=success&fm=you_have_left_the_group&l=$l";
		header("Location: $url");
		exit;

	} // process

	echo"
	<!-- Headline -->
		<h1>$get_current_group_name</h1>
	<!-- //Headline -->

	<!-- Where am I ? -->
		<p><b>$l_you_are_here:</b><br />
		<a href=\"index.php?l=$l\">$l_rebus</a>
		&gt;
		<a href=\"groups.php?l=$l\">$l_groups</a>
		&gt;
		<a href=\"group_open.php?group_id=$get_current_group_id&amp;l=$l\">$get_current_group_name</a>
		&gt;
		<a href=\"group_leave.php?group_id=$get_current_group_id&amp;l=$l\">$l_leave_group</a>
		</p>
	<!-- //Where am I ? -->

	<!-- Feedback -->
		";
		if($ft != "" && $fm != ""){
			$fm = ucfirst($fm);
			$fm = str_replace("_", " ", $fm);
			echo"<div class=\"$ft\"><p>$fm</p></div>";
		}

		if(isset($_GET['ft_logo']) && isset($_GET['fm_logo'])) {
			$ft_logo = $_GET['ft_logo'];
			$ft_logo = output_html($ft_logo);

			$fm_logo = $_GET['fm_logo'];
			$fm_logo = output_html($fm_logo);
			$fm_logo = str_replace("_", " ", $fm_logo);
			$fm_logo = ucfirst($fm_logo);
			echo"<div class=\"$ft_logo\"><p>$fm_logo</p></div>";
		}
		echo"
	<!-- //Feedback -->


	<!-- Leave group form-->
		<p>$l_are_you_sure_you_want_to_leave_the_group</p>

		<p>
		<a href=\"group_leave.php?group_id=$get_current_group_id&amp;l=$l&amp;process=1\" class=\"btn_danger\">$l_confirm</a>
		<a href=\"group_open.php?group_id=$get_current_group_id&amp;l=$l\" class=\"btn_default\">$l_cancel</a>
		</p>
		
		</form>
	<!-- //Leave group form -->


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