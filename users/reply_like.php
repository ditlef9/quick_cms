<?php
/**
*
* File: users/reply_like.php
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
$website_title = "$l_users";
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
	
	if(isset($_SESSION['user_id'])){
		$my_user_id = $_SESSION['user_id'];
		$my_user_id_mysql = quote_smart($link, $my_user_id);
		$query = "SELECT user_id, user_alias, user_date_format FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_alias, $get_my_user_date_format) = $row;

		if($process == "1"){
			// Did I like this reply from before?
			$q = "SELECT like_id FROM $t_users_status_replies_likes WHERE like_reply_id=$get_reply_id AND like_user_id=$my_user_id_mysql";
			$r = mysqli_query($link, $q);
			$rowb = mysqli_fetch_row($r);
			list($get_like_id) = $rowb;	

			if($get_like_id== ""){
				$inp_my_user_alias_mysql = quote_smart($link, $get_my_user_alias);

				// Insert like
				mysqli_query($link, "INSERT INTO $t_users_status_replies_likes 
				(like_id, like_reply_id, like_user_id, like_user_alias) 
				VALUES 
				(NULL, '$get_reply_id', '$get_my_user_id', $inp_my_user_alias_mysql)")
				or die(mysqli_error($link));

				$inp_likes = $get_reply_likes+1;
			}
			else{
				$result = mysqli_query($link, "DELETE FROM $t_users_status_replies_likes WHERE like_id=$get_like_id") or die(mysqli_error($link));

				$inp_likes = $get_reply_likes-1;
			}

			// Update likes
			$result = mysqli_query($link, "UPDATE $t_users_status_replies SET reply_likes=$inp_likes WHERE reply_id=$reply_id_mysql") or die(mysqli_error($link));

			$url = "view_profile.php?user_id=$get_reply_user_id&l=$l&ft=success&fm=reply_liked#reply$get_reply_id";
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
		$url = "$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/users/stats_report.php?";
		header("Location: $url");
		die;
	}
} // user found
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>