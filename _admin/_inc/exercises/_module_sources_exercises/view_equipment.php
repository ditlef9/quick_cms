<?php 
/**
*
* File: exercise/view_equipment.php
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

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['equipment_id'])){
	$equipment_id = $_GET['equipment_id'];
	$equipment_id = output_html($equipment_id);
}
else{
	$equipment_id = "";
}

$tabindex = 0;
$l_mysql = quote_smart($link, $l);


// Get equipment
$equipment_id_mysql = quote_smart($link, $equipment_id);
$query = "SELECT equipment_id, equipment_title, equipment_title_clean, equipment_user_id, equipment_language, equipment_muscle_group_id_main, equipment_muscle_group_id_sub, equipment_muscle_part_of_id, equipment_text, equipment_image_path, equipment_image_file FROM $t_exercise_equipments WHERE equipment_id=$equipment_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_equipment_id, $get_equipment_title, $get_equipment_title_clean, $get_equipment_user_id, $get_equipment_language, $get_equipment_muscle_group_id_main, $get_equipment_muscle_group_id_sub, $get_equipment_muscle_part_of_id, $get_equipment_text, $get_equipment_image_path, $get_equipment_image_file) = $row;
	

if($get_equipment_id == ""){
	echo"<p>Equipment not found.</p>";
	die;
}


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$get_equipment_title - $l_equipment - $l_exercises";
include("$root/_webdesign/header.php");


echo"
<h1>$get_equipment_title</h1>


<!-- Img -->
	";
	if($get_equipment_image_file != "" && file_exists("$root/$get_equipment_image_path/$get_equipment_image_file")){
		echo"	
		<p>
		<img src=\"$root/$get_equipment_image_path/$get_equipment_image_file\" alt=\"$root/$get_equipment_image_path/$get_equipment_image_file\" />
		</p>";
	}
	echo"
<!-- //Img -->

<!-- Text -->
	$get_equipment_text
<!-- //Text -->
";



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>