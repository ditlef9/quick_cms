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

$query = "SELECT $t_muscle_part_of.muscle_part_of_id, $t_muscle_part_of.muscle_part_of_latin_name, $t_muscle_part_of.muscle_part_of_latin_name_clean, $t_muscle_part_of.muscle_part_of_name, $t_muscle_part_of.muscle_part_of_name_clean, $t_muscle_part_of.muscle_part_of_muscle_group_id_main, $t_muscle_part_of.muscle_part_of_muscle_group_id_sub, $t_muscle_part_of.muscle_part_of_image_path, $t_muscle_part_of.muscle_part_of_image_file, ";
$query = $query . "$t_muscle_part_of_translations.muscle_part_of_translation_id, $t_muscle_part_of_translations.muscle_part_of_translation_name, $t_muscle_part_of_translations.muscle_part_of_translation_text ";
$query = $query . "FROM $t_muscle_part_of ";
$query = $query . "JOIN $t_muscle_part_of_translations ON $t_muscle_part_of.muscle_part_of_id=$t_muscle_part_of_translations.muscle_part_of_translation_muscle_part_of_id ";
$query = $query . "WHERE $t_muscle_part_of_translations.muscle_part_of_translation_language=$l_mysql";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_array($result)) {
	$rows_array[] = $row;	
}


// Json everything
$rows_json = json_encode($rows_array);

echo"$rows_json";


?>