<?php 
/**
*
* File: howto/open_space.php
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

/*- Tables ---------------------------------------------------------------------------------- */
$t_knowledge_home_page_user_remember 		= $mysqlPrefixSav . "knowledge_home_page_user_remember";

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
	$website_title = "$get_current_space_title";
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
			// Make sure I remember this space 
			$query = "SELECT user_remember_id, user_remember_user_id, user_remember_space_id, user_remember_space_title FROM $t_knowledge_home_page_user_remember WHERE user_remember_user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_user_remember_id, $get_current_user_remember_user_id, $get_current_user_remember_space_id, $get_current_user_remember_space_title) = $row;
			if($get_current_user_remember_id == ""){
				$inp_space_title_mysql = quote_smart($link, $get_current_space_title);
				mysqli_query($link, "INSERT INTO $t_knowledge_home_page_user_remember 
				(user_remember_id, user_remember_user_id, user_remember_space_id, user_remember_space_title) 
				VALUES 
				(NULL, $my_user_id_mysql, $get_current_space_id, $inp_space_title_mysql )")
				or die(mysqli_error($link));
			}
			else{
				if($get_current_user_remember_space_id != "$get_current_user_remember_id"){
					$inp_space_title_mysql = quote_smart($link, $get_current_space_title);
					$result = mysqli_query($link, "UPDATE $t_knowledge_home_page_user_remember SET user_remember_space_id=$get_current_space_id,  user_remember_space_title=$inp_space_title_mysql WHERE user_remember_id=$get_current_user_remember_id") or die(mysqli_error($link));
				}
			}

			// Hits
			// Hits per user
			$have_visisted_before = "false"; // Guess 

			$block_array = explode("\n", $get_current_space_unique_hits_user_id_block);
			$block_array_size = sizeof($block_array);
			if($block_array_size > 10){
				$block_array_size = 5;
			}
			for($x=0;$x<$block_array_size;$x++){
				$temp = $block_array[$x];
				if($temp == "$my_user_id"){
					$have_visisted_before =  "true";
					break;
				}
				if(!(isset($inp_unique_hits_user_id_block))){
					$inp_unique_hits_user_id_block = "$temp";
				}
				else{
					$inp_unique_hits_user_id_block = $inp_unique_hits_user_id_block . "\n$temp";
				}
			}

			if($have_visisted_before == "false"){
			
				if(!(isset($inp_unique_hits_user_id_block))){
					$inp_unique_hits_user_id_block = "$my_user_id";
				}
				else{
					$inp_unique_hits_user_id_block = $my_user_id . "\n" . $inp_unique_hits_user_id_block;
				}
				$inp_unique_hits_user_id_block = output_html($inp_unique_hits_user_id_block);
				$inp_unique_hits_user_id_block = str_replace("<br />", "\n", $inp_unique_hits_user_id_block);
				$inp_unique_hits_user_id_block_mysql = quote_smart($link, $inp_unique_hits_user_id_block);

				$inp_unique_hits = $get_current_space_unique_hits+1;

				$result = mysqli_query($link, "UPDATE $t_knowledge_spaces_index SET space_unique_hits=$inp_unique_hits, space_unique_hits_user_id_block=$inp_unique_hits_user_id_block_mysql WHERE space_id=$get_current_space_id");
			}

			echo"
			<!-- Headline and logo -->
				<div class=\"knowledge_headline_and_logo\">
				";
				if($get_current_space_image != "" && file_exists("$root/_uploads/knowledge/space_$get_current_space_id/$get_current_space_image")){
				
					if(!(file_exists("$root/_uploads/knowledge/space_$get_current_space_id/$get_current_space_thumb_32"))){
						resize_crop_image(32, 32, "$root/_uploads/knowledge/space_$get_current_space_id/$get_current_space_image", "$root/_uploads/knowledge/space_$get_current_space_id/$get_current_space_thumb_32");
					}

					echo"<img src=\"$root/_uploads/knowledge/space_$get_current_space_id/$get_current_space_thumb_32\" alt=\"Image $get_current_space_image\" />\n";
				}
				echo"
				<h1>$get_current_space_title</h1>
				</div>
			<!-- //Headline and logo -->


			<!-- Space icons -->
				<div class=\"knowledge_head_menu\">
					<ul>

						<li><a href=\"new_page.php?space_id=$get_current_space_id&amp;l=$l\" class=\"btn_default\"><img src=\"_gfx/icons/new_black_18dp.png\" alt=\"new_black_18dp.png\" title=\"$l_new\" /> $l_new</a></li>";
				
					// Favorited?
					if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
						$my_user_id = $_SESSION['user_id'];
						$my_user_id = output_html($my_user_id);
						$my_user_id_mysql = quote_smart($link, $my_user_id);

						$query = "SELECT favorite_id, favorite_user_id FROM $t_knowledge_spaces_favorites WHERE favorite_space_id=$get_current_space_id AND favorite_user_id=$my_user_id_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_favorite_id, $get_favorite_user_id) = $row;
						if($get_favorite_id == ""){
							echo"					<li><a href=\"favorite_space.php?space_id=$get_current_space_id&amp;l=$l&amp;process=1\" class=\"btn_default\"><img src=\"_gfx/icons/favorite_border_black_18dp.png\" alt=\"favorite_border_black_18dp.png\" title=\"$l_add_favorite\" /> $l_add_favorite</a></li>\n";
						}
						else{
							echo"					<li><a href=\"favorite_space.php?space_id=$get_current_space_id&amp;l=$l&amp;process=1\" class=\"btn_default\"><img src=\"_gfx/icons/favorite_black_18dp.png\" alt=\"favorite_black_18dp.png\" title=\"$l_favorited\" /> $l_favorited</a></li>\n";
						}
					}
					else{
						echo"					<li><a href=\"favorite_space.php?space_id=$get_current_space_id&amp;l=$l&amp;process=1\" class=\"btn_default\"><img src=\"_gfx/icons/favorite_border_black_18dp.png\" alt=\"favorite_border_black_18dp.png\" title=\"$l_favorite\" /> $l_favorite</a></li>\n";
					}
					echo"
					<li><a href=\"edit_space.php?space_id=$get_current_space_id&amp;l=$l\" class=\"btn_default\"><img src=\"_gfx/icons/edit_black_18dp.png\" alt=\"edit_black_18dp.png\" title=\"$l_edit\" /> $l_edit</a></li>
					<li><a href=\"delete_space.php?space_id=$get_current_space_id&amp;l=$l\" class=\"btn_default\"><img src=\"_gfx/icons/delete_black_18dp.png\" alt=\"delete_black_18dp.png\" title=\"$l_delete\" /> $l_delete</a></li>
					<li><a href=\"search.php?l=$l\" class=\"btn_default\"><img src=\"_gfx/icons/search_black_18dp.png\" alt=\"search_black_18dp.png\" title=\"$l_search\" /> $l_search</a></li>

					<li><a href=\"spaces_overview.php?l=$l\" class=\"btn_default\"><img src=\"_gfx/icons/home_black_18dp.png\" alt=\"home_black_18dp.png\" title=\"$l_home\" /> $l_home</a></li>
					
					<li><span><select name=\"knowledge_spaces\" class=\"on_select_go_to_url\">
							<option value=\"spaces_overview.php?l=$l\">- $l_change_space -</option>\n";
						$query_space = "SELECT space_id, space_title FROM $t_knowledge_spaces_index WHERE space_is_archived='0' ORDER BY space_title ASC";
						$result_space = mysqli_query($link, $query_space);
						while($row_space = mysqli_fetch_row($result_space)) {
							list($get_space_id, $get_space_title) = $row_space;
							echo"			";
							echo"<option value=\"open_space.php?space_id=$get_space_id&amp;l=$l\""; if($get_space_id == "$get_current_space_id"){ echo" selected=\"selected\""; } echo">$get_space_title</option>\n";
						}
						echo"</select></span></li>
					</ul>
				
				</div>
				<!-- On select go to url -->
					<script>
					\$(function(){
						// bind change event to select
						\$('.on_select_go_to_url').on('change', function () {
							var url = \$(this).val(); // get selected value
							if (url) { // require a URL
								window.location = url; // redirect
							}
							return false;
						});
					});
					</script>
				<!-- //On select go to url -->
				
			<!-- //Space icons -->
			<div class=\"clear\" style=\"height: 10px;\"></div>

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

			<!-- Membership requests -->";
				// Check if I am a member
				if($get_my_member_rank == "admin" OR $get_my_member_rank == "moderator" OR $get_my_member_rank == "editor"){
					$query = "SELECT requested_membership_id, requested_membership_space_id, requested_membership_user_id, requested_membership_user_alias, requested_membership_user_email, requested_membership_user_image, requested_membership_user_position, requested_membership_user_department, requested_membership_user_location, requested_membership_user_about, requested_membership_datetime, requested_membership_date_saying FROM $t_knowledge_spaces_requested_memberships WHERE requested_membership_space_id=$get_current_space_id";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_requested_membership_id, $get_requested_membership_space_id, $get_requested_membership_user_id, $get_requested_membership_user_alias, $get_requested_membership_user_email, $get_requested_membership_user_image, $get_requested_membership_user_position, $get_requested_membership_user_department, $get_requested_membership_user_location, $get_requested_membership_user_about, $get_requested_membership_datetime, $get_requested_membership_date_saying) = $row;
					if($get_requested_membership_id != ""){
						echo"
						<div class=\"info\">
							<p><a href=\"edit_space_members.php?space_id=$get_current_space_id&amp;l=$l\">$l_membership_requests_pending</a><br />
							<a href=\"$root/users/view_profile.php?user_id=$get_requested_membership_user_id&amp;l=$l\">$get_requested_membership_user_alias</a> $l_has_requested_membersip_lowercase</p>
						</div>
						";
					}
				}
			echo"
			<!-- //Membership requests -->


			<!-- Text -->
				$get_current_space_text
			<!-- //Text -->

			<!-- Table of contents -->
				<h2>$l_contents</h2> 
				<div style=\"height: 10px;\"></div>
				<table class=\"hor-zebra\">
				 <thead>
				  <tr>
				   <th scope=\"col\">
					<span>$l_page</span>
				   </th>
				   <th scope=\"col\">
					<span>$l_description</span>
				   </th>
				  </tr>
				 </thead>
				 <tbody>
				";
				$style = "";
				$query = "SELECT page_id, page_title, page_description, page_no_of_children, page_weight FROM $t_knowledge_pages_index WHERE page_space_id=$get_current_space_id AND page_parent_id=0 ORDER BY page_weight ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_page_id_a, $get_page_title_a, $get_page_description_a, $get_page_no_of_children_a, $get_page_weight_a) = $row;
					if($style == ""){
						$style = "odd";
					}
					else{
						$style = "";
					}
							
					echo"
					  <tr>
					   <td class=\"$style\" style=\"vertical-align: top;\">
						<span><a href=\"view_page.php?space_id=$get_current_space_id&amp;page_id=$get_page_id_a&amp;l=$l\">$get_page_title_a</a></span>
					   </td>
					   <td class=\"$style\" style=\"vertical-align: top;\">
						<span>$get_page_description_a</span>
					   </td>
					  </tr>\n";

					$query_b = "SELECT page_id, page_title, page_description, page_no_of_children, page_weight FROM $t_knowledge_pages_index WHERE page_space_id=$get_current_space_id AND page_parent_id=$get_page_id_a ORDER BY page_weight ASC";
					$result_b = mysqli_query($link, $query_b);
					while($row_b = mysqli_fetch_row($result_b)) {
						list($get_page_id_b, $get_page_title_b, $get_page_description_b, $get_page_no_of_children_b, $get_page_weight_b) = $row_b;
						echo"
						  <tr>
						   <td class=\"$style\" style=\"padding-left: 40px;\">
							<span><a href=\"view_page.php?space_id=$get_current_space_id&amp;page_id=$get_page_id_b&amp;l=$l\">$get_page_title_b</a></span>
						   </td>
						   <td class=\"$style\" style=\"vertical-align: top;\">
							<span>$get_page_description_b</span>
						   </td>
						  </tr>\n";

						// B: Children
						$query_c = "SELECT page_id, page_title, page_description, page_no_of_children, page_weight FROM $t_knowledge_pages_index WHERE page_space_id=$get_current_space_id AND page_parent_id=$get_page_id_b ORDER BY page_weight ASC";
						$result_c = mysqli_query($link, $query_c);
						while($row_c = mysqli_fetch_row($result_c)) {
							list($get_page_id_c, $get_page_title_c, $get_page_description_c, $get_page_no_of_children_c, $get_page_weight_c) = $row_c;
							echo"
							  <tr>
							   <td class=\"$style\" style=\"padding-left: 80px;\">
								<span><a href=\"view_page.php?space_id=$get_current_space_id&amp;page_id=$get_page_id_c&amp;l=$l\">$get_page_title_c</a></span>
							   </td>
							   <td class=\"$style\" style=\"vertical-align: top;\">
								<span>$get_page_description_c</span>
							   </td>
							  </tr>\n";

							$query_d = "SELECT page_id, page_title, page_description, page_no_of_children, page_weight FROM $t_knowledge_pages_index WHERE page_space_id=$get_current_space_id AND page_parent_id=$get_page_id_c ORDER BY page_weight ASC";
							$result_d = mysqli_query($link, $query_d);
							while($row_d = mysqli_fetch_row($result_d)) {
								list($get_page_id_d, $get_page_title_d, $get_page_description_d, $get_page_no_of_children_d, $get_page_weight_d) = $row_d;
								echo"
								  <tr>
								   <td class=\"$style\" style=\"padding-left: 120px;\">
									<span><a href=\"view_page.php?space_id=$get_current_space_id&amp;page_id=$get_page_id_d&amp;l=$l\">$get_page_title_d</a></span>
								   </td>
								   <td class=\"$style\" style=\"vertical-align: top;\">
									<span>$get_page_description_d</span>
								   </td>
								  </tr>\n";
	
							} // D
						} // C
					} // B
				} // A
				echo"
				 </tbody>
				</table>
			<!-- //Table of contents -->

			<!-- Recently updated and popular -->
				<div class=\"knowledge_recently_updated\">
					<h2>$l_recently_updated</h2>
					<ul>
					";
					$query = "SELECT page_id, page_space_id, page_title, page_updated_date_saying FROM $t_knowledge_pages_index WHERE page_space_id=$get_current_space_id ORDER BY page_updated_datetime DESC LIMIT 0,10";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_page_id, $get_page_space_id, $get_page_title, $get_page_updated_date_saying) = $row;

						echo"
						<li><a href=\"view_page.php?space_id=$get_page_space_id&amp;page_id=$get_page_id&amp;l=$l\" title=\"$l_updated $get_page_updated_date_saying\">$get_page_title</a></li>
						";
					}
					echo"
					</ul>
		
				</div>
				<div class=\"knowledge_popular\">

					<h2>$l_popular</h2>
					<ul>
					";
					$query = "SELECT page_id, page_space_id, page_title, page_unique_hits FROM $t_knowledge_pages_index WHERE page_space_id=$get_current_space_id ORDER BY page_unique_hits DESC LIMIT 0,10";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_page_id, $get_page_space_id, $get_page_title, $get_page_unique_hits) = $row;

						echo"
						<li><a href=\"view_page.php?space_id=$get_page_space_id&amp;page_id=$get_page_id&amp;l=$l\" title=\"$get_page_unique_hits $l_unique_hits_lowercase\">$get_page_title</a></li>
						";
					}
					echo"
					</ul>
				</div>
				<div class=\"clear\"></div>
			<!-- //Recently updated and popular -->


			<!-- Team -->
				<h2>$l_meet_the_team</h2>
				";
				
				$col_counter = 0;
				$query = "SELECT member_id, member_space_id, member_rank, member_user_id, member_user_alias, member_user_image, member_user_about, member_added_datetime, member_added_date_saying, member_added_by_user_id, member_added_by_user_alias, member_added_by_user_image FROM $t_knowledge_spaces_members WHERE member_space_id=$get_current_space_id ORDER BY member_user_alias ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_member_id, $get_member_space_id, $get_member_rank, $get_member_user_id, $get_member_user_alias, $get_member_user_image, $get_member_user_about, $get_member_added_datetime, $get_member_added_date_saying, $get_member_added_by_user_id, $get_member_added_by_user_alias, $get_member_added_by_user_image) = $row;
					
					if($col_counter == "0"){
						echo"
						<div class=\"knowledge_team_row\">
						";
					}
					echo"
							<div class=\"knowledge_team_col\">
								<p>
								<a href=\"view_profile_and_update_profile.php?user_id=$get_member_user_id&amp;l=$l\">";
								if(file_exists("$root/_uploads/users/images/$get_member_id/$get_member_user_image") && $get_member_user_image != ""){
									echo"<img src=\"$root/_uploads/users/images/$get_member_id/$get_member_user_image\" alt=\"$get_member_user_image\" width=\"85\" height=\"85\" />";
								}
								else{
									echo"<img src=\"_gfx/avatar_blank_85.png\" alt=\"avatar_blank_85.png\" />";
								}
								echo"</a><br />
								<a href=\"view_profile_and_update_profile.php?user_id=$get_member_user_id&amp;l=$l\">$get_member_user_alias</a>
								</p>
							</div>
					";
					if($col_counter == "5"){
						echo"
						</div> <!-- //knowledge_team_row -->
						";
						$col_counter = -1;
					}

					$col_counter++;
				}
				if($col_counter == "6"){
					echo"
						</div> <!-- //knowledge_team_row -->
					";
				}
				elseif($col_counter == "5"){
					echo"
							<div class=\"knowledge_team_col\">
							</div>
						</div> <!-- //knowledge_team_row -->
					";
				}
				elseif($col_counter == "4"){
					echo"
							<div class=\"knowledge_team_col\">
							</div>
							<div class=\"knowledge_team_col\">
							</div>
						</div> <!-- //knowledge_team_row -->
					";
				}
				elseif($col_counter == "3"){
					echo"
							<div class=\"knowledge_team_col\">
							</div>
							<div class=\"knowledge_team_col\">
							</div>
							<div class=\"knowledge_team_col\">
							</div>
						</div> <!-- //knowledge_team_row -->
					";
				}
				elseif($col_counter == "2"){
					echo"
							<div class=\"knowledge_team_col\">
							</div>
							<div class=\"knowledge_team_col\">
							</div>
							<div class=\"knowledge_team_col\">
							</div>
							<div class=\"knowledge_team_col\">
							</div>
						</div> <!-- //knowledge_team_row -->
					";
				}
				elseif($col_counter == "1"){
					echo"
							<div class=\"knowledge_team_col\">
							</div>
							<div class=\"knowledge_team_col\">
							</div>
							<div class=\"knowledge_team_col\">
							</div>
							<div class=\"knowledge_team_col\">
							</div>
							<div class=\"knowledge_team_col\">
							</div>
						</div> <!-- //knowledge_team_row -->
					";
				}
				echo"
			<!-- //Team -->
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