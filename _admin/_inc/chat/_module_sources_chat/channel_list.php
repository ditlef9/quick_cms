<?php 
/**
*
* File: chat/channel_list.php
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
$website_title = "$l_channel_list - $l_chat";
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
	
	if($action == ""){
	
		echo"
		<h1>$l_channel_list</h1>
		
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

		<p><a href=\"$root/chat/new_channel.php?l=$l\" class=\"btn_default\">$l_new_channel</a></p>
	

		<!-- All channels -->
		
			<div class=\"vertical\" style=\"width: 100%;\">
				<ul style=\"width: 100%;\">
				";
				$time = time();
				$query = "SELECT channel_id, channel_name, channel_password, channel_last_message_time, channel_last_message_saying, channel_users_online FROM $t_chat_channels_index ORDER BY channel_name ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_channel_id, $get_channel_name, $get_channel_password, $get_channel_last_message_time, $get_channel_last_message_saying, $get_channel_users_online) = $row;

					$time_since_last_message = $time-$get_channel_last_message_time;
					$days_since_last_message = round($time_since_last_message  / (60 * 60 * 24));
					if($days_since_last_message > 365){
						echo"<li><span>Delete $get_channel_name because of inactivity</span></li>";
						$result_del = mysqli_query($link, "DELETE FROM $t_chat_channels_index WHERE channel_id=$get_channel_id");
						$result_del = mysqli_query($link, "DELETE FROM $t_chat_channels_messages WHERE message_channel_id=$get_channel_id");

					}
					else{
						if($get_channel_password == ""){
							echo"				";
							echo"<li style=\"width: 100%;\"><a href=\"channel_list.php?action=join_without_password&amp;channel_id=$get_channel_id&amp;l=$l&amp;process=1\">$get_channel_name";
						}
						else{
							echo"				";
							echo"<li style=\"width: 100%;\"><a href=\"channel_list.php?action=join_with_password&amp;channel_id=$get_channel_id&amp;l=$l\">$get_channel_name";
						}
						echo"<br />
						<span class=\"small\">$get_channel_users_online ";
						if($get_channel_users_online == "1"){
							echo"$l_user_online_lowercase";
						}
						else{
							echo"$l_users_online_lowercase";
						}
						echo" &middot; $l_last_message $get_channel_last_message_saying</span></a></li>\n";
					}
				}
				echo"
				</ul>
			</div>
			<div class=\"clear\"></div>
		<!-- All channels -->
		";
	} // action == ""
	elseif($action == "join_without_password"){
		if(isset($_GET['channel_id'])){
			$channel_id = $_GET['channel_id'];
			$channel_id = output_html($channel_id);
		}
		else{
			$channel_id = "";
		}
		$channel_id_mysql = quote_smart($link, $channel_id);
		
		// Find channel
		$query = "SELECT channel_id, channel_name, channel_password, channel_last_message_time FROM $t_chat_channels_index WHERE channel_id=$channel_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_channel_id, $get_current_channel_name, $get_current_channel_password, $get_current_channel_last_message_time) = $row;
	
		if($get_current_channel_id == ""){
			echo"Not found $query ";
		}
		else{
			// Already starred?
			$query = "SELECT starred_channel_id FROM $t_chat_users_starred_channels WHERE channel_id=$get_current_channel_id AND user_id=$get_my_user_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_starred_channel_id) = $row;

			if($get_starred_channel_id == ""){
				// Starred
				$inp_name_mysql = quote_smart($link, $get_current_channel_name);
				mysqli_query($link, "INSERT INTO $t_chat_users_starred_channels 
				(starred_channel_id, channel_id, channel_name, new_messages, user_id) 
				VALUES 
				(NULL, $get_current_channel_id, $inp_name_mysql, 0, $get_my_user_id)")
				or die(mysqli_error($link));

				// Get Starred ID
				$query = "SELECT starred_channel_id FROM $t_chat_users_starred_channels WHERE channel_id=$get_current_channel_id AND user_id=$get_my_user_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_starred_channel_id) = $row;
			}


			// Header
			$url = "open_starred_channel.php?starred_channel_id=$get_starred_channel_id&l=$l";
			header("Location: $url");
			exit;
		}
	} // join_without_password
	elseif($action == "join_with_password"){
		if(isset($_GET['channel_id'])){
			$channel_id = $_GET['channel_id'];
			$channel_id = output_html($channel_id);
		}
		else{
			$channel_id = "";
		}
		$channel_id_mysql = quote_smart($link, $channel_id);
		
		// Find channel
		$query = "SELECT channel_id, channel_name, channel_password, channel_last_message_time FROM $t_chat_channels_index WHERE channel_id=$channel_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_channel_id, $get_current_channel_name, $get_current_channel_password, $get_current_channel_last_message_time) = $row;
	
		if($get_current_channel_id == ""){
			echo"Not found $query ";
		}
		else{
			// Already starred?
			$query = "SELECT starred_channel_id FROM $t_chat_users_starred_channels WHERE channel_id=$get_current_channel_id AND user_id=$get_my_user_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_starred_channel_id) = $row;

			if($get_starred_channel_id != ""){
				echo"
				<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" /> Loading...</h1>
				<meta http-equiv=\"refresh\" content=\"1;url=open_starred_channel.php?starred_channel_id=$get_starred_channel_id\">
				";
				
			}
			else{
				if($process == "1"){

					// Password
					$inp_password = $_POST['inp_password'];
					if($inp_password == ""){
						$inp_password_encrypted = "";
					}
					else{
						$inp_password_encrypted = sha1($inp_password);
					}
					if($get_current_channel_password != "$inp_password_encrypted"){
						$url = "channel_list.php?action=join_with_password&channel_id=$get_current_channel_id&l=$l&ft=error&fm=wrong_password";
						header("Location: $url");
						exit;

					}
					else{
						// Starred
						$inp_name_mysql = quote_smart($link, $get_current_channel_name);
						mysqli_query($link, "INSERT INTO $t_chat_users_starred_channels 
						(starred_channel_id, channel_id, channel_name, new_messages, user_id) 
						VALUES 
						(NULL, $get_current_channel_id, $inp_name_mysql, 0, $get_my_user_id)")
						or die(mysqli_error($link));

						// Get Starred ID
						$query = "SELECT starred_channel_id FROM $t_chat_users_starred_channels WHERE channel_id=$get_current_channel_id AND user_id=$get_my_user_id";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_starred_channel_id) = $row;
			

						// Header
						$url = "open_starred_channel.php?starred_channel_id=$get_starred_channel_id&l=$l";
						header("Location: $url");
						exit;
					}
				}

				echo"
				<h1>$get_current_channel_name</h1>
				

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

				<p>$l_channel_is_password_protected
				$l_please_enter_password_to_join </p>


				<!-- Form -->
					<script>
					\$(document).ready(function(){
						\$('[name=\"inp_password\"]').focus();
					});
					</script>
	
					<form method=\"post\" action=\"channel_list.php?action=join_with_password&amp;channel_id=$get_current_channel_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
					<p><b>$l_password:</b>
					<input type=\"password\" name=\"inp_password\" value=\"";
					if(isset($_GET['inp_password'])){
						$inp_password = $_GET['inp_password'];
						$inp_password = output_html($inp_password);
						echo"$inp_password";
					}
					echo"\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					<input type=\"submit\" value=\"$l_join_channel\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>
					</form>
				<!-- //Form -->
				";
			} // not member
		} // channel found
	} // action == join_with_password
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