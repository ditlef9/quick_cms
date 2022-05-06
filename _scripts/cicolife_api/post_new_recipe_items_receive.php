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
$t_recipes_items	= $mysqlPrefixSav . "recipes_items";


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

if(isset($_POST['inp_items_count'])){
	$inp_items_count = $_POST['inp_items_count'];
	$inp_items_count = output_html($inp_items_count);

	if(empty($inp_items_count)){
		$fm = "inp_items_count is empty";
	}
}
else{
	$fm = "Missing inp_items_count";
}


if($fm == ""){
	$inp_ip = $_SERVER['REMOTE_ADDR'];
	$inp_ip_mysql = quote_smart($link, $inp_ip);


	for($x=0;$x<$inp_items_count;$x++){

		$inp_group_id = $_POST["inp_group_id_$x"];
		$inp_group_id = output_html($inp_group_id);
		$inp_group_id_mysql = quote_smart($link, $inp_group_id);


		$inp_amount = $_POST["inp_amount_$x"];
		$inp_amount = output_html($inp_amount);
		$inp_amount_mysql = quote_smart($link, $inp_amount);

		$inp_measurement = $_POST["inp_measurement_$x"];
		$inp_measurement = output_html($inp_measurement);
		$inp_measurement_mysql = quote_smart($link, $inp_measurement);

		$inp_grocery = $_POST["inp_grocery_$x"];
		$inp_grocery = output_html($inp_grocery);
		$inp_grocery_mysql = quote_smart($link, $inp_grocery);

		$inp_calories_per_hundred = $_POST["inp_calories_per_hundred_$x"];
		$inp_calories_per_hundred = output_html($inp_calories_per_hundred);
		$inp_calories_per_hundred_mysql = quote_smart($link, $inp_calories_per_hundred);

		$inp_calories_calculated = $_POST["inp_calories_calculated_$x"];
		$inp_calories_calculated = output_html($inp_calories_calculated);
		$inp_calories_calculated_mysql = quote_smart($link, $inp_calories_calculated);

		// Insert recipes_items
		mysqli_query($link, "INSERT INTO $t_recipes_items
		(item_id, item_recipe_id, item_group_id, item_amount, item_measurement, item_grocery, item_calories_per_hundred, item_calories_calculated) 
		VALUES 
		(NULL, $inp_recipe_id_mysql, $inp_group_id_mysql, $inp_amount_mysql, $inp_measurement_mysql, $inp_grocery_mysql, $inp_calories_per_hundred_mysql, $inp_calories_calculated_mysql)")
		or die(mysqli_error($link));

	}


	// Create array
	$array_rows = array();

	// Get recipes_items
	$query = "SELECT * FROM $t_recipes_items WHERE item_recipe_id=$inp_recipe_id_mysql";
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