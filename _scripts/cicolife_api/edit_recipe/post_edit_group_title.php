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
		
		if(isset($_POST['inp_recipe_password'])){
			$inp_recipe_password = $_POST['inp_recipe_password'];
			$inp_recipe_password = output_html($inp_recipe_password);

			if($inp_recipe_password == "$get_recipe_password"){

			}
			else{
				$fm = "Wrong password";
			}
		}
		else{
			$fm = "Missing inp_recipe_password";
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
	$fm = "Missing inp_group_id";
}


if(isset($_POST['inp_group_title'])){
	$inp_group_title = $_POST['inp_group_title'];
	$inp_group_title = output_html($inp_group_title);
	$inp_group_title_mysql = quote_smart($link, $inp_group_title);
}
else{
	$fm = "Missing group title";
}


if($fm == ""){
	
	// Delete
	mysqli_query($link, "UPDATE $t_recipes_groups SET group_title=$inp_group_title_mysql WHERE group_id='$get_group_id'") or die(mysqli_error($link));
	
	// Print 
	echo"ok";

}
else{
	echo"$fm";
}

?>