<?php 
/**
*
* File: food/delete_equipment.php
* Version 1.0.0
* Date 12:05 10.02.2018
* Copyright (c) 2011-2018 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Configuration --------------------------------------------------------------------- */
$pageIdSav            = "2";
$pageNoColumnSav      = "2";
$pageAllowCommentsSav = "0";

/*- Root dir -------------------------------------------------------------------------- */
// This determine where we are
if(file_exists("favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
elseif(file_exists("../../../../favicon.ico")){ $root = "../../../.."; }
else{ $root = "../../.."; }

/*- Website config -------------------------------------------------------------------- */
include("$root/_admin/website_config.php");

/*- Tables ---------------------------------------------------------------------------- */
include("_tables_exercises.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/exercises/ts_new_equipment.php");
include("$root/_admin/_translations/site/$l/exercises/ts_new_exercise.php");

/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);

if(isset($_GET['equipment_id'])){
	$equipment_id = $_GET['equipment_id'];
	$equipment_id = output_html($equipment_id);
}
else{
	$equipment_id = "";
}
/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_delete_equipment - $l_my_equipment - $l_exercises";
include("$root/_webdesign/header.php");


/*- Functions -------------------------------------------------------------------------------- */
function delete_cache($dirname) {
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


/*- Content ---------------------------------------------------------------------------------- */
// Logged in?
if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
	
	// Get my user
	$my_user_id = $_SESSION['user_id'];
	$my_user_id = output_html($my_user_id);
	$my_user_id_mysql = quote_smart($link, $my_user_id);
	$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_rank) = $row;

	// Get equipment
	$equipment_id_mysql = quote_smart($link, $equipment_id);
	$query = "SELECT equipment_id, equipment_title, equipment_title_clean, equipment_user_id, equipment_language, equipment_muscle_group_id_main, equipment_muscle_group_id_sub, equipment_muscle_part_of_id, equipment_text, equipment_image_path, equipment_image_file FROM $t_exercise_equipments WHERE equipment_id=$equipment_id_mysql AND equipment_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_equipment_id, $get_equipment_title, $get_equipment_title_clean, $get_equipment_user_id, $get_equipment_language, $get_equipment_muscle_group_id_main, $get_equipment_muscle_group_id_sub, $get_equipment_muscle_part_of_id, $get_equipment_text, $get_equipment_image_path, $get_equipment_image_file) = $row;
	
	

	if($get_equipment_id == ""){
		echo"<p>Equipment not found.</p>";
	}
	else{
		if($action == ""){
			if($process == 1){






				// Images
				if($get_equipment_image_file != "" && file_exists("$root/$get_equipment_image_path/$get_equipment_image_file")){
					unlink("$root/$get_equipment_image_path/$get_equipment_image_file");
				}




				// Update MySQL
				$result = mysqli_query($link, "DELETE FROM $t_exercise_equipments WHERE equipment_id=$equipment_id_mysql");


				
				$url = "my_equipment.php?l=$l&ft=success&fm=equipment_deleted";
				header("Location: $url");
				exit;
			} // process


			echo"
			<h1>$get_equipment_title</h1>
	
			<p>$l_are_you_sure_you_want_to_delete_the_equipment</p>

			<p>
			<a href=\"delete_equipment.php?equipment_id=$equipment_id&amp;l=$l&amp;process=1\" class=\"btn btn_warning\">$l_yes</a>
			<a href=\"my_equipment.php?l=$l\" class=\"btn btn_default\">$l_cancel</a>
			</p>
			</form>
		<!-- //Form -->
			";
		} // action == ""
	} // found
}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/exercises/my_exercises.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>