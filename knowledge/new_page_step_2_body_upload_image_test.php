<?php 
/**
*
* File: new_page_step_2_body_upload_image_test.php
* Version 1.0
* Date 21:14 29.10.2020
* Copyright (c) 2020 S. A. Ditlefsen
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

/*- Translation ------------------------------------------------------------------------------- */
include("$root/_admin/_translations/site/$l/knowledge/ts_view_page.php");

/*- Variables -------------------------------------------------------------------------------- */
$tabindex = 0;

if(isset($_GET['space_id'])) {
	$space_id = $_GET['space_id'];
	$space_id = stripslashes(strip_tags($space_id));
}
else{
	$space_id = "";
}
$space_id_mysql = quote_smart($link, $space_id);

if(isset($_GET['page_id'])) {
	$page_id = $_GET['page_id'];
	$page_id = stripslashes(strip_tags($page_id));
}
else{
	$page_id = "";
}
$page_id_mysql = quote_smart($link, $page_id);

// Find space
$query = "SELECT space_id, space_title, space_title_clean, space_description, space_text, space_image, space_thumb_32, space_thumb_16, space_is_archived, space_unique_hits, space_unique_hits_ip_block, space_unique_hits_user_id_block, space_created_datetime, space_created_date_saying, space_created_user_id, space_created_user_alias, space_created_user_image, space_updated_datetime, space_updated_date_saying, space_updated_user_id, space_updated_user_alias, space_updated_user_image FROM $t_knowledge_spaces_index WHERE space_id=$space_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_space_id, $get_current_space_title, $get_current_space_title_clean, $get_current_space_description, $get_current_space_text, $get_current_space_image, $get_current_space_thumb_32, $get_current_space_thumb_16, $get_current_space_is_archived, $get_current_space_unique_hits, $get_current_space_unique_hits_ip_block, $get_current_space_unique_hits_user_id_block, $get_current_space_created_datetime, $get_current_space_created_date_saying, $get_current_space_created_user_id, $get_current_space_created_user_alias, $get_current_space_created_user_image, $get_current_space_updated_datetime, $get_current_space_updated_date_saying, $get_current_space_updated_user_id, $get_current_space_updated_user_alias, $get_current_space_updated_user_image) = $row;

if($get_current_space_id == ""){
	// Space not found
	header("HTTP/1.1 500 Server Error");
}
else{
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "Upload";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");


	// Get my user
	if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
		$my_user_id = $_SESSION['user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);

		// Check if I am a member
		$query = "SELECT member_id, member_space_id, member_rank, member_user_id, member_user_alias, member_user_image, member_user_about, member_added_datetime, member_added_date_saying, member_added_by_user_id, member_added_by_user_alias, member_added_by_user_image FROM $t_knowledge_spaces_members WHERE member_space_id=$get_current_space_id AND member_user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_member_id, $get_my_member_space_id, $get_my_member_rank, $get_my_member_user_id, $get_my_member_user_alias, $get_my_member_user_image, $get_my_member_user_about, $get_my_member_added_datetime, $get_my_member_added_date_saying, $get_my_member_added_by_user_id, $get_my_member_added_by_user_alias, $get_my_member_added_by_user_image) = $row;
		if($get_my_member_id == ""){
			// Did I already request membership?
			$query = "SELECT requested_membership_id, requested_membership_date_saying FROM $t_knowledge_spaces_requested_memberships WHERE requested_membership_space_id=$get_current_space_id AND requested_membership_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_requested_membership_id, $get_requested_membership_date_saying) = $row;
			if($get_requested_membership_id == ""){

				// Check my USER rank. If admin or moderator: then add me
				$query = "SELECT user_id, user_email, user_name, user_alias, user_language, user_last_online, user_rank, user_login_tries FROM $t_users WHERE user_id=$my_user_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_language, $get_my_user_last_online, $get_my_user_rank, $get_my_user_login_tries) = $row;
				if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
					// Auto insert
					$query_p = "SELECT photo_id, photo_destination, photo_thumb_40 FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
					$result_p = mysqli_query($link, $query_p);
					$row_p = mysqli_fetch_row($result_p);
					list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_40) = $row_p;

					$inp_my_rank_mysql = quote_smart($link, $get_my_user_rank);
					$inp_my_user_alias_mysql = quote_smart($link, $get_my_user_alias);
					$inp_my_user_email_mysql = quote_smart($link, $get_my_user_email);
					$inp_my_user_image_mysql = quote_smart($link, $get_my_photo_destination);

					$datetime = date("Y-m-d H:i:s");
					$date_saying = date("j M Y");


					mysqli_query($link, "INSERT INTO $t_knowledge_spaces_members
					(member_id, member_space_id, member_rank, member_user_id, member_user_alias, member_user_email, member_user_image, member_user_position, member_user_department, member_user_location, member_user_about, member_added_datetime, member_added_date_saying, member_added_by_user_id, member_added_by_user_alias, member_added_by_user_image) 
					VALUES 
					(NULL, $get_current_space_id, $inp_my_rank_mysql, $get_my_user_id, $inp_my_user_alias_mysql, $inp_my_user_email_mysql, $inp_my_user_image_mysql, '', '', '', '', '$datetime', '$date_saying', '$get_my_user_id', $inp_my_user_alias_mysql, $inp_my_user_image_mysql)")
					or die(mysqli_error($link));
					echo"
					<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Auto inserting...</h1>
					<meta http-equiv=\"refresh\" content=\"1;url=open_space.php?space_id=$get_current_space_id\">
					";
				}
	
				
				header("HTTP/1.1 500 Server Error");
				// echo"<h1>your_not_a_member_of_this_space</h1>";
			}
			else{
				header("HTTP/1.1 500 Server Error");
				// echo"<h1>membership_requests_pending</h1>";
			}
		}
		else{
			// Find page
			$query = "SELECT page_id FROM $t_knowledge_pages_index WHERE page_id=$page_id_mysql AND page_space_id=$get_current_space_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_page_id) = $row;
			if($get_current_page_id == ""){
				// Page not found
				header("HTTP/1.1 500 Server Error");

			}
			else{
				echo"
				Test
				
				<!-- Upload form -->
					<form method=\"POST\" action=\"new_page_step_2_body_upload_image.php?space_id=$get_current_space_id&amp;page_id=$get_current_page_id\" enctype=\"multipart/form-data\">
					<p>
					<input name=\"inp_image\" type=\"file\" tabindex=\"1\" />
					<input type=\"submit\" value=\"Upload\" tabindex=\"2\" />
					</p>
					</form>
				<!-- //Upload form -->
				";
			} // page found
		} // member of space
	} // logged inn
	else{
		// Not logged in
		header("HTTP/1.1 500 Server Error");
	}	
} // space found
?>
