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
if(isset($get_current_backup_id)){
	echo"
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
		$query = "SELECT module_id, module_name, module_name_clean, module_icon_black_18x18, module_tables_finished, module_files_finished, module_backup_completed FROM $t_backup_modules WHERE module_backup_id=$get_current_backup_id";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_module_id, $get_module_name, $get_module_name_clean, $get_module_icon_black_18x18, $get_module_tables_finished, $get_module_files_finished, $get_module_backup_completed) = $row;

			echo"
			 <tr>
			  <td style=\"padding: 2px;\">
				<span>
				<img src=\"_inc/$get_module_name_clean/_gfx/icons/$get_module_icon_black_18x18\" alt=\"$get_module_icon_black_18x18\" /> $get_module_name
				</span>
			  </td>
			  <td style=\"padding: 2px;text-align: center;\">
				<span>";
				if($get_module_tables_finished == "1"){
					echo"&#10003;";
				}
				echo"
				</span>
			  </td>
			  <td style=\"padding: 2px;text-align: center;\">
				<span>";
				if($get_module_files_finished == "1"){
					echo"&#10003;";
				}
				echo"
				</span>
			  </td>
			 </tr>
			";
		}
		echo"
		 </tbody>
		</table>
	";

} // isset backup
?>