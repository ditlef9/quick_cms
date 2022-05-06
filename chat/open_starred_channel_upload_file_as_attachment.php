<?php 
/**
*
* File: chat/open_starred_channel_upload_file_as_attachment.php
* Version 1.0.0
* Date 14:27 07.01.2020
* Copyright (c) 2020 S. A. Ditlefsen
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
include("$root/_admin/_data/chat.php");


/*- Tables ---------------------------------------------------------------------------- */
include("_tables_chat.php");

/*- Functions ------------------------------------------------------------------------- */
if($chatEncryptionMethodChannelsSav == "openssl_encrypt(AES-128-CBC)"){
	include("_encrypt_decrypt/openssl_encrypt_aes-128-cbc.php");
}
elseif($chatEncryptionMethodChannelsSav == "caesar_cipher(random)"){
	include("_encrypt_decrypt/caesar_cipher.php");
}

/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);




/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['starred_channel_id'])){
	$starred_channel_id = $_GET['starred_channel_id'];
	$starred_channel_id = output_html($starred_channel_id);
}
else{
	$starred_channel_id = "";
}
if($starred_channel_id == ""){
	$url = "my_starred_channels.php?ft=error&fm=No_Starred_Channel_selected&l=$l";
	header("Location: $url");
	exit;
}
$starred_channel_id_mysql = quote_smart($link, $starred_channel_id);

if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);

	// Get starred
	$query = "SELECT starred_channel_id, channel_id, channel_name, new_messages, user_id FROM $t_chat_users_starred_channels WHERE starred_channel_id=$starred_channel_id_mysql AND user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_starred_channel_id, $get_current_channel_id, $get_current_channel_name, $get_current_new_messages, $get_current_user_id) = $row;

	if($get_current_starred_channel_id == ""){
		echo"
		<p><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" /> Loading...</p>
		<meta http-equiv=\"refresh\" content=\"1;url=my_starred_channels.php?ft=error&fm=Starred_Channel_not_found_($starred_channel_id)&l=$l\">
		";
	}
	else{
		// Find channel
		$query = "SELECT channel_id, channel_name, channel_password, channel_last_message_time, channel_encryption_key, channel_encryption_key_year, channel_encryption_key_month FROM $t_chat_channels_index WHERE channel_id=$get_current_channel_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_channel_id, $get_current_channel_name, $get_current_channel_password, $get_current_channel_last_message_time, $get_current_channel_encryption_key, $get_current_channel_encryption_key_year, $get_current_channel_encryption_key_month) = $row;

		if($get_current_channel_id == ""){
			echo"<h1>Channel not found</h1>";
			// Delete refrence
			$result_del = mysqli_query($link, "DELETE FROM $t_chat_users_starred_channels WHERE starred_channel_id=$get_current_starred_channel_id");

			echo"
			<p><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" /> Loading...</p>
			<meta http-equiv=\"refresh\" content=\"1;url=my_starred_channels.php?l=$l\">
			";
		}
		else{
			
			// Dates
			$datetime_clean = date("YmdHis");
				
			// My IP
			$inp_my_ip = $_SERVER['REMOTE_ADDR'];
			$inp_my_ip = output_html($inp_my_ip);
			$inp_my_ip_mysql = quote_smart($link, $inp_my_ip);

			$inp_my_hostname = "";
			if($configSiteUseGethostbyaddrSav == "1"){
				$inp_my_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
			}
			$inp_my_hostname = output_html($inp_my_hostname);
			$inp_my_hostname_mysql = quote_smart($link, $inp_my_hostname);

			$inp_my_user_agent = $_SERVER['HTTP_USER_AGENT'];
			$inp_my_user_agent = output_html($inp_my_user_agent);
			$inp_my_user_agent_mysql = quote_smart($link, $inp_my_user_agent);

			// Me
			$query = "SELECT user_id, user_email, user_name, user_alias, user_language, user_last_online, user_rank, user_login_tries FROM $t_users WHERE user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_language, $get_my_user_last_online, $get_my_user_rank, $get_my_user_login_tries) = $row;
					
			// My photo
			$query = "SELECT photo_id, photo_destination, photo_thumb_40, photo_thumb_50 FROM $t_users_profile_photo WHERE photo_user_id=$my_user_id_mysql AND photo_profile_image='1'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_40, $get_my_photo_thumb_50) = $row;

			$inp_my_user_name_mysql = quote_smart($link, $get_my_user_name);
			$inp_my_user_alias_mysql = quote_smart($link, $get_my_user_alias);

			$inp_my_user_image_path = "_uploads/users/images/$get_my_user_id";
			$inp_my_user_image_path_mysql = quote_smart($link, $inp_my_user_image_path);

			$inp_my_user_image_file_mysql = quote_smart($link, $get_my_photo_destination);

			$inp_my_user_image_thumb_a_mysql = quote_smart($link, $get_my_photo_thumb_40);
			$inp_my_user_image_thumb_b_mysql = quote_smart($link, $get_my_photo_thumb_50);


			// Dates
			$datetime = date("Y-m-d H:i:s");
			$time = time();
			$year = date("Y");
			$date_saying = date("j M Y");
			$datetime_saying = date("j M Y H:i");
			$time_saying = date("H:i");
		
			// Check for file (to be attatched to message)
			if(!(is_dir("$root/_uploads/"))){
				mkdir("$root/_uploads/", 0777);
			}
			if(!(is_dir("$root/_uploads/chat/"))){
				mkdir("$root/_uploads/chat/", 0777);
			}
			if(!(is_dir("$root/_uploads/chat/attachments"))){
				mkdir("$root/_uploads/chat/attachments", 0777);
			}
			if(!(is_dir("$root/_uploads/chat/attachments/channel_$get_current_channel_id"))){
				mkdir("$root/_uploads/chat/attachments/channel_$get_current_channel_id", 0777);
			}
		
			$allowed_image_formats = array("jpg", "jpeg", "png", "gif");
			$allowed_other_formats = array(
    						"pdf"  => "application/pdf",
    						"odt"  => "application/vnd.oasis.opendocument.text",
    						"docx"  => "application/octet-stream",
   						"txt"  => "text/plain"
					);
	

			$inp_file_name = $_FILES['inp_file']['name'];
			$extension = get_extension($inp_file_name);
			$extension = strtolower($extension);
			if($inp_file_name){	
				if (in_array($extension, $allowed_image_formats)) {

					$size=filesize($_FILES['inp_file']['tmp_name']);
					if($extension=="jpg" || $extension=="jpeg" ){
						ini_set ('gd.jpeg_ignore_warning', 1);
						error_reporting(0);
						$uploadedfile = $_FILES['inp_file']['tmp_name'];
						$src = imagecreatefromjpeg($uploadedfile);
					}
					elseif($extension=="png"){
						$uploadedfile = $_FILES['inp_file']['tmp_name'];
						$src = @imagecreatefrompng($uploadedfile);
					}
					else{
						$src = @imagecreatefromgif($uploadedfile);
					}

					list($width,$height) = @getimagesize($uploadedfile);
					if($width == "" OR $height == ""){
						$ft = "warning";
						$fm = "photo_could_not_be_uploaded_please_check_file_size";
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
						$filename = "$root/_uploads/chat/attachments/channel_$get_current_channel_id/". $get_my_user_id . "_" . $datetime_clean . "." . $extension;

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


						// Store
						$inp_text = output_html($inp_file_name);

						// Encrypt text
						if($chatEncryptionMethodChannelsSav == "none"){
							$inp_text_mysql = quote_smart($link, $inp_text);
						}
						elseif($chatEncryptionMethodChannelsSav == "openssl_encrypt(AES-128-CBC)"){
							$inp_text_encrypted = openssl_encrypt_aes_128_cbc_encrypt($inp_text, $get_current_channel_encryption_key);
							$inp_text_mysql = quote_smart($link, $inp_text_encrypted);
						}
						elseif($chatEncryptionMethodChannelsSav == "caesar_cipher(random)"){
							$cipher = new KKiernan\CaesarCipher(); 
							$inp_text_encrypted = $cipher->encrypt($inp_text, $get_current_channel_encryption_key);
							$inp_text_mysql = quote_smart($link, $inp_text_encrypted);
						}

						$inp_attachment_type = "$extension";
						$inp_attachment_type = output_html($inp_attachment_type);
						$inp_attachment_type_mysql = quote_smart($link, $inp_attachment_type);
	
						$inp_attachment_path = "_uploads/chat/attachments/channel_$get_current_channel_id";
						$inp_attachment_path_mysql = quote_smart($link, $inp_attachment_path);

						$inp_attachment_file = $get_my_user_id . "_" . $datetime_clean . "." . $extension;
						$inp_attachment_file = output_html($inp_attachment_file);
						$inp_attachment_file_mysql = quote_smart($link, $inp_attachment_file);

						// Add to chat
						mysqli_query($link, "INSERT INTO $t_chat_channels_messages
						(message_id, message_channel_id, message_type, message_text, message_datetime, 
						message_date_saying, message_time_saying, message_time, message_year, message_from_user_id, 
						message_from_user_name, message_from_user_alias, message_from_user_image_path, message_from_user_image_file, message_from_user_image_thumb_40, 
						message_from_user_image_thumb_50, message_from_ip, message_from_hostname, message_from_user_agent, message_attachment_type,
						message_attachment_path, message_attachment_file) 
						VALUES 
						(NULL, $get_current_channel_id, 'chat', $inp_text_mysql, '$datetime', 
						'$date_saying', '$time_saying', '$time', $year, $get_my_user_id, 
						$inp_my_user_name_mysql, $inp_my_user_alias_mysql, $inp_my_user_image_path_mysql, $inp_my_user_image_file_mysql, $inp_my_user_image_thumb_a_mysql, 
						$inp_my_user_image_thumb_b_mysql, $inp_my_ip_mysql, $inp_my_hostname_mysql, $inp_my_user_agent_mysql,
						$inp_attachment_type_mysql, $inp_attachment_path_mysql, $inp_attachment_file_mysql)")
						or die(mysqli_error($link));



						// $url = "dm.php?t_user_id=$get_to_user_id&ft_attachment=success&fm_attachment=image_uploaded&l=$l";
						$url = "open_starred_channel.php?starred_channel_id=$get_current_starred_channel_id&l=$l";
						header("Location: $url");
						exit;
						
					}  // if($width == "" OR $height == ""){
				} // extention = image
				else{
					$file_type = $_FILES['inp_file']['type']; //returns the mimetype


					if(!in_array($file_type, $allowed_other_formats)) {
						$url = "open_starred_channel.php?starred_channel_id=$get_current_starred_channel_id&ft_attachment=error&fm_attachment=unsupported_file_format_$file_type&l=$l";
						header("Location: $url");
						exit;
					}
	
					// Upload file
					$inp_file_name_clean = str_replace("$extension", "", $inp_file_name);
					$inp_file_name_clean = clean($inp_file_name_clean);
					$target = "$root/_uploads/chat/attachments/channel_$get_current_channel_id/". $get_my_user_id . "_" . $datetime_clean . "_" . $inp_file_name_clean . "." . $extension;
					if (move_uploaded_file($_FILES['inp_file']['tmp_name'], $target)) {

						// Check mime
						$mime = mime_content_type($target);
						if(!in_array($file_type, $allowed_other_formats)) {
							unlink("$target");
							$url = "dm.php?t_user_id=$get_to_user_id&ft_attachment=error&fm_attachment=unsupported&l=$l";
							header("Location: $url");
							exit;
						}
						else{

							// Store
							$inp_text = "";
							if($chatEncryptionMethodChannelsSav == "none"){
								$inp_text_mysql = quote_smart($link, $inp_text);
							}
							elseif($chatEncryptionMethodChannelsSav == "openssl_encrypt(AES-128-CBC)"){
								$inp_text_encrypted = openssl_encrypt_aes_128_cbc_encrypt($inp_text, $get_current_channel_encryption_key);
								$inp_text_mysql = quote_smart($link, $inp_text_encrypted);
							}
							elseif($chatEncryptionMethodChannelsSav == "caesar_cipher(random)"){
								$cipher = new KKiernan\CaesarCipher(); 
								$inp_text_encrypted = $cipher->encrypt($inp_text, $get_current_channel_encryption_key);
								$inp_text_mysql = quote_smart($link, $inp_text_encrypted);
							}


							$inp_attachment_type = "$extension";
							$inp_attachment_type = output_html($inp_attachment_type);
							$inp_attachment_type_mysql = quote_smart($link, $inp_attachment_type);

							$inp_attachment_path = "_uploads/chat/attachments/channel_$get_current_channel_id";
							$inp_attachment_path_mysql = quote_smart($link, $inp_attachment_path);

							$inp_attachment_file = $get_my_user_id . "_" .  $datetime_clean . "_" . $inp_file_name_clean . "." . $extension;
							$inp_attachment_file = output_html($inp_attachment_file);
							$inp_attachment_file_mysql = quote_smart($link, $inp_attachment_file);

							// Add to chat
							mysqli_query($link, "INSERT INTO $t_chat_channels_messages
							(message_id, message_channel_id, message_type, message_text, message_datetime, 
							message_date_saying, message_time_saying, message_time, message_year, message_from_user_id, 
							message_from_user_name, message_from_user_alias, message_from_user_image_path, message_from_user_image_file, message_from_user_image_thumb_40, 
							message_from_user_image_thumb_50, message_from_ip, message_from_hostname, message_from_user_agent, message_attachment_type,
							message_attachment_path, message_attachment_file) 
							VALUES 
							(NULL, $get_current_channel_id, 'chat', $inp_text_mysql, '$datetime', 
							'$date_saying', '$time_saying', '$time', $year, $get_my_user_id, 
							$inp_my_user_name_mysql, $inp_my_user_alias_mysql, $inp_my_user_image_path_mysql, $inp_my_user_image_file_mysql, $inp_my_user_image_thumb_a_mysql, 
							$inp_my_user_image_thumb_b_mysql, $inp_my_ip_mysql, $inp_my_hostname_mysql, $inp_my_user_agent_mysql,
							$inp_attachment_type_mysql, $inp_attachment_path_mysql, $inp_attachment_file_mysql)")
							or die(mysqli_error($link));
	
							// $url = "dm.php?t_user_id=$get_to_user_id&ft_attachment=success&fm_attachment=file_uploaded&l=$l";
							$url = "open_starred_channel.php?starred_channel_id=$get_current_starred_channel_id&l=$l";
							header("Location: $url");
							exit;
						}
					}
					else{
						$url = "open_starred_channel.php?starred_channel_id=$get_current_starred_channel_id&ft_attachment=error&fm_attachment=file_not_uploaded&l=$l";
						header("Location: $url");
						exit;
					}


					die;
					$ft = "warning";
					$fm = "unknown_file_format";	
				}
			
			} // if($image){
			else{
				$url = "open_starred_channel.php?starred_channel_id=$get_current_starred_channel_id&ft_attachment=warning&fm_attachment=no_file_selected&l=$l";
				header("Location: $url");
				exit;
			}
		} // channel found

	} // starred channel found

} // logged in
else{
	echo"
	<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" /></h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=$root/chat\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>