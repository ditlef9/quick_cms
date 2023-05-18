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
$l_mysql = quote_smart($link, $l);

if(isset($_GET['start'])) {
	$start = $_GET['start'];
	$start = strip_tags(stripslashes($start));
}
else{
	$start = "";
}
$start_mysql = quote_smart($link, $start);

if(isset($_GET['stop'])) {
	$stop = $_GET['stop'];
	$stop = strip_tags(stripslashes($stop));
}
else{
	$stop = "";
}
$stop_mysql = quote_smart($link, $stop);


/*- Get recipe ------------------------------------------------------------------------- */

// Build array
$rows_array = array();

$query = "SELECT equipment_id, equipment_title, equipment_title_clean, equipment_user_id, equipment_language, equipment_muscle_group_id_main, equipment_muscle_group_id_sub, equipment_muscle_part_of_id, equipment_type_id, equipment_text, equipment_image_path, equipment_image_file, equipment_created_datetime, equipment_updated_datetime, equipment_uniqe_hits, equipment_likes, equipment_dislikes, equipment_rating, equipment_number_of_comments, equipment_reported, equipment_reported_checked, equipment_reported_reason FROM $t_exercise_equipments WHERE equipment_id BETWEEN $start_mysql AND $stop_mysql AND equipment_language=$l_mysql";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_array($result)) {
	$rows_array[] = $row;	
}


// Json everything
$rows_json = json_encode(utf8ize($rows_array));

echo"$rows_json";

?>