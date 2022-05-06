<?php 
/**
*
* File: howto/media_bank.php
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
	$website_title = "$get_current_space_title - $l_image_gallery";
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

			if($action == ""){
				echo"
				<h1>$l_media</h1>

				<!-- Folders -->
					<div class=\"image_gallery_folders_list\">
						<table>
						 <tr>
						  <td style=\"padding: 0px 6px 6px 0px;\">
							<a href=\"media.php?space_id=$get_current_space_id&amp;l=$l\"><img src=\"_gfx/icons/format-justify-fill.png\" alt=\"format-justify-fill.png\" /></a>
						  </td>
						  <td style=\"padding: 0px 0px 6px 0px;\">
							<a href=\"media.php?space_id=$get_current_space_id&amp;l=$l\" style=\"font-weight: bold;\">$get_current_space_title</a>
						  </td>
						 </tr>
						";
						$query = "SELECT page_id, page_title, page_no_of_children, page_weight FROM $t_knowledge_pages_index WHERE page_space_id=$get_current_space_id AND page_parent_id='0' ORDER BY page_weight ASC";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_page_id_a, $get_page_title_a, $get_page_no_of_children_a, $get_page_weight_a) = $row;


							echo"
							 <tr>
							  <td style=\"padding: 0px 6px 6px 0px;\">
								<a href=\"media.php?space_id=$get_current_space_id&amp;action=open_folder&amp;page_id=$get_page_id_a&amp;page_id_a=$get_page_id_a&amp;l=$l\"><img src=\"_gfx/icons/folder.png\" alt=\"folder.png\" /></a>
							  </td>
							  <td style=\"padding: 0px 0px 6px 0px;\">
								<a href=\"media.php?space_id=$get_current_space_id&amp;action=open_folder&amp;page_id=$get_page_id_a&amp;page_id_a=$get_page_id_a&amp;l=$l\">$get_page_title_a</a>
							  </td>
							 </tr>
							";
						}
						echo"
						</table>
					</div>
				<!-- //Folders -->

				<!-- Folder browse -->
					<div class=\"image_gallery_folder_browse\">
						<h2>$get_current_space_title</h2>

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

						<!-- Upload form -->
							<form method=\"POST\" action=\"media.php?space_id=$get_current_space_id&amp;action=upload_image&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
							<p>
							<input name=\"inp_image\" type=\"file\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
							<input type=\"submit\" value=\"$l_upload\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
							</p>
							</form>
						<!-- //Upload form -->
						";
						$x = 0;
						$query = "SELECT media_id, media_space_id, media_page_id, media_type, media_ext, media_version, media_title, media_file_path, media_file_name, media_file_thumb_800, media_file_thumb_100, media_unique_hits, media_unique_hits_ip_block, media_unique_hits_user_id_block, media_created_datetime, media_created_date_saying, media_created_by_user_id, media_created_by_user_alias, media_created_by_user_email, media_created_by_user_image_file, media_created_by_user_ip, media_created_by_user_hostname, media_created_by_user_agent, media_updated_datetime, media_updated_date_saying, media_updated_by_user_id, media_updated_by_user_alias, media_updated_by_user_email, media_updated_by_user_image_file, media_updated_by_user_ip, media_updated_by_user_hostname, media_updated_by_user_agent FROM $t_knowledge_pages_media WHERE media_space_id=$get_current_space_id ORDER BY media_id DESC";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_media_id, $get_media_space_id, $get_media_page_id, $get_media_type, $get_media_ext, $get_media_version, $get_media_title, $get_media_file_path, $get_media_file_name, $get_media_file_thumb_800, $get_media_file_thumb_100, $get_media_unique_hits, $get_media_unique_hits_ip_block, $get_media_unique_hits_user_id_block, $get_media_created_datetime, $get_media_created_date_saying, $get_media_created_by_user_id, $get_media_created_by_user_alias, $get_media_created_by_user_email, $get_media_created_by_user_image_file, $get_media_created_by_user_ip, $get_media_created_by_user_hostname, $get_media_created_by_user_agent, $get_media_updated_datetime, $get_media_updated_date_saying, $get_media_updated_by_user_id, $get_media_updated_by_user_alias, $get_media_updated_by_user_email, $get_media_updated_by_user_image_file, $get_media_updated_by_user_ip, $get_media_updated_by_user_hostname, $get_media_updated_by_user_agent) = $row;

							// Look for image
							if(!(file_exists("$root/$get_media_file_path/$get_media_file_name")) OR $get_media_file_name == ""){
								echo"<div class=\"info\"><p>Media &quot;$root/$get_media_file_path/$get_media_file_name&quot; doesnt exists. Deleting database reference.</p></div>\n";
								$result_delete = mysqli_query($link, "DELETE FROM $t_knowledge_pages_media WHERE media_id=$get_media_id");
							}

							// Look for thumb
							if($get_media_type == "image" && !(file_exists("$root/$get_media_file_path/$get_media_file_thumb_100")) && $get_media_file_thumb_100 != ""){
								resize_crop_image(100, 100, "$root/$get_media_file_path/$get_media_file_name", "$root/$get_media_file_path/$get_media_file_thumb_100");
							}

							// Layout
							if($x == 0){
								echo"
								<div class=\"image_gallery_folder_browse_row\">
								";
							}
			
							// Title
							$title_len = strlen($get_media_title);
							if($title_len > 15){
								$get_media_title = substr($get_media_title, 0, 15);
								
							}

							echo"
									<div class=\"image_gallery_folder_browse_col\">
										<p>
										<a href=\"media.php?space_id=$get_current_space_id&amp;action=view_media&amp;page_id=$get_media_page_id&amp;media_id=$get_media_id&amp;l=$l\">";
										if($get_media_type == "image"){
											echo"<img src=\"$root/$get_media_file_path/$get_media_file_thumb_100\" alt=\"$get_media_file_thumb_100\" />";
										}
										else{
											echo"<img src=\"_gfx/icons/100x100/$get_media_ext\" alt=\"_gfx/icons/100x100/$get_media_ext\" />";
										}
										echo"</a><br />
										<a href=\"media.php?space_id=$get_current_space_id&amp;action=view_media&amp;page_id=$get_media_page_id&amp;media_id=$get_media_id&amp;l=$l\">$get_media_title</a>
										</p>
									</div>
							";

							// Layout
							if($x == 3){
								echo"
								</div>
								";
								$x = -1;
							}
							$x++;
						}
						if($x != 0){
								echo"
								</div>
								";
						}
						echo"
					</div>
				<!-- //Folder browse -->
				";
			} // action == ""
			elseif($action == "upload_image"){
				if(isset($_GET['page_id_a'])) {
					$page_id_a = $_GET['page_id_a'];
					$page_id_a = stripslashes(strip_tags($page_id_a));
				}
				else{
					$page_id_a = "";
				}
				if(isset($_GET['page_id_b'])) {
					$page_id_b = $_GET['page_id_b'];
					$page_id_b = stripslashes(strip_tags($page_id_b));
				}
				else{
					$page_id_b = "";
				}
				if(isset($_GET['page_id_c'])) {
					$page_id_c = $_GET['page_id_c'];
					$page_id_c = stripslashes(strip_tags($page_id_c));
				}
				else{
					$page_id_c = "";
				}
				if(isset($_GET['page_id_d'])) {
					$page_id_d = $_GET['page_id_d'];
					$page_id_d = stripslashes(strip_tags($page_id_d));
				}
				else{
					$page_id_d = "";
				}

				if (isset($_GET['page_id'])) {
					$page_id = $_GET['page_id'];
					$page_id = stripslashes(strip_tags($page_id));
				}
				else{
					$page_id = "";
				}
				$page_id_mysql = quote_smart($link, $page_id);

				// Find page
			 	$query = "SELECT page_id FROM $t_knowledge_pages_index WHERE page_id=$page_id_mysql AND page_space_id=$get_current_space_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_page_id) = $row;

				if($get_current_page_id == ""){
					$get_current_page_id = 0;
				}
				

				// Create dir
				if(!is_dir("$root/_uploads")){
					mkdir("$root/_uploads");
				}
				if(!is_dir("$root/_uploads/knowledge")){
					mkdir("$root/_uploads/knowledge");
				}
				if(!is_dir("$root/_uploads/knowledge/space_$get_current_space_id")){
					mkdir("$root/_uploads/knowledge/space_$get_current_space_id");
				}

				if(!is_dir("$root/_uploads/knowledge/space_$get_current_space_id/page_$get_current_page_id")){
					mkdir("$root/_uploads/knowledge/space_$get_current_space_id/page_$get_current_page_id");
				}


				$tmp_name = $_FILES["inp_image"]["tmp_name"];
				$filename = stripslashes($_FILES['inp_image']['name']);
				$extension = get_extension($filename);
				$extension = strtolower($extension);

				// Transfer
				$ft = "";
				$fm = "";
				$media_id = "";


				// Me
				$query = "SELECT user_id, user_email, user_name, user_alias, user_date_format FROM $t_users WHERE user_id=$my_user_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_date_format) = $row;

				// Get my profile image
				$q = "SELECT photo_id, photo_destination FROM $t_users_profile_photo WHERE photo_user_id=$my_user_id_mysql AND photo_profile_image='1'";
				$r = mysqli_query($link, $q);
				$rowb = mysqli_fetch_row($r);
				list($get_my_photo_id, $get_my_photo_destination) = $rowb;


				$inp_ext = "$extension";
				$inp_ext = output_html($inp_ext);
				$inp_ext_mysql = quote_smart($link, $inp_ext);

				$inp_title = output_html($filename);
				$inp_title_mysql = quote_smart($link, $inp_title);

				$inp_file_path = "_uploads/knowledge/space_$get_current_space_id/page_$get_current_page_id";
				$inp_file_path_mysql = quote_smart($link, $inp_file_path);

				$datetime = date("Y-m-d H:i:s");
				$date_saying = date("j M Y");

				$inp_my_alias_mysql = quote_smart($link, $get_my_user_alias);
				$inp_my_email_mysql = quote_smart($link, $get_my_user_email);
				$inp_my_image_mysql = quote_smart($link, $get_my_photo_destination);

				// IP
				$my_ip = $_SERVER['REMOTE_ADDR'];
				$my_ip = output_html($my_ip);
				$my_ip_mysql = quote_smart($link, $my_ip);

				$my_hostname = "";
				if($configSiteUseGethostbyaddrSav == "1"){
					$my_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
				}
				$my_hostname = output_html($my_hostname);
				$my_hostname_mysql = quote_smart($link, $my_hostname);

				$my_user_agent = $_SERVER['HTTP_USER_AGENT'];
				$my_user_agent = output_html($my_user_agent);
				$my_user_agent_mysql = quote_smart($link, $my_user_agent);



				if($filename){
					if ($extension == "jpg" OR $extension == "jpeg" OR $extension == "png" OR $extension == "gif") {
						$inp_type = "image";
						$inp_type_mysql = quote_smart($link, $inp_type);

						$size=filesize($_FILES['inp_image']['tmp_name']);

						if($extension=="jpg" || $extension=="jpeg" ){
							ini_set ('gd.jpeg_ignore_warning', 1);
							error_reporting(0);
							$uploadedfile = $_FILES['inp_image']['tmp_name'];
							$src = imagecreatefromjpeg($uploadedfile);
						}
						elseif($extension=="png"){
							$uploadedfile = $_FILES['inp_image']['tmp_name'];
							$src = @imagecreatefrompng($uploadedfile);
						}
						else{
							$src = @imagecreatefromgif($uploadedfile);
						}
						list($width,$height) = @getimagesize($uploadedfile);
						if($width == "" OR $height == ""){
							$ft = "warning";
							$fm = "photo_could_not_be_uploaded_please_check_file_size";
						}
						else{

							mysqli_query($link, "INSERT INTO $t_knowledge_pages_media
							(media_id, media_space_id, media_page_id, media_type, media_ext, media_version, media_title, media_file_path, media_unique_hits, media_created_datetime, media_created_date_saying, media_created_by_user_id, media_created_by_user_alias, media_created_by_user_email, media_created_by_user_image_file, media_created_by_user_ip, media_created_by_user_hostname, media_created_by_user_agent, media_updated_datetime, media_updated_date_saying, media_updated_by_user_id, media_updated_by_user_alias, media_updated_by_user_email, media_updated_by_user_image_file, media_updated_by_user_ip, media_updated_by_user_hostname, media_updated_by_user_agent) 
							VALUES 
							(NULL, $get_current_space_id, $get_current_page_id, $inp_type_mysql, $inp_ext_mysql, '1', $inp_title_mysql, $inp_file_path_mysql, 0, '$datetime', '$date_saying', $my_user_id_mysql, $inp_my_alias_mysql, $inp_my_email_mysql, $inp_my_image_mysql, $my_ip_mysql, $my_hostname_mysql, $my_user_agent_mysql, '$datetime', '$date_saying', $my_user_id_mysql, $inp_my_alias_mysql, $inp_my_email_mysql, $inp_my_image_mysql, $my_ip_mysql, $my_hostname_mysql, $my_user_agent_mysql)")
							or die(mysqli_error($link));

							// Get ID
							$q = "SELECT media_id FROM $t_knowledge_pages_media WHERE media_created_datetime='$datetime' AND media_created_by_user_id=$my_user_id_mysql";
							$r = mysqli_query($link, $q);
							$rowb = mysqli_fetch_row($r);
							list($get_current_media_id) = $rowb;

							// Transfer
							$media_id = "$get_current_media_id";
						

							// Update values
							$inp_file_name = $get_current_media_id . "." . $extension;
							$inp_file_name_mysql = quote_smart($link, $inp_file_name);

							$inp_file_thumb_a = $get_current_media_id . "_thumb_800." . $extension;
							$inp_file_thumb_a_mysql = quote_smart($link, $inp_file_thumb_a);

							$inp_file_thumb_b = $get_current_media_id . "_thumb_100." . $extension;
							$inp_file_thumb_b_mysql = quote_smart($link, $inp_file_thumb_b);

							$result = mysqli_query($link, "UPDATE $t_knowledge_pages_media SET
									media_file_name=$inp_file_name_mysql,
									media_file_thumb_800=$inp_file_thumb_a_mysql,
									media_file_thumb_100=$inp_file_thumb_b_mysql
									 WHERE media_id=$get_current_media_id");


							if(move_uploaded_file($tmp_name, "../$inp_file_path/$inp_file_name")){
								
								// Header
								$ft = "success";
								$fm = "image_uploaded";

							} // move_uploaded_file
							else{
								$ft = "warning";
								$fm = "move_uploaded_file_failed";
							} // move_uploaded_file failed
						}  // if($width == "" OR $height == ""){
					} // image
					elseif($extension == "doc" OR $extension == "docx" OR $extension == "pdf" OR $extension == "txt" OR $extension == "xlsx") {
						
						if($extension == "doc" OR $extension == "docx") {
							$inp_type = "Word";
						}
						elseif($extension == "pdf") {
							$inp_type = "PDF";
						}
						elseif($extension == "xlsx") {
							$inp_type = "PDF";
						}
						else{
							$inp_type = "Text";
						}
						$inp_type_mysql = quote_smart($link, $inp_type);

						mysqli_query($link, "INSERT INTO $t_knowledge_pages_media
						(media_id, media_space_id, media_page_id, media_type, media_ext, media_version, media_title, media_file_path, media_unique_hits, media_created_datetime, media_created_date_saying, media_created_by_user_id, media_created_by_user_alias, media_created_by_user_email, media_created_by_user_image_file, media_created_by_user_ip, media_created_by_user_hostname, media_created_by_user_agent, media_updated_datetime, media_updated_date_saying, media_updated_by_user_id, media_updated_by_user_alias, media_updated_by_user_email, media_updated_by_user_image_file, media_updated_by_user_ip, media_updated_by_user_hostname, media_updated_by_user_agent) 
						VALUES 
						(NULL, $get_current_space_id, $get_current_page_id, $inp_type_mysql, $inp_ext_mysql, '1', $inp_title_mysql, $inp_file_path_mysql, 0, '$datetime', '$date_saying', $my_user_id_mysql, $inp_my_alias_mysql, $inp_my_email_mysql, $inp_my_image_mysql, $my_ip_mysql, $my_hostname_mysql, $my_user_agent_mysql, '$datetime', '$date_saying', $my_user_id_mysql, $inp_my_alias_mysql, $inp_my_email_mysql, $inp_my_image_mysql, $my_ip_mysql, $my_hostname_mysql, $my_user_agent_mysql)")
						or die(mysqli_error($link));

						// Get ID
						$q = "SELECT media_id FROM $t_knowledge_pages_media WHERE media_created_datetime='$datetime' AND media_created_by_user_id=$my_user_id_mysql";
						$r = mysqli_query($link, $q);
						$rowb = mysqli_fetch_row($r);
						list($get_current_media_id) = $rowb;
						
						// Transfer
						$media_id = "$get_current_media_id";

						// Update values
						$inp_file_name = $get_current_media_id . "." . $extension;
						$inp_file_name_mysql = quote_smart($link, $inp_file_name);

						$result = mysqli_query($link, "UPDATE $t_knowledge_pages_media SET
								media_file_name=$inp_file_name_mysql
								 WHERE media_id=$get_current_media_id");


						if(move_uploaded_file($tmp_name, "../$inp_file_path/$inp_file_name")){
							// Header
							$ft = "success";
							$fm = "document_$inp_file_name" . "_uploaded";
						}
						else{
							$ft = "warning";
							$fm = "move_uploaded_file_failed_for_docx_file";
						} // move_uploaded_file failed

					} // docx
					else{
						$ft = "warning";
						$fm = "unknown_file_format";
					}
				} // if($image){
				else{
					switch ($_FILES['inp_image']['error']) {
						case UPLOAD_ERR_OK:
								$fm = "photo_unknown_error";
								$ft = "warning";
								break;
						case UPLOAD_ERR_NO_FILE:
       								$fm = "no_file_selected";
								$ft = "warning";
								break;
						case UPLOAD_ERR_INI_SIZE:
           							$fm = "photo_exceeds_filesize";
								$ft = "warning";
								break;
						case UPLOAD_ERR_FORM_SIZE:
           							$fm_front = "photo_exceeds_filesize_form";
								$ft = "warning";
								break;
						default:
           							$fm_front = "unknown_upload_error";
								$ft = "warning";
								break;
					}


				} // else



				if($page_id_d != ""){
					$url = "media.php?space_id=$get_current_space_id&action=open_folder&mode=show_media_address&media_id=$media_id&page_id=$get_current_page_idv&page_id_a=$page_id_a&page_id_b=$page_id_b&page_id_c=$page_id_c&page_id_d=$page_id_d&l=$l&ft=$ft&fm=$fm";
				}
				if($page_id_c != ""){
					$url = "media.php?space_id=$get_current_space_id&action=open_folder&mode=show_media_address&media_id=$media_id&page_id=$get_current_page_idv&page_id_a=$page_id_a&page_id_b=$page_id_b&page_id_c=$page_id_c&l=$l&ft=$ft&fm=$fm";
				}
				if($page_id_b != ""){
					$url = "media.php?space_id=$get_current_space_id&action=open_folder&mode=show_media_address&media_id=$media_id&page_id=$get_current_page_idv&page_id_a=$page_id_a&page_id_b=$page_id_b&l=$l&ft=$ft&fm=$fm";
				}
				if($page_id_a != ""){
					$url = "media.php?space_id=$get_current_space_id&action=open_folder&mode=show_media_address&media_id=$media_id&page_id=$get_current_page_id&page_id_a=$page_id_a&l=$l&ft=$ft&fm=$fm";
				}
				else{
					$url = "media.php?space_id=$get_current_space_id&mode=show_media_address&media_id=$media_id&page_id=$get_current_page_id&l=$l&ft=$ft&fm=$fm";
				}
				header("Location: $url");
				exit;

			} // action == upload
			elseif($action == "open_folder"){
				if(isset($_GET['page_id_a'])) {
					$page_id_a = $_GET['page_id_a'];
					$page_id_a = stripslashes(strip_tags($page_id_a));
				}
				else{
					$page_id_a = "";
				}
				if(isset($_GET['page_id_b'])) {
					$page_id_b = $_GET['page_id_b'];
					$page_id_b = stripslashes(strip_tags($page_id_b));
				}
				else{
					$page_id_b = "";
				}
				if(isset($_GET['page_id_c'])) {
					$page_id_c = $_GET['page_id_c'];
					$page_id_c = stripslashes(strip_tags($page_id_c));
				}
				else{
					$page_id_c = "";
				}
				if(isset($_GET['page_id_d'])) {
					$page_id_d = $_GET['page_id_d'];
					$page_id_d = stripslashes(strip_tags($page_id_d));
				}
				else{
					$page_id_d = "";
				}
				if(isset($_GET['media_id'])) {
					$media_id = $_GET['media_id'];
					$media_id = stripslashes(strip_tags($media_id));
				}
				else{
					$media_id = "";
				}


				$query = "SELECT page_id, page_space_id, page_title, page_title_clean, page_description, page_text, page_parent_id, page_weight, page_allow_comments, page_no_of_comments, page_unique_hits, page_unique_hits_ip_block, page_unique_hits_user_id_block, page_created_datetime, page_created_date_saying, page_created_user_id, page_created_user_alias, page_created_user_image, page_updated_datetime, page_updated_date_saying, page_updated_user_id, page_updated_user_alias, page_updated_user_image FROM $t_knowledge_pages_index WHERE page_id=$page_id_mysql AND page_space_id=$get_current_space_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_page_id, $get_current_page_space_id, $get_current_page_title, $get_current_page_title_clean, $get_current_page_description, $get_current_page_text, $get_current_page_parent_id, $get_current_page_weight, $get_current_page_allow_comments, $get_current_page_no_of_comments, $get_current_page_unique_hits, $get_current_page_unique_hits_ip_block, $get_current_page_unique_hits_user_id_block, $get_current_page_created_datetime, $get_current_page_created_date_saying, $get_current_page_created_user_id, $get_current_page_created_user_alias, $get_current_page_created_user_image, $get_current_page_updated_datetime, $get_current_page_updated_date_saying, $get_current_page_updated_user_id, $get_current_page_updated_user_alias, $get_current_page_updated_user_image) = $row;

				if($get_current_page_id == ""){
					echo"
					<h1>$l_image_gallery</h1>
					<p>Page A not found.</p>
					<p><a href=\"media.php?space_id=$get_current_space_id&amp;l=$l\">Gallery</a></p>
					";
				}
				else{
					// Missing page_id 
					if($get_current_page_parent_id != "0"){
						// Find parent
						$query = "SELECT page_id, page_space_id, page_title, page_parent_id FROM $t_knowledge_pages_index WHERE page_id=$get_current_page_parent_id AND page_space_id=$get_current_space_id";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_parent_a_page_id, $get_parent_a_page_space_id, $get_parent_a_page_title, $get_parent_a_page_parent_id) = $row;


						if($get_parent_a_page_parent_id != "0" && $get_parent_a_page_parent_id != ""){
							// Find parent
							$query = "SELECT page_id, page_space_id, page_title, page_parent_id FROM $t_knowledge_pages_index WHERE page_id=$get_parent_a_page_parent_id AND page_space_id=$get_current_space_id";
							$result = mysqli_query($link, $query);
							$row = mysqli_fetch_row($result);
							list($get_parent_b_page_id, $get_parent_b_page_space_id, $get_parent_b_page_title, $get_parent_b_page_parent_id) = $row;


							if($get_parent_b_page_parent_id != "0"){
								// Find parent
								$query = "SELECT page_id, page_space_id, page_title, page_parent_id FROM $t_knowledge_pages_index WHERE page_id=$get_parent_b_page_parent_id AND page_space_id=$get_current_space_id";
								$result = mysqli_query($link, $query);
								$row = mysqli_fetch_row($result);
								list($get_parent_c_page_id, $get_parent_c_page_space_id, $get_parent_c_page_title, $get_parent_c_page_parent_id) = $row;

								// echo"<a href=\"view_page.php?space_id=$get_current_page_space_id&amp;page_id=$get_parent_c_page_id&amp;l=$l\">$get_parent_c_page_title</a>
							}
						}				
					}
					if($page_id_c == "" && isset($get_parent_b_page_id) && $get_parent_b_page_id != ""){
						$page_id_a = "$get_parent_b_page_id";
						$page_id_b = "$get_parent_a_page_id";
						$page_id_c = "$get_current_page_id";
					}
					elseif($page_id_b == "" && isset($get_parent_a_page_id) && $get_parent_a_page_id != ""){
						$page_id_a = "$get_parent_a_page_id";
						$page_id_b = "$get_current_page_id";
					}


					// Start open gallery
					echo"
					<h1>$l_image_gallery</h1>

					
						
					

					<!-- Folders -->
						<div class=\"image_gallery_folders_list\">
							<table>
							 <tr>
							  <td style=\"padding: 0px 6px 6px 0px;\">
								<a href=\"media.php?space_id=$get_current_space_id&amp;l=$l\"><img src=\"_gfx/icons/format-justify-fill.png\" alt=\"format-justify-fill.png\" /></a>
							  </td>
							  <td style=\"padding: 0px 0px 6px 0px;\">
								<a href=\"media.php?space_id=$get_current_space_id&amp;l=$l\">$get_current_space_title</a>
							  </td>
							 </tr>
							</table>
							";
							$query = "SELECT page_id, page_title, page_no_of_children, page_weight FROM $t_knowledge_pages_index WHERE page_space_id=$get_current_space_id AND page_parent_id='0' ORDER BY page_weight ASC";
							$result = mysqli_query($link, $query);
							while($row = mysqli_fetch_row($result)) {
								list($get_page_id_a, $get_page_title_a, $get_page_no_of_children_a, $get_page_weight_a) = $row;

								$title_len = strlen($get_page_title_a);
								if($title_len > 20){
									$get_page_title_a = substr($get_page_title_a, 0, 20);
									$get_page_title_a = $get_page_title_a . "...";
								}
								echo"
								<table>
								 <tr>
								  <td style=\"padding: 0px 6px 6px 0px;\">
									<a href=\"media.php?space_id=$get_current_space_id&amp;action=open_folder&amp;page_id=$get_page_id_a&amp;page_id_a=$get_page_id_a&amp;l=$l\"><img src=\"_gfx/icons/"; if($get_page_id_a == "$page_id_a"){ echo"folder-open.png"; } else{ echo"folder.png"; }echo"\" alt=\"folder.png\" /></a>
								  </td>
								  <td style=\"padding: 0px 0px 6px 0px;\">
									<a href=\"media.php?space_id=$get_current_space_id&amp;action=open_folder&amp;page_id=$get_page_id_a&amp;page_id_a=$get_page_id_a&amp;l=$l\""; if($get_page_id_a == "$page_id"){ echo" style=\"font-weight:bold;\""; } echo">$get_page_title_a</a>
								  </td>
								 </tr>
								</table>
								";
								if($get_page_id_a == "$page_id_a"){
									$query_b = "SELECT page_id, page_title, page_no_of_children, page_weight FROM $t_knowledge_pages_index WHERE page_space_id=$get_current_space_id AND page_parent_id=$get_page_id_a ORDER BY page_weight ASC";
									$result_b = mysqli_query($link, $query_b);
									while($row_b = mysqli_fetch_row($result_b)) {
										list($get_page_id_b, $get_page_title_b, $get_page_no_of_children_b, $get_page_weight_b) = $row_b;

										$title_len = strlen($get_page_title_b);
										if($title_len > 20){
											$get_page_title_b = substr($get_page_title_b, 0, 20);
											$get_page_title_b = $get_page_title_b . "...";
										}

										echo"
										<table>
										 <tr>
										  <td style=\"padding: 0px 6px 6px 15px;\">
											<a href=\"media.php?space_id=$get_current_space_id&amp;action=open_folder&amp;page_id=$get_page_id_b&amp;page_id_a=$get_page_id_a&amp;page_id_b=$get_page_id_b&amp;l=$l\"><img src=\"_gfx/icons/"; if($get_page_id_b == "$page_id_b"){ echo"folder-open.png"; } else{ echo"folder.png"; }echo"\" alt=\"folder.png\" /></a>
										  </td>
										  <td style=\"padding: 0px 0px 6px 0px;\">
											<a href=\"media.php?space_id=$get_current_space_id&amp;action=open_folder&amp;page_id=$get_page_id_b&amp;page_id_a=$get_page_id_a&amp;page_id_b=$get_page_id_b&amp;l=$l\""; if($get_page_id_b == "$page_id"){ echo" style=\"font-weight:bold;\""; } echo">$get_page_title_b</a>
										  </td>
										 </tr>
										</table>
										";

										if($get_page_id_b == "$page_id_b"){
											$query_c = "SELECT page_id, page_title, page_no_of_children, page_weight FROM $t_knowledge_pages_index WHERE page_space_id=$get_current_space_id AND page_parent_id=$get_page_id_b ORDER BY page_weight ASC";
											$result_c = mysqli_query($link, $query_c);
											while($row_c = mysqli_fetch_row($result_c)) {
												list($get_page_id_c, $get_page_title_c, $get_page_no_of_children_c, $get_page_weight_c) = $row_c;

												$title_len = strlen($get_page_title_c);
												if($title_len > 20){
													$get_page_title_c = substr($get_page_title_c, 0, 20);
													$get_page_title_c = $get_page_title_c . "...";
												}

												echo"
												<table>
												 <tr>
												  <td style=\"padding: 0px 6px 6px 30px;\">
													<a href=\"media.php?space_id=$get_current_space_id&amp;action=open_folder&amp;page_id=$get_page_id_c&amp;page_id_a=$get_page_id_a&amp;page_id_b=$get_page_id_b&amp;page_id_c=$get_page_id_c&amp;l=$l\"><img src=\"_gfx/icons/"; if($get_page_id_c == "$page_id_c"){ echo"folder-open.png"; } else{ echo"folder.png"; }echo"\" alt=\"folder.png\" /></a>
												  </td>
												  <td style=\"padding: 0px 0px 6px 0px;\">
													<a href=\"media.php?space_id=$get_current_space_id&amp;action=open_folder&amp;page_id=$get_page_id_c&amp;page_id_a=$get_page_id_a&amp;page_id_b=$get_page_id_b&amp;page_id_c=$get_page_id_c&amp;l=$l\""; if($get_page_id_c == "$page_id"){ echo" style=\"font-weight:bold;\""; } echo">$get_page_title_c</a>
												  </td>
												 </tr>
												</table>
												";
											} // while c
										} // open b

									} // while b
								} // open a
							} // while a
							echo"
						</div>
					<!-- //Folders -->

					<!-- Folder browse -->
						<div class=\"image_gallery_folder_browse\">

							<h2>$get_current_page_title</h2>

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

							<!-- Upload form -->
								<form method=\"POST\" action=\"media.php?space_id=$get_current_space_id&amp;action=upload_image&amp;page_id=$get_current_page_id&amp;page_id_a=$page_id_a";
								if($page_id_b != ""){
									echo"&amp;page_id_b=$page_id_b";
								}
								if($page_id_c != ""){
									echo"&amp;page_id_c=$page_id_c";
								}
								if($page_id_d != ""){
									echo"&amp;page_id_d=$page_id_d";
								}
								echo"&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
								<p>
								<input name=\"inp_image\" type=\"file\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
								<input type=\"submit\" value=\"$l_upload\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
								</p>
								</form>
							<!-- //Upload form -->
							";
							$x = 0;
							$query = "SELECT media_id, media_space_id, media_page_id, media_type, media_ext, media_version, media_title, media_file_path, media_file_name, media_file_thumb_800, media_file_thumb_100, media_unique_hits, media_unique_hits_ip_block, media_unique_hits_user_id_block, media_created_datetime, media_created_date_saying, media_created_by_user_id, media_created_by_user_alias, media_created_by_user_email, media_created_by_user_image_file, media_created_by_user_ip, media_created_by_user_hostname, media_created_by_user_agent, media_updated_datetime, media_updated_date_saying, media_updated_by_user_id, media_updated_by_user_alias, media_updated_by_user_email, media_updated_by_user_image_file, media_updated_by_user_ip, media_updated_by_user_hostname, media_updated_by_user_agent FROM $t_knowledge_pages_media WHERE media_space_id=$get_current_space_id AND media_page_id=$get_current_page_id ORDER BY media_id DESC";
							$result = mysqli_query($link, $query);
							while($row = mysqli_fetch_row($result)) {
								list($get_media_id, $get_media_space_id, $get_media_page_id, $get_media_type, $get_media_ext, $get_media_version, $get_media_title, $get_media_file_path, $get_media_file_name, $get_media_file_thumb_800, $get_media_file_thumb_100, $get_media_unique_hits, $get_media_unique_hits_ip_block, $get_media_unique_hits_user_id_block, $get_media_created_datetime, $get_media_created_date_saying, $get_media_created_by_user_id, $get_media_created_by_user_alias, $get_media_created_by_user_email, $get_media_created_by_user_image_file, $get_media_created_by_user_ip, $get_media_created_by_user_hostname, $get_media_created_by_user_agent, $get_media_updated_datetime, $get_media_updated_date_saying, $get_media_updated_by_user_id, $get_media_updated_by_user_alias, $get_media_updated_by_user_email, $get_media_updated_by_user_image_file, $get_media_updated_by_user_ip, $get_media_updated_by_user_hostname, $get_media_updated_by_user_agent) = $row;

								// Look for image
								if(!(file_exists("$root/$get_media_file_path/$get_media_file_name")) OR $get_media_file_name == ""){
									echo"<div class=\"info\"><p>Image doesnt exists. Deleting database reference.</p></div>\n";
									$result_delete = mysqli_query($link, "DELETE FROM $t_knowledge_pages_media WHERE media_id=$get_media_id");
								}
	
								// Look for thumb
								if($get_media_type == "image" && !(file_exists("$root/$get_media_file_path/$get_media_file_thumb_100")) && $get_media_file_thumb_100 != ""){
									resize_crop_image(100, 100, "$root/$get_media_file_path/$get_media_file_name", "$root/$get_media_file_path/$get_media_file_thumb_100");
								}

								// Layout
								if($x == 0){
									echo"
									<div class=\"image_gallery_folder_browse_row\">
									";
								}
			
								// Title
								$title_len = strlen($get_media_title);
								if($title_len > 15){
									$get_media_title = substr($get_media_title, 0, 15);
								}
								
								// View image url
								$view_image_url = "media.php?space_id=$get_current_space_id&amp;action=view_media&amp;page_id=$get_current_page_id&amp;media_id=$get_media_id&amp;page_id_a=$page_id_a";
								if($page_id_b != ""){
									$view_image_url = $view_image_url . "&amp;page_id_b=$page_id_b";
								}
								if($page_id_c != ""){
									$view_image_url = $view_image_url . "&amp;page_id_c=$page_id_c";
								}
								if($page_id_d != ""){
									$view_image_url = $view_image_url . "&amp;page_id_d=$page_id_d";
								}
								$view_image_url = $view_image_url . "&amp;l=$l";

								echo"
									<div class=\"image_gallery_folder_browse_col\">
										<p>
										<a href=\"$view_image_url\">";
										if($get_media_type == "image"){
											echo"<img src=\"$root/$get_media_file_path/$get_media_file_thumb_100\" alt=\"$get_media_file_thumb_100\" />";
										}
										else{
											echo"<img src=\"_gfx/icons/100x100/$get_media_ext.png\" alt=\"_gfx/icons/100x100/$get_media_ext.png\" />";
										}
										echo"</a><br />
										<a href=\"$view_image_url\">$get_media_title</a>
										</p>

										";
										if($mode == "show_media_address" && $get_media_id == "$media_id"){

											echo"
											<form>
											<script>
											\$(document).ready(function(){
												\$('[name=\"inp_copy\"]').focus().select();
											});
											</script>
									
											<p><b>$l_url:</b><br />
											<input type=\"text\" name=\"inp_copy\" value=\"$root/$get_media_file_path/$get_media_file_name\" size=\"25\" style=\"width: 100%;border: #fff 1px solid;border-bottom: #ccc 1px dashed;\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
											</p>
											</form>
											";
										}
										echo"
									</div>
								";

								// Layout
								if($x == 3){
									echo"
									</div>
									";
									$x = -1;
								}
								$x++;
							}
							if($x != 0){
								echo"
								</div>
								";
							}
							echo"
						</div>
					<!-- //Folder browse -->
					";
				} // page a found
			} // action == "open_page_a"
			elseif($action == "view_media"){
				if(isset($_GET['page_id_a'])) {
					$page_id_a = $_GET['page_id_a'];
					$page_id_a = stripslashes(strip_tags($page_id_a));
				}
				else{
					$page_id_a = "";
				}
				if(isset($_GET['page_id_b'])) {
					$page_id_b = $_GET['page_id_b'];
					$page_id_b = stripslashes(strip_tags($page_id_b));
				}
				else{
					$page_id_b = "";
				}
				if(isset($_GET['page_id_c'])) {
					$page_id_c = $_GET['page_id_c'];
					$page_id_c = stripslashes(strip_tags($page_id_c));
				}
				else{
					$page_id_c = "";
				}
				if(isset($_GET['page_id_d'])) {
					$page_id_d = $_GET['page_id_d'];
					$page_id_d = stripslashes(strip_tags($page_id_d));
				}
				else{
					$page_id_d = "";
				}
				if(isset($_GET['media_id'])) {
					$media_id = $_GET['media_id'];
					$media_id = stripslashes(strip_tags($media_id));
				}
				else{
					$media_id = "";
				}
				$media_id_mysql = quote_smart($link, $media_id);

				$query = "SELECT page_id, page_space_id, page_title, page_title_clean, page_description, page_text, page_parent_id, page_weight, page_allow_comments, page_no_of_comments, page_unique_hits, page_unique_hits_ip_block, page_unique_hits_user_id_block, page_created_datetime, page_created_date_saying, page_created_user_id, page_created_user_alias, page_created_user_image, page_updated_datetime, page_updated_date_saying, page_updated_user_id, page_updated_user_alias, page_updated_user_image FROM $t_knowledge_pages_index WHERE page_id=$page_id_mysql AND page_space_id=$get_current_space_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_page_id, $get_current_page_space_id, $get_current_page_title, $get_current_page_title_clean, $get_current_page_description, $get_current_page_text, $get_current_page_parent_id, $get_current_page_weight, $get_current_page_allow_comments, $get_current_page_no_of_comments, $get_current_page_unique_hits, $get_current_page_unique_hits_ip_block, $get_current_page_unique_hits_user_id_block, $get_current_page_created_datetime, $get_current_page_created_date_saying, $get_current_page_created_user_id, $get_current_page_created_user_alias, $get_current_page_created_user_image, $get_current_page_updated_datetime, $get_current_page_updated_date_saying, $get_current_page_updated_user_id, $get_current_page_updated_user_alias, $get_current_page_updated_user_image) = $row;
				if($get_current_page_id == ""){
					$get_current_page_id = 0;
				}
			

				echo"
				<h1>$l_media</h1>

				<!-- Folders -->
						<div class=\"image_gallery_folders_list\">
							<table>
							 <tr>
							  <td style=\"padding: 0px 6px 6px 0px;\">
								<a href=\"media.php?space_id=$get_current_space_id&amp;l=$l\"><img src=\"_gfx/icons/format-justify-fill.png\" alt=\"format-justify-fill.png\" /></a>
							  </td>
							  <td style=\"padding: 0px 0px 6px 0px;\">
								<a href=\"media.php?space_id=$get_current_space_id&amp;l=$l\">$get_current_space_title</a>
							  </td>
							 </tr>
							</table>
							";
							$query = "SELECT page_id, page_title, page_no_of_children, page_weight FROM $t_knowledge_pages_index WHERE page_space_id=$get_current_space_id AND page_parent_id='0' ORDER BY page_weight ASC";
							$result = mysqli_query($link, $query);
							while($row = mysqli_fetch_row($result)) {
								list($get_page_id_a, $get_page_title_a, $get_page_no_of_children_a, $get_page_weight_a) = $row;
								echo"
								<table>
								 <tr>
								  <td style=\"padding: 0px 6px 6px 0px;\">
									<a href=\"media.php?space_id=$get_current_space_id&amp;action=open_folder&amp;page_id=$get_page_id_a&amp;page_id_a=$get_page_id_a&amp;l=$l\"><img src=\"_gfx/icons/"; if($get_page_id_a == "$page_id_a"){ echo"folder-open.png"; } else{ echo"folder.png"; }echo"\" alt=\"folder.png\" /></a>
								  </td>
								  <td style=\"padding: 0px 0px 6px 0px;\">
									<a href=\"media.php?space_id=$get_current_space_id&amp;action=open_folder&amp;page_id=$get_page_id_a&amp;page_id_a=$get_page_id_a&amp;l=$l\""; if($get_page_id_a == "$page_id"){ echo" style=\"font-weight:bold;\""; } echo">$get_page_title_a</a>
								  </td>
								 </tr>
								</table>
								";
								if($get_page_id_a == "$page_id_a"){
									$query_b = "SELECT page_id, page_title, page_no_of_children, page_weight FROM $t_knowledge_pages_index WHERE page_space_id=$get_current_space_id AND page_parent_id=$get_page_id_a ORDER BY page_weight ASC";
									$result_b = mysqli_query($link, $query_b);
									while($row_b = mysqli_fetch_row($result_b)) {
										list($get_page_id_b, $get_page_title_b, $get_page_no_of_children_b, $get_page_weight_b) = $row_b;
										echo"
										<table>
										 <tr>
										  <td style=\"padding: 0px 6px 6px 15px;\">
											<a href=\"media.php?space_id=$get_current_space_id&amp;action=open_folder&amp;page_id=$get_page_id_b&amp;page_id_a=$get_page_id_a&amp;page_id_b=$get_page_id_b&amp;l=$l\"><img src=\"_gfx/icons/"; if($get_page_id_b == "$page_id_b"){ echo"folder-open.png"; } else{ echo"folder.png"; }echo"\" alt=\"folder.png\" /></a>
										  </td>
										  <td style=\"padding: 0px 0px 6px 0px;\">
											<a href=\"media.php?space_id=$get_current_space_id&amp;action=open_folder&amp;page_id=$get_page_id_b&amp;page_id_a=$get_page_id_a&amp;page_id_b=$get_page_id_b&amp;l=$l\""; if($get_page_id_b == "$page_id"){ echo" style=\"font-weight:bold;\""; } echo">$get_page_title_b</a>
										  </td>
										 </tr>
										</table>
										";

										if($get_page_id_b == "$page_id_b"){
											$query_c = "SELECT page_id, page_title, page_no_of_children, page_weight FROM $t_knowledge_pages_index WHERE page_space_id=$get_current_space_id AND page_parent_id=$get_page_id_b ORDER BY page_weight ASC";
											$result_c = mysqli_query($link, $query_c);
											while($row_c = mysqli_fetch_row($result_c)) {
												list($get_page_id_c, $get_page_title_c, $get_page_no_of_children_c, $get_page_weight_c) = $row_c;
												echo"
												<table>
												 <tr>
												  <td style=\"padding: 0px 6px 6px 30px;\">
													<a href=\"media.php?space_id=$get_current_space_id&amp;action=open_folder&amp;page_id=$get_page_id_c&amp;page_id_a=$get_page_id_a&amp;page_id_b=$get_page_id_b&amp;page_id_c=$get_page_id_c&amp;l=$l\"><img src=\"_gfx/icons/"; if($get_page_id_c == "$page_id_c"){ echo"folder-open.png"; } else{ echo"folder.png"; }echo"\" alt=\"folder.png\" /></a>
												  </td>
												  <td style=\"padding: 0px 0px 6px 0px;\">
													<a href=\"media.php?space_id=$get_current_space_id&amp;action=open_folder&amp;page_id=$get_page_id_c&amp;page_id_a=$get_page_id_a&amp;page_id_b=$get_page_id_b&amp;page_id_c=$get_page_id_c&amp;l=$l\""; if($get_page_id_c == "$page_id"){ echo" style=\"font-weight:bold;\""; } echo">$get_page_title_c</a>
												  </td>
												 </tr>
												</table>
												";
											} // while c
										} // open b

									} // while b
								} // open a
							} // while a
							echo"
						</div>
				<!-- //Folders -->

				<!-- Folder browse -->
						<div class=\"image_gallery_folder_browse\">


							<!-- View image -->
								";


								$query = "SELECT media_id, media_space_id, media_page_id, media_type, media_ext, media_version, media_title, media_file_path, media_file_name, media_file_thumb_800, media_file_thumb_100, media_unique_hits, media_unique_hits_ip_block, media_unique_hits_user_id_block, media_created_datetime, media_created_date_saying, media_created_by_user_id, media_created_by_user_alias, media_created_by_user_email, media_created_by_user_image_file, media_created_by_user_ip, media_created_by_user_hostname, media_created_by_user_agent, media_updated_datetime, media_updated_date_saying, media_updated_by_user_id, media_updated_by_user_alias, media_updated_by_user_email, media_updated_by_user_image_file, media_updated_by_user_ip, media_updated_by_user_hostname, media_updated_by_user_agent FROM $t_knowledge_pages_media WHERE media_id=$media_id_mysql AND media_space_id=$get_current_space_id AND media_page_id=$get_current_page_id";
								$result = mysqli_query($link, $query);
								$row = mysqli_fetch_row($result);
								list($get_current_media_id, $get_current_media_space_id, $get_current_media_page_id, $get_current_media_type, $get_current_media_ext, $get_current_media_version, $get_current_media_title, $get_current_media_file_path, $get_current_media_file_name, $get_current_media_file_thumb_800, $get_current_media_file_thumb_100, $get_current_media_unique_hits, $get_current_media_unique_hits_ip_block, $get_current_media_unique_hits_user_id_block, $get_current_media_created_datetime, $get_current_media_created_date_saying, $get_current_media_created_by_user_id, $get_current_media_created_by_user_alias, $get_current_media_created_by_user_email, $get_current_media_created_by_user_image_file, $get_current_media_created_by_user_ip, $get_current_media_created_by_user_hostname, $get_current_media_created_by_user_agent, $get_current_media_updated_datetime, $get_current_media_updated_date_saying, $get_current_media_updated_by_user_id, $get_current_media_updated_by_user_alias, $get_current_media_updated_by_user_email, $get_current_media_updated_by_user_image_file, $get_current_media_updated_by_user_ip, $get_current_media_updated_by_user_hostname, $get_current_media_updated_by_user_agent) = $row;
								if($get_current_media_id == ""){
									echo"<p>Image not found</p>";
								}
								else{
								
									// Look for thumb
									if($get_current_media_type == "image" && !(file_exists("$root/$get_current_media_file_path/$get_current_media_file_thumb_100")) && $get_current_media_file_thumb_100 != ""){
										resize_crop_image(100, 100, "$root/$get_current_media_file_path/$get_current_media_file_name", "$root/$get_current_media_file_path/$get_current_media_file_thumb_100");
									}



									echo"
									<h2>$get_current_media_title</h2>
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

									
									<script>
									\$(document).ready(function(){
										\$('[name=\"inp_copy\"]').focus().select();
									});
									</script>
									<p>
									<a href=\"$root/$get_current_media_file_path/$get_current_media_file_name\">";
									if($get_current_media_type == "image"){
										echo"<img src=\"$root/$get_current_media_file_path/$get_current_media_file_thumb_100\" alt=\"$get_current_media_file_thumb_100\" />";
									}
									else{
										echo"<img src=\"_gfx/icons/100x100/$get_current_media_ext\" alt=\"_gfx/icons/100x100/$get_current_media_ext\" />";
									}
									echo"</a><br />
									</p>

									<p><b>$l_url:</b><br />
									<input type=\"text\" name=\"inp_copy\" value=\"$root/$get_current_media_file_path/$get_current_media_file_name\" size=\"25\" style=\"width: 100%;border: #fff 1px solid;border-bottom: #ccc 1px dashed;\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
									</p>
									

									<!-- Form -->
										";

										// Action image url
										$action_edit = "media.php?space_id=$get_current_space_id&amp;action=edit_media&amp;page_id=$get_current_page_id&amp;media_id=$get_current_media_id&amp;page_id_a=$page_id_a";
										$action_rotate = "media.php?space_id=$get_current_space_id&amp;action=rotate_image&amp;page_id=$get_current_page_id&amp;media_id=$get_current_media_id&amp;page_id_a=$page_id_a";
										$action_delete = "media.php?space_id=$get_current_space_id&amp;action=delete_media&amp;page_id=$get_current_page_id&amp;media_id=$get_current_media_id&amp;page_id_a=$page_id_a";
										if($page_id_b != ""){
											$action_edit = $action_edit . "&amp;page_id_b=$page_id_b";
											$action_rotate = $action_rotate . "&amp;page_id_b=$page_id_b";
											$action_delete = $action_delete . "&amp;page_id_b=$page_id_b";
										}
										if($page_id_c != ""){
											$action_edit = $action_edit . "&amp;page_id_c=$page_id_c";
											$action_rotate = $action_rotate . "&amp;page_id_c=$page_id_c";
											$action_delete = $action_delete . "&amp;page_id_c=$page_id_c";
										}
										if($page_id_d != ""){
											$action_edit = $action_edit . "&amp;page_id_d=$page_id_d";
											$action_rotate = $action_rotate . "&amp;page_id_d=$page_id_d";
											$action_delete = $action_delete . "&amp;page_id_d=$page_id_d";
										}
										$action_edit = $action_edit . "&amp;l=$l";
										$action_rotate = $action_rotate . "&amp;l=$l&amp;process=1";
										$action_delete = $action_delete . "&amp;l=$l";

										echo"
										<form method=\"POST\" action=\"$action_edit&amp;process=1\" enctype=\"multipart/form-data\">
										<p><b>Title:</b><br />
										<input type=\"text\" name=\"inp_title\" value=\"$get_current_media_title\" size=\"25\" style=\"width: 100%;\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
										</p>
											  
										<p>
										<input type=\"submit\" value=\"$l_save_changes\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
										";
										if($get_current_media_type == "image"){
											echo"
											<a href=\"$action_rotate\" class=\"btn_default\">$l_rotate_image</a>";
										}
										echo"
										<a href=\"$action_delete\" class=\"btn_warning\">$l_delete</a>
										</p>
										</form>
									<!-- //Form -->
									";
								}
								echo"
							<!-- //View image -->
						</div>
				<!-- //Folder browse -->
				";
			} // action == "view media"
			elseif($action == "edit_media"){
				if(isset($_GET['page_id'])) {
					$page_id = $_GET['page_id'];
					$page_id = stripslashes(strip_tags($page_id));
				}
				else{
					$page_id = "";
				}
				$page_id_mysql = quote_smart($link, $page_id);

				if(isset($_GET['page_id_a'])) {
					$page_id_a = $_GET['page_id_a'];
					$page_id_a = stripslashes(strip_tags($page_id_a));
				}
				else{
					$page_id_a = "";
				}
				if(isset($_GET['page_id_b'])) {
					$page_id_b = $_GET['page_id_b'];
					$page_id_b = stripslashes(strip_tags($page_id_b));
				}
				else{
					$page_id_b = "";
				}
				if(isset($_GET['page_id_c'])) {
					$page_id_c = $_GET['page_id_c'];
					$page_id_c = stripslashes(strip_tags($page_id_c));
				}
				else{
					$page_id_c = "";
				}
				if(isset($_GET['page_id_d'])) {
					$page_id_d = $_GET['page_id_d'];
					$page_id_d = stripslashes(strip_tags($page_id_d));
				}
				else{
					$page_id_d = "";
				}
				if(isset($_GET['media_id'])) {
					$media_id = $_GET['media_id'];
					$media_id = stripslashes(strip_tags($media_id));
				}
				else{
					$media_id = "";
				}
				$media_id_mysql = quote_smart($link, $media_id);



				$query = "SELECT media_id, media_space_id, media_page_id, media_type, media_ext, media_version, media_title, media_file_path, media_file_name, media_file_thumb_800, media_file_thumb_100, media_unique_hits, media_unique_hits_ip_block, media_unique_hits_user_id_block, media_created_datetime, media_created_date_saying, media_created_by_user_id, media_created_by_user_alias, media_created_by_user_email, media_created_by_user_image_file, media_created_by_user_ip, media_created_by_user_hostname, media_created_by_user_agent, media_updated_datetime, media_updated_date_saying, media_updated_by_user_id, media_updated_by_user_alias, media_updated_by_user_email, media_updated_by_user_image_file, media_updated_by_user_ip, media_updated_by_user_hostname, media_updated_by_user_agent FROM $t_knowledge_pages_media WHERE media_id=$media_id_mysql AND media_space_id=$get_current_space_id AND media_page_id=$page_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_media_id, $get_current_media_space_id, $get_current_media_page_id, $get_current_media_type, $get_current_media_ext, $get_current_media_version, $get_current_media_title, $get_current_media_file_path, $get_current_media_file_name, $get_current_media_file_thumb_800, $get_current_media_file_thumb_100, $get_current_media_unique_hits, $get_current_media_unique_hits_ip_block, $get_current_media_unique_hits_user_id_block, $get_current_media_created_datetime, $get_current_media_created_date_saying, $get_current_media_created_by_user_id, $get_current_media_created_by_user_alias, $get_current_media_created_by_user_email, $get_current_media_created_by_user_image_file, $get_current_media_created_by_user_ip, $get_current_media_created_by_user_hostname, $get_current_media_created_by_user_agent, $get_current_media_updated_datetime, $get_current_media_updated_date_saying, $get_current_media_updated_by_user_id, $get_current_media_updated_by_user_alias, $get_current_media_updated_by_user_email, $get_current_media_updated_by_user_image_file, $get_current_media_updated_by_user_ip, $get_current_media_updated_by_user_hostname, $get_current_media_updated_by_user_agent) = $row;
				if($get_current_media_id == ""){
					echo"<p>Image not found</p>";
				}
				else{
								
					$inp_title = $_POST['inp_title'];
					$inp_title = output_html($inp_title);
					$inp_title_mysql = quote_smart($link, $inp_title);

					$result_update = mysqli_query($link, "UPDATE $t_knowledge_pages_media SET media_title=$inp_title_mysql WHERE media_id=$get_current_media_id") or die(mysqli_error($link));

					$url = "media.php?space_id=$get_current_space_id&action=view_media&page_id=$page_id&media_id=$get_current_media_id&page_id_a=$page_id_a&l=$l&ft=success&fm=changes_saved";
					if($page_id_d != ""){
						$url = $url  . "&page_id_d=$page_id_d";
					}
					if($page_id_c != ""){
						$url = $url  . "&page_id_c=$page_id_c";
					}
					if($page_id_b != ""){
						$url = $url  . "&page_id_b=$page_id_b";
					}
					header("Location: $url");
					exit;
				} // image found
			} // action == "edit_media"
			elseif($action == "delete_media"){

				if(isset($_GET['page_id'])) {
					$page_id = $_GET['page_id'];
					$page_id = stripslashes(strip_tags($page_id));
				}
				else{
					$page_id = "";
				}
				$page_id_mysql = quote_smart($link, $page_id);



				if(isset($_GET['page_id_a'])) {
					$page_id_a = $_GET['page_id_a'];
					$page_id_a = stripslashes(strip_tags($page_id_a));
				}
				else{
					$page_id_a = "";
				}
				if(isset($_GET['page_id_b'])) {
					$page_id_b = $_GET['page_id_b'];
					$page_id_b = stripslashes(strip_tags($page_id_b));
				}
				else{
					$page_id_b = "";
				}
				if(isset($_GET['page_id_c'])) {
					$page_id_c = $_GET['page_id_c'];
					$page_id_c = stripslashes(strip_tags($page_id_c));
				}
				else{
					$page_id_c = "";
				}
				if(isset($_GET['page_id_d'])) {
					$page_id_d = $_GET['page_id_d'];
					$page_id_d = stripslashes(strip_tags($page_id_d));
				}
				else{
					$page_id_d = "";
				}

				if(isset($_GET['media_id'])) {
					$media_id = $_GET['media_id'];
					$media_id = stripslashes(strip_tags($media_id));
				}
				else{
					$media_id = "";
				}
				$media_id_mysql = quote_smart($link, $media_id);

				$query = "SELECT page_id, page_space_id, page_title, page_title_clean, page_description, page_text, page_parent_id, page_weight, page_allow_comments, page_no_of_comments, page_unique_hits, page_unique_hits_ip_block, page_unique_hits_user_id_block, page_created_datetime, page_created_date_saying, page_created_user_id, page_created_user_alias, page_created_user_image, page_updated_datetime, page_updated_date_saying, page_updated_user_id, page_updated_user_alias, page_updated_user_image FROM $t_knowledge_pages_index WHERE page_id=$page_id_mysql AND page_space_id=$get_current_space_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_page_id, $get_current_page_space_id, $get_current_page_title, $get_current_page_title_clean, $get_current_page_description, $get_current_page_text, $get_current_page_parent_id, $get_current_page_weight, $get_current_page_allow_comments, $get_current_page_no_of_comments, $get_current_page_unique_hits, $get_current_page_unique_hits_ip_block, $get_current_page_unique_hits_user_id_block, $get_current_page_created_datetime, $get_current_page_created_date_saying, $get_current_page_created_user_id, $get_current_page_created_user_alias, $get_current_page_created_user_image, $get_current_page_updated_datetime, $get_current_page_updated_date_saying, $get_current_page_updated_user_id, $get_current_page_updated_user_alias, $get_current_page_updated_user_image) = $row;

				if($get_current_page_id == ""){
					$get_current_page_id = 0;
				}
				

				$query = "SELECT media_id, media_space_id, media_page_id, media_type, media_ext, media_version, media_title, media_file_path, media_file_name, media_file_thumb_800, media_file_thumb_100, media_unique_hits, media_unique_hits_ip_block, media_unique_hits_user_id_block, media_created_datetime, media_created_date_saying, media_created_by_user_id, media_created_by_user_alias, media_created_by_user_email, media_created_by_user_image_file, media_created_by_user_ip, media_created_by_user_hostname, media_created_by_user_agent, media_updated_datetime, media_updated_date_saying, media_updated_by_user_id, media_updated_by_user_alias, media_updated_by_user_email, media_updated_by_user_image_file, media_updated_by_user_ip, media_updated_by_user_hostname, media_updated_by_user_agent FROM $t_knowledge_pages_media WHERE media_id=$media_id_mysql AND media_space_id=$get_current_space_id AND media_page_id=$get_current_page_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_media_id, $get_current_media_space_id, $get_current_media_page_id, $get_current_media_type, $get_current_media_ext, $get_current_media_version, $get_current_media_title, $get_current_media_file_path, $get_current_media_file_name, $get_current_media_file_thumb_800, $get_current_media_file_thumb_100, $get_current_media_unique_hits, $get_current_media_unique_hits_ip_block, $get_current_media_unique_hits_user_id_block, $get_current_media_created_datetime, $get_current_media_created_date_saying, $get_current_media_created_by_user_id, $get_current_media_created_by_user_alias, $get_current_media_created_by_user_email, $get_current_media_created_by_user_image_file, $get_current_media_created_by_user_ip, $get_current_media_created_by_user_hostname, $get_current_media_created_by_user_agent, $get_current_media_updated_datetime, $get_current_media_updated_date_saying, $get_current_media_updated_by_user_id, $get_current_media_updated_by_user_alias, $get_current_media_updated_by_user_email, $get_current_media_updated_by_user_image_file, $get_current_media_updated_by_user_ip, $get_current_media_updated_by_user_hostname, $get_current_media_updated_by_user_agent) = $row;
				if($get_current_media_id == ""){
					echo"<p>Image not found</p>";
				}
				else{
								
					if($process == "1"){
						if(file_exists("$root/$get_current_media_file_path/$get_current_media_file_name") && $get_current_media_file_name != ""){
							unlink("$root/$get_current_media_file_path/$get_current_media_file_name");
						}
						if(file_exists("$root/$get_current_media_file_path/$get_current_media_file_thumb_800") && $get_current_media_file_thumb_800 != ""){
							unlink("$root/$get_current_media_file_path/$get_current_media_file_thumb_800");
						}
						if(file_exists("$root/$get_current_media_file_path/$get_current_media_file_thumb_100") && $get_current_media_file_thumb_100 != ""){
							unlink("$root/$get_current_media_file_path/$get_current_media_file_thumb_100");
						}
						$result = mysqli_query($link, "DELETE FROM $t_knowledge_pages_media WHERE media_id=$get_current_media_id");

						if($get_current_page_id == "0"){
							$url = "media.php?space_id=$get_current_space_id";
						}
						else{
							$url = "media.php?space_id=$get_current_space_id&action=open_folder&page_id=$get_current_page_id&page_id_a=$page_id_a";
						}
						if($page_id_b != ""){
							$url = $url . "&page_id_b=$page_id_b";
						}
						if($page_id_c != ""){
							$url = $url . "&page_id_c=$page_id_c";
						}
						if($page_id_d != ""){
							$url = $url . "&page_id_d=$page_id_d";
						}
						$url = $url . "&l=$l&ft=success&fm=media_deleted";

						header("Location: $url");
						exit;


					}
					echo"
					<h1>$l_media</h1>

					<!-- Folders -->
						<div class=\"image_gallery_folders_list\">
							";
							$query = "SELECT page_id, page_title, page_no_of_children, page_weight FROM $t_knowledge_pages_index WHERE page_space_id=$get_current_space_id AND page_parent_id='0' ORDER BY page_weight ASC";
							$result = mysqli_query($link, $query);
							while($row = mysqli_fetch_row($result)) {
								list($get_page_id_a, $get_page_title_a, $get_page_no_of_children_a, $get_page_weight_a) = $row;
								echo"
								<table>
								 <tr>
								  <td style=\"padding: 0px 6px 6px 0px;\">
									<a href=\"media.php?space_id=$get_current_space_id&amp;action=open_folder&amp;page_id=$get_page_id_a&amp;page_id_a=$get_page_id_a&amp;l=$l\"><img src=\"_gfx/icons/"; if($get_page_id_a == "$page_id_a"){ echo"folder-open.png"; } else{ echo"folder.png"; }echo"\" alt=\"folder.png\" /></a>
								  </td>
								  <td style=\"padding: 0px 0px 6px 0px;\">
									<a href=\"media.php?space_id=$get_current_space_id&amp;action=open_folder&amp;page_id=$get_page_id_a&amp;page_id_a=$get_page_id_a&amp;l=$l\""; if($get_page_id_a == "$page_id"){ echo" style=\"font-weight:bold;\""; } echo">$get_page_title_a</a>
								  </td>
								 </tr>
								</table>
								";
								if($get_page_id_a == "$page_id_a"){
									$query_b = "SELECT page_id, page_title, page_no_of_children, page_weight FROM $t_knowledge_pages_index WHERE page_space_id=$get_current_space_id AND page_parent_id=$get_page_id_a ORDER BY page_weight ASC";
									$result_b = mysqli_query($link, $query_b);
									while($row_b = mysqli_fetch_row($result_b)) {
										list($get_page_id_b, $get_page_title_b, $get_page_no_of_children_b, $get_page_weight_b) = $row_b;
										echo"
										<table>
										 <tr>
										  <td style=\"padding: 0px 6px 6px 15px;\">
											<a href=\"media.php?space_id=$get_current_space_id&amp;action=open_folder&amp;page_id=$get_page_id_b&amp;page_id_a=$get_page_id_a&amp;page_id_b=$get_page_id_b&amp;l=$l\"><img src=\"_gfx/icons/"; if($get_page_id_b == "$page_id_b"){ echo"folder-open.png"; } else{ echo"folder.png"; }echo"\" alt=\"folder.png\" /></a>
										  </td>
										  <td style=\"padding: 0px 0px 6px 0px;\">
											<a href=\"media.php?space_id=$get_current_space_id&amp;action=open_folder&amp;page_id=$get_page_id_b&amp;page_id_a=$get_page_id_a&amp;page_id_b=$get_page_id_b&amp;l=$l\""; if($get_page_id_b == "$page_id"){ echo" style=\"font-weight:bold;\""; } echo">$get_page_title_b</a>
										  </td>
										 </tr>
										</table>
										";

										if($get_page_id_b == "$page_id_b"){
											$query_c = "SELECT page_id, page_title, page_no_of_children, page_weight FROM $t_knowledge_pages_index WHERE page_space_id=$get_current_space_id AND page_parent_id=$get_page_id_b ORDER BY page_weight ASC";
											$result_c = mysqli_query($link, $query_c);
											while($row_c = mysqli_fetch_row($result_c)) {
												list($get_page_id_c, $get_page_title_c, $get_page_no_of_children_c, $get_page_weight_c) = $row_c;
												echo"
												<table>
												 <tr>
												  <td style=\"padding: 0px 6px 6px 30px;\">
													<a href=\"media.php?space_id=$get_current_space_id&amp;action=open_folder&amp;page_id=$get_page_id_c&amp;page_id_a=$get_page_id_a&amp;page_id_b=$get_page_id_b&amp;page_id_c=$get_page_id_c&amp;l=$l\"><img src=\"_gfx/icons/"; if($get_page_id_c == "$page_id_c"){ echo"folder-open.png"; } else{ echo"folder.png"; }echo"\" alt=\"folder.png\" /></a>
												  </td>
												  <td style=\"padding: 0px 0px 6px 0px;\">
													<a href=\"media.php?space_id=$get_current_space_id&amp;action=open_folder&amp;page_id=$get_page_id_c&amp;page_id_a=$get_page_id_a&amp;page_id_b=$get_page_id_b&amp;page_id_c=$get_page_id_c&amp;l=$l\""; if($get_page_id_c == "$page_id"){ echo" style=\"font-weight:bold;\""; } echo">$get_page_title_c</a>
												  </td>
												 </tr>
												</table>
												";
											} // while c
										} // open b

									} // while b
								} // open a
							} // while a
							echo"
						</div>
					<!-- //Folders -->

					<!-- Folder browse -->
						<div class=\"image_gallery_folder_browse\">


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

							<!-- View image -->
									<h2>$get_current_media_title</h2>
									<p>
									<a href=\"$root/$get_current_media_file_path/$get_current_media_file_name\">";
									if($get_current_media_type == "image"){
										echo"<img src=\"$root/$get_current_media_file_path/$get_current_media_file_thumb_100\" alt=\"$get_current_media_file_thumb_100\" />";
									}
									else{
										echo"<img src=\"_gfx/icons/100x100/$get_current_media_ext\" alt=\"_gfx/icons/100x100/$get_current_media_ext\" />";
									}
									echo"</a><br />
									<input type=\"text\" name=\"inp_copy\" value=\"$root/$get_current_media_file_path/$get_current_media_file_name\" size=\"25\" style=\"width: 80%;border: #fff 1px solid;border-bottom: #ccc 1px dashed;\" />
									</p>

									<!-- Form -->
										<h2>$l_delete_media</h2>";

										// Action image url
										$action_cancel = "media.php?space_id=$get_current_space_id&amp;action=view_media&amp;page_id=$get_current_page_id&amp;media_id=$get_current_media_id&amp;page_id_a=$page_id_a";
										$action_delete = "media.php?space_id=$get_current_space_id&amp;action=delete_media&amp;page_id=$get_current_page_id&amp;media_id=$get_current_media_id&amp;page_id_a=$page_id_a";
										if($page_id_b != ""){
											$action_cancel = $action_cancel . "&amp;page_id_b=$page_id_b";
											$action_delete = $action_delete . "&amp;page_id_b=$page_id_b";
										}
										if($page_id_c != ""){
											$action_cancel = $action_cancel . "&amp;page_id_c=$page_id_c";
											$action_delete = $action_delete . "&amp;page_id_c=$page_id_c";
										}
										if($page_id_d != ""){
											$action_cancel = $action_cancel . "&amp;page_id_d=$page_id_d";
											$action_delete = $action_delete . "&amp;page_id_d=$page_id_d";
										}
										$action_cancel = $action_cancel . "&amp;l=$l";
										$action_delete = $action_delete . "&amp;l=$l&amp;process=1";

										echo"
										<p>$l_are_you_sure</p>
										  
										<p>
										<a href=\"$action_delete\" class=\"btn_warning\">$l_confirm</a>
										<a href=\"$action_cancel\" class=\"btn_default\">$l_cancel</a>
										</p>
										</form>
									<!-- //Form -->
									";
								
								echo"
							<!-- //View image -->
						</div>
					<!-- //Folder browse -->
					";
				} // image found
			} // action == "delete media"
			elseif($action == "rotate_image"){

				if(isset($_GET['page_id'])) {
					$page_id = $_GET['page_id'];
					$page_id = stripslashes(strip_tags($page_id));
				}
				else{
					$page_id = "";
				}
				$page_id_mysql = quote_smart($link, $page_id);

				if(isset($_GET['page_id_a'])) {
					$page_id_a = $_GET['page_id_a'];
					$page_id_a = stripslashes(strip_tags($page_id_a));
				}
				else{
					$page_id_a = "";
				}
				if(isset($_GET['page_id_b'])) {
					$page_id_b = $_GET['page_id_b'];
					$page_id_b = stripslashes(strip_tags($page_id_b));
				}
				else{
					$page_id_b = "";
				}
				if(isset($_GET['page_id_c'])) {
					$page_id_c = $_GET['page_id_c'];
					$page_id_c = stripslashes(strip_tags($page_id_c));
				}
				else{
					$page_id_c = "";
				}
				if(isset($_GET['page_id_d'])) {
					$page_id_d = $_GET['page_id_d'];
					$page_id_d = stripslashes(strip_tags($page_id_d));
				}
				else{
					$page_id_d = "";
				}
				if(isset($_GET['media_id'])) {
					$media_id = $_GET['media_id'];
					$media_id = stripslashes(strip_tags($media_id));
				}
				else{
					$media_id = "";
				}
				$media_id_mysql = quote_smart($link, $media_id);

				$query = "SELECT page_id, page_space_id, page_title, page_title_clean, page_description, page_text, page_parent_id, page_weight, page_allow_comments, page_no_of_comments, page_unique_hits, page_unique_hits_ip_block, page_unique_hits_user_id_block, page_created_datetime, page_created_date_saying, page_created_user_id, page_created_user_alias, page_created_user_image, page_updated_datetime, page_updated_date_saying, page_updated_user_id, page_updated_user_alias, page_updated_user_image FROM $t_knowledge_pages_index WHERE page_id=$page_id_mysql AND page_space_id=$get_current_space_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_page_id, $get_current_page_space_id, $get_current_page_title, $get_current_page_title_clean, $get_current_page_description, $get_current_page_text, $get_current_page_parent_id, $get_current_page_weight, $get_current_page_allow_comments, $get_current_page_no_of_comments, $get_current_page_unique_hits, $get_current_page_unique_hits_ip_block, $get_current_page_unique_hits_user_id_block, $get_current_page_created_datetime, $get_current_page_created_date_saying, $get_current_page_created_user_id, $get_current_page_created_user_alias, $get_current_page_created_user_image, $get_current_page_updated_datetime, $get_current_page_updated_date_saying, $get_current_page_updated_user_id, $get_current_page_updated_user_alias, $get_current_page_updated_user_image) = $row;

				if($get_current_page_id == ""){
					$get_current_page_id = 0;
				}

				$query = "SELECT media_id, media_space_id, media_page_id, media_type, media_ext, media_version, media_title, media_file_path, media_file_name, media_file_thumb_800, media_file_thumb_100, media_unique_hits, media_unique_hits_ip_block, media_unique_hits_user_id_block, media_created_datetime, media_created_date_saying, media_created_by_user_id, media_created_by_user_alias, media_created_by_user_email, media_created_by_user_image_file, media_created_by_user_ip, media_created_by_user_hostname, media_created_by_user_agent, media_updated_datetime, media_updated_date_saying, media_updated_by_user_id, media_updated_by_user_alias, media_updated_by_user_email, media_updated_by_user_image_file, media_updated_by_user_ip, media_updated_by_user_hostname, media_updated_by_user_agent FROM $t_knowledge_pages_media WHERE media_id=$media_id_mysql AND media_space_id=$get_current_space_id AND media_page_id=$get_current_page_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_media_id, $get_current_media_space_id, $get_current_media_page_id, $get_current_media_type, $get_current_media_ext, $get_current_media_version, $get_current_media_title, $get_current_media_file_path, $get_current_media_file_name, $get_current_media_file_thumb_800, $get_current_media_file_thumb_100, $get_current_media_unique_hits, $get_current_media_unique_hits_ip_block, $get_current_media_unique_hits_user_id_block, $get_current_media_created_datetime, $get_current_media_created_date_saying, $get_current_media_created_by_user_id, $get_current_media_created_by_user_alias, $get_current_media_created_by_user_email, $get_current_media_created_by_user_image_file, $get_current_media_created_by_user_ip, $get_current_media_created_by_user_hostname, $get_current_media_created_by_user_agent, $get_current_media_updated_datetime, $get_current_media_updated_date_saying, $get_current_media_updated_by_user_id, $get_current_media_updated_by_user_alias, $get_current_media_updated_by_user_email, $get_current_media_updated_by_user_image_file, $get_current_media_updated_by_user_ip, $get_current_media_updated_by_user_hostname, $get_current_media_updated_by_user_agent) = $row;
				if($get_current_media_id == ""){
					echo"<p>Image not found</p>";
				}
				else{
								
					if($process == "1"){
							if(file_exists("$root/$get_current_media_file_path/$get_current_media_file_thumb_800") && $get_current_media_file_thumb_800 != ""){
								unlink("$root/$get_current_media_file_path/$get_current_media_file_thumb_800");
							}
							if(file_exists("$root/$get_current_media_file_path/$get_current_media_file_thumb_100") && $get_current_media_file_thumb_100 != ""){
								unlink("$root/$get_current_media_file_path/$get_current_media_file_thumb_100");
							}

							// Ext
							$extension = get_extension($get_current_media_file_name);
							$extension = strtolower($extension);

							
							// Rotate
							if($extension == "jpg"){
								// Load
								$source = imagecreatefromjpeg("$root/$get_current_media_file_path/$get_current_media_file_name");

								// Rotate
								$rotate = imagerotate($source, -90, 0);

								// Save
								imagejpeg($rotate, "$root/$get_current_media_file_path/$get_current_media_file_name");

							}
							elseif($extension == "png"){
								// Load
								$source = imagecreatefrompng("$root/$get_current_media_file_path/$get_current_media_file_name");

								// Bg
								$bgColor = imagecolorallocatealpha($source, 255, 255, 255, 127);

								// Rotate
								$rotate = imagerotate($source, -90, $bgColor);
	
								// Save
								imagesavealpha($rotate, true);
								imagepng($rotate, "$root/$get_current_media_file_path/$get_current_media_file_name");

							}
							else{
								echo"Unknown extension";
								die;
							}


							// Give new name
							$random = rand(0,1000);


							$inp_file_thumb_a = $get_current_media_id . "_" . $random . "_thumb_800." . $extension;
							$inp_file_thumb_a_mysql = quote_smart($link, $inp_file_thumb_a);

							$inp_file_thumb_b = $get_current_media_id . "_" . $random . "_thumb_100." . $extension;
							$inp_file_thumb_b_mysql = quote_smart($link, $inp_file_thumb_a);


							$result = mysqli_query($link, "UPDATE $t_knowledge_pages_media SET
									media_file_thumb_800=$inp_file_thumb_a_mysql,
									media_file_thumb_100=$inp_file_thumb_b_mysql
									 WHERE media_id=$get_current_media_id");

							

							// Unlink old
							// unlink("$root/$get_current_image_file_path/$inp_file_name");

							$url = "media.php?space_id=$get_current_space_id&action=view_media&page_id=$get_current_page_id&media_id=$get_current_media_id&page_id_a=$page_id_a";
							if($page_id_b != ""){
								$url = $url . "&page_id_b=$page_id_b";
							}
							if($page_id_c != ""){
								$url = $url . "&page_id_c=$page_id_c";
							}
							if($page_id_d != ""){
								$url = $url . "&page_id_d=$page_id_d";
							}
							$url = $url . "&l=$l&ft=success&fm=image_rotated";

							header("Location: $url");
							exit;


					}
				} // image found
			} // action == "rotate image"
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