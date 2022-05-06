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
		if($process == "1"){
			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);
	
			$inp_title_clean = clean($inp_title);
			$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

			$inp_front_page_intro = $_POST['inp_front_page_intro'];
			$inp_front_page_intro = output_html($inp_front_page_intro);
			$inp_front_page_intro_mysql = quote_smart($link, $inp_front_page_intro);

			$inp_description = $_POST['inp_description'];
			$inp_description = output_html($inp_description);
			$inp_description_mysql = quote_smart($link, $inp_description);

			$inp_language = $_POST['inp_language'];
			$inp_language = output_html($inp_language);
			$inp_language_mysql = quote_smart($link, $inp_language);


			$datetime = date("Y-m-d H:i:s");
			$datetime_saying = date("j M Y H:i");


			$result = mysqli_query($link, "UPDATE $t_references_index SET 
							reference_title=$inp_title_mysql,
							reference_title_clean=$inp_title_clean_mysql,
							reference_front_page_intro=$inp_front_page_intro_mysql,
							reference_description=$inp_description_mysql,
							reference_language=$inp_language_mysql,
							reference_updated='$datetime'
							WHERE reference_id=$get_current_reference_id") or die(mysqli_error($link));

			// Category
			$inp_category_id = $_POST['inp_category_id'];
			$inp_category_id = output_html($inp_category_id);
			$inp_category_id_mysql = quote_smart($link, $inp_category_id);
			if($inp_category_id != "0" && $inp_category_id != "$get_current_reference_sub_category_id"){
				// Find this new sub category
				$query = "SELECT sub_category_id, sub_category_title, sub_category_main_category_id FROM $t_references_categories_sub WHERE sub_category_id=$inp_category_id_mysql";
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
			} // new category
			

			// Search engine
			$inp_index_title = "$inp_title | $get_current_reference_title_translation_title";
			$inp_index_title_mysql = quote_smart($link, $inp_index_title);

			$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name='references' AND index_reference_name='reference_id' AND index_reference_id=$get_current_reference_id";
			$result_exists = mysqli_query($link, $query_exists);
			$row_exists = mysqli_fetch_row($result_exists);
			list($get_index_id) = $row_exists;
			if($get_index_id == ""){
				// URL
				$inp_index_url = "$inp_title_clean";
				$inp_index_url_mysql = quote_smart($link, $inp_index_url);

				$inp_index_short_description = substr($inp_front_page_intro, 0, 200);
				$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);


				mysqli_query($link, "INSERT INTO $t_search_engine_index 
				(index_id, index_title, index_url, index_short_description, index_keywords, 
				index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
				index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
				index_unique_hits) 
				VALUES 
				(NULL, $inp_index_title_mysql, $inp_index_url_mysql, $inp_index_short_description_mysql, '', 
				'references', 'references', '0', 'reference_id', $get_current_reference_id,
				'0', 0, '$datetime', '$datetime_saying', $inp_language_mysql,
				0)")
				or die(mysqli_error($link));
			}
			else{

				$result = mysqli_query($link, "UPDATE $t_search_engine_index SET 
								index_title=$inp_index_title_mysql,
								index_url=$inp_title_clean_mysql,
								index_short_description=$inp_front_page_intro_mysql,
								index_updated_datetime='$datetime',
								index_updated_datetime_print='$datetime_saying'
							WHERE index_id=$get_index_id") or die(mysqli_error($link));
			}
			

			$url = "index.php?open=$open&page=$page&reference_id=$get_current_reference_id&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
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
					<li><a href=\"index.php?open=references&amp;page=reference_open&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"active\">Info</a>
					<li><a href=\"index.php?open=references&amp;page=reference_groups_and_guides&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Groups and guides</a>
					<li><a href=\"index.php?open=references&amp;page=reference_icon&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Icon</a>
					<li><a href=\"index.php?open=references&amp;page=reference_read_from_file&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Read from file</a>
					<li><a href=\"index.php?open=references&amp;page=reference_write_to_file&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Write to file</a>
					<li><a href=\"index.php?open=references&amp;page=reference_delete&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
				</ul>
			</div>
			<div class=\"clear\" style=\"height: 10px;\"></div>
		<!-- //Refrence navigation -->


		<!-- Form -->
		
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
			</script>
			
			<form method=\"post\" action=\"index.php?open=references&amp;page=reference_open&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>Title:</b><br />
			<input type=\"text\" name=\"inp_title\" value=\"$get_current_reference_title\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p><b>Front page intro:</b><br />
			<textarea name=\"inp_front_page_intro\" rows=\"8\" cols=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
			$get_current_reference_front_page_intro = str_replace("<br />", "\n", $get_current_reference_front_page_intro);
			echo"$get_current_reference_front_page_intro</textarea>
			</p>

			<p><b>Description:</b><br />
			<textarea name=\"inp_description\" rows=\"8\" cols=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
			$get_current_reference_description = str_replace("<br />", "\n", $get_current_reference_description);
			echo"$get_current_reference_description</textarea>
			</p>

			<p><b>Language:</b><br />
			<select name=\"inp_language\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">\n";
			$query = "SELECT language_active_id, language_active_name, language_active_iso_two FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two) = $row;
				echo"	<option value=\"$get_language_active_iso_two\""; if($get_language_active_iso_two == "$get_current_reference_language"){ echo" selected=\"selected\""; } echo">$get_language_active_name</option>\n";
			}
			echo"
			</select>

			<p><b>Category:</b><br />
			<select name=\"inp_category_id\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">\n";
			$query = "SELECT main_category_id, main_category_title FROM $t_references_categories_main ORDER BY main_category_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_main_category_id, $get_main_category_title) = $row;

				echo"	<option value=\"0\">$get_main_category_title</option>\n";

				$query_sub = "SELECT sub_category_id, sub_category_title FROM $t_references_categories_sub WHERE sub_category_main_category_id=$get_main_category_id ORDER BY sub_category_title ASC";
				$result_sub = mysqli_query($link, $query_sub);
				while($row_sub = mysqli_fetch_row($result_sub)) {
					list($get_sub_category_id, $get_sub_category_title) = $row_sub;

					echo"	<option value=\"$get_sub_category_id\""; if($get_sub_category_id == "$get_current_reference_sub_category_id"){ echo" selected=\"selected\""; } echo">&nbsp; &nbsp; $get_sub_category_title</option>\n";
				}
			}
			echo"
			</select>

			<p><input type=\"submit\" value=\"Save changes\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>

			</form>
		<!-- //Form -->
		";
	} // action ==""
} // found
?>