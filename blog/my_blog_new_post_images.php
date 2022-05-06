<?php 
/**
*
* File: blog/my_blog_new_post_images.php
* Version 1.0.0
* Date 16:20 12.07.2020
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

/*- Tables -------------------------------------------------------------------- */
$t_blog_stats_most_used_categories	= $mysqlPrefixSav . "blog_stats_most_used_categories";


/*- Variables ------------------------------------------------------------------------- */


$tabindex = 0;
$l_mysql = quote_smart($link, $l);




/*- Functions -------------------------------------------------------------------------------- */
// include("$root/_admin/_functions/get_extension.php");

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



/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_new_post_$images - $l_my_blog - $l_blog";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");

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
			echo"
			<h1><img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />Loading...</h1>
			<meta http-equiv=\"refresh\" content=\"1;url=$root/blog/my_blog_setup.php?reference=new_post&amp;l=$l\">
			<p>$l_creating_your_blog</p>
			";
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
				echo"<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" /> Loading...</h1>
				<meta http-equiv=\"refresh\" content=\"0;url=my_blog_new_post.php?l=$l\">
				";
				die;
			} // blog post not found, create one
			
			if($action == ""){
				// Upload
				if($process == "1"){
					// Upload image

					// Date
					$datetime = date("ymdhis");
				
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

					$inp_image_text = $_POST['inp_text'];
					$inp_image_text = output_html($inp_image_text);
					$inp_image_text_mysql = quote_smart($link, $inp_image_text);

					$inp_photo_by_name = $_POST['inp_photo_by_name'];
					$inp_photo_by_name = output_html($inp_photo_by_name);
					$inp_photo_by_name_mysql = quote_smart($link, $inp_photo_by_name);

					$inp_photo_by_website = $_POST['inp_photo_by_website'];
					$inp_photo_by_website = output_html($inp_photo_by_website);
					$inp_photo_by_website_mysql = quote_smart($link, $inp_photo_by_website);
				
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
						$filename = "$root/_uploads/blog/$l/$get_blog_info_id/$get_current_blog_post_id/". $inp_file;


						if($image){

							if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif") && ($extension != "heic") && ($extension != "webp")) {
								$ft = "warning";
								$fm = "unknown_file_format";
								$url = "my_blog_new_post_images.php?blog_post_id=$get_current_blog_post_id&l=$l&ft=$ft&fm=$fm"; 
								header("Location: $url");
								exit;
							}
							else{
								if(move_uploaded_file($tmp_name, "$filename")){
							
									// Check width and height
									list($width,$height) = getimagesize($filename);
									if($width == "" OR $height == ""){
										unlink("$filename");

										$ft = "warning";
										$fm = "image_could_not_be_uploaded_please_check_file_size";
						
										$url = "my_blog_new_post_images.php?blog_post_id=$get_current_blog_post_id&l=$l&ft=$ft&fm=$fm"; 
										header("Location: $url");
										exit;
									}
							

									// Path
									$inp_path = "_uploads/blog/$l/$get_blog_info_id/$get_current_blog_post_id";
									$inp_path_mysql = quote_smart($link, $inp_path);



									// Thumb
									$file_without_extension = str_replace(".$extension", "", $inp_file);

									$inp_thumb_a = $file_without_extension . "_thumb_a." . $extension;
									$inp_thumb_a_mysql = quote_smart($link, $inp_thumb_a);

									$inp_thumb_b = $file_without_extension . "_thumb_b." . $extension;
									$inp_thumb_b_mysql = quote_smart($link, $inp_thumb_b);

									$inp_thumb_c = $file_without_extension . "_thumb_c." . $extension;
									$inp_thumb_c_mysql = quote_smart($link, $inp_thumb_c);
	
									// Datetime
									$datetime = date("Y-m-d H:i:s");

									// IP
									$my_ip = $_SERVER['REMOTE_ADDR'];
									$my_ip = output_html($my_ip);
									$my_ip_mysql = quote_smart($link, $my_ip);


									// Insert into MySQL
									mysqli_query($link, "INSERT INTO $t_blog_images
									(image_id, image_user_id, image_blog_post_id, image_title, image_text, image_path, image_thumb_a, image_thumb_b, image_thumb_c, image_file, image_photo_by_name, image_photo_by_website, image_uploaded_datetime, image_uploaded_ip, image_unique_views, image_ip_block, image_reported, image_reported_checked, image_likes, image_dislikes, image_likes_dislikes_ipblock, image_comments) 
									VALUES 
									(NULL, $my_user_id_mysql, $get_current_blog_post_id, $inp_image_title_mysql, $inp_image_text_mysql , $inp_path_mysql, $inp_thumb_a_mysql, $inp_thumb_b_mysql, $inp_thumb_c_mysql, $inp_file_mysql, $inp_photo_by_name_mysql, $inp_photo_by_website_mysql, '$datetime', $my_ip_mysql, '0', '', 0, '', 0, 0, '', '0')")
									or die(mysqli_error($link));

									// Resize image to $blogPostsImageSizeXSav x $blogPostsImageSizeYSav
									if($width > "$blogPostsImageSizeXSav" OR $height > "$blogPostsImageSizeYSav"){
										$source_file = "$root/_uploads/blog/$l/$get_blog_info_id/$get_current_blog_post_id/$inp_file";
										resize_crop_image($blogPostsImageSizeXSav, $blogPostsImageSizeYSav, $source_file, $source_file);
									}

									// Insert into blog post
									if($blogEditModeSav == "bbcode"){
										$inp_image_text_bb = str_replace("<br />", "\n", $inp_image_text);
										$inp_image_text_bb = str_replace("&amp;quot;", "&quot;", $inp_image_text_bb);

										$inp_text = "$get_current_blog_post_text
[img]../_uploads/blog/"; 
										$inp_text = $inp_text  . "$l";
										$inp_text = $inp_text  . "/";
										$inp_text = $inp_text  . "$get_blog_info_id";
										$inp_text = $inp_text  . "/";
										$inp_text = $inp_text  . "$get_current_blog_post_id";
										$inp_text = $inp_text  . "/";
										$inp_text = $inp_text  . "/";
										$inp_text = $inp_text  . "$inp_file";
										$inp_text = $inp_text  . "[/img]";
										$inp_text = $inp_text  . "$inp_image_text_bb";

										$inp_text_mysql = quote_smart($link, $inp_text);

										$result = mysqli_query($link, "UPDATE $t_blog_posts SET blog_post_text=$inp_text_mysql WHERE blog_post_id=$get_current_blog_post_id") or die(mysqli_error($link));
									}
									elseif($blogEditModeSav == "wuciwug"){
										$inp_text = "$get_current_blog_post_text
<figure>
	<img src=\"../_uploads/blog/$l/$get_blog_info_id/$get_current_blog_post_id/$inp_file\" alt=\"$inp_file\" />
	<figcaption>$inp_image_text</figcaption>
</figure>
";

										// $inp_text = encode_national_letters($inp_text);

										// require_once "$root/_admin/_functions/htmlpurifier/HTMLPurifier.auto.php";

										// $config = HTMLPurifier_Config::createDefault();
										// $purifier = new HTMLPurifier($config);
										// $clean_html = $purifier->purify($inp_text);

										$sql = "UPDATE $t_blog_posts SET blog_post_text=? WHERE blog_post_id=$get_current_blog_post_id";
										$stmt = $link->prepare($sql);
										$stmt->bind_param("s", $inp_text);
										$stmt->execute();
										if ($stmt->errno) {
											echo "FAILURE!!! " . $stmt->error; die;
										}
									}

									// Send feedback
									$ft = "success";
									$fm = "image_uploaded";
									$url = "my_blog_new_post_images.php?blog_post_id=$get_current_blog_post_id&l=$l&ft=$ft&fm=$fm"; 
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
							$url = "my_blog_new_post_images.php?blog_post_id=$get_current_blog_post_id&l=$l&ft=$ft&fm=$fm"; 
							header("Location: $url");
							exit;
				
						}

					} // if($_SERVER["REQUEST_METHOD"] == "POST") {

				}

				echo"
			
				<h1>$l_new_post</h1>

				<!-- Where am I? -->
					<p><b>$l_you_are_here:</b><br />
					<a href=\"index.php?l=$l\">$l_blog</a>
					&gt;
					<a href=\"view_blog.php?info_id=$get_blog_info_id&amp;l=$l\">$get_blog_title</a>
					&gt;
					<a href=\"my_blog.php?l=$l\">$l_my_blog</a>
					&gt;
					<a href=\"my_blog_new_post.php?blog_post_id=$get_current_blog_post_id&amp;l=$l\">$l_new_post</a>
					&gt;
					<a href=\"my_blog_new_post_images.php?blog_post_id=$get_current_blog_post_id&amp;l=$l\">$l_images</a>
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
					<a href=\"my_blog_new_post.php?blog_post_id=$get_current_blog_post_id&amp;l=$l\" class=\"btn_default\">$l_text</a>
					<a href=\"my_blog_new_post_meta.php?blog_post_id=$get_current_blog_post_id&amp;l=$l\" class=\"btn_default\">$l_meta</a>
					<a href=\"my_blog_new_post_main_image.php?blog_post_id=$get_current_blog_post_id&amp;l=$l\" class=\"btn_default\">$l_main_image</a>
					<a href=\"my_blog_new_post_images.php?blog_post_id=$get_current_blog_post_id&amp;l=$l\" class=\"btn_default\" style=\"font-weight: bold;\">$l_images</a>
					<a href=\"view_post.php?post_id=$get_current_blog_post_id&amp;l=$l\" class=\"btn_default\">$l_view_post</a>
					</p>
				<!-- //Form Buttons (Navigation) -->
			

				<!-- Upload image form -->
		
					<form method=\"post\" action=\"my_blog_new_post_images.php?blog_post_id=$get_current_blog_post_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
					<div class=\"bodycell\" style=\"margin-top: 20px;\">
						<h2>$l_add_image_to_post</h2>

						<p>$l_select_image (<span>$l_will_be_resized_to $blogPostsImageSizeXSav x $blogPostsImageSizeYSav px</span>):<br />
						<input name=\"inp_image\" type=\"file\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /><br />
						</p>

						<p><b>$l_image_text:</b><br />
						<input type=\"text\" name=\"inp_text\" value=\"\" size=\"25\" style=\"width: 90%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
						</p>


						<p><b>$l_photo_by_name:</b><br />
						<input type=\"text\" name=\"inp_photo_by_name\" value=\"\" size=\"25\" style=\"width: 90%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
						</p>


						<p><b>$l_photo_by_website:</b><br />
						<input type=\"text\" name=\"inp_photo_by_website\" value=\"\" size=\"25\" style=\"width: 90%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
						</p>


						<p><input type=\"submit\" value=\"$l_upload\" class=\"btn btn_default\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></p>
			
					</div>
					</form>
				<!-- //Upload image form -->



				<!-- All Images -->
					";
				
					$query = "SELECT image_id, image_user_id, image_blog_post_id, image_title, image_text, image_path, image_thumb_a, image_thumb_b, image_thumb_c, image_file, image_uploaded_datetime, image_uploaded_ip, image_unique_views, image_ip_block, image_reported, image_reported_checked, image_likes, image_dislikes, image_likes_dislikes_ipblock, image_comments FROM $t_blog_images WHERE image_user_id=$my_user_id_mysql AND image_blog_post_id=$get_current_blog_post_id ORDER BY image_id DESC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_image_id, $get_image_user_id, $get_image_blog_post_id, $get_image_title, $get_image_text, $get_image_path, $get_image_thumb_a, $get_image_thumb_b, $get_image_thumb_c, $get_image_file, $get_image_uploaded_datetime, $get_image_uploaded_ip, $get_image_unique_views, $get_image_ip_block, $get_image_reported, $get_image_reported_checked, $get_image_likes, $get_image_dislikes, $get_image_likes_dislikes_ipblock, $get_image_comments) = $row;
			
						// Clean up
						if(!(file_exists("$root/$get_image_path/$get_image_file"))){
							echo"<div class=\"info\"><p>Img not found on server.</p></div>\n";
							mysqli_query($link, "DELETE FROM $t_blog_images WHERE image_id='$get_image_id'") or die(mysqli_error($link));
						}

						// Thumb A
						if(!(file_exists("$root/$get_image_path/$get_image_thumb_a")) && $get_image_file != ""){
							resize_crop_image($blogPostsThumbSmallSizeXSav, $blogPostsThumbSmallSizeYSav, "$root/$get_image_path/$get_image_file", "$root/$get_image_path/$get_image_thumb_a");
						}

						// Thumb B
						if(!(file_exists("$root/$get_image_path/$get_image_thumb_b")) && $get_image_file != ""){
							resize_crop_image($blogPostsThumbMediumSizeXSav, $blogPostsThumbMediumSizeYSav, "$root/$get_image_path/$get_image_file", "$root/$get_image_path/$get_image_thumb_b");
						}

						echo"<a id=\"image_id$get_image_id\"></a>
						<table style=\"width: 100%;\">
						 <tr>
						  <td style=\"vertical-align: top;padding: 0px 10px 0px 0px;width: 200px;\">
							<p>
							<a href=\"$root/$get_image_path/$get_image_file\"><img src=\"$root/$get_image_path/$get_image_thumb_b\" alt=\"$get_image_thumb_b\" /></a>
							</p>
						  </td>
						  <td style=\"vertical-align: top;\">
						
							<p><b>$get_image_title</b></p>

							<p>$l_url_to_copy:<br />
							<input type=\"text\" name=\"img_$get_image_id\" size=\"25\" value=\"$configSiteURLSav/$get_image_path/$get_image_file\" style=\"width: 50%;\" />
							</p>
						
							<p>
							<a href=\"my_blog_new_post_images.php?blog_post_id=$get_current_blog_post_id&amp;action=edit_image&amp;image_id=$get_image_id&amp;l=$l\">$l_edit</a>
							&middot;
							<a href=\"my_blog_new_post_images.php?blog_post_id=$get_current_blog_post_id&amp;action=rotate_image&amp;image_id=$get_image_id&amp;l=$l&amp;process=1\">$l_rotate</a>
							&middot;
							<a href=\"my_blog_new_post_images.php?blog_post_id=$get_current_blog_post_id&amp;action=delete_image&amp;image_id=$get_image_id&amp;l=$l\">$l_delete</a>
							</p>
						  </td>
						 </tr>
						</table>
						";
					}
					echo"
				<!-- //All Images -->
				";
			}  // action == ""
			elseif($action == "edit_image"){
				// Find image
				if(isset($_GET['image_id'])) {
					$image_id = $_GET['image_id'];
					$image_id = strip_tags(stripslashes($image_id));
					if(!(is_numeric($image_id))){
						echo"Image not numeric";
						die;
					}
				}
				else{
					echo"Missing Image";
					die;
				}

				$image_id_mysql = quote_smart($link, $image_id);
				$query = "SELECT image_id, image_user_id, image_blog_post_id, image_title, image_text, image_path, image_thumb_a, image_thumb_b, image_thumb_c, image_file, image_photo_by_name, image_photo_by_website, image_uploaded_datetime, image_uploaded_ip, image_unique_views, image_ip_block, image_reported, image_reported_checked, image_likes, image_dislikes, image_likes_dislikes_ipblock, image_comments FROM $t_blog_images WHERE image_id=$image_id_mysql AND image_user_id=$my_user_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_image_id, $get_image_user_id, $get_image_blog_post_id, $get_image_title, $get_image_text, $get_image_path, $get_image_thumb_a, $get_image_thumb_b, $get_image_thumb_c, $get_image_file, $get_image_photo_by_name, $get_image_photo_by_website, $get_image_uploaded_datetime, $get_image_uploaded_ip, $get_image_unique_views, $get_image_ip_block, $get_image_reported, $get_image_reported_checked, $get_image_likes, $get_image_dislikes, $get_image_likes_dislikes_ipblock, $get_image_comments) = $row;


				if($get_image_id == ""){
					echo"<p>Image not found</p>";
				} // image not found
				else{
					if($process == "1"){
						$inp_title = $_POST['inp_title'];
						$inp_title = output_html($inp_title);
						$inp_title_mysql = quote_smart($link, $inp_title);

						$inp_image_text = $_POST['inp_text'];
						$inp_image_text = output_html($inp_image_text);
						$inp_image_text_mysql = quote_smart($link, $inp_image_text);

						$inp_photo_by_name = $_POST['inp_photo_by_name'];
						$inp_photo_by_name = output_html($inp_photo_by_name);
						$inp_photo_by_name_mysql = quote_smart($link, $inp_photo_by_name);

						$inp_photo_by_website = $_POST['inp_photo_by_website'];
						$inp_photo_by_website = output_html($inp_photo_by_website);
						$inp_photo_by_website_mysql = quote_smart($link, $inp_photo_by_website);

						mysqli_query($link, "UPDATE $t_blog_images SET image_title=$inp_title_mysql, image_text=$inp_image_text_mysql, image_photo_by_name=$inp_photo_by_name_mysql, image_photo_by_website=$inp_photo_by_website_mysql WHERE image_id=$image_id_mysql AND image_user_id=$my_user_id_mysql") or die(mysqli_error($link));


						$url = "my_blog_new_post_images.php?blog_post_id=$get_current_blog_post_id&action=$action&image_id=$image_id&l=$l&ft=success&fm=changes_saved"; 
						header("Location: $url");
						exit;
					}

					echo"
					<h2>$get_image_title</h2>
				
			
					<!-- Feedback -->
						";
						if($ft != "" && $fm != ""){
							if($fm == "changes_saved"){
								$fm = "$l_changes_saved";
							}
							else{
								$fm = "$ft";
							}
							echo"<div class=\"$ft\"><p>$fm</p></div>";
						}
						echo"
					<!-- //Feedback -->
				
					<p>
					<a href=\"$root/$get_image_path/$get_image_file\"><img src=\"$root/$get_image_path/$get_image_thumb_b\" alt=\"$get_image_thumb_b\" /></a>
					</p>

					<script>
					\$(document).ready(function(){
						\$('[name=\"inp_title\"]').focus();
					});
					</script>
		
					<form method=\"post\" action=\"my_blog_new_post_images.php?blog_post_id=$get_current_blog_post_id&amp;action=$action&amp;image_id=$image_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
				

					<p><b>$l_title:</b><br />
					<input type=\"text\" name=\"inp_title\" value=\"$get_image_title\" size=\"25\" style=\"width: 90%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
					</p>

					<p><b>$l_image_text:</b><br />
					<input type=\"text\" name=\"inp_text\" value=\"$get_image_text\" size=\"25\" style=\"width: 90%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
					</p>

					<p><b>$l_photo_by_name:</b><br />
					<input type=\"text\" name=\"inp_photo_by_name\" value=\"$get_image_photo_by_name\" size=\"25\" style=\"width: 90%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
					</p>

					<p><b>$l_photo_by_website:</b><br />
					<input type=\"text\" name=\"inp_photo_by_website\" value=\"$get_image_photo_by_website\" size=\"25\" style=\"width: 90%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
					</p>


					<p><input type=\"submit\" value=\"$l_save_changes\" class=\"btn btn_default\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></p>
					</form>



					<p>
					<a href=\"my_blog_new_post_images.php?blog_post_id=$get_current_blog_post_id&amp;l=$l#image_id$image_id\"><img src=\"_gfx/icons/go-previous.png\" alt=\"go-previous.png\" /></a>
					<a href=\"my_blog_new_post_images.php?blog_post_id=$get_current_blog_post_id&amp;l=$l#image_id$image_id\">$l_previous</a>
					</p>
					";
				}// image found
			} // action == "" edit_image
			elseif($action == "delete_image"){
				// Find image
				if(isset($_GET['image_id'])) {
					$image_id = $_GET['image_id'];
					$image_id = strip_tags(stripslashes($image_id));
					if(!(is_numeric($image_id))){
						echo"Image not numeric";
						die;
					}
				}
				else{
					echo"Missing Image";
					die;
				}

				$image_id_mysql = quote_smart($link, $image_id);
				$query = "SELECT image_id, image_user_id, image_blog_post_id, image_title, image_text, image_path, image_thumb_a, image_thumb_b, image_thumb_c, image_file, image_photo_by_name, image_photo_by_website, image_uploaded_datetime, image_uploaded_ip, image_unique_views, image_ip_block, image_reported, image_reported_checked, image_likes, image_dislikes, image_likes_dislikes_ipblock, image_comments FROM $t_blog_images WHERE image_id=$image_id_mysql AND image_user_id=$my_user_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_image_id, $get_image_user_id, $get_image_blog_post_id, $get_image_title, $get_image_text, $get_image_path, $get_image_thumb_a, $get_image_thumb_b, $get_image_thumb_c, $get_image_file, $get_image_photo_by_name, $get_image_photo_by_website, $get_image_uploaded_datetime, $get_image_uploaded_ip, $get_image_unique_views, $get_image_ip_block, $get_image_reported, $get_image_reported_checked, $get_image_likes, $get_image_dislikes, $get_image_likes_dislikes_ipblock, $get_image_comments) = $row;



				if($get_image_id == ""){
					echo"<p>Image not found</p>";
				} // image not found
				else{
					if($process == "1"){
						if(file_exists("$root/$get_image_path/$get_image_thumb_a")){
							unlink("$root/$get_image_path/$get_image_thumb_a");
						}
						if(file_exists("$root/$get_image_path/$get_image_thumb_b")){
							unlink("$root/$get_image_path/$get_image_thumb_b");
						}
						if(file_exists("$root/$get_image_path/$get_image_thumb_c")){
							unlink("$root/$get_image_path/$get_image_thumb_c");
						}
						unlink("$root/$get_image_path/$get_image_file");

						mysqli_query($link, "DELETE FROM $t_blog_images WHERE image_id=$image_id_mysql AND image_user_id=$my_user_id_mysql") or die(mysqli_error($link));

						$url = "my_blog_new_post_images.php?blog_post_id=$get_current_blog_post_id&l=$l&ft=success&fm=image_deleted"; 
						header("Location: $url");
						exit;
					}

					echo"
					<h2>$get_image_title</h2>
				
			
					<!-- Feedback -->
					";
					if($ft != "" && $fm != ""){
						if($fm == "changes_saved"){
							$fm = "$l_changes_saved";
						}
						else{
							$fm = "$ft";
						}
						echo"<div class=\"$ft\"><p>$fm</p></div>";
					}
					echo"
					<!-- //Feedback -->
				
					<p>
					<a href=\"$root/$get_image_path/$get_image_file\"><img src=\"$root/$get_image_path/$get_image_thumb_b\" alt=\"$get_image_thumb_b\" /></a>
					</p>
					
					<p>
					$l_are_you_sure
					</p>

					<p>
					<a href=\"my_blog_new_post_images.php?blog_post_id=$get_current_blog_post_id&amp;action=delete_image&amp;image_id=$get_image_id&amp;l=$l&amp;process=1\" class=\"btn_danger\">$l_confirm_delete</a>
					</p>

					<p>
					<a href=\"my_blog_images.php?l=$l\"><img src=\"_gfx/icons/go-previous.png\" alt=\"go-previous.png\" /></a>
					<a href=\"my_blog_images.php?l=$l\">$l_previous</a>
					</p>
					";
				}// image found
			}
			elseif($action == "rotate_image"){
				// Find image
				if(isset($_GET['image_id'])) {
					$image_id = $_GET['image_id'];
					$image_id = strip_tags(stripslashes($image_id));
					if(!(is_numeric($image_id))){
						echo"Image not numeric";
						die;
					}
				}
				else{
					echo"Missing Image";
					die;
				}

				$image_id_mysql = quote_smart($link, $image_id);
				$query = "SELECT image_id, image_user_id, image_blog_post_id, image_title, image_text, image_path, image_thumb_a, image_thumb_b, image_thumb_c, image_file, image_photo_by_name, image_photo_by_website, image_uploaded_datetime, image_uploaded_ip, image_unique_views, image_ip_block, image_reported, image_reported_checked, image_likes, image_dislikes, image_likes_dislikes_ipblock, image_comments FROM $t_blog_images WHERE image_id=$image_id_mysql AND image_user_id=$my_user_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_image_id, $get_image_user_id, $get_image_blog_post_id, $get_image_title, $get_image_text, $get_image_path, $get_image_thumb_a, $get_image_thumb_b, $get_image_thumb_c, $get_image_file, $get_image_photo_by_name, $get_image_photo_by_website, $get_image_uploaded_datetime, $get_image_uploaded_ip, $get_image_unique_views, $get_image_ip_block, $get_image_reported, $get_image_reported_checked, $get_image_likes, $get_image_dislikes, $get_image_likes_dislikes_ipblock, $get_image_comments) = $row;


				if($get_image_id == ""){
					echo"<p>Image not found</p>";
				} // image not found
				else{
					if($process == "1"){
						// Delete thumb
						if(file_exists("$root/$get_image_path/$get_image_thumb_a")){
							unlink("$root/$get_image_path/$get_image_thumb_a");
						}
						if(file_exists("$root/$get_image_path/$get_image_thumb_b")){
							unlink("$root/$get_image_path/$get_image_thumb_b");
						}
						if(file_exists("$root/$get_image_path/$get_image_thumb_c")){
							unlink("$root/$get_image_path/$get_image_thumb_c");
						}
					
						// Get extention
						$extension = get_extension($get_image_file);
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
						mysqli_query($link, "UPDATE $t_blog_images SET image_thumb_a=$new_image_thumb_a_mysql, image_thumb_b=$new_image_thumb_b_mysql, image_thumb_c=$new_image_thumb_c_mysql, image_file=$new_image_file_mysql WHERE image_id=$image_id_mysql AND image_user_id=$my_user_id_mysql") or die(mysqli_error($link));


						// Rename
						rename("$root/$get_image_path/$get_image_file", "$root/$get_image_path/$new_image_file");


						$rotate = "";
						if($extension == "jpg"){
							// Load
							$source = imagecreatefromjpeg("$root/$get_image_path/$new_image_file");

							// Rotate
							if($rotate == ""){
								$rotate = imagerotate($source, -90, 0);
							}
							else{
								$rotate = imagerotate($source, 90, 0);
							}

							// Save
							imagejpeg($rotate, "$root/$get_image_path/$new_image_file");
						}
						elseif($extension == "png"){
							// Load
							$source = imagecreatefrompng("$root/$get_image_path/$new_image_file");

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
							imagepng($rotate, "$root/$get_image_path/$new_image_file");

						}

		
						// Free the memory
						imagedestroy($source);


						// Update post text (if we have this image in text)
						$inp_blog_post_image_text = str_replace("$get_image_path/$get_image_file", "$get_image_path/$new_image_file", $get_current_blog_post_text);


						$sql = "UPDATE $t_blog_posts SET blog_post_text=? WHERE blog_post_id=$get_current_blog_post_id";
						$stmt = $link->prepare($sql);
						$stmt->bind_param("s", $inp_blog_post_image_text);
						$stmt->execute();
						if ($stmt->errno) {
							echo "FAILURE!!! " . $stmt->error; die;
						}


						// Move
						$url = "my_blog_new_post_images.php?blog_post_id=$get_current_blog_post_id&l=$l&ft=success&fm=image_rotated#image_id$get_image_id"; 
						header("Location: $url");
						exit;
					} // process
				}// image found
			} // rotate_image
		} // blog info found
	} // can post (access)
}
else{
	echo"
	<h1>
	<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=blog/my_blog.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>