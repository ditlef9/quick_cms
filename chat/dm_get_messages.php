<?php 
/**
*
* File: chat/pm_get_message.php
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
if(isset($_POST['to_user_id'])){
	$to_user_id = $_POST['to_user_id'];
	$to_user_id = output_html($to_user_id);
}
else{
	$to_user_id = "";
}
$to_user_id_mysql = quote_smart($link, $to_user_id);


if(isset($_POST['last_message_id'])){
	$last_message_id = $_POST['last_message_id'];
	$last_message_id = output_html($last_message_id);
}
else{
	$last_message_id = "";
}
$last_message_id_mysql = quote_smart($link, $last_message_id);

if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	// Get my user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;


	// Find pm user
	$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$to_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_to_user_id, $get_to_user_email, $get_to_user_name, $get_to_user_alias, $get_to_user_rank) = $row;


	if($get_to_user_id == ""){
		echo"<h1>To user not found $query </h1>";
	}
	else{
		// Look for conversation
		$query = "SELECT conversation_id, conversation_key, conversation_f_user_id, conversation_f_user_name, conversation_f_user_alias, conversation_f_image_path, conversation_f_image_file, conversation_f_image_thumb40, conversation_f_image_thumb50, conversation_f_has_blocked, conversation_f_unread_messages, conversation_t_user_id, conversation_t_user_nickname, conversation_t_user_name, conversation_t_user_alias, conversation_t_image_path, conversation_t_image_file, conversation_t_image_thumb40, conversation_t_image_thumb50, conversation_t_has_blocked, conversation_t_unread_messages, conversation_encryption_key, conversation_encryption_key_year, conversation_encryption_key_month FROM $t_chat_dm_conversations WHERE conversation_f_user_id=$get_my_user_id AND conversation_t_user_id=$get_to_user_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_conversation_id, $get_current_conversation_key, $get_current_conversation_f_user_id, $get_current_conversation_f_user_name, $get_current_conversation_f_user_alias, $get_current_conversation_f_image_path, $get_current_conversation_f_image_file, $get_current_conversation_f_image_thumb40, $get_current_conversation_f_image_thumb50, $get_current_conversation_f_has_blocked, $get_current_conversation_f_unread_messages, $get_current_conversation_t_user_id, $get_current_conversation_t_user_nickname, $get_current_conversation_t_user_name, $get_current_conversation_t_user_alias, $get_current_conversation_t_image_path, $get_current_conversation_t_image_file, $get_current_conversation_t_image_thumb40, $get_current_conversation_t_image_thumb50, $get_current_conversation_t_has_blocked, $get_current_conversation_t_unread_messages, $get_current_conversation_encryption_key, $get_current_conversation_encryption_key_year, $get_current_conversation_encryption_key_month) = $row;

		if($get_current_conversation_id == ""){
			echo"Create conversation";
			die;
		}

		// Set all messages read
		$result = mysqli_query($link, "UPDATE $t_chat_dm_conversations SET conversation_f_unread_messages=0 WHERE conversation_id=$get_current_conversation_id");

	
		// Get messages
		$variable_last_message_id = "1";
		$date_saying = date("j M Y");
		$time = time();
		$x = 0;
		$query = "SELECT message_id, message_conversation_key, message_type, message_text, message_datetime, message_date_saying, message_time_saying, message_time, message_year, message_seen, message_attachment_type, message_attachment_path, message_attachment_file, message_from_user_id, message_from_ip, message_from_hostname, message_from_user_agent FROM $t_chat_dm_messages WHERE message_id > $last_message_id_mysql AND message_conversation_key='$get_current_conversation_key' AND message_from_user_id=$get_to_user_id ORDER BY message_id DESC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_message_id, $get_message_conversation_key, $get_message_type, $get_message_text, $get_message_datetime, $get_message_date_saying, $get_message_time_saying, $get_message_time, $get_message_year, $get_message_seen, $get_message_attachment_type, $get_message_attachment_path, $get_message_attachment_file, $get_message_from_user_id, $get_message_from_ip, $get_message_from_hostname, $get_message_from_user_agent) = $row;
	
			// Decrypt message
			if($chatEncryptionMethodDmsSav == "none"){
			}
			elseif($chatEncryptionMethodDmsSav == "openssl_encrypt(AES-128-CBC)"){
				$get_message_text = openssl_decrypt_aes_128_cbc_decrypt($get_message_text, $get_current_conversation_encryption_key);
			}
			elseif($chatEncryptionMethodDmsSav == "caesar_cipher(random)"){
				$cipher = new KKiernan\CaesarCipher();
				$get_message_text = $cipher->encrypt($get_message_text, -$get_current_conversation_encryption_key);
			}
			


				if($get_message_from_user_id == "$get_current_conversation_f_user_id"){
					// This is a message that I have written
					if($get_message_seen == "0"){
						$result_update = mysqli_query($link, "UPDATE $t_chat_dm_messages SET message_seen=1 WHERE message_id=$get_message_id") or die(mysqli_error($link));
						$get_message_seen = "1";
					}
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
								<a href=\"$root/users/view_profile.php?user_id=$get_current_conversation_f_user_id&amp;l=$l\" class=\"chat_messages_from_user_alias\">$get_current_conversation_f_user_alias</a>
								<span class=\"chat_messages_date_and_time\">";
								if($date_saying != "$get_message_date_saying"){
									echo"$get_message_date_saying ";
								}
								echo"$get_message_time_saying</span>";
						
								if($get_message_seen == "2"){
									echo" <img src=\"_gfx/seen_$get_message_seen.png\" alt=\"seen_$get_message_seen.png\" class=\"dm_message_seen_icon\" />";
								}
								if($get_current_conversation_f_user_id == "$my_user_id"){
									echo"
									<a href=\"dm.php?action=delete_message&amp;message_id=$get_message_id&amp;t_user_id=$get_to_user_id&amp;l=$l&amp;process=1\"><img src=\"_gfx/delete_grey_16x16.png\" alt=\"delete.png\" /></a>
									";
								}
								echo"<br />
								";
								// Attachment?
								if($get_message_attachment_file != ""){
									if(file_exists("$root/$get_message_attachment_path/$get_message_attachment_file")){
										if($get_message_attachment_type == "jpg" OR $get_message_attachment_type == "png" OR $get_message_attachment_type == "gif"){
											echo"
											<img src=\"$root/$get_message_attachment_path/$get_message_attachment_file\" alt=\"$get_message_attachment_path/$get_message_attachment_file\" /><br />
											\n";
										}
										else{
											$icon = $get_message_attachment_type . "_32x32.png";
											echo"
											<a href=\"$root/$get_message_attachment_path/$get_message_attachment_file\"><img src=\"_gfx/$icon\" alt=\"$icon\" style=\"float: left;\"></a>
											<a href=\"$root/$get_message_attachment_path/$get_message_attachment_file\" style=\"float: left;padding: 8px 0px 0px 8px;\">$get_message_attachment_file</a>
											<br class=\"clear\" />";
										}
									}
									else{
										echo"<a href=\"$root/$get_message_attachment_path/$get_message_attachment_file\"><img src=\"_gfx/dialog_warning_16x16.png\" alt=\"dialog_warning_16x16.png\"> Attachment not found</a>";
									}
								}
								echo"
								$get_message_text
								</p>
						<!-- //Name and text -->
					  </td>
					 </tr>
					</table>
					";


				}
				else{
					// This is me, so set the message as read
					if($get_message_seen == "0" OR $get_message_seen == "1"){
						$result_update = mysqli_query($link, "UPDATE $t_chat_dm_messages SET message_seen=2 WHERE message_id=$get_message_id") or die(mysqli_error($link));
						$get_message_seen = "2";
					}
					echo"
					<table>
					 <tr>
					  <td style=\"padding: 5px 5px 0px 0px;vertical-align:top;\">
						<!-- Img -->
							<p>";
							if($get_current_conversation_t_image_file != "" && file_exists("$root/$get_current_conversation_t_image_path/$get_current_conversation_t_image_file")){
								if(!(file_exists("$root/$get_current_conversation_t_image_path/$get_current_conversation_t_image_thumb40")) && $get_current_conversation_t_image_thumb40 != ""){
									// Make thumb
									$inp_new_x = 40; // 950
									$inp_new_y = 40; // 640
									resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_current_conversation_t_image_path/$get_current_conversation_t_image_file", "$root/$get_current_conversation_t_image_path/$get_current_conversation_t_image_thumb40");
								}

								if(file_exists("$root/$get_current_conversation_t_image_path/$get_current_conversation_t_image_thumb40") && $get_current_conversation_t_image_thumb40 != ""){
									echo"
									<a href=\"$root/users/view_profile.php?user_id=$get_current_conversation_t_user_id&amp;l=$l\"><img src=\"$root/$get_current_conversation_t_image_path/$get_current_conversation_t_image_thumb40\" alt=\"$get_current_conversation_t_image_thumb40\" class=\"chat_messages_from_user_image\" /></a>
									";
								}
							}
							else{
								echo"
								<a href=\"$root/users/view_profile.php?user_id=$get_current_conversation_t_user_id&amp;l=$l\"><img src=\"_gfx/avatar_blank_40.png\" alt=\"avatar_blank_40.png\" class=\"chat_messages_from_user_image\" /></a>
								";
							}
							echo"
							</p>
						<!-- //Img -->
					  </td>
					  <td style=\"vertical-align:top;\">
						<!-- Name and text -->	
							<p>
							<a href=\"$root/users/view_profile.php?user_id=$get_current_conversation_t_user_id&amp;l=$l\" class=\"chat_messages_from_user_alias\" title=\"$get_current_conversation_t_user_alias\">$get_current_conversation_t_user_nickname</a>
							<span class=\"chat_messages_date_and_time\">";
							if($date_saying != "$get_message_date_saying"){
								echo"$get_message_date_saying ";
							}
							echo"$get_message_time_saying</span>";
							if($get_message_seen == "2"){
								echo" <img src=\"_gfx/seen_$get_message_seen.png\" alt=\"seen_$get_message_seen.png\" class=\"dm_message_seen_icon\" />";
							}
							if($get_current_conversation_t_user_id == "$my_user_id"){
								echo"
								<a href=\"dm.php?action=delete_message&amp;message_id=$get_message_id&amp;t_user_id=$get_to_user_id&amp;l=$l&amp;process=1\"><img src=\"_gfx/delete_grey_16x16.png\" alt=\"delete.png\" /></a>
								";
							}
							echo"<br />
								";
								// Attachment?
								if($get_message_attachment_file != ""){
									if(file_exists("$root/$get_message_attachment_path/$get_message_attachment_file")){
										if($get_message_attachment_type == "jpg" OR $get_message_attachment_type == "png" OR $get_message_attachment_type == "gif"){
											echo"
											<img src=\"$root/$get_message_attachment_path/$get_message_attachment_file\" alt=\"$get_message_attachment_path/$get_message_attachment_file\" /><br />
											\n";
										}
										else{
											$icon = $get_message_attachment_type . "_32x32.png";
											echo"
											<a href=\"$root/$get_message_attachment_path/$get_message_attachment_file\"><img src=\"_gfx/$icon\" alt=\"$icon\" style=\"float: left;\"></a>
											<a href=\"$root/$get_message_attachment_path/$get_message_attachment_file\" style=\"float: left;padding: 8px 0px 0px 8px;\">$get_message_attachment_file</a>
											<br class=\"clear\" />";
										}
									}
									else{
										echo"<a href=\"$root/$get_message_attachment_path/$get_message_attachment_file\"><img src=\"_gfx/dialog_warning_16x16.png\" alt=\"dialog_warning_16x16.png\"> Attachment not found</a>";
									}
								}
								echo"
							$get_message_text
							</p>
						<!-- //Name and text -->
					  </td>
					 </tr>
					</table>
					";

				}
	
			$variable_last_message_id  = "$get_message_id";

			// Update last message ID
			echo"
			<script language=\"javascript\" type=\"text/javascript\">
			\$(document).ready(function () {
				\$(\"#variable_last_message_id\").html($variable_last_message_id);
         		});
			</script>
			";

		} // messages


	} // to_user found

} // logged in


?>