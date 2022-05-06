<?php
/**
*
* File: _webdesign/picture_it/header.php
* Version 3.1
* Date 17:55 07.02.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Web design start ------------------------------------------------------------------ */
if($process != "1") {
echo"<!DOCTYPE html>
<html lang=\"$l\">
<head>
	<title>";
	if(isset($website_title)){
		echo"$website_title - $configWebsiteTitleSav";
	}
	else{
		$website_title = $_SERVER['PHP_SELF'];
		$website_title = str_replace("/", " - ", $website_title);
		$website_title = str_replace(".php", "", $website_title);
		$website_title = str_replace("_", " ", $website_title);
		$website_title = ucwords($website_title);
		echo"$website_title - $configWebsiteTitleSav";
	}
	echo"</title>

	<!-- Site CSS-->
		<link rel=\"stylesheet\" type=\"text/css\" href=\"$root/_webdesign/$webdesignSav/master.css?rand="; $datetime = date("Y-m-d H:i:s"); echo"$datetime\" />
	<!-- //Site CSS -->

	<!-- Special CSS -->
		";
		if(isset($pageCSSFile)){
			if(file_exists("$pageCSSFile")){
				echo"<link rel=\"stylesheet\" type=\"text/css\" href=\"$pageCSSFile?rand=$datetime\" />";
			}
			else{
				echo"<!-- <link rel=\"stylesheet\" type=\"text/css\" href=\"$pageCSSFile\" /> -->";
			}
		}
		echo"
	<!-- //Special CSS -->

	<!-- Favicon -->";
		if(file_exists("$root/_uploads/favicon/16x16.png")){
			echo"\n	<link rel=\"icon\" href=\"$root/_uploads/favicon/16x16.png\" type=\"image/png\" sizes=\"16x16\" />";
		}
		if(file_exists("$root/_uploads/favicon/32x32.png")){
			echo"\n	<link rel=\"icon\" href=\"$root/_uploads/favicon/32x32.png\" type=\"image/png\" sizes=\"32x32\" />";
		}
		if(file_exists("$root/_uploads/favicon/260x260.png")){
			echo"\n	<link rel=\"icon\" href=\"$root/_uploads/favicon/260x260.png\" type=\"image/png\" sizes=\"260x260\" />";
		}
		echo"	
	<!-- //Favicon -->

	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
	<meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0;\"/>

	<!-- jQuery -->
		<script type=\"text/javascript\" src=\"$root/_admin/_javascripts/jquery/jquery.min.js\"></script>
	<!-- //jQuery -->
</head>
<body>
<a id=\"top\"></a>

<!-- Header -->
	<header>
		<div class=\"header_inner\">

			<!-- Logo -->
				<div id=\"header_logo\">

					<a href=\"$root/index.php?l=$l\" title=\"$configWebsiteTitleSav\">$configWebsiteTitleSav</a>
				</div>
			<!-- //Logo -->


			<!-- Header menu -->

				<!-- Hide show search, Header hamburger -->
					<script>
					\$(document).ready(function(){
					\$(\".header_show_search\").click(function () {
						\$(\".site_search\").fadeToggle();
						\$(\"#inp_site_search_query\").focus();
					});
					\$(\".header_menu_icon\").click(function () {
						\$(\"nav\").fadeToggle();
						var src = (\$(this).attr('src') === '$root/_webdesign/$webdesignSav/images/header/menu_24x24_white.png')
								? '$root/_webdesign/$webdesignSav/images/header/clear_24x24_white.png'
								: '$root/_webdesign/$webdesignSav/images/header/menu_24x24_white.png';
						\$(\".header_menu_icon\").attr('src', src);
					});
					});
					</script>
				<!-- //Hide show nav + change hamburger icon -->


				<div class=\"header_menu\">
					<ul>
						<li><a href=\"#\" class=\"header_show_menu\"><img src=\"$root/_webdesign/$webdesignSav/images/header/menu_24x24_white.png\" alt=\"menu_24x24_white.png\" class=\"header_menu_icon\" /></a></li>
						<li><a href=\"$root/search/index.php?l=$l\"><img src=\"$root/_webdesign/$webdesignSav/images/header/search_24x24_white.png\" alt=\"search_24x24_white.png\" /></a></li>
					
					";
					// Am I logged in?
					if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
						// Get my user alias, date format, profile image
						$my_user_id 	  = $_SESSION['user_id'];
						$my_user_id_mysql = quote_smart($link, $my_user_id);
						$query = "SELECT user_id, user_alias, user_date_format FROM $t_users WHERE user_id=$my_user_id_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_my_user_id, $get_my_user_alias, $get_my_user_date_format) = $row;
						if($get_my_user_id == ""){
							echo"<p style=\"color:red\">Session error</p>";
							unset($_SESSION['user_id']);
						}
						echo"
						<li><a href=\"$root/users/my_profile.php?l=$l\"><img src=\"$root/_webdesign/$webdesignSav/images/header/profile_24x24_white.png\" alt=\"profile_24x24_white.png\" title=\"$get_my_user_alias\" /></a></li>
						";
					}
					else{
						echo"
						<li><a href=\"$root/users/login.php?l=$l&amp;referer=../users/my_profile.php\"><img src=\"$root/_webdesign/$webdesignSav/images/header/login_24x24_white.png\" alt=\"login_24x24_white.png\" title=\"$l_login\" /></a></li>
						";
					}
				
					echo"
					</ul>
				</div>
			<!-- //Header menu -->
		</div> <!-- //header_inner -->
	</header>
<!-- //Header -->

<!-- Navigation -->
	<nav>
		<ul>\n";
		// Chat
		include("$root/chat/_tables_chat.php");
		$look_for_chat_messages = 0;
		$navigation_language_mysql = quote_smart($link, $l);
		$query_nav_main = "SELECT navigation_id, navigation_parent_id, navigation_title, navigation_title_clean, navigation_url, navigation_url_path, navigation_url_path_md5, navigation_url_query, navigation_internal_or_external, navigation_icon_path, navigation_icon_18x18_inactive, navigation_icon_18x18_hover, navigation_icon_18x18_active FROM $t_pages_navigation WHERE navigation_parent_id='0' AND navigation_language=$navigation_language_mysql ORDER BY navigation_weight ASC";
		$result_nav_main = mysqli_query($link, $query_nav_main);
		$row_cnt_nav_main = mysqli_num_rows($result_nav_main);
		while($row_nav_main = mysqli_fetch_row($result_nav_main)) {
			list($get_a_navigation_id, $get_a_navigation_a_id, $get_a_navigation_title, $get_a_navigation_title_clean, $get_a_navigation_url, $get_a_navigation_url_path, $get_a_navigation_url_path_md5, $get_a_navigation_url_query, $get_a_navigation_internal_or_external, $get_a_navigation_icon_path, $get_a_navigation_icon_18x18_inactive, $get_a_navigation_icon_18x18_hover, $get_a_navigation_icon_18x18_active) = $row_nav_main;
			echo"			";
			echo"<li><a href=\"$root/$get_a_navigation_url_path$get_a_navigation_url_query\" class=\"nav_$get_a_navigation_url_path_md5\">$get_a_navigation_title</a></li>\n";

			// Children level 2
			$found_child = 0;
			$query_c = "SELECT navigation_id, navigation_parent_id, navigation_title, navigation_title_clean, navigation_url, navigation_url_path, navigation_url_path_md5, navigation_url_query, navigation_internal_or_external, navigation_icon_path, navigation_icon_18x18_inactive, navigation_icon_18x18_hover, navigation_icon_18x18_active FROM $t_pages_navigation WHERE navigation_parent_id=$get_a_navigation_id AND navigation_language=$navigation_language_mysql ORDER BY navigation_weight ASC";
			$result_c = mysqli_query($link, $query_c);
			while($row_c = mysqli_fetch_row($result_c)) {
				list($get_b_navigation_id, $get_b_navigation_b_id, $get_b_navigation_title, $get_b_navigation_title_clean, $get_b_navigation_url, $get_b_navigation_url_path, $get_b_navigation_url_path_md5, $get_b_navigation_url_query, $get_b_navigation_internal_or_external, $get_b_navigation_icon_path, $get_b_navigation_icon_18x18_inactive, $get_b_navigation_icon_18x18_hover, $get_b_navigation_icon_18x18_active) = $row_c;
				
				if($found_child == "0"){
					$found_child = "1";
					echo"				<ul>\n";				
				}

				echo"				";
				echo"<li><a href=\"$root/$get_b_navigation_url_path$get_b_navigation_url_query\" class=\"nav_$get_b_navigation_url_path_md5\">$get_b_navigation_title</a></li>\n";
			}
			if($found_child == "1"){
					echo"				</ul>\n";
			}
		}
		echo"
		</ul>
	</nav>
<!-- //Navigation -->



<!-- Content -->
	<main"; if(isset($main_class)){ echo" class=\"$main_class\""; } echo">
		<div class=\"main_inner\">

	";
	

} // process != 1
?>