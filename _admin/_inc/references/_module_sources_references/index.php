<?php
/**
*
* File: references/index.php
* Version 2.0.0
* Date 22:38 03.05.2019
* Copyright (c) 2011-2019 Localhost
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "201905032238";
$layoutNumberOfColumn = "2";
$layoutCommentsActive = "0";

/*- Root dir --------------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config --------------------------------------------------------------------------- */
include("$root/_admin/website_config.php");


/*- Tables ---------------------------------------------------------------------------- */
$t_references_title_translations = $mysqlPrefixSav . "references_title_translations";
$t_references_categories_main	 = $mysqlPrefixSav . "references_categories_main";
$t_references_categories_sub 	 = $mysqlPrefixSav . "references_categories_sub";
$t_references_index		 = $mysqlPrefixSav . "references_index";
$t_references_index_groups	 = $mysqlPrefixSav . "references_index_groups";
$t_references_index_guides	 = $mysqlPrefixSav . "references_index_guides";

/*- Content ---------------------------------------------------------- */

// Title
$l_mysql = quote_smart($link, $l);
$query = "SELECT reference_title_translation_id, reference_title_translation_title FROM $t_references_title_translations WHERE reference_title_translation_language=$l_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_reference_title_translation_id, $get_current_reference_title_translation_title) = $row;
if($get_current_reference_title_translation_id == ""){
	mysqli_query($link, "INSERT INTO $t_references_title_translations
	(reference_title_translation_id, reference_title_translation_title, reference_title_translation_language) 
	VALUES 
	(NULL, 'References', $l_mysql)")
	or die(mysqli_error($link));
	$get_current_reference_title_translation_title = "References";
}


/*- Header ----------------------------------------------------------- */
$website_title = "$get_current_reference_title_translation_title";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");






echo"
<h1>$get_current_reference_title_translation_title</h1> 
";

// Get all references
$l_mysql = quote_smart($link, $l);
$query = "SELECT reference_id, reference_title, reference_title_clean, reference_is_active, reference_front_page_intro, reference_description, reference_language, reference_main_category_id, reference_main_category_title, reference_sub_category_id, reference_sub_category_title, reference_image_file, reference_image_thumb, reference_icon_16, reference_icon_32, reference_icon_48, reference_icon_64, reference_icon_96, reference_icon_260, reference_groups_count, reference_guides_count, reference_read_times, reference_created, reference_updated FROM $t_references_index WHERE reference_language=$l_mysql ORDER BY reference_title ASC";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_row($result)) {
	list($get_reference_id, $get_reference_title, $get_reference_title_clean, $get_reference_is_active, $get_reference_front_page_intro, $get_reference_description, $get_reference_language, $get_reference_main_category_id, $get_reference_main_category_title, $get_reference_sub_category_id, $get_reference_sub_category_title, $get_reference_image_file, $get_reference_image_thumb, $get_reference_icon_16, $get_reference_icon_32, $get_reference_icon_48, $get_reference_icon_64, $get_reference_icon_96, $get_reference_icon_260, $get_reference_groups_count, $get_reference_guides_count, $get_reference_read_times, $get_reference_created, $get_reference_updated) = $row;
	echo"
		<table style=\"width: 100%;\">
		  <tr>";
	if(file_exists("$root/$get_reference_title_clean/_gfx/$get_reference_icon_48")){
		echo"
		   <td style=\"width: 48px;vertical-align:top;padding-right: 10px;\">
			<p>
			<a href=\"$root/$get_reference_title_clean/index.php?l=$l\"><img src=\"$root/$get_reference_title_clean/_gfx/$get_reference_icon_48\" alt=\"$get_reference_icon_48\" /></a>
			</p>
		   </td>
		";
	}
	echo"
		   <td style=\"vertical-align:top;padding-right: 10px;\">
			<p class=\"reference_title\">
			<a href=\"$root/$get_reference_title_clean/index.php?l=$l\">$get_reference_title</a>
			</p>
		  </td>
		 </tr>
		</table>
	";
}
echo"
<p>
&nbsp;
</p>
";

/*- Footer ----------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>