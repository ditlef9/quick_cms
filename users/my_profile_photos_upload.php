<?php
/**
*
* File: users/index.php
* Version 17.46 18.02.2017
* Copyright (c) 2009-2017 Sindre Andre Ditlefsen
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
$website_title = "$l_upload_photo - $l_my_profile - $l_users";
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
		if(!(is_dir("$root/_uploads/"))){
			mkdir("$root/_uploads/", 0777);
		}
		if(!(is_dir("$root/_uploads/users/"))){
			mkdir("$root/_uploads/users/", 0777);
		}
		if(!(is_dir("$root/_uploads/users/images"))){
			mkdir("$root/_uploads/users/images", 0777);
		}
		if(!(is_dir("$root/_uploads/users/images/$user_id"))){
			mkdir("$root/_uploads/users/images/$user_id", 0777);
		}


		// Get extention
		function getExtension($str) {
			$i = strrpos($str,".");
			if (!$i) { return ""; } 
			$l = strlen($str) - $i;
			$ext = substr($str,$i+1,$l);
			return $ext;
		}

		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

		// Upload
		if($_SERVER["REQUEST_METHOD"] == "POST") {
			/*- Front -----------------------------------------------------------------------------------------------------------------------*/
			$image = $_FILES['inp_image']['name'];
			$uploadedfile = $_FILES['inp_image']['tmp_name'];
				

			$filename = stripslashes($_FILES['inp_image']['name']);
			$extension = getExtension($filename);
			$extension = strtolower($extension);

			if($image){

				if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
					$ft = "warning";
					$fm = "unknown_file_format";
					$url = "my_profile_photos_upload.php?l=$l&ft=$ft&fm=$fm"; 
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
					
						$url = "my_profile_photos_upload.php?l=$l&ft=$ft&fm=$fm"; 
						header("Location: $url");
						exit;


					}
					else{
 




						// Keep orginal
						if($width > 969){
							$newwidth=970;
						}
						else{
							$newwidth=$width;
						}
						$newheight=round(($height/$width)*$newwidth, 0);
						$tmp_org =imagecreatetruecolor($newwidth,$newheight);

						imagecopyresampled($tmp_org,$src,0,0,0,0,$newwidth,$newheight, $width,$height);

						$datetime = date("ymdhis");
						$filename = "$root/_uploads/users/images/$user_id/". $user_id . "_" . $datetime . "." . $extension;

						imagejpeg($tmp_org,$filename,100);

					
						imagedestroy($tmp_org);

						// Remove profile photo from old photos
						$result = mysqli_query($link, "UPDATE $t_users_profile_photo SET photo_profile_image='0' WHERE photo_user_id='$get_user_id'");
			

						// Insert to Mysql
						$inp_photo_destination = $user_id . "_" . $datetime . "." . $extension;
						$inp_photo_destination_mysql = quote_smart($link, $inp_photo_destination);
			
						// Thumb
						$inp_photo_thumb_a = $user_id . "_" . $datetime . "_40." . $extension;
						$inp_photo_thumb_a_mysql = quote_smart($link, $inp_photo_thumb_a);

						$inp_photo_thumb_b = $user_id . "_" . $datetime . "_50." . $extension;
						$inp_photo_thumb_b_mysql = quote_smart($link, $inp_photo_thumb_b);

						$inp_photo_thumb_c = $user_id . "_" . $datetime . "_60." . $extension;
						$inp_photo_thumb_c_mysql = quote_smart($link, $inp_photo_thumb_c);

						$inp_photo_thumb_d = $user_id . "_" . $datetime . "_200." . $extension;
						$inp_photo_thumb_d_mysql = quote_smart($link, $inp_photo_thumb_d);

						$inp_photo_uploaded = date("Y-m-d H:i:s");

						$inp_photo_uploaded_ip = $_SERVER['REMOTE_ADDR'];
						$inp_photo_uploaded_ip = output_html($inp_photo_uploaded_ip);
						$inp_photo_uploaded_ip_mysql = quote_smart($link, $inp_photo_uploaded_ip);


						mysqli_query($link, "INSERT INTO $t_users_profile_photo
						(photo_id, photo_user_id, photo_profile_image, photo_title, photo_destination, photo_thumb_40, photo_thumb_50, photo_thumb_60, photo_thumb_200, 
						photo_uploaded, photo_uploaded_ip, photo_views, photo_views_ip_block, photo_likes, photo_comments, photo_x_offset, photo_y_offset, photo_text) 
						VALUES 
						(NULL, '$get_user_id', '1', $inp_title_mysql, $inp_photo_destination_mysql, $inp_photo_thumb_a_mysql, $inp_photo_thumb_b_mysql, $inp_photo_thumb_c_mysql, $inp_photo_thumb_d_mysql,
						'$inp_photo_uploaded', $inp_photo_uploaded_ip_mysql, 0, '', '0', '0', '0', '0', '')")
						or die(mysqli_error($link));
						

						// Send feedback
						$ft = "success";
						$fm = "photo_uploaded";
						$url = "my_profile_photos.php?l=$l&ft=$ft&fm=$fm"; 
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
				$url = "my_profile_photos_upload.php?l=$l&ft=$ft&fm=$fm"; 
				header("Location: $url");
				exit;
				
			}

		} // if($_SERVER["REQUEST_METHOD"] == "POST") {
	}
	if($action == ""){
		echo"
		<h1>$l_upload_photo</h1>

		<!-- You are here -->
			<div class=\"you_are_here\">
				<p>
				<b>$l_you_are_here:</b><br />
				<a href=\"my_profile.php?l=$l\">$l_my_profile</a>
				&gt; 
				<a href=\"my_profile_photos.php?l=$l\">$l_photo</a>
				&gt; 
				<a href=\"my_profile_photos_upload.php?l=$l\">$l_upload_photo</a>
				</p>
			</div>
		<!-- //You are here -->


		<form method=\"POST\" action=\"my_profile_photos_upload.php?action=upload&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

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

		<p>$l_title<br />
		<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" tabindex=\"2\" />
		</p>

		<p>
		<input type=\"submit\" value=\"$l_upload\" tabindex=\"3\" />
		</p>
		</form>


		<!-- Go back -->
			<p><br />
			<a href=\"my_profile_photos.php?l=$l\"><img src=\"$root/users/_gfx/go-previous.png\" alt=\"go-previous.png\" /></a>
			<a href=\"my_profile_photos.php?l=$l\">$l_go_back</a>
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