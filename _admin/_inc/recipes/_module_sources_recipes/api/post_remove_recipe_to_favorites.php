<?php
/*- Functions ------------------------------------------------------------------------- */
include("../../_admin/_functions/output_html.php");
include("../../_admin/_functions/clean.php");
include("../../_admin/_functions/quote_smart.php");

/*- Config ----------------------------------------------------------------------------- */
include("../../_admin/_data/config/meta.php");
include("../../_admin/_data/config/user_system.php");
include("../../_admin/_data/logo.php");

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

/*- API Password --------------------------------------------------------------------- */
$apiPasswordSav = "w7Vdwenb";

/*- MySQL Tables ---------------------------------------------------------------------- */
$t_users 	 	= $mysqlPrefixSav . "users";
$t_users_profile_photo	= $mysqlPrefixSav . "users_profile_photo";
$t_users_email_subscriptions 	= $mysqlPrefixSav . "users_email_subscriptions";
$t_users_moderator_of_the_week	= $mysqlPrefixSav . "users_moderator_of_the_week";
$t_recipes			= $mysqlPrefixSav . "recipes";
$t_recipes_favorites		= $mysqlPrefixSav . "recipes_favorites";


/*- Find user ------------------------------------------------------------------------- */
if(isset($_POST['inp_api_password'])){
	$inp_api_password = $_POST['inp_api_password'];
	$inp_api_password = output_html($inp_api_password);
}
else{
	echo"Missing inp_api_password";
	die;
}
if($inp_api_password != "$apiPasswordSav"){
	echo"Wrong api password";
	die;
}

if(isset($_POST['inp_user_id'])){
	$inp_user_id = $_POST['inp_user_id'];
	$inp_user_id = output_html($inp_user_id);
}
else{
	echo"Missing user id";
	die;
}
$inp_user_id_mysql = quote_smart($link, $inp_user_id);

if(isset($_POST['inp_user_password'])){
	$inp_user_password = $_POST['inp_user_password']; // Already encrypted
}
else{
	echo"Missing user password";
	die;
}

if(isset($_POST['inp_recipe_id'])){
	$inp_recipe_id = $_POST['inp_recipe_id'];
	$inp_recipe_id = output_html($inp_recipe_id);
}
else{
	echo"Missing recipe id";
	die;
}
$inp_recipe_id_mysql = quote_smart($link, $inp_recipe_id);

// Check for user
$query = "SELECT user_id, user_password, user_email, user_alias, user_date_format FROM $t_users WHERE user_id=$inp_user_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_user_id, $get_user_password, $get_my_user_email, $get_my_user_alias, $get_my_user_date_format) = $row;

if($get_user_id == ""){
	echo"User id";
	die;
}
if($get_user_password != "$inp_user_password"){
	echo"Wrong user password";
	die;
}	

// Find recipe
$query = "SELECT recipe_id, recipe_user_id, recipe_language FROM $t_recipes WHERE recipe_id=$inp_recipe_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_recipe_id, $get_recipe_user_id, $get_recipe_language) = $row;
if($get_recipe_id == ""){
	echo"Recipe not found";
	die;
}


// Check if favorite exists
$query = "SELECT recipe_favorite_id FROM $t_recipes_favorites ";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_recipe_favorite_id) = $row;
if($get_recipe_favorite_id != ""){

	$result = mysqli_query($link, "DELETE FROM $t_recipes_favorites WHERE recipe_favorite_recipe_id=$get_recipe_id AND recipe_favorite_user_id=$get_user_id") or die(mysqli_error($link));

	echo"Favorite deleted";
}
else{
	
	echo"Favorite doesnt exits";
}

?>