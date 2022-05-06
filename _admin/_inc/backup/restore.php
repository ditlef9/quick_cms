<?php
/**
*
* File: _admin/_inc/backup/restore.php
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

/*- Script start ------------------------------------------------------------------------ */
echo"
<h1>Restore</h1>


<p>
To restore a backup place the zip-file in the root of the CMS. Then load it by clicking the corresponding
file name below.
</p>

<!-- Browse files in root -->
	<table class=\"hor-zebra\">
	 <thead>
	  <tr>
	   <th>
		<span>File name</span>
	   </th>
	   <th>
		<span>Modifed</span>
	   </th>
	   <th>
		<span>Size</span>
	   </th>
	  </tr>
	 </thead>
	 <tbody>
	";

	$files_found = 0;
	$filenames = "";
	$dir = "../";
	if ($handle = opendir($dir)) {
		$files = array();   
		while (false !== ($file = readdir($handle))) {
			if ($file === '.') continue;
			if ($file === '..') continue;
			array_push($files, $file);
		}
			
		sort($files);
		foreach ($files as $file){
			// $content_saying = 
			$file_name_clean = "$file";

			$ext = get_extension($file);

			if($ext == "zip"){

				$modified = date("j M Y H:i", filemtime("../$file"));

				$size = filesize("../$file");
				$size_human = format_size($size);
				echo"
				  <tr>
				   <td>
					<span><a href=\"index.php?open=backup&amp;page=restore_step_1_files&amp;file=$file&amp;editor_language=no&amp;l=$l\">$file</a></span>
				   </td>
				   <td>
					<span>$modified</span>
				   </td>
				   <td>
					<span>$size_human</span>
				   </td>
				  </tr>
				";
				$files_found++;
			}
			
		} // foreach file
	} // open dir
	echo"
	 </tbody>
	</table>
	";
	if($files_found == "0"){
		echo"<div class=\"info\"><p>No files found</p></div>\n";
	}
	echo"
<!-- //Browse files in root -->
";
?>