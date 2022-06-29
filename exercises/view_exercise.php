<?php
/**
*
* File: _admin/_inc/exercise/view_exercise.php
* Version 1.0.0
* Date 18:48 24.03.2019
* Copyright (c) 2008-2019 Sindre Andre Ditlefsen
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
$t_stats_comments_per_week	= $mysqlPrefixSav . "stats_comments_per_week";

/*- Scriptstart ---------------------------------------------------------------------- */


// Get exercise
$exercise_id_mysql = quote_smart($link, $exercise_id);
$query = "SELECT exercise_id, exercise_title, exercise_title_alternative, exercise_user_id, exercise_language, exercise_muscle_group_id_main, exercise_muscle_group_id_sub, exercise_muscle_part_of_id, exercise_equipment_id, exercise_type_id, exercise_level_id, exercise_text, exercise_preparation, exercise_guide, exercise_important, exercise_created_datetime, exercise_updated_datetime, exercise_user_ip, exercise_uniqe_hits, exercise_uniqe_hits_ip_block, exercise_likes, exercise_dislikes, exercise_rating, exercise_rating_ip_block, exercise_number_of_comments, exercise_reported, exercise_reported_checked, exercise_reported_reason FROM $t_exercise_index WHERE exercise_id=$exercise_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_current_exercise_id, $get_current_exercise_title, $get_current_exercise_title_alternative, $get_current_exercise_user_id, $get_current_exercise_language, $get_current_exercise_muscle_group_id_main, $get_current_exercise_muscle_group_id_sub, $get_current_exercise_muscle_part_of_id, $get_current_exercise_equipment_id, $get_current_exercise_type_id, $get_current_exercise_level_id, $get_current_exercise_text, $get_current_exercise_preparation, $get_current_exercise_guide, $get_current_exercise_important, $get_current_exercise_created_datetime, $get_current_exercise_updated_datetime, $get_current_exercise_user_ip, $get_current_exercise_uniqe_hits, $get_current_exercise_uniqe_hits_ip_block, $get_current_exercise_likes, $get_current_exercise_dislikes, $get_current_exercise_rating, $get_current_exercise_rating_ip_block, $get_current_exercise_number_of_comments, $get_current_exercise_reported, $get_current_exercise_reported_checked, $get_current_exercise_reported_reason) = $row;


if($get_current_exercise_id == ""){
	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$l_exercises - Server error 404";
	include("$root/_webdesign/header.php");

	echo"
	<p>Exercise not found.</p>
	";
	include("$root/_webdesign/footer.php");

}
else {
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


	// Update last viewed
	$datetime = date("Y-m-d H:i:s");
	$result = mysqli_query($link, "UPDATE $t_exercise_index SET exercise_last_viewed='$datetime' WHERE exercise_id=$get_current_exercise_id") or die(mysqli_error($link));

	// Unique views
	$inp_ip = $_SERVER['REMOTE_ADDR'];
	$inp_ip = output_html($inp_ip);

	$ip_array = explode("\n", $get_current_exercise_uniqe_hits_ip_block);
	$ip_array_size = sizeof($ip_array);

	$has_seen_this_before = 0;

	for($x=0;$x<$ip_array_size;$x++){
		if($ip_array[$x] == "$inp_ip"){
			$has_seen_this_before = 1;
			break;
		}
		if($x > 5){
			break;
		}
	}
	
	if($has_seen_this_before == 0){
		$inp_exercise_uniqe_hits_ip_block = $inp_ip . "\n" . $get_current_exercise_uniqe_hits_ip_block;
		$inp_exercise_uniqe_hits_ip_block_mysql = quote_smart($link, $inp_exercise_uniqe_hits_ip_block);
		$inp_exercise_uniqe_hits = $get_current_exercise_uniqe_hits + 1;
		$result = mysqli_query($link, "UPDATE $t_exercise_index  SET exercise_uniqe_hits=$inp_exercise_uniqe_hits, exercise_uniqe_hits_ip_block=$inp_exercise_uniqe_hits_ip_block_mysql WHERE exercise_id=$get_current_exercise_id") or die(mysqli_error($link));
	}




	/*- Headers ---------------------------------------------------------------------------------- */
	$website_title = "$get_current_exercise_title - $l_exercises";
	if($get_current_exercise_title_alternative != ""){
		$website_title = "$get_current_exercise_title ($get_current_exercise_title_alternative) - $l_exercises";
	}
	include("$root/_webdesign/header.php");

	if($process != "1"){
		echo"
		<!-- Edit/Delete -->";
		if(isset($_SESSION['user_id']) && isset($_SESSION['security'])){

			// Get my user
			$my_user_id = $_SESSION['user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);

			$my_security = $_SESSION['security'];
			$my_security = output_html($my_security);
			$my_security_mysql = quote_smart($link, $my_security);


			$query = "SELECT user_id, user_name, user_language, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql AND user_security=$my_security_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_user_id, $get_my_user_name, $get_my_user_language, $get_my_user_rank) = $row;

	
			if($get_current_exercise_user_id == "$my_user_id" OR $get_my_user_rank == "admin" OR $get_my_user_rank == "moderator"){
				echo"
				<div style=\"float: right;\">
					<p>
					<a href=\"edit_exercise.php?exercise_id=$exercise_id&amp;type_id=$get_current_exercise_type_id&amp;main_muscle_group_id=$get_current_exercise_muscle_group_id_main&amp;l=$l\"><img src=\"_gfx/icons/edit.png\" alt=\"edit.png\" /></a>
					</p>
				</div>
				";
			}
		}
		echo"
		<!-- //Edit/Delete -->
		<!-- Headline -->
			<h1>$get_current_exercise_title</h1> 
		<!-- //Headline -->


		<!-- Where am I? -->
			<div class=\"exercises_float_left\">
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
				<a href=\"$root/exercises/view_exercise.php?exercise_id=$exercise_id&amp;l=$l\">$get_current_exercise_title</a>
				</p>
			</div>
		<!-- //Where am I? -->

	
		<!-- Search -->
			<div class=\"exercises_float_right\">
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
			<div class=\"clear\"></div>
			<div id=\"nettport_search_results\"></div>
		<!-- //Search -->
		<!-- Ad -->
		";
		include("$root/ad/_includes/ad_main_below_headline.php");
		echo"
		<!-- //Ad -->

		<!-- Muscles beeing trained -->
		<div style=\"height: 10px;\"></div>
		<table class=\"hor-zebra\">
		 <tbody>
		  <tr>
		   <td>
			<table>
			 <tbody>
			  <tr>
			   <td style=\"padding-left: 10px;\">
				<p><b>$l_muscles_trained:</b></p>
			   </td>
			   <td style=\"padding-left: 10px;\">
				<p style=\"font-weight: bold\">";

				

				$exercise_muscle_image_main_muscle_ids = "";
				$exercise_muscle_image_assistant_muscle_ids = "";
				$count = 0;
				$query = "SELECT exercise_muscle_id, exercise_muscle_muscle_id FROM $t_exercise_index_muscles WHERE exercise_muscle_exercise_id='$get_current_exercise_id' AND exercise_muscle_type='main'";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_exercise_muscle_id, $get_exercise_muscle_muscle_id) = $row;


					// Find muscles main and sub group
					$query_muscle = "SELECT muscle_id, muscle_latin_name_clean, muscle_group_id_main, muscle_group_id_sub FROM $t_muscles WHERE muscle_id='$get_exercise_muscle_muscle_id'";
					$result_muscle = mysqli_query($link, $query_muscle);
					$row_muscle = mysqli_fetch_row($result_muscle);
					list($get_muscle_id, $get_muscle_latin_name_clean, $get_muscle_group_id_main, $get_muscle_group_id_sub) = $row_muscle;


					// Translation
					$query_translation = "SELECT muscle_translation_id, muscle_translation_simple_name, muscle_translation_short_name FROM $t_muscles_translations WHERE muscle_translation_muscle_id='$get_exercise_muscle_muscle_id' AND muscle_translation_language=$l_mysql";
					$result_translation = mysqli_query($link, $query_translation);
					$row_translation = mysqli_fetch_row($result_translation);
					list($get_muscle_translation_id, $get_muscle_translation_simple_name, $get_muscle_translation_short_name) = $row_translation;
					

					if($count > 0){
						echo",";
					}
					echo"
					<a href=\"$root/muscles/muscle.php?main_group_id=$get_muscle_group_id_main&amp;sub_group_id=$get_muscle_group_id_sub&amp;muscle_id=$get_exercise_muscle_muscle_id&amp;l=$l\" style=\"font-weight: bold\">$get_muscle_translation_simple_name</a>";
					
					$count = $count+1;

					// Muscle image
					if($exercise_muscle_image_main_muscle_ids == ""){
						$exercise_muscle_image_main_muscle_ids = $get_exercise_muscle_id;
					}
					else{
						$exercise_muscle_image_main_muscle_ids = $exercise_muscle_image_main_muscle_ids . "," . $get_exercise_muscle_id;
					}
				}
				echo"
				</p>
			   </td>
			  </tr>
			  <tr>
			   <td style=\"padding-left: 10px;\">
				<p>$l_assistant_muscles:</p>
			   </td>
			   <td style=\"padding-left: 10px;\">
				<p>";


				$count = 0;
				$query = "SELECT exercise_muscle_id, exercise_muscle_muscle_id FROM $t_exercise_index_muscles WHERE exercise_muscle_exercise_id='$get_current_exercise_id' AND exercise_muscle_type='assistant'";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_exercise_muscle_id, $get_exercise_muscle_muscle_id) = $row;


					// Find muscles main and sub group	
					$query_muscle = "SELECT muscle_id, muscle_latin_name_clean, muscle_group_id_main, muscle_group_id_sub FROM $t_muscles WHERE muscle_id='$get_exercise_muscle_muscle_id'";
					$result_muscle = mysqli_query($link, $query_muscle);
					$row_muscle = mysqli_fetch_row($result_muscle);
					list($get_muscle_id, $get_muscle_latin_name_clean, $get_muscle_group_id_main, $get_muscle_group_id_sub) = $row_muscle;


					// Translation
					$query_translation = "SELECT muscle_translation_id, muscle_translation_simple_name, muscle_translation_short_name FROM $t_muscles_translations WHERE muscle_translation_muscle_id='$get_exercise_muscle_muscle_id' AND muscle_translation_language=$l_mysql";
					$result_translation = mysqli_query($link, $query_translation);
					$row_translation = mysqli_fetch_row($result_translation);
					list($get_muscle_translation_id, $get_muscle_translation_simple_name, $get_muscle_translation_short_name) = $row_translation;
					

					if($count != 0){
						echo",";
					}
					echo"
					<a href=\"$root/muscles/muscle.php?main_group_id=$get_muscle_group_id_main&amp;sub_group_id=$get_muscle_group_id_sub&amp;muscle_id=$get_exercise_muscle_muscle_id&amp;l=$l\">$get_muscle_translation_simple_name</a>";
					
					$count = $count+1;

					// Muscle image
					if($exercise_muscle_image_assistant_muscle_ids == ""){
						$exercise_muscle_image_assistant_muscle_ids = $get_exercise_muscle_id;
					}
					else{
						$exercise_muscle_image_assistant_muscle_ids = $exercise_muscle_image_assistant_muscle_ids . "," . $get_exercise_muscle_id;
					}
				}
				echo"
				</p>
			   </td>
			  </tr>
			 </tbody>
			</table>
		   </td>
		  </tr>
		 </tbody>
		</table>
		<!-- //Muscles beeing trained -->

		<!-- Video -->";

		$query = "SELECT exercise_video_id, exercise_video_service_name, exercise_video_service_id FROM $t_exercise_index_videos WHERE exercise_video_exercise_id=$get_current_exercise_id";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_exercise_video_id, $get_exercise_video_service_name, $get_exercise_video_service_id) = $row;
		if($get_exercise_video_id != ""){
			if($get_exercise_video_service_name == "youtube"){
				echo"<p></p><iframe width=\"715\" height=\"402\" src=\"https://www.youtube.com/embed/$get_exercise_video_service_id\" frameborder=\"0\" allow=\"autoplay; encrypted-media\" allowfullscreen></iframe>";
			}
		}
		echo"
		<!-- //Video -->
		<!-- Display all images -->

		<table>
		 <tr>";

		$img_counter = 0;
		$query = "SELECT exercise_image_id, exercise_image_type, exercise_image_path, exercise_image_file, exercise_image_thumb_350x350 FROM $t_exercise_index_images WHERE exercise_image_exercise_id='$get_current_exercise_id' ORDER BY exercise_image_type ASC";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_exercise_image_id, $get_exercise_image_type, $get_exercise_image_path, $get_exercise_image_file, $get_exercise_image_thumb_350x350) = $row;


			if($get_exercise_image_file != "" && file_exists("$root/$get_exercise_image_path/$get_exercise_image_file")){
					
				if($get_exercise_image_thumb_350x350 == ""){
					$extension = getExtension($get_exercise_image_file);
					$extension = strtolower($extension);

					$thumb = substr($get_exercise_image_file, 0, -4);
					$get_exercise_image_thumb_350x350 = $thumb . "_thumb_350x350." . $extension;
					$thumb_mysql = quote_smart($link, $get_exercise_image_thumb_350x350);

					$result_update = mysqli_query($link, "UPDATE $t_exercise_index_images SET exercise_image_thumb_350x350=$thumb_mysql WHERE exercise_image_id=$get_exercise_image_id") or die(mysqli_error($link));
					echo"<div class=\"info\"><p>Creating thumb</p></div>\n";
				}		
				if(!(file_exists("../$get_exercise_image_path/$get_exercise_image_thumb_350x350"))){
					$extension = getExtension($get_exercise_image_file);
					$extension = strtolower($extension);

					$thumb = substr($get_exercise_image_file, 0, -4);
					$thumb = $thumb . "_thumb_350x350." . $extension;
					$thumb_mysql = quote_smart($link, $thumb);

					// Thumb
					$inp_new_x = 350;
					$inp_new_y = 350;
					resize_crop_image($inp_new_x, $inp_new_y, "$root/$get_exercise_image_path/$get_exercise_image_file", "$root/$get_exercise_image_path/$get_exercise_image_thumb_350x350");

					$result_update = mysqli_query($link, "UPDATE $t_exercise_index_images SET exercise_image_thumb_350x350=$thumb_mysql WHERE exercise_image_id=$get_exercise_image_id") or die(mysqli_error($link));
				}

					
				echo"
				  <td style=\"padding-right: 10px;\">
					<p>
					<a href=\"$root/$get_exercise_image_path/$get_exercise_image_file\"><img src=\"$root/$get_exercise_image_path/$get_exercise_image_thumb_350x350\" alt=\"$get_exercise_image_thumb_350x350\" /></a>
					</p>
				  </td>";

				$img_counter++;
			}
		}
		if($img_counter == "0"){

			$missing_file = "$root/_cache/exercise_missing_img_$get_current_exercise_id.txt";

			
			if(!(file_exists("$missing_file"))){
				// Who is moderator of the week?
				$week = date("W");
				$year = date("Y");

				$query = "SELECT moderator_user_id, moderator_user_email, moderator_user_name FROM $t_users_moderator_of_the_week WHERE moderator_week=$week AND moderator_year=$year";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name) = $row;
				if($get_moderator_user_id == ""){
					// Create moderator of the week
					include("$root/_admin/_functions/create_moderator_of_the_week.php");
					
					$query = "SELECT moderator_user_id, moderator_user_email, moderator_user_name FROM $t_users_moderator_of_the_week WHERE moderator_week=$week AND moderator_year=$year";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name) = $row;
				}


				echo"<div class=\"clear\"></div>
				<div class=\"info\"><p>E-mail sent to admins. Writing to $missing_file</p></div>";

				// Mail from
				$host = $_SERVER['HTTP_HOST'];
		
				$view_link = $configSiteURLSav . "/exercises/view_exercise.php?exercise_id=$get_current_exercise_id";

				$subject = "Exercise missing images $get_current_exercise_title at $host";

				$message = "<html>\n";
				$message = $message. "<head>\n";
				$message = $message. "  <title>$subject</title>\n";
				$message = $message. " </head>\n";
				$message = $message. "<body>\n";

				$message = $message . "<p>Hi $get_moderator_user_name,</p>\n\n";
				$message = $message . "<p><b>Summary:</b><br />A exercise is missing image.</p>\n\n";

					$message = $message . "<p style='padding-bottom:0;margin-bottom:0'><b>Information:</b></p>\n";
				$message = $message . "<table>\n";
				$message = $message . " <tr><td><span>Exercise ID:</span></td><td><span>$get_current_exercise_id</span></td></tr>\n";
				$message = $message . " <tr><td><span>Title:</span></td><td><span><a href=\"$view_link\">$get_current_exercise_title</a></span></td></tr>\n";
				$message = $message . "</table>\n";
		
				$message = $message . "<p>\n\n--<br />\nBest regards<br />\n$host</p>";
				$message = $message. "</body>\n";
				$message = $message. "</html>\n";


				// Preferences for Subject field
				$headers[] = 'MIME-Version: 1.0';
				$headers[] = 'Content-type: text/html; charset=utf-8';
				$headers[] = "From: $configFromNameSav <" . $configFromEmailSav . ">";
				// mail($get_moderator_user_email, $subject, $message, implode("\r\n", $headers));


				$fh = fopen($missing_file, "w") or die("can not open file");
				fwrite($fh, "-");
				fclose($fh);
			} // missing file
		} // img counter 0 
		echo"
		 </tr>
		</table>
		<!-- //Display all images -->

		<!-- Text -->
			$get_current_exercise_text

			<p style=\"padding-bottom:0;margin-bottom:0\"><b>$l_preparation</b></p>
			$get_current_exercise_preparation

			<p style=\"padding-bottom:0;margin-bottom:0\"><b>$l_guide</b></p>
			$get_current_exercise_guide

			<p style=\"padding-bottom:0;margin-bottom:0\"><b>$l_important</b></p>
			$get_current_exercise_important
		<!-- //Text -->

		<!-- Muscle image -->";
			// Find image
			if($exercise_muscle_image_main_muscle_ids == ""){ $exercise_muscle_image_main_muscle_ids = 0; }
			$exercise_muscle_image_main_muscle_ids_mysql = quote_smart($link, $exercise_muscle_image_main_muscle_ids);
			if($exercise_muscle_image_assistant_muscle_ids == ""){ $exercise_muscle_image_assistant_muscle_ids = 0; }
			$exercise_muscle_image_assistant_muscle_ids_mysql = quote_smart($link, $exercise_muscle_image_assistant_muscle_ids);
			$query_muscle = "SELECT exercise_muscle_image_id, exercise_muscle_image_file, exercise_muscle_image_main_muscle_ids, exercise_muscle_image_assistant_muscle_ids FROM $t_exercise_index_muscles_images WHERE exercise_muscle_image_exercise_id='$get_current_exercise_id' AND exercise_muscle_image_main_muscle_ids=$exercise_muscle_image_main_muscle_ids_mysql AND exercise_muscle_image_assistant_muscle_ids=$exercise_muscle_image_assistant_muscle_ids_mysql";
			$result_muscle = mysqli_query($link, $query_muscle);
			$row_muscle = mysqli_fetch_row($result_muscle);
			list($get_exercise_muscle_image_id, $get_exercise_muscle_image_file, $get_exercise_muscle_image_main_muscle_ids, $get_exercise_muscle_image_assistant_muscle_ids) = $row_muscle;
			
			// Muscle image doesnt exists
			if($get_exercise_muscle_image_id == ""){
				// Truncate all existsing
				mysqli_query($link, "TRUNCATE $t_exercise_index_muscles_images");

				$inp_exercise_muscle_image_file = "main-";

				$x=0;
				$query = "SELECT exercise_muscle_id, exercise_muscle_muscle_id FROM $t_exercise_index_muscles WHERE exercise_muscle_exercise_id='$get_current_exercise_id' AND exercise_muscle_type='main'";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_exercise_muscle_id, $get_exercise_muscle_muscle_id) = $row;


					// Find muscles main and sub group
					$query_muscle = "SELECT muscle_id, muscle_latin_name_clean, muscle_group_id_main, muscle_group_id_sub FROM $t_muscles WHERE muscle_id='$get_exercise_muscle_muscle_id'";
					$result_muscle = mysqli_query($link, $query_muscle);
					$row_muscle = mysqli_fetch_row($result_muscle);
					list($get_muscle_id, $get_muscle_latin_name_clean, $get_muscle_group_id_main, $get_muscle_group_id_sub) = $row_muscle;


					// Translation
					$query_translation = "SELECT muscle_translation_id, muscle_translation_simple_name, muscle_translation_short_name FROM $t_muscles_translations WHERE muscle_translation_muscle_id='$get_exercise_muscle_muscle_id' AND muscle_translation_language=$l_mysql";
					$result_translation = mysqli_query($link, $query_translation);
					$row_translation = mysqli_fetch_row($result_translation);
					list($get_muscle_translation_id, $get_muscle_translation_simple_name, $get_muscle_translation_short_name) = $row_translation;
					

					if($x == 0){
						$inp_exercise_muscle_image_file = $inp_exercise_muscle_image_file . "$get_muscle_latin_name_clean";
					}
					else{
						$inp_exercise_muscle_image_file = $inp_exercise_muscle_image_file . "-" . $get_muscle_latin_name_clean;
					}
					$x++;
				}
			
				
				$inp_exercise_muscle_image_file = $inp_exercise_muscle_image_file  . "-assistant-";
				$x = 0;
				$query = "SELECT exercise_muscle_id, exercise_muscle_muscle_id FROM $t_exercise_index_muscles WHERE exercise_muscle_exercise_id='$get_current_exercise_id' AND exercise_muscle_type='assistant'";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_exercise_muscle_id, $get_exercise_muscle_muscle_id) = $row;


					// Find muscles main and sub group	
					$query_muscle = "SELECT muscle_id, muscle_latin_name_clean, muscle_group_id_main, muscle_group_id_sub FROM $t_muscles WHERE muscle_id='$get_exercise_muscle_muscle_id'";
					$result_muscle = mysqli_query($link, $query_muscle);
					$row_muscle = mysqli_fetch_row($result_muscle);
					list($get_muscle_id, $get_muscle_latin_name_clean, $get_muscle_group_id_main, $get_muscle_group_id_sub) = $row_muscle;


					// Translation
					$query_translation = "SELECT muscle_translation_id, muscle_translation_simple_name, muscle_translation_short_name FROM $t_muscles_translations WHERE muscle_translation_muscle_id='$get_exercise_muscle_muscle_id' AND muscle_translation_language=$l_mysql";
					$result_translation = mysqli_query($link, $query_translation);
					$row_translation = mysqli_fetch_row($result_translation);
					list($get_muscle_translation_id, $get_muscle_translation_simple_name, $get_muscle_translation_short_name) = $row_translation;
					

					if($x == 0){
						$inp_exercise_muscle_image_file = $inp_exercise_muscle_image_file . "$get_muscle_latin_name_clean";
					}
					else{
						$inp_exercise_muscle_image_file = $inp_exercise_muscle_image_file . "-" . $get_muscle_latin_name_clean;
					}
					$x++;
				}


				$inp_exercise_muscle_image_file  = $inp_exercise_muscle_image_file . ".png";
				$inp_exercise_muscle_image_file_mysql = quote_smart($link, $inp_exercise_muscle_image_file);

				mysqli_query($link, "INSERT INTO $t_exercise_index_muscles_images
				(exercise_muscle_image_id, exercise_muscle_image_exercise_id, exercise_muscle_image_file, exercise_muscle_image_main_muscle_ids, exercise_muscle_image_assistant_muscle_ids) 
				VALUES 
				(NULL, '$get_current_exercise_id', $inp_exercise_muscle_image_file_mysql, $exercise_muscle_image_main_muscle_ids_mysql, $exercise_muscle_image_assistant_muscle_ids_mysql)")
				or die(mysqli_error($link));

				echo"<div class=\"info\"><p>Added muscle image $inp_exercise_muscle_image_file</p></div>";


				$query_muscle = "SELECT exercise_muscle_image_id, exercise_muscle_image_file FROM $t_exercise_index_muscles_images WHERE exercise_muscle_image_exercise_id='$get_current_exercise_id'";
				$result_muscle = mysqli_query($link, $query_muscle);
				$row_muscle = mysqli_fetch_row($result_muscle);
				list($get_exercise_muscle_image_id, $get_exercise_muscle_image_file) = $row_muscle;
			}


			if(!(file_exists("$root/_uploads/exercises/muscle_image/$get_exercise_muscle_image_file"))){
			
				$missing_file = "$root/_cache/exercise_muscle_missing_img_$get_current_exercise_id.txt";

			
				if(!(file_exists("$missing_file"))){
					// Who is moderator of the week?
					$week = date("W");
					$year = date("Y");

					$query = "SELECT moderator_user_id, moderator_user_email, moderator_user_name FROM $t_users_moderator_of_the_week WHERE moderator_week=$week AND moderator_year=$year";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name) = $row;
						if($get_moderator_user_id == ""){
						// Create moderator of the week
						include("$root/_admin/_functions/create_moderator_of_the_week.php");
					
						$query = "SELECT moderator_user_id, moderator_user_email, moderator_user_name FROM $t_users_moderator_of_the_week WHERE moderator_week=$week AND moderator_year=$year";
						$result = mysqli_query($link, $query);
						$row = mysqli_fetch_row($result);
						list($get_moderator_user_id, $get_moderator_user_email, $get_moderator_user_name) = $row;
					}


					echo"<div class=\"clear\"></div>
					<div class=\"info\"><p>E-mail sent to admins. Writing to $missing_file</p></div>";

					// Mail from
					$host = $_SERVER['HTTP_HOST'];
		
					$view_link = $configSiteURLSav . "/exercises/view_exercise.php?exercise_id=$get_current_exercise_id";

					$subject = "Exercise missing muscle image $get_exercise_muscle_image_file at $host";

					$message = "<html>\n";
					$message = $message. "<head>\n";
					$message = $message. "  <title>$subject</title>\n";
					$message = $message. " </head>\n";
					$message = $message. "<body>\n";

					$message = $message . "<p>Hi $get_moderator_user_name,</p>\n\n";
					$message = $message . "<p><b>Summary:</b><br />A exercise is missing muscle image.</p>\n\n";

					$message = $message . "<p style='padding-bottom:0;margin-bottom:0'><b>Information:</b></p>\n";
					$message = $message . "<table>\n";
					$message = $message . " <tr><td><span>Exercise ID:</span></td><td><span>$get_current_exercise_id</span></td></tr>\n";
					$message = $message . " <tr><td><span>Title:</span></td><td><span><a href=\"$view_link\">$get_current_exercise_title</a></span></td></tr>\n";
					$message = $message . "</table>\n";
		
					$message = $message . "<p>\n\n--<br />\nBest regards<br />\n$host</p>";
					$message = $message. "</body>\n";
					$message = $message. "</html>\n";


					// Preferences for Subject field
					$headers[] = 'MIME-Version: 1.0';
					$headers[] = 'Content-type: text/html; charset=utf-8';
					$headers[] = "From: $configFromNameSav <" . $configFromEmailSav . ">";
					// mail($get_moderator_user_email, $subject, $message, implode("\r\n", $headers));



					$fh = fopen($missing_file, "w") or die("can not open file");
					fwrite($fh, "-");
					fclose($fh);
				} // missing file
			
			} // img doesnt exists
			echo"
			<p><img src=\"$root/_uploads/exercises/muscle_image/$get_exercise_muscle_image_file\" alt=\"$get_exercise_muscle_image_file\" /></p>
		<!-- //Muscle image -->
		

		<!-- Meta -->
		<p style=\"padding-bottom:0;margin-bottom:0\"><b>$l_extra_information</b></p>
		<div class=\"meta_left\">
			<table>
			 <tr>
			   <td style=\"padding: 6px 6px 0px 0px;\">
				<img src=\"_gfx/icons/start-here.png\" alt=\"start-here.png\" />
			   </td>
			   <td style=\"padding: 6px 0px 0px 0px;\">
				<span>$l_level:</span>
				$get_current_level_translation_value</span>
			  </td>
			 </tr>
			 <tr>
			   <td style=\"padding: 6px 6px 0px 0px;\">
				<img src=\"_gfx/icons/package-x-generic.png\" alt=\"package-x-generic.png\" />
			  </td>
			   <td style=\"padding: 6px 0px 0px 0px;\">
				<span>$l_equipment:";
				if($get_current_exercise_equipment_id != ""){

					$query = "SELECT equipment_id, equipment_title, equipment_title_clean, equipment_text, equipment_image_path, equipment_image_file FROM $t_exercise_equipments WHERE equipment_id=$get_current_exercise_equipment_id";
					$result = mysqli_query($link, $query);
					$row = mysqli_fetch_row($result);
					list($get_equipment_id, $get_equipment_title, $get_equipment_title_clean, $get_equipment_text, $get_equipment_image_path, $get_equipment_image_file) = $row;
					echo" <a href=\"view_equipment.php?equipment_id=$get_equipment_id&amp;l=$l\">$get_equipment_title</a>";
				}
				echo"
			  </td>
			 </tr>
			 <tr>
			   <td style=\"padding: 6px 6px 0px 0px;\">
				<img src=\"_gfx/icons/member.png\" alt=\"member.png\" />
			   </td>
			   <td style=\"padding: 6px 0px 0px 0px;\">
				<span>$l_author:
				<a href=\"$root/users/view_profile.php?user_id=$get_current_exercise_user_id&amp;l=$l\">$get_author_user_alias</a></span>
			  </td>
			 </tr>
			</table>
		</div>

		<div class=\"meta_right\">

			<table>";
			if($get_current_exercise_title_alternative != ""){
				echo"
			 <tr>
			   <td style=\"padding: 6px 6px 0px 0px;\">
				<img src=\"_gfx/icons/document.png\" alt=\"document.png\" />
			  </td>
			   <td style=\"padding: 6px 0px 0px 0px;\">
				<span>$l_alt_title:
				$get_current_exercise_title_alternative</span>
			  </td>
			 </tr>
				";
			}
			echo"
			 <tr>
			   <td style=\"padding: 6px 6px 0px 0px;\">
				<img src=\"_gfx/icons/tag_green.png\" alt=\"tag_green.png\" />
			   </td>
			   <td style=\"padding: 6px 0px 0px 0px;\">
				<span>$l_tags:
				";
				// Count tags
				$query = "SELECT count(tag_id) FROM $t_exercise_index_tags WHERE tag_exercise_id=$get_current_exercise_id";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_row($result);
				list($get_count_tags) = $row;
		
				if($get_count_tags != "0"){

					$x = 0;
					$get_count_tags_minus_two = $get_count_tags-2;
					$query = "SELECT tag_id, tag_text, tag_clean FROM $t_exercise_index_tags WHERE tag_exercise_id=$get_current_exercise_id";
					$result = mysqli_query($link, $query);
					while($row = mysqli_fetch_row($result)) {
						list($get_tag_id, $get_tag_text, $get_tag_clean) = $row;
						echo"
						<a href=\"view_tag.php?tag=$get_tag_clean&amp;l=$l\">#$get_tag_text</a>";
				
						if($x < $get_count_tags_minus_two){
							echo", ";
						}
						elseif($x == $get_count_tags_minus_two){
							echo" $l_and_lowercase ";
						}

						$x++;
					}
				}
				echo"
			  </td>
			 </tr>
			 <tr>
			   <td style=\"padding: 6px 6px 0px 0px;\">
				<img src=\"_gfx/icons/eye_dark_grey.png\" alt=\"eye_dark_grey.png\" />
			   </td>
			   <td style=\"padding: 6px 0px 0px 0px;\">
				<span>$l_unique_hits:
				$get_current_exercise_uniqe_hits</span>
			  </td>
			 </tr>
			</table>
		</div>
		<!-- //Meta -->



		<!-- Translate -->";

		if($get_current_exercise_language == "no" && isset($_SESSION['user_id']) && isset($_SESSION['security'])){

			// Get my user
			$my_user_id = $_SESSION['user_id'];
			$my_user_id = output_html($my_user_id);

			
			$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_user_id, $get_user_email, $get_user_name, $get_user_alias, $get_user_rank) = $row;

			if($get_user_rank == "admin" OR $get_user_rank == "moderator"){

				$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
				$result = mysqli_query($link, $query);
				while($row = mysqli_fetch_row($result)) {
					list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;


					$query_t = "SELECT relation_id, exercise_original_id, exercise_target_id, exercise_language, exercise_translated FROM $t_exercise_index_translations_relations WHERE exercise_original_id=$get_current_exercise_id AND exercise_language='$get_language_active_iso_two'";
					$result_t = mysqli_query($link, $query_t);
					$row_t = mysqli_fetch_row($result_t);
					list($get_relation_id, $get_exercise_original_id, $get_exercise_target_id, $get_exercise_language, $get_exercise_translated) = $row_t;

					if($get_relation_id == ""){
						$query_t = "SELECT relation_id, exercise_original_id, exercise_target_id, exercise_language, exercise_translated FROM $t_exercise_index_translations_relations WHERE exercise_target_id=$get_current_exercise_id AND exercise_language='$get_language_active_iso_two'";
						$result_t = mysqli_query($link, $query_t);
						$row_t = mysqli_fetch_row($result_t);
						list($get_relation_id, $get_exercise_original_id, $get_exercise_target_id, $get_exercise_language, $get_exercise_translated) = $row_t;
					}

					if($get_relation_id == ""){

						if($get_language_active_iso_two == "$get_current_exercise_language"){
							// Insert it
							$inp_exercise_language_mysql = quote_smart($link, $get_current_exercise_language);
				
							mysqli_query($link, "INSERT INTO $t_exercise_index_translations_relations
							(relation_id, exercise_original_id, exercise_language, exercise_translated) 
							VALUES 
							(NULL, '$get_current_exercise_id', $inp_exercise_language_mysql, '1')")
							or die(mysqli_error($link));
						}
						else{

							echo"
							<p class=\"smal_grey\" style=\"margin-top: 40px;\">
							<a href=\"view_exercise_translate.php?exercise_id=$exercise_id&amp;l=$l&amp;translate_to=$get_language_active_iso_two\">$l_translate_to $get_language_active_iso_two</a>
							</p>
							";
						}
					}
				}
			}
		}
		echo"
		<!-- //Translate -->
		";
	} // process != 1

	// New comment and read comments
	if($process != "1"){
		echo"
		<!-- Comments -->
			<div class=\"clear\"></div>
			<a id=\"comments\"></a>
			<!-- Feedback -->
				";
				if(isset($_GET['ft_comment']) && isset($_GET['fm_comment'])){
					$ft_comment = $_GET['ft_comment'];
					$ft_comment = output_html($ft_comment);
					$fm_comment = $_GET['fm_comment'];
					$fm_comment = output_html($fm_comment);
					$fm_comment = str_replace("_", " ", $fm_comment);
					$fm_comment = ucfirst($fm_comment);
					echo"<div class=\"$ft_comment\"><span>$fm_comment</span></div>";
				}
				echo"	
			<!-- //Feedback -->
		";
	}
	include("view_exercise_include_new_comment.php");
	include("view_exercise_include_fetch_comments.php");
	echo"
		<!-- //Comments -->
	";


	/*- Footer ---------------------------------------------------------------- */
	include("$root/_webdesign/footer.php");
} // muscle not found

?>