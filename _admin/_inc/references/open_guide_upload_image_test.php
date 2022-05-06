<?php
/**
*
* File: _admin/_inc/references/open_guide_upload_image_test.php
* Version 
* Date 14:12 03.04.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */
$t_references_title_translations 	= $mysqlPrefixSav . "references_title_translations";
$t_references_categories_main	 	= $mysqlPrefixSav . "references_categories_main";
$t_references_categories_sub 		= $mysqlPrefixSav . "references_categories_sub";
$t_references_index		 	= $mysqlPrefixSav . "references_index";
$t_references_index_groups	 	= $mysqlPrefixSav . "references_index_groups";
$t_references_index_groups_images	= $mysqlPrefixSav . "references_index_groups_images";
$t_references_index_guides	 	= $mysqlPrefixSav . "references_index_guides";
$t_references_index_guides_images	= $mysqlPrefixSav . "references_index_guides_images";



/*- Functions ------------------------------------------------------------------------ */
include("_functions/get_extension.php");


/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;
if(isset($_GET['guide_id'])){
	$guide_id = $_GET['guide_id'];
	$guide_id = strip_tags(stripslashes($guide_id));
	if(!(is_numeric($guide_id))){
		echo"guide_id not numeric";
		die;
	}
}
else{
	$guide_id = "";
}
$guide_id_mysql = quote_smart($link, $guide_id);


$query = "SELECT guide_id, guide_number, guide_title, guide_title_clean, guide_title_short, guide_title_length, guide_short_description, guide_content, guide_group_id, guide_group_title, guide_reference_id, guide_reference_title, guide_read_times, guide_read_ipblock, guide_created, guide_updated, guide_updated_formatted, guide_last_read, guide_last_read_formatted, guide_comments FROM $t_references_index_guides WHERE guide_id=$guide_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_guide_id, $get_current_guide_number, $get_current_guide_title, $get_current_guide_title_clean, $get_current_guide_title_short, $get_current_guide_title_length, $get_current_guide_short_description, $get_current_guide_content, $get_current_guide_group_id, $get_current_guide_group_title, $get_current_guide_reference_id, $get_current_guide_reference_title, $get_current_guide_read_times, $get_current_guide_read_ipblock, $get_current_guide_created, $get_current_guide_updated, $get_current_guide_updated_formatted, $get_current_guide_last_read, $get_current_guide_last_read_formatted, $get_current_guide_comments) = $row;

if($get_current_guide_id == ""){
	echo"<p>Server error 404.</p>";
}
else{
	// Reference
	$query = "SELECT reference_id, reference_title, reference_title_clean, reference_is_active, reference_front_page_intro, reference_description, reference_language, reference_main_category_id, reference_main_category_title, reference_sub_category_id, reference_sub_category_title, reference_image_file, reference_image_thumb, reference_icon_16, reference_icon_32, reference_icon_48, reference_icon_64, reference_icon_96, reference_icon_260, reference_groups_count, reference_guides_count, reference_read_times, reference_read_times_ip_block, reference_created, reference_updated FROM $t_references_index WHERE reference_id=$get_current_guide_reference_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_reference_id, $get_current_reference_title, $get_current_reference_title_clean, $get_current_reference_is_active, $get_current_reference_front_page_intro, $get_current_reference_description, $get_current_reference_language, $get_current_reference_main_category_id, $get_current_reference_main_category_title, $get_current_reference_sub_category_id, $get_current_reference_sub_category_title, $get_current_reference_image_file, $get_current_reference_image_thumb, $get_current_reference_icon_16, $get_current_reference_icon_32, $get_current_reference_icon_48, $get_current_reference_icon_64, $get_current_reference_icon_96, $get_current_reference_icon_260, $get_current_reference_groups_count, $get_current_reference_guides_count, $get_current_reference_read_times, $get_current_reference_read_times_ip_block, $get_current_reference_created, $get_current_reference_updated) = $row;


	// Find group
	$query = "SELECT group_id, group_title, group_title_clean, group_title_short, group_title_length, group_number, group_content, group_reference_id, group_reference_title, group_read_times, group_read_times_ip_block, group_created_datetime, group_updated_datetime, group_updated_formatted, group_last_read, group_last_read_formatted FROM $t_references_index_groups WHERE group_id=$get_current_guide_group_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_group_id, $get_current_group_title, $get_current_group_title_clean, $get_current_group_title_short, $get_current_group_title_length, $get_current_group_number, $get_current_group_content, $get_current_group_reference_id, $get_current_group_reference_title, $get_current_group_read_times, $get_current_group_read_times_ip_block, $get_current_group_created_datetime, $get_current_group_updated_datetime, $get_current_group_updated_formatted, $get_current_group_last_read, $get_current_group_last_read_formatted) = $row;

	// Find category
	$query = "SELECT main_category_id, main_category_title, main_category_title_clean, main_category_description, main_category_language, main_category_created, main_category_updated FROM $t_references_categories_main WHERE main_category_id=$get_current_reference_main_category_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_title_clean, $get_current_main_category_description, $get_current_main_category_language, $get_current_main_category_created, $get_current_main_category_updated) = $row;

	$query = "SELECT sub_category_id, sub_category_title, sub_category_title_clean, sub_category_description, sub_category_main_category_id, sub_category_main_category_title, sub_category_language, sub_category_created, sub_category_updated FROM $t_references_categories_sub WHERE sub_category_id=$get_current_reference_sub_category_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_sub_category_id, $get_current_sub_category_title, $get_current_sub_category_title_clean, $get_current_sub_category_description, $get_current_sub_category_main_category_id, $get_current_sub_category_main_category_title, $get_current_sub_category_language, $get_current_sub_category_created, $get_current_sub_category_updated) = $row;

	
	echo"
	<!-- Upload form -->
		<form method=\"POST\" action=\"index.php?open=$open&amp;page=open_guide_upload_image&amp;guide_id=$get_current_guide_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
		<p>
		<input name=\"inp_image\" type=\"file\" tabindex=\"1\" />
		<input type=\"submit\" value=\"Upload\" tabindex=\"2\" />
		</p>
		</form>
	<!-- //Upload form -->
	";
	
} // found group
?>