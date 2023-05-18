<?php 
/**
*
* File: forum/new_topic_image_uploader.php
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
include("$root/_admin/_data/forum.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/forum/ts_index.php");

/*- Forum config ------------------------------------------------------------------------ */
include("$root/_admin/_data/forum.php");
include("_include_tables.php");


/*- Variables ------------------------------------------------------------------------- */


$tabindex = 0;
$l_mysql = quote_smart($link, $l);




/*- Title ---------------------------------------------------------------------------------- */
$query_t = "SELECT title_id, title_language, title_value FROM $t_forum_titles WHERE title_language=$l_mysql";
$result_t = mysqli_query($link, $query_t);
$row_t = mysqli_fetch_row($result_t);
list($get_current_title_id, $get_current_title_language, $get_current_title_value) = $row_t;

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_new_topic: $l_upload_image - $get_current_title_value";
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
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;
	
	if($action == ""){
		if($process == "1"){
		
			// Create folders
			if(!(is_dir("$root/_scripts/users/"))){
				mkdir("$root/_scripts/users/", 0777);
			}
			if(!(is_dir("$root/_uploads/users/images"))){
				mkdir("$root/_uploads/users/images", 0777);
			}
			if(!(is_dir("$root/_uploads/users/images/$get_my_user_id/forum"))){
				mkdir("$root/_uploads/users/images/$get_my_user_id/forum", 0777);
			}



			// Get extention
			function getExtension($str) {
				$i = strrpos($str,".");
				if (!$i) { return ""; } 
				$l = strlen($str) - $i;
				$ext = substr($str,$i+1,$l);
				return $ext;
			}


			// Upload
			if($_SERVER["REQUEST_METHOD"] == "POST") {
				/*- Front -----------------------------------------------------------------------------------------------------------------------*/
				$image = $_FILES['inp_image']['name'];
				

				$filename = stripslashes($_FILES['inp_image']['name']);
				$extension = getExtension($filename);
				$extension = strtolower($extension);

				if($image){

					if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
						$ft = "warning";
						$fm = "unknown_file_format";
						$url = "new_topic_image_uploader.php?l=$l&ft=$ft&fm=$fm"; 
						header("Location: $url");
						exit;
					}
					else{
						$size=filesize($_FILES['inp_image']['tmp_name']);


						if($extension=="jpg" || $extension=="jpeg" ){
							ini_set ('gd.jpeg_ignore_warning', 1);
							error_reporting(0);
							$uploadedfile = $_FILES['inp_image']['tmp_name'];
							$src = imagecreatefromjpeg($uploadedfile);

						}
						elseif($extension=="png"){
							$uploadedfile = $_FILES['inp_image']['tmp_name'];
							$src = @imagecreatefrompng($uploadedfile);
						}
						else{
							$src = @imagecreatefromgif($uploadedfile);
						}
 
						list($width,$height) = @getimagesize($uploadedfile);

						if($width == "" OR $height == ""){
	
							$ft = "warning";
							$fm = "photo_could_not_be_uploaded_please_check_file_size";
						
							$url = "new_topic_image_uploader.php?l=$l&ft=$ft&fm=$fm"; 
							header("Location: $url");
							exit;


						}
						else{
							// Keep orginal
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
							$filename = "$root/_uploads/users/images/$get_my_user_id/forum/". $get_my_user_id . "_" . $datetime . "." . $extension;

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

						


							// Send feedback
							$ft = "success";
							$fm = "image_uploaded";
							$new_image = $get_my_user_id . "_" . $datetime . "." . $extension;
							$url = "new_topic_image_uploader.php?l=$l&ft=$ft&fm=$fm&new_image=$new_image"; 
							header("Location: $url");
							exit;

						}  // if($width == "" OR $height == ""){
					}
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
					$url = "new_topic_image_uploader.php?l=$l&ft=$ft&fm=$fm"; 
					header("Location: $url");
					exit;
				
				}

			} // if($_SERVER["REQUEST_METHOD"] == "POST") {

		} // process == 1
		echo"
		<h1>$l_upload_image</h1>
		

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
					if(isset($_GET['new_image'])){
						$new_image = $_GET['new_image'];
						$new_image = output_html($new_image);
						if(file_exists("$root/_uploads/users/images/$get_my_user_id/forum/$new_image")){
							$fm = "
								</p>
								<form method=\"GET\" action=\"new_topic_image_uploader.php\" enctype=\"multipart/form-data\">
									<p><b>$l_image_uploaded</b></p>

									<p>
									<img src=\"$root/_uploads/users/images/$get_my_user_id/forum/$new_image\" alt=\"$root/_uploads/users/images/$get_my_user_id/forum/$new_image\" />
									</p>

									<script>
									\$(document).ready(function(){
										\$('[name=\"inp_copy\"]').focus();
									});
									</script>
									<p><b>$l_url_to_copy:</b><br />
									<input type=\"text\" name=\"inp_copy\" value=\"$configSiteURLSav/_uploads/users/images/$get_my_user_id/forum/$new_image\" size=\"25\" />
									</p>
								</form>
								<p>
								";
						}
						else{
							$fm = "Image uploaded, but an error happened.. <a href=\"$root/_uploads/users/images/$get_my_user_id/forum/$new_image\">$root/_uploads/users/images/$get_my_user_id/forum/$new_image</a>";
						}
					}
					
				}
				else{
					$fm = "$ft";
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->


		<form method=\"POST\" action=\"new_topic_image_uploader.php?l=$l&amp;process=1\" enctype=\"multipart/form-data\">

		<p>$l_select_image:<br />
		<input name=\"inp_image\" type=\"file\" tabindex=\"1\" />
		</p>

		<p>
		<input type=\"submit\" value=\"$l_upload\" tabindex=\"2\" class=\"btn_default\" />
		</p>

		</form>
	
		<p style=\"margin-top: 30px;\">
		<a href=\"new_topic.php?l=$l\"><img src=\"_gfx/go-previous.png\" alt=\"go-previous.png\" /></a>
		<a href=\"new_topic.php?l=$l\">$l_go_back</a>
		</p>
		";
	} // action == ""
	
}
else{
	echo"
	<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" /> Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=forum/new_topic.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>