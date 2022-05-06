<?php 
/**
*
* File: howto/edit_space_members.php
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

/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";


/*- Translation ------------------------------------------------------------------------------- */
include("$root/_admin/_translations/site/$l/knowledge/ts_new_page.php");
include("$root/_admin/_translations/site/$l/knowledge/ts_view_page.php");
include("$root/_admin/_translations/site/$l/knowledge/ts_edit_space.php");

/*- Functions ------------------------------------------------------------------------- */
include("$root/_admin/_functions/encode_national_letters.php");
include("$root/_admin/_functions/decode_national_letters.php");

/*- Variables -------------------------------------------------------------------------------- */
if(isset($_GET['space_id'])) {
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
	
	
		$query = "SELECT member_id, member_space_id, member_rank, member_user_id, member_user_alias, member_user_image, member_user_about, member_added_datetime, member_added_date_saying, member_added_by_user_id, member_added_by_user_alias, member_added_by_user_image FROM $t_knowledge_spaces_members WHERE member_space_id=$get_current_space_id AND member_user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_member_id, $get_my_member_space_id, $get_my_member_rank, $get_my_member_user_id, $get_my_member_user_alias, $get_my_member_user_image, $get_my_member_user_about, $get_my_member_added_datetime, $get_my_member_added_date_saying, $get_my_member_added_by_user_id, $get_my_member_added_by_user_alias, $get_my_member_added_by_user_image) = $row;
		
		if($get_my_member_id == ""){

			if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
				// If im admin, then add me
	
				$inp_my_user_alias_mysql = quote_smart($link, $get_my_user_alias);
				$inp_my_user_email_mysql = quote_smart($link, $get_my_user_email);
				$inp_my_user_image_mysql = quote_smart($link, $get_my_photo_destination);

				mysqli_query($link, "INSERT INTO $t_knowledge_spaces_members
				(member_id, member_space_id, member_status, member_user_id, member_user_alias, member_user_email, member_user_image, member_user_about, member_added_datetime, member_added_date_saying, member_added_by_user_id, member_added_by_user_alias, member_added_by_user_image) 
				VALUES 
				(NULL, $get_current_space_id, 'admin', $get_my_user_id, $inp_my_user_alias_mysql, $inp_my_user_email_mysql, $inp_my_user_image_mysql, '', '$datetime', '$date_saying', $get_my_user_id, $inp_my_user_alias_mysql, $inp_my_user_image_mysql)")
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
			// Can edit members: admin, moderator, editor
			// Can edit space:   admin, moderator, editor
			if($get_my_member_rank == "admin" OR $get_my_member_rank == "moderator" OR $get_my_member_rank == "editor"){

			}
			else{
				$url = "open_space.php?space_id=$get_current_space_id&ft=error&fm=your_dont_have_access_to_edit_this_space__please_contact_the_admin_for_access";
				header("Location: $url");
				exit;
			}
		}


		/*- Headers ---------------------------------------------------------------------------------- */
		$website_title = "$get_current_space_title - $l_edit";
		if(file_exists("./favicon.ico")){ $root = "."; }
		elseif(file_exists("../favicon.ico")){ $root = ".."; }
		elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
		elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
		include("$root/_webdesign/header.php");


		if($action == ""){

			echo"
			<h1>$l_edit_space</h1>

			<!-- Where am I ? -->
				<p><b>$l_you_are_here:</b><br />
				<a href=\"open_space.php?space_id=$get_current_space_id&amp;l=$l\">$get_current_space_title</a>
				&gt;
				<a href=\"edit_space.php?space_id=$get_current_space_id&amp;l=$l\">$l_edit</a>
				&gt;
				<a href=\"edit_space_members.php?space_id=$get_current_space_id&amp;l=$l\">$l_members</a>
				</p>
			<!-- Where am I ? -->

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

			<!-- Member list -->
				<p><a href=\"edit_space_members.php?space_id=$get_current_space_id&amp;action=add_member&amp;l=$l\" class=\"btn_default\">$l_add_member</a></p>
				<div style=\"height: 10px;\"></div>
				<table class=\"hor-zebra\">
				 <thead>
				  <tr>
				   <th scope=\"col\">
					<span>$l_alias</span>
				   </th>
				   <th scope=\"col\">
					<span>$l_rank</span>
				   </th>
				   <th scope=\"col\">
					<span>$l_added</span>
				   </th>
				  </tr>
			 	 </thead>
				<tbody>";
				// Select pending
				$query = "SELECT requested_membership_id, requested_membership_space_id, requested_membership_user_id, requested_membership_user_alias, requested_membership_user_email, requested_membership_user_image, requested_membership_user_position, requested_membership_user_department, requested_membership_user_location, requested_membership_user_about, requested_membership_datetime, requested_membership_date_saying FROM $t_knowledge_spaces_requested_memberships WHERE requested_membership_space_id=$get_current_space_id";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_requested_membership_id, $get_requested_membership_space_id, $get_requested_membership_user_id, $get_requested_membership_user_alias, $get_requested_membership_user_email, $get_requested_membership_user_image, $get_requested_membership_user_position, $get_requested_membership_user_department, $get_requested_membership_user_location, $get_requested_membership_user_about, $get_requested_membership_datetime, $get_requested_membership_date_saying) = $row;

					echo"
					 <tr>
       					  <td class=\"important\">
						<span>$get_requested_membership_user_alias</span>
					  </td>
       					  <td class=\"important\">
						<p style=\"padding: 0px 0px 4px 0px;margin: 0px 0px 0px 0px;\">
						$l_membership_requested<br />
						</p>
						<span>
						<a href=\"edit_space_members.php?space_id=$get_current_space_id&amp;action=accept_membership&amp;requested_membership_id=$get_requested_membership_id&amp;l=$l&amp;process=1\" class=\"btn_default\">$l_accept</a>
						<a href=\"edit_space_members.php?space_id=$get_current_space_id&amp;action=decline_membership&amp;requested_membership_id=$get_requested_membership_id&amp;l=$l&amp;process=1\" class=\"btn_default\">$l_decline</a>
						</span>
						
					  </td>
       					  <td class=\"important\">
						<span>$get_requested_membership_date_saying</span>
					  </td>
     					 </tr>";

				}

				// Select members
				$query = "SELECT member_id, member_space_id, member_rank, member_user_id, member_user_alias, member_user_image, member_user_about, member_added_datetime, member_added_date_saying, member_added_by_user_id, member_added_by_user_alias, member_added_by_user_image FROM $t_knowledge_spaces_members WHERE member_space_id=$get_current_space_id ORDER BY member_user_alias ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_member_id, $get_member_space_id, $get_member_rank, $get_member_user_id, $get_member_user_alias, $get_member_user_image, $get_member_user_about, $get_member_added_datetime, $get_member_added_date_saying, $get_member_added_by_user_id, $get_member_added_by_user_alias, $get_member_added_by_user_image) = $row;

				
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
						<span><a href=\"edit_space_members.php?space_id=$get_current_space_id&amp;action=edit_member&amp;member_id=$get_member_id&amp;l=$l\">$get_member_user_alias</a></span>
					  </td>
       					  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
						<span>";
						if($get_member_rank == "admin"){
							echo"$l_admin";
						}
						elseif($get_member_rank == "moderator"){
							echo"$l_moderator";
						}
						elseif($get_member_rank == "editor"){
							echo"$l_editor";
						}
						elseif($get_member_rank == "member"){
							echo"$l_member";
						}
						echo"</span>
					  </td>
       					  <td"; if($odd == true){ echo" class=\"odd\""; } echo">
						<span>$get_member_added_date_saying</span>
					  </td>
     					 </tr>";
				}
				echo"
				 </tbody>
				</table>
			<!-- //Member list -->
			";
		} // action == ""
		elseif($action == "add_member"){
			if($process == "1"){
				$inp_email = $_POST['inp_email'];
				$inp_email = output_html($inp_email);
				$inp_email_mysql = quote_smart($link, $inp_email);

				$inp_rank = $_POST['inp_rank'];
				$inp_rank = output_html($inp_rank);
				$inp_rank_mysql = quote_smart($link, $inp_rank);

				$query = "SELECT user_id, user_email, user_name, user_alias, user_language, user_last_online, user_rank, user_login_tries FROM $t_users WHERE user_email=$inp_email_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_language, $get_user_last_online, $get_user_rank, $get_user_login_tries) = $row;
	
				if($get_user_id == ""){
					$url = "edit_space_members.php?space_id=$get_current_space_id&action=add_member&l=$l&ft=error&fm=email_not_found&inp_email=$inp_email&inp_rank=$inp_rank";
					header("Location: $url");
					exit;
				}
				else{
					// Is user already member?
					$query = "SELECT member_id FROM $t_knowledge_spaces_members WHERE member_space_id=$get_current_space_id AND member_user_id=$get_user_id";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_member_id) = $row;
					if($get_member_id != ""){
						$url = "edit_space_members.php?space_id=$get_current_space_id&action=add_member&l=$l&ft=error&fm=user_is_already_member&inp_email=$inp_email&inp_rank=$inp_rank";
						header("Location: $url");
						exit;
					}

					$query_p = "SELECT photo_id, photo_destination, photo_thumb_40 FROM $t_users_profile_photo WHERE photo_user_id='$get_user_id' AND photo_profile_image='1'";
					$result_p = mysqli_query($link, $query_p);
					$row_p = mysqli_fetch_row($result_p);
					list($get_photo_id, $get_photo_destination, $get_photo_thumb_40) = $row_p;

					$inp_user_name_mysql  = quote_smart($link, $get_user_name);
					$inp_user_alias_mysql = quote_smart($link, $get_user_alias);
					$inp_user_image_mysql = quote_smart($link, $get_photo_destination);


					// Dates
					$datetime = date("Y-m-d H:i:s");
					$date_saying = date("j M Y");
					$datetime_saying = date("j. M Y H:i");

					// Get my user
					$query = "SELECT user_id, user_email, user_name, user_alias, user_language, user_last_online, user_rank, user_login_tries FROM $t_users WHERE user_id=$my_user_id_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_language, $get_my_user_last_online, $get_my_user_rank, $get_my_user_login_tries) = $row;
	
					$query_p = "SELECT photo_id, photo_destination, photo_thumb_40 FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
					$result_p = mysqli_query($link, $query_p);
					$row_p = mysqli_fetch_row($result_p);
					list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_40) = $row_p;

					
					$inp_my_user_name_mysql  = quote_smart($link, $get_my_user_name);
					$inp_my_user_alias_mysql = quote_smart($link, $get_my_user_alias);
					$inp_my_user_email_mysql = quote_smart($link, $get_my_user_email);
					$inp_my_user_image_mysql = quote_smart($link, $get_my_photo_destination);

					mysqli_query($link, "INSERT INTO $t_knowledge_spaces_members
					(member_id, member_space_id, member_rank, member_user_id, member_user_alias, member_user_image, member_user_about, member_added_datetime, member_added_date_saying, member_added_by_user_id, member_added_by_user_alias, member_added_by_user_image) 
					VALUES 
					(NULL, $get_current_space_id, $inp_rank_mysql, $get_user_id, $inp_user_alias_mysql, $inp_user_image_mysql, '', '$datetime', '$date_saying', $get_my_user_id, $inp_my_user_alias_mysql, $inp_my_user_image_mysql)")
					or die(mysqli_error($link));


					// Search engine index: access
					mysqli_query($link, "INSERT INTO $t_search_engine_access_control 
					(control_id, control_user_id, control_user_name, control_has_access_to_module_name, control_has_access_to_module_part_name, 
					control_has_access_to_module_part_id, control_created_datetime, control_created_datetime_print) 
					VALUES 
					(NULL, $get_user_id, $inp_user_name_mysql, 'knowledge', 'spaces', 
					$get_current_space_id, '$datetime', '$datetime_saying')")
					or die(mysqli_error($link));

					$url = "edit_space_members.php?space_id=$get_current_space_id&l=$l&ft=success&fm=member_added";
					header("Location: $url");
					exit;
				} // user found
			}
			echo"
			<h1>$l_edit_space</h1>

			<!-- Where am I ? -->
				<p><b>$l_you_are_here:</b><br />
				<a href=\"open_space.php?space_id=$get_current_space_id&amp;l=$l\">$get_current_space_title</a>
				&gt;
				<a href=\"edit_space.php?space_id=$get_current_space_id&amp;l=$l\">$l_edit</a>
				&gt;
				<a href=\"edit_space_members.php?space_id=$get_current_space_id&amp;l=$l\">$l_members</a>
				&gt;
				<a href=\"edit_space_members.php?space_id=$get_current_space_id&amp;action=add_member&amp;l=$l\">$l_add_member</a>
				</p>
			<!-- Where am I ? -->

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

			<!-- Add member form -->
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_email\"]').focus();
				});
				</script>
				
				<p>
				$l_user_have_to_be_registered_before_you_can_add_him_or_her_as_a_member
				<a href=\"$root/users/create_free_account.php?l=$l\">$l_register_new_user</a>
				</p>

				<form method=\"POST\" action=\"edit_space_members.php?space_id=$get_current_space_id&amp;action=add_member&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

				<p><b>$l_email</b><br />
				<input type=\"text\" name=\"inp_email\" value=\"";
				if(isset($_GET['inp_email'])) {
					$inp_email = $_GET['inp_email'];
					$inp_email = output_html($inp_email);
					echo"$inp_email";
				}
				echo"\" size=\"25\" style=\"width: 100%;\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>

				<p><b>$l_rank</b><br />";
				if(isset($_GET['inp_rank'])) {
					$inp_rank = $_GET['inp_rank'];
					$inp_rank = output_html($inp_rank);
				}
				else{
					$inp_rank = "editor";
				}
				echo"
				<select name=\"inp_rank\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
				if($get_my_member_rank == "admin"){
					echo"					";
					echo"<option value=\"admin\""; if($inp_rank == "admin"){ echo" selected=\"selected\""; } echo">$l_admin - $l_full_access</option>\n";
				}
				if($get_my_member_rank == "admin" OR $get_my_member_rank == "moderator"){
					echo"					";
					echo"<option value=\"moderator\""; if($inp_rank == "moderator"){ echo" selected=\"selected\""; } echo">$l_moderator - $l_full_access_cant_delete_admin</option>\n";
				}
				echo"
					<option value=\"editor\""; if($inp_rank == "editor"){ echo" selected=\"selected\""; } echo">$l_editor - $l_can_edit_space_and_pages</option>
					<option value=\"member\""; if($inp_rank == "member"){ echo" selected=\"selected\""; } echo">$l_member - $l_view_access_only</option>
				</select>
				</p>

				<p>
				<input type=\"submit\" value=\"$l_add_member\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>

				</form>
			<!-- //Add member form -->
			";
		} // action == "add member"
		elseif($action == "edit_member"){
			if(isset($_GET['member_id'])) {
				$member_id = $_GET['member_id'];
				$member_id = stripslashes(strip_tags($member_id));
			}
			else{
				$member_id = "";
			}
			$member_id_mysql = quote_smart($link, $member_id);

			
			$query = "SELECT member_id, member_space_id, member_rank, member_user_id, member_user_alias, member_user_image, member_user_about, member_added_datetime, member_added_date_saying, member_added_by_user_id, member_added_by_user_alias, member_added_by_user_image FROM $t_knowledge_spaces_members WHERE member_id=$member_id_mysql AND member_space_id=$get_current_space_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_member_id, $get_current_member_space_id, $get_current_member_rank, $get_current_member_user_id, $get_current_member_user_alias, $get_current_member_user_image, $get_current_member_user_about, $get_current_member_added_datetime, $get_current_member_added_date_saying, $get_current_member_added_by_user_id, $get_current_member_added_by_user_alias, $get_current_member_added_by_user_image) = $row;
			
			if($get_current_member_id == ""){
				echo"
				<h1>Member not found</h1>

				<p>
				<a href=\"edit_space_members.php?space_id=$get_current_space_id&amp;l=$l\">$l_members</a>
				</p>
				";
			}
			else{
				if($process == "1"){
					$inp_rank = $_POST['inp_rank'];
					$inp_rank = output_html($inp_rank);
					$inp_rank_mysql = quote_smart($link, $inp_rank);
					$result = mysqli_query($link, "UPDATE $t_knowledge_spaces_members SET member_rank=$inp_rank_mysql WHERE member_id=$get_current_member_id");

					$url = "edit_space_members.php?space_id=$get_current_space_id&action=edit_member&member_id=$get_current_member_id&l=$l&ft=success&fm=changes_saved";
					header("Location: $url");
					exit;
				}
				echo"
				<h1>$l_edit_space</h1>

				<!-- Where am I ? -->
					<p><b>$l_you_are_here:</b><br />
					<a href=\"open_space.php?space_id=$get_current_space_id&amp;l=$l\">$get_current_space_title</a>
					&gt;
					<a href=\"edit_space.php?space_id=$get_current_space_id&amp;l=$l\">$l_edit</a>
					&gt;
					<a href=\"edit_space_members.php?space_id=$get_current_space_id&amp;l=$l\">$l_members</a>
					&gt;
					<a href=\"edit_space_members.php?space_id=$get_current_space_id&amp;action=edit_member&amp;member_id=$get_current_member_id&amp;l=$l\">$l_edit_member</a>
					</p>
				<!-- Where am I ? -->
	
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

				<!-- Edit member form -->
					<h2>$l_edit_member $get_current_member_user_alias</h2>
					<script>
					\$(document).ready(function(){
						\$('[name=\"inp_rank\"]').focus();
					});
					</script>
				
					<form method=\"POST\" action=\"edit_space_members.php?space_id=$get_current_space_id&amp;action=edit_member&amp;member_id=$get_current_member_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

					<p><b>$l_rank</b><br />
					<select name=\"inp_rank\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
					if($get_my_member_rank == "admin"){
						echo"					";
						echo"<option value=\"admin\""; if($get_current_member_rank == "admin"){ echo" selected=\"selected\""; } echo">$l_admin - $l_full_access</option>\n";
					}
					if($get_my_member_rank == "admin" OR $get_current_member_rank == "moderator"){
						echo"					";
						echo"<option value=\"moderator\""; if($get_current_member_rank == "moderator"){ echo" selected=\"selected\""; } echo">$l_moderator - $l_full_access_cant_delete_admin</option>\n";
					}
					echo"
						<option value=\"editor\""; if($get_current_member_rank == "editor"){ echo" selected=\"selected\""; } echo">$l_editor - $l_can_edit_space_and_pages</option>
						<option value=\"member\""; if($get_current_member_rank == "member"){ echo" selected=\"selected\""; } echo">$l_member - $l_view_access_only</option>
					</select>
					</p>

					<p>
					<input type=\"submit\" value=\"$l_save_changes\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					<a href=\"edit_space_members.php?space_id=$get_current_space_id&amp;action=delete_member&amp;member_id=$get_current_member_id&amp;l=$l\" class=\"btn_warning\">$l_delete_member</a>
					</p>
	
					</form>
				<!-- //Edit member form -->
				";
			} // member found
		} // action == "edit member"
		elseif($action == "delete_member"){
			if(isset($_GET['member_id'])) {
				$member_id = $_GET['member_id'];
				$member_id = stripslashes(strip_tags($member_id));
			}
			else{
				$member_id = "";
			}
			$member_id_mysql = quote_smart($link, $member_id);

			
			$query = "SELECT member_id, member_space_id, member_rank, member_user_id, member_user_alias, member_user_image, member_user_about, member_added_datetime, member_added_date_saying, member_added_by_user_id, member_added_by_user_alias, member_added_by_user_image FROM $t_knowledge_spaces_members WHERE member_id=$member_id_mysql AND member_space_id=$get_current_space_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_member_id, $get_current_member_space_id, $get_current_member_rank, $get_current_member_user_id, $get_current_member_user_alias, $get_current_member_user_image, $get_current_member_user_about, $get_current_member_added_datetime, $get_current_member_added_date_saying, $get_current_member_added_by_user_id, $get_current_member_added_by_user_alias, $get_current_member_added_by_user_image) = $row;
			
			if($get_current_member_id == ""){
				echo"
				<h1>Member not found</h1>

				<p>
				<a href=\"edit_space_members.php?space_id=$get_current_space_id&amp;l=$l\">$l_members</a>
				</p>
				";
			}
			else{
				if($process == "1"){
					
					$result = mysqli_query($link, "DELETE FROM $t_knowledge_spaces_members WHERE member_id=$get_current_member_id");


					// Search engine index: access
					$result = mysqli_query($link, "DELETE FROM $t_search_engine_access_control WHERE control_user_id=$get_current_member_user_id AND control_has_access_to_module_name='knowledge' AND control_has_access_to_module_part_name='spaces' AND control_has_access_to_module_part_id=$get_current_space_id") or die(mysqli_error($link));


					$url = "edit_space_members.php?space_id=$get_current_space_id&l=$l&ft=success&fm=member_deleted";
					header("Location: $url");
					exit;
				}
				echo"
				<h1>$l_edit_space</h1>

				<!-- Where am I ? -->
					<p><b>$l_you_are_here:</b><br />
					<a href=\"open_space.php?space_id=$get_current_space_id&amp;l=$l\">$get_current_space_title</a>
					&gt;
					<a href=\"edit_space.php?space_id=$get_current_space_id&amp;l=$l\">$l_edit</a>
					&gt;
					<a href=\"edit_space_members.php?space_id=$get_current_space_id&amp;l=$l\">$l_members</a>
					&gt;
					<a href=\"edit_space_members.php?space_id=$get_current_space_id&amp;action=edit_member&amp;member_id=$get_current_member_id&amp;l=$l\">$l_edit_member</a>
					&gt;
					<a href=\"edit_space_members.php?space_id=$get_current_space_id&amp;action=delete_member&amp;member_id=$get_current_member_id&amp;l=$l\">$l_delete_member</a>
					</p>
				<!-- Where am I ? -->
	
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

				<!-- Delete member form -->
					<h2>$l_delete_member $get_current_member_user_alias</h2>
					
					<p>
					$l_are_you_sure
					</p>

					<p>
					<a href=\"edit_space_members.php?space_id=$get_current_space_id&amp;action=delete_member&amp;member_id=$get_current_member_id&amp;l=$l&amp;process=1\" class=\"btn_warning\">$l_confirm</a>
					</p>
	
				<!-- //Delete member form -->
				";
			} // member found
		} // action == "delete member"
		elseif($action == "accept_membership"){
			if(isset($_GET['requested_membership_id'])) {
				$requested_membership_id = $_GET['requested_membership_id'];
				$requested_membership_id = stripslashes(strip_tags($requested_membership_id));
			}
			else{
				$requested_membership_id = "";
			}
			$requested_membership_id_mysql = quote_smart($link, $requested_membership_id);

			
			$query = "SELECT requested_membership_id, requested_membership_space_id, requested_membership_user_id, requested_membership_user_alias, requested_membership_user_email, requested_membership_user_image, requested_membership_user_position, requested_membership_user_department, requested_membership_user_location, requested_membership_user_about, requested_membership_datetime, requested_membership_date_saying FROM $t_knowledge_spaces_requested_memberships WHERE requested_membership_id=$requested_membership_id_mysql AND requested_membership_space_id=$get_current_space_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_requested_membership_id, $get_requested_membership_space_id, $get_requested_membership_user_id, $get_requested_membership_user_alias, $get_requested_membership_user_email, $get_requested_membership_user_image, $get_requested_membership_user_position, $get_requested_membership_user_department, $get_requested_membership_user_location, $get_requested_membership_user_about, $get_requested_membership_datetime, $get_requested_membership_date_saying) = $row;
			
			if($get_requested_membership_id == ""){
				echo"
				<h1>Request not found</h1>

				<p>
				<a href=\"edit_space_members.php?space_id=$get_current_space_id&amp;l=$l\">$l_members</a>
				</p>
				";
			}
			else{
				if($process == "1"){
					// Delete
					$result = mysqli_query($link, "DELETE FROM $t_knowledge_spaces_requested_memberships WHERE requested_membership_id=$get_requested_membership_id");
					
					// Find user
					$query = "SELECT user_id, user_email, user_name, user_alias, user_language, user_last_online, user_rank, user_login_tries FROM $t_users WHERE user_id=$get_requested_membership_user_id";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_language, $get_user_last_online, $get_user_rank, $get_user_login_tries) = $row;
	


					// Insert
					$inp_user_name_mysql  = quote_smart($link, $get_user_name);
					$inp_alias_mysql = quote_smart($link, $get_requested_membership_user_alias);
					$inp_email_mysql = quote_smart($link, $get_requested_membership_user_email);
					$inp_image_mysql = quote_smart($link, $get_requested_membership_user_image);
					$inp_position_mysql = quote_smart($link, $get_requested_membership_user_position);
					$inp_department_mysql = quote_smart($link, $get_requested_membership_user_department);
					$inp_location_mysql = quote_smart($link, $get_requested_membership_user_location);
					$inp_about_mysql = quote_smart($link, $get_requested_membership_user_about);



					// Dates
					$datetime = date("Y-m-d H:i:s");
					$date_saying = date("j M Y");


					// Get my user
					$query = "SELECT user_id, user_email, user_name, user_alias, user_language, user_last_online, user_rank, user_login_tries FROM $t_users WHERE user_id=$my_user_id_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_language, $get_my_user_last_online, $get_my_user_rank, $get_my_user_login_tries) = $row;
	
					$query_p = "SELECT photo_id, photo_destination, photo_thumb_40 FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
					$result_p = mysqli_query($link, $query_p);
					$row_p = mysqli_fetch_row($result_p);
					list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_40) = $row_p;

					
					$inp_my_user_alias_mysql = quote_smart($link, $get_my_user_alias);
					$inp_my_user_email_mysql = quote_smart($link, $get_my_user_email);
					$inp_my_user_image_mysql = quote_smart($link, $get_my_photo_destination);


					mysqli_query($link, "INSERT INTO $t_knowledge_spaces_members
					(member_id, member_space_id, member_rank, member_user_id, member_user_alias, member_user_email, member_user_image, member_user_position, member_user_department, member_user_location, member_user_about, member_added_datetime, member_added_date_saying, member_added_by_user_id, member_added_by_user_alias, member_added_by_user_image) 
					VALUES 
					(NULL, $get_current_space_id, 'member', $get_requested_membership_user_id, $inp_alias_mysql, $inp_email_mysql, $inp_image_mysql, $inp_position_mysql, $inp_department_mysql, $inp_location_mysql, $inp_about_mysql, '$datetime', '$date_saying', '$get_my_user_id', $inp_my_user_alias_mysql, $inp_my_user_image_mysql)")
					or die(mysqli_error($link));
						
					// Email
					$subject = "$l_welcome_to $get_current_space_title";
					$message = $message . "$l_hi $get_requested_membership_user_alias,\n";
					$message = $message . "$l_you_have_gained_access_to_the_space $get_current_space_title.\n";
					$message = $message . "$l_the_url_to_the_space_is\n";
					$message = $message . "$configSiteURLSav/knowledge/open_space.php?space_id=$get_current_space_id&amp;l=$l\n\n";
					$message = $message . "--\n";
					$message = $message . "$l_regards\n";
					$message = $message . "$configFromNameSav\n";
					$message = $message . "$configWebsiteTitleSav\n";
					$message = $message . "$configFromEmailSav\n\n";
					$message = $message . "$l_dont_want_any_more_emails_then_unsubscribe_by_follow_this_link\n";

					$headers = "From: $configFromEmailSav" . "\r\n" .
					    "Reply-To: $configFromEmailSav" . "\r\n" .
					    'X-Mailer: PHP/' . phpversion();
					if($configMailSendActiveSav == "1"){
						mail($get_requested_membership_user_email, $subject, $send_message, $headers);
					}

					// Search engine index: access
					mysqli_query($link, "INSERT INTO $t_search_engine_access_control 
					(control_id, control_user_id, control_user_name, control_has_access_to_module_name, control_has_access_to_module_part_name, 
					control_has_access_to_module_part_id, control_created_datetime, control_created_datetime_print) 
					VALUES 
					(NULL, $get_user_id, $inp_user_name_mysql, 'knowledge', 'spaces', 
					$get_current_space_id, '$datetime', '$datetime_saying')")
					or die(mysqli_error($link));



					$url = "edit_space_members.php?space_id=$get_current_space_id&l=$l&ft=success&fm=member_accepted";
					header("Location: $url");
					exit;
				}
			} // member request found
		} // action == "accept_membership"
		elseif($action == "decline_membership"){
			if(isset($_GET['requested_membership_id'])) {
				$requested_membership_id = $_GET['requested_membership_id'];
				$requested_membership_id = stripslashes(strip_tags($requested_membership_id));
			}
			else{
				$requested_membership_id = "";
			}
			$requested_membership_id_mysql = quote_smart($link, $requested_membership_id);

			
			$query = "SELECT requested_membership_id, requested_membership_space_id, requested_membership_user_id, requested_membership_user_alias, requested_membership_user_email, requested_membership_user_image, requested_membership_user_position, requested_membership_user_department, requested_membership_user_location, requested_membership_user_about, requested_membership_datetime, requested_membership_date_saying FROM $t_knowledge_spaces_requested_memberships WHERE requested_membership_id=$requested_membership_id_mysql AND requested_membership_space_id=$get_current_space_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_requested_membership_id, $get_requested_membership_space_id, $get_requested_membership_user_id, $get_requested_membership_user_alias, $get_requested_membership_user_email, $get_requested_membership_user_image, $get_requested_membership_user_position, $get_requested_membership_user_department, $get_requested_membership_user_location, $get_requested_membership_user_about, $get_requested_membership_datetime, $get_requested_membership_date_saying) = $row;
			
			if($get_requested_membership_id == ""){
				echo"
				<h1>Request not found</h1>

				<p>
				<a href=\"edit_space_members.php?space_id=$get_current_space_id&amp;l=$l\">$l_members</a>
				</p>
				";
			}
			else{
				if($process == "1"){
					// Delete
					$result = mysqli_query($link, "DELETE FROM $t_knowledge_spaces_requested_memberships WHERE requested_membership_id=$get_requested_membership_id");
					
					// Email
					$subject = "$l_membership_request_declined ($get_current_space_title)";
					$message = $message . "$l_hi $get_requested_membership_user_alias,\n";
					$message = $message . "$l_your_membership_request_was_declined\n\n";
					$message = $message . "--\n";
					$message = $message . "$l_regards\n";
					$message = $message . "$configFromNameSav\n";
					$message = $message . "$configWebsiteTitleSav\n";
					$message = $message . "$configFromEmailSav\n\n";
					$message = $message . "$l_dont_want_any_more_emails_then_unsubscribe_by_follow_this_link\n";

					$headers = "From: $configFromEmailSav" . "\r\n" .
					    "Reply-To: $configFromEmailSav" . "\r\n" .
					    'X-Mailer: PHP/' . phpversion();
					if($configMailSendActiveSav == "1"){
						mail($get_requested_membership_user_email, $subject, $send_message, $headers);
					}

					$url = "edit_space_members.php?space_id=$get_current_space_id&l=$l&ft=success&fm=member_declined";
					header("Location: $url");
					exit;
				}
			} // member request found
		} // action == "decline_membership"
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