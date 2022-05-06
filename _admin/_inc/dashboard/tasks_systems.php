<?php
/**
*
* File: _admin/_inc/tasks_systems.php
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
$t_tasks_systems  		= $mysqlPrefixSav . "tasks_systems";
$t_tasks_systems_parts  	= $mysqlPrefixSav . "tasks_systems_parts";
$t_tasks_systems  		= $mysqlPrefixSav . "tasks_systems";
$t_tasks_systems_parts  	= $mysqlPrefixSav . "tasks_systems_parts";



/*- Variables  ---------------------------------------------------- */
if(isset($_GET['system_id'])) {
	$system_id = $_GET['system_id'];
	$system_id = strip_tags(stripslashes($system_id));
}
else{
	$system_id = "";
}
if(isset($_GET['system_part_id'])) {
	$system_part_id = $_GET['system_part_id'];
	$system_part_id = strip_tags(stripslashes($system_part_id));
}
else{
	$system_part_id = "";
}


if($action == ""){
	echo"
	<h1>Tasks systems</h1>

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l\">Systems</a>
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
		<p><a href=\"index.php?open=$open&amp;page=$page&amp;action=new_system&amp;l=$l\" class=\"btn_default\">New system</a></p>
	<!-- Menu -->

	<!-- systems -->
		<div class=\"vertical\">
			<ul>\n";

			$query = "SELECT system_id, system_title FROM $t_tasks_systems WHERE system_is_active=1 ORDER BY system_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_system_id, $get_system_title) = $row;

				echo"
				<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=open_system&amp;system_id=$get_system_id&amp;l=$l\">$get_system_title</a></li>
				";
			}
			echo"
			</ul>
		</div>
	<!-- //systems -->
	";
}
elseif($action == "new_system"){
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


		$datetime = date("Y-m-d H:i:s");

		// Insert
		mysqli_query($link, "INSERT INTO $t_tasks_systems
		(system_id, system_title, system_task_abbr, system_description, system_logo, system_is_active, system_increment_tasks_counter, system_created, system_updated) 
		VALUES 
		(NULL, $inp_title_mysql, $inp_task_abbr_mysql, '', '', 1, 1, '$datetime', '$datetime')")
		or die(mysqli_error($link));

		// Get ID
		$query = "SELECT system_id FROM $t_tasks_systems WHERE system_created='$datetime'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_system_id) = $row;

		// Insert content
		$sql = "UPDATE $t_tasks_systems SET system_description=? WHERE system_id='$get_system_id'";
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
	<h1>New system</h1>

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l\">Systems</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;l=$l\">New system</a>
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

	<!-- New system form -->";
		
		echo"
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
		<p>Title:<br />
		<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" />
		</p>

		<p>Description:<br />
		<textarea name=\"inp_description\" rows=\"10\" cols=\"80\" class=\"editor\"></textarea><br />
		</p>

		<p><input type=\"submit\" value=\"Create system\" class=\"btn_default\" /></p>

		</form>
	<!-- //New system form -->

	";
} // new_system
elseif($action == "open_system"){
	// Get ID
	$system_id_mysql = quote_smart($link, $system_id);
	$query = "SELECT system_id, system_title, system_description, system_logo, system_is_active, system_created, system_updated FROM $t_tasks_systems WHERE system_id=$system_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_system_id, $get_current_system_title, $get_current_system_description, $get_current_system_logo, $get_current_system_is_active, $get_current_system_created, $get_current_system_updated) = $row;

	if($get_current_system_id == ""){
		echo"<p>404 server error</p>";
	}
	else{
		echo"
		<h1>$get_current_system_title</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l\">Systems</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;system_id=$get_current_system_id&amp;l=$l\">$get_current_system_title</a>
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
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=new_system_part&amp;system_id=$get_current_system_id&amp;l=$l\" class=\"btn_default\">New part</a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_system&amp;system_id=$get_current_system_id&amp;l=$l\" class=\"btn_default\">Edit system</a>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_system&amp;system_id=$get_current_system_id&amp;l=$l\" class=\"btn_default\">Delete system</a>
			</p>
		<!-- Menu -->

		<!-- Description -->
			$get_current_system_description
		<!-- //Description -->


		<!-- systems parts -->
			<div class=\"vertical\">
				<ul>\n";

				$query = "SELECT system_part_id, system_part_title FROM $t_tasks_systems_parts WHERE system_part_system_id=$get_current_system_id AND system_part_is_active=1 ORDER BY system_part_title ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_system_part_id, $get_system_part_title) = $row;

					echo"
					<li><a href=\"index.php?open=$open&amp;page=$page&amp;action=open_system_part&amp;system_id=$get_current_system_id&amp;system_part_id=$get_system_part_id&amp;l=$l\">$get_system_part_title</a></li>
					";
				}
				echo"
				</ul>
			</div>
		<!-- //systems parts -->
		";
	}
} // open_system
elseif($action == "edit_system"){
	// Get ID
	$system_id_mysql = quote_smart($link, $system_id);
	$query = "SELECT system_id, system_title, system_task_abbr, system_description, system_logo, system_is_active, system_created, system_updated FROM $t_tasks_systems WHERE system_id=$system_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_system_id, $get_current_system_title, $get_current_system_task_abbr, $get_current_system_description, $get_current_system_logo, $get_current_system_is_active, $get_current_system_created, $get_current_system_updated) = $row;

	if($get_current_system_id == ""){
		echo"<p>404 server error</p>";
	}
	else{
		if($process == 1){

			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);

			$inp_task_abbr = $_POST['inp_task_abbr'];
			$inp_task_abbr = output_html($inp_task_abbr);

			$inp_description = $_POST['inp_description'];


			$datetime = date("Y-m-d H:i:s");

			// Insert content
			$sql = "UPDATE $t_tasks_systems SET system_title=?, system_task_abbr=?, system_description=?, system_updated=? WHERE system_id='$get_current_system_id'";
			$stmt = $link->prepare($sql);
			$stmt->bind_param("ssss", $inp_title, $inp_task_abbr, $inp_description, $datetime);
			$stmt->execute();
			if ($stmt->errno) {
				echo "FAILURE!!! $inp_title<br />$inp_description<br />$datetime<br />$get_current_system_id " . $stmt->error; die;
			}



			header("Location: index.php?open=dashboard&page=$page&action=open_system&system_id=$get_current_system_id&ft=success&fm=saved");
			exit;
		}

		echo"
		<h1>$get_current_system_title</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l\">Systems</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_system&amp;system_id=$get_current_system_id&amp;l=$l\">$get_current_system_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;system_id=$get_current_system_id&amp;l=$l\">Edit</a>
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

		<!-- Edit system form -->";
		
		echo"
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;system_id=$get_current_system_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			<p>Title:<br />
			<input type=\"text\" name=\"inp_title\" value=\"$get_current_system_title\" size=\"25\" />
			</p>
			<p>Task abbreviation for this system:<br />
			<input type=\"text\" name=\"inp_task_abbr\" value=\"$get_current_system_task_abbr\" size=\"25\" />
			</p>


			<p>Description:<br />
			<textarea name=\"inp_description\" rows=\"10\" cols=\"80\" class=\"editor\">$get_current_system_description</textarea><br />
			</p>

			<p><input type=\"submit\" value=\"Save\" class=\"btn_default\" /></p>

		</form>
		<!-- //Edit system form -->

		";
	}
} // edit_system
elseif($action == "delete_system"){
	// Get ID
	$system_id_mysql = quote_smart($link, $system_id);
	$query = "SELECT system_id, system_title, system_description, system_logo, system_is_active, system_created, system_updated FROM $t_tasks_systems WHERE system_id=$system_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_system_id, $get_current_system_title, $get_current_system_description, $get_current_system_logo, $get_current_system_is_active, $get_current_system_created, $get_current_system_updated) = $row;

	if($get_current_system_id == ""){
		echo"<p>404 server error</p>";
	}
	else{
		if($process == 1){

			
			$result = mysqli_query($link, "DELETE FROM $t_tasks_systems WHERE system_id=$system_id_mysql");
			$result = mysqli_query($link, "DELETE FROM $t_tasks_systems_parts WHERE system_part_system_id=$system_id_mysql");

			header("Location: index.php?open=dashboard&page=$page&ft=success&fm=deleted");
			exit;
		}

		echo"
		<h1>$get_current_system_title</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l\">Systems</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_system&amp;system_id=$get_current_system_id&amp;l=$l\">$get_current_system_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;system_id=$get_current_system_id&amp;l=$l\">Delete</a>
			</p>
		<!-- //Where am I? -->

		<p>Are you sure you want to delete the system?</p>
		<p>Also <em>system parts</em> will be deleted.</p>

		<p><a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;system_id=$get_current_system_id&amp;l=$l&amp;process=1\" class=\"btn_danger\">Delete</a></p>


		";
	}
} // delete_system
elseif($action == "new_system_part"){
	// Get ID
	$system_id_mysql = quote_smart($link, $system_id);
	$query = "SELECT system_id, system_title, system_description, system_logo, system_is_active, system_created, system_updated FROM $t_tasks_systems WHERE system_id=$system_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_system_id, $get_current_system_title, $get_current_system_description, $get_current_system_logo, $get_current_system_is_active, $get_current_system_created, $get_current_system_updated) = $row;

	if($get_current_system_id == ""){
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
			mysqli_query($link, "INSERT INTO $t_tasks_systems_parts
			(system_part_id, system_part_system_id, system_part_title, system_part_description, system_part_logo, system_part_is_active, system_part_created, system_part_updated) 
			VALUES 
			(NULL, $get_current_system_id, $inp_title_mysql, '', '', 1, '$datetime', '$datetime')")
			or die(mysqli_error($link));

			// Get ID
			$query = "SELECT system_part_id FROM $t_tasks_systems_parts WHERE system_part_created='$datetime'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_system_part_id) = $row;

			// Insert content
			$sql = "UPDATE $t_tasks_systems_parts SET system_part_description=? WHERE system_part_id='$get_system_part_id'";
			$stmt = $link->prepare($sql);
			$stmt->bind_param("s", $inp_description);
			$stmt->execute();
			if ($stmt->errno) {
				echo "FAILURE!!! " . $stmt->error; die;
			}

			header("Location: index.php?open=dashboard&page=$page&action=open_system&system_id=$get_current_system_id&ft=success&fm=saved");
			exit;
		}

		echo"
		<h1>$get_current_system_title</h1>

		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l\">Systems</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_system&amp;system_id=$get_current_system_id&amp;l=$l\">$get_current_system_title</a>
			&gt;
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;system_id=$get_current_system_id&amp;l=$l\">New system part</a>
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

		<!-- New system part form -->
			<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;system_id=$get_current_system_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			<p>Title:<br />
			<input type=\"text\" name=\"inp_title\" value=\"\" size=\"25\" />
			</p>

			<p>Description:<br />
			<textarea name=\"inp_description\" rows=\"10\" cols=\"80\" class=\"editor\"></textarea><br />
			</p>

			<p><input type=\"submit\" value=\"Save\" class=\"btn_default\" /></p>

			</form>
		<!-- //Edit system part form -->

		";
	}
} // new_system_part
elseif($action == "open_system_part"){
	// Get system
	$system_id_mysql = quote_smart($link, $system_id);
	$query = "SELECT system_id, system_title, system_description, system_logo, system_is_active, system_created, system_updated FROM $t_tasks_systems WHERE system_id=$system_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_system_id, $get_current_system_title, $get_current_system_description, $get_current_system_logo, $get_current_system_is_active, $get_current_system_created, $get_current_system_updated) = $row;

	if($get_current_system_id == ""){
		echo"<p>404 server error</p>";
	}
	else{

		// Get part
		$system_part_id_mysql = quote_smart($link, $system_part_id);
		$query = "SELECT system_part_id, system_part_system_id, system_part_title, system_part_description, system_part_logo, system_part_is_active, system_part_created, system_part_updated FROM $t_tasks_systems_parts WHERE system_part_id=$system_part_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_system_part_id, $get_current_system_part_system_id, $get_current_system_part_title, $get_current_system_part_description, $get_current_system_part_logo, $get_current_system_part_is_active, $get_current_system_part_created, $get_current_system_part_updated) = $row;

		if($get_current_system_part_id == ""){
			echo"<p>404 server error</p>";
		}
		else{


			echo"
			<h1>$get_current_system_title</h1>

			<!-- Where am I? -->
				<p><b>You are here:</b><br />
				<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l\">Systems</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_system&amp;system_id=$get_current_system_id&amp;l=$l\">$get_current_system_title</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_system_part&amp;system_id=$get_current_system_id&amp;system_part_id=$get_current_system_part_id&amp;l=$l\">$get_current_system_part_title</a>
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
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_system_part&amp;system_id=$get_current_system_id&amp;system_part_id=$get_current_system_part_id&amp;l=$l\" class=\"btn_default\">Edit system part</a>
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_system_part&amp;system_id=$get_current_system_id&amp;system_part_id=$get_current_system_part_id&amp;l=$l\" class=\"btn_default\">Delete system part</a>
				</p>
			<!-- Menu -->

			<!-- Description -->
				$get_current_system_part_description
			<!-- //Description -->


			";
		} // system part found
	} // system found
} // open_system_part
elseif($action == "edit_system_part"){
	// Get system
	$system_id_mysql = quote_smart($link, $system_id);
	$query = "SELECT system_id, system_title, system_description, system_logo, system_is_active, system_created, system_updated FROM $t_tasks_systems WHERE system_id=$system_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_system_id, $get_current_system_title, $get_current_system_description, $get_current_system_logo, $get_current_system_is_active, $get_current_system_created, $get_current_system_updated) = $row;

	if($get_current_system_id == ""){
		echo"<p>404 server error</p>";
	}
	else{

		// Get part
		$system_part_id_mysql = quote_smart($link, $system_part_id);
		$query = "SELECT system_part_id, system_part_system_id, system_part_title, system_part_description, system_part_logo, system_part_is_active, system_part_created, system_part_updated FROM $t_tasks_systems_parts WHERE system_part_id=$system_part_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_system_part_id, $get_current_system_part_system_id, $get_current_system_part_title, $get_current_system_part_description, $get_current_system_part_logo, $get_current_system_part_is_active, $get_current_system_part_created, $get_current_system_part_updated) = $row;

		if($get_current_system_part_id == ""){
			echo"<p>404 server error</p>";
		}
		else{
			if($process == "1"){

				$inp_title = $_POST['inp_title'];
				$inp_title = output_html($inp_title);

				$inp_description = $_POST['inp_description'];


				$datetime = date("Y-m-d H:i:s");

				// Update content
				$sql = "UPDATE $t_tasks_systems_parts SET system_part_title=?, system_part_description=?, system_part_updated=? WHERE system_part_id='$get_current_system_part_id'";
				$stmt = $link->prepare($sql);
				$stmt->bind_param("sss", $inp_title, $inp_description, $datetime);
				$stmt->execute();
				if ($stmt->errno) {
					echo "FAILURE!!! " . $stmt->error; die;
				}

				header("Location: index.php?open=dashboard&page=$page&action=open_system_part&system_id=$get_current_system_id&system_part_id=$get_current_system_part_id&ft=success&fm=saved");
				exit;

			}


			echo"
			<h1>$get_current_system_title</h1>

			<!-- Where am I? -->
				<p><b>You are here:</b><br />
				<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l\">Systems</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_system&amp;system_id=$get_current_system_id&amp;l=$l\">$get_current_system_title</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_system_part&amp;system_id=$get_current_system_id&amp;system_part_id=$get_current_system_part_id&amp;l=$l\">$get_current_system_part_title</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=edit_system_part&amp;system_id=$get_current_system_id&amp;system_part_id=$get_current_system_part_id&amp;l=$l\">Edit</a>
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

			<!-- Edit system part form -->
				<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;action=edit_system_part&amp;system_id=$get_current_system_id&amp;system_part_id=$get_current_system_part_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
				<p>Title:<br />
				<input type=\"text\" name=\"inp_title\" value=\"$get_current_system_part_title\" size=\"25\" />
				</p>

				<p>Description:<br />
				<textarea name=\"inp_description\" rows=\"10\" cols=\"80\" class=\"editor\">$get_current_system_part_description</textarea><br />
				</p>

				<p><input type=\"submit\" value=\"Save\" class=\"btn_default\" /></p>

				</form>
			<!-- //Edit system part form -->


			";
		} // system part found
	} // system found
} // edit_system_part
elseif($action == "delete_system_part"){
	// Get system
	$system_id_mysql = quote_smart($link, $system_id);
	$query = "SELECT system_id, system_title, system_description, system_logo, system_is_active, system_created, system_updated FROM $t_tasks_systems WHERE system_id=$system_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_system_id, $get_current_system_title, $get_current_system_description, $get_current_system_logo, $get_current_system_is_active, $get_current_system_created, $get_current_system_updated) = $row;

	if($get_current_system_id == ""){
		echo"<p>404 server error</p>";
	}
	else{

		// Get part
		$system_part_id_mysql = quote_smart($link, $system_part_id);
		$query = "SELECT system_part_id, system_part_system_id, system_part_title, system_part_description, system_part_logo, system_part_is_active, system_part_created, system_part_updated FROM $t_tasks_systems_parts WHERE system_part_id=$system_part_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_current_system_part_id, $get_current_system_part_system_id, $get_current_system_part_title, $get_current_system_part_description, $get_current_system_part_logo, $get_current_system_part_is_active, $get_current_system_part_created, $get_current_system_part_updated) = $row;

		if($get_current_system_part_id == ""){
			echo"<p>404 server error</p>";
		}
		else{

			if($process == "1"){
				$result = mysqli_query($link, "DELETE FROM $t_tasks_systems_parts WHERE system_part_id=$system_part_id_mysql");

				header("Location: index.php?open=dashboard&page=$page&action=open_system&system_id=$get_current_system_id&ft=success&fm=deleted");
				exit;

			}
			echo"
			<h1>$get_current_system_title</h1>
			<!-- Where am I? -->
				<p><b>You are here:</b><br />
				<a href=\"index.php?open=$open&amp;page=tasks&amp;l=$l\">Tasks</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;l=$l\">Systems</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_system&amp;system_id=$get_current_system_id&amp;l=$l\">$get_current_system_title</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=open_system_part&amp;system_id=$get_current_system_id&amp;system_part_id=$get_current_system_part_id&amp;l=$l\">$get_current_system_part_title</a>
				&gt;
				<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_system_part&amp;system_id=$get_current_system_id&amp;system_part_id=$get_current_system_part_id&amp;l=$l\">Delete</a>
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
			<p><a href=\"index.php?open=$open&amp;page=$page&amp;action=delete_system_part&amp;system_id=$get_current_system_id&amp;system_part_id=$get_current_system_part_id&amp;l=$l&amp;process=1\" class=\"btn_danger\">Delete</a><p>

			";
		} // system part found
	} // system found
} // delete_system_part
?>