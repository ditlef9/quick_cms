<?php 
/**
*
* File: howto/delete_page.php
* Version 1.0
* Date 14:55 30.06.2019
* Copyright (c) 2019 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "1";
$pageAuthorUserIdSav  = "1";

/*- Root dir --------------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config --------------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Translations ----------------------------------------------------------------------------- */
include("$root/_admin/_translations/site/$l/knowledge/ts_new_page.php");

/*- Functions ------------------------------------------------------------------------- */
include("$root/_admin/_functions/encode_national_letters.php");
include("$root/_admin/_functions/decode_national_letters.php");

/*- Tables ------------------------------------------------------------------------------------ */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";

/*- Variables -------------------------------------------------------------------------------- */
if (isset($_GET['space_id'])) {
	$space_id = $_GET['space_id'];
	$space_id = stripslashes(strip_tags($space_id));
}
else{
	$space_id = "";
}
$space_id_mysql = quote_smart($link, $space_id);
if (isset($_GET['page_id'])) {
	$page_id = $_GET['page_id'];
	$page_id = stripslashes(strip_tags($page_id));
}
else{
	$page_id = "";
}
$page_id_mysql = quote_smart($link, $page_id);

// Find space
$query = "SELECT space_id, space_title, space_title_clean, space_description, space_image, space_is_archived, space_unique_hits, space_unique_hits_ip_block, space_unique_hits_user_id_block, space_created_datetime, space_created_date_saying, space_created_user_id, space_created_user_alias, space_created_user_image, space_updated_datetime, space_updated_date_saying, space_updated_user_id, space_updated_user_alias, space_updated_user_image FROM $t_knowledge_spaces_index WHERE space_id=$space_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_space_id, $get_current_space_title, $get_current_space_title_clean, $get_current_space_description, $get_current_space_image, $get_current_space_is_archived, $get_current_space_unique_hits, $get_current_space_unique_hits_ip_block, $get_current_space_unique_hits_user_id_block, $get_current_space_created_datetime, $get_current_space_created_date_saying, $get_current_space_created_user_id, $get_current_space_created_user_alias, $get_current_space_created_user_image, $get_current_space_updated_datetime, $get_current_space_updated_date_saying, $get_current_space_updated_user_id, $get_current_space_updated_user_alias, $get_current_space_updated_user_image) = $row;

if($get_current_space_id == ""){
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "404 server error";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");

	echo"
	<h1>Server error 404</h1>

	<p>Space not found.</p>
	";
}
else{
	// Find page
	$query = "SELECT page_id, page_space_id, page_title, page_title_clean, page_description, page_text, page_parent_id, page_weight, page_allow_comments, page_no_of_comments, page_unique_hits, page_unique_hits_ip_block, page_unique_hits_user_id_block, page_created_datetime, page_created_date_saying, page_created_user_id, page_created_user_alias, page_created_user_image, page_updated_datetime, page_updated_date_saying, page_updated_user_id, page_updated_user_alias, page_updated_user_image, page_version FROM $t_knowledge_pages_index WHERE page_id=$page_id_mysql AND page_space_id=$get_current_space_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_page_id, $get_current_page_space_id, $get_current_page_title, $get_current_page_title_clean, $get_current_page_description, $get_current_page_text, $get_current_page_parent_id, $get_current_page_weight, $get_current_page_allow_comments, $get_current_page_no_of_comments, $get_current_page_unique_hits, $get_current_page_unique_hits_ip_block, $get_current_page_unique_hits_user_id_block, $get_current_page_created_datetime, $get_current_page_created_date_saying, $get_current_page_created_user_id, $get_current_page_created_user_alias, $get_current_page_created_user_image, $get_current_page_updated_datetime, $get_current_page_updated_date_saying, $get_current_page_updated_user_id, $get_current_page_updated_user_alias, $get_current_page_updated_user_image, $get_current_page_version) = $row;

	if($get_current_page_id == ""){
		/*- Headers ---------------------------------------------------------------------------------- */
		$website_title = "404 server error";
		if(file_exists("./favicon.ico")){ $root = "."; }
		elseif(file_exists("../favicon.ico")){ $root = ".."; }
		elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
		elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
		include("$root/_webdesign/header.php");

		echo"
		<h1>Server error 404</h1>

		<p>Page not found.</p>
		";
	}
	else{

		// Check if I have access
		if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
			$my_user_id = $_SESSION['user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);

			// Access?
			$query = "SELECT member_id, member_space_id, member_rank, member_user_id, member_user_alias, member_user_image, member_user_about, member_added_datetime, member_added_date_saying, member_added_by_user_id, member_added_by_user_alias, member_added_by_user_image FROM $t_knowledge_spaces_members WHERE member_space_id=$space_id_mysql AND member_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_member_id, $get_member_space_id, $get_member_rank, $get_member_user_id, $get_member_user_alias, $get_member_user_image, $get_member_user_about, $get_member_added_datetime, $get_member_added_date_saying, $get_member_added_by_user_id, $get_member_added_by_user_alias, $get_member_added_by_user_image) = $row;
			if($get_member_id == ""){
				echo"
				<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Server error 403</h1>
		
				<meta http-equiv=\"refresh\" content=\"1;url=view_page.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;l=$l&amp;ft=warning&amp;fm=your_not_a_member_of_this_space\">
				";
			}
			else{
				
				// Rank has to be admin, moderator or editor to delete pages
				if($get_member_rank == "admin" OR $get_member_rank == "moderator" OR $get_member_rank == "editor"){
				
					/*- Headers ---------------------------------------------------------------------------------- */
					$website_title = "$get_current_space_title - $get_current_page_title - $l_delete_page";
					if(file_exists("./favicon.ico")){ $root = "."; }
					elseif(file_exists("../favicon.ico")){ $root = ".."; }
					elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
					elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
					include("$root/_webdesign/header.php");
		
					if($process == "1"){
						


						// Delete page and tags
						$result = mysqli_query($link, "DELETE FROM $t_knowledge_pages_index WHERE page_id=$page_id_mysql AND page_space_id=$get_current_space_id");
						$result = mysqli_query($link, "DELETE FROM $t_knowledge_pages_tags WHERE tag_page_id='$get_current_page_id'");


						// Create history
						$datetime = date("Y-m-d H:i:s");
						$date_saying = date("j M Y");
	
						// Me
						$query = "SELECT user_id, user_email, user_name, user_alias, user_language, user_last_online, user_rank, user_login_tries FROM $t_users WHERE user_id=$my_user_id_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_language, $get_my_user_last_online, $get_my_user_rank, $get_my_user_login_tries) = $row;
	
						// Get my photo
						$query = "SELECT photo_id, photo_destination, photo_thumb_40 FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_40) = $row;

						$inp_my_user_alias_mysql = quote_smart($link, $get_my_user_alias);
						$inp_my_user_email_mysql = quote_smart($link, $get_my_user_email);
						$inp_my_user_image_mysql = quote_smart($link, $get_my_photo_destination);

						$my_ip = $_SERVER['REMOTE_ADDR'];
						$my_ip = output_html($inp_ip);
						$my_ip_mysql = quote_smart($link, $inp_ip);

						$my_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
						$my_hostname = output_html($inp_hostname);
						$my_hostname_mysql = quote_smart($link, $inp_hostname);

						$my_user_agent = $_SERVER['HTTP_USER_AGENT'];
						$my_user_agent = output_html($user_agent);
						$my_user_agent_mysql = quote_smart($link, $user_agent);

						$year = date("Y");
						$inp_can_be_deleted_year = $year+2;
						$inp_can_be_deleted_year_mysql = quote_smart($link, $inp_can_be_deleted_year);

						// page ver
						$inp_page_version = $get_current_page_version+1;

						// General
						$inp_title_mysql = quote_smart($link, $get_current_page_title);
						$inp_title_clean_mysql = quote_smart($link, $get_current_page_title_clean);
						$inp_description_mysql = quote_smart($link, $get_current_page_description);

						mysqli_query($link, "INSERT INTO $t_knowledge_pages_edit_history
						(history_id, history_page_id, history_page_version, history_page_is_deleted, history_page_title, history_page_title_clean, 
						history_page_description, history_page_text, history_page_parent_id, history_weight, history_allow_comments, 	
						history_page_no_of_comments, history_page_updated_datetime, history_page_updated_date_saying, history_page_updated_user_id, history_page_updated_user_alias, 
						history_page_updated_user_image, history_page_ip, history_page_hostname, history_page_user_agent, history_can_be_deleted_year) 
						VALUES 
						(NULL, $get_current_page_id, $inp_page_version, '1', $inp_title_mysql, $inp_title_clean_mysql, 
						$inp_description_mysql, '', $get_current_page_parent_id, $get_current_page_weight, $get_current_page_allow_comments, 
						$get_current_page_no_of_comments, '$datetime', '$date_saying', $get_my_user_id, $inp_my_user_alias_mysql, 
						$inp_my_user_image_mysql, $my_ip_mysql, $my_hostname_mysql, $my_user_agent_mysql, $inp_can_be_deleted_year_mysql)")
						or die(mysqli_error($link));
						
						$sql = "UPDATE $t_knowledge_pages_edit_history SET history_page_text=? WHERE history_page_id=$get_current_page_id AND history_page_version=$inp_page_version";
						$stmt = $link->prepare($sql);
						$stmt->bind_param("s", $get_current_page_text);
						$stmt->execute();
						if ($stmt->errno) {
							echo "FAILURE!!! " . $stmt->error; die;
						}

						
						// Search engine
						$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name='knowledge' AND index_reference_name='page_id' AND index_reference_id=$get_current_page_id";
						$result_exists = mysqli_query($link, $query_exists);
						$row_exists = mysqli_fetch_row($result_exists);
						list($get_index_id) = $row_exists;
						if($get_index_id != ""){
							$result = mysqli_query($link, "DELETE FROM $t_search_engine_index WHERE index_id=$get_index_id") or die(mysqli_error($link));
						}

						// Go to parent if any, else go to space
						if($get_current_page_parent_id != ""){
							$url = "view_page.php?space_id=$space_id&page_id=$get_current_page_parent_id&l=$l&ft=success&fm=page_deleted";
						}
						else{
							$url = "open_space.php?space_id=$space_id&l=$l&ft=success&fm=page_deleted";
						}
						header("Location: $url");
						exit;

					} // process
	
					echo"
					<h1>$l_delete_page</h1>
	
					<!-- Feedback -->
						";
						if($ft != ""){
							if($fm == "changes_saved"){
								$fm = "$l_changes_saved";
							}
							else{
								$fm = ucfirst($fm);
								$fm = str_replace("_", " ", $fm);
							}
							echo"<div class=\"$ft\"><span>$fm</span></div>";
						}
						echo"	
					<!-- //Feedback -->

					<!-- Delete page Form -->
						<p>
						$l_are_you_sure_you_want_to_delete_the_page
						</p>

						<p>
						<a href=\"delete_page.php?space_id=$space_id&amp;page_id=$get_current_page_id&amp;l=$l&amp;process=1\" class=\"btn_danger\">$l_delete</a>
						<a href=\"view_page.php?space_id=$space_id&amp;page_id=$get_current_page_id&amp;l=$l\" class=\"btn_default\">$l_cancel</a>
						</p>
					<!-- //Edit page Form -->
					";
				} // member can delete
				else{
					echo"
					<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Server error 403</h1>
		
					<meta http-equiv=\"refresh\" content=\"1;url=view_page.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;l=$l&amp;ft=warning&amp;fm=your_user_cant_edit_pages\">

					";
				}
			} // is member of space
		} // logged in
		else{
			echo"
			<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" /> New page - Please log in...</h1>
		
			<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=$root/knowledge/move_page_down.php?space_id=$get_current_page_space_id" . "amp;page_id=$get_current_page_id\">
			";
			
		}
	} // page found
} // space found


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/$webdesignSav/footer.php");
?>