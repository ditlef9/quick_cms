<?php 
/**
*
* File: chat/pm.php
* Version 1.0.0
* Date 10:25 01.09.2019
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

/*- Translation ------------------------------------------------------------------------ */


/*- Tables ---------------------------------------------------------------------------- */
include("_tables_chat.php");


/*- Functions ------------------------------------------------------------------------- */
if($chatEncryptionMethodDmsSav == "openssl_encrypt(AES-128-CBC)"){
	include("_encrypt_decrypt/openssl_encrypt_aes-128-cbc.php");
}
elseif($chatEncryptionMethodDmsSav == "caesar_cipher(random)"){
	include("_encrypt_decrypt/caesar_cipher.php");
}

// include("_webcamera/VideoStream.php");



/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);


if(isset($_GET['inp_file'])){
	$inp_file = $_GET['inp_file'];
	$inp_file = output_html($inp_file);
}
else{
	$inp_file = "";
}
if(isset($_GET['inp_thumb'])){
	$inp_thumb = $_GET['inp_thumb'];
	$inp_thumb = output_html($inp_thumb);
}
else{
	$inp_thumb = "";
}


/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['t_user_id'])){
	$t_user_id = $_GET['t_user_id'];
	$t_user_id = output_html($t_user_id);
}
else{
	$t_user_id = "";
}
$t_user_id_mysql = quote_smart($link, $t_user_id);

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
	$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$t_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_to_user_id, $get_to_user_email, $get_to_user_name, $get_to_user_alias, $get_to_user_rank) = $row;

	if($get_to_user_id == ""){
		echo"<h1>User not found</h1>";
	}
	else{

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



		// To photo
		$query = "SELECT photo_id, photo_destination, photo_thumb_40, photo_thumb_50 FROM $t_users_profile_photo WHERE photo_user_id=$get_to_user_id AND photo_profile_image='1'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_to_photo_id, $get_to_photo_destination, $get_to_photo_thumb_40, $get_to_photo_thumb_50) = $row;

		// To nickname
		$query = "SELECT nickname_id, nickname_user_id, nickname_value, nickname_datetime, nickname_datetime_saying FROM $t_chat_nicknames WHERE nickname_user_id=$get_to_user_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_to_nickname_id, $get_to_nickname_user_id, $get_to_nickname_value, $get_to_nickname_datetime, $get_to_nickname_datetime_saying) = $row;

		$inp_to_user_nickname_mysql = quote_smart($link, $get_to_nickname_value);

		$inp_to_user_name_mysql = quote_smart($link, $get_to_user_name);
		$inp_to_user_alias_mysql = quote_smart($link, $get_to_user_alias);
		$inp_to_user_image_path = "_uploads/users/images/$get_to_user_id";
		$inp_to_user_image_path_mysql = quote_smart($link, $inp_to_user_image_path);

		$inp_to_user_image_file_mysql = quote_smart($link, $get_to_photo_destination);
		$inp_to_user_image_thumb_a_mysql = quote_smart($link, $get_to_photo_thumb_40);
		$inp_to_user_image_thumb_b_mysql = quote_smart($link, $get_to_photo_thumb_50);


		// Look for conversation
		$query = "SELECT conversation_id, conversation_key, conversation_f_user_id, conversation_f_user_nickname, conversation_f_user_name, conversation_f_user_alias, conversation_f_image_path, conversation_f_image_file, conversation_f_image_thumb40, conversation_f_image_thumb50, conversation_f_has_blocked, conversation_f_unread_messages, conversation_t_user_id, conversation_t_user_nickname, conversation_t_user_name, conversation_t_user_alias, conversation_t_image_path, conversation_t_image_file, conversation_t_image_thumb40, conversation_t_image_thumb50, conversation_t_has_blocked, conversation_t_unread_messages, conversation_encryption_key, conversation_encryption_key_year, conversation_encryption_key_month FROM $t_chat_dm_conversations WHERE conversation_f_user_id=$get_my_user_id AND conversation_t_user_id=$get_to_user_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_conversation_id, $get_current_conversation_key, $get_current_conversation_f_user_id, $get_current_conversation_f_user_nickname, $get_current_conversation_f_user_name, $get_current_conversation_f_user_alias, $get_current_conversation_f_image_path, $get_current_conversation_f_image_file, $get_current_conversation_f_image_thumb40, $get_current_conversation_f_image_thumb50, $get_current_conversation_f_has_blocked, $get_current_conversation_f_unread_messages, $get_current_conversation_t_user_id, $get_current_conversation_t_user_nickname, $get_current_conversation_t_user_name, $get_current_conversation_t_user_alias, $get_current_conversation_t_image_path, $get_current_conversation_t_image_file, $get_current_conversation_t_image_thumb40, $get_current_conversation_t_image_thumb50, $get_current_conversation_t_has_blocked, $get_current_conversation_t_unread_messages, $get_current_conversation_encryption_key, $get_current_conversation_encryption_key_year, $get_current_conversation_encryption_key_month) = $row;

		if($get_current_conversation_id == ""){
			// Create conversation
			$inp_conversation_key = date("ymdhis");
			$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
			$charactersLength = strlen($characters);
			for ($i = 0; $i < 5; $i++) {
				$inp_conversation_key .= $characters[rand(0, $charactersLength - 1)];
			}
			$inp_conversation_key_mysql = quote_smart($link, $inp_conversation_key);

			mysqli_query($link, "INSERT INTO $t_chat_dm_conversations 
			(conversation_id, conversation_key, conversation_f_user_id, conversation_f_user_nickname, conversation_f_user_name, conversation_f_user_alias, conversation_f_image_path, conversation_f_image_file, conversation_f_image_thumb40, conversation_f_image_thumb50, conversation_f_has_blocked, conversation_f_unread_messages, conversation_t_user_id, conversation_t_user_nickname, conversation_t_user_name, conversation_t_user_alias, conversation_t_image_path, conversation_t_image_file, conversation_t_image_thumb40, conversation_t_image_thumb50, conversation_t_has_blocked, conversation_t_unread_messages) 
			VALUES 
			(NULL, $inp_conversation_key_mysql, $get_my_user_id, $inp_my_user_nickname_mysql, $inp_my_user_name_mysql, $inp_my_user_alias_mysql, $inp_my_user_image_path_mysql, $inp_my_user_image_file_mysql, $inp_my_user_image_thumb_a_mysql, $inp_my_user_image_thumb_b_mysql, '0', '0', $get_to_user_id, $inp_to_user_nickname_mysql, $inp_to_user_name_mysql, $inp_to_user_alias_mysql, $inp_to_user_image_path_mysql, $inp_to_user_image_file_mysql, $inp_to_user_image_thumb_a_mysql, $inp_to_user_image_thumb_b_mysql, '0', '0')")
			or die(mysqli_error($link));

			$query = "SELECT conversation_id, conversation_key, conversation_f_user_id, conversation_f_user_nickname, conversation_f_user_name, conversation_f_user_alias, conversation_f_image_path, conversation_f_image_file, conversation_f_image_thumb40, conversation_f_image_thumb50, conversation_f_has_blocked, conversation_f_unread_messages, conversation_t_user_id, conversation_t_user_nickname, conversation_t_user_name, conversation_t_user_alias, conversation_t_image_path, conversation_t_image_file, conversation_t_image_thumb40, conversation_t_image_thumb50, conversation_t_has_blocked, conversation_t_unread_messages, conversation_encryption_key, conversation_encryption_key_year, conversation_encryption_key_month FROM $t_chat_dm_conversations WHERE conversation_f_user_id=$get_my_user_id AND conversation_t_user_id=$get_to_user_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_conversation_id, $get_current_conversation_key, $get_current_conversation_f_user_id, $get_current_conversation_f_user_nickname, $get_current_conversation_f_user_name, $get_current_conversation_f_user_alias, $get_current_conversation_f_image_path, $get_current_conversation_f_image_file, $get_current_conversation_f_image_thumb40, $get_current_conversation_f_image_thumb50, $get_current_conversation_f_has_blocked, $get_current_conversation_f_unread_messages, $get_current_conversation_t_user_id, $get_current_conversation_t_user_nickname, $get_current_conversation_t_user_name, $get_current_conversation_t_user_alias, $get_current_conversation_t_image_path, $get_current_conversation_t_image_file, $get_current_conversation_t_image_thumb40, $get_current_conversation_t_image_thumb50, $get_current_conversation_t_has_blocked, $get_current_conversation_t_unread_messages, $get_current_conversation_encryption_key, $get_current_conversation_encryption_key_year, $get_current_conversation_encryption_key_month) = $row;
		}


		// Also check that the other user has conversation
		$query = "SELECT conversation_id, conversation_key, conversation_f_user_id, conversation_f_user_nickname, conversation_f_user_name, conversation_f_user_alias, conversation_f_image_path, conversation_f_image_file, conversation_f_image_thumb40, conversation_f_image_thumb50, conversation_f_has_blocked, conversation_f_unread_messages, conversation_t_user_id, conversation_t_user_nickname, conversation_t_user_name, conversation_t_user_alias, conversation_t_image_path, conversation_t_image_file, conversation_t_image_thumb40, conversation_t_image_thumb50, conversation_t_has_blocked, conversation_t_unread_messages FROM $t_chat_dm_conversations WHERE conversation_f_user_id=$get_to_user_id AND conversation_t_user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_to_conversation_id, $get_to_conversation_key, $get_to_conversation_f_user_id, $get_to_conversation_f_user_nickname, $get_to_conversation_f_user_name, $get_to_conversation_f_user_alias, $get_to_conversation_f_image_path, $get_to_conversation_f_image_file, $get_to_conversation_f_image_thumb40, $get_to_conversation_f_image_thumb50, $get_to_conversation_f_has_blocked, $get_to_conversation_f_unread_messages, $get_to_conversation_t_user_id, $get_to_conversation_t_user_nickname, $get_to_conversation_t_user_name, $get_to_conversation_t_user_alias, $get_to_conversation_t_image_path, $get_to_conversation_t_image_file, $get_to_conversation_t_image_thumb40, $get_to_conversation_t_image_thumb50, $get_to_conversation_t_has_blocked, $get_to_conversation_t_unread_messages) = $row;
		if($get_to_conversation_id == ""){
			// Insert
			$inp_conversation_key_mysql = quote_smart($link, $get_current_conversation_key);

			mysqli_query($link, "INSERT INTO $t_chat_dm_conversations 
			(conversation_id, conversation_key, conversation_f_user_id, conversation_f_user_nickname, conversation_f_user_name, conversation_f_user_alias, conversation_f_image_path, conversation_f_image_file, conversation_f_image_thumb40, conversation_f_image_thumb50, conversation_f_has_blocked, conversation_f_unread_messages, conversation_t_user_id, conversation_t_user_nickname, conversation_t_user_name, conversation_t_user_alias, conversation_t_image_path, conversation_t_image_file, conversation_t_image_thumb40, conversation_t_image_thumb50, conversation_t_has_blocked, conversation_t_unread_messages) 
			VALUES 
			(NULL, $inp_conversation_key_mysql, $get_to_user_id, $inp_to_user_nickname_mysql, $inp_to_user_name_mysql, $inp_to_user_alias_mysql, $inp_to_user_image_path_mysql, $inp_to_user_image_file_mysql, $inp_to_user_image_thumb_a_mysql, $inp_to_user_image_thumb_b_mysql, '0', '0', $get_my_user_id, $inp_my_user_nickname_mysql, $inp_my_user_name_mysql, $inp_my_user_alias_mysql, $inp_my_user_image_path_mysql, $inp_my_user_image_file_mysql, $inp_my_user_image_thumb_a_mysql, $inp_my_user_image_thumb_b_mysql, '0', '0')")
			or die(mysqli_error($link));
	
			$query = "SELECT conversation_id, conversation_key, conversation_f_user_id, conversation_f_user_nickname, conversation_f_user_name, conversation_f_user_alias, conversation_f_image_path, conversation_f_image_file, conversation_f_image_thumb40, conversation_f_image_thumb50, conversation_f_has_blocked, conversation_f_unread_messages, conversation_t_user_id, conversation_t_user_nickname, conversation_t_user_name, conversation_t_user_alias, conversation_t_image_path, conversation_t_image_file, conversation_t_image_thumb40, conversation_t_image_thumb50, conversation_t_has_blocked, conversation_t_unread_messages FROM $t_chat_dm_conversations WHERE conversation_f_user_id=$get_to_user_id AND conversation_t_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_to_conversation_id, $get_to_conversation_key, $get_to_conversation_f_user_id, $get_to_conversation_f_user_nickname, $get_to_conversation_f_user_name, $get_to_conversation_f_user_alias, $get_to_conversation_f_image_path, $get_to_conversation_f_image_file, $get_to_conversation_f_image_thumb40, $get_to_conversation_f_image_thumb50, $get_to_conversation_f_has_blocked, $get_to_conversation_f_unread_messages, $get_to_conversation_t_user_id, $get_to_conversation_t_user_nickname, $get_to_conversation_t_user_name, $get_to_conversation_t_user_alias, $get_to_conversation_t_image_path, $get_to_conversation_t_image_file, $get_to_conversation_t_image_thumb40, $get_to_conversation_t_image_thumb50, $get_to_conversation_t_has_blocked, $get_to_conversation_t_unread_messages) = $row;
		}

		// Block check
		if($get_current_conversation_f_has_blocked == "1"){
			echo"Blocked";
		}
		else{

			/*- Headers ---------------------------------------------------------------------------------- */
			$website_title = "$get_current_conversation_t_user_alias - $l_chat";
			if(file_exists("./favicon.ico")){ $root = "."; }
			elseif(file_exists("../favicon.ico")){ $root = ".."; }
			elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
			elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
			include("$root/_webdesign/header.php");

			if($action == ""){
				$time = time();
				$date_saying = date("j M Y");
				$day = date("d");

				echo"

				<!-- Messages -->
					<!-- Set messages to 100 % height -->
						<script language=\"javascript\" type=\"text/javascript\">
						\$(document).ready(function(){
							var height = \$(window).height() - 130;
							\$('#messages').height(height);
						});
						\$(window).resize(function(){
							var height = \$(window).height() - 200;
							\$('#messages').css('height', \$(window).height());
         				   	});
						</script>
					<!-- //Set messages to 100 % height -->


					<div id=\"messages\">";
					// Set all messages read
					$result = mysqli_query($link, "UPDATE $t_chat_dm_conversations SET conversation_f_unread_messages=0 WHERE conversation_id=$get_current_conversation_id") or die(mysqli_error($link));

					// Get messages
					$start_day = "";
					$variable_last_message_id = "1";
					$x = 0;

					$last_conversation_user_id = "";

					$query = "SELECT message_id, message_conversation_key, message_type, message_text, message_datetime, message_date_saying, message_time_saying, message_time, message_year, message_day, message_seen, message_attachment_type, message_attachment_path, message_attachment_file, message_from_user_id, message_from_ip, message_from_hostname, message_from_user_agent FROM $t_chat_dm_messages WHERE message_conversation_key='$get_current_conversation_key' ORDER BY message_id ASC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_message_id, $get_message_conversation_key, $get_message_type, $get_message_text, $get_message_datetime, $get_message_date_saying, $get_message_time_saying, $get_message_time, $get_message_year, $get_message_day, $get_message_seen, $get_message_attachment_type, $get_message_attachment_path, $get_message_attachment_file, $get_message_from_user_id, $get_message_from_ip, $get_message_from_hostname, $get_message_from_user_agent) = $row;
	
									
						if($x > 250){
							$result_del = mysqli_query($link, "DELETE FROM $t_chat_dm_messages WHERE message_id=$get_message_id");
							// Attachment?
							if($get_message_attachment_file != "" && file_exists("$root/$get_message_attachment_path/$get_message_attachment_file")){
								unlink("$root/$get_message_attachment_path/$get_message_attachment_file");
							}

						}

						// New day?
						if($get_message_day != "$start_day"){
							echo"
							<div class=\"chat_new_day\">
								<p>$get_message_date_saying</p>
							</div>
							";
							$start_day = "$get_message_day";
							$last_conversation_user_id = ""; // show image again
						}


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
								<!-- Img -->";
									if($last_conversation_user_id == "$get_current_conversation_f_user_id"){
										echo"
										<div style=\"width: 40px;\"></div>";
									}
									else{
										echo"
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
										</p>";
									}
									echo"
								<!-- //Img -->
							  </td>
							  <td style=\"vertical-align:top;\">
								<!-- Name and text -->
									<p>";
									if($last_conversation_user_id == "$get_current_conversation_f_user_id"){
									}
									else{
										echo"
										<a href=\"$root/users/view_profile.php?user_id=$get_current_conversation_f_user_id&amp;l=$l\" class=\"chat_messages_from_user_alias\" title=\"$get_current_conversation_f_user_alias\">$get_current_conversation_f_user_nickname</a>
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
									}
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
									echo"$get_message_text
									</p>
								<!-- //Name and text -->
							  </td>
							 </tr>
							</table>
							";


							// Last conversation user id 
							$last_conversation_user_id = "$get_current_conversation_f_user_id";

		
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
								<!-- Img -->";
									if($last_conversation_user_id == "$get_current_conversation_t_user_id"){
										echo"
										<div style=\"width: 40px;\"></div>";
									}
									else{
										echo"
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
										";
									}
									echo"
								<!-- //Img -->
							  </td>
							  <td style=\"vertical-align:top;\">
								<!-- Name and text -->
									<p>";
									if($last_conversation_user_id == "$get_current_conversation_t_user_id"){
										echo"
										";
									}
									else{
										echo"
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
									}
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

							// Last conversation user id 
							$last_conversation_user_id = "$get_current_conversation_t_user_id";
						}


						// Update last message ID
						$variable_last_message_id = "$get_message_id";

						$x++;
					} // messages
					echo"

					</div>


					<span id=\"variable_last_message_id\">$variable_last_message_id</span>
							
					<!-- Get new message script -->
						<script language=\"javascript\" type=\"text/javascript\">
							\$(document).ready(function () {
								var scrolled = false;
								\$('#messages').scrollTop(\$('#messages')[0].scrollHeight);
								function get_messages(){
									var variable_last_message_id = \$('#variable_last_message_id').html(); 
									var data = 'l=$l&to_user_id=$get_to_user_id&last_message_id=' + variable_last_message_id;
            								\$.ajax({
                								type: \"POST\",
               									url: \"dm_get_messages.php\",
                								data: data,
										beforeSend: function(html) { // this happens before actual call
										},
               									success: function(html){
                    									\$(\"#messages\").append(html);

											// We want to scroll to bottom if user is not scrolling
											if(!scrolled){
												\$('#messages').scrollTop(\$('#messages')[0].scrollHeight);
												var scrolled = false;
              										}
              									}
									});
								}
								setInterval(get_messages,5000);

								// Has the user scrolled?
								\$(\"#messages\").on('scroll', function(){
									scrolled=true;
								});
         				   		});
						</script>
					<!-- //Get new message script -->

				<!-- //Messages -->


				<!-- Webamera chat -->
							
					<div id=\"webcamera_chat\">
						<!-- My camera -->
							<div id=\"webcamera_my_camera\">
								<video autoplay=\"true\" id=\"my_camera\">
	
								</video>
								<button id=\"start_webcamera\">Start Video</button>
								<button id=\"stop_webcamera\">Stop Video</button>

							<!-- My camera javascripts -->
								<script>
								var video = document.querySelector(\"#my_camera\");
    								var startVideo = document.querySelector(\"#start_webcamera\");
    								var stopVideo = document.querySelector(\"#stop_webcamera\");


    								startVideo.addEventListener(\"click\", start, false);
								function start(e) {
									if (navigator.mediaDevices.getUserMedia) {
										navigator.mediaDevices.getUserMedia({ video: true })
										.then(function (stream) {
											video.srcObject = stream;
										})
										.catch(function (err0r) {
											console.log(\"Something went wrong!\");
										});
									}
								}


    								stopVideo.addEventListener(\"click\", stop, false);
								function stop(e) {
									var stream = video.srcObject;
									var tracks = stream.getTracks();

									for (var i = 0; i < tracks.length; i++) {
										var track = tracks[i];
										track.stop();
									}

									video.srcObject = null;
								}
								</script>
							<!-- //My camera javascripts -->
						</div>
						<!-- //My camera -->

						<!-- Other camera -->

								";

								// $stream = new VideoStream("_uploads/$get_current_conversation_id.mp4");
								// $stream->start();
								echo"

						<!-- //Other camera -->
						
					</div>
				<!-- //Webamera chat -->



				<!-- New message form -->
					<div id=\"new_message_form\">
						<!-- Focus -->
							<script>
							\$(document).ready(function(){
								\$('[name=\"inp_text\"]').focus();
							});
						</script>
						<!-- //Focus -->
					
						
						<form>
						<p>
						<input type=\"text\" name=\"inp_text\" id=\"inp_text\" value=\"\" size=\"25\" style=\"width: 80%;\" tabindex=\""; $tabindex = $tabindex+1; echo"$tabindex\" />
						<a href=\"#\" id=\"emojies_selector_toggle\" class=\"btn_default\" tabindex=\""; $tabindex = $tabindex+1; echo"$tabindex\" />&#128578;</a>
						\n";
						if($chatWebcameraChatActiveDmsSav == "1"){
							echo"<a href=\"#\" id=\"webcamera_toggle\" class=\"btn_default\" tabindex=\""; $tabindex = $tabindex+1; echo"$tabindex\" />&#128247;</a>\n";
						}
						echo"	
						<a href=\"#\" id=\"attachment_selector_toggle\" class=\"btn_default\" tabindex=\""; $tabindex = $tabindex+1; echo"$tabindex\" />&#128206;</a>
						<a href=\"#\" id=\"inp_message_send\" class=\"btn_default\" tabindex=\""; $tabindex = $tabindex+1; echo"$tabindex\" />$l_send</a>
						</p>
						</form>
					</div>	
						<!-- emojies, attachment and webcamera selector_toggle -->
							<script>
								$(document).ready(function(){
									$(\"#emojies_selector_toggle\").click(function () {
										\$(\"#emojies_selector\").toggle();	
									});
									$(\"#webcamera_toggle\").click(function () {
										\$('#webcamera_chat').toggle('slow', function() {
											if(\$(this).is(':hidden')) { 
												\$(\"#stop_webcamera\").click();
											}
											else {
												\$(\"#start_webcamera\").click();
											}
										}); 
									});
									$(\"#attachment_selector_toggle\").click(function () {
										\$(\"#attachment_selector\").toggle();	
										\$(\"#attachment_selector\").css('visibility', 'visible');
									});
								});
							</script>
						<!-- //emojies, attachment and webcamera selector toggle -->

						<!-- Emojies -->
							<div id=\"emojies_selector\">
								<div id=\"emojies_selector_header\">
									<ul>
										<li><a href=\"#\" class=\"emojies_selector emojies_toggle\" data-divid=\"emojies_selector_recent\">&#128347;</a></li>\n";
									$query = "SELECT main_category_id, main_category_title, main_category_code, main_category_char, main_category_source_path, main_main_category_source_file, main_category_source_ext, main_category_weight, main_category_is_active, main_category_language FROM $t_emojies_categories_main";
									$result = mysqli_query($link, $query);
									while($row = mysqli_fetch_row($result)) {
										list($get_main_category_id, $get_main_category_title, $get_main_category_code, $get_main_category_char, $get_main_category_source_path, $get_main_main_category_source_file, $get_main_category_source_ext, $get_main_category_weight, $get_main_category_is_active, $get_main_category_language) = $row;
										echo"									";
										echo"<li><a href=\"#\" class=\"emojies_selector emojies_toggle\" data-divid=\"emojies_selector_main_category_id_$get_main_category_id\">$get_main_category_char</a></li>\n";
									}
									echo"
									</ul>
								</div>
								<div id=\"emojies_selector_body\">
									";
									// Recent
									echo"
										<div class=\"emojies_selector_emojies emojies_selector_recent\">
									
											<table>
											 <tbody>";
											$smileys_per_row = 11;
											$x=0;
											$query_emojies = "SELECT recent_used_id, recent_used_user_id, recent_used_datetime, recent_used_emoji_id, recent_used_sub_category_id, recent_used_main_category_id, recent_used_emoji_title, recent_used_emoji_replace_a, recent_used_emoji_code, recent_used_emoji_char, recent_used_emoji_source_path, recent_used_emoji_source_file, recent_used_emoji_source_ext FROM $t_emojies_users_recent_used WHERE recent_used_user_id=$get_my_user_id ORDER BY recent_used_counter DESC";
											$result_emojies = mysqli_query($link, $query_emojies);
											while($row_emojies = mysqli_fetch_row($result_emojies)) {
												list($get_recent_used_id, $get_recent_used_user_id, $get_recent_used_datetime, $get_recent_used_emoji_id, $get_recent_used_sub_category_id, $get_recent_used_main_category_id, $get_recent_used_emoji_title, $get_recent_used_emoji_replace_a, $get_recent_used_emoji_code, $get_recent_used_emoji_char, $get_recent_used_emoji_source_path, $get_recent_used_emoji_source_file, $get_recent_used_emoji_source_ext) = $row_emojies;


												// Div ID
												$divid = "$get_recent_used_emoji_char";
												if($chatCompensateForEmojisStringErrorSav == "1"){
													if($get_recent_used_emoji_replace_a == ""){
														$divid = ":$get_recent_used_emoji_title:";
													}
													else{
														$divid = "$get_recent_used_emoji_replace_a";
													}
												}

												if($x == "0"){
													echo"											";
													echo" <tr> <td>\n";
												}
												else{
													echo"											";
													echo"  <td>\n";
												}
												
												echo"												";
												echo"<a href=\"#\" class=\"emoji_select\" data-divid=\"$divid\">$get_recent_used_emoji_char</a>\n";


												if($x == "$smileys_per_row"){
													echo"											";
													echo"   </td>\n";
													echo"											";
													echo"  </tr>\n";
													$x = -1;
												}
												else{
													echo"											";
													echo"  </td>\n";
												}
												$x = $x+1;
												
											}
											if($x != "0"){
												echo"
												  </td>
												 </tr>
												";
											}
											echo"
											 </tbody>
											</table>
										</div> <!-- //emojies_selector_recent -->
									";


									// Rest
									$query = "SELECT main_category_id, main_category_title, main_category_code, main_category_char, main_category_source_path, main_main_category_source_file, main_category_source_ext, main_category_weight, main_category_is_active, main_category_language FROM $t_emojies_categories_main";
									$result = mysqli_query($link, $query);
									while($row = mysqli_fetch_row($result)) {
										list($get_main_category_id, $get_main_category_title, $get_main_category_code, $get_main_category_char, $get_main_category_source_path, $get_main_main_category_source_file, $get_main_category_source_ext, $get_main_category_weight, $get_main_category_is_active, $get_main_category_language) = $row;
										echo"
										<div class=\"emojies_selector_emojies emojies_selector_main_category_id_$get_main_category_id\">
									

											<table>
											 <tbody>";
											$x=0;
											$query_emojies = "SELECT emoji_id, emoji_main_category_id, emoji_sub_category_id, emoji_title, emoji_replace_a, emoji_code, emoji_char, emoji_source_path, emoji_source_file, emoji_source_ext, emoji_skin_tone, emoji_created_by_user_id, emoji_created_datetime, emoji_updated_by_user_id, emoji_updated_datetime, emoji_used_count, emoji_last_used_datetime FROM $t_emojies_index WHERE emoji_main_category_id=$get_main_category_id";
											$result_emojies = mysqli_query($link, $query_emojies);
											while($row_emojies = mysqli_fetch_row($result_emojies)) {
												list($get_emoji_id, $get_emoji_main_category_id, $get_emoji_sub_category_id, $get_emoji_title, $get_emoji_replace_a, $get_emoji_code, $get_emoji_char, $get_emoji_source_path, $get_emoji_source_file, $get_emoji_source_ext, $get_emoji_skin_tone, $get_emoji_created_by_user_id, $get_emoji_created_datetime, $get_emoji_updated_by_user_id, $get_emoji_updated_datetime, $get_emoji_used_count, $get_emoji_last_used_datetime) = $row_emojies;


												// Div ID
												$divid = "$get_emoji_char";
												if($chatCompensateForEmojisStringErrorSav == "1"){
													if($get_emoji_replace_a == ""){
														$divid = ":$get_emoji_title:";
													}
													else{
														$divid = "$get_emoji_replace_a";
													}
												}
												

												if($x == "0"){
													echo"											";
													echo" <tr> <td>\n";
												}
												else{
													echo"											";
													echo"  <td>\n";
												}
												
												echo"												";
												echo"<a href=\"#\" class=\"emoji_select\" data-divid=\"$divid\">$get_emoji_char</a>\n";


												if($x == "$smileys_per_row"){
													echo"											";
													echo" </tr> </td>\n";
													$x = -1;
												}
												else{
													echo"											";
													echo"  </td>\n";
												}
												$x = $x+1;
												
											}
											if($x != "0"){
												echo"
												  </td>
												 </tr>
												";
											}
											echo"
											 </tbody>
											</table>
										</div> <!-- //emojies_selector_main_category_id_$get_main_category_id -->
										";
									} // categories
									echo"
								</div>
							</div>
							<!-- Emojies javascript click on headline open emojies  -->
							<script>
								$(document).ready(function(){
									$(\".emojies_toggle\").click(function () {
										\$(\".emojies_selector_emojies\").hide();
										var idname= $(this).data('divid');
										\$(\".\"+idname).toggle();	
										
									});
			
								});
							</script>
							<!-- //Emojies javascript click on headline open emojies -->

							<!-- Emojies javascript click on emoji append to text -->
								<script type=\"text/javascript\">
								\$(function() {
									\$('.emoji_select').click(function() {
										var emoji = \$(this).data('divid');
            									\$('#inp_text').val(\$('#inp_text').val() + emoji);

										// Close
										
            									return false;
       									});
    								});
								</script>
							<!-- //Emojies javascript click on emoji append to text -->

						<!-- //Emojies -->
						
						<!-- Attachment -->";

							if(isset($_GET['ft_attachment']) && isset($_GET['fm_attachment'])){
								$ft_attachment = $_GET['ft_attachment'];
								$ft_attachment = output_html($ft_attachment);

								$fm_attachment = $_GET['fm_attachment'];
								$fm_attachment = output_html($fm_attachment);
								$fm_attachment = str_replace("_", " ", $fm_attachment);
								$fm_attachment = ucfirst($fm_attachment);
								echo"
								<div id=\"attachment_selector\" style=\"display: block;\">X
									<div class=\"$ft_attachment\"><span>$fm_attachment</span></div>
								";
							}
							else{
								echo"
								<div id=\"attachment_selector\">
								";
							}
							echo"
								<!-- New attachment upload -->
									<form method=\"POST\" action=\"dm_upload_file_as_attachment.php?t_user_id=$t_user_id&amp;l=$l&amp;process=1\" id=\"dm_upload_file_as_attachment_form_data\" enctype=\"multipart/form-data\">
					
										<p><b>$l_new_file</b> (jpg, png, gif, docx, pdf, txt)<br />
										<input type=\"file\" name=\"inp_file\" tabindex=\""; $tabindex = $tabindex+1; echo"$tabindex\" />
										<input type=\"submit\" value=\"$l_upload\" tabindex=\""; $tabindex = $tabindex+1; echo"$tabindex\" />
										</p>
									</form>
						
								<!-- //New attachment upload -->
							</div>


							<!-- On file selected send form -->
								<script type=\"text/javascript\">
								\$(document).ready(function(){
									\$('input[type=\"file\"]').change(function(){
            									\$(\"#dm_upload_file_as_attachment_form_data\").submit();
									});
								});
								</script>
							<!-- //On file selected send form -->


						<!-- //Attachment -->
						
					<div style=\"height: 5px;\"></div>

					<!-- Send new message script -->
						<script id=\"source\" language=\"javascript\" type=\"text/javascript\">
							\$(document).ready(function () {

							\$('#inp_text').keypress(function (e) {
								if (e.which == 13) {
									myfunc();
   									return false;
								}
							});


							\$('#inp_message_send').click(function(){
								myfunc();
   								return false;
							});
							
							function myfunc () {
								// getting the value that user typed
       								var inp_text = $(\"#inp_text\").val();
								inp_text = inp_text.replace(\"&\", \":amp;:\");
       								var inp_attachment_file = $(\"#inp_attachment_file\").val();
 								// forming the queryString
								var data = 'l=$l&t_user_id=$get_current_conversation_t_user_id&inp_attachment_file='+ inp_attachment_file + '&inp_text='+ inp_text;
         
        							// if searchString is not empty
        							if(inp_text) {
           								// ajax call
            								\$.ajax({
                								type: \"POST\",
               									url: \"dm_send_message.php\",
                								data: data,
										beforeSend: function(html) { // this happens before actual call
                    								
										},
               									success: function(html){
                    									\$(\"#messages\").append(html);
                    									\$(\"#inp_text\").val('');
                    									\$(\"#inp_attachment_file\").val('');
											\$('#messages').scrollTop(\$('#messages')[0].scrollHeight);

											// Reset upload image somehow
											\$(\"#dm_upload_file_as_attachment_preview\").hide();
											\$(\"#dm_upload_file_as_attachment_form\").css('visibility', 'visible');
              									}
            								});
       								}
        							return false;
            						}
         				   		});
							</script>
					<!-- //Send new message script -->

				<!-- //New message form -->
				";
			} // action == ""
			elseif($action == "delete_message"){
				if(isset($_GET['message_id'])){
					$message_id = $_GET['message_id'];
					$message_id = output_html($message_id);
				}
				else{
					$message_id = "";
				}
				$message_id_mysql = quote_smart($link, $message_id);

					
				// Find pm
				$query = "SELECT message_id, message_from_user_id, message_attachment_type, message_attachment_path, message_attachment_file FROM $t_chat_dm_messages WHERE message_id=$message_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_message_id, $get_current_message_from_user_id, $get_message_attachment_type, $get_message_attachment_path, $get_message_attachment_file) = $row;
				if($get_current_message_id != ""){
					if($get_current_message_from_user_id == "$my_user_id"){
						$result = mysqli_query($link, "DELETE FROM $t_chat_dm_messages WHERE message_id=$get_current_message_id");

						// Attachment?
						if($get_message_attachment_file != "" && file_exists("$root/$get_message_attachment_path/$get_message_attachment_file")){
							unlink("$root/$get_message_attachment_path/$get_message_attachment_file");
						}
						$url = "dm.php?t_user_id=$t_user_id&l=$l";
						header("Location: $url");
						exit;
					}
					else{
						echo"<p>Access to message denied</p>";
					}
				}
				else{
					echo"<p>Message not found</p>";
				}
			} // delete message
		} // not blocked
	} // user found

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