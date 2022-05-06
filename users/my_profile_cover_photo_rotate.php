<?php
/**
*
* File: users/my_profile_cover_photo_rotate.php
* Version 11:07 08.08.2021
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
$website_title = "$l_rotate_cover_photo - $l_my_profile - $l_users";
include("$root/_webdesign/header.php");



/*- Content --------------------------------------------------------------------------- */


if(isset($_SESSION['user_id']) && isset($_SESSION['security']) && $process == "1"){
	// Variables
	if(isset($_GET['cover_photo_id'])) {
		$cover_photo_id = $_GET['cover_photo_id'];
		$cover_photo_id = strip_tags(stripslashes($cover_photo_id));
	}
	else{
		$cover_photo_id = "";
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
	
	// Get extention
	function getExtension($str) {
		$i = strrpos($str,".");
		if (!$i) { return ""; } 
		$l = strlen($str) - $i;
		$ext = substr($str,$i+1,$l);
		return $ext;
	}
	$extension = getExtension($get_cover_photo_destination);
	$extension = strtolower($extension);


	// Get a new name
	$datetime = date("ymdhis");
	$new_name = $get_cover_photo_user_id . "_" . $datetime . "." . $extension;
	$new_name_mysql = quote_smart($link, $new_name);

	// Update table
	$result = mysqli_query($link, "UPDATE $t_users_cover_photos SET cover_photo_destination=$new_name_mysql WHERE cover_photo_id=$cover_photo_id_mysql");

	// Rename
	rename("$root/_uploads/users/images/$get_user_id/cover_photos/$get_cover_photo_destination", "$root/_uploads/users/images/$get_user_id/cover_photos/$new_name");

	// Transfer variable name
	$get_cover_photo_destination = "$new_name";


	if($extension == "jpg"){
		// Load
		$source = imagecreatefromjpeg("$root/_uploads/users/images/$get_user_id/cover_photos/$get_cover_photo_destination");

		// Rotate
		if($rotate == ""){
			$rotate = imagerotate($source, -90, 0);
		}
		else{
			$rotate = imagerotate($source, 90, 0);
		}

		// Save
		imagejpeg($rotate, "$root/_uploads/users/images/$get_user_id/cover_photos/$get_cover_photo_destination");
	}
	elseif($extension == "png"){
		// Load
		$source = imagecreatefrompng("$root/_uploads/users/images/$get_user_id/cover_photos/$get_cover_photo_destination");

		// Bg
		$bgColor = imagecolorallocatealpha($source, 255, 255, 255, 127);

		// Rotate
		if($rotate == ""){
			$rotate = imagerotate($source, -90, $bgColor);
		}
		else{
			$rotate = imagerotate($source, 90, $bgColor);
		}
	
		// Save
		imagesavealpha($rotate, true);
		imagepng($rotate, "$root/_uploads/users/images/$get_user_id/cover_photos/$get_cover_photo_destination");

	}




	// Remove all temp files
	$filenames = "";
	$dir = "_cache/";
	$dirLen = strlen($dir);
	$dp = @opendir($dir);

	while($file = @readdir($dp)) $filenames [] = $file;

	for ($i = 0; $i < count($filenames); $i++){
		$content = $filenames[$i];
		$file_path = "$dir$content";

		if($file_path != "$dir." && $file_path != "$dir.."){
			unlink("$file_path");
		}
	}


	// Header
	$url = "my_profile_cover_photo_edit.php?cover_photo_id=$cover_photo_id&l=$l&ft=success&fm=photo_rotated";
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