<?php
/**
*
* File: _admin/_inc/references/courses_open.php
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
			&gt;
			<a href=\"index.php?open=references&amp;page=reference_groups_and_guides&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Groups and guides</a>
			</p>
		<!-- //Where am I? -->

		<!-- Refrence navigation -->
			<div class=\"tabs\">
				<ul>
					<li><a href=\"index.php?open=references&amp;page=reference_open&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Info</a>
					<li><a href=\"index.php?open=references&amp;page=reference_groups_and_guides&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"active\">Groups and guides</a>
					<li><a href=\"index.php?open=references&amp;page=reference_icon&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Icon</a>
					<li><a href=\"index.php?open=references&amp;page=reference_read_from_file&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Read from file</a>
					<li><a href=\"index.php?open=references&amp;page=reference_write_to_file&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Write to file</a>
					<li><a href=\"index.php?open=references&amp;page=reference_delete&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
				</ul>
			</div>
			<div class=\"clear\" style=\"height: 10px;\"></div>
		<!-- //Refrence navigation -->

		<!-- Actions -->
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=new_group&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">New group</a>
			</p>
		<!-- //Actions -->


		<!-- Groups and guides -->

			

			<table class=\"hor-zebra\">
			 <thead>
			  <tr>
			   <th scope=\"col\">
				<span>No</span>
			   </th>
			   <th scope=\"col\">
				<span>Title</span>
			   </th>
			   <th scope=\"col\">
				<span>Unique hits</span>
			   </th>
			   <th scope=\"col\">
				<span>Actions</span>
			   </th>
			  </tr>
			 </thead>
			 <tbody>";
			$total_groups = 0;
			$total_guides = 0;
			$query = "SELECT group_id, group_title, group_number, group_read_times FROM $t_references_index_groups WHERE group_reference_id=$get_current_reference_id ORDER BY group_number ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_group_id, $get_group_title, $get_group_number, $get_group_read_times) = $row;


				// Question number
				$total_groups = $total_groups+1;
				if($total_groups != "$get_group_number"){
					$result_update = mysqli_query($link, "UPDATE $t_references_index_groups SET group_number=$total_groups WHERE group_id=$get_group_id");
					$get_group_number = "$total_groups";
				}

				echo"
				  <tr>
				   <td>
					<span><b>$get_group_number</b></span>
				   </td>
				   <td>
					<span><b>
					<a href=\"index.php?open=$open&amp;page=open_group&amp;group_id=$get_group_id&amp;editor_language=$editor_language&amp;l=$l\">$get_group_title</a>
					</b></span>
				   </td>
				   <td>
					<span><b>$get_group_read_times</b></span>
				   </td>
				   <td>
					<span><b>
					<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=new_guide&amp;group_id=$get_group_id&amp;editor_language=$editor_language&amp;l=$l\">New guide</a>
					&middot;
					<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=edit_group&amp;group_id=$get_group_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
					&middot;
					<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=delete_group&amp;group_id=$get_group_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
					</b></span>
				   </td>
				  </tr>";

				
				$query_lessons = "SELECT guide_id, guide_title, guide_number, guide_read_times, guide_group_id, guide_group_title, guide_reference_id, guide_reference_title FROM $t_references_index_guides WHERE guide_group_id=$get_group_id ORDER BY guide_number ASC";
				$result_lessons = mysqli_query($link, $query_lessons);
				while($row_lessons = mysqli_fetch_row($result_lessons)) {
					list($get_guide_id, $get_guide_title, $get_guide_number, $get_guide_read_times, $get_guide_group_id, $get_guide_group_title, $get_guide_reference_id, $get_guide_reference_title) = $row_lessons;

					// Guidenumber
					$total_guides = $total_guides+1;
					if($total_guides != "$get_guide_number"){
						$result_update = mysqli_query($link, "UPDATE $t_references_index_guides SET guide_number=$total_guides WHERE guide_id=$get_guide_id");
						$get_guide_number = "$total_guides";
					}
					if($get_guide_group_title != "$get_group_title"){
						echo"<p><b>Group title mismathc:</b> get_guide_group_title != get_group_title ($get_guide_group_title != $get_group_title). Updating guide titles to group title ($get_group_title). Please update site to get the new titles.</p>";
						$inp_title_mysql = quote_smart($link, $get_group_title);
						$result_update = mysqli_query($link, "UPDATE $t_references_index_guides SET guide_group_title=$inp_title_mysql WHERE guide_id=$get_guide_id");
					}

					echo"
					 <tr>
					  <td class=\"odd\" style=\"padding-left: 10px;\">
						<span>$get_guide_number</span>
					  </td>
					  <td class=\"odd\">
						<span>
						<a href=\"index.php?open=$open&amp;page=open_guide&amp;guide_id=$get_guide_id&amp;editor_language=$editor_language&amp;l=$l\">$get_guide_title</a>
						</span>
					  </td>
					  <td class=\"odd\">
						<span>$get_guide_read_times</span>
					  </td>
					  <td class=\"odd\">
						<span>
						<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=edit_guide&amp;guide_id=$get_guide_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
						&middot;
						<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=delete_guide&amp;guide_id=$get_guide_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
						</span>
					  </td>
					 </tr>";

				} // while guides

			} // while groups
			echo"
			 </tbody>
			</table>
		<!-- //Groups and guides -->
		";
	} // action ==""
	elseif($action == "new_group"){
		if($process == "1"){
			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_title_clean = clean($inp_title);
			$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

			$inp_reference_title_mysql = quote_smart($link, $get_current_reference_title);

			$datetime = date("Y-m-d H:i:s");
			$datetime_saying = date("j M Y H:i");

			mysqli_query($link, "INSERT INTO $t_references_index_groups
			(group_id, group_title, group_title_clean, group_number, group_reference_id, group_reference_title, group_read_times, group_created_datetime, group_updated_datetime) 
			VALUES 
			(NULL, $inp_title_mysql, $inp_title_clean_mysql, 99, $get_current_reference_id, $inp_reference_title_mysql, 0, '$datetime', '$datetime')")
			or die(mysqli_error($link));

			// Get ID
			$query = "SELECT group_id, group_title, group_title_clean, group_title_short, group_title_length, group_number, group_reference_id, group_reference_title, group_read_times, group_read_times_ip_block, group_created_datetime, group_updated_datetime, group_updated_formatted, group_last_read, group_last_read_formatted FROM $t_references_index_groups WHERE group_created_datetime='$datetime'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_group_id, $get_current_group_title, $get_current_group_title_clean, $get_current_group_title_short, $get_current_group_title_length, $get_current_group_number, $get_current_group_reference_id, $get_current_group_reference_title, $get_current_group_read_times, $get_current_group_read_times_ip_block, $get_current_group_created_datetime, $get_current_group_updated_datetime, $get_current_group_updated_formatted, $get_current_group_last_read, $get_current_group_last_read_formatted) = $row;

			// Make file
			include("_inc/references/reference_write_to_file_include.php");

			// Search engine
			$inp_index_title = "$inp_title | $get_current_reference_title | $l_references";
			$inp_index_title_mysql = quote_smart($link, $inp_index_title);

			$inp_index_url = "$get_current_reference_title_clean/index.php?reference_id=$get_current_reference_id&group_id=$get_current_group_id";
			$inp_index_url_mysql = quote_smart($link, $inp_index_url);

	
			$inp_index_language_mysql = quote_smart($link, $get_current_reference_language);
			
			mysqli_query($link, "INSERT INTO $t_search_engine_index 
			(index_id, index_title, index_url, index_short_description, index_keywords, 
			index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
			index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
			index_unique_hits) 
			VALUES 
			(NULL, $inp_index_title_mysql, $inp_index_url_mysql, '', '', 
			'references', 'groups', '0', 'group_id', $get_current_group_id,
			'0', 0, '$datetime', '$datetime_saying', $inp_index_language_mysql,
			0)")
			or die(mysqli_error($link));

			// Header
			$url = "index.php?open=$open&page=open_group&group_id=$get_current_group_id&editor_language=$editor_language&ft=success&fm=group_$inp_title" . "_saved";
			header("Location: $url");
			exit;
		}
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
			&gt;
			<a href=\"index.php?open=references&amp;page=reference_groups_and_guides&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Groups and guides</a>
			&gt;
			<a href=\"index.php?open=references&amp;page=reference_groups_and_guides&amp;reference_id=$get_current_reference_id&amp;action=$action&amp;editor_language=$editor_language&amp;l=$l\">New group</a>
			</p>
		<!-- //Where am I? -->

		<!-- Left and right -->
			<table>
			 <tr>
			  <td style=\"padding: 0px 50px 0px 0px;vertical-align: top;\">
				<!-- New group form -->
					<h2>New group</h2>
					<script>
					\$(document).ready(function(){
						\$('[name=\"inp_title\"]').focus();
					});
					</script>
			
					<form method=\"post\" action=\"index.php?open=references&amp;page=reference_groups_and_guides&amp;reference_id=$get_current_reference_id&amp;action=$action&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

					<p><b>Group title:</b><br />
					<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" style=\"width: 90%;\" />
					</p>
					<p>
					<input type=\"submit\" value=\"Create\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
					</p>
				<!-- //New group form -->
			
			  </td>
			  <td style=\"vertical-align: top;\">	
				
				<!-- Groups and guides -->
					<h2>Groups and guides</h2>
			

					<table class=\"hor-zebra\">
					 <thead>
					  <tr>
					   <th scope=\"col\">
						<span>No</span>
					   </th>
					   <th scope=\"col\">
						<span>Title</span>
					   </th>
					   <th scope=\"col\">
						<span>Actions</span>
					   </th>
					  </tr>
					 </thead>
					 <tbody>";
					$total_groups = 0;
					$total_guides = 0;
					$query = "SELECT group_id, group_title, group_number FROM $t_references_index_groups WHERE group_reference_id=$get_current_reference_id ORDER BY group_number ASC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_group_id, $get_group_title, $get_group_number) = $row;


						// Question number
						$total_groups = $total_groups+1;
						if($total_groups != "$get_group_number"){
							$result_update = mysqli_query($link, "UPDATE $t_references_index_groups SET group_number=$total_groups WHERE group_id=$get_group_id");
							$get_group_number = "$total_groups";
						}

						echo"
						  <tr>
						   <td>
							<span><b>$get_group_number</b></span>
						   </td>
						   <td>
							<span><b>$get_group_title</b></span>
						   </td>
						   <td>
							<span><b>
							<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=new_guide&amp;group_id=$get_group_id&amp;editor_language=$editor_language&amp;l=$l\">New guide</a>
							&middot;
							<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=edit_group&amp;group_id=$get_group_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
							&middot;
							<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=delete_group&amp;group_id=$get_group_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
							</b></span>
						   </td>
						  </tr>";

					} // while groups
					echo"
					 </tbody>
					</table>
				<!-- //Groups and guides -->
			  </td>
			 </tr>
			</table>
		<!-- //Left and right -->
		
		";
	} // action == new group
	elseif($action == "edit_group"){
		if(isset($_GET['group_id'])){
			$group_id = $_GET['group_id'];
			$group_id = strip_tags(stripslashes($group_id));
		}
		else{
			$group_id = "";
		}
		$group_id_mysql = quote_smart($link, $group_id);

		$query = "SELECT group_id, group_title, group_title_clean, group_number, group_reference_id, group_reference_title, group_read_times, group_read_times_ip_block, group_created_datetime, group_updated_datetime FROM $t_references_index_groups WHERE group_id=$group_id_mysql AND group_reference_id=$get_current_reference_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_group_id, $get_current_group_title, $get_current_group_title_clean, $get_current_group_number, $get_current_group_reference_id, $get_current_group_reference_title, $get_current_group_read_times, $get_current_group_read_times_ip_block, $get_current_group_created_datetime, $get_current_group_updated_datetime) = $row;

		if($get_current_group_id == ""){
			echo"<p>Server error 404.</p>";
		}
		else{
			if($process == "1"){
				$inp_title = $_POST['inp_title'];
				$inp_title = output_html($inp_title);
				$inp_title_mysql = quote_smart($link, $inp_title);

				$inp_title_clean = clean($inp_title);
				$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

				$inp_reference_title_mysql = quote_smart($link, $get_current_reference_title);

				$datetime = date("Y-m-d H:i:s");
				$datetime_saying = date("j M Y H:i");

				$result = mysqli_query($link, "UPDATE $t_references_index_groups SET 
								group_title=$inp_title_mysql, 
								group_title_clean=$inp_title_clean_mysql,
								group_reference_title=$inp_reference_title_mysql,
								group_updated_datetime='$datetime'
								WHERE group_id=$get_current_group_id") or die(mysqli_error($link));



				// Search engine
				$inp_index_title = "$inp_title | $get_current_reference_title | $l_references";
				$inp_index_title_mysql = quote_smart($link, $inp_index_title);

				$inp_index_url = "$get_current_reference_title_clean/index.php?reference_id=$get_current_reference_id&group_id=$get_current_group_id";
				$inp_index_url_mysql = quote_smart($link, $inp_index_url);

	
				$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name='references' AND index_reference_name='group_id' AND index_reference_id=$get_current_group_id";
				$result_exists = mysqli_query($link, $query_exists);
				$row_exists = mysqli_fetch_row($result_exists);
				list($get_index_id) = $row_exists;

				$result = mysqli_query($link, "UPDATE $t_search_engine_index SET 
								index_title=$inp_index_title_mysql, 
								index_url=$inp_index_url_mysql,
								index_updated_datetime='$datetime',
								index_updated_datetime_print='$datetime_saying'
								WHERE index_id=$get_index_id") or die(mysqli_error($link));



				// Make file
				include("_inc/references/reference_write_to_file_include.php");

				$url = "index.php?open=$open&page=$page&reference_id=$reference_id&action=$action&group_id=$get_current_group_id&editor_language=$editor_language&ft=success&fm=changes_saved";
				header("Location: $url");
				exit;
			}
			echo"
			<h1>$get_current_group_title</h1>
				

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
				&gt;
				<a href=\"index.php?open=references&amp;page=reference_groups_and_guides&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Groups and guides</a>
				&gt;
				<a href=\"index.php?open=references&amp;page=reference_groups_and_guides&amp;reference_id=$get_current_reference_id&amp;action=$action&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language&amp;l=$l\">Edit group $get_current_group_title</a>
				</p>
			<!-- //Where am I? -->

			<!-- Left and right -->
				<table>
				 <tr>
				  <td style=\"padding: 0px 50px 0px 0px;vertical-align: top;\">
					<!-- Edit group form -->
						<h2>Edit group</h2>
						<script>
						\$(document).ready(function(){
							\$('[name=\"inp_title\"]').focus();
						});
						</script>
			
						<form method=\"post\" action=\"index.php?open=references&amp;page=reference_groups_and_guides&amp;reference_id=$get_current_reference_id&amp;action=$action&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

						<p><b>Title:</b><br />
						<input type=\"text\" name=\"inp_title\" value=\"$get_current_group_title\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						</p>

						<p>
						<input type=\"submit\" value=\"Save changes\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						</p>
					<!-- //Edit module form -->
		
				  </td>
				  <td style=\"vertical-align: top;\">	
					
					<!-- Groups and guides -->
						<h2>Groups and guides</h2>
			

						<table class=\"hor-zebra\">
						 <thead>
						  <tr>
						   <th scope=\"col\">
							<span>No</span>
						   </th>
						   <th scope=\"col\">
							<span>Title</span>
						   </th>
						   <th scope=\"col\">
							<span>Actions</span>
						   </th>
						  </tr>
						 </thead>
						 <tbody>";
						$total_groups = 0;
						$total_guides = 0;
						$query = "SELECT group_id, group_title, group_number FROM $t_references_index_groups WHERE group_reference_id=$get_current_reference_id ORDER BY group_number ASC";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_group_id, $get_group_title, $get_group_number) = $row;


							// Question number
							$total_groups = $total_groups+1;
							if($total_groups != "$get_group_number"){
								$result_update = mysqli_query($link, "UPDATE $t_references_index_groups SET group_number=$total_groups WHERE group_id=$get_group_id");
								$get_group_number = "$total_groups";
							}

							echo"
							  <tr>
						  	 <td>
								<span><b>$get_group_number</b></span>
							   </td>
							   <td>
								<span><b>$get_group_title</b></span>
							   </td>
							   <td>
								<span><b>
								<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=new_guide&amp;group_id=$get_group_id&amp;editor_language=$editor_language&amp;l=$l\">New guide</a>
								&middot;
								<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=edit_group&amp;group_id=$get_group_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
								&middot;
								<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=delete_group&amp;group_id=$get_group_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
								</b></span>
							   </td>
							  </tr>";

						} // while groups
						echo"
						 </tbody>
						</table>
					<!-- //Groups and guides -->
				  </td>
				 </tr>
				</table>
			<!-- //Left and right -->
			";
		} // group found
	} // action == edit group
	elseif($action == "delete_group"){
		if(isset($_GET['group_id'])){
			$group_id = $_GET['group_id'];
			$group_id = strip_tags(stripslashes($group_id));
		}
		else{
			$group_id = "";
		}
		$group_id_mysql = quote_smart($link, $group_id);

		$query = "SELECT group_id, group_title, group_title_clean, group_number, group_reference_id, group_reference_title, group_read_times, group_read_times_ip_block, group_created_datetime, group_updated_datetime FROM $t_references_index_groups WHERE group_id=$group_id_mysql AND group_reference_id=$get_current_reference_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_group_id, $get_current_group_title, $get_current_group_title_clean, $get_current_group_number, $get_current_group_reference_id, $get_current_group_reference_title, $get_current_group_read_times, $get_current_group_read_times_ip_block, $get_current_group_created_datetime, $get_current_group_updated_datetime) = $row;

		if($get_current_group_id == ""){
			echo"<p>Server error 404.</p>";
		}
		else{
			if($process == "1"){
				

				$result = mysqli_query($link, "DELETE FROM $t_references_index_groups WHERE group_id=$get_current_group_id") or die(mysqli_error($link));

				// Search engine :: Group


				// Search engine
				$result = mysqli_query($link, "DELETE FROM $t_search_engine_index WHERE index_module_name='references' AND index_reference_name='group_id' AND index_reference_id=$get_current_group_id") or die(mysqli_error($link));
			

				// Search engine :: Lessons (TODO)

				$url = "index.php?open=$open&page=$page&reference_id=$reference_id&group_id=$get_current_group_id&editor_language=$editor_language&ft=success&fm=group_deleted";
				header("Location: $url");
				exit;
			}
			echo"
			<h1>$get_current_group_title</h1>
				

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
				&gt;
				<a href=\"index.php?open=references&amp;page=reference_groups_and_guides&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Groups and guides</a>
				&gt;
				<a href=\"index.php?open=references&amp;page=reference_groups_and_guides&amp;reference_id=$get_current_reference_id&amp;action=$action&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language&amp;l=$l\">Delete group $get_current_group_title</a>
				</p>
			<!-- //Where am I? -->

			<!-- Left and right -->
				<table>
				 <tr>
				  <td style=\"padding: 0px 50px 0px 0px;vertical-align: top;\">
					<!-- Delete group form -->
						<h2>Delete group</h2>
					
						<p>Are you sure you want to delete the group $get_current_group_title?</p>
			
						<p>
						<a href=\"index.php?open=references&amp;page=reference_groups_and_guides&amp;reference_id=$get_current_reference_id&amp;action=$action&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_danger\">Delete</a>
						</p>
					<!-- //Delete group form -->
				  </td>
				  <td style=\"vertical-align: top;\">	
					
					<!-- Groups and guides -->
						<h2>Groups and guides</h2>
			

						<table class=\"hor-zebra\">
						 <thead>
						  <tr>
						   <th scope=\"col\">
							<span>No</span>
						   </th>
						   <th scope=\"col\">
							<span>Title</span>
						   </th>
						   <th scope=\"col\">
							<span>Actions</span>
						   </th>
						  </tr>
						 </thead>
						 <tbody>";
						$total_groups = 0;
						$total_guides = 0;
						$query = "SELECT group_id, group_title, group_number FROM $t_references_index_groups WHERE group_reference_id=$get_current_reference_id ORDER BY group_number ASC";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_group_id, $get_group_title, $get_group_number) = $row;


							// Question number
							$total_groups = $total_groups+1;
							if($total_groups != "$get_group_number"){
								$result_update = mysqli_query($link, "UPDATE $t_references_index_groups SET group_number=$total_groups WHERE group_id=$get_group_id");
								$get_group_number = "$total_groups";
							}

							echo"
							  <tr>
						  	 <td>
								<span><b>$get_group_number</b></span>
							   </td>
							   <td>
								<span><b>$get_group_title</b></span>
							   </td>
							   <td>
								<span><b>
								<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=new_guide&amp;group_id=$get_group_id&amp;editor_language=$editor_language&amp;l=$l\">New guide</a>
								&middot;
								<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=edit_group&amp;group_id=$get_group_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
								&middot;
								<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=delete_group&amp;group_id=$get_group_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
								</b></span>
							   </td>
							  </tr>";

						} // while groups
						echo"
						 </tbody>
						</table>
					<!-- //Groups and guides -->
				  </td>
				 </tr>
				</table>
			<!-- //Left and right -->
			";
		} // group found
	} // action == delete group
	elseif($action == "new_guide"){
		if(isset($_GET['group_id'])){
			$group_id = $_GET['group_id'];
			$group_id = strip_tags(stripslashes($group_id));
		}
		else{
			$group_id = "";
		}
		$group_id_mysql = quote_smart($link, $group_id);

		$query = "SELECT group_id, group_title, group_title_clean, group_number, group_reference_id, group_reference_title, group_read_times, group_read_times_ip_block, group_created_datetime, group_updated_datetime FROM $t_references_index_groups WHERE group_id=$group_id_mysql AND group_reference_id=$get_current_reference_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_group_id, $get_current_group_title, $get_current_group_title_clean, $get_current_group_number, $get_current_group_reference_id, $get_current_group_reference_title, $get_current_group_read_times, $get_current_group_read_times_ip_block, $get_current_group_created_datetime, $get_current_group_updated_datetime) = $row;

		if($get_current_group_id == ""){
			echo"<p>Server error 404.</p>";
		}
		else{
			if($process == "1"){
				$inp_title = $_POST['inp_title'];
				$inp_title = output_html($inp_title);
				$inp_title_mysql = quote_smart($link, $inp_title);

				$inp_title_clean = clean($inp_title);
				$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

				$inp_title_length = strlen($inp_title);
				$inp_title_length_mysql = quote_smart($link, $inp_title_length);

				if($inp_title_length  > 27){
					$inp_title_short = substr($inp_title, 0, 27);
					$inp_title_short = $inp_title_short . "...";
				}
				else{
					$inp_title_short = "";
				}
				$inp_title_short_mysql = quote_smart($link, $inp_title_short);


				$inp_short_description = $_POST['inp_short_description'];
				$inp_short_description = output_html($inp_short_description);
				$inp_short_description_mysql = quote_smart($link, $inp_short_description);


				$inp_reference_title_mysql = quote_smart($link, $get_current_reference_title);
				$inp_group_title_mysql = quote_smart($link, $get_current_group_title);

				$datetime = date("Y-m-d H:i:s");
				$datetime_saying = date("j M Y H:i");

				mysqli_query($link, "INSERT INTO $t_references_index_guides
				(guide_id, guide_number, guide_title, guide_title_clean, guide_title_short, guide_title_length, guide_short_description, guide_group_id, guide_group_title, guide_reference_id, guide_reference_title, guide_read_times, guide_created, guide_updated, guide_comments) 
				VALUES 
				(NULL, 99, $inp_title_mysql, $inp_title_clean_mysql, $inp_title_short_mysql, $inp_title_length_mysql, $inp_short_description_mysql, $get_current_group_id, $inp_group_title_mysql, $get_current_reference_id, $inp_reference_title_mysql, 0, '$datetime', '$datetime', 0)")
				or die(mysqli_error($link));

				// Get ID
				$query = "SELECT guide_id, guide_number, guide_title, guide_title_clean, guide_short_description, guide_group_id, guide_group_title, guide_reference_id, guide_reference_title, guide_read_times, guide_read_ipblock, guide_created, guide_updated, guide_comments FROM $t_references_index_guides WHERE guide_created='$datetime'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_guide_id, $get_current_guide_number, $get_current_guide_title, $get_current_guide_title_clean, $get_current_guide_short_description, $get_current_guide_group_id, $get_current_guide_group_title, $get_current_guide_reference_id, $get_current_guide_reference_title, $get_current_guide_read_times, $get_current_guide_read_ipblock, $get_current_guide_created, $get_current_guide_updated, $get_current_guide_comments) = $row;

				// Search engine
				$inp_index_title = "$inp_title |  $get_current_group_title | $get_current_reference_title | $l_references";
				$inp_index_title_mysql = quote_smart($link, $inp_index_title);

				$inp_index_url = "$get_current_reference_title_clean/$get_current_group_title_clean/$inp_title_clean.php?reference_id=$get_current_reference_id&group_id=$get_current_group_id&guide_id=$get_current_guide_id";
				$inp_index_url_mysql = quote_smart($link, $inp_index_url);

				$inp_index_language_mysql = quote_smart($link, $get_current_reference_language);
			
				mysqli_query($link, "INSERT INTO $t_search_engine_index 
				(index_id, index_title, index_url, index_short_description, index_keywords, 
				index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
				index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
				index_unique_hits) 
				VALUES 
				(NULL, $inp_index_title_mysql, $inp_index_url_mysql, $inp_short_description_mysql, '', 
				'references', 'guides', '0', 'guide_id', $get_current_guide_id,
				'0', 0, '$datetime', '$datetime_saying', $inp_index_language_mysql,
				0)")
				or die(mysqli_error($link));
	

				// Make file
				include("_inc/references/reference_write_to_file_include.php");

				// Move to open
				$url = "index.php?open=$open&page=open_guide&reference_id=$reference_id&group_id=$get_current_group_id&guide_id=$get_current_guide_id&editor_language=$editor_language&ft=success&fm=guide_created";
				header("Location: $url");
				exit;
			}
			echo"
			<h1>$get_current_group_title</h1>
				

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
				&gt;
				<a href=\"index.php?open=references&amp;page=reference_groups_and_guides&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Groups and guides</a>
				&gt;
				<a href=\"index.php?open=references&amp;page=reference_groups_and_guides&amp;reference_id=$get_current_reference_id&amp;action=edit_group&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_group_title</a>
				&gt;
				<a href=\"index.php?open=references&amp;page=reference_groups_and_guides&amp;reference_id=$get_current_reference_id&amp;action=new_guide&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language&amp;l=$l\">New guide</a>
				</p>
			<!-- //Where am I? -->

			<!-- Left and right -->
				<table>
				 <tr>
				  <td style=\"padding: 0px 50px 0px 0px;vertical-align: top;\">
					<!-- New guide form -->
						<h2>New guide to $get_current_group_title</h2>
						<script>
						\$(document).ready(function(){
							\$('[name=\"inp_title\"]').focus();
						});
						</script>
			
						<form method=\"post\" action=\"index.php?open=references&amp;page=reference_groups_and_guides&amp;reference_id=$get_current_reference_id&amp;action=$action&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

						<p><b>Title:</b><br />
						<input type=\"text\" name=\"inp_title\" value=\"\" size=\"45\" autocomplete=\"off\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						</p>

						<p><b>Short description:</b><br />
						<input type=\"text\" name=\"inp_short_description\" value=\"\" size=\"45\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						</p>

						<p>
						<input type=\"submit\" value=\"Create\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						</p>
					<!-- //New guide form -->
		
				  </td>
				  <td style=\"vertical-align: top;\">	
					
					<!-- Groups and guides -->
						<h2>Groups and guides</h2>
			

						<table class=\"hor-zebra\">
						 <thead>
						  <tr>
						   <th scope=\"col\">
							<span>No</span>
						   </th>
						   <th scope=\"col\">
							<span>Title</span>
						   </th>
						   <th scope=\"col\">
							<span>Actions</span>
						   </th>
						  </tr>
						 </thead>
						 <tbody>";
						$total_groups = 0;
						$total_guides = 0;
						$query = "SELECT group_id, group_title, group_number FROM $t_references_index_groups WHERE group_reference_id=$get_current_reference_id ORDER BY group_number ASC";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_group_id, $get_group_title, $get_group_number) = $row;


							// Question number
							$total_groups = $total_groups+1;
							if($total_groups != "$get_group_number"){
								$result_update = mysqli_query($link, "UPDATE $t_references_index_groups SET group_number=$total_groups WHERE group_id=$get_group_id");
								$get_group_number = "$total_groups";
							}

							echo"
							  <tr>
						  	 <td>
								<span><b>$get_group_number</b></span>
							   </td>
							   <td>
								<span><b>$get_group_title</b></span>
							   </td>
							   <td>
								<span><b>
								<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=new_guide&amp;group_id=$get_group_id&amp;editor_language=$editor_language&amp;l=$l\">New guide</a>
								&middot;
								<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=edit_group&amp;group_id=$get_group_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
								&middot;
								<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=delete_group&amp;group_id=$get_group_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
								</b></span>
							   </td>
							  </tr>";
						
							if($get_group_id == "$get_current_group_id"){

								$query_lessons = "SELECT guide_id, guide_title, guide_number FROM $t_references_index_guides WHERE guide_group_id=$get_group_id ORDER BY guide_number ASC";
								$result_lessons = mysqli_query($link, $query_lessons);
								while($row_lessons = mysqli_fetch_row($result_lessons)) {
									list($get_guide_id, $get_guide_title, $get_guide_number) = $row_lessons;

									// Guidenumber
									$total_guides = $total_guides+1;
									if($total_guides != "$get_guide_number"){
										$result_update = mysqli_query($link, "UPDATE $t_references_index_guides SET guide_number=$total_guides WHERE guide_id=$get_guide_id");
										$get_guide_number = "$total_guides";
									}

									echo"
									 <tr>
									  <td class=\"odd\" style=\"padding-left: 10px;\">
										<span>$get_guide_number</span>
									  </td>
									  <td class=\"odd\">
										<span>$get_guide_title</span>
									  </td>
									  <td class=\"odd\">
										<span>
										<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=edit_guide&amp;guide_id=$get_guide_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
										&middot;
										<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=delete_guide&amp;guide_id=$get_guide_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
										</span>
									  </td>
									 </tr>";

								} // while guides
							} // if($get_group_id == "$get_current_group_id"){
						} // while groups
						echo"
						 </tbody>
						</table>
					<!-- //Groups and guides -->
				  </td>
				 </tr>
				</table>
			<!-- //Left and right -->
			";
		} // group found
	} // action == new_guide
	elseif($action == "edit_guide"){
		if(isset($_GET['guide_id'])){
			$guide_id = $_GET['guide_id'];
			$guide_id = strip_tags(stripslashes($guide_id));
		}
		else{
			$guide_id = "";
		}
		$guide_id_mysql = quote_smart($link, $guide_id);

		$query = "SELECT guide_id, guide_number, guide_title, guide_title_clean, guide_short_description, guide_group_id, guide_group_title, guide_reference_id, guide_reference_title, guide_read_times, guide_read_ipblock, guide_created, guide_updated, guide_comments FROM $t_references_index_guides WHERE guide_id=$guide_id_mysql AND guide_reference_id=$get_current_reference_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_guide_id, $get_current_guide_number, $get_current_guide_title, $get_current_guide_title_clean, $get_current_guide_short_description, $get_current_guide_group_id, $get_current_guide_group_title, $get_current_guide_reference_id, $get_current_guide_reference_title, $get_current_guide_read_times, $get_current_guide_read_ipblock, $get_current_guide_created, $get_current_guide_updated, $get_current_guide_comments) = $row;


		if($get_current_guide_id == ""){
			echo"<p>Server error 404.</p>";
		}
		else{
			// Get group
			$query = "SELECT group_id, group_title, group_title_clean, group_number, group_reference_id, group_reference_title, group_read_times, group_read_times_ip_block, group_created_datetime, group_updated_datetime FROM $t_references_index_groups WHERE group_id=$get_current_guide_group_id AND group_reference_id=$get_current_reference_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_group_id, $get_current_group_title, $get_current_group_title_clean, $get_current_group_number, $get_current_group_reference_id, $get_current_group_reference_title, $get_current_group_read_times, $get_current_group_read_times_ip_block, $get_current_group_created_datetime, $get_current_group_updated_datetime) = $row;



			if($process == "1"){
				$inp_title = $_POST['inp_title'];
				$inp_title = output_html($inp_title);
				$inp_title_mysql = quote_smart($link, $inp_title);

				$inp_title_clean = clean($inp_title);
				$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

				$inp_title_length = strlen($inp_title);
				$inp_title_length_mysql = quote_smart($link, $inp_title_length);

				if($inp_title_length  > 27){
					$inp_title_short = substr($inp_title, 0, 27);
					$inp_title_short = $inp_title_short . "...";
				}
				else{
					$inp_title_short = "";
				}
				$inp_title_short_mysql = quote_smart($link, $inp_title_short);

				$inp_short_description = $_POST['inp_short_description'];
				$inp_short_description = output_html($inp_short_description);
				$inp_short_description_mysql = quote_smart($link, $inp_short_description);


				$inp_reference_title_mysql = quote_smart($link, $get_current_reference_title);
				$inp_group_title_mysql = quote_smart($link, $get_current_group_title);

				$datetime = date("Y-m-d H:i:s");
				$datetime_saying = date("j M Y H:i");



				$result = mysqli_query($link, "UPDATE $t_references_index_guides SET 
								guide_title=$inp_title_mysql, 
								guide_title_clean=$inp_title_clean_mysql,
								guide_title_short=$inp_title_short_mysql,
								guide_title_length=$inp_title_length_mysql,
								guide_short_description=$inp_short_description_mysql,
								guide_group_title=$inp_group_title_mysql,
								guide_reference_title=$inp_reference_title_mysql,
								guide_updated='$datetime'
								WHERE guide_id=$get_current_guide_id") or die(mysqli_error($link));

				// Search engine
				$inp_index_title = "$inp_title |  $get_current_group_title | $get_current_reference_title | $l_references";
				$inp_index_title_mysql = quote_smart($link, $inp_index_title);

				$inp_index_url = "$get_current_reference_title_clean/$get_current_group_title_clean/$inp_title_clean.php?reference_id=$get_current_reference_id&group_id=$get_current_group_id&guide_id=$get_current_guide_id";
				$inp_index_url_mysql = quote_smart($link, $inp_index_url);

				$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name='references' AND index_reference_name='guide_id' AND index_reference_id=$get_current_guide_id";
				$result_exists = mysqli_query($link, $query_exists);
				$row_exists = mysqli_fetch_row($result_exists);
				list($get_index_id) = $row_exists;

				$result = mysqli_query($link, "UPDATE $t_search_engine_index SET 
								index_title=$inp_index_title_mysql,
								index_url=$inp_index_url_mysql,
								index_short_description=$inp_short_description_mysql,
								index_updated_datetime='$datetime',
								index_updated_datetime_print='$datetime_saying'
							WHERE index_id=$get_index_id") or die(mysqli_error($link));
			

				// Make file
				include("_inc/references/reference_write_to_file_include.php");

				$url = "index.php?open=$open&page=$page&reference_id=$reference_id&action=$action&guide_id=$get_current_guide_id&editor_language=$editor_language&ft=success&fm=changes_saved";
				header("Location: $url");
				exit;
			}
			echo"
			<h1>$get_current_group_title</h1>
				

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
				&gt;
				<a href=\"index.php?open=references&amp;page=reference_groups_and_guides&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Groups and guides</a>
				&gt;
				<a href=\"index.php?open=references&amp;page=reference_groups_and_guides&amp;reference_id=$get_current_reference_id&amp;action=edit_group&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_group_title</a>
				&gt;
				<a href=\"index.php?open=references&amp;page=reference_groups_and_guides&amp;reference_id=$get_current_reference_id&amp;action=edit_guide&amp;guide_id=$get_current_guide_id&amp;editor_language=$editor_language&amp;l=$l\">Edit guide $get_current_guide_title</a>
				</p>
			<!-- //Where am I? -->

			<!-- Left and right -->
				<table>
				 <tr>
				  <td style=\"padding: 0px 50px 0px 0px;vertical-align: top;\">
					<!-- New guide form -->
						<h2>Edit guide $get_current_guide_title</h2>
						<script>
						\$(document).ready(function(){
							\$('[name=\"inp_title\"]').focus();
						});
						</script>
			
						<form method=\"post\" action=\"index.php?open=references&amp;page=reference_groups_and_guides&amp;reference_id=$get_current_reference_id&amp;action=edit_guide&amp;guide_id=$get_current_guide_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

						<p><b>Title:</b><br />
						<input type=\"text\" name=\"inp_title\" value=\"$get_current_guide_title\" size=\"45\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						</p>

						<p><b>Short description:</b><br />
						<input type=\"text\" name=\"inp_short_description\" value=\"$get_current_guide_short_description\" size=\"45\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						</p>

						<p>
						<input type=\"submit\" value=\"Save changes\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						</p>
					<!-- //New guide form -->
		
				  </td>
				  <td style=\"vertical-align: top;\">	
					
					<!-- Groups and guides -->
						<h2>Groups and guides</h2>
			

						<table class=\"hor-zebra\">
						 <thead>
						  <tr>
						   <th scope=\"col\">
							<span>No</span>
						   </th>
						   <th scope=\"col\">
							<span>Title</span>
						   </th>
						   <th scope=\"col\">
							<span>Actions</span>
						   </th>
						  </tr>
						 </thead>
						 <tbody>";
						$total_groups = 0;
						$total_guides = 0;
						$query = "SELECT group_id, group_title, group_number FROM $t_references_index_groups WHERE group_reference_id=$get_current_reference_id ORDER BY group_number ASC";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_group_id, $get_group_title, $get_group_number) = $row;


							// Question number
							$total_groups = $total_groups+1;
							if($total_groups != "$get_group_number"){
								$result_update = mysqli_query($link, "UPDATE $t_references_index_groups SET group_number=$total_groups WHERE group_id=$get_group_id");
								$get_group_number = "$total_groups";
							}

							echo"
							  <tr>
						  	 <td>
								<span><b>$get_group_number</b></span>
							   </td>
							   <td>
								<span><b>$get_group_title</b></span>
							   </td>
							   <td>
								<span><b>
								<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=new_guide&amp;group_id=$get_group_id&amp;editor_language=$editor_language&amp;l=$l\">New guide</a>
								&middot;
								<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=edit_group&amp;group_id=$get_group_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
								&middot;
								<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=delete_group&amp;group_id=$get_group_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
								</b></span>
							   </td>
							  </tr>";
						
							if($get_group_id == "$get_current_group_id"){

								$query_lessons = "SELECT guide_id, guide_title, guide_number FROM $t_references_index_guides WHERE guide_group_id=$get_group_id ORDER BY guide_number ASC";
								$result_lessons = mysqli_query($link, $query_lessons);
								while($row_lessons = mysqli_fetch_row($result_lessons)) {
									list($get_guide_id, $get_guide_title, $get_guide_number) = $row_lessons;

									// Guidenumber
									$total_guides = $total_guides+1;
									if($total_guides != "$get_guide_number"){
										$result_update = mysqli_query($link, "UPDATE $t_references_index_guides SET guide_number=$total_guides WHERE guide_id=$get_guide_id");
										$get_guide_number = "$total_guides";
									}

									echo"
									 <tr>
									  <td class=\"odd\" style=\"padding-left: 10px;\">
										<span>$get_guide_number</span>
									  </td>
									  <td class=\"odd\">
										<span>$get_guide_title</span>
									  </td>
									  <td class=\"odd\">
										<span>
										<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=edit_guide&amp;guide_id=$get_guide_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
										&middot;
										<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=delete_guide&amp;guide_id=$get_guide_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
										</span>
									  </td>
									 </tr>";

								} // while guides
							} // if($get_group_id == "$get_current_group_id"){
						} // while groups
						echo"
						 </tbody>
						</table>
					<!-- //Groups and guides -->
				  </td>
				 </tr>
				</table>
			<!-- //Left and right -->
			";
		} // group found
	} // action == edit_guide
	elseif($action == "delete_guide"){
		if(isset($_GET['guide_id'])){
			$guide_id = $_GET['guide_id'];
			$guide_id = strip_tags(stripslashes($guide_id));
		}
		else{
			$guide_id = "";
		}
		$guide_id_mysql = quote_smart($link, $guide_id);

		$query = "SELECT guide_id, guide_title, guide_title_clean, guide_number, guide_group_id, guide_group_title, guide_reference_id, guide_reference_title, guide_read_times, guide_read_ipblock, guide_created, guide_updated, guide_comments FROM $t_references_index_guides WHERE guide_id=$guide_id_mysql AND guide_reference_id=$get_current_reference_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_guide_id, $get_current_guide_title, $get_current_guide_title_clean, $get_current_guide_number, $get_current_guide_group_id, $get_current_guide_group_title, $get_current_guide_reference_id, $get_current_guide_reference_title, $get_current_guide_read_times, $get_current_guide_read_ipblock, $get_current_guide_created, $get_current_guide_updated, $get_current_guide_comments) = $row;


		if($get_current_guide_id == ""){
			echo"<p>Server error 404.</p>";
		}
		else{
			// Get group
			$query = "SELECT group_id, group_title, group_title_clean, group_number, group_reference_id, group_reference_title, group_read_times, group_read_times_ip_block, group_created_datetime, group_updated_datetime FROM $t_references_index_groups WHERE group_id=$get_current_guide_group_id AND group_reference_id=$get_current_reference_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_group_id, $get_current_group_title, $get_current_group_title_clean, $get_current_group_number, $get_current_group_reference_id, $get_current_group_reference_title, $get_current_group_read_times, $get_current_group_read_times_ip_block, $get_current_group_created_datetime, $get_current_group_updated_datetime) = $row;



			if($process == "1"){
				


				$result = mysqli_query($link, "DELETE FROM $t_references_index_guides WHERE guide_id=$get_current_guide_id") or die(mysqli_error($link));

				// Search engine
				$result = mysqli_query($link, "DELETE FROM $t_search_engine_index WHERE index_module_name='references' AND index_reference_name='guide_id' AND index_reference_id=$get_current_guide_id") or die(mysqli_error($link));



				$url = "index.php?open=$open&page=$page&reference_id=$reference_id&editor_language=$editor_language&ft=success&fm=guide_deleted";
				header("Location: $url");
				exit;
			}
			echo"
			<h1>$get_current_group_title</h1>
				

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
				&gt;
				<a href=\"index.php?open=references&amp;page=reference_groups_and_guides&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Groups and guides</a>
				&gt;
				<a href=\"index.php?open=references&amp;page=reference_groups_and_guides&amp;reference_id=$get_current_reference_id&amp;action=edit_group&amp;group_id=$get_current_group_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_group_title</a>
				&gt;
				<a href=\"index.php?open=references&amp;page=reference_groups_and_guides&amp;reference_id=$get_current_reference_id&amp;action=delete_guide&amp;guide_id=$get_current_guide_id&amp;editor_language=$editor_language&amp;l=$l\">Delete guide $get_current_guide_title</a>
				</p>
			<!-- //Where am I? -->

			<!-- Left and right -->
				<table>
				 <tr>
				  <td style=\"padding: 0px 50px 0px 0px;vertical-align: top;\">
					<!-- Delete guide form -->
						<h2>Delete guide $get_current_guide_title</h2>
						<p>Are you sure you want to delte the guide $get_current_guide_title?</p>
			
						<p>
						<a href=\"index.php?open=references&amp;page=reference_groups_and_guides&amp;reference_id=$get_current_reference_id&amp;action=delete_guide&amp;guide_id=$get_current_guide_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_danger\">Delete</a>
						</p>
					<!-- //Delete guide form -->
		
				  </td>
				  <td style=\"vertical-align: top;\">	
					
					<!-- Groups and guides -->
						<h2>Groups and guides</h2>
			

						<table class=\"hor-zebra\">
						 <thead>
						  <tr>
						   <th scope=\"col\">
							<span>No</span>
						   </th>
						   <th scope=\"col\">
							<span>Title</span>
						   </th>
						   <th scope=\"col\">
							<span>Actions</span>
						   </th>
						  </tr>
						 </thead>
						 <tbody>";
						$total_groups = 0;
						$total_guides = 0;
						$query = "SELECT group_id, group_title, group_number FROM $t_references_index_groups WHERE group_reference_id=$get_current_reference_id ORDER BY group_number ASC";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_group_id, $get_group_title, $get_group_number) = $row;


							// Question number
							$total_groups = $total_groups+1;
							if($total_groups != "$get_group_number"){
								$result_update = mysqli_query($link, "UPDATE $t_references_index_groups SET group_number=$total_groups WHERE group_id=$get_group_id");
								$get_group_number = "$total_groups";
							}

							echo"
							  <tr>
						  	 <td>
								<span><b>$get_group_number</b></span>
							   </td>
							   <td>
								<span><b>$get_group_title</b></span>
							   </td>
							   <td>
								<span><b>
								<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=new_guide&amp;group_id=$get_group_id&amp;editor_language=$editor_language&amp;l=$l\">New guide</a>
								&middot;
								<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=edit_group&amp;group_id=$get_group_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
								&middot;
								<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=delete_group&amp;group_id=$get_group_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
								</b></span>
							   </td>
							  </tr>";
						
							if($get_group_id == "$get_current_group_id"){

								$query_lessons = "SELECT guide_id, guide_title, guide_number FROM $t_references_index_guides WHERE guide_group_id=$get_group_id ORDER BY guide_number ASC";
								$result_lessons = mysqli_query($link, $query_lessons);
								while($row_lessons = mysqli_fetch_row($result_lessons)) {
									list($get_guide_id, $get_guide_title, $get_guide_number) = $row_lessons;

									// Guidenumber
									$total_guides = $total_guides+1;
									if($total_guides != "$get_guide_number"){
										$result_update = mysqli_query($link, "UPDATE $t_references_index_guides SET guide_number=$total_guides WHERE guide_id=$get_guide_id");
										$get_guide_number = "$total_guides";
									}

									echo"
									 <tr>
									  <td class=\"odd\" style=\"padding-left: 10px;\">
										<span>$get_guide_number</span>
									  </td>
									  <td class=\"odd\">
										<span>$get_guide_title</span>
									  </td>
									  <td class=\"odd\">
										<span>
										<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=edit_guide&amp;guide_id=$get_guide_id&amp;editor_language=$editor_language&amp;l=$l\">Edit</a>
										&middot;
										<a href=\"index.php?open=$open&amp;page=$page&amp;reference_id=$get_current_reference_id&amp;action=delete_guide&amp;guide_id=$get_guide_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
										</span>
									  </td>
									 </tr>";

								} // while guides
							} // if($get_group_id == "$get_current_group_id"){
						} // while groups
						echo"
						 </tbody>
						</table>
					<!-- //Groups and guides -->
				  </td>
				 </tr>
				</table>
			<!-- //Left and right -->
			";
		} // guide found
	} // action == delete_guide
} // found
?>