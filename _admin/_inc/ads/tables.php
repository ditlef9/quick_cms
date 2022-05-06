<?php
/**
*
* File: _admin/_inc/ads/tables.php
* Version 1.0.0
* Date 08:43 17.05.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Variables ---------------------------------------------------------------------------- */
if(isset($_GET['refererer'])) {
	$refererer = $_GET['refererer'];
	$refererer = strip_tags(stripslashes($refererer));
	if($refererer == "default"){
		echo"
		<table>
		 <tr> 
		  <td style=\"padding-right: 6px;\">
			<p>
			<img src=\"_design/gfx/loading_22.gif\" alt=\"Loading\" />
			</p>
		  </td>
		  <td>
			<h1>Loading...</h1>
		  </td>
		 </tr>
		</table>
		<meta http-equiv=\"refresh\" content=\"2;url=index.php?open=$open&amp;page=$refererer&amp;editor_language=$editor_language&amp;l=$l&amp;ft=success&amp;fm=module_installed\">
		";
	}
}

/*- Tables ---------------------------------------------------------------------------- */
$t_ads_index 		= $mysqlPrefixSav . "ads_index";
$t_ads_advertisers	= $mysqlPrefixSav . "ads_advertisers";

echo"
<h1>Tables</h1>


<!-- ads_index -->
	";
	$query = "SELECT * FROM $t_ads_index";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_ads_index: $row_cnt</p>
		";
	}
	else{
		echo"
		<table>
		 <tr> 
		  <td style=\"padding-right: 6px;\">
			<p>
			<img src=\"_design/gfx/loading_22.gif\" alt=\"Loading\" />
			</p>
		  </td>
		  <td>
			<h1>Loading...</h1>
		  </td>
		 </tr>
		</table>

		
		<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=ads\">
		";


		mysqli_query($link, "CREATE TABLE $t_ads_index(
	  	 ad_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(ad_id), 
	  	   ad_internal_name VARCHAR(250),
	  	   ad_active INT,
	  	   ad_html_code TEXT,
	  	   ad_title VARCHAR(250),
	  	   ad_text TEXT,
	  	   ad_url VARCHAR(250),
	  	   ad_language VARCHAR(20),
	  	   ad_image_path VARCHAR(250),
	  	   ad_image_file VARCHAR(250),
	  	   ad_video_file VARCHAR(250),
	  	   ad_placement VARCHAR(250),
		   ad_advertiser_id INT,
	  	   ad_active_from_datetime DATETIME,
	  	   ad_active_from_time VARCHAR(250),
	  	   ad_active_from_saying VARCHAR(250),
	  	   ad_active_from_year INT,
	  	   ad_active_from_month INT,
	  	   ad_active_from_day INT,
	  	   ad_active_from_hour INT,
	  	   ad_active_to_datetime DATETIME,
	  	   ad_active_to_time VARCHAR(250),
	  	   ad_active_to_saying VARCHAR(250),
	  	   ad_active_to_year INT,
	  	   ad_active_to_month INT,
	  	   ad_active_to_day INT,
	  	   ad_active_to_hour INT,
	  	   ad_expired_email_sent INT,
	  	   ad_views INT,
	  	   ad_clicks INT,
	  	   ad_unique_clicks INT,
	  	   ad_unique_clicks_ip_block TEXT,
	  	   ad_unique_last_clicked_datetime DATETIME,
	  	   ad_unique_last_clicked_saying VARCHAR(250),
	  	   ad_created_by_user_id INT,
	  	   ad_created_by_user_alias VARCHAR(250),
	  	   ad_created_datetime DATETIME,
	  	   ad_updated_by_user_id INT,
	  	   ad_updated_by_user_alias VARCHAR(250),
	  	   ad_updated_datetime DATETIME)")
		   or die(mysqli_error());




		mysqli_query($link, "INSERT INTO $t_ads_index
		(ad_id , ad_internal_name, ad_active , ad_html_code , ad_title , ad_text , ad_url , ad_language , ad_image_path , ad_image_file , ad_video_file , ad_placement , ad_advertiser_id , ad_active_from_datetime , ad_active_from_time , ad_active_from_saying , ad_active_from_year , ad_active_from_month , ad_active_from_day , ad_active_from_hour , ad_active_to_datetime , ad_active_to_time , ad_active_to_saying , ad_active_to_year , ad_active_to_month , ad_active_to_day , ad_active_to_hour , ad_expired_email_sent , ad_views , ad_clicks , ad_unique_clicks , ad_unique_clicks_ip_block , ad_created_by_user_id , ad_created_by_user_alias , ad_created_datetime , ad_updated_by_user_id , ad_updated_by_user_alias , ad_updated_datetime) 
		VALUES 
		(NULL, 'Ditlef text link en', 1, NULL, 'Program with Ditlef', 'Learn C, Java, Android, web development and more for free.', 'https://ditlef.net/courses', 'en', NULL, NULL, NULL, 'main_below_headline', 1, '2019-05-18 00:00:00', '1558137600', '18 May 2019', 2019, 5, 18, 0, '9999-01-01 00:00:00', '253370764800', '01 Jan 9999', 9999, 1, 1, 0, NULL, NULL, 0, 0, NULL, 1, 'Solo', '2019-05-18 12:24:37', 1, 'Solo', '2019-05-18 12:24:37'),
		(NULL, 'Ditlef text link no', 1, NULL, 'Programmer med Ditlef', 'L&aelig;r C, Java, Android, webutvikling med mer gratis.', 'https://ditlef.net/courses', 'no', NULL, NULL, NULL, 'main_below_headline', 1, '2019-05-18 00:00:00', '1558137600', '18 May 2019', 2019, 5, 18, 0, '9999-01-01 00:00:00', '253370764800', '01 Jan 9999', 9999, 1, 1, 0, NULL, NULL, 0, 0, NULL, 1, 'Solo', '2019-05-18 12:24:37', 1, 'Solo', '2019-05-18 12:24:37')")
		or die(mysqli_error($link));
		
	}
	echo"
<!-- ads_index -->


<!-- advertisers -->
	";
	$query = "SELECT * FROM $t_ads_advertisers";
	$result = mysqli_query($link, $query);
	if($result !== FALSE){
		// Count rows
		$row_cnt = mysqli_num_rows($result);
		echo"
		<p>$t_ads_advertisers: $row_cnt</p>
		";
	}
	else{
		
		mysqli_query($link, "CREATE TABLE $t_ads_advertisers(
	  	 advertiser_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(advertiser_id), 
	  	   advertiser_name VARCHAR(250), 
	  	   advertiser_website VARCHAR(250), 
	  	   advertiser_contact_name VARCHAR(250), 
	  	   advertiser_contact_email VARCHAR(250), 
	  	   advertiser_contact_phone VARCHAR(250))")
		   or die(mysqli_error());

		mysqli_query($link, "INSERT INTO $t_ads_advertisers
		(`advertiser_id`, `advertiser_name`, `advertiser_website`, `advertiser_contact_name`, `advertiser_contact_email`, `advertiser_contact_phone`) VALUES
		(NULL, 'Ditlef.net', 'https://ditlef.net', 'Sindre', 'ditlef@ditlef.net', '')")
		or die(mysqli_error($link));

	}
	echo"
<!-- advertisers -->
";


?>