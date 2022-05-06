<?php
/*- MySQL Tables -------------------------------------------------- */
$t_users 	 		= $mysqlPrefixSav . "users";
$t_users_profile 		= $mysqlPrefixSav . "users_profile";
$t_users_friends 		= $mysqlPrefixSav . "users_friends";
$t_users_friends_requests 	= $mysqlPrefixSav . "users_friends_requests";
$t_users_profile		= $mysqlPrefixSav . "users_profile";
$t_users_profile_photo 		= $mysqlPrefixSav . "users_profile_photo";
$t_users_status 		= $mysqlPrefixSav . "users_status";
$t_users_status_comments 	= $mysqlPrefixSav . "users_status_comments";
$t_users_status_comments_likes 	= $mysqlPrefixSav . "users_status_comments_likes";
$t_users_status_likes 		= $mysqlPrefixSav . "users_status_likes";

// Variables
if(isset($_GET['user_id'])) {
	$user_id = $_GET['user_id'];
	$user_id = strip_tags(stripslashes($user_id));
}
else{
	$user_id = "";
	echo"
	<h1>Error</h1>

	<p>$l_user_profile_not_found</p>
	";
	die;
}
if(isset($_GET['refer'])) {
	$refer = $_GET['refer'];
	$refer = strip_tags(stripslashes($refer));
}
else{
	$refer = "";
}


// Am I admin?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	// Get user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$my_security = $_SESSION['security'];
	$my_security_mysql = quote_smart($link, $my_security);

	$query = "SELECT user_id, user_name, user_language, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql AND user_security=$my_security_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_name, $get_my_user_language, $get_my_user_rank) = $row;


	if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){



		// Get user
		$user_id_mysql = quote_smart($link, $user_id);

		$query = "SELECT * FROM $t_users WHERE user_id=$user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_user_id, $get_user_email, $get_user_name, $get_user_password, $get_user_salt, $get_user_security, $get_user_language, $get_user_registered, $get_user_last_online, $get_user_rank, $get_user_points, $get_user_likes, $get_user_dislikes, $get_user_status, $get_user_login_tries, $get_user_last_ip) = $row;

		$query = "SELECT profile_id, profile_user_id, profile_first_name, profile_middle_name, profile_last_name, profile_address_line_a, profile_address_line_b, profile_zip, profile_city, profile_country, profile_phone, profile_work, profile_university, profile_high_school, profile_languages, profile_website, profile_interested_in, profile_relationship, profile_about, profile_newsletter FROM $t_users_profile WHERE profile_user_id=$user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_profile_id, $get_profile_user_id, $get_profile_first_name, $get_profile_middle_name, $get_profile_last_name, $get_profile_address_line_a, $get_profile_address_line_b, $get_profile_zip, $get_profile_city, $get_profile_country, $get_profile_phone, $get_profile_work, $get_profile_university, $get_profile_high_school, $get_profile_languages, $get_profile_website, $get_profile_interested_in, $get_profile_relationship, $get_profile_about, $get_profile_newsletter) = $row;

		if($get_user_id == ""){
			echo"<h1>Error</h1><p>Error with user id.</p>"; 
			die;
		}

		if($get_my_user_rank == "moderator" && $get_user_rank == "admin"){
			echo"
			<h1>Server error 403</h1>
			<p>You can not edit a administrator.</p>
			";
			die;
		}

		// Delete user
		$result = mysqli_query($link, "DELETE FROM $t_users WHERE user_id=$user_id_mysql");
		
		// Delete profile
		$result = mysqli_query($link, "DELETE FROM $t_users_profile WHERE profile_user_id=$user_id_mysql");
		
		// Browse photos
		$query = "SELECT photo_id, photo_destination, photo_uploaded, photo_uploaded_ip, photo_views, photo_views_ip_block, photo_likes, photo_comments FROM $t_users_profile_photo WHERE photo_user_id='$get_user_id'";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_photo_id, $get_photo_destination, $get_photo_uploaded, $get_photo_uploaded_ip, $get_photo_views, $get_photo_views_ip_block, $get_photo_likes, $get_photo_comments) = $row;
			$thumb = str_replace("_org", "_thumb", $get_photo_destination);
			
			
			unlink("$root/_scripts/users/images/$get_user_id/$get_photo_destination");
			unlink("$root/_scripts/users/images/$get_user_id/$thumb");
		}
						
		// Delete photos
		$result = mysqli_query($link, "DELETE FROM $t_users_profile_photo WHERE photo_user_id=$user_id_mysql");

		// Header
		$url = "index.php?category=users&page=moderator&l=$l&ft=success&fm=user_deleted";
		header("Location: $url");
		exit;
	}
	else{
		echo"
		<h1>Server error 403</h1>

		<p>Administrator and moderator only.</p>
		";
	}
}
else{

	echo"
	<h1>Server error 403</h1>

	<p>Not logged in.</p>
	";
}
?>