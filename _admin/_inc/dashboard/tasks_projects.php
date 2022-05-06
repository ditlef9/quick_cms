<?php
/**
*
* File: _admin/_inc/tasks_projects.php
* Version 1.0.1
* Date 12:54 28.04.2019
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
$t_tasks_index  		= $mysqlPrefixSav . "tasks_index";
$t_tasks_status_codes  		= $mysqlPrefixSav . "tasks_status_codes";
$t_tasks_projects  		= $mysqlPrefixSav . "tasks_projects";
$t_tasks_projects_parts  	= $mysqlPrefixSav . "tasks_projects_parts";
$t_tasks_systems  		= $mysqlPrefixSav . "tasks_systems";
$t_tasks_systems_parts  	= $mysqlPrefixSav . "tasks_systems_parts";



/*- Variables  ---------------------------------------------------- */
if(isset($_GET['project_id'])) {
	$project_id = $_GET['project_id'];
	$project_id = strip_tags(stripslashes($project_id));
}
else{
	$project_id = "";
}
if(isset($_GET['project_part_id'])) {
	$project_part_id = $_GET['project_part_id'];
	$project_part_id = strip_tags(stripslashes($project_part_id));
}
else{
	$project_part_id = "";
}


if($action == ""){
	echo"
	<h1>Tasks projects</h1>

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l\">Projects</a>
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
		<p><a href=\"index.php?open=$open&amp;page=$page&amp;action=new_project&amp;l=$l\" class=\"btn_default\">New project</a></p>
	<!-- Menu -->

	<!-- Projects -->
		<div class=\"vertical\">
			<ul>\n";

			$query = "SELECT project_id, project_title FROM $t_tasks_projects WHERE project_is_active=1 ORDER BY project_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_project_id, $get_project_title) = $row;

				echo"
				<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=open_project&amp;project_id=$get_project_id&amp;l=$l\">$get_project_title</a></li>
				";
			}
			echo"
			</ul>
		</div>
	<!-- //Projects -->
	";
}
elseif($action == "new_project"){
	if($process == 1){

		$inp_title = $_POST['inp_title'];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

		$title_len = strlen($inp_title);
		if($title_len > 3){
			$inp_task_abbr = substr($inp_title, 0, 4);
		}
		else{
			$inp_task_abbr = "$inp_title";
		}
		$inp_task_abbr = strtoupper($inp_task_abbr);
		$inp_task_abbr = output_html($inp_task_abbr);
		$inp_task_abbr_mysql = quote_smart($link, $inp_task_abbr);


		$inp_description = $_POST['inp_description'];

		$inp_system_id = $_POST['inp_system_id'];
		$inp_system_id = output_html($inp_system_id);
		$inp_system_id_mysql = quote_smart($link, $inp_system_id);

		$datetime = date("Y-m-d H:i:s");

		// Insert
		mysqli_query($link, "INSERT INTO $t_tasks_projects
		(project_id, project_system_id, project_title, project_task_abbr, project_description, project_logo, project_is_active, project_created, project_updated) 
		VALUES 
		(NULL, $inp_system_id_mysql, $inp_title_mysql, $inp_task_abbr_mysql, '', '', 1, '$datetime', '$datetime')")
		or die(mysqli_error($link));

		// Get ID
		$query = "SELECT project_id FROM $t_tasks_projects WHERE project_created='$datetime'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_project_id) = $row;

		// Insert content
		$sql = "UPDATE $t_tasks_projects SET project_description=? WHERE project_id='$get_project_id'";
		$stmt = $link->prepare($sql);
		$stmt->bind_param("s", $inp_description);
		$stmt->execute();
		if ($stmt->errno) {
			echo "FAILURE!!! " . $stmt->error; die;
		}



		header("Location: index.php?open=dashboard&page=$page&ft=success&fm=saved");
		exit;
	}
	$tabindex = 0;
	echo"
	<h1>New project</h1>

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l\">Projects</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;l=$l\">New project</a>
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

	<!-- New project form -->";
		
		echo"
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
		<p>Title:<br />
		<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" />
		</p>

		<p>Description:<br />
		<textarea name=\"inp_description\" rows=\"10\" cols=\"80\" class=\"editor\"></textarea><br />
		</p>

		<p>Part of system <a href=\"index.php?open=$open&amp;page=tasks_systems&amp;action=new_system&amp;l=$l\" target=\"_blank\">New</a><br />
		<select name=\"inp_system_id\">";
		$query = "SELECT system_id, system_title FROM $t_tasks_systems WHERE system_is_active=1 ORDER BY system_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_system_id, $get_system_title) = $row;
			echo"			<option value=\"$get_system_id\">$get_system_title</option>\n";
		}
		echo"
		</select>
		</p>

		<p><input type=\"submit\" value=\"Create project\" class=\"btn_default\" /></p>

		</form>
	<!-- //New project form -->

	";
} // new_project
elseif($action == "open_project"){
	// Get ID
	$project_id_mysql = quote_smart($link, $project_id);
	$query = "SELECT project_id, project_title, project_description, project_logo, project_is_active, project_created, project_updated FROM $t_tasks_projects WHERE project_id=$project_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_project_id, $get_current_project_title, $get_current_project_description, $get_current_project_logo, $get_current_project_is_active, $get_current_project_created, $get_current_project_updated) = $row;

	if($get_current_project_id == ""){
		echo"<p>404 server error</p>";
	}
	else{
		echo"
		<h1>$get_current_project_title</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l\">Projects</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;project_id=$get_current_project_id&amp;l=$l\">$get_current_project_title</a>
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
			<p>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_project_part&amp;project_id=$get_current_project_id&amp;l=$l\" class=\"btn_default\">New part</a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_project&amp;project_id=$get_current_project_id&amp;l=$l\" class=\"btn_default\">Edit project</a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_project&amp;project_id=$get_current_project_id&amp;l=$l\" class=\"btn_default\">Delete project</a>
			</p>
		<!-- Menu -->

		<!-- Description -->
			$get_current_project_description
		<!-- //Description -->


		<!-- Projects parts -->
			<div class=\"vertical\">
				<ul>\n";

				$query = "SELECT project_part_id, project_part_title FROM $t_tasks_projects_parts WHERE project_part_project_id=$get_current_project_id AND project_part_is_active=1 ORDER BY project_part_title ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_project_part_id, $get_project_part_title) = $row;

					echo"
					<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=open_project_part&amp;project_id=$get_current_project_id&amp;project_part_id=$get_project_part_id&amp;l=$l\">$get_project_part_title</a></li>
					";
				}
				echo"
				</ul>
			</div>
		<!-- //Projects parts -->
		";
	}
} // open_project
elseif($action == "edit_project"){
	// Get ID
	$project_id_mysql = quote_smart($link, $project_id);
	$query = "SELECT project_id, project_system_id, project_title, project_task_abbr, project_description, project_logo, project_is_active, project_created, project_updated FROM $t_tasks_projects WHERE project_id=$project_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_project_id, $get_current_project_system_id, $get_current_project_title, $get_current_project_task_abbr, $get_current_project_description, $get_current_project_logo, $get_current_project_is_active, $get_current_project_created, $get_current_project_updated) = $row;

	if($get_current_project_id == ""){
		echo"<p>404 server error</p>";
	}
	else{
		if($process == 1){

			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);

			$inp_task_abbr = $_POST['inp_task_abbr'];
			$inp_task_abbr = output_html($inp_task_abbr);

			$inp_description = $_POST['inp_description'];



			$inp_system_id = $_POST['inp_system_id'];
			$inp_system_id = output_html($inp_system_id);
			$inp_system_id_mysql = quote_smart($link, $inp_system_id);


			$datetime = date("Y-m-d H:i:s");

			// Insert content
			$sql = "UPDATE $t_tasks_projects SET project_system_id=?, project_title=?, project_task_abbr=?, project_description=?, project_updated=? WHERE project_id='$get_current_project_id'";
			$stmt = $link->prepare($sql);
			$stmt->bind_param("issss", $inp_system_id, $inp_title, $inp_task_abbr, $inp_description, $datetime);
			$stmt->execute();
			if ($stmt->errno) {
				echo "FAILURE!!! $inp_title<br />$inp_description<br />$datetime<br />$get_current_project_id " . $stmt->error; die;
			}



			header("Location: index.php?open=dashboard&page=$page&action=open_project&project_id=$get_current_project_id&ft=success&fm=saved");
			exit;
		}

		echo"
		<h1>$get_current_project_title</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l\">Projects</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_project&amp;project_id=$get_current_project_id&amp;l=$l\">$get_current_project_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;project_id=$get_current_project_id&amp;l=$l\">Edit</a>
			</p>
		<!-- //Where am I? -->


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

		<!-- Edit project form -->";
		
		echo"
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;project_id=$get_current_project_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
		<p>Title:<br />
		<input type=\"text\" name=\"inp_title\" value=\"$get_current_project_title\" size=\"25\" />
		</p>
		<p>Task abbreviation for this project:<br />
		<input type=\"text\" name=\"inp_task_abbr\" value=\"$get_current_project_task_abbr\" size=\"25\" />
		</p>

		<p>Description:<br />
		<textarea name=\"inp_description\" rows=\"10\" cols=\"80\" class=\"editor\">$get_current_project_description</textarea><br />
		</p>

		<p>System: <a href=\"index.php?open=$open&amp;page=tasks_systems&amp;action=new_system&amp;l=$l\" target=\"_blank\">New</a><br />
		<select name=\"inp_system_id\">";
		$query = "SELECT system_id, system_title FROM $t_tasks_systems WHERE system_is_active=1 ORDER BY system_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_system_id, $get_system_title) = $row;
			echo"			<option value=\"$get_system_id\""; if($get_system_id == "$get_current_project_system_id"){ echo" selected=\"selected\""; } echo">$get_system_title</option>\n";
		}
		echo"
		</select>
		</p>

		<p><input type=\"submit\" value=\"Save\" class=\"btn_default\" /></p>

		</form>
		<!-- //Edit project form -->

		";
	}
} // edit_project
elseif($action == "delete_project"){
	// Get ID
	$project_id_mysql = quote_smart($link, $project_id);
	$query = "SELECT project_id, project_title, project_description, project_logo, project_is_active, project_created, project_updated FROM $t_tasks_projects WHERE project_id=$project_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_project_id, $get_current_project_title, $get_current_project_description, $get_current_project_logo, $get_current_project_is_active, $get_current_project_created, $get_current_project_updated) = $row;

	if($get_current_project_id == ""){
		echo"<p>404 server error</p>";
	}
	else{
		if($process == 1){

			
			$result = mysqli_query($link, "DELETE FROM $t_tasks_projects WHERE project_id=$project_id_mysql");
			$result = mysqli_query($link, "DELETE FROM $t_tasks_projects_parts WHERE project_part_project_id=$project_id_mysql");

			header("Location: index.php?open=dashboard&page=$page&ft=success&fm=deleted");
			exit;
		}

		echo"
		<h1>$get_current_project_title</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l\">Projects</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_project&amp;project_id=$get_current_project_id&amp;l=$l\">$get_current_project_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;project_id=$get_current_project_id&amp;l=$l\">Delete</a>
			</p>
		<!-- //Where am I? -->

		<p>Are you sure you want to delete the project?</p>
		<p>Also <em>project parts</em> will be deleted.</p>

		<p><a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;project_id=$get_current_project_id&amp;l=$l&amp;process=1\" class=\"btn_danger\">Delete</a></p>


		";
	}
} // delete_project
elseif($action == "new_project_part"){
	// Get ID
	$project_id_mysql = quote_smart($link, $project_id);
	$query = "SELECT project_id, project_title, project_description, project_logo, project_is_active, project_created, project_updated FROM $t_tasks_projects WHERE project_id=$project_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_project_id, $get_current_project_title, $get_current_project_description, $get_current_project_logo, $get_current_project_is_active, $get_current_project_created, $get_current_project_updated) = $row;

	if($get_current_project_id == ""){
		echo"<p>404 server error</p>";
	}
	else{
		if($process == 1){

			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);


			$inp_description = $_POST['inp_description'];


			$datetime = date("Y-m-d H:i:s");

			// Insert
			mysqli_query($link, "INSERT INTO $t_tasks_projects_parts
			(project_part_id, project_part_project_id, project_part_title, project_part_description, project_part_logo, project_part_is_active, project_part_created, project_part_updated) 
			VALUES 
			(NULL, $get_current_project_id, $inp_title_mysql, '', '', 1, '$datetime', '$datetime')")
			or die(mysqli_error($link));

			// Get ID
			$query = "SELECT project_part_id FROM $t_tasks_projects_parts WHERE project_part_created='$datetime'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_project_part_id) = $row;

			// Insert content
			$sql = "UPDATE $t_tasks_projects_parts SET project_part_description=? WHERE project_part_id='$get_project_part_id'";
			$stmt = $link->prepare($sql);
			$stmt->bind_param("s", $inp_description);
			$stmt->execute();
			if ($stmt->errno) {
				echo "FAILURE!!! " . $stmt->error; die;
			}

			header("Location: index.php?open=dashboard&page=$page&action=open_project&project_id=$get_current_project_id&ft=success&fm=saved");
			exit;
		}

		echo"
		<h1>$get_current_project_title</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l\">Projects</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_project&amp;project_id=$get_current_project_id&amp;l=$l\">$get_current_project_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;project_id=$get_current_project_id&amp;l=$l\">New project part</a>
			</p>
		<!-- //Where am I? -->


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

		<!-- New project part form -->
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;project_id=$get_current_project_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			<p>Title:<br />
			<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" />
			</p>

			<p>Description:<br />
			<textarea name=\"inp_description\" rows=\"10\" cols=\"80\" class=\"editor\"></textarea><br />
			</p>

	
			<p><input type=\"submit\" value=\"Save\" class=\"btn_default\" /></p>

			</form>
		<!-- //Edit project part form -->

		";
	}
} // new_project_part
elseif($action == "open_project_part"){
	// Get Project
	$project_id_mysql = quote_smart($link, $project_id);
	$query = "SELECT project_id, project_title, project_description, project_logo, project_is_active, project_created, project_updated FROM $t_tasks_projects WHERE project_id=$project_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_project_id, $get_current_project_title, $get_current_project_description, $get_current_project_logo, $get_current_project_is_active, $get_current_project_created, $get_current_project_updated) = $row;

	if($get_current_project_id == ""){
		echo"<p>404 server error</p>";
	}
	else{

		// Get part
		$project_part_id_mysql = quote_smart($link, $project_part_id);
		$query = "SELECT project_part_id, project_part_project_id, project_part_title, project_part_description, project_part_logo, project_part_is_active, project_part_created, project_part_updated FROM $t_tasks_projects_parts WHERE project_part_id=$project_part_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_project_part_id, $get_current_project_part_project_id, $get_current_project_part_title, $get_current_project_part_description, $get_current_project_part_logo, $get_current_project_part_is_active, $get_current_project_part_created, $get_current_project_part_updated) = $row;

		if($get_current_project_part_id == ""){
			echo"<p>404 server error</p>";
		}
		else{


			echo"
			<h1>$get_current_project_title</h1>

			<!-- Where am I? -->
				<p><b>You are here:</b><br />
				<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l\">Projects</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_project&amp;project_id=$get_current_project_id&amp;l=$l\">$get_current_project_title</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_project_part&amp;project_id=$get_current_project_id&amp;project_part_id=$get_current_project_part_id&amp;l=$l\">$get_current_project_part_title</a>
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
				<p>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_project_part&amp;project_id=$get_current_project_id&amp;project_part_id=$get_current_project_part_id&amp;l=$l\" class=\"btn_default\">Edit project part</a>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_project_part&amp;project_id=$get_current_project_id&amp;project_part_id=$get_current_project_part_id&amp;l=$l\" class=\"btn_default\">Delete project part</a>
				</p>
			<!-- Menu -->

			<!-- Description -->
				$get_current_project_part_description
			<!-- //Description -->


			";
		} // project part found
	} // project found
} // open_project_part
elseif($action == "edit_project_part"){
	// Get Project
	$project_id_mysql = quote_smart($link, $project_id);
	$query = "SELECT project_id, project_title, project_description, project_logo, project_is_active, project_created, project_updated FROM $t_tasks_projects WHERE project_id=$project_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_project_id, $get_current_project_title, $get_current_project_description, $get_current_project_logo, $get_current_project_is_active, $get_current_project_created, $get_current_project_updated) = $row;

	if($get_current_project_id == ""){
		echo"<p>404 server error</p>";
	}
	else{

		// Get part
		$project_part_id_mysql = quote_smart($link, $project_part_id);
		$query = "SELECT project_part_id, project_part_project_id, project_part_title, project_part_description, project_part_logo, project_part_is_active, project_part_created, project_part_updated FROM $t_tasks_projects_parts WHERE project_part_id=$project_part_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_project_part_id, $get_current_project_part_project_id, $get_current_project_part_title, $get_current_project_part_description, $get_current_project_part_logo, $get_current_project_part_is_active, $get_current_project_part_created, $get_current_project_part_updated) = $row;

		if($get_current_project_part_id == ""){
			echo"<p>404 server error</p>";
		}
		else{
			if($process == "1"){

				$inp_title = $_POST['inp_title'];
				$inp_title = output_html($inp_title);

				$inp_description = $_POST['inp_description'];


				$datetime = date("Y-m-d H:i:s");

				// Update content
				$sql = "UPDATE $t_tasks_projects_parts SET project_part_title=?, project_part_description=?, project_part_updated=? WHERE project_part_id='$get_current_project_part_id'";
				$stmt = $link->prepare($sql);
				$stmt->bind_param("sss", $inp_title, $inp_description, $datetime);
				$stmt->execute();
				if ($stmt->errno) {
					echo "FAILURE!!! " . $stmt->error; die;
				}

				header("Location: index.php?open=dashboard&page=$page&action=open_project_part&project_id=$get_current_project_id&project_part_id=$get_current_project_part_id&ft=success&fm=saved");
				exit;

			}


			echo"
			<h1>$get_current_project_title</h1>

			<!-- Where am I? -->
				<p><b>You are here:</b><br />
				<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l\">Projects</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_project&amp;project_id=$get_current_project_id&amp;l=$l\">$get_current_project_title</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_project_part&amp;project_id=$get_current_project_id&amp;project_part_id=$get_current_project_part_id&amp;l=$l\">$get_current_project_part_title</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_project_part&amp;project_id=$get_current_project_id&amp;project_part_id=$get_current_project_part_id&amp;l=$l\">Edit</a>
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

			<!-- Edit project part form -->
				<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=edit_project_part&amp;project_id=$get_current_project_id&amp;project_part_id=$get_current_project_part_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
				<p>Title:<br />
				<input type=\"text\" name=\"inp_title\" value=\"$get_current_project_part_title\" size=\"25\" />
				</p>

				<p>Description:<br />
				<textarea name=\"inp_description\" rows=\"10\" cols=\"80\" class=\"editor\">$get_current_project_part_description</textarea><br />
				</p>

				<p><input type=\"submit\" value=\"Save\" class=\"btn_default\" /></p>

				</form>
			<!-- //Edit project part form -->


			";
		} // project part found
	} // project found
} // edit_project_part
elseif($action == "delete_project_part"){
	// Get Project
	$project_id_mysql = quote_smart($link, $project_id);
	$query = "SELECT project_id, project_title, project_description, project_logo, project_is_active, project_created, project_updated FROM $t_tasks_projects WHERE project_id=$project_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_project_id, $get_current_project_title, $get_current_project_description, $get_current_project_logo, $get_current_project_is_active, $get_current_project_created, $get_current_project_updated) = $row;

	if($get_current_project_id == ""){
		echo"<p>404 server error</p>";
	}
	else{

		// Get part
		$project_part_id_mysql = quote_smart($link, $project_part_id);
		$query = "SELECT project_part_id, project_part_project_id, project_part_title, project_part_description, project_part_logo, project_part_is_active, project_part_created, project_part_updated FROM $t_tasks_projects_parts WHERE project_part_id=$project_part_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_project_part_id, $get_current_project_part_project_id, $get_current_project_part_title, $get_current_project_part_description, $get_current_project_part_logo, $get_current_project_part_is_active, $get_current_project_part_created, $get_current_project_part_updated) = $row;

		if($get_current_project_part_id == ""){
			echo"<p>404 server error</p>";
		}
		else{

			if($process == "1"){
				$result = mysqli_query($link, "DELETE FROM $t_tasks_projects_parts WHERE project_part_id=$project_part_id_mysql");

				header("Location: index.php?open=dashboard&page=$page&action=open_project&project_id=$get_current_project_id&ft=success&fm=deleted");
				exit;

			}
			echo"
			<h1>$get_current_project_title</h1>
			<!-- Where am I? -->
				<p><b>You are here:</b><br />
				<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l\">Projects</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_project&amp;project_id=$get_current_project_id&amp;l=$l\">$get_current_project_title</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_project_part&amp;project_id=$get_current_project_id&amp;project_part_id=$get_current_project_part_id&amp;l=$l\">$get_current_project_part_title</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_project_part&amp;project_id=$get_current_project_id&amp;project_part_id=$get_current_project_part_id&amp;l=$l\">Delete</a>
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


			<p>Are you sure you want to delete?</p>
			<p><a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_project_part&amp;project_id=$get_current_project_id&amp;project_part_id=$get_current_project_part_id&amp;l=$l&amp;process=1\" class=\"btn_danger\">Delete</a><p>

			";
		} // project part found
	} // project found
} // delete_project_part
?>