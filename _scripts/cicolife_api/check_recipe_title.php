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


/*- MySQL Tables -------------------------------------------------- */
$t_users 	 		= $mysqlPrefixSav . "users";
$t_recipes		= $mysqlPrefixSav . "recipes";

/*- Variables ------------------------------------------------------------------------ */



if(isset($_POST['inp_user_id'])){
	$inp_user_id = $_POST['inp_user_id'];
	$inp_user_id = output_html($inp_user_id);
	$inp_user_id_mysql = quote_smart($link, $inp_user_id);

	// Check if it alreaddy exists
	$query = "SELECT user_id FROM $t_users WHERE user_id=$inp_user_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_user_id) = $row;

	if($get_user_id == ""){
		$fm = "User not found";
	}

}
else{
	$fm = "Missing user_id";
}


if(isset($_POST['inp_title'])){
	$inp_title = $_POST['inp_title'];
	$inp_title = output_html($inp_title);
	$inp_title_mysql = quote_smart($link, $inp_title);

	if(empty($inp_title)){
		$fm = "Title is empty";
	}
	if($inp_title == "recipe_title"){
		$fm = "inp_title cannot be recipe_title";
	}
}
else{
	$fm = "Missing title";
}

if($fm == "") {
	
	$query = "SELECT recipe_id FROM $t_recipes WHERE recipe_user_id=$inp_user_id_mysql AND recipe_title=$inp_title_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_recipe_id) = $row;


	if($get_recipe_id == ""){
		echo"Recipe is available";
	}
	else{
		echo"Duplicate recipe title";
	}

}
else{
	echo"$fm";
}



?>