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
$t_recipes_items	= $mysqlPrefixSav . "recipes_items";
$t_recipes_groups	= $mysqlPrefixSav . "recipes_groups";
$t_recipes_numbers	= $mysqlPrefixSav . "recipes_numbers";


/*- Variables ------------------------------------------------------------------------- */
$fm = "";

if(isset($_POST['inp_recipe_id'])){
	$inp_recipe_id = $_POST['inp_recipe_id'];
	$inp_recipe_id = output_html($inp_recipe_id);
	$inp_recipe_id_mysql = quote_smart($link, $inp_recipe_id);

	// Check if it exists
	$query = "SELECT recipe_id, recipe_password FROM $t_recipes WHERE recipe_id=$inp_recipe_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_recipe_id, $get_recipe_password) = $row;

	if($get_recipe_id == ""){
		$fm = "Recipe not found";
	}
	else{
		
		if(isset($_POST['inp_password'])){
			$inp_password = $_POST['inp_password'];
			$inp_password = output_html($inp_password);

			if($inp_password == "$get_recipe_password"){

			}
			else{
				$fm = "Wrong password";
			}
		}
		else{
			$fm = "Missing recipe_password";
		}
	}
}
else{
	$fm = "Missing recipe_id";
}


if(isset($_POST['inp_group_id'])){
	$inp_group_id = $_POST['inp_group_id'];
	$inp_group_id = output_html($inp_group_id);
	$inp_group_id_mysql = quote_smart($link, $inp_group_id);


	// Check if it exists
	$query = "SELECT group_id FROM $t_recipes_groups WHERE group_id=$inp_group_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_group_id) = $row;


	if($get_group_id == ""){
		$fm = "Group not found";
	}
}
else{
	$fm = "Missing group_id";
}

if(isset($_POST['inp_amount'])){
	$inp_amount = $_POST['inp_amount'];
	$inp_amount = output_html($inp_amount);
	$inp_amount_mysql = quote_smart($link, $inp_amount);

	if(empty($inp_amount)){
		$fm = "Amount is empty";
	}
}
else{
	$fm = "Missing amount";
}


if(isset($_POST['inp_measurement'])){
	$inp_measurement = $_POST['inp_measurement'];
	$inp_measurement = output_html($inp_measurement);
	$inp_measurement_mysql = quote_smart($link, $inp_measurement);

	if(empty($inp_measurement)){
		$fm = "Measurement is empty";
	}
}
else{
	$fm = "Missing measurement";
}

if(isset($_POST['inp_grocery'])){
	$inp_grocery = $_POST['inp_grocery'];
	$inp_grocery = output_html($inp_grocery);
	$inp_grocery_mysql = quote_smart($link, $inp_grocery);

	if(empty($inp_grocery)){
		$fm = "Grocery is empty";
	}
}
else{
	$fm = "Missing grocery";
}

if(isset($_POST['inp_calories_per_hundred'])){
	$inp_calories_per_hundred = $_POST['inp_calories_per_hundred'];
	$inp_calories_per_hundred = output_html($inp_calories_per_hundred);
	$inp_calories_per_hundred_mysql = quote_smart($link, $inp_calories_per_hundred);
}
else{
	$inp_calories_per_hundred = "";
}
$inp_calories_per_hundred_mysql = quote_smart($link, $inp_calories_per_hundred);



if(isset($_POST['inp_calories_calculated'])){
	$inp_calories_calculated = $_POST['inp_calories_calculated'];
	$inp_calories_calculated = output_html($inp_calories_calculated);
	$inp_calories_calculated_mysql = quote_smart($link, $inp_calories_calculated);
}
else{
	$inp_calories_calculated = "";
}
$inp_calories_calculated_mysql = quote_smart($link, $inp_calories_calculated);


// Numbers
if(isset($_POST['inp_serving_calories'])){
	$inp_serving_calories = $_POST['inp_serving_calories'];
	$inp_serving_calories = output_html($inp_serving_calories);
	$inp_serving_calories_mysql = quote_smart($link, $inp_serving_calories);
}
else{
	$inp_serving_calories = "";
}
$inp_serving_calories_mysql = quote_smart($link, $inp_serving_calories);

if(isset($_POST['inp_total_calories'])){
	$inp_total_calories = $_POST['inp_total_calories'];
	$inp_total_calories = output_html($inp_total_calories);
	$inp_total_calories_mysql = quote_smart($link, $inp_total_calories);
}
else{
	$inp_total_calories = "";
}
$inp_total_calories_mysql = quote_smart($link, $inp_total_calories);



if($fm == ""){
	$inp_ip = $_SERVER['REMOTE_ADDR'];
	$inp_ip = output_html($inp_ip);
	$inp_ip_mysql = quote_smart($link, $inp_ip);
	
	// Echo
	// echo"UPDATE $t_recipes_items SET item_amount=$inp_amount_mysql, item_measurement=$inp_measurement_mysql, item_grocery=$inp_grocery_mysql, item_calories_per_hundred=$inp_calories_per_hundred_mysql, item_calories_calculated=$inp_calories_calculated_mysql, recipe_user_ip=$inp_ip_mysql WHERE item_id=$inp_item_id_mysql";
	// echo"\n";

	// Check for duplicates
	$query = "SELECT item_id FROM $t_recipes_items WHERE item_group_id=$inp_group_id_mysql AND item_grocery=$inp_grocery_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_item_id) = $row;
	if($get_item_id != ""){
		echo"There is already an item with that grocery";
	}
	else{
		// Insert item
		mysqli_query($link, "INSERT INTO $t_recipes_items
		(item_id, item_recipe_id, item_group_id, item_amount, item_measurement, item_grocery, item_calories_per_hundred, item_calories_calculated) 
		VALUES 
		(NULL, $inp_recipe_id_mysql, $inp_group_id_mysql, $inp_amount_mysql, $inp_measurement_mysql, $inp_grocery_mysql, $inp_calories_per_hundred_mysql, $inp_calories_calculated_mysql)")
		or die(mysqli_error($link));

		// Numbers
		$result = mysqli_query($link, "UPDATE $t_recipes_numbers SET number_serving_calories=$inp_serving_calories_mysql, number_total_calories=$inp_total_calories_mysql WHERE number_recipe_id=$inp_recipe_id_mysql");


		// Get id
		$query = "SELECT item_id FROM $t_recipes_items WHERE item_group_id=$inp_group_id_mysql AND item_grocery=$inp_grocery_mysql";
		$result = mysqli_query($link, $query);
		$row = mysqli_fetch_row($result);
		list($get_item_id) = $row;
		echo"$get_item_id";
	}
}
else{
	echo"$fm";
}

?>