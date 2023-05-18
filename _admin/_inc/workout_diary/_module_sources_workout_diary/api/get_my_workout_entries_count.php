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


/*- MySQL Tables ---------------------------------------------------------------------- */

$t_users 	 		= $mysqlPrefixSav . "users";
$t_users_profile 		= $mysqlPrefixSav . "users_profile";
$t_users_profile_photo 		= $mysqlPrefixSav . "users_profile_photo";

/*- Workout plans ------------------------------------------------------------- */
$t_workout_plans_yearly  				= $mysqlPrefixSav . "workout_plans_yearly";
$t_workout_plans_period  				= $mysqlPrefixSav . "workout_plans_period";
$t_workout_plans_weekly  				= $mysqlPrefixSav . "workout_plans_weekly";
$t_workout_plans_sessions 				= $mysqlPrefixSav . "workout_plans_sessions";
$t_workout_plans_sessions_main 				= $mysqlPrefixSav . "workout_plans_sessions_main";
$t_workout_plans_favorites 				= $mysqlPrefixSav . "workout_plans_favorites";

/*- Workout diary ------------------------------------------------------------- */
$t_workout_diary_entries 	= $mysqlPrefixSav . "workout_diary_entries";
$t_workout_diary_plans 		= $mysqlPrefixSav . "workout_diary_plans";



/*- Variables ------------------------------------------------------------------------- */
$fm = "";

if(isset($_GET['user_id'])){
	$user_id = $_GET['user_id'];
	$user_id = output_html($user_id);
	$user_id_mysql = quote_smart($link, $user_id);

	// Check if it alreaddy exists
	$query = "SELECT user_id FROM $t_users WHERE user_id=$user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id) = $row;

	if($get_user_id == ""){
		echo"Unknown user $user_id";
		die;
	}

}
else{
	echo"Missing email";
	die;
}




$query = "SELECT count(*) FROM $t_workout_diary_entries WHERE workout_diary_entry_user_id=$user_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_count) = $row;
echo"$get_count";


?>