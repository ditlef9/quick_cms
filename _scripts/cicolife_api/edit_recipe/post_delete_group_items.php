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
	$inp_recipe_id = "";
}
$inp_recipe_id_mysql = quote_smart($link, $inp_recipe_id);

if(isset($_POST['inp_recipe_password'])){
	$inp_recipe_password = $_POST['inp_recipe_password'];
	$inp_recipe_password = output_html($inp_recipe_password);
}
else{
	$inp_recipe_password = "";
}
$inp_recipe_password_mysql = quote_smart($link, $inp_recipe_password);


if(isset($_POST['inp_user_id'])){
	$inp_user_id = $_POST['inp_user_id'];
	$inp_user_id = output_html($inp_user_id);
}
else{
	$inp_user_id = "";
}
$inp_user_id_mysql = quote_smart($link, $inp_user_id);


if(isset($_POST['inp_user_password'])){
	$inp_user_password = $_POST['inp_user_password'];
	$inp_user_password = output_html($inp_user_password);
}
else{
	$inp_user_password = "";
}
$inp_user_password_mysql = quote_smart($link, $inp_user_password);

if(isset($_POST['inp_group_id'])){
	$inp_group_id = $_POST['inp_group_id'];
	$inp_group_id = output_html($inp_group_id);
}
else{
	echo"Missing inp_group_id";
}
$inp_group_id_mysql = quote_smart($link, $inp_group_id);



// Find user
$query = "SELECT user_id FROM $t_users WHERE user_id=$inp_user_id_mysql AND user_password=$inp_user_password_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_user_id) = $row;
if($get_user_id == ""){
	$fm = "User not found";
}

// Find recipe
$query = "SELECT recipe_id FROM $t_recipes WHERE recipe_id=$inp_recipe_id_mysql AND recipe_password=$inp_recipe_password_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_recipe_id) = $row;
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
	
	// Delete
	mysqli_query($link, "DELETE FROM $t_recipes_groups WHERE group_id='$get_group_id'") or die(mysqli_error($link));
	mysqli_query($link, "DELETE FROM $t_recipes_items WHERE item_group_id='$get_group_id'") or die(mysqli_error($link));

	// Print 
	echo"Deleted";

}
else{
	echo"$fm";
}

?>