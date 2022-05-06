<?php 
/**
*
* File: chat/open_starred_channel_send_message.php
* Version 1.0.0
* Date 19:41 31.08.2019
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
if(isset($_POST['starred_channel_id'])){
	$starred_channel_id = $_POST['starred_channel_id'];
	$starred_channel_id = output_html($starred_channel_id);
}
else{
	$starred_channel_id = "";
}
$starred_channel_id_mysql = quote_smart($link, $starred_channel_id);

if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);

	// Dates
	$datetime = date("Y-m-d H:i:s");
	$time = time();
	$year = date("Y");
	$month = date("m");
	$day = date("d");
	$date_saying = date("j M Y");
	$datetime_saying = date("j M Y H:i");
	$time_saying = date("H:i");

	// Get starred
	$query = "SELECT starred_channel_id, channel_id, channel_name, new_messages, user_id FROM $t_chat_users_starred_channels WHERE starred_channel_id=$starred_channel_id_mysql AND user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_starred_channel_id, $get_current_channel_id, $get_current_channel_name, $get_current_new_messages, $get_current_user_id) = $row;

	if($get_current_starred_channel_id == ""){
		echo"<h1>Starred Channel not found</h1>";
	}
	else{
		// Find channel
		$query = "SELECT channel_id, channel_name, channel_password, channel_last_message_time, channel_encryption_key, channel_encryption_key_year, channel_encryption_key_month FROM $t_chat_channels_index WHERE channel_id=$get_current_channel_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_channel_id, $get_current_channel_name, $get_current_channel_password, $get_current_channel_last_message_time, $get_current_channel_encryption_key, $get_current_channel_encryption_key_year, $get_current_channel_encryption_key_month) = $row;

		if($get_current_channel_id == ""){
			echo"<h1>Channel not found</h1>";
		}
		else{
			// Get text
			if(isset($_POST['inp_text'])){
				$inp_text = $_POST['inp_text'];

				// Make text safe
				$inp_text = output_html($inp_text);
				$inp_text = str_replace(":amp;:", "&amp;", $inp_text);

				// Replace emoji with html character
				$query_emojies = "SELECT emoji_id, emoji_main_category_id, emoji_sub_category_id, emoji_title, emoji_code, emoji_char, emoji_char_output_html, emoji_char_string_value, emoji_source_path, emoji_source_file, emoji_source_ext, emoji_skin_tone, emoji_created_by_user_id, emoji_created_datetime, emoji_updated_by_user_id, emoji_updated_datetime, emoji_used_count, emoji_last_used_datetime FROM $t_emojies_index";
				$result_emojies = mysqli_query($link, $query_emojies);
				while($row_emojies = mysqli_fetch_row($result_emojies)) {
					list($get_emoji_id, $get_emoji_main_category_id, $get_emoji_sub_category_id, $get_emoji_title, $get_emoji_code, $get_emoji_char, $get_emoji_char_output_html, $get_emoji_char_string_value, $get_emoji_source_path, $get_emoji_source_file, $get_emoji_source_ext, $get_emoji_skin_tone, $get_emoji_created_by_user_id, $get_emoji_created_datetime, $get_emoji_updated_by_user_id, $get_emoji_updated_datetime, $get_emoji_used_count, $get_emoji_last_used_datetime) = $row_emojies;
					$inp_text = str_replace("$get_emoji_char_output_html", "$get_emoji_char", $inp_text);
					$inp_text = str_replace(":$get_emoji_title:", "$get_emoji_char", $inp_text);

				}


				// Replace title with emoji 
				$query_emojies = "SELECT emoji_id, emoji_main_category_id, emoji_sub_category_id, emoji_title, emoji_code, emoji_char, emoji_source_path, emoji_source_file, emoji_source_ext, emoji_skin_tone, emoji_created_by_user_id, emoji_created_datetime, emoji_updated_by_user_id, emoji_updated_datetime, emoji_used_count, emoji_last_used_datetime FROM $t_emojies_index";
				$result_emojies = mysqli_query($link, $query_emojies);
				while($row_emojies = mysqli_fetch_row($result_emojies)) {
					list($get_emoji_id, $get_emoji_main_category_id, $get_emoji_sub_category_id, $get_emoji_title, $get_emoji_code, $get_emoji_char, $get_emoji_source_path, $get_emoji_source_file, $get_emoji_source_ext, $get_emoji_skin_tone, $get_emoji_created_by_user_id, $get_emoji_created_datetime, $get_emoji_updated_by_user_id, $get_emoji_updated_datetime, $get_emoji_used_count, $get_emoji_last_used_datetime) = $row_emojies;


					// Did I use the smiley?
					$pos = strpos($inp_text, "$get_emoji_char");
					if ($pos === false) {
					} else {
						// Add to recent
						$query_recent = "SELECT recent_used_id, recent_used_user_id, recent_used_datetime, recent_used_counter, recent_used_emoji_id, recent_used_sub_category_id, recent_used_main_category_id, recent_used_emoji_code, recent_used_emoji_char, recent_used_emoji_source_path, recent_used_emoji_source_file, recent_used_emoji_source_ext FROM $t_emojies_users_recent_used WHERE recent_used_emoji_id=$get_emoji_id AND recent_used_user_id=$my_user_id_mysql";
						$result_recent = mysqli_query($link, $query_recent);
						$row_recent = mysqli_fetch_row($result_recent);
						list($get_recent_used_id, $get_recent_used_user_id, $get_recent_used_datetime, $get_recent_used_counter, $get_recent_used_emoji_id, $get_recent_used_sub_category_id, $get_recent_used_main_category_id, $get_recent_used_emoji_code, $get_recent_used_emoji_char, $get_recent_used_emoji_source_path, $get_recent_used_emoji_source_file, $get_recent_used_emoji_source_ext) = $row_recent;
	
						if($get_recent_used_id == ""){
							$inp_emoji_code_mysql = quote_smart($link, $get_emoji_code);
							$inp_emoji_source_path_mysql = quote_smart($link, $get_emoji_source_path);
							$inp_emoji_source_file_mysql = quote_smart($link, $get_emoji_source_file);
							$inp_emoji_source_ext_mysql = quote_smart($link, $get_emoji_source_ext);
							mysqli_query($link, "INSERT INTO $t_emojies_users_recent_used
							(recent_used_id, recent_used_user_id, recent_used_datetime, recent_used_counter, recent_used_emoji_id, recent_used_sub_category_id, 
							recent_used_main_category_id, recent_used_emoji_code, recent_used_emoji_char, recent_used_emoji_source_path, recent_used_emoji_source_file, recent_used_emoji_source_ext) 
							VALUES 
							(NULL, $my_user_id_mysql, '$datetime', 1, $get_emoji_id, $get_emoji_sub_category_id, 
							$get_emoji_main_category_id, $inp_emoji_code_mysql, '', $inp_emoji_source_path_mysql, $inp_emoji_source_file_mysql, $inp_emoji_source_ext_mysql)")
							or die(mysqli_error($link));

							// Get ID
							$query_recent = "SELECT recent_used_id FROM $t_emojies_users_recent_used WHERE recent_used_emoji_id=$get_emoji_id AND recent_used_user_id=$my_user_id_mysql";
							$result_recent = mysqli_query($link, $query_recent);
							$row_recent = mysqli_fetch_row($result_recent);
							list($get_recent_used_id) = $row_recent;
						
							// Update with char
							$sql = "UPDATE $t_emojies_users_recent_used SET recent_used_emoji_char=? WHERE recent_used_id=$get_recent_used_id";
							$stmt = $link->prepare($sql);
							$stmt->bind_param("s", $get_emoji_char);
							$stmt->execute();
							if ($stmt->errno) {
								echo "FAILURE!!! " . $stmt->error; die;
							}
						}
						else{
							$inp_recent_used_counter = $get_recent_used_counter+1;
							$result = mysqli_query($link, "UPDATE $t_emojies_users_recent_used SET recent_used_counter=$inp_recent_used_counter WHERE recent_used_id=$get_recent_used_id");
	
						}
					
					}


					// Replace
					$inp_text = str_replace(":emoji_id$get_emoji_id:", "$get_emoji_char", $inp_text);
				}

				// Add links
				$url_pattern = '/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/';
				$inp_text = preg_replace($url_pattern, '<a href="$0">$0</a>', $inp_text);


			}
			else{
				$inp_text = "";
			}

			if($inp_text != ""){
				// Encrypter
				if($year != "$get_current_channel_encryption_key_year"){
					// make a new encryption string for this year month
					if($chatEncryptionMethodChannelsSav == "none"){
						$inp_encryption_key_mysql = quote_smart($link, "");

						// Transfer
						$get_current_channel_encryption_key = "";

					}
					elseif($chatEncryptionMethodChannelsSav == "openssl_encrypt(AES-128-CBC)"){
						$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
						$randstring = '';
						for ($i = 0; $i < 10; $i++) {
							$randstring = $randstring . $characters[rand(0, strlen($characters))];
						}
						$inp_encryption_key_mysql = quote_smart($link, $randstring);

						// Transfer
						$get_current_channel_encryption_key = "$randstring";
					}
					elseif($chatEncryptionMethodChannelsSav == "caesar_cipher(random)"){
						$random = rand(0,10);
						$inp_encryption_key_mysql = quote_smart($link, $random);

						// Transfer
						$get_current_channel_encryption_key = "$random";
					}

					$result_update = mysqli_query($link, "UPDATE $t_chat_channels_index SET 
						channel_encryption_key=$inp_encryption_key_mysql,
						channel_encryption_key_year=$year, 
						channel_encryption_key_month=$month WHERE channel_id=$get_current_channel_id") or die(mysqli_error($link));

					// Delete old messages (new year - new encrytion string)
					$result_delete = mysqli_query($link, "DELETE FROM $t_chat_channels_messages WHERE message_channel_id=$get_current_channel_id") or die(mysqli_error($link));
					
				}

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

				// My nickname
				$query = "SELECT nickname_id, nickname_user_id, nickname_value, nickname_datetime, nickname_datetime_saying FROM $t_chat_nicknames WHERE nickname_user_id=$get_my_user_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_my_nickname_id, $get_my_nickname_user_id, $get_my_nickname_value, $get_my_nickname_datetime, $get_my_nickname_datetime_saying) = $row;

				$inp_my_user_nickname_mysql = quote_smart($link, $get_my_nickname_value);

				$inp_my_user_name_mysql = quote_smart($link, $get_my_user_name);
				$inp_my_user_alias_mysql = quote_smart($link, $get_my_user_alias);

				$inp_my_user_image_path = "_uploads/users/images/$get_my_user_id";
				$inp_my_user_image_path_mysql = quote_smart($link, $inp_my_user_image_path);

				$inp_my_user_image_file_mysql = quote_smart($link, $get_my_photo_destination);

				$inp_my_user_image_thumb_a_mysql = quote_smart($link, $get_my_photo_thumb_40);
				$inp_my_user_image_thumb_b_mysql = quote_smart($link, $get_my_photo_thumb_50);

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




				// Insert
				mysqli_query($link, "INSERT INTO $t_chat_channels_messages
				(message_id, message_channel_id, message_type, message_text, message_datetime, message_date_saying, message_time_saying, message_time, message_year, message_day, message_from_user_id, message_from_user_nickname, message_from_user_name, message_from_user_alias, message_from_user_image_path, message_from_user_image_file, message_from_user_image_thumb_40, message_from_user_image_thumb_50, message_from_ip, message_from_hostname, message_from_user_agent) 
				VALUES 
				(NULL, $get_current_channel_id, 'chat', $inp_text_mysql, '$datetime', '$date_saying', '$time_saying', '$time', $year, $day, $get_my_user_id, $inp_my_user_nickname_mysql, $inp_my_user_name_mysql, $inp_my_user_alias_mysql, $inp_my_user_image_path_mysql, $inp_my_user_image_file_mysql, $inp_my_user_image_thumb_a_mysql, $inp_my_user_image_thumb_b_mysql, $inp_my_ip_mysql, $inp_my_hostname_mysql, $inp_my_user_agent_mysql)")
				or die(mysqli_error($link));


				// Update new messages for all channel users
				$query = "SELECT starred_channel_id, channel_id, channel_name, new_messages, user_id FROM $t_chat_users_starred_channels WHERE channel_id=$get_current_channel_id";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_starred_channel_id, $get_channel_id, $get_channel_name, $get_new_messages, $get_user_id) = $row;
					
					$inp_new_messages = $get_new_messages+1;

					if($get_user_id != "$my_user_id"){
						$result_update = mysqli_query($link, "UPDATE $t_chat_users_starred_channels SET new_messages=$inp_new_messages WHERE starred_channel_id=$get_starred_channel_id");
					}
				}

				// Update last message date
				$result_del = mysqli_query($link, "UPDATE $t_chat_channels_index SET channel_last_message_time='$time', channel_last_message_saying='$datetime_saying' WHERE channel_id=$get_current_channel_id");

				// Echo this msg
				$query = "SELECT message_id, message_channel_id, message_text, message_datetime, message_date_saying, message_time_saying, message_time, message_year, message_from_user_id, message_from_user_nickname, message_from_user_name, message_from_user_alias, message_from_user_image_path, message_from_user_image_file, message_from_user_image_thumb_40, message_from_user_image_thumb_50, message_from_ip, message_from_hostname, message_from_user_agent FROM $t_chat_channels_messages WHERE message_channel_id=$get_current_channel_id AND message_datetime='$datetime' AND message_from_user_id=$my_user_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_message_id, $get_message_channel_id, $get_message_text, $get_message_datetime, $get_message_date_saying, $get_message_time_saying, $get_message_time, $get_message_year, $get_message_from_user_id, $get_message_from_user_nickname, $get_message_from_user_name, $get_message_from_user_alias, $get_message_from_user_image_path, $get_message_from_user_image_file, $get_message_from_user_image_thumb_40, $get_message_from_user_image_thumb_50, $get_message_from_ip, $get_message_from_hostname, $get_message_from_user_agente) = $row;

				// Decrypt message	
				/*
				$c = base64_decode($get_message_text);
				$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
				$iv = substr($c, 0, $ivlen);
				$hmac = substr($c, $ivlen, $sha2len=32);
				$ciphertext_raw = substr($c, $ivlen+$sha2len);
				$original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $get_current_channel_encryption_key, $options=OPENSSL_RAW_DATA, $iv);
				$calcmac = hash_hmac('sha256', $ciphertext_raw, $get_current_channel_encryption_key, $as_binary=true);
				if (hash_equals($hmac, $calcmac)) {
					 $get_message_text = "$original_plaintext";
				}
				*/
				$get_message_text = "$inp_text";

				echo"
				<table>
				 <tr>
				  <td style=\"padding: 5px 5px 0px 0px;vertical-align:top;\">
					<!-- Img -->
						<p>";
						if($get_message_from_user_image_file != "" && file_exists("$root/$get_message_from_user_image_path/$get_message_from_user_image_file")){
							if(!(file_exists("$root/$get_message_from_user_image_path/$get_message_from_user_image_thumb_40")) && $get_message_from_user_image_thumb_40 != ""){
								// Make thumb
								$inp_new_x = 40; // 950
								$inp_new_y = 40; // 640
								resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_message_from_user_image_path/$get_message_from_user_image_file", "$root/$get_message_from_user_image_path/$get_message_from_user_image_thumb_40");
							
							}

							if(file_exists("$root/$get_message_from_user_image_path/$get_message_from_user_image_thumb_40") && $get_message_from_user_image_thumb_40 != ""){
								echo"
								<a href=\"dm.php?t_user_id=$get_message_from_user_id&amp;l=$l\"><img src=\"$root/$get_message_from_user_image_path/$get_message_from_user_image_thumb_40\" alt=\"$get_message_from_user_image_thumb_40\" class=\"chat_messages_from_user_image\" /></a>
								";
							}
						}
						else{
							echo"
							<a href=\"dm.php?t_user_id=$get_message_from_user_id&amp;l=$l\"><img src=\"_gfx/avatar_blank_40.png\" alt=\"avatar_blank_40.png\" class=\"chat_messages_from_user_image\" /></a>
							";
						}
						echo"
						</p>
					<!-- //Img -->
				  </td>
				  <td style=\"vertical-align:top;\">
					<!-- Name and text -->	
						<p>
						<a href=\"dm.php?t_user_id=$get_message_from_user_id&amp;l=$l\" class=\"chat_messages_from_user_alias\">$get_message_from_user_nickname</a>
						<span class=\"chat_messages_date_and_time\">";
						if($date_saying != "$get_message_date_saying"){
							echo"$get_message_date_saying ";
						}
						echo"$get_message_time_saying</span>";
						
						if($get_message_from_user_id == "$my_user_id"){
							echo"
							<a href=\"open_starred_channel.php?action=delete_message&amp;message_id=$get_message_id&amp;starred_channel_id=$get_current_starred_channel_id&amp;l=$l&amp;process=1\"><img src=\"_gfx/delete_grey_16x16.png\" alt=\"delete.png\" /></a>
							";
						}

						echo"<br />
						$get_message_text
						</p>
					<!-- //Name and text -->
				  </td>
				 </tr>
				</table>";

			} // inp_text
			else{
				echo"Noting to post";
			}
		} // channel found

	} // starred channel found

} // logged in


?>