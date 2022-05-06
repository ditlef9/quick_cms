<?php
/*- MySQL Tables -------------------------------------------------- */
$t_users 	 		= $mysqlPrefixSav . "users";
$t_users_cover_photos		= $mysqlPrefixSav . "users_cover_photos";
$t_users_profile 		= $mysqlPrefixSav . "users_profile";
$t_users_friends 		= $mysqlPrefixSav . "users_friends";
$t_users_friends_requests 	= $mysqlPrefixSav . "users_friends_requests";
$t_users_profile		= $mysqlPrefixSav . "users_profile";
$t_users_profile_photo 		= $mysqlPrefixSav . "users_profile_photo";
$t_users_status 		= $mysqlPrefixSav . "users_status";
$t_users_status_comments 	= $mysqlPrefixSav . "users_status_comments";
$t_users_status_comments_likes 	= $mysqlPrefixSav . "users_status_comments_likes";
$t_users_status_likes 		= $mysqlPrefixSav . "users_status_likes";

if(isset($define_can_view_view_profile_not_logged_in)){
	// Header photo
	$current_user_id_mysql = quote_smart($link, $user_id);
	$query = "SELECT cover_photo_id, cover_photo_destination FROM $t_users_cover_photos WHERE cover_photo_user_id=$current_user_id_mysql AND cover_photo_is_current='1'";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_cover_photo_id, $get_current_cover_photo_destination) = $row;

	// Profile
	$query = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id=$current_user_id_mysql AND photo_profile_image='1'";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_photo_id, $get_current_photo_destination) = $row;

	echo"
	<!-- Header photo -->
		<div style=\"margin-top: 10px;background: #ccc;height: 150px;";
			if($get_current_cover_photo_id != ""){
				echo"background: url('$root/_uploads/users/images/$user_id/cover_photos/$get_current_cover_photo_destination'); no-repeat;background-position: center center;";
			}
			echo"padding: 110px 0px 0px 240px;\">
			<a href=\"index.php?category=users&amp;page=view_profile&amp;user_id=$get_current_user_id&amp;l=$l\" style=\"font-size: 25px;color: #fff;text-shadow: black 1px 1px 1px\">$get_current_user_alias</a>
		</div>
	<!-- //Header photo -->


	<!-- Left -->
		<div class=\"left\" style=\"width: 270px;display:inline-block;\">
			<!-- Photo -->
				<div class=\"left_background\">
					<div style=\"width:100%;margin: 0px auto;text-align:center;\">";
					if($get_current_photo_id != ""){
						echo"
						<img src=\"$root/image.php?width=128&amp;height=128&amp;cropratio=1:1&amp;image=/_uploads/users/images/$get_current_user_id/$get_current_photo_destination\" style=\"border-radius: 50%;margin-top: -50px;\" alt=\"$get_current_photo_destination\" />
						";
					}
					echo"
						
					</div>
				</div>
			<!-- Photo -->



			<!-- About -->
				<div class=\"left_background\" style=\"margin-top: 10px;padding:0px 6px 0px 6px;\">
					<h2>$l_about</h2>
					";

					if($get_current_user_dob != ""){
						$age = date('Y') - substr($get_current_user_dob, 0, 4);
						if (strtotime(date('Y-m-d')) - strtotime(date('Y') . substr($get_current_user_dob, 4, 6)) < 0){
							$age--;
						}



						echo"
						<table>
						 <tr>
						  <td style=\"padding: 0px 4px 8px 0px;\">
							<span class=\"glyphicon glyphicon-gift\"></span> <span class=\"grey_dark\">$l_age:</span>
						  </td>
						  <td style=\"padding: 0px 0px 8px 0px;\">
							<span style=\"font-weight: bold;\">$age</span>
						  </td>
						 </tr>
						</table>";
						
					}

					if($get_current_profile_city != ""){
						echo"
						<table>
						 <tr>
						  <td style=\"padding: 0px 4px 8px 0px;\">
							<span class=\"glyphicon glyphicon-map-marker\"></span> <span class=\"grey_dark\">$l_city:</span>
						  </td>
						  <td style=\"padding: 0px 0px 8px 0px;\">
							<span style=\"font-weight: bold;\">$get_current_profile_city"; 
						if($get_current_profile_country != ""){ echo", $get_current_profile_country"; } echo"</span>
						  </td>
						 </tr>
						</table>";
					}

					if($get_current_profile_work != ""){
						echo"
						<table>
						 <tr>
						  <td style=\"padding: 0px 4px 8px 0px;\">
							<span class=\"glyphicon glyphicon-briefcase\"></span> <span class=\"grey_dark\">$l_work:</span>
						  </td>
						  <td style=\"padding: 0px 0px 8px 0px;\">
							<span style=\"font-weight: bold;\">$get_current_profile_work</span>
						  </td>
						 </tr>
						</table>";
					}
					else{

						if($get_current_profile_university != ""){
							echo"
							<table>
							 <tr>
							  <td style=\"padding: 0px 4px 8px 0px;\">
								<span class=\"glyphicon glyphicon-education\"></span> <span class=\"grey_dark\">$l_university:</span>
							  </td>
							  <td style=\"padding: 0px 0px 8px 0px;\">
								<span style=\"font-weight: bold;\">$get_current_profile_university</span>
							  </td>
							 </tr>
							</table>";
						}
						else{
							if($get_current_profile_high_school != ""){
								echo"
								<table>
								 <tr>
								  <td style=\"padding: 0px 4px 8px 0px;\">
									<span class=\"glyphicon glyphicon-apple\"></span> <span class=\"grey_dark\">$l_school:</span>
								  </td>
								  <td style=\"padding: 0px 0px 8px 0px;\">
									<span style=\"font-weight: bold;\">$get_current_profile_high_school</span>
								  </td>
								 </tr>
								</table>";
							}
						}
					}
					echo"
				</div>
			<!-- //About -->
		</div>
	<!-- //Left -->

	<!-- Right -->
		<div style=\"width: auto;overflow: hidden;\">";
			$host = $_SERVER['HTTP_HOST'];
			$l_user_is_on_site = str_replace("%alias%", $get_current_user_alias, $l_user_is_on_site);
			$l_user_is_on_site = str_replace("%site%", $host, $l_user_is_on_site);
			$l_to_view_profile_then_add_user_as_friend = str_replace("%alias%", $get_current_user_alias, $l_to_view_profile_then_add_user_as_friend);
			echo"
			<p><b>$l_user_is_on_site</b></p>

			<p>$l_you_have_to_be_logged_in_to_view_the_profile</p>

			<p>
			<a href=\"index.php?category=users&amp;page=login&amp;l=$l&refer=page=view_profileamp;user_id=$user_id\" class=\"btn\">$l_login</a>
			<a href=\"index.php?category=users&amp;page=create_free_account&amp;l=$l\" class=\"btn\">$l_create_free_account</a>
			</p>
			
		</div>
	<!-- //Right -->
	";

	echo"


	";
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
		
	<meta http-equiv=\"refresh\" content=\"1;url=index.php?category=users&amp;page=login&amp;l=$l&refer=page=view_profileamp;user_id=$user_id\">
	";
}
?>