<?php
/**
*
* File: users/my_profile_cover_photo_upload.php
* Version 11:08 08.08.2021
* Copyright (c) 2009-2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "0";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

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

/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/users/ts_index.php");

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_upload_cover_photo - $l_my_profile - $l_users";
include("$root/_webdesign/header.php");



/*- Content --------------------------------------------------------------------------- */



if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	// Get user
	$user_id = $_SESSION['user_id'];
	$user_id_mysql = quote_smart($link, $user_id);
	$security = $_SESSION['security'];
	$security_mysql = quote_smart($link, $security);

	$query = "SELECT user_id, user_name, user_language, user_rank FROM $t_users WHERE user_id=$user_id_mysql AND user_security=$security_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_name, $get_user_language, $get_user_rank) = $row;

	if($get_user_id == ""){
		echo"<h1>Error</h1><p>Error with user id.</p>"; 
		$_SESSION = array();
		session_destroy();
		die;
	}

	if($action == "upload"){

		
		// Create folders
		if(!(is_dir("$root/_scripts/users/"))){
			mkdir("$root/_scripts/users/", 0777);
		}
		if(!(is_dir("$root/_uploads/users/images"))){
			mkdir("$root/_uploads/users/images", 0777);
		}
		if(!(is_dir("$root/_uploads/users/images/$user_id/cover_photos"))){
			mkdir("$root/_uploads/users/images/$user_id/cover_photos", 0777);
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
					$url = "my_profile_cover_photo.php?l=$l&ft=$ft&fm=$fm"; 
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
					
						$url = "my_profile_cover_photo.php?l=$l&ft=$ft&fm=$fm"; 
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
						$filename = "$root/_uploads/users/images/$user_id/cover_photos/". $user_id . "_" . $datetime . "." . $extension;

						imagejpeg($tmp_org,$filename,100);

					
						imagedestroy($tmp_org);

						// Remove is cover from old cover photo
						$result = mysqli_query($link, "UPDATE $t_users_cover_photos SET cover_photo_is_current='0' WHERE cover_photo_user_id='$get_user_id'");
			

						// Insert to Mysql
						$inp_cover_photo_user_id_mysql = quote_smart($link, $user_id);

						$inp_cover_photo_destination = $user_id . "_" . $datetime . "." . $extension;
						$inp_pcover_photo_destination_mysql = quote_smart($link, $inp_cover_photo_destination);
			
						$inp_cover_photo_datetime = date("Y-m-d H:i:s");

						$inp_cover_photo_ip = $_SERVER['REMOTE_ADDR'];
						$inp_cover_photo_ip = output_html($inp_cover_photo_ip);
						$inp_cover_photo_ip_mysql = quote_smart($link, $inp_cover_photo_ip);

						mysqli_query($link, "INSERT INTO $t_users_cover_photos
						(cover_photo_id, cover_photo_user_id, cover_photo_is_current, cover_photo_destination, cover_photo_datetime, cover_photo_ip, cover_photo_views, cover_photo_likes, cover_photo_comments) 
						VALUES 
						(NULL, $inp_cover_photo_user_id_mysql, '1', $inp_pcover_photo_destination_mysql, '$inp_cover_photo_datetime', $inp_cover_photo_ip_mysql, '0', '0', '0')")
						or die(mysqli_error($link));
						

						// Send feedback
						$ft = "success";
						$fm = "photo_uploaded";
						$url = "my_profile_cover_photo.php?l=$l&ft=$ft&fm=$fm"; 
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
				$url = "my_profile_cover_photo.php?l=$l&ft=$ft&fm=$fm"; 
				header("Location: $url");
				exit;
				
			}

		} // if($_SERVER["REQUEST_METHOD"] == "POST") {
	}
	if($action == ""){
		echo"
		<h1>$l_upload_photo</h1>


		<form method=\"POST\" action=\"my_profile_cover_photo_upload.php?action=upload&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\" name=\"nameform\">

		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "unknown_file_format"){
					$fm = "$l_unknown_file_format";
				}
				elseif($fm == "photo_could_not_be_uploaded_please_check_file_size"){
					$fm = "$l_photo_could_not_be_uploaded_please_check_file_size";
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
				else{
					$fm = "$ft";
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->


		<p>$l_select_photo:<br />
		<input name=\"inp_image\" type=\"file\" tabindex=\"1\" />
		</p>

		<p>
		<input type=\"submit\" value=\"$l_upload\" tabindex=\"2\" class=\"btn\" />
		</p>

		</form>


		<!-- Go back -->
			<p><br />
			<a href=\"my_profile_cover_photo.php?l=$l\"><img src=\"$root/_webdesign/images/icons/16x16/go-previous.png\" alt=\"go-previous.png\" /></a>
			<a href=\"my_profile_cover_photo.php?l=$l\">$l_go_back</a>
			</p>
		<!-- //Go back -->



		";
	}
}
else{
	echo"
	<table>
	 <tr> 
	  <td style=\"padding-right: 6px;\">
		<p>
		<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"Loading\" />
		</p>
	  </td>
	  <td>
		<h1>Loading</h1>
	  </td>
	 </tr>
	</table>
		
	<meta http-equiv=\"refresh\" content=\"1;url=index.php\">
	";
}
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>