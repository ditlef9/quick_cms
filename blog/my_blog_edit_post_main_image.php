<?php 
/**
*
* File: blog/my_blog_edit_post_main_image.php
* Version 1.0.0
* Date 09:37 18.07.2020
* Copyright (c) 2011-2020 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration --------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

/*- Root dir -------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config -------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Tables ---------------------------------------------------------------------------- */
include("_tables_blog.php");

/*- Functions ------------------------------------------------------------------------- */
include("$root/_admin/_functions/encode_national_letters.php");
include("$root/_admin/_functions/decode_national_letters.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/blog/ts_index.php");
include("$root/_admin/_translations/site/$l/blog/ts_my_blog.php");

/*- Blog config -------------------------------------------------------------------- */
include("$root/_admin/_data/blog.php");


/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);


/*- Functions -------------------------------------------------------------------------------- */
function delete_cache($dirname) {
	if (is_dir($dirname))
		$dir_handle = opendir($dirname);
	if (!$dir_handle)
		return false;
	while($file = readdir($dir_handle)) {
		if ($file != "." && $file != "..") {
			if (!is_dir($dirname."/".$file))
  				unlink($dirname."/".$file);
        		else
				delete_directory($dirname.'/'.$file);    
			}
		}
	closedir($dir_handle);
	rmdir($dirname);
	return true;
}

/*- Get extention ---------------------------------------------------------------------- */
function getExtension($str) {
		$i = strrpos($str,".");
		if (!$i) { return ""; } 
		$l = strlen($str) - $i;
		$ext = substr($str,$i+1,$l);
		return $ext;
}


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_my_blog - $l_blog";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */
// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security']) && isset($_GET['post_id'])){
	
	// Get my user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_rank) = $row;

	// Get blog info
	$query = "SELECT blog_info_id, blog_user_id, blog_language, blog_title, blog_description, blog_created, blog_updated, blog_posts, blog_comments, blog_views, blog_user_ip FROM $t_blog_info WHERE blog_user_id=$my_user_id_mysql AND blog_language=$l_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_blog_info_id, $get_current_blog_user_id, $get_current_blog_language, $get_current_blog_title, $get_current_blog_description, $get_current_blog_created, $get_current_blog_updated, $get_current_blog_posts, $get_current_blog_comments, $get_current_blog_views, $get_current_blog_user_ip) = $row;

	if($get_current_blog_info_id == ""){
		echo"
		<h1><img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />Loading...</h1>
		<meta http-equiv=\"refresh\" content=\"1;url=$root/blog/my_blog_setup.php?l=$l\">
		<p>$l_creating_your_blog</p>
		";
	}
	else{
		// Get post
		$post_id = $_GET['post_id'];
		$post_id = strip_tags(stripslashes($post_id));
		$post_id_mysql = quote_smart($link, $post_id);
			
		$query = "SELECT blog_post_id, blog_post_user_id, blog_post_title_pre, blog_post_title, blog_post_language, blog_post_status, blog_post_category_id, blog_post_category_title, blog_post_introduction, blog_post_privacy_level, blog_post_text, blog_post_image_path, blog_post_image_thumb_small, blog_post_image_thumb_medium, blog_post_image_thumb_large, blog_post_image_file, blog_post_image_ext, blog_post_image_text, blog_post_ad, blog_post_created, blog_post_created_rss, blog_post_updated, blog_post_updated_rss, blog_post_allow_comments, blog_post_comments, blog_post_views, blog_post_views_ipblock, blog_post_user_ip FROM $t_blog_posts WHERE blog_post_id=$post_id_mysql AND blog_post_user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_blog_post_id, $get_current_blog_post_user_id, $get_current_blog_post_title_pre, $get_current_blog_post_title, $get_current_blog_post_language, $get_current_blog_post_status, $get_current_blog_post_category_id, $get_current_blog_post_category_title, $get_current_blog_post_introduction, $get_current_blog_post_privacy_level, $get_current_blog_post_text, $get_current_blog_post_image_path, $get_current_blog_post_image_thumb_small, $get_current_blog_post_image_thumb_medium, $get_current_blog_post_image_thumb_large, $get_current_blog_post_image_file, $get_current_blog_post_image_ext, $get_current_blog_post_image_text, $get_current_blog_post_ad, $get_current_blog_post_created, $get_current_blog_post_created_rss, $get_current_blog_post_updated, $get_current_blog_post_updated_rss, $get_current_blog_post_allow_comments, $get_current_blog_post_comments, $get_current_blog_post_views, $get_current_blog_post_views_ipblock, $get_current_blog_post_user_ip) = $row;
			
		if($get_current_blog_post_id == ""){
			echo"<p>Post not found.</p>";
		}
		else{
		
			if($action == ""){
				// Upload
				if($process == "1"){
					// Upload image

					// Date
					$datetime = date("ymdhis");
				
					// Finnes mappen?
					$upload_path = "$root/_uploads/blog/$l/$get_current_blog_info_id/$get_current_blog_post_id";
	
					if(!(is_dir("$root/_uploads"))){
						mkdir("$root/_uploads");
					}
					if(!(is_dir("$root/_uploads/blog"))){
						mkdir("$root/_uploads/blog");
					}
					if(!(is_dir("$root/_uploads/blog/$l"))){
						mkdir("$root/_uploads/blog/$l");
					}
					if(!(is_dir("$root/_uploads/blog/$l/$get_current_blog_info_id"))){
						mkdir("$root/_uploads/blog/$l/$get_current_blog_info_id");
					}
					if(!(is_dir("$root/_uploads/blog/$l/$get_current_blog_info_id/$get_current_blog_post_id"))){
						mkdir("$root/_uploads/blog/$l/$get_current_blog_info_id/$get_current_blog_post_id");
					}

					$inp_image_text = $_POST['inp_image_text'];
					$inp_image_text = output_html($inp_image_text);
					$inp_image_text_mysql = quote_smart($link, $inp_image_text);

					// Upload
					if($_SERVER["REQUEST_METHOD"] == "POST") {
       						$tmp_name = $_FILES['inp_image']["tmp_name"];
						$image = $_FILES['inp_image']['name'];
						$extension = get_extension($image);
						$extension = strtolower($extension);

						// Title
						$inp_image_title = output_html($image);
						$inp_image_title = str_replace(".$extension", "", $inp_image_title);
						$inp_image_title = str_replace("_", " ", $inp_image_title);
						$inp_image_title = ucfirst($inp_image_title);
						$inp_image_title_mysql = quote_smart($link, $inp_image_title);

						// File
						$inp_image_title_clean = clean($inp_image_title);
						$inp_file = $inp_image_title_clean . ".$extension";
						$inp_file_mysql = quote_smart($link, $inp_file);
						$filename = "$root/_uploads/blog/$l/$get_current_blog_info_id/$get_current_blog_post_id/". $inp_file;


						if($image){

							if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif") && ($extension != "heic") && ($extension != "webp")) {
								$ft = "warning";
								$fm = "unknown_file_format";
								$url = "my_blog_edit_post_main_image.php?post_id=$get_current_blog_post_id&l=$l&ft=$ft&fm=$fm"; 
								header("Location: $url");
								exit;
							}
							else{
								// Delete old image
								if(file_exists("$root/$get_current_blog_post_image_path/$get_current_blog_post_image_thumb_small") && $get_current_blog_post_image_thumb_small != ""){
									unlink("$root/$get_current_blog_post_image_path/$get_current_blog_post_image_thumb_small");
								}
								if(file_exists("$root/$get_current_blog_post_image_path/$get_current_blog_post_image_thumb_medium") && $get_current_blog_post_image_thumb_medium != ""){
									unlink("$root/$get_current_blog_post_image_path/$get_current_blog_post_image_thumb_medium");
								}
								if(file_exists("$root/$get_current_blog_post_image_path/$get_current_blog_post_image_thumb_large") && $get_current_blog_post_image_thumb_large != ""){
									unlink("$root/$get_current_blog_post_image_path/$get_current_blog_post_image_thumb_large");
								}
								if(file_exists("$root/$get_current_blog_post_image_path/$get_current_blog_post_image_file") && $get_current_blog_post_image_file != ""){
									unlink("$root/$get_current_blog_post_image_path/$get_current_blog_post_image_file");
								}

								if(move_uploaded_file($tmp_name, "$filename")){
							
									// Check width and height
									list($width,$height) = getimagesize($filename);
									if($width == "" OR $height == ""){
										unlink("$filename");

										$ft = "warning";
										$fm = "image_could_not_be_uploaded_please_check_file_size";
						
										$url = "my_blog_edit_post_main_image.php?post_id=$get_current_blog_post_id&l=$l&ft=$ft&fm=$fm"; 
										header("Location: $url");
										exit;
									}

							

									// Path
									$inp_path = "_uploads/blog/$l/$get_current_blog_info_id/$get_current_blog_post_id";
									$inp_path_mysql = quote_smart($link, $inp_path);


									// Resize original image to $blogPostsImageSizeXSav x $blogPostsImageSizeYSav
									if($width > $blogPostsImageSizeXSav OR $height > $blogPostsImageSizeYSav){
										resize_crop_image($blogPostsImageSizeXSav, $blogPostsImageSizeYSav, "$root/_uploads/blog/$l/$get_current_blog_info_id/$get_current_blog_post_id/$inp_file", "$root/_uploads/blog/$l/$get_current_blog_info_id/$get_current_blog_post_id/$inp_file");
									}

									// Thumb
									$file_without_extension = str_replace(".$extension", "", $inp_file);

									$inp_thumb_a = $file_without_extension . "_thumb_a." . $extension;
									$inp_thumb_a_mysql = quote_smart($link, $inp_thumb_a);
									resize_crop_image($blogPostsThumbSmallSizeXSav, $blogPostsThumbSmallSizeYSav, "$root/$inp_path/$inp_file", "$root/$inp_path/$inp_thumb_a");


									$inp_thumb_b = $file_without_extension . "_thumb_b." . $extension;
									$inp_thumb_b_mysql = quote_smart($link, $inp_thumb_b);
									resize_crop_image($blogPostsThumbMediumSizeXSav, $blogPostsThumbMediumSizeYSav, "$root/$inp_path/$inp_file", "$root/$inp_path/$inp_thumb_b");

									$inp_thumb_c = $file_without_extension . "_thumb_c." . $extension;
									$inp_thumb_c_mysql = quote_smart($link, $inp_thumb_c);
									resize_crop_image($blogPostsThumbLargeSizeXSav, $blogPostsThumbLargeSizeYSav, "$root/$inp_path/$inp_file", "$root/$inp_path/$inp_thumb_c");
	

									// Logo over image
									if($blogPrintLogoOnImagesSav == "1"){
										include("$root/_admin/_functions/stamp_image.php");
										include("$root/_admin/_data/logo.php");
										$stamp = "$logoFileStampImages1280x720Sav";
										list($width,$height) = getimagesize("$root/_uploads/blog/$l/$get_current_blog_info_id/$get_current_blog_post_id/$inp_file");

										if($width < 1280){ // Width less than 1280
											$stamp = "$logoFileStampImages1280x720Sav";
										}
										elseif($width > 1280 && $width < 1920){  // Width bigger than 1280 and less than 1920
											$stamp = "$logoFileStampImages1920x1080Sav";
										}
										elseif($width > 1921 && $width < 2560){
											$stamp = "$logoFileStampImages2560x1440Sav";
										}
										else{
											$stamp = "$logoFileStampImages7680x4320Sav";
										}
										stamp_image("$root/_uploads/blog/$l/$get_current_blog_info_id/$get_current_blog_post_id/$inp_file", "$root/$logoPathSav/$stamp");
									}


									// Datetime
									$datetime = date("Y-m-d H:i:s");

									// IP
									$my_ip = $_SERVER['REMOTE_ADDR'];
									$my_ip = output_html($my_ip);
									$my_ip_mysql = quote_smart($link, $my_ip);


									// Update MySQL
									$result = mysqli_query($link, "UPDATE $t_blog_posts SET blog_post_image_path=$inp_path_mysql,
													blog_post_image_thumb_small=$inp_thumb_a_mysql,
													blog_post_image_thumb_medium=$inp_thumb_b_mysql,
													blog_post_image_thumb_large=$inp_thumb_c_mysql,
													blog_post_image_file=$inp_file_mysql WHERE blog_post_id=$get_current_blog_post_id") or die(mysqli_error($link));

									// Send feedback
									$ft = "success";
									$fm = "image_uploaded";
									$url = "my_blog_edit_post_main_image.php?post_id=$get_current_blog_post_id&l=$l&ft=$ft&fm=$fm"; 
									header("Location: $url");
									exit;

								}  // move uploaded file
							} // if($width == "" OR $height == ""){
						} // if($image){
						else{
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
           								$fm_front = "photo_exceeds_filesize_form";
									break;
								default:
           								$fm_front = "unknown_upload_error";
									break;
							}
							if(isset($fm) && $fm != ""){
								$ft = "warning";
							}
						
							// Send feedback
							$url = "my_blog_edit_post_main_image.php?post_id=$get_current_blog_post_id&l=$l&ft=$ft&fm=$fm"; 
							header("Location: $url");
							exit;
				
						}

					} // if($_SERVER["REQUEST_METHOD"] == "POST") {

				}

				echo"
				<h1>$l_edit $get_current_blog_post_title</h1>

				<!-- Where am I? -->
					<p><b>$l_you_are_here:</b><br />
					<a href=\"index.php?l=$l\">$l_blog</a>
					&gt;
					<a href=\"view_blog.php?info_id=$get_current_blog_info_id&amp;l=$l\">$get_current_blog_title</a>
					&gt;
					<a href=\"my_blog.php?l=$l\">$l_my_blog</a>
					&gt;
					<a href=\"view_post.php?post_id=$get_current_blog_post_id&amp;l=$l\">$get_current_blog_post_title</a>
					&gt;
					<a href=\"my_blog_edit_post_main_image.php?post_id=$get_current_blog_post_id&amp;l=$l\">$l_main_image</a>
					</p>
				<!-- //Where am I? -->

				<!-- Feedback -->
				";
				if($ft != ""){
					if($fm == "changes_saved"){
						$fm = "$l_changes_saved";
					}
					else{
						$fm = str_replace("_", " ", $fm);
						$fm = ucfirst($fm);
					}
					echo"<div class=\"$ft\"><span>$fm</span></div>";
				}
				echo"	
				<!-- //Feedback -->

				<!-- Form Buttons (Navigation) -->
					<p>
					<a href=\"my_blog_edit_post.php?post_id=$get_current_blog_post_id&amp;l=$l\" class=\"btn_default\">$l_text</a>
					<a href=\"my_blog_edit_post_meta.php?post_id=$get_current_blog_post_id&amp;l=$l\" class=\"btn_default\">$l_meta</a>
					<a href=\"my_blog_edit_post_main_image.php?post_id=$get_current_blog_post_id&amp;l=$l\" class=\"btn_default\" style=\"font-weight: bold;\">$l_main_image</a>
					<a href=\"my_blog_edit_post_images.php?post_id=$get_current_blog_post_id&amp;l=$l\" class=\"btn_default\">$l_images</a>
					<a href=\"view_post.php?post_id=$get_current_blog_post_id&amp;l=$l\" class=\"btn_default\">$l_view_post</a>
					</p>
				<!-- //Form Buttons (Navigation) -->
			

				<!-- Upload image form -->
		
					<form method=\"post\" action=\"my_blog_edit_post_main_image.php?post_id=$get_current_blog_post_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">



					<p><b>$l_image: (<em>$l_images_will_be_scaled_to $blogPostsImageSizeXSav x $blogPostsImageSizeYSav $l_pixels_lowercase</em>)</b><br />
					<!-- Existing image? -->
						";

						if($get_current_blog_post_image_file != "" && file_exists("$root/$get_current_blog_post_image_path/$get_current_blog_post_image_file")){

							if($get_current_blog_post_image_thumb_small != "" && !(file_exists("$root/$get_current_blog_post_image_path/$get_current_blog_post_image_thumb_small"))){
								resize_crop_image($blogPostsThumbSmallSizeXSav, $blogPostsThumbSmallSizeYSav, "$root/$get_current_blog_post_image_path/$get_current_blog_post_image_file", "$root/$get_current_blog_post_image_path/$get_current_blog_post_image_thumb_small");
							}
							if($get_current_blog_post_image_thumb_medium != "" && !(file_exists("$root/$get_current_blog_post_image_path/$get_current_blog_post_image_thumb_medium"))){
								resize_crop_image($blogPostsThumbMediumSizeXSav, $blogPostsThumbMediumSizeYSav, "$root/$get_current_blog_post_image_path/$get_current_blog_post_image_file", "$root/$get_current_blog_post_image_path/$get_current_blog_post_image_thumb_medium");
							}
							if($get_current_blog_post_image_thumb_large != "" && !(file_exists("$root/$get_current_blog_post_image_path/$get_current_blog_post_image_thumb_large"))){
								resize_crop_image($blogPostsThumbLargeSizeXSav, $blogPostsThumbLargeSizeYSav, "$root/$get_current_blog_post_image_path/$get_current_blog_post_image_file", "$root/$get_current_blog_post_image_path/$get_current_blog_post_image_thumb_large");
							}

	


							// 950 x 640
							echo"
							<a href=\"$root/$get_current_blog_post_image_path/$get_current_blog_post_image_file\"><img src=\"$root/$get_current_blog_post_image_path/$get_current_blog_post_image_thumb_medium\" alt=\"$get_current_blog_post_image_thumb_medium\" /></a>
							<br />
							<a href=\"my_blog_edit_post_main_image.php?action=rotate_image&amp;post_id=$get_current_blog_post_id&amp;l=$l&amp;process=1\">$l_rotate</a>

							</p>

							<p><b>$l_new_image:</b><br />";
						}
						echo"
					<!-- //Existing image? -->
					<input type=\"file\" name=\"inp_image\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>

					<p><b>$l_text:</b><br />	
					<textarea name=\"inp_image_text\" rows=\"5\" cols=\"45\">"; 
					$get_current_blog_post_image_text = str_replace("<br />", "\n", $get_current_blog_post_image_text);
					echo"$get_current_blog_post_image_text</textarea>
					</p>


					<p><input type=\"submit\" value=\"$l_save\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>

					</form>
				<!-- //Upload image form -->



				";
			}  // action == ""
			elseif($action == "rotate_image"){
				
				if($process == "1"){
					// Delete thumb
					if(file_exists("$root/$get_current_blog_post_image_path/$get_current_blog_post_image_thumb_small")){
						unlink("$root/$get_current_blog_post_image_path/$get_current_blog_post_image_thumb_small");
					}
					if(file_exists("$root/$get_current_blog_post_image_path/$get_current_blog_post_image_thumb_medium")){
						unlink("$root/$get_current_blog_post_image_path/$get_current_blog_post_image_thumb_medium");
					}
					if(file_exists("$root/$get_current_blog_post_image_path/$get_current_blog_post_image_thumb_large")){
						unlink("$root/$get_current_blog_post_image_path/$get_current_blog_post_image_thumb_large");
					}
					
					// Get extention
					$extension = get_extension($get_current_blog_post_image_file);
					$extension = strtolower($extension);	
	
					// Get a new name
					$datetime = date("ymdhis");
					$new_image_file = $my_user_id . "_" . $datetime . "." . $extension;
					$new_image_file_mysql = quote_smart($link, $new_image_file);

					// Get a new thumb name
					$datetime = date("ymdhis");
					$new_image_thumb_a = $my_user_id . "_" . $datetime . "_thumb_a." . $extension;
					$new_image_thumb_a_mysql = quote_smart($link, $new_image_thumb_a);

					$new_image_thumb_b = $my_user_id . "_" . $datetime . "_thumb_b." . $extension;
					$new_image_thumb_b_mysql = quote_smart($link, $new_image_thumb_b);

					$new_image_thumb_c = $my_user_id . "_" . $datetime . "_thumb_c." . $extension;
					$new_image_thumb_c_mysql = quote_smart($link, $new_image_thumb_c);


					// Update name
					mysqli_query($link, "UPDATE  $t_blog_posts SET 
								blog_post_image_file=$new_image_file_mysql, 
								blog_post_image_thumb_small=$new_image_thumb_a_mysql, 
								blog_post_image_thumb_medium=$new_image_thumb_b_mysql, 
								blog_post_image_thumb_large=$new_image_thumb_c_mysql WHERE blog_post_id=$get_current_blog_post_id") or die(mysqli_error($link));

					// Rename
					rename("$root/$get_current_blog_post_image_path/$get_current_blog_post_image_file", "$root/$get_current_blog_post_image_path/$new_image_file");

					$rotate = "";
					if($extension == "jpg"){
						// Load
						$source = imagecreatefromjpeg("$root/$get_current_blog_post_image_path/$new_image_file");

						// Rotate
						if($rotate == ""){
							$rotate = imagerotate($source, -90, 0);
						}
						else{
							$rotate = imagerotate($source, 90, 0);
						}

						// Save
						imagejpeg($rotate, "$root/$get_current_blog_post_image_path/$new_image_file");
					}
					elseif($extension == "png"){
						// Load
						$source = imagecreatefrompng("$root/$get_current_blog_post_image_path/$new_image_file");

						// Bg
						$bgColor = imagecolorallocatealpha($source, 255, 255, 255, 127);
				
						// Rotate
						if($rotate == ""){
							$rotate = imagerotate($source, -90, $bgColor);
						}
						else{
							$rotate = imagerotate($source, 90, $bgColor);
						}
	
						// Save
						imagesavealpha($rotate, true);
						imagepng($rotate, "$root/$get_current_blog_post_image_path/$new_image_file");

					}

		
					// Free the memory
					imagedestroy($source);


					// Move
					$url = "my_blog_edit_post_main_image.php?post_id=$get_current_blog_post_id&l=$l&ft=success&fm=image_rotated#image_id$get_image_id"; 
					header("Location: $url");
					exit;
				} // process
			} // rotate_image
				
		} //  post found

	} // found
}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/blog/my_blog.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>