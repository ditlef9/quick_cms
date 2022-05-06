<?php
/*- Functions ------------------------------------------------------------------------- */
include("../../_admin/_functions/output_html.php");
include("../../_admin/_functions/clean.php");
include("../../_admin/_functions/quote_smart.php");


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
$t_exercise_index 			= $mysqlPrefixSav . "exercise_index";
$t_exercise_index_images		= $mysqlPrefixSav . "exercise_index_images";
$t_exercise_index_videos		= $mysqlPrefixSav . "exercise_index_videos";
$t_exercise_index_muscles		= $mysqlPrefixSav . "exercise_index_muscles";
$t_exercise_index_muscles_images	= $mysqlPrefixSav . "exercise_index_muscles_images";
$t_exercise_equipments 			= $mysqlPrefixSav . "exercise_equipments";
$t_exercise_types			= $mysqlPrefixSav . "exercise_types";
$t_exercise_types_translations 		= $mysqlPrefixSav . "exercise_types_translations";
$t_exercise_levels			= $mysqlPrefixSav . "exercise_levels";
$t_exercise_levels_translations 	= $mysqlPrefixSav . "exercise_levels_translations";
$t_users			  	= $mysqlPrefixSav . "users";

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['l'])) {
	$l = $_GET['l'];
	$l = strip_tags(stripslashes($l));
	$l_mysql = quote_smart($link, $l);
	$query = "SELECT exercise_id FROM $t_exercise_index ORDER BY exercise_id DESC LIMIT 0,1";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_exercise_id) = $row;

	echo"$get_exercise_id";
}





?>