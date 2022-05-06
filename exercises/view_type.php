<?php
/**
*
* File: exercises/view_type.php
* Version 1.0.0.
* Date 19:42 08.02.2018
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


if(isset($_GET['type_id'])){
	$type_id = $_GET['type_id'];
	$type_id = strip_tags(stripslashes($type_id));
}
else{
	$type_id = "";
}

// Get type
$type_id_mysql = quote_smart($link, $type_id);
$query = "SELECT type_id, type_title FROM $t_exercise_types WHERE type_id=$type_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_type_id, $get_type_title) = $row;
if($get_current_type_id == ""){
	echo"Type not found";
	die;
}
// Translation
$query_translation = "SELECT type_translation_id, type_translation_value FROM $t_exercise_types_translations WHERE type_id='$get_current_type_id' AND type_translation_language=$l_mysql";
$result_translation = mysqli_query($link, $query_translation);
$row_translation = mysqli_fetch_row($result_translation);
list($get_current_type_translation_id, $get_current_type_translation_value) = $row_translation;


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$get_current_type_translation_value - $l_exercises";
include("$root/_webdesign/header.php");


echo"
<!-- Headline and language -->
	<h1>$get_current_type_translation_value</h1>
<!-- //Headline and language -->


<!-- Where am I? -->
	<div class=\"left\">
		<p><b>$l_you_are_here:</b><br />
		<a href=\"index.php?l=$l\">$l_exercises</a>
		&gt;
		<a href=\"view_type.php?type_id=$type_id&amp;l=$l\">$get_current_type_translation_value</a>
		</p>
	</div>
	<div class=\"clear\"></div>
<!-- //Where am I ? -->

<!-- Search -->
	<div class=\"exercises_float_left\">
		<form method=\"get\" action=\"search_exercise.php\" enctype=\"multipart/form-data\">
		<p>
		<input type=\"text\" name=\"search_query\" value=\"\" size=\"20\" id=\"nettport_inp_search_query\" />
		<input type=\"submit\" value=\"$l_search\" id=\"nettport_search_submit_button\" />
		</p>
	

		<!-- Search script -->
		<script id=\"source\" language=\"javascript\" type=\"text/javascript\">
		\$(document).ready(function () {
			\$('#nettport_inp_search_query').keyup(function () {
        			var searchString    = $(\"#nettport_inp_search_query\").val();
       				var data            = 'l=$l&search_query='+ searchString;
         
        			// if searchString is not empty
        			if(searchString) {
           				// ajax call
            				\$.ajax({
                				type: \"GET\",
               					url: \"search_exercise_jquery.php\",
                				data: data,
						beforeSend: function(html) { // this happens before actual call
							\$(\"#nettport_search_results\").html(''); 
						},
               					success: function(html){
                    					\$(\"#nettport_search_results\").append(html);
              					}
            				});
       				}
        			return false;
            		});
            	});
		</script>
		<!-- //Search script -->
	</div>
<!-- //Search -->

<!-- Buttons -->
	<div class=\"exercises_float_right\">
		<p>
		<a href=\"$root/exercises/my_exercises.php?l=$l\" class=\"btn_default\">$l_my_exercises</a>
		</p>
	</div>
	<div class=\"clear\"></div>
<!-- //Buttons -->

<!-- Sub categories of exercise type -->
	<div class=\"clear\"></div>
	<div class=\"exercises_all_main_categories_selector\">
		<a href=\"#\" id=\"show_all_main_categories_link_img\"><img src=\"_gfx/show_all_categories_img.png\" alt=\"show_all_categories_img.png\" class=\"show_all_main_categories_img\" /></a>
		<a href=\"#\" id=\"show_all_main_categories_link_text\">$l_muscle_group</a>
	</div>

	<script>
	\$(document).ready(function(){
		\$(\"#show_all_main_categories_link_img\").click(function () {
			\$(\"#exercises_show_all_main_categories\").toggle();
		});
		\$(\"#show_all_main_categories_link_text\").click(function () {
			\$(\"#exercises_show_all_main_categories\").toggle();
		});
	});
	</script>

	<div id=\"exercises_show_all_main_categories\">
		<ul>
		";

		// Get sub categories
		$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_parent_id='0'";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_main_muscle_group_id, $get_main_muscle_group_name, $get_main_muscle_group_name_clean, $get_main_muscle_group_parent_id, $get_main_muscle_group_image_path, $get_main_muscle_group_image_file) = $row;
			// Translation
			$query_translation = "SELECT muscle_group_translation_id,muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_main_muscle_group_id AND muscle_group_translation_language=$l_mysql";
			$result_translation = mysqli_query($link, $query_translation);
			$row_translation = mysqli_fetch_row($result_translation);
			list($get_main_muscle_group_translation_id, $get_main_muscle_group_translation_name) = $row_translation;

			echo"			";
			echo"<li><a href=\"$root/exercises/view_muscle_group.php?main_muscle_group_id=$get_main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\">$get_main_muscle_group_translation_name</a></li>\n";
		}
		echo"
		</ul>
	</div>
	<div style=\"height:20px;\"></div>
<!-- //Sub categories of exercise type -->


<!-- Show all main muscle groups -->
	<div id=\"nettport_search_results\">
	";	
	// Get all Show all types
	$query = "SELECT muscle_group_id, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups WHERE muscle_group_parent_id='0'";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_main_muscle_group_id, $get_main_muscle_group_name, $get_main_muscle_group_name_clean, $get_main_muscle_group_parent_id, $get_main_muscle_group_image_path, $get_main_muscle_group_image_file) = $row;

		// Translation
		$query_translation = "SELECT muscle_group_translation_id,muscle_group_translation_name FROM $t_muscle_groups_translations WHERE muscle_group_translation_muscle_group_id=$get_main_muscle_group_id AND muscle_group_translation_language=$l_mysql";
		$result_translation = mysqli_query($link, $query_translation);
		$row_translation = mysqli_fetch_row($result_translation);
		list($get_main_muscle_group_translation_id, $get_main_muscle_group_translation_name) = $row_translation;




		echo"
		<div class=\"clear\"></div>
		
		<p style=\"padding-bottom:0;margin-bottom:0;\">
		<a href=\"view_muscle_group.php?main_muscle_group_id=$get_main_muscle_group_id&amp;type_id=$type_id&amp;l=$l\" class=\"h2\">$get_main_muscle_group_translation_name</a>
		</p>
		";

		// Get exercices in that 
		$x = 0;
		$query_exercises = "SELECT exercise_id, exercise_title FROM $t_exercise_index WHERE exercise_language=$l_mysql AND exercise_muscle_group_id_main='$get_main_muscle_group_id' AND exercise_type_id='$get_current_type_id'";
		$result_exercises = mysqli_query($link, $query_exercises);
		while($row_exercises = mysqli_fetch_row($result_exercises)) {
			list($get_exercise_id, $get_exercise_title) = $row_exercises;

			
			if($x == 0){
				echo"
				<div class=\"clear\"></div>
				<div class=\"left_right_left\">
				";
			}
			elseif($x == 1){
				echo"
				<div class=\"left_right_right\">
				";
			}

			echo"
			<p style=\"padding-bottom:0;margin-bottom:0;\">
			<a href=\"view_exercise.php?exercise_id=$get_exercise_id&amp;type_id=$type_id&amp;main_muscle_group_id=$get_main_muscle_group_id&amp;l=$l\" style=\"color:#000;font-weight: bold\">$get_exercise_title</a><br />
			</p>
			";
			// Images
			$query_images = "SELECT exercise_image_id, exercise_image_type, exercise_image_path, exercise_image_file, exercise_image_thumb_150x150 FROM $t_exercise_index_images WHERE exercise_image_exercise_id='$get_exercise_id' ORDER BY exercise_image_type ASC LIMIT 0,2";
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

					echo"			<a href=\"view_exercise.php?exercise_id=$get_exercise_id&amp;type_id=$type_id&amp;main_muscle_group_id=$get_main_muscle_group_id&amp;l=$l\"><img src=\"$root/$get_exercise_image_path/$get_exercise_image_thumb_150x150\" alt=\"$get_exercise_image_thumb_150x150\" /></a>\n";
				}
			}
			echo"
				</div>
			";
			if($x == 0){
				
				$x++;
			}
			else{
				$x = 0;
			}
		}
	}
	echo"
	</div>
<!-- //Show all types -->

";


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>