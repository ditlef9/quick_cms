<?php
/**
*
* File: _admin/_inc/food/default.php
* Version 10:21 21.01.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}


/*- Tables ---------------------------------------------------------------------------- */
$t_food_liquidbase		  = $mysqlPrefixSav . "food_liquidbase";
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

/*- Functions ----------------------------------------------------------------------- */

/*- Config ----------------------------------------------------------------------- */
if(!(file_exists("_data/food.php"))){
	$update_file="<?php
\$foodPrintLogoOnImagesSav = \"0\";
?>";
	$fh = fopen("_data/food.php", "w+") or die("can not open file");
	fwrite($fh, $update_file);
	fclose($fh);
}



/*- Variables ------------------------------------------------------------------------ */

/*- Check if setup is run ------------------------------------------------------------- */
$query = "SELECT * FROM $t_food_liquidbase LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	echo"
	<h1>Food</h1>

	<!-- Backup menu -->
	<div class=\"vertical\">
		<ul>
			";
			include("_inc/food/menu.php");
			echo"
		</ul>
	</div>
	<!-- //Backup menu -->

	";
} // setup ok
else{
	echo"
	<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Running setup</p></div>
	<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=$open&amp;page=tables&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
	";
} // setup has not runned

?>