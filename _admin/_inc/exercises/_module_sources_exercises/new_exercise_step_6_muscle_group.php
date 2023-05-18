<?php 
/**
*
* File: exercise/new_exercise_step_6_muscle_group.php
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

/*- Tables ---------------------------------------------------------------------------- */
$t_search_engine_index 		= $mysqlPrefixSav . "search_engine_index";
$t_search_engine_access_control = $mysqlPrefixSav . "search_engine_access_control";

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/exercises/ts_new_exercise.php");

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['exercise_id'])){
	$exercise_id = $_GET['exercise_id'];
	$exercise_id = output_html($exercise_id);
}
else{
	$exercise_id = "";
}

$tabindex = 0;
$l_mysql = quote_smart($link, $l);




/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_new_exercise - $l_exercises";
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

	// Get exercise
	$exercise_id_mysql = quote_smart($link, $exercise_id);
	$query = "SELECT exercise_id, exercise_title, exercise_user_id, exercise_language, exercise_muscle_group_id_main, exercise_muscle_group_id_sub, exercise_muscle_part_of_id, exercise_equipment_id, exercise_type_id, exercise_level_id, exercise_preparation, exercise_guide, exercise_important, exercise_created_datetime, exercise_updated_datetime, exercise_user_ip, exercise_uniqe_hits, exercise_uniqe_hits_ip_block, exercise_likes, exercise_dislikes, exercise_rating, exercise_rating_ip_block, exercise_number_of_comments, exercise_reported, exercise_reported_checked, exercise_reported_reason FROM $t_exercise_index WHERE exercise_id=$exercise_id_mysql AND exercise_user_id=$my_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_exercise_id, $get_exercise_title, $get_exercise_user_id, $get_exercise_language, $get_exercise_muscle_group_id_main, $get_exercise_muscle_group_id_sub, $get_exercise_muscle_part_of_id, $get_exercise_equipment_id, $get_exercise_type_id, $get_exercise_level_id, $get_exercise_preparation, $get_exercise_guide, $get_exercise_important, $get_exercise_created_datetime, $get_exercise_updated_datetime, $get_exercise_user_ip, $get_exercise_uniqe_hits, $get_exercise_uniqe_hits_ip_block, $get_exercise_likes, $get_exercise_dislikes, $get_exercise_rating, $get_exercise_rating_ip_block, $get_exercise_number_of_comments, $get_exercise_reported, $get_exercise_reported_checked, $get_exercise_reported_reason) = $row;
	
	/*
	// Get type
	$type_id_mysql = quote_smart($link, $type_id);
	$query = "SELECT type_id, type_title FROM $t_exercise_types WHERE type_id=$type_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_type_id, $get_type_title) = $row;
	if($get_type_id == ""){
		echo"Type not found";
		die;
	}

	// Get main muscle group
	$muscle_group_id_main_mysql = quote_smart($link, $muscle_group_id_main);
	$query = "SELECT muscle_group_id FROM $t_muscle_groups WHERE muscle_group_id=$muscle_group_id_main_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_main_muscle_group_id) = $row;
	if($get_main_muscle_group_id == ""){
		echo"muscle_group_id_main not found";
		die;
	}
	*/
	

	if($get_exercise_id == ""){
		echo"<p>Exercise not found.</p>";
	}
	else{

		if($process == "1"){

			
			// Find sub
			$inp_exercise_muscle_group_id_sub = $_POST['inp_exercise_muscle_group_id_sub'];
			$inp_exercise_muscle_group_id_sub = output_html($inp_exercise_muscle_group_id_sub);
			$inp_exercise_muscle_group_id_sub_mysql = quote_smart($link, $inp_exercise_muscle_group_id_sub);
			if(empty($inp_exercise_muscle_group_id_sub)){
				$url = "new_exercise_step_6_muscle_group.php?type_id=$type_id&l=$l";
				$url = "new_exercise_step_6_muscle_group.php?exercise_id=$get_exercise_id&l=$l";
				header("Location: $url");
				exit;
			}
		
			$query = "SELECT muscle_group_id, muscle_group_parent_id FROM $t_muscle_groups WHERE muscle_group_id=$inp_exercise_muscle_group_id_sub_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_sub_muscle_group_id, $get_sub_muscle_group_parent_id) = $row;
			if($get_sub_muscle_group_id == ""){
				$url = "new_exercise_step_6_muscle_group.php?type_id=$type_id&l=$l";
				$url = "new_exercise_step_6_muscle_group.php?exercise_id=$get_exercise_id&l=$l";
				header("Location: $url");
				exit;
			}
			
			// Find main
			$query = "SELECT muscle_group_id, muscle_group_parent_id FROM $t_muscle_groups WHERE muscle_group_id='$get_sub_muscle_group_parent_id'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_main_muscle_group_id, $get_muscle_group_parent_id) = $row;
			if($get_main_muscle_group_id == ""){

				$url = "new_exercise_step_6_muscle_group.php?exercise_id=$get_exercise_id&l=$l";
				$url = $url . "&ft=error&fm=main_muscle_group_not_found__please_select_a_sub_muscle";
				header("Location: $url");
				exit;
			}
			
				
			// Update
			$result = mysqli_query($link, "UPDATE $t_exercise_index SET exercise_muscle_group_id_main=$get_main_muscle_group_id,
							exercise_muscle_group_id_sub=$get_sub_muscle_group_id WHERE exercise_id=$exercise_id_mysql");




			// Search engine
			$reference_name_mysql = quote_smart($link, "exercise_id");
			$reference_id_mysql = quote_smart($link, "$get_exercise_id");
			$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name='exercises' AND index_reference_name=$reference_name_mysql AND index_reference_id=$reference_id_mysql";
			$result_exists = mysqli_query($link, $query_exists);
			$row_exists = mysqli_fetch_row($result_exists);
			list($get_index_id) = $row_exists;
			if($get_index_id != ""){
				$inp_index_url = "exercises/view_exercise.php?exercise_id=$get_exercise_id&type_id=$get_exercise_type_id&main_muscle_group_id=$get_main_muscle_group_id";
				$inp_index_url_mysql = quote_smart($link, $inp_index_url);

				$result = mysqli_query($link, "UPDATE $t_search_engine_index SET 
								index_url=$inp_index_url_mysql WHERE index_id=$get_index_id") or die(mysqli_error($link));
			}

			// Header
			$url = "new_exercise_step_7_muscles_that_are_beeing_trained.php?exercise_id=$get_exercise_id&main_group_id=$get_main_muscle_group_id&sub_group_id=$inp_exercise_muscle_group_id_sub&l=$l";
			header("Location: $url");
			exit;

		} // process
	
		echo"
		<h1>$l_new_exercise</h1>
	

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

			<form method=\"post\" action=\"new_exercise_step_6_muscle_group.php?exercise_id=$exercise_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
	
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
						echo"<option value=\"$get_sub_muscle_group_id\""; if($get_sub_muscle_group_id == "$get_exercise_muscle_group_id_sub"){ echo" selected=\"selected\""; } echo">&nbsp; &nbsp; $get_sub_muscle_group_translation_name</option>\n";
					}

					echo"				";
					echo"<option value=\"0\"> </option>\n";
				}
			echo"
			</select></p>





			<p>
			<input type=\"submit\" value=\"$l_continue\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
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