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


if(isset($_POST['inp_calories_per_hundred'])){
	$inp_calories_per_hundred = $_POST['inp_calories_per_hundred'];
	$inp_calories_per_hundred = output_html($inp_calories_per_hundred);
}
else{
	$inp_calories_per_hundred = "";
}
$inp_calories_per_hundred_mysql = quote_smart($link, $inp_calories_per_hundred);


if(isset($_POST['inp_proteins_per_hundred'])){
	$inp_proteins_per_hundred = $_POST['inp_proteins_per_hundred'];
	$inp_proteins_per_hundred = output_html($inp_proteins_per_hundred);
}
else{
	$inp_proteins_per_hundred = "";
}
$inp_proteins_per_hundred_mysql = quote_smart($link, $inp_proteins_per_hundred);

if(isset($_POST['inp_fat_per_hundred'])){
	$inp_fat_per_hundred = $_POST['inp_fat_per_hundred'];
	$inp_fat_per_hundred = output_html($inp_fat_per_hundred);
}
else{
	$inp_fat_per_hundred = "";
}
$inp_fat_per_hundred_mysql = quote_smart($link, $inp_fat_per_hundred);

if(isset($_POST['inp_carbs_per_hundred'])){
	$inp_carbs_per_hundred = $_POST['inp_carbs_per_hundred'];
	$inp_carbs_per_hundred = output_html($inp_carbs_per_hundred);
}
else{
	$inp_carbs_per_hundred = "";
}
$inp_carbs_per_hundred_mysql = quote_smart($link, $inp_carbs_per_hundred);




/*
if(isset($_POST['inp_total_weight'])){
	$inp_total_weight = $_POST['inp_total_weight'];
	$inp_total_weight = output_html($inp_total_weight);
}
else{
	$inp_total_weight = "";
}
$inp_total_weight_mysql = quote_smart($link, $inp_total_weight);
*/

if(isset($_POST['inp_total_calories'])){
	$inp_total_calories = $_POST['inp_total_calories'];
	$inp_total_calories = output_html($inp_total_calories);
}
else{
	$inp_total_calories = "";
}
$inp_total_calories_mysql = quote_smart($link, $inp_total_calories);

if(isset($_POST['inp_total_proteins'])){
	$inp_total_proteins = $_POST['inp_total_proteins'];
	$inp_total_proteins = output_html($inp_total_proteins);
}
else{
	$inp_total_proteins = "";
}
$inp_total_proteins_mysql = quote_smart($link, $inp_total_proteins);

if(isset($_POST['inp_total_fat'])){
	$inp_total_fat = $_POST['inp_total_fat'];
	$inp_total_fat = output_html($inp_total_fat);
}
else{
	$inp_total_fat = "";
}
$inp_total_fat_mysql = quote_smart($link, $inp_total_fat);

if(isset($_POST['inp_total_carbs'])){
	$inp_total_carbs = $_POST['inp_total_carbs'];
	$inp_total_carbs = output_html($inp_total_carbs);
}
else{
	$inp_total_carbs = "";
}
$inp_total_carbs_mysql = quote_smart($link, $inp_total_carbs);


if($fm == ""){
	$inp_ip = $_SERVER['REMOTE_ADDR'];
	$inp_ip = output_html($inp_ip);
	$inp_ip_mysql = quote_smart($link, $inp_ip);
	
	// Echo
	//echo"UPDATE $t_recipes SET recipe_calories_per_hundred=$inp_calories_per_hundred_mysql, recipe_proteins_per_hundred=$inp_recipe_proteins_per_hundred_mysql, recipe_fat_per_hundred=$inp_recipe_fat_per_hundred_mysql, recipe_carbs_per_hundred=$inp_recipe_carbs_per_hundred_mysql, recipe_total_calories=$inp_recipe_total_calories_mysql, recipe_total_proteins=$inp_recipe_total_proteins_mysql, recipe_total_fat=$inp_recipe_total_fat_mysql, recipe_total_carbs=$inp_recipe_total_carbs_mysql, recipe_user_ip=$inp_ip_mysql WHERE recipe_id=$inp_recipe_id_mysql\n\n";
		
	// Update recipe
	mysqli_query($link, "UPDATE $t_recipes SET recipe_calories_per_hundred=$inp_calories_per_hundred_mysql, recipe_proteins_per_hundred=$inp_proteins_per_hundred_mysql, recipe_fat_per_hundred=$inp_fat_per_hundred_mysql, recipe_carbs_per_hundred=$inp_carbs_per_hundred_mysql, recipe_total_calories=$inp_total_calories_mysql, recipe_total_proteins=$inp_total_proteins_mysql, recipe_total_fat=$inp_total_fat_mysql, recipe_total_carbs=$inp_total_carbs_mysql, recipe_user_ip=$inp_ip_mysql WHERE recipe_id=$inp_recipe_id_mysql") or die(mysqli_error($link));



	// Get recipe ID
	$query = "SELECT recipe_id FROM $t_recipes WHERE recipe_id=$inp_recipe_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_recipe_id) = $row;


	echo"$get_recipe_id";

}
else{
	echo"$fm";
}

?>