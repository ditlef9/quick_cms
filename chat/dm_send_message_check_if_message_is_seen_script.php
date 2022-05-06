<?php 
/**
*
* File: chat/dm_send_message_check_if_message_is_seen_script.php
* Version 1.0.0
* Date 13:34 10.09.2019
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

/*- Tables ---------------------------------------------------------------------------- */
include("_tables_chat.php");


/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);



/*- Variables ------------------------------------------------------------------------- */
if(isset($_POST['t_user_id'])){
	$t_user_id = $_POST['t_user_id'];
	$t_user_id = output_html($t_user_id);
}
else{
	$t_user_id = "";
}
$t_user_id_mysql = quote_smart($link, $t_user_id);
if(isset($_POST['message_id'])){
	$message_id = $_POST['message_id'];
	$message_id = output_html($message_id);
}
else{
	$message_id = "";
}
$message_id_mysql = quote_smart($link, $message_id);


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
		echo"<h1>To user not found $query </h1>";
	}
	else{
		// Find conversation (we need conversation key)
		$query = "SELECT message_id, message_conversation_key, message_type, message_text, message_datetime, message_date_saying, message_time_saying, message_time, message_year, message_seen, message_from_user_id, message_from_ip, message_from_hostname, message_from_user_agent FROM $t_chat_dm_messages WHERE message_id=$message_id_mysql AND message_from_user_id=$get_my_user_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_message_id, $get_message_conversation_key, $get_message_type, $get_message_text, $get_message_datetime, $get_message_date_saying, $get_message_time_saying, $get_message_time, $get_message_year, $get_message_seen, $get_message_from_user_id, $get_message_from_ip, $get_message_from_hostname, $get_message_from_user_agent) = $row;
		echo"
		<script language=\"javascript\" type=\"text/javascript\">
			\$(document).ready(function () {
				\$('.dm_message_seen_icon_$get_message_id').attr('src','_gfx/seen_$get_message_seen.png');
         		});
		</script>
		";
		

	} // to_user found

} // logged in


?>