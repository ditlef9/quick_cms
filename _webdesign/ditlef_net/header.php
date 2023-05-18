<?php
/**
*
* File: en/_webdesign/ditlef/header.php
* Version 13.05.2023
* Copyright (c) 2009-2023 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/


/*- Website config -------------------------------------------------------------------- */
if(!(isset($server_name))){
	include("$root/_admin/website_config.php");
}

/*- Latex ----------------------------------------------------------------------------- */
include("$root/_admin/_functions/latex.php");
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

	<!-- Hide show nav -->
		<script>
		</script>
	<!-- //Hide show nav -->

</head>
<body>
<a id=\"top\"></a>

<!-- Header -->

<header>
		<!-- Header left -->
			<div class=\"header_left\">
				<!-- Javascript toggle nav -->
				<script>
				function hoverNavImage(element) {
					var menuBox = document.getElementById('nav');
					if(menuBox.style.display == \"block\") {
						// Nav is showing
						// Toggle between X black and X blue
						// Image = Black X
						element.setAttribute('src', '$root/_webdesign/ditlef_net/images/header/close_rounded_nofill_black_24x24.svg');
					}
					else {
						// Nav is closed
						// Toggle between menu black and menu blue
						// Image = Menu
						var menuImgSrc = document.getElementById(\"menu_image\").src;
						menuImgSrc = /[^/]*$/.exec(menuImgSrc)[0];
						if(menuImgSrc == \"menu_rounded_nofill_black_24x24.svg\"){
							element.setAttribute('src', '$root/_webdesign/ditlef_net/images/header/menu_rounded_nofill_2f3ab2_24x24.svg');
						}
						else{
							element.setAttribute('src', '$root/_webdesign/ditlef_net/images/header/menu_rounded_nofill_black_24x24.svg');
						}
					}

				}
				function onClickNavImage(element) {
					var menuBox = document.getElementById('nav');    
					if(menuBox.style.display == \"block\") { // if is menuBox displayed, hide it
						menuBox.style.display = \"none\";
						element.setAttribute('src', '$root/_webdesign/ditlef_net/images/header/menu_rounded_nofill_black_24x24.svg');
					}
					else { // if is menuBox hidden, display it
						menuBox.style.display = \"block\";
						element.setAttribute('src', '$root/_webdesign/ditlef_net/images/header/close_rounded_nofill_black_24x24.svg');
					}
				}
				</script>
				<!-- //Javascript toggle menu -->
				<a href=\"#menu\" class=\"menu\"><img src=\"$root/_webdesign/ditlef_net/images/header/menu_rounded_nofill_black_24x24.svg\" alt=\"Menu\" id=\"menu_image\" onclick=\"onClickNavImage(this)\" onmouseover=\"hoverNavImage(this);\" onmouseout=\"hoverNavImage(this);\"> </a>
				<a href=\"$root\" class=\"logo\">Ditlef</a>
			</div>
		<!-- //Header left -->
		
		<!-- Header center -->
			<!-- Javascript change hover image -->
				<script>
				function hover_search_image(element) {
					element.setAttribute('src', '$root/_webdesign/ditlef_net/images/header/search_rounded_nofill_2f3ab2_20x20.svg');
				}
				  
				function unhover_search_image(element) {
					element.setAttribute('src', '$root/_webdesign/ditlef_net/images/header/search_rounded_nofill_black_20x20.svg');
				}
				</script>
			<!-- //Javascript change hover image -->

			<div class=\"header_center\">
				<div class=\"header_search_container\">
					<form action=\"/action_page.php\">
			  			<input type=\"text\" placeholder=\"Search..\" name=\"search\">
			  			<button type=\"submit\"><img src=\"$root/_webdesign/ditlef_net/images/header/search_rounded_nofill_black_20x20.svg\" alt=\"Search\" onmouseover=\"hover_search_image(this);\" onmouseout=\"unhover_search_image(this);\"></button>
					</form>
		  		</div>
			</div>
		<!-- //Header center -->

		
		<!-- Header right -->
			<div class=\"header_right\">
			";

			if(isset($_SESSION['user_id'])){
				echo"
				<a href=\"$root/users/view_profile.php?user_id=$get_my_user_id&amp;l=en\" class=\"nofill\">$get_my_user_alias</a>
				<a href=\"$root/users/logout.php?process=1&amp;l=en\" class=\"nofill\">Log out</a>
				";
			}
			else{
				echo"
				<a href=\"$root/users/login.php?l=en\" class=\"nofill\">Login</a>
				<a href=\"$root/users/create_free_account.php?l=en\"  class=\"fill\">Registrer</a>
				";
			}
			echo"
			</div>
		<!-- //Header right -->
</header>

<nav id=\"nav\">
	<ul>
		<!-- All Courses -->\n";
			$t_courses_categories_main	 = $mysqlPrefixSav . "courses_categories_main";
			$query = "SELECT main_category_id, main_category_title FROM $t_courses_categories_main ORDER BY main_category_title ASC";
			echo"$query";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_main_category_id, $get_main_category_title) = $row;
				echo"								";
				echo"<li><a href=\"$root/courses/open_main_category.php?main_category_id=$get_main_category_id&amp;l=$l\">$get_main_category_title</a></li>\n";
			}
		echo"
	<!-- All Courses -->
	</ul>
</nav>


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