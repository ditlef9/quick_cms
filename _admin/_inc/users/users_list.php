<?php
/**
*
* File: _admin/_inc/users/default.php
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

/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['order_by'])) {
	$order_by = $_GET['order_by'];
	$order_by = strip_tags(stripslashes($order_by));
}
else{
	$order_by = "";
}
if($order_by == ""){
	$order_by = "user_name";
}
if(isset($_GET['order_method'])) {
	$order_method = $_GET['order_method'];
	$order_method = strip_tags(stripslashes($order_method));
	if($order_method != "asc" && $order_method != "desc"){
		echo"Wrong order method";
		die;
	}
}
else{
	$order_method = "asc";
}






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


if($action == ""){
	echo"
	<h1>$l_users</h1>


	<!-- Feedback -->
	";
	if($ft != "" && $fm != ""){
		if($fm == "user_deleted"){
			$fm = "$l_user_deleted";
		}
		else{
			$fm = ucfirst($ft);
		}
		echo"<div class=\"$ft\"><p>$fm</p></div>";
	}
	echo"
	<!-- //Feedback -->


	<!-- Users list -->
	<p>
	<a href=\"index.php?open=$open&amp;page=users_new&amp;editor_language=$editor_language\" class=\"btn\">$l_new_user</a>
	</p>

		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">";
			if($order_by == "user_id" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=user_id&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>ID</b></a>";
			if($order_by == "user_id" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "user_id" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		   <th scope=\"col\">";
			if($order_by == "user_name" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=user_name&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>User name</b></a>";
			if($order_by == "user_name" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "user_name" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		   <th scope=\"col\">";
			if($order_by == "user_first_name" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=user_first_name&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>First name</b></a>";
			if($order_by == "user_first_name" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "user_first_name" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		   <th scope=\"col\">";
			if($order_by == "user_middle_name" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=user_middle_name&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>Middle name</b></a>";
			if($order_by == "user_middle_name" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "user_middle_name" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		   <th scope=\"col\">";
			if($order_by == "user_last_name" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=user_last_name&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>Last name</b></a>";
			if($order_by == "user_last_name" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "user_last_name" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		   <th scope=\"col\">";
			if($order_by == "user_email" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=user_email&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>E-mail</b></a>";
			if($order_by == "user_email" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "user_email" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		   <th scope=\"col\">";
			if($order_by == "user_rank" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=user_rank&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>Rank</b></a>";
			if($order_by == "user_rank" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "user_rank" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		   <th scope=\"col\">
			<span>Photo</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_actions</span>
		   </th>
		  </tr>
		</thead>
		<tbody>


	";
	$query = "SELECT user_id, user_email, user_name, user_alias, user_gender, user_rank, user_first_name, user_middle_name, user_last_name FROM $t_users";
	if($order_by == "user_id" OR $order_by == "user_email" OR $order_by == "user_name" OR $order_by == "user_alias" OR $order_by == "user_rank" OR $order_by == "user_first_name" OR $order_by == "user_middle_name" OR $order_by == "user_last_name"){
		if($order_method == "asc"){
			$query = $query . " ORDER BY $order_by ASC";
		}
		else{
			$query = $query . " ORDER BY $order_by DESC";
		}
	}

	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_gender, $get_user_rank, $get_user_first_name,  $get_user_middle_name,  $get_user_last_name) = $row;

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
			<span><a href=\"?open=$open&amp;page=users_edit_user&amp;user_id=$get_user_id&amp;l=$l&amp;editor_language=$editor_language\">$get_user_id</a></span>
		  </td>
		  <td class=\"$style\">
			<span><a href=\"?open=$open&amp;page=users_edit_user&amp;user_id=$get_user_id&amp;l=$l&amp;editor_language=$editor_language\">$get_user_name</a></span>
		  </td>
		  <td class=\"$style\">
			<span>$get_user_first_name</span>
		  </td>
		  <td class=\"$style\">
			<span>$get_user_middle_name</span>
		  </td>
		  <td class=\"$style\">
			<span>$get_user_last_name</span>
		  </td>
		  <td class=\"$style\">
			<span>$get_user_email</span>
		  </td>
		  <td class=\"$style\">
			<span>$get_user_rank</span>
		  </td>
		  <td class=\"$style\">
			";
			if($get_photo_id != ""){
				$thumb = str_replace("_org", "_thumb", $get_photo_destination);
				echo"
				<a href=\"index.php?open=$open&amp;page=users_edit_user&amp;user_id=$get_user_id&amp;l=$l&amp;editor_language=$editor_language\"><img src=\"../image.php?width=35&amp;height=35&amp;cropratio=1:1&amp;image=/_uploads/users/images/$get_user_id/$thumb\" alt=\"$get_photo_destination\" class=\"image_rounded\" style=\"float: left;margin-right: 5px;\" /></a>
				";
			}
			else{
				echo"
				<a href=\"index.php?open=$open&amp;page=users_edit_user&amp;user_id=$get_user_id&amp;l=$l&amp;editor_language=$editor_language\"><img src=\"_design/gfx/avatar_blank_35.png\" alt=\"Avatar\" class=\"image_rounded\" style=\"float: left;margin-right: 5px;\" /></a>
				";
			}
			echo"
		  </td>
		  <td class=\"$style\">
			<span>
			<a href=\"?open=$open&amp;page=users_edit_user&amp;user_id=$get_user_id&amp;l=$l&amp;editor_language=$editor_language\">$l_edit</a>
			| 
			<a href=\"?open=$open&amp;page=users_delete_user&amp;user_id=$get_user_id&amp;l=$l&amp;process=1&amp;editor_language=$editor_language\" class=\"confirm\">$l_delete</a>
			| 
			<a href=\"?open=$open&amp;page=users_identify_as_user&amp;user_id=$get_user_id&amp;l=$l&amp;process=1&amp;editor_language=$editor_language\" class=\"confirm\">Identify as</a>
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
	<!-- //Users list -->
	";
}
?>
