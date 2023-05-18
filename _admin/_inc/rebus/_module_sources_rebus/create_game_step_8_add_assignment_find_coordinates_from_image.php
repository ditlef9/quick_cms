<?php
/**
*
* File: rebus/create_game_step_8_add_assignment_find_coordinates_from_image.php
* Version 1.0.0.
* Date 09:50 01.07.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
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
include("_tables_rebus.php");

/*- Functions ------------------------------------------------------------------------- */
function getGps($exifCoord, $hemi) {

    $degrees = count($exifCoord) > 0 ? gps2Num($exifCoord[0]) : 0;
    $minutes = count($exifCoord) > 1 ? gps2Num($exifCoord[1]) : 0;
    $seconds = count($exifCoord) > 2 ? gps2Num($exifCoord[2]) : 0;

    $flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;

    return $flip * ($degrees + $minutes / 60 + $seconds / 3600);

}
function gps2Num($coordPart) {

    $parts = explode('/', $coordPart);

    if (count($parts) <= 0)
        return 0;

    if (count($parts) == 1)
        return $parts[0];

	$parts0 = floatval($parts[0]);
	$parts1 = floatval($parts[1]);
    
	if($parts1 == 0){
		return 0;
	}
	else{
		return $parts0 / $parts1;
	}
}

/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);

if(isset($_GET['game_id'])) {
	$game_id = $_GET['game_id'];
	$game_id = output_html($game_id);
	if(!(is_numeric($game_id))){
		echo"Game id not numeric";
		die;
	}
}
else{
	echo"Missing game id";
	die;
}

$tabindex = 0;

// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);


	$query = "SELECT user_id, user_name, user_alias, user_language, user_measurement, user_date_format, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_name, $get_my_user_alias, $get_my_user_language, $get_my_user_measurement, $get_my_user_date_format, $get_my_user_rank) = $row;




	/*- Find game ------------------------------------------------------------------------- */
	$game_id_mysql = quote_smart($link, $game_id);
	$query = "SELECT game_id, game_title, game_language, game_description, game_privacy, game_published, game_group_id, game_group_name, game_times_played, game_image_path, game_image_file, game_created_by_user_id, game_created_by_user_name, game_created_by_user_email, game_created_by_ip, game_created_by_hostname, game_created_by_user_agent, game_created_datetime, game_created_date_saying, game_updated_by_user_id, game_updated_by_user_name, game_updated_by_user_email, game_updated_by_ip, game_updated_by_hostname, game_updated_by_user_agent, game_updated_datetime, game_updated_date_saying FROM $t_rebus_games_index WHERE game_id=$game_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_game_id, $get_current_game_title, $get_current_game_language, $get_current_game_description, $get_current_game_privacy, $get_current_game_published, $get_current_game_group_id, $get_current_game_group_name, $get_current_game_times_played, $get_current_game_image_path, $get_current_game_image_file, $get_current_game_created_by_user_id, $get_current_game_created_by_user_name, $get_current_game_created_by_user_email, $get_current_game_created_by_ip, $get_current_game_created_by_hostname, $get_current_game_created_by_user_agent, $get_current_game_created_datetime, $get_current_game_created_date_saying, $get_current_game_updated_by_user_id, $get_current_game_updated_by_user_name, $get_current_game_updated_by_user_email, $get_current_game_updated_by_ip, $get_current_game_updated_by_hostname, $get_current_game_updated_by_user_agent, $get_current_game_updated_datetime, $get_current_game_updated_date_saying) = $row;
	if($get_current_game_id == ""){
		$url = "index.php?ft=error&fm=game_not_found&l=$l";
		header("Location: $url");
		exit;
	}

	/*- Check that I am a owner of this game --------------------------------------------- */
	$query = "SELECT owner_id, owner_game_id, owner_user_id, owner_user_name, owner_user_email FROM $t_rebus_games_owners WHERE owner_game_id=$get_current_game_id AND owner_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_owner_id, $get_my_owner_game_id, $get_my_owner_user_id, $get_my_owner_user_name, $get_my_owner_user_email) = $row;
	if($get_my_owner_id == ""){
		$url = "index.php?ft=error&fm=your_not_a_owner_of_that_game&l=$l";
		header("Location: $url");
		exit;
	}

	// Upload image
	$valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp' , 'pdf' , 'doc' , 'ppt'); // valid extensions
	if(!(is_dir("$root/_cache"))){
		mkdir("$root/_cache");
	}
	$upload_path = "$root/_cache"; // upload directory

	if(!(isset($_FILES['file']))){
		echo"<div class=\"error\"><p>No image selected</p></div>";
		die;
	}
	$img = $_FILES['file']['name'];
	$tmp = $_FILES['file']['tmp_name'];

	// get uploaded file's extension
	$ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

	// can upload same image using rand function
	$image_name = rand(1000,1000000).$img;

	$uploaded_file = $upload_path . "/" . $image_name;

	// check's valid format
	if(in_array($ext, $valid_extensions)) { 
		if(move_uploaded_file($tmp, $uploaded_file)) {
			// Check width and height
			list($width,$height) = getimagesize($uploaded_file);
			if($width == "" OR $height == ""){
				unlink("$filename");
				echo"<div class=\"error\"><p>image_could_not_be_uploaded_please_check_file_size</p></div>";
				die;
			}

			// Read exif
			$exif = @exif_read_data($uploaded_file); 
			
			if($exif == ""){
				unlink($uploaded_file);
				echo"<div class=\"error\"><p>Could not get exif data from image</p></div>";
				die;
			}

			
			if(!(isset($exif["GPSLongitude"]))){
				unlink($uploaded_file);
				echo"<div class=\"error\"><p>Could not find longitude from image</p></div>";
				die;
			}

			// Long lat 
			$gps_longitude = getGps($exif["GPSLongitude"], $exif['GPSLongitudeRef']);
			$gps_latitude = getGps($exif["GPSLatitude"], $exif['GPSLatitudeRef']);

			
			// Resize to 1280x720
			$uploaded_file_new = $uploaded_file;
			if($width > "250" OR $height > "250"){
				resize_crop_image(250, 250, $uploaded_file, $uploaded_file_new, $quality = 80);
			}

			echo"
			<div class=\"info\">
				<p><img src=\"$uploaded_file_new\" alt=\"$uploaded_file_new\" /><br />
				Found coordinates $gps_latitude, $gps_longitude</p></div>

			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_answer_b\"]').val($gps_longitude);
				\$('[name=\"inp_answer_a\"]').val($gps_latitude);
				\$('[name=\"inp_answer_a\"]').focus();


				// Change map
				


			});
			</script>

			";
			

		} // move_uploaded_file ok
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
			echo"<div class=\"$ft\"><p>$fm</p></div>";
			
		} // move_uploaded_file failed
	}
	else{
		echo"<div class=\"error\"><p>Invalid extension</p></div>";
	}


} // logged in
else{
	echo"<p>Please log in...</p>";
}

?>