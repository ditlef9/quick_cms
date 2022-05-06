<?php
/**
*
* File: downloads/open_sub_category.php
* Version 11:51 18.11.2018
* Copyright (c) 2009-2018 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Configuration ---------------------------------------------------------------------------- */
$pageIdSav            = "0";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

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
include("_tables_downloads.php");

/*- Varialbes  ---------------------------------------------------- */
if(isset($_GET['main_category_id'])) {
	$main_category_id = $_GET['main_category_id'];
	$main_category_id = strip_tags(stripslashes($main_category_id));
}
else{
	$main_category_id = "";
}
if(isset($_GET['sub_category_id'])) {
	$sub_category_id = $_GET['sub_category_id'];
	$sub_category_id = strip_tags(stripslashes($sub_category_id));
}
else{
	$sub_category_id = "";
}
$l_mysql = quote_smart($link, $l);


/*- Find main category ------------------------------------------------------------------------------ */
$main_category_id_mysql = quote_smart($link, $main_category_id);
$query = "SELECT main_category_id, main_category_title, main_category_icon_path, main_category_icon_file FROM $t_downloads_main_categories WHERE main_category_id=$main_category_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_icon_path, $get_current_main_category_icon_file) = $row;

if($get_current_main_category_id == ""){
	$website_title = "Server error 404 - $l_downloads";
}
else{
	// Find translation
	$query_t = "SELECT main_category_translation_id, main_category_translation_value FROM $t_downloads_main_categories_translations WHERE main_category_id='$get_current_main_category_id' AND main_category_translation_language=$l_mysql";
	$result_t = mysqli_query($link, $query_t);
	$row_t = mysqli_fetch_row($result_t);
	list($get_current_main_category_translation_id, $get_current_main_category_translation_value) = $row_t;


	// Sub category
	$sub_category_id_mysql = quote_smart($link, $sub_category_id);
	$query = "SELECT sub_category_id, sub_category_title FROM $t_downloads_sub_categories WHERE sub_category_id=$sub_category_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_sub_category_id, $get_current_sub_category_title) = $row;

	if($get_current_sub_category_id == ""){
		$website_title = "Server error 404 - $l_downloads";
	}
	else{
		// Find translation
		$query_t = "SELECT sub_category_id, sub_category_translation_value FROM $t_downloads_sub_categories_translations WHERE sub_category_id='$get_current_sub_category_id' AND sub_category_translation_language=$l_mysql";
		$result_t = mysqli_query($link, $query_t);
		$row_t = mysqli_fetch_row($result_t);
		list($get_current_sub_category_id, $get_current_sub_category_translation_value) = $row_t;
		
		if($get_current_sub_category_id == ""){
			echo"<p>Sub category translation not found</p>";
		}
		$website_title = "$get_current_sub_category_translation_value - $get_current_main_category_translation_value - $l_downloads";
	}
}


/*- Headers ---------------------------------------------------------------------------------- */
include("$root/_webdesign/header.php");


/*- Content --------------------------------------------------------------------------- */
if($get_current_main_category_id == ""){
	echo"
	<h1>Server error 404</h1>
	";
}
else{
	if($get_current_sub_category_id == ""){
		echo"
		<h1>Server error 404</h1>
		";
	}
	else{
		// Headline
		if(file_exists("$root/$get_current_main_category_icon_path/$get_current_main_category_icon_file")){
			echo"
			<table>
			 <tr>
			  <td style=\"padding: 10px 10px 0px 0px;vertical-algin:top;\">
				<img src=\"$root/$get_current_main_category_icon_path/$get_current_main_category_icon_file\" alt=\"$get_current_main_category_icon_file\" />
			  </td>
			  <td style=\"vertical-algin:top;\">
				<h1>$get_current_sub_category_translation_value</h1>
			  </td>
			 </tr>
			</table>
			";
		}
		else{
			echo"
			<h1>$get_current_sub_category_translation_value</h1>
			";
		}
		echo"


		<!-- Where am I ? -->
			<p>
			<b>$l_you_are_here:</b><br />
			<a href=\"index.php?l=$l\">$l_downloads</a>
			&gt;
			<a href=\"open_main_category.php?main_category_id=$main_category_id&amp;l=$l\">$get_current_main_category_translation_value</a>
			&gt;
			<a href=\"open_sub_category.php?main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;l=$l\">$get_current_sub_category_translation_value</a>
			</p>
		<!-- //Where am I ? -->


		<!-- Downloads in sub category -->
			<table class=\"downloads_list\">
				";
				// Get all downloads
				$query = "SELECT download_id, download_title, download_introduction, download_image_path, download_image_store_thumb, download_unique_hits FROM $t_downloads_index WHERE download_sub_category_id=$get_current_sub_category_id ORDER BY download_unique_hits DESC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_download_id, $get_download_title, $get_download_introduction, $get_download_image_path, $get_download_image_store_thumb, $get_download_unique_hits) = $row;

					echo"
					 <tr class=\"downloads_list_tr\">";
					if(file_exists("$root/$get_download_image_path/$get_download_image_store_thumb") && $get_download_image_store_thumb != ""){
						echo"
						  <td class=\"downloads_list_td\" style=\"width: 184px;padding: 10px 0px 10px 10px\">
							<img src=\"$root/$get_download_image_path/$get_download_image_store_thumb\" alt=\"$get_download_image_store_thumb\" width=\"184\" height=\"69\" />
						  </td>
					  <td class=\"downloads_list_td\" style=\"vertical-align: top;padding-left: 20px;\">
						";
					}
					else{
						echo"
						  <td class=\"downloads_list_td\" style=\"vertical-align: top;padding-left: 20px;\" colspan=\"2\">
						";
					}
					echo"
						<p>
						<a href=\"view_download.php?download_id=$get_download_id&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;l=$l\" class=\"download_title_a\">$get_download_title</a>
						</p>

						<p class=\"download_intro\">
							$get_download_introduction
						</p>

						<p class=\"download_data\">
						$get_download_unique_hits $l_unique_downloads_lowercase
						</p>
					  </td>
					 </tr>
					";
				}
				echo"
			</table>
		<!-- //Downloads in sub category -->
		

		";
	} // sub category found
} // main category found

/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>