<?php
/**
*
* File: _admin/_inc/backup/new_backup_step_6_cleanup.php
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
$query_t = "SELECT backup_id, backup_created_datetime, backup_created_datetime_saying, backup_zip_dir, backup_zip_file, backup_secret, backup_start FROM $t_backup_index WHERE backup_id=$backup_id_mysql";
$result_t = mysqli_query($link, $query_t);
$row_t = mysqli_fetch_row($result_t);
list($get_current_backup_id, $get_current_backup_created_datetime, $get_current_backup_created_datetime_saying, $get_current_backup_zip_dir, $get_current_backup_zip_file, $get_current_backup_secret, $get_current_backup_start) = $row_t;
if($get_current_backup_id == ""){
	echo"
	<p>Backup not found</p>
	<p>
	<a href=\"index.php?open=$open&amp;page=backup&amp;editor_language=$editor_language&amp;l=$l\">Backup</a>
	</p>
	";
}
else{
	// Calculate
	$time = time();
	$diff = $time-$get_current_backup_start;
	
	$s = $diff%60;
	$m = floor(($diff%3600)/60);
	$h = floor(($diff%86400)/3600);
	$d = floor(($diff%2592000)/86400);
	// $M = floor($diff/2592000);
	$inp_time_used = "$s sec";
	if($d != "0"){
		$inp_time_used = "$d day $h hour $m min";
	}
	else{
		if($h != "0"){
			$inp_time_used = "$h hour $m min";
		}
		else{
			if($m != "0"){
				$inp_time_used = "$m min $s sec";
			}
		}
	}
	$inp_time_used_mysql = quote_smart($link, $inp_time_used);

	$inp_zip_size = filesize("_data/backup/$get_current_backup_zip_file");
	$inp_zip_size_mysql = quote_smart($link, $inp_zip_size);

	$inp_zip_size_human = format_size($inp_zip_size);
	$inp_zip_size_human_mysql = quote_smart($link, $inp_zip_size_human);

	
	mysqli_query($link, "UPDATE $t_backup_index SET 
		backup_zip_size=$inp_zip_size_mysql,
		backup_zip_size_human=$inp_zip_size_human_mysql, 
		backup_is_finished=1,
		backup_end='$time',
		backup_time_used=$inp_time_used_mysql
		WHERE backup_id=$get_current_backup_id") or die(mysqli_error($link));


	// Email

	// Who is moderator of the week?
	$week = date("W");
	$year = date("Y");
	$query = "SELECT moderator_user_id, moderator_user_email, moderator_user_name FROM $t_users_moderator_of_the_week WHERE moderator_week=$week AND moderator_year=$year";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name) = $row;
	if($get_moderator_user_id == ""){
		// Create moderator of the week
		include("_functions/create_moderator_of_the_week.php");
					
		$query = "SELECT moderator_user_id, moderator_user_email, moderator_user_name FROM $t_users_moderator_of_the_week WHERE moderator_week=$week AND moderator_year=$year";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name) = $row;
	}


	$date_saying = date("j F Y");
	$subject = "$configWebsiteTitleSav Backup $date_saying";

	$message = "<html>\n";
	$message = $message. "<head>\n";
	$message = $message. "  <title>$subject</title>\n";
	$message = $message. " </head>\n";
	$message = $message. "<body>\n";


	$message = "<h1>Backup $get_current_backup_created_datetime_saying</h1>\n\n";
	$message = "<h2>Dear $get_moderator_user_name</h2>\n\n";
	$message = $message . "<p>You are the moderator of the week for $year/$week. A backup is ready for download.</p>\n\n";

	$message = $message . "<p>\n";
	$message = $message . "Download: <a href=\"$configControlPanelURLSav/index.php?open=backup&amp;page=backups\">$get_current_backup_zip_file</a><br />\n";
	$message = $message . "File size: $inp_zip_size_human\n";
	$message = $message . "</p>\n\n";

	$message = $message . "<p>\n";
	$message = $message . "To restore the backup install a fresh copy of ";
	$message = $message . "<a href=\"$cmsWebsiteSav\">$cmsNameSav</a>,\n";
	$message = $message . "then log into the control panel and go to \n";
	$message = $message . "Backup -&gt; Restore\n";
	$message = $message . "</p>\n\n";

	$message = $message . "<p>\n";
	$message = $message . "--<br />\n";
	$message = $message . "Yours sincerely<br />\n";
	$message = $message . "$configWebsiteWebmasterSav at $configWebsiteTitleSav\n";
	$message = $message . "</p>\n\n";
	$message = $message. "</body>\n";
	$message = $message. "</html>\n";

	$headers[] = 'MIME-Version: 1.0';
	$headers[] = 'Content-type: text/html; charset=utf-8';
	$headers[] = "From: $configFromNameSav <" . $configFromEmailSav . ">";
	if($configMailSendActiveSav == "1"){
		mail($get_moderator_user_email, $subject, $message, implode("\r\n", $headers));
	}

	
	// Truncate temp tables
	mysqli_query($link, "TRUNCATE $t_backup_modules");
	mysqli_query($link, "TRUNCATE $t_backup_directories");
	mysqli_query($link, "TRUNCATE $t_backup_files");


	echo"
	<h1><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> New Backup &middot; $get_current_backup_id</h1>


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
			<table>
			 <tr>
			  <td style=\"padding-right: 4px;\">
				<span><b>Operation:</b></span>
			  </td>
			  <td>
				<span>Cleanup</span>
			  </td>
			 </tr>
			</table>
			<div style=\"height: 10px;\"></div>
		<!-- //Operation -->


		<!-- List files -->
			<table class=\"hor-zebra\">
			 <thead>
			  <tr>
			   <th>
				<span>Dir name</span>
			   </th>
			  </tr>
			 </thead>
			 <tbody>
			";

			// Delete all folders
			$filenames = "";
			$dir = "_data/backup/";
			if ($handle = opendir($dir)) {
				$files = array();   
				while (false !== ($file = readdir($handle))) {
					if ($file === '.') continue;
					if ($file === '..') continue;
					if ($file === "index.html") continue;
					array_push($files, $file);
				}
			
				sort($files);
				foreach ($files as $file){
					// Add current file to archive
					if(is_dir("_data/backup/$file")){
						rmdir_recursive("_data/backup/$file");
						echo"
						  <tr>
						   <td>
							<span>$file</span>
						   </td>
						  </tr>
						";
					}
				} // foreach file
			} // open dir

	
			echo"
			 </tbody>
			</table>
		<!-- List files -->

		<!-- Refresh -->
			<meta http-equiv=refresh content=\"6; URL=index.php?open=$open&amp;page=backups&amp;editor_language=$editor_language&amp;l=$l\">
			<!-- Jquery go to URL after x seconds -->
			<!-- In case meta refresh doesnt work -->
				<script>
				\$(document).ready(function(){
					window.setTimeout(function(){
      							// Move to a new location or you can do something else
						window.location.href = \"index.php?open=$open&page=backups&editor_language=$editor_language&l=$l\";
					}, 10000);
				});
				</script>
			<!-- //Jquery go to URL after x seconds -->
		<!-- //Refresh -->
		";

			
	} // action == ""

	echo"
		</div> <!-- //backup_column_right -->
	</div> <!-- //backup_row -->
	";
} // backup found
?>