<?php 
/**
*
* File: exercise/new_exercise_step_12_finish.php
* Version 1.0.0
* Date 09:38 10.04.2021
* Copyright (c) 2021 S. A. Ditlefsen
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
	list($get_current_exercise_id, $get_current_exercise_title, $get_current_exercise_user_id, $get_current_exercise_language, $get_current_exercise_muscle_group_id_main, $get_current_exercise_muscle_group_id_sub, $get_current_exercise_muscle_part_of_id, $get_current_exercise_equipment_id, $get_current_exercise_type_id, $get_current_exercise_level_id, $get_current_exercise_preparation, $get_current_exercise_guide, $get_current_exercise_important, $get_current_exercise_created_datetime, $get_current_exercise_updated_datetime, $get_current_exercise_user_ip, $get_current_exercise_uniqe_hits, $get_current_exercise_uniqe_hits_ip_block, $get_current_exercise_likes, $get_current_exercise_dislikes, $get_current_exercise_rating, $get_current_exercise_rating_ip_block, $get_current_exercise_number_of_comments, $get_current_exercise_reported, $get_current_exercise_reported_checked, $get_current_exercise_reported_reason) = $row;
	

	

	if($get_current_exercise_id == ""){
		echo"<p>Exercise not found.</p>";
	}
	else{

		if($process == "1"){

			
			// Search engine
			$inp_index_title = "$get_current_exercise_title"; 
			$inp_index_title_mysql = quote_smart($link, $inp_index_title);

			$inp_index_url = "exercises/view_exercise.php?exercise_id=$get_current_exercise_id&amp;muscle_group_id_main=$get_current_exercise_muscle_group_id_main";
			$inp_index_url_mysql = quote_smart($link, $inp_index_url);
	
			$inp_index_short_description = substr($get_current_exercise_preparation, 0, 200);
			$inp_index_short_description = str_replace("&lt;p&gt;", "", $inp_index_short_description);
			$inp_index_short_description = str_replace("&lt;/p&gt;", "", $inp_index_short_description);

			$inp_index_short_description_mysql = quote_smart($link, $inp_index_short_description);

			// tags
			$inp_index_keywords = "";
			$query_r = "SELECT tag_id, tag_text, tag_clean FROM $t_exercise_index_tags WHERE tag_exercise_id=$get_current_exercise_id";
			$result_r = mysqli_query($link, $query_r);
			while($row_r = mysqli_fetch_row($result_r)) {
				list($get_tag_id, $get_tag_text, $get_tag_clean) = $row_r;
				if($inp_index_keywords == ""){
					$inp_index_keywords  = "$get_tag_text";
				}
				else{
					$inp_index_keywords  = $inp_index_keywords . ", $get_tag_text";
				}
			}
			$inp_index_keywords_mysql = quote_smart($link, $inp_index_keywords);

			// Image
			$query = "SELECT exercise_image_id, exercise_image_path, exercise_image_file, exercise_image_thumb_120x120, exercise_image_thumb_150x150, exercise_image_thumb_350x350 FROM $t_exercise_index_images WHERE exercise_image_exercise_id=$get_current_exercise_id AND exercise_image_type='guide_1'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_exercise_image_id, $get_current_exercise_image_path, $get_current_exercise_image_file, $get_current_exercise_image_thumb_120x120, $get_current_exercise_image_thumb_150x150, $get_current_exercise_image_thumb_350x350) = $row;

			$inp_index_image_path_mysql = quote_smart($link, $get_current_exercise_image_path);
			$inp_index_image_file_mysql = quote_smart($link, $get_current_exercise_image_file);
	
			// Thumb
			$thumb = "";
			if($get_current_exercise_image_file != ""){
				$ext = get_extension($get_current_exercise_image_file);
				$thumb = str_replace(".$ext", "", $get_current_exercise_image_file);
				$thumb = $thumb . "_235x132." . $ext;
			}
			$inp_index_image_thumb_mysql = quote_smart($link, $thumb);



			$inp_index_module_name_mysql = quote_smart($link, "exercises");

			$inp_index_module_part_name_mysql = quote_smart($link, "exercise");

			$inp_index_reference_name_mysql = quote_smart($link, "exercise_id");
			$inp_index_reference_id_mysql = quote_smart($link, "$get_current_exercise_id");

			$inp_index_has_access_control_mysql = quote_smart($link, 0);
	
			$inp_index_is_ad_mysql = quote_smart($link, 0);
		
			$inp_index_language_mysql = quote_smart($link, "$get_current_exercise_language");

			// Check if exists
			$query_exists = "SELECT index_id FROM $t_search_engine_index WHERE index_module_name=$inp_index_module_name_mysql AND index_reference_name=$inp_index_reference_name_mysql AND index_reference_id=$inp_index_reference_id_mysql";
			$result_exists = mysqli_query($link, $query_exists);
			$row_exists = mysqli_fetch_row($result_exists);
			list($get_index_id) = $row_exists;
			if($get_index_id == ""){
				// Insert
				// echo"<span>Insert $inp_index_title<br /></span>\n";
				mysqli_query($link, "INSERT INTO $t_search_engine_index 
				(index_id, index_title, index_url, index_short_description, index_keywords, 
				index_image_path, index_image_file, index_image_thumb_235x132, 
				index_module_name, index_module_part_name, index_module_part_id, index_reference_name, index_reference_id, 
				index_has_access_control, index_is_ad, index_created_datetime, index_created_datetime_print, index_language, 
				index_unique_hits) 
				VALUES 
				(NULL, $inp_index_title_mysql, $inp_index_url_mysql, $inp_index_short_description_mysql, $inp_index_keywords_mysql, 
				$inp_index_image_path_mysql, $inp_index_image_file_mysql, $inp_index_image_thumb_mysql, 
				$inp_index_module_name_mysql, $inp_index_module_part_name_mysql, '0', $inp_index_reference_name_mysql, $inp_index_reference_id_mysql,
				'0', $inp_index_is_ad_mysql, '$datetime', '$datetime_saying', $inp_index_language_mysql,
				0)")
				or die(mysqli_error($link));
			}






			// Feed
			$inp_feed_title_mysql = quote_smart($link, $get_current_exercise_title);
			$inp_feed_text_mysql = quote_smart($link, "");
			$inp_feed_image_path_mysql = quote_smart($link, $get_current_exercise_image_path);
			$inp_feed_image_file_mysql = quote_smart($link, $get_current_exercise_image_file);

			// Feed Thumb 300x169
			$ext = get_extension($get_current_exercise_image_file);
			$img_name = str_replace(".$ext", "", $get_current_exercise_image_file);
			$inp_feed_image_thumb_a = $img_name . "_thumb_300x169." . $ext;
			$inp_feed_image_thumb_a_mysql = quote_smart($link, $inp_feed_image_thumb_a);

			// Feed Thumb 540x304
			$inp_feed_image_thumb_b = $img_name . "_thumb_540x304." . $ext;
			$inp_feed_image_thumb_b_mysql = quote_smart($link, $inp_feed_image_thumb_b);

			$inp_feed_link_url = "exercises/view_exercise.php?exercise_id=$get_current_exercise_id&amp;muscle_group_id_main=$get_current_exercise_muscle_group_id_main&amp;l=$l";
			$inp_feed_link_url_mysql = quote_smart($link, $inp_feed_link_url);

			$inp_feed_link_name_mysql = quote_smart($link, "$l_view");
 
			// Feed category name (exercise type)
			$query = "SELECT type_translation_id, type_translation_value FROM $t_exercise_types_translations WHERE type_id=$get_current_exercise_type_id AND type_translation_language=$l_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_type_translation_id, $get_type_translation_value) = $row;
			$inp_feed_category_name_mysql = quote_smart($link, $get_type_translation_value);

			// Feed user
			$my_user_id = $_SESSION['user_id'];
			$my_user_id = output_html($my_user_id);
			$my_user_id_mysql = quote_smart($link, $my_user_id);

			// Feed Get current user
			$query = "SELECT user_id, user_email, user_name, user_alias, user_rank FROM $t_users WHERE user_id=$my_user_id_mysql";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_user_id, $get_my_user_email, $get_my_user_name, $get_my_user_alias, $get_my_user_rank) = $row;

			// Feed Author image
			$query = "SELECT photo_id, photo_destination, photo_thumb_40, photo_thumb_50, photo_thumb_60, photo_thumb_200 FROM $t_users_profile_photo WHERE photo_user_id='$get_my_user_id' AND photo_profile_image='1'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_my_photo_id, $get_my_photo_destination, $get_my_photo_thumb_40, $get_my_photo_thumb_50, $get_my_photo_thumb_60, $get_my_photo_thumb_200) = $row;


			$inp_feed_user_email_mysql = quote_smart($link, $get_my_user_email);
			$inp_feed_user_name_mysql = quote_smart($link, $get_my_user_name);
			$inp_feed_user_alias_mysql = quote_smart($link, $get_my_user_alias);
			$inp_feed_user_photo_file_mysql = quote_smart($link, $get_my_photo_destination);
			$inp_feed_user_photo_thumb_40_mysql = quote_smart($link, $get_my_photo_thumb_40);
			$inp_feed_user_photo_thumb_50_mysql = quote_smart($link, $get_my_photo_thumb_50);
			$inp_feed_user_photo_thumb_60_mysql = quote_smart($link, $get_my_photo_thumb_60);
			$inp_feed_user_photo_thumb_200_mysql = quote_smart($link, $get_my_photo_thumb_200);


			// Feed My IP
			$inp_my_ip = $_SERVER['REMOTE_ADDR'];
			$inp_my_ip = output_html($inp_my_ip);
			$inp_my_ip_mysql = quote_smart($link, $inp_my_ip);

			// Feed My hostname
			$inp_my_hostname = "$my_ip";
			if($configSiteUseGethostbyaddrSav == "1"){
				$inp_my_hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']); // Some servers in local network cant use getostbyaddr because of nameserver missing
			}
			$inp_my_hostname = output_html($inp_my_hostname);
			$inp_my_hostname_mysql = quote_smart($link, $inp_my_hostname);
					
			// Feed Lang
			$inp_feed_language = output_html($l);
			$inp_feed_language_mysql = quote_smart($link, $inp_feed_language);


			// Feed Subscribe
			$query = "SELECT es_id, es_user_id, es_type, es_on_off FROM $t_users_email_subscriptions WHERE es_user_id='$get_my_user_id' AND es_type='users_feed'";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_es_id, $get_es_user_id, $get_es_type, $get_es_on_off) = $row;
			if($get_es_id == ""){
				// Dont know
				mysqli_query($link, "INSERT INTO $t_users_email_subscriptions 
				(es_id, es_user_id, es_type, es_on_off) 
				VALUES 
				(NULL, $get_my_user_id, 'users_feed', 0)") or die(mysqli_error($link));
				$get_es_on_off = 0;
			}

			// Feed dates
			$year = date("Y");
			$date_saying = date("j M Y");


			$query = "SELECT feed_id FROM $t_users_feeds_index WHERE feed_module_name='exercises' AND feed_module_part_name='exercise' AND feed_module_part_id=$get_current_exercise_id AND feed_user_id=$get_my_user_id";
			$result = mysqli_query($link, $query);
			$row = mysqli_fetch_row($result);
			list($get_current_feed_id) = $row;
			if($get_current_feed_id == ""){
				// Insert feed
				mysqli_query($link, "INSERT INTO $t_users_feeds_index
				(feed_id, feed_title, feed_text, feed_image_path, feed_image_file, 
				feed_image_thumb_300x169, feed_image_thumb_540x304, feed_link_url, feed_link_name, feed_module_name, 
				feed_module_part_name, feed_module_part_id, feed_main_category_id, feed_main_category_name, 
				feed_user_id, feed_user_email, feed_user_name, feed_user_alias, 
				feed_user_photo_file, feed_user_photo_thumb_40, feed_user_photo_thumb_50, feed_user_photo_thumb_60, feed_user_photo_thumb_200, 
				feed_user_subscribe, feed_user_ip, feed_user_hostname, feed_language, feed_created_datetime, 
				feed_created_year, feed_created_time, feed_created_date_saying, feed_likes, feed_dislikes, feed_comments) 
				VALUES 
				(NULL, $inp_feed_title_mysql, $inp_feed_text_mysql, $inp_feed_image_path_mysql, $inp_feed_image_file_mysql, 
				$inp_feed_image_thumb_a_mysql, $inp_feed_image_thumb_b_mysql, $inp_feed_link_url_mysql, $inp_feed_link_name_mysql, 'exercises', 
				'exercise', $get_current_exercise_id, $get_current_exercise_type_id, $inp_feed_category_name_mysql, 
				$get_my_user_id, $inp_feed_user_email_mysql, $inp_feed_user_name_mysql, $inp_feed_user_alias_mysql, 
				$inp_feed_user_photo_file_mysql, $inp_feed_user_photo_thumb_40_mysql, $inp_feed_user_photo_thumb_50_mysql, $inp_feed_user_photo_thumb_60_mysql, $inp_feed_user_photo_thumb_200_mysql, 
				$get_es_on_off, $inp_my_ip_mysql, $inp_my_hostname_mysql, $inp_feed_language_mysql, '$datetime',
				'$year', '$time', '$date_saying', 0, 0, 0)")
				or die(mysqli_error($link));
						
			} // Create feed
			else{
				// Update feed
				mysqli_query($link, "UPDATE $t_users_feeds_index SET
						feed_title=$inp_feed_title_mysql, 
						feed_text=$inp_feed_text_mysql, 
						feed_image_path=$inp_feed_image_path_mysql, 
						feed_image_file=$inp_feed_image_file_mysql, 
						feed_image_thumb_300x169=$inp_feed_image_thumb_a_mysql, 
						feed_image_thumb_540x304=$inp_feed_image_thumb_b_mysql, 
						feed_modified_datetime='$datetime'
						WHERE feed_id=$get_current_feed_id")
						or die(mysqli_error($link));
			} // Update feed

			// Header
			$url = "view_exercise.php?exercise_id=$get_current_exercise_id&muscle_group_id_main=$get_current_exercise_muscle_group_id_main&l=$l";
			header("Location: $url");
			exit;

		} // process
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