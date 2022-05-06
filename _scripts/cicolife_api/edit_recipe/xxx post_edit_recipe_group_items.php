<?php
/*- Functions ------------------------------------------------------------------------- */
include("../../../_admin/_functions/output_html.php");
include("../../../_admin/_functions/clean.php");
include("../../../_admin/_functions/quote_smart.php");


/*- MySQL ----------------------------------------------------------------------------- */
$server_name = $_SERVER['HTTP_HOST'];
$server_name = clean($server_name);

$mysql_config_file = "../../../_admin/_data/mysql_" . $server_name . ".php";
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
}
else{
	echo"Missing inp_recipe_id";
}
$inp_recipe_id_mysql = quote_smart($link, $inp_recipe_id);


if(isset($_POST['inp_recipe_password'])){
	$inp_recipe_password = $_POST['inp_recipe_password'];
	$inp_recipe_password = output_html($inp_recipe_password);
}
else{
	echo"Missing inp_recipe_password";
}
$inp_recipe_password_mysql = quote_smart($link, $inp_recipe_password);

if(isset($_POST['inp_group_id'])){
	$inp_group_id = $_POST['inp_group_id'];
	$inp_group_id = output_html($inp_group_id);
}
else{
	echo"Missing inp_group_id";
}
$inp_group_id_mysql = quote_smart($link, $inp_group_id);

if(isset($_POST['inp_group_title'])){
	$inp_group_title = $_POST['inp_group_title'];
	$inp_group_title = output_html($inp_group_title);
}
else{
	echo"Missing inp_group_title";
}
$inp_group_title_mysql = quote_smart($link, $inp_group_title);

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


// Look for recipe
$query = "SELECT recipe_id, recipe_password FROM $t_recipes WHERE recipe_id=$inp_recipe_id_mysql AND recipe_password=$inp_recipe_password_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_recipe_id, $get_recipe_password) = $row;
if($get_recipe_id == ""){
	$fm = "Recipe not found";
}
// Look for group
$query = "SELECT group_id FROM $t_recipes_groups WHERE group_id=$inp_group_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_group_id) = $row;
if($get_group_id == ""){
	$fm = "Group not found";
}




if($fm == ""){
	$inp_ip = $_SERVER['REMOTE_ADDR'];
	$inp_ip = output_html($inp_ip);
	$inp_ip_mysql = quote_smart($link, $inp_ip);
	
	// Update group
	mysqli_query($link, "UPDATE $t_recipes_groups SET group_title=$inp_group_title_mysql WHERE group_id=$inp_group_id_mysql") or die(mysqli_error($link));


	for($x=0;$x<$inp_items_count;$x++){

		if(isset($_POST["inp_item__id_$x"])){
			$inp_item__id = $_POST["inp_item__id_$x"];
			$inp_item__id = output_html($inp_item__id);
			$inp_item__id_mysql = quote_smart($link, $inp_item__id);
		}

		if(isset($_POST["inp_item_id_$x"])){
			$inp_item_id = $_POST["inp_item_id_$x"];
			$inp_item_id = output_html($inp_item_id);
			$inp_item_id_mysql = quote_smart($link, $inp_item_id);
		}

		$inp_amount = $_POST["inp_amount_$x"];
		$inp_amount = output_html($inp_amount);
		$inp_amount_mysql = quote_smart($link, $inp_amount);

		$inp_measurement = $_POST["inp_measurement_$x"];
		$inp_measurement = output_html($inp_measurement);
		$inp_measurement_mysql = quote_smart($link, $inp_measurement);

		$inp_grocery = $_POST["inp_grocery_$x"];
		$inp_grocery = output_html($inp_grocery);
		$inp_grocery_mysql = quote_smart($link, $inp_grocery);

		if(isset($inp_item__id)){
			// Insert recipes_items
			mysqli_query($link, "INSERT INTO $t_recipes_items
			(item_id, item_recipe_id, item_group_id, item_amount, item_measurement, item_grocery) 
			VALUES 
			(NULL, $inp_recipe_id_mysql, $inp_group_id_mysql, $inp_amount_mysql, $inp_measurement_mysql, $inp_grocery_mysql)")
			or die(mysqli_error($link));
		}
		else{
			// Update
			mysqli_query($link, "UPDATE $t_recipes_items SET item_amount=$inp_amount_mysql, item_measurement=$inp_measurement_mysql, item_grocery=$inp_grocery_mysql WHERE item_id=$inp_item_id_mysql") or die(mysqli_error($link));


			
		}
	}
	
	// Get all the items
	

	// Create array
	$array_rows = array();

	// Get recipes_items
	$query = "SELECT * FROM $t_recipes_items WHERE item_group_id=$inp_group_id_mysql";
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