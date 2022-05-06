<?php
/**
*
* File: users/my_profile_photos_delete.php
* Version 10:57 08.08.2021
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
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");



/*- Content --------------------------------------------------------------------------- */


if(isset($_SESSION['user_id']) && isset($_SESSION['security']) && $process == "1"){
	// Variables
	if(isset($_GET['photo_id'])) {
		$photo_id = $_GET['photo_id'];
		$photo_id = strip_tags(stripslashes($photo_id));
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

	// Get photo id
	$photo_id_mysql = quote_smart($link, $photo_id);
	$query = "SELECT photo_id, photo_user_id, photo_profile_image, photo_destination FROM $t_users_profile_photo WHERE photo_id=$photo_id_mysql AND photo_user_id=$user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_photo_id, $get_photo_user_id, $get_photo_profile_image, $get_photo_destination) = $row;

	if($get_photo_id == ""){
		$url = "my_profile_photos.php?l=$l&ft=warning&fm=photo_not_found_in_database"; 
		header("Location: $url");
		die;
	}

	if(!(file_exists("$root/_uploads/users/images/$get_user_id/$get_photo_destination"))){
		$url = "my_profile_photos.php?l=$l&ft=warning&fm=photo_not_found"; 
		header("Location: $url");
		die;
	}

	
	// Delete from MySQL
	$result = mysqli_query($link, "DELETE FROM $t_users_profile_photo WHERE photo_id='$get_photo_id'");

	// Delete photo
	unlink("$root/_uploads/users/images/$get_user_id/$get_photo_destination");

	// Delete thumb
	$thumb = str_replace("_org", "_thumb", $get_photo_destination);
	if(file_exists("$root/_uploads/users/images/$get_user_id/$thumb")){
		unlink("$root/_uploads/users/images/$get_user_id/$thumb");
	}

	// Check if this was my profile photo
	if($get_photo_profile_image == "1"){
		// get a new photo to use as profile photo
		$query = "SELECT photo_id, photo_user_id, photo_profile_image, photo_destination FROM $t_users_profile_photo WHERE photo_user_id=$user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_photo_id, $get_photo_user_id, $get_users_profile_photo, $get_photo_destination) = $row;
		
		if($get_photo_id != ""){
			$result = mysqli_query($link, "UPDATE $t_users_profile_photo SET photo_profile_image='1' WHERE photo_id=$get_photo_id");
		}
	}


	// Header
	$url = "my_profile_photos.php?l=$l&ft=success&fm=photo_deleted";
	if($refer != ""){
		$url = $url . "&" . $refer;
	}
	$url = $url . "#photo$photo_id";
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