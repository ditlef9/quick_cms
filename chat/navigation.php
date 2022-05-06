<?php 
/*- Current page ------------------------------------------------------------------------- */
$self 		= $_SERVER['PHP_SELF'];
$request_url 	= $_SERVER["REQUEST_URI"];
$self_array     = explode("/", $self);
$array_size     = sizeof($self_array);

$minus_one	= $array_size-1;
$minus_one	= $self_array[$minus_one];

$minus_two	= $array_size-2;
$minus_two	= $self_array[$minus_two];

$complex	= $minus_two . "/" . $minus_one;



/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/chat/ts_index.php");


/*- Special main mode ------------------------------------------------------------------ */
if(!(isset($include_as_navigation_main_mode))){
	$include_as_navigation_main_mode = 0;
}

/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['starred_channel_id'])){
	$starred_channel_id = $_GET['starred_channel_id'];
	$starred_channel_id = output_html($starred_channel_id);
}
else{
	$starred_channel_id = "";
}

if(isset($_GET['t_user_id'])){
	$t_user_id = $_GET['t_user_id'];
	$t_user_id = output_html($t_user_id);
}
else{
	$t_user_id = "";
}

$l_mysql = quote_smart($link, $l);

if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);

	/*- Tables ---------------------------------------------------------------------------- */
	include("_tables_chat.php");

	// Header
	if($include_as_navigation_main_mode == 0){

		echo"
		<ul class=\"toc\"  style=\"margin-bottom:0;padding-bottom:0;\">
			<li class=\"header_home\"><a href=\"$root/chat/index.php?l=$l\""; if($minus_one == "index.php" && $minus_two == "chat"){ echo" class=\"navigation_active\"";}echo">$l_chat</a></li>\n";
	}
	echo"
			<li><a href=\"$root/chat/channel_list.php?l=$l\""; if($minus_one == "channel_list.php"){ echo" class=\"navigation_active\"";}echo">$l_channel_list</a></li>
			<li><a href=\"$root/chat/settings.php?l=$l\""; if($minus_one == "settings.php"){ echo" class=\"navigation_active\"";}echo">$l_settings</a></li>
	
			<li class=\"header_up\"><a href=\"$root/chat/my_starred_channels.php?l=$l\""; if($minus_one == "my_starred_channels.php"){ echo" class=\"navigation_active\"";}echo">$l_starred_channels</a></li>
		</ul>

		<ul class=\"toc\" id=\"navigation_look_for_new_messages_and_conversations_result\" style=\"margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0;\">\n";

	// My channels
	$query = "SELECT starred_channel_id, channel_id, channel_name, new_messages, user_id FROM $t_chat_users_starred_channels WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_starred_channel_id, $get_channel_id, $get_channel_name, $get_new_messages, $get_user_id) = $row;
		echo"			";
		echo"<li><a href=\"$root/chat/open_starred_channel.php?starred_channel_id=$get_starred_channel_id&amp;l=$l\""; if($get_starred_channel_id == "$starred_channel_id"){ echo" class=\"navigation_active\"";}echo">$get_channel_name";
		echo" <b id=\"navigation_starred_channel_id$get_starred_channel_id\">";
		if($get_new_messages != "0"){echo"$get_new_messages"; }
		echo"</b>";
		echo"</a></li>\n";
	}
	echo"
	";

	// Conversations
	echo"
			<li class=\"header_up\"><a href=\"$root/chat/direct_messages.php?l=$l\""; if($minus_one == "direct_messages.php"){ echo" class=\"navigation_active\"";}echo">$l_direct_messages</a></li>
	";
	$query = "SELECT conversation_id, conversation_key, conversation_f_unread_messages, conversation_t_user_id, conversation_t_user_nickname, conversation_t_user_alias, conversation_t_last_online_time FROM $t_chat_dm_conversations WHERE conversation_f_user_id=$my_user_id_mysql AND conversation_f_has_blocked=0";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_conversation_id, $get_conversation_key, $get_conversation_f_unread_messages, $get_conversation_t_user_id, $get_conversation_t_user_nickname, $get_conversation_t_user_alias, $get_conversation_t_last_online_time) = $row;
		if($get_conversation_t_last_online_time == ""){
			$get_conversation_t_last_online_time = 0;
		}
		$seconds_since_online = $time-$get_conversation_t_last_online_time;

		echo"
			<li><a href=\"$root/chat/dm.php?t_user_id=$get_conversation_t_user_id&amp;l=$l\""; if($get_conversation_t_user_id == "$t_user_id"){ echo" class=\"navigation_active\"";}echo" title=\"$get_conversation_t_user_alias\">";

		if($seconds_since_online > 100){
			echo"<span style=\"color: #42b72a;height: 7px; width: 7px; background-color: #a0a0a0; border-radius: 50%; display: inline-block;float: left;margin: 6px 4px 0px 0px\"></span>";
		}
		else{
			echo"<span style=\"color: #42b72a;height: 7px; width: 7px; background-color: #42b72a; border-radius: 50%; display: inline-block;float: left;margin: 6px 4px 0px 0px\"></span>";
		}

		echo"$get_conversation_t_user_nickname";
		echo" <b id=\"conversation_id$get_conversation_id\">";
		if($get_conversation_f_unread_messages != "0"){echo"$get_conversation_f_unread_messages"; }
		echo"</b>";
		echo"</a></li>\n";
	}
	
	// Other nav
	// Footer
	if($include_as_navigation_main_mode == 0){
		echo"
		</ul>

		<!-- Look for new messages script -->
			<script language=\"javascript\" type=\"text/javascript\">
			\$(document).ready(function () {
				function navigation_look_for_new_messages_and_conversations(){

					var data = 'l=$l&starred_channel_id=$starred_channel_id&t_user_id=$t_user_id';
            				\$.ajax({
                				type: \"POST\",
               					url: \"navigation_look_for_new_messages_and_conversations.php\",
                				data: data,
						beforeSend: function(html) { // this happens before actual call
						},
               					success: function(html){
                    					\$(\"#navigation_look_for_new_messages_and_conversations_result\").html(html);
              					}
       									
					});
				}
				setInterval(navigation_look_for_new_messages_and_conversations,2000);
         		});
			</script>
		<!-- //Look for new messages script -->
		";
	}



} // logged in
?>