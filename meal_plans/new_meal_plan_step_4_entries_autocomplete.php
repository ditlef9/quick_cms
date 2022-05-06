<?php
header('Content-type: text/html; charset=windows-1252;');


/*- Root */
$root = ".";

/*- Functions ------------------------------------------------------------------------ */
include("$root/_admin/_functions/output_html.php");
include("$root/_admin/_functions/clean.php");
include("$root/_admin/_functions/clean_dir_reverse.php");
include("$root/_admin/_functions/create_user_thumb.php");
include("$root/_admin/_functions/create_thumb.php");
include("$root/_admin/_functions/resize_crop_image.php");
include("$root/_admin/_functions/quote_smart.php");
include("$root/_admin/_functions/page_url.php");

/*- Common variables ----------------------------------------------------------------- */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);


/*- MySQL ---------------------------------------------------------------------------- */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);
$mysql_config_file = "_admin/_data/mysql_" . $server_name . ".php";

include("$mysql_config_file");
$link = mysqli_connect($mysqlHostSav, $mysqlUserNameSav, $mysqlPasswordSav, $mysqlDatabaseNameSav);
if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


/*- Tables --------------------------------------------------------------------------- */
$t_diet_food  = $mysqlPrefixSav . "diet_food";


if(isset($_GET['term']) && $_GET['term'] != ''){

	$data = "";

	$term = $_GET['term'];
	$term = strip_tags(stripslashes($term));
	$term = trim($term);
	$term = strtolower($term);
	$term = $term . "%";
	$part_mysql = quote_smart($link, $term);


	//get matched data from skills table
	$query = "SELECT * FROM $t_diet_food WHERE food_name LIKE $part_mysql ORDER BY food_unique_hits DESC LIMIT 0,8";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
    		$data[] = $row[0];
	}
	//return json data
	echo json_encode($data);
}
?>