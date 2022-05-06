<?php
/**
*
* File: users/index.php
* Version 17.46 18.02.2017
* Copyright (c) 2009-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "0";
$pageNoColumnSav      = "1";
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

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/users/ts_index.php");

/*- Variables --------------------------------------------------------------------------- */
if(isset($_GET['user_id'])) {
	$user_id = $_GET['user_id'];
	$user_id = strip_tags(stripslashes($user_id));
	if(!(is_numeric($user_id))){
		echo"headline_id not numeric";
		die;
	}
}
else{
	$user_id = "";
	echo"
	<h1>Error</h1>
	
	<p>$l_user_profile_not_found</p>
	";
	die;
}
$user_id_mysql = quote_smart($link, $user_id);

if(isset($_GET['headline_id'])) {
	$headline_id = $_GET['headline_id'];
	$headline_id = strip_tags(stripslashes($headline_id));
	if(!(is_numeric($headline_id))){
		echo"headline_id not numeric";
		die;
	}
}
else{
	$headline_id = "";
}
$headline_id_mysql = quote_smart($link, $headline_id);


$tabindex = 0;

/*- Content --------------------------------------------------------------------------- */


// Get user
$query = "SELECT user_id, user_email, user_name, user_alias, user_password, user_password_replacement, user_password_date, user_salt, user_security, user_rank, user_verified_by_moderator, user_first_name, user_middle_name, user_last_name, user_language, user_country_id, user_country_name, user_city_name, user_timezone_utc_diff, user_timezone_value, user_measurement, user_date_format, user_gender, user_height, user_dob, user_registered, user_registered_time, user_newsletter, user_privacy, user_views, user_views_ipblock, user_points, user_points_rank, user_likes, user_dislikes, user_status, user_login_tries, user_last_online, user_last_online_time, user_last_ip, user_synchronized, user_notes, user_marked_as_spammer FROM $t_users WHERE user_id=$user_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_user_id, $get_current_user_email, $get_current_user_name, $get_current_user_alias, $get_current_user_password, $get_current_user_password_replacement, $get_current_user_password_date, $get_current_user_salt, $get_current_user_security, $get_current_user_rank, $get_current_user_verified_by_moderator, $get_current_user_first_name, $get_current_user_middle_name, $get_current_user_last_name, $get_current_user_language, $get_current_user_country_id, $get_current_user_country_name, $get_current_user_city_name, $get_current_user_timezone_utc_diff, $get_current_user_timezone_value, $get_current_user_measurement, $get_current_user_date_format, $get_current_user_gender, $get_current_user_height, $get_current_user_dob, $get_current_user_registered, $get_current_user_registered_time, $get_current_user_newsletter, $get_current_user_privacy, $get_current_user_views, $get_current_user_views_ipblock, $get_current_user_points, $get_current_user_points_rank, $get_current_user_likes, $get_current_user_dislikes, $get_current_user_status, $get_current_user_login_tries, $get_current_user_last_online, $get_current_user_last_online_time, $get_current_user_last_ip, $get_current_user_synchronized, $get_current_user_notes, $get_current_user_marked_as_spammer) = $row;


if($get_current_user_id == ""){
	echo"
	<h1>Error</h1>
	
	<p>$l_user_profile_not_found</p>
	";
	
}
else{
	
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$get_current_user_name - $l_users";
	include("$root/_webdesign/header.php");


	// Header photo
	$current_user_id_mysql = quote_smart($link, $user_id);
	$query = "SELECT cover_photo_id, cover_photo_destination FROM $t_users_cover_photos WHERE cover_photo_user_id='$get_current_user_id' AND cover_photo_is_current='1'";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_cover_photo_id, $get_current_cover_photo_destination) = $row;

	// Profile photo
	$query = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id='$get_current_user_id' AND photo_profile_image='1'";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_photo_id, $get_current_photo_destination) = $row;

	// Thumb
	$inp_new_x = 175;
	$inp_new_y = 175;
	$thumb = "user_" . $get_current_photo_destination . "-" . $inp_new_x . "x" . $inp_new_y . "png";
	if($get_current_photo_id != "" && !(file_exists("$root/_cache/$thumb"))){
		resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_current_user_id/$get_current_photo_destination", "$root/_cache/$thumb");
	}


	$get_current_user_privacy = trim($get_current_user_privacy);
	if($get_current_user_privacy == "public" OR $get_current_user_privacy == ""){
		$can_view_profile = "1";
	}
	else{
		if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
			if($get_current_user_id == "$my_user_id"){
				$can_view_profile = "1";
			}
			else{
				if($get_current_user_privacy == "registered_users"){
					$can_view_profile = "1";
				}
				elseif($get_current_user_privacy == "friends"){
					// Are we friends?
					// Get my user alias, date format, profile image
					$my_user_id = $_SESSION['user_id'];
					$my_user_id_mysql = quote_smart($link, $my_user_id);
					$query = "SELECT user_id, user_alias, user_date_format FROM $t_users WHERE user_id=$my_user_id_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_my_user_id, $get_my_user_alias, $get_my_user_date_format) = $row;
		
					// Are we friends?
					// user_A = Lowest ID
					// user_B = Higher ID

					if($get_current_user_id > $my_user_id){
						$inp_friend_user_id_a_mysql = quote_smart($link, $my_user_id);
						$inp_friend_user_id_b_mysql = quote_smart($link, $get_current_user_id);
					}
					else{
						$inp_friend_user_id_a_mysql = quote_smart($link, $get_current_user_id);
						$inp_friend_user_id_b_mysql = quote_smart($link, $my_user_id);
					}
					$query = "SELECT friend_id FROM $t_users_friends WHERE friend_user_id_a=$inp_friend_user_id_a_mysql AND friend_user_id_b=$inp_friend_user_id_b_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_friend_id) = $row;
					if($get_current_friend_id != ""){
						$can_view_profile = "1";
					}
					else{
						echo"
						<h1>You are not friends</h1>
						";
						$can_view_profile = "0";
					}
				} // friends
			} // not my self
		} // session
		else{
			echo"
			<table>
			 <tr> 
			  <td style=\"padding-right: 6px;\">
				<p><img src=\"_gfx/loading_22.gif\" alt=\"Loading\" /></p>
			  </td>
			  <td>
				<h1>Loading</h1>
			  </td>
			 </tr>
			</table>
			<p>Please log in.</p>
			<meta http-equiv=\"refresh\" content=\"1;url=login.php?l=$l&referer=view_profile.php?user_id=$get_current_user_id\">
			";
		}
	} // public


	// Update numbers
	if(isset($can_view_profile)){

		$inp_ip = $_SERVER['REMOTE_ADDR'];
		$inp_ip = output_html($inp_ip);

		$ip_block_array = explode("\n", $get_current_user_views_ipblock);
		$ip_block_array_size = sizeof($ip_block_array);

		if($ip_block_array_size > 30){
			$ip_block_array_size = 20;
		}
	
		$has_seen_this_before = 0;

		for($x=0;$x<$ip_block_array_size;$x++){
			if($ip_block_array[$x] == "$inp_ip"){
				$has_seen_this_before = 1;
				break;
			}
		}
	
		if($has_seen_this_before == 0){
			$ip_block = $inp_ip . "\n" . $get_current_user_views_ipblock;
			$ip_block_mysql = quote_smart($link, $ip_block);
			$inp_unique_hits = $get_current_user_views + 1;
			$result = mysqli_query($link, "UPDATE $t_users SET user_views=$inp_unique_hits, user_views_ipblock=$ip_block_mysql WHERE user_id='$get_current_user_id'") or die(mysqli_error($link));
		}
	}

	/*- Show user - */
	if(isset($can_view_profile) && $can_view_profile == "1"){
		echo"
	

		<!-- Headline desktop -->
			<div class=\"view_profile_headline_desktop\">
				<h1>$get_current_user_alias</h1>
			</div>
		<!-- //Headline desktop -->

		<div class=\"profile_wrap\">

		<!-- Photo and menu -->
			
			";
			if($action != "view_photo"){
			echo"
			<div class=\"view_profile_left\">
				<!-- Photo -->
					";
				if($get_current_photo_id != ""){
					echo"
					<a href=\"view_profile.php?action=view_photo&amp;user_id=$get_current_user_id&amp;photo_id=$get_current_photo_id&amp;l=$l#photo\"><div class=\"view_profile_image_frame\" id=\"z\" style=\"background-image: url('$root/_uploads/users/images/$get_current_user_id/$get_current_photo_destination')\">
					</div></a>
					"; 
					
				}
				else{
					// If this is me, then clicking on photo will let me upload a new one
					if(isset($_SESSION['user_id']) && $my_user_id == "$get_current_user_id"){
						echo"<a href=\"photo.php?l=$l\"><div class=\"view_profile_image_frame\" style=\"background-image: url('$root/users/_gfx/avatar_blank_175.png')\"></div></a>";
					}
					else{
						echo"<div class=\"view_profile_image_frame\" style=\"background-image: url('$root/users/_gfx/avatar_blank_175.png')\"></div>";
					}
				}
				echo"
				<!-- Photo -->

				<!-- Add friend -->
					";
					if(isset($_SESSION['user_id']) && !(isset($get_current_friend_id)) && $my_user_id != "$get_current_user_id"){
						// Check if I've sendt friend request
						
						$query = "SELECT fr_id FROM $t_users_friends_requests WHERE fr_to_user_id=$user_id_mysql AND fr_from_user_id='$my_user_id'";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_fr_id) = $row;

						if($get_fr_id == ""){
							echo"
							<div class=\"view_profile_add_friend_button\">
								<p>
								<a href=\"view_profile.php?action=add_friend&amp;user_id=$get_current_user_id&amp;photo_id=$get_current_photo_id&amp;l=$l\" class=\"btn_default\" style=\"border: #fff 2px solid;\">$l_add_friend</a>
								</p>
							</div>";
						}
						else{

							echo"
							<div class=\"view_profile_add_friend_button\">
								<p>
								<a href=\"view_profile.php?user_id=$get_current_user_id&amp;l=$l\" class=\"btn_default\" style=\"border: #fff 2px solid;\">$l_invitation_sent</a>
								</p>
							</div>";
						}
					}
					echo"
				<!-- //Add friend -->
	
				<!-- Headline desktop -->
					<div class=\"view_profile_headline_mobile\">
						<h2>$get_current_user_alias</h2>
					</div>
					<div class=\"view_profile_after_headline_mobile\">
					</div>
				<!-- //Headline desktop -->

				<!-- Cool stats -->
					<div class=\"cool_stats\">
					<table>
					<!-- Last seen -->
					 <tr>
					  <td style=\"padding: 8px 8px 0px 0px;\">
						<span>
						<img src=\"_gfx/ic_schedule_black_18dp_1x.png\" alt=\"schedule.png\" />
						</span>
					  </td>
					  <td style=\"padding: 11px 0px 0px 0px;vertical-align: top;\">
						<span class=\"grey_smal\">$l_last_seen<br /></span>
						<span class=\"grey\"><b>";
						include("$root/_admin/_functions/relative_time.php");
						echo relativeTime($get_current_user_last_online_time);
						echo"</b></span>
					  </td>
					 </tr>
					<!-- //Last seen -->

					<!-- Days on -->
					 <tr>
					  <td style=\"padding: 8px 8px 0px 0px;\">
						<span>
						<img src=\"_gfx/ic_date_range_black_18dp_1x.png\" alt=\"date_range.png\" />
						</span>
					  </td>
					  <td style=\"padding: 8px 0px 0px 0px;vertical-align: top;\">
						<span class=\"grey_smal\">$l_registered<br /></span>
						<span class=\"grey\"><b>";
						echo relativeTime($get_current_user_registered_time);
						echo"</b></span>
					  </td>
					 </tr>
					<!-- //Days on -->

					<!-- Profile views -->
					 <tr>
					  <td style=\"padding: 8px 8px 0px 0px;\">
						<span><img src=\"_gfx/ic_timeline_black_18dp_1x.png\" alt=\"ic_timeline_black_18dp_1x.png\" /></span>
					  </td>
					  <td style=\"padding: 8px 0px 0px 0px;vertical-align: top;\">
						<span class=\"grey_smal\">$l_profile_views<br /></span>
						<span class=\"grey\"><b>$get_current_user_views</b></span>
					  </td>
					 </tr>
					<!-- //Profile views -->


					<!-- Points rank -->
					 <tr>
					  <td style=\"padding: 8px 8px 0px 0px;\">
						<span><img src=\"_gfx/ic_school_black_18dp_1x.png\" alt=\"ic_school_black_18dp_1x.png\" /></span>
					  </td>
					  <td style=\"padding: 8px 0px 0px 0px;vertical-align: top;\">
						<span class=\"grey_smal\">$l_status<br /></span>
						<span class=\"grey\"><b>$get_current_user_points_rank</b></span>
					  </td>
					 </tr>
					<!-- //Points rank -->

					<!-- Points -->
					 <tr>
					  <td style=\"padding: 8px 8px 0px 0px;\">
						<span><img src=\"_gfx/ic_loyalty_black_18dp_1x.png\" alt=\"ic_loyalty_black_18dp_1x.png\" /></span>
					  </td>
					  <td style=\"padding: 8px 0px 0px 0px;vertical-align: top;\">
						<span class=\"grey_smal\">$l_points<br /></span>
						<span class=\"grey\"><b>$get_current_user_points</b></span>
					  </td>
					 </tr>
					<!-- //Points -->
					</table>
					</div>
				<!-- //Cool stats -->

			</div>
			";
			} // if($action != "view_photo"){
			echo"
		<!-- //Photo and menu -->

		

		<!-- Right -->
			<div class=\"view_profile_right\">

				<!-- Tabs -->
					<div class=\"view_profile_navigation\">
						<ul>
							<li><a href=\"view_profile.php?user_id=$get_current_user_id&amp;l=$l\" "; if($action == ""){ echo" class=\"active\"";} echo">$l_status</a></li>
							<li><a href=\"view_profile.php?action=friends&amp;user_id=$get_current_user_id&amp;l=$l\" "; if($action == "friends"){ echo" class=\"active\"";} echo">$l_friends</a></li>
							<li><a href=\"view_profile.php?action=images&amp;user_id=$get_current_user_id&amp;l=$l\" "; if($action == "images"){ echo" class=\"active\"";} echo">$l_images</a></li>\n";

							// Headlines
							$query = "SELECT headline_id, headline_title, headline_icon_path_18x18, headline_icon_file_18x18 FROM $t_users_profile_headlines WHERE headline_show_on_profile=1 ORDER BY headline_weight DESC";
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

								echo"							";
								echo"<li><a href=\"view_profile.php?action=headline&amp;user_id=$get_current_user_id&amp;headline_id=$get_headline_id&amp;l=$l\">$get_translation_value</a></li>\n";
							}
							echo"
						</ul>
					</div>
					<div class=\"clear\" style=\"height: 20px;\"></div>
				<!-- //Tabs -->
			";
				if($action == "friends"){
					include("$root/users/view_profile_action_friends.php");
				}
				elseif($action == "add_friend"){
					include("$root/users/view_profile_action_add_friend.php");
				}
				elseif($action == "images"){
					include("$root/users/view_profile_action_images.php");
				}
				elseif($action == "view_photo"){
					include("$root/users/view_profile_action_view_photo.php");
				}
				elseif($action == "headline"){
					include("$root/users/view_profile_action_headline.php");
				}
				else{
					include("$root/users/view_profile_action_home.php");
				}
		echo"
			</div>
		<!-- //Right -->

		</div> <!-- //Profile wrap -->



		";
	} // $can_view_profile


} // user found

echo"
<p>
<a href=\"index.php?l=$l#user$get_current_user_id\"><img src=\"_gfx/go-previous.png\" alt=\"go-previous.png\" /></a>
<a href=\"index.php?l=$l#user$get_current_user_id\">$l_users</a>
</p>
";
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>