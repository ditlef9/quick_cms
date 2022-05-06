<?php 
/**
*
* File: howto/diagram_editor_delete_data.php
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
include("$root/_admin/_translations/site/$l/knowledge/ts_diagrams.php");

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
	$page_id = "0";
}
$page_id_mysql = quote_smart($link, $page_id);
if(isset($_GET['diagram_id'])) {
	$diagram_id = $_GET['diagram_id'];
	$diagram_id = stripslashes(strip_tags($diagram_id));
}
else{
	$diagram_id = "0";
}
$diagram_id_mysql = quote_smart($link, $diagram_id);

if(isset($_GET['data_id'])) {
	$data_id = $_GET['data_id'];
	$data_id = stripslashes(strip_tags($data_id));
}
else{
	$data_id = "";
}
$data_id_mysql = quote_smart($link, $data_id);

if(isset($_GET['toolbox'])) {
	$toolbox = $_GET['toolbox'];
	$toolbox = stripslashes(strip_tags($toolbox));
}
else{
	$toolbox = "uml";
}

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

	// Get diagram
	$query = "SELECT diagram_id, diagram_space_id, diagram_page_id, diagram_type, diagram_version, diagram_title, diagram_file_path, diagram_file_name, diagram_file_thumb_100, diagram_unique_hits, diagram_unique_hits_ip_block, diagram_unique_hits_user_id_block, diagram_created_datetime, diagram_created_date_saying, diagram_created_by_user_id, diagram_created_by_user_alias, diagram_created_by_user_email, diagram_created_by_user_image_file, diagram_created_by_user_ip, diagram_created_by_user_hostname, diagram_created_by_user_agent, diagram_updated_datetime, diagram_updated_date_saying, diagram_updated_by_user_id, diagram_updated_by_user_alias, diagram_updated_by_user_email, diagram_updated_by_user_image_file, diagram_updated_by_user_ip, diagram_updated_by_user_hostname, diagram_updated_by_user_agent FROM $t_knowledge_pages_diagrams WHERE diagram_id=$diagram_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_diagram_id, $get_current_diagram_space_id, $get_current_diagram_page_id, $get_current_diagram_type, $get_current_diagram_version, $get_current_diagram_title, $get_current_diagram_file_path, $get_current_diagram_file_name, $get_current_diagram_file_thumb_100, $get_current_diagram_unique_hits, $get_current_diagram_unique_hits_ip_block, $get_current_diagram_unique_hits_user_id_block, $get_current_diagram_created_datetime, $get_current_diagram_created_date_saying, $get_current_diagram_created_by_user_id, $get_current_diagram_created_by_user_alias, $get_current_diagram_created_by_user_email, $get_current_diagram_created_by_user_image_file, $get_current_diagram_created_by_user_ip, $get_current_diagram_created_by_user_hostname, $get_current_diagram_created_by_user_agent, $get_current_diagram_updated_datetime, $get_current_diagram_updated_date_saying, $get_current_diagram_updated_by_user_id, $get_current_diagram_updated_by_user_alias, $get_current_diagram_updated_by_user_email, $get_current_diagram_updated_by_user_image_file, $get_current_diagram_updated_by_user_ip, $get_current_diagram_updated_by_user_hostname, $get_current_diagram_updated_by_user_agent) = $row;

	if($get_current_diagram_id == ""){
		
		echo"
		<p>Diagram not found.</p>
		";
	}
	else{



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
				echo"Not member";
				die;
			}
			else{

				// Get data
				$query = "SELECT data_id, data_space_id, data_page_id, data_diagram_id, data_cord_start_x, data_cord_start_y, data_cord_start_x_px, data_cord_start_y_px, data_cord_end_x, data_cord_end_y, data_width_x, data_width_y, data_width_x_px, data_width_y_px, data_border_color, data_background_color, data_cord_toolbox, data_cord_tool FROM $t_knowledge_pages_diagrams_data WHERE data_id=$data_id_mysql AND data_space_id=$get_current_diagram_space_id AND data_page_id=$get_current_diagram_page_id AND data_diagram_id=$get_current_diagram_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_data_id, $get_current_data_space_id, $get_current_data_page_id, $get_current_data_diagram_id, $get_current_data_cord_start_x, $get_current_data_cord_start_y, $get_current_data_cord_start_x_px, $get_current_data_cord_start_y_px, $get_current_data_cord_end_x, $get_current_data_cord_end_y, $get_current_data_width_x, $get_current_data_width_y, $get_current_data_width_x_px, $get_current_data_width_y_px, $get_current_data_border_color, $get_current_data_background_color, $get_current_data_cord_toolbox, $get_current_data_cord_tool) = $row;

				if($get_current_data_id == ""){
					echo"Data not found";
					die;
				}
				else{
					
					$inp_headline =  $_POST['inp_headline'];
					$inp_headline = output_html($inp_headline);
					$inp_headline_mysql = quote_smart($link, $inp_headline);

					$result = mysqli_query($link, "UPDATE $t_knowledge_pages_diagrams_data  SET data_headline=$inp_headline_mysql WHERE data_id=$get_current_data_id") or die(mysqli_error($link));

					$time_hi = date("H:i");
					$url = "diagram_editor.php?space_id=$get_current_diagram_space_id&page_id=$get_current_diagram_page_id&diagram_id=$get_current_diagram_id&toolbox=$toolbox&tool=$tool&l=$l&ft=success&fm=saved $time_hi";
					header("Location: $url");
					exit;
				}

			} // is member
		} // logged in
		else{
			echo"Not logged in";
		}
	} // diagram found
} // space found

?>