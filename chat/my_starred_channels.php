<?php 
/**
*
* File: chat/my_starred_channels.php
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
$website_title = "$l_my_starred_channels - $l_chat";
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
		<h1>$l_my_starred_channels</h1>
		
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



		<!-- My channels -->
			<table class=\"hor-zebra\">
			 <thead>
			  <tr>
			   <th scope=\"col\">
				<span>$l_name</span>
			   </th>
			   <th scope=\"col\">
				<span>$l_actions</span>
			   </th>
			  </tr>
			 </thead>
			 <tbody>
			";
			$query = "SELECT starred_channel_id, channel_id, channel_name, new_messages, user_id FROM $t_chat_users_starred_channels WHERE user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_starred_channel_id, $get_channel_id, $get_channel_name, $get_new_messages, $get_user_id) = $row;
				
				if(isset($odd) && $odd == false){
					$odd = true;
				}
				else{
					$odd = false;
				}

				echo"
				<tr>
				  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
					<a href=\"open_starred_channel.php?starred_channel_id=$get_starred_channel_id&amp;l=$l\">$get_channel_name</a>
				  </td>
				  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
					<a href=\"my_starred_channels.php?action=delete&amp;starred_channel_id=$get_starred_channel_id&amp;l=$l\">$l_delete</a>
				  </td>
				 </tr>
				";
			}
			echo"
				 </tbody>
				</table>

		<!-- My channels -->
		";
	} // action == ""
	elseif($action == "delete"){
		if(isset($_GET['starred_channel_id'])){
			$starred_channel_id = $_GET['starred_channel_id'];
			$starred_channel_id = output_html($starred_channel_id);
		}
		else{
			$starred_channel_id = "";
		}
		$starred_channel_id_mysql = quote_smart($link, $starred_channel_id);
		
		// Find channel
		$query = "SELECT starred_channel_id, channel_id, channel_name, new_messages, user_id FROM $t_chat_users_starred_channels WHERE starred_channel_id=$starred_channel_id_mysql AND user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_starred_channel_id, $get_current_channel_id, $get_current_channel_name, $get_current_new_messages, $get_current_user_id) = $row;

		if($get_current_starred_channel_id == ""){
			echo"<h1>Starred Channel not found</h1>";
		}
		else{
			if($process == "1"){
				$result = mysqli_query($link, "DELETE FROM $t_chat_users_starred_channels WHERE starred_channel_id=$get_current_starred_channel_id") or die(mysqli_error($link));

				// Header
				$url = "my_starred_channels.php?ft=success&fm=favorite_deleted&l=$l";
				header("Location: $url");
				exit;
					
			}

			echo"
			<h1>$l_remove $get_current_channel_name</h1>
			<p>$l_are_you_sure</p>

			<p>
			<a href=\"my_starred_channels.php?action=delete&amp;starred_channel_id=$get_starred_channel_id&amp;l=$l&amp;process=1\" class=\"btn_warning\">$l_confirm</a>
			</p>
			";
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