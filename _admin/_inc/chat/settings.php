<?php
/**
*
* File: _admin/_inc/chat/settings.php
* Version 
* Date 09:20 21.01.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables ---------------------------------------------------------------------------- */
$t_chat_liquidbase				= $mysqlPrefixSav . "chat_liquidbase";

$t_chat_channels_index		= $mysqlPrefixSav . "chat_channels_index";
$t_chat_channels_messages	= $mysqlPrefixSav . "chat_channels_messages";
$t_chat_channels_users_online	= $mysqlPrefixSav . "chat_channels_users_online";
$t_chat_users_starred_channels	= $mysqlPrefixSav . "chat_users_starred_channels";

$t_chat_dm_conversations = $mysqlPrefixSav . "chat_dm_conversations";
$t_chat_dm_messages	 = $mysqlPrefixSav . "chat_dm_messages";

$t_chat_total_unread = $mysqlPrefixSav . "chat_total_unread";





/*- Config ------------------------------------------------------------------------------- */
if(!(file_exists("_data/chat.php"))){
	$update_file="<?php
\$chatTitleSav	= \"Chat\";

// Encryption
\$chatEncryptionMethodChannelsSav	= \"openssl_encrypt(AES-128-CBC)\";
\$chatEncryptionMethodDmsSav		= \"openssl_encrypt(AES-128-CBC)\";

\$chatWebcameraChatActiveDmsSav		= \"0\";

\$chatCompensateForEmojisStringErrorSav = \"0\";
\$chatUsersCanCreateChannelsSav  	= \"0\";
?>";

		$fh = fopen("_data/chat.php", "w+") or die("can not open file");
		fwrite($fh, $update_file);
		fclose($fh);
}
include("_data/chat.php");



/*- Check if setup has runned -------------------------------------------------------- */
$t_chat_liquidbase = $mysqlPrefixSav . "chat_liquidbase";
$query = "SELECT * FROM $t_chat_liquidbase LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{
	echo"
	<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Running setup</p></div>
	<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=$open&amp;page=tables&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
	";
}


/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;


if($action == "save"){
		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);

		$inp_encryption_method_channels = $_POST['inp_encryption_method_channels'];
		$inp_encryption_method_channels = output_html($inp_encryption_method_channels);

		$inp_encryption_method_dms = $_POST['inp_encryption_method_dms'];
		$inp_encryption_method_dms = output_html($inp_encryption_method_dms);

		$inp_webcamera_chat_active_dms = $_POST['inp_webcamera_chat_active_dms'];
		$inp_webcamera_chat_active_dms = output_html($inp_webcamera_chat_active_dms);

		$inp_compensate_for_emojis_string_error = $_POST['inp_compensate_for_emojis_string_error'];
		$inp_compensate_for_emojis_string_error = output_html($inp_compensate_for_emojis_string_error);

		$inp_users_can_create_channels = $_POST['inp_users_can_create_channels'];
		$inp_users_can_create_channels = output_html($inp_users_can_create_channels);

	$update_file="<?php
\$chatTitleSav	= \"$inp_title\";

// Encryption
\$chatEncryptionMethodChannelsSav 	= \"$inp_encryption_method_channels\";
\$chatEncryptionMethodDmsSav 		= \"$inp_encryption_method_dms\";

\$chatWebcameraChatActiveDmsSav		= \"$inp_webcamera_chat_active_dms\";

\$chatCompensateForEmojisStringErrorSav = \"$inp_compensate_for_emojis_string_error\";

\$chatUsersCanCreateChannelsSav		= \"$inp_users_can_create_channels\";
?>";

		$fh = fopen("_data/chat.php", "w+") or die("can not open file");
		fwrite($fh, $update_file);
		fclose($fh);

		// Update chats
		$result_update = mysqli_query($link, "UPDATE $t_chat_channels_index SET 
						channel_encryption_key='',
						channel_encryption_key_year=0, 
						channel_encryption_key_month=0") or die(mysqli_error($link));
		$result_delete = mysqli_query($link, "DELETE FROM $t_chat_channels_messages") or die(mysqli_error($link));


		$result_update = mysqli_query($link, "UPDATE $t_chat_dm_conversations SET 
						conversation_encryption_key='',
						conversation_encryption_key_year=0, 
						conversation_encryption_key_month=0") or die(mysqli_error($link));
		$result_delete = mysqli_query($link, "DELETE FROM $t_chat_dm_messages") or die(mysqli_error($link));

		echo"<h1><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Saving..</h1>
		<meta http-equiv=refresh content=\"2; url=index.php?open=$open&page=$page&ft=success&fm=changes_saved\">";
		// header("Location: ?open=$open&page=$page&ft=success&fm=changes_saved");
		// exit;
}
elseif($action == ""){

		echo"
		<h1>Chat</h1>
				

		<!-- Feedback -->
		";
		if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		else{
			$fm = ucfirst($fm);
			$fm = str_replace("_", " ", $fm);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->


		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=chat&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Chat</a>
			&gt;
			<a href=\"index.php?open=chat&amp;page=settings&amp;editor_language=$editor_language&amp;l=$l\">Settings</a>
			</p>
		<!-- //Where am I? -->

		
		<!-- Chat module menu buttons -->
			";

			// Navigation
			$query = "SELECT navigation_id FROM $t_pages_navigation WHERE navigation_url_path='chat/index.php'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_navigation_id) = $row;
			if($get_navigation_id == ""){
				echo"
				<p>
				<a href=\"index.php?open=pages&amp;page=navigation&amp;action=new_auto_insert&amp;module=chat&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_default\">Create navigation</a>
				</p>
				";
			}
			echo"
		<!-- //Chat module menu buttons -->

		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
			</script>
		<!-- //Focus -->

		<!-- Settings -->
		<form method=\"post\" action=\"?open=$open&page=$page&amp;action=save\" enctype=\"multipart/form-data\">
		

		<p>Talk title:<br />
		<input type=\"text\" name=\"inp_title\" value=\"$chatTitleSav\" value=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p>Encryption method channels:<br />
		<select name=\"inp_encryption_method_channels\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
			<option value=\"none\""; if($chatEncryptionMethodChannelsSav == "none"){ echo" selected=\"selected\""; } echo">None</option>
			<option value=\"openssl_encrypt(AES-128-CBC)\""; if($chatEncryptionMethodChannelsSav == "openssl_encrypt(AES-128-CBC)"){ echo" selected=\"selected\""; } echo">openssl_encrypt(AES-128-CBC)</option>
			<option value=\"caesar_cipher(random)\""; if($chatEncryptionMethodChannelsSav == "caesar_cipher(random)"){ echo" selected=\"selected\""; } echo">Caesar cipher(random)</option>
		</select>
		</p>

		<p>Encryption method direct messages:<br />
		<select name=\"inp_encryption_method_dms\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
			<option value=\"none\""; if($chatEncryptionMethodDmsSav == "none"){ echo" selected=\"selected\""; } echo">None</option>
			<option value=\"openssl_encrypt(AES-128-CBC)\""; if($chatEncryptionMethodDmsSav == "openssl_encrypt(AES-128-CBC)"){ echo" selected=\"selected\""; } echo">openssl_encrypt(AES-128-CBC)</option>
			<option value=\"caesar_cipher(random)\""; if($chatEncryptionMethodDmsSav == "caesar_cipher(random)"){ echo" selected=\"selected\""; } echo">Caesar cipher(random)</option>
		</select>
		</p>

		<p>Webcamera chat active for direct messages:<br />
		<input type=\"radio\" name=\"inp_webcamera_chat_active_dms\" value=\"1\" "; if($chatWebcameraChatActiveDmsSav == "1"){ echo" checked=\"checked\""; } echo" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		Yes
		&nbsp;
		<input type=\"radio\" name=\"inp_webcamera_chat_active_dms\" value=\"0\" "; if($chatWebcameraChatActiveDmsSav == "0"){ echo" checked=\"checked\""; } echo" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		No
		</p>

		<p>Compensate for emojis string error (Set to true if you get error message like Incorrect string value: '\&nbsp;x98\&nbsp;x83' for column):<br />
		<input type=\"radio\" name=\"inp_compensate_for_emojis_string_error\" value=\"1\" "; if($chatCompensateForEmojisStringErrorSav == "1"){ echo" checked=\"checked\""; } echo" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		Yes
		&nbsp;
		<input type=\"radio\" name=\"inp_compensate_for_emojis_string_error\" value=\"0\" "; if($chatCompensateForEmojisStringErrorSav == "0"){ echo" checked=\"checked\""; } echo" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		No
		</p>

		<p>Users can create channels:<br />
		<input type=\"radio\" name=\"inp_users_can_create_channels\" value=\"1\" "; if($chatUsersCanCreateChannelsSav == "1"){ echo" checked=\"checked\""; } echo" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		Yes
		&nbsp;
		<input type=\"radio\" name=\"inp_users_can_create_channels\" value=\"0\" "; if($chatUsersCanCreateChannelsSav == "0"){ echo" checked=\"checked\""; } echo" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		No
		</p>




		<p><input type=\"submit\" value=\"Save changes\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
		</form>		
		<!-- //Settings -->
		";
}
?>