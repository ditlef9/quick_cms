<?php
/**
*
* File: _admin/_inc/workout_plans/sqlite_tags_unique.php
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
$t_workout_plans_yearly  		= $mysqlPrefixSav . "workout_plans_yearly";
$t_workout_plans_period  		= $mysqlPrefixSav . "workout_plans_period";
$t_workout_plans_weekly  		= $mysqlPrefixSav . "workout_plans_weekly";
$t_workout_plans_weekly_tags  		= $mysqlPrefixSav . "workout_plans_weekly_tags";
$t_workout_plans_weekly_tags_unique  	= $mysqlPrefixSav . "workout_plans_weekly_tags_unique";
$t_workout_plans_sessions 		= $mysqlPrefixSav . "workout_plans_sessions";
$t_workout_plans_sessions_main 		= $mysqlPrefixSav . "workout_plans_sessions_main";
$t_workout_plans_favorites 		= $mysqlPrefixSav . "workout_plans_favorites";

/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['type_id'])) {
	$type_id= $_GET['type_id'];
	$type_id = strip_tags(stripslashes($type_id));
}
else{
	$type_id = "";
}




	echo"
	<h1>Tags Unique SQL Lite</h1>

	

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
	$sqlite_file = "../_cache/sqlite_workout_plans_tags_unique_" . $editor_language . "_" . $datetime . ".txt";
	if(file_exists("$sqlite_file")){
		unlink("$sqlite_file");
	}
	$input = "/*- insertWorkoutPlansTagsUnique $editor_language --------------------------------------------------------- */
private void insertWorkoutPlansTagsUnique(){
	DBAdapter db = new DBAdapter(this);
	db.open();

	String q = \"\";

";

	$fh = fopen($sqlite_file, "w+") or die("can not open file");
	fwrite($fh, $input);
	fclose($fh);


	// Synchronize workout_plans_weekly_tags_unique
	$editor_language_mysql = quote_smart($link, $editor_language);
	$query = "SELECT count(tag_unique_id) FROM $t_workout_plans_weekly_tags_unique WHERE tag_unique_language=$editor_language_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_count_tag_unique_id) = $row;

	
	$input = "
	// Synchronize
	q = \"SELECT _id, name, last_on_local, synchronized_week FROM synchronize WHERE name='workout_plans_weekly_tags_unique'\";
        Cursor exerciseIndexCursor = db.rawQuery(q);
        int size = exerciseIndexCursor.getCount();
        if (size == 0) {
            q = \"INSERT INTO synchronize (_id, name, last_on_local, last_on_server) VALUES (NULL, 'workout_plans_weekly_tags_unique', '$get_count_tag_unique_id', '$get_count_tag_unique_id')\";
            db.rawQuery(q);
        } else {
            q = \"UPDATE synchronize SET last_on_local='$get_count_tag_unique_id', last_on_server='$get_count_tag_unique_id' WHERE name='workout_plans_weekly_tags_unique'\";
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

	$editor_language_mysql = quote_smart($link, $editor_language);


	$query = "SELECT tag_unique_id, tag_unique_language, tag_unique_title, tag_unique_title_clean, tag_unique_no_of_workout_plans, tag_unique_hits, tag_unique_hits_ipblock FROM $t_workout_plans_weekly_tags_unique WHERE tag_unique_language=$editor_language_mysql";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_tag_unique_id, $get_tag_unique_language, $get_tag_unique_title, $get_tag_unique_title_clean, $get_tag_unique_no_of_workout_plans, $get_tag_unique_hits, $get_tag_unique_hits_ipblock) = $row;
		
		// Variables
		$inp_tag_unique_id_mysql = quote_smart($link, $get_tag_unique_id);
		$inp_tag_unique_language_mysql = quote_smart($link, $get_tag_unique_language);
		$inp_tag_unique_title_mysql = quote_smart($link, $get_tag_unique_title);
		$inp_tag_unique_title_clean_mysql = quote_smart($link, $get_tag_unique_title_clean);
		$inp_tag_unique_no_of_workout_plans_mysql = quote_smart($link, $get_tag_unique_no_of_workout_plans);
		$inp_tag_unique_hits_mysql = quote_smart($link, $get_tag_unique_hits);
		$inp_tag_unique_hits_ipblock_mysql = quote_smart($link, $get_tag_unique_hits_ipblock);

		// Current
		$input = "
			q = \"INSERT INTO workout_plans_weekly_tags_unique (_id, tag_unique_id, tag_unique_language, \" +
                                \"tag_unique_title, tag_unique_title_clean, tag_unique_no_of_workout_plans, \" +
                                \"tag_unique_hits, tag_unique_hits_ipblock) \" +
                                \"VALUES (\"
                                + \"NULL, \"
                                + \"$inp_tag_unique_id_mysql\" + \", \"
                                + \"$inp_tag_unique_language_mysql\" + \", \"
                                + \"$inp_tag_unique_title_mysql\" + \", \"
                                + \"$inp_tag_unique_title_clean_mysql\" + \", \"
                                + \"$inp_tag_unique_no_of_workout_plans_mysql\" + \", \"
                                + \"$inp_tag_unique_hits_mysql\" + \", \"
                                + \"$inp_tag_unique_hits_ipblock_mysql\" 
                                + \")\";
                        db.rawQuery(q);

";

		$fh = fopen($sqlite_file, "a+") or die("can not open file");
		fwrite($fh, $input);
		fclose($fh);
	}
	

 	// Footer
	$editor_language_ucfirst = ucfirst($editor_language);
	$input = "		// Db close
db.close();

		// Move
                Intent i = new Intent(this, SetupBPermissionsActivity.class);
                startActivity(i);
                finish();
		
	} // insertWorkoutPlansWeekly
";

	$fh = fopen($sqlite_file, "a+") or die("can not open file");
	fwrite($fh, $input);
	fclose($fh);


?>