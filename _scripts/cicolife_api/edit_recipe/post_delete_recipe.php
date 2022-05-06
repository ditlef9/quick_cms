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
$t_recipes_numbers	= $mysqlPrefixSav . "recipes_numbers";
$t_recipes_rating	= $mysqlPrefixSav . "recipes_rating";


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


// Find user
$query = "SELECT user_id FROM $t_users WHERE user_id=$inp_user_id_mysql AND user_password=$inp_user_password_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_user_id) = $row;
if($get_user_id == ""){
	$fm = "User not found";
}

// Find recipe
$query = "SELECT recipe_id, recipe_image_path, recipe_image, recipe_thumb FROM $t_recipes WHERE recipe_id=$inp_recipe_id_mysql AND recipe_password=$inp_recipe_password_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_recipe_id, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb) = $row;
if($get_recipe_id == ""){
	$fm = "Recipe not found";
}

	


if($fm == ""){
	$inp_ip = $_SERVER['REMOTE_ADDR'];
	$inp_ip = output_html($inp_ip);
	$inp_ip_mysql = quote_smart($link, $inp_ip);
		



	// Delete recipe
	mysqli_query($link, "DELETE FROM $t_recipes WHERE recipe_id='$get_recipe_id'") or die(mysqli_error($link));
	mysqli_query($link, "DELETE FROM $t_recipes_groups WHERE group_recipe_id='$get_recipe_id'") or die(mysqli_error($link));
	mysqli_query($link, "DELETE FROM $t_recipes_items WHERE item_recipe_id='$get_recipe_id'") or die(mysqli_error($link));
	mysqli_query($link, "DELETE FROM $t_recipes_numbers WHERE number_recipe_id='$get_recipe_id'") or die(mysqli_error($link));
	mysqli_query($link, "DELETE FROM $t_recipes_rating WHERE rating_recipe_id='$get_recipe_id'") or die(mysqli_error($link));

	// Delete images
	if($get_recipe_image_path != "" && $get_recipe_image != ""){
		if(file_exists("../../../../$get_recipe_image_path/$get_recipe_image")){
			unlink("../../../../$get_recipe_image_path/$get_recipe_image");
		}
	}
	if($get_recipe_image_path != "" && $get_recipe_thumb != ""){
		if(file_exists("../../../../$get_recipe_image_path/$get_recipe_thumb")){
			unlink("../../../../$get_recipe_image_path/$get_recipe_thumb");
		}
	}


	// Get recipe ID
	echo"Deleted $get_recipe_id";

}
else{
	echo"$fm";
}

?>