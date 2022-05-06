<?php
/**
*
* File: _admin/_inc/backup/_tables_and_dirs_to_backup/chat.php
* Version 18:17 13.01.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ------------------------------------------------------------------------ */
$t_chat_liquidbase				= $mysqlPrefixSav . "chat_liquidbase";

$t_chat_channels_index		= $mysqlPrefixSav . "chat_channels_index";
$t_chat_channels_messages	= $mysqlPrefixSav . "chat_channels_messages";
$t_chat_channels_users_online	= $mysqlPrefixSav . "chat_channels_users_online";
$t_chat_users_starred_channels	= $mysqlPrefixSav . "chat_users_starred_channels";

$t_chat_dm_conversations = $mysqlPrefixSav . "chat_dm_conversations";
$t_chat_dm_messages	 = $mysqlPrefixSav . "chat_dm_messages";

$t_chat_total_unread = $mysqlPrefixSav . "chat_total_unread";

$t_chat_nicknames 		= $mysqlPrefixSav . "chat_nicknames";
$t_chat_nicknames_changes 	= $mysqlPrefixSav . "chat_nicknames_changes";

$t_chat_user_settings 		= $mysqlPrefixSav . "chat_user_settings";



$tables_truncate_array = array("$t_chat_channels_messages", 
			"$t_chat_channels_users_online", 
			"$t_chat_users_starred_channels", 
			"$t_chat_dm_conversations", 
			"$t_chat_dm_messages", 
			"$t_chat_total_unread",
			"$t_chat_nicknames_changes");

$tables_backup_array = array(
			"$t_chat_liquidbase", 

			"$t_chat_channels_index", 
			"$t_chat_channels_messages", 
			"$t_chat_channels_users_online", 
			"$t_chat_users_starred_channels", 

			"$t_chat_dm_conversations", 
			"$t_chat_dm_messages", 

			"$t_chat_total_unread", 

			"$t_chat_nicknames", 
			"$t_chat_nicknames_changes", 

			"$t_chat_user_settings");

/*- Directories ---------------------------------------------------------------------------- */

$directories_array = array();

?>