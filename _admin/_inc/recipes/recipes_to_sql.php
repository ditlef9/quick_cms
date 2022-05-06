<?php
/**
*
* File: _admin/_inc/recipes/recipes_to_sql.php
* Version 1.0
* Date 13:41 04.11.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['view'])) {
	$view = $_GET['view'];
	$view = strip_tags(stripslashes($view));
}
else{
	$view = "";
}


/*- Tables ---------------------------------------------------------------------------- */
$t_recipes 	 	= $mysqlPrefixSav . "recipes";
$t_recipes_ingredients	= $mysqlPrefixSav . "recipes_ingredients";
$t_recipes_groups	= $mysqlPrefixSav . "recipes_groups";
$t_recipes_items	= $mysqlPrefixSav . "recipes_items";
$t_recipes_numbers	= $mysqlPrefixSav . "recipes_numbers";
$t_recipes_rating	= $mysqlPrefixSav . "recipes_rating";
$t_recipes_cuisines	= $mysqlPrefixSav . "recipes_cuisines";
$t_recipes_seasons	= $mysqlPrefixSav . "recipes_seasons";
$t_recipes_occasions	= $mysqlPrefixSav . "recipes_occasions";



echo"
<h1>Recipes</h1>



<!-- Views -->
		<div class=\"tabs\">
			<ul>
				<li><a href=\"index.php?open=$open&amp;editor_language=$editor_language\">All</a>
				<li><a href=\"index.php?open=$open&amp;view=marked_as_spam&amp;editor_language=$editor_language\">Marked as spam</a>
				<li><a href=\"index.php?open=$open&amp;page=recipes_to_sql&amp;editor_language=$editor_language\" class=\"active\">Recipes to SQL</a>
				<li><a href=\"index.php?open=$open&amp;page=recipes_to_sqlite\">Recipes to SQLite</a>
				<li><a href=\"index.php?open=$open&amp;page=rest_to_sqlite&amp;editor_language=$editor_language\">Rest to SQLite</a>
			</ul>
		</div><p>&nbsp;</p>
<!-- //Views -->


<h2>Recipes</h2>

	";

	$x = 0;	
	$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_country, recipe_introduction, recipe_directions, recipe_image_path, recipe_image_h_a, recipe_image_h_b, recipe_image_v_a, recipe_thumb_h_a_278x156, recipe_thumb_h_b_278x156, recipe_video_h, recipe_video_v, recipe_date, recipe_date_saying, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_times_favorited, recipe_user_ip, recipe_notes, recipe_password, recipe_last_viewed, recipe_age_restriction, recipe_published FROM $t_recipes";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_country, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image_h_a, $get_recipe_image_h_b, $get_recipe_image_v_a, $get_recipe_thumb_h_a_278x156, $get_recipe_thumb_h_b_278x156, $get_recipe_video_h, $get_recipe_video_v, $get_recipe_date, $get_recipe_date_saying, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_times_favorited, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password, $get_recipe_last_viewed, $get_recipe_age_restriction, $get_recipe_published) = $row;

		

		$get_recipe_title = str_replace("'", "&amp;&#39;", $get_recipe_title);
		$inp_recipe_title_mysql = quote_smart($link, $get_recipe_title);

		$inp_recipe_country = str_replace("'", "&amp;&#39;", $get_recipe_country);
		$inp_recipe_country_mysql =  quote_smart($link, $inp_recipe_country);

		
		$get_recipe_introduction = str_replace("'", "&amp;#39;", $get_recipe_introduction);
		$inp_recipe_introduction_mysql = quote_smart($link, $get_recipe_introduction);

		$get_recipe_directions = str_replace("<br />", "\n", $get_recipe_directions);
		$get_recipe_directions = str_replace("\r", "", $get_recipe_directions);
		$get_recipe_directions = str_replace('å', '&amp;aring;', $get_recipe_directions);
		$get_recipe_directions = str_replace("ø", "�", $get_recipe_directions);
		$get_recipe_directions = str_replace("&#039;", "&amp;#39;", $get_recipe_directions);
		$get_recipe_directions = str_replace("'", "&amp;#39;", $get_recipe_directions);
		$get_recipe_directions = str_replace("'", "&amp;#39;", $get_recipe_directions);
		$get_recipe_directions = addslashes($get_recipe_directions);
		$inp_recipe_directions_mysql = quote_smart($link, $get_recipe_directions);
		

		if($get_recipe_cusine_id == ""){ $get_recipe_cusine_id = 0; } 
		if($get_recipe_season_id == ""){ $get_recipe_season_id = 0; } 
		if($get_recipe_occasion_id == ""){ $get_recipe_occasion_id = 0; } 
		if($get_recipe_cusine_id == ""){ $get_recipe_cusine_id = 0; } 

		echo"
		<p>
		mysqli_query(\$link, &quot;INSERT INTO \$t_recipes<br />
		(recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, 
		recipe_country, recipe_introduction, recipe_directions, recipe_image_path, recipe_image_h_a, 
		recipe_image_h_b, recipe_image_v_a, recipe_thumb_h_a_278x156, recipe_thumb_h_b_278x156, recipe_video_h, 
		recipe_video_v, recipe_date, recipe_date_saying, recipe_time, recipe_cusine_id, 
		recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_comments, 
		recipe_times_favorited, recipe_password, recipe_last_viewed, recipe_age_restriction, 
		recipe_published) <br />
		VALUES<br />
		(NULL, $get_recipe_user_id, $inp_recipe_title_mysql, $get_recipe_category_id, '$get_recipe_language', 
		$inp_recipe_country_mysql, $inp_recipe_introduction_mysql, $inp_recipe_directions_mysql, '$get_recipe_image_path', '$get_recipe_image_h_a', 
		'$get_recipe_image_h_b', '$get_recipe_image_v_a', '$get_recipe_thumb_h_a_278x156', '$get_recipe_thumb_h_b_278x156', '$get_recipe_video_h', 
		'$get_recipe_video_v', $get_recipe_date', $get_recipe_date_saying', '$get_recipe_time', '$get_recipe_cusine_id', 
		'$get_recipe_season_id', '$get_recipe_occasion_id', '$get_recipe_marked_as_spam', '$get_recipe_unique_hits', $get_recipe_comments,
		$get_recipe_times_favorited, '$get_recipe_password', $get_recipe_last_viewed, $get_recipe_age_restriction, 
		$get_recipe_published)<br />
		&quot;)<br />
		or die(mysqli_error(\$link));</p>
		";

		$x++;
	}
echo"


<h2>Items</h2>

<textarea rows=\"40\" cols=\"100\">";

	$x = 0;	
	$query = "SELECT item_id, item_recipe_id, item_group_id, item_amount, item_measurement, item_grocery, item_calories_per_hundred, item_proteins_per_hundred, item_fat_per_hundred, item_carbs_per_hundred, item_calories_calculated, item_proteins_calculated, item_fat_calculated, item_carbs_calculated FROM $t_recipes_items";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_item_id, $get_item_recipe_id, $get_item_group_id, $get_item_amount, $get_item_measurement, $get_item_grocery, $get_item_calories_per_hundred, $get_item_proteins_per_hundred, $get_item_fat_per_hundred, $get_item_carbs_per_hundred, $get_item_calories_calculated, $get_item_proteins_calculated, $get_item_fat_calculated, $get_item_carbs_calculated) = $row;

		$get_item_grocery = str_replace("'", "&amp;#39;", $get_item_grocery);
		$get_item_grocery = str_replace("&#039;", "&amp;#39;", $get_item_grocery);
		
		if($get_item_calories_per_hundred == ""){ $get_item_calories_per_hundred = 0; } 
		if($get_item_proteins_per_hundred == ""){ $get_item_proteins_per_hundred = 0; } 
		if($get_item_fat_per_hundred == ""){ $get_item_fat_per_hundred = 0; } 
		if($get_item_carbs_per_hundred == ""){ $get_item_carbs_per_hundred = 0; } 

		if($get_item_calories_calculated == ""){ $get_item_calories_calculated = 0; } 
		if($get_item_proteins_calculated == ""){ $get_item_proteins_calculated = 0; } 
		if($get_item_fat_calculated == ""){ $get_item_fat_calculated = 0; } 
		if($get_item_carbs_calculated == ""){ $get_item_carbs_calculated = 0; } 


		echo"mysqli_query(\$link, &quot;INSERT INTO \$t_recipes_items\n";
		echo"(item_id, item_recipe_id, item_group_id, item_amount, item_measurement, item_grocery, item_calories_per_hundred, item_proteins_per_hundred, item_fat_per_hundred, item_carbs_per_hundred, item_calories_calculated, item_proteins_calculated, item_fat_calculated, item_carbs_calculated)\n";
		echo"VALUES\n";
		echo"(NULL, $get_item_recipe_id, $get_item_group_id, '$get_item_amount', '$get_item_measurement', '$get_item_grocery', '$get_item_calories_per_hundred', '$get_item_proteins_per_hundred', '$get_item_fat_per_hundred', '$get_item_carbs_per_hundred', '$get_item_calories_calculated', '$get_item_proteins_calculated', '$get_item_fat_calculated', '$get_item_carbs_calculated')\n";
		echo"&quot;)\n";
		echo"or die(mysqli_error(\$link));\n\n";

		$x++;
	}
echo"</textarea>
";

?>