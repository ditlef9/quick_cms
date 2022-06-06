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
$website_title = "$l_lets_upload_your_image - $l_users";
include("$root/_webdesign/header.php");



/*- Content --------------------------------------------------------------------------- */

/*- Translations -------------------------------------------------------------------- */


// Get extention
function getExtension($str) {
	$i = strrpos($str,".");
	if (!$i) { return ""; } 
	$l = strlen($str) - $i;
	$ext = substr($str,$i+1,$l);
	return $ext;
}
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
				delete_cache($dirname.'/'.$file);    
			}
		}
	closedir($dir_handle);
	rmdir($dirname);
	return true;
}

if(isset($_SESSION['user_id'])){
	// Get user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$my_security = $_SESSION['security'];
	$my_security_mysql = quote_smart($link, $my_security);

	$query = "SELECT user_id, user_name, user_language, user_rank, user_points FROM $t_users WHERE user_id=$my_user_id_mysql AND user_security=$my_security_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_name, $get_my_user_language, $get_my_user_rank, $get_my_user_points) = $row;

	if($get_my_user_id == ""){
		echo"<h1>Error</h1><p>Error with user id.</p>"; 
		$_SESSION = array();
		session_destroy();
		die;
	}


	if($action == "upload" && $process == "1"){

		// Delete cache
		delete_cache("$root/_cache");
		if(!(is_dir("$root/_cache/"))){
			mkdir("$root/_cache/", 0777);
		}

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
		if(!(is_dir("$root/_uploads/users/images/$my_user_id"))){
			mkdir("$root/_uploads/users/images/$my_user_id", 0777);
		}
				
		// Sjekk filen
		$file_name = basename($_FILES['inp_image']['name']);
		$file_exp = explode('.', $file_name); 
		$file_type = $file_exp[count($file_exp) -1]; 
		$file_type = strtolower("$file_type");



		// Sett variabler
		$datetime = date("ymdhis");
		$new_name = $my_user_id . "_" . $datetime . "." . $file_type;
		
		$target_path = "$root/_uploads/users/images/$my_user_id/" . $new_name;

		// Sjekk om det er en OK filendelse
		if($file_type == "jpg" OR $file_type == "jpeg" OR $file_type == "png" OR $file_type == "gif"){
			if(move_uploaded_file($_FILES['inp_image']['tmp_name'], $target_path)) {

				// Sjekk om det faktisk er et bilde som er lastet opp
				list($width,$height) = getimagesize($target_path);
				if(is_numeric($width) && is_numeric($height)){
					// Check that file is big enough
					if($width < 99){
						$url = "create_free_account_step_6_image.php?l=$l&ft=error&fm=width_have_to_be_bigger&width=$width&height=$height";
						header("Location: $url");
						exit;
					}
					if($height < 99){
						$url = "create_free_account_step_6_image.php?l=$l&ft=error&fm=height_have_to_be_bigger&width=$width&height=$height";
						header("Location: $url");
						exit;
					}

					// Dette bildet er OK

					// Remove profile photo from old photos
					$result = mysqli_query($link, "UPDATE $t_users_profile_photo SET photo_profile_image='0' WHERE photo_user_id='$get_my_user_id'");
			
					// Photo title
					$inp_photo_title = "$get_my_user_name";
					$inp_photo_title_mysql = quote_smart($link, $inp_photo_title);

					// Insert to Mysql
					$inp_photo_destination = $my_user_id . "_" . $datetime . "." . $file_type;
					$inp_photo_destination_mysql = quote_smart($link, $inp_photo_destination);

					// Thumb
					$inp_photo_thumb_a = $my_user_id . "_" . $datetime . "_40." . $file_type;
					$inp_photo_thumb_a_mysql = quote_smart($link, $inp_photo_thumb_a);

					$inp_photo_thumb_b = $my_user_id . "_" . $datetime . "_50." . $file_type;
					$inp_photo_thumb_b_mysql = quote_smart($link, $inp_photo_thumb_b);

					$inp_photo_thumb_c = $my_user_id . "_" . $datetime . "_60." . $file_type;
					$inp_photo_thumb_c_mysql = quote_smart($link, $inp_photo_thumb_c);

					$inp_photo_thumb_d = $my_user_id . "_" . $datetime . "_200." . $file_type;
					$inp_photo_thumb_d_mysql = quote_smart($link, $inp_photo_thumb_d);
			
					$inp_photo_uploaded = date("Y-m-d H:i:s");

					$inp_photo_uploaded_ip = $_SERVER['REMOTE_ADDR'];
					$inp_photo_uploaded_ip = output_html($inp_photo_uploaded_ip);
					$inp_photo_uploaded_ip_mysql = quote_smart($link, $inp_photo_uploaded_ip);

					mysqli_query($link, "INSERT INTO $t_users_profile_photo
					(photo_id, photo_user_id, photo_profile_image, photo_title, photo_destination, photo_thumb_40, photo_thumb_50, photo_thumb_60, photo_thumb_200, 
					photo_uploaded, photo_uploaded_ip, photo_views, photo_views_ip_block, photo_likes, photo_comments, photo_x_offset, photo_y_offset, photo_text) 
					VALUES 
					(NULL, '$get_my_user_id', '1', $inp_photo_title_mysql, $inp_photo_destination_mysql, $inp_photo_thumb_a_mysql, $inp_photo_thumb_b_mysql, $inp_photo_thumb_c_mysql, $inp_photo_thumb_d_mysql, 
					'$inp_photo_uploaded', $inp_photo_uploaded_ip_mysql, 0, '', '0', '0', '0', '0', '')")
					or die(mysqli_error($link));

					// Give + 1 point
					$inp_points = $get_my_user_points+1;
					$result = mysqli_query($link, "UPDATE $t_users SET user_points='$inp_points' WHERE user_id='$get_my_user_id'");
					
							
					// Rezie image to 847x437
					if($width > 846){
						$newwidth=847;
						$newheight=($height/$width)*$newwidth; // 667
						$tmp=imagecreatetruecolor($newwidth,$newheight);
						
						if($file_type == "jpg" || $file_type == "jpeg" ){
							$src = imagecreatefromjpeg($target_path);
						}
						else{
							$src = imagecreatefrompng($target_path);
						}

						imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight, $width,$height);
						if($file_type == "jpg" || $file_type == "jpeg" ){
							imagejpeg($tmp, $target_path);
						}
						else{
							imagepng($tmp, $target_path);
						}
						
						imagedestroy($tmp);
					}



					$ft = "success";
					$fm = "photo_uploaded";
					$url = "create_free_account_step_6_image.php?action=photo_uploaded&l=$l&ft=$ft&fm=$fm"; 
					header("Location: $url");
					exit;
					
				}
				else{
					// Dette er en fil som har fått byttet filendelse...
					unlink("$target_path");

					$url = "create_free_account_step_6_image.php?l=$l&ft=error&fm=file_is_not_an_image";
					header("Location: $url");
					exit;
				}
			}
			else{
				switch ($_FILES['inp_image'] ['error']){
					case 1:
						$url = "create_free_account_step_6_image.php?l=$l&ft=error&fm=to_big_file";
						header("Location: $url");
						exit;
					case 2:
						$url = "create_free_account_step_6_image.php?l=$l&ft=error&fm=to_big_file";
						header("Location: $url");
						exit;
					case 3:
						$url = "create_free_account_step_6_image.php?l=$l&ft=error&fm=only_parts_uploaded";
						header("Location: $url");
						exit;
					case 4:
						$url = "create_free_account_step_6_image.php?l=$l&ft=error&fm=no_file_uploaded";
						header("Location: $url");
						exit;
				}
			} // if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
		} // if($file_type == "jpg" OR $file_type == "jpeg" OR $file_type == "png" OR $file_type == "gif"){
		else{
			$url = "create_free_account_step_6_image.php?l=$l&ft=error&fm=invalid_file_type&file_type=$file_type";
			header("Location: $url");
			exit;
		}
	}
	if($action == ""){
		echo"
		<h1>$l_hello $get_my_user_name</h1>

		<p>
		$l_smile_say_cheese
		</p>
		<p>
		$l_lets_upload_your_image
		</p>


		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "photo_not_found_in_database"){
					$fm = "$l_photo_not_found_in_database";
				}
				elseif($fm == "photo_not_found"){
					$fm = "$l_photo_not_found";
				}
				elseif($fm == "photo_deleted"){
					$fm = "$l_photo_deleted";
				}
				elseif($fm == "photo_rotated"){
					$fm = "$l_photo_rotated";
				}
				elseif($fm == "photo_uploaded"){
					$fm = "$l_photo_uploaded";
				}
				elseif($fm == "photo_sat_as_profile_photo"){
					$fm = "$l_photo_sat_as_profile_photo";
				}
				else{
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->

		<!-- Form -->
			<form method=\"POST\" action=\"create_free_account_step_6_image.php?action=upload&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>$l_select_your_profile_image:</b><br />
			<input type=\"file\" name=\"inp_image\" />

			<input type=\"submit\" value=\"$l_upload\" class=\"btn\" />
			</p>
			</form>

		<!-- //Form -->

		<hr />
		<p>
		<a href=\"view_profile.php?user_id=$my_user_id&amp;l=$l&amp;first_run=1\" class=\"btn btn_default\">$l_skip_this_step</a>
		</p>
		";
	}
	elseif($action == "photo_uploaded"){
		echo"
		<h1>$l_great_job $get_my_user_name!</h1>

		<p>$l_the_photo_looks_nice</p>

		
		";
		$query = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' ORDER BY photo_profile_image DESC";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_photo_id, $get_photo_destination) = $row;

		if(file_exists("$root/_uploads/users/images/$get_my_user_id/$get_photo_destination")){

			$inp_new_x = 150;
			$inp_new_y = 150;
			$thumb = "user_" . $get_photo_destination . "-" . $inp_new_x . "x" . $inp_new_y . "png";

			//if($get_photo_id != "" && !(file_exists("$root/_cache/$thumb"))){
			//	resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_my_user_id/$get_photo_destination", "$root/_cache/$thumb");
			//}
			echo"
			<p style=\"padding-top:0;margin-top:0;\">
			<img src=\"$root/image.php?width=150&amp;image=/_uploads/users/images/$get_my_user_id/$get_photo_destination\" alt=\"$root/_cache/$thumb\" />
		
			</p>
			";
		}

		echo"
		<p>
		<a href=\"create_free_account_step_6_image.php?action=rotate&amp;l=$l&amp;process=1\" class=\"btn btn_default\">$l_rotate</a>
		<a href=\"create_free_account_step_6_image.php?action=delete&amp;l=$l\" class=\"btn btn_default\">$l_delete</a>
		<a href=\"create_free_account_step_6_image.php?l=$l\" class=\"btn btn_default\">$l_upload_new_photo</a>
		</p>

		<p>
		<a href=\"view_profile.php?user_id=$my_user_id&amp;l=$l&amp;first_run=1\" class=\"btn btn_success\">$l_continue</a>
		</p>
		";
	}
	elseif($action == "rotate" && $process == "1"){
		// Delete cache
		delete_cache("$root/_cache");
		if(!(is_dir("$root/_cache/"))){
			mkdir("$root/_cache/", 0777);
		}
		
		$query = "SELECT photo_id, photo_user_id, photo_profile_image, photo_destination, photo_uploaded, photo_uploaded_ip, photo_views, photo_views_ip_block, photo_likes, photo_comments, photo_x_offset, photo_y_offset, photo_text FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' ORDER BY photo_profile_image DESC";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_photo_id, $get_photo_user_id, $get_photo_profile_image, $get_photo_destination, $get_photo_uploaded, $get_photo_uploaded_ip, $get_photo_views, $get_photo_views_ip_block, $get_photo_likes, $get_photo_comments, $get_photo_x_offset, $get_photo_y_offset, $get_photo_text) = $row;

		if(file_exists("$root/_uploads/users/images/$get_my_user_id/$get_photo_destination")){
			
			$extension = getExtension($get_photo_destination);
			$extension = strtolower($extension);


			if($extension == "jpg"){
				// Load
				$source = imagecreatefromjpeg("$root/_uploads/users/images/$get_my_user_id/$get_photo_destination");

				// Rotate
				$rotate = imagerotate($source, -90, 0);
	

				// Save
				imagejpeg($rotate, "$root/_uploads/users/images/$get_my_user_id/$get_photo_destination");
			}
			elseif($extension == "png"){
				// Load
				$source = imagecreatefrompng("$root/_uploads/users/images/$get_my_user_id/$get_photo_destination");

				// Bg
				$bgColor = imagecolorallocatealpha($source, 255, 255, 255, 127);

				// Rotate
				$rotate = imagerotate($source, -90, $bgColor);
		
	
				// Save
				imagesavealpha($rotate, true);
				imagepng($rotate, "$root/_uploads/users/images/$get_my_user_id/$get_photo_destination");

			}

			// Free the memory
			imagedestroy($source);


			// Rename it
			$datetime = date("ymdhis");
			$new_name = $get_my_user_id . "_" . $datetime . "." . $extension;
			$new_name_mysql = quote_smart($link, $new_name);
		
			// Rename
			rename("$root/_uploads/users/images/$get_my_user_id/$get_photo_destination", "$root/_uploads/users/images/$get_my_user_id/$new_name");

			// Update table
			$result = mysqli_query($link, "UPDATE $t_users_profile_photo SET photo_destination=$new_name_mysql WHERE photo_id='$get_photo_id'");


			$url = "create_free_account_step_6_image.php?action=photo_uploaded&l=$l";
			header("Location: $url");
			exit;

		} // photo exists
	} // rotate
	elseif($action == "delete"){
		$query = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' ORDER BY photo_profile_image DESC";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_photo_id, $get_photo_destination) = $row;

		if($get_photo_id != ""){


			if($process == "1"){
				unlink("$root/_uploads/users/images/$get_user_id/$get_photo_destination");
				$result = mysqli_query($link, "DELETE FROM $t_users_profile_photo WHERE photo_id='$get_photo_id'");


				$url = "create_free_account_step_6_image.php?ft=success&fm=photo_deleted&l=$l";
				header("Location: $url");
				exit;
				
			}


			echo"
			<h1>$l_delete_image</h1>
			";

			$inp_new_x = 150;
			$inp_new_y = 150;
			$thumb = "user_" . $get_photo_destination . "-" . $inp_new_x . "x" . $inp_new_y . "png";

			if(!(file_exists("$root/_cache/$thumb"))){
				resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_user_id/$get_photo_destination", "$root/_cache/$thumb");
			}
			echo"
			<p><br />
			<img src=\"$root/_cache/$thumb\" alt=\"$root/_cache/$thumb\" />
		
			</p>
			";

			echo"
			<p>
			$l_are_you_sure_you_want_to_delete
			</p>

			<p>
			<a href=\"create_free_account_step_6_image.php?action=delete&amp;l=$l&amp;process=1\" class=\"btn btn_warning\">$l_delete</a>
			<a href=\"create_free_account_step_6_image.php?action=photo_uploaded&amp;l=$l\" class=\"btn btn_default\">$l_cancel</a>
			</p>

			";
		}
	}
}
/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>