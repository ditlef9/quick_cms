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


/*- MySQL Tables ---------------------------------------------------------------------- */
$t_users 	 	= $mysqlPrefixSav . "users";
$t_recipes		= $mysqlPrefixSav . "recipes";
$t_recipes_groups	= $mysqlPrefixSav . "recipes_groups";


/*- Variables ------------------------------------------------------------------------- */
$fm = "";

if(isset($_POST['inp_recipe_id'])){
	$inp_recipe_id = $_POST['inp_recipe_id'];
	$inp_recipe_id = output_html($inp_recipe_id);
	$inp_recipe_id_mysql = quote_smart($link, $inp_recipe_id);

	// Check if it alreaddy exists
	$query = "SELECT recipe_id FROM $t_recipes WHERE recipe_id=$inp_recipe_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_recipe_id) = $row;

	if($get_recipe_id == ""){
		$fm = "Recipe not found";
	}

}
else{
	$fm = "Missing recipe_id";
}

if(isset($_POST['inp_group_count'])){
	$inp_group_count = $_POST['inp_group_count'];
	$inp_group_count = output_html($inp_group_count);

	if(empty($inp_group_count)){
		$fm = "inp_group_count is empty";
	}
}
else{
	$fm = "Missing inp_group_count";
}


if($fm == ""){
	$inp_ip = $_SERVER['REMOTE_ADDR'];
	$inp_ip_mysql = quote_smart($link, $inp_ip);


	for($x=0;$x<$inp_group_count;$x++){
		$inp_title = $_POST["inp_group_title_$x"];
		$inp_title = output_html($inp_title);
		$inp_title_mysql = quote_smart($link, $inp_title);

		// Insert recipes_ingredients_groups
		mysqli_query($link, "INSERT INTO $t_recipes_groups
		(group_id, group_recipe_id, group_title) 
		VALUES 
		(NULL, $inp_recipe_id_mysql, $inp_title_mysql)")
		or die(mysqli_error($link));

	}


	// Create array
	$array_rows = array();

	// Get recipes_ingredients_groups 
	$query = "SELECT * FROM $t_recipes_groups WHERE group_recipe_id=$inp_recipe_id_mysql";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_array($result,MYSQL_ASSOC)) {
		array_push($array_rows,$row);
	}

	// Json everything
	$rows_json = json_encode($array_rows);
	
	// Print 
	echo"$rows_json";

}
else{
	echo"$fm";
}

?>