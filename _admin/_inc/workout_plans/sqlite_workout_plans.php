<?php
/**
*
* File: _admin/_inc/workout_plans/sqlite_workout_plans.php
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
	<h1>Workout plans SQL Lite</h1>

	

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
	$sqlite_file = "../_cache/sqlite_workout_plans_" . $editor_language . ".txt";
	if(file_exists("$sqlite_file")){
		unlink("$sqlite_file");
	}
	$input = "/*- insertWorkoutPlansWeekly $editor_language --------------------------------------------------------- */
private void insertWorkoutPlansWeekly(){
	DBAdapter db = new DBAdapter(this);
	db.open();

	String q = \"\";

";

	$fh = fopen($sqlite_file, "w+") or die("can not open file");
	fwrite($fh, $input);
	fclose($fh);


	// Synchronize Workout plans
	$editor_language_mysql = quote_smart($link, $editor_language);
	$query = "SELECT count(workout_weekly_id) FROM $t_workout_plans_weekly WHERE workout_weekly_language=$editor_language_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_count_workout_weekly_id) = $row;

	
	$input = "
	// Synchronize
	q = \"SELECT _id, name, last_on_local, synchronized_week FROM synchronize WHERE name='workout_plans_weekly'\";
        Cursor exerciseIndexCursor = db.rawQuery(q);
        int size = exerciseIndexCursor.getCount();
        if (size == 0) {
            q = \"INSERT INTO synchronize (_id, name, last_on_local, last_on_server) VALUES (NULL, 'workout_plans_weekly', '$get_count_workout_weekly_id', '$get_count_workout_weekly_id')\";
            db.rawQuery(q);
        } else {
            q = \"UPDATE synchronize SET last_on_local='$get_count_workout_weekly_id', last_on_server='$get_count_workout_weekly_id' WHERE name='workout_plans_weekly'\";
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
	$query_w = "SELECT workout_weekly_id, workout_weekly_user_id, workout_weekly_period_id, workout_weekly_weight, workout_weekly_language, workout_weekly_title, workout_weekly_title_clean, workout_weekly_introduction, workout_weekly_goal, workout_weekly_image_path, workout_weekly_image_thumb_medium, workout_weekly_image_thumb_big, workout_weekly_image_file, workout_weekly_created, workout_weekly_updated, workout_weekly_unique_hits, workout_weekly_unique_hits_ip_block, workout_weekly_comments, workout_weekly_likes, workout_weekly_dislikes, workout_weekly_rating, workout_weekly_ip_block, workout_weekly_user_ip, workout_weekly_notes, workout_weekly_number_of_sessions FROM $t_workout_plans_weekly ";
	$query_w = $query_w . "WHERE workout_weekly_language=$editor_language_mysql ";
	$query_w = $query_w . "ORDER BY workout_weekly_unique_hits DESC";
	$result_w = mysqli_query($link, $query_w);
	while($row_w = mysqli_fetch_row($result_w)) {
		list($get_workout_weekly_id, $get_workout_weekly_user_id, $get_workout_weekly_period_id, $get_workout_weekly_weight, $get_workout_weekly_language, $get_workout_weekly_title, $get_workout_weekly_title_clean, $get_workout_weekly_introduction, $get_workout_weekly_goal, $get_workout_weekly_image_path, $get_workout_weekly_image_thumb_medium, $get_workout_weekly_image_thumb_big, $get_workout_weekly_image_file, $get_workout_weekly_created, $get_workout_weekly_updated, $get_workout_weekly_unique_hits, $get_workout_weekly_unique_hits_ip_block, $get_workout_weekly_comments, $get_workout_weekly_likes, $get_workout_weekly_dislikes, $get_workout_weekly_rating, $get_workout_weekly_ip_block, $get_workout_weekly_user_ip, $get_workout_weekly_notes, $get_workout_weekly_number_of_sessions) = $row_w;
		

		// Variables
		$inp_workout_weekly_weight_mysql = quote_smart($link, $get_workout_weekly_weight);
		$inp_workout_weekly_language_mysql = quote_smart($link, $get_workout_weekly_language);
		$inp_workout_weekly_title_mysql = quote_smart($link, $get_workout_weekly_title);
		$inp_workout_weekly_title_clean_mysql = quote_smart($link, $get_workout_weekly_title_clean); 
		$inp_workout_weekly_introduction_mysql = quote_smart($link, $get_workout_weekly_introduction);
		$inp_workout_weekly_goal_mysql = quote_smart($link, $get_workout_weekly_goal);
		$inp_workout_weekly_image_path_mysql = quote_smart($link, $get_workout_weekly_image_path);
		$inp_workout_weekly_image_thumb_medium_mysql = quote_smart($link, $get_workout_weekly_image_thumb_medium);
		$inp_workout_weekly_image_thumb_big_mysql = quote_smart($link, $get_workout_weekly_image_thumb_big);
		$inp_workout_weekly_image_file_mysql = quote_smart($link, $get_workout_weekly_image_file); 
		$inp_workout_weekly_created_mysql = quote_smart($link, $get_workout_weekly_created);
		$inp_workout_weekly_updated_mysql = quote_smart($link, $get_workout_weekly_updated);
		$inp_workout_weekly_unique_hits_mysql = quote_smart($link, $get_workout_weekly_unique_hits);
		$inp_workout_weekly_unique_hits_ip_block_mysql = quote_smart($link, $get_workout_weekly_unique_hits_ip_block);
		$inp_workout_weekly_comments_mysql = quote_smart($link, $get_workout_weekly_comments);
		$inp_workout_weekly_likes_mysql = quote_smart($link, $get_workout_weekly_likes);
		$inp_workout_weekly_dislikes_mysql = quote_smart($link, $get_workout_weekly_dislikes);
		$inp_workout_weekly_rating_mysql = quote_smart($link, $get_workout_weekly_rating);
		$inp_workout_weekly_ip_block_mysql = quote_smart($link, $get_workout_weekly_ip_block);
		$inp_workout_weekly_user_ip_mysql = quote_smart($link, $get_workout_weekly_user_ip);
		$inp_workout_weekly_notes_mysql = quote_smart($link, $get_workout_weekly_notes);
		$inp_workout_weekly_number_of_sessions_mysql = quote_smart($link, $get_workout_weekly_number_of_sessions);

		// Current
		$input = "/*- $get_workout_weekly_title ------------------------------------------------------*/

                       q = \"INSERT INTO workout_plans_weekly (_id, workout_weekly_id, workout_weekly_user_id, \" +
                                \"workout_weekly_period_id, workout_weekly_weight, workout_weekly_language, \" +
                                \"workout_weekly_title, workout_weekly_title_clean, workout_weekly_introduction, \" +
                                \"workout_weekly_goal, workout_weekly_image_path, workout_weekly_image_thumb_medium, \" +
                                \"workout_weekly_image_thumb_big, workout_weekly_image_file, \" +
                                \"workout_weekly_created, workout_weekly_updated, workout_weekly_unique_hits, \" +
                                \"workout_weekly_unique_hits_ip_block, workout_weekly_comments, workout_weekly_likes, \" +
                                \"workout_weekly_dislikes, workout_weekly_rating, workout_weekly_ip_block, \" +
                                \"workout_weekly_user_ip, workout_weekly_notes, workout_weekly_number_of_sessions) \" +
                                \"VALUES (\"
                                + \"NULL, \"
                                + $get_workout_weekly_id + \", \"
                                + $get_workout_weekly_user_id + \", \"
                                + $get_workout_weekly_period_id + \", \"
                                + \"$inp_workout_weekly_weight_mysql\" + \", \"
                                + \"$inp_workout_weekly_language_mysql\" + \", \"
                                + \"$inp_workout_weekly_title_mysql\" + \", \"
                                + \"$inp_workout_weekly_title_clean_mysql\" + \", \"
                                + \"$inp_workout_weekly_introduction_mysql\" + \", \"
                                + \"$inp_workout_weekly_goal_mysql\" + \", \"
                                + \"$inp_workout_weekly_image_path_mysql\" + \", \"
                                + \"$inp_workout_weekly_image_thumb_medium_mysql\" + \", \"
                                + \"$inp_workout_weekly_image_thumb_big_mysql\" + \", \"
                                + \"$inp_workout_weekly_image_file_mysql\" + \", \"
                                + \"$inp_workout_weekly_created_mysql\" + \", \"
                                + \"$inp_workout_weekly_updated_mysql\" + \", \"
                                + \"$inp_workout_weekly_unique_hits_mysql\" + \", \"
                                + \"$inp_workout_weekly_unique_hits_ip_block_mysql\" + \", \"
                                + \"$inp_workout_weekly_comments_mysql\" + \", \"
                                + \"$inp_workout_weekly_likes_mysql\" + \", \"
                                + \"$inp_workout_weekly_dislikes_mysql\" + \", \"
                                + \"$inp_workout_weekly_rating_mysql\" + \", \"
                                + \"$inp_workout_weekly_ip_block_mysql\" + \", \"
                                + \"$inp_workout_weekly_user_ip_mysql\" + \", \"
                                + \"$inp_workout_weekly_notes_mysql\" + \", \"
                                + \"$inp_workout_weekly_number_of_sessions_mysql\" 
                                + \")\";
                        db.rawQuery(q);

";

		$fh = fopen($sqlite_file, "a+") or die("can not open file");
		fwrite($fh, $input);
		fclose($fh);

		
		// Tags
		$query_t = "SELECT tag_id, tag_weekly_id, tag_language, tag_title, tag_title_clean, tag_user_id FROM $t_workout_plans_weekly_tags WHERE tag_weekly_id=$get_workout_weekly_id";
		$result_t = mysqli_query($link, $query_t);
		while($row_t = mysqli_fetch_row($result_t)) {
			list($get_tag_id, $get_tag_weekly_id, $get_tag_language, $get_tag_title, $get_tag_title_clean, $get_tag_user_id) = $row_t;
		
			$inp_tag_language_mysql = quote_smart($link, $get_tag_language);
			$inp_tag_title_mysql = quote_smart($link, $get_tag_title);
			$inp_tag_title_clean_mysql = quote_smart($link, $get_tag_title_clean);

			// Tag
			$input = "// Tags
				q = \"INSERT INTO workout_plans_weekly_tags (_id, tag_id, tag_weekly_id, \" +
                                        \"tag_language, tag_title, tag_title_clean, \" +
                                        \"tag_user_id) \" +
                                        \"VALUES (\"
                                        + \"NULL, \"
                                        + $get_tag_id + \", \"
                                        + $get_tag_weekly_id + \", \"
                                        + \"$inp_tag_language_mysql\" + \", \"
                                        + \"$inp_tag_title_mysql\" + \", \"
                                        + \"$inp_tag_title_clean_mysql\" + \", \"
                                        + $get_tag_user_id
                                        + \")\";
                        db.rawQuery(q);

";

			$fh = fopen($sqlite_file, "a+") or die("can not open file");
			fwrite($fh, $input);
			fclose($fh);

			
		} // while tags
		

		// Sessions
		$query_t = "SELECT workout_session_id, workout_session_user_id, workout_session_weekly_id, workout_session_weight, workout_session_title, workout_session_title_clean, workout_session_duration, workout_session_intensity, workout_session_goal, workout_session_warmup, workout_session_end FROM $t_workout_plans_sessions WHERE workout_session_weekly_id=$get_workout_weekly_id";
		$result_t = mysqli_query($link, $query_t);
		while($row_t = mysqli_fetch_row($result_t)) {
			list($get_workout_session_id, $get_workout_session_user_id, $get_workout_session_weekly_id, $get_workout_session_weight, $get_workout_session_title, $get_workout_session_title_clean, $get_workout_session_duration, $get_workout_session_intensity, $get_workout_session_goal, $get_workout_session_warmup, $get_workout_session_end) = $row_t;
		

			$inp_workout_session_id_mysql = quote_smart($link, $get_workout_session_id);
			$inp_workout_session_user_id_mysql = quote_smart($link, $get_workout_session_user_id);
			$inp_workout_session_weekly_id_mysql = quote_smart($link, $get_workout_session_weekly_id);
			$inp_workout_session_weight_mysql = quote_smart($link, $get_workout_session_weight);

			$inp_workout_session_title = str_replace("'", "&#39;", $get_workout_session_title);
			$inp_workout_session_title_mysql = quote_smart($link, $inp_workout_session_title);

			$inp_workout_session_title_clean_mysql = quote_smart($link, $get_workout_session_title_clean);
			$inp_workout_session_duration_mysql = quote_smart($link, $get_workout_session_duration);
			$inp_workout_session_intensity_mysql = quote_smart($link, $get_workout_session_intensity);

			$inp_workout_session_goal = str_replace("'", "&#39;", $get_workout_session_goal);
			$inp_workout_session_goal_mysql = quote_smart($link, $inp_workout_session_goal);

			$inp_workout_session_warmup = str_replace("'", "&#39;", $get_workout_session_warmup);
			$inp_workout_session_warmup_mysql = quote_smart($link, $inp_workout_session_warmup);

			$inp_workout_session_end = str_replace("'", "&#39;", $get_workout_session_end);
			$inp_workout_session_end_mysql = quote_smart($link, $inp_workout_session_end);

			$input = "// Sessions
				 q = \"INSERT INTO workout_plans_sessions (_id, workout_session_id, workout_session_user_id, \" +
                                \"workout_session_weekly_id, workout_session_weight, workout_session_title, \" +
                                \"workout_session_title_clean, workout_session_duration, workout_session_intensity, \" +
                                \"workout_session_goal, workout_session_warmup, workout_session_end) \" +
                                \"VALUES (\"
                                + \"NULL, \"
                                + \"$inp_workout_session_id_mysql\" + \", \"
                                + \"$inp_workout_session_user_id_mysql\" + \", \"
                                + \"$inp_workout_session_weekly_id_mysql\" + \", \"
                                + \"$inp_workout_session_weight_mysql\" + \", \"
                                + \"$inp_workout_session_title_mysql\" + \", \"
                                + \"$inp_workout_session_title_clean_mysql\" + \", \"
                                + \"$inp_workout_session_duration_mysql\" + \", \"
                                + \"$inp_workout_session_intensity_mysql\" + \", \"
                                + \"$inp_workout_session_goal_mysql\" + \", \"
                                + \"$inp_workout_session_warmup_mysql\" + \", \"
                                + \"$inp_workout_session_end_mysql\" 
                                + \")\";
                        db.rawQuery(q);
			";

			$fh = fopen($sqlite_file, "a+") or die("can not open file");
			fwrite($fh, $input);
			fclose($fh);

			
			// Sessions Main
			$query_s = "SELECT workout_session_main_id, workout_session_main_user_id, workout_session_main_session_id, workout_session_main_weight, workout_session_main_exercise_id, workout_session_main_exercise_title, workout_session_main_reps, workout_session_main_sets, workout_session_main_velocity_a, workout_session_main_velocity_b, workout_session_main_distance, workout_session_main_duration, workout_session_main_intensity, workout_session_main_text FROM $t_workout_plans_sessions_main WHERE workout_session_main_session_id=$inp_workout_session_id_mysql";
			$result_s = mysqli_query($link, $query_s);
			while($row_s = mysqli_fetch_row($result_s)) {
				list($get_workout_session_main_id, $get_workout_session_main_user_id, $get_workout_session_main_session_id, $get_workout_session_main_weight, $get_workout_session_main_exercise_id, $get_workout_session_main_exercise_title, $get_workout_session_main_reps, $get_workout_session_main_sets, $get_workout_session_main_velocity_a, $get_workout_session_main_velocity_b, $get_workout_session_main_distance, $get_workout_session_main_duration, $get_workout_session_main_intensity, $get_workout_session_main_text) = $row_s;
		
				$inp_workout_session_main_id_mysql = quote_smart($link, $get_workout_session_main_id);
				$inp_workout_session_main_user_id_mysql = quote_smart($link, $get_workout_session_main_user_id);
				$inp_workout_session_main_session_id_mysql = quote_smart($link, $get_workout_session_main_session_id);
				$inp_workout_session_main_weight_mysql = quote_smart($link, $get_workout_session_main_weight);
				$inp_workout_session_main_exercise_id_mysql = quote_smart($link, $get_workout_session_main_exercise_id);
				$inp_workout_session_main_exercise_title_mysql = quote_smart($link, $get_workout_session_main_exercise_title);
				$inp_workout_session_main_reps_mysql = quote_smart($link, $get_workout_session_main_reps);
				$inp_workout_session_main_sets_mysql = quote_smart($link, $get_workout_session_main_sets);
				$inp_workout_session_main_velocity_a_mysql = quote_smart($link, $get_workout_session_main_velocity_a);
				$inp_workout_session_main_velocity_b_mysql = quote_smart($link, $get_workout_session_main_velocity_b);
				$inp_workout_session_main_distance_mysql = quote_smart($link, $get_workout_session_main_distance);
				$inp_workout_session_main_duration_mysql = quote_smart($link, $get_workout_session_main_duration);
				$inp_workout_session_main_intensity_mysql = quote_smart($link, $get_workout_session_main_intensity);
				$inp_workout_session_main_text_mysql = quote_smart($link, $get_workout_session_main_text);

			$input = "// Sessions
				q = \"INSERT INTO workout_plans_sessions_main (_id, workout_session_main_id, workout_session_main_user_id, \" +
                                    \"workout_session_main_session_id, workout_session_main_weight, workout_session_main_exercise_id, \" +
                                    \"workout_session_main_exercise_title, workout_session_main_reps, workout_session_main_sets, \" +
                                    \"workout_session_main_velocity_a, workout_session_main_velocity_b, workout_session_main_distance, \" +
                                    \"workout_session_main_duration, workout_session_main_intensity, workout_session_main_text) \" +
                                    \"VALUES (\"
                                    + \"NULL, \"
                                    + \"$inp_workout_session_main_id_mysql\" + \", \"
                                    + \"$inp_workout_session_main_user_id_mysql\" + \", \"
                                    + \"$inp_workout_session_main_session_id_mysql\" + \", \"
                                    + \"$inp_workout_session_main_weight_mysql\" + \", \"
                                    + \"$inp_workout_session_main_exercise_id_mysql\" + \", \"
                                    + \"$inp_workout_session_main_exercise_title_mysql\" + \", \"
                                    + \"$inp_workout_session_main_reps_mysql\" + \", \"
                                    + \"$inp_workout_session_main_sets_mysql\" + \", \"
                                    + \"$inp_workout_session_main_velocity_a_mysql\" + \", \"
                                    + \"$inp_workout_session_main_velocity_b_mysql\" + \", \"
                                    + \"$inp_workout_session_main_distance_mysql\" + \", \"
                                    + \"$inp_workout_session_main_duration_mysql\" + \", \"
                                    + \"$inp_workout_session_main_intensity_mysql\" + \", \"
                                    + \"$inp_workout_session_main_text_mysql\" 
                                    + \")\";
                            db.rawQuery(q);
				";

				$fh = fopen($sqlite_file, "a+") or die("can not open file");
				fwrite($fh, $input);
				fclose($fh);

			
			} // while sessions Main
		} // while sessions


	} // while



	

 	// Footer
	$editor_language_ucfirst = ucfirst($editor_language);
	$input = "		// Db close
db.close();

		// Move
                Intent i = new Intent(SetupOfflineInsertAWorkoutPlans$editor_language_ucfirst.this, SetupOfflineInsertBExercises$editor_language_ucfirst.class);
                startActivity(i);
                finish();
		
	} // insertWorkoutPlansWeekly
";

	$fh = fopen($sqlite_file, "a+") or die("can not open file");
	fwrite($fh, $input);
	fclose($fh);


?>