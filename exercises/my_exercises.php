<?php 
/**
*
* File: food/my_exercises.php
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


/*- Variables ------------------------------------------------------------------------- */
$tabindex = 0;
$l_mysql = quote_smart($link, $l);

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

/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_my_exercises - $l_exercises";
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


	
	echo"
	<h1>$l_my_exercises</h1>
	
	<!-- Buttons -->
		<p>
		<a href=\"$root/exercises/new_exercise.php?l=$l\" class=\"btn_default\">$l_new_exercise</a>
		<a href=\"$root/exercises/my_equipment.php?l=$l\" class=\"btn_default\">$l_my_equipment</a>
		</p>
	<div class=\"clear\"></div>
	<!-- //Buttons -->

	<!-- Selector -->

	<div class=\"right\" style=\"text-align: right;\">

		<script>
			\$(function(){
				\$('#inp_language_select').on('change', function () {
					var url = \$(this).val();
					if (url) { // require a URL
 						window.location = url;
					}
					return false;
				});
				\$('#inp_type_select').on('change', function () {
					var url = \$(this).val();
					if (url) { // require a URL
 						window.location = url;
					}
					return false;
				});
				\$('#inp_muscle_group_select').on('change', function () {
					var url = \$(this).val();
					if (url) { // require a URL
 						window.location = url;
					}
					return false;
				});
			});
		</script>

		<form method=\"get\" action=\"cc\" enctype=\"multipart/form-data\">
			<p>

			<select name=\"inp_language_select\" id=\"inp_language_select\">
				<option value=\"my_exercises.php?main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\">- $l_language -</option>\n";

				$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;



					echo"		";
					echo"<option value=\"my_exercises.php?main_muscle_group_id=$main_muscle_group_id&amp;type_id=$type_id&amp;l=$get_language_active_iso_two\""; if($l == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";

				}
			echo"
			</select>


			<select name=\"inp_type_select\" id=\"inp_type_select\">
				<option value=\"my_exercises.php?main_muscle_group_id=$main_muscle_group_id&amp;l=$l\">- $l_type -</option>\n";
				// Get all types
				$query_sub = "SELECT type_id, type_title FROM $t_exercise_types ORDER BY type_title ASC";
				$result_sub = mysqli_query($link, $query_sub);
				while($row_sub = mysqli_fetch_row($result_sub)) {
					list($get_type_id, $get_type_title) = $row_sub;

					// Translation
					$query_translation = "SELECT type_translation_id, type_translation_value FROM $t_exercise_types_translations WHERE type_id='$get_type_id' AND type_translation_language=$l_mysql";
					$result_translation = mysqli_query($link, $query_translation);
					$row_translation = mysqli_fetch_row($result_translation);
					list($get_type_translation_id, $get_type_translation_value) = $row_translation;

					echo"		";
					echo"<option value=\"my_exercises.php?main_muscle_group_id=$main_muscle_group_id&amp;type_id=$get_type_id&amp;l=$l\""; if($type_id == "$get_type_id"){ echo" selected=\"selected\"";}echo">$get_type_translation_value</option>\n";

				}
			echo"
			</select>


			<select name=\"inp_muscle_group_select\" id=\"inp_muscle_group_select\">
				<option value=\"my_exercises.php?type_id=$get_type_id&amp;l=$l\">- $l_muscles -</option>\n";

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



					echo"		";
					echo"<option value=\"my_exercises.php?main_muscle_group_id=$get_main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\""; if($main_muscle_group_id == "$get_main_muscle_group_id"){ echo" selected=\"selected\"";}echo">$get_main_muscle_group_translation_name</option>\n";

				}
			echo"
			</select>
			</p>
        	</form>
	</div>
	<div class=\"clear\"></div>
	<!-- //Selector -->

	<!-- List my exercises -->
	";
	
	$query = "SELECT exercise_id, exercise_title, exercise_language, exercise_muscle_group_id_main, exercise_equipment_id, exercise_type_id, exercise_level_id, exercise_updated_datetime FROM $t_exercise_index WHERE exercise_user_id=$my_user_id_mysql AND exercise_language=$l_mysql";

	if($type_id != ""){
		$type_id_mysql = quote_smart($link, $type_id);
		$query = $query . " AND exercise_type_id=$type_id_mysql";
	}
	if($main_muscle_group_id != ""){
		$main_muscle_group_id_mysql = quote_smart($link, $main_muscle_group_id);
		$query = $query . " AND exercise_muscle_group_id_main=$main_muscle_group_id_mysql";
	}

	$query = $query . " ORDER BY exercise_title ASC";

	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_exercise_id, $get_exercise_title, $get_exercise_language, $get_exercise_muscle_group_id_main, $get_exercise_equipment_id, $get_exercise_type_id, $get_exercise_level_id, $get_exercise_updated_datetime) = $row;

			if(isset($style) && $style == "odd"){
				$style = "";
			}
			else{
				$style = "odd";
			}

			
			// Updated date
			$updated_year = substr($get_exercise_updated_datetime, 0, 4);
			$updated_month = substr($get_exercise_updated_datetime, 5, 2);
			$updated_day = substr($get_exercise_updated_datetime, 8, 2);

			if($updated_day < 10){
				$updated_day = substr($updated_day, 1, 1);
			}
		
			if($updated_month == "01"){
				$updated_month_saying = $l_january;
			}
			elseif($updated_month == "02"){
				$updated_month_saying = $l_february;
			}
			elseif($updated_month == "03"){
				$updated_month_saying = $l_march;
			}
			elseif($updated_month == "04"){
				$updated_month_saying = $l_april;
			}
			elseif($updated_month == "05"){
				$updated_month_saying = $l_may;
			}
			elseif($updated_month == "06"){
				$updated_month_saying = $l_june;
			}
			elseif($updated_month == "07"){
				$updated_month_saying = $l_july;
			}
			elseif($updated_month == "08"){
				$updated_month_saying = $l_august;
			}
			elseif($updated_month == "09"){
				$updated_month_saying = $l_september;
			}
			elseif($updated_month == "10"){
				$updated_month_saying = $l_october;
			}
			elseif($updated_month == "11"){
				$updated_month_saying = $l_november;
			}
			else{
				$updated_month_saying = $l_december;
			}

			echo"
			<div class=\"my_exercises_div\">
				<table>
				 <tr>
				  <td class=\"my_exercises_row_img\">
				
					";
					// Images
					$query_images = "SELECT exercise_image_id, exercise_image_type, exercise_image_path, exercise_image_file, exercise_image_thumb_150x150 FROM $t_exercise_index_images WHERE exercise_image_exercise_id='$get_exercise_id' ORDER BY exercise_image_type ASC LIMIT 0,1";
					$result_images = mysqli_query($link, $query_images);
					while($row_images = mysqli_fetch_row($result_images)) {
						list($get_exercise_image_id, $get_exercise_image_type, $get_exercise_image_path, $get_exercise_image_file, $get_exercise_image_thumb_150x150) = $row_images;
						if($get_exercise_image_file != "" && file_exists("$root/$get_exercise_image_path/$get_exercise_image_file")){


							if($get_exercise_image_thumb_150x150 == ""){
								$extension = get_extension($get_exercise_image_file);
								$extension = strtolower($extension);

								$thumb = substr($get_exercise_image_file, 0, -4);
								$get_exercise_image_thumb_150x150 = $thumb . "_thumb_150x150." . $extension;
								$thumb_mysql = quote_smart($link, $get_exercise_image_thumb_150x150);

								$result_update = mysqli_query($link, "UPDATE $t_exercise_index_images SET exercise_image_thumb_150x150=$thumb_mysql WHERE exercise_image_id=$get_exercise_image_id") or die(mysqli_error($link));
							}
							if(!(file_exists("../$get_exercise_image_path/$get_exercise_image_thumb_150x150"))){
								$extension = get_extension($get_exercise_image_file);
								$extension = strtolower($extension);

								$thumb = substr($get_exercise_image_file, 0, -4);
								$thumb = $thumb . "_thumb_150x150." . $extension;
								$thumb_mysql = quote_smart($link, $thumb);

								// Thumb
								$inp_new_x = 150;
								$inp_new_y = 150;
								resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_exercise_image_path/$get_exercise_image_file", "$root/$get_exercise_image_path/$get_exercise_image_thumb_150x150");

								$result_update = mysqli_query($link, "UPDATE $t_exercise_index_images SET exercise_image_thumb_150x150=$thumb_mysql WHERE exercise_image_id=$get_exercise_image_id") or die(mysqli_error($link));
							}

							echo"			<a href=\"view_exercise.php?exercise_id=$get_exercise_id&amp;type_id=$get_exercise_type_id&amp;main_muscle_group_id=$get_exercise_muscle_group_id_main&amp;l=$l\"><img src=\"$root/$get_exercise_image_path/$get_exercise_image_thumb_150x150\" alt=\"$get_exercise_image_thumb_150x150\" /></a>\n";
						}
					}
					echo"
				  </td>
				  <td class=\"my_exercises_row_text\">
				
					<p>
					<a href=\"edit_exercise.php?exercise_id=$get_exercise_id&amp;type_id=$type_id&amp;main_muscle_group_id=$get_exercise_muscle_group_id_main&amp;l=$l\">$get_exercise_title</a><br />
					";

					// Other languages?
					$query_translations = "SELECT relation_id, exercise_original_id, exercise_target_id, exercise_language, exercise_translated FROM $t_exercise_index_translations_relations WHERE exercise_original_id=$get_exercise_id OR exercise_target_id=$get_exercise_id";
					$result_translations = mysqli_query($link, $query_translations);
					while($row_translations = mysqli_fetch_row($result_translations)) {
						list($get_relation_id, $get_relation_exercise_original_id, $get_relation_exercise_target_id, $get_relation_exercise_language, $get_relation_exercise_translated) = $row_translations;
						

						if($get_relation_id != "" && $get_exercise_language != "$get_relation_exercise_language"){

							if($get_relation_exercise_original_id == "$get_exercise_id"){
								$relation_translated_id = "$get_relation_exercise_target_id";
							}
							else{
								$relation_translated_id = "$get_relation_exercise_original_id";
							}			


							echo"
							<a href=\"edit_exercise.php?exercise_id=$relation_translated_id&amp;l=$get_relation_exercise_language\"><img src=\"$root/_admin/_translations/site/$get_relation_exercise_language/$get_relation_exercise_language.png\" alt=\"$get_exercise_language.png\" /></a>
							";
						}
					}

					echo"</p>
				
				  </td>
				 </tr>
				</table>
			</div>
			";
	}
	echo"
	<!-- //List all exercises -->
	";
}
else{
	echo"
	<h1>
	<img src=\"_gfx/loading_22.gif\" alt=\"loading_22.gif\" style=\"float:left;padding: 1px 5px 0px 0px;\" />
	Loading...</h1>
	<meta http-equiv=\"refresh\" content=\"1;url=$root/users/login.php?l=$l&amp;referer=$root/exercises/my_exercises.php\">
	";
}



/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>