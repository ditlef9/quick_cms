<?php
/**
*
* File: _admin/_inc/notes_new_page.php
* Version 1
* Date 14:58 02.04.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */
$t_notes_categories   = $mysqlPrefixSav . "notes_categories";
$t_notes_pages	      = $mysqlPrefixSav . "notes_pages";
$t_notes_pages_images = $mysqlPrefixSav . "notes_pages_images";
$t_notes_pages_files  = $mysqlPrefixSav . "notes_pages_files";

/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['page_id'])) {
	$page_id = $_GET['page_id'];
	$page_id = strip_tags(stripslashes($page_id));
	if(!(is_numeric($page_id))){
		echo"page id not numeric";
		die;
	}
}
else{
	$page_id = "";
}
$page_id_mysql = quote_smart($link, $page_id);
$query = "SELECT page_id, page_title, page_category_id, page_weight, page_parent_id, page_text, page_created_datetime, page_created_by_user_id, page_updated_datetime, page_updated_by_user_id FROM $t_notes_pages WHERE page_id=$page_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_page_id, $get_current_page_title, $get_current_page_category_id, $get_current_page_weight, $get_current_page_parent_id, $get_current_page_text, $get_current_page_created_datetime, $get_current_page_created_by_user_id, $get_current_page_updated_datetime, $get_current_page_updated_by_user_id) = $row;


if($get_current_page_id == ""){
	echo"page not found";
}
else{
	// Get category
	$query = "SELECT category_id, category_title, category_weight, category_bg_color, category_border_color, category_title_color, category_pages_bg_color, category_pages_bg_color_hover, category_pages_bg_color_active, category_pages_border_color, category_pages_border_color_hover, category_pages_border_color_active, category_pages_title_color, category_pages_title_color_hover, category_pages_title_color_active, category_created_datetime, category_created_by_user_id, category_updated_datetime, category_updated_by_user_id FROM $t_notes_categories WHERE category_id=$get_current_page_category_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_category_id, $get_current_category_title, $get_current_category_weight, $get_current_category_bg_color, $get_current_category_border_color, $get_current_category_title_color, $get_current_category_pages_bg_color, $get_current_category_pages_bg_color_hover, $get_current_category_pages_bg_color_active, $get_current_category_pages_border_color, $get_current_category_pages_border_color_hover, $get_current_category_pages_border_color_active, $get_current_category_pages_title_color, $get_current_category_pages_title_color_hover, $get_current_category_pages_title_color_active, $get_current_category_created_datetime, $get_current_category_created_by_user_id, $get_current_category_updated_datetime, $get_current_category_updated_by_user_id) = $row;


	if($process == "1"){
		$datetime = date("Y-m-d H:i:s");

		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);
	
		
		$inp_text = $_POST['inp_text'];
	
		// Me
		$my_user_id = $_SESSION['user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);


		$sql = "UPDATE $t_notes_pages SET page_title=?, page_text=?, page_updated_datetime='$datetime', page_updated_by_user_id=$my_user_id_mysql WHERE page_id=$get_current_page_id";
		$stmt = $link->prepare($sql);
		$stmt->bind_param("ss", $inp_title, $inp_text);
		$stmt->execute();
		if ($stmt->errno) {
			echo "FAILURE!!! " . $stmt->error; die;
		}

		// Header
		$url = "index.php?open=dashboard&page=notes_open_page&page_id=$get_current_page_id&editor_language=$editor_language&l=$l&ft=success&fm=changes_saved";
		header("Location: $url");
		exit;
	}


	echo"
	<h1>Notes</h1>

	<!-- Where am I ? -->
		<p><b>$l_you_are_here</b><br />
		<a href=\"index.php?open=$open&amp;page=notes&amp;editor_language=$editor_language&amp;l=$l\">Notes</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=notes_open_category&amp;category_id=$get_current_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_category_title</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=notes_open_page&amp;page_id=$get_current_page_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_page_title</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=notes_edit_page&amp;page_id=$get_current_page_id&amp;editor_language=$editor_language&amp;l=$l\">Edit page</a>
		</p>
	<!-- //Where am I ? -->

	<!-- Categories -->
		<div class=\"tabs\">
			<ul>";
			$query_u = "SELECT category_id, category_title, category_weight, category_bg_color, category_border_color, category_title_color, category_pages_bg_color, category_pages_bg_color_hover, category_pages_bg_color_active, category_pages_border_color, category_pages_border_color_hover, category_pages_border_color_active, category_pages_title_color, category_pages_title_color_hover, category_pages_title_color_active, category_created_datetime, category_created_by_user_id, category_updated_datetime, category_updated_by_user_id FROM $t_notes_categories ORDER BY category_weight ASC";
			$result_u = mysqli_query($link, $query_u);
			while($row_u = mysqli_fetch_row($result_u)) {
				list($get_category_id, $get_category_title, $get_category_weight, $get_category_bg_color, $get_category_border_color, $get_category_title_color, $get_category_pages_bg_color, $get_category_pages_bg_color_hover, $get_category_pages_bg_color_active, $get_category_pages_border_color, $get_category_pages_border_color_hover, $get_category_pages_border_color_active, $get_category_pages_title_color, $get_category_pages_title_color_hover, $get_category_pages_title_color_active, $get_category_created_datetime, $get_category_created_by_user_id, $get_category_updated_datetime, $get_category_updated_by_user_id) = $row_u;
				echo"				";
				echo"<li><a href=\"index.php?open=$open&amp;page=notes_open_category&amp;category_id=$get_category_id&amp;editor_language=$editor_language&amp;l=$l\""; if($get_category_id == "$get_current_category_id"){ echo" class=\"active\""; } echo">$get_category_title</a>\n";
			}
			echo"
				<li><a href=\"index.php?open=$open&amp;page=notes_new_category&amp;editor_language=$editor_language&amp;l=$l\">+</a>
			</ul>
		</div>
		<div class=\"clear\"></div>
	<!-- //Categories -->


	<!-- New form -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_title\"]').focus();
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
				image_list: [\n";
				$x = 0;
				$query = "SELECT image_id, image_category_id, image_page_id, image_title, image_text, image_path, image_file FROM $t_notes_pages_images WHERE image_page_id=$get_current_page_id ORDER BY image_title ASC ";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_image_id, $get_image_category_id, $get_image_page_id, $get_image_title, $get_image_text, $get_image_path, $get_image_file) = $row;
			
					if($x > 0){
						echo",\n";
					}
					echo"					";
					echo"{ title: '$get_image_title', value: '$get_image_path/$get_image_file' }";

					$x++;
				}
				echo"
				],
				image_class_list: [
					{ title: 'None', value: '' },
					{ title: 'Some class', value: 'class-name' }
				],
				importcss_append: true,
				height: 600,
				/* without images_upload_url set, Upload tab won't show up*/
				images_upload_url: 'index.php?open=dashboard&page=notes_new_page_upload_image&category_id=$get_current_category_id&page_id=$get_current_page_id&process=1',

			});
			</script>
		<!-- //TinyMCE -->


		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;page_id=$get_current_page_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
		<p>Title:<br />
		<input type=\"text\" name=\"inp_title\" value=\"$get_current_page_title\" size=\"25\" style=\"width: 99%;\" />
		</p>

		<p>
		<textarea name=\"inp_text\" class=\"editor\" rows=\"25\" cols=\"50\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" style=\"width: 100%;\">$get_current_page_text</textarea>
		</p>

		<p><input type=\"submit\" value=\"Save\" class=\"btn_default\" /></p>

		</form>
	<!-- //New form -->
	";
} // found category


?>