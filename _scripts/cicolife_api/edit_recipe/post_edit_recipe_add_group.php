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

if(isset($_POST['inp_group_title'])){
	$inp_group_title = $_POST['inp_group_title'];
	$inp_group_title = output_html($inp_group_title);
	$inp_group_title_mysql = quote_smart($link, $inp_group_title);
}
else{
	$fm = "Missing inp_group_title";
}


// Look for recipe
$query = "SELECT recipe_id, recipe_password FROM $t_recipes WHERE recipe_id=$inp_recipe_id_mysql AND recipe_password=$inp_recipe_password_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_recipe_id, $get_recipe_password) = $row;
if($get_recipe_id == ""){
	$fm = "Recipe not found";
}



if($fm == ""){
	$inp_ip = $_SERVER['REMOTE_ADDR'];
	$inp_ip = output_html($inp_ip);
	$inp_ip_mysql = quote_smart($link, $inp_ip);
	
	// Create group
	mysqli_query($link, "INSERT INTO $t_recipes_groups
	(group_id, group_recipe_id, group_title) 
	VALUES 
	(NULL, $inp_recipe_id_mysql, $inp_group_title_mysql)")
	or die(mysqli_error($link));


	// Get group id
	$query = "SELECT * FROM $t_recipes_groups WHERE group_recipe_id=$inp_recipe_id_mysql AND group_title=$inp_group_title_mysql ORDER BY group_id DESC LIMIT 1";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_group_id, $get_group_recipe_id, $get_group_title) = $row;

	echo"$get_group_id";
}
else{
	echo"$fm";
}

?>