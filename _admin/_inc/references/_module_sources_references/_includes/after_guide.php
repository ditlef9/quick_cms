<?php
/**
*
* File: references/_includes/after_group_guide.php
* Version 2.0.0
* Date 22:38 03.05.2019
* Copyright (c) 2011-2019 Localhost
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/


/*- Course content --------------------------------------------------------------------------- */
// Usage:
// include("$root/references/_includes/after_guide.php");



/*- Translation ------------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/references/ts_after_guide.php");

/*- Tables ------------------------------------------------------------------------------ */
$t_references_title_translations 	= $mysqlPrefixSav . "references_title_translations";
$t_references_categories_main	 	= $mysqlPrefixSav . "references_categories_main";
$t_references_categories_sub 	 	= $mysqlPrefixSav . "references_categories_sub";

$t_references_index		 	= $mysqlPrefixSav . "references_index";
$t_references_index_groups	 	= $mysqlPrefixSav . "references_index_groups";
$t_references_index_groups_images 	= $mysqlPrefixSav . "references_index_groups_images";
$t_references_index_guides	 	= $mysqlPrefixSav . "references_index_guides";
$t_references_index_guides_comments	= $mysqlPrefixSav . "references_index_guides_comments";
$t_references_index_guides_images	= $mysqlPrefixSav . "references_index_guides_images";

/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['reference_id'])) {
	$reference_id = $_GET['reference_id'];
	$reference_id = strip_tags(stripslashes($reference_id));
}
else{
	$reference_id = "";
}
if(isset($_GET['group_id'])) {
	$group_id = $_GET['group_id'];
	$group_id = strip_tags(stripslashes($group_id));
}
else{
	$group_id = "";
}
if(isset($_GET['guide_id'])) {
	$guide_id = $_GET['guide_id'];
	$guide_id = strip_tags(stripslashes($guide_id));
}
else{
	$guide_id = "";
}

if($guide_id != ""){
	// Search for guide
	$reference_id_mysql = quote_smart($link, $reference_id);
	$group_id_mysql = quote_smart($link, $group_id);
	$guide_id_mysql = quote_smart($link, $guide_id);
	$query = "SELECT guide_id, guide_title, guide_title_clean, guide_title_short, guide_title_length, guide_number, guide_group_id, guide_group_title, guide_reference_id, guide_reference_title, guide_read_times, guide_read_ipblock, guide_created, guide_updated, guide_comments FROM $t_references_index_guides WHERE guide_id=$guide_id_mysql AND guide_group_id=$group_id_mysql AND guide_reference_id=$reference_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_guide_id, $get_current_guide_title, $get_current_guide_title_clean, $get_current_guide_title_short, $get_current_guide_title_length, $get_current_guide_number, $get_current_guide_group_id, $get_current_guide_group_title, $get_current_guide_reference_id, $get_current_guide_reference_title, $get_current_guide_read_times, $get_current_guide_read_ipblock, $get_current_guide_created, $get_current_guide_updated, $get_current_guide_comments) = $row;

	if($get_current_guide_id != ""){
		// Last read
		$datetime = date("Y-m-d H:i:s");
		$date_formatted = date("j M Y");
		$result = mysqli_query($link, "UPDATE $t_references_index_guides SET guide_last_read='$datetime', guide_last_read_formatted='$date_formatted' WHERE guide_id=$get_current_guide_id");

		// Visits
		$my_ip = $_SERVER['REMOTE_ADDR'];
		$my_ip = output_html($my_ip);
		
		$ipblock_array = explode("\n", $get_current_guide_read_ipblock);
		$size = sizeof($ipblock_array);
		$i_have_visited_before = "false";
		for($x=0;$x<$size;$x++){
			if($ipblock_array[$x] == "$my_ip"){
				$i_have_visited_before = "true";
			}
		}
		if($i_have_visited_before == "false"){
			$inp_read_times = $get_current_guide_read_times+1;
			
			if($get_current_guide_read_ipblock == ""){
				$inp_read_ipblock = "$my_ip";
			}
			else{
				$inp_read_ipblock = "$my_ip\n" . substr($get_current_guide_read_ipblock, 0, 400);
			}
			$inp_read_ipblock_mysql = quote_smart($link, $inp_read_ipblock);

			$result = mysqli_query($link, "UPDATE $t_references_index_guides SET guide_read_times=$inp_read_times, guide_read_ipblock=$inp_read_ipblock_mysql WHERE guide_id=$get_current_guide_id") or die(mysqli_error($link));


		}



		// Comments
		echo"
		<!-- Comments -->
			<a id=\"comments\"></a>

			<h2>$l_comments</h2>


			<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				else{
					$fm = str_replace("_", " ", $fm);
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
			echo"	
			<!-- //Feedback -->

			<!-- Write comment -->";
				if(isset($_SESSION['user_id'])){
					echo"
					<p>
					<a href=\"$root/references/comment_guide_new.php?reference_id=$get_current_guide_reference_id&amp;group_id=$get_current_guide_group_id&amp;guide_id=$get_current_guide_id&amp;l=$l\" class=\"btn_default\">$l_write_a_comment</a>	
					</p>
					";
				}
				else{
					echo"
					<p>
					<a href=\"$root/users/login.php?l=$l&amp;referer=references/comment_guide_new.php?reference_id=$get_current_guide_reference_id&amp;group_id=$get_current_guide_group_id&amp;guide_id=$get_current_guide_id&amp;l=$l\" class=\"btn_default\">$l_write_a_comment</a>	
					</p>
					";
				}
				echo"
			<!-- //Write comment -->

			<!-- View comments -->
			";

				// me
				if(isset($_SESSION['user_id'])){
					$my_user_id = $_SESSION['user_id'];
					$my_user_id = output_html($my_user_id);
					$my_user_id_mysql = quote_smart($link, $my_user_id);
					$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;
				}


				$query_groups = "SELECT comment_id, comment_reference_id, comment_reference_title, comment_group_id, comment_group_title, comment_guide_id, comment_guide_title, comment_language, comment_approved, comment_datetime, comment_time, comment_date_print, comment_user_id, comment_user_alias, comment_user_image_path, comment_user_image_file, comment_user_ip, comment_user_hostname, comment_user_agent, comment_title, comment_text, comment_rating, comment_helpful_clicks, comment_useless_clicks, comment_marked_as_spam, comment_spam_checked, comment_spam_checked_comment FROM $t_references_index_guides_comments WHERE comment_reference_id=$get_current_guide_reference_id AND comment_group_id=$get_current_guide_group_id AND comment_guide_id=$get_current_guide_id ORDER BY comment_id ASC";
				$result_groups = mysqli_query($link, $query_groups);
				while($row_groups = mysqli_fetch_row($result_groups)) {
					list($get_comment_id, $get_comment_reference_id, $get_comment_reference_title, $get_comment_group_id, $get_comment_group_title, $get_comment_guide_id, $get_comment_guide_title, $get_comment_language, $get_comment_approved, $get_comment_datetime, $get_comment_time, $get_comment_date_print, $get_comment_user_id, $get_comment_user_alias, $get_comment_user_image_path, $get_comment_user_image_file, $get_comment_user_ip, $get_comment_user_hostname, $get_comment_user_agent, $get_comment_title, $get_comment_text, $get_comment_rating, $get_comment_helpful_clicks, $get_comment_useless_clicks, $get_comment_marked_as_spam, $get_comment_spam_checked, $get_comment_spam_checked_comment) = $row_groups;
		
					echo"
					<a id=\"comment$get_comment_id\"></a>
					<div class=\"clear\" style=\"height:14px;\"></div>

					<div class=\"comment_item\">
					<table style=\"width: 100%;\">
					 <tr>
					  <td style=\"width: 80px;vertical-align:top;\">
						<!-- Image -->
							<p style=\"padding: 10px 0px 10px 0px;margin:0;\">
							<a href=\"$root/users/view_profile.php?user_id=$get_comment_user_id&amp;l=$l\">";
							if($get_comment_user_image_file == "" OR !(file_exists("$root/$get_comment_user_image_path/$get_comment_user_image_file"))){ 
								echo"<img src=\"$root/comments/_gfx/avatar_blank_65.png\" alt=\"avatar_blank_65.png\" class=\"comment_avatar\" />";
							} 
							else{ 
								$inp_new_x = 65; // 950
								$inp_new_y = 65; // 640
								$thumb_full_path = "$root/$get_comment_user_image_path/user_" . $get_comment_user_id . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
								if(!(file_exists("$thumb_full_path"))){
									resize_crop_image($inp_new_x, $inp_new_y, "$root/_uploads/users/images/$get_comment_user_id/$get_comment_user_image_file", "$thumb_full_path");
								}

								echo"	<img src=\"$thumb_full_path\" alt=\"$get_comment_user_image_file\" class=\"comment_view_avatar\" />"; 
							} 
							echo"</a>
							</p>
						<!-- //Image -->
					  </td>
					  <td style=\"vertical-align:top;\">

						<!-- menu -->
						<table style=\"width: 100%;\">
						 <tr>
						  <td style=\"text-align: right;\">


							<!-- Menu -->
							";
							if(isset($my_user_id)){
								if($get_comment_user_id == "$my_user_id" OR $get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
									echo"
									<a href=\"$root/references/comment_guide_edit.php?comment_id=$get_comment_id&amp;reference_id=$get_comment_reference_id&amp;group_id=$get_comment_group_id&amp;guide_id=$get_comment_guide_id&amp;l=$l\"><img src=\"$root/users/_gfx/edit.png\" alt=\"edit.png\" title=\"$l_edit\" /></a>
									<a href=\"$root/references/comment_guide_delete.php?comment_id=$get_comment_id&amp;reference_id=$get_comment_reference_id&amp;group_id=$get_comment_group_id&amp;guide_id=$get_comment_guide_id&amp;l=$l\"><img src=\"$root/users/_gfx/delete.png\" alt=\"delete.png\" title=\"$l_delete\" /></a>
									";
								}
								else{
									echo"
									<a href=\"$root/courses/comment_guide_report.php?comment_id=$get_comment_id&amp;reference_id=$get_comment_reference_id&amp;group_id=$get_comment_group_id&amp;guide_id=$get_comment_guide_id&amp;l=$l\"><img src=\"$root/comments/_gfx/report_grey.png\" alt=\"report_grey.png\" title=\"$l_report\" /></a>
									";
								}
							}
							echo"
							<!-- //Menu -->
						  </td>
						 </tr>
						</table>
						<!-- //menu -->


						<!-- Author + date -->
						<p style=\"margin:0;padding:0;\">
						<span class=\"course_comment_by\">$l_by</span>
						<a href=\"$root/users/view_profile.php?user_id=$get_comment_user_id&amp;l=$l\" class=\"course_comment_author\">$get_comment_user_alias</a>
						<span class=\"course_comment_at\">$l_special_translation_at_date_lowercase</span>
						<a href=\"#comment$get_comment_id\" class=\"course_comment_date\">$get_comment_date_print</a></span>
						</p>

						<!-- //Author + date -->

						<!-- Comment -->
							<p style=\"margin-top: 0px;padding-top: 0;\">$get_comment_text</p>
						<!-- Comment -->
					  </td>
					 </tr>
					</table>
					</div>
					";
				}
			echo"
			<!-- //View comments -->

		<!-- //Comments -->
		";
		
	} // Content found
	else{
		echo"<div class=\"warning\"><p>Guide not found</p></div>\n";
	}
} // lesson






?>