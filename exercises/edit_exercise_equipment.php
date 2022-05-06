<?php 
/**
*
* File: exercise/edit_exercise_title.php
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

/*- Functions ------------------------------------------------------------------------- */
include("$root/_admin/_functions/encode_national_letters.php");
include("$root/_admin/_functions/decode_national_letters.php");

/*- Translation ------------------------------------------------------------------------ */
include("$root/_admin/_translations/site/$l/exercises/ts_new_exercise.php");
include("$root/_admin/_translations/site/$l/exercises/ts_edit_exercise.php");

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['exercise_id'])){
	$exercise_id = $_GET['exercise_id'];
	$exercise_id = output_html($exercise_id);
}
else{
	$exercise_id = "";
}
if(isset($_GET['type_id'])){
	$type_id = $_GET['type_id'];
	$type_id = strip_tags(stripslashes($type_id));
}
else{
	$type_id = "";
}
if(isset($_GET['main_muscle_group_id'])){
	$main_muscle_group_id = $_GET['main_muscle_group_id'];
	$main_muscle_group_id = strip_tags(stripslashes($main_muscle_group_id));
}
else{
	$main_muscle_group_id = "";
}

$tabindex = 0;
$l_mysql = quote_smart($link, $l);




/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_edit_exercise - $l_exercises";
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
	

	if($get_exercise_id == ""){
		echo"<p>Exercise not found.</p>";
	}
	else{
		if($process == 1){
			
			$inp_exercise_equipment_id = $_GET['inp_exercise_equipment_id'];
			$inp_exercise_equipment_id = output_html($inp_exercise_equipment_id);
			$inp_exercise_equipment_id_mysql = quote_smart($link, $inp_exercise_equipment_id);


			// Check that equipment is valid
			$query = "SELECT equipment_id, equipment_title FROM $t_exercise_equipments WHERE equipment_id=$inp_exercise_equipment_id_mysql AND equipment_language=$l_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_equipment_id, $get_equipment_title) = $row;


			if($get_equipment_id == ""){
				// Not valid 
				// Header
				$ft = "error";
				$fm = "invalid_equipment";
				
				$url = "edit_exercise_equipment.php?exercise_id=$exercise_id&main_muscle_group_id=$main_muscle_group_id&type_id=$type_id&l=$l";
				$url = $url . "&ft=$ft&fm=$fm";
				header("Location: $url");
				exit;

			}


	
			// Update exercise
			$result = mysqli_query($link, "UPDATE $t_exercise_index SET exercise_equipment_id='$get_equipment_id' WHERE exercise_id=$exercise_id_mysql");



			// Update meta data
			
			$datetime = date("Y-m-d H:i:s");

			$inp_user_ip = $_SERVER['REMOTE_ADDR'];
			$inp_user_ip = output_html($inp_user_ip);
			$inp_user_ip_mysql = quote_smart($link, $inp_user_ip);

			$result = mysqli_query($link, "UPDATE $t_exercise_index SET exercise_updated_datetime='$datetime', exercise_user_ip=$inp_user_ip_mysql WHERE exercise_id=$exercise_id_mysql");


			$url = "edit_exercise_equipment.php?exercise_id=$exercise_id&main_muscle_group_id=$main_muscle_group_id&type_id=$type_id&l=$l";
			$url = $url . "&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;

		}
		echo"
		<h1>$l_edit_equipment</h1>
	



		<!-- Where am I? -->
			<p>
			<b>$l_you_are_here:</b><br />
			<a href=\"$root/exercises/index.php?l=$l\">$l_exercises</a>
			&gt;
			<a href=\"$root/exercises/my_exercises.php?main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\">$l_my_exercises</a>
			&gt;
			<a href=\"$root/exercises/edit_exercise.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\">$get_exercise_title</a>
			&gt;
			<a href=\"$root/exercises/edit_exercise_equipment.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\">$l_equipment</a>
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
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->


		<!-- Equipment -->
			<p><b>$l_selected_equipment:</b></p>
					";
					if($get_exercise_equipment_id != ""){

						$query = "SELECT equipment_id, equipment_title, equipment_title_clean, equipment_text, equipment_image_path, equipment_image_file FROM $t_exercise_equipments WHERE equipment_id=$get_exercise_equipment_id";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_equipment_id, $get_equipment_title, $get_equipment_title_clean, $get_equipment_text, $get_equipment_image_path, $get_equipment_image_file) = $row;
			
						echo"
						<p>

						";
						if(file_exists("$root/$get_equipment_image_path/$get_equipment_image_file") && $get_equipment_image_file != ""){
				
							// Thumb
							$inp_new_x = 150;
							$inp_new_y = 150;
							$thumb = "equipment_" . $get_equipment_id . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
			
							if(!(file_exists("$root/_cache/$thumb"))){
								resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_equipment_image_path/$get_equipment_image_file", "$root/_cache/$thumb");
							}


							echo"<a href=\"view_equipment.php?equipment_id=$get_equipment_id&amp;l=$l\"><img src=\"$root/_cache/$thumb\" alt=\"$get_equipment_image_file\" /></a><br />";
						}
						echo"
						<a href=\"view_equipment.php?equipment_id=$get_equipment_id&amp;l=$l\">$get_equipment_title</a>
						</p>
					";
					}
					echo"
		<!-- //Equipment -->


		<!-- Equipments -->

			<div class=\"clear\" style=\"height: 10px;\"></div>
			<p><b>$l_select_a_new_equipment:</b></p>
			";
			// Get all types
			$x = 0;
			$query = "SELECT equipment_id, equipment_title, equipment_title_clean, equipment_text, equipment_image_path, equipment_image_file FROM $t_exercise_equipments WHERE equipment_language=$l_mysql ORDER BY equipment_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_equipment_id, $get_equipment_title, $get_equipment_title_clean, $get_equipment_text, $get_equipment_image_path, $get_equipment_image_file) = $row;
				

				if($x == 0){
					echo"
					<div class=\"clear\" style=\"height: 10px;\"></div>
					<div class=\"left_right_left\">
					";
				}
				elseif($x == 1){
					echo"
					<div class=\"left_right_right\">
					";
				}
				echo"
						<p>
				";

				// Thumb
				if(file_exists("$root/$get_equipment_image_path/$get_equipment_image_file") && $get_equipment_image_file != ""){
					// Thumb
					$inp_new_x = 150;
					$inp_new_y = 150;
					$thumb = "equipment_" . $get_equipment_id . "-" . $inp_new_x . "x" . $inp_new_y . ".png";
			
					if(!(file_exists("$root/_cache/$thumb"))){
						resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_equipment_image_path/$get_equipment_image_file", "$root/_cache/$thumb");
					}
					echo"<a href=\"edit_exercise_equipment.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l&amp;inp_exercise_equipment_id=$get_equipment_id&amp;process=1\"><img src=\"$root/_cache/$thumb\" alt=\"$get_equipment_image_file\"></a><br />";
				}

				echo"
				
						<a href=\"edit_exercise_equipment.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l&amp;inp_exercise_equipment_id=$get_equipment_id&amp;process=1\">$get_equipment_title</a>
						</p>
					</div>
				";
				if($x == 1){
					$x = -1;
				}
				$x++;		
			}
		echo"
		<!-- //Equipments -->
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