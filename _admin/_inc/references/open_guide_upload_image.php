<?php
/**
*
* File: _admin/_inc/references/open_guide_upload_image.php
* Version 
* Date 14:12 03.04.2021
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
$t_references_title_translations 	= $mysqlPrefixSav . "references_title_translations";
$t_references_categories_main	 	= $mysqlPrefixSav . "references_categories_main";
$t_references_categories_sub 		= $mysqlPrefixSav . "references_categories_sub";
$t_references_index		 	= $mysqlPrefixSav . "references_index";
$t_references_index_groups	 	= $mysqlPrefixSav . "references_index_groups";
$t_references_index_groups_images	= $mysqlPrefixSav . "references_index_groups_images";
$t_references_index_guides	 	= $mysqlPrefixSav . "references_index_guides";
$t_references_index_guides_images	= $mysqlPrefixSav . "references_index_guides_images";



/*- Functions ------------------------------------------------------------------------ */
include("_functions/get_extension.php");


/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;
if(isset($_GET['guide_id'])){
	$guide_id = $_GET['guide_id'];
	$guide_id = strip_tags(stripslashes($guide_id));
	if(!(is_numeric($guide_id))){
		echo"guide_id not numeric";
		die;
	}
}
else{
	$guide_id = "";
}
$guide_id_mysql = quote_smart($link, $guide_id);


$query = "SELECT guide_id, guide_number, guide_title, guide_title_clean, guide_title_short, guide_title_length, guide_short_description, guide_content, guide_group_id, guide_group_title, guide_reference_id, guide_reference_title, guide_read_times, guide_read_ipblock, guide_created, guide_updated, guide_updated_formatted, guide_last_read, guide_last_read_formatted, guide_comments FROM $t_references_index_guides WHERE guide_id=$guide_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_guide_id, $get_current_guide_number, $get_current_guide_title, $get_current_guide_title_clean, $get_current_guide_title_short, $get_current_guide_title_length, $get_current_guide_short_description, $get_current_guide_content, $get_current_guide_group_id, $get_current_guide_group_title, $get_current_guide_reference_id, $get_current_guide_reference_title, $get_current_guide_read_times, $get_current_guide_read_ipblock, $get_current_guide_created, $get_current_guide_updated, $get_current_guide_updated_formatted, $get_current_guide_last_read, $get_current_guide_last_read_formatted, $get_current_guide_comments) = $row;

if($get_current_guide_id == ""){
	echo"<p>Server error 404.</p>";
}
else{
	// Reference
	$query = "SELECT reference_id, reference_title, reference_title_clean, reference_is_active, reference_front_page_intro, reference_description, reference_language, reference_main_category_id, reference_main_category_title, reference_sub_category_id, reference_sub_category_title, reference_image_file, reference_image_thumb, reference_icon_16, reference_icon_32, reference_icon_48, reference_icon_64, reference_icon_96, reference_icon_260, reference_groups_count, reference_guides_count, reference_read_times, reference_read_times_ip_block, reference_created, reference_updated FROM $t_references_index WHERE reference_id=$get_current_guide_reference_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_reference_id, $get_current_reference_title, $get_current_reference_title_clean, $get_current_reference_is_active, $get_current_reference_front_page_intro, $get_current_reference_description, $get_current_reference_language, $get_current_reference_main_category_id, $get_current_reference_main_category_title, $get_current_reference_sub_category_id, $get_current_reference_sub_category_title, $get_current_reference_image_file, $get_current_reference_image_thumb, $get_current_reference_icon_16, $get_current_reference_icon_32, $get_current_reference_icon_48, $get_current_reference_icon_64, $get_current_reference_icon_96, $get_current_reference_icon_260, $get_current_reference_groups_count, $get_current_reference_guides_count, $get_current_reference_read_times, $get_current_reference_read_times_ip_block, $get_current_reference_created, $get_current_reference_updated) = $row;


	// Find group
	$query = "SELECT group_id, group_title, group_title_clean, group_title_short, group_title_length, group_number, group_content, group_reference_id, group_reference_title, group_read_times, group_read_times_ip_block, group_created_datetime, group_updated_datetime, group_updated_formatted, group_last_read, group_last_read_formatted FROM $t_references_index_groups WHERE group_id=$get_current_guide_group_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_group_id, $get_current_group_title, $get_current_group_title_clean, $get_current_group_title_short, $get_current_group_title_length, $get_current_group_number, $get_current_group_content, $get_current_group_reference_id, $get_current_group_reference_title, $get_current_group_read_times, $get_current_group_read_times_ip_block, $get_current_group_created_datetime, $get_current_group_updated_datetime, $get_current_group_updated_formatted, $get_current_group_last_read, $get_current_group_last_read_formatted) = $row;

	// Find category
	$query = "SELECT main_category_id, main_category_title, main_category_title_clean, main_category_description, main_category_language, main_category_created, main_category_updated FROM $t_references_categories_main WHERE main_category_id=$get_current_reference_main_category_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_title_clean, $get_current_main_category_description, $get_current_main_category_language, $get_current_main_category_created, $get_current_main_category_updated) = $row;

	$query = "SELECT sub_category_id, sub_category_title, sub_category_title_clean, sub_category_description, sub_category_main_category_id, sub_category_main_category_title, sub_category_language, sub_category_created, sub_category_updated FROM $t_references_categories_sub WHERE sub_category_id=$get_current_reference_sub_category_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_sub_category_id, $get_current_sub_category_title, $get_current_sub_category_title_clean, $get_current_sub_category_description, $get_current_sub_category_main_category_id, $get_current_sub_category_main_category_title, $get_current_sub_category_language, $get_current_sub_category_created, $get_current_sub_category_updated) = $row;

	if($process == "1"){
		// Get my user
		$my_user_id = $_SESSION['user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);
		$query = "SELECT user_id, user_email, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;

	
		// Mkdir
		if(!(is_dir("../$get_current_reference_title_clean"))){
			mkdir("../$get_current_reference_title_clean");
		}
		if(!(is_dir("../$get_current_reference_title_clean/$get_current_group_title_clean"))){
			mkdir("../$get_current_reference_title_clean/$get_current_group_title_clean");
		}
		if(!(is_dir("../$get_current_reference_title_clean/$get_current_group_title_clean/_images"))){
			mkdir("../$get_current_reference_title_clean/$get_current_group_title_clean/_images");
		}
		if(!(is_dir("../$get_current_reference_title_clean/$get_current_group_title_clean/_images/$get_current_guide_title_clean"))){
			mkdir("../$get_current_reference_title_clean/$get_current_group_title_clean/_images/$get_current_guide_title_clean");
		}

		// Upload image

		// Image folder
		$imageFolder = "../$get_current_reference_title_clean/$get_current_group_title_clean/_images/$get_current_guide_title_clean/";

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
			$new_name = str_replace(".$inp_ext", "", $name);
			$new_name = output_html($new_name);
			$new_name = clean($new_name);
			$new_name = $new_name . ".$inp_ext";

			// Accept upload if there was no origin, or if it is an accepted origin
			$filetowrite = $imageFolder . $new_name;

			// Move it
			move_uploaded_file($temp['tmp_name'], $filetowrite);

			$inp_title = $temp['name'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_file_path = "$get_current_reference_title_clean/$get_current_group_title_clean/_images/$get_current_guide_title_clean";
			$inp_file_path_mysql = quote_smart($link, $inp_file_path);

			$datetime = date("Y-m-d H:i:s");

			$inp_file_name = output_html($new_name);
			$inp_file_name_mysql = quote_smart($link, $inp_file_name);

			$inp_file_thumb_a = str_replace(".$inp_ext", "", $name);
			$inp_file_thumb_a = $inp_file_thumb_a . "_thumb_200x113" . "." . $inp_ext;
			$inp_file_thumb_a_mysql = quote_smart($link, $inp_file_thumb_a);

			// IP
			$my_ip = "";
			$my_ip = $_SERVER['REMOTE_ADDR'];
			$my_ip = output_html($my_ip);
			$my_ip_mysql = quote_smart($link, $my_ip);

			mysqli_query($link, "INSERT INTO $t_references_index_guides_images
			(image_id, image_reference_id, image_group_id, image_guide_id, image_title, image_text, 
			image_path, image_file, image_thumb_200x113, image_photo_by_name, image_photo_by_website, 
			image_uploaded_datetime, image_uploaded_user_id, image_uploaded_ip) 
			VALUES 
			(NULL, $get_current_reference_id, $get_current_group_id, $get_current_guide_id, $inp_title_mysql, '', 
			$inp_file_path_mysql, $inp_file_name_mysql, $inp_file_thumb_a_mysql, '', '', 
			'$datetime', $get_my_user_id, $my_ip_mysql)")
			or die(mysqli_error($link));


			// Respond to the successful upload with JSON.
			// Use a location key to specify the path to the saved image resource.
			// { location : '/your/uploaded/image/file'}
			echo json_encode(array('location' => $filetowrite));
			exit;
		} 
		else {
			// Notify editor that the upload failed
			switch ($temp['name']['error']) {
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
	
} // found group
?>