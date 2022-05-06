<?php 
/**
*
* File: new_page_step_2_body_upload_image.php
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
	$process = 1;
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
				// Create dir
				if(!is_dir("$root/_uploads")){
					mkdir("$root/_uploads");
				}
				if(!is_dir("$root/_uploads/knowledge")){
					mkdir("$root/_uploads/knowledge");
				}
				if(!is_dir("$root/_uploads/knowledge/space_$get_current_space_id")){
					mkdir("$root/_uploads/knowledge/space_$get_current_space_id");
				}
				if(!is_dir("$root/_uploads/knowledge/space_$get_current_space_id/page_$get_current_page_id")){
					mkdir("$root/_uploads/knowledge/space_$get_current_space_id/page_$get_current_page_id");
				}



				// Image folder
				$imageFolder = "$root/_uploads/knowledge/space_$get_current_space_id/page_$get_current_page_id/";

				reset ($_FILES);
				$temp = current($_FILES);
				if (is_uploaded_file($temp['tmp_name'])){
					if (isset($_SERVER['HTTP_ORIGIN'])) {
						// same-origin requests won't set an origin. If the origin is set, it must be valid.

					}


					// Sanitize input
					if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
						header("HTTP/1.1 400 Invalid file name.");
						return;
					}

					// Verify extension
					if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
						header("HTTP/1.1 400 Invalid extension.");
						return;
					}


					list($width,$height) = @getimagesize($temp['tmp_name']);
					if($width == "" OR $height == ""){
						header("HTTP/1.1 400 Invalid extension.");
						return;
					}

					// Get extension
					$inp_ext = get_extension($temp['name']);
					$inp_ext = output_html($inp_ext);
					$inp_ext_mysql = quote_smart($link, $inp_ext);

				
					// New name
					$name = $temp['name'];
					$name = str_replace(".$inp_ext", "", $name);
					$uniqid = uniqid();
					$new_name = $name . "_" . $uniqid . "." . $inp_ext;

					// Accept upload if there was no origin, or if it is an accepted origin
					$filetowrite = $imageFolder . $new_name;


					// Move it
					move_uploaded_file($temp['tmp_name'], $filetowrite);



					// Me
					$query = "SELECT user_id, user_email, user_name, user_alias, user_date_format FROM $t_users WHERE user_id=$my_user_id_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_date_format) = $row;



					// Get my profile image
					$q = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id=$my_user_id_mysql AND photo_profile_image='1'";
					$r = mysqli_query($link, $q);
					$rowb = mysqli_fetch_row($r);
					list($get_my_photo_id, $get_my_photo_destination) = $rowb;


					$inp_type = "image";
					$inp_type_mysql = quote_smart($link, $inp_type);


					$inp_title = $temp['name'];
					$inp_title = output_html($inp_title);
					$inp_title_mysql = quote_smart($link, $inp_title);

					$inp_file_path = "_uploads/knowledge/space_$get_current_space_id/page_$get_current_page_id";
					$inp_file_path_mysql = quote_smart($link, $inp_file_path);

					$datetime = date("Y-m-d H:i:s");
					$date_saying = date("j M Y");

					$inp_my_alias_mysql = quote_smart($link, $get_my_user_alias);
					$inp_my_email_mysql = quote_smart($link, $get_my_user_email);
					$inp_my_image_mysql = quote_smart($link, $get_my_photo_destination);

					$inp_file_name = output_html($new_name);
					$inp_file_name_mysql = quote_smart($link, $inp_file_name);

					$inp_file_thumb_a = $name . "_" . $uniqid . "_thumb_800" . "." . $inp_ext;
					$inp_file_thumb_a_mysql = quote_smart($link, $inp_file_thumb_a);

					$inp_file_thumb_b = $name . "_" . $uniqid . "_thumb_100" . "." . $inp_ext;
					$inp_file_thumb_b_mysql = quote_smart($link, $inp_file_thumb_b);


					// IP
					$my_ip = "";
					$my_ip = $_SERVER['REMOTE_ADDR'];
					$my_ip = output_html($inp_ip);
					$my_ip_mysql = quote_smart($link, $inp_ip);

					$my_hostname = "";
					$my_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
					$my_hostname = output_html($my_hostname);
					$my_hostname_mysql = quote_smart($link, $my_hostname);

					$my_user_agent = "";
					if($configSiteUseGethostbyaddrSav == "1"){
						$my_user_agent = $_SERVER['HTTP_USER_AGENT'];
					}
					$my_user_agent = output_html($my_user_agent);
					$my_user_agent_mysql = quote_smart($link, $my_user_agent);


					mysqli_query($link, "INSERT INTO $t_knowledge_pages_media
					(media_id, media_space_id, media_page_id, media_type, media_ext, media_version, media_title, media_file_path, media_file_name, media_file_thumb_800, media_file_thumb_100, media_unique_hits, media_created_datetime, media_created_date_saying, media_created_by_user_id, media_created_by_user_alias, media_created_by_user_email, media_created_by_user_image_file, media_created_by_user_ip, media_created_by_user_hostname, media_created_by_user_agent, media_updated_datetime, media_updated_date_saying, media_updated_by_user_id, media_updated_by_user_alias, media_updated_by_user_email, media_updated_by_user_image_file, media_updated_by_user_ip, media_updated_by_user_hostname, media_updated_by_user_agent) 
					VALUES 
					(NULL, $get_current_space_id, $get_current_page_id, $inp_type_mysql, $inp_ext_mysql, '1', $inp_title_mysql, $inp_file_path_mysql, $inp_file_name_mysql, $inp_file_thumb_a_mysql, $inp_file_thumb_b_mysql, 0, '$datetime', '$date_saying', $my_user_id_mysql, $inp_my_alias_mysql, $inp_my_email_mysql, $inp_my_image_mysql, $my_ip_mysql, $my_hostname_mysql, $my_user_agent_mysql, '$datetime', '$date_saying', $my_user_id_mysql, $inp_my_alias_mysql, $inp_my_email_mysql, $inp_my_image_mysql, $my_ip_mysql, $my_hostname_mysql, $my_user_agent_mysql)")
					or die(mysqli_error($link));

					// Resize image if it is over 1024 in witdth
					if($width > 1024){
						$newwidth=1024;
						$newheight=($height/$width)*$newwidth; // 667
						resize_crop_image($newwidth, $newheight, $filetowrite, $filetowrite);
					}


					// Respond to the successful upload with JSON.
					// Use a location key to specify the path to the saved image resource.
					// { location : '/your/uploaded/image/file'}
					echo json_encode(array('location' => $filetowrite));

				} 
				else {
					// Notify editor that the upload failed
					switch ($_FILES['inp_image']['error']) {
						case UPLOAD_ERR_OK:
							$fm = "photo_unknown_error";
							break;
						case UPLOAD_ERR_NO_FILE:
       							$fm = "no_file_selected";
							break;
						case UPLOAD_ERR_INI_SIZE:
           						$fm = "photo_exceeds_filesize";
							break;
						case UPLOAD_ERR_FORM_SIZE:
           						$fm = "photo_exceeds_filesize_form";
							break;
						default:
           						$fm = "unknown_upload_error";
							break;
					}
					header("HTTP/1.1 500 Server Error $fm");
				}
			} // page found
		} // member of space
	} // logged inn
	else{
		// Not logged in
		header("HTTP/1.1 500 Server Error");
	}	
} // space found
?>
