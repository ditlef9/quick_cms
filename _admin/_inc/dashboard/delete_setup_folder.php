<?php
/**
*
* File: _admin/_inc/delete_setup_folder.php
* Version 1
* Date 14:58 02.04.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Functions -------------------------------------------------------------------------- */
function delete_directory($dirname) {
	if (is_dir($dirname))
		$dir_handle = opendir($dirname);
	if (!$dir_handle)
		return false;
	while($file = readdir($dir_handle)) {
		if ($file != "." && $file != "..") {
			if (!is_dir($dirname."/".$file))
				unlink($dirname."/".$file);
        	else
                	delete_directory($dirname.'/'.$file);
          	}
     	}
     	closedir($dir_handle);
     	rmdir($dirname);
	return true;
}


if(file_exists("setup/index.php")){
	if($process == "1"){
		delete_directory("setup");
		
		$url = "index.php?open=$open&page=default&editor_language=$editor_language&l=$l&ft=success&fm=setup_folder_deleted";
		header("Location: $url");
		exit;
	}
	echo"
	<h1>Delete setup folder</h1>


	<p>
	Do you want to delete the setup folder?
	</p>

	<p>
	<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" class=\"btn_danger\">Confirm delete</a>
	</p>
	";
} // tables exists

?>