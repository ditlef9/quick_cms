<?php 
/**
*
* File: ad/out.php
* Version 1.0.0
* Date 14:36 18.05.2019
* Copyright (c) 2019 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/


/*- Root dir -------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config -------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Tables ------ -------------------------------------------------------------------- */
include("_tables_ad.php");

/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);

if(isset($_GET['a_id'])){
	$a_id = $_GET['a_id'];
	$a_id = output_html($a_id);
}
else{
	$a_id = "";
}



// Get ad
$a_id_mysql = quote_smart($link, $a_id);
$query = "SELECT ad_id, ad_active, ad_html_code, ad_title, ad_text, ad_url, ad_language, ad_image_path, ad_image_file, ad_video_file, ad_placement, ad_advertiser_id, ad_active_from_datetime, ad_active_from_time, ad_active_from_saying, ad_active_from_year, ad_active_from_month, ad_active_from_day, ad_active_from_hour, ad_active_to_datetime, ad_active_to_time, ad_active_to_saying, ad_active_to_year, ad_active_to_month, ad_active_to_day, ad_active_to_hour, ad_expired_email_sent, ad_views, ad_clicks, ad_unique_clicks, ad_unique_clicks_ip_block, ad_created_by_user_id, ad_created_by_user_alias, ad_created_datetime, ad_updated_by_user_id, ad_updated_by_user_alias, ad_updated_datetime FROM $t_ads_index WHERE ad_id=$a_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_ad_id, $get_current_ad_active, $get_current_ad_html_code, $get_current_ad_title, $get_current_ad_text, $get_current_ad_url, $get_current_ad_language, $get_current_ad_image_path, $get_current_ad_image_file, $get_current_ad_video_file, $get_current_ad_placement, $get_current_ad_advertiser_id, $get_current_ad_active_from_datetime, $get_current_ad_active_from_time, $get_current_ad_active_from_saying, $get_current_ad_active_from_year, $get_current_ad_active_from_month, $get_current_ad_active_from_day, $get_current_ad_active_from_hour, $get_current_ad_active_to_datetime, $get_current_ad_active_to_time, $get_current_ad_active_to_saying, $get_current_ad_active_to_year, $get_current_ad_active_to_month, $get_current_ad_active_to_day, $get_current_ad_active_to_hour, $get_current_ad_expired_email_sent, $get_current_ad_views, $get_current_ad_clicks, $get_current_ad_unique_clicks, $get_current_ad_unique_clicks_ip_block, $get_current_ad_created_by_user_id, $get_current_ad_created_by_user_alias, $get_current_ad_created_datetime, $get_current_ad_updated_by_user_id, $get_current_ad_updated_by_user_alias, $get_current_ad_updated_datetime) = $row;

if($get_current_ad_id == ""){

	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "Ad - 404";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");
	echo"<p>Ad not found.</p>";
	
}
else{
	// Unique hits
	$inp_ip = $_SERVER['REMOTE_ADDR'];
	$inp_ip = output_html($inp_ip);

	$ip_block_array = explode("\n", $get_current_ad_unique_clicks_ip_block);
	$ip_block_array_size = sizeof($ip_block_array);
	
	if($ip_block_array_size > 30){
		$ip_block_array_size = 20;
	}
	
	$has_seen_this_before = 0;

	for($x=0;$x<$ip_block_array_size;$x++){
		if($ip_block_array[$x] == "$inp_ip"){
			$has_seen_this_before = 1;
			break;
		}
	}
		
	if($has_seen_this_before == 0){
		$ip_block = $inp_ip . "\n" . $get_current_ad_unique_clicks_ip_block;
		$ip_block = substr($ip_block, 0, 200);
		$ip_block_mysql = quote_smart($link, $ip_block);
		$inp_ad_clicks = $get_current_ad_clicks + 1;
		$inp_ad_unique_clicks = $get_current_ad_unique_clicks + 1;

		$datetime = date("Y-m-d");
		$saying = date("j M Y");

		$result = mysqli_query($link, "UPDATE $t_ads_index SET 
			ad_clicks=$inp_ad_clicks,
			ad_unique_clicks=$inp_ad_unique_clicks,
			ad_unique_clicks_ip_block=$ip_block_mysql, 
			ad_unique_last_clicked_datetime='$datetime',
			ad_unique_last_clicked_saying='$saying'
			 WHERE ad_id=$get_current_ad_id") or die(mysqli_error($link));
	}
	else{
		$inp_ad_clicks = $get_current_ad_clicks + 1;
		$result = mysqli_query($link, "UPDATE $t_ads_index SET 
			ad_clicks=$inp_ad_clicks
			 WHERE ad_id=$get_current_ad_id") or die(mysqli_error($link));
	}


	// Before we go, we want to check for ads that are out of date and send e-mail about them
	$query = "SELECT ad_id, ad_internal_name, ad_html_code, ad_title, ad_text, ad_url, ad_image_path, ad_image_file, ad_video_file, ad_active_from_datetime, ad_active_from_time, ad_active_from_saying, ad_active_to_datetime, ad_active_to_time, ad_active_to_saying, ad_views, ad_clicks, ad_unique_clicks FROM $t_ads_index WHERE ad_active=1 AND ad_language=$l_mysql AND ad_placement='main_below_headline' AND ad_active_to_time < '$time' ORDER BY ad_views ASC LIMIT 0,1";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_ad_id, $get_ad_internal_name, $get_ad_html_code, $get_ad_title, $get_ad_text, $get_ad_url, $get_ad_image_path, $get_ad_image_file, $get_ad_video_file, $get_ad_active_from_datetime, $get_ad_active_from_saying, $get_ad_active_from_time, $get_ad_active_to_datetime, $get_ad_active_to_time, $get_ad_active_to_saying, $get_ad_views, $get_ad_clicks, $get_ad_unique_clicks) = $row;
	if($get_ad_id != ""){

		// Set active = 0
		$result = mysqli_query($link, "UPDATE $t_ads_index SET ad_active=0 WHERE ad_id=$get_ad_id") or die(mysqli_error($link));

		// Send email to admins
		$query = "SELECT user_id, user_email, user_name, user_alias FROM $t_users WHERE user_rank='admin' OR user_rank='moderator'";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_admin_user_id, $get_admin_user_email, $get_admin_user_name, $get_admin_user_alias) = $row;



			// Mail from
			$subject = "Ad $get_ad_internal_name expired at $configWebsiteTitleSav";

			$message = "<html>\n";
			$message = $message. "<head>\n";
			$message = $message. "  <title>$subject</title>\n";
			$message = $message. " </head>\n";
			$message = $message. "<body>\n";

			$message = $message . "<p>Hi admin/moderator $get_admin_user_alias,</p>\n\n";
			$message = $message . "<p>The ad &quot;$get_ad_internal_name&quot; has expired, and its status has been updated to inactive.
			You can create new, edit and delete ads at the control panel 
			<a href=\"$configControlPanelURLSav/_admin/index.php?open=ads&amp;editor_language=$l&amp;l=$l\">ads</a>.</p>\n\n";

			$message = $message . "<table>\n";
			$message = $message . " <tr>\n";
			$message = $message . "  <td><span>ID:</span></td>\n";
			$message = $message . "  <td><span>$get_ad_id</span></td>\n";
			$message = $message . " </tr>\n";
			$message = $message . " <tr>\n";
			$message = $message . "  <td><span>Internal name:</span></td>\n";
			$message = $message . "  <td><span>$get_ad_internal_name</span></td>\n";
			$message = $message . " </tr>\n";
			$message = $message . " <tr>\n";
			$message = $message . "  <td><span>Title:</span></td>\n";
			$message = $message . "  <td><span>$get_ad_title</span></td>\n";
			$message = $message . " </tr>\n";
			$message = $message . " <tr>\n";
			$message = $message . "  <td><span>Text:</span></td>\n";
			$message = $message . "  <td><span>$get_ad_text</span></td>\n";
			$message = $message . " </tr>\n";
			$message = $message . " <tr>\n";
			$message = $message . "  <td><span>URL:</span></td>\n";
			$message = $message . "  <td><span>$get_ad_url</span></td>\n";
			$message = $message . " </tr>\n";
			$message = $message . " <tr>\n";
			$message = $message . "  <td><span>Image:</span></td>\n";
			$message = $message . "  <td><span>$configSiteURLSav/$get_ad_image_path$get_ad_image_file</span></td>\n";
			$message = $message . " </tr>\n";
			$message = $message . " <tr>\n";
			$message = $message . "  <td><span>Active from:</span></td>\n";
			$message = $message . "  <td><span>$get_ad_active_from_saying</span></td>\n";
			$message = $message . " </tr>\n";
			$message = $message . " <tr>\n";
			$message = $message . " <tr>\n";
			$message = $message . "  <td><span>Active to:</span></td>\n";
			$message = $message . "  <td><span>$get_ad_active_to_saying</span></td>\n";
			$message = $message . " </tr>\n";
			$message = $message . " <tr>\n";
			$message = $message . "  <td><span>Actions:</span></td>\n";
			$message = $message . "  <td>\n";
			$message = $message . "		<span><a href=\"$configControlPanelURLSav/_admin/index.php?open=ads&amp;page=edit_ad&amp;ad_id=$get_ad_id&amp;editor_language=$l&amp;l=$l\">Edit</a>\n";
			$message = $message . "		&middot;\n";
			$message = $message . "		<a href=\"$configControlPanelURLSav/_admin/index.php?open=ads&amp;page=delete_ad&amp;ad_id=$get_ad_id&amp;editor_language=$l&amp;l=$l\">Delete</a>\n";
			$message = $message . "		</span>\n";
			$message = $message . "  </td>\n";
			$message = $message . " </tr>\n";
			$message = $message . "</table>\n";

			$message = $message . "<p>\n\n--<br />\nBest regards<br />\n$configWebsiteTitleSav\n$configFromEmailSav</p>";
			$message = $message. "</body>\n";
			$message = $message. "</html>\n";

			// Email headers
			$headers = 'MIME-Version: 1.0' . "\r\n" .
			    'Content-type: text/html; charset=utf-8' . "\r\n" .
			    "From: $configFromNameSav <" . $configFromEmailSav . ">" . "\r\n" .
			    'X-Mailer: PHP/' . phpversion();

			mail($get_admin_user_email, $subject, $message, $headers);

		} // mail
	} // inactive ad found


	// Header
	header("Location: $get_current_ad_url");
	exit;
} //  ad found



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>