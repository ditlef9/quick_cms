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
$t_muscles				= $mysqlPrefixSav . "muscles";
$t_muscles_translations 		= $mysqlPrefixSav . "muscles_translations";
$t_muscle_groups 			= $mysqlPrefixSav . "muscle_groups";
$t_muscle_groups_translations	 	= $mysqlPrefixSav . "muscle_groups_translations";
$t_muscle_part_of 			= $mysqlPrefixSav . "muscle_part_of";
$t_muscle_part_of_translations	 	= $mysqlPrefixSav . "muscle_part_of_translations";

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


// Build array
$rows_array = array();

$query = "SELECT muscle_id, $t_muscles.muscle_user_id, $t_muscles.muscle_latin_name, $t_muscles.muscle_latin_name_clean, $t_muscles.muscle_simple_name, $t_muscles.muscle_short_name, $t_muscles.muscle_group_id_main, $t_muscles.muscle_group_id_sub, $t_muscles.muscle_part_of_id, $t_muscles.muscle_text, $t_muscles.muscle_image_path, $t_muscles.muscle_image_file, $t_muscles.muscle_video_path, $t_muscles.muscle_video_file, $t_muscles.muscle_video_embedded, $t_muscles.muscle_unique_hits, ";
$query = $query . "$t_muscles_translations.muscle_translation_id, $t_muscles_translations.muscle_translation_simple_name, $t_muscles_translations.muscle_translation_short_name, $t_muscles_translations.muscle_translation_text, $t_muscles_translations.muscle_translation_video_path, $t_muscles_translations.muscle_translation_video_file, $t_muscles_translations.muscle_translation_video_embedded ";
$query = $query . "FROM $t_muscles ";
$query = $query . "JOIN $t_muscles_translations ON $t_muscles.muscle_id=$t_muscles_translations.muscle_translation_muscle_id ";
$query = $query . "WHERE muscle_id BETWEEN $start_mysql AND $stop_mysql AND $t_muscles_translations.muscle_translation_language=$l_mysql";

$result = mysqli_query($link, $query);
while($row = mysqli_fetch_array($result)) {
	$rows_array[] = $row;	
}

// Json everything
$rows_json = json_encode(utf8ize($rows_array));

echo"$rows_json";


?>