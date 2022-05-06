<?php 
/**
*
* File: howto/diagrams.php
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

/*- Variables -------------------------------------------------------------------------------- */
$tabindex = 0;

if(isset($_GET['space_id'])) {
	$space_id = $_GET['space_id'];
	$space_id = stripslashes(strip_tags($space_id));
}
else{
	$space_id = "";
}
$space_id_mysql = quote_smart($link, $space_id);
if(isset($_GET['page_id'])) {
	$page_id = $_GET['page_id'];
	$page_id = stripslashes(strip_tags($page_id));
}
else{
	$page_id = "";
}
$page_id_mysql = quote_smart($link, $page_id);

// Find space
$query = "SELECT space_id, space_title, space_title_clean, space_description, space_text, space_image, space_thumb_32, space_thumb_16, space_is_archived, space_unique_hits, space_unique_hits_ip_block, space_unique_hits_user_id_block, space_created_datetime, space_created_date_saying, space_created_user_id, space_created_user_alias, space_created_user_image, space_updated_datetime, space_updated_date_saying, space_updated_user_id, space_updated_user_alias, space_updated_user_image FROM $t_knowledge_spaces_index WHERE space_id=$space_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_space_id, $get_current_space_title, $get_current_space_title_clean, $get_current_space_description, $get_current_space_text, $get_current_space_image, $get_current_space_thumb_32, $get_current_space_thumb_16, $get_current_space_is_archived, $get_current_space_unique_hits, $get_current_space_unique_hits_ip_block, $get_current_space_unique_hits_user_id_block, $get_current_space_created_datetime, $get_current_space_created_date_saying, $get_current_space_created_user_id, $get_current_space_created_user_alias, $get_current_space_created_user_image, $get_current_space_updated_datetime, $get_current_space_updated_date_saying, $get_current_space_updated_user_id, $get_current_space_updated_user_alias, $get_current_space_updated_user_image) = $row;

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
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$get_current_space_title - $l_diagrams";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");


	// Get my user
	if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
		$my_user_id = $_SESSION['user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);

		// Check if I am a member
		$query = "SELECT member_id, member_space_id, member_rank, member_user_id, member_user_alias, member_user_image, member_user_about, member_added_datetime, member_added_date_saying, member_added_by_user_id, member_added_by_user_alias, member_added_by_user_image FROM $t_knowledge_spaces_members WHERE member_space_id=$get_current_space_id AND member_user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_member_id, $get_my_member_space_id, $get_my_member_rank, $get_my_member_user_id, $get_my_member_user_alias, $get_my_member_user_image, $get_my_member_user_about, $get_my_member_added_datetime, $get_my_member_added_date_saying, $get_my_member_added_by_user_id, $get_my_member_added_by_user_alias, $get_my_member_added_by_user_image) = $row;
		if($get_my_member_id == ""){
			// Did I already request membership?
			$query = "SELECT requested_membership_id, requested_membership_date_saying FROM $t_knowledge_spaces_requested_memberships WHERE requested_membership_space_id=$get_current_space_id AND requested_membership_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_requested_membership_id, $get_requested_membership_date_saying) = $row;
			if($get_requested_membership_id == ""){

				// Check my USER rank. If admin or moderator: then add me
				$query = "SELECT user_id, user_email, user_name, user_alias, user_language, user_last_online, user_rank, user_login_tries FROM $t_users WHERE user_id=$my_user_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_language, $get_my_user_last_online, $get_my_user_rank, $get_my_user_login_tries) = $row;
				if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
					// Auto insert
					$query_p = "SELECT photo_id, photo_destination, photo_thumb_40 FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
					$result_p = mysqli_query($link, $query_p);
					$row_p = mysqli_fetch_row($result_p);
					list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_40) = $row_p;

					$inp_my_rank_mysql = quote_smart($link, $get_my_user_rank);
					$inp_my_user_alias_mysql = quote_smart($link, $get_my_user_alias);
					$inp_my_user_email_mysql = quote_smart($link, $get_my_user_email);
					$inp_my_user_image_mysql = quote_smart($link, $get_my_photo_destination);

					$datetime = date("Y-m-d H:i:s");
					$date_saying = date("j M Y");


					mysqli_query($link, "INSERT INTO $t_knowledge_spaces_members
					(member_id, member_space_id, member_rank, member_user_id, member_user_alias, member_user_email, member_user_image, member_user_position, member_user_department, member_user_location, member_user_about, member_added_datetime, member_added_date_saying, member_added_by_user_id, member_added_by_user_alias, member_added_by_user_image) 
					VALUES 
					(NULL, $get_current_space_id, $inp_my_rank_mysql, $get_my_user_id, $inp_my_user_alias_mysql, $inp_my_user_email_mysql, $inp_my_user_image_mysql, '', '', '', '', '$datetime', '$date_saying', '$get_my_user_id', $inp_my_user_alias_mysql, $inp_my_user_image_mysql)")
					or die(mysqli_error($link));
					echo"
					<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Auto inserting...</h1>
					<meta http-equiv=\"refresh\" content=\"1;url=open_space.php?space_id=$get_current_space_id\">
					";
				}
	
				
				echo"
				<h1>$l_your_not_a_member_of_this_space</h1>
			
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

				<p>
				$l_only_members_can_see_this_space
				</p>
		
				<p>
				<a href=\"request_menbership_to_space.php?space_id=$get_current_space_id&amp;l=$l\" class=\"btn_default\">$l_request_membership</a>
				</p>
				";
			}
			else{
				echo"
				<h1>$l_membership_requests_pending</h1>

				<p>$l_you_sent_a_membersip_request $get_requested_membership_date_saying.</p>

				<p>
				<a href=\"index.php?l=$l\" class=\"btn_default\">$l_spaces</a>
				</p>
				";
			}
		}
		else{

			echo"
				<h1>$l_diagrams</h1>

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

				<!-- Diagrams -->
					<p>
					<a href=\"new_diagram.php?space_id=$space_id&amp;page_id=$page_id&amp;l=$l\" class=\"btn_default\">$l_new_diagram</a>
					</p>
					<div style=\"height: 10px;\"></div>

					<table class=\"hor-zebra\">
					 <thead>
					  <tr>
					   <th scope=\"col\">
						<span>$l_title</span>
					   </th>
					   <th scope=\"col\">
						<span>$l_page</span>
					   </th>
					   <th scope=\"col\">
						<span>$l_author</span>
					   </th>
					   <th scope=\"col\">
						<span>$l_edited</span>
					   </th>
					   <th scope=\"col\">
						<span>$l_actions</span>
					   </th>
					  </tr>
			 		 </thead>
					<tbody>";
					// Select diagrams
					$query = "SELECT diagram_id, diagram_space_id, diagram_page_id, diagram_page_title, diagram_type, diagram_version, diagram_title, diagram_file_path, diagram_file_xml_name, diagram_file_image_name, diagram_file_image_thumb_100, diagram_unique_hits, diagram_unique_hits_ip_block, diagram_unique_hits_user_id_block, diagram_created_datetime, diagram_created_date_saying, diagram_created_by_user_id, diagram_created_by_user_alias, diagram_created_by_user_email, diagram_created_by_user_image_file, diagram_created_by_user_ip, diagram_created_by_user_hostname, diagram_created_by_user_agent, diagram_updated_datetime, diagram_updated_date_saying, diagram_updated_by_user_id, diagram_updated_by_user_alias, diagram_updated_by_user_email, diagram_updated_by_user_image_file, diagram_updated_by_user_ip, diagram_updated_by_user_hostname, diagram_updated_by_user_agent FROM $t_knowledge_pages_diagrams WHERE diagram_space_id=$get_current_space_id";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_diagram_id, $get_diagram_space_id, $get_diagram_page_id, $get_diagram_page_title, $get_diagram_type, $get_diagram_version, $get_diagram_title, $get_diagram_file_path, $get_diagram_file_xml_name, $get_diagram_file_image_name, $get_diagram_file_image_thumb_100, $get_diagram_unique_hits, $get_diagram_unique_hits_ip_block, $get_diagram_unique_hits_user_id_block, $get_diagram_created_datetime, $get_diagram_created_date_saying, $get_diagram_created_by_user_id, $get_diagram_created_by_user_alias, $get_diagram_created_by_user_email, $get_diagram_created_by_user_image_file, $get_diagram_created_by_user_ip, $get_diagram_created_by_user_hostname, $get_diagram_created_by_user_agent, $get_diagram_updated_datetime, $get_diagram_updated_date_saying, $get_diagram_updated_by_user_id, $get_diagram_updated_by_user_alias, $get_diagram_updated_by_user_email, $get_diagram_updated_by_user_image_file, $get_diagram_updated_by_user_ip, $get_diagram_updated_by_user_hostname, $get_diagram_updated_by_user_agent) = $row;


				
						// Style
						if(isset($odd) && $odd == false){
							$odd = true;
						}
						else{
							$odd = false;
						}	

						echo"
						 <tr>
       						  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
							<span><a href=\"diagram_mxgraph_open.php?space_id=$get_current_space_id&amp;page_id=$get_diagram_page_id&amp;diagram_id=$get_diagram_id&amp;l=$l\">$get_diagram_title</a></span>
						  </td>
       						  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
							<span><a href=\"view_page.php?space_id=$get_current_space_id&amp;page_id=$get_diagram_page_id&amp;l=$l\">$get_diagram_page_title</a></span>
						  </td>
       						  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
							<span><a href=\"$root/users/view_profile.php?user_id=$get_diagram_updated_by_user_id&amp;l=$l\">$get_diagram_updated_by_user_alias</a></span>
						  </td>
       						  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
							<span>$get_diagram_updated_date_saying</span>
						  </td>
       						  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
							<span>
							<a href=\"$root/$get_diagram_file_path/$get_diagram_file_image_name\">$l_image</a>
							&middot;
							<a href=\"$root/$get_diagram_file_path/$get_diagram_file_xml_name\">$l_xml</a>
							&middot;
							<a href=\"diagram_mxgraph_xml_to_png.php?space_id=$get_current_space_id&amp;page_id=$get_diagram_page_id&amp;diagram_id=$get_diagram_id&amp;l=$l\">$l_xml_to_png</a>
							&middot;
							<a href=\"diagram_edit.php?space_id=$get_current_space_id&amp;page_id=$get_diagram_page_id&amp;diagram_id=$get_diagram_id&amp;l=$l\">$l_edit</a>
							&middot;
							<a href=\"diagram_delete.php?space_id=$get_current_space_id&amp;page_id=$get_diagram_page_id&amp;diagram_id=$get_diagram_id&amp;l=$l\">$l_delete</a>
							</span>
						  </td>
     						 </tr>";
					}
					echo"
					 </tbody>
					</table>
				<!-- //Diagrams -->
			";
		} // is member
	} // logged in
	else{
		
		echo"
		<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" /> $l_please_log_in...</h1>
		<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=$root/knowledge/open_space.php?space_id=$get_current_space_id\">
		";
	}
} // space found


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/$webdesignSav/footer.php");
?>