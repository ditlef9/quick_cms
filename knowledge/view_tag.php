<?php 
/**
*
* File: howto/view_tags.php
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

if (isset($_GET['tag'])) {
	$tag = $_GET['tag'];
	$tag = stripslashes(strip_tags($tag));
	$tag = output_html($tag);
}
else{
	$tag = "";
}
$tag_mysql = quote_smart($link, $tag);

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
	$website_title = "$get_current_space_title - #$tag";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");

	echo"
	<h1>#$tag</h1>

	<!-- Results -->";

		$query = "SELECT tag_id, tag_page_id FROM $t_knowledge_pages_tags WHERE tag_title_clean=$tag_mysql";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_tag_id, $get_tag_page_id) = $row;

			// Get page
			$query_p = "SELECT $t_knowledge_pages_index.page_id, $t_knowledge_pages_index.page_space_id, $t_knowledge_pages_index.page_title, $t_knowledge_pages_index.page_title_clean, $t_knowledge_pages_index.page_description FROM $t_knowledge_pages_index ";
			$query_p = $query_p . "JOIN $t_knowledge_spaces_members ON $t_knowledge_pages_index.page_space_id=$t_knowledge_spaces_members.member_space_id ";
			$query_p = $query_p . "WHERE $t_knowledge_pages_index.page_id=$get_tag_page_id AND member_user_id=$my_user_id_mysql";
			$result_p = mysqli_query($link, $query_p);
			$row_p = mysqli_fetch_row($result_p);
			list($get_page_id, $get_page_space_id, $get_page_title, $get_page_title_clean, $get_page_description) = $row_p;
			
			if($get_page_id != ""){
				echo"
				<p>
				<a href=\"view_page.php?space_id=$get_page_space_id&amp;page_id=$get_page_id&amp;l=$l\">$get_page_title</a><br />
				$get_page_description
				</p>
				";
			}
		}
	echo"
	<!-- //Results -->
	";

	

} // space found


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/$webdesignSav/footer.php");
?>