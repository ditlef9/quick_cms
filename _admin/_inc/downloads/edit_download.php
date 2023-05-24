<?php
/**
*
* File: _admin/_inc/downloads/edit_download.php
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

/*- Tables search --------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";



/*- Varialbes  ---------------------------------------------------- */
if(isset($_GET['download_id'])) {
	$download_id = $_GET['download_id'];
	$download_id = strip_tags(stripslashes($download_id));
}
else{
	$download_id = "";
}

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


/*- Scriptstart ---------------------------------------------------------------------- */
// Find the download
$stmt = $mysqli->prepare("SELECT download_id, download_title, download_language, download_introduction, download_description, download_image_path, download_image_store, download_image_store_thumb, download_image_thumb_a, download_image_thumb_b, download_image_thumb_c, download_image_thumb_d, download_image_file_a, download_image_file_b, download_image_file_c, download_image_file_d, download_read_more_url, download_main_category_id, download_sub_category_id, download_dir, download_internal_external, download_file, download_file_external_url, download_type, download_version, download_file_size, download_file_date, download_last_download, download_hits, download_unique_hits, download_ip_block, download_tag_a, download_tag_b, download_tag_c, download_created_datetime, download_have_to_be_logged_in_to_download FROM $t_downloads_index WHERE download_id=?"); 
$stmt->bind_param("s", $download_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_current_download_id, $get_current_download_title, $get_current_download_language, $get_current_download_introduction, $get_current_download_description, $get_current_download_image_path, $get_download_image_store, $get_download_image_store_thumb, $get_current_download_image_thumb_a, $get_current_download_image_thumb_b, $get_current_download_image_thumb_c, $get_current_download_image_thumb_d, $get_current_download_image_file_a, $get_current_download_image_file_b, $get_current_download_image_file_c, $get_current_download_image_file_d, $get_current_download_read_more_url, $get_current_download_main_category_id, $get_current_download_sub_category_id, $get_current_download_dir, $get_current_download_internal_external, $get_current_download_file, $get_current_download_file_external_url, $get_current_download_type, $get_current_download_version, $get_current_download_file_size, $get_current_download_file_date, $get_current_download_last_download, $get_current_download_hits, $get_current_download_unique_hits, $get_current_download_ip_block, $get_current_download_tag_a, $get_current_download_tag_b, $get_current_download_tag_c, $get_current_download_created_datetime, $get_current_download_have_to_be_logged_in_to_download) = $row;

if($get_current_download_id == ""){
	echo"Not found";
}
else{
	// Zipped
	if(!(is_dir("../_zipped"))){
		mkdir("../_zipped");
	}

	// Editor language
	/*
	if($editor_language == ""){
		$query_t = "SELECT language_active_id, language_active_iso_two FROM $t_languages_active WHERE language_active_default='1'";
		$result_t = mysqli_query($link, $query_t);
		$row_t = mysqli_fetch_row($result_t);
		list($get_language_active_id, $get_language_active_iso_two) = $row_t;
		$editor_language = "$get_language_active_iso_two";
	}
	*/

	// $editor_language_mysql = quote_smart($link, $get_current_download_language);

	// Main category
	$stmt = $mysqli->prepare("SELECT main_category_id, main_category_title, main_category_icon_path, main_category_icon_file FROM $t_downloads_main_categories WHERE main_category_id=?"); 
	$stmt->bind_param("s", $get_current_download_main_category_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_icon_path, $get_current_main_category_icon_file) = $row;
	if(!(is_dir("../_zipped/$get_current_main_category_title_clean"))){
		mkdir("../_zipped/$get_current_main_category_title_clean");
	}

	// Main category translation
	$stmt = $mysqli->prepare("SELECT main_category_translation_id, main_category_translation_value FROM $t_downloads_main_categories_translations WHERE main_category_id=? AND main_category_translation_language=?"); 
	$stmt->bind_param("ss", $get_current_download_main_category_id, $editor_language);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_current_main_category_translation_id, $get_current_main_category_translation_value) = $row;

	// Sub category
	$stmt = $mysqli->prepare("SELECT sub_category_id, sub_category_title FROM $t_downloads_sub_categories WHERE sub_category_id=?"); 
	$stmt->bind_param("s", $get_current_download_sub_category_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_current_sub_category_id, $get_sub_category_title) = $row;
	if($get_current_sub_category_id == ""){
		echo"<div class=\"info\"><p>Please choose a sub category for the download. Current is $get_current_download_main_category_id<br />$query</p></div>";
	}
	else{
		if(!(is_dir("../_zipped/$get_current_main_category_title_clean/$get_current_sub_category_title_clean"))){
			mkdir("../_zipped/$get_current_main_category_title_clean/$get_current_sub_category_title_clean");
		}
	}

	// Sub category translation
	$stmt = $mysqli->prepare("SELECT sub_category_translation_id, sub_category_translation_value FROM $t_downloads_sub_categories_translations WHERE sub_category_id=? AND sub_category_translation_language=?"); 
	$stmt->bind_param("ss", $get_current_download_sub_category_id, $editor_language);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_current_sub_category_translation_id, $get_current_sub_category_translation_value) = $row;
	if($get_current_sub_category_translation_id == ""){
		echo"<div class=\"info\"><p>Missing sub category translation... Creating it!</p></div>";
		
		$inp_value = "$get_current_sub_category_title";

		$stmt = $mysqli->prepare("INSERT INTO $t_downloads_sub_categories_translations
			(sub_category_translation_id, sub_category_id, sub_category_translation_language, sub_category_translation_value) 
			VALUES 
			(NULL,?,?,?)");
		$stmt->bind_param("sss", $get_current_download_sub_category_id, $editor_language, $inp_value); 
		$stmt->execute();

	}


	if($action == ""){
		if($process == "1"){
			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			if(empty($inp_title)){
				echo"No title";die;
			}

			$inp_title_length = strlen($inp_title);

			if($inp_title_length  > 27){
				$inp_title_short = substr($inp_title, 0, 27);
				$inp_title_short = $inp_title_short . "...";
			}
			else{
				$inp_title_short = "";
			}

			$inp_introduction = $_POST['inp_introduction'];
			$inp_introduction = output_html($inp_introduction);
			
			$inp_description = $_POST['inp_description'];

			$inp_language = $_POST['inp_language'];
			$inp_language = output_html($inp_language);

			$inp_main_category_id = $_POST['inp_main_category_id'];
			$inp_main_category_id = output_html($inp_main_category_id);

			$inp_sub_category_id = $_POST['inp_sub_category_id'];
			$inp_sub_category_id = output_html($inp_sub_category_id);
			if($inp_sub_category_id == ""){ $inp_sub_category_id = "0"; }

			$inp_read_more_url = $_POST['inp_read_more_url'];
			$inp_read_more_url = output_html($inp_read_more_url);

			$inp_tags = $_POST['inp_tags'];
			$inp_tags = output_html($inp_tags);
			$inp_tags_array = explode(" ", $inp_tags);
			$size = sizeof($inp_tags_array);
			
			$inp_tag_a = "";
			$inp_tag_b = "";
			$inp_tag_c = "";

			if($size == "0"){
			}
			elseif($size == "1"){
				$inp_tag_a = $inp_tags_array[0];
			}
			elseif($size == "2"){
				$inp_tag_a = $inp_tags_array[0];
				$inp_tag_b = $inp_tags_array[1];
			}
			else{
				$inp_tag_a = $inp_tags_array[0];
				$inp_tag_b = $inp_tags_array[1];
				$inp_tag_c = $inp_tags_array[2];
			}
			
			$inp_have_to_be_logged_in_to_download = $_POST['inp_have_to_be_logged_in_to_download'];
			$inp_have_to_be_logged_in_to_download = output_html($inp_have_to_be_logged_in_to_download);
			

			// Datetime
			$datetime = date("Y-m-d H:i:s");
			$date_print = date('j M Y');
			$datetime_saying = date("j M Y H:i");

			// Update
			$stmt = $mysqli->prepare("UPDATE $t_downloads_index SET 
				download_title=?,
				download_title_short=?,
				download_title_length=?, 
				download_language=?, 
				download_introduction=?, 
				download_description=?,
				download_read_more_url=?, 
				download_main_category_id=?, 
				download_sub_category_id=?,
				download_tag_a=?, 
				download_tag_b=?, 
				download_tag_c=?, 
				download_updated_datetime=?, 
				download_updated_print=?,
				download_have_to_be_logged_in_to_download=?
				WHERE download_id=?");
			$stmt->bind_param("sssssssssssssss", 
				$inp_title, 
				$inp_title_short, 
				$inp_title_length, 
				$inp_language, 
				$inp_introduction, 
				$inp_description,
				$inp_read_more_url, 
				$inp_main_category_id,
				$inp_sub_category_id,
				$inp_tag_a,
				$inp_tag_b,
				$inp_tag_c,
				$datetime,
				$date_print,
				$inp_have_to_be_logged_in_to_download,
				$get_current_download_id
				); 
			$stmt->execute();


			// Title
			if(!(file_exists("_translations/site/$inp_language/downloads/ts_downloads.php"))){
				$input="<?php \$l_downloads = \"Downloads\"; ?>";
				$fh = fopen("_translations/site/$inp_language/downloads/ts_downloads.php", "w") or die("can not open file");
				fwrite($fh, $input);
				fclose($fh); 
			}
			include("_translations/site/$inp_language/downloads/ts_downloads.php");
		
			// Search engine
			$inp_index_title = "$inp_title | $l_downloads";
			$inp_index_title_mysql = quote_smart($link, $inp_index_title);
	
			$inp_index_keywords_mysql = quote_smart($link, $inp_tags);




			$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name='downloads' AND index_reference_name='download_id' AND index_reference_id=$get_current_download_id";
			$result_exists = mysqli_query($link, $query_exists);
			$row_exists = mysqli_fetch_row($result_exists);
			list($get_index_id) = $row_exists;
			if($get_index_id != ""){
				$stmt = $mysqli->prepare("UPDATE $t_search_engine_index SET 
						index_title=?,
						index_short_description=?, 
						index_keywords=?, 
						index_updated_datetime=?,
						index_updated_datetime_print=?
						WHERE index_id=?");
				$stmt->bind_param("ssssss", $inp_index_title, $inp_introduction, $inp_index_keywords, $datetime, $datetime_saying, $get_index_id); 
				$stmt->execute();

			}


			$url = "index.php?open=downloads&page=edit_download&download_id=$download_id&main_category_id=$main_category_id&sub_category_id=$sub_category_id&l=$l&editor_language=$editor_language&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;
		}

		echo"
		<h1>$get_current_download_title</h1>
		<!-- Where am I -->
			<p>
			<a href=\"index.php?open=$open&amp;page=downloads&amp;main_category_id=$get_current_download_main_category_id&l=$l&amp;editor_language=$editor_language\">Downloads</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=downloads_2_open_main_category&amp;main_category_id=$get_current_download_main_category_id&l=$l&amp;editor_language=$editor_language\">$get_current_main_category_translation_value</a>
			&gt;";
			if($get_current_sub_category_id != ""){
				echo"
				<a href=\"index.php?open=$open&amp;page=downloads_3_open_sub_category&amp;main_category_id=$get_current_download_main_category_id&sub_category_id=$get_current_sub_category_id&amp;l=$l&amp;editor_language=$editor_language\">$get_current_sub_category_translation_value</a>
				&gt;
				";
			}
			echo"
			<a href=\"index.php?open=$open&amp;page=edit_download&amp;download_id=$get_current_download_id&amp;main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&l=$l&amp;editor_language=$editor_language\">$get_current_download_title</a>
			</p>
		<!-- //Where am I -->
		
		<!-- Menu -->
			<div class=\"tabs\">
				<ul>
					<li><a href=\"index.php?open=$open&amp;page=edit_download&amp;download_id=$get_current_download_id&amp;main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&l=$l&amp;editor_language=$editor_language\" class=\"active\">Edit</a></li>
					<li><a href=\"index.php?open=$open&amp;page=edit_download&amp;action=file&amp;download_id=$get_current_download_id&amp;main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&l=$l&amp;editor_language=$editor_language\">File</a></li>
					<li><a href=\"index.php?open=$open&amp;page=edit_download&amp;action=images&amp;download_id=$get_current_download_id&amp;main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&l=$l&amp;editor_language=$editor_language\">Images</a></li>
					<li><a href=\"index.php?open=$open&amp;page=edit_download&amp;action=delete&amp;download_id=$get_current_download_id&amp;main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&l=$l&amp;editor_language=$editor_language\">Delete</a></li>
				</ul>
			</div>
			<div class=\"clear\"></div>
		<!-- //Menu -->

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
		
		
		<!-- Edit download form -->
			<!-- TinyMCE -->
				<script type=\"text/javascript\" src=\"_javascripts/tinymce/tinymce.min.js\"></script>
				<script>
				tinymce.init({
					selector: 'textarea.editor',
					plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern help',
					toolbar: 'formatselect | bold italic strikethrough forecolor backcolor permanentpen formatpainter | link image media pageembed | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment',
					image_advtab: true,
					content_css: [
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
					height: 500,
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

			<!-- Focus -->
			<script>
			window.onload = function() {
				document.getElementById(\"inp_title\").focus();
			}
			</script>
			<!-- //Focus -->

			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;download_id=$download_id&amp;sub_category_id=$get_current_download_sub_category_id&amp;main_category_id=$get_current_download_main_category_id&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>Title:</b><br />
			<input type=\"text\" name=\"inp_title\" id=\"inp_title\" value=\"$get_current_download_title\" size=\"25\" style=\"width: 99%;\" />
			</p>

			<p><b>Introduction:</b><br />
			<textarea name=\"inp_introduction\" rows=\"5\" cols=\"40\">$get_current_download_introduction</textarea>
			</p>

			<p><b>Description:</b><br />
			<textarea name=\"inp_description\" rows=\"20\" cols=\"40\" class=\"editor\">$get_current_download_description</textarea>
			</p>

			<p>Language:<br />
			<select name=\"inp_language\">\n";
			$query = "SELECT language_active_id, language_active_name, language_active_iso_two FROM $t_languages_active";
			$result = $mysqli->query($query);
			while($row = $result->fetch_row()) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two) = $row;
				echo"		<option value=\"$get_language_active_iso_two\""; if($get_language_active_iso_two == "$get_current_download_language"){ echo" selected=\"selected\""; } echo">$get_language_active_name</option>\n";
			}
			echo"</select>

			<p>Main category:<br />
			<select name=\"inp_main_category_id\">\n";
			$query = "SELECT main_category_id, main_category_title FROM $t_downloads_main_categories ORDER BY main_category_title ASC";
			$result = $mysqli->query($query);
			while($row = $result->fetch_row()) {
				list($get_main_category_id, $get_main_category_title) = $row;
				echo"		<option value=\"$get_main_category_id\""; if($get_main_category_id == "$get_current_download_main_category_id"){ echo" selected=\"selected\""; } echo">$get_main_category_title</option>\n";
			}
			echo"</select>

			<p>Sub category:<br />
			<select name=\"inp_sub_category_id\">
			<option value=\"\""; if($get_current_download_sub_category_id == ""){ echo" selected=\"selected\""; } echo"> </option>\n";

			$query = "SELECT sub_category_id, sub_category_title FROM $t_downloads_sub_categories WHERE sub_category_parent_id='$get_current_download_main_category_id' ORDER BY sub_category_title ASC";
			$result = $mysqli->query($query);
			while($row = $result->fetch_row()) {
				list($get_sub_category_id, $get_sub_category_title) = $row;
				echo"		<option value=\"$get_sub_category_id\""; if($get_sub_category_id == "$get_current_download_sub_category_id"){ echo" selected=\"selected\""; } echo">$get_sub_category_title</option>\n";
			}
			echo"</select>

			<p>Read more URL:<br />
			<input type=\"text\" name=\"inp_read_more_url\" value=\"$get_current_download_read_more_url\" size=\"25\" />
			</p>

			<p>Tags:<br />
			<input type=\"text\" name=\"inp_tags\" value=\"$get_current_download_tag_a $get_current_download_tag_b $get_current_download_tag_c\" size=\"25\" />
			</p>

			<p>Have to be logged in to download:<br />
			<input type=\"radio\" name=\"inp_have_to_be_logged_in_to_download\" value=\"1\""; if($get_current_download_have_to_be_logged_in_to_download == "1"){ echo" checked=\"checked\""; } echo" /> Yes
			&nbsp;
			<input type=\"radio\" name=\"inp_have_to_be_logged_in_to_download\" value=\"0\""; if($get_current_download_have_to_be_logged_in_to_download == "0" OR $get_current_download_have_to_be_logged_in_to_download == ""){ echo" checked=\"checked\""; } echo" /> No
			</p>

			<p>
			<input type=\"submit\" value=\"Save\" class=\"btn_default\" />
			</p>

			</form>
		<!-- //Edit download form -->
	";
	} // action == ""
	elseif($action == "file"){
		if($process == "1"){
			
			
			// Get extention
			function getExtension($str) {
				$i = strrpos($str,".");
				if (!$i) { return ""; } 
				$l = strlen($str) - $i;
				$ext = substr($str,$i+1,$l);
				return $ext;
			}
			function format_size($bytes) {
        			if ($bytes >= 1073741824){
            				$bytes = number_format($bytes / 1073741824, 1) . ' GB';
        			}
        			elseif ($bytes >= 1048576) {
            				$bytes = number_format($bytes / 1048576, 1) . ' MB';
       				}
        			elseif ($bytes >= 1024) {
            				$bytes = number_format($bytes / 1024, 1) . ' KB';
        			}
        			elseif ($bytes > 1){
            				$bytes = $bytes . ' bytes';
        			}
        			elseif ($bytes == 1){
            				$bytes = $bytes . ' byte';
        			}
        			else{
            				$bytes = '0 bytes';
       				}
				return $bytes;
			}



			// Version
			$inp_version = $_POST['inp_version'];
			$inp_version = output_html($inp_version);

			$stmt = $mysqli->prepare("UPDATE $t_downloads_index SET download_version=? WHERE download_id=?");
			$stmt->bind_param("ss", $inp_version, $get_current_download_id); 
			$stmt->execute();

			// External 
			$inp_internal_external = $_POST['inp_internal_external'];
			$inp_internal_external = output_html($inp_internal_external);
			
			if($inp_internal_external == "internal"){

				// File
				$file = $_FILES['inp_file']['name'];

				// Dir
				if($get_current_sub_category_title_clean == ""){
					$inp_download_dir = "_zipped/$get_current_main_category_title_clean";
				}
				else{
					$inp_download_dir = "_zipped/$get_current_main_category_title_clean/$get_current_sub_category_title_clean";
				}

				// Type
				$inp_type = getExtension($file);

				// File
				$inp_file = str_replace(".$inp_type", "", $file);

				// Upload
			
				if (($inp_type != "zip") && ($inp_type != "rar") && ($inp_type != "pdf") && ($inp_type != "docx") && ($inp_type != "xlsx") && ($inp_type != "txt")) {
					$url = "index.php?open=downloads&page=edit_download&download_id=$download_id&action=file&main_category_id=$main_category_id&sub_category_id=$sub_category_id&l=$l&editor_language=$editor_language&ft=error&fm=unknown_file_format";
					header("Location: $url");
					exit;
				}
				else{
					$tmp_name = $_FILES['inp_file']['tmp_name'];
  					if (move_uploaded_file($tmp_name, "../$inp_download_dir/$inp_file.$inp_type")){


						// File size
						$inp_file_size = format_size (filesize("../$inp_download_dir/$inp_file.$inp_type"));

						// File date
						$inp_file_date = date('Y-m-d',filemtime("../$inp_download_dir/$inp_file.$inp_type"));

					
						$inp_file_date_print = date('j M Y',filemtime("../$inp_download_dir/$inp_file.$inp_type"));

						// Now date
						$datetime = date("Y-m-d H:i:s");
						$date_print = date('j M Y');

						$inp_internal_external = "internal";
						$inp_file_external_url = "";

						// Update
						$stmt = $mysqli->prepare("UPDATE $t_downloads_index SET 
								download_internal_external=?,
								download_file_external_url=?,
								download_dir=?, 
								download_file=?, 	
								download_type=?,  
								download_file_size=?, 
								download_file_date=?, 
								download_file_date_print=?,
								download_updated_datetime=?, 
								download_updated_print=? 
								WHERE download_id=?");
						$stmt->bind_param("sssssssssss", $inp_internal_external,
							$inp_file_external_url,
							$inp_download_dir,
							$inp_file,
							$inp_type,
							$inp_file_size,
							$inp_file_date,
							$inp_file_date_print,
							$datetime,
							$date_print,
							$get_current_download_id
							); 
						$stmt->execute();
				
						$url = "index.php?open=downloads&page=edit_download&download_id=$download_id&action=file&main_category_id=$main_category_id&sub_category_id=$sub_category_id&l=$l&editor_language=$editor_language&ft=success&fm=changes_saved";
						header("Location: $url");
						exit;
					}
					else{
						$error = $_FILES["inp_file"]["error"];
						$url = "index.php?open=downloads&page=edit_download&download_id=$download_id&action=file&main_category_id=$main_category_id&sub_category_id=$sub_category_id&l=$l&editor_language=$editor_language&ft=error&fm=$error";
						header("Location: $url");
						exit;
					}
				}
			} // internal
			else{
				// External URL
				$inp_file_external_url = $_POST['inp_file_external_url'];
				$inp_file_external_url = output_html($inp_file_external_url);
				// Type
				$inp_type = substr($inp_file_external_url, strrpos($inp_file_external_url, '.') + 1);

				// File -> 
				$inp_file = substr($inp_file_external_url, strrpos($inp_file_external_url, '/') + 1);
				$inp_file = str_replace(".$inp_type", "", $inp_file);

				// Now date
				$datetime = date("Y-m-d H:i:s");
				$date_print = date('j M Y');

				$inp_internal_external = "external";
				$inp_dir = "";
				$inp_file_size = 0;

				// Update
				$stmt = $mysqli->prepare("UPDATE $t_downloads_index SET 
					download_internal_external=?,
					download_file_external_url=?,
					download_dir=?, 
					download_file=?, 	
					download_type=?,  
					download_file_size=?, 
					download_file_date=?, 
					download_file_date_print=?, 
					download_updated_datetime=?, 
					download_updated_print=?
					WHERE download_id=?");
				$stmt->bind_param("ssssssssss", $inp_internal_external,
					$inp_file_external_url,
					$inp_dir,
					$inp_file,
					$inp_type,
					$inp_file_size,
					$datetime,
					$date_print,
					$datetime,
					$date_print,
					$get_current_download_id); 
				$stmt->execute();



			} // external


			$url = "index.php?open=downloads&page=edit_download&download_id=$download_id&action=file&main_category_id=$main_category_id&sub_category_id=$sub_category_id&l=$l&editor_language=$editor_language&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;

		}

		echo"
		<h1>$get_current_download_title</h1>
		<!-- Where am I -->
			<p>
			<a href=\"index.php?open=$open&amp;page=downloads&amp;main_category_id=$get_current_download_main_category_id&l=$l&amp;editor_language=$editor_language\">Downloads</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=downloads_2_open_main_category&amp;main_category_id=$get_current_download_main_category_id&l=$l&amp;editor_language=$editor_language\">$get_current_main_category_translation_value</a>
			&gt;";
			if($get_current_sub_category_id != ""){
				echo"
				<a href=\"index.php?open=$open&amp;page=downloads_3_open_sub_category&amp;main_category_id=$get_current_download_main_category_id&sub_category_id=$get_current_sub_category_id&amp;l=$l&amp;editor_language=$editor_language\">$get_current_sub_category_translation_value</a>
				&gt;
				";
			}
			echo"
			<a href=\"index.php?open=$open&amp;page=edit_download&amp;download_id=$get_current_download_id&amp;main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&l=$l&amp;editor_language=$editor_language\">$get_current_download_title</a>
			</p>
		<!-- //Where am I -->
		
		<!-- Menu -->
			<div class=\"tabs\">
				<ul>
					<li><a href=\"index.php?open=$open&amp;page=edit_download&amp;download_id=$get_current_download_id&amp;main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&l=$l&amp;editor_language=$editor_language\">Edit</a></li>
					<li><a href=\"index.php?open=$open&amp;page=edit_download&amp;action=file&amp;download_id=$get_current_download_id&amp;main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&l=$l&amp;editor_language=$editor_language\" class=\"active\">File</a></li>
					<li><a href=\"index.php?open=$open&amp;page=edit_download&amp;action=images&amp;download_id=$get_current_download_id&amp;main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&l=$l&amp;editor_language=$editor_language\">Images</a></li>
					<li><a href=\"index.php?open=$open&amp;page=edit_download&amp;action=delete&amp;download_id=$get_current_download_id&amp;main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&l=$l&amp;editor_language=$editor_language\">Delete</a></li>
				</ul>
			</div>
			<div class=\"clear\"></div>
		<!-- //Menu -->

		<!-- Feedback -->
		";
		if($ft != "" && $fm != ""){
			if($fm == "1"){
				$fm = "UPLOAD_ERR_INI_SIZE = Value: 1; The uploaded file exceeds the upload_max_filesize directive in php.ini.";
			}
			elseif($fm == "1"){
				$fm = "UPLOAD_ERR_FORM_SIZE = Value: 2; The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
			}
			elseif($fm == "2"){
				$fm = "UPLOAD_ERR_PARTIAL = Value: 3; The uploaded file was only partially uploaded.";
			}
			elseif($fm == "4"){
				$fm = "UPLOAD_ERR_NO_FILE = Value: 4; No file was uploaded.";
			}
			elseif($fm == "6"){
				$fm = "UPLOAD_ERR_NO_TMP_DIR = Value: 6; Missing a temporary folder. Introduced in PHP 5.0.3.";
			}
			elseif($fm == "7"){
				$fm = "UPLOAD_ERR_CANT_WRITE = Value: 7; Failed to write file to disk. Introduced in PHP 5.1.0.";
			}
			elseif($fm == "8"){
				$fm = "UPLOAD_ERR_EXTENSION = Value: 8; A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop; examining the list of loaded extensions with phpinfo() may help.";
			}
			elseif($fm == "category_deleted"){
				$fm = "Category deleted";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><p>$fm</p></div>";
		}
		echo"
		<!-- //Feedback -->

		<!-- File info -->
			";
			if($get_current_download_internal_external == "external" OR file_exists("../$get_current_download_dir/$get_current_download_file.$get_current_download_type")){
				echo"
				<div style=\"border: #ccc 1px solid;padding: 0px 10px 0px 10px;margin-top: 20px;\">
					<p>";
					if($get_current_download_internal_external == "internal"){
						echo"
						<b>File:</b> <a href=\"../$get_current_download_dir/$get_current_download_file.$get_current_download_type\">$get_current_download_file.$get_current_download_type</a><br />
						<b>Version:</b> $get_current_download_version<br />
						<b>Size:</b> $get_current_download_file_size<br />
						<b>Date:</b> $get_current_download_file_date
						";
					}
					else{
						echo"
						<b>File:</b> <a href=\"$get_current_download_file_external_url\">$get_current_download_file.$get_current_download_type</a><br />
						<b>Version:</b> $get_current_download_version<br />
						<b>Date:</b> $get_current_download_file_date
						";
					}
					echo"</p>
				</div>
				";
			}
			echo"
		<!-- //File info -->
		
		<!-- Edit File -->
			<h2>New file</h2>
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;download_id=$download_id&amp;sub_category_id=$get_current_download_sub_category_id&amp;main_category_id=$get_current_download_main_category_id&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

			
			<p><b>Version:</b><br />";
			$inp_version = "1.0.0";
			if($get_current_download_version != ""){
				$version_array = explode(".", $get_current_download_version);
				$size = sizeof($version_array);
				$minor = $version_array[$size-1];
				$new_minor = $minor+1;

				$inp_version = "";
				for($x=0;$x<$size-1;$x++){
					if($inp_version  == ""){
						$inp_version = $version_array[$x];
					}
					else{
						$inp_version = $inp_version . "." . $version_array[$x];
					}
				}
				$inp_version = $inp_version . "." . $new_minor;
			}
			echo"
			<input type=\"text\" name=\"inp_version\" value=\"$inp_version\" size=\"25\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</p>

			<p>Internal or external file:<br />
			<input type=\"radio\" name=\"inp_internal_external\" value=\"internal\" onclick=\"toggleInternalExternalFileDivs()\" "; if($get_current_download_internal_external == "internal"){ echo" checked=\"checked\""; } echo" />
			Internal
			&nbsp;
			<input type=\"radio\" name=\"inp_internal_external\" value=\"external\" onclick=\"toggleInternalExternalFileDivs()\" "; if($get_current_download_internal_external == "external"){ echo" checked=\"checked\""; } echo" />
			External
			</p>


			<!-- Javascript select internal/external -->
				<script>
				function toggleInternalExternalFileDivs() {
						var internalFileDiv = document.getElementById(\"internal_file_div\");
						var externalFileDiv = document.getElementById(\"external_file_div\");
						var checkDisplay = document.getElementById(\"internal_file_div\").style.display;
						if(checkDisplay == \"\" || checkDisplay == \"none\"){
							internalFileDiv.style.display = 'block';
							externalFileDiv.style.display = 'none';
						}
						else{
							internalFileDiv.style.display = 'none';
							externalFileDiv.style.display = 'block';
						}
				}
				</script>
			<!-- //Hide show nav + change hamburger icon -->


			<div id=\"internal_file_div\""; if($get_current_download_internal_external == "external"){ echo"style=\"display: none;\""; } echo">
				<p>Internal file:<br />
				<input type=\"file\" name=\"inp_file\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
				</p>
			</div>


			<div id=\"external_file_div\""; if($get_current_download_internal_external == "internal"){ echo"style=\"display: none;\""; } echo">
				<p>External file:<br />
				<input type=\"text\" name=\"inp_file_external_url\" size=\"25\" value=\"$get_current_download_file_external_url\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" style=\"width: 99%;\" />
				</p>
			</div>


			<p>
			<input type=\"submit\" value=\"Save\" class=\"btn_default\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</p>

			</form>
		<!-- //Edit File -->
	";
	} // action == "file"
	elseif($action == "images"){
		if($process == "1"){

			if(isset($_GET['mode'])) {
				$mode = $_GET['mode'];
				$mode = strip_tags(stripslashes($mode));
			}
			else{
				$mode = "";
			}

			// Remove old thumbs
			if(file_exists("../$get_current_download_image_path/$get_download_image_store_thumb") && $get_download_image_store_thumb != ""){
				unlink("../$get_current_download_image_path/$get_download_image_store_thumb");
			}
			if(file_exists("../$get_current_download_image_path/$get_current_download_image_thumb_a") && $get_current_download_image_thumb_a != ""){
				unlink("../$get_current_download_image_path/$get_current_download_image_thumb_a");
			}
			if(file_exists("../$get_current_download_image_path/$get_current_download_image_thumb_b") && $get_current_download_image_thumb_b != ""){
				unlink("../$get_current_download_image_path/$get_current_download_image_thumb_b");
			}
			if(file_exists("../$get_current_download_image_path/$get_current_download_image_thumb_c") && $get_current_download_image_thumb_c != ""){
				unlink("../$get_current_download_image_path/$get_current_download_image_thumb_c");
			}
			if(file_exists("../$get_current_download_image_path/$get_current_download_image_thumb_d") && $get_current_download_image_thumb_d != ""){
				unlink("../$get_current_download_image_path/$get_current_download_image_thumb_d");
			}


			// Dir
			if(!(is_dir("../_zipped/$get_current_main_category_title_clean"))){
				mkdir("../_zipped/$get_current_main_category_title_clean");
			}
			if($get_current_sub_category_title_clean == ""){
				$inp_img_path = "_zipped/$get_current_main_category_title_clean/_gfx";
				if(!(is_dir("../_zipped/$get_current_main_category_title_clean/_gfx"))){
					mkdir("../_zipped/$get_current_main_category_title_clean/_gfx");
				}
			}
			else{
				$inp_img_path = "_zipped/$get_current_main_category_title_clean/$get_current_sub_category_title_clean/_gfx";
				if(!(is_dir("../_zipped/$get_current_main_category_title_clean/$get_current_sub_category_title_clean"))){
					mkdir("../_zipped/$get_current_main_category_title_clean/$get_current_sub_category_title_clean");
				}
				if(!(is_dir("../_zipped/$get_current_main_category_title_clean/$get_current_sub_category_title_clean/_gfx"))){
					mkdir("../_zipped/$get_current_main_category_title_clean/$get_current_sub_category_title_clean/_gfx");
				}
			}
			$inp_img_path_mysql = quote_smart($link, $inp_img_path);


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
					if($mode == "upload_store_image"){
						$full_dest  = "../$inp_img_path/" . $get_current_download_id . "_store_image." . $extension;
					}
					elseif($mode == "img_a"){
						$full_dest  = "../$inp_img_path/" . $get_current_download_id . "_img_a." . $extension;
					}
					elseif($mode == "img_b"){
						$full_dest  = "../$inp_img_path/" . $get_current_download_id . "_img_b." . $extension;
					}
					elseif($mode == "img_c"){
						$full_dest  = "../$inp_img_path/" . $get_current_download_id . "_img_c." . $extension;
					}
					elseif($mode == "img_d"){
						$full_dest  = "../$inp_img_path/" . $get_current_download_id . "_img_d." . $extension;
					}
					else{
						$ft_image = "warning";
						$fm_image = "unknown_mode";
					}
  					if (move_uploaded_file($tmp_name, $full_dest)){

						list($width,$height) = @getimagesize("$full_dest");

						if($width == "" OR $height == ""){
							$ft_image = "warning";
							$fm_image = "photo_could_not_be_uploaded_please_check_file_size";
							unlink("$full_dest");
						}
						else{							
							// Update image
							if($mode == "upload_store_image"){
								$inp_img = $get_current_download_id . "_store_image." . $extension;

								$inp_img_thumb = $get_current_download_id . "_store_image_thumb." . $extension;


								$stmt = $mysqli->prepare("UPDATE $t_downloads_index SET download_image_path=?, 
									download_image_store=?, download_image_store_thumb=? WHERE download_id=?");
								$stmt->bind_param("ssss", $inp_img_path, $inp_img, $inp_img_thumb, $get_current_download_id); 
								$stmt->execute();

							}
							elseif($mode == "img_a"){
								$inp_img = $get_current_download_id . "_img_a." . $extension;
								$inp_img_mysql = quote_smart($link, $inp_img);

								$inp_img_thumb = $get_current_download_id . "_img_a_thumb." . $extension;
								$inp_img_thumb_mysql = quote_smart($link, $inp_img_thumb);

								
								$stmt = $mysqli->prepare("UPDATE $t_downloads_index SET 
									download_image_path=?, download_image_file_a=?, download_image_thumb_a=? WHERE download_id=?");
								$stmt->bind_param("ssss", $inp_img_path, $inp_img, $inp_img_thumb, $get_current_download_id); 
								$stmt->execute();

								
							}
							elseif($mode == "img_b"){
								$inp_img = $get_current_download_id . "_img_b." . $extension;

								$inp_img_thumb = $get_current_download_id . "_img_b_thumb." . $extension;

								$stmt = $mysqli->prepare("UPDATE $t_downloads_index SET 
									download_image_path=?, download_image_file_b=?, download_image_thumb_b=? WHERE download_id=?");
								$stmt->bind_param("ssss", $inp_img_path, $inp_img, $inp_img_thumb, $get_current_download_id); 
								$stmt->execute();

							}
							elseif($mode == "img_c"){
								$inp_img = $get_current_download_id . "_img_c." . $extension;

								$inp_img_thumb = $get_current_download_id . "_img_c_thumb." . $extension;

								$stmt = $mysqli->prepare("UPDATE $t_downloads_index SET 
									download_image_path=?, download_image_file_c=?, download_image_thumb_c=? WHERE download_id=?");
								$stmt->bind_param("ssss", $inp_img_path, $inp_img, $inp_img_thumb, $get_current_download_id); 
								$stmt->execute();

							}
							elseif($mode == "img_d"){
								$inp_img = $get_current_download_id . "_img_d." . $extension;

								$inp_img_thumb = $get_current_download_id . "_img_d_thumb." . $extension;

								
								$stmt = $mysqli->prepare("UPDATE $t_downloads_index SET 
									download_image_path=?, download_image_file_d=?, download_image_thumb_d=? WHERE download_id=?");
								$stmt->bind_param("ssss", $inp_img_path, $inp_img, $inp_img_thumb, $get_current_download_id); 
								$stmt->execute();

							}

							$ft_image = "success";
							$fm_image = "image_uploaded";


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
			$url = "index.php?open=downloads&page=edit_download&action=images&download_id=$download_id&main_category_id=$main_category_id&sub_category_id=$sub_category_id&l=$l&editor_language=$editor_language&ft=$ft_image&fm=$fm_image";
			header("Location: $url");
			exit;

		} // process == 1

		echo"
		<h1>$get_current_download_title</h1>
		<!-- Where am I -->
			<p>
			<a href=\"index.php?open=$open&amp;page=downloads&amp;main_category_id=$get_current_download_main_category_id&l=$l&amp;editor_language=$editor_language\">Downloads</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=downloads_2_open_main_category&amp;main_category_id=$get_current_download_main_category_id&l=$l&amp;editor_language=$editor_language\">$get_current_main_category_translation_value</a>
			&gt;";
			if($get_current_sub_category_id != ""){
				echo"
				<a href=\"index.php?open=$open&amp;page=downloads_3_open_sub_category&amp;main_category_id=$get_current_download_main_category_id&sub_category_id=$get_current_sub_category_id&amp;l=$l&amp;editor_language=$editor_language\">$get_current_sub_category_translation_value</a>
				&gt;
				";
			}
			echo"
			<a href=\"index.php?open=$open&amp;page=edit_download&amp;download_id=$get_current_download_id&amp;main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&l=$l&amp;editor_language=$editor_language\">$get_current_download_title</a>
			</p>
		<!-- //Where am I -->
		
		<!-- Menu -->
			<div class=\"tabs\">
				<ul>
					<li><a href=\"index.php?open=$open&amp;page=edit_download&amp;download_id=$get_current_download_id&amp;main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&l=$l&amp;editor_language=$editor_language\">Edit</a></li>
					<li><a href=\"index.php?open=$open&amp;page=edit_download&amp;action=file&amp;download_id=$get_current_download_id&amp;main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&l=$l&amp;editor_language=$editor_language\">File</a></li>
					<li><a href=\"index.php?open=$open&amp;page=edit_download&amp;action=images&amp;download_id=$get_current_download_id&amp;main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&l=$l&amp;editor_language=$editor_language\" class=\"active\">Images</a></li>
					<li><a href=\"index.php?open=$open&amp;page=edit_download&amp;action=delete&amp;download_id=$get_current_download_id&amp;main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&l=$l&amp;editor_language=$editor_language\">Delete</a></li>
				</ul>
			</div>
			<div class=\"clear\"></div>
		<!-- //Menu -->

		<!-- Feedback -->
		";
		if($ft != "" && $fm != ""){
			if($fm == "image_uploaded"){
				$fm = "Image uploaded";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><p>$fm</p></div>";
		}
		echo"
		<!-- //Feedback -->

		<!-- Store image -->
			<h2>Store image</h2>
			";
			if(file_exists("../$get_current_download_image_path/$get_download_image_store") && $get_download_image_store != ""){
				// Thumb
				if(!(file_exists("../$get_current_download_image_path/$get_download_image_store_thumb"))){
					$inp_new_x = 229;
					$inp_new_y = 131;
					resize_crop_image($inp_new_x, $inp_new_y, "../$get_current_download_image_path/$get_download_image_store", "../$get_current_download_image_path/$get_download_image_store_thumb");
				}
				echo"
				<a href=\"../$get_current_download_image_path/$get_download_image_store\"><img src=\"../$get_current_download_image_path/$get_download_image_store_thumb\" alt=\"$get_current_download_image_path/$get_download_image_store\" /></a>
				";
			}
			echo"
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;mode=upload_store_image&amp;download_id=$download_id&amp;main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">
			<p>New store image 616x353:<br />
			<input name=\"inp_image\" type=\"file\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			<input type=\"submit\" value=\"Upload\" class=\"btn_default\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</p>
			</form>
		<!-- //Store image -->

		<!-- Img A -->
			<h2>Img A</h2>
			";
			if(file_exists("../$get_current_download_image_path/$get_current_download_image_file_a") && $get_current_download_image_file_a != ""){
				// Thumb
				if(!(file_exists("../$get_current_download_image_path/$get_current_download_image_thumb_a"))){
					$inp_new_x = 115;
					$inp_new_y = 66;
					resize_crop_image($inp_new_x, $inp_new_y, "../$get_current_download_image_path/$get_current_download_image_file_a", "../$get_current_download_image_path/$get_current_download_image_thumb_a");
				}
				echo"
				<a href=\"../$get_current_download_image_path/$get_current_download_image_file_a\"><img src=\"../$get_current_download_image_path/$get_current_download_image_thumb_a\" alt=\"$get_current_download_image_thumb_a\" /></a>";
			}
			echo"
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;mode=img_a&amp;download_id=$download_id&amp;main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">
			<p>New img A 1110x636:<br />
			<input name=\"inp_image\" type=\"file\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			<input type=\"submit\" value=\"Upload\" class=\"btn_default\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</p>
			</form>
		<!-- //Img A -->

		<!-- Img B -->
			<h2>Img B</h2>
			";
			if(file_exists("../$get_current_download_image_path/$get_current_download_image_file_b") && $get_current_download_image_file_b != ""){
				// Thumb
				if(!(file_exists("../$get_current_download_image_path/$get_current_download_image_thumb_b"))){
					$inp_new_x = 115;
					$inp_new_y = 66;
					resize_crop_image($inp_new_x, $inp_new_y, "../$get_current_download_image_path/$get_current_download_image_file_b", "../$get_current_download_image_path/$get_current_download_image_thumb_b");
				}
				echo"
				<a href=\"../$get_current_download_image_path/$get_current_download_image_file_b\"><img src=\"../$get_current_download_image_path/$get_current_download_image_thumb_b\" alt=\"$get_current_download_image_thumb_b\" /></a>";
			}
			echo"
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;mode=img_b&amp;download_id=$download_id&amp;main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">
			<p>New img B 1110x636:<br />
			<input name=\"inp_image\" type=\"file\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			<input type=\"submit\" value=\"Upload\" class=\"btn_default\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</p>
			</form>
		<!-- //Img B -->
		

		<!-- Img C -->
			<h2>Img C</h2>
			";
			if(file_exists("../$get_current_download_image_path/$get_current_download_image_file_c") && $get_current_download_image_file_c != ""){
				// Thumb
				if(!(file_exists("../$get_current_download_image_path/$get_current_download_image_thumb_c"))){
					$inp_new_x = 115;
					$inp_new_y = 66;
					resize_crop_image($inp_new_x, $inp_new_y, "../$get_current_download_image_path/$get_current_download_image_file_c", "../$get_current_download_image_path/$get_current_download_image_thumb_c");
				}
				echo"
				<a href=\"../$get_current_download_image_path/$get_current_download_image_file_c\"><img src=\"../$get_current_download_image_path/$get_current_download_image_thumb_c\" alt=\"$get_current_download_image_thumb_c\" /></a>";
			}
			echo"
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;mode=img_c&amp;download_id=$download_id&amp;main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">
			<p>New img C 1110x636:<br />
			<input name=\"inp_image\" type=\"file\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			<input type=\"submit\" value=\"Upload\" class=\"btn_default\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</p>
			</form>
		<!-- //Img C -->

		<!-- Img D -->
			<h2>Img D</h2>
			";
			if(file_exists("../$get_current_download_image_path/$get_current_download_image_file_d") && $get_current_download_image_file_d != ""){
				// Thumb
				if(!(file_exists("../$get_current_download_image_path/$get_current_download_image_thumb_d"))){
					$inp_new_x = 115;
					$inp_new_y = 66;
					resize_crop_image($inp_new_x, $inp_new_y, "../$get_current_download_image_path/$get_current_download_image_file_d", "../$get_current_download_image_path/$get_current_download_image_thumb_d");
				}
				echo"
				<a href=\"../$get_current_download_image_path/$get_current_download_image_file_d\"><img src=\"../$get_current_download_image_path/$get_current_download_image_thumb_d\" alt=\"$get_current_download_image_thumb_d\" /></a>";
			}
			echo"
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;mode=img_d&amp;download_id=$download_id&amp;main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">
			<p>New img D 1110x636:<br />
			<input name=\"inp_image\" type=\"file\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			<input type=\"submit\" value=\"Upload\" class=\"btn_default\" tabindex=\""; $tabindex=$tabindex+1; echo"$tabindex\" />
			</p>
			</form>
		<!-- //Img D -->
	";
	} // action == "images"
	elseif($action == "delete"){
		if($process == "1"){
			// Update
			$mysqli->query("DELETE FROM $t_downloads_index WHERE download_id='$get_current_download_id'") or die($mysqli->error);

			$url = "index.php?open=downloads&page=downloads_2_open_main_category&main_category_id=$get_current_download_main_category_id&l=$l&editor_language=$editor_language&ft=success&fm=download_deleted";
			header("Location: $url");
			exit;
		}

		echo"
		<h1>$get_current_download_title</h1>
		<!-- Where am I -->
			<p>
			<a href=\"index.php?open=$open&amp;page=downloads&amp;main_category_id=$get_current_download_main_category_id&l=$l&amp;editor_language=$editor_language\">Downloads</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=downloads_2_open_main_category&amp;main_category_id=$get_current_download_main_category_id&l=$l&amp;editor_language=$editor_language\">$get_current_main_category_translation_value</a>
			&gt;";
			if($get_current_sub_category_id != ""){
				echo"
				<a href=\"index.php?open=$open&amp;page=downloads_3_open_sub_category&amp;main_category_id=$get_current_download_main_category_id&sub_category_id=$get_current_sub_category_id&amp;l=$l&amp;editor_language=$editor_language\">$get_current_sub_category_translation_value</a>
				&gt;
				";
			}
			echo"
			<a href=\"index.php?open=$open&amp;page=edit_download&amp;download_id=$get_current_download_id&amp;main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&l=$l&amp;editor_language=$editor_language\">$get_current_download_title</a>
			</p>
		<!-- //Where am I -->
		
		<!-- Menu -->
			<div class=\"tabs\">
				<ul>
					<li><a href=\"index.php?open=$open&amp;page=edit_download&amp;download_id=$get_current_download_id&amp;main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&l=$l&amp;editor_language=$editor_language\">Edit</a></li>
					<li><a href=\"index.php?open=$open&amp;page=edit_download&amp;action=file&amp;download_id=$get_current_download_id&amp;main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&l=$l&amp;editor_language=$editor_language\">File</a></li>
					<li><a href=\"index.php?open=$open&amp;page=edit_download&amp;action=images&amp;download_id=$get_current_download_id&amp;main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&l=$l&amp;editor_language=$editor_language\">Images</a></li>
					<li><a href=\"index.php?open=$open&amp;page=edit_download&amp;action=delete&amp;download_id=$get_current_download_id&amp;main_category_id=$get_current_download_main_category_id&amp;sub_category_id=$get_current_download_sub_category_id&l=$l&amp;editor_language=$editor_language\" class=\"active\">Delete</a></li>
				</ul>
			</div>
			<div class=\"clear\"></div>
		<!-- //Menu -->

		
		
		<!-- Delete File -->
			<h2>Delete</h2>

			<p>Are you sure?</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;download_id=$download_id&amp;sub_category_id=$get_current_download_sub_category_id&amp;main_category_id=$get_current_download_main_category_id&amp;l=$l&amp;editor_language=$editor_language&amp;process=1\" class=\"btn_warning\">Confirm</a>
			</p>
		<!-- //Delete File -->
	";
	} // action == delete
} // download found
?>