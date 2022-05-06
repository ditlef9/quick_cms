<?php
/**
*
* File: users/my_profile_delete_account.php
* Version 17.46 18.02.2021
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

/*- Tables ----------------------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/users/ts_index.php");
/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_delete_account - $l_my_profile - $l_users";
include("$root/_webdesign/header.php");



/*- Content --------------------------------------------------------------------------- */

if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	// Get user
	$user_id = $_SESSION['user_id'];
	$user_id_mysql = quote_smart($link, $user_id);
	$security = $_SESSION['security'];
	$security_mysql = quote_smart($link, $security);

	$query = "SELECT user_id, user_name, user_password, user_salt, user_language, user_rank FROM $t_users WHERE user_id=$user_id_mysql AND user_security=$security_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_name, $get_user_password, $get_user_salt, $get_user_language, $get_user_rank) = $row;

	if($get_user_id == ""){
		echo"<h1>Error</h1><p>Error with user id.</p>"; 
		$_SESSION = array();
		session_destroy();
		die;
	}

	if($process == "1"){

		$inp_password = $_POST['inp_password'];
		$inp_password_encrypted = sha1("$inp_password");

		if($inp_password_encrypted == "$get_user_password"){

		
			// Delete user
			$result = mysqli_query($link, "DELETE FROM $t_users WHERE user_id=$user_id_mysql");
			
			// Browse photos
			$query = "SELECT photo_id, photo_destination, photo_uploaded, photo_uploaded_ip, photo_views, photo_views_ip_block, photo_likes, photo_comments FROM $t_users_profile_photo WHERE photo_user_id='$get_user_id'";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_photo_id, $get_photo_destination, $get_photo_uploaded, $get_photo_uploaded_ip, $get_photo_views, $get_photo_views_ip_block, $get_photo_likes, $get_photo_comments) = $row;
				
				if(file_exists("_uploads/users/images/$get_user_id/$get_photo_destination")){
					unlink("_uploads/users/images/$get_user_id/$get_photo_destination");
				}
			}
						
			// Delete photos
			$result = mysqli_query($link, "DELETE FROM $t_users_profile_photo WHERE photo_user_id=$user_id_mysql");

			
			// Search engine
			$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name='users' AND index_reference_name='user_id' AND index_reference_id=$get_user_id";
			$result_exists = mysqli_query($link, $query_exists);
			$row_exists = mysqli_fetch_row($result_exists);
			list($get_index_id) = $row_exists;
			if($get_index_id != ""){
				$result = mysqli_query($link, "DELETE FROM $t_search_engine_index WHERE index_id=$get_index_id") or die(mysqli_error($link));
			}

			$url = "logout.php?process=1";
			header("Location: $url");
			exit;
		}
		else{
			$url = "my_profile_delete_account.php?ft=warning&fm=wrong_password&l=$l";
			header("Location: $url");
			exit;
		}
	}
	if($process == ""){
		echo"
		<h1>$l_delete_account</h1>

		<p>
		$l_please_enter_your_password_to_confirm 
		</p>


		<form method=\"POST\" action=\"my_profile_delete_account.php?l=$l&amp;process=1\" enctype=\"multipart/form-data\" name=\"nameform\">

		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				$fm = "$l_wrong_password";
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
		$l_password:
		<input type=\"password\" name=\"inp_password\" size=\"30\" />
		
		<input type=\"submit\" value=\"$l_confirm\" />
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
		<img src=\"_gfx/loading_22.gif\" alt=\"Loading\" />
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