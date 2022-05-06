<?php
/**
*
* File: _admin/_inc/backup/_tables_and_dirs_to_backup/food.php
* Version 18:17 13.01.2022
* Copyright (c) 2022 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ------------------------------------------------------------------------ */
$t_food_liquidbase			= $mysqlPrefixSav . "food_liquidbase";

$t_food_titles				= $mysqlPrefixSav . "food_titles";

$t_food_categories		  	= $mysqlPrefixSav . "food_categories";
$t_food_categories_translations	  	= $mysqlPrefixSav . "food_categories_translations";
$t_food_index			 	= $mysqlPrefixSav . "food_index";
$t_food_index_stores		 	= $mysqlPrefixSav . "food_index_stores";
$t_food_index_ads		 	= $mysqlPrefixSav . "food_index_ads";
$t_food_index_tags		  	= $mysqlPrefixSav . "food_index_tags";
$t_food_index_prices		  	= $mysqlPrefixSav . "food_index_prices";
$t_food_index_contents		 	= $mysqlPrefixSav . "food_index_contents";
$t_food_index_ratings		 	= $mysqlPrefixSav . "food_index_ratings";
$t_food_stores		  	  	= $mysqlPrefixSav . "food_stores";
$t_food_prices_currencies	  	= $mysqlPrefixSav . "food_prices_currencies";
$t_food_favorites 		  	= $mysqlPrefixSav . "food_favorites";
$t_food_measurements	 	  	= $mysqlPrefixSav . "food_measurements";
$t_food_measurements_translations 	= $mysqlPrefixSav . "food_measurements_translations";
$t_food_countries_used	 	 	= $mysqlPrefixSav . "food_countries_used";
$t_food_integration	 	  	= $mysqlPrefixSav . "food_integration";
$t_food_age_restrictions 	 	= $mysqlPrefixSav . "food_age_restrictions";
$t_food_age_restrictions_accepted	= $mysqlPrefixSav . "food_age_restrictions_accepted";
$t_food_user_adapted_view		= $mysqlPrefixSav . "food_user_adapted_view";
$t_food_tags_unique			= $mysqlPrefixSav . "food_tags_unique";



$tables_truncate_array = array(
			"$t_food_age_restrictions_accepted", 
			"$t_food_user_adapted_view", 
			"$t_food_tags_unique");

$tables_backup_array = array(
			"$t_food_liquidbase", 

			"$t_food_titles", 

			"$t_food_categories", 
			"$t_food_categories_translations", 
			"$t_food_index", 
			"$t_food_index_stores", 
			"$t_food_index_ads", 
			"$t_food_index_tags", 
			"$t_food_index_prices", 
			"$t_food_index_contents", 
			"$t_food_index_ratings", 
			"$t_food_stores", 
			"$t_food_prices_currencies", 
			"$t_food_favorites", 
			"$t_food_measurements", 
			"$t_food_measurements_translations", 
			"$t_food_countries_used", 
			"$t_food_integration", 
			"$t_food_age_restrictions", 
			"$t_food_age_restrictions_accepted", 
			"$t_food_user_adapted_view", 
			"$t_food_tags_unique");

/*- Directories ---------------------------------------------------------------------------- */

$directories_array = array("_uploads/food");

?>