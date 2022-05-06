<?php
/**
*
* File: _admin/_inc/exercies/muscle_groups.php
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
$t_muscle_groups 			= $mysqlPrefixSav . "muscle_groups";
$t_muscle_groups_translations	 	= $mysqlPrefixSav . "muscle_groups_translations";

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
if(isset($_GET['main_id'])){
	$main_id = $_GET['main_id'];
	$main_id = strip_tags(stripslashes($main_id));
}
else{
	$main_id = "";
}

if($action == ""){
	echo"
	<h1>Muscle groups</h1>


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
		$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_parent_id='0'";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_muscle_group_id, $get_muscle_group_name, $get_muscle_group_name_clean, $get_muscle_group_parent_id, $get_muscle_group_image_path, $get_muscle_group_image_file) = $row;
				
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
				<span><a href=\"index.php?open=$open&amp;page=$page&amp;action=open_main&amp;main_id=$get_muscle_group_id&amp;editor_language=$editor_language\">$get_muscle_group_name</a></span>
			  </td>
			  <td class=\"$style\">
				<span>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit&amp;id=$get_muscle_group_id&amp;editor_language=$editor_language\">Edit</a>
				|
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete&amp;id=$get_muscle_group_id&amp;editor_language=$editor_language\">Delete</a>
				</span>
			  </td>
			 </tr>";
		}

		echo"
		</table>
	<!-- //Main categories -->
	";
}
elseif($action == "open_main"){
	// Select main
	$main_id_mysql = quote_smart($link, $main_id);
	$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_id=$main_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_muscle_group_id, $get_current_muscle_group_name, $get_current_muscle_group_name_clean, $get_current_muscle_group_parent_id, $get_current_muscle_group_image_path, $get_current_muscle_group_image_file) = $row;

	if($get_current_muscle_group_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>Main not found.</p>

		<p><a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Back</a></p>
		";
	}
	else{
		echo"
		<h1>$get_current_muscle_group_name</h1>

		<!-- Where am I? -->
			<p>
			<b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Main</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_main&amp;main_id=$main_id&amp;editor_language=$editor_language\">$get_current_muscle_group_name</a>
			</p>
		<!-- //Where am I? -->

		<!-- Menu -->
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=new&amp;editor_language=$editor_language\">New</a>
			</p>
		<!-- //Menu -->
		<!-- Sub categories -->

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
			$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_parent_id='0'";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_main_muscle_group_id, $get_main_muscle_group_name, $get_main_muscle_group_name_clean, $get_main_muscle_group_parent_id, $get_main_muscle_group_image_path, $get_main_muscle_group_image_file) = $row;


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
					<span><a href=\"index.php?open=$open&amp;page=$page&amp;action=open_main&amp;main_id=$get_main_muscle_group_id&amp;editor_language=$editor_language\""; if($get_main_muscle_group_id == $get_current_muscle_group_id){ echo" style=\"font-weight:bold;\""; } echo">$get_main_muscle_group_name</a></span>
				  </td>
				  <td class=\"$style\">
					<span>
					<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit&amp;id=$get_main_muscle_group_id&amp;editor_language=$editor_language\">Edit</a>
					|
					<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete&amp;id=$get_main_muscle_group_id&amp;editor_language=$editor_language\">Delete</a>
					</span>
				  </td>
				 </tr>";

				if($get_main_muscle_group_id == $get_current_muscle_group_id){

					// Get all sub
					$query_sub = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_parent_id=$main_id_mysql";
					$result_sub = mysqli_query($link, $query_sub);
					while($row_sub = mysqli_fetch_row($result_sub)) {
						list($get_sub_muscle_group_id, $get_sub_muscle_group_name, $get_sub_muscle_group_name_clean, $get_sub_muscle_group_parent_id, $get_sub_muscle_group_image_path, $get_sub_muscle_group_image_file) = $row_sub;

						echo"
						 <tr>
						  <td class=\"$style\">
							<span>&nbsp; &nbsp; $get_sub_muscle_group_name</span>
						  </td>
						  <td class=\"$style\">
							<span>
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit&amp;id=$get_sub_muscle_group_id&amp;editor_language=$editor_language\">Edit</a>
							|
							<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete&amp;id=$get_sub_muscle_group_id&amp;editor_language=$editor_language\">Delete</a>
							</span>
						  </td>
						 </tr>";
					}
				}
			}

			echo"
			</table>
		<!-- //Sub categories -->
		";
	}
} // open main
elseif($action == "edit"){
	// Select main
	$id_mysql = quote_smart($link, $id);
	$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_id=$id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_muscle_group_id, $get_current_muscle_group_name, $get_current_muscle_group_name_clean, $get_current_muscle_group_parent_id, $get_current_muscle_group_image_path, $get_current_muscle_group_image_file) = $row;

	if($get_current_muscle_group_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>Not found.</p>

		<p><a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Back</a></p>
		";
	}
	else{
		// Muscle Group Translation
		$query_translation = "SELECT muscle_group_translation_id, muscle_group_translation_name, muscle_group_translation_text FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_current_muscle_group_id AND muscle_group_translation_language=$editor_language_mysql";
		$result_translation = mysqli_query($link, $query_translation);
		$row_translation = mysqli_fetch_row($result_translation);
		list($get_current_muscle_group_translation_id, $get_current_muscle_group_translation_name, $get_current_muscle_group_translation_text) = $row_translation;
		if($get_current_muscle_group_translation_id == ""){
			mysqli_query($link, "INSERT INTO $t_muscle_groups_translations
			(muscle_group_translation_id, muscle_group_translation_muscle_group_id, muscle_group_translation_language, muscle_group_translation_name, muscle_group_translation_text) 
			VALUES 
			(NULL, 	'$get_current_muscle_group_id', $editor_language_mysql, '$get_current_muscle_group_name', '')")
			or die(mysqli_error($link));
			echo"<div class=\"info\"><span>L O A D I N G</span></div>";
			echo"
			<meta http-equiv=\"refresh\" content=\"0;URL='index.php?open=$open&amp;page=$page&amp;action=$action&amp;id=$id&amp;editor_language=$editor_language&amp;l=$l'\" />
			";
		}

		if($process == "1"){
			$inp_name = $_POST['inp_name'];
			$inp_name = output_html($inp_name);
			$inp_name_mysql = quote_smart($link, $inp_name);

			$inp_name_clean = clean($inp_name);
			$inp_name_clean_mysql = quote_smart($link, $inp_name_clean);

			$inp_translation_name = $_POST['inp_translation_name'];
			$inp_translation_name = output_html($inp_translation_name);
			$inp_translation_name_mysql = quote_smart($link, $inp_translation_name);

			$inp_parent_id = $_POST['inp_parent_id'];
			$inp_parent_id = output_html($inp_parent_id);
			$inp_parent_id_mysql = quote_smart($link, $inp_parent_id);

	
			$inp_translation_text = $_POST['inp_translation_text'];

			// Update
			$result = mysqli_query($link, "UPDATE $t_muscle_groups SET muscle_group_name=$inp_name_mysql, muscle_group_name_clean=$inp_name_clean_mysql, muscle_group_parent_id=$inp_parent_id_mysql WHERE muscle_group_id='$get_current_muscle_group_id'") or die(mysqli_error());
			$result = mysqli_query($link, "UPDATE $t_muscle_groups_translations SET muscle_group_translation_name=$inp_translation_name_mysql WHERE muscle_group_translation_muscle_group_id=$get_current_muscle_group_id AND muscle_group_translation_language=$editor_language_mysql") or die(mysqli_error());


			// Update content
			$sql = "UPDATE $t_muscle_groups_translations SET muscle_group_translation_text=? WHERE muscle_group_translation_muscle_group_id=$get_current_muscle_group_id AND muscle_group_translation_language=$editor_language_mysql";
			$stmt = $link->prepare($sql);
			$stmt->bind_param("s", $inp_translation_text);
			$stmt->execute();
			if ($stmt->errno) {
				echo "FAILURE!!! " . $stmt->error; die;
			}


			// Image
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


					// Does it have parent?	
					if($get_current_muscle_group_parent_id != 0){
						$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_id='$get_current_muscle_group_parent_id'";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_main_muscle_group_id, $get_main_muscle_group_name, $get_main_muscle_group_name_clean, $get_main_muscle_group_parent_id, $get_main_muscle_group_image_path, $get_main_muscle_group_image_file) = $row;
		
	

						if(!(is_dir("../_uploads/muscles/$get_main_muscle_group_name_clean"))){
							mkdir("../_uploads/muscles/$get_main_muscle_group_name_clean");
						}


						if(!(is_dir("../_uploads/muscles/$get_main_muscle_group_name_clean/$get_current_muscle_group_name_clean"))){
							mkdir("../_uploads/muscles/$get_main_muscle_group_name_clean/$get_current_muscle_group_name_clean");
						}

						$new_path = "../_uploads/muscles/$get_main_muscle_group_name_clean/$get_current_muscle_group_name_clean";
						
					}
					else{

						if(!(is_dir("../_uploads/muscles/$get_current_muscle_group_name_clean"))){
							mkdir("../_uploads/muscles/$get_current_muscle_group_name_clean");
						}
						$new_path = "../_uploads/muscles/$get_current_muscle_group_name_clean";
					}


 					// Give new name
					$new_name = $inp_name_clean . ".png";
					$uploaded_file = $new_path . "/" . $new_name;

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
							$inp_image_path = str_replace("../", "", $new_path);
							$inp_image_path_mysql = quote_smart($link, $inp_image_path);
						
							$inp_image_mysql = quote_smart($link, $new_name);

							$result = mysqli_query($link, "UPDATE $t_muscle_groups SET muscle_group_image_path=$inp_image_path_mysql, muscle_group_image_file=$inp_image_mysql WHERE muscle_group_id='$get_current_muscle_group_id'");
	
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
           							$fm_image = "unknown_error to path $new_path";
								break;
						}	
					}
				} // extension check
			} // if($image){

	
			// Send success
			$url = "index.php?open=$open&page=$page&action=$action&id=$id&ft=success&fm=changes_saved&editor_language=$editor_language";

			if(isset($ft_image)){

				$url = $url . "&ft_image=$ft_image&fm_image=$fm_image";
			}

			header("Location: $url");
			exit;

		}

		echo"
		<div style=\"float:left;\">
			<h1>$get_current_muscle_group_name</h1>

		</div>
		<div style=\"float:left;padding: 14px 0px 0px 500px\">
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


			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;

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
		</div>
		<div class=\"clear\"></div>


		<!-- Where am I? -->
			<p>
			<b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Main</a>
			&gt; ";
	
			// Does it have parent?	
			if($get_current_muscle_group_parent_id != 0){
				$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_id='$get_current_muscle_group_parent_id'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_main_muscle_group_id, $get_main_muscle_group_name, $get_main_muscle_group_name_clean, $get_main_muscle_group_parent_id, $get_main_muscle_group_image_path, $get_main_muscle_group_image_file) = $row;
		
				echo"<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_main&amp;main_id=$get_main_muscle_group_id&amp;editor_language=$editor_language\">$get_main_muscle_group_name</a>";
				echo" &gt; ";
			}	
			else{
				// This is parent
				echo"<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_main&amp;main_id=$get_current_muscle_group_id&amp;editor_language=$editor_language\">$get_current_muscle_group_name</a>";
				echo" &gt; ";
			}
			
			echo"
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit&amp;id=$id&amp;editor_language=$editor_language\">Edit $get_current_muscle_group_name</a>
			</p>
		<!-- //Where am I? -->

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
			if(isset($_GET['ft_image']) && isset($_GET['fm_image'])){
				
				$ft_image = $_GET['ft_image'];
				$ft_image = strip_tags(stripslashes($ft_image));
				$fm_image = $_GET['fm_image'];
				$fm_image = strip_tags(stripslashes($fm_image));

				echo"<div class=\"$ft_image\"><span>$fm_image</span></div>";
			}
		echo"
		<!-- //Feedback -->

		<!-- Edit form -->
			<h2>Edit $get_current_muscle_group_name</h2>
			
				
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


			<!-- Focus -->
			<script>
				\$(document).ready(function(){
					\$('[name=\"inp_name\"]').focus();
				});
			</script>
			<!-- //Focus -->

			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=edit&amp;id=$id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">


			<p><b>Name:</b><br />
			<input type=\"text\" name=\"inp_name\" value=\"$get_current_muscle_group_name\" size=\"40\" />
			</p>


			<p><b>Name ($editor_language):</b><br />
			<input type=\"text\" name=\"inp_translation_name\" value=\"$get_current_muscle_group_translation_name\" size=\"40\" />
			</p>

			<p><b>Parent:</b><br />
			<select name=\"inp_parent_id\">
				<option value=\"0\""; if($get_current_muscle_group_parent_id == "0"){ echo" selected=\"selected\""; } echo">- This is parent -</option>\n";
				$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_parent_id='0'";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_muscle_group_id, $get_muscle_group_name, $get_muscle_group_name_clean, $get_muscle_group_parent_id, $get_muscle_group_image_path, $get_muscle_group_image_file) = $row;
					echo"			";
					echo"<option value=\"$get_muscle_group_id\""; if($get_current_muscle_group_parent_id == "$get_muscle_group_id"){ echo" selected=\"selected\""; } echo">$get_muscle_group_name</option>\n";
				}
			echo"
			</select>
			</p>


			<p><b>Text ($editor_language):</b><br />
			<textarea name=\"inp_translation_text\" rows=\"10\" cols=\"70\">$get_current_muscle_group_translation_text</textarea>
			</p>


			<p><b>Image:</b><br />
			<img src=\"";
			if($get_current_muscle_group_image_file != "" && file_exists("../$get_current_muscle_group_image_path/$get_current_muscle_group_image_file")){
				echo"../image.php?image=/$get_current_muscle_group_image_path/$get_current_muscle_group_image_file";
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
	// Select main
	$id_mysql = quote_smart($link, $id);
	$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_id=$id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_muscle_group_id, $get_current_muscle_group_name, $get_current_muscle_group_name_clean, $get_current_muscle_group_parent_id, $get_current_muscle_group_image_path, $get_current_muscle_group_image_file) = $row;

	if($get_current_muscle_group_id == ""){
		echo"
		<h1>Server error 404</h1>

		<p>Not found.</p>

		<p><a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Back</a></p>
		";
	}
	else{
		if($process == "1"){

			// Update
			$result = mysqli_query($link, "DELETE FROM $t_muscle_groups WHERE muscle_group_id='$get_current_muscle_group_id'") or die(mysqli_error());


			// Is this a parent?
			if($get_current_muscle_group_parent_id == 0){
				// Move all children to someone else..
				
				$result = mysqli_query($link, "UPDATE $t_muscle_groups SET muscle_group_parent_id='0' WHERE muscle_group_parent_id='$get_current_muscle_group_id'") or die(mysqli_error());
			
				// Send success
				$url = "index.php?open=$open&page=$page&ft=success&fm=changes_saved&editor_language=$editor_language";
				header("Location: $url");
				exit;
			}
			else{
				// Send success
				$url = "index.php?open=$open&page=$page&action=open_main&main_id=$get_current_muscle_group_parent_id&ft=success&fm=changes_saved&editor_language=$editor_language";
				header("Location: $url");
				exit;
			}

		}

		echo"
		<h1>$get_current_muscle_group_name</h1>

		<!-- Where am I? -->
			<p>
			<b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language\">Main</a>
			&gt; ";
	
			// Does it have parent?	
			if($get_current_muscle_group_parent_id != 0){
				$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_id='$get_current_muscle_group_parent_id'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_main_muscle_group_id, $get_main_muscle_group_name, $get_main_muscle_group_name_clean, $get_main_muscle_group_parent_id, $get_main_muscle_group_image_path, $get_main_muscle_group_image_file) = $row;
		
				echo"<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_main&amp;main_id=$get_main_muscle_group_id&amp;editor_language=$editor_language\">$get_main_muscle_group_name</a>";
				echo" &gt; ";
			}	
			else{
				// This is parent
				echo"<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_main&amp;main_id=$get_current_muscle_group_id&amp;editor_language=$editor_language\">$get_current_muscle_group_name</a>";
				echo" &gt; ";
			}
			
			echo"
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete&amp;id=$id&amp;editor_language=$editor_language\">Delete $get_current_muscle_group_name</a>
			</p>
		<!-- //Where am I? -->

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
			<h2>Delete $get_current_muscle_group_name</h2>
			<p>
			Are you sure you want to delete?
			The action cant be undone.
			</p>

			";
			
			if($get_current_muscle_group_parent_id == 0){
				echo"<p><i>If this has children, then they will become parents after deletion.</i></p>";
			}
			echo"

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
		$inp_name = $_POST['inp_name'];
		$inp_name = output_html($inp_name);
		$inp_name_mysql = quote_smart($link, $inp_name);

		$inp_name_clean = clean($inp_name);
		$inp_name_clean_mysql = quote_smart($link, $inp_name_clean);

		$inp_parent_id = $_POST['inp_parent_id'];
		$inp_parent_id = output_html($inp_parent_id);
		$inp_parent_id_mysql = quote_smart($link, $inp_parent_id);

		// Insert
		mysqli_query($link, "INSERT INTO $t_muscle_groups
		(muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id) 
		VALUES 
		(NULL, $inp_name_mysql, $inp_name_clean_mysql, $inp_parent_id_mysql)")
		or die(mysqli_error($link));

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


		<p><b>Name:</b><br />
		<input type=\"text\" name=\"inp_name\" value=\"\" size=\"40\" />
		</p>

		<p><b>Parent:</b><br />
		<select name=\"inp_parent_id\">
			<option value=\"0\">- This is parent -</option>\n";
			$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_parent_id='0'";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_muscle_group_id, $get_muscle_group_name, $get_muscle_group_name_clean, $get_muscle_group_parent_id, $get_muscle_group_image_path, $get_muscle_group_image_file) = $row;
				echo"			";
				echo"<option value=\"$get_muscle_group_id\">$get_muscle_group_name</option>\n";
			}
		echo"
		</select>
		</p>

		<p>
		<input type=\"submit\" value=\"Save\" class=\"btn\" />
		</p>
		</form>
	<!-- //New form -->
	";
} // new
elseif($action == "translations"){
	if($process == 1){
		$editor_language_mysql = quote_smart($link, $editor_language);
		$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_muscle_group_id, $get_muscle_group_name, $get_muscle_group_name_clean, $get_muscle_group_parent_id, $get_muscle_group_image_path, $get_muscle_group_image_file) = $row;

			$inp_value = $_POST["inp_value_$get_muscle_group_id"];
			$inp_value = output_html($inp_value);
			$inp_value_mysql = quote_smart($link, $inp_value);

			// Update
			$result_update = mysqli_query($link, "UPDATE $t_muscle_groups_translations SET muscle_group_translation_name=$inp_value_mysql WHERE muscle_group_translation_muscle_group_id=$get_muscle_group_id AND muscle_group_translation_language=$editor_language_mysql") or die(mysqli_error($link));
		}

		$url = "index.php?open=$open&page=$page&action=$action&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
		header("Location: $url");
		exit;

	}


	echo"
	<h1>Translations</h1>


	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		else{
			$fm = ucfirst($ft);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->

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
				
				
				echo"	<option value=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$get_language_active_iso_two&amp;l=$l\" style=\"background: url('$flag_path') no-repeat;padding-left: 20px;\"";if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
			}
		echo"
		</select>
		</p>
		</form>
	<!-- //Select language -->

	

	<!-- Translate form -->
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	

		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>Name</span>
		   </th>
		   <th scope=\"col\">
			<span>Translation</span>
		   </th>
		  </tr>
		</thead>
		<tbody>
		";
	


		$editor_language_mysql = quote_smart($link, $editor_language);

		$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_muscle_group_id, $get_muscle_group_name, $get_muscle_group_name_clean, $get_muscle_group_parent_id, $get_muscle_group_image_path, $get_muscle_group_image_file) = $row;
				

			// Style
			if(isset($style) && $style == ""){
				$style = "odd";
			}
			else{
				$style = "";
			}	

			// Translation
			$query_translation = "SELECT muscle_group_translation_id, muscle_group_translation_muscle_group_id, muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_muscle_group_id AND muscle_group_translation_language=$editor_language_mysql";
			$result_translation = mysqli_query($link, $query_translation);
			$row_translation = mysqli_fetch_row($result_translation);
			list($get_muscle_group_translation_id, $get_muscle_group_translation_muscle_group_id, $get_muscle_group_translation_name) = $row_translation;
			if($get_muscle_group_translation_id == ""){
				// It doesnt exists, create it.
				mysqli_query($link, "INSERT INTO $t_muscle_groups_translations
				(muscle_group_translation_id, muscle_group_translation_muscle_group_id, muscle_group_translation_language, muscle_group_translation_name) 
				VALUES 
				(NULL, '$get_muscle_group_id', $editor_language_mysql, '$get_muscle_group_name')")
				or die(mysqli_error($link));

				echo"<div class=\"info\"><span>L O A D I N G</span></div>";
				echo"
 				<meta http-equiv=\"refresh\" content=\"0;URL='index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$editor_language&amp;l=$l'\" />
				";

				
			}

			echo"
			<tr>
			  <td class=\"$style\">
				<span>$get_muscle_group_name</span>
			  </td>
			  <td class=\"$style\">
				<span><input type=\"text\" name=\"inp_value_$get_muscle_group_id\" value=\"$get_muscle_group_translation_name\" size=\"40\" /></span>
			  </td>
			</tr>
			";
		}
		echo"
		 </tbody>
		</table>

		<p>
		<input type=\"submit\" value=\"$l_save_changes\" class=\"btn\" />
		</p>
		</form>

	<!-- //List all categories -->

	<!-- Back -->
		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn\">Back</a>
		</p>
	<!-- //Back -->
 	";
} // action == "";
?>