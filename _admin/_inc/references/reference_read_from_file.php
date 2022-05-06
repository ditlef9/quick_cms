<?php
/**
*
* File: _admin/_inc/references/reference_read_from_file.php
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


	if($action == ""){
		if($process == "1"){
			
			// Datetime
			$datetime = date("Y-m-d H:i:s");

			// _reference.php
			if(file_exists("../$get_current_reference_title_clean/_reference.php")){
				include("../$get_current_reference_title_clean/_reference.php");

				$inp_reference_title_mysql	 	= quote_smart($link, $reference_title_sav);
				$inp_reference_title_clean_mysql 	= quote_smart($link, $reference_title_clean_sav);
				$inp_reference_is_active_mysql 		= quote_smart($link, $reference_is_active_sav);
				$inp_reference_front_page_intro_mysql 	= quote_smart($link, $reference_front_page_intro_sav);
				$inp_reference_description_mysql 	= quote_smart($link, $reference_description_sav);
				$inp_reference_language_mysql 		= quote_smart($link, $reference_language_sav);
				$inp_reference_main_category_title_mysql = quote_smart($link, $reference_main_category_title_sav);
				$inp_reference_sub_category_title_mysql = quote_smart($link, $reference_sub_category_title_sav);
				$inp_reference_image_file_mysql 	= quote_smart($link, $reference_image_file_sav);
				$inp_reference_image_thumb_mysql 	= quote_smart($link, $reference_image_thumb_sav);
				$inp_reference_icon_a_mysql 		= quote_smart($link, $reference_icon_a_sav);
				$inp_reference_icon_b_mysql 		= quote_smart($link, $reference_icon_b_sav);
				$inp_reference_icon_c_mysql 		= quote_smart($link, $reference_icon_c_sav);
				$inp_reference_icon_d_mysql 		= quote_smart($link, $reference_icon_d_sav);
				$inp_reference_icon_e_mysql		= quote_smart($link, $reference_icon_e_sav);
				$inp_reference_icon_f_mysql 		= quote_smart($link, $reference_icon_f_sav);



				$result = mysqli_query($link, "UPDATE $t_references_index SET 
							reference_title=$inp_reference_title_mysql,
							reference_title_clean=$inp_reference_title_clean_mysql,
							reference_is_active=$inp_reference_is_active_mysql, 
							reference_front_page_intro=$inp_reference_front_page_intro_mysql,
							reference_description=$inp_reference_description_mysql,
							reference_language=$inp_reference_language_mysql,
							reference_image_file=$inp_reference_image_file_mysql,
							reference_image_thumb=$inp_reference_image_thumb_mysql,
							reference_icon_16=$inp_reference_icon_a_mysql, 
							reference_icon_32=$inp_reference_icon_b_mysql, 
							reference_icon_48=$inp_reference_icon_c_mysql, 
							reference_icon_64=$inp_reference_icon_d_mysql, 
							reference_icon_96=$inp_reference_icon_e_mysql, 
							reference_icon_260=$inp_reference_icon_f_mysql, 
							reference_read_times_ip_block='',
							reference_updated='$datetime'
							WHERE reference_id=$get_current_reference_id") or die(mysqli_error($link));

				// Category
				// Find this new sub category
				$query = "SELECT sub_category_id, sub_category_title, sub_category_main_category_id FROM $t_references_categories_sub WHERE sub_category_title=$inp_reference_sub_category_title_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_new_sub_category_id, $get_new_sub_category_title, $get_new_sub_category_main_category_id) = $row;
				if($get_new_sub_category_id != ""){
					// Find new main category
					$query = "SELECT main_category_id, main_category_title FROM $t_references_categories_main WHERE main_category_id=$get_new_sub_category_main_category_id";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_new_main_category_id, $get_new_main_category_title) = $row;

					$inp_sub_category_id_mysql = quote_smart($link, $get_new_sub_category_id);
					$inp_sub_category_title_mysql = quote_smart($link, $get_new_sub_category_title);

					$inp_main_category_id_mysql = quote_smart($link, $get_new_main_category_id);
					$inp_main_category_title_mysql = quote_smart($link, $get_new_main_category_title);

					$result = mysqli_query($link, "UPDATE $t_references_index SET 
								reference_main_category_id=$inp_main_category_id_mysql, 
								reference_main_category_title=$inp_main_category_title_mysql,
								reference_sub_category_id=$inp_sub_category_id_mysql, 
								reference_sub_category_title=$inp_sub_category_title_mysql
							WHERE reference_id=$get_current_reference_id") or die(mysqli_error($link));
				}


			} // ../$get_current_reference_title_clean/_reference.php
					
			
			// _groups_and_guides.php
			if(file_exists("../$get_current_reference_title_clean/_groups_and_guides.php")){
				include("../$get_current_reference_title_clean/_groups_and_guides.php");

				

				// vars
				$datetime = date("Y-m-d H:i:s");
				$date_formatted = date("j M Y");
				$inp_group_number = 0;
				$inp_guide_number = 0;

				// Lang
				$inp_language_mysql = quote_smart($link, $get_current_reference_language);

				// Reference title
				$inp_reference_title_mysql = quote_smart($link, $get_current_reference_title);
				
				for($x=0;$x<sizeof($group_title_sav);$x++){
					$inp_group_number = $inp_group_number+1;
					
					$inp_group_title = trim($group_title_sav[$x]);
					$inp_group_title = output_html($inp_group_title);
					$inp_group_title = str_replace("&iuml;&raquo;&iquest;", "", $inp_group_title);
					$inp_group_title_mysql = quote_smart($link, $inp_group_title);

					$inp_group_title_clean = clean($inp_group_title);
					$inp_group_title_clean_mysql = quote_smart($link, $inp_group_title_clean);


					$inp_group_title_length = strlen($inp_group_title);
					$inp_group_title_length_mysql = quote_smart($link, $inp_group_title_length);

					if($inp_group_title_length  > 27){
						$inp_group_title_short = substr($inp_group_title, 0, 27);
						$inp_group_title_short = $inp_group_title_short . "...";
					}
					else{
						$inp_group_title_short = "";
					}
					$inp_group_title_short_mysql = quote_smart($link, $inp_group_title_short);

					
					// Does it exists?
					$query = "SELECT group_id FROM $t_references_index_groups WHERE group_reference_id=$get_current_reference_id AND group_title=$inp_group_title_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_current_group_id) = $row;
					if($get_current_group_id == ""){
						mysqli_query($link, "INSERT INTO $t_references_index_groups 
						(group_id, group_title, group_title_clean, group_title_short, group_title_length, group_number, group_reference_id, group_reference_title, group_read_times, group_created_datetime, group_updated_datetime) 
						VALUES 
						(NULL, $inp_group_title_mysql, $inp_group_title_clean_mysql, $inp_group_title_short_mysql, $inp_group_title_length_mysql, $inp_group_number, $get_current_reference_id, $inp_reference_title_mysql, 0, '$datetime', '$datetime')")
						or die(mysqli_error($link));

						// Get ID
						$query = "SELECT group_id FROM $t_references_index_groups WHERE group_reference_id=$get_current_reference_id AND group_title=$inp_group_title_mysql";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_current_group_id) = $row;
					}
					
					if(isset($guide_title_sav[$x])){
						for($y=0;$y<sizeof($guide_title_sav[$x]);$y++){
							$inp_guide_number = $inp_guide_number+1;
					
							$inp_guide_title = trim($guide_title_sav[$x][$y]);
							$inp_guide_title = output_html($inp_guide_title);
							$inp_guide_title = str_replace("&iuml;&raquo;&iquest;", "", $inp_guide_title);
							$inp_guide_title_mysql = quote_smart($link, $inp_guide_title);

							$inp_guide_title_clean = clean($inp_guide_title);
							$inp_guide_title_clean_mysql = quote_smart($link, $inp_guide_title_clean);
	
							$inp_guide_title_length = strlen($inp_guide_title);
							$inp_guide_title_length_mysql = quote_smart($link, $inp_guide_title_length);

							if($inp_guide_title_length  > 27){
								$inp_guide_title_short = substr($inp_guide_title, 0, 27);
								$inp_guide_title_short = $inp_guide_title_short . "...";
							}
							else{
								$inp_guide_title_short = "";
							}
							$inp_guide_title_short_mysql = quote_smart($link, $inp_guide_title_short);
					
							$inp_guide_short_description = trim($guide_short_description_sav[$x][$y]);
							$inp_guide_short_description = output_html($inp_guide_short_description);
							$inp_guide_short_description = str_replace("&iuml;&raquo;&iquest;", "", $inp_guide_short_description);
							$inp_guide_short_description_mysql = quote_smart($link, $inp_guide_short_description);

							// Does it exists?
							$query = "SELECT guide_id FROM $t_references_index_guides WHERE guide_title=$inp_guide_title_mysql AND guide_group_id=$get_current_group_id AND guide_reference_id=$get_current_reference_id";
							$result = mysqli_query($link, $query);
							$row = mysqli_fetch_row($result);
							list($get_current_guide_id) = $row;
							if($get_current_guide_id == ""){
								mysqli_query($link, "INSERT INTO $t_references_index_guides 
								(guide_id, guide_number, guide_title, guide_title_clean, guide_title_short, guide_title_length, 
								guide_short_description, guide_group_id, guide_group_title, guide_reference_id, guide_reference_title, 
								guide_read_times, guide_created, guide_updated, guide_comments) 
								VALUES 
								(NULL, $inp_guide_number, $inp_guide_title_mysql, $inp_guide_title_clean_mysql, $inp_guide_title_short_mysql, $inp_guide_title_length_mysql, 
								$inp_guide_short_description_mysql, $get_current_group_id, $inp_group_title_mysql, $get_current_reference_id, $inp_reference_title_mysql, 
								0, '$datetime', '$datetime', 0)")
								or die(mysqli_error($link));

								// Get ID
								$query = "SELECT guide_id FROM $t_references_index_guides WHERE guide_title=$inp_guide_title_mysql AND guide_group_id=$get_current_group_id AND guide_reference_id=$get_current_reference_id";
								$result = mysqli_query($link, $query);
								$row = mysqli_fetch_row($result);
								list($get_current_guide_id) = $row;
							}
						

						} // for guides
					} // isset $guide_title_sav
				} // for groups
			} // _groups_and_guides.php


			$url = "index.php?open=$open&page=$page&reference_id=$get_current_reference_id&editor_language=$editor_language&l=$l&ft=success&fm=files_read";
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
					<li><a href=\"index.php?open=references&amp;page=reference_read_from_file&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"active\">Read from file</a>
					<li><a href=\"index.php?open=references&amp;page=reference_write_to_file&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Write to file</a>
					<li><a href=\"index.php?open=references&amp;page=reference_delete&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
				</ul>
			</div>
			<div class=\"clear\" style=\"height: 10px;\"></div>
		<!-- //Refrence navigation -->

		<!-- Files -->
			<p><b>Files to read from:</b></p>
		
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
			</table>
		<!-- //Files -->
		<!-- Actions -->
			<p><b>Actions:</b><br />
			Do you want to read from files?
			</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$reference_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_warning\">Read from files</a>
			</p>
		<!-- //Actions -->
		";
	} // action ==""
} // found
?>