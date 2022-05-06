<?php
/**
*
* File: _admin/_inc/recipes/default.php
* Version 1.0
* Date 23:58 05.01.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Functions ------------------------------------------------------------------------ */
include("_functions/get_extension.php");


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

/*- Config ----------------------------------------------------------------------- */
if(!(file_exists("_data/recipes.php"))){
	$update_file="<?php
\$recipesActiveSav = \"1\";
\$recipesPrintLogoOnImagesSav = \"0\";
?>";

	$fh = fopen("_data/recipes.php", "w+") or die("can not open file");
	fwrite($fh, $update_file);
	fclose($fh);
}

/*- Check if setup is run ------------------------------------------------------------- */
$t_recipes_liquidbase	= $mysqlPrefixSav . "recipes_liquidbase";
$query = "SELECT * FROM $t_recipes_liquidbase LIMIT 1";
$result = mysqli_query($link, $query);
if($result !== FALSE){
	echo"
	<h1>Recipes</h1>

	
	<!-- Feedback -->
	";
	if($ft != ""){
		if($fm == "changes_saved"){
			$fm = "$l_changes_saved";
		}
		else{
			$fm = ucfirst($fm);
			$fm = str_replace("_", " ", $fm);
		}
		echo"<div class=\"$ft\"><span>$fm</span></div>";
	}
	echo"	
	<!-- //Feedback -->

	<!-- Recipes menu -->
	<div class=\"vertical\">
		<ul>
			";
			include("_inc/recipes/menu.php");
			echo"
		</ul>
	</div>
	<!-- //Recipes menu -->
	";


}
else{
	echo"
	<div class=\"info\"><p><img src=\"_design/gfx/loading_22.gif\" alt=\"loading_22.gif\" /> Running setup</p></div>
	<meta http-equiv=\"refresh\" content=\"1;url=index.php?open=$open&amp;page=tables&amp;refererer=default&amp;editor_language=$editor_language&amp;l=$l\" />
	";
} // setup has not runned
?>