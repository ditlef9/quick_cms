<?php
error_reporting(E_ALL);
session_start();
ini_set('arg_separator.output', '&amp;');
/**
*
* File: _admin/index.php
* Version 3.0.0
* Date 14.05.2023
* Copyright (c) 2008-2023 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Functions ------------------------------------------------------------------------ */
include("_functions/output_html.php");
include("_functions/clean.php");
include("_functions/resize_crop_image.php");


/*- Make sure we are on the correct web site ----------------------------------------- */
if(file_exists("_data/config/meta.php")){
	include("_data/config/meta.php");

	// Page URL
	$page_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$page_url = htmlspecialchars($page_url, ENT_QUOTES, 'UTF-8');

	$page_url_substr = substr($page_url, 0, strlen($configControlPanelURLSav));

	if($configControlPanelURLSav != "$page_url_substr"){
		// Check for localhost
		$check_localhost = substr($page_url, 0, 16);
		if($check_localhost != "http://localhost"){
	
			echo"<p>Security error. Page url is not the same as configured. Please fix meta.php.
			</p>

			<p>
			<a href=\"$configControlPanelURLSav\">$configControlPanelURLSav</a> != $page_url_substr
			</p>
			";
			die;
		}
	}
}

/*- Check for admin ----------------------------------------------------------------- */

if(!(isset($_SESSION['admin_user_id']))){
	header("Location: login/index.php");
	// echo"<meta http-equiv=refresh content=\"1; url=login/index.php\">";
	die;
}
else{
	$current_user_id = $_SESSION['admin_user_id'];
}
/*- Check if setup is run ------------------------------------------------------------ */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);
$setup_finished_file = "setup_finished_" . $server_name . ".php";
if(!(file_exists("_data/$setup_finished_file"))){
	header("Location: setup/");
	exit;
}



/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['open'])) {
	$open = $_GET['open'];
	$open = strip_tags(stripslashes($open));
}
else{
	$open = "dashboard";
}
if(isset($_GET['page'])) {
	$page = $_GET['page'];
	$page = strip_tags(stripslashes($page));
}
else{
	$page = "";
}
if(isset($_GET['subpage'])) {
	$subpage = $_GET['subpage'];
	$subpage = strip_tags(stripslashes($subpage));
}
else{
	$subpage = "";
}
if(isset($_GET['process'])) {
	$process = $_GET['process'];
	$process = strip_tags(stripslashes($process));
}
else{
	$process = "";
}
if(isset($_GET['ft'])) {
	$ft = $_GET['ft'];
	$ft = strip_tags(stripslashes($ft));
	if($ft != "error" && $ft != "warning" && $ft != "success" && $ft != "info"){
		echo"Server error 403 feedback error";die;
	}
}
else{
	$ft = "";
}
if(isset($_GET['fm'])) {
	$fm = $_GET['fm'];
	$fm = strip_tags(stripslashes($fm));
}
if(isset($_GET['action'])) {
	$action = $_GET['action'];
	$action = strip_tags(stripslashes($action));
}
else{
	$action = "";
}


/*- MySQL ----------------------------------------------------------------------------- */
$mysql_config_file = "_data/mysql_" . $server_name . ".php";
if(file_exists($mysql_config_file)){
	include("$mysql_config_file");
	$mysqli = new mysqli($mysqlHostSav, $mysqlUserNameSav, $mysqlPasswordSav, $mysqlDatabaseNameSav);

	if ($mysqli -> connect_errno) {
		$error = $mysqli -> connect_error;
		echo "
		<div class=\"error\"><p><b>MySQL connection error</b>: $error</p>
		</div>
		";
	}


	/*- MySQL Tables -------------------------------------------------- */
	$t_users 	 				= $mysqlPrefixSav . "users";
	$t_users_profile 			= $mysqlPrefixSav . "users_profile";
	$t_users_friends 			= $mysqlPrefixSav . "users_friends";
	$t_users_friends_requests 	= $mysqlPrefixSav . "users_friends_requests";
	$t_users_profile			= $mysqlPrefixSav . "users_profile";
	$t_users_profile_photo 		= $mysqlPrefixSav . "users_profile_photo";
	$t_users_status 			= $mysqlPrefixSav . "users_status";
	$t_users_status_subscriptions	= $mysqlPrefixSav . "users_status_subscriptions";
	$t_users_status_replies 	= $mysqlPrefixSav . "users_status_replies";
	$t_users_status_replies_likes 	= $mysqlPrefixSav . "users_status_replies_likes";
	$t_users_status_likes 		= $mysqlPrefixSav . "users_status_likes";
	$t_users_profile 			= $mysqlPrefixSav . "users_profile";
	$t_users_cover_photos 		= $mysqlPrefixSav . "users_cover_photos";
	$t_users_email_subscriptions 	= $mysqlPrefixSav . "users_email_subscriptions";
	$t_users_notifications 		= $mysqlPrefixSav . "users_notifications";
	$t_users_moderator_of_the_week	= $mysqlPrefixSav . "users_moderator_of_the_week";

	$t_users_antispam_questions	= $mysqlPrefixSav . "users_antispam_questions";
	$t_users_antispam_answers	= $mysqlPrefixSav . "users_antispam_answers";
	
	$t_pages 					= $mysqlPrefixSav . "pages";
	$t_pages_comments			= $mysqlPrefixSav . "pages_comments";
	$t_pages_navigation 		= $mysqlPrefixSav . "pages_navigation";

	$t_comments				= $mysqlPrefixSav . "comments";
	$t_comments_users_block	= $mysqlPrefixSav . "comments_users_block";

	$t_images				= $mysqlPrefixSav . "images";
	$t_images_paths			= $mysqlPrefixSav . "images_paths";

	$t_languages 				= $mysqlPrefixSav . "languages";
	$t_languages_active 		= $mysqlPrefixSav . "languages_active";
	$t_languages_countries		= $mysqlPrefixSav . "languages_countries";
	
	$t_site_translations_directories = $mysqlPrefixSav . "site_translations_directories";
	$t_site_translations_files       = $mysqlPrefixSav . "site_translations_files";
	$t_site_translations_strings	 = $mysqlPrefixSav . "site_translations_strings";

	$t_admin_translations_directories = $mysqlPrefixSav . "admin_translations_directories";
	$t_admin_translations_files       = $mysqlPrefixSav . "admin_translations_files";
	$t_admin_translations_strings     = $mysqlPrefixSav . "admin_translations_strings";

	$t_admin_navigation			= $mysqlPrefixSav . "admin_navigation";
	$t_admin_messages_inbox     = $mysqlPrefixSav . "admin_messages_inbox";

	$t_social_media 	= $mysqlPrefixSav . "social_media";

	$t_analytics 		= $mysqlPrefixSav . "analytics";


	/*- Tables blog ---------------------------------------------------------------------------- */
	$t_blog_info 		= $mysqlPrefixSav . "blog_info";
	$t_blog_categories	= $mysqlPrefixSav . "blog_categories";
	$t_blog_posts 		= $mysqlPrefixSav . "blog_posts";
	$t_blog_posts_tags 	= $mysqlPrefixSav . "blog_posts_tags";


	/*- Tables ---------------------------------------------------------------------------- */
	$t_social_media 	= $mysqlPrefixSav . "social_media";
}

/*- Log out -------------------------------------------------------------------------- */
if(isset($_GET['log_out'])) {
	// Unset all of the session variables.
	$_SESSION = array();
	
	// Finally, destroy the session.
	session_destroy();

	header("Location: index.php?good_bye");
	exit;
}


/*- Editor language ------------------------------------------------------------------------------- */
if(isset($_GET['editor_language'])) {
	$editor_language = $_GET['editor_language'];
	$editor_language = strip_tags(stripslashes($editor_language));
}
else{
	// Find active language
	$query = "SELECT language_active_id, language_active_iso_two FROM $t_languages_active WHERE language_active_default='1'";
	$result = $mysqli->query($query);
	$row = $result->fetch_row();
	list($get_language_active_id, $get_language_active_iso_two) = $row;



	if($get_language_active_id == ""){
		echo"<div class=\"error\"><p>Active language not predefined! ($query)</p></div>";
		$editor_language = "en";
	}
	else{
		$editor_language = "$get_language_active_iso_two";
	}
}
$tabindex = 0;


/*- Include config -------------------------------------------------------------------- */
include("global_variables.php");
if(file_exists("_data/config/meta.php")){
	include("_data/config/meta.php");
	include("_data/config/user_system.php");
}
else{
	header("Location: login.php");
}

/*- Select language ------------------------------------------------------------------ */
if(isset($_GET['l'])) {
	$l = $_GET['l'];

	if(file_exists("_translations/admin/$l/login/t_login.php")){
		$_SESSION['l'] = $l;
	}
	else{
		echo"
		<div class=\"warning\"><p>Missing <a href=\"_translations/admin/$l/login/t_login.php\">_translations/admin/$l/login/login.php</a></div>
		";
		$_SESSION['l'] = "en";
	}
}
if(isset($_SESSION['l'])){
	$l = $_SESSION['l'];
}
else{
	if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
		$accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		$accept_language = output_html($accept_language);
		$accept_language = strtolower($accept_language);
		$accept_language_prefered = substr("$accept_language", 0,2);

		if(file_exists("_translations/admin/$accept_language_prefered/cp/cp.php")){
			$l = "$accept_language_prefered";
		}
		else{
			$l = "en";
		}
	}
	else{
		$l = "en";
	}
}
/*- Translation ----------------------------------------------------------------------- */
if(!(file_exists("_translations/admin/$l/common/t_common.php"))){
	// Language that doesnt exists
	echo"<div class=\"error\">Unknow language $l</div>";
	echo"<meta http-equiv=refresh content=\"1; url=index.php?l=en\">";
	die;
}

// Common for all control panel
include("_translations/admin/$l/common/t_common.php");

// Open specific
if($open != ""){
	// Example: _translations/admin/en/settings/t_common.php
	if(!(file_exists("_translations/admin/$l/$open/t_common.php"))){
		if(!(is_dir("_translations/admin/$l/$open"))){
			echo"<p>Making dir _translations/admin/$l/$open</p>";
			mkdir("_translations/admin/$l/$open");
		}
		$fh = fopen("_translations/admin/$l/$open/t_common.php", "w+") or die("can not open file");
		fwrite($fh, "<?php ?>");
		fclose($fh);
	}
	include("_translations/admin/$l/$open/t_common.php");
}

// Open default
if($open != "" && $page == ""){
	// Example: ?open=users&editor_language=en
	//          _translations/admin/en/users/t_default.php
	if(!(file_exists("_translations/admin/$l/$open/t_default.php"))){
		if(!(is_dir("_translations/admin/$l/default"))){
			mkdir("_translations/admin/$l/default");
		}

		$fh = fopen("_translations/admin/$l/$open/t_default.php", "w+") or die("can not open file");
		fwrite($fh, "<?php ?>");
		fclose($fh);
	}
	include("_translations/admin/$l/$open/t_default.php");
}
// Open page
elseif($open != "" && $page != ""){
	// Example: ?open=users&page=user_system&editor_language=en
	//          _translations/admin/en/settings/t_common.php

	if(!(file_exists("_translations/admin/$l/$open/t_$page.php"))){
		$fh = fopen("_translations/admin/$l/$open/t_$page.php", "w+") or die("can not open file");
		fwrite($fh, "<?php ?>");
		fclose($fh);
	}
	include("_translations/admin/$l/$open/t_$page.php");
}




/*- Include user ---------------------------------------------------------------------- */
$my_user_id = $_SESSION['admin_user_id'];
$my_user_id = output_html($my_user_id);

$my_security = $_SESSION['admin_security'];
$my_security = output_html($my_security);

$stmt = $mysqli->prepare("SELECT user_id, user_email, user_name, user_language, user_last_online, user_rank, user_login_tries FROM $t_users WHERE user_id=? AND user_security=?"); 
$stmt->bind_param("ss", $my_user_id, $my_security);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_language, $get_my_user_last_online, $get_my_user_rank, $get_my_user_login_tries) = $row;
if($get_my_user_id == ""){
	$url = "login/index.php?ft=info&fm=please_login_to_the_control_panel";
	header("Location: $url");
	exit;
}
else{
	if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
		// Access OK!
		$define_access_to_control_panel = 1;
	}
	else{
		echo"<h1>Server error 403</h1><p>Access denied!</p><p>Only administrator and moderator can access the control panel.</p>";die;
	}
}

// Get my photo
$query = "SELECT photo_id, photo_destination, photo_thumb_40, photo_thumb_50 FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
$result = $mysqli->query($query);
$row = $result->fetch_row();
list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_40, $get_my_photo_thumb_50) = $row;

/*- Design ---------------------------------------------------------------------------- */
if($process != "1"){
echo"<!DOCTYPE html>
<html lang=\"$editor_language\">
<head>
	<title>";
	if($page != ""){
		$page_saying = ucfirst($page);
		$page_saying = str_replace("_", " ", $page_saying);
		echo"$page_saying - ";
	} 
	if($open != ""){
		$open_saying = ucfirst($open);
		$open_saying = str_replace("_", " ", $open_saying);
		echo"$open_saying - ";
	} 
	echo"$cmsNameSav</title>

	<!-- Favicon -->
		<link rel=\"icon\" href=\"_design/favicon/cms_16x16.png\" type=\"image/png\" sizes=\"16x16\" />
		<link rel=\"icon\" href=\"_design/favicon/cms_32x32.png\" type=\"image/png\" sizes=\"32x32\" />
		<link rel=\"icon\" href=\"_design/favicon/cms_256x256.png\" type=\"image/png\" sizes=\"256x256\" />
	<!-- //Favicon -->


	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
	<meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0;\"/>


	<!-- CSS -->";
		echo"
		<link rel=\"stylesheet\" href=\"_design/admin.css?filesize="; echo filesize("_design/admin.css"); echo"\" type=\"text/css\" />
	<!-- //CSS -->

	<!-- Special CSS -->
		";
		if($page != ""){
			$special_css = "_inc/$open/_stylesheets/$page.css";
		}
		else{
			$special_css = "_inc/$open/_stylesheets/default.css";
		}
		if(file_exists("$special_css")){
			echo"<link rel=\"stylesheet\" type=\"text/css\" href=\"$special_css?filesize="; echo filesize("$special_css"); echo"\" />";
		}
		else{
			echo"<!-- $special_css doesnt exists -->";
		}
		echo"
	<!-- //Special CSS -->

	<!-- jQuery -->
		<script type=\"text/javascript\" src=\"_javascripts/jquery/jquery.min.js\"></script>
	<!-- //jQuery -->


</head>
<body>


<!-- Header -->
	<header>

		<!-- Logo -->
			<div id=\"logo\">
				<a href=\"index.php?editor_language=$editor_language&amp;l=$l\">$configWebsiteTitleSav</a>
			</div>
		<!-- //Logo -->

		<!-- Mobile icons -->
			<div id=\"header_user_icons\">

				<!-- Hide show search, Header hamburger -->
					<script>
					\$(document).ready(function(){
						\$(\".main_navigation_menu_icon\").click(function () {
							\$(\"nav\").fadeToggle();

							var src = (\$(this).attr('src') === './_design/gfx/icons/24x24/menu_outline_white_24x24.png')
										? './_design/gfx/icons/24x24/menu_outline_white_24x24.png'
										: './_design/gfx/icons/24x24/menu_outline_white_24x24.png';
							\$(\".main_navigation_menu_icon\").attr('src', src);
						});
						\$(\".open_admin_user_menu\").click(function () {
							\$(\".admin_user_menu\").toggle();
						});
					});
					</script>
				<!-- //Hide show nav + change hamburger icon -->
				<ul>
					<li><a href=\"#\" class=\"a_header_left_hamburger\"><img src=\"_design/gfx/icons/24x24/menu_outline_white_24x24.png\" alt=\"menu_outline_white_24x24.png\" class=\"main_navigation_menu_icon\" /></a></li>
					<li><a href=\"#\" class=\"open_admin_user_menu\"><img src=\"_design/gfx/icons/24x24/person_round_white_24x24.png\" alt=\"person_round_white_24x24.png\" /></a></li>
				</ul>
			</div> <!-- //Header user icons -->
		<!-- //Mobile icons -->

		<!-- Header navigation -->
			<nav>

				<ul>";


				$x = 0;
				$query = "SELECT navigation_id, navigation_url, navigation_title, navigation_icon_white_18 FROM $t_admin_navigation WHERE navigation_user_id=? ORDER BY navigation_weight ASC";
				$stmt = $mysqli->prepare($query); 
				$stmt->bind_param("s", $my_user_id);
				$stmt->execute();
				$result = $stmt->get_result();
				while($row = $result->fetch_row()) {
					list($get_navigation_id, $get_navigation_url, $get_navigation_title, $get_navigation_icon_white_18) = $row;
		
					echo"
					<li";if($open == "$get_navigation_url"){echo" class=\"main_navigation_has_sub_li_active\"";}echo">
						<a href=\"index.php?open=$get_navigation_url&amp;editor_language=$editor_language&amp;l=$l\"";if($open == "$get_navigation_url"){echo" class=\"main_navigation_has_sub_a_active\"";}echo"><img src=\"_inc/$get_navigation_url/_gfx/icons/$get_navigation_icon_white_18\" alt=\"$get_navigation_icon_white_18\" /> $get_navigation_title</a> <img src=\"_design/gfx/main_navigation/main_navigation_has_sub_grey.png\" alt=\"main_navigation_has_sub.png\" class=\"main_navigation_has_sub toggle\" data-divid=\"display_main_navigation_sub_dashboard\" />
						<ul class=\"main_navigation_sub display_main_navigation_sub_dashboard\"";if($open == "$get_navigation_url"){echo" style=\"display:block;\"";}echo">\n";
							include("_inc/$get_navigation_url/menu.php");
							echo"
						</ul>
					</li>\n";

					$x++;
				}
				if($x == 0){
					// No items
					echo"<meta http-equiv=refresh content=\"0; url=index.php?open=admin_cms&amp;action=my_navigation_auto_setup&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\">";
				}

				echo"
					<li><a href=\"index.php?open=admin_cms&amp;editor_language=$editor_language&amp;l=$l\"";if($open == "admin_cms" && $action == ""){echo" class=\"sidebar_left_below_navigation_active\"";}echo">All items</a> <img src=\"_design/gfx/main_navigation/main_navigation_no_sub_grey.png\" alt=\"main_navigation_no_sub_grey.png\" class=\"main_navigation_has_no_sub\" /></li>
				</ul>
			</nav>

		<!-- //Header navigation -->

	</header>
<!-- //Header -->

<!-- Admin user menu -->
	<div class=\"admin_user_menu\">
		<ul>
			<li class=\"header_admin_menu_profile\"><a href=\"./index.php?open=users&amp;page=users_edit_user&amp;user_id=$my_user_id&amp;editor_language=$editor_language&amp;l=$l\">Profile</a></li>
			<li class=\"header_admin_menu_profile\"><a href=\"./index.php?open=users&amp;page=users_edit_user&amp;user_id=$my_user_id&amp;editor_language=$editor_language&amp;l=$l\">Password</a></li>
			<li class=\"header_admin_menu_profile\"><a href=\"./index.php?log_out=1&amp;editor_language=$editor_language&amp;l=$l\">Log out</a></li>
		</ul>
	</div>
	
<!-- //Admin user menu -->

<!-- Top line -->
	<div class=\"top_line_wrapper\">
		<!-- Top line left -->
			<div class=\"top_line_left\">
				<ul>
					<li><a href=\"index.php?editor_language=$editor_language&amp;l=$l\"><img src=\"_inc/dashboard/_gfx/icons/dashboard_black_24x24.png\" alt=\"dashboard_black_24x24.png\" /></a></li>
					<li><a href=\"../index.php?l=$l\"><img src=\"_design/gfx/icons/24x24/home_black_24x24.png\" alt=\"home_black_24x24.png\" /></a></li>
					<li><a href=\"index.php?open=dashboard&amp;page=notes&amp;action=edit&amp;editor_language=$editor_language&amp;l=$l\"><img src=\"_design/gfx/icons/24x24/speaker_notes_black_24x24.png\" alt=\"speaker_notes_black_24x24.png\" /></a></li>
					<li><a href=\"index.php?open=dashboard&amp;page=tasks&amp;editor_language=$editor_language&amp;l=$l\"><img src=\"_design/gfx/icons/24x24/assignment_black_24x24.png\" alt=\"assignment_black_24x24.png\" /></a></li>
				</ul>
			</div> <!-- //Top Line left -->
		<!-- //Top line left -->


		<!-- Top line right -->
			<div class=\"top_line_right\">
			<!-- Admin -->
				";
				if(file_exists("../_uploads/users/images/$get_my_user_id/$get_my_photo_destination")){
					if(!(file_exists("../_uploads/users/images/$get_my_user_id/$get_my_photo_thumb_40"))){
						// Create thumb
						resize_crop_image(40, 40, "../_uploads/users/images/$get_my_user_id/$get_my_photo_destination", "../_uploads/users/images/$get_my_user_id/$get_my_photo_thumb_40");
					}

					echo"<a href=\"#\" class=\"open_admin_user_menu\"><img src=\"../_uploads/users/images/$get_my_user_id/$get_my_photo_thumb_40\" alt=\"$get_my_photo_thumb_40\" class=\"admin_image\" /></a>";
				}
				echo"
				<p class=\"admin_username\">
					<a href=\"#\" class=\"open_admin_user_menu\">$get_my_user_name</a>
					<img src=\"_design/gfx/top_line_right_admin_menu_profile.png\" alt=\"header_admin_menu_profile.png\" class=\"open_admin_user_menu\" />
				</p>
			<!-- //Admin -->


			</div> <!-- //Top Line right -->
		<!-- //Top line right -->
	</div> <!-- //Top Line -->
<!-- Top line -->

<!-- Content -->
	<main>
		<div id=\"main_inner\">

			<!-- Includes -->
			";
} // process != 1
				if($open == "" && $page == ""){
					include("_inc/dashboard/default.php");
				}
				else{
					if($open != "" && $page == ""){
						if (preg_match('/(http:\/\/|^\/|\.+?\/)/', $open)){
							echo"
							<h1>Advarsel</h1>
							<p>Adressen du oppga er ikke gyldig.</p>
							";
						}
						else{
							if(file_exists("_inc/$open/default.php")){
								include("_inc/$open/default.php");
							}
							else{
								echo"
								<h1>Server error 404</h1>
								<p>Flatfilen _inc/$open/default.php finnes ikke p� serveren.</p>
								";
							}
						}
					} // end if
					elseif($open != "" && $page != ""){

						if (preg_match('/(http:\/\/|^\/|\.+?\/)/', $open)){
							echo"
							<h1>Advarsel</h1>
							<p>Adressen du oppga er ikke gyldig.</p>
							";
						}
						else{
							if (preg_match('/(http:\/\/|^\/|\.+?\/)/', $page)){
								echo"
								<h1>Advarsel</h1>
								<p>Adressen du oppga er ikke gyldig.</p>
								";
							}
							else{
								if(file_exists("_inc/$open/$page.php")){
									include("_inc/$open/$page.php");
								}
								else{
									echo"
									<h1>Server error 404</h1>
									<p>Flatfilen _inc/$open/$page.php finnes ikke p� serveren.</p>
									";
								}
							
							}
						}
					} // end elseif
					elseif($open == "" && $page != ""){
						echo"
						<h1>Advarsel</h1>
						<p>Mangler variabel open.</p>
						";
					} // end elseif


				} // end else
			echo"
			<!-- //Includes -->

		</div>


	</main>
<!-- //Content -->


<!-- Footer line -->
<footer>
	<p>
	<a href=\"https://ditlef.net\">&copy; 2008-2023 S. Ditlefsen</a>
	|
	<a href=\"https://github.com/ditlef9/quick_cms\">Quick CMS $cmsVersionSav</a>
	</p>
</footer>
<!-- //Footer line -->

</body>
</html>";
?>