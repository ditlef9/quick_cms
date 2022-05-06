<?php
/**
*
* File: users/my_profile_cover_photo_set_current.php
* Version 11:08 08.08.2021
* Copyright (c) 2009-2021 Sindre Andre Ditlefsen
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
$website_title = "$l_users";
include("$root/_webdesign/header.php");



/*- Content --------------------------------------------------------------------------- */



if(isset($_SESSION['user_id']) && isset($_SESSION['security']) && $process == "1"){
	// Variables
	if(isset($_GET['cover_photo_id'])) {
		$cover_photo_id = $_GET['cover_photo_id'];
		$cover_photo_id = strip_tags(stripslashes($cover_photo_id));
	}
	else{
		$photo_id = "";
	}
	if(isset($_GET['rotate'])) {
		$rotate = $_GET['rotate'];
		$rotate = strip_tags(stripslashes($rotate));
	}
	else{
		$rotate = "";
	}
	if(isset($_GET['refer'])) {
		$refer = $_GET['refer'];
		$refer = strip_tags(stripslashes($refer));
		$refer = str_replace("amp;", "&", $refer);
	}
	else{
		$refer = "";
	}


	// Get user
	$user_id = $_SESSION['user_id'];
	$user_id_mysql = quote_smart($link, $user_id);
	$security = $_SESSION['security'];
	$security_mysql = quote_smart($link, $security);

	$query = "SELECT user_id, user_name, user_language, user_rank FROM $t_users WHERE user_id=$user_id_mysql AND user_security=$security_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_name, $get_user_language, $get_user_rank) = $row;

	if($get_user_id == ""){
		echo"<h1>Error</h1><p>Error with user id.</p>"; 
		$_SESSION = array();
		session_destroy();
		die;
	}

	// Get cover photo id
	$cover_photo_id_mysql = quote_smart($link, $cover_photo_id);
	$query = "SELECT cover_photo_id, cover_photo_user_id, cover_photo_is_current, cover_photo_destination FROM $t_users_cover_photos WHERE cover_photo_id=$cover_photo_id_mysql AND cover_photo_user_id=$user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_cover_photo_id, $get_cover_photo_user_id, $get_cover_photo_is_current, $get_cover_photo_destination) = $row;

	if($get_cover_photo_id == ""){
		$url = "my_profile_cover_photo.php?l=$l&ft=warning&fm=photo_not_found_in_database"; 
		header("Location: $url");
		die;
	}

	if(!(file_exists("$root/_uploads/users/images/$get_user_id/cover_photos/$get_cover_photo_destination"))){
		$url = "my_profile_cover_photo.php?l=$l&ft=warning&fm=photo_not_found"; 
		header("Location: $url");
		die;
	}
	
	// Set all photos to 0 on current
	$result = mysqli_query($link, "UPDATE $t_users_cover_photos SET cover_photo_is_current='0' WHERE cover_photo_user_id=$user_id_mysql");

	// Set this photo to 1 on current
	$result = mysqli_query($link, "UPDATE $t_users_cover_photos SET cover_photo_is_current='1' WHERE cover_photo_id=$cover_photo_id_mysql");

	// Header
	$url = "my_profile_cover_photo.php?l=$l&ft=success&fm=cover_photo_is_now_current";
	if($refer != ""){
		$url = $url . "&" . $refer;
	}
	$url = $url . "#cover_photo$cover_photo_id";
	header("Location: $url");
	die;
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