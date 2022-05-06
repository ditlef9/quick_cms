<?php 
/**
*
* File: chat/settings.php
* Version 1.0.0
* Date 21:32 31.08.2019
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

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/chat/ts_index.php");



/*- Tables ---------------------------------------------------------------------------- */
include("_tables_chat.php");

/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);




/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_settings - $l_chat";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");

/*- Content ---------------------------------------------------------------------------------- */
// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	// Get my user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;
	
	// Get my nickname
	$query = "SELECT nickname_id, nickname_user_id, nickname_value, nickname_datetime, nickname_datetime_saying FROM $t_chat_nicknames WHERE nickname_user_id=$get_my_user_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_nickname_id, $get_my_nickname_user_id, $get_my_nickname_value, $get_my_nickname_datetime, $get_my_nickname_datetime_saying) = $row;

	// Get my settings
	$query = "SELECT user_setting_id, user_setting_user_id, user_setting_show_channel_info FROM $t_chat_user_settings WHERE user_setting_user_id=$get_my_user_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_user_setting_id, $get_current_user_setting_user_id, $get_current_user_setting_show_channel_info) = $row;
	if($get_current_user_setting_id == ""){
		mysqli_query($link, "INSERT INTO $t_chat_user_settings 
		(user_setting_id, user_setting_user_id, user_setting_show_channel_info) 
		VALUES 
		(NULL, $get_my_user_id, 0)")
		or die(mysqli_error($link));
	}

	if($action == ""){
		if($process == "1"){
			// Dates
			$datetime = date("Y-m-d H:i:s");
			$datetime_saying = date("j. M Y H:i");
			$time_saying = date("H:i");
			$date_saying = date("j M Y");
			$time = time();
			$year = date("Y");

			$inp_nickname_value = $_POST['inp_nickname_value'];
			$inp_nickname_value = output_html($inp_nickname_value);
			$inp_nickname_value_mysql = quote_smart($link, $inp_nickname_value);

			// CHeck if taken
			$query = "SELECT nickname_id, nickname_user_id FROM $t_chat_nicknames WHERE nickname_value=$inp_nickname_value_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_check_nickname_id, $get_check_nickname_user_id) = $row;
			if($get_check_nickname_id == ""){
			
				// Update my nickname
				$result = mysqli_query($link, "UPDATE $t_chat_nicknames SET nickname_value=$inp_nickname_value_mysql, nickname_datetime='$datetime', nickname_datetime_saying='$datetime_saying' WHERE nickname_id=$get_my_nickname_id") or die(mysqli_error($link));


				// Insert history
				$inp_change_from_value_mysql = quote_smart($link, $get_my_nickname_value);
				mysqli_query($link, "INSERT INTO $t_chat_nicknames_changes 
				(change_id, change_user_id, change_from_value, change_to_value, change_datetime, change_datetime_saying) 
				VALUES 
				(NULL, $get_my_user_id, $inp_change_from_value_mysql, $inp_nickname_value_mysql, '$datetime', '$datetime_saying')")
				or die(mysqli_error($link));

				// Insert into channel messages as info
				
				// My photo
				$query = "SELECT photo_id, photo_destination, photo_thumb_40, photo_thumb_50 FROM $t_users_profile_photo WHERE photo_user_id=$my_user_id_mysql AND photo_profile_image='1'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_40, $get_my_photo_thumb_50) = $row;
				
				$inp_text = "<a href=\"$root/users/view_profile.php?user_id=$get_my_user_id&amp;l=$l\">$get_my_nickname_value</a> $l_changes_nickname_to_lowercase $inp_nickname_value";
				$inp_text_mysql = quote_smart($link, $inp_text);

				$inp_my_user_nickname_mysql = quote_smart($link, $inp_nickname_value);

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




				// Insert into channels
				$query = "SELECT starred_channel_id, channel_id, channel_name, new_messages, user_id FROM $t_chat_users_starred_channels WHERE user_id=$my_user_id_mysql";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_starred_channel_id, $get_channel_id, $get_channel_name, $get_new_messages, $get_user_id) = $row;

					mysqli_query($link, "INSERT INTO $t_chat_channels_messages
					(message_id, message_channel_id, message_type, message_text, message_datetime, message_date_saying, message_time_saying, message_time, message_year, message_from_user_id, message_from_user_nickname, message_from_user_name, message_from_user_alias, message_from_user_image_path, message_from_user_image_file, message_from_user_image_thumb_40, message_from_user_image_thumb_50, message_from_ip, message_from_hostname, message_from_user_agent) 
					VALUES 
					(NULL, $get_channel_id, 'info', $inp_text_mysql, '$datetime', '$date_saying', '$time_saying', '$time', $year, $get_my_user_id, $inp_my_user_nickname_mysql, $inp_my_user_name_mysql, $inp_my_user_alias_mysql, $inp_my_user_image_path_mysql, $inp_my_user_image_file_mysql, $inp_my_user_image_thumb_a_mysql, $inp_my_user_image_thumb_b_mysql, $inp_my_ip_mysql, $inp_my_hostname_mysql, $inp_my_user_agent_mysql)")
					or die(mysqli_error($link));	


					// Update users online in channels
					$result_update = mysqli_query($link, "UPDATE $t_chat_channels_users_online SET online_user_nickname=$inp_nickname_value_mysql WHERE online_user_id=$get_my_user_id") or die(mysqli_error($link));

				}


				// Insert into dms
				$query = "SELECT conversation_id, conversation_key, conversation_f_unread_messages, conversation_t_user_id, conversation_t_user_nickname, conversation_t_user_alias, conversation_t_last_online_time FROM $t_chat_dm_conversations WHERE conversation_f_user_id=$my_user_id_mysql AND conversation_f_has_blocked=0";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_conversation_id, $get_conversation_key, $get_conversation_f_unread_messages, $get_conversation_t_user_id, $get_conversation_t_user_nickname, $get_conversation_t_user_alias, $get_conversation_t_last_online_time) = $row;

					$inp_key_mysql = quote_smart($link, $get_conversation_key);

					mysqli_query($link, "INSERT INTO $t_chat_dm_messages 
					(message_id, message_conversation_key, message_type, message_text, message_datetime, message_date_saying, message_time_saying, message_time, message_year, message_seen, message_from_user_id, message_from_ip, message_from_hostname, message_from_user_agent) 
					VALUES 
					(NULL, $inp_key_mysql, 'info', $inp_text_mysql, '$datetime', '$date_saying', '$time_saying', '$time', $year, '0', $get_my_user_id, $inp_my_ip_mysql, $inp_my_hostname_mysql, $inp_my_user_agent_mysql)")
					or die(mysqli_error($link));

				}
				// Update dm conversations 
				$result_update = mysqli_query($link, "UPDATE $t_chat_dm_conversations SET conversation_t_user_nickname=$inp_nickname_value_mysql WHERE conversation_t_user_id=$get_my_user_id") or die(mysqli_error($link));
				$result_update = mysqli_query($link, "UPDATE $t_chat_dm_conversations SET conversation_f_user_nickname=$inp_nickname_value_mysql WHERE conversation_f_user_id=$get_my_user_id") or die(mysqli_error($link));

			}
			else{
				// Is it my nickname?
				if($get_check_nickname_user_id == "$get_my_user_id"){
					// $url = "settings.php?l=$l&ft=info&fm=no_changes";
					// header("Location: $url");
					// exit;
				}
			}
			
			// Settings
			$inp_show_channel_info = $_POST['inp_show_channel_info'];
			$inp_show_channel_info = output_html($inp_show_channel_info);
			$inp_show_channel_info_mysql = quote_smart($link, $inp_show_channel_info);
			$result_update = mysqli_query($link, "UPDATE $t_chat_user_settings SET user_setting_show_channel_info=$inp_show_channel_info_mysql WHERE user_setting_user_id=$get_my_user_id") or die(mysqli_error($link));



			$url = "settings.php?l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;
		}
		echo"
		<h1>$l_settings</h1>
		
		<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->


		<!-- Form -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_nickname_value\"]').focus();
			});
			</script>
	
			<form method=\"post\" action=\"settings.php?l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	
			<p><b>$l_my_nickname:</b><br />
			<input type=\"text\" name=\"inp_nickname_value\" value=\"$get_my_nickname_value\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>
	
			<p><b>$l_show_channel_info:</b> <span class=\"smal\">($l_example_join_and_timeouts)</span><br />
			<input type=\"radio\" name=\"inp_show_channel_info\" value=\"1\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\""; if($get_current_user_setting_show_channel_info == "1"){ echo" checked=\"checked\""; } echo" />
			$l_yes
			&nbsp;
			<input type=\"radio\" name=\"inp_show_channel_info\" value=\"0\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\""; if($get_current_user_setting_show_channel_info == "0"){ echo" checked=\"checked\""; } echo" />
			$l_no
			</p>

			<p><input type=\"submit\" value=\"$l_save_changes\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>			
			</form>
		<!-- //Form -->
		";
	} // action == ""
}
else{
	echo"
	<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" /> Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=$root/discuss/new_topic.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>