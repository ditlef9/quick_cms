<?php
/**
*
* File: forum/tags.php
* Version 1.0.0.
* Date 18:12 16.04.2019
* Copyright (c) 2008-2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration --------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

/*- Root dir -------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config -------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Forum config ------------------------------------------------------------------------ */
include("$root/_admin/_data/forum.php");
include("_include_tables.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/forum/ts_index.php");
include("$root/_admin/_translations/site/$l/forum/ts_new_topic.php");

/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);


if(isset($_GET['ignore_id'])) {
	$ignore_id = $_GET['ignore_id'];
	$ignore_id = strip_tags(stripslashes($ignore_id));
	$ignore_id = output_html($ignore_id);
}
else{
	$ignore_id = "";
}

/*- Title ---------------------------------------------------------------------------------- */
$query_t = "SELECT title_id, title_language, title_value FROM $t_forum_titles WHERE title_language=$l_mysql";
$result_t = mysqli_query($link, $query_t);
$row_t = mysqli_fetch_row($result_t);
list($get_current_title_id, $get_current_title_language, $get_current_title_value) = $row_t;


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_tags - $get_current_title_value";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");



if($action == ""){
	echo"
	<h1>$l_tags</h1>

	<!-- You are here -->
		<p><b>$l_you_are_here:</b><br />
		<a href=\"index.php?l=$l\">$get_current_title_value</a>
		&gt;
		<a href=\"tags.php?l=$l\">Tags</a>
		</p>
	<!-- //You are here -->

	<!-- Tags -->
		<div class=\"forum_grids\">
				
			";
			$l_mysql = quote_smart($link, $l);
			$query_w = "SELECT tag_id, tag_title, tag_title_clean, tag_introduction, tag_description, tag_topics_total_counter, tag_topics_today_counter, tag_topics_this_week_counter, tag_icon_path, tag_icon_file_16 FROM $t_forum_tags_index WHERE tag_is_official='1' ORDER BY tag_topics_total_counter DESC";
			$result_w = mysqli_query($link, $query_w);
			while($row_w = mysqli_fetch_row($result_w)) {
				list($get_tag_id, $get_tag_title, $get_tag_title_clean, $get_tag_introduction, $get_tag_description, $get_tag_topics_total_counter, $get_tag_topics_today_counter, $get_tag_topics_this_week_counter, $get_tag_icon_path, $get_tag_icon_file_16) = $row_w;


				// Translation
				$query_tag = "SELECT tag_translation_id, tag_id, tag_translation_language, tag_translation_introduction, tag_translation_description FROM $t_forum_tags_index_translation WHERE tag_id=$get_tag_id AND tag_translation_language=$l_mysql";
				$result_tag = mysqli_query($link, $query_tag);
				$row_tag = mysqli_fetch_row($result_tag);
				list($get_current_tag_translation_id, $get_translation_current_tag_id, $get_current_tag_translation_language, $get_current_tag_translation_introduction, $get_current_tag_translation_description) = $row_tag;
	


				echo"
				<div>
					<p>
					<a href=\"open_tag.php?tag=$get_tag_title_clean&amp;l=$l\" class=\"forum_a_tag\"";
				if(file_exists("$root/$get_tag_icon_path/$get_tag_icon_file_16") && $get_tag_icon_file_16 != ""){
					// echo"<img src=\"$root/$get_tag_icon_path/$get_tag_icon_file_16\" alt=\"$get_tag_icon_file_16\" /> ";
					echo" style=\"background-image: url('$root/$get_tag_icon_path/$get_tag_icon_file_16');background-repeat: no-repeat;background-position: center left 4px;padding-left:26px\" ";
				}
				echo">$get_tag_title_clean</a>
					<span class=\"light_grey_small\">&middot; $get_tag_topics_total_counter</span>
					</p>
				
					<p class=\"grey_small forum_tag_introduction_p\">$get_current_tag_translation_introduction</p>

					
				</div>
				";
			}
		echo"
		</div>
	<!-- //Tags -->
	";
} // action == ""



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>