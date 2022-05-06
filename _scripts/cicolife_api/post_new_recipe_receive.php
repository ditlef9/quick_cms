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


/*- MySQL Tables ---------------------------------------------------------------------- */
$t_users 	 	= $mysqlPrefixSav . "users";
$t_recipes		= $mysqlPrefixSav . "recipes";
$t_recipes_numbers	= $mysqlPrefixSav . "recipes_numbers";
$t_recipes_rating	= $mysqlPrefixSav . "recipes_rating";


/*- Variables ------------------------------------------------------------------------- */
$fm = "";

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

if(isset($_POST['inp_category_id'])){
	$inp_category_id = $_POST['inp_category_id'];
	$inp_category_id = output_html($inp_category_id);
	$inp_category_id_mysql = quote_smart($link, $inp_category_id);

	if(empty($inp_category_id)){
		$fm = "Category_id is empty";
	}
}
else{
	$fm = "Missing category_id";
}


if(isset($_POST['inp_language'])){
	$inp_language = $_POST['inp_language'];
	$inp_language = output_html($inp_language);
	$inp_language_mysql = quote_smart($link, $inp_language);

	if(empty($inp_language)){
		$fm = "Language is empty";
	}
}
else{
	$fm = "Missing language";
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


if(isset($_POST['inp_directions'])){
	$inp_directions = $_POST['inp_directions'];
	$inp_directions = output_html($inp_directions);

	// Android doesnt have <p> etc
	$inp_directions = str_replace("\n", "<br />", $inp_directions);
	$inp_directions = "<p>$inp_directions</p>";
	
	$inp_directions_mysql = quote_smart($link, $inp_directions);

	if(empty($inp_directions)){
		$fm = "Directions is empty";
	}
}
else{
	$fm = "Missing directions";
}


if(isset($_POST['inp_hundred_calories'])){
	$inp_hundred_calories = $_POST['inp_hundred_calories'];
	$inp_hundred_calories = output_html($inp_hundred_calories);
}
else{
	$inp_hundred_calories = "";
}
$inp_hundred_calories_mysql = quote_smart($link, $inp_hundred_calories);

if(isset($_POST['inp_hundred_proteins'])){
	$inp_hundred_proteins = $_POST['inp_hundred_proteins'];
	$inp_hundred_proteins= output_html($inp_hundred_proteins);
}
else{
	$inp_hundred_proteins = "";
}
$inp_hundred_proteins_mysql = quote_smart($link, $inp_hundred_proteins);

if(isset($_POST['inp_hundred_fat'])){
	$inp_hundred_fat = $_POST['inp_hundred_fat'];
	$inp_hundred_fat = output_html($inp_hundred_fat);
}
else{
	$inp_hundred_fat = "";
}
$inp_hundred_fat_mysql = quote_smart($link, $inp_hundred_fat);

if(isset($_POST['inp_hundred_carbs'])){
	$inp_hundred_carbs = $_POST['inp_hundred_carbs'];
	$inp_hundred_carbs = output_html($inp_hundred_carbs);
}
else{
	$inp_hundred_carbs = "";
}
$inp_hundred_carbs_mysql = quote_smart($link, $inp_hundred_carbs);

// Serving

if(isset($_POST['inp_serving_calories'])){
	$inp_serving_calories = $_POST['inp_serving_calories'];
	$inp_serving_calories = output_html($inp_serving_calories);
}
else{
	$inp_serving_calories = "";
}
$inp_serving_calories_mysql = quote_smart($link, $inp_serving_calories);

if(isset($_POST['inp_serving_proteins'])){
	$inp_serving_proteins = $_POST['inp_serving_proteins'];
	$inp_serving_proteins = output_html($inp_serving_proteins);
}
else{
	$inp_serving_proteins = "";
}
$inp_serving_proteins_mysql = quote_smart($link, $inp_serving_proteins);

if(isset($_POST['inp_serving_fat'])){
	$inp_serving_fat = $_POST['inp_serving_fat'];
	$inp_serving_fat = output_html($inp_serving_fat);
}
else{
	$inp_serving_fat = "";
}
$inp_serving_fat_mysql = quote_smart($link, $inp_serving_fat);

if(isset($_POST['inp_serving_carbs'])){
	$inp_serving_carbs = $_POST['inp_serving_carbs'];
	$inp_serving_carbs = output_html($inp_serving_carbs);
}
else{
	$inp_serving_carbs = "";
}
$inp_serving_carbs_mysql = quote_smart($link, $inp_serving_carbs);


if(isset($_POST['inp_servings'])){
	$inp_servings = $_POST['inp_servings'];
	$inp_servings = output_html($inp_servings);
}
else{
	$inp_servings = "";
}
$inp_servings_mysql = quote_smart($link, $inp_servings);


// Total

if(isset($_POST['inp_total_weight'])){
	$inp_total_weight = $_POST['inp_total_weight'];
	$inp_total_weight = output_html($inp_total_weight);
}
else{
	$inp_total_weight = "";
}
$inp_total_weight_mysql = quote_smart($link, $inp_total_weight);

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


if(isset($_POST['inp_servings'])){
	$inp_servings = $_POST['inp_servings'];
	$inp_servings = output_html($inp_servings);
}
else{
	$inp_servings = "";
}
$inp_servings_mysql = quote_smart($link, $inp_servings);




if(isset($_POST['inp_password'])){
	$inp_password = $_POST['inp_password'];
	$inp_password = output_html($inp_password);
	$inp_password_mysql = quote_smart($link, $inp_password);
}
else{
	$fm = "Missing inp_password";
}





if($fm == ""){
	$inp_ip = $_SERVER['REMOTE_ADDR'];
	$inp_ip_mysql = quote_smart($link, $inp_ip);

	// Echo
	//echo"INSERT INTO $t_recipes
	//(recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_introduction, recipe_directions, recipe_calories_per_hundred, recipe_proteins_per_hundred, recipe_fat_per_hundred, recipe_carbs_per_hundred, recipe_total_weight, recipe_total_calories, recipe_total_proteins, recipe_total_fat, recipe_total_carbs, recipe_image_path, recipe_image, recipe_thumb, recipe_rating_1, recipe_rating_2, recipe_rating_3, recipe_rating_4, recipe_rating_5, recipe_rating_total_votes, recipe_rating_average, recipe_servings, recipe_cook_time, recipe_prep_time, recipe_tags, recipe_marked_as_spam, recipe_user_ip, recipe_notes) 
	//VALUES 
	//(NULL, $inp_user_id_mysql, $inp_title_mysql, $inp_category_id_mysql, $inp_language_mysql, $inp_introduction_mysql, $inp_directions_mysql, $inp_calories_per_hundred_mysql, $inp_proteins_per_hundred_mysql, $inp_fat_per_hundred_mysql, $inp_carbs_per_hundred_mysql, $inp_total_weight_mysql, $inp_total_calories_mysql, $inp_total_proteins_mysql, $inp_total_fat_mysql, $inp_total_carbs_mysql, '', '', '', '0', '0', '0', '0', '0', '0', '0', $inp_servings_mysql, $inp_cook_time_mysql, $inp_prep_time_mysql, $inp_tags_mysql, '', $inp_ip_mysql, '')";
		
	// Insert recipe
	mysqli_query($link, "INSERT INTO $t_recipes
	(recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_introduction, recipe_directions, recipe_image_path, recipe_image, recipe_thumb, recipe_marked_as_spam, recipe_user_ip, recipe_password, recipe_notes) 
	VALUES 
	(NULL, $inp_user_id_mysql, $inp_title_mysql, $inp_category_id_mysql, $inp_language_mysql, $inp_introduction_mysql, $inp_directions_mysql, '', '', '', '', $inp_ip_mysql, $inp_password_mysql, '')")
	or die(mysqli_error($link));

	// Get recipe ID
	$query = "SELECT recipe_id FROM $t_recipes WHERE recipe_user_id=$inp_user_id_mysql AND recipe_title=$inp_title_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_recipe_id) = $row;

	// Insert numbers
	mysqli_query($link, "INSERT INTO $t_recipes_numbers
	(number_id, number_recipe_id, number_hundred_calories, number_hundred_proteins, number_hundred_fat, number_hundred_carbs, number_serving_calories, number_serving_proteins, number_serving_fat, number_serving_carbs, number_total_weight, number_total_calories, number_total_proteins, number_total_fat, number_total_carbs, number_servings) 
	VALUES 
	(NULL, '$get_recipe_id', $inp_hundred_calories_mysql, $inp_hundred_proteins_mysql, $inp_hundred_fat_mysql, $inp_hundred_carbs_mysql, $inp_serving_calories_mysql, $inp_serving_proteins_mysql, $inp_serving_fat_mysql, $inp_serving_carbs_mysql, $inp_total_weight_mysql, $inp_total_calories_mysql, $inp_total_proteins_mysql, $inp_total_fat_mysql, $inp_total_carbs_mysql, $inp_servings_mysql)")
	or die(mysqli_error($link));

	// Insert rating
	mysqli_query($link, "INSERT INTO $t_recipes_rating
	(rating_id, rating_recipe_id) 
	VALUES 
	(NULL, '$get_recipe_id')")
	or die(mysqli_error($link));

	echo"$get_recipe_id";

}
else{
	echo"$fm";
}

?>