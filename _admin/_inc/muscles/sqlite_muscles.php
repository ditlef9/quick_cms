<?php
/**
*
* File: _admin/_inc/muscles/sqlite_muslces.php
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
$t_muscles				= $mysqlPrefixSav . "muscles";
$t_muscles_translations 		= $mysqlPrefixSav . "muscles_translations";
$t_muscle_groups 			= $mysqlPrefixSav . "muscle_groups";
$t_muscle_groups_translations	 	= $mysqlPrefixSav . "muscle_groups_translations";
$t_muscle_part_of 			= $mysqlPrefixSav . "muscle_part_of";
$t_muscle_part_of_translations	 	= $mysqlPrefixSav . "muscle_part_of_translations";


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
	<h1>Muscles SQL Lite</h1>


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
	$sqlite_file = "../_cache/sqlite_muscles_" . $editor_language . "_" . $datetime . ".txt";
	if(file_exists("$sqlite_file")){
		unlink("$sqlite_file");
	}
	$input = "/*- insertMuscles $editor_language --------------------------------------------------------- */
private void insertMuscles(){
	DBAdapter db = new DBAdapter(this);
	db.open();

	String q = \"\";
	Cursor cursorTemp;
";

	$fh = fopen($sqlite_file, "w+") or die("can not open file");
	fwrite($fh, $input);
	fclose($fh);


	// Synchronize Muscles
	$editor_language_mysql = quote_smart($link, $editor_language);
	$query = "SELECT count(muscle_id) FROM $t_muscles";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_count_muscle_id) = $row;
	
	$input = "
	// Synchronize
	q = \"SELECT _id, name, last_on_local, synchronized_week FROM synchronize WHERE name='muscles'\";
        Cursor exerciseIndexCursor = db.rawQuery(q);
        int size = exerciseIndexCursor.getCount();
        if (size == 0) {
            q = \"INSERT INTO synchronize (_id, name, last_on_local, last_on_server) VALUES (NULL, 'muscles', '$get_count_muscle_id', '$get_count_muscle_id')\";
            db.rawQuery(q);
        } else {
            q = \"UPDATE synchronize SET last_on_local='$get_count_muscle_id', last_on_server='$get_count_muscle_id' WHERE name='muscles'\";
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

	$query_w = "SELECT muscle_id, muscle_user_id, muscle_latin_name, muscle_latin_name_clean, muscle_simple_name, muscle_short_name, muscle_group_id_main, muscle_group_id_sub, muscle_part_of_id, muscle_text, muscle_image_path, muscle_image_file, muscle_video_path, muscle_video_file, muscle_video_embedded, muscle_unique_hits, muscle_unique_hits_ip_block FROM $t_muscles";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_muscle_id, $get_muscle_user_id, $get_muscle_latin_name, $get_muscle_latin_name_clean, $get_muscle_simple_name, $get_muscle_short_name, $get_muscle_group_id_main, $get_muscle_group_id_sub, $get_muscle_part_of_id, $get_muscle_text, $get_muscle_image_path, $get_muscle_image_file, $get_muscle_video_path, $get_muscle_video_file, $get_muscle_video_embedded, $get_muscle_unique_hits, $get_muscle_unique_hits_ip_block) = $row_w;
		
		// Variables
		$inp_muscle_id_mysql = quote_smart($link, $get_muscle_id);
		$inp_muscle_user_id_mysql = quote_smart($link, $get_muscle_user_id);
		$inp_muscle_latin_name_mysql = quote_smart($link, $get_muscle_latin_name);
		$inp_muscle_latin_name_clean_mysql = quote_smart($link, $get_muscle_latin_name_clean);
		$inp_muscle_simple_name_mysql = quote_smart($link, $get_muscle_simple_name);
		$inp_muscle_short_name_mysql = quote_smart($link, $get_muscle_short_name);
		$inp_muscle_group_id_main_mysql = quote_smart($link, $get_muscle_group_id_main);
		$inp_muscle_group_id_sub_mysql = quote_smart($link, $get_muscle_group_id_sub);
		$inp_muscle_part_of_id_mysql = quote_smart($link, $get_muscle_part_of_id);
		$inp_muscle_text_mysql = quote_smart($link, $get_muscle_text);
		$inp_muscle_image_path_mysql = quote_smart($link, $get_muscle_image_path);
		$inp_muscle_image_file_mysql = quote_smart($link, $get_muscle_image_file);
		$inp_muscle_video_path_mysql = quote_smart($link, $get_muscle_video_path);
		$inp_muscle_video_file_mysql = quote_smart($link, $get_muscle_video_file);
		$inp_muscle_video_embedded_mysql = quote_smart($link, $get_muscle_video_embedded);
		$inp_muscle_unique_hits_mysql = quote_smart($link, $get_muscle_unique_hits);
		$inp_muscle_unique_hits_ip_block_mysql = quote_smart($link, $get_muscle_unique_hits_ip_block);

		// Current
		$input = "/*- $get_muscle_latin_name ------------------------------------------------------*/ 
                        q = \"INSERT INTO muscles (_id, muscle_id, muscle_user_id, \" +
                                \"muscle_latin_name, muscle_latin_name_clean, muscle_simple_name, \" +
                                \"muscle_short_name, muscle_group_id_main, muscle_group_id_sub, \" +
                                \"muscle_part_of_id, muscle_text, muscle_image_path, \" +
                                \"muscle_image_file, muscle_video_path, muscle_video_file, \" +
                                \"muscle_video_embedded, muscle_unique_hits) \" +
                                \"VALUES (\"
                                + \"NULL, \"
                                + \"$inp_muscle_id_mysql\" + \", \"
                                + \"$inp_muscle_user_id_mysql\" + \", \"
                                + \"$inp_muscle_latin_name_mysql\" + \", \"
                                + \"$inp_muscle_latin_name_clean_mysql\" + \", \"
                                + \"$inp_muscle_simple_name_mysql\" + \", \"
                                + \"$inp_muscle_short_name_mysql\" + \", \"
                                + \"$inp_muscle_group_id_main_mysql\" + \", \"
                                + \"$inp_muscle_group_id_sub_mysql\" + \", \"
                                + \"$inp_muscle_part_of_id_mysql\" + \", \"
                                + \"$inp_muscle_text_mysql\" + \", \"
                                + \"$inp_muscle_image_path_mysql\" + \", \"
                                + \"$inp_muscle_image_file_mysql\" + \", \"
                                + \"$inp_muscle_video_path_mysql\" + \", \"
                                + \"$inp_muscle_video_file_mysql\" + \", \"
                                + \"$inp_muscle_video_embedded_mysql\" + \", \"
                                + \"$inp_muscle_unique_hits_mysql\" 
                                + \")\";
                        cursorTemp = db.rawQuery(q);
			cursorTemp.close();
";

		$fh = fopen($sqlite_file, "a+") or die("can not open file");
		fwrite($fh, $input);
		fclose($fh);

	} // while muscles


	// muscles_translations
	$query_w = "SELECT muscle_translation_id, muscle_translation_muscle_id, muscle_translation_language, muscle_translation_simple_name, muscle_translation_short_name, muscle_translation_text, muscle_translation_video_path, muscle_translation_video_file, muscle_translation_video_embedded FROM $t_muscles_translations WHERE muscle_translation_language=$editor_language_mysql";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_muscle_translation_id, $get_muscle_translation_muscle_id, $get_muscle_translation_language, $get_muscle_translation_simple_name, $get_muscle_translation_short_name, $get_muscle_translation_text, $get_muscle_translation_video_path, $get_muscle_translation_video_file, $get_muscle_translation_video_embedded) = $row_w;
		
		// Variables

		$inp_muscle_translation_id_mysql = quote_smart($link, $get_muscle_translation_id);
		$inp_muscle_translation_muscle_id_mysql = quote_smart($link, $get_muscle_translation_muscle_id);
		$inp_muscle_translation_language_mysql = quote_smart($link, $get_muscle_translation_language);
		$inp_muscle_translation_simple_name_mysql = quote_smart($link, $get_muscle_translation_simple_name);
		$inp_muscle_translation_short_name_mysql = quote_smart($link, $get_muscle_translation_short_name);

		$get_muscle_translation_text = str_replace('"', "&quot;", $get_muscle_translation_text);
		$get_muscle_translation_text = str_replace("'", "&#39;", $get_muscle_translation_text);
		$inp_muscle_translation_text_mysql = quote_smart($link, $get_muscle_translation_text);
		$inp_muscle_translation_video_path_mysql = quote_smart($link, $get_muscle_translation_video_path);
		$inp_muscle_translation_video_file_mysql = quote_smart($link, $get_muscle_translation_video_file);
		$inp_muscle_translation_video_embedded_mysql = quote_smart($link, $get_muscle_translation_video_embedded);

		// Current
		$input = "
                       q = \"INSERT INTO muscles_translations (_id, muscle_translation_id, muscle_translation_muscle_id, \" +
                                \"muscle_translation_language, muscle_translation_simple_name, muscle_translation_short_name, \" +
                                \"muscle_translation_text, muscle_translation_video_path, muscle_translation_video_file, \" +
                                \"muscle_translation_video_embedded) \" +
                                \"VALUES (\"
                                + \"NULL, \"
                                + \"$inp_muscle_translation_id_mysql\" + \", \"
                                + \"$inp_muscle_translation_muscle_id_mysql\" + \", \"
                                + \"$inp_muscle_translation_language_mysql\" + \", \"
                                + \"$inp_muscle_translation_simple_name_mysql\" + \", \"
                                + \"$inp_muscle_translation_short_name_mysql\" + \", \"
                                + \"$inp_muscle_translation_text_mysql\" + \", \"
                                + \"$inp_muscle_translation_video_path_mysql\" + \", \"
                                + \"$inp_muscle_translation_video_file_mysql\" + \", \"
                                + \"$inp_muscle_translation_video_embedded_mysql\" 
                                + \")\";
                        cursorTemp = db.rawQuery(q);
			cursorTemp.close();
";

		$fh = fopen($sqlite_file, "a+") or die("can not open file");
		fwrite($fh, $input);
		fclose($fh);

	} // while muscles_translations


	// muscle_groups
	$query_w = "SELECT muscle_group_id, muscle_group_latin_name, muscle_group_latin_name_clean, muscle_group_name, muscle_group_name_clean, muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file FROM $t_muscle_groups";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_muscle_group_id, $get_muscle_group_latin_name, $get_muscle_group_latin_name_clean, $get_muscle_group_name, $get_muscle_group_name_clean, $get_muscle_group_parent_id, $get_muscle_group_image_path, $get_muscle_group_image_file) = $row_w;
		
		// Variables
		$inp_muscle_group_id_mysql = quote_smart($link, $get_muscle_group_id);
		$inp_muscle_group_latin_name_mysql = quote_smart($link, $get_muscle_group_latin_name);
		$inp_muscle_group_latin_name_clean_mysql = quote_smart($link, $get_muscle_group_latin_name_clean);
		$inp_muscle_group_name_mysql = quote_smart($link, $get_muscle_group_name);
		$inp_muscle_group_name_clean_mysql = quote_smart($link, $get_muscle_group_name_clean);
		$inp_muscle_group_parent_id_mysql = quote_smart($link, $get_muscle_group_parent_id);
		$inp_muscle_group_image_path_mysql = quote_smart($link, $get_muscle_group_image_path);
		$inp_muscle_group_image_file_mysql = quote_smart($link, $get_muscle_group_image_file);

		// Current
		$input = "
                      q = \"INSERT INTO muscle_groups (_id, muscle_group_id, muscle_group_latin_name, \" +
                                \"muscle_group_latin_name_clean, muscle_group_name, muscle_group_name_clean, \" +
                                \"muscle_group_parent_id, muscle_group_image_path, muscle_group_image_file) \" +
                                \"VALUES (\"
                                + \"NULL, \"
                                + \"$inp_muscle_group_id_mysql\" + \", \"
                                + \"$inp_muscle_group_latin_name_mysql\" + \", \"
                                + \"$inp_muscle_group_latin_name_clean_mysql\" + \", \"
                                + \"$inp_muscle_group_name_mysql\" + \", \"
                                + \"$inp_muscle_group_name_clean_mysql\" + \", \"
                                + \"$inp_muscle_group_parent_id_mysql\" + \", \"
                                + \"$inp_muscle_group_image_path_mysql\" + \", \"
                                + \"$inp_muscle_group_image_file_mysql\" 
                                + \")\";
                        cursorTemp = db.rawQuery(q);
			cursorTemp.close();
";

		$fh = fopen($sqlite_file, "a+") or die("can not open file");
		fwrite($fh, $input);
		fclose($fh);

	} // while muscle_groups

	

	// muscle_groups_translations 
	$query_w = "SELECT muscle_group_translation_id, muscle_group_translation_muscle_group_id, muscle_group_translation_language, muscle_group_translation_name, muscle_group_translation_text FROM $t_muscle_groups_translations WHERE muscle_group_translation_language=$editor_language_mysql";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_muscle_group_translation_id, $get_muscle_group_translation_muscle_group_id, $get_muscle_group_translation_language, $get_muscle_group_translation_name, $get_muscle_group_translation_text) = $row_w;
		
		// Variables
		$inp_muscle_group_translation_id_mysql = quote_smart($link, $get_muscle_group_translation_id);
		$inp_muscle_group_translation_muscle_group_id_mysql = quote_smart($link, $get_muscle_group_translation_muscle_group_id);
		$inp_muscle_group_translation_language_mysql = quote_smart($link, $get_muscle_group_translation_language);
		$inp_muscle_group_translation_name_mysql = quote_smart($link, $get_muscle_group_translation_name);

		$get_muscle_group_translation_text = str_replace('"', "&quot;", $get_muscle_group_translation_text);
		$get_muscle_group_translation_text = str_replace("'", "&#39;", $get_muscle_group_translation_text);
		$inp_muscle_group_translation_text_mysql = quote_smart($link, $get_muscle_group_translation_text);

		// Current
		$input = "
                        q = \"INSERT INTO muscle_groups_translations (_id, muscle_group_translation_id, muscle_group_translation_muscle_group_id, \" +
                                \"muscle_group_translation_language, muscle_group_translation_name, muscle_group_translation_text) \" +
                                \"VALUES (\"
                                + \"NULL, \"
                                + \"$inp_muscle_group_translation_id_mysql\" + \", \"
                                + \"$inp_muscle_group_translation_muscle_group_id_mysql\" + \", \"
                                + \"$inp_muscle_group_translation_language_mysql\" + \", \"
                                + \"$inp_muscle_group_translation_name_mysql\" + \", \"
                                + \"$inp_muscle_group_translation_text_mysql\" 
                                + \")\";
                        cursorTemp = db.rawQuery(q);
			cursorTemp.close();
";

		$fh = fopen($sqlite_file, "a+") or die("can not open file");
		fwrite($fh, $input);
		fclose($fh);

	} // while muscle_groups_translations 


	// muscle_part_of
	$query_w = "SELECT muscle_part_of_id, muscle_part_of_latin_name, muscle_part_of_latin_name_clean, muscle_part_of_name, muscle_part_of_name_clean, muscle_part_of_muscle_group_id_main, muscle_part_of_muscle_group_id_sub, muscle_part_of_image_path, muscle_part_of_image_file FROM $t_muscle_part_of";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_muscle_part_of_id, $get_muscle_part_of_latin_name, $get_muscle_part_of_latin_name_clean, $get_muscle_part_of_name, $get_muscle_part_of_name_clean, $get_muscle_part_of_muscle_group_id_main, $get_muscle_part_of_muscle_group_id_sub, $get_muscle_part_of_image_path, $get_muscle_part_of_image_file) = $row_w;
		
		// Variables


		$inp_muscle_part_of_id_mysql = quote_smart($link, $get_muscle_part_of_id);
		$inp_muscle_part_of_latin_name_mysql = quote_smart($link, $get_muscle_part_of_latin_name);
		$inp_muscle_part_of_latin_name_clean_mysql = quote_smart($link, $get_muscle_part_of_latin_name_clean);
		$inp_muscle_part_of_name_mysql = quote_smart($link, $get_muscle_part_of_name);
		$inp_muscle_part_of_name_clean_mysql = quote_smart($link, $get_muscle_part_of_name_clean);
		$inp_muscle_part_of_muscle_group_id_main_mysql = quote_smart($link, $get_muscle_part_of_muscle_group_id_main);
		$inp_muscle_part_of_muscle_group_id_sub_mysql = quote_smart($link, $get_muscle_part_of_muscle_group_id_sub);
		$inp_muscle_part_of_image_path_mysql = quote_smart($link, $get_muscle_part_of_image_path);
		$inp_muscle_part_of_image_file_mysql = quote_smart($link, $get_muscle_part_of_image_file);

		// Current
		$input = "
                          q = \"INSERT INTO muscle_part_of (_id, muscle_part_of_id, muscle_part_of_latin_name, \" +
                                \"muscle_part_of_latin_name_clean, muscle_part_of_name, muscle_part_of_name_clean, \" +
                                \"muscle_part_of_muscle_group_id_main, muscle_part_of_muscle_group_id_sub, muscle_part_of_image_path, \" +
                                \"muscle_part_of_image_file) \" +
                                \"VALUES (\"
                                + \"NULL, \"
                                + \"$inp_muscle_part_of_id_mysql\" + \", \"
                                + \"$inp_muscle_part_of_latin_name_mysql\" + \", \"
                                + \"$inp_muscle_part_of_latin_name_clean_mysql\" + \", \"
                                + \"$inp_muscle_part_of_name_mysql\" + \", \"
                                + \"$inp_muscle_part_of_name_clean_mysql\" + \", \"
                                + \"$inp_muscle_part_of_muscle_group_id_main_mysql\" + \", \"
                                + \"$inp_muscle_part_of_muscle_group_id_sub_mysql\" + \", \"
                                + \"$inp_muscle_part_of_image_path_mysql\" + \", \"
                                + \"$inp_muscle_part_of_image_file_mysql\" 
                                + \")\";
                        cursorTemp = db.rawQuery(q);
			cursorTemp.close();
";

		$fh = fopen($sqlite_file, "a+") or die("can not open file");
		fwrite($fh, $input);
		fclose($fh);

	} // while muscle_part_of



	// muscle_part_of_translations
	$query_w = "SELECT muscle_part_of_translation_id, muscle_part_of_translation_muscle_part_of_id, muscle_part_of_translation_language, muscle_part_of_translation_name, muscle_part_of_translation_text FROM $t_muscle_part_of_translations WHERE muscle_part_of_translation_language=$editor_language_mysql";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_muscle_part_of_translation_id, $get_muscle_part_of_translation_muscle_part_of_id, $get_muscle_part_of_translation_language, $get_muscle_part_of_translation_name, $get_muscle_part_of_translation_text) = $row_w;
		
		// Variables
		$inp_muscle_part_of_translation_id_mysql = quote_smart($link, $get_muscle_part_of_translation_id);
		$inp_muscle_part_of_translation_muscle_part_of_id_mysql = quote_smart($link, $get_muscle_part_of_translation_muscle_part_of_id);
		$inp_muscle_part_of_translation_language_mysql = quote_smart($link, $get_muscle_part_of_translation_language);
		$inp_muscle_part_of_translation_name_mysql = quote_smart($link, $get_muscle_part_of_translation_name);
		$inp_muscle_part_of_translation_text_mysql = quote_smart($link, $get_muscle_part_of_translation_text);


		$input = "
                          q = \"INSERT INTO muscle_part_of_translations (_id, muscle_part_of_translation_id, muscle_part_of_translation_muscle_part_of_id, \" +
                                \"muscle_part_of_translation_language, muscle_part_of_translation_name, muscle_part_of_translation_text) \" +
                                \"VALUES (\"
                                + \"NULL, \"
                                + \"$inp_muscle_part_of_translation_id_mysql\" + \", \"
                                + \"$inp_muscle_part_of_translation_muscle_part_of_id_mysql\" + \", \"
                                + \"$inp_muscle_part_of_translation_language_mysql\" + \", \"
                                + \"$inp_muscle_part_of_translation_name_mysql\" + \", \"
                                + \"$inp_muscle_part_of_translation_text_mysql\" 
                                + \")\";
                        cursorTemp = db.rawQuery(q);
			cursorTemp.close();
";

		$fh = fopen($sqlite_file, "a+") or die("can not open file");
		fwrite($fh, $input);
		fclose($fh);

	} // while muscle_part_of_translations



 	// Footer
	$editor_language_ucfirst = ucfirst($editor_language);
	$input = "		// Db close
db.close();

		// Move
        	Intent i = new Intent(this, SetupOfflineInsertEWorkoutPlansTagsUnique$editor_language_ucfirst.class);
       	 	startActivity(i);
       		finish();

	} // insertMuscles
";

	$fh = fopen($sqlite_file, "a+") or die("can not open file");
	fwrite($fh, $input);
	fclose($fh);

?>