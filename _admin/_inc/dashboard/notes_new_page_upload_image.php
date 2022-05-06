<?php
/**
*
* File: _admin/_inc/notes_new_page_upload_image.php
* Version 1
* Date 14:58 02.04.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */
$t_notes_categories   = $mysqlPrefixSav . "notes_categories";
$t_notes_pages	      = $mysqlPrefixSav . "notes_pages";
$t_notes_pages_images = $mysqlPrefixSav . "notes_pages_images";
$t_notes_pages_files  = $mysqlPrefixSav . "notes_pages_files";

/*- Functions--------------------------------------------------------------------------- */
include("_functions/get_extension.php");

/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['category_id'])) {
	$category_id = $_GET['category_id'];
	$category_id = strip_tags(stripslashes($category_id));
	if(!(is_numeric($category_id))){
		echo"Category id not numeric";
		die;
	}
}
else{
	$category_id = "";
}
$category_id_mysql = quote_smart($link, $category_id);

if(isset($_GET['page_id'])) {
	$page_id = $_GET['page_id'];
	$page_id = strip_tags(stripslashes($page_id));
	if(!(is_numeric($page_id))){
		echo"page id not numeric";
		die;
	}
}
else{
	$page_id = "";
}
$page_id_mysql = quote_smart($link, $page_id);

$query = "SELECT category_id, category_title, category_weight, category_bg_color, category_border_color, category_title_color, category_pages_bg_color, category_pages_bg_color_hover, category_pages_bg_color_active, category_pages_border_color, category_pages_border_color_hover, category_pages_border_color_active, category_pages_title_color, category_pages_title_color_hover, category_pages_title_color_active, category_created_datetime, category_created_by_user_id, category_updated_datetime, category_updated_by_user_id FROM $t_notes_categories ORDER BY category_weight ASC";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_category_id, $get_current_category_title, $get_current_category_weight, $get_current_category_bg_color, $get_current_category_border_color, $get_current_category_title_color, $get_current_category_pages_bg_color, $get_current_category_pages_bg_color_hover, $get_current_category_pages_bg_color_active, $get_current_category_pages_border_color, $get_current_category_pages_border_color_hover, $get_current_category_pages_border_color_active, $get_current_category_pages_title_color, $get_current_category_pages_title_color_hover, $get_current_category_pages_title_color_active, $get_current_category_created_datetime, $get_current_category_created_by_user_id, $get_current_category_updated_datetime, $get_current_category_updated_by_user_id) = $row;
if($get_current_category_id == ""){
	echo"Category not found";
}
else{
	if($process == "1"){
		$datetime = date("Y-m-d H:i:s");


		// Finnes mappen?
		$upload_path = "../_uploads/notes_pages/$page_id";
	
		if(!(is_dir("../_uploads"))){
			mkdir("../_uploads");
		}
		if(!(is_dir("../_uploads/notes_pages"))){
			mkdir("../_uploads/notes_pages");
		}
		if(!(is_dir("../_uploads/notes_pages/$page_id"))){
			mkdir("../_uploads/notes_pages/$page_id");
		}



		// Image folder
		$imageFolder = "../_uploads/notes_pages/$page_id/";


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

			// Get my user
			$my_user_id = $_SESSION['user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);

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

			$inp_title = $temp['name'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_file_path = "../_uploads/notes_pages/$page_id";
			$inp_file_path_mysql = quote_smart($link, $inp_file_path);

			$datetime = date("Y-m-d H:i:s");
			$date_saying = date("j M Y");

			$inp_my_alias_mysql = quote_smart($link, $get_my_user_alias);
			$inp_my_email_mysql = quote_smart($link, $get_my_user_email);
			$inp_my_image_mysql = quote_smart($link, $get_my_photo_destination);

			$inp_file_name = output_html($new_name);
			$inp_file_name_mysql = quote_smart($link, $inp_file_name);

			// IP
			$my_ip = "";
			$my_ip = $_SERVER['REMOTE_ADDR'];
			$my_ip = output_html($my_ip);
			$my_ip_mysql = quote_smart($link, $my_ip);

			$my_hostname = "";
			if($configSiteUseGethostbyaddrSav == "1"){
				$my_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
			}
			$my_hostname = output_html($my_hostname);
			$my_hostname_mysql = quote_smart($link, $my_hostname);

			$my_user_agent = "";
			$my_user_agent = $_SERVER['HTTP_USER_AGENT'];
			$my_user_agent = output_html($my_user_agent);
			$my_user_agent_mysql = quote_smart($link, $my_user_agent);

			mysqli_query($link, "INSERT INTO $t_notes_pages_images
			(image_id, image_category_id, image_page_id, image_title, image_path, 
			image_file, image_uploaded_user_id, image_uploaded_ip, image_uploaded_datetime) 
			VALUES 
			(NULL, $get_current_category_id, $page_id_mysql,$inp_title_mysql, $inp_file_path_mysql, 
			$inp_file_name_mysql, $get_my_user_id, $my_ip_mysql, '$datetime')")
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
			exit;
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
	} // process == 1
} // found category


?>