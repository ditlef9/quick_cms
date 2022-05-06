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


/*- MySQL Tables -------------------------------------------------- */
$t_meal_plans 		= $mysqlPrefixSav . "meal_plans";
$t_meal_plans_days	= $mysqlPrefixSav . "meal_plans_days";
$t_meal_plans_meals	= $mysqlPrefixSav . "meal_plans_meals";
$t_meal_plans_entries	= $mysqlPrefixSav . "meal_plans_entries";

/*- Script start ------------------------------------------------------------------------ */
if(isset($_GET['l'])) {
	$l = $_GET['l'];
	$l = strip_tags(stripslashes($l));
	$l_mysql = quote_smart($link, $l);


	$query = "SELECT meal_plan_id FROM $t_meal_plans WHERE meal_plan_language=$l_mysql ORDER BY meal_plan_id DESC LIMIT 1";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_meal_plan_id) = $row;
	echo"$get_meal_plan_id";
}

?>