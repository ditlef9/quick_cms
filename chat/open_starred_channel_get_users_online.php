<?php 
/**
*
* File: chat/open_starred_channel_get_users_online.php
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

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/chat/ts_open_starred_channel.php");

/*- Tables ---------------------------------------------------------------------------- */
include("_tables_chat.php");

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

if(isset($_POST['last_time'])){
	$last_time = $_POST['last_time'];
	$last_time = output_html($last_time);
}
else{
	$last_time = "";
}
$last_time_mysql = quote_smart($link, $last_time);


$time = time();
$year = date("Y");
$date_saying = date("j M Y");
$time_saying = date("H:i");


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
		echo"<h1>Starred Channel not found</h1>";
	}
	else{
		// Find channel
		$query = "SELECT channel_id, channel_name, channel_password, channel_last_message_time, channel_users_online FROM $t_chat_channels_index WHERE channel_id=$get_current_channel_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_channel_id, $get_current_channel_name, $get_current_channel_password, $get_current_channel_last_message_time, $get_current_channel_users_online) = $row;

		if($get_current_channel_id == ""){
			echo"<h1>Channel not found</h1>";
		}
		else{

			// Make myself online
			$query = "SELECT online_id, online_time, online_is_online, online_user_id, online_user_name, online_user_alias FROM $t_chat_channels_users_online WHERE online_channel_id=$get_current_channel_id AND online_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_online_id, $get_online_time, $get_online_is_online, $get_online_user_id, $get_online_user_name, $get_online_user_alias) = $row;
			if($get_online_id == ""){
				// Insert myself

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
				mysqli_query($link, "INSERT INTO $t_chat_channels_users_online 
				(online_id, online_channel_id, online_time, online_is_online, online_user_id, online_user_nickname, online_user_name, online_user_alias, online_user_image_path, online_user_image_file, online_user_image_thumb_40, online_user_image_thumb_50, online_ip, online_hostname, online_user_agent) 
				VALUES 
				(NULL, $get_current_channel_id, '$time', 1, $get_my_user_id, $inp_my_user_nickname_mysql, $inp_my_user_name_mysql, $inp_my_user_alias_mysql, $inp_my_user_image_path_mysql, $inp_my_user_image_file_mysql, $inp_my_user_image_thumb_a_mysql, $inp_my_user_image_thumb_b_mysql, $inp_my_ip_mysql, $inp_my_hostname_mysql, $inp_my_user_agent_mysql)")
				or die(mysqli_error($link));

				$inp_text = "<a href=\"../users/view_profile.php?user_id=$get_my_user_id&amp;l=$l\">$get_my_nickname_value</a> $l_joined_lowercase #$get_current_channel_name";
				$inp_text = ucfirst($inp_text);
				$inp_text_mysql  = quote_smart($link, $inp_text);

				mysqli_query($link, "INSERT INTO $t_chat_channels_messages
				(message_id, message_channel_id, message_type, message_text, message_datetime, message_date_saying, message_time_saying, message_time, message_year, message_from_user_id, message_from_user_name, message_from_user_alias, message_from_user_image_path, message_from_user_image_file, message_from_user_image_thumb_40, message_from_user_image_thumb_50, message_from_ip, message_from_hostname, message_from_user_agent) 
				VALUES 
				(NULL, $get_current_channel_id, 'info', $inp_text_mysql, '$datetime', '$date_saying', '$time_saying', '$time', $year, $get_my_user_id, $inp_my_user_name_mysql, $inp_my_user_alias_mysql, $inp_my_user_image_path_mysql, $inp_my_user_image_file_mysql, $inp_my_user_image_thumb_a_mysql, $inp_my_user_image_thumb_b_mysql, $inp_my_ip_mysql, $inp_my_hostname_mysql, $inp_my_user_agent_mysql)")
				or die(mysqli_error($link));
			}
			else{
				if($get_online_is_online == "0"){
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

					// Im back online 
					$inp_text = "$get_my_nickname_value $l_is_online_lowercase.";
					$inp_text = ucfirst($inp_text);
					$inp_text_mysql  = quote_smart($link, $inp_text);

					mysqli_query($link, "INSERT INTO $t_chat_channels_messages
					(message_id, message_channel_id, message_type, message_text, message_datetime, 
					message_date_saying, message_time_saying, message_time, message_year, message_from_user_id, 
					message_from_user_nickname, message_from_user_name, message_from_user_alias, message_from_user_image_path, message_from_user_image_file, message_from_user_image_thumb_40, 
					message_from_user_image_thumb_50, message_from_ip, message_from_hostname, message_from_user_agent) 
					VALUES 
					(NULL, $get_current_channel_id, 'info', $inp_text_mysql, '$datetime', 
					'$date_saying', '$time_saying', '$time', $year, $get_my_user_id, 
					$inp_my_user_nickname_mysql, $inp_my_user_name_mysql, $inp_my_user_alias_mysql, $inp_my_user_image_path_mysql, $inp_my_user_image_file_mysql, $inp_my_user_image_thumb_a_mysql, $inp_my_user_image_thumb_b_mysql, $inp_my_ip_mysql, $inp_my_hostname_mysql, $inp_my_user_agent_mysql)")
					or die(mysqli_error($link));


					$result_update_online = mysqli_query($link, "UPDATE $t_chat_channels_users_online SET online_time='$time', online_is_online=1 WHERE online_id=$get_online_id") or die(mysqli_error($link));
				}
				
			}
				
			echo"
			<ul>
			";
			$count_users_online = 0;
			// Get users online
			$query = "SELECT online_id, online_channel_id, online_time, online_is_online, online_user_id, online_user_nickname, online_user_name, online_user_alias, online_user_image_path, online_user_image_file, online_user_image_thumb_40, online_user_image_thumb_50, online_ip, online_hostname, online_user_agent FROM $t_chat_channels_users_online WHERE online_channel_id=$get_current_channel_id AND online_is_online=1 ORDER BY online_user_name";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_online_id, $get_online_channel_id, $get_online_time, $get_online_is_online, $get_online_user_id, $get_online_user_nickname, $get_online_user_name, $get_online_user_alias, $get_online_user_image_path, $get_online_user_image_file, $get_online_user_image_thumb_40, $get_online_user_image_thumb_50, $get_online_ip, $get_online_hostname, $get_online_user_agent) = $row;
			
				echo"				";
				echo"<li><a href=\"dm.php?t_user_id=$get_online_user_id&amp;l=$l\" class=\"users_in_channel_user_alias\" title=\"$get_online_user_alias\"><span style=\"color: #42b72a;height: 7px; width: 7px; background-color: #42b72a; border-radius: 50%; display: inline-block;float: left;margin: 6px 4px 0px 0px\"></span>$get_online_user_nickname</a></li>\n";
				$count_users_online++;




				// Is this user online?
				$query_u = "SELECT user_id, user_email, user_name, user_alias, user_last_online_time FROM $t_users WHERE user_id=$get_online_user_id";
				$result_u = mysqli_query($link, $query_u);
				$row_u = mysqli_fetch_row($result_u);
				list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_last_online_time) = $row_u;

				$seconds_since_online = $time-$get_user_last_online_time;
				if($seconds_since_online > 180){
					$inp_text = "$get_online_user_nickname $l_timed_out_after_lowercase $seconds_since_online $l_seconds_lowercase.";
					$inp_text = ucfirst($inp_text);
					$inp_text_mysql  = quote_smart($link, $inp_text);

					// Photo
					$query_p = "SELECT photo_id, photo_destination, photo_thumb_40, photo_thumb_50 FROM $t_users_profile_photo WHERE photo_user_id=$get_online_user_id AND photo_profile_image='1'";
					$result_p = mysqli_query($link, $query_p);
					$row_p = mysqli_fetch_row($result_p);
					list($get_photo_id, $get_photo_destination, $get_photo_thumb_40, $get_photo_thumb_50) = $row_p;


					$inp_user_nickname_mysql = quote_smart($link, $get_online_user_nickname);
					$inp_user_name_mysql = quote_smart($link, $get_user_name);
					$inp_user_alias_mysql = quote_smart($link, $get_user_alias);

					$inp_user_image_path = "_uploads/users/images/$get_user_id";
					$inp_user_image_path_mysql = quote_smart($link, $inp_user_image_path);

					$inp_user_image_file_mysql = quote_smart($link, $get_photo_destination);
	
					$inp_user_image_thumb_a_mysql = quote_smart($link, $get_photo_thumb_40);
					$inp_user_image_thumb_b_mysql = quote_smart($link, $get_photo_thumb_50);

					// My IP
					$inp_ip = $_SERVER['REMOTE_ADDR'];
					$inp_ip = output_html($inp_ip);
					$inp_ip_mysql = quote_smart($link, $inp_ip);
	
					$inp_hostname = "";
					if($configSiteUseGethostbyaddrSav == "1"){
						$inp_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
					}
					$inp_hostname = output_html($inp_hostname);
					$inp_hostname_mysql = quote_smart($link, $inp_hostname);

					$inp_user_agent = $_SERVER['HTTP_USER_AGENT'];
					$inp_user_agent = output_html($inp_user_agent);
					$inp_user_agent_mysql = quote_smart($link, $inp_user_agent);

					mysqli_query($link, "INSERT INTO $t_chat_channels_messages
					(message_id, message_channel_id, message_type, message_text, message_datetime, message_date_saying, message_time_saying, message_time, message_year, message_from_user_id, message_from_user_nickname, message_from_user_name, message_from_user_alias, message_from_user_image_path, message_from_user_image_file, message_from_user_image_thumb_40, message_from_user_image_thumb_50, message_from_ip, message_from_hostname, message_from_user_agent) 
					VALUES 
					(NULL, $get_current_channel_id, 'info', $inp_text_mysql, '$datetime', '$date_saying', '$time_saying', '$time', $year, $get_user_id, $inp_user_nickname_mysql, $inp_user_name_mysql, $inp_user_alias_mysql, $inp_user_image_path_mysql, $inp_user_image_file_mysql, $inp_user_image_thumb_a_mysql, $inp_user_image_thumb_b_mysql, $inp_ip_mysql, $inp_hostname_mysql, $inp_user_agent_mysql)")
					or die(mysqli_error($link));
					$result_update_online = mysqli_query($link, "UPDATE $t_chat_channels_users_online SET online_is_online=0 WHERE online_id=$get_online_id");
				} // user has been inactive over 180 sec
			
			}

			// Get users offline
			$query = "SELECT online_id, online_channel_id, online_time, online_is_online, online_user_id, online_user_nickname, online_user_name, online_user_alias, online_user_image_path, online_user_image_file, online_user_image_thumb_40, online_user_image_thumb_50, online_ip, online_hostname, online_user_agent FROM $t_chat_channels_users_online WHERE online_channel_id=$get_current_channel_id AND online_is_online=0 ORDER BY online_user_name";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_online_id, $get_online_channel_id, $get_online_time, $get_online_is_online, $get_online_user_id, $get_online_user_nickname, $get_online_user_name, $get_online_user_alias, $get_online_user_image_path, $get_online_user_image_file, $get_online_user_image_thumb_40, $get_online_user_image_thumb_50, $get_online_ip, $get_online_hostname, $get_online_user_agent) = $row;
			
				echo"				";
				echo"<li><a href=\"dm.php?t_user_id=$get_online_user_id&amp;l=$l\" class=\"users_in_channel_user_alias_offline\" title=\"$get_online_user_alias\"><span style=\"color: #a0a0a0;height: 7px; width: 7px; background-color: #a0a0a0; border-radius: 50%; display: inline-block;float: left;margin: 6px 4px 0px 0px\"></span>$get_online_user_nickname</a></li>\n";
			}

			
				

			if($get_current_channel_users_online != "$count_users_online"){
				$result = mysqli_query($link, "UPDATE $t_chat_channels_index SET channel_users_online=$count_users_online WHERE channel_id=$get_current_channel_id");

			}
			echo"
			</ul>
			";

			// Update time
			echo"
			<script language=\"javascript\" type=\"text/javascript\">
			\$(document).ready(function () {
				\$(\"#variable_last_time\").html($time);
        		});
			</script>
			";

		} // channel found

	} // starred channel found

} // logged in


?>