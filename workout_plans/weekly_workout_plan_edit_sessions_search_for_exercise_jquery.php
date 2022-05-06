<?php 
/**
*
* File: workout_plans/weekly_workout_plan_edit_sessions_search_for_exercise_jquery.php
* Version 1.0.0
* Date 15:38 21.01.2018
* Copyright (c) 2018 S. A. Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/





/*- Functions ------------------------------------------------------------------------ */
include("../_admin/_functions/output_html.php");
include("../_admin/_functions/clean.php");
include("../_admin/_functions/quote_smart.php");
include("../_admin/_functions/get_extension.php");
include("../_admin/_functions/resize_crop_image.php");





/*- Common variables ----------------------------------------------------------------- */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);



/*- MySQL ------------------------------------------------------------ */
$check = substr($server_name, 0, 3);
if($check == "www"){
	$server_name = substr($server_name, 3);
}

$setup_finished_file = "setup_finished_" . $server_name . ".php";
if(!(file_exists("../_admin/_data/$setup_finished_file"))){
	die;
}

else{
	include("../_admin/_data/config/meta.php");
	include("../_admin/_data/config/user_system.php");

}

$mysql_config_file = "../_admin/_data/mysql_" . $server_name . ".php";
include("$mysql_config_file");
$link = mysqli_connect($mysqlHostSav, $mysqlUserNameSav, $mysqlPasswordSav, $mysqlDatabaseNameSav);
if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}



/*- Tables ---------------------------------------------------------------------------- */
include("_tables.php");




/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['l']) OR isset($_POST['l'])) {
	if(isset($_GET['l'])){
		$l = $_GET['l'];
	}
	else{
		$l = $_POST['l'];
	}
	$l = strip_tags(stripslashes($l));
}
else{
	$l = "";
}
$l_mysql = quote_smart($link, $l);

if(isset($_GET['weekly_id']) OR isset($_POST['weekly_id'])) {
	if(isset($_GET['weekly_id'])){
		$weekly_id = $_GET['weekly_id'];
	}
	else{
		$weekly_id = $_POST['weekly_id'];
	}
	$weekly_id = strip_tags(stripslashes($weekly_id));
}
else{
	$weekly_id = "";
}
if(isset($_GET['session_id']) OR isset($_POST['session_id'])) {
	if(isset($_GET['session_id'])){
		$session_id = $_GET['session_id'];
	}
	else{
		$session_id = $_POST['session_id'];
	}
	$session_id = strip_tags(stripslashes($session_id));
}
else{
	$session_id = "";
}




/*- Language ------------------------------------------------------------------------ */
// include("../_admin/_translations/site/$l/food/ts_food.php");

/*- Table exists? -------------------------------------------------------------------- */
$query = "SELECT * FROM $t_exercise_queries LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
}
else{
	echo"Table created";
	mysqli_query($link, "CREATE TABLE $t_exercise_queries(
	 query_id INT NOT NULL AUTO_INCREMENT,
	 PRIMARY KEY(query_id), 
	 query_name VARCHAR(90) NOT NULL,
	 query_times BIGINT,
	 query_last_use DATETIME,
	 query_hidden INT)")
	 or die(mysql_error());
}


/*- Query --------------------------------------------------------------------------- */
if(isset($_GET['q']) OR isset($_POST['q'])){
	if(isset($_GET['q'])) {
		$q = $_GET['q'];
	}
	else{
		$q = $_POST['q'];
	}
	$q = trim($q);
	$q = strtolower($q);
	$inp_datetime = date("Y-m-d H:i:s");
	$q = output_html($q);
	$q_mysql = quote_smart($link, $q);

	if($q != ""){
		$query = "SELECT query_name, query_times FROM $t_exercise_queries WHERE query_name=$q_mysql";
		$res = mysqli_query($link, $query);
		$row = mysqli_fetch_row($res);
		$get_query_name = $row[0];
		$get_query_times = $row[1];

		if($get_query_name == ""){
			// Insert
			$insert_error = "0";
			mysqli_query($link, "INSERT INTO $t_exercise_queries
			(query_name, query_times, query_last_use) 
			VALUES
			($q_mysql, '1', '$inp_datetime') ")
			or $insert_error = 1;
		}
		else{
			$inp_query_times = $get_query_times+1;
			$result = mysqli_query($link, "UPDATE $t_exercise_queries SET query_times='$inp_query_times', query_last_use='$inp_datetime' WHERE query_name=$q_mysql");
		}


		// Ready for MySQL search
		$q = $q . "%";
		$q_mysql = quote_smart($link, $q);

		// Set layout
		$x = 0;

		// Query
		$query = "SELECT exercise_id, exercise_title, exercise_user_id, exercise_muscle_group_id_main, exercise_equipment_id, exercise_type_id, exercise_level_id, exercise_updated_datetime, exercise_guide FROM $t_exercise_index";
		$query = $query  . " WHERE exercise_language=$l_mysql AND (exercise_title LIKE $q_mysql OR exercise_title_alternative LIKE $q_mysql) ORDER BY exercise_title ASC";
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



			// Title
			echo"
			<p style=\"padding: 10px 0px 0px 0px;margin-bottom:0;\">
			<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=add_exercise_to_session&amp;session_id=$session_id&amp;type_id=$get_exercise_type_id&amp;main_muscle_group_id=$get_exercise_muscle_group_id_main&amp;exercise_id=$get_exercise_id&amp;mode=step_4_data&amp;l=$l\" class=\"exercise_index_title\">$get_exercise_title</a><br />
			</p>
			";

			// Thumb
			$query_images = "SELECT exercise_image_id, exercise_image_path, exercise_image_file, exercise_image_thumb_150x150 FROM $t_exercise_index_images WHERE exercise_image_exercise_id='$get_exercise_id' ORDER BY exercise_image_type ASC LIMIT 0,2";
			$result_images = mysqli_query($link, $query_images);
			while($row_images = mysqli_fetch_row($result_images)) {
				list($get_exercise_image_id, $get_exercise_image_path, $get_exercise_image_file, $get_exercise_image_thumb_150x150) = $row_images;

				if($get_exercise_image_file != "" && file_exists("../$get_exercise_image_path/$get_exercise_image_file")){
					
					echo"				";
					echo"<a href=\"weekly_workout_plan_edit_sessions.php?weekly_id=$weekly_id&amp;action=add_exercise_to_session&amp;session_id=$session_id&amp;type_id=$get_exercise_type_id&amp;main_muscle_group_id=$get_exercise_muscle_group_id_main&amp;exercise_id=$get_exercise_id&amp;mode=step_4_data&amp;l=$l\"><img src=\"../$get_exercise_image_path/$get_exercise_image_thumb_150x150\" alt=\"$get_exercise_image_thumb_150x150\" /></a>\n";
				}
			}

			echo"
			</div>

			";



			// Increment
			if($x == 1){
				$x = -1;
			}
			$x++;

		} // while

		if($x == 1){
			echo"<div class=\"clear\"></div>";
		}
	}

}

else{

	echo"No q";

}



echo"<div id=\"number_action\"></div>";

?>