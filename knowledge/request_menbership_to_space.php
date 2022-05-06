<?php 
/**
*
* File: howto/request_menbership_to_space.php
* Version 1.0
* Date 14:55 30.06.2019
* Copyright (c) 2019 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "1";
$pageAuthorUserIdSav  = "1";

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

/*- Variables -------------------------------------------------------------------------------- */
$tabindex = 0;

if (isset($_GET['space_id'])) {
	$space_id = $_GET['space_id'];
	$space_id = stripslashes(strip_tags($space_id));
}
else{
	$space_id = "";
}
$space_id_mysql = quote_smart($link, $space_id);

if($space_id == ""){
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$l_request_menbership_to_space";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");

	echo"
	<h1>$l_request_menbership_to_space</h1>
	
	<!-- Spaces -->
		<h2>$l_select_space</h2>
	
		<div class=\"vertical\">
			<ul>
			";
			$spaces_counter = 0;
			$query = "SELECT space_id, space_title FROM $t_knowledge_spaces_index WHERE space_is_archived='0' ORDER BY space_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_space_id, $get_space_title) = $row;
				echo"			";
				echo"<li><a href=\"request_menbership_to_space.php?space_id=$get_space_id&amp;l=$l\">$get_space_title</a></li>\n";

				$spaces_counter++;
			}
			echo"
			</ul>
		</div>
		";
		if($spaces_counter == 1){
			echo"
			<meta http-equiv=\"refresh\" content=\"0;url=request_menbership_to_space.php?space_id=$get_space_id&amp;l=$l\">
			";
		}
		echo"
	<!-- //Spaces -->
	";
}
else{
	// Find space
	$query = "SELECT space_id, space_title, space_title_clean, space_description, space_text, space_image, space_thumb_32, space_thumb_16, space_is_archived, space_unique_hits, space_unique_hits_ip_block, space_unique_hits_user_id_block, space_created_datetime, space_created_date_saying, space_created_user_id, space_created_user_alias, space_created_user_image, space_updated_datetime, space_updated_date_saying, space_updated_user_id, space_updated_user_alias, space_updated_user_image FROM $t_knowledge_spaces_index WHERE space_id=$space_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_space_id, $get_current_space_title, $get_current_space_title_clean, $get_current_space_description, $get_current_space_text, $get_current_space_image, $get_current_space_thumb_32, $get_current_space_thumb_16, $get_current_space_is_archived, $get_current_space_unique_hits, $get_current_space_unique_hits_ip_block, $get_current_space_unique_hits_user_id_block, $get_current_space_created_datetime, $get_current_space_created_date_saying, $get_current_space_created_user_id, $get_current_space_created_user_alias, $get_current_space_created_user_image, $get_current_space_updated_datetime, $get_current_space_updated_date_saying, $get_current_space_updated_user_id, $get_current_space_updated_user_alias, $get_current_space_updated_user_image) = $row;

	if($get_current_space_id == ""){
		/*- Headers ---------------------------------------------------------------------------------- */
		$website_title = "404 server error";
		if(file_exists("./favicon.ico")){ $root = "."; }
		elseif(file_exists("../favicon.ico")){ $root = ".."; }
		elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
		elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
		include("$root/_webdesign/header.php");

		echo"
		<h1>Server error 404</h1>

		<p>Space not found.</p>
		";
	}
	else{
		/*- Headers ---------------------------------------------------------------------------------- */
		$website_title = "$get_current_space_title - $l_membership_requests_to_space";
		if(file_exists("./favicon.ico")){ $root = "."; }
		elseif(file_exists("../favicon.ico")){ $root = ".."; }
		elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
		elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
		include("$root/_webdesign/header.php");

		// Check for user
		if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
			$my_user_id = $_SESSION['user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);

			// Check if I am member
			$query = "SELECT member_id, member_space_id, member_rank, member_user_id, member_user_alias, member_user_image, member_user_about, member_added_datetime, member_added_date_saying, member_added_by_user_id, member_added_by_user_alias, member_added_by_user_image FROM $t_knowledge_spaces_members WHERE member_space_id=$get_current_space_id AND member_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_member_id, $get_member_space_id, $get_member_rank, $get_member_user_id, $get_member_user_alias, $get_member_user_image, $get_member_user_about, $get_member_added_datetime, $get_member_added_date_saying, $get_member_added_by_user_id, $get_member_added_by_user_alias, $get_member_added_by_user_image) = $row;
			if($get_member_id == ""){

				// Did I request a membership from before?
				$query = "SELECT requested_membership_id FROM $t_knowledge_spaces_requested_memberships WHERE requested_membership_space_id=$space_id_mysql AND requested_membership_user_id=$my_user_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_requested_membership_id) = $row;
				if($get_requested_membership_id == ""){

					if($process == "1"){
					$inp_position = $_POST['inp_position'];
					$inp_position = output_html($inp_position);
					$inp_position_mysql = quote_smart($link, $inp_position);

					$inp_department = $_POST['inp_department'];
					$inp_department = output_html($inp_department);
					$inp_department_mysql = quote_smart($link, $inp_department);

					$inp_location = $_POST['inp_location'];
					$inp_location = output_html($inp_location);
					$inp_location_mysql = quote_smart($link, $inp_location);

					$inp_about = $_POST['inp_about'];
					$inp_about = output_html($inp_about);
					$inp_about_mysql = quote_smart($link, $inp_about);

					// Dates
					$datetime = date("Y-m-d H:i:s");
					$date_saying = date("j M Y");


					// Me
					$query = "SELECT user_id, user_email, user_name, user_alias, user_language, user_last_online, user_rank, user_login_tries FROM $t_users WHERE user_id=$my_user_id_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_language, $get_my_user_last_online, $get_my_user_rank, $get_my_user_login_tries) = $row;
					
					// Get my photo
					$query = "SELECT photo_id, photo_destination, photo_thumb_40 FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_40) = $row;


					$inp_my_user_alias_mysql = quote_smart($link, $get_my_user_alias);
					$inp_my_user_email_mysql = quote_smart($link, $get_my_user_email);
					$inp_my_user_image_mysql = quote_smart($link, $get_my_photo_destination);


					// Insert request
					mysqli_query($link, "INSERT INTO $t_knowledge_spaces_requested_memberships 
					(requested_membership_id, requested_membership_space_id, requested_membership_user_id, requested_membership_user_alias, requested_membership_user_email, requested_membership_user_image, requested_membership_user_position, requested_membership_user_department, requested_membership_user_location, requested_membership_user_about, requested_membership_datetime, requested_membership_date_saying) 
					VALUES 
					(NULL,  $get_current_space_id, $get_my_user_id, $inp_my_user_alias_mysql, $inp_my_user_email_mysql, $inp_my_user_image_mysql, $inp_position_mysql, $inp_department_mysql, $inp_location_mysql, $inp_about_mysql, '$datetime', '$date_saying')")
					or die(mysqli_error($link));


					// Email to admins
					$subject = "Access request to space $get_current_space_title by $get_my_user_alias";
					$message = "Hello,\n\nThe user $get_my_user_alias has requested access to the space $get_current_space_title.\n";
					$message = $message . "View requests: $configSiteURLSav/knowledge/edit_space_members.php?space_id=$get_current_space_id\n";
					$message = $message . "--\n";
					$message = $message . "Best regards\n";
					$message = $message . "$configFromNameSav\n";
					$message = $message . "$configSiteURLSav\n";
					$headers = "From: $configFromEmailSav" . "\r\n" .
					    'X-Mailer: PHP/' . phpversion();


					$query = "SELECT member_id, member_space_id, member_rank, member_user_id, member_user_alias, member_user_email, member_user_image, member_user_about, member_added_datetime, member_added_date_saying, member_added_by_user_id, member_added_by_user_alias, member_added_by_user_image FROM $t_knowledge_spaces_members WHERE member_space_id=$get_current_space_id AND (member_rank='admin' OR member_rank='moderator')";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_member_id, $get_member_space_id, $get_member_rank, $get_member_user_id, $get_member_user_alias, $get_member_user_email, $get_member_user_image, $get_member_user_about, $get_member_added_datetime, $get_member_added_date_saying, $get_member_added_by_user_id, $get_member_added_by_user_alias, $get_member_added_by_user_image) = $row;
						
						mail($get_member_user_email, $subject, $message, $headers);
					}
					
						$url = "open_space.php?space_id=$get_current_space_id&l=$l&ft=success&fm=request_sent";
						header("Location: $url");
						exit;
					}
					echo"
					<h1>$l_membership_requests_to_space</h1>

					<!-- Focus -->
					<script>
					\$(document).ready(function(){
						\$('[name=\"inp_position\"]').focus();
					});
					</script>
					<!-- //Focus -->
				
					<!-- Form -->
					<form method=\"POST\" action=\"request_menbership_to_space.php?space_id=$get_current_space_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

					<p><b>$l_your_position</b><br />
					<input type=\"text\" name=\"inp_position\" value=\"\" size=\"25\" style=\"width: 100%;\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>

					<p><b>$l_your_department</b><br />
					<input type=\"text\" name=\"inp_department\" value=\"\" size=\"25\" style=\"width: 100%;\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>

					<p><b>$l_your_location</b><br />
					<input type=\"text\" name=\"inp_location\" value=\"\" size=\"25\" style=\"width: 100%;\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>

					<p><b>$l_about_you</b><br />
					<textarea name=\"inp_about\" rows=\"3\" cols=\"40\" style=\"width: 100%;min-height:50px\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea>
		
					</p>

					<p>
					<input type=\"submit\" value=\"$l_send_request\" class=\"btn_default\" />
					</p>
					<!-- //Form -->
					";
				}
				else{
					echo"
					<h1>$l_membership_request_already_sent</h1>

					<p>
					<a href=\"open_space.php?space_id=$get_current_space_id&amp;l=$l\">$get_current_space_title</a>
					</p>";
				}
			}			
			else{
				echo"
				<h1>You already are a member</h1>
				";
			}
		} // logged in
		else{
			echo"
			<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Please log in...</h1>
		
		
			<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=$root/knowledge/request_menbership_to_space.php?space_id=$space_id\">
	
			";
		} // not logged in
	} // space found
} // space != ""

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/$webdesignSav/footer.php");
?>