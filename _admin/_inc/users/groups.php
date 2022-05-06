<?php
/*- MySQL Tables -------------------------------------------------- */
$t_users 	 		= $mysqlPrefixSav . "users";
$t_users_friends 		= $mysqlPrefixSav . "users_friends";
$t_users_friends_requests 	= $mysqlPrefixSav . "users_friends_requests";
$t_users_profile		= $mysqlPrefixSav . "users_profile";
$t_users_profile_photo 		= $mysqlPrefixSav . "users_profile_photo";
$t_users_status 		= $mysqlPrefixSav . "users_status";
$t_users_status_comments 	= $mysqlPrefixSav . "users_status_comments";
$t_users_status_comments_likes 	= $mysqlPrefixSav . "users_status_comments_likes";
$t_users_status_likes 		= $mysqlPrefixSav . "users_status_likes";


$t_users_profile_headlines			= $mysqlPrefixSav . "users_profile_headlines";
$t_users_profile_headlines_translations		= $mysqlPrefixSav . "users_profile_headlines_translations";
$t_users_profile_fields				= $mysqlPrefixSav . "users_profile_fields";
$t_users_profile_fields_translations		= $mysqlPrefixSav . "users_profile_fields_translations";
$t_users_profile_fields_options			= $mysqlPrefixSav . "users_profile_fields_options";
$t_users_profile_fields_options_translations	= $mysqlPrefixSav . "users_profile_fields_options_translations";


$t_users_groups_index = $mysqlPrefixSav . "users_groups_index";
$t_users_groups_members = $mysqlPrefixSav . "users_groups_members";



/*- Tables search --------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";


/*- Access check -------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Config ---------------------------------------------------------------------------- */
include("_data/logo.php");
include("_data/config/user_system.php");

/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['order_by'])) {
	$order_by = $_GET['order_by'];
	$order_by = strip_tags(stripslashes($order_by));
}
else{
	$order_by = "";
}
if($order_by == ""){
	$order_by = "group_name";
}
if(isset($_GET['order_method'])) {
	$order_method = $_GET['order_method'];
	$order_method = strip_tags(stripslashes($order_method));
	if($order_method != "asc" && $order_method != "desc"){
		echo"Wrong order method";
		die;
	}
}
else{
	$order_method = "asc";
}




if($action == ""){
	echo"
	<h1>Groups</h1>


	<!-- Feedback -->
	";
	if($ft != "" && $fm != ""){
		if($fm == "user_deleted"){
			$fm = "$l_user_deleted";
		}
		else{
			$fm = ucfirst($ft);
		}
		echo"<div class=\"$ft\"><p>$fm</p></div>";
	}
	echo"
	<!-- //Feedback -->


	<!-- Groups list -->
		<p>
		<a href=\"index.php?open=$open&amp;page=groups&amp;action=new_group&amp;editor_language=$editor_language\" class=\"btn\">New group</a>
		</p>

		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">";
			if($order_by == "group_id" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=group_id&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>ID</b></a>";
			if($order_by == "group_id" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "group_id" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		   <th scope=\"col\">";
			if($order_by == "group_name" && $order_method == "asc"){
				$order_method_link = "desc";
			}
			else{
				$order_method_link = "asc";
			}

			echo"
			<span><a href=\"index.php?open=$open&amp;page=$page&amp;order_by=group_name&amp;order_method=$order_method_link&amp;editor_language=$editor_language&amp;l=$l\" style=\"color:black;\"><b>Group name</b></a>";
			if($order_by == "group_name" && $order_method == "asc"){
				echo"<img src=\"_design/gfx/arrow_down.png\" alt=\"arrow_down.png\" />";
			}
			if($order_by == "group_name" && $order_method == "desc"){
				echo"<img src=\"_design/gfx/arrow_up.png\" alt=\"arrow_up.png\" />";
			}
			echo"</span>
		   </th>
		   <th scope=\"col\">
			<span>$l_actions</span>
		   </th>
		  </tr>
		</thead>
		<tbody>


	";
	$query = "SELECT group_id, group_name FROM $t_users_groups_index";
	if($order_by == "group_id" OR $order_by == "group_name"){
		if($order_method == "asc"){
			$query = $query . " ORDER BY $order_by ASC";
		}
		else{
			$query = $query . " ORDER BY $order_by DESC";
		}
	}

	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_group_id, $get_group_name) = $row;

	
		echo"
		 <tr>
		  <td>
			<span><a href=\"?open=$open&amp;page=groups&amp;action=open_group&amp;group_id=$get_group_id&amp;l=$l&amp;editor_language=$editor_language\">$get_group_id</a></span>
		  </td>
		  <td>
			<span><a href=\"?open=$open&amp;page=groups&amp;action=open_group&amp;group_id=$get_group_id&amp;l=$l&amp;editor_language=$editor_language\">$get_group_name</a></span>
		  </td>
		  <td>
			<span>
			<a href=\"?open=$open&amp;page=groups&amp;action=edit_group&amp;group_id=$get_group_id&amp;l=$l&amp;editor_language=$editor_language\">$l_edit</a>
			| 
			<a href=\"?open=$open&amp;page=groups&amp;action=delete_group&amp;group_id=$get_group_id&amp;l=$l&amp;editor_language=$editor_language\">$l_delete</a>
			</span>
		  </td>
		 </tr>
		";

	}
	echo"
	
		 </tbody>
		</table>

	<!-- //Groups list -->
	";
}
elseif($action == "new_group"){
	if($process == "1"){
		// Dates
		$datetime = date("Y-m-d H:i:s");
		$date_saying = date("j M Y");

		// Posts
		$inp_name = $_POST['inp_name'];
		$inp_name = output_html($inp_name);
		$inp_name_mysql = quote_smart($link, $inp_name);

		$inp_language = $_POST['inp_language'];
		$inp_language = output_html($inp_language);
		$inp_language_mysql = quote_smart($link, $inp_language);

		$inp_description = $_POST['inp_description'];
		$inp_description = output_html($inp_description);
		$inp_description_mysql = quote_smart($link, $inp_description);

		$inp_privacy = $_POST['inp_privacy'];
		$inp_privacy = output_html($inp_privacy);
		$inp_privacy_mysql = quote_smart($link, $inp_privacy);

		// Key
		$inp_key = "";
		$password_length = rand(8, 16);
		$alphas = range('a', 'z');
		$alphas_len = sizeof($alphas);
		for($i = 0; $i < $password_length; $i++) {
			$random = rand(0, $alphas_len);
			$inp_key .= $alphas[$random];
		}
		$inp_key = output_html($inp_key);
		$inp_key_mysql = quote_smart($link, $inp_key);
		

		// Me
		$inp_created_by_user_id_mysql = quote_smart($link, $get_my_user_id);
		$inp_created_by_user_name_mysql = quote_smart($link, $get_my_user_name);
		$inp_created_by_user_email_mysql = quote_smart($link, $get_my_user_email);

		// IP
		$my_ip = $_SERVER['REMOTE_ADDR'];
		$my_ip = output_html($my_ip);
		$my_ip_mysql = quote_smart($link, $my_ip);
		
		// Hostname
		$my_hostname = "$my_ip";
		if($configSiteUseGethostbyaddrSav == "1"){
			$my_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); // Some servers in local network cant use getostbyaddr because of nameserver missing
		}
		$my_hostname = output_html($my_hostname);
		$my_hostname_mysql = quote_smart($link, $my_hostname);

		
		// User agent
		$my_user_agent = $_SERVER['HTTP_USER_AGENT'];
		$my_user_agent = output_html($my_user_agent);
		$my_user_agent_mysql = quote_smart($link, $my_user_agent);

		// Check for duplicates
		$query = "SELECT group_id FROM $t_users_groups_index WHERE group_name=$inp_name_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_group_id) = $row;
		if($get_group_id != ""){
			$url = "index.php?open=users&page=groups&action=new_group&l=$l&editor_language=$inp_language&ft=error&fm=group_name_already_exists&inp_description=$inp_description&inp_privacy=$inp_privacy";
			header("Location: $url");
			exit;
		}

		// Insert group
		mysqli_query($link, "INSERT INTO $t_users_groups_index
		(group_id, group_name, group_language, group_description, group_privacy, 
		group_key, group_created_by_user_id, group_created_by_user_name, group_created_by_user_email, group_created_by_ip, 
		group_created_by_hostname, group_created_by_user_agent, group_created_datetime, group_created_date_saying) 
		VALUES 
		(NULL, $inp_name_mysql, $inp_language_mysql, $inp_description_mysql, $inp_privacy_mysql, 
		$inp_key_mysql, $inp_created_by_user_id_mysql, $inp_created_by_user_name_mysql, $inp_created_by_user_name_mysql, $my_ip_mysql, 
		$my_hostname_mysql, $my_user_agent_mysql, '$datetime', '$date_saying')")
		or die(mysqli_error($link));

		// Get ID
		$query = "SELECT group_id FROM $t_users_groups_index WHERE group_created_datetime='$datetime'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_group_id) = $row;

		// My photo
		$inp_my_photo_destination_mysql = quote_smart($link, $get_my_photo_destination);
		$inp_my_photo_thumb_50_mysql = quote_smart($link, $get_my_photo_thumb_50);


		// Insert me as owner
		mysqli_query($link, "INSERT INTO $t_users_groups_members
		(member_id, member_group_id, member_user_id, member_user_name, member_user_email, 
		member_user_photo_destination, member_user_photo_thumb_50, member_status, member_invited, member_user_accepted_invitation, 
		member_accepted_by_moderator, member_joined_datetime, member_joined_date_saying) 
		VALUES 
		(NULL, $get_current_group_id, $inp_created_by_user_id_mysql, $inp_created_by_user_name_mysql, $inp_created_by_user_name_mysql, 
		$inp_my_photo_destination_mysql, $inp_my_photo_thumb_50_mysql, 'admin', 0, 1, 
		1, '$datetime', '$date_saying')")
		or die(mysqli_error($link));


		$url = "index.php?open=users&page=groups&action=open_group&group_id=$get_current_group_id&l=$l&editor_language=$inp_language&ft=success&fm=group_created";
		header("Location: $url");
		exit;
	}

	echo"
	<h1>New Groups</h1>

	<!-- Where am I ? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=users&amp;page=groups&amp;l=$l&amp;editor_language=$editor_language\">Groups</a>
		&gt;
		<a href=\"index.php?open=users&amp;page=groups&amp;action=new_group&amp;l=$l&amp;editor_language=$editor_language\">New group</a>
		</p>
	<!-- //Where am I ? -->

	<!-- Feedback -->
	";
	if($ft != "" && $fm != ""){
		$fm = ucfirst($fm);
		echo"<div class=\"$ft\"><p>$fm</p></div>";
	}
	echo"
	<!-- //Feedback -->

	<!-- New group form -->
		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_name\"]').focus();
			});
			</script>
		<!-- //Focus -->
		
		<form method=\"POST\" action=\"index.php?open=users&amp;page=groups&amp;action=new_group&amp;process=1&amp;editor_language=$editor_language&amp;l=$l\" enctype=\"multipart/form-data\">


		<p><b>Language:</b><br />
		<select name=\"inp_language\">";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag_path_16x16, language_active_flag_16x16 FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag_path_16x16, $get_language_active_flag_16x16) = $row;
			echo"		<option value=\"$get_language_active_iso_two\""; if($get_language_active_iso_two == "$editor_language"){ echo" selected=\"selected\""; } echo">$get_language_active_name</option>\n";
			
		} // while
		echo"
		</select>
		</p>

		<p><b>Name:</b><br />
		<input type=\"text\" name=\"inp_name\" value=\"\" size=\"48\" style=\"width: 99%;\" />
		</p>

		<p><b>Description:</b><br />
		<textarea name=\"inp_description\" cols=\"40\" rows=\"5\" style=\"width: 99%;\">";
		if(isset($_GET['inp_description'])){
			$inp_description = $_GET['inp_description'];
			$inp_description = output_html($inp_description);
			echo"$inp_description";
		}
		echo"</textarea>
		</p>

		<p><b>Privacy:</b><br />";
		$inp_privacy = "public";
		if(isset($_GET['inp_privacy'])){
			$inp_privacy = $_GET['inp_privacy'];
			$inp_privacy = output_html($inp_privacy);
		}
		echo"
		<select name=\"inp_privacy\">
			<option value=\"public\""; if($inp_privacy == "public"){ echo" selected=\"selected\""; } echo">Public</option>
			<option value=\"private\""; if($inp_privacy == "private"){ echo" selected=\"selected\""; } echo">Private</option>
		</select>
		</p>

		<p>
		<input type=\"submit\" value=\"Create group\" class=\"btn_default\" />
		</p>

		</form>
	<!-- //New group form -->
	";
} // new group
elseif($action == "open_group"){
	if(isset($_GET['group_id'])) {
		$group_id = $_GET['group_id'];
		$group_id = output_html($group_id);
		if(!(is_numeric($group_id))){
			echo"Group id not numeric";
			die;
		}
	}
	else{
		echo"Missing group id";
		die;
	}
	/*- Find group ------------------------------------------------------------------------- */
	$group_id_mysql = quote_smart($link, $group_id);
	$query = "SELECT group_id, group_name, group_language, group_description, group_privacy, group_key, group_logo_path, group_logo_file, group_created_by_user_id, group_created_by_user_name, group_created_by_user_email, group_created_by_ip, group_created_by_hostname, group_created_by_user_agent, group_created_datetime, group_created_date_saying, group_updated_by_user_id, group_updated_by_user_name, group_updated_by_user_email, group_updated_by_ip, group_updated_by_hostname, group_updated_by_user_agent, group_updated_datetime, group_updated_date_saying FROM $t_users_groups_index WHERE group_id=$group_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_group_id, $get_current_group_name, $get_current_group_language, $get_current_group_description, $get_current_group_privacy, $get_current_group_key, $get_current_group_logo_path, $get_current_group_logo_file, $get_current_group_created_by_user_id, $get_current_group_created_by_user_name, $get_current_group_created_by_user_email, $get_current_group_created_by_ip, $get_current_group_created_by_hostname, $get_current_group_created_by_user_agent, $get_current_group_created_datetime, $get_current_group_created_date_saying, $get_current_group_updated_by_user_id, $get_current_group_updated_by_user_name, $get_current_group_updated_by_user_email, $get_current_group_updated_by_ip, $get_current_group_updated_by_hostname, $get_current_group_updated_by_user_agent, $get_current_group_updated_datetime, $get_current_group_updated_date_saying) = $row;
	if($get_current_group_id == ""){
		echo"Group_not_found";
		exit;
	}
	echo"

	<h1>$get_current_group_name</h1>

	<!-- Where am I ? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=users&amp;page=groups&amp;l=$l&amp;editor_language=$editor_language\">Groups</a>
		&gt;
		<a href=\"index.php?open=users&amp;page=groups&amp;action=open_group&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\">$get_current_group_name</a>
		</p>
	<!-- //Where am I ? -->

	<!-- Feedback -->
	";
	if($ft != "" && $fm != ""){
		$fm = ucfirst($fm);
		echo"<div class=\"$ft\"><p>$fm</p></div>";
	}
	echo"
	<!-- //Feedback -->

	<!-- Actions -->
		<p>
		<a href=\"index.php?open=users&amp;page=groups&amp;action=add_member_to_group&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn_default\">Add member</a>
		<a href=\"index.php?open=users&amp;page=groups&amp;action=edit_group&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn_default\">Edit</a>
		<a href=\"index.php?open=users&amp;page=groups&amp;action=delete_group&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn_default\">Delete</a>
		</p>
	<!-- //Actions -->

	<!-- Group members -->
		<h2>Members</h2>

			
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th>
			<span>Username</span>
		   </th>
		   <th>
			<span>Status</span>
		   </th>
		   <th>
			<span>Actions</span>
		    </th>
		   </tr>
		  </thead>
		 <tbody>\n";

		$x = 1;
		$query = "SELECT member_id, member_group_id, member_user_id, member_user_name, member_user_email, member_user_photo_destination, member_user_photo_thumb_50, member_status, member_invited, member_user_accepted_invitation, member_accepted_by_moderator, member_joined_datetime, member_joined_date_saying FROM $t_users_groups_members WHERE member_group_id=$get_current_group_id ORDER BY member_user_name ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_member_id, $get_member_group_id, $get_member_user_id, $get_member_user_name, $get_member_user_email, $get_member_user_photo_destination, $get_member_user_photo_thumb_50, $get_member_status, $get_member_invited, $get_member_user_accepted_invitation, $get_member_accepted_by_moderator, $get_member_joined_datetime, $get_member_joined_date_saying) = $row;

			// Style
			$style = "";
			if($get_member_status == "admin_invited" OR $get_member_status == "moderator_invited" OR $get_member_status == "member_invited"){
				$style = "important";
			}
			if($get_member_accepted_by_moderator == "0"){
				$style = "danger";
			}
			echo"
			 <tr>
			  <td"; if($style != ""){ echo" class=\"$style\""; } echo">
				<span>
				<a href=\"index.php?open=users&amp;page=groups&amp;action=view_group_member&amp;group_id=$get_current_group_id&amp;member_id=$get_member_id&amp;l=$l\">$get_member_user_name</a>
				</span>
			  </td>
			  <td"; if($style != ""){ echo" class=\"$style\""; } echo">
				<span>";
				if($get_member_status == "admin"){
					echo"Admin";
				}
				elseif($get_member_status == "admin_invited"){
					echo"Waiting for user to accept invitation";
				}
				elseif($get_member_status == "moderator"){
					echo"Moderator";
				}
				elseif($get_member_status == "moderator_invited"){
					echo"Waiting for user to accept invitation";
				}
				elseif($get_member_status == "member"){
					echo"Member";
				}
				elseif($get_member_status == "member_invited"){
					echo"Waiting for user to accept invitation";
				}
				else{
					echo"?";
				}
					
				if($get_member_accepted_by_moderator == "0"){
					echo" - Not verified by moderator";
					if($get_my_member_status == "admin" OR $get_my_member_status == "moderator"){
						echo"<a href=\"index.php?open=users&amp;page=groups&amp;action=verify_group_member&amp;group_id=$get_current_group_id&amp;member_id=$get_member_id&amp;l=$l&amp;process=1\" class=\"btn_default\">Verify user</a>";
					}
				}
				echo"</span>
			  </td>
			  <td"; if($style != ""){ echo" class=\"$style\""; } echo">
				<span>
				<a href=\"index.php?open=users&amp;page=groups&amp;action=remove_group_member&amp;group_id=$get_current_group_id&amp;member_id=$get_member_id&amp;l=$l\">Remove</a>
				</span>
			  </td>
			 </tr>
			";
		}
		echo"
		 </tbody>
		</table>
	<!-- //Group members -->
	";
} // open group
elseif($action == "edit_group"){
	if(isset($_GET['group_id'])) {
		$group_id = $_GET['group_id'];
		$group_id = output_html($group_id);
		if(!(is_numeric($group_id))){
			echo"Group id not numeric";
			die;
		}
	}
	else{
		echo"Missing group id";
		die;
	}
	/*- Find group ------------------------------------------------------------------------- */
	$group_id_mysql = quote_smart($link, $group_id);
	$query = "SELECT group_id, group_name, group_language, group_description, group_privacy, group_key, group_logo_path, group_logo_file, group_created_by_user_id, group_created_by_user_name, group_created_by_user_email, group_created_by_ip, group_created_by_hostname, group_created_by_user_agent, group_created_datetime, group_created_date_saying, group_updated_by_user_id, group_updated_by_user_name, group_updated_by_user_email, group_updated_by_ip, group_updated_by_hostname, group_updated_by_user_agent, group_updated_datetime, group_updated_date_saying FROM $t_users_groups_index WHERE group_id=$group_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_group_id, $get_current_group_name, $get_current_group_language, $get_current_group_description, $get_current_group_privacy, $get_current_group_key, $get_current_group_logo_path, $get_current_group_logo_file, $get_current_group_created_by_user_id, $get_current_group_created_by_user_name, $get_current_group_created_by_user_email, $get_current_group_created_by_ip, $get_current_group_created_by_hostname, $get_current_group_created_by_user_agent, $get_current_group_created_datetime, $get_current_group_created_date_saying, $get_current_group_updated_by_user_id, $get_current_group_updated_by_user_name, $get_current_group_updated_by_user_email, $get_current_group_updated_by_ip, $get_current_group_updated_by_hostname, $get_current_group_updated_by_user_agent, $get_current_group_updated_datetime, $get_current_group_updated_date_saying) = $row;
	if($get_current_group_id == ""){
		echo"Group_not_found";
		exit;
	}
	if($process == "1"){

		// Dates
		$datetime = date("Y-m-d H:i:s");
		$date_saying = date("j M Y");

		// Posts
		$inp_name = $_POST['inp_name'];
		$inp_name = output_html($inp_name);
		$inp_name_mysql = quote_smart($link, $inp_name);

		$inp_language = $_POST['inp_language'];
		$inp_language = output_html($inp_language);
		$inp_language_mysql = quote_smart($link, $inp_language);

		$inp_description = $_POST['inp_description'];
		$inp_description = output_html($inp_description);
		$inp_description_mysql = quote_smart($link, $inp_description);

		$inp_privacy = $_POST['inp_privacy'];
		$inp_privacy = output_html($inp_privacy);
		$inp_privacy_mysql = quote_smart($link, $inp_privacy);


		// Me
		$inp_my_user_id_mysql = quote_smart($link, $get_my_user_id);
		$inp_my_user_name_mysql = quote_smart($link, $get_my_user_name);
		$inp_my_user_email_mysql = quote_smart($link, $get_my_user_email);

		// IP
		$my_ip = $_SERVER['REMOTE_ADDR'];
		$my_ip = output_html($my_ip);
		$my_ip_mysql = quote_smart($link, $my_ip);
		
		// Hostname
		$my_hostname = "$my_ip";
		if($configSiteUseGethostbyaddrSav == "1"){
			$my_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); // Some servers in local network cant use getostbyaddr because of nameserver missing
		}
		$my_hostname = output_html($my_hostname);
		$my_hostname_mysql = quote_smart($link, $my_hostname);

		
		// User agent
		$my_user_agent = $_SERVER['HTTP_USER_AGENT'];
		$my_user_agent = output_html($my_user_agent);
		$my_user_agent_mysql = quote_smart($link, $my_user_agent);

		// Check for duplicates
		$query = "SELECT group_id FROM $t_users_groups_index WHERE group_name=$inp_name_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_group_id) = $row;
		if($get_group_id != ""){
			if($get_current_group_id != "$get_group_id"){
				$inp_description = str_replace("\n", "<br />", $inp_description);
				$url = "index.php?open=users&page=groups&action=new_group&l=$l&editor_language=$inp_language&ft=error&fm=group_name_already_exists&inp_description=$inp_description&inp_privacy=$inp_privacy";
				header("Location: $url");
				exit;
			}
		}

		// Update group
		mysqli_query($link, "UPDATE $t_users_groups_index SET 
				group_name=$inp_name_mysql, 
				group_language=$inp_language_mysql, 
				group_description=$inp_description_mysql, 
				group_privacy=$inp_privacy_mysql, 
				group_updated_by_user_id=$inp_my_user_id_mysql, 
				group_updated_by_user_name=$inp_my_user_name_mysql, 
				group_updated_by_user_email=$inp_my_user_email_mysql, 
				group_updated_by_ip=$my_ip_mysql, 
				group_updated_by_hostname=$my_hostname_mysql, 
				group_updated_by_user_agent=$my_user_agent_mysql, 
				group_updated_datetime='$datetime', 
				group_updated_date_saying='$date_saying'
				WHERE group_id=$get_current_group_id") or die(mysqli_error($link));

		// Header
		$url = "index.php?open=users&page=groups&action=edit_group&group_id=$get_current_group_id&l=$l&editor_language=$editor_language&ft=success&fm=changes_saved";
		header("Location: $url");
		exit;
	}

	echo"

	<h1>$get_current_group_name</h1>

	<!-- Where am I ? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=users&amp;page=groups&amp;l=$l&amp;editor_language=$editor_language\">Groups</a>
		&gt;
		<a href=\"index.php?open=users&amp;page=groups&amp;action=open_group&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\">$get_current_group_name</a>
		&gt;
		<a href=\"index.php?open=users&amp;page=groups&amp;action=edit_group&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\">Edit group</a>
		</p>
	<!-- //Where am I ? -->

	<!-- Feedback -->
	";
	if($ft != "" && $fm != ""){
		$fm = ucfirst($fm);
		echo"<div class=\"$ft\"><p>$fm</p></div>";
	}
	echo"
	<!-- //Feedback -->

	<!-- Actions -->
		<p>
		<a href=\"index.php?open=users&amp;page=groups&amp;action=add_member_to_group&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn_default\">Add member</a>
		<a href=\"index.php?open=users&amp;page=groups&amp;action=edit_group&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn_default\">Edit</a>
		<a href=\"index.php?open=users&amp;page=groups&amp;action=delete_group&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn_default\">Delete</a>
		</p>
	<!-- //Actions -->

	<!-- Edit group form -->
		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_name\"]').focus();
			});
			</script>
		<!-- //Focus -->
		
		<form method=\"POST\" action=\"index.php?open=users&amp;page=groups&amp;action=edit_group&amp;group_id=$get_current_group_id&amp;process=1&amp;editor_language=$editor_language&amp;l=$l\" enctype=\"multipart/form-data\">


		<p><b>Language:</b><br />
		<select name=\"inp_language\">";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag_path_16x16, language_active_flag_16x16 FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag_path_16x16, $get_language_active_flag_16x16) = $row;
			echo"		<option value=\"$get_language_active_iso_two\""; if($get_language_active_iso_two == "$get_current_group_language"){ echo" selected=\"selected\""; } echo">$get_language_active_name</option>\n";
			
		} // while
		echo"
		</select>
		</p>

		<p><b>Name:</b><br />
		<input type=\"text\" name=\"inp_name\" value=\"$get_current_group_name\" size=\"48\" style=\"width: 99%;\" />
		</p>

		<p><b>Description:</b><br />
		<textarea name=\"inp_description\" cols=\"40\" rows=\"5\" style=\"width: 99%;\">";
		$get_current_group_description = str_replace("<br />", "\n", $get_current_group_description);
		echo"$get_current_group_description</textarea>
		</p>

		<p><b>Privacy:</b><br />";
		$inp_privacy = "public";
		if(isset($_GET['inp_privacy'])){
			$inp_privacy = $_GET['inp_privacy'];
			$inp_privacy = output_html($inp_privacy);
		}
		echo"
		<select name=\"inp_privacy\">
			<option value=\"public\""; if($get_current_group_privacy == "public"){ echo" selected=\"selected\""; } echo">Public</option>
			<option value=\"private\""; if($get_current_group_privacy == "private"){ echo" selected=\"selected\""; } echo">Private</option>
		</select>
		</p>

		<p>
		<input type=\"submit\" value=\"Save changes\" class=\"btn_default\" />
		</p>

		</form>
	<!-- //Edit group form -->
	";
} // edit group
elseif($action == "delete_group"){
	if(isset($_GET['group_id'])) {
		$group_id = $_GET['group_id'];
		$group_id = output_html($group_id);
		if(!(is_numeric($group_id))){
			echo"Group id not numeric";
			die;
		}
	}
	else{
		echo"Missing group id";
		die;
	}
	/*- Find group ------------------------------------------------------------------------- */
	$group_id_mysql = quote_smart($link, $group_id);
	$query = "SELECT group_id, group_name, group_language, group_description, group_privacy, group_key, group_logo_path, group_logo_file, group_created_by_user_id, group_created_by_user_name, group_created_by_user_email, group_created_by_ip, group_created_by_hostname, group_created_by_user_agent, group_created_datetime, group_created_date_saying, group_updated_by_user_id, group_updated_by_user_name, group_updated_by_user_email, group_updated_by_ip, group_updated_by_hostname, group_updated_by_user_agent, group_updated_datetime, group_updated_date_saying FROM $t_users_groups_index WHERE group_id=$group_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_group_id, $get_current_group_name, $get_current_group_language, $get_current_group_description, $get_current_group_privacy, $get_current_group_key, $get_current_group_logo_path, $get_current_group_logo_file, $get_current_group_created_by_user_id, $get_current_group_created_by_user_name, $get_current_group_created_by_user_email, $get_current_group_created_by_ip, $get_current_group_created_by_hostname, $get_current_group_created_by_user_agent, $get_current_group_created_datetime, $get_current_group_created_date_saying, $get_current_group_updated_by_user_id, $get_current_group_updated_by_user_name, $get_current_group_updated_by_user_email, $get_current_group_updated_by_ip, $get_current_group_updated_by_hostname, $get_current_group_updated_by_user_agent, $get_current_group_updated_datetime, $get_current_group_updated_date_saying) = $row;
	if($get_current_group_id == ""){
		echo"Group_not_found";
		exit;
	}
	if($process == "1"){

		// Delete group
		mysqli_query($link, "DELETE FROM $t_users_groups_index WHERE group_id=$get_current_group_id") or die(mysqli_error($link));
		mysqli_query($link, "DELETE FROM $t_users_groups_members WHERE member_group_id=$get_current_group_id") or die(mysqli_error($link));

		// Header
		$url = "index.php?open=users&page=groups&l=$l&editor_language=$editor_language&ft=success&fm=group_deleted";
		header("Location: $url");
		exit;
	}

	echo"

	<h1>$get_current_group_name</h1>

	<!-- Where am I ? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=users&amp;page=groups&amp;l=$l&amp;editor_language=$editor_language\">Groups</a>
		&gt;
		<a href=\"index.php?open=users&amp;page=groups&amp;action=open_group&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\">$get_current_group_name</a>
		&gt;
		<a href=\"index.php?open=users&amp;page=groups&amp;action=delete_group&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\">Delete group</a>
		</p>
	<!-- //Where am I ? -->

	<!-- Feedback -->
	";
	if($ft != "" && $fm != ""){
		$fm = ucfirst($fm);
		echo"<div class=\"$ft\"><p>$fm</p></div>";
	}
	echo"
	<!-- //Feedback -->

	<!-- Actions -->
		<p>
		<a href=\"index.php?open=users&amp;page=groups&amp;action=add_member_to_group&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn_default\">Add member</a>
		<a href=\"index.php?open=users&amp;page=groups&amp;action=edit_group&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn_default\">Edit</a>
		<a href=\"index.php?open=users&amp;page=groups&amp;action=delete_group&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn_default\">Delete</a>
		</p>
	<!-- //Actions -->

	<!-- Delete group form -->
		<p>
		Are you sure you want to delete the group?
		</p>

		<p>
		<a href=\"index.php?open=users&amp;page=groups&amp;action=delete_group&amp;group_id=$get_current_group_id&amp;process=1&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_danger\">Confirm</a>
		</p>


	<!-- //Delete group form -->
	";
} // delete group
elseif($action == "add_member_to_group"){
	if(isset($_GET['group_id'])) {
		$group_id = $_GET['group_id'];
		$group_id = output_html($group_id);
		if(!(is_numeric($group_id))){
			echo"Group id not numeric";
			die;
		}
	}
	else{
		echo"Missing group id";
		die;
	}
	/*- Find group ------------------------------------------------------------------------- */
	$group_id_mysql = quote_smart($link, $group_id);
	$query = "SELECT group_id, group_name, group_language, group_description, group_privacy, group_key, group_logo_path, group_logo_file, group_created_by_user_id, group_created_by_user_name, group_created_by_user_email, group_created_by_ip, group_created_by_hostname, group_created_by_user_agent, group_created_datetime, group_created_date_saying, group_updated_by_user_id, group_updated_by_user_name, group_updated_by_user_email, group_updated_by_ip, group_updated_by_hostname, group_updated_by_user_agent, group_updated_datetime, group_updated_date_saying FROM $t_users_groups_index WHERE group_id=$group_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_group_id, $get_current_group_name, $get_current_group_language, $get_current_group_description, $get_current_group_privacy, $get_current_group_key, $get_current_group_logo_path, $get_current_group_logo_file, $get_current_group_created_by_user_id, $get_current_group_created_by_user_name, $get_current_group_created_by_user_email, $get_current_group_created_by_ip, $get_current_group_created_by_hostname, $get_current_group_created_by_user_agent, $get_current_group_created_datetime, $get_current_group_created_date_saying, $get_current_group_updated_by_user_id, $get_current_group_updated_by_user_name, $get_current_group_updated_by_user_email, $get_current_group_updated_by_ip, $get_current_group_updated_by_hostname, $get_current_group_updated_by_user_agent, $get_current_group_updated_datetime, $get_current_group_updated_date_saying) = $row;
	if($get_current_group_id == ""){
		echo"Group_not_found";
		exit;
	}
	if($process == "1"){

		// Dates
		$datetime = date("Y-m-d H:i:s");
		$date_saying = date("j M Y");

		// Find user
		$inp_username = $_POST['inp_username'];
		$inp_username = output_html($inp_username);
		$inp_username_mysql = quote_smart($link, $inp_username);


		$query = "SELECT user_id, user_email, user_name FROM $t_users WHERE user_name=$inp_username_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_user_id, $get_user_email, $get_user_name) = $row;
		if($get_user_id == ""){
			$url = "index.php?open=users&page=groups&action=add_member_to_group&group_id=$get_current_group_id&l=$l&editor_language=$inp_language&ft=error&fm=user_not_found";
			header("Location: $url");
			exit;
		}

		$query = "SELECT photo_id, photo_destination, photo_thumb_50 FROM $t_users_profile_photo WHERE photo_user_id=$get_user_id AND photo_profile_image=1";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_photo_id, $get_photo_destination, $get_photo_thumb_50) = $row;

		$inp_user_id_mysql = quote_smart($link, $get_user_id);
		$inp_user_email_mysql = quote_smart($link, $get_user_email);
		$inp_user_name_mysql = quote_smart($link, $get_user_name);
		$inp_my_photo_destination_mysql = quote_smart($link, $get_photo_destination);
		$inp_my_photo_thumb_50_mysql = quote_smart($link, $get_photo_thumb_50);

		// Status
		$inp_status = $_POST['inp_status'];
		$inp_status = output_html($inp_status);
		$inp_status_mysql = quote_smart($link, $inp_status);


		// Insert user
		mysqli_query($link, "INSERT INTO $t_users_groups_members
		(member_id, member_group_id, member_user_id, member_user_name, member_user_email, 
		member_user_photo_destination, member_user_photo_thumb_50, member_status, member_invited, member_user_accepted_invitation, 
		member_accepted_by_moderator, member_joined_datetime, member_joined_date_saying) 
		VALUES 
		(NULL, $get_current_group_id, $inp_user_id_mysql, $inp_user_name_mysql, $inp_user_email_mysql, 
		$inp_my_photo_destination_mysql, $inp_my_photo_thumb_50_mysql, $inp_status_mysql, 0, 1, 
		1, '$datetime', '$date_saying')")
		or die(mysqli_error($link));
		

		// Header
		$url = "index.php?open=users&page=groups&action=add_member_to_group&group_id=$get_current_group_id&l=$l&editor_language=$editor_language&ft=success&fm=member_added";
		header("Location: $url");
		exit;
	}

	echo"

	<h1>$get_current_group_name</h1>

	<!-- Where am I ? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=users&amp;page=groups&amp;l=$l&amp;editor_language=$editor_language\">Groups</a>
		&gt;
		<a href=\"index.php?open=users&amp;page=groups&amp;action=open_group&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\">$get_current_group_name</a>
		&gt;
		<a href=\"index.php?open=users&amp;page=groups&amp;action=add_member_to_group&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\">Add member to group</a>
		</p>
	<!-- //Where am I ? -->

	<!-- Feedback -->
	";
	if($ft != "" && $fm != ""){
		$fm = ucfirst($fm);
		echo"<div class=\"$ft\"><p>$fm</p></div>";
	}
	echo"
	<!-- //Feedback -->

	<!-- Actions -->
		<p>
		<a href=\"index.php?open=users&amp;page=groups&amp;action=add_member_to_group&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn_default\">Add member</a>
		<a href=\"index.php?open=users&amp;page=groups&amp;action=edit_group&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn_default\">Edit</a>
		<a href=\"index.php?open=users&amp;page=groups&amp;action=delete_group&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn_default\">Delete</a>
		</p>
	<!-- //Actions -->

	<!-- Add member to group form -->
		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_username\"]').focus();
			});
			</script>
		<!-- //Focus -->
		
		<form method=\"POST\" action=\"index.php?open=users&amp;page=groups&amp;action=add_member_to_group&amp;group_id=$get_current_group_id&amp;process=1&amp;editor_language=$editor_language&amp;l=$l\" enctype=\"multipart/form-data\">



		<p><b>Username:</b><br />
		<input type=\"text\" name=\"inp_username\" value=\"\" size=\"48\" style=\"width: 99%;\" />
		</p>

		<p><b>Status:</b><br />
		<select name=\"inp_status\">
			<option value=\"admin\">Admin</option>
			<option value=\"moderator\">Moderator</option>
			<option value=\"member\" selected=\"selected\">Member</option>
		</select>
		</p>

		<p>
		<input type=\"submit\" value=\"Add to group\" class=\"btn_default\" />
		</p>

		</form>
	<!-- //Add member to group form -->
	";
} // add_member_to_group
elseif($action == "remove_group_member"){
	if(isset($_GET['group_id'])) {
		$group_id = $_GET['group_id'];
		$group_id = output_html($group_id);
		if(!(is_numeric($group_id))){
			echo"Group id not numeric";
			die;
		}
	}
	else{
		echo"Missing group id";
		die;
	}
	if(isset($_GET['member_id'])) {
		$member_id = $_GET['member_id'];
		$member_id = output_html($member_id);
		if(!(is_numeric($member_id))){
			echo"Member id not numeric";
			die;
		}
	}
	else{
		echo"Missing member id";
		die;
	}
	/*- Find group ------------------------------------------------------------------------- */
	$group_id_mysql = quote_smart($link, $group_id);
	$query = "SELECT group_id, group_name, group_language, group_description, group_privacy, group_key, group_logo_path, group_logo_file, group_created_by_user_id, group_created_by_user_name, group_created_by_user_email, group_created_by_ip, group_created_by_hostname, group_created_by_user_agent, group_created_datetime, group_created_date_saying, group_updated_by_user_id, group_updated_by_user_name, group_updated_by_user_email, group_updated_by_ip, group_updated_by_hostname, group_updated_by_user_agent, group_updated_datetime, group_updated_date_saying FROM $t_users_groups_index WHERE group_id=$group_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_group_id, $get_current_group_name, $get_current_group_language, $get_current_group_description, $get_current_group_privacy, $get_current_group_key, $get_current_group_logo_path, $get_current_group_logo_file, $get_current_group_created_by_user_id, $get_current_group_created_by_user_name, $get_current_group_created_by_user_email, $get_current_group_created_by_ip, $get_current_group_created_by_hostname, $get_current_group_created_by_user_agent, $get_current_group_created_datetime, $get_current_group_created_date_saying, $get_current_group_updated_by_user_id, $get_current_group_updated_by_user_name, $get_current_group_updated_by_user_email, $get_current_group_updated_by_ip, $get_current_group_updated_by_hostname, $get_current_group_updated_by_user_agent, $get_current_group_updated_datetime, $get_current_group_updated_date_saying) = $row;
	if($get_current_group_id == ""){
		echo"Group_not_found";
		exit;
	}
	/*- Find member ------------------------------------------------------------------------- */
	$member_id_mysql = quote_smart($link, $member_id);
	$query = "SELECT member_id, member_group_id, member_user_id, member_user_name, member_user_email, member_user_photo_destination, member_user_photo_thumb_50, member_status, member_invited, member_user_accepted_invitation, member_accepted_by_moderator, member_joined_datetime, member_joined_date_saying FROM $t_users_groups_members WHERE member_id=$member_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_member_id, $get_current_member_group_id, $get_current_member_user_id, $get_current_member_user_name, $get_current_member_user_email, $get_current_member_user_photo_destination, $get_current_member_user_photo_thumb_50, $get_current_member_status, $get_current_member_invited, $get_current_member_user_accepted_invitation, $get_current_member_accepted_by_moderator, $get_current_member_joined_datetime, $get_current_member_joined_date_saying) = $row;
	if($get_current_member_id == ""){
		echo"member not found";
		exit;
	}
	if($process == "1"){

		
		// Delete member
		mysqli_query($link, "DELETE FROM $t_users_groups_members
					WHERE member_id=$get_current_member_id")
		or die(mysqli_error($link));
		

		// Header
		$url = "index.php?open=users&page=groups&action=open_group&group_id=$get_current_group_id&l=$l&editor_language=$editor_language&ft=success&fm=member_deleted";
		header("Location: $url");
		exit;
	}

	echo"

	<h1>$get_current_group_name</h1>

	<!-- Where am I ? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=users&amp;page=groups&amp;l=$l&amp;editor_language=$editor_language\">Groups</a>
		&gt;
		<a href=\"index.php?open=users&amp;page=groups&amp;action=open_group&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\">$get_current_group_name</a>
		&gt;
		<a href=\"index.php?open=users&amp;page=groups&amp;action=remove_group_member&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\">Remove member from group</a>
		</p>
	<!-- //Where am I ? -->

	<!-- Feedback -->
	";
	if($ft != "" && $fm != ""){
		$fm = ucfirst($fm);
		echo"<div class=\"$ft\"><p>$fm</p></div>";
	}
	echo"
	<!-- //Feedback -->

	<!-- Actions -->
		<p>
		<a href=\"index.php?open=users&amp;page=groups&amp;action=add_member_to_group&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn_default\">Add member</a>
		<a href=\"index.php?open=users&amp;page=groups&amp;action=edit_group&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn_default\">Edit</a>
		<a href=\"index.php?open=users&amp;page=groups&amp;action=delete_group&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn_default\">Delete</a>
		</p>
	<!-- //Actions -->

	<!-- Remove member from group form -->
		
		<p>
		Are you sure you want to remove the member <b>$get_current_member_user_name</b> from the group?
		</p>

		<p>
		<a href=\"index.php?open=users&amp;page=groups&amp;action=remove_group_member&amp;group_id=$get_current_group_id&amp;member_id=$get_current_member_id&amp;process=1&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_danger\">Confirm</a>
		</p>
	<!-- //Remove member from group form -->
	";
} // remove_group_member
elseif($action == "view_group_member"){
	if(isset($_GET['group_id'])) {
		$group_id = $_GET['group_id'];
		$group_id = output_html($group_id);
		if(!(is_numeric($group_id))){
			echo"Group id not numeric";
			die;
		}
	}
	else{
		echo"Missing group id";
		die;
	}
	if(isset($_GET['member_id'])) {
		$member_id = $_GET['member_id'];
		$member_id = output_html($member_id);
		if(!(is_numeric($member_id))){
			echo"Member id not numeric";
			die;
		}
	}
	else{
		echo"Missing member id";
		die;
	}
	/*- Find group ------------------------------------------------------------------------- */
	$group_id_mysql = quote_smart($link, $group_id);
	$query = "SELECT group_id, group_name, group_language, group_description, group_privacy, group_key, group_logo_path, group_logo_file, group_created_by_user_id, group_created_by_user_name, group_created_by_user_email, group_created_by_ip, group_created_by_hostname, group_created_by_user_agent, group_created_datetime, group_created_date_saying, group_updated_by_user_id, group_updated_by_user_name, group_updated_by_user_email, group_updated_by_ip, group_updated_by_hostname, group_updated_by_user_agent, group_updated_datetime, group_updated_date_saying FROM $t_users_groups_index WHERE group_id=$group_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_group_id, $get_current_group_name, $get_current_group_language, $get_current_group_description, $get_current_group_privacy, $get_current_group_key, $get_current_group_logo_path, $get_current_group_logo_file, $get_current_group_created_by_user_id, $get_current_group_created_by_user_name, $get_current_group_created_by_user_email, $get_current_group_created_by_ip, $get_current_group_created_by_hostname, $get_current_group_created_by_user_agent, $get_current_group_created_datetime, $get_current_group_created_date_saying, $get_current_group_updated_by_user_id, $get_current_group_updated_by_user_name, $get_current_group_updated_by_user_email, $get_current_group_updated_by_ip, $get_current_group_updated_by_hostname, $get_current_group_updated_by_user_agent, $get_current_group_updated_datetime, $get_current_group_updated_date_saying) = $row;
	if($get_current_group_id == ""){
		echo"Group_not_found";
		exit;
	}
	/*- Find member ------------------------------------------------------------------------- */
	$member_id_mysql = quote_smart($link, $member_id);
	$query = "SELECT member_id, member_group_id, member_user_id, member_user_name, member_user_email, member_user_photo_destination, member_user_photo_thumb_50, member_status, member_invited, member_user_accepted_invitation, member_accepted_by_moderator, member_joined_datetime, member_joined_date_saying FROM $t_users_groups_members WHERE member_id=$member_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_member_id, $get_current_member_group_id, $get_current_member_user_id, $get_current_member_user_name, $get_current_member_user_email, $get_current_member_user_photo_destination, $get_current_member_user_photo_thumb_50, $get_current_member_status, $get_current_member_invited, $get_current_member_user_accepted_invitation, $get_current_member_accepted_by_moderator, $get_current_member_joined_datetime, $get_current_member_joined_date_saying) = $row;
	if($get_current_member_id == ""){
		echo"member not found";
		exit;
	}

	if($process == "1"){

		// Dates
		$datetime = date("Y-m-d H:i:s");
		$date_saying = date("j M Y");

		// Find updated user information
		$query = "SELECT user_id, user_email, user_name FROM $t_users WHERE user_id=$get_current_member_user_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_user_id, $get_user_email, $get_user_name) = $row;
		if($get_user_id == ""){
			$url = "index.php?open=users&page=groups&action=view_group_member&group_id=$get_current_group_id&member_id=$get_current_member_id&l=$l&editor_language=$inp_language&ft=error&fm=user_not_found";
			header("Location: $url");
			exit;
		}

		$query = "SELECT photo_id, photo_destination, photo_thumb_50 FROM $t_users_profile_photo WHERE photo_user_id=$get_user_id AND photo_profile_image=1";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_photo_id, $get_photo_destination, $get_photo_thumb_50) = $row;

		$inp_user_id_mysql = quote_smart($link, $get_user_id);
		$inp_user_email_mysql = quote_smart($link, $get_user_email);
		$inp_user_name_mysql = quote_smart($link, $get_user_name);
		$inp_my_photo_destination_mysql = quote_smart($link, $get_photo_destination);
		$inp_my_photo_thumb_50_mysql = quote_smart($link, $get_photo_thumb_50);

		// Status
		$inp_status = $_POST['inp_status'];
		$inp_status = output_html($inp_status);
		$inp_status_mysql = quote_smart($link, $inp_status);


		// Update member
		mysqli_query($link, "UPDATE $t_users_groups_members SET 
					member_user_name=$inp_user_name_mysql, 
					member_user_email=$inp_user_email_mysql, 
					member_user_photo_destination=$inp_my_photo_destination_mysql, 
					member_user_photo_thumb_50=$inp_my_photo_thumb_50_mysql, 
					member_status=$inp_status_mysql 
					WHERE member_id=$get_current_member_id") or die(mysqli_error($link));
		

		// Header
		$url = "index.php?open=users&page=groups&action=open_group&group_id=$get_current_group_id&l=$l&editor_language=$editor_language&ft=success&fm=member_updated";
		header("Location: $url");
		exit;
	}


	echo"

	<h1>$get_current_group_name</h1>

	<!-- Where am I ? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=users&amp;page=groups&amp;l=$l&amp;editor_language=$editor_language\">Groups</a>
		&gt;
		<a href=\"index.php?open=users&amp;page=groups&amp;action=open_group&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\">$get_current_group_name</a>
		&gt;
		<a href=\"index.php?open=users&amp;page=groups&amp;action=remove_group_member&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\">Remove member from group</a>
		</p>
	<!-- //Where am I ? -->

	<!-- Feedback -->
	";
	if($ft != "" && $fm != ""){
		$fm = ucfirst($fm);
		echo"<div class=\"$ft\"><p>$fm</p></div>";
	}
	echo"
	<!-- //Feedback -->

	<!-- Actions -->
		<p>
		<a href=\"index.php?open=users&amp;page=groups&amp;action=add_member_to_group&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn_default\">Add member</a>
		<a href=\"index.php?open=users&amp;page=groups&amp;action=edit_group&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn_default\">Edit</a>
		<a href=\"index.php?open=users&amp;page=groups&amp;action=delete_group&amp;group_id=$get_current_group_id&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn_default\">Delete</a>
		</p>
	<!-- //Actions -->

	<!-- Edit group member form -->
		<h2>Edit group member $get_current_member_user_name</h2>
		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_username\"]').focus();
			});
			</script>
		<!-- //Focus -->
		
		<form method=\"POST\" action=\"index.php?open=users&amp;page=groups&amp;action=view_group_member&amp;group_id=$get_current_group_id&amp;member_id=$get_current_member_id&amp;process=1&amp;editor_language=$editor_language&amp;l=$l\" enctype=\"multipart/form-data\">



		<p><b>Status:</b><br />
		<select name=\"inp_status\">
			<option value=\"admin\""; if($get_current_member_status == "admin"){ echo" selected=\"selected\""; } echo">Admin</option>
			<option value=\"moderator\""; if($get_current_member_status == "moderator"){ echo" selected=\"selected\""; } echo">Moderator</option>
			<option value=\"member\""; if($get_current_member_status == "member"){ echo" selected=\"selected\""; } echo">Member</option>
		</select>
		</p>

		<p>
		<input type=\"submit\" value=\"Save changes\" class=\"btn_default\" />
		</p>

		</form>
	<!-- //Edit group member form -->
	";
} // view_group_member
?>