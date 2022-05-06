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
$t_recipes_rating	= $mysqlPrefixSav . "recipes_rating";


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



if(isset($_POST['inp_rating_1'])){
	$inp_rating_a = $_POST['inp_rating_1'];
	$inp_rating_a = output_html($inp_rating_a);
	$inp_rating_a_mysql = quote_smart($link, $inp_rating_a);
}
else{
	$fm = "Missing inp_rating_1";
}
if(isset($_POST['inp_rating_2'])){
	$inp_rating_b = $_POST['inp_rating_2'];
	$inp_rating_b = output_html($inp_rating_b);
	$inp_rating_b_mysql = quote_smart($link, $inp_rating_b);
}
else{
	$fm = "Missing inp_rating_2";
}
if(isset($_POST['inp_rating_3'])){
	$inp_rating_c = $_POST['inp_rating_3'];
	$inp_rating_c = output_html($inp_rating_c);
	$inp_rating_c_mysql = quote_smart($link, $inp_rating_c);
}
else{
	$fm = "Missing inp_rating_3";
}
if(isset($_POST['inp_rating_4'])){
	$inp_rating_d = $_POST['inp_rating_4'];
	$inp_rating_d = output_html($inp_rating_d);
	$inp_rating_d_mysql = quote_smart($link, $inp_rating_d);
}
else{
	$fm = "Missing inp_rating_4";
}
if(isset($_POST['inp_rating_5'])){
	$inp_rating_e = $_POST['inp_rating_5'];
	$inp_rating_e = output_html($inp_rating_e);
	$inp_rating_e_mysql = quote_smart($link, $inp_rating_e);
}
else{
	$fm = "Missing inp_rating_5";
}

if(isset($_POST['inp_total_votes'])){
	$inp_total_votes = $_POST['inp_total_votes'];
	$inp_total_votes = output_html($inp_total_votes);
	$inp_total_votes_mysql = quote_smart($link, $inp_total_votes);
}
else{
	$fm = "Missing inp_total_votes";
}


if(isset($_POST['inp_average'])){
	$inp_average = $_POST['inp_average'];
	$inp_average = output_html($inp_average);
	$inp_average_mysql = quote_smart($link, $inp_average);
}
else{
	$fm = "Missing inp_average";
}

if(isset($_POST['inp_popularity'])){
	$inp_popularity = $_POST['inp_popularity'];
	$inp_popularity = output_html($inp_popularity);
}
else{
	$inp_popularity = "0";
}
$inp_popularity_mysql = quote_smart($link, $inp_popularity);


if($fm == ""){
	$inp_ip = $_SERVER['REMOTE_ADDR'];
	$inp_ip = output_html($inp_ip);
	$inp_ip_mysql = quote_smart($link, $inp_ip);
	
	// Echo
	// echo"UPDATE $t_recipes_rating SET rating_1=$inp_rating_a_mysql, rating_2=$inp_rating_b_mysql, rating_3=$inp_rating_c_mysql, rating_4=$inp_rating_d_mysql, rating_5=$inp_rating_e_mysql, rating_total_votes=$inp_total_votes_mysql, rating_average=$inp_average_mysql, rating_popularity=$inp_rating_popularity_mysql WHERE rating_recipe_id=$inp_recipe_id_mysql\n\n";
		
	// Update recipe
	mysqli_query($link, "UPDATE $t_recipes_rating SET rating_1=$inp_rating_a_mysql, rating_2=$inp_rating_b_mysql, rating_3=$inp_rating_c_mysql, rating_4=$inp_rating_d_mysql, rating_5=$inp_rating_e_mysql, rating_total_votes=$inp_total_votes_mysql, rating_average=$inp_average_mysql, rating_popularity=$inp_popularity_mysql WHERE rating_recipe_id=$inp_recipe_id_mysql") or die(mysqli_error($link));


	echo"ok";

}
else{
	echo"$fm";
}

?>