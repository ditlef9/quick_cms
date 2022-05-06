<?php
/**
*
* File: _admin/_inc/exercises/exercises.php
* Version 1.0
* Date 12:23 10.02.2019
* Copyright (c) 2019 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */
$t_exercise_index 				= $mysqlPrefixSav . "exercise_index";
$t_exercise_index_images			= $mysqlPrefixSav . "exercise_index_images";
$t_exercise_index_videos			= $mysqlPrefixSav . "exercise_index_videos";
$t_exercise_index_muscles			= $mysqlPrefixSav . "exercise_index_muscles";
$t_exercise_index_muscles_images		= $mysqlPrefixSav . "exercise_index_muscles_images";
$t_exercise_index_tags				= $mysqlPrefixSav . "exercise_index_tags";
$t_exercise_tags_cloud				= $mysqlPrefixSav . "exercise_tags_cloud";
$t_exercise_index_translations_relations	= $mysqlPrefixSav . "exercise_index_translations_relations";
$t_exercise_equipments 				= $mysqlPrefixSav . "exercise_equipments";
$t_exercise_types				= $mysqlPrefixSav . "exercise_types";
$t_exercise_types_translations 			= $mysqlPrefixSav . "exercise_types_translations";
$t_exercise_levels				= $mysqlPrefixSav . "exercise_levels";
$t_exercise_levels_translations 		= $mysqlPrefixSav . "exercise_levels_translations";

/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['type_id'])) {
	$type_id= $_GET['type_id'];
	$type_id = strip_tags(stripslashes($type_id));
}
else{
	$type_id = "";
}


/*- Script start --------------------------------------------------------------------- */

	echo"
	<h1>Exercises SQL Lite</h1>


	<!-- Language select -->
		<form method=\"get\" enctype=\"multipart/form-data\">
		<p><b>Language:</b><br />
		<select id=\"inp_language_select\">
			<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">$l_editor_language</option>
			<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">-</option>\n";

			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;
				$flag_path 	= "_design/gfx/flags/16x16/$get_language_active_flag" . "_16x16.png";

			
				echo"	<option value=\"index.php?open=$open&amp;page=$page&amp;action=$action&amp;editor_language=$get_language_active_iso_two&amp;l=$l\"";if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
			}
			echo"
		</select>	
		</p>
		</form>
	<!-- //Language select -->
	
	";
	// Header
	$datetime = date("ymdhis");
	$sqlite_file = "../_cache/sqlite_exercises_" . $editor_language . "_" . $datetime . ".txt";
	if(file_exists("$sqlite_file")){
		unlink("$sqlite_file");
	}
	$input = "/*- insertExercises $editor_language --------------------------------------------------------- */
private void insertExercises(){
	DBAdapter db = new DBAdapter(this);
	db.open();

	String q = \"\";
	Cursor cursorTemp;
";

	$fh = fopen($sqlite_file, "w+") or die("can not open file");
	fwrite($fh, $input);
	fclose($fh);


	// Synchronize (count exercises on server)
	$editor_language_mysql = quote_smart($link, $editor_language);
	$query = "SELECT count(exercise_id) FROM $t_exercise_index WHERE exercise_language=$editor_language_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_count_exercise_id) = $row;
	
	$input = "
	// Synchronize
	q = \"SELECT _id, name, last_on_local, synchronized_week FROM synchronize WHERE name='exercise_index'\";
        Cursor exerciseIndexCursor = db.rawQuery(q);
        int size = exerciseIndexCursor.getCount();
        if (size == 0) {
            q = \"INSERT INTO synchronize (_id, name, last_on_local, last_on_server) VALUES (NULL, 'exercise_index', '$get_count_exercise_id', '$get_count_exercise_id')\";
            db.rawQuery(q);
        } else {
            q = \"UPDATE synchronize SET last_on_local='$get_count_exercise_id', last_on_server='$get_count_exercise_id' WHERE name='exercise_index'\";
            db.rawQuery(q);
        }
";

	$fh = fopen($sqlite_file, "a+") or die("can not open file");
	fwrite($fh, $input);
	fclose($fh);

	echo"
	<!-- File -->
		<p><a href=\"$sqlite_file\" style=\"font-size: 20px;\" target=\"_blank\">$sqlite_file</a></p>
	<!-- //File -->
	";

	$query_w = "SELECT exercise_id, exercise_title, exercise_title_clean, exercise_title_alternative, exercise_user_id, exercise_language, exercise_muscle_group_id_main, exercise_muscle_group_id_sub, exercise_muscle_part_of_id, exercise_equipment_id, exercise_type_id, exercise_level_id, exercise_preparation, exercise_guide, exercise_important, exercise_created_datetime, exercise_updated_datetime, exercise_user_ip, exercise_uniqe_hits, exercise_uniqe_hits_ip_block, exercise_likes, exercise_dislikes, exercise_rating, exercise_rating_ip_block, exercise_number_of_comments, exercise_reported, exercise_reported_checked, exercise_reported_reason, exercise_last_viewed FROM $t_exercise_index WHERE exercise_language=$editor_language_mysql";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_exercise_id, $get_exercise_title, $get_exercise_title_clean, $get_exercise_title_alternative, $get_exercise_user_id, $get_exercise_language, $get_exercise_muscle_group_id_main, $get_exercise_muscle_group_id_sub, $get_exercise_muscle_part_of_id, $get_exercise_equipment_id, $get_exercise_type_id, $get_exercise_level_id, $get_exercise_preparation, $get_exercise_guide, $get_exercise_important, $get_exercise_created_datetime, $get_exercise_updated_datetime, $get_exercise_user_ip, $get_exercise_uniqe_hits, $get_exercise_uniqe_hits_ip_block, $get_exercise_likes, $get_exercise_dislikes, $get_exercise_rating, $get_exercise_rating_ip_block, $get_exercise_number_of_comments, $get_exercise_reported, $get_exercise_reported_checked, $get_exercise_reported_reason, $get_exercise_last_viewed) = $row_w;
		
		// Variables
		$inp_exercise_id_mysql = quote_smart($link, $get_exercise_id);
		$inp_exercise_title_mysql = quote_smart($link, $get_exercise_title);
		$inp_exercise_title_clean_mysql = quote_smart($link, $get_exercise_title_clean);
		$inp_exercise_title_alternative_mysql = quote_smart($link, $get_exercise_title_alternative);
		$inp_exercise_user_id_mysql = quote_smart($link, $get_exercise_user_id);
		$inp_exercise_language_mysql = quote_smart($link, $get_exercise_language);
		$inp_exercise_muscle_group_id_main_mysql = quote_smart($link, $get_exercise_muscle_group_id_main);
		$inp_exercise_muscle_group_id_sub_mysql = quote_smart($link, $get_exercise_muscle_group_id_sub);
		$inp_exercise_muscle_part_of_id_mysql = quote_smart($link, $get_exercise_muscle_part_of_id);
		$inp_exercise_equipment_id_mysql = quote_smart($link, $get_exercise_equipment_id);
		$inp_exercise_type_id_mysql = quote_smart($link, $get_exercise_type_id);
		$inp_exercise_level_id_mysql = quote_smart($link, $get_exercise_level_id);

		$inp_exercise_preparation = str_replace("'", "&#39;", $get_exercise_preparation);
		$inp_exercise_preparation_mysql = quote_smart($link, $inp_exercise_preparation);

		$inp_exercise_guide = str_replace("'", "&#39;", $get_exercise_guide);
		$inp_exercise_guide_mysql = quote_smart($link, $inp_exercise_guide);

		$inp_exercise_important= str_replace("'", "&#39;", $get_exercise_important);
		$inp_exercise_important_mysql = quote_smart($link, $inp_exercise_important);

		$inp_exercise_created_datetime_mysql = quote_smart($link, $get_exercise_created_datetime);
		$inp_exercise_updated_datetime_mysql = quote_smart($link, $get_exercise_updated_datetime);
		$inp_exercise_user_ip_mysql = quote_smart($link, $get_exercise_user_ip);
		$inp_exercise_uniqe_hits_mysql = quote_smart($link, $get_exercise_uniqe_hits);
		$inp_exercise_uniqe_hits_ip_block_mysql = quote_smart($link, $get_exercise_uniqe_hits_ip_block);
		$inp_exercise_likes_mysql = quote_smart($link, $get_exercise_likes);
		$inp_exercise_dislikes_mysql = quote_smart($link, $get_exercise_dislikes);
		$inp_exercise_rating_mysql = quote_smart($link, $get_exercise_rating);
		$inp_exercise_rating_ip_block_mysql = quote_smart($link, $get_exercise_rating_ip_block);
		$inp_exercise_number_of_comments_mysql = quote_smart($link, $get_exercise_number_of_comments);
		$inp_exercise_reported_mysql = quote_smart($link, $get_exercise_reported);
		$inp_exercise_reported_checked_mysql = quote_smart($link, $get_exercise_reported_checked);
		$inp_exercise_reported_reason_mysql = quote_smart($link, $get_exercise_reported_reason);
		$inp_exercise_last_viewed_mysql = quote_smart($link, $get_exercise_last_viewed);

		// Current
		$input = "/*- $get_exercise_title ------------------------------------------------------*/
				  q = \"INSERT INTO exercise_index (_id, exercise_id, exercise_title, \" +
                                \"exercise_title_clean, exercise_title_alternative, exercise_user_id, exercise_language, \" +
                                \"exercise_muscle_group_id_main, exercise_muscle_group_id_sub, exercise_muscle_part_of_id, \" +
                                \"exercise_equipment_id, exercise_type_id, exercise_level_id, \" +
                                \"exercise_preparation, exercise_guide, exercise_important, \" +
                                \"exercise_created_datetime, exercise_updated_datetime, exercise_user_ip, \" +
                                \"exercise_uniqe_hits, exercise_uniqe_hits_ip_block, exercise_likes, \" +
                                \"exercise_dislikes, exercise_rating, exercise_rating_ip_block, \" +
                                \"exercise_number_of_comments, exercise_reported, exercise_reported_checked, \" +
                                \"exercise_reported_reason, exercise_last_viewed) \" +
                                \"VALUES (\"
                                + \"NULL, \"
                                + \"$inp_exercise_id_mysql\" + \", \"
                                + \"$inp_exercise_title_mysql\" + \", \"
                                + \"$inp_exercise_title_clean_mysql\" + \", \" 
                                + \"$inp_exercise_title_alternative_mysql\" + \", \" 
                                + \"$inp_exercise_user_id_mysql\" + \", \"
                                + \"$inp_exercise_language_mysql\" + \", \"
                                + \"$inp_exercise_muscle_group_id_main_mysql\" + \", \"
                                + \"$inp_exercise_muscle_group_id_sub_mysql\" + \", \"
                                + \"$inp_exercise_muscle_part_of_id_mysql\" + \", \"
                                + \"$inp_exercise_equipment_id_mysql\" + \", \"
                                + \"$inp_exercise_type_id_mysql\" + \", \"
                                + \"$inp_exercise_level_id_mysql\" + \", \"
                                + \"$inp_exercise_preparation_mysql\" + \", \"
                                + \"$inp_exercise_guide_mysql\" + \", \"
                                + \"$inp_exercise_important_mysql\" + \", \"
                                + \"$inp_exercise_created_datetime_mysql\" + \", \"
                                + \"$inp_exercise_updated_datetime_mysql\" + \", \"
                                + \"''\" + \", \"
                                + \"$inp_exercise_uniqe_hits_mysql\" + \", \"
                                + \"''\" + \", \"
                                + \"$inp_exercise_likes_mysql\" + \", \"
                                + \"$inp_exercise_dislikes_mysql\" + \", \"
                                + \"$inp_exercise_rating_mysql\" + \", \"
                                + \"$inp_exercise_rating_ip_block_mysql\" + \", \"
                                + \"$inp_exercise_number_of_comments_mysql\" + \", \"
                                + \"$inp_exercise_reported_mysql\" + \", \"
                                + \"$inp_exercise_reported_checked_mysql\" + \", \"
                                + \"$inp_exercise_reported_reason_mysql\" + \", \"
                                + \"$inp_exercise_last_viewed_mysql\" 
                                + \")\";
                        cursorTemp = db.rawQuery(q);
			cursorTemp.close();
";

		$fh = fopen($sqlite_file, "a+") or die("can not open file");
		fwrite($fh, $input);
		fclose($fh);

		

		// exercise_index_images
		$query_t = "SELECT exercise_image_id, exercise_image_user_id, exercise_image_exercise_id, exercise_image_datetime, exercise_image_user_ip, exercise_image_type, exercise_image_path, exercise_image_file, exercise_image_thumb_small, exercise_image_thumb_medium, exercise_image_thumb_large, exercise_image_uniqe_hits, exercise_image_uniqe_hits_ip_block FROM $t_exercise_index_images WHERE exercise_image_exercise_id=$get_exercise_id";
		$result_t = mysqli_query($link, $query_t);
		while($row_t = mysqli_fetch_row($result_t)) {
			list($get_exercise_image_id, $get_exercise_image_user_id, $get_exercise_image_exercise_id, $get_exercise_image_datetime, $get_exercise_image_user_ip, $get_exercise_image_type, $get_exercise_image_path, $get_exercise_image_file, $get_exercise_image_thumb_small, $get_exercise_image_thumb_medium, $get_exercise_image_thumb_large, $get_exercise_image_uniqe_hits, $get_exercise_image_uniqe_hits_ip_block) = $row_t;


			$inp_exercise_image_id_mysql = quote_smart($link, $get_exercise_image_id);
			$inp_exercise_image_user_id_mysql = quote_smart($link, $get_exercise_image_user_id);
			$inp_exercise_image_exercise_id_mysql = quote_smart($link, $get_exercise_image_exercise_id);
			$inp_exercise_image_datetime_mysql = quote_smart($link, $get_exercise_image_datetime);
			$inp_exercise_image_user_ip_mysql = quote_smart($link, $get_exercise_image_user_ip);
			$inp_exercise_image_type_mysql = quote_smart($link, $get_exercise_image_type);
			$inp_exercise_image_path_mysql = quote_smart($link, $get_exercise_image_path);
			$inp_exercise_image_file_mysql = quote_smart($link, $get_exercise_image_file);
			$inp_exercise_image_thumb_small_mysql = quote_smart($link, $get_exercise_image_thumb_small);
			$inp_exercise_image_thumb_medium_mysql = quote_smart($link, $get_exercise_image_thumb_medium);
			$inp_exercise_image_thumb_large_mysql = quote_smart($link, $get_exercise_image_thumb_large);
			$inp_exercise_image_uniqe_hits_mysql = quote_smart($link, $get_exercise_image_uniqe_hits);
			$inp_exercise_image_uniqe_hits_ip_block_mysql = quote_smart($link, $get_exercise_image_uniqe_hits_ip_block);

			$input = "
				 q = \"INSERT INTO exercise_index_images (_id, exercise_image_id, exercise_image_user_id, \" +
                                        \"exercise_image_exercise_id, exercise_image_datetime, exercise_image_type, \" +
                                        \"exercise_image_path, exercise_image_file, exercise_image_thumb_small, \" +
                                        \"exercise_image_thumb_medium, exercise_image_thumb_large, exercise_image_uniqe_hits) \" +
                                        \"VALUES (\"
                                        + \"NULL, \"
                                        + \"$inp_exercise_image_id_mysql\" + \", \"
                                        + \"$inp_exercise_image_user_id_mysql\" + \", \"
                                        + \"$inp_exercise_image_exercise_id_mysql\" + \", \"
                                        + \"$inp_exercise_image_datetime_mysql\" + \", \"
                                        + \"$inp_exercise_image_type_mysql\" + \", \"
                                        + \"$inp_exercise_image_path_mysql\" + \", \"
                                        + \"$inp_exercise_image_file_mysql\" + \", \"
                                        + \"$inp_exercise_image_thumb_small_mysql\" + \", \"
                                        + \"$inp_exercise_image_thumb_medium_mysql\" + \", \"
                                        + \"$inp_exercise_image_thumb_large_mysql\" + \", \"
                                        + \"$inp_exercise_image_uniqe_hits_mysql\" 
                                        + \")\";
                        cursorTemp = db.rawQuery(q);
			cursorTemp.close();

";

			$fh = fopen($sqlite_file, "a+") or die("can not open file");
			fwrite($fh, $input);
			fclose($fh);

			
		} // while exercise_index_images


		// exercise_index_videos
		$query_t = "SELECT exercise_video_id, exercise_video_user_id, exercise_video_exercise_id, exercise_video_datetime, exercise_video_user_ip, exercise_video_service_name, exercise_video_service_id, exercise_video_path, exercise_video_file, exercise_video_uniqe_hits, exercise_video_uniqe_hits_ip_block FROM $t_exercise_index_videos WHERE exercise_video_exercise_id=$get_exercise_id";
		$result_t = mysqli_query($link, $query_t);
		while($row_t = mysqli_fetch_row($result_t)) {
			list($get_exercise_video_id, $get_exercise_video_user_id, $get_exercise_video_exercise_id, $get_exercise_video_datetime, $get_exercise_video_user_ip, $get_exercise_video_service_name, $get_exercise_video_service_id, $get_exercise_video_path, $get_exercise_video_file, $get_exercise_video_uniqe_hits, $get_exercise_video_uniqe_hits_ip_block) = $row_t;



			$inp_exercise_video_id_mysql = quote_smart($link, $get_exercise_video_id);
			$inp_exercise_video_user_id_mysql = quote_smart($link, $get_exercise_video_user_id);
			$inp_exercise_video_exercise_id_mysql = quote_smart($link, $get_exercise_video_exercise_id);
			$inp_exercise_video_datetime_mysql = quote_smart($link, $get_exercise_video_datetime);
			$inp_exercise_video_user_ip_mysql = quote_smart($link, $get_exercise_video_user_ip);
			$inp_exercise_video_service_name_mysql = quote_smart($link, $get_exercise_video_service_name);
			$inp_exercise_video_service_id_mysql = quote_smart($link, $get_exercise_video_service_id);
			$inp_exercise_video_path_mysql = quote_smart($link, $get_exercise_video_path);
			$inp_exercise_video_file_mysql = quote_smart($link, $get_exercise_video_file);
			$inp_exercise_video_uniqe_hits_mysql = quote_smart($link, $get_exercise_video_uniqe_hits);
			$inp_exercise_video_uniqe_hits_ip_block_mysql = quote_smart($link, $get_exercise_video_uniqe_hits_ip_block);

			$input = "
				 q = \"INSERT INTO exercise_index_videos (_id, exercise_video_id, exercise_video_user_id, \" +
                                        \"exercise_video_exercise_id, exercise_video_datetime, exercise_video_service_name, \" +
                                        \"exercise_video_service_id, exercise_video_path, exercise_video_file, \" +
                                        \"exercise_video_uniqe_hits ) \" +
                                        \"VALUES (\"
                                        + \"NULL, \"
                                        + \"$inp_exercise_video_id_mysql\" + \", \"
                                        + \"$inp_exercise_video_user_id_mysql\" + \", \"
                                        + \"$inp_exercise_video_exercise_id_mysql\" + \", \"
                                        + \"$inp_exercise_video_datetime_mysql\" + \", \"
                                        + \"$inp_exercise_video_service_name_mysql\" + \", \"
                                        + \"$inp_exercise_video_service_id_mysql\" + \", \"
                                        + \"$inp_exercise_video_path_mysql\" + \", \"
                                        + \"$inp_exercise_video_file_mysql\" + \", \"
                                        + \"$inp_exercise_video_uniqe_hits_mysql\" 
                                        + \")\";
                        cursorTemp = db.rawQuery(q);
			cursorTemp.close();

";

			$fh = fopen($sqlite_file, "a+") or die("can not open file");
			fwrite($fh, $input);
			fclose($fh);

			
		} // while exercise_index_videos


		// exercise_index_muscles
		$query_t = "SELECT exercise_muscle_id, exercise_muscle_exercise_id, exercise_muscle_muscle_id, exercise_muscle_type FROM $t_exercise_index_muscles WHERE exercise_muscle_exercise_id=$get_exercise_id";
		$result_t = mysqli_query($link, $query_t);
		while($row_t = mysqli_fetch_row($result_t)) {
			list($get_exercise_muscle_id, $get_exercise_muscle_exercise_id, $get_exercise_muscle_muscle_id, $get_exercise_muscle_type) = $row_t;


			$inp_exercise_muscle_type_mysql = quote_smart($link, $get_exercise_muscle_type);

			$input = "
                                q = \"INSERT INTO exercise_index_muscles (_id, exercise_muscle_id, exercise_muscle_exercise_id, \" +
                                        \"exercise_muscle_muscle_id, exercise_muscle_type) \" +
                                        \"VALUES (\"
                                        + \"NULL, \"
                                        + $get_exercise_muscle_id + \", \"
                                        + $get_exercise_muscle_exercise_id + \", \"
                                        + $get_exercise_muscle_muscle_id + \", \"
                                        + \"$inp_exercise_muscle_type_mysql\" 
                                        + \")\";
                        cursorTemp = db.rawQuery(q);
			cursorTemp.close();

";

			$fh = fopen($sqlite_file, "a+") or die("can not open file");
			fwrite($fh, $input);
			fclose($fh);
		} // while exercise_index_muscles


		// exercise_index_muscles_images
		$query_t = "SELECT exercise_muscle_image_id, exercise_muscle_image_exercise_id, exercise_muscle_image_file FROM $t_exercise_index_muscles_images WHERE exercise_muscle_image_exercise_id=$get_exercise_id";
		$result_t = mysqli_query($link, $query_t);
		while($row_t = mysqli_fetch_row($result_t)) {
			list($get_exercise_muscle_image_id, $get_exercise_muscle_image_exercise_id, $get_exercise_muscle_image_file) = $row_t;



			$inp_exercise_muscle_image_file_mysql = quote_smart($link, $get_exercise_muscle_image_file);

			$input = "
                                    q = \"INSERT INTO exercise_index_muscles_images (_id, exercise_muscle_image_id, exercise_muscle_image_exercise_id, \" +
                                            \"exercise_muscle_image_file) \" +
                                            \"VALUES (\"
                                            + \"NULL, \"
                                            + $get_exercise_muscle_image_id + \", \"
                                            + $get_exercise_muscle_image_exercise_id + \", \"
                                            + \"$inp_exercise_muscle_image_file_mysql\"
                                            + \")\";
                        cursorTemp = db.rawQuery(q);
			cursorTemp.close();

";

			$fh = fopen($sqlite_file, "a+") or die("can not open file");
			fwrite($fh, $input);
			fclose($fh);
		} // while exercise_index_muscles_images



	} // while



	

 	// Footer
	$editor_language_ucfirst = ucfirst($editor_language);
	$input = "		// Db close
db.close();

		// Move
        	Intent i = new Intent(this, SetupOfflineInsertCExerciseEquipmentsTypesLevels$editor_language_ucfirst.class);
       	 	startActivity(i);
       		finish();

	} // insertExercises
";

	$fh = fopen($sqlite_file, "a+") or die("can not open file");
	fwrite($fh, $input);
	fclose($fh);

?>