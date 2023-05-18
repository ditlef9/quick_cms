<?php
/**
*
* File: references/_includes/reference.php
* Version 2.0.0
* Date 22:38 03.05.2019
* Copyright (c) 2011-2019 Localhost
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Language -------------------------------------------------------------------------- */
include("$root/_admin/_translations/site/$l/references/ts_index.php");

/*- Tables ---------------------------------------------------------------------------- */
$t_references_title_translations = $mysqlPrefixSav . "references_title_translations";
$t_references_categories_main	 = $mysqlPrefixSav . "references_categories_main";
$t_references_categories_sub 	 = $mysqlPrefixSav . "references_categories_sub";
$t_references_index		 = $mysqlPrefixSav . "references_index";
$t_references_index_groups	 = $mysqlPrefixSav . "references_index_groups";
$t_references_index_guides	 = $mysqlPrefixSav . "references_index_guides";


// Find reference
$reference_title_mysql = quote_smart($link, $referenceTitleSav);
$query = "SELECT reference_id, reference_title, reference_title_clean, reference_is_active, reference_front_page_intro, reference_description, reference_language, reference_main_category_id, reference_main_category_title, reference_sub_category_id, reference_sub_category_title, reference_image_file, reference_image_thumb, reference_icon_16, reference_icon_32, reference_icon_48, reference_icon_64, reference_icon_96, reference_icon_260, reference_groups_count, reference_guides_count, reference_read_times, reference_read_times_ip_block, reference_created, reference_updated FROM $t_references_index WHERE reference_title=$reference_title_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_reference_id, $get_current_reference_title, $get_current_reference_title_clean, $get_current_reference_is_active, $get_current_reference_front_page_intro, $get_current_reference_description, $get_current_reference_language, $get_current_reference_main_category_id, $get_current_reference_main_category_title, $get_current_reference_sub_category_id, $get_current_reference_sub_category_title, $get_current_reference_image_file, $get_current_reference_image_thumb, $get_current_reference_icon_16, $get_current_reference_icon_32, $get_current_reference_icon_48, $get_current_reference_icon_64, $get_current_reference_icon_96, $get_current_reference_icon_260, $get_current_reference_groups_count, $get_current_reference_guides_count, $get_current_reference_read_times, $get_current_reference_read_times_ip_block, $get_current_reference_created, $get_current_reference_updated) = $row;

if($get_current_reference_id != ""){
	

	if($action == ""){
		// Read times
		$my_ip = $_SERVER['REMOTE_ADDR'];
		$my_ip = output_html($my_ip);
		
		$ipblock_array = explode("\n", $get_current_reference_read_times_ip_block);
		$size = sizeof($ipblock_array);
		$i_have_visited_before = "false";
		for($x=0;$x<$size;$x++){
			if($ipblock_array[$x] == "$my_ip"){
				$i_have_visited_before = "true";
			}
		}
			
		if($i_have_visited_before == "false"){
			$inp_reference_read_times = $get_current_reference_read_times+1;
		
			if($get_current_reference_read_times_ip_block == ""){
				$inp_reference_read_times_ip_block = "$my_ip";
			}
			else{
				$inp_reference_read_times_ip_block = "$my_ip\n" . substr($get_current_reference_read_times_ip_block, 0, 400);
			}
			$inp_reference_read_times_ip_block_mysql = quote_smart($link, $inp_reference_read_times_ip_block);
			$result = mysqli_query($link, "UPDATE $t_references_index SET reference_read_times=$inp_reference_read_times, reference_read_times_ip_block=$inp_reference_read_times_ip_block_mysql WHERE reference_id=$get_current_reference_id") or die(mysqli_error($link));
		}

		// Headline
		echo"
		<h1>$get_current_reference_title</h1>
		<a href=\"index.php?l=$l\"><img src=\"_gfx/$get_current_reference_icon_96\" alt=\"$get_current_reference_icon_96\" style=\"float: right;padding: 0px 0px 10px 10px;\" /></a>
		
		<!-- About reference -->
			<div style=\"height:20px;\"></div>
			<p>
			$get_current_reference_description
			</p>
			
			<div style=\"height:20px;\"></div>
		<!-- //About reference -->

		<!-- Groups and guides -->\n";
			
			$query = "SELECT group_id, group_title, group_title_clean, group_number, group_read_times FROM $t_references_index_groups WHERE group_reference_id=$get_current_reference_id ORDER BY group_number ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_group_id, $get_group_title, $get_group_title_clean, $get_group_number, $get_group_read_times) = $row;


				echo"
				<h2><a href=\"$root/$get_current_reference_title_clean/$get_group_title_clean/index.php?reference_id=$get_current_reference_id&amp;group_id=$get_group_id&amp;l=$l\" class=\"h2\">$get_group_title</a></h2>
				
				<div style=\"height:10px;\"></div>

				<table class=\"hor-zebra\">
				 <thead>
				  <tr>
				   <th scope=\"col\">
					<span>$l_title</span>
				   </th>
				   <th scope=\"col\">
					<span>$l_description</span>
				   </th>
				  </tr>
				 </thead>
				 <tbody>";
				// Get guides
				$query_lessons = "SELECT guide_id, guide_title, guide_title_clean, guide_title_short, guide_title_length, guide_short_description FROM $t_references_index_guides WHERE guide_group_id=$get_group_id ORDER BY guide_number ASC";
				$result_lessons = mysqli_query($link, $query_lessons);
				while($row_lessons = mysqli_fetch_row($result_lessons)) {
					list($get_guide_id, $get_guide_title, $get_guide_title_clean, $get_guide_title_short, $get_guide_title_length, $get_guide_short_description) = $row_lessons;
					
					if(isset($style) && $style == ""){
						$style = "odd";
					}
					else{
						$style = "";
					}
					echo"
					 <tr>
					  <td class=\"$style\" style=\"width: 20%;\">
						<span><a href=\"$root/$get_current_reference_title_clean/$get_group_title_clean/$get_guide_title_clean.php?reference_id=$get_current_reference_id&amp;group_id=$get_group_id&amp;guide_id=$get_guide_id&amp;l=$l\">$get_guide_title</a></span>
					  </td>
					  <td class=\"$style\">
						<span>$get_guide_short_description</span>
					  </td>
					 </tr>";
				} // guides
				echo"
				 </tbody>
				</table>
				";
			} // groups
			echo"
			
			<div style=\"height:20px;\"></div>
		<!-- //Groups and guides -->
		";

	} // action == ""
} // course found
else{
	echo"<p>Reference not found</p>";
}
?>