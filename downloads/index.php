<?php
/**
*
* File: downloads/index.php
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

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_downloads";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");

/*- Variables ------------------------------------------------------------------------------ */
$l_mysql = quote_smart($link, $l);


if(isset($_GET['order_by'])) {
	$order_by = $_GET['order_by'];
	$order_by = strip_tags(stripslashes($order_by));
}
else{
	$order_by = "download_updated_datetime";
}
if(isset($_GET['order_method'])) {
	$order_method = $_GET['order_method'];
	$order_method = strip_tags(stripslashes($order_method));
}
else{
	$order_method = "";
}


/*- Content --------------------------------------------------------------------------- */
echo"
<!-- Search -->
	<div style=\"float: right;padding-top: 12px;\">
		<form method=\"get\" action=\"search.php\" enctype=\"multipart/form-data\">
		<p>
		<input type=\"hidden\" name=\"l\" value=\"$l\" />
		<input type=\"text\" name=\"q\" value=\"\" size=\"10\" id=\"nettport_inp_search_query\" />
		<input type=\"submit\" value=\"$l_search\" id=\"nettport_search_submit_button\" class=\"btn_default\" />
		</p>
	</div>
<!-- //Search -->


<h1>$l_downloads</h1>


<!-- Main categories -->
	<div class=\"clear\"></div>
	<div class=\"downloads_all_main_categories_selector\">
		<a href=\"#\" id=\"show_all_main_categories_link_img\" onclick=\"toggleCategoriesDownloads()\"><img src=\"_gfx/show_all_categories_img.png\" alt=\"show_all_categories_img.png\" class=\"show_all_main_categories_img\" /></a>
		<a href=\"#\" id=\"show_all_main_categories_link_text\" onclick=\"toggleCategoriesDownloads()\">$l_categories</a>
	</div>



	<!-- Hide show categories script -->
		<script>
		function toggleCategoriesDownloads() {
			var element = document.getElementById(\"downloads_show_all_main_categories\");
			var checkDisplay = document.getElementById(\"downloads_show_all_main_categories\").style.display;
			if(checkDisplay == \"\" || checkDisplay == \"none\"){
				element.style.display = 'block';
			}
			else{
				element.style.display = 'none';
			}
		}
		</script>
	<!-- //Hide show categories script -->


	<div id=\"downloads_show_all_main_categories\">
		<ul>
		";
		// Get all categories
		$query = "SELECT main_category_id, main_category_title, main_category_icon_path, main_category_icon_file FROM $t_downloads_main_categories ORDER BY main_category_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_main_category_id, $get_main_category_title, $get_main_category_icon_path, $get_main_category_icon_file) = $row;

			// Fetch translation
			$query_t = "SELECT main_category_translation_id, main_category_translation_value FROM $t_downloads_main_categories_translations WHERE main_category_id='$get_main_category_id' AND main_category_translation_language=$l_mysql";
			$result_t = mysqli_query($link, $query_t);
			$row_t = mysqli_fetch_row($result_t);
			list($get_main_category_translation_id, $get_main_category_translation_value) = $row_t;
			if($get_main_category_translation_id == ""){
				$inp_value_mysql = quote_smart($link, $get_main_category_title);
				echo"<div class=\"info\"><p>Create $get_main_category_id, $l_mysql, $inp_value_mysql)</p></div>";

				mysqli_query($link, "INSERT INTO $t_downloads_main_categories_translations
				(main_category_translation_id, main_category_id, main_category_translation_language, main_category_translation_value) 
				VALUES 
				(NULL, $get_main_category_id, $l_mysql, $inp_value_mysql)")
				or die(mysqli_error($link));
			}

			echo"			";
			echo"<li><a href=\"open_main_category.php?main_category_id=$get_main_category_id&amp;l=$l\">$get_main_category_translation_value</a></li>\n";
		}
		echo"
		</ul>
	</div>
<!-- //Main categories -->


<!-- Top -->
	<a id=\"downloads_list_start\"></a>
	<div class=\"tabs\" style=\"margin-top: 20px;\">
		<ul>
			<li><a href=\"index.php?order_by=download_updated_datetime&amp;order_method=desc&amp;l=$l#downloads_list_start\""; if($order_by == "download_updated_datetime"){ echo" class=\"active\""; } echo">$l_last_updated</a></li>
			<li><a href=\"index.php?order_by=download_unique_hits&amp;order_method=desc&amp;l=$l#downloads_list_start\""; if($order_by == "download_unique_hits"){ echo" class=\"active\""; } echo">$l_top_downloads</a></li>
			<li><a href=\"index.php?order_by=download_id&amp;order_method=desc&amp;l=$l#downloads_list_start\""; if($order_by == "download_id"){ echo" class=\"active\""; } echo">$l_new</a></li>
		</ul>
	</div>
	<table class=\"downloads_list\">
		";
		// Get all downloads
		$query = "SELECT download_id, download_title, download_introduction, download_image_path, download_image_store_thumb, download_file_date_print, download_unique_hits, download_updated_print FROM $t_downloads_index";

		if($order_by == "download_updated_datetime"){
			$query = $query . " ORDER BY download_updated_datetime DESC LIMIT 0,10";
		}
		elseif($order_by == "download_unique_hits"){
			$query = $query . " ORDER BY download_unique_hits DESC LIMIT 0,10";
		}
		elseif($order_by == "download_id"){
			$query = $query . " ORDER BY download_id DESC LIMIT 0,10";
		}


		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_download_id, $get_download_title, $get_download_introduction, $get_download_image_path, $get_download_image_store_thumb, $get_download_file_date_print, $get_download_unique_hits, $get_download_updated_print) = $row;

				echo"
					 <tr class=\"downloads_list_tr\">";
				if(file_exists("$root/$get_download_image_path/$get_download_image_store_thumb") && $get_download_image_store_thumb != ""){
					echo"
					  <td class=\"downloads_list_td\" style=\"width: 184px;padding: 10px 0px 10px 10px\">
						<a href=\"view_download.php?download_id=$get_download_id&amp;l=$l\"><img src=\"$root/$get_download_image_path/$get_download_image_store_thumb\" alt=\"$get_download_image_store_thumb\" width=\"184\" height=\"69\" /></a>
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
						<a href=\"view_download.php?download_id=$get_download_id&amp;l=$l\" class=\"download_title_a\">$get_download_title</a>
						</p>

						<p class=\"download_intro\">
							$get_download_introduction
						</p>

						<p class=\"download_data\">
						$get_download_unique_hits $l_unique_downloads_lowercase";
						if($order_by == "download_updated_datetime"){
							echo" &middot; $l_updated $get_download_updated_print";
						}
						elseif($order_by == "download_id"){
							echo" &middot; $l_released $get_download_file_date_print";
						}
						echo"
						</p>
					  </td>
					 </tr>
					";
				}
				echo"
			</table>

<!-- //Top -->
";


/*- Footer ---------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");

?>