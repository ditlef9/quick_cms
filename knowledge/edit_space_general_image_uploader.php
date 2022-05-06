<?php
/**
*
* File: image_uploader.php
* About: This file retrives images and uploads them to the uploads folder.
* Version 1.0.0
* Date 13:52 29.06.2019
* Copyright (c) 2019 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Website config --------------------------------------------------------------------------- */
$root = "..";
include("$root/_admin/website_config.php");


/*- Functions -------------------------------------------------------------------------------- */
// Get extention
function getExtension($str) {
	$i = strrpos($str,".");
	if (!$i) { return ""; } 
	$l = strlen($str) - $i;
	$ext = substr($str,$i+1,$l);
	return $ext;
}



/*- Get space and page ----------------------------------------------------------------------- */
if(isset($_SESSION['space_id']) && isset($_SESSION['user_id']) && isset($_SESSION['security'])){


	$space_id = $_SESSION['space_id'];
	$space_id = output_html($space_id);
	$space_id = stripslashes(strip_tags($space_id));
	$space_id_mysql = quote_smart($link, $space_id);

	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id = stripslashes(strip_tags($my_user_id));
	$my_user_id_mysql = quote_smart($link, $my_user_id);

	if(isset($_SESSION['page_id'])){
		$page_id = $_SESSION['page_id'];
	}
	else{
		$page_id = 0;
	}
	$page_id = output_html($page_id);
	$page_id = stripslashes(strip_tags($page_id));
	$page_id_mysql = quote_smart($link, $page_id);

	// Find space
	$query = "SELECT space_id, space_title, space_title_clean, space_description, space_image, space_is_archived, space_unique_hits, space_unique_hits_ip_block, space_unique_hits_user_id_block, space_created_datetime, space_created_date_saying, space_created_user_id, space_created_user_alias, space_created_user_image, space_updated_datetime, space_updated_date_saying, space_updated_user_id, space_updated_user_alias, space_updated_user_image FROM $t_knowledge_spaces_index WHERE space_id=$space_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_space_id, $get_current_space_title, $get_current_space_title_clean, $get_current_space_description, $get_current_space_image, $get_current_space_is_archived, $get_current_space_unique_hits, $get_current_space_unique_hits_ip_block, $get_current_space_unique_hits_user_id_block, $get_current_space_created_datetime, $get_current_space_created_date_saying, $get_current_space_created_user_id, $get_current_space_created_user_alias, $get_current_space_created_user_image, $get_current_space_updated_datetime, $get_current_space_updated_date_saying, $get_current_space_updated_user_id, $get_current_space_updated_user_alias, $get_current_space_updated_user_image) = $row;
	
	if($get_current_space_id == ""){
		echo"Error space not found";
		die;
	}
	

	// Find me member
	$query = "SELECT member_id, member_space_id, member_rank, member_user_id, member_user_alias, member_user_image, member_user_about, member_added_datetime, member_added_date_saying, member_added_by_user_id, member_added_by_user_alias, member_added_by_user_image FROM $t_knowledge_spaces_members WHERE member_space_id=$space_id_mysql AND member_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_member_id, $get_member_space_id, $get_member_rank, $get_member_user_id, $get_member_user_alias, $get_member_user_image, $get_member_user_about, $get_member_added_datetime, $get_member_added_date_saying, $get_member_added_by_user_id, $get_member_added_by_user_alias, $get_member_added_by_user_image) = $row;
	if($get_member_id == ""){
		echo"Error member not found";
		die;
	}	
	
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

	if(!is_dir("$root/_uploads/knowledge/space_$get_current_space_id/page_0")){
		mkdir("$root/_uploads/knowledge/space_$get_current_space_id/page_0");
	}

	// Start image upload
	if($_SERVER["REQUEST_METHOD"] == "POST" OR $_SERVER["REQUEST_METHOD"] == "GET") {
		foreach ($_FILES as $file) {
		
			$image    = $file['name'];
			$tmp_name = $file['tmp_name'];
			$size     = $file['size'];
				
			$filename = stripslashes($file['name']);
			$extension = getExtension($filename);
			$extension = strtolower($extension);

			if($image){
				if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
					echo"unknown_file_format";
					exit;
				}
				else{
					$size= filesize($file['tmp_name']);
					if($extension=="jpg" || $extension=="jpeg" ){
						ini_set ('gd.jpeg_ignore_warning', 1);
						error_reporting(0);
						$uploadedfile = $file['tmp_name'];
						$src = imagecreatefromjpeg($uploadedfile);
					}
					elseif($extension=="png"){
						$uploadedfile = $file['tmp_name'];
						$src = @imagecreatefrompng($uploadedfile);
					}
					else{
						$src = @imagecreatefromgif($uploadedfile);
					}
 
					list($width,$height) = @getimagesize($uploadedfile);
					if($width == "" OR $height == ""){
						echo"photo_could_not_be_uploaded_please_check_file_size";
						exit;
					}
					else{
						// Insert into MySQL
						$inp_type = "image";
						$inp_type_mysql = quote_smart($link, $inp_type);

						$inp_ext = "$extension";
						$inp_ext = output_html($inp_ext);
						$inp_ext_mysql = quote_smart($link, $inp_ext);

						$inp_title = output_html($image);
						$inp_title_mysql = quote_smart($link, $inp_title);

						$inp_file_path = "_uploads/knowledge/space_$get_current_space_id/page_0";
						$inp_file_path_mysql = quote_smart($link, $inp_file_path);

						$datetime = date("Y-m-d H:i:s");
						$date_saying = date("j M Y");

						$inp_my_alias_mysql = quote_smart($link, $get_my_user_alias);
						$inp_my_email_mysql = quote_smart($link, $get_my_user_email);
						$inp_my_image_mysql = quote_smart($link, $get_my_photo_destination);

						// IP
						$my_ip = $_SERVER['REMOTE_ADDR'];
						$my_ip = output_html($my_ip);
						$my_ip_mysql = quote_smart($link, $my_ip);

						$my_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
						$my_hostname = output_html($my_hostname);
						$my_hostname_mysql = quote_smart($link, $my_hostname);

						$my_user_agent = $_SERVER['HTTP_USER_AGENT'];
						$my_user_agent = output_html($my_user_agent);
						$my_user_agent_mysql = quote_smart($link, $my_user_agent);


						mysqli_query($link, "INSERT INTO $t_knowledge_pages_media
						(media_id, media_space_id, media_page_id, media_type, media_ext, media_version, media_title, media_file_path, media_unique_hits, media_created_datetime, media_created_date_saying, media_created_by_user_id, media_created_by_user_alias, media_created_by_user_email, media_created_by_user_image_file, media_created_by_user_ip, media_created_by_user_hostname, media_created_by_user_agent, media_updated_datetime, media_updated_date_saying, media_updated_by_user_id, media_updated_by_user_alias, media_updated_by_user_email, media_updated_by_user_image_file, media_updated_by_user_ip, media_updated_by_user_hostname, media_updated_by_user_agent) 
						VALUES 
						(NULL, $get_current_space_id, 0, $inp_type_mysql, $inp_ext_mysql, '1', $inp_title_mysql, $inp_file_path_mysql, 0, '$datetime', '$date_saying', $my_user_id_mysql, $inp_my_alias_mysql, $inp_my_email_mysql, $inp_my_image_mysql, $my_ip_mysql, $my_hostname_mysql, $my_user_agent_mysql, '$datetime', '$date_saying', $my_user_id_mysql, $inp_my_alias_mysql, $inp_my_email_mysql, $inp_my_image_mysql, $my_ip_mysql, $my_hostname_mysql, $my_user_agent_mysql)")
						or die(mysqli_error($link));

						// Get ID
						$q = "SELECT media_id FROM $t_knowledge_pages_media WHERE media_created_datetime='$datetime' AND media_created_by_user_id=$my_user_id_mysql";
						$r = mysqli_query($link, $q);
						$rowb = mysqli_fetch_row($r);
						list($get_current_media_id) = $rowb;
						

						// Move uploaded image
						$inp_file_name = $get_current_media_id . "." . $extension;
						$inp_file_name_mysql = quote_smart($link, $inp_file_name);

						$inp_file_thumb_a = $get_current_media_id . "_thumb_800." . $extension;
						$inp_file_thumb_a_mysql = quote_smart($link, $inp_file_thumb_a);

						$inp_file_thumb_b = $get_current_media_id . "_thumb_100." . $extension;
						$inp_file_thumb_b_mysql = quote_smart($link, $inp_file_thumb_a);

						$result = mysqli_query($link, "UPDATE $t_knowledge_pages_media SET
									media_file_name=$inp_file_name_mysql,
									media_file_thumb_800=$inp_file_thumb_a_mysql,
									media_file_thumb_100=$inp_file_thumb_b_mysql
									 WHERE media_id=$get_current_media_id");

						// Resize if very large image
						if($width > 971){
							$newwidth=970;
						}
						else{
							$newwidth=$width;
						}
						$newheight=round(($height/$width)*$newwidth, 0);
						$tmp_org =imagecreatetruecolor($newwidth,$newheight);

						imagecopyresampled($tmp_org,$src,0,0,0,0,$newwidth,$newheight, $width,$height);



						$datetime = date("ymdhis");
						$filename = "$root/$inp_file_path/$inp_file_name";

						if($extension=="jpg" || $extension=="jpeg" ){
							imagejpeg($tmp_org,$filename,100);
						}
						elseif($extension=="png"){
							imagepng($tmp_org,$filename);
						}
						else{
							imagegif($tmp_org,$filename);
						}

						imagedestroy($tmp_org);


						echo"{\"data\":{\"id\":\"Gq5J4AA\",\"title\":null,\"description\":null,\"datetime\":$datetime,\"type\":\"image\/jpeg\",\"animated\":false,\"width\":$width,\"height\":$height,\"size\":$size,\"views\":0,\"bandwidth\":0,\"vote\":null,\"favorite\":false,\"nsfw\":null,\"section\":null,\"account_url\":null,\"account_id\":0,\"is_ad\":false,\"in_most_viral\":false,\"has_sound\":false,\"tags\":[],\"ad_type\":0,\"ad_url\":\"\",\"edited\":\"0\",\"in_gallery\":false,\"deletehash\":\"arX9Pn8m7oHkEAR\",\"name\":\"\",\"link\":\"..\/_uploads\/knowledge\/space_$get_current_space_id\/page_0\/$inp_file_name\"},\"success\":true,\"status\":200}";
						exit;
						
					}  // if($width == "" OR $height == ""){
				}
			} // if($image){
			else{
				switch ($_FILES['inp_image']['error']) {
					case UPLOAD_ERR_OK:
         					echo"photo_unknown_error";
						exit;
					case UPLOAD_ERR_NO_FILE:
           					echo"no_file_selected";
						exit;
					case UPLOAD_ERR_INI_SIZE:
           					echo"photo_exceeds_filesize";
						exit;
					case UPLOAD_ERR_FORM_SIZE:
           					echo"photo_exceeds_filesize_form";
						exit;
					default:
           					echo"unknown_upload_error";
						exit;

				}
			}
		} // for each file
	} // if($_SERVER["REQUEST_METHOD"] == "POST") {
	else{
		echo"No image sent to server";
		exit;
	}
}
else{
	echo"Missing variables";
}
?>