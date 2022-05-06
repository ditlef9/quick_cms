<?php
/**
*
* File: _admin/_inc/pages/new_page.php
* Version 1.0 
* Date 18:50 29.10.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Variables ------------------------------------------------------------------------ */

if($process == "1"){
	$inp_page_title = $_POST['inp_page_title'];
	$inp_page_title = output_html($inp_page_title);
	$inp_page_title_mysql = quote_smart($link, $inp_page_title);

	$inp_page_language = $_POST['inp_page_language'];
	$inp_page_language = output_html($inp_page_language);
	$inp_page_language_mysql = quote_smart($link, $inp_page_language);
	$editor_language = $inp_page_language;

	$inp_page_slug = clean($inp_page_title);
	$inp_page_slug_mysql = quote_smart($link, $inp_page_slug);

	$inp_page_parent_id = $_POST['inp_page_parent_id'];
	$inp_page_parent_id = output_html($inp_page_parent_id);
	$inp_page_parent_id_mysql = quote_smart($link, $inp_page_parent_id);

	$inp_page_content = $_POST['inp_page_content'];
	//$inp_page_content = output_html($inp_page_content);
	//$inp_page_content_mysql = quote_smart($link, $inp_page_content);

	$inp_page_created = date("Y-m-d H:i:s");
	$inp_page_created_mysql = quote_smart($link, $inp_page_created);

	$inp_page_created_by_user_id = $_SESSION['admin_user_id'];
	$inp_page_created_by_user_id = output_html($inp_page_created_by_user_id);
	$inp_page_created_by_user_id_mysql = quote_smart($link, $inp_page_created_by_user_id);

	$inp_page_comments_active = $_POST['inp_page_comments_active'];
	$inp_page_comments_active = output_html($inp_page_comments_active);
	if($inp_page_comments_active == "on"){ $inp_page_comments_active = "1"; }
	else{ $inp_page_comments_active = "0"; }
	$inp_page_comments_active_mysql = quote_smart($link, $inp_page_comments_active);
	

	$inp_page_no_of_columns = $_POST['inp_page_no_of_columns'];
	$inp_page_no_of_columns = output_html($inp_page_no_of_columns);
	$inp_page_no_of_columns_mysql = quote_smart($link, $inp_page_no_of_columns);

	// Insert
	mysqli_query($link, "INSERT INTO $t_pages
	(page_id, page_title, page_language, page_slug, page_parent_id, page_created, page_created_by_user_id, page_updated_by_user_id, page_allow_comments, page_no_of_columns, page_no_of_comments, page_uniqe_hits) 
	VALUES 
	(NULL, $inp_page_title_mysql, $inp_page_language_mysql, $inp_page_slug_mysql, $inp_page_parent_id_mysql, $inp_page_created_mysql, $inp_page_created_by_user_id_mysql, $inp_page_created_by_user_id_mysql, $inp_page_comments_active_mysql, $inp_page_no_of_columns_mysql, '0', '0')")
	or die(mysqli_error($link));

	// Get page ID
	$query = "SELECT page_id FROM $t_pages WHERE page_created=$inp_page_created_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_page_id) = $row;

	// Insert content
	$sql = "UPDATE $t_pages SET page_content=? WHERE page_id='$get_page_id'";
	$stmt = $link->prepare($sql);
	$stmt->bind_param("s", $inp_page_content);
	$stmt->execute();
	if ($stmt->errno) {
		echo "FAILURE!!! " . $stmt->error; die;
	}

	
	// Parent?
	$final_directory = "";
	$final_file_name = "";

	if($inp_page_parent_id == 0){
		
		// Path for flat file
		$final_directory = "../$inp_page_slug";
		$final_file_name = "index.php";

		$inp_page_path = str_replace("../", "", $final_directory);
		$inp_page_path_mysql = quote_smart($link, $inp_page_path);

		$inp_page_file_name = "$final_file_name";
		$inp_page_file_name_mysql = quote_smart($link, $inp_page_file_name);


		// No parent
		$result = mysqli_query($link, "UPDATE $t_pages SET page_path=$inp_page_path_mysql, page_file_name=$inp_page_file_name_mysql, page_no_of_children='0', page_child_level='1' WHERE page_id='$get_page_id'");



	}
	else{

		// Find parent
		$query = "SELECT page_id, page_parent_id, page_path, page_file_name, page_no_of_children, page_child_level FROM $t_pages WHERE page_id=$inp_page_parent_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_parent_page_id, $get_parent_page_parent_id, $get_parent_page_path, $get_parent_page_file_name, $get_parent_page_no_of_children, $get_parent_page_child_level) = $row;
		
		// Update number of children
		$inp_parent_page_no_of_children = $get_parent_page_no_of_children+1;
		$result = mysqli_query($link, "UPDATE $t_pages SET page_no_of_children='$inp_parent_page_no_of_children' WHERE page_id=$inp_page_parent_id_mysql");
		

		if($get_parent_page_child_level == "1"){
			// Path for flat file
			$final_directory = "../$get_parent_page_path";
			$final_file_name = "$inp_page_slug.php";

			
			
		}
		elseif($get_parent_page_child_level == "2"){
			// Path for flat file
			$final_directory = "../$get_parent_page_path/$inp_page_slug";
			$final_file_name = "index.php";
		}
		elseif($get_parent_page_child_level == "3"){
			// Path for flat file
			$final_directory = "../$get_parent_page_path";
			$final_file_name = "$inp_page_slug.php";
		}
		else{
			echo"<p>Parent error</p>";
		}

		// Update child level
		$inp_page_child_level = $get_parent_page_child_level+1;

		// Update current path
		$inp_page_path = str_replace("../", "", $final_directory);
		$inp_page_path_mysql = quote_smart($link, $inp_page_path);

		$inp_page_file_name = "$final_file_name";
		$inp_page_file_name_mysql = quote_smart($link, $inp_page_file_name);

		$result = mysqli_query($link, "UPDATE $t_pages SET page_path=$inp_page_path_mysql, page_file_name=$inp_page_file_name_mysql, page_child_level=$inp_page_child_level WHERE page_id=$get_page_id");
	
	} // if($inp_page_parent_id == 0){


	// Image path
	if($inp_page_parent_id == 0){
		$inp_image_path_path = "$inp_page_slug/_img";
		$inp_image_path_path_mysql = quote_smart($link, $inp_image_path_path);

		$inp_image_path_title = "$inp_page_slug";
		$inp_image_path_title_mysql = quote_smart($link, $inp_image_path_title);


		// Check that the path exists in image paths
		$query = "SELECT image_path_id FROM $t_images_paths WHERE image_path_path=$inp_image_path_path_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_image_path_id) = $row;
			
		if($get_image_path_id == ""){
			// Insert image path
			mysqli_query($link, "INSERT INTO $t_images_paths
			(image_path_id, image_path_title, image_path_parent_id, image_path_path) 
			VALUES 
			(NULL, $inp_image_path_title_mysql, '0', $inp_image_path_path_mysql)")
			or die(mysqli_error($link));
		}

	}
	else{
		/*
		if($get_parent_page_child_level == "1"){
			$inp_image_path_path = "$get_parent_page_path/_img";
			$inp_image_path_path_mysql = quote_smart($link, $inp_image_path_path);

			$inp_image_path_title = "$get_parent_page_path";
			$inp_image_path_title_mysql = quote_smart($link, $inp_image_path_title);
		}
		elseif($get_parent_page_child_level == "2"){
			$inp_image_path_path = "$get_parent_page_path/$inp_page_slug/_img";
			$inp_image_path_path_mysql = quote_smart($link, $inp_image_path_path);
	
			$inp_image_path_title = "$inp_page_slug";
			$inp_image_path_title_mysql = quote_smart($link, $inp_image_path_title);
		}
		elseif($get_parent_page_child_level == "3"){
			$inp_image_path_path = "$get_parent_page_path/_img";
			$inp_image_path_path_mysql = quote_smart($link, $inp_image_path_path);

			$inp_image_path_title = "$get_parent_page_path";
			$inp_image_path_title_mysql = quote_smart($link, $inp_image_path_title);
		}
		*/
	}



	// Make directory
	if(!(file_exists("../$inp_page_path"))){
		mkdir("../$inp_page_path");
	}



	// Make flat file
	$inp_page_content_flat_file = str_replace('"', '\"', $inp_page_content);
	$text_value="<?php 
/**
*
* File: $inp_page_path/$final_file_name
* Version 
* Date $inp_page_created
* $configWebsiteCopyrightSav
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration ---------------------------------------------------------------------------- */
\$pageIdSav            = \"$get_page_id\";
\$pageNoColumnSav      = \"$inp_page_no_of_columns\";
\$pageAllowCommentsSav = \"$inp_page_comments_active\";
\$pageAuthorUserIdSav  = \"$inp_page_created_by_user_id\";

/*- Root dir --------------------------------------------------------------------------------- */
// This determine where we are
if(file_exists(\"favicon.ico\")){ \$root = \".\"; }
elseif(file_exists(\"../favicon.ico\")){ \$root = \"..\"; }
elseif(file_exists(\"../../favicon.ico\")){ \$root = \"../..\"; }
elseif(file_exists(\"../../../favicon.ico\")){ \$root = \"../../..\"; }
elseif(file_exists(\"../../../../favicon.ico\")){ \$root = \"../../../..\"; }
else{ \$root = \"../../..\"; }

/*- Website config --------------------------------------------------------------------------- */
include(\"\$root/_admin/website_config.php\");

/*- Headers ---------------------------------------------------------------------------------- */
\$website_title = \"$inp_page_title\";
if(file_exists(\"./favicon.ico\")){ \$root = \".\"; }
elseif(file_exists(\"../favicon.ico\")){ \$root = \"..\"; }
elseif(file_exists(\"../../favicon.ico\")){ \$root = \"../..\"; }
elseif(file_exists(\"../../../favicon.ico\")){ \$root = \"../../..\"; }
include(\"\$root/_webdesign/header.php\");

/*- Content ---------------------------------------------------------------------------------- */
echo\"
$inp_page_content_flat_file
\";

/*- Footer ----------------------------------------------------------------------------------- */
include(\"\$root/_webdesign/footer.php\");
?>";
	$fh = fopen("$final_directory/$final_file_name", "w") or die("can not open file");
	fwrite($fh, $text_value);
	fclose($fh);


	header("Location: index.php?open=$open&editor_language=$editor_language&ft=success&fm=page_created");

} // process

	
echo"
<h1>$l_new_page</h1>

<!-- Focus -->
	<script>
	\$(document).ready(function(){
		\$('[name=\"inp_page_title\"]').focus();
	});
	</script>
<!-- //Focus -->

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

<!-- Form -->
	<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">
	

	<!-- Content Left -->
		<div class=\"content_left\">

			<p><b>$l_title</b><br />
			<input type=\"text\" name=\"inp_page_title\" value=\"\" size=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p>
			<textarea name=\"inp_page_content\" rows=\"10\" cols=\"70\" class=\"editor\"></textarea>
			</p>


			<p><input type=\"submit\" value=\"$l_publish\" class=\"submit\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
	
		</div>

	<!-- //Content Left -->
	

	<!-- Content Right -->
		<div class=\"content_right\">

			<!-- Attributtes -->

				<div class=\"content_right_box\">
					<h2>Attributtes</h2>

					<p>$l_language<br />
					<select name=\"inp_page_language\" tabindex=\"";$tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\" />";
		
					$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;
						echo"	<option value=\"$get_language_active_iso_two\"";if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
						
					}
					echo"
					</select>
					

					<p>$l_parent<br />
					<select name=\"inp_page_parent_id\" tabindex=\"";$tabindex=0; $tabindex=$tabindex+1;echo"$tabindex\" />
						<option value=\"0\" selected=\"selected\">$l_this_is_parent</option>
						<option value=\"0\">-</option>";
		
						$editor_language_mysql = quote_smart($link, $editor_language);
						$query = "SELECT page_id, page_title FROM $t_pages WHERE page_parent_id='0' AND page_language=$editor_language_mysql";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_parent_page_id, $get_parent_page_title) = $row;

							echo"		<option value=\"$get_parent_page_id\">$get_parent_page_title</option>\n";


							$query_b = "SELECT page_id, page_title FROM $t_pages WHERE page_parent_id='$get_parent_page_id' AND page_language=$editor_language_mysql";
							$result_b = mysqli_query($link, $query_b);
							while($row_b = mysqli_fetch_row($result_b)) {
								list($get_b_parent_page_id, $get_b_parent_page_title) = $row_b;

								echo"		<option value=\"$get_b_parent_page_id\">&nbsp; $get_b_parent_page_title</option>\n";

								$query_c = "SELECT page_id, page_title FROM $t_pages WHERE page_parent_id='$get_b_parent_page_id' AND page_language=$editor_language_mysql";
								$result_c = mysqli_query($link, $query_c);
								while($row_c = mysqli_fetch_row($result_c)) {
									list($get_c_parent_page_id, $get_c_parent_page_title) = $row_c;

									echo"		<option value=\"$get_c_parent_page_id\">&nbsp; &nbsp; $get_c_parent_page_title</option>\n";

								}

							}

						}
					echo"
					</select>
					</p>

					<p>$l_allow_comments<br />
					<input type=\"checkbox\" name=\"inp_page_comments_active\" checked=\"checked\" /> $l_yes
					</p>
						
					<p>$l_no_of_columns<br />
					<select name=\"inp_page_no_of_columns\">
						<option value=\"1\">1</option>
						<option value=\"2\" selected=\"selected\">2</option>
					</select>
					</p>
				</div>
		</div>
	<!-- //Content Right -->


	</form>


	";
?>