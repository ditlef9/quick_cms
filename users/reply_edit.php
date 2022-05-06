<?php
/**
*
* File: users/reply_edit.php
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
if(isset($_GET['reply_id'])) {
	$reply_id = $_GET['reply_id'];
	$reply_id = strip_tags(stripslashes($reply_id));
}
else{
	$reply_id = "";
	echo"
	<h1>Error</h1>
	
	<p>Reply not found</p>
	";
	die;
}
$reply_id_mysql = quote_smart($link, $reply_id);



/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_edit_reply - $l_users";
include("$root/_webdesign/header.php");

// Get reply
$q = "SELECT reply_id, reply_status_id, reply_user_id, reply_parent_id, reply_created_by_user_id, reply_created_by_user_alias, reply_created_by_user_image, reply_created_by_ip, reply_text, reply_likes, reply_datetime, reply_datetime_print, reply_time, reply_reported, reply_reported_checked, reply_reported_reason, reply_seen FROM $t_users_status_replies WHERE reply_id=$reply_id_mysql";
$r = mysqli_query($link, $q);
$rowb = mysqli_fetch_row($r);
list($get_reply_id, $get_reply_status_id, $get_reply_user_id, $get_reply_parent_id, $get_reply_created_by_user_id, $get_reply_created_by_user_alias, $get_reply_created_by_user_image, $get_reply_created_by_ip, $get_reply_text, $get_reply_likes, $get_reply_datetime, $get_reply_datetime_print, $get_reply_time, $get_reply_reported, $get_reply_reported_checked, $get_reply_reported_reason, $get_reply_seen) = $rowb;




if($get_reply_id == ""){
	echo"
	<h1>Error</h1>
	
	<p>Reply not found</p>
	";
	
}
else{
	// Thumb
	$inp_new_x = 40; // 950
	$inp_new_y = 40; // 640
	if(file_exists("$root/_uploads/users/images/$get_reply_created_by_user_id/$get_reply_created_by_user_image") && $get_reply_created_by_user_image != ""){
		$reply_created_by_thumb_full_path = "$root/_cache/user_" . $get_reply_created_by_user_image . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
		if(!(file_exists("$reply_created_by_thumb_full_path"))){
			resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_reply_created_by_user_id/$get_reply_created_by_user_image", "$thumb_full_path");
		}
		$reply_created_by_thumb_full_path = "_cache/user_" . $get_reply_created_by_user_image . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
	}
	else{
		$reply_created_by_thumb_full_path = "users/_gfx/avatar_blank_40.png";
	}


	if(isset($_SESSION['user_id'])){
		$my_user_id = $_SESSION['user_id'];
		$my_user_id_mysql = quote_smart($link, $my_user_id);
		$query = "SELECT user_id, user_alias, user_date_format FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_alias, $get_my_user_date_format) = $row;


		if($get_reply_created_by_user_id == "$get_my_user_id"){

			if($process == "1"){
				
				$inp_text = $_POST['inp_text'];
				$inp_text = output_html($inp_text);
				$inp_text_mysql = quote_smart($link, $inp_text);
				if($inp_text == ""){
					$url = "status_report.php?status_id=$status_id&amp;l=$l&ft=error&fm=missing_text";
					header("Location: $url");
					die;

				}
				$inp_text = $inp_text . "\nReport by $get_my_user_alias";
	
				// Edit reply
				$result = mysqli_query($link, "UPDATE $t_users_status_replies SET reply_text=$inp_text_mysql WHERE reply_id=$get_reply_id") or die(mysqli_error($link));





				$url = "view_profile.php?user_id=$get_reply_user_id&l=$l&ft=success&fm=reply_edited#reply$get_reply_id";
				header("Location: $url");
				die;



			} // process


			echo"
			<h1>$l_edit_reply</h1>

			<!-- Reply -->
				<!-- Focus -->
					<script>
					\$(document).ready(function(){
						\$('[name=\"inp_text\"]').focus();
					});
					</script>
				<!-- //Focus -->

				<form method=\"post\" action=\"reply_edit.php?reply_id=$get_reply_id&amp;l=$l&amp;process=1\" />


				<table style=\"width: 100%;\">
				 <tr>
				  <td style=\"width: 45px;vertical-align: top;\">
					<p><img src=\"$root/$reply_created_by_thumb_full_path\" alt=\"$reply_created_by_thumb_full_path\" /></p>
				  </td>
				  <td class=\"status\" style=\"vertical-align: top;\">
					
					<p>
					<textarea name=\"inp_text\" rows=\"3\" cols=\"40\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">$get_reply_text</textarea><br />
					
					<input type=\"submit\" value=\"$l_send\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>
				  </td>
				 </tr>
				</table>
			<!-- //Reply  -->


			<!-- Back -->
				<p>
				<a href=\"view_profile.php?user_id=$get_reply_user_id&amp;l=$l\"><img src=\"_gfx/go-previous.png\" alt=\"go-previous.png\" /></a>
				<a href=\"view_profile.php?user_id=$get_reply_user_id&amp;l=$l\">$l_previous</a>
				</p>
			<!-- //Back -->
		";
		} // access
		else{
			echo"<p>Access denied</p>
			<p>Only user id $get_reply_created_by_user_id can edit this reply. You are user id $get_my_user_id.</p>
			";
		}

	} // im logged in
	else{
		$url = "$root/users/login.php?l=$l&referer=$root/users/reply_edit.php?reply_id=$reply_id";
		header("Location: $url");
		die;
	}
} // reply found
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>