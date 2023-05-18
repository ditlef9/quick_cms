<?php
/**
*
* File: exercises/index.php
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


if(isset($_GET['order_by'])) {
	$order_by = $_GET['order_by'];
	$order_by = strip_tags(stripslashes($order_by));
}
else{
	$order_by = "";
}
if(isset($_GET['order_method'])) {
	$order_method = $_GET['order_method'];
	$order_method = strip_tags(stripslashes($order_method));
}
else{
	$order_method = "";
}


/*- Headers ---------------------------------------------------------------------------------- */
$website_title = "$l_exercises";
if(file_exists("./favicon.ico")){ $root = "."; }
elseif(file_exists("../favicon.ico")){ $root = ".."; }
elseif(file_exists("../../favicon.ico")){ $root = "../.."; }
elseif(file_exists("../../../favicon.ico")){ $root = "../../.."; }
include("$root/_webdesign/header.php");


echo"
<!-- Headline and language -->
	<h1>$l_exercises</h1>
<!-- //Headline and language -->


<!-- Search -->
	<div class=\"exercises_float_left\">
		<form method=\"get\" action=\"search_exercise.php\" enctype=\"multipart/form-data\">
		<p>
		<input type=\"text\" name=\"search_query\" value=\"\" size=\"20\" id=\"nettport_inp_search_query\" />
		<input type=\"hidden\" name=\"l\" value=\"$l\" />
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


<!-- Tags -->
	<p>";
	$l_mysql = quote_smart($link, $l);
	$query = "SELECT cloud_id, cloud_text, cloud_clean, cloud_unique_hits FROM $t_exercise_tags_cloud WHERE cloud_language=$l_mysql ORDER BY cloud_unique_hits DESC LIMIT 0,20";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_cloud_id, $get_cloud_text, $get_cloud_clean, $get_cloud_unique_hits) = $row;

		echo"	<a href=\"view_tag.php?tag=$get_cloud_clean&amp;l=$l\" class=\"btn_default\">$get_cloud_text</a>\n";
	}
	echo"
	</p>
<!-- //Tags -->

<!-- Types -->
	<div class=\"exercises_all_main_categories_selector\">
		<a href=\"#\" id=\"show_all_main_categories_link_img\"><img src=\"_gfx/show_all_categories_img.png\" alt=\"show_all_categories_img.png\" class=\"show_all_main_categories_img\" /></a>
		<a href=\"#\" id=\"show_all_main_categories_link_text\">$l_types</a>
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
		// Get all exercises categories
		$query = "SELECT type_id, type_title FROM $t_exercise_types ORDER BY type_title ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_type_id, $get_type_title) = $row;

			// Translation
			$query_translation = "SELECT type_translation_id, type_translation_value FROM $t_exercise_types_translations WHERE type_id='$get_type_id' AND type_translation_language=$l_mysql";
			$result_translation = mysqli_query($link, $query_translation);
			$row_translation = mysqli_fetch_row($result_translation);
			list($get_type_translation_id, $get_type_translation_value) = $row_translation;

			echo"			";
			echo"<li><a href=\"$root/exercises/view_type.php?type_id=$get_type_id&amp;l=$l\">$get_type_translation_value</a></li>\n";
		}
		echo"
		</ul>
	</div>
<!-- //Types -->

<!-- Show last added exercises -->
	<div id=\"nettport_search_results\">
	";	
	//  
	$x = 0;
	$query = "SELECT exercise_id, exercise_title, exercise_user_id, exercise_muscle_group_id_main, exercise_equipment_id, exercise_type_id, exercise_level_id, exercise_updated_datetime, exercise_guide FROM $t_exercise_index WHERE exercise_language=$l_mysql ORDER BY exercise_uniqe_hits DESC";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_exercise_id, $get_exercise_title, $get_exercise_user_id, $get_exercise_muscle_group_id_main, $get_exercise_equipment_id, $get_exercise_type_id, $get_exercise_level_id, $get_exercise_updated_datetime, $get_exercise_guide) = $row;


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
				<p style=\"padding: 10px 0px 0px 0px;margin-bottom:0;\">
				<a href=\"view_exercise.php?exercise_id=$get_exercise_id&amp;type_id=$get_exercise_type_id&amp;main_muscle_group_id=$get_exercise_muscle_group_id_main&amp;l=$l\" class=\"exercise_index_title\">$get_exercise_title</a><br />
				</p>\n";
					// Images
					$query_images = "SELECT exercise_image_id, exercise_image_type, exercise_image_path, exercise_image_file, exercise_image_thumb_120x120, exercise_image_thumb_150x150 FROM $t_exercise_index_images WHERE exercise_image_exercise_id='$get_exercise_id' ORDER BY exercise_image_type ASC LIMIT 0,2";
					$result_images = mysqli_query($link, $query_images);
					while($row_images = mysqli_fetch_row($result_images)) {
						list($get_exercise_image_id, $get_exercise_image_type, $get_exercise_image_path, $get_exercise_image_file, $get_exercise_image_thumb_120x120, $get_exercise_image_thumb_150x150) = $row_images;

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
								// Thumb
								$inp_new_x = 150;
								$inp_new_y = 150;
								resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_exercise_image_path/$get_exercise_image_file", "$root/$get_exercise_image_path/$get_exercise_image_thumb_150x150");
							}

							echo"				";
							echo"<a href=\"view_exercise.php?exercise_id=$get_exercise_id&amp;type_id=$get_exercise_type_id&amp;main_muscle_group_id=$get_exercise_muscle_group_id_main&amp;l=$l\"><img src=\"$root/$get_exercise_image_path/$get_exercise_image_thumb_150x150\" alt=\"$get_exercise_image_thumb_150x150\" /></a>\n";
						}
					}
					echo"
			</div>
		";
		if($x == 1){
			$x = -1;
		}
		$x++;
	}
	echo"
	</div>
<!-- //Show all types -->

";


/*- Footer ----------------------------------------------------------------------------------- */
include("$root/_webdesign/footer.php");
?>