<?php
/**
*
* File: _admin/_inc/recipes/rest_to_sqlite.php
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


/*- File to save to --------------------------------------------------------------- */
$sqlite_file = "../_cache/sqlite_recipes_rest.txt";

$fh = fopen($sqlite_file, "w+") or die("can not open file");
fwrite($fh, "
DBAdapter db = new DBAdapter(this);
db.open();

String q = \"\";
");
fclose($fh);


echo"
<h1>Recipes</h1>



<!-- Views -->
		<div class=\"tabs\">
			<ul>
				<li><a href=\"index.php?open=$open&amp;editor_language=$editor_language\">All</a>
				<li><a href=\"index.php?open=$open&amp;view=marked_as_spam&amp;editor_language=$editor_language\">Marked as spam</a>
				<li><a href=\"index.php?open=$open&amp;page=recipes_to_sql&amp;editor_language=$editor_language\">Recipes to SQL</a>
				<li><a href=\"index.php?open=$open&amp;page=recipes_to_sqlite&amp;editor_language=$editor_language\">Recipes to SQLite</a>
				<li><a href=\"index.php?open=$open&amp;page=rest_to_sqlite&amp;editor_language=$editor_language\" class=\"active\">Rest to SQLite</a>
			</ul>
		</div><p>&nbsp;</p>
<!-- //Views -->


<h2>Rest to SQLite</h2>

<!-- File -->
	<p>
	<a href=\"$sqlite_file\">$sqlite_file</a>
	</p>
<!-- //File -->

<!-- Categories -->
	<p><b>Categories:</b></p>";

	$input = "/*- Categories ------------------------------------ */";
	$fh = fopen($sqlite_file, "a+") or die("can not open file");
	fwrite($fh, $input);
	fclose($fh);

	$query = "SELECT category_id, category_name, category_age_restriction FROM $t_recipes_categories";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_category_id, $get_category_name, $get_category_age_restriction) = $row;
		echo"<span>$get_category_name<br /></span>";
		

		$input = "
        q = \"INSERT INTO categories(_id, category_id, category_name, category_age_restriction) \" +
	    \" VALUES (\" +
	    \"NULL, \" +
	    $get_category_id + \", \" +
	    \"'$get_category_name'\"  + \", \" +
	    \"'$get_category_age_restriction'\" +
	    \")\";
	    db.rawQuery(q);
";

		$fh = fopen($sqlite_file, "a+") or die("can not open file");
		fwrite($fh, $input);
		fclose($fh);

	}
echo"
<!-- //Categories -->



<!-- Categories translations -->
	<p><b>Categories translations:</b></p>";

	$input = "/*- Categories translations ------------------------------------ */";
	$fh = fopen($sqlite_file, "a+") or die("can not open file");
	fwrite($fh, $input);
	fclose($fh);

	$query = "SELECT category_translation_id, category_id, category_translation_language, category_translation_value, category_translation_no_recipes, category_translation_last_updated FROM $t_recipes_categories_translations";
	$result = mysqli_query($link, $query);
	while($row = mysqli_fetch_row($result)) {
		list($get_category_translation_id, $get_category_id, $get_category_translation_language, $get_category_translation_value, $get_category_translation_no_recipes, $get_category_translation_last_updated) = $row;
		echo"<span>$get_category_translation_language: $get_category_translation_value<br /></span>";
		
		$input = "
        q = \"INSERT INTO categories_translations(_id, category_translation_id, category_id, category_translation_language, category_translation_value, category_translation_no_recipes, category_translation_last_updated) \" +
	    \" VALUES (\" +
	    \"NULL, \" +
	   $get_category_translation_id + \", \" +
	   $get_category_id + \", \" +
	    \"'$get_category_translation_language'\"  + \", \" +
	    \"'$get_category_translation_value'\" + \", \" +
	    \"'$get_category_translation_no_recipes'\" + \", \" +
	    \"'$get_category_translation_last_updated'\" +
	    \")\";
	    db.rawQuery(q);
";

		$fh = fopen($sqlite_file, "a+") or die("can not open file");
		fwrite($fh, $input);
		fclose($fh);

	}
echo"
<!-- //Categories translations -->
";


/*- File footer  --------------------------------------------------------------- */


$fh = fopen($sqlite_file, "a+") or die("can not open file");
fwrite($fh, "

db.close();
");
fclose($fh);

?>