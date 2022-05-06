<?php 
/**
*
* File: exercise/new_equipment_step_2_categorization.php
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

/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";

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




/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_new_equipment - $l_exercises";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");

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

		if($process == "1"){

			
			// Find sub
			$inp_exercise_muscle_group_id_sub = $_POST['inp_exercise_muscle_group_id_sub'];
			$inp_exercise_muscle_group_id_sub = output_html($inp_exercise_muscle_group_id_sub);
			$inp_exercise_muscle_group_id_sub_mysql = quote_smart($link, $inp_exercise_muscle_group_id_sub);
			if(empty($inp_exercise_muscle_group_id_sub)){
				$url = "new_equipment_step_2_categorization.php?equipment_id=$equipment_id&l=$l";
				header("Location: $url");
				exit;
			}
		
			$query = "SELECT muscle_group_id, muscle_group_parent_id FROM $t_muscle_groups WHERE muscle_group_id=$inp_exercise_muscle_group_id_sub_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_sub_muscle_group_id, $get_sub_muscle_group_parent_id) = $row;
			if($get_sub_muscle_group_id == ""){
				$url = "new_equipment_step_2_categorization.php?equipment_id=$equipment_id&l=$l";
				header("Location: $url");
				exit;
			}
			
			// Find main
			$query = "SELECT muscle_group_id, muscle_group_parent_id FROM $t_muscle_groups WHERE muscle_group_id='$get_sub_muscle_group_parent_id'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_main_muscle_group_id, $get_muscle_group_parent_id) = $row;
			if($get_main_muscle_group_id == ""){

				$url = "new_equipment_step_2_categorization.php?equipment_id=$equipment_id&l=$l";
				header("Location: $url");
				exit;
			}
			
				
			// Update
			$result = mysqli_query($link, "UPDATE $t_exercise_equipments SET equipment_muscle_group_id_main=$get_main_muscle_group_id,
							equipment_muscle_group_id_sub=$get_sub_muscle_group_id WHERE equipment_id=$equipment_id_mysql");



			// Header
			$url = "new_equipment_step_3_text.php?equipment_id=$get_equipment_id&main_group_id=$get_main_muscle_group_id&sub_group_id=$inp_exercise_muscle_group_id_sub&l=$l";
			header("Location: $url");
			exit;

		} // process
	
		echo"
		<h1>$l_new_equipment</h1>
	

		<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->


		<!-- Form -->

			<form method=\"post\" action=\"new_equipment_step_2_categorization.php?equipment_id=$equipment_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	
			<p><b>$l_muscle_group:</b><br />
			<select name=\"inp_exercise_muscle_group_id_sub\">
				<option value=\"0\">- $l_please_select - </option>
				<option value=\"\"></option>\n";
				// Get groups
				$query_main = "SELECT muscle_group_id, muscle_group_name FROM $t_muscle_groups WHERE muscle_group_parent_id='0'";
				$result_main = mysqli_query($link, $query_main);
				while($row_main = mysqli_fetch_row($result_main)) {
					list($get_main_muscle_group_id, $get_main_muscle_group_name) = $row_main;
					// Translation
					$query_translation = "SELECT muscle_group_translation_id,muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_main_muscle_group_id AND muscle_group_translation_language=$l_mysql";
					$result_translation = mysqli_query($link, $query_translation);
					$row_translation = mysqli_fetch_row($result_translation);
					list($get_main_muscle_group_translation_id, $get_main_muscle_group_translation_name) = $row_translation;

					echo"				";
					echo"<option value=\"$get_main_muscle_group_id\">$get_main_muscle_group_translation_name</option>\n";
			
					// Get sub categories
					$query_sub = "SELECT muscle_group_id, muscle_group_name FROM $t_muscle_groups WHERE muscle_group_parent_id='$get_main_muscle_group_id'";
					$result_sub = mysqli_query($link, $query_sub);
					while($row_sub = mysqli_fetch_row($result_sub)) {
						list($get_sub_muscle_group_id, $get_sub_muscle_group_name) = $row_sub;
						// Translation
						$query_translation = "SELECT muscle_group_translation_id,muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_sub_muscle_group_id AND muscle_group_translation_language=$l_mysql";
						$result_translation = mysqli_query($link, $query_translation);
						$row_translation = mysqli_fetch_row($result_translation);
						list($get_sub_muscle_group_translation_id, $get_sub_muscle_group_translation_name) = $row_translation;

						echo"				";
						echo"<option value=\"$get_sub_muscle_group_id\""; if($get_sub_muscle_group_id == "$get_equipment_muscle_group_id_sub"){ echo" selected=\"selected\""; } echo">&nbsp; &nbsp; $get_sub_muscle_group_translation_name</option>\n";
					}

					echo"				";
					echo"<option value=\"0\"> </option>\n";
				}
			echo"
			</select></p>





			<p>
			<input type=\"submit\" value=\"$l_save\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			</form>
		<!-- //Form -->
		";
	} // found
}
else{
	echo"
	<h1>
	<img src=\"$root/_webdesign/images/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/index.php?page=login&amp;l=$l&amp;refer=$root/exercises/new_exercise.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>