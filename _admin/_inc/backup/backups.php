<?php
/**
*
* File: _admin/_inc/backup/backups.php
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


/*- Functions -------------------------------------------------------------------------- */
function rmdir_recursive($dirPath){
	// echo"<p>Removing $dirPath...</p>";
    if(!empty($dirPath) && is_dir($dirPath) ){
        $dirObj= new RecursiveDirectoryIterator($dirPath, RecursiveDirectoryIterator::SKIP_DOTS); //upper dirs not included,otherwise DISASTER HAPPENS :)
        $files = new RecursiveIteratorIterator($dirObj, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $path) 
            $path->isDir() && !$path->isLink() ? rmdir($path->getPathname()) : unlink($path->getPathname());
        rmdir($dirPath);
        return true;
    }
    return false;
}

/*- Tables Backup ----------------------------------------------------------------------- */
$t_backup_liquidbase	 = $mysqlPrefixSav . "backup_liquidbase";
$t_backup_index 	 = $mysqlPrefixSav . "backup_index";
$t_backup_modules	 = $mysqlPrefixSav . "backup_modules";


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
	$backup_id = "";
}


/*- Script start ------------------------------------------------------------------------ */
if($action == ""){
	echo"
	<h1>Backups</h1>

	<p>
	<a href=\"index.php?open=$open&amp;page=new_backup&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">New backup</a>
	<a href=\"index.php?open=$open&amp;page=restore&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Restore</a>
	</p>

	<!-- Backups list -->
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span><b>Date</b></span>
		   </th>
		   <th scope=\"col\">
			<span><b>Size</b></span>
		   </th>
		   <th scope=\"col\">
			<span><b>Time</b></span>
		   </th>
		   <th scope=\"col\">
			<span><b>Actions</b></span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>
		";
		// Show all backups
		$query = "SELECT backup_id, backup_created_datetime, backup_created_datetime_saying, backup_zip_dir, backup_zip_file, backup_zip_size, backup_zip_size_human, backup_secret, backup_no_of_modules_total, backup_no_of_modules_finished, backup_start, backup_end, backup_time_used FROM $t_backup_index ORDER BY backup_id DESC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_backup_id, $get_backup_created_datetime, $get_backup_created_datetime_saying, $get_backup_zip_dir, $get_backup_zip_file, $get_backup_zip_size, $get_backup_zip_size_human, $get_backup_secret, $get_backup_no_of_modules_total, $get_backup_no_of_modules_finished, $get_backup_start, $get_backup_end, $get_backup_time_used) = $row;

			echo"
			 <tr>
			  <td>
				<span>
				<a href=\"_data/backup/$get_backup_zip_dir/$get_backup_zip_file\"><img src=\"_inc/backup/_gfx/icons/document_save_tango_16x16.png\" alt=\"document_save_tango_16x16.png\" /></a>
				<a href=\"_data/backup/$get_backup_zip_dir/$get_backup_zip_file\">$get_backup_created_datetime_saying</a>
				</span>
			  </td>
			  <td>
				<span>$get_backup_zip_size_human</span>
			  </td>
			  <td>
				<span>$get_backup_time_used</span>
			  </td>
			  <td>
				<span>
				<a href=\"index.php?open=backup&amp;page=backups&amp;action=delete&amp;backup_id=$get_backup_id&amp;editor_language=$editor_language&amp;l=$l\">Delete</a>
				</span>
			  </td>
			 </tr>
			";
		}
		echo"
		 </tbody>
		</table>
	";

}
elseif($action == "delete"){
	$backup_id_mysql = quote_smart($link, $backup_id);
	$query_t = "SELECT backup_id, backup_created_datetime, backup_created_datetime_saying, backup_zip_dir, backup_zip_file, backup_secret FROM $t_backup_index WHERE backup_id=$backup_id_mysql";
	$result_t = mysqli_query($link, $query_t);
	$row_t = mysqli_fetch_row($result_t);
	list($get_current_backup_id, $get_current_backup_created_datetime, $get_current_backup_created_datetime_saying, $get_current_backup_zip_dir, $get_current_backup_zip_file, $get_current_backup_secret) = $row_t;
	if($get_current_backup_id == ""){
		echo"
		<p>Backup not found</p>
		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">Backup</a>
		</p>
		";
	}
	else{
		if($process == "1"){
			if(file_exists("_data/backup/$get_current_backup_zip_dir/$get_current_backup_zip_file")){
				unlink("_data/backup/$get_current_backup_zip_dir/$get_current_backup_zip_file");
			}
			if(file_exists("_data/backup/$get_current_backup_zip_dir/index.html")){
				unlink("_data/backup/$get_current_backup_zip_dir/index.html");
			}
			if(is_dir("_data/backup/$get_current_backup_zip_dir")){
				rmdir_recursive("_data/backup/$get_current_backup_zip_dir");
			}
			mysqli_query($link, "DELETE FROM $t_backup_index WHERE backup_id=$get_current_backup_id");

			// Header
			$url = "index.php?open=backup&page=backups&editor_language=$editor_language&l=$l&ft=success&fm=backup_deleted";
			header("Location: $url");
			exit;
		}
		echo"
		<h1>Delete backup $get_current_backup_created_datetime_saying</h1>

		<p>
		Are you sure you want to delete the backup?
		</p>


		<p>
		<a href=\"index.php?open=backup&amp;page=backups&amp;action=delete&amp;backup_id=$get_current_backup_id&amp;process=1&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_danger\">Confirm</a>
		<a href=\"index.php?open=backup&amp;page=backups&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Cancel</a>
		</p>
		";
	}
}
?>