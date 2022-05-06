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


/*- MySQL Tables -------------------------------------------------------------------- */
$t_recipes 	 			= $mysqlPrefixSav . "recipes";
$t_recipes_ingredients			= $mysqlPrefixSav . "recipes_ingredients";
$t_recipes_favorites			= $mysqlPrefixSav . "recipes_favorites";
$t_recipes_groups			= $mysqlPrefixSav . "recipes_groups";
$t_recipes_items			= $mysqlPrefixSav . "recipes_items";
$t_recipes_numbers			= $mysqlPrefixSav . "recipes_numbers";
$t_recipes_rating			= $mysqlPrefixSav . "recipes_rating";
$t_recipes_cuisines			= $mysqlPrefixSav . "recipes_cuisines";
$t_recipes_cuisines_translations	= $mysqlPrefixSav . "recipes_cuisines_translations";
$t_recipes_seasons			= $mysqlPrefixSav . "recipes_seasons";
$t_recipes_seasons_translations		= $mysqlPrefixSav . "recipes_seasons_translations";
$t_recipes_occasions			= $mysqlPrefixSav . "recipes_occasions";
$t_recipes_occasions_translations	= $mysqlPrefixSav . "recipes_occasions_translations";
$t_recipes_categories			= $mysqlPrefixSav . "recipes_categories";
$t_recipes_categories_translations	= $mysqlPrefixSav . "recipes_categories_translations";
$t_recipes_weekly_special		= $mysqlPrefixSav . "recipes_weekly_special";
$t_recipes_of_the_day			= $mysqlPrefixSav . "recipes_of_the_day";
$t_recipes_comments			= $mysqlPrefixSav . "recipes_comments";
$t_users =  $mysqlPrefixSav . "users";

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['recipe_id'])) {
	$recipe_id = $_GET['recipe_id'];
	$recipe_id = strip_tags(stripslashes($recipe_id));
}
else{
	$recipe_id = "";
}

$l_mysql = quote_smart($link, $l);


/*- Get recipe ------------------------------------------------------------------------- */
// Select
$recipe_id_mysql = quote_smart($link, $recipe_id);
$query = "SELECT recipe_id, recipe_user_id FROM $t_recipes WHERE recipe_id=$recipe_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_recipe_id, $get_recipe_user_id) = $row;





if($get_recipe_id != ""){
	// Build array
	$recipe_array = array();


	// Recipe
	$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_introduction, recipe_directions, recipe_image_path, recipe_image, recipe_thumb, recipe_video, recipe_date, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_user_ip, recipe_notes, recipe_password FROM $t_recipes WHERE recipe_id=$recipe_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_array($result);
	$recipe_array[recipe] = $row;


	// Groups
	$recipe_array[groups] = array();
	$query = "SELECT group_id, group_title FROM $t_recipes_groups WHERE group_recipe_id=$get_recipe_id";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_array($result,MYSQL_ASSOC)) {
		array_push($recipe_array[groups],$row);
	}

	// Items
	$recipe_array[items] = array();
	$query = "SELECT * FROM $t_recipes_items WHERE item_recipe_id='$get_recipe_id'";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_array($result,MYSQL_ASSOC)) {
		array_push($recipe_array[items],$row);
	}


	// Rating
	$query_rating = "SELECT rating_id, rating_recipe_id, rating_1, rating_2, rating_3, rating_4, rating_5, rating_total_votes, rating_average, rating_popularity FROM $t_recipes_rating WHERE rating_recipe_id='$get_recipe_id'";
	$result_rating = mysqli_query($link, $query_rating);
	$row_rating = mysqli_fetch_array($result_rating);
	$recipe_array[rating] = $row_rating;




	// Select Nutrients
	$query = "SELECT number_id, number_recipe_id, number_hundred_calories, number_hundred_proteins, number_hundred_fat, number_hundred_carbs, number_serving_calories, number_serving_proteins, number_serving_fat, number_serving_carbs, number_total_weight, number_total_calories, number_total_proteins, number_total_fat, number_total_carbs, number_servings FROM $t_recipes_numbers WHERE number_recipe_id=$recipe_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_array($result);
	$recipe_array[numbers] = $row;



	// Author
	$query = "SELECT user_alias FROM $t_users WHERE user_id=$get_recipe_user_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_array($result);
	$recipe_array[author] = $row;

	// Json everything
	$rows_json = json_encode($recipe_array);

	echo"$rows_json";
}



?>