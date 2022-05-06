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
$t_recipes_categories = $mysqlPrefixSav . "recipes_categories";
$t_recipes_categories_translations = $mysqlPrefixSav . "recipes_categories_translations";


/*- Script start --------------------------------------------------------------------- */

if(isset($_GET['l'])) {
	$l = $_GET['l'];
	$l = strip_tags(stripslashes($l));
	$l_mysql = quote_smart($link, $l);

	$rows_array = array();
	$query = "SELECT * FROM $t_recipes_categories_translations WHERE category_translation_language=$l_mysql";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_array($result,MYSQL_ASSOC)) {
		array_push($rows_array,$row);
	}

	// Json everything
	$rows_json = json_encode($rows_array);
	echo"$rows_json";
}


?>