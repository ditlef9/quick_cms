<?php
/**
*
* File: _admin/_inc/talk/default.php
* Version 
* Date 19:59 02.08.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
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



/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['mode'])){
	$mode = $_GET['mode'];
	$mode = output_html($mode);
}
else{
	$mode = "";
}


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
echo"
<h1>Chat</h1>


<!-- Chat menu -->
	<div class=\"vertical\">
		<ul>
			";
			include("_inc/chat/menu.php");
			echo"
		</ul>
	</div>
<!-- //Chat menu -->
";
?>