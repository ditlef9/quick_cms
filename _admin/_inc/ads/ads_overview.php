<?php
/**
*
* File: _admin/_inc/ads/ads_overview.php
* Version 1.0.0
* Date 09:10 21.01.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */
$t_ads_index		= $mysqlPrefixSav . "ads_index";
$t_ads_advertisers	= $mysqlPrefixSav . "ads_advertisers";


/*- Check if installed ---------------------------------------------------------------- */
$query = "SELECT * FROM $t_ads_index";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{
	echo"
	<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Running setup</p></div>
	<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=ads&amp;page=tables&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
	";
}


if($action == ""){
	echo"
	<h1>Ads</h1>

	<!-- Navigation -->
		<p>
		<a href=\"index.php?open=$open&amp;page=new_ad&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">New ad</a>
		<a href=\"index.php?open=$open&amp;page=advertisers&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Advertisers</a>
		</p>
	<!-- //Navigation -->
		
	<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($ft);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"
	<!-- //Feedback -->



	<!-- Ads -->

		<h2>Acitve ads</h2>
		
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>Title</span>
		   </th>
		   <th scope=\"col\">
			
		   </th>
		   <th scope=\"col\">
			<span>Views</span>
		   </th>
		   <th scope=\"col\">
			<span>Unique clicks</span>
		   </th>
		   <th scope=\"col\">
			<span>View/click</span>
		   </th>
		   <th scope=\"col\">
			<span>Active from</span>
		   </th>
		   <th scope=\"col\">
			<span>Active to</span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>
		";
		$query = "SELECT ad_id, ad_internal_name, ad_active, ad_html_code, ad_title, ad_text, ad_url, ad_language, ad_image_path, ad_image_file, ad_video_file, ad_placement, ad_advertiser_id, ad_active_from_datetime, ad_active_from_time, ad_active_from_saying, ad_active_from_year, ad_active_from_month, ad_active_from_day, ad_active_from_hour, ad_active_to_datetime, ad_active_to_time, ad_active_to_saying, ad_active_to_year, ad_active_to_month, ad_active_to_day, ad_active_to_hour, ad_expired_email_sent, ad_views, ad_clicks, ad_unique_clicks, ad_unique_clicks_ip_block, ad_unique_last_clicked_datetime, ad_unique_last_clicked_saying, ad_created_by_user_id, ad_created_by_user_alias, ad_created_datetime, ad_updated_by_user_id, ad_updated_by_user_alias, ad_updated_datetime FROM $t_ads_index WHERE ad_active='1' ORDER BY ad_internal_name ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_ad_id, $get_ad_internal_name, $get_ad_active, $get_ad_html_code, $get_ad_title, $get_ad_text, $get_ad_url, $get_ad_language, $get_ad_image_path, $get_ad_image_file, $get_ad_video_file, $get_ad_placement, $get_ad_advertiser_id, $get_ad_active_from_datetime, $get_ad_active_from_time, $get_ad_active_from_saying, $get_ad_active_from_year, $get_ad_active_from_month, $get_ad_active_from_day, $get_ad_active_from_hour, $get_ad_active_to_datetime, $get_ad_active_to_time, $get_ad_active_to_saying, $get_ad_active_to_year, $get_ad_active_to_month, $get_ad_active_to_day, $get_ad_active_to_hour, $get_ad_expired_email_sent, $get_ad_views, $get_ad_clicks, $get_ad_unique_clicks, $get_ad_unique_clicks_ip_block, $get_ad_unique_last_clicked_datetime, $get_ad_unique_last_clicked_saying, $get_ad_created_by_user_id, $get_ad_created_by_user_alias, $get_ad_created_datetime, $get_ad_updated_by_user_id, $get_ad_updated_by_user_alias, $get_ad_updated_datetime) = $row;

			if(isset($odd) && $odd == false){
				$odd = true;
			}
			else{
				$odd = false;
			}

			// Title
			if($get_ad_internal_name == ""){
				$get_ad_internal_name = "$get_ad_id";
			}

			// View click
			if($get_ad_unique_clicks == 0){
				$view_click = 0;
			}
			else{
				$view_click = $get_ad_views/$get_ad_unique_clicks;
			}

			echo"
			 <tr>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span>
				<a href=\"index.php?open=$open&amp;page=edit_ad&amp;ad_id=$get_ad_id&amp;editor_language=$editor_language&amp;l=$l\">$get_ad_internal_name</a>
				</span>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span>$get_ad_title</span>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span>$get_ad_views</span>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span>$get_ad_unique_clicks</span>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span>$view_click</span>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span>$get_ad_active_from_saying</span>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span>$get_ad_active_to_saying</span>
			  </td>
			 </tr>
			";
		}
		echo"
		 </tbody>
		</table>

		<h2>Inacitve ads</h2>
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>Title</span>
		   </th>
		   <th scope=\"col\">
			
		   </th>
		   <th scope=\"col\">
			<span>Views</span>
		   </th>
		   <th scope=\"col\">
			<span>Unique clicks</span>
		   </th>
		   <th scope=\"col\">
			<span>View/click</span>
		   </th>
		   <th scope=\"col\">
			<span>Active from</span>
		   </th>
		   <th scope=\"col\">
			<span>Active to</span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>
		";
		$query = "SELECT ad_id, ad_internal_name, ad_active, ad_html_code, ad_title, ad_text, ad_url, ad_language, ad_image_path, ad_image_file, ad_video_file, ad_placement, ad_advertiser_id, ad_active_from_datetime, ad_active_from_time, ad_active_from_saying, ad_active_from_year, ad_active_from_month, ad_active_from_day, ad_active_from_hour, ad_active_to_datetime, ad_active_to_time, ad_active_to_saying, ad_active_to_year, ad_active_to_month, ad_active_to_day, ad_active_to_hour, ad_expired_email_sent, ad_views, ad_clicks, ad_unique_clicks, ad_unique_clicks_ip_block, ad_unique_last_clicked_datetime, ad_unique_last_clicked_saying, ad_created_by_user_id, ad_created_by_user_alias, ad_created_datetime, ad_updated_by_user_id, ad_updated_by_user_alias, ad_updated_datetime FROM $t_ads_index WHERE ad_active='0' ORDER BY ad_internal_name ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_ad_id, $get_ad_internal_name, $get_ad_active, $get_ad_html_code, $get_ad_title, $get_ad_text, $get_ad_url, $get_ad_language, $get_ad_image_path, $get_ad_image_file, $get_ad_video_file, $get_ad_placement, $get_ad_advertiser_id, $get_ad_active_from_datetime, $get_ad_active_from_time, $get_ad_active_from_saying, $get_ad_active_from_year, $get_ad_active_from_month, $get_ad_active_from_day, $get_ad_active_from_hour, $get_ad_active_to_datetime, $get_ad_active_to_time, $get_ad_active_to_saying, $get_ad_active_to_year, $get_ad_active_to_month, $get_ad_active_to_day, $get_ad_active_to_hour, $get_ad_expired_email_sent, $get_ad_views, $get_ad_clicks, $get_ad_unique_clicks, $get_ad_unique_clicks_ip_block, $get_ad_unique_last_clicked_datetime, $get_ad_unique_last_clicked_saying, $get_ad_created_by_user_id, $get_ad_created_by_user_alias, $get_ad_created_datetime, $get_ad_updated_by_user_id, $get_ad_updated_by_user_alias, $get_ad_updated_datetime) = $row;

			if(isset($odd) && $odd == false){
				$odd = true;
			}
			else{
				$odd = false;
			}

			// Title
			if($get_ad_internal_name == ""){
				$get_ad_internal_name = "$get_ad_id";
			}

			// View click
			if($get_ad_unique_clicks == 0){
				$view_click = 0;
			}
			else{
				$view_click = $get_ad_views/$get_ad_unique_clicks;
			}

			echo"
			 <tr>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span>
				<a href=\"index.php?open=$open&amp;page=edit_ad&amp;ad_id=$get_ad_id&amp;editor_language=$editor_language&amp;l=$l\">$get_ad_internal_name</a>
				</span>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span>$get_ad_title</span>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span>$get_ad_views</span>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span>$get_ad_unique_clicks</span>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span>$view_click</span>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span>$get_ad_active_from_saying</span>
			  </td>
			  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
				<span>$get_ad_active_to_saying</span>
			  </td>
			 </tr>
			";
		}
		echo"
		 </tbody>
		</table>
	<!-- //Ads -->
	";
}
?>