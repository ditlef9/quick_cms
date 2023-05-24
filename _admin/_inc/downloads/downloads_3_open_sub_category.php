<?php
/**
*
* File: _admin/_inc/downloads/downloads_3_open_sub_category.php
* Version 2
* Copyright (c) 2008-2023 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */
$t_downloads_index 				= $mysqlPrefixSav . "downloads_index";
$t_downloads_main_categories 			= $mysqlPrefixSav . "downloads_main_categories";
$t_downloads_main_categories_translations 	= $mysqlPrefixSav . "downloads_main_categories_translations";

$t_downloads_sub_categories 			= $mysqlPrefixSav . "downloads_sub_categories";
$t_downloads_sub_categories_translations 	= $mysqlPrefixSav . "downloads_sub_categories_translations";



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


/*- Functuons ---------------------------------------------------- */


/*- Scriptstart ---------------------------------------------------------------------- */
if($action == ""){
	// Main category
	$stmt = $mysqli->prepare("SELECT main_category_id, main_category_title, main_category_icon_path, main_category_icon_file FROM $t_downloads_main_categories WHERE main_category_id=?"); 
	$stmt->bind_param("s", $main_category_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_icon_path, $get_current_main_category_icon_file) = $row;

	if($get_current_main_category_id == ""){
		echo"<p>Not found</p>";
	}
	else{
		// Sub category
		$stmt = $mysqli->prepare("SELECT sub_category_id, sub_category_title FROM $t_downloads_sub_categories WHERE sub_category_id=?"); 
		$stmt->bind_param("s", $sub_category_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_row();
		list($get_current_sub_category_id, $get_sub_category_title) = $row;

		if($get_current_sub_category_id == ""){
			echo"<p>Not found</p>";
		}
		else{

			// Headline
			if(file_exists("../$get_current_main_category_icon_path/$get_current_main_category_icon_file")){
				echo"
				<table>
				 <tr>
				  <td style=\"padding-right: 10px;\">
					<img src=\"../$get_current_main_category_icon_path/$get_current_main_category_icon_file\" alt=\"$get_current_main_category_icon_path/$get_current_main_category_icon_file\" />
				  </td>
				  <td>
					<h1>$get_current_main_category_title</h1>
				  </td>
				 </tr>
				</table>
				";
			}
			else{
				echo"
				<h1>$get_current_main_category_title</h1>
				";
			}
			echo"

			<!-- Where am I ? -->
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l&amp;editor_language=$editor_language\">Downloads</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;main_category_id=$main_category_id&amp;l=$l&amp;editor_language=$editor_language\">$get_current_main_category_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;l=$l&amp;editor_language=$editor_language\">$get_current_sub_category_title</a>
			</p>
			<!-- //Where am I ? -->


			<!-- Downloads in sub category -->

			<table>
				";
				// Get all downloads
				$query = "SELECT download_id, download_title, download_introduction, download_image_path, download_image_store FROM $t_downloads_index WHERE download_sub_category_id=$get_current_sub_category_id ORDER BY download_title ASC";
				$result = $mysqli->query($query);
				while($row = $result->fetch_row()) {
					list($get_download_id, $get_download_title, $get_download_introduction, $get_download_image_path, $get_download_image_store) = $row;

					echo"
					 <tr>";
					if(file_exists("../$get_download_image_path/$get_download_image_store") && $get_download_image_store != ""){
						echo"
						  <td>
							<p>
							<img src=\"../$get_download_image_path/$get_download_image_store\" alt=\"$get_download_image_store\" />
							</p>
						  </td>
						";
					}
					echo"
					  <td>
						<p>
						<a href=\"index.php?open=$open&amp;page=edit_download&amp;download_id=$get_download_id&amp;main_category_id=$get_current_main_category_id&amp;l=$l&amp;editor_language=$editor_language\">$get_download_title</a>
						$get_download_introduction
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
} // action == ""
?>