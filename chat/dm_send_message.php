<?php 
/**
*
* File: chat/pm_send_message.php
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
if($chatEncryptionMethodDmsSav == "openssl_encrypt(AES-128-CBC)"){
	include("_encrypt_decrypt/openssl_encrypt_aes-128-cbc.php");
}
elseif($chatEncryptionMethodDmsSav == "caesar_cipher(random)"){
	include("_encrypt_decrypt/caesar_cipher.php");
}

/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);



/*- Variables ------------------------------------------------------------------------- */
if(isset($_POST['t_user_id'])){
	$t_user_id = $_POST['t_user_id'];
	$t_user_id = output_html($t_user_id);
}
else{
	$t_user_id = "";
}
$t_user_id_mysql = quote_smart($link, $t_user_id);


if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){

	// Dates
	$datetime = date("Y-m-d H:i:s");
	$time = time();
	$year = date("Y");
	$day = date("d");
	$date_saying = date("j M Y");
	$datetime_saying = date("j M Y H:i");
	$time_saying = date("H:i");
				

	// Get my user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;


	// Find pm user
	$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$t_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_to_user_id, $get_to_user_email, $get_to_user_name, $get_to_user_alias, $get_to_user_rank) = $row;

	if($get_to_user_id == ""){
		echo"<h1>To user not found $query </h1>";
	}
	else{
		// Find conversation (we need conversation key)
		$query = "SELECT conversation_id, conversation_key, conversation_f_user_id, conversation_f_user_nickname, conversation_f_user_name, conversation_f_user_alias, conversation_f_image_path, conversation_f_image_file, conversation_f_image_thumb40, conversation_f_image_thumb50, conversation_f_has_blocked, conversation_f_unread_messages, conversation_t_user_id, conversation_t_user_nickname, conversation_t_user_name, conversation_t_user_alias, conversation_t_image_path, conversation_t_image_file, conversation_t_image_thumb40, conversation_t_image_thumb50, conversation_t_has_blocked, conversation_t_unread_messages, conversation_encryption_key, conversation_encryption_key_year, conversation_encryption_key_month FROM $t_chat_dm_conversations WHERE conversation_f_user_id=$get_my_user_id AND conversation_t_user_id=$get_to_user_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_conversation_id, $get_current_conversation_key, $get_current_conversation_f_user_id, $get_current_conversation_f_user_nickname, $get_current_conversation_f_user_name, $get_current_conversation_f_user_alias, $get_current_conversation_f_image_path, $get_current_conversation_f_image_file, $get_current_conversation_f_image_thumb40, $get_current_conversation_f_image_thumb50, $get_current_conversation_f_has_blocked, $get_current_conversation_f_unread_messages, $get_current_conversation_t_user_id, $get_current_conversation_t_user_nickname, $get_current_conversation_t_user_name, $get_current_conversation_t_user_alias, $get_current_conversation_t_image_path, $get_current_conversation_t_image_file, $get_current_conversation_t_image_thumb40, $get_current_conversation_t_image_thumb50, $get_current_conversation_t_has_blocked, $get_current_conversation_t_unread_messages, $get_current_conversation_encryption_key, $get_current_conversation_encryption_key_year, $get_current_conversation_encryption_key_month) = $row;

		if($get_current_conversation_id == ""){
			echo"Create conversation";
			die;
		}


		// Get text
		if(isset($_POST['inp_text'])){
			$inp_text = $_POST['inp_text'];

			// Make text safe
			$inp_text = output_html($inp_text);
			$inp_text = str_replace(":amp;:", "&amp;", $inp_text);

			// Replace emoji with html character
			$query_emojies = "SELECT emoji_id, emoji_main_category_id, emoji_sub_category_id, emoji_title, emoji_replace_a, emoji_code, emoji_char, emoji_char_output_html, emoji_source_path, emoji_source_file, emoji_source_ext, emoji_skin_tone, emoji_created_by_user_id, emoji_created_datetime, emoji_updated_by_user_id, emoji_updated_datetime, emoji_used_count, emoji_last_used_datetime FROM $t_emojies_index";
			$result_emojies = mysqli_query($link, $query_emojies);
			while($row_emojies = mysqli_fetch_row($result_emojies)) {
				list($get_emoji_id, $get_emoji_main_category_id, $get_emoji_sub_category_id, $get_emoji_title, $get_emoji_replace_a, $get_emoji_code, $get_emoji_char, $get_emoji_char_output_html, $get_emoji_source_path, $get_emoji_source_file, $get_emoji_source_ext, $get_emoji_skin_tone, $get_emoji_created_by_user_id, $get_emoji_created_datetime, $get_emoji_updated_by_user_id, $get_emoji_updated_datetime, $get_emoji_used_count, $get_emoji_last_used_datetime) = $row_emojies;
				$inp_text = str_replace("$get_emoji_char_output_html", "$get_emoji_char", $inp_text);
				$inp_text = str_replace(":$get_emoji_title:", "$get_emoji_char", $inp_text);
			}

		
			// Replace title with emoji 
			$query_emojies = "SELECT emoji_id, emoji_main_category_id, emoji_sub_category_id, emoji_title, emoji_code, emoji_char, emoji_char_output_html, emoji_source_path, emoji_source_file, emoji_source_ext, emoji_skin_tone, emoji_created_by_user_id, emoji_created_datetime, emoji_updated_by_user_id, emoji_updated_datetime, emoji_used_count, emoji_last_used_datetime FROM $t_emojies_index";
			$result_emojies = mysqli_query($link, $query_emojies);
			while($row_emojies = mysqli_fetch_row($result_emojies)) {
				list($get_emoji_id, $get_emoji_main_category_id, $get_emoji_sub_category_id, $get_emoji_title, $get_emoji_code, $get_emoji_char, $get_emoji_char_output_html, $get_emoji_source_path, $get_emoji_source_file, $get_emoji_source_ext, $get_emoji_skin_tone, $get_emoji_created_by_user_id, $get_emoji_created_datetime, $get_emoji_updated_by_user_id, $get_emoji_updated_datetime, $get_emoji_used_count, $get_emoji_last_used_datetime) = $row_emojies;


				// Did I use the smiley?
				
				$pos = strpos($inp_text, "$get_emoji_char");
				if ($pos === false) {
				} else {
					// Add to recent
					$query_recent = "SELECT recent_used_id, recent_used_user_id, recent_used_datetime, recent_used_counter, recent_used_emoji_id, recent_used_sub_category_id, recent_used_main_category_id, recent_used_emoji_code, recent_used_emoji_char, recent_used_emoji_source_path, recent_used_emoji_source_file, recent_used_emoji_source_ext FROM $t_emojies_users_recent_used WHERE recent_used_emoji_id=$get_emoji_id AND recent_used_user_id=$get_my_user_id";
					$result_recent = mysqli_query($link, $query_recent);
					$row_recent = mysqli_fetch_row($result_recent);
					list($get_recent_used_id, $get_recent_used_user_id, $get_recent_used_datetime, $get_recent_used_counter, $get_recent_used_emoji_id, $get_recent_used_sub_category_id, $get_recent_used_main_category_id, $get_recent_used_emoji_code, $get_recent_used_emoji_char, $get_recent_used_emoji_source_path, $get_recent_used_emoji_source_file, $get_recent_used_emoji_source_ext) = $row_recent;
	
					if($get_recent_used_id == ""){
						$inp_emoji_title_mysql = quote_smart($link, $get_emoji_title);
						$inp_emoji_replace_a_mysql = quote_smart($link, $get_emoji_replace_a);
						$inp_emoji_code_mysql = quote_smart($link, $get_emoji_code);
						$inp_emoji_source_path_mysql = quote_smart($link, $get_emoji_source_path);
						$inp_emoji_source_file_mysql = quote_smart($link, $get_emoji_source_file);
						$inp_emoji_source_ext_mysql = quote_smart($link, $get_emoji_source_ext);
						mysqli_query($link, "INSERT INTO $t_emojies_users_recent_used
						(recent_used_id, recent_used_user_id, recent_used_datetime, recent_used_counter, recent_used_emoji_id, recent_used_sub_category_id, 
						recent_used_main_category_id, recent_used_emoji_title, recent_used_emoji_replace_a, recent_used_emoji_code, recent_used_emoji_char, recent_used_emoji_source_path, recent_used_emoji_source_file, recent_used_emoji_source_ext) 
						VALUES 
						(NULL, $get_my_user_id, '$datetime', 1, $get_emoji_id, $get_emoji_sub_category_id, 
						$get_emoji_main_category_id, $inp_emoji_title_mysql, $inp_emoji_replace_a_mysql, $inp_emoji_code_mysql, '', $inp_emoji_source_path_mysql, $inp_emoji_source_file_mysql, $inp_emoji_source_ext_mysql)")
						or die(mysqli_error($link));

						// Get ID
						$query_recent = "SELECT recent_used_id FROM $t_emojies_users_recent_used WHERE recent_used_emoji_id=$get_emoji_id AND recent_used_user_id=$get_my_user_id";
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


		if(isset($_POST['inp_attachment_file'])){
			$inp_attachment_file = $_POST['inp_attachment_file'];
			$inp_attachment_file = output_html($inp_attachment_file);
		}
		else{
			$inp_attachment_file = "";
		}
		$inp_attachment_file_mysql = quote_smart($link, $inp_attachment_file);

		if($inp_text != ""){

			// Encrypter
			$year = date("Y");
			$month = date("m");
			if($year != "$get_current_conversation_encryption_key_year"){

				// make a new encryption string for this year month
				if($chatEncryptionMethodDmsSav == "none"){
					$inp_encryption_key_mysql = quote_smart($link, "");
					// Transfer
					$get_current_conversation_encryption_key = "";
				}
				elseif($chatEncryptionMethodDmsSav == "openssl_encrypt(AES-128-CBC)"){
					$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
					$pass = array(); //remember to declare $pass as an array
					$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
					$pass = "";
					for ($i = 0; $i < 8; $i++) {
						$n = rand(0, $alphaLength);
						$pass = $pass . $alphabet[$n];
					}
					$inp_encryption_key_mysql = quote_smart($link, $pass);

					// Transfer
					$get_current_conversation_encryption_key = "$pass";
				}
				elseif($chatEncryptionMethodDmsSav == "caesar_cipher(random)"){
					$random = rand(0,10);
					$inp_encryption_key_mysql = quote_smart($link, $random);

					// Transfer
					$get_current_conversation_encryption_key = "$random";
				}


				// make a new encryption string for this year month
				$conversation_key_mysql = quote_smart($link, $get_current_conversation_key);
				$result_update = mysqli_query($link, "UPDATE $t_chat_dm_conversations SET 
					conversation_encryption_key=$inp_encryption_key_mysql,
					conversation_encryption_key_year=$year, 
					conversation_encryption_key_month=$month WHERE conversation_key=$conversation_key_mysql") or die(mysqli_error($link));

				// Delete old messages (new year - new encrytion string)
				$result_delete = mysqli_query($link, "DELETE FROM $t_chat_dm_messages WHERE message_conversation_key=$conversation_key_mysql") or die(mysqli_error($link));
					
			}

			// Encrypt text
			if($chatEncryptionMethodDmsSav == "none"){
				$inp_text_mysql = quote_smart($link, $inp_text);
			}
			elseif($chatEncryptionMethodDmsSav == "openssl_encrypt(AES-128-CBC)"){
				$inp_text_encrypted = openssl_encrypt_aes_128_cbc_encrypt($inp_text, $get_current_conversation_encryption_key);
				$inp_text_mysql = quote_smart($link, $inp_text_encrypted);
			}
			elseif($chatEncryptionMethodDmsSav == "caesar_cipher(random)"){
				$cipher = new KKiernan\CaesarCipher(); 
				$inp_text_encrypted = $cipher->encrypt($inp_text, $get_current_conversation_encryption_key);
				$inp_text_mysql = quote_smart($link, $inp_text_encrypted);
			}



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



			// Key
			$inp_key_mysql = quote_smart($link, $get_current_conversation_key);

			// Insert
			mysqli_query($link, "INSERT INTO $t_chat_dm_messages 
			(message_id, message_conversation_key, message_type, message_text, message_datetime, message_date_saying, message_time_saying, message_time, message_year, message_day, message_seen, message_from_user_id, message_from_ip, message_from_hostname, message_from_user_agent) 
			VALUES 
			(NULL, $inp_key_mysql, 'chat', $inp_text_mysql, '$datetime', '$date_saying', '$time_saying', '$time', $year, $day, '0', $get_my_user_id, $inp_my_ip_mysql, $inp_my_hostname_mysql, $inp_my_user_agent_mysql)")
			or die(mysqli_error($link));

			// Attachment?
			if($inp_attachment_file != "" && file_exists("$root/_uploads/chat/images/$get_current_conversation_id/$inp_attachment_file")){
				$extension = get_extension($inp_attachment_file);

				$inp_attachment_type = "$extension";
				$inp_attachment_type = output_html($inp_attachment_type);
				$inp_attachment_type_mysql = quote_smart($link, $inp_attachment_type);

				$inp_attachment_path = "_uploads/chat/images/$get_current_conversation_id";
				$inp_attachment_path_mysql = quote_smart($link, $inp_attachment_path);

				

				$inp_attachment_thumb = str_replace(".$extension", "", $inp_attachment_file);
				$inp_attachment_thumb = $inp_attachment_thumb . "_thumb." . $extension;
				$inp_attachment_thumb = output_html($inp_attachment_thumb);
				$inp_attachment_thumb_mysql = quote_smart($link, $inp_attachment_thumb);
				
				$result = mysqli_query($link, "UPDATE $t_chat_dm_messages SET 
								message_attachment_type=$inp_attachment_type_mysql,
								message_attachment_path=$inp_attachment_path_mysql,
								message_attachment_file=$inp_attachment_file_mysql 
								 WHERE message_time='$time' AND message_from_user_id=$get_my_user_id") or die(mysqli_error($link));

				// Delete thumb
				if($inp_attachment_thumb != "" && file_exists("$root/_uploads/chat/images/$get_current_conversation_id/$inp_attachment_thumb")){
					unlink("$root/_uploads/chat/images/$get_current_conversation_id/$inp_attachment_thumb");
				}

			}


			// Update new messages box for to user
			$query = "SELECT conversation_id, conversation_key, conversation_f_user_id, conversation_f_user_nickname, conversation_f_user_name, conversation_f_user_alias, conversation_f_image_path, conversation_f_image_file, conversation_f_image_thumb40, conversation_f_image_thumb50, conversation_f_has_blocked, conversation_f_unread_messages, conversation_t_user_id, conversation_t_user_nickname, conversation_t_user_name, conversation_t_user_alias, conversation_t_image_path, conversation_t_image_file, conversation_t_image_thumb40, conversation_t_image_thumb50, conversation_t_has_blocked, conversation_t_unread_messages FROM $t_chat_dm_conversations WHERE conversation_f_user_id=$get_to_user_id AND conversation_t_user_id=$get_my_user_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_to_conversation_id, $get_to_conversation_key, $get_to_conversation_f_user_id, $get_to_conversation_f_user_nickname, $get_to_conversation_f_user_name, $get_to_conversation_f_user_alias, $get_to_conversation_f_image_path, $get_to_conversation_f_image_file, $get_to_conversation_f_image_thumb40, $get_to_conversation_f_image_thumb50, $get_to_conversation_f_has_blocked, $get_to_conversation_f_unread_messages, $get_to_conversation_t_user_id, $get_to_conversation_t_user_nickname, $get_to_conversation_t_user_name, $get_to_conversation_t_user_alias, $get_to_conversation_t_image_path, $get_to_conversation_t_image_file, $get_to_conversation_t_image_thumb40, $get_to_conversation_t_image_thumb50, $get_to_conversation_t_has_blocked, $get_to_conversation_t_unread_messages) = $row;

			$inp_new_messages = $get_to_conversation_f_unread_messages+1;

			$result = mysqli_query($link, "UPDATE $t_chat_dm_conversations SET conversation_f_unread_messages=$inp_new_messages WHERE conversation_id=$get_to_conversation_id");



			// Echo this message
			$query = "SELECT message_id, message_conversation_key, message_type, message_text, message_datetime, message_date_saying, message_time_saying, message_time, message_year, message_seen, message_from_user_id, message_attachment_type, message_attachment_path, message_attachment_file, message_from_ip, message_from_hostname, message_from_user_agent FROM $t_chat_dm_messages WHERE message_time='$time' AND message_from_user_id=$get_my_user_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_message_id, $get_message_conversation_key, $get_message_type, $get_message_text, $get_message_datetime, $get_message_date_saying, $get_message_time_saying, $get_message_time, $get_message_year, $get_message_seen, $get_message_from_user_id, $get_message_attachment_type, $get_message_attachment_path, $get_message_attachment_file, $get_message_from_ip, $get_message_from_hostname, $get_message_from_user_agent) = $row;
	
			

			echo"
							<table>
							 <tr>
							  <td style=\"padding: 5px 5px 0px 0px;vertical-align:top;\">
								<!-- Img -->
								<p>";
								if($get_current_conversation_f_image_file != "" && file_exists("$root/$get_current_conversation_f_image_path/$get_current_conversation_f_image_file")){
								if(!(file_exists("$root/$get_current_conversation_f_image_path/$get_current_conversation_f_image_thumb40")) && $get_current_conversation_f_image_thumb40 != ""){
									// Make thumb
									$inp_new_x = 40; // 950
									$inp_new_y = 40; // 640
									resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_current_conversation_f_image_path/$get_current_conversation_f_image_file", "$root/$get_current_conversation_f_image_path/$get_current_conversation_f_image_thumb40");
								}

								if(file_exists("$root/$get_current_conversation_f_image_path/$get_current_conversation_f_image_thumb40") && $get_current_conversation_f_image_thumb40 != ""){
									echo"
									<a href=\"$root/users/view_profile.php?user_id=$get_current_conversation_f_user_id&amp;l=$l\"><img src=\"$root/$get_current_conversation_f_image_path/$get_current_conversation_f_image_thumb40\" alt=\"$get_current_conversation_f_image_thumb40\" class=\"chat_messages_from_user_image\" /></a>
									";
								}
								}
								else{
								echo"
								<a href=\"$root/users/view_profile.php?user_id=$get_current_conversation_f_user_id&amp;l=$l\"><img src=\"_gfx/avatar_blank_40.png\" alt=\"avatar_blank_40.png\" class=\"chat_messages_from_user_image\" /></a>
								";
								}
								echo"
								</p>
								<!-- //Img -->
							  </td>
							  <td style=\"vertical-align:top;\">
								<!-- Name and text -->	
								<p>
								<a href=\"$root/users/view_profile.php?user_id=$get_current_conversation_f_user_id&amp;l=$l\" class=\"chat_messages_from_user_alias\" title=\"$get_current_conversation_f_user_alias\">$get_current_conversation_f_user_nickname</a>
								<span class=\"chat_messages_date_and_time\">";
								if($date_saying != "$get_message_date_saying"){
									echo"$get_message_date_saying ";
								}
								echo"$get_message_time_saying</span>
								<img src=\"_gfx/seen_1.png\" alt=\"seen_1.png\" class=\"dm_message_seen_icon_$get_message_id\">";
						
								if($get_current_conversation_f_user_id == "$my_user_id"){
									echo"
									<a href=\"dm.php?action=delete_message&amp;message_id=$get_message_id&amp;t_user_id=$get_to_user_id&amp;l=$l&amp;process=1\"><img src=\"_gfx/delete_grey_16x16.png\" alt=\"delete.png\" /></a>
									";
								}
								echo"<br />
								";
								// Attachment?
								if($get_message_attachment_file != "" && file_exists("$root/$get_message_attachment_path/$get_message_attachment_file")){
									if($get_message_attachment_type == "jpg" OR $get_message_attachment_type == "png" OR $get_message_attachment_type == "gif"){
										echo"<img src=\"$root/$get_message_attachment_path/$get_message_attachment_file\" alt=\"$get_message_attachment_path/$get_message_attachment_file\" /><br />\n";
									}
								}
								echo"
								$inp_text
								</p>
								<!-- //Name and text -->
							  </td>
							 </tr>
							</table>
			<div id=\"dm_message_seen_icon_result_$get_message_id\"></div>

			<!-- Check if sendt message is seen script -->
				<script language=\"javascript\" type=\"text/javascript\">
				\$(document).ready(function () {
					function navigation_look_for_new_messages_and_conversations(){
						var data = 'l=$l&t_user_id=$get_current_conversation_t_user_id&message_id=$get_message_id';
            					\$.ajax({
                					type: \"POST\",
               						url: \"dm_send_message_check_if_message_is_seen_script.php\",
                					data: data,
							beforeSend: function(html) { // this happens before actual call
							},
               						success: function(html){
								\$(\"#dm_message_seen_icon_result_$get_message_id\").html(html);
              						}
       						});
					}
					setInterval(navigation_look_for_new_messages_and_conversations,10000);
         			});
				</script>
			<!-- //Check if sendt message is seen script -->

			";
		} // inp_text
		else{
			echo"Noting to post";
		}

	} // to_user found

} // logged in


?>