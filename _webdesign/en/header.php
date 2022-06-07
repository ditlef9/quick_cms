<?php
/**
*
* File: en/_webdesign/en/header.php
* Version 12:28 10.04.2019
* Copyright (c) 2009-2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/



/*- Website config -------------------------------------------------------------------- */
if(!(isset($server_name))){
	include("$root/_admin/website_config.php");
}

/*- Latex ----------------------------------------------------------------------------- */
include("$root/_scripts/functions/latex.php");
/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);



/*- Money ---------------------------------------------------------------------------- */
$host = $_SERVER['HTTP_HOST'];
if($host != "localhost" && $host != "127.1.1.0"){
	$money = "";
}
else{
	$money = "";
}



/*- Logged in? ----------------------------------------------------------------------- */
// This is used in the design (my user id)

				// Am I logged in?
				$t_users 	 		= $mysqlPrefixSav . "users";
				$t_users_profile		= $mysqlPrefixSav . "users_profile";
				$t_users_profile_photo 		= $mysqlPrefixSav . "users_profile_photo";
				if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
					// Get my user alias, date format, profile image
					$my_user_id = $_SESSION['user_id'];
					$my_user_id_mysql = quote_smart($link, $my_user_id);

					$query = "SELECT user_id, user_alias, user_date_format FROM $t_users WHERE user_id=$my_user_id_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_my_user_id, $get_my_user_alias, $get_my_user_date_format) = $row;

					if($get_my_user_id == ""){
						echo"<p style=\"color:red\">Session error</p>";
						unset($_SESSION['user_id']);
					}
					// Get my profile image
					$q = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id=$my_user_id_mysql AND photo_profile_image='1'";
					$r = mysqli_query($link, $q);
					$rowb = mysqli_fetch_row($r);
					list($get_my_photo_id, $get_my_photo_destination) = $rowb;
				}



/*- Select design -------------------------------------------------------------------- */
$get_webdesign_name = "";



// Find sub navigation
$nav = "";
if($root != "."){
	if(file_exists("navigation.php")){
		$nav = "navigation.php";
	}
	else{
		if(file_exists("../navigation.php")){
			$nav = "../navigation.php";
		}
		else{
			if(file_exists("../../navigation.php")){
				$nav = "../../navigation.php";
			}
		}
	}	
}



/*- Default, mobile, print ---------------------------------------------------------- */
if(isset($_GET['print']) ){
	include("$root/_webdesign/print_header.php");
}
else{

	if($process != "1"){
echo"<!DOCTYPE html>
<html lang=\"en-US\">
<head>
	<title>$configWebsiteTitleSav";
	if(isset($website_title)){
		echo" - $website_title";
	}
	else{
		$title = $_SERVER['PHP_SELF'];
		$title = str_replace("/", " - ", $title);
		$title = str_replace(".php", "", $title);
		$title = str_replace("_", " ", $title);
		$title = ucwords($title);
		echo" $title";
	}
	echo"</title>

	<!-- Site CSS-->
		<link rel=\"stylesheet\" type=\"text/css\" href=\"$root/_webdesign/$webdesignSav/en.css?filesize="; echo filesize("$root/_webdesign/$webdesignSav/en.css"); echo"\" />
	<!-- //Site CSS -->

	<!-- Special CSS -->
		";
		if(isset($pageCSSFile)){
			if(file_exists("$pageCSSFile")){
				echo"<link rel=\"stylesheet\" type=\"text/css\" href=\"$pageCSSFile?filesize="; echo filesize("$pageCSSFile"); echo"\" />";
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
	<meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0;\" />

	<!-- jQuery -->
		<script type=\"text/javascript\" src=\"$root/_admin/_javascripts/jquery/jquery-3.5.1.min.js\"></script>
	<!-- //jQuery -->

	<!-- Hide show nav -->
		<script>
		\$(document).ready(function(){
			\$(\".toggle\").click(function () {
				var idname= \$(this).data('divid');
				\$(\".\"+idname).toggle();
			});
			\$(\".main_navigation_menu_icon\").click(function () {
				\$(\"#main_left\").toggle();
				\$(\"#main_navigation\").hide();
			});
			\$(\".main_navigation_menu_more\").click(function () {
				\$(\"#main_navigation\").toggle();
				\$(\"#main_left\").hide();
			});
		});
		</script>
	<!-- //Hide show nav -->

</head>
<body>
<a id=\"top\"></a>

<!-- Header -->
	<header>
		<div id=\"header_inner\">
			<div id=\"header_left\">
				<a href=\"$root\"><span>&lt;&gt;</span> $configWebsiteTitleSav</a>
			</div>
			<div id=\"header_center\">
				<div id=\"main_navigation\">
					<ul>

						<li><a href=\"$root/courses/index.php?l=$l\" class=\"main_navigation_has_sub"; if($url_minus_two == "courses"){ echo"_active"; } echo"\">Courses</a> <img src=\"$root/_webdesign/en/images/header/main_navigation/main_navigation_has_sub_mobile_black.png\" alt=\"main_navigation_has_sub_mobile.png\" class=\"main_navigation_has_sub_mobile toggle\" data-divid=\"display_main_navigation_sub_courses\" />
							<ul class=\"main_navigation_sub display_main_navigation_sub_courses\">\n";
							$t_courses_categories_main	 = $mysqlPrefixSav . "courses_categories_main";
							$query = "SELECT main_category_id, main_category_title FROM $t_courses_categories_main WHERE main_category_language=$l_mysql ORDER BY main_category_title ASC";
							$result = mysqli_query($link, $query);
							while($row = mysqli_fetch_row($result)) {
								list($get_main_category_id, $get_main_category_title) = $row;
								echo"								";
								echo"<li><a href=\"$root/courses/open_main_category.php?main_category_id=$get_main_category_id&amp;l=$l\">$get_main_category_title</a></li>\n";
							}
							echo"
							</ul>
						</li>
						<li><a href=\"$root/references/index.php?l=$l\""; if($url_minus_two == "references"){ echo" class=\"main_navigation_active\""; } echo">References</a>
						<li><a href=\"$root/forum/index.php?l=$l\" class=\"main_navigation_has_sub"; if($url_minus_two == "forum"){ echo"_active"; } echo"\">Forum</a> <img src=\"$root/_webdesign/en/images/header/main_navigation/main_navigation_has_sub_mobile_black.png\" alt=\"main_navigation_has_sub_mobile.png\" class=\"main_navigation_has_sub_mobile toggle\" data-divid=\"display_main_navigation_sub_community\" />
							<ul class=\"main_navigation_sub display_main_navigation_sub_community\">
								<li><a href=\"$root/forum/index.php?l=$l\">All questions</a></li>
								<li><a href=\"$root/forum/index.php?show=recent&amp;l=$l\">Recent</a></li>
								<li><a href=\"$root/forum/index.php?show=popular&amp;l=$l\">Popular</a></li>
								<li><a href=\"$root/forum/index.php?show=unanswered&amp;l=$l\">Unanswered</a></li>
								<li><a href=\"$root/forum/index.php?show=active&amp;l=$l\">Active</a></li>
							</ul>
						</li>
	
						<li><a href=\"$root/downloads/index.php?l=$l\" class=\"main_navigation_has_sub"; if($url_minus_two == "downloads"){ echo"_active"; } echo"\">Downloads</a> <img src=\"$root/_webdesign/en/images/header/main_navigation/main_navigation_has_sub_mobile_black.png\" alt=\"main_navigation_has_sub_mobile.png\" class=\"main_navigation_has_sub_mobile toggle\" data-divid=\"diplay_main_navigation_sub_downloads\" />
							<ul class=\"main_navigation_sub diplay_main_navigation_sub_downloads\">
							<li><a href=\"$root/downloads/index.php?order_by=download_updated_datetime&amp;order_method=desc&amp;l=$l\">Last updated</a></li>
							<li><a href=\"$root/downloads/index.php?order_by=download_unique_hits&amp;order_method=desc&amp;l=$l\">Top downloads</a></li>
							<li><a href=\"$root/downloads/index.php?order_by=download_id&amp;order_method=desc&amp;l=$l\">New</a></li>
							</ul>
						</li>

						<li><a href=\"$root/blog/index.php?l=$l\""; if($url_minus_two == "blog"){ echo" class=\"main_navigation_active\""; } echo">Blog</a>
						
					</ul>
			
				</div> <!-- //main_navigation -->
			</div>
			<div id=\"header_right\">
			
				<div id=\"navigation_right\">
					<ul>
					";

					if(isset($_SESSION['user_id'])){
						echo"
						<li><a href=\"$root/users/view_profile.php?user_id=$get_my_user_id&amp;l=en\">$get_my_user_alias</a></li>
						<li><a href=\"$root/users/logout.php?process=1&amp;l=en\">Log out</a></li>
						";
					}
					else{
						echo"
						<li><a href=\"$root/users/login.php?l=en\">Login</a></li>
						<li><a href=\"$root/users/create_free_account.php?l=en\">Registrer</a></li>
						";
					}
					echo"
						<li><a href=\"$root/search/index.php?l=en\" class=\"sub_header_right_search\">Search</a></li>
					</ul>
				</div> <!-- //navigation_right -->


			<!-- Mobile navigation -->
				";
				echo"
				<div class=\"mobile_nav\">
					<ul>\n";
						if($nav != ""){
							echo"						";
							echo"<li><a href=\"#\"><img src=\"$root/_webdesign/en/images/header/main_navigation_menu_icon_white.png\" alt=\"main_navigation_menu_icon.png\" class=\"main_navigation_menu_icon\" /></a></li>\n";
						}
						echo"
						<li><a href=\"#\" class=\"main_navigation_menu_more\">More</a></li>
						<li><a href=\"$root/search/index.php?l=$l\" id=\"mobile_bar_left_search\"><img src=\"$root/_webdesign/en/images/header/main_navigation_search_white.png\" alt=\"Search\" /></a></li>
						<li><a href=\"#\" id=\"ic_more_vert_link_img\"><img src=\"$root/_webdesign/en/images/header/ic_more_vert_white.png\" alt=\"ic_more_vert.png\" /></a></li>
					</ul>

					<script>
					\$(document).ready(function(){
						\$(\"#ic_more_vert_link_img\").click(function () {
							\$(\"#ic_more_vert_div\").toggle();
						});
					});
					</script>

					<!-- Mobile user menu -->
						<div id=\"ic_more_vert_div\">
							<ul>
							";
							if(isset($my_user_id)){
								echo"
								<li><a href=\"$root/users/my_profile.php?l=$l\">$get_my_user_alias</a></li>
								<li class=\"last\"><a href=\"$root/users/logout.php?process=1&amp;l=$l\">$l_logout</a></li>
								";
							}
							else{
								echo"
								<li><a href=\"$root/users/login.php?l=$l\">$l_login</a></li>
								<li class=\"last\"><a href=\"$root/users/create_free_account.php?l=$l\">$l_registrer</a></li>
								";
							}
							echo"
							</ul>
						</div>
					<!-- //Mobile user -->
				</div>
			<!-- //Mobile navigation -->
			</div> <!-- //header_right -->
		</div> <!-- //header_inner -->
	</header>
<!-- //Header -->





<!-- Main -->
	<div id=\"main_wrapper\">

	";
	// Find sub navigation
	$nav = "";
	if($root != "."){
		if(file_exists("navigation.php")){
			$nav = "navigation.php";
		}
		else{
			if(file_exists("../navigation.php")){
				$nav = "../navigation.php";
			}
			else{
				if(file_exists("../../navigation.php")){
					$nav = "../../navigation.php";
				}
			}
		}
	}

	if($nav != ""){
		echo"
		<div id=\"main_left\">
		<!-- Menu -->
			<div id=\"div_toc\">";
				// Menu
				include("$nav");
				echo"
			</div> <!-- // div.div_toc -->
		<!-- //Menu -->
		</div> <!-- // div.main_left -->
		<div id=\"main_center\">
		";
	}
	else{
		echo"
		<div id=\"main_center_single_colum\">";
	}
	echo"
			<div id=\"main_center_inner\">
	";


	} // process != 1
} // not print
?>