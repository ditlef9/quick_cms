<?php
/**
*
* File: rebus/team_edit.php
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
include("$root/_admin/_data/logo.php");
include("$root/_admin/_data/config/user_system.php");

/*- Tables ---------------------------------------------------------------------------- */
include("_tables_rebus.php");


/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);

if(isset($_GET['team_id'])) {
	$team_id = $_GET['team_id'];
	$team_id = output_html($team_id);
	if(!(is_numeric($team_id))){
		echo"team id not numeric";
		die;
	}
}
else{
	echo"Missing teamid";
	die;
}

$tabindex = 0;


/*- Translation ------------------------------------------------------------------------------- */


// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);

	/*- Find team ------------------------------------------------------------------------- */
	$team_id_mysql = quote_smart($link, $team_id);
	$query = "SELECT team_id, team_name, team_language, team_description, team_privacy, team_key, team_group_id, team_group_name, team_logo_path, team_logo_file, team_color, team_created_by_user_id, team_created_by_user_name, team_created_by_user_email, team_created_by_ip, team_created_by_hostname, team_created_by_user_agent, team_created_datetime, team_created_date_saying, team_updated_by_user_id, team_updated_by_user_name, team_updated_by_user_email, team_updated_by_ip, team_updated_by_hostname, team_updated_by_user_agent, team_updated_datetime, team_updated_date_saying FROM $t_rebus_teams_index WHERE team_id=$team_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_team_id, $get_current_team_name, $get_current_team_language, $get_current_team_description, $get_current_team_privacy, $get_current_team_key, $get_current_team_group_id, $get_current_team_group_name, $get_current_team_logo_path, $get_current_team_logo_file, $get_current_team_color, $get_current_team_created_by_user_id, $get_current_team_created_by_user_name, $get_current_team_created_by_user_email, $get_current_team_created_by_ip, $get_current_team_created_by_hostname, $get_current_team_created_by_user_agent, $get_current_team_created_datetime, $get_current_team_created_date_saying, $get_current_team_updated_by_user_id, $get_current_team_updated_by_user_name, $get_current_team_updated_by_user_email, $get_current_team_updated_by_ip, $get_current_team_updated_by_hostname, $get_current_team_updated_by_user_agent, $get_current_team_updated_datetime, $get_current_team_updated_date_saying) = $row;
	if($get_current_team_id == ""){
		$url = "teams.php?ft=error&fm=team_not_found&l=$l";
		header("Location: $url");
		exit;
	}

	/*- Check that I am a member of this team --------------------------------------------- */
	$query = "SELECT member_id, member_team_id, member_status, member_user_id, member_user_name, member_user_email, member_user_photo_destination, member_user_photo_thumb_50, member_joined_datetime, member_joined_date_saying FROM $t_rebus_teams_members WHERE member_team_id=$get_current_team_id AND member_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_my_member_id, $get_my_member_team_id, $get_my_member_status, $get_my_member_user_id, $get_my_member_user_name, $get_my_member_user_email, $get_my_member_user_photo_destination, $get_my_member_user_photo_thumb_50, $get_my_member_joined_datetime, $get_my_member_joined_date_saying) = $row;
	if($get_my_member_id == ""){
		$url = "teams.php?ft=error&fm=your_not_a_member_of_that_team&l=$l";
		header("Location: $url");
		exit;
	}

	// Access
	if($get_my_member_status != "admin" && $get_my_member_status != "moderator"){
		$url = "teams.php?ft=error&fm=access_denied&l=$l";
		header("Location: $url");
		exit;
	}

	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$get_current_team_name - $l_teams";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");

	if($process == "1"){
		$inp_description = $_POST['inp_description'];
		$inp_description = output_html($inp_description);
		$inp_description_mysql = quote_smart($link, $inp_description);

		$inp_privacy = $_POST['inp_privacy'];
		$inp_privacy = output_html($inp_privacy);
		$inp_privacy_mysql = quote_smart($link, $inp_privacy);

		// team is a part of group
		$inp_group_id = $_POST['inp_group_id'];
		$inp_group_id = output_html($inp_group_id);
		$inp_group_id_mysql = quote_smart($link, $inp_group_id);

		$inp_group_name = "";

		if($inp_group_id != "0"){
			// Find group
			$query = "SELECT group_id, group_name FROM $t_rebus_groups_index WHERE group_id=$inp_group_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_group_id, $get_group_name) = $row;
			
			// Check that I am a member of that group
			$query = "SELECT member_id FROM $t_rebus_groups_members WHERE member_group_id=$get_group_id AND member_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_member_id) = $row;

			if($get_member_id != ""){
				$inp_group_id = "$get_group_id";
				$inp_group_id = output_html($inp_group_id);
				$inp_group_id_mysql = quote_smart($link, $inp_group_id);

				$inp_group_name = output_html($get_group_name);
			}
		}
		$inp_group_name_mysql = quote_smart($link, $inp_group_name);
		
		mysqli_query($link, "UPDATE $t_rebus_teams_index SET
				team_group_id=$inp_group_id_mysql, 
				team_group_name=$inp_group_name_mysql, 
				team_description=$inp_description_mysql, 
				team_privacy=$inp_privacy_mysql
						WHERE team_id=$get_current_team_id") or die(mysqli_error($link));


		// Directory for storing
		if(!(is_dir("../_uploads"))){
			mkdir("../_uploads");
		}
		if(!(is_dir("../_uploads/rebus"))){
			mkdir("../_uploads/rebus");
		}
		if(!(is_dir("../_uploads/rebus/teams"))){
			mkdir("../_uploads/rebus/teams");
		}
		if(!(is_dir("../_uploads/rebus/teams/$get_current_team_id"))){
			mkdir("../_uploads/rebus/teams/$get_current_team_id");
		}
	
		/*- Image upload ------------------------------------------------------------------------------------------ */
		$name = stripslashes($_FILES['inp_logo']['name']);
		$extension = get_extension($name);
		$extension = strtolower($extension);

		$ft_logo = "";
		$fm_logo = "";
		if($name){
			if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
				$ft_logo = "warning";
				$fm_logo = "unknown_file_extension";
			}
			else{
				$new_path = "../_uploads/rebus/teams/$get_current_team_id/";
				$new_name = date("ymdhis");
				$uploaded_file = $new_path . $new_name . "." . $extension;

				// Upload file
				if (move_uploaded_file($_FILES['inp_logo']['tmp_name'], $uploaded_file)) {
					// Get image size
					$file_size = filesize($uploaded_file);
						
					// Check with and height
					list($width,$height) = getimagesize($uploaded_file);
	
					if($width == "" OR $height == ""){
						$ft_logo = "warning";
						$fm_logo = "getimagesize_failed";
						unlink($uploaded_file);
					}
					else{
						// Resize to 256x256
						$uploaded_file_new = $uploaded_file;
						if($width != "256" OR $height != "256"){
							resize_crop_image(256, 256, $uploaded_file, $uploaded_file_new, $quality = 80);
						}
						
						// MySQL
						$inp_path = "_uploads/rebus/teams/$get_current_team_id";
						$inp_path = output_html($inp_path);
						$inp_path_mysql = quote_smart($link, $inp_path);


						$inp_file_mysql = quote_smart($link, $new_name . "." . $extension);


						mysqli_query($link, "UPDATE $t_rebus_teams_index SET
									team_logo_path=$inp_path_mysql, 
									team_logo_file=$inp_file_mysql
									WHERE team_id=$get_current_team_id") or die(mysqli_error($link));

						// Delete old logo
						if(file_exists("../$get_current_team_logo_path/$get_current_team_logo_file") && $get_current_team_logo_file != ""){
							unlink("../$get_current_team_logo_path/$get_current_team_logo_file");
						}

						
						$ft_logo = "success";
						$fm_logo = "logo_uploaded";
					}
 
				} // move_uploaded_file
				else{
					$ft_logo = "error";
					switch ($_FILES['inp_food_image']['error']) {
						case UPLOAD_ERR_OK:
           						$fm_logo = "There is no error, the file uploaded with success.";
							break;
						case UPLOAD_ERR_NO_FILE:
           						// $fm_logo = "no_file_uploaded";
							break;
						case UPLOAD_ERR_INI_SIZE:
           						$fm_logo = "to_big_size_in_configuration";
							break;
						case UPLOAD_ERR_FORM_SIZE:
           						$fm_logo = "to_big_size_in_form";
							break;
						default:
           						$fm_logo = "unknown_error";
							break;
					}	
				}
			}
		} // name



		$url = "team_edit.php?team_id=$get_current_team_id&ft=success&fm=changes_saved&l=$l";
		if($fm_logo != ""){
			$url = $url . "&fm_logo=$fm_logo&ft_logo=$ft_logo";
		}
		header("Location: $url");
		exit;
	} // process

	echo"
	<!-- Headline -->
		<h1>$get_current_team_name</h1>
	<!-- //Headline -->

	<!-- Where am I ? -->
		<p><b>$l_you_are_here:</b><br />
		<a href=\"index.php?l=$l\">$l_rebus</a>
		&gt;
		<a href=\"teams.php?l=$l\">$l_teams</a>
		&gt;
		<a href=\"team_open.php?team_id=$get_current_team_id&amp;l=$l\">$get_current_team_name</a>
		&gt;
		<a href=\"team_edit.php?team_id=$get_current_team_id&amp;l=$l\">$l_edit_team</a>
		</p>
	<!-- //Where am I ? -->

	<!-- Feedback -->
		";
		if($ft != "" && $fm != ""){
			$fm = ucfirst($fm);
			$fm = str_replace("_", " ", $fm);
			echo"<div class=\"$ft\"><p>$fm</p></div>";
		}

		if(isset($_GET['ft_logo']) && isset($_GET['fm_logo'])) {
			$ft_logo = $_GET['ft_logo'];
			$ft_logo = output_html($ft_logo);

			$fm_logo = $_GET['fm_logo'];
			$fm_logo = output_html($fm_logo);
			$fm_logo = str_replace("_", " ", $fm_logo);
			$fm_logo = ucfirst($fm_logo);
			echo"<div class=\"$ft_logo\"><p>$fm_logo</p></div>";
		}
		echo"
	<!-- //Feedback -->

	<!-- Edit team form-->
		<form method=\"post\" action=\"team_edit.php?team_id=$get_current_team_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

		<p><b>$l_description:</b><br />
		<textarea name=\"inp_description\" rows=\"6\" cols=\"50\" style=\"width:99%;\">";
		$get_current_team_description = str_replace("<br />", "\n", $get_current_team_description);
		echo"$get_current_team_description</textarea>
		</p>

		<p><b>$l_privacy:</b><br />
		<input type=\"radio\" name=\"inp_privacy\" value=\"public\""; if($get_current_team_privacy == "public"){ echo" checked=\"checked\""; } echo" /> $l_public
		&nbsp;
		<input type=\"radio\" name=\"inp_privacy\" value=\"private\""; if($get_current_team_privacy == "private"){ echo" checked=\"checked\""; } echo" /> $l_private
		</p>


		<p><b>$l_logo (256x256 png):</b><br />";
		if(file_exists("../$get_current_team_logo_path/$get_current_team_logo_file") && $get_current_team_logo_file != ""){
			echo"<img src=\"../$get_current_team_logo_path/$get_current_team_logo_file\" alt=\"$get_current_team_logo_file\" /><br />\n";
		}
		echo"
		<input type=\"file\" name=\"inp_logo\" /> 
		</p>

		<p><b>$l_team_is_a_part_of_group:</b>
		(<a href=\"new_group.php?l=$l\">$l_create_group</a>)<br />
		<select name=\"inp_group_id\">
			<option value=\"0\""; if($get_current_team_group_id == "0"){ echo" selected=\"selected\""; } echo">$l_none</selected>";
			$query = "SELECT member_id, member_group_id, group_name FROM $t_rebus_groups_members JOIN $t_rebus_groups_index ON $t_rebus_groups_members.member_group_id=$t_rebus_groups_index.group_id WHERE member_user_id=$my_user_id_mysql ORDER BY $t_rebus_groups_index.group_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_member_id, $get_member_group_id, $get_group_name) = $row;
				echo"			<option value=\"$get_member_group_id\""; if($get_current_team_group_id == "$get_member_group_id"){ echo" selected=\"selected\""; } echo">$get_group_name</selected>\n";
			}
			echo"
		</select></p>

		<p><input type=\"submit\" value=\"$l_save_changes\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
		</p>
		
		</form>
	<!-- //Edit team form -->
	";
}
else{
	echo"
	<h1>
	<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=rebus/teams.php\">

	<p>Please log in...</p>
	";
}

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>