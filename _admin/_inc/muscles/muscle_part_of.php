<?php
/**
*
* File: _admin/_inc/exercies/muscle_part_of.php
* Version 00.28 20.03.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ----------------------------------------------------------------------------- */
$t_muscles				= $mysqlPrefixSav . "muscles";
$t_muscles_translations 		= $mysqlPrefixSav . "muscles_translations";
$t_muscle_groups 			= $mysqlPrefixSav . "muscle_groups";
$t_muscle_groups_translations	 	= $mysqlPrefixSav . "muscle_groups_translations";
$t_muscle_part_of 			= $mysqlPrefixSav . "muscle_part_of";
$t_muscle_part_of_translations	 	= $mysqlPrefixSav . "muscle_part_of_translations";

/*- Get extention ---------------------------------------------------------------------- */
function getExtension($str) {
		$i = strrpos($str,".");
		if (!$i) { return ""; } 
		$l = strlen($str) - $i;
		$ext = substr($str,$i+1,$l);
		return $ext;
}
/*- Variables -------------------------------------------------------------------------- */
$editor_language_mysql = quote_smart($link, $editor_language);

if(isset($_GET['id'])){
	$id = $_GET['id'];
	$id = strip_tags(stripslashes($id));
}
else{
	$id = "";
}

if($action == ""){
	echo"
	<h1>Muscle part of</h1>


	<p>
	<a href=\"index.php?open=$open&amp;page=$page&amp;action=new&amp;editor_language=$editor_language\">New</a>
	</p>
	
	<!-- Main categories -->
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span><b>Name</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Actions</b></span>
		  </td>
		  </tr>
		 </thead>";
		// Get all main
		$query = "SELECT muscle_part_of_id, muscle_part_of_latin_name, muscle_part_of_latin_name_clean, muscle_part_of_name, muscle_part_of_name_clean, muscle_part_of_muscle_group_id_main, muscle_part_of_muscle_group_id_sub, muscle_part_of_image_path, muscle_part_of_image_file FROM $t_muscle_part_of";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_muscle_part_of_id, $get_muscle_part_of_latin_name, $get_muscle_part_of_latin_name_clean, $get_muscle_part_of_name, $get_muscle_part_of_name_clean, $get_muscle_part_of_muscle_group_id_main, $get_muscle_part_of_muscle_group_id_sub, $get_muscle_part_of_image_path, $get_muscle_part_of_image_file) = $row;
				
			// Style
			if(isset($style) && $style == ""){
				$style = "odd";
			}
			else{
				$style = "";
			}
				
	
			echo"
			 <tr>
			  <td class=\"$style\">
				<span>$get_muscle_part_of_name</span>
			  </td>
			  <td class=\"$style\">
				<span>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit&amp;id=$get_muscle_part_of_id&amp;editor_language=$editor_language\">Edit</a>
				|
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete&amp;id=$get_muscle_part_of_id&amp;editor_language=$editor_language\">Delete</a>
				</span>
			  </td>
			 </tr>";
		}

		echo"
		</table>
	<!-- //Main categories -->
	";
}
elseif($action == "edit"){
	$id_mysql = quote_smart($link, $id);
	$query = "SELECT muscle_part_of_id, muscle_part_of_latin_name, muscle_part_of_latin_name_clean, muscle_part_of_name, muscle_part_of_name_clean, muscle_part_of_muscle_group_id_main, muscle_part_of_muscle_group_id_sub, muscle_part_of_image_path, muscle_part_of_image_file FROM $t_muscle_part_of WHERE muscle_part_of_id=$id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_muscle_part_of_id, $get_muscle_part_of_latin_name, $get_muscle_part_of_latin_name_clean, $get_muscle_part_of_name, $get_muscle_part_of_name_clean, $get_muscle_part_of_muscle_group_id_main, $get_muscle_part_of_muscle_group_id_sub, $get_muscle_part_of_image_path, $get_muscle_part_of_image_file) = $row;

	if($get_muscle_part_of_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>Not found.</p>

		<p><a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Back</a></p>
		";
	}
	else{
		// Get translation
		$query_translation = "SELECT muscle_part_of_translation_id, muscle_part_of_translation_name, muscle_part_of_translation_text FROM $t_muscle_part_of_translations WHERE muscle_part_of_translation_muscle_part_of_id='$get_muscle_part_of_id' AND muscle_part_of_translation_language=$editor_language_mysql";
		$result_translation = mysqli_query($link, $query_translation);
		$row_translation = mysqli_fetch_row($result_translation);
		list($get_muscle_part_of_translation_id, $get_muscle_part_of_translation_name, $get_muscle_part_of_translation_text) = $row_translation;
		if($get_muscle_part_of_translation_id == ""){
			mysqli_query($link, "INSERT INTO $t_muscle_part_of_translations
			(muscle_part_of_translation_id, muscle_part_of_translation_muscle_part_of_id, muscle_part_of_translation_language, muscle_part_of_translation_name, muscle_part_of_translation_text) 
			VALUES 
			(NULL, 	'$get_muscle_part_of_id', $editor_language_mysql, '$get_muscle_part_of_name', '')")
			or die(mysqli_error($link));
			echo"<div class=\"info\"><span>L O A D I N G</span></div>";
			echo"
			<meta http-equiv=\"refresh\" content=\"1;URL='index.php?open=$open&amp;page=$page&amp;action=$action&amp;id=$id&amp;editor_language=$editor_language&amp;l=$l'\" />
			";
		}

		

		if($process == "1"){
			$inp_latin_name = $_POST['inp_latin_name'];
			$inp_latin_name = output_html($inp_latin_name);
			$inp_latin_name_mysql = quote_smart($link, $inp_latin_name);
			if(empty($inp_latin_name)){
				$url = "index.php?open=$open&page=$page&action=$action&id=$id&ft=error&fm=missing_latin_name&editor_language=$editor_language";
				header("Location: $url");
				exit;
			}

			$inp_latin_name_clean = clean($inp_latin_name);
			$inp_latin_name_clean_mysql = quote_smart($link, $inp_latin_name_clean);

			$inp_name = $_POST['inp_name'];
			$inp_name = output_html($inp_name);
			$inp_name_mysql = quote_smart($link, $inp_name);

			$inp_name_clean = clean($inp_name);
			$inp_name_clean_mysql = quote_smart($link, $inp_name_clean);

			$inp_group_id_sub = $_POST['inp_group_id_sub'];
			$inp_group_id_sub = output_html($inp_group_id_sub);
			$inp_group_id_sub_mysql = quote_smart($link, $inp_group_id_sub);
			if(empty($inp_group_id_sub)){
				$url = "index.php?open=$open&page=$page&action=$action&id=$id&ft=error&fm=missing_group&editor_language=$editor_language";
				header("Location: $url");
				exit;
			}

			// Main
			$query = "SELECT muscle_group_parent_id, muscle_group_name_clean FROM $t_muscle_groups WHERE muscle_group_id=$inp_group_id_sub_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($inp_group_id_main, $get_current_sub_muscle_group_name_clean) = $row;


			$query = "SELECT muscle_group_name_clean FROM $t_muscle_groups WHERE muscle_group_id='$inp_group_id_main'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_main_muscle_group_name_clean) = $row;


			$inp_group_id_main = output_html($inp_group_id_main);
			$inp_group_id_main_mysql = quote_smart($link, $inp_group_id_main);



			// Update
			$result = mysqli_query($link, "UPDATE $t_muscle_part_of SET muscle_part_of_latin_name=$inp_latin_name_mysql, muscle_part_of_latin_name_clean=$inp_latin_name_clean_mysql,
							muscle_part_of_name=$inp_name_mysql, muscle_part_of_name_clean=$inp_name_clean_mysql,
							muscle_part_of_muscle_group_id_main=$inp_group_id_main_mysql, muscle_part_of_muscle_group_id_sub=$inp_group_id_sub_mysql 
							WHERE muscle_part_of_id=$id_mysql") or die(mysqli_error());



			// Image?
			$name = stripslashes($_FILES['inp_image']['name']);
			$extension = getExtension($name);
			$extension = strtolower($extension);
	
			if($name){
				if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
					$ft_image = "warning";
					$fm_image = "unknown_file_extension";
				}
				else{
					// Folders exists?
					if(!(is_dir("../_uploads"))){
						mkdir("../_uploads");
					}
					if(!(is_dir("../_uploads/muscles"))){
						mkdir("../_uploads/muscles");
					}
					if(!(is_dir("../_uploads/muscles/$get_current_main_muscle_group_name_clean"))){
						mkdir("../_uploads/muscles/$get_current_main_muscle_group_name_clean");
					}

					if(!(is_dir("../_uploads/muscles/$get_current_main_muscle_group_name_clean/$get_current_sub_muscle_group_name_clean"))){
						mkdir("../_uploads/muscles/$get_current_main_muscle_group_name_clean/$get_current_sub_muscle_group_name_clean");
					}


 					// Give new name
					$new_name = $inp_latin_name_clean . ".png";
					$new_path = "../_uploads/muscles/$get_current_main_muscle_group_name_clean/$get_current_sub_muscle_group_name_clean/";
					$uploaded_file = $new_path . $new_name;

					// Upload file
					if (move_uploaded_file($_FILES['inp_image']['tmp_name'], $uploaded_file)) {


						// Get image size
						$file_size = filesize($uploaded_file);
 
						// Check with and height
						list($width,$height) = getimagesize($uploaded_file);
	
						if($width == "" OR $height == ""){
							$ft_image = "warning";
							$fm_image = "getimagesize_failed";
						}
						else{
							// Update MySQL
							$inp_image_path = "_uploads/muscles/$get_current_main_muscle_group_name_clean/$get_current_sub_muscle_group_name_clean";
							$inp_image_path_mysql = quote_smart($link, $inp_image_path);
						
							$inp_image_mysql = quote_smart($link, $new_name);
							$result = mysqli_query($link, "UPDATE $t_muscle_part_of SET muscle_part_of_image_path=$inp_image_path_mysql, muscle_part_of_image_file=$inp_image_mysql WHERE muscle_part_of_id=$id_mysql");
		
							$ft_image = "success";
							$fm_image = "image_uploaded";

						}  // if($width == "" OR $height == ""){
					} // move_uploaded_file
					else{	
						switch ($_FILES['inp_image']['error']) {
							case UPLOAD_ERR_OK:
								$ft_image = "error";
           							$fm_image = "image_to_big";
								break;
							case UPLOAD_ERR_NO_FILE:
           							// $fm_image = "no_file_uploaded";
								break;
							case UPLOAD_ERR_INI_SIZE:
								$ft_image = "error";
           							$fm_image = "to_big_size_in_configuration";
								break;
							case UPLOAD_ERR_FORM_SIZE:
								$ft_image = "error";
           							$fm_image = "to_big_size_in_form";
								break;
							default:
								$ft_image = "error";
           							$fm_image = "unknown_error";
								break;
						}	
					}
				} // extension check
			} // if($image){


			// Delete cache
			$filenames = "";
			$dir = "../_cache/";
			$dirLen = strlen($dir);
			$dp = @opendir($dir);

			while($file = @readdir($dp)) $filenames [] = $file;

			for ($i = 0; $i < count($filenames); $i++){
				$content = $filenames[$i];
				$file_path = "$dir$content";

				if($file_path != "$dir." && $file_path != "$dir.."){
					unlink("$file_path");
				}
			}


			// Translations
			$inp_translation_name = $_POST['inp_translation_name'];
			$inp_translation_name = output_html($inp_translation_name);
			$inp_translation_name_mysql = quote_smart($link, $inp_translation_name);

			$result = mysqli_query($link, "UPDATE $t_muscle_part_of_translations SET muscle_part_of_translation_name=$inp_translation_name_mysql WHERE muscle_part_of_translation_muscle_part_of_id='$get_muscle_part_of_id' AND muscle_part_of_translation_language=$editor_language_mysql");
		


			$inp_translation_text = $_POST['inp_translation_text'];
			$sql = "UPDATE $t_muscle_part_of_translations SET muscle_part_of_translation_text=? WHERE muscle_part_of_translation_muscle_part_of_id='$get_muscle_part_of_id' AND muscle_part_of_translation_language=$editor_language_mysql";
			$stmt = $link->prepare($sql);
			$stmt->bind_param("s", $inp_translation_text);
			$stmt->execute();
			if ($stmt->errno) {
				echo "FAILURE!!! " . $stmt->error; die;
			}
		


			// Send success
			$url = "index.php?open=$open&page=$page&action=$action&id=$id&ft=success&fm=changes_saved&editor_language=$editor_language";
			if(isset($ft_image)){
				$url = $url . "&ft_image=$ft_image&fm_image=$fm_image";
			}
			header("Location: $url");
			exit;

		}

		echo"
		<h1>$get_muscle_part_of_name</h1>

		<!-- Where am I? -->
			<p>
			<b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Main</a>
			&gt; 
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit&amp;id=$id&amp;editor_language=$editor_language\">Edit</a>
			</p>
		<!-- //Where am I? -->

		<!-- Menu -->
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=new&amp;editor_language=$editor_language\">New</a>
			</p>
		<!-- //Menu -->


	<!-- Select language -->

		<script>
		\$(function(){
			// bind change event to select
			\$('#inp_l').on('change', function () {
				var url = \$(this).val(); // get selected value
				if (url) { // require a URL
 					window.location = url; // redirect
				}
				return false;
			});
		});
		</script>

		<form method=\"get\" enctype=\"multipart/form-data\">
		<p>
		$l_language:
		<select id=\"inp_l\">
			<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">$l_editor_language</option>
			<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">-</option>\n";


			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;

				$flag_path 	= "_design/gfx/flags/16x16/$get_language_active_flag" . "_16x16.png";

				// No language selected?
				if($editor_language == ""){
						$editor_language = "$get_language_active_iso_two";
				}
				
				
				echo"	<option value=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;id=$id&amp;editor_language=$get_language_active_iso_two&amp;l=$l\" style=\"background: url('$flag_path') no-repeat;padding-left: 20px;\"";if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
			}
		echo"
		</select>
		</p>
		</form>
	<!-- //Select language -->


		<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "Changes saved";
				}
				else{
					$fm = ucfirst($fm);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
		echo"
		<!-- //Feedback -->

		<!-- Edit form -->
		<!-- TinyMCE -->
		<script type=\"text/javascript\" src=\"_javascripts/tinymce/tinymce_4.7.1/tinymce.min.js\"></script>
		<script>
		tinymce.init({
			selector: \"textarea\",  // change this value according to your HTML
			plugins: \"image\",
			menubar: \"insert\",
			toolbar: \"image\",
			height: 500,
			menubar: false,
			plugins: [
			    'advlist autolink lists link image charmap print preview anchor textcolor',
			    'searchreplace visualblocks code fullscreen',
			    'insertdatetime media table contextmenu paste code help'
			  ],
			  toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
			  content_css: [
			    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
			    '//www.tinymce.com/css/codepen.min.css']
		});
		</script>
		<!-- //TinyMCE -->


			<h2>Edit $get_muscle_part_of_name</h2>
			
			<!-- Focus -->
			<script>
				\$(document).ready(function(){
					\$('[name=\"inp_name\"]').focus();
				});
			</script>
			<!-- //Focus -->

			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=edit&amp;id=$id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">


			<p><b>Name:</b><br />
			<input type=\"text\" name=\"inp_name\" value=\"$get_muscle_part_of_name\" size=\"40\" />
			</p>

			<p><b>Name ($editor_language):</b><br />
			<input type=\"text\" name=\"inp_translation_name\" value=\"$get_muscle_part_of_translation_name\" size=\"40\" />
			</p>

			<p><b>Latin name:</b><br />
			<input type=\"text\" name=\"inp_latin_name\" value=\"$get_muscle_part_of_latin_name\" size=\"40\" />
			</p>

			<p><b>Part of group:</b><br />
			<select name=\"inp_group_id_sub\">\n";
			echo"					";
			echo"<option value=\"\">- Please select -</option>\n";
			// Get all main
			$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_parent_id='0'";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_muscle_group_id, $get_muscle_group_name, $get_muscle_group_name_clean, $get_muscle_group_parent_id, $get_muscle_group_image_path, $get_muscle_group_image_file) = $row;
			
				// Translation
				$query_translation = "SELECT muscle_group_translation_id,muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_muscle_group_id AND muscle_group_translation_language=$editor_language_mysql";
				$result_translation = mysqli_query($link, $query_translation);
				$row_translation = mysqli_fetch_row($result_translation);
				list($get_muscle_group_translation_id, $get_muscle_group_translation_name) = $row_translation;
				echo"					";
				echo"<option value=\"\"> </option>\n";
				echo"					";
				echo"<option value=\"$get_muscle_group_id\">$get_muscle_group_name</option>\n";

				// Get sub
				$query_sub = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_parent_id='$get_muscle_group_id'";
				$result_sub = mysqli_query($link, $query_sub);
				while($row_sub = mysqli_fetch_row($result_sub)) {
					list($get_sub_muscle_group_id, $get_sub_muscle_group_name, $get_sub_muscle_group_name_clean, $get_sub_muscle_group_parent_id, $get_sub_muscle_group_image_path, $get_sub_muscle_group_image_file) = $row_sub;
			
					// Translation
					$query_translation = "SELECT muscle_group_translation_id,muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_sub_muscle_group_id AND muscle_group_translation_language=$editor_language_mysql";
					$result_translation = mysqli_query($link, $query_translation);
					$row_translation = mysqli_fetch_row($result_translation);
					list($get_sub_muscle_group_translation_id, $get_sub_muscle_group_translation_name) = $row_translation;
					echo"					";
					echo"<option value=\"$get_sub_muscle_group_id\""; if($get_sub_muscle_group_id == "$get_muscle_part_of_muscle_group_id_sub"){ echo" selected=\"selected\""; } echo">&nbsp; &nbsp; $get_sub_muscle_group_translation_name</option>\n";


				}
			}
			
			echo"
			</select>
			</p>


			<p><b>Text ($editor_language):</b><br />
			<textarea name=\"inp_translation_text\" rows=\"10\" cols=\"70\">$get_muscle_part_of_translation_text</textarea>
			</p>

			<p><b>Image:</b><br />
			<img src=\"";
			if($get_muscle_part_of_image_file != "" && file_exists("../$get_muscle_part_of_image_path/$get_muscle_part_of_image_file")){
				echo"../image.php?image=/$get_muscle_part_of_image_path/$get_muscle_part_of_image_file";
			}
			else{
				echo"_design/gfx/no_thumb.png";
			}
			echo"\" alt=\"$get_muscle_part_of_image_file\" style=\"margin-bottom: 5px;\" />
			<br /><br />
			New image: <input type=\"file\" name=\"inp_image\" />
			</p>

			<p>
			<input type=\"submit\" value=\"Save\" class=\"btn\" />
			</p>

			</form>
		<!-- //Edit form -->
		";
	}
} // edit
elseif($action == "delete"){
	$id_mysql = quote_smart($link, $id);
	$query = "SELECT muscle_part_of_id, muscle_part_of_latin_name, muscle_part_of_latin_name_clean, muscle_part_of_name, muscle_part_of_name_clean, muscle_part_of_muscle_group_id_main, muscle_part_of_muscle_group_id_sub, muscle_part_of_image_path, muscle_part_of_image_file FROM $t_muscle_part_of WHERE muscle_part_of_id=$id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_muscle_part_of_id, $get_muscle_part_of_latin_name, $get_muscle_part_of_latin_name_clean, $get_muscle_part_of_name, $get_muscle_part_of_name_clean, $get_muscle_part_of_muscle_group_id_main, $get_muscle_part_of_muscle_group_id_sub, $get_muscle_part_of_image_path, $get_muscle_part_of_image_file) = $row;

	if($get_muscle_part_of_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>Not found.</p>

		<p><a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Back</a></p>
		";
	}
	else{
		if($process == "1"){

			// Delete
			$result = mysqli_query($link, "DELETE FROM $t_muscle_part_of WHERE muscle_part_of_id=$id_mysql") or die(mysqli_error());

			if($get_muscle_part_of_image_file != "" && file_exists("../$get_muscle_part_of_image_path/$get_muscle_part_of_image_file")){
				unlink("../$get_muscle_part_of_image_path/$get_muscle_part_of_image_file");
			}


			// Send success
			$url = "index.php?open=$open&page=$page&ft=success&fm=changes_saved&editor_language=$editor_language";
			header("Location: $url");
			exit;
			
		}

		echo"
		<h1>$get_muscle_part_of_name</h1>

		<!-- Where am I? -->
			<p>
			<b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Main</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete&amp;id=$id&amp;editor_language=$editor_language\">Delete</a>
			</p>
		<!-- //Where am I? -->

		<!-- Menu -->
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=new&amp;editor_language=$editor_language\">New</a>
			|
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=translations&amp;editor_language=$editor_language\">Translations</a>
			</p>
		<!-- //Menu -->

		<!-- Feedback -->
			";
			if($ft != ""){
				if($fm == "changes_saved"){
					$fm = "Changes saved";
				}
				else{
					$fm = ucfirst($ft);
				}
				echo"<div class=\"$ft\"><span>$fm</span></div>";
			}
		echo"
		<!-- //Feedback -->

		<!-- Delete form -->
			<h2>Delete $get_muscle_part_of_name</h2>
			<p>
			Are you sure you want to delete?
			The action cant be undone.
			</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete&amp;id=$id&amp;editor_language=$editor_language&amp;process=1\" class=\"btn\" />Delete</a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\" class=\"btn btn_default\" />Cancel</a>
			</p>

			</form>
		<!-- //Delete form -->
		";
	}
} // delete
elseif($action == "new"){
	if($process == "1"){
		
		$inp_latin_name = $_POST['inp_latin_name'];
		$inp_latin_name = output_html($inp_latin_name);
		$inp_latin_name_mysql = quote_smart($link, $inp_latin_name);
		if(empty($inp_latin_name)){
			$url = "index.php?open=$open&page=$page&action=$action&id=$id&ft=error&fm=missing_latin_name&editor_language=$editor_language";
			header("Location: $url");
			exit;
		}

		$inp_latin_name_clean = clean($inp_latin_name);
		$inp_latin_name_clean_mysql = quote_smart($link, $inp_latin_name_clean);

		$inp_name = $_POST['inp_name'];
		$inp_name = output_html($inp_name);
		$inp_name_mysql = quote_smart($link, $inp_name);

		$inp_name_clean = clean($inp_name);
		$inp_name_clean_mysql = quote_smart($link, $inp_name_clean);

		$inp_group_id_sub = $_POST['inp_group_id_sub'];
		$inp_group_id_sub = output_html($inp_group_id_sub);
		$inp_group_id_sub_mysql = quote_smart($link, $inp_group_id_sub);
		if(empty($inp_group_id_sub)){
			$url = "index.php?open=$open&page=$page&action=$action&id=$id&ft=error&fm=missing_group&editor_language=$editor_language";
			header("Location: $url");
			exit;
		}

		// Main
		$query = "SELECT muscle_group_parent_id, muscle_group_name_clean FROM $t_muscle_groups WHERE muscle_group_id=$inp_group_id_sub_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($inp_group_id_main, $get_current_sub_muscle_group_name_clean) = $row;


		$query = "SELECT muscle_group_name_clean FROM $t_muscle_groups WHERE muscle_group_id='$inp_group_id_main'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_main_muscle_group_name_clean) = $row;


		$inp_group_id_main = output_html($inp_group_id_main);
		$inp_group_id_main_mysql = quote_smart($link, $inp_group_id_main);

		// Insert
		mysqli_query($link, "INSERT INTO $t_muscle_part_of
		(muscle_part_of_id, muscle_part_of_latin_name, muscle_part_of_latin_name_clean, muscle_part_of_name, muscle_part_of_name_clean, muscle_part_of_muscle_group_id_main, muscle_part_of_muscle_group_id_sub) 
		VALUES 
		(NULL, $inp_latin_name_mysql, $inp_latin_name_clean_mysql, $inp_name_mysql, $inp_name_clean_mysql, $inp_group_id_main_mysql, $inp_group_id_sub_mysql)")
		or die(mysqli_error($link));

		// Get new ID
		$query = "SELECT muscle_part_of_id FROM $t_muscle_part_of WHERE muscle_part_of_latin_name=$inp_latin_name_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_muscle_part_of_id) = $row;


		// Img
		$name = stripslashes($_FILES['inp_image']['name']);
		$extension = getExtension($name);
		$extension = strtolower($extension);
	
		if($name){
			if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
				$ft_image = "warning";
				$fm_image = "unknown_file_extension";
			}
			else{
				// Folders exists?
				if(!(is_dir("../_uploads"))){
					mkdir("../_uploads");
				}
				if(!(is_dir("../_uploads/muscles"))){
					mkdir("../_uploads/muscles");
				}
				if(!(is_dir("../_uploads/muscles/$get_current_main_muscle_group_name_clean"))){
					mkdir("../_uploads/muscles/$get_current_main_muscle_group_name_clean");
				}
				if(!(is_dir("../_uploads/muscles/$get_current_main_muscle_group_name_clean/$get_current_sub_muscle_group_name_clean"))){
					mkdir("../_uploads/muscles/$get_current_main_muscle_group_name_clean/$get_current_sub_muscle_group_name_clean");
				}


				// Give new name
				$new_name = $inp_latin_name_clean . ".png";
				$new_path = "../_uploads/muscles/$get_current_main_muscle_group_name_clean/$get_current_sub_muscle_group_name_clean/";
				$uploaded_file = $new_path . $new_name;

					// Upload file
					if (move_uploaded_file($_FILES['inp_image']['tmp_name'], $uploaded_file)) {


						// Get image size
						$file_size = filesize($uploaded_file);
 
						// Check with and height
						list($width,$height) = getimagesize($uploaded_file);
	
						if($width == "" OR $height == ""){
							$ft_image = "warning";
							$fm_image = "getimagesize_failed";
						}
						else{
							// Update MySQL
							$inp_image_path = "_uploads/muscles/$get_current_main_muscle_group_name_clean/$get_current_sub_muscle_group_name_clean";
							$inp_image_path_mysql = quote_smart($link, $inp_image_path);
						
							$inp_image_mysql = quote_smart($link, $new_name);
							$result = mysqli_query($link, "UPDATE $t_muscle_part_of SET muscle_part_of_image_path=$inp_image_path_mysql, muscle_part_of_image_file=$inp_image_mysql WHERE muscle_part_of_id=$get_muscle_part_of_id");
		
							$ft_image = "success";
							$fm_image = "image_uploaded";

						}  // if($width == "" OR $height == ""){
					} // move_uploaded_file
					else{	
						switch ($_FILES['inp_image']['error']) {
							case UPLOAD_ERR_OK:
								$ft_image = "error";
           							$fm_image = "image_to_big";
								break;
							case UPLOAD_ERR_NO_FILE:
           							// $fm_image = "no_file_uploaded";
								break;
							case UPLOAD_ERR_INI_SIZE:
								$ft_image = "error";
           							$fm_image = "to_big_size_in_configuration";
								break;
							case UPLOAD_ERR_FORM_SIZE:
								$ft_image = "error";
           							$fm_image = "to_big_size_in_form";
								break;
							default:
								$ft_image = "error";
           							$fm_image = "unknown_error";
								break;
						}	
					}
				} // extension check
			} // if($image){


			// Delete cache
			$filenames = "";
			$dir = "../_cache/";
			$dirLen = strlen($dir);
			$dp = @opendir($dir);

			while($file = @readdir($dp)) $filenames [] = $file;

			for ($i = 0; $i < count($filenames); $i++){
				$content = $filenames[$i];
				$file_path = "$dir$content";

				if($file_path != "$dir." && $file_path != "$dir.."){
					unlink("$file_path");
				}
			}

		// Send success
		$url = "index.php?open=$open&page=$page&action=$action&id=$id&ft=success&fm=changes_saved&editor_language=$editor_language";
		header("Location: $url");
		exit;
	}

	echo"
	<h1>New</h1>

	<!-- Where am I? -->
		<p>
		<b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Main</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;id=$id&amp;editor_language=$editor_language\">New</a>
		</p>
	<!-- //Where am I? -->

	<!-- Menu -->
		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=new&amp;editor_language=$editor_language\">New</a>
		</p>
	<!-- //Menu -->

	<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "Changes saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
	echo"
	<!-- //Feedback -->

	<!-- New form -->
		<h2>New</h2>
			
		<!-- Focus -->
		<script>
			\$(document).ready(function(){
				\$('[name=\"inp_name\"]').focus();
			});
		</script>
		<!-- //Focus -->

		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;id=$id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">


		<p><b>Name*:</b><br />
		<input type=\"text\" name=\"inp_name\" value=\"\" size=\"40\" />
		</p>

		<p><b>Latin name*:</b><br />
		<input type=\"text\" name=\"inp_latin_name\" value=\"\" size=\"40\" />
		</p>

		<p><b>Part of group*:</b><br />
		<select name=\"inp_group_id_sub\">\n";
		echo"					";
		echo"<option value=\"\">- Please select -</option>\n";
		// Get all main
		$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_parent_id='0'";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_muscle_group_id, $get_muscle_group_name, $get_muscle_group_name_clean, $get_muscle_group_parent_id, $get_muscle_group_image_path, $get_muscle_group_image_file) = $row;
			
			// Translation
			$query_translation = "SELECT muscle_group_translation_id,muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_muscle_group_id AND muscle_group_translation_language=$editor_language_mysql";
			$result_translation = mysqli_query($link, $query_translation);
			$row_translation = mysqli_fetch_row($result_translation);
			list($get_muscle_group_translation_id, $get_muscle_group_translation_name) = $row_translation;
			echo"					";
			echo"<option value=\"\"> </option>\n";
			echo"					";
			echo"<option value=\"$get_muscle_group_id\">$get_muscle_group_name</option>\n";

			// Get sub
			$query_sub = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_parent_id='$get_muscle_group_id'";
			$result_sub = mysqli_query($link, $query_sub);
			while($row_sub = mysqli_fetch_row($result_sub)) {
				list($get_sub_muscle_group_id, $get_sub_muscle_group_name, $get_sub_muscle_group_name_clean, $get_sub_muscle_group_parent_id, $get_sub_muscle_group_image_path, $get_sub_muscle_group_image_file) = $row_sub;
		
				// Translation
				$query_translation = "SELECT muscle_group_translation_id,muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_sub_muscle_group_id AND muscle_group_translation_language=$editor_language_mysql";
				$result_translation = mysqli_query($link, $query_translation);
				$row_translation = mysqli_fetch_row($result_translation);
				list($get_sub_muscle_group_translation_id, $get_sub_muscle_group_translation_name) = $row_translation;
				echo"					";
				echo"<option value=\"$get_sub_muscle_group_id\">&nbsp; &nbsp; $get_sub_muscle_group_translation_name</option>\n";

			}
		}
			
		echo"
		</select>
		</p>


		<p><b>Image:</b><br />
		New image: <input type=\"file\" name=\"inp_image\" />
		</p>

		<p>
		<input type=\"submit\" value=\"Save\" class=\"btn\" />
		</p>
		</form>
	<!-- //New form -->
	";
} // new
?>