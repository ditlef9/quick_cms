<?php
/**
*
* File: users/status_edit.php
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
include("$root/_admin/_data/logo.php");

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/users/ts_index.php");

/*- Variables --------------------------------------------------------------------------- */
if(isset($_GET['status_id'])) {
	$status_id = $_GET['status_id'];
	$status_id = strip_tags(stripslashes($status_id));
}
else{
	$status_id = "";
	echo"
	<h1>Error</h1>
	
	<p>Status not found</p>
	";
	die;
}
$status_id_mysql = quote_smart($link, $status_id);



/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_edit_status - $l_users";
include("$root/_webdesign/header.php");

// Get status
$q = "SELECT status_id, status_user_id, status_created_by_user_id, status_created_by_user_alias, status_created_by_user_image, status_created_by_ip, status_text, status_photo, status_datetime, status_datetime_print, status_time, status_language, status_likes, status_comments, status_reported, status_reported_checked, status_reported_reason, status_seen FROM $t_users_status WHERE status_id=$status_id_mysql";
$r = mysqli_query($link, $q);
$rowb = mysqli_fetch_row($r);
list($get_status_id, $get_status_user_id, $get_status_created_by_user_id, $get_status_created_by_user_alias, $get_status_created_by_user_image, $get_status_created_by_ip, $get_status_text, $get_status_photo, $get_status_datetime, $get_status_datetime_print, $get_status_time, $get_status_language, $get_status_likes, $get_status_comments, $get_status_reported, $get_status_reported_checked, $get_status_reported_reason, $get_status_seen) = $rowb;




if($get_status_id == ""){
	echo"
	<h1>Error</h1>
	
	<p>Status not found</p>
	";
	
}
else{
	if(isset($_SESSION['user_id'])){
		$my_user_id = $_SESSION['user_id'];
		$my_user_id_mysql = quote_smart($link, $my_user_id);
		$query = "SELECT user_id, user_alias, user_date_format FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_alias, $get_my_user_date_format) = $row;


		// My image
		$q = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id=$my_user_id_mysql AND photo_profile_image='1'";
		$r = mysqli_query($link, $q);
		$rowb = mysqli_fetch_row($r);
		list($get_my_photo_id, $get_my_photo_destination) = $rowb;	

		$inp_new_x = 40; // 950
		$inp_new_y = 40; // 640
		if(file_exists("$root/_uploads/users/images/$get_my_user_id/$get_my_photo_destination") && $get_my_photo_destination != ""){
			$thumb_full_path = "$root/_cache/user_" . $get_my_photo_destination . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
			if(!(file_exists("$thumb_full_path"))){
				resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_my_user_id/$get_my_photo_destination", "$thumb_full_path");
			}
			$thumb_full_path = "_cache/user_" . $get_my_photo_destination . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
		}
		else{
			$thumb_full_path = "_gfx/avatar_blank_40.png";
		}


	
		if($get_status_created_by_user_id == "$my_user_id"){

			if($process == "1"){
				
				// Text
				$inp_text = $_POST['inp_text'];
				$inp_text = output_html($inp_text);
				$inp_text_mysql = quote_smart($link, $inp_text);
				if($inp_text == ""){
					$url = "status_edit.php?status_id=$status_id&amp;l=$l&ft=error&fm=missing_text";
					header("Location: $url");
					die;
				}
				

				// My IP
				$my_ip = $_SERVER['REMOTE_ADDR'];
				$my_ip = output_html($my_ip);
				$my_ip_mysql = quote_smart($link, $my_ip);

				// Time, date
				$datetime = date("Y-m-d H:i:s");
				$datetime_print = date('j M y');
				$time = time();

				// Other variables
				$inp_my_user_alias_mysql = quote_smart($link, $get_my_user_alias);
				$inp_my_user_image_mysql = quote_smart($link, $get_my_photo_destination);

				// Update status

				mysqli_query($link, "UPDATE $t_users_status SET 
				status_created_by_user_alias=$inp_my_user_alias_mysql, status_created_by_user_image=$inp_my_user_image_mysql, status_created_by_ip=$my_ip_mysql, 
				status_text=$inp_text_mysql, status_datetime='$datetime', status_datetime_print='$datetime_print', status_time='$time'
				WHERE status_id=$get_status_id") or die(mysqli_error($link));


				$url = "view_profile.php?user_id=$get_status_user_id&l=$l&ft=success&fm=comment_edited#status$get_status_id";
				header("Location: $url");
				die;
			} // process


			echo"
			<h1>$l_edit_status</h1>

			<!-- Edit status -->
				<form method=\"post\" action=\"status_edit.php?status_id=$get_status_id&amp;l=$l&amp;process=1\" />


				<!-- Focus -->
					<script>
					\$(document).ready(function(){
						\$('[name=\"inp_text\"]').focus();
					});
					</script>
				<!-- //Focus -->

				<table style=\"width: 100%;\">
				 <tr>
				  <td style=\"width: 45px;vertical-align: top;\">
					<p><img src=\"$root/$thumb_full_path\" alt=\"$thumb_full_path\" /></p>
				  </td>
				  <td class=\"status\" style=\"vertical-align: top;\">
					<p>
					<textarea name=\"inp_text\" rows=\"3\" cols=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
					$get_status_text = str_replace("<br />", "\n", $get_status_text);
					echo"$get_status_text</textarea>
					</p>

					<p>
					<input type=\"submit\" value=\"$l_save_changes\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>
				  </td>
				 </tr>
				</table>

				</form>	
			<!-- //Edit status  -->
	
			<!-- //My report -->

			<!-- Back -->
				<p>
				<a href=\"view_profile.php?user_id=$get_status_user_id&amp;l=$l\"><img src=\"_gfx/go-previous.png\" alt=\"go-previous.png\" /></a>
				<a href=\"view_profile.php?user_id=$get_status_user_id&amp;l=$l\">$l_previous</a>
				</p>
			<!-- //Back -->
			";

		}  // access
		else{
			echo"Access denied";
		}
	} // logged in
	else{
		$url = "$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/users/stats_edit.php?status_id=$status_id";
		header("Location: $url");
		die;
	}
} // status found
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>