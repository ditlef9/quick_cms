<?php 
/**
*
* File: food_diary/_tables.php
* Version 1.0.0
* Date 20:00 11.01.2021
* Copyright (c) 2021 Localhost
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/



/*- Food diary ---------------------------------------------------------------------------- */
$t_food_diary_goals 	  		= $mysqlPrefixSav . "food_diary_goals";
$t_food_diary_entires	  		= $mysqlPrefixSav . "food_diary_entires";
$t_food_diary_consumed_days  		= $mysqlPrefixSav . "food_diary_consumed_days";
$t_food_diary_consumed_hours  		= $mysqlPrefixSav . "food_diary_consumed_hours";
$t_food_diary_last_used  		= $mysqlPrefixSav . "food_diary_last_used";
$t_food_diary_user_adapted_view		= $mysqlPrefixSav . "food_diary_user_adapted_view";
$t_food_diary_meals_index 		= $mysqlPrefixSav . "food_diary_meals_index";
$t_food_diary_meals_items 		= $mysqlPrefixSav . "food_diary_meals_items";
$t_food_diary_lifestyle_selected_per_day = $mysqlPrefixSav . "food_diary_lifestyle_selected_per_day";

/*- Food ---------------------------------------------------------------------------- */
$t_food_liquidbase			= $mysqlPrefixSav . "food_liquidbase";

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

/*- Recipes ---------------------------------------------------------------------------- */
$t_recipes_liquidbase	 			= $mysqlPrefixSav . "recipes_liquidbase";

$t_recipes 	 			= $mysqlPrefixSav . "recipes";
$t_recipes_images			= $mysqlPrefixSav . "recipes_images";
$t_recipes_ingredients			= $mysqlPrefixSav . "recipes_ingredients";
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
$t_recipes_comments			= $mysqlPrefixSav . "recipes_comments";
$t_recipes_searches			= $mysqlPrefixSav . "recipes_searches";
$t_recipes_age_restrictions 	 	= $mysqlPrefixSav . "recipes_age_restrictions";
$t_recipes_age_restrictions_accepted	= $mysqlPrefixSav . "recipes_age_restrictions_accepted";

$t_recipes_pairing_loaded 		= $mysqlPrefixSav . "recipes_pairing_loaded";
$t_recipes_pairing_recipes		= $mysqlPrefixSav . "recipes_pairing_recipes";


$t_recipes_similar_loaded = $mysqlPrefixSav . "recipes_similar_loaded";
$t_recipes_similar_recipes = $mysqlPrefixSav . "recipes_similar_recipes";


$t_recipes_stats_views_per_month 	= $mysqlPrefixSav . "recipes_stats_views_per_month";
$t_recipes_stats_views_per_month_ips 	= $mysqlPrefixSav . "recipes_stats_views_per_month_ips";

$t_recipes_stats_views_per_year 	= $mysqlPrefixSav . "recipes_stats_views_per_year";
$t_recipes_stats_views_per_year_ips	= $mysqlPrefixSav . "recipes_stats_views_per_year_ips";

$t_recipes_stats_comments_per_month 	= $mysqlPrefixSav . "recipes_stats_comments_per_month";
$t_recipes_stats_comments_per_year 	= $mysqlPrefixSav . "recipes_stats_comments_per_year";
$t_recipes_stats_favorited_per_month 	= $mysqlPrefixSav . "recipes_stats_favorited_per_month";
$t_recipes_stats_favorited_per_year 	= $mysqlPrefixSav . "recipes_stats_favorited_per_year";

$t_recipes_stats_chef_of_the_month 	= $mysqlPrefixSav . "recipes_stats_chef_of_the_month";
$t_recipes_stats_chef_of_the_year 	= $mysqlPrefixSav . "recipes_stats_chef_of_the_year";

$t_webdesign_share_buttons 	= $mysqlPrefixSav . "webdesign_share_buttons";

?>