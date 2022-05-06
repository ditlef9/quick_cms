<?php
/**
*
* File: _admin/_inc/inbox.php
* Version 1.0.1
* Date 11:46 28-Jul-18
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables ---------------------------------------------------------------------------- */
$t_banned_hostnames	= $mysqlPrefixSav . "banned_hostnames";
$t_banned_ips	 	= $mysqlPrefixSav . "banned_ips";
$t_banned_user_agents	= $mysqlPrefixSav . "banned_user_agents";


/*- Check that folders and files exists ------------------------------------------------ */
if(!(is_dir("_inc/dashboard/_banned"))){
	mkdir("_inc/dashboard/_banned");
}
if(!(file_exists("_inc/dashboard/_banned/banned_ips.txt"))){
	$fh = fopen("_inc/dashboard/_banned/banned_ips.txt", "w") or die("can not open file");
	fwrite($fh, "");
	fclose($fh);
}

if(!(file_exists("_inc/dashboard/_banned/banned_hostnames.txt"))){
	$fh = fopen("_inc/dashboard/_banned/banned_hostnames.txt", "w") or die("can not open file");
	fwrite($fh, "");
	fclose($fh);
}
if(!(file_exists("_inc/dashboard/_banned/banned_user_agents.txt"))){
	$fh = fopen("_inc/dashboard/_banned/banned_user_agents.txt", "w") or die("can not open file");
	fwrite($fh, "");
	fclose($fh);
}



/*- Variables -------------------------------------------------------------------------- */
if($action == ""){
	echo"
	<h1>Inbox</h1>
	
	<!-- Messages in inbox -->
		 ";
		$query = "SELECT message_id, message_title, message_text, message_language, message_datetime, message_year, message_month, message_day, message_date_sayning, message_sent_email_warning, message_replied, message_from_user_id, message_from_name, message_from_image, message_from_ip, message_read, message_read_by_user_id, message_read_by_user_name, message_comment, message_archived, message_spam, message_action_needed, message_tags FROM $t_admin_messages_inbox ORDER BY message_id DESC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_message_id, $get_message_title, $get_message_text, $get_message_language, $get_message_datetime, $get_message_year, $get_message_month, $get_message_day, $get_message_date_sayning, $get_message_sent_email_warning, $get_message_replied, $get_message_from_user_id, $get_message_from_name, $get_message_from_image, $get_message_from_ip, $get_message_read, $get_message_read_by_user_id, $get_message_read_by_user_name, $get_message_comment, $get_message_archived, $get_message_spam, $get_message_action_needed, $get_message_tags) = $row;
			
			// Img
			if($get_message_from_image != "" && file_exists("../_uploads/users/images/$get_message_from_user_id/$get_message_from_image")){
				
				$inp_new_x = 40; // 950
				$inp_new_y = 40; // 640
				$thumb_full_path = "../_cache/user_" . $get_message_from_image . "-" . $inp_new_x . "x" . $inp_new_y . ".png";

				if(!(file_exists("$thumb_full_path"))){
					resize_crop_image($inp_new_x, $inp_new_y, "../_uploads/users/images/$get_message_from_user_id/$get_message_from_image", "$thumb_full_path");
				}
			}
			else{
				$thumb_full_path = "_design/gfx/avatar_blank_40.png";
			}
				
			// Read
			if($get_message_read == "0"){
				$read_status = "unread";
			}
			else{
				$read_status = "read";
			}
		
			echo"
			<div class=\"message_div_$read_status\">
				<table>
				 <tr>
				  <td class=\"message_row_img_$read_status\">
					<p>
					<a href=\"index.php?open=$open&amp;page=$page&amp;action=read_message&amp;message_id=$get_message_id&amp;editor_language=$editor_language&amp;l=$l\"><img src=\"$thumb_full_path\" alt=\"$get_message_from_image\" /></a>
					</p>
				  </td>
				  <td class=\"message_row_text_$read_status\">
					<p>	
					<a href=\"index.php?open=$open&amp;page=$page&amp;action=read_message&amp;message_id=$get_message_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"message_from_name_$read_status\">$get_message_from_name</a><br />
					<a href=\"index.php?open=$open&amp;page=$page&amp;action=read_message&amp;message_id=$get_message_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"message_title_$read_status\">$get_message_title</a>
					</p>
				  </td>
				 </tr>
				</table>
			</div>
			";

		}

		echo"
	<!-- //Messages in inbox -->
	";
}
elseif($action == "read_message"){
	if(isset($_GET['message_id'])){
		$message_id = $_GET['message_id'];
		$message_id = strip_tags(stripslashes($message_id));
		$message_id_mysql = quote_smart($link, $message_id);

		
		$query = "SELECT message_id, message_title, message_text, message_language, message_datetime, message_year, message_month, message_day, message_date_sayning, message_sent_email_warning, message_replied, message_from_user_id, message_from_name, message_from_image, message_from_ip, message_read, message_read_by_user_id, message_read_by_user_name, message_comment, message_archived, message_spam, message_action_needed, message_tags FROM $t_admin_messages_inbox WHERE message_id=$message_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_message_id, $get_current_message_title, $get_current_message_text, $get_current_message_language, $get_current_message_datetime, $get_current_message_year, $get_current_message_month, $get_current_message_day, $get_current_message_date_sayning, $get_current_message_sent_email_warning, $get_current_message_replied, $get_current_message_from_user_id, $get_current_message_from_name, $get_current_message_from_ip, $get_current_message_read, $get_current_message_read_by_user_id, $get_current_message_read_by_user_name, $get_current_message_comment, $get_current_message_archived, $get_current_message_spam, $get_current_message_action_needed, $get_current_message_tags) = $row;

		if($get_current_message_id == ""){
			echo"<p>Data not found in database.</p>";
		} // not found in database
		else{
			// Read status
			if($get_current_message_read != "1"){

				$my_user_id_mysql = quote_smart($link, $get_my_user_id);

				$my_user_name_mysql = quote_smart($link, $get_my_user_name);


				$result = mysqli_query($link, "UPDATE $t_admin_messages_inbox SET message_read=1, message_read_by_user_id=$my_user_id_mysql, message_read_by_user_name=$my_user_name_mysql WHERE message_id=$message_id_mysql") or die(mysqli_error($link));
				
			}



			echo"
			<h1>$get_current_message_title</h1>

			$get_current_message_text

			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;&amp;action=delete_message&amp;message_id=$message_id&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" class=\"btn_warning\">Delete</a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l&amp;editor_language=$editor_language#message$get_current_message_id\" class=\"btn\">Go back</a>
			</p>

			";
		} // found
	}
	else{
		echo"<p>Missing variable.</p>";
	} // find message_id
} // action read_message
elseif($action == "delete_message"){
	if(isset($_GET['message_id'])){
		$message_id = $_GET['message_id'];
		$message_id = strip_tags(stripslashes($message_id));
		$message_id_mysql = quote_smart($link, $message_id);

		
		$query = "SELECT message_id, message_title, message_text, message_language, message_datetime, message_year, message_month, message_day, message_date_sayning, message_sent_email_warning, message_replied, message_from_user_id, message_from_name, message_from_image, message_from_ip, message_read, message_read_by_user_id, message_read_by_user_name, message_comment, message_archived, message_spam, message_action_needed, message_tags FROM $t_admin_messages_inbox WHERE message_id=$message_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_message_id, $get_current_message_title, $get_current_message_text, $get_current_message_language, $get_current_message_datetime, $get_current_message_year, $get_current_message_month, $get_current_message_day, $get_current_message_date_sayning, $get_current_message_sent_email_warning, $get_current_message_replied, $get_current_message_from_user_id, $get_current_message_from_name, $get_current_message_from_ip, $get_current_message_read, $get_current_message_read_by_user_id, $get_current_message_read_by_user_name, $get_current_message_comment, $get_current_message_archived, $get_current_message_spam, $get_current_message_action_needed, $get_current_message_tags) = $row;

		if($get_current_message_id == ""){
			echo"<p>Data not found in database.</p>";
		} // not found in database
		else{
			if($process == "1"){

				$result = mysqli_query($link, "DELETE FROM $t_admin_messages_inbox WHERE message_id=$message_id_mysql") or die(mysqli_error($link));
				header("Location: index.php?open=$open&page=$page&l=$l&editor_language=$editor_language&ft=success&fm=deleted");
				exit;
			}
		} // found
	}
	else{
		echo"<p>Missing variable.</p>";
	} // find message_id
} // action delete_message


?>