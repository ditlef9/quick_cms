<?php 
/**
*
* File: my_blog_new_post_text_upload_image.php
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

/*- Tables ---------------------------------------------------------------------------- */
include("_tables_blog.php");

/*- Blog config -------------------------------------------------------------------- */
include("$root/_admin/_data/blog.php");


/*- Content ---------------------------------------------------------------------------------- */
// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	// Get my user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_email, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_email, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;

	// Get blog info
	$query = "SELECT blog_info_id, blog_user_id, blog_language, blog_title, blog_description, blog_created, blog_updated, blog_posts, blog_comments, blog_views, blog_user_ip FROM $t_blog_info WHERE blog_user_id=$my_user_id_mysql AND blog_language=$l_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_blog_info_id, $get_blog_user_id, $get_blog_language, $get_blog_title, $get_blog_description, $get_blog_created, $get_blog_updated, $get_blog_posts, $get_blog_comments, $get_blog_views, $get_blog_user_ip) = $row;


	// Can I have a blog?
	$can_post = "true";
	if($blogWhoCanHaveBlogSav == "admin"){
		if($get_my_user_rank != "admin"){
			$can_post = "false";
			echo"<p>Sorry, you can not post. Only admin can post.</p>";
		}
	}
	elseif($blogWhoCanHaveBlogSav == "admin_and_moderator"){
		if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
		}
		else{
			$can_post = "false";
			echo"<p>Sorry, you can not post. Only admin and moderator can post.</p>";
		}
	}
	elseif($blogWhoCanHaveBlogSav == "admin_moderator_and_editor"){
		if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator" OR $get_my_user_rank == "editor"){
		}
		else{
			$can_post = "false";
			echo"<p>Sorry, you can not post. Only admin, moderator and editor can post.</p>";
		}
	}
	elseif($blogWhoCanHaveBlogSav == "admin_moderator_editor_and_trusted"){
		if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator" OR $get_my_user_rank == "editor" OR $get_my_user_rank == "trusted"){
		}
		else{
			$can_post = "false";
			echo"<p>Sorry, you can not post. Only admin, moderator, editor and trusted can post.</p>";
		}
	}

	if($can_post == "true"){
		if($get_blog_info_id == ""){
			echo"blog id not found";
		}
		else{
			// Fetch blog post
			if(isset($_GET['blog_post_id'])){
				$blog_post_id = $_GET['blog_post_id'];
				$blog_post_id = strip_tags(stripslashes($blog_post_id));
				if(!(is_numeric($blog_post_id))){
					echo"Blog post id not numeric";
					die;
				}
			}
			else{
				$blog_post_id =  "";
			}
			$blog_post_id_mysql = quote_smart($link, $blog_post_id);

			$query = "SELECT blog_post_id, blog_post_user_id, blog_post_title_pre, blog_post_title, blog_post_language, blog_post_status, blog_post_category_id, blog_post_category_title, blog_post_introduction, blog_post_privacy_level, blog_post_text, blog_post_image_path, blog_post_image_thumb_small, blog_post_image_thumb_medium, blog_post_image_thumb_large, blog_post_image_file, blog_post_image_ext, blog_post_image_text, blog_post_ad, blog_post_created, blog_post_created_rss, blog_post_updated, blog_post_updated_rss, blog_post_allow_comments, blog_post_comments, blog_post_views, blog_post_views_ipblock, blog_post_user_ip FROM $t_blog_posts WHERE blog_post_id=$blog_post_id_mysql AND blog_post_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_blog_post_id, $get_current_blog_post_user_id, $get_current_blog_post_title_pre, $get_current_blog_post_title, $get_current_blog_post_language, $get_current_blog_post_status, $get_current_blog_post_category_id, $get_current_blog_post_category_title, $get_current_blog_post_introduction, $get_current_blog_post_privacy_level, $get_current_blog_post_text, $get_current_blog_post_image_path, $get_current_blog_post_image_thumb_small, $get_current_blog_post_image_thumb_medium, $get_current_blog_post_image_thumb_large, $get_current_blog_post_image_file, $get_current_blog_post_image_ext, $get_current_blog_post_image_text, $get_current_blog_post_ad, $get_current_blog_post_created, $get_current_blog_post_created_rss, $get_current_blog_post_updated, $get_current_blog_post_updated_rss, $get_current_blog_post_allow_comments, $get_current_blog_post_comments, $get_current_blog_post_views, $get_current_blog_post_views_ipblock, $get_current_blog_post_user_ip) = $row;
			if($get_current_blog_post_id == ""){
				echo"Post not found";
				die;
			} // blog post not found, create one


			// Finnes mappen?
			$upload_path = "$root/_uploads/blog/$l/$get_blog_info_id/$get_current_blog_post_id";
	
			if(!(is_dir("$root/_uploads"))){
				mkdir("$root/_uploads");
			}
			if(!(is_dir("$root/_uploads/blog"))){
				mkdir("$root/_uploads/blog");
			}
			if(!(is_dir("$root/_uploads/blog/$l"))){
				mkdir("$root/_uploads/blog/$l");
			}
			if(!(is_dir("$root/_uploads/blog/$l/$get_blog_info_id"))){
				mkdir("$root/_uploads/blog/$l/$get_blog_info_id");
			}
			if(!(is_dir("$root/_uploads/blog/$l/$get_blog_info_id/$get_current_blog_post_id"))){
				mkdir("$root/_uploads/blog/$l/$get_blog_info_id/$get_current_blog_post_id");
			}



			// Image folder
			$imageFolder = "$root/_uploads/blog/$l/$get_blog_info_id/$get_current_blog_post_id/";

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

				$inp_file_path = "_uploads/blog/$l/$get_blog_info_id/$get_current_blog_post_id";
				$inp_file_path_mysql = quote_smart($link, $inp_file_path);

				$datetime = date("Y-m-d H:i:s");
				$date_saying = date("j M Y");

				$inp_my_alias_mysql = quote_smart($link, $get_my_user_alias);
				$inp_my_email_mysql = quote_smart($link, $get_my_user_email);
				$inp_my_image_mysql = quote_smart($link, $get_my_photo_destination);

				$inp_file_name = output_html($new_name);
				$inp_file_name_mysql = quote_smart($link, $inp_file_name);

				$inp_file_thumb_a = $name . "_" . $uniqid . "_thumb_a" . "." . $inp_ext;
				$inp_file_thumb_a_mysql = quote_smart($link, $inp_file_thumb_a);

				$inp_file_thumb_b = $name . "_" . $uniqid . "_thumb_b" . "." . $inp_ext;
				$inp_file_thumb_b_mysql = quote_smart($link, $inp_file_thumb_b);

				$inp_file_thumb_c = $name . "_" . $uniqid . "_thumb_c" . "." . $inp_ext;
				$inp_file_thumb_c_mysql = quote_smart($link, $inp_file_thumb_b);

				// IP
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

				
				mysqli_query($link, "INSERT INTO $t_blog_images
				(image_id, image_user_id, image_blog_post_id, image_title, image_text, image_path, image_thumb_a, image_thumb_b, image_thumb_c, image_file, image_photo_by_name, image_photo_by_website, image_uploaded_datetime, image_uploaded_ip, image_unique_views, image_ip_block, image_reported, image_reported_checked, image_likes, image_dislikes, image_likes_dislikes_ipblock, image_comments) 
				VALUES 
				(NULL, $my_user_id_mysql, $get_current_blog_post_id, $inp_title_mysql, '', $inp_file_path_mysql, $inp_file_thumb_a_mysql, $inp_file_thumb_b_mysql, $inp_file_thumb_c_mysql, $inp_file_name_mysql, '', '', '$datetime', $my_ip_mysql, '0', '', 0, '', 0, 0, '', '0')")
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
				echo"HTTP/1.1 500 Server Error $fm";
			}
		} // found blog
	} // can post
	else{
		// Not logged in
		header("HTTP/1.1 500 Server Error");
		echo"HTTP/1.1 500 Server Error";
	}	
} // logged in
else{
	// Not logged in
	header("HTTP/1.1 500 Server Error");
}
?>
