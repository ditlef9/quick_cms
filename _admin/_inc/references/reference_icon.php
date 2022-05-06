<?php
/**
*
* File: _admin/_inc/references/reference_icon.php
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

/*- Functions ------------------------------------------------------------------------ */
include("_functions/get_extension.php");

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


	if($action == ""){
		if($process == "1"){
		// Folder
			if(!(is_dir("../$get_current_reference_title_clean"))){
				mkdir("../$get_current_reference_title_clean");
			}
			if(!(is_dir("../$get_current_reference_title_clean/_gfx"))){
				mkdir("../$get_current_reference_title_clean/_gfx");
			}


			$ft = "info";
           		$fm = "nothing";
			$icon_sizes = array('16', '32', '48', '64', '96', '260');
			for($x=0;$x<sizeof($icon_sizes);$x++){
				$icon_size = $icon_sizes[$x] . "x" . $icon_sizes[$x];
				


				$image_name = stripslashes($_FILES["inp_icon_$icon_size"]['name']);
				$extension = get_extension($image_name);
				$extension = strtolower($extension);

				if($image_name){
					if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
						$ft = "warning";
						$fm = "unknown_file_extension_$extension";

					}
					else{
 
						// Give new name
						$inp_name = $get_current_reference_title_clean . "_icon_" . $icon_size . ".$extension";
						$new_path = "../$get_current_reference_title_clean/_gfx";
						$uploaded_file = $new_path . "/" . $inp_name;

						// Upload file
						if (move_uploaded_file($_FILES["inp_icon_$icon_size"]['tmp_name'], $uploaded_file)) {

							// Get image size
							$file_size = filesize($uploaded_file);
						
							// Check with and height
							list($width,$height) = getimagesize($uploaded_file);
	
							if($width == "" OR $height == ""){
								unlink("$uploaded_file");

								$ft = "warning";
								$fm = "getimagesize_failed";

							
							}
							else{
								// All ok
								$inp_icon_mysql = quote_smart($link, $inp_name);
							
							
								$datetime = date("Y-m-d H:i:s");


								if($icon_sizes[$x] == "16"){
									$result = mysqli_query($link, "UPDATE $t_references_index SET 
										reference_icon_16=$inp_icon_mysql, 
										reference_updated='$datetime'
										WHERE reference_id=$get_current_reference_id") or die(mysqli_error($link));
								}
								if($icon_sizes[$x] == "32"){
									$result = mysqli_query($link, "UPDATE $t_references_index SET 
										reference_icon_32=$inp_icon_mysql, 
										reference_updated='$datetime'
										WHERE reference_id=$get_current_reference_id") or die(mysqli_error($link));
								}
								if($icon_sizes[$x] == "48"){
									$result = mysqli_query($link, "UPDATE $t_references_index SET 
										reference_icon_48=$inp_icon_mysql, 
										reference_updated='$datetime'
										WHERE reference_id=$get_current_reference_id") or die(mysqli_error($link));
								}
								if($icon_sizes[$x] == "64"){
									$result = mysqli_query($link, "UPDATE $t_references_index SET 
										reference_icon_64=$inp_icon_mysql, 
										reference_updated='$datetime'
										WHERE reference_id=$get_current_reference_id") or die(mysqli_error($link));
								}
								if($icon_sizes[$x] == "96"){
									$result = mysqli_query($link, "UPDATE $t_references_index SET 
										reference_icon_96=$inp_icon_mysql, 
										reference_updated='$datetime'
										WHERE reference_id=$get_current_reference_id") or die(mysqli_error($link));
								}
								if($icon_sizes[$x] == "260"){
									$result = mysqli_query($link, "UPDATE $t_references_index SET 
										reference_icon_260=$inp_icon_mysql, 
										reference_updated='$datetime'
										WHERE reference_id=$get_current_reference_id") or die(mysqli_error($link));
								}

								$ft = "success";
								$fm = "icon_uploaded";

						
							}

						}
						else{
							switch ($_FILES['inp_food_image']['error']) {
								case UPLOAD_ERR_OK:
           								$fm = "There is no error, the file uploaded with success.";
									break;
								case UPLOAD_ERR_NO_FILE:
           								$fm = "no_file_uploaded";
									break;
								case UPLOAD_ERR_INI_SIZE:
           								$fm = "to_big_size_in_configuration";
									break;
								case UPLOAD_ERR_FORM_SIZE:
           								$fm = "to_big_size_in_form";
									break;
								default:
           								$fm = "unknown_error";
									break;
							}	
							$ft = "warning";
						
						}
					}
				}
			} // for

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
					<li><a href=\"index.php?open=references&amp;page=reference_open&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Info</a>
					<li><a href=\"index.php?open=references&amp;page=reference_groups_and_guides&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Groups and guides</a>
					<li><a href=\"index.php?open=references&amp;page=reference_icon&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"active\">Icon</a>
					<li><a href=\"index.php?open=references&amp;page=reference_read_from_file&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Read from file</a>
					<li><a href=\"index.php?open=references&amp;page=reference_write_to_file&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Write to file</a>
					<li><a href=\"index.php?open=references&amp;page=reference_delete&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
				</ul>
			</div>
			<div class=\"clear\" style=\"height: 10px;\"></div>
		<!-- //Refrence navigation -->


			
		<!-- Icon 48, 64, 96 -->
			<form method=\"post\" action=\"index.php?open=references&amp;page=reference_icon&amp;reference_id=$get_current_reference_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			";

			$icon_sizes = array('16', '32', '48', '64', '96', '260');
			for($x=0;$x<sizeof($icon_sizes);$x++){
				$icon_size = $icon_sizes[$x] . "x" . $icon_sizes[$x];
				echo"
				<!-- Icon x -->
					<h2>$icon_size</h2>
		
					<table>
					 <tr>
					  <td style=\"vertical-align:top;padding-right: 20px;\">
						<p><b>New icon (<a href=\"https://www.google.com/search?q=$get_current_reference_title_clean+imagesize%3A$icon_size+filetype:png&amp;sxsrf=ACYBGNQ-QNqgpcTcu6z0kt6lYgAIX2mrcg:1570910144451&amp;source=lnms&amp;tbm=isch&amp;sa=X&amp;ved=0ahUKEwiM9IipwJflAhWGo4sKHQFDAC8Q_AUIESgB&amp;biw=1536&amp;bih=728\">$icon_size</a>)</b><br />
						<input type=\"file\" name=\"inp_icon_$icon_size\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						</p>
					  </td>
					  <td style=\"vertical-align:top;padding-right: 20px;\">
						";
						if($icon_sizes[$x] == "16" && $get_current_reference_icon_16 != "" && file_exists("../$get_current_reference_title_clean/_gfx/$get_current_reference_icon_16")){
							echo"<p><img src=\"../$get_current_reference_title_clean/_gfx/$get_current_reference_icon_16\" alt=\"$get_current_reference_icon_16\" /></p>\n";
						}
						if($icon_sizes[$x] == "32" && $get_current_reference_icon_32 != "" && file_exists("../$get_current_reference_title_clean/_gfx/$get_current_reference_icon_32")){
							echo"<p><img src=\"../$get_current_reference_title_clean/_gfx/$get_current_reference_icon_32\" alt=\"$get_current_reference_icon_32\" /></p>\n";
						}
						if($icon_sizes[$x] == "48" && $get_current_reference_icon_48 != "" && file_exists("../$get_current_reference_title_clean/_gfx/$get_current_reference_icon_48")){
							echo"<p><img src=\"../$get_current_reference_title_clean/_gfx/$get_current_reference_icon_48\" alt=\"$get_current_reference_icon_48\" /></p>\n";
						}
						if($icon_sizes[$x] == "64" && $get_current_reference_icon_64 != "" && file_exists("../$get_current_reference_title_clean/_gfx/$get_current_reference_icon_64")){
							echo"<p><img src=\"../$get_current_reference_title_clean/_gfx/$get_current_reference_icon_64\" alt=\"$get_current_reference_icon_64\" /></p>\n";
						}
						if($icon_sizes[$x] == "96" && $get_current_reference_icon_96 != "" && file_exists("../$get_current_reference_title_clean/_gfx/$get_current_reference_icon_96")){
							echo"<p><img src=\"../$get_current_reference_title_clean/_gfx/$get_current_reference_icon_96\" alt=\"$get_current_reference_icon_96\" /></p>\n";
						}
						if($icon_sizes[$x] == "260" && $get_current_reference_icon_260 != "" && file_exists("../$get_current_reference_title_clean/_gfx/$get_current_reference_icon_260")){
							echo"<p><img src=\"../$get_current_reference_title_clean/_gfx/$get_current_reference_icon_260\" alt=\"$get_current_reference_icon_260\" /></p>\n";
						}
						echo"
					  </td>
					 </tr>
					</table>
					
				<!-- //Icon x -->
				";
			}
			echo"
			<p>
			<input type=\"submit\" value=\"Upload\" class=\"btn_default\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>
			</form>
		<!-- //Icon 48, 64, 96 -->
		";
	} // action ==""
} // found
?>