<?php
/**
*
* File: _admin/_inc/food/export_to_android_kotlin.php
* Version 10:05 18.10.2020
* Copyright (c) 2008-2020 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}
/*- Tables ---------------------------------------------------------------------------- */
$t_food_categories		  = $mysqlPrefixSav . "food_categories";
$t_food_categories_translations	  = $mysqlPrefixSav . "food_categories_translations";
$t_food_index			  = $mysqlPrefixSav . "food_index";
$t_food_index_stores		  = $mysqlPrefixSav . "food_index_stores";
$t_food_index_ads		  = $mysqlPrefixSav . "food_index_ads";
$t_food_index_tags		  = $mysqlPrefixSav . "food_index_tags";
$t_food_index_prices		  = $mysqlPrefixSav . "food_index_prices";
$t_food_index_contents		  = $mysqlPrefixSav . "food_index_contents";
$t_food_stores		  	  = $mysqlPrefixSav . "food_stores";
$t_food_prices_currencies	  = $mysqlPrefixSav . "food_prices_currencies";
$t_food_favorites 		  = $mysqlPrefixSav . "food_favorites";
$t_food_measurements	 	  = $mysqlPrefixSav . "food_measurements";
$t_food_measurements_translations = $mysqlPrefixSav . "food_measurements_translations";

/*- Variables ------------------------------------------------------------------------ */
if(isset($_GET['inc'])) {
	$inc = $_GET['inc'];
	$inc = strip_tags(stripslashes($inc));
}
else{
	$inc = "";
}

/*- Content---------------------------------------------------------------------------- */
echo"
<h1>Export to Android Kotlin</h1>

<!-- Tabs -->
	<div class=\"tabs\">
		<ul>\n";
		$path = "_inc/food/_export_to_android_kotlin";
		if(!(is_dir("$path"))){
			echo"$path doesnt exists";
			die;
		}
		if ($handle = opendir($path)) {
			while (false !== ($file = readdir($handle))) {
				if ($file === '.') continue;
				if ($file === '..') continue;
				$file = str_replace(".php", "", $file);
				$file_saying = str_replace("_", " ", $file);
				$file_saying = ucfirst($file_saying);
				if($inc == ""){ $inc = "$file"; }

				echo"			";
				echo"<li><a href=\"index.php?open=food&amp;page=export_to_android_kotlin&amp;inc=$file&amp;editor_language=$editor_language&amp;l=$l\""; if($file == "$inc"){ echo" class=\"active\""; } echo">$file_saying</a></li>\n";
			}
		}
		echo"
		</ul>
	</div>
	<div class=\"clear\" style=\"height: 10px;\"></div>
<!-- //Tabs -->

<!-- Inc -->";
	if(file_exists("_inc/food/_export_to_android_kotlin/$inc.php")){
		include("_inc/food/_export_to_android_kotlin/$inc.php");
	}
	else{
		echo"<p>Server error 404</p>
		<p>_inc/food/_export_to_android_kotlin/$inc.php not found.</p>";
	}
echo"
<!-- //Inc -->
";

?>