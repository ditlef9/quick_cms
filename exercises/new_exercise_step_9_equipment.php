<?php 
/**
*
* File: exercise/new_exercise_step_9_equipment.php
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
	

	

	if($get_exercise_id == ""){
		echo"<p>Exercise not found.</p>";
	}
	else{

		if($action == "use_existing"){



			$inp_exercise_equipment_id = $_POST['inp_exercise_equipment_id'];
			$inp_exercise_equipment_id = output_html($inp_exercise_equipment_id);
			$inp_exercise_equipment_id_mysql = quote_smart($link, $inp_exercise_equipment_id);


			// Check that equipment is valid
			$query = "SELECT equipment_id, equipment_title FROM $t_exercise_equipments WHERE equipment_id=$inp_exercise_equipment_id_mysql AND equipment_language=$l_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_equipment_id, $get_equipment_title) = $row;


			if($get_equipment_id != ""){
				// Update exercise
				$result = mysqli_query($link, "UPDATE $t_exercise_index SET exercise_equipment_id='$get_equipment_id' WHERE exercise_id=$exercise_id_mysql");
			}

			// Header
			$ft = "success";
			$fm = "equipment_saved";
				
			$url = "new_exercise_step_10_tags.php?exercise_id=$exercise_id&l=$l";
			$url = $url . "&ft=$ft&fm=$fm";
			header("Location: $url");
			exit;

			

		} // use_existing
		if($action == "make_new"){


			$inp_equipment_title = $_POST['inp_equipment_title'];
			$inp_equipment_title = output_html($inp_equipment_title);
			$inp_equipment_title_mysql = quote_smart($link, $inp_equipment_title);

			$inp_equipment_title_clean = clean($inp_equipment_title);
			$inp_equipment_title_clean_mysql = quote_smart($link, $inp_equipment_title_clean);

			// Check if it alreaddy exsits
			$query = "SELECT equipment_id, equipment_title FROM $t_exercise_equipments WHERE equipment_title=$inp_equipment_title_mysql AND equipment_language=$l_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_equipment_id, $get_equipment_title) = $row;

			if($get_equipment_id == ""){
				// It does not exists

				$datetime = date("Y-m-d H:i:s");

				$inp_user_ip = $_SERVER['REMOTE_ADDR'];
				$inp_user_ip = output_html($inp_user_ip);
				$inp_user_ip_mysql = quote_smart($link, $inp_user_ip);

				mysqli_query($link, "INSERT INTO $t_exercise_equipments
				(equipment_id, equipment_title, equipment_title_clean, equipment_user_id, equipment_language, equipment_muscle_group_id_main, equipment_muscle_group_id_sub, equipment_created_datetime, equipment_updated_datetime, equipment_user_ip, equipment_uniqe_hits, equipment_uniqe_hits_ip_block, equipment_likes, equipment_dislikes, equipment_rating, equipment_number_of_comments) 
				VALUES 
				(NULL, $inp_equipment_title_mysql, $inp_equipment_title_clean_mysql, $my_user_id_mysql, $l_mysql, '$get_exercise_muscle_group_id_main', '$get_exercise_muscle_group_id_sub', '$datetime', 
				'$datetime', $inp_user_ip_mysql, '0', '', '0', '0', '0', '0')
				")
				or die(mysqli_error($link));

				// Get ID
				$query = "SELECT equipment_id, equipment_title FROM $t_exercise_equipments WHERE equipment_title=$inp_equipment_title_mysql AND equipment_language=$l_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_equipment_id, $get_equipment_title) = $row;



				// Update exercise
				$result = mysqli_query($link, "UPDATE $t_exercise_index SET exercise_equipment_id='$get_equipment_id' WHERE exercise_id=$exercise_id_mysql");

				// Header
				$ft = "success";
				$fm = "new_equipment_created";
				
				$url = "new_exercise_step_10_tags.php?exercise_id=$exercise_id&l=$l";
				$url = $url . "&ft=$ft&fm=$fm";
				header("Location: $url");
				exit;
				
			}
			else{
				// It alreaddy exists,
				// use exising

				// Update exercise
				$result = mysqli_query($link, "UPDATE $t_exercise_index SET exercise_equipment_id='$get_equipment_id' WHERE exercise_id=$exercise_id_mysql");

				// Header
				$ft = "success";
				$fm = "the_equipment_already_existed_so_we_used_the_existing_one";
				
				$url = "new_exercise_step_10_tags.php?exercise_id=$exercise_id&l=$l";
				$url = $url . "&ft=$ft&fm=$fm";
				header("Location: $url");
				exit;
			}

		} // make_new
	
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



		<p>
		$l_select_a_equipment_or_add_a_new_one
		</p>


		<form method=\"post\" action=\"new_exercise_step_9_equipment.php?exercise_id=$exercise_id&amp;l=$l&amp;action=use_existing&amp;process=1\" enctype=\"multipart/form-data\">
			<p>$l_select_equipment:
			<select name=\"inp_exercise_equipment_id\">
			<option value=\"0\">$l_none</option>
			";
			// Get all types
			$query = "SELECT equipment_id, equipment_title FROM $t_exercise_equipments WHERE equipment_language=$l_mysql ORDER BY equipment_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_equipment_id, $get_equipment_title) = $row;
				echo"
				<option value=\"$get_equipment_id\">$get_equipment_title</option>\n";
				
			}
			echo"
			</select>
		
			<input type=\"submit\" value=\"$l_continue\" class=\"btn\" />
			</p>
		</form>


		<p>... $l_or_add_new_lowercase</p>


		<form method=\"post\" action=\"new_exercise_step_9_equipment.php?exercise_id=$exercise_id&amp;l=$l&amp;action=make_new&amp;process=1\" enctype=\"multipart/form-data\">
			<p>$l_new_name:
			<input type=\"text\" name=\"inp_equipment_title\" size=\"10\" />
			<input type=\"submit\" value=\"$l_create\" class=\"btn\" />
			</p>
		</form>



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