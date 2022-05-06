<?php
/**
*
* File: users/notifications.php
* Version 19:20 23.08.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
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
include("$root/_admin/_translations/site/$l/users/ts_edit_password.php");

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_notifications - $l_my_profile - $l_users";
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

	if($action == ""){
		// Count notifications, delete older than X
		$delete_date = date('Y-m-d H:i:s', strtotime('-12 months'));
		$result_delete = mysqli_query($link, "DELETE FROM $t_users_notifications WHERE notification_datetime < '$delete_date'") or die(mysqli_error($link));
		

		echo"
		<h1>$l_notifications</h1>

		<!-- You are here -->
			<div class=\"you_are_here\">
				<p>
				<b>$l_you_are_here:</b><br />
				<a href=\"my_profile.php?l=$l\">$l_my_profile</a>
				&gt; 
				<a href=\"notifications.php?l=$l\">$l_notifications</a>
				</p>
			</div>
		<!-- //You are here -->

		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				else{
					$fm = str_replace("_", " ", $fm);
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->


		<!-- Notifications -->
		
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>$l_notification</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_date</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_actions</span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>
		";
		$x = 0;		
		$query = "SELECT notification_id, notification_user_id, notification_seen, notification_url, notification_text, notification_datetime, notification_datetime_saying, notification_emailed, notification_week FROM $t_users_notifications WHERE notification_user_id=$get_user_id ORDER BY notification_id DESC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_notification_id, $get_notification_user_id, $get_notification_seen, $get_notification_url, $get_notification_text, $get_notification_datetime, $get_notification_datetime_saying, $get_notification_emailed, $get_notification_week) = $row;

			if(isset($odd) && $odd == false){
				$odd = true;
			}
			else{
				$odd = false;
			}
	
			echo"
			 <tr>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span><a href=\"notifications.php?action=visit&amp;notification_id=$get_notification_id&amp;l=$l&amp;process=1\""; if($get_notification_seen == "0"){ echo"style=\"font-weight: bold;\""; } echo">$get_notification_text</a></span>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span>$get_notification_datetime_saying</span>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span><a href=\"notifications.php?action=delete&amp;notification_id=$get_notification_id&amp;l=$l&amp;process=1\">$l_delete</a></span>
			  </td>
			 </tr>
			";

			$x++;
			if($x > 200){
				$result_delete = mysqli_query($link, "DELETE FROM $t_users_notifications WHERE notification_id=$get_notification_id") or die(mysqli_error($link));
			}
		}
		echo"
		  </tr>
		 </tbody>
		</table>
		<!-- //Notifications -->
		";
	}
	elseif($action == "visit"){
		if(isset($_GET['notification_id'])) {
			$notification_id = $_GET['notification_id'];
			$notification_id = strip_tags(stripslashes($notification_id));
		}
		else{
			$notification_id = "";
		}
		$notification_id_mysql = quote_smart($link, $notification_id);
		
		$query = "SELECT notification_id, notification_user_id, notification_seen, notification_url, notification_text, notification_datetime, notification_emailed, notification_week FROM $t_users_notifications WHERE notification_id=$notification_id_mysql AND notification_user_id=$get_user_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_notification_id, $get_current_notification_user_id, $get_current_notification_seen, $get_current_notification_url, $get_current_notification_text, $get_current_notification_datetime, $get_current_notification_emailed, $get_current_notification_week) = $row;
			
		// Update seen status
		$result = mysqli_query($link, "UPDATE $t_users_notifications SET notification_seen=1 WHERE notification_id=$get_current_notification_id");

		// Header
		header("Location: $get_current_notification_url");
		exit;
	} // action == visit
	elseif($action == "delete"){
		if(isset($_GET['notification_id'])) {
			$notification_id = $_GET['notification_id'];
			$notification_id = strip_tags(stripslashes($notification_id));
		}
		else{
			$notification_id = "";
		}
		$notification_id_mysql = quote_smart($link, $notification_id);
		
		$query = "SELECT notification_id, notification_user_id, notification_seen, notification_url, notification_text, notification_datetime, notification_datetime_saying, notification_emailed, notification_week FROM $t_users_notifications WHERE notification_id=$notification_id_mysql AND notification_user_id=$get_user_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_notification_id, $get_current_notification_user_id, $get_current_notification_seen, $get_current_notification_url, $get_current_notification_text, $get_current_notification_datetime, $get_current_notification_datetime_saying, $get_current_notification_emailed, $get_current_notification_week) = $row;
			
		// Delete
		$result = mysqli_query($link, "DELETE FROM $t_users_notifications WHERE notification_id=$get_current_notification_id");

		// Header
		$url = "notifications.php?ft=success&fm=deleted&l=$l";
		header("Location: $url");
		exit;
	} // action == delete
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