<?php 
/**
*
* File: howto/move_page_down.php
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
	$query = "SELECT page_id, page_space_id, page_title, page_title_clean, page_description, page_text, page_parent_id, page_weight, page_allow_comments, page_no_of_comments, page_unique_hits, page_unique_hits_ip_block, page_unique_hits_user_id_block, page_created_datetime, page_created_date_saying, page_created_user_id, page_created_user_alias, page_created_user_image, page_updated_datetime, page_updated_date_saying, page_updated_user_id, page_updated_user_alias, page_updated_user_image FROM $t_knowledge_pages_index WHERE page_id=$page_id_mysql AND page_space_id=$get_current_space_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_page_id, $get_current_page_space_id, $get_current_page_title, $get_current_page_title_clean, $get_current_page_description, $get_current_page_text, $get_current_page_parent_id, $get_current_page_weight, $get_current_page_allow_comments, $get_current_page_no_of_comments, $get_current_page_unique_hits, $get_current_page_unique_hits_ip_block, $get_current_page_unique_hits_user_id_block, $get_current_page_created_datetime, $get_current_page_created_date_saying, $get_current_page_created_user_id, $get_current_page_created_user_alias, $get_current_page_created_user_image, $get_current_page_updated_datetime, $get_current_page_updated_date_saying, $get_current_page_updated_user_id, $get_current_page_updated_user_alias, $get_current_page_updated_user_image) = $row;

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
				$url = "view_page.php?space_id=$get_current_page_space_id&page_id=$get_current_page_id&l=$l&ft=warning&fm=your_not_a_member_of_this_space";
				header("Location: $url");
				exit;
			}
			else{
				
				// Rank has to be admin, moderator or editor to edit pages
				if($get_member_rank == "admin" OR $get_member_rank == "moderator" OR $get_member_rank == "editor"){
					/*- Headers ---------------------------------------------------------------------------------- */
					$website_title = "$get_current_space_title";
					if(file_exists("./favicon.ico")){ $root = "."; }
					elseif(file_exists("../favicon.ico")){ $root = ".."; }
					elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
					elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
					include("$root/_webdesign/header.php");
		
					// Find switch
					$page_switch_page_weight = $get_current_page_weight+1;
	
					$query = "SELECT page_id, page_weight FROM $t_knowledge_pages_index WHERE page_weight=$page_switch_page_weight AND page_space_id=$get_current_space_id AND page_parent_id=$get_current_page_parent_id";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_switch_page_id, $get_switch_page_weight) = $row;
	
					if($get_switch_page_id == ""){
						$url = "view_page.php?space_id=$get_current_page_space_id&page_id=$get_current_page_id&l=$l&ft=warning&fm=cant_move_position";
						header("Location: $url");
						exit;
					}
					else{
						// Dates
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
						$inp_my_user_image_mysql = quote_smart($link, $get_my_photo_destination);


						// Update current
						$result = mysqli_query($link, "UPDATE $t_knowledge_pages_index SET page_weight=$get_switch_page_weight, 
							page_updated_datetime='$datetime', 
							page_updated_date_saying='$date_saying', 
							page_updated_user_id='$get_my_user_id', 
							page_updated_user_alias=$inp_my_user_alias_mysql, 
							page_updated_user_image=$inp_my_user_image_mysql,
							page_updated_info='Moved page down' WHERE page_id=$get_current_page_id") or die(mysqli_error($link));

						// Update switch
						$result = mysqli_query($link, "UPDATE $t_knowledge_pages_index SET page_weight=$get_current_page_weight WHERE page_id=$get_switch_page_id") or die(mysqli_error($link));


						$url = "view_page.php?space_id=$get_current_page_space_id&page_id=$get_current_page_id&l=$l&ft=success&fm=position_moved";
						header("Location: $url");
						exit;
					} // switch page found
				} // member can edit
				else{
					$url = "view_page.php?space_id=$get_current_page_space_id&page_id=$get_current_page_id&l=$l&ft=warning&fm=your_user_cant_move_pages";
					header("Location: $url");
					exit;
				}
			} // is member of space
		} // logged in
		else{
			$url = "$root/users/login.php?l=$l&amp;referer=$root/knowledge/move_page_down.php?space_id=$get_current_page_space_id" . "amp;page_id=$get_current_page_id";
			header("Location: $url");
			exit;
			
		}
	} // page found
} // space found


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/$webdesignSav/footer.php");
?>