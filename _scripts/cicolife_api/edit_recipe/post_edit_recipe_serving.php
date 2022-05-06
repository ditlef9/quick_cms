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


if(isset($_POST['inp_servings'])){
	$inp_servings = $_POST['inp_servings'];
	$inp_servings = output_html($inp_servings);
	$inp_servings_mysql = quote_smart($link, $inp_servings);

	if(empty($inp_servings)){
		$fm = "inp_servings is empty";
	}
}
else{
	$fm = "Missing inp_servings";
}

if(isset($_POST['inp_serving_calories'])){
	$inp_serving_calories = $_POST['inp_serving_calories'];
	$inp_serving_calories = output_html($inp_serving_calories);
	$inp_serving_calories_mysql = quote_smart($link, $inp_serving_calories);

	if(empty($inp_serving_calories)){
		$fm = "inp_serving_calories is empty";
	}
}
else{
	$fm = "Missing inp_serving_calories";
}


if($fm == ""){
	
		
	// Update recipe
	mysqli_query($link, "UPDATE $t_recipes_numbers SET number_serving_calories=$inp_serving_calories_mysql, number_servings=$inp_servings_mysql WHERE number_recipe_id=$inp_recipe_id_mysql");


	// echo"UPDATE $t_recipes_numbers SET number_serving_calories=$inp_serving_calories_mysql, number_servings=$inp_servings_mysql WHERE number_recipe_id=$inp_recipe_id_mysql";

	echo"ok";
}
else{
	echo"$fm";
}

?>