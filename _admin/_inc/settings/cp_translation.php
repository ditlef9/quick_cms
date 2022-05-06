<?php
/**
*
* File: _admin/_inc/settings/cp_translation.php
* Version 19.52 18.03.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


// Variables
if(isset($_GET['mode'])) {
	$mode = $_GET['mode'];
	$mode = strip_tags(stripslashes($mode));
}
else{
	$mode = "";
}
if(isset($_GET['language'])) {
	$language = $_GET['language'];
	$language = strip_tags(stripslashes($language));
}
else{
	$language = "";
}
if(isset($_GET['step'])) {
	$step = $_GET['step'];
	$step = strip_tags(stripslashes($step));
}
else{
	$step = "";
}
if(isset($_GET['folder'])) {
	$folder = $_GET['folder'];
	$folder = strip_tags(stripslashes($folder));
}
else{
	$folder = "";
}
if(isset($_GET['file'])) {
	$file = $_GET['file'];
	$file = strip_tags(stripslashes($file));
}
else{
	$file = "";
}


function formatSizeUnits($bytes){
        if ($bytes >= 1073741824){
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576){
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024){
            $bytes = number_format($bytes / 1024, 2) . ' kB';
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



if($mode == "truncate_tables"){
	$res = mysqli_query($link, "TRUNCATE $t_admin_translations_directories");
	$res = mysqli_query($link, "TRUNCATE $t_admin_translations_files");
	$res = mysqli_query($link, "TRUNCATE $t_admin_translations_strings");


	echo"<div class=\"info\"><p>Tables truncated</p></div>
	<meta http-equiv=refresh content=\"1; URL=index.php?open=$open&amp;page=$page&amp;ft=success&amp;fm=tables_truncated\">";
}
elseif($mode == "flag"){
		
		if($process == "1" && is_dir("_translations/admin/$language") && $language != ""){
			// Upload photo


			// Get extention
			function getExtension($str) {
				$i = strrpos($str,".");
				if (!$i) { return ""; } 
				$l = strlen($str) - $i;
				$ext = substr($str,$i+1,$l);
				return $ext;
			}

			
			// Upload
			if($_SERVER["REQUEST_METHOD"] == "POST") {
				$image = $_FILES['inp_image']['name'];
				
				$filename = stripslashes($_FILES['inp_image']['name']);
				$extension = getExtension($filename);
				$extension = strtolower($extension);

				if($image){

					if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
						$ft = "warning";
						$fm = "unknown_file_format";
						$url = "index.php?open=settings&page=cp_translation&mode=flag&language=$language&l=$l&ft=$ft&fm=$fm"; 
						header("Location: $url");
						exit;
					}
					else{
						$size=filesize($_FILES['inp_image']['tmp_name']);

						if($extension == "png"){
							$uploadedfile = $_FILES['inp_image']['tmp_name'];
							

							// Width and height
							list($width,$height) = @getimagesize($uploadedfile);

							if($width == "16" && $height == "16"){
								
								// Destination file
								$uploadfile = "_translations/admin/$language/". $language . "." . $extension;

								if (move_uploaded_file($_FILES['inp_image']['tmp_name'], $uploadfile)) {
									// Send feedback
									$ft = "success";
									$fm = "photo_uploaded";
									$url = "index.php?open=settings&page=cp_translation&mode=flag&language=$language&l=$l&ft=$ft&fm=$fm"; 
									header("Location: $url");
									exit;
								} else {
									
									$ft = "warning";
									$fm = "photo_could_not_be_uploaded_please_check_file_size";
					
									$url = "index.php?open=settings&page=cp_translation&mode=flag&language=$language&l=$l&ft=$ft&fm=$fm"; 
									header("Location: $url");
									exit;
								}
							}
							else{
								$ft = "warning";
								$fm = "width_and_height_must_be";
					
								$url = "index.php?open=settings&page=cp_translation&mode=flag&language=$language&l=$l&ft=$ft&fm=$fm"; 
								header("Location: $url");
								exit;

							}
						}
						else{
							$ft = "warning";
							$fm = "file_format_must_be_png";
					
							$url = "index.php?open=settings&page=cp_translation&mode=flag&language=$language&l=$l&ft=$ft&fm=$fm"; 
							header("Location: $url");
							exit;

						}
						
					}
				} // if($image){
				else{
					switch ($_FILES['inp_image']['error']) {
						case UPLOAD_ERR_OK:
           						$fm = "photo_unknown_error";
							break;
						case UPLOAD_ERR_NO_FILE:
           						$fm = "no_file_selected";
							break;
						case UPLOAD_ERR_INI_SIZE:
           						$fm = "photo_exceeds_filesize";
							break;
						case UPLOAD_ERR_FORM_SIZE:
           						$fm_front = "photo_exceeds_filesize_form";
							break;
						default:
           						$fm_front = "unknown_upload_error";
							break;

					}
					if(isset($fm) && $fm != ""){
						$ft = "warning";
					}
						
					// Send feedback
					$url = "index.php?open=settings&page=cp_translation&mode=flag&language=$language&l=$l&ft=$ft&fm=$fm"; 
					header("Location: $url");
					exit;
				
				} // if!($image){

			} // if($_SERVER["REQUEST_METHOD"] == "POST") {


		} // process == 1

		echo"
		<h1>$l_cp_translation</h1>



		";
		// Check if language exists
		if(is_dir("_translations/admin/$language") && $language != ""){
			echo"
			<!-- Submenu -->
				<p>";
			if($language != "en"){
				echo"
				<a href=\"index.php?open=settings&amp;page=cp_translation&amp;mode=check_integrity&amp;language=$language&amp;l=$l\">$l_check_integrity_of_files</a>
				&middot;
				";
			}
			echo"
				<a href=\"index.php?open=settings&amp;page=cp_translation&amp;mode=flag&amp;language=$language&amp;l=$l\">$l_flag</a>

				</p>
			<!-- //Submenu -->
			
			
			<form method=\"POST\" action=\"index.php?open=settings&amp;page=cp_translation&amp;mode=flag&amp;language=$language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<!-- Feedback -->
				";
				if($ft != "" && $fm != ""){
					if($fm == "unknown_file_format"){
						$fm = "$l_unknown_file_format";
					}
					elseif($fm == "photo_uploaded"){
						$fm = "$l_photo_uploaded";
					}
					elseif($fm == "photo_could_not_be_uploaded_please_check_file_size"){
						$fm = "$l_photo_could_not_be_uploaded_please_check_file_size";
					}
					elseif($fm == "width_and_height_must_be"){
						$fm = "$l_width_and_height_must_be";
					}
					elseif($fm == "file_format_must_be_png"){
						$fm = "$l_file_format_must_be_png";
					}
					elseif($fm == "photo_unknown_error"){
						$fm = "$l_photo_unknown_error";
					}
					elseif($fm == "no_file_selected"){
						$fm = "$l_no_file_selected";
					}
					elseif($fm == "photo_exceeds_filesize"){
						$fm = "$l_photo_exceeds_filesize";
					}
					elseif($fm == "photo_exceeds_filesize_form"){
						$fm = "$l_photo_exceeds_filesize_form";
					}
					elseif($fm == "unknown_upload_error"){
						$fm = "$l_unknown_upload_error";
					}
					
					echo"<div class=\"$ft\"><p>$fm</p></div>";
				}
				echo"
			<!-- //Feedback -->


			<p>";
			if(file_exists("_translations/admin/$language/$language.png")){
				echo"<img src=\"_translations/admin/$language/$language.png\" alt=\"_scripts/language/data/$language/$language.png\" style=\"padding: 0px 4px 0px 0px;float:left;\" />";
			}
			echo"$l_select_photo (16x16 png):<br />
			<input name=\"inp_image\" type=\"file\" tabindex=\"1\" />
			<input type=\"submit\" value=\"$l_upload\" tabindex=\"2\" />
			</p>

			</form>


			<!-- Go back -->
				<p>
				<a href=\"index.php?open=settings&amp;page=cp_translation&amp;mode=open_language&amp;language=$language&amp;l=$l\"><img src=\"_inc/settings/_gfx/icons/go_previous_tango_16x16.png\" alt=\"go_previous_tango_16x16.png\" /></a>
				<a href=\"index.php?open=settings&amp;page=cp_translation&amp;mode=open_language&amp;language=$language&amp;l=$l\">$l_open_language</a>
				</p>
			<!-- //Go back -->
			";
		}
		else{
			echo"<p>$l_language_not_found.</p>";
		}
	} // mode == flag
elseif($mode == "open_language"){
	echo"
	<h1>$l_cp_translation - $language</h1>



	";
	// Check if language exists
	if(is_dir("_translations/admin/$language") && $language != ""){
	}
	else{
		echo"<p>$l_language_not_found.</p>";
		exit;
	}

	echo"

	<!-- Submenu -->
		<p>
		<a href=\"index.php?open=settings&amp;page=cp_translation&amp;mode=flag&amp;language=$language&amp;l=$l\">$l_flag</a>
		</p>
		<div style=\"height:10px;\"></div>
	<!-- //Submenu -->


		<table>
		  <tr>
		   <td style=\"vertical-align:top;padding-right: 20px;\">


	<!-- Content Left -->

			<!-- Select directory -->
				<div class=\"content_right_box\">
					<h2>$l_directories</h2>
					";

					$query = "SELECT admin_translation_directory_id, admin_translation_directory_name, admin_translation_directory_level FROM $t_admin_translations_directories ORDER BY admin_translation_directory_name ASC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_admin_translation_directory_id, $get_admin_translation_directory_name, $get_admin_translation_directory_level) = $row;


						echo"
						<table>
						 <tr>
						  <td style=\"padding: 2px 6px 0px 0px;\" >
							<a href=\"index.php?open=$open&amp;page=$page&amp;mode=open_directory&amp;folder=$get_admin_translation_directory_name&amp;language=$language&amp;editor_language=$editor_language\"><img src=\"_inc/settings/_gfx/icons/folder_blue_16x16.png\" alt=\"folder_blue_16x16.png\" /></a>
						  </td>
						  <td>
							<a href=\"index.php?open=$open&amp;page=$page&amp;mode=open_directory&amp;folder=$get_admin_translation_directory_name&amp;language=$language&amp;editor_language=$editor_language\">$get_admin_translation_directory_name</a>
						  </td>
						 </tr>
						</table>
						";

						// Check that the folder exist for this language
						if(!(is_dir("_translations/admin/$language/$get_admin_translation_directory_name"))){
							echo"<div class=\"info\"><p>Created <i>_translations/admin/$language/$get_admin_translation_directory_name</i></p></div>";
							mkdir("_translations/admin/$language/$get_admin_translation_directory_name");
						}
					}
					echo"
				</div>
			<!-- //Select directory -->
	<!-- //Content Left -->
	
		   </td>
		   <td style=\"vertical-align:top;\">

	<!-- Content Right -->
			<h2>Check that all strings that are in flat files exists in database</h2>

			
			<table class=\"hor-zebra\">
			 <thead>
			  <tr>
			   <th scope=\"col\">
				<span>URL</span>
			   </th>
			   <th scope=\"col\">
				<span>ID</span>
			   </th>
			  </tr>
			 </thead>
			 <tbody>

		";
			// List all folders
			$language_mysql = quote_smart($link, $language);
			$query = "SELECT admin_translation_directory_id, admin_translation_directory_name, admin_translation_directory_level FROM $t_admin_translations_directories ORDER BY admin_translation_directory_name ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_admin_translation_directory_id, $get_admin_translation_directory_name, $get_admin_translation_directory_level) = $row;
					
				if(isset($style) && $style == "odd"){
					$style = "";
				}
				else{
					$style = "odd";
				}

				echo"
				  <tr>
				   <td class=\"$style\">
					<span>$get_admin_translation_directory_name</span>
				   </td>
				   <td class=\"$style\">
					<span>$get_admin_translation_directory_id</span>
				   </td>
				  </tr>
				";
				
				// List all files
				$query_files = "SELECT admin_translation_file_id, admin_translation_file_name, admin_translation_dir_id FROM $t_admin_translations_files WHERE admin_translation_dir_id=$get_admin_translation_directory_id ORDER BY admin_translation_file_name ASC";
				$result_files = mysqli_query($link, $query_files);
				while($row_files = mysqli_fetch_row($result_files)) {
					list($get_admin_translation_file_id, $get_admin_translation_file_name, $get_admin_translation_dir_id) = $row_files;

					if(isset($style) && $style == "odd"){
						$style = "";
					}
					else{
						$style = "odd";
					}


					echo"
					  <tr>
					   <td class=\"$style\">
						<span>&nbsp; &nbsp; $get_admin_translation_file_name</span>
					   </td>
					   <td class=\"$style\">
						<span>$get_admin_translation_file_id</span>
					   </td>
					  </tr>
					";

	
					// Check strings in file
					if(!(file_exists("_translations/admin/$language/$get_admin_translation_directory_name/$get_admin_translation_file_name"))){

						echo"<div class=\"info\"><p>Copy $get_admin_translation_file_name</p></div>
						<meta http-equiv=refresh content=\"2; URL=index.php?open=settings&amp;page=cp_translation&amp;language=$language&amp;mode=open_language&amp;editor_language=$editor_language\">";


						copy("_translations/admin/en/$get_admin_translation_directory_name/$get_admin_translation_file_name", "_translations/admin/$language/$get_admin_translation_directory_name/$get_admin_translation_file_name");
						
					}
					$read_local_file = file_get_contents("_translations/admin/$language/$get_admin_translation_directory_name/$get_admin_translation_file_name"); 
					preg_match_all('/\$[A-Za-z0-9-_]+/', $read_local_file, $variable_names);
					preg_match_all('/"([^"]+)"/', $read_local_file, $translations);
								
					// Loop	
					$size = sizeof($variable_names[0]);
					for($x=0;$x<$size;$x++){
						// Variable name
						$variable_name = $variable_names[0][$x];
						$variable_name_mysql = quote_smart($link, $variable_name);

						// Content
						if(isset($translations[0][$x])){
							$translation = $translations[0][$x];
						}
						else{
							$translation = "";
						}
						$translation = str_replace('"', "", $translation);
						$translation_mysql = quote_smart($link, $translation);


						// Status
						$query_strings = "SELECT admin_translation_string_id FROM $t_admin_translations_strings WHERE admin_translation_string_dir_id=$get_admin_translation_directory_id AND admin_translation_string_file_id=$get_admin_translation_file_id AND admin_translation_string_language=$language_mysql AND admin_translation_string_variable=$variable_name_mysql";
						$result_strings = mysqli_query($link, $query_strings);
						$row_strings = mysqli_fetch_row($result_strings);
						list($get_admin_translation_string_id) = $row_strings;
						if($get_admin_translation_string_id == ""){
							mysqli_query($link, "INSERT INTO $t_admin_translations_strings
							(admin_translation_string_id, admin_translation_string_dir_id, admin_translation_string_file_id, admin_translation_string_language, admin_translation_string_variable, admin_translation_string_value) 
							VALUES 
							(NULL, $get_admin_translation_directory_id, $get_admin_translation_file_id, $language_mysql, $variable_name_mysql, $translation_mysql)")
							or die(mysqli_error($link));
							echo"<div class=\"info\"><p>Created $variable_name</p></div>
							<meta http-equiv=refresh content=\"1; URL=index.php?open=settings&amp;page=cp_translation&amp;language=$language&amp;mode=open_language&amp;editor_language=$editor_language\">";

						}

						if(isset($style) && $style == "odd"){
							$style = "";
						}
						else{
							$style = "odd";
						}

						// Variable
						echo"
						  <tr>
						   <td class=\"$style\">
							<span>&nbsp; &nbsp; &nbsp; &nbsp; $variable_name = $translation</span>
						   </td>
						   <td class=\"$style\">
							<span>$get_admin_translation_string_id</span>
						   </td>
						  </tr>
						";
					}
				} // loop trough files
			} // loop trough folder
		echo"
		 </tbody>
		</table>
	<!-- //Content Right -->
		  </td>
		 </tr>
		</table>

	";
	
} // mode == open_language

elseif($mode == "open_directory"){
	echo"
	<h1>$l_open_directory</h1>



	";
	// Check if language exists
	if(is_dir("_translations/admin/$language") && $language != ""){
	}
	else{
		echo"<p>$l_language_not_found.</p>";
		exit;
	}

	echo"

	<!-- You are here -->
		<p>
		<b>$l_you_are_here</b><br />
		<a href=\"index.php?open=settings&amp;page=cp_translation&amp;editor_language=$editor_language\">$l_cp_translation</a>
		&gt;
		<a href=\"index.php?open=settings&amp;page=cp_translation&amp;language=$language&amp;mode=open_language&amp;editor_language=$editor_language\">$language</a>
		&gt;
		<a href=\"index.php?open=settings&amp;page=cp_translation&amp;language=$language&amp;mode=open_directory&amp;folder=$folder&amp;editor_language=$editor_language\">$folder</a>
		</p>
	<!-- //You are here -->

	<!-- Submenu -->
		<p>
		<a href=\"index.php?open=settings&amp;page=cp_translation&amp;mode=flag&amp;language=$language&amp;l=$l\" class=\"btn\">$l_flag</a>
		</p>
		<div style=\"height:10px;\"></div>
	<!-- //Submenu -->

		<table>
		  <tr>
		   <td style=\"vertical-align:top;padding-right: 20px;\">

	<!-- Content Left -->

			<!-- Select directory -->
					<h2>$l_directories</h2>
					";
					$get_current_admin_translation_directory_id = "";
					$query = "SELECT admin_translation_directory_id, admin_translation_directory_name, admin_translation_directory_level FROM $t_admin_translations_directories ORDER BY admin_translation_directory_name ASC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_admin_translation_directory_id, $get_admin_translation_directory_name, $get_admin_translation_directory_level) = $row;
								
						if($folder == "$get_admin_translation_directory_name"){
							$get_current_admin_translation_directory_id = "$get_admin_translation_directory_id";
						}

						echo"
						<table>
						 <tr>
						  <td style=\"padding: 2px 6px 0px 0px;\" >
							<a href=\"index.php?open=$open&amp;page=$page&amp;mode=open_directory&amp;folder=$get_admin_translation_directory_name&amp;language=$language&amp;editor_language=$editor_language\"><img src=\"_inc/settings/_gfx/icons/folder"; if($folder == "$get_admin_translation_directory_name"){ echo"_open"; } echo"_tango_16x16.png\" alt=\"folder_tango_16x16.png\" /></a>
						  </td>
						  <td>
							<a href=\"index.php?open=$open&amp;page=$page&amp;mode=open_directory&amp;folder=$get_admin_translation_directory_name&amp;language=$language&amp;editor_language=$editor_language\""; if($folder == "$get_admin_translation_directory_name"){ echo" style=\"font-weight: bold;\""; } echo">$get_admin_translation_directory_name</a>
						  </td>
						 </tr>
						</table>
						";

						// Check that the folder exist for this language
						if(!(is_dir("_translations/admin/$language/$get_admin_translation_directory_name"))){
							echo"<div class=\"info\"><p>Created <i>_translations/admin/$language/$get_admin_translation_directory_name</i></p></div>";
							mkdir("_translations/admin/$language/$get_admin_translation_directory_name");
						}
					}

					echo"
				<!-- //Select directory -->

	<!-- //Content Left -->
	

		   </td>
		   <td style=\"vertical-align:top;\">


	<!-- Content Right -->
			<!-- List files in folder -->
			
				<table class=\"hor-zebra\">
				 <thead>
				  <tr>
				   <th scope=\"col\">
					<span>$l_file</span>
				   </th>
				   <th scope=\"col\" style=\"text-align:right;padding-right: 10px;\">
					<span>$l_size</span>
				   </th>
				   <th scope=\"col\">
					<span>$l_updated (Y-m-d H:i)</span>
				   </th>
				   <th scope=\"col\">
					<span>$l_actions</span>
				   </th>
				  </tr>
				</thead>
				";

				
				$query = "SELECT admin_translation_file_id, admin_translation_file_name, admin_translation_dir_id FROM $t_admin_translations_files WHERE admin_translation_dir_id=$get_current_admin_translation_directory_id ORDER BY admin_translation_file_name ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_admin_translation_file_id, $get_admin_translation_file_name, $get_admin_translation_dir_id) = $row;


					if(isset($style) && $style == "odd"){
						$style = "";
					}
					else{
						$style = "odd";
					}

					// File exists?
					if(!(file_exists("_translations/admin/$language/$folder/$get_admin_translation_file_name"))){
						$fh = fopen("_translations/admin/$language/$folder/$get_admin_translation_file_name", "w") or die("can not open file");
						fwrite($fh, "<?php\n?>");
						fclose($fh);
					}

					// Name
					$name = str_replace(".php", "", $get_admin_translation_file_name);

					// Size
					$bytes = filesize("_translations/admin/$language/$folder/$get_admin_translation_file_name");
					$filesize = formatSizeUnits($bytes);

					// Last edited
					$edited = date ("Y-m-d H:i", filemtime("_translations/admin/$language/$folder/$get_admin_translation_file_name"));
					echo"
					 <tr>
					  <td class=\"$style\">
						<span><a href=\"index.php?open=settings&amp;page=cp_translation&amp;mode=edit_language_file&amp;language=$language&amp;folder=$folder&amp;file=$name&amp;l=$l\">$name</a></span>
					  </td>
					  <td class=\"$style\" style=\"text-align:right;padding-right: 10px;\">
						<span>$filesize</span>
					  </td>
					  <td class=\"$style\">
						<span>$edited</span>
					  </td>
					  <td class=\"$style\">
						<span><a href=\"index.php?open=settings&amp;page=cp_translation&amp;mode=delete_language_file&amp;language=$language&amp;folder=$folder&amp;file=$name&amp;l=$l\">$l_delete</a></span>
					  </td>
					 </tr>
					";
				}
				echo"
				 </tbody>
				</table>
			<!-- //List files in folder -->
				
				

		  </td>
		 </tr>
		</table>


		<!-- Go back -->
			<div class=\"clear\"></div>
			<p>
			<a href=\"index.php?open=settings&amp;page=cp_translation\"><img src=\"_inc/settings/_gfx/icons/go_previous_tango_16x16.png\" alt=\"go_previous_tango_16x16.png\" /></a>
			<a href=\"index.php?open=settings&amp;page=cp_translation\">$l_languages</a>
			</p>
		<!-- //Go back -->
		";
} // mode == open_directory
elseif($mode == "edit_language_file"){
		
		
	// Check if language exists
	if(!(file_exists("_translations/admin/$language/$folder/$file.php"))){
		echo"
		<p>Error language file not found.
		";
	}
	else{
		
		if($process == "1" ){
			

		
			// Write header
			$fh = fopen("_translations/admin/$language/$folder/$file.php", "w") or die("can not open file");
			fwrite($fh, "<?php
");
			fclose($fh);


			// Dir and file
			$dir_id = $_GET['dir_id'];
			$dir_id = strip_tags(stripslashes($dir_id));
			$dir_id_mysql = quote_smart($link, $dir_id);

			$file_id = $_GET['file_id'];
			$file_id = strip_tags(stripslashes($file_id));
			$file_id_mysql = quote_smart($link, $file_id);

			// Get all variables that needs writing 
			$language_mysql = quote_smart($link, $language);
			$query = "SELECT admin_translation_string_id, admin_translation_string_variable FROM $t_admin_translations_strings WHERE admin_translation_string_dir_id=$dir_id_mysql AND admin_translation_string_file_id=$file_id_mysql AND admin_translation_string_language='en' ORDER BY admin_translation_string_variable ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_admin_translation_file_id, $get_admin_translation_string_variable) = $row;

				$variable_mysql = quote_smart($link, $get_admin_translation_string_variable);

				$inp_name = str_replace('$', "", $get_admin_translation_string_variable);
				$post_data = $_POST["$inp_name"];
				$post_data = output_html($post_data);
				$post_data_mysql = quote_smart($link, $post_data);

				// Update
				$res = mysqli_query($link, "UPDATE $t_admin_translations_strings SET admin_translation_string_value=$post_data_mysql WHERE admin_translation_string_dir_id=$dir_id_mysql AND admin_translation_string_file_id=$file_id_mysql AND admin_translation_string_language=$language_mysql AND admin_translation_string_variable=$variable_mysql");

		
				// Write Boody
				$inp_body ="$get_admin_translation_string_variable = \"$post_data\";
";
				$fh = fopen("_translations/admin/$language/$folder/$file.php", "a+") or die("can not open file");
				fwrite($fh, $inp_body);
				fclose($fh);
	

			}
				
		
			// Write Footer
			$fh = fopen("_translations/admin/$language/$folder/$file.php", "a+") or die("can not open file");
			fwrite($fh, "?>");
			fclose($fh);

			$url = "index.php?open=settings&page=cp_translation&mode=edit_language_file&language=$language&folder=$folder&file=$file&l=$l&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;

		} // process == 1

		echo"
		<h1>$l_edit_file</h1>


		<!-- You are here -->
			<p>
			<b>$l_you_are_here</b><br />
			<a href=\"index.php?open=settings&amp;page=cp_translation&amp;editor_language=$editor_language\">$l_cp_translation</a>
			&gt;
			<a href=\"index.php?open=settings&amp;page=cp_translation&amp;language=$language&amp;mode=open_language&amp;editor_language=$editor_language\">$language</a>
			&gt;
			<a href=\"index.php?open=settings&amp;page=cp_translation&amp;language=$language&amp;mode=open_directory&amp;folder=$folder&amp;editor_language=$editor_language\">$folder</a>
			&gt;
			<a href=\"index.php?open=settings&amp;page=cp_translation&amp;mode=edit_language_file&amp;language=$language&amp;folder=$folder&amp;file=$file&amp;editor_language=$editor_language\">$file</a>
			</p>
		<!-- //You are here -->
		
		
		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				if($fm == "changes_saved"){
					$fm = "$l_changes_saved";
				}
				else{
					$fm = "$ft";
				}
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->
		
		
		<!-- Submenu -->
		
			<p>
			<a href=\"index.php?open=settings&amp;page=cp_translation&amp;mode=flag&amp;language=$language&amp;l=$l\" class=\"btn\">$l_flag</a>
			</p>
			<div style=\"height:10px;\"></div>
			
		<!-- //Submenu -->
		
		<table style=\"width: 100%;\">
		  <tr>
		   <td style=\"vertical-align:top;width:150px;padding-right: 20px;\">



		<!-- Content Left -->
			<!-- Select directory -->
					<h2>$l_directories</h2>


					<table class=\"hor-zebra\">
					 <tbody>
					  <tr>
					   <td>
					";
					$get_current_admin_translation_directory_id = "";
					$get_current_admin_translation_file_id = "";
					$query = "SELECT admin_translation_directory_id, admin_translation_directory_name, admin_translation_directory_level FROM $t_admin_translations_directories ORDER BY admin_translation_directory_name ASC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_admin_translation_directory_id, $get_admin_translation_directory_name, $get_admin_translation_directory_level) = $row;
								
						if($folder == "$get_admin_translation_directory_name"){
							$get_current_admin_translation_directory_id = "$get_admin_translation_directory_id";
						}

						echo"
						<table>
						 <tr>
						  <td style=\"padding: 2px 6px 0px 0px;\" >
							<a href=\"index.php?open=$open&amp;page=$page&amp;mode=open_directory&amp;folder=$get_admin_translation_directory_name&amp;language=$language&amp;editor_language=$editor_language\"><img src=\"_inc/settings/_gfx/icons/folder"; if($folder == "$get_admin_translation_directory_name"){ echo"-open"; } echo".png\" alt=\"folder.png\" /></a>
						  </td>
						  <td>
							<a href=\"index.php?open=$open&amp;page=$page&amp;mode=open_directory&amp;folder=$get_admin_translation_directory_name&amp;language=$language&amp;editor_language=$editor_language\""; if($folder == "$get_admin_translation_directory_name"){ echo" style=\"font-weight: bold;\""; } echo">$get_admin_translation_directory_name</a>
						  </td>
						 </tr>
						</table>
						";

						// Check that the folder exist for this language
						if(!(is_dir("_translations/admin/$language/$get_admin_translation_directory_name"))){
							echo"<div class=\"info\"><p>Created <i>_translations/admin/$language/$get_admin_translation_directory_name</i></p></div>";
							mkdir("_translations/admin/$language/$get_admin_translation_directory_name");
						}

						// List all files
						if($folder == "$get_admin_translation_directory_name"){
							$query_files = "SELECT admin_translation_file_id, admin_translation_file_name, admin_translation_dir_id FROM $t_admin_translations_files WHERE admin_translation_dir_id=$get_admin_translation_directory_id ORDER BY admin_translation_file_name ASC";
							$result_files = mysqli_query($link, $query_files);
							while($row_files = mysqli_fetch_row($result_files)) {
								list($get_admin_translation_file_id, $get_admin_translation_file_name, $get_admin_translation_dir_id) = $row_files;

								// Name
								$name = str_replace(".php", "", $get_admin_translation_file_name);

								if($file == "$name"){
									$get_current_admin_translation_file_id = "$get_admin_translation_file_id";
								}

								$name_saying = "$name";
								$name_len = strlen($name);
								if($name_len > 20){
									$name_saying = substr($name, 0, 17);
									$name_saying = $name_saying . "...";
								}

								echo"
								<table>
								 <tr>
								  <td style=\"padding: 4px 6px 4px 16px;\" >
								
								  </td>
								  <td>
									<span><a href=\"index.php?open=settings&amp;page=cp_translation&amp;mode=edit_language_file&amp;language=$language&amp;folder=$get_admin_translation_directory_name&amp;file=$name&amp;l=$l\""; if($file == "$name"){ echo" style=\"font-weight: bold;\""; } echo">$name_saying</a></span>
								  </td>
								 </tr>
								</table>
								";
							} // files
						}
					} // folder

					echo"
					   </td>
					  </tr>
					 </tbody>
					</table>
				<!-- //Select directory -->

		<!-- //Content Left -->
	
		   </td>
		   <td style=\"vertical-align:top;\">

			<!-- Content Right -->

				<!-- Next file -->
					<div style=\"text-align: right;\">
					";


					$dir = "_translations/admin/$language/$folder/";
					if ($handle = opendir($dir)) {
						while (false !== ($content = readdir($handle))) {
							if ($content === '.') continue;
							if ($content === '..') continue;
							if(isset($next_file)){
								$content_saying = str_replace(".php", "", $content);
								echo"
								<p>
								<a href=\"index.php?open=settings&amp;page=cp_translation&amp;mode=edit_language_file&amp;language=$language&amp;folder=$folder&amp;file=$content_saying&amp;l=$l\">$content_saying</a>
								<a href=\"index.php?open=settings&amp;page=cp_translation&amp;mode=edit_language_file&amp;language=$language&amp;folder=$folder&amp;file=$content_saying&amp;l=$l\"><img src=\"_inc/settings/_gfx/icons/go-next.png\" alt=\"go-next.png\" /></a>
								</p>
								";
								break;
							}

							if($content == "$file.php"){
								$next_file = "1";
							}
						}
					}
					echo"
					</div>
				<!-- //Next file -->


				<!-- Edit this file -->
					<form method=\"POST\" action=\"index.php?open=settings&amp;page=cp_translation&amp;mode=edit_language_file&amp;language=$language&amp;folder=$folder&amp;file=$file&amp;l=$l&amp;dir_id=$get_current_admin_translation_directory_id&amp;file_id=$get_current_admin_translation_file_id&amp;process=1\" enctype=\"multipart/form-data\" name=\"nameform\">

					<table class=\"hor-zebra\">
					 <thead>
					  <tr>
					   <th scope=\"col\">
						<span>Variable</span>
					   </th>
					   <th scope=\"col\">
						<span>English</span>
					   </th>
					   <th scope=\"col\">
						<span>Value</span>
					   </th>
					  </tr>
					</thead>
					";

					// Make sure that english file exists
					if(!(file_exists("_translations/admin/en/$folder/$file.php"))){
						echo"
						<div class=\"error\"><p>English file doesnt exists!</p></div>

						<p><b>Deleting _translations/admin/$language/$folder/$file.php!! Please go to next file...</b></p>
						";
						unlink("_translations/admin/$language/$folder/$file.php");
					}
		
					// Current file - Get data between brackets
					$translated_file = file_get_contents("_translations/admin/$language/$folder/$file.php");
					preg_match_all('/"([^"]+)"/', $translated_file, $translations);

					$x = 0;
					$language_mysql = quote_smart($link, $language);
					$query = "SELECT admin_translation_string_id, admin_translation_string_variable, admin_translation_string_value FROM $t_admin_translations_strings WHERE admin_translation_string_dir_id=$get_current_admin_translation_directory_id AND admin_translation_string_file_id=$get_current_admin_translation_file_id AND admin_translation_string_language='en' ORDER BY admin_translation_string_variable ASC";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_admin_translation_file_id, $get_admin_translation_string_variable, $get_admin_translation_string_value) = $row;

						if(isset($style) && $style == "odd"){
							$style = "";
						}
						else{
							$style = "odd";
						}
				
						// Variable
						$get_admin_translation_string_variable_mysql = quote_smart($link, $get_admin_translation_string_variable);
						$inp_name = str_replace('$', "", $get_admin_translation_string_variable);

						// Translated content
						$query_local = "SELECT admin_translation_string_id, admin_translation_string_value FROM $t_admin_translations_strings WHERE admin_translation_string_dir_id=$get_current_admin_translation_directory_id AND admin_translation_string_file_id=$get_current_admin_translation_file_id AND admin_translation_string_language=$language_mysql AND admin_translation_string_variable=$get_admin_translation_string_variable_mysql";
						$result_local = mysqli_query($link, $query_local);
						$row_local = mysqli_fetch_row($result_local);
						list($get_local_admin_translation_string_id, $get_local_admin_translation_string_value) = $row_local;
						if($get_local_admin_translation_string_id == ""){


							mysqli_query($link, "INSERT INTO $t_admin_translations_strings
							(admin_translation_string_id, admin_translation_string_dir_id, admin_translation_string_file_id, admin_translation_string_language, admin_translation_string_variable, admin_translation_string_value) 
							VALUES 
							(NULL, $get_current_admin_translation_directory_id, $get_current_admin_translation_file_id, $language_mysql, $get_admin_translation_string_variable_mysql, '')")
							or die(mysqli_error($link));

							echo"<div class=\"info\"><p>Created $query_local</p></div>
							<meta http-equiv=refresh content=\"1; URL=index.php?open=settings&amp;page=cp_translation&amp;language=$language&amp;mode=$mode&amp;folder=$folder&amp;file=$file&amp;editor_language=$editor_language\">";


						}



						echo"
						 <tr>
						  <td class=\"$style\" style=\"padding-left:8px;\">
							<span>$inp_name</span>
						  </td>
						  <td class=\"$style\" style=\"padding-left:8px;\">
							<span>$get_admin_translation_string_value</span>
						  </td>
						  <td class=\"$style\">";
							if($x == 0){

						echo"
						<!-- Focus -->
						<script>
						\$(document).ready(function(){
							\$('[name=\"$inp_name\"]').focus();
						});
						</script>
						<!-- //Focus -->
						";
						$focus = "";
							}
							echo"
							<span><input type=\"text\" name=\"$inp_name\" value=\"$get_local_admin_translation_string_value\" size=\"45\" /></span>
						  </td>
						 </tr>
						";
						$x++;
					}

					echo"
					</table>

					<p>
					<input type=\"submit\" value=\"Save changes\" class=\"btn btn_default\" />
					</p>

					</form>
				<!-- //Edit this file -->
			<!-- //Content right -->


		  </td>
		 </tr>
		</table>
		";
	}
} // mode == edit_language_file
elseif($mode == "add_language"){
		if($process == "1"){
			$inp_lang = $_POST['inp_lang'];
			$inp_lang = output_html($inp_lang);
			$inp_lang = strtolower($inp_lang);
			$inp_lang = substr($inp_lang, 0, 2);
			if(empty($inp_lang)){
				$url = "index.php?open=settings&page=cp_translation&mode=add_language&ft=warning&fm=Please enter a language";
				header("Location: $url");
				exit;
			}


			// Mkdir
			mkdir("_translations/admin/$inp_lang");


			$url = "index.php?open=settings&page=cp_translation&ft=success&fm=Language added. Please run integrity of language files.";
			header("Location: $url");
			exit;
			
			
		}
		echo"
		<h1>$l_add_language</h1>



			
		<form method=\"POST\" action=\"index.php?open=settings&amp;page=cp_translation&amp;mode=add_language&amp;process=1&amp;l=$l\" enctype=\"multipart/form-data\" name=\"nameform\">

		<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
		<!-- //Feedback -->


		<!-- Focus -->
		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_lang\"]').focus();
		});
		</script>
		<!-- //Focus -->

		<p>
		ISO Language Code (example en, no):<br />
		<input type=\"text\" name=\"inp_lang\" size=\"30\" />
		</p>

		<p>
		<input type=\"submit\" value=\"$l_save\" />
		</p>

		</form>

		<!-- Go back -->
			<p>
			<a href=\"index.php?open=settings&amp;page=cp_translation\"><img src=\"_inc/settings/_gfx/icons/go_previous_tango_16x16.png\" alt=\"go_previous_tango_16x16.png\" /></a>
			<a href=\"index.php?open=settings&amp;page=cp_translation\">$l_languages</a>
			</p>
		<!-- //Go back -->
		";
	} // mode == add_language
elseif($mode == ""){
	echo"
	<h1>$l_cp_translation</h1>


	<!-- Feedback -->
			";
			if($ft != "" && $fm != ""){
				echo"<div class=\"$ft\"><p>$fm</p></div>";
			}
			echo"
	<!-- //Feedback -->

	<!-- Language selection -->
		<p><b>$l_language</b><br />";

		$dir = "_translations/site/";
		if ($handle = opendir($dir)) {
			while (false !== ($content = readdir($handle))) {
				if ($content === '.') continue;
				if ($content === '..') continue;
				if ($content === 'en') continue;

				if(file_exists("_translations/admin/$content/$content.png")){
					echo"<a href=\"index.php?open=settings&amp;page=cp_translation&amp;mode=open_language&amp;language=$content&amp;l=$l\"><img src=\"_translations/admin/$content/$content.png\" alt=\"_translations/admin/$content/$content.png\" style=\"padding: 0px 4px 0px 0px;float:left;\" /></a> ";
				}
				echo"<a href=\"index.php?open=settings&amp;page=cp_translation&amp;mode=open_language&amp;language=$content&amp;l=$l\">$content</a><br />\n";
				
			}
		}
		echo"	
		</p>
	<!-- //Language selection -->

	<!-- Add language -->
		<p>
		<a href=\"index.php?open=settings&amp;page=cp_translation&amp;mode=add_language\">Add language</a>
		</p>
	<!-- //Add language -->




	<!-- Check that all english files and folders are represented in MySQL -->
		<h2>English folders and files</h2>
		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;mode=truncate_tables\">Truncate tables</a>
		</p>
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>URL</span>
		   </th>
		   <th scope=\"col\">
			<span>ID</span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>
		";

		$dir = "_translations/admin/en/";
		if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
				if ($file === '.') continue;
				if ($file === '..') continue;


				if(isset($style) && $style == "odd"){
					$style = "";
				}
				else{
					$style = "odd";
				}

				// Folder
				$english_folder_name = "$file";
				$english_folder_name_mysql = quote_smart($link, $english_folder_name);

				// Status
				$query = "SELECT admin_translation_directory_id, admin_translation_directory_name, admin_translation_directory_level FROM $t_admin_translations_directories WHERE admin_translation_directory_name=$english_folder_name_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_admin_translation_directory_id, $get_admin_translation_directory_name, $get_admin_translation_directory_level) = $row;
				if($get_admin_translation_directory_id == ""){
					mysqli_query($link, "INSERT INTO $t_admin_translations_directories
					(admin_translation_directory_id, admin_translation_directory_name, admin_translation_directory_level) 
					VALUES 
					(NULL, $english_folder_name_mysql, '1')")
					or die(mysqli_error($link));
					echo"<div class=\"info\"><p>Created $english_folder_name</p></div><meta http-equiv=refresh content=\"1; URL=?open=settings&amp;page=cp_translation&amp;editor_language=$editor_language\">";

					$query = "SELECT admin_translation_directory_id, admin_translation_directory_name, admin_translation_directory_level FROM $t_admin_translations_directories WHERE admin_translation_directory_name=$english_folder_name_mysql";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_admin_translation_directory_id, $get_admin_translation_directory_name, $get_admin_translation_directory_level) = $row;

				}

				// This folder
				echo"
				  <tr>
				   <td class=\"$style\">
					<span>$english_folder_name</span>
				   </td>
				   <td class=\"$style\">
					<span>$get_admin_translation_directory_id</span>
				   </td>
				  </tr>
				";

				if($get_admin_translation_directory_id != "" && $file != "en.png"){
					$dir_sub = "_translations/admin/en/$file/";
					$dir_len_sub = strlen($dir_sub);
					if ($handle_sub = opendir($dir_sub)) {
						while (false !== ($file_sub = readdir($handle_sub))) {
							if ($file_sub === '.') continue;
							if ($file_sub === '..') continue;

							if(isset($style) && $style == "odd"){
								$style = "";
							}
							else{
								$style = "odd";
							}

							// File
							$english_file_name = "$file_sub";
							$english_file_name_mysql = quote_smart($link, $english_file_name);

							// Status
							$query = "SELECT admin_translation_file_id, admin_translation_file_name, admin_translation_dir_id FROM $t_admin_translations_files WHERE admin_translation_file_name=$english_file_name_mysql AND admin_translation_dir_id=$get_admin_translation_directory_id";
							$result = mysqli_query($link, $query);
							$row = mysqli_fetch_row($result);
							list($get_admin_translation_file_id, $get_admin_translation_file_name, $get_admin_translation_dir_id) = $row;
							if($get_admin_translation_file_id == ""){
								mysqli_query($link, "INSERT INTO $t_admin_translations_files
								(admin_translation_file_id, admin_translation_file_name, admin_translation_dir_id) 
								VALUES 
								(NULL, $english_file_name_mysql, $get_admin_translation_directory_id)")
								or die(mysqli_error($link));
								echo"<div class=\"info\"><p>Created $english_file_name</p></div><meta http-equiv=refresh content=\"1; URL=?open=settings&amp;page=cp_translation&amp;editor_language=$editor_language\">";

								$query = "SELECT admin_translation_file_id, admin_translation_file_name, admin_translation_dir_id FROM $t_admin_translations_files WHERE admin_translation_file_name=$english_file_name_mysql AND admin_translation_dir_id=$get_admin_translation_directory_id";
								$result = mysqli_query($link, $query);
								$row = mysqli_fetch_row($result);
								list($get_admin_translation_file_id, $get_admin_translation_file_name, $get_admin_translation_dir_id) = $row;
							}
		

							// This file
							echo"
							  <tr>
							   <td class=\"$style\">
								<span>&nbsp; &nbsp; <a href=\"_translations/admin/en/$english_folder_name/$english_file_name\">$english_file_name</a></span>
							   </td>
							   <td class=\"$style\">
								<span>$get_admin_translation_file_id</span>
							   </td>
							  </tr>
							";

	
							if($get_admin_translation_file_id != ""){
								// Check strings in file

								$read_english_file = file_get_contents("_translations/admin/en/$english_folder_name/$english_file_name"); 
								preg_match_all('/\$[A-Za-z0-9-_]+/', $read_english_file, $variable_names);
								preg_match_all('/"([^"]+)"/', $read_english_file, $translations);
								

								// Current file - Get data between brackets


								// Loop	
								$size = sizeof($variable_names[0]);
								for($x=0;$x<$size;$x++){
										
									// Variable name
									$variable_name = $variable_names[0][$x];
									$variable_name_mysql = quote_smart($link, $variable_name);

									// Content
									if(isset($translations[0][$x])){
										$translation = $translations[0][$x];
									}
									else{
										$translation = "";
									}
									$translation = str_replace('"', "", $translation);
									$translation_mysql = quote_smart($link, $translation);


									// Status
									$query = "SELECT admin_translation_string_id FROM $t_admin_translations_strings WHERE admin_translation_string_dir_id=$get_admin_translation_directory_id AND admin_translation_string_file_id=$get_admin_translation_file_id AND admin_translation_string_variable=$variable_name_mysql";
									$result = mysqli_query($link, $query);
									$row = mysqli_fetch_row($result);
									list($get_admin_translation_string_id) = $row;
									if($get_admin_translation_string_id == ""){
										mysqli_query($link, "INSERT INTO $t_admin_translations_strings
										(admin_translation_string_id, admin_translation_string_dir_id, admin_translation_string_file_id, admin_translation_string_language, admin_translation_string_variable, admin_translation_string_value) 
										VALUES 
										(NULL, $get_admin_translation_directory_id, $get_admin_translation_file_id, 'en', $variable_name_mysql, $translation_mysql)")
										or die(mysqli_error($link));
										echo"<div class=\"info\"><p>Created $variable_name</p></div><meta http-equiv=refresh content=\"1; URL=?open=settings&amp;page=cp_translation&amp;editor_language=$editor_language\">";

										$query = "SELECT admin_translation_string_id FROM $t_admin_translations_strings WHERE admin_translation_string_dir_id=$get_admin_translation_directory_id AND admin_translation_string_file_id=$get_admin_translation_file_id AND admin_translation_string_variable=$variable_name_mysql";
										$result = mysqli_query($link, $query);
										$row = mysqli_fetch_row($result);
										list($get_admin_translation_string_id) = $row;
									}

									// Variable
									echo"
									  <tr>
									   <td class=\"$style\">
										<span>&nbsp; &nbsp; &nbsp; &nbsp; $variable_name = $translation</span>
									   </td>
									   <td class=\"$style\">
										<span>$get_admin_translation_string_id</span>
									   </td>
									  </tr>
									";

									// Make sure that the translated file to local language exists
								}

							} // Site id
						} // loop trough files
					} // loop trough folder
				} // $get_admin_translation_directory_id != ""
			} // loop trough files
		} // loop trough folder
		echo"
		 </tbody>
		</table>


	<!-- //Check that all english files and folders are represented in MySQL -->

	";


} // mode == !!
			
?>