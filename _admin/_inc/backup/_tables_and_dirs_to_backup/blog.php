<?php
/**
*
* File: _admin/_inc/backup/_tables_and_dirs_to_backup/blog.php
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
$t_blog_liquidbase			= $mysqlPrefixSav . "blog_liquidbase";

$t_blog_titles 				= $mysqlPrefixSav . "blog_titles";
$t_blog_info 				= $mysqlPrefixSav . "blog_info";
$t_blog_default_categories		= $mysqlPrefixSav . "blog_default_categories";
$t_blog_categories			= $mysqlPrefixSav . "blog_categories";
$t_blog_posts 				= $mysqlPrefixSav . "blog_posts";
$t_blog_posts_tags 			= $mysqlPrefixSav . "blog_posts_tags";
$t_blog_posts_comments			= $mysqlPrefixSav . "blog_posts_comments";
$t_blog_posts_comments_likes_dislikes	= $mysqlPrefixSav . "blog_posts_comments_likes_dislikes";

$t_blog_posts_comments_replies			= $mysqlPrefixSav . "blog_posts_comments_replies";
$t_blog_posts_comments_replies_likes_dislikes	= $mysqlPrefixSav . "blog_posts_comments_replies_likes_dislikes";

$t_blog_images 				= $mysqlPrefixSav . "blog_images";
$t_blog_logos				= $mysqlPrefixSav . "blog_logos";

$t_blog_links_index			= $mysqlPrefixSav . "blog_links_index";
$t_blog_links_categories		= $mysqlPrefixSav . "blog_links_categories";

$t_blog_ping_list_per_blog		= $mysqlPrefixSav . "blog_ping_list_per_blog";

$t_blog_stats_most_used_categories	= $mysqlPrefixSav . "blog_stats_most_used_categories";



$tables_truncate_array = array();

$tables_backup_array = array(
			"$t_blog_liquidbase", 

			"$t_blog_titles", 
			"$t_blog_info", 
			"$t_blog_default_categories", 
			"$t_blog_categories", 
			"$t_blog_posts", 
			"$t_blog_posts_tags", 
			"$t_blog_posts_comments", 
			"$t_blog_posts_comments_likes_dislikes", 

			"$t_blog_posts_comments_replies", 
			"$t_blog_posts_comments_replies_likes_dislikes", 

			"$t_blog_images", 
			"$t_blog_logos", 

			"$t_blog_links_index", 
			"$t_blog_links_categories", 

			"$t_blog_ping_list_per_blog", 

			"$t_blog_stats_most_used_categories"

			);

/*- Directories ---------------------------------------------------------------------------- */
$directories_array = array("_uploads/blog");

?>