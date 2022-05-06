<?php
/**
*
* File: rebus/my_games_edit_image.php
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



	/*- Find game ------------------------------------------------------------------------- */
	$game_id_mysql = quote_smart($link, $game_id);
	$query = "SELECT game_id, game_title, game_language, game_introduction, game_description, game_privacy, game_published, game_playable_after_datetime, game_playable_after_time, game_group_id, game_group_name, game_times_played, game_image_path, game_image_file, game_image_thumb_278x156, game_image_thumb_570x321, game_image_thumb_570x380, game_created_by_user_id, game_created_by_user_name, game_created_by_user_email, game_created_by_ip, game_created_by_hostname, game_created_by_user_agent, game_created_datetime, game_created_date_saying, game_updated_by_user_id, game_updated_by_user_name, game_updated_by_user_email, game_updated_by_ip, game_updated_by_hostname, game_updated_by_user_agent, game_updated_datetime, game_updated_date_saying FROM $t_rebus_games_index WHERE game_id=$game_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_game_id, $get_current_game_title, $get_current_game_language, $get_current_game_introduction, $get_current_game_description, $get_current_game_privacy, $get_current_game_published, $get_current_game_playable_after_datetime, $get_current_game_playable_after_time, $get_current_game_group_id, $get_current_game_group_name, $get_current_game_times_played, $get_current_game_image_path, $get_current_game_image_file, $get_current_game_image_thumb_278x156, $get_current_game_image_thumb_570x321, $get_current_game_image_thumb_570x380, $get_current_game_created_by_user_id, $get_current_game_created_by_user_name, $get_current_game_created_by_user_email, $get_current_game_created_by_ip, $get_current_game_created_by_hostname, $get_current_game_created_by_user_agent, $get_current_game_created_datetime, $get_current_game_created_date_saying, $get_current_game_updated_by_user_id, $get_current_game_updated_by_user_name, $get_current_game_updated_by_user_email, $get_current_game_updated_by_ip, $get_current_game_updated_by_hostname, $get_current_game_updated_by_user_agent, $get_current_game_updated_datetime, $get_current_game_updated_date_saying) = $row;
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


	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$l_image - $get_current_game_title - $l_my_games";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");
	
	if($process == "1"){
		// Dates
		$datetime = date("Y-m-d H:i:s");
		$date_saying = date("j M Y");

		// Me
		$query = "SELECT user_id, user_email, user_name, user_language, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_language, $get_my_user_rank) = $row;
			
		$inp_my_user_name_mysql = quote_smart($link, $get_my_user_name);
		$inp_my_user_email_mysql = quote_smart($link, $get_my_user_email);

		// Ip 
		$my_ip = $_SERVER['REMOTE_ADDR'];
		$my_ip = output_html($my_ip);
		$my_ip_mysql = quote_smart($link, $my_ip);

		$my_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$my_hostname = output_html($my_hostname);
		$my_hostname_mysql = quote_smart($link, $my_hostname);

		$my_user_agent = $_SERVER['HTTP_USER_AGENT'];
		$my_user_agent = output_html($my_user_agent);
		$my_user_agent_mysql = quote_smart($link, $my_user_agent);


		// Directory for storing
		if(!(is_dir("../_uploads"))){
			mkdir("../_uploads");
		}
		if(!(is_dir("../_uploads/rebus"))){
			mkdir("../_uploads/rebus");
		}
		if(!(is_dir("../_uploads/rebus/games"))){
			mkdir("../_uploads/rebus/games");
		}
		if(!(is_dir("../_uploads/rebus/games/$get_current_game_id"))){
			mkdir("../_uploads/rebus/games/$get_current_game_id");
		}
	
		/*- Image upload ------------------------------------------------------------------------------------------ */
		$name = stripslashes($_FILES['inp_image']['name']);
		$extension = get_extension($name);
		$extension = strtolower($extension);

		$ft_image = "";
		$fm_image = "";
		if($name){
			if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
				$ft_image = "warning";
				$fm_image = "unknown_file_extension";
			}
			else{
				// Read exif
				$exif = exif_read_data($_FILES['inp_image']['tmp_name']);

				$new_path = "../_uploads/rebus/games/$get_current_game_id/";
				$new_name = date("ymdhis");
				$uploaded_file = $new_path . $new_name . "." . $extension;

				// Upload file
				if (move_uploaded_file($_FILES['inp_image']['tmp_name'], $uploaded_file)) {
					// Get image size
					$file_size = filesize($uploaded_file);
						
					// Check with and height
					list($width,$height) = getimagesize($uploaded_file);
	
					if($width == "" OR $height == ""){
						$ft_image = "warning";
						$fm_image = "getimagesize_failed";
						unlink($uploaded_file);
					}
					else{
						// Check rotation
						$rotation = 0;
						if (!empty($exif['Orientation'])) {
							switch ($exif['Orientation']) {
								case 3:
									$rotation = 180;
									break;
								case 6:
									$rotation = -90;
									break;
								case 8:
									$rotation = 90;
									break;
								default:
									$rotation = 0;
							}
						}

						// Resize to 1280x720
						$uploaded_file_new = $uploaded_file;
						if($width > "1281" OR $height > "720"){
							$resize_width = "1280";
							$resize_height = "720";
							if($rotation != "0"){
								$resize_height = "1280";
							}
							resize_crop_image($resize_width, $resize_height, $uploaded_file, $uploaded_file_new, $quality = 80);
						}

						// Rotation
						if($rotation != "0"){
							$image_resource = imagecreatefromjpeg($uploaded_file);
							$image = imagerotate($image_resource, $rotation, 0);
							imagejpeg($image, $uploaded_file, 90);
							imagedestroy($image_resource);
							@imagedestroy($image);
							resize_crop_image(1280, 720, $uploaded_file, $uploaded_file_new, $quality = 80);
						}
					
						// MySQL
						$inp_path = "_uploads/rebus/games/$get_current_game_id";
						$inp_path = output_html($inp_path);
						$inp_path_mysql = quote_smart($link, $inp_path);

						$inp_file_mysql = quote_smart($link, $new_name . "." . $extension);


						mysqli_query($link, "UPDATE $t_rebus_games_index SET
									game_image_path=$inp_path_mysql, 
									game_image_file=$inp_file_mysql, 
									game_updated_by_user_id=$get_my_user_id,
					 				game_updated_by_user_name=$inp_my_user_name_mysql,
					 				game_updated_by_user_email=$inp_my_user_email_mysql,
					 				game_updated_by_ip=$my_ip_mysql,
					 				game_updated_by_hostname=$my_hostname_mysql,
					 				game_updated_by_user_agent=$my_user_agent_mysql,
					 				game_updated_datetime='$datetime',
					 				game_updated_date_saying='$date_saying'
									WHERE game_id=$get_current_game_id")
									or die(mysqli_error($link));


						// Delete old image
						if(file_exists("../$get_current_game_image_path/$get_current_game_image_file") && $get_current_game_image_file != ""){
							unlink("../$get_current_game_image_path/$get_current_game_image_file");
						}
						if(file_exists("../$get_current_game_image_path/$get_current_game_image_thumb_278x156") && $get_current_game_image_thumb_278x156 != ""){
							unlink("../$get_current_game_image_path/$get_current_game_image_thumb_278x156");
						}
						if(file_exists("../$get_current_game_image_path/$get_current_game_image_thumb_570x321") && $get_current_game_image_thumb_570x321 != ""){
							unlink("../$get_current_game_image_path/$get_current_game_image_thumb_570x321");
						}
						if(file_exists("../$get_current_game_image_path/$get_current_game_image_thumb_570x380") && $get_current_game_image_thumb_570x380 != ""){
							unlink("../$get_current_game_image_path/$get_current_game_image_thumb_570x380");
						}


						$ft_image = "success";
						$fm_image = "image_uploaded";
					}
				} // move_uploaded_file
				else{
					$ft_image = "error";
					switch ($_FILES['inp_image']['error']) {
						case UPLOAD_ERR_OK:
           						$fm_image = "There is no error, the file uploaded with success.";
							break;
						case UPLOAD_ERR_NO_FILE:
           						$fm_image = "no_file_uploaded";
							break;
						case UPLOAD_ERR_INI_SIZE:
           						$fm_image = "to_big_size_in_configuration";
							break;
						case UPLOAD_ERR_FORM_SIZE:
           						$fm_image = "to_big_size_in_form";
							break;
						default:
           						$fm_image = "unknown_error";
							break;
					}	
				}
			}
		} // name

		// Header
		$url = "edit_game_image.php?game_id=$get_current_game_id&l=$l&ft=$ft_image&fm=$fm_image";
		header("Location: $url");
		exit;

	}


	echo"
	<!-- Headline -->
		<h1>$get_current_game_title</h1>
	<!-- //Headline -->

	<!-- Where am I ? -->
		<p><b>$l_you_are_here:</b><br />
		<a href=\"index.php?l=$l\">$l_rebus</a>
		&gt;
		<a href=\"my_games.php?l=$l\">$l_my_games</a>
		&gt;
		<a href=\"edit_game.php?game_id=$get_current_game_id&amp;l=$l\">$get_current_game_title</a>
		&gt;
		<a href=\"edit_game_image.php?game_id=$get_current_game_id&amp;l=$l\">$l_image</a>
		</p>
	<!-- //Where am I ? -->

	<!-- Feedback -->
		";
		if($ft != "" && $fm != ""){
			$fm = ucfirst($fm);
			$fm = str_replace("_", " ", $fm);
			echo"<div class=\"$ft\"><p>$fm</p>";

			echo"</div>";
		}
		echo"
	<!-- //Feedback -->

	<!-- Focus -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_title\"]').focus();
		});
		</script>
	<!-- //Focus -->


	<!-- Existing image -->";
		if(file_exists("../$get_current_game_image_path/$get_current_game_image_file") && $get_current_game_image_file != ""){
			echo"<img src=\"../$get_current_game_image_path/$get_current_game_image_file\" alt=\"$get_current_game_image_file\" /><br />\n";
		}
	echo"
	<!-- //Existing image -->
			

		<!-- Add image form -->
			<form method=\"post\" action=\"edit_game_image.php?game_id=$get_current_game_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

		
			<p><b>$l_image ($l_image_will_be_resized_to 1280x720):</b><br />
			<input type=\"file\" name=\"inp_image\" /> 
			<input type=\"submit\" value=\"$l_upload\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</p>

	
			</form>
			
		<!-- //Add image form -->
	";
}
else{
	echo"
	<h1>
	<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=rebus/my_games.php\">

	<p>Please log in...</p>
	";
}

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>