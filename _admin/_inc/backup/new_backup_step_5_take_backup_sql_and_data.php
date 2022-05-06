<?php
/**
*
* File: _admin/_inc/backup/new_backup_step_5_take_backup_sql_and_data.php
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



/*- Functions -------------------------------------------------------------------------- */
include("_data/webdesign.php");
include("_functions/get_extension.php");

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

/*- Variables -------------------------------------------------------------------------- */
if(isset($_GET['backup_id'])) {
	$backup_id = $_GET['backup_id'];
	$backup_id = strip_tags(stripslashes($backup_id));
	if(!(is_numeric($backup_id))){
		echo"Backup id not numeric";
		die;
	}
}
else{
	echo"Missing backup id";
	die;
	$backup_id = "";
}


/*- Script start ------------------------------------------------------------------------ */
// Find backup
$backup_id_mysql = quote_smart($link, $backup_id);
$query_t = "SELECT backup_id, backup_created_datetime, backup_created_datetime_saying, backup_zip_dir, backup_zip_file, backup_secret FROM $t_backup_index WHERE backup_id=$backup_id_mysql";
$result_t = mysqli_query($link, $query_t);
$row_t = mysqli_fetch_row($result_t);
list($get_current_backup_id, $get_current_backup_created_datetime, $get_current_backup_created_datetime_saying, $get_current_backup_zip_dir, $get_current_backup_zip_file, $get_current_backup_secret) = $row_t;
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
	if($action == ""){
		echo"

		<!-- Operation -->
			<h2>SQL and Data List Files</h2>
			<div style=\"height: 10px;\"></div>
		<!-- //Operation -->


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
				<span>File type</span>
			   </th>
			   <th>
				<span>Size</span>
			   </th>
			  </tr>
			 </thead>
			 <tbody>
			";

			
			// Get real path for our folder
			$dirs = array("../_admin/_data", "../_webdesign/$webdesignSav");
			foreach ($dirs as $dir){
				$rootPath = realpath($dir);
				if($rootPath != ""){

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
		
							// Check if is backup dir
							$is_backup_dir = 0;
							if (strpos($inp_relative_path, 'backup\\') !== FALSE OR strpos($inp_relative_path, 'backup/') !== FALSE) {
								$is_backup_dir = 1;
							}

							// Relative path : Check for Linux or Windows
							if (strpos($inp_relative_path, '\\') !== FALSE) {
								$inp_relative_path = "$dir\\" . $inp_relative_path;
								$inp_relative_path = str_replace("/", "\\", $inp_relative_path);
								$inp_relative_path = str_replace("..\\", "", $inp_relative_path);
							}
							else{
								$inp_relative_path = "$dir/" . $inp_relative_path;
								$inp_relative_path = str_replace("../", "", $inp_relative_path);
							}

							// Relative path: check for mysql config file
							$relative_path_len = strlen($inp_relative_path);
							if($relative_path_len > 20){
								$relative_path_start = substr("$inp_relative_path", 0, 19);
								if($relative_path_start == "_admin/_data/mysql_"){
									$inp_relative_path = str_replace(".php", ".backup.php", $inp_relative_path);
								}
							}
							$inp_relative_path_mysql = quote_smart($link, $inp_relative_path);

							// Check if relateive path is zip, backup or admin dir
							$file_type = get_extension($inp_relative_path);


							// Add current file to archive
							if($file_type != "zip" && $is_backup_dir == "0"){
								// echo"$filePath &middot; $relativePath &middot; $check_if_zipped_dir<br />";
								// File path
								$inp_web_file_path_mysql = quote_smart($link, $filePath);
							
								// File size
								$inp_file_size = filesize($filePath);
								$inp_web_size_mysql = quote_smart($link, $inp_file_size);

								mysqli_query($link, "INSERT INTO $t_backup_files 
								(file_id, file_backup_id, file_module_id, file_directory_id, file_file_path, 
								file_relative_path, file_size) 
								VALUES 
								(NULL, $get_current_backup_id, 0, 0, $inp_web_file_path_mysql, 
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
									<span>$file_type</span>
								   </td>
								   <td>
									<span>$inp_file_size</span>
								   </td>
								  </tr>
								";
							}
						}
					} 
				} // $rootPath != ""
			} // for


			// Add Index to database dir 
			$inp_file_path = "_data/backup/$get_current_backup_secret/index.html";
			$inp_file_path = realpath($inp_file_path);
			$inp_file_path_mysql = quote_smart($link, $inp_file_path);

			$datetime_clean = str_replace(" ", "_", $get_current_backup_created_datetime);
			$datetime_clean = str_replace(":", "-", $datetime_clean);
			$datetime_clean = substr($datetime_clean, 0, -3);

			$inp_relative_path = "_DATABASE_" . $configWebsiteTitleCleanSav . "_" . $datetime_clean . "_" . $get_current_backup_secret . "/index.html";
			if (strpos($inp_relative_path, '\\') !== FALSE OR strpos($inp_relative_path, 'backup/') !== FALSE) {
				$inp_relative_path = "_DATABASE_" . $configWebsiteTitleCleanSav . "_" . $datetime_clean . "_" . $get_current_backup_secret . "\\index.html";
			}
			$inp_relative_path_mysql = quote_smart($link, $inp_relative_path);

			mysqli_query($link, "INSERT INTO $t_backup_files 
			(file_id, file_backup_id, file_module_id, file_directory_id, file_file_path, 
			file_relative_path, file_size) 
			VALUES 
			(NULL, $get_current_backup_id, 0, 0, $inp_file_path_mysql, 
			$inp_relative_path_mysql, 0)") or die(mysqli_error($link));

								echo"
								  <tr>
								   <td>
									<span>$inp_file_path</span>
								   </td>
								   <td>
									<span>$inp_relative_path</span>
								   </td>
								   <td>
									<span>html</span>
								   </td>
								   <td>
									<span>-</span>
								   </td>
								  </tr>
								";

			echo"
			 </tbody>
			</table>
		<!-- List files -->


		<!-- Refresh -->
			<meta http-equiv=refresh content=\"5; URL=index.php?open=$open&amp;page=$page&amp;action=zip_files&amp;backup_id=$get_current_backup_id&amp;editor_language=$editor_language&amp;l=$l\">
			<!-- Jquery go to URL after x seconds -->
			<!-- In case meta refresh doesnt work -->
				<script>
				\$(document).ready(function(){
					window.setTimeout(function(){
      							// Move to a new location or you can do something else
						window.location.href = \"index.php?open=$open&page=$page&action=zip_files&backup_id=$get_current_backup_id&editor_language=$editor_language&l=$l\";
					}, 10000);
				});
				</script>
			<!-- //Jquery go to URL after x seconds -->
		<!-- //Refresh -->
		";

			
	} // action == "list_files_in_directory"
	elseif($action == "zip_files"){
		echo"
		<!-- Operation -->
			<h2>SQL and Data Zip Files</h2>
			<div style=\"height: 10px;\"></div>
		<!-- //Operation -->

		<!-- Zip files -->
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
			$query = "SELECT file_id, file_backup_id, file_module_id, file_directory_id, file_file_path, file_relative_path, file_size FROM $t_backup_files WHERE file_backup_id=$get_current_backup_id AND file_module_id=0 ORDER BY file_id ASC LIMIT 0,25";
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
			$rand = rand(4,5);

			if($number_of_files > "0"){
				echo"
				<meta http-equiv=refresh content=\"$rand; URL=index.php?open=$open&amp;page=$page&amp;action=$action&amp;backup_id=$get_current_backup_id&amp;editor_language=$editor_language&amp;l=$l&amp;datetime=$datetime_url\">
				<!-- Jquery go to URL after x seconds -->
				<!-- In case meta refresh doesnt work -->
					<script>
					\$(document).ready(function(){
						window.setTimeout(function(){
      						// Move to a new location or you can do something else
							window.location.href = \"index.php?open=$open&page=$page&action=$action&backup_id=$get_current_backup_id&editor_language=$editor_language&l=$l&datetime=$datetime_url\";
					}, 10000);
				});
   				</script>
				<!-- //Jquery go to URL after x seconds -->
				";
			}
			else{
				echo"
				<p>Finished with zipping files for data.</p>

				<meta http-equiv=refresh content=\"$rand; URL=index.php?open=$open&amp;page=new_backup_step_6_cleanup&amp;backup_id=$get_current_backup_id&amp;editor_language=$editor_language&amp;l=$l\">
				<!-- Jquery go to URL after x seconds -->
				<!-- In case meta refresh doesnt work -->
   				<script>
				\$(document).ready(function(){
					window.setTimeout(function(){
        					// Move to a new location or you can do something else
						window.location.href = \"index.php?open=$open&page=new_backup_step_6_cleanup&amp;backup_id=$get_current_backup_id&editor_language=$editor_language&l=$l\";
					}, 10000);
				});
   				</script>
				<!-- //Jquery go to URL after x seconds -->
				";
			}
			echo"
		<!-- //Refresh -->

		";
	} // action

	echo"
		</div> <!-- //backup_column_right -->
	</div> <!-- //backup_row -->
	";
} // backup found
?>