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
	$fm = "Missing inp_title";
}

if(isset($_POST['inp_introduction'])){
	$inp_introduction = $_POST['inp_introduction'];
	$inp_introduction = output_html($inp_introduction);
	$inp_introduction_mysql = quote_smart($link, $inp_introduction);

	if(empty($inp_introduction)){
		$fm = "Introduction is empty";
	}
}
else{
	$fm = "Missing introduction";
}




if($fm == ""){
	$inp_ip = $_SERVER['REMOTE_ADDR'];
	$inp_ip = output_html($inp_ip);
	$inp_ip_mysql = quote_smart($link, $inp_ip);
	
	// Echo
	//echo"UPDATE $t_recipes SET recipe_title=$inp_title_mysql, recipe_introduction=$inp_introduction_mysql, recipe_user_ip=$inp_ip_mysql, recipe_user_ip=$inp_ip_mysql WHERE recipe_id=$inp_recipe_id";
		
	// Update recipe
	mysqli_query($link, "UPDATE $t_recipes SET recipe_title=$inp_title_mysql, recipe_introduction=$inp_introduction_mysql WHERE recipe_id=$inp_recipe_id_mysql") or die(mysqli_error($link));



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