<?php
/**
*
* File: users/status_like.php
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
$website_title = "$l_users";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
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

		if($process == "1"){
			// Did I like this status from before?
			$q = "SELECT like_id, like_status_id, like_user_id, like_user_alias FROM $t_users_status_likes WHERE like_status_id=$get_status_id AND like_user_id=$my_user_id_mysql";
			$r = mysqli_query($link, $q);
			$rowb = mysqli_fetch_row($r);
			list($get_like_id, $get_like_status_id, $get_like_user_id, $get_like_user_alias) = $rowb;	

			if($get_like_id == ""){
				$inp_my_user_alias_mysql = quote_smart($link, $get_my_user_alias);

				// Insert like
				mysqli_query($link, "INSERT INTO $t_users_status_likes 
				(like_id, like_status_id, like_user_id, like_user_alias) 
				VALUES 
				(NULL, '$get_status_id', '$get_my_user_id', $inp_my_user_alias_mysql)")
				or die(mysqli_error($link));

				$inp_likes = $get_status_likes+1;
			}
			else{
				$result = mysqli_query($link, "DELETE FROM $t_users_status_likes WHERE like_id=$get_like_id") or die(mysqli_error($link));

				$inp_likes = $get_status_likes-1;
			}

			// Update likes
			$result = mysqli_query($link, "UPDATE $t_users_status SET status_likes=$inp_likes WHERE status_id=$get_status_id") or die(mysqli_error($link));


			$url = "view_profile.php?user_id=$get_status_user_id&l=$l&ft=success&fm=comment_liked#status$get_status_id";
			header("Location: $url");
			die;



		} // process


		echo"
		<h1>?</h1>


		<!-- Back -->";
			$l_s_profile_lowercase = str_replace("&amp;nbsp;", " ", $l_s_profile_lowercase);
			echo"
			<p>
			<a href=\"view_profile.php?user_id=$get_status_user_id&amp;l=$get_current_user_language\"><img src=\"_gfx/go-previous.png\" alt=\"go-previous.png\" /></a>
			<a href=\"view_profile.php?user_id=$get_status_user_id&amp;l=$get_current_user_language\">$get_current_user_alias$l_s_profile_lowercase</a>
			</p>
		<!-- //Back -->
		";

	} // im logged in
	else{
		$url = "$root/users/login.php?l=$l&referer=$root/users/status_like.php?status_id=$status_id";
		header("Location: $url");
		die;
	}
} // user found
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>