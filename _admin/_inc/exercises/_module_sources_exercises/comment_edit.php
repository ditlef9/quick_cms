<?php 
/**
*
* File: exercises/comment_edit.php
* Version 1.0.0
* Date 11:51 01.11.2020
* Copyright (c) 2020 S. A. Ditlefsen
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


/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);

/*- Tables ---------------------------------------------------------------------------- */
$t_exercise_index 				= $mysqlPrefixSav . "exercise_index";
$t_exercise_index_images			= $mysqlPrefixSav . "exercise_index_images";
$t_exercise_index_videos			= $mysqlPrefixSav . "exercise_index_videos";
$t_exercise_index_muscles			= $mysqlPrefixSav . "exercise_index_muscles";
$t_exercise_index_muscles_images		= $mysqlPrefixSav . "exercise_index_muscles_images";
$t_exercise_index_tags				= $mysqlPrefixSav . "exercise_index_tags";
$t_exercise_tags_cloud				= $mysqlPrefixSav . "exercise_tags_cloud";
$t_exercise_index_comments			= $mysqlPrefixSav . "exercise_index_comments";
$t_exercise_index_translations_relations	= $mysqlPrefixSav . "exercise_index_translations_relations";
$t_exercise_equipments 				= $mysqlPrefixSav . "exercise_equipments";
$t_exercise_types				= $mysqlPrefixSav . "exercise_types";
$t_exercise_types_translations 			= $mysqlPrefixSav . "exercise_types_translations";
$t_exercise_levels				= $mysqlPrefixSav . "exercise_levels";
$t_exercise_levels_translations 		= $mysqlPrefixSav . "exercise_levels_translations";


$t_stats_comments_per_year	= $mysqlPrefixSav . "stats_comments_per_year";
$t_stats_comments_per_month	= $mysqlPrefixSav . "stats_comments_per_month";


/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['comment_id'])){
	$comment_id = $_GET['comment_id'];
	$comment_id = output_html($comment_id);
}
else{
	$comment_id = "";
}

// Get comment
$comment_id_mysql = quote_smart($link, $comment_id);
$query = "SELECT comment_id, comment_exercise_id, comment_text, comment_by_user_id, comment_by_user_name, comment_by_user_image_path, comment_by_user_image_file, comment_by_user_image_thumb_60, comment_by_user_ip, comment_created, comment_created_saying, comment_created_timestamp, comment_updated, comment_updated_saying, comment_likes, comment_dislikes, comment_read_blog_owner FROM $t_exercise_index_comments WHERE comment_id=$comment_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_comment_id, $get_current_comment_exercise_id, $get_current_comment_text, $get_current_comment_by_user_id, $get_current_comment_by_user_name, $get_current_comment_by_user_image_path, $get_current_comment_by_user_image_file, $get_current_comment_by_user_image_thumb_60, $get_current_comment_by_user_ip, $get_current_comment_created, $get_current_comment_created_saying, $get_current_comment_created_timestamp, $get_current_comment_updated, $get_current_comment_updated_saying, $get_current_comment_likes, $get_current_comment_dislikes, $get_current_comment_read_blog_owner) = $row;

if($get_current_comment_id == ""){
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "404 - $l_exercises";
	include("$root/_webdesign/header.php");
	echo"<p>Comment not found.</p>";
}
else{
	// Get exercise
	$query = "SELECT exercise_id, exercise_title, exercise_title_alternative, exercise_user_id, exercise_language, exercise_muscle_group_id_main, exercise_muscle_group_id_sub, exercise_muscle_part_of_id, exercise_equipment_id, exercise_type_id, exercise_level_id, exercise_preparation, exercise_guide, exercise_important, exercise_created_datetime, exercise_updated_datetime, exercise_user_ip, exercise_uniqe_hits, exercise_uniqe_hits_ip_block, exercise_likes, exercise_dislikes, exercise_rating, exercise_rating_ip_block, exercise_number_of_comments, exercise_reported, exercise_reported_checked, exercise_reported_reason FROM $t_exercise_index WHERE exercise_id=$get_current_comment_exercise_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_exercise_id, $get_current_exercise_title, $get_current_exercise_title_alternative, $get_current_exercise_user_id, $get_current_exercise_language, $get_current_exercise_muscle_group_id_main, $get_current_exercise_muscle_group_id_sub, $get_current_exercise_muscle_part_of_id, $get_current_exercise_equipment_id, $get_current_exercise_type_id, $get_current_exercise_level_id, $get_current_exercise_preparation, $get_current_exercise_guide, $get_current_exercise_important, $get_current_exercise_created_datetime, $get_current_exercise_updated_datetime, $get_current_exercise_user_ip, $get_current_exercise_uniqe_hits, $get_current_exercise_uniqe_hits_ip_block, $get_current_exercise_likes, $get_current_exercise_dislikes, $get_current_exercise_rating, $get_current_exercise_rating_ip_block, $get_current_exercise_number_of_comments, $get_current_exercise_reported, $get_current_exercise_reported_checked, $get_current_exercise_reported_reason) = $row;


	// Main muscle group
	$query = "SELECT muscle_group_id FROM $t_muscle_groups WHERE muscle_group_id='$get_current_exercise_muscle_group_id_main'";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_main_muscle_group_id) = $row;

	if($get_current_main_muscle_group_id != ""){
		$query_translation = "SELECT muscle_group_translation_id,muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_current_main_muscle_group_id AND muscle_group_translation_language=$l_mysql";
		$result_translation = mysqli_query($link, $query_translation);
		$row_translation = mysqli_fetch_row($result_translation);
		list($get_current_main_muscle_group_translation_id, $get_current_main_muscle_group_translation_name) = $row_translation;
	}
	
	// Get type
	$query = "SELECT type_id, type_title FROM $t_exercise_types WHERE type_id='$get_current_exercise_type_id'";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_type_id, $get_current_type_title) = $row;

	if($get_current_type_id != ""){
		$query_translation = "SELECT type_translation_id, type_translation_value FROM $t_exercise_types_translations WHERE type_id='$get_current_exercise_type_id' AND type_translation_language=$l_mysql";
		$result_translation = mysqli_query($link, $query_translation);
		$row_translation = mysqli_fetch_row($result_translation);
		list($get_current_type_translation_id, $get_current_type_translation_value) = $row_translation;
	}

	// Author
	$query = "SELECT user_id, user_email, user_name, user_alias FROM $t_users WHERE user_id='$get_current_exercise_user_id'";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_author_user_id, $get_author_user_email, $get_author_user_name, $get_author_user_alias) = $row;

	// Level
	$query = "SELECT level_translation_id, level_translation_value FROM $t_exercise_levels_translations WHERE level_id=$get_current_exercise_level_id AND level_translation_language=$l_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_level_translation_id, $get_current_level_translation_value) = $row;

	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$l_edit_comment - $get_current_exercise_title - $l_exercises";
	include("$root/_webdesign/header.php");
	
	// Logged in?
	if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){
		// Get my user
		$my_user_id = $_SESSION['user_id'];
		$my_user_id = output_html($my_user_id);
		$my_user_id_mysql = quote_smart($link, $my_user_id);
		$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;

		$query = "SELECT photo_id, photo_destination, photo_thumb_60 FROM $t_users_profile_photo WHERE photo_user_id=$my_user_id_mysql AND photo_profile_image='1'";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_60) = $row;

		// Can edit?
		$can_edit = 0;
		if($get_my_user_id == "$get_current_comment_by_user_id"){
			$can_edit = 1;
		}
		if($get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
			$can_edit = 1;
		}
		if($can_edit == "0"){
			echo"<p>Access denied.</p>";
		}
		else{
			if($process == "1"){
				$inp_text = $_POST['inp_text'];
				$inp_text = output_html($inp_text);
				$inp_text_mysql = quote_smart($link, $inp_text);

				$datetime = date("Y-m-d H:i:s");
				$date_saying = date("j M Y");

				// Update
				mysqli_query($link, "UPDATE $t_exercise_index_comments SET 
							comment_text=$inp_text_mysql,
							comment_updated='$datetime',
							comment_updated_saying='$date_saying'
							WHERE comment_id=$get_current_comment_id")
							or die(mysqli_error($link));

				// header
				$url = "view_exercise.php?exercise_id=$get_current_exercise_id&main_muscle_group_id=$get_current_exercise_muscle_group_id_main&type_id=$get_current_type_id&l=$l&ft_comment=success&fm_comment=changes_saved#comment$get_current_comment_id";
				header("Location: $url");
				exit;
			}


			echo"
			<h1>$l_edit_comment</h1>

			<!-- Where am I? -->
				<p>
				<b>$l_you_are_here:</b><br />
				<a href=\"$root/exercises/index.php?l=$l\">$l_exercises</a>
				&gt;
				<a href=\"$root/exercises/view_type.php?type_id=$get_current_type_id&amp;l=$l\">$get_current_type_translation_value</a>";
				if(isset($get_current_main_muscle_group_translation_name)){
					echo"
					&gt;
					<a href=\"$root/exercises/view_muscle_group.php?main_muscle_group_id=$get_current_exercise_muscle_group_id_main&amp;type_id=$get_current_type_id&amp;l=$l\">$get_current_main_muscle_group_translation_name</a>
					";
				}
				echo"
				&gt;
				<a href=\"$root/exercises/view_exercise.php?exercise_id=$get_current_exercise_id&amp;main_muscle_group_id=$get_current_exercise_muscle_group_id_main&amp;type_id=$get_current_type_id&amp;l=$l\">$get_current_exercise_title</a>
				&gt;
				<a href=\"comment_edit.php?comment_id=$get_current_comment_id&amp;l=$l\">$l_edit_comment $get_current_comment_id</a>
				</p>
			<!-- //Where am I? -->
	
			<!-- Edit comment form -->

				<form method=\"post\" action=\"comment_edit.php?comment_id=$get_current_comment_id&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">
		
				<table>
	 			 <tr>
				  <td style=\"vertical-align: top;padding-right: 10px;text-align:center;\">
					<p>
					";
					if(file_exists("$root/$get_current_comment_by_user_image_path/$get_current_comment_by_user_image_thumb_60") && $get_current_comment_by_user_image_thumb_60 != ""){

				
						echo"
						<img src=\"$root/$get_current_comment_by_user_image_path/$get_current_comment_by_user_image_thumb_60\" alt=\"$get_current_comment_by_user_image_thumb_60\" />
						<br />
						";
					}
					echo"
					$get_current_comment_by_user_name
					</p>
				  </td>
				  <td style=\"vertical-align: top;\">
					<p>
					<textarea name=\"inp_text\" rows=\"5\" cols=\"80\">";
					$get_current_comment_text = str_replace("<br />", "\n", $get_current_comment_text);
					echo"$get_current_comment_text</textarea><br />
					<input type=\"submit\" value=\"$l_save_comment\" class=\"btn_default\" />
					</p>
				  </td>
				 </tr>
				</table>

				</form>
			<!-- //Edit comment form -->
			";
		} // can edit
	}
	else{
		echo"<p>Not logged in.</p>";
	}
} // comment found


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>