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

$t_users_profile_headlines			= $mysqlPrefixSav . "users_profile_headlines";
$t_users_profile_headlines_translations		= $mysqlPrefixSav . "users_profile_headlines_translations";
$t_users_profile_fields				= $mysqlPrefixSav . "users_profile_fields";
$t_users_profile_fields_translations		= $mysqlPrefixSav . "users_profile_fields_translations";
$t_users_profile_fields_options			= $mysqlPrefixSav . "users_profile_fields_options";
$t_users_profile_fields_options_translations	= $mysqlPrefixSav . "users_profile_fields_options_translations";


/*- Access check -------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}



/*- Language ------------------------------------------------------ */
include("_translations/admin/$l/users/t_users_edit_user.php");


/*- Varialbes  ---------------------------------------------------- */
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
if(isset($_GET['mode'])) {
	$mode = $_GET['mode'];
	$mode = strip_tags(stripslashes($mode));
}
else{
	$mode = "";
}
if(isset($_GET['refer'])) {
	$refer = $_GET['refer'];
	$refer = strip_tags(stripslashes($refer));
}
else{
	$refer = "";
}

// Get user
$user_id_mysql = quote_smart($link, $user_id);

$query = "SELECT user_id, user_email, user_name, user_alias, user_password, user_salt, user_security, user_language, user_gender, user_measurement, user_dob, user_date_format, user_registered, user_last_online, user_rank, user_points, user_likes, user_dislikes, user_status, user_login_tries, user_last_ip, user_synchronized, user_verified_by_moderator FROM $t_users WHERE user_id=$user_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_password, $get_user_salt, $get_user_security, $get_user_language, $get_user_gender, $get_user_measurement, $get_user_dob, $get_user_date_format, $get_user_registered, $get_user_last_online, $get_user_rank, $get_user_points, $get_user_likes, $get_user_dislikes, $get_user_status, $get_user_login_tries, $get_user_last_ip, $get_user_synchronized, $get_user_verified_by_moderator) = $row;
	
if($get_user_id == ""){
	echo"<h1>Error</h1><p>Error with user id.</p>"; 
	die;
}

// Can I edit?
$my_user_id = $_SESSION['admin_user_id'];
$my_user_id = output_html($my_user_id);
$my_user_id_mysql = quote_smart($link, $my_user_id);

$my_security  = $_SESSION['admin_security'];
$my_security = output_html($my_security);
$my_security_mysql = quote_smart($link, $my_security);
$query = "SELECT user_id, user_name, user_language, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql AND user_security=$my_security_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_my_user_id, $get_my_user_name, $get_my_user_language, $get_my_user_rank) = $row;


if($get_my_user_rank != "moderator" && $get_my_user_rank != "admin"){
	echo"
	<h1>Server error 403</h1>
	<p>Your rank is $get_my_user_rank. You can not edit.</p>
	";
	die;
}

elseif($action == "edit_password"){
			if($mode == "save"){


				$inp_password = $_POST['inp_password'];
				$inp_password = output_html($inp_password);
	
				if(empty($inp_password)){
					// Send error
					$fm = "please_enter_a_password";
					$ft = "warning";
				}
				else{

					// Create salt
					$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    					$charactersLength = strlen($characters);
    					$salt = '';
    						for ($i = 0; $i < 6; $i++) {
        					$salt .= $characters[rand(0, $charactersLength - 1)];
    					}
					$inp_user_salt_mysql = quote_smart($link, $salt);


					// Password	
					/* Password is encrypted, then stored in datbase */
					$inp_password_encrypted = sha1($inp_password);
					$inp_user_password_mysql = quote_smart($link, $inp_password_encrypted);


					$result = mysqli_query($link, "UPDATE $t_users SET user_password=$inp_user_password_mysql, user_salt=$inp_user_salt_mysql WHERE user_id=$user_id_mysql");
		


					// Send success
					$fm = "changes_saved";
					$ft = "success";
				}

			}
			echo"
			<h1>$l_edit_password_for $get_user_name</h1>

	<!-- Menu -->
		";
		include("_inc/users/users_edit_user_menu.php");
		echo"
	<!-- //Menu -->


			<form method=\"POST\" action=\"index.php?open=$open&amp;page=$page&amp;action=edit_password&amp;mode=save&amp;user_id=$user_id&amp;l=$l&amp;editor_language=$editor_language\" enctype=\"multipart/form-data\" name=\"nameform\">

			<!-- Feedback -->
				";
				if($ft != "" && $fm != ""){
					if($fm == "please_enter_a_password"){
						$fm = "$l_please_enter_a_password";
					}
					elseif($fm == "changes_saved"){
						$fm = "$l_changes_saved";
					}
					else{
						$fm = "$ft";
					}
					echo"<div class=\"$ft\"><p>$fm</p></div>";
				}
				echo"
			<!-- //Feedback -->


			<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_password\"]').focus();
			});
			</script>
			<!-- //Focus -->


			<p>
			$l_wanted_password:<br />
			<input type=\"password\" name=\"inp_password\" size=\"30\" />
			</p>

			<p>
			<input type=\"submit\" value=\"$l_save\" />
			</p>

			</form>

			";
} // action == "edit_password"
?>