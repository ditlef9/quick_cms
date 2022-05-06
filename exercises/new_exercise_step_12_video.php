<?php 
/**
*
* File: exercise/new_exercise_step_12_video.php
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

			
			
			$inp_exercise_video_service_name = $_POST['inp_exercise_video_service_name'];
			$inp_exercise_video_service_name = output_html($inp_exercise_video_service_name);
			$inp_exercise_video_service_name_mysql = quote_smart($link, $inp_exercise_video_service_name);
			if(empty($inp_exercise_video_service_name)){
				$url = "new_exercise_step_10_video.php?exercise_id=$exercise_id&l=$l&ft=error&fm=missing_service_name";
				header("Location: $url");
				exit;
			}

			$inp_exercise_video_service_id = $_POST['inp_exercise_video_service_id'];
			$inp_exercise_video_service_id = output_html($inp_exercise_video_service_id);
			$inp_exercise_video_service_id_mysql = quote_smart($link, $inp_exercise_video_service_id);
			if(empty($inp_exercise_video_service_id)){
				$url = "new_exercise_step_10_video.php?exercise_id=$exercise_id&l=$l&ft=error&fm=missing_service_id";
				header("Location: $url");
				exit;
			}

			$datetime = date("Y-m-d H:i:s");

			$inp_user_ip = $_SERVER['REMOTE_ADDR'];
			$inp_user_ip = output_html($inp_user_ip);
			$inp_user_ip_mysql = quote_smart($link, $inp_user_ip);


			// Does it exists? Then update
			$query = "SELECT exercise_video_id FROM $t_exercise_index_videos WHERE exercise_video_exercise_id='$get_exercise_id'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_exercise_video_id) = $row;
			if($get_exercise_video_id == ""){
				// Insert
				mysqli_query($link, "INSERT INTO $t_exercise_index_videos
				(exercise_video_id, exercise_video_user_id, exercise_video_exercise_id, exercise_video_datetime, exercise_video_user_ip, exercise_video_service_name, exercise_video_service_id, exercise_video_path, exercise_video_file, exercise_video_uniqe_hits, exercise_video_uniqe_hits_ip_block) 
				VALUES 
				(NULL, '$my_user_id_mysql', '$get_exercise_id', '$datetime', $inp_user_ip_mysql, $inp_exercise_video_service_name_mysql, $inp_exercise_video_service_id_mysql, '', '', '0', '')")
				or die(mysqli_error($link));



			}
			else{
				// Update
				$result = mysqli_query($link, "UPDATE $t_exercise_index_videos SET exercise_video_datetime='$datetime', exercise_video_user_ip=$inp_user_ip_mysql, 
				exercise_video_service_name=$inp_exercise_video_service_name_mysql, exercise_video_service_id=$inp_exercise_video_service_id_mysql WHERE exercise_video_exercise_id='$get_exercise_id'");


			}
			
			

			// Header
			$url = "new_exercise_step_13_finish.php?exercise_id=$get_exercise_id&muscle_group_id_main=$get_exercise_muscle_group_id_main&l=$l&process=1";
			header("Location: $url");
			exit;

		} // process
	
		echo"
		<h1>$get_exercise_title</h1>
	

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

			<!-- Focus -->
			<script>
				\$(document).ready(function(){
					\$('[name=\"inp_exercise_video_service_name\"]').focus();
				});
			</script>
			<!-- //Focus -->


			<form method=\"post\" action=\"new_exercise_step_12_video.php?exercise_id=$exercise_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
			<h2>$l_video</h2>

			<p><b>$l_service:</b><br />
			<select name=\"inp_exercise_video_service_name\">
				<option value=\"youtube\">YouTube</option>
			</select><br />
			<span class=\"small\">Search:</span> <a href=\"https://youtube.com/results?search_query=$get_exercise_title\" class=\"small\" target=\"_blank\">YouTube</a>
			</p>


			<p><b>$l_id:</b><br />
			<input type=\"text\" name=\"inp_exercise_video_service_id\" value=\"\" size=\"30\"><br />
			<span class=\"grey_smal\">$l_video_service_example</span>
			</p>


			<p>
			<input type=\"submit\" value=\"$l_continue\" class=\"btn\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			<a href=\"new_exercise_step_13_finish.php?exercise_id=$exercise_id&amp;l=$l&amp;process=1\" class=\"btn btn_default\">$l_skip</a>
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