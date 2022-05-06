<?php
/**
*
* File: _admin/_inc/backup/restore_step_1
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
if(isset($_GET['file'])) {
	$file = $_GET['file'];
	$file = strip_tags(stripslashes($file));
	if (strpos($file, '/') !== FALSE) {
		echo"File has illegal character";
		die;
	}
}
else{
	echo"Missing file";
	die;
	$file = "";
}

/*- Script start ------------------------------------------------------------------------ */
if(!(file_exists("../$file"))){
	echo"File doesnt exists.";
	die;
} // file exists

// Correct file type
$mime = mime_content_type("../$file");
if($mime != "application/zip"){
	echo"Invalid mime";
	die;
}

// Start
$path = pathinfo(realpath("../$file"), PATHINFO_DIRNAME);

$zip = new ZipArchive;
$res = $zip->open("../$file");
if ($res === TRUE) {
	// extract it to the path we determined above
	$zip->extractTo("../");
	$zip->close();
	echo "<p><b>$file</b> extracted to <b>$path</b></p>";
} else {
	echo "<p>Couldn't open $file</p>";
	die;
}



// Refresh
$rand = rand(3,4);
echo"
<meta http-equiv=refresh content=\"$rand; URL=index.php?open=$open&amp;page=restore_step_2_db&amp;file=$file&amp;editor_language=$editor_language&amp;l=$l\">
<!-- Jquery go to URL after x seconds -->
	<!-- In case meta refresh doesnt work -->
	<script>
	\$(document).ready(function(){
		window.setTimeout(function(){
			// Move to a new location or you can do something else
			window.location.href = \"index.php?open=$open&page=restore_step_2_db&file=$file&editor_language=$editor_language&l=$l\";
		}, 10000);
	});
	</script>
<!-- //Jquery go to URL after x seconds -->
";
					
?>