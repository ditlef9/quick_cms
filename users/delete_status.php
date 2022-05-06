<?php
/**
*
* File: users/index.php
* Version 17.46 18.02.2017
* Copyright (c) 2009-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "0";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

/*- Root dir --------------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config --------------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/users/ts_index.php");

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_delete_status - $l_users";
include("$root/_webdesign/header.php");



/*- Content --------------------------------------------------------------------------- */

/*- Variables ------------------------------------------------------------------------- */
if (isset($_GET['status_id'])) {
	$status_id = $_GET['status_id'];
	$status_id = stripslashes(strip_tags($status_id));
}
else{
	$status_id = "";
}


if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	// Get user
	$user_id = $_SESSION['user_id'];
	$user_id_mysql = quote_smart($link, $user_id);
	$security = $_SESSION['security'];
	$security_mysql = quote_smart($link, $security);

	$query = "SELECT user_id, user_name, user_alias, user_language, user_date_format, user_rank FROM $t_users WHERE user_id=$user_id_mysql AND user_security=$security_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_name, $get_user_alias, $get_user_language, $get_user_date_format, $get_user_rank) = $row;

	$query = "SELECT profile_id, profile_user_id, profile_first_name, profile_middle_name, profile_last_name, profile_address_line_a, profile_address_line_b, profile_zip, profile_city, profile_country, profile_phone, profile_work, profile_university, profile_high_school, profile_languages, profile_website, profile_interested_in, profile_relationship, profile_about, profile_newsletter FROM $t_users_profile WHERE profile_user_id=$user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_profile_id, $get_profile_user_id, $get_profile_first_name, $get_profile_middle_name, $get_profile_last_name, $get_profile_address_line_a, $get_profile_address_line_b, $get_profile_zip, $get_profile_city, $get_profile_country, $get_profile_phone, $get_profile_work, $get_profile_university, $get_profile_high_school, $get_profile_languages, $get_profile_website, $get_profile_interested_in, $get_profile_relationship, $get_profile_about, $get_profile_newsletter) = $row;

	if($get_user_id == ""){
		echo"<h1>Error</h1><p>Error with user id.</p>"; 
		$_SESSION = array();
		session_destroy();
		die;
	}

	if($action == "do_delete_status"){
		// Get that status
		$status_id_mysql = quote_smart($link, $status_id);

		$query = "SELECT status_id, status_user_id, status_text, status_photo, status_datetime, status_language, status_likes, status_comments, status_reported, status_reported_checked FROM $t_users_status WHERE status_id=$status_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_status_id, $get_status_user_id, $get_status_text, $get_status_photo, $get_status_datetime, $get_status_language, $get_status_likes, $get_status_comments, $get_status_reported, $get_status_reported_checked) = $row;
	
		if($get_status_id == ""){
			echo"
			<h1>Server error 404</h1>

			<div class=\"alert alert-danger\" role=\"alert\">
				<span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span>
				<span class=\"sr-only\">Error:</span>
				<span>$l_status_not_found</span>
			</div>

			<p>
			<a href=\"index.php?category=users&amp;page=edit_status&amp;l=$l\"><img src=\"$root/_webdesign/images/icons/16x16/go-previous.png\" style=\"float: left;padding: 0px 4px 0px 0px;\" alt=\"go-previous.png\" /></a>
			<a href=\"index.php?category=users&amp;page=edit_status&amp;l=$l\">$l_status</a>
			</p>
			";
		}
		else{
			if($get_user_id != "$get_status_user_id"){
				echo"
				<h1>Server error 403</h1>

				<div class=\"alert alert-danger\" role=\"alert\">
					<span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span>
					<span class=\"sr-only\">Error:</span>
					<span>$l_access_denied</span>
				</div>
				<p>$l_only_administrator_moderator_editor_and_the_owner_can_edit</p>
				";
			}
			else{
				
				// Delete image
				if($get_status_photo != ""){
					unlink("$get_status_photo");
				}

				// Delete status
				$result = mysqli_query($link, "DELETE FROM $t_users_status WHERE status_id=$status_id_mysql");



				// Header
				$url = "index.php?category=users&page=edit_status&l=$l&ft=success&fm=status_deleted";
				$url = $url . "#status$status_id";
				header("Location: $url");
				die;
			}
		}
	} // do_delete_status
	elseif($action == ""){
		// Get that status
		$status_id_mysql = quote_smart($link, $status_id);

		$query = "SELECT status_id, status_user_id, status_text, status_photo, status_datetime, status_language, status_likes, status_comments, status_reported, status_reported_checked FROM $t_users_status WHERE status_id=$status_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_status_id, $get_status_user_id, $get_status_text, $get_status_photo, $get_status_datetime, $get_status_language, $get_status_likes, $get_status_comments, $get_status_reported, $get_status_reported_checked) = $row;
	
		if($get_status_id == ""){
			echo"
			<h1>Server error 404</h1>

			<div class=\"alert alert-danger\" role=\"alert\">
				<span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span>
				<span class=\"sr-only\">Error:</span>
				<span>$l_status_not_found</span>
			</div>

			<p>
			<a href=\"index.php?category=users&amp;page=edit_status&amp;l=$l\"><img src=\"$root/_webdesign/images/icons/16x16/go-previous.png\" style=\"float: left;padding: 0px 4px 0px 0px;\" alt=\"go-previous.png\" /></a>
			<a href=\"index.php?category=users&amp;page=edit_status&amp;l=$l\">$l_status</a>
			</p>
			";
		}
		else{
			if($get_user_id != "$get_status_user_id"){
				echo"
				<h1>Server error 403</h1>

				<div class=\"alert alert-danger\" role=\"alert\">
					<span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span>
					<span class=\"sr-only\">Error:</span>
					<span>$l_access_denied</span>
				</div>
				<p>$l_only_administrator_moderator_editor_and_the_owner_can_edit</p>
				";
			}
			else{

				// Get profile image
				$q = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id=$user_id_mysql AND photo_profile_image='1'";
				$r = mysqli_query($link, $q);
				$rowb = mysqli_fetch_row($r);
				list($get_photo_id, $get_photo_destination) = $rowb;

				echo"
				<h1>$l_delete_status</h1>


				<p>
				$l_are_you_sure_you_want_to_delete_status
				</p>
				
				<p>";
				$status_security = $get_status_datetime;
				echo"
				<a href=\"index.php?category=users&amp;page=delete_status&amp;action=do_delete_status&amp;process=1&amp;status_id=$status_id&amp;status_security=$status_security&amp;l=$l\"><img src=\"$root/_webdesign/images/icons/16x16/delete.png\" alt=\"delete.png\" /></a>
				<a href=\"index.php?category=users&amp;page=delete_status&amp;action=do_delete_status&amp;process=1&amp;status_id=$status_id&amp;status_security=$status_security&amp;l=$l\">$l_delete</a>
				&nbsp;
				<a href=\"index.php?category=users&amp;page=edit_status&amp;l=$l#status$status_id\"><img src=\"$root/_webdesign/images/icons/16x16/go-previous.png\" alt=\"go-previous.png\" /></a>
				<a href=\"index.php?category=users&amp;page=edit_status&amp;l=$l#status$status_id\">$l_cancel</a>
				</p>
				";
			} // if($get_user_id != "$get_status_user_id"){
		} // if($get_status_id != ""){
	} // action == ""
}
else{
	echo"
	<table>
	 <tr> 
	  <td style=\"padding-right: 6px;\">
		<p>
		<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"Loading\" />
		</p>
	  </td>
	  <td>
		<h1>Loading</h1>
	  </td>
	 </tr>
	</table>
		
	<meta http-equiv=\"refresh\" content=\"1;url=index.php\">
	";
}
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>