<?php
/*- MySQL Tables -------------------------------------------------- */
$t_users 	 		= $mysqlPrefixSav . "users";
$t_users_profile 		= $mysqlPrefixSav . "users_profile";
$t_users_friends 		= $mysqlPrefixSav . "users_friends";
$t_users_friends_requests 	= $mysqlPrefixSav . "users_friends_requests";
$t_users_profile		= $mysqlPrefixSav . "users_profile";
$t_users_profile_photo 		= $mysqlPrefixSav . "users_profile_photo";
$t_users_status 		= $mysqlPrefixSav . "users_status";
$t_users_status_comments 	= $mysqlPrefixSav . "users_status_comments";
$t_users_status_comments_likes 	= $mysqlPrefixSav . "users_status_comments_likes";
$t_users_status_likes 		= $mysqlPrefixSav . "users_status_likes";

$t_users_profile_headlines			= $mysqlPrefixSav . "users_profile_headlines";
$t_users_profile_headlines_translations		= $mysqlPrefixSav . "users_profile_headlines_translations";
$t_users_profile_fields				= $mysqlPrefixSav . "users_profile_fields";
$t_users_profile_fields_translations		= $mysqlPrefixSav . "users_profile_fields_translations";
$t_users_profile_fields_options			= $mysqlPrefixSav . "users_profile_fields_options";
$t_users_profile_fields_options_translations	= $mysqlPrefixSav . "users_profile_fields_options_translations";


/*- Access check -------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Language ------------------------------------------------------ */
include("_translations/admin/$l/users/t_users_edit_user.php");


/*- Varialbes  ---------------------------------------------------- */
if(isset($_GET['user_id'])) {
	$user_id = $_GET['user_id'];
	$user_id = strip_tags(stripslashes($user_id));
}
else{
	$user_id = "";
	echo"
	<h1>Error</h1>

	<p>$l_user_profile_not_found</p>
	";
	die;
}
if(isset($_GET['mode'])) {
	$mode = $_GET['mode'];
	$mode = strip_tags(stripslashes($mode));
}
else{
	$mode = "";
}
if(isset($_GET['refer'])) {
	$refer = $_GET['refer'];
	$refer = strip_tags(stripslashes($refer));
}
else{
	$refer = "";
}

// Get user
$user_id_mysql = quote_smart($link, $user_id);

$query = "SELECT user_id, user_email, user_name, user_alias, user_password, user_salt, user_security, user_language, user_gender, user_measurement, user_dob, user_date_format, user_registered, user_last_online, user_rank, user_points, user_likes, user_dislikes, user_status, user_login_tries, user_last_ip, user_synchronized, user_verified_by_moderator FROM $t_users WHERE user_id=$user_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_password, $get_user_salt, $get_user_security, $get_user_language, $get_user_gender, $get_user_measurement, $get_user_dob, $get_user_date_format, $get_user_registered, $get_user_last_online, $get_user_rank, $get_user_points, $get_user_likes, $get_user_dislikes, $get_user_status, $get_user_login_tries, $get_user_last_ip, $get_user_synchronized, $get_user_verified_by_moderator) = $row;

if($get_user_id == ""){
	echo"<h1>Error</h1><p>Error with user id.</p>"; 
	die;
}

// Can I edit?
$my_user_id = $_SESSION['admin_user_id'];
$my_user_id = output_html($my_user_id);
$my_user_id_mysql = quote_smart($link, $my_user_id);

$my_security  = $_SESSION['admin_security'];
$my_security = output_html($my_security);
$my_security_mysql = quote_smart($link, $my_security);
$query = "SELECT user_id, user_name, user_language, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql AND user_security=$my_security_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_my_user_id, $get_my_user_name, $get_my_user_language, $get_my_user_rank) = $row;


if($get_my_user_rank != "moderator" && $get_my_user_rank != "admin"){
	echo"
	<h1>Server error 403</h1>
	<p>Your rank is $get_my_user_rank. You can not edit.</p>
	";
	die;
}

if($mode == ""){
	echo"
	<h1>$l_photos $get_user_name</h1>

	<!-- Menu -->
		";
		include("_inc/users/users_edit_user_menu.php");
		echo"
	<!-- //Menu -->


	<!-- Feedback -->
		";
		if($ft != "" && $fm != ""){
			if($fm == "photo_not_found"){
				$fm = "$l_photo_not_found";
			}
			elseif($fm == "photo_deleted"){
				$fm = "$l_photo_deleted";
			}
			else{
				$fm = str_replace("_", " ", $fm);
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><p>$fm</p></div>";
		}
		echo"
	<!-- //Feedback -->

	<!-- Upload photo -->
		

		<form method=\"POST\" action=\"index.php?open=users&amp;page=users_edit_user_photos&amp;action=$action&amp;mode=upload_photo&amp;user_id=$user_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

		<p><b>Upload photo:</b><br />
		<input name=\"inp_image\" type=\"file\" tabindex=\"1\" />
		
		<input type=\"submit\" value=\"Upload\" tabindex=\"3\" class=\"btn_default\" />
		</p>
		</form>
	<!-- //Upload photo -->

	<!-- Display photos -->
		<table>
		";
		$prev_photo_id = "";
		$query = "SELECT photo_id, photo_user_id, photo_profile_image, photo_title, photo_destination, photo_thumb_40, photo_thumb_50, photo_thumb_60, photo_thumb_200, photo_uploaded, photo_uploaded_ip, photo_views, photo_views_ip_block, photo_likes, photo_comments, photo_x_offset, photo_y_offset, photo_text FROM $t_users_profile_photo WHERE photo_user_id='$get_user_id'";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_photo_id, $get_photo_user_id, $get_photo_profile_image, $get_photo_title, $get_photo_destination, $get_photo_thumb_40, $get_photo_thumb_50, $get_photo_thumb_60, $get_photo_thumb_200, $get_photo_uploaded, $get_photo_uploaded_ip, $get_photo_views, $get_photo_views_ip_block, $get_photo_likes, $get_photo_comments, $get_photo_x_offset, $get_photo_y_offset, $get_photo_text) = $row;
			
			// Exists?
			if(!(file_exists("../_uploads/users/images/$get_user_id/$get_photo_destination"))){
				mysqli_query($link, "DELETE FROM $t_users_profile_photo WHERE photo_id=$get_photo_id") or die(mysqli_error($link));
			}
			else{
				if($get_photo_thumb_200 == ""){
						$extension = get_extension($get_photo_destination);
						$extension = strtolower($extension);
						$name = str_replace(".$extension", "", $get_photo_destination);
	
						// Small
						$thumb_a = $name . "_40." . $extension;
						$thumb_a_mysql = quote_smart($link, $thumb_a);

						// Medium
						$thumb_b = $name . "_50." . $extension;
						$thumb_b_mysql = quote_smart($link, $thumb_b);

						// Large
						$thumb_c = $name . "_60." . $extension;
						$thumb_c_mysql = quote_smart($link, $thumb_c);

						// Extra Large
						$thumb_d = $name . "_200." . $extension;
						$thumb_d_mysql = quote_smart($link, $thumb_d);
		
						// Update
						$result_update = mysqli_query($link, "UPDATE $t_users_profile_photo SET photo_thumb_40=$thumb_a_mysql, photo_thumb_50=$thumb_b_mysql, photo_thumb_60=$thumb_c_mysql, photo_thumb_200=$thumb_d_mysql WHERE photo_id=$get_photo_id");
				
						// Pass new variables
						$get_photo_thumb_40 = "$thumb_a";
						$get_photo_thumb_50 = "$thumb_b";
						$get_photo_thumb_60 = "$thumb_c";
						$get_photo_thumb_200 = "$thumb_d";
				}
				if(!(file_exists("../_uploads/users/images/$get_photo_user_id/$get_photo_thumb_200"))){
					// Thumb
					$inp_new_x = 200;
					$inp_new_y = 200;
					resize_crop_image($inp_new_x, $inp_new_y, "../_uploads/users/images/$get_photo_user_id/$get_photo_destination", "../_uploads/users/images/$get_photo_user_id/$get_photo_thumb_200");
				} // thumb

				echo"
				 <tr>
				  <td style=\"padding: 0px 6px 0px 0px;vertical-align:top;\">
					<p>
					<a id=\"photo$get_photo_id\"></a>
					<a href=\"../_uploads/users/images/$get_user_id/$get_photo_destination\"><img src=\"../_uploads/users/images/$get_user_id/$get_photo_thumb_200\" alt=\"$get_photo_destination\" /></a>
					</p>
				  </td>
				  <td style=\"padding: 0px 0px 0px 0px;vertical-align:top;\">
					<p>
					$l_uploaded: $get_photo_uploaded<br />
					$l_ip: $get_photo_uploaded_ip<br />
					$l_views: $get_photo_views<br />
					$l_likes: $get_photo_likes<br />
					$l_comments: $get_photo_comments<br />
					<a href=\"index.php?open=$open&amp;page=$page&amp;action=photos&amp;mode=delete_photo&amp;photo_id=$get_photo_id&amp;user_id=$user_id&amp;l=$l#photo$prev_photo_id\">$l_delete_this_photo</a>
					</p>
				  </td>
				 </tr>
				";
				$prev_photo_id = $get_photo_id;
			} // image exists
		} // query images
		echo"
		</table>
	<!-- //Display photos -->
	";
}
elseif($mode == "upload_photo"){
	// Create folders
	if(!(is_dir("../_uploads/"))){
		mkdir("../_uploads/", 0777);
	}
	if(!(is_dir("../_uploads/users/"))){
		mkdir("../_uploads/users/", 0777);
	}
	if(!(is_dir("../_uploads/users/images"))){
		mkdir("../_uploads/users/images", 0777);
	}
	if(!(is_dir("../_uploads/users/images/$user_id"))){
		mkdir("../_uploads/users/images/$user_id", 0777);
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
		$uploadedfile = $_FILES['inp_image']['tmp_name'];
				
		$filename = stripslashes($_FILES['inp_image']['name']);
		$extension = getExtension($filename);
		$extension = strtolower($extension);

		// Title
		$inp_title = str_replace($extension, "", $filename);
		$inp_title = str_replace("-", " ", $inp_title);
		$inp_title = str_replace("_", " ", $inp_title);
		$inp_title = ucfirst($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);


		if($image){
			if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
				$ft = "warning";
				$fm = "unknown_file_format";
				$url = "index.php?open=users&page=users_edit_user_photos&action=photos&user_id=$user_id&l=$l&ft=$ft&fm=$fm"; 
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
					
					$url = "photo_upload.php?l=$l&ft=$ft&fm=$fm"; 
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
					$filename = "../_uploads/users/images/$user_id/". $user_id . "_" . $datetime . "." . $extension;

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
					$url = "index.php?open=users&page=users_edit_user_photos&action=photos&user_id=$user_id&l=$l&ft=$ft&fm=$fm"; 
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
			$url = "index.php?open=users&page=users_edit_user_photos&action=photos&user_id=$user_id&l=$l&ft=$ft&fm=$fm"; 
			header("Location: $url");
			exit;
		}
	} // if($_SERVER["REQUEST_METHOD"] == "POST") {
}
elseif($mode == "delete_photo"){
	// Variables
	if(isset($_GET['photo_id'])) {
		$photo_id = $_GET['photo_id'];
		$photo_id = strip_tags(stripslashes($photo_id));
	}
	else{
		$photo_id = "";
	}
	if(isset($_GET['prev_photo_id'])) {
		$prev_photo_id = $_GET['prev_photo_id'];
		$prev_photo_id = strip_tags(stripslashes($prev_photo_id));
	}
	else{
		$prev_photo_id = "";
	}

	// Get photo id
	$photo_id_mysql = quote_smart($link, $photo_id);
	$query = "SELECT photo_id, photo_user_id, photo_profile_image, photo_title, photo_destination, photo_thumb_40, photo_thumb_50, photo_thumb_60, photo_thumb_200, photo_uploaded, photo_uploaded_ip, photo_views, photo_views_ip_block, photo_likes, photo_comments, photo_x_offset, photo_y_offset, photo_text FROM $t_users_profile_photo WHERE photo_id=$photo_id_mysql AND photo_user_id=$user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_photo_id, $get_photo_user_id, $get_photo_profile_image, $get_photo_title, $get_photo_destination, $get_photo_thumb_40, $get_photo_thumb_50, $get_photo_thumb_60, $get_photo_thumb_200, $get_photo_uploaded, $get_photo_uploaded_ip, $get_photo_views, $get_photo_views_ip_block, $get_photo_likes, $get_photo_comments, $get_photo_x_offset, $get_photo_y_offset, $get_photo_text) = $row;

	if($get_photo_id == ""){
		// Send warning
		$fm = "photo_not_found";
		$ft = "warning";
		echo"Photo not found";
	}
	else{

		if($process == "1"){
			if(!(file_exists("../_uploads/users/images/$get_user_id/$get_photo_destination"))){
				// Send warning
				$fm = "photo_not_found";
				$ft = "warning";

				$url = "index.php?open=users&page=users_edit_user_photos&action=photos&user_id=$user_id&l=$l&ft=$ft&fm=$fm"; 
				header("Location: $url");
				exit;

			}
			else{
	
				// Delete from MySQL
				$result = mysqli_query($link, "DELETE FROM $t_users_profile_photo WHERE photo_id='$get_photo_id'");

				// Delete photo
				unlink("../_uploads/users/images/$get_user_id/$get_photo_destination");

				// Delete thumb
				if(file_exists("../_uploads/users/images/$get_user_id/$get_photo_thumb_40") && $get_photo_thumb_40 != ""){
					unlink("../_uploads/users/images/$get_user_id/$get_photo_thumb_40");
				}
				if(file_exists("../_uploads/users/images/$get_user_id/$get_photo_thumb_50") && $get_photo_thumb_50 != ""){
					unlink("../_uploads/users/images/$get_user_id/$get_photo_thumb_50");
				}
				if(file_exists("../_uploads/users/images/$get_user_id/$get_photo_thumb_60") && $get_photo_thumb_60 != ""){
					unlink("../_uploads/users/images/$get_user_id/$get_photo_thumb_60");
				}
				if(file_exists("../_uploads/users/images/$get_user_id/$get_photo_thumb_200") && $get_photo_thumb_200 != ""){
					unlink("../_uploads/users/images/$get_user_id/$get_photo_thumb_200");
				}

				// Check if this was my profile photo
				if($get_photo_profile_image == "1"){
					// get a new photo to use as profile photo
					$query = "SELECT photo_id, photo_user_id, photo_profile_image, photo_destination FROM $t_users_profile_photo WHERE photo_user_id=$user_id_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_photo_id, $get_photo_user_id, $get_users_profile_photo, $get_photo_destination) = $row;
		
					if($get_photo_id != ""){
						$result = mysqli_query($link, "UPDATE $t_users_profile_photo SET photo_profile_image='1' WHERE photo_id=$get_photo_id");
					}
				}


				// Send success
				$fm = "photo_deleted";
				$ft = "success";

				$url = "index.php?open=users&page=users_edit_user_photos&action=photos&user_id=$user_id&l=$l&ft=$ft&fm=$fm"; 
				header("Location: $url");
				exit;
			}
		} // process == 1

		echo"

		<h1>$l_photos $get_user_name</h1>

		<!-- Menu -->
			";
			include("_inc/users/users_edit_user_menu.php");
			echo"
		<!-- //Menu -->


	

		<!-- Delete photos -->
			<p>Are you sure you want to delete the image?</p>

			<p>
			<a href=\"../_uploads/users/images/$get_user_id/$get_photo_destination\"><img src=\"../_uploads/users/images/$get_user_id/$get_photo_thumb_200\" alt=\"$get_photo_destination\" /></a>
			</p>
			
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=photos&amp;mode=delete_photo&amp;photo_id=$get_photo_id&amp;user_id=$user_id&amp;l=$l&amp;process=1\" class=\"btn_danger\">$l_delete_this_photo</a>
			</p>
		<!-- //Delete photo -->
		";
	} // image found
} // mode == delete image
?>