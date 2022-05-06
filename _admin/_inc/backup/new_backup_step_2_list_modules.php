<?php
/**
*
* File: _admin/_inc/backup/new_backup_step_2_list_modules
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
$backup_id_mysql = quote_smart($link, $backup_id);
$query_t = "SELECT backup_id, backup_created_datetime, backup_created_datetime_saying, backup_zip_dir, backup_zip_file, backup_zip_size, backup_zip_size_human, backup_secret, backup_no_of_modules_total, backup_no_of_modules_finished, backup_start, backup_end, backup_time_used FROM $t_backup_index WHERE backup_id=$backup_id_mysql";
$result_t = mysqli_query($link, $query_t);
$row_t = mysqli_fetch_row($result_t);
list($get_current_backup_id, $get_current_backup_created_datetime, $get_current_backup_created_datetime_saying, $get_current_backup_zip_dir, $get_current_backup_zip_file, $get_current_backup_zip_size, $get_current_backup_zip_size_human, $get_current_backup_secret, $get_current_backup_no_of_modules_total, $get_current_backup_no_of_modules_finished, $get_current_backup_start, $get_current_backup_end, $get_current_backup_time_used) = $row_t;
if($get_current_backup_id == ""){
	echo"
	<p>Backup not found</p>
	<p>
	<a href=\"index.php?open=$open&amp;page=backup&amp;editor_language=$editor_language&amp;l=$l\">Backup</a>
	|
	<a href=\"index.php?open=$open&amp;page=new_backup&amp;editor_language=$editor_language&amp;l=$l\">New backup</a>
	</p>
	";
}
else{
	echo"
	<h1><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> New Backup no $get_current_backup_id</h1>


	<div class=\"backup_row\">
		<div class=\"backup_column_left\">
			


	<!-- Modules backup status -->
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\" style=\"padding: 2px;\">
			<span><b>Module</b></span>
		   </th>
		   <th scope=\"col\" style=\"padding: 2px;text-align: center;\">
			<span><b>Tables</b></span>
		   </th>
		   <th scope=\"col\" style=\"padding: 2px;text-align: center;\">
			<span><b>Files</b></span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>
		";
		// Show all modules that has backup functions
		$inp_backup_no_of_modules_finished = 0;
		$refresh_url = "";
		$query = "SELECT module_id, module_name, module_name_clean, module_icon_black_18x18, module_tables_finished, module_files_finished, module_backup_completed FROM $t_backup_modules WHERE module_backup_id=$get_current_backup_id";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_module_id, $get_module_name, $get_module_name_clean, $get_module_icon_black_18x18, $get_module_tables_finished, $get_module_files_finished, $get_module_backup_completed) = $row;

			if($get_module_backup_completed == "1"){
				$inp_backup_no_of_modules_finished++;
			}

			echo"
			 <tr>
			  <td style=\"padding: 2px;\">
				<span>
				<img src=\"_inc/$get_module_name_clean/_gfx/icons/$get_module_icon_black_18x18\" alt=\"$get_module_icon_black_18x18\" /> $get_module_name
				</span>
			  </td>
			  <td style=\"padding: 2px;text-align: center;\">
				<span>";
				if($get_module_tables_finished == "0" && $refresh_url == ""){
					$refresh_url = "index.php?open=backup&amp;page=new_backup_step_3_take_backup_of_tables&amp;module_id=$get_module_id&amp;editor_language=$editor_language&amp;l=$l";
				}
				elseif($get_module_tables_finished == "1"){
					echo"&#10003;";
				}
				echo"
				</span>
			  </td>
			  <td style=\"padding: 2px;text-align: center;\">
				<span>";
				if($get_module_files_finished == "0" && $refresh_url == ""){
					$refresh_url = "index.php?open=backup&amp;page=new_backup_step_4_take_backup_of_files&amp;module_id=$get_module_id&amp;editor_language=$editor_language&amp;l=$l";
				}
				elseif($get_module_files_finished == "1"){
					echo"&#10003;";
				}
				echo"
				</span>
			  </td>
			 </tr>
			";
		}

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

		$inp_zip_size = 0;
		if(file_exists("$get_current_backup_zip_dir/$get_current_backup_zip_file")){
			$inp_zip_size = filesize("$get_current_backup_zip_dir/$get_current_backup_zip_file");
		}
		$inp_zip_size_mysql = quote_smart($link, $inp_zip_size);
	
		$inp_zip_size_human = format_size($inp_zip_size);
		$inp_zip_size_human_mysql = quote_smart($link, $inp_zip_size_human);

	
		mysqli_query($link, "UPDATE $t_backup_index SET 
					backup_zip_size=$inp_zip_size_mysql,
					backup_zip_size_human=$inp_zip_size_human_mysql, 
					backup_no_of_modules_finished=$inp_backup_no_of_modules_finished, 
					backup_end='$time',
					backup_time_used=$inp_time_used_mysql
					WHERE backup_id=$get_current_backup_id") or die(mysqli_error($link));
		

		echo"
		 </tbody>
		</table>
	<!-- //Modules backup status -->
		</div>
		<div class=\"backup_column_right\">

			<!-- Listing modules for status -->
				<h2>Listing modules for status</h2>

				<p><b>Time used:</b> $inp_time_used<br />
				<b>Modules finished:</b> $inp_backup_no_of_modules_finished of $get_current_backup_no_of_modules_total<br />
				<b>Zip size:</b> $inp_zip_size_human
				</p>
			<!-- //Listing modules for status -->

			
		</div> <!-- //backup_column_right -->
	</div> <!-- //backup_row -->



	<!-- Refresh -->
		";
		if($refresh_url != ""){
			echo"<meta http-equiv=refresh content=\"1; URL=$refresh_url\">";
		}
		else{
			echo"<p>Done!</p>
			<meta http-equiv=refresh content=\"1; URL=index.php?open=backup&amp;page=new_backup_step_5_take_backup_sql_and_data&amp;backup_id=$get_current_backup_id&amp;editor_language=$editor_language&amp;l=$l\">
			";
		}
		echo"
	<!-- //Refresh -->
	";

}
?>