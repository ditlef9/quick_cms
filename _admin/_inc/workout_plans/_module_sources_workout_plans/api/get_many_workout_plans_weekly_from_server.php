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

$q = "SELECT workout_weekly_id FROM $t_workout_plans_weekly WHERE workout_weekly_id BETWEEN $start_mysql AND $stop_mysql AND workout_weekly_language=$l_mysql";
$r = mysqli_query($link, $q);
while($rows = mysqli_fetch_row($r)) {
	list($get_workout_weekly_id) = $rows;

	$workout_plan_weekly_array = array();


	// Workout plan
	$query_rating = "SELECT workout_weekly_id, workout_weekly_user_id, workout_weekly_period_id, workout_weekly_weight, workout_weekly_language, workout_weekly_title, workout_weekly_title_clean, workout_weekly_introduction, workout_weekly_goal, workout_weekly_image_path, workout_weekly_image_thumb_medium, workout_weekly_image_thumb_big, workout_weekly_image_file, workout_weekly_created, workout_weekly_updated, workout_weekly_unique_hits, workout_weekly_unique_hits_ip_block, workout_weekly_comments, workout_weekly_likes, workout_weekly_dislikes, workout_weekly_rating, workout_weekly_ip_block, workout_weekly_user_ip, workout_weekly_notes, workout_weekly_number_of_sessions FROM $t_workout_plans_weekly WHERE workout_weekly_id=$get_workout_weekly_id";
	$result_rating = mysqli_query($link, $query_rating);
	$row_rating = mysqli_fetch_array($result_rating);
	$workout_plan_weekly_array['workout_plan_weekly'] = $row_rating;


	// Tags
	$workout_plan_weekly_array['tags'] = array();
	$query_tags = "SELECT * FROM $t_workout_plans_weekly_tags WHERE tag_weekly_id=$get_workout_weekly_id";
	$result_tags = mysqli_query($link, $query_tags);
	while($row_tags = mysqli_fetch_array($result_tags)) {
		array_push($workout_plan_weekly_array['tags'],$row_tags);
	}

	array_push($rows_array, $workout_plan_weekly_array);

}
// Json everything
$rows_json = json_encode(utf8ize($rows_array));

echo"$rows_json";



?>