<?php
/**
*
* File: _admin/_inc/tasks_templates.php
* Version 1.0.1
* Date 14:50 23.03.2021
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
$t_tasks_index  		= $mysqlPrefixSav . "tasks_index";
$t_tasks_status_codes  		= $mysqlPrefixSav . "tasks_status_codes";
$t_tasks_projects  		= $mysqlPrefixSav . "tasks_projects";
$t_tasks_projects_parts  	= $mysqlPrefixSav . "tasks_projects_parts";
$t_tasks_systems  		= $mysqlPrefixSav . "tasks_systems";
$t_tasks_systems_parts  	= $mysqlPrefixSav . "tasks_systems_parts";
$t_tasks_templates	  	= $mysqlPrefixSav . "tasks_templates";



/*- Variables  ---------------------------------------------------- */
if(isset($_GET['template_id'])) {
	$template_id = $_GET['template_id'];
	$template_id = strip_tags(stripslashes($template_id));
	if(!(is_numeric($template_id))){
		echo"Template id not numeric"; 
		die;
	}
}
else{
	$template_id = "";
}


if($action == ""){
	echo"
	<h1>Tasks templates</h1>

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l\">Templates</a>
		</p>
	<!-- //Where am I? -->


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

	<!-- Menu -->
		<p><a href=\"index.php?open=$open&amp;page=$page&amp;action=new&amp;l=$l\" class=\"btn_default\">New template</a></p>
	<!-- Menu -->

	<!-- Templates -->
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span><b>Title</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Active</b></span>
		   </td>
		   <th scope=\"col\">
			<span><b>Actions</b></span>
		   </td>
		  </tr>
		 </thead>
		 <tbody>
			";
			$y=1;
			$query = "SELECT template_id, template_language, template_title, template_text, template_active, template_created_by_user_id, template_created_datetime, template_updated_by_user_id, template_updated_datetime FROM $t_tasks_templates ORDER BY template_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_template_id, $get_template_language, $get_template_title, $get_template_text, $get_template_active, $get_template_created_by_user_id, $get_template_created_datetime, $get_template_updated_by_user_id, $get_template_updated_datetime) = $row;
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
					<span>$get_template_title</span>
				  </td>
				  <td class=\"$style\">
					<span>";
					if($get_template_active == "1"){
						echo"Yes";
					}
					else{
						echo"No";
					}
					echo"</span>
				  </td>
				  <td class=\"$style\">
					<span>
					<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit&amp;template_id=$get_template_id&amp;l=$l\">Edit</a>
					|
					<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete&amp;template_id=$get_template_id&amp;l=$l\">Delete</a>
					</span>
				  </td>
				 </tr>
				";

				$y++;
			}
			echo"
		 </tbody>
		</table>
	<!-- //Templates -->
	";
}
elseif($action == "new"){
	if($process == 1){

		$inp_language = output_html($l);
		$inp_language_mysql = quote_smart($link, $inp_language);

		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

		$inp_text = $_POST['inp_text'];
		$inp_active = $_POST['inp_active'];
		$inp_active = output_html($inp_active);
		$inp_active_mysql = quote_smart($link, $inp_active);
		
		$my_user_id = $_SESSION['admin_user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);

		$datetime = date("Y-m-d H:i:s");

		// Insert
		mysqli_query($link, "INSERT INTO $t_tasks_templates
		(template_id, template_language, template_title, template_active, template_created_by_user_id, template_created_datetime) 
		VALUES 
		(NULL, $inp_language_mysql, $inp_title_mysql, $inp_active_mysql, $my_user_id_mysql, '$datetime')")
		or die(mysqli_error($link));

		// Get ID
		$query = "SELECT template_id, template_language, template_title, template_text, template_active, template_created_by_user_id, template_created_datetime, template_updated_by_user_id, template_updated_datetime FROM $t_tasks_templates WHERE template_created_datetime='$datetime'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_template_id, $get_current_template_language, $get_current_template_title, $get_current_template_text, $get_current_template_active, $get_current_template_created_by_user_id, $get_current_template_created_datetime, $get_current_template_updated_by_user_id, $get_current_template_updated_datetime) = $row;


		// Text
		$sql = "UPDATE $t_tasks_templates SET template_text=? WHERE template_id=$get_current_template_id";
		$stmt = $link->prepare($sql);
		$stmt->bind_param("s", $inp_text);
		$stmt->execute();
		if ($stmt->errno) {
			echo "FAILURE!!! " . $stmt->error; die;
		}


		header("Location: index.php?open=dashboard&page=$page&ft=success&fm=created");
		exit;
	}
	$tabindex = 0;
	echo"
	<h1>New project</h1>

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l\">Templates</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;l=$l\">New</a>
		</p>
	<!-- //Where am I? -->

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

	<!-- Focus -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_title\"]').focus();
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

	<!-- New form -->
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
		<p>Title:<br />
		<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" />
		</p>

		<p>Text:<br />
		<textarea name=\"inp_text\" rows=\"6\" cols=\"50\" class=\"editor\"></textarea>
		</p>


		<p>Active:<br />
		<input type=\"radio\" name=\"inp_active\" value=\"1\" checked=\"checked\" /> Yes
		<input type=\"radio\" name=\"inp_active\" value=\"0\" /> No
		</p>

		<p><input type=\"submit\" value=\"Create\" class=\"btn_default\" /></p>

		</form>
	<!-- //New form -->

	";
} // new
elseif($action == "edit"){
	// Get ID
	$template_id_mysql = quote_smart($link, $template_id);
	$query = "SELECT template_id, template_language, template_title, template_text, template_active, template_created_by_user_id, template_created_datetime, template_updated_by_user_id, template_updated_datetime FROM $t_tasks_templates WHERE template_id=$template_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_template_id, $get_current_template_language, $get_current_template_title, $get_current_template_text, $get_current_template_active, $get_current_template_created_by_user_id, $get_current_template_created_datetime, $get_current_template_updated_by_user_id, $get_current_template_updated_datetime) = $row;

	if($get_current_template_id == ""){
		echo"<p>404 server error</p>";
	}
	else{
		if($process == 1){

			$inp_language = output_html($l);
			$inp_language_mysql = quote_smart($link, $inp_language);

			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);
	
			$inp_text = $_POST['inp_text'];

			$inp_active = $_POST['inp_active'];
			$inp_active = output_html($inp_active);
			$inp_active_mysql = quote_smart($link, $inp_active);
		
			$my_user_id = $_SESSION['admin_user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);

			$datetime = date("Y-m-d H:i:s");
			


			$result = mysqli_query($link, "UPDATE $t_tasks_templates SET
					template_language=$inp_language_mysql, 
					template_title=$inp_title_mysql, 
					template_active=$inp_active_mysql,
					template_updated_by_user_id=$my_user_id_mysql, 
					template_updated_datetime='$datetime'
					
					WHERE template_id=$get_current_template_id") or die(mysqli_error($link));

			// Text
			$sql = "UPDATE $t_tasks_templates SET template_text=? WHERE template_id=$get_current_template_id";
			$stmt = $link->prepare($sql);
			$stmt->bind_param("s", $inp_text);
			$stmt->execute();
			if ($stmt->errno) {
				echo "FAILURE!!! " . $stmt->error; die;
			}




			header("Location: index.php?open=dashboard&page=$page&action=$action&template_id=$get_current_template_id&ft=success&fm=changes_saved");
			exit;
		}
		echo"
		<h1>$get_current_template_title</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l\">Templates</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;template_id=$get_current_template_id&amp;l=$l\">$get_current_template_title</a>
			</p>
		<!-- //Where am I? -->

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
		<!-- Focus -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
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
		<!-- Edit form -->
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;template_id=$get_current_template_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			<p>Title:<br />
			<input type=\"text\" name=\"inp_title\" value=\"$get_current_template_title\" size=\"25\" />
			</p>

		

			<p>Text:<br />
			<textarea name=\"inp_text\" rows=\"6\" cols=\"50\" class=\"editor\">$get_current_template_text</textarea>
			</p>

	
			<p>Active:<br />
			<input type=\"radio\" name=\"inp_active\" value=\"1\""; if($get_current_template_active == "1"){ echo" checked=\"checked\""; } echo" /> Yes
			<input type=\"radio\" name=\"inp_active\" value=\"0\""; if($get_current_template_active == "0"){ echo" checked=\"checked\""; } echo" /> No
			</p>


			<p><input type=\"submit\" value=\"Save changes\" class=\"btn_default\" /></p>

			</form>
		<!-- //Edit form -->
		";
	}
} // edit
elseif($action == "delete"){
	// Get ID
	$template_id_mysql = quote_smart($link, $template_id);
	$query = "SELECT template_id, template_language, template_title, template_text, template_active, template_created_by_user_id, template_created_datetime, template_updated_by_user_id, template_updated_datetime FROM $t_tasks_templates WHERE template_id=$template_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_template_id, $get_current_template_language, $get_current_template_title, $get_current_template_text, $get_current_template_active, $get_current_template_created_by_user_id, $get_current_template_created_datetime, $get_current_template_updated_by_user_id, $get_current_template_updated_datetime) = $row;

	if($get_current_template_id == ""){
		echo"<p>404 server error</p>";
	}
	else{
		if($process == 1){

			
			$result = mysqli_query($link, "DELETE FROM $t_tasks_templates WHERE template_id=$get_current_template_id");


			header("Location: index.php?open=dashboard&page=$page&ft=success&fm=deleted");
			exit;
		}
		echo"
		<h1>$get_current_template_title</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l\">Templates</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;template_id=$get_current_template_id&amp;l=$l\">$get_current_template_title</a>
			</p>
		<!-- //Where am I? -->

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
		

		<!-- Delete form -->
			<p>
			Are you sure you want to delete?
			</p>

			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;template_id=$get_current_template_id&amp;l=$l&amp;process=1\" class=\"btn_default\" />Confirm</a>
			</p>

		<!-- //Delete form -->
		";
	}
} // delete
?>