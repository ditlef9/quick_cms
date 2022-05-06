<?php
/**
*
* File: _admin/_inc/comments/new_course.php
* Version 
* Date 20:17 30.10.2017
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
$tabindex = 0;


if($action == ""){


	if($process == "1"){
		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

		$inp_short_introduction = $_POST['inp_short_introduction'];
		$inp_short_introduction = output_html($inp_short_introduction);
		$inp_short_introduction_mysql = quote_smart($link, $inp_short_introduction);

		$inp_long_introduction = $_POST['inp_long_introduction'];

		$inp_contents = $_POST['inp_contents'];

		$inp_language = $_POST['inp_language'];
		$inp_language = output_html($inp_language);
		$inp_language_mysql = quote_smart($link, $inp_language);

		$inp_dir_name = $_POST['inp_dir_name'];
		$inp_dir_name = output_html($inp_dir_name);
		$inp_dir_name_mysql = quote_smart($link, $inp_dir_name);

		$inp_category_id = $_POST['inp_category_id'];
		$inp_category_id = output_html($inp_category_id);
		$inp_category_id_mysql = quote_smart($link, $inp_category_id);

		$inp_intro_video_embedded = $_POST['inp_intro_video_embedded'];
		$inp_intro_video_embedded = output_html($inp_intro_video_embedded);
		$inp_intro_video_embedded_mysql = quote_smart($link, $inp_intro_video_embedded);




		$inp_icon_a = $inp_dir_name . "_48x48.png";
		$inp_icon_a_mysql = quote_smart($link, $inp_icon_a);

		$inp_icon_b = $inp_dir_name . "_64x64.png";
		$inp_icon_b_mysql = quote_smart($link, $inp_icon_b);

		$inp_icon_c = $inp_dir_name . "_96x96.png";
		$inp_icon_c_mysql = quote_smart($link, $inp_icon_c);

		$datetime = date("Y-m-d H:i:s");
		
		mysqli_query($link, "INSERT INTO $t_courses_index
		(course_id, course_title, course_short_introduction, course_language, course_dir_name, course_category_id, course_intro_video_embedded, course_icon_48, course_icon_64, course_icon_96, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times, course_created, course_updated) 
		VALUES 
		(NULL, $inp_title_mysql, $inp_short_introduction_mysql, $inp_language_mysql, $inp_dir_name_mysql, $inp_category_id_mysql, $inp_intro_video_embedded_mysql, $inp_icon_a_mysql, $inp_icon_b_mysql, $inp_icon_c_mysql, 0, 0, 0, 0, 0, '$datetime', '$datetime')")
		or die(mysqli_error($link));

		// Get ID
		$query = "SELECT course_id FROM $t_courses_index WHERE course_created='$datetime'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_course_id) = $row;


		// Long intro and content
		$sql = "UPDATE $t_courses_index SET course_long_introduction=?, course_contents=? WHERE course_id=$get_current_course_id";
		$stmt = $link->prepare($sql);
		$stmt->bind_param("ss", $inp_long_introduction, $inp_contents);
		$stmt->execute();
		if ($stmt->errno) {
			echo "FAILURE!!! " . $stmt->error; die;
		}

		// Dir
		if(!(is_dir("../$inp_dir_name"))){
			mkdir("../$inp_dir_name");
		}

		// Create file
		$datetime_print = date("j M Y H:i");
		$year = date("Y");
		$page_id = date("ymdhis");
		if(!(file_exists("../$inp_dir_name/index.php"))){
			$input="<?php
/**
*
* File: $inp_dir_name/index.php
* Version 2.0.0
* Date $datetime_print
* Copyright (c) 2009-$year Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*- Configuration ---------------------------------------------------------------------------- */
\$pageIdSav            = \"$page_id\";
\$pageNoColumnSav      = \"2\";
\$pageAllowCommentsSav = \"0\";

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

/*- Translation ------------------------------------------------------------------------------ */
include(\"\$root/_admin/_translations/site/\$l/courses/ts_courses.php\");

/*- Headers ---------------------------------------------------------------------------------- */
\$website_title = \"$inp_title\";
if(file_exists(\"./favicon.ico\")){ \$root = \".\"; }
elseif(file_exists(\"../favicon.ico\")){ \$root = \"..\"; }
elseif(file_exists(\"../../favicon.ico\")){ \$root = \"../..\"; }
elseif(file_exists(\"../../../favicon.ico\")){ \$root = \"../../..\"; }
include(\"\$root/_webdesign/header.php\");

/* Course header ---------------------------------------------------------------------------- */
\$courseDirNameSav = \"$inp_dir_name\";

include(\"\$root/courses/_includes/course.php\");

/*- Footer ---------------------------------------------------------------------------------- */
include(\"\$root/_webdesign/footer.php\");
?>";

			$fh = fopen("../$inp_dir_name/index.php", "w+") or die("can not open file");
			fwrite($fh, $input);
			fclose($fh);
		}


		// Header
		$url = "index.php?open=$open&page=open_category&category_id=$inp_category_id&editor_language=$editor_language&ft=success&fm=course_created";
		header("Location: $url");
		exit;
	}

	echo"
	<h1>New course</h1>
				

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
		<a href=\"index.php?open=courses&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Courses</a>
		&gt;
		<a href=\"index.php?open=courses&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">All courses</a>
		&gt;
		<a href=\"index.php?open=courses&amp;page=courses_new&amp;editor_language=$editor_language&amp;l=$l\">New course</a>
		</p>
	<!-- //Where am I? -->


	<!-- New course form -->
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

		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_title\"]').focus();
		});
		</script>
			
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

		<p><b>Title:</b><br />
		<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>Short introduction:</b><br />
		<textarea name=\"inp_short_introduction\" rows=\"8\" cols=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\"></textarea>
		</p>

		<p><b>Long introduction:</b><br />
		<textarea name=\"inp_long_introduction\" rows=\"8\" cols=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" class=\"editor\"></textarea>
		</p>

		<p><b>Contents:</b><br />
		<textarea name=\"inp_contents\" rows=\"8\" cols=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" class=\"editor\"><ul><li><span>a</span></li><li><span>b</span></li><li><span>c</span></li></ul></textarea>
		</p>

		<p><b>Language:</b><br />
		<select name=\"inp_language\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">\n";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;
			echo"	<option value=\"$get_language_active_iso_two\">$get_language_active_name</option>\n";
		}
		echo"
		</select>

		<p><b>Directory name:</b><br />
		<input type=\"text\" name=\"inp_dir_name\" value=\"\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>Category:</b><br />
		<select name=\"inp_category_id\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">\n";
		$query = "SELECT category_id, category_title, category_description, category_language, category_created, category_updated FROM $t_courses_categories ORDER BY category_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_category_id, $get_category_title, $get_category_description, $get_category_language, $get_category_created, $get_category_updated) = $row;

			echo"	<option value=\"$get_category_id\">$get_category_title</option>\n";
		}
		echo"
		</select>

		<p><b>Intro video embedded:</b><br />
		<input type=\"text\" name=\"inp_intro_video_embedded\" value=\"\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><input type=\"submit\" value=\"Create\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>

		</form>
	<!-- //New course form -->
	";
}
?>