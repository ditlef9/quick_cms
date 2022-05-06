<?php
/**
*
* File: users/me.php
* Version 09:20 08.08.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "0";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

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

/*- Tables ---------------------------------------------------------------------------------- */
include("_tables_users.php");

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_my_profile - $l_users";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");



/*- Content --------------------------------------------------------------------------- */

if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	// Get user
	$user_id = $_SESSION['user_id'];
	$user_id_mysql = quote_smart($link, $user_id);
	$security = $_SESSION['security'];
	$security_mysql = quote_smart($link, $security);

	$query = "SELECT user_id, user_name, user_alias, user_language, user_rank FROM $t_users WHERE user_id=$user_id_mysql AND user_security=$security_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_name,  $get_user_alias, $get_user_language, $get_user_rank) = $row;

	if($get_user_id != ""){
		echo"
		<h1>$l_my_profile</h1>

		<!-- Where am I ? -->
			<p><b>$l_you_are_here:</b><br />
			<a href=\"index.php?l=$l\">$l_users</a>
			&gt;
			<a href=\"my_profile.php?l=$l\">$l_my_profile</a>
			</p>
		<!-- //Where am I ? -->

		<h2>$l_you</h2>
		<div class=\"vertical\">
			<ul>
				<li><a href=\"view_profile.php?user_id=$user_id&amp;l=$l\""; if($page == "view_profile"){ echo" class=\"navigation_active\"";}echo"><img src=\"_gfx/ic_person_black_18dp_1x.png\" alt=\"ic_person_black_18dp_1x.png\" /> $get_user_alias</a></li>
				<li><a href=\"my_profile_edit_user.php?l=$l\""; if($page  == "edit_user"){ echo" class=\"navigation_active\"";}echo"><img src=\"_gfx/ic_mode_edit_black_18dp_1x.png\" alt=\"iic_mode_edit_black_18dp_1x.png\" /> $l_edit_user</a></li>
				<li><a href=\"my_profile_photos.php?l=$l\""; if($page  == "photo"){ echo" class=\"navigation_active\"";}echo"><img src=\"_gfx/ic_portrait_black_18dp_1x.png\" alt=\"ic_portrait_black_18dp_1x.png\" /> $l_photo</a></li>
				<li><a href=\"my_profile_cover_photo.php?l=$l\""; if($page  == "cover_photo"){ echo" class=\"navigation_active\"";}echo"><img src=\"_gfx/ic_insert_photo_black_18dp_1x.png\" alt=\"ic_insert_photo_black_18dp_1x.png\" /> $l_cover_photo</a></li>\n";

				// Headlines
				$query = "SELECT headline_id, headline_title, headline_icon_path_18x18, headline_icon_file_18x18 FROM $t_users_profile_headlines WHERE headline_user_can_view_headline=1 ORDER BY headline_weight DESC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_headline_id, $get_headline_title, $get_headline_icon_path_18x18, $get_headline_icon_file_18x18) = $row;

					// Get translation
					$query_t = "SELECT translation_id, translation_headline_id, translation_language, translation_value FROM $t_users_profile_headlines_translations WHERE translation_headline_id=$get_headline_id AND translation_language=$l_mysql";
					$result_t = mysqli_query($link, $query_t);
					$row_t = mysqli_fetch_row($result_t);
					list($get_translation_id, $get_translation_headline_id, $get_translation_language, $get_translation_value) = $row_t;

					if($get_translation_id == ""){
						$inp_title_mysql = quote_smart($link, $get_headline_title);
						mysqli_query($link, "INSERT INTO $t_users_profile_headlines_translations
						(translation_id, translation_headline_id, translation_language, translation_value) 
						VALUES 
						(NULL, $get_headline_id, $l_mysql, $inp_title_mysql)")
						or die(mysqli_error($link));
						$get_translation_value = "$get_current_headline_title";
					}


					echo"<li><a href=\"my_profile_edit_headline.php?headline_id=$get_headline_id&amp;l=$l\"><img src=\"$root/$get_headline_icon_path_18x18/$get_headline_icon_file_18x18\" alt=\"$get_headline_icon_file_18x18\" /> $get_translation_value</a></li>\n";
				}
				echo"
			</ul>
		</div>

		<h2>$l_general_account_settings</h2>
		<div class=\"vertical\">
			<ul>
				<li><a href=\"my_profile_known_devices.php?l=$l\""; if($page == "known_devices"){ echo" class=\"navigation_active\"";}echo"><img src=\"_gfx/ic_verified_user_black_18dp.png\" alt=\"ic_verified_user_black_18dp.png\" /> $l_known_devices</a></li>
				<li><a href=\"my_profile_edit_subscriptions.php?l=$l\""; if($page == "edit_subscriptions"){ echo" class=\"navigation_active\"";}echo"><img src=\"_gfx/ic_notifications_black_18dp_1x.png\" alt=\"ic_notifications_black_18dp_1x.png\" /> $l_subscriptions</a></li>
				<li><a href=\"my_profile_settings.php?l=$l\""; if($page == "settings"){ echo" class=\"navigation_active\"";}echo"><img src=\"_gfx/ic_settings_black_18dp_1x.png\" alt=\"ic_settings_black_18dp_1x.png\" /> $l_settings</a></li>
				<li><a href=\"my_profile_edit_password.php?l=$l\""; if($page == "edit_password"){ echo" class=\"navigation_active\"";}echo"><img src=\"_gfx/ic_lock_outline_black_18dp_1x.png\" alt=\"ic_lock_outline_black_18dp_1x.png\" /> $l_password</a></li>
				<li><a href=\"logout.php?process=1&amp;l=$l\""; if($page  == "logout"){ echo" class=\"navigation_active\"";}echo"><img src=\"_gfx/ic_exit_to_app_black_18dp_1x.png\" alt=\"ic_exit_to_app_black_18dp_1x.png\" /> $l_logout</a></li>
				<li><a href=\"my_profile_delete_account.php?l=$l\""; if($page  == "delete_account"){ echo" class=\"navigation_active\"";}echo"><img src=\"_gfx/ic_delete_black_18dp_1x.png\" alt=\"ic_delete_black_18dp_1x.png\" /> $l_delete_account</a></li>
			
			</ul>	
		</div>";
			
	}
	else{
		// Logout
		if (isset($_SERVER['HTTP_COOKIE'])){
    			$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    			foreach($cookies as $cookie) {
        			$parts = explode('=', $cookie);
        			$name = trim($parts[0]);
        			setcookie($name, '', time()-1000);
        			setcookie($name, '', time()-1000, '/');
    			}
		}

		$_SESSION = array();
		session_destroy();


		$host = $_SERVER['HTTP_HOST'];
		unset($_COOKIE['remember_user']);
		setcookie ('remember_user', 'unset', strtotime( '+10 months' ), '/', $host);


		echo"
		<table>
		 <tr> 
		  <td style=\"padding-right: 6px;vertical-align: top;\">
			<span>
			<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"margin:0;padding: 23px 0px 0px 0px;\" />
			</span>
		  </td>
		  <td>
			<h1 style=\"border:0;margin:0;padding: 20px 0px 0px 0px;\">Loading</h1>
		  </td>
		 </tr>
		</table>
		<meta http-equiv=\"refresh\" content=\"1;url=login.php?referer=$root/users/my_profile.php&amp;l=$l\">
		";
	}
}
else{
	echo"
	<ul class=\"vertical\">
		<li class=\"header_home\"><a href=\"index.php?l=$l&amp;l=$l\""; if($page  == "users"){ echo" class=\"navigation_active\"";}echo">Users</a></li>

		<li><a href=\"create_free_account.php?l=$l\""; if($page == "create_free_account"){ echo" class=\"navigation_active\"";}echo">$l_create_free_account</a></li>
		<li><a href=\"login.php?l=$l\""; if($page == "login"){ echo" class=\"navigation_active\"";}echo">$l_login</a></li>
	</ul>";
}
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>