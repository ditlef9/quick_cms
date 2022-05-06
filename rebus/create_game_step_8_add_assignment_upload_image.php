<?php
/**
*
* File: rebus/create_game_step_8_add_assignment_upload_image.php
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

	// Finnes mappen?
	$upload_path = "$root/_uploads/rebus/games/$get_current_game_id";
	
	if(!(is_dir("$root/_uploads"))){
		mkdir("$root/_uploads");
	}
	if(!(is_dir("$root/_uploads/rebus"))){
		mkdir("$root/_uploads/rebus");
	}
	if(!(is_dir("$root/_uploads/rebus/games"))){
		mkdir("$root/_uploads/rebus/games");
	}
	if(!(is_dir("$root/_uploads/rebus/games/$get_current_game_id"))){
		mkdir("$root/_uploads/rebus/games/$get_current_game_id");
	}



	// Image folder
	$imageFolder = "$root/_uploads/rebus/games/$get_current_game_id/";

	reset ($_FILES);
	$temp = current($_FILES);
	if (is_uploaded_file($temp['tmp_name'])){
		if (isset($_SERVER['HTTP_ORIGIN'])) {
			// same-origin requests won't set an origin. If the origin is set, it must be valid.
		}

		// Sanitize input
		if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
			header("HTTP/1.1 400 Invalid file name.");
			return;
		}

		// Verify extension
		if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
			header("HTTP/1.1 400 Invalid extension.");
			return;
		}


		list($width,$height) = @getimagesize($temp['tmp_name']);
		if($width == "" OR $height == ""){
			header("HTTP/1.1 400 Invalid extension.");
			return;
		}

		// Get extension
		$inp_ext = get_extension($temp['name']);
		$inp_ext = output_html($inp_ext);
		$inp_ext_mysql = quote_smart($link, $inp_ext);

		// New name
		$name = $temp['name'];
		$name = str_replace(".$inp_ext", "", $name);
		$uniqid = uniqid();
		$new_name = $name . "_" . $uniqid . "." . $inp_ext;

		// Accept upload if there was no origin, or if it is an accepted origin
		$filetowrite = $imageFolder . $new_name;

		// Move it
		move_uploaded_file($temp['tmp_name'], $filetowrite);

		// Me
		$query = "SELECT user_id, user_email, user_name, user_alias, user_date_format FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_date_format) = $row;

		// Get my profile image
		$q = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id=$my_user_id_mysql AND photo_profile_image='1'";
		$r = mysqli_query($link, $q);
		$rowb = mysqli_fetch_row($r);
		list($get_my_photo_id, $get_my_photo_destination) = $rowb;

	
		$inp_title = $temp['name'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

		$inp_file_path = "_uploads/rebus/games/$get_current_game_id";
		$inp_file_path_mysql = quote_smart($link, $inp_file_path);

		$datetime = date("Y-m-d H:i:s");
		$date_saying = date("j M Y");

		//$inp_my_alias_mysql = quote_smart($link, $get_my_user_alias);
		//$inp_my_email_mysql = quote_smart($link, $get_my_user_email);
		//$inp_my_image_mysql = quote_smart($link, $get_my_photo_destination);

		$inp_file = output_html($new_name);
		$inp_file_mysql = quote_smart($link, $inp_file);
	

		// IP
		$my_ip = $_SERVER['REMOTE_ADDR'];
		$my_ip = output_html($my_ip);
		$my_ip_mysql = quote_smart($link, $my_ip);


				
		mysqli_query($link, "INSERT INTO $t_rebus_games_assignments_images
		(image_id, image_game_id, image_path, image_file, image_name, 
		image_uploaded_by_user_id, image_uploaded_by_ip, image_uploaded_datetime) 
		VALUES 
		(NULL, $get_current_game_id, $inp_file_path_mysql, $inp_file_mysql, $inp_title_mysql, 
		$my_user_id_mysql, $my_ip_mysql, '$datetime')")
		or die(mysqli_error($link));

		// Resize image if it is over 1024 in witdth
		if($width > 1024){
			$newwidth=1024;
			$newheight=($height/$width)*$newwidth; // 667
			resize_crop_image($newwidth, $newheight, $filetowrite, $filetowrite);
		}


		// Respond to the successful upload with JSON.
		// Use a location key to specify the path to the saved image resource.
		// { location : '/your/uploaded/image/file'}
		echo json_encode(array('location' => $filetowrite));
	} 
	else {
		// Notify editor that the upload failed
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
           			$fm = "photo_exceeds_filesize_form";
				break;
			default:
           			$fm = "unknown_upload_error";
				break;
		}
		header("HTTP/1.1 500 Server Error $fm");
		echo"HTTP/1.1 500 Server Error $fm";
	}
} // logged in
else{
	echo"<p>Please log in...</p>";
}

?>