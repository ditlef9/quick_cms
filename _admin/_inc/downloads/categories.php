<?php
/**
*
* File: _admin/_inc/downloads/categories.php
* Version 15.00 03.03.2017
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


/*- Functions ---------------------------------------------------- */


/*- Scriptstart ---------------------------------------------------------------------- */
if($action == ""){
	echo"

	<h1>Categories</h1>

	<!-- Feedback -->
		";
		if($ft != "" && $fm != ""){
			if($fm == "category_deleted"){
				$fm = "Category deleted";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><p>$fm</p></div>";
		}
		echo"
	<!-- //Feedback -->
		


	<p>
	<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_category&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn_default\">New category</a>
	</p>

	<div class=\"vertical\">
		<ul>
		";
		// Get all categories
		$query = "SELECT main_category_id, main_category_title, main_category_icon_path, main_category_icon_file FROM $t_downloads_main_categories ORDER BY main_category_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_main_category_id, $get_main_category_title, $get_main_category_icon_path, $get_main_category_icon_file) = $row;

			echo"			";
			echo"<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=open_category&amp;main_category_id=$get_main_category_id&amp;l=$l&amp;editor_language=$editor_language\">$get_main_category_title</a></li>\n";
		}
		echo"
		</ul>
	</div>
	";
} // action == ""
elseif($action == "open_category"){
	$main_category_id_mysql = quote_smart($link, $main_category_id);
	$query = "SELECT main_category_id, main_category_title, main_category_icon_path, main_category_icon_file FROM $t_downloads_main_categories WHERE main_category_id=$main_category_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_icon_path, $get_current_main_category_icon_file) = $row;

	if($get_current_main_category_id == ""){
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
			<p>
			Icon <a href=\"../$get_current_main_category_icon_path/$get_current_main_category_icon_file\">../$get_current_main_category_icon_path/$get_current_main_category_icon_file</a>
			doesnt exists.
			</p>
			";
		}
		echo"

		<!-- Where am I ? -->
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l&amp;editor_language=$editor_language\">Downloads</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;main_category_id=$main_category_id&amp;l=$l&amp;editor_language=$editor_language\">$get_current_main_category_title</a>
			</p>
		<!-- //Where am I ? -->


		<!-- Actions -->
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_category&amp;main_category_id=$main_category_id&amp;l=$l&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/16x16/edit.png\" alt=\"edit.png\" /></a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_category&amp;main_category_id=$main_category_id&amp;l=$l&amp;editor_language=$editor_language\">Edit category</a>
			&nbsp; &nbsp; 
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_category&amp;main_category_id=$main_category_id&amp;l=$l&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/16x16/delete.png\" alt=\"delete.png\" /></a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_category&amp;main_category_id=$main_category_id&amp;l=$l&amp;editor_language=$editor_language\">Delete category</a>
			</p>
		<!-- //Actions -->


		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_sub_category&amp;main_category_id=$main_category_id&amp;l=$l&amp;editor_language=$editor_language\" class=\"btn_default\">New sub category</a>
		</p>

		<!-- Sub categories -->
			<div class=\"vertical\">
				<ul>
				";
				// Get all categories
				$query = "SELECT sub_category_id, sub_category_title FROM $t_downloads_sub_categories WHERE sub_category_parent_id=$get_current_main_category_id ORDER BY sub_category_title ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_sub_category_id, $get_sub_category_title) = $row;

					echo"			";
					echo"<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=open_sub_category&amp;main_category_id=$get_current_main_category_id&amp;sub_category_id=$get_sub_category_id&amp;l=$l&amp;editor_language=$editor_language\">$get_sub_category_title</a></li>\n";
				}
				echo"
				</ul>
			</div>
		<!-- //Sub categories -->
		";

	} // main category found

} // action == open_category
elseif($action == "edit_category"){
	$main_category_id_mysql = quote_smart($link, $main_category_id);
	$query = "SELECT main_category_id, main_category_title, main_category_icon_path, main_category_icon_file FROM $t_downloads_main_categories WHERE main_category_id=$main_category_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_icon_path, $get_current_main_category_icon_file) = $row;

	if($get_current_main_category_id == ""){
		echo"<p>Not found</p>";
	}
	else{
		if($process == "1"){
			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);
			if(empty($inp_title)){
				echo"No title";die;
			}

			$inp_title_clean = clean($inp_title);
			$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

			// Update
			mysqli_query($link, "UPDATE $t_downloads_main_categories SET main_category_title=$inp_title_mysql, main_category_title_clean=$inp_title_clean_mysql WHERE main_category_id=$get_current_main_category_id") or die(mysqli_error($link));

		

			// Translations
			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;
	
				$inp_value = $_POST["inp_title_$get_language_active_iso_two"];
				$inp_value = output_html($inp_value);
				$inp_value_mysql = quote_smart($link, $inp_value);

				$inp_l_mysql = quote_smart($link, $get_language_active_iso_two);

				mysqli_query($link, "UPDATE $t_downloads_main_categories_translations SET main_category_translation_value=$inp_value_mysql WHERE
				 main_category_id=$get_current_main_category_id AND  main_category_translation_language=$inp_l_mysql") or die(mysqli_error($link));

			}

			// Dir exists?
			if(!(is_dir("../_zipped/_icons"))){
				mkdir("../_zipped/_icons");
			}

			// Icon
			$ft_image = "";
			$fm_image = "";
		
			// Get extention
			function getExtension($str) {
				$i = strrpos($str,".");
				if (!$i) { return ""; } 
				$l = strlen($str) - $i;
				$ext = substr($str,$i+1,$l);
				return $ext;
			}

			$image = $_FILES['inp_image']['name'];
				
			$filename = stripslashes($_FILES['inp_image']['name']);
			$extension = getExtension($filename);
			$extension = strtolower($extension);

			if($image){
				if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
					$ft_image = "warning";
					$fm_image = "unknown_file_format";
				}
				else{
					$tmp_name = $_FILES['inp_image']['tmp_name'];
					$full_dest  = "../_zipped/_icons/" . $get_current_main_category_id . "." . $extension;

  					if (move_uploaded_file($tmp_name, $full_dest)){

						list($width,$height) = @getimagesize("$full_dest");

						if($width == "" OR $height == ""){
	
							$ft_image = "warning";
							$fm_image = "photo_could_not_be_uploaded_please_check_file_size";
							unlink("$full_dest");

						}
						else{
							// Update image
							$inp_icon_file = $get_current_main_category_id . "." . $extension;
							$inp_icon_file_mysql = quote_smart($link, $inp_icon_file);

							$inp_icon_path = "_zipped/_icons";
							$inp_icon_path_mysql = quote_smart($link, $inp_icon_path);
						
							mysqli_query($link, "UPDATE $t_downloads_main_categories SET main_category_icon_path=$inp_icon_path_mysql, main_category_icon_file=$inp_icon_file_mysql WHERE main_category_id=$get_current_main_category_id") or die(mysqli_error($link));
						

						}  // if($width == "" OR $height == ""){
					}
				}
			} // if($image){
			else{
				switch ($_FILES['inp_image']['error']) {
					case UPLOAD_ERR_OK:
						$fm_image = "photo_unknown_error";
						break;
					case UPLOAD_ERR_NO_FILE:
           					$fm_image = "no_file_selected";
						break;
					case UPLOAD_ERR_INI_SIZE:
           					$fm_image = "photo_exceeds_filesize";
						break;
					case UPLOAD_ERR_FORM_SIZE:
           					$fm_image = "photo_exceeds_filesize_form";
						break;
					default:
           					$fm_image = "unknown_upload_error";
						break;
					}
				if(isset($fm) && $fm != ""){
					$ft_image = "warning";
				}
			}

			// Send success
			$url = "index.php?open=$open&page=$page&action=$action&main_category_id=$get_current_main_category_id&editor_language=$editor_language&ft=success&fm=category_updated&ft_image=$ft_image&fm_image=$fm_image";
			header("Location: $url");
			exit;
		}

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
			<h1>$get_current_main_category_title</h1>../$get_current_main_category_icon_path/$get_current_main_category_icon_file
			";
		}
		echo"

		<!-- Where am I ? -->
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l&amp;editor_language=$editor_language\">Downloads</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_category&amp;main_category_id=$main_category_id&amp;l=$l&amp;editor_language=$editor_language\">$get_current_main_category_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;main_category_id=$main_category_id&amp;l=$l&amp;editor_language=$editor_language\">Edit category</a>
			</p>
		<!-- //Where am I ? -->




		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "unknown_file_format"){
					$fm = "$l_unknown_file_format";
				}
				elseif($fm == "image_could_not_be_uploaded_please_check_file_size"){
					$fm = "$l_image_could_not_be_uploaded_please_check_file_size";
				}
				elseif($fm == "photo_unknown_error"){
					$fm = "$l_photo_unknown_error";
				}
				elseif($fm == "no_file_selected"){
					$fm = "$l_no_file_selected";
				}
				elseif($fm == "photo_exceeds_filesize"){
					$fm = "$l_photo_exceeds_filesize";
				}
				elseif($fm == "photo_exceeds_filesize_form"){
					$fm = "$l_photo_exceeds_filesize_form";
				}
				elseif($fm == "unknown_upload_error"){
					$fm = "$l_unknown_upload_error";
				}
				elseif($fm == "category_created"){
					$fm = "Category created";
				}
				else{
					$fm = "$ft";
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->
		
		<!-- Edit category form -->
			
			<!-- Focus -->
			<script>
				\$(document).ready(function(){
					\$('[name=\"inp_title\"]').focus();
				});
			</script>
			<!-- //Focus -->

			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;main_category_id=$main_category_id&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>Title:</b><br />
			<input type=\"text\" name=\"inp_title\" value=\"$get_current_main_category_title\" size=\"25\" />
			</p>

			<p>New icon 48x48:<br />
			<input name=\"inp_image\" type=\"file\" tabindex=\"1\" />
			</p>
			";

			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;

				$flag = $get_language_active_flag . "_16x16.png";

				$inp_l_mysql = quote_smart($link, $get_language_active_iso_two);
				$query_t = "SELECT main_category_translation_id, main_category_translation_value FROM $t_downloads_main_categories_translations WHERE main_category_id='$get_current_main_category_id' AND main_category_translation_language=$inp_l_mysql";
				$result_t = mysqli_query($link, $query_t);
				$row_t = mysqli_fetch_row($result_t);
				list($get_main_category_translation_id, $get_main_category_translation_value) = $row_t;

				if($get_main_category_translation_id == ""){
					mysqli_query($link, "INSERT INTO $t_downloads_main_categories_translations
					(main_category_translation_id, main_category_id, main_category_translation_language, main_category_translation_value) 
					VALUES 
					(NULL, '$get_current_main_category_id', $inp_l_mysql, '')")
					or die(mysqli_error($link));
				}

				echo"
				<p><img src=\"_design/gfx/flags/16x16/$flag\" alt=\"$get_language_active_flag\" /> $get_language_active_name<br />
				<input type=\"text\" name=\"inp_title_$get_language_active_iso_two\" value=\"$get_main_category_translation_value\" size=\"25\" />
				</p>
				";
			}
			echo"

			<p>
			<input type=\"submit\" value=\"Save\" class=\"btn_default\" />
			</p>

			</form>
		<!-- //Edit category form -->

		<!-- Actions -->
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/16x16/go-previous.png\" alt=\"go-previous.png\" /></a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l&amp;editor_language=$editor_language\">Categories</a>
			
			</p>
		<!-- //Actions -->

		";
	} // main category found

} // action == edit_category
elseif($action == "delete_category"){
	$main_category_id_mysql = quote_smart($link, $main_category_id);
	$query = "SELECT main_category_id, main_category_title, main_category_icon_path, main_category_icon_file FROM $t_downloads_main_categories WHERE main_category_id=$main_category_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_icon_path, $get_current_main_category_icon_file) = $row;

	if($get_current_main_category_id == ""){
		echo"<p>Not found</p>";
	}
	else{
		if($process == "1"){
			mysqli_query($link, "DELETE FROM $t_downloads_main_categories WHERE main_category_id=$get_current_main_category_id") or die(mysqli_error($link));
			mysqli_query($link, "DELETE FROM $t_downloads_main_categories_translations WHERE main_category_id=$get_current_main_category_id") or die(mysqli_error($link));

			if(file_exists("../$get_current_main_category_icon_path/$get_current_main_category_icon_file")){
				unlink("../$get_current_main_category_icon_path/$get_current_main_category_icon_file");
			}
			// Send success
			$url = "index.php?open=$open&page=$page&editor_language=$editor_language&ft=success&fm=category_deleted";
			header("Location: $url");
			exit;
		}


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
			<p>
			Icon <a href=\"../$get_current_main_category_icon_path/$get_current_main_category_icon_file\">../$get_current_main_category_icon_path/$get_current_main_category_icon_file</a>
			doesnt exists.
			</p>
			";
		}
		echo"

		<!-- Where am I ? -->
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l&amp;editor_language=$editor_language\">Downloads</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_category&amp;main_category_id=$main_category_id&amp;l=$l&amp;editor_language=$editor_language\">$get_current_main_category_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_category&amp;main_category_id=$main_category_id&amp;l=$l&amp;editor_language=$editor_language\">Delete category</a>
			</p>
		<!-- //Where am I ? -->


		<!-- Delete -->
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_category&amp;main_category_id=$main_category_id&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" class=\"btn_danger\">Confirm delete category</a>
			</p>
		<!-- //Delete -->

		";
	} // main category found

} // action == delete_category
elseif($action == "new_category"){
	if($process == "1"){
		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);
		if(empty($inp_title)){
			echo"No title";die;
		}


		$inp_title_clean = clean($inp_title);
		$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

		$inp_icon_path = "_zipped/_icons";
		$inp_icon_path_mysql = quote_smart($link, $inp_icon_path);

		$datetime = date("Y-m-d H:i:s");

		// Insert
		mysqli_query($link, "INSERT INTO $t_downloads_main_categories
		(main_category_id, main_category_title, main_category_title_clean, main_category_icon_path, main_category_created) 
		VALUES 
		(NULL, $inp_title_mysql, $inp_title_clean_mysql, $inp_icon_path_mysql, '$datetime')")
		or die(mysqli_error($link));

		// Get ID
		$query = "SELECT main_category_id FROM $t_downloads_main_categories WHERE main_category_created='$datetime'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_main_category_id) = $row;

		// Translations
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;
	
			$inp_l_mysql = quote_smart($link, $get_language_active_iso_two);

			mysqli_query($link, "INSERT INTO $t_downloads_main_categories_translations
			(main_category_translation_id, main_category_id, main_category_translation_language, main_category_translation_value) 
			VALUES 
			(NULL, '$get_main_category_id', $inp_l_mysql, $inp_title_mysql)")
			or die(mysqli_error($link));

		}

		// Icon
		$ft_image = "";
		$fm_image = "";
		
		// Create folders
		if(!(is_dir("../_uploads/"))){
			mkdir("../_uploads/", 0777);
		}
		if(!(is_dir("../_zipped/"))){
			mkdir("../_zipped/", 0777);
		}
		if(!(is_dir("../_zipped/_icons"))){
			mkdir("../_zipped/_icons", 0777);
		}


		// Get extention
		function getExtension($str) {
			$i = strrpos($str,".");
			if (!$i) { return ""; } 
			$l = strlen($str) - $i;
			$ext = substr($str,$i+1,$l);
			return $ext;
		}

		$image = $_FILES['inp_image']['name'];
				
		$filename = stripslashes($_FILES['inp_image']['name']);
		$extension = getExtension($filename);
		$extension = strtolower($extension);

		if($image){

			if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
				$ft_image = "warning";
				$fm_image = "unknown_file_format";
			}
			else{

				$tmp_name = $_FILES['inp_image']['tmp_name'];
				$full_dest  = "../_zipped/_icons/" . $get_main_category_id . "." . $extension;

  				if (move_uploaded_file($tmp_name, $full_dest)){


					list($width,$height) = @getimagesize("$full_dest");

					if($width == "" OR $height == ""){
	
						$ft_image = "warning";
						$fm_image = "photo_could_not_be_uploaded_please_check_file_size";
						unlink("$full_dest");
					}
					else{
					
						// Update image
						$inp_icon_file = $get_main_category_id . "." . $extension;
						$inp_icon_file_mysql = quote_smart($link, $inp_icon_file);

						mysqli_query($link, "UPDATE $t_downloads_main_categories SET main_category_icon_file=$inp_icon_file_mysql WHERE main_category_id=$get_main_category_id") or die(mysqli_error($link));
					} // if($width == "" OR $height == ""){
				}  // if move_file
			}
		} // if($image){
		else{
			switch ($_FILES['inp_image']['error']) {
				case UPLOAD_ERR_OK:
					$fm_image = "photo_unknown_error";
					break;
				case UPLOAD_ERR_NO_FILE:
           				$fm_image = "no_file_selected";
					break;
				case UPLOAD_ERR_INI_SIZE:
           				$fm_image = "photo_exceeds_filesize";
					break;
				case UPLOAD_ERR_FORM_SIZE:
           				$fm_image = "photo_exceeds_filesize_form";
					break;
				default:
           				$fm_image = "unknown_upload_error";
					break;
				}
			if(isset($fm) && $fm != ""){
				$ft_image = "warning";
			}
		}

		// Send success
		$url = "index.php?open=$open&page=$page&action=$action&editor_language=$editor_language&ft=success&fm=category_created&ft_image=$ft_image&fm_image=$fm_image";
		header("Location: $url");
		exit;
	}



	echo"
	<h1>New category</h1>

	<!-- Where am I ? -->
		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l&amp;editor_language=$editor_language\">Downloads</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_category&amp;l=$l&amp;editor_language=$editor_language\">New category</a>
		</p>
	<!-- //Where am I ? -->



		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "category_created"){
					$fm = "Category created";
				}
				else{
					$fm = "$ft";
				}

				if(isset($_GET['image_fm'])) {
					$image_fm = $_GET['image_fm'];
					$image_fm = strip_tags(stripslashes($image_fm));
					$fm = "$fm</p><p>$image_fm";

				}

				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->
		
	<!-- New category form -->
			
		<!-- Focus -->
		<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
		</script>
		<!-- //Focus -->

		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

		<p><b>Title:</b><br />
		<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
		</p>

		<p>Icon 48x48:<br />
		<input name=\"inp_image\" type=\"file\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" /><br />
		<a href=\"https://www.google.com/search?q=php&amp;client=firefox-b&amp;biw=1680&amp;bih=914&amp;tbs=ift:png,isz:ex,iszw:48,iszh:48&amp;tbm=isch&amp;source=lnt\" target=\"_blank\">Google</a>
		</p>


		<p>
		<input type=\"submit\" value=\"Save\" class=\"btn_default\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
		</p>

		</form>
	<!-- //New category form -->
	";
} // action == new category
elseif($action == "new_sub_category"){
	$main_category_id_mysql = quote_smart($link, $main_category_id);
	$query = "SELECT main_category_id, main_category_title, main_category_icon_path, main_category_icon_file FROM $t_downloads_main_categories WHERE main_category_id=$main_category_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_icon_path, $get_current_main_category_icon_file) = $row;

	if($get_current_main_category_id == ""){
		echo"<p>Not found</p>";
	}
	else{
		if($process == "1"){
			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);
			if(empty($inp_title)){
				echo"No title";die;
			}

			$inp_title_clean = clean($inp_title);
			$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

			// Create 
			$datetime = date("Y-m-d H:i:s");

			// Insert
			mysqli_query($link, "INSERT INTO $t_downloads_sub_categories
			(sub_category_id, sub_category_parent_id, sub_category_title, sub_category_title_clean, sub_category_created) 
			VALUES 
			(NULL, $get_current_main_category_id, $inp_title_mysql, $inp_title_clean_mysql, '$datetime')")
			or die(mysqli_error($link));

			// Get ID
			$query = "SELECT sub_category_id FROM $t_downloads_sub_categories WHERE sub_category_created='$datetime'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_sub_category_id) = $row;

			// Translations
			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;
	
				$inp_l_mysql = quote_smart($link, $get_language_active_iso_two);

				$inp_value = $_POST["inp_title_$get_language_active_iso_two"];
				$inp_value = output_html($inp_value);
				$inp_value_mysql = quote_smart($link, $inp_value);


				mysqli_query($link, "INSERT INTO $t_downloads_sub_categories_translations
				(sub_category_translation_id, sub_category_id, sub_category_translation_language, sub_category_translation_value) 
				VALUES 
				(NULL, '$get_sub_category_id', $inp_l_mysql, $inp_value_mysql)")
				or die(mysqli_error($link));

			}


			// Send success
			$url = "index.php?open=$open&page=$page&action=$action&main_category_id=$get_current_main_category_id&editor_language=$editor_language&ft=success&fm=sub_category_created";
			header("Location: $url");
			exit;
		}

		echo"
		<h1>New sub category</h1>
		

		<!-- Where am I ? -->
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l&amp;editor_language=$editor_language\">Downloads</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_category&amp;main_category_id=$main_category_id&amp;l=$l&amp;editor_language=$editor_language\">$get_current_main_category_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;main_category_id=$main_category_id&amp;l=$l&amp;editor_language=$editor_language\">New sub category</a>
			</p>
		<!-- //Where am I ? -->




		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "category_created"){
					$fm = "Category created";
				}
				else{
					$fm = "$ft";
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->
		
		<!-- New sub category form -->
			
			<!-- Focus -->
			<script>
				\$(document).ready(function(){
					\$('[name=\"inp_title\"]').focus();
				});
			</script>
			<!-- //Focus -->

			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;main_category_id=$main_category_id&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>Title:</b><br />
			<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" />
			</p>
			";

			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;

				$flag = $get_language_active_flag . "_16x16.png";

				echo"
				<p><img src=\"_design/gfx/flags/16x16/$flag\" alt=\"$get_language_active_flag\" /> $get_language_active_name<br />
				<input type=\"text\" name=\"inp_title_$get_language_active_iso_two\" value=\"\" size=\"25\" />
				</p>
				";
			}
			echo"

			<p>
			<input type=\"submit\" value=\"Create sub category\" class=\"btn_default\" />
			</p>

			</form>
		<!-- //Edit category form -->

		<!-- Actions -->
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/16x16/go-previous.png\" alt=\"go-previous.png\" /></a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l&amp;editor_language=$editor_language\">Categories</a>
			</p>
		<!-- //Actions -->

		";
	} // main category found

} // action == new sub category
elseif($action == "open_sub_category"){
	$main_category_id_mysql = quote_smart($link, $main_category_id);
	$query = "SELECT main_category_id, main_category_title, main_category_icon_path, main_category_icon_file FROM $t_downloads_main_categories WHERE main_category_id=$main_category_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_icon_path, $get_current_main_category_icon_file) = $row;

	if($get_current_main_category_id == ""){
		echo"<p>Not found</p>";
	}
	else{
		// Sub category
		$sub_category_id_mysql = quote_smart($link, $sub_category_id);
		$query = "SELECT sub_category_id, sub_category_title FROM $t_downloads_sub_categories WHERE sub_category_id=$sub_category_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
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
					<h1>$get_sub_category_title</h1>
				  </td>
				 </tr>
				</table>
				";
			}
			else{
				echo"
				<h1>$get_sub_category_title</h1>
				";
			}
			echo"

			<!-- Where am I ? -->
				<p>
				<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l&amp;editor_language=$editor_language\">Downloads</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_category&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;l=$l&amp;editor_language=$editor_language\">$get_current_main_category_title</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;l=$l&amp;editor_language=$editor_language\">$get_sub_category_title</a>
				</p>
			<!-- //Where am I ? -->


			<!-- Actions -->
				<p>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_sub_category&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;l=$l&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/16x16/edit.png\" alt=\"edit.png\" /></a>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_sub_category&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;l=$l&amp;editor_language=$editor_language\">Edit sub category</a>
				&nbsp; &nbsp; 
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_sub_category&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;l=$l&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/16x16/delete.png\" alt=\"delete.png\" /></a>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_sub_category&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;l=$l&amp;editor_language=$editor_language\">Delete sub category</a>
				</p>
			<!-- //Actions -->


		
			";
		} // sub category found
	} // main category found
} // action == open_sub_category
elseif($action == "edit_sub_category"){
	$main_category_id_mysql = quote_smart($link, $main_category_id);
	$query = "SELECT main_category_id, main_category_title, main_category_icon_path, main_category_icon_file FROM $t_downloads_main_categories WHERE main_category_id=$main_category_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_icon_path, $get_current_main_category_icon_file) = $row;

	if($get_current_main_category_id == ""){
		echo"<p>Not found</p>";
	}
	else{
		// Sub category
		$sub_category_id_mysql = quote_smart($link, $sub_category_id);
		$query = "SELECT sub_category_id, sub_category_title FROM $t_downloads_sub_categories WHERE sub_category_id=$sub_category_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_sub_category_id, $get_current_sub_category_title) = $row;

		if($get_current_sub_category_id == ""){
			echo"<p>Not found</p>";
		}
		else{

			if($process == "1"){
				$inp_title = $_POST['inp_title'];
				$inp_title = output_html($inp_title);
				$inp_title_mysql = quote_smart($link, $inp_title);
				if(empty($inp_title)){
					echo"No title";die;
				}

				$inp_title_clean = clean($inp_title);
				$inp_title_clean_mysql = quote_smart($link, $inp_title_clean);

				// Create 
				$datetime = date("Y-m-d H:i:s");

				// Insert
				mysqli_query($link, "UPDATE $t_downloads_sub_categories SET sub_category_title=$inp_title_mysql, sub_category_title_clean=$inp_title_clean_mysql WHERE sub_category_id=$get_current_sub_category_id")
				or die(mysqli_error($link));


				// Translations
				$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;
	
					$inp_l_mysql = quote_smart($link, $get_language_active_iso_two);
	
					$inp_value = $_POST["inp_title_$get_language_active_iso_two"];
					$inp_value = output_html($inp_value);
					$inp_value_mysql = quote_smart($link, $inp_value);


					mysqli_query($link, "UPDATE $t_downloads_sub_categories_translations SET sub_category_translation_value=$inp_value_mysql WHERE sub_category_id=$get_current_sub_category_id AND
								sub_category_translation_language=$inp_l_mysql")
					or die(mysqli_error($link));

				}

				// Send success
				$url = "index.php?open=$open&page=$page&action=$action&main_category_id=$get_current_main_category_id&sub_category_id=$sub_category_id&editor_language=$editor_language&ft=success&fm=changes_saved";
				header("Location: $url");
				exit;
			}


			// Headline
			if(file_exists("../$get_current_main_category_icon_path/$get_current_main_category_icon_file")){
				echo"
				<table>
				 <tr>
				  <td style=\"padding-right: 10px;\">
					<img src=\"../$get_current_main_category_icon_path/$get_current_main_category_icon_file\" alt=\"$get_current_main_category_icon_path/$get_current_main_category_icon_file\" />
				  </td>
				  <td>
					<h1>$get_current_sub_category_title</h1>
				  </td>
				 </tr>
				</table>
				";
			}
			else{
				echo"
				<h1>$get_current_sub_category_title</h1>
				";
			}
			echo"

			<!-- Where am I ? -->
				<p>
				<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l&amp;editor_language=$editor_language\">Downloads</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_category&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;l=$l&amp;editor_language=$editor_language\">$get_current_main_category_title</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_sub_category&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;l=$l&amp;editor_language=$editor_language\">$get_current_sub_category_title</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_sub_category&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;l=$l&amp;editor_language=$editor_language\">Edit sub category</a>
				</p>
			<!-- //Where am I ? -->



			<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "changes_saves"){
					$fm = "Changes saved";
				}
				else{
					$fm = "$ft";
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
			<!-- //Feedback -->
		
			<!-- Edit sub category form -->
			
				<!-- Focus -->
				<script>
					\$(document).ready(function(){
						\$('[name=\"inp_title\"]').focus();
					});
				</script>
				<!-- //Focus -->

				<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

				<p><b>Title:</b><br />
				<input type=\"text\" name=\"inp_title\" value=\"$get_current_sub_category_title\" size=\"25\" />
				</p>
				";

				$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;

					$flag = $get_language_active_flag . "_16x16.png";
	
					$inp_l_mysql = quote_smart($link, $get_language_active_iso_two);

					// Get translation
					$query_t = "SELECT sub_category_id, sub_category_translation_value FROM $t_downloads_sub_categories_translations WHERE sub_category_id='$get_current_sub_category_id' AND sub_category_translation_language=$inp_l_mysql";
					$result_t = mysqli_query($link, $query_t);
					$row_t = mysqli_fetch_row($result_t);
					list($get_sub_category_id, $get_sub_category_translation_value) = $row_t;

					if($get_sub_category_id == ""){
						// Create it
						
						mysqli_query($link, "INSERT INTO $t_downloads_sub_categories_translations
						(sub_category_translation_id, sub_category_id, sub_category_translation_language, sub_category_translation_value) 
						VALUES 
						(NULL, '$get_current_sub_category_id', $inp_l_mysql, '')")
						or die(mysqli_error($link));
					}

					echo"
					<p><img src=\"_design/gfx/flags/16x16/$flag\" alt=\"$get_language_active_flag\" /> $get_language_active_name<br />
					<input type=\"text\" name=\"inp_title_$get_language_active_iso_two\" value=\"$get_sub_category_translation_value\" size=\"25\" />
					</p>
					";
				}
				echo"

				<p>
				<input type=\"submit\" value=\"Save changes\" class=\"btn_default\" />
				</p>

				</form>
			<!-- //Edit sub category form -->

			<!-- Actions -->
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/16x16/go-previous.png\" alt=\"go-previous.png\" /></a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l&amp;editor_language=$editor_language\">Categories</a>
			</p>
			<!-- //Actions -->
		
			";
		} // sub category found
	} // main category found
} // action == edit_sub_category
elseif($action == "delete_sub_category"){
	$main_category_id_mysql = quote_smart($link, $main_category_id);
	$query = "SELECT main_category_id, main_category_title, main_category_icon_path, main_category_icon_file FROM $t_downloads_main_categories WHERE main_category_id=$main_category_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_icon_path, $get_current_main_category_icon_file) = $row;

	if($get_current_main_category_id == ""){
		echo"<p>Not found</p>";
	}
	else{
		// Sub category
		$sub_category_id_mysql = quote_smart($link, $sub_category_id);
		$query = "SELECT sub_category_id, sub_category_title FROM $t_downloads_sub_categories WHERE sub_category_id=$sub_category_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_sub_category_id, $get_current_sub_category_title) = $row;

		if($get_current_sub_category_id == ""){
			echo"<p>Not found</p>";
		}
		else{

			if($process == "1"){
				// Delete
				mysqli_query($link, "DELETE FROM $t_downloads_sub_categories WHERE sub_category_id=$get_current_sub_category_id")or die(mysqli_error($link));
				mysqli_query($link, "DELETE FROM $t_downloads_sub_categories_translations WHERE sub_category_id=$get_current_sub_category_id") or die(mysqli_error($link));

			

				// Send success
				$url = "index.php?open=$open&page=$page&action=open_category&main_category_id=$get_current_main_category_id&editor_language=$editor_language&ft=success&fm=sub_category_deleted";
				header("Location: $url");
				exit;
			}


			// Headline
			if(file_exists("../$get_current_main_category_icon_path/$get_current_main_category_icon_file")){
				echo"
				<table>
				 <tr>
				  <td style=\"padding-right: 10px;\">
					<img src=\"../$get_current_main_category_icon_path/$get_current_main_category_icon_file\" alt=\"$get_current_main_category_icon_path/$get_current_main_category_icon_file\" />
				  </td>
				  <td>
					<h1>$get_current_sub_category_title</h1>
				  </td>
				 </tr>
				</table>
				";
			}
			else{
				echo"
				<h1>$get_current_sub_category_title</h1>
				";
			}
			echo"

			<!-- Where am I ? -->
				<p>
				<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l&amp;editor_language=$editor_language\">Downloads</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_category&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;l=$l&amp;editor_language=$editor_language\">$get_current_main_category_title</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_sub_category&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;l=$l&amp;editor_language=$editor_language\">$get_current_sub_category_title</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_sub_category&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;l=$l&amp;editor_language=$editor_language\">Delete sub category</a>
				</p>
			<!-- //Where am I ? -->



			<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "changes_saves"){
					$fm = "Changes saved";
				}
				else{
					$fm = "$ft";
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
			<!-- //Feedback -->
		
			<!-- Delete sub category form -->
			
				<p>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;main_category_id=$main_category_id&amp;sub_category_id=$sub_category_id&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" class=\"btn_danger\">Confirm delete</a>
				</p>
			<!-- //Delete sub category form -->

			<!-- Actions -->
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l&amp;editor_language=$editor_language\"><img src=\"_design/gfx/icons/16x16/go-previous.png\" alt=\"go-previous.png\" /></a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l&amp;editor_language=$editor_language\">Categories</a>
			</p>
			<!-- //Actions -->
		
			";
		} // sub category found
	} // main category found
} // action == edit_sub_category
?>