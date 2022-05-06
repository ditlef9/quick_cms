<?php 
/**
*
* File: howto/new_page.php
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

/*- Tables ------------------------------------------------------------------------------------ */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";

/*- Functions ------------------------------------------------------------------------- */
include("$root/_admin/_functions/encode_national_letters.php");
include("$root/_admin/_functions/decode_national_letters.php");

/*- Variables -------------------------------------------------------------------------------- */
$tabindex = 0;
if (isset($_GET['space_id'])) {
	$space_id = $_GET['space_id'];
	$space_id = stripslashes(strip_tags($space_id));
}
else{
	$space_id = "";
}
$space_id_mysql = quote_smart($link, $space_id);

if($space_id == ""){
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$l_new_page";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");

	echo"
	<h1>$l_new_page</h1>
	
	<!-- Spaces -->
		<h2>$l_select_space</h2>
	
		<div class=\"vertical\">
			<ul>
			";
			$spaces_counter = 0;
			$query = "SELECT space_id, space_title FROM $t_knowledge_spaces_index WHERE space_is_archived='0' ORDER BY space_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_space_id, $get_space_title) = $row;
				echo"			";
				echo"<li><a href=\"new_page.php?space_id=$get_space_id&amp;l=$l\">$get_space_title</a></li>\n";

				$spaces_counter++;
			}
			echo"
			</ul>
		</div>
		";
		if($spaces_counter == 1){
			echo"
			<meta http-equiv=\"refresh\" content=\"0;url=new_page.php?space_id=$get_space_id&amp;l=$l\">
			";
		}
		echo"
	<!-- //Spaces -->
	";
}
else{
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
		/*- Headers ---------------------------------------------------------------------------------- */
		$website_title = "$get_current_space_title";
		if(file_exists("./favicon.ico")){ $root = "."; }
		elseif(file_exists("../favicon.ico")){ $root = ".."; }
		elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
		elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
		include("$root/_webdesign/header.php");

		// Check for user
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
				<h1>$l_your_not_a_member_of_this_space</h1>
				
				<p>$l_only_members_can_create_pages_in_this_space</p>

				<p>
				<a href=\"request_menbership_to_space.php?space_id=$get_current_space_id&amp;l=$l\" class=\"btn_default\">$l_request_membership</a>
				</p>
				";
			}
			else{
				// Rank has to be admin, moderator or editor to create pages
				if($get_member_rank == "admin" OR $get_member_rank == "moderator" OR $get_member_rank == "editor"){
					if($process == "1"){
						$inp_title = $_POST['inp_title'];
						$inp_title = output_html($inp_title);
						if($inp_title == ""){
							$datetime = date("Y-m-d H:i:s");
							$inp_title = "Page without name $datetime";
						}
						$inp_title_mysql = quote_smart($link, $inp_title);

						$inp_title_clean = clean($inp_title);
						$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

						$inp_parent = $_POST['inp_parent'];
						$inp_parent = output_html($inp_parent);
						$inp_parent_mysql = quote_smart($link, $inp_parent);


						$inp_subscribe_to_comments = $_POST['inp_subscribe_to_comments'];
						$inp_subscribe_to_comments = output_html($inp_subscribe_to_comments);
						$inp_subscribe_to_comments_mysql = quote_smart($link, $inp_subscribe_to_comments);


						// Preselected value on or off Auto
						$query = "SELECT preselected_id, preselected_user_id, preselected_subscribe FROM $t_knowledge_preselected_subscribe WHERE preselected_user_id=$my_user_id_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_preselected_id, $get_preselected_user_id, $get_preselected_subscribe) = $row;
						if($get_preselected_id == ""){
							mysqli_query($link, "INSERT INTO $t_knowledge_preselected_subscribe 
							(preselected_id, preselected_user_id, preselected_subscribe) 
							VALUES 
							(NULL, $my_user_id_mysql, $inp_subscribe_to_comments_mysql)")
							or die(mysqli_error($link));
						}
						else{
							$result = mysqli_query($link, "UPDATE $t_knowledge_preselected_subscribe  SET 
									preselected_subscribe=$inp_subscribe_to_comments_mysql WHERE 
									preselected_user_id=$my_user_id_mysql");
						}

						$datetime = date("Y-m-d H:i:s");
						$date_saying = date("j M Y");
						$datetime_saying = date("j. M Y H:i");
	
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

						// Create general
						mysqli_query($link, "INSERT INTO $t_knowledge_pages_index
						(page_id, page_space_id, page_title, page_title_clean, page_parent_id, page_weight, page_allow_comments, page_no_of_comments, page_unique_hits, page_created_datetime, page_created_date_saying, page_created_user_id, page_created_user_alias, page_created_user_email, page_created_user_image, page_created_subscribe_to_comments, page_updated_datetime, page_updated_date_saying, page_updated_user_id, page_updated_user_alias, page_updated_user_email, page_updated_user_image, page_updated_subscribe_to_comments, page_version) 
						VALUES 
						(NULL, $get_current_space_id, $inp_title_mysql, $inp_title_clean_mysql, $inp_parent_mysql, '999', '1', '0', '0', '$datetime', '$date_saying', $get_my_user_id, $inp_my_user_alias_mysql, $inp_my_user_email_mysql, $inp_my_user_image_mysql, $inp_subscribe_to_comments_mysql, '$datetime', '$date_saying', $get_my_user_id, $inp_my_user_alias_mysql, $inp_my_user_email_mysql, $inp_my_user_image_mysql, '0', 1)")
						or die(mysqli_error($link));

						// Get ID
						$query = "SELECT page_id FROM $t_knowledge_pages_index WHERE page_created_datetime='$datetime'";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_current_page_id) = $row;
				
						// Tags
						$inp_tags = $_POST['inp_tags'];
						$inp_tags = output_html($inp_tags);
						$inp_tags_array = explode(" ", $inp_tags);
						for($x=0;$x<sizeof($inp_tags_array);$x++){
							if($inp_tags_array[$x] != ""){
								$inp_tag_title = "$inp_tags_array[$x]";
								$inp_tag_title_mysql = quote_smart($link, $inp_tag_title);

								$inp_tag_title_clean = "$inp_tags_array[$x]";
								$inp_tag_title_clean = strtolower($inp_tag_title_clean);
								$inp_tag_title_clean_mysql = quote_smart($link, $inp_tag_title_clean);

								mysqli_query($link, "INSERT INTO $t_knowledge_pages_tags
								(tag_id, tag_page_id, tag_title, tag_title_clean) 
								VALUES 
								(NULL, $get_current_page_id, $inp_tag_title_mysql, $inp_tag_title_clean_mysql)")
								or die(mysqli_error($link));
							}
						}


						// Search engine
						$inp_index_title = "$inp_title | $get_current_space_title | $l_spaces";
						$inp_index_title_mysql = quote_smart($link, "$inp_index_title");

						$inp_index_url = "knowledge/view_page.php?space_id=$get_current_space_id&page_id=$get_current_page_id";
						$inp_index_url_mysql = quote_smart($link, $inp_index_url);

						$inp_index_keywords = "$inp_tags";
						$inp_index_keywords_mysql = quote_smart($link, "$inp_index_keywords");

						$inp_index_language_mysql = quote_smart($link, $l);

						mysqli_query($link, "INSERT INTO $t_search_engine_index 
						(index_id, index_title, index_url, index_short_description, index_keywords, 
						index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
						index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
						index_unique_hits) 
						VALUES 
						(NULL, $inp_index_title_mysql, $inp_index_url_mysql, '', $inp_index_keywords_mysql, 
						'knowledge', 'spaces', $get_current_space_id, 'page_id', $get_current_page_id,
						'0', 0, '$datetime', '$datetime_saying', $inp_index_language_mysql,
						0)")
						or die(mysqli_error($link));
						


						// Header
						header("Location: new_page_step_2_body.php?space_id=$space_id&page_id=$get_current_page_id&l=$l&ft=success&fm=page_created");
						exit;
					}
					echo"<h1>$l_new_page</h1>

					<!-- Focus -->
						<script>
						\$(document).ready(function(){
							\$('[name=\"inp_title\"]').focus();
						});
						</script>
					<!-- //Focus -->
				
					<!-- Form -->
						<form method=\"POST\" action=\"new_page.php?space_id=$space_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

						<p><b>$l_title</b><br />
						<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" style=\"width: 100%;\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						</p>

						<p><b>$l_parent</b><br />
						<select name=\"inp_parent\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
							<option value=\"0\">$l_this_is_parent</option>\n";

						$query = "SELECT page_id, page_title FROM $t_knowledge_pages_index WHERE page_space_id=$get_current_space_id AND page_parent_id='0' ORDER BY page_weight ASC";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_page_id_a, $get_page_title_a) = $row;
							echo"					";
							echo"<option value=\"$get_page_id_a\""; if(isset($page_id) && $page_id == "$get_page_id_a"){ echo" selected=\"selected\""; } echo">$get_page_title_a</option>\n";

							$query_b = "SELECT page_id, page_title FROM $t_knowledge_pages_index WHERE page_space_id=$get_current_space_id AND page_parent_id=$get_page_id_a ORDER BY page_weight ASC";
							$result_b = mysqli_query($link, $query_b);
							while($row_b = mysqli_fetch_row($result_b)) {
								list($get_page_id_b, $get_page_title_b) = $row_b;
								echo"					";
								echo"<option value=\"$get_page_id_b\""; if(isset($page_id) && $page_id == "$get_page_id_b"){ echo" selected=\"selected\""; } echo">&nbsp; &nbsp; $get_page_title_b</option>\n";


								$query_c = "SELECT page_id, page_title FROM $t_knowledge_pages_index WHERE page_space_id=$get_current_space_id AND page_parent_id=$get_page_id_b ORDER BY page_weight ASC";
								$result_c = mysqli_query($link, $query_c);
								while($row_c = mysqli_fetch_row($result_c)) {
									list($get_page_id_c, $get_page_title_c) = $row_c;
									echo"					";
									echo"<option value=\"$get_page_id_c\""; if(isset($page_id) && $page_id == "$get_page_id_c"){ echo" selected=\"selected\""; } echo">&nbsp; &nbsp; &nbsp; &nbsp; $get_page_title_c</option>\n";
								} // c

							} // b

						} // a
						echo"
						</select>
						</p>

						<p><b>$l_tags</b><br />
						<input type=\"text\" name=\"inp_tags\" value=\"\" size=\"25\" style=\"width: 100%;\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						</p>

						<p><b>$l_subscribe_to_comments</b><br />";
						// Preselected value on or off Auto
						$query = "SELECT preselected_id, preselected_user_id, preselected_subscribe FROM $t_knowledge_preselected_subscribe WHERE preselected_user_id=$my_user_id_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_preselected_id, $get_preselected_user_id, $get_preselected_subscribe) = $row;
						echo"
						<input type=\"radio\" name=\"inp_subscribe_to_comments\" value=\"1\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\""; if($get_preselected_subscribe == "1" OR $get_preselected_subscribe == ""){ echo" checked=\"checked\""; } echo" /> $l_yes
						&nbsp;
						<input type=\"radio\" name=\"inp_subscribe_to_comments\" value=\"0\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\""; if($get_preselected_subscribe == "0"){ echo" checked=\"checked\""; } echo" /> $l_no
						</p>

						<p>
						<input type=\"submit\" value=\"$l_create_page\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						</p>
						</form>
					<!-- //Form -->
					";
				}
				else{
					echo"
					<h1>$l_access_denied</h1>
					<p>$l_only_admins_moderators_and_editors_can_add_new_pages</p>
					
					";
				} // have to be admin, moderator or editor
			} // is member
		} // logged in
		else{
			echo"
			<h1><img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" /> New page - Please log in...</h1>
		
			<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=$root/knowledge/new_page.php?space_id=$space_id\">
			";
		} // not logged in
	} // space found
} // space != ""

/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/$webdesignSav/footer.php");
?>