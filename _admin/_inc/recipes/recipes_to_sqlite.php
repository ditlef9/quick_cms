<?php
/**
*
* File: _admin/_inc/recipes/recipes_to_sqlite.php
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

if($editor_language == ""){
	$editor_language = "$get_language_active_iso_two";
}
$editor_language_mysql = quote_smart($link, $editor_language);


/*- Tables ---------------------------------------------------------------------------- */
$t_recipes 	 			= $mysqlPrefixSav . "recipes";
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
$t_recipes_measurements			= $mysqlPrefixSav . "recipes_measurements";
$t_recipes_measurements_translations	= $mysqlPrefixSav . "recipes_measurements_translations";
$t_recipes_weekly_special		= $mysqlPrefixSav . "recipes_weekly_special";
$t_recipes_of_the_day			= $mysqlPrefixSav . "recipes_of_the_day";
$t_recipes_comments			= $mysqlPrefixSav . "recipes_comments";
$t_recipes_favorites			= $mysqlPrefixSav . "recipes_favorites";
$t_recipes_tags				= $mysqlPrefixSav . "recipes_tags";
$t_recipes_links			= $mysqlPrefixSav . "recipes_links";



echo"
<h1>Recipes</h1>



<!-- Views -->
		<div class=\"tabs\">
			<ul>
				<li><a href=\"index.php?open=$open&amp;editor_language=$editor_language\">All</a>
				<li><a href=\"index.php?open=$open&amp;view=marked_as_spam&amp;editor_language=$editor_language\">Marked as spam</a>
				<li><a href=\"index.php?open=$open&amp;page=recipes_to_sql&amp;editor_language=$editor_language\">Recipes to SQL</a>
				<li><a href=\"index.php?open=$open&amp;page=recipes_to_sqlite\" class=\"active\">Recipes to SQLite</a>
				<li><a href=\"index.php?open=$open&amp;page=rest_to_sqlite&amp;editor_language=$editor_language\">Rest to SQLite</a>
			</ul>
		</div><p>&nbsp;</p>
<!-- //Views -->


<h2>Recipes to SQLite</h2>

<!-- Language select -->
	<form method=\"get\" enctype=\"multipart/form-data\">
	<p><b>Language:</b><br />
	<select id=\"inp_language_select\">
		<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">$l_editor_language</option>
		<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$editor_language&amp;l=$l\">-</option>\n";

		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
		$result = mysqli_query($link, $query);
		while($row = mysqli_fetch_row($result)) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;
			$flag_path 	= "_design/gfx/flags/16x16/$get_language_active_flag" . "_16x16.png";

			
			echo"	<option value=\"index.php?open=$open&amp;page=$page&amp;editor_language=$get_language_active_iso_two&amp;l=$l\"";if($editor_language == "$get_language_active_iso_two"){ echo" selected=\"selected\"";}echo">$get_language_active_name</option>\n";
		}
		echo"
	</select>	
	</p>
	</form>
<!-- //Language select -->



<!-- Recipes -->
	<p><b>Recipes:</b></p>";

	$counter = 0;
	$file_number = 0;
	$query = "SELECT recipe_id, recipe_user_id, recipe_title, recipe_category_id, recipe_language, recipe_introduction, recipe_directions, recipe_image_path, recipe_image, recipe_thumb_278x156, recipe_video, recipe_date, recipe_time, recipe_cusine_id, recipe_season_id, recipe_occasion_id, recipe_marked_as_spam, recipe_unique_hits, recipe_unique_hits_ip_block, recipe_comments, recipe_user_ip, recipe_notes, recipe_password, recipe_last_viewed FROM $t_recipes WHERE recipe_language=$editor_language_mysql ORDER BY recipe_id ASC";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_recipe_id, $get_recipe_user_id, $get_recipe_title, $get_recipe_category_id, $get_recipe_language, $get_recipe_introduction, $get_recipe_directions, $get_recipe_image_path, $get_recipe_image, $get_recipe_thumb_278x156, $get_recipe_video, $get_recipe_date, $get_recipe_time, $get_recipe_cusine_id, $get_recipe_season_id, $get_recipe_occasion_id, $get_recipe_marked_as_spam, $get_recipe_unique_hits, $get_recipe_unique_hits_ip_block, $get_recipe_comments, $get_recipe_user_ip, $get_recipe_notes, $get_recipe_password, $get_recipe_last_viewed) = $row;
	
		if($get_recipe_image != ""){


			if($counter == "0"){
				// Header
				/*- File to save to --------------------------------------------------------------- */
				$sqlite_file = "../_cache/sqlite_recipes_" . $editor_language . $file_number . ".txt";

				$input = "/*- $editor_language --------------------------------------------------------- */
DBAdapter db = new DBAdapter(this);
db.open();

String q = \"\";";

				$fh = fopen($sqlite_file, "w+") or die("can not open file");
				fwrite($fh, $input);
				fclose($fh);

				echo"
				<!-- File -->
					<p><a href=\"$sqlite_file\" style=\"font-size: 20px;\">$sqlite_file</a></p>
				<!-- //File -->
				";


			/*- Cache dir --------------------------------------------------------------- */
			$img_cache_dir = "../_cache/recipes_" . $editor_language . $file_number;
			if(!(is_dir("$img_cache_dir"))){
				mkdir("$img_cache_dir");
			}


				
			}
			

			echo"<span>$counter: $get_recipe_title - Hits: $get_recipe_unique_hits<br /></span>
";
		

			$get_recipe_title = str_replace("'", "&amp;&#39;", $get_recipe_title);
			$get_recipe_title = str_replace("Ã¸", "ø", $get_recipe_title);
			$get_recipe_title = str_replace("Ã¥", "å", $get_recipe_title);
			$get_recipe_title_mysql = quote_smart($link, $get_recipe_title);

			$get_recipe_introduction = str_replace("'", "&amp;#39;", $get_recipe_introduction);
			$get_recipe_introduction = str_replace("Ã¸", "ø", $get_recipe_introduction);
			$get_recipe_introduction = str_replace("Ã¥", "å", $get_recipe_introduction);
			$get_recipe_introduction_mysql = quote_smart($link, $get_recipe_introduction);

			$get_recipe_directions = str_replace("\r", "", $get_recipe_directions);
			$get_recipe_directions = str_replace('Ã¥', '&amp;aring;', $get_recipe_directions);
			$get_recipe_directions = str_replace("Ã¸", "ø", $get_recipe_directions);
			$get_recipe_directions = str_replace("&#039;", "&amp;#39;", $get_recipe_directions);
			$get_recipe_directions = str_replace("'", "&amp;#39;", $get_recipe_directions);
			$get_recipe_directions = addslashes($get_recipe_directions);
		

			if($get_recipe_cusine_id == ""){ $get_recipe_cusine_id = 0; } 
			if($get_recipe_season_id == ""){ $get_recipe_season_id = 0; } 
			if($get_recipe_occasion_id == ""){ $get_recipe_occasion_id = 0; } 
			if($get_recipe_cusine_id == ""){ $get_recipe_cusine_id = 0; } 

			$input = "/*- $get_recipe_title ------------------------------------------------------*/
        q = \"INSERT INTO recipes(_id, recipe_id, recipe_user_id, recipe_title, \" +
	    \"recipe_category_id, recipe_language, recipe_introduction, \" +
	    \"recipe_local_image_path, \" +
	    \"recipe_image_path, recipe_image, recipe_thumb_278x156, \" +
	    \"recipe_marked_as_spam, recipe_password) \" +
	    \" VALUES (\" +
	    \"NULL, \" +
	    $get_recipe_id + \", \" +
	    $get_recipe_user_id + \", \" +
	    \"$get_recipe_title_mysql\"  + \", \" +
	    $get_recipe_category_id + \", \" +
	    \"'$get_recipe_language'\" + \", \" +
	    \"$get_recipe_introduction_mysql\" + \", \" +
	    \"''\" + \", \" +
	    \"'$get_recipe_image_path'\" + \", \" +
	    \"'$get_recipe_image'\" + \", \" +
	    \"'$get_recipe_thumb_278x156'\" + \", \" +
	    \"'$get_recipe_marked_as_spam'\" + \", \" +
	    \"'$get_recipe_password'\" +
	    \")\";
	    db.rawQuery(q);
";

			$fh = fopen($sqlite_file, "a+") or die("can not open file");
			fwrite($fh, $input);
			fclose($fh);


			// Directions top
			$input = "q = \"UPDATE recipes SET recipe_directions='";

			$fh = fopen($sqlite_file, "a+") or die("can not open file");
			fwrite($fh, $input);
			fclose($fh);


			// Directions middle
			$directions_array = explode("\n", $get_recipe_directions);
			for($i=0;$i<sizeof($directions_array);$i++){
				if($i == "0"){
					$inp_body="$directions_array[0]";
				}
				else{
					$inp_body="\" + 
\"$directions_array[$i]";

				}
	
				$fh = fopen($sqlite_file, "a+") or die("can not open file");
				fwrite($fh, $inp_body);
				fclose($fh);

			}

			// Directions bottom
			$input = "' WHERE recipe_id=$get_recipe_id\";
	    db.rawQuery(q);
";
			$fh = fopen($sqlite_file, "a+") or die("can not open file");
			fwrite($fh, $input);
			fclose($fh);




			/*- Groups -------------------------------------------------- */
			$query_sub = "SELECT group_id, group_recipe_id, group_title FROM $t_recipes_groups WHERE group_recipe_id=$get_recipe_id";
			$result_sub = mysqli_query($link, $query_sub);
			while($row_sub = mysqli_fetch_row($result_sub)) {
				list($get_group_id, $get_group_recipe_id, $get_group_title) = $row_sub;

			
				$get_group_title = str_replace("Ã¦", "æ", $get_group_title);
				$get_group_title = str_replace("Ã¸", "ø", $get_group_title);
				$get_group_title = str_replace("Ã¥", "å", $get_group_title);

				$input = "
				q = \"INSERT INTO recipes_groups(_id, group_id, group_recipe_id, group_title) \" +
                                    \" VALUES (\" +
                                    \"NULL, \" +
                                    $get_group_id + \", \" +
                                   $get_group_recipe_id + \", \" +
                                    \"'$get_group_title'\" +
                                    \")\";
				db.rawQuery(q);
";

				$fh = fopen($sqlite_file, "a+") or die("can not open file");
				fwrite($fh, $input);
				fclose($fh);


			}



			/*- Items -------------------------------------------------- */
			$query_sub = "SELECT item_id, item_recipe_id, item_group_id, item_amount, item_measurement, item_grocery, item_calories_per_hundred, item_proteins_per_hundred, item_fat_per_hundred, item_carbs_per_hundred, item_calories_calculated, item_proteins_calculated, item_fat_calculated, item_carbs_calculated FROM $t_recipes_items WHERE item_recipe_id=$get_recipe_id";
			$result_sub = mysqli_query($link, $query_sub);
			while($row_sub = mysqli_fetch_row($result_sub)) {
				list($get_item_id, $get_item_recipe_id, $get_item_group_id, $get_item_amount, $get_item_measurement, $get_item_grocery, $get_item_calories_per_hundred, $get_item_proteins_per_hundred, $get_item_fat_per_hundred, $get_item_carbs_per_hundred, $get_item_calories_calculated, $get_item_proteins_calculated, $get_item_fat_calculated, $get_item_carbs_calculated) = $row_sub;


				$get_item_grocery = str_replace("Ã¦", "æ", $get_item_grocery);
				$get_item_grocery = str_replace("Ã¸", "ø", $get_item_grocery);
				$get_item_grocery = str_replace("Ã¥", "å", $get_item_grocery);

			
				$input = "
				q = \"INSERT INTO recipes_items(_id, item_id, item_recipe_id, item_group_id, item_amount, item_measurement, item_grocery, item_calories_per_hundred, item_proteins_per_hundred, item_fat_per_hundred, item_carbs_per_hundred, item_calories_calculated, item_proteins_calculated, item_fat_calculated, item_carbs_calculated) \" +
                                    \" VALUES (\" +
                                    \"NULL, \" +
                                    $get_item_id + \", \" +
                                   $get_item_recipe_id + \", \" +
                                   $get_item_group_id + \", \" +
                                    \"'$get_item_amount'\"  + \", \" +
                                    \"'$get_item_measurement'\" + \", \" +
                                    \"'$get_item_grocery'\" + \", \" +
                                    \"'$get_item_calories_per_hundred'\" + \", \" +
                                    \"'$get_item_proteins_per_hundred'\" + \", \" +
                                    \"'$get_item_fat_per_hundred'\" + \", \" +
                                    \"'$get_item_carbs_per_hundred'\" + \", \" +
                                    \"'$get_item_calories_calculated'\" + \", \" +
                                    \"'$get_item_proteins_calculated'\" + \", \" +
                                    \"'$get_item_fat_calculated'\" + \", \" +
                                    \"'$get_item_carbs_calculated'\" +
                                    \")\";
				db.rawQuery(q);
";

				$fh = fopen($sqlite_file, "a+") or die("can not open file");
				fwrite($fh, $input);
				fclose($fh);


			}

			/*- Numbers -------------------------------------------------- */
			$query_sub = "SELECT number_id, number_recipe_id, number_hundred_calories, number_hundred_proteins, number_hundred_fat, number_hundred_carbs, number_serving_calories, number_serving_proteins, number_serving_fat, number_serving_carbs, number_total_weight, number_total_calories, number_total_proteins, number_total_fat, number_total_carbs, number_servings FROM $t_recipes_numbers WHERE number_recipe_id=$get_recipe_id";
			$result_sub = mysqli_query($link, $query_sub);
			while($row_sub = mysqli_fetch_row($result_sub)) {
				list($get_number_id, $get_number_recipe_id, $get_number_hundred_calories, $get_number_hundred_proteins, $get_number_hundred_fat, $get_number_hundred_carbs, $get_number_serving_calories, $get_number_serving_proteins, $get_number_serving_fat, $get_number_serving_carbs, $get_number_total_weight, $get_number_total_calories, $get_number_total_proteins, $get_number_total_fat, $get_number_total_carbs, $get_number_servings) = $row_sub;

			
				$input = "
				q = \"INSERT INTO recipes_numbers(_id, number_id, number_recipe_id, number_hundred_calories, number_hundred_proteins, number_hundred_fat, number_hundred_carbs, number_serving_calories, number_serving_proteins, number_serving_fat, number_serving_carbs, number_total_weight, number_total_calories, number_total_proteins, number_total_fat, number_total_carbs, number_servings) \" +
                                    \" VALUES (\" +
                                    \"NULL, \" +
                                    $get_number_id + \", \" +
                                   $get_number_recipe_id + \", \" +
                                    \"'$get_number_hundred_calories'\"  + \", \" +
                                    \"'$get_number_hundred_proteins'\" + \", \" +
                                    \"'$get_number_hundred_fat'\" + \", \" +
                                    \"'$get_number_hundred_carbs'\" + \", \" +
                                    \"'$get_number_serving_calories'\" + \", \" +
                                    \"'$get_number_serving_proteins'\" + \", \" +
                                    \"'$get_number_serving_fat'\" + \", \" +
                                    \"'$get_number_serving_carbs'\" + \", \" +
                                    \"'$get_number_total_weight'\" + \", \" +
                                    \"'$get_number_total_calories'\" + \", \" +
                                    \"'$get_number_total_proteins'\" + \", \" +
                                    \"'$get_number_total_fat'\" + \", \" +
                                    \"'$get_number_total_carbs'\" + \", \" +
                                    \"'$get_number_servings'\" +
                                    \")\";
				db.rawQuery(q);
";

				$fh = fopen($sqlite_file, "a+") or die("can not open file");
				fwrite($fh, $input);
				fclose($fh);


			}
		

			/*- Links ---------------------------------------------- */
			$query_sub = "SELECT link_id, link_language, link_recipe_id, link_title, link_url, link_unique_click, link_unique_click_ipblock, link_user_id FROM $t_recipes_links WHERE link_recipe_id=$get_recipe_id";
			$result_sub = mysqli_query($link, $query_sub);
			while($row_sub = mysqli_fetch_row($result_sub)) {
				list($get_link_id, $get_link_language, $get_link_recipe_id, $get_link_title, $get_link_url, $get_link_unique_click, $get_link_unique_click_ipblock, $get_link_user_id) = $row_sub;

			
				$input = "
				q = \"INSERT INTO recipes_links(_id, link_id, link_language, link_recipe_id, link_title, link_url, link_unique_click, link_unique_click_ipblock, link_user_id) \" +
                                    \" VALUES (\" +
                                    \"NULL, \" +
                                    $get_link_id + \", \" +
                                    \"'$get_link_language'\"  + \", \" +
                                   $get_link_recipe_id + \", \" +
                                    \"'$get_link_title'\" + \", \" +
                                    \"'$get_link_url'\" + \", \" +
                                    $get_link_unique_click + \", \" +
                                    \"''\" + \", \" +
                                    $get_link_user_id +
                                    \")\";
				db.rawQuery(q);
";

				$fh = fopen($sqlite_file, "a+") or die("can not open file");
				fwrite($fh, $input);
				fclose($fh);

			}

			/*- Tags ---------------------------------------------- */
			$query_sub = "SELECT tag_id, tag_language, tag_recipe_id, tag_title, tag_title_clean, tag_user_id FROM $t_recipes_tags WHERE tag_recipe_id=$get_recipe_id";
			$result_sub = mysqli_query($link, $query_sub);
			while($row_sub = mysqli_fetch_row($result_sub)) {
				list($get_tag_id, $get_tag_language, $get_tag_recipe_id, $get_tag_title, $get_tag_title_clean, $get_tag_user_id) = $row_sub;

			
				$input = "
				q = \"INSERT INTO recipes_tags(_id, tag_id, tag_language, tag_recipe_id, tag_title, tag_title_clean, tag_user_id) \" +
                                    \" VALUES (\" +
                                    \"NULL, \" +
                                    $get_tag_id + \", \" +
                                    \"'$get_tag_language'\"  + \", \" +
                                   $get_tag_recipe_id + \", \" +
                                    \"'$get_tag_title'\"  + \", \" +
                                    \"'$get_tag_title_clean'\" + \", \" +
                                    $get_tag_user_id +
                                    \")\";
				db.rawQuery(q);
";

				$fh = fopen($sqlite_file, "a+") or die("can not open file");
				fwrite($fh, $input);
				fclose($fh);


			}


			/*- Thumb --------------------------------------------- */
			// Thumb
			$from_thumb = "../" . $get_recipe_image_path . "/" . $get_recipe_thumb_278x156;
			$to_thumb   = $img_cache_dir  . "/" .  "img" . $get_recipe_thumb_278x156;
			$to_thumb   = str_replace("-", "_", $to_thumb);

			$inp_new_x = 100;
			$inp_new_y = 87;
			if(!(file_exists("../$to_thumb"))){
				resize_crop_image($inp_new_x, $inp_new_y, $from_thumb, $to_thumb);
			}


			/*- Imgs --------------------------------------------- */
			// Img
			$from_img = "../" . $get_recipe_image_path . "/" . $get_recipe_image;
			$to_img   = $img_cache_dir  . "/" .  "img" . $get_recipe_image;
			$to_img   = str_replace("-", "_", $to_img);

			$inp_new_x = 500;
			$inp_new_y = 298;
			if(!(file_exists("../$to_img"))){
				resize_crop_image($inp_new_x, $inp_new_y, $from_img, $to_img);
			}

			$counter++;
			
				if($counter == "50"){
					$counter = 0;
					$file_number++;
				}

		} // image found
	} // loop
echo"
<!-- //Recipes -->


";


/*- File footer  --------------------------------------------------------------- */


$fh = fopen($sqlite_file, "a+") or die("can not open file");
fwrite($fh, "

db.close();
");
fclose($fh);

?>