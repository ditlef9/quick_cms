<?php 
/**
*
* File: office_calendar/delete_event.php
* Version 1.0
* Date 21:03 04.08.2019
* Copyright (c) 2019 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "1";
$pageAuthorUserIdSav  = "1";

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

/*- Language --------------------------------------------------------------------------- */
include("$root/_admin/_translations/site/$l/office_calendar/ts_office_calendar.php");
include("$root/_admin/_translations/site/$l/office_calendar/ts_add_event.php");
include("$root/_admin/_translations/site/$l/users/ts_users.php");

/*- Tables ---------------------------------------------------------------------------- */
include("tables_office_calendar.php");


/*- Variables -------------------------------------------------------------------------- */
$tabindex = 0;
if(isset($_GET['event_id'])) {
	$event_id = $_GET['event_id'];
	$event_id = strip_tags(stripslashes($event_id));
}
else{
	$event_id = "";
}
$event_id_mysql = quote_smart($link, $event_id);

/*- Content ---------------------------------------------------------------------------------- */
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){

	// Me
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);

	$query = "SELECT user_id, user_email, user_name, user_alias, user_language, user_last_online, user_rank, user_login_tries FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_language, $get_my_user_last_online, $get_my_user_rank, $get_my_user_login_tries) = $row;
	
	// Event
	$query = "SELECT event_id, event_user_id, event_user_name, event_location_id, event_location_title, event_equipment_id, event_equipment_title, event_text, event_bg_color, event_text_color, event_from_datetime, event_from_time, event_from_day, event_from_month, event_from_year, event_from_hour, event_from_minute, event_to_datetime, event_to_time, event_to_day, event_to_month, event_to_year, event_to_hour, event_to_minute FROM $t_office_calendar_events WHERE event_id=$event_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_event_id, $get_current_event_user_id, $get_current_event_user_name, $get_current_event_location_id, $get_current_event_location_title, $get_current_event_equipment_id, $get_current_event_equipment_title, $get_current_event_text, $get_current_event_bg_color, $get_current_event_text_color, $get_current_event_from_datetime, $get_current_event_from_time, $get_current_event_from_day, $get_current_event_from_month, $get_current_event_from_year, $get_current_event_from_hour, $get_current_event_from_minute, $get_current_event_to_datetime, $get_current_event_to_time, $get_current_event_to_day, $get_current_event_to_month, $get_current_event_to_year, $get_current_event_to_hour, $get_current_event_to_minute) = $row;
	
	if($get_current_event_id == ""){
		/*- Headers ---------------------------------------------------------------------------------- */
		$website_title = "$l_office_calendar - 404 server error";
		if(file_exists("./favicon.ico")){ $root = "."; }
		elseif(file_exists("../favicon.ico")){ $root = ".."; }
		elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
		elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
		include("$root/_webdesign/header.php");
		echo"
		<h1>Event not found</h1>
		";
	}
	else{
			
		/*- Headers ---------------------------------------------------------------------------------- */
		$website_title = "$l_office_calendar - $l_event";
		if(file_exists("./favicon.ico")){ $root = "."; }
		elseif(file_exists("../favicon.ico")){ $root = ".."; }
		elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
		elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
		include("$root/_webdesign/header.php");

		if($process == "1"){
			
			// Delete
			$result = mysqli_query($link, "DELETE FROM $t_office_calendar_events WHERE event_id=$get_current_event_id") or die(mysqli_error($link));

			// Search engine 
			$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name='office_calendar' AND index_reference_name='event_id' AND index_reference_id=$get_current_event_id";
			$result_exists = mysqli_query($link, $query_exists);
			$row_exists = mysqli_fetch_row($result_exists);
			list($get_index_id) = $row_exists;
			if($get_index_id != ""){
				$result = mysqli_query($link, "DELETE FROM $t_search_engine_index WHERE index_id=$get_index_id") or die(mysqli_error($link));
			}

			$url = "index.php?year=$get_current_event_from_year&month=$get_current_event_from_month&l=$l&ft=success&fm=deleted_event";
			header("Location: $url");
			exit;
		
		}
		echo"
		<h1>$l_delete_event</h1>
	
		<!-- Where am I? -->
			<p style=\"padding-top:0;margin-top:0;\"><b>$l_you_are_here:</b><br />
			<a href=\"index.php?year=$get_current_event_from_year&amp;month=$get_current_event_from_month&amp;l=$l\">$l_office_calendar</a>
			&gt;
			<a href=\"edit_event.php?event_id=$get_current_event_id&amp;l=$l\">$l_event</a>
			&gt;
			<a href=\"delete_event.php?event_id=$get_current_event_id&amp;l=$l\">$l_delete</a>
			</p>
			<div style=\"height: 10px;\"></div>
		<!-- //Where am I? -->

		<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
				$fm = str_replace("_", " ", $fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->

		

		<!-- Delete event form -->
			<p>$l_are_you_sure</p>

			<p>
			<a href=\"delete_event.php?event_id=$get_current_event_id&amp;l=$l&amp;process=1\" class=\"btn_danger\">$l_confirm</a>
			<a href=\"index.php?year=$get_current_event_from_year&amp;month=$get_current_event_from_month&amp;l=$l\" class=\"btn_default\">$l_cancel</a>
			</p>
		<!-- //Delete event form -->	
		";
	} // event foound
} // logged in
else{
	// Log in
	echo"
	<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" /> $l_please_log_in...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=office_calendar\">
	";
} // not logged in
/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/$webdesignSav/footer.php");
?>