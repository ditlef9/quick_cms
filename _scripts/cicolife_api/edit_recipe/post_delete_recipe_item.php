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


if(isset($_POST['inp_item_id'])){
	$inp_item_id = $_POST['inp_item_id'];
	$inp_item_id = output_html($inp_item_id);
	$inp_item_id_mysql = quote_smart($link, $inp_item_id);


	// Check if it exists
	$query = "SELECT item_id FROM $t_recipes_items WHERE item_id=$inp_item_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_item_id) = $row;


	if($get_item_id == ""){
		$fm = "Item not found";
	}
}
else{
	$fm = "Missing item_id";
}




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
		
	// Update recipe
	mysqli_query($link, "DELETE FROM $t_recipes_items WHERE item_id=$inp_item_id_mysql") or die(mysqli_error($link));


		// Numbers
		$result = mysqli_query($link, "UPDATE $t_recipes_numbers SET number_serving_calories=$inp_serving_calories_mysql, number_total_calories=$inp_total_calories_mysql WHERE number_recipe_id=$inp_recipe_id_mysql");


	echo"ok";

}
else{
	echo"$fm";
}

?>