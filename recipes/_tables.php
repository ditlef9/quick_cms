<?php 
/**
*
* File: recipes/_tables.php
* Version 1.0.0
* Date 20:00 11.01.2021
* Copyright (c) 2021 Localhost
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/


/*- Tables ---------------------------------------------------------------------------- */
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

$t_recipes_main_ingredients			= $mysqlPrefixSav . "recipes_main_ingredients";
$t_recipes_main_ingredients_translations	= $mysqlPrefixSav . "recipes_main_ingredients_translations";

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

$t_recipes_user_adapted_view 	= $mysqlPrefixSav . "recipes_user_adapted_view";

$t_recipes_weekly_subscriptions 	= $mysqlPrefixSav . "recipes_weekly_subscriptions";
$t_recipes_weekly_subscriptions_checked_ingredients = $mysqlPrefixSav . "recipes_weekly_subscriptions_checked_ingredients";

/*- Tables ads ---------------------------------------------------------------------------- */
$t_ads_index	= $mysqlPrefixSav . "ads_index";


/*- Tables stats ---------------------------------------------------------------------------- */
$t_stats_comments_per_year 	= $mysqlPrefixSav . "stats_comments_per_year";
$t_stats_comments_per_month	= $mysqlPrefixSav . "stats_comments_per_month";
$t_stats_comments_per_week	= $mysqlPrefixSav . "stats_comments_per_week";

/*- Tables languages -------------------------------------------------------------------------- */
$t_languages_countries	= $mysqlPrefixSav . "languages_countries";
?>