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
$website_title = "$l_users";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
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
	$my_user_id = $_SESSION['user_id'];
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$security = $_SESSION['security'];
	$security_mysql = quote_smart($link, $security);

	$query = "SELECT user_id, user_name, user_alias, user_language, user_date_format, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql AND user_security=$security_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_name, $get_my_user_alias, $get_my_user_language, $get_my_user_date_format, $get_my_user_rank) = $row;

	if($get_my_user_id == ""){
		echo"<h1>Error</h1><p>Error with user id.</p>"; 
		$_SESSION = array();
		session_destroy();
		die;
	}

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
		// Did I like this?
		$q = "SELECT sl_id FROM $t_users_status_likes WHERE sl_status_id='$get_status_id' AND sl_user_id=$my_user_id_mysql";
		$r = mysqli_query($link, $q);
		$rowb = mysqli_fetch_row($r);
		list($get_sl_id) = $rowb;

		// Ready inputs
		$inp_sl_status_id_mysql = quote_smart($link, $get_status_id);
		$inp_sl_user_alias_mysql = quote_smart($link, $get_my_user_alias);
		
		if($get_sl_id == ""){
			// Like it 
			mysqli_query($link, "INSERT INTO $t_users_status_likes
			(sl_id, sl_status_id, sl_user_id, sl_user_alias) 
			VALUES 
			(NULL, $inp_sl_status_id_mysql, $my_user_id_mysql, $inp_sl_user_alias_mysql)")
			or die(mysqli_error($link));

			// Update likes to post
			$inp_status_likes = $get_status_likes+1;
			$result = mysqli_query($link, "UPDATE $t_users_status SET status_likes='$inp_status_likes' WHERE status_id=$status_id_mysql");

		}
		else{
			// Remove like
			$result = mysqli_query($link, "DELETE FROM $t_users_status_likes WHERE sl_status_id=$inp_sl_status_id_mysql AND sl_user_id=$my_user_id_mysql");
			
			// Update likes to post
			$inp_status_likes = $get_status_likes-1;
			$result = mysqli_query($link, "UPDATE $t_users_status SET status_likes='$inp_status_likes' WHERE status_id=$status_id_mysql");
		}

		// Header
		$url = "index.php?category=users&page=edit_status&status_id=$status_id&l=$l#status$status_id";
		header("Location: $url");
		die;
	}
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