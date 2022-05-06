<?php
/**
*
* File: _admin/_inc/comments/courses_new.php
* Version 
* Date 20:17 30.10.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
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


if($action == ""){

	echo"
	<h1>New references</h1>
				

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
		<a href=\"index.php?open=references&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">All courses</a>
		&gt;
		<a href=\"index.php?open=references&amp;page=courses_new&amp;editor_language=$editor_language&amp;l=$l\">New course</a>
		</p>
	<!-- //Where am I? -->

	<!-- Language -->

		<form method=\"get\" enctype=\"multipart/form-data\">
			<script>
			\$(function(){
				// bind change event to select
				\$('#inp_l').on('change', function () {
					var url = \$(this).val(); // get selected value
					if (url) { // require a URL
 						window.location = url; // redirect
					}
					return false;
				});
			});
			</script>
			<p><b>Editor language:</b><br />

			<select id=\"inp_l\">
				<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">$l_editor_language</option>
				<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">-</option>\n";

				$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;

					// No language selected?
					if($editor_language == ""){
							$editor_language = "$get_language_active_iso_two";
					}
					echo"	<option value=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$get_language_active_iso_two&amp;l=$l\"";if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
				}
			echo"
			</select>
			</p>
			</form>
		<!-- //Select language -->

	<!-- //Language -->

	<!-- New course form -->
		<p><b>Please select main category:</b></p>

		<div class=\"vertical\">
			<ul>\n";
		$editor_language_mysql = quote_smart($link, $editor_language);
		$query = "SELECT main_category_id, main_category_title FROM $t_references_categories_main WHERE main_category_language=$editor_language_mysql ORDER BY main_category_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_main_category_id, $get_main_category_title) = $row;

			echo"	<li><a href=\"index.php?open=references&amp;page=references_new&amp;action=step_2_select_sub_category&amp;main_category_id=$get_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_main_category_title</a></li>\n";

		}
		echo"
			</ul>
		</div>
	<!-- //New course form -->
	";
}
elseif($action == "step_2_select_sub_category"){
	if(isset($_GET['main_category_id'])){
		$main_category_id = $_GET['main_category_id'];
		$main_category_id = strip_tags(stripslashes($main_category_id));
	}
	else{
		$main_category_id = "";
	}
	$main_category_id_mysql = quote_smart($link, $main_category_id);
	$query = "SELECT main_category_id, main_category_title, main_category_title_clean, main_category_description, main_category_language, main_category_created, main_category_updated FROM $t_references_categories_main WHERE main_category_id=$main_category_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_title_clean, $get_current_main_category_description, $get_current_main_category_language, $get_current_main_category_created, $get_current_main_category_updated) = $row;

	if($get_current_main_category_id == ""){
		echo"<p>Server error 404.</p>";
	}
	else{

		echo"
		<h1>New reference</h1>
				

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
			<a href=\"index.php?open=references&amp;page=courses_new&amp;editor_language=$editor_language&amp;l=$l\">New reference</a>
			&gt;
			<a href=\"index.php?open=references&amp;page=courses_new&amp;action=step_2_select_sub_category&amp;main_category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_main_category_title</a>
			</p>
		<!-- //Where am I? -->


		<!-- Select sub category -->
			<p><b>Please select sub category</b></p>

			<div class=\"vertical\">
				<ul>\n";
		

			$query_sub = "SELECT sub_category_id, sub_category_title FROM $t_references_categories_sub WHERE sub_category_main_category_id=$get_current_main_category_id ORDER BY sub_category_title ASC";
			$result_sub = mysqli_query($link, $query_sub);
			while($row_sub = mysqli_fetch_row($result_sub)) {
				list($get_sub_category_id, $get_sub_category_title) = $row_sub;

				echo"	<li><a href=\"index.php?open=references&amp;page=references_new&amp;action=step_3_info&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_sub_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_sub_category_title</a></li>\n";
			}
			echo"
				</ul>
			</div>
		
		<!-- //Select sub category -->
		";
	} // main category found
} // action == step_2_select_sub_category
elseif($action == "step_3_info"){
	if(isset($_GET['main_category_id'])){
		$main_category_id = $_GET['main_category_id'];
		$main_category_id = strip_tags(stripslashes($main_category_id));
	}
	else{
		$main_category_id = "";
	}
	$main_category_id_mysql = quote_smart($link, $main_category_id);
	$query = "SELECT main_category_id, main_category_title, main_category_title_clean, main_category_description, main_category_language, main_category_created, main_category_updated FROM $t_references_categories_main WHERE main_category_id=$main_category_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_title_clean, $get_current_main_category_description, $get_current_main_category_language, $get_current_main_category_created, $get_current_main_category_updated) = $row;

	if($get_current_main_category_id == ""){
		echo"<p>Server error 404.</p>";
	}
	else{
		if(isset($_GET['sub_category_id'])){
			$sub_category_id = $_GET['sub_category_id'];
			$sub_category_id = strip_tags(stripslashes($sub_category_id));
		}
		else{
			$sub_category_id = "";
		}
		$sub_category_id_mysql = quote_smart($link, $sub_category_id);
		$query = "SELECT sub_category_id, sub_category_title, sub_category_title_clean, sub_category_description, sub_category_main_category_id, sub_category_main_category_title, sub_category_language, sub_category_created, sub_category_updated FROM $t_references_categories_sub WHERE sub_category_id=$sub_category_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_sub_category_id, $get_current_sub_category_title, $get_current_sub_category_title_clean, $get_current_sub_category_description, $get_current_sub_category_main_category_id, $get_current_sub_category_main_category_title, $get_current_sub_category_language, $get_current_sub_category_created, $get_current_sub_category_updated) = $row;

		if($get_current_sub_category_id == ""){
			echo"<p>Server error 404.</p>";
		}
		else{

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


				$inp_language = $_GET['editor_language'];
				$inp_language = output_html($inp_language);
				$inp_language_mysql = quote_smart($link, $inp_language);

				$inp_main_category_title_mysql = quote_smart($link, $get_current_main_category_title);

				$inp_sub_category_title_mysql = quote_smart($link, $get_current_sub_category_title);


				$inp_image_file  = $inp_title_clean . ".png";
				$inp_image_file_mysql = quote_smart($link, $inp_image_file);

				$inp_image_thumb  = $inp_title_clean . "_thumb.png";
				$inp_image_thumb_mysql = quote_smart($link, $inp_image_thumb);

				$inp_icon_a = $inp_title_clean . "_16x16.png";
				$inp_icon_a_mysql = quote_smart($link, $inp_icon_a);

				$inp_icon_b = $inp_title_clean . "_32x32.png";
				$inp_icon_b_mysql = quote_smart($link, $inp_icon_b);

				$inp_icon_c = $inp_title_clean . "_48x48.png";
				$inp_icon_c_mysql = quote_smart($link, $inp_icon_c);

				$inp_icon_d = $inp_title_clean . "_64x64.png";
				$inp_icon_d_mysql = quote_smart($link, $inp_icon_d);

				$inp_icon_e = $inp_title_clean . "_96x96.png";
				$inp_icon_e_mysql = quote_smart($link, $inp_icon_e);

				$inp_icon_f = $inp_title_clean . "_260x260.png";
				$inp_icon_f_mysql = quote_smart($link, $inp_icon_f);

				$datetime = date("Y-m-d H:i:s");
				$datetime_saying = date("j M Y H:i");
		
				mysqli_query($link, "INSERT INTO $t_references_index
				(reference_id, reference_title, reference_title_clean, reference_is_active, reference_front_page_intro, 
				reference_description, reference_language, reference_main_category_id, reference_main_category_title, reference_sub_category_id, 
				reference_sub_category_title, reference_image_file, reference_image_thumb, reference_icon_16, reference_icon_32, 
				reference_icon_48, reference_icon_64, reference_icon_96, reference_icon_260, reference_groups_count, 
				reference_guides_count, reference_read_times, reference_read_times_ip_block, reference_created, reference_updated) 
				VALUES 
				(NULL, $inp_title_mysql, $inp_title_clean_mysql, 1, $inp_front_page_intro_mysql, 
				$inp_description_mysql, $inp_language_mysql, $get_current_main_category_id, $inp_main_category_title_mysql, $get_current_sub_category_id, 
				$inp_sub_category_title_mysql, $inp_image_file_mysql, $inp_image_thumb_mysql, $inp_icon_a_mysql, $inp_icon_b_mysql, 
				$inp_icon_c_mysql, $inp_icon_d_mysql, $inp_icon_e_mysql, $inp_icon_f_mysql, 0,
				0, 0, '', '$datetime', '$datetime')")
				or die(mysqli_error($link));

				// Get ID
				$query = "SELECT reference_id, reference_title, reference_title_clean, reference_title_short, reference_title_length, reference_is_active, reference_front_page_intro, reference_description, reference_language, reference_main_category_id, reference_main_category_title, reference_sub_category_id, reference_sub_category_title, reference_image_file, reference_image_thumb, reference_icon_16, reference_icon_32, reference_icon_48, reference_icon_64, reference_icon_96, reference_icon_260, reference_groups_count, reference_guides_count, reference_read_times, reference_read_times_ip_block, reference_created, reference_updated FROM $t_references_index WHERE reference_created='$datetime'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_reference_id, $get_current_reference_title, $get_current_reference_title_clean, $get_current_reference_title_short, $get_current_reference_title_length, $get_current_reference_is_active, $get_current_reference_front_page_intro, $get_current_reference_description, $get_current_reference_language, $get_current_reference_main_category_id, $get_current_reference_main_category_title, $get_current_reference_sub_category_id, $get_current_reference_sub_category_title, $get_current_reference_image_file, $get_current_reference_image_thumb, $get_current_reference_icon_16, $get_current_reference_icon_32, $get_current_reference_icon_48, $get_current_reference_icon_64, $get_current_reference_icon_96, $get_current_reference_icon_260, $get_current_reference_groups_count, $get_current_reference_guides_count, $get_current_reference_read_times, $get_current_reference_read_times_ip_block, $get_current_reference_created, $get_current_reference_updated) = $row;

				// Title
				include("_translations/site/$inp_language/references/ts_references.php");

				// Make dir
				if(!(is_dir("../$inp_title_clean"))){
					mkdir("../$inp_title_clean");
				}


				// Create file
				$datetime = date("Y-m-d H:i:s");
				$datetime_print = date("j M Y H:i");
				$year = date("Y");
				$page_id = date("ymdhis");
				if(!(file_exists("../$get_current_reference_title_clean/index.php"))){
			$input="<?php
/**
*
* File: $get_current_reference_title_clean/index.php
* Version 3.0.0
* Date $datetime_print
* Copyright (c) 2009-$year Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Configuration ---------------------------------------------------------------------------- */
\$pageIdSav            = \"$page_id\";
\$pageNoColumnSav      = \"2\";
\$pageAllowCommentsSav = \"0\";

/*- Root dir --------------------------------------------------------------------------------- */
// This determine where we are
if(file_exists(\"favicon.ico\")){ \$root = \".\"; }
elseif(file_exists(\"../favicon.ico\")){ \$root = \"..\"; }
elseif(file_exists(\"../../favicon.ico\")){ \$root = \"../..\"; }
elseif(file_exists(\"../../../favicon.ico\")){ \$root = \"../../..\"; }
elseif(file_exists(\"../../../../favicon.ico\")){ \$root = \"../../../..\"; }
else{ \$root = \"../../..\"; }

/*- Website config --------------------------------------------------------------------------- */
include(\"\$root/_admin/website_config.php\");

/*- Translation ------------------------------------------------------------------------------ */
include(\"\$root/_admin/_translations/site/\$l/references/ts_references.php\");

/*- Headers ---------------------------------------------------------------------------------- */
\$website_title = \"$inp_title\";
if(file_exists(\"./favicon.ico\")){ \$root = \".\"; }
elseif(file_exists(\"../favicon.ico\")){ \$root = \"..\"; }
elseif(file_exists(\"../../favicon.ico\")){ \$root = \"../..\"; }
elseif(file_exists(\"../../../favicon.ico\")){ \$root = \"../../..\"; }
include(\"\$root/_webdesign/header.php\");

/* Course header ---------------------------------------------------------------------------- */
\$referenceTitleSav = \"$inp_title\";

include(\"\$root/references/_includes/reference.php\");

/*- Footer ---------------------------------------------------------------------------------- */
include(\"\$root/_webdesign/\$webdesignSav/footer.php\");
?>";
					$fh = fopen("../$get_current_reference_title_clean/index.php", "w+") or die("can not open file");
					fwrite($fh, $input);
					fclose($fh);

				} // index.php



				// _reference.php
				if(!(file_exists("../$get_current_reference_title_clean/index.php"))){

			$input_reference_txt ="<?php
\$reference_txt_file_generated_datetime	= \"$datetime\";

\$reference_title_sav 			= \"$inp_title\";
\$reference_title_clean_sav 		= \"$inp_title_clean\";
\$reference_is_active_sav		= \"$get_current_reference_is_active\";
\$reference_front_page_intro_sav	= \"$get_current_reference_front_page_intro\";
\$reference_description_sav   		= \"$get_current_reference_description\";
\$reference_language_sav		= \"$get_current_reference_language\";
\$reference_main_category_title_sav  	= \"$get_current_reference_main_category_title\";
\$reference_sub_category_title_sav	= \"$get_current_reference_sub_category_title\";
\$reference_image_file_sav		= \"$get_current_reference_image_file\";
\$reference_image_thumb_sav  		= \"$get_current_reference_image_thumb\";
\$reference_icon_a_sav			= \"$get_current_reference_icon_16\"; // 16x16
\$reference_icon_b_sav			= \"$get_current_reference_icon_32\"; // 32x32
\$reference_icon_c_sav			= \"$get_current_reference_icon_48\"; // 48x48
\$reference_icon_d_sav			= \"$get_current_reference_icon_64\"; // 64x64
\$reference_icon_e_sav			= \"$get_current_reference_icon_96\"; // 96x96
\$reference_icon_f_sav			= \"$get_current_reference_icon_260\"; // 260x260
?>";

					$fh = fopen("../$inp_title_clean/_reference.php", "w+") or die("can not open file");
					fwrite($fh, $input_reference_txt);
					fclose($fh);

				} // _reference.php

				// Search engine
				$inp_index_title = "$inp_title | $l_references";
				$inp_index_title_mysql = quote_smart($link, $inp_index_title);

				$inp_index_url = "$inp_title_clean";
				$inp_index_url_mysql = quote_smart($link, $inp_index_url);

				mysqli_query($link, "INSERT INTO $t_search_engine_index 
				(index_id, index_title, index_url, index_short_description, index_keywords, 
				index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
				index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
				index_unique_hits) 
				VALUES 
				(NULL, $inp_index_title_mysql, $inp_index_url_mysql, $inp_front_page_intro_mysql, '', 
				'references', 'references', 0, 'reference_id', $get_current_reference_id, 
				0, 0, '$datetime', '$datetime_saying', $inp_language_mysql,
				0)")
				or die(mysqli_error($link));

	
				// Header
				$url = "index.php?open=$open&page=default&main_category_id=$get_current_main_category_id&sub_category_id=$get_current_sub_category_id&editor_language=$editor_language&ft=success&fm=reference_created";
				header("Location: $url");
				exit;
			}

			echo"
			<h1>New reference</h1>
				

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
				<a href=\"index.php?open=references&amp;page=courses_new&amp;editor_language=$editor_language&amp;l=$l\">New reference</a>
				&gt;
				<a href=\"index.php?open=references&amp;page=courses_new&amp;action=step_2_select_sub_category&amp;main_category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_main_category_title</a>
				&gt;
				<a href=\"index.php?open=references&amp;page=courses_new&amp;action=step_3_info&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_sub_category_title</a>
				</p>
			<!-- //Where am I? -->


			<!-- New course form -->
		
				<script>
				\$(document).ready(function(){
					\$('[name=\"inp_title\"]').focus();
				});
				</script>
			
				<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=step_3_info&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_current_sub_category_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

				<p><b>Title:</b><br />
				<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>

				<p><b>Front page intro:</b><br />
				<textarea name=\"inp_front_page_intro\" rows=\"8\" cols=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea>
				</p>

				<p><b>Description:</b><br />
				<textarea name=\"inp_description\" rows=\"8\" cols=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea>
				</p>
	
				<p><input type=\"submit\" value=\"Create\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>

				</form>
			<!-- //New course form -->
			";
		} // sub category found
	} // main category found
} // action == "step_3_course_info"
?>