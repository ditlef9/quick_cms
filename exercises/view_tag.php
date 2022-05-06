<?php
/**
*
* File: _admin/_inc/exercise/view_tag.php
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
if(isset($_GET['tag'])){
	$tag = $_GET['tag'];
	$tag = output_html($tag);
}
else{
	$tag = "";
}

/*- Scriptstart ---------------------------------------------------------------------- */


// Get tag
$tag_mysql = quote_smart($link, $tag);
$l_mysql = quote_smart($link, $l);
$query = "SELECT cloud_id, cloud_language, cloud_text, cloud_clean, cloud_occurrences, cloud_unique_hits, cloud_unique_hits_ipblock FROM $t_exercise_tags_cloud WHERE cloud_language=$l_mysql AND cloud_clean=$tag_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_cloud_id, $get_current_cloud_language, $get_current_cloud_text, $get_current_cloud_clean, $get_current_cloud_occurrences, $get_current_cloud_unique_hits, $get_current_cloud_unique_hits_ipblock) = $row;

// Counter
if($get_current_cloud_id == ""){
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "Server error 404 - $l_exercises";
	include("$root/_webdesign/header.php");
	echo"
	<p>Tag not found</p>
	";
}
else{
	// Count
	$ipblock_array = explode("\n", $get_current_cloud_unique_hits_ipblock);
	$ipblock_array_size = sizeof($ipblock_array);
	if($ipblock_array_size > 10){
		$ipblock_array_size = "5";
	}

	$my_ip_found = 0;
	$inp_ipblock = "$my_ip";
	for($x=0;$x<$ipblock_array_size;$x++){
		if($ipblock_array[$x] == "$my_ip"){
			$my_ip_found = 1;
			break;
		}
		$inp_ipblock = $inp_ipblock . "\n" . "$ipblock_array[$x]";
	}

	if($my_ip_found == "0"){
		$inp_unique_hits = $get_current_cloud_unique_hits+1;
		$inp_ipblock_mysql = quote_smart($link, $inp_ipblock);
		mysqli_query($link, "UPDATE $t_exercise_tags_cloud SET 
					cloud_unique_hits=$inp_unique_hits, 
					cloud_unique_hits_ipblock=$inp_ipblock_mysql
					WHERE cloud_id=$get_current_cloud_id") or die(mysqli_error($link));
	}

	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$tag - $l_exercises";
	include("$root/_webdesign/header.php");

	echo"
	<!-- Headline -->
		<h1>$tag</h1> 
	<!-- //Headline -->


	<!-- Where am I? -->
		<p>
		<b>$l_you_are_here:</b><br />
		<a href=\"$root/exercises/index.php?l=$l\">$l_exercises</a>
		&gt;
		<a href=\"$root/exercises/view_tag.php?tag=$tag&amp;l=$l\">#$tag</a>
		</p>
	<!-- //Where am I? -->

	<!-- Search -->
	<div class=\"exercises_float_left\">
		<form method=\"post\" action=\"search_exercise.php\" enctype=\"multipart/form-data\">
		<p>
		<input type=\"text\" name=\"q\" value=\"\" size=\"20\" id=\"nettport_inp_search_query\" />
		<input type=\"submit\" value=\"$l_search\" id=\"nettport_search_submit_button\" />
		</p>
	

		<!-- Search script -->
		<script id=\"source\" language=\"javascript\" type=\"text/javascript\">
		\$(document).ready(function () {
			\$('#nettport_inp_search_query').keyup(function () {
        			var searchString    = $(\"#nettport_inp_search_query\").val();
       				var data            = 'l=$l&q='+ searchString;
         
        			// if searchString is not empty
        			if(searchString) {
           				// ajax call
            				\$.ajax({
                				type: \"POST\",
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
	<!-- Tags -->
		<p>";
		$l_mysql = quote_smart($link, $l);
		$query = "SELECT cloud_id, cloud_text, cloud_clean, cloud_unique_hits FROM $t_exercise_tags_cloud WHERE cloud_language=$l_mysql ORDER BY cloud_unique_hits DESC LIMIT 0,20";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_cloud_id, $get_cloud_text, $get_cloud_clean, $get_cloud_unique_hits) = $row;
			echo"	<a href=\"view_tag.php?tag=$get_cloud_clean&amp;l=$l\""; if($get_cloud_clean == "$get_current_cloud_clean"){ echo"class=\"btn\""; } else{  echo"class=\"btn_default\""; } echo">$get_cloud_text</a>\n";
		}
		echo"
		</p>
	<!-- //Tags -->

	<!-- Exercises -->
		<div id=\"nettport_search_results\">
	";
	$x = 0;
	$count_exercises = 0;
	$tag_mysql = quote_smart($link, $tag);
	$query = "SELECT $t_exercise_index_tags.tag_id, $t_exercise_index_tags.tag_exercise_id, $t_exercise_index.exercise_id, $t_exercise_index.exercise_title, $t_exercise_index.exercise_user_id, $t_exercise_index.exercise_muscle_group_id_main, $t_exercise_index.exercise_equipment_id, $t_exercise_index.exercise_type_id, $t_exercise_index.exercise_level_id, $t_exercise_index.exercise_updated_datetime, $t_exercise_index.exercise_guide FROM $t_exercise_index_tags JOIN $t_exercise_index ON $t_exercise_index_tags.tag_exercise_id=$t_exercise_index.exercise_id WHERE $t_exercise_index_tags.tag_language=$l_mysql AND $t_exercise_index_tags.tag_clean=$tag_mysql";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_tag_id, $get_tag_exercise_id, $get_exercise_id, $get_exercise_title, $get_exercise_user_id, $get_exercise_muscle_group_id_main, $get_exercise_equipment_id, $get_exercise_type_id, $get_exercise_level_id, $get_exercise_updated_datetime, $get_exercise_guide) = $row;
		
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

		$count_exercises++;

	}
	if($x == "1"){
			echo"
			<div class=\"left_right_right\">
			</div>
			<div class=\"clear\"></div>
			";
	}
	if($count_exercises == "0"){
		// Cleanup
		$result = mysqli_query($link, "DELETE FROM $t_exercise_tags_cloud WHERE cloud_id=$get_current_cloud_id") or die(mysqli_error($link));

		// Make sure all tags exists
		$query = "SELECT tag_id, tag_exercise_id, tag_language, tag_text, tag_clean FROM $t_exercise_index_tags";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_tag_id, $get_tag_exercise_id, $get_tag_language, $get_tag_text, $get_tag_clean) = $row;
		
			$tag_text_mysql = quote_smart($link, $get_tag_text);
			$tag_clean_mysql = quote_smart($link, $get_tag_clean);
			$tag_language_mysql = quote_smart($link, $get_tag_language);
			
			// Check that exercise exists
			$query_e = "SELECT exercise_id FROM $t_exercise_index WHERE exercise_id=$get_tag_exercise_id";
			$result_e = mysqli_query($link, $query_e);
			$row_e = mysqli_fetch_row($result_e);
			list($get_exercise_id) = $row_e;
			if($get_exercise_id == ""){
				echo"
				<div class=\"info\"><p>Tag $get_tag_text belongs to exercise that doesnt exits. Deleting tag.</p></div>";
				mysqli_query($link, "DELETE FROM $t_exercise_index_tags WHERE tag_id=$get_tag_id")
		 		or die(mysqli_error());
			}
			else{
				// Check cloud
				$query_c = "SELECT cloud_id FROM $t_exercise_tags_cloud WHERE cloud_language=$tag_language_mysql AND cloud_clean=$tag_clean_mysql";
				$result_c = mysqli_query($link, $query_c);
				$row_c = mysqli_fetch_row($result_c);
				list($get_cloud_id) = $row_c;
				if($get_cloud_id == ""){
					echo"
					<div class=\"info\"><p>Missing tag $get_tag_text. Inserted it into cloud</p></div>
					";
					mysqli_query($link, "INSERT INTO $t_exercise_tags_cloud(cloud_id, cloud_language, cloud_text, cloud_clean, cloud_occurrences)
					VALUES
					(NULL, $tag_language_mysql, $tag_text_mysql, $tag_clean_mysql, 1)")
		 			or die(mysqli_error());
				}
			}
		}
	} // no exercises found
	echo"
		</div> <!-- //nettport_search_results -->

	<!-- //Exercises -->
	";
} // tag found
?>