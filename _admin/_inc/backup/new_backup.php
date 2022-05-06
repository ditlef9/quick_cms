<?php
/**
*
* File: _admin/_inc/backup/new_backup.php
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
$t_backup_liquidbase	 	= $mysqlPrefixSav . "backup_liquidbase";
$t_backup_index 	 	= $mysqlPrefixSav . "backup_index";
$t_backup_modules	 	= $mysqlPrefixSav . "backup_modules";
$t_backup_last_backed_up_modules = $mysqlPrefixSav . "backup_last_backed_up_modules";

/*- Script start ------------------------------------------------------------------------ */

if($action == ""){

	echo"
	<h1>New Backup</h1>

	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=backup&amp;editor_language=$editor_language&amp;l=$l\">Backup</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=new_backup&amp;editor_language=$editor_language&amp;l=$l\">New backup</a>
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
			$fm = str_replace("_", " ", $fm);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->

	<!-- About backup -->
		<p>
		When you take a backup it will first take a backup of the modules database, then the modules files.
		</p>

		<p>
		To restore a backup you will need to first install a fresh version of the CMS, then insert the database files and 
		then the files.
		</p>

	<!-- //About backup -->

	<!-- Check if I have an ongoing backup -->";
		$query_t = "SELECT backup_id, backup_created_datetime, backup_created_datetime_saying, backup_zip_dir, backup_zip_file, backup_zip_size, backup_zip_size_human, backup_secret, backup_is_finished, backup_no_of_modules_total, backup_no_of_modules_finished, backup_start, backup_end, backup_time_used FROM $t_backup_index WHERE backup_is_finished=0 ORDER BY backup_id DESC";
		$result_t = mysqli_query($link, $query_t);
		$row_t = mysqli_fetch_row($result_t);
		list($get_backup_id, $get_backup_created_datetime, $get_backup_created_datetime_saying, $get_backup_zip_dir, $get_backup_zip_file, $get_backup_zip_size, $get_backup_zip_size_human, $get_backup_secret, $get_backup_is_finished, $get_backup_no_of_modules_total, $get_backup_no_of_modules_finished, $get_backup_start, $get_backup_end, $get_backup_time_used) = $row_t;
		if($get_backup_id != ""){
			echo"
			<p><b>Unfinished backup</b><br />
			You have an unfinished backup in progress. You can continue the backup of database and files or start on a new backup.<br /><br />
			<a href=\"index.php?open=backup&amp;page=new_backup_step_2_list_modules&amp;backup_id=$get_backup_id&amp;editor_language=$editor_language&amp;l=$l\" class=\"btn_default\">Continue backup from $get_backup_created_datetime_saying</a>
			</p>
			";
		}
		echo"
	<!-- //Check if I have an ongoing backup -->

	<!-- Select modules to take backup of -->

		<script>
		\$(document).ready(function(){
			\$('[name=\"inp_text\"]').focus();
		});
		</script>
			
		<form method=\"post\" action=\"index.php?open=$open&amp;page=new_backup_step_1_generate_row&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

		<p><input type=\"submit\" value=\"Create backup\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>

		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">";

			// Check all: Check or not chekced?
			$query_t = "SELECT module_id, module_name, module_name_clean FROM $t_backup_last_backed_up_modules WHERE module_name_clean='all'";
			$result_t = mysqli_query($link, $query_t);
			$row_t = mysqli_fetch_row($result_t);
			list($get_module_id, $get_module_name, $get_module_name_clean) = $row_t;
			echo"
			<span><input type=\"checkbox\" id=\"checkAll\""; if($get_module_id != ""){ echo" checked=\"checked\"";} echo" /></span>
	
			<!-- Check all Javascript -->
			<script>
			\$(document).ready(function(){
				\$(\"#checkAll\").click(function () {
					\$('input:checkbox').not(this).prop('checked', this.checked);
				});
			});
			</script>
			<!-- //Check all Javascript -->

		   </th>
		   <th scope=\"col\">
			<span><b>Module</b></span>
		   </th>
		  </tr>
		 </thead>
		 <tbody>
		";
		// Show all modules that has backup functions
		$filenames = "";
		$dir = "_inc/";
		if ($handle = opendir($dir)) {
			$files = array();   
			while (false !== ($file = readdir($handle))) {
				if ($file === '.') continue;
				if ($file === '..') continue;
				if ($file === "admin_cms") continue;
				if ($file === "dashboard") continue;
				if ($file === "backup") continue;
				if ($file === "login") continue;
				if ($file === "ucp") continue;
				if ($file === "setup") continue;
				array_push($files, $file);
			}
			
			sort($files);
			foreach ($files as $file){
				// $content_saying = 
				$admin_navigation_clean = "$file";
				$admin_navigation_title = ucfirst($file);
				$admin_navigation_icon = "$file";
				$admin_navigation_icon_black_small = $file . "_black_18x18.png";
				$admin_navigation_icon_black_medium = $file . "_black_24x24.png";
				$admin_navigation_icon_white_small = $file . "_white_18x18.png";
				$admin_navigation_icon_white_medium = $file . "t_white_24x24.png";

				// Check or not chekced?
				$module_clean_mysql = quote_smart($link, $admin_navigation_clean);
				$query_t = "SELECT module_id, module_name, module_name_clean FROM $t_backup_last_backed_up_modules WHERE module_name_clean=$module_clean_mysql";
				$result_t = mysqli_query($link, $query_t);
				$row_t = mysqli_fetch_row($result_t);
				list($get_module_id, $get_module_name, $get_module_name_clean) = $row_t;


				echo"
					 <tr>
					  <td>
						<span>
						<input type=\"checkbox\" name=\"inp_$admin_navigation_clean\""; if($get_module_id != ""){ echo" checked=\"checked\"";} echo" />
						</span>
					  </td>
					  <td>
						<span>
						<img src=\"_inc/$file/_gfx/icons/$admin_navigation_icon_black_small\" alt=\"$admin_navigation_icon_black_small\" /> $admin_navigation_title
						</span>
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

		// Test: Check or not chekced?
		$query_t = "SELECT module_id, module_name, module_name_clean FROM $t_backup_last_backed_up_modules WHERE module_name_clean='test'";
		$result_t = mysqli_query($link, $query_t);
		$row_t = mysqli_fetch_row($result_t);
		list($get_module_id, $get_module_name, $get_module_name_clean) = $row_t;
		echo"
		<p>Use test-mode (to get debug information)<br />
		<input type=\"radio\" name=\"inp_test\" value=\"1\" "; if($get_module_id != ""){ echo" checked=\"checked\"";} echo" /> Yes
		<input type=\"radio\" name=\"inp_test\" value=\"0\" "; if($get_module_id == ""){ echo" checked=\"checked\"";} echo" /> No
		</p>

		<p><input type=\"submit\" value=\"Create backup\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>
		</form>
	<!-- //Select modules to take backup of -->
	";

}
?>