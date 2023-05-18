<?php

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



/*- Variables -------------------------------------------------------------------------- */


if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);

	/*- Tables ---------------------------------------------------------------------------- */
	include("_tables_chat.php");


	// Channel messages
	$query = "SELECT SUM(new_messages) FROM $t_chat_users_starred_channels WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($count_new_messages) = $row;

	// Conversation messages
	$query = "SELECT SUM(conversation_f_unread_messages) FROM $t_chat_dm_conversations WHERE conversation_f_user_id=$my_user_id_mysql AND conversation_f_has_blocked=0";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($count_conversation_f_unread_messages) = $row;

	$total_messages = $count_new_messages+$count_conversation_f_unread_messages;

	// Echo javascript
	if($total_messages == "0"){
		echo"";
	}
	else{
		echo" ($total_messages)";
	}

	// Update numbers
	$query = "SELECT total_unread_id, total_unread_user_id, total_unread_count, total_unread_message FROM $t_chat_total_unread WHERE total_unread_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_total_unread_id, $get_total_unread_user_id, $get_total_unread_count, $get_total_unread_message) = $row;
	if($get_total_unread_id == ""){
		mysqli_query($link, "INSERT INTO $t_chat_total_unread 
		(total_unread_id, total_unread_user_id, total_unread_count, total_unread_message) 
		VALUES 
		(NULL, $my_user_id_mysql, 0, '')")
		or die(mysqli_error($link));
	}
	else{
		if($get_total_unread_count != "$total_messages"){
			$result = mysqli_query($link, "UPDATE $t_chat_total_unread SET total_unread_count=$total_messages WHERE total_unread_user_id=$my_user_id_mysql");

		}
	}
} // logged in
?>