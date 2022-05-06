<?php
/*- Tables ---------------------------------------------------------------------------------- */
include("_tables_users.php");

/*- Translations ---------------------------------------------------------------------------- */
include("$root/_admin/_translations/site/$l/users/ts_my_profile.php");

if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	// Get user
	$user_id = $_SESSION['user_id'];
	$user_id_mysql = quote_smart($link, $user_id);
	$security = $_SESSION['security'];
	$security_mysql = quote_smart($link, $security);

	$query = "SELECT user_id, user_name, user_language, user_rank FROM $t_users WHERE user_id=$user_id_mysql AND user_security=$security_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_name, $get_my_user_language, $get_my_user_rank) = $row;

	

	if($get_my_user_id != ""){
		echo"	
		<ul>
			<li class=\"header_home\"><a href=\"index.php?l=$l\""; if($url_minus_one  == "users"){ echo" class=\"navigation_active\"";}echo">$l_users</a></li>
			";
			echo"
			<li><a href=\"view_profile.php?user_id=$get_my_user_id&amp;l=$l\""; if($url_minus_one == "view_profile.php" && $get_my_user_id == "$user_id"){ echo" class=\"navigation_active\"";}echo">$get_my_user_name</a></li>
			<li><a href=\"my_profile.php?l=$l\""; if($url_minus_one  == "my_profile.php"){ echo" class=\"navigation_active\"";}echo">$l_my_profile</a></li>
			<li><a href=\"my_profile_photos.php?l=$l\""; if($url_minus_one  == "my_profile_photos.php"){ echo" class=\"navigation_active\"";}echo">$l_photo</a></li>
			<li><a href=\"my_profile_cover_photo.php?l=$l\""; if($url_minus_one  == "my_profile_cover_photo.php"){ echo" class=\"navigation_active\"";}echo">$l_cover_photo</a></li>";

			
			// Headlines
			if (isset($_GET['headline_id'])) {
				$headline_id = $_GET['headline_id'];
				$headline_id = stripslashes(strip_tags($headline_id));
				if(!(is_numeric($headline_id))){
					echo"Headline id not numeric";
					die;
				}
			}
			else{
				$headline_id = -1;
			}
			$query = "SELECT headline_id, headline_title, headline_icon_path_18x18, headline_icon_file_18x18 FROM $t_users_profile_headlines WHERE headline_user_can_view_headline=1 ORDER BY headline_weight DESC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_headline_id, $get_headline_title, $get_headline_icon_path_18x18, $get_headline_icon_file_18x18) = $row;

				// Get translation
				$query_t = "SELECT translation_id, translation_headline_id, translation_language, translation_value FROM $t_users_profile_headlines_translations WHERE translation_headline_id=$get_headline_id AND translation_language=$l_mysql";
				$result_t = mysqli_query($link, $query_t);
				$row_t = mysqli_fetch_row($result_t);
				list($get_translation_id, $get_translation_headline_id, $get_translation_language, $get_translation_value) = $row_t;

				echo"<li><a href=\"my_profile_edit_headline.php?headline_id=$get_headline_id&amp;l=$l\""; if($url_minus_one == "my_profile_edit_headline.php" && $get_headline_id == "$headline_id"){ echo" class=\"navigation_active\"";}echo">$get_translation_value</a></li>\n";
			}

			echo"
			<li><a href=\"my_profile_known_devices.php?l=$l\""; if($url_minus_one == "my_profile_known_devices.php"){ echo" class=\"navigation_active\"";}echo">$l_known_devices</a></li>
			<li><a href=\"my_profile_edit_subscriptions.php?l=$l\""; if($url_minus_one == "my_profile_edit_subscriptions.php"){ echo" class=\"navigation_active\"";}echo">$l_subscriptions</a></li>
			<li><a href=\"my_profile_settings.php?l=$l\""; if($url_minus_one == "my_profile_settings.php"){ echo" class=\"navigation_active\"";}echo">$l_settings</a></li>
			<li><a href=\"my_profile_edit_password.php?l=$l\""; if($url_minus_one == "my_profile_edit_password.php"){ echo" class=\"navigation_active\"";}echo">$l_password</a></li>
			<li><a href=\"logout.php?process=1&amp;l=$l\""; if($url_minus_one  == "logout.php"){ echo" class=\"navigation_active\"";}echo">$l_logout</a></li>

			<li><a href=\"delete_account.php?l=$l\""; if($url_minus_one  == "delete_account.php"){ echo" class=\"navigation_active\"";}echo">$l_delete_account</a></li>
			
		</ul>";
			
	}
}
else{
	echo"
	<ul>
		<li class=\"header_home\"><a href=\"index.php?l=$l&amp;l=$l\""; if($page  == "users"){ echo" class=\"navigation_active\"";}echo">$l_users</a></li>

		<li><a href=\"create_free_account.php?l=$l\""; if($page == "create_free_account"){ echo" class=\"navigation_active\"";}echo">$l_create_free_account</a></li>
		<li><a href=\"login.php?l=$l\""; if($page == "login"){ echo" class=\"navigation_active\"";}echo">$l_login</a></li>
	</ul>";
}
?>