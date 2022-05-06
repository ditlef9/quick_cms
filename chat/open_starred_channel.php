<?php 
/**
*
* File: discuss/view_topic.php
* Version 1.0.0
* Date 12:05 10.02.2018
* Copyright (c) 2011-2018 S. A. Ditlefsen
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

	// Make sure that I have a nickname
	$query = "SELECT nickname_id, nickname_user_id, nickname_value, nickname_datetime, nickname_datetime_saying FROM $t_chat_nicknames WHERE nickname_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_nickname_id, $get_my_nickname_user_id, $get_my_nickname_value, $get_my_nickname_datetime, $get_my_nickname_datetime_saying) = $row;
	if($get_my_nickname_id == ""){
		// Dates
		$datetime = date("Y-m-d H:i:s");
		$datetime_saying = date("j. M Y H:i");

		// nickname variables
		$found_nickname = "0";

		// Create a nickname
		$query = "SELECT profile_id, profile_user_id, profile_first_name, profile_middle_name, profile_last_name FROM $t_users_profile WHERE profile_user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_profile_id, $get_my_profile_user_id, $get_my_profile_first_name, $get_my_profile_middle_name, $get_my_profile_last_name) = $row;
		
		if($get_my_profile_first_name != ""){
			$inp_nickname_value = "$get_my_profile_first_name";
			$inp_nickname_value_mysql = quote_smart($link, $inp_nickname_value);
	
			$query = "SELECT nickname_id FROM $t_chat_nicknames WHERE nickname_value=$inp_nickname_value_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_check_nickname_id) = $row;
			if($get_check_nickname_id == ""){
				// We can take this nickname
				mysqli_query($link, "INSERT INTO $t_chat_nicknames 
				(nickname_id, nickname_user_id, nickname_value, nickname_datetime, nickname_datetime_saying) 
				VALUES 
				(NULL, $get_my_profile_user_id, $inp_nickname_value_mysql, '$datetime', '$datetime_saying')")
				or die(mysqli_error($link));

				$found_nickname = "1";
			}
			else{
				// Try first name, middle name
				if($get_my_profile_middle_name != ""){
					$inp_nickname_value = "$get_my_profile_first_name $get_my_profile_middle_name";
					$inp_nickname_value_mysql = quote_smart($link, $inp_nickname_value);
	
					$query = "SELECT nickname_id FROM $t_chat_nicknames WHERE nickname_value=$inp_nickname_value_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_check_nickname_id) = $row;
					if($get_check_nickname_id == ""){
						// We can take this nickname
						mysqli_query($link, "INSERT INTO $t_chat_nicknames 
						(nickname_id, nickname_user_id, nickname_value, nickname_datetime, nickname_datetime_saying) 
						VALUES 
						(NULL, $get_my_profile_user_id, $inp_nickname_value_mysql, '$datetime', '$datetime_saying')")
						or die(mysqli_error($link));

						$found_nickname = "1";
					}
				
				}
			}
		}
		if($found_nickname == "0"){
			// Take username as nickname
			$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;
			$inp_nickname_value_mysql = quote_smart($link, $get_my_user_name);
			mysqli_query($link, "INSERT INTO $t_chat_nicknames 
			(nickname_id, nickname_user_id, nickname_value, nickname_datetime, nickname_datetime_saying) 
			VALUES 
			(NULL, $get_my_user_id, $inp_nickname_value_mysql, '$datetime', '$datetime_saying')")
			or die(mysqli_error($link));

			
		}
	} // Create nickname

	// Get my settings
	$query = "SELECT user_setting_id, user_setting_user_id, user_setting_show_channel_info FROM $t_chat_user_settings WHERE user_setting_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_user_setting_id, $get_current_user_setting_user_id, $get_current_user_setting_show_channel_info) = $row;
	if($get_current_user_setting_id == ""){
		mysqli_query($link, "INSERT INTO $t_chat_user_settings 
		(user_setting_id, user_setting_user_id, user_setting_show_channel_info) 
		VALUES 
		(NULL, $my_user_id_mysql, 0)")
		or die(mysqli_error($link));
	}


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
			/*- Headers ---------------------------------------------------------------------------------- */
			$website_title = "#$get_current_channel_name - $l_chat";
			if(file_exists("./favicon.ico")){ $root = "."; }
			elseif(file_exists("../favicon.ico")){ $root = ".."; }
			elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
			elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
			include("$root/_webdesign/header.php");

			if($action == ""){
				$time = time();
				$day = date("d");
				$date_saying = date("j M Y");


				echo"
				<!-- Where am I ? -->
				<!-- //Where am I ? -->

				<!-- Messages and users online -->
					<table style=\"width: 100%;\">
					 <tr>
					  <td style=\"vertical-align: top;\">
						<!-- Messages -->
							<div id=\"messages\">
								<!-- Set messages to 100 % height -->
								<script language=\"javascript\" type=\"text/javascript\">
								\$(document).ready(function(){
									var height = \$(window).height() - 130;
									\$('#messages').height(height);
									\$('#users_in_channel').height(height);
								});
								\$(window).resize(function(){
									var height = \$(window).height() - 200;
									\$('#messages').css('height', \$(window).height());
									\$('#users_in_channel').css('height', \$(window).height());
         				   			});
								</script>
								<!-- //Set messages to 100 % height -->
								";
								// Set all messages read
								$result = mysqli_query($link, "UPDATE $t_chat_users_starred_channels SET new_messages=0 WHERE starred_channel_id=$get_current_starred_channel_id") or die(mysqli_error($link));

								// Get messages
								$start_day = "";
								$variable_last_message_id = "1";
								$query = "SELECT message_id, message_channel_id, message_type, message_text, message_datetime, message_date_saying, message_time_saying, message_time, message_year, message_day, message_from_user_id, message_from_user_nickname, message_from_user_name, message_from_user_alias, message_from_user_image_path, message_from_user_image_file, message_from_user_image_thumb_40, message_from_user_image_thumb_50, message_from_ip, message_from_hostname, message_from_user_agent, message_attachment_type, message_attachment_path, message_attachment_file FROM $t_chat_channels_messages WHERE message_channel_id=$get_current_channel_id ORDER BY message_id ASC";
								//echo"$query ";
								$result = mysqli_query($link, $query);
								while($row = mysqli_fetch_row($result)) {
									list($get_message_id, $get_message_channel_id, $get_message_type, $get_message_text, $get_message_datetime, $get_message_date_saying, $get_message_time_saying, $get_message_time, $get_message_year, $get_message_day, $get_message_from_user_id, $get_message_from_user_nickname, $get_message_from_user_name, $get_message_from_user_alias, $get_message_from_user_image_path, $get_message_from_user_image_file, $get_message_from_user_image_thumb_40, $get_message_from_user_image_thumb_50, $get_message_from_ip, $get_message_from_hostname, $get_message_from_user_agent, $get_message_attachment_type, $get_message_attachment_path, $get_message_attachment_file) = $row;
	
									// Is the message X days old?
									$time_since_written = $time-$get_message_time;
									$days_since_written = round($time_since_written  / (60 * 60 * 24));

									if($days_since_written > 100){
										$result_del = mysqli_query($link, "DELETE FROM $t_chat_channels_messages WHERE message_id=$get_message_id");
									}

									// New day?
									if($get_message_day != "" && $get_message_day != "$start_day"){
										echo"
										<div class=\"chat_new_day\">
											<p>$get_message_date_saying</p>
										</div>
										";
										$start_day = "$get_message_day";
									}

									if($get_message_type == "info"){
										if($get_current_user_setting_show_channel_info == "1"){
											echo"
											<!-- Info -->
											<p class=\"chat_messages_info\">
											$get_message_text
											<span class=\"chat_messages_date_and_time\">";
											if($date_saying != "$get_message_date_saying"){
											echo"$get_message_date_saying ";
											}
											echo"$get_message_time_saying</span>
											</p>
											<!-- //Info -->
											";
										} // show info
									}
									else{
										// Decrypt message
										if($chatEncryptionMethodChannelsSav == "none"){
											
										}
										elseif($chatEncryptionMethodChannelsSav == "openssl_encrypt(AES-128-CBC)"){
											$get_message_text = openssl_decrypt_aes_128_cbc_decrypt($get_message_text, $get_current_channel_encryption_key);
										}
										elseif($chatEncryptionMethodChannelsSav == "caesar_cipher(random)"){
											$cipher = new KKiernan\CaesarCipher();
											$get_message_text = $cipher->encrypt($get_message_text, -$get_current_channel_encryption_key);
										}
										echo"
										<table>
										 <tr>
										  <td style=\"padding: 5px 5px 0px 0px;vertical-align:top;\">
											<!-- Img -->
											<p>";
											if($get_message_from_user_image_file != ""){
												if(file_exists("$root/$get_message_from_user_image_path/$get_message_from_user_image_file")){
													if(!(file_exists("$root/$get_message_from_user_image_path/$get_message_from_user_image_thumb_40")) && $get_message_from_user_image_thumb_40 != ""){
														// Make thumb
														$inp_new_x = 40; // 950
														$inp_new_y = 40; // 640
														resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_message_from_user_image_path/$get_message_from_user_image_file", "$root/$get_message_from_user_image_path/$get_message_from_user_image_thumb_40");
												
													}

													echo"
													<a href=\"dm.php?t_user_id=$get_message_from_user_id&amp;l=$l\"><img src=\"$root/$get_message_from_user_image_path/$get_message_from_user_image_thumb_40\" alt=\"$get_message_from_user_image_thumb_40\" class=\"chat_messages_from_user_image\" /></a>
													";
												}
												else{
													echo"
													<a href=\"dm.php?t_user_id=$get_message_from_user_id&amp;l=$l\"><img src=\"_gfx/avatar_blank_40.png\" alt=\"avatar_blank_40.png\" class=\"chat_messages_from_user_image\" /></a>
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
											<a href=\"dm.php?t_user_id=$get_message_from_user_id&amp;l=$l\" class=\"chat_messages_from_user_alias\" title=\"$get_message_from_user_alias\">$get_message_from_user_nickname</a>
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
											echo"<br />\n";

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
										</table>";
									} // message type chat
									// Update last message ID
									$variable_last_message_id = "$get_message_id";
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
										var data = 'l=$l&starred_channel_id=$get_current_starred_channel_id&last_message_id=' + variable_last_message_id;
            									\$.ajax({
                									type: \"POST\",
               										url: \"open_starred_channel_get_messages.php\",
                									data: data,
											beforeSend: function(html) { // this happens before actual call
											},
               										success: function(html){
                    										\$(\"#messages\").append(html);
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
					  </td>
					  <td style=\"vertical-align: top;\">
						<!-- Users -->
							<span id=\"variable_last_time\">$time</span>
							<div id=\"users_in_channel\">
								<ul>
								";
								// Get users
								$query = "SELECT online_id, online_channel_id, online_time, online_is_online, online_user_id, online_user_nickname, online_user_name, online_user_alias, online_user_image_path, online_user_image_file, online_user_image_thumb_40, online_user_image_thumb_50, online_ip, online_hostname, online_user_agent FROM $t_chat_channels_users_online WHERE online_channel_id=$get_current_channel_id AND online_is_online=1 ORDER BY online_user_name";
								$result = mysqli_query($link, $query);
								while($row = mysqli_fetch_row($result)) {
									list($get_online_id, $get_online_channel_id, $get_online_time, $get_online_is_online, $get_online_user_id, $get_online_user_nickname, $get_online_user_name, $get_online_user_alias, $get_online_user_image_path, $get_online_user_image_file, $get_online_user_image_thumb_40, $get_online_user_image_thumb_50, $get_online_ip, $get_online_hostname, $get_online_user_agent) = $row;

									echo"									";
									echo"<li><a href=\"dm.php?t_user_id=$get_online_user_id&amp;l=$l\" class=\"users_in_channel_user_alias\" title=\"$get_online_user_alias\"><span style=\"color: #42b72a;height: 7px; width: 7px; background-color: #42b72a; border-radius: 50%; display: inline-block;float: left;margin: 6px 4px 0px 0px\"></span>$get_online_user_nickname</a></li>";
									

									echo"";
								} // users online

								$query = "SELECT online_id, online_channel_id, online_time, online_is_online, online_user_id, online_user_nickname, online_user_name, online_user_alias, online_user_image_path, online_user_image_file, online_user_image_thumb_40, online_user_image_thumb_50, online_ip, online_hostname, online_user_agent FROM $t_chat_channels_users_online WHERE online_channel_id=$get_current_channel_id AND online_is_online=0 ORDER BY online_user_name";
								$result = mysqli_query($link, $query);
								while($row = mysqli_fetch_row($result)) {
									list($get_online_id, $get_online_channel_id, $get_online_time, $get_online_is_online, $get_online_user_id, $get_online_user_nickname, $get_online_user_name, $get_online_user_alias, $get_online_user_image_path, $get_online_user_image_file, $get_online_user_image_thumb_40, $get_online_user_image_thumb_50, $get_online_ip, $get_online_hostname, $get_online_user_agent) = $row;

									echo"									";
									echo"<li><a href=\"dm.php?t_user_id=$get_online_user_id&amp;l=$l\" class=\"users_in_channel_user_alias_offline\" title=\"$get_online_user_alias\"><span style=\"color: #d4d4d4;height: 7px; width: 7px; background-color: #d4d4d4; border-radius: 50%; display: inline-block;float: left;margin: 6px 4px 0px 0px\"></span>$get_online_user_nickname</a></li>";
									

									echo"";
								} // users offline
								echo"
								</ul>
							</div>


							<!-- Get users script -->
								<script language=\"javascript\" type=\"text/javascript\">
								\$(document).ready(function () {
									function get_users(){
										var variable_last_time = \$('#variable_last_time').html(); 
										var data = 'l=$l&starred_channel_id=$get_current_starred_channel_id&last_time=' + variable_last_message_id;
            									\$.ajax({
                									type: \"POST\",
               										url: \"open_starred_channel_get_users_online.php\",
                									data: data,
											beforeSend: function(html) { // this happens before actual call
											},
               										success: function(html){
                    										\$(\"#users_in_channel\").html(html);
              										}
       									
										});
									}
									setInterval(get_users,7000);
         				   			});
								</script>
							<!-- //Get users script -->
						<!-- //Users -->
					  </td>
					 </tr>
					</table>
				<!-- //Messages and users online -->

				<!-- New message form -->
				
					<!-- Focus -->
						<script>
						\$(document).ready(function(){
							\$('[name=\"inp_text\"]').focus();
						});
						</script>
					<!-- //Focus -->

					
					

					<p>
					<input type=\"text\" name=\"inp_text\" id=\"inp_text\" value=\"\" size=\"25\" style=\"width: 75%;\" tabindex=\""; $tabindex = $tabindex+1; echo"$tabindex\" />
					<a href=\"#\" id=\"emojies_selector_toggle\" class=\"btn_default\" tabindex=\""; $tabindex = $tabindex+1; echo"$tabindex\" />:)</a>
					<a href=\"#\" id=\"attachment_selector_toggle\" class=\"btn_default\" tabindex=\""; $tabindex = $tabindex+1; echo"$tabindex\" />&#128206;</a>

					<a href=\"#\" id=\"inp_message_send\" class=\"btn_default\" tabindex=\""; $tabindex = $tabindex+1; echo"$tabindex\" />$l_send</a>
					</p>

						<!-- emojies, attachment _selector_toggle -->
							<script>
								$(document).ready(function(){
									$(\"#emojies_selector_toggle\").click(function () {
										\$(\"#emojies_selector\").toggle();	
									});
									$(\"#attachment_selector_toggle\").click(function () {
										\$(\"#attachment_selector\").toggle();	
										\$(\"#attachment_selector\").css('visibility', 'visible');
									});
								});
							</script>
						<!-- //Emojies selector toggle -->

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
									<form method=\"POST\" action=\"open_starred_channel_upload_file_as_attachment.php?starred_channel_id=$get_current_starred_channel_id&amp;l=$l&amp;process=1\" id=\"starred_channel_upload_file_as_attachment_form_data\" enctype=\"multipart/form-data\">
					
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

 								// forming the queryString
								var data            = 'l=$l&starred_channel_id=$get_current_starred_channel_id&inp_text='+ inp_text;
         
        							// if searchString is not empty
        							if(inp_text) {
           								// ajax call
            								\$.ajax({
                								type: \"POST\",
               									url: \"open_starred_channel_send_message.php\",
                								data: data,
										beforeSend: function(html) { // this happens before actual call
											
										},
               									success: function(html){
                    									\$(\"#messages\").append(html);
                    									\$(\"#inp_text\").val('');
											\$('#messages').scrollTop(\$('#messages')[0].scrollHeight);
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

					
				// Find message
				$query = "SELECT message_id, message_channel_id, message_text, message_datetime, message_date_saying, message_time_saying, message_time, message_year, message_from_user_id, message_from_user_name, message_from_user_alias, message_from_user_image_path, message_from_user_image_file, message_from_user_image_thumb_40, message_from_user_image_thumb_50, message_from_ip, message_from_hostname, message_from_user_agent FROM $t_chat_channels_messages WHERE message_id=$message_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_message_id, $get_current_message_channel_id, $get_current_message_text, $get_current_message_datetime, $get_current_message_date_saying, $get_current_message_time_saying, $get_current_message_time, $get_current_message_year, $get_current_message_from_user_id, $get_current_message_from_user_name, $get_current_message_from_user_alias, $get_current_message_from_user_image_path, $get_current_message_from_user_image_file, $get_current_message_from_user_image_thumb_40, $get_current_message_from_user_image_thumb_50, $get_current_message_from_ip, $get_current_message_from_hostname, $get_current_message_from_user_agent) = $row;
				if($get_current_message_id != ""){
					if($get_current_message_from_user_id == "$my_user_id"){
						$result = mysqli_query($link, "DELETE FROM $t_chat_channels_messages WHERE message_id=$get_current_message_id");

						$url = "open_starred_channel.php?starred_channel_id=$get_current_starred_channel_id&amp;l=$l";
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