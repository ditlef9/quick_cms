<?php
/**
*
* File: _admin/_inc/exercise/view_exercise.php
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

if(isset($_GET['translate_to'])){
	$translate_to = $_GET['translate_to'];
	$translate_to = output_html($translate_to);
}
else{
	$translate_to = "";
}

/*- Scriptstart ---------------------------------------------------------------------- */


// Get exercise
$exercise_id_mysql = quote_smart($link, $exercise_id);
$query = "SELECT exercise_id, exercise_title, exercise_user_id, exercise_language, exercise_muscle_group_id_main, exercise_muscle_group_id_sub, exercise_muscle_part_of_id, exercise_equipment_id, exercise_type_id, exercise_level_id, exercise_preparation, exercise_guide, exercise_important, exercise_created_datetime, exercise_updated_datetime, exercise_user_ip, exercise_uniqe_hits, exercise_uniqe_hits_ip_block, exercise_likes, exercise_dislikes, exercise_rating, exercise_rating_ip_block, exercise_number_of_comments, exercise_reported, exercise_reported_checked, exercise_reported_reason FROM $t_exercise_index WHERE exercise_id=$exercise_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_exercise_id, $get_current_exercise_title, $get_current_exercise_user_id, $get_current_exercise_language, $get_current_exercise_muscle_group_id_main, $get_current_exercise_muscle_group_id_sub, $get_current_exercise_muscle_part_of_id, $get_current_exercise_equipment_id, $get_current_exercise_type_id, $get_current_exercise_level_id, $get_current_exercise_preparation, $get_current_exercise_guide, $get_current_exercise_important, $get_current_exercise_created_datetime, $get_current_exercise_updated_datetime, $get_current_exercise_user_ip, $get_current_exercise_uniqe_hits, $get_current_exercise_uniqe_hits_ip_block, $get_current_exercise_likes, $get_current_exercise_dislikes, $get_current_exercise_rating, $get_current_exercise_rating_ip_block, $get_current_exercise_number_of_comments, $get_current_exercise_reported, $get_current_exercise_reported_checked, $get_current_exercise_reported_reason) = $row;
	


if($get_current_exercise_id == ""){
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "Server error 404 - $l_exercises";
	if(file_exists("./favicon.ico")){ $root = "."; }
	elseif(file_exists("../favicon.ico")){ $root = ".."; }
	elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
	elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
	include("$root/_webdesign/header.php");

	echo"
	<p>Exercise not found.</p>
	";
	include("$root/_webdesign/footer.php");

}
else {
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$get_current_exercise_title";
	include("$root/_webdesign/header.php");


	if(isset($_SESSION['user_id'])){

		// Find to language
		$translate_to_mysql = quote_smart($link, $translate_to);

		$query_t = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active WHERE language_active_iso_two=$translate_to_mysql";
		$result_t = mysqli_query($link, $query_t);
		$row_t = mysqli_fetch_row($result_t);
		list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row_t;
		if($get_language_active_id == ""){
			echo"<p>Translate to not found</p>";
		}
		else{
			// Check if it already is translated
			$query_t = "SELECT relation_id, exercise_translated FROM $t_exercise_index_translations_relations WHERE exercise_original_id=$get_current_exercise_id AND exercise_language='$get_language_active_iso_two'";
			$result_t = mysqli_query($link, $query_t);
			$row_t = mysqli_fetch_row($result_t);
			list($get_relation_id, $get_exercise_translated) = $row_t;
			if($get_relation_id != ""){
				echo"<p>Alread translated.</p>";
			}
			else{

				// Get type name
				$query = "SELECT type_id, type_title FROM $t_exercise_types WHERE type_id='$get_current_exercise_type_id'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_type_id, $get_type_title) = $row;
				if($get_type_id == ""){
					echo"Type not found";
					die;
				}

				// Get main muscle group
				$query = "SELECT muscle_group_id, muscle_group_name_clean FROM $t_muscle_groups WHERE muscle_group_id='$get_current_exercise_muscle_group_id_main'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_main_muscle_group_id, $get_main_muscle_group_name_clean) = $row;
				if($get_main_muscle_group_id == ""){
					echo"muscle_group_id_main not found";
					die;
				}



				echo"<p>Ok, wait a second</p>";

				// Title
				$inp_exercise_title = $get_current_exercise_title . " ($get_language_active_name)";
				$inp_exercise_title_mysql = quote_smart($link, $inp_exercise_title);

				$inp_exercise_title_clean = clean($inp_exercise_title);
				$inp_exercise_title_clean_mysql = quote_smart($link, $inp_exercise_title_clean);

				// Get my user
				$my_user_id = $_SESSION['user_id'];
				$my_user_id = output_html($my_user_id);
				$my_user_id_mysql = quote_smart($link, $my_user_id);
				$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_rank) = $row;


				// exercise_muscle_group_id_main
				$inp_exercise_muscle_group_id_main_mysql = quote_smart($link, $get_current_exercise_muscle_group_id_main);
				
				// exercise_muscle_group_id_sub
				$inp_exercise_muscle_group_id_sub_mysql = quote_smart($link, $get_current_exercise_muscle_group_id_sub);

				// exercise_muscle_part_of_id
				$inp_exercise_muscle_part_of_id_mysql = quote_smart($link, $get_current_exercise_muscle_part_of_id);

				// $inp_exercise_type_id_mysql
				$inp_exercise_type_id_mysql = quote_smart($link, $get_current_exercise_type_id);

				// $inp_exercise_type_id_mysql
				$inp_exercise_equipment_id_mysql = quote_smart($link, $get_current_exercise_equipment_id);

				// $inp_exercise_level_id_mysql
				$inp_exercise_level_id_mysql = quote_smart($link, $get_current_exercise_level_id);

				$datetime = date("Y-m-d H:i:s");

				$inp_user_ip = $_SERVER['REMOTE_ADDR'];
				$inp_user_ip = output_html($inp_user_ip);
				$inp_user_ip_mysql = quote_smart($link, $inp_user_ip);


				// Create
				mysqli_query($link, "INSERT INTO $t_exercise_index
				(exercise_id, exercise_title, exercise_title_clean, exercise_user_id, exercise_language, exercise_muscle_group_id_main, 
				exercise_muscle_group_id_sub, exercise_muscle_part_of_id, exercise_equipment_id, exercise_type_id, exercise_level_id, exercise_preparation, 
				exercise_guide, exercise_important, exercise_created_datetime, exercise_updated_datetime, exercise_user_ip, exercise_uniqe_hits, 
				exercise_uniqe_hits_ip_block, exercise_likes, exercise_dislikes, exercise_rating, exercise_rating_ip_block, exercise_number_of_comments, 
				exercise_reported, exercise_reported_checked, exercise_reported_reason, exercise_last_viewed)
				VALUES 
				(NULL, $inp_exercise_title_mysql, $inp_exercise_title_clean_mysql, '$get_user_id', '$get_language_active_iso_two', $inp_exercise_muscle_group_id_main_mysql, 
				$inp_exercise_muscle_group_id_sub_mysql, $inp_exercise_muscle_part_of_id_mysql, $inp_exercise_equipment_id_mysql, $inp_exercise_type_id_mysql, $inp_exercise_level_id_mysql, '',
				'', '', '$datetime', '$datetime', $inp_user_ip_mysql, '0', 
				'0', '0', '0', '0', '0', '0',
				'0', '0', '', '$datetime')")
				or die(mysqli_error($link));
			
				// Get ID
				$query = "SELECT exercise_id FROM $t_exercise_index WHERE exercise_title=$inp_exercise_title_mysql AND exercise_user_id='$get_user_id' AND exercise_created_datetime='$datetime'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_new_exercise_id) = $row;


				// Images
				$query = "SELECT exercise_image_id, exercise_image_type, exercise_image_path, exercise_image_file, exercise_image_thumb_large FROM $t_exercise_index_images WHERE exercise_image_exercise_id='$get_current_exercise_id' ORDER BY exercise_image_type ASC";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_exercise_image_id, $get_exercise_image_type, $get_exercise_image_path, $get_exercise_image_file, $get_exercise_image_thumb_large) = $row;

					// Type
					$inp_exercise_image_type_mysql = quote_smart($link, $get_exercise_image_type);

					$inp_type_title_clean = clean($get_type_title);

					if(!(is_dir("$root/_uploads"))){
						mkdir("$root/_uploads");
					}
					if(!(is_dir("$root/_uploads/exercises"))){
						mkdir("$root/_uploads/exercises");
					}
					if(!(is_dir("$root/_uploads/exercises/$get_language_active_iso_two"))){
						mkdir("$root/_uploads/exercises/$get_language_active_iso_two");
					}
					if(!(is_dir("$root/_uploads/exercises/$get_language_active_iso_two/$inp_type_title_clean"))){
						mkdir("$root/_uploads/exercises/$get_language_active_iso_two/$inp_type_title_clean");
					}
					if(!(is_dir("$root/_uploads/exercises/$get_language_active_iso_two/$inp_type_title_clean/$get_main_muscle_group_name_clean"))){
						mkdir("$root/_uploads/exercises/$get_language_active_iso_two/$inp_type_title_clean/$get_main_muscle_group_name_clean");
					}
					if(!(is_dir("$root/_uploads/exercises/$get_language_active_iso_two/$inp_type_title_clean/$get_main_muscle_group_name_clean/$inp_exercise_title_clean"))){
						mkdir("$root/_uploads/exercises/$get_language_active_iso_two/$inp_type_title_clean/$get_main_muscle_group_name_clean/$inp_exercise_title_clean");
					}


					// Path
					$inp_exercise_image_path = "_uploads/exercises/$get_language_active_iso_two/$inp_type_title_clean/$get_main_muscle_group_name_clean/$inp_exercise_title_clean";
					$inp_exercise_image_path = output_html($inp_exercise_image_path);
					$inp_exercise_image_path_mysql = quote_smart($link, $inp_exercise_image_path);

					// File type
					$file_type = get_extension($get_exercise_image_file);

					$inp_exercise_image_file = $inp_exercise_title_clean . "_" . $get_new_exercise_id . "_" . $get_exercise_image_type . ".$file_type";
					$inp_exercise_image_file_mysql = quote_smart($link, $inp_exercise_image_file);

					$inp_exercise_image_file_thumb_small = $inp_exercise_title_clean . "_" . $get_new_exercise_id . "_" . $get_exercise_image_type . "_thumb_small.$file_type";
					$inp_exercise_image_file_thumb_small_mysql = quote_smart($link, $inp_exercise_image_file_thumb_small);

					$inp_exercise_image_file_thumb_medium = $inp_exercise_title_clean . "_" . $get_new_exercise_id . "_" . $get_exercise_image_type . "_thumb_medium.$file_type";
					$inp_exercise_image_file_thumb_medium_mysql = quote_smart($link, $inp_exercise_image_file_thumb_medium);

					$inp_exercise_image_file_thumb_large = $inp_exercise_title_clean . "_" . $get_new_exercise_id . "_" . $get_exercise_image_type . "_thumb_large.$file_type";
					$inp_exercise_image_file_thumb_large_mysql = quote_smart($link, $inp_exercise_image_file_thumb_large);

					mysqli_query($link, "INSERT INTO $t_exercise_index_images 
					(exercise_image_id, exercise_image_user_id, exercise_image_exercise_id, exercise_image_datetime, exercise_image_user_ip, exercise_image_type, 
					exercise_image_path, exercise_image_file, exercise_image_thumb_small, exercise_image_thumb_medium, exercise_image_thumb_large, exercise_image_uniqe_hits, 
					exercise_image_uniqe_hits_ip_block)
					VALUES 
					(NULL, $get_user_id, $get_new_exercise_id, '$datetime', $inp_user_ip_mysql, $inp_exercise_image_type_mysql,
					$inp_exercise_image_path_mysql, $inp_exercise_image_file_mysql, $inp_exercise_image_file_thumb_small_mysql, $inp_exercise_image_file_thumb_medium_mysql,
					$inp_exercise_image_file_thumb_large_mysql, 0, '')")
					or die(mysqli_error($link));

					// Copy the images
					copy("$root/$get_exercise_image_path/$get_exercise_image_file", "$root/$inp_exercise_image_path/$inp_exercise_image_file");
				}


				// Muscles
				$query = "SELECT exercise_muscle_id, exercise_muscle_exercise_id, exercise_muscle_muscle_id, exercise_muscle_type FROM $t_exercise_index_muscles WHERE exercise_muscle_exercise_id='$get_current_exercise_id'";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_exercise_muscle_id, $get_exercise_muscle_exercise_id, $get_exercise_muscle_muscle_id, $get_exercise_muscle_type) = $row;


					mysqli_query($link, "INSERT INTO $t_exercise_index_muscles 
					(exercise_muscle_id, exercise_muscle_exercise_id, exercise_muscle_muscle_id, exercise_muscle_type) 
					VALUES 
					(NULL, $get_new_exercise_id, $get_exercise_muscle_muscle_id, '$get_exercise_muscle_type')")
					or die(mysqli_error($link));
				}

				// Video
				$query = "SELECT exercise_video_id, exercise_video_service_name, exercise_video_service_id FROM $t_exercise_index_videos WHERE exercise_video_exercise_id=$get_current_exercise_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_exercise_video_id, $get_exercise_video_service_name, $get_exercise_video_service_id) = $row;
				if($get_exercise_video_id != ""){

					// Insert
					$inp_exercise_video_service_name_mysql = quote_smart($link, $get_exercise_video_service_name); 
					$inp_exercise_video_service_id_mysql = quote_smart($link, $get_exercise_video_service_id); 

					mysqli_query($link, "INSERT INTO $t_exercise_index_videos
					(exercise_video_id, exercise_video_user_id, exercise_video_exercise_id, exercise_video_datetime, exercise_video_user_ip, exercise_video_service_name, exercise_video_service_id, exercise_video_path, exercise_video_file, exercise_video_uniqe_hits, exercise_video_uniqe_hits_ip_block) 
					VALUES 
					(NULL, $get_user_id, $get_new_exercise_id, '$datetime', $inp_user_ip_mysql, $inp_exercise_video_service_name_mysql, $inp_exercise_video_service_id_mysql, '', '', '0', '')")
					or die(mysqli_error($link));

				}


				// Insert into translated
				mysqli_query($link, "INSERT INTO $t_exercise_index_translations_relations
				(relation_id, exercise_original_id, exercise_target_id, exercise_language, exercise_translated) 
				VALUES 
				(NULL, '$get_current_exercise_id', $get_new_exercise_id, '$get_language_active_iso_two', '1')")
				or die(mysqli_error($link));


				// Refresh
				echo"
				<p><a href=\"edit_exercise.php?exercise_id=$get_new_exercise_id&amp;l=$get_language_active_iso_two\">Edit</a></p>
  				<meta http-equiv=\"refresh\" content=\"0;URL='edit_exercise.php?exercise_id=$get_new_exercise_id&amp;l=$get_language_active_iso_two'\" />   
				";

			} // not translated
		} // translate to found
	} // logged in
	else{
		echo"Not logged in";
	}
	/*- Footer ---------------------------------------------------------------- */
	include("$root/_webdesign/footer.php");
} // muscle not found

?>