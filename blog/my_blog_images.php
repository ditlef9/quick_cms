<?php 
/**
*
* File: blog/my_blog_images.php
* Version 1.0.0
* Date 12:05 10.02.2018
* Copyright (c) 2011-2018 S. A. Ditlefsen
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


/*- Blog config -------------------------------------------------------------------- */
include("$root/_admin/_data/blog.php");


/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/blog/ts_index.php");
include("$root/_admin/_translations/site/$l/blog/ts_my_blog.php");

/*- Variables ------------------------------------------------------------------------- */


$tabindex = 0;
$l_mysql = quote_smart($link, $l);


if(isset($_GET['image_id'])) {
	$image_id = $_GET['image_id'];
	$image_id = strip_tags(stripslashes($image_id));
}
else{
	$image_id = "";
}


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_images - $l_my_blog - $l_blog";
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
	$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_rank) = $row;

	// Get blog info
	$query = "SELECT blog_info_id, blog_user_id, blog_language, blog_title, blog_description, blog_created, blog_updated, blog_posts, blog_comments, blog_views, blog_user_ip FROM $t_blog_info WHERE blog_user_id=$my_user_id_mysql AND blog_language=$l_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_blog_info_id, $get_blog_user_id, $get_blog_language, $get_blog_title, $get_blog_description, $get_blog_created, $get_blog_updated, $get_blog_posts, $get_blog_comments, $get_blog_views, $get_blog_user_ip) = $row;

	if($get_blog_info_id == ""){

		echo"
		<h1><img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />Loading...</h1>
		<meta http-equiv=\"refresh\" content=\"1;url=$root/blog/my_blog_setup.php?l=$l\">
	

		<p>$l_creating_your_blog</p>
		";
	}
	else{
		if($action == ""){
			if($process == "1"){
				
				// Finnes mappen?
				$upload_path = "$root/_uploads/blog/$l/$get_blog_info_id";

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


				// Upload
				if($_SERVER["REQUEST_METHOD"] == "POST") {
       					$tmp_name = $_FILES['inp_image']["tmp_name"];
					$image = $_FILES['inp_image']['name'];
					$extension = get_extension($image);
					$extension = strtolower($extension);
				

					$datetime = date("ymdhis");
					
					$inp_title = str_replace(".$extension", "", $image);
					$inp_title = output_html($inp_title);
					$inp_title_mysql = quote_smart($link, $inp_title);

					$inp_title_clean = clean($inp_title);

					$inp_extension_clean = clean($extension);
					
					$inp_text = $_POST['inp_text'];
					$inp_text = output_html($inp_text);
					$inp_text_mysql = quote_smart($link, $inp_text);

					$inp_photo_by_name = $_POST['inp_photo_by_name'];
					$inp_photo_by_name = output_html($inp_photo_by_name);
					$inp_photo_by_name_mysql = quote_smart($link, $inp_photo_by_name);

					$inp_photo_by_website = $_POST['inp_photo_by_website'];
					$inp_photo_by_website = output_html($inp_photo_by_website);
					$inp_photo_by_website_mysql = quote_smart($link, $inp_photo_by_website);

					$inp_path = "_uploads/blog/$l/$get_blog_info_id";
					$inp_path_mysql = quote_smart($link, $inp_path);

					$inp_file = "$inp_title_clean.$inp_extension_clean";
					$inp_file_mysql = quote_smart($link, $inp_file);

					$inp_thumb_a = $inp_title_clean . "_thumb_a." . $inp_extension_clean;
					$inp_thumb_a_mysql = quote_smart($link, $inp_thumb_a);

					$inp_thumb_b = $inp_title_clean . "_thumb_b." . $inp_extension_clean;
					$inp_thumb_b_mysql = quote_smart($link, $inp_thumb_b);

					$inp_thumb_c = $inp_title_clean . "_thumb_c." . $inp_extension_clean;
					$inp_thumb_c_mysql = quote_smart($link, $inp_thumb_c);

					$my_ip = $_SERVER['REMOTE_ADDR'];
					$my_ip = output_html($my_ip);
					$my_ip_mysql = quote_smart($link, $my_ip);

					if($image){

						if (($extension != "heic") && ($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
							$ft = "warning";
							$fm = "unknown_file_format";
							$url = "my_blog_images.php?action=upload_image&l=$l&ft=$ft&fm=$fm"; 
							header("Location: $url");
							exit;
						}
						else{
							if(move_uploaded_file($tmp_name, "$root/$inp_path/$inp_file")){
							
								// Check width and height
								list($width,$height) = getimagesize("$root/$inp_path/$inp_file");
								if($width == "" OR $height == ""){
									unlink("$filename");

									$ft = "warning";
									$fm = "image_could_not_be_uploaded_please_check_file_size";
						
									$url = "my_blog_images.php?l=$l&ft=$ft&fm=$fm"; 
									header("Location: $url");
									exit;
								}
							



								// Insert into MySQL
								mysqli_query($link, "INSERT INTO $t_blog_images
								(image_id, image_user_id, image_title, image_text, image_path, image_thumb_a, image_thumb_b, image_thumb_c, image_file, image_photo_by_name, image_photo_by_website, image_uploaded_datetime, image_uploaded_ip, image_unique_views, image_ip_block, image_reported, image_reported_checked, image_likes, image_dislikes, image_likes_dislikes_ipblock, image_comments) 
								VALUES 
								(NULL, $my_user_id_mysql, $inp_title_mysql, $inp_text_mysql , $inp_path_mysql, $inp_thumb_a_mysql, $inp_thumb_b_mysql, $inp_thumb_c_mysql, $inp_file_mysql, $inp_photo_by_name_mysql, $inp_photo_by_website_mysql, '$datetime', $my_ip_mysql, '0', '', 0, '', 0, 0, '', '0')")
								or die(mysqli_error($link));


								// Send feedback
								$ft = "success";
								$fm = "image_uploaded";
								$url = "my_blog_images.php?l=$l&ft=$ft&fm=$fm"; 
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
						$url = "my_blog_images.php?l=$l&ft=$ft&fm=$fm"; 
						header("Location: $url");
						exit;
				
					}

				} // if($_SERVER["REQUEST_METHOD"] == "POST") {

				
			}
			echo"
			<h1>$l_my_blog $l_images</h1>
		
			<!-- Where am I ? -->
				<p><b>$l_you_are_here:</b><br />
				<a href=\"index.php?l=$l\">$l_blog</a>
				&gt;
				<a href=\"view_blog.php?info_id=$get_blog_info_id&amp;l=$l\">$get_blog_title</a>
				&gt;
				<a href=\"my_blog.php?l=$l\">$l_my_blog</a>
				&gt;
				<a href=\"my_blog_images.php?l=$l\">$l_images</a>
				</p>
			<!-- Where am I ? -->
				
			<!-- Feedback -->
				";
				if($ft != ""){
					if($fm == "changes_saved"){
						$fm = "$l_changes_saved";
					}
					else{
						$fm = ucfirst($fm);
					}
					echo"<div class=\"$ft\"><span>$fm</span></div>";
				}
				echo"	
			<!-- //Feedback -->


				
			
			<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "unknown_file_format"){
					$fm = "$l_unknown_file_format";
				}
				elseif($fm == "image_could_not_be_uploaded_please_check_file_size"){
					$fm = "$l_image_could_not_be_uploaded_please_check_file_size";
				}
				elseif($fm == "photo_unknown_error"){
					$fm = "$l_photo_unknown_error";
				}
				elseif($fm == "no_file_selected"){
					$fm = "$l_no_file_selected";
				}
				elseif($fm == "photo_exceeds_filesize"){
					$fm = "$l_photo_exceeds_filesize";
				}
				elseif($fm == "photo_exceeds_filesize_form"){
					$fm = "$l_photo_exceeds_filesize_form";
				}
				elseif($fm == "unknown_upload_error"){
					$fm = "$l_unknown_upload_error";
				}
				elseif($fm == "image_uploaded"){
					$fm = "$l_image_uploaded";
					
				}
				else{
					$fm = "$ft";
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
			<!-- //Feedback -->

			<!-- Upload iamge Form -->

				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_title\"]').focus();
				});
				</script>
		
				<form method=\"post\" action=\"my_blog_images.php?l=$l&amp;process=1\" enctype=\"multipart/form-data\">
				
				<div class=\"bodycell\">
					<h2>$l_upload_image</h2>

					<p>$l_select_image (<span>$l_will_be_resized_to $blogPostsImageSizeXSav x $blogPostsImageSizeYSav px</span>):<br />
					<input name=\"inp_image\" type=\"file\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
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

			<!-- //Upload iamge Form -->


			<!-- Last x Images -->
				";
				
				$query = "SELECT image_id, image_user_id, image_blog_post_id, image_title, image_text, image_path, image_thumb_a, image_thumb_b, image_thumb_c, image_file, image_photo_by_name, image_photo_by_website, image_uploaded_datetime, image_uploaded_ip, image_unique_views, image_ip_block, image_reported, image_reported_checked, image_likes, image_dislikes, image_likes_dislikes_ipblock, image_comments FROM $t_blog_images WHERE image_user_id=$my_user_id_mysql ORDER BY image_id DESC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_image_id, $get_image_user_id, $get_image_blog_post_id, $get_image_title, $get_image_text, $get_image_path, $get_image_thumb_a, $get_image_thumb_b, $get_image_thumb_c, $get_image_file, $get_image_photo_by_name, $get_image_photo_by_website, $get_image_uploaded_datetime, $get_image_uploaded_ip, $get_image_unique_views, $get_image_ip_block, $get_image_reported, $get_image_reported_checked, $get_image_likes, $get_image_dislikes, $get_image_likes_dislikes_ipblock, $get_image_comments) = $row;
			
					// Clean up
					if(!(file_exists("../$get_image_path/$get_image_file"))){
						echo"<div class=\"info\"><p><a href=\"../$get_image_path/$get_image_file\">$root/$get_image_path/$get_image_file</a> image not found on server.</p></div>\n";
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


					echo"
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
						<a href=\"my_blog_images.php?action=edit_image&amp;image_id=$get_image_id&amp;l=$l\">$l_edit</a>
						&middot;
						<a href=\"my_blog_images.php?action=rotate_image&amp;image_id=$get_image_id&amp;l=$l&amp;process=1\">$l_rotate</a>
						&middot;
						<a href=\"my_blog_images.php?action=delete_image&amp;image_id=$get_image_id&amp;l=$l\">$l_delete</a>
						</p>
					  </td>
					 </tr>
					</table>
					";
				}
				echo"
			<!-- //Last x Images -->
			";
		} // action == ""
		elseif($action == "edit_image"){
			// Find image
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
					
					$inp_text = $_POST['inp_text'];
					$inp_text = output_html($inp_text);
					$inp_text_mysql = quote_smart($link, $inp_text);

					$inp_photo_by_name = $_POST['inp_photo_by_name'];
					$inp_photo_by_name = output_html($inp_photo_by_name);
					$inp_photo_by_name_mysql = quote_smart($link, $inp_photo_by_name);

					$inp_photo_by_website = $_POST['inp_photo_by_website'];
					$inp_photo_by_website = output_html($inp_photo_by_website);
					$inp_photo_by_website_mysql = quote_smart($link, $inp_photo_by_website);


					mysqli_query($link, "UPDATE $t_blog_images SET 
								image_title=$inp_title_mysql, 
								image_text=$inp_text_mysql, 
								image_photo_by_name=$inp_photo_by_name_mysql, 
								image_photo_by_website=$inp_photo_by_website_mysql 
							WHERE image_id=$image_id_mysql AND image_user_id=$my_user_id_mysql") or die(mysqli_error($link));

					$url = "my_blog_images.php?action=$action&image_id=$image_id&l=$l&ft=success&fm=changes_saved"; 
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
		
				<form method=\"post\" action=\"my_blog_images.php?action=$action&amp;image_id=$image_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
				

				<p><b>$l_title:</b><br />
				<input type=\"text\" name=\"inp_title\" value=\"$get_image_title\" size=\"25\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
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
				<a href=\"my_blog_images.php?l=$l\"><img src=\"_gfx/icons/go-previous.png\" alt=\"go-previous.png\" /></a>
				<a href=\"my_blog_images.php?l=$l\">$l_previous</a>
				</p>
				";
			}// image found

		} // edit_image
		elseif($action == "delete_image"){
			// Find image
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
					if(file_exists("$root/$get_image_path/$get_image_thumb_a") && $get_image_thumb_a != ""){
						unlink("$root/$get_image_path/$get_image_thumb_a");
					}
					if(file_exists("$root/$get_image_path/$get_image_thumb_b") && $get_image_thumb_b != ""){
						unlink("$root/$get_image_path/$get_image_thumb_b");
					}
					if(file_exists("$root/$get_image_path/$get_image_thumb_c") && $get_image_thumb_c != ""){
						unlink("$root/$get_image_path/$get_image_thumb_c");
					}
					if(file_exists("$root/$get_image_path/$get_image_file") && $get_image_file != ""){
						unlink("$root/$get_image_path/$get_image_file");
					}

					mysqli_query($link, "DELETE FROM $t_blog_images WHERE image_id=$image_id_mysql AND image_user_id=$my_user_id_mysql") or die(mysqli_error($link));

					$url = "my_blog_images.php?l=$l&ft=success&fm=image_deleted"; 
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
				<a href=\"my_blog_images.php?action=delete_image&amp;image_id=$image_id&amp;l=$l&amp;process=1\" class=\"btn_danger\">$l_confirm_delete</a>
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
					if(file_exists("$root/$get_image_path/$get_image_thumb_a") && $get_image_thumb_a != ""){
						unlink("$root/$get_image_path/$get_image_thumb_a");
					}
					if(file_exists("$root/$get_image_path/$get_image_thumb_b") && $get_image_thumb_b != ""){
						unlink("$root/$get_image_path/$get_image_thumb_b");
					}
					if(file_exists("$root/$get_image_path/$get_image_thumb_c") && $get_image_thumb_c != ""){
						unlink("$root/$get_image_path/$get_image_thumb_c");
					}
				
					// Dates
					$datetime = date("ymdhis"); 
					
					// Get extention
					$extension = get_extension($get_image_file);
					$extension = strtolower($extension);	

					// Get a new name
					$new_image_file = $my_user_id . "_" . $datetime . "." . $extension;
					$new_image_file_mysql = quote_smart($link, $new_image_file);

					// Get a new thumb name
					$new_image_thumb_a = $my_user_id . "_" . $datetime . "_thumb_a." . $extension;
					$new_image_thumb_a_mysql = quote_smart($link, $new_image_thumb_a);

					$new_image_thumb_b = $my_user_id . "_" . $datetime . "_thumb_b." . $extension;
					$new_image_thumb_b_mysql = quote_smart($link, $new_image_thumb_b);

					$new_image_thumb_c = $my_user_id . "_" . $datetime . "_thumb_c." . $extension;
					$new_image_thumb_c_mysql = quote_smart($link, $new_image_thumb_c);


			
					// Update name
					mysqli_query($link, "UPDATE $t_blog_images SET image_thumb_a=$new_image_thumb_a_mysql, 
								image_thumb_b=$new_image_thumb_b_mysql, 
								image_thumb_c=$new_image_thumb_c_mysql, 
								image_file=$new_image_file_mysql WHERE image_id=$image_id_mysql AND image_user_id=$my_user_id_mysql") or die(mysqli_error($link));


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




					// Move
					$url = "my_blog_images.php?l=$l&ft=success&fm=image_rotated#image$get_image_id"; 
					header("Location: $url");
					exit;
				} // process
			}// image found
		} // rotate_image
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