<?php 
/**
*
* File: exercise/edit_exercise_video.php
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
	
	// Get video
	$query = "SELECT exercise_video_id, exercise_video_service_name, exercise_video_service_id FROM $t_exercise_index_videos WHERE exercise_video_exercise_id='$get_exercise_id'";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_exercise_video_id, $get_exercise_video_service_name, $get_exercise_video_service_id) = $row;



	if($get_exercise_id == ""){
		echo"<p>Exercise not found.</p>";
	}
	else{
		if($process == 1){
			
		
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

			// Update meta data
			$datetime = date("Y-m-d H:i:s");

			$inp_user_ip = $_SERVER['REMOTE_ADDR'];
			$inp_user_ip = output_html($inp_user_ip);
			$inp_user_ip_mysql = quote_smart($link, $inp_user_ip);

			$result = mysqli_query($link, "UPDATE $t_exercise_index SET 
							exercise_updated_datetime='$datetime', exercise_user_ip=$inp_user_ip_mysql WHERE exercise_id=$exercise_id_mysql");


			$url = "edit_exercise_video.php?exercise_id=$exercise_id&main_muscle_group_id=$main_muscle_group_id&type_id=$type_id&l=$l";
			$url = $url . "&ft=success&fm=changes_saved";
			header("Location: $url");
			exit;

		}
		echo"
		<h1>$l_edit_video</h1>
	



		<!-- Where am I? -->
			<p>
			<b>$l_you_are_here:</b><br />
			<a href=\"$root/exercises/index.php?l=$l\">$l_exercises</a>
			&gt;
			<a href=\"$root/exercises/my_exercises.php?main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\">$l_my_exercises</a>
			&gt;
			<a href=\"$root/exercises/edit_exercise.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\">$get_exercise_title</a>
			&gt;
			<a href=\"$root/exercises/edit_exercise_video.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\">$l_video</a>
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

		<!-- Form -->
			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_exercise_title\"]').focus();
			});
			</script>
	
			<form method=\"post\" action=\"edit_exercise_video.php?exercise_id=$exercise_id&amp;main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>$l_service:</b><br />
			<select name=\"inp_exercise_video_service_name\">
				<option value=\"youtube\""; if($get_exercise_video_service_name == "youtube"){ echo" selected=\"selected\"";} echo">YouTube</option>
			</select></p>

			<p><b>$l_id:</b><br />
			<input type=\"text\" name=\"inp_exercise_video_service_id\" value=\"$get_exercise_video_service_id\" size=\"30\"><br />
			<span class=\"grey_smal\">$l_video_service_example</span>
			</p>


			
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