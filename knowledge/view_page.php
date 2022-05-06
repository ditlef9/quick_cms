<?php 
/**
*
* File: howto/view_page.php
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
			list($get_member_id, $get_member_space_id, $get_member_rank, $get_member_user_id, $get_member_user_alias, $get_member_user_image, $get_member_user_about, $get_member_added_datetime, $get_member_added_date_saying, $get_member_added_by_user_id, $get_member_added_by_user_alias, $get_member_added_by_user_image) = $row;
			if($get_member_id == ""){
				echo"
				<h1>$l_acccess_denied</h1>
				<p>
				$l_only_members_can_see_pages_in_this_space
				</p>

				<p>
				<a href=\"request_menbership_to_space.php?space_id=$get_current_space_id&amp;l=$l\" class=\"btn_default\">$l_request_membership</a>
				</p>
				";
			}
			else{



				// My user view history
				$query = "SELECT history_id, history_page_id, history_space_id, history_user_id, history_page_title, history_page_description, history_page_viewed_datetime FROM $t_knowledge_pages_view_history WHERE history_page_id=$get_current_page_id AND history_user_id=$my_user_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_history_id, $get_history_page_id, $get_history_space_id, $get_history_user_id, $get_history_page_title, $get_history_page_description, $get_history_page_viewed_datetime) = $row;

				$inp_page_title_mysql = quote_smart($link, $get_current_page_title);
				$inp_page_description = substr($get_current_page_description, 0, 200);
				$inp_page_description_mysql = quote_smart($link, $inp_page_description);
				$datetime = date("Y-m-d H:i:s");
				$year = date("Y");

				if($get_history_id == ""){
					mysqli_query($link, "INSERT INTO $t_knowledge_pages_view_history 
					(history_id, history_page_id, history_space_id, history_user_id, history_page_title, history_page_description, history_page_viewed_datetime, history_page_viewed_year) 
					VALUES 
					(NULL, $get_current_page_id, $get_current_page_space_id, $my_user_id_mysql, $inp_page_title_mysql, $inp_page_description_mysql, '$datetime', '$year')")
					or die(mysqli_error($link));
				}
				else{
					$result = mysqli_query($link, "UPDATE $t_knowledge_pages_view_history SET history_page_viewed_datetime='$datetime', history_page_viewed_year='$year' WHERE history_id=$get_history_id");
				}

				// Hits
				// Hits per user
				$have_visisted_before = "false"; // Guess 

				$block_array = explode("\n", $get_current_page_unique_hits_user_id_block);
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

					$inp_unique_hits = $get_current_page_unique_hits+1;

					$result = mysqli_query($link, "UPDATE $t_knowledge_pages_index SET page_unique_hits=$inp_unique_hits, page_unique_hits_user_id_block=$inp_unique_hits_user_id_block_mysql WHERE page_id=$get_current_page_id");

				}
		


				echo"
				<!-- Where am I ? -->
					<div class=\"knowledge_where_am_i\">
					<p>
					<!-- Look for parent -->
					";

			
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

							echo"
							<a href=\"view_page.php?space_id=$get_current_page_space_id&amp;page_id=$get_parent_c_page_id&amp;l=$l\">$get_parent_c_page_title</a>
							<span>/</span> ";
						}
						echo"
						<a href=\"view_page.php?space_id=$get_current_page_space_id&amp;page_id=$get_parent_b_page_id&amp;l=$l\">$get_parent_b_page_title</a>
						<span>/</span> ";
					}
					echo"
					<a href=\"view_page.php?space_id=$get_current_page_space_id&amp;page_id=$get_parent_a_page_id&amp;l=$l\">$get_parent_a_page_title</a>
					<span>/</span> ";
				}
				echo"
				
					<!-- This page -->
					<a href=\"view_page.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;l=$l\">$get_current_page_title</a>
					</p>
					</div>
				<!-- //Where am I ? -->

				<!-- Head menu -->
				<div class=\"knowledge_head_menu\">
				<ul>
					<li><a href=\"new_page.php?space_id=$get_current_space_id&amp;page_id=$get_current_page_id&amp;l=$l\"><img src=\"_gfx/icons/new_black_18dp.png\" alt=\"new_black_18dp.png\" title=\"$l_new\" /> $l_new</a></li>";
				
				// Favorited?
				if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
					$my_user_id = $_SESSION['user_id'];
					$my_user_id = output_html($my_user_id);
					$my_user_id_mysql = quote_smart($link, $my_user_id);

					$query = "SELECT favorite_id, favorite_page_id, favorite_user_id, favorite_category_id, favorite_page_title, favorite_page_description FROM $t_knowledge_pages_favorites WHERE favorite_page_id=$get_current_page_id AND favorite_user_id=$my_user_id_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_favorite_id, $get_favorite_page_id, $get_favorite_user_id, $get_favorite_category_id, $get_favorite_page_title, $get_favorite_page_description) = $row;
					if($get_favorite_id == ""){
						echo"					<li><a href=\"favorite_page.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;l=$l&amp;process=1\"><img src=\"_gfx/icons/favorite_border_black_18dp.png\" alt=\"favorite_border_black_18dp.png\" title=\"$l_add_favorite\" /> $l_add_favorite</a></li>\n";
					}
					else{
						echo"					<li><a href=\"favorite_page.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;l=$l&amp;process=1\"><img src=\"_gfx/icons/favorite_black_18dp.png\" alt=\"favorite_black_18dp.png\" title=\"$l_favorited\" /> $l_favorited</a></li>\n";
					}
				}
				else{
					echo"					<li><a href=\"favorite_page.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;l=$l&amp;process=1\"><img src=\"_gfx/icons/favorite_border_black_18dp.png\" alt=\"favorite_border_black_18dp.png\" title=\"$l_favorite\" /> $l_favorite</a></li>\n";
				}
				echo"
					<li><a href=\"edit_page.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;l=$l\"><img src=\"_gfx/icons/edit_black_18dp.png\" alt=\"edit_black_18dp.png\" title=\"$l_edit\" /> $l_edit</a></li>
					<li><a href=\"delete_page.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;l=$l\"><img src=\"_gfx/icons/delete_black_18dp.png\" alt=\"delete_black_18dp.png\" title=\"$l_delete\" /> $l_delete</a></li>
					<li><a href=\"move_page_up.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;l=$l&amp;process=1\"><img src=\"_gfx/icons/keyboard_arrow_up_black_18dp.png\" alt=\"arrow_upward_black_18dp.png\" /> $l_up</a></li>
					<li><a href=\"move_page_down.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;l=$l&amp;process=1\"><img src=\"_gfx/icons/keyboard_arrow_down_black_18dp.png\" alt=\"arrow_upward_black_18dp.png\" /> $l_down</a></li>
					<li><a href=\"page_history.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;l=$l\"><img src=\"_gfx/icons/history_black_18dp.png\" alt=\"history_black_18dp.png\" /> $l_history</a></li>
					<li><a href=\"print_page.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;l=$l&amp;process=1\"><img src=\"_gfx/icons/print_black_18dp.png\" alt=\"print_black_18dp.png\" /> $l_print</a></li>

					<li><a href=\"search.php?l=$l\"><img src=\"_gfx/icons/search_black_18dp.png\" alt=\"search_black_18dp.png\" title=\"$l_search\" /> $l_search</a></li>
					<li><a href=\"spaces_overview.php?l=$l\"><img src=\"_gfx/icons/home_black_18dp.png\" alt=\"home_black_18dp.png\" title=\"$l_home\" /> $l_home</a></li>
				</ul>
				</div>
				<!-- Head menu -->
				<div class=\"clear\"></div>


				<!-- Prism Javascript -->
					<script type=\"text/javascript\" src=\"_css/prism.js\"></script>
				<!-- //Prism Javascript -->

				<h1>$get_current_page_title</h1>
			
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
				
				$get_current_page_text
				


				<!-- No text? Add table of contents for sub pages -->";
					if($get_current_page_text == ""){
						echo"

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
						$query = "SELECT page_id, page_title, page_description, page_no_of_children, page_weight FROM $t_knowledge_pages_index WHERE page_space_id=$get_current_space_id AND page_parent_id=$get_current_page_id ORDER BY page_weight ASC";
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
								} // C
							} // B
	
						} // A
						echo"
						 </tbody>
						</table>
						";
					}
					echo"
				<!-- //No text? Add table of contents for sub pages -->
				
				<!-- Tags -->
					<div class=\"knowledge_tags\">
						";
						$query_c = "SELECT tag_id, tag_page_id, tag_title, tag_title_clean FROM $t_knowledge_pages_tags WHERE tag_page_id=$get_current_page_id";
						$result_c = mysqli_query($link, $query_c);
						while($row_c = mysqli_fetch_row($result_c)) {
							list($get_tag_id, $get_tag_page_id, $get_tag_title, $get_tag_title_clean) = $row_c;
							
							echo"
							<a href=\"view_tag.php?space_id=$get_current_page_space_id&amp;tag=$get_tag_title_clean&amp;l=$l\">#$get_tag_title</a>
							";
							$x++;
						} 
						echo"
					</div>
				<!-- //Tags -->

				<!-- Comments -->
				<p>
				<a id=\"comments\"></a>
				<a href=\"new_comment_to_page.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;l=$l\" class=\"btn_default\">$l_new_comment</a>
				</p>
				";

				// Feedback
				if (isset($_GET['ft_comment']) && isset($_GET['fm_comment'])) {
					$ft_comment = $_GET['ft_comment'];
				$ft_comment = stripslashes(strip_tags($ft_comment));
				$fm_comment = $_GET['fm_comment'];
				$fm_comment = output_html($fm_comment);
				$fm_comment = str_replace("_", " ", $fm_comment);

				if($ft_comment == "error" OR $ft_comment == "warning" OR $ft_comment == "success" OR $ft_comment == "info"){
					echo"<div class=\"$ft_comment\"><p>$fm_comment</p></div>";
				}
				}



				// Get my user
				$query = "SELECT user_id, user_email, user_name, user_alias, user_language, user_last_online, user_rank, user_login_tries FROM $t_users WHERE user_id=$my_user_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_language, $get_my_user_last_online, $get_my_user_rank, $get_my_user_login_tries) = $row;
	


			
				$query = "SELECT comment_id, comment_page_id, comment_parent_comment_id, comment_title, comment_text, comment_approved, comment_datetime, comment_time, comment_date_print, comment_user_id, comment_user_alias, comment_user_email, comment_user_image_file, comment_user_ip, comment_user_hostname, comment_user_agent, comment_subscribe_to_new_comments, comment_rating, comment_helpful_clicks, comment_useless_clicks, comment_marked_as_spam, comment_spam_checked, comment_spam_checked_comment, comment_read_by_page_author FROM $t_knowledge_pages_comments WHERE comment_page_id=$get_current_page_id ORDER BY comment_id ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_comment_id, $get_comment_page_id, $get_comment_parent_comment_id, $get_comment_title, $get_comment_text, $get_comment_approved, $get_comment_datetime, $get_comment_time, $get_comment_date_print, $get_comment_user_id, $get_comment_user_alias, $get_comment_user_email, $get_comment_user_image_file, $get_comment_user_ip, $get_comment_user_hostname, $get_comment_user_agent, $get_comment_subscribe_to_new_comments, $get_comment_rating, $get_comment_helpful_clicks, $get_comment_useless_clicks, $get_comment_marked_as_spam, $get_comment_spam_checked, $get_comment_spam_checked_comment, $get_comment_read_by_page_author) = $row;


				echo"
				<a id=\"comment$get_comment_id\"></a>
				<div class=\"clear\" style=\"height:14px;\"></div>

				<div class=\"comment_item\">
					<table style=\"width: 100%;\">
					 <tr>
					  <td style=\"width: 80px;vertical-align:top;\">
						<!-- Image -->
							<p style=\"padding: 10px 0px 10px 0px;margin:0;\">
							<a href=\"$root/users/view_profile.php?user_id=$get_comment_user_id&amp;l=$l\">";
							if($get_comment_user_image_file == "" OR !(file_exists("$root/_uploads/users/images/$get_comment_user_id/$get_comment_user_image_file"))){ 
								echo"<img src=\"_gfx/avatar_blank_65.png\" alt=\"avatar_blank_65.png\" class=\"comment_view_avatar\" />";
							} 
							else{ 
								$inp_new_x = 65; // 950
								$inp_new_y = 65; // 640
								$thumb_full_path = "$root/_uploads/users/images/$get_comment_user_id/user_" . $get_comment_user_id . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
								if(!(file_exists("$thumb_full_path"))){
									resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_comment_user_id/$get_comment_user_image_file", "$thumb_full_path");
								}

								echo"	<img src=\"$thumb_full_path\" alt=\"$get_comment_user_image_file\" class=\"comment_view_avatar\" />"; 
							} 
							echo"</a>
							</p>
							<!-- //Image -->
					  </td>
					  <td style=\"vertical-align:top;\">

						<!-- Stars, title and menu -->
						<table style=\"width: 100%;\">
						 <tr>
						  <td>
							<p style=\"margin:0;padding:0;\">
							<b>$get_comment_title</b>
							</p>
						  </td>
						  <td style=\"text-align: right;\">


							<!-- Menu -->
							";
							if(isset($my_user_id)){
								if($get_comment_user_id == "$my_user_id" OR $get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
									echo"
									<a href=\"edit_comment.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;comment_id=$get_comment_id&amp;l=$l\"><img src=\"_gfx/icons/edit_black_18dp.png\" alt=\"edit.png\" title=\"$l_edit\" /></a>
									<a href=\"delete_comment.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;comment_id=$get_comment_id&amp;l=$l\"><img src=\"_gfx/icons/delete_black_18dp.png\" alt=\"delete.png\" title=\"$l_delete\" /></a>
									";
								}
								else{
									if($get_comment_marked_as_spam == "" OR $get_comment_marked_as_spam == "0"){
										echo"
										<a href=\"report_comment.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;comment_id=$get_comment_id&amp;l=$l\"><img src=\"_gfx/icons/outlined_flag_black_18dp.png\" alt=\"outlined_flag_black_18dp.png\" title=\"$l_report\" /></a>
										";
									}
									else{
										echo"
										<a href=\"report_comment.php?space_id=$get_current_page_space_id&amp;page_id=$get_current_page_id&amp;comment_id=$get_comment_id&amp;l=$l\"><img src=\"_gfx/icons/flag_black_18dp.png\" alt=\"outlined_flag_black_18dp.png\" title=\"$l_report\" /></a>
										";
									}
								}
							}
							echo"
							<!-- //Menu -->
						  </td>
						 </tr>
						</table>
						<!-- //Stars, title and menu -->


						<!-- Author + date -->
						<p style=\"margin:0;padding:0;\">
						<span class=\"knowledge_comment_by\">$l_by</span>
						<a href=\"$root/users/view_profile.php?user_id=$get_comment_user_id&amp;l=$l\" class=\"knowledge_comment_author\">$get_comment_user_alias</a>
						<a href=\"#comment$get_comment_id\" class=\"knowledge_comment_date\">$get_comment_date_print</a></span>
						</p>

						<!-- //Author + date -->

						<!-- Comment -->
							<p style=\"margin-top: 0px;padding-top: 0;\">$get_comment_text</p>
						<!-- Comment -->
					  </td>
					 </tr>
					</table>
				</div>
				";
				} // while comments
				echo"
				<!-- //Comments -->
				";
			} // is member of space
		} // logged in
		else{
			echo"
			<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" /> $l_please_log_in...</h1>
			<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=$root/knowledge/view_page.php?space_id=$get_current_space_id"; echo"amp;page_id=$get_current_page_id\">
			";
		}
	} // page found
} // space found


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/$webdesignSav/footer.php");
?>