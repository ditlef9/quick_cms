<?php
/*- Functions ------------------------------------------------------------------------- */
include("../../_admin/_functions/output_html.php");
include("../../_admin/_functions/clean.php");
include("../../_admin/_functions/quote_smart.php");

function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;

}

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

$t_recipes_tags				= $mysqlPrefixSav . "recipes_tags";
$t_recipes_links			= $mysqlPrefixSav . "recipes_links";

/*- Variables ------------------------------------------------------------------------- */
if(isset($_GET['from'])) {
	$from = $_GET['from'];
	$from = strip_tags(stripslashes($from));
}
else{
	$from = "";
}
if(isset($_GET['to'])) {
	$to = $_GET['to'];
	$to = strip_tags(stripslashes($to));
}
else{
	$to = "";
}


/*- Get recipe ------------------------------------------------------------------------- */

// Build array
$rows_array = array();


// Select
$x =0;

$from_mysql = quote_smart($link, $from);
$to_mysql   = quote_smart($link, $to);



$query = "SELECT $t_recipes.recipe_id, $t_recipes.recipe_user_id, $t_recipes.recipe_title, $t_recipes.recipe_category_id, $t_recipes.recipe_language, $t_recipes.recipe_introduction, $t_recipes.recipe_image_path, $t_recipes.recipe_image, $t_recipes.recipe_thumb, $t_recipes.recipe_video, $t_recipes.recipe_date, $t_recipes.recipe_time, $t_recipes.recipe_cusine_id, $t_recipes.recipe_season_id, $t_recipes.recipe_occasion_id, $t_recipes.recipe_marked_as_spam, $t_recipes.recipe_unique_hits, $t_recipes.recipe_password, $t_recipes_numbers.number_id, $t_recipes_numbers.number_recipe_id, $t_recipes_numbers.number_hundred_calories, $t_recipes_numbers.number_hundred_proteins, $t_recipes_numbers.number_hundred_fat, $t_recipes_numbers.number_hundred_carbs, $t_recipes_numbers.number_serving_calories, $t_recipes_numbers.number_serving_proteins, $t_recipes_numbers.number_serving_fat, $t_recipes_numbers.number_serving_carbs, $t_recipes_numbers.number_total_weight, $t_recipes_numbers.number_total_calories, $t_recipes_numbers.number_total_proteins, $t_recipes_numbers.number_total_fat, $t_recipes_numbers.number_total_carbs, $t_recipes_numbers.number_servings FROM $t_recipes JOIN $t_recipes_numbers ON $t_recipes.recipe_id=$t_recipes_numbers.number_recipe_id WHERE recipe_id BETWEEN $from_mysql AND $to_mysql";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_array($result)) {
	list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_introduction, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb, $get_recipe_video, $get_recipe_date, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_password, $get_number_id, $get_number_recipe_id, $get_number_hundred_calories, $get_number_hundred_proteins, $get_number_hundred_fat, $get_number_hundred_carbs, $get_number_serving_calories, $get_number_serving_proteins, $get_number_serving_fat, $get_number_serving_carbs, $get_number_total_weight, $get_number_total_calories, $get_number_total_proteins, $get_number_total_fat, $get_number_total_carbs, $get_number_servings) = $row;


	// Fetch tags
	$tag_a_id = "";
	$tag_a_title = "";
	$tag_a_title_clean = "";

	$tag_b_id = "";
	$tag_b_title = "";
	$tag_b_title_clean = "";

	$tag_c_id = "";
	$tag_c_title = "";
	$tag_c_title_clean = "";
	$y = 0;
	$query_b = "SELECT tag_id, tag_language, tag_recipe_id, tag_title, tag_title_clean, tag_user_id FROM $t_recipes_tags WHERE tag_recipe_id='$get_recipe_id'";
	$result_b = mysqli_query($link, $query_b);
	while($row_b = mysqli_fetch_array($result_b)) {
		list($get_tag_id, $get_tag_language, $get_tag_recipe_id, $get_tag_title, $get_tag_title_clean, $get_tag_user_id) = $row_b;
	
		if($y == "0"){
			$tag_a_id = "$get_tag_id";
			$tag_a_title = "$get_tag_title";
			$tag_a_title_clean = "$get_tag_title_clean";
		}
		elseif($y == "1"){
			$tag_b_id = "$get_tag_id";
			$tag_b_title = "$get_tag_title";
			$tag_b_title_clean = "$get_tag_title_clean";
		}
		elseif($y == "2"){
			$tag_c_id = "$get_tag_id";
			$tag_c_title = "$get_tag_title";
			$tag_c_title_clean = "$get_tag_title_clean";
		}
		
		$y++;
	}

	
	$a = ['recipe_id' => $get_recipe_id, 
		'recipe_user_id' => $get_recipe_user_id, 
		'recipe_title' => $get_recipe_title, 
		'recipe_category_id' => $get_recipe_category_id, 
		'recipe_user_id' => $get_recipe_user_id, 
		'recipe_language' => $get_recipe_language, 
		'recipe_introduction' => $get_recipe_introduction, 
		'recipe_image_path' => $get_recipe_image_path, 
		'recipe_image' => $get_recipe_image, 
		'recipe_thumb' => $get_recipe_thumb, 
		'recipe_user_id' => $get_recipe_user_id, 
		'recipe_video' => $get_recipe_video, 
		'recipe_date' => $get_recipe_date, 
		'recipe_time' => $get_recipe_time, 
		'recipe_cusine_id' => $get_recipe_cusine_id, 
		'recipe_season_id' => $get_recipe_season_id, 
		'recipe_occasion_id' => $get_recipe_occasion_id, 
		'recipe_marked_as_spam' => $get_recipe_marked_as_spam, 
		'recipe_unique_hits' => $get_recipe_unique_hits, 
		'recipe_password' => $get_recipe_password, 
		'number_id' => $get_number_id, 
		'number_recipe_id' => $get_number_recipe_id, 
		'number_hundred_calories' => $get_number_hundred_calories, 
		'number_hundred_proteins' => $get_number_hundred_proteins, 
		'number_hundred_fat' => $get_number_hundred_fat, 
		'number_hundred_carbs' => $get_number_hundred_carbs, 
		'number_serving_calories' => $get_number_serving_calories, 
		'number_serving_proteins' => $get_number_serving_proteins, 
		'number_serving_fat' => $get_number_serving_fat, 
		'number_serving_carbs' => $get_number_serving_carbs, 
		'number_total_weight' => $get_number_total_weight, 
		'number_total_calories' => $get_number_total_calories, 
		'number_total_proteins' => $get_number_total_proteins, 
		'number_total_fat' => $get_number_total_fat, 
		'number_total_carbs' => $get_number_total_carbs, 
		'number_servings' => $get_number_servings, 
		'tag_a_id' => $tag_a_id, 
		'tag_a_title' => $tag_a_title, 
		'tag_a_title_clean' => $tag_a_title_clean, 
		'tag_b_id' => $tag_b_id, 
		'tag_b_title' => $tag_b_title, 
		'tag_b_title_clean' => $tag_b_title_clean, 
		'tag_c_id' => $tag_c_id, 
		'tag_c_title' => $tag_c_title, 
		'tag_c_title_clean' => $tag_c_title_clean];





	$rows_array[] = $a;

	$x++;
}

if($x > 0){
	// Json everything
	$rows_json = json_encode(utf8ize($rows_array));

	echo"$rows_json";
}
else{
	// the recipe was not found
	// Are there more records?

	$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language FROM $t_recipes WHERE recipe_id > $to_mysql ORDER BY recipe_id ASC LIMIT 1"; 
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_recipe_id) = $row;

	if($get_recipe_id != ""){
		echo"Recipe not found.Please look for next recipe";
	}
	else{
		echo"Recipe not found.No more recipes";
	}
}





?>