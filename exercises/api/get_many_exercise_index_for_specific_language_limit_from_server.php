<?php
header('Content-type: text/html; charset=utf-8');

/*- Functions ------------------------------------------------------------------------- */
include("../../_admin/_functions/output_html.php");
include("../../_admin/_functions/clean.php");
include("../../_admin/_functions/quote_smart.php");
include("../../_admin/_functions/encode_national_letters.php");
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
$l_mysql = quote_smart($link, $l);

if(isset($_GET['start'])) {
	$start = $_GET['start'];
	$start = strip_tags(stripslashes($start));
}
else{
	$start = "";
}
if(!(is_numeric($start))){
	echo"Start not numeric";
	die;
}

if(isset($_GET['stop'])) {
	$stop = $_GET['stop'];
	$stop = strip_tags(stripslashes($stop));
}
else{
	$stop = "";
}
if(!(is_numeric($stop))){
	echo"Stop not numeric";
	die;
}


/*- Get recipe ------------------------------------------------------------------------- */

// Build array
$rows_array = array();


$x=0;
$q = "SELECT exercise_id, exercise_title, exercise_language FROM $t_exercise_index WHERE exercise_language=$l_mysql LIMIT $start,$stop";
$r = mysqli_query($link, $q);
while($rows = mysqli_fetch_row($r)) {
	list($get_exercise_id, $get_exercise_title, $get_exercise_language) = $rows;

	$exercise_array = array();

	// Exercise index
	$query = "SELECT exercise_id, exercise_title, exercise_title_clean, exercise_title_alternative, exercise_user_id, exercise_language, exercise_muscle_group_id_main, exercise_muscle_group_id_sub, exercise_muscle_part_of_id, exercise_equipment_id, exercise_type_id, exercise_level_id, exercise_preparation, exercise_guide, exercise_important, exercise_created_datetime, exercise_updated_datetime, exercise_user_ip, exercise_uniqe_hits, exercise_uniqe_hits_ip_block, exercise_likes, exercise_dislikes, exercise_rating, exercise_rating_ip_block, exercise_number_of_comments, exercise_reported, exercise_reported_checked, exercise_reported_reason, exercise_last_viewed FROM $t_exercise_index WHERE exercise_id=$get_exercise_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_exercise_id, $get_exercise_title, $get_exercise_title_clean, $get_exercise_title_alternative, $get_exercise_user_id, $get_exercise_language, $get_exercise_muscle_group_id_main, $get_exercise_muscle_group_id_sub, $get_exercise_muscle_part_of_id, $get_exercise_equipment_id, $get_exercise_type_id, $get_exercise_level_id, $get_exercise_preparation, $get_exercise_guide, $get_exercise_important, $get_exercise_created_datetime, $get_exercise_updated_datetime, $get_exercise_user_ip, $get_exercise_uniqe_hits, $get_exercise_uniqe_hits_ip_block, $get_exercise_likes, $get_exercise_dislikes, $get_exercise_rating, $get_exercise_rating_ip_block, $get_exercise_number_of_comments, $get_exercise_reported, $get_exercise_reported_checked, $get_exercise_reported_reason, $get_exercise_last_viewed) = $row;

	$exercise_array['index']['exercise_id'] = "$get_exercise_id";
	$exercise_array['index']['exercise_title'] = "$get_exercise_title";
	$exercise_array['index']['exercise_title_clean'] = "$get_exercise_title_clean";
	$exercise_array['index']['exercise_title_alternative'] = "$get_exercise_title_alternative";
	$exercise_array['index']['exercise_user_id'] = "$get_exercise_user_id";
	$exercise_array['index']['exercise_language'] = "$get_exercise_language";
	$exercise_array['index']['exercise_muscle_group_id_main'] = "$get_exercise_muscle_group_id_main";
	$exercise_array['index']['exercise_muscle_group_id_sub'] = "$get_exercise_muscle_group_id_sub";
	$exercise_array['index']['exercise_muscle_part_of_id'] = "$get_exercise_muscle_part_of_id";
	$exercise_array['index']['exercise_equipment_id'] = "$get_exercise_equipment_id";
	$exercise_array['index']['exercise_type_id'] = "$get_exercise_type_id";
	$exercise_array['index']['exercise_level_id'] = "$get_exercise_level_id";
	$exercise_array['index']['exercise_preparation'] = encode_national_letters($get_exercise_preparation);
	$exercise_array['index']['exercise_preparation'] = html_entity_decode(preg_replace("/U\+([0-9A-F]{4})/", "&#x\\1;", $exercise_array['index']['exercise_preparation']), ENT_NOQUOTES, 'UTF-8');

	$exercise_array['index']['exercise_guide'] = encode_national_letters($get_exercise_guide);
	$exercise_array['index']['exercise_guide'] = html_entity_decode(preg_replace("/U\+([0-9A-F]{4})/", "&#x\\1;", $exercise_array['index']['exercise_guide']), ENT_NOQUOTES, 'UTF-8');

	$exercise_array['index']['exercise_important'] = encode_national_letters($get_exercise_important);
	$exercise_array['index']['exercise_important'] = html_entity_decode(preg_replace("/U\+([0-9A-F]{4})/", "&#x\\1;", $exercise_array['index']['exercise_important']), ENT_NOQUOTES, 'UTF-8');

	$exercise_array['index']['exercise_created_datetime'] = "$get_exercise_created_datetime";
	$exercise_array['index']['exercise_updated_datetime'] = "$get_exercise_updated_datetime";
	$exercise_array['index']['exercise_user_ip'] = "$get_exercise_user_ip";
	$exercise_array['index']['exercise_uniqe_hits'] = "$get_exercise_uniqe_hits";
	$exercise_array['index']['exercise_uniqe_hits_ip_block'] = "$get_exercise_uniqe_hits_ip_block";
	$exercise_array['index']['exercise_likes'] = "$get_exercise_likes";
	$exercise_array['index']['exercise_dislikes'] = "$get_exercise_dislikes";
	$exercise_array['index']['exercise_rating'] = "$get_exercise_rating";
	$exercise_array['index']['exercise_rating_ip_block'] = "$get_exercise_rating_ip_block";
	$exercise_array['index']['exercise_number_of_comments'] = "$get_exercise_number_of_comments";
	$exercise_array['index']['exercise_reported'] = "$get_exercise_reported";
	$exercise_array['index']['exercise_reported_checked'] = "$get_exercise_reported_checked";
	$exercise_array['index']['exercise_reported_reason'] = "$get_exercise_reported_reason";
	$exercise_array['index']['exercise_last_viewed'] = "$get_exercise_last_viewed";





	// exercise_index_images
	$exercise_array['images'] = array();
	$query = "SELECT exercise_image_id, exercise_image_user_id, exercise_image_exercise_id, exercise_image_datetime, exercise_image_type, exercise_image_path, exercise_image_file, exercise_image_thumb_small, exercise_image_thumb_medium, exercise_image_thumb_large, exercise_image_uniqe_hits FROM $t_exercise_index_images WHERE exercise_image_exercise_id=$get_exercise_id";
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
	array_push($rows_array,$exercise_array);
	
}


// Json everything


function unicode2html($string) {
    return preg_replace('/\\\\u([0-9a-z]{4})/', '&#x$1;', $string);
}



$rows_json = json_encode(utf8ize($rows_array));

echo"$rows_json";

?>