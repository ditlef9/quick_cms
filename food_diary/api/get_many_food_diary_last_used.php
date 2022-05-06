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

$t_food_diary_goals 	  	= $mysqlPrefixSav . "food_diary_goals";
$t_food_diary_entires	  	= $mysqlPrefixSav . "food_diary_entires";
$t_food_diary_totals_meals  	= $mysqlPrefixSav . "food_diary_totals_meals";
$t_food_diary_totals_days  	= $mysqlPrefixSav . "food_diary_totals_days";
$t_food_diary_last_used  	= $mysqlPrefixSav . "food_diary_last_used";

$t_users			  =  $mysqlPrefixSav . "users";

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['user_id'])) {
	$user_id = $_GET['user_id'];
	$user_id = strip_tags(stripslashes($user_id));
}
else{
	$user_id = "";
}
if(isset($_GET['start'])) {
	$start = $_GET['start'];
	$start = strip_tags(stripslashes($start));
}
else{
	$start = "";
}
if(isset($_GET['stop'])) {
	$stop = $_GET['stop'];
	$stop = strip_tags(stripslashes($stop));
}
else{
	$stop = "";
}


/*- Get recipe ------------------------------------------------------------------------- */

// Build array
$rows_array = array();


// Select
$x =0;

$user_id_mysql = quote_smart($link, $user_id);

if(!(is_numeric($start))){
	echo"Start not numeric";
	die;
}
if(!(is_numeric($stop))){
	echo"Stop not numeric";
	die;
}
$start_mysql = quote_smart($link, $start);
$stop_mysql   = quote_smart($link, $stop);



// Food diary last used
$query = "SELECT * FROM $t_food_diary_last_used WHERE last_used_user_id=$user_id_mysql LIMIT $start,$stop";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_array($result)) {
	$rows_array[] = $row;	
}



// Json everything
$rows_json = json_encode(utf8ize($rows_array));

echo"$rows_json";






?>