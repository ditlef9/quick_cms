<?php
/**
*
* File: _admin/_inc/references/reference_write_to_file.php
* Version 
* Date 15:13 15.09.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */
$t_references_title_translations = $mysqlPrefixSav . "references_title_translations";
$t_references_categories_main	 = $mysqlPrefixSav . "references_categories_main";
$t_references_categories_sub 	 = $mysqlPrefixSav . "references_categories_sub";
$t_references_index		 = $mysqlPrefixSav . "references_index";
$t_references_index_groups	 = $mysqlPrefixSav . "references_index_groups";
$t_references_index_guides	 = $mysqlPrefixSav . "references_index_guides";

/*- Tables search --------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";

/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;
if(isset($_GET['reference_id'])){
	$reference_id = $_GET['reference_id'];
	$reference_id = strip_tags(stripslashes($reference_id));
}
else{
	$reference_id = "";
}
$reference_id_mysql = quote_smart($link, $reference_id);

$query = "SELECT reference_id, reference_title, reference_title_clean, reference_is_active, reference_front_page_intro, reference_description, reference_language, reference_main_category_id, reference_main_category_title, reference_sub_category_id, reference_sub_category_title, reference_image_file, reference_image_thumb, reference_icon_16, reference_icon_32, reference_icon_48, reference_icon_64, reference_icon_96, reference_icon_260, reference_groups_count, reference_guides_count, reference_read_times, reference_read_times_ip_block, reference_created, reference_updated FROM $t_references_index WHERE reference_id=$reference_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_reference_id, $get_current_reference_title, $get_current_reference_title_clean, $get_current_reference_is_active, $get_current_reference_front_page_intro, $get_current_reference_description, $get_current_reference_language, $get_current_reference_main_category_id, $get_current_reference_main_category_title, $get_current_reference_sub_category_id, $get_current_reference_sub_category_title, $get_current_reference_image_file, $get_current_reference_image_thumb, $get_current_reference_icon_16, $get_current_reference_icon_32, $get_current_reference_icon_48, $get_current_reference_icon_64, $get_current_reference_icon_96, $get_current_reference_icon_260, $get_current_reference_groups_count, $get_current_reference_guides_count, $get_current_reference_read_times, $get_current_reference_read_times_ip_block, $get_current_reference_created, $get_current_reference_updated) = $row;

if($get_current_reference_id == ""){
	echo"<p>Server error 404.</p>";
}
else{

	// Find category
	$query = "SELECT main_category_id, main_category_title, main_category_title_clean, main_category_description, main_category_language, main_category_created, main_category_updated FROM $t_references_categories_main WHERE main_category_id=$get_current_reference_main_category_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_title_clean, $get_current_main_category_description, $get_current_main_category_language, $get_current_main_category_created, $get_current_main_category_updated) = $row;

	$query = "SELECT sub_category_id, sub_category_title, sub_category_title_clean, sub_category_description, sub_category_main_category_id, sub_category_main_category_title, sub_category_language, sub_category_created, sub_category_updated FROM $t_references_categories_sub WHERE sub_category_id=$get_current_reference_sub_category_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_sub_category_id, $get_current_sub_category_title, $get_current_sub_category_title_clean, $get_current_sub_category_description, $get_current_sub_category_main_category_id, $get_current_sub_category_main_category_title, $get_current_sub_category_language, $get_current_sub_category_created, $get_current_sub_category_updated) = $row;

	// Title
	$language_mysql = quote_smart($link, $get_current_reference_language);
	$query = "SELECT reference_title_translation_id, reference_title_translation_title, reference_title_translation_language FROM $t_references_title_translations WHERE reference_title_translation_language=$language_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_reference_title_translation_id, $get_current_reference_title_translation_title, $get_current_reference_title_translation_language) = $row;


	if($action == ""){
		if($process == "1"){
			
			include("_inc/references/reference_write_to_file_include.php");

			$url = "index.php?open=$open&page=$page&reference_id=$get_current_reference_id&editor_language=$editor_language&l=$l&ft=success&fm=files_written";
			header("Location: $url");
			exit;
		} // process

		echo"
		<h1>$get_current_reference_title</h1>
				

		<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=references&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">References</a>
			&gt;
			<a href=\"index.php?open=references&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">References index</a>
			&gt;
			<a href=\"index.php?open=references&amp;page=open_main_category&amp;main_category_id=$get_current_reference_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_reference_main_category_title</a>
			&gt;
			<a href=\"index.php?open=references&amp;page=open_sub_category&amp;sub_category_id=$get_current_reference_sub_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_reference_sub_category_title</a>
			&gt;
			<a href=\"index.php?open=references&amp;page=reference_open&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_reference_title</a>
			</p>
		<!-- //Where am I? -->

		<!-- Refrence navigation -->
			<div class=\"tabs\">
				<ul>
					<li><a href=\"index.php?open=references&amp;page=reference_open&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Info</a>
					<li><a href=\"index.php?open=references&amp;page=reference_groups_and_guides&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Groups and guides</a>
					<li><a href=\"index.php?open=references&amp;page=reference_icon&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Icon</a>
					<li><a href=\"index.php?open=references&amp;page=reference_read_from_file&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Read from file</a>
					<li><a href=\"index.php?open=references&amp;page=reference_write_to_file&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"active\">Write to file</a>
					<li><a href=\"index.php?open=references&amp;page=reference_delete&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
				</ul>
			</div>
			<div class=\"clear\" style=\"height: 10px;\"></div>
		<!-- //Refrence navigation -->


		<!-- Files -->
			<p><b>Files:</b></p>
			<table>
			 <tr>
			  <td style=\"padding-right: 6px;\">
				<span>_reference.php</span>
			  </td>
			  <td>
				";
				if(file_exists("../$get_current_reference_title_clean/_reference.php")){
					$modified = date ("j M Y H:i", filemtime("../$get_current_reference_title_clean/_reference.php"));
					echo"<span>$modified</span>";
				}
				else{
					echo"<span style=\"color:red;\">Doesnt exits</a>";
				}
				echo"
			  </td>
			 </tr>
			 <tr>
			  <td style=\"padding-right: 6px;\">
				<span>_groups_and_guides.php</span>
			  </td>
			  <td>
				";
				if(file_exists("../$get_current_reference_title_clean/_groups_and_guides.php")){
					$modified = date ("j M Y H:i", filemtime("../$get_current_reference_title_clean/_groups_and_guides.php"));
					echo"<span>$modified</span>";
				}
				else{
					echo"<span style=\"color:red;\">Doesnt exits</a>";
				}
				echo"
			  </td>
			 </tr>
			 <tr>
			  <td style=\"padding-right: 6px;\">
				<span>index.php</span>
			  </td>
			  <td>
				";
				if(file_exists("../$get_current_reference_title_clean/index.php")){
					$modified = date ("j M Y H:i", filemtime("../$get_current_reference_title_clean/index.php"));
					echo"<span>$modified</span>";
				}
				else{
					echo"<span style=\"color:red;\">Doesnt exits</a>";
				}
				echo"
			  </td>
			 </tr>
			 <tr>
			  <td style=\"padding-right: 6px;\">
				<span>navigation.php</span>
			  </td>
			  <td>
				";
				if(file_exists("../$get_current_reference_title_clean/navigation.php")){
					$modified = date ("j M Y H:i", filemtime("../$get_current_reference_title_clean/navigation.php"));
					echo"<span>$modified</span>";
				}
				else{
					echo"<span style=\"color:red;\">Doesnt exits</a>";
				}
				echo"
			  </td>
			 </tr>
			</table>

	
		<!-- //Files -->

		<!-- Actions -->
			<p><b>Actions:</b><br />
			Do you want to write to file?
			</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$reference_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_warning\">Write to file</a>
			</p>
		<!-- //Actions -->
		";
	} // action ==""
} // found
?>