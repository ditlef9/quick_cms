<?php
/**
*
* File: users/my_profile_edit_password.php
* Version 11:15 08.08.2021
* Copyright (c) 2009-2021 Sindre Andre Ditlefsen
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

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/users/ts_index.php");
include("$root/_admin/_translations/site/$l/users/ts_my_profile_edit_password.php");

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_edit_password - $l_my_profile - $l_users";
include("$root/_webdesign/header.php");



/*- Content --------------------------------------------------------------------------- */


if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	// Get user
	$user_id = $_SESSION['user_id'];
	$user_id_mysql = quote_smart($link, $user_id);
	$security = $_SESSION['security'];
	$security_mysql = quote_smart($link, $security);

	$query = "SELECT user_id, user_name, user_language, user_rank FROM $t_users WHERE user_id=$user_id_mysql AND user_security=$security_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_name, $get_user_language, $get_user_rank) = $row;

	if($get_user_id == ""){
		echo"<h1>Error</h1><p>Error with user id.</p>"; 
		$_SESSION = array();
		session_destroy();
		die;
	}

	if($action == "save"){

		$inp_password = $_POST['inp_password'];
		$inp_password_encrypted = sha1("$inp_password");
		$inp_password_mysql = quote_smart($link, $inp_password_encrypted);


		if(empty($inp_password)){
			$url = "my_profile_edit_password.php?l=$l&ft=warning&fm=users_please_enter_a_password"; 
			header("Location: $url");
			exit;
		}
		

		// Create salt
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    		$charactersLength = strlen($characters);
    		$salt = '';
    		for ($i = 0; $i < 6; $i++) {
        		$salt .= $characters[rand(0, $charactersLength - 1)];
    		}
		$inp_user_salt_mysql = quote_smart($link, $salt);



		$result = mysqli_query($link, "UPDATE $t_users SET user_password=$inp_password_mysql, user_salt=$inp_user_salt_mysql WHERE user_id=$user_id_mysql");
		

		$url = "my_profile_edit_password.php?l=$l&ft=success&fm=changes_saved";
		header("Location: $url");
		exit;
	}
	if($action == ""){
		echo"
		<h1>$l_edit_password</h1>

		<!-- You are here -->
			<div class=\"you_are_here\">
				<p>
				<b>$l_you_are_here:</b><br />
				<a href=\"my_profile.php?l=$l\">$l_my_profile</a>
				&gt; 
				<a href=\"my_profile_edit_password.php?l=$l\">$l_edit_password</a>
				</p>
			</div>
		<!-- //You are here -->

		<form method=\"POST\" action=\"my_profile_edit_password.php?action=save&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\" name=\"nameform\">

		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "users_please_enter_a_password"){
					$fm = "$l_users_please_enter_a_password";
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
		<input type=\"password\" name=\"inp_password\" size=\"25\" autocomplete=\"off\" />
		</p>

		<p>
		<input type=\"submit\" value=\"$l_save\" class=\"btn\" />
		</p>

		</form>

		";
	}
}
else{
	echo"
	<table>
	 <tr> 
	  <td style=\"padding-right: 6px;\">
		<p>
		<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"Loading\" />
		</p>
	  </td>
	  <td>
		<h1>Loading</h1>
	  </td>
	 </tr>
	</table>
		
	<meta http-equiv=\"refresh\" content=\"1;url=index.php\">
	";
}
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>