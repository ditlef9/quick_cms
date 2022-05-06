<?php
/**
*
* File: users/my_profile_cover_photo.php
* Version 11:06 08.08.2021
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
$website_title = "$l_cover_photo - $l_my_profile - $l_users";
include("$root/_webdesign/header.php");



/*- Content --------------------------------------------------------------------------- */

if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
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

	if($action == ""){
		echo"
		<h1>$l_cover_photo</h1>

		<!-- You are here -->
			<div class=\"you_are_here\">
				<p>
				<b>$l_you_are_here:</b><br />
				<a href=\"my_profile.php?l=$l\">$l_my_profile</a>
				&gt; 
				<a href=\"my_profile_cover_photo.php?l=$l\">$l_cover_photo</a>
				</p>
			</div>
		<!-- //You are here -->

		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "photo_not_found_in_database"){
					$fm = "$l_photo_not_found_in_database";
				}
				elseif($fm == "photo_not_found"){
					$fm = "$l_photo_not_found";
				}
				elseif($fm == "photo_deleted"){
					$fm = "$l_photo_deleted";
				}
				elseif($fm == "photo_rotated"){
					$fm = "$l_photo_rotated";
				}
				elseif($fm == "photo_uploaded"){
					$fm = "$l_photo_uploaded";
				}
				elseif($fm == "photo_sat_as_profile_photo"){
					$fm = "$l_photo_sat_as_profile_photo";
				}
				elseif($fm == "unknown_file_format"){
					$fm = "$l_unknown_file_format";
				}
				elseif($fm == "photo_unknown_error"){
					$fm = "$l_photo_unknown_error";
				}
				elseif($fm == "photo_could_not_be_uploaded_please_check_file_size"){
					$fm = "$l_photo_could_not_be_uploaded_please_check_file_size";
				}
				else{
					$fm = "$ft";
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->

		<!-- Upload new cover photo -->
			<form method=\"POST\" action=\"my_profile_cover_photo_upload.php?action=upload&amp;l=$l&amp;process=1\" id=\"upload_cover_photo_form\" enctype=\"multipart/form-data\">

			<p>$l_upload_cover_photo:<br />
			<input type=\"file\" id=\"uploadBtn\" class=\"upload\" name=\"inp_image\" />
			<input type=\"submit\" value=\"$l_upload\" class=\"btn\" />	
			</p>
		<!-- //Upload new cover photo -->

		<!-- Display cover photos -->
			<div class=\"wrap\">";

			// Selected profile photo


			// Rest of photos
			$query = "SELECT cover_photo_id, cover_photo_destination FROM $t_users_cover_photos WHERE cover_photo_user_id='$get_user_id' ORDER BY cover_photo_is_current DESC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_cover_photo_id, $get_cover_photo_destination) = $row;
				
				if(file_exists("$root/_uploads/users/images/$user_id/cover_photos/$get_cover_photo_destination")){
					echo"
					<p>
					<a id=\"cover_photo$get_cover_photo_id\"></a>		
					<a href=\"my_profile_cover_photo_edit.php?cover_photo_id=$get_cover_photo_id&amp;l=$l\"><img src=\"$root/_uploads/users/images/$user_id/cover_photos/$get_cover_photo_destination\" alt=\"$get_cover_photo_destination\" /></a>
					</p>
						
					";
				}
				else{
					echo"<div class=\"clear\"></div>
					<div class=\"error\"><p>Image not found.. Deleting from MySQL</p></div>";
					$rd = mysqli_query($link, "DELETE FROM $t_users_cover_photos WHERE cover_photo_id='$get_cover_photo_id' AND cover_photo_user_id='$get_user_id'");
				}
			}

			echo"
			</div>
		<!-- //Display cover photos -->
		";
	}
}
else{
	echo"
	<table>
	 <tr> 
	  <td style=\"padding-right: 6px;\">
		<p>
		<img src=\"_webdesign/images/loading_22.gif\" alt=\"Loading\" />
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