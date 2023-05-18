<?php
// Language
include("$root/_admin/_translations/site/$l/ad/ts_ad.php");

// Config
if(file_exists("$root/_admin/_data/ads.php")){
	include("$root/_admin/_data/ads.php");
	if($adsActiveSav == "1"){

		$time = time();
		$query = "SELECT ad_id, ad_html_code, ad_title, ad_text, ad_url, ad_image_path, ad_image_file, ad_video_file, ad_active_from_datetime, ad_active_from_time, ad_active_to_datetime, ad_active_to_time, ad_views, ad_clicks, ad_unique_clicks FROM $t_ads_index WHERE ad_active=1 AND ad_language=$l_mysql AND ad_placement='main_below_headline' AND ad_active_from_time < '$time' AND ad_active_to_time > '$time' ORDER BY ad_views ASC LIMIT 0,1";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_ad_id, $get_ad_html_code, $get_ad_title, $get_ad_text, $get_ad_url, $get_ad_image_path, $get_ad_image_file, $get_ad_video_file, $get_ad_active_from_datetime, $get_ad_active_from_time, $get_ad_active_to_datetime, $get_ad_active_to_time, $get_ad_views, $get_ad_clicks, $get_ad_unique_clicks) = $row;

		if($get_ad_id != ""){
			// Update views
			$inp_ad_views = $get_ad_views+1;
			$result = mysqli_query($link, "UPDATE $t_ads_index SET ad_views=$inp_ad_views WHERE ad_id=$get_ad_id") or die(mysqli_error($link));

			// HTML?
			if($get_ad_html_code != ""){
				echo"
				<div class=\"a_main_below_headline_html_code\">
					$get_ad_html_code
				</div>";
			}
			else{
				// Image?
				if(file_exists("$root/$get_ad_image_path/$get_ad_image_file") && $get_ad_image_file != ""){
					echo"
					<div class=\"a_main_below_headline_image\">
						<p>
						<span class=\"a_main_below_headline_image_ad_arrow\">$l_ad <img src=\"$root/ad/_gfx/mark_down_black.png\" alt=\"mark_down_black.png\" /></span><br />
						<a href=\"$root/ad/out.php?a_id=$get_ad_id&amp;process=1\" rel=\"nofollow\"><img src=\"$root/$get_ad_image_path/$get_ad_image_file\" alt=\"$get_ad_title\" /></a>
						</p>
					</div>";
				}
				else{
					// Text
					echo"
					<div class=\"a_main_below_headline_text\">
						<p>
						<span class=\"a_main_below_headline_text_ad_arrow\">$l_ad <img src=\"$root/ad/_gfx/mark_down_black.png\" alt=\"mark_down_black.png\" /></span><br />
						<a href=\"$root/ad/out.php?a_id=$get_ad_id&amp;process=1\" rel=\"nofollow\">$get_ad_title</a><br />
						$get_ad_text
						</p>
					</div>
					";
				}
			}
		}
	} // ads active
} // config exists
?>