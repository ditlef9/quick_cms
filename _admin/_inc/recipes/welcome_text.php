<?php
/**
*
* File: _admin/_inc/recipes/welcome_text.php
* Version 1.0
* Date 10:44 13.03.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */


/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;


/*- Script start --------------------------------------------------------------------- */

if($action == ""){
	echo"
	<h1>Welcome text</h1>


	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		else{
			$fm = str_replac("_", " ", $fm);
			$fm = ucfirst($fm);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->

	<!-- Languages -->
		<p>
		";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag_path_16x16, language_active_flag_16x16 FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag_path_16x16, $get_language_active_flag_16x16) = $row;

			echo"
			<a href=\"index.php?open=$open&amp;page=welcome_text&amp;editor_language=$get_language_active_iso_two&amp;l=$l\""; if($get_language_active_iso_two == "$editor_language"){ echo" style=\"font-weight: bold;\""; } echo"><img src=\"../$get_language_active_flag_path_16x16/$get_language_active_flag_16x16\" alt=\"$get_language_active_flag_16x16\" /> $get_language_active_name</a>
			&nbsp; &nbsp; ";
		}
		echo"
		</p>
	<!-- //Languages -->


	<!-- Edit welcome text -->
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


		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=save&amp;editor_language=$editor_language&amp;l=$l\" enctype=\"multipart/form-data\">
	
		<p>
		<textarea name=\"inp_text\" rows=\"40\" cols=\"120\" class=\"editor\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";

		$file = "../_uploads/recipes/welcome_text/welcome_$editor_language.txt";
		if(file_exists("$file")){
			$myfile = fopen($file, "r") or die("Unable to open file!");
			$data = fread($myfile,filesize($file));
			fclose($myfile);
			echo"$data";
		}
		echo"</textarea>
		</p>
		
		<p>
		<input type=\"submit\" value=\"Save changes\" class=\"submit\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		</form>
	<!-- //Edit welcome text -->
 	";
} // action == "";
elseif($action == "save"){
	$inp_text = $_POST['inp_text'];
	
	if(!(is_dir("../_uploads"))){
		mkdir("../_uploads");
	}
	if(!(is_dir("../_uploads/recipes"))){
		mkdir("../_uploads/recipes");
	}
	if(!(is_dir("../_uploads/recipes/welcome_text"))){
		mkdir("../_uploads/recipes/welcome_text");
	}

	$fh = fopen("../_uploads/recipes/welcome_text/welcome_$editor_language.txt", "w") or die("can not open file");
	fwrite($fh, $inp_text);
	fclose($fh);

	echo"
	<h1><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Saving</h1>
	
	<meta http-equiv=\"refresh\" content=\"3;url=index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l&amp;ft=success&amp;fm=changes_saved\" />
	";

}
?>