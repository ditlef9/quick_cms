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
$t_recipes 		= $mysqlPrefixSav . "recipes";
$t_recipes_numbers 	= $mysqlPrefixSav . "recipes_numbers";
$t_recipes_rating 	= $mysqlPrefixSav . "recipes_rating";


/*- Script start --------------------------------------------------------------------- */
if(isset($_GET['start']) && isset($_GET['stop'])) {
	$start = $_GET['start'];
	$start = strip_tags(stripslashes($start));
	if(!(is_numeric($start))){
		$start = 0;
	}

	$stop = $_GET['stop'];
	$stop = strip_tags(stripslashes($stop));
	if(!(is_numeric($stop))){
		$stop = 0;
	}

	$rows_array = array();

	// Recipes, Numbers
	$query = "SELECT * FROM $t_recipes INNER JOIN $t_recipes_numbers ON $t_recipes.recipe_id=$t_recipes_numbers.number_recipe_id INNER JOIN $t_recipes_rating ON $t_recipes.recipe_id=$t_recipes_rating.rating_recipe_id WHERE $t_recipes.recipe_id BETWEEN $start AND $stop";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_array($result,MYSQL_ASSOC)) {
		array_push($rows_array,$row);
	}

	// Json everything
	$rows_json = json_encode($rows_array);

	echo"$rows_json";

} // start stop


?>