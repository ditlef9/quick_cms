<?php 
/**
*
* File: blog/my_blog_logo.php
* Version 1.0.0
* Date 21:05 13.03.2019
* Copyright (c) 2019 S. A. Ditlefsen
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

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/blog/ts_index.php");
include("$root/_admin/_translations/site/$l/blog/ts_my_blog.php");
include("$root/_admin/_translations/site/$l/blog/ts_my_blog_images.php");

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
$website_title = "$l_logo - $l_my_blog - $l_blog";
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
			echo"
			<h1>$l_my_blog</h1>
		
			<!-- Where am I ? -->
				<p><b>$l_you_are_here:</b><br />
				<a href=\"index.php?l=$l\">$l_blog</a>
				&gt;
				<a href=\"view_blog.php?info_id=$get_blog_info_id&amp;l=$l\">$get_blog_title</a>
				&gt;
				<a href=\"my_blog.php?l=$l\">$l_my_blog</a>
				&gt;
				<a href=\"my_blog_logo.php?l=$l\">$l_logo</a>
				</p>
			<!-- //Where am I ? -->
		
				
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


			<p>
			<a href=\"my_blog_logo.php?action=upload_logo&amp;l=$l\" class=\"btn btn_default\">$l_upload_logo</a>
			</p>
			
			<!-- Current logo -->";
				
				// Get blog logo
				$query = "SELECT logo_id, logo_blog_info_id, logo_user_id, logo_path, logo_thumb, logo_file, logo_uploaded_datetime, logo_uploaded_ip, logo_reported, logo_reported_checked FROM $t_blog_logos WHERE logo_blog_info_id=$get_blog_info_id AND logo_user_id=$my_user_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_logo_id, $get_logo_blog_info_id, $get_logo_user_id, $get_logo_path, $get_logo_thumb, $get_logo_file, $get_logo_uploaded_datetime, $get_logo_uploaded_ip, $get_logo_reported, $get_logo_reported_checked) = $row;

				if($get_logo_id != ""){
					echo"<h2>$l_logo</h2>";

					if(file_exists("$root/$get_logo_path/$get_logo_file") && $get_logo_file != ""){
						echo"
						<table>
						 <tr>
						  <td style=\"vertical-align: top;padding: 0px 10px 0px 0px;\">
							<p>
							<a href=\"$root/$get_logo_path/$get_logo_file\"><img src=\"$root/$get_logo_path/$get_logo_file\" alt=\"$get_logo_file\" /></a>
							</p>
						  </td>
						  <td style=\"vertical-align: top;\">
							<p>
							<a href=\"my_blog_logo.php?action=delete_image&amp;logo_id=$get_logo_id&amp;l=$l\">$l_delete</a>
							</p>
						  </td>
						 </tr>
						</table>
						";
					}
					else{
						echo"Problem";
					}
				} // logo found
				echo"
			<!-- //Current logo -->
			";
		} // action == ""
		elseif($action == "upload_logo"){
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
				
					$filename = stripslashes($_FILES['inp_image']['name']);
					$extension = get_extension($filename);
					$extension = strtolower($extension);


					$datetime = date("ymdhis");
					$filename = "$root/_uploads/blog/$l/$get_blog_info_id/". $my_user_id . "_" . $datetime . "." . $extension;

					if($image){

						if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
							$ft = "warning";
							$fm = "unknown_file_format";
							$url = "my_blog_logo.php?action=upload_logo&l=$l&ft=$ft&fm=$fm"; 
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
						
									$url = "my_blog_logo.php?action=upload_logo&l=$l&ft=$ft&fm=$fm"; 
									header("Location: $url");
									exit;
								}
								

								// Inp Path
								$inp_path = "_uploads/blog/$l/$get_blog_info_id";
								$inp_path_mysql = quote_smart($link, $inp_path);

								// Inp File
								$inp_file = $my_user_id . "_" . $datetime . "." . $extension;
								$inp_file_mysql = quote_smart($link, $inp_file);

								// Inp Thumb
								$inp_thumb = $my_user_id . "_" . $datetime . "_thumb." . $extension;
								$inp_thumb_mysql = quote_smart($link, $inp_thumb);

								// Inp Datetime
								$datetime = date("Y-m-d H:i:s");

								// Inp IP
								$my_ip = $_SERVER['REMOTE_ADDR'];
								$my_ip = output_html($my_ip);
								$my_ip_mysql = quote_smart($link, $my_ip);


								// Look for old logo
								$query = "SELECT logo_id, logo_blog_info_id, logo_user_id, logo_path, logo_thumb, logo_file, logo_uploaded_datetime, logo_uploaded_ip, logo_reported, logo_reported_checked FROM $t_blog_logos WHERE logo_blog_info_id=$get_blog_info_id AND logo_user_id=$my_user_id_mysql";
								$result = mysqli_query($link, $query);
								$row = mysqli_fetch_row($result);
								list($get_logo_id, $get_logo_blog_info_id, $get_logo_user_id, $get_logo_path, $get_logo_thumb, $get_logo_file, $get_logo_uploaded_datetime, $get_logo_uploaded_ip, $get_logo_reported, $get_logo_reported_checked) = $row;

								if(file_exists("$root/$get_logo_path/$get_logo_file") && $get_logo_file != ""){
									unlink("$root/$get_logo_path/$get_logo_file");

								}
								
								if($get_logo_id == ""){
									// Insert into MySQL
									mysqli_query($link, "INSERT INTO $t_blog_logos 
									(logo_id, logo_blog_info_id, logo_user_id, logo_path, logo_thumb, logo_file, logo_uploaded_datetime, logo_uploaded_ip, logo_reported, logo_reported_checked) 
									VALUES 
									(NULL, $get_blog_info_id, $my_user_id_mysql, $inp_path_mysql, $inp_thumb_mysql, $inp_file_mysql, '$datetime', $my_ip_mysql, '0', '')")
									or die(mysqli_error($link));
								}
								else{
									$result = mysqli_query($link, "UPDATE $t_blog_logos SET 
logo_path=$inp_path_mysql, 
logo_thumb=$inp_thumb_mysql, 
logo_file=$inp_file_mysql, 
logo_uploaded_datetime='$datetime', 
logo_uploaded_ip=$my_ip_mysql, 
logo_reported='0', logo_reported_checked=''
 WHERE logo_id=$get_logo_id");

								}


								// Send feedback
								$ft = "success";
								$fm = "image_uploaded";
								$url = "my_blog_logo.php?l=$l&ft=$ft&fm=$fm"; 
								header("Location: $url");
								exit;

							}  // move uploaded file
						} // ext ok
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
						$url = "my_blog_logo.php?action=upload_logo&l=$l&ft=$ft&fm=$fm"; 
						header("Location: $url");
						exit;
				
					}

				} // if($_SERVER["REQUEST_METHOD"] == "POST") {

				
			}
			echo"
			<h1>$l_my_blog</h1>
			
			<!-- Where am I ? -->
				<p><b>$l_you_are_here:</b><br />
				<a href=\"index.php?l=$l\">$l_blog</a>
				&gt;
				<a href=\"view_blog.php?info_id=$get_blog_info_id&amp;l=$l\">$get_blog_title</a>
				&gt;
				<a href=\"my_blog.php?l=$l\">$l_my_blog</a>
				&gt;
				<a href=\"my_blog_logo.php?l=$l\">$l_logo</a>
				&gt;
				<a href=\"my_blog_logo.php?action=$action&amp;l=$l\">$l_upload_logo</a>
				</p>
			<!-- //Where am I ? -->
				
			
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

				<h2>$l_upload_logo</h2>

				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_title\"]').focus();
				});
				</script>
		
				<form method=\"post\" action=\"my_blog_logo.php?action=$action&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
				

				<p>$l_select_image 114x114 px:<br />
				<input name=\"inp_image\" type=\"file\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
				</p>

				<p><input type=\"submit\" value=\"$l_upload\" class=\"btn btn_default\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></p>
				</form>

			<!-- //Upload iamge Form -->

			";
		} // upload
		elseif($action == "delete_logo"){
			// Find image
			$logo_id_mysql = quote_smart($link, $logo_id);
			$query = "SELECT logo_id, logo_blog_info_id, logo_user_id, logo_path, logo_thumb, logo_file, logo_uploaded_datetime, logo_uploaded_ip, logo_reported, logo_reported_checked FROM $t_blog_logos WHERE logo_id=$logo_id_mysql AND logo_blog_info_id=$get_blog_info_id AND logo_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_logo_id, $get_logo_blog_info_id, $get_logo_user_id, $get_logo_path, $get_logo_thumb, $get_logo_file, $get_logo_uploaded_datetime, $get_logo_uploaded_ip, $get_logo_reported, $get_logo_reported_checked) = $row;


			if($get_logo_id == ""){
				echo"<p>Logo not found</p>";
			} // image not found
			else{
				if($process == "1"){
					unlink("$root/$get_logo_path/$get_logo_thumb");
					unlink("$root/$get_logo_path/$get_logo_file");

					mysqli_query($link, "DELETE FROM $t_blog_logos WHERE logo_id=$logo_id_mysql") or die(mysqli_error($link));

					$url = "my_blog_logo.php?l=$l&ft=success&fm=image_deleted"; 
					header("Location: $url");
					exit;
				}

				echo"
				<h1>$l_logo</h1>
				
			
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
				<a href=\"$root/$get_logo_path/$get_logo_file\"><img src=\"$root/$get_logo_path/$get_logo_file\" alt=\"$get_logo_file\" /></a>
				</p>
					
				<p>
				$l_are_you_sure
				</p>
				

				<p>
				<a href=\"my_blog_logo.php?action=delete_logo&amp;logo_id=$logo_id&amp;l=$l&amp;process=1\" class=\"btn_danger\">$l_confirm_delete</a>
				</p>

				<p>
				<a href=\"my_blog_logo.php?l=$l\"><img src=\"_gfx/icons/go-previous.png\" alt=\"go-previous.png\" /></a>
				<a href=\"my_blog_logo.php?l=$l\">$l_previous</a>
				</p>
				";
			}// image found
		}
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