<?php
/**
*
* File: _admin/_inc/exercises/sqlite_equipments_levels_types.php
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
	<h1>Equipments levels types SQL Lite</h1>


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
	$sqlite_file = "../_cache/sqlite_equipments_levels_types_" . $editor_language . "_" . $datetime . ".txt";
	if(file_exists("$sqlite_file")){
		unlink("$sqlite_file");
	}
	$input = "/*- insertExercises $editor_language --------------------------------------------------------- */
private void insertEquipmentsLevelsTypes(){
	DBAdapter db = new DBAdapter(this);
	db.open();

	String q = \"\";
	Cursor cursorTemp;
";

	$fh = fopen($sqlite_file, "w+") or die("can not open file");
	fwrite($fh, $input);
	fclose($fh);

	// Synchronize Equipments
	$editor_language_mysql = quote_smart($link, $editor_language);
	$query = "SELECT count(equipment_id) FROM $t_exercise_equipments WHERE equipment_language=$editor_language_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_count_equipment_id) = $row;

	/*
	$query = "SELECT count(level_id) FROM $t_exercise_levels";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_count_level_id) = $row;

	$query = "SELECT count(type_id) FROM $t_exercise_equipments";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_count_type_id) = $row;
	*/


	
	$input = "
	// Synchronize
	q = \"SELECT _id, name, last_on_local, synchronized_week FROM synchronize WHERE name='exercise_equipments'\";
        Cursor exerciseIndexCursor = db.rawQuery(q);
        int size = exerciseIndexCursor.getCount();
        if (size == 0) {
            q = \"INSERT INTO synchronize (_id, name, last_on_local, last_on_server) VALUES (NULL, 'exercise_equipments', '$get_count_equipment_id', '$get_count_equipment_id')\";
            db.rawQuery(q);
        } else {
            q = \"UPDATE synchronize SET last_on_local='$get_count_equipment_id', last_on_server='$get_count_equipment_id' WHERE name='exercise_equipments'\";
            db.rawQuery(q);
        }
";

	$fh = fopen($sqlite_file, "a+") or die("can not open file");
	fwrite($fh, $input);
	fclose($fh);





	echo"
	<!-- File -->
		<p><a href=\"$sqlite_file\" style=\"font-size: 20px;\">$sqlite_file</a></p>
	<!-- //File -->
	";

	$query_w = "SELECT equipment_id, equipment_title, equipment_title_clean, equipment_user_id, equipment_language, equipment_muscle_group_id_main, equipment_muscle_group_id_sub, equipment_muscle_part_of_id, equipment_type_id, equipment_text, equipment_image_path, equipment_image_file, equipment_created_datetime, equipment_updated_datetime, equipment_uniqe_hits, equipment_likes, equipment_dislikes, equipment_rating, equipment_number_of_comments, equipment_reported, equipment_reported_checked, equipment_reported_reason FROM $t_exercise_equipments WHERE equipment_language=$editor_language_mysql";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_equipment_id, $get_equipment_title, $get_equipment_title_clean, $get_equipment_user_id, $get_equipment_language, $get_equipment_muscle_group_id_main, $get_equipment_muscle_group_id_sub, $get_equipment_muscle_part_of_id, $get_equipment_type_id, $get_equipment_text, $get_equipment_image_path, $get_equipment_image_file, $get_equipment_created_datetime, $get_equipment_updated_datetime, $get_equipment_uniqe_hits, $get_equipment_likes, $get_equipment_dislikes, $get_equipment_rating, $get_equipment_number_of_comments, $get_equipment_reported, $get_equipment_reported_checked, $get_equipment_reported_reason) = $row_w;
		
		// Variables
		$inp_equipment_title = str_replace("'", "&#39;", $get_equipment_title);
		$inp_equipment_title_mysql = quote_smart($link, $inp_equipment_title);

		$inp_equipment_title_clean_mysql = quote_smart($link, $get_equipment_title_clean);
		$inp_equipment_user_id_mysql = quote_smart($link, $get_equipment_user_id);
		$inp_equipment_language_mysql = quote_smart($link, $get_equipment_language);

		$inp_equipment_text = str_replace("'", "&#39;", $get_equipment_text);
		$inp_equipment_text_mysql = quote_smart($link, $inp_equipment_text);

		$inp_equipment_image_path_mysql = quote_smart($link, $get_equipment_image_path);
		$inp_equipment_image_file_mysql = quote_smart($link, $get_equipment_image_file);
		$inp_equipment_created_datetime_mysql = quote_smart($link, $get_equipment_created_datetime);
		$inp_equipment_updated_datetime_mysql = quote_smart($link, $get_equipment_updated_datetime);
		$inp_equipment_uniqe_hits_mysql = quote_smart($link, $get_equipment_uniqe_hits);
		$inp_equipment_likes_mysql = quote_smart($link, $get_equipment_likes);
		$inp_equipment_dislikes_mysql = quote_smart($link, $get_equipment_dislikes);
		$inp_equipment_rating_mysql = quote_smart($link, $get_equipment_rating);
		$inp_equipment_number_of_comments_mysql = quote_smart($link, $get_equipment_number_of_comments);
		$inp_equipment_reported_mysql = quote_smart($link, $get_equipment_reported);
		$inp_equipment_reported_checked_mysql = quote_smart($link, $get_equipment_reported_checked);
		$inp_equipment_reported_reason_mysql = quote_smart($link, $get_equipment_reported_reason);

		if($get_equipment_muscle_part_of_id == ""){
			$get_equipment_muscle_part_of_id = 0;
		}
		if($get_equipment_type_id == ""){
			$get_equipment_type_id = 0;
		}

		// Current
		$input = "
			q = \"INSERT INTO exercise_equipments (_id, equipment_id, equipment_title, \" +
                                \"equipment_title_clean, equipment_user_id, equipment_language, \" +
                                \"equipment_muscle_group_id_main, equipment_muscle_group_id_sub, equipment_muscle_part_of_id, \" +
                                \"equipment_type_id, equipment_text, equipment_image_path, \" +
                                \"equipment_image_file, equipment_created_datetime, equipment_updated_datetime, \" +
                                \"equipment_uniqe_hits, equipment_likes, equipment_dislikes, \" +
                                \"equipment_rating, equipment_number_of_comments, equipment_reported, \" +
                                \"equipment_reported_checked, equipment_reported_reason) \" +
                                \"VALUES (\"
                                + \"NULL, \"
                                + $get_equipment_id + \", \"
                                + \"$inp_equipment_title_mysql\" + \", \"
                                + \"$inp_equipment_title_clean_mysql\" + \", \"
                                + \"$inp_equipment_user_id_mysql\" + \", \"
                                + \"$editor_language_mysql\" + \", \"
                                + $get_equipment_muscle_group_id_main + \", \"
                                + $get_equipment_muscle_group_id_sub + \", \"
                                + $get_equipment_muscle_part_of_id + \", \"
                                + $get_equipment_type_id + \", \"
                                + \"$inp_equipment_text_mysql\" + \", \"
                                + \"$inp_equipment_image_path_mysql\" + \", \"
                                + \"$inp_equipment_image_file_mysql\" + \", \"
                                + \"$inp_equipment_created_datetime_mysql\" + \", \"
                                + \"$inp_equipment_updated_datetime_mysql\" + \", \"
                                + \"$inp_equipment_uniqe_hits_mysql\" + \", \"
                                + \"$inp_equipment_likes_mysql\" + \", \"
                                + \"$inp_equipment_dislikes_mysql\" + \", \"
                                + \"$inp_equipment_rating_mysql\" + \", \"
                                + \"$inp_equipment_number_of_comments_mysql\" + \", \"
                                + \"$inp_equipment_reported_mysql\" + \", \"
                                + \"$inp_equipment_reported_checked_mysql\" + \", \"
                                + \"$inp_equipment_reported_reason_mysql\" 
                                + \")\";
                        cursorTemp = db.rawQuery(q);
			cursorTemp.close();
";

		$fh = fopen($sqlite_file, "a+") or die("can not open file");
		fwrite($fh, $input);
		fclose($fh);


	} // while equipments



	// Levels
	$query_w = "SELECT $t_exercise_levels.level_id, $t_exercise_levels.level_title, $t_exercise_levels_translations.level_translation_id, $t_exercise_levels_translations.level_translation_value FROM $t_exercise_levels";
	$query_w = $query_w . " JOIN $t_exercise_levels_translations ON $t_exercise_levels.level_id=$t_exercise_levels_translations.level_id";
	$query_w = $query_w . " WHERE $t_exercise_levels_translations.level_translation_language=$editor_language_mysql";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_level_id, $get_level_title, $get_level_translation_id, $get_level_translation_value) = $row_w;
		
		// Variables

		$inp_level_title_mysql = quote_smart($link, $get_level_title);
		$inp_level_translation_value_mysql = quote_smart($link, $get_level_translation_value);

		// Current
		$input = "
			
                        q = \"INSERT INTO exercise_levels (_id, level_id, level_title) \" +
                                \"VALUES (\"
                                + \"NULL, \"
                                + $get_level_id + \", \"
                                + \"$inp_level_title_mysql\"
                                + \")\";
                        cursorTemp = db.rawQuery(q);
			cursorTemp.close();

                        q = \"INSERT INTO exercise_levels_translations (_id, level_translation_id, level_id, \" +
                                \"level_translation_language, level_translation_value) \" +
                                \"VALUES (\"
                                + \"NULL, \"
                                + $get_level_translation_id + \", \"
                                + $get_level_id + \", \"
                                + \"$editor_language_mysql\" + \", \"
                                + \"$inp_level_translation_value_mysql\"
                                + \")\";
                        db.rawQuery(q);


";

		$fh = fopen($sqlite_file, "a+") or die("can not open file");
		fwrite($fh, $input);
		fclose($fh);


	} // while levels


	
	// Types
	$query_w = "SELECT $t_exercise_types.type_id, $t_exercise_types.type_title, $t_exercise_types.type_image_path, $t_exercise_types.type_image_file, $t_exercise_types_translations.type_translation_id, $t_exercise_types_translations.type_translation_value FROM $t_exercise_types";
	$query_w = $query_w . " JOIN $t_exercise_types_translations ON $t_exercise_types.type_id=$t_exercise_types_translations.type_id";
	$query_w = $query_w . " WHERE $t_exercise_types_translations.type_translation_language=$editor_language_mysql";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_type_id, $get_type_title, $get_type_image_path, $get_type_image_file, $get_type_translation_id, $get_type_translation_value) = $row_w;
		
		// Variables

		$inp_type_title_mysql = quote_smart($link, $get_type_title);
		$inp_type_image_path_mysql = quote_smart($link, $get_type_image_path);
		$inp_type_image_file_mysql = quote_smart($link, $get_type_image_file);
		$inp_type_translation_value_mysql = quote_smart($link, $get_type_translation_value);

		// Current
		$input = "
			
                         q = \"INSERT INTO exercise_types (_id, type_id, type_title, \" +
                                \"type_image_path, type_image_file) \" +
                                \"VALUES (\"
                                + \"NULL, \"
                                + $get_type_id + \", \"
                                + \"$inp_type_title_mysql\" + \", \"
                                + \"$inp_type_image_path_mysql\" + \", \"
                                + \"$inp_type_image_file_mysql\" 
                                + \")\";

                        cursorTemp = db.rawQuery(q);
			cursorTemp.close();

                        q = \"INSERT INTO exercise_types_translations (_id, type_translation_id, type_id, \" +
                                \"type_translation_language, type_translation_value) \" +
                                \"VALUES (\"
                                + \"NULL, \"
                                + $get_type_translation_id + \", \"
                                + $get_type_id + \", \"
                                + \"$editor_language_mysql\" + \", \"
                                + \"$inp_type_translation_value_mysql\" 
                                + \")\";
                        cursorTemp = db.rawQuery(q);
			cursorTemp.close();


";

		$fh = fopen($sqlite_file, "a+") or die("can not open file");
		fwrite($fh, $input);
		fclose($fh);


	} // while levels



 	// Footer
	$editor_language_ucfirst = ucfirst($editor_language);
	$input = "		// Db close
db.close();

		// Move
        	Intent i = new Intent(this, SetupOfflineInsertDMuscles$editor_language_ucfirst.class);
       	 	startActivity(i);
       		finish();

	} // insertExercises
";

	$fh = fopen($sqlite_file, "a+") or die("can not open file");
	fwrite($fh, $input);
	fclose($fh);

?>