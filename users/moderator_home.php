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


if($define_in_moderator == "1"){
	echo"
	<h1>$l_moderator</h1>


	<!-- Menu -->
		<div id=\"tabs\">
			<ul>
				<li"; if($action == ""){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator&amp;l=$l\">$l_users</a></li>
				<li"; if($action == "translations"){ echo" id=\"current\" "; } echo"><a href=\"index.php?category=users&amp;page=moderator&amp;action=translations&amp;l=$l\">$l_translations</a></li>
			</ul>
		</div>
		<div class=\"clear\"></div>
	<!-- //Menu -->


	<table style=\"width: 100%;\">
	 <tr>
	  <td class=\"outline\">
		<table style=\"border-spacing: 1px;width:100%;\">
		 <tr>
		  <td class=\"headcell\">
			<span>$l_user_name</span>
		  </td>
		  <td class=\"headcell\">
			<span>$l_rank</span>
		  </td>
		  <td class=\"headcell\">
			<span>$l_actions</span>
		  </td>
		 </tr>
	";

	$query = "SELECT user_id, user_name, user_rank FROM $t_users ORDER BY user_last_online DESC";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_user_id, $get_user_name, $get_user_rank) = $row;

		// Profile
		$query = "SELECT profile_id, profile_user_id, profile_first_name, profile_middle_name, profile_last_name, profile_address_line_a, profile_address_line_b, profile_zip, profile_city, profile_country, profile_phone, profile_work, profile_university, profile_high_school, profile_languages, profile_website, profile_interested_in, profile_relationship, profile_about, profile_newsletter FROM $t_users_profile WHERE profile_user_id=$get_user_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_profile_id, $get_profile_user_id, $get_profile_first_name, $get_profile_middle_name, $get_profile_last_name, $get_profile_address_line_a, $get_profile_address_line_b, $get_profile_zip, $get_profile_city, $get_profile_country, $get_profile_phone, $get_profile_work, $get_profile_university, $get_profile_high_school, $get_profile_languages, $get_profile_website, $get_profile_interested_in, $get_profile_relationship, $get_profile_about, $get_profile_newsletter) = $row;

		// Photo
		$q = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id='$get_user_id' AND photo_profile_image='1'";
		$r = mysqli_query($link, $q);
		$rowb = mysqli_fetch_row($r);
		list($get_photo_id, $get_photo_destination) = $rowb;
	
		// Style
		if(isset($style) && $style == "bodycell"){
			$style = "subcell";
		}
		else{
			$style = "subcell";
		}
	
		echo"
		 <tr>
		  <td class=\"$style\">
			";
			if($get_photo_id != ""){
				$thumb = str_replace("_org", "_thumb", $get_photo_destination);
				echo"
				<a href=\"index.php?category=users&amp;page=view_profile&amp;user_id=$get_user_id&amp;l=$l\"><img src=\"$root/image.php?width=35&amp;height=35&amp;cropratio=1:1&amp;image=/_scripts/users/images/$get_user_id/$thumb\" alt=\"$get_photo_destination\" class=\"image_rounded\" style=\"float: left;margin-right: 5px;\" /></a>
				";
			}
			else{
				echo"
				<a href=\"index.php?category=users&amp;page=view_profile&amp;user_id=$get_user_id&amp;l=$l\"><img src=\"$root/_webdesign/images/avatar_blank_35.png\" alt=\"Avatar\" class=\"image_rounded\" style=\"float: left;margin-right: 5px;\" /></a>
				";
			}
			echo"
			<span>$get_user_name<br />
			$get_profile_first_name  $get_profile_middle_name  $get_profile_last_name</span>
		  </td>
		  <td class=\"$style\">
			<span>$get_user_rank</span>
		  </td>
		  <td class=\"$style\">
			<span><a href=\"index.php?category=users&amp;page=moderator_edit_user&amp;user_id=$get_user_id&amp;l=$l&amp;refer=admin_home\">$l_edit</a>
			| <a href=\"index.php?category=users&amp;page=moderator_delete_user&amp;user_id=$get_user_id&amp;l=$l&amp;refer=admin_home&amp;process=1\" class=\"confirm\">$l_delete</a></span>
		  </td>
		 </tr>
		";

	}
	echo"
		</table>
	  </td>
	 </tr>
	</table>

	<script>
	\$(function() {
		\$('.confirm').click(function() {
			return window.confirm(\"$l_are_you_sure\");
		});
	});
	</script>
	";
		
} // $define_in_admin == 1
else{
	echo"<h1>Server error 403</h1>";
}
?>