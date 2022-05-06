<?php 
/**
*
* File: chat/direct_messages.php
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
$website_title = "$l_direct_messages - $l_chat";
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
		<h1>$l_direct_messages</h1>
		
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



		<!-- Conversations -->
			<table class=\"hor-zebra\">
			 <thead>
			  <tr>
			   <th scope=\"col\">
				<span>$l_alias</span>
			   </th>
			   <th scope=\"col\">
				<span>$l_user_name</span>
			   </th>
			   <th scope=\"col\">
				<span>$l_blocked</span>
			   </th>
			   <th scope=\"col\">
				<span>$l_actions</span>
			   </th>
			  </tr>
			 </thead>
			 <tbody>
			";
			$query = "SELECT conversation_id, conversation_key, conversation_f_user_id, conversation_f_user_name, conversation_f_user_alias, conversation_f_image_path, conversation_f_image_file, conversation_f_image_thumb40, conversation_f_image_thumb50, conversation_f_has_blocked, conversation_f_unread_messages, conversation_t_user_id, conversation_t_user_name, conversation_t_user_alias, conversation_t_image_path, conversation_t_image_file, conversation_t_image_thumb40, conversation_t_image_thumb50, conversation_t_has_blocked, conversation_t_unread_messages FROM $t_chat_dm_conversations WHERE conversation_f_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_conversation_id, $get_conversation_key, $get_conversation_f_user_id, $get_conversation_f_user_name, $get_conversation_f_user_alias, $get_conversation_f_image_path, $get_conversation_f_image_file, $get_conversation_f_image_thumb40, $get_conversation_f_image_thumb50, $get_conversation_f_has_blocked, $get_conversation_f_unread_messages, $get_conversation_t_user_id, $get_conversation_t_user_name, $get_conversation_t_user_alias, $get_conversation_t_image_path, $get_conversation_t_image_file, $get_conversation_t_image_thumb40, $get_conversation_t_image_thumb50, $get_conversation_t_has_blocked, $get_conversation_t_unread_messages) = $row;
				
				if(isset($odd) && $odd == false){
					$odd = true;
				}
				else{
					$odd = false;
				}


				echo"
				<tr>
				  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
					<a href=\"$root/users/view_profile.php?user_id=$get_conversation_t_user_id&amp;l=$l\">$get_conversation_t_user_alias</a>
				  </td>
				  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
					<span>$get_conversation_t_user_name</span>
				  </td>
				  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
					";
					if($get_conversation_f_has_blocked == "1"){
						echo"$l_yes";
					}
					else{
						echo"$l_no";
					}
					echo"
				  </td>
				  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
					<span>
					";
					if($get_conversation_f_has_blocked == "1"){
						echo"<a href=\"direct_messages.php?action=unblock&amp;conversation_id=$get_conversation_id&amp;l=$l&amp;process=1\">$l_unblock</a>";
					}
					else{
						echo"<a href=\"direct_messages.php?action=block&amp;conversation_id=$get_conversation_id&amp;l=$l\">$l_block</a>";
					}
					echo"
					
					&middot;
					<a href=\"direct_messages.php?action=delete&amp;conversation_id=$get_conversation_id&amp;l=$l\">$l_delete</a>
					</span>
				  </td>
				 </tr>
				";
			}
			echo"
				 </tbody>
				</table>
		<!-- //Conversations -->
		";
	} // action == ""
	elseif($action == "delete"){
		if(isset($_GET['conversation_id'])){
			$conversation_id = $_GET['conversation_id'];
			$conversation_id = output_html($conversation_id);
		}
		else{
			$conversation_id = "";
		}
		$conversation_id_mysql = quote_smart($link, $conversation_id);
		
		// Find conversation_id
		$query = "SELECT conversation_id, conversation_key, conversation_f_user_id, conversation_f_user_name, conversation_f_user_alias, conversation_f_image_path, conversation_f_image_file, conversation_f_image_thumb40, conversation_f_image_thumb50, conversation_f_has_blocked, conversation_f_unread_messages, conversation_t_user_id, conversation_t_user_name, conversation_t_user_alias, conversation_t_image_path, conversation_t_image_file, conversation_t_image_thumb40, conversation_t_image_thumb50, conversation_t_has_blocked, conversation_t_unread_messages FROM $t_chat_dm_conversations WHERE conversation_id=$conversation_id_mysql AND conversation_f_user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_conversation_id, $get_current_conversation_key, $get_current_conversation_f_user_id, $get_current_conversation_f_user_name, $get_current_conversation_f_user_alias, $get_current_conversation_f_image_path, $get_current_conversation_f_image_file, $get_current_conversation_f_image_thumb40, $get_current_conversation_f_image_thumb50, $get_current_conversation_f_has_blocked, $get_current_conversation_f_unread_messages, $get_current_conversation_t_user_id, $get_current_conversation_t_user_name, $get_current_conversation_t_user_alias, $get_current_conversation_t_image_path, $get_current_conversation_t_image_file, $get_current_conversation_t_image_thumb40, $get_current_conversation_t_image_thumb50, $get_current_conversation_t_has_blocked, $get_current_conversation_t_unread_messages) = $row;


		if($get_current_conversation_id == ""){
			echo"<h1>Conversation not found</h1>";
		}
		else{
			if($process == "1"){
				$result = mysqli_query($link, "DELETE FROM $t_chat_dm_conversations WHERE conversation_id=$get_current_conversation_id") or die(mysqli_error($link));

				// Header
				$url = "direct_messages.php?ft=success&fm=favorite_deleted&l=$l";
				header("Location: $url");
				exit;
					
			}

			echo"
			<h1>$l_remove $get_current_conversation_t_user_alias</h1>
			<p>$l_are_you_sure</p>

			<p>
			<a href=\"direct_messages.php?action=delete&amp;conversation_id=$get_current_conversation_id&amp;l=$l&amp;process=1\" class=\"btn_warning\">$l_confirm</a>
			</p>
			";
		} // starred found
	} // action == delete
	elseif($action == "block"){
		if(isset($_GET['conversation_id'])){
			$conversation_id = $_GET['conversation_id'];
			$conversation_id = output_html($conversation_id);
		}
		else{
			$conversation_id = "";
		}
		$conversation_id_mysql = quote_smart($link, $conversation_id);
		
		// Find conversation_id
		$query = "SELECT conversation_id, conversation_key, conversation_f_user_id, conversation_f_user_name, conversation_f_user_alias, conversation_f_image_path, conversation_f_image_file, conversation_f_image_thumb40, conversation_f_image_thumb50, conversation_f_has_blocked, conversation_f_unread_messages, conversation_t_user_id, conversation_t_user_name, conversation_t_user_alias, conversation_t_image_path, conversation_t_image_file, conversation_t_image_thumb40, conversation_t_image_thumb50, conversation_t_has_blocked, conversation_t_unread_messages FROM $t_chat_dm_conversations WHERE conversation_id=$conversation_id_mysql AND conversation_f_user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_conversation_id, $get_current_conversation_key, $get_current_conversation_f_user_id, $get_current_conversation_f_user_name, $get_current_conversation_f_user_alias, $get_current_conversation_f_image_path, $get_current_conversation_f_image_file, $get_current_conversation_f_image_thumb40, $get_current_conversation_f_image_thumb50, $get_current_conversation_f_has_blocked, $get_current_conversation_f_unread_messages, $get_current_conversation_t_user_id, $get_current_conversation_t_user_name, $get_current_conversation_t_user_alias, $get_current_conversation_t_image_path, $get_current_conversation_t_image_file, $get_current_conversation_t_image_thumb40, $get_current_conversation_t_image_thumb50, $get_current_conversation_t_has_blocked, $get_current_conversation_t_unread_messages) = $row;


		if($get_current_conversation_id == ""){
			echo"<h1>Conversation not found</h1>";
		}
		else{
			if($process == "1"){
				$result = mysqli_query($link, "UPDATE $t_chat_dm_conversations SET conversation_f_has_blocked=1 WHERE conversation_id=$get_current_conversation_id") or die(mysqli_error($link));

				// Header
				$url = "direct_messages.php?ft=success&fm=user_blocked&l=$l";
				header("Location: $url");
				exit;
					
			}

			echo"
			<h1>$l_block $get_current_conversation_t_user_alias</h1>
			<p>$l_are_you_sure</p>

			<p>
			<a href=\"direct_messages.php?action=block&amp;conversation_id=$get_current_conversation_id&amp;l=$l&amp;process=1\" class=\"btn_warning\">$l_confirm</a>
			</p>
			";
		} // starred found
	} // action == block
	elseif($action == "unblock"){
		if(isset($_GET['conversation_id'])){
			$conversation_id = $_GET['conversation_id'];
			$conversation_id = output_html($conversation_id);
		}
		else{
			$conversation_id = "";
		}
		$conversation_id_mysql = quote_smart($link, $conversation_id);
		
		// Find conversation_id
		$query = "SELECT conversation_id, conversation_key, conversation_f_user_id, conversation_f_user_name, conversation_f_user_alias, conversation_f_image_path, conversation_f_image_file, conversation_f_image_thumb40, conversation_f_image_thumb50, conversation_f_has_blocked, conversation_f_unread_messages, conversation_t_user_id, conversation_t_user_name, conversation_t_user_alias, conversation_t_image_path, conversation_t_image_file, conversation_t_image_thumb40, conversation_t_image_thumb50, conversation_t_has_blocked, conversation_t_unread_messages FROM $t_chat_dm_conversations WHERE conversation_id=$conversation_id_mysql AND conversation_f_user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_conversation_id, $get_current_conversation_key, $get_current_conversation_f_user_id, $get_current_conversation_f_user_name, $get_current_conversation_f_user_alias, $get_current_conversation_f_image_path, $get_current_conversation_f_image_file, $get_current_conversation_f_image_thumb40, $get_current_conversation_f_image_thumb50, $get_current_conversation_f_has_blocked, $get_current_conversation_f_unread_messages, $get_current_conversation_t_user_id, $get_current_conversation_t_user_name, $get_current_conversation_t_user_alias, $get_current_conversation_t_image_path, $get_current_conversation_t_image_file, $get_current_conversation_t_image_thumb40, $get_current_conversation_t_image_thumb50, $get_current_conversation_t_has_blocked, $get_current_conversation_t_unread_messages) = $row;


		if($get_current_conversation_id == ""){
			echo"<h1>Conversation not found</h1>";
		}
		else{
			if($process == "1"){
				$result = mysqli_query($link, "UPDATE $t_chat_dm_conversations SET conversation_f_has_blocked=0 WHERE conversation_id=$get_current_conversation_id") or die(mysqli_error($link));

				// Header
				$url = "direct_messages.php?ft=success&fm=user_unblocked&l=$l";
				header("Location: $url");
				exit;
					
			}

			echo"
			<h1>$l_unblock $get_current_conversation_t_user_alias</h1>
			<p>$l_are_you_sure</p>

			<p>
			<a href=\"direct_messages.php?action=unblock&amp;conversation_id=$get_current_conversation_id&amp;l=$l&amp;process=1\" class=\"btn_warning\">$l_confirm</a>
			</p>
			";
		} // starred found
	} // action == unblock
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