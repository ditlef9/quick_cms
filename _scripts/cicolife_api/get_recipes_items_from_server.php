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
$t_recipes 		= $mysqlPrefixSav . "recipes";
$t_recipes_groups 	= $mysqlPrefixSav . "recipes_groups";
$t_recipes_items 	= $mysqlPrefixSav . "recipes_items";


/*- Script start --------------------------------------------------------------------- */
if(isset($_GET['recipe_id'])) {
	$recipe_id = $_GET['recipe_id'];
	$recipe_id = strip_tags(stripslashes($recipe_id));
	$recipe_id_mysql = quote_smart($link, $recipe_id);


	$rows_array = array();
	$query = "SELECT * FROM $t_recipes_items WHERE item_recipe_id=$recipe_id_mysql";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_array($result,MYSQL_ASSOC)) {
			array_push($rows_array,$row);
		}
		
	/*
	while($row = mysqli_fetch_row($result)) {
		list($get_recipe_id) = $row;

		echo"$get_recipe_id<br />";
	}
	*/

	// Json everything
	$rows_json = json_encode($rows_array);

	echo"$rows_json";

} // start stop


?>