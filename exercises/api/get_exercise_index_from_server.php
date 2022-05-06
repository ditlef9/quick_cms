<?php
/*- Functions ------------------------------------------------------------------------- */
include("../../_admin/_functions/output_html.php");
include("../../_admin/_functions/clean.php");
include("../../_admin/_functions/quote_smart.php");
function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}


/*- MySQL ----------------------------------------------------------------------------- */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);

$mysql_config_file = "../../_admin/_data/mysql_" . $server_name . ".php";
include("$mysql_config_file");
$link = mysqli_connect($mysqlHostSav, $mysqlUserNameSav, $mysqlPasswordSav, $mysqlDatabaseNameSav);
if (!$link) {
	echo "Error MySQL link";
	die;
}


/*- MySQL Tables -------------------------------------------------------------------- */
$t_exercise_index 		= $mysqlPrefixSav . "exercise_index";
$t_exercise_index_images	= $mysqlPrefixSav . "exercise_index_images";
$t_exercise_index_videos	= $mysqlPrefixSav . "exercise_index_videos";
$t_exercise_index_muscles	= $mysqlPrefixSav . "exercise_index_muscles";
$t_exercise_index_muscles_images= $mysqlPrefixSav . "exercise_index_muscles_images";
$t_exercise_equipments 		= $mysqlPrefixSav . "exercise_equipments";
$t_exercise_types		= $mysqlPrefixSav . "exercise_types";
$t_exercise_types_translations 	= $mysqlPrefixSav . "exercise_types_translations";
$t_exercise_levels		= $mysqlPrefixSav . "exercise_levels";
$t_exercise_levels_translations = $mysqlPrefixSav . "exercise_levels_translations";
$t_users			= $mysqlPrefixSav . "users";

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['l'])) {
	$l = $_GET['l'];
	$l = strip_tags(stripslashes($l));
}
else{
	$l = "";
}
if(isset($_GET['exercise_id'])) {
	$exercise_id = $_GET['exercise_id'];
	$exercise_id = strip_tags(stripslashes($exercise_id));
}
else{
	$exercise_id = "";
}


/*- Get recipe ------------------------------------------------------------------------- */

// Build array
$rows_array = array();


// Select
$l_mysql = quote_smart($link, $l);
$exercise_id_mysql   = quote_smart($link, $exercise_id);
$query = "SELECT exercise_id FROM $t_exercise_index WHERE exercise_id=$exercise_id_mysql AND exercise_language=$l_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_exercise_id) = $row;

if($get_exercise_id != ""){


	// Build array
	$exercise_array = array();


	// Exercise index
	$query = "SELECT exercise_id, exercise_title, exercise_title_clean, exercise_user_id, exercise_language, exercise_muscle_group_id_main, exercise_muscle_group_id_sub, exercise_muscle_part_of_id, exercise_equipment_id, exercise_type_id, exercise_level_id, exercise_preparation, exercise_guide, exercise_important, exercise_created_datetime, exercise_updated_datetime, exercise_user_ip, exercise_uniqe_hits, exercise_uniqe_hits_ip_block, exercise_likes, exercise_dislikes, exercise_rating, exercise_rating_ip_block, exercise_number_of_comments, exercise_reported, exercise_reported_checked, exercise_reported_reason, exercise_last_viewed FROM $t_exercise_index WHERE exercise_id=$exercise_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_array($result);
	$exercise_array['index'] = $row;


	// exercise_index_images
	$exercise_array['images'] = array();
	$query = "SELECT exercise_image_id, exercise_image_user_id, exercise_image_exercise_id, exercise_image_datetime, exercise_image_type, exercise_image_path, exercise_image_file, exercise_image_uniqe_hits FROM $t_exercise_index_images WHERE exercise_image_exercise_id=$get_exercise_id";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_array($result)) {
		array_push($exercise_array['images'],$row);
	}

	// exercise_index_videos
	$exercise_array['videos'] = array();
	$query = "SELECT exercise_video_id, exercise_video_user_id, exercise_video_exercise_id, exercise_video_datetime, exercise_video_service_name, exercise_video_service_id, exercise_video_path, exercise_video_file, exercise_video_uniqe_hits FROM $t_exercise_index_videos WHERE exercise_video_exercise_id=$get_exercise_id";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_array($result)) {
		array_push($exercise_array['videos'],$row);
	}

	// exercise_index_muscles
	$exercise_array['muscles'] = array();
	$query = "SELECT exercise_muscle_id, exercise_muscle_exercise_id, exercise_muscle_muscle_id, exercise_muscle_type FROM $t_exercise_index_muscles WHERE exercise_muscle_exercise_id=$get_exercise_id";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_array($result)) {
		array_push($exercise_array['muscles'],$row);
	}


	// exercise_index_muscles_images
	$exercise_array['muscles_images'] = array();
	$query = "SELECT exercise_muscle_image_id, exercise_muscle_image_exercise_id, exercise_muscle_image_file FROM $t_exercise_index_muscles_images WHERE exercise_muscle_image_exercise_id=$get_exercise_id";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_array($result)) {
		array_push($exercise_array['muscles_images'],$row);
	}

	// Json everything
	$rows_json = json_encode(utf8ize($exercise_array));

	echo"$rows_json";
} else {
	// the exercise was not found
	// Are there more exercises?

	$query = "SELECT exercise_id FROM $t_exercise_index WHERE exercise_id > $exercise_id_mysql ORDER BY exercise_id ASC LIMIT 1";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_exercise_id) = $row;

	if($get_exercise_id != ""){
		echo"Exercise not found.Please look for next exercise";
	}
	else{
		echo"Exercise not found.No more exercises";
	}
}



?>