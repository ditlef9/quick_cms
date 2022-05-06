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

$t_meal_plans 		= $mysqlPrefixSav . "meal_plans";
$t_meal_plans_days	= $mysqlPrefixSav . "meal_plans_days";
$t_meal_plans_meals	= $mysqlPrefixSav . "meal_plans_meals";
$t_meal_plans_entries	= $mysqlPrefixSav . "meal_plans_entries";

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


$x=0;
$q = "SELECT meal_plan_id FROM $t_meal_plans WHERE meal_plan_id BETWEEN $start_mysql AND $stop_mysql AND meal_plan_language=$l_mysql";
$r = mysqli_query($link, $q);
while($rows = mysqli_fetch_row($r)) {
	list($get_meal_plan_id) = $rows;

	$exercise_array = array();

	// Meal plan
	$query = "SELECT * FROM $t_meal_plans WHERE meal_plan_id=$get_meal_plan_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_array($result);
	$exercise_array['meal_plan'] = $row;



	// days
	$exercise_array['days'] = array();
	$query = "SELECT * FROM $t_meal_plans_days WHERE day_meal_plan_id=$get_meal_plan_id";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_array($result)) {
		array_push($exercise_array['days'],$row);
	}


	// entries
	$exercise_array['entries'] = array();
	$query = "SELECT * FROM $t_meal_plans_entries WHERE entry_meal_plan_id=$get_meal_plan_id";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_array($result)) {
		array_push($exercise_array['entries'],$row);
	}


	// meals
	$exercise_array['meals'] = array();
	$query = "SELECT * FROM $t_meal_plans_meals WHERE meal_meal_plan_id=$get_meal_plan_id";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_array($result)) {
		array_push($exercise_array['meals'],$row);
	}
	array_push($rows_array,$exercise_array);
	
}


// Json everything
$rows_json = json_encode(utf8ize($rows_array));

echo"$rows_json";

?>