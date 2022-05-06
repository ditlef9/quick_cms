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

			
			// Create file
			$datetime_saying = date("j M Y H:i");
			$year = date("Y");
			$page_id = date("ymdhis");
			$input="<?php
/**
*
* File: $get_current_reference_title_clean/index.php
* Version 3.0.0
* Date $datetime_saying
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
include(\"\$root/_admin/_translations/site/\$l/references/ts_index.php\");

/*- Headers ---------------------------------------------------------------------------------- */
\$website_title = \"$get_current_reference_title\";
if(file_exists(\"./favicon.ico\")){ \$root = \".\"; }
elseif(file_exists(\"../favicon.ico\")){ \$root = \"..\"; }
elseif(file_exists(\"../../favicon.ico\")){ \$root = \"../..\"; }
elseif(file_exists(\"../../../favicon.ico\")){ \$root = \"../../..\"; }
include(\"\$root/_webdesign/header.php\");

/* Course header ---------------------------------------------------------------------------- */
\$referenceTitleSav = \"$get_current_reference_title\";

include(\"\$root/references/_includes/reference.php\");

/*- Footer ---------------------------------------------------------------------------------- */
include(\"\$root/_webdesign/\$webdesignSav/footer.php\");
?>";

			$fh = fopen("../$get_current_reference_title_clean/index.php", "w+") or die("can not open file");
			fwrite($fh, $input);
			fclose($fh);


			// _reference.php
			$datetime = date("Y-m-d H:i:s");
			$input_reference_txt ="<?php
\$reference_txt_file_generated_datetime	= \"$datetime\";

\$reference_title_sav 			= \"$get_current_reference_title\";
\$reference_title_clean_sav 		= \"$get_current_reference_title_clean\";
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

			$fh = fopen("../$get_current_reference_title_clean/_reference.php", "w+") or die("can not open file");
			fwrite($fh, $input_reference_txt);
			fclose($fh);

			// Groups and guides
			$total_groups = 0;
			$total_guides = 0;
			$input = "<?php";
			$query = "SELECT group_id, group_title, group_number FROM $t_references_index_groups WHERE group_reference_id=$get_current_reference_id ORDER BY group_number ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_group_id, $get_group_title, $get_group_number) = $row;
				$input = $input . "

/*- $get_group_title -------------------------------------- */
\$group_title_sav[$total_groups] = \"$get_group_title\";";
				
				$guides_counter = 0;
				$query_lessons = "SELECT guide_id, guide_number, guide_title, guide_short_description FROM $t_references_index_guides WHERE guide_group_id=$get_group_id ORDER BY guide_number ASC";
				$result_lessons = mysqli_query($link, $query_lessons);
				while($row_lessons = mysqli_fetch_row($result_lessons)) {
					list($get_guide_id, $get_guide_number, $get_guide_title, $get_guide_short_description) = $row_lessons;


					$input = $input . "
\$guide_title_sav[$total_groups][$guides_counter] = \"$get_guide_title\";
\$guide_short_description_sav[$total_groups][$guides_counter] = \"$get_guide_short_description\";";

					// Increment
					$guides_counter = $guides_counter+1;
				} // while guides
				// Increment
				$total_groups = $total_groups+1;
			} // while groups

			$input = $input . "
?>";
			$fh = fopen("../$get_current_reference_title_clean/_groups_and_guides.php", "w+") or die("can not open file");
			fwrite($fh, $input);
			fclose($fh);


			// Navigation
			$inp_navigation="<?php
/**
*
* File: $get_current_reference_title_clean/navigation.php
* Version 2.0.0
* Date $datetime
* Copyright (c) 2011-2019 Localhost
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Course info --------------------------------------------------------------------- */
\$referenceTitleSav = \"$get_current_reference_title\";

/*- Functions ------------------------------------------------------------------------ */

/*- Variables ------------------------------------------------------------------------ */
if(isset(\$_GET['group_id'])) {
	\$group_id = \$_GET['group_id'];
	\$group_id = strip_tags(stripslashes(\$group_id));
}
else{
	\$group_id = \"\";
}
if(isset(\$_GET['guide_id'])) {
	\$guide_id = \$_GET['guide_id'];
	\$guide_id = strip_tags(stripslashes(\$guide_id));
}
else{
	\$guide_id = \"\";
}
if(isset(\$_GET['order_by'])) {
	\$order_by = \$_GET['order_by'];
	\$order_by = strip_tags(stripslashes(\$order_by));
}
else{
	\$order_by = \"\";
}

/*- Tables ---------------------------------------------------------------------------- */
\$t_references_title_translations 	= \$mysqlPrefixSav . \"references_title_translations\";
\$t_references_categories_main	 	= \$mysqlPrefixSav . \"references_categories_main\";
\$t_references_categories_sub 	 	= \$mysqlPrefixSav . \"references_categories_sub\";
\$t_references_index		 	= \$mysqlPrefixSav . \"references_index\";
\$t_references_index_groups	 	= \$mysqlPrefixSav . \"references_index_groups\";
\$t_references_index_guides		= \$mysqlPrefixSav . \"references_index_guides\";
\$t_references_index_guides_comments	= \$mysqlPrefixSav . \"references_index_guides_comments\";




/*- Translations ---------------------------------------------------------------------- */
include(\"\$root/_admin/_translations/site/\$l/references/ts_navigation.php\");

// Find course
\$reference_title_mysql = quote_smart(\$link, \$referenceTitleSav);
\$query = \"SELECT reference_id, reference_title, reference_title_clean, reference_is_active, reference_front_page_intro, reference_description, reference_language, reference_main_category_id, reference_main_category_title, reference_sub_category_id, reference_sub_category_title, reference_image_file, reference_image_thumb, reference_icon_16, reference_icon_32, reference_icon_48, reference_icon_64, reference_icon_96, reference_icon_260, reference_groups_count, reference_guides_count, reference_read_times, reference_read_times_ip_block, reference_created, reference_updated FROM \$t_references_index WHERE reference_title=\$reference_title_mysql\";
\$result = mysqli_query(\$link, \$query);
\$row = mysqli_fetch_row(\$result);
list(\$get_current_reference_id, \$get_current_reference_title, \$get_current_reference_title_clean, \$get_current_reference_is_active, \$get_current_reference_front_page_intro, \$get_current_reference_description, \$get_current_reference_language, \$get_current_reference_main_category_id, \$get_current_reference_main_category_title, \$get_current_reference_sub_category_id, \$get_current_reference_sub_category_title, \$get_current_reference_image_file, \$get_current_reference_image_thumb, \$get_current_reference_icon_16, \$get_current_reference_icon_32, \$get_current_reference_icon_48, \$get_current_reference_icon_64, \$get_current_reference_icon_96, \$get_current_reference_icon_260, \$get_current_reference_groups_count, \$get_current_reference_guides_count, \$get_current_reference_read_times, \$get_current_reference_read_times_ip_block, \$get_current_reference_created, \$get_current_reference_updated) = \$row;

if(\$get_current_reference_id != \"\"){

	// Nav start
	echo\"
				<ul class=\\\"toc\\\">
					<li class=\\\"header_home\\\"><a href=\\\"\$root/\$get_current_reference_title_clean/index.php?reference_id=\$get_current_reference_id&amp;l=\$l\\\">\$get_current_reference_title</a></li>
					<li><a href=\\\"\$root/\$get_current_reference_title_clean/reference_by_alphabet.php?reference_id=\$get_current_reference_id&amp;guide_id=-1&amp;l=\$l\\\" \"; if(\$guide_id == \"-1\"){ echo\" class=\\\"navigation_active\\\"\"; } echo\">\$l_alphabet</a></li>
					<li><a href=\\\"\$root/\$get_current_reference_title_clean/reference_by_category.php?reference_id=\$get_current_reference_id&amp;guide_id=-2&amp;l=\$l\\\" \"; if(\$guide_id == \"-2\"){ echo\" class=\\\"navigation_active\\\"\"; } echo\">\$l_category</a></li>\n\";
	// Groups
	\$query = \"SELECT group_id, group_title, group_title_clean, group_number FROM \$t_references_index_groups WHERE group_reference_id=\$get_current_reference_id ORDER BY group_number ASC\";
	\$result = mysqli_query(\$link, \$query);
	while(\$row = mysqli_fetch_row(\$result)) {
		list(\$get_group_id, \$get_group_title, \$get_group_title_clean, \$get_group_number) = \$row;


		echo\"
					<li class=\\\"header_up\\\"><a href=\\\"\$root/\$get_current_reference_title_clean/\$get_group_title_clean/index.php?reference_id=\$get_current_reference_id&amp;group_id=\$get_group_id&amp;l=\$l\\\" id=\\\"navigation_group_id_\$get_group_id\\\">\$get_group_title</a></li>\n\";

		// Get guides
		\$query_lessons = \"SELECT guide_id, guide_title, guide_title_clean, guide_title_short, guide_title_length, guide_number FROM \$t_references_index_guides WHERE guide_group_id=\$get_group_id ORDER BY guide_number ASC\";
		\$result_lessons = mysqli_query(\$link, \$query_lessons);
		while(\$row_lessons = mysqli_fetch_row(\$result_lessons)) {
			list(\$get_guide_id, \$get_guide_title, \$get_guide_title_clean, \$get_guide_title_short, \$get_guide_title_length, \$get_guide_number) = \$row_lessons;


			echo\"					\";
			echo\"<li><a href=\\\"\$root/\$get_current_reference_title_clean/\$get_group_title_clean/\$get_guide_title_clean.php?reference_id=\$get_current_reference_id&amp;group_id=\$get_group_id&amp;guide_id=\$get_guide_id&amp;l=\$l\\\" \"; if(\$get_guide_id == \"\$guide_id\"){ echo\" class=\\\"navigation_active\\\"\"; } echo\" id=\\\"navigation_guide_id_\$get_guide_id\\\"\";
			if(\$get_guide_title_length  > 30){
				echo\" title=\\\"\$get_guide_title\\\"\"; 
			}
			echo\">\";
			if(\$get_guide_title_length  > 30){
				echo\"\$get_guide_title_short\";
			}
			else{
				echo\"\$get_guide_title\";
			}
			echo\"</a></li>\n\";

		} // guides
	} // groups

	echo\"
				</ul> <!-- //toc -->
	\";

	// Scroll to guide
	if(\$group_id !=\"\"){
		echo\"
		<script> 
		\\\$(document).ready(function(){
			var elmnt = document.getElementById(\\\"navigation_group_id_\$group_id\\\");
			elmnt.scrollIntoView();
		});
		</script>
		\";
	}
} // reference found

?>";
			$fh = fopen("../$get_current_reference_title_clean/navigation.php", "w+") or die("can not open file");
			fwrite($fh, $inp_navigation);
			fclose($fh);




			$input="<?php
/**
*
* File: $get_current_reference_title_clean/reference_by_alphabet.php
* Version 3.0.0
* Date $datetime_saying
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
include(\"\$root/_admin/_translations/site/\$l/references/ts_reference_by_alphabet.php\");

/*- Variables -------------------------------------------------------------------------------- */
/*- Headers ---------------------------------------------------------------------------------- */
\$website_title = \"$get_current_reference_title - \$l_reference_by_alphabet_headline\";
if(isset(\$_GET['search_query'])){
	\$search_query = \$_GET['search_query'];
	\$search_query = trim(\$search_query);
	\$search_query = strtolower(\$search_query);
	\$search_query = output_html(\$search_query);
	\$search_query_mysql = quote_smart(\$link, \$search_query);
	\$website_title = \"$get_current_reference_title - \$l_reference_by_alphabet_headline - \$search_query\";
}
if(file_exists(\"./favicon.ico\")){ \$root = \".\"; }
elseif(file_exists(\"../favicon.ico\")){ \$root = \"..\"; }
elseif(file_exists(\"../../favicon.ico\")){ \$root = \"../..\"; }
elseif(file_exists(\"../../../favicon.ico\")){ \$root = \"../../..\"; }
include(\"\$root/_webdesign/header.php\");

/* Course header ---------------------------------------------------------------------------- */
\$referenceTitleSav = \"$get_current_reference_title\";

include(\"\$root/references/_includes/reference_by_alphabet.php\");

/*- Footer ---------------------------------------------------------------------------------- */
include(\"\$root/_webdesign/\$webdesignSav/footer.php\");
?>";

			$fh = fopen("../$get_current_reference_title_clean/reference_by_alphabet.php", "w+") or die("can not open file");
			fwrite($fh, $input);
			fclose($fh);



			$input="<?php
/**
*
* File: $get_current_reference_title_clean/reference_by_category.php
* Version 3.0.0
* Date $datetime_saying
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
include(\"\$root/_admin/_translations/site/\$l/references/ts_reference_by_category.php\");

/*- Headers ---------------------------------------------------------------------------------- */
\$website_title = \"$get_current_reference_title - \$l_reference_by_category_headline\";
if(isset(\$_GET['search_query'])){
	\$search_query = \$_GET['search_query'];
	\$search_query = trim(\$search_query);
	\$search_query = strtolower(\$search_query);
	\$search_query = output_html(\$search_query);
	\$search_query_mysql = quote_smart(\$link, \$search_query);
	\$website_title = \"$get_current_reference_title - \$l_reference_by_category_headline - \$search_query\";
}
if(file_exists(\"./favicon.ico\")){ \$root = \".\"; }
elseif(file_exists(\"../favicon.ico\")){ \$root = \"..\"; }
elseif(file_exists(\"../../favicon.ico\")){ \$root = \"../..\"; }
elseif(file_exists(\"../../../favicon.ico\")){ \$root = \"../../..\"; }
include(\"\$root/_webdesign/header.php\");

/* Course header ---------------------------------------------------------------------------- */
\$referenceTitleSav = \"$get_current_reference_title\";

include(\"\$root/references/_includes/reference_by_category.php\");

/*- Footer ---------------------------------------------------------------------------------- */
include(\"\$root/_webdesign/\$webdesignSav/footer.php\");
?>";

			$fh = fopen("../$get_current_reference_title_clean/reference_by_category.php", "w+") or die("can not open file");
			fwrite($fh, $input);
			fclose($fh);

			// Groups index
			$query = "SELECT group_id, group_title, group_title_clean, group_number FROM $t_references_index_groups WHERE group_reference_id=$get_current_reference_id ORDER BY group_number ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_group_id, $get_group_title, $get_group_title_clean, $get_group_number) = $row;


			$input="<?php
/**
*
* File: $get_current_reference_title_clean/$get_group_title_clean/index.php
* Version 3.0.0
* Date $datetime_saying
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
include(\"\$root/_admin/_translations/site/\$l/references/ts_group_index.php\");

/*- Headers ---------------------------------------------------------------------------------- */
\$website_title = \"$get_current_reference_title - $get_group_title\";
if(file_exists(\"./favicon.ico\")){ \$root = \".\"; }
elseif(file_exists(\"../favicon.ico\")){ \$root = \"..\"; }
elseif(file_exists(\"../../favicon.ico\")){ \$root = \"../..\"; }
elseif(file_exists(\"../../../favicon.ico\")){ \$root = \"../../..\"; }
include(\"\$root/_webdesign/header.php\");

/* Course header ---------------------------------------------------------------------------- */
\$referenceTitleSav = \"$get_current_reference_title\";

include(\"\$root/references/_includes/group_index.php\");

/*- Footer ---------------------------------------------------------------------------------- */
include(\"\$root/_webdesign/\$webdesignSav/footer.php\");
?>";

				if(!(is_dir("../$get_current_reference_title_clean/$get_group_title_clean"))){
					mkdir("../$get_current_reference_title_clean/$get_group_title_clean");
				}

				$fh = fopen("../$get_current_reference_title_clean/$get_group_title_clean/index.php", "w+") or die("can not open file");
				fwrite($fh, $input);
				fclose($fh);
			}


			// Search engine :: Delete all
			$query_w = "SELECT reference_id, reference_title, reference_title_clean, reference_title_short, reference_title_length, reference_is_active, reference_front_page_intro, reference_description, reference_language, reference_main_category_id, reference_main_category_title, reference_sub_category_id, reference_sub_category_title, reference_image_file, reference_image_thumb, reference_icon_16, reference_icon_32, reference_icon_48, reference_icon_64, reference_icon_96, reference_icon_260, reference_groups_count, reference_guides_count, reference_read_times, reference_read_times_ip_block, reference_created, reference_updated FROM $t_references_index";
			$result_w = mysqli_query($link, $query_w);
			while($row_w = mysqli_fetch_row($result_w)) {
				list($get_reference_id, $get_reference_title, $get_reference_title_clean, $get_reference_title_short, $get_reference_title_length, $get_reference_is_active, $get_reference_front_page_intro, $get_reference_description, $get_reference_language, $get_reference_main_category_id, $get_reference_main_category_title, $get_reference_sub_category_id, $get_reference_sub_category_title, $get_reference_image_file, $get_reference_image_thumb, $get_reference_icon_16, $get_reference_icon_32, $get_reference_icon_48, $get_reference_icon_64, $get_reference_icon_96, $get_reference_icon_260, $get_reference_groups_count, $get_reference_guides_count, $get_reference_read_times, $get_reference_read_times_ip_block, $get_reference_created, $get_reference_updated) = $row_w;
					
				
				$result_delete = mysqli_query($link, "DELETE FROM $t_search_engine_index WHERE index_module_name='references' AND index_reference_name='reference_id' AND index_reference_id=$get_reference_id") or die(mysqli_error($link));


				// Groups
				$query_g = "SELECT group_id, group_title, group_title_clean, group_title_short, group_title_length, group_number, group_reference_id, group_reference_title, group_read_times, group_read_times_ip_block, group_created_datetime, group_updated_datetime, group_updated_formatted, group_last_read, group_last_read_formatted FROM $t_references_index_groups WHERE group_reference_id=$get_reference_id";
				$result_g = mysqli_query($link, $query_g);
				while($row_g = mysqli_fetch_row($result_g)) {
					list($get_group_id, $get_group_title, $get_group_title_clean, $get_group_title_short, $get_group_title_length, $get_group_number, $get_group_reference_id, $get_group_reference_title, $get_group_read_times, $get_group_read_times_ip_block, $get_group_created_datetime, $get_group_updated_datetime, $get_group_updated_formatted, $get_group_last_read, $get_group_last_read_formatted) = $row_g;
		

					$result_delete = mysqli_query($link, "DELETE FROM $t_search_engine_index WHERE index_module_name='references' AND index_reference_name='group_id' AND index_reference_id=$get_group_id") or die(mysqli_error($link));

					// Guides
					$query_guides = "SELECT guide_id, guide_number, guide_title, guide_title_clean, guide_title_short, guide_title_length, guide_short_description, guide_group_id, guide_group_title, guide_reference_id, guide_reference_title, guide_read_times, guide_read_ipblock, guide_created, guide_updated, guide_updated_formatted, guide_last_read, guide_last_read_formatted, guide_comments FROM $t_references_index_guides WHERE guide_group_id=$get_group_id";
					$result_guides = mysqli_query($link, $query_guides);
					while($row_guides = mysqli_fetch_row($result_guides)) {
						list($get_guide_id, $get_guide_number, $get_guide_title, $get_guide_title_clean, $get_guide_title_short, $get_guide_title_length, $get_guide_short_description, $get_guide_group_id, $get_guide_group_title, $get_guide_reference_id, $get_guide_reference_title, $get_guide_read_times, $get_guide_read_ipblock, $get_guide_created, $get_guide_updated, $get_guide_updated_formatted, $get_guide_last_read, $get_guide_last_read_formatted, $get_guide_comments) = $row_guides;

						$result_delete = mysqli_query($link, "DELETE FROM $t_search_engine_index WHERE index_module_name='references' AND index_reference_name='guide_id' AND index_reference_id=$get_guide_id") or die(mysqli_error($link));

					} // guides
				} // groups
			} // references

			// Search engine :: Insert all
			
			
			/* references index */
			$query_w = "SELECT reference_id, reference_title, reference_title_clean, reference_title_short, reference_title_length, reference_is_active, reference_front_page_intro, reference_description, reference_language, reference_main_category_id, reference_main_category_title, reference_sub_category_id, reference_sub_category_title, reference_image_file, reference_image_thumb, reference_icon_16, reference_icon_32, reference_icon_48, reference_icon_64, reference_icon_96, reference_icon_260, reference_groups_count, reference_guides_count, reference_read_times, reference_read_times_ip_block, reference_created, reference_updated FROM $t_references_index";
			$result_w = mysqli_query($link, $query_w);
			while($row_w = mysqli_fetch_row($result_w)) {
				list($get_reference_id, $get_reference_title, $get_reference_title_clean, $get_reference_title_short, $get_reference_title_length, $get_reference_is_active, $get_reference_front_page_intro, $get_reference_description, $get_reference_language, $get_reference_main_category_id, $get_reference_main_category_title, $get_reference_sub_category_id, $get_reference_sub_category_title, $get_reference_image_file, $get_reference_image_thumb, $get_reference_icon_16, $get_reference_icon_32, $get_reference_icon_48, $get_reference_icon_64, $get_reference_icon_96, $get_reference_icon_260, $get_reference_groups_count, $get_reference_guides_count, $get_reference_read_times, $get_reference_read_times_ip_block, $get_reference_created, $get_reference_updated) = $row_w;

	
				// Reference title
				$l_mysql = quote_smart($link, $get_current_reference_language);
				$query = "SELECT reference_title_translation_id, reference_title_translation_title FROM $t_references_title_translations WHERE reference_title_translation_language=$l_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_current_reference_title_translation_id, $get_current_reference_title_translation_title) = $row;


				$inp_index_title = "$get_current_reference_title | $get_current_reference_title_translation_title";
				$inp_index_title_mysql = quote_smart($link, $inp_index_title);

				$inp_index_url = "$get_current_reference_title_clean";
				$inp_index_url_mysql = quote_smart($link, $inp_index_url);

				$inp_index_short_description = substr($get_current_reference_front_page_intro, 0, 200);
				$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

				// tags
				$inp_index_keywords = "";
				$inp_index_keywords_mysql = quote_smart($link, "$inp_index_keywords");

				$inp_index_module_name_mysql = quote_smart($link, "references");

				$inp_index_module_part_name_mysql = quote_smart($link, "references");

				$inp_index_reference_name_mysql = quote_smart($link, "reference_id");
				$inp_index_reference_id_mysql = quote_smart($link, "$get_current_reference_id");

				$inp_index_has_access_control_mysql = quote_smart($link, 0);

				$inp_index_is_ad_mysql = quote_smart($link, 0);
	
				$inp_index_language_mysql = quote_smart($link, $get_current_reference_language);


				// Check if exists
				$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name=$inp_index_module_name_mysql AND index_reference_name=$inp_index_reference_name_mysql AND index_reference_id=$inp_index_reference_id_mysql";
				$result_exists = mysqli_query($link, $query_exists);
				$row_exists = mysqli_fetch_row($result_exists);
				list($get_index_id) = $row_exists;
				if($get_index_id == ""){
					// Insert
					mysqli_query($link, "INSERT INTO $t_search_engine_index 
					(index_id, index_title, index_url, index_short_description, index_keywords, 
					index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
					index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
					index_unique_hits) 
					VALUES 
					(NULL, $inp_index_title_mysql, $inp_index_url_mysql, $inp_index_short_description_mysql, $inp_index_keywords_mysql, 
					$inp_index_module_name_mysql, $inp_index_module_part_name_mysql, '0', $inp_index_reference_name_mysql, $inp_index_reference_id_mysql,
					'0', $inp_index_is_ad_mysql, '$datetime', '$datetime_saying', $inp_index_language_mysql,
					0)")
					or die(mysqli_error($link));
				}


				// Groups
				$query_g = "SELECT group_id, group_title, group_title_clean, group_title_short, group_title_length, group_number, group_reference_id, group_reference_title, group_read_times, group_read_times_ip_block, group_created_datetime, group_updated_datetime, group_updated_formatted, group_last_read, group_last_read_formatted FROM $t_references_index_groups WHERE group_reference_id=$get_current_reference_id";
				$result_g = mysqli_query($link, $query_g);
				while($row_g = mysqli_fetch_row($result_g)) {
					list($get_group_id, $get_group_title, $get_group_title_clean, $get_group_title_short, $get_group_title_length, $get_group_number, $get_group_reference_id, $get_group_reference_title, $get_group_read_times, $get_group_read_times_ip_block, $get_group_created_datetime, $get_group_updated_datetime, $get_group_updated_formatted, $get_group_last_read, $get_group_last_read_formatted) = $row_g;




					$inp_index_title = "$get_group_title | $get_current_reference_title | $get_current_reference_title_translation_title";
					$inp_index_title_mysql = quote_smart($link, $inp_index_title);

					$inp_index_url = "$get_current_reference_title_clean/index.php?reference_id=$get_current_reference_id&group_id=$get_group_id";
					$inp_index_url_mysql = quote_smart($link, $inp_index_url);

					$inp_index_short_description = "";
					$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

					// tags
					$inp_index_keywords = "";
					$inp_index_keywords_mysql = quote_smart($link, "$inp_index_keywords");

					$inp_index_module_name_mysql = quote_smart($link, "references");

					$inp_index_module_part_name_mysql = quote_smart($link, "groups");

					$inp_index_reference_name_mysql = quote_smart($link, "group_id");
					$inp_index_reference_id_mysql = quote_smart($link, "$get_group_id");

					$inp_index_has_access_control_mysql = quote_smart($link, 0);
		
					$inp_index_is_ad_mysql = quote_smart($link, 0);
	
					$inp_index_language_mysql = quote_smart($link, $get_current_reference_language);

			
					// Check if exists
					$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name=$inp_index_module_name_mysql AND index_reference_name=$inp_index_reference_name_mysql AND index_reference_id=$inp_index_reference_id_mysql";
					$result_exists = mysqli_query($link, $query_exists);
					$row_exists = mysqli_fetch_row($result_exists);
					list($get_index_id) = $row_exists;
					if($get_index_id == ""){
						// Insert
						mysqli_query($link, "INSERT INTO $t_search_engine_index 
						(index_id, index_title, index_url, index_short_description, index_keywords, 
						index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
						index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
						index_unique_hits) 
						VALUES 
						(NULL, $inp_index_title_mysql, $inp_index_url_mysql, $inp_index_short_description_mysql, $inp_index_keywords_mysql, 
						$inp_index_module_name_mysql, $inp_index_module_part_name_mysql, '0', $inp_index_reference_name_mysql, $inp_index_reference_id_mysql,
						'0', $inp_index_is_ad_mysql, '$datetime', '$datetime_saying', $inp_index_language_mysql,
						0)")
						or die(mysqli_error($link));
					}


					// Guides
					$query_guides = "SELECT guide_id, guide_number, guide_title, guide_title_clean, guide_title_short, guide_title_length, guide_short_description, guide_group_id, guide_group_title, guide_reference_id, guide_reference_title, guide_read_times, guide_read_ipblock, guide_created, guide_updated, guide_updated_formatted, guide_last_read, guide_last_read_formatted, guide_comments FROM $t_references_index_guides WHERE guide_group_id=$get_group_id";
					$result_guides = mysqli_query($link, $query_guides);
					while($row_guides = mysqli_fetch_row($result_guides)) {
						list($get_guide_id, $get_guide_number, $get_guide_title, $get_guide_title_clean, $get_guide_title_short, $get_guide_title_length, $get_guide_short_description, $get_guide_group_id, $get_guide_group_title, $get_guide_reference_id, $get_guide_reference_title, $get_guide_read_times, $get_guide_read_ipblock, $get_guide_created, $get_guide_updated, $get_guide_updated_formatted, $get_guide_last_read, $get_guide_last_read_formatted, $get_guide_comments) = $row_guides;


						$inp_index_title = "$get_guide_title | $get_group_title | $get_current_reference_title | $get_current_reference_title_translation_title";
						$inp_index_title_mysql = quote_smart($link, $inp_index_title);

						$inp_index_url = "$get_current_reference_title_clean/$get_group_title_clean/$get_guide_title_clean.php?reference_id=$get_current_reference_id&group_id=$get_group_id&guide_id=$get_guide_id";
						$inp_index_url_mysql = quote_smart($link, $inp_index_url);

						$inp_index_short_description = "";
						$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

						// tags
						$inp_index_keywords = "";
						$inp_index_keywords_mysql = quote_smart($link, "$inp_index_keywords");

						$inp_index_module_name_mysql = quote_smart($link, "references");

						$inp_index_module_part_name_mysql = quote_smart($link, "guides");

						$inp_index_reference_name_mysql = quote_smart($link, "guide_id");
						$inp_index_reference_id_mysql = quote_smart($link, "$get_guide_id");

						$inp_index_has_access_control_mysql = quote_smart($link, 0);

						$inp_index_is_ad_mysql = quote_smart($link, 0);
	
						$inp_index_language_mysql = quote_smart($link, $get_current_reference_language);

						// Check if exists
						$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name=$inp_index_module_name_mysql AND index_reference_name=$inp_index_reference_name_mysql AND index_reference_id=$inp_index_reference_id_mysql";
						$result_exists = mysqli_query($link, $query_exists);
						$row_exists = mysqli_fetch_row($result_exists);
						list($get_index_id) = $row_exists;
						if($get_index_id == ""){
							// Insert
					
							mysqli_query($link, "INSERT INTO $t_search_engine_index 
							(index_id, index_title, index_url, index_short_description, index_keywords, 
							index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
							index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
							index_unique_hits) 
							VALUES 
							(NULL, $inp_index_title_mysql, $inp_index_url_mysql, $inp_index_short_description_mysql, $inp_index_keywords_mysql, 
							$inp_index_module_name_mysql, $inp_index_module_part_name_mysql, '0', $inp_index_reference_name_mysql, $inp_index_reference_id_mysql,
							'0', $inp_index_is_ad_mysql, '$datetime', '$datetime_saying', $inp_index_language_mysql,
							0)")
							or die(mysqli_error($link));
						}


					} // guides

				} // groups

			} // all references
?>