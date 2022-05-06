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
$t_recipes_comments			= $mysqlPrefixSav . "recipes_comments";


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
$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_introduction, recipe_directions, recipe_image_path, recipe_image, recipe_thumb, recipe_video, recipe_date, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_user_ip, recipe_notes, recipe_password, recipe_last_viewed FROM $t_recipes WHERE recipe_id=$recipe_id_mysql";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_row($result);
list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb, $get_recipe_video, $get_recipe_date, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password, $get_recipe_last_viewed) = $row;





if($get_recipe_id != ""){
	// Build array
	$recipe_array = array();

	// Recipe directions
	$get_recipe_directions = str_replace("\r\n", "", $get_recipe_directions);
	$get_recipe_directions = str_replace("\n", "", $get_recipe_directions);

	// Recipe
	$recipe_array['recipe']['recipe_id'] = "$get_recipe_id";
	$recipe_array['recipe']['recipe_user_id'] = "$get_recipe_user_id";
	$recipe_array['recipe']['recipe_title'] = "$get_recipe_title";
	$recipe_array['recipe']['recipe_category_id'] = "$get_recipe_category_id";
	$recipe_array['recipe']['recipe_language'] = "$get_recipe_language";
	$recipe_array['recipe']['recipe_introduction'] = "$get_recipe_introduction";
	$recipe_array['recipe']['recipe_directions'] = "$get_recipe_directions";
	$recipe_array['recipe']['recipe_image_path'] = "$get_recipe_image_path";
	$recipe_array['recipe']['recipe_image'] = "$get_recipe_image";
	$recipe_array['recipe']['recipe_thumb'] = "$get_recipe_thumb";
	$recipe_array['recipe']['recipe_video'] = "$get_recipe_video";
	$recipe_array['recipe']['recipe_date'] = "$get_recipe_date";
	$recipe_array['recipe']['recipe_time'] = "$get_recipe_time";
	$recipe_array['recipe']['recipe_cusine_id'] = "$get_recipe_cusine_id";
	$recipe_array['recipe']['recipe_season_id'] = "$get_recipe_season_id";
	$recipe_array['recipe']['recipe_occasion_id'] = "$get_recipe_occasion_id";
	$recipe_array['recipe']['recipe_marked_as_spam'] = "$get_recipe_marked_as_spam";
	$recipe_array['recipe']['recipe_unique_hits'] = "$get_recipe_unique_hits";
	$recipe_array['recipe']['recipe_unique_hits_ip_block'] = "$get_recipe_unique_hits_ip_block";
	$recipe_array['recipe']['recipe_comments'] = "$get_recipe_comments";
	$recipe_array['recipe']['recipe_user_ip'] = "$get_recipe_user_ip";
	$recipe_array['recipe']['recipe_notes'] = "$get_recipe_notes";
	$recipe_array['recipe']['recipe_password'] = "$get_recipe_password";
	$recipe_array['recipe']['recipe_last_viewed'] = "$get_recipe_last_viewed";
	

	// Groups
	$recipe_array['groups'] = array();
	$query = "SELECT group_id, group_title FROM $t_recipes_groups WHERE group_recipe_id=$get_recipe_id";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_array($result)) {
		$recipe_array['groups'][] = $row;
	}



	// Items
	$recipe_array['items'] = array();
	$query = "SELECT * FROM $t_recipes_items WHERE item_recipe_id='$get_recipe_id'";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_array($result)) {
		$recipe_array['items'][] = $row;
	}


	// Rating
	$query_rating = "SELECT rating_id, rating_recipe_id, rating_1, rating_2, rating_3, rating_4, rating_5, rating_total_votes, rating_average, rating_popularity FROM $t_recipes_rating WHERE rating_recipe_id='$get_recipe_id'";
	$result_rating = mysqli_query($link, $query_rating);
	$row_rating = mysqli_fetch_array($result_rating);
	$recipe_array['rating'] = $row_rating;




	// Select Nutrients
	$query = "SELECT number_id, number_recipe_id, number_hundred_calories, number_hundred_proteins, number_hundred_fat, number_hundred_fat_of_which_saturated_fatty_acids, number_hundred_carbs, number_hundred_carbs_of_which_dietary_fiber, number_hundred_carbs_of_which_sugars, number_hundred_salt, number_hundred_sodium, number_serving_calories, number_serving_proteins, number_serving_fat, number_serving_fat_of_which_saturated_fatty_acids, number_serving_carbs, number_serving_carbs_of_which_dietary_fiber, number_serving_carbs_of_which_sugars, number_serving_salt, number_serving_sodium, number_total_weight, number_total_calories, number_total_proteins, number_total_fat, number_total_fat_of_which_saturated_fatty_acids, number_total_carbs, number_total_carbs_of_which_dietary_fiber, number_total_carbs_of_which_sugars, number_total_salt, number_total_sodium, number_servings FROM $t_recipes_numbers WHERE number_recipe_id=$recipe_id_mysql";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_array($result);
	$recipe_array['numbers'] = $row;



	// Author
	$query = "SELECT user_alias FROM $t_users WHERE user_id=$get_recipe_user_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_array($result);
	$recipe_array['author'] = $row;

	// Links
	$recipe_array['links'] = array();
	$query = "SELECT * FROM $t_recipes_links WHERE link_recipe_id='$get_recipe_id'";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_array($result)) {
		$recipe_array['links'][] = $row;
	}

	// Tags
	$recipe_array['tags'] = array();
	$query = "SELECT * FROM $t_recipes_tags WHERE tag_recipe_id='$get_recipe_id'";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_array($result)) {
		$recipe_array['tags'][] = $row;
	}

	// Comments
	$recipe_array['comments'] = array();
	$query = "SELECT * FROM $t_recipes_comments WHERE comment_recipe_id='$get_recipe_id'";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_array($result)) {
		$recipe_array['comments'][] = $row;
	}


	// Json everything
	$rows_json = json_encode(utf8ize($recipe_array));

	echo"$rows_json";
}
else{
	echo"404 recipe not found";
}


?>