<?php
/**
*
* File: _admin/_inc/exercise/view_exercise_fix_charset.php
* Version 1.0.0
* Date 20:53 09.02.2018
* Copyright (c) 2008-2018 Sindre Andre Ditlefsen
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


/*- Get extention ---------------------------------------------------------------------- */
function getExtension($str) {
	$i = strrpos($str,".");
	if (!$i) { return ""; } 
	$l = strlen($str) - $i;
	$ext = substr($str,$i+1,$l);
	return $ext;
}


/*- Variables ------------------------------------------------------------------------- */
$l_mysql = quote_smart($link, $l);
if(isset($_GET['exercise_id'])){
	$exercise_id = $_GET['exercise_id'];
	$exercise_id = output_html($exercise_id);
}
else{
	$exercise_id = "";
}

/*- Scriptstart ---------------------------------------------------------------------- */


// Get exercise
$exercise_id_mysql = quote_smart($link, $exercise_id);
$query = "SELECT exercise_id, exercise_title, exercise_user_id, exercise_language, exercise_muscle_group_id_main, exercise_muscle_group_id_sub, exercise_muscle_part_of_id, exercise_equipment_id, exercise_type_id, exercise_level_id, exercise_preparation, exercise_guide, exercise_important, exercise_created_datetime, exercise_updated_datetime, exercise_user_ip, exercise_uniqe_hits, exercise_uniqe_hits_ip_block, exercise_likes, exercise_dislikes, exercise_rating, exercise_rating_ip_block, exercise_number_of_comments, exercise_reported, exercise_reported_checked, exercise_reported_reason FROM $t_exercise_index WHERE exercise_id=$exercise_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_exercise_id, $get_current_exercise_title, $get_current_exercise_user_id, $get_current_exercise_language, $get_current_exercise_muscle_group_id_main, $get_current_exercise_muscle_group_id_sub, $get_current_exercise_muscle_part_of_id, $get_current_exercise_equipment_id, $get_current_exercise_type_id, $get_current_exercise_level_id, $get_current_exercise_preparation, $get_current_exercise_guide, $get_current_exercise_important, $get_current_exercise_created_datetime, $get_current_exercise_updated_datetime, $get_current_exercise_user_ip, $get_current_exercise_uniqe_hits, $get_current_exercise_uniqe_hits_ip_block, $get_current_exercise_likes, $get_current_exercise_dislikes, $get_current_exercise_rating, $get_current_exercise_rating_ip_block, $get_current_exercise_number_of_comments, $get_current_exercise_reported, $get_current_exercise_reported_checked, $get_current_exercise_reported_reason) = $row;
	


if($get_current_exercise_id == ""){
}
else {
	function fix_charset($value){

		utf8_decode($value);
		$value = str_replace("Ã¦", "&aelig;",  "$value"); // æ
		$value = str_replace('Ã¸', "&oslash;", "$value"); // ø
		$value = str_replace("Ã¥", "&aring;",  "$value"); // å
		return $value;
	}


	// Fix charset
	$inp_exercise_preparation = fix_charset($get_current_exercise_preparation);
	$inp_exercise_preparation_mysql = quote_smart($link, $inp_exercise_preparation);
	
	$inp_exercise_guide = fix_charset($get_current_exercise_guide);
	$inp_exercise_guide_mysql = quote_smart($link, $inp_exercise_guide);
	
	$inp_exercise_important = fix_charset($get_current_exercise_important);
	$inp_exercise_important_mysql = quote_smart($link, $inp_exercise_important);

	$result = mysqli_query($link, "UPDATE $t_exercise_index SET exercise_preparation=$inp_exercise_preparation_mysql, exercise_guide=$inp_exercise_guide_mysql, exercise_important=$inp_exercise_important_mysql WHERE exercise_id=$get_current_exercise_id") or die(mysqli_error($link));


	
	echo"
	UPDATE $t_exercise_index SET exercise_preparation=$inp_exercise_preparation_mysql, exercise_guide=$inp_exercise_guide_mysql, exercise_important=$inp_exercise_important_mysql WHERE exercise_id=$get_current_exercise_id
	";

} // muscle not found

?>