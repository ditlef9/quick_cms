<?php
/**
*
* File: _admin/_inc/discuss/tags.php
* Version 1.0.0
* Date 21:07 12.04.2019
* Copyright (c) 2008-2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}
/*- Tables ---------------------------------------------------------------------------- */
$t_forum_tags_index			= $mysqlPrefixSav . "forum_tags_index";
$t_forum_tags_index_translation		= $mysqlPrefixSav . "forum_tags_index_translation";
$t_forum_tags_watch			= $mysqlPrefixSav . "forum_tags_watch";
$t_forum_tags_ignore			= $mysqlPrefixSav . "forum_tags_ignore";


/*- Functions ------------------------------------------------------------------------ */
include("_functions/get_extension.php");


/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['tag_id'])){
	$tag_id = $_GET['tag_id'];
	$tag_id = output_html($tag_id);
}
else{
	$tag_id = "";
}

if($action == ""){
	echo"
	<h1>Tags</h1>

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


	<!-- Tags list -->
		<div class=\"vertical\">
			<ul>
	";
	
	$query = "SELECT tag_id, tag_title, tag_title_clean, tag_introduction, tag_description, tag_is_official, tag_icon_path, tag_icon_file_16 FROM $t_forum_tags_index ORDER BY tag_title ASC";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_tag_id, $get_tag_title, $get_tag_title_clean, $get_tag_introduction, $get_tag_description, $get_tag_is_official, $get_tag_icon_path, $get_tag_icon_file_16) = $row;

		if(isset($odd) && $odd == false){
			$odd = true;
		}
		else{
			$odd = false;
		}

		echo"
			<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=open_tag&amp;tag_id=$get_tag_id&amp;editor_language=$editor_language\">$get_tag_title</a></li>
		";
	}
	echo"
			</ul>
		</div>
	<!-- //Tags list -->

	";
} // action == ""
elseif($action == "open_tag"){
	// Find tag
	$tag_id_mysql = quote_smart($link, $tag_id);
	$query = "SELECT tag_id, tag_title, tag_title_clean, tag_introduction, tag_description, tag_created, tag_updated, tag_topics_total_counter, tag_topics_today_counter, tag_topics_today_day, tag_topics_this_week_counter, tag_topics_this_week_week, tag_is_official, tag_icon_path, tag_icon_file_16, tag_icon_file_32, tag_icon_file_256 FROM $t_forum_tags_index WHERE tag_id=$tag_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_tag_id, $get_current_tag_title, $get_current_tag_title_clean, $get_current_tag_introduction, $get_current_tag_description, $get_current_tag_created, $get_current_tag_updated, $get_current_tag_topics_total_counter, $get_current_tag_topics_today_counter, $get_current_tag_topics_today_day, $get_current_tag_topics_this_week_counter, $get_current_tag_topics_this_week_week, $get_current_tag_is_official, $get_current_tag_icon_path, $get_current_tag_icon_file_16, $get_current_tag_icon_file_32, $get_current_tag_icon_file_256) = $row;

	if($get_current_tag_id == ""){
		echo"
		<h1>Tag not found</h1>
		";
	}
	else{
		if($process == "1"){
			$inp_is_official = $_POST['inp_is_official'];
			$inp_is_official = output_html($inp_is_official);
			$inp_is_official_mysql = quote_smart($link, $inp_is_official);

			$result = mysqli_query($link, "UPDATE $t_forum_tags_index SET tag_is_official=$inp_is_official_mysql WHERE tag_id=$get_current_tag_id");

			// Icon dir
			if(!(is_dir("../_uploads"))){
				mkdir("../_uploads");
			}
			if(!(is_dir("../_uploads/discuss"))){
				mkdir("../_uploads/discuss");
			}
			if(!(is_dir("../_uploads/discuss/tags_icons"))){
				mkdir("../_uploads/discuss/tags_icons");
			}

			// Icon 16x16
			$image = $_FILES['inp_icon_a']['name'];
				
			$filename = stripslashes($_FILES['inp_icon_a']['name']);
			$extension = get_extension($filename);
			$extension = strtolower($extension);

			if($image){
				if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
					$ft_image_a = "warning";
					$fm_image_a = "unknown_file_format";
				}
				else{
					$tmp_name = $_FILES['inp_icon_a']['tmp_name'];
					$full_dest  = "../_uploads/discuss/tags_icons/" . $get_current_tag_title_clean . "_16x16." . $extension;
					
  					if (move_uploaded_file($tmp_name, $full_dest)){

						list($width,$height) = @getimagesize("$full_dest");

						if($width == "" OR $height == ""){
							$ft_image_a = "warning";
							$fm_image_a = "photo_could_not_be_uploaded_please_check_file_size";
							unlink("$full_dest");
						}
						else{							
							// Update icon
							$inp_icon_path = "_uploads/discuss/tags_icons";
							$inp_icon_path_mysql = quote_smart($link, $inp_icon_path);

							$inp_icon = $get_current_tag_title_clean . "_16x16." . $extension;
							$inp_icon_mysql = quote_smart($link, $inp_icon);

							mysqli_query($link, "UPDATE $t_forum_tags_index SET tag_icon_path=$inp_icon_path_mysql, tag_icon_file_16=$inp_icon_mysql WHERE tag_id=$get_current_tag_id") or die(mysqli_error($link));


							$ft_image_a = "success";
							$fm_image_a = "image_uploaded";


						}  // if($width == "" OR $height == ""){
					}
					else{
						$ft_image_a = "warning";
						$fm_image_a = "move_uploaded_file_failed";
					}
				}
			} // if($image){
			else{
				switch ($_FILES['inp_icon_a']['error']) {
					case UPLOAD_ERR_OK:
						$fm_image_a = "photo_unknown_error";
						break;
					case UPLOAD_ERR_NO_FILE:
           					$fm_image_a = "no_file_selected";
						break;
					case UPLOAD_ERR_INI_SIZE:
           					$fm_image_a = "photo_exceeds_filesize";
						break;
					case UPLOAD_ERR_FORM_SIZE:
           					$fm_image_a = "photo_exceeds_filesize_form";
						break;
					default:
           					$fm_image_a = "unknown_upload_error";
						break;
					}
				if(isset($fm_image_a) && $fm_image_a != ""){
					$ft_image_a = "warning";
				}
			}

			// Icon 32x32
			$image = $_FILES['inp_icon_b']['name'];
				
			$filename = stripslashes($_FILES['inp_icon_b']['name']);
			$extension = get_extension($filename);
			$extension = strtolower($extension);

			if($image){
				if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
					$ft_image_b = "warning";
					$fm_image_b = "unknown_file_format";
				}
				else{
					$tmp_name = $_FILES['inp_icon_b']['tmp_name'];
					$full_dest  = "../_uploads/discuss/tags_icons/" . $get_current_tag_title_clean . "_32x32." . $extension;
					
  					if(move_uploaded_file($tmp_name, $full_dest)){

						list($width,$height) = @getimagesize("$full_dest");

						if($width == "" OR $height == ""){
							$ft_image_b = "warning";
							$fm_image_b = "photo_could_not_be_uploaded_please_check_file_size";
							unlink("$full_dest");
						}
						else{							
							// Update icon
							$inp_icon_path = "_uploads/discuss/tags_icons";
							$inp_icon_path_mysql = quote_smart($link, $inp_icon_path);

							$inp_icon = $get_current_tag_title_clean . "_32x32." . $extension;
							$inp_icon_mysql = quote_smart($link, $inp_icon);

							mysqli_query($link, "UPDATE $t_forum_tags_index SET tag_icon_path=$inp_icon_path_mysql, tag_icon_file_32=$inp_icon_mysql WHERE tag_id=$get_current_tag_id") or die(mysqli_error($link));


							$ft_image_b = "success";
							$fm_image_b = "image_uploaded";


						}  // if($width == "" OR $height == ""){
					}
					else{
						$ft_image_b = "warning";
						$fm_image_b = "move_uploaded_file_failed";
					}
				}
			} // if($image){
			else{
				switch ($_FILES['inp_icon_b']['error']) {
					case UPLOAD_ERR_OK:
						$fm_image_b = "photo_unknown_error";
						break;
					case UPLOAD_ERR_NO_FILE:
           					$fm_image_b = "no_file_selected";
						break;
					case UPLOAD_ERR_INI_SIZE:
           					$fm_image_b = "photo_exceeds_filesize";
						break;
					case UPLOAD_ERR_FORM_SIZE:
           					$fm_image_b = "photo_exceeds_filesize_form";
						break;
					default:
           					$fm_image_b = "unknown_upload_error";
						break;
					}
				if(isset($fm_image_b) && $fm_image_b != ""){
					$ft_image_b = "warning";
				}
			}


			// Icon 256x256
			$image = $_FILES['inp_icon_c']['name'];
				
			$filename = stripslashes($_FILES['inp_icon_c']['name']);
			$extension = get_extension($filename);
			$extension = strtolower($extension);

			if($image){
				if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
					$ft_image_c = "warning";
					$fm_image_c = "unknown_file_format";
				}
				else{
					$tmp_name = $_FILES['inp_icon_c']['tmp_name'];
					$full_dest  = "../_uploads/discuss/tags_icons/" . $get_current_tag_title_clean . "_256x256." . $extension;
					
  					if (move_uploaded_file($tmp_name, $full_dest)){

						list($width,$height) = @getimagesize("$full_dest");

						if($width == "" OR $height == ""){
							$ft_image_c = "warning";
							$fm_image_c = "photo_could_not_be_uploaded_please_check_file_size";
							unlink("$full_dest");
						}
						else{							
							// Update icon
							$inp_icon_path = "_uploads/discuss/tags_icons";
							$inp_icon_path_mysql = quote_smart($link, $inp_icon_path);

							$inp_icon = $get_current_tag_title_clean . "_256x256." . $extension;
							$inp_icon_mysql = quote_smart($link, $inp_icon);

							mysqli_query($link, "UPDATE $t_forum_tags_index SET tag_icon_path=$inp_icon_path_mysql, tag_icon_file_256=$inp_icon_mysql WHERE tag_id=$get_current_tag_id") or die(mysqli_error($link));


							$ft_image_c = "success";
							$fm_image_c = "image_uploaded";


						}  // if($width == "" OR $height == ""){
					}
					else{
						$ft_image_c = "warning";
						$fm_image_c = "move_uploaded_file_failed";
					}
				}
			} // if($image){
			else{
				switch ($_FILES['inp_icon_c']['error']) {
					case UPLOAD_ERR_OK:
						$fm_image_c = "photo_unknown_error";
						break;
					case UPLOAD_ERR_NO_FILE:
           					$fm_image_c = "no_file_selected";
						break;
					case UPLOAD_ERR_INI_SIZE:
           					$fm_image_c = "photo_exceeds_filesize";
						break;
					case UPLOAD_ERR_FORM_SIZE:
           					$fm_image_c = "photo_exceeds_filesize_form";
						break;
					default:
           					$fm_image_c = "unknown_upload_error";
						break;
					}
				if(isset($fm_image_c) && $fm_image_c != ""){
					$ft_image_c = "warning";
				}
			}

			// Text	
			$query = "SELECT language_active_id, language_active_name, language_active_iso_two FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two) = $row;

				$inp_introduction = $_POST["inp_introduction_$get_language_active_iso_two"];
				$inp_introduction = output_html($inp_introduction);
				$inp_introduction_mysql = quote_smart($link, $inp_introduction);
				mysqli_query($link, "UPDATE $t_forum_tags_index_translation SET tag_translation_introduction=$inp_introduction_mysql WHERE tag_id=$get_current_tag_id AND tag_translation_language='$get_language_active_iso_two'") or die(mysqli_error($link));


				$inp_description = $_POST["inp_description_$get_language_active_iso_two"];
				$sql = "UPDATE $t_forum_tags_index_translation SET tag_translation_description=? WHERE tag_id=$get_current_tag_id AND tag_translation_language='$get_language_active_iso_two'";
				$stmt = $link->prepare($sql);
				$stmt->bind_param("s", $inp_description);
				$stmt->execute();
				if ($stmt->errno) {
					echo "FAILURE!!! " . $stmt->error; die;
				}


				
			}

			// Header
			$url = "index.php?open=$open&page=tags&action=open_tag&tag_id=$tag_id&editor_language=$editor_language&ft=success&fm=changes_saved";
			if(isset($ft_image_a) && isset($fm_image_a)){
				$url = $url . "&ft_image_a=$ft_image_a&fm_image_a=$fm_image_a";
			}
			if(isset($ft_image_b) && isset($fm_image_b)){
				$url = $url . "&ft_image_b=$ft_image_b&fm_image_b=$fm_image_b";
			}
			if(isset($ft_image_c) && isset($fm_image_c)){
				$url = $url . "&ft_image_c=$ft_image_c&fm_image_c=$fm_image_c";
			}
			header("Location: $url");
			exit;

		} // process
		echo"
		<h1>$get_current_tag_title</h1>


		
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

		<!-- Form -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_text\"]').focus();
			});
			</script>

			<!-- TinyMCE -->
				<script type=\"text/javascript\" src=\"_javascripts/tinymce/tinymce.min.js\"></script>
				<script>
				tinymce.init({
					selector: 'textarea.editor',
					plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern help',
					toolbar: 'formatselect | bold italic strikethrough forecolor backcolor permanentpen formatpainter | link image media pageembed | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment',
					image_advtab: true,
					content_css: [
						'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
						'//www.tiny.cloud/css/codepen.min.css'
					],
					link_list: [
						{ title: 'My page 1', value: 'http://www.tinymce.com' },
						{ title: 'My page 2', value: 'http://www.moxiecode.com' }
					],
					image_list: [
						{ title: 'My page 1', value: 'http://www.tinymce.com' },
						{ title: 'My page 2', value: 'http://www.moxiecode.com' }
					],
						image_class_list: [
						{ title: 'None', value: '' },
						{ title: 'Some class', value: 'class-name' }
					],
					importcss_append: true,
					height: 400,
					file_picker_callback: function (callback, value, meta) {
						/* Provide file and text for the link dialog */
						if (meta.filetype === 'file') {
							callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
						}
						/* Provide image and alt text for the image dialog */
						if (meta.filetype === 'image') {
							callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
						}
						/* Provide alternative source and posted for the media dialog */
						if (meta.filetype === 'media') {
							callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
						}
					}
				});
				</script>
			<!-- //TinyMCE -->

			
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=open_tag&amp;tag_id=$get_current_tag_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">
		


			<p><b>Tag is official:</b><br />
			<input type=\"radio\" name=\"inp_is_official\" value=\"1\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\""; if($get_current_tag_is_official == "1"){ echo" checked=\"checked\"";} echo" />
			Yes
			&nbsp;
			<input type=\"radio\" name=\"inp_is_official\" value=\"0\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\""; if($get_current_tag_is_official == "0"){ echo" checked=\"checked\"";} echo" />
			No
			</p>

			<p><b>Icon 16x16:</b><br />
			";
			if(file_exists("../$get_current_tag_icon_path/$get_current_tag_icon_file_16") && $get_current_tag_icon_file_16 != ""){
				echo"
				<a href=\"../$get_current_tag_icon_path/$get_current_tag_icon_file_16\"><img src=\"../$get_current_tag_icon_path/$get_current_tag_icon_file_16\" alt=\"$get_current_tag_icon_file_16\" /></a><br />
				";
			}

			if(isset($_GET['ft_image_a']) && isset($_GET['fm_image_a'])) {
				$ft = $_GET['ft_image_a'];
				$ft = strip_tags(stripslashes($ft));
				if($ft != "error" && $ft != "warning" && $ft != "success" && $ft != "info"){
					echo"Server error 403 feedback error";die;
				}
				$fm = $_GET['fm_image_a'];
				$fm = strip_tags(stripslashes($fm));
				$fm = ucfirst($fm);
				$fm = str_replace("_", " ", $fm);
				echo"<span class=\"$ft\"><em>$fm</em><br /></span>";
			}
			echo"			
			<input name=\"inp_icon_a\" type=\"file\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</p>

			<p><b>Icon 32x32:</b><br />
			";
			if(file_exists("../$get_current_tag_icon_path/$get_current_tag_icon_file_32") && $get_current_tag_icon_file_32 != ""){
				echo"
				<a href=\"../$get_current_tag_icon_path/$get_current_tag_icon_file_32\"><img src=\"../$get_current_tag_icon_path/$get_current_tag_icon_file_32\" alt=\"$get_current_tag_icon_file_32\" /></a><br />";
			}
			if(isset($_GET['ft_image_b']) && isset($_GET['fm_image_b'])) {
				$ft = $_GET['ft_image_b'];
				$ft = strip_tags(stripslashes($ft));
				if($ft != "error" && $ft != "warning" && $ft != "success" && $ft != "info"){
					echo"Server error 403 feedback error";die;
				}
				$fm = $_GET['fm_image_b'];
				$fm = strip_tags(stripslashes($fm));
				$fm = ucfirst($fm);
				$fm = str_replace("_", " ", $fm);
				echo"<span class=\"$ft\"><em>$fm</em><br /></span>";
			}
			echo"			
			<input name=\"inp_icon_b\" type=\"file\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</p>

			<p><b>Icon 256x256:</b><br />
			";
			if(file_exists("../$get_current_tag_icon_path/$get_current_tag_icon_file_256") && $get_current_tag_icon_file_256 != ""){
				echo"
				<a href=\"../$get_current_tag_icon_path/$get_current_tag_icon_file_256\"><img src=\"../$get_current_tag_icon_path/$get_current_tag_icon_file_256\" alt=\"$get_current_tag_icon_file_256\" /></a><br />";
			}
			if(isset($_GET['ft_image_c']) && isset($_GET['fm_image_c'])) {
				$ft = $_GET['ft_image_c'];
				$ft = strip_tags(stripslashes($ft));
				if($ft != "error" && $ft != "warning" && $ft != "success" && $ft != "info"){
					echo"Server error 403 feedback error";die;
				}
				$fm = $_GET['fm_image_c'];
				$fm = strip_tags(stripslashes($fm));
				$fm = ucfirst($fm);
				$fm = str_replace("_", " ", $fm);
				echo"<span class=\"$ft\"><em>$fm</em><br /></span>";
			}
			echo"			
			<input name=\"inp_icon_c\" type=\"file\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</p>


			\n";
			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag_path_16x16, language_active_flag_16x16 FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag_path_16x16, $get_language_active_flag_16x16) = $row;
			

				
				// Find translation
				$query_t = "SELECT tag_translation_id, tag_id, tag_translation_language, tag_translation_introduction, tag_translation_description FROM $t_forum_tags_index_translation WHERE tag_id=$get_current_tag_id AND tag_translation_language='$get_language_active_iso_two'";
				$result_t = mysqli_query($link, $query_t);
				$row_t = mysqli_fetch_row($result_t);
				list($get_tag_translation_id, $get_tag_id, $get_tag_translation_language, $get_tag_translation_introduction, $get_tag_translation_description) = $row_t;
				if($get_tag_translation_id == ""){
					mysqli_query($link, "INSERT INTO $t_forum_tags_index_translation 
					(tag_translation_id, tag_id, tag_translation_language) 
					VALUES 
					(NULL, $get_current_tag_id, '$get_language_active_iso_two')")
					or die(mysqli_error($link));
				}

				echo"
				<hr />
				<p><b><img src=\"../$get_language_active_flag_path_16x16/$get_language_active_flag_16x16\" alt=\"$get_language_active_flag_16x16\" /> Introduction</b><br />
				<textarea name=\"inp_introduction_$get_language_active_iso_two\" rows=\"5\" cols=\"60\">";
				$get_tag_translation_introduction = str_replace("<br />", "\n", $get_tag_translation_introduction);
				echo"$get_tag_translation_introduction</textarea>
				</p>

				<p><b><img src=\"../$get_language_active_flag_path_16x16/$get_language_active_flag_16x16\" alt=\"$get_language_active_flag_16x16\" /> Description</b><br />
				<textarea name=\"inp_description_$get_language_active_iso_two\" rows=\"10\" cols=\"60\" class=\"editor\">$get_tag_translation_description</textarea>
				</p>
				\n";
			}
			echo"
		
		
			<p><input type=\"submit\" value=\"Save changes\" class=\"btn btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
			</form>
		<!-- //Form -->
		
		";
	} // tag found
} // open_tag


?>