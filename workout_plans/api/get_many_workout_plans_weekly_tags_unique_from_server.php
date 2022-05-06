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
$t_workout_plans_yearly  		= $mysqlPrefixSav . "workout_plans_yearly";
$t_workout_plans_period  		= $mysqlPrefixSav . "workout_plans_period";
$t_workout_plans_weekly  		= $mysqlPrefixSav . "workout_plans_weekly";
$t_workout_plans_weekly_tags  		= $mysqlPrefixSav . "workout_plans_weekly_tags";
$t_workout_plans_weekly_tags_unique  	= $mysqlPrefixSav . "workout_plans_weekly_tags_unique";
$t_workout_plans_sessions 		= $mysqlPrefixSav . "workout_plans_sessions";
$t_workout_plans_sessions_main 		= $mysqlPrefixSav . "workout_plans_sessions_main";
$t_workout_plans_favorites 		= $mysqlPrefixSav . "workout_plans_favorites";
$t_users				= $mysqlPrefixSav . "users";

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


$query = "SELECT tag_unique_id, tag_unique_language, tag_unique_title, tag_unique_title_clean, tag_unique_no_of_workout_plans, tag_unique_hits, tag_unique_hits_ipblock FROM $t_workout_plans_weekly_tags_unique WHERE tag_unique_id BETWEEN $start_mysql AND $stop_mysql";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_array($result)) {
	$rows_array[] = $row;	
}
// Json everything
$rows_json = json_encode(utf8ize($rows_array));

echo"$rows_json";



?>