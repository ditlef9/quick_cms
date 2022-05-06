<?php
/**
*
* File: rebus/new_group.php
* Version 1.0.0.
* Date 09:50 01.07.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
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
include("_tables_rebus.php");


/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);
$tabindex = 0;

/*- Translation ------------------------------------------------------------------------ */


/*- Headers ---------------------------------------------------------------------------- */
$website_title = "$l_new_group - $l_groups - $l_rebus";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");


// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);


	if($process == "1"){
		$inp_name = $_POST['inp_name'];
		$inp_name = output_html($inp_name);
		$inp_name_mysql = quote_smart($link, $inp_name);
		if($inp_name == ""){
			$url = "new_group.php?l=$l&ft=error&fm=missing_name";
			header("Location: $url");
			exit;
		}
			
		$l_mysql = quote_smart($link, $l);

		$inp_privacy = $_POST['inp_privacy'];
		$inp_privacy = output_html($inp_privacy);
		$inp_privacy_mysql = quote_smart($link, $inp_privacy);

		// Key
		$characters = '023456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ';
    		$charactersLength = strlen($characters);
    		$inp_key = '';
    		for ($i = 0; $i < 6; $i++) {
        		$inp_key .= $characters[rand(0, $charactersLength - 1)];
    		}
		$inp_key_mysql = quote_smart($link, $inp_key);


		// Me
		$query = "SELECT user_id, user_email, user_name, user_language, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_language, $get_my_user_rank) = $row;
		
		$inp_my_user_name_mysql = quote_smart($link, $get_my_user_name);
		$inp_my_user_email_mysql = quote_smart($link, $get_my_user_email);

		// Profile photo
		$query = "SELECT photo_id, photo_destination, photo_thumb_50 FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_50) = $row;

		$inp_my_photo_destination_mysql = quote_smart($link, $get_my_photo_destination);
		$inp_my_photo_thumb_50_mysql = quote_smart($link, $get_my_photo_thumb_50);

		// Ip 
		$my_ip = $_SERVER['REMOTE_ADDR'];
		$my_ip = output_html($my_ip);
		$my_ip_mysql = quote_smart($link, $my_ip);

		$my_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$my_hostname = output_html($my_hostname);
		$my_hostname_mysql = quote_smart($link, $my_hostname);

		$my_user_agent = $_SERVER['HTTP_USER_AGENT'];
		$my_user_agent = output_html($my_user_agent);
		$my_user_agent_mysql = quote_smart($link, $my_user_agent);

		// Dates
		$datetime = date("Y-m-d H:i:s");
		$date_saying = date("j M Y");

		// Check if group exists
		$query = "SELECT group_id FROM $t_rebus_groups_index WHERE group_name=$inp_name_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_group_id) = $row;
		if($get_group_id != ""){
			$url = "group_new.php?l=$l&ft=error&fm=there_is_already_a_group_with_that_name_(" . $inp_name . ")";
			header("Location: $url");
			exit;
		}

		// Create group
		mysqli_query($link, "INSERT INTO $t_rebus_groups_index
		(group_id, group_name, group_language, group_description, group_privacy, 
		group_key, group_created_by_user_id, group_created_by_user_name, group_created_by_user_email, group_created_by_ip, 
		group_created_by_hostname, group_created_by_user_agent, group_created_datetime, group_created_date_saying) 
		VALUES 
		(NULL, $inp_name_mysql, $l_mysql, '', $inp_privacy_mysql, 
		$inp_key_mysql, $get_my_user_id, $inp_my_user_name_mysql, $inp_my_user_email_mysql, $my_ip_mysql, 
		$my_hostname_mysql, $my_user_agent_mysql, '$datetime', '$date_saying')")
		or die(mysqli_error($link));

		// Get id
		$query = "SELECT group_id FROM $t_rebus_groups_index WHERE group_created_by_user_id=$get_my_user_id AND group_created_datetime='$datetime'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_group_id) = $row;

		// Insert me as member
		mysqli_query($link, "INSERT INTO $t_rebus_groups_members
		(member_id, member_group_id, member_user_id, member_user_name, member_user_email, 
		member_user_photo_destination, member_user_photo_thumb_50, member_status, member_invited, member_user_accepted_invitation, 
		member_accepted_by_moderator, member_joined_datetime, member_joined_date_saying) 
		VALUES 
		(NULL, $get_current_group_id, $get_my_user_id, $inp_my_user_name_mysql, $inp_my_user_email_mysql, 
		$inp_my_photo_destination_mysql, $inp_my_photo_thumb_50_mysql, 'admin', 0, 1, 
		1, '$datetime', '$date_saying')")
		or die(mysqli_error($link));



		// Open group
		$url = "group_open.php?group_id=$get_current_group_id&l=$l&ft=success&fm=group_created";
		header("Location: $url");
		exit;


	} // process

	echo"
	<!-- Headline -->
		<h1>$l_new_group</h1>
	<!-- //Headline -->

	<!-- Where am I ? -->
		<p><b>$l_you_are_here:</b><br />
		<a href=\"index.php?l=$l\">$l_rebus</a>
		&gt;
		<a href=\"groups.php?l=$l\">$l_groups</a>
		&gt;
		<a href=\"group_new.php?l=$l\">$l_new_group</a>
		</p>
	<!-- //Where am I ? -->

		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				$fm = ucfirst($fm);
				$fm = str_replace("_", " ", $fm);
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->

	<!-- Focus -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_name\"]').focus();
		});
		</script>
	<!-- //Focus -->

	<!-- New group form -->
		<form method=\"post\" action=\"group_new.php?l=$l&amp;process=1\" enctype=\"multipart/form-data\">

		<p><b>$l_name:</b><br />
		<input type=\"text\" name=\"inp_name\" value=\"\" size=\"25\" style=\"width: 99%;\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
		</p>

		<p><b>$l_privacy:</b><br />
		<input type=\"radio\" name=\"inp_privacy\" value=\"public\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /> $l_public &nbsp;
		<input type=\"radio\" name=\"inp_privacy\" value=\"private\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" checked=\"checked\" /> $l_private
		</p>

		<p><input type=\"submit\" value=\"$l_create_group\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /></p>
		
		</form>
	<!-- //New group form -->
	";
}
else{
	echo"
	<h1>
	<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=rebus/group_new.php\">

	<p>Please log in...</p>
	";
}

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>