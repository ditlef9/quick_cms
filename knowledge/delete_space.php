<?php 
/**
*
* File: howto/delete_space.php
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

/*- Translation ------------------------------------------------------------------------------- */
include("$root/_admin/_translations/site/$l/knowledge/ts_view_page.php");

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

// Find space
$query = "SELECT space_id, space_title, space_title_clean, space_description, space_text, space_image, space_is_archived, space_unique_hits, space_unique_hits_ip_block, space_unique_hits_user_id_block, space_created_datetime, space_created_date_saying, space_created_user_id, space_created_user_alias, space_created_user_image, space_updated_datetime, space_updated_date_saying, space_updated_user_id, space_updated_user_alias, space_updated_user_image FROM $t_knowledge_spaces_index WHERE space_id=$space_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_space_id, $get_current_space_title, $get_current_space_title_clean, $get_current_space_description, $get_current_space_text, $get_current_space_image, $get_current_space_is_archived, $get_current_space_unique_hits, $get_current_space_unique_hits_ip_block, $get_current_space_unique_hits_user_id_block, $get_current_space_created_datetime, $get_current_space_created_date_saying, $get_current_space_created_user_id, $get_current_space_created_user_alias, $get_current_space_created_user_image, $get_current_space_updated_datetime, $get_current_space_updated_date_saying, $get_current_space_updated_user_id, $get_current_space_updated_user_alias, $get_current_space_updated_user_image) = $row;

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


	// Check if I am admin, second in commander0
	if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
		// Access?
		$my_user_id = $_SESSION['user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);

		// Get my user
		$query = "SELECT user_id, user_email, user_name, user_alias, user_language, user_last_online, user_rank, user_login_tries FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_language, $get_my_user_last_online, $get_my_user_rank, $get_my_user_login_tries) = $row;
	
		$query_p = "SELECT photo_id, photo_destination, photo_thumb_40 FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
		$result_p = mysqli_query($link, $query_p);
		$row_p = mysqli_fetch_row($result_p);
		list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_40) = $row_p;

	
		$query = "SELECT member_id, member_space_id, member_rank, member_user_id, member_user_alias, member_user_image, member_user_about, member_added_datetime, member_added_date_saying, member_added_by_user_id, member_added_by_user_alias, member_added_by_user_image FROM $t_knowledge_spaces_members WHERE member_space_id=$get_current_space_id AND member_user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_member_id, $get_current_member_space_id, $get_current_member_rank, $get_current_member_user_id, $get_current_member_user_alias, $get_current_member_user_image, $get_current_member_user_about, $get_current_member_added_datetime, $get_current_member_added_date_saying, $get_current_member_added_by_user_id, $get_current_member_added_by_user_alias, $get_current_member_added_by_user_image) = $row;
		
		if($get_current_member_id == ""){

			if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
				// If im admin, then add me
	
				$inp_my_user_alias_mysql = quote_smart($link, $get_my_user_alias);
				$inp_my_user_email_mysql = quote_smart($link, $get_my_user_email);
				$inp_my_user_image_mysql = quote_smart($link, $get_my_photo_destination);

				mysqli_query($link, "INSERT INTO $t_knowledge_spaces_members
				(member_id, member_space_id, member_rank, member_user_id, member_user_alias, member_user_image, member_user_about, member_added_datetime, member_added_date_saying, member_added_by_user_id, member_added_by_user_alias, member_added_by_user_image) 
				VALUES 
				(NULL, $get_current_space_id, 'admin', $get_my_user_id, $inp_my_user_alias_mysql, $inp_my_user_image_mysql, '', '$datetime', '$date_saying', $get_my_user_id, $inp_my_user_alias_mysql, $inp_my_user_image_mysql)")
				or die(mysqli_error($link));
			}
			else{
				$url = "open_space.php?space_id=$get_current_space_id&ft=error&fm=your_not_a_space_member_and_thus_cannot_edit_the_space";
				header("Location: $url");
				exit;
			}	
		}
		else{
			// Im registered member.
			// Can edit members: admin, moderator
			// Can edit space:   admin, moderator, editor
			if($get_current_member_rank == "admin" OR $get_current_member_rank == "moderator" OR $get_current_member_rank == "editor"){

			}
			else{
				$url = "open_space.php?space_id=$get_current_space_id&ft=error&fm=your_dont_have_access_to_edit_this_space__please_contact_the_admin_for_access__your_rank_is_$get_current_member_rank";
				header("Location: $url");
				exit;
			}
		}


		// Begin space edit
		if($action == ""){
			/*- Headers ---------------------------------------------------------------------------------- */
			$website_title = "$get_current_space_title - $l_delete";
			if(file_exists("./favicon.ico")){ $root = "."; }
			elseif(file_exists("../favicon.ico")){ $root = ".."; }
			elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
			elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
			include("$root/_webdesign/header.php");


			if($process == "1"){
				$result = mysqli_query($link, "DELETE FROM $t_knowledge_spaces_index WHERE space_id=$get_current_space_id");
				$result = mysqli_query($link, "DELETE FROM $t_knowledge_spaces_members WHERE member_space_id=$get_current_space_id");

				// Search engine index: space
				$result = mysqli_query($link, "DELETE FROM $t_search_engine_index WHERE index_module_name='knowledge' AND index_reference_name='space_id' AND index_reference_id=$get_current_space_id") or die(mysqli_error($link));

				// Search engine index: pages
				$result = mysqli_query($link, "DELETE FROM $t_search_engine_index WHERE index_module_name='knowledge' AND index_module_part_name='spaces' AND index_module_part_id=$get_current_space_id") or die(mysqli_error($link));

				// Search engine index: access
				$result = mysqli_query($link, "DELETE FROM $t_search_engine_access_control WHERE control_has_access_to_module_name='knowledge' AND control_has_access_to_module_part_name='spaces' AND control_has_access_to_module_part_id=$get_current_space_id") or die(mysqli_error($link));

				header("Location: index.php?l=$l&ft=success&fm=space_deleted");
				exit;
			}

			echo"
			<h1>$l_delete_space</h1>

			<!-- Where am I ? -->
				<p><b>$l_you_are_here:</b><br />
				<a href=\"open_space.php?space_id=$get_current_space_id&amp;l=$l\">$get_current_space_title</a>
				&gt;
				<a href=\"delete_space.php?space_id=$get_current_space_id&amp;l=$l\">$l_delete</a>
				</p>
			<!-- Where am I ? -->
			
			<!-- Delete space form -->
				<p>
				$l_are_you_sure_you_want_to_delete_the_space
				</p>

				<p>
				<a href=\"delete_space.php?space_id=$get_current_space_id&amp;l=$l&amp;process=1\" class=\"btn_warning\">$l_confirm</a>
				<a href=\"open_space.php?space_id=$get_current_space_id&amp;l=$l\" class=\"btn_default\">$l_cancel</a>
				</p>
			<!-- Delete space form -->

			<!-- Back -->
				<p>
				<a href=\"open_space.php?space_id=$get_current_space_id&amp;l=$l\"><img src=\"_gfx/icons/go-previous.png\" alt=\"go-previous.png\" /></a>
				<a href=\"open_space.php?space_id=$get_current_space_id&amp;l=$l\">$l_view_space</a>
				</p>
			<!-- //Back -->
			";
			
		} // action == ""


	} // logged in
	else{
		$url = "$root/users/login.php?l=$l&amp;referer=$root/knowledge/edit_space.php?space_id=$get_current_space_id";
		header("Location: $url");
		exit;
	} // not logged in
}


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/$webdesignSav/footer.php");
?>