<?php
/**
*
* File: _admin/_inc/ads/delete_ad.php
* Version 1
* Date 08:57 17.05.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Functions ----------------------------------------------------------------------- */

/*- Tables ---------------------------------------------------------------------------- */
$t_ads_index		= $mysqlPrefixSav . "ads_index";
$t_ads_advertisers	= $mysqlPrefixSav . "ads_advertisers";


/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;
if(isset($_GET['ad_id'])) {
	$ad_id = $_GET['ad_id'];
	$ad_id = strip_tags(stripslashes($ad_id));
}
else{
	$ad_id = "";
}
$ad_id_mysql = quote_smart($link, $ad_id);

// Ad
$query = "SELECT ad_id, ad_active, ad_html_code, ad_title, ad_text, ad_url, ad_language, ad_image_path, ad_image_file, ad_video_file, ad_placement, ad_advertiser_id, ad_active_from_datetime, ad_active_from_time, ad_active_from_saying, ad_active_from_year, ad_active_from_month, ad_active_from_day, ad_active_from_hour, ad_active_to_datetime, ad_active_to_time, ad_active_to_saying, ad_active_to_year, ad_active_to_month, ad_active_to_day, ad_active_to_hour, ad_clicks, ad_unique_clicks, ad_unique_clicks_ip_block, ad_created_by_user_id, ad_created_by_user_alias, ad_created_datetime, ad_updated_by_user_id, ad_updated_by_user_alias, ad_updated_datetime FROM $t_ads_index WHERE ad_id=$ad_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_ad_id, $get_current_ad_active, $get_current_ad_html_code, $get_current_ad_title, $get_current_ad_text, $get_current_ad_url, $get_current_ad_language, $get_current_ad_image_path, $get_current_ad_image_file, $get_current_ad_video_file, $get_current_ad_placement, $get_current_ad_advertiser_id, $get_current_ad_active_from_datetime, $get_current_ad_active_from_time, $get_current_ad_active_from_saying, $get_current_ad_active_from_year, $get_current_ad_active_from_month, $get_current_ad_active_from_day, $get_current_ad_active_from_hour, $get_current_ad_active_to_datetime, $get_current_ad_active_to_time, $get_current_ad_active_to_saying, $get_current_ad_active_to_year, $get_current_ad_active_to_month, $get_current_ad_active_to_day, $get_current_ad_active_to_hour, $get_current_ad_clicks, $get_current_ad_unique_clicks, $get_current_ad_unique_clicks_ip_block, $get_current_ad_created_by_user_id, $get_current_ad_created_by_user_alias, $get_current_ad_created_datetime, $get_current_ad_updated_by_user_id, $get_current_ad_updated_by_user_alias, $get_current_ad_updated_datetime) = $row;

if($get_current_ad_id == ""){
	echo"<p>Ad not found</p>";
}
else{




	if($process == "1"){
		

		$result = mysqli_query($link, "DELETE FROM $t_ads_index WHERE ad_id=$get_current_ad_id") or die(mysqli_error($link));



		$url = "index.php?open=ads&editor_language=$editor_language&ft=success&fm=ad_deleted";
		header("Location: $url");
		exit;
	}
	echo"
	<h1>Delete ad</h1>

	<!-- Where am I ? -->
		<p>
		<b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Ads</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=edit_ad&amp;ad_id=$ad_id&amp;editor_language=$editor_language&amp;l=$l\">Edit Ad</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=edit_ad&amp;ad_id=$ad_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
		</p>
	<!-- Where am I ? -->


	<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
	<!-- //Feedback -->


	<!-- Form -->
		<p>
		Are you sure?
		</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;ad_id=$ad_id&amp;editor_language=$editor_language&amp;process=1\" class=\"btn_warning\">Delete ad</a>
		</p>
	<!-- //Delete ad -->
	";
}

?>