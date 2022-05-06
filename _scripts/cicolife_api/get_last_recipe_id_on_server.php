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
$t_recipes = $mysqlPrefixSav . "recipes";


/*- Script start ------------------------------------------------------------------------ */
if(isset($_GET['language'])) {
	$language = $_GET['language'];
	$language = strip_tags(stripslashes($language));
	$language_mysql = quote_smart($link, $language);


	$query = "SELECT recipe_id FROM $t_recipes WHERE recipe_language=$language_mysql ORDER BY recipe_id DESC LIMIT 1";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_recipe_id) = $row;
	echo"$get_recipe_id";
}

?>