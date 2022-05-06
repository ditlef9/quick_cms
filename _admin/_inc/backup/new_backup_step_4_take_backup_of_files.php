<?php
/**
*
* File: _admin/_inc/backup/new_backup_step_4_take_backup_of_files.php
* Version 20:18 12.01.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ------------------------------------------------------------------------ */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables Backup ----------------------------------------------------------------------- */
$t_backup_liquidbase	 = $mysqlPrefixSav . "backup_liquidbase";
$t_backup_index 	 = $mysqlPrefixSav . "backup_index";
$t_backup_modules	 = $mysqlPrefixSav . "backup_modules";
$t_backup_directories	 = $mysqlPrefixSav . "backup_directories";
$t_backup_files		 = $mysqlPrefixSav . "backup_files";


/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['module_id'])) {
	$module_id = $_GET['module_id'];
	$module_id = strip_tags(stripslashes($module_id));
	if(!(is_numeric($module_id))){
		echo"Module id not numeric";
		die;
	}
}
else{
	echo"Missing module id";
	die;
	$module_id = "";
}
if(isset($_GET['dir_no'])) {
	$dir_no = $_GET['dir_no'];
	$dir_no = strip_tags(stripslashes($dir_no));
	if(!(is_numeric($dir_no))){
		echo"dir_no not numeric";
		die;
	}
}
else{
	$dir_no = "0";
}


/*- Script start ------------------------------------------------------------------------ */
// Find module
$module_id_mysql = quote_smart($link, $module_id);
$query_t = "SELECT module_id, module_backup_id, module_name, module_name_clean, module_icon_black_18x18, module_tables_finished, module_files_finished, module_backup_completed FROM $t_backup_modules WHERE module_id=$module_id_mysql";
$result_t = mysqli_query($link, $query_t);
$row_t = mysqli_fetch_row($result_t);
list($get_current_module_id, $get_current_module_backup_id, $get_current_module_name, $get_current_module_name_clean, $get_current_module_icon_black_18x18, $get_current_module_tables_finished, $get_current_module_files_finished, $get_current_module_backup_completed) = $row_t;
if($get_current_module_id == ""){
	echo"
	<h1>Module not found</h1>
	<p>
	<a href=\"index.php?open=$open&amp;page=backup&amp;editor_language=$editor_language&amp;l=$l\">Backup</a>
	</p>
	";
}
else{
	// Find backup
	$query_t = "SELECT backup_id, backup_created_datetime, backup_created_datetime_saying, backup_zip_dir, backup_zip_file, backup_secret, backup_test FROM $t_backup_index WHERE backup_id=$get_current_module_backup_id";
	$result_t = mysqli_query($link, $query_t);
	$row_t = mysqli_fetch_row($result_t);
	list($get_current_backup_id, $get_current_backup_created_datetime, $get_current_backup_created_datetime_saying, $get_current_backup_zip_dir, $get_current_backup_zip_file, $get_current_backup_secret, $get_current_backup_test) = $row_t;
	if($get_current_backup_id == ""){
		echo"
		<p>Backup not found</p>
		<p>
		<a href=\"index.php?open=$open&amp;page=backup&amp;editor_language=$editor_language&amp;l=$l\">Backup</a>
		</p>
		";
	}
	else{
		echo"
		<h1><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> New Backup no $get_current_backup_id</h1>


		<div class=\"backup_row\">
			<div class=\"backup_column_left\">
				";
				include("new_backup_step_include_list_modules_status.php");
				echo"
			</div>
			<div class=\"backup_column_right\">
		";


		// Include tables and directories
		if(!(file_exists("_inc/backup/_tables_and_dirs_to_backup/$get_current_module_name_clean.php"))){
			// Update done with content
			mysqli_query($link, "UPDATE $t_backup_modules  SET module_tables_finished=1, module_files_finished=1, module_backup_completed=1 WHERE module_id=$get_current_module_id") or die(mysqli_error($link));


			echo"
			<p>Tables and directories files doesnt exists for the module $get_current_module_name_clean 
			(<a href=\"_inc/backup/_tables_and_dirs_to_backup/$get_current_module_name_clean.php\">_inc/backup/_tables_and_dirs_to_backup/$get_current_module_name_clean.php</a>)
			</p>

			<!-- Refresh -->
				<meta http-equiv=refresh content=\"1; URL=index.php?open=$open&amp;page=new_backup_step_2_list_modules&amp;backup_id=$get_current_backup_id&amp;editor_language=$editor_language&amp;l=$l\">
				<!-- Jquery go to URL after x seconds -->
				<!-- In case meta refresh doesnt work -->
   					<script>
					\$(document).ready(function(){
						window.setTimeout(function(){
        						// Move to a new location or you can do something else
							window.location.href = \"index.php?open=$open&page=new_backup_step_2_list_modules&backup_id=$get_current_backup_id&editor_language=$editor_language&l=$l\";
						}, 10000);
					});
   					</script>
				<!-- //Jquery go to URL after x seconds -->
			<!-- //Refresh -->
			";


		}
		else{
			// Include tables file
			include("_inc/backup/_tables_and_dirs_to_backup/$get_current_module_name_clean.php");
			if($action == ""){
				echo"
				<!-- Operation -->
					<h2>$get_current_module_name List files in root directory $dir_no</h2>
				<!-- //Operation -->


				<!-- Feedback -->
				";
					if($ft != ""){
						if($fm == "changes_saved"){
							$fm = "$l_changes_saved";
						}
						else{
							$fm = ucfirst($fm);
							$fm = str_replace("_", " ", $fm);
						}
						echo"<div class=\"$ft\"><span>$fm</span></div>";
					}
					echo"	
				<!-- //Feedback -->

				<!-- Path -->
					<p>
					<b>List files in root directory</b>
					-&gt;
					List Files in Directories
					-&gt;
					Zip files
					</p>
				<!-- //Path -->
				";


				if(isset($directories_array[$dir_no])){
					echo"
					<h3>$directories_array[$dir_no]</h3>
					
						";



					// Step 1: Make list of all directories in root
					// Step 2: Loop trough one and one directory, and insert them
					// Directory id
					$inp_directory_id_mysql = quote_smart($link, "$dir_no");
					$filenames = "";
					$dir = "../$directories_array[$dir_no]/";
					if(is_dir("$dir")){
						echo"
						<table class=\"hor-zebra\">
						 <thead>
						  <tr>
						   <th style=\"padding-right: 5px;\">
							<span>File path</span>
						   </th>
						   <th style=\"padding-right: 5px;\">
							<span>Relative path</span>
						   </th>
						   <th style=\"padding-right: 5px;\">
							<span>Is dir?</span>
						   </th>
						  </tr>
						 </thead>
						 <tbody>
						";

						if ($handle = opendir($dir)) {
							while (false !== ($file = readdir($handle))) {
								if ($file === '.') continue;
								if ($file === '..') continue;

	
								// File path
								$inp_file_path_mysql = quote_smart($link, "../$directories_array[$dir_no]/$file");

								// Relative path
								$inp_real_path = realpath("../$directories_array[$dir_no]/$file");
								$inp_real_path_mysql = quote_smart($link, $inp_real_path);


								// is dir
								$inp_is_dir = 0;
								if(is_dir("../$directories_array[$dir_no]/$file")){
									$inp_is_dir = 1;
								}
								$inp_is_dir_mysql = quote_smart($link, $inp_is_dir);


								// Size
								$inp_size = "?";
								if($inp_is_dir == "0"){
									$inp_size = filesize("../$file");
								}
								$inp_size_mysql = quote_smart($link, $inp_size);


								// Insert
								if($inp_is_dir == 1){
									mysqli_query($link, "INSERT INTO $t_backup_directories
									(directory_id, directory_backup_id, directory_module_id, directory_file_path, directory_relative_path, 
									directory_size) 
									VALUES 
									(NULL, $get_current_backup_id, $get_current_module_id, $inp_file_path_mysql, $inp_real_path_mysql, 
									$inp_size_mysql)")
									or die(mysqli_error($link));
								}
								elseif($inp_is_dir == 0){
									mysqli_query($link, "INSERT INTO $t_backup_files
									(file_id, file_backup_id, file_module_id, file_directory_id, file_file_path, 
									file_relative_path, file_size) 
									VALUES 
									(NULL, $get_current_backup_id, $get_current_module_id, $inp_directory_id_mysql, $inp_real_path_mysql, 
									$inp_file_path_mysql, $inp_size_mysql)")
									or die(mysqli_error($link));
								}


								echo"
								 <tr>
								  <td style=\"padding-right: 5px;\">
									<span>../$directories_array[$dir_no]/$file</span>
								  </td>
								  <td style=\"padding-right: 5px;\">
									<span>$inp_real_path</span>
								  </td>
								  <td style=\"padding-right: 5px;\">
									<span>$inp_is_dir</span>
								  </td>
								 </tr>
								";
							}
							closedir($handle);
						}
						echo"
						 </tbody>
						</table>

						";
					} // is dir
					else{
						echo"<p><em>Directory doesnt not exists, skipping...</em></p>";
					}


					// Next dir_no
					$next_dir_no = $dir_no+1;
					$rand = rand(0,1);
					if($get_current_backup_test == "1"){
						$rand = 10;
					}
					echo"
					<meta http-equiv=\"refresh\" content=\"$rand; url=index.php?open=$open&amp;page=$page&amp;dir_no=$next_dir_no&amp;module_id=$get_current_module_id&amp;editor_language=$editor_language&amp;l=$l\" />
					";
					
				} // isset dir
				else{
					$rand = rand(0,1);
					if($get_current_backup_test == "1"){
						$rand = 10;
					}
					echo"<p>
					Done with List files in root directory, start with content! 
					</p>
					<meta http-equiv=\"refresh\" content=\"$rand; url=index.php?open=$open&amp;page=$page&amp;action=list_files_in_directory&amp;module_id=$get_current_module_id&amp;editor_language=$editor_language&amp;l=$l\" />
					
					<!-- Directories and files -->
						<table>
						 <thead>
						  <tr>
						   <th>
							<span>File</span>
						   </th>
						   <th>
							<span>Directory</span>
						   </th>
						  </tr>
						 </thead>
						 <tbody>\n";
						$query = "SELECT directory_id, directory_file_path, directory_relative_path, directory_size FROM $t_backup_directories WHERE directory_backup_id=$get_current_backup_id AND directory_module_id=$get_current_module_id ORDER BY directory_id ASC";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_directory_id, $get_directory_file_path, $get_directory_relative_path, $get_directory_size) = $row;
						
							echo"
							  <tr>
							   <td>
								<span></span>
							   </td>
							   <td>
								<span><img src=\"_inc/backup/_gfx/icons/folder_outline_black_18x18.png\" alt=\"folder_outline_black_18x18.png\" />
								$get_directory_file_path</span>
							   </td>
							  </tr>
							";
						}
						$query = "SELECT file_id, file_directory_id, file_file_path, file_relative_path, file_size FROM $t_backup_files WHERE file_backup_id=$get_current_backup_id AND file_module_id=$get_current_module_id ORDER BY file_id ASC";
						$result = mysqli_query($link, $query);
						while($row = mysqli_fetch_row($result)) {
							list($get_file_id, $get_file_directory_id, $get_file_file_path, $get_file_relative_path, $get_file_size) = $row;
						
							echo"
							  <tr>
							   <td>
								<span><img src=\"_inc/backup/_gfx/icons/description_outline_black_18x18.png\" alt=\"description_outline_black_18x18.png\" />
								$get_directory_file_path</span>
							   </td>
							   <td>
								<span>$get_file_file_path</span>
							   </td>
							  </tr>
							";
						}
						echo"
						 </tbody>
						</table>
					<!-- //Directories and files -->";
				} // not isset dir



			} // action == ""
			elseif($action == "list_files_in_directory"){
				echo"
				
				<!-- Operation -->
					<h2>$get_current_module_name List Files in Directories</h2>
				<!-- //Operation -->


				<!-- Feedback -->
				";
					if($ft != ""){
						if($fm == "changes_saved"){
							$fm = "$l_changes_saved";
						}
						else{
							$fm = ucfirst($fm);
							$fm = str_replace("_", " ", $fm);
						}
						echo"<div class=\"$ft\"><span>$fm</span></div>";
					}
					echo"	
				<!-- //Feedback -->

				<!-- Path -->
					<p>
					List files in root directory
					-&gt;
					<b>List Files in Directories</b>
					-&gt;
					Zip files
					</p>
				<!-- //Path -->
				";

				// Find directory
				$query = "SELECT directory_id, directory_backup_id, directory_module_id, directory_file_path, directory_relative_path, directory_size FROM $t_backup_directories WHERE directory_backup_id=$get_current_backup_id AND directory_module_id=$get_current_module_id LIMIT 0,1";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_directory_id, $get_directory_backup_id, $get_directory_module_id, $get_directory_file_path, $get_directory_relative_path, $get_directory_size) = $row;
				if($get_directory_id == ""){
					echo"
					<p>Finished with recursive directory files listing</p>\n
					";

					// Refresh
					$datetime_url = date("ymdhis");
					$rand = rand(0,5);
					if($get_current_backup_test == "1"){
						$rand = "10";
					}
					echo"
					<meta http-equiv=refresh content=\"$rand; URL=index.php?open=$open&amp;page=$page&amp;action=zip_files&amp;module_id=$get_current_module_id&amp;editor_language=$editor_language&amp;l=$l&amp;datetime=$datetime_url\">
					<!-- Jquery go to URL after x seconds -->
						<!-- In case meta refresh doesnt work -->
   						<script>
						\$(document).ready(function(){
							window.setTimeout(function(){
        							// Move to a new location or you can do something else
								window.location.href = \"index.php?open=$open&page=$page&action=zip_files&module_id=$get_current_module_id&editor_language=$editor_language&l=$l&datetime=$datetime_url\";
							}, 102000);
						});
   						</script>
					<!-- //Jquery go to URL after x seconds -->
					";
				}
				else{
					echo"
					<h3>$get_directory_file_path</h3>

					<!-- Directory info -->
						<table>
						 <tr>
						  <td style=\"padding-right: 4px;\">
							<span><b>Directory ID:</b></span>
						  </td>
						  <td>
							<span>$get_directory_id</span>
						  </td>
						 </tr>
						 <tr>
						  <td style=\"padding-right: 4px;\">
							<span><b>Directory file path:</b></span>
						  </td>
						  <td>
							<span>$get_directory_file_path</span>
						  </td>
						 </tr>
						 <tr>
						  <td style=\"padding-right: 4px;\">
							<span><b>Directory relative path:</b></span>
						  </td>
						  <td>
							<span>$get_directory_relative_path</span>
						  </td>
						 </tr>
						</table>

					<!-- //Directory info -->
					
					<p>Now inserting into table backup_files...</p>					

					<!-- List files -->
						<table class=\"hor-zebra\">
						 <thead>
						  <tr>
						   <th>
							<span>File path</span>
						   </th>
						   <th>
							<span>Relative path (path that will be used in ZIP-file)</span>
						   </th>
						   <th>
							<span>Size</span>
						   </th>
						   <th>
							<span>Is thumb?</span>
						   </th>
						  </tr>
						 </thead>
						 <tbody>
						";

					// Relative path
					$inp_relative_path_start = str_replace("../", "", $get_directory_file_path);

					// Get real path for our folder
					$rootPath = realpath($get_directory_file_path);
					//echo"Rootpath = $rootPath ";

					// Create recursive directory iterator
					/** @var SplFileInfo[] $files */
					$files = new RecursiveIteratorIterator(
					    new RecursiveDirectoryIterator($rootPath),
					    RecursiveIteratorIterator::LEAVES_ONLY
					);

					foreach ($files as $name => $file){
    						// Skip directories (they would be added automatically)
    						if (!$file->isDir()){
							// Get real and relative path for current file
							
							// File path
							$filePath = $file->getRealPath();

							// Relative path
							$inp_relative_path = substr($filePath, strlen($rootPath) + 1);

							// Relative path : Check for Linux or Windows
							if (strpos($inp_relative_path, '\\') !== FALSE) {
								$inp_relative_path = $inp_relative_path_start . "\\" . $inp_relative_path;
								$inp_relative_path = str_replace("/", "\\", $inp_relative_path);
							}
							else{
								$inp_relative_path = $inp_relative_path_start . "/" . $inp_relative_path;
							}
							$inp_relative_path_mysql = quote_smart($link, $inp_relative_path);

							// Check if relateive path is zip, backup or admin dir
							$relative_path_str_len = strlen($inp_relative_path);
							$check_if_zipped_dir = "";
							$check_if_admin_backup_dir = "";
							if($relative_path_str_len > 6){
								$check_if_zipped_dir = substr($inp_relative_path, 0, 7);
							}
							if($relative_path_str_len > 18){
								$check_if_admin_backup_dir = substr($inp_relative_path, 0, 19);
							}

							// Check if file is a thumb (we dont want to take backup of thumbs as they can be generated on the fly)
							$is_thumb = 0;
							if (strpos($filePath, 'thumb') !== FALSE) {
								$is_thumb = 1;
							}

							// File size
							$inp_file_size = filesize($filePath);
							$inp_web_size_mysql = quote_smart($link, $inp_file_size);

							// Add current file to archive
							if($inp_relative_path != "$get_current_backup_zip_file" && $check_if_zipped_dir != "_zipped" && $check_if_admin_backup_dir != "_admin\_data\backup" && $check_if_admin_backup_dir != "_admin/_data/backup" && $is_thumb == "0"){
								// echo"$filePath &middot; $relativePath &middot; $check_if_zipped_dir<br />";
								// File path
								$inp_web_file_path_mysql = quote_smart($link, $filePath);
								



								mysqli_query($link, "INSERT INTO $t_backup_files 
								(file_id, file_backup_id, file_module_id, file_directory_id, file_file_path, 
								file_relative_path, file_size) 
								VALUES 
								(NULL, $get_current_backup_id, $get_current_module_id, $get_directory_id, $inp_web_file_path_mysql, 
								$inp_relative_path_mysql, $inp_web_size_mysql)")
								or die(mysqli_error($link));
								echo"
								  <tr>
								   <td>
									<span>$filePath</span>
								   </td>
								   <td>
									<span>$inp_relative_path</span>
								   </td>
								   <td>
									<span>$inp_file_size</span>
								   </td>
								   <td>
									<span>$is_thumb</span>
								   </td>
								  </tr>
								";
							}
							else{
								
								echo"
								  <tr>
								   <td>
									<span style=\"color: grey;\">$filePath</span>
								   </td>
								   <td>
									<span style=\"color: grey;\">$inp_relative_path</span>
								   </td>
								   <td>
									<span style=\"color: grey;\">$inp_file_size</span>
								   </td>
								   <td>
									<span>$is_thumb</span>
								   </td>
								  </tr>
								";
							}
						}
					}
					echo"
						 </tbody>
						</table>
					<!-- List files -->
					";

					// Delete
					$result = mysqli_query($link, "DELETE FROM $t_backup_directories WHERE directory_id=$get_directory_id");
					
					// Refresh
					$datetime_url = date("ymdhis");
					$rand = rand(0,1);
					if($get_current_backup_test == "1"){
						$test = "10";
					}
					echo"
					<meta http-equiv=refresh content=\"$rand; URL=index.php?open=$open&amp;page=$page&amp;action=$action&amp;module_id=$get_current_module_id&amp;editor_language=$editor_language&amp;l=$l&amp;datetime=$datetime_url\">
					<!-- Jquery go to URL after x seconds -->
						<!-- In case meta refresh doesnt work -->
   						<script>
						\$(document).ready(function(){
							window.setTimeout(function(){
        							// Move to a new location or you can do something else
								window.location.href = \"index.php?open=$open&page=$page&action=$action&module_id=$get_current_module_id&editor_language=$editor_language&l=$l&datetime=$datetime_url\";
							}, 102000);
						});
   						</script>
					<!-- //Jquery go to URL after x seconds -->
					";
				} // found directory to work with
			} // action == "list_files_in_directory"
			elseif($action == "zip_files"){
				echo"
				

				<!-- Operation -->
					<h2>$get_current_module_name Zip Files</h2>

					<p>
					If the process stops then you can manually force it to 
					<a href=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;module_id=$get_current_module_id&amp;editor_language=$editor_language&amp;l=$l\">continue</a>.
					</p>
				<!-- //Operation -->


				<!-- Feedback -->
				";
					if($ft != ""){
						if($fm == "changes_saved"){
							$fm = "$l_changes_saved";
						}
						else{
							$fm = ucfirst($fm);
							$fm = str_replace("_", " ", $fm);
						}
						echo"<div class=\"$ft\"><span>$fm</span></div>";
					}
					echo"	
				<!-- //Feedback -->

				<!-- Path -->
					<p>
					List files in root directory
					-&gt;
					List Files in Directories
					-&gt;
					<b>Zip files</b>
					</p>
				<!-- //Path -->


				<!-- Zip files -->
					<div style=\"height: 10px;\"></div>
					<table class=\"hor-zebra\">
					 <thead>
					  <tr>
					   <th>
						<span>File path</span>
					   </th>
					   <th>
						<span>Relative path</span>
					   </th>
					   <th>
						<span>Size</span>
					   </th>
					  </tr>
					 </thead>
					 <tbody>
					";


					// Get real path for our folder
					$rootPath = realpath('../');

					// Initialize archive object
					$zip = new ZipArchive();
				
					// Open zip
					if(file_exists("_data/backup/$get_current_backup_zip_file")){
						$zip->open("_data/backup/$get_current_backup_zip_file");
					}
					else{
						$zip->open("_data/backup/$get_current_backup_zip_file", ZipArchive::CREATE | ZipArchive::OVERWRITE);
					}

					// Find files to zip
					$number_of_files = 0;
					$query = "SELECT file_id, file_backup_id, file_module_id, file_directory_id, file_file_path, file_relative_path, file_size FROM $t_backup_files WHERE file_backup_id=$get_current_backup_id AND file_module_id=$get_current_module_id ORDER BY file_id ASC LIMIT 0,25";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_file_id, $get_file_backup_id, $get_file_module_id, $get_file_directory_id, $get_file_file_path, $get_file_relative_path, $get_file_size) = $row;
						
						$zip->addFile($get_file_file_path, $get_file_relative_path);

						// Remove
						$result_remove = mysqli_query($link, "DELETE FROM $t_backup_files WHERE file_id=$get_file_id") or die(mysqli_error($link));


						echo"
						  <tr>
						   <td>
							<span>$get_file_file_path</span>
						   </td>
						   <td>
							<span>$get_file_relative_path</span>
						   </td>
						   <td>
							<span>$get_file_size</span>
						   </td>
						  </tr>
						";

						$number_of_files++;
					}
					echo"
					 </tbody>
					</table>
				<!-- //Zip files -->
				

				<!-- Refresh -->
					";
					// Refresh
					$datetime_url = date("ymdhis");
					$rand = rand(1,2);

					if($number_of_files > "0"){
						echo"
						<meta http-equiv=refresh content=\"$rand; URL=index.php?open=$open&amp;page=$page&amp;action=$action&amp;module_id=$get_current_module_id&amp;editor_language=$editor_language&amp;l=$l&amp;datetime=$datetime_url\">
						<!-- Jquery go to URL after x seconds -->
						<!-- In case meta refresh doesnt work -->
   						<script>
						\$(document).ready(function(){
							window.setTimeout(function(){
        							// Move to a new location or you can do something else
								window.location.href = \"index.php?open=$open&page=$page&action=$action&module_id=$get_current_module_id&editor_language=$editor_language&l=$l&datetime=$datetime_url\";
							}, 10000);
						});
   						</script>
						<!-- //Jquery go to URL after x seconds -->
						";
					}
					else{
						// Update done with content
						mysqli_query($link, "UPDATE $t_backup_modules  SET module_files_finished=1, module_backup_completed=1 WHERE module_id=$get_current_module_id") or die(mysqli_error($link));

						echo"
						<p>Finished with zipping files for this module.</p>

						<meta http-equiv=refresh content=\"$rand; URL=index.php?open=$open&amp;page=new_backup_step_2_list_modules&amp;backup_id=$get_current_backup_id&amp;editor_language=$editor_language&amp;l=$l&amp;datetime=$datetime_url\">
						<!-- Jquery go to URL after x seconds -->
						<!-- In case meta refresh doesnt work -->
   						<script>
						\$(document).ready(function(){
							window.setTimeout(function(){
        							// Move to a new location or you can do something else
								window.location.href = \"index.php?open=$open&page=new_backup_step_2_list_modules&amp;backup_id=$get_current_backup_id&editor_language=$editor_language&l=$l&datetime=$datetime_url\";
							}, 10000);
						});
   						</script>
						<!-- //Jquery go to URL after x seconds -->
						";
					}
					echo"
				<!-- //Refresh -->
				";
			} // action == "zip_files"

		} // tables and dir file exists

		echo"
			</div> <!-- //backup_column_right -->
		</div> <!-- //backup_row -->
		";
	} // backup found
} // module found
?>