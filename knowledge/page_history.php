<?php 
/**
*
* File: howto/page_history.php
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
if (isset($_GET['version'])) {
	$version = $_GET['version'];
	$version = stripslashes(strip_tags($version));
}
else{
	$version = "";
}
$version_mysql = quote_smart($link, $version);

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
		/*- Headers ---------------------------------------------------------------------------------- */
		$website_title = "$get_current_space_title - $get_current_page_title - $l_history";
		if(file_exists("./favicon.ico")){ $root = "."; }
		elseif(file_exists("../favicon.ico")){ $root = ".."; }
		elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
		elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
		include("$root/_webdesign/header.php");

		
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
				
					if($action == ""){
						echo"

						<h1>$l_history</h1>

			
						<!-- Page history -->

							<table class=\"hor-zebra\">
							 <thead>
							  <tr>
							   <th scope=\"col\">
								<span>$l_ver</span>
							   </th>
							   <th scope=\"col\">
								<span>$l_title</span>
							  </th>
							   <th scope=\"col\">
								<span>$l_description</span>
							  </th>
							   <th scope=\"col\">
								<span>$l_date</span>
							   </th>
							   <th scope=\"col\">
								<span>$l_author</span>
							  </th>
							 </tr>
							</thead>
							<tbody>";
						$query_c = "SELECT history_id, history_page_id, history_page_version, history_page_is_deleted, history_page_title, history_page_title_clean, history_page_description, history_page_text, history_page_parent_id, history_weight, history_allow_comments, history_page_no_of_comments, history_page_updated_datetime, history_page_updated_date_saying, history_page_updated_user_id, history_page_updated_user_alias, history_page_updated_user_image, history_page_ip, history_page_hostname, history_page_user_agent, history_can_be_deleted_year FROM $t_knowledge_pages_edit_history WHERE history_page_id=$get_current_page_id ORDER BY history_id DESC";
						$result_c = mysqli_query($link, $query_c);
						while($row_c = mysqli_fetch_row($result_c)) {
							list($get_history_id, $get_history_page_id, $get_history_page_version, $get_history_page_is_deleted, $get_history_page_title, $get_history_page_title_clean, $get_history_page_description, $get_history_page_text, $get_history_page_parent_id, $get_history_weight, $get_history_allow_comments, $get_history_page_no_of_comments, $get_history_page_updated_datetime, $get_history_page_updated_date_saying, $get_history_page_updated_user_id, $get_history_page_updated_user_alias, $get_history_page_updated_user_image, $get_history_page_ip, $get_history_page_hostname, $get_history_page_user_agent, $get_history_can_be_deleted_year) = $row_c;

							// Style
							if(isset($odd) && $odd == false){
								$odd = true;
							}
							else{
								$odd = false;
							}
			
							echo"
							  <tr>
							   <td>
								<a href=\"page_history.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;action=view_historic_version&amp;version=$get_history_page_version&amp;l=$l\">$get_history_page_version</a>
							   </td>
							   <td>
								<span>$get_history_page_title</span>
							   </td>
							   <td>
								<span>$get_history_page_description</span>
							   </td>
							   <td>
								<span>$get_history_page_updated_date_saying</span>
							   </td>
							   <td>
								<span><a href=\"../users/view_profile.php?user_id=$get_history_page_updated_user_id&amp;l=$l\">$get_history_page_updated_user_alias</a></span>
							   </td>
							  </tr>
							";
						}
						echo"
							 </tbody>
							</table>
						<!-- //Page history -->

						<!-- Navigation -->
							<p>
							<a href=\"view_page.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;l=$l\"><img src=\"_gfx/icons/go-previous.png\" alt=\"go-previous.png\" /></a>
							<a href=\"view_page.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;l=$l\">$l_view_page</a>
							</p>
						<!-- //Navigation -->
						";
					} // action == ""
					elseif($action == "view_historic_version"){
						// Fetch version
						$query = "SELECT history_id, history_page_id, history_page_version, history_page_is_deleted, history_page_title, history_page_title_clean, history_page_description, history_page_text, history_page_parent_id, history_weight, history_allow_comments, history_page_no_of_comments, history_page_updated_datetime, history_page_updated_date_saying, history_page_updated_user_id, history_page_updated_user_alias, history_page_updated_user_image, history_page_ip, history_page_hostname, history_page_user_agent, history_can_be_deleted_year FROM $t_knowledge_pages_edit_history WHERE history_page_id=$get_current_page_id AND history_page_version=$version_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_current_history_id, $get_current_history_page_id, $get_current_history_page_version, $get_current_history_page_is_deleted, $get_current_history_page_title, $get_current_history_page_title_clean, $get_current_history_page_description, $get_current_history_page_text, $get_current_history_page_parent_id, $get_current_history_weight, $get_current_history_allow_comments, $get_current_history_page_no_of_comments, $get_current_history_page_updated_datetime, $get_current_history_page_updated_date_saying, $get_current_history_page_updated_user_id, $get_current_history_page_updated_user_alias, $get_current_history_page_updated_user_image, $get_current_history_page_ip, $get_current_history_page_hostname, $get_current_history_page_user_agent, $get_current_history_can) = $row;

						if($get_current_history_id == ""){
							echo"
							<h1>Server error 404</h1>
							<p>Historic version not found.</p>
							";
						}
						else{
							echo"
							<h1>$get_current_history_page_title</h1>

							<!-- Actions -->
								<p>
								<a href=\"page_history.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;action=revert_historic_version&amp;version=$get_current_history_page_version&amp;l=$l\" class=\"btn_default\">$l_revert_to_this_version</a>
								</p>
							<!-- //Actions -->

							<!-- Info -->

								<table class=\"hor-zebra\">
								 <tbody>
									 <tr>
									  <td style=\"padding-right: 4px;vertical-align: top;text-align:right;\">
										<span>$l_title:</span>
									  </td>
									  <td>
										<span>$get_current_history_page_title</span>
									  </td>
									 </tr>
									 <tr>
									  <td class=\"odd\" style=\"padding-right: 4px;vertical-align: top;text-align:right;\">
										<span>$l_description:</span>
									  </td>
									  <td class=\"odd\">
										<span>$get_current_history_page_description</span>
									  </td>
									 </tr>
									 <tr>
									  <td style=\"padding-right: 4px;vertical-align: top;text-align:right;\">
										<span>$l_parent:</span>
									  </td>
									  <td>
										<span>";
										if($get_current_history_page_parent_id != "0"){
											$query = "SELECT page_id, page_title FROM $t_knowledge_pages_index WHERE page_id=$get_current_history_page_parent_id AND page_space_id=$get_current_space_id";
											$result = mysqli_query($link, $query);
											$row = mysqli_fetch_row($result);
											list($get_parent_page_id, $get_parent_page_title) = $row;

											echo"<a href=\"view_page.php?space_id=$get_current_page_space_id&amp;page_id=$get_parent_page_id&amp;l=$l\">$get_parent_page_title</a>";
										}
										echo"</span>
									  </td>
								  </tr>
								  <tr>
								   <td class=\"odd\" style=\"padding-right: 4px;vertical-align: top;text-align:right;\">
									<span>$l_description:</span>
								   </td>
								   <td class=\"odd\">
									<span>$get_current_history_page_description</span>
								   </td>
								  </tr>
								  <tr>
								   <td style=\"padding-right: 4px;vertical-align: top;text-align:right;\">
									<span>$l_weight:</span>
								   </td>
								   <td>
									<span>$get_current_history_weight</span>
								   </td>
								  </tr>

								  <tr>
								   <td class=\"odd\" style=\"padding-right: 4px;vertical-align: top;text-align:right;\">
									<span>$l_allow_comments:</span>
								   </td>
								   <td class=\"odd\">
									<span>$get_current_history_allow_comments</span>
								   </td>
								  </tr>

								  <tr>
								   <td style=\"padding-right: 4px;vertical-align: top;text-align:right;\">
									<span>$l_updated:</span>
								   </td>
								   <td>
									<span title=\"$get_current_history_page_updated_datetime\">$get_current_history_page_updated_date_saying</span>
								   </td>
								  </tr>

								  <tr>
								   <td style=\"padding-right: 4px;vertical-align: top;text-align:right;\">
									<span>$l_author:</span>
								   </td>
								   <td>
									<span><a href=\"../users/view_profile.php?user_id=$get_current_history_page_updated_user_id&amp;l=$l\">$get_current_history_page_updated_user_alias</a></span>
								   </td>
								  </tr>

								  <tr>
								   <td style=\"padding-right: 4px;vertical-align: top;text-align:right;\">
									<span>$l_ip:</span>
								   </td>
								   <td>
									<span>$get_current_history_page_ip</span>
								   </td>
								  </tr>

								  <tr>
								   <td style=\"padding-right: 4px;vertical-align: top;text-align:right;\">
									<span>$l_host:</span>
								   </td>
								   <td>
									<span>$get_current_history_page_hostname</span>
								   </td>
								  </tr>

								  <tr>
								   <td style=\"padding-right: 4px;vertical-align: top;text-align:right;\">
									<span>$l_user_agent:</span>
								   </td>
								   <td>
									<span>$get_current_history_page_user_agent</span>
								   </td>
								  </tr>
								 </tbody>
								</table>

							<!-- //Info -->

							<p>$get_current_history_page_text</p>



							<!-- Navigation -->
								<p>
								<a href=\"page_history.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;l=$l\"><img src=\"_gfx/icons/go-previous.png\" alt=\"go-previous.png\" /></a>
								<a href=\"page_history.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;l=$l\">$l_history</a>
								</p>
							<!-- //Navigation -->
							";
						} // historic version found
					} // view_historic_version
					elseif($action == "revert_historic_version"){
						// Fetch version
						$query = "SELECT history_id, history_page_id, history_page_version, history_page_is_deleted, history_page_title, history_page_title_clean, history_page_description, history_page_text, history_page_parent_id, history_weight, history_allow_comments, history_page_no_of_comments, history_page_updated_datetime, history_page_updated_date_saying, history_page_updated_user_id, history_page_updated_user_alias, history_page_updated_user_image, history_page_ip, history_page_hostname, history_page_user_agent, history_can_be_deleted_year FROM $t_knowledge_pages_edit_history WHERE history_page_id=$get_current_page_id AND history_page_version=$version_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_current_history_id, $get_current_history_page_id, $get_current_history_page_version, $get_current_history_page_is_deleted, $get_current_history_page_title, $get_current_history_page_title_clean, $get_current_history_page_description, $get_current_history_page_text, $get_current_history_page_parent_id, $get_current_history_weight, $get_current_history_allow_comments, $get_current_history_page_no_of_comments, $get_current_history_page_updated_datetime, $get_current_history_page_updated_date_saying, $get_current_history_page_updated_user_id, $get_current_history_page_updated_user_alias, $get_current_history_page_updated_user_image, $get_current_history_page_ip, $get_current_history_page_hostname, $get_current_history_page_user_agent, $get_current_history_can) = $row;

						if($get_current_history_id == ""){
							echo"
							<h1>Server error 404</h1>
							<p>Historic version not found.</p>
							";
						}
						else{
							if($process == "1"){
								
								$inp_title_mysql = quote_smart($link, $get_current_history_page_title);
								$inp_title_clean_mysql = quote_smart($link, $get_current_history_page_title_clean); 
								$inp_description_mysql = quote_smart($link, $get_current_history_page_description);
		
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

							
								// page ver
								$inp_page_version = $get_current_page_version+1;

								// Update page
								$result = mysqli_query($link, "UPDATE $t_knowledge_pages_index SET 
									page_title=$inp_title_mysql, 
									page_title_clean=$inp_title_clean_mysql, 
									page_description=$inp_description_mysql,
									page_parent_id=$get_current_history_page_parent_id,
									page_allow_comments=$get_current_history_allow_comments, 
									page_updated_datetime='$datetime',
									page_updated_date_saying='$date_saying',
									page_updated_user_id=$get_my_user_id,
									page_updated_user_alias=$inp_my_user_alias_mysql,
									page_updated_user_image=$inp_my_user_image_mysql,
									page_updated_info='Reverted version $get_current_history_page_version',
									page_version=$inp_page_version
									 WHERE page_id=$get_current_page_id") or die(mysqli_error($link));

								
								$sql = "UPDATE $t_knowledge_pages_index SET page_text=? WHERE page_id=$get_current_page_id";
								$stmt = $link->prepare($sql);
								$stmt->bind_param("s", $get_current_history_page_text);
								$stmt->execute();
								if ($stmt->errno) {
									echo "FAILURE!!! " . $stmt->error; die;
								}

								// Header
								$url = "view_page.php?space_id=$get_current_page_space_id&page_id=$get_current_page_id&l=$l&ft=success&fm=page_reverted";
								header("Location: $url");
								exit;

							}
							echo"
							<h1>$l_revert $get_current_history_page_title</h1>

							<p>
							$l_are_you_sure_you_want_to_revert_the_page_to_version $get_current_history_page_version?
							</p>

							<p>
							<a href=\"page_history.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;action=revert_historic_version&amp;version=$get_current_history_page_version&amp;l=$l&amp;process=1\" class=\"btn_default\">$l_confirm</a>
							<a href=\"page_history.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;action=view_historic_version&amp;version=$get_current_history_page_version&amp;l=$l\" class=\"btn_default\">$l_cancel</a>
							</p>
							";
						}
					} // action == revert_historic_version
				} // member can edit
				else{
					$url = "view_page.php?space_id=$get_current_page_space_id&page_id=$get_current_page_id&l=$l&ft=warning&fm=your_user_cant_edit_pages";
					
					echo"
					<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Server error 403</h1>
		
					<meta http-equiv=\"refresh\" content=\"1;url=$url\">
					";
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