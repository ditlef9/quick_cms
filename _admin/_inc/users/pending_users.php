<?php
/**
*
* File: _admin/_inc/users/pending_users.php
* Version 1.0
* Date: 18:32 30.10.2017
* Copyright (c) 2008-2012 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
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


/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['user_id'])) {
	$user_id = $_GET['user_id'];
	$user_id = strip_tags(stripslashes($user_id));
}
else{
	$user_id = "";
}


if($action == ""){

	echo"
	<h1>$l_pending_users</h1>


	<!-- Feedback -->
		";
		if($ft != "" && $fm != ""){
			if($fm == "user_approved"){
				$fm = "$l_user_approved";
			}
			elseif($fm == "user_disapproved"){
				$fm = "$l_user_disapproved";
			}
			else{
				$fm = ucfirst($ft);
			}
			echo"<div class=\"$ft\"><p>$fm</p></div>";
		}
		echo"
	<!-- //Feedback -->


	<!-- Pending Users  -->

		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>$l_user_name</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_email</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_ip</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_registered</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_approve</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_actions</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_disapprove</span>
		   </th>
		  </tr>
		</thead>
		<tbody>


		";

		$query = "SELECT user_id, user_email, user_name, user_registered, user_last_ip FROM $t_users WHERE user_verified_by_moderator='0'";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_user_id, $get_user_email, $get_user_name, $get_user_registered, $get_user_last_ip) = $row;

			// Photo
			$q = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id='$get_user_id' AND photo_profile_image='1'";
			$r = mysqli_query($link, $q);
			$rowb = mysqli_fetch_row($r);
			list($get_photo_id, $get_photo_destination) = $rowb;
	
			// Style
			if(isset($style) && $style == ""){
				$style = "odd";
			}
			else{
				$style = "";
			}
	
			echo"
			 <tr>
			  <td class=\"$style\">
				";
				if($get_photo_id != ""){
					$thumb = str_replace("_org", "_thumb", $get_photo_destination);
					echo"
					<a href=\"index.php?open=$open&amp;page=users_view_profile&amp;user_id=$get_user_id&amp;l=$l&amp;editor_language=$editor_language\"><img src=\"../image.php?width=35&amp;height=35&amp;cropratio=1:1&amp;image=/_scripts/users/images/$get_user_id/$thumb\" alt=\"$get_photo_destination\" class=\"image_rounded\" style=\"float: left;margin-right: 5px;\" /></a>
					";
				}
				else{
					echo"
					<a href=\"index.php?open=$open&amp;page=users_view_profile&amp;user_id=$get_user_id&amp;l=$l&amp;editor_language=$editor_language\"><img src=\"_design/gfx/avatar_blank_35.png\" alt=\"Avatar\" class=\"image_rounded\" style=\"float: left;margin-right: 5px;\" /></a>
					";
				}
				echo"
				<span>$get_user_name</span>
			  </td>
			  <td class=\"$style\">
				<span>$get_user_email</span>
			  </td>
			  <td class=\"$style\">
				<span>$get_user_last_ip</span>
			  </td>
			  <td class=\"$style\">
				<span>$get_user_registered</span>
			  </td>
			  <td class=\"$style\">
				<span>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=approve&amp;user_id=$get_user_id&amp;editor_language=$editor_language&amp;process=1\">$l_approve</a>
				</span>
			  </td>
			  <td class=\"$style\">
				<span><a href=\"?open=$open&amp;page=users_edit_user&amp;user_id=$get_user_id&amp;l=$l&amp;editor_language=$editor_language\">$l_edit</a>
				| <a href=\"?open=$open&amp;page=users_delete_user&amp;user_id=$get_user_id&amp;l=$l&amp;process=1&amp;editor_language=$editor_language\" class=\"confirm\">$l_delete</a></span>
			  </td>
			  <td class=\"$style\">
				<span>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=disapprove&amp;user_id=$get_user_id&amp;editor_language=$editor_language&amp;process=1\">$l_disapprove</a>
				</span>
			  </td>
			 </tr>
			";

		}
		echo"
	
		 </tbody>
		</table>

		<script>
		\$(function() {
			\$('.confirm').click(function() {
				return window.confirm(\"$l_are_you_sure\");
			});
		});
		</script>
	<!-- //Pending Users  -->
	";
}
elseif($action == "approve"){
	// Fetch user
	$user_id_mysql = quote_smart($link, $user_id);
	
	$query = "SELECT user_id, user_email, user_name, user_alias, user_password, user_salt, user_security, user_language, user_gender, user_measurement, user_dob, user_date_format, user_registered, user_last_online, user_rank, user_points, user_likes, user_dislikes, user_status, user_login_tries, user_last_ip, user_synchronized FROM $t_users WHERE user_id=$user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_password, $get_user_salt, $get_user_security, $get_user_language, $get_user_gender, $get_user_measurement, $get_user_dob, $get_user_date_format, $get_user_registered, $get_user_last_online, $get_user_rank, $get_user_points, $get_user_likes, $get_user_dislikes, $get_user_status, $get_user_login_tries, $get_user_last_ip, $get_user_synchronized) = $row;

	if($get_user_id == ""){
		echo"<h1>Error</h1><p>Error with user id.</p>"; 
		die;
	}
	else{
		// Update
		$result = mysqli_query($link, "UPDATE $t_users SET user_verified_by_moderator='1' WHERE user_id=$user_id_mysql");
		

		// E-mail
		$host = $_SERVER['HTTP_HOST'];
		$from = "post@" . $_SERVER['HTTP_HOST'];
		$reply = "post@" . $_SERVER['HTTP_HOST'];

		$headers = "From: $from" . "\r\n" .
			    "Reply-To: $reply" . "\r\n" .
			    'X-Mailer: PHP/' . phpversion();

		$subject = "Account approved at $host";
		$message = "Hi $get_user_name\n\nYour account is now approved and you can log in with your e-mail address as username.\n\n--Regards\n$host";

		mail($get_user_email, $subject, $message, $headers);

		// Headers
		$url = "index.php?open=users&amp;page=pending_users&amp;editor_language=$editor_language&amp;ft=success&amp;fm=user_approved";

		echo"
		<h1><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float: left;padding: 2px 4px 0px 0px\" /> Approved!</h1>

		<meta http-equiv=\"refresh\" content=\"5;url=$url\">
		
		<table>
		 <tr>
		  <td>
			<p>User name:</p>
		  </td>
		  <td>
			<p>$get_user_name</p>
		  </td>
		 </tr>
		 <tr>
		  <td>
			<p>Email:</p>
		  </td>
		  <td>
			<p>$get_user_email</p>
		  </td>
		 </tr>
		</table>

		<p>
		<a href=\"$url\" class=\"btn\">$l_continue</a>
		</p>
		";
	}

}
elseif($action == "disapprove"){
	// Fetch user
	$user_id_mysql = quote_smart($link, $user_id);
	
	$query = "SELECT user_id, user_email, user_name, user_alias, user_password, user_salt, user_security, user_language, user_gender, user_measurement, user_dob, user_date_format, user_registered, user_last_online, user_rank, user_points, user_likes, user_dislikes, user_status, user_login_tries, user_last_ip, user_synchronized FROM $t_users WHERE user_id=$user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_password, $get_user_salt, $get_user_security, $get_user_language, $get_user_gender, $get_user_measurement, $get_user_dob, $get_user_date_format, $get_user_registered, $get_user_last_online, $get_user_rank, $get_user_points, $get_user_likes, $get_user_dislikes, $get_user_status, $get_user_login_tries, $get_user_last_ip, $get_user_synchronized) = $row;

	if($get_user_id == ""){
		echo"<h1>Error</h1><p>Error with user id.</p>"; 
		die;
	}
	else{
		// Update
		$result = mysqli_query($link, "UPDATE $t_users SET user_verified_by_moderator='1' WHERE user_id=$user_id_mysql");
		

		// E-mail
		$host = $_SERVER['HTTP_HOST'];
		$from = "post@" . $_SERVER['HTTP_HOST'];
		$reply = "post@" . $_SERVER['HTTP_HOST'];

		$headers = "From: $from" . "\r\n" .
			    "Reply-To: $reply" . "\r\n" .
			    'X-Mailer: PHP/' . phpversion();

		$subject = "Account not approved at $host";
		$message = "Hi $get_user_name\n\nYour account was not approved. Please feel free to reply to this email to complain.\n\n--Regards\n$host";

		mail($get_user_email, $subject, $message, $headers);

		// Headers
		$url = "index.php?open=users&amp;page=pending_users&amp;editor_language=$editor_language&amp;ft=success&amp;fm=user_disapproved";
		echo"
		<h1><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float: left;padding: 2px 4px 0px 0px\" /> Disapproved!</h1>

		<meta http-equiv=\"refresh\" content=\"1;url=$url\">

		<p>
		<a href=\"$url\" class=\"btn\">$l_continue</a>
		</p>
		";
	}

}
?>